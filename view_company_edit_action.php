<?php
include "includes/functions.php";


$prospect_id = $_POST['prospect_id'];
$submit1 = $_POST['submit1'];


if($submit1=="Update Logo"){

  if (is_uploaded_file($_FILES['logo']['tmp_name'])){
	if(is_image_valid($_FILES['logo']['name']))
    {
	$ext = explode(".", $_FILES['logo']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['logo']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "logos/", $LOGO_SIZE);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE prospects set logo='$filename' where prospect_id='$prospect_id'";
	executeupdate($sql);
    }
  }

}
meta_redirect("view_company.php?prospect_id=$prospect_id");
?>
