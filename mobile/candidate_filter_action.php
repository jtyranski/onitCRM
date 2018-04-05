<?php
include "includes/functions.php";

//$import = go_escape_string($_POST['import_identifier']);
$region = go_escape_string($_POST['region']);
$prospect_type = go_escape_string($_POST['prospect_type']);
$zip = go_escape_string($_POST['zip']);
$distance = go_escape_string($_POST['distance']);
$zip = go_reg_replace("\-.*", "", $zip);

//$_SESSION['cand_import'] = $import;
$_SESSION['cand_region'] = $region;
$_SESSION['cand_prospect_type'] = $prospect_type;
$_SESSION['cand_zip'] = $zip;
$_SESSION['cand_distance'] = $distance;

meta_redirect("candidate.php");
?>
