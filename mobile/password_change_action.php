<?php
include "includes/functions.php";

$email = go_escape_string($_POST['email']);
$oldpassword = go_escape_string($_POST['oldpassword']);
$newpassword1 = go_escape_string($_POST['newpassword1']);
$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $password_check = md5($oldpassword);
  $sql = "SELECT user_id from users where email=\"$email\" and password=\"$password_check\" and enabled=1";
  $user_id = getsingleresult($sql);
  if($user_id == ""){
    $_SESSION['sess_msg'] = "The email/password combination entered is invalid or the account has been disabled.";
	meta_redirect("password_change.php");
  }
  
  $invalid = 0;
  if(strlen($newpassword1) < 5) $invalid = 1;
  
  preg_match_all('/[0-9]/', $newpassword1, $your_match) ;
  $int_count = count($your_match [0]);
  if($int_count < 2) $invalid = 1;
  
  if($invalid){
    $_SESSION['sess_msg'] = "Your new password must be at least 5 characters long, with at least 2 numeric characters.";
	meta_redirect("password_change.php");
  }
  
  if($oldpassword == $newpassword1){
    $_SESSION['sess_msg'] = "Your new password cannot be the same as your old password.";
	meta_redirect("password_change.php");
  }
  
  $sql = "SELECT password, (hour(timediff(now(), password_change_date)) / 30) as days from users where user_id='$user_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $oldpassword_enc = stripslashes($record['password']);
  $days = $record['days'];
  
  $sql = "UPDATE users set password=\"" . md5($newpassword1) . "\", last_password=\"$oldpassword_enc\", password_change_date=now(), 
  force_change = 0 where user_id='$user_id'";
  executeupdate($sql);
  
  $_SESSION['ro_force_change'] = 0;
  meta_redirect("calendar.php");
  
}
?>