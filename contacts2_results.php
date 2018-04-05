<?php
include "includes/functions.php";

$filterby = $_GET['filterby'];
$searchfor = $_GET['searchfor'];
$zip = $_GET['zip'];
$distance = $_GET['distance'];

$cand_type = $_GET['cand_type'];
$show_hidden = $_GET['show_hidden'];
$show_all_cand = $_GET['show_all_cand'];

$stage_filter = $_GET['stage_filter'];
$status_filter = $_GET['status_filter'];
$identifier_filter = $_GET['identifier_filter'];
if($stage_filter=="") $stage_filter=0;
if($status_filter=="") $status_filter = 0;
//if($identifier_filter=="") $identifier_filter = 0;
$resource_filter = $_GET['resource_filter'];
if($resource_filter=="") $resource_filter = 0;

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by2 == "") $order_by2 = $_SESSION['contacts2_order_by2'];
if($order_by2 == "") $order_by2 = "asc";

if($order_by=="") $order_by = $_SESSION['contacts2_order_by'];
if($order_by=="") $order_by = "company_name";

  $_SESSION[$sess_header . '_contacts2_filterby'] = $filterby;
  $_SESSION[$sess_header . '_contacts2_zip'] = $zip;
  $_SESSION[$sess_header . '_contacts2_distance'] = $distance;
  $_SESSION[$sess_header . '_contacts2_order_by'] = $order_by;
  $_SESSION[$sess_header . '_contacts2_order_by2'] = $order_by2;
  $_SESSION[$sess_header . '_contacts2_searchfor'] = $searchfor;
  $_SESSION[$sess_header . '_contacts2_cand_type'] = $cand_type;
  $_SESSION[$sess_header . '_contacts2_show_hidden'] = $show_hidden;
  $_SESSION[$sess_header . '_contacts2_show_all_cand'] = $show_all_cand;
  $_SESSION[$sess_header . '_contacts2_stage_filter'] = $stage_filter;
  $_SESSION[$sess_header . '_contacts2_status_filter'] = $status_filter;
  $_SESSION[$sess_header . '_contacts2_identifier_filter'] = $identifier_filter;
  $_SESSION[$sess_header . '_contacts2_resource_filter'] = $resource_filter;




$sql_order_by = $order_by;
if($sql_order_by=="lastaction_raw") $sql_order_by = "lastaction";
if($sql_order_by=="master_status") $sql_order_by = "master_status_id";
if($sql_order_by=="master_stage") $sql_order_by = "master_stage_id";

if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}
  
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

$sql = "SELECT * from master_stage";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_stage_id = $record['master_stage_id'];
  $master_stage = stripslashes($record['master_stage']);
  $MASTER_STAGE_ARRAY[$master_stage_id] = $master_stage;
}

$sql = "SELECT * from master_status";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $master_status_id = $record['master_status_id'];
  $master_status = stripslashes($record['master_status']);
  $MASTER_STATUS_ARRAY[$master_status_id] = $master_status;
}

$searchfor = go_escape_string($searchfor);
$filter_clause = " 1=1 ";
if($filterby != '' && $filterby != "0") $filter_clause = " a.$filterby like '%$searchfor%' ";

if($stage_filter != 0){
  $stage_clause = " master_stage_id = '$stage_filter' ";
}
else {
  $stage_clause = " 1=1 ";
}

if($status_filter != 0){
  $status_clause = " master_status_id = '$status_filter' ";
}
else {
  $status_clause = " 1=1 ";
}


if($identifier_filter=="0") $identifier_filter = "";




if($identifier_filter != ""){
  $identifier_clause = " identifier = '$identifier_filter' ";
}
else {
  $identifier_clause = " 1=1 ";
}

$hidden_clause = " 1=1 "; // just forcing it

$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  
  for($x=0;$x<sizeof($SESSION_GROUP_PROSPECT_ID);$x++){
    $pg .= $SESSION_GROUP_PROSPECT_ID[$x] . ",";
  }
  $pg = go_reg_replace("\,$", "", $pg);
  if($pg=="") $pg="0";
  $groups_clause = " a.prospect_id in ($pg) ";
}
	
$sql = "SELECT prospects_per_page from users where user_id='" . $SESSION_USER_ID . "'";
$prospects_per_page = getsingleresult($sql);
if($prospects_per_page=="" || $prospects_per_page < 1) $prospects_per_page = 2000;

$start_record = $_SESSION[$sess_header . '_contacts2_start_record'];
if($start_record=="") $start_record = 0;

