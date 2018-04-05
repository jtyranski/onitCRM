<?php include "includes/header_white.php"; ?>
<meta http-equiv="refresh" content="60;url=<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>">
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
function ShowHideDetails(area, x){
  if(x==1){
    document.getElementById(area + "_details").style.display="";
	document.getElementById(area + "_arrow").innerHTML = "<a href=\"javascript:ShowHideDetails('" + area + "', '0')\"><img src=\"images/redarrow_down.png\" border=\"0\"></a>";
  }
  else {
    document.getElementById(area + "_details").style.display="none";
	document.getElementById(area + "_arrow").innerHTML = "<a href=\"javascript:ShowHideDetails('" + area + "', '1')\"><img src=\"images/redarrow_up.png\" border=\"0\"></a>";
  }
}
</script>
<script src="piechart.js"></script>
<!--[if IE]><script src="excanvas.js"></script><![endif]-->
<style>
</style>
<script>
function createPieChart(prospects,candidates,clients) {
	// convert variables from % to degrees
	prospects_deg = prospects * 360;
	candidates_deg = candidates * 360;
	clients_deg = clients * 360;

	// create a PieChart.
	var pieChart = new PieChart( "piechart", 
		{
			includeLabels: true, 
			data: [prospects_deg, candidates_deg, clients_deg],
			labels: [prospects*100 + "%", candidates*100 + "%", clients*100 + "%"],
			colors: [
            	["#ff0000", "#ff0000"],  // red (prospects)
            	["#ffff00", "#ffff00"],  // yellow (candidates)
            	["#07ff02", "#07ff02"]   // green (clients)
			]
		}
	);

	//
	// nothing appears until you call draw().
	//
	pieChart.draw();

/*
 * If you want to draw the labels separately, you can set
 * includeLabels to false, and call drawLabel() for each
 * pie chart segment.
 *
	for (var i = 0; i < pieChart.labels.length; i++) {
		pieChart.drawLabel(i);
	}
 */

/*
 * If you want to select a segment to highight it, you can
 * call select() for a given segment. Here's a little snippet
 * that animates selecting each segment.
 *
	var segment = 0;
	function nextSegment() {
		pieChart.select(segment);
		segment++;
		if (segment < pieChart.data.length) {
			setTimeout(nextSegment, 1000);
		}
	}
	setTimeout(nextSegment, 1000);
 */

}
</script>
<?php
$searchby = $_GET['searchby'];
$order_by = "rank_points";
$submit1 = $_GET['submit1'];
$rate_calc = $_GET['rate_calc'];
$user_id = $_SESSION['user_id'];
$user_type = $_GET['user_type'];

if($searchby=="") $searchby = "thisweek";

$wd = date('w') ;
$daterange = "1=1";
$forceblack = 0;
// this week
switch($searchby){

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
$dv = 52;
$goalfiller = "Weekly";
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
$dv = 52;
$goalfiller = "Weekly";
break;
}
  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	$dv = 12;
	$goalfiller = "Monthly";
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
	$dv = 12;
	$goalfiller = "Monthly";
	break;
  }
  
  case "ytd":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	$dv = 1;
	$goalfiller = "Yearly";
	break;
  }
  
  default:{ // past years
    $daterange = "XXX like '$searchby%'";
	$forceblack = 1;
	$dv = 1;
	$goalfiller = "Yearly";
	break;
  }
  
} // end switch of searchby

$budget_snapshot_daterange = $daterange;
$budget_snapshot_daterange = str_replace("XXX", "ts", $budget_snapshot_daterange);
// ********* PROSPECTING activity **************

$order_by_prospect = $_GET['order_by_prospect'];

$p_daterange = $daterange;
$p_daterange = str_replace("XXX", "prospect_date", $p_daterange);

$opp_budget_daterange = $daterange;
$opp_budget_daterange = str_replace("XXX", "budget_set_date", $opp_budget_daterange);

$opp_pro_daterange = $daterange;
$opp_pro_daterange = str_replace("XXX", "pro_set_date", $opp_pro_daterange);

$opp_won_daterange = $daterange;
$opp_won_daterange = str_replace("XXX", "won_set_date", $opp_won_daterange);


$sql = "SELECT ap_salesgoal, ap_increase, ap_contract_percent, ap_contract_dollar, ap_contract_close, ap_contract_meetings, 
ap_contract_contacts, ap_service_percent, ap_service_dollar, ap_service_close, ap_tm, ap_service_meetings, 
ap_service_contacts, objective_met_label from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ap_salesgoal = stripslashes($record['ap_salesgoal']);
$ap_increase = stripslashes($record['ap_increase']);
$ap_contract_percent = stripslashes($record['ap_contract_percent']);
$ap_contract_dollar = stripslashes($record['ap_contract_dollar']);
$ap_contract_close = stripslashes($record['ap_contract_close']);
$ap_contract_meetings = stripslashes($record['ap_contract_meetings']);
$ap_contract_contacts = stripslashes($record['ap_contract_contacts']);
$ap_service_percent = stripslashes($record['ap_service_percent']);
$ap_service_dollar = stripslashes($record['ap_service_dollar']);
$ap_service_close = stripslashes($record['ap_service_close']);
$ap_tm = stripslashes($record['ap_tm']);
$ap_service_meetings = stripslashes($record['ap_service_meetings']);
$ap_service_contacts = stripslashes($record['ap_service_contacts']);

