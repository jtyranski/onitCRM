<?php
$sql = "SELECT user_id, prospect_id, property_id
from activities where act_id='$act_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$user_id = stripslashes($record['user_id']);
$prospect_id = stripslashes($record['prospect_id']);
$property_id = stripslashes($record['property_id']);
$test=1;
if($SESSION_MASTER_ID==1){
    $sql = "SELECT count(*) from activities where act_result='Objective Met' and property_id='$property_id'";
	$test = getsingleresult($sql);
	if($test == 0){
	  $sql = "SELECT always_supercali from users where user_id='$user_id'";
	  $test = getsingleresult($sql);
	}
}

if($test != 0){
if($event=="Bid Presentation" || $event == "Inspection" || $event=="Risk Report Presentation" || $event == "FCS Meetings" || $event == "Calendar - Other" || $event == "Project Close-Out" || $event=="Meeting" || $event=="Contact" || $event=="Quickbid" || $event=="To Do" || $event== $RESAPPT || $event=="Drop Performed"){
  
  
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
	case $RESAPPT:{
	  $category_id = 44;
	  $what = "RS";
	  $description = $regarding;
	  break;
	}
	case "Drop Performed":{
	  $category_id = 50;
	  $what = "DP";
	  $description = $regarding;
	  break;
	}
	
  }
  
  
  $sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
  executeupdate($sql);
  
  $sauce = md5(time());
  $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
  quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id, repeat_type) 
  values 
  (\"$event - $act_id\", '1', '1', \"$description\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
   '".$property_id."', '".$what."','".$bp_value."', '".$user_id."', '$act_id', '" . $SESSION_MASTER_ID . "', '$repeat_type')";
  executeupdate($sql);
  
  $event_id = go_insert_id();
  
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$event_id', '$date', '$date')";
  executeupdate($sql);
  
  if($repeat_type != "Never" && $repeat_type != "Span"){
    // in case it's somehow an edit (which I don't think we're editing in this system, but oh well)
	$sql = "DELETE from supercali_dates where event_id='$event_id'";
	executeupdate($sql);
	$sql = "DELETE from activities_repeat where act_id='$act_id'";
	executeupdate($sql);
	
    $sql = "INSERT into activities_repeat(act_id, event_id, start_date, repeat_type) values ('$act_id', '$event_id', '$date', 
	'$repeat_type')";
	executeupdate($sql);
	
	
  }
  
  if($repeat_type == "Span"){
    $span = GetDays($date, $span_date, "+1 day");
	for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
	}
  }
  
  if($repeat_type=="Daily" || $repeat_type=="Weekly" || $repeat_type=="Monthly" || $repeat_type=="Yearly") include "cron_calendar_repeat.php";
  
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
	  $sql = "SELECT scheduled_by from activities where act_id='$act_id'";
	  $scheduled_by = getsingleresult($sql);
	  if($scheduled_by==0 || $scheduled_by=="") $scheduled_by = $SESSION_USER_ID;
	  $sql = "SELECT email from users where user_id='$scheduled_by'";
	  $from_email = getsingleresult($sql);
	  $message = "New Inspection scheduled at $site_name on $datepretty";
	  if($alert_inspection_scheduled_email == 1) email_q($email, "New Inspection Scheduled", $message, "From:$from_email");
	  if($alert_inspection_scheduled_text ==1){
	    $sql = "SELECT cell_extension from cell_providers where cell_id='$cell_id'";
		$cell_extension = stripslashes(getsingleresult($sql));
	    if($cellphone != "") email_q($cellphone . "@" . $cell_extension, "", $message, "From:$from_email");
	  }
	}
  }
	
	
}

} // end my big test if 
?>
  
  