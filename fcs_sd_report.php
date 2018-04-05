<?php include "includes/header_white.php"; ?>
<?php
$sql = "SELECT sd_results_per_page from users where user_id='$SESSION_USER_ID'";
$SD_RESULTS_PER_PAGE = getsingleresult($sql);

$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
if($invoice_type==2) $special_invoice = 2;
//$special_invoice = "";

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

?>
<script src="includes/calendar.js"></script>
<script>
function DelProspect(x){
  cf = confirm("Are you sure you want to delete this entry?");
  if(cf){
    document.location.href="fcs_sd_report_dispatch_delete.php?leak_id=" + x;
  }
}

function Archive(x){
  document.location.href="fcs_sd_report_dispatch_archive.php?leak_id=" + x;
}

function datefilter_change(x){
  if(x.value=="Custom"){
    document.getElementById("customdate").style.display="";
  }
  else {
    document.getElementById("customdate").style.display="none";
  }
}

var form='leaks'; //Give the form name here

function SetChecked(x,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}

function goform(x){
  if(x=="archive"){
    document.leaks.submit1.value="Archive Selected";
	document.leaks.submit();
  }
  if(x=="delete"){
    cf = confirm("Are you sure you want to delete these dispatches?");
	if(cf){
	  document.leaks.submit1.value="Delete Selected";
	  document.leaks.submit();
	}
  }
  if(x=="xml"){
    var form='leaks'; //Give the form name here
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var anycheck = 0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
        if(dml.elements[i].checked==true) anycheck = 1;
      }
    }

	if(anycheck==0){ 
	  for( i=0 ; i<len ; i++) {
        if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
          dml.elements[i].checked=true;
        }
      }
	}
	
    document.leaks.submit1.value="XML";
	document.leaks.submit();
  }
  if(x=="cease2"){
    var form='leaks'; //Give the form name here
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var anycheck = 0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
        if(dml.elements[i].checked==true) anycheck = 1;
      }
    }

	if(anycheck==0){ 
	  for( i=0 ; i<len ; i++) {
        if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
          dml.elements[i].checked=true;
        }
      }
	}
    document.leaks.submit1.value="CEASE2";
	document.leaks.submit();
  }
  
  if(x=="excel"){
    var form='leaks'; //Give the form name here
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var anycheck = 0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
        if(dml.elements[i].checked==true) anycheck = 1;
      }
    }

	if(anycheck==0){ 
	  for( i=0 ; i<len ; i++) {
        if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
          dml.elements[i].checked=true;
        }
      }
	}
    document.leaks.submit1.value="excel";
	document.leaks.submit();
  }
  
  if(x=="excel2"){
    var form='leaks'; //Give the form name here
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var anycheck = 0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
        if(dml.elements[i].checked==true) anycheck = 1;
      }
    }

	if(anycheck==0){ 
	  for( i=0 ; i<len ; i++) {
        if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
          dml.elements[i].checked=true;
        }
      }
	}
    document.leaks.submit1.value="excel2";
	document.leaks.submit();
  }
  
  if(x=="excel2csv"){
    var form='leaks'; //Give the form name here
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var anycheck = 0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
        if(dml.elements[i].checked==true) anycheck = 1;
      }
    }

	if(anycheck==0){ 
	  for( i=0 ; i<len ; i++) {
        if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
          dml.elements[i].checked=true;
        }
      }
	}
    document.leaks.submit1.value="excel2csv";
	document.leaks.submit();
  }
  
  if(x=="timberline"){
    var form='leaks'; //Give the form name here
    dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var anycheck = 0;
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
        if(dml.elements[i].checked==true) anycheck = 1;
      }
    }

	if(anycheck==0){ 
	  for( i=0 ; i<len ; i++) {
        if (dml.elements[i].name=="leak_ids[]" && dml.elements[i].disabled==false) {
          dml.elements[i].checked=true;
        }
      }
	}
    document.leaks.submit1.value="timberline";
	document.leaks.submit();
  }
}