$objective_met_label = stripslashes($record['objective_met_label']);

if($ap_salesgoal==0){
  echo "You must first enter your ActionPlan Sales Goal in the admin area before you can view this report";
  exit;
}

$yearly_contract_dollar = round($ap_salesgoal * ($ap_contract_percent / 100));
$yearly_contract_number = round($yearly_contract_dollar / $ap_contract_dollar);

$yearly_contract_sold_dollar = round($yearly_contract_dollar / ($ap_contract_close / 100));
$yearly_contract_sold_number = round($yearly_contract_number / ($ap_contract_close / 100));

$yearly_contract_quoted_dollar = round($ap_salesgoal * ($ap_service_percent / 100));
$yearly_contract_quoted_number = round($yearly_contract_quoted_dollar / $ap_service_dollar);

$yearly_service_quoted_dollar = round($yearly_contract_quoted_dollar / ($ap_service_close / 100));
$yearly_service_quoted_number = round($yearly_contract_quoted_number / ($ap_service_close / 100));

$yearly_contacts = round(($yearly_service_quoted_number * $ap_service_contacts) + ($yearly_contract_sold_number * $ap_contract_contacts));
$yearly_meetings = round(($yearly_service_quoted_number * $ap_service_meetings) + ($yearly_contract_sold_number * $ap_contract_meetings));
$yearly_ops = round(($yearly_service_quoted_number + $yearly_contract_sold_number) * ($ap_increase / 100));
$yearly_calls = round($yearly_ops * 10);

$display_contract_sold_number = ceil($yearly_contract_number / $dv);
$display_contract_sold_dollar = ceil($yearly_contract_dollar / $dv);

$display_service_sold_number = ceil($yearly_contract_quoted_number / $dv);
$display_service_sold_dollar = ceil($yearly_contract_quoted_dollar / $dv);

$display_contract_quoted_number = ceil($yearly_contract_sold_number / $dv);
$display_contract_quoted_dollar = ceil($yearly_contract_sold_dollar / $dv);

$display_service_quoted_number = ceil($yearly_service_quoted_number / $dv);
$display_service_quoted_dollar = ceil($yearly_service_quoted_dollar / $dv);

$display_inspections = ceil(((100 - $ap_tm) / 100) * $display_service_quoted_number);

$display_qwikbids = ceil(($ap_tm / 100) * $display_service_sold_number);

$display_contacts = ceil($yearly_contacts / $dv);
$display_meetings = ceil($yearly_meetings / $dv);
$display_ops = ceil($yearly_ops / $dv);
$display_calls = ceil($yearly_calls / $dv);


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

$pie_total = $total_prospects + $total_candidates + $total_clients;
$prospect_percent = round($total_prospects / $pie_total, 1);
$candidate_percent = round($total_candidates / $pie_total, 1);
$client_percent = round($total_clients / $pie_total, 1);

?>
<div style="position:relative;" class="main">
<div style="float:left;">
<canvas id="piechart" width="200" height="200">
</canvas>
</div>
<div style="float:left; padding-left:10px;">
  <div style="position:relative;">
    <div style="float:left; width:50px; height:16px; background-color:red;">&nbsp;</div>
	<div style="float:left; padding-left:10px;">Prospects</div>
  </div>
  <div style="clear:both;"></div>
  <div style="height:5px;"><img src="images/spacer.gif"></div>
  <div style="position:relative;">
    <div style="float:left; width:50px; height:16px; background-color:yellow;">&nbsp;</div>
	<div style="float:left; padding-left:10px;">Candidates</div>
  </div>
  <div style="clear:both;"></div>
  <div style="height:5px;"><img src="images/spacer.gif"></div>
  <div style="position:relative;">
    <div style="float:left; width:50px; height:16px; background-color:#07ff02;">&nbsp;</div>
	<div style="float:left; padding-left:10px;">Clients</div>
  </div>
  <div style="clear:both;"></div>
</div>
</div>
<div style="clear:both;"></div>

<div class="main">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
Include data for: 
<select name="searchby" onchange="datefilter_change(this)">
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected"; ?>>This Week</option>
<option value="lastweek"<?php if($searchby=="lastweek") echo " selected"; ?>>Last Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected"; ?>>This Month</option>
<option value="lastmonth"<?php if($searchby=="lastmonth") echo " selected"; ?>>Last Month</option>
<option value="ytd"<?php if($searchby=="ytd") echo " selected"; ?>>Year to Date</option>
<?php
for($x=2011;$x<date("Y");$x++){
  ?>
  <option value="<?=$x?>"<?php if($searchby==$x) echo " selected"; ?>><?=$x?></option>
  <?php
}
?>
</select>


<input type="submit" name="submit1" value="Search">


<?php // Drops activities area ?>
<br><br>



<?php

$sched_daterange = $daterange;
$sched_daterange = str_replace("XXX", "date", $sched_daterange);
$total_met = 0;

$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $sql = "SELECT count(*) from activities where event='Drop Performed' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $drops_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where $sched_daterange and complete=1 and display=1 and act_result ='Objective Met' and user_id='$x_user_id'";
  $met_total += getsingleresult($sql);
  

}

$ops_percent = round($met_total / $display_ops, 2);
if($ops_percent > 1) $ops_percent = 1;
$ops_percent = $ops_percent * 100;
?>
<div style="font-size:18px; font-weight:bold;"> Prospecting Report</div>

