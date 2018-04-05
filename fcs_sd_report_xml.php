<?php
include "includes/functions.php";

$leak_ids = $_SESSION['sess_xml_leak_id'];

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
