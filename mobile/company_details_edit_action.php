<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;

$prospect_id = $_POST['prospect_id'];


$company_name = go_escape_string($_POST['company_name']);
$address = go_escape_string($_POST['address']);
$city = go_escape_string($_POST['city']);
$state = go_escape_string($_POST['state']);
$zip = go_escape_string($_POST['zip']);


$submit1 = go_escape_string($_POST['submit1']);

  
if($submit1 != ""){

    $sql = "UPDATE prospects set company_name=\"$company_name\", address=\"$address\", city=\"$city\", state='$state', 
	zip='$zip'
	where prospect_id='$prospect_id'";
	
	executeupdate($sql);
  
}

meta_redirect("company_details.php?prospect_id=$prospect_id");
?>
