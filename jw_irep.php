<?php
exit;
include "includes/functions.php";

$sql = "SELECT prospect_id, property_id, irep from properties where irep != 0 and irep != ''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $property_id = $record['property_id'];
  $irep = $record['irep'];
  $sql = "SELECT irep from prospects where prospect_id='$prospect_id'";
  $p_irep = getsingleresult($sql);
  if(!(go_reg("," . $irep . ",", $p_irep))){
    $p_irep .= "," . $irep . ",";
	$sql = "UPDATE prospects set irep='$p_irep' where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
}
?>