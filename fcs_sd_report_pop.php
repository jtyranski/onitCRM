<?php
include "includes/functions.php";

$sql = "SELECT sd_results_per_page from users where user_id='$SESSION_USER_ID'";
$SD_RESULTS_PER_PAGE = getsingleresult($sql);



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
  if($_SESSION['sdreport_archivefilter'] != ""){
    $archivefilter = $_SESSION['sdreport_archivefilter'];
  }
  else {
    $archivefilter = "0";
  }
}
if($archivefilter==0){
  $archive_search = " and a.archive=0 ";
}
else{
  $archive_search = " and a.archive=1 ";
}

$datefilter = $_GET['datefilter'];
if($datefilter == "") {
  if($_SESSION['sdreport_datefilter'] != ""){
    $datefilter = $_SESSION['sdreport_datefilter'];
  }
  else {
    $datefilter = "All";
  }
}

$statusfilter = $_GET['statusfilter'];
if($statusfilter == "") {
  if($_SESSION['sdreport_statusfilter'] != ""){
    $statusfilter = $_SESSION['sdreport_statusfilter'];
  }
  else {
    $statusfilter = "All";
  }
}

$typefilter = $_GET['typefilter'];
if($typefilter == "") {
  if($_SESSION['sdreport_typefilter'] != ""){
    $typefilter = $_SESSION['sdreport_typefilter'];
  }
  else {
    $typefilter = "All";
  }
}

$servicemanfilter = $_GET['servicemanfilter'];

if($servicemanfilter=="0") $servicemanfilter = "All";
if($servicemanfilter == "") {
  if($_SESSION['sdreport_servicemanfilter'] != ""){
    $servicemanfilter = $_SESSION['sdreport_servicemanfilter'];
  }
  else {
    $servicemanfilter = "All";
  }
}

$contractorfilter = $_GET['contractorfilter'];
if($contractorfilter == "") {
  if($_SESSION['sdreport_contractorfilter'] != ""){
    $contractorfilter = $_SESSION['sdreport_contractorfilter'];
  }
  else {
    $contractorfilter = "All";
  }
}

$priorityfilter = $_GET['priorityfilter'];
if($priorityfilter == "") {
  if($_SESSION['sdreport_priorityfilter'] != ""){
    $priorityfilter = $_SESSION['sdreport_priorityfilter'];
  }
  else {
    $priorityfilter = "0";
  }
}
$priority_search = "";
if($priorityfilter) $priority_search = " and a.priority_id = '$priorityfilter' ";



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
    $section_type_search = "";
	break;
  }
  default:{
    $section_type_search = " and a.section_type='$typefilter' ";
	break;
  }
}


