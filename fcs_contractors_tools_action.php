<?php

include "includes/functions.php";


$master_id = go_escape_string($_POST['master_id']);
$tool_id = $_POST['tool_id'];
$activity_id = $_POST['activity_id'];
$submit1 = $_POST['submit1'];
$opportunities = $_POST['opportunities'];
if($opportunities != 1) $opportunities=0;
$multilogo = $_POST['multilogo'];
if($multilogo != 1) $multilogo=0;

$quickbid = $_POST['quickbid'];
if($quickbid != 1) $quickbid=0;

$demo = $_POST['demo'];
if($demo != 1) $demo=0;

$use_resources = $_POST['use_resources'];
if($use_resources != 1) $use_resources=0;

$use_ops = $_POST['use_ops'];
if($use_ops != 1) $use_ops=0;

$residential = $_POST['residential'];
if($residential != 1) $residential=0;

$use_groups = $_POST['use_groups'];
if($use_groups != 1) $use_groups=0;

$use_cron_sd_export = $_POST['use_cron_sd_export'];
if($use_cron_sd_export != 1) $use_cron_sd_export=0;

$invoice_type = $_POST['invoice_type'];
$discipline = $_POST['discipline'];

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

if($submit1 =="Update"){

  $sql = "UPDATE master_list set opportunities='$opportunities', multilogo='$multilogo', quickbid='$quickbid', demo='$demo', 
  use_resources='$use_resources', invoice_type='$invoice_type', goal_fcs_users=\"$goal_fcs_users\", goal_companies=\"$goal_companies\", 
  goal_properties=\"$goal_properties\", goal_contacts=\"$goal_contacts\", goal_meetings=\"$goal_meetings\", goal_inspections=\"$goal_inspections\", 
  goal_dispatches=\"$goal_dispatches\", goal_quoted=\"$goal_quoted\", goal_sold=\"$goal_sold\", goal_quickbid=\"$goal_quickbid\", use_ops='$use_ops', 
  residential='$residential', use_groups='$use_groups', use_cron_sd_export='$use_cron_sd_export' where master_id='$master_id'";
  executeupdate($sql);
  
  $sql = "SELECT * from toolbox_master";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $tool_master_id = $record['tool_master_id'];
	if(in_array($tool_master_id, $tool_id)){
	  $sql = "SELECT id from toolbox_items where master_id='$master_id' and tool_master_id='$tool_master_id'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into toolbox_items(master_id, tool_master_id, cat_id, name, url, on_icon, off_icon) values(
		'$master_id', '$tool_master_id', '" . $record['cat_id'] . "', \"" . $record['name'] . "\", 
		\"" . $record['url'] . "\", \"" . $record['on_icon'] . "\", \"" . $record['off_icon'] . "\")";
		executeupdate($sql);
	  }
	}
	else{
	  $sql = "SELECT id from toolbox_items where master_id='$master_id' and tool_master_id='$tool_master_id'";
	  $test = getsingleresult($sql);
	  if($test != ""){
	    $sql = "DELETE from toolbox_items where master_id='$master_id' and tool_master_id='$tool_master_id'";
		executeupdate($sql);
		
		$sql = "SELECT user_id, tools_available, tools_active from users where master_id='$master_id'";
        $res = executequery($sql);
        while($rec = go_fetch_array($res)){
          $user_id = $rec['user_id'];
          $tools_available = $rec['tools_available'];
          $tools_active = $rec['tools_active'];

	      $tools_available = go_reg_replace("," . $test . ",", "", $tools_available);
		  $tools_active = go_reg_replace("," . $test . ",", "", $tools_active);
		  
  
          $sql = "UPDATE users set tools_active='$tools_active', tools_available='$tools_available' where user_id='$user_id'";
          executequery($sql);
        }
	  }
	}
  }
  
  
  $sql = "SELECT * from activities_master";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $activity_master_id = $record['activity_master_id'];
	if(in_array($activity_master_id, $activity_id)){
	  $sql = "SELECT id from activities_items where master_id='$master_id' and activity_master_id='$activity_master_id'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into activities_items(master_id, activity_master_id) values(
		'$master_id', '$activity_master_id')";
		executeupdate($sql);
	  }
	}
	else{
	  $sql = "SELECT id from activities_items where master_id='$master_id' and activity_master_id='$activity_master_id'";
	  $test = getsingleresult($sql);
	  if($test != ""){
	    $sql = "DELETE from activities_items where master_id='$master_id' and activity_master_id='$activity_master_id'";
		executeupdate($sql);
		
	  }
	}
  }
  
  if($quickbid==1){
    // if they have qb enabled, automatically make sure they have activity master id of 3 (quickbid activity) enabled
      $sql = "SELECT id from activities_items where master_id='$master_id' and activity_master_id='3'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into activities_items(master_id, activity_master_id) values(
		'$master_id', '3')";
		executeupdate($sql);
	  }
  }
  else {
      $sql = "SELECT id from activities_items where master_id='$master_id' and activity_master_id='3'";
	  $test = getsingleresult($sql);
	  if($test != ""){
	    $sql = "DELETE from activities_items where master_id='$master_id' and activity_master_id='3'";
		executeupdate($sql);
		
	  }
  }
  
  if(is_array($discipline)){
    $sql = "DELETE from discipline_to_master where master_id='$master_id'";
	executeupdate($sql);
	for($x=0;$x<sizeof($discipline);$x++){
	  $sql = "INSERT into discipline_to_master(dis_id, master_id) values('" . $discipline[$x] . "', '$master_id')";
	  executeupdate($sql);
	}
  }
  
}

meta_redirect("contacts.php");
  ?>