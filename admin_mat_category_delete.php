<?php
include "includes/functions.php";

$category_id = go_escape_string($_GET['category_id']);

$sql = "SELECT id, category_ids from material_list where category_ids like '%,$category_id,%'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $id = $record['id'];
  $category_ids = $record['category_ids'];
  $category_ids = go_reg_replace("\," . $category_id . "\,", "", $category_ids);
  if($category_ids=="") $category_ids = ",0,";
  $sql = "UPDATE material_list set category_ids=\"$category_ids\" where id='$id'";
  executeupdate($sql);
}

$sql = "DELETE from material_list_categories where category_id='$category_id' and master_id='$master_id'";
executeupdate($sql);

meta_redirect("admin_mat_category.php");
?>
