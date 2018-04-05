<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_POST['prospect_id']);
$property_id = go_escape_string($_POST['property_id']);
$user_id = go_escape_string($_POST['user_id']);
$prospect_type = go_escape_string($_POST['prospect_type']);
$prospect_result = go_escape_string($_POST['prospect_result']);
$prospecting_type = go_escape_string($_POST['prospecting_type']);
$beazer_claim_id = go_escape_string($_POST['beazer_claim_id']);

if(in_array($SESSION_USER_ID, $onlyjim)){
  $field = "beazer_claim_id";
  $field_value = $beazer_claim_id;
}
else {
  $field = "prospecting_type";
  $field_value = $prospecting_type;
}

$datepretty = go_escape_string($_POST['datepretty']);
$hourpretty = go_escape_string($_POST['hourpretty']);
$minutepretty = go_escape_string($_POST['minutepretty']);
$ampm = go_escape_string($_POST['ampm']);

$notes = go_escape_string($_POST['notes']);
$submit1 = go_escape_string($_POST['submit1']);
$no_act = $_POST['no_act'];

if($ampm == "PM" && $hourpretty < 12) $hourpretty += 12;
$timepretty = $hourpretty . ":" . $minutepretty . ":00";
$date_parts = explode("/", $datepretty);
$fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
$fixed_date .= " " . $timepretty;

if($submit1 != ""){
  $sql = "INSERT into stats_prospects(prospect_id, property_id, user_id, prospect_type, prospect_date, notes, prospect_result) values (
  '$prospect_id', '$property_id', '$user_id', '$prospect_type', '$fixed_date', \"$notes\", \"$prospect_result\")";
  executeupdate($sql);
  
  $sql = "UPDATE properties set $field='$field_value' where property_id='$property_id'";
  executeupdate($sql);
  
  $sql = "SELECT corporate from properties where property_id='$property_id'";
  $corporate = getsingleresult($sql);
  if($corporate && $field=="prospecting_type"){
    $sql = "UPDATE prospects set prospecting_type='$prospecting_type' where prospect_id='$prospect_id'";
    executeupdate($sql);
  }
  
  if($notes != ""){
    $sql = "INSERT into notes(prospect_id, property_id, user_id, date, event, note) values('$prospect_id', '$property_id', '$user_id', 
	'$fixed_date', 'Note', \"Prospecting: $notes\")";
	executeupdate($sql);
  }
  
  $delete_todo = 0;
  if($prospect_result=="Prospect") $delete_todo = 1;  // became a prospect
  if($prospect_result=="Connection" && $prospecting_type==2) $delete_todo = 1; //got a hold of them, not a prospect
  if($beazer_claim_id==2 || $beazer_claim_id==3) $delete_todo = 1;
  
  if($delete_todo){
    $sql = "DELETE from prospecting_todo where property_id='$property_id' and user_id='$user_id'";
	executeupdate($sql);
  }
  else {
    $sql = "UPDATE prospecting_todo set result='$prospect_result', last_action=now(), notes=\"$notes\", complete=1, 
	prospect_type='$prospect_type'
	where property_id='$property_id' 
	and user_id='$user_id' and complete=0";
	executeupdate($sql);
	$sql = "INSERT into prospecting_todo(prospect_id, property_id, date_added, last_action, user_id) values(
	'$prospect_id', '$property_id', now(), now(), '$user_id')";
	executeupdate($sql);
  }
  
  //$_SESSION['sess_msg'] = "Prospecting info added. You may now schedule your next activity.";
}

if($no_act) {
  $_SESSION['sess_msg'] = "";
  meta_redirect("company_details.php?prospect_id=$prospect_id");
}

meta_redirect("calendar_add.php?property_id=$property_id");
?>