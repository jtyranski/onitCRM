<?php
include "includes/functions.php";

$leak_id = go_escape_string($_POST['leak_id']);
$submit1 = $_POST['submit1'];

$sql = "SELECT b.timezone from am_leakcheck a, properties b where a.property_id=b.property_id and a.leak_id='$leak_id'";
$timezone = getsingleresult($sql);

if($submit1=="Enter ETA"){
  $eta_month = go_escape_string($_POST['eta_month']);
  $eta_day = go_escape_string($_POST['eta_day']);
  $eta_year = go_escape_string($_POST['eta_year']);
  $eta_hour = go_escape_string($_POST['eta_hour']);
    
  $eta_minute = go_escape_string($_POST['eta_minute']);
  $eta_ampm = go_escape_string($_POST['eta_ampm']);
  if($eta_ampm == "PM" && $eta_hour < 12) $eta_hour += 12;
  if($eta_ampm == "AM" && $eta_hour ==12) $eta_hour = 0;
  $eta_timepretty = $eta_hour . ":" . $eta_minute . ":00";
  $eta_date = $eta_year . "-" . $eta_month . "-" . $eta_day;
  $eta_date .= " " . $eta_timepretty;
  
  $sql = "UPDATE am_leakcheck set eta_date = '$eta_date', status='Arrival ETA' where leak_id='$leak_id'";
  executeupdate($sql);
  
  $mail_from_email = "service@fcscontrol.com";
  $mail_from_name = $MAIN_CO_NAME;
  
    
  include "../sd_email.php";
  $sql = "SELECT body from templates where name='Leakcheck - General'";
  $body = stripslashes(getsingleresult($sql));
  $body = str_replace("[MESSAGE]", $table, $body);
  
  $subject = "Service Dispatch Arrival ETA";
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  
  email_q($fcs_email, $subject, $body, $headers);
  
  email_q($ro_email, $subject, $body, $headers);
  
  meta_redirect("service_dispatch_details.php?leak_id=$leak_id");
}

if($submit1=="Punch In" || $submit1=="Punch Out"){
  $serviceman = go_escape_string($_POST['serviceman']);
  $punch_month = go_escape_string($_POST['punch_month']);
  $punch_day = go_escape_string($_POST['punch_day']);
  $punch_year = go_escape_string($_POST['punch_year']);
  $punch_hour = go_escape_string($_POST['punch_hour']);
  $punch_minute = go_escape_string($_POST['punch_minute']);
  $punch_ampm = go_escape_string($_POST['punch_ampm']);
  $timezone = go_escape_string($_POST['timezone']);
  if($punch_ampm == "PM" && $punch_hour < 12) $punch_hour += 12;
  if($punch_ampm == "AM" && $punch_hour ==12) $punch_hour = 0;
  $punch_timepretty = $punch_hour . ":" . $punch_minute . ":00";
  $punch_date = $punch_year . "-" . $punch_month . "-" . $punch_day;
  $punch_date .= " " . $punch_timepretty;
  
  $invoice_type = go_escape_string($_POST['invoice_type']);
  $time_id = go_escape_string($_POST['time_id']);
  
  $sql = "UPDATE am_leakcheck set invoice_type='$invoice_type' where leak_id='$leak_id'";
  executeupdate($sql);
  
  if($time_id==0){
    $sql = "INSERT into am_leakcheck_time(leak_id, time_in, complete, servicemen_1_or_2, timezone) values('$leak_id', '$punch_date', '0', 
	'$serviceman', '$timezone')";
    executeupdate($sql);
  }
  else {
    $sql = "UPDATE am_leakcheck_time set time_out='$punch_date', complete='1' where id='$time_id'";
    executeupdate($sql);
  
    $sql = "SELECT date_format(time_in, \"%k\") as in_hour, date_format(time_in, \"%i\") as in_minute, 
    date_format(time_out, \"%k\") as out_hour, date_format(time_out, \"%i\") as out_minute
    from am_leakcheck_time where id='$time_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $in_hour = $record['in_hour'];
    $in_minute = $record['in_minute'];
    $out_hour = $record['out_hour'];
    $out_minute = $record['out_minute'];
  
    if($in_minute == 15) $in_minute = .25;
    if($in_minute == 30) $in_minute = .5;
    if($in_minute == 45) $in_minute = .75;
  
    if($out_minute == 15) $out_minute = .25;
    if($out_minute == 30) $out_minute = .5;
    if($out_minute == 45) $out_minute = .75;
  
    $in = $in_hour + $in_minute;
    $out = $out_hour + $out_minute;
  
    $total_hours = $out - $in;
    if($total_hours < 0) $total_hours += 24;
  
    $sql = "UPDATE am_leakcheck_time set total_hours='$total_hours' where id='$time_id'";
    executeupdate($sql);
  }
  
  $sql = "SELECT date_format(inprogress_date, \"%Y\") from am_leakcheck where leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test=="0000"){
    $sql = "UPDATE am_leakcheck set inprogress_date=now(), status='In Progress' where leak_id='$leak_id'";
	executeupdate($sql);
	
	$mail_from_email = "service@fcscontrol.com";
    $mail_from_name = $MAIN_CO_NAME;
  
  
    include "../sd_email.php";
    $sql = "SELECT body from templates where name='Leakcheck - General'";
    $body = stripslashes(getsingleresult($sql));
    $body = str_replace("[MESSAGE]", $table, $body);
  
    $subject = "Service Dispatch Update";
    $headers = "Content-type: text/html; charset=iso-8859-1\n";
    $headers .= "From: $mail_from_name <$mail_from_email>\n";
    $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  
    email_q($fcs_email, $subject, $body, $headers);
  
    email_q($ro_email, $subject, $body, $headers);
  }
  
  meta_redirect("service_dispatch_details.php?leak_id=$leak_id");
}

if($submit1=="Mark Project Complete"){
  $sql = "SELECT b.site_name, concat(c.firstname, ' ', c.lastname) as fullname 
  from am_leakcheck a, properties b, users c where
  a.property_id=b.property_id and a.servicemen_id = c.user_id and a.leak_id='$leak_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $site_name = stripslashes($record['site_name']);
  $fullname = stripslashes($record['fullname']);
  
  $subject = "Service Dispatch Ready for Resolved - #" . $leak_id;
  $message = "$fullname has marked the following Service Dispatch as ready to be marked resolved:<br><br>";
  $message .= "Dispatch ID: $leak_id<br>";
  $message .= "Site: $site_name";
  
  $mail_from_email = "service@fcscontrol.com";
  $mail_from_name = $MAIN_CO_NAME;
  
  $sql = "SELECT b.master_id from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and a.leak_id='$leak_id'";
  $master_id = getsingleresult($sql);
  $sql = "SELECT email from users where master_id='$master_id' and enabled=1 and resource=0 and alert_dispatch_approval=1";
  $result = executequery($sql);
  $to = "";
  while($record = go_fetch_array($result)){
    $to .= stripslashes($record['email']) . ",";
  }
  
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: $mail_from_email\n";  // necessary for some emails such as aol
  
  //email_q("jwert@encitegroup.com", $subject, $message, $headers);
  email_q($to, $subject, $message, $headers);
  
  $sql = "UPDATE am_leakcheck set mobile_display=0 where leak_id='$leak_id'";
  executeupdate($sql);
  
  meta_redirect("service_dispatch.php");
}

  
  
	
	
