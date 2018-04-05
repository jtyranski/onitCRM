<?php
include "includes/functions.php";

$property_id = go_escape_string($_POST['property_id']);
$section_type = go_escape_string($_POST['section_type']);
$submit1 = go_escape_string($_POST['submit1']);
$content = $_POST['content'];
$section_id = go_escape_string($_POST['section_id']);
$type = go_escape_string($_POST['type']);
$header = go_escape_string($_POST['header']);
$template_name = go_escape_string($_POST['template_name']);
$include_report = go_escape_string($_POST['include_report']);
$include = $_POST['include'];

if($include_report != 1) $include_report = 0;
if($header != 1) $header = 0;

if($submit1=="save_template"){
  if($template_name=="") $template_name = "New Template";
  $content = go_escape_string($content);
  $sql = "INSERT into document_template(master_id, template, template_name, header) values('" . $SESSION_MASTER_ID . "', \"$content\", \"$template_name\", \"$header\")";
  executeupdate($sql);
  
  meta_redirect("frame_property_document_new.php?property_id=$property_id&section_type=$section_type");
}

if($submit1=="save" || $submit1=="send" || $submit1=="sendblank" || $submit1=="saveblank"){
  include "frame_property_document_new_pdfcreate.php";
  $proposal_filename = $filename;
  
  $doc_name = "Proposal " . date("m/d/Y");
  
  $sql = "SELECT timezone, prospect_id from properties where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $timezone = $record['timezone'];
  $prospect_id = $record['prospect_id'];
  
  if($timezone=="") $timezone = 0;
  
  $sql = "SELECT date_format(now(), \"%m/%d/%Y %h:%i %p\")";
  $now = getsingleresult($sql);
  $note = "Proposal created on: $now";
  
  echo "Generating report...";
  ob_start();
  
  // check for combining pdfs
  if($include_report){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $FCS_URL . "report_print_presentation_pdf_property.php?property_id=$property_id&ipod=1&section_type=$section_type");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
	sleep(3);
	$sql = "SELECT filename from roof_report_pdf where property_id='$property_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $pdf_report_url = stripslashes($record['filename']);
	
      $fileArray[] = $UP_FCSVIEW . "uploaded_files/roofreport/$pdf_report_url";
	  //sleep(3);
	}
  }
  if($submit1=="save" || $submit1=="send") $fileArray[] = "uploaded_files/drawings/$proposal_filename";
  
  for($x=0;$x<sizeof($include);$x++){
    $sql = "SELECT filename from attachment_library where attach_id='" . $include[$x] . "' and master_id='" . $SESSION_MASTER_ID . "'";
	$f = stripslashes(getsingleresult($sql));
	$fileArray[] = "uploaded_files/attachment_library/$f";
  }
  
  if(is_array($fileArray)){
    $outputFile = secretCode() . ".pdf";
  
    $datadir = "uploaded_files/drawings/";
    $outputName = $datadir. $outputFile;

    $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
    //Add each pdf file to the end of the command
    foreach($fileArray as $file) {
      $cmd .= $file." ";
    }
    $result = shell_exec($cmd);
  }
  else {
    $outputFile = $proposal_filename;
  }
  
  ob_end_clean();
  
  $sql = "INSERT into drawings(prospect_id, property_id, section_id, type, name, file, note) values(
  '$prospect_id', '$property_id', '$section_id', \"$type\", 'Proposal', \"$outputFile\", \"$note\")";
  executeupdate($sql);
  $drawing_id = go_insert_id();
  
  
}

if($submit1=="save" || $submit1=="saveblank"){
  meta_redirect("frame_property_drawings.php?property_id=$property_id&section_type=$section_type");
}
if($submit1=="send" || $submit1=="sendblank"){
  meta_redirect("frame_property_document_email.php?property_id=$property_id&drawing_id=$drawing_id");
}

?>
