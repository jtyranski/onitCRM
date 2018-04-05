<?php
include "includes/functions.php";

$property_id = go_escape_string($_GET['property_id']);
$opm_id = go_escape_string($_GET['opm_id']);
$opm_entry_id = go_escape_string($_GET['opm_entry_id']);


$sql = "SELECT photo from opm_entry_photos where opm_entry_id='$opm_entry_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $photo = $record['photo'];
  if($photo != "") @unlink("uploaded_files/opm_photos/$photo");
}

$sql = "DELETE from opm_entry_photos where opm_entry_id='$opm_entry_id'";
executeupdate($sql);

$sql = "DELETE from opm_entry where opm_entry_id='$opm_entry_id'";
executeupdate($sql);

meta_redirect("frame_property_production.php?property_id=$property_id&opm_id=$opm_id");
?>