<?php
include "includes/functions.php";

$category_id = go_escape_string($_POST['category_id']);

$category_name = go_escape_string($_POST['category_name']);

$submit1 = go_escape_string($_POST['submit1']);

if($category_id != "new"){
  $sql = "SELECT master_id from material_list_categories where category_id ='$category_id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
}

if($submit1 == "Update"){
  if($category_id=="new"){
    $sql = "INSERT into material_list_categories(master_id) values('" . $SESSION_MASTER_ID . "')";
	executeupdate($sql);
	$category_id = go_insert_id();
  }
  $sql = "UPDATE material_list_categories set category_name=\"$category_name\"
  where category_id='$category_id' and master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
}
meta_redirect("admin_mat.php");
?>