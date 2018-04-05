<?php
include "includes/functions.php";

$act_id = $_POST['act_id'];
$personal = $_POST['personal'];

//$user_id = $SESSION_USER_ID;
$user_id = $_POST['user_id'];
$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];
if($property_id == "") $property_id = 0;
$event = go_escape_string($_POST['event']);
$act_result = go_escape_string($_POST['act_result']);

$notes = go_escape_string($_POST['notes']);
  $datepretty = go_escape_string($_POST['datepretty']);
  $hourpretty = go_escape_string($_POST['hourpretty']);
  $minutepretty = go_escape_string($_POST['minutepretty']);
  $ampm = go_escape_string($_POST['ampm']);
  if($ampm == "PM") $hourpretty += 12;
  $timepretty = $hourpretty . ":" . $minutepretty . ":00";
  $rollover = 1;
  
  $contact = go_escape_string($_POST['artistName']);
  $regarding = go_escape_string($_POST['regarding']);

$date_parts = explode("/", $datepretty);
$fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
$fixed_date .= " " . $timepretty;

$schedule_next = go_escape_string($_POST['schedule_next']);
if($schedule_next != 1) $schedule_next = 0;

$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){

	
    $sql = "UPDATE activities set complete=1, complete_date=now(), act_result = \"$act_result\" 
	where act_id='$act_id'";
	executeupdate($sql);
	$sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
	executeupdate($sql);
	
	$sql = "UPDATE properties set lastaction=now() where property_id='$property_id'";
	executeupdate($sql);
	$sql = "UPDATE prospects set lastaction=now() where prospect_id='$prospect_id'";
	executeupdate($sql);

    $sql = "SELECT * from activities where act_id='$act_id'";
	$result = executequery($sql);
	$old = go_fetch_array($result);
	
	if($old['event']=="Conference Call"){
	  $sql = "UPDATE activities set complete=1, complete_date=now(), act_result = \"$act_result\" where cc_act_id='$act_id'";
	  executeupdate($sql);
	}
	
	if($old['event']=="Risk Report Presentation" && $act_result != "Attempted"){
	
	  
	  $sql = "UPDATE opportunities set opp_stage_id=7 where property_id='$property_id' and product='Roof Management - Proactive' and display=1";
      executeupdate($sql);
	  
	  $sql = "SELECT inspection_frequency from properties where property_id='$property_id'";
	  $inspection_frequency = getsingleresult($sql);
	  if($inspection_frequency==0) $inspection_frequency = 3;
	  
	  $sql = "SELECT eta_date, leak_id from am_leakcheck where property_id='$property_id' and (invoice_type='RTM' or invoice_type='RTM - Prevent') order by leak_id desc limit 1";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $eta_date = $record['eta_date'];
	  $leak_id = $record['leak_id'];
	  if($leak_id != ""){
	    $sql = "INSERT into am_leakcheck_future(leak_id, property_id, next_date) values('$leak_id', '$property_id', '$eta_date')";
		executeupdate($sql);
		$sql = "SELECT id from am_leakcheck_future where property_id='$property_id' order by id desc limit 1";
		$fid = getsingleresult($sql);
		$sql = "UPDATE am_leakcheck_future set next_date = date_add(next_date, interval $inspection_frequency month) where id='$fid'";
		executeupdate($sql);
	  }
	}
	  
	
	$sql = "SELECT * from activities where act_id='$act_id'";
	$result = executequery($sql);
	$old = go_fetch_array($result);
	
	$sql = "INSERT into notes (user_id, property_id, prospect_id, act_id, date, event, contact, regarding, note, result) values(
	'" . $SESSION_USER_ID . "', '" . $old['property_id'] . "', '" . $old['prospect_id'] . "', '$act_id', now(), '" . $old['event'] . "', 
	\"" . $old['contact'] . "\", \"" . $old['regarding'] . "\", \"$notes\", '$act_result')";
	executeupdate($sql);
	
	
	if($schedule_next==1){
    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by, 
	rollover) values (
	'$prospect_id', '$property_id', '$user_id', \"$fixed_date\", '$event', \"$contact\", \"$regarding\", 
	'" . $SESSION_USER_ID . "', '$rollover')";
	executeupdate($sql);
	}
	else {
	  $event = "";
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
	  $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
      where property_id='$property_id'";
	  executeupdate($sql);
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
  quick_approve, prospect_id, property_id, what, ro_user_id, act_id, master_id) 
  values 
  (\"$event - $act_id\", '1', '1', \"$description\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
   '".$property_id."', '".$what."', '".$user_id."', '$act_id', '" . $SESSION_MASTER_ID . "')";
  executeupdate($sql);
  
  $sql = "SELECT event_id from supercali_events where property_id='$property_id' order by event_id desc limit 1";
  $event_id = getsingleresult($sql);
  
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', '$date', '$date')";
  executeupdate($sql);
}

}
meta_redirect("calendar.php?personal=$personal");

?>
