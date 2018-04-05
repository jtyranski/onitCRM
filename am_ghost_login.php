<?php
include "includes/functions.php";

$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
$section_id = $_GET['section_id'];

    $_SESSION[$sess_view_header . '_user_id'] = 1;
	$_SESSION[$sess_view_header . '_prospect_id'] = $prospect_id;
	$_SESSION[$sess_view_header . '_isadmin'] = 1;
	$_SESSION[$sess_view_header . '_divisions'] = "";
	$_SESSION[$sess_view_header . '_sites'] = "";
	$_SESSION[$sess_view_header . '_edit_mode'] = 1;
	$_SESSION[$sess_view_header . '_reports'] = 1;
	$_SESSION[$sess_view_header . '_master_id'] = $SESSION_MASTER_ID;
    $_SESSION[$sess_view_header . '_master_edit_mode'] = 1; // not sure what all regular edit mode even does anymore
	$_SESSION[$sess_view_header . '_disciplines'] = $SESSION_DIS;
	
	$sql = "SELECT logo, master_name from master_list where master_id='" . $SESSION_MASTER_ID . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$_SESSION[$sess_view_header . '_master_logo'] = $record['logo'];
	$_SESSION[$sess_view_header . '_master_name'] = stripslashes($record['master_name']);

$redirect = $_GET['redirect'];
$redirect = go_reg_replace("\*\*", "?", $redirect);
$redirect = go_reg_replace("\*", "&", $redirect);
if($redirect != "") meta_redirect($UP_FCSVIEW . $redirect);
if($property_id != "") meta_redirect($UP_FCSVIEW . "view_property.php?property_id=$property_id");
if($section_id != "") meta_redirect($UP_FCSVIEW . "view_section.php?section_id=$section_id");
	meta_redirect($UP_FCSVIEW . "welcome.php");

?>