<?php
include "includes/functions.php";

$unique_url = $_GET['unique_url'];

$sql = "SELECT master_id from master_list where unique_url = '$unique_url' and demo=1";
$master_id = getsingleresult($sql);

if($master_id==""){
  exit;
}

$sql = "SELECT demo_property_id from master_list where master_id='$master_id'";
$property_id = getsingleresult($sql);
if($property_id==0){
  $sql = "SELECT prospect_id from prospects where master_id='$master_id' and display=1 order by prospect_id limit 1";
  $prospect_id = getsingleresult($sql);

  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and display=1 and corporate=0 order by property_id limit 1";
  $property_id = getsingleresult($sql);
}
else {
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
}

    $_SESSION[$sess_view_header . '_user_id'] = 1;
	$_SESSION[$sess_view_header . '_prospect_id'] = $prospect_id;
	$_SESSION[$sess_view_header . '_isadmin'] = 1;
	$_SESSION[$sess_view_header . '_divisions'] = "";
	$_SESSION[$sess_view_header . '_sites'] = "";
	$_SESSION[$sess_view_header . '_edit_mode'] = 1;
	$_SESSION[$sess_view_header . '_reports'] = 1;
	$_SESSION[$sess_view_header . '_master_id'] = $master_id;
	$_SESSION[$sess_view_header . '_demo'] = 1;
	
if($property_id != "") meta_redirect($UP_FCSVIEW . "view_property.php?property_id=$property_id");
if($section_id != "") meta_redirect($UP_FCSVIEW . "view_section.php?section_id=$section_id");
	meta_redirect($UP_FCSVIEW . "welcome.php");

?>