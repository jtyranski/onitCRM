<?php
include "includes/functions.php";

//$user_id = $_SESSION['user_id'];
$user_id = $_POST['user_id'];
$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];
if($property_id == "") $property_id = 0;
$act_id = $_POST['act_id'];
$act_result = go_escape_string($_POST['act_result']);
$event = go_escape_string($_POST['event']);
$notes = go_escape_string($_POST['notes']);
$priority = go_escape_string($_POST['priority']);
$act_result = go_escape_string($_POST['act_result']);
$act_type = go_escape_string($_POST['act_type']);
$complete = go_escape_string($_POST['complete']);
$dis_id = go_escape_string($_POST['dis_id']);
$needs_approval = go_escape_string($_POST['needs_approval']);
if($needs_approval != 1) $needs_approval=0;
if($event != "Inspection") $needs_approval=0; // only for inspection acts
$needs_approval=0;

$sql = "SELECT corporate from properties where property_id='$property_id'";
$corporate = getsingleresult($sql);
if($corporate==1){
  if($event=="Inspection"){
    $_SESSION['sess_msg'] = "You cannot create an event type of $event with the selected property";
	meta_redirect("activity_edit_info.php?prospect_id=$prospect_id&property_id=$property_id");
  }
}


$repeat_type = go_escape_string($_POST['repeat_type']);
if($repeat_type=="") $repeat_type = "Never";
$spanpretty = go_escape_string($_POST['spanpretty']);
if($spanpretty != ""){
  $date_parts = explode("/", $spanpretty);
  $span_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
}
else {
  $span_date = "";
}

if($event != "Conference Call"){
  $datepretty = go_escape_string($_POST['datepretty']);
  $hourpretty = go_escape_string($_POST['hourpretty']);
  $minutepretty = go_escape_string($_POST['minutepretty']);
  $ampm = go_escape_string($_POST['ampm']);
  if($ampm == "PM" && $hourpretty < 12) $hourpretty += 12;
  $timepretty = $hourpretty . ":" . $minutepretty . ":00";
  $rollover = $_POST['rollover'];
  if($rollover != 1) $rollover = 0;
  
  $contact = go_escape_string($_POST['artistName']);
  $regarding = go_escape_string($_POST['regarding']);
  $regarding_large = go_escape_string($_POST['regarding_large']);
}
else {
  $cc_date = go_escape_string($_POST['cc_date']);
  $cc_hour = go_escape_string($_POST['cc_hour']);
  $cc_minute = go_escape_string($_POST['cc_minute']);
  $cc_zone = go_escape_string($_POST['cc_zone']);
  $cc_ampm = go_escape_string($_POST['cc_ampm']);
  $users = $_POST['users'];
  $guests = $_POST['guests'];
  $guestname = $_POST['guestname'];
  $email = $_POST['email'];
  $totalguests = go_escape_string($_POST['totalguests']);
  $phone_number = go_escape_string($_POST['phone_number']);
  $phone_code = go_escape_string($_POST['phone_code']);
  
  $keep = go_escape_string($_POST['keep']);
  $keep_guests = go_escape_string($_POST['keep_guests']);
  $keep_guests_email = go_escape_string($_POST['keep_guests_email']);
  $keep_users = go_escape_string($_POST['keep_users']);
  $keep_users_email = go_escape_string($_POST['keep_users_email']);
  if($keep != 1) $keep=0;
  
  $sendemail = go_escape_string($_POST['sendemail']);
  if($sendemail != 1) $sendemail=0;
  $claim_type = go_escape_string($_POST['claim_type']);
}
$date_parts = explode("/", $datepretty);
$fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];

  if($fixed_date < date("Y-m-d") && $act_id=="new"){
    echo "Error: You can't add activities with dates before " . date("m/d/Y") . "<br>";
	echo "<a href='javascript:history.go(-1)'>Back</a>";
	exit;
  }

$fixed_date .= " " . $timepretty;

$date_parts = explode("/", $cc_date);
$cc_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($cc_ampm=="PM" && $cc_hour < 12) $cc_hour += 12;
$cc_date_full = $cc_date . " " . $cc_hour . ":" . $cc_minute . ":00";

$cst_hour = $cc_hour;
if($cc_zone=="EST") $cst_hour--;
if($cc_zone=="MST") $cst_hour++;
if($cc_zone=="PST") $cst_hour +=2;
$cst_date_full = $cc_date . " " . $cst_hour . ":" . $cc_minute . ":00";
$submit1 = go_escape_string($_POST['submit1']);

