<?php
include "includes/functions.php";

$property_id = $_GET['property_id'];

if($SESSION_ISADMIN!=1) meta_redirect("view_property.php?property_id=$property_id");

$sql = "SELECT image from properties where property_id='$property_id'";
$image = getsingleresult($sql);

if($image != "") @unlink("uploaded_files/properties/$image");

$sql = "UPDATE properties set image='', image_paving='', image_mech='', image_ww='', mapRect='' where property_id='$property_id'";
executeupdate($sql);

$sql = "DELETE from properties_images where property_id='$property_id'";
executeupdate($sql);

$sql = "SELECT section_id, main_photo from sections where property_id='$property_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $section_id = $record['section_id'];
  $main_photo = $record['main_photo'];
  
  if($main_photo != '') @unlink("uploaded_files/sections/$main_photo");
  /*
  $sql = "SELECT photo from sections_def where section_id='$section_id'";
  $r = executequery($sql);
  while($s = go_fetch_array($r)){
    if($s['photo'] != "") @unlink("uploaded_files/def/" . $s['photo']);
  }
  $sql = "DELETE from sections_def where section_id='$section_id'";
  executeupdate($sql);
*/
$sql = "update sections_def set coordinates='' where section_id = '$section_id'";
executeupdate($sql);
}

$sql = "UPDATE sections set main_photo='' where property_id='$property_id'";
executeupdate($sql);

meta_redirect("view_property.php?property_id=$property_id");
?>