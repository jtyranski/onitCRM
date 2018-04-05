<?php
include "includes/functions.php";

$user_id = $_POST['user_id'];

$prospect_id = $_POST['prospect_id'];
$property_id = $_POST['property_id'];
$act_id = $_POST['act_id'];
$act_result = go_escape_string($_POST['act_result']);
//$act_type = go_escape_string($_POST['act_type']);
$act_type="Inspection";

$act_incentive = go_escape_string($_POST['act_incentive']);
$act_incentive = go_reg_replace(",", "", $act_incentive);
$act_incentive = go_reg_replace("\$", "", $act_incentive);
$no_new_act = $_POST['no_new_act'];
if($no_new_act != 1) $no_new_act = 0;
$tm_rating = go_escape_string($_POST['tm_rating']);

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

$event = go_escape_string($_POST['event']);
$notes = go_escape_string($_POST['notes']);
$priority = go_escape_string($_POST['priority']);

$schedule_next_act = go_escape_string($_POST['schedule_next_act']);
if($schedule_next_act != 1) $schedule_next_act = 0;

$met_id = go_escape_string($_POST['met_id']);
if($met_id=="") $met_id = 0;

$submit1 = go_escape_string($_POST['submit1']);



  
if($submit1 == "Complete Activity"){
    /*
	if($act_type=="Inspection"){
	  $sql = "UPDATE properties set inspection_opp_rank = '" . go_escape_string($_POST['inspection_opp_rank']) . "' where 
	  property_id='$property_id'";
	  executeupdate($sql);
	}
	*/
	
    $sql = "UPDATE activities set complete=1, complete_date=now(), act_result = \"$act_result\", met_id=\"$met_id\"";
	if($act_result == "Objective Met" && $SESSION_MASTER_ID==1) $sql .= ", user_id=\"$user_id\"";
	$sql .= " where act_id='$act_id'";
	executeupdate($sql);
	$sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
	executeupdate($sql);
	$sql = "DELETE from activities_repeat where act_id='$act_id'";
	executeupdate($sql);
	
	if($act_result=="Objective Met"){
	  $sql = "UPDATE prospects set has_act=1 where prospect_id='$prospect_id'";
      executeupdate($sql);
	}
	
	
	$sql = "UPDATE properties set lastaction=now() where property_id='$property_id'";
	executeupdate($sql);
	$sql = "UPDATE prospects set lastaction=now() where prospect_id='$prospect_id'";
	executeupdate($sql);
	if($act_result != "Attempted"){
	  $sql = "UPDATE properties set lastcontact=now() where property_id='$property_id'";
	  executeupdate($sql);
    }

    $sql = "SELECT * from activities where act_id='$act_id'";
	$result = executequery($sql);
	$old = go_fetch_array($result);
	
	if($old['event']=="Conference Call"){
	  $sql = "UPDATE activities set complete=1, complete_date=now(), act_result = \"$act_result\" where cc_act_id='$act_id'";
	  executeupdate($sql);
	}
	
	$sql = "UPDATE supercali_events set complete=1 where act_id='$act_id'";
    executeupdate($sql);
	
	// create a sold opportunity. Product=Risk Management Inspection.  Amount=Incentive
	// insert into closing_stats as quoted and then again as sold
	//echo $old['event'];
	//exit;
	/*
	if($old['event']== "Inspection") { 
	  $code = uniqueTimeStamp();
      $sql = "INSERT into opportunities(prospect_id, property_id, user_id, product, amount, status, description, code, probability, 
	  lastaction, opp_incentive) values (
	  '$prospect_id', '$property_id', '$user_id', \"Risk Management Inspection\", '$act_incentive', 'Sold', \"$notes\", \"$code\", \"100\", 
	  now(), '100')";
	  executeupdate($sql);
	  $sql = "SELECT opp_id from opportunities where user_id = \"$user_id\" order by opp_id desc limit 1";
	  $opp_id = getsingleresult($sql);
	
	  //stats
	  $sql = "INSERT into closing_stats(user_id, opp_id, date, status, amount, incentive) values (
	  '$user_id', '$opp_id', now(), 'Quoted', '$act_incentive', '1')";
	  executeupdate($sql);
	  
	  $sql = "INSERT into closing_stats(user_id, opp_id, status, amount, date, opp_incentive, incentive) values(
	  '$user_id', '$opp_id', 'Sold', '$act_incentive', now(), \"100\", '1')";
	  executeupdate($sql);
	  
	}
	*/
	
	$sql = "INSERT into notes (user_id, property_id, prospect_id, act_id, date, event, contact, regarding, note, result) values(
	'" . $SESSION_USER_ID . "', '" . $old['property_id'] . "', '" . $old['prospect_id'] . "', '$act_id', now(), '" . $old['event'] . "', 
	\"" . go_escape_string($old['contact']) . "\", \"" . go_escape_string($old['regarding']) . "\", \"$notes\", '$act_result')";
	executeupdate($sql);
	
	$sql = "SELECT demo_master_id from activities where act_id='$act_id'";
	$demo_master_id = getsingleresult($sql);
	if($demo_master_id){
	  $sql = "SELECT prospect_id from prospects where master_id='$demo_master_id'";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $x_prospect_id = $record['prospect_id'];
		$sql = "DELETE from properties where prospect_id='$x_prospect_id'";
        executeupdate($sql);
        $sql = "DELETE from prospects where prospect_id='$x_prospect_id'";
        executeupdate($sql);
        $sql = "DELETE from am_leakcheck where prospect_id='$x_prospect_id'";
        executeupdate($sql);
        $sql = "DELETE from activities where prospect_id='$x_prospect_id'";
        executeupdate($sql);
        $sql = "DELETE from supercali_events where prospect_id='$x_prospect_id'";
        executeupdate($sql);
        $sql = "DELETE from opportunities where prospect_id='$x_prospect_id'";
        executeupdate($sql);
		$sql = "DELETE from contacts where prospect_id='$x_prospect_id'";
        executeupdate($sql);
		$sql = "DELETE from am_users where prospect_id='$x_prospect_id'";
        executeupdate($sql);
	  }
	  @unlink("uploaded_files/logos/demo_" . $demo_master_id . ".gif");
	  $sql = "SELECT logo from master_list where master_id='$demo_master_id'";
	  $dellogo = stripslashes(getsingleresult($sql));
	  if($dellogo != "") @unlink("uploaded_files/master_logos/$dellogo");
	  $sql = "DELETE from contacts where master_id='$demo_master_id'";
	  executeupdate($sql);
	  $sql = "DELETE from prospects where master_id='$demo_master_id'";
	  executeupdate($sql);
	  $sql = "UPDATE prospects set created_master_id=0 where prospect_id='$prospect_id'";
	  executeupdate($sql);
	  $sql = "DELETE from def_list_master where master_id='$demo_master_id'";
	  executeupdate($sql);
	  $sql = "DELETE from users where master_id='$demo_master_id'";
	  executeupdate($sql);
	  $sql = "DELETE from master_list where master_id='$demo_master_id'";
	  executeupdate($sql);
	  
	  $sql = "UPDATE activities set demo_master_id=0, regarding='Core Demo' where act_id='$act_id'";
	  executeupdate($sql);
	}
	
	/*
	if($no_new_act == 0){
	  $sql = "INSERT into activities(prospect_id, property_id, user_id, date, event, contact, regarding, scheduled_by, priority, 
	  rollover, regarding_large, repeat_type, span_date) values (
	  '$prospect_id', '$property_id', '$user_id', \"$fixed_date\", '$event', \"$contact\", \"$regarding\", 
	  '" . $SESSION_USER_ID . "', '$priority', '$rollover', \"$regarding_large\", \"$repeat_type\", '$span_date')";
	  executeupdate($sql);
	  $sql = "SELECT act_id from activities where property_id='$property_id' order by 
	  act_id desc limit 1";
	  $act_id = getsingleresult($sql);
	}
	else {
	  $event = "";  // just to make sure it doesn't run any of the if loops regarding $event
	}
	
	if (is_uploaded_file($_FILES['attachment']['tmp_name']))
    {
	
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], "uploaded_files/activities/". $filename);
	
	$sql = "UPDATE activities set attachment='$filename' where act_id='$act_id'";
	executeupdate($sql);
    }
	*/
	

	

/*  Not adding new events from complete page
  include "activity_action_si.php";
  include "activity_action_bid.php";
  include "activity_action_email.php";
  include "activity_action_opm.php";
  include "activity_action_supercali.php";
  include "activity_action_sendletter.php";
  include "activity_action_operations.php";
  include "activity_action_productionmeeting.php";
  */
}// end if submit is pressed



  if($schedule_next_act==1) meta_redirect("activity_edit.php?act_id=new&property_id=$property_id&prospect_id=$prospect_id");

  $redirect = $_POST['redirect'];
  if($redirect != "") meta_redirect($redirect);
  
  $sql = "SELECT industry from prospects where prospect_id='$prospect_id'";
  $industry = getsingleresult($sql);
  if($industry){
    meta_redirect("view_prospect.php?prospect_id=$prospect_id&view=activities");
  }
  
  $sql = "SELECT corporate from properties where property_id='$property_id'";
  $test = getsingleresult($sql);
  if($test){
    meta_redirect("view_prospect.php?prospect_id=$prospect_id&view=activities");
  }
  else {
	meta_redirect("view_property.php?property_id=$property_id&view=activities");
  }


?>