switch($servicemanfilter){
  case "All":{
    $servicemanfilter_search = "";
	break;
  }
  default:{
    if(go_reg("\*", $servicemanfilter)){
	  $resource_id = go_reg_replace("\*", "", $servicemanfilter);
	  $servicemanfilter_search = " and a.resource_id='$resource_id' ";
	}
	else {
      $servicemanfilter_search = " and (a.servicemen_id='$servicemanfilter' or a.servicemen_id2='$servicemanfilter') ";
	}
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
$contractorfilter_search = "";

switch($invoicetypefilter){
  case "All":{
    $invoicetypefilter_search = "";
	break;
  }
  /*
  case "2Year":{
    $invoicetypefilter_search = " (a.invoice_type='2 Year' or a.invoice_type='Warranty') and (a.status='Invoiced' or a.status='Closed Out')' ";
	break;
  }
  */
  default:{
    $invoicetypefilter_search = " and a.invoice_type='$invoicetypefilter' ";
	break;
  }
}

$order_by = $_GET['order_by'];
if($order_by == "") {
  if($_SESSION['sdreport_order_by'] != ""){
    $order_by = $_SESSION['sdreport_order_by'];
  }
  else {
    $order_by = "a.invoice_id";
  }
}
$order_by2 = $_GET['order_by2'];
if($order_by2 == "") {
  if($_SESSION['sdreport_order_by2'] != ""){
    $order_by2 = $_SESSION['sdreport_order_by2'];
  }
  else {
    $order_by2 = "desc";
  }
}
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

$zip = $_GET['zip'];
$distance = $_GET['distance'];

$results_per_page = $SD_RESULTS_PER_PAGE;

$start_record = $_GET['start_record'];
if($start_record=="") $start_record = 0;

$extra_searchby = $_GET['extra_searchby'];
//if($extra_searchby=="") $extra_searchby = $_SESSION['sd_report_extra_searchby'];

$extra_searchfor = $_GET['extra_searchfor'];
//if($extra_searchfor=="") $extra_searchfor = $_SESSION['sd_report_extra_searchby'];

$extra_search = "";
if($extra_searchfor != ""){
  switch($extra_searchby){
    case "invoice_id":{
	  $extra_search = " and a.invoice_id like '%$extra_searchfor%' ";
	  break;
	}
	case "company":{
	  $extra_search = " and d.company_name like '%$extra_searchfor%' ";
	  break;
	}
	case "property":{
	  $extra_search = " and b.site_name like '%$extra_searchfor%' ";
	  break;
	}
  }
}



if($datefilter=="All" || $datefilter=="YTD" || $datefilter=="3Month" || $datefilter=="Custom"){
  $_SESSION['sdreport_datefilter'] = $datefilter;
}
$_SESSION['sdreport_statusfilter'] = $statusfilter;
$_SESSION['sdreport_typefilter'] = $typefilter;
$_SESSION['sdreport_order_by'] = $order_by;
$_SESSION['sdreport_order_by2'] = $order_by2;
$_SESSION['sdreport_servicemanfilter'] = $servicemanfilter;
$_SESSION['sdreport_contractorfilter'] = $contractorfilter;
$_SESSION['sdreport_priorityfilter'] = $priorityfilter;

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

$groups_clause = " 1=1 ";
$groups_filter_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
 $sql = "SELECT pre_invoiceid from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$pre_invoiceid = getsingleresult($sql);
$sql = "SELECT xml_sd_export from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $xml_sd_export = getsingleresult($sql);
  if($xml_sd_export != "Timberline") $pre_invoiceid = 0;
  
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " b.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and b.subgroups in ($subgroups_search)";
  }
  
  
  if($SESSION_GROUPS != ""){
    $groups_raw = explode(",", $SESSION_GROUPS);
    $groups = array();
    for($x=0;$x<sizeof($groups_raw);$x++){
      if($groups_raw[$x] == "") continue;
	  $groups[] = $groups_raw[$x];
    }
  }
  
  if($SESSION_SUBGROUPS != ""){
    $subgroups_raw = explode(",", $SESSION_SUBGROUPS);
    $subgroups = array();
    for($x=0;$x<sizeof($subgroups_raw);$x++){
      if($subgroups_raw[$x] == "") continue;
	  $subgroups[] = $subgroups_raw[$x];
    }
  }

  
  $group_filter = $_GET['group_filter'];
  $subgroup_filter = $_GET['subgroup_filter'];
  if($group_filter != ""){
    $group_array = explode(",", $group_filter);
	$group_filter = array();
	for($x=0;$x<sizeof($group_array);$x++){
	  if($group_array[$x]=="") continue;
	  $group_filter[] = $group_array[$x];
	}
  }
  
  if($subgroup_filter != ""){
    $subgroup_array = explode(",", $subgroup_filter);
	$subgroup_filter = array();
	for($x=0;$x<sizeof($subgroup_array);$x++){
	  if($subgroup_array[$x]=="") continue;
	  $subgroup_filter[] = $subgroup_array[$x];
	}
  }
  
  if(!(is_array($group_filter))) $group_filter = $_SESSION['sdreport_group_filter'];
  if(!(is_array($subgroup_filter))) $subgroup_filter = $_SESSION['sdreport_subgroup_filter'];
  if(!(is_array($group_filter))) $group_filter = array();
  if(!(is_array($subgroup_filter))) $subgroup_filter = array();
  
  $_SESSION['sdreport_group_filter'] = $group_filter;
  $_SESSION['sdreport_subgroup_filter'] = $subgroup_filter;
  
  
  $groups_array = $group_filter;
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_filter_clause = " b.groups in($groups_search) ";;
  if($groups_filter_clause == "") $groups_filter_clause = " 1=1 ";
  
  if(sizeof($subgroup_filter) > 0){
    if($groups_filter_clause == " 1=1 "){
	  $groups_filter_clause = "(";
	}
	else {
      $groups_filter_clause = "(" . $groups_filter_clause;
	}
    $subgroups_array = $subgroup_filter;
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($groups_filter_clause != "(") $groups_filter_clause .= " or ";
    $groups_filter_clause = $groups_filter_clause .= " b.subgroups in($subgroups_search)) ";
  } 
  
}