switch($cand_type){
  case 0:{ // Prospects.  All, not industry
    //$cand_clause = "  ";
	$has_act_clause = " 1=1" ;
	if($SESSION_OPPORTUNITIES==1) $has_act_clause = " has_act=0 and has_opp=0 ";
	$sql = "SELECT prospect_id, company_name, city, state, zip, 
	properties, master_stage_id, master_status_id, irep, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty, lastaction, created_master_id
	from prospects a
	where $filter_clause
	and $stage_clause
	and $status_clause
	and $identifier_clause
	and a.industry=0
	and $has_act_clause
	and $groups_clause
	 and a.display=1 and a.master_id='" . $SESSION_MASTER_ID . "' order by a.$sql_order_by $order_by2";
	 $sql_jr = $sql;
	 $counter=0;
    $sql .= " ";
	$sql_count = $sql;
	if($zip =="") $sql .= " limit $start_record, $prospects_per_page";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $irep = $company['irep'];
	  if($SESSION_IREP==1){
	    if(!(go_reg("," . $SESSION_USER_ID . ",", $irep))) continue;
	  }
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $master_stage_id = stripslashes($company['master_stage_id']);
	  $master_status_id = stripslashes($company['master_status_id']);

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['lastaction_raw'] = stripslashes($company['lastaction']);
	  $row[$counter]['master_stage'] = $MASTER_STAGE_ARRAY[$master_stage_id];
	  $row[$counter]['master_status'] = $MASTER_STATUS_ARRAY[$master_status_id];
	  $row[$counter]['created_master_id'] = $company['created_master_id'];
		
	  $counter++;
	}
	
	break;
  }
  
  case 1:{ // Candidate - Any scheduled activity, or quoted opportunity
    //$cand_clause = " property_type != 'Contractor' and has_act=1 and has_opp=0 ";
	  $sql = "SELECT a.prospect_id, a.company_name, a.city, a.state, a.zip, 
	  a.properties, a.identifier, a.prospect_hidden, master_stage_id, master_status_id, 
	  date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.lastaction, a.created_master_id, a.irep
	  from prospects a, opportunities b
	  where a.master_id='" . $SESSION_MASTER_ID . "'
	  and a.industry=0
	  and a.prospect_id = b.prospect_id and (b.status='Quoted')
	  and $filter_clause and $hidden_clause 
	  and $stage_clause
	and $status_clause
	  and $identifier_clause
	  and $groups_clause
	  and a.display=1 group by a.prospect_id order by a.$sql_order_by $order_by2";
	  
	  $counter=0;
    $sql .= " ";
	$sql_count = $sql;
	if($zip =="") $sql .= " limit 2000";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $irep = $company['irep'];
	  if($SESSION_IREP==1){
	    if(!(go_reg("," . $SESSION_USER_ID . ",", $irep))) continue;
	  }
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $master_stage_id = stripslashes($company['master_stage_id']);
	  $master_status_id = stripslashes($company['master_status_id']);
	  
	  $exist[] = $prospect_id;

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['lastaction_raw'] = stripslashes($company['lastaction']);
	  $row[$counter]['master_stage'] = $MASTER_STAGE_ARRAY[$master_stage_id];
	  $row[$counter]['master_status'] = $MASTER_STATUS_ARRAY[$master_status_id];
	  $row[$counter]['created_master_id'] = $company['created_master_id'];
		
	  $counter++;
	}
	
	  $sql = "SELECT a.prospect_id, a.company_name, a.city, a.state, a.zip, 
	  a.properties, a.identifier, a.prospect_hidden, 
	  date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.lastaction, master_stage_id, master_status_id, a.created_master_id, a.irep
	  from prospects a, activities b
	  where a.master_id='" . $SESSION_MASTER_ID . "'
	  and a.industry=0
	  and a.prospect_id = b.prospect_id and b.display=1
	  and $filter_clause and $hidden_clause 
	  and $stage_clause
	  and $status_clause
	  and $identifier_clause
	  and $groups_clause
	  and a.display=1 group by a.prospect_id order by a.$sql_order_by $order_by2";
	  
    $sql .= " ";
	$sql_count = $sql;
	$sql_jr_test = $sql;
	if($zip =="") $sql .= " limit 2000";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $irep = $company['irep'];
	  if($SESSION_IREP==1){
	    if(!(go_reg("," . $SESSION_USER_ID . ",", $irep))) continue;
	  }
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $master_stage_id = stripslashes($company['master_stage_id']);
	  $master_status_id = stripslashes($company['master_status_id']);
	  if(in_array($prospect_id, $exist)) continue;

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['lastaction_raw'] = stripslashes($company['lastaction']);
	  $row[$counter]['master_stage'] = $MASTER_STAGE_ARRAY[$master_stage_id];
	  $row[$counter]['master_status'] = $MASTER_STATUS_ARRAY[$master_status_id];
	  $row[$counter]['created_master_id'] = $company['created_master_id'];
		
	  $counter++;
	}

	break;
  }
  case 2:{ // Client.  Yes Opportunity, opp status of sold, complete
    //$cand_clause = " property_type != 'Contractor' and has_opp=1 ";
	  $sql = "SELECT a.prospect_id, a.company_name, a.city, a.state, a.zip, 
	  a.properties, a.identifier, a.prospect_hidden, 
	  date_format(a.lastaction, \"%m/%d/%Y\") as lastaction_pretty, a.lastaction,
	  master_stage_id, master_status_id, created_master_id, a.irep
	  from prospects a, opportunities b
	  where a.property_type != 'Contractor' 
	  and a.master_id='" . $SESSION_MASTER_ID . "'
	  and a.prospect_id = b.prospect_id and (b.status='Sold' or b.status='Complete')
	  and b.display=1
	  and $filter_clause and $hidden_clause
	  and $stage_clause
	  and $status_clause
	  and $identifier_clause
	  and $groups_clause
	  and a.display=1 group by a.prospect_id order by a.$sql_order_by $order_by2";
	  
	  $counter=0;
    $sql .= " ";
	$sql_jr = $sql;
	$sql_count = $sql;
	if($zip =="") $sql .= " limit 2000";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $irep = $company['irep'];
	  if($SESSION_IREP==1){
	    if(!(go_reg("," . $SESSION_USER_ID . ",", $irep))) continue;
	  }
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $master_stage_id = stripslashes($company['master_stage_id']);
	  $master_status_id = stripslashes($company['master_status_id']);

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['lastaction_raw'] = stripslashes($company['lastaction']);
	  $row[$counter]['master_stage'] = $MASTER_STAGE_ARRAY[$master_stage_id];
	  $row[$counter]['master_status'] = $MASTER_STATUS_ARRAY[$master_status_id];
	  $row[$counter]['created_master_id'] = $company['created_master_id'];
		
	  $counter++;
	}

	break;
  }
  case 3:{ // Industry.  Contractors
    //$cand_clause = " property_type = 'Contractor' ";
	$sql = "SELECT prospect_id, company_name, city, state, zip, 
	properties, 
	date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty, lastaction, 
	master_stage_id, master_status_id, created_master_id
	from prospects a
	where $filter_clause
	and $stage_clause
	and $status_clause
	and $identifier_clause
	and a.industry=1";
	if($resource_filter==1) $sql .= " and a.resource=0 ";
	if($resource_filter==2) $sql .= " and a.resource=1 ";
	$sql .= " and a.display=1 and a.master_id='" . $SESSION_MASTER_ID . "' order by a.$sql_order_by $order_by2";
	 
	 $counter=0;
    $sql .= " ";
	$sql_count = $sql;
	if($zip =="") $sql .= " limit 2000";
	$result = executequery($sql);
	while($company = go_fetch_array($result)){
	  $prospect_id = $company['prospect_id'];
	  $company_name = stripslashes($company['company_name']);
	  $master_stage_id = stripslashes($company['master_stage_id']);
	  $master_status_id = stripslashes($company['master_status_id']);

	  $row[$counter]['company_name'] = $company_name;
	  $row[$counter]['prospect_id'] = $prospect_id;
	  $row[$counter]['city'] = stripslashes($company['city']);
	  $row[$counter]['state'] = stripslashes($company['state']);
	  $row[$counter]['properties'] = stripslashes($company['properties']);
	  $row[$counter]['lastaction'] = stripslashes($company['lastaction_pretty']);
	  $row[$counter]['zip'] = $company['zip'];
	  $row[$counter]['lastaction_raw'] = stripslashes($company['lastaction']);
	  $row[$counter]['master_stage'] = $MASTER_STAGE_ARRAY[$master_stage_id];
	  $row[$counter]['master_status'] = $MASTER_STATUS_ARRAY[$master_status_id];
	  $row[$counter]['created_master_id'] = $company['created_master_id'];
		
	  $counter++;
	}
	break;
  }
  
  case 4:{ // help
    $row = "";
	$sql = "SELECT now()";
	$sql_count = $sql;
	$zip = "";
	break;
  }
  
}


  
	
    


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
		$sql_new="select * from	zipcodes where zipcode='$zip' limit 1";
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
			    if($counter >= 2000) continue;
				$row2[$counter] = $row[$y];
			    $counter++;
				
			}

		}
		$row = $row2;
  }
}

  
  usort($row, $function);

