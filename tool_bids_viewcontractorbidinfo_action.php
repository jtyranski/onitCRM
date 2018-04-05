<?php
include "includes/functions.php";

$bid_id = go_escape_string($_POST['bid_id']);
$prospect_id = go_escape_string($_POST['prospect_id']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  $sql = "UPDATE bids set winning_prospect_id=\"$prospect_id\", stage=3 where bid_id=\"$bid_id\"";
  executeupdate($sql);
}

meta_redirect("tool_bids_contractors.php?bid_id=$bid_id");
?>
