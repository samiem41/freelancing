<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

include 'startsession.php';
include 'database.php';
include 'library.php';
include 'timezone.php';
include 'util/util.php';
?>
<html>
<body class="w3-light-grey">
<?php 
include 'backmenu.php';
?>
<?php 
$dateformat = "Y-m-d";
$startdate = date($dateformat);
$enddate = $startdate;
$contents;
$genres;
$timing = 0;
$pageIndex=0;
//default set up
$venue =array();
$venue["id"] = 0;

$userSelectedValidDate = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (isset($_POST['btnNext'])) {
		if(isset($_SESSION["PAGE_INDEX"])){
			$_SESSION["PAGE_INDEX"] = $_SESSION["PAGE_INDEX"] + 1;
		}
		else{
			$_SESSION["PAGE_INDEX"] = 1;
		}
	}
	
	if(isset($_POST['btnPrevious'])) {
		if(isset($_SESSION["PAGE_INDEX"])){
			$_SESSION["PAGE_INDEX"] = $_SESSION["PAGE_INDEX"] - 1;
		}
	}
	
	if(isset($_POST['btnReset'])) {
		$_SESSION["PAGE_INDEX"] = 0;
	}
	
	if(isset($_POST["show_date"])) {
		if(!strtotime($_POST["show_date"])) {
			
			//date seems invalid
			$day_limit = 7; # No.of days
			$yesterday = new DateTime('now');
			$yesterday->sub(new DateInterval('P1D'));
			$yesterday->add(new DateInterval('P7D'));
			$startdate = $yesterday->format($dateformat);
			$enddate = $startdate;
		}
		else {
			$userSelectedValidDate = true;
			$userSelectedDate = new DateTime($_POST["show_date"]);
			$startdate = $userSelectedDate->format($dateformat);
			$enddate = $startdate;
		}
		
	}
	else {
	 /*TODO:if no date was selected we show all shows between 1 week range and then increase it via pagination
	 * so basically start date and end date will vary
	 * 
	 */
		
		$day_limit = 7; # No.of days
	    $added_timestamp = strtotime('+'.$day_limit.' day', time());
		$startdate = date('Y-m-d', $added_timestamp);
		$enddate = $startdate;
	}
	
	if(isset($_POST["content_list"])) {
		$contentlist = $_POST["content_list"];
		$contents = implode(', ', $contentlist);
	}
	
	if(isset($_POST["genre"])) {
		$genre = $_POST["genre"];
	}
	
	if(isset($_POST["timing"])) {
		$timing = $_POST["timing"];
		$timing = unserialize(base64_decode($timing));
	}
	
	if(isset($_POST["venues"])) {
		$venue = $_POST["venues"];
		$venue = unserialize(base64_decode($venue));
	}
	
	if(isset($_POST["shows"])) {
		$show = $_POST["shows"];
	}
}


$today = date("Y-m-d");
$sql = "select distinct(s.id) from shows s, 
performance_pattern p,venue v,show_timing t,show_genres g,genre g1,show_contents c,content c1
where s.performance_pattern_id = p.id
and p.venue_id = v.id and p.timing_id = t.id
and s.show_status = 'Active' and p.is_valid = 1
and s.show_start_date <='". $startdate . "' and show_end_date >='". $enddate .
"' and s.id = g.show_id and g.genre_id = g1.id
and c.show_id = s.id and c1.id = c.content_id";

if(isset($contents)){
	$sql = $sql . " and c.content_id in (" .$contents.")";
}

if(isset($genre)){
	if($genre != 0){
		$sql = $sql . " and g.genre_id = ".$genre ;
	}
}

if(isset($show)){
	if($show != 0){
		$sql = $sql . " and s.id = ".$show ;
	}
}

echo "<div style='padding-left:20px'><br> the query to run is : ".$sql."</div>";

$showIds = array();

