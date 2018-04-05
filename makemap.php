<?php
include "includes/functions.php";
$boxtype = $_GET['boxtype'];
$pid = $_GET['pid'];

$ids = array($pid);

$_SESSION['list_prospect_id'] = "";
$_SESSION['list_property_id'] = "";
$_SESSION['list_contact_id'] = "";
$_SESSION['list_boxtype'] = $boxtype;

if($boxtype=="prospect") $_SESSION['list_prospect_id'] = $ids;
if($boxtype=="property") $_SESSION['list_property_id'] = $ids;

meta_redirect("map_generic.php");
?>