<?php

include 'library.php';
include '../database.php';
include '../util/util.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['btnSave'])) {
		if(isset($_POST['venue'])){
			$venueName = $_POST['venue'];
		}
		
		if(isset($_POST['image'])){
			$imageName = $_POST['image'];
		}
		
		saveVenue($venueName,$imageName,$conn);
		$URL = 'managevenue.php';
		echo "<script>location.href='$URL'</script>";
	}
}
?>


<html>

<script type="text/javascript">

function cancel(){
	location.href='managevenue.php'
}

</script>
<div class="w3-card-4">

<div class="w3-container w3-teal" >
  <h2>Create Venue:</h2>
</div>

<form class="w3-container paddingTop20" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	<label class="w3-label">Venue Name</label>
	<input class="w3-input" name="venue" type="text" required>

	<br>
	
	<label class="w3-label">Venue Image</label>
	<input class="w3-input" name="image" type="text">
			
	<br>
	
	<input class="w3-btn w3-teal" type="submit" name="btnSave" value="Save" />
	<input class="w3-btn w3-teal btnCancel" type="submit" onClick="cancel()" name="btnCancel" value="Cancel" />
</form>
<br>
<br>
</div>

</html>
