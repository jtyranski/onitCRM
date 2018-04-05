<?php
if($filter_resource=="") $filter_resource = "All"; // the map has this value, so default to all for other things, like dispatch select
switch($filter_resource){
  case "All":{
    $resource_clause = " 1=1 ";
	break;
  }
  case "None":{
    $resource_clause = " c.resource_type = 'NONE' ";
	break;
  }
  default:{
    $resource_clause = " c.resource_type = '$filter_resource' ";
	break;
  }
}

$sql = "SELECT logo_map from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$LOGO_MAP = stripslashes(getsingleresult($sql));

$sql = "SELECT concat(a.firstname, ' ', a.lastname) as fullname, b.company_name, a.user_id, b.logo, a.googlemap_zipcode, a.resource
from users a, prospects b, prospects_resources c where 
a.resource_id = b.prospect_id
and b.prospect_id=c.prospect_id
and $resource_clause
and a.googlemap_zipcode != '' and a.master_id='" . $SESSION_MASTER_ID . "' and a.resource=1";
$result = executequery($sql);
//echo "<!-- QQQ $sql -->\n";
echo "<script type='text/javascript' language='JavaScript'>";
while($record = go_fetch_array($result)){
  $fullname = stripslashes($record['fullname']);
  $user_id = stripslashes($record['user_id']);
  $logo = stripslashes($record['logo']);
  $googlemap_zipcode = stripslashes($record['googlemap_zipcode']);
  $resource = stripslashes($record['resource']);
  $company_name = stripslashes($record['company_name']);
  
  $latitude = "";
  if($googlemap_zipcode){
    $sql = "SELECT city, state, latitude, longitude from zipcodes where zipcode='$googlemap_zipcode'";
	$res_z = executequery($sql);
	$rec_z = go_fetch_array($res_z);
    $city = stripslashes($rec_z['city']);
    $state = stripslashes($rec_z['state']);
    $latitude = stripslashes($rec_z['latitude']);
    $longitude = stripslashes($rec_z['longitude']);
  
    $city = ucwords(strtolower($city));
  }
  

  
  if($latitude){
    if($logo != ""){
      $newpoint = "var fcs" . $user_id . " = new GIcon(badgeIcon, \"" . $SITE_URL . "uploaded_files/logos/" . $logo . "\", null, \"" . $SITE_URL . "uploaded_files/logos/" . $logo . "\");\n";
      $marker = "fcs" . $user_id;
	  $icon = $SITE_URL . "uploaded_files/logos/" . $logo;
    }
    else {
      $newpoint = "";
	  $marker = "fcs";
	  if($LOGO_MAP != ""){
	    $icon = $SITE_URL . "uploaded_files/master_logos/" . $LOGO_MAP;
	  }
	  else {
	    $icon = $SITE_URL . "googlemap/fcs.jpg";
	  }
    }
    $LatLng = $latitude . "," . $longitude;
  
    $html = "<b>$company_name<br>$fullname</b><br />";
    $html .= "$city, $state<br>";
	if($DISPATCH_MAP==1){ // assigned on fcs_sd_report_view
	  $html .= "<a href=\"javascript:Assign($user_id, $resource)\">Assign</a>";
	}
    $html = go_reg_replace("\'", "", $html);
  
  
    
    echo "placeBadge($latitude, $longitude,'$html','$icon');";
    echo "\n";
    
  }
}
echo "</script>";
?>