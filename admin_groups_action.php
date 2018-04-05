<?php
include "includes/functions.php";

$add_group_name = go_escape_string($_POST['add_group_name']);
$edit_group_name = go_escape_string($_POST['edit_group_name']);

$add_subgroup_name = go_escape_string($_POST['add_subgroup_name']);
$edit_subgroup_name = go_escape_string($_POST['edit_subgroup_name']);

$group_id = go_escape_string($_POST['group_id']);
$subgroup_id = go_escape_string($_POST['subgroup_id']);

$group_members = $_POST['group_members'];
if(!(is_array($group_members))) $group_members[] = "";

$subgroup_members = $_POST['subgroup_members'];
if(!(is_array($subgroup_members))) $subgroup_members[] = "";

$submit1 = go_escape_string($_POST['submit1']);




if($submit1=="add_group"){
  $sql = "INSERT into groups(master_id, group_name, sub_of) values('" . $SESSION_MASTER_ID . "', \"$add_group_name\", 0)";
  executeupdate($sql);
  $id = go_insert_id();
  $sql = "SELECT master_name, logo, logo2, logo_report, logo_map, dispatch_from_email, address, city, state, zip, invoice_user, phone, fax, website, 
  emergency_time_frame, urgent_time_frame, scheduled_time_frame, productionmeeting_user, priority1, priority2, priority3, priority1_rate, priority2_rate, priority3_rate,
  custom_sd_field, custom_sd_field2, xml_sd_export, from_email, ar_account, sales_account, checks_payable_to, timezone, 
  company_code, acct_rec_code, general_ledger_acct, sales_tax_acct from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $master_name = go_escape_string(stripslashes($record['master_name'])) . " - " . $add_group_name;
  $logo = go_escape_string(stripslashes($record['logo']));
  $logo2 = go_escape_string(stripslashes($record['logo2']));
  $logo_report = go_escape_string(stripslashes($record['logo_report']));
  $dispatch_from_email = go_escape_string(stripslashes($record['dispatch_from_email']));
  $address = go_escape_string(stripslashes($record['address']));
  $city = go_escape_string(stripslashes($record['city']));
  $state = go_escape_string(stripslashes($record['state']));
  $zip = go_escape_string(stripslashes($record['zip']));
  $invoice_user = go_escape_string(stripslashes($record['invoice_user']));
  $phone = go_escape_string(stripslashes($record['phone']));
  $fax = go_escape_string(stripslashes($record['fax']));
  $website = go_escape_string(stripslashes($record['website']));
  $emergency_time_frame = go_escape_string(stripslashes($record['emergency_time_frame']));
  $urgent_time_frame = go_escape_string(stripslashes($record['urgent_time_frame']));
  $scheduled_time_frame = go_escape_string(stripslashes($record['scheduled_time_frame']));
  $productionmeeting_user = go_escape_string(stripslashes($record['productionmeeting_user']));
  $priority1 = go_escape_string(stripslashes($record['priority1']));
  $priority2 = go_escape_string(stripslashes($record['priority2']));
  $priority3 = go_escape_string(stripslashes($record['priority3']));
  $priority1_rate = go_escape_string(stripslashes($record['priority1_rate']));
  $priority2_rate = go_escape_string(stripslashes($record['priority2_rate']));
  $priority3_rate = go_escape_string(stripslashes($record['priority3_rate']));
  $custom_sd_field = go_escape_string(stripslashes($record['custom_sd_field']));
  $custom_sd_field2 = go_escape_string(stripslashes($record['custom_sd_field2']));
  $xml_sd_export = go_escape_string(stripslashes($record['xml_sd_export']));
  $from_email = go_escape_string(stripslashes($record['from_email']));
  $ar_account = go_escape_string(stripslashes($record['ar_account']));
  $sales_account = go_escape_string(stripslashes($record['sales_account']));
  $logo_map = go_escape_string(stripslashes($record['logo_map']));
  $checks_payable_to = go_escape_string(stripslashes($record['checks_payable_to']));
  $company_code = go_escape_string(stripslashes($record['company_code']));
  $acct_rec_code = go_escape_string(stripslashes($record['acct_rec_code']));
  $general_ledger_acct = go_escape_string(stripslashes($record['general_ledger_acct']));
  $sales_tax_acct = go_escape_string(stripslashes($record['sales_tax_acct']));
  $timezone = go_escape_string(stripslashes($record['timezone']));
  
  $sql = "UPDATE groups set master_name=\"$master_name\",
  logo=\"$logo\",
  logo2=\"$logo2\",
  logo_report=\"$logo_report\",
  dispatch_from_email=\"$dispatch_from_email\",
  address=\"$address\",
  city=\"$city\",
  state=\"$state\",
  zip=\"$zip\",
  invoice_user=\"$invoice_user\",
  phone=\"$phone\",
  fax=\"$fax\",
  website=\"$website\",
  emergency_time_frame=\"$emergency_time_frame\",
  urgent_time_frame=\"$urgent_time_frame\",
  scheduled_time_frame=\"$scheduled_time_frame\",
  productionmeeting_user=\"$productionmeeting_user\",
  priority1=\"$priority1\",
  priority2=\"$priority2\",
  priority3=\"$priority3\",
  priority1_rate=\"$priority1_rate\",
  priority2_rate=\"$priority2_rate\",
  priority3_rate=\"$priority3_rate\",
  custom_sd_field=\"$custom_sd_field\",
  custom_sd_field2=\"$custom_sd_field2\",
  xml_sd_export=\"$xml_sd_export\",
  from_email=\"$from_email\",
  ar_account=\"$ar_account\",
  sales_account=\"$sales_account\",
  logo_map=\"$logo_map\",
  checks_payable_to=\"$checks_payable_to\",
  company_code=\"$company_code\",
  acct_rec_code=\"$acct_rec_code\",
  general_ledger_acct=\"$general_ledger_acct\",
  sales_tax_acct=\"$sales_tax_acct\",
  timezone=\"$timezone\"
  where id = '$id'";
  executeupdate($sql);
  
  
}

