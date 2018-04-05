<?php
include "includes/functions.php";

$property_id = $_GET['property_id'];
$drawing_id = $_GET['drawing_id'];

$_SESSION[$sess_header . '_document_attach'] = $drawing_id;

meta_redirect("direct_email.php?property_id=$property_id", 0, 1);
?>