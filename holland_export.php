<?php
include "includes/functions.php";
if($SESSION_MASTER_ID != 1870){
  echo "You must be logged in as Holland Roofing to access this page.";
  exit;
}

//*************************************************************** TIMBERLINE **********************************************************************
function export_timberline($leak_ids, $master_id){

$sql = "SELECT company_code, acct_rec_code, general_ledger_acct, sales_tax_acct from master_list where master_id='" . $master_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master['company_code'] = stripslashes($record['company_code']);
$master['acct_rec_code'] = stripslashes($record['acct_rec_code']);
$master['general_ledger_acct'] = stripslashes($record['general_ledger_acct']);
$master['sales_tax_acct'] = stripslashes($record['sales_tax_acct']);


$line = "";
if(is_array($leak_ids)){
  for($x=0;$x<sizeof($leak_ids);$x++){
    $sql = "SELECT a.invoice_id, a.custom_field, a.custom_field2, date_format(a.invoice_sent_date, \"%m%d%Y\") as invoice_sent_date, 
	date_format(a.invoice_due_date, \"%m%d%Y\") as invoice_due_date, a.status, a.invoice_type, a.invoice_total, a.section_id, b.cust_id, c.site_name, 
	c.city, c.state, a.travel_time, a.travel_rate, a.labor_time, a.labor_rate, a.desc_work_performed, a.property_id, a.tax_amount, a.tax_percent, a.general_ledger_acct 
	from am_leakcheck a, prospects b, properties c where
	a.prospect_id=b.prospect_id and
	a.property_id=c.property_id and
	a.leak_id='" . $leak_ids[$x] . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$invoice_id = stripslashes($record['invoice_id']);
	$property_id = stripslashes($record['property_id']);
	$custom_field = stripslashes($record['custom_field']);
	$custom_field2 = stripslashes($record['custom_field2']);
	$invoice_sent_date = stripslashes($record['invoice_sent_date']);
	$invoice_due_date = stripslashes($record['invoice_due_date']);
	$status = stripslashes($record['status']);
	$invoice_type = stripslashes($record['invoice_type']);
	$invoice_total = stripslashes($record['invoice_total']);
	$section_id = stripslashes($record['section_id']);
	$cust_id = stripslashes($record['cust_id']);
	$site_name = stripslashes($record['site_name']);
	$city = stripslashes($record['city']);
	$state = stripslashes($record['state']);
	$travel_time = stripslashes($record['travel_time']);
	$travel_rate = stripslashes($record['travel_rate']);
	$labor_time = stripslashes($record['labor_time']);
	$labor_rate = stripslashes($record['labor_rate']);
	$tax_percent = stripslashes($record['tax_percent']);
	
	$tax_percent = $tax_percent / 100;
	
	$travel_time = number_format($travel_time, 2);
    $travel_cost = $travel_rate * $travel_time;
	$labor_time = number_format($labor_time, 2);
    $labor_cost = $labor_rate * $labor_time;
	$total_labor = $travel_cost + $labor_cost;
	
	// get total of other line items (hrs only)
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_othercost where leak_id='" . $leak_ids[$x] . "' AND units = 'Hrs'";
	$other_hrs = getsingleresult($sql);
	$total_labor = $total_labor + $other_hrs;
	
	$desc_work_performed = stripslashes($record['desc_work_performed']);
	$desc_work_performed = go_reg_replace("\"", "", $desc_work_performed);
	$desc_work_performed = go_reg_replace("\n", " ", $desc_work_performed);
	$desc_work_performed = go_reg_replace("\r", " ", $desc_work_performed);
	$desc_work_performed = substr($desc_work_performed, 0, 29);
	
	$company_code = $master['company_code'];
	$acct_rec_code = $master['acct_rec_code'];
	$general_ledger_acct = stripslashes($record['general_ledger_acct']);
	$sales_tax_acct = $master['sales_tax_acct'];

	if($invoice_due_date=="00000000") { $invoice_due_date = $invoice_sent_date; } // if there is no invoice due date, then use the sent date
	
	$sql = "SELECT groups, subgroups from properties where property_id='$property_id'";
	$res = executequery($sql);
	$rec = go_fetch_array($res);
	$groups = go_reg_replace("\,", "", $rec['groups']);
	$subgroups = go_reg_replace("\,", "", $rec['subgroups']);
	
	$USEGROUP = 0;
    if($groups != "" && $groups != 0) $USEGROUP = $groups;
    if($subgroups != "" && $subgroups !=0) $USEGROUP = $subgroups;

    if($USEGROUP !=0 && $USEGROUP != ""){
      $sql = "SELECT company_code, acct_rec_code, general_ledger_acct, sales_tax_acct from groups where id='" . $USEGROUP . "'";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $company_code = stripslashes($record['company_code']);
      $acct_rec_code = stripslashes($record['acct_rec_code']);
      $general_ledger_acct = stripslashes($record['general_ledger_acct']);
	  $sales_tax_acct = stripslashes($record['sales_tax_acct']);
	  
	  if($company_code=="") $company_code = $master['company_code'];
	  if($acct_rec_code=="") $acct_rec_code = $master['acct_rec_code'];
	  if($general_ledger_acct=="") $general_ledger_acct = $master['general_ledger_acct'];
	  if($sales_tax_acct=="") $sales_tax_acct = $master['sales_tax_acct'];
	  
    }
	
	
	
    $line .= "I," . $cust_id . ",,,";
	$line .= $company_code . "-" . $invoice_id . ",";
	$line .= $invoice_sent_date . ",";
	$line .= $invoice_total . ",,,";
	$line .= "\"" . $desc_work_performed . "\",";
	$line .= $invoice_due_date . "\r\n";
	
    if($invoice_type<>"Billable - Contract") {
	if($total_labor){
	  $line .= "D,Labor,,,,,,,,,";
	  $line .= number_format($total_labor, 2) . ",,";
	  $line .= $invoice_sent_date . ",";
	  $line .= $company_code . "-" . $acct_rec_code . ",";
	  $line .= $company_code . "-" . $general_ledger_acct . ",\r\n";
	}
	
	// get total of materials
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_materials where leak_id='" . $leak_ids[$x] . "'";
	$mat_total = getsingleresult($sql);
	
	// get total of taxable materials
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_materials where leak_id='" . $leak_ids[$x] . "' and taxable=1";
	$mat_total_taxable = getsingleresult($sql);
	$mat_total_tax = $mat_total_taxable * $tax_percent;
	
	// get total of other line items (not unit of hrs)
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_othercost where leak_id='" . $leak_ids[$x] . "'";
	$other_total = getsingleresult($sql);
	
	// get total of other taxable line items (not unit of hrs)
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_othercost where leak_id='" . $leak_ids[$x] . "' and taxable=1";
	$other_total_taxable = getsingleresult($sql);
	$other_total_tax = $other_total_taxable * $tax_percent;
	
	$mat_other_total = $mat_total + $other_total;
	$mat_other_total_tax = $mat_total_tax + $other_total_tax;
	
	if($mat_other_total){
	  $line .= "D,Material,,,,,,,,,";
	  $line .= number_format($mat_other_total, 2) . ",,";
	  $line .= $invoice_sent_date . ",";
	  $line .= $company_code . "-" . $acct_rec_code . ",";
	  $line .= $company_code . "-" . $general_ledger_acct . ",\r\n";
	  
	  if($mat_other_total_tax <> 0) {  // don't print this line if material tax is zero
	    $line .= "D,Material,,,,,,,,,";
	    $line .= number_format($mat_other_total_tax, 2) . ",,";
	    $line .= $invoice_sent_date . ",";
	    $line .= $company_code . "-" . $acct_rec_code . ",";
	    $line .= $company_code . "-" . $sales_tax_acct . ",\r\n";
	  }
	}
    }
	
  }
}

return($line);
} // end of timberline function


//**********************************************************************  STANDARD ****************************************************************
function export_standard($leak_ids, $master_id){

  $sql = "SELECT cron_sd_type from master_list where master_id='$master_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $cron_sd_type = $record['cron_sd_type'];
  $row = array();
  $counter = 0;
  for($x=0;$x<sizeof($leak_ids);$x++){
$sql = "SELECT a.leak_id, a.status, b.site_name, b.city, b.state, a.admin_resolve, a.admin_invoice, a.invoice_total, a.invoice_type, 
b.property_id, a.section_type, b.division_id, a.section_id, a.additional, a.section_id, d.prospect_id, d.company_name, a.cont_id, a.fix_contractor, 
a.servicemen_id, a.invoice_id, a.custom_field, date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date
from am_leakcheck a, properties b, prospects d
where a.property_id=b.property_id and a.prospect_id=d.prospect_id and a.display=1 and a.demo=0 and a.archive=0
and a.leak_id='" . $leak_ids[$x] . "'";

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


  
  if($status == "Dispatched") $status_rank = 1;
  if($status == "Acknowledged") $status_rank = 2;
  if($status == "Arrival ETA") $status_rank = 3;
  if($status == "In Progress") $status_rank = 4;
  if($status == "Resolved") $status_rank = 5;
  if($status == "Confirmed") $status_rank = 6;
  if($status == "Invoiced") $status_rank = 7;
  if($status == "Closed Out") $status_rank = 8;
  

  
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
}

$line = "";

if($cron_sd_type=="csv"){
  $line .= "\"Dispatch ID\",\"Invoice ID\",\"Company\",\"Site\",\"City\",\"State\",\"Section\",\"Dispatched\",\"Invoice Date\",\"Status\",\"Invoice\"";
  if($custom_sd_field != "") $line .= ",\"" . $custom_sd_field . "\"";
  $line .= "\n";
  for($x=0;$x<sizeof($row);$x++){
    $line .= "\"" . $row[$x]['leak_id'] . "\",";
	$line .= "\"" . $row[$x]['invoice_id'] . "\",";
	$line .= "\"" . $row[$x]['company_name'] . "\",";
	$line .= "\"" . $row[$x]['site_name'] . "\",";
	$line .= "\"" . $row[$x]['city'] . "\",";
	$line .= "\"" . $row[$x]['state'] . "\",";
	$line .= "\"" . $row[$x]['section_name'] . "\",";
	$line .= "\"" . $row[$x]['dispatch'] . "\",";
	$line .= "\"" . $row[$x]['invoice_sent_date'] . "\",";
	$line .= "\"" . $row[$x]['status'] . "\",";
	$line .= "\"" . $row[$x]['invoice_total'] . "\",";
	if($custom_sd_field != "") $line .= "\"" . $row[$x]['custom_field'] . "\",";
	$line = go_reg_replace("\,$", "\n", $line);
  }
}

if($cron_sd_type=="xml"){
  $custom_sd_field = go_reg_replace(" ", "", $custom_sd_field);
  $custom_sd_field = go_reg_replace("\#", "num", $custom_sd_field);
  $custom_sd_field = go_reg_replace("\&", "and", $custom_sd_field);
  
  $s = "\n\r\n";
  $line = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$s<import type=\"batchinvoices\">$s";
  for($x=0;$x<sizeof($row);$x++){
    $line .= "\t<invoice>$s";
    $line .= "\t\t<dispatchid>" . $row[$x]['leak_id'] . "</dispatchid>$s";
    $line .= "\t\t<invoiceid>" . $row[$x]['invoice_id'] . "</invoiceid>$s";
    $line .= "\t\t<company>" . $row[$x]['company_name'] . "</company>$s";
    $line .= "\t\t<site>" . $row[$x]['site_name'] . "</site>$s";
    $line .= "\t\t<city>" . $row[$x]['city'] . "</city>$s";
    $line .= "\t\t<state>" . $row[$x]['state'] . "</state>$s";
    $line .= "\t\t<section>" . $row[$x]['section_name'] . "</section>$s";
    $line .= "\t\t<dispatched>" . $row[$x]['dispatch'] . "</dispatched>$s";
    $line .= "\t\t<invoicedate>" . $row[$x]['invoice_sent_date'] . "</invoicedate>$s";
    $line .= "\t\t<status>" . $row[$x]['status'] . "</status>$s";
    $line .= "\t\t<invoicetotal>" . $row[$x]['invoice_total'] . "</invoicetotal>$s";
    if($custom_sd_field != "") $line .= "\t\t<" . $custom_sd_field . ">" . $row[$x]['custom_field'] . "</" . $custom_sd_field . ">$s";
    $line .= "\t</invoice>$s";
  }
  $line .= "</import>";
}  

return($line);
} // end of standard export



//*************************************************************************  COMPUTER EASE 2 ******************************************************
function export_cease2($leak_ids, $master_id){
$s = "\n";
$line = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$s\r\n<import type=\"batchinvoices\">$s\r\n";

$sql = "SELECT ar_account, sales_account from master_list where master_id='" . $master_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ar_account = stripslashes($record['ar_account']);
$sales_account = stripslashes($record['sales_account']);

if(is_array($leak_ids)){
  for($x=0;$x<sizeof($leak_ids);$x++){
    $sql = "SELECT a.invoice_id, a.custom_field, a.custom_field2, date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date, 
	date_format(a.invoice_due_date, \"%m/%d/%Y\") as invoice_due_date, a.status, a.invoice_total, a.section_id, b.cust_id, c.site_name, 
	c.city, c.state
	from am_leakcheck a, prospects b, properties c where
	a.prospect_id=b.prospect_id and
	a.property_id=c.property_id and
	a.leak_id='" . $leak_ids[$x] . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$invoice_id = stripslashes($record['invoice_id']);
	$custom_field = stripslashes($record['custom_field']);
	$custom_field2 = stripslashes($record['custom_field2']);
	$invoice_sent_date = stripslashes($record['invoice_sent_date']);
	$invoice_due_date = stripslashes($record['invoice_due_date']);
	$status = stripslashes($record['status']);
	$invoice_total = stripslashes($record['invoice_total']);
	$section_id = stripslashes($record['section_id']);
	$cust_id = stripslashes($record['cust_id']);
	$site_name = stripslashes($record['site_name']);
	$city = stripslashes($record['city']);
	$state = stripslashes($record['state']);
	
	if($section_id != 0){
      $sql = "SELECT section_name from sections where section_id='$section_id'";
      $section_name = stripslashes(getsingleresult($sql));
    }
    else {
      $section_name = "Unknown";
    }
	
	$line .= "\t<invoice>$s\r\n";
	$line .= "\t\t<customerid>$cust_id</customerid>$s\r\n";
	$line .= "\t\t<jobid>$custom_field2</jobid>$s\r\n";
	$line .= "\t\t<invoiceid>$invoice_id</invoiceid>$s\r\n";
	$line .= "\t\t<duedate>$invoice_due_date</duedate>$s\r\n";
	$line .= "\t\t<invoicedate>$invoice_sent_date</invoicedate>$s\r\n";
	$line .= "\t\t<nontaxablesalesamount>$invoice_total</nontaxablesalesamount>$s\r\n";
	$line .= "\t\t<retentionamount>0.00</retentionamount>$s\r\n";
	$line .= "\t\t<araccount>$ar_account</araccount>$s\r\n";
	$line .= "\t\t<salesaccount>$sales_account</salesaccount>$s\r\n";
	$line .= "\t\t<salesrepid></salesrepid>$s\r\n";
	$line .= "\t\t<notes></notes>$s\r\n";
	$line .= "\t\t<invoicesource></invoicesource>$s\r\n";

	$line .= "\t</invoice>$s\r\n";
  }
}
$line .= "\t<type>batchinvoices</type>$s\r\n";
$line .= "</import>";

return($line);
} // end cease 2

//***********************************************************************************  COMPUTEREASE ***************************************
function export_computerease($leak_ids, $master_id){
$s = "\n";
$line = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$s\r\n<import type=\"freeform\">$s\r\n";

if(is_array($leak_ids)){
  for($x=0;$x<sizeof($leak_ids);$x++){
    $sql = "SELECT a.invoice_id, a.custom_field, a.custom_field2, date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date, 
	date_format(a.invoice_due_date, \"%m/%d/%Y\") as invoice_due_date, a.status, a.invoice_total, a.section_id, b.cust_id, c.site_name, 
	c.city, c.state
	from am_leakcheck a, prospects b, properties c where
	a.prospect_id=b.prospect_id and
	a.property_id=c.property_id and
	a.leak_id='" . $leak_ids[$x] . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$invoice_id = stripslashes($record['invoice_id']);
	$custom_field = stripslashes($record['custom_field']);
	$custom_field2 = stripslashes($record['custom_field2']);
	$invoice_sent_date = stripslashes($record['invoice_sent_date']);
	$invoice_due_date = stripslashes($record['invoice_due_date']);
	$status = stripslashes($record['status']);
	$invoice_total = stripslashes($record['invoice_total']);
	$section_id = stripslashes($record['section_id']);
	$cust_id = stripslashes($record['cust_id']);
	$site_name = stripslashes($record['site_name']);
	$city = stripslashes($record['city']);
	$state = stripslashes($record['state']);
	
	if($section_id != 0){
      $sql = "SELECT section_name from sections where section_id='$section_id'";
      $section_name = stripslashes(getsingleresult($sql));
    }
    else {
      $section_name = "Unknown";
    }
	
	$line .= "\t<invoice>$s\r\n";
	$line .= "\t\t<cusnum>$cust_id</cusnum>$s\r\n";
	$line .= "\t\t<invnum>$invoice_id</invnum>$s\r\n";
	$line .= "\t\t<deptnum>AJ</deptnum>$s\r\n";
	$line .= "\t\t<jobnum>$custom_field2</jobnum>$s\r\n";
	$line .= "\t\t<notes>Dispatch ID: " . $leak_ids[$x] . " Site: $site_name City: $city State: $state Section $section_name Status: $status</notes>$s\r\n";
	$line .= "\t\t<taxnum/>$s\r\n";
	$line .= "\t\t<invdate>$invoice_sent_date</invdate>$s\r\n";
	$line .= "\t\t<duedate>$invoice_due_date</duedate>$s\r\n";
	$line .= "\t\t<terms/>$s\r\n";
	$line .= "\t\t<discdate/>$s\r\n";
	$line .= "\t\t<discpcnt/>$s\r\n";
	$line .= "\t\t<retamt/>$s\r\n";
	$line .= "\t\t<retpcnt/>$s\r\n";
	$line .= "\t\t<freightamt/>$s\r\n";
	$line .= "\t\t<freighttype>none</freighttype>$s\r\n";
	$line .= "\t\t<pricecode>1</pricecode>$s\r\n";
	$line .= "\t\t<salesacct>7500.00</salesacct>$s\r\n";
	$line .= "\t\t<ponum>$custom_field</ponum>$s\r\n";
	$line .= "\t\t<shipvia/>$s\r\n";
	$line .= "\t\t<shipdate/>$s\r\n";
	$line .= "\t\t<blurb/>$s\r\n";
	$line .= "\t\t<depositamt/>$s\r\n";
	$line .= "\t\t<description>Imported from $MAIN_CO_NAME</description>$s\r\n";
	$line .= "\t\t<source/>$s\r\n";
	$line .= "\t\t<sourceid/>$s\r\n";
	$line .= "\t\t<items>$s\r\n";
	$line .= "\t\t<item>$s\r\n";
	$line .= "\t\t<itemnum/>$s\r\n";
	$line .= "\t\t<description>Imported Invoice Amount</description>$s\r\n";
	$line .= "\t\t<location>CENTRAL</location>$s\r\n";
	$line .= "\t\t<qty>1</qty>$s\r\n";
	$line .= "\t\t<unitprice>$invoice_total</unitprice>$s\r\n";
	$line .= "\t\t<unitcost/>$s\r\n";
	$line .= "\t\t<taxable/>$s\r\n";
	$line .= "\t\t<salesacct/>$s\r\n";
	$line .= "\t\t</item>$s\r\n";
	$line .= "\t\t</items>$s\r\n";
	$line .= "\t</invoice>$s\r\n";
  }
}

$line .= "</import>";

return($line);
} // end cease



//**********************************************************************  EXCEL 2 (in csv format) **********************************************
function export_excel2($leak_ids, $master_id){


$sql = "SELECT custom_sd_field from master_list where master_id='" . $master_id . "'";
$custom_sd_field = stripslashes(getsingleresult($sql));

$sql = "SELECT custom_sd_field2 from master_list where master_id='" . $master_id . "'";
$custom_sd_field2 = stripslashes(getsingleresult($sql));

$counter = 0;

for($x=0;$x<sizeof($leak_ids);$x++){
  $leak_id = $leak_ids[$x];
  
$sql = "SELECT a.leak_id, a.status, b.site_name, b.city, b.state, a.admin_resolve, a.admin_invoice, a.invoice_total, a.invoice_type, 
b.property_id, a.section_type, b.division_id, a.section_id, a.additional, a.section_id, d.prospect_id, d.company_name, a.cont_id, a.fix_contractor, 
a.servicemen_id, a.invoice_id, a.custom_field, d.cust_id, date_format(a.invoice_due_date, \"%m/%d/%Y\") as invoice_due_date, a.custom_field2, 
date_format(invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date
from am_leakcheck a, properties b, prospects d
where a.property_id=b.property_id and a.prospect_id=d.prospect_id and a.display=1 and a.demo=0 
and d.master_id='" . $master_id . "' 
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
  $custom_field2 = stripslashes($record['custom_field2']);
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
  $cust_id = stripslashes($record['cust_id']);
  $invoice_due_date = stripslashes($record['invoice_due_date']);
  $invoice_sent_date = stripslashes($record['invoice_sent_date']);
  if($invoice_sent_date=="00/00/0000") $invoice_sent_date = "";
  if($status!="Confirmed" && $status != "Invoiced" && $status != "Closed Out") $invoice_total = "";
  if($invoice_type=="") $invoice_total = "";
  

  
  if($status == "Dispatched") $status_rank = 1;
  if($status == "Acknowledged") $status_rank = 2;
  if($status == "Arrival ETA") $status_rank = 3;
  if($status == "In Progress") $status_rank = 4;
  if($status == "Resolved") $status_rank = 5;
  if($status == "Confirmed") $status_rank = 6;
  if($status == "Invoiced") $status_rank = 7;
  if($status == "Closed Out") $status_rank = 8;
  
  
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
  
  $invoice = "";
  
  $sql = "SELECT 
  date_format(date_add(a.dispatch_date, interval $timezone hour), \"%m/%d/%Y %r\") as dispatch, 
  date_format(date_add(a.fix_date, interval $timezone hour), \"%m/%d/%Y %r\") as resolved, 
  date_format(date_add(a.acknowledge_date, interval $timezone hour), \"%m/%d/%Y %r\") as acknowledge, 
  date_format(date_add(a.eta_date, interval $timezone hour), \"%m/%d/%Y %r\") as eta, 
  date_format(date_add(a.confirm_date, interval $timezone hour), \"%m/%d/%Y %r\") as confirm, 
  date_format(date_add(a.invoice_date, interval $timezone hour), \"%m/%d/%Y\") as invoice, 
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
  
  //if($record_times['invoice'] != "") $invoice = stripslashes($record_times['invoice']) . " " . $tz_display;
  $invoice = stripslashes($record_times['invoice']);
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
  $row[$counter]['contractor_prospect_id'] = $contractor_prospect_id;
  $row[$counter]['section_type'] = $section_type;
  $row[$counter]['invoice_total'] = $invoice_total;
  $row[$counter]['dispatch'] = $dispatch;
  $row[$counter]['invoice_id'] = $invoice_id;
  $row[$counter]['custom_field'] = $custom_field;
  $row[$counter]['custom_field2'] = $custom_field2;
  $row[$counter]['invoice_date'] = $invoice;
  $row[$counter]['invoice_due_date'] = $invoice_due_date;
  $row[$counter]['invoice_sent_date'] = $invoice_sent_date;
  $row[$counter]['cust_id'] = $cust_id;
  
  $counter++;
}
} // end for loop


$data = "Invoice Date,Invoice Month,Dispatch ID,Invoice ID,Company ID,Company,Site,City,State,Section,Due Date,Status,Invoice";
if($custom_sd_field != "") $data.= "," . $custom_sd_field;
if($custom_sd_field2 != "") $data.= "," . $custom_sd_field2;
$data .= "\n";

for($x=0;$x<sizeof($row);$x++){
  $data .= stripslashes($row[$x]['invoice_sent_date']) . ",";
  $data .= ",";
  $data .= stripslashes($row[$x]['leak_id']) . ",";
  $data .= stripslashes($row[$x]['invoice_id']) . ",";
  $data .= stripslashes($row[$x]['cust_id']) . ",";
  $data .= stripslashes($row[$x]['company_name']) . ",";
  $data .= stripslashes($row[$x]['site_name']) . ",";
  $data .= stripslashes($row[$x]['city']) . ",";
  $data .= stripslashes($row[$x]['state']) . ",";
  $data .= stripslashes($row[$x]['section_name']) . ",";
  $data .= stripslashes($row[$x]['invoice_due_date']) . ",";
  $data .= stripslashes($row[$x]['status']) . ",";
  $data .= stripslashes($row[$x]['invoice_total']);
  if($custom_sd_field != "") $data .= "," . stripslashes($row[$x]['custom_field']);
  if($custom_sd_field2 != "") $data .= "," . stripslashes($row[$x]['custom_field2']);
  $data .= "\n";
}

return($data);
} // end excel2




$sql = "SELECT master_id, cron_sd_filename, cron_sd_type, custom_sd_field, xml_sd_export, cron_sd_email, master_name from master_list where master_id=1870";
$result_main = executequery($sql);
while($record_main = go_fetch_array($result_main)){
  $master_id = stripslashes($record_main['master_id']);
  $cron_sd_filename = stripslashes($record_main['cron_sd_filename']);
  $cron_sd_type = stripslashes($record_main['cron_sd_type']);
  $xml_sd_export = stripslashes($record_main['xml_sd_export']);
  $cron_sd_email = stripslashes($record_main['cron_sd_email']);
  $master_name = stripslashes($record_main['master_name']);
  
  unset($leak_ids);
  $leak_ids = array();
  
  $sql = "SELECT a.leak_id from am_leakcheck a, prospects b
  where a.prospect_id=b.prospect_id and a.display=1 and a.demo=0 and a.archive=0 and b.master_id='$master_id' and a.include_cron_export=1 and (a.status='Invoiced' or a.status='Closed Out')";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $leak_ids[] = $record['leak_id'];
  }
  
  switch($xml_sd_export){
    case "none":{ // standard
	  $line = export_standard($leak_ids, $master_id);
	  break;
	}
	case "ComputerEase":{
	  $line = export_computerease($leak_ids, $master_id);
	  $cron_sd_type = "xml";
	  break;
	}
	case "ComputerEase2":{
	  $line = export_cease2($leak_ids, $master_id);
	  $cron_sd_type = "xml";
	  break;
	}
	case "Excel 2":{
	  $line = export_excel2($leak_ids, $master_id);
	  $cron_sd_type = "csv";
	  break;
	}
	case "Timberline":{
	  $line = export_timberline($leak_ids, $master_id);
	  $cron_sd_type = "txt";
	  break;
	}
  }
  

  if(sizeof($leak_ids) > 0){
    $file = "uploaded_files/download/" . $cron_sd_filename . "." . $cron_sd_type;
    $fp = fopen($file, 'w');
    fwrite($fp, $line);
    fclose($fp);
  
    if($cron_sd_email != ""){
      $subject = $master_name . " service dispatch export for " . date("m/d/Y");
	  $headers = "Content-type: text/html; charset=iso-8859-1\n";
      $headers .= "From: info@fcscontrol.com\n";
      $headers .= "Return-Path: info@fcscontrol.com";  // necessary for some emails such as aol
	  $message = "Attached is the Service Dispatch export for " . date("m/d/Y");
	  email_q($cron_sd_email, $subject, $message, $headers, $file, "", "1", $master_id);
    }
  }
  for($x=0;$x<sizeof($leak_ids);$x++){
    $sql = "UPDATE am_leakcheck set include_cron_export=0 where leak_id='" . $leak_ids[$x] . "'";
	executeupdate($sql);
  }
  


} // end main loop
echo "Export Complete";
?>