if($submit1=="edit_group"){ // try to do the subgroup info, too, so they won't have to click twice
  $sql = "SELECT master_id from groups where id='$group_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  $sql = "UPDATE groups set group_name=\"$edit_group_name\" where id=\"$group_id\"";
  executeupdate($sql);
  
  // set users of group
  $sql = "SELECT user_id, groups from users where master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $user_id = $record['user_id'];
	$groups = $record['groups'];
	if(in_array($user_id, $group_members)){
	  if(!(go_reg("," . $group_id . ",", $groups))) $groups .= "," . $group_id . ",";
	}
	else {
	  $groups = go_reg_replace("," . $group_id . ",", "", $groups);
	}
	$sql = "UPDATE users set groups=\"$groups\" where user_id='$user_id'";
	executeupdate($sql);
  }

  
  // edit subname
  if($subgroup_id != ""){
    $sql = "SELECT master_id from groups where id='$subgroup_id'";
    $test = getsingleresult($sql);
    if($test != $SESSION_MASTER_ID) exit;
	
    $sql = "UPDATE groups set group_name=\"$edit_subgroup_name\" where id=\"$subgroup_id\"";
	executeupdate($sql);
  }
  
  // set users of subgroup
  $sql = "SELECT user_id, subgroups from users where master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $user_id = $record['user_id'];
	$subgroups = $record['subgroups'];
	if(in_array($user_id, $subgroup_members)){
	  if(!(go_reg("," . $subgroup_id . ",", $subgroups))) $subgroups .= "," . $subgroup_id . ",";
	}
	else {
	  $subgroups = go_reg_replace("," . $subgroup_id . ",", "", $subgroups);
	}
	$sql = "UPDATE users set subgroups=\"$subgroups\" where user_id='$user_id'";
	executeupdate($sql);
  }
}