<div style="position:relative;">
<div style="float:left;">
<table class="main">
<tr>
<td valign="top" style="padding-right:15px;">
    <div style="width:250px; height:210px;" class="cornerbox">
	<div style="padding:65px 8px 5px 5px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
	  <table class="main" width="100%">

	  <tr>
	  <td>Drops Performed</td>
	  <td align="right"><?=$drops_total?></td>
	  </tr>
	  <tr>
	  <td> <?=$objective_met_label?> </td>
	  <td align="right"><?=$met_total?></td>
	  </tr>

	  </table>
	  <strong>Combined</strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
</td>
<td valign="bottom" id="drops_arrow"><a href="javascript:ShowHideDetails('drops', '1')"><img src="images/redarrow_up.png" border="0"></a></td>
</tr>
</table>
</div>
<div style="float:left;">

  <div style="position:relative;">
    <div align="right" style="font-weight:bold;"><?=$goalfiller?> Goal</div>
    <div style="float:left; width:150px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right"><?=$objective_met_label?></div>
	</div>
	<div style="float:left;">
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$ops_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	</div>
	<div style="float:left; width:90px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_ops?></div>
	</div>
  </div>
  <div style="clear:both;"></div>

</div>
</div>
<div style="clear:both;"></div>

<div id="drops_details" style="display:none;">
<hr size="1">
<table class="main">
<?php
$data = "";
$counter=0;
$data = "";
$row2 = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);
  $objective_3 = stripslashes($record['objective_3']);
  $goal = stripslashes($record['objective_3_value']);
  
  $sql = "SELECT count(*) from activities where event='Drop Performed' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $drops_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where $sched_daterange and complete=1 and display=1 and act_result ='Objective Met' and user_id='$user_id'";
  $met_total = getsingleresult($sql);
  
  

  $data[$counter]['user_id'] = $user_id;
  $data[$counter]['fullname'] = $fullname;
  $data[$counter]['photo'] = $photo;
  $data[$counter]['drops'] = $drops_total;
  $data[$counter]['met'] = $met_total;
  $counter++;
}
//usort($row, "rcompare_sched");

foreach ($data as $key => $row2) {
	$data_user_id[$key] = $row2['user_id'];
	$data_fullname[$key] = $row2['fullname'];
	$data_drops[$key] = $row2['drops'];
	$data_met[$key] = $row2['met'];
	$data_photo[$key] = $row2['photo'];
}

    array_multisort($data_met, SORT_DESC, $data_drops, SORT_DESC, $data);


$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  $selltotal = $data[$x]['selltotal'];
  $fullname = $data[$x]['fullname'];
  $drops = $data[$x]['drops'];
  $met = $data[$x]['met'];
  $photo = $data[$x]['photo'];
  if($photo != ""){
    list($width, $height) = getimagesize("uploaded_files/headshots/" . $photo);
    $make_width=75;
    $max_height = 79;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
	if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
	}
  }
  if($counter==0) echo "<tr>\n";
  ?>
  <td width="190" valign="top" align="center">
  <div style="width:200px;height:275px;" class="cornerbox">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main" width="100%">
	<tr>
	<td align="center">
	  <table width="100%">
	  <tr>
	  <td valign="top">
	  <?php if($photo != "") { ?>
	  <img src="uploaded_files/headshots/<?=$photo?>" width="<?=$make_width?>" height="<?=$image_height?>">
	  <?php } ?>
	  </td>
	  <td valign="middle" align="center">
      <div style="background:url(images/roundreport_circle.gif); width:68px; height:68px; background-repeat:no-repeat; padding-top:15px; font-weight:bold; font-size:24px;">
	  <?=$place?>
	  </div>
	  </td>
	  </tr>
	  </table>

	  <table class="main" width="100%">
	  <tr>
	  <td>Drops Performed</td>
	  <td align="right">
	  <?=$drops?>
	  </td>
	  </tr>

	  <tr>
	  <td> <?=$objective_met_label?> </td>
	  <td align="right">
	  <?=$met?>
	  </td>
	  </tr>

	  </table>
	  <strong><?=$fullname?></strong>
	</td>
	</tr>
	</table>
	</div>
	</div>
  </td>
  <?php
  $counter++;
  if($counter==6){
    //echo "</tr>\n";
	//$counter=0;
  }
}
if($place==0) {
  ?>
  <tr>
  <td>
  No activities were completed during the specified date range
  </td>
  </tr>
  <?php
}
?>
</table>
</div> <?php // end div of whole list of employee data ?>


<br><br>


<?php // Schedule activities area ?>
<br><br>



<?php

$sched_daterange = $daterange;
$sched_daterange = str_replace("XXX", "date", $sched_daterange);
$total_met = 0;

$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $sql = "SELECT count(*) from activities where event='Contact' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $contact_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Inspection' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $inspection_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Quickbid' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $qb_total += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Meeting' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$x_user_id'";
  $meeting_total += getsingleresult($sql);
  

}


$inspection_percent = round($inspection_total / $display_inspections, 2);
if($inspection_percent > 1) $inspection_percent = 1;
$inspection_percent = $inspection_percent * 100;

$qb_percent = round($qb_total / $display_qwikbids, 2);
if($qb_percent > 1) $qb_percent = 1;
$qb_percent = $qb_percent * 100;

$contact_percent = round($contact_total / $display_contacts, 2);
if($contact_percent > 1) $contact_percent = 1;
$contact_percent = $contact_percent * 100;

