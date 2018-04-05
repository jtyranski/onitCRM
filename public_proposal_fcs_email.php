<?php include "includes/functions.php";?>
<?php
$doc_id = $_GET['doc_id'];


$sql = "SELECT check1, check2, name, email, def_selected, section_id, subtotal, total, user_id, intro_credit, 
date_format(sign_date, \"%m/%d/%Y %r\") as sign_date_pretty 
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

$sql = "SELECT section_name, sqft, property_id, roof_type, grade, inspector, notes, main_photo, property_type, 
date_format(installation_date, \"%m/%d/%Y\") as installation_date_pretty, multiple, section_type, 
date_format(inspection_date, \"%m/%d/%Y\") as inspection_date_pretty 
from sections where section_id='$section_id'";
$result = executequery($sql);
$section = go_fetch_array($result);
$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $section['inspector'] . "'";
$section['inspector_name'] = stripslashes(getsingleresult($sql));

$multiple = $section['multiple'];
$property_id = $section['property_id'];
$section_type = $section['section_type'];

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name, address, city, state, zip, logo from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['logo'] = stripslashes($record['logo']);

switch($section['section_type']){
  case "paving":{
    $imagequery = " image_paving as ximage ";
	break;
  }
  case "mech":{
    $imagequery = " image_mech as ximage ";
	break;
  }
  case "ww":{
    $imagequery = " image_ww as ximage ";
	break;
  }
  default:{
    $imagequery = " image as ximage ";
	break;
  }
}
$sql = "SELECT site_name, address, city, state, zip, roof_size, $imagequery from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['image'] = stripslashes($record['ximage']);
$property['roof_size'] = stripslashes($record['roof_size']);

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
  $sql = "SELECT * from sections_def where section_id='$section_id' order by def_id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $def_photo[$counter] = $record['photo'];
	$def_name[$counter] = stripslashes($record['name']);
	$def_foo[$counter] = stripslashes($record['name']);
	$def_def[$counter] = stripslashes(nl2br($record['def']));
	$def_action[$counter] = stripslashes(nl2br($record['action']));
	$def_cost[$counter] = $record['cost'];
	$def_bar[$counter] = $record['cost'];
	$def_type[$counter] = $record['def_type'];
	$def_quantity[$counter] = $record['quantity'];
	$def_quantity_unit[$counter] = $record['quantity_unit'];
	$def_id[$counter] = $record['def_id'];
	$counter++;
  }
}
else {
  $counter = 0;
  $multiple_array = explode(",", $multiple);
  for($x=0;$x<sizeof($multiple_array);$x++){
    $x_section_id = $multiple_array[$x];
    if($x_section_id == "") continue;
    $sql = "SELECT * from sections_def where section_id='$x_section_id' order by def_id";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $def_photo[$counter] = $record['photo'];
	  $def_foo[$counter] = stripslashes($record['name']);
	  $def_def[$counter] = stripslashes(nl2br($record['def']));
	  $def_action[$counter] = stripslashes(nl2br($record['action']));
	  $def_cost[$counter] = $record['cost'];
	  $def_bar[$counter] = $record['cost'];
	  $def_type[$counter] = $record['def_type'];
	  $def_quantity[$counter] = $record['quantity'];
	  $def_quantity_unit[$counter] = $record['quantity_unit'];
	  $def_id[$counter] = $record['def_id'];
	  $counter++;
    }
  }
}

$def_table = "<table class=\"main\">";

for($x=0;$x<=sizeof($def_photo);$x++){
  if($def_photo[$x] == "") continue;

  $checked = "";
  if(go_reg("," . $def_id[$x] . ",", $def_selected)) $checked = " checked";
  $def_table .= "  
  <tr>
  <td valign='top'>
  <input type='checkbox' class=\"mobi_checkbox\" name='def_id[]' value='" . $def_id[$x] . "'  $checked>
  </td>
  <td valign=\"top\">";
  if($def_photo[$x] != ""){
  $max_width = 300;
  $max_height = 200;
  list($width, $height) = getimagesize("uploaded_files/def/" . $def_photo[$x]);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }
  $def_table .= "
  <img src=\"" . $SITE_URL . "uploaded_files/def/" . $def_photo[$x] . "\" width=\"" . $width . "\" height=\"" . $height . "\">";
  }
  $def_table .= "
  </td>
  <td valign=\"top\">
  <strong>" . $def_foo[$x] . "</strong>";
  if($def_type[$x]=="R") {
    $def_table .= "<strong>(Remedial)</strong>";
  }
  else{
    $def_table .= "<strong>(Emergency)</strong>";
  }
  
  $def_table .= "<br>";
  if($def_quantity[$x] != ""){ 
    $def_table .= "Quantity: " . $def_quantity[$x] . " " . $def_quantity_unit[$x] . "<br>";
  }
  $def_table .= "<strong>Deficiency:</strong><br>";
  $def_table .= $def_def[$x] . "<br>
  <strong>Corrective Action:</strong><br>";
  $def_table .= $def_action[$x] . "<br>
  <table class=\"main\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
  <td>Estimated Repair Cost:</td>
  <td align=\"right\">$" . number_format($def_bar[$x], 2) . "</td>
  </tr>
  </table>
  </td>
  </tr>";
  
}

$def_table .= "</table>";

