<?php
include "includes/functions.php";

$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
$special_invoice = 2;
//$special_invoice = "";

$leak_id = $_GET['leak_id'];
$resource_id = $_GET['resource_id'];

  $sql = "SELECT dispatch_from_email, master_name, logo, emergency_time_frame, urgent_time_frame, scheduled_time_frame from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $mail_from_email = stripslashes($record['dispatch_from_email']);
  $mail_from_name = stripslashes($record['master_name']);
  $logo = stripslashes($record['logo']);
  $emergency_time_frame = stripslashes($record['emergency_time_frame']);
  $urgent_time_frame = stripslashes($record['urgent_time_frame']);
  $scheduled_time_frame = stripslashes($record['scheduled_time_frame']);

$sql = "SELECT b.site_name,  
a.notes, date_format(a.dispatch_date, \"%m/%d/%Y\") as datepretty,  
date_format(dispatch_date, \"%r\") as timepretty, a.property_id, a.section_type, 
b.city, b.state, b.address, b.zip, a.prospect_id, b.am_user_id, b.timezone, a.invoice_id, a.invoice_type, a.other_cost, 
a.priority_id, a.code, a.nte, a.nte_amount, a.labor_rate, date_format(a.resource_due_date, \"%m/%d/%Y\") as resource_due_date_pretty, a.upsell, 
a.po_number, a.notes, a.additional_notes, b.city, b.state
from am_leakcheck a, properties b 
where a.property_id=b.property_id and 
a.leak_id=\"$leak_id\"";
$result = executequery($sql);


$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$property_id = stripslashes($record['property_id']);
$prospect_id = stripslashes($record['prospect_id']);
$am_user_id = stripslashes($record['am_user_id']);
$code = stripslashes($record['code']);
$priority_id = stripslashes($record['priority_id']);
$invoice_id = stripslashes($record['invoice_id']);
$invoice_type = stripslashes($record['invoice_type']);
$other_cost = stripslashes($record['other_cost']);
$upsell = stripslashes($record['upsell']);
$other_cost -= $upsell;

$sf = $record['ximage'];

$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$address = stripslashes($record['address']);
$zip = stripslashes($record['zip']);

$nte = stripslashes($record['nte']);
$nte_amount = stripslashes($record['nte_amount']);
$labor_rate = stripslashes($record['labor_rate']);
$resource_due_date_pretty = stripslashes($record['resource_due_date_pretty']);

$timezone = stripslashes($record['timezone']);
$po_number = stripslashes($record['po_number']);
$notes = stripslashes($record['notes']);
$additional_notes = stripslashes($record['additional_notes']);

switch($timezone){
  case 1:{
    $tz_display = "EST";
	break;
  }
  case 0:{
    $tz_display = "CST";
	break;
  }
  case -1:{
    $tz_display = "MST";
	break;
  }
  case -2:{
    $tz_display = "PST";
	break;
  }
  default:{
    $tz_display = "CST";
	break;
  }
}

$sql = "SELECT *, concat(firstname, ' ', lastname) as fullname from am_users where user_id='$am_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_manager_name = stripslashes($record['fullname']);
$site_manager_photo = $record['photo'];
$site_manager_title = stripslashes($record['title']);
$site_manager_phone = stripslashes($record['phone']);
$site_manager_cell = stripslashes($record['cell']);
$site_manager_email = stripslashes($record['email']);

$message = "<div style='font-family:Arial, Helvetica, sans-serif;'>";

if($logo != ""){
  $message .= "<img src='" . $SITE_URL . "uploaded_files/master_logos/" . $logo . "'><br><br>";
}

$message .= "Invoice #" . $invoice_id . "<br><br>";
$message .= "PO #" . $po_number . "<br><br>";
$message .= $site_name . "<br>";
$message .= $address . "<br>";
$message .= $city . ", " . $state . " " . $zip . "<br><br>";
$message .= "Site Contact: $site_manager_name<br><br>";
if($priority_id != 4){
  if($invoice_type=="Billable - Contract"){
    $message .= "Contract Amount: $" . number_format($other_cost, 2) . "<br>";
  }
  else {
    if($nte==1) $message .= "Not to exceed: $" . number_format($nte_amount, 2) . "<br>";
    if($labor_rate != 0 && $labor_rate != "") $message .= "Labor Rate: $" . $labor_rate . " /hr<br>";
  }
}

if($priority_id==3) $message .= "Targeted Response: $emergency_time_frame<br><br>";
if($priority_id==2) $message .= "Targeted Response: $urgent_time_frame<br><br>";
if($priority_id==1) $message .= "Targeted Response: $scheduled_time_frame<br><br>";

if($notes != "") $message .= "Service Dispatch Notes:<br>" . nl2br($notes) . "<br><br>";
if($additional_notes != "") $message .= "Additional Notes:<br>" . nl2br($additional_notes) . "<br><br>";

if($resource_due_date_pretty != "00/00/0000") $message .= "Must complete by: $resource_due_date_pretty<br><br>";

if($priority_id==4){
  $message .= $mail_from_name . " is requesting a confirmation that your company can provide a 'No Cost Proposal' on the above referenced property. Click the link below to confirm or deny this 
  confirmation.<br><br>";
}
else {
  $message .= $mail_from_name . " is requesting a confirmation that your company can provide service on the above reference property within the targeted response time. Click the link below to confirm or deny this 
  confirmation.<br><br>";
}
$message .= "<a href='" . $SITE_URL . "public_resource_resolve.php?code=$code'>Show Confirmation</a>";
$message .= "</div>";

$headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
$subject = "Service Dispatch Request for: Dispatch #" . $invoice_id . " " . $site_name . " " . $city . ", " . $state;

$sql = "SELECT email from contacts where email != '' and prospect_id='$resource_id' and prospect_id != 0";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $to .= stripslashes($record['email']) . ",";
}
$headers .= "Bcc: $to\n";
/*
$sql = "SELECT email from users where user_id='$servicemen_id'";
$to = getsingleresult($sql);
*/

email_q("", $subject, $message, $headers);
$sql = "UPDATE am_leakcheck set sent_resource_date=now(), open_resource=1, deny_resource=0 where leak_id='$leak_id'";
executeupdate($sql);
$sql = "INSERT into am_leakcheck_resourcelog(leak_id, resource_id, ts, response, notes) values('$leak_id', '$resource_id', now(), 0, \"Sent Request\")";
executeupdate($sql);

meta_redirect("fcs_sd_report_view" . $special_invoice . ".php?leak_id=$leak_id");


?>