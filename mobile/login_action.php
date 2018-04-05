<?php
exit;
include "includes/functions.php";

$email = go_escape_string($_POST['email']);
$password = go_escape_string($_POST['password']);
$password = md5($password);

$remember = $_POST['remember'];
if($remember !=1) $remember = 0;

$sql = "SELECT user_id, master_id, admin, resource, resource_id, user_level, irep
	from users where email='$email' and password='$password' and enabled=1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $user_id = $record['user_id'];
	$master_id = $record['master_id'];
	$admin = $record['admin'];
	$resource = $record['resource'];
	$resource_id = $record['resource_id'];
	$user_level = $record['user_level'];
	$irep = $record['irep'];

if($user_id != ""){
  $SESSION_USER_ID = $user_id;
  $SESSION_MASTER_ID = $master_id;
	$SESSION_ISADMIN = $admin;
	$SESSION_USER_LEVEL = $user_level;
	$SESSION_IREP = $irep;
  $sql = "UPDATE users set lastlogin=now() where user_id='$user_id'";
  executeupdate($sql);
  if($remember){
    setcookie("ro_rememberme", $email, time()+60*60*24*30);
	setcookie("ro_rememberme_pass", $_POST['password'], time()+60*60*24*30);
  }

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
	  


    meta_redirect("home.php");

}
else {
  $_SESSION['sess_msg'] = "The email/password combination entered is not in the system.";
  meta_redirect("login.php");
}
?>