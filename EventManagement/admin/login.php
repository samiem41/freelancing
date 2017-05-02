<!DOCTYPE html>
<html>
<title>Admin Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
include 'library.php';
?>
<body>

<header class="w3-container w3-teal">
  <h1>Admin Login</h1>
</header>

<div class="w3-container w3-half w3-margin-top">

<form class="w3-container w3-card-4" method="post" action="manage.php">

<p>
<input class="w3-input" type="text" style="width:90%" required>
<label class="w3-label w3-validate">Name</label></p>
<p>
<input class="w3-input" type="password" style="width:90%" required>
<label class="w3-label w3-validate">Password</label></p>

<p>
<input id="milk" class="w3-check" type="checkbox" checked="checked">
<label class="w3-validate">Stay logged in</label></p>

<p>
<button class="w3-btn w3-section w3-teal w3-ripple"> Log in </button></p>

</form>

</div>

</body>
</html> 
<?php 

?>