<?php
include "includes/functions.php";

//$user_id = $SESSION_USER_ID;
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
  
  $contact = go_escape_string($_POST['artistName']);
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
    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by, tm, 
	rollover, tm_user_id) values (
	'$prospect_id', '$property_id', '$user_id', \"$fixed_date\", '$event', \"$contact\", \"$regarding\", 
	'$scheduled_by', '$tm', '$rollover', '$tm_user_id')";
	executeupdate($sql);
	
    if($SESSION_MASTER_ID != 1){
	  $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
      executeupdate($sql);
	}

if($event=="Bid Presentation" || $event == "Inspection" || $event=="Risk Report Presentation" || $event == "FCS Meetings" || $event == "Calendar - Other"){
  
  
  $sql = "SELECT act_id from activities where property_id='$property_id' and user_id='$user_id' order by act_id desc limit 1";
  $act_id = getsingleresult($sql);
  
  // re-acquire values (just for sake of seeing them all on this script)
  $sql = "SELECT act_id, user_id, prospect_id, property_id, date, regarding from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $user_id = stripslashes($record['user_id']);
  $prospect_id = stripslashes($record['prospect_id']);
  $property_id = stripslashes($record['property_id']);
  $date = stripslashes($record['date']);
  $regarding = $record['regarding'];
  
  switch($event){
    case "Bid Presentation":{
	  $what = "BP";
	  $category_id = 22;
	  $description = $event;
	  break;
	}
	case "Inspection":{
	  $what = "I";
	  $category_id = 23;
	  $description = $event;
	  break;
	}
	case "Risk Report Presentation":{
	  $what = "RRP";
	  $category_id = 24;
	  $description = $event;
	  break;
	}
	case "FCS Meetings":{
	  $what = "UM";
	  $category_id = 25;
	  $description = $event;
	  break;
	}
	case "Calendar - Other":{
	  $what = "O";
	  $category_id = 26;
	  $description = $regarding;
	  break;
	}
  }
  
  
  $sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
  executeupdate($sql);
  
  $sauce = md5(time());
  $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
  quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id) 
  values 
  (\"$event - $act_id\", '1', '1', \"$description\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
   '".$property_id."', '".$what."','0', '".$user_id."', '$act_id')";
  executeupdate($sql);
  
  $sql = "SELECT event_id from supercali_events where property_id='$property_id' order by event_id desc limit 1";
  $event_id = getsingleresult($sql);
  
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', '$date', '$date')";
  executeupdate($sql);
}

}


?>
<script>
parent.location.href="contacts.php";
</script>
