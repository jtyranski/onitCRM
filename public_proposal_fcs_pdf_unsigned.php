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
	
	public function TopInfo($showleak=0){
	  global $doc_id, $company, $property, $section, $proposal_date_pretty, $serviceman, $continued, $master, $leak_id, $dispatch;
	  
	  $this->SetFont('helvetica', '', 12);
	  
      //$this->Image("images/lifecycle_logo_small.png", 15, 10, 55);
      //$this->Image("images/fcs_logo.jpg", 140, 10, 55);
      //$this->Image("images/roofoptions_logo_small.jpg", 120, 10, 80);
	  
	  if($master['proposal_template']==1){
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
	    $new_y = 27;
	    $this->SetY($new_y);
	  } // end template 1
	  
	  if($master['proposal_template']==2){
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
	
          $this->Image("uploaded_files/master_logos/" . $master['master_logo'], 10, 10, $make_width, $image_height);
        }
	    $y = $this->GetY();
	    $y_after_image = $y + $image_height;
	  
	    $this->SetFont('helvetica', '', 10);
	    if($master['phone'] != "") $phoneline = "p: " . $master['phone'] . " ";
		if($master['fax'] != "") $phoneline .= "f: " . $master['fax'];
		$this->SetX(120);
		$this->Cell(0, 0, $master['master_name'], 0, 1, 'L');
		$this->SetX(120);
		$this->Cell(0, 0, $master['address'] . " " . $master['city'] . ", " . $master['state'] . " " . $master['zip'], 0, 1, 'L');
		$this->SetX(120);
		$this->Cell(0, 0, $phoneline, 0, 1, 'L');
		$this->SetX(120);
		if($master['website'] != "") $this->Cell(0, 0, $master['website'], 0, 1, 'L');
		$this->SetX(120);
		if($master['license_number'] != "") $this->Cell(0, 0, "License No: " . $master['license_number'], 0, 1, 'L');
		$this->SetFont('helvetica', '', 12);
		$y_after_info = $this->GetY();
		
		$new_y = max($y_after_image, $y_after_info);
		$new_y += 4;
		$this->SetY($new_y);
	  }
	  
	  
	  /*
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
	  */
	    //$this->SetY(27);
        $this->Cell(0, 0, $company['name'], 0, 1, 'L');
		$this->Cell(0, 0, $company['address'], 0, 1, 'L');
	    $this->Cell(0, 0, $company['city'] . ", " . $company['state'] . " " . $company['zip'], 0, 1, 'L');
		
	  $bottom_company_y = $this->GetY();

      $this->SetY($new_y);
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
	    $this->Cell(0, 0, "Proposal #" . $doc_id, 0, 1, 'L');
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

      $this->SetFont('helvetica', 'B', 12);
      $this->Cell($cell_width, 0, 'Serviceman:', 0, 0, 'L');
      $this->SetFont('helvetica', '', 12);
      $this->Cell(0, 0, $serviceman, 0, 1, 'L');

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



$doc_id = $_GET['doc_id'];
$filename = "PROPOSAL_UNSIGNED_" . $doc_id . ".pdf";

