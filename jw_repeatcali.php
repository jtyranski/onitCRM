<?php
include "includes/functions.php";

$sql = "SELECT act_id, event_id from supercali_events where act_id !=0 and complete=0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $event_id = $record['event_id'];
  $act_id = $record['act_id'];
  
  $sql = "SELECT repeat_type from activities where act_id='$act_id'";
  $repeat_type = getsingleresult($sql);
  $sql = "UPDATE supercali_events set repeat_type='$repeat_type' where event_id='$event_id'";
  executeupdate($sql);
}
?>