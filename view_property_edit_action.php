<?php
include "includes/functions.php";


$property_id = $_POST['property_id'];
$submit1 = $_POST['submit1'];


if($submit1=="Update Image"){

  if (is_uploaded_file($_FILES['image']['tmp_name'])){
	if(is_image_valid($_FILES['image']['name']))
    {
	$ext = explode(".", $_FILES['image']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "properties/", $PROPERTY_SIZE);
	@copy($UPLOAD . "properties/$filename", $UPLOAD . "properties/th_" . $filename);
	resizeimage($UPLOAD . "properties/th_" . $filename, $UPLOAD . "properties/", $THUMBNAIL);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE properties set image='$filename' where property_id='$property_id'";
	executeupdate($sql);
	$sql = "DELETE from properties_images where property_id='$property_id' and type='roof'";
	executeupdate($sql);
	
	$sql = "INSERT into properties_images(property_id, image, type) values('$property_id', '$filename', 'roof')";
	executeupdate($sql);
    }
  }

}

if($submit1=="Update Front Image"){

  if (is_uploaded_file($_FILES['image']['tmp_name'])){
	if(is_image_valid($_FILES['image']['name']))
    {
	$ext = explode(".", $_FILES['image']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['image']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "properties/", $PROPERTY_SIZE);
	@copy($UPLOAD . "properties/$filename", $UPLOAD . "properties/th_" . $filename);
	resizeimage($UPLOAD . "properties/th_" . $filename, $UPLOAD . "properties/", $THUMBNAIL);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE properties set image_front='$filename' where property_id='$property_id'";
	executeupdate($sql);
    }
  }

}

if($submit1=="Change Logo"){
  $logo = go_escape_string($_POST['logo']);
  $sql = "UPDATE properties set logo='$logo' where property_id='$property_id'";
  executeupdate($sql);
}

if($submit1=="Edit Service Dispatch Contact"){
  $sd_contact = go_escape_string($_POST['sd_contact']);
  $sql = "UPDATE properties set sd_contact='$sd_contact' where property_id='$property_id'";
  executeupdate($sql);
}


meta_redirect("view_property.php?property_id=$property_id");
?>
