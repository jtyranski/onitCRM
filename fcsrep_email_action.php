<?php
include "includes/functions.php";

$email_list = $_SESSION['sess_email_list'];
//$email_list = array("jwert@roofbidsnow.com");
$selected = $_POST['selected'];
//$selected = 1;
$users = $_POST['users'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$submit1 = $_POST['submit1'];
$email_include_signature = $_POST['email_include_signature'];
if($email_include_signature != 1) $email_include_signature = 0;

$include_evolution = $_POST['include_evolution'];
if($include_evolution != 1) $include_evolution = 0;

$include_service = $_POST['include_service'];
if($include_service != 1) $include_service = 0;

$attachbrochure = $_POST['attachbrochure'];

// copy into notes section
if($submit1=="Send"){
  
  $attachment = 0;
	if (is_uploaded_file($_FILES['emailattachment']['tmp_name']))
    {
	
	  $ext = explode(".", $_FILES['emailattachment']['name']);
  	  $ext = strtolower(array_pop($ext));
  	  $attachfilename = uniqueTimeStamp() . "." . $ext;
	  move_uploaded_file($_FILES['emailattachment']['tmp_name'], "uploaded_files/attachments/". $attachfilename);
	  $attachment = 1;
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
  
  if($include_evolution==1){
    $message .= "\n\nEvolution of the Roofing Industry\n<a href=\"" . $CORE_URL . "video_evolution.php\"><img src='" . $CORE_URL . "uploaded_files/videos/evolution/evolution_vid.png' border='0'></a>";
  }
  if($include_service==1){
    $message .= "\n\nFCSCore Service Technologies\n<a href=\"" . $CORE_URL . "video_service.php\"><img src='" . $CORE_URL . "uploaded_files/videos/fcscore_tech/fcscore_tech_vid.png' border='0'></a>";
  }
  switch($attachbrochure){
    case "sample_roof_report.pdf":{
	  $message .= "\n\n<a href=\"" . $CORE_URL . "uploaded_files/docs/sample_roof_report.pdf\">Please see this sample roof report.</a>";
	  break;
	}
	case "sales_packet.pdf":{
	  $message .= "\n\n<a href=\"" . $CORE_URL . "uploaded_files/docs/sales_packet.pdf\">Please see this sales packet.</a>";
	  break;
	}
	case "contractor_marketing.pdf":{
	  $message .= "\n\n<a href=\"" . $CORE_URL . "uploaded_files/docs/contractor_marketing.pdf\">Please see this contractor marketing document.</a>";
	  break;
	}
	case "order_form.pdf":{
	  $message .= "\n\n<a href=\"" . $CORE_URL . "uploaded_files/docs/order_form.pdf\">Please see this order form.</a>";
	  break;
	}
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
	
  
	
  if($selected==1){
    if(is_array($email_list)){
      for($x=0;$x<sizeof($email_list);$x++){
	    $userlist_email .= $email_list[$x] . ",";
		$sent_list[] = $email_list[$x];
	  }
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
  
  if($selected==1){

  
  
    $list_type = $_SESSION['sess_list_type'];
    $ids = $_SESSION['sess_ids'];
    if($list_type=="property"){
      if(is_array($ids)){
	    for($x=0;$x<sizeof($ids);$x++){
	      $property_id = $ids[$x];
		  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
		  $prospect_id = getsingleresult($sql);
		  $sql = "INSERT into notes(user_id, property_id, prospect_id, date, event, regarding, note) values(
		  '" . $SESSION_USER_ID . "', '$property_id', '$prospect_id', now(), 'Contact', 'Sent Message', \"" . go_escape_string($body) . "\")";
		  executeupdate($sql);
	    }
	  }
    }
  
    if($list_type=="prospect"){
      if(is_array($ids)){
	    for($x=0;$x<sizeof($ids);$x++){
	      $prospect_id = $ids[$x];
		  $sql = "INSERT into notes(user_id, prospect_id, date, event, regarding, note) values(
		  '" . $SESSION_USER_ID . "', '$prospect_id', now(), 'Contact', 'Sent Message', \"" . go_escape_string($body) . "\")";
		  executeupdate($sql);
	    }
	  }
    }
  
  } // end if selected users box was checked
  

  

  
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
  
}
$_SESSION['sess_ids'] = "";
$_SESSION['sess_list_type'] = "";
$_SESSION['sess_email_list'] = "";

meta_redirect("email_complete.php");
	  
?>