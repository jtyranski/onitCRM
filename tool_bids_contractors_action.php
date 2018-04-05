<?php
include "includes/functions.php";

$bid_id = go_escape_string($_POST['bid_id']);
$prospect_ids = $_POST['prospect_ids'];

$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "UPDATE bids set stage=2 where bid_id='$bid_id'";
  executeupdate($sql);
  if(is_array($prospect_ids)){
    for($x=0;$x<sizeof($prospect_ids);$x++){
	  $prospect_id = go_escape_string($prospect_ids[$x]);
	  $code = secretCode();
	  
	  $sql = "INSERT into bids_to_invites(bid_id, prospect_id, code) values(\"$bid_id\", \"$prospect_id\", \"$code\")";
	  executeupdate($sql);
	}
  }
  
}

meta_redirect("tool_bids_contractors.php?bid_id=$bid_id");
?>