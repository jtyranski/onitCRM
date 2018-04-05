<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$property_id = $_POST['property_id'];

$comp_name = go_escape_string($_POST['comp_name']);
$comp_address = go_escape_string($_POST['comp_address']);
$comp_city = go_escape_string($_POST['comp_city']);
$comp_state = go_escape_string($_POST['comp_state']);
$comp_zip = go_escape_string($_POST['comp_zip']);
//$comp_company = go_escape_string($_POST['comp_company']);
$comp_amount = go_escape_string($_POST['comp_amount']);
$comp_number = go_escape_string($_POST['comp_number']);
$key_number = go_escape_string($_POST['key_number']);
$claim_status = go_escape_string($_POST['claim_status']);
$pfri_shipped = go_escape_string($_POST['pfri_shipped']);
$corrosion_level = go_escape_string($_POST['corrosion_level']);
$completion_date_pretty = go_escape_string($_POST['completion_date_pretty']);
$jm_guarantee = go_escape_string($_POST['jm_guarantee']);
$pfri_shipped_date_pretty = go_escape_string($_POST['pfri_shipped_date_pretty']);
$eligible = go_escape_string($_POST['eligible']);
$eligibility_comments1 = go_escape_string($_POST['eligibility_comments1']);
$remediated = go_escape_string($_POST['remediated']);
$inspected = go_escape_string($_POST['inspected']);
$inspection_status = go_escape_string($_POST['inspection_status']);
$repairs_required = go_escape_string($_POST['repairs_required']);
$repairs_completed = go_escape_string($_POST['repairs_completed']);
$layers_pfri = go_escape_string($_POST['layers_pfri']);
$eligibility_comments2 = go_escape_string($_POST['eligibility_comments2']);
$inspected_date_pretty = go_escape_string($_POST['inspected_date_pretty']);
$test_cuts = go_escape_string($_POST['test_cuts']);
$remed_comp_pretty = go_escape_string($_POST['remed_comp_pretty']);
//$inspection_number = go_escape_string($_POST['inspection_number']);
$ra_change_date_pretty = go_escape_string($_POST['ra_change_date_pretty']);
$ra_stage = go_escape_string($_POST['ra_stage']);

$ro_status = go_escape_string($_POST['ro_status']);
$identifier = go_escape_string($_POST['identifier']);

$date_parts = explode("/", $completion_date_pretty);
$completion_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($completion_date == "--") $completion_date = "0000-00-00";

$date_parts = explode("/", $pfri_shipped_date_pretty);
$pfri_shipped_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($pfri_shipped_date == "--") $pfri_shipped_date = "0000-00-00";

$date_parts = explode("/", $inspected_date_pretty);
$inspected_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($inspected_date == "--") $inspected_date = "0000-00-00";

$date_parts = explode("/", $remed_comp_pretty);
$remed_comp = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($remed_comp == "--") $remed_comp = "0000-00-00";

$date_parts = explode("/", $ra_change_date_pretty);
$ra_change_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($ra_change_date == "--") $ra_change_date = "0000-00-00";

$submit1 = go_escape_string($_POST['submit1']);

  
if($submit1 != ""){
	$sql = "UPDATE properties_manville set key_number=\"$key_number\", claim_status=\"$claim_status\", 
	eligible=\"$eligible\", eligibility_comments1=\"$eligibility_comments1\", eligibility_comments2=\"$eligibility_comments2\", 
	remediated=\"$remediated\", pfri_shipped=\"$pfri_shipped\", pfri_shipped_date=\"$pfri_shipped_date\", 
	completion_date=\"$completion_date\", jm_guarantee=\"$jm_guarantee\", comp_number=\"$comp_number\", comp_amount=\"$comp_amount\", 
	corrosion_level=\"$corrosion_level\", comp_company=\"$comp_company\", comp_address=\"$comp_address\", comp_city=\"$comp_city\", 
	comp_state=\"$comp_state\", comp_zip=\"$comp_zip\", inspected=\"$inspected\", inspection_status=\"$inspection_status\", 
	inspection_number=\"$inspection_number\", test_cuts=\"$test_cuts\", inspected_date=\"$inspected_date\", 
	repairs_required=\"$repairs_required\", layers_pfri=\"$layers_pfri\", repairs_completed=\"$repairs_completed\", 
	remed_comp=\"$remed_comp\", comp_name=\"$comp_name\", ra_stage=\"$ra_stage\", ra_change_date=\"$ra_change_date\" 
	where property_id='$property_id'";
	executeupdate($sql);
	
	$sql = "UPDATE properties set sales_stage='$ra_stage', sales_stage_change_date=\"$ra_change_date\", 
	ro_status=\"$ro_status\", identifier=\"$identifier\" 
	where property_id='$property_id'";
	executeupdate($sql);
  
}

meta_redirect("property_details.php?property_id=$property_id");
?>
