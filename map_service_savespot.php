<?php
include "includes/functions.php";

$zoom = $_GET['zoom'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];

$_SESSION['map_lat'] = $lat;
$_SESSION['map_lng'] = $lng;
$_SESSION['map_zoom'] = $zoom;
?>