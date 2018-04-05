<?php
exit;
include "includes/functions.php";

function remove_non_numeric($string) {

return preg_replace('/\D/', '', $string);

}

$sql = "SELECT a.cellphone, b.cell_extension from users a, cell_providers b where a.cell_id=b.cell_id and a.enabled=1 
and a.alert_inspection_approval=1 and a.cell_id != 0 and a.cellphone != '' and a.user_id!=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $cellphone = remove_non_numeric(stripslashes($record['cellphone']));
  $cell_extension = stripslashes($record['cell_extension']);
  
  $text[] = $cellphone . "@" . $cell_extension;
}

$sql = "SELECT property_id, site_name from properties where text_alert_sent=0 and (ready_for_pre_approval=1 or ready_for_email=1)";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = stripslashes($record['property_id']);
  $site_name = stripslashes($record['site_name']);
  echo $property_id . "<br>";
  $message = $site_name . " needs approval. http://www.encitegroup.com/roofoptions/ipad/toolbox.php?go=report_inspectionapproval.php";
  if(is_array($text)){
    for($x=0;$x<sizeof($text);$x++){
	  email_q($text[$x], "", $message, "From:info@roofoptions.com");
	  echo $text[$x] . "<br>";
	}
  }
  
  $sql = "UPDATE properties set text_alert_sent=1 where property_id='$property_id'";
  executeupdate($sql);
}
?>