$_SESSION[$sess_header . '_contacts_search_results'] = $row;
$_SESSION[$sess_header . '_contacts_order_by'] = $order_by;
$_SESSION[$sess_header . '_contacts_order_by2'] = $order_by2;
$_SESSION[$sess_header . '_contacts_search_display'] = "company";


if(sizeof($row) > 17){
  $header_width = 99;
}
else {
  $header_width = 100;
}

switch($cand_type){

  case 4:{ // help screen
    $html = "<div style='height:400px; width:100%; overflow:auto;'>";
	$html .= "<strong>Prospects</strong>: All of your records who are not Vendors.<br><br>";
	if($SESSION_OPPORTUNITIES==1){
	  $html .= "<strong>Candidate</strong>: Any record with a scheduled activity, or a quoted opportunity.<br><br>";
	  $html .= "<strong>Client</strong>: Any record with a sold opportunity.<br><br>";
	}
	$html .= "<strong>Vendor</strong>: Specially flagged records of companies you're not selling to. Could be suppliers, associates, or just the pizza place down the block.<br><br>";
	
	break;
  }
  
  default:{
    $html = "<div style='height:39px; width:" . $header_width . "%;' class='fancytable'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0'>";
	$html .= "<tr height='39px' valign='top' style='font-weight:bold;'>";
	if($SESSION_MASTER_ID==1){
	$html .= "<td width='32%'>";
	$html .= "<input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\" id='checkallbox'>";
	$html .= addslashes(sort_header2('company_name', 'Company Name')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('master_status', 'Status')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('master_stage', 'Stage')) . "</td>";
	$html .= "<td width='15%'>" . addslashes(sort_header2('city', 'City')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('state', 'State')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('properties', 'Props')) . "</td>";
	$html .= "<td width='8%'>" . addslashes(sort_header2('lastaction_raw', 'Last Action')) . "</td>";
	$html .= "<td width='5%'></td>";
	$html .= "<td width='5%'></td>";
	$html .= "<td width='5%'></td>";
	} 
	else {
	$html .= "<td width='60%'>";
	$html .= "<input type='checkbox' onchange=\\\"SetChecked(this, 'ids[]')\\\" id='checkallbox'>";
	$html .= addslashes(sort_header2('company_name', 'Company Name')) . "</td>";
	$html .= "<td width='20%'>" . addslashes(sort_header2('city', 'City')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('state', 'State')) . "</td>";
	$html .= "<td width='5%'>" . addslashes(sort_header2('properties', 'Props')) . "</td>";
	$html .= "<td width='10%'>" . addslashes(sort_header2('lastaction_raw', 'Last Action')) . "</td>";
	}
	$html .= "</tr>";
	$html .= "</table>";
	$html .= "</div>";
	
	$html .= "<div style='min-height:361px; width:100%; overflow:auto;'>";
	$html .= "<table class='main' width='100%' cellpadding='4' cellspacing='0' id='results_table'>";
		
	for($x=0;$x<sizeof($row);$x++){
	  $company_name = $row[$x]['company_name'];
	  $city = $row[$x]['city'];
	  $state = $row[$x]['state'];
	  $properties = $row[$x]['properties'];
	  $prospect_status = $row[$x]['prospect_status'];
	  //$identifier = $row[$x]['identifier'];
	  $lastaction = $row[$x]['lastaction'];
	  $master_status = $row[$x]['master_status'];
	  $master_stage = $row[$x]['master_stage'];
	  //$hidden = $row[$x]['hidden'];
	  $created_master_id = $row[$x]['created_master_id'];
	  $login = "";
	  $tools = "";
	  $props = "";
	  if($created_master_id != 0){
	    $login = "<a href='fcs_contractor_login_as.php?master_id=$created_master_id' target='_blank'>Login</a>";
		$tools = "<a href='fcs_contractors_tools.php?master_id=$created_master_id'>Tools</a>";
		$props = "<a href='fcs_contractors_properties.php?master_id=$created_master_id'>Props</a>";
	  }
	  
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
	  $ital = "";
	  $html .= "<tr id='" . $x . "' class='" . $class . "' onclick=\\\"javascript:selectRecord('" . $x . "')\\\" ondblclick=\\\"document.location.href='view_company.php?prospect_id=" . $row[$x]['prospect_id'] . "'\\\"";
	  //if($hidden) $ital = " style='font-style:italic;'";
	  $html .= ">";
	  if($SESSION_MASTER_ID==1){
	  $html .= "<td width='32%'$ital>";
	  $html .= "<input type='checkbox' name='ids[]' value='" . $row[$x]['prospect_id'] . "' onchange='showdelete()'>";
	  $html .= $company_name . "</td>";
	  $html .= "<td width='10%'$ital>" . $master_status . "</td>";
	  $html .= "<td width='10%'$ital>" . $master_stage . "</td>";
	  $html .= "<td width='15%'$ital>" . $city . "</td>";
	  $html .= "<td width='5%'$ital>" . $state . "</td>";
	  $html .= "<td width='5%'$ital>" . $properties . "</td>";
	  $html .= "<td width='8%'$ital>" . $lastaction . "</td>";
	  $html .= "<td width='5%'$ital>" . $props . "</td>";
	  $html .= "<td width='5%'$ital>" . $tools . "</td>";
	  $html .= "<td width='5%'$ital>" . $login . "</td>";
	  }
	  else {
	  $html .= "<td width='60%'$ital>";
	  $html .= "<input type='checkbox' name='ids[]' value='" . $row[$x]['prospect_id'] . "' onchange='showdelete()'>";
	  $html .= $company_name . "</td>";
	  $html .= "<td width='20%'$ital>" . $city . "</td>";
	  $html .= "<td width='5%'$ital>" . $state . "</td>";
	  $html .= "<td width='5%'$ital>" . $properties . "</td>";
	  $html .= "<td width='10%'$ital>" . $lastaction . "</td>";
	  }
	  $html .= "</tr>";
	  //echo "$x $company_name<br>";
	}
	$html .= "</table>";
	$html .= "<input type='hidden' name='boxtype' value='prospect'>";
	$html .= "</div>";
	
	break;
  }
}  


