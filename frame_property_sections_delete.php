<?php
include "includes/functions.php";
$property_id = $_GET['property_id'];
$section_id = $_GET['section_id'];

$sql = "UPDATE sections set display=0 where section_id='$section_id' and property_id='$property_id'";
executeupdate($sql);
meta_redirect("frame_property_sections.php?property_id=$property_id");
?>