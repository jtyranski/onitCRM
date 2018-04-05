<?php
include "includes/functions.php";


$sql = "SELECT act_id from activities where complete=1 and cron_supercali=0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $act_id = $record['act_id'];
  $sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
  executeupdate($sql);
  $sql = "UPDATE activities set cron_supercali=1 where act_id='$act_id'";
  executeupdate($sql);
}
?>