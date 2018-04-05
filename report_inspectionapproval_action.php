<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$pre_approval_reason = go_escape_string($_POST['pre_approval_reason']);
$final_approval_reason = go_escape_string($_POST['final_approval_reason']);
$submit1 = go_escape_string($_POST['submit1']);
$type = go_escape_string($_POST['type']);

if($submit1 != ""){
  if($type=="pre_approve"){
    if($submit1=="Approve") $pre_approval = 1;
    if($submit1=="Deny") $pre_approval = -1;
  
    $sql = "UPDATE properties set pre_approval='$pre_approval', pre_approval_reason=\"$pre_approval_reason\", 
    ready_for_pre_approval=0 where property_id='$property_id'";
    executeupdate($sql);
    if($submit1=="Approve"){
      $sql = "UPDATE activities set complete=1 where property_id='$property_id' and event='Inspection'";
      executeupdate($sql);
	}
  }
  
  if($type=="final_approve"){
    if($submit1=="Approve"){
	  $sql = "UPDATE properties set final_approval=1, ready_for_email=0, pre_approval_reason='', final_approval_reason=''
	  where property_id='$property_id'";
	  executeupdate($sql);
	  //$sql = "UPDATE activities set complete=1 where property_id='$property_id' and event='Inspection'";
      //executeupdate($sql);
	}
	if($submit1=="Deny"){
	  $sql = "UPDATE properties set final_approval=-1, ready_for_email=0, ready_for_pre_approval=0, 
	  final_approval_reason=\"$final_approval_reason\" where property_id='$property_id'";
	  executeupdate($sql);
	  $sql = "UPDATE activities set complete=0 where property_id='$property_id' and event='Inspection' and display=1";
	  executeupdate($sql);
	}
	if($submit1=="Submit For Final"){
	  $sql = "UPDATE properties set final_approval=0, ready_for_email=1, text_alert_sent=0 where property_id='$property_id'";
	  executeupdate($sql);
	}
  }
}

meta_redirect("report_inspectionapproval.php");
?>
