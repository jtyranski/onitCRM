<?php
$pdf_output = $_GET['pdf_output'];

if($pdf_output=="") $pdf_output = "F";

require_once "includes/functions.php";



require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

if(!(class_exists("MYPDF"))){
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
	
	public function TopInfo($type="R", $show_def_header=1){
	  global $company, $property, $master, $custom_proposal_id, $master;
	  
	  $this->SetFont('helvetica', '', 12);
	  
      //$this->Image("images/lifecycle_logo_small.png", 15, 10, 55);
      //$this->Image("images/fcs_logo.jpg", 140, 10, 55);
      //$this->Image("images/roofoptions_logo_small.jpg", 120, 10, 80);
	  
	  if($master['master_logo'] != ""){ 
        list($width, $height) = getimagesize("uploaded_files/master_logos/" . $master['master_logo']);

        $make_width = 80;
		$max_height = 25;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	
        $this->Image("uploaded_files/master_logos/" . $master['master_logo'], 120, 10, $make_width, $image_height);
      }
	  $this->Cell(0, 0, $master['master_name'], 0, 1, 'L');
	  $this->Cell(0, 0, $master['address'], 0, 1, 'L');
	  $this->Cell(0, 0, $master['city'] . ", " . $master['state'] . " " . $master['zip'], 0, 1, 'L');
	  $this->Cell(0, 0, $master['phone'], 0, 1, 'L');
	  
	  $this->SetFont('helvetica', ' ', 10);
      if($company['logo'] != ""){ 
        list($width, $height) = getimagesize("uploaded_files/logos/" . $company['logo']);
        if($width > $height){
          $make_width=55;
        }
        else {
          $make_width=25;
        }
		$max_height = 25;
        $ratio = $width / $make_width;
        $image_height = round($height / $ratio);
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
		
        $this->Image("uploaded_files/logos/" . $company['logo'], 10, 32, $make_width, $image_height, '', '', 'M', '', '', 'L');
		$y = 32 + $image_height;
		$this->SetY($y);
		$this->Cell(0, 0, $company['address'], 0, 1, 'L');
	    $this->Cell(0, 0, $company['city'] . ", " . $company['state'] . " " . $company['zip'], 0, 1, 'L');
		
        $image_y = 30 + $image_height;
      }
      else {
        $this->SetY(32);
        $this->Cell(0, 0, $company['name'], 0, 1, 'L');
		$this->Cell(0, 0, $company['address'], 0, 1, 'L');
	    $this->Cell(0, 0, $company['city'] . ", " . $company['state'] . " " . $company['zip'], 0, 1, 'L');
        $image_y = 30;
      }
	  $bottom_company_y = $this->GetY();

      $this->SetY(32);
      $this->SetX(120);
	  $this->SetFont('helvetica', 'B', 14);
	  $this->Cell(0, 0, "Proposal " . $custom_proposal_id, 0, 1, 'L');
	  $this->SetFont('helvetica', ' ', 10);
	  $this->SetX(120);
	  $this->Cell(0, 0, "PROPERTY:", 0, 1, 'L');
	  $this->SetX(120);
      $this->Cell(0, 0, $property['site_name'], 0, 1, 'L');
      $this->SetX(120);
      $this->Cell(0, 0, $property['address'], 0, 1, 'L');
      $this->SetX(120);
      $this->Cell(0, 0, $property['city'] . ", " . $property['state'], 0, 1, 'L');
      $info_y = $this->GetY();

      if($image_y > $info_y){
        $current_y = $image_y;
      }
      else {
        $current_y = $info_y;
      }

      $current_y +=4;
      $this->SetY($current_y);
	  
	  
	  $cell_width = 35;
	  
      $y = $this->GetY();



      $bottom_text = $this->GetY();

      $this->SetY($y);
      $bottom_image = 0;

      if($property['image'] != ""){ 
        list($width, $height) = getimagesize("uploaded_files/properties/" . $property['image']);

        $make_width = 70;
		$max_height = 40;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	
        $this->Image("uploaded_files/properties/" . $property['image'], 120, $y, $make_width, $image_height);
        
        $bottom_image = $y + $image_height;
      }
	  
	  $new_y = max($bottom_company_y, $bottom_image, $bottom_text);

      $new_y += 2;
      $this->SetY($new_y);
	  
	  if($show_def_header==1){
	    $this->MultiCell(0, 10, "We hereby propose to furnish all labor, material, equipment, and insurance to complete the following scope of work on the above referenced property:", 0, 'L', false, 1);
        $this->SetFont('helvetica', 'B', 14);
        $this->Cell(0, 0, "Deficiencies", 0, 1, 'L');
        $this->SetFont('helvetica', '', 10);
      }

	}
		
}
}


$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('JWJr');
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



$proposal_id = $_GET['proposal_id'];

$sql = "SELECT scope_filename, amount, scope, property_id, custom_proposal_id from proposals where proposal_id='$proposal_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$scope_filename = stripslashes($record['scope_filename']);
$property_id = stripslashes($record['property_id']);
$amount = stripslashes($record['amount']);
$scope = stripslashes($record['scope']);
$custom_proposal_id = stripslashes($record['custom_proposal_id']);


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

$sql = "SELECT site_name, address, city, state, zip, roof_size, image from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['roof_size'] = stripslashes($record['roof_size']);
$property['image'] = stripslashes($record['image']);


  

$pdf->AddPage();
$pdf->TopInfo("RR", 0);
$pdf->Ln();
$pdf->MultiCell(0, 0, $scope . "\n\n", 0, 'L', false, 1);
$pdf->MultiCell(0, 0, "Total: $" . number_format($amount, 2), 0, 'R', false, 1);

$pdf->AddPage();
$pdf->TopInfo("RR", 0);
$pdf->Ln();

$pdf->SetTextColor(255);
$pdf->Cell(0, 6, "TERMS AND CONDITIONS", 1, 1, 'C', 1);
$pdf->SetTextColor(0);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->writeHTMLCell(0, 0, $x, $y, nl2br($master['payment_terms']), 'LRB', 1);

$pdf->Ln();

$auth_proceed = "<br><br><br>";
$auth_proceed .= "Signature: ________________________________ Date: ______________ $ ____________<br><br><br>";
$auth_proceed .= "Printed Name:______________________________________________ PO # ___________<br>";
$pdf->SetTextColor(255);
$pdf->Cell(0, 6, "AUTHORIZATION TO PROCEED", 1, 1, 'C', 1);
$pdf->SetTextColor(0);
$x = $pdf->GetX();
$y = $pdf->GetY();
$pdf->writeHTMLCell(0, 0, $x, $y, $auth_proceed, 'LRB', 1);

$pdf->MultiCell(0, 0, "Standard terms and conditions are expressly incorporated into this proposal. No deviation from the work specified in the contract will be permitted unless a change order is first agreed upon and signed. Your signature represents authorization to proceed with this proposal as written and offers guarantee of payment.", 0, 'L', false, 1);



$pdf->Output('uploaded_files/proposals/' . $scope_filename, $pdf_output);
  
?>
