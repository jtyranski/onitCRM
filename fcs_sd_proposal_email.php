<?php 
include_once "includes/functions.php"; 



$leak_id = $_GET['leak_id'];

$sql = "SELECT section_id, property_id from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$section_id = $record['section_id'];
$property_id = $record['property_id'];
$sql = "SELECT id from document_proposal_fcs where multisection='$section_id' order by id desc limit 1";
$doc_id = getsingleresult($sql);
  
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $FCS_URL . "report_print_presentation_pdf_property.php?property_id=$property_id&ipod=1");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);

$sql = "SELECT pdf_report_url from properties where property_id='$property_id'";
$filename_report_x = stripslashes(getsingleresult($sql));
	  
$filename_report = $UP_FCSVIEW . "uploaded_files/roofreport/" . $filename_report_x;

$urlscript = "public_nocostproposal_fcs_pdf_unsigned.php";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $CORE_URL . $urlscript . "?pdf_output=F&leak_id=$leak_id&iphone=54321");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
$filename_proposal = "uploaded_files/proposals/NOCOSTPROPOSAL_UNSIGNED_" . $leak_id . ".pdf";

$sql = "SELECT ncp_filename from am_leakcheck where leak_id='$leak_id'";
$ncp_filename = getsingleresult($sql);
if($ncp_filename ==""){
  $ncp_filename = uniqueTimeStamp() . ".pdf";
  $sql = "UPDATE am_leakcheck set ncp_filename = '$ncp_filename' where leak_id='$leak_id'";
  executeupdate($sql);
}

$filename_literature = "";
$sql = "SELECT literature from am_leakcheck where leak_id='$leak_id'";
$literature = stripslashes(getsingleresult($sql));
if($literature != "" && go_reg("\.pdf$", $literature)) $filename_literature = "uploaded_files/proposals/$literature";


$fileArray= array($filename_report,$filename_proposal);
if($filename_literature != "") $fileArray[] = $filename_literature;
$datadir = "uploaded_files/download/";
$outputName = $datadir. $ncp_filename;

$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
//Add each pdf file to the end of the command
foreach($fileArray as $file) {
    $cmd .= $file." ";
}
$result = shell_exec($cmd);

$email_body = nl2br($_GET['email_body']);
$email_include_signature = $_GET['email_include_signature'];
if($email_include_signature != 1) $email_include_signature = 0;

//$body = "Attached you will find the invoice for your FCS Service Dispatch work.";

$email_list = $_GET['email_list'];
$email_list = go_reg_replace("\n", ",", $email_list);
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature
	from users where user_id='" . $SESSION_USER_ID . "'";
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

$subject = "Proposal";
$headers = "From: $mail_user_name <$mail_user_email>\n";
$headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
$headers .= "Bcc: $email_list";
	  
//email_q($mail_user_email, $subject, $body, $headers);
email_q($mail_user_email, $subject, $email_body, $headers, $outputName);
//email_q($mail_user_email, $subject, $email_body, $headers, $filename_proposal);

meta_redirect("fcs_sd_report_view" . $special_invoice . ".php?leak_id=$leak_id");
?>