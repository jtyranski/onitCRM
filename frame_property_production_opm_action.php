<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$opm_id = go_escape_string($_POST['opm_id']);
$opm_entry_id = go_escape_string($_POST['opm_entry_id']);

$opm_date_pretty = go_escape_string($_POST['opm_date_pretty']);
$removed = go_escape_string($_POST['removed']);
$replaced = go_escape_string($_POST['replaced']);
$comments = go_escape_string($_POST['comments']);

$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  if($opm_entry_id=="new"){
    $sql = "INSERT into opm_entry(opm_id) values('$opm_id')";
	executeupdate($sql);
	$opm_entry_id = go_insert_id();
  }
  $date_parts = explode("/", $opm_date_pretty);
  $opm_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  $sql = "UPDATE opm_entry set opm_date='$opm_date', removed=\"$removed\", replaced=\"$replaced\", comments=\"$comments\" where opm_entry_id='$opm_entry_id'";
  executeupdate($sql);
}

meta_redirect("frame_property_production_opm_photos.php?property_id=$property_id&opm_id=$opm_id&opm_entry_id=$opm_entry_id");
?>
