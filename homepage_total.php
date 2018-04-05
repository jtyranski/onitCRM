<?php include "includes/header.php"; ?>
<?php if($SESSION_MASTER_ID != 1) exit;?>
<?php
$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") $order_by = "master_name";
if($order_by2 == "") $order_by2 = "asc";
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

if(!(is_array($_SESSION['master_list_total']))){
$counter = 0;
$sql = "SELECT master_id, master_name, date_format(date_created, \"%m/%d/%Y\") as date_created_pretty, date_created from master_list where active=1 and demo=0";
$result_master = executequery($sql);
while($master = go_fetch_array($result_master)){
  $master_id = $master['master_id'];
  $master_name = stripslashes($master['master_name']);
  $date_created_pretty = stripslashes($master['date_created_pretty']);
  $date_created = stripslashes($master['date_created']);
  
  $total_users = 0;
  $total_companies = 0;
  $total_vendors = 0;
  $total_sqft = 0;
  $total_sections = 0;
  $total_prospects = 0;
  $total_candidates = 0;
  $total_clients = 0;
  $contact_total =0;
  $inspection_total =0;
  $qb_total =0;
  $meeting_total=0;
  $totalquoted =0;
  $totalsold =0;
  $totaldead =0;
  $selltotal =0;
  $numbersold =0;
  $numberquoted =0;
  $numberdead =0;
  $total_sd = 0;
  
$sql = "SELECT count(*) from users where master_id='" . $master_id . "' and enabled=1 and resource=0";
$total_users = getsingleresult($sql);

$sql = "SELECT count(*) from prospects where master_id='" . $master_id . "' and display=1 and industry=0";
$total_companies = getsingleresult($sql);

$sql = "SELECT count(*) from prospects where master_id='" . $master_id . "' and display=1 and industry=1";
$total_vendors = getsingleresult($sql);

/*
$sql = "SELECT prospect_id, industry from prospects where master_id='" . $SESSION_MASTER_ID . "' and display=1 and industry=0";
$result = executequery($sql);
$total_properties = 0;
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $industry = $record['industry'];
  $sql = "SELECT count(*) from properties where prospect_id='$prospect_id' and corporate=0 and display=1";
  $sub_prop = getsingleresult($sql);
  if($sub_prop==0 && $industry==0) $sub_prop = 1;
  $total_properties += $sub_prop;
}
*/

$total_properties = 0;
// finds all properties, showing the count of corporate properties so it can use that to include a company as a property if there are no other properties.
$sql="SELECT a.prospect_id, COUNT(a.corporate) as count FROM properties a, prospects b WHERE a.prospect_id=b.prospect_id AND b.master_id='" . $master_id . "' AND b.display=1 and b.industry=0 AND a.display=1 GROUP BY a.prospect_id";

$result = executequery($sql);
while($record = go_fetch_array($result)){
	$count=$record['count'];
	if($count > 1) {
		$total_properties = $total_properties + ($count - 1);
	} else {
		$total_properties++;
	}
}


$sql = "SELECT sum(c.sqft) as total_sqft, count(c.section_id) as total_sections from prospects a, properties b, sections c where
a.prospect_id = b.prospect_id and b.property_id = c.property_id and a.master_id='" . $master_id . "' and a.display=1 and b.display=1";
$result = executequery($sql);
$record = go_fetch_array($result);
$total_sqft = $record['total_sqft'];
$total_sections = $record['total_sections'];
if($total_sqft=="") $total_sqft = 0;

$sql = "SELECT count(*)
	from prospects 
	where industry=0
    and display=1 and master_id='" . $master_id . "'";
$total_prospects = getsingleresult($sql);

$sql = "SELECT a.prospect_id
	  from prospects a, opportunities b
	  where a.master_id='" . $master_id . "'
	  and a.industry=0
	  and a.prospect_id = b.prospect_id and (b.status='Quoted')
	  and a.display=1 group by a.prospect_id";
$result = executequery($sql);
$total_candidates=0;
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  $exist[] = $prospect_id;
  $total_candidates++;
}
$sql = "SELECT a.prospect_id
	  from prospects a, activities b
	  where a.master_id='" . $master_id . "'
	  and a.industry=0
	  and a.prospect_id = b.prospect_id and b.display=1
	  and a.display=1 group by a.prospect_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $prospect_id = $record['prospect_id'];
  if(in_array($prospect_id, $exist)) continue;
  $total_candidates++;
}

