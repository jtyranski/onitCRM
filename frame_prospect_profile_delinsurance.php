<?php
include "includes/functions.php";

$prospect_id=$_GET['prospect_id'];

$sql = "SELECT insurance_file from prospects_resources where prospect_id='$prospect_id'";
$file = getsingleresult($sql);
if($file != "") @unlink("uploaded_files/insurance/" . $file);
$sql = "UPDATE prospects_resources set insurance_file='' where prospect_id='$prospect_id'";
executeupdate($sql);

meta_redirect("frame_prospect_profile.php?prospect_id=$prospect_id");
?>