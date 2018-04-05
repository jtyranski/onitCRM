<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];

if($SESSION_ISADMIN != 1) exit;

$sql = "DELETE FROM am_leakcheck WHERE leak_id='$leak_id'";
executeupdate($sql);
$sql = "DELETE FROM am_leakcheck_extras WHERE leak_id='$leak_id'";
executeupdate($sql);
$sql = "DELETE FROM am_leakcheck_notes WHERE leak_id='$leak_id'";
executeupdate($sql);
$sql = "DELETE FROM am_leakcheck_photos WHERE leak_id='$leak_id'";
executeupdate($sql);

$sql = "SELECT event_id from supercali_events where leak_id='$leak_id'";
$event_id = getsingleresult($sql);
if($event_id != ""){
  $sql = "DELETE from supercali_dates where event_id='$event_id'";
  executeupdate($sql);
  $sql = "DELETE from supercali_events where event_id='$event_id'";
  executeupdate($sql);
}

meta_redirect("fcs_sd_report.php");
?>
