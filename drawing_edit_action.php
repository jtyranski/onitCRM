<?php
include "includes/functions.php";


$property_id = $_POST['property_id'];
$section_id = $_POST['section_id'];
$drawing_id = $_POST['drawing_id'];


$note = go_escape_string($_POST['note']);
$name = go_escape_string($_POST['name']);
$submit1 = go_escape_string($_POST['submit1']);


$type = go_escape_string($_POST['type']);
  $bill_to = go_escape_string($_POST['bill_to']);
  $bt_manufacturer = go_escape_string($_POST['bt_manufacturer']);
  $bt_installer = go_escape_string($_POST['bt_installer']);
  $bt_term = go_escape_string($_POST['bt_term']);
  $bt_start = go_escape_string($_POST['bt_start']);
  $bt_contact = go_escape_string($_POST['bt_contact']);
  $bt_phone = go_escape_string($_POST['bt_phone']);
  
if($submit1 != ""){
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
  
  if($drawing_id=="new"){
    $sql = "INSERT into drawings(property_id, prospect_id, section_id, user_id, note, name) values (
	'$property_id', '" . $prospect_id . "', '$section_id', '0', \"$note\", \"$name\")";
	executeupdate($sql);
	$drawing_id = go_insert_id();
  }

    $sql = "UPDATE drawings set section_id='$section_id', note=\"$note\", name=\"$name\", type=\"$type\", 
	bill_to=\"$bill_to\", bt_manufacturer=\"$bt_manufacturer\", bt_installer=\"$bt_installer\", bt_term=\"$bt_term\", 
	bt_start=\"$bt_start\", bt_contact=\"$bt_contact\", bt_phone=\"$bt_phone\"
	where drawing_id='$drawing_id'";
	executeupdate($sql);

  if($type=="Warranty"){
    if($bill_to=="Installer") $company = $bt_installer;
    if($bill_to=="Manufacturer") $company = $bt_manufacturer;
	$sql = "SELECT address, city, state, zip from prospects where master_id='" . $SESSION_MASTER_ID . "' and company_name=\"$company\" and industry=1";
    $res2 = executequery($sql);
    $rec2 = go_fetch_array($res2);
    $address = go_escape_string(stripslashes($rec2['address']));
    $city = go_escape_string(stripslashes($rec2['city']));
    $state = go_escape_string(stripslashes($rec2['state']));
    $zip = go_escape_string(stripslashes($rec2['zip']));
  
    $sql = "UPDATE drawings set bt_address=\"$address\", bt_city=\"$city\", bt_state=\"$state\", bt_zip=\"$zip\" where drawing_id='$drawing_id'";
    executeupdate($sql);
  }
  
  if (is_uploaded_file($_FILES['file']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['file']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['file']['tmp_name'], "uploaded_files/drawings/". $filename);
	
	$sql = "UPDATE drawings set file='$filename' where drawing_id='$drawing_id'";
	executeupdate($sql);
  }

}

$section_type = $_POST['section_type'];
meta_redirect("frame_property_drawings.php?section_type=$section_type&property_id=$property_id");
?>
