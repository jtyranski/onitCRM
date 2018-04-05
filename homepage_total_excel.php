<?php
include "includes/functions.php";
if($SESSION_MASTER_ID != 1) exit;

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") $order_by = "master_name";
if($order_by2 == "") $order_by2 = "asc";
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

function compare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function rcompare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return 1;
else
 return -1;
}

$row = $_SESSION['master_list_total'];
usort($row, $function);

require_once "excel/class.writeexcel_workbook.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

$fname = tempnam("/tmp", "simple.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

$worksheet->write(0, 0,  "Client");
$worksheet->write(0, 1,  "Created");
$worksheet->write(0, 2,  "Users");
$worksheet->write(0, 3,  "Companies");
$worksheet->write(0, 4,  "Properties");
$worksheet->write(0, 5,  "Sections");
$worksheet->write(0, 6,  "Sqft");
$worksheet->write(0, 7,  "Prospects");
$worksheet->write(0, 8,  "Candidates");
$worksheet->write(0, 9,  "Clients");
$worksheet->write(0, 10,  "Vendors");
$worksheet->write(0, 11,  "Contacts");
$worksheet->write(0, 12,  "Meetings");
$worksheet->write(0, 13,  "Inspections");
$worksheet->write(0, 14,  "Dispatches");
$worksheet->write(0, 15,  "Dispatches Done");
$worksheet->write(0, 16,  "Qwikbids");
$worksheet->write(0, 17,  "Quoted $");
$worksheet->write(0, 18,  "Quoted #");
$worksheet->write(0, 19,  "Sold $");
$worksheet->write(0, 20,  "Sold #");
$worksheet->write(0, 21,  "Dead $");
$worksheet->write(0, 22,  "Dead #");
$worksheet->write(0, 23,  $MAIN_CO_NAME . " Certified");

for($x=0;$x<sizeof($row);$x++){
  $counter = $x+1;
  $worksheet->write($counter, 0,  stripslashes($row[$x]['master_name']));
  $worksheet->write($counter, 1,  stripslashes($row[$x]['date_created_pretty']));
  $worksheet->write($counter, 2,  stripslashes($row[$x]['total_users']));
  $worksheet->write($counter, 3,  stripslashes($row[$x]['total_companies']));
  $worksheet->write($counter, 4,  stripslashes($row[$x]['total_properties']));
  $worksheet->write($counter, 5,  stripslashes($row[$x]['total_sections']));
  $worksheet->write($counter, 6,  stripslashes($row[$x]['total_sqft']));
  $worksheet->write($counter, 7,  stripslashes($row[$x]['total_prospects']));
  $worksheet->write($counter, 8,  stripslashes($row[$x]['total_candidates']));
  $worksheet->write($counter, 9,  stripslashes($row[$x]['total_clients']));
  $worksheet->write($counter, 10,  stripslashes($row[$x]['total_vendors']));
  $worksheet->write($counter, 11,  stripslashes($row[$x]['contact_total']));
  $worksheet->write($counter, 12,  stripslashes($row[$x]['meeting_total']));
  $worksheet->write($counter, 13,  stripslashes($row[$x]['inspection_total']));
  $worksheet->write($counter, 14,  stripslashes($row[$x]['total_sd']));
  $worksheet->write($counter, 15,  stripslashes($row[$x]['total_sd_done']));
  $worksheet->write($counter, 16,  stripslashes($row[$x]['qb_total']));
  $worksheet->write($counter, 17,  stripslashes($row[$x]['totalquoted']));
  $worksheet->write($counter, 18,  stripslashes($row[$x]['numberquoted']));
  $worksheet->write($counter, 19,  stripslashes($row[$x]['totalsold']));
  $worksheet->write($counter, 20,  stripslashes($row[$x]['numbersold']));
  $worksheet->write($counter, 21,  stripslashes($row[$x]['totaldead']));
  $worksheet->write($counter, 22,  stripslashes($row[$x]['numberdead']));
  $worksheet->write($counter, 23,  stripslashes($row[$x]['percent']));
}

$workbook->close();

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/x-msexcel; name=\"fcs_master_list.xls\"");
header("Content-Disposition: inline; filename=\"fcs_master_list.xls\"");


$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>
