<?php
include "includes/functions.php";

$user_id = $_GET['user_id'];
if($user_id=="") $user_id="all";
$view = $_GET['view'];
if($view=="") $view = "All";

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") $order_by = "a.lastaction";
if($order_by2 == "") $order_by2 = "desc";
$order_clause = " order by $order_by $order_by2";



$projected = $_GET['projected'];
if($projected == "") $projected = 0;

if($projected == 0){
  $projected_clause = " 1=1 ";
}
else {
  $projected_clause = " a.projected_replacement='$projected' ";
}

$type_search = $_GET['type_search'];
switch($type_search){
  case "Beazer":{
    $type_clause = " c.property_type='Beazer' ";
	break;
  }
  case "Manville":{
    $type_clause = " (c.property_type='Beazer B' or c.property_type='Manville') ";
	break;
  }
  case "Non-PFRI":{
    $type_clause = " c.property_type='Non-PFRI' ";
	break;
  }
  default:{
    $type_clause = " 1=1 ";
	break;
  }
}

$amount_clause = " 1=1 ";
$hide_zero_amount = $_GET['hide_zero_amount'];
if($hide_zero_amount == "-1") $hide_zero_amount = 0;
if($hide_zero_amount==1) $amount_clause = " a.amount > 0 ";

$srmanager = $_GET['srmanager'];
$srmanager = 0;
if($srmanager) {
  $srmanager_clause = " a.srmanager = '$srmanager' ";
}
else {
  $srmanager_clause = " 1=1 ";
}

$product = $_GET['product'];

if($product) {
  $product_clause = " a.product = '$product' ";
}
else {
  $product_clause = " 1=1 ";
}


$report_view = $_GET['report_view'];


if($report_view=="Rep"){
  $sql = "SELECT user_id from users where report=1 and enabled=1 and telemarketing=0";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $userlist[] = $record['user_id'];
  }
  $rep_header="PM";
  $sql_user_id = " a.user_id = b.user_id ";
}

if($report_view=="TM"){
  $sql = "SELECT user_id from users where enabled=1 and telemarketing=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $userlist[] = $record['user_id'];
  }
  $rep_header = "TM";
  $sql_user_id = " a.scheduled_by = b.user_id ";
}

if($user_id == "all") {
  $totalclause = " 1=1 ";
}
else {
  if($report_view=="Rep"){
    $totalclause = " user_id = '$user_id' ";
  }
  else {
    $totalclause = " scheduled_by = '$user_id' ";
  }
}

if($report_view=="Rep"){
  $user_clause = " a.user_id in (" . implode(",", $userlist) . ") ";
}
else {
  $user_clause = " a.scheduled_by in (" . implode(",", $userlist) . ") ";
}

$opp_stage_id = $_GET['opp_stage_id'];
if($opp_stage_id != "All") {
  $opp_stage_id_clause = " a.opp_stage_id = '$opp_stage_id' ";
}
else {
  $opp_stage_id_clause = " 1=1 ";
}

$tm = $_GET['tm'];

$sql = "SELECT user_id from users where consolidated_report=1 and telemarketing=1 and enabled=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $opp_tm_users[] = $record['user_id'];
}

if($tm==1){
  $opp_tm_clause = " a.scheduled_by in (" . implode(",", $opp_tm_users) . ") ";
}
else {
  $opp_tm_clause = " 1=1 ";
}

$territory = $_GET['territory'];

if($territory=="All"){
  $territory_clause = " 1=1 ";
}
else {
  $territory_clause = " c.territory = '$territory' ";
}
$rd = $_SERVER['QUERY_STRING'];
$rd = go_reg_replace("\&", "*", $rd);

if($_SESSION['ro_irep'] != "") {
  $user_id = $_SESSION['ro_irep'];
  $irep_clause = " and c.account_manager = '" . $_SESSION['ro_irep'] . "' ";
}




switch($view){
  case "All":{
    $whereclause = " And 1=1 ";
	break;
  }
  case "Prospect":{
    $whereclause = " And a.status='Prospect' ";
	break;
  }
  case "Bid Procurement":{
    $whereclause = " And a.status='Bid Procurement' ";
	break;
  }
  case "Quoted":{
    $whereclause = " And a.status='Quoted' ";
	break;
  }
  case "Sold":{
    $whereclause = " And a.status='Sold' ";
	break;
  }
  case "Dead":{
    $whereclause = " And a.status='Dead' ";
	break;
  }
}

if($user_id == "all") {
  $totalclause = " 1=1 ";
}
else {
  if($report_view=="Rep"){
    $totalclause = " a.user_id = '$user_id' ";
  }
  else {
    $totalclause = " a.scheduled_by = '$user_id' ";
  }
}

