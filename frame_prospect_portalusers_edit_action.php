<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_POST['prospect_id']);
$user_id = go_escape_string($_POST['user_id']);
$admin = go_escape_string($_POST['admin']);
/*
$admin = $_POST['admin'];
if($admin != 1) $admin=0;
$edit_mode = $_POST['edit_mode'];
if($edit_mode != 1) $edit_mode=0;
$reports = $_POST['reports'];
if($reports != 1) $reports=0;
*/
$level_access = go_escape_string($_POST['level_access']);
switch($level_access){
  case "User":{
    $admin = 0;
	$edit_mode = 0;
	$reports = 0;
	break;
  }
  case "User - Reports":{
    $admin = 0;
	$edit_mode = 0;
	$reports = 1;
	break;
  }
  case "Site Admin":{
    $admin = 0;
	$edit_mode = 1;
	$reports = 1;
	break;
  }
  case "Master Admin":{
    $admin = 1;
	$edit_mode = 1;
	$reports = 1;
	break;
  }
  default:{
    $admin = 0;
	$edit_mode = 0;
	$reports = 0;
	break;
  }
}
$enabled = go_escape_string($_POST['enabled']);
if($enabled != 1) $enabled=0;
$force_change = go_escape_string($_POST['force_change']);
if($force_change != 1) $force_change=0;
$sd_resource = go_escape_string($_POST['sd_resource']);
if($sd_resource != 1) $sd_resource=0;
$sd_notify = go_escape_string($_POST['sd_notify']);
if($sd_notify != 1) $sd_notify=0;

$firstname = go_escape_string($_POST['firstname']);
$lastname = go_escape_string($_POST['lastname']);
$email = go_escape_string($_POST['email']);

$password = go_escape_string($_POST['password']);
$submit1 = go_escape_string($_POST['submit1']);
$divisions = $_POST['divisions'];
$sites = $_POST['sites'];

  
if($submit1 != ""){
  if(is_array($divisions)){
    for($x=0;$x<sizeof($divisions);$x++){
	  $x_divisions .= "," . $divisions[$x] . ",";
	}
  }
  
  if(is_array($sites)){
    for($x=0;$x<sizeof($sites);$x++){
	  $x_sites .= "," . $sites[$x] . ",";
	}
  }
  
  if($user_id=="new"){
    $sql = "SELECT user_id from am_users where email=\"$email\" and prospect_id='$prospect_id'";
	$test = getsingleresult($sql);
	if($test != ""){
	  $_SESSION['sess_msg'] = "There is already a user in the system with that email address.";
	  meta_redirect("frame_prospect_portalusers_edit.php?user_id=$user_id&prospect_id=$prospect_id");
	}
	
    $sql = "INSERT into am_users(firstname, lastname, email, password, admin, title, phone, cell, prospect_id, divisions, sites, 
	edit_mode, reports, enabled, force_change, level_access, sd_resource, sd_notify) values(
	\"$firstname\", \"$lastname\", \"$email\", \"" . md5($password) . "\", '$admin', 
	\"$title\", \"$phone\", \"$cell\", '" . $prospect_id . "', '$x_divisions', '$x_sites', '$edit_mode', '$reports', 
	'$enabled', '$force_change', '$level_access', '$sd_resource', '$sd_notify')";
	executeupdate($sql);
	
	$sql = "SELECT user_id from am_users where email=\"$email\" order by user_id desc limit 1";
	$user_id = getsingleresult($sql);
	/*
	$sql = "INSERT into closing_stats(user_id, status, amount) values('$user_id', 'Sold', 0)";
	executeupdate($sql);
	*/
  }
  else {
    $sql = "UPDATE am_users set firstname=\"$firstname\", lastname=\"$lastname\", email=\"$email\", ";
	if($password != "") $sql .= " password = \"" . md5($password) . "\", ";
	$sql .= " admin='$admin', title=\"$title\", phone=\"$phone\", cell=\"$cell\", divisions=\"$x_divisions\", sites=\"$x_sites\", 
	edit_mode='$edit_mode', reports='$reports', enabled=\"$enabled\", force_change='$force_change', level_access='$level_access', 
	sd_resource='$sd_resource', sd_notify='$sd_notify'
	where 
	user_id='$user_id' and prospect_id='" . $prospect_id . "'";
	executeupdate($sql);
  }
  

}

meta_redirect("frame_prospect_portalusers.php?prospect_id=$prospect_id");
?>
