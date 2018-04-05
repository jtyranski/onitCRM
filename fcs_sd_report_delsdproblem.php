<?php
include "includes/functions.php";

$problem_id = go_escape_string($_GET['problem_id']);
$leak_id = go_escape_string($_GET['leak_id']);

$sql = "SELECT photo from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $photo = stripslashes($record['photo']);
  if($photo != "") @unlink($UP_FCSVIEW . "uploaded_files/leakcheck/$photo");
}

$sql = "DELETE from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id'";
executeupdate($sql);
$sql = "DELETE from am_leakcheck_problems where leak_id='$leak_id' and problem_id='$problem_id'";
executeupdate($sql);

meta_redirect("fcs_sd_report_view2.php?leak_id=$leak_id");
?>