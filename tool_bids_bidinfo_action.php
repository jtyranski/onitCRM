<?php
include "includes/functions.php";

$rs_id = $_POST['rs_id'];
$rs_name = $_POST['rs_name'];
$new_rs_name = go_escape_string($_POST['new_rs_name']);
$bid_id = go_escape_string($_POST['bid_id']);
$submit1 = $_POST['submit1'];

if($submit1 != ""){
  if(is_array($rs_id)){
    for($x=0;$x<sizeof($rs_id);$x++){
	  $sql = "UPDATE bids_to_roofsystem set rs_name=\"" . go_escape_string($rs_name[$x]) . "\" where rs_id=\"" . $rs_id[$x] . "\" and bid_id='$bid_id'";
	  executeupdate($sql);
	}
  }
}

if($submit1=="Add New System"){
  $sql = "INSERT into bids_to_roofsystem (bid_id, rs_name) values('$bid_id', \"$new_rs_name\")";
  executeupdate($sql);
}

meta_redirect("tool_bids_bidinfo.php?bid_id=$bid_id");
?>