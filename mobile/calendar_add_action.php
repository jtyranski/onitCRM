<?php
include "includes/functions.php";

//$user_id = $SESSION_USER_ID;
$user_id = $_POST['user_id'];
$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];
if($property_id == "") $property_id = 0;
$event = go_escape_string($_POST['event']);
$notes = go_escape_string($_POST['notes']);
$priority = go_escape_string($_POST['priority']);
  $datepretty = go_escape_string($_POST['datepretty']);
  $hourpretty = go_escape_string($_POST['hourpretty']);
  $minutepretty = go_escape_string($_POST['minutepretty']);
  $ampm = go_escape_string($_POST['ampm']);
  if($ampm == "PM") $hourpretty += 12;
  $timepretty = $hourpretty . ":" . $minutepretty . ":00";
  $rollover = 1;
  
  $contact = go_escape_string($_POST['artistName']);
  $regarding = go_escape_string($_POST['regarding']);

$month = go_escape_string($_POST['month']);
$day = go_escape_string($_POST['day']);
$year = go_escape_string($_POST['year']);

/*
$date_parts = explode("/", $datepretty);
$fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
*/
$fixed_date = $year . "-" . $month . "-" . $day;
$fixed_date .= " " . $timepretty;

$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  if($SESSION_MASTER_ID != 1){
	  $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
      executeupdate($sql);
  }
  
    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by, priority, 
	rollover) values (
	'$prospect_id', '$property_id', '$user_id', \"$fixed_date\", '$event', \"$contact\", \"$regarding\", 
	'" . $SESSION_USER_ID . "', '$priority', '$rollover')";
	executeupdate($sql);


