<?php 
include "includes/functions.php";
if($SESSION_ISADMIN==0) meta_redirect("ipad_toolbox.php");

$user_id = $_GET['user_id'];
$id = $_GET['id'];
$cat_id = $_GET['cat_id'];
$action = $_GET['action'];

if($action=="single"){
  $sql = "SELECT tools_available, tools_active from users where user_id='$user_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  
  $tools_available = $record['tools_available'];
  $tools_active = $record['tools_active'];
  
  if(go_reg("," . $id . ",", $tools_available)){
    $tools_available = go_reg_replace("," . $id . ",", "", $tools_available);
	$tools_active = go_reg_replace("," . $id . ",", "", $tools_active);
  }
  else {
    $tools_available.= "," . $id . ",";
  }
  $sql = "UPDATE users set tools_available = '$tools_available', tools_active='$tools_active' where user_id='$user_id'";
  executeupdate($sql);
}

if($action=="allon"){
  $sql = "SELECT tools_available from users where user_id='$user_id'";
  $tools_available = getsingleresult($sql);
  $sql = "SELECT id from toolbox_items where cat_id='$cat_id' and master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x_id = $record['id'];
	if(!go_reg("," . $x_id . ",", $tools_available)){
	  $tools_available .= "," . $x_id . ",";
	}
  }
  $sql = "UPDATE users set tools_available = '$tools_available' where user_id='$user_id'";
  executeupdate($sql);
}

if($action=="alloff"){
  $sql = "SELECT tools_available, tools_active from users where user_id='$user_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  
  $tools_available = $record['tools_available'];
  $tools_active = $record['tools_active'];
  
  $sql = "SELECT id from toolbox_items where cat_id='$cat_id' and master_id='" . $SESSION_MASTER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x_id = $record['id'];
	if(go_reg("," . $x_id . ",", $tools_available)){
	  $tools_available = go_reg_replace("," . $x_id . ",", "", $tools_available);
	  $tools_active = go_reg_replace("," . $x_id . ",", "", $tools_active);
	}
  }
  $sql = "UPDATE users set tools_available = '$tools_available', tools_active='$tools_active' where user_id='$user_id'";
  executeupdate($sql);
}

meta_redirect("ipad_toolbox_admin.php?user_id=$user_id");
?>
