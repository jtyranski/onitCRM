<?php
include "includes/functions.php";

$tool_id = $_GET['tool_id'];
$x = $_GET['x'];
$y = $_GET['y'];
$x = go_reg_replace("px", "", $x);
$y = go_reg_replace("px", "", $y);
$user_id = $SESSION_USER_ID;

if(go_reg("X\_", $tool_id)){ // existing one on the top
  if($y > 100){
    $tool_id = go_reg_replace("X\_", "", $tool_id);
	$sql = "SELECT tools_active from users where user_id='$user_id'";
    $tools_active = getsingleresult($sql);
	
	$tools_active = go_reg_replace("," . $tool_id . ",", "", $tools_active);
	
	$sql = "UPDATE users set tools_active=\"$tools_active\" where user_id='$user_id'";
    executeupdate($sql);
  }
}
else { // new one on bottom
  if($y > -240 && $y < -100){ // about the top nav bar
    $sql = "SELECT tools_active from users where user_id='$user_id'";
    $tools_active = getsingleresult($sql);
  
    $tools_active .= "," . $tool_id . ",";
  
    $sql = "UPDATE users set tools_active=\"$tools_active\" where user_id='$user_id'";
    executeupdate($sql);
  }
}

meta_redirect("toolbox_edit.php");
?>