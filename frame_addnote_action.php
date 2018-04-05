<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$prospect_id = go_escape_string($_POST['prospect_id']);
$regarding = go_escape_string($_POST['regarding']);
$note = go_escape_string($_POST['note']);
$note_id = go_escape_string($_POST['note_id']);

$submit1 = go_escape_string($_POST['submit1']);
if($submit1 != ""){
  if($note_id=="new"){
    $sql = "INSERT into notes(prospect_id, property_id, user_id, date, event, regarding, note) values(
    '$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', now(), 'Note', \"$regarding\", \"$note\")";
    executeupdate($sql);
    $sql = "SELECT note_id from notes where property_id='$property_id' order by note_id desc limit 1";
    $note_id = getsingleresult($sql);
  }
  else {
    $sql = "UPDATE notes set regarding=\"$regarding\", note=\"$note\" where note_id='$note_id'";
	executeupdate($sql);
  }
  if (is_uploaded_file($_FILES['attachment']['tmp_name']))
    {
	
	$ext = explode(".", $_FILES['attachment']['name']);
  	$ext = strtolower(array_pop($ext));
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['attachment']['tmp_name'], $UPLOAD . "attachments/". $filename);
	
	//$note .= "\n\n<a href='" . $CORE_URL . "uploaded_files/attachments/" . $filename . "' target='_blank'>Attachment</a>";
	$sql = "UPDATE notes set attachment='$filename' where note_id='$note_id'";
	executeupdate($sql);
  }

  
}

$redirect = "frame_prospect_notes.php?prospect_id=$prospect_id";
if($property_id != 0) $redirect = "frame_property_notes.php?property_id=$property_id";
meta_redirect($redirect);
?>
