<?php
include "includes/functions.php";

$section_id = $_POST['section_id'];
$property_id = $_POST['property_id'];
$map_reference = $_POST['map_reference'];
$submit1 = $_POST['submit1'];
$include_in_report = $_POST['include_in_report'];

if($submit1 != ""){
  for($x=0;$x<sizeof($map_reference);$x++){
    $sid = $section_id[$x];
	$mr = $map_reference[$x];
	$sql = "UPDATE sections set map_reference='$mr' where section_id='$sid'";
	executeupdate($sql);
  }
  
  if($submit1== "Include Checked Sections in Report"){
    $sql = "UPDATE sections set include_in_report=0 where property_id='$property_id'";
	executeupdate($sql);
	for($x=0;$x<sizeof($include_in_report);$x++){
	  $sql = "UPDATE sections set include_in_report=1 where section_id='" . $include_in_report[$x] . "'";
	  executeupdate($sql);
	}
  }
    
}

meta_redirect("frame_property_sections.php?property_id=$property_id");
?>