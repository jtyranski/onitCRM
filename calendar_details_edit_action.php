<?php
include "includes/functions.php";

//$user_id = $SESSION_USER_ID;
$act_id = go_escape_string($_POST['act_id']);
$user_id = $_POST['user_id'];
$scheduled_by = $_POST['scheduled_by'];
$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];
if($property_id == "") $property_id = 0;
$event = go_escape_string($_POST['event']);
  $datepretty = go_escape_string($_POST['datepretty']);
  $hourpretty = go_escape_string($_POST['hourpretty']);
  $minutepretty = go_escape_string($_POST['minutepretty']);
  $ampm = go_escape_string($_POST['ampm']);
  if($ampm == "PM") $hourpretty += 12;
  $timepretty = $hourpretty . ":" . $minutepretty . ":00";
$rollover = go_escape_string($_POST['rollover']);
if($rollover != 1) $rollover = 0;
  
  $contact = go_escape_string($_POST['contact']);
  $regarding = go_escape_string($_POST['regarding']);
$tm = go_escape_string($_POST['tm']);
$tm_user_id = $scheduled_by;
if($tm != 1){
  $tm = 0;
  $tm_user_id=0;
}

/*
$month = go_escape_string($_POST['month']);
$day = go_escape_string($_POST['day']);
$year = go_escape_string($_POST['year']);
*/

$date_parts = explode("/", $datepretty);
$fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

//$fixed_date = $year . "-" . $month . "-" . $day;
$fixed_date .= " " . $timepretty;

$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){

  $sql = "UPDATE activities set user_id='$user_id', contact=\"$contact\", regarding=\"$regarding\", date='$fixed_date'
	where act_id='$act_id'";
	executeupdate($sql);

  
  $sql = "SELECT event_id from supercali_events where act_id='$act_id'";
  $event_id = getsingleresult($sql);
  
  $sql = "UPDATE supercali_events set description=\"$regarding\", ro_user_id='$user_id' where event_id='$event_id'";
  executeupdate($sql);
  
  $sql = "UPDATE supercali_dates set date='$fixed_date', end_date='$fixed_date' where event_id='$event_id'";
  executeupdate($sql);
}


meta_redirect("calendar_activities.php");
?>

