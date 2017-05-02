<?php

include 'library.php';
include '../database.php';
include '../util/util.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if (isset($_POST['btnSave'])) {
		if(isset($_POST['content'])){
			$contentName = $_POST['content'];
		}
		
		saveContent($contentName,$conn);
		$URL = 'managecontent.php';
		echo "<script>location.href='$URL'</script>";
	}
}
?>


<html>

<script type="text/javascript">

function cancel(){
	location.href='managecontent.php'
}

</script>
<div class="w3-card-4">

<div class="w3-container w3-teal" >
  <h2>Create Content:</h2>
</div>

<form class="w3-container paddingTop20" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	<label class="w3-label">Content Name</label>
	<input class="w3-input" name="venue" type="text" required>

	<br>
	
	<input class="w3-btn w3-teal" type="submit" name="btnSave" value="Save" />
	<input class="w3-btn w3-teal btnCancel" type="submit" onClick="cancel()" name="btnCancel" value="Cancel" />
</form>
<br>
<br>
</div>

</html>
