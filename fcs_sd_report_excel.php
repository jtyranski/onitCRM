<?php 
include "includes/functions.php";



$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users order by user_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x = $record['user_id'];
  $SERVICEMAN[$x] = stripslashes($record['fullname']);
}

$sql = "SELECT custom_sd_field from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$custom_sd_field = stripslashes(getsingleresult($sql));

$counter = 0;

for($x=0;$x<sizeof($_SESSION['sess_xml_leak_id']);$x++){
  $leak_id = $_SESSION['sess_xml_leak_id'][$x];
  
$sql = "SELECT a.leak_id, a.status, b.site_name, b.city, b.state, a.admin_resolve, a.admin_invoice, a.invoice_total, a.invoice_type, 
b.property_id, a.section_type, b.division_id, a.section_id, a.additional, a.section_id, d.prospect_id, d.company_name, a.cont_id, a.fix_contractor, 
a.servicemen_id, a.invoice_id, a.custom_field, date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date
from am_leakcheck a, properties b, prospects d
where a.property_id=b.property_id and a.prospect_id=d.prospect_id and a.display=1 and a.demo=0 
and d.master_id='" . $SESSION_MASTER_ID . "' 
and a.leak_id='$leak_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){

  $additional = $record['additional'];
  $prospect_id = stripslashes($record['prospect_id']);
  $property_id = stripslashes($record['property_id']);
  $section_type = stripslashes($record['section_type']);
  $status = stripslashes($record['status']);
  $leak_id = stripslashes($record['leak_id']);
  $invoice_id = stripslashes($record['invoice_id']);
  $custom_field = stripslashes($record['custom_field']);
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
  $invoice_sent_date = stripslashes($record['invoice_sent_date']);
  if($invoice_sent_date=="00/00/0000") $invoice_sent_date = "";
  
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
  $row[$counter]['invoice_sent_date'] = $invoice_sent_date;
  $row[$counter]['invoice_id'] = $invoice_id;
  $row[$counter]['custom_field'] = $custom_field;
  
  $counter++;
}
} // end for loop
$_SESSION['sess_xml_leak_id'] = "";

require_once "excel/class.writeexcel_workbook.inc.php";
require_once "excel/class.writeexcel_worksheet.inc.php";

$fname = tempnam("/tmp", "simple.xls");
$workbook = &new writeexcel_workbook($fname);
$worksheet = &$workbook->addworksheet();

$worksheet->write(0, 0,  "Dispatch ID");
$worksheet->write(0, 1,  "Invoice ID");
$worksheet->write(0, 2,  "Company");
$worksheet->write(0, 3,  "Site");
$worksheet->write(0, 4,  "City");
$worksheet->write(0, 5,  "State");
$worksheet->write(0, 6,  "Section");
$worksheet->write(0, 7,  "Dispatched");
$worksheet->write(0, 8,  "Invoice Date");
$worksheet->write(0, 9,  "Status");
$worksheet->write(0, 10,  "Invoice");
if($custom_sd_field != "") $worksheet->write(0, 11, $custom_sd_field);

for($x=0;$x<sizeof($row);$x++){
  $counter = $x+1;
  $worksheet->write($counter, 0,  stripslashes($row[$x]['leak_id']));
  $worksheet->write($counter, 1,  stripslashes($row[$x]['invoice_id']));
  $worksheet->write($counter, 2,  stripslashes($row[$x]['company_name']));
  $worksheet->write($counter, 3,  stripslashes($row[$x]['site_name']));
  $worksheet->write($counter, 4,  stripslashes($row[$x]['city']));
  $worksheet->write($counter, 5,  stripslashes($row[$x]['state']));
  $worksheet->write($counter, 6,  stripslashes($row[$x]['section_name']));
  $worksheet->write($counter, 7,  stripslashes($row[$x]['dispatch']));
  $worksheet->write($counter, 8,  stripslashes($row[$x]['invoice_sent_date']));
  $worksheet->write($counter, 9,  stripslashes($row[$x]['status']));
  $worksheet->write($counter, 10,  stripslashes($row[$x]['invoice_total']));
  if($custom_sd_field != "") $worksheet->write($counter, 11,  stripslashes($row[$x]['custom_field']));
}
  
$workbook->close();

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
header("Content-Type: application/x-msexcel; name=\"fcs_sd_report.xls\"");
header("Content-Disposition: inline; filename=\"fcs_sd_report.xls\"");


$fh=fopen($fname, "rb");
fpassthru($fh);
unlink($fname);
?>