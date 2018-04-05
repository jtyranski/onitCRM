<?php
include "../includes/functions.php";

$other_user_id = go_escape_string($_POST['other_user_id']);
$otherdate = go_escape_string($_POST['otherdate']);
$hourpretty = go_escape_string($_POST['hourpretty']);
$minutepretty = go_escape_string($_POST['minutepretty']);
$ampm = go_escape_string($_POST['ampm']);
$description = go_escape_string($_POST['description']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){

  if($ampm == "PM" && $hourpretty < 12) $hourpretty += 12;
  $timepretty = $hourpretty . ":" . $minutepretty . ":00";
  $date_parts = explode("/", $otherdate);
  $fixed_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  $fixed_date .= " " . $timepretty;
  
  $sql = "INSERT into activities(event, user_id, date, regarding) values('Other', '$other_user_id', '$fixed_date', \"$description\")";
  executeupdate($sql);
  $act_id = go_insert_id();
  
  $sql = "INSERT into supercali_events(description, category_id, master_id, what, ro_user_id, act_id) values(
  \"$description\", 39, '" . $SESSION_MASTER_ID . "', 'O', '$other_user_id', '$act_id')";
  executeupdate($sql);
  $event_id = go_insert_id();
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '$fixed_date', '$fixed_date')";
  executeupdate($sql);
  
  
}

meta_redirect("index.php");




?>