$meeting_percent = round($meeting_total / $display_meetings, 2);
if($meeting_percent > 1) $meeting_percent = 1;
$meeting_percent = $meeting_percent * 100;

?>
<div style="font-size:18px; font-weight:bold;"> Activity Report</div>

<div style="position:relative;">
<div style="float:left;">
<table class="main">
<tr>
<td valign="top" style="padding-right:15px;">
    <div style="width:250px; height:210px;" class="cornerbox">
	<div style="padding:65px 8px 5px 5px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
	  <table class="main" width="100%">

	  <tr>
	  <td>Inspections</td>
	  <td align="right"><?=$inspection_total?></td>
	  </tr>
	  <tr>
	  <td> Qwikbids </td>
	  <td align="right"><?=$qb_total?></td>
	  </tr>
	  <tr>
	  <td> Contacts </td>
	  <td align="right"><?=$contact_total?></td>
	  </tr>
	  <tr>
	  <td> Meetings</td>
	  <td align="right"><?=$meeting_total?></td>
	  </tr>

	  </table>
	  <strong>Combined</strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
</td>
<td valign="bottom" id="activities_arrow"><a href="javascript:ShowHideDetails('activities', '1')"><img src="images/redarrow_up.png" border="0"></a></td>
</tr>
</table>
</div>
<div style="float:left;">

  <div style="position:relative;">
    <div align="right" style="font-weight:bold;"><?=$goalfiller?> Goal</div>
    <div style="float:left; width:150px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">Inspections</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">Qwikbids</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">Contacts</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">Meetings</div>
	</div>
	<div style="float:left;">
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$inspection_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$qb_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$contact_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$meeting_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	</div>
	<div style="float:left; width:90px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_inspections?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_qwikbids?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_contacts?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_meetings?></div>
	</div>
  </div>
  <div style="clear:both;"></div>

</div>
</div>
<div style="clear:both;"></div>

<div id="activities_details" style="display:none;">
<hr size="1">
<table class="main">
<?php
$data = "";
$counter=0;
$data = "";
$row2 = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);
  $objective_3 = stripslashes($record['objective_3']);
  $goal = stripslashes($record['objective_3_value']);
  
  $sql = "SELECT count(*) from activities where event='Contact' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $contact_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Inspection' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $inspection_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Quickbid' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $qb_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Meeting' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $meeting_total = getsingleresult($sql);
  
  
  
  
  //$total = $bptotal + $itotal + $rrptotal + $umtotal;
  //$total = $itotal + $umtotal + $total_met + $user_budget;
  $total = $contact_total + $inspection_total + $qb_total + $meeting_total;
  

  $data[$counter]['user_id'] = $user_id;
  $data[$counter]['fullname'] = $fullname;
  $data[$counter]['photo'] = $photo;
  $data[$counter]['contact'] = $contact_total;
  $data[$counter]['inspection'] = $inspection_total;
  $data[$counter]['qb'] = $qb_total;
  $data[$counter]['meeting'] = $meeting_total;
  $data[$counter]['total'] = $total;
  $counter++;
}
//usort($row, "rcompare_sched");

foreach ($data as $key => $row2) {
	$data_user_id[$key] = $row2['user_id'];
	$data_fullname[$key] = $row2['fullname'];
	$data_contact[$key] = $row2['contact'];
	$data_inspection[$key] = $row2['inspection'];
	$data_qb[$key] = $row2['qb'];
	$data_meeting[$key] = $row2['meeting'];
	$data_total[$key] = $row2['total'];
	$data_photo[$key] = $row2['photo'];
}
$order_by_sched = "total"; // just defaulting for now, no choices

switch($order_by_sched){
  case "total":{
    array_multisort($data_total, SORT_DESC, $data_meeting, SORT_DESC, $data_inspection, SORT_DESC, $data);
	break;
  }

  case "meeting":{
    array_multisort($data_meeting, SORT_DESC, $data_total, SORT_DESC, $data_inspection, SORT_DESC, $data);
	break;
  }

}

$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  $selltotal = $data[$x]['selltotal'];
  $fullname = $data[$x]['fullname'];
  $contact = $data[$x]['contact'];
  $inspection = $data[$x]['inspection'];
  $qb = $data[$x]['qb'];
  $meeting = $data[$x]['meeting'];
  $total = $data[$x]['total'];
  $photo = $data[$x]['photo'];
  if($photo != ""){
    list($width, $height) = getimagesize("uploaded_files/headshots/" . $photo);
    $make_width=75;
    $max_height = 79;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
	if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
	}
  }
  if($counter==0) echo "<tr>\n";
  ?>
  <td width="190" valign="top" align="center">
  <div style="width:200px;height:275px;" class="cornerbox">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main" width="100%">
	<tr>
	<td align="center">
	  <table width="100%">
	  <tr>
	  <td valign="top">
	  <?php if($photo != "") { ?>
	  <img src="uploaded_files/headshots/<?=$photo?>" width="<?=$make_width?>" height="<?=$image_height?>">
	  <?php } ?>
	  </td>
	  <td valign="middle" align="center">
      <div style="background:url(images/roundreport_circle.gif); width:68px; height:68px; background-repeat:no-repeat; padding-top:15px; font-weight:bold; font-size:24px;">
	  <?=$place?>
	  </div>
	  </td>
	  </tr>
	  </table>

	  <table class="main" width="100%">
	  <tr>
	  <td>Inspections</td>
	  <td align="right">
	  <?=$inspection?>
	  </td>
	  </tr>

	  <tr>
	  <td> Qwikbids </td>
	  <td align="right">
	  <?=$qb?>
	  </td>
	  </tr>
	  <tr>
	  <td> Contacts </td>
	  <td align="right">
	  <?=$contact?>
	  </td>
	  </tr>
	  <tr>
	  <td> Meetings</td>
	  <td align="right">
	  <?=$meeting?>
	  </td>
	  </tr>
	  <tr>
	  <td>Total</td>
	  <td align="right">
	  <?=$total?>
	  </td>
	  </tr>
	  </table>
	  <strong><?=$fullname?></strong>
	</td>
	</tr>
	</table>
	</div>
	</div>
  </td>
  <?php
  $counter++;
  if($counter==6){
    //echo "</tr>\n";
	//$counter=0;
  }
}
if($place==0) {
  ?>
  <tr>
  <td>
  No activities were completed during the specified date range
  </td>
  </tr>
  <?php
}
?>
</table>
</div> <?php // end div of whole list of employee data ?>


