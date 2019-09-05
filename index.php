<?php session_start(); ?>
<?php
if(isset($_FILES['jsonfile'])){
    $file_name = $_FILES['jsonfile']['name'];
    $file_tmp =$_FILES['jsonfile']['tmp_name'];
    $ext = strtolower(end(explode('.',$_FILES['jsonfile']['name'])));
    if($ext != "geojson" && $ext != "geoJSON"){
       $error = "Wrong File. Only geojson or geoJSON allowed.";
    }
    if($error == null){
       move_uploaded_file($file_tmp,"geoJson/".$file_name);
       $filetoload = $file_name;
       $_SESSION['fileloaded'] = $filetoload;
       echo "Success";
    }else{
       echo "<span style='color: red;'>".$error."</span>";
    }
 }
 if (isset($_SESSION['fileloaded'])){
   $geojsonfile = $_SESSION['fileloaded'];
 }

 if (isset($_POST['color'])) {
   $color = $_POST['color'];
 }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <title>Geopal Test</title>
  </head>
  <body>
    <div id="map"></div>

    <div id="options">
      <form method="POST" action="index.php" enctype="multipart/form-data">
        Upload GeoJSON file: <input type="file" name="jsonfile" onchange="form.submit()">
      </form>
      <form method="POST" action="index.php">
        Marker Color:
        <select name="color" onchange="form.submit()">
          <option value="red" <?php if ($color=="red") echo "selected";?>> Red </option>
          <option value="blue" <?php if ($color=="blue") echo "selected";?>> Blue </option>
          <option value="green" <?php if ($color=="green") echo "selected";?>> Green </option>
        </select>
      </form>
      <button onclick="findPoint()" id="findbtn" disabled> Find Points </button>
    </div>

    <div id="foundmarkers" display: none;>

    </div>

<!-- Replace the value of the key parameter with your own API key. -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAwI2qwx3uN_Q2v941johzEwbaUANHTqO4&callback=initMap">
</script>
<script type="text/javascript">

  var map;
  var poly;


  //initMao and Zoom Dublin
  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 12,
      center: {
        lat: 53.3498,
        lng: -6.2603
      }
    });

    var infowindow = new google.maps.InfoWindow();

    // Load GeoJSON.
    // map.data.loadGeoJson(
    //    'https://storage.googleapis.com/mapsdevsite/json/google.json');
    //map.data.loadGeoJson('https://data.smartdublin.ie/dataset/58969481-417e-4f5a-b8ea-18b56419d0ed/resource/a38b3d50-96ae-495e-ae69-899d833404cf/download/dccrdpandd.geojson');
    map.data.loadGeoJson('<?php echo "geoJson/".$geojsonfile; ?>');
    map.data.setStyle(function(feature) {
      var color = "<?php echo $color; ?>";

     return {
     		//icon: 'http://www.myiconfinder.com/uploads/iconsets/256-256-f900504cdc9f243b1c6852985c35a7f7.png',
        icon: 'icons/'+'<?php echo $color; ?>'+'.png',
        fillColor: color,
        strokeWeight: 1
      }
    });

    map.data.addListener('click', function(event) {
    	var Zone = event.feature.getProperty("Zone");
      var Tariff = event.feature.getProperty("Tariff");
    	infowindow.setContent("<div style='width:150px;'><em>Parking Info:</em><br><strong>Zone:</strong>"+Zone+"<br><strong>Tariff:</strong>"+Tariff+"</div>");
    	// position the infowindow on the marker
    	infowindow.setPosition(event.feature.getGeometry().get());
    	// anchor the infowindow on the marker
    	infowindow.setOptions({pixelOffset: new google.maps.Size(0,-30)});
    	infowindow.open(map);
    });

    var isClosed = false;
    poly = new google.maps.Polyline({ map: map, path: [], strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });
    google.maps.event.addListener(map, 'click', function (clickEvent) {
        if (isClosed) {
          //findPoint(poly);
          return;
        }
        var markerIndex = poly.getPath().length;
        var isFirstMarker = markerIndex === 0;
        var marker = new google.maps.Marker({ map: map, position: clickEvent.latLng, draggable: true });
        if (isFirstMarker) {
            google.maps.event.addListener(marker, 'click', function () {
                if (isClosed)
                    return;
                var path = poly.getPath();
                poly.setMap(null);
                poly = new google.maps.Polygon({ map: map, path: path, strokeColor: "#FF0000", strokeOpacity: 0.8, strokeWeight: 2, fillColor: "#FF0000", fillOpacity: 0.35 });
                isClosed = true;
                $("#findbtn").removeAttr('disabled');
            });
        }
        google.maps.event.addListener(marker, 'drag', function (dragEvent) {
            poly.getPath().setAt(markerIndex, dragEvent.latLng);
        });

        poly.getPath().push(clickEvent.latLng);
    });


  //var file = require('<?php echo "geoJson/".$geojsonfile; ?>');


}

  function findPoint(){
    var arr = null;

    $.ajax({
        'async': false,
        'global': false,
        'url': "<?php echo "geoJson/".$geojsonfile; ?>",
        'dataType': "json",
        'success': function (data) {
            arr = data;
        }
    });

    arr.features.forEach(function (entry) {
      //console.log(entry.geometry.coordinates);
      var coord = new google.maps.LatLng(entry.geometry.coordinates[1], entry.geometry.coordinates[0]);

      if (google.maps.geometry.poly.containsLocation(coord,poly)) {

        console.log(coord.lat());
        console.log("found");
      }


    });
    return;
  }



</script>

</body>
</html>