// add subgroup - group id should already be set by clicking on the group div above
if($submit1=="add_subgroup"){
  $sql = "SELECT master_id from groups where id='$group_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  $sql = "INSERT into groups(master_id, group_name, sub_of) values('" . $SESSION_MASTER_ID . "', \"$add_subgroup_name\", '$group_id')";
  executeupdate($sql);
  $id = go_insert_id();
  $sql = "SELECT master_name, logo, logo2, logo_report, logo_map, dispatch_from_email, address, city, state, zip, invoice_user, phone, fax, website, 
  emergency_time_frame, urgent_time_frame, scheduled_time_frame, productionmeeting_user, priority1, priority2, priority3, priority1_rate, priority2_rate, priority3_rate,
  custom_sd_field, custom_sd_field2, xml_sd_export, from_email, ar_account, sales_account, checks_payable_to, timezone, 
  company_code, acct_rec_code, general_ledger_acct, sales_tax_acct from groups where id='" . $group_id . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $master_name = go_escape_string(stripslashes($record['master_name'])) . " - " . $add_subgroup_name;
  $logo = go_escape_string(stripslashes($record['logo']));
  $logo2 = go_escape_string(stripslashes($record['logo2']));
  $logo_report = go_escape_string(stripslashes($record['logo_report']));
  $dispatch_from_email = go_escape_string(stripslashes($record['dispatch_from_email']));
  $address = go_escape_string(stripslashes($record['address']));
  $city = go_escape_string(stripslashes($record['city']));
  $state = go_escape_string(stripslashes($record['state']));
  $zip = go_escape_string(stripslashes($record['zip']));
  $invoice_user = go_escape_string(stripslashes($record['invoice_user']));
  $phone = go_escape_string(stripslashes($record['phone']));
  $fax = go_escape_string(stripslashes($record['fax']));
  $website = go_escape_string(stripslashes($record['website']));
  $emergency_time_frame = go_escape_string(stripslashes($record['emergency_time_frame']));
  $urgent_time_frame = go_escape_string(stripslashes($record['urgent_time_frame']));
  $scheduled_time_frame = go_escape_string(stripslashes($record['scheduled_time_frame']));
  $productionmeeting_user = go_escape_string(stripslashes($record['productionmeeting_user']));
  $priority1 = go_escape_string(stripslashes($record['priority1']));
  $priority2 = go_escape_string(stripslashes($record['priority2']));
  $priority3 = go_escape_string(stripslashes($record['priority3']));
  $priority1_rate = go_escape_string(stripslashes($record['priority1_rate']));
  $priority2_rate = go_escape_string(stripslashes($record['priority2_rate']));
  $priority3_rate = go_escape_string(stripslashes($record['priority3_rate']));
  $custom_sd_field = go_escape_string(stripslashes($record['custom_sd_field']));
  $custom_sd_field2 = go_escape_string(stripslashes($record['custom_sd_field2']));
  $xml_sd_export = go_escape_string(stripslashes($record['xml_sd_export']));
  $from_email = go_escape_string(stripslashes($record['from_email']));
  $ar_account = go_escape_string(stripslashes($record['ar_account']));
  $sales_account = go_escape_string(stripslashes($record['sales_account']));
  $logo_map = go_escape_string(stripslashes($record['logo_map']));
  $checks_payable_to = go_escape_string(stripslashes($record['checks_payable_to']));
  $company_code = go_escape_string(stripslashes($record['company_code']));
  $acct_rec_code = go_escape_string(stripslashes($record['acct_rec_code']));
  $general_ledger_acct = go_escape_string(stripslashes($record['general_ledger_acct']));
  $sales_tax_acct = go_escape_string(stripslashes($record['sales_tax_acct']));
  $timezone = go_escape_string(stripslashes($record['timezone']));
  
  $sql = "UPDATE groups set master_name=\"$master_name\",
  logo=\"$logo\",
  logo2=\"$logo2\",
  logo_report=\"$logo_report\",
  dispatch_from_email=\"$dispatch_from_email\",
  address=\"$address\",
  city=\"$city\",
  state=\"$state\",
  zip=\"$zip\",
  invoice_user=\"$invoice_user\",
  phone=\"$phone\",
  fax=\"$fax\",
  website=\"$website\",
  emergency_time_frame=\"$emergency_time_frame\",
  urgent_time_frame=\"$urgent_time_frame\",
  scheduled_time_frame=\"$scheduled_time_frame\",
  productionmeeting_user=\"$productionmeeting_user\",
  priority1=\"$priority1\",
  priority2=\"$priority2\",
  priority3=\"$priority3\",
  priority1_rate=\"$priority1_rate\",
  priority2_rate=\"$priority2_rate\",
  priority3_rate=\"$priority3_rate\",
  custom_sd_field=\"$custom_sd_field\",
  custom_sd_field2=\"$custom_sd_field2\",
  xml_sd_export=\"$xml_sd_export\",
  from_email=\"$from_email\",
  ar_account=\"$ar_account\",
  sales_account=\"$sales_account\",
  logo_map=\"$logo_map\",
  checks_payable_to=\"$checks_payable_to\",
  company_code=\"$company_code\",
  acct_rec_code=\"$acct_rec_code\",
  general_ledger_acct=\"$general_ledger_acct\",
  sales_tax_acct=\"$sales_tax_acct\",
  timezone=\"$timezone\"
  where id = '$id'";
  executeupdate($sql);
}

if($submit1=="delgroup"){
  $sql = "SELECT master_id from groups where id='$group_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT user_id, groups from users where master_id='" . $SESSION_MASTER_ID . "' and groups like '%," . $group_id . ",%'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $user_id = $record['user_id'];
	$groups = $record['groups'];
	$groups = go_reg_replace("," . $group_id . ",", "", $groups);
	$sql = "UPDATE users set groups='$groups' where user_id='$user_id'";
	executeupdate($sql);
  }
  $sql = "SELECT prospect_id, groups from prospects where master_id='" . $SESSION_MASTER_ID . "' and groups like '%," . $group_id . ",%'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $prospect_id = $record['prospect_id'];
	$groups = $record['groups'];
	$groups = go_reg_replace("," . $group_id . ",", "", $groups);
	$sql = "UPDATE prospects set groups='$groups' where prospect_id='$prospect_id'";
	executeupdate($sql);
  }
  $sql = "SELECT a.property_id, a.groups from properties a, prospects b where b.master_id='" . $SESSION_MASTER_ID . "' and a.groups = '" . $group_id . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $property_id = $record['property_id'];
	$groups = $record['groups'];
	$groups = go_reg_replace("," . $group_id . ",", "", $groups);
	$sql = "UPDATE properties set groups='$groups' where property_id='$property_id'";
	executeupdate($sql);
  }
  
  
  $sql = "SELECT id from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $id = $record['id'];
  
  
    $sql = "SELECT user_id, subgroups from users where master_id='" . $SESSION_MASTER_ID . "' and subgroups like '%," . $id . ",%'";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $user_id = $record['user_id'];
	  $subgroups = $record['subgroups'];
	  $subgroups = go_reg_replace("," . $id . ",", "", $subgroups);
	  $sql = "UPDATE users set subgroups='$subgroups' where user_id='$user_id'";
	  executeupdate($sql);
    }
    $sql = "SELECT prospect_id, subgroups from prospects where master_id='" . $SESSION_MASTER_ID . "' and subgroups like '%," . $id . ",%'";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $prospect_id = $record['prospect_id'];
	  $subgroups = $record['subgroups'];
	  $subgroups = go_reg_replace("," . $id . ",", "", $subgroups);
	  $sql = "UPDATE prospects set subgroups='$subgroups' where prospect_id='$prospect_id'";
	  executeupdate($sql);
    }
    $sql = "SELECT a.property_id, a.subgroups from properties a, prospects b where b.master_id='" . $SESSION_MASTER_ID . "' and a.subgroups = '" . $id . "'";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $property_id = $record['property_id'];
	  $subgroups = $record['subgroups'];
	  $subgroups = go_reg_replace("," . $id . ",", "", $subgroups);
	  $sql = "UPDATE properties set subgroups='$subgroups' where property_id='$property_id'";
	  executeupdate($sql);
    }
  }
  $sql = "DELETE from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id'";
  executeupdate($sql);
  $sql = "DELETE from groups where master_id='" . $SESSION_MASTER_ID . "' and id='$group_id'";
  executeupdate($sql);
	
}

