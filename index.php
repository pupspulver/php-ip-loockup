<style>
table, th, td {
  border: 1px solid #CCC;
  border: none;
}

table {
   width:100%
}

th, td {
  padding: 5px;
  text-align: left;
}
tr:nth-child(odd) {background-color: #f2f2f2;}
</style>

<?php
$ip = $_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

echo "Your IP Address is: $ip", "<br>" . "<br/>";

$ipdat = @json_decode(file_get_contents(
    "http://www.geoplugin.net/json.gp?ip=" . $ip));
?>

<table style="width:100%">
    <tr>
      <th>Country Name:</th>
      <th>Country Code:</th>
      <th>Region Name:</th>
      <th>Region:</th>
    </tr>
    <tr>
      <td><?php echo $ipdat->geoplugin_countryName ?></td>
      <td><?php echo $ipdat->geoplugin_countryCode ?></td>
      <td><?php echo $ipdat->geoplugin_regionName ?></td>
      <td><?php echo $ipdat->geoplugin_region ?></td>
    </tr>
  </table>

  <table style="width:100%">
    <tr>
      <th>City Name:</th>
      <th>Continent Name:</th>
      <th>Latitude:</th>
      <th>Longitude:</th>
    </tr>
    <tr>
      <td><?php echo $ipdat->geoplugin_city ?></td>
      <td><?php echo $ipdat->geoplugin_continentName ?></td>
      <td><?php echo $ipdat->geoplugin_latitude ?></td>
      <td><?php echo $ipdat->geoplugin_longitude ?></td>
    </tr>
  </table>

  <table style="width:100%">
    <tr>
      <th>Currency Symbol:</th>
      <th>Currency Code:</th>
      <th>Timezone:</th>
      <th>Domain:</th>
    </tr>
   <tr>
     <td><?php echo $ipdat->geoplugin_currencySymbol ?></td>
     <td><?php echo $ipdat->geoplugin_currencyCode ?></td>
     <td><?php echo $ipdat->geoplugin_timezone ?></td>
     <td><?php echo $hostname ?></td>
   </tr>
  </table>
  <br>

<?php
$browser = $_SERVER['HTTP_USER_AGENT'];
$referrer = $_SERVER['HTTP_REFERER'];
 if ($referred == "") {
  $referrer = "This page was accessed directly";
  }
echo "<b>Browser (User Agent) Info: </b>" . $browser . "<br/>";
echo "<b>Referrer: </b>" . $referrer . "<br/>";
?>
<link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet"> 

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
<script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

<script>
var map;
var mapLat = <?php echo $ipdat->geoplugin_latitude ?>;
var mapLng = <?php echo $ipdat->geoplugin_longitude ?>;
var mapDefaultZoom = 10;
function initialize_map() {
map = new ol.Map({
target: "map",
layers: [
new ol.layer.Tile({
source: new ol.source.OSM({
url: "https://a.tile.openstreetmap.org/{z}/{x}/{y}.png"
})
})
],
view: new ol.View({
center: ol.proj.fromLonLat([mapLng, mapLat]),
zoom: mapDefaultZoom
})
});
}
function add_map_point(lat, lng) {
var vectorLayer = new ol.layer.Vector({
source:new ol.source.Vector({
features: [new ol.Feature({
geometry: new ol.geom.Point(ol.proj.transform([parseFloat(lng), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857')),
})]
}),
style: new ol.style.Style({
image: new ol.style.Icon({
anchor: [0.5, 0.5],
anchorXUnits: "fraction",
anchorYUnits: "fraction",
src: "https://upload.wikimedia.org/wikipedia/commons/e/ec/RedDot.svg"
})
})
});
map.addLayer(vectorLayer);
}
</script>
  <br>
  <body onload="initialize_map(); add_map_point(<?php echo $ipdat->geoplugin_latitude ?>, <?php echo $ipdat->geoplugin_longitude ?>);">
    <div id="map" style="width: 75vw; height: 50vh;"></div>
