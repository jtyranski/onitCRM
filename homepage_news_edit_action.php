<?php
include "includes/functions.php";

$message_id = go_escape_string($_POST['message_id']);
$message = go_escape_string($_POST['message']);
$display = go_escape_string($_POST['display']);
$submit1 = go_escape_string($_POST['submit1']);

if($submit1 != ""){

  if($message_id=="new"){
    $sql = "INSERT into homepage_messages(message_date) values(now())";
	executeupdate($sql);
	$message_id = go_insert_id();
  }
  
  $sql = "UPDATE homepage_messages set message=\"$message\", display='$display' where message_id='$message_id'";
  executeupdate($sql);
}

meta_redirect("homepage_news.php");
?>
