<?php
include "includes/functions.php";

$email = go_escape_string($_POST['email']);
$password = go_escape_string($_POST['password']);
$password = md5($password);
$master_id = $_POST['master_id'];

$sql = "SELECT user_id from users where email='$email' and password='$password' and master_id='$master_id' and enabled=1 and resource=0";
$user_id = getsingleresult($sql);

if($user_id != ""){
  $sql = "SELECT active from master_list where master_id='$master_id'";
  $active = getsingleresult($sql);
  if($active==0){
	$_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
    meta_redirect("login.php");
  }
  $SESSION_USER_ID = $user_id;
  $SESSION_MASTER_ID = $master_id;
  $sql = "SELECT admin, user_level
  from users where email='$email' and password='$password' and master_id='$master_id' and enabled=1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $admin = $record['admin'];
  $user_level = $record['user_level'];
  
  $SESSION_ISADMIN = $admin;
  $SESSION_USER_LEVEL = $user_level;
    $sql = "UPDATE users set lastlogin=now() where user_id='$user_id'";
    executeupdate($sql);
	
	$sql = "SELECT opportunities, multilogo, timezone, use_resources from master_list where master_id='$master_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $opportunities = $record['opportunities'];
	$multilogo = $record['multilogo'];
	$timezone = $record['timezone'];
	$use_resources = $record['use_resources'];

    $SESSION_OPPORTUNITIES = $opportunities;
	$SESSION_MULTILOGO = $multilogo;
	$SESSION_TIMEZONE = $timezone;
	$SESSION_USE_RESOURCES = $use_resources;
	/*
    $sql = "SELECT force_change from users where user_id='$user_id'";
    $force_change = getsingleresult($sql);
    if($force_change){
      $_SESSION['sess_msg'] = "Your FCS password has expired.  Please set a new one.";
      $_SESSION['am_force_change'] = 1;
    }
	*/
	

	
    meta_redirect("index.php");
}
else {

  $_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
    meta_redirect("login.php");
}

?>