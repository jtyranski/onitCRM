<?php
include "includes/functions.php";

$opp_product_id = go_escape_string($_GET['opp_product_id']);

$sql = "SELECT master_id from opportunities_items where opp_product_id='$opp_product_id'";
$master_id = getsingleresult($sql);
if($master_id != $SESSION_MASTER_ID) exit;

$sql = "DELETE from opportunities_items where opp_product_id='$opp_product_id'";
executeupdate($sql);

meta_redirect("admin_opportunities.php");
?>