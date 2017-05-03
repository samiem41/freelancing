<?php 

function isHoliday($holiday_dates, $date2){
	$timestamp2 = strtotime($date2);
	
	foreach($holiday_dates as $holiday) {
		$timestamp1 = strtotime($holiday);
		if($timestamp1 == $timestamp2){
			return true;
		}	
	}
	
	return false;
}

function isSameDate($date1, $date2){
	$timestamp1 = strtotime($date1);
	$timestamp2 = strtotime($date2);
	if($timestamp1 == $timestamp2){
		return true;
	}
	return false;
}

function isShowConfirmingToUserSelection($showdata,$timing,$venue){
	if(isset($timing)){
		if($timing["id"] != 0){
			//is special timing filter
			if($timing["timing_name"] == 'Tonight'){
				
				if(($showdata["start_time"]) >= $timing["start_time"] || $showdata["end_time"] <= $timing["end_time"]){
					return true;
				}
				else{
					return false;
				}
				
			}
			
			if($showdata["timing_id"] != $timing["id"]){
				return false;
			}
		}
	}
	
	if(isset($venue)) {
		if($venue["id"] != 0) {
			if($showdata["venue_id"] != $venue["id"]) {
				return false;
			}
		}
	}
	
	return true;	
}

function showHolidayList($shows_id_list,$conn) {
	$show_holidays = array();

	$sql = "select s.id,o.holiday_date from shows s, show_holiday_overrides o where o.show_id = s.id and s.id in (".$shows_id_list.")";
	if ($result = $conn->query($sql)) {
		while($row = $result->fetch_assoc()) {
			if(isset($show_holidays[$row["id"]])){
				$existingSet = $show_holidays[$row["id"]];
				array_push($existingSet, $row["holiday_date"]);
				$show_holidays[$row["id"]] = $existingSet;
			}
			else{
				$value = array();
				array_push($value, $row["holiday_date"]);
				$show_holidays[$row["id"]] = $value;
			}
		}
	}
	return $show_holidays;
}


function showOverridesList($shows_id_list,$conn) {
	$show_overrides = array();

	$sql = " select s.id,o.show_date,o.venue_id,o.timing_id,t.show_timing,t.start_time,t.end_time,v.venue_name 
			from shows s , shows_overrides o left outer join show_timing t on o.timing_id = t.id 
			left outer join venue v on o.venue_id = v.id
			where o.show_id = s.id
			and s.id in (".$shows_id_list.")";
	
	if ($result = $conn->query($sql)) {
		while($row = $result->fetch_assoc()) {
			$overrides = array();
			$overrides["show_date"] = $row["show_date"];
			$overrides["venue_id"] = $row["venue_id"];
			$overrides["timing_id"] = $row["timing_id"];
			$overrides["show_timing"] = $row["show_timing"];
			$overrides["venue_name"] = $row["venue_name"];
			$overrides["start_time"] = $row["start_time"];
			$overrides["end_time"] = $row["end_time"];
			$show_overrides[$row["id"]] = $overrides;
		}
	}

	return $show_overrides;
}


function getShowTiming($conn){
	$timings = array();
	$sql = "select id,show_timing,start_time,end_time from show_timing";
	
	if ($result = $conn->query($sql)) {
	
		/* determine number of rows result set */
		$row_cnt = $result->num_rows;
	
		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {
				$timingdata = array();
				$timingdata["timing_name"] = $row["show_timing"];
				$timingdata["start_time"] = $row["start_time"];
				$timingdata["end_time"] = $row["end_time"];
				$timingdata["id"] = $row["id"];
				$timings[$row["id"]] = $timingdata;
			}
		}
	}
	
	$result->close();
	
	return $timings;
}

function getGenres($conn){
	$genres = array();
	$sql = "select id,genre_name,image from genre";

	if ($result = $conn->query($sql)) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {

				$genredata = array();
				$genredata["genre_name"] = $row["genre_name"];
				$genredata["image"] = $row["image"];
				$genredata["id"] = $row["id"];
				$genres[$row["id"]] = $genredata;
			}
		}
	}

	$result->close();
	return $genres;
}

function getVenues($conn){
	$venues = array();
	$sql = "select id,venue_name,image from venue";

	if ($result = $conn->query($sql)) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {
				$venuedata = array();
				$venuedata["venue_name"] = $row["venue_name"];
				$venuedata["image"] = $row["image"];
				$venuedata["id"] = $row["id"];
				$venues[$row["id"]] = $venuedata;
			}
		}
	}

	$result->close();
	return $venues;
}

function getContents($conn){
	$contents = array();
	$sql = "select id,content_name from content";

	if ($result = $conn->query($sql)) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {
				$contentdata = array();
				$contentdata["id"] = $row["id"];
				$contentdata["content_name"] = $row["content_name"];
				
				$contents[$row["id"]] = $contentdata;
			}
		}
	}

	$result->close();
	return $contents;
}

function getShows($conn){
	$shows = array();
	$sql = "select id,show_name,show_start_date,show_end_date,show_status,show_description from shows where show_status = 'Active'";

	if ($result = $conn->query($sql)) {

		/* determine number of rows result set */
		$row_cnt = $result->num_rows;

		if($row_cnt>0) {
			while($row = $result->fetch_assoc()) {
				$showdata = array();
				$showdata["id"] = $row["id"];
				$showdata["show_name"] = $row["show_name"];
				$showdata["start_date"] = $row["show_start_date"];
				$showdata["end_date"] = $row["show_end_date"];
				$showdata["status"] = $row["show_status"];
				$showdata["description"] = $row["show_description"];

				$shows[$row["id"]] = $showdata;
			}
		}
	}

	$result->close();
	return $shows;
}

function getNumberOfWeeks ($row,$show_date){
	$show_start_date = new DateTime($row["show_start_date"]);
	$tentative_show_date = new DateTime($show_date);
	$interval = $show_start_date->diff($tentative_show_date);
	$weeks = (int)(($interval->days) / 7);
	return $weeks;
}

function formatDateInBritishPattern($show_date){
	$date = date_create($show_date);
	return date_format($date,"d/m/Y");
}

function saveVenue($venueName,$imageName,$conn) {
	$recordSaved = false;
	
	if($imageName == NULL){
		$imageName = '';
	}
	$sql = "INSERT INTO VENUE (venue_name, image)
			VALUES ('$venueName','$imageName')";
	
	if ($conn->query($sql) === TRUE) {
		$recordSaved = true;
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	return $recordSaved;
	
}

function saveGenre($genreName,$imageName,$conn) {
	$recordSaved = false;
	
	if($imageName == NULL){
		$imageName = '';
	}
	$sql = "INSERT INTO GENRE (genre_name, image)
			VALUES ('$genreName','$imageName')";
	
	if ($conn->query($sql) === TRUE) {
		$recordSaved = true;
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	return $recordSaved;
	
}

?>