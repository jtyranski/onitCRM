<?php
//$pdf_output = "FI";
if($pdf_output == "") $pdf_output = "I";

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
		
	    $this->SetY(-25);
		
	    $this->SetFont('helvetica', '', 10);
		if($showcall){
		  $this->Cell(0, 0, "If you have any questions about this invoice, please contact $call", 0, 1, 'C');
		}
		$this->SetFont('helvetica', 'BI', 10);
		$this->Cell(0, 0, "Thank you for your business.", 0, 0, 'C');
		// Position at 15 mm from bottom
		
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
  $invoice_filename = uniqueTimeStamp() . ".pdf";
  $sql = "UPDATE am_leakcheck set invoice_filename='$invoice_filename' where leak_id='$leak_id'";
  executeupdate($sql);
}

//$filename = "INVOICE_" . $leak_id . ".pdf";

$sql = "SELECT a.property_id, a.prospect_id, a.correction, a.invoice_type, a.materials, a.labor_rate, a.gtotal_hours, a.extra_cost, 
a.invoice_total, a.rtm_billing, a.sub_total, a.promotional_amount, a.discount_amount, a.rtm_amount, a.billto, a.status, a.payment, a.tax_amount, 
a.rtm_customer, a.rtm_customer_percent, date_format(confirm_date, \"%m/%d/%Y\") as invoice_date_pretty, a.subcontractor_amount, date_format(fix_date, \"%m/%d/%Y\") as invoice_date_pretty2, 
date_format(a.invoice_sent_date, \"%m/%d/%Y\") as invoice_sent_date_pretty, date_format(a.invoice_due_date, \"%m/%d/%Y\") as invoice_due_date_pretty, 
a.travel_desc, a.labor_desc, a.other_desc, a.travel_time, a.travel_rate, a.labor_time, a.desc_work_performed, a.withholding_amount, a.withholding_percent, a.tax_percent, a.other_cost, 
a.include_docs
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

$leak['invoice_total'] -= $leak['payment'];

$leak['travel_total'] = $leak['travel_time'] * $leak['travel_rate'];
$leak['labor_total'] = $leak['labor_time'] * $leak['labor_rate'];

$total = "$" . number_format($leak['invoice_total'], 2);
if($leak['status']=="Closed Out") {
  $leak['materials'] = "Thank you for your business.";
  //$total .= "\nPAID IN FULL";
}


$sql = "SELECT company_name, address, city, state, zip, logo, master_id from prospects where prospect_id='" . $leak['prospect_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);
$company['master_id'] = stripslashes($record['master_id']);

$sql = "SELECT address, city, state, zip, invoice_contact, invoice_contact_number, invoice_user, phone
from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$master = go_fetch_array($result);
if($master['invoice_user'] != 0){
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office as phone, extension from users where user_id='" . $master['invoice_user'] . "'";
  $result = executequery($sql);
  $invoice_user = go_fetch_array($result);
}

$sql = "SELECT site_name, address, city, state, zip from properties where property_id='" . $leak['property_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);

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
if($leak['travel_time'] != 0) {
  $desc .= "Travel: ";
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

if($leak['other_cost'] > 0){
  $desc .= $leak['other_desc'] . "<br>";
  $amt .= number_format($leak['other_cost'], 2) . "<br>";
}



//$pdf->Image("images/lifecycle_logo_small.png", 140, 10, 55);
if($MASTER_LOGO != ""){
  list($width, $height) = getimagesize("uploaded_files/master_logos/" . $MASTER_LOGO);
  $make_width=55;
  $max_height = 20;
  $ratio = $width / $make_width;
  $image_height = round($height / $ratio);
  if($image_height > $max_height){
    $ratio = $height / $max_height;
    $make_width = round($width / $ratio);
    $image_height = $max_height;
  }
  $pdf->Image("uploaded_files/master_logos/" . $MASTER_LOGO, 140, 10, $make_width, $image_height);
}

$pdf->Cell(0, 0, $MASTER_NAME, 0, 1);
$pdf->Cell(0, 0, stripslashes($master['address']), 0, 1);
$pdf->Cell(0, 0, stripslashes($master['city']) . ", " . stripslashes($master['state']) . " " . stripslashes($master['zip']), 0, 1);
$pdf->Cell(0, 0, stripslashes($master['phone']), 0, 1);

$pdf->SetY(57);
$pdf->MultiCell(0, 0, $leak['billto'], 0, "L");

$pdf->SetTextColor(100);
$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetY(27);
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
$pdf->Cell(30, 5, "DUE DATE", 0, 0, 'L');
$pdf->Cell(0, 5, $leak['invoice_due_date_pretty'], 0, 1, 'L');
$pdf->SetX(130);
$pdf->Cell(30, 5, "INVOICE #", 0, 0, 'L');
$pdf->Cell(0, 5, $leak_id, 0, 1, 'L');

$y = $pdf->GetY();
$y += 5;
$pdf->SetY($y);
$pdf->SetX(130);
$pdf->MultiCell(0, 0, "PROPERTY:\n" . $property_info, 0, 'L');

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

$y = $pdf->GetY();
$y += 5;
$pdf->SetY($y);

if($leak['desc_work_performed'] != ""){
  $pdf->SetFillColor(0, 69, 134);
  $pdf->SetTextColor(255);
  $pdf->Cell(100, 6, "WORK PERFORMED", 1, 1, 'L', 1);
  $pdf->SetTextColor(0);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  $pdf->writeHTMLCell(100, 0, $x, $y, nl2br($leak['desc_work_performed']), 'LRB', 1);
}

$pdf->SetY($y);
$pdf->SetX(135);

$pdf->Cell(25, 5, "SUBTOTAL", 0, 0, 'L');
$pdf->Cell(0, 5, "$" . number_format($leak['sub_total'], 2), 0, 1, 'R');




if($leak['tax_amount'] != 0){
  $pdf->SetX(135);
  $pdf->Cell(25, 5, $leak['tax_percent'] . "% TAX", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['tax_amount'], 2), 0, 1, 'R');
}
/*
if($leak['withholding_amount'] != 0){
  $pdf->SetX(135);
  $pdf->Cell(25, 5, $leak['withholding_percent'] . "% WITHHOLDING", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['withholding_amount'], 2), 0, 1, 'R');
}
*/
if($leak['payment'] != 0){
  $pdf->SetX(135);
  $pdf->Cell(25, 5, "RECEIVED", 0, 0, 'L');
  $pdf->Cell(0, 5, "$" . number_format($leak['payment'], 2), 0, 1, 'R');
}


$pdf->SetX(135);
$pdf->Cell(25, 5, "BALANCE DUE", 0, 0, 'L');
$pdf->Cell(0, 5, $total, 0, 1, 'R');
if($leak['status']=="Closed Out") {
  $pdf->SetX(135);
  $pdf->Cell(0, 5, "PAID IN FULL", 0, 1, "R");
}
  

$pdf->SetX(135);
$pdf->Cell(0, 0, "Please make all checks payable to:", 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetX(135);
$pdf->Cell(0, 0, $MASTER_NAME, 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);

if($leak['include_docs']==1) include "fcs_sd_invoice_pdf_documents_data.php";

$pdf->Output('uploaded_files/download/' . $invoice_filename, $pdf_output);
?>
