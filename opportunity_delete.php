<?php 
include "includes/functions.php";

$opp_id= $_GET['opp_id'];
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];

$redirect = $_GET['redirect'];
$redirect = go_reg_replace("\*", "&", $redirect);

$sql = "UPDATE opportunities set display=0 where opp_id='$opp_id'";
executeupdate($sql);

// different from NRP!
$sql = "SELECT opm_id from opportunities where opp_id='$opp_id'";
$opm_id = getsingleresult($sql);
if($opm_id){
  $sql = "DELETE from opm where opm_id='$opm_id'";
  executeupdate($sql);
}

$sql = "SELECT prospect_id from opportunities where opp_id='$opp_id'";
$x_prospect_id = getsingleresult($sql);
$sql = "SELECT count(*) from opportunities where display=1 and prospect_id='$x_prospect_id'";
$test = getsingleresult($sql);
if($test==0){
  $sql = "UPDATE prospects set has_opp=0 where prospect_id='$x_prospect_id'";
  executeupdate($sql);
}


$meta = "contacts.php";
if($prospect_id != "") $meta = "view_company.php?prospect_id=$prospect_id&view=opportunities";
if($property_id != "") $meta = "view_property.php?property_id=$property_id&view=opportunities";

if($redirect != "") meta_redirect($redirect);

meta_redirect($meta);
?>