<?php
require_once "includes/functions.php";

function compare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function rcompare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return 1;
else
 return -1;
}

$submit1 = $_GET['submit1'];
if($submit1=="search"){
  $sql = "SELECT * from sales_stage order by sales_stage";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x = $record['sales_stage_id'];
	$sales_stage_array[$x] = stripslashes($record['sales_stage']);
  }

  $searchfor = go_escape_string($searchfor);
  switch($searchby){
  
  case "company_name":
  case "importidentifier":
  case "prospect_status":{
    $search_display = "company";
    if($order_by == "") $order_by = "company_name";
    if($searchby=="company_name") $special_search = " company_name like \"%$searchfor%\" ";
	if($searchby=="prospect_status") $special_search = " prospect_status =\"$prospect_status_searchby\" ";
	if($searchby=="importidentifier"){
	  $special_search = " identifier like \"%$searchfor%\" ";
	  $search_display = "importidentifier";
	}
    $searchlable = "Company Status" . sort_arrows('searchfield');
	
    $counter=0;
    $sql = "SELECT prospect_id, company_name, prospect_status, city, state, zip, 
	properties, property_type, identifier, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty
	from prospects 
	where $special_search and display=1 and master_id='" . $SESSION_MASTER_ID . "' order by company_name";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = "";
	  $row[$counter]['property_id'] = "";
	  $row[$counter]['number'] = "";
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['prospect_status'] = stripslashes($company['prospect_status']);
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['property_type'] = stripslashes($company['property_type']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['territory'] = "";
	  $row[$counter]['identifier'] = stripslashes($company['identifier']);
		
	  $counter++;
	}
	break;
  }
  
  default:{
    if($order_by == "") $order_by = "site_name";
    $special_search = " a.$searchby like \"%$searchfor%\" ";
	if($searchby=="prospecting_type") $special_search = " a.prospecting_type ='$prospecting_searchby' ";
	if($searchby=="beazer_claim_id") $special_search = " a.beazer_claim_id ='$prospecting_beazer_searchby' ";
	
    $counter=0;
	$search_display = "property";
	$searchlable = ucwords($searchby) . sort_arrows('searchfield');
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, a.$searchby as searchfield, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, a.territory, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, a.zip, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region, a.ro_status
	from 
	properties a, prospects b 
	where a.prospect_id = b.prospect_id and $special_search and a.corporate=0 and a.display=1 and b.master_id='" . $SESSION_MASTER_ID . "' order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $searchfield = stripslashes($property['searchfield']);
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }

	  if($property_type=="Beazer" || $property_type=="Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
		$sql = "SELECT beazer_status from properties_beazer where property_id='$property_id'";
		$beazer_status = stripslashes(getsingleresult($sql));
		$row[$counter]['beazer_status'] = $beazer_status;
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  $rr_rm = "";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Replacement'";
	  $rr = getsingleresult($sql);
	  if($rr != 0) $rr_rm = "RR";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Management'";
	  $rm = getsingleresult($sql);
	  if($rm != 0) {
	    if($rr_rm==""){
		  $rr_rm = "RM";
		}
		else {
		  $rr_rm .= "/RM";
		}
	  }
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];
	  
	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = $searchfield;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['property_type'] = $property_type;
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  $row[$counter]['ro_status'] = stripslashes($property['ro_status']);
	  $row[$counter]['zip'] = $property['zip'];
	  $row[$counter]['territory'] = $property['territory'];
	  $row[$counter]['rr_rm'] = $rr_rm;
	  
	  $counter++;
	}
	break;
  }
  
  case "key_number":
  case "beazer_file":
  case "beazer_status":{
    if($order_by == "") $order_by = "site_name";
    $searchlable = "";
	$search_display = "property";
	$number_query = "c.beazer_file as number";
	$table_query = "properties_beazer c ";
	$money_query = "c.settlement_number as money";
	$beazer_query = ", c.beazer_status";
	if($searchby=="key_number"){
	  $number_query = "c.key_number as number";
	  $table_query = "properties_manville c";
	  $money_query = "c.comp_amount as money";
	  $beazer_query = "";
	}
    $counter=0;
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, $number_query, 
	a.zip, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, $money_query, a.roof_size, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, a.territory, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region, a.ro_status" . $beazer_query . "
	from 
	properties a, prospects b, $table_query
	where a.prospect_id = b.prospect_id 
	and a.property_id = c.property_id 
	and c.$searchby like '$searchfor%' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $number = stripslashes($property['number']);
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  
	  $rr_rm = "";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Replacement'";
	  $rr = getsingleresult($sql);
	  if($rr != 0) $rr_rm = "RR";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Management'";
	  $rm = getsingleresult($sql);
	  if($rm != 0) {
	    if($rr_rm==""){
		  $rr_rm = "RM";
		}
		else {
		  $rr_rm .= "/RM";
		}
	  }
	  
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
		
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  $row[$counter]['beazer_status'] = stripslashes($property['beazer_status']);
	  
	  $row[$counter]['money'] = $property['money'];
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];
	  
	  $row[$counter]['property_type'] = "Beazer";

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = "";
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  $row[$counter]['ro_status'] = stripslashes($property['ro_status']);
	  $row[$counter]['zip'] = $property['zip'];
	  $row[$counter]['territory'] = $property['territory'];
	  $row[$counter]['rr_rm'] = $rr_rm;
	  
	  $counter++;
	}
	break;
  }

  
  case "contact":
  case "phone":
  case "email":{
    if($order_by == "") $order_by = "site_name";
    if($searchby=="contact") $special_search = " concat(a.firstname, ' ', a.lastname) like '%$searchfor%' ";
	if($searchby=="phone") $special_search = " a.phone like '%$searchfor%' ";
	if($searchby=="email") $special_search = " a.email like '%$searchfor%' ";
    $search_display = "contact";
    //$searchlable = "Contact" . sort_arrows('searchfield');
    $counter=0;
	
	$sql = "SELECT concat(a.firstname, ' ', a.lastname) as fullname, a.position, a.phone, a.mobile, a.email, a.property_id, a.prospect_id, 
	a.id
	from contacts a where
	$special_search";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $property_id = $record['property_id'];
	  $prospect_id = $record['prospect_id'];
	  $row[$counter]['property_id'] = stripslashes($record['property_id']);
	  $row[$counter]['prospect_id'] = stripslashes($record['prospect_id']);
	  $row[$counter]['fullname'] = stripslashes($record['fullname']);
	  $row[$counter]['position'] = stripslashes($record['position']);
	  $row[$counter]['phone'] = stripslashes($record['phone']);
	  $row[$counter]['mobile'] = stripslashes($record['mobile']);
	  $row[$counter]['email'] = stripslashes($record['email']);
	  $row[$counter]['contact_id'] = stripslashes($record['id']);
	  
	  if($property_id != 0){
	    $sql = "SELECT site_name, city, state, property_type, zip from properties where property_id='$property_id'";
		$r2 = executequery($sql);
		$rec2 = go_fetch_array($r2);
		$site_name = stripslashes($rec2['site_name']);
		$city = stripslashes($rec2['city']);
		$state = stripslashes($rec2['state']);
		$row[$counter]['site_name'] = $site_name;
		$row[$counter]['city'] = $city;
		$row[$counter]['state'] = $state;
		$row[$counter]['p_or_c'] = "p";
		$row[$counter]['property_type'] = $rec2['property_type'];
		$row[$counter]['zip'] = $rec2['zip'];
	  }
	  if($prospect_id != 0){
	    $sql = "SELECT company_name, city, state, zip from prospects where prospect_id='$prospect_id'";
		$r2 = executequery($sql);
		$rec2 = go_fetch_array($r2);
		$site_name = stripslashes($rec2['company_name']);
		$city = stripslashes($rec2['city']);
		$state = stripslashes($rec2['state']);
		$row[$counter]['site_name'] = $site_name . " (Company)";
		$row[$counter]['city'] = $city;
		$row[$counter]['state'] = $state;
		$row[$counter]['p_or_c'] = "c";
		$row[$counter]['property_type'] = "";
		$row[$counter]['zip'] = $rec2['zip'];
	  }
	  
	  $counter++;
	}
	  
	break;
  }
  
  case "id_status":{  // beazer only
    if($order_by == "") $order_by = "site_name";
    $searchlable = "ID Status" . sort_arrows('searchfield');
	$search_display = "property";
    $counter=0;
	
	$sql = "SELECT id, status from beazer_id_status where status like '$searchfor%'";
	$result_stat = executequery($sql);
	while($stat = go_fetch_array($result_stat)){
	$status = $stat['status'];
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, c.beazer_file, c.beazer_id_status, 
	a.zip, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, a.sales_stage, a.roof_size, a.territory, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region, a.ro_status
	from 
	properties a, prospects b, properties_beazer c
	where a.prospect_id = b.prospect_id 
	and a.property_id = c.property_id 
	and c.beazer_id_status ='" . $stat['id'] . "' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $beazer_file = stripslashes($property['beazer_file']);
	  $beazer_id_status = stripslashes($property['beazer_id_status']);
	  
	  $money = "";
	  if($property['property_type']=="Beazer" || $property['property_type']=="Beazer B"){
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
		$sql = "SELECT beazer_status from properties_beazer where property_id='$property_id'";
		$beazer_status = stripslashes(getsingleresult($sql));
		$row[$counter]['beazer_status'] = $beazer_status;
	  }
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  
	  $rr_rm = "";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Replacement'";
	  $rr = getsingleresult($sql);
	  if($rr != 0) $rr_rm = "RR";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Management'";
	  $rm = getsingleresult($sql);
	  if($rm != 0) {
	    if($rr_rm==""){
		  $rr_rm = "RM";
		}
		else {
		  $rr_rm .= "/RM";
		}
	  }
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $sales_stage = $property['sales_stage'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage];

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $beazer_file;
	  $row[$counter]['searchfield'] = $status;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['property_type'] = "Beazer";
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  $row[$counter]['ro_status'] = stripslashes($property['ro_status']);
	  $row[$counter]['zip'] = $property['zip'];
	  $row[$counter]['territory'] = $property['territory'];
	  $row[$counter]['rr_rm'] = $rr_rm;
	  
	  $counter++;
	}
	}
	break;
  }
  
  case "sales_stage":{
    if($order_by == "") $order_by = "site_name";
    $searchlable = "Sales Stage" . sort_arrows('searchfield');
	$search_display = "property";
    $counter=0;
	
    $sql = "SELECT sales_stage_id from sales_stage where sales_stage like '$searchfor%'";
	$result_stage = executequery($sql);
	while($rec_stage = go_fetch_array($result_stage)){
	
	
	$sales_stage_searchby = $rec_stage['sales_stage_id'];
    
	$sql = "SELECT a.site_name, a.property_id, a.property_type, b.company_name, a.prospect_id, d.sales_stage, 
	a.zip, 
	a.address, a.city, a.state, a.identifier, b.prospect_status, d.sales_stage_id, a.roof_size, a.territory, 
	date_format(a.sales_stage_change_date, \"%m/%d/%Y\") as ssdate, 
	date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.region, a.ro_status
	from 
	properties a, prospects b, sales_stage d
	where a.prospect_id = b.prospect_id 
	and a.sales_stage = d.sales_stage_id 
	and a.sales_stage = '$sales_stage_searchby' and a.corporate=0 and a.display=1 order by a.site_name";
	$result = executequery($sql);
	while($property = go_fetch_array($result)){
	  $site_name = stripslashes($property['site_name']);
	  $property_id = stripslashes($property['property_id']);
	  $property_type = stripslashes($property['property_type']);
	  $company_name = stripslashes($property['company_name']);
	  $prospect_id = stripslashes($property['prospect_id']);
	  $key_number = stripslashes($property['key_number']);
	  $sales_stage = stripslashes($property['sales_stage']);
	  
 
	  if($property_type=="Manville"){
		$sql = "SELECT key_number from properties_manville where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT comp_amount from properties_manville where property_id='$property_id'";
		$money = getsingleresult($sql);
	  }
	  if($property_type=="Beazer" || $property_type=="Beazer B"){
		$sql = "SELECT beazer_file from properties_beazer where property_id='$property_id'";
		$number = getsingleresult($sql);
		$sql = "SELECT id_status from properties_beazer where property_id='$property_id'";
		$id_status = getsingleresult($sql);
		$row[$counter]['id_status'] = $id_status;
		$sql = "SELECT settlement_number from properties_beazer where property_id='$property_id'";
		$money = getsingleresult($sql);
		$sql = "SELECT beazer_status from properties_beazer where property_id='$property_id'";
		$beazer_status = stripslashes(getsingleresult($sql));
		$row[$counter]['beazer_status'] = $beazer_status;
	  }
	  if($property_type=="Non-PFRI"){
		$number = "";
		$money = "";
	  }
	  
	  $address = stripslashes($property['address']);
	  $city = stripslashes($property['city']);
	  $state = stripslashes($property['state']);
	  
	  $rr_rm = "";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Replacement'";
	  $rr = getsingleresult($sql);
	  if($rr != 0) $rr_rm = "RR";
	  $sql = "SELECT count(*) from opportunities where property_id='$property_id' and display=1 and product='Roof Management'";
	  $rm = getsingleresult($sql);
	  if($rm != 0) {
	    if($rr_rm==""){
		  $rr_rm = "RM";
		}
		else {
		  $rr_rm .= "/RM";
		}
	  }
	  $row[$counter]['address'] = $address;
	  $row[$counter]['city'] = $city;
	  $row[$counter]['state'] = $state;
	  $row[$counter]['identifier'] = stripslashes($property['identifier']);
	  $row[$counter]['prospect_status'] = stripslashes($property['propsect_status']);
	  
	  $sales_stage_id = $property['sales_stage_id'];
	  $row[$counter]['sales_stage'] = $sales_stage_array[$sales_stage_id];

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['site_name'] = $site_name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['number'] = $number;
	  $row[$counter]['searchfield'] = $sales_stage;
	  $row[$counter]['money'] = $money;
	  $row[$counter]['roof_size'] = stripslashes($property['roof_size']);
	  $row[$counter]['property_type'] = $property_type;
	  $row[$counter]['ssdate'] = $property['ssdate'];
	  $row[$counter]['lastaction'] = stripslashes($property['lastaction_pretty']);
	  $row[$counter]['region'] = $property['region'];
	  $row[$counter]['ro_status'] = stripslashes($property['ro_status']);
	  $row[$counter]['zip'] = $property['zip'];
	  $row[$counter]['territory'] = $property['territory'];
	  $row[$counter]['rr_rm'] = $rr_rm;
	  $counter++;
	}
	
	}
	break;
  }
  
  case "prospect_result":{
    $search_display = "prospect_result";
	if($order_by == "") $order_by = "site_name";
	
	$sql = "SELECT a.prospect_type, date_format(a.prospect_date, \"%m/%d/%y\") as datepretty, b.site_name, 
	concat(c.firstname, ' ', c.lastname) as fullname, a.property_id, a.prospect_id, b.property_type, b.zip from
	stats_prospects a, properties b, users c where
	a.property_id=b.property_id and a.user_id = c.user_id and a.prospect_result='$prospect_result_searchby'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $row[$counter]['site_name'] = stripslashes($record['site_name']);
	  $row[$counter]['company_name'] = stripslashes($record['site_name']); // faking the default sort by
	  $row[$counter]['prospect_type'] = stripslashes($record['prospect_type']);
	  $row[$counter]['datepretty'] = stripslashes($record['datepretty']);
	  $row[$counter]['fullname'] = stripslashes($record['fullname']);
	  $row[$counter]['property_id'] = stripslashes($record['property_id']);
	  $row[$counter]['prospect_id'] = stripslashes($record['prospect_id']);
	  $row[$counter]['property_type'] = stripslashes($record['property_type']);
	  $row[$counter]['zip'] = $record['zip'];
	  $counter++;
	}
	
	break;
  }
	  
	  
  
  } // end switch	
  

