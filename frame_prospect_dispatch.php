<?php include "includes/header_white.php"; ?>
<?php
$x_prospect_id = $_GET['prospect_id'];
?>
<script src="includes/calendar.js"></script>
<script>

function datefilter_change(x){
  if(x.value=="Custom"){
    document.getElementById("customdate").style.display="";
  }
  else {
    document.getElementById("customdate").style.display="none";
  }
}
</script>
<?php

//used on redirect from serviceman report
// if this value is being passed, blank out all filters from before so we're sure to get all of this
$invoicetypefilter = $_GET['invoicetypefilter'];

if($invoicetypefilter != ""){
/*
  $_SESSION['sdreport_datefilter'] = "";
  $_SESSION['sdreport_statusfilter'] = "";
  $_SESSION['sdreport_typefilter'] = "";
  $_SESSION['sdreport_servicemanfilter'] = "";
  $_SESSION['sdreport_contractorfilter'] = "";
  $_SESSION['sdreport_archivefilter'] = "";
  */
  $force_invoicedate = 1;
}


if($invoicetypefilter=="") $invoicetypefilter="All";
if($invoicetypefilter=="All") $force_invoicedate=0;

$invoicedatefilter = $_GET['invoicedatefilter'];
if($invoicedatefilter=="") $invoicedatefilter = "All";

$archivefilter = $_GET['archivefilter'];
if($archivefilter == "") {

    $archivefilter = "0";

}
if($archivefilter==0){
  $archive_search = " a.archive=0 ";
}
else{
  $archive_search = " a.archive=1 ";
}

$datefilter = $_GET['datefilter'];
if($datefilter == "") {

    $datefilter = "All";

}

$statusfilter = $_GET['statusfilter'];
if($statusfilter == "") {

    $statusfilter = "All";

}

$typefilter = $_GET['typefilter'];
if($typefilter == "") {

    $typefilter = "All";

}

$servicemanfilter = $_GET['servicemanfilter'];
if($servicemanfilter==0) $servicemanfilter = "All";
if($servicemanfilter == "") {
    $servicemanfilter = "All";
}


switch($datefilter){
  case "All":{
    $datesearch = " 1=1 ";
	break;
  }
  case "YTD":{
    $datesearch = " a.dispatch_date like '" . date("Y") . "%' ";
	break;
  }
  case "3Month":{
    $datesearch = " a.dispatch_date >= date_sub(now(), interval 3 month) ";
	break;
  }
  case "Custom":{
    $startdate = $_GET['startdate'];
	$enddate = $_GET['enddate'];
	$start_parts = explode("/", $startdate);
    $startdate_pretty = $start_parts[2] . "-" . $start_parts[0] . "-" . $start_parts[1];
	$end_parts = explode("/", $enddate);
    $enddate_pretty = $end_parts[2] . "-" . $end_parts[0] . "-" . $end_parts[1];
	$datesearch = " a.dispatch_date >= '$startdate_pretty' and a.dispatch_date <= '$enddate_pretty 23:59:59' ";
	break;
  }
	
  default:{
    $datesearch = " 1=1 ";
	break;
  }
}


$wd = date('w') ;
$daterange = "1=1";
// this week
switch($invoicedatefilter){
  case "todate":{
    $daterange = "1=1";
	break;
  }
  case "today":{
    $daterange = "XXX like '" . date("Y") . "-" . date("m") . "-" . date("d") . "%'";
	break;
  }
  case "yesterday":{
    $yesterday = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$daterange = "XXX like '" . $yesterday . "%' ";
	break;
  }
case "thisweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval 0 DAY) and XXX <= date_add(curdate(), interval 6 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -1 DAY) and XXX <= date_add(curdate(), interval 5 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -2 DAY) and XXX <= date_add(curdate(), interval 4 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -3 DAY) and XXX <= date_add(curdate(), interval 3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -4 DAY) and XXX <= date_add(curdate(), interval 2 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -5 DAY) and XXX <= date_add(curdate(), interval 1 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -6 DAY) and XXX <= date_add(curdate(), interval 0 DAY)';
    break;
  }
}
// end this week
break;
}

