<?php
exit;
include "includes/functions.php";

$sql = "SELECT prospect_id, extra_field1 from prospects where display=1 and extra_field1 != ''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $extra1 = go_escape_string(stripslashes($record['extra_field1']));
  $sql = "INSERT into notes(prospect_id, date, event, note, regarding) values('$prospect_id', now(), 'Note', \"Import field: $extra1\", 'Import')";
  executeupdate($sql);
}


$sql = "SELECT property_id, prospect_id, extra_field1 from properties where display=1 and extra_field1 != ''";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  $prospect_id = $record['prospect_id'];
  $extra1 = go_escape_string(stripslashes($record['extra_field1']));
  $sql = "INSERT into notes(prospect_id, property_id, date, event, note, regarding) values('$prospect_id', '$property_id', now(), 'Note', \"Import field: $extra1\", 'Import')";
  executeupdate($sql);
}
?>