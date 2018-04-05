<?php
exit;
include "includes/functions.php";

$sql = "UPDATE prospects set has_act=0 where master_id=1";
executeupdate($sql);

$sql = "SELECT prospect_id from activities where act_result='Objective Met' group by prospect_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
  executeupdate($sql);
}

/*
$sql = "SELECT prospect_id from opportunities group by prospect_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $sql = "UPDATE prospects set has_opp=1 where prospect_id='$prospect_id'";
  executeupdate($sql);
}
*/

?>