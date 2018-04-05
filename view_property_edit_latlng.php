<?php
include "includes/functions.php";

$property_id = $_GET['property_id'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$error_desc = $_GET['error_desc'];

$sql = "SELECT count(*) from geocode where property_id='$property_id'";
$test = getsingleresult($sql);
if($test == 0){
  $sql = "INSERT into geocode(property_id) values('$property_id')";
  executeupdate($sql);
}

if($property_id){
if($lat){
  $sql = "UPDATE geocode set latitude='$lat', longitude='$lng' where property_id='$property_id'";
}
else {
  $sql = "UPDATE geocode set latitude=0, longitude=0, error_desc=\"$error_desc\" where property_id='$property_id'";
}
}

executeupdate($sql);
?>