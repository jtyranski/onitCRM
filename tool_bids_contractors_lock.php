<?php
include "includes/functions.php";

$bid_id = go_escape_string($_GET['bid_id']);
$cont_id = go_escape_string($_GET['cont_id']);

$sql = "SELECT bidlock from bids_to_invites where bid_id=\"$bid_id\" and prospect_id=\"$cont_id\"";
$bidlock = getsingleresult($sql);

if($bidlock==1){
  $newlock = 0;
}
else {
  $newlock = 1;
}

$sql = "UPDATE bids_to_invites set bidlock='$newlock' where bid_id=\"$bid_id\" and prospect_id=\"$cont_id\"";
executeupdate($sql);

meta_redirect("tool_bids_contractors.php?bid_id=$bid_id");
?>