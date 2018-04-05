<?php
include "includes/functions.php";

$id = go_escape_string($_GET['id']);


if($id != "new"){
  $sql = "SELECT master_id from def_list_master where id ='$id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
}

$sql = "UPDATE def_list_master set visible=0 where id='$id'";
executeupdate($sql);

meta_redirect("admin_def.php");
?>