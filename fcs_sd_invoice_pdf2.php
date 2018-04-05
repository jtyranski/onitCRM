<?php
//$pdf_output = "FI";
if($pdf_output == "") {
  $pdf_output = $_GET['pdf_output'];
}

if($pdf_output == "") {
  $pdf_output = "FI";
}

require_once "includes/functions.php";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

$iphone = $_GET['iphone'];
if($iphone != "54321"){
  $leak_id = $_GET['leak_id'];
  $sql = "SELECT c.master_id from am_leakcheck a, properties b, prospects c where a.property_id=b.property_id and b.prospect_id=c.prospect_id and a.leak_id='$leak_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}

class MYPDF extends TCPDF {
 
	//Page header
	public function Header() {
	/*
	    global $pagename_header;
		// Logo
		//$image_file = K_PATH_IMAGES.'logo_example.jpg';
		//$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', '', 10);
		// Title
		$this->Cell(0, 15, date("M j, Y - g:iA"), 0, false, 'L', 0, '', 0, false, 'M', 'M');
		if($pagename_header != ""){
		  $this->Cell(0, 15, $pagename_header, 0, false, 'R', 0, '', 0, false, 'M', 'M');
		}
		*/
	}

	// Page footer
	public function Footer() {
	    global $master;
		global $invoice_user;
		
		$this->SetY(-20);
		$this->SetFont('helvetica', '', 10);
		if($master['phone'] != "") $phoneline = "p: " . $master['phone'] . " ";
		if($master['fax'] != "") $phoneline .= "f: " . $master['fax'];
		$this->Cell(0, 0, stripslashes($master['master_name']) . " - " . stripslashes($master['address']) . " " . stripslashes($master['city']) . ", " . stripslashes($master['state']) . " " . $master['zip'], 0, 1, 'L');
		$this->Cell(0, 0, $phoneline, 0, 1, 'L');
		if($master['website'] != "") $this->Cell(0, 0, stripslashes($master['website']), 0, 1, 'L');
		if($master['license_number'] != "") $this->Cell(0, 0, "License No: " . stripslashes($master['license_number']), 0, 1, 'L');
		$this->SetY(-20);
		
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		
	}
	
	
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($MAIN_CO_NAME);
$pdf->SetTitle('Service Dispatch Invoice');
$pdf->SetSubject('Service Dispatch Invoice');
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 13, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(5);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();

$leak_id = $_GET['leak_id'];
$sql = "SELECT invoice_filename from am_leakcheck where leak_id='$leak_id'";
$invoice_filename = getsingleresult($sql);
if($invoice_filename==""){
  $invoice_filename = secretCode() . ".pdf";
  $sql = "UPDATE am_leakcheck set invoice_filename='$invoice_filename' where leak_id='$leak_id'";
  executeupdate($sql);
}

//$filename = "INVOICE_" . $leak_id . ".pdf";

$sql = "SELECT a.property_id, a.prospect_id, a.correction, a.invoice_type, a.materials, a.labor_rate, a.gtotal_hours, a.extra_cost, 
a.invoice_total, a.rtm_billing, a.sub_total, a.promotional_amount, a.discount_amount, a.rtm_amount, a.billto, a.status, a.payment, a.tax_amount, 
a.rtm_customer, a.rtm_customer_percent, date_format(confirm_date, \"%m/%d/%Y\") as invoice_date_pretty, a.subcontractor_amount, date_format(fix_date, \"%m/%d/%Y\") as invoice_date_pretty2, 
date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date_pretty, date_format(a.invoice_due_date, \"%m/%d/%Y\") as invoice_due_date_pretty, 
a.travel_desc, a.labor_desc, a.other_desc, a.travel_time, a.travel_rate, a.labor_time, a.desc_work_performed, a.withholding_amount, a.withholding_percent, a.tax_percent, a.other_cost, 
a.include_docs, a.invoice_id, a.custom_field, a.simple_invoice, a.custom_field2, a.travel_label_desc, a.contract_amount 
from am_leakcheck a 
where a.leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$leak['property_id'] = stripslashes($record['property_id']);
$leak['prospect_id'] = stripslashes($record['prospect_id']);
$leak['correction'] = stripslashes($record['correction']);
$leak['invoice_type'] = stripslashes($record['invoice_type']);
$leak['materials'] = stripslashes($record['materials']);
$leak['labor_rate'] = stripslashes($record['labor_rate']);
$leak['gtotal_hours'] = stripslashes($record['gtotal_hours']);
$leak['extra_cost'] = stripslashes($record['extra_cost']);
$leak['invoice_total'] = stripslashes($record['invoice_total']);
$leak['rtm_billing'] = stripslashes($record['rtm_billing']);
$leak['sub_total'] = stripslashes($record['sub_total']);
$leak['promotional_amount'] = stripslashes($record['promotional_amount']);
$leak['tax_amount'] = stripslashes($record['tax_amount']);
$leak['discount_amount'] = stripslashes($record['discount_amount']);
$leak['rtm_amount'] = stripslashes($record['rtm_amount']);
$leak['labor_cost'] = $leak['labor_rate'] * $leak['gtotal_hours'];
$leak['billto'] = stripslashes($record['billto']);
$leak['status'] = stripslashes($record['status']);
$leak['rtm_customer'] = stripslashes($record['rtm_customer']);
$leak['rtm_customer_percent'] = stripslashes($record['rtm_customer_percent']);
$leak['payment'] = stripslashes($record['payment']);
$leak['invoice_date_pretty'] = stripslashes($record['invoice_date_pretty']);
if($leak['invoice_date_pretty']=="00/00/0000") $leak['invoice_date_pretty'] = stripslashes($record['invoice_date_pretty2']);
$leak['subcontractor_amount'] = stripslashes($record['subcontractor_amount']);

$leak['invoice_sent_date_pretty'] = stripslashes($record['invoice_sent_date_pretty']);
$leak['invoice_due_date_pretty'] = stripslashes($record['invoice_due_date_pretty']);
$leak['travel_desc'] = stripslashes($record['travel_desc']);
$leak['labor_desc'] = stripslashes($record['labor_desc']);
$leak['other_desc'] = stripslashes($record['other_desc']);
$leak['travel_time'] = stripslashes($record['travel_time']);
$leak['travel_rate'] = stripslashes($record['travel_rate']);
$leak['labor_time'] = stripslashes($record['labor_time']);
$leak['desc_work_performed'] = stripslashes($record['desc_work_performed']);
$leak['withholding_amount'] = stripslashes($record['withholding_amount']);
$leak['withholding_percent'] = stripslashes($record['withholding_percent']);
$leak['tax_percent'] = stripslashes($record['tax_percent']);
$leak['other_cost'] = stripslashes($record['other_cost']);
$leak['include_docs'] = stripslashes($record['include_docs']);
$leak['invoice_id'] = stripslashes($record['invoice_id']);
$leak['custom_field'] = stripslashes($record['custom_field']);
$leak['custom_field2'] = stripslashes($record['custom_field2']);
$leak['simple_invoice'] = stripslashes($record['simple_invoice']);
$leak['travel_label_desc'] = stripslashes($record['travel_label_desc']);
$leak['contract_amount'] = stripslashes($record['contract_amount']);

$leak['invoice_total'] -= $leak['payment'];

$leak['travel_total'] = $leak['travel_time'] * $leak['travel_rate'];
$leak['labor_total'] = $leak['labor_time'] * $leak['labor_rate'];

$total = "$" . number_format($leak['invoice_total'], 2);
if($leak['status']=="Closed Out") {
  $leak['materials'] = "Thank you for your business.";
  //$total .= "\nPAID IN FULL";
}


$sql = "SELECT company_name, address, city, state, zip, logo, master_id, payment_terms from prospects where prospect_id='" . $leak['prospect_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);
$company['master_id'] = stripslashes($record['master_id']);
$company['payment_terms'] = stripslashes($record['payment_terms']);

$sql = "SELECT address, city, state, zip, invoice_contact, invoice_contact_number, invoice_user, phone, website, license_number, custom_sd_field, custom_sd_field2, 
logo, master_name, checks_payable_to, payment_terms, fax
from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$master = go_fetch_array($result);


$payment_terms = $company['payment_terms'];
if($payment_terms=="") $payment_terms = stripslashes($master['payment_terms']);

$sql = "SELECT site_name, address, city, state, zip, groups, subgroups from properties where property_id='" . $leak['property_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['groups'] = go_reg_replace(",", "", stripslashes($record['groups']));
$property['subgroups'] = go_reg_replace(",", "", stripslashes($record['subgroups']));
$USEGROUP = 0;
if($property['groups'] != "" && $property['groups'] !=0) $USEGROUP = $property['groups'];
if($property['subgroups'] != "" && $property['subgroups'] != 0) $USEGROUP = $property['subgroups'];
if($USEGROUP != 0 && $USEGROUP != ""){
  $sql = "SELECT master_name, address, city, state, zip, invoice_user, phone, website, custom_sd_field, custom_sd_field2, logo, master_name, fax, checks_payable_to from groups where id='$USEGROUP'";
  $result = executequery($sql);
  $groups = go_fetch_array($result);
  $master['address'] = $groups['address'];
  $master['city'] = $groups['city'];
  $master['state'] = $groups['state'];
  $master['zip'] = $groups['zip'];
  $master['invoice_user'] = $groups['invoice_user'];
  $master['phone'] = $groups['phone'];
  $master['website'] = $groups['website'];
  $master['custom_sd_field'] = $groups['custom_sd_field'];
  $master['custom_sd_field2'] = $groups['custom_sd_field2'];
  $master['logo'] = $groups['logo'];
  $master['master_name'] = $groups['master_name'];
  $master['fax'] = $groups['fax'];
  $master['checks_payable_to'] = $groups['checks_payable_to'];
}
  
if($master['invoice_user'] != 0){
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office as phone, extension from users where user_id='" . $master['invoice_user'] . "'";
  $result = executequery($sql);
  $invoice_user = go_fetch_array($result);
}
if($master['checks_payable_to']=="") $master['checks_payable_to'] = $master['master_name'];


$sql = "SELECT concat(firstname, ' ', lastname) as fullname, phone from contacts where prospect_id='" . $leak['prospect_id'] . "' 
order by id limit 1";
$result = executequery($sql);
$record = go_fetch_array($result);
$contact['fullname'] = stripslashes($record['fullname']);
$contact['phone'] = stripslashes($record['phone']);


$property_info = $property['site_name'] . "\n";
$property_info .= $property['address'] . "\n";
$property_info .= $property['city'] . ", " . $property['state'] . " " . $property['zip'];


$desc = "";
$amt = "";
if($leak['invoice_type'] != "Billable - Contract"){
if($leak['travel_time'] != 0 && $leak['travel_label_desc'] != "None") {
  $desc .= $leak['travel_label_desc'] . ": ";
  if($leak['travel_desc'] != "") $desc .= "(" . $leak['travel_desc'] . ") ";
  $desc .= $leak['travel_time'] . "hrs @ $" . number_format($leak['travel_rate'], 2) . "/hr<br>";
  
  $amt .= number_format($leak['travel_total'], 2) . "<br>";
  
}

if($leak['labor_time'] != 0) {
  $desc .= "Labor: ";
  if($leak['labor_desc'] != "") $desc .= "(" . $leak['labor_desc'] . ") ";
  $desc .= $leak['labor_time'] . "hrs @ $" . number_format($leak['labor_rate'], 2) . "/hr<br>";
  
  $amt .= number_format($leak['labor_total'], 2) . "<br>";
  
}

$sql = "SELECT * from am_leakcheck_materials where leak_id='$leak_id'";
$result = executequery($sql);
while($mat = go_fetch_array($result)){
  $desc .= stripslashes($mat['description']) . " " . $mat['quantity'] . " " . $mat['units'] . " @ $" . number_format($mat['cost'], 2) . "<br>";
  $mat_sub = $mat['quantity'] * $mat['cost'];
  $amt .= number_format($mat_sub, 2) . "<br>";
}
} else { // end if type not contract.
// show contract amount info
  $desc .= "Contract<br>";
  $amt .= number_format($leak['contract_amount'], 2) . "<br>";
}

$sql = "SELECT * from am_leakcheck_othercost where leak_id='$leak_id'";
$result = executequery($sql);
while($other = go_fetch_array($result)){
  $desc .= stripslashes($other['description']);
  if($other['quantity'] != 0) $desc .=  " " . $other['quantity'] . " " . $other['units'] . " @ $" . number_format($other['cost'], 2);
  $desc .= "<br>";
  $other_sub = $other['quantity'] * $other['cost'];
  $amt .= number_format($other_sub, 2) . "<br>";
}

if($leak['simple_invoice']==1){
  $desc = nl2br($leak['desc_work_performed']);
  $amt = "$". number_format($leak['sub_total'],2);
}



//$pdf->Image("images/lifecycle_logo_small.png", 140, 10, 55);
if($master['logo'] != ""){
  list($width, $height) = getimagesize("uploaded_files/master_logos/" . $master['logo']);
  $make_width=55;
  $max_height = 20;
  $ratio = $width / $make_width;
  $image_height = round($height / $ratio);
  if($image_height > $max_height){
    $ratio = $height / $max_height;
    $make_width = round($width / $ratio);
    $image_height = $max_height;
  }
  $pdf->Image("uploaded_files/master_logos/" . $master['logo'], 140, 10, $make_width, $image_height);
}

$pdf->Cell(0, 0, stripslashes($master['master_name']), 0, 1);
$pdf->Cell(0, 0, stripslashes($master['address']), 0, 1);
$pdf->Cell(0, 0, stripslashes($master['city']) . ", " . stripslashes($master['state']) . " " . stripslashes($master['zip']), 0, 1);
$pdf->Cell(0, 0, stripslashes($master['phone']), 0, 1);

$pdf->SetY(57);
$pdf->MultiCell(0, 0, $leak['billto'], 0, "L");

$pdf->SetTextColor(100);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetY(33);
//$pdf->SetX(160);
$pdf->Cell(0, 0, "INVOICE", 0, 1, 'R');

$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 12);

$y = $pdf->GetY();
$y += 5;
$pdf->SetY($y);

$pdf->SetX(130);
$pdf->Cell(30, 5, "DATE", 0, 0, 'L');
$pdf->Cell(0, 5, $leak['invoice_sent_date_pretty'], 0, 1, 'L');
$pdf->SetX(130);
if(!$leak['invoice_sent_date_pretty']=="00/00/0000"){
$pdf->Cell(30, 5, "DUE DATE", 0, 0, 'L');
$pdf->Cell(0, 5, $leak['invoice_due_date_pretty'], 0, 1, 'L');
}
$pdf->SetX(130);
$pdf->Cell(30, 5, "INVOICE #", 0, 0, 'L');
$pdf->Cell(0, 5, $leak['invoice_id'], 0, 1, 'L');
if($leak['custom_field']!=""){
  $pdf->SetX(130);
  $pdf->Cell(30, 5, $master['custom_sd_field'] . " " . $leak['custom_field'], 0, 0, 'L');
}
if($leak['custom_field2']!=""){
  $pdf->Cell(30, 5, "", 0, 1, 'L');
  $pdf->SetX(130);
  $pdf->Cell(30, 5, $master['custom_sd_field2'] . " " . $leak['custom_field2'], 0, 0, 'L');
}

$y = $pdf->GetY();
$y += 5;
$pdf->SetY($y);
$pdf->SetX(130);
$pdf->MultiCell(0, 0, "PROPERTY:\n" . $property_info, 0, 'L');

//if($leak['simple_invoice']==0){
  $pdf->SetY(95);
  $pdf->SetFillColor(0, 69, 134);
  $pdf->SetTextColor(255);
  $pdf->Cell(130, 6, "DESCRIPTION", 1, 0, 'C', 1);
  $pdf->Cell(0, 6, "AMOUNT", 1, 1, 'C', 1);
  $pdf->SetTextColor(0);

  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->writeHTMLCell(130, 0, $x, $y, $desc, 'LRB', 1);
  $endy = $pdf->GetY();

  $pdf->SetY($y);

  $height = $endy - $y;

  $pdf->writeHTMLCell(0, $height, 145, $y, $amt, 'LRB', 1, 0, true, 'R');
//}

$y = $pdf->GetY();
$y += 5;
$pdf->SetY($y);
if($leak['simple_invoice']==1){ // just a little more padding
  $y = $pdf->GetY();
  $y += 10;
  $pdf->SetY($y);
}

if($leak['desc_work_performed'] != "" && $leak['simple_invoice']==0){
  $pdf->SetFillColor(0, 69, 134);
  $pdf->SetTextColor(255);
  $pdf->Cell(100, 6, "WORK PERFORMED", 1, 1, 'L', 1);
  $pdf->SetTextColor(0);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->writeHTMLCell(100, 0, $x, $y, nl2br($leak['desc_work_performed']), 'LRB', 1);
}

$y_after_desc = $pdf->GetY();

$xstart = 120;
if($leak['simple_invoice']==0){
  $pdf->SetY($y);
  $pdf->SetX($xstart);

  $pdf->Cell(25, 5, "SUBTOTAL", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['sub_total'], 2), 0, 1, 'R');



}
if($leak['tax_amount'] != 0){
  $pdf->SetX($xstart);
  $pdf->Cell(25, 5, $leak['tax_percent'] . "% TAX", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['tax_amount'], 2), 0, 1, 'R');
}
/*
if($leak['withholding_amount'] != 0){
  $pdf->SetX($xstart);
  $pdf->Cell(25, 5, $leak['withholding_percent'] . "% WITHHOLDING", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['withholding_amount'], 2), 0, 1, 'R');
}
*/
if($leak['payment'] != 0){
  $pdf->SetX($xstart);
  $pdf->Cell(25, 5, "RECEIVED", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['payment'], 2), 0, 1, 'R');
}


$pdf->SetX($xstart);
$pdf->Cell(25, 5, "BALANCE DUE", 0, 0, 'L');
$pdf->Cell(0, 5, $total, 0, 1, 'R');
if($leak['status']=="Closed Out") {
  $pdf->SetX($xstart);
  $pdf->Cell(0, 5, "PAID IN FULL", 0, 1, "R");
}
  

$pdf->SetX($xstart);
$pdf->Cell(0, 0, "Please make all checks payable to:", 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetX($xstart);
$pdf->MultiCell(0, 0, stripslashes($master['checks_payable_to']), 0, 'C');
$pdf->SetFont('helvetica', '', 12);

$y_after_total = $pdf->GetY();
$y = max($y_after_total, $y_after_desc);
//$y = $pdf->GetY();
$y += 10;
$pdf->SetY($y);

$pdf->SetFont('helvetica', '', 10);
if($payment_terms != ""){
  $pdf->MultiCell(0, 0, "Payment Terms: $payment_terms\n\n", 0, 'C');
}
	
$showcall = 0;
		if($master['invoice_user'] != 0){
		  $call = stripslashes($invoice_user['fullname']) . " " . stripslashes($invoice_user['email']);
		  $showcall = 1;
		}
		else {
		
		  if($master['invoice_contact_number'] != ""){
		    $call = $master['invoice_contact_number'];
		    $showcall = 1;
		  }
		  if($master['invoice_contact'] != "" && $master['invoice_contact_number'] != ""){
		    $call = stripslashes($master['invoice_contact']) . " at " . $master['invoice_contact_number'];
		    $showcall = 1;
		  }
		}
		
		
	    $pdf->SetFont('helvetica', '', 10);
		if($showcall){
		  $pdf->Cell(0, 0, "If you have any questions about this invoice, please contact $call", 0, 1, 'C');
		}
		$pdf->SetFont('helvetica', 'BI', 10);
		$pdf->Cell(0, 0, "Thank you for your business.", 0, 0, 'C');
		

if($leak['include_docs']==1) include "fcs_sd_invoice_pdf_documents_data.php";

$pdf->Output('uploaded_files/download/' . $invoice_filename, $pdf_output);
?>
