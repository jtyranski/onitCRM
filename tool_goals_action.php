<?php
include "includes/functions.php";

$goal_fcs_users = go_escape_string($_POST['goal_fcs_users']);
$goal_companies = go_escape_string($_POST['goal_companies']);
$goal_properties = go_escape_string($_POST['goal_properties']);
$goal_contacts = go_escape_string($_POST['goal_contacts']);
$goal_meetings = go_escape_string($_POST['goal_meetings']);
$goal_inspections = go_escape_string($_POST['goal_inspections']);
$goal_dispatches = go_escape_string($_POST['goal_dispatches']);
$goal_quoted = go_escape_string($_POST['goal_quoted']);
$goal_sold = go_escape_string($_POST['goal_sold']);
$goal_quickbid = go_escape_string($_POST['goal_quickbid']);

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "UPDATE master_list set goal_fcs_users=\"$goal_fcs_users\", goal_companies=\"$goal_companies\", 
  goal_properties=\"$goal_properties\", goal_contacts=\"$goal_contacts\", goal_meetings=\"$goal_meetings\", goal_inspections=\"$goal_inspections\", 
  goal_dispatches=\"$goal_dispatches\", goal_quoted=\"$goal_quoted\", goal_sold=\"$goal_sold\", goal_quickbid=\"$goal_quickbid\" where master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
}

meta_redirect("tool_goals.php");
?>