if($submit1=="delsubgroup"){
  $sql = "SELECT master_id from groups where id='$subgroup_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
    $sql = "SELECT user_id, subgroups from users where master_id='" . $SESSION_MASTER_ID . "' and subgroups like '%," . $subgroup_id . ",%'";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $user_id = $record['user_id'];
	  $subgroups = $record['subgroups'];
	  $subgroups = go_reg_replace("," . $subgroup_id . ",", "", $subgroups);
	  $sql = "UPDATE users set subgroups='$subgroups' where user_id='$user_id'";
	  executeupdate($sql);
    }
    $sql = "SELECT prospect_id, subgroups from prospects where master_id='" . $SESSION_MASTER_ID . "' and subgroups like '%," . $subgroup_id . ",%'";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $prospect_id = $record['prospect_id'];
	  $subgroups = $record['subgroups'];
	  $subgroups = go_reg_replace("," . $subgroup_id . ",", "", $subgroups);
	  $sql = "UPDATE prospects set subgroups='$subgroups' where prospect_id='$prospect_id'";
	  executeupdate($sql);
    }
    $sql = "SELECT a.property_id, a.subgroups from properties a, prospects b where b.master_id='" . $SESSION_MASTER_ID . "' and a.subgroups = '" . $subgroup_id . "'";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $property_id = $record['property_id'];
	  $subgroups = $record['subgroups'];
	  $subgroups = go_reg_replace("," . $subgroup_id . ",", "", $subgroups);
	  $sql = "UPDATE properties set subgroups='$subgroups' where property_id='$property_id'";
	  executeupdate($sql);
    }

  $sql = "DELETE from groups where master_id='" . $SESSION_MASTER_ID . "' and id='$subgroup_id'";
  executeupdate($sql);
}

