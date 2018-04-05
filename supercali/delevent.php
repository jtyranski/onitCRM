<?php
include "../includes/functions.php";

$show_userid = $_GET['show_userid'];
$show_what = $_GET['show_what'];
$show_type = $_GET['show_type'];
$show_crew = $_GET['show_crew'];
$show_complete = $_GET['show_complete'];
$event_id = $_GET['event_id'];

$sql = "DELETE from supercali_dates where event_id='$event_id'";
executeupdate($sql);
$sql = "SELECT act_id from supercali_events where event_id='$event_id'";
$act_id = getsingleresult($sql);
$sql = "DELETE from activities where act_id='$act_id'";
executeupdate($sql);

$sql = "SELECT opm_id from supercali_events where event_id='$event_id'";
$opm_id = getsingleresult($sql);
if($opm_id){
  $sql = "UPDATE opm set in_q=1, pm_start='', pm_finish='', 
  ip_start='', ip_finish='', 
  fi_start='', fi_finish='', 
  produced_sqft=0
  where opm_id='$opm_id'";
  executeupdate($sql);
}
  
$sql = "DELETE from supercali_events where event_id='$event_id'";
executeupdate($sql);

meta_redirect("index.php?show_userid=$show_userid&show_what=$show_what&show_type=$show_type&show_crew=$show_crew&show_complete=$show_complete");
?>