$sql = "SELECT count(a.prospect_id)
	  from prospects a, opportunities b
	  where a.property_type != 'Contractor' 
	  and a.master_id='" . $master_id . "'
	  and a.prospect_id = b.prospect_id and (b.status='Sold' or b.status='Complete')
	  and b.display=1
	  and a.display=1 group by a.prospect_id";
$total_clients = getsingleresult($sql);
if($total_clients=="") $total_clients = 0;






$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "todate";

$wd = date('w') ;
$daterange = "1=1";
$forceblack = 0;
// this week
switch($searchby){
  case "todate":{
    $daterange = "1=1";
	$forceblack = 1;
	break;
  }
  case "today":{
    $daterange = "XXX like '" . date("Y") . "-" . date("m") . "-" . date("d") . "%'";
	$forceblack = 1;
	break;
  }
  case "yesterday":{
    $yesterday = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$daterange = "XXX like '" . $yesterday . "%' ";
	break;
  }
case "thisweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval 0 DAY) and XXX <= date_add(curdate(), interval 6 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -1 DAY) and XXX <= date_add(curdate(), interval 5 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -2 DAY) and XXX <= date_add(curdate(), interval 4 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -3 DAY) and XXX <= date_add(curdate(), interval 3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -4 DAY) and XXX <= date_add(curdate(), interval 2 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -5 DAY) and XXX <= date_add(curdate(), interval 1 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -6 DAY) and XXX <= date_add(curdate(), interval 0 DAY)';
    break;
  }
}
// end this week

break;
}

case "lastweek":{
// last week
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval -7 DAY) and XXX <= date_add(curdate(), interval -1 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -8 DAY) and XXX <= date_add(curdate(), interval -2 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -9 DAY) and XXX <= date_add(curdate(), interval -3 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -10 DAY) and XXX <= date_add(curdate(), interval -4 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -11 DAY) and XXX <= date_add(curdate(), interval -5 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -12 DAY) and XXX <= date_add(curdate(), interval -6 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -13 DAY) and XXX <= date_add(curdate(), interval -7 DAY)';
    break;
  }
}
// end last week
break;
}
  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	break;
  }
  
  case "lastmonth":{
    $d = date("j");
    $foo = 0;
    if($d==30 || $d==31) $foo = 3;
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d") - $foo,   date("Y"));
	$timesearch = date("Y-m", $lastmonth);
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	break;
  }
  
  case "ytd":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	break;
  }
  case "Custom":{
    $custom_startdate = $_GET['custom_startdate'];
	$custom_enddate = $_GET['custom_enddate'];
	$start_parts = explode("/", $custom_startdate);
    $startdate_pretty = $start_parts[2] . "-" . $start_parts[0] . "-" . $start_parts[1];
	$end_parts = explode("/", $custom_enddate);
    $enddate_pretty = $end_parts[2] . "-" . $end_parts[0] . "-" . $end_parts[1];
	$daterange = " XXX >= '$startdate_pretty' and XXX <= '$enddate_pretty' ";
	$forceblack=1;
	break;
  }
  
  default:{
    $daterange = "XXX like '$searchby%'";
	$forceblack = 1;
	break;
  }
  
} // end switch of searchby

$sched_daterange = $daterange;
$sched_daterange = str_replace("XXX", "date", $sched_daterange);
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $master_id . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $sql = "SELECT count(*) from activities where event='Contact' and $sched_daterange and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $contact_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Inspection' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $inspection_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Quickbid' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $qb_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Meeting' and $sched_daterange and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $meeting_total += getsingleresult($sql);
}


$sales_daterange = $daterange;
$sales_daterange = str_replace("XXX", "lastaction", $sales_daterange);
$opp_sold_daterange = $daterange;
$opp_sold_daterange = str_replace("XXX", "sold_date", $opp_sold_daterange);


$budget_total = 0;
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $master_id . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $sql = "SELECT sum(amount) from opportunities where status='Quoted' and $sales_daterange and user_id='$x_user_id' and display=1";
  $totalquoted += getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where status='Sold' and $opp_sold_daterange and user_id='$x_user_id' and display=1";
  $totalsold += getsingleresult($sql);
  
  $sql = "SELECT sum(amount) from opportunities where status='Dead' and $sales_daterange and user_id='$x_user_id' and display=1";
  $totaldead += getsingleresult($sql);
  $sql = "SELECT count(*) from opportunities where status='Sold' and amount > 0 and $opp_sold_daterange and user_id='$x_user_id' and display=1";
  $selltotal += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and status='Sold' and amount > 0 and $opp_sold_daterange and display=1";
  $numbersold += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and status='Quoted' and $sales_daterange and display=1";
  $numberquoted += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and status='Dead' and $sales_daterange and display=1";
  $numberdead += getsingleresult($sql);
  
}

