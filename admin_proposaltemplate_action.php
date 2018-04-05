<?php
include "includes/functions.php";

$proposal_template = go_escape_string($_POST['proposal_template']);

$sql = "UPDATE master_list set proposal_template=\"$proposal_template\" where master_id='" . $SESSION_MASTER_ID . "'";
executeupdate($sql);

meta_redirect("admin_proposaltemplate.php");
?>