<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$prospect_id = go_escape_string($_POST['prospect_id']);

$firstname = ucfirst(go_escape_string($_POST['firstname']));
$lastname = ucfirst(go_escape_string($_POST['lastname']));
$position = ucfirst(go_escape_string($_POST['position']));
$phone = go_escape_string($_POST['phone']);
$mobile = go_escape_string($_POST['mobile']);
$email = go_escape_string($_POST['email']);
$fax = go_escape_string($_POST['fax']);

$submit1 = go_escape_string($_POST['submit1']);

$id = "new";
if($submit1=="Add"){
  if($id=="new"){
    $duplicate = 0;
    if($firstname != "" && $lastname != ""){
	  if($prospect_id != 0){
	    $sql = "SELECT id from contacts where firstname=\"$firstname\" and lastname=\"$lastname\" and prospect_id = '$prospect_id'";
		$test = getsingleresult($sql);
		if($test != "") $duplicate=1;
	  }
	  if($property_id != 0){
	    $sql = "SELECT id from contacts where firstname=\"$firstname\" and lastname=\"$lastname\" and property_id = '$property_id'";
		$test = getsingleresult($sql);
		if($test != "") $duplicate=1;
	  }
	  if($duplicate==1 && $_SESSION['fcs_add_contact']==""){
	    $_SESSION['sess_msg'] = "Contact already exists, would you like to add it anyway?";
		$_SESSION['fcs_add_contact'] = $_POST;
		meta_redirect("add_contact.php?prospect_id=$prospect_id&property_id=$property_id");
	  }
	}
		
		
    $sql = "INSERT into contacts(prospect_id, property_id, master_id) values('$prospect_id', '$property_id', '" . $SESSION_MASTER_ID . "')";
	executeupdate($sql);
	
	$sql = "SELECT id from contacts where prospect_id='$prospect_id' and property_id='$property_id' order by id desc limit 1";
	$id = getsingleresult($sql);
  }
  
  $sql = "UPDATE contacts set firstname=\"$firstname\", lastname=\"$lastname\", position=\"$position\", phone=\"$phone\", 
  mobile=\"$mobile\", fax=\"$fax\", email=\"$email\" where id='$id'";
  executeupdate($sql);
}
$_SESSION['fcs_add_contact'] = "";
$redirect = "view_company.php?prospect_id=$prospect_id";
if($prospect_id==0) $redirect = "view_property.php?property_id=$property_id";
meta_redirect($redirect);
?>
