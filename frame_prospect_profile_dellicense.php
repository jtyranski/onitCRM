<?php
include "includes/functions.php";

$prospect_id = $_GET['prospect_id'];
$id = $_GET['id'];

$sql = "DELETE from prospects_licenses where id='$id' and prospect_id='$prospect_id'";
executeupdate($sql);

meta_redirect("frame_prospect_profile.php?prospect_id=$prospect_id");
?>