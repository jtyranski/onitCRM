<?php
include "includes/functions.php";

$property_id = $_POST['property_id'];
$prospect_id = $_POST['prospect_id'];

$note = go_escape_string($_POST['note']);
$submit1 = $_POST['submit1'];

if($submit1 != ""){
  $sql = "INSERT into notes(prospect_id, property_id, user_id, date, event, note) values(
  '$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', now(), 'Note', \"$note\")";
  executeupdate($sql);
}

meta_redirect("property_notes.php?property_id=$property_id");
?>
  