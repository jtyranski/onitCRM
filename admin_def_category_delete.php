<?php
include "includes/functions.php";

$category_id = go_escape_string($_GET['category_id']);

$sql = "UPDATE def_list_master set category_id=0 where category_id='$category_id' and master_id='" . $SESSION_MASTER_ID . "'";
executeupdate($sql);

$sql = "SELECT id, category_ids from def_list_master where category_ids like '%,$category_id,%'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $id = $record['id'];
  $category_ids = $record['category_ids'];
  $category_ids = go_reg_replace("\," . $category_id . "\,", "", $category_ids);
  if($category_ids=="") $category_ids = ",0,";
  $sql = "UPDATE def_list_master set category_ids=\"$category_ids\" where id='$id'";
  executeupdate($sql);
}

$sql = "DELETE from def_list_categories where category_id='$category_id' and master_id='$master_id'";
executeupdate($sql);

meta_redirect("admin_def_category.php");
?>
