<?php
include "includes/functions.php";

$drawing_id = $_GET['drawing_id'];
$property_id = $_GET['property_id'];
$section_type = $_GET['section_type'];


$sql = "SELECT file from drawings where drawing_id='$drawing_id' and property_id='$property_id'";
$file = getsingleresult($sql);
if($file) @unlink("uploaded_files/drawings/$file");

$sql = "DELETE from drawings where drawing_id='$drawing_id' and property_id='$property_id'";
executeupdate($sql);

meta_redirect("frame_property_drawings.php?property_id=$property_id&section_type=$section_type");
?>