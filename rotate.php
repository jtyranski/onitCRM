<?php
include "includes/functions.php";

$image = $_GET['image'];
$path = "uploaded_files/" . $_GET['path'] . "/";
$degrees = $_GET['degrees'];
$redirect = $_GET['redirect'];
$property_id = $_GET['property_id'];

image_rotate($image, $path, $degrees);
$random = uniqueTimeStamp();

$idname = $_GET['idname'];
$id = $_GET['id'];
$table = $_GET['table'];
$photoname = $_GET['photoname'];
if($table != ""){
  $ext = explode(".", $image);
  $ext = array_pop($ext);
  $newfilename = uniqueTimeStamp() . "." . $ext;
  @copy($path . $image, $path . $newfilename);
  @unlink($path . $image);
  $sql = "UPDATE $table set $photoname='$newfilename' where $idname='$id'";
  executeupdate($sql);
}

meta_redirect($redirect . "?property_id=$property_id");
?>