<br><br>

<?php // Sales Service area ?>

<?php
$products_clause = " (opp_product_id=-2 or opp_product_id=-3) ";
$sales_daterange = $daterange;
$sales_daterange = str_replace("XXX", "lastaction", $sales_daterange);
$opp_sold_daterange = $daterange;
$opp_sold_daterange = str_replace("XXX", "sold_date", $opp_sold_daterange);

$budget_total = 0;
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $sql = "SELECT sum(amount) from opportunities where status='Quoted' and $sales_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $totalquoted += getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where status='Sold' and $opp_sold_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $totalsold += getsingleresult($sql);
  
  $sql = "SELECT sum(amount) from opportunities where status='Dead' and $sales_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $totaldead += getsingleresult($sql);
  $sql = "SELECT count(*) from opportunities where status='Sold' and amount > 0 and $opp_sold_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $selltotal += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and status='Sold' and amount > 0 and $opp_sold_daterange and $products_clause and display=1";
  $numbersold += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and $opp_sold_daterange and $products_clause and display=1";
  $numberquoted += getsingleresult($sql);
  
}
$real_totalquoted = $totalquoted + $totalsold + $totaldead; // $totalquoted is now openquoted
$close_rate = ($totalsold / $real_totalquoted) * 100;

$soldnumber_percent = round($numbersold / $display_service_sold_number, 2);
if($soldnumber_percent > 1) $soldnumber_percent = 1;
$soldnumber_percent = $soldnumber_percent * 100;

$solddollar_percent = round($totalsold / $display_service_sold_dollar, 2);
if($solddollar_percent > 1) $solddollar_percent = 1;
$solddollar_percent = $solddollar_percent * 100;

$quotednumber_percent = round($numberquoted / $display_service_quoted_number, 2);
if($quotednumber_percent > 1) $quotednumber_percent = 1;
$quotednumber_percent = $quotednumber_percent * 100;

$quoteddollar_percent = round($real_totalquoted / $display_service_quoted_dollar, 2);
if($quoteddollar_percent > 1) $quoteddollar_percent = 1;
$quoteddollar_percent = $quoteddollar_percent * 100;

?>
<div style="font-size:18px; font-weight:bold;">Service Sales Report</div>
<div style="position:relative;">
<div style="float:left;">
<table class="main">
<tr>
<td valign="top" style="padding-right:15px;">
    <div style="width:250px; height:210px;" class="cornerbox">
	<div style="padding:35px 8px 5px 5px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
	  <div>
	  <?=number_format($close_rate, 1)?>%
	  </div>
	  <table class="main" width="100%">
	  <tr>
	  <td style="font-size:12px;">Total Quoted</td>
	  <td align="right">
	  $<?=number_format($real_totalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td style="font-size:12px;">Open Quoted</td>
	  <td align="right">
	  $<?=number_format($totalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td>Sold</td>
	  <td align="right">
	  $<?=number_format($totalsold, 0)?> (<?=$numbersold?>)
	  </td>
	  </tr>
	  <tr>
	  <td>Dead</td>
	  <td align="right">
	  $<?=number_format($totaldead, 0)?>
	  </td>
	  </tr>
	  </table>
	  <strong>Combined</strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
</td>
<td valign="bottom" id="salesservice_arrow"><a href="javascript:ShowHideDetails('salesservice', '1')"><img src="images/redarrow_up.png" border="0"></a></td>
</tr>
</table>
</div>
<div style="float:left;">

  <div style="position:relative;">
    <div align="right" style="font-weight:bold;"><?=$goalfiller?> Goal</div>
    <div style="float:left; width:150px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right"># Quoted</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">$ Quoted</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right"># Sold</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">$ Sold</div>
	</div>
	<div style="float:left;">
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$quotednumber_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$quoteddollar_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$soldnumber_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$solddollar_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	</div>
	<div style="float:left; width:90px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_service_quoted_number?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right">$<?=number_format($display_service_quoted_dollar, 0)?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_service_sold_number?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right">$<?=number_format($display_service_sold_dollar, 0)?></div>
	</div>
  </div>
  <div style="clear:both;"></div>
  
</div>
</div>
<div style="clear:both;"></div>

<div id="salesservice_details" style="display:none;">
<hr size="1">
<table class="main">
<?php
$counter=0;
$data = "";
$row2 = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);


  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Quoted' and $sales_daterange and $products_clause and display=1";
  $totalquoted = getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Sold' and $opp_sold_daterange and $products_clause and display=1";
  $totalsold = getsingleresult($sql);
  
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Dead' and $sales_daterange and $products_clause and display=1";
  $totaldead = getsingleresult($sql);
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$user_id' and status='Sold' and amount > 0 and $opp_sold_daterange and $products_clause and display=1";
  $numbersold = getsingleresult($sql);
  
  $real_totalquoted = $totalquoted + $totalsold + $totaldead;
  $close_rate = ($totalsold / $real_totalquoted) * 100;
  
  
  $data[$counter]['user_id'] = $user_id;
  $data[$counter]['fullname'] = $fullname;
  $data[$counter]['photo'] = $photo;
  $data[$counter]['totalquoted'] = $totalquoted;
  $data[$counter]['totalsold'] = $totalsold;
  $data[$counter]['close_rate'] = $close_rate;
  $data[$counter]['totaldead'] = $totaldead;
  $data[$counter]['numbersold'] = $numbersold;
  $data[$counter]['numberbudget'] = $number_budget;
  $data[$counter]['amountbudget'] = $amount_budget;
  $data[$counter]['realtotalquoted'] = $real_totalquoted;
  $counter++;
}
//usort($row, "rcompare_sales");

