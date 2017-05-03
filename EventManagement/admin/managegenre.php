<?php
include 'library.php';
include '../database.php';
include '../util/util.php';


$genres = getGenres($conn);
?>

<html>

<header class="w3-container w3-teal">
  <h1>Manage Genres</h1>
</header>


<div class="w3-container">

	<h2>List of Genres:</h2>
	
	<div class="w3-responsive">
		<table class="w3-table-all">
		
		<tr>
		  <th>Genre Id</th>
		  <th>Genre Name</th>
		  <th>Image Name</th>
		</tr>
		
		<?php 
		
		foreach($genres as $key=>$value) {
			echo "<tr>";
			echo "<td>";
			echo "{$genres[$key]['id']} </td>";
			echo "<td>";
			echo "{$genres[$key]['genre_name']} </td>";
			echo "<td>";
				echo "{$genres[$key]['image']} </td>";
			echo "</tr>";
		}
		
		?>
		
		</table>
	</div>
	
	<br>
	
	<form method="post" action="creategenre.php">
			
		<input type="submit" name="btnCreateGenre" value="Create Genre" />
			
	</form>

</div>

</html>
