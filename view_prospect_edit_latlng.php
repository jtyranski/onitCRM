<?php
include "includes/functions.php";

$prospect_id = $_GET['prospect_id'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$error_desc = $_GET['error_desc'];

$sql = "SELECT count(*) from geocode where prospect_id='$prospect_id'";
$test = getsingleresult($sql);
if($test == 0){
  $sql = "INSERT into geocode(prospect_id) values('$prospect_id')";
  executeupdate($sql);
}

if($prospect_id){
if($lat){
  $sql = "UPDATE geocode set latitude='$lat', longitude='$lng' where prospect_id='$prospect_id'";
}
else {
  $sql = "UPDATE geocode set latitude=0, longitude=0, error_desc=\"$error_desc\" where prospect_id='$prospect_id'";
}
}

executeupdate($sql);
?>