$test=1;
if($SESSION_MASTER_ID==1){
    $sql = "SELECT count(*) from activities where act_result='Objective Met' and property_id='$property_id'";
	$test = getsingleresult($sql);
	$sql = "SELECT always_supercali from users where user_id='$user_id'";
	$test = getsingleresult($sql);
}
if($test != 0){
if($event=="Bid Presentation" || $event == "Inspection" || $event=="Risk Report Presentation" || $event == "FCS Meetings" || $event == "Calendar - Other" || $event == "Project Close-Out" || $event=="Meeting" || $event=="Contact" || $event=="Quickbid" || $event=="To Do"){
  
  $bp_value = go_escape_string($_POST['bp_value']);
  $bp_value = go_reg_replace("\$", "", $bp_value);
  $bp_value = go_reg_replace("\,", "", $bp_value);
  
  if($act_id=="new"){
    $sql = "SELECT act_id from activities where property_id='$property_id' order by 
	act_id desc limit 1";
	$act_id = getsingleresult($sql);
  }
  // re-acquire values (just for sake of seeing them all on this script)
  $sql = "SELECT act_id, user_id, prospect_id, property_id, date, regarding, repeat_type, span_date 
  from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $user_id = stripslashes($record['user_id']);
  $prospect_id = stripslashes($record['prospect_id']);
  $property_id = stripslashes($record['property_id']);
  $date = stripslashes($record['date']);
  $repeat_type = stripslashes($record['repeat_type']);
  $span_date = stripslashes($record['span_date']);
  $regarding = $record['regarding'];
  
  switch($event){
    case "Bid Presentation":{
	  $what = "BP";
	  $category_id = 22;
	  $description = $BP;
	  break;
	}
	case "Inspection":{
	  $what = "I";
	  $category_id = 34;
	  $description = $I;
	  // when an inspection is scheduled, final approval should be zero - 4/6/11 JW
	  $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
      where property_id='$property_id'";
	  executeupdate($sql);
	  break;
	}
	case "Quickbid":{
	  $what = "Q";
	  $category_id = 38;
	  $description = "Quickbid";
	  // when an inspection is scheduled, final approval should be zero - 4/6/11 JW
	  $sql = "UPDATE properties set final_approval=0, final_approval_reason='', pre_approval=0, pre_approval_reason='', ready_for_pre_approval=0, ready_for_email=0, text_alert_sent=0 
      where property_id='$property_id'";
	  executeupdate($sql);
	  break;
	}
	case "Risk Report Presentation":{
	  $what = "RRP";
	  $category_id = 24;
	  $description = $RRP;
	  break;
	}
	case "FCS Meetings":{
	  $what = "UM";
	  $category_id = 25;
	  $description = $UM;
	  break;
	}
	case "Project Close-Out":{
	  $what = "PCO";
	  $category_id = 30;
	  $description = $PCO;
	  break;
	}
	case "Calendar - Other":{
	  $what = "O";
	  $category_id = 26;
	  $description = $regarding;
	  break;
	}
	case "Meeting":{
	  $category_id = 35;
	  $what = "M";
	  $description = $regarding;
	  break;
	}
	case "Contact":{
	  $category_id = 36;
	  $what = "C";
	  $description = $regarding;
	  break;
	}
	case "To Do":{
	  $category_id = 40;
	  $what = "TD";
	  $description = $regarding;
	  break;
	}
	
  }
  
  $sql = "UPDATE activities set bp_value=\"$bp_value\" where act_id='$act_id'";
  executeupdate($sql);
  
  $sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
  executeupdate($sql);
  
  $sauce = md5(time());
  $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
  quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id) 
  values 
  (\"$event - $act_id\", '1', '1', \"$description\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
   '".$property_id."', '".$what."','".$bp_value."', '".$user_id."', '$act_id', '" . $SESSION_MASTER_ID . "')";
  executeupdate($sql);
  
  $sql = "SELECT event_id from supercali_events where property_id='$property_id' order by event_id desc limit 1";
  $event_id = getsingleresult($sql);
  
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', '$date', '$date')";
  executeupdate($sql);
  
  if($event=="Inspection"){
    $sql = "SELECT a.email, a.cellphone, a.alert_inspection_scheduled_text, a.alert_inspection_scheduled_email, a.cell_id from users a where a.user_id='$user_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$email = stripslashes($record['email']);
	$cellphone = remove_non_numeric(stripslashes($record['cellphone']));
	$alert_inspection_scheduled_text = stripslashes($record['alert_inspection_scheduled_text']);
	$alert_inspection_scheduled_email = stripslashes($record['alert_inspection_scheduled_email']);
	$cell_id = stripslashes($record['cell_id']);
	
	if($alert_inspection_scheduled_text==1 || $alert_inspection_scheduled_email==1){
	  $sql = "SELECT date_format(date, \"%m/%d/%Y %r\") from activities where act_id='$act_id'";
	  $datepretty = getsingleresult($sql);
	  $sql = "SELECT site_name from properties where property_id='$property_id'";
	  $site_name = stripslashes(getsingleresult($sql));
	  $sql = "SELECT dispatch_from_email from master_list where master_id='" . $SESSION_MASTER_ID . "'";
      $from_email = getsingleresult($sql);
	  $message = "New $event scheduled at $site_name on $datepretty";
	  if($alert_inspection_scheduled_email == 1) email_q($email, "New $event Scheduled", $message, "From:$from_email");
	  if($alert_inspection_scheduled_text ==1){
	    $sql = "SELECT cell_extension from cell_providers where cell_id='$cell_id'";
		$cell_extension = stripslashes(getsingleresult($sql));
	    if($cellphone != "") email_q($cellphone . "@" . $cell_extension, "", $message, "From:$from_email");
	  }
	}
  }
  
  if($event=="Contact"){
    $sql = "SELECT a.email, a.cellphone, a.alert_contact_text, a.alert_contact_email, a.cell_id from users a where a.user_id='$user_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$email = stripslashes($record['email']);
	$cellphone = remove_non_numeric(stripslashes($record['cellphone']));
	$alert_contact_text = stripslashes($record['alert_contact_text']);
	$alert_contact_email = stripslashes($record['alert_contact_email']);
	$cell_id = stripslashes($record['cell_id']);
	
	if($alert_contact_text==1 || $alert_contact_email==1){
	  $sql = "SELECT date_format(date, \"%m/%d/%Y %r\") from activities where act_id='$act_id'";
	  $datepretty = getsingleresult($sql);
	  $sql = "SELECT site_name from properties where property_id='$property_id'";
	  $site_name = stripslashes(getsingleresult($sql));
	  $sql = "SELECT dispatch_from_email from master_list where master_id='" . $SESSION_MASTER_ID . "'";
      $from_email = getsingleresult($sql);
	  $message = "New $event scheduled for $site_name on $datepretty";
	  if($alert_contact_email == 1) email_q($email, "New $event Scheduled", $message, "From:$from_email");
	  if($alert_contact_text ==1){
	    $sql = "SELECT cell_extension from cell_providers where cell_id='$cell_id'";
		$cell_extension = stripslashes(getsingleresult($sql));
	    if($cellphone != "") email_q($cellphone . "@" . $cell_extension, "", $message, "From:$from_email");
	  }
	}
  }
  
  if($event=="Meeting"){
    $sql = "SELECT a.email, a.cellphone, a.alert_meeting_text, a.alert_meeting_email, a.cell_id from users a where a.user_id='$user_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$email = stripslashes($record['email']);
	$cellphone = remove_non_numeric(stripslashes($record['cellphone']));
	$alert_meeting_text = stripslashes($record['alert_meeting_text']);
	$alert_meeting_email = stripslashes($record['alert_meeting_email']);
	$cell_id = stripslashes($record['cell_id']);
	
	if($alert_meeting_text==1 || $alert_meeting_email==1){
	  $sql = "SELECT date_format(date, \"%m/%d/%Y %r\") from activities where act_id='$act_id'";
	  $datepretty = getsingleresult($sql);
	  $sql = "SELECT site_name from properties where property_id='$property_id'";
	  $site_name = stripslashes(getsingleresult($sql));
	  $sql = "SELECT dispatch_from_email from master_list where master_id='" . $SESSION_MASTER_ID . "'";
      $from_email = getsingleresult($sql);
	  $message = "New $event scheduled for $site_name on $datepretty";
	  if($alert_meeting_email == 1) email_q($email, "New $event Scheduled", $message, "From:$from_email");
	  if($alert_meeting_text ==1){
	    $sql = "SELECT cell_extension from cell_providers where cell_id='$cell_id'";
		$cell_extension = stripslashes(getsingleresult($sql));
	    if($cellphone != "") email_q($cellphone . "@" . $cell_extension, "", $message, "From:$from_email");
	  }
	}
  }
  
}

} // end big if test if

}
meta_redirect("calendar.php");

?>
