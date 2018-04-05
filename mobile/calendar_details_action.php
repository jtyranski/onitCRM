<?php
include "includes/functions.php";

$act_id = go_escape_string($_POST['act_id']);
$priority = go_escape_string($_POST['priority']);
$submit1 = go_escape_string($_POST['submit1']);
$regarding = go_escape_string($_POST['regarding']);
if($submit1 != ""){
  $sql = "UPDATE activities set priority='$priority', regarding=\"$regarding\" where act_id='$act_id'";
  executeupdate($sql);
}

meta_redirect("calendar.php");
?>
