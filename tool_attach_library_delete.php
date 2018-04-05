<?php
include "includes/functions.php";

$attach_id = go_escape_string($_GET['attach_id']);

if($attach_id != "new"){
  $sql = "SELECT master_id from attachment_library where attach_id='$attach_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}

$sql = "SELECT filename from attachment_library where attach_id='$attach_id'";
$filename = getsingleresult($sql);

if($filename != "") @unlink("uploaded_files/attachment_library/$filename");

$sql = "DELETE from attachment_library where attach_id='$attach_id'";
executeupdate($sql);

meta_redirect("tool_attach_library.php");
?>