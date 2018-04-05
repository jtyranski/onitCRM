<?php 
include "device.php";
//include "resize.php";
ini_set("session.gc_maxlifetime", "18000");
ini_set("session.cookie_lifetime", "18000"); 
session_start();
//ini_set("session.gc_maxlifetime", "18000"); 
ini_set("memory_limit","256M");
ini_set("allow_url_fopen", true);
ini_set("allow_url_include", true);
$currentTimeoutInSecs = ini_get("session.gc_maxlifetime");
//echo "<!-- $currentTimeoutInSecs -->\n";
//error_reporting(E_ALL);
global $DB;
$QUERY_STRING = $_SERVER['QUERY_STRING'];
$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
//$uplink = "../";

$filename_array = explode("/", $_SERVER['SCRIPT_NAME']);
$current_file_name = array_pop($filename_array);

include "variables.php";

$con = mysqli_connect($DB["host"], $DB["user"], $DB["pass"], $DB["dbName"]);


// Check connection
if (mysqli_connect_errno()) {
	echo "Failed to connect ot MySQL: ". mysqli_connect_error();
}

function go_reg_replace($pattern, $replacement, $subject){  // go_reg_replace is gone, just make own function
  //$return = preg_replace($pattern, $replacement, $subject);
  $return = str_replace($pattern, $replacement, $subject);
  return $return;
}

function go_reg($pattern, $subject){ // e*reg is gone, just make own function
  $return = preg_match("/" . $pattern . "/", $subject);
  return $return;
}




if($SESSION_USER_ID==""){
  $sql = "SELECT * from sessions_core where ip='" . $_SERVER['REMOTE_ADDR'] . "' order by id desc limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $SESSION_USER_ID = $record['user_id'];
  $SESSION_ISADMIN = $record['admin'];
  $SESSION_MASTER_ID = $record['master_id'];
  $SESSION_SUPERCALI_USER_ID = "2";
  $SESSION_OPPORTUNITIES = $record['opportunities'];
  $SESSION_MULTILOGO = stripslashes($record['multilogo']);
  $SESSION_TIMEZONE = $record['timezone'];
  $SESSION_USE_RESOURCES = $record['use_resources'];
  $SESSION_USER_LEVEL = $record['user_level'];
}

$sql = "UPDATE sessions_core set lastaction=now() where ip='" . $_SERVER['REMOTE_ADDR'] . "'";
executeupdate($sql);

		
$no_log_array = array("login.php", "login_action.php", "login_multi.php", "login_multi_action.php", "logout.php", "iphone_upload.php", "test_iphonedata.php", 
"unsubscribe_servicedispatch.php", "unsubscribe_servicedispatch_action.php", "public_proposal_fcs_pdf_signed.php", "public_proposal_fcs_pdf_unsigned.php", 
"cron_inspection_approval.php", "cron_dispatch_approval.php", "test_session.php", "test_session_display.php", "fcscontrol_demo_login.php", 
"fcscontrol_demo_login_pdf.php", "demo_proposal.php", "demo_proposal_display.php", 
"master_forgotpassword.php", "master_forgotpassword_action.php", "master_password_complete.php", "master_password_reset_forgot.php", "master_password_reset_forgot_action.php", 
"document_proposal_fcs_new.php", "document_proposal_fcs_new_action.php", "public_proposal_fcs_pdf.php", 
"public_resource_resolve.php", "public_resource_resolve_action.php", "public_resource_resolve_pdf.php", "public_updates.php", "public_updates_pop.php", 
"unsubscribe_main.php", "unsubscribe_main_action.php", "public_quickbid_pdf_signed.php", "public_quickbid_pdf_unsigned.php", "cron_emailq.php", 
"cron_act_complete.php", "cron_check_geocode_property.php", "cron_check_geocode_prospect.php", "cron_corpprop.php", "cron_geocode_property.php", 
"cron_geocode_prospect.php", "cron_propertycount.php", "cron_sd_resource.php", "cron_sessiondestroy.php", 
"view_property_edit_latlng.php", "view_prospect_edit_latlng.php", "cron_leaktime.php", "video_evolution.php", "video_service.php", "fcs_sd_invoice_pdf.php", "fcs_sd_invoice_pdf2.php", 
"fcs_sd_invoice_pdf_documents.php", "fcs_sd_invoice_pdf_documents_data.php", 
"cron_geocode_property2.php", "public_calendar.php", "cron_productionmeeting.php", "unsubscribe_productionmeeting.php", "unsubscribe_productionmeeting_action.php",
"opm_s_header.php", "opm_s_project.php", "opm_s_photos.php", "cron_opm.php", "opm_s_entryview.php", 
"rr_proposal_repair_pdf.php", "rr_proposal_scope_pdf.php", "cron_programming_calendar.php", "cron_sd_export.php", 
"public_view_bid.php", "public_view_bid_action.php");

$only_fcs_array = array("activity_bulk.php", "activity_bulk_action.php", "contacts_deactivate", "fcs_contractor_login_as.php", "fcs_contractors_delcompany.php", 
"fcs_contractors_delproperty.php", "fcs_contractors_properties.php", "fcs_contractors_tools.php", "fcs_contractors_tools_action.php", "implementation_tracker.php", 
"implementation_tracker_action.php", "master_import.php", "master_import_action.php", "master_import_error.php", "report_calls.php", "setupascontractor.php", 
"setupascontractor_action.php", "tool_updates_email.php", "tool_emailq.php", "tool_emailq_action.php", "tool_emailq_filter.php", "tool_emailq_messages.php", 
"homepage_total.php", "homepage_total_excel.php");

$admin_array = array("frame_prospect_properties_action.php", "frame_prospect_properties_paste.php");

  if($SESSION_USER_ID=="" && !(in_array($current_file_name, $no_log_array))){
    meta_redirect("logout.php");
  }

if($SESSION_MASTER_ID != 1 && in_array($only_fcs_array)){
  meta_redirect("logout.php");
}

if(go_reg("^admin_", $current_file_name)){
  if($SESSION_ISADMIN != 1) meta_redirect("logout.php");
}
if(in_array($current_file_name, $admin_array)){
  if($SESSION_ISADMIN != 1) meta_redirect("logout.php");
}

if(go_reg("\_admin", $current_file_name)){
  if($SESSION_ISADMIN != 1) meta_redirect("logout.php");
}

if(go_reg("^jw\_", $current_file_name)){
  if($SESSION_MASTER_ID != 1) meta_redirect("logout.php");
}

