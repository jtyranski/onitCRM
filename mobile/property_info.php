<?php
include "includes/functions.php";
$property_id = $_GET['property_id'];
$sql = "SELECT property_type from properties where property_id='$property_id'";
$property_type = getsingleresult($sql);
switch($property_type){
  case "Beazer":{
    meta_redirect("property_info_beazer.php?property_id=$property_id");
	break;
  }
  case "Beazer B":{
    meta_redirect("property_info_beazer.php?property_id=$property_id");
	break;
  }
  case "Manville":{
    meta_redirect("property_info_manville.php?property_id=$property_id");
	break;
  }
  case "Non-PFRI":{
    meta_redirect("property_info_nonpfri.php?property_id=$property_id");
	break;
  }
}
?>