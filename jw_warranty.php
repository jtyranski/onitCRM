<?php
include "includes/functions.php";

$sql = "SELECT a.drawing_id, a.bill_to, a.bt_manufacturer, a.bt_installer, b.master_id from drawings a, prospects b where a.prospect_id=b.prospect_id 
and a.type='Warranty'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $drawing_id = stripslashes($record['drawing_id']);
  $bill_to = stripslashes($record['bill_to']);
  $bt_manufacturer = stripslashes($record['bt_manufacturer']);
  $bt_installer = stripslashes($record['bt_installer']);
  $master_id = stripslashes($record['master_id']);
  if($bill_to=="Installer") $company = $bt_installer;
  if($bill_to=="Manufacturer") $company = $bt_manufacturer;
  
  $sql = "SELECT address, city, state, zip from prospects where master_id='$master_id' and company_name=\"$company\" and industry=1";
  $res2 = executequery($sql);
  $rec2 = go_fetch_array($res2);
  $address = go_escape_string(stripslashes($rec2['address']));
  $city = go_escape_string(stripslashes($rec2['city']));
  $state = go_escape_string(stripslashes($rec2['state']));
  $zip = go_escape_string(stripslashes($rec2['zip']));
  
  $sql = "UPDATE drawings set bt_address=\"$address\", bt_city=\"$bt_city\", bt_state=\"$bt_state\", bt_zip=\"$bt_zip\" where drawing_id='$drawing_id'";
  executeupdate($sql);
}
?>
  