case "lastweek":{
// last week
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval -7 DAY) and XXX <= date_add(curdate(), interval 0 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -8 DAY) and XXX <= date_add(curdate(), interval -1 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -9 DAY) and XXX <= date_add(curdate(), interval -2 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -10 DAY) and XXX <= date_add(curdate(), interval -3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -11 DAY) and XXX <= date_add(curdate(), interval -4 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -12 DAY) and XXX <= date_add(curdate(), interval -5 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -13 DAY) and XXX <= date_add(curdate(), interval -6 DAY)';
    break;
  }
}
// end last week
break;
}
  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  case "lastmonth":{
    $d = date("j");
    $foo = 0;
    if($d==30 || $d==31) $foo = 3;
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d") - $foo,   date("Y"));
	$timesearch = date("Y-m", $lastmonth);
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  case "YTD":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  case "3Month":{
    $daterange = " XXX >= date_sub(now(), interval 3 month) ";
	break;
  }
  case "Custom":{
    $startdate = $_GET['startdate'];
	$enddate = $_GET['enddate'];
	$start_parts = explode("/", $startdate);
    $startdate_pretty = $start_parts[2] . "-" . $start_parts[0] . "-" . $start_parts[1];
	$end_parts = explode("/", $enddate);
    $enddate_pretty = $end_parts[2] . "-" . $end_parts[0] . "-" . $end_parts[1];
	$daterange = " XXX >= '$startdate_pretty' and XXX <= '$enddate_pretty 23:59:59' ";
	break;
  }
  
  default:{
    $daterange = "XXX like '$searchby%'";
	break;
  }
  
} // end switch of searchby

$invoicedatefilter_search = $daterange;
$invoicedatefilter_search = go_reg_replace("XXX", "a.invoice_date", $invoicedatefilter_search);

switch($typefilter){
  case "All":{
    $section_type_search = " 1=1 ";
	break;
  }
  default:{
    $section_type_search = " a.section_type='$typefilter' ";
	break;
  }
}

switch($servicemanfilter){
  case "All":{
    $servicemanfilter_search = " 1=1 ";
	break;
  }
  default:{
    $servicemanfilter_search = " (a.servicemen_id='$servicemanfilter' or a.servicemen_id2='$servicemanfilter') ";
	break;
  }
}
/*
switch($contractorfilter){
  case "All":{
    $contractorfilter_search = " 1=1 ";
	break;
  }
  case "RoofOptions":{
    $contractorfilter_search = " a.fix_contractor='RoofOptions' ";
	break;
  }
  case "Other":{
    $contractorfilter_search = " a.fix_contractor!='RoofOptions' ";
	break;
  }
}
*/
$contractorfilter_search = " 1=1 ";

switch($invoicetypefilter){
  case "All":{
    $invoicetypefilter_search = " 1=1 ";
	break;
  }
  case "2Year":{
    $invoicetypefilter_search = " (a.invoice_type='2 Year' or a.invoice_type='Warranty') and (a.status='Invoiced' or a.status='Closed Out')' ";
	break;
  }
  default:{
    $invoicetypefilter_search = " a.invoice_type='$invoicetypefilter' and (a.status='Invoiced' or a.status='Closed Out')";
	break;
  }
}

$order_by = $_GET['order_by'];
if($order_by == "") {

    $order_by = "leak_id";

}
$order_by2 = $_GET['order_by2'];
if($order_by2 == "") {

    $order_by2 = "desc";

}
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

$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' order by user_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x = $record['user_id'];
  $SERVICEMAN[$x] = stripslashes($record['fullname']);
}

$counter = 0;

