<?php
include "includes/functions.php";

$act_ids = $_POST['act_ids'];

$submit1 = $_POST['submit1'];

if($submit1=="Approve Checked"){
  if(is_array($act_ids)){
    for($x=0;$x<sizeof($act_ids);$x++){
	  $act_id = $act_ids[$x];
	  $sql = "UPDATE activities set display=1, needs_approval=0 where act_id='$act_id' and user_id='" . $SESSION_USER_ID . "'";
	  executeupdate($sql);
	  $sql = "UPDATE supercali_events set ro_user_id='" . $SESSION_USER_ID . "' where act_id='$act_id'";
	  executeupdate($sql);
	}
  }
}

meta_redirect("tool_approve_inspection.php");
?>