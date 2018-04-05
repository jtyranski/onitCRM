<?php
include "includes/functions.php";

$leak_id = go_escape_string($_POST['leak_id']);
$problem_desc = go_escape_string($_POST['problem_desc']);
$problem_name = go_escape_string($_POST['problem_name']);
$correction = go_escape_string($_POST['correction']);

$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  $sql = "INSERT into am_leakcheck_problems(leak_id, problem_desc, correction, from_app, problem_name) values('$leak_id', \"$problem_desc\", \"$correction\", 0, \"$problem_name\")";
  executeupdate($sql);
  $problem_id = go_insert_id();
  
  meta_redirect("fcs_sd_report_addproblem_photos.php?leak_id=$leak_id&problem_id=$problem_id");
}
?>