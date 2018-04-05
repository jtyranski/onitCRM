<?php
include "includes/functions.php";

$act_id = go_escape_string($_POST['act_id']);
$reason = $_POST['reason'];
$submit1 = $_POST['submit1'];

$sql = "SELECT a.property_id, a.event, b.site_name, b.address, b.city, b.state, b.zip, date_format(a.date, \"%m/%d/%Y %r\") as datepretty 
from activities a, properties b 
where a.property_id=b.property_id and a.act_id='$act_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
$datepretty = stripslashes($record['datepretty']);
$event = stripslashes($record['event']);
$property_id = stripslashes($record['property_id']);

if($submit1=="Send"){
  $sql = "SELECT b.email from activities a, users b where a.scheduled_by=b.user_id and a.act_id='$act_id'";
  $to = getsingleresult($sql);
  
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email from users where user_id='" . $SESSION_USER_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $mail_from_name = stripslashes($record['fullname']);
  $mail_from_email = stripslashes($record['email']);
  
  $message = $mail_from_name . " cannot perform the following:<br><br>";
  $message .= $event . "<br><a href='" . $CORE_URL . "view_property.php?property_id=$property_id'>$site_name</a><br>";
  $message .= $address . "<br>" . $city . ", " . $state . " " . $zip . "<br>";
  $message .= $datepretty . "<br><br>For the following reason:<br>";
  $message .= nl2br($reason);
  
  $subject = $mail_from_name . " declines $event for $site_name";
  
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_from_name <$mail_from_email>\n";
  $headers .= "Return-Path: ". $mail_from_email;  // necessary for some emails such as aol
  
  email_q($to, $subject, $message, $headers);
  
  $sql = "DELETE from activities where act_id='$act_id'";
  executeupdate($sql);
  $sql = "DELETE from supercali_events where act_id='$act_id' and act_id != 0";
  executeupdate($sql);
}

meta_redirect("tool_approve_inspection.php");
  
  
?>