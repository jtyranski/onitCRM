<?php
include "includes/functions.php";

$searchby = go_escape_string($_POST['searchby']);
$user_id = go_escape_string($_POST['user_id']);
$new_user_id = go_escape_string($_POST['new_user_id']);
$submit1 = go_escape_string($_POST['submit1']);

$act_id = $_POST['act_id'];

if($submit1=="Assign"){
  if(is_array($act_id)){
    for($x=0;$x<sizeof($act_id);$x++){
	  $sql = "UPDATE activities set user_id='$new_user_id' where act_id='" . $act_id[$x] . "'";
	  executeupdate($sql);
	  $sql = "UPDATE supercali_events set ro_user_id='$new_user_id' where act_id='" . $act_id[$x] . "' and act_id != 0";
	  executeupdate($sql);
	}
  }
}

if($submit1=="Delete Checked"){
  if(is_array($act_id)){
    for($x=0;$x<sizeof($act_id);$x++){
	  if($act_id[$x] != 0){
	    $sql = "DELETE from activities where act_id='" . $act_id[$x] . "'";
	    executeupdate($sql);
	    $sql = "DELETE from supercali_events where act_id='" . $act_id[$x] . "' and act_id != 0";
	    executeupdate($sql);
	  }
	}
  }
}

meta_redirect("calendar_activities.php?user_id=$user_id&searchby=$searchby");
?>