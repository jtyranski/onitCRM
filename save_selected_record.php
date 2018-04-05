<?php
include "includes/functions.php";

$record = $_GET['record'];
$type = $_GET['type'];
$pid = $_GET['pid'];

$_SESSION['ro_saved_selected_record'] = $record;
if($type == "company") $redirect = "view_company.php?prospect_id=$pid";
if($type == "property") $redirect = "view_property.php?property_id=$pid";

meta_redirect($redirect);
?>