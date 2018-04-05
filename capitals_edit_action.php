<?php
include "includes/functions.php";

$id = go_escape_string($_POST['id']);
$word = go_escape_string($_POST['word']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){
  if($id=="new"){
    $sql = "INSERT into capitalize(word, master_id) values(\"$word\", '" . $SESSION_MASTER_ID . "')";
  }
  else {
    $sql = "UPDATE capitalize set word=\"$word\" where id='$id' and master_id='" . $SESSION_MASTER_ID . "'";
  }
  executeupdate($sql);
}

meta_redirect("capitals.php");
?>

