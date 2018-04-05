<?php
include "includes/functions.php";

$sql = "SELECT leak_id, property_id from am_leakcheck";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $leak_id = $record['leak_id'];
  $property_id = $record['property_id'];
  
  $sql = "SELECT sd_contact from properties where property_id='$property_id'";
  $sd_contact = getsingleresult($sql);
  
  $sql = "UPDATE am_leakcheck set contact_id='$sd_contact' where leak_id='$leak_id'";
  executeupdate($sql);
}
?>