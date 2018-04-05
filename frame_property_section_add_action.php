<?php
include "includes/functions.php";


$section_id = $_POST['section_id'];

$property_id = $_POST['property_id'];
$grade = go_escape_string($_POST['grade']);
$sqft = go_escape_string($_POST['sqft']);
$roof_type = go_escape_string($_POST['roof_type']);
$section_name = go_escape_string($_POST['section_name']);
$inspection_status = go_escape_string($_POST['inspection_status']);
$map_reference = go_escape_string($_POST['map_reference']);
$projected_replacement = go_escape_string($_POST['projected_replacement']);

$installation_date_pretty = go_escape_string($_POST['installation_date_pretty']);
$estimated_install = go_escape_string($_POST['estimated_install']);

$manufacturer = go_escape_string($_POST['manufacturer']);
$warranty = go_escape_string($_POST['warranty']);
$contractor = go_escape_string($_POST['contractor']);
$cont_contact = go_escape_string($_POST['cont_contact']);
$cont_phone = go_escape_string($_POST['cont_phone']);
$cont_email = go_escape_string($_POST['cont_email']);
$cont_mobile = go_escape_string($_POST['cont_mobile']);
$roof_system = go_escape_string($_POST['roof_system']);
$insulation = go_escape_string($_POST['insulation']);
$deck = go_escape_string($_POST['deck']);
$property_type = go_escape_string($_POST['property_type']);
$section_type = go_escape_string($_POST['section_type']);
if($section_type=="paving") $property_type = "Non-PFRI";

$paving_regular = go_escape_string($_POST['paving_regular']);
$paving_handicap = go_escape_string($_POST['paving_handicap']);
$paving_interplant = go_escape_string($_POST['paving_interplant']);
$paving_guest = go_escape_string($_POST['paving_guest']);
$paving_unitedway = go_escape_string($_POST['paving_unitedway']);
$paving_motorcycle = go_escape_string($_POST['paving_motorcycle']);
$paving_tenminute = go_escape_string($_POST['paving_tenminute']);

$dis_id = go_escape_string($_POST['dis_id']);

$submit1 = go_escape_string($_POST['submit1']);

$multiple = $_POST['multiple'];

if(is_array($multiple)){
  for($x=0;$x<sizeof($multiple);$x++){
    $x_multiple = $multiple[$x];
	$multiple_insert .= $x_multiple . ",";
  }
  $multiple_insert = go_reg_replace(",$", "", $multiple_insert);
}
else {
  $multiple_insert = "";
}



if($submit1 != ""){
  if($section_id=="new"){
    $sql = "INSERT into sections(property_id) values (\"$property_id\")";
	executeupdate($sql);
	$section_id = go_insert_id();
  }


    $sql = "UPDATE sections set section_name=\"$section_name\", 
	grade=\"$grade\", roof_type=\"$roof_type\", sqft=\"$sqft\", map_reference=\"$map_reference\", 
	projected_replacement=\"$projected_replacement\", estimated_install=\"$estimated_install\", 
	manufacturer=\"$manufacturer\", warranty=\"$warranty\", contractor=\"$contractor\", cont_contact=\"$cont_contact\", 
	cont_phone=\"$cont_phone\", cont_email=\"$cont_email\", cont_mobile=\"$cont_mobile\", roof_system=\"$roof_system\", 
	insulation=\"$insulation\", deck=\"$deck\", property_type=\"$property_type\", multiple=\"$multiple_insert\",
	dis_id=\"$dis_id\"
	where section_id='$section_id'";
	executeupdate($sql);

  
  if (is_uploaded_file($_FILES['photo']['tmp_name']))
      {
	  if(is_image_valid($_FILES['photo']['name'])){

  
        $ext = explode(".", $_FILES['photo']['name']);
  	    $ext = array_pop($ext);
  	    $filename = uniqueTimeStamp() . "." . $ext;
	    move_uploaded_file($_FILES['photo']['tmp_name'], "uploaded_files/temp/". $filename);
  	    resizeimage("uploaded_files/temp/$filename", "uploaded_files/sections/", $SECTION_PHOTO_SIZE);
  	    @unlink("uploaded_files/temp/". $filename);
	
	    $sql = "UPDATE sections set main_photo='$filename' where section_id='$section_id'";
	    executeupdate($sql);
      }
  }
  
}

meta_redirect("frame_property_sections.php?property_id=$property_id&dis_id=$dis_id");
?>
