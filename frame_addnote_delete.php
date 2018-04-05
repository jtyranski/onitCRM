<?php
include "includes/functions.php";

$property_id = go_escape_string($_GET['property_id']);
$prospect_id = go_escape_string($_GET['prospect_id']);
$note_id = go_escape_string($_GET['note_id']);

$sql = "SELECT user_id from notes where note_id='$note_id'";
$user_id = getsingleresult($sql);
if($user_id==$SESSION_USER_ID || $SESSION_ISADMIN==1){
  $sql = "DELETE from notes where note_id='$note_id'";
  executeupdate($sql);
}

$redirect = "frame_prospect_notes.php?prospect_id=$prospect_id";
if($property_id != 0) $redirect = "frame_property_notes.php?property_id=$property_id";
meta_redirect($redirect);
?>
