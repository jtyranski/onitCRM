<?php
include "includes/functions.php";

 // this is just for fcs login

$prospect_id = go_escape_string($_GET['prospect_id']);
$master_id = go_escape_string($_GET['master_id']);


$sql = "UPDATE properties set display=0 where prospect_id='$prospect_id'";
executeupdate($sql);


$sql = "UPDATE prospects set display=0, properties=0 where prospect_id='$prospect_id'";
executeupdate($sql);

$sql = "DELETE from am_leakcheck where prospect_id='$prospect_id'";
executeupdate($sql);
$sql = "DELETE from activities where prospect_id='$prospect_id'";
executeupdate($sql);
$sql = "DELETE from supercali_events where prospect_id='$prospect_id'";
executeupdate($sql);
$sql = "DELETE from opportunities where prospect_id='$prospect_id'";
executeupdate($sql);
$sql = "DELETE from notes where prospect_id='$prospect_id'";
executeupdate($sql);
$sql = "DELETE from drawings where prospect_id='$prospect_id'";
executeupdate($sql);


meta_redirect("fcs_contractors_properties.php?master_id=$master_id");
?>
