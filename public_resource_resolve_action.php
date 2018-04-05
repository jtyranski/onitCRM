<?php
include "includes/functions.php";

$code = go_escape_string($_POST['code']);

$sql = "SELECT property_id from am_leakcheck where code='$code'";
$property_id = getsingleresult($sql);
$sql = "SELECT timezone from properties where property_id='$property_id'";
$timezone = getsingleresult($sql);

  $eta_date_pretty = go_escape_string($_POST['eta_date_pretty']);
  $hour = go_escape_string($_POST['hour']);
  $minute = go_escape_string($_POST['minute']);
  $ampm = go_escape_string($_POST['ampm']);
  $hour = $hour - $timezone;
  if($ampm == "PM" && $hour < 12) $hour += 12;
  if($ampm == "AM" && $hour == 12) $hour = 0;
  $timepretty = $hour . ":" . $minute . ":00";
  $date_parts = explode("/", $eta_date_pretty);
  $eta_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  $eta_date .= " " . $timepretty;
$eta_message = go_escape_string($_POST['eta_message']);

$submit1 = go_escape_string($_POST['submit1']);

if($submit1=="YES! I can meet this Service Dispatch request"){
  $sql = "SELECT leak_id from am_leakcheck where code='$code'";
  $leak_id = getsingleresult($sql);
  
  $sql = "UPDATE am_leakcheck set status='Arrival ETA', eta_date=\"$eta_date\", eta_message=\"$eta_message\", accept_resource=1, open_resource=0 where leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "SELECT dispatch_date, eta_date, inprogress_date, fix_date, confirm_date, invoice_date, closed_date from am_leakcheck where leak_id='$leak_id'";
  $result = executequery($sql);
  $recordmax = go_fetch_array($result);
  $last_update = max($recordmax);
  $sql = "UPDATE am_leakcheck set lastupdate='$last_update' where leak_id='$leak_id'";
  executeupdate($sql);
  
  
  $sql = "SELECT b.master_id, a.resource_id from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $master_id = $record['master_id'];
  $resource_id = $record['resource_id'];
  $sql = "INSERT into am_leakcheck_resourcelog(leak_id, resource_id, ts, response, notes) values('$leak_id', '$resource_id', now(), 1, \"$eta_message\")";
  executeupdate($sql);
  
  $sql = "SELECT company_name from prospects where prospect_id='$resource_id'";
  $company_name = stripslashes(getsingleresult($sql));
  $sql = "SELECT c.site_name from am_leakcheck a, properties c where
  a.property_id=c.property_id and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  //$firstname = stripslashes($record['firstname']);
  $site_name = stripslashes($record['site_name']);
  $message = $company_name . " has agreed to meet the Service Dispatch request for $site_name, dispatch ID: $leak_id";
  $subject = $message;
  
  $sql = "SELECT dispatch_from_email, master_name from master_list where master_id='" . $master_id . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $mail_from_email = stripslashes($record['dispatch_from_email']);
  $mail_from_name = stripslashes($record['master_name']);
  
 
  $sql = "SELECT email from users where master_id='$master_id' and alert_dispatch_approval=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $to .= $record['email'] . ",";
  }
  
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  
  email_q($to, $subject, $message, $headers);
  
  
  
  $message = "An arrival ETA has been established for the Service Dispatch at your location.<br><br>ETA: $eta_date<br><br>";
  $subject = "Service Dispatch Arrival ETA";


  
  
  
  include "sd_email.php";
  $sql = "SELECT body from templates where name='Leakcheck - General'";
  $body = stripslashes(getsingleresult($sql));
  $body = str_replace("[MESSAGE]", $table, $body);
  

  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  
  if($fcs_email != "") email_q($fcs_email, $subject, $body, $headers);
  
  $ro_body = $body;
  if($status=="Arrival ETA") $ro_body .= "<br><br>ETA Notes:<br>" . nl2br($eta_message);
  //$ro_body .= "<br>This message sent to following FCS view users: $fcs_email";
  if($ro_email != "") email_q($ro_email, $subject, $ro_body, $headers);
  
  $pdf_output = "F";
  include "public_resource_resolve_pdf.php";
  include 'mail_attachment.php';
  $filename = "SERVICE_DISPATCH_" . $leak_id . ".pdf";
  $headers = "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email";  // necessary for some emails such as aol
  $sql = "SELECT b.email from am_leakcheck a, users b where a.servicemen_id=b.user_id and a.leak_id='$leak_id'";
  $to = stripslashes(getsingleresult($sql));
  //$to = "jwert@encitegroup.com";
  $subject = "Service Dispatch #" . $leak_id . " Information";
  $email_body = "Attached you will find a signable pdf with information regarding this Service Dispatch.";
  email_q($to, $subject, $email_body, $headers, 'uploaded_files/invoices/' . $filename);
  
}

if($submit1=="NO, I am unable to meet this Service Dispatch request"){
  $sql = "SELECT leak_id from am_leakcheck where code='$code'";
  $leak_id = getsingleresult($sql);
  
  $sql = "SELECT resource_id from am_leakcheck where leak_id='$leak_id'";
  $resource_id = getsingleresult($sql);
  $sql = "SELECT company_name from prospects where prospect_id='$resource_id'";
  $company_name = stripslashes(getsingleresult($sql));
  
  $sql = "UPDATE am_leakcheck set deny_resource=1, deny_resource_date=now(), resource_id=0, open_resource=0 where leak_id='$leak_id'";
  executeupdate($sql);
  
  $sql = "SELECT b.master_id from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $master_id = $record['master_id'];
  
  
  
  $sql = "INSERT into am_leakcheck_resourcelog(leak_id, resource_id, ts, response, notes) values('$leak_id', '$resource_id', now(), -1, \"$eta_message\")";
  executeupdate($sql);
  $sql = "INSERT into notes(prospect_id, date, event, regarding, note) values('$resource_id', now(), 'Declined Dispatch', 'Dispatch ID $leak_id', \"$eta_message\")";
  executeupdate($sql);
  
  $sql = "SELECT c.site_name from am_leakcheck a, properties c where
  a.property_id=c.property_id and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  //$firstname = stripslashes($record['firstname']);
  $site_name = stripslashes($record['site_name']);
  $message = $company_name . " is unable to meet the Service Dispatch request for $site_name, dispatch ID: $leak_id";
  $subject = $message;
  
  $sql = "SELECT dispatch_from_email, master_name from master_list where master_id='" . $master_id . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $mail_from_email = stripslashes($record['dispatch_from_email']);
  $mail_from_name = stripslashes($record['master_name']);
  
  $sql = "SELECT email from users where master_id='$master_id' and alert_dispatch_approval=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $to .= $record['email'] . ",";
  }
  
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  
  email_q($to, $subject, $message, $headers);
}

$_SESSION['sess_msg'] = "Thank you for your response to this Service Dispatch.";
meta_redirect("public_resource_resolve.php?code=$code");

  
  
  
?>