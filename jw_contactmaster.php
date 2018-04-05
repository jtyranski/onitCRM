<?php
include "includes/functions.php";

$sql = "SELECT prospect_id, id from contacts where prospect_id != 0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $id = $record['id'];
  
  $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
  $master_id = getsingleresult($sql);
  
  $sql = "UPDATE contacts set master_id='$master_id' where id='$id'";
  executeupdate($sql);
}

$sql = "SELECT property_id, id from contacts where property_id != 0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  $id = $record['id'];
  
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
  
  $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
  $master_id = getsingleresult($sql);
  
  $sql = "UPDATE contacts set master_id='$master_id' where id='$id'";
  executeupdate($sql);
}
?>