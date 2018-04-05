<?php
include "includes/functions.php";

$dispatch_from_email = go_escape_string($_POST['dispatch_from_email']);
$master_name = go_escape_string($_POST['master_name']);
$address = go_escape_string($_POST['address']);
$city = go_escape_string($_POST['city']);
$state = go_escape_string($_POST['state']);
$zip = go_escape_string($_POST['zip']);
$invoice_contact = go_escape_string($_POST['invoice_contact']);
$invoice_contact_number = go_escape_string($_POST['invoice_contact_number']);
$phone = go_escape_string($_POST['phone']);
$fax = go_escape_string($_POST['fax']);
$website = go_escape_string($_POST['website']);
$timezone = go_escape_string($_POST['timezone']);
$emergency_time_frame = go_escape_string($_POST['emergency_time_frame']);
$urgent_time_frame = go_escape_string($_POST['urgent_time_frame']);
$scheduled_time_frame = go_escape_string($_POST['scheduled_time_frame']);
$payment_terms = go_escape_string($_POST['payment_terms']);
$checks_payable_to = go_escape_string($_POST['checks_payable_to']);
$from_email = go_escape_string($_POST['from_email']);

$priority1 = go_escape_string($_POST['priority1']);
$priority2 = go_escape_string($_POST['priority2']);
$priority3 = go_escape_string($_POST['priority3']);

$priority1_rate = go_escape_string($_POST['priority1_rate']);
$priority2_rate = go_escape_string($_POST['priority2_rate']);
$priority3_rate = go_escape_string($_POST['priority3_rate']);

$disable_caps = go_escape_string($_POST['disable_caps']);
if($disable_caps != 1) $disable_caps=0;

$first_sd_alert = go_escape_string($_POST['first_sd_alert']);
$second_sd_alert = go_escape_string($_POST['second_sd_alert']);
$third_sd_alert = go_escape_string($_POST['third_sd_alert']);
$invoice_user = go_escape_string($_POST['invoice_user']);
$idle_time = go_escape_string($_POST['idle_time']);

$custom_sd_field = go_escape_string($_POST['custom_sd_field']);
$custom_sd_field2 = go_escape_string($_POST['custom_sd_field2']);
$license_number = go_escape_string($_POST['license_number']);
$map_service_xml = go_escape_string($_POST['map_service_xml']);
$xml_sd_export = go_escape_string($_POST['xml_sd_export']);
  if($xml_sd_export=="ComputerEase"){
    $custom_sd_field = "PO #";
	$custom_sd_field2 = "Job #";
  }
$company_code = go_escape_string($_POST['company_code']);
$acct_rec_code = go_escape_string($_POST['acct_rec_code']);
$general_ledger_acct = go_escape_string($_POST['general_ledger_acct']);
$sales_tax_acct = go_escape_string($_POST['sales_tax_acct']);

$productionmeeting_user = go_escape_string($_POST['productionmeeting_user']);
if($productionmeeting_user=="") $productionmeeting_user = 0;

$full_appointment = go_escape_string($_POST['full_appointment']);
$objective_met_label = go_escape_string($_POST['objective_met_label']);

$ar_account = go_escape_string($_POST['ar_account']);
$sales_account = go_escape_string($_POST['sales_account']);

$cron_sd = go_escape_string($_POST['cron_sd']);
$cron_sd_type = go_escape_string($_POST['cron_sd_type']);
$cron_sd_email = go_escape_string($_POST['cron_sd_email']);

if($cron_sd !=1) $cron_sd = 0;


switch($xml_sd_export){
    case "none":{ // standard
	  break;
	}
	case "ComputerEase":{
	  $cron_sd_type = "xml";
	  break;
	}
	case "ComputerEase2":{
	  $cron_sd_type = "xml";
	  break;
	}
	case "Excel 2":{
	  $cron_sd_type = "csv";
	  break;
	}
	case "Timberline":{
	  $cron_sd_type = "txt";
	  break;
	}
}

$submit1 = $_POST['submit1'];

if($submit1=="Update"){
  $sql = "UPDATE master_list set dispatch_from_email=\"$dispatch_from_email\", address=\"$address\", city=\"$city\", state=\"$state\", 
  zip=\"$zip\", invoice_user=\"$invoice_user\",
  phone=\"$phone\", fax=\"$fax\", timezone='$timezone', emergency_time_frame=\"$emergency_time_frame\", urgent_time_frame=\"$urgent_time_frame\", 
  payment_terms=\"$payment_terms\", priority1=\"$priority1\", priority2=\"$priority2\", priority3=\"$priority3\", disable_caps='$disable_caps', 
  first_sd_alert=\"$first_sd_alert\", second_sd_alert=\"$second_sd_alert\", third_sd_alert=\"$third_sd_alert\", scheduled_time_frame=\"$scheduled_time_frame\", 
  idle_time=\"$idle_time\", priority1_rate=\"$priority1_rate\", priority2_rate=\"$priority2_rate\", priority3_rate=\"$priority3_rate\", custom_sd_field=\"$custom_sd_field\", 
  license_number=\"$license_number\", map_service_xml=\"$map_service_xml\", website=\"$website\", custom_sd_field2=\"$custom_sd_field2\", xml_sd_export=\"$xml_sd_export\",
  productionmeeting_user=\"$productionmeeting_user\", checks_payable_to=\"$checks_payable_to\", full_appointment=\"$full_appointment\", from_email=\"$from_email\", 
  ar_account=\"$ar_account\", sales_account=\"$sales_account\", master_name=\"$master_name\", objective_met_label=\"$objective_met_label\",
  cron_sd='$cron_sd', cron_sd_type=\"$cron_sd_type\", company_code=\"$company_code\", acct_rec_code=\"$acct_rec_code\", general_ledger_acct=\"$general_ledger_acct\", 
  sales_tax_acct=\"$sales_tax_acct\", cron_sd_email=\"$cron_sd_email\"
  where master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
  
  $sql = "UPDATE prospects set company_name=\"$master_name\" where master_id=1 and created_master_id = '" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
  
  if($cron_sd){
    $sql = "SELECT cron_sd_filename from master_list where master_id='" . $SESSION_MASTER_ID . "'";
	$cron_sd_filename = getsingleresult($sql);
	if($cron_sd_filename==""){
	  $cron_sd_filename = secretCode();
	  $sql = "UPDATE master_list set cron_sd_filename=\"$cron_sd_filename\" where master_id='" . $SESSION_MASTER_ID . "'";
	  executeupdate($sql);
	}
  }
  
  if (is_uploaded_file($_FILES['logo']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['logo']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("admin_info.php");
    }
  }
  if (is_uploaded_file($_FILES['logo']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['logo']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['logo']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE master_list set logo='$filename' where master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
  }
  
  if (is_uploaded_file($_FILES['logo2']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['logo2']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("admin_info");
    }
  }
  if (is_uploaded_file($_FILES['logo2']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['logo2']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['logo2']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE master_list set logo2='$filename' where master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
  }
  
  
  if (is_uploaded_file($_FILES['logo_report']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['logo_report']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("admin_info");
    }
  }
  if (is_uploaded_file($_FILES['logo_report']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['logo_report']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['logo_report']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE master_list set logo_report='$filename' where master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
  }
  
  
  if (is_uploaded_file($_FILES['logo_map']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['logo_map']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("admin_info");
    }
  }
  if (is_uploaded_file($_FILES['logo_map']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['logo_map']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['logo_map']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 100);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE master_list set logo_map='$filename' where master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
  }
  
}

meta_redirect("admin_index.php");
?>