$sql = "SELECT check1, check2, name, email, def_selected, section_id, subtotal, total, user_id, intro_credit, multisection, 
date_format(sign_date, \"%m/%d/%Y %r\") as sign_date_pretty, property_id, leak_id, signature_image, 
date_format(proposal_date, \"%m/%d/%Y\") as proposal_date_pretty
from document_proposal_fcs where id='$doc_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$check1 = stripslashes($record['check1']);
$check2 = stripslashes($record['check2']);
$name = stripslashes($record['name']);
$email = stripslashes($record['email']);
$def_selected = stripslashes($record['def_selected']);
$sign_date_pretty = stripslashes($record['sign_date_pretty']);
$section_id = stripslashes($record['section_id']);
$subtotal = stripslashes($record['subtotal']);
$total = stripslashes($record['total']);
$ro_user_id = $record['user_id'];
$intro_credit = stripslashes($record['intro_credit']);
$multisection = stripslashes($record['multisection']);
$property_id = stripslashes($record['property_id']);
$proposal_date_pretty = stripslashes($record['proposal_date_pretty']);
$leak_id = stripslashes($record['leak_id']);
$signature_image = stripslashes($record['signature_image']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='$ro_user_id'";
$serviceman = stripslashes(getsingleresult($sql));

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

$sql = "SELECT site_name, address, city, state, zip, roof_size, groups, subgroups from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['roof_size'] = stripslashes($record['roof_size']);
$property['groups'] = go_reg_replace(",", "", stripslashes($record['groups']));
$property['subgroups'] = go_reg_replace(",", "", stripslashes($record['subgroups']));

$USEGROUP = 0;
if($property['groups'] != "" && $property['groups'] !=0) $USEGROUP = $property['groups'];
if($property['subgroups'] != "" && $property['subgroups'] != 0) $USEGROUP = $property['subgroups'];
if($USEGROUP != 0 && $USEGROUP != ""){
  $sql = "SELECT master_name, address, city, state, zip, invoice_user, phone, website, custom_sd_field, custom_sd_field2, logo, master_name, fax, checks_payable_to,
  dispatch_from_email from groups where id='$USEGROUP'";
  $result = executequery($sql);
  $groups = go_fetch_array($result);
  $master['address'] = stripslashes($groups['address']);
  $master['city'] = stripslashes($groups['city']);
  $master['state'] = stripslashes($groups['state']);
  $master['zip'] = stripslashes($groups['zip']);
  $master['invoice_user'] = stripslashes($groups['invoice_user']);
  $master['phone'] = stripslashes($groups['phone']);
  $master['website'] = stripslashes($groups['website']);
  $master['custom_sd_field'] = stripslashes($groups['custom_sd_field']);
  $master['custom_sd_field2'] = stripslashes($groups['custom_sd_field2']);
  $master['logo'] = stripslashes($groups['logo']);
  $master['master_logo'] = stripslashes($groups['logo']);
  $master['master_name'] = stripslashes($groups['master_name']);
  $master['fax'] = stripslashes($groups['fax']);
  $master['checks_payable_to'] = stripslashes($groups['checks_payable_to']);
  $master['dispatch_from_email'] = stripslashes($groups['dispatch_from_email']);
}

$sitepoints = 0;
$sitesections = 0;
$sql = "SELECT sqft, grade, roof_type from sections where property_id='$property_id' and display=1 and section_type='$section_type'";
$res_sec = executequery($sql);
while($sections = go_fetch_array($res_sec)){
  if($sections['grade'] != "0") $sitesections++;
  $property['sqft'] += $sections['sqft'];
  $grade = $sections['grade'];
  $this_points = $gradevalue[$grade];
  $sitepoints += $this_points;
}
$siteavg = round($sitepoints / $sitesections);
$property['grade'] = $gradevalue_reverse[$siteavg];
  
if($property['sqft'] == 0 || $property['sqft']=="") $property['sqft'] = $property['roof_size'] * 100;

if($leak_id != 0){
  $sql = "SELECT date_format(dispatch_date, \"%m/%d/%Y\") as d_date, problem_desc, correction, section_id, review_def
  from am_leakcheck where leak_id='$leak_id'";
  $sqljr = $sql;
  $result = executequery($sql);
  $dispatch = go_fetch_array($result);
  
  $sql = "SELECT section_name, sqft, main_photo from sections where section_id='" . $dispatch['section_id'] . "'";
  $r_section = executequery($sql);
  $section = go_fetch_array($r_section);
  
  $pdf->AddPage();
  $pdf->TopInfo('1');
  $make_width = 40;
  $max_height = 50;
  //$pdf->MultiCell(0, 0, $sqljr, 0, 'L', false, 1);
  
  if($dispatch['problem_desc'] != ""){
    $pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0, 0, "Problem:", 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
	$pdf->MultiCell(0, 0, stripslashes($dispatch['problem_desc']), 0, 'L', false, 1);
	
	$sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=0 and problem_id=0 and type=1";
    $result = executequery($sql);
    $counter = 0;
	$tallest_image = 0;
    while($record = go_fetch_array($result)){
	  $x = $pdf->GetX();
      $y = $pdf->GetY();
	  if($record['photo'] != ""){ 
        list($width, $height) = getimagesize($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo']);

        
	
        $ratio = $width / $make_width;
	    $image_height = round($height / $ratio);
	    if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	    if($image_height > $tallest_image) $tallest_image = $image_height;
		
        $pdf->Image($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo'], $x, $y, $make_width, $image_height, '', '', '', '', true);
        
		$counter++;
		$x = $x + $make_width + 5;
		$pdf->SetX($x);
		if($counter==4){
		  $y = $y + $tallest_image + 4;
		  $pdf->SetY($y);
		  $counter=0;
		  $tallest_image=0;
		}
      }
	}

	$y = $pdf->GetY();
	$y += 5;
	if($counter != 0) $y += $tallest_image;
	$pdf->SetY($y);
	
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0, 0, "Correction:", 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
	$pdf->MultiCell(0, 0, stripslashes($dispatch['correction']), 0, 'L', false, 1);
	
	$sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=0 and problem_id=0 and type=2";
    $result = executequery($sql);
    $counter = 0;
	$tallest_image = 0;
    while($record = go_fetch_array($result)){
	  $x = $pdf->GetX();
      $y = $pdf->GetY();
	  if($record['photo'] != ""){ 
        list($width, $height) = getimagesize($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo']);

        $ratio = $width / $make_width;
	    $image_height = round($height / $ratio);
	    if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	    if($image_height > $tallest_image) $tallest_image = $image_height;
		
        $pdf->Image($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo'], $x, $y, $make_width, $image_height, '', '', '', '', true);
        
		$counter++;
		$x = $x + $make_width + 5;
		$pdf->SetX($x);
		if($counter==4){
		  $y = $y + $tallest_image + 4;
		  $pdf->SetY($y);
		  $counter=0;
		  $tallest_image=0;
		}
      }
	}
	
	$y = $pdf->GetY();
	$y += 5;
	$pdf->SetY($y);
	
	// end of general problem and correction, now go to am_leakcheck_problems
  }
	
  $sql = "SELECT * from am_leakcheck_problems where leak_id='$leak_id'";
  $result_problems = executequery($sql);
  while($problems = go_fetch_array($result_problems)){
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0, 0, "Problem:", 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
	$pdf->MultiCell(0, 0, stripslashes($problems['problem_desc']), 0, 'L', false, 1);
	
	$sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='" . $problems['problem_id'] . "' and type=1";
    $result = executequery($sql);
    $counter = 0;
	$tallest_image = 0;
    while($record = go_fetch_array($result)){
	  $x = $pdf->GetX();
      $y = $pdf->GetY();
	  if($record['photo'] != ""){ 
        list($width, $height) = getimagesize($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo']);

        $ratio = $width / $make_width;
	    $image_height = round($height / $ratio);
	    if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	    if($image_height > $tallest_image) $tallest_image = $image_height;
		
        $pdf->Image($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo'], $x, $y, $make_width, $image_height, '', '', '', '', true);
        
		$counter++;
		$x = $x + $make_width + 5;
		$pdf->SetX($x);
		if($counter==4){
		  $y = $y + $tallest_image + 4;
		  $pdf->SetY($y);
		  $counter=0;
		  $tallest_image=0;
		}
      }
	}
	
	$y = $pdf->GetY();
	$y += 5;
	if($counter != 0) $y += $tallest_image;
	$pdf->SetY($y);
	
	$pdf->SetFont('helvetica', 'B', 10);
	$pdf->Cell(0, 0, "Correction:", 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
	$pdf->MultiCell(0, 0, stripslashes($problems['correction']), 0, 'L', false, 1);
	
	$sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='" . $problems['problem_id'] . "' and type=2";
    $result = executequery($sql);
    $counter = 0;
	$tallest_image = 0;
    while($record = go_fetch_array($result)){
	  $x = $pdf->GetX();
      $y = $pdf->GetY();
	  if($record['photo'] != ""){ 
        list($width, $height) = getimagesize($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo']);

        $ratio = $width / $make_width;
	    $image_height = round($height / $ratio);
	    if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	    if($image_height > $tallest_image) $tallest_image = $image_height;
		
        $pdf->Image($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record['photo'], $x, $y, $make_width, $image_height, '', '', '', '', true);
        
		$counter++;
		$x = $x + $make_width + 5;
		$pdf->SetX($x);
		if($counter==4){
		  $y = $y + $tallest_image + 4;
		  $pdf->SetY($y);
		  $counter=0;
		  $tallest_image=0;
		}
      }
	}
	
	$y = $pdf->GetY();
	$y += 5;
	if($counter != 0) $y += $tallest_image;
	$pdf->SetY($y);
  }
  
  if(!go_reg("0000", $sign_date_pretty)){
    $pdf->Cell(0, 0, "Signed on: " . $sign_date_pretty, 0, 1, "L");
  }
  if($signature_image != ""){
    $y = $pdf->GetY();
    $pdf->Image("uploaded_files/signatures/" . $signature_image, 15, $y, 55, '', '', '', '', '', true);
  }
} // end if leak_id is not zero, so we're showing problem and correction photos
else {
  $dispatch['review_def']=1; // if not associated with a leak, always show the defs
}
	
if($dispatch['review_def']==1){
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
$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $section['inspector'] . "'";
$section['inspector_name'] = stripslashes(getsingleresult($sql));

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

$continued = "";
$pdf->AddPage();
$pdf->TopInfo();

$per_page = 0;
$def_counter = 0;

for($foo=0;$foo<=sizeof($def_photo);$foo++){ 
  if($def_id[$foo] == "") continue;
  if(!go_reg("," . $def_id[$foo] . ",", $def_selected)) continue; // now only show checked items, even on unsigned 3/1/12 JW
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
/*
  $y = $pdf->GetY();
  if($check2){
    $pdf->Image("images/bigbox_check.gif", 15, $y, 3, '', '', '', '', '', true);
  }
  else {
    $pdf->Image("images/bigbox_uncheck.gif", 15, $y, 3, '', '', '', '', '', true);
  }
  $pdf->SetX(20);
  $pdf->Cell(0, 0, "Please initiate Quarterly Rooftop Maintenance", 0, 1, 'L');
  $pdf->SetX(20);
  $pdf->Cell(0, 0, "Payment terms: $250 billed after each quarterly visit", 0, 1, 'L');
*/

$y_after_total = $pdf->GetY();

$pdf->SetY($bottom_y);

/*
if($intro_credit != 0){
  $pdf->SetX(120);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->Cell(25, 0, "Subtotal:", 0, 0, "L");
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Cell(25, 0, "$" . number_format($subtotal, 2), 0, 1, "R");
  $pdf->SetX(120);
  $pdf->SetTextColor(255, 0, 0);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->Cell(25, 0, "Credit:", 0, 0, "L");
  $pdf->SetFont('helvetica', '', 10);
  $pdf->Cell(25, 0, "$" . number_format($intro_credit, 2), 0, 1, "R");
  $pdf->SetTextColor(0, 0, 0);
}
*/
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
$pdf->Cell(0, 0, "Or scan and email to: " . $master['dispatch_from_email'], 0, 1, "C");
} // end if review_def is 1.  if it's zero, we don't want any def stuff

$pdf->Output('uploaded_files/proposals/' . $filename, $pdf_output);
  
?>
