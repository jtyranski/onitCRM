<?php
include "includes/functions.php";

$user_id = go_escape_string($_GET['user_id']);
$prospect_id = go_escape_string($_GET['prospect_id']);

$sql = "DELETE from am_users where user_id='$user_id' and prospect_id='" . $prospect_id . "'";
executeupdate($sql);
meta_redirect("frame_prospect_portalusers.php?prospect_id=$prospect_id");
?>