$sql = "SELECT count(*) from groups where master_id='" . $SESSION_MASTER_ID . "'";
$group_test = getsingleresult($sql);

if($group_test){
  $sql = "SELECT priority1, priority2, priority3, emergency_time_frame, urgent_time_frame, scheduled_time_frame, id, company_code from groups where master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $xid = $record['id'];
    $priority[$xid][1] = stripslashes($record['priority1']);
    $priority[$xid][2] = stripslashes($record['priority2']);
    $priority[$xid][3] = stripslashes($record['priority3']);
	$COMPANY_CODE[$xid] = stripslashes($record['company_code']);
  }
}

$sql = "SELECT priority1, priority2, priority3, emergency_time_frame, urgent_time_frame, scheduled_time_frame from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$priority[0][1] = stripslashes($record['priority1']);
$priority[0][2] = stripslashes($record['priority2']);
$priority[0][3] = stripslashes($record['priority3']);


$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' order by user_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x = $record['user_id'];
  $SERVICEMAN[$x] = stripslashes($record['fullname']);
}
$counter = 0;

$sql = "SELECT a.leak_id, a.status, b.site_name, b.city, b.state, a.admin_resolve, a.admin_invoice, a.invoice_total, a.invoice_type, 
b.property_id, a.section_type, b.division_id, a.section_id, a.additional, a.section_id, d.prospect_id, d.company_name, a.cont_id, a.fix_contractor, 
a.servicemen_id, a.invoice_id, a.invoice_filename, a.priority_id, b.groups, b.subgroups
from am_leakcheck a, properties b, prospects d
where a.property_id=b.property_id and a.prospect_id=d.prospect_id and a.display=1 and a.demo=0 
and $datesearch 
$section_type_search 
$servicemanfilter_search 
$contractorfilter_search
$invoicetypefilter_search
and $invoicedatefilter_search
$archive_search
$priority_search
$extra_search
and $groups_clause
and $groups_filter_clause
and d.master_id='" . $SESSION_MASTER_ID . "'
order by $order_by $order_by2 ";
$result = executequery($sql);
$total_records = mysql_num_rows($result);
//$sql .= "limit $start_record, $results_per_page";

