<?php
include "includes/functions.php";

$leak_ids = $_POST['leak_ids'];
$submit1 = $_POST['submit1'];

if($submit1=="Archive Selected"){
  if(is_array($leak_ids)){
    for($x=0;$x<sizeof($leak_ids);$x++){
	  $leak_id = $leak_ids[$x];
	  $sql = "SELECT archive, status from am_leakcheck where leak_id='$leak_id'";
      $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $archive = $record['archive'];
	  $status = $record['status'];
	  if($status=="Resolved"){
	    $sql = "UPDATE am_leakcheck set status='Confirmed', confirm_date=now() where leak_id='$leak_id'";
		executeupdate($sql);
	  }
      if($archive==0){
        $newarchive = 1;
      }
      else {
        $newarchive = 0;
      }

      $sql = "UPDATE am_leakcheck set archive='$newarchive' where leak_id='$leak_id'";
      executeupdate($sql);
	}
  }
}

if($submit1=="Delete Selected"){
  if(is_array($leak_ids)){
    for($x=0;$x<sizeof($leak_ids);$x++){
	  $leak_id = $leak_ids[$x];
	  $sql = "DELETE FROM am_leakcheck WHERE leak_id='$leak_id'";
      executeupdate($sql);
      $sql = "DELETE FROM am_leakcheck_extras WHERE leak_id='$leak_id'";
      executeupdate($sql);
      $sql = "DELETE FROM am_leakcheck_notes WHERE leak_id='$leak_id'";
      executeupdate($sql);
      $sql = "DELETE FROM am_leakcheck_photos WHERE leak_id='$leak_id'";
      executeupdate($sql);
	  $sql = "DELETE FROM am_leakcheck_resourcelog WHERE leak_id='$leak_id'";
      executeupdate($sql);

      $sql = "SELECT event_id from supercali_events where leak_id='$leak_id'";
      $event_id = getsingleresult($sql);
      if($event_id != ""){
        $sql = "DELETE from supercali_dates where event_id='$event_id'";
        executeupdate($sql);
        $sql = "DELETE from supercali_events where event_id='$event_id'";
        executeupdate($sql);
      }
	}
  }
}

if($submit1=="XML"){
  $_SESSION['sess_xml_leak_id'] = $leak_ids;
  meta_redirect("fcs_sd_report_xml.php");
}

if($submit1=="CEASE2"){
  $_SESSION['sess_xml_leak_id'] = $leak_ids;
  meta_redirect("fcs_sd_report_cease2.php");
}

if($submit1=="excel"){
  $_SESSION['sess_xml_leak_id'] = $leak_ids;
  meta_redirect("fcs_sd_report_excel.php");
}

if($submit1=="excel2"){
  $_SESSION['sess_xml_leak_id'] = $leak_ids;
  meta_redirect("fcs_sd_report_excel2.php");
}

if($submit1=="excel2csv"){
  $_SESSION['sess_xml_leak_id'] = $leak_ids;
  meta_redirect("fcs_sd_report_excel2_csv.php");
}

if($submit1=="timberline"){
  $_SESSION['sess_xml_leak_id'] = $leak_ids;
  meta_redirect("fcs_sd_report_timberline.php");
}

meta_redirect("fcs_sd_report.php");
?>