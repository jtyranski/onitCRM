<?php
include "includes/functions.php";

$user_id = go_escape_string($_GET['user_id']);
$prospect_id = go_escape_string($_GET['prospect_id']);

$sql = "DELETE from users where user_id='$user_id' and resource_id='$prospect_id' and master_id='" . $SESSION_MASTER_ID . "'";
executeupdate($sql);

meta_redirect("frame_prospect_resourceusers.php?prospect_id=$prospect_id");
?>