<?php
include "includes/functions.php";

$leak_ids = $_SESSION['sess_xml_leak_id'];

$s = "\n";
$line = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>$s\r\n<import type=\"batchinvoices\">$s\r\n";

$sql = "SELECT ar_account, sales_account from master_list where master_id='" . $SESSION_MASTER_ID . "'";
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

$_SESSION['sess_xml_leak_id'] = "";

$file = "uploaded_files/invoice_export/invoice_" . uniqueTimeStamp() . ".xml";
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