/*
$sql = "SELECT a.status, a.product, a.amount, a.opp_id, a.user_id, b.firstname, date_format(a.lastaction, \"%m/%d/%y\") as datepretty, 
a.probability, a.property_id, a.prospect_id, a.projected_replacement, a.srmanager, a.loi_flag, a.lastaction, d.stage_name, d.display_order, 
a.opp_stage_changedate, date_format(a.opp_stage_changedate, \"%m/%d/%y\") as changedate_pretty 
from opportunities a, users b, properties c, opportunities_stages d
where a.user_id=b.user_id and b.report=1 
and $type_clause 
and a.property_id = c.property_id 
and a.opp_stage_id = d.opp_stage_id 
$whereclause and a.display=1 and $projected_clause and 
$amount_clause  
and $srmanager_clause
and $product_clause";
*/
$sql = "SELECT a.status, a.product, a.amount, a.opp_id, a.user_id, b.firstname, date_format(a.lastaction, \"%m/%d/%y\") as datepretty, 
a.probability, a.property_id, a.prospect_id, a.projected_replacement, a.srmanager, a.loi_flag, a.lastaction, d.stage_name, d.display_order, 
a.opp_stage_changedate, date_format(a.opp_stage_changedate, \"%m/%d/%y\") as changedate_pretty, 
date_format(a.paid_date, \"%m/%d/%y\") as paiddate_pretty, a.paid_date, a.tm_amount, c.territory
from opportunities a, users b, properties c, opportunities_stages d
where $sql_user_id and b.consolidated_report=1 
and $type_clause 
and $user_clause
and a.property_id = c.property_id 
and a.opp_stage_id = d.opp_stage_id 
$whereclause and a.display=1 and $projected_clause and 
$amount_clause  
and $srmanager_clause
and $product_clause
and $totalclause
and $opp_stage_id_clause
and $opp_tm_clause
and $territory_clause
$irep_clause";
$result = executequery($sql);
$counter=0;


require_once "excel/class.writeexcel_workbook.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

$fname = tempnam("/tmp", "simple.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

$worksheet->write(0, 0,  "Date");
$worksheet->write(0, 1,  "Status");
$worksheet->write(0, 2,  "Property");
$worksheet->write(0, 3,  "Region");
$worksheet->write(0, 4,  "Product");
$worksheet->write(0, 5,  "Amount");
$worksheet->write(0, 6,  $rep_header);
//$worksheet->write(0, 7,  "Sr Manager");
$worksheet->write(0, 7,  "Last Attempt");
$worksheet->write(0, 8,  "Last Complete");
$worksheet->write(0, 9,  "%");
$worksheet->write(0, 10,  "Projected");
$worksheet->write(0, 11,  "Stage");
$worksheet->write(0, 12,  "Date of Change");
if($report_view=="TM")  $worksheet->write(0, 13,  "Paid Date");


while($record = go_fetch_array($result)){
  if($record['product'] == "Risk Management Inspection" && $record['amount']==0) continue;
  $counter++;
  if($record['status'] == 'Sold' && $record['loi_flag'] == 1){
    $status = "Sold-LOI";
  }
  else {
    $status = $record['status'];
  }
  $status = $OPP_STATUS_SORT[$status];
  $property_id = $record['property_id'];
  
  $stage_name = stripslashes($record['stage_name']);
  $display_order = stripslashes($record['display_order']);
  $changedate_raw = stripslashes($record['opp_stage_changedate']);
  $changedate = stripslashes($record['changedate_pretty']);
  
  $paiddate = stripslashes($record['paiddate_pretty']);
  
  $projected_replacement = $record['projected_replacement'];
  if($projected_replacement=="0") $projected_replacement = "";
  $amount = $record['amount'];
  $tm_amount = $record['tm_amount'];
  if($report_view=="Rep") $amount_display = $amount;
  if($report_view=="TM") $amount_display = $tm_amount;
  $probability = $record['probability'] / 100;
  $subtotal = $amount * $probability;
  $total += $subtotal;
  $sql = "SELECT site_name, corporate from properties where property_id='" . $record['property_id'] . "'";
  $result2 = executequery($sql);
  $record2 = go_fetch_array($result2);
  $site_name = stripslashes($record2['site_name']);
  $corporate = $record2['corporate'];
  /*
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $record['srmanager'] . "'";
  $srmanager = stripslashes(getsingleresult($sql));
  */
  
  
  $sql = "SELECT complete_date, date_format(complete_date, \"%m/%d/%y\") as datepretty from activities where property_id='$property_id' 
  and (act_result='Completed' or act_result='Objective Met') order by complete_date desc limit 1";
  $result2 = executequery($sql);
  $record2 = go_fetch_array($result2);
  $lastcomplete = $record2['datepretty'];
  
  
  $sql = "SELECT complete_date, date_format(complete_date, \"%m/%d/%y\") as datepretty from activities where property_id='$property_id' 
  and (act_result='Attempted') order by complete_date desc limit 1";
  $result2 = executequery($sql);
  $record2 = go_fetch_array($result2);
  $lastattempt = $record2['datepretty'];
  
  $worksheet->write($counter, 0,  stripslashes($record['datepretty']));
  $worksheet->write($counter, 1,  $status);
  $worksheet->write($counter, 2,  $site_name);
  $worksheet->write($counter, 3,  $record['territory']);
  $worksheet->write($counter, 4,  stripslashes($record['product']));
  $worksheet->write($counter, 5,  number_format($amount_display, 0));
  $worksheet->write($counter, 6,  stripslashes($record['firstname']));
  //$worksheet->write($counter, 7,  $srmanager);
  $worksheet->write($counter, 7,  $lastattempt);
  $worksheet->write($counter, 8,  $lastcomplete);
  $worksheet->write($counter, 9,  number_format($record['probability'], 0));
  $worksheet->write($counter, 10,  $projected_replacement);
  $worksheet->write($counter, 11,  $stage_name);
  $worksheet->write($counter, 12,  $changedate);
  if($report_view=="TM") $worksheet->write($counter, 13,  $paiddate);
  
}

$workbook->close();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/x-msexcel; name=\"report_sales_status.xls\"");
header("Content-Disposition: inline; filename=\"report_sales_status.xls\"");


$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>  
