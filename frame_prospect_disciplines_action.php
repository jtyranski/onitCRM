<?php
include "includes/functions.php";

$prospect_id = go_escape_string($_POST['prospect_id']);

$dis_id = $_POST['dis_id'];
$submit1 = $_POST['submit1'];

if($submit1 != ""){
    if(is_array($dis_id)){
	  $sql = "DELETE from vendor_to_dis where prospect_id='$prospect_id'";
	  executeupdate($sql);
	  
	  for($x=0;$x<sizeof($dis_id);$x++){
	    $sql = "INSERT into vendor_to_dis(prospect_id, dis_id) values('$prospect_id', '" . $dis_id[$x] . "')";
		executeupdate($sql);
	  }
	}
}

meta_redirect("frame_prospect_disciplines.php?prospect_id=$prospect_id");
?>