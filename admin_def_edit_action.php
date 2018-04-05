<?php
include "includes/functions.php";

$id = go_escape_string($_POST['id']);

$def_name = go_escape_string($_POST['def_name']);
$def = go_escape_string($_POST['def']);
$corrective_action = go_escape_string($_POST['corrective_action']);
//$category_id = go_escape_string($_POST['category_id']);
$def_name_spanish = go_escape_string($_POST['def_name_spanish']);
$category_ids = $_POST['category_ids'];

$submit1 = go_escape_string($_POST['submit1']);

if($id != "new"){
  $sql = "SELECT master_id from def_list_master where id ='$id'";
  $master_id = getsingleresult($sql);
  if($master_id != $SESSION_MASTER_ID) exit;
}

if($submit1 == "Update"){
  if($id=="new"){
    $sql = "INSERT into def_list_master(master_id) values('" . $SESSION_MASTER_ID . "')";
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
  
  $sql = "UPDATE def_list_master set def_name=\"$def_name\", def=\"$def\", corrective_action=\"$corrective_action\", category_ids=\"$x_category_ids\", def_name_spanish=\"$def_name_spanish\"
  where id='$id' and master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
}
meta_redirect("admin_def.php");
?>