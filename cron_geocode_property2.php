<?php
//exit;
ob_start("ob_gzhandler");
session_start();
include "includes/functions.php";




define("MAPS_HOST", "maps.google.com");
define("KEY", $GOOGLE_API);

// Initialize delay in geocode speed
$delay = 0;
$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;

$sql = "SELECT property_id, address, city, state from properties where display=1";
$result = executequery($sql);

while ($record = go_fetch_array($result)){


  $property_id = $record['property_id'];
  $sql = "SELECT latitude from geocode where property_id='$property_id'";
  $test = getsingleresult($sql);
  if($test) continue;
  
  
  $address = $record['address'];
  $city = $record['city'];
  $state = $record['state'];
  if($address=="") continue;
  if($city=="") continue;
  if($state=="") continue;
  $fulladdress = $address .", ". $city .", ". $state;
  $fulladdress = go_reg_replace("\"", "", $fulladdress);




  $geocode_pending = true;

  while ($geocode_pending) {
    $id = $row["id"];
    $request_url = $base_url . "&q=" . urlencode($fulladdress);
    $xml = simplexml_load_file($request_url) or die("url not loading");

    $status = $xml->Response->Status->code;
    if (strcmp($status, "200") == 0) {
      // Successful geocode
      $geocode_pending = false;
      $coordinates = $xml->Response->Placemark->Point->coordinates;
      $coordinatesSplit = split(",", $coordinates);
      // Format: Longitude, Latitude, Altitude
      $lat = $coordinatesSplit[1];
      $lng = $coordinatesSplit[0];

      $sql = "INSERT into geocode(property_id, latitude, longitude) values('$property_id', \"" . go_escape_string($lat) . "\", \"" . go_escape_string($lng) . "\")";
      executeupdate($sql);

    } else if (strcmp($status, "620") == 0) {
      // sent geocodes too fast
      $delay += 100000;
    } else {
      // failure to geocode
      $geocode_pending = false;
      echo "Address " . $address . " failed to geocoded. ";
      echo "Received status " . $status . "
	  
      <br>";
	  $sql = "INSERT into geocode(property_id, error_desc) values('$property_id', \"" . go_escape_string($status) . "\")";
	  executeupdate($sql);
    }
    usleep($delay);
  }
}

?>
