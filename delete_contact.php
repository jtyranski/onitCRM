<?php
include "includes/functions.php";

$id = go_escape_string($_GET['id']);
$type = go_escape_string($_GET['type']);
$pid = go_escape_string($_GET['pid']);

$sql = "DELETE from contacts where id='$id'";
executeupdate($sql);

$redirect = "view_company.php?prospect_id=$pid";
if($type=="property") $redirect = "view_property.php?property_id=$pid";
meta_redirect($redirect);
?>
