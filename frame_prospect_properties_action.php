<?php
include "includes/functions.php";


$propertyaction = $_POST['propertyaction'];
$prospect_id = $_POST['prospect_id'];


switch($propertyaction){
  case "move":{
    $_SESSION[$sess_header . '_move_property'] = $_POST['property_ids'];
	meta_redirect("contacts.php", 0, 1);
	break;
  }
  
  case "delete":{
    for($x=0;$x<sizeof($_POST['property_ids']);$x++){
	  $prop_id = $_POST['property_ids'][$x];
	  
	  $sql = "SELECT b.master_id from properties a, prospects b where a.prospect_id = b.prospect_id and a.property_id='$prop_id'";
	  $test = getsingleresult($sql);
	  if($test == $SESSION_MASTER_ID){
	    $sql = "UPDATE properties set display=0 where property_id='$prop_id'";
        executeupdate($sql);

        $sql = "DELETE from am_leakcheck where property_id='$prop_id'";
        executeupdate($sql);
        $sql = "DELETE from activities where property_id='$prop_id'";
        executeupdate($sql);
        $sql = "DELETE from supercali_events where property_id='$prop_id'";
        executeupdate($sql);
        $sql = "DELETE from opportunities where property_id='$prop_id'";
        executeupdate($sql);
        $sql = "DELETE from notes where property_id='$prop_id'";
        executeupdate($sql);
        $sql = "DELETE from drawings where property_id='$prop_id'";
        executeupdate($sql);

        $sql = "SELECT prospect_id from properties where property_id='$prop_id'";
        $x_prospect_id = getsingleresult($sql);

        $sql = "SELECT count(*) from properties where prospect_id='$x_prospect_id' and display=1 and corporate=0";
        $prop_count = getsingleresult($sql);
        $sql = "UPDATE prospects set properties='$prop_count' where prospect_id='$x_prospect_id'";
        executeupdate($sql);
	  }
	}
	meta_redirect("frame_prospect_properties.php?prospect_id=$prospect_id");
	break;
  }
}

?>