if(go_reg("fcs_sd_", $current_file_name)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$can_export_array = array("fcs_sd_report_excel.php", "excel.php", "report_sales_status_excel.php", "fcs_sd_report_excel2.php");
if(in_array($current_file_name, $can_export_array)){
  if($SESSION_CAN_EXPORT != 1) meta_redirect("logout.php");
}

$dispatch_approval = array("report_sdapproval.php", "report_sdapproval_action.php");
if(in_array($current_file_name, $dispatch_approval)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=2 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$inspection_approval = array("report_inspectionapproval.php", "report_inspectionapproval_action.php");
if(in_array($current_file_name, $inspection_approval)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=1 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$opportunity_array = array("opportunities_edit.php", "opportunities_edit_action.php", "frame_prospect_opportunities.php", "frame_property_opportunities.php", 
"report_sales_status.php", "admin_opportunities.php", "admin_opportunities_delete.php", "admin_opportunities_edit.php", "admin_opportunities_edit_action.php");
if(in_array($current_file_name, $opportunity_array)){
  if($SESSION_OPPORTUNITIES!=1) meta_redirect("logout.php");
}

$capital_tool = array("capitals.php", "capitals_delete.php", "capitals_edit.php", "capitals_edit_action.php");
if(in_array($current_file_name, $capital_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=4 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$unsubscribe_tool = array("unsubscribe.php", "unsubscribe_delete.php", "unsubscribe_edit.php", "unsubscribe_edit_action.php");
if(in_array($current_file_name, $unsubscribe_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=5 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$implement_tool = array("implementation_tracker.php", "implementation_tracker_action.php", "implementation_tracker_email.php", "implementation_tracker_email_action.php");
if(in_array($current_file_name, $implement_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=6 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$callreport_tool = array("report_calls.php");
if(in_array($current_file_name, $callreport_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=7 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$dispatchmap_tool = array("map_service.php", "map_service_action.php");
if(in_array($current_file_name, $dispatchmap_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=8 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$emailupdates_tool = array("tool_updates.php", "tool_updates_email.php");
if(in_array($current_file_name, $emailupdates_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=9 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$import_tool = array("import.php", "import_action.php");
if(in_array($current_file_name, $import_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=10 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$salesandactivity_tool = array("report_salesandactivity.php", "report_salesandactivity_ind_detail.php");
if(in_array($current_file_name, $salesandactivity_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=11 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$servicemanreport_tool = array("report_serviceman_individual.php");
if(in_array($current_file_name, $servicemanreport_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=13 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$homepagenews_tool = array("homepage_news.php", "homepage_news_edit.php", "homepage_news_edit_action.php", "homepage_news_onoff.php", "homepage_news_delete.php");
if(in_array($current_file_name, $homepagenews_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=15 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$attachment_library_tool = array("tool_attach_library.php", "tool_attach_library_delete.php", "tool_attach_library_edit.php", "tool_attach_library_edit_action.php");
if(in_array($current_file_name, $attachment_library_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=16 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$goals_tool = array("tool_goals.php", "tool_goals_action.php");
if(in_array($current_file_name, $goals_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=17 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$youtube_tool = array("youtube.php", "youtube_delete.php", "youtube_edit.php", "youtube_edit_action.php", "youtube_properties.php", "youtube_view.php", "youtube_companies.php");
if(in_array($current_file_name, $youtube_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=18 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$discipline_tool = array("tool_discipline.php", "tool_discipline_edit.php", "tool_discipline_edit_action.php");
if(in_array($current_file_name, $discipline_tool)){
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=20 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test ==0) meta_redirect("logout.php");
}

$UPLOAD = "uploaded_files/";
$ALT_ROW_COLOR="#ececec";
//$ALT_ROW_COLOR="#222222";
//$ALT_ROW_COLOR2="#222222";
$ALT_ROW_COLOR2="#ececec";
$LOGO_SIZE = 150;
$PROPERTY_SIZE = 600;
$BUILDING_PHOTO_SIZE = 440;
$SECTION_PHOTO_SIZE = 600;
$REPORT_PHOTO_SIZE = 640;
$CONTRACTOR_LOGO_SIZE = 150;
$CONTRACTOR_PHOTO_SIZE = 250;
$RO_PHONE_NUMBER = "877-460-7663";
$THUMBNAIL = 100;
$RESOURCE_FILTER = " resource = 0 ";
$RESAPPT = "Residential Appointment"; // not sure if Dennis will want to use this wording, so I'll global variable it.


$I = "Inspection";
$BP = "Bid Presentation";
$RRP = "Roof Report Presentation";
$UM = "Intro Meeting";
$PCO = "Project Close-Out";


$gradevalue['0'] = 0;
$gradevalue['A'] = 5;
$gradevalue['B'] = 4;
$gradevalue['C'] = 3;
$gradevalue['D'] = 2;
$gradevalue['F'] = 1;
$gradevalue_reverse[1] = 'F';
$gradevalue_reverse[2] = 'D';
$gradevalue_reverse[3] = 'C';
$gradevalue_reverse[4] = 'B';
$gradevalue_reverse[5] = 'A';

$monthname_full[1] = "January";
$monthname_full[2] = "February";
$monthname_full[3] = "March";
$monthname_full[4] = "April";
$monthname_full[5] = "May";
$monthname_full[6] = "June";
$monthname_full[7] = "July";
$monthname_full[8] = "August";
$monthname_full[9] = "September";
$monthname_full[10] = "October";
$monthname_full[11] = "November";
$monthname_full[12] = "December";

$monthname[1] = "JAN";
$monthname[2] = "FEB";
$monthname[3] = "MAR";
$monthname[4] = "APR";
$monthname[5] = "MAY";
$monthname[6] = "JUN";
$monthname[7] = "JUL";
$monthname[8] = "AUG";
$monthname[9] = "SEP";
$monthname[10] = "OCT";
$monthname[11] = "NOV";
$monthname[12] = "DEC";





function executeQuery($sql)
{
    global $con;
	$result = mysqli_query($con, $sql)or die("<span style='FONT-SIZE:11px; FONT-COLOR: #000000; font-family=tahoma;'><center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysqli_error()."'</center></FONT>");;
	return $result;
} 

function getSingleResult($sql)
{
    global $con;
	$response = "";
	$result = mysqli_query($con, $sql)or die("<span style='FONT-SIZE:11px; FONT-COLOR: #000000; font-family=tahoma;'><center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysqli_error()."'</center></FONT>");;
	if ($line = mysqli_fetch_array($result)) {
		$response = $line[0];
	} 
	return $response;
} 

function executeUpdate($sql)
{
    global $con;
	mysqli_query($con, $sql) or die("<center>An Internal Error has Occured. Please report following error to the webmaster.<br><br>".$sql."<br><br>".mysqli_error()."'</center>");
}

function go_fetch_array($result){
  $r = mysqli_fetch_array($result);
  return $r;
}

function go_escape_string($string){
  global $con;
  $return = $con->real_escape_string($string);
  return $return;
}

function go_insert_id(){
  global $con;
  return $con->insert_id;
}




function meta_redirect($url, $time=0, $top=0){

  if($top==1){
    ?>
	<script>parent.location.href="<?=$url?>";</script>
	<?php
  }
  else {
    ?>
    <meta http-equiv="refresh" content="<?=$time?>;url=<?=$url?>">
    <?php
  }
  
  exit();
}

function deleteDir($dir)
{
   if (substr($dir, strlen($dir)-1, 1) != '/')
       $dir .= '/';

   //echo $dir;

   if ($handle = opendir($dir))
   {
       while ($obj = readdir($handle))
       {
           if ($obj != '.' && $obj != '..')
           {
               if (is_dir($dir.$obj))
               {
                   if (!deleteDir($dir.$obj))
                       return false;
               }
               elseif (is_file($dir.$obj))
               {
                   if (!unlink($dir.$obj))
                       return false;
               }
           }
       }

       closedir($handle);

       if (!@rmdir($dir))
           return false;
       return true;
   }
   return false;
}

function is_image_valid($file_name)
{
	$pos = strrpos($file_name, ".");
	$len = strlen($file_name);
	$ext = substr($file_name , $pos + 1, $len);
	$ext = strtolower($ext);
	if ($ext == "gif" || $ext == "jpg" || $ext == "jpeg" || $ext == "png") {
		return true;
	} else {
		return false;
	} 
}

function is_video_valid($file_name)
{
	$pos = strrpos($file_name, ".");
	$len = strlen($file_name);
	$ext = substr($file_name , $pos + 1, $len);
	$ext = strtolower($ext);
	if ($ext == "mpg" || $ext == "mpeg" || $ext == "asf" || $ext == "avi" || $ext == "wmv" || $ext == "mov" || $ext == "mod") {
		return true;
	} else {
		return false;
	} 
}

function resizeimage($source, $dest_dir, $maxwidth){
  if(!go_reg("/$", $dest_dir)) $dest_dir .= "/";
  if(is_image_valid($source)){
    // Get new dimensions
    list($width, $height) = getimagesize($source);
  
    if($width > $maxwidth){
      $new_width = $maxwidth;
      $ratio = $new_width/$width;
      $new_height = $height * $ratio;
    }
    else {
      $new_width = $width;
  	  $new_height = $height;
    }
	
	$pos = strrpos($source, ".");
	$len = strlen($source);
	$ext = substr($source , $pos + 1, $len);
	$ext = strtolower($ext);
	
	$file_array = explode("/", $source);
	$sourcefilename = array_pop($file_array);
	$dest_filename = $dest_dir . $sourcefilename;

	
    // Resample
    $image_p = imagecreatetruecolor($new_width, $new_height);

	switch(true){
	  case ($ext == "gif"):{
        $image = imagecreatefromgif($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagegif($image_p, $dest_filename, 100);
		break;
	  }
	  case ($ext=="jpg" || $ext=="jpeg"):{
        $image = @imagecreatefromjpeg($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dest_filename, 100);
		break;
	  }
	  /*
	  case ($ext == "png"):{
        $image = imagecreatefrompng($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagepng($image_p, $dest_filename, 100);
		break;
	  }
	  */
	  case ($ext == "png"):{
        $image = imagecreatefrompng($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dest_filename, 100);
		break;
	  }
	  
	}
	  
	return true;
  }
  else {
    return false;
  }
}

function stretchimage($source, $dest_dir, $maxwidth){
  if(!go_reg("/$", $dest_dir)) $dest_dir .= "/";
  if(is_image_valid($source)){
    // Get new dimensions
    list($width, $height) = getimagesize($source);
  
      $new_width = $maxwidth;
      $ratio = $new_width/$width;
      $new_height = $height * $ratio;

	
	$pos = strrpos($source, ".");
	$len = strlen($source);
	$ext = substr($source , $pos + 1, $len);
	$ext = strtolower($ext);
	
	$file_array = explode("/", $source);
	$sourcefilename = array_pop($file_array);
	$dest_filename = $dest_dir . $sourcefilename;

	
    // Resample
    $image_p = imagecreatetruecolor($new_width, $new_height);

	switch(true){
	  case ($ext == "gif"):{
        $image = imagecreatefromgif($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagegif($image_p, $dest_filename, 100);
		break;
	  }
	  case ($ext=="jpg" || $ext=="jpeg"):{
        $image = @imagecreatefromjpeg($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dest_filename, 100);
		break;
	  }
	  /*
	  case ($ext == "png"):{
        $image = imagecreatefrompng($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagepng($image_p, $dest_filename, 100);
		break;
	  }
	  */
	  case ($ext == "png"):{
        $image = imagecreatefrompng($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($image_p, $dest_filename, 100);
		break;
	  }
	  
	}
	  
	return true;
  }
  else {
    return false;
  }
}

function get_qry_str($over_write_key = array(), $over_write_value= array())
{
	global $_GET;
	$m = $_GET;
	if(is_array($over_write_key)){
		$i=0;
		foreach($over_write_key as $key){
			$m[$key] = $over_write_value[$i];
			$i++;
		}
	}else{
		$m[$over_write_key] = $over_write_value;
	}
	$qry_str = qry_str($m);
	return $qry_str;
} 

function qry_str($arr, $skip = 'start')
{
	$s = "?";
	$i = 0;
	foreach($arr as $key => $value) {
		if ($key != $skip) {
			if ($i == 0) {
				$s .= "$key=$value";
				$i = 1;
			} else {
				$s .= "&$key=$value";
			} 
		} 
	} 

	return $s;
} 

function sort_arrows($column){
	global $_SERVER;
	return '<A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'asc')).'"><IMG SRC="images/up_arrow.gif" BORDER="0" alt="Sort Ascending"></A><A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'desc')).'"><IMG SRC="images/down_arrow.gif" BORDER="0" alt="Sort Descending"></A>';
}

function sponsor_sort_arrows($column){
	global $_SERVER;
	return '<A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'asc')).'"><IMG SRC="images/sp_up_arrow.jpg" BORDER="0" alt="Sort Ascending" align="absmiddle"></A><A HREF="'.$_SERVER['PHP_SELF'].get_qry_str(array('order_by','order_by2'), array($column,'desc')).'"><IMG SRC="images/sp_down_arrow.jpg" BORDER="0" alt="Sort Descending" align="absmiddle"></A>';
}

function sort_header($column, $header, $url=""){
	global $_SERVER;
	global $_GET;
	if($url=="") $url = $_SERVER['PHP_SELF'];
	$order_by2 = "asc";
	if($_GET['order_by']==$column && $_GET['order_by2']=="asc") $order_by2 = "desc";
	return '<A HREF="'.$url.get_qry_str(array('order_by','order_by2'), array($column,$order_by2)).'">' . $header . '</A>';
}

function sort_header2($column, $header){
	global $_SERVER;
	global $_GET;
	$order_by2 = "asc";
	if($_GET['order_by']==$column && $_GET['order_by2']=="asc") $order_by2 = "desc";
	return '<A class="blankclick" HREF="javascript:searchResults(\''.get_qry_str(array('order_by','order_by2'), array($column,$order_by2)).'\')">' . $header . '</A>';
}

function uniqueTimeStamp() {
  $milliseconds = microtime();
  $timestring = explode(" ", $milliseconds);
  $sg = $timestring[1];
  $mlsg = substr($timestring[0], 2, 4);
  $timestamp = $sg.$mlsg;
  return $timestamp; 
}


function GetContacts($text, $prospect_id, $has_email=0, $sub_property=1){

  $listArray = array();
  $i=0;
  
  if($prospect_id=="" || $prospect_id=="0") return;

  if($has_email){
    $email_clause = " email != '' ";
  }
  else {
    $email_clause = " 1=1 ";
  }
  
  $strSQL = "SELECT concat(firstname, ' ', lastname) as fullname, email, firstname, lastname, id FROM contacts WHERE concat(firstname, ' ', lastname) LIKE '$text%' and prospect_id='$prospect_id' and $email_clause";
  $result = executequery($strSQL);
  while($record = go_fetch_array($result))
  {
  $artist_name = stripslashes($record['fullname']);
  if($artist_name != " "){
    $listArray[$i]['name'] = $artist_name;
	$listArray[$i]['email'] = stripslashes($record['email']);
	$listArray[$i]['firstname'] = stripslashes($record['firstname']);
	$listArray[$i]['lastname'] = stripslashes($record['lastname']);
	$listArray[$i]['id'] = stripslashes($record['id']);
	//echo $artist_name . " " . stripslashes($record['email']) . "<br>";
    $i++;
  }
  }
  
  if($sub_property){
    $sql = "SELECT property_id from properties where prospect_id ='$prospect_id' and corporate=0";
    $result = executequery($sql);
    while($record = go_fetch_array($result))
    {
      $property_id=$record['property_id'];
      $sql2 = "SELECT concat(firstname, ' ', lastname) as fullname, email, firstname, lastname, id FROM contacts WHERE concat(firstname, ' ', lastname) LIKE '$text%' and property_id='$property_id' and $email_clause";
      $result2 = executequery($sql2);
      while($record2 = go_fetch_array($result2))
      {
        $artist_name = stripslashes($record2['fullname']);
        if($artist_name != " "){
          $listArray[$i]['name'] = $artist_name;
		  $listArray[$i]['email'] = stripslashes($record2['email']);
		  $listArray[$i]['firstname'] = stripslashes($record2['firstname']);
	      $listArray[$i]['lastname'] = stripslashes($record2['lastname']);
		  $listArray[$i]['id'] = stripslashes($record2['id']);
		  //echo $artist_name . " " . stripslashes($record2['email']) . "<br>";
          $i++;
        }
      }
    }
  }



asort( $listArray );

return $listArray;

}


function GetContacts_Property($property_id, $has_email=0){

  $listArray = array();
  $i=0;
  
  if($property_id=="" || $property_id=="0") return;

  if($has_email){
    $email_clause = " email != '' ";
  }
  else {
    $email_clause = " 1=1 ";
  }
  
  $strSQL = "SELECT concat(firstname, ' ', lastname) as fullname, email, id, firstname, lastname FROM contacts WHERE property_id='$property_id' and $email_clause";
  $result = executequery($strSQL);
  while($record = go_fetch_array($result))
  {
  $artist_name = stripslashes($record['fullname']);
  if($artist_name != " "){
    $listArray[$i]['name'] = $artist_name;
	$listArray[$i]['email'] = stripslashes($record['email']);
	$listArray[$i]['id'] = stripslashes($record['id']);
	$listArray[$i]['firstname'] = stripslashes($record['firstname']);
	$listArray[$i]['lastname'] = stripslashes($record['lastname']);
	//echo $artist_name . " " . stripslashes($record['email']) . "<br>";
    $i++;
  }
  }

  $sql = "SELECT prospect_id from properties where property_id ='$property_id'";
  $prospect_id = getsingleresult($sql);
  
  $strSQL = "SELECT concat(firstname, ' ', lastname) as fullname, email, id, firstname, lastname FROM contacts WHERE prospect_id='$prospect_id' and $email_clause";
  $result = executequery($strSQL);
  while($record = go_fetch_array($result))
  {
  $artist_name = stripslashes($record['fullname']);
  if($artist_name != " "){
    $listArray[$i]['name'] = $artist_name;
	$listArray[$i]['email'] = stripslashes($record['email']);
	$listArray[$i]['id'] = stripslashes($record['id']);
	$listArray[$i]['firstname'] = stripslashes($record['firstname']);
	$listArray[$i]['lastname'] = stripslashes($record['lastname']);
	//echo $artist_name . " " . stripslashes($record['email']) . "<br>";
    $i++;
  }
  }



asort( $listArray );

return $listArray;

}

function PropertyEmails($property_id){
  $listArray = array();
  
  $sql = "SELECT email from contacts where property_id='$property_id' and property_id != 0";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if($email != "" && !go_reg(" ", $email)) $listArray[] = $email;
  }
  
  return $listArray;
}

function ProspectEmails($prospect_id){
  $listArray = array();
  
  $sql = "SELECT email from contacts where prospect_id='$prospect_id' and prospect_id != 0";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if($email != "" && !go_reg(" ", $email)) $listArray[] = $email;
  }
  
  return $listArray;
}

function EverybodyEmail(){
  $listArray = array();
 
  $sql = "SELECT email from contacts where email != '' group by email";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $email = $record['email'];
	if($email != "" && !go_reg(" ", $email)) $listArray[] = $email;
  }
  
  return array_unique($listArray);
}

function GetStatus($text){

$listArray = array();
$i=0;

$strSQL = "SELECT id_status FROM properties_beazer WHERE id_status LIKE '$text%' group by id_status";
$result = executequery($strSQL);
while($record = go_fetch_array($result))
{
$id_status = stripslashes($record['id_status']);
if($id_status != ""){
  $listArray[$i] = $id_status;
  $i++;
}
}


asort( $listArray );

return $listArray;

}

function guestList($type, $prospect_id, $property_id, $selected=""){

  if($type != "") $type .= "_";
  
  if($prospect_id=="" || $prospect_id=="0") return;
  
  $totalguests=0;
  $sql = "SELECT concat(firstname, ' ', lastname) as contact, email from contacts where prospect_id='$prospect_id' and email != ''";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $contact = stripslashes($record['contact']);
    $email = stripslashes($record['email']);
    $totalguests++;
    echo "<input type=\"checkbox\" name=\"" . $type . "guests[" . $totalguests . "]\" value=\"1\"";
	if($selected != "" && $selected == $email) echo " checked";
	echo "> " . $contact . "\n"; 
    echo "<input type=\"hidden\" name=\"" . $type . "guestname[" . $totalguests . "]\" value=\"" . $contact . "\">\n";
    echo "<em>email:</em><input type=\"text\" name=\"" . $type . "email[" . $totalguests . "]\" value=\"" . $email . "\"><br>\n";
  }

  if($property_id != "0" && $property_id != ""){
  $sql = "SELECT concat(firstname, ' ', lastname) as contact, email from contacts where property_id='$property_id' and email != ''";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $contact = stripslashes($record['contact']);
    $email = stripslashes($record['email']);
    $totalguests++;
    echo "<input type=\"checkbox\" name=\"" . $type . "guests[" . $totalguests . "]\" value=\"1\"";
	if($selected != "" && $selected == $email) echo " checked";
	echo "> " . $contact . "\n"; 
    echo "<input type=\"hidden\" name=\"" . $type . "guestname[" . $totalguests . "]\" value=\"" . $contact . "\">\n";
    echo "<em>email:</em><input type=\"text\" name=\"" . $type . "email[" . $totalguests . "]\" value=\"" . $email . "\"><br>\n";
  }
  }

  echo "<input type=\"hidden\" name=\"" . $type . "totalguests\" value=\"" . $totalguests . "\">";
}

function guestList_address($type, $prospect_id, $property_id, $selected=""){

  if($type != "") $type .= "_";
  
  if($prospect_id=="" || $prospect_id=="0") return;
  
  $totalguests=0;
  $sql = "SELECT address, city, state, zip from prospects where prospect_id='$prospect_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $address = stripslashes($record['address']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $zip = stripslashes($record['zip']);
  
  $sql = "SELECT concat(firstname, ' ', lastname) as contact, email, position from contacts where prospect_id='$prospect_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $contact = stripslashes($record['contact']);
    $email = stripslashes($record['email']);
	$position = stripslashes($record['position']);
    $totalguests++;
    echo "<input type=\"checkbox\" name=\"" . $type . "guests[" . $totalguests . "]\" value=\"1\"";
	if($selected != "" && $selected == $email) echo " checked";
	echo "> " . $contact . "\n"; 
    echo "<input type=\"hidden\" name=\"" . $type . "guestname[" . $totalguests . "]\" value=\"" . $contact . "\">\n";
	echo "<em>position:</em><input type=\"text\" name=\"" . $type . "position[" . $totalguests . "]\" value=\"" . $position . "\">\n";
    echo "<input type=\"text\" name=\"" . $type . "address[" . $totalguests . "]\" value=\"" . $address . "\">\n";
	echo "<input type=\"text\" name=\"" . $type . "city[" . $totalguests . "]\" value=\"" . $city . "\" size='10'>\n";
	echo "<input type=\"text\" name=\"" . $type . "state[" . $totalguests . "]\" value=\"" . $state . "\" size='2'>\n";
	echo "<input type=\"text\" name=\"" . $type . "zip[" . $totalguests . "]\" value=\"" . $zip . "\" size='5'><br>\n";
  }

  if($property_id != "0" && $property_id != ""){
  $sql = "SELECT address, city, state, zip from properties where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $address = stripslashes($record['address']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $zip = stripslashes($record['zip']);
  
  $sql = "SELECT concat(firstname, ' ', lastname) as contact, email, position from contacts where property_id='$property_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $contact = stripslashes($record['contact']);
    $email = stripslashes($record['email']);
	$position = stripslashes($record['position']);
    $totalguests++;
    echo "<input type=\"checkbox\" name=\"" . $type . "guests[" . $totalguests . "]\" value=\"1\"";
	if($selected != "" && $selected == $email) echo " checked";
	echo "> " . $contact . "\n"; 
    echo "<input type=\"hidden\" name=\"" . $type . "guestname[" . $totalguests . "]\" value=\"" . $contact . "\">\n";
	echo "<em>position:</em><input type=\"text\" name=\"" . $type . "position[" . $totalguests . "]\" value=\"" . $position . "\">\n";
    echo "<input type=\"text\" name=\"" . $type . "address[" . $totalguests . "]\" value=\"" . $address . "\">\n";
	echo "<input type=\"text\" name=\"" . $type . "city[" . $totalguests . "]\" value=\"" . $city . "\" size='10'>\n";
	echo "<input type=\"text\" name=\"" . $type . "state[" . $totalguests . "]\" value=\"" . $state . "\" size='2'>\n";
	echo "<input type=\"text\" name=\"" . $type . "zip[" . $totalguests . "]\" value=\"" . $zip . "\" size='5'><br>\n";
  }
  }

  echo "<input type=\"hidden\" name=\"" . $type . "totalguests\" value=\"" . $totalguests . "\">";
}


function great_circle_distance($lat1,$lat2,$lon1,$lon2)
	{
		/* Convert all the degrees to radians */
		$lat1 = deg_to_rad($lat1);
		$lon1 = deg_to_rad($lon1);
		$lat2 = deg_to_rad($lat2);
		$lon2 = deg_to_rad($lon2);

		/* Find the deltas */
		$delta_lat = $lat2 - $lat1;
		$delta_lon = $lon2 - $lon1;
	
		/* Find the Great Circle distance */
		$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
		
		$EARTH_RADIUS = 3956;

		$distance = $EARTH_RADIUS * 2 * atan2(sqrt($temp),sqrt(1-$temp));
		
		return $distance;
	}

function deg_to_rad($deg)
	{
		$radians = 0.0;	
		$radians = $deg * M_PI/180.0;
		return($radians);
	}

function GetDays($sStartDate, $sEndDate, $cycle){
  // Firstly, format the provided dates.
  // This function works best with YYYY-MM-DD
  // but other date formats will work thanks
  // to strtotime().
  $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));
  $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));

  // Start the variable off with the start date
  $aDays[] = $sStartDate;

  // Set a 'temp' variable, sCurrentDate, with
  // the start date - before beginning the loop
  $sCurrentDate = $sStartDate;

  // While the current date is less than the end date
  while($sCurrentDate < $sEndDate){
    // Add a day to the current date
    $sCurrentDate = gmdate("Y-m-d", strtotime($cycle, strtotime($sCurrentDate)));

    // Add this new day to the aDays array
    $aDays[] = $sCurrentDate;
  }

  // Once the loop has finished, return the
  // array of days.
  return $aDays;
}

function bumpbackcompare($x, $y)
{
$order_by = "firstdate";
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function BumpBack($event_id){
  $sql = "SELECT ro_user_id, operations_crew, act_id from supercali_events where event_id='$event_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $ro_user_id = $record['ro_user_id'];
  $operations_crew = $record['operations_crew'];
  $act_id = $record['act_id'];
  
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='$event_id' order by date desc limit 1";
  $enddate = getsingleresult($sql);
  
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='$event_id' order by date asc limit 1";
  $startdate = getsingleresult($sql);
  
  $sql = "SELECT date_format(date, \"%T\") from activities where act_id='$act_id'";
  $start_time = getsingleresult($sql);
  $startdate_time = $startdate . " " . $start_time;
  
  $sql = "UPDATE activities set operations_start='$startdate', operations_finish='$enddate', date='$startdate_time' where act_id='$act_id'";
  executeupdate($sql);
  
  $sql = "SELECT a.event_id from supercali_events a, supercali_dates b where a.event_id=b.event_id and 
  a.ro_user_id='$ro_user_id' and a.operations_crew=\"$operations_crew\" and b.date <= '$enddate' and b.date >= '$startdate' and a.event_id != '$event_id' group by a.event_id";
  $result = executequery($sql);
  $counter = 0;
  while($record = go_fetch_array($result)){
    $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='" . $record['event_id'] . "' order by date asc limit 1";
	$firstdate = getsingleresult($sql);
	if($firstdate < $startdate) continue;
    $row[$counter]['event_id'] = $record['event_id'];
	$row[$counter]['firstdate'] = $firstdate;
	
	$counter++;
  }
  
  if(is_array($row)){
    usort($row, "bumpbackcompare");
	$next_event_id = $row[0]['event_id'];
	
	$sql = "SELECT date_format(date, \"%Y-%m-%d\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$next_event_id' order by date desc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $next_enddate = $record['datepretty'];
	$endmonth = $record['month'];
	$endday = $record['day'];
	$endyear = $record['year'];
  
    $sql = "SELECT date_format(date, \"%Y-%m-%d\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$next_event_id' order by date asc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $next_startdate = $record['datepretty'];
	$startmonth = $record['month'];
	$startday = $record['day'];
	$startyear = $record['year'];
	
	$span = GetDays($next_startdate, $enddate, "+1 day");
	$number_bump = sizeof($span);
	$new_enddate = date("Y-m-d", mktime(0, 0, 0, $endmonth, $endday + $number_bump, $endyear));
	$new_startdate = date("Y-m-d", mktime(0, 0, 0, $startmonth, $startday + $number_bump, $startyear));
	
	$sql = "DELETE from supercali_dates where event_id='$next_event_id'";
	executeupdate($sql);
	$span = GetDays($new_startdate, $new_enddate, "+1 day");
	for($x=0;$x<sizeof($span);$x++){
	  $sql = "SELECT event_id from supercali_dates where event_id = '$next_event_id' and date like '" . $span[$x] . "%'";
	  $test = getsingleresult($sql);
	  if($test==""){
	    $sql = "INSERT into supercali_dates(event_id, date, end_date) values('$next_event_id', '" . $span[$x] . "', '" . $span[$x] . "')";
		executeupdate($sql);
	  }
	}
	return($next_event_id);
  }
  else {
    return(0);
  }
}

function ImageLink($url, $image, $onclick=0, $javascript=0, $title=''){
  if($javascript==0){
    $action = "document.location.href='$url'";
  }
  else {
    $action = "javascript:$url";
  }
  
  echo "<input type=\"image\" src=\"images/" . $image . "_off.png\" hoversrc=\"images/" . $image . "_hover.png\"";
  if($onclick) echo " activesrc=\"images/" . $image . "_click.png\"";
  echo " border=\"0\" onclick=\"$action; return false\" title=\"$title\">";
}

function ScrollingArrow($lr, $url, $nextrecord){
  if($lr=="L"){
    $image = "redarrow-left";
	$direction = -1;
  }
  else {
    $image = "redarrow-right";
	$direction = 1;
  }
  $action = "javascript:slideGenerate('" . $nextrecord . "', '" . $direction . "', '" . $url . "')";
  $arrow =  "<input type=\\\"image\\\" src=\\\"images/" . $image . "_off.png\\\" hoversrc=\\\"images/" . $image . "_hover.png\\\"";
  $arrow .= " activesrc=\\\"images/" . $image . "_click.png\\\"";
  $arrow .= " border=\\\"0\\\" onclick=\\\"$action\\\">";
  
  return $arrow;
}

function superclean($x){
  $x = go_reg_replace("\n", "", $x);
  $x = go_reg_replace("\r", "", $x);
  $x = go_reg_replace("\"", "&quot;", $x);
  $x = go_reg_replace("\<", "&lt;", $x);
  $x = go_reg_replace("\>", "&gt;", $x);
  return $x;
}

function jsclean($x){

  $x = go_reg_replace("\n", "", $x);
  $x = go_reg_replace("\r", "", $x);
  $x = go_reg_replace("'", "\\'", $x);
  //$x = go_reg_replace("\"", "\"", $x);
 // $x = go_reg_replace("\<", "&lt;", $x);
 // $x = go_reg_replace("\>", "&gt;", $x);
 $x = go_reg_replace(chr(12), "", $x);
 $x = go_reg_replace(chr(15), "", $x);
 $x = trim($x);
 
 //$x = trim(str_replace("\r\n", "", $x));
 
 
 //return (string)str_replace(array("\r", "\r\n", "\n"), '', $x);
  return $x;
}

function remove_non_numeric($string) {

return preg_replace('/\D/', '', $string);

}

function sentence_case($string) { 
    $sentences = preg_split('/([.?!;]+)/', $string, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE); 
    $new_string = ''; 
    foreach ($sentences as $key => $sentence) { 
        $new_string .= ($key & 1) == 0? 
            ucfirst(strtolower(trim($sentence))) : 
            $sentence.' '; 
    } 
    return trim($new_string); 
}

function capitalizer($replace, $text){
  for($x=0;$x<sizeof($replace);$x++){
    $word = $replace[$x];
	$text = ucfirst($text);
	//$text = sentence_case($text);
    $text = preg_replace("/ " . $word . "[ ]/i", " " . $word . " ", $text);
    $text = preg_replace("/ " . $word . "[\,]/i", " " . $word . ",", $text);
    $text = preg_replace("/ " . $word . "[\.]/i", " " . $word . ".", $text);
	$text = preg_replace("/ " . $word . "[\;]/i", " " . $word . ";", $text);
	$text = preg_replace("/[\(]" . $word . "[\)]/i", "(" . $word . ")", $text);
  }
  
  return $text;
}

function getContactInfo($name, $prospect_id, $property_id){
  $array['phone'] = "";
  $array['mobile'] = "";
  $array['email'] = "";

  
  $sql = "SELECT email, phone, mobile from contacts where prospect_id='$prospect_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $phone = stripslashes($record['phone']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	if($phone != "") $array['phone'] = $phone;
	if($mobile != "") $array['mobile'] = $mobile;
	if($email != "") $array['email'] = $email;
  }
  
  $sql = "SELECT email, phone, mobile from contacts where property_id='$property_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $phone = stripslashes($record['phone']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	if($phone != "") $array['phone'] = $phone;
	if($mobile != "") $array['mobile'] = $mobile;
	if($email != "") $array['email'] = $email;
  }
  
  return $array;
}

function email_q($to, $subject, $message, $headers, $attachment="", $aurl = "", $force_real_attach=0, $real_master_id=0){
  global $SESSION_MASTER_ID;
  global $CORE_URL;
  global $SESSION_USER_ID;
  
  $use_master_id = $SESSION_MASTER_ID;
  if($real_master_id != 0) $use_master_id = $real_master_id;
  
  if($attachment != ""){
    if($aurl=="") $aurl = $CORE_URL;
	$attachment_url = $aurl . $attachment;
	$headers = "Content-type: text/html; charset=iso-8859-1\n" . $headers;
  }
  
  $headers_array = explode("\n", $headers);
  
  if(is_array($headers_array)){
    for($x=0;$x<sizeof($headers_array);$x++){
	  if(go_reg("From:", $headers_array[$x])){
	    $fromline = $headers_array[$x];
		$from_array = explode(" ", $fromline);
		if(is_array($from_array)){
		  for($z=0;$z<sizeof($from_array);$z++){
		    if(go_reg("\@", $from_array[$z])){
			  $from_email = $from_array[$z];
			}
		  }
		}
	  }
	}
  }
  $from_email = str_replace("<", "", $from_email);
  $from_email = str_replace(">", "", $from_email);
  $from_email = str_replace("From:", "", $from_email);
  
  if($use_master_id != ""){
    $sql = "SELECT from_email from master_list where master_id='" . $use_master_id . "'";
    $master_from_email = stripslashes(getsingleresult($sql));
    if($master_from_email != ''){
      $headers = "";
      if(is_array($headers_array)){
	    for($x=0;$x<sizeof($headers_array);$x++){
	      if(go_reg("From:", $headers_array[$x])){
		    $headers .= "From: $master_from_email\n";
		  }
		  else {
		    $headers .= $headers_array[$x] . "\n";
		  }
	    }
	  }
  	  $from_email = $master_from_email;
    }
  }
  
  $bcc = "";
  if(is_array($headers_array)){
    for($x=0;$x<sizeof($headers_array);$x++){
	  if(eregi("Bcc:", $headers_array[$x])){
	    $bcc = $headers_array[$x];
	  }
	}
  }
  $bcc = str_replace("Bcc:", "", $bcc);
  $bcc = go_reg_replace("\,$", "", $bcc);
  $bcc = go_reg_replace("\, $", "", $bcc);
  $bcc_array = explode(",", $bcc);
  $bcc_count = 0;
  if(is_array($bcc_array)) $bcc_count = sizeof($bcc_array);
  if($bcc=="") $bcc_count = 0;
  
  $cc = "";
  if(is_array($headers_array)){
    for($x=0;$x<sizeof($headers_array);$x++){
	  if(go_reg("Cc:", $headers_array[$x])){
	    $cc = $headers_array[$x];
	  }
	}
  }
  $cc = str_replace("Cc:", "", $cc);
  $cc = go_reg_replace("\,$", "", $cc);
  $cc = go_reg_replace("\, $", "", $cc);
  $cc_array = explode(",", $cc);
  $cc_count = 0;
  if(is_array($cc_array)) $cc_count = sizeof($cc_array);
  if($cc=="") $cc_count = 0;
  
  
  $to = go_reg_replace("\,$", "", $to);
  $to = go_reg_replace("\, $", "", $to);
  $to_array = explode(",", $to);
  $to_count = 0;
  if(is_array($to_array)) $to_count = sizeof($to_array);
  if($to=="") $to_count = 0;
  
  $num_recipients = $to_count + $bcc_count + $cc_count;
  
  $sql = "INSERT into email_q(ts, justtime, to_field, subject, message, headers, from_field, master_id, bcc, cc, num_recipients, attachment, attachment_url, from_user_id, force_real_attach) values(
  now(), now(), \"" . go_escape_string($to) . "\", \"" . go_escape_string($subject) . "\", \"" . go_escape_string($message) . "\", 
  \"" . go_escape_string($headers) . "\", \"" . go_escape_string($from_email) . "\", '" . $use_master_id . "', 
  \"" . go_escape_string($bcc) . "\", \"" . go_escape_string($cc) . "\", '$num_recipients', \"" . go_escape_string($attachment) . "\", 
  \"" . go_escape_string($attachment_url) . "\", '" . $SESSION_USER_ID . "', '$force_real_attach')";
  executeupdate($sql);
}
  
function image_rotate($image, $path, $degrees){
  $ext = explode(".", $image);
  $ext = array_pop($ext);
  $ext = strtolower($ext);
  if($ext=="gif"){
    $source = imagecreatefromgif($path . $image);

    $rotate = imagerotate($source, $degrees, 0);

    imagegif($rotate, $path . $image);
  }
  else {
    $source = imagecreatefromjpeg($path . $image);

    $rotate = imagerotate($source, $degrees, 0);

    imagejpeg($rotate, $path . $image);
  }
}

function secretCode() {
    $string = uniqueTimeStamp();
    $length = 8;
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

function supercaliOperations($user_id){
  $sql = "SELECT a.event_id, a.category_id, a.opm_id from supercali_events a, supercali_categories b
  where a.category_id=b.category_id and b.calendar_type='Operations' and a.ro_user_id='$user_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $event_id = $record['event_id'];
	$cat = $record['category_id'];
	$opm_id = $record['opm_id'];
	switch($cat){
	  case 41:{
	    $start_field = "pm_start";
		$end_field = "pm_finish";
		break;
	  }
	  case 42:{
	    $start_field = "ip_start";
		$end_field = "ip_finish";
		break;
	  }
	  case 43:{
	    $start_field = "fi_start";
		$end_field = "fi_finish";
		break;
	  }
	}
	$sql = "SELECT date from supercali_dates where event_id='$event_id' order by date limit 1";
	$start_date = getsingleresult($sql);
	$sql = "SELECT date from supercali_dates where event_id='$event_id' order by date DESC limit 1";
	$end_date = getsingleresult($sql);
	
	$sql = "UPDATE opm set $start_field='$start_date', $end_field='$end_date' where opm_id='$opm_id'";
	executeupdate($sql);
  }
}

function specialChar($text){
  //echo "Entering: " . $text . "<br>";
  $text = go_reg_replace("\n", "NEWLINE", $text);
  
  $sql = "SELECT * from special_chars";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    //$search_for = stripslashes($record['search_for']);
	$search_for = $record['search_for'];
	$code = stripslashes($record['code']);
	//echo "searchfor: *" . $search_for . "* replace with *" . $code . "*<br>";
	//echo "go_reg_replace(" . $search_for . ", " . $code . ", " . $text . ")<br>";
	$text = go_reg_replace($search_for, $code, $text);
  }
  return($text);
}
	  
$welcome_user_id = $SESSION_USER_ID;
$sql = "SELECT firstname, concat(firstname, ' ', lastname) as fullname, photo, master_id from users where user_id='$welcome_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$firstname_welcome = stripslashes($record['firstname']);
$fullname_welcome = stripslashes($record['fullname']);
$master_id = $record['master_id'];
if($fullname_welcome == " " || $fullname_welcome == "") $fullname_welcome = "Guest OnlyInTestMode";
$HEADSHOT = stripslashes($record['photo']);
if($HEADSHOT == "") $HEADSHOT = "fcs.png";

$sql = "SELECT master_name, logo, idle_time from master_list where master_id='$master_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$MASTER_NAME = stripslashes($record['master_name']);
$MASTER_LOGO = stripslashes($record['logo']);
$IDLE_TIME = stripslashes($record['idle_time']);
if(is_nan($IDLE_TIME)) $IDLE_TIME = 60;

if($SESSION_USER_ID != ""){
  $sql = "SELECT enabled, forcetime, forcetime_start, forcetime_end from users where user_id='" . $SESSION_USER_ID . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $test = $record['enabled'];
  $forcetime = $record['forcetime'];
  $forcetime_start = $record['forcetime_start'];
  $forcetime_end = $record['forcetime_end'];
  
  if($test==0) {
    $SESSION_USER_ID = "";
	meta_redirect("logout.php");
  }
  $testtime = date("G");
  if($forcetime){
    if($testtime < $forcetime_start || $testtime > $forcetime_end){
	  $SESSION_USER_ID = "";
	  meta_redirect("logout.php");
    }
  }
  
  if($current_file_name != "check_dispatch_approval.php" && $current_file_name != "check_inspection_approval.php"){
    $sql = "UPDATE users set lastaction=now() where user_id='" . $SESSION_USER_ID . "'";
    executeupdate($sql);
  }
  
  $sql = "SELECT count(*) from users where user_id='" . $SESSION_USER_ID . "' and date_add(lastaction, interval $IDLE_TIME minute) < now()";
  $test = getsingleresult($sql);
  if($test != 0) meta_redirect("logout.php", 0, 1);
}

if($_GET['prospect_id'] != "") $test_prospect_id = $_GET['prospect_id'];
if($_POST['prospect_id'] != "") $test_prospect_id = $_POST['prospect_id'];

if($_GET['property_id'] != "") $test_property_id = $_GET['property_id'];
if($_POST['property_id'] != "") $test_property_id = $_POST['property_id'];

if($_GET['section_id'] != "") $test_section_id = $_GET['section_id'];
if($_POST['section_id'] != "") $test_section_id = $_POST['section_id'];

if($_GET['leak_id'] != "") $test_leak_id = $_GET['leak_id'];
if($_POST['leak_id'] != "") $test_leak_id = $_POST['leak_id'];

if($_GET['opm_id'] != "") $test_opm_id = $_GET['opm_id'];
if($_POST['opm_id'] != "") $test_opm_id = $_POST['opm_id'];

if($_GET['bid_id'] != "") $test_bid_id = $_GET['bid_id'];
if($_POST['bid_id'] != "") $test_bid_id = $_POST['bid_id'];

if(!(in_array($current_file_name, $no_log_array))){
if($master_id != 1){

if($test_prospect_id != "" && $test_prospect_id != "new" && $test_prospect_id != 0 && !(is_array($test_prospect_id))){
  $sql = "SELECT master_id from prospects where prospect_id='$test_prospect_id'";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("logout.php");
}

if($test_property_id != "" && $test_property_id != "new" && $test_property_id != 0 && !(is_array($test_property_id))){
  $sql = "SELECT a.master_id from prospects a, properties b where b.property_id='$test_property_id' and a.prospect_id=b.prospect_id";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("logout.php");
}

if($test_section_id != "" && $test_section_id != "new" && $test_section_id != 0 && !(is_array($test_section_id))){
  $sql = "SELECT a.master_id from prospects a, properties b, sections c where c.section_id='$test_section_id' and a.prospect_id=b.prospect_id and b.property_id=c.property_id";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("logout.php");
}

if($test_leak_id != "" && $test_leak_id != "new" && $test_leak_id != 0 && !(is_array($test_leak_id))){
  $sql = "SELECT a.master_id from prospects a, am_leakcheck b where a.prospect_id=b.prospect_id and b.leak_id='$test_leak_id'";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("logout.php");
}

if($test_opm_id != "" && $test_opm_id != "new" && $test_opm_id != 0 && !(is_array($test_opm_id))){
  $sql = "SELECT master_id from opm where opm_id='$test_opm_id'";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("logout.php");
}

if($test_bid_id != "" && $test_bid_id != "new" && $test_bid_id != 0 && !(is_array($test_bid_id))){
  $sql = "SELECT a.master_id from prospects a, properties b, bids c where c.bid_id='$test_bid_id' and a.prospect_id=b.prospect_id and b.property_id=c.property_id";
  $test_master_id = getsingleresult($sql);
  if($test_master_id != $master_id) meta_redirect("logout.php");
}
  

}
}
// check irep for all, even master_id of 1 (FCS)
  if($SESSION_IREP == 1){
    if($test_prospect_id != "" && $test_prospect_id != "new" && $test_prospect_id != 0 && !(is_array($test_prospect_id))){
      $sql = "SELECT count(*) from prospects where prospect_id='$test_prospect_id' and display=1 and irep like '%," . $SESSION_USER_ID . ",%'";
      $test_irep = getsingleresult($sql);
      if($test_irep ==0) meta_redirect("logout.php");
    }

    if($test_property_id != "" && $test_property_id != "new" && $test_property_id != 0 && !(is_array($test_property_id))){
      $sql = "SELECT count(*) from properties where property_id='$test_property_id' and display=1 and irep='" . $SESSION_USER_ID . "'";
      $test_irep = getsingleresult($sql);
      if($test_irep ==0) meta_redirect("logout.php");
    }
	
	if($test_section_id != "" && $test_section_id != "new" && $test_section_id != 0 && !(is_array($test_section_id))){
	  $sql = "SELECT property_id from sections where section_id='$test_section_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT count(*) from properties where property_id='$test_property_id' and display=1 and irep='" . $SESSION_USER_ID . "'";
      $test_irep = getsingleresult($sql);
      if($test_irep ==0) meta_redirect("logout.php");
	}
	
	if($test_leak_id != "" && $test_leak_id != "new" && $test_leak_id != 0 && !(is_array($test_leak_id))){
	  $sql = "SELECT property_id from am_leakcheck where leak_id='$test_leak_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT count(*) from properties where property_id='$test_property_id' and display=1 and irep='" . $SESSION_USER_ID . "'";
      $test_irep = getsingleresult($sql);
      if($test_irep ==0) meta_redirect("logout.php");
	}
	
	if($test_bid_id != "" && $test_bid_id != "new" && $test_bid_id != 0 && !(is_array($test_bid_id))){
	  $sql = "SELECT property_id from bids where bid_id='$test_bid_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT count(*) from properties where property_id='$test_property_id' and display=1 and irep='" . $SESSION_USER_ID . "'";
      $test_irep = getsingleresult($sql);
      if($test_irep ==0) meta_redirect("logout.php");
	}
  } // end irep check


if($SESSION_USE_GROUPS==1){
  if($SESSION_GROUPS != ""){
	
    if($test_prospect_id != "" && $test_prospect_id != "new" && $test_prospect_id != 0 && !(is_array($test_prospect_id))){
      $sql = "SELECT groups from prospects where prospect_id='$test_prospect_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  $groups_array = explode(",", $test_groups);
	  for($x=0;$x<sizeof($groups_array);$x++){
        if($groups_array[$x]=="") continue;
	    if(go_reg("," . $groups_array[$x] . ",", $SESSION_GROUPS)) $good = 1;
	  }
    
      if($good ==0) meta_redirect("logout.php");
    }

    if($test_property_id != "" && $test_property_id != "new" && $test_property_id != 0 && !(is_array($test_property_id))){
      $sql = "SELECT groups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
      if(go_reg("," . $test_groups . ",", $SESSION_GROUPS)) $good = 1;

    
      if($good ==0) meta_redirect("logout.php");
    }
	
	if($test_section_id != "" && $test_section_id != "new" && $test_section_id != 0 && !(is_array($test_section_id))){
	  $sql = "SELECT property_id from sections where section_id='$test_section_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT groups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_GROUPS)) $good = 1;
    
      if($good ==0) meta_redirect("logout.php");
	}
	
	if($test_leak_id != "" && $test_leak_id != "new" && $test_leak_id != 0 && !(is_array($test_leak_id))){
	  $sql = "SELECT property_id from am_leakcheck where leak_id='$test_leak_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT groups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_GROUPS)) $good = 1;
    
      if($good ==0) meta_redirect("logout.php");
	}
	
	if($test_bid_id != "" && $test_bid_id != "new" && $test_bid_id != 0 && !(is_array($test_bid_id))){
	  $sql = "SELECT property_id from bids where bid_id='$test_bid_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT groups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_GROUPS)) $good = 1;
    
      if($good ==0) meta_redirect("logout.php");
	}
  } // end groups check
  
  
  if($SESSION_SUBGROUPS != ""){
	
    if($test_prospect_id != "" && $test_prospect_id != "new" && $test_prospect_id != 0 && !(is_array($test_prospect_id))){
      $sql = "SELECT subgroups from prospects where prospect_id='$test_prospect_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  $groups_array = explode(",", $test_groups);
	  for($x=0;$x<sizeof($groups_array);$x++){
        if($groups_array[$x]=="") continue;
	    if(go_reg("," . $groups_array[$x] . ",", $SESSION_SUBGROUPS)) $good = 1;
	  }
    
      if($good ==0) meta_redirect("logout.php");
    }

    if($test_property_id != "" && $test_property_id != "new" && $test_property_id != 0 && !(is_array($test_property_id))){
      $sql = "SELECT subgroups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_SUBGROUPS)) $good = 1;
	  
      if($good ==0) meta_redirect("logout.php");
    }
	
	if($test_section_id != "" && $test_section_id != "new" && $test_section_id != 0 && !(is_array($test_section_id))){
	  $sql = "SELECT property_id from sections where section_id='$test_section_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT subgroups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_SUBGROUPS)) $good = 1;
    
      if($good ==0) meta_redirect("logout.php");
	}
	
	if($test_leak_id != "" && $test_leak_id != "new" && $test_leak_id != 0 && !(is_array($test_leak_id))){
	  $sql = "SELECT property_id from am_leakcheck where leak_id='$test_leak_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT subgroups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_SUBGROUPS)) $good = 1;
    
      if($good ==0) meta_redirect("logout.php");
	}
	
	if($test_bid_id != "" && $test_bid_id != "new" && $test_bid_id != 0 && !(is_array($test_bid_id))){
	  $sql = "SELECT property_id from bids where bid_id='$test_bid_id'";
	  $test_property_id = getsingleresult($sql);
	  $sql = "SELECT subgroups from properties where property_id='$test_property_id'";
      $test_groups = getsingleresult($sql);
	  $good = 0;
	  if(go_reg("," . $test_groups . ",", $SESSION_SUBGROUPS)) $good = 1;
    
      if($good ==0) meta_redirect("logout.php");
	}
  } // end groups check
  
}// end whole groups check

//include "includes/functions_irepsecurity.php";
?>