if($zip){
  if($distance){
        $srch_serv_prov = array();
		
		$latitude = array();
		$longitute = array();
		$sqlzips = "SELECT * from zipcodes";
		$resultzips = executequery($sqlzips);
		while($record = go_fetch_array($resultzips)){
		  $x = $record['zipcode'];
		  $latitude[$x] = $record['latitude'];
		  $longitude[$x] = $record['longitude'];
		}
		$sql_new="select * from	zipcodes where zipcode='$zip'";
		$result2=executeQuery($sql_new);
		if($line2=go_fetch_array($result2))
		{
			$search_longitude=$line2["longitude"];
			$search_latitude=$line2["latitude"];
		}
		$counter = 0;
	    for($y=0;$y<sizeof($row);$y++){
		  $x = $row[$y]['zip'];
			$x = go_reg_replace("\-.*", "", $x);
			//$distance2=great_circle_distance($search_latitude,$line['latitude'],$search_longitude,$line['longitude']);
			$distance2=great_circle_distance($search_latitude,$latitude[$x],$search_longitude,$longitude[$x]);
			//echo "<br>distance2:$distance2<br>";
			if($distance2 <= $distance)
			{
				$row2[$counter] = $row[$y];
			    $counter++;
			}
		}
		$row = $row2;
  }
}
 