$create_demo = go_escape_string($_POST['create_demo']);
if($create_demo != 1) $create_demo = 0;

//$_SESSION['act'] = $_POST;

  
if($submit1 != ""){
  if($SESSION_MASTER_ID != 1){
    $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
    executeupdate($sql);
  }
  
  if($act_id=="new"){
    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by, priority, 
	rollover, regarding_large, tm, tm_user_id, repeat_type, span_date, needs_approval, dis_id) values (
	'$prospect_id', '$property_id', '$user_id', '$fixed_date', '$event', \"$contact\", \"$regarding\", 
	'" . $SESSION_USER_ID . "', '$priority', '$rollover', \"$regarding_large\", '$tm', '$tm_user_id', 
	\"$repeat_type\", '$span_date', '$needs_approval', '$dis_id')";
	//echo $sql;
	//exit;
	
	executeupdate($sql);
	$sql = "SELECT act_id from activities where property_id='$property_id' order by 
	act_id desc limit 1";
	$act_id = getsingleresult($sql);
	

  }
  else {
    $sql = "UPDATE activities set prospect_id='$prospect_id', property_id='$property_id', date='$fixed_date', needs_approval='$needs_approval', 
	event='$event', contact=\"$contact\", regarding=\"$regarding\", user_id='$user_id', priority='$priority', rollover='$rollover', 
	got_rolledover=0, regarding_large=\"$regarding_large\", act_result=\"$act_result\", tm='$tm', tm_user_id='$tm_user_id', dis_id='$dis_id', 
	repeat_type=\"$repeat_type\", span_date='$span_date'";
	if($complete) $sql .= ", act_type=\"$act_type\"";
	$sql .= " where act_id='$act_id'";
	executeupdate($sql);
	
	
	// in case they scheduled as supercali event then changed it to non supercali
	$sql = "DELETE from supercali_events where act_id='$act_id' and act_id != 0";
    executeupdate($sql);
//echo $sql;
	//exit;
  }
  
  if (is_uploaded_file($_FILES['attachment']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], $UPLOAD . "activities/". $filename);
	
	$sql = "UPDATE activities set attachment='$filename' where act_id='$act_id'";
	executeupdate($sql);
  }
  
  if($needs_approval==0 && $event=="Inpsection"){
    $sql = "UPDATE properties set pre_approval=1 and final_approval=1 where property_id='$property_id'";
	executeupdate($sql);
  }
  

  include "activity_action_create_demo.php";
  //include "activity_action_si.php";
  //include "activity_action_bid.php";
  //include "activity_action_email.php";
  //include "activity_action_opm.php";
  include "activity_action_supercali.php";
  //include "activity_action_sendletter.php";
  //include "activity_action_operations.php";
  //include "activity_action_productionmeeting.php";
  
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
	  $sql = "SELECT scheduled_by from activities where act_id='$act_id'";
	  $scheduled_by = getsingleresult($sql);
	  if($scheduled_by==0 || $scheduled_by=="") $scheduled_by = $SESSION_USER_ID;
	  $sql = "SELECT email from users where user_id='$scheduled_by'";
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
	  $sql = "SELECT scheduled_by from activities where act_id='$act_id'";
	  $scheduled_by = getsingleresult($sql);
	  if($scheduled_by==0 || $scheduled_by=="") $scheduled_by = $SESSION_USER_ID;
	  $sql = "SELECT email from users where user_id='$scheduled_by'";
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

  if($redirect=="") $redirect = $_POST['redirect'];
  if($redirect != "") meta_redirect($redirect, 0, 1);
  
  if($prospect_id != 0){
    $sql = "SELECT corporate from properties where property_id='$property_id'";
	$test = getsingleresult($sql);
	if($test){
      meta_redirect("view_prospect.php?prospect_id=$prospect_id&view=activities", 0, 1);
	}
	else {
	  if($property_id == "0"){
	    meta_redirect("view_prospect.php?prospect_id=$prospect_id&view=activities", 0, 1);
	  }
	  else {
	    meta_redirect("view_property.php?property_id=$property_id&view=activities", 0, 1);
	  }
	}
  }
  else {
    meta_redirect("index.php", 0, 1);
  }

?>
