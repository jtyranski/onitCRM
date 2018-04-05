<?php
include "includes/functions.php";

$id = go_escape_string($_POST['id']);

$material = go_escape_string($_POST['material']);
$unit = go_escape_string($_POST['xunit']);
$cost = go_escape_string($_POST['cost']);

$submit1 = go_escape_string($_POST['submit1']);
$category_ids = $_POST['category_ids'];

if($id != "new"){
  $sql = "SELECT master_id from material_list where id ='$id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
}

if($submit1 == "Update"){
  if($id=="new"){
    $sql = "INSERT into material_list(master_id) values('" . $SESSION_MASTER_ID . "')";
	executeupdate($sql);
	$id = go_insert_id();
  }
  $x_category_ids = "";
  if(is_array($category_ids)){
    for($x=0;$x<sizeof($category_ids);$x++){
	  $x_category_ids .= "," . $category_ids[$x] . ",";
	}
  }
  if($x_category_ids=="") $x_category_ids = ",0,";
  
  $sql = "UPDATE material_list set material=\"$material\", unit=\"$unit\", cost=\"$cost\", category_ids=\"$x_category_ids\"
  where id='$id' and master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
}
meta_redirect("admin_mat.php");
?>