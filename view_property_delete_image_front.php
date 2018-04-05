<?php
include "includes/functions.php";

$property_id = $_GET['property_id'];

if($SESSION_ISADMIN!=1) meta_redirect("view_property.php?property_id=$property_id");

$sql = "SELECT image_front from properties where property_id='$property_id'";
$image_front = getsingleresult($sql);

if($image_front != "") @unlink("uploaded_files/properties/$image_front");


$sql = "UPDATE properties set image_front='' where property_id='$property_id'";
executeupdate($sql);

meta_redirect("view_property.php?property_id=$property_id");
?>