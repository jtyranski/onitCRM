<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$property_id = $_POST['property_id'];

// beazer
$adjuster = go_escape_string($_POST['adjuster']);
$beazer_file = go_escape_string($_POST['beazer_file']);
$beazer_id_status = go_escape_string($_POST['beazer_id_status']);
$old_beazer_id_status = go_escape_string($_POST['old_beazer_id_status']);
$status_change_date_pretty = go_escape_string($_POST['status_change_date_pretty']);
$sales_status_change_date_pretty = go_escape_string($_POST['sales_status_change_date_pretty']);
$claim_analysis_stage = go_escape_string($_POST['claim_analysis_stage']);
$ca_change_date_pretty = go_escape_string($_POST['ca_change_date_pretty']);
$install_date_pretty = go_escape_string($_POST['install_date_pretty']);
$settlement_number = go_escape_string($_POST['settlement_number']);
$record_creator = go_escape_string($_POST['record_creator']);
$sales_status = go_escape_string($_POST['sales_status']);
$beazer_status = go_escape_string($_POST['beazer_status']);

$ro_status = go_escape_string($_POST['ro_status']);
$identifier = go_escape_string($_POST['identifier']);

$date_parts = explode("/", $status_change_date_pretty);
$status_change_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($status_change_date == "--") $status_change_date = "0000-00-00";

$date_parts = explode("/", $sales_status_change_date_pretty);
$sales_status_change_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($sales_status_change_date == "--") $sales_status_change_date = "0000-00-00";

$date_parts = explode("/", $ca_change_date_pretty);
$ca_change_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($ca_change_date == "--") $ca_change_date = "0000-00-00";

$date_parts = explode("/", $install_date_pretty);
$install_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($install_date == "--") $install_date = "0000-00-00";

$submit1 = go_escape_string($_POST['submit1']);


  
if($submit1 != ""){
	$sql = "UPDATE properties_beazer set adjuster=\"$adjuster\", beazer_file=\"$beazer_file\", beazer_id_status=\"$beazer_id_status\", 
	status_change_date=\"$status_change_date\", claim_analysis_stage=\"$claim_analysis_stage\", ca_change_date=\"$ca_change_date\", 
	install_date=\"$install_date\", settlement_number=\"$settlement_number\", record_creator=\"$record_creator\", 
	sales_status=\"$sales_status\", sales_status_change_date = \"$sales_status_change_date\" 
	where property_id='$property_id'";
	executeupdate($sql);
	
	$sql = "UPDATE properties set sales_stage='$sales_status', sales_stage_change_date=\"$sales_status_change_date\", 
	ro_status=\"$ro_status\", identifier=\"$identifier\" 
	where property_id='$property_id'";
	executeupdate($sql);
	
	if($old_beazer_id_status != $beazer_id_status){
	  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	  $prospect_id = getsingleresult($sql);
	  
	  $sql = "SELECT status from beazer_id_status where id='$old_beazer_id_status'";
	  $old_status_name = getsingleresult($sql);
	  $sql = "SELECT status from beazer_id_status where id='$beazer_id_status'";
	  $new_status_name = getsingleresult($sql);
	  
	  $sql = "INSERT into notes (user_id, prospect_id, property_id, date, event, note) values ('" . $SESSION_USER_ID . "', 
	  '$prospect_id', '$property_id', now(), 'Note', \"Status changed from $old_status_name to $new_status_name\")";
	  executeupdate($sql);
	}
  
}

meta_redirect("property_details.php?property_id=$property_id");
?>
