<?php
include "includes/functions.php";

$leak_id = go_escape_string($_POST['leak_id']);
$approval_reason = go_escape_string($_POST['approval_reason']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
    if($submit1=="Approve") $approval_response = 1;
    if($submit1=="Deny") $approval_response = -1;
  
    $sql = "UPDATE am_leakcheck set approval_response='$approval_response', approval_reason=\"$approval_reason\", 
    ready_for_approval=0 where leak_id='$leak_id'";
    executeupdate($sql);

  

}

meta_redirect("report_sdapproval.php");
?>
