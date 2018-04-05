<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;





$firstname = go_escape_string($_POST['firstname']);
$lastname = go_escape_string($_POST['lastname']);
$company = go_escape_string($_POST['company']);
$email = go_escape_string($_POST['email']);
$password = go_escape_string($_POST['password']);
$office = go_escape_string($_POST['office']);
$extension = go_escape_string($_POST['extension']);
$title = go_escape_string($_POST['title']);
$cellphone = go_escape_string($_POST['cellphone']);
$cell_id = go_escape_string($_POST['cell_id']);
$submit1 = go_escape_string($_POST['submit1']);
$googlemap_zipcode = go_escape_string($_POST['googlemap_zipcode']);
$signature_block = go_escape_string($_POST['signature_block']);

$alert_inspection_approval = go_escape_string($_POST['alert_inspection_approval']);
$alert_inspection_approval_email = go_escape_string($_POST['alert_inspection_approval_email']);
$alert_inspection_approval_text = go_escape_string($_POST['alert_inspection_approval_text']);
  
$alert_dispatch_approval = go_escape_string($_POST['alert_dispatch_approval']);
$alert_dispatch_approval_email = go_escape_string($_POST['alert_dispatch_approval_email']);
$alert_dispatch_approval_text = go_escape_string($_POST['alert_dispatch_approval_text']);

if($alert_inspection_approval != 1) $alert_inspection_approval = 0;
if($alert_inspection_approval_email != 1) $alert_inspection_approval_email = 0;
if($alert_inspection_approval_text != 1) $alert_inspection_approval_text = 0;
if($alert_dispatch_approval != 1) $alert_dispatch_approval = 0;
if($alert_dispatch_approval_email != 1) $alert_dispatch_approval_email = 0;
if($alert_dispatch_approval_text != 1) $alert_dispatch_approval_text = 0;

if($alert_dispatch_approval_text==1 || $alert_dispatch_approval_email==1) $alert_dispatch_approval = 1;
if($alert_inspection_approval_text==1 || $alert_inspection_approval_email==1) $alert_inspection_approval = 1;

$alert_inspection_scheduled_text = go_escape_string($_POST['alert_inspection_scheduled_text']);
if($alert_inspection_scheduled_text != "1") $alert_inspection_scheduled_text = 0;
$alert_inspection_scheduled_email = go_escape_string($_POST['alert_inspection_scheduled_email']);
if($alert_inspection_scheduled_email != "1") $alert_inspection_scheduled_email = 0;

$alert_meeting_text = go_escape_string($_POST['alert_meeting_text']);
if($alert_meeting_text != "1") $alert_meeting_text = 0;
$alert_meeting_email = go_escape_string($_POST['alert_meeting_email']);
if($alert_meeting_email != "1") $alert_meeting_email = 0;

$alert_contact_text = go_escape_string($_POST['alert_contact_text']);
if($alert_contact_text != "1") $alert_contact_text = 0;
$alert_contact_email = go_escape_string($_POST['alert_contact_email']);
if($alert_contact_email != "1") $alert_contact_email = 0;

$customer_portal_contact = go_escape_string($_POST['customer_portal_contact']);
if($customer_portal_contact != 1) $customer_portal_contact = 0;
$gets_sdemail = go_escape_string($_POST['gets_sdemail']);
if($gets_sdemail != "1") $gets_sdemail = 0;
$always_require_approval = go_escape_string($_POST['always_require_approval']);
if($always_require_approval != "1") $always_require_approval = 0;

$prospects_per_page = go_escape_string($_POST['prospects_per_page']);
$sd_results_per_page = go_escape_string($_POST['sd_results_per_page']);

if($submit1 != ""){
  
    $sql = "UPDATE users set firstname=\"$firstname\", lastname=\"$lastname\", email=\"$email\", ";
	if($password != "") $sql .= " password = \"" . md5($password) . "\", ";
	$sql .= " office=\"$office\", extension=\"$extension\", 
	title=\"$title\", cellphone=\"$cellphone\",
	cell_id='$cell_id',
	alert_dispatch_approval='$alert_dispatch_approval', alert_dispatch_approval_email='$alert_dispatch_approval_email', alert_dispatch_approval_text='$alert_dispatch_approval_text',
	alert_inspection_approval='$alert_inspection_approval', alert_inspection_approval_email='$alert_inspection_approval_email', alert_inspection_approval_text='$alert_inspection_approval_text',
	alert_inspection_scheduled_text='$alert_inspection_scheduled_text', alert_inspection_scheduled_email='$alert_inspection_scheduled_email', 
	googlemap_zipcode=\"$googlemap_zipcode\",  
	customer_portal_contact='$customer_portal_contact', gets_sdemail='$gets_sdemail', always_require_approval='$always_require_approval',
	alert_meeting_text='$alert_meeting_text', alert_meeting_email='$alert_meeting_email', alert_contact_text='$alert_contact_text', alert_contact_email='$alert_contact_email',
	prospects_per_page=\"$prospects_per_page\", signature_block=\"$signature_block\", sd_results_per_page=\"$sd_results_per_page\"
	where user_id='$user_id' and master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
  
  
  if (is_uploaded_file($_FILES['photo']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['photo']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("user_edit.php");
    }
  }
  if (is_uploaded_file($_FILES['photo']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['photo']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['photo']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "headshots/", 75);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE users set photo='$filename' where user_id='$user_id'";
	executeupdate($sql);
  }
  
  if (is_uploaded_file($_FILES['signature']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['signature']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("user_edit.php");
    }
  }
  if (is_uploaded_file($_FILES['signature']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['signature']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['signature']['tmp_name'], $UPLOAD . "headshots/". $filename);
	
	$sql = "UPDATE users set signature='$filename' where user_id='$user_id'";
	executeupdate($sql);
  }
  
}


meta_redirect("user_edit.php");
?>
