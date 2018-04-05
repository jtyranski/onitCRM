<?php
include "includes/functions.php";

$code = go_escape_string($_POST['code']);
$bid = go_escape_string($_POST['bid']);
$notes = go_escape_string($_POST['notes']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  $bid = remove_non_numeric($bid);
  $sql = "UPDATE bids_to_invites set bid=\"$bid\", notes=\"$notes\" where code=\"$code\"";
  executeupdate($sql);
}

meta_redirect("public_view_bid.php?$code");
?>