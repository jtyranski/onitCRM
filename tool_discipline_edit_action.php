<?php
include "includes/functions.php";

$dis_id = go_escape_string($_POST['dis_id']);
$discipline = go_escape_string($_POST['discipline']);
$membrane_filler = go_escape_string($_POST['membrane_filler']);
$flashings_filler = go_escape_string($_POST['flashings_filler']);
$sheetmetal_filler = go_escape_string($_POST['sheetmetal_filler']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  if($dis_id=="new"){
    $sql = "INSERT into disciplines(discipline) values ('a')";
	executeupdate($sql);
	$dis_id = go_insert_id();
  }
  $sql = "UPDATE disciplines set discipline=\"$discipline\", membrane_filler=\"$membrane_filler\", flashings_filler=\"$flashings_filler\", sheetmetal_filler=\"$sheetmetal_filler\"
  where dis_id=\"$dis_id\"";
  executeupdate($sql);
}

meta_redirect("tool_discipline.php");
?>