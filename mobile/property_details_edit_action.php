<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$property_id = $_POST['property_id'];
$prospect_id = $_POST['prospect_id'];

$site_name = go_escape_string($_POST['site_name']);
$address = go_escape_string($_POST['address']);
$city = go_escape_string($_POST['city']);
$state = go_escape_string($_POST['state']);
$zip = go_escape_string($_POST['zip']);


$roof_size = go_escape_string($_POST['roof_size']);
$roof_type = go_escape_string($_POST['roof_type']);
$deck_type = go_escape_string($_POST['deck_type']);
$installation_type = go_escape_string($_POST['installation_type']);
$building_use = go_escape_string($_POST['building_use']);
$region = go_escape_string($_POST['region']);

$submit1 = go_escape_string($_POST['submit1']);

  
if($submit1 != ""){

    $sql = "UPDATE properties set site_name=\"$site_name\", address=\"$address\", city=\"$city\", state='$state', 
	zip='$zip', 
	roof_size=\"$roof_size\", roof_type=\"$roof_type\", deck_type=\"$deck_type\", 
	installation_type=\"$installation_type\", building_use=\"$building_use\", 
	region=\"$region\"
	where property_id='$property_id'";
	
	executeupdate($sql);

}

meta_redirect("property_details.php?property_id=$property_id");
?>
