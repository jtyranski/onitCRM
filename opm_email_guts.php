<?php
$opm_id = stripslashes($record_main['opm_id']);
  $opm_entry_id = stripslashes($record_main['opm_entry_id']);
  $removed = stripslashes($record_main['removed']);
  $replaced = stripslashes($record_main['replaced']);
  $comments = stripslashes(nl2br($record_main['comments']));
  $opm_date = stripslashes($record_main['opm_date']);
  $opm_date_raw = stripslashes($record_main['opm_date_raw']);
  
  $sql = "SELECT a.project_sqft, a.code, b.company_name, c.site_name, concat(d.firstname, ' ', d.lastname) as fullname, e.master_name, e.logo as master_logo, c.image as propertyimage,
  b.logo as company_logo, d.email, a.master_id, a.property_id, a.prospect_id, a.project_id, a.produced_sqft
  from opm a, prospects b, properties c, users d, master_list e where
  a.prospect_id=b.prospect_id and
  a.property_id=c.property_id and
  a.user_id=d.user_id and
  a.master_id=e.master_id and 
  a.opm_id='$opm_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $project_sqft = stripslashes($record['project_sqft']);
  $code = stripslashes($record['code']);
  $company_name = stripslashes($record['company_name']);
  $site_name = stripslashes($record['site_name']);
  $fullname = stripslashes($record['fullname']);
  $master_name = stripslashes($record['master_name']);
  $master_logo = stripslashes($record['master_logo']);
  $propertyimage = stripslashes($record['propertyimage']);
  $company_logo = stripslashes($record['company_logo']);
  $email_from = stripslashes($record['email']);
  $master_id = stripslashes($record['master_id']);
  $property_id = stripslashes($record['property_id']);
  $prospect_id = stripslashes($record['prospect_id']);
  $project_id = stripslashes($record['project_id']);
  $produced_sqft = stripslashes($record['produced_sqft']);
  
  if($master_logo != "") {
    list($width, $height) = getimagesize("uploaded_files/master_logos/" . $master_logo);
    $make_width=240;
    $max_height = 200;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
    $master_logo = "<img src='" . $CORE_URL . "uploaded_files/master_logos/" . $master_logo . "' width='" . $make_width . "' height='" . $image_height . "'>";
  }
  else {
    $master_logo = $master_name;
  }
  
  if($company_logo != "") {
    list($width, $height) = getimagesize("uploaded_files/logos/" . $company_logo);
    $make_width=240;
    $max_height = 200;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
    $company_logo = "<img src='" . $CORE_URL . "uploaded_files/logos/" . $company_logo . "' width='" . $make_width . "' height='" . $image_height . "'>";
  }
  else {
    $company_logo = $company_name;
  }
  if($propertyimage != "") {
    list($width, $height) = getimagesize("uploaded_files/properties/" . $propertyimage);
    $make_width=250;
    $max_height = 250;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
    if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
    }
    $propertyimage = "<img src='" . $CORE_URL . "uploaded_files/properties/" . $propertyimage . "' width='" . $make_width . "' height='" . $image_height . "'>";
  }
  
  if($produced_sqft==0){
    $sql = "SELECT sum(replaced) from opm_entry where opm_id='$opm_id' and opm_date <= \"$opm_date_raw\"";
    $total_replaced = getsingleresult($sql);
  }
  else {
    $total_replaced = $produced_sqft;
  }
  
  $percent = round(($total_replaced / $project_sqft) * 100, 1);
  if($percent > 100) $percent = 100;
  $percent .= "%";
  
  $progress_url = $CORE_URL . "opm_s_project.php?xid=$code";
  
  $date_url = $CORE_URL . "opm_s_entryview.php?xid=" . $code . "&opm_entry_id=" . $opm_entry_id;
  
  $photo_table = "<table class='main' cellpadding='3'>";
  
  $sql = "SELECT photo, description from opm_entry_photos where opm_entry_id='$opm_entry_id' and photo != '' order by photo_id";
  $res_photo = executequery($sql);
  $counter = 0;
  while($photo = go_fetch_array($res_photo)){
    if($counter==0) $photo_table .= "<tr>";
    $photo_table .= "<td valign='top' align='center'>";
	$photo_table .= "<a href=\"" . $date_url . "\" target='_blank'>";
	$photo_table .= "<img src=\"" . $CORE_URL . "uploaded_files/opm_photos/" . $photo['photo'] . "\" width='195' border='0'></a><br>";
	$photo_table .= stripslashes($photo['description']);
	$photo_table .= "</td>";
	$counter++;
	if($counter==4){
	  $photo_table .= "</tr>";
	  $counter = 0;
	}
  }
  $photo_table .= "</tr></table>";
  
  $unsubscribe_url = $CORE_URL . "unsubscribe_opm.php?$code";
  
  $sql = "SELECT event_id from supercali_events where opm_id='$opm_id' and category_id=42";
  $event_id = getsingleresult($sql);
  $sql = "SELECT date_format(date, \"%Y-%m-%d\") as endpretty from supercali_dates where 
    event_id='$event_id' order by date desc limit 1";
    $result = executequery($sql);
    $record = go_fetch_array($result);
	$endpretty = $record['endpretty'];
	//echo $startpretty . "<br>";
	$span = GetDays($opm_date_raw, $endpretty, "+1 day");
	$fullDays = sizeof($span);
  if($fullDays < 1) $fullDays = 1;
  
  $sql = "SELECT body from templates where name='OPM Entry'";
  $body = getsingleresult($sql);
  
  $body = str_replace("[SITE NAME]", $site_name, $body);
  $body = str_replace("[COMPANY NAME]", $company_name, $body);
  $body = str_replace("[COMPANY LOGO]", $company_logo, $body);
  $body = str_replace("[MASTER LOGO]", $master_logo, $body);
  $body = str_replace("[DATE]", $opm_date, $body);
  $body = str_replace("[PROJECT MANAGER]", $fullname, $body);
  $body = str_replace("[PROPERTY IMAGE]", $propertyimage, $body);
  $body = str_replace("[PERCENT]", $percent, $body);
  $body = str_replace("[PROGRESS URL]", $progress_url, $body);
  $body = str_replace("[CORE URL]", $CORE_URL, $body);
  $body = str_replace("[COMMENTS]", $comments, $body);
  $body = str_replace("[PHOTO TABLE]", $photo_table, $body);
  $body = str_replace("[UNSUBSCRIBE URL]", $unsubscribe_url, $body);
  $body = str_replace("[PROJECT ID]", $project_id, $body);
  $body = str_replace("[NUMDAYS]", $fullDays, $body);
?>