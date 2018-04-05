<?php include "includes/functions.php"; ?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$property_id=$_GET['property_id'];
$uplink = "../";

  $sql = "SELECT property_type from properties where property_id='$property_id'";
  $property_type = getsingleresult($sql);
  if($property_type=="Manville") { 
	$report_array[] = "<a href='" . $uplink . "report_manville.php?property_id=$property_id' target='_blank'>Generate Risk Report</a>";
	$sql = "SELECT count(*) from roof_report where property_id='$property_id'";
	$test = getsingleresult($sql);
	if($test) $report_array[] = "<a href='" . $uplink . "delete_roof_report.php?property_id=$property_id' target='_blank'>Delete Risk Report</a>";
	$document_array[] = "<a href='" . $uplink . "document_manville_display.php?property_id=$property_id' target='_blank'>Manville Notice</a>";
    $document_array[] = "<a href='" . $uplink . "document_pfri.php?property_id=$property_id' target='_blank'>Inspection Report</a>";
	$document_array[] = "<a href='" . $uplink . "pfri_coop.php?property_id=$property_id&property_type=Manville' target='_blank'>PFRI Co-Op</a>";
  }
  if($property_type=="Beazer" || $property_type=="Beazer B") { 
    //$report_array[] = "<a href='report_beazer.php?property_id=$property_id'>Generate Risk Report</a>";
	// these are now based off section, not property
	$sql = "SELECT count(*) from roof_report_beazer where property_id='$property_id'";
	$test = getsingleresult($sql);
	if($test) $report_array[] = "<a href='" . $uplink . "delete_roof_report.php?property_id=$property_id target='_blank''>Delete Risk Report</a>";
	$document_array[] = "<a href='" . $uplink . "document_beazer_display.php?property_id=$property_id' target='_blank'>Beazer Notice</a>";
	$document_array[] = "<a href='" . $uplink . "document_beazer_gpdoc_display.php?property_id=$property_id' target='_blank'>Gilman Pastor Doc</a>";
	$document_array[] = "<a href='" . $uplink . "pfri_coop.php?property_id=$property_id&property_type=Beazer' target='_blank'>PFRI Co-Op</a>";
  }
  
  $document_array[] = "<a href='" . $uplink . "property_contract.php?property_id=$property_id' target='_blank'>Contract</a>";
  $document_array[] = "<a href='" . $uplink . "proposal_image_doc.php?property_id=".$property_id."' target='_blank'>Compliance Document</a>";
  $document_array[] = "<a href='" . $uplink . "service_work_order.php?property_id=".$property_id."' target='_blank'>Service Work Order</a>";
  $sql = "SELECT count(*) from bid_proposal where property_id='$property_id'";
  $test = getsingleresult($sql);
  if($test) $document_array[] = "<a href='" . $uplink . "proposal_bid_list.php?property_id=".$property_id."' target='_blank'>Bid Proposal</a>";

  $sql = "SELECT count(*) from opm_survey where property_id='$property_id'";
  $test = getsingleresult($sql);
  if($test) $document_array[] = "<a href='" . $uplink . "roofoptions_opm_survey.php?property_id=".$property_id."' target='_blank'>OPM Survey</a>";

  $sql = "SELECT count(b.id) from opm_survey a, opm_survey_answers b where a.survey_id=b.survey_id and a.property_id='$property_id'";
  $test = getsingleresult($sql);
  if($test) $document_array[] = "<a href='" . $uplink . "roofoptions_opm_survey_answerlist.php?property_id=".$property_id."' target='_blank'>OPM Survey Answers</a>";
  
  $sql = "SELECT section_type from sections where property_id='$property_id' and display=1 group by section_type";
  $r_section_type = executequery($sql);
  while($rec_section_type = go_fetch_array($r_section_type)){
    $document_array[] = "<a href='" . $uplink . "report_print_presentation_property.php?property_id=".$property_id."&section_type=" . $rec_section_type['section_type'] . "' target='_blank'>Print (" . $rec_section_type['section_type'] . ")</a>";
  }
  
  $sql = "SELECT count(*) as count from activities where property_id='$property_id' and event='Production Meeting' and display=1";
  $test = getsingleresult($sql);
  if($test){
    $document_array[] = "<a href='" . $uplink . "project_summary_cards.php?property_id=".$property_id . "' target='_blank'>Project Summary Cards</a>";
  }
  
  $document_array[] = "<a href='" . $uplink . "production_meeting_itinerary.php?property_id=".$property_id . "' target='_blank'>Production Itinerary</a>";
  
for($x=0;$x<sizeof($document_array);$x++){
  echo $document_array[$x] . "<br>";
}
?>
