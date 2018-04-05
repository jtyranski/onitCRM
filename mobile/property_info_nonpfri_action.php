<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$property_id = $_POST['property_id'];

// nonpfri
$sales_status = go_escape_string($_POST['sales_status']);
$sales_status_change_date_pretty = go_escape_string($_POST['sales_status_change_date_pretty']);

$ro_status = go_escape_string($_POST['ro_status']);
$identifier = go_escape_string($_POST['identifier']);

$date_parts = explode("/", $sales_status_change_date_pretty);
$sales_status_change_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($sales_status_change_date == "--") $sales_status_change_date = "0000-00-00";

$submit1 = go_escape_string($_POST['submit1']);

  
if($submit1 != ""){
	$sql = "UPDATE properties_nonpfri set sales_status=\"$sales_status\", sales_status_change_date = \"$sales_status_change_date\" 
	where property_id='$property_id'";
	executeupdate($sql);
	
	$sql = "UPDATE properties set sales_stage='$sales_status', sales_stage_change_date=\"$sales_status_change_date\", 
	ro_status=\"$ro_status\", identifier=\"$identifier\"  
	where property_id='$property_id'";
	executeupdate($sql);
  
}

meta_redirect("property_details.php?property_id=$property_id");
?>