$numrecords = sizeof($row);
$result_count = executequery($sql_count);
$total_records = mysql_num_rows($result_count);
//$total_records = getsingleresult($sql);
if($zip != "") $total_records = $numrecords;

/*
if($_SESSION['fcsadmin_user_id']==1){
  echo $sql_jr_test;
  echo "<br>" . $_GET['identifier_filter'];
  echo "<br>$identifier_filter";
  echo "<br>$identifier_clause";
  exit;
}
*/
/*
if($_SESSION[$sess_header . '_backup_login']==10){
  echo $sql_count;
  exit;
}
*/

//echo $sql_jr . "***";
//exit;

$start_record_display = $start_record + 1;
$end_record_display = $numrecords + $start_record;

$shownext = 1;
$nextblock = "";
if(($prospects_per_page + $start_record) > $total_records) $shownext=0;
if($shownext){
  $nextblock = " <a href=\\\"javascript:nextGroup('1', '$order_by', '$order_by2', '$total_records')\\\"><img src='images/arrow_right.gif' border='0'></a> ";
}

$showprev = 1;
$prevblock = "";
if($start_record==0) $showprev = 0;
if($showprev){
  $prevblock = " <a href=\\\"javascript:nextGroup('-1', '$order_by', '$order_by2', '$total_records')\\\"><img src='images/arrow_left.gif' border='0'></a> ";
}

