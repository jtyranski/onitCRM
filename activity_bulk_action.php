<?php
include "includes/functions.php";

 // this is just for fcs login

//$user_id = $_SESSION['user_id'];
$user_id = $_POST['user_id'];
$act_id = $_POST['act_id'];
$act_result = go_escape_string($_POST['act_result']);
$event = go_escape_string($_POST['event']);
$notes = go_escape_string($_POST['notes']);
$priority = go_escape_string($_POST['priority']);
$act_result = go_escape_string($_POST['act_result']);
$act_type = go_escape_string($_POST['act_type']);
$complete = go_escape_string($_POST['complete']);


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

  
if($submit1 != ""){
  for($x=0;$x<sizeof($_SESSION['list_prospect_id']);$x++){
    $prospect_id = $_SESSION['list_prospect_id'][$x];
	$sql = "SELECT property_id from properties where prospect_id='$prospect_id' and corporate=1";
	$property_id = getsingleresult($sql);
	if($property_id==""){
	  $sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
	  $company_name = stripslashes(getsingleresult($sql));
	  $sql = "INSERT into properties(prospect_id, site_name, corporate) values('$prospect_id', \"" . go_escape_string($company_name) . " Corporate\", 1)";
	  executeupdate($sql);
	  $property_id = go_insert_id();
	}
	//$sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
    //executeupdate($sql);
  
    $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by, priority, 
	rollover, regarding_large, tm, tm_user_id, repeat_type, span_date) values (
	'$prospect_id', '$property_id', '$user_id', '$fixed_date', '$event', \"$contact\", \"$regarding\", 
	'" . $SESSION_USER_ID . "', '$priority', '$rollover', \"$regarding_large\", '$tm', '$tm_user_id', 
	\"$repeat_type\", '$span_date')";
	executeupdate($sql);
	
	$act_id = go_insert_id();
	
	switch($event){
      case "Meeting":{
	    $category_id = 35;
	    $what = "M";
	    break;
	  }
	  case "Contact":{
	    $category_id = 36;
	    $what = "C";
	    break;
	  }
    }
	$sql = "INSERT into supercali_events(title, description, category_id, user_id, prospect_id, property_id, what, ro_user_id, act_id, complete, master_id) values(
    \"$event\", \"$regarding\", $category_id, 2, '$prospect_id', '$property_id', '$what', '$user_id', '$act_id', '0', '" . $SESSION_MASTER_ID . "')";
    executeupdate($sql);
  
    $event_id = go_insert_id();
  
    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '$fixed_date', '$fixed_date')";
    executeupdate($sql);

  }
}

meta_redirect("contacts.php");
?>
