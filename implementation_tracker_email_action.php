<?php
include "includes/functions.php";
 // this is just for fcs login

$master_id = $_POST['master_id'];
//$email_list = array("jwert@roofbidsnow.com");
$selected = $_POST['selected'];
//$selected = 1;

//$users = $_POST['users'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$imagelink = $_POST['imagelink'];

$submit1 = $_POST['submit1'];

$email_include_signature = $_POST['email_include_signature'];
if($email_include_signature != 1) $email_include_signature = 0;

//if($email_banner_message != "") $message .= "<br>" . $email_banner_message;
//if($email_movie_message != "") $message .= "<br>" . $email_movie_message;

// copy into notes section
if($submit1=="Send"){
  

  $attachment = 0;
  if (is_uploaded_file($_FILES['attachment']['tmp_name']))
  {
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], "uploaded_files/attachments/". $filename);
	include 'mail_attachment.php';
	$attachment=1;
  }
  
  
  
  
  $sql = "SELECT body from templates where name='Admin - Email'";
  $body = stripslashes(getsingleresult($sql));
  $body = str_replace("[MESSAGE]", nl2br($message), $body);
  $body = str_replace("[IMAGE]", "", $body);
  
	
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, signature from users where user_id='" . $SESSION_USER_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $mail_user_name = stripslashes($record['fullname']);
  $mail_user_email = stripslashes($record['email']);
  $mail_user_signature = $record['signature'];
  
  if($attachment == 0){
	  $headers = "Content-type: text/html; charset=iso-8859-1\n";
	}
	else {
	  $headers = "";
  }

	
  //$headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_user_name <$mail_user_email>\n";
  $headers .= "Return-Path: ". $mail_user_email;  // necessary for some emails such as aol
  //$headers .= "Bcc: $to_list\n";

  //$to_list .= ",dpr@fcscontrol.com";
  //$to_list = str_replace($mail_user_email, "", $to_list);
  

	
	

	
  $body = str_replace("[SIGNATURE]", "", $body);
  
  //email_q($to_list, $subject, $body, $headers);

  
  $sql = "SELECT contact_email from master_list where master_id='" . $master_id . "'";
	$email = stripslashes(getsingleresult($sql));
	if($email != "") $email_list[] = $email;
	
	$sql = "SELECT email from users where master_id='" . $master_id . "'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $email = stripslashes($record['email']);
	  if($email != ""){
	    if(!(in_array($email, $email_list))) $email_list[] = $email;
	  }
	}
	
    if(is_array($email_list)){
      for($x=0;$x<sizeof($email_list);$x++){
        $sql = "SELECT id from unsubscribe_admin where email=\"" . $email_list[$x] . "\"";
		$test = getsingleresult($sql);
		if($test==""){
		  if($attachment==0){
	       email_q($email_list[$x], $subject, $body, $headers);
	      }
	      else {
	        email_q($email_list[$x], $subject, $body, $headers, 'uploaded_files/attachments/' . $filename);
	      }

		  $sent_list[] = $email_list[$x];
		}
	  }
    }
  
  
  
  

  

  
  // mail $sent_list array to user id that sent this
  $subject = $subject . " - Recipient List";
  $body = "";
  for($x=0;$x<sizeof($sent_list);$x++){
    $body .= $sent_list[$x] . "<br>";
  }
  //email_q($mail_user_email, $subject, $body, $headers);
	if($attachment==0){
      email_q($mail_user_email, $subject, $body, $headers);
    }
    else {
      email_q($mail_user_email, $subject, $body, $headers, 'uploaded_files/attachments/' . $filename);
    }

  
}

meta_redirect("implementation_tracker.php");
	  
?>