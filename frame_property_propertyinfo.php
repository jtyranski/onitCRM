<?php include "includes/functions.php"; ?>
<?php $property_id = $_GET['property_id']; ?>

<?php
$user_id = $SESSION_USER_ID;
// in future, check for property type to know which table to pull info
$sql = "SELECT property_type  
 from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property_type = $record['property_type'];
switch($property_type){
  case "Manville":{
    meta_redirect("frame_property_manville.php?property_id=$property_id");
	break;
  }
  case "Beazer":{
    meta_redirect("frame_property_beazer.php?property_id=$property_id");
	break;
  }
  case "Beazer B":{
    meta_redirect("frame_property_beazer.php?property_id=$property_id");
	break;
  }
  case "Non-PFRI":{
    meta_redirect("frame_property_nonpfri.php?property_id=$property_id");
	break;
  }
  default:{
    meta_redirect("frame_property_manville.php?property_id=$property_id");
	break;
  }
}
?>