foreach ($data as $key => $row2) {
	$data_user_id[$key] = $row2['user_id'];
	$data_fullname[$key] = $row2['fullname'];
	$data_totalquoted[$key] = $row2['totalquoted'];
	$data_totalsold[$key] = $row2['totalsold'];
	$data_closerate[$key] = $row2['close_rate'];
	$data_totaldead[$key] = $row2['totaldead'];
	$data_numbersold[$key] = $row2['numbersold'];
	$data_photo[$key] = $row2['photo'];
	$data_numberbudget[$key] = $row2['numberbudget'];
	$data_amountbudget[$key] = $row2['amountbudget'];
	$data_realtotalquoted[$key] = $row2['realtotalquoted'];
}
$order_by_sales = "totalsold"; // static for now

switch($order_by_sales){
  case "totalsold":{
    array_multisort($data_totalsold, SORT_DESC, $data_totalquoted, SORT_DESC, $data_numbersold, SORT_DESC, $data);
	break;
  }
  case "numbersold":{
    array_multisort($data_numbersold, SORT_DESC, $data_totalquoted, SORT_DESC, $data_totalsold, SORT_DESC, $data);
	break;
  }
  case "totalquoted":{
    array_multisort($data_totalquoted, SORT_DESC, $data_totalsold, SORT_DESC, $data_numbersold, SORT_DESC, $data);
	break;
  }
}

$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  $selltotal = $data[$x]['selltotal'];
  $fullname = $data[$x]['fullname'];
  $totalquoted = $data[$x]['totalquoted'];
  $totalsold = $data[$x]['totalsold'];
  $close_rate = $data[$x]['close_rate'];
  $totaldead = $data[$x]['totaldead'];
  $numbersold = $data[$x]['numbersold'];
  $photo = $data[$x]['photo'];
  $numberbudget = $data[$x]['numberbudget'];
  $amountbudget = $data[$x]['amountbudget'];
  $realtotalquoted = $data[$x]['realtotalquoted'];
  if($photo != ""){
    list($width, $height) = getimagesize("uploaded_files/headshots/" . $photo);
    $make_width=75;
    $max_height = 79;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
	if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
	}
  }
  if($counter==0) echo "<tr>\n";
  ?>
  <td width="190" valign="top" align="center">
    <div style="width:200px;height:275px;" class="cornerbox">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
      <table>
	  <tr>
	  <td valign="top">
	  <?php if($photo != "") { ?>
	  <img src="uploaded_files/headshots/<?=$photo?>" width="<?=$make_width?>" height="<?=$image_height?>">
	  <?php } ?>
	  </td>
	  <td valign="middle" align="center">
      <div style="background:url(images/roundreport_circle.gif); width:68px; height:68px; background-repeat:no-repeat; padding-top:15px; font-weight:bold; font-size:24px;">
	  <?=$place?>
	  </div>
	  </td>
	  </tr>
	  </table>


	  <div class="main_superlarge_black">
	  <?=number_format($close_rate, 1)?>%
	  </div>
	  Close Rate
	  <table class="main" width="100%">
	  <tr>
	  <td style="font-size:12px;" nowrap="nowrap">Total Quoted</td>
	  <td align="right">
	  $<?=number_format($realtotalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td style="font-size:12px;" nowrap="nowrap">Open Quoted</td>
	  <td align="right">
	  $<?=number_format($totalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td>Sold</td>
	  <td align="right">
	  $<?=number_format($totalsold, 0)?> (<?=$numbersold?>)
	  </td>
	  </tr>
	  <tr>
	  <td>Dead</td>
	  <td align="right">
	  $<?=number_format($totaldead, 0)?>
	  </td>
	  </tr>
	  </table>
	  <strong><?=$fullname?></strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
  </td>
  <?php
  $counter++;
  if($counter==6){
    //echo "</tr>\n";
	//$counter=0;
  }
}
if($place==0) {
  ?>
  <tr>
  <td>
  No sales were made during the specified date range
  </td>
  </tr>
  <?php
}
?>
</table>
</div> <?php // end salesservice div ?>

<br><br>

<?php // Contract Service area ?>

<?php
$products_clause = " (opp_product_id=-1) ";
$sales_daterange = $daterange;
$sales_daterange = str_replace("XXX", "lastaction", $sales_daterange);
$opp_sold_daterange = $daterange;
$opp_sold_daterange = str_replace("XXX", "sold_date", $opp_sold_daterange);

$budget_total = 0;
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $sql = "SELECT sum(amount) from opportunities where status='Quoted' and $sales_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $totalquoted += getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where status='Sold' and $opp_sold_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $totalsold += getsingleresult($sql);
  
  $sql = "SELECT sum(amount) from opportunities where status='Dead' and $sales_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $totaldead += getsingleresult($sql);
  $sql = "SELECT count(*) from opportunities where status='Sold' and amount > 0 and $opp_sold_daterange and user_id='$x_user_id' and $products_clause and display=1";
  $selltotal += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and status='Sold' and amount > 0 and $opp_sold_daterange and $products_clause and display=1";
  $numbersold += getsingleresult($sql);
  
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$x_user_id' and $opp_sold_daterange and $products_clause and display=1";
  $numberquoted += getsingleresult($sql);
  
}
$real_totalquoted = $totalquoted + $totalsold + $totaldead; // $totalquoted is now openquoted
$close_rate = ($totalsold / $real_totalquoted) * 100;

