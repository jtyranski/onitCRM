<?php
include "includes/functions.php";

$searchby = $_GET['searchby'];
$searchfor = $_GET['searchfor'];
$prospect_status_searchby = $_GET['prospect_status_searchby'];
$sales_stage_searchby = $_GET['sales_stage_searchby'];
$prospecting_searchby = $_GET['prospecting_searchby'];
$prospecting_beazer_searchby = $_GET['prospecting_beazer_searchby'];
$prospect_result_searchby = $_GET['prospect_result_searchby'];
$zip = $_GET['zip'];
$distance = $_GET['distance'];


$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by2 == "") $order_by2 = "asc";

if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}


include "contacts_search.php";

$_SESSION['contacts_search_results'] = $row;
$_SESSION['contacts_order_by'] = $order_by;
$_SESSION['contacts_order_by2'] = $order_by2;
$_SESSION['contacts_search_display'] = $search_display;


if(sizeof($row) > 17){
  $header_width = 99;
}
else {
  $header_width = 100;
}

switch($search_display){
  case "company":{
    $html = "<div style='height:39px; width:" . $header_width . "%;' class='fancytable'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0'>";
	$html .= "<tr height='39px' valign='top' style='font-weight:bold;'>";
	$html .= "<td width='50%'><input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\">" . addslashes(sort_header2('company_name', 'Company Name')) . "</td>";
	$html .= "<td width='30%'>" . addslashes(sort_header2('city', 'City')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('state', 'State')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('properties', 'Properties')) . "</td>";
	$html .= "</tr>";
	$html .= "</table>";
	$html .= "</div>";
	
	$html .= "<div style='height:361px; width:100%; overflow:auto;'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0' id='results_table'>";
	for($x=0;$x<sizeof($row);$x++){
	  $company_name = $row[$x]['company_name'];
	  $city = $row[$x]['city'];
	  $state = $row[$x]['state'];
	  $properties = $row[$x]['properties'];
	  
	  $company_name = go_reg_replace("\"", "", $company_name);
	  $city = go_reg_replace("\"", "", $city);
	  $state = go_reg_replace("\"", "", $state);
	  $properties = go_reg_replace("\"", "", $properties);
	  
	  
	  
	  if($x % 2){
        $class = "altrow";
      }
      else {
        $class = "mainrow";
      }
	  $html .= "<tr id='" . $x . "' class='" . $class . "' onclick=\\\"javascript:selectRecord('" . $x . "')\\\">";
	  $html .= "<td width='50%'><input type='checkbox' name='ids[]' value='" . $row[$x]['prospect_id'] . "'>" . $company_name . "</td>";
	  $html .= "<td width='30%'>" . $city . "</td>";
	  $html .= "<td width='10%'>" . $state . "</td>";
	  $html .= "<td width='10%'>" . $properties . "</td>";
	  $html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<input type='hidden' name='boxtype' value='prospect'>";
	$html .= "</div>";
	break;
  }
  
  case "property":{
    $html = "<div style='height:39px; width:" . $header_width . "%;' class='fancytable'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0'>";
	$html .= "<tr height='39px' valign='top' style='font-weight:bold;'>";
	$html .= "<td width='20%'><input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\">" . addslashes(sort_header2('site_name', 'Property')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('city', 'City')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('state', 'State')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('roof_size', 'Size')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('sales_stage', 'Sales Status')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('ro_status', 'RO Status')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('money', 'Comp')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('property_type', 'Type')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('lastaction', 'Last Action')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('territory', 'Region')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('rr_rm', 'RR/RM')) . "</td>";
  
	$html .= "</tr>";
	$html .= "</table>";
	$html .= "</div>";
	
	$html .= "<div style='height:361px; width:100%; overflow:auto;'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0' id='results_table'>";
	for($x=0;$x<sizeof($row);$x++){
      $site_name = $row[$x]['site_name'];
	  $city = $row[$x]['city'];
	  $state = $row[$x]['state'];
	  $roof_size = $row[$x]['roof_size'];
	  $sales_stage = $row[$x]['sales_stage'];
	  $ro_status = $row[$x]['ro_status'];
	  $money = $row[$x]['money'];
	  $property_type = $row[$x]['property_type'];
	  $lastaction = $row[$x]['lastaction'];
	  $territory = $row[$x]['territory'];
	  $rr_rm = $row[$x]['rr_rm'];
	  
	  
	  $site_name = go_reg_replace("\"", "", $site_name);
	  $city = go_reg_replace("\"", "", $city);
	  $state = go_reg_replace("\"", "", $state);
	  $sales_stage = go_reg_replace("\"", "", $sales_stage);
	  
	  if($x % 2){
        $class = "altrow";
      }
      else {
        $class = "mainrow";
      }
	  $html .= "<tr id='" . $x . "' class='" . $class . "' onclick=\\\"javascript:selectRecord('" . $x . "')\\\">";
	  $html .= "<td width='20%'><input type='checkbox' name='ids[]' value='" . $row[$x]['property_id'] . "'>" . $site_name . "</td>";
	  $html .= "<td width='10%'>" . $city . "</td>";
	  $html .= "<td width='5%'>" . $state . "</td>";
	  $html .= "<td width='5%'>" . $roof_size . "</td>";
	  $html .= "<td width='10%'>" . $sales_stage . "</td>";
	  $html .= "<td width='10%'>" . $ro_status . "</td>";
	  $html .= "<td width='10%'>" . $money . "</td>";
	  $html .= "<td width='10%'>" . $property_type . "</td>";
	  $html .= "<td width='10%'>" . $lastaction . "</td>";
	  $html .= "<td width='5%'>" . $territory . "</td>";
	  $html .= "<td width='5%'>" . $rr_rm . "</td>";
	  
	  $html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<input type='hidden' name='boxtype' value='property'>";
	$html .= "</div>";
	break;
  }
  
  case "contact":{
    $html = "<div style='height:39px; width:" . $header_width . "%;' class='fancytable'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0'>";
	$html .= "<tr height='39px' valign='top' style='font-weight:bold;'>";
	$html .= "<td width='25%'><input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\">" . addslashes(sort_header2('site_name', 'Property')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('city', 'City')) . "</td>";
	$html .= "<td width='4%'>" . addslashes(sort_header2('state', 'State')) . "</td>";
	$html .= "<td width='14%'>" . addslashes(sort_header2('fullname', 'Contact')) . "</td>";
	$html .= "<td width='9%'>" . addslashes(sort_header2('position', 'Position')) . "</td>";
	$html .= "<td width='9%'>" . addslashes(sort_header2('phone', 'Phone')) . "</td>";
	$html .= "<td width='9%'>" . addslashes(sort_header2('mobile', 'Mobile')) . "</td>";
	$html .= "<td width='20%'>" . addslashes(sort_header2('email', 'Email')) . "</td>";
  
	$html .= "</tr>";
	$html .= "</table>";
	$html .= "</div>";
	
	$html .= "<div style='height:361px; width:100%; overflow:auto;'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0' id='results_table'>";
	for($x=0;$x<sizeof($row);$x++){
      $site_name = $row[$x]['site_name'];
	  $city = $row[$x]['city'];
	  $state = $row[$x]['state'];
	  $fullname = $row[$x]['fullname'];
	  $position = $row[$x]['position'];
	  $phone = $row[$x]['phone'];
	  $mobile = $row[$x]['mobile'];
	  $email = $row[$x]['email'];
	  
	  
	  $site_name = go_reg_replace("\"", "", $site_name);
	  $city = go_reg_replace("\"", "", $city);
	  $state = go_reg_replace("\"", "", $state);
	  $fullname = go_reg_replace("\"", "", $fullname);
	  $position = go_reg_replace("\"", "", $position);
	  $phone = go_reg_replace("\"", "", $phone);
	  $mobile = go_reg_replace("\"", "", $mobile);
	  $email = go_reg_replace("\"", "", $email);
	  
	  if($x % 2){
        $class = "altrow";
      }
      else {
        $class = "mainrow";
      }
	  $html .= "<tr id='" . $x . "' class='" . $class . "' onclick=\\\"javascript:selectRecord('" . $x . "')\\\">";
	  $html .= "<td width='25%'><input type='checkbox' name='ids[]' value='" . $row[$x]['contact_id'] . "'>" . $site_name . "</td>";
	  $html .= "<td width='10%'>" . $city . "</td>";
	  $html .= "<td width='4%'>" . $state . "</td>";
	  $html .= "<td width='14%'>" . $fullname . "</td>";
	  $html .= "<td width='9%'>" . $position . "</td>";
	  $html .= "<td width='9%'>" . $phone . "</td>";
	  $html .= "<td width='9%'>" . $mobile . "</td>";
	  $html .= "<td width='20%'>" . $email . "</td>";
	  
	  $html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<input type='hidden' name='boxtype' value='contact'>";
	$html .= "</div>";
	break;
  }
  
  case "prospect_result":{
    $html = "<div style='height:39px; width:" . $header_width . "%;' class='fancytable'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0'>";
	$html .= "<tr height='39px' valign='top' style='font-weight:bold;'>";
	$html .= "<td width='50%'><input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\">" . addslashes(sort_header2('site_name', 'Property')) . "</td>";
	$html .= "<td width='20%'>" . addslashes(sort_header2('prospect_type', 'Type')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('datepretty', 'Date')) . "</td>";
	$html .= "<td width='20%'>" . addslashes(sort_header2('fullname', 'By')) . "</td>";
  
	$html .= "</tr>";
	$html .= "</table>";
	$html .= "</div>";
	
	$html .= "<div style='height:361px; width:100%; overflow:auto;'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0' id='results_table'>";
	for($x=0;$x<sizeof($row);$x++){
      $site_name = $row[$x]['site_name'];
	  $prospect_type = $row[$x]['prospect_type'];
	  $datepretty = $row[$x]['datepretty'];
	  $fullname = $row[$x]['fullname'];
	  
	  
	  $site_name = go_reg_replace("\"", "", $site_name);
	  $prospect_type = go_reg_replace("\"", "", $prospect_type);
	  $fullname = go_reg_replace("\"", "", $fullname);
	  
	  if($x % 2){
        $class = "altrow";
      }
      else {
        $class = "mainrow";
      }
	  $html .= "<tr id='" . $x . "' class='" . $class . "' onclick=\\\"javascript:selectRecord('" . $x . "')\\\">";
	  $html .= "<td width='50%'><input type='checkbox' name='ids[]' value='" . $row[$x]['property_id'] . "'>" . $site_name . "</td>";
	  $html .= "<td width='20%'>" . $prospect_type . "</td>";
	  $html .= "<td width='10%'>" . $datepretty . "</td>";
	  $html .= "<td width='20%'>" . $fullname . "</td>";
	  
	  $html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<input type='hidden' name='boxtype' value='property'>";
	$html .= "</div>";
	break;
  }
  
  case "importidentifier":{
    $html = "<div style='height:39px; width:" . $header_width . "%;' class='fancytable'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0'>";
	$html .= "<tr height='39px' valign='top' style='font-weight:bold;'>";
	$html .= "<td width='30%'><input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\">" . addslashes(sort_header2('company_name', 'Company Name')) . "</td>";
	$html .= "<td width='15%'>" . addslashes(sort_header2('city', 'City')) . "</td>";
	$html .= "<td width='4%'>" . addslashes(sort_header2('state', 'State')) . "</td>";
	$html .= "<td width='4%'>" . addslashes(sort_header2('properties', 'Props')) . "</td>";
	$html .= "<td width='15%'>" . addslashes(sort_header2('prospect_status', 'Status')) . "</td>";
	$html .= "<td width='22%'>" . addslashes(sort_header2('identifier', 'Identifier')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('lastaction', 'Last Action')) . "</td>";
	$html .= "</tr>";
	$html .= "</table>";
	$html .= "</div>";
	
	$html .= "<div style='height:361px; width:100%; overflow:auto;'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0' id='results_table'>";
	for($x=0;$x<sizeof($row);$x++){
	  $company_name = $row[$x]['company_name'];
	  $city = $row[$x]['city'];
	  $state = $row[$x]['state'];
	  $properties = $row[$x]['properties'];
	  $prospect_status = $row[$x]['prospect_status'];
	  $identifier = $row[$x]['identifier'];
	  $lastaction = $row[$x]['lastaction'];
	  
	  $company_name = go_reg_replace("\"", "", $company_name);
	  $city = go_reg_replace("\"", "", $city);
	  $state = go_reg_replace("\"", "", $state);
	  $properties = go_reg_replace("\"", "", $properties);
	  $prospect_status = go_reg_replace("\"", "", $prospect_status);
	  $identifier = go_reg_replace("\"", "", $identifier);
	  $lastaction = go_reg_replace("\"", "", $lastaction);
	  
	  if($x % 2){
        $class = "altrow";
      }
      else {
        $class = "mainrow";
      }
	  $html .= "<tr id='" . $x . "' class='" . $class . "' onclick=\\\"javascript:selectRecord('" . $x . "')\\\">";
	  $html .= "<td width='30%'><input type='checkbox' name='ids[]' value='" . $row[$x]['prospect_id'] . "'>" . $company_name . "</td>";
	  $html .= "<td width='15%'>" . $city . "</td>";
	  $html .= "<td width='4%'>" . $state . "</td>";
	  $html .= "<td width='4%'>" . $properties . "</td>";
	  $html .= "<td width='15%'>" . $prospect_status . "</td>";
	  $html .= "<td width='22%'>" . $identifier . "</td>";
	  $html .= "<td width='10%'>" . $lastaction . "</td>";
	  $html .= "</tr>";
	}
	$html .= "</table>";
	$html .= "<input type='hidden' name='boxtype' value='prospect'>";
	$html .= "</div>";
	break;
  }
  
}
$numrecords = sizeof($row);

?>
document.getElementById('search_results_wrapper').style.display="";

div = document.getElementById('search_results');
div.innerHTML = "<?php echo $html; ?>";
div.style.display = "";

div = document.getElementById('numrecords');
div.innerHTML = "<?php echo $numrecords; ?> Records";
	  
	
	
  