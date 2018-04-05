<?php include_once "includes/functions.php"; 
$section_id = $_GET['section_id'];
$multisection = $_GET['multisection'];
$property_id = $_GET['property_id'];
$user_id = $SESSION_USER_ID;
$intro_credit = $_GET['intro_credit'];
$intro_credit = go_reg_replace("\,", "", $intro_credit);
$intro_credit = go_reg_replace("\$", "", $intro_credit);
if($intro_credit=="") $intro_credit = 1000;

$sql = "INSERT into document_proposal_fcs(section_id, user_id, intro_credit, property_id, multisection, proposal_date) values('$section_id', '$user_id', '$intro_credit', '$property_id', '$multisection', now())";
executeupdate($sql);
$sql = "SELECT id from document_proposal_fcs where section_id='$section_id' order by id desc limit 1";
$doc_id = getsingleresult($sql);
meta_redirect("public_proposal_fcs.php?doc_id=$doc_id");
exit;



$subject = $_POST['subject'];
$content = $_POST['content'];
$inc_sig = $_POST['inc_sig'];
$email_list = $_POST['email_list'];



$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $message = "<img src='" . $CORE_URL . "images/roofoptions_header4.png'><br><br>";
  $message .= nl2br($content);
  
  $code = uniqueTimeStamp();
  
  $message .= "<br><br><a href='" . $CORE_URL . "public_proposal_fcs.php?$code'>Click here to view proposal</a>";
  
  
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature
	from users where user_id='" . $user_id . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$mail_user_name = stripslashes($record['fullname']);
	$mail_user_email = stripslashes($record['email']);
	$mail_user_office = stripslashes($record['office']);
	//$mail_user_office = substr($mail_user_office,0,3)."-".substr($mail_user_office,3,3)."-".substr($mail_user_office,6,4);
	$mail_user_extension = stripslashes($record['extension']);
	$mail_user_title = stripslashes($record['title']);
	$mail_user_cellphone = stripslashes($record['cellphone']);
	//$mail_user_cellphone = substr($mail_user_cellphone,0,3)."-".substr($mail_user_cellphone,3,3)."-".substr($mail_user_cellphone,6,4);
	$mail_user_signature = $record['signature'];
	
	if($inc_sig){
	  if($mail_user_signature != ""){
	    $signature = "<img src=\"" . $SITE_URL . "uploaded_files/headshots/" . $mail_user_signature . "\">";
	  }
	  else {
	    $signature = "Respectfully,<br>";
	    $signature .= $mail_user_name . "<br>";
	    if($mail_user_title != "") $signature .= $mail_user_title . "<br>";
	    $signature .= "<a href='mailto:" . $mail_user_email . "'>" . $mail_user_email . "</a><br>";
	    $signature .= "Office: " . $mail_user_office;
	    if($mail_user_extension != "") $signature .= " Ext: " . $mail_user_extension;
	    $signature .= "<br>";
	    if($mail_user_cellphone != "") $signature .= "Cell: " . $mail_user_cellphone . "<br>";
	    if($email_company_logo == "roofoptions_header.jpg"){
	      $signature .= "<a href='http://www.roofoptions.com'>www.roofoptions.com</a>";
	    }
	    if($email_company_logo == "phencon_logo.jpg"){
	      $signature .= "<a href='http://www.phencon.com'>www.phencon.com</a>";
	    }
	    if($email_company_logo == "fcs_header_800.jpg"){
	      $signature .= "<a href='http://www.fcscontrol.com'>www.fcscontrol.com</a>";
	    }
	  }
	}
	else {
	  $signature = "";
	}
  
  $message .= "<br><br>$signature";
  
  $sql = "INSERT into document_proposal_fcs(section_id, code, user_id) values('$section_id', '$code', '$user_id')";
  executeupdate($sql);
  
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_user_name <$mail_user_email>\n";
  $headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
  $headers .= "Bcc: $email_list\n";
	  
  email_q($mail_user_email, $subject, $message, $headers);
  
  $_SESSION['sess_msg'] = "The $MAIN_CO_NAME proposal document has been emailed.";
}

meta_redirect("document_proposal_fcs_new.php?section_id=$section_id");
?>
  
  
