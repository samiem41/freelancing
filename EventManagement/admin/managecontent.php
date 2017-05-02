<?php
include 'library.php';
include '../database.php';
include '../util/util.php';


$contents = getContents($conn);
?>

<html>

<header class="w3-container w3-teal">
  <h1>Manage Contents</h1>
</header>


<div class="w3-container">

	<h2>List of Contents:</h2>
	
	<div class="w3-responsive">
		<table class="w3-table-all">
		
		<tr>
		  <th>Content Id</th>
		  <th>Content Name</th>
		</tr>
		
		<?php 
		var_dump($contents);
		foreach($contents as $key=>$value) {
			echo "<tr>";
			echo "<td>";
			echo "{$contents[$key]['id']} </td>";
			echo "<td>";
			echo "{$contents[$key]['content_name']} </td>";
			echo "</tr>";
		}
		
		?>
		
		</table>
	</div>
	
	<br>
	
	<form method="post" action="createcontent.php">
			
		<input type="submit" name="btnCreateContent" value="Create Content" />
			
	</form>

</div>

</html>
