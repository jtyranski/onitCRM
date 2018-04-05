<?php include "includes/header.php"; ?>
<?php
$sql = "SELECT count(*) from users where master_id='" . $SESSION_MASTER_ID . "' and enabled=1 and resource=0";
$total_users = getsingleresult($sql);

$sql = "SELECT count(*) from prospects where master_id='" . $SESSION_MASTER_ID . "' and display=1 and industry=0";
$total_companies = getsingleresult($sql);

$sql = "SELECT count(*) from prospects where master_id='" . $SESSION_MASTER_ID . "' and display=1 and industry=1";
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
$sql="SELECT a.prospect_id, COUNT(a.corporate) as count FROM properties a, prospects b WHERE a.prospect_id=b.prospect_id AND b.master_id='" . $SESSION_MASTER_ID . "' AND b.display=1 and b.industry=0 AND a.display=1 GROUP BY a.prospect_id";

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
a.prospect_id = b.prospect_id and b.property_id = c.property_id and a.master_id='" . $SESSION_MASTER_ID . "' and a.display=1 and b.display=1";
$result = executequery($sql);
$record = go_fetch_array($result);
$total_sqft = $record['total_sqft'];
$total_sections = $record['total_sections'];
if($total_sqft=="") $total_sqft = 0;

$sql = "SELECT count(*)
	from prospects 
	where industry=0
    and display=1 and master_id='" . $SESSION_MASTER_ID . "'";
$total_prospects = getsingleresult($sql);

$sql = "SELECT a.prospect_id
	  from prospects a, opportunities b
	  where a.master_id='" . $SESSION_MASTER_ID . "'
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
	  where a.master_id='" . $SESSION_MASTER_ID . "'
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
	  and a.master_id='" . $SESSION_MASTER_ID . "'
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
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
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
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
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

$sql = "SELECT count(a.leak_id) from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='" . $SESSION_MASTER_ID . "' 
and (a.status='Invoiced' or a.status='Closed Out') and $invoice_daterange";
$total_sd = getsingleresult($sql);


$sql = "SELECT * from master_list where master_id='" . $SESSION_MASTER_ID . "'";
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

?>
<script src="includes/calendar.js"></script>
<script>
function datefilter_change(x){
  if(x.value=="Custom"){
    document.getElementById("customdate").style.display="";
  }
  else {
    document.getElementById("customdate").style.display="none";
  }
}
</script>

<div style="width:98%; position:relative;">

<div style="width:49%; float:left;">
  <div style="width:100%;" class="whiteround">
    <div style="position:relative;">
	<div style="float:left; font-size:20px; font-weight:bold; padding-right:10px;">
      <?=$percent?>%
	</div>
	<div style="float:left;">
      <div style="width:500px; border:5px solid black; height:20px;">
	    <div style="height:100%; width:<?=$percent?>%; background-color:#FF0000;">&nbsp;</div>
	  </div>
	</div>
	<div style="float:left;">
	<img src="images/fcs_certified.png">
	</div>
	</div>
	<div style="clear:both;"></div>
	<br>
    <div style="position:relative;">
	<div style="float:left; padding-right:40px;">
	<?php if($MASTER_LOGO != ""){ 
	list($width, $height) = getimagesize("uploaded_files/master_logos/" . $MASTER_LOGO);

        $make_width = 200;
		$max_height = 75;
        $ratio = $width / $make_width;
		
		$image_height = round($height / $ratio);
		
		if($image_height > $max_height){
          $ratio = $height / $max_height;
          $make_width = round($width / $ratio);
          $image_height = $max_height;
        }
	?>
	<img src="uploaded_files/master_logos/<?=$MASTER_LOGO?>" width="<?=$make_width?>" height="<?=$image_height?>">
	<?php } ?>
	</div>
	<div style="float:left;">
	
	<div style="position:relative;">
	<div style="float:left; padding-right:25px;">
	<strong>General</strong><br>
	<table class="main" cellpadding="5">
	<tr>
	<td align="right"><?=$MAIN_CO_NAME?> Users</td>
	<td align="right"><?=$total_users?> (<?=$goal_fcs_users?>)</td>
	</tr>
	<tr>
	<td align="right">Companies</td>
	<td align="right"><?=$total_companies?> (<?=$goal_companies?>)</td>
	</tr>
	<tr>
	<td align="right">Properties</td>
	<td align="right"><?=$total_properties?> (<?=$goal_properties?>)</td>
	</tr>
	<tr>
	<td align="right">Sections</td>
	<td align="right"><?=$total_sections?></td>
	</tr>
	<tr>
	<td align="right">Sqft Roofs</td>
	<td align="right"><?=$total_sqft?></td>
	</tr>
	</table>
	</div>
	<div style="float:left;">
	<strong>Contacts</strong>
	<br>
	<table class="main" cellpadding="5">
	<tr>
	<td align="right">Prospects</td>
	<td align="right"><?=$total_prospects?></td>
	</tr>
	<tr>
	<td align="right">Candidates</td>
	<td align="right"><?=$total_candidates?></td>
	</tr>
	<tr>
	<td align="right">Clients</td>
	<td align="right"><?=$total_clients?></td>
	</tr>
	<tr>
	<td align="right">Vendors</td>
	<td align="right"><?=$total_vendors?></td>
	</tr>
	</table>
	</div>
	</div>
	<div style="clear:both;"></div>
	
	
	</div>
	</div>
	<div style="clear:both;"></div>
  </div>
  <br>
  <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="datesearch">
  <select name="searchby" onchange="datefilter_change(this)">
