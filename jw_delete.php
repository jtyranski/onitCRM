<?php
exit;
include "includes/functions.php";

$ii = "JWORPHAN1";

$sql = "SELECT prospect_id from prospects where identifier='$ii'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $sql = "SELECT property_id from properties where prospect_id='$prospect_id'";
  $res2 = executequery($sql);
  while($rec2 = go_fetch_array($res2)){
    $property_id = $rec2['property_id'];
	$sql = "DELETE from contacts where property_id='$property_id'";
	executeupdate($sql);
  }
  $sql = "DELETE from properties where prospect_id='$prospect_id'";
  executeupdate($sql);
  $sql = "DELETE from contacts where prospect_id='$prospect_id'";
  executeupdate($sql);
}

$sql = "DELETE from prospects where identifier='$ii'";
executeupdate($sql);

?>