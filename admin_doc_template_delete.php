<?php
include "includes/functions.php";

$id = go_escape_string($_GET['id']);

if($id != "new"){
  $sql = "SELECT master_id from document_template where id='$id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}


$sql = "DELETE from document_template where id='$id'";
executeupdate($sql);

meta_redirect("admin_doc_template.php");
?>