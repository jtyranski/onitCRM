<?php
exit;
include "includes/functions.php";

$sql = "SELECT leak_id, prospect_id, cron_alert, resource_id from am_leakcheck where open_resource=1";
$main_result = executequery($sql);
while($main_record = go_fetch_array($main_result)){
  $leak_id = $main_record['leak_id'];
  $prospect_id = $main_record['prospect_id'];
  $cron_alert = $main_record['cron_alert'];
  $resource_id = $main_record['resource_id'];
  //echo $leak_id . "<br>";
  $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
  $master_id = getsingleresult($sql);
  $sql = "SELECT first_sd_alert, second_sd_alert, third_sd_alert from master_list where master_id='$master_id'";
  $res2 = executequery($sql);
  $rec2 = go_fetch_array($res2);
  $first_sd_alert = $rec2['first_sd_alert'];
  $second_sd_alert = $rec2['second_sd_alert'];
  $third_sd_alert = $rec2['third_sd_alert'];
  if($cron_alert==0) $hours = $first_sd_alert;
  if($cron_alert==1) $hours = $first_sd_alert + $second_sd_alert;
  if($cron_alert==2) $hours = $first_sd_alert + $second_sd_alert + $third_sd_alert;
  
  $sql = "SELECT count(*) from am_leakcheck where leak_id='$leak_id' and date_add(sent_resource_date, interval $hours minute) < now()";
  $test = getsingleresult($sql);
  if($test){
    // send alert
	//echo $leak_id . " * " . $cron_alert . "<br>";
	
	
	  if($cron_alert==0){
	    $new_cron_alert = 1;
		$cron_alert_message = "SECOND NOTICE";
	  }
	  if($cron_alert==1){
	    $new_cron_alert = 2;
		$cron_alert_message = "FINAL NOTICE";
	  }
	  $sql = "SELECT dispatch_from_email, master_name, logo, emergency_time_frame, urgent_time_frame, scheduled_time_frame from master_list where master_id='" . $master_id . "'";
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
b.city, b.state, b.address, b.zip, a.prospect_id, b.am_user_id, 
a.priority_id, a.code, a.nte, a.nte_amount, a.labor_rate
from am_leakcheck a, properties b 
where a.property_id=b.property_id and 
a.leak_id=\"$leak_id\"";
$result = executequery($sql);


$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$property_id = stripslashes($record['property_id']);
$prospect_id = stripslashes($record['prospect_id']);
$am_user_id = stripslashes($record['am_user_id']);
$code = stripslashes($record['code']);
$priority_id = stripslashes($record['priority_id']);

$sf = $record['ximage'];

$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$address = stripslashes($record['address']);
$zip = stripslashes($record['zip']);

$nte = stripslashes($record['nte']);
$nte_amount = stripslashes($record['nte_amount']);
$labor_rate = stripslashes($record['labor_rate']);

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
  $message .= "<img src='" . $RO_URL . "uploaded_files/master_logos/" . $logo . "'><br><br>";
}
$message .= $cron_alert_message . "<br><br>";
$message .= "Dispatch #" . $leak_id . "<br><br>";
$message .= $site_name . "<br>";
$message .= $address . "<br>";
$message .= $city . ", " . $state . " " . $zip . "<br><br>";
$message .= "Site Contact: $site_manager_name<br><br>";
if($nte==1) $message .= "Not to exceed: $" . number_format($nte_amount, 2) . "<br>";
if($labor_rate != 0 && $labor_rate != "") $message .= "Labor Rate: $" . $labor_rate . " /hr<br>";

if($priority_id==3) $message .= "Targeted Response: $emergency_time_frame<br><br>";
if($priority_id==2) $message .= "Targeted Response: $urgent_time_frame<br><br>";
if($priority_id==1) $message .= "Targeted Response: $scheduled_time_frame<br><br>";

$message .= $mail_from_name . " is requesting a confirmation that your company can provide service on the above reference property within the targeted response time. Click the link below to confirm or deny this 
confirmation.<br><br>";
$message .= "<a href='" . $RO_URL . "public_resource_resolve.php?code=$code'>Show Confirmation</a>";
$message .= "</div>";

$ro_email = "";
$sql = "SELECT email from users where enabled=1 and master_id='$master_id' and alert_dispatch_approval=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $ro_email .= $record['email'] . ",";
}
$to = "";
$sql = "SELECT email from contacts where email != '' and prospect_id='$resource_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $to .= stripslashes($record['email']) . ",";
}
$headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  $headers .= "Bcc: $ro_email, $to\n";
$subject = "Service Dispatch request sent from " . $mail_from_name . " Dispatch #" . $leak_id;


//$to = "jwert@encitegroup.com";

if($cron_alert==0 || $cron_alert==1){
  email_q($mail_from_email, $subject, $message, $headers);
  $sql = "INSERT into am_leakcheck_resourcelog(leak_id, resource_id, ts, response, notes) values('$leak_id', '$resource_id', now(), 0, \"Sent Request $cron_alert_message\")";
  executeupdate($sql);
  $sql = "UPDATE am_leakcheck set cron_alert='$new_cron_alert' where leak_id='$leak_id'";
  executeupdate($sql);
}
if($cron_alert==2){
  $message = "<div style='font-family:Arial, Helvetica, sans-serif;'>";

  if($logo != ""){
    $message .= "<img src='" . $RO_URL . "uploaded_files/master_logos/" . $logo . "'><br><br>";
  }
  $message .= "The invitation to work on dispatch #" . $leak_id . " has expired<br><br</div>";
  $subject = "Service Dispatch expiration notice";
  email_q($mail_from_email, $subject, $message, $headers);
  $sql = "UPDATE am_leakcheck set deny_resource=1, deny_resource_date=now(), resource_id=0, open_resource=0 where leak_id='$leak_id'";
  executeupdate($sql);
  $sql = "INSERT into am_leakcheck_resourcelog(leak_id, resource_id, ts, response, notes) values('$leak_id', '$resource_id', now(), -1, \"Declined - No Response\")";
  executeupdate($sql);
  $sql = "INSERT into notes(prospect_id, date, event, regarding, note) values('$resource_id', now(), 'Declined Dispatch', 'Dispatch ID $leak_id', \"Declined - No Response\")";
  executeupdate($sql);
}



  }
}
	
	
?>
  
  