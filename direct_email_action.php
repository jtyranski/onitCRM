<?php
include "includes/functions.php";

$to = $_POST['to'];

$users = $_POST['users'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$submit1 = $_POST['submit1'];
$email_include_signature = $_POST['email_include_signature'];
if($email_include_signature != 1) $email_include_signature = 0;

// copy into notes section
if($submit1=="Send"){
  
  $attachment = 0;
	if (is_uploaded_file($_FILES['emailattachment']['tmp_name']))
    {
	
	  $ext = explode(".", $_FILES['emailattachment']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $attachfilename = uniqueTimeStamp() . "." . $ext;
	  move_uploaded_file($_FILES['emailattachment']['tmp_name'], "uploaded_files/attachments/". $attachfilename);
      include 'mail_attachment.php';
	  $attachment = 1;
    }
  
  /*
  if (is_uploaded_file($_FILES['email_attachimage']['tmp_name']))
  {
  if(is_image_valid($_FILES['email_attachimage']['name'])){
	$ext = explode(".", $_FILES['email_attachimage']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['email_attachimage']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/email_images/", 800);
  	@unlink("uploaded_files/temp/". $filename);
  }
  }
  */
  if(is_array($_SESSION['sess_email_multi_attach'])){
    for($x=0;$x<sizeof($_SESSION['sess_email_multi_attach']);$x++){
	  $y = $x + 1;
	  $message .= "\n\n";
	  $message .= "<a href='" . $CORE_URL . "uploaded_files/attachments/" . $_SESSION['sess_email_multi_attach'][$x] . "'>View Attachment #" . $y . "</a>";
	}
  }
  $_SESSION['sess_email_multi_attach'] = "";
  
  if($_SESSION[$sess_header . '_document_attach'] != ""){
    $sql = "SELECT type, file from drawings where drawing_id='" . $_SESSION[$sess_header . '_document_attach'] . "'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $xtype = stripslashes($record['type']);
    $xfile = stripslashes($record['file']);
    $message .= "\n\n";
	$message .= "<a href='" . $CORE_URL . "uploaded_files/drawings/" . $xfile . "' target='_blank'>Click here to view the attached $xtype file</a>";
  }
  
  if($MASTER_LOGO != ""){
    $logo = "<img src=\"" . $SITE_URL . "uploaded_files/master_logos/" . $MASTER_LOGO . "\">";
  }
  else {
    $logo = "<strong>" . $MASTER_NAME . "</strong>";
  }
  
  
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature, signature_block from users where user_id='" . $SESSION_USER_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $mail_user_name = stripslashes($record['fullname']);
  $mail_user_email = stripslashes($record['email']);
  $mail_user_office = stripslashes($record['office']);
  $mail_user_extension = stripslashes($record['extension']);
  $mail_user_title = stripslashes($record['title']);
  $mail_user_cellphone = stripslashes($record['cellphone']);
  $mail_user_fax = stripslashes($record['fax']);
  $signature_image = stripslashes($record['signature']);
  $signature_block = stripslashes($record['signature_block']);
  
  $which_sig = 0;
  if($signature_block != "") $which_sig = 1;
  if($signature_image != "") $which_sig = 2;
  
  $signature = "";
  if($email_include_signature){
    switch($which_sig){
	  case 0:{
	    $signature = "Respectfully,<br>";
	    $signature .= $mail_user_name . "<br>";
	    if($mail_user_title != "") $signature .= $mail_user_title . "<br>";
	    $signature .= "<a href='mailto:" . $mail_user_email . "'>" . $mail_user_email . "</a><br>";
	    if($mail_user_office) $signature .= "Office: " . $mail_user_office;
	    if($mail_user_extension != "") $signature .= " Ext: " . $mail_user_extension;
	    $signature .= "<br>";
	    if($mail_user_cellphone != "") $signature .= "Cell: " . $mail_user_cellphone . "<br>";
		if($mail_user_fax != "") $signature .= "Fax: " . $mail_user_fax . "<br>";
		break;
	  }
	  case 1:{
	    $signature = nl2br($signature_block);
		break;
	  }
	  case 2:{
	    $signature = "<img src='" . $CORE_URL . "uploaded_files/headshots/" . $signature_image . "'>";
		break;
	  }
	}
  }
  else {
	  $signature = "";
  }
  
  $sql = "SELECT body from templates where name='Activity - Email'";
  $body = stripslashes(getsingleresult($sql));
  $body = str_replace("[MESSAGE]", nl2br($message), $body);
  $body = str_replace("[LOGO]", $logo, $body);
  $body = str_replace("[SIGNATURE]", $signature, $body);
  $body_raw = $body;
  
  for($x=0;$x<sizeof($users);$x++){
    $xuser_id = $users[$x];
    $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email from users where user_id='$xuser_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    //$fullname = stripslashes($record['fullname']);
    $user_email = stripslashes($record['email']);
    //$userlist .= $fullname . ", ";
    $userlist_email .= $user_email . ",";
  }
  /*
  $sql = "SELECT email from users where bcc_spam=1 and enabled=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
	$xemail = stripslashes($record['email']);
	if(!(go_reg($xemail, $userlist_email))) $userlist_email .= $xemail . ",";
  }
  */
	
  
	
  if(is_array($to)){
      for($x=0;$x<sizeof($to);$x++){
	    $body = $body_raw;

        $userlist_email .= $to[$x] . ",";
		$sent_list[] = $to[$x];
	  }
  }
  $userlist_email = go_reg_replace(",$", "", $userlist_email);
  
  if($attachment == 0){
	  $headers = "Content-type: text/html; charset=iso-8859-1\n";
	}
	else {
	  $headers = "";
	}
	
  //$headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_user_name <$mail_user_email>\n";
  $headers .= "Return-Path: ". $mail_user_email;  // necessary for some emails such as aol
  $headers_users = $headers . "\n" . "Bcc: $userlist_email";
  //$headers .= "Bcc: $to_list\n";

  $to_list = $userlist_email;
  //$to_list .= ",dpr@fcscontrol.com";
  //$to_list = str_replace($mail_user_email, "", $to_list);
  

  
  //email_q($to_list, $subject, $body, $headers);
  if($attachment==0){
	  email_q("", $subject, $body, $headers_users);
	}
	else {
	  email_q("", $subject, $body, $headers_users, 'uploaded_files/attachments/' . $attachfilename);
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
	email_q($mail_user_email, $subject, $body, $headers, 'uploaded_files/attachments/' . $attachfilename);
  }
  
  $message = go_escape_string($message);
  $sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, note, attachment) values(
  '" . $SESSION_USER_ID . "', '" . $_POST['property_id'] . "', '" . $_POST['prospect_id'] . "', now(), 'Email', \"Sent From: $mail_user_name\nBcc: $userlist_email\n\n$message\", \"$attachfilename\")";
  executeupdate($sql);
  
}
$_SESSION[$sess_header . '_document_attach'] = "";

$property_id = $_POST['property_id'];
$prospect_id = $_POST['prospect_id'];
$redirect = "view_company.php?prospect_id=$prospect_id";
if($property_id != 0) $redirect = "view_property.php?property_id=$property_id";

meta_redirect($redirect);
	  
?>