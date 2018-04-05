<?php
include "includes/functions.php";

//$image = $_GET['image'];
$path = $UP_FCSVIEW . "uploaded_files/leakcheck/";
$degrees = $_GET['degrees'];
$photo_id = $_GET['photo_id'];

$sql = "SELECT photo from am_leakcheck_photos where photo_id='$photo_id'";
$image = getsingleresult($sql);

image_rotate($image, $path, $degrees);
$random = uniqueTimeStamp();


  $ext = explode(".", $image);
  $ext = array_pop($ext);
  $newfilename = uniqueTimeStamp() . "." . $ext;
  @copy($path . $image, $path . $newfilename);
  @unlink($path . $image);
  $sql = "UPDATE am_leakcheck_photos set photo='$newfilename' where photo_id='$photo_id'";
  executeupdate($sql);


?>