<?php
include "includes/functions.php";

$attach_id = go_escape_string($_POST['attach_id']);
$description = go_escape_string($_POST['description']);
$submit1 = go_escape_string($_POST['submit1']);

if($attach_id != "new"){
  $sql = "SELECT master_id from attachment_library where attach_id='$attach_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}

if($submit1 != ""){
  if($attach_id=="new"){
    $sql = "INSERT into attachment_library(master_id) values('" . $SESSION_MASTER_ID . "')";
	executeupdate($sql);
	$attach_id = go_insert_id();
  }
  
  $sql = "UPDATE attachment_library set description=\"$description\" where attach_id='$attach_id'";
  executeupdate($sql);
  
  if (is_uploaded_file($_FILES['attach']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['attach']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attach']['tmp_name'], $UPLOAD . "attachment_library/". $filename);
	
	$sql = "UPDATE attachment_library set filename='$filename' where attach_id='$attach_id'";
	executeupdate($sql);
  }
}

meta_redirect("tool_attach_library.php");
  
  ?>