<?php
include "includes/functions.php";

$sql = "SELECT prospect_id from prospects where has_act=1 and display=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = stripslashes($record['prospect_id']);
  $sql = "SELECT count(act_id) from activities where prospect_id='$prospect_id' and display=1";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "UPDATE prospects set has_act=0 where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
}

$sql = "SELECT prospect_id from prospects where has_opp=1 and display=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = stripslashes($record['prospect_id']);
  $sql = "SELECT count(opp_id) from opportunities where prospect_id='$prospect_id' and display=1";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "UPDATE prospects set has_opp=0 where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
}
?>