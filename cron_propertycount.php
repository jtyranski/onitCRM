<?php
include "includes/functions.php";

$sql = "SELECT master_id from master_list";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_id = $record['master_id'];
  $sql = "SELECT count(property_id) from prospects a, properties b where a.prospect_id=b.prospect_id and a.display=1 and b.display=1
  and b.corporate=0 and a.master_id='$master_id'";
  $properties = getsingleresult($sql);
  $sql = "UPDATE master_list set properties='$properties' where master_id='$master_id'";
  executeupdate($sql);
}

$sql = "SELECT prospect_id from prospects";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $sql = "SELECT count(property_id) from properties where display=1 and corporate=0 and prospect_id='$prospect_id'";
  $properties = getsingleresult($sql);
  $sql = "UPDATE prospects set properties='$properties' where prospect_id='$prospect_id'";
  executeupdate($sql);
}
?>
  