$soldnumber_percent = round($numbersold / $display_contract_sold_number, 2);
if($soldnumber_percent > 1) $soldnumber_percent = 1;
$soldnumber_percent = $soldnumber_percent * 100;

$solddollar_percent = round($totalsold / $display_contract_sold_dollar, 2);
if($solddollar_percent > 1) $solddollar_percent = 1;
$solddollar_percent = $solddollar_percent * 100;

$quotednumber_percent = round($numberquoted / $display_contract_quoted_number, 2);
if($quotednumber_percent > 1) $quotednumber_percent = 1;
$quotednumber_percent = $quotednumber_percent * 100;

$quoteddollar_percent = round($real_totalquoted / $display_contract_quoted_dollar, 2);
if($quoteddollar_percent > 1) $quoteddollar_percent = 1;
$quoteddollar_percent = $quoteddollar_percent * 100;

?>
<div style="font-size:18px; font-weight:bold;">Contract Sales Report</div>
<div style="position:relative;">
<div style="float:left;">
<table class="main">
<tr>
<td valign="top" style="padding-right:15px;">
    <div style="width:250px; height:210px;" class="cornerbox">
	<div style="padding:35px 8px 5px 5px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
	  <div>
	  <?=number_format($close_rate, 1)?>%
	  </div>
	  <table class="main" width="100%">
	  <tr>
	  <td style="font-size:12px;">Total Quoted</td>
	  <td align="right">
	  $<?=number_format($real_totalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td style="font-size:12px;">Open Quoted</td>
	  <td align="right">
	  $<?=number_format($totalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td>Sold</td>
	  <td align="right">
	  $<?=number_format($totalsold, 0)?> (<?=$numbersold?>)
	  </td>
	  </tr>
	  <tr>
	  <td>Dead</td>
	  <td align="right">
	  $<?=number_format($totaldead, 0)?>
	  </td>
	  </tr>
	  </table>
	  <strong>Combined</strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
</td>
<td valign="bottom" id="salescontract_arrow"><a href="javascript:ShowHideDetails('salescontract', '1')"><img src="images/redarrow_up.png" border="0"></a></td>
</tr>
</table>
</div>
<div style="float:left;">

  <div style="position:relative;">
    <div align="right" style="font-weight:bold;"><?=$goalfiller?> Goal</div>
    <div style="float:left; width:150px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right"># Quoted</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">$ Quoted</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right"># Sold</div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-right:5px; padding-bottom:5px;" align="right">$ Sold</div>
	</div>
	<div style="float:left;">
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$quotednumber_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$quoteddollar_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$soldnumber_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	  <div style="width:600px; height:20px; border:3px #831203 solid;">
	    <div style="width:<?=$solddollar_percent?>%; background:#831203; height:20px;">&nbsp;</div>
	  </div>
	  <div style="height:5px;"><img src="images/spacer.gif"></div>
	</div>
	<div style="float:left; width:90px;">
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_contract_quoted_number?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right">$<?=number_format($display_contract_quoted_dollar, 0)?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right"><?=$display_contract_sold_number?></div>
	  <div style="height:26px; font-weight:bold; vertical-align:middle; padding-left:5px; padding-bottom:5px;" align="right">$<?=number_format($display_contract_sold_dollar, 0)?></div>
	</div>
  </div>
  <div style="clear:both;"></div>
  
</div>
</div>
<div style="clear:both;"></div>

<div id="salescontract_details" style="display:none;">
<hr size="1">
<table class="main">
<?php
$counter=0;
$data = "";
$row2 = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);


  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Quoted' and $sales_daterange and $products_clause and display=1";
  $totalquoted = getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Sold' and $opp_sold_daterange and $products_clause and display=1";
  $totalsold = getsingleresult($sql);
  
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Dead' and $sales_daterange and $products_clause and display=1";
  $totaldead = getsingleresult($sql);
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$user_id' and status='Sold' and amount > 0 and $opp_sold_daterange and $products_clause and display=1";
  $numbersold = getsingleresult($sql);
  
  $real_totalquoted = $totalquoted + $totalsold + $totaldead;
  $close_rate = ($totalsold / $real_totalquoted) * 100;
  
  
  $data[$counter]['user_id'] = $user_id;
  $data[$counter]['fullname'] = $fullname;
  $data[$counter]['photo'] = $photo;
  $data[$counter]['totalquoted'] = $totalquoted;
  $data[$counter]['totalsold'] = $totalsold;
  $data[$counter]['close_rate'] = $close_rate;
  $data[$counter]['totaldead'] = $totaldead;
  $data[$counter]['numbersold'] = $numbersold;
  $data[$counter]['numberbudget'] = $number_budget;
  $data[$counter]['amountbudget'] = $amount_budget;
  $data[$counter]['realtotalquoted'] = $real_totalquoted;
  $counter++;
}
//usort($row, "rcompare_sales");

