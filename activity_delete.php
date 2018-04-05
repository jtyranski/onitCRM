<?php 
include "includes/functions.php";

$act_id= $_GET['act_id'];
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
$redirect = $_GET['redirect'];

$sql = "UPDATE activities set display=0 where act_id='$act_id'";
executeupdate($sql);
/*
$sql = "DELETE from activities_conferencecall where act_id='$act_id'";
executeupdate($sql);
*/

$sql = "DELETE from supercali_events where act_id='$act_id' and act_id != 0";
executeupdate($sql);
$sql = "DELETE from activities_repeat where act_id='$act_id'";
executeupdate($sql);

$sql = "SELECT prospect_id from activities where act_id='$act_id'";
$x_prospect_id = getsingleresult($sql);
$sql = "SELECT count(*) from activities where display=1 and prospect_id='$x_prospect_id'";
$test = getsingleresult($sql);
if($test==0){
  $sql = "UPDATE prospects set has_act=0 where prospect_id='$x_prospect_id'";
  executeupdate($sql);
}

$meta = "welcome.php?view=activities";
if($prospect_id != "") $meta = "view_prospect.php?prospect_id=$prospect_id&view=activities";
if($property_id != "") $meta = "view_property.php?property_id=$property_id&view=activities";
if($redirect != "") $meta = $redirect;

meta_redirect($meta);
?>