if($_SESSION['ro_irep'] != ""){
  switch($search_display){
    case "property":{
	  $counter = 0;
	  for($x=0;$x<sizeof($row);$x++){
	    $property_id = $row[$x]['property_id'];
	    $sql = "SELECT property_id from properties where property_id='$property_id' and account_manager='" . $_SESSION['ro_irep'] . "'";
	    $test = getsingleresult($sql);
	    if($test != ""){
	      $row2[$counter] = $row[$x];
		  $counter++;
	    }
	  }
	  break;
	}
	
	case "prospect_result":{
	  $counter = 0;
	  for($x=0;$x<sizeof($row);$x++){
	    $property_id = $row[$x]['property_id'];
	    $sql = "SELECT property_id from properties where property_id='$property_id' and account_manager='" . $_SESSION['ro_irep'] . "'";
	    $test = getsingleresult($sql);
	    if($test != ""){
	      $row2[$counter] = $row[$x];
		  $counter++;
	    }
	  }
	  break;
	}
	
	case "company":{
	  $counter = 0;
	  for($x=0;$x<sizeof($row);$x++){
	    $prospect_id = $row[$x]['prospect_id'];
	    $sql = "SELECT prospect_id from prospects where prospect_id='$prospect_id' and account_manager like '%," . $_SESSION['ro_irep'] . ",%'";
	    $test = getsingleresult($sql);
	    if($test != ""){
	      $row2[$counter] = $row[$x];
		  $counter++;
	    }
	  }
	  break;
	}
	
	case "importidentifier":{
	  $counter = 0;
	  for($x=0;$x<sizeof($row);$x++){
	    $prospect_id = $row[$x]['prospect_id'];
	    $sql = "SELECT prospect_id from prospects where prospect_id='$prospect_id' and account_manager like '%," . $_SESSION['ro_irep'] . ",%'";
	    $test = getsingleresult($sql);
	    if($test != ""){
	      $row2[$counter] = $row[$x];
		  $counter++;
	    }
	  }
	  break;
	}
	
	case "contact":{
	  $counter = 0;
	  for($x=0;$x<sizeof($row);$x++){
	    $property_id = $row[$x]['property_id'];
		$prospect_id = $row[$x]['prospect_id'];
		$p_or_c = $row[$x]['p_or_c'];
		if($p_or_c == "p"){
	      $sql = "SELECT property_id from properties where property_id='$property_id' and account_manager='" . $_SESSION['ro_irep'] . "'";
	    }
		else {
		  $sql = "SELECT prospect_id from prospects where prospect_id='$prospect_id' and account_manager like '%," . $_SESSION['ro_irep'] . ",%'";
		}
		$test = getsingleresult($sql);
	    if($test != ""){
	      $row2[$counter] = $row[$x];
		  $counter++;
	    }
	  }
	  break;
	}
  }
  
  $row = $row2;
}
  
		  
  
  usort($row, $function);
}