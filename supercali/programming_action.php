<?php
include "../includes/functions.php";

$event_id = go_escape_string($_POST['event_id']);
$ro_user_id = go_escape_string($_POST['ro_user_id']);
$old_ro_user_id = go_escape_string($_POST['old_ro_user_id']);
$title = go_escape_string($_POST['title']);
$description = go_escape_string($_POST['description']);

$urgency = go_escape_string($_POST['urgency']);
$programming_type = go_escape_string($_POST['programming_type']);
$stage = go_escape_string($_POST['stage']);
$stage_old = go_escape_string($_POST['stage_old']);


$startdate = go_escape_string($_POST['startdate']);
$enddate = go_escape_string($_POST['enddate']);

$startdate_raw = go_escape_string($_POST['startdate']);
$enddate_raw = go_escape_string($_POST['enddate']);

$submit1 = go_escape_string($_POST['submit1']);

$valid_date = 1;
if($startdate=="" || $enddate=="") $valid_date = 0;

$date_parts = explode("/", $startdate);
$startdate = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

$date_parts = explode("/", $enddate);
$enddate = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

if($submit1 != ""){
  if($event_id=="new"){
    $sql = "INSERT into supercali_events(category_id, created_by, created_date) values(37, '" . $SESSION_USER_ID . "', now())";
	executeupdate($sql);
	$event_id = go_insert_id();
	$mail_subject = "($urgency) A project has been added to the programming queue";
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $SESSION_USER_ID . "'";
	$fullname = stripslashes(getsingleresult($sql));
	$mail_body = "Project: $title<br>";
	$mail_body .= "Created by: $fullname<br>";
	$mail_body .= "On: " . date("m/d/Y") . "<br>";
	$mail_body .= "Description: " . nl2br($description);
	$sendemail = 1;
  }
  
  
  if($stage != $stage_old && $sendemail != 1){
    $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $ro_user_id . "'";
	$fullname = stripslashes(getsingleresult($sql));
	$sql = "SELECT date_format(created_date, \"%m/%d/%Y\") as created_date, created_by from supercali_events where event_id='$event_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$created_date = $record['created_date'];
	$cb = $record['created_by'];
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $cb . "'";
	$created_by = stripslashes(getsingleresult($sql));
	$mail_subject = "($urgency) \"$title\" has been moved to $stage";
	$mail_body = "Project: $title<br>";
	$mail_body .= "Created by: $created_by<br>";
	$mail_body .= "On: " . $created_date . "<br>";
	$mail_body .= "Description: " . nl2br($description);
	$sendemail = 1;
  }
  
  if($old_ro_user_id != $ro_user_id && $sendemail != 1){
    $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $ro_user_id . "'";
	$fullname = stripslashes(getsingleresult($sql));
	$sql = "SELECT date_format(created_date, \"%m/%d/%Y\") as created_date, created_by from supercali_events where event_id='$event_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$created_date = $record['created_date'];
	$cb = $record['created_by'];
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $cb . "'";
	$created_by = stripslashes(getsingleresult($sql));
	$mail_subject = "($urgency) \"$title\" has been assigned to $fullname";
	$mail_body = "Project: $title<br>";
	$mail_body .= "Created by: $created_by<br>";
	$mail_body .= "On: " . $created_date . "<br>";
	$mail_body .= "Start: $startdate_raw<br>";
	$mail_body .= "End: $enddate_raw<br>";
	$mail_body .= "Description: " . nl2br($description);
	$sendemail = 1;
  }
  
  
  
  
  $sql = "UPDATE supercali_events set ro_user_id='$ro_user_id', title=\"$title\", description=\"$description\", master_id='" . $SESSION_MASTER_ID . "', 
  urgency=\"$urgency\", programming_type=\"$programming_type\", stage=\"$stage\"";
  if($stage != $stage_old) $sql .= ", stage_date=now()";
  $sql .= " where event_id='$event_id'";
  executeupdate($sql);
  
  if($valid_date == 1){
    $sql = "DELETE from supercali_dates where event_id='$event_id'";
    executeupdate($sql);
  
    $span = GetDays($startdate, $enddate, "+1 day");
	for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
	}
  }
  
  
  if (is_uploaded_file($_FILES['attachment']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], "../uploaded_files/programming/". $filename);
	
	$sql = "UPDATE supercali_events set attachment='$filename' where event_id='$event_id'";
	executeupdate($sql);
  }
  
  if($submit1=="Complete"){
    $sql = "UPDATE supercali_events set complete=1, complete_date=now(), complete_dev=0 where event_id='$event_id'";
	executeupdate($sql);
	
	if($sendemail != 1){
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $ro_user_id . "'";
	$fullname = stripslashes(getsingleresult($sql));
	$sql = "SELECT date_format(created_date, \"%m/%d/%Y\") as created_date, date_format(complete_date, \"%m/%d/%Y\") as complete_date, created_by from supercali_events where event_id='$event_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$created_date = $record['created_date'];
	$complete_date = $record['complete_date'];
	$cb = $record['created_by'];
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $cb . "'";
	$created_by = stripslashes(getsingleresult($sql));
	$mail_subject = "($urgency) \"$title\" has been completed";
	$mail_body = "Project: $title<br>";
	$mail_body .= "Created by: $created_by<br>";
	$mail_body .= "On: " . $created_date . "<br>";
	$mail_body .= "Start: $startdate_raw<br>";
	$mail_body .= "End: $complete_date<br>";
	$mail_body .= "Description: " . nl2br($description);
	$sendemail = 1;
	}
	
  }
  if($submit1=="Complete Dev"){
    $sql = "UPDATE supercali_events set complete_dev=1, complete_dev_date=now() where event_id='$event_id'";
	executeupdate($sql);
	
	
	if($sendemail != 1){
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $ro_user_id . "'";
	$fullname = stripslashes(getsingleresult($sql));
	$sql = "SELECT date_format(created_date, \"%m/%d/%Y\") as created_date, date_format(complete_dev_date, \"%m/%d/%Y\") as complete_date, created_by from supercali_events where event_id='$event_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$created_date = $record['created_date'];
	$complete_date = $record['complete_date'];
	$cb = $record['created_by'];
	$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $cb . "'";
	$created_by = stripslashes(getsingleresult($sql));
	$mail_subject = "($urgency) \"$title\" has been completed on the Dev Site";
	$mail_body .= "DEV SITE COMPLETE ONLY<br>";
	$mail_body .= "Project: $title<br>";
	$mail_body .= "Created by: $created_by<br>";
	$mail_body .= "On: " . $created_date . "<br>";
	$mail_body .= "Start: $startdate_raw<br>";
	$mail_body .= "End: $complete_date<br>";
	$mail_body .= "Description: " . nl2br($description);
	$sendemail = 1;
	}
  }
  
}

if($sendemail==1){
  $sql = "SELECT created_by from supercali_events where event_id='$event_id'";
  $created_by = getsingleresult($sql);
  
  $sql = "SELECT email from users where programming_alert=1 or user_id='$created_by'";
  $result = executequery($sql);
  $to = "";
  while($record = go_fetch_array($result)){
    $to .= stripslashes($record['email']) . ",";
  }
  $to = go_reg_replace("\,$", "", $to);
  
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: FCS Control <info@fcscontrol.com>\n";
  $headers .= "Return-Path: info@fcscontrol.com\n";  // necessary for some emails such as aol
  $headers .= "Bcc: $to\n";
  email_q("", $mail_subject, $mail_body, $headers);
}

meta_redirect("index.php");
?>
