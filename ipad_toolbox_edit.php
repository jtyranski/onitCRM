<?php
include "includes/functions.php";

$id = $_GET['id'];

$sql = "SELECT tools_active from users where user_id='" . $SESSION_USER_ID . "'";
  $tools_active = getsingleresult($sql);
  if(go_reg("," . $id . ",", $tools_active)){
    $tools_active = go_reg_replace("," . $id . ",", "", $tools_active);
  }
  else {
    $tools_active.= "," . $id . ",";
  }
  $sql = "UPDATE users set tools_active = '$tools_active' where user_id='" . $SESSION_USER_ID . "'";
  executeupdate($sql);
  
meta_redirect("ipad_toolbox.php");
?>