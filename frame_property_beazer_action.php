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
//$beazer_status = go_escape_string($_POST['beazer_status']);
$phencon_client = go_escape_string($_POST['phencon_client']);
$beazer_audit_date_pretty = go_escape_string($_POST['beazer_audit_date_pretty']);
$audit_identifier = go_escape_string($_POST['audit_identifier']);
$beazer_stage = go_escape_string($_POST['beazer_stage']);
$replacement = go_escape_string($_POST['replacement']);
$probability = go_escape_string($_POST['probability']);
$opportunity = go_escape_string($_POST['opportunity']);
$beazer_type = go_escape_string($_POST['beazer_type']);
$opp_rating = go_escape_string($_POST['opp_rating']);
$roof_settlement = go_escape_string($_POST['roof_settlement']);
$ip_settlement = go_escape_string($_POST['ip_settlement']);
$paint = go_escape_string($_POST['paint']);
$overlay = go_escape_string($_POST['overlay']);
$remove_replace = go_escape_string($_POST['remove_replace']);

$old_sales_status = go_escape_string($_POST['old_sales_status']);

$ro_status = go_escape_string($_POST['ro_status']);
$identifier = go_escape_string($_POST['identifier']);
$public_type = go_escape_string($_POST['public_type']);

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

$date_parts = explode("/", $beazer_audit_date_pretty);
$beazer_audit_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($beazer_audit_date == "--") $beazer_audit_date = "0000-00-00";

$submit1 = go_escape_string($_POST['submit1']);

//$_SESSION['property'] = $_POST;

  
if($submit1 != ""){
	$sql = "UPDATE properties_beazer set adjuster=\"$adjuster\", beazer_file=\"$beazer_file\", beazer_id_status=\"$beazer_id_status\", 
	status_change_date=\"$status_change_date\", claim_analysis_stage=\"$claim_analysis_stage\", ca_change_date=\"$ca_change_date\", 
	install_date=\"$install_date\", settlement_number=\"$settlement_number\", record_creator=\"$record_creator\", 
	sales_status=\"$sales_status\", sales_status_change_date = \"$sales_status_change_date\",
	beazer_audit_date=\"$beazer_audit_date\", phencon_client=\"$phencon_client\", audit_identifier=\"$audit_identifier\", 
	beazer_stage=\"$beazer_stage\", replacement=\"$replacement\", probability=\"$probability\", opportunity=\"$opportunity\", 
	opp_rating=\"$opp_rating\", roof_settlement=\"$roof_settlement\", ip_settlement=\"$ip_settlement\", paint=\"$paint\", 
	overlay=\"$overlay\", remove_replace=\"$remove_replace\"
	where property_id='$property_id'";
	executeupdate($sql);
	
	$sql = "UPDATE properties set sales_stage='$sales_status', sales_stage_change_date=\"$sales_status_change_date\", 
	ro_status=\"$ro_status\", identifier=\"$identifier\", public_type=\"$public_type\"
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
	
	if($old_sales_status != $sales_status){
	  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	  $prospect_id = getsingleresult($sql);
	  
	  $sql = "SELECT sales_stage from sales_stage where sales_stage_id='$old_sales_status'";
	  $old_sales_status_name = getsingleresult($sql);
	  $sql = "SELECT sales_stage from sales_stage where sales_stage_id='$sales_status'";
	  $new_sales_status_name = getsingleresult($sql);
	  
	  $sql = "INSERT into notes (user_id, prospect_id, property_id, date, event, note) values ('" . $SESSION_USER_ID . "', 
	  '$prospect_id', '$property_id', now(), 'Note', \"Sales status changed from $old_sales_status_name to $new_sales_status_name\")";
	  executeupdate($sql);
	}
  
}

$_SESSION['property'] = "";
meta_redirect("frame_property_beazer.php?property_id=$property_id");
?>