$lastblock = " <a href=\\\"javascript:nextGroup('2', '$order_by', '$order_by2', '$total_records')\\\"><img src='images/arrow_double_right.gif' border='0'></a> ";
$firstblock = " <a href=\\\"javascript:nextGroup('-2', '$order_by', '$order_by2', '$total_records')\\\"><img src='images/arrow_double_left.gif' border='0'></a> ";
?>
document.getElementById('search_results_wrapper').style.display="";

div = document.getElementById('search_results');
div.innerHTML = "<?php echo $html; ?>";
div.style.display = "";

div = document.getElementById('numrecords');
<?php if($cand_type==0){ ?>
div.innerHTML = "<?=$firstblock?> <?=$prevblock?> Showing <?=$start_record_display?> through <?php echo number_format($end_record_display, 0); ?> (<?=number_format($total_records, 0)?> total) <?=$nextblock?> <?=$lastblock?>";
<?php } else { ?>
div.innerHTML = "Showing <?php echo number_format($numrecords, 0); ?> Records (<?=number_format($total_records, 0)?> total)";
<?php } ?>
div = document.getElementById('filter_results_number');
div.innerHTML = "<?php echo number_format($numrecords, 0); ?> Records";

<?php
if($_SESSION[$sess_header . '_saved_selected_record'] != ""){
?>
selectRecord('<?=$_SESSION[$sess_header . '_saved_selected_record']?>');
<?php
$_SESSION[$sess_header . '_saved_selected_record'] = "";
}
?>
	  
	
	
  