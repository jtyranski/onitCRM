<?php
include "includes/functions.php";

$message_id = go_escape_string($_GET['message_id']);

$sql = "SELECT display from homepage_messages where message_id=\"$message_id\"";
$display = getsingleresult($sql);

if($display==0){
  $nd = 1;
}
else {
  $nd = 0;
}

$sql = "UPDATE homepage_messages set display='$nd' where message_id=\"$message_id\"";
executeupdate($sql);

meta_redirect("homepage_news.php");
?>