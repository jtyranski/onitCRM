<?php
include "includes/functions.php";

$id = go_escape_string($_POST['id']);
$template_name = go_escape_string($_POST['template_name']);
$template = go_escape_string($_POST['template']);
$submit1 = go_escape_string($_POST['submit1']);

if($id != "new"){
  $sql = "SELECT master_id from document_template where id='$id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}

if($submit1 != ""){
  if($id=="new"){
    $sql = "INSERT into document_template(master_id) values('" . $SESSION_MASTER_ID . "')";
	executeupdate($sql);
	$id = go_insert_id();
  }
  
  $sql = "UPDATE document_template set template_name=\"$template_name\", template=\"$template\" where id='$id'";
  executeupdate($sql);
  
}

meta_redirect("admin_doc_template.php");
  
  ?>