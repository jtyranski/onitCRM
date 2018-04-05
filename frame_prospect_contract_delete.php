<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_GET['prospect_id']);

if($SESSION_ISADMIN || $SESSION_USER_LEVEL == "Manager"){
  $sql = "SELECT contractdoc from prospects where prospect_id='$prospect_id'";
  $contractdoc = getsingleresult($sql);
  if($contractdoc != "") @unlink("uploaded_files/contracts/$contractdoc");
  $sql = "UPDATE prospects set contractdoc='' where prospect_id='$prospect_id'";
  executeupdate($sql);
}

meta_redirect("frame_prospect_contract.php?prospect_id=$prospect_id");
?>