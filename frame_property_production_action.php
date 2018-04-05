<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$opm_id = go_escape_string($_POST['opm_id']);
$user_id = go_escape_string($_POST['user_id']);

$pm_start_pretty = go_escape_string($_POST['pm_start_pretty']);
$pm_finish_pretty = go_escape_string($_POST['pm_finish_pretty']);
$ip_start_pretty = go_escape_string($_POST['ip_start_pretty']);
$ip_finish_pretty = go_escape_string($_POST['ip_finish_pretty']);
$fi_start_pretty = go_escape_string($_POST['fi_start_pretty']);
$fi_finish_pretty = go_escape_string($_POST['fi_finish_pretty']);
$ip_email_start_pretty = go_escape_string($_POST['ip_email_start_pretty']);

$project_sqft = go_escape_string($_POST['project_sqft']);

$submit1 = go_escape_string($_POST['submit1']);

$sql = "SELECT project_id from opm where opm_id='$opm_id'";
$project_id = stripslashes(getsingleresult($sql));

$sql = "SELECT prospect_id, site_name from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_id = $record['prospect_id'];
$site_name = go_escape_string(stripslashes($record['site_name']));

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = go_escape_string(stripslashes(getsingleresult($sql)));

$title = $company_name . ":" . $site_name;

if($submit1 != ""){
  $project_sqft = go_reg_replace(",", "", $project_sqft);
  
  $sql = "UPDATE opm set user_id='$user_id', project_sqft=\"$project_sqft\" where opm_id='$opm_id'";
  executeupdate($sql);
  
  // production meeting
  /*
  if($pm_start_pretty != ""){
    $date_parts = explode("/", $pm_start_pretty);
    $pm_start = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	$date_parts = explode("/", $pm_finish_pretty);
    $pm_finish = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	
	$update_pm = 1;
	if($pm_finish < $pm_start){
	  $_SESSION['sess_msg'] = "You have a start date that is after your finish date.  Please correct this error.";
	  $_SESSION['sess_error_pm_start_pretty'] = $pm_start_pretty;
	  $_SESSION['sess_error_pm_finish_pretty'] = $pm_finish_pretty;
	  $_SESSION['sess_error_pm'] = 1;
	  $update_pm = 0;
	}
	
	if($update_pm==1){
	  $_SESSION['sess_error_pm_start_pretty'] = "";
	  $_SESSION['sess_error_pm_finish_pretty'] = "";
	  $_SESSION['sess_error_pm'] = "";
	  $sql = "UPDATE opm set pm_start='$pm_start', pm_finish='$pm_finish' where opm_id='$opm_id'";
	  executeupdate($sql);
	  
	  $category_id=41;
	  $what = "PM";
	  $sql = "SELECT event_id from supercali_events where opm_id='$opm_id' and category_id='$category_id'";
	  $event_id = getsingleresult($sql);
	  if($event_id==""){
	    $sauce = md5(time());
        $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
        quick_approve, prospect_id, property_id, what, value, opm_id, master_id) 
        values 
        (\"$title - $project_id\", '1', '1', \"Production Meeting\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
        '".$property_id."', '".$what."','".$bp_value."', '$opm_id', '" . $SESSION_MASTER_ID . "')";
        executeupdate($sql);
  
        $event_id = go_insert_id();
	  }
	  $sql = "UPDATE supercali_events set ro_user_id='$user_id' where event_id='$event_id'";
	  executeupdate($sql);
	
	  $sql = "DELETE from supercali_dates where event_id='$event_id'";
	  executeupdate($sql);
	  $span = GetDays($pm_start, $pm_finish, "+1 day");
	  for($x=0;$x<sizeof($span);$x++){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
	    executeupdate($sql);
	  }
	  
	}
  }
  */
  
  // in production
  if($ip_start_pretty != ""){
    $date_parts = explode("/", $ip_start_pretty);
    $ip_start = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	$date_parts = explode("/", $ip_finish_pretty);
    $ip_finish = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	$date_parts = explode("/", $ip_email_start_pretty);
    $ip_email_start = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	$update_ip = 1;
	if($ip_finish < $ip_start){
	  $_SESSION['sess_msg'] = "You have a start date that is after your finish date.  Please correct this error.";
	  $_SESSION['sess_error_ip_start_pretty'] = $ip_start_pretty;
	  $_SESSION['sess_error_ip_finish_pretty'] = $ip_finish_pretty;
	  $_SESSION['sess_error_ip'] = 1;
	  $update_ip = 0;
	}
	
	if($update_ip==1){
	  $_SESSION['sess_error_ip_start_pretty'] = "";
	  $_SESSION['sess_error_ip_finish_pretty'] = "";
	  $_SESSION['sess_error_ip'] = "";
	  $sql = "UPDATE opm set ip_start='$ip_start', ip_finish='$ip_finish', ip_email_start='$ip_email_start' where opm_id='$opm_id'";
	  executeupdate($sql);
	  
	  $category_id=42;
	  $what = "IP";
	  $sql = "SELECT event_id from supercali_events where opm_id='$opm_id' and category_id='$category_id'";
	  $event_id = getsingleresult($sql);
	  if($event_id==""){
	    $sauce = md5(time());
        $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
        quick_approve, prospect_id, property_id, what, value, opm_id, master_id) 
        values 
        (\"$title - $project_id\", '1', '1', \"In Production\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
        '".$property_id."', '".$what."','".$bp_value."', '$opm_id', '" . $SESSION_MASTER_ID . "')";
        executeupdate($sql);
  
        $event_id = go_insert_id();
	  }
	  $sql = "UPDATE supercali_events set ro_user_id='$user_id' where event_id='$event_id'";
	  executeupdate($sql);
	
	  $sql = "DELETE from supercali_dates where event_id='$event_id'";
	  executeupdate($sql);
	  $span = GetDays($ip_start, $ip_finish, "+1 day");
	  for($x=0;$x<sizeof($span);$x++){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
	    executeupdate($sql);
	  }
	  
	}
  }
  else {
    $sql = "UPDATE opm set ip_start='', ip_finish='' where opm_id='$opm_id'";
	executeupdate($sql);
  }
  
  
  // final inspection
  /*
  if($fi_start_pretty != ""){
    $date_parts = explode("/", $fi_start_pretty);
    $fi_start = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	$date_parts = explode("/", $fi_finish_pretty);
    $fi_finish = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
	
	$update_fi = 1;
	if($fi_finish < $fi_start){
	  $_SESSION['sess_msg'] = "You have a start date that is after your finish date.  Please correct this error.";
	  $_SESSION['sess_error_fi_start_pretty'] = $fi_start_pretty;
	  $_SESSION['sess_error_fi_finish_pretty'] = $fi_finish_pretty;
	  $_SESSION['sess_error_fi'] = 1;
	  $update_fi = 0;
	}
	
	if($update_fi==1){
	  $_SESSION['sess_error_fi_start_pretty'] = "";
	  $_SESSION['sess_error_fi_finish_pretty'] = "";
	  $_SESSION['sess_error_fi'] = "";
	  $sql = "UPDATE opm set fi_start='$fi_start', fi_finish='$fi_finish' where opm_id='$opm_id'";
	  executeupdate($sql);
	  $category_id=43;
	  $what = "FI";
	  $sql = "SELECT event_id from supercali_events where opm_id='$opm_id' and category_id='$category_id'";
	  $event_id = getsingleresult($sql);
	  if($event_id==""){
	    $sauce = md5(time());
        $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
        quick_approve, prospect_id, property_id, what, value, opm_id, master_id) 
        values 
        (\"$title - $project_id\", '1', '1', \"Final Inspection\", '$category_id', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
        '".$property_id."', '".$what."','".$bp_value."', '$opm_id', '" . $SESSION_MASTER_ID . "')";
        executeupdate($sql);
  
        $event_id = go_insert_id();
	  }
	  $sql = "UPDATE supercali_events set ro_user_id='$user_id' where event_id='$event_id'";
	  executeupdate($sql);
	
	  $sql = "DELETE from supercali_dates where event_id='$event_id'";
	  executeupdate($sql);
	  $span = GetDays($fi_start, $fi_finish, "+1 day");
	  for($x=0;$x<sizeof($span);$x++){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
	    executeupdate($sql);
	  }
	  
	}
  }
  */
  
  //if($update_pm==1 || $update_ip==1 || $update_fi==1){
  if($update_ip==1 && $user_id != 0){
    $sql = "UPDATE opm set in_q=0 where opm_id='$opm_id'";
	executeupdate($sql);
  }
  else {
    $sql = "SELECT event_id from supercali_events where opm_id='$opm_id'";
	$event_id = getsingleresult($sql);
	if($event_id){
	  $sql = "DELETE from supercali_events where event_id='$event_id'";
	  executeupdate($sql);
	  $sql = "DELETE from supercali_dates where event_id='$event_id'";
	  executeupdate($sql);
	}
	$sql = "UPDATE opm set in_q=1 where opm_id='$opm_id'";
	executeupdate($sql);
  }

}
meta_redirect("frame_property_production.php?property_id=$property_id&opm_id=$opm_id");  
  


?>