function go_filterform(x){
  
  if(x==0){
    var start_record = 0;
  }
  else {
    var start_record = document.filterform.start_record.value;
  }
  var datefilter = document.filterform.datefilter.value;
  var startdate = document.filterform.startdate.value;
  
  var form="filterform";
  
  dml=document.forms[form];
    len = dml.elements.length;
    var i=0;
	var group_filter="";
	var subgroup_filter="";
	
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name=="group_filter[]" && dml.elements[i].checked==true) {
	    group_filter = group_filter + "," + dml.elements[i].value;
	  }
	  if (dml.elements[i].name=="subgroup_filter[]" && dml.elements[i].checked==true) {
	    subgroup_filter = subgroup_filter + "," + dml.elements[i].value;
	  }
	}
	
	//alert(statusfilter);
	
  var statusfilter = document.filterform.statusfilter.value;
  var enddate = document.filterform.enddate.value;
  //var typefilter = document.filterform.typefilter.value;
  var typefilter="All";
  var servicemanfilter = document.filterform.servicemanfilter.value;
  var invoicetypefilter = document.filterform.invoicetypefilter.value;
  //var account_manager_filter = document.filterform.account_manager_filter.value;
  var priorityfilter = document.filterform.priorityfilter.value;
  var archivefilter = document.filterform.archivefilter.value;
  var zip = document.filterform.zip.value;
  var distance = document.filterform.distance.value;
  var extra_searchby = document.filterform.extra_searchby.value;
  var extra_searchfor = document.filterform.extra_searchfor.value;

  url = "fcs_sd_report_pop.php?datefilter=" + datefilter + "&startdate=" + startdate + "&enddate=" + enddate + "&statusfilter=" + statusfilter + "&typefilter=" + typefilter;
  url = url+"&servicemanfilter=" + servicemanfilter + "&invoicetypefilter=" + invoicetypefilter + "&priorityfilter=" + priorityfilter;
  url = url + "&archivefilter=" + archivefilter + "&zip=" + zip + "&distance=" + distance;
  url = url + "&extra_searchby=" + extra_searchby + "&extra_searchfor=" + extra_searchfor;
  url = url + "&order_by=<?=$order_by?>&order_by2=<?=$order_by2?>";
  url = url + "&group_filter=" + group_filter + "&subgroup_filter=" + subgroup_filter;
  url = url + "&start_record=" + start_record;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
		//document.getElementById('debug_act').style.display="";
		//document.getElementById('debug_act').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);  
}  

function next_prev(x){
  var start_record = parseInt(document.filterform.start_record.value);
  if(x==1){
    start_record = start_record + <?=$SD_RESULTS_PER_PAGE?>;
  }
  else {
    start_record = start_record - <?=$SD_RESULTS_PER_PAGE?>;
  }
  document.filterform.start_record.value = start_record;
  go_filterform('1');
}
    
function OpenClose(group, x){
  if(x==1){
    document.getElementById('group_' + group).style.display="";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById('group_' + group).style.display="none";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
  }
}

function OpenClose_Main(group, x){
  if(x==1){
    document.getElementById(group + '_list').style.display="";
	document.getElementById(group + '_list_arrow').innerHTML = "<a href=\"javascript:OpenClose_Main('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById(group + '_list').style.display="none";
	document.getElementById(group + '_list_arrow').innerHTML = "<a href=\"javascript:OpenClose_Main('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
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


$sql = "SELECT count(*) from groups where master_id='" . $SESSION_MASTER_ID . "'";
$group_test = getsingleresult($sql);

if($group_test){
  $sql = "SELECT priority1, priority2, priority3, emergency_time_frame, urgent_time_frame, scheduled_time_frame, id from groups where master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $xid = $record['id'];
    $priority[$xid][1] = stripslashes($record['priority1']);
    $priority[$xid][2] = stripslashes($record['priority2']);
    $priority[$xid][3] = stripslashes($record['priority3']);
  }
}

$sql = "SELECT priority1, priority2, priority3, emergency_time_frame, urgent_time_frame, scheduled_time_frame from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$priority[0][1] = stripslashes($record['priority1']);
$priority[0][2] = stripslashes($record['priority2']);
$priority[0][3] = stripslashes($record['priority3']);


?>
<div id="debug_act" style="display:none;"></div>
<div class="main" style="font-weight:bold;"><?=$MAIN_CO_NAME?> Service Dispatch Report</div>
<div class="main">
<form name="filterform">
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
<!--<option value="New Project"<?php if($statusfilter=="New Project") echo " selected";?>>New Project</option>-->
<option value="Acknowledged"<?php if($statusfilter=="Acknowledged") echo " selected";?>>Acknowledged</option>
<option value="Hold"<?php if($statusfilter=="Hold") echo " selected";?>>Hold</option>
<option value="Arrival ETA"<?php if($statusfilter=="Arrival ETA") echo " selected";?>>Arrival ETA</option>
<option value="In Progress"<?php if($statusfilter=="In Progress") echo " selected";?>>In Progress</option>
<option value="Resolved"<?php if($statusfilter=="Resolved") echo " selected";?>>Resolved</option>
<option value="Confirmed"<?php if($statusfilter=="Confirmed") echo " selected";?>>Confirmed</option>
<option value="Invoiced"<?php if($statusfilter=="Invoiced") echo " selected";?>>Invoiced</option>
<option value="Closed Out"<?php if($statusfilter=="Closed Out") echo " selected";?>>Closed Out</option>
</select>

</div>

<?php /*
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
*/?>
<div style="float:left; padding-right:10px;">
Assigned to:
<select name="servicemanfilter">
<option value="All">All</option>


<?php

$groups_filter = " 1=1 ";
if($SESSION_USE_GROUPS){
  if($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != "") $groups_filter = " user_id in(" . $SESSION_GROUP_ALLOWED_USERS . ") ";
}

	$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where (servicemen=1 and resource=0) and enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $groups_filter order by lastname";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=$record['user_id']?>"<?php if($servicemanfilter==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
	  <?php
	}
	?>
	<?php
	$sql = "SELECT prospect_id, company_name from prospects  where
	resource=1 and display=1 and master_id='" . $SESSION_MASTER_ID . "' order by company_name";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="*<?=$record['prospect_id']?>"<?php if($servicemanfilter== "*" . $record['prospect_id']) echo " selected";?>><?=stripslashes($record['company_name'])?></option>
	  <?php
	}
	?>
	</select>
