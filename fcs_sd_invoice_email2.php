<?php 
include_once "includes/functions.php"; 
$leak_id = $_GET['leak_id'];
$pdf_output = "F";
include "fcs_sd_invoice_pdf.php";
include 'mail_attachment.php';
$filename = "INVOICE_" . $leak_id . ".pdf";

$email_body = nl2br($_GET['email_body']);
$email_include_signature = $_GET['email_include_signature'];
if($email_include_signature != 1) $email_include_signature = 0;

//$body = "Attached you will find the invoice for your FCS Service Dispatch work.";

$email_list = $_GET['email_list'];
$email_list = go_reg_replace("\n", ",", $email_list);
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature
	from users where user_id='" . $_SESSION['user_id'] . "'";
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
	
	if($email_include_signature){
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
$email_body .= "<br><br>" . $signature;

$subject = "Service Dispatch Invoice";
$headers = "From: $mail_user_name <$mail_user_email>\n";
$headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
$headers .= "Bcc: $email_list";
	  
//email_q($mail_user_email, $subject, $body, $headers);
email_q($mail_user_email, $subject, $email_body, $headers, 'uploaded_files/invoices/' . $filename);

meta_redirect("fcs_sd_report_view.php?leak_id=$leak_id");
?>