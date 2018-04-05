<?php
exit;
include "includes/functions.php";

$sql = "SELECT master_id, timezone from master_list";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_id = $record['master_id'];
  $timezone = $record['timezone'];
  if($timezone=="") $timezone=0;
  $sql = "SELECT prospect_id from prospects where master_id='$master_id'";
  $res2 = executequery($sql);
  while($rec2=go_fetch_array($res2)){
    $prospect_id = $rec2['prospect_id'];
	$sql = "UPDATE properties set timezone='$timezone' where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
}
?>