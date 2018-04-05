<?php
include "includes/functions.php";

$resource_type = go_escape_string($_POST['resource_type']);
$labor_rate = go_escape_string($_POST['labor_rate']);
$labor_rate2 = go_escape_string($_POST['labor_rate2']);
$labor_rate3 = go_escape_string($_POST['labor_rate3']);
$discount = go_escape_string($_POST['discount']);
$con_new = go_escape_string($_POST['con_new']);
$con_reroof = go_escape_string($_POST['con_reroof']);
$con_service = go_escape_string($_POST['con_service']);
$rt_tpo = go_escape_string($_POST['rt_tpo']);
$rt_epdm = go_escape_string($_POST['rt_epdm']);
$rt_bur = go_escape_string($_POST['rt_bur']);
$rt_modified = go_escape_string($_POST['rt_modified']);
$rt_metal = go_escape_string($_POST['rt_metal']);
$l_firestone = go_escape_string($_POST['l_firestone']);
$l_gaf = go_escape_string($_POST['l_gaf']);
$l_carlisle = go_escape_string($_POST['l_carlisle']);
$l_manville = go_escape_string($_POST['l_manville']);
$l_genflex = go_escape_string($_POST['l_genflex']);
$l_duralast = go_escape_string($_POST['l_duralast']);
$l_versico = go_escape_string($_POST['l_versico']);
$insurance = go_escape_string($_POST['insurance']);
$insurance_exp_pretty = go_escape_string($_POST['insurance_exp_pretty']);
$notes = go_escape_string($_POST['notes']);
$withholding = go_escape_string($_POST['withholding']);
$os_snow = go_escape_string($_POST['os_snow']);
$os_solar = go_escape_string($_POST['os_solar']);
$os_green = go_escape_string($_POST['os_green']);

$submit1 = go_escape_string($_POST['submit1']);
$prospect_id = go_escape_string($_POST['prospect_id']);

if($con_new != 1) $con_new = 0;
if($con_reroof != 1) $con_reroof = 0;
if($con_service != 1) $con_service = 0;
if($rt_tpo != 1) $rt_tpo = 0;
if($rt_epdm != 1) $rt_epdm = 0;
if($rt_bur != 1) $rt_bur = 0;
if($rt_modified != 1) $rt_modified = 0;
if($rt_metal != 1) $rt_metal = 0;
if($l_firestone != 1) $l_firestone = 0;
if($l_gaf != 1) $l_gaf = 0;
if($l_carlisle != 1) $l_carlisle = 0;
if($l_manville != 1) $l_manville = 0;
if($l_genflex != 1) $l_genflex = 0;
if($l_duralast != 1) $l_duralast = 0;
if($l_versico != 1) $l_versico = 0;
if($os_snow != 1) $os_snow = 0;
if($os_solar != 1) $os_solar = 0;
if($os_green != 1) $os_green = 0;


$date_parts = explode("/", $insurance_exp_pretty);
$insurance_exp = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  
if($submit1!= ""){
  $sql = "SELECT count(*) from prospects_resources where prospect_id='$prospect_id'";
  $test = getsingleresult($sql);
  if($test==0){
    $sql = "INSERT into prospects_resources(prospect_id) values('$prospect_id')";
	executeupdate($sql);
  }
  $sql = "UPDATE prospects_resources set resource_type=\"$resource_type\", labor_rate=\"$labor_rate\", 
  con_new=\"$con_new\", con_reroof=\"$con_reroof\", con_service=\"$con_service\", 
  rt_tpo=\"$rt_tpo\", rt_epdm=\"$rt_epdm\", rt_bur=\"$rt_bur\", rt_modified=\"$rt_modified\", rt_metal=\"$rt_metal\", 
  l_firestone=\"$l_firestone\", l_gaf='$l_gaf', l_carlisle='$l_carlisle', l_manville='$l_manville', l_genflex='$l_genflex', l_duralast='$l_duralast', l_versico='$l_versico', 
  insurance_exp=\"$insurance_exp\", insurance=\"$insurance\", notes=\"$notes\", labor_rate2=\"$labor_rate2\", labor_rate3=\"$labor_rate3\", 
  withholding=\"$withholding\", os_snow='$os_snow', os_solar='$os_solar', os_green='$os_green'
  where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  if (is_uploaded_file($_FILES['insurance_file']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['insurance_file']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['insurance_file']['tmp_name'], "uploaded_files/insurance/". $filename);
	
	$sql = "UPDATE prospects_resources set insurance_file='$filename' where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
}

if($submit1 =="Add License"){
  $state = go_escape_string($_POST['state']);
  $license = go_escape_string($_POST['license']);
  $sql = "INSERT into prospects_licenses(prospect_id, state, license) values('$prospect_id', \"$state\", \"$license\")";
  executeupdate($sql);
}

meta_redirect("frame_prospect_profile.php?prospect_id=$prospect_id");
?>


