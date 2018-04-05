<?php
include "includes/functions.php";

$id = go_escape_string($_GET['id']);

$sql = "DELETE from capitalize where id='$id' and master_id='" . $SESSION_MASTER_ID . "'";
executeupdate($sql);

meta_redirect("capitals.php");
?>