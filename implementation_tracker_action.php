<?php
include "includes/functions.php";
 // this is just for fcs login

$master_id = go_escape_string($_GET['master_id']);
$stage = go_escape_string($_GET['stage']);
$turnon = go_escape_string($_GET['turnon']);
$imp_complete = go_escape_string($_GET['imp_complete']);

$stagefield = "imp_" . $stage;
$stagedate = $stagefield . "_date";

if($turnon==1) {
  $newdate = "now()";
}
else {
  $newdate = "'0000-00-00'";
}

$sql = "UPDATE master_list set $stagefield='$turnon', $stagedate=$newdate where master_id='$master_id'";
executeupdate($sql);

meta_redirect("implementation_tracker.php?imp_complete=$imp_complete");
?>
  