$sql = "SELECT a.leak_id, a.status, b.site_name, b.city, b.state, a.admin_resolve, a.admin_invoice, a.invoice_total, a.invoice_type, 
b.property_id, a.section_type, b.division_id, a.section_id, a.additional, a.section_id, d.prospect_id, d.company_name, a.cont_id, a.fix_contractor, 
a.servicemen_id 
from am_leakcheck a, properties b, prospects d
where a.property_id=b.property_id and a.prospect_id=d.prospect_id and a.display=1 and a.demo=0 and 
$datesearch and $section_type_search 
and $servicemanfilter_search 
and $invoicetypefilter_search
and $invoicedatefilter_search
and $archive_search
and a.resource_id = '$x_prospect_id' 
and a.accept_resource = 1
and d.master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  $additional = $record['additional'];
  $prospect_id = stripslashes($record['prospect_id']);
  $property_id = stripslashes($record['property_id']);
  $section_type = stripslashes($record['section_type']);
  $status = stripslashes($record['status']);
  $leak_id = stripslashes($record['leak_id']);
  $site_name = stripslashes($record['site_name']);
  $site_city = stripslashes($record['city']);
  $site_state = stripslashes($record['state']);
  $section_id = stripslashes($record['section_id']);
  $admin_resolve = stripslashes($record['admin_resolve']);
  $admin_invoice = stripslashes($record['admin_invoice']);
  $company_name = stripslashes($record['company_name']);
  $fix_contractor = stripslashes($record['fix_contractor']);
  $cont_id = stripslashes($record['cont_id']);
  $invoice_total = stripslashes($record['invoice_total']);
  $invoice_type = stripslashes($record['invoice_type']);
  $servicemen_id = stripslashes($record['servicemen_id']);
  if($status!="Confirmed" && $status != "Invoiced" && $status != "Closed Out") $invoice_total = "";
  

  if($invoice_type=="") $invoice_total = "";
  /*
  if($status == "Resolved" && $admin_resolve == 0){
    $sql = "SELECT date_format(eta_date, \"%Y\") from am_leakcheck where leak_id='$leak_id'";
	$testeta = getsingleresult($sql);
	if($testeta=="0000"){
	  $status = "Acknowledged";
	}
	else {
	  $status = "Arrival ETA";
	}
  }
  */
  
  if($statusfilter != "All"){
    if($status != $statusfilter) continue;
  }
  
  if($status == "Dispatched") $status_rank = 1;
  if($status == "Acknowledged") $status_rank = 2;
  if($status == "Arrival ETA") $status_rank = 3;
  if($status == "In Progress") $status_rank = 4;
  if($status == "Resolved") $status_rank = 5;
  if($status == "Confirmed") $status_rank = 6;
  if($status == "Invoiced") $status_rank = 7;
  if($status == "Closed Out") $status_rank = 8;
  
  $contractor_company_name = $fix_contractor;
  $contractor_prospect_id = "";
  

  
  if($section_id != 0){
    $sql = "SELECT section_name from sections where section_id='$section_id'";
    $section_name = stripslashes(getsingleresult($sql));
  }
  else {
    $section_name = "Unknown";
  }
  
  $sql = "SELECT timezone from properties where property_id='$property_id'";
  $timezone = getsingleresult($sql);
  if($timezone=="") $timezone = 0;
  
  switch($timezone){
    case 1:{
	  $tz_display = "EST";
	  break;
	}
	case 0:{
	  $tz_display = "CST";
	  break;
	}
	case -1:{
	  $tz_display = "MST";
	  break;
	}
	case -2:{
	  $tz_display = "PST";
	  break;
	}
	default:{
	  $tz_display = "CST";
	  break;
	}
  }
  
  $sql = "SELECT 
  date_format(date_add(a.dispatch_date, interval $timezone hour), \"%m/%d/%Y %r\") as dispatch, 
  date_format(date_add(a.fix_date, interval $timezone hour), \"%m/%d/%Y %r\") as resolved, 
  date_format(date_add(a.acknowledge_date, interval $timezone hour), \"%m/%d/%Y %r\") as acknowledge, 
  date_format(date_add(a.eta_date, interval $timezone hour), \"%m/%d/%Y %r\") as eta, 
  date_format(date_add(a.confirm_date, interval $timezone hour), \"%m/%d/%Y %r\") as confirm, 
  date_format(date_add(a.invoice_date, interval $timezone hour), \"%m/%d/%Y %r\") as invoice, 
  date_format(date_add(a.inprogress_date, interval $timezone hour), \"%m/%d/%Y %r\") as inprogress, 
  date_format(date_add(a.closed_date, interval $timezone hour), \"%m/%d/%Y %r\") as closed
  from am_leakcheck a where leak_id='$leak_id'";
  $result_times = executequery($sql);
  $record_times = go_fetch_array($result_times);
  $dispatch = stripslashes($record_times['dispatch']) . " " . $tz_display;
  $acknowledge = stripslashes($record_times['acknowledge']) . " " . $tz_display;
  $eta = stripslashes($record_times['eta']) . " " . $tz_display;
  $resolved = stripslashes($record_times['resolved']) . " " . $tz_display;
  $confirm = stripslashes($record_times['confirm']) . " " . $tz_display;
  $invoice = stripslashes($record_times['invoice']) . " " . $tz_display;
  $inprogress = stripslashes($record_times['inprogress']) . " " . $tz_display;
  $closed = stripslashes($record_times['closed']) . " " . $tz_display;
  
  if($status == "Dispatched") $lastupdate = $dispatch;
  if($status == "Acknowledged") $lastupdate = $acknowledge;
  if($status == "Arrival ETA") $lastupdate = $eta;
  if($status == "Resolved") $lastupdate = $resolved;
  if($status == "Confirmed") $lastupdate = $confirm;
  if($status == "Invoiced") $lastupdate = $invoice;
  if($status == "In Progress") $lastupdate = $inprogress;
  if($status == "Closed Out") $lastupdate = $closed;
  if($force_invoicedate==1) $lastupdate = $invoice;
  
  $row[$counter]['status'] = $status;
  $row[$counter]['leak_id'] = $leak_id;
  $row[$counter]['lastupdate'] = $lastupdate;
  $row[$counter]['site_name'] = $site_name;
  $row[$counter]['city'] = $site_city;
  $row[$counter]['state'] = $site_state;
  $row[$counter]['section_name'] = $section_name;
  $row[$counter]['company_name'] = $company_name;
  $row[$counter]['status_rank'] = $status_rank;
  $row[$counter]['prospect_id'] = $prospect_id;
  $row[$counter]['property_id'] = $property_id;
  $row[$counter]['contractor_company_name'] = $contractor_company_name;
  $row[$counter]['contractor_prospect_id'] = $contractor_prospect_id;
  $row[$counter]['section_type'] = $section_type;
  $row[$counter]['invoice_total'] = $invoice_total;
  $row[$counter]['dispatch'] = $dispatch;
  
  $counter++;
}
usort($row, $function);
?>
<div class="main_superlarge"><?=$MAIN_CO_NAME?> Service Dispatch Report</div>
<div class="main">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
<?php /* <input type="hidden" name="invoicetypefilter" value="<?=$invoicetypefilter?>"> */ ?>
<input type="hidden" name="invoicedatefilter" value="<?=$invoicedatefilter?>">


<div style="width:100%; position:relative;">

<div style="float:left; padding-right:10px;">
Dispatched:
<select name="datefilter" onchange="datefilter_change(this)">
<option value="All"<?php if($datefilter=="All") echo " selected";?>>All</option>
<option value="YTD"<?php if($datefilter=="YTD") echo " selected";?>>Year to Date</option>
<option value="3Month"<?php if($datefilter=="3Month") echo " selected";?>>Past 3 Months</option>
<option value="Custom"<?php if($datefilter=="Custom") echo " selected";?>>Custom</option>
</select>
  <div id="customdate" <?php if($datefilter != "Custom") {?> style="display:none;"<?php } ?>>
  <table class="main">
  <tr>
  <td>
  From:
  </td>
  <td>
  <input size="10" type="text" name="startdate" value="<?=$startdate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('startdate',0)" align="absmiddle">
  </td>
  </tr>
  <tr>
  <td>
  To:
  </td>
  <td>
  <input size="10" type="text" name="enddate" value="<?=$enddate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('enddate',0)" align="absmiddle">
  </td>
  </tr>
  </table>
  </div>
</div>

<div style="float:left; padding-right:10px;">
Status: 
<select name="statusfilter">
<option value="All"<?php if($statusfilter=="All") echo " selected";?>>All</option>
<option value="Dispatched"<?php if($statusfilter=="Dispatched") echo " selected";?>>Dispatched</option>
<option value="Arrival ETA"<?php if($statusfilter=="Arrival ETA") echo " selected";?>>Arrival ETA</option>
<option value="In Progress"<?php if($statusfilter=="In Progress") echo " selected";?>>In Progress</option>
<option value="Resolved"<?php if($statusfilter=="Resolved") echo " selected";?>>Resolved</option>
<option value="Confirmed"<?php if($statusfilter=="Confirmed") echo " selected";?>>Confirmed</option>
<option value="Invoiced"<?php if($statusfilter=="Invoiced") echo " selected";?>>Invoiced</option>
<option value="Closed Out"<?php if($statusfilter=="Closed Out") echo " selected";?>>Closed Out</option>
</select>

</div>

<div style="float:left; padding-right:10px;">
Type:
<select name="typefilter">
<option value="All"<?php if($typefilter=="All") echo " selected";?>>All</option>
<option value="roof"<?php if($typefilter=="roof") echo " selected";?>>Roof</option>
<option value="paving"<?php if($typefilter=="paving") echo " selected";?>>Paving</option>
<option value="mech"<?php if($typefilter=="mech") echo " selected";?>>Mechanical</option>
<option value="ww"<?php if($typefilter=="ww") echo " selected";?>>Windows Walls</option>
</select>

</div>
<?php /*
<div style="float:left; padding-right:10px;">
Assigned to:
<select name="servicemanfilter">
<option value="All">All</option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and (servicemen=1 or resource=1) and master_id='" . $SESSION_MASTER_ID . "' order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$servicemanfilter) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</div>
*/?>

<div style="float:left; padding-right:10px;">
Invoice:
<select name="invoicetypefilter">
<option value="All">All</option>
<option value="Billable - TM"<?php if($invoicetypefilter=="Billable - TM") echo " selected";?>>Billable - T&amp;M</option>
  <option value="Billable - Contract(minor)"<?php if($invoicetypefilter=="Billable - Contract(minor)") echo " selected";?>>Billable - Contract(minor)</option>
  <option value="Billable - Contract(major)"<?php if($invoicetypefilter=="Billable - Contract(major)") echo " selected";?>>Billable - Contract(major)</option>
  <option value="Billable - Warranty"<?php if($invoicetypefilter=="Billable - Warranty") echo " selected";?>>Billable - Warranty</option>
  <option value="2 Year"<?php if($invoicetypefilter=="2 Year") echo " selected";?>>2 Year</option>
  <option value="Warranty"<?php if($invoicetypefilter=="Warranty") echo " selected";?>>Warranty</option>
  <option value="Promotional"<?php if($invoicetypefilter=="Promotional") echo " selected";?>>Promotional</option>
  <option value="Project Mgmt"<?php if($invoicetypefilter=="Project Mgmt") echo " selected";?>>Project Mgmt</option>
  <option value="RTM"<?php if($invoicetypefilter=="RTM") echo " selected";?>>RTM</option>
  <option value="RTM - Prevent"<?php if($invoicetypefilter=="RTM - Prevent") echo " selected";?>>RTM Preventative</option>
</select>
</div>


<div style="float:left; padding-right:10px;">
<select name="archivefilter">
<option value="0">Active</option>
<option value="1"<?php if($archivefilter=="1") echo " selected";?>>Archived</option>
</select>
</div>

<div style="float:left; padding-right:10px;">
<input type="submit" name="submit1" value="Filter">
</div>


</div>
<div style="clear:both;"></div>

</form>
</div>
<?php
if($force_invoicedate==1){
  $invoice_header = "Invoice Date";
}
else {
  $invoice_header = "Status Changed";
}
?>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong><?=sort_header('leak_id', "Dispatch ID")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('company_name', "Company")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('site_name', "Site")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('state', "Location")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('section_name', "Section")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('dispatch', "Dispatched")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('lastupdate', $invoice_header)?></strong></td>

<td nowrap="nowrap"><strong><?=sort_header('status_rank', "Status")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('invoice_total', "Invoice")?></strong></td>
</tr>
<?php

for($x=0;$x<sizeof($row);$x++){
  $color = "black";
  if($row[$x]['status']=="Dispatched") $color = "red";
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top">
  <a style="color:<?=$color?>;" href="toolbox.php?go=fcs_sd_report_view.php*leak_id=<?=$row[$x]['leak_id']?>" target="_blank">
  <?=$row[$x]['leak_id']?></a>
  </td>
  <td valign="top"><a style="color:<?=$color?>;" href="view_company.php?prospect_id=<?=$row[$x]['prospect_id']?>" target="_blank"><?=$row[$x]['company_name']?></a></td>
  <td valign="top"><a style="color:<?=$color?>;" href="view_property.php?property_id=<?=$row[$x]['property_id']?>" target="_blank"><?=$row[$x]['site_name']?></a></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['city'] .", ". $row[$x]['state']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['section_name']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['dispatch']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['lastupdate']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['status']?></td>
  <td valign="top" style="color:<?=$color?>;">
  <?php if($row[$x]['invoice_total'] != ""){ ?>
    $<?=number_format($row[$x]['invoice_total'], 2)?>
  <?php } ?>
  </td>
  </tr>
  <?php
}
?>
</table>
  
