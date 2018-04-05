<?php
include "includes/functions.php";

$id = go_escape_string($_GET['id']);


if($id != "new"){
  $sql = "SELECT master_id from material_list where id ='$id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
}

$sql = "DELETE from material_list where id='$id'";
executeupdate($sql);

meta_redirect("admin_mat.php");
?>