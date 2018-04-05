<?php
include "includes/functions.php";

$property_id = go_escape_string($_GET['property_id']);
$opm_id = go_escape_string($_GET['opm_id']);
$opm_entry_id = go_escape_string($_GET['opm_entry_id']);
$photo_id = go_escape_string($_GET['photo_id']);

$sql = "SELECT photo from opm_entry_photos where photo_id='$photo_id'";
$photo = getsingleresult($sql);
if($photo != "") @unlink("uploaded_files/opm_photos/$photo");

$sql = "DELETE from opm_entry_photos where photo_id='$photo_id'";
executeupdate($sql);

meta_redirect("frame_property_production_opm_photos.php?property_id=$property_id&opm_id=$opm_id&opm_entry_id=$opm_entry_id");
?>