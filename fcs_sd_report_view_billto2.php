<?php
include "includes/functions.php";

$property_id = $_GET['property_id'];
$prospect_id = $_GET['prospect_id'];
$x = $_GET['x'];

if($x=="company"){
  $sql = "SELECT company_name, address, city, state, zip from prospects where prospect_id='" . $prospect_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $prospect_id . "' 
order by id limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$contact['fullname'] = stripslashes($record['fullname']);
$contact['phone'] = stripslashes($record['phone']);

$billto = $contact['fullname'] . "ZZZZ";
$billto .= $company['name'] . "ZZZZ";
$billto .= $company['address'] . "ZZZZ";
$billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'] . "ZZZZ";
$billto .= $contact['phone'];
}

if($x=="property"){
  $sql = "SELECT site_name, address, city, state, zip from properties where property_id='" . $property_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['site_name']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where property_id='" . $property_id . "' 
order by id limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$contact['fullname'] = stripslashes($record['fullname']);
$contact['phone'] = stripslashes($record['phone']);

$billto = $contact['fullname'] . "ZZZZ";
$billto .= $company['name'] . "ZZZZ";
$billto .= $company['address'] . "ZZZZ";
$billto .= $company['city'] . ", " . $company['state'] . " " . $company['zip'] . "ZZZZ";
$billto .= $contact['phone'];
}

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
		