$result = executequery($sql);
while($record = go_fetch_array($result)){

  if($SESSION_IREP==1){
	    $sql = "SELECT count(*) from properties where prospect_id='" . $record['prospect_id'] . "' and display=1 and irep='" . $SESSION_USER_ID . "'";
        $test_irep = getsingleresult($sql);
		if($test_irep==0) continue;
  }
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
  $invoice_id = stripslashes($record['invoice_id']);
  
  $invoice_filename = stripslashes($record['invoice_filename']);
  $priority_id = stripslashes($record['priority_id']);
  if($status!="Confirmed" && $status != "Invoiced" && $status != "Closed Out") $invoice_total = "";
  $property['groups'] = go_reg_replace(",", "", stripslashes($record['groups']));
  $property['subgroups'] = go_reg_replace(",", "", stripslashes($record['subgroups']));
  $USEGROUP = 0;
  if($property['groups'] != "" && $property['groups'] !=0) $USEGROUP = $property['groups'];
  if($property['subgroups'] != "" && $property['subgroups'] != 0) $USEGROUP = $property['subgroups'];
  if($USEGROUP=="") $USEGROUP = 0;
  if($pre_invoiceid==1 && $USEGROUP != 0) $invoice_id = $COMPANY_CODE[$USEGROUP] . "-" . $invoice_id;
  
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
  if($status == "Hold") $status_rank = 1.5;
  if($status == "Acknowledged" || $status == "New Project") $status_rank = 2;
  if($status == "Arrival ETA") $status_rank = 3;
  if($status == "In Progress") $status_rank = 4;
  if($status == "Resolved") $status_rank = 5;
  if($status == "Confirmed") $status_rank = 6;
  if($status == "Invoiced") $status_rank = 7;
  if($status == "Closed Out") $status_rank = 8;
  
  $contractor_company_name = $fix_contractor;
  $contractor_prospect_id = "";
  
  if($contractor_company_name=="RoofOptions") $contractor_company_name =  $SERVICEMAN[$servicemen_id];
  /*
  if($cont_id){
    $sql = "SELECT company_name, prospect_id from prospects where cont_id='$cont_id'";
    $result_cont = executequery($sql);
    $record_cont = go_fetch_array($result_cont);
    $contractor_company_name = stripslashes($record_cont['company_name']);
    $contractor_prospect_id = stripslashes($record_cont['prospect_id']);
  }
  */
  
  //$sql = "SELECT am_contractor_id from sections where section_id='" . $record['section_id'] . "'";
  //$am_contractor_id = getsingleresult($sql);
  
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
  date_format(date_add(a.dispatch_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as dispatch, 
  date_format(date_add(a.fix_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as resolved, 
  date_format(date_add(a.acknowledge_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as acknowledge, 
  date_format(date_add(a.eta_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as eta, 
  date_format(date_add(a.confirm_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as confirm, 
  date_format(date_add(a.invoice_sent_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as invoice, 
  date_format(date_add(a.inprogress_date, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as inprogress, 
  date_format(date_add(a.lastupdate, interval $timezone hour), \"%m/%d/%Y %h:%i %p\") as closed
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
  
  /*
  if($status == "Dispatched" || $status=="New Project" || $status=="Hold") $lastupdate = $dispatch;
  if($status == "Acknowledged") $lastupdate = $acknowledge;
  if($status == "Arrival ETA") $lastupdate = $eta;
  if($status == "Resolved") $lastupdate = $resolved;
  if($status == "Confirmed") $lastupdate = $confirm;
  if($status == "Invoiced") $lastupdate = $invoice;
  if($status == "In Progress") $lastupdate = $inprogress;
  if($status == "Closed Out") $lastupdate = $closed;
  if($force_invoicedate==1) $lastupdate = $invoice;
  */
  
  $lastupdate = stripslashes($record_times['closed']) . " " . $tz_display;
  
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
  $row[$counter]['invoice_id'] = $invoice_id;
  $row[$counter]['invoice_filename'] = $invoice_filename;
  $row[$counter]['priority'] = $priority[$USEGROUP][$priority_id];
  $row[$counter]['priority_id'] = $priority_id;
  
  $counter++;
}

if($zip){
  if($distance){
        $srch_serv_prov = array();
		
		$latitude = array();
		$longitute = array();
		$sqlzips = "SELECT * from zipcodes";
		$resultzips = executequery($sqlzips);
		while($record = go_fetch_array($resultzips)){
		  $x = $record['zipcode'];
		  $latitude[$x] = $record['latitude'];
		  $longitude[$x] = $record['longitude'];
		}
		$sql_new="select * from	zipcodes where zipcode='$zip' limit 1";
		$result2=executeQuery($sql_new);
		if($line2=go_fetch_array($result2))
		{
			$search_longitude=$line2["longitude"];
			$search_latitude=$line2["latitude"];
		}
		$counter = 0;
	    for($y=0;$y<sizeof($row);$y++){
		  $x = $row[$y]['zip'];
			$x = go_reg_replace("\-.*", "", $x);
			//$distance2=great_circle_distance($search_latitude,$line['latitude'],$search_longitude,$line['longitude']);
			$distance2=great_circle_distance($search_latitude,$latitude[$x],$search_longitude,$longitude[$x]);
			//echo "<br>distance2:$distance2<br>";
			if($distance2 <= $distance)
			{
			    //if($counter >= 2000) continue;
				$row2[$counter] = $row[$y];
			    $counter++;
				
			}

		}
		$row = $row2;
  }
}

//usort($row, $function);

ob_start();
?>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><input type='checkbox' onchange="SetChecked(this, 'leak_ids[]')"></td>
<td><strong><?=sort_header('a.invoice_id', "Invoice ID", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('d.company_name', "Company", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('b.site_name', "Site", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('b.state', "Location", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong>Section</strong></td>
<td nowrap="nowrap"><strong><?=sort_header('a.dispatch_date', "Dispatched", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('a.lastupdate', "Status Timestamp", "fcs_sd_report.php")?></strong></td>
<?php /*
<td nowrap="nowrap"><strong><?=sort_header('contractor_company_name', "Contractor")?></strong></td>
*/?>
<td nowrap="nowrap"><strong><?=sort_header('a.status', "Status", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('a.priority_id', "Priority", "fcs_sd_report.php")?></strong></td>
<td nowrap="nowrap"><strong><?=sort_header('a.invoice_total', "Invoice", "fcs_sd_report.php")?></strong></td>
</tr>
<?php

for($x=0;$x<sizeof($row);$x++){
  $color = "black";
  if($row[$x]['status']=="Dispatched" || $row[$x]['status']=="New Project" || $row[$x]['status']=="Acknowledged" || $row[$x]['status']=="Hold") $color = "red";
  if($row[$x]['status']=="Arrival ETA") $color = "#FF9146";
  if($row[$x]['status']=="In Progress") $color = "#BFBF00";
  if($row[$x]['status']=="Resolved") $color = "#00FF00";
  if($row[$x]['status']=="Confirmed") $color = "#0000FF";
  if($row[$x]['status']=="Invoiced") $color = "#006633";
  if($row[$x]['status']=="Closed Out") $color = "black";
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td nowrap="nowrap"><?php if($SESSION_ISADMIN){ ?>
  <input type="checkbox" name="leak_ids[]" value="<?=$row[$x]['leak_id']?>">
  <?php } ?></td>
  <td valign="top">
  <a style="color:<?=$color?>;" href="fcs_sd_report_view<?=$special_invoice?>.php?leak_id=<?=$row[$x]['leak_id']?>">
  <?=$row[$x]['invoice_id']?></a>
  </td>
  <td valign="top"><a style="color:<?=$color?>;" href="view_company.php?prospect_id=<?=$row[$x]['prospect_id']?>" target="_blank"><?=$row[$x]['company_name']?></a></td>
  <td valign="top"><a style="color:<?=$color?>;" href="view_property.php?property_id=<?=$row[$x]['property_id']?>" target="_blank"><?=$row[$x]['site_name']?></a></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['city'] .", ". $row[$x]['state']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['section_name']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['dispatch']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['lastupdate']?></td>
  <?php /*
  <td valign="top" style="color:<?=$color?>;">
  <?=$row[$x]['contractor_company_name']?>
  </td>
  */?>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['status']?></td>
  <td valign="top" style="color:<?=$color?>;"><?=$row[$x]['priority']?></td>
  <td valign="top" style="color:<?=$color?>;">
  <?php if($row[$x]['invoice_total'] != ""){ ?>
  <?php if($row[$x]['invoice_filename'] != ""){?><a href="uploaded_files/download/<?=$row[$x]['invoice_filename']?>" target="_blank"><?php } ?>
    $<?=number_format($row[$x]['invoice_total'], 2)?>
  <?php if($row[$x]['invoice_filename'] != ""){?></a><?php } ?>
  <?php } ?>
  </td>
  </tr>
  <?php
}
?>
</table>
<?php
$html = ob_get_contents();
ob_end_clean();

$html = jsclean($html);
?>
document.getElementById('report_results').innerHTML = '<?=$html?>';
<?php
/*
$numrecords = sizeof($row);
$start_record_display = $start_record + 1;
$end_record_display = $numrecords + $start_record;

$shownext = 1;
$nextblock = "";
if(($results_per_page + $start_record) >= $total_records) $shownext=0;
if($shownext){
  $nextblock = " <a href=\\\"javascript:next_prev('1')\\\"><img src='images/arrow_right.gif' border='0'></a> ";
}

$showprev = 1;
$prevblock = "";
if($start_record==0) $showprev = 0;
if($showprev){
  $prevblock = " <a href=\\\"javascript:next_prev('-1')\\\"><img src='images/arrow_left.gif' border='0'></a> ";
}

$navigation = $prevblock . " Showing $start_record_display through " . number_format($end_record_display, 0) . "(" . number_format($total_records, 0) . " total) " . $nextblock;
?>
document.getElementById('navigation').style.display='none';
<?php
if($shownext==1 || $showprev==1){
  ?>
  document.getElementById('navigation').style.display='';
  document.getElementById('navigation').innerHTML = "<?=$navigation?>";
  <?php
}
*/
?>