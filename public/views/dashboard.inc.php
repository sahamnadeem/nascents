<!DOCTYPE html>
<html>
<head>
	<title>Saham</title>
	<script type="text/javascript" src="../public/assets/js/home.js"></script>
</head>
<body>
<a href="/ceptraproj/logout/">Logout</a>

<p id="demo"></p>

<script type="text/javascript">
var x = document.getElementById('demo');
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
} else { 
    x.innerHTML = 'Geolocation is not supported by this browser.';
}
function showPosition(position) {
    x.innerHTML = 'Latitude: '+ position.coords.latitude + 
    '<br>Longitude: ' + position.coords.longitude;
    (async () => {
	  const rawResponse = await fetch('/ceptraproj/users/location/', {
	    method: 'PUT',
	    headers: {
	      'Accept': 'application/json',
	      'Content-Type': 'application/json',
	      'Authorization': 'Bearer <?php echo $_SESSION['access_token']; ?>'
	    },
	    body: JSON.stringify({lat: position.coords.latitude , lng : position.coords.longitude})
	  });
	  const content = await rawResponse.json();
	  console.log(content);
	  return content;
	})();
}

</script>

<?php
$location = "alied bank,ghori town,islamabad";
$data = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($location)."&sensor=true");
 
 $longitude = json_decode($data)->results[0]->geometry->location->lng;
 $latitude = json_decode($data)->results[0]->geometry->location->lat;
 echo "$latitude, $longitude";
?>
</body>
</html>