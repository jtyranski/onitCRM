<?php
$sql = "SELECT priority1, priority2, priority3 from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$priorty1 = stripslashes($record['priority1']);
$priorty2 = stripslashes($record['priority2']);
$priorty3 = stripslashes($record['priority3']);

$DENY = "";
$sql = "SELECT resource_id from am_leakcheck_resourcelog where leak_id='$leak_id' and response=-1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $DENY[] = $record['resource_id'];
}

$sql = "SELECT logo_map from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$LOGO_MAP = stripslashes(getsingleresult($sql));

/*
$sql = "SELECT a.company_name, a.prospect_id, a.logo, a.zip, b.labor_rate, b.labor_rate2, b.labor_rate3
from prospects a left join prospects_resources b on a.prospect_id = b.prospect_id where 
a.zip != '' and a.master_id='" . $SESSION_MASTER_ID . "' and a.resource=1";
$result = executequery($sql);
//echo "<!-- QQQ $sql -->\n";
echo "<script type='text/javascript' language='JavaScript'>";
while($record = go_fetch_array($result)){
  $fullname = stripslashes($record['fullname']);
  $prospect_id = stripslashes($record['prospect_id']);
  $logo = stripslashes($record['logo']);
  $googlemap_zipcode = stripslashes($record['zip']);
  $company_name = stripslashes($record['company_name']);
  $labor_rate = stripslashes($record['labor_rate']);
  $labor_rate2 = stripslashes($record['labor_rate2']);
  $labor_rate3 = stripslashes($record['labor_rate3']);
  
  if(strlen($googlemap_zipcode) > 5) $googlemap_zipcode = substr($googlemap_zipcode, 0, 5);
  
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
  
    $html = "<b>$company_name</b><br />";
	$html .= $priorty1 . " = $" . number_format($labor_rate, 2) . "/hr<br>";
	$html .= $priorty2 . " = $" . number_format($labor_rate2, 2) . "/hr<br>";
	$html .= $priorty3 . " = $" . number_format($labor_rate3, 2) . "/hr<br>";
    $html .= "$city, $state<br>";
	if(is_array($DENY)){
	  if(!in_array($prospect_id, $DENY)){
        $html .= "<a href=\"javascript:Assign($prospect_id, 1)\">Assign</a>";
	  }
    }
	else {
	  $html .= "<a href=\"javascript:Assign($prospect_id, 1)\">Assign</a>";
	}

    $html = go_reg_replace("\'", "", $html);
  
  
    
    echo "placeBadge($latitude, $longitude,'$html','$icon');";
    echo "\n";
    
  }
}
echo "</script>";
*/
// now just the servicemen.
$sql = "SELECT concat(a.firstname, ' ', a.lastname) as fullname, a.user_id, a.googlemap_zipcode
from users a where 
a.googlemap_zipcode != '' and a.master_id='" . $SESSION_MASTER_ID . "' and a.servicemen=1 and a.resource=0";
$result = executequery($sql);
echo "<script type='text/javascript' language='JavaScript'>";
while($record = go_fetch_array($result)){
  $fullname = stripslashes($record['fullname']);
  $user_id = stripslashes($record['user_id']);
  $googlemap_zipcode = stripslashes($record['googlemap_zipcode']);
  
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

      $newpoint = "";
	  $marker = "fcs";
	  if($MASTER_LOGO != ""){
	    $icon = $SITE_URL . "uploaded_files/master_logos/" . $MASTER_LOGO;
	  }
	  else {
	    $icon = $SITE_URL . "googlemap/fcs.jpg";
	  }

    $LatLng = $latitude . "," . $longitude;
  
    $html = "<b>$fullname</b><br />";
    $html .= "$city, $state<br>";
	$html .= "<a href=\"javascript:Assign($user_id, 0)\">Assign</a>";

    $html = go_reg_replace("\'", "", $html);
  
  
    
    echo "placeBadge($latitude, $longitude,'$html','$icon');";
    echo "\n";
    
  }
}
echo "</script>";
?>