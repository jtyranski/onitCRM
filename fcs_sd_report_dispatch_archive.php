<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];

$sql = "SELECT archive from am_leakcheck where leak_id='$leak_id'";
$archive = getsingleresult($sql);
if($archive==0){
  $newarchive = 1;
}
else {
  $newarchive = 0;
}

$sql = "UPDATE am_leakcheck set archive='$newarchive' where leak_id='$leak_id'";
executeupdate($sql);

meta_redirect("fcs_sd_report.php");
?>