$invoice_daterange = $daterange;
$invoice_daterange = str_replace("XXX", "a.invoice_date", $invoice_daterange);

$sql = "SELECT count(a.leak_id) from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='" . $master_id . "' 
and (a.status='Invoiced' or a.status='Closed Out' or a.status='Resolved') and $invoice_daterange";
$total_sd_done = getsingleresult($sql);

$sql = "SELECT count(a.leak_id) from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='" . $master_id . "' 
and $invoice_daterange";
$total_sd = getsingleresult($sql);

$sql = "SELECT * from master_list where master_id='" . $master_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$goal_fcs_users = $record['goal_fcs_users'];
$goal_companies = $record['goal_companies'];
$goal_properties = $record['goal_properties'];
$goal_contacts = $record['goal_contacts'];
$goal_meetings = $record['goal_meetings'];
$goal_inspections = $record['goal_inspections'];
$goal_dispatches = $record['goal_dispatches'];
$goal_quoted = $record['goal_quoted'];
$goal_sold = $record['goal_sold'];
$goal_quickbid = $record['goal_quickbid'];

$reach_fcs_users = $total_users;
if($reach_fcs_users > $goal_fcs_users) $reach_fcs_users = $goal_fcs_users;

$reach_companies = $total_companies;
if($reach_companies > $goal_companies) $reach_companies = $goal_companies;

$reach_properties = $total_properties;
if($reach_properties > $goal_properties) $reach_properties = $goal_properties;

$reach_contacts = $contact_total;
if($reach_contacts > $goal_contacts) $reach_contacts = $goal_contacts;

$reach_meetings = $meeting_total;
if($reach_meetings > $goal_meetings) $reach_meetings = $goal_meetings;

$reach_inspections = $inspection_total;
if($reach_inspections > $goal_inspections) $reach_inspections = $goal_inspections;

$reach_dispatches = $total_sd;
if($reach_dispatches > $goal_dispatches) $reach_dispatches = $goal_dispatches;

$reach_quoted = $numberquoted + $numbersold + $numberdead;
if($reach_quoted > $goal_quoted) $reach_quoted = $goal_quoted;

$reach_sold = $numbersold;
if($reach_sold > $goal_sold) $reach_sold = $goal_sold;

$reach_quickbid = $qb_total;
if($reach_quickbid > $goal_quickbid) $reach_quickbid = $goal_quickbid;

$goal_total = $goal_fcs_users + $goal_companies + $goal_properties + $goal_contacts + $goal_meetings + $goal_inspections + $goal_dispatches + $goal_quoted + $goal_sold + $goal_quickbid;
$reach_total = $reach_fcs_users + $reach_companies + $reach_properties + $reach_contacts + $reach_meetings + $reach_inspections + $reach_dispatches + $reach_quoted + $reach_sold + $reach_quickbid;
/*
echo "<!-- total $goal_total $reach_total -->\n";
echo "<!-- companies $goal_companies $reach_companies -->\n";
echo "<!-- properties $goal_properties $reach_properties -->\n";
echo "<!-- contacts $goal_contacts $reach_contacts -->\n";
echo "<!-- meetings $goal_meetings $reach_meetings -->\n";
echo "<!-- inspections $goal_inspections $reach_inspections -->\n";
echo "<!-- dispatches $goal_dispatches $reach_dispatches -->\n";
echo "<!-- quoted $goal_quoted $reach_quoted -->\n";
echo "<!-- sold $goal_sold $reach_sold -->\n";
echo "<!-- users $goal_fcs_users $reach_fcs_users -->\n";
*/

