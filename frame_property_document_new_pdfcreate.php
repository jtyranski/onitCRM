<?php
$pdf_output = "F";

require_once "includes/functions.php";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

if(!(class_exists("MYPDF"))){
class MYPDF extends TCPDF {
 
    public function Header(){
	}
	
	// Page footer
	public function Footer() {
	    global $master;
	    $this->SetY(-20);
		if($master['proposal_template']==1){
		  $this->SetFont('helvetica', '', 10);
		  if($master['phone'] != "") $phoneline = "p: " . $master['phone'] . " ";
		  if($master['fax'] != "") $phoneline .= "f: " . $master['fax'];
		  $this->Cell(0, 0, $master['master_name'] . " - " . $master['address'] . " " . $master['city'] . ", " . $master['state'] . " " . $master['zip'], 0, 1, 'L');
		  $this->Cell(0, 0, $phoneline, 0, 1, 'L');
		  if($master['website'] != "") $this->Cell(0, 0, $master['website'], 0, 1, 'L');
		  if($master['license_number'] != "") $this->Cell(0, 0, "License No: " . $master['license_number'], 0, 1, 'L');
		  $this->SetY(-20);
		}
		
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		
	}
	
	public function TopInfo($header) {
	  global $master, $company, $property, $section;
	  
	  $this->SetFont('helvetica', '', 12);
	  
	  
	  if($header==1){
	    $y = $this->GetY();
	    $x = 10;
		$this->SetX($x);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(50, 0, "Customer:", 0, 1, 'L');
		$this->SetFont('helvetica', '', 10);
		$this->SetX($x);
		$this->Cell(50, 0, $company['name'], 0, 1, 'L');
		$this->SetX($x);
		$this->Cell(50, 0, $company['address'], 0, 1, 'L');
		$this->SetX($x);
		$this->Cell(50, 0, $company['city'] . ", " . $company['state'] . " " . $company['zip'], 0, 1, 'L');
		$y_after_company = $this->GetY();
		
		$this->SetY($y);
		$x = 68;
		
		$this->SetX($x);
		$this->SetFont('helvetica', 'B', 10);
		$this->Cell(50, 0, "Property:", 0, 1, 'L');
		$this->SetFont('helvetica', '', 10);
		$this->SetX($x);
		$this->Cell(50, 0, $property['name'], 0, 1, 'L');
		if($section['name'] != ""){
		  $this->SetX($x);
		  $this->Cell(50, 0, $section['name'], 0, 1, 'L');
		}
		$this->SetX($x);
		$this->Cell(50, 0, $property['address'], 0, 1, 'L');
		$this->SetX($x);
		$this->Cell(50, 0, $property['city'] . ", " . $property['state'] . " " . $property['zip'], 0, 1, 'L');
		$y_after_property = $this->GetY();
		
		
	    if($master['master_logo'] != ""){ 
          list($width, $height) = getimagesize("uploaded_files/master_logos/" . $master['master_logo']);

          $make_width = 60;
		  $max_height = 17;
          $ratio = $width / $make_width;
		
		  $image_height = round($height / $ratio);
		
		  if($image_height > $max_height){
            $ratio = $height / $max_height;
            $make_width = round($width / $ratio);
            $image_height = $max_height;
          }
	
          $this->Image("uploaded_files/master_logos/" . $master['master_logo'], 125, 10, $make_width, $image_height);
        }
	    $y_after_image = $y + $image_height;
	  
		
		$new_y = max($y_after_image, $y_after_info);
		$new_y += 4;
		$this->SetY($new_y);
	  } // end if using header
	  
	}
	  

	
	
}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($MAIN_CO_NAME);
$pdf->SetTitle('Service Proposal');
$pdf->SetSubject('Service Proposal');
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(5);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$pdf->SetFont('helvetica', '', 12);

$filename = secretCode() . ".pdf";

$section['name'] = "";
if($section_id != 0){
  $sql = "SELECT section_name from sections where section_id='$section_id'";
  $section['name'] = stripslashes(getsingleresult($sql));
}

$sql = "SELECT site_name, address, city, state, zip from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name, address, city, state, zip, logo, master_id from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['logo'] = stripslashes($record['logo']);
$company['master_id'] = stripslashes($record['master_id']);
$company['address'] = stripslashes($record['address']);
$company['city'] = stripslashes($record['city']);
$company['state'] = stripslashes($record['state']);
$company['zip'] = stripslashes($record['zip']);

$sql = "SELECT logo, master_name, payment_terms, address, city, state, zip, license_number, phone, fax, website, proposal_template, dispatch_from_email from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master['master_name'] = stripslashes($record['master_name']);
$master['master_logo'] = stripslashes($record['logo']);
$master['payment_terms'] = stripslashes($record['payment_terms']);
$master['address'] = stripslashes($record['address']);
$master['city'] = stripslashes($record['city']);
$master['state'] = stripslashes($record['state']);
$master['zip'] = stripslashes($record['zip']);
$master['license_number'] = stripslashes($record['license_number']);
$master['phone'] = stripslashes($record['phone']);
$master['fax'] = stripslashes($record['fax']);
$master['website'] = stripslashes($record['website']);
$master['proposal_template'] = stripslashes($record['proposal_template']);
$master['dispatch_from_email'] = stripslashes($record['dispatch_from_email']);


$pdf->AddPage();
$pdf->TopInfo($header);
$pdf->SetFont('helvetica', '', 10);

//$pdf->SetY(30);

//$pdf->MultiCell(0, 0, $content, 0, 'L', false, 0, 10);
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $content, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

$pdf->Output('uploaded_files/drawings/' . $filename, $pdf_output);
  
?>
