<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$property_id = $_POST['property_id'];

// nonpfri
$sales_status = go_escape_string($_POST['sales_status']);
$sales_status_change_date_pretty = go_escape_string($_POST['sales_status_change_date_pretty']);

$old_sales_status = go_escape_string($_POST['old_sales_status']);

$ro_status = go_escape_string($_POST['ro_status']);
$identifier = go_escape_string($_POST['identifier']);
$public_type = go_escape_string($_POST['public_type']);

$date_parts = explode("/", $sales_status_change_date_pretty);
$sales_status_change_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
if($sales_status_change_date == "--") $sales_status_change_date = "0000-00-00";

$submit1 = go_escape_string($_POST['submit1']);

//$_SESSION['property'] = $_POST;

  
if($submit1 != ""){
	$sql = "UPDATE properties_nonpfri set sales_status=\"$sales_status\", sales_status_change_date = \"$sales_status_change_date\" 
	where property_id='$property_id'";
	executeupdate($sql);
	
	$sql = "UPDATE properties set sales_stage='$sales_status', sales_stage_change_date=\"$sales_status_change_date\", 
	ro_status=\"$ro_status\", identifier=\"$identifier\", public_type=\"$public_type\"
	where property_id='$property_id'";
	executeupdate($sql);
	
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
meta_redirect("frame_property_nonpfri.php?property_id=$property_id");
?>