</select>
</div>


<div style="float:left; padding-right:10px;">
Invoice:
<select name="invoicetypefilter">
<option value="All">All</option>
<option value="Billable - TM"<?php if($invoicetypefilter=="Billable - TM") echo " selected";?>>T&amp;M</option>
  <option value="Billable - Contract"<?php if($invoicetypefilter=="Billable - Contract") echo " selected";?>>Contract</option>
  <option value="2 Year"<?php if($invoicetypefilter=="2 Year") echo " selected";?>>Non-Billable Warranty</option>
  <option value="Warranty"<?php if($invoicetypefilter=="Warranty") echo " selected";?>>Warranty</option>
  <option value="RTM"<?php if($invoicetypefilter=="RTM") echo " selected";?>>RTM</option>
</select>
</div>

<div style="float:left; padding-right:10px;">
Priority:
<select name="priorityfilter">
<option value="0">All</option>
<?php for($x=0;$x<sizeof($priority[0]);$x++){
   $y = $x+1;?>
  <option value="<?=$y?>"<?php if($priorityfilter==$y) echo " selected";?>><?=$priority[0][$y]?></option>
<?php } ?>
</select>
</div>


<div style="float:left; padding-right:10px;">
<select name="archivefilter">
<option value="0">Active</option>
<option value="1"<?php if($archivefilter=="1") echo " selected";?>>Archived</option>
</select>
</div>

<div style="float:left; padding-right:10px;">
Zip:
<input type="text" name="zip" value="<?=$zip?>" size="5">
Radius:
<select name="distance">
<option value="10"<?php if($distance=="10") echo " selected";?>>10 mi</option>
<option value="25"<?php if($distance=="25") echo " selected";?>>25 mi</option>
<option value="50"<?php if($distance=="50") echo " selected";?>>50 mi</option>
<option value="100"<?php if($distance=="100") echo " selected";?>>100 mi</option>
<option value="200"<?php if($distance=="200") echo " selected";?>>200 mi</option>
</select>
</div>

<?php 

