<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Geopal Test</title>
  </head>
  <body>
    <div id="map"></div>
<!-- Replace the value of the key parameter with your own API key. -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwI2qwx3uN_Q2v941johzEwbaUANHTqO4&callback=initMap">
</script>
<script type="text/javascript">
  var map;

function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: {
      lat: 53.3498,
      lng: -6.2603
    }
  });

  // Load GeoJSON.
  // map.data.loadGeoJson(
  //    'https://storage.googleapis.com/mapsdevsite/json/google.json');
  map.data.loadGeoJson('https://data.smartdublin.ie/dataset/58969481-417e-4f5a-b8ea-18b56419d0ed/resource/a38b3d50-96ae-495e-ae69-899d833404cf/download/dccrdpandd.geojson');

  map.data.setStyle(function(feature) {
    var color = "green";

   return {
   		//icon: 'http://www.myiconfinder.com/uploads/iconsets/256-256-f900504cdc9f243b1c6852985c35a7f7.png',
      fillColor: color,
      strokeWeight: 1
    }
  });

}


</script>

</body>
</html>
