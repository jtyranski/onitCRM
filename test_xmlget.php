<?php
$homepage = file_get_contents('http://www.fleetmatics-usa.com/xml/xmlout.asp?login=triadroof&xmlcode=xml-g4zh1&utc=1');
//echo $homepage;

$p = xml_parser_create();
xml_parse_into_struct($p, $homepage, $vals, $index);
xml_parser_free($p);

echo "Index array<br>";
print_r($index);
echo "<br>Vals array<br>";
print_r($vals);

echo "<br>";
for($x=0;$x<sizeof($vals);$x++){
  if($vals[$x]['tag']=="DATA" && $vals[$x]['type']=="open"){
    $vehicle_id = $vals[$x]['attributes']['VEHICLEID'];
    $lat = $vals[$x]['attributes']['FLATT'];
	$lng = $vals[$x]['attributes']['FLONG'];
	echo "ID: $vehicle_id is at lat $lat and lng $lng<br>";
  }
  
}

?>