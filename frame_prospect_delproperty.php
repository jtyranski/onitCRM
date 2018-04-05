<?php
exit;
include "includes/functions.php";
if($SESSION_ISADMIN !=1) exit;

$property_id = $_GET['property_id'];
$redirect = $_GET['redirect'];
$prospect_id = $_GET['prospect_id'];
if($redirect == "") $redirect = "frame_prospect_properties.php";
if($prospect_id != "") $redirect .= "?prospect_id=$prospect_id";
$sql = "UPDATE properties set display=0 where property_id='$property_id'";
executeupdate($sql);

$sql = "SELECT count(*) from properties where prospect_id='$prospect_id' and corporate=0 and display=1";
  $props = getsingleresult($sql);
  $sql = "UPDATE prospects set properties='$props' where prospect_id='$prospect_id'";
  executeupdate($sql);
  
meta_redirect($redirect);
?>