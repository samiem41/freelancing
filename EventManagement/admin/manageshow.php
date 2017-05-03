<?php
include 'library.php';
include '../database.php';
include '../util/util.php';


$shows = getShows($conn);
?>

<html>

<header class="w3-container w3-teal">
  <h1>Manage Shows</h1>
</header>


<div class="w3-container">

	<h2>List of Shows:</h2>
	
	<div class="w3-responsive">
		<table class="w3-table-all">
		
		<tr>
		  <th>Show Id</th>
		  <th>Show Name</th>
		  <th>Show Start Date</th>
		  <th>Show End Date</th>
		  <th>Show Status</th>
		  <th>Show Description</th>
		</tr>
		
		<?php 
		
		foreach($shows as $key=>$value) {
			echo "<tr>";
			echo "<td>";
			echo "{$shows[$key]['id']} </td>";
			echo "<td>";
			echo "{$shows[$key]['show_name']} </td>";
			echo "<td>";
			echo "{$shows[$key]['start_date']} </td>";
			echo "<td>";
			echo "{$shows[$key]['end_date']} </td>";
			echo "<td>";
			echo "{$shows[$key]['status']} </td>";
			echo "<td>";
			echo "{$shows[$key]['description']} </td>";
			echo "</tr>";
		}
		
		?>
		
		</table>
	</div>
	
	<br>
	
	<form method="post" action="createshow.php">
			
		<input type="submit" name="btnCreateShow" value="Create Show" />
			
	</form>

</div>

</html>
