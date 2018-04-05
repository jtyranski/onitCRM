<?php
$sql = "SELECT map_service_xml from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$map_service_xml = getsingleresult($sql);

if($map_service_xml != ""){

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, vehicle_id from users where vehicle_id != '' and enabled=1 and master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $vid = $record['vehicle_id'];
  $vname = stripslashes($record['fullname']);
  $vuser[$vid] = $vname;
}

$xml = file_get_contents($map_service_xml);


$p = xml_parser_create();
xml_parse_into_struct($p, $xml, $vals, $index);
xml_parser_free($p);
echo "<script type='text/javascript' language='JavaScript'>";
for($x=0;$x<sizeof($vals);$x++){
  if($vals[$x]['tag']=="DATA" && $vals[$x]['type']=="open"){
    $vehicle_id = $vals[$x]['attributes']['VEHICLEID'];
    $latitude = $vals[$x]['attributes']['FLATT'];
	$longitude = $vals[$x]['attributes']['FLONG'];
	$label = $vals[$x]['attributes']['LABEL'];
	$reg_number = $vals[$x]['attributes']['REG_NUMBER'];
	$html  = "";
	if($vuser[$vehicle_id] != "") $html = "<b>" . $vuser[$vehicle_id] . "</b><br>";
	$html .= "<b>$label</b><br>Reg# $reg_number<br />VehID: $vehicle_id<br>";

    $html = go_reg_replace("\'", "", $html);
  
  
    
    echo "placeMarker($latitude, $longitude,'$html','van');";
    echo "\n";
  }
  
}
echo "</script>";

}
?>