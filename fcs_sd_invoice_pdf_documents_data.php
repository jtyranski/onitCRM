<?php

require_once "includes/functions.php";
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');







$leak_id = $_GET['leak_id'];

$sql = "SELECT a.property_id, a.prospect_id, concat(b.firstname, ' ', b.lastname) as dispatchedby, date_format(a.dispatch_date, \"%m/%d/%Y\") as datepretty, 
date_format(dispatch_date, \"%h:%i %p\") as timepretty, a.signature_image, date_format(a.signature_date, \"%m/%d/%Y %h:%i %p\") as signature_date_pretty, 
date_format(a.signature_date, \"%Y\") as sigdate_check, a.signature_name, a.custom_field
from am_leakcheck a, am_users b 
where a.user_id=b.user_id and
a.leak_id='$leak_id'";
$result = executequery($sql);
$leak = go_fetch_array($result);

$sql = "SELECT company_name, address, city, state, zip, master_id from prospects where prospect_id='" . $leak['prospect_id'] . "'";
$result = executequery($sql);
$company = go_fetch_array($result);

$sql = "SELECT address, city, state, zip, invoice_contact, invoice_contact_number, invoice_user, logo, master_name, phone, fax, website, license_number, custom_sd_field
from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$master = go_fetch_array($result);


$sql = "SELECT site_name, address, city, state, zip, image, image_front, groups, subgroups from properties where property_id='" . $leak['property_id'] . "'";
$result = executequery($sql);
$property = go_fetch_array($result);

$property['groups'] = go_reg_replace(",", "", stripslashes($property['groups']));
$property['subgroups'] = go_reg_replace(",", "", stripslashes($property['subgroups']));
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

function probleminfo(){
  global $master, $leak, $company, $property, $pdf, $leak_id, $CORE_URL;
  
  $tallest_image = 0;
  $y = $pdf->GetY();
  if($master['logo'] != ""){
    list($width, $height) = getimagesize($CORE_URL . "uploaded_files/master_logos/" . $master['logo']);
    $make_width=55;
    $max_height = 20;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
    $pdf->Image($CORE_URL . "uploaded_files/master_logos/" . $master['logo'], 5, $y, $make_width, $image_height);
  }
  
  $x = 70;
  $y = $pdf->GetY();
  $pdf->SetX($x);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->MultiCell(0, 0, "Property:", 0, "L");
  $pdf->SetFont('helvetica', '', 10);
  $pdf->SetX($x);
  $pdf->MultiCell(60, 0, stripslashes($property['site_name']), 0, "L");
  $pdf->SetX($x);
  $pdf->MultiCell(60, 0, stripslashes($property['address']), 0, "L");
  $pdf->SetX($x);
  $pdf->MultiCell(60, 0, stripslashes($property['city']) . ", " . stripslashes($property['state']) . " " . stripslashes($property['zip']), 0, "L");
  $y_bottom = $pdf->GetY();
  
  
  
  $x = 135;
  $pdf->SetY($y);
  $pdf->SetX($x);
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->MultiCell(0, 0, "Dispatch ID# " . $leak_id, 0, "L");
  $pdf->SetFont('helvetica', '', 10);
  if($master['custom_sd_field'] != "" && $leak['custom_field'] != ""){
    $pdf->SetX($x);
    $pdf->MultiCell(0, 0, stripslashes($master['custom_sd_field']) . ": " . stripslashes($leak['custom_field']), 0, "L");
  }
  $pdf->SetX($x);
  $pdf->MultiCell(0, 0, stripslashes($company['company_name']), 0, "L");
  $pdf->SetX($x);
  $pdf->MultiCell(0, 0, stripslashes($company['address']), 0, "L");
  $pdf->SetX($x);
  $pdf->MultiCell(0, 0, stripslashes($company['city']) . ", " . stripslashes($company['state']) . " " . stripslashes($company['zip']), 0, "L");
  
  $y_bottom += 3;
  $pdf->SetY($y_bottom);
  $x = $pdf->GetX();
  $y = $pdf->GetY();
  
  if($property['image_front'] != ""){
    list($width, $height) = getimagesize($CORE_URL . "uploaded_files/properties/" . $property['image_front']);
    $make_width=50;
    $max_height = 50;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
	if(($image_height + $y) > $tallest_image) $tallest_image = $y + $image_height;
	
    $pdf->Image($CORE_URL . "uploaded_files/properties/" . $property['image_front'], $x, $y, $make_width, $image_height);
  }
  else {
    $make_width = 50; // for purposes of no front photo, still align next image to right
  }
  $x+=$make_width;
  $x+=10;
  
  if($property['image'] != ""){
    list($width, $height) = getimagesize($CORE_URL . "uploaded_files/properties/" . $property['image']);
    $make_width=50;
    $max_height = 50;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
	if(($image_height + $y) > $tallest_image) $tallest_image = $y + $image_height;
	
    $pdf->Image($CORE_URL . "uploaded_files/properties/" . $property['image'], $x, $y, $make_width, $image_height);
	
	$northx = $x + $make_width + 2;
	$pdf->Image($CORE_URL . "images/north.png", $northx, $y, 5);
  }
  $x+=$make_width;
  $x+=10;
  
  
  $pdf->SetX($x);
  $pdf->SetFont('helvetica', '', 8);
  $pdf->MultiCell($cw, 0, "Distributed By: " . stripslashes($leak['dispatchedby']), 0, "L");
  $pdf->SetX($x);
  $pdf->MultiCell($cw, 0, "Date Dispatched: " . stripslashes($leak['datepretty']), 0, "L");
  $pdf->SetX($x);
  $pdf->MultiCell($cw, 0, "Time Dispatched: " . stripslashes($leak['timepretty']), 0, "L");
  
  $bottom_y = $pdf->GetY();
  $start_sig = $bottom_y;
  if($leak['signature_image'] != ""){
    list($width, $height) = getimagesize($CORE_URL . "uploaded_files/signatures/" . $leak['signature_image']);
    $make_width=40;
    $max_height = 40;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
	$bottom_y += $image_height;
	
    $pdf->Image($CORE_URL . "uploaded_files/signatures/" . $leak['signature_image'], $x, $start_sig, $make_width, $image_height);
  }
  $pdf->SetY($bottom_y);
  if($leak['sigdate_check'] != "0000") {
    $pdf->SetX($x);
    $pdf->MultiCell($cw, 0, "Acknowledged: " . stripslashes($leak['signature_date_pretty']), 0, "L");
  }
  if($leak['signature_name'] != "") {
    $pdf->SetX($x);
    $pdf->MultiCell($cw, 0, "Signed: " . stripslashes($leak['signature_name']), 0, "L");
  }
  $bottom_y = $pdf->GetY();
  $new_y = max($tallest_image, $bottom_y);
  
  
  $new_y += 3;
  $pdf->SetY($new_y);
  
  
  
  $pdf->SetFont('helvetica', '', 10);
  
  
  
}

$sql = "SELECT * from am_leakcheck_problems where leak_id='$leak_id'";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  $y_after_text = 0;
  $y_after_images = 0;
  $pdf->AddPage();
  probleminfo();
  
  
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->MultiCell(40, 0, "Problem " . $counter, 0, "L");
  $pdf->SetFont('helvetica', '', 10);
  
  $y = $pdf->GetY();
  $x = 20;
  
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='" .$record['problem_id'] . "' and type=1 and photo != '' limit 2";
  $result_photo = executequery($sql);
  $photo_counter = 0;
  $tallest_image = 0;
  while($record_photo = go_fetch_array($result_photo)){
    if($photo_counter==1) $x += 80;
    if($record_photo['photo'] != ""){
      list($width, $height) = getimagesize($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record_photo['photo']);
      $make_width=75;
      $max_height = 60;
      $ratio = $width / $make_width;
      $image_height = round($height / $ratio);
      if($image_height > $max_height){
        $ratio = $height / $max_height;
        $make_width = round($width / $ratio);
        $image_height = $max_height;
      }
	  if($image_height > $tallest_image) $tallest_image = $image_height;
	
      $pdf->Image($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record_photo['photo'], $x, $y, $make_width, $image_height);
    }
	$photo_counter++;
	if($photo_counter==2){
	  $x = 20;
	  $y += $tallest_image + 3;
	  $y_after_images = $y;
	  $tallest_image = 0;
	  $photo_counter=0;
	}
  }
  if($photo_counter != 0) $y_after_images = $y + $tallest_image + 3;
  
  if(mysql_num_rows($result_photo)==0) $y_after_images = $pdf->GetY();
  
  $new_y = $y_after_images + 3;
  $pdf->SetY($new_y);
  
  
  $pdf->MultiCell(0, 0, stripslashes($record['problem_desc']), 0, "L");
  
  $y = $pdf->GetY();
  $y += 4;
  $pdf->SetY($y);
  
  $pdf->SetFont('helvetica', 'B', 10);
  $pdf->MultiCell(40, 0, "Correction " . $counter, 0, "L");
  $pdf->SetFont('helvetica', '', 10);
  
  $y = $pdf->GetY();
  $y += 4;
  $pdf->SetY($y);
  
  $x = 20;
  
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='" .$record['problem_id'] . "' and type=2 and photo != '' limit 2";
  $result_photo = executequery($sql);
  $photo_counter = 0;
  $tallest_image = 0;
  while($record_photo = go_fetch_array($result_photo)){
    if($photo_counter==1) $x += 80;
    if($record_photo['photo'] != ""){
      list($width, $height) = getimagesize($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record_photo['photo']);
      $make_width=75;
      $max_height = 60;
      $ratio = $width / $make_width;
      $image_height = round($height / $ratio);
      if($image_height > $max_height){
        $ratio = $height / $max_height;
        $make_width = round($width / $ratio);
        $image_height = $max_height;
      }
	  if($image_height > $tallest_image) $tallest_image = $image_height;
	
      $pdf->Image($UP_FCSVIEW . "uploaded_files/leakcheck/" . $record_photo['photo'], $x, $y, $make_width, $image_height);
    }
	$photo_counter++;
	if($photo_counter==2){
	  $x = 20;
	  $y += $tallest_image + 3;
	  $y_after_images = $y;
	  $tallest_image = 0;
	  $photo_counter=0;
	}
  }
  if($photo_counter != 0) $y_after_images = $y + $tallest_image + 3;
  
  if(mysql_num_rows($result_photo)==0) $y_after_images = $pdf->GetY();
  
  $new_y = $y_after_images + 3;
  $pdf->SetY($new_y);
  
  
  $pdf->MultiCell(0, 0, stripslashes($record['correction']), 0, "L");
  
    
}

  
  
?>