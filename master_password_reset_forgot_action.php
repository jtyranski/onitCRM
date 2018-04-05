<?php
include "includes/functions.php";

$email = go_escape_string($_POST['email']);
$oldpassword = go_escape_string($_POST['oldpassword']);
$newpassword1 = go_escape_string($_POST['newpassword1']);
$logchoice = go_escape_string($_POST['logchoice']);
$submit1 = $_POST['submit1'];
if($newpassword1 == "") meta_redirect("master_password_reset_forgot.php");
if($email == "") meta_redirect("master_password_reset_forgot.php");


if($submit1 != ""){
  $password_check = md5($oldpassword);
  $user_id = "";
  switch($logchoice){
	case "fcsview":{
	  $sql = "SELECT prospect_id, user_id from am_users 
      where email=\"$email\" and temp_password=\"$password_check\" and enabled=1";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $master_id = $record['prospect_id'];
      $user_id = $record['user_id'];
	  break;
	}
	case "fcs":{
	  $sql = "SELECT user_id, master_id from users 
      where email=\"$email\" and temp_password=\"$password_check\" and enabled=1";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $master_id = $record['master_id'];
      $user_id = $record['user_id'];
	  break;
	}
  }
  
  if($user_id == ""){
    $_SESSION['sess_msg'] = "The email/password combination entered is invalid or the account has been disabled.";
	meta_redirect("master_password_reset_forgot.php");
  }
  
  $invalid = 0;
  if(strlen($newpassword1) < 6) $invalid = 1;
  
  preg_match_all('/[0-9]/', $newpassword1, $your_match) ;
  $int_count = count($your_match [0]);
  if($int_count < 2) $invalid = 1;
  
  if($invalid){
    $_SESSION['sess_msg'] = "Your new password must be at least 6 characters long, with at least 2 numeric characters.";
	meta_redirect("master_password_reset_forgot.php");
  }
  
  if($oldpassword == $newpassword1){
    $_SESSION['sess_msg'] = "Your new password cannot be the same as your old password.";
	meta_redirect("master_password_reset_forgot.php");
  }
  
  /*
  $sql = "SELECT password from oldpasswords where user_id='$user_id' and logchoice='$logchoice'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $op = stripslashes($record['password']);
	$op_array[] = $op;
  }
  
  if(is_array($op_array)){
    if(in_array(md5($newpassword1), $op_array)){
	  $_SESSION['sess_msg'] = "Your new password cannot be the same as a previous password.";
	  meta_redirect("master_password_reset_forgot.php");
	}
  }
  
  $sql = "INSERT into oldpasswords(user_id, password, changedate, logchoice) values 
  ('$user_id', \"" . md5($newpassword1) . "\", now(), '$logchoice')";
  executeupdate($sql);
  */
  

  
  switch($logchoice){

	
	case "fcsview":{
      $sql = "UPDATE am_users set password=\"" . md5($newpassword1) . "\", password_change_date=now(), 
      temp_password='' where prospect_id='$master_id' and user_id ='$user_id'";
      executeupdate($sql);
	  
	  break;
	}
	
	case "fcs":{
      $sql = "UPDATE users set password=\"" . md5($newpassword1) . "\", password_change_date=now(), 
      force_change = 0, temp_password='' where user_id ='$user_id' and master_id='$master_id'";
      executeupdate($sql);
	  
	  break;
	}
  }
  
  meta_redirect("master_password_complete.php");
  
  
  
  
}
?>