if($submit1=="companyinfo"){
  $company_info_group_id = go_escape_string($_POST['company_info_group_id']);
  $master_name = go_escape_string($_POST['master_name']);
  $dispatch_from_email = go_escape_string($_POST['dispatch_from_email']);
  $address = go_escape_string($_POST['address']);
  $city = go_escape_string($_POST['city']);
  $state = go_escape_string($_POST['state']);
  $zip = go_escape_string($_POST['zip']);
  $invoice_user = go_escape_string($_POST['invoice_user']);
  $phone = go_escape_string($_POST['phone']);
  $fax = go_escape_string($_POST['fax']);
  $website = go_escape_string($_POST['website']);
  $emergency_time_frame = go_escape_string($_POST['emergency_time_frame']);
  $urgent_time_frame = go_escape_string($_POST['urgent_time_frame']);
  $scheduled_time_frame = go_escape_string($_POST['scheduled_time_frame']);
  $productionmeeting_user = go_escape_string($_POST['productionmeeting_user']);
  $priority1 = go_escape_string($_POST['priority1']);
  $priority2 = go_escape_string($_POST['priority2']);
  $priority3 = go_escape_string($_POST['priority3']);
  $priority1_rate = go_escape_string($_POST['priority1_rate']);
  $priority2_rate = go_escape_string($_POST['priority2_rate']);
  $priority3_rate = go_escape_string($_POST['priority3_rate']);
  $custom_sd_field = go_escape_string($_POST['custom_sd_field']);
  $custom_sd_field2 = go_escape_string($_POST['custom_sd_field2']);
  $xml_sd_export = go_escape_string($_POST['xml_sd_export']);
  $from_email = go_escape_string($_POST['from_email']);
  $ar_account = go_escape_string($_POST['ar_account']);
  $sales_account = go_escape_string($_POST['sales_account']);
  $checks_payable_to = go_escape_string($_POST['checks_payable_to']);
  $company_code = go_escape_string($_POST['company_code']);
  $acct_rec_code = go_escape_string($_POST['acct_rec_code']);
  $general_ledger_acct = go_escape_string($_POST['general_ledger_acct']);
  $sales_tax_acct = go_escape_string($_POST['sales_tax_acct']);
  $timezone = go_escape_string($_POST['timezone']);
  
  $sql = "UPDATE groups set master_name=\"$master_name\",
  dispatch_from_email=\"$dispatch_from_email\",
  address=\"$address\",
  city=\"$city\",
  state=\"$state\",
  zip=\"$zip\",
  invoice_user=\"$invoice_user\",
  phone=\"$phone\",
  fax=\"$fax\",
  website=\"$website\",
  emergency_time_frame=\"$emergency_time_frame\",
  urgent_time_frame=\"$urgent_time_frame\",
  scheduled_time_frame=\"$scheduled_time_frame\",
  productionmeeting_user=\"$productionmeeting_user\",
  priority1=\"$priority1\",
  priority2=\"$priority2\",
  priority3=\"$priority3\",
  priority1_rate=\"$priority1_rate\",
  priority2_rate=\"$priority2_rate\",
  priority3_rate=\"$priority3_rate\",
  custom_sd_field=\"$custom_sd_field\",
  custom_sd_field2=\"$custom_sd_field2\",
  xml_sd_export=\"$xml_sd_export\",
  from_email=\"$from_email\",
  ar_account=\"$ar_account\",
  sales_account=\"$sales_account\",
  checks_payable_to=\"$checks_payable_to\",
  company_code=\"$company_code\",
  acct_rec_code=\"$acct_rec_code\",
  general_ledger_acct=\"$general_ledger_acct\",
  sales_tax_acct=\"$sales_tax_acct\",
  timezone=\"$timezone\"
  where id = '$company_info_group_id'";
  executeupdate($sql);
  
  $sql = "UPDATE properties set timezone=\"$timezone\" where groups='$company_info_group_id' or subgroups='$company_info_group_id'";
  executeupdate($sql);
  

  if (is_uploaded_file($_FILES['logo']['tmp_name']))
  {
	if(is_image_valid($_FILES['logo']['name'])){
	$ext = explode(".", $_FILES['logo']['name']);
  	$ext = array_pop($ext);
  	$filename = secretCode() . "." . $ext;
	move_uploaded_file($_FILES['logo']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE groups set logo='$filename' where id='$company_info_group_id'";
	executeupdate($sql);
	}
  }
  

  if (is_uploaded_file($_FILES['logo2']['tmp_name']))
  {
	if(is_image_valid($_FILES['logo2']['name'])){
	$ext = explode(".", $_FILES['logo2']['name']);
  	$ext = array_pop($ext);
  	$filename = secretCode() . "." . $ext;
	move_uploaded_file($_FILES['logo2']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE groups set logo2='$filename' where id='$company_info_group_id'";
	executeupdate($sql);
	}
  }
  
  

  if (is_uploaded_file($_FILES['logo_report']['tmp_name']))
  {
	if(is_image_valid($_FILES['logo_report']['name'])){
	$ext = explode(".", $_FILES['logo_report']['name']);
  	$ext = array_pop($ext);
  	$filename = secretCode() . "." . $ext;
	move_uploaded_file($_FILES['logo_report']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 400);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE groups set logo_report='$filename' where id='$company_info_group_id'";
	executeupdate($sql);
	}
  }
  
  

  if (is_uploaded_file($_FILES['logo_map']['tmp_name']))
  {
	if(is_image_valid($_FILES['logo_map']['name'])){
	$ext = explode(".", $_FILES['logo_map']['name']);
  	$ext = array_pop($ext);
  	$filename = secretCode() . "." . $ext;
	move_uploaded_file($_FILES['logo_map']['tmp_name'], "uploaded_files/temp/". $filename);
  	resizeimage("uploaded_files/temp/$filename", "uploaded_files/master_logos/", 100);
  	@unlink("uploaded_files/temp/". $filename);
	
	$sql = "UPDATE groups set logo_map='$filename' where id='$company_info_group_id'";
	executeupdate($sql);
	}
  }
  
}

meta_redirect("admin_groups.php");
?>