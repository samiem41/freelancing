<?php
include 'startsession.php';
$_SESSION["PAGE_INDEX"] = 0;
?>

<!DOCTYPE html>
<html>
<title>Benidorm Shows!</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
include 'database.php';
include 'library.php';
include 'timezone.php';
include 'util/util.php';
?>

<body class="w3-light-grey">

<?php 
include 'menu.php';

$timings = getShowTiming($conn);
$genres = getGenres($conn);
$venues = getVenues($conn);
$contents = getContents($conn);
$shows = getShows($conn);

?>

<!-- Header -->
<header class="w3-display-container w3-content" style="max-width:1500px;">
  <img class="w3-image" src="images/shows.jpg" alt="The Benidorm Shows" style="min-width:1000px" width="1500" height="800">
  <div class="w3-display-left w3-padding w3-col l6 m8">
    <div class="w3-container w3-red">
      <h2><i class="fa fa-bed w3-margin-right"></i>Shows</h2>
    </div>
    <div class="w3-container w3-white w3-padding-16">
      <form method="post" action="shows.php">
        <div class="w3-row-padding" style="margin:0 -16px;">
          <div class="w3-half w3-margin-bottom">
            <label><i class="fa fa-calendar-o"></i> Show date</label>
            <input class="w3-input w3-border" type="text" placeholder="DD MM YYYY" name="show_date">
          </div>
          <div class="w3-half">
          	  <label><i class="glyphicon glyphicon-time"></i> Show timing:</label>
          	  
	          <select class="w3-select" name="timing">
	          <option value="0" selected>Any</option>;
	           <?php
	              
				  foreach($timings as $key=>$value) {
				  	 $ser = base64_encode(serialize($timings[$key]));
				     echo "<option value=".$ser.">".$timings[$key]['timing_name']."</option>"; 	
				  }
			   ?>
			  </select>
			  
          </div>
        </div>
        <div class="w3-row-padding" style="margin:8px -16px;">
          <div class="w3-half w3-margin-bottom">
              <label><i class="fa fa-music"></i> Genre:</label>
	          <select class="w3-select" name="genre">
				  <option value="0" selected>Any</option>
				  
				    <?php
						  foreach($genres as $key=>$value) {
						     echo "<option value=".$key.">".$genres[$key]."</option>"; 	
						  }
			  		 ?>
				  
			  </select>
          </div>
          
          <div class="w3-half">
          	  <label><i class="glyphicon glyphicon-time"></i> Venues:</label>
          	  
	          <select class="w3-select" name="venues">
		           <option value="0" selected>Any</option>
				  <?php
	 					foreach($venues as $key=>$value) {
	 						 $ser = base64_encode(serialize($venues[$key]));
							 echo "<option value=".$ser.">".$venues[$key]["venue_name"]."</option>"; 	
						}
				   ?>
			  </select>
			  
          </div>
          
        </div>
        <div class="w3-row-padding" style="margin:8px -16px;">
          <div class="w3-half w3-margin-bottom">
          
          	 <label><i class="fa fa-music"></i> Show Names:</label>
	          <select class="w3-select" name="shows">
				  <option value="0" selected>Any</option>
				  
				    <?php
						  foreach($shows as $key=>$value) {
						     echo "<option value=".$key.">".$shows[$key]."</option>"; 	
						  }
			  		 ?>
				  
			  </select>
          
          </div>
        </div>
        <div class="w3-row-padding" style="margin:8px -16px;">
        
        	<?php
 				foreach($contents as $key=>$value) {
 				   echo "<input class='w3-check' type='checkbox' name='content_list[]' checked " . "value='" . $key ."'>";
 				   echo "<label class='w3-validate'>".$contents[$key]['content_name']."</label>";
				}
			?>
				   
 	    
		</div>
        <button class="w3-btn w3-dark-grey" type="submit"><i class="fa fa-search w3-margin-right"></i> Search availability</button>
      </form>
    </div>
  </div>
</header>

 <!--  <div class="w3-container w3-padding-32 w3-black w3-opacity w3-card-2 w3-hover-opacity-off" style="margin:32px 0;">
    <h2>Get the best offers first!</h2>
    <p>Join our newsletter.</p>
    <label>E-mail</label>
    <input class="w3-input w3-border" type="text" placeholder="Your Email address">
    <button type="button" class="w3-btn w3-red w3-margin-top">Subscribe</button>
  </div>  

  <div class="w3-container" id="contact">
    <h2>Contact</h2>
    <p>If you have any questions, do not hesitate to ask them.</p>
    <i class="fa fa-map-marker w3-text-red" style="width:30px"></i> Benidorm, Spain<br>
    <i class="fa fa-phone w3-text-red" style="width:30px"></i> Phone: +00 151515<br>
    <i class="fa fa-envelope w3-text-red" style="width:30px"> </i> Email: derekbelivins@mail.com<br>
    <form action="form.asp" target="_blank">
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Name" required name="Name"></p>
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Email" required name="Email"></p>
      <p><input class="w3-input w3-padding-16 w3-border" type="text" placeholder="Message" required name="Message"></p>
      <p><button class="w3-btn w3-padding-large" type="submit">SEND MESSAGE</button></p>
    </form>
  </div> -->

<!-- End page content -->

<?php 
include 'footer.php';
?>
<!-- Add Google Maps -->
<!-- <script>
function myMap()
{
  myCenter=new google.maps.LatLng(41.878114, -87.629798);
  var mapOptions= {
    center:myCenter,
    zoom:12, scrollwheel: false, draggable: false,
    mapTypeId:google.maps.MapTypeId.ROADMAP
  };
  var map=new google.maps.Map(document.getElementById("googleMap"),mapOptions);

  var marker = new google.maps.Marker({
    position: myCenter,
  });
  marker.setMap(map);
}
</script> 
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu-916DdpKAjTmJNIgngS6HL_kDIKU0aU&callback=myMap"></script>-->
<!--
To use this code on your website, get a free API key from Google.
Read more at: http://www.w3schools.com/graphics/google_maps_basic.asp
-->

</body>
</html>

<?php

?>