<?php 
include "includes/functions.php"; 


  
require_once "excel/class.writeexcel_workbook.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

$fname = tempnam("/tmp", "simple.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

if($_SESSION['list_boxtype']=="property"){
  $worksheet->write(0, 0,  "Property");
  $worksheet->write(0, 1,  "City");
  $worksheet->write(0, 2,  "State");
  $worksheet->write(0, 3,  "SQS");
  $worksheet->write(0, 4,  "Sales Status");
  $worksheet->write(0, 5,  "RO Status");
  $worksheet->write(0, 6,  "Comp");
  $worksheet->write(0, 7,  "Type");
  $worksheet->write(0, 8,  "Last Action");
  $worksheet->write(0, 9,  "Region");
  $worksheet->write(0, 10,  "RR/RM");
  
  $counter=0;
  for($x=0;$x<sizeof($_SESSION['list_property_id']);$x++){
    $x_property_id = $_SESSION['list_property_id'][$x];

    $sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, a.territory, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, a.zip, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region, a.ro_status
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and a.property_id='$x_property_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$property_type = $record['property_type'];
	  if($property_type=="Manville"){
		$sql = "SELECT comp_amount from properties_manville where property_id='$x_property_id'";
		$money = getsingleresult($sql);
	  }

	  if($property_type=="Beazer" || $property_type=="Beazer B"){
		$sql = "SELECT settlement_number from properties_beazer where property_id='$x_property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  $rr_rm = "";
	  $sql = "SELECT count(*) from opportunities where property_id='$x_property_id' and display=1 and product='Roof Replacement'";
	  $rr = getsingleresult($sql);
	  if($rr != 0) $rr_rm = "RR";
	  $sql = "SELECT count(*) from opportunities where property_id='$x_property_id' and display=1 and product='Roof Management'";
	  $rm = getsingleresult($sql);
	  if($rm != 0) {
	    if($rr_rm==""){
		  $rr_rm = "RM";
		}
		else {
		  $rr_rm .= "/RM";
		}
	  }
	$counter++;
	$worksheet->write($counter, 0,  stripslashes($record['site_name']));
	$worksheet->write($counter, 1,  stripslashes($record['city']));
	$worksheet->write($counter, 2,  stripslashes($record['state']));
	$worksheet->write($counter, 3,  number_format($record['roof_size'], 0));
	$worksheet->write($counter, 4,  stripslashes($record['sales_stage']));
	$worksheet->write($counter, 5,  stripslashes($record['ro_status']));
	$worksheet->write($counter, 6,  number_format($money, 2));
	$worksheet->write($counter, 7,  stripslashes($record['property_type']));
	$worksheet->write($counter, 8,  stripslashes($record['lastaction_pretty']));
	$worksheet->write($counter, 9,  stripslashes($record['territory']));
	$worksheet->write($counter, 10,  stripslashes($rr_rm));

  }
} 
  
if($_SESSION['list_boxtype']=="prospect"){
  $worksheet->write(0, 0,  "Company");
  $worksheet->write(0, 1,  "City");
  $worksheet->write(0, 2,  "State");
  $worksheet->write(0, 3,  "Properties");
  //$worksheet->write(0, 4,  "Status");
  $worksheet->write(0, 4,  "Last Action");
  //$worksheet->write(0, 6,  "Identifier");

  $counter = 0;
  for($x=0;$x<sizeof($_SESSION['list_prospect_id']);$x++){
    $x_prospect_id = $_SESSION['list_prospect_id'][$x];
	$sql = "SELECT company_name, city, state, zip, 
	properties, property_type, identifier, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from prospects where prospect_id='$x_prospect_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);


	$counter++;
	$worksheet->write($counter, 0,  stripslashes($record['company_name']));
	$worksheet->write($counter, 1,  stripslashes($record['city']));
	$worksheet->write($counter, 2,  stripslashes($record['state']));
	$worksheet->write($counter, 3,  stripslashes($record['properties']));
	//$worksheet->write($counter, 4,  stripslashes($record['prospect_status']));
	$worksheet->write($counter, 4,  stripslashes($record['lastaction_pretty']));
	//$worksheet->write($counter, 6,  stripslashes($record['identifier']));

  }
}  // end search display company
  
if($_SESSION['list_boxtype']=="contact"){
  $worksheet->write(0, 0,  "Property");
  $worksheet->write(0, 1,  "City");
  $worksheet->write(0, 2,  "State");
  $worksheet->write(0, 3,  "Contact");
  $worksheet->write(0, 4,  "Position");
  $worksheet->write(0, 5,  "Phone");
  $worksheet->write(0, 6,  "Mobile");
  $worksheet->write(0, 7,  "Email");

  $counter = 0;
  for($x=0;$x<sizeof($_SESSION['list_contact_id']);$x++){
    $x_contact_id = $_SESSION['list_contact_id'][$x];
  
    $sql = "SELECT concat(firstname, ' ', lastname) as fullname, position, phone, mobile, email, prospect_id, property_id 
    from contacts where id='$x_contact_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
	$fullname = stripslashes($record['fullname']);
	$position = stripslashes($record['position']);
	$phone = stripslashes($record['phone']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	$prospect_id = stripslashes($record['prospect_id']);
	$property_id = stripslashes($record['property_id']);
	
	if($prospect_id != 0){
	  $sql = "SELECT company_name as site_name, city, state from prospects where prospect_id='$prospect_id'";
	}
	if($property_id != 0){
	  $sql = "SELECT site_name, city, state from properties where property_id='$property_id'";
	}
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$site_name = stripslashes($record['site_name']);
	$city = stripslashes($record['city']);
	$state = stripslashes($record['state']);
	
	

	$counter++;
	$worksheet->write($counter, 0,  $site_name);
	$worksheet->write($counter, 1,  $city);
	$worksheet->write($counter, 2,  $state);
	$worksheet->write($counter, 3,  $fullname);
	$worksheet->write($counter, 4,  $position);
	$worksheet->write($counter, 5,  $phone);
	$worksheet->write($counter, 6,  $mobile);
	$worksheet->write($counter, 7,  $email);

  }
}  // end search display company



$workbook->close();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/x-msexcel; name=\"search_export.xls\"");
header("Content-Disposition: inline; filename=\"search_export.xls\"");


$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);

?>
