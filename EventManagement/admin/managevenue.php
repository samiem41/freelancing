<?php
include 'library.php';
include '../database.php';
include '../util/util.php';


$venues = getVenues($conn);
?>

<html>

<header class="w3-container w3-teal">
  <h1>Manage Venues</h1>
</header>


<div class="w3-container">

	<h2>List of Venues:</h2>
	
	<div class="w3-responsive">
		<table class="w3-table-all">
		
		<tr>
		  <th>Venue Id</th>
		  <th>Venue Name</th>
		  <th>Image Name</th>
		</tr>
		
		<?php 
		
		foreach($venues as $key=>$value) {
			echo "<tr>";
			echo "<td>";
			echo "{$venues[$key]['id']} </td>";
			echo "<td>";
			echo "{$venues[$key]['venue_name']} </td>";
			echo "<td>";
				echo "{$venues[$key]['image']} </td>";
			echo "</tr>";
		}
		
		?>
		
		</table>
	</div>
	
	<br>
	
	<form method="post" action="createvenue.php">
			
		<input type="submit" name="btnCreateVenue" value="Create Venue" />
			
	</form>

</div>

</html>
