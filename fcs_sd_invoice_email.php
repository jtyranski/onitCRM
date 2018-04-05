<?php 
include_once "includes/functions.php"; 

$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
if($invoice_type==2) $special_invoice = 2;
//$special_invoice = "";

$leak_id = $_GET['leak_id'];
$pdf_output = "F";
include "fcs_sd_invoice_pdf" . $special_invoice . ".php";
$sql = "SELECT invoice_filename from am_leakcheck where leak_id='$leak_id'";
$invoice_filename = getsingleresult($sql);

$sql = "SELECT proofdoc from am_leakcheck where leak_id='$leak_id'";
$proofdoc = getsingleresult($sql);
if($proofdoc != ""){
  $fileArray[] = "uploaded_files/download/$invoice_filename";
  $fileArray[] = "uploaded_files/proofdoc/$proofdoc";
  
  $newfile = secretCode() . ".pdf";
  $datadir = "uploaded_files/download/";
  $outputName = $datadir. $newfile;

  $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
  //Add each pdf file to the end of the command
  foreach($fileArray as $file) {
    $cmd .= $file." ";
  }
  $result = shell_exec($cmd);
  
  $invoice_filename = $newfile;
}


$email_body = nl2br($_GET['email_body']);
$email_include_signature = $_GET['email_include_signature'];
if($email_include_signature != 1) $email_include_signature = 0;

//$body = "Attached you will find the invoice for your FCS Service Dispatch work.";

$email_list = $_GET['email_list'];
$email_list = go_reg_replace("\n", ",", $email_list);
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
$email_body .= "<br><br>" . $signature;

$subject = "Service Dispatch Invoice";
$headers = "From: $mail_user_name <$mail_user_email>\n";
$headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
$headers .= "Bcc: $email_list";
	  
//email_q($mail_user_email, $subject, $body, $headers);
email_q($mail_user_email, $subject, $email_body, $headers, 'uploaded_files/download/' . $invoice_filename);

meta_redirect("fcs_sd_report_view" . $special_invoice . ".php?leak_id=$leak_id");
?>