foreach ($data as $key => $row2) {
	$data_user_id[$key] = $row2['user_id'];
	$data_fullname[$key] = $row2['fullname'];
	$data_totalquoted[$key] = $row2['totalquoted'];
	$data_totalsold[$key] = $row2['totalsold'];
	$data_closerate[$key] = $row2['close_rate'];
	$data_totaldead[$key] = $row2['totaldead'];
	$data_numbersold[$key] = $row2['numbersold'];
	$data_photo[$key] = $row2['photo'];
	$data_numberbudget[$key] = $row2['numberbudget'];
	$data_amountbudget[$key] = $row2['amountbudget'];
	$data_realtotalquoted[$key] = $row2['realtotalquoted'];
}
$order_by_sales = "totalsold"; // static for now

switch($order_by_sales){
  case "totalsold":{
    array_multisort($data_totalsold, SORT_DESC, $data_totalquoted, SORT_DESC, $data_numbersold, SORT_DESC, $data);
	break;
  }
  case "numbersold":{
    array_multisort($data_numbersold, SORT_DESC, $data_totalquoted, SORT_DESC, $data_totalsold, SORT_DESC, $data);
	break;
  }
  case "totalquoted":{
    array_multisort($data_totalquoted, SORT_DESC, $data_totalsold, SORT_DESC, $data_numbersold, SORT_DESC, $data);
	break;
  }
}

$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  $selltotal = $data[$x]['selltotal'];
  $fullname = $data[$x]['fullname'];
  $totalquoted = $data[$x]['totalquoted'];
  $totalsold = $data[$x]['totalsold'];
  $close_rate = $data[$x]['close_rate'];
  $totaldead = $data[$x]['totaldead'];
  $numbersold = $data[$x]['numbersold'];
  $photo = $data[$x]['photo'];
  $numberbudget = $data[$x]['numberbudget'];
  $amountbudget = $data[$x]['amountbudget'];
  $realtotalquoted = $data[$x]['realtotalquoted'];
  if($photo != ""){
    list($width, $height) = getimagesize("uploaded_files/headshots/" . $photo);
    $make_width=75;
    $max_height = 79;
    $ratio = $width / $make_width;
    $image_height = round($height / $ratio);
	if($image_height > $max_height){
      $ratio = $height / $max_height;
      $make_width = round($width / $ratio);
      $image_height = $max_height;
	}
  }
  if($counter==0) echo "<tr>\n";
  ?>
  <td width="190" valign="top" align="center">
    <div style="width:200px;height:275px;" class="cornerbox">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
      <table>
	  <tr>
	  <td valign="top">
	  <?php if($photo != "") { ?>
	  <img src="uploaded_files/headshots/<?=$photo?>" width="<?=$make_width?>" height="<?=$image_height?>">
	  <?php } ?>
	  </td>
	  <td valign="middle" align="center">
      <div style="background:url(images/roundreport_circle.gif); width:68px; height:68px; background-repeat:no-repeat; padding-top:15px; font-weight:bold; font-size:24px;">
	  <?=$place?>
	  </div>
	  </td>
	  </tr>
	  </table>


	  <div class="main_superlarge_black">
	  <?=number_format($close_rate, 1)?>%
	  </div>
	  Close Rate
	  <table class="main" width="100%">
	  <tr>
	  <td style="font-size:12px;" nowrap="nowrap">Total Quoted</td>
	  <td align="right">
	  $<?=number_format($realtotalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td style="font-size:12px;" nowrap="nowrap">Open Quoted</td>
	  <td align="right">
	  $<?=number_format($totalquoted, 0)?>
	  </td>
	  </tr>
	  <tr>
	  <td>Sold</td>
	  <td align="right">
	  $<?=number_format($totalsold, 0)?> (<?=$numbersold?>)
	  </td>
	  </tr>
	  <tr>
	  <td>Dead</td>
	  <td align="right">
	  $<?=number_format($totaldead, 0)?>
	  </td>
	  </tr>
	  </table>
	  <strong><?=$fullname?></strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
  </td>
  <?php
  $counter++;
  if($counter==6){
    //echo "</tr>\n";
	//$counter=0;
  }
}
if($place==0) {
  ?>
  <tr>
  <td>
  No sales were made during the specified date range
  </td>
  </tr>
  <?php
}
?>
</table>
</div> <?php // end salescontract div ?>

</form>
</div>
<script>
createPieChart(<?=$prospect_percent?>,<?=$candidate_percent?>,<?=$client_percent?>);
</script>