if($SESSION_USE_GROUPS){ ?>
<div style="float:left; padding-right:10px;" class="main">
Groups:
<span id="group_list_arrow"><a href="javascript:OpenClose_Main('group', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
<div id="group_list" style="display:none;">
<?php
$sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of=0 order by group_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $group_id = $record['id'];
  if(is_array($groups)){
    if(in_array($group_id, $groups)){
      ?>
      <input type="checkbox" name="group_filter[]" value="<?=$group_id?>"<?php if(in_array($group_id, $group_filter)) echo " checked";?>> 
	  <?php
	}
  }
  else {
    ?>
	<input type="checkbox" name="group_filter[]" value="<?=$group_id?>"<?php if(in_array($group_id, $group_filter)) echo " checked";?>> 
	<?php
  }
  ?>
  <span id="group_<?=$group_id?>_arrow" style="display:none;"><a href="javascript:OpenClose('<?=$group_id?>', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
  <?=stripslashes($record['group_name'])?>
  <br>
  <div id="group_<?=$group_id?>" style="padding-left:20px; display:none;">
  <?php
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id' order by group_name";
  $result_sub = executequery($sql);
  if(mysql_num_rows($result_sub)) $SHOWARROW[] = $group_id;
  while($record_sub = go_fetch_array($result_sub)){
    $subgroup_id = $record_sub['id'];
	if(is_array($subgroups)){
	  if(in_array($subgroup_id, $subgroups)){
	    ?>
	    <input type="checkbox" name="subgroup_filter[]" value="<?=$subgroup_id?>"<?php if(in_array($subgroup_id, $subgroup_filter)) echo " checked";?>> <?=stripslashes($record_sub['group_name'])?><br>
		<?php
	  }
	}
	else {
	  ?>
      <input type="checkbox" name="subgroup_filter[]" value="<?=$subgroup_id?>"<?php if(in_array($subgroup_id, $subgroup_filter)) echo " checked";?>> <?=stripslashes($record_sub['group_name'])?><br>
	  <?php
	}
  }
  ?>
  </div>
  <?php
}
?>
</div>
</div>
<?php } // end if using groups 

?>

<div style="float:left; padding-right:10px;">
<input type="button" name="buttonfilter" value="Filter" onclick="go_filterform('1')">
&nbsp;
<input type="button" name="buttonclearfilter" value="Clear Filters" onclick="document.location.href='fcs_sd_report_clearfilter.php'">
</div>

<div style="float:right; padding-right:10px;">
<a href="<?=$_SERVER['SCRIPT_NAME']?>">refresh</a>
</div>

<div style="float:right; padding-right:10px;">
<?php if($SESSION_CAN_EXPORT){?>
<a href="javascript:goform('excel')">excel</a> &nbsp;
<?php
$sql = "SELECT xml_sd_export from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$xml_sd_export = getsingleresult($sql);
if($xml_sd_export=="ComputerEase"){
  ?>
  <br>
  <a href="javascript:goform('xml')">Export selected to XML</a>
  <?php
}
if($xml_sd_export=="ComputerEase2"){
  ?>
  <br>
  <a href="javascript:goform('cease2')">Export selected to XML</a>
  <?php
}
if($xml_sd_export=="Excel 2"){
  ?>
  <br>
  excel 2 <a href="javascript:goform('excel2')">(xls)</a> <a href="javascript:goform('excel2csv')">(csv)</a> &nbsp;
  <?php
}
if($xml_sd_export=="Timberline"){
  ?>
  <br>
  <a href="javascript:goform('timberline')">Export selected to Timberline</a>
  <?php
}
?>

<?php } ?>
</div>



</div>
<div style="clear:both;"></div>
Search:
<select name="extra_searchby">
<option value="invoice_id"<?php if($extra_searchby=="invoice_id") echo " selected";?>>Invoice ID</option>
<option value="company"<?php if($extra_searchby=="company") echo " selected";?>>Company</option>
<option value="property"<?php if($extra_searchby=="property") echo " selected";?>>Property</option>
</select>
<input type="text" name="extra_searchfor" value="<?=$extra_searchfor?>" size="15" onkeyup="go_filterform('0')">
<input type="hidden" name="start_record" value="0">
</form>
</div>
<!-- <?=$force_invoicedate?> VVV-->
<?php
if($force_invoicedate==1){
  $invoice_header = "Invoice Date";
}
else {
  $invoice_header = "Status Changed";
}
?>
<div align="right" id="navigation" style="display:none;" class="main"></div>

<form action="fcs_sd_report_action.php" method="post" name="leaks">
<div id="report_results">

</div>
<?php if($SESSION_ISADMIN){ ?>
<input type="hidden" name="submit1" value="">
<input type="button" name="button1" value="Archive Selected" onclick="goform('archive')"> &nbsp;
<?php if($SESSION_GROUPS=="" && $SESSION_SUBGROUPS==""){?>
  <input type="button" name="button2" value="Delete Selected" onclick="goform('delete')">
<?php } ?>
<?php } ?>

</form>
<script>go_filterform('0');</script>
<script>
<?php
for($x=0;$x<sizeof($SHOWARROW);$x++){
  if($SHOWARROW[$x]=="") continue;
  ?>
  document.getElementById('group_<?=$SHOWARROW[$x]?>_arrow').style.display="";
  <?php
}
?>
</script>
<?php include "includes/footer.php"; ?>