$shows = array();
if ($result = $conn->query($sql)) {

    /* determine number of rows result set */
    $row_cnt = $result->num_rows;

    if($row_cnt>0) {
    	while($row = $result->fetch_assoc()) {
    		array_push($showIds,$row["id"]);
    	}	
   
	$commaList = implode(', ', $showIds);
	
	$showHolidayList = showHolidayList($commaList,$conn);
	$showOverridesList = showOverridesList($commaList,$conn);
	$sql = "select s.id,s.show_name,v.id venueId,t.id timingId,v.venue_name,t.show_timing,t.start_time,t.end_time from shows s,performance_pattern p,show_timing t,venue v
	where s.performance_pattern_id = p.id and p.timing_id = t.id
	and p.venue_id = v.id and s.id in(".$commaList.")";
	
	if ($result = $conn->query($sql)) {
		$row_cnt = $result->num_rows;
		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {
				
				//for each show we need to iterate between start date and end date!
				if($userSelectedValidDate == true){
					
					$start = new DateTime($startdate);
					$end = new DateTime($startdate);
					$end->add(new DateInterval('P1D'));
				}
				else{
					
					$start = new DateTime('now');
					//$start->sub(new DateInterval('P1D'));
					$end = DateTime::createFromFormat('Y-m-d', $startdate);
					
					if($_SESSION["PAGE_INDEX"] > 0){
						//this means we want paginated behaviour!
						$start->add(new DateInterval('P'.$_SESSION["PAGE_INDEX"].'W'));
						$end->add(new DateInterval('P'.$_SESSION["PAGE_INDEX"].'W'));
					}
					
				}
				
				$interval = DateInterval::createFromDateString('1 day');
				$period = new DatePeriod($start, $interval, $end);
				foreach ( $period as $dt ) {
					
					$show_date = $dt->format($dateformat);
					
					if(filterRecord($row,$showHolidayList,$show_date)){
						/*
						 * TODO: one more level of validation if we have overrides made.
						 * in case user selected Venue 1 and our show result data has an override of different venue, then we will skip
						 * similarly for timing as well.
						*/
						
						$val = array();
						$val["show_date"] = formatDateInBritishPattern($show_date);
						$val["name"] = $row["show_name"];
						$val["weekday"] = date('l', strtotime($show_date)); 
						$val["timing"] = $row["show_timing"];
						$val["venue"] = $row["venue_name"];
						$val["venue_id"] = $row["venueId"];
						$val["timing_id"] = $row["timingId"];
						$val["start_time"] = $row["start_time"];
						$val["end_time"] = $row["end_time"];
						
						$shouldOverrideApply = false;
						
						if(isset($showOverridesList[$row["id"]])) {
							//means we have some override hence this will take preference!
							$overrides = $showOverridesList[$row["id"]];
							$show_override_date = $overrides["show_date"];
							
							if(isSameDate($show_override_date,$show_date)){
								//so, for this record the overrides needs to apply!
								$shouldOverrideApply = true;
							}
						}
						
						if($shouldOverrideApply) {
							if($overrides["show_timing"] != NULL){
								$val["timing"] = $overrides["show_timing"];
								$val["timing_id"] = $overrides["timing_id"];
								$val["start_time"] = $row["start_time"];
								$val["end_time"] = $row["end_time"];
							}
							if($overrides["venue_name"] != NULL){
								$val["venue"] = $overrides["venue_name"];
								$val["venue_id"] = $overrides["venue_id"];
							}
						}
						
						/*now the final level of validation should apply
						 * based on user selection from UI
						 */
						if(isShowConfirmingToUserSelection($val,$timing,$venue)) {
						  $shows[$row["id"]."$".$show_date] = $val;
						}
						
					}
				}
			}
		}
	}
    }
    
    /* close result set */
    $result->close();
}

function filterRecord($row,$show_holiday_list,$show_date) {
	global $conn;
	echo "day of week";
	$sql = "select s.id,s.show_start_date,r.recurrence_name,n.sunday,n.monday,n.tuesday,n.wednesday,n.thursday,n.friday,n.saturday from
	shows s, performance_pattern p, recurrence r,recurrence_pattern n where p.recurrence_id = r.id
	and n.id = r.recurrence_pattern and s.performance_pattern_id = p.id and s.id = ".$row["id"];
	
	if ($result = $conn->query($sql)) {
		$row_cnt = $result->num_rows;
		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {
				$holiday_dates = array();
				
				if(array_key_exists($row["id"],$show_holiday_list)){
					$holiday_dates = $show_holiday_list[$row["id"]];
				}
				
				$day_of_week = date('l', strtotime($show_date));
				
				if($row["recurrence_name"] == 'day_of_week') {
					// for weekly pattern we can trust the week days and return true for now unless we have an override
					
					//first check if we have a holiday
					if(!isHoliday($holiday_dates,$show_date)) {
						//now verify if this weekday is marked in pattern
						if($row[strtolower($day_of_week)] == 1) {
							return true;
						}
					}
				}
				
				//TODO: handle bi-weekly pattern and monthly pattern as well!
				if($row["recurrence_name"] == 'bi_weekly') {
					$weeks = getNumberOfWeeks($row,$show_date);
					if($weeks % 2 == 0){
						//this week has a chance of occurence based on weekdays pattern now!
						if(!isHoliday($holiday_dates,$show_date)) {
							//now verify if this weekday is marked in pattern
							if($row[strtolower($day_of_week)] == 1) {
								return true;
							}
						}
					}
				}
				
				return false;
			}
		}
	}
	
	$result->close();
}

/* close connection */
$conn->close();

?>
<div class="w3-container">

	<h2>Shows in Benidorm</h2>
	<div style ="float: right;">
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			
				<input type="submit" name="btnReset" value="Reset" />
				
				<span style="padding-left:10px">
				
				<?php 
				
				echo "<input type='submit' name='btnPrevious' value='Previous'";
				
				if($_SESSION["PAGE_INDEX"] == 0){
					echo " disabled = true";
				}
				
				echo "/>";
				
				?>				
				
				</span>
				
				<span style="padding-left:10px">
					<input type="submit" name="btnNext" value="Next" />
				</span>
			
		</form>
	</div>
	<br>
	<br>
	<div class="w3-responsive">
		<table class="w3-table-all">
		
		<tr>
		  <th>Show Date</th>
		  <th>Week Day</th>
		  <th>Show Name</th>
		  <th>Timing</th>
		  <th>Venue</th>
		</tr>
		
		<?php 
		
		foreach($shows as $key=>$value) {
		echo "<tr>";
		echo "<td>";
		echo "{$shows[$key]['show_date']} </td>";
		echo "<td>";
		echo "{$shows[$key]['weekday']} </td>";
		echo "<td>";
			echo "{$shows[$key]['name']} </td>";
	    echo "<td>";
			echo "{$shows[$key]['timing']} </td>";
		echo "<td>";
			echo "{$shows[$key]['venue']} </td>";
		echo "</tr>";
		}
		
		?>
		
		</table>
	</div>

</div>
</body>
</html>
