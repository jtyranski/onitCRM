<?php
include "includes/functions.php";

$message_id = go_escape_string($_GET['message_id']);

$sql = "DELETE from homepage_messages where message_id=\"$message_id\"";
executeupdate($sql);

meta_redirect("homepage_news.php");
?>