if($company['logo'] != ""){ 
  $max_width = 150;
  $max_height = 100;
  list($width, $height) = getimagesize("uploaded_files/logos/" . $company['logo']);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }
  
  $company_logo = "<img src=\"" . $SITE_URL . "uploaded_files/logos/" . $company['logo'] . "\" width=\"" . $width . "\" height=\"" . $height . "\">";
} else { 
  $company_logo = "<div class=\"main_large\">" . $company['name'] . "</div>";
}


if($section['main_photo'] != ""){

  $max_width = 440;
  $max_height = 250;
  list($width, $height) = getimagesize("uploaded_files/sections/" . $section['main_photo']);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }

  $section_photo = "<img src=\"" . $SITE_URL . "uploaded_files/sections/" . $section['main_photo'] . "\" border=\"0\" width=\"" . $width . "\" height=\"" . $height . "\">";
  
}


$sql = "SELECT body from templates where name='Proposal FCS 2.0'";
$body = stripslashes(getsingleresult($sql));

$body = str_replace("[COMPANY LOGO]", $company_logo, $body);
$body = str_replace("[PROPERTY SITE NAME]", $property['site_name'], $body);
$body = str_replace("[PROPERTY ADDRESS]", $property['address'], $body);
$body = str_replace("[PROPERTY CITY]", $property['city'], $body);
$body = str_replace("[PROPERTY STATE]", $property['state'], $body);
$body = str_replace("[SECTION NAME]", stripslashes($section['section_name']), $body);
$body = str_replace("[SQFT]", stripslashes($section['sqft']), $body);
$body = str_replace("[ROOF TYPE]", stripslashes($section['roof_type']), $body);
$body = str_replace("[INSTALLATION DATE PRETTY]", stripslashes($section['installation_date_pretty']), $body);
$body = str_replace("[GRADE]", stripslashes($section['grade']), $body);
$body = str_replace("[INSPECTION DATE PRETTY]", stripslashes($section['inspection_date_pretty']), $body);
$body = str_replace("[INSPECTOR NAME]", stripslashes($section['inspector_name']), $body);
$body = str_replace("[SECTION PHOTO]", $section_photo, $body);
$body = str_replace("[DEF TABLE]", $def_table, $body);

ob_start()
?>
<div class="main">
<?=$body?>
<br><br>

<div style="width:100%; position:relative;">
<div style="width:50%; float:left;">
  <table class="main">
  <tr>
  <td valign="top"><input type="checkbox" name="check1" value="1"<?php if($check1) echo " checked";?> class="mobi_checkbox"></td>
  <td valign="top">I hereby authorize the work indicated above.<br>
  Payment terms: 30 days from completion of work
  </td>
  </tr>
  <tr>
  <td valign="top"><input type="checkbox" name="check2" value="1"<?php if($check2) echo " checked";?> class="mobi_checkbox"></td>
  <td valign="top">Please initiate Quarterly Rooftop Maintenance<br>
  Payment terms: $250 billed after each quarterly visit
  </td>
  </tr>
  </table>

  
</div>
<div style="width:50%; float:right;">
  <table class="main">
  <tr>
  <td align="right"><strong>Subtotal</strong></td>
  <td width="20">&nbsp;</td>
  <td align="right" id="totaldisplay">$<?=number_format($subtotal, 2)?></td>
  </tr>
  <?php if($intro_credit != 0){ ?>
  <tr>
  <td align="right"><strong>Intro Credit</strong></td>
  <td></td>
  <td align="right" style="color:red;">$<?=number_format($intro_credit, 2)?></td>
  </tr>
  <?php } ?>
  <tr>
  <td align="right"><strong>Total</strong></td>
  <td></td>
  <td align="right" id="discounttotal">$<?=number_format($total, 2)?></td>
  </tr>
  </table>
  
</div>
</div>
<div style="clear:both;"></div>
  <table class="main" width="100%">
  <tr>
  <td align="right"><strong>Name</strong></td>
  <td><?=$name?></td>

  <td align="right"><strong>Email</strong></td>
  <td><?=$email?></td>
  </tr>
  
  
  </table>
  <?php if($sign_date_pretty != ""){ ?>
  <br>
  <div class="main">Signed on <?=$sign_date_pretty?></div>
  <?php } ?>
 
</div>
<?php
$message = ob_get_contents();
ob_end_clean();

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, email, office, extension, title, cellphone, signature
	from users where user_id='" . $ro_user_id . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$mail_user_name = stripslashes($record['fullname']);
	$mail_user_email = stripslashes($record['email']);
	$mail_user_office = stripslashes($record['office']);
	//$mail_user_office = substr($mail_user_office,0,3)."-".substr($mail_user_office,3,3)."-".substr($mail_user_office,6,4);
	$mail_user_extension = stripslashes($record['extension']);
	$mail_user_title = stripslashes($record['title']);
	$mail_user_cellphone = stripslashes($record['cellphone']);
	//$mail_user_cellphone = substr($mail_user_cellphone,0,3)."-".substr($mail_user_cellphone,3,3)."-".substr($mail_user_cellphone,6,4);
	$mail_user_signature = $record['signature'];

  $subject = "Signed Proposal Form for " . $property['site_name'];
  $headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $mail_user_name <$mail_user_email>\n";
  $headers .= "Return-Path: ". $mail_user_email ."\n";  // necessary for some emails such as aol
  $headers .= "Bcc: $email\n";
	  
  email_q($mail_user_email, $subject, $message, $headers);
  
  $_SESSION['sess_msg'] = "Thank you for completing this form.";
  meta_redirect("msg.php");
?>