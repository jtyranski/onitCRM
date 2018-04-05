<?php


include "includes/functions.php";

$sql = "SELECT company_code, acct_rec_code, general_ledger_acct, sales_tax_acct from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master['company_code'] = stripslashes($record['company_code']);
$master['acct_rec_code'] = stripslashes($record['acct_rec_code']);
$master['general_ledger_acct'] = stripslashes($record['general_ledger_acct']);
$master['sales_tax_acct'] = stripslashes($record['sales_tax_acct']);

$leak_ids = $_SESSION['sess_xml_leak_id'];

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
	$tax_amount = stripslashes($record['tax_amount']);
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
    if($groups != "" && $groups !=0) $USEGROUP = $groups;
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
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_othercost where leak_id='" . $leak_ids[$x] . "' AND units != 'Hrs'";
	$other_total = getsingleresult($sql);
	
	// get total of other taxable line items (not unit of hrs)
	$sql = "SELECT sum(quantity * cost) from am_leakcheck_othercost where leak_id='" . $leak_ids[$x] . "' and taxable=1 AND units != 'Hrs'";
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


$_SESSION['sess_xml_leak_id'] = "";

$file = "uploaded_files/invoice_export/invoice_" . secretCode() . ".txt";
$fp = fopen($file, 'w');
fwrite($fp, $line);
fclose($fp);

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
	exit;
}
?>
