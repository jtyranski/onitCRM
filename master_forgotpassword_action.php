<?php
include "includes/functions.php";
$email = go_escape_string($_POST['email']);
$logchoice = go_escape_string($_POST['logchoice']);
$input_master_id = go_escape_string($_POST['input_master_id']);

for($x=1;$x<8;$x++){
    $rand = rand(65, 90);
    $chr = chr($rand);
    $extra .= $chr;
  }
$password_enc = md5($extra);

  $user_id = "";
  switch($logchoice){
	case "fcsview":{
	  
	  $sql = "SELECT prospect_id, user_id from am_users 
      where email=\"$email\" and enabled=1";
	  //if($input_master_id) $sql .= " and prospect_id = '$input_master_id'";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $master_id = $record['prospect_id'];
      $user_id = $record['user_id'];
	  
	  $table = "am_users";
	  $clause = "prospect_id='$master_id' and user_id='$user_id'";
	  $filler = "Facility Control Systems";
	  break;
	}
	case "fcs":{
	  $sql = "SELECT user_id from users 
      where email=\"$email\" and enabled=1";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $master_id = "";
      $user_id = $record['user_id'];
	  
	  $table = "users";
	  $clause = "user_id='$user_id'";
	  $filler = "Facility Control Systems";
	  break;
	}
  }
  
$_SESSION['fp_logchoice'] = "";
$_SESSION['fp_email'] = "";

if($user_id != ""){
  $sql = "UPDATE $table set temp_password=\"$password_enc\" where 
  $clause";
  executeupdate($sql);

  $message = "You are receiving this message because you requested your password for $filler.";
  $message .= "If you did not request this, please disregard this notice.<br><br>";
  $message .= "Your temporary password is: $extra<br><br>";
  $message .= "Please use the temporary password with the following link to reset your password. This temporary password will expire ";
  $message .= "if not used within 24 hours.<br><br>";
  $message .= "<a href='" . $SITE_URL . "master_password_reset_forgot.php'>";
  $message .= $SITE_URL . "master_password_reset_forgot.php</a>";
  $subject = "Password request from $filler";
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $filler <no-reply@fcscontrol.com>\n";
  $headers .= "Return-Path: no-reply@fcscontrol.com\n";  // necessary for some emails such as aol
  email_q($email, $subject, $message, $headers);
  $_SESSION['sess_msg'] = "Your password has been emailed to $email";
}
else {
  $_SESSION['sess_msg'] = "The email address: $email is not registered with the selected log in option with $MAIN_CO_NAME";
}
  

meta_redirect("master_forgotpassword.php");
?>