<option value="todate"<?php if($searchby=="todate") echo " selected"; ?>>To Date</option>
<option value="today"<?php if($searchby=="today") echo " selected"; ?>>Today</option>
<option value="yesterday"<?php if($searchby=="yesterday") echo " selected"; ?>>Yesterday</option>
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected"; ?>>This Week</option>
<option value="lastweek"<?php if($searchby=="lastweek") echo " selected"; ?>>Last Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected"; ?>>This Month</option>
<option value="lastmonth"<?php if($searchby=="lastmonth") echo " selected"; ?>>Last Month</option>
<option value="ytd"<?php if($searchby=="ytd") echo " selected"; ?>>Year to Date</option>
<option value="Custom"<?php if($searchby=="Custom") echo " selected"; ?>>Custom</option>
<?php
for($x=2011;$x<date("Y");$x++){
  ?>
  <option value="<?=$x?>"<?php if($searchby==$x) echo " selected"; ?>><?=$x?></option>
  <?php
}
?>
</select>
<input type="submit" name="submit1" value="Search">
  <span id="customdate" style="color:white; <?php if($searchby != "Custom") {?> display:none;<?php } ?>">

  From:

  <input size="10" type="text" name="custom_startdate" value="<?=$custom_startdate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('custom_startdate',0)" align="absmiddle">

  To:

  <input size="10" type="text" name="custom_enddate" value="<?=$custom_enddate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('custom_enddate',0)" align="absmiddle">

  </span>
  </form>
  <div style="width:100%; position:relative;">
    <div style="float:left; width:30%; height:120px;" class="whiteround">
	<strong>Activities</strong>
	<table class="main" cellpadding="5">
	<tr>
	<td align="right">Contacts</td>
	<td align="right"><?=$contact_total?> (<?=$goal_contacts?>)</td>
	</tr>
	<tr>
	<td align="right">Meetings</td>
	<td align="right"><?=$meeting_total?> (<?=$goal_meetings?>)</td>
	</tr>
	</table>
	<br>
	</div>
	<div style="float:left; width:1%;"><img src="images/spacer.gif"></div>
	<div style="float:left; width:30%; height:120px;" class="whiteround">
	<strong>Service</strong>
	<br>
	<table class="main" cellpadding="5">
	<tr>
	<td align="right">Inspections</td>
	<td align="right"><?=$inspection_total?> (<?=$goal_inspections?>)</td>
	</tr>
	<tr>
	<td align="right">Dispatched</td>
	<td align="right"><?=$total_sd?> (<?=$goal_dispatches?>)</td>
	</tr>
	<tr>
	<td align="right">Qwikbids</td>
	<td align="right"><?=$qb_total?> (<?=$goal_quickbid?>)</td>
	</tr>
	</table>
	</div>
	<div style="float:left; width:1%;"><img src="images/spacer.gif"></div>
	<div style="float:left; width:30%; height:120px;" class="whiteround">
	<strong>Sales</strong>
	<br>
	<table class="main" cellpadding="5">
	<tr>
	<td align="right">Quoted</td>
	<td align="right">$<?=number_format($totalquoted, 2)?></td>
	<td align="right"><?=$numberquoted?> (<?=$goal_quoted?>)</td>
	</tr>
	<tr>
	<td align="right">Sold</td>
	<td align="right">$<?=number_format($totalsold, 2)?></td>
	<td align="right"><?=$numbersold?> (<?=$goal_sold?>)</td>
	</tr>
	<tr>
	<td align="right">Dead</td>
	<td align="right">$<?=number_format($totaldead, 2)?></td>
	<td align="right"><?=$numberdead?></td>
	</tr>
	</table>
	</div>
  </div>
  <div style="clear:both;"></div>
</div>

<div style="width:2%; float:left;"><img src="images/spacer.gif"></div>

<div style="width:49%; float:left;">
  <div style="width:100%; height:330px;" class="whiteround">
  <strong><?=$MAIN_CO_NAME?> News</strong>
    <div style="width:100%; height:320px; overflow:auto;">
    <?php
	$sql = "SELECT *, date_format(message_date, \"%m/%d/%Y\") as datepretty from homepage_messages where display=1 order by message_id desc";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <strong><?=$record['datepretty']?></strong> - <?=nl2br(stripslashes($record['message']))?><br><br>
	  <?php
	}
	?>
	</div>
  <br>
  </div>
</div>

</div>
<div style="clear:both;"></div>

<?php include "includes/footer.php"; ?>
