<?php
include "includes/functions.php";

 // this is just for fcs login

$property_id = go_escape_string($_GET['property_id']);
$master_id = go_escape_string($_GET['master_id']);

$sql = "UPDATE properties set display=0 where property_id='$property_id'";
executeupdate($sql);

$sql = "DELETE from am_leakcheck where property_id='$property_id'";
executeupdate($sql);
$sql = "DELETE from activities where property_id='$property_id'";
executeupdate($sql);
$sql = "DELETE from supercali_events where property_id='$property_id'";
executeupdate($sql);
$sql = "DELETE from opportunities where property_id='$property_id'";
executeupdate($sql);
$sql = "DELETE from notes where property_id='$property_id'";
executeupdate($sql);
$sql = "DELETE from drawings where property_id='$property_id'";
executeupdate($sql);

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT count(*) from properties where prospect_id='$prospect_id' and display=1 and corporate=0";
$prop_count = getsingleresult($sql);
$sql = "UPDATE prospects set properties='$prop_count' where prospect_id='$prospect_id'";
executeupdate($sql);


meta_redirect("fcs_contractors_properties.php?master_id=$master_id");
?>
