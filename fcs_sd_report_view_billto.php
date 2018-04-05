<?php
include "includes/functions.php";

$property_id = $_GET['property_id'];
$prospect_id = $_GET['prospect_id'];
$x = $_GET['x'];

switch($x){
  case "company":{
  $sql = "SELECT company_name, address, address2, city, state, zip, billto_address from prospects where prospect_id='" . $prospect_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['address'] = stripslashes($record['address']);
$company['address2'] = stripslashes($record['address2']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);
$company['billto_address'] = stripslashes($record['billto_address']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $prospect_id . "' 
order by id limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$contact['fullname'] = stripslashes($record['fullname']);
$contact['phone'] = stripslashes($record['phone']);

if($company['billto_address'] != "" && $company['billto_address'] != " "){
  $billto = $company['billto_address'];
  $billto = go_reg_replace("\n", "ZZZZ", $company['billto_address']);
}
else {
  $billto = "";
  //$billto .= $contact['fullname'] . "ZZZZ";
  $billto .= $company['name'] . "ZZZZ";
  $billto .= $company['address'] . "ZZZZ";
  if($company['address2'] != "") $billto .= $company['address2'] . "ZZZZ";
  $billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'];
  //$billto .= $contact['phone'];
}
break;
}

case "property":{
  $sql = "SELECT site_name, address, address2, city, state, zip, billto_address from properties where property_id='" . $property_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['site_name']);
$company['address'] = stripslashes($record['address']);
$company['address2'] = stripslashes($record['address2']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);
$company['billto_address'] = stripslashes($record['billto_address']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where property_id='" . $property_id . "' 
order by id limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$contact['fullname'] = stripslashes($record['fullname']);
$contact['phone'] = stripslashes($record['phone']);

if($company['billto_address'] != "" && $company['billto_address'] != " "){
  $billto = $company['billto_address'];
  $billto = go_reg_replace("\n", "ZZZZ", $company['billto_address']);
}
else {
  $billto = "";
  //$billto .= $contact['fullname'] . "ZZZZ";
  $billto .= $company['name'] . "ZZZZ";
  $billto .= $company['address'] . "ZZZZ";
  if($company['address2'] != "") $billto .= $company['address2'] . "ZZZZ";
  $billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'];
  //$billto .= $contact['phone'];
}
break;
}

default:{ // sending a drawing_id
  $sql = "SELECT bill_to, bt_installer, bt_manufacturer, bt_address, bt_city, bt_state, bt_zip, bt_contact, bt_phone from drawings where drawing_id='$x'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $bill_to = stripslashes($record['bill_to']);
  $bt_installer = stripslashes($record['bt_installer']);
  $bt_manufacturer = stripslashes($record['bt_manufacturer']);
  $bt_address = stripslashes($record['bt_address']);
  $bt_city = stripslashes($record['bt_city']);
  $bt_state = stripslashes($record['bt_state']);
  $bt_zip = stripslashes($record['bt_zip']);
  $bt_contact = stripslashes($record['bt_contact']);
  $bt_phone = stripslashes($record['bt_phone']);
  if($bill_to=="Installer") $company = $bt_installer;
  if($bill_to=="Manufacturer") $company = $bt_manufacturer;
  $billto = $company . "ZZZZ";
  if($bt_address != "") $billto .= $bt_address . "ZZZZ";
  if($bt_city != "") $billto .= $bt_city . ", " . $bt_state . " " . $bt_zip . "ZZZZ";
  if($bt_contact != "") $billto .= $bt_contact . "ZZZZ";
  if($bt_phone != "") $billto .= $bt_phone;
  break;
}
  
  
  
}// end switch

$billto = go_reg_replace("\<br \/\>", "ZZZZ", $billto);
$billto = go_reg_replace("\"", "'", $billto);
$billto = go_reg_replace(chr(10), "", $billto);
$billto = go_reg_replace(chr(12), "", $billto);
$billto = go_reg_replace(chr(13), "", $billto);
$billto = go_reg_replace("\n", "", $billto);

//$billto = "testZZZZ1234ZZZZ x is $x";

?>
document.getElementById('billto').value = "<?php echo $billto; ?>";

foo = document.getElementById('billto').value;
		bar = foo.replace(/ZZZZ/g, "\n");
		document.getElementById('billto').value = bar;
		
