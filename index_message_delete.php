<?php
include "includes/functions.php";

$comment_id = $_GET['comment_id'];

$sql = "DELETE from comments where comment_id='$comment_id' and to_user_id='" . $SESSION_USER_ID . "'";
executeupdate($sql);

meta_redirect("index.php");
?>