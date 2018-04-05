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
	    $this->SetY(-20);
		$this->SetFont('helvetica', '', 10);
		if($master['phone'] != "") $phoneline = "p: " . $master['phone'] . " ";
		if($master['fax'] != "") $phoneline .= "f: " . $master['fax'];
		$this->Cell(0, 0, $master['master_name'] . " - " . $master['address'] . " " . $master['city'] . ", " . $master['state'] . " " . $master['zip'], 0, 1, 'L');
		$this->Cell(0, 0, $phoneline, 0, 1, 'L');
		if($master['website'] != "") $this->Cell(0, 0, $master['website'], 0, 1, 'L');
		if($master['license_number'] != "") $this->Cell(0, 0, "License No: " . $master['license_number'], 0, 1, 'L');
		$this->SetY(-20);
		
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		
	}
	
	public function TopInfo($showleak=0){
	  global $custom_proposal_id, $company, $property, $proposal_date_pretty, $continued, $master, $section;
	  
	  $this->SetFont('helvetica', '', 12);
	  
      //$this->Image("images/lifecycle_logo_small.png", 15, 10, 55);
      //$this->Image("images/fcs_logo.jpg", 140, 10, 55);
      //$this->Image("images/roofoptions_logo_small.jpg", 120, 10, 80);
	  
	  if($master['master_logo'] != ""){ 
        list($width, $height) = getimagesize("uploaded_files/master_logos/" . $master['master_logo']);

        $make_width = 80;
		$max_height = 17;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	
        $this->Image("uploaded_files/master_logos/" . $master['master_logo'], 120, 10, $make_width, $image_height);
      }
	  
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
		
        $this->Image("uploaded_files/logos/" . $company['logo'], 10, 10, $make_width, $image_height, '', '', 'M', '', '', 'L');
		$y = 10 + $image_height;
		$this->SetY($y);
        
		$this->Cell(0, 0, $company['name'], 0, 1, 'L');
		$this->Cell(0, 0, $company['address'], 0, 1, 'L');
	    $this->Cell(0, 0, $company['city'] . ", " . $company['state'] . " " . $company['zip'], 0, 1, 'L');
		$image_y = 30 + $image_height;
      }
      else {
        $this->SetY(27);
        $this->Cell(0, 0, $company['name'], 0, 1, 'L');
		$this->Cell(0, 0, $company['address'], 0, 1, 'L');
	    $this->Cell(0, 0, $company['city'] . ", " . $company['state'] . " " . $company['zip'], 0, 1, 'L');
        $image_y = 0;
      }
	  $bottom_company_y = $this->GetY();

      $this->SetY(27);
      $this->SetX(120);
      $this->Cell(0, 0, $property['site_name'], 0, 1, 'L');
      $this->SetX(120);
      $this->Cell(0, 0, $property['address'], 0, 1, 'L');
      $this->SetX(120);
      $this->Cell(0, 0, $property['city'] . ", " . $property['state'], 0, 1, 'L');
      $info_y = $this->GetY();

      if($bottom_company_y > $info_y){
        $current_y = $bottom_company_y;
      }
      else {
        $current_y = $info_y;
      }

      $current_y +=4;
      $this->SetY($current_y);
	  
	  
	  $cell_width = 35;
	  
      $y = $this->GetY();
	  if($showleak==1){
	    $this->Cell(0, 0, "Dispatch #" . $leak_id, 0, 1, 'L');
	  }
	  else {
	    $this->Cell(0, 0, "Proposal #" . $custom_proposal_id, 0, 1, 'L');
	  }
      $this->SetFont('helvetica', 'B', 12);
      $this->Cell($cell_width, 0, 'Roof Section:', 0, 0, 'L');
      $this->SetFont('helvetica', '', 12);
      $this->Cell(0, 0, stripslashes($section['section_name']) . $continued, 0, 1, 'L');

      if($section['sqft'] != 0){
      $this->SetFont('helvetica', 'B', 12);
      $this->Cell($cell_width, 0, 'Roof Size:', 0, 0, 'L');
      $this->SetFont('helvetica', '', 12);
      $this->Cell(0, 0, stripslashes(number_format($section['sqft'], 0)) . " Sq/Ft", 0, 1, 'L');
      }

      $this->Cell(0, 0, '', 0, 1, 'L');

      

      //$this->Cell(0, 0, '', 0, 1, 'L');
      
	  if($showleak==1){
	    $this->SetFont('helvetica', 'B', 12);
        $this->Cell($cell_width, 0, 'Dispatch Date:', 0, 0, 'L');
        $this->SetFont('helvetica', '', 12);
        $this->Cell(0, 0, stripslashes($dispatch['d_date']), 0, 1, 'L');
	  }
	  else {
        if($proposal_date_pretty != "00/00/0000"){
          $this->SetFont('helvetica', 'B', 12);
          $this->Cell($cell_width, 0, 'Proposal Date:', 0, 0, 'L');
          $this->SetFont('helvetica', '', 12);
          $this->Cell(0, 0, stripslashes($proposal_date_pretty), 0, 1, 'L');
        }
	  }

      $bottom_text = $this->GetY();

      $this->SetY($y);
      $bottom_image = 0;

      if($section['main_photo'] != ""){ 
        list($width, $height) = getimagesize("uploaded_files/sections/" . $section['main_photo']);

        $make_width = 70;
		$max_height = 40;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	
        $this->Image("uploaded_files/sections/" . $section['main_photo'], 120, $y, $make_width, $image_height);
        
        $bottom_image = $y + $image_height;
      }
      if($bottom_image >= $bottom_text){
        $new_y = $bottom_image;
      }
      else {
        $new_y = $bottom_text;
      }
      $new_y += 2;
      $this->SetY($new_y);
	  
	  if($showleak==0){
	    $this->Cell(0, 10, "Please check next to the deficiencies you authorize for repair, then total, sign, and date where indicated", 0, 1, 'C');

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
$sql = "SELECT repair_filename, sections, property_id, date_format(proposal_date, \"%m/%d/%Y\") as proposal_date_pretty, custom_proposal_id from proposals where proposal_id='$proposal_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$repair_filename = stripslashes($record['repair_filename']);
$property_id = stripslashes($record['property_id']);
$multisection = stripslashes($record['sections']);
$proposal_date_pretty = stripslashes($record['proposal_date_pretty']);
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

$sql = "SELECT logo, master_name, payment_terms, address, city, state, zip, license_number, phone, fax, website from master_list where master_id='" . $company['master_id'] . "'";
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

$sql = "SELECT site_name, address, city, state, zip, roof_size from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['roof_size'] = stripslashes($record['roof_size']);

	
// start loop of multisection
$first_time=1;
$multisection_array = explode(",", $multisection);
for($loop=0;$loop<sizeof($multisection_array);$loop++){
  $section_id = $multisection_array[$loop];
  if($section_id=="") continue;
  //if($first_time==0) $pdf->AddPage();
  //$first_time = 0;

$sql = "SELECT section_name, sqft, property_id, roof_type, grade, inspector, notes, main_photo, property_type, 
date_format(installation_date, \"%m/%d/%Y\") as installation_date_pretty, multiple, section_type, 
date_format(inspection_date, \"%m/%d/%Y\") as inspection_date_pretty 
from sections where section_id='$section_id'";
$result = executequery($sql);
$section = go_fetch_array($result);

$multiple = $section['multiple'];
$section_type = $section['section_type'];




$def_photo = "";
$def_name = "";
$def_foo = "";
$def_def = "";
$def_action = "";
$def_cost = "";
$def_bar = "";
$def_type = "";
$def_quantity = "";
$def_quantity_unit = "";

if($multiple == ""){
  $counter = 0;
  $sql = "SELECT *, date_format(date_recorded, \"%m/%d/%Y\") as daterec from sections_def where section_id='$section_id' and complete=0 order by def_id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $def_photo[$counter] = $record['photo'];
	$def_name[$counter] = stripslashes($record['name']);
	$def_foo[$counter] = stripslashes($record['name']);
	$def_def[$counter] = stripslashes($record['def']);
	$def_action[$counter] = stripslashes($record['action']);
	$def_cost[$counter] = $record['cost'];
	$def_bar[$counter] = $record['cost'];
	$def_type[$counter] = $record['def_type'];
	$def_quantity[$counter] = $record['quantity'];
	$def_quantity_unit[$counter] = $record['quantity_unit'];
	$def_id[$counter] = $record['def_id'];
	$def_daterec[$counter] = $record['daterec'];
	$counter++;
  }
}
else {
  $counter = 0;
  $multiple_array = explode(",", $multiple);
  for($x=0;$x<sizeof($multiple_array);$x++){
    $x_section_id = $multiple_array[$x];
    if($x_section_id == "") continue;
    $sql = "SELECT *, date_format(date_recorded, \"%m/%d/%Y\") as daterec from sections_def where section_id='$x_section_id' and complete=0 order by def_id";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $def_photo[$counter] = $record['photo'];
	  $def_foo[$counter] = stripslashes($record['name']);
	  $def_def[$counter] = stripslashes($record['def']);
	  $def_action[$counter] = stripslashes($record['action']);
	  $def_cost[$counter] = $record['cost'];
	  $def_bar[$counter] = $record['cost'];
	  $def_type[$counter] = $record['def_type'];
	  $def_quantity[$counter] = $record['quantity'];
	  $def_quantity_unit[$counter] = $record['quantity_unit'];
	  $def_id[$counter] = $record['def_id'];
	  $def_daterec[$counter] = $record['daterec'];
	  $counter++;
    }
  }
}


$pdf->AddPage();
$pdf->TopInfo();

$per_page = 0;
$def_counter = 0;
$continued = "";
for($foo=0;$foo<=sizeof($def_photo);$foo++){ 
  if($def_id[$foo] == "") continue;
  if($def_def[$foo]=="") continue;
  $def_counter++;
  
  
  $x = $pdf->GetX() + 5;
  $y = $pdf->GetY();
  $pdf->Image("images/bigbox_uncheck.gif", 10, $y+5, 3, '', '', '', '', '', true);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->MultiCell(15, 0, $def_counter, 0, 'L', false, 0, 10);
  
  if($def_photo[$foo] != ""){ 
    list($width, $height) = getimagesize("uploaded_files/def/" . $def_photo[$foo]);

    $make_width = 55;
	$max_height = 50;
	
    $ratio = $width / $make_width;
	$image_height = round($height / $ratio);
	if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
	$image_y = $y + $image_height;
    $pdf->Image("uploaded_files/def/" . $def_photo[$foo], $x, $y, $make_width, $image_height, '', '', '', '', true);
  
  }

  //$x = $pdf->GetX();
  $x+=$make_width;
  $x+=2;
  
  $info_width = 120;
  $text = $def_foo[$foo];
  if($def_type[$foo]=="R") {
    $text .= " (Remedial)";
  }
  else{
    $text .= " (Emergency)";
  }
  if($def_daterec[$foo] != "00/00/0000") $text .= " - " . $def_daterec[$foo];
  $text .= "\n";
  if($def_quantity[$foo] != ""){
    $text .= "Quantity: " . $def_quantity[$foo] . " " . $def_quantity_unit[$foo] . "\n\n";
  }
  
  $text .= "Deficiency: ";
  $text .= $def_def[$foo] . "\n\n";
  $text .= "Corrective Action: ";
  $text .= $def_action[$foo] . "\n\n";
  //$text .= "Estimated Repair Cost: ";
  //$text .= "$" . number_format($def_bar[$foo], 2);
  
  $pdf->SetY($y);
  $pdf->SetX($x);
  $pdf->SetFont('helvetica', '', 10);
  $pdf->MultiCell($info_width, 0, $text, 0, 'L', false, 1);
  
  if($def_bar[$foo] != 0 && $def_bar[$foo] != ""){
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->SetX($x);
  $pdf->MultiCell($info_width, 0, "Estimated Repair Cost: $" . number_format($def_bar[$foo], 2), 0, 'L', false, 1);
  $pdf->SetFont('helvetica', '', 10);
  }
  
  $test_y = $pdf->GetY();
  if($test_y >= $image_y) {
    $new_y = $test_y;
  }
  else {
    $new_y = $image_y;
  }
  $new_y += 7;
  $pdf->SetY($new_y);
  
  $per_page++;
  if($per_page==2 && ($foo + 1) < sizeof($def_photo)){
    $pdf->AddPage();
	$continued = " (continued)";
	$pdf->TopInfo();
	$per_page=0;
	$x = $pdf->GetX();
    $y = $pdf->GetY();
  }
  
}

$y = $pdf->GetY();
$y += 5;
$pdf->SetY($y);

}
//***************************************  end of loop multisection  ****************************************


$y = $pdf->GetY();
$bottom_y = $y;

$pdf->SetX(20);
$pdf->MultiCell(110, 0, $master['payment_terms'], 0, 'L', false, 1);

$y = $pdf->GetY();
$y += 5;

$pdf->SetY($y);


$y_after_total = $pdf->GetY();

$pdf->SetY($bottom_y);


$pdf->SetX(130);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(25, 0, "Total:", 0, 0, "L");
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(25, 0, "$" . "___________", 0, 1, "R");


$y_after_total += 8;
$pdf->SetY($y_after_total);

$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(15, 0, "Name:", 0, 0, "L");
$pdf->Cell(80, 0, "_________________________________", 0, 0, "L");
$pdf->Cell(14, 0, "Date:", 0, 0, "L");
$pdf->Cell(0, 0, "_________________", 0, 1, "L");

$pdf->Ln();
$pdf->SetFont('helvetica', 'I', 12);
$pdf->Cell(0, 0, "Please sign and date, then fax to: " . $master['fax'], 0, 1, "C");


$pdf->Output('uploaded_files/proposals/' . $repair_filename, $pdf_output);
  
?>