$percent = $reach_total / $goal_total;
$percent = $percent * 100;
$percent = ceil($percent);

  $row[$counter]['master_id'] = $master_id;
  $row[$counter]['master_name'] = $master_name;
  
  $row[$counter]['totalquoted'] = $totalquoted;
  $row[$counter]['totalsold'] = $totalsold;
  $row[$counter]['totaldead'] = $totaldead;
  
  $row[$counter]['numberquoted'] = $numberquoted;
  $row[$counter]['numbersold'] = $numbersold;
  $row[$counter]['numberdead'] = $numberdead;
  
  $row[$counter]['percent'] = $percent;
  
  $row[$counter]['date_created'] = $date_created;
  $row[$counter]['date_created_pretty'] = $date_created_pretty;
  
  $row[$counter]['total_users'] = $total_users;
  $row[$counter]['total_companies'] = $total_companies;
  $row[$counter]['total_vendors'] = $total_vendors;
  $row[$counter]['total_sqft'] = $total_sqft;
  $row[$counter]['total_sections'] = $total_sections;
  $row[$counter]['total_prospects'] = $total_prospects;
  $row[$counter]['total_candidates'] = $total_candidates;
  $row[$counter]['total_clients'] = $total_clients;
  $row[$counter]['contact_total'] = $contact_total;
  $row[$counter]['inspection_total'] = $inspection_total;
  $row[$counter]['qb_total'] = $qb_total;
  $row[$counter]['meeting_total'] = $meeting_total;
  $row[$counter]['total_sd'] = $total_sd;
  $row[$counter]['total_sd_done'] = $total_sd_done;
  $row[$counter]['total_properties'] = $total_properties;
  

  
  $counter++;
} // end of looping through master table
} // end if session exists
else {
  $row = $_SESSION['master_list_total'];
}
$_SESSION['master_list_total'] = $row;

usort($row, $function);
?>
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
  
  <div style="height:780px; overflow:scroll;">
<a href="homepage_total_excel.php?<?=$_SERVER['QUERY_STRING']?>" target="_blank">Export</a><br>
<table class="main" cellpadding="3" cellspacing="0" width="3700">
<tr>
<td width="250"><strong><?=sort_header('master_name', "Client")?></strong></td>
<td width="150"><strong><?=sort_header('date_created', "Created")?></strong></td>

<td align="right" width="150"><strong><?=sort_header('total_users', "Users")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_companies', "Companies")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_properties', "Properties")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_sections', "Sections")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_sqft', "Sqft")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_prospects', "Prospects")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_candidates', "Candidates")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_clients', "Clients")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_vendors', "Vendors")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('contact_total', "Contacts")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('meeting_total', "Meetings")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('inspection_total', "Inspections")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_sd', "Dispatches")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('total_sd_done', "Dispatches Done")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('qb_total', "Qwikbids")?></strong></td>


<td align="right" width="150"><strong><?=sort_header('totalquoted', "Quoted $")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('numberquoted', "Quoted #")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('totalsold', "Sold $")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('numbersold', "Sold #")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('totaldead', "Dead $")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('numberdead', "Dead #")?></strong></td>
<td align="right" width="150"><strong><?=sort_header('percent', "$MAIN_CO_NAME Certified")?></strong></td>
</tr>
<?php
for($x=0;$x<sizeof($row);$x++){
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=$row[$x]['master_name']?></td>
  <td><?=$row[$x]['date_created_pretty']?></td>
  
  <td align="right"><?=$row[$x]['total_users']?></td>
  <td align="right"><?=$row[$x]['total_companies']?></td>
  <td align="right"><?=$row[$x]['total_properties']?></td>
  <td align="right"><?=$row[$x]['total_sections']?></td>
  <td align="right"><?=$row[$x]['total_sqft']?></td>
  <td align="right"><?=$row[$x]['total_prospects']?></td>
  <td align="right"><?=$row[$x]['total_candidates']?></td>
  <td align="right"><?=$row[$x]['total_clients']?></td>
  <td align="right"><?=$row[$x]['total_vendors']?></td>
  <td align="right"><?=$row[$x]['contact_total']?></td>
  <td align="right"><?=$row[$x]['meeting_total']?></td>
  <td align="right"><?=$row[$x]['inspection_total']?></td>
  <td align="right"><?=$row[$x]['total_sd']?></td>
  <td align="right"><?=$row[$x]['total_sd_done']?></td>
  <td align="right"><?=$row[$x]['qb_total']?></td>
  
  
  <td align="right">$<?=number_format($row[$x]['totalquoted'], 2)?></td>
  <td align="right"><?=$row[$x]['numberquoted']?></td>
  <td align="right">$<?=number_format($row[$x]['totalsold'], 2)?></td>
  <td align="right"><?=$row[$x]['numbersold']?></td>
  <td align="right">$<?=number_format($row[$x]['totaldead'], 2)?></td>
  <td align="right"><?=$row[$x]['numberdead']?></td>
  <td align="right"><?=$row[$x]['percent']?>%</td>
  </tr>
  <?php
}
?>
</table>
  
</div>
</div>
</div>
</div>
<?php include "includes/footer.php"; ?>
