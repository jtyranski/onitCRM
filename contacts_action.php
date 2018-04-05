<?php
include "includes/functions.php";

$ids = $_POST['ids'];
$clicktype = $_POST['clicktype'];
$boxtype = $_POST['boxtype'];

$_SESSION['list_prospect_id'] = "";
$_SESSION['list_property_id'] = "";
$_SESSION['list_contact_id'] = "";
$_SESSION['list_boxtype'] = $boxtype;
$_SESSION['list_just_id'] = $ids;

if($boxtype=="prospect") $_SESSION['list_prospect_id'] = $ids;
if($boxtype=="property") $_SESSION['list_property_id'] = $ids;
if($boxtype=="contact") $_SESSION['list_contact_id'] = $ids;

switch($clicktype){
  case "map":{
    meta_redirect("map_generic.php");
	break;
  }
  case "excel":{
    meta_redirect("excel.php");
	break;
  }
  case "print":{
    meta_redirect("print.php");
	break;
  }
  case "email":{
    meta_redirect("email.php");
	break;
  }
  case "hide":{
    meta_redirect("prospects_hide.php");
	break;
  }
  case "bulkact":{
    meta_redirect("activity_bulk.php");
	break;
  }
  case "fcsrep":{
    meta_redirect("fcsrep_email_prepare.php");
	break;
  }
  case "setup":{
    for($x=0;$x<sizeof($_SESSION['list_prospect_id']);$x++){
	  $prospect_id = $_SESSION['list_prospect_id'][$x];
	  $sql = "SELECT created_master_id from prospects where prospect_id='$prospect_id'";
	  $test = getsingleresult($sql);
	  if($test=="" || $test==0){
	    $good[] = $prospect_id;
	  }
	}
	$_SESSION['list_prospect_id'] = $good;
	
	if(sizeof($_SESSION['list_prospect_id'])==0) meta_redirect("contacts.php");
    meta_redirect("setupascontractor.php");
	break;
  }
  case "delete":{
    if($SESSION_ISADMIN==1){
      for($x=0;$x<sizeof($ids);$x++){
	    $prospect_id = $ids[$x];
	    $sql = "SELECT master_id from prospects where prospect_id='$prospect_id'";
	    $test = getsingleresult($sql);
	    if($test==$SESSION_MASTER_ID){
	      $sql = "UPDATE properties set display=0 where prospect_id='$prospect_id'";
	      executeupdate($sql);


	      $sql = "UPDATE prospects set display=0, properties=0 where prospect_id='$prospect_id'";
	      executeupdate($sql);

	      $sql = "DELETE from am_leakcheck where prospect_id='$prospect_id'";
	      executeupdate($sql);
	      $sql = "DELETE from activities where prospect_id='$prospect_id'";
	      executeupdate($sql);
	      $sql = "DELETE from supercali_events where prospect_id='$prospect_id'";
	      executeupdate($sql);
	      $sql = "DELETE from opportunities where prospect_id='$prospect_id'";
	      executeupdate($sql);
	      $sql = "DELETE from notes where prospect_id='$prospect_id'";
	      executeupdate($sql);
	      $sql = "DELETE from drawings where prospect_id='$prospect_id'";
	      executeupdate($sql);
	    }
	  }
	}
	$_SESSION['list_prospect_id'] = "";
    $_SESSION['list_property_id'] = "";
    $_SESSION['list_contact_id'] = "";
    $_SESSION['list_boxtype'] = "";
    $_SESSION['list_just_id'] = "";
	
	meta_redirect("contacts.php");
	
	
	break;
  }
}
?>