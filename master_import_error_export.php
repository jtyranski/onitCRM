<?php 
include "includes/functions.php"; 


  
require_once "excel/class.writeexcel_workbook.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

$fname = tempnam("/tmp", "simple.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

$type = $_POST['type'];
$ids = $_POST['ids'];

if($type=="contact"){
  $worksheet->write(0, 0,  "Property ID");
  $worksheet->write(0, 1,  "Cust ID");
  $worksheet->write(0, 2,  "First Name");
  $worksheet->write(0, 3,  "Last Name");
  $worksheet->write(0, 4,  "Position");
  $worksheet->write(0, 5,  "Phone");
  $worksheet->write(0, 6,  "Fax");
  $worksheet->write(0, 7,  "Mobile");
  $worksheet->write(0, 8,  "Email");
  $worksheet->write(0, 9,  "Reason");
  
  $counter = 0;
  for($x=0;$x<sizeof($ids);$x++){
    $id = $ids[$x];
	$counter++;
	$sql = "SELECT * from import_errors_contact where id='$id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$worksheet->write($counter, 0,  stripslashes($record['custom_property_id']));
	$worksheet->write($counter, 1,  stripslashes($record['cust_id']));
	$worksheet->write($counter, 2,  stripslashes($record['firstname']));
	$worksheet->write($counter, 3,  stripslashes($record['lastname']));
	$worksheet->write($counter, 4,  stripslashes($record['position']));
	$worksheet->write($counter, 5,  stripslashes($record['phone']));
	$worksheet->write($counter, 6,  stripslashes($record['fax']));
	$worksheet->write($counter, 7,  stripslashes($record['mobile']));
	$worksheet->write($counter, 8,  stripslashes($record['email']));
	$worksheet->write($counter, 9,  stripslashes($record['reason']));
  }
}

if($type=="property"){
  $worksheet->write(0, 0,  "Property ID");
  $worksheet->write(0, 1,  "Cust ID");
  $worksheet->write(0, 2,  "Site Name");
  $worksheet->write(0, 3,  "Address");
  $worksheet->write(0, 4,  "City");
  $worksheet->write(0, 5,  "State");
  $worksheet->write(0, 6,  "Zip");
  $worksheet->write(0, 7,  "Reason");
  
  $counter = 0;
  for($x=0;$x<sizeof($ids);$x++){
    $id = $ids[$x];
	$counter++;
	$sql = "SELECT * from import_errors_property where id='$id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$worksheet->write($counter, 0,  stripslashes($record['custom_property_id']));
	$worksheet->write($counter, 1,  stripslashes($record['cust_id']));
	$worksheet->write($counter, 2,  stripslashes($record['site_name']));
	$worksheet->write($counter, 3,  stripslashes($record['address']));
	$worksheet->write($counter, 4,  stripslashes($record['city']));
	$worksheet->write($counter, 5,  stripslashes($record['state']));
	$worksheet->write($counter, 6,  stripslashes($record['zip']));
	$worksheet->write($counter, 7,  stripslashes($record['reason']));
  }
}


$workbook->close();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/x-msexcel; name=\"error_export.xls\"");
header("Content-Disposition: inline; filename=\"error_export.xls\"");


$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>
	