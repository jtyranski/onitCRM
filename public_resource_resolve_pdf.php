<?php


require_once "includes/functions.php";
if($code=="") $code = $_GET['code'];

$sql = "SELECT leak_id from am_leakcheck where code='$code'";
$leak_id = getsingleresult($sql);
if($leak_id==""){
  exit;
}

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

if(!(class_exists("MYPDF"))){
class MYPDF extends TCPDF {
 
	public function Header() {

	}

	// Page footer
	public function Footer() {

		
	}
	
	
}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('FCS');
$pdf->SetTitle('Service Proposal');
$pdf->SetSubject('Service Proposal');
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(5);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$pdf->SetFont('helvetica', '', 12);



$filename = "NOCOSTPROPOSAL_UNSIGNED_" . $leak_id . ".pdf";

$sql = "SELECT section_id, property_id from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$section_id = stripslashes($record['section_id']);
$property_id = stripslashes($record['property_id']);
$multisection = $section_id;

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name, address, city, state, zip, logo, master_id, hours_of_operation, invoice_requirements, checkin_procedure, checkout_procedure from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['logo'] = stripslashes($record['logo']);
$company['master_id'] = stripslashes($record['master_id']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);
$company['hours_of_operation'] = stripslashes($record['hours_of_operation']);
$company['invoice_requirements'] = stripslashes($record['invoice_requirements']);
$company['checkin_procedure'] = stripslashes($record['checkin_procedure']);
$company['checkout_procedure'] = stripslashes($record['checkout_procedure']);

$sql = "SELECT logo, master_name, fax, payment_terms, address, city, state, zip, phone, master_id, invoice_user, invoice_contact, invoice_contact_number from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master['master_name'] = stripslashes($record['master_name']);
$master['master_logo'] = stripslashes($record['logo']);
$master['fax'] = stripslashes($record['fax']);
$master['payment_terms'] = stripslashes($record['payment_terms']);
$master['address'] = stripslashes($record['address']);
$master['city'] = stripslashes($record['city']);
$master['state'] = stripslashes($record['state']);
$master['zip'] = stripslashes($record['zip']);
$master['phone'] = stripslashes($record['phone']);
$master['master_id'] = stripslashes($record['master_id']);
$master['invoice_user'] = stripslashes($record['invoice_user']);
$master['invoice_contact'] = stripslashes($record['invoice_contact']);
$master['invoice_contact_number'] = stripslashes($record['invoice_contact_number']);

if($master['invoice_user'] != 0){
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office as phone, extension from users where user_id='" . $master['invoice_user'] . "'";
  $result = executequery($sql);
  $invoice_user = go_fetch_array($result);
}

$sql = "SELECT site_name, address, city, state, zip, roof_size from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['roof_size'] = stripslashes($record['roof_size']);

$sql = "SELECT section_name, sqft, property_id, roof_type, grade, inspector, notes, main_photo, property_type, 
date_format(installation_date, \"%m/%d/%Y\") as installation_date_pretty, multiple, section_type, 
date_format(inspection_date, \"%m/%d/%Y\") as inspection_date_pretty 
from sections where section_id='$section_id'";
$result = executequery($sql);
$section = go_fetch_array($result);

$sql = "SELECT a.priority_id, date_format(a.dispatch_date, \"%m/%d/%Y %h:%i %p\") as dispatch_pretty, concat(e.firstname, ' ', e.lastname) as dispatchedby, 
date_format(a.eta_date, \"%m/%d/%Y %h:%i %p\") as eta_pretty, b.timezone, a.invoice_id, a.invoice_type, a.other_cost, a.po_number, a.notes, a.additional_notes
from am_leakcheck a, am_users e, properties b
where a.user_id = e.user_id and a.property_id=b.property_id and leak_id='$leak_id'";
$result = executequery($sql);
$leak = go_fetch_array($result);
switch($leak['timezone']){
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

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
	  
      //$this->Image("images/lifecycle_logo_small.png", 15, 10, 55);
      //$this->Image("images/fcs_logo.jpg", 140, 10, 55);
      //$this->Image("images/roofoptions_logo_small.jpg", 120, 10, 80);
	  
	  if($master['master_logo'] != ""){ 
        list($width, $height) = getimagesize($CORE_URL . "uploaded_files/master_logos/" . $master['master_logo']);

        $make_width = 80;
		$max_height = 25;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	
        $pdf->Image($CORE_URL . "uploaded_files/master_logos/" . $master['master_logo'], 120, 10, $make_width, $image_height);
      }
	  $pdf->Cell(0, 0, $master['master_name'], 0, 1, 'L');
	  $pdf->Cell(0, 0, $master['address'], 0, 1, 'L');
	  $pdf->Cell(0, 0, $master['city'] . ", " . $master['state'] . " " . $master['zip'], 0, 1, 'L');
	  $pdf->Cell(0, 0, $master['phone'], 0, 1, 'L');

$y = $pdf->GetY();

if($section['main_photo'] != ""){ 
        list($width, $height) = getimagesize($CORE_URL . "uploaded_files/sections/" . $section['main_photo']);

        $make_width = 70;
		$max_height = 40;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	
        $pdf->Image($CORE_URL . "uploaded_files/sections/" . $section['main_photo'], 10, $y, $make_width, $image_height);
        
        $bottom_image = $y + $image_height;
}
$pdf->SetY($y);
$x = $make_width + 15;
$pdf->SetX($x);
$pdf->Cell(0, 0, $property['site_name'], 0, 1, 'L');
$pdf->SetX($x);
$pdf->Cell(0, 0, $property['address'], 0, 1, 'L');
$pdf->SetX($x);
$pdf->Cell(0, 0, $property['city'] . ", " . $property['state'] . " " . $property['zip'], 0, 1, 'L');

$x += 60;
$pdf->SetX($x);
$pdf->Cell(0, 0, "Invoice # " . stripslashes($leak['invoice_id']), 0, 1, 'L');
$pdf->SetX($x);
$pdf->Cell(0, 0, "PO # " . stripslashes($leak['po_number']), 0, 1, 'L');
$pdf->SetX($x);
$pdf->Cell(0, 0, "Distributed By: " . stripslashes($leak['dispatchedby']), 0, 1, 'L');
$pdf->SetX($x);
$pdf->Cell(0, 0, "Date: " . stripslashes($leak['dispatch_pretty']) . " " . $tz_display, 0, 1, 'L');
$pdf->SetX($x);
$pdf->Cell(0, 0, "ETA: " . stripslashes($leak['eta_pretty']) . " " . $tz_display, 0, 1, 'L');

$y = $pdf->GetY();
$y +=6;
$pdf->SetY($y);

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, "Notes", 0, 1, 'L');
$pdf->Cell(0, 0, "", 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);

if($leak['notes'] != ""){
  $pdf->MultiCell(0, 0, stripslashes($leak['notes']) . "\n\n", 0, 'L', false, 1);
}

if($leak['additional_notes'] != ""){
  $pdf->MultiCell(0, 0, stripslashes($leak['additional_notes']) . "\n\n", 0, 'L', false, 1);
}

$y = $pdf->GetY();

$pdf->Image($CORE_URL . "images/proofdoc.png", 10, $y, 160);

$pdf->Output('uploaded_files/proposals/' . $filename, "I");

?>
