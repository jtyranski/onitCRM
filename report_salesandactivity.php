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
</script>
<?php
$sales_filter = " sales_activity_report=1 ";

$searchby = $_GET['searchby'];
$order_by = "rank_points";
$submit1 = $_GET['submit1'];
$rate_calc = $_GET['rate_calc'];
$user_id = $_SESSION['user_id'];
$user_type = $_GET['user_type'];


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


?>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
Include data for: 
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
&nbsp;
<strong>NEW!</strong> Select which users appear on this report by checking the box "On Sales Activity Report" in the admin tool.
  <div id="customdate" <?php if($searchby != "Custom") {?> style="display:none;"<?php } ?>>
  <table class="main">
  <tr>
  <td>
  From:
  </td>
  <td>
  <input size="10" type="text" name="custom_startdate" value="<?=$custom_startdate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('custom_startdate',0)" align="absmiddle">
  </td>
  </tr>
  <tr>
  <td>
  To:
  </td>
  <td>
  <input size="10" type="text" name="custom_enddate" value="<?=$custom_enddate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('custom_enddate',0)" align="absmiddle">
  </td>
  </tr>
  </table>
  </div>
  



<?php 
// ******** CALL activity ******************* 
if($SESSION_MASTER_ID==1){ // only need for us
$c_daterange = $daterange;
$c_daterange = str_replace("XXX", "complete_date", $c_daterange);

$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $sales_filter";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  // attempted and completed now includes prospecting activities
  $sql = "SELECT count(*) from activities where $c_daterange and display=1 and user_id='$x_user_id'";
  $total_attempted += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where (act_result='Completed' or act_result='Objective Met') and $c_daterange and display=1 and user_id='$x_user_id'";
  $total_completed += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where (act_result='Objective Met') and $c_daterange and display=1 and user_id='$x_user_id'";
  $total_met += getsingleresult($sql);
}

$totalbg = "roundreport_thumbup.gif";
?>
<div style="font-size:18px; font-weight:bold;">Contact Report</div>
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
	  <td>Calls Attempted</td>
	  <td align="right"><?=$total_attempted?></td>
	  </tr>
	  <tr>
	  <td>Calls Completed</td>
	  <td align="right"><?=$total_completed?></td>
	  </tr>
	  <tr>
	  <td>Objective Met</td>
	  <td align="right"><?=$total_met?></td>
	  </tr>
	  </table>
	  <strong>Combined</strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
</td>

<td valign="top">
	

</td>
</tr>
</table>
<?php
if($submit1=="Search"){
?>
<hr size="1">
<table class="main">
<?php
$counter=0;
$data = "";
$data = array();
$row2 = "";

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $sales_filter";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);

  $total_attempted = $total_completed = $total_email = 0;
  $sql = "SELECT count(*) from activities where user_id='$user_id' and event!='Other' and $c_daterange and display=1";
  $total_attempted = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$user_id' and (act_result='Completed' or act_result='Objective Met') and $c_daterange and display=1";
  $total_completed = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$user_id' and (act_result='Objective Met') and $c_daterange and display=1";
  $total_met = getsingleresult($sql);
  

  
  $data[$counter]['user_id'] = $record['user_id'];
  $data[$counter]['fullname'] = $record['fullname'];
  $data[$counter]['met'] = $total_met;
  $data[$counter]['attempted'] = $total_attempted;
  $data[$counter]['completed'] = $total_completed;
  //$data[$counter]['close_rate'] = $close_rate;
  $data[$counter]['photo'] = $photo;
  $data[$counter]['rank_points'] = $rank_points;
  $data[$counter]['objective_3'] = $objective_3;
  $data[$counter]['goal'] = $goal;
  $data[$counter]['email'] = $total_email;
  $data[$counter]['visit'] = $user_visit;
  $counter++;
}
//usort($row, "rcompare");


foreach ($data as $key => $row2) {
	$data_user_id[$key] = $row2['user_id'];
	$data_fullname[$key] = $row2['fullname'];
	$data_met[$key] = $row2['met'];
	$data_attempted[$key] = $row2['attempted'];
	$data_completed[$key] = $row2['completed'];
	$data_closerate[$key] = $row2['close_rate'];
	$data_photo[$key] = $row2['photo'];
	$data_rankpoints[$key] = $row2['rank_points'];
	$data_objective3[$key] = $row2['objective_3'];
	$data_goal[$key] = $row2['goal'];
	$data_email[$key] = $row2['email'];
	$data_visit[$key] = $row2['visit'];
}

array_multisort($data_attempted, SORT_DESC, $data_completed, SORT_DESC, $data);
$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  $selltotal = $data[$x]['selltotal'];
  $fullname = $data[$x]['fullname'];
  $total_attempted = $data[$x]['attempted'];
  $total_completed = $data[$x]['completed'];
  $close_rate = $data[$x]['close_rate'];
  $total_met = $data[$x]['met'];
  $objective_3 = $data[$x]['objective_3'];
  $photo = $data[$x]['photo'];
  $goal = $data[$x]['goal'];
  $email = $data[$x]['email'];
  $visit = $data[$x]['visit'];
  //$numbersold = $row[$x]['numbersold'];
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

  $boxcolor = "black";
  ?>
  <td width="220" valign="top" align="center">
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
	  <td>Calls Attempted</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=contact&table=activities&filter=5">
	  <?=$total_attempted?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td>Calls Completed</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=contact&table=activities&filter=6">
	  <?=$total_completed?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td>Objective Met</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=contact&table=activities&filter=7">
	  <?=$total_met?>
	  </a>
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
  No calls were made during the specified date range
  </td>
  </tr>
  <?php
}
?>
</table>
<?php } // end if submit1== search ?>

<?php } // end only for us ?>

<?php // Schedule activities area ?>
<br><br>



<?php
$searchby = $_GET['searchby'];
$order_by_sched = $_GET['order_by_sched'];
$submit1 = $_GET['submit1'];

$sched_daterange = $daterange;
$sched_daterange = str_replace("XXX", "date", $sched_daterange);
$total_met = 0;

$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $sales_filter";
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

?>
<div style="font-size:18px; font-weight:bold;"> Activity Report</div>
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
	  <td> Quickbids </td>
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
<td valign="top" class="main">
	
<div class="main_large">Sort By</div>

<input type="radio" name="order_by_sched" value="total"<?php if($order_by_sched=="total" || $order_by_sched=="") echo " checked"; ?>>Total
&nbsp;&nbsp;&nbsp;

<input type="radio" name="order_by_sched" value="meeting"<?php if($order_by_sched=="meeting") echo " checked"; ?>>Meetings
<br><br>
<input type="submit" name="submit1" value="Search">

</td>
</tr>
</table>
<?php
if($submit1=="Search"){
?>
<hr size="1">
<table class="main">
<?php
$data = "";
$data = array();
$counter=0;
$row2 = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $sales_filter";
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
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=9">
	  <?=$inspection?>
	  </a>
	  </td>
	  </tr>

	  <tr>
	  <td> Quickbids </td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=10">
	  <?=$qb?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td> Contacts </td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=11">
	  <?=$contact?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td> Meetings</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=12">
	  <?=$meeting?>
	  </a>
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
<?php } // end if submit1== search ?>


<br><br>

<?php // Sales area ?>

<?php
$searchby = $_GET['searchby'];
$order_by_sales = $_GET['order_by_sales'];
$submit1 = $_GET['submit1'];



$sales_daterange = $daterange;
$sales_daterange = str_replace("XXX", "lastaction", $sales_daterange);
$opp_sold_daterange = $daterange;
$opp_sold_daterange = str_replace("XXX", "sold_date", $opp_sold_daterange);

$budget_total = 0;
$sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $sales_filter";
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
  
}
$real_totalquoted = $totalquoted + $totalsold + $totaldead; // $totalquoted is now openquoted
$close_rate = ($totalsold / $real_totalquoted) * 100;
?>
<div style="font-size:18px; font-weight:bold;"> Sales Report</div>
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
<td valign="top" class="main">
	
<div class="main_large">Sort By</div>

<input type="radio" name="order_by_sales" value="totalsold"<?php if($order_by_sales=="totalsold" || $order_by_sales=="") echo " checked"; ?>>Sales Dollars
&nbsp;&nbsp;&nbsp;
<input type="radio" name="order_by_sales" value="numbersold"<?php if($order_by_sales=="numbersold") echo " checked"; ?>>Number of Sales
&nbsp;&nbsp;&nbsp;
<input type="radio" name="order_by_sales" value="totalquoted"<?php if($order_by_sales=="totalquoted") echo " checked"; ?>>Open Quoted
<br><br>
<input type="submit" name="submit1" value="Search">

</td>
</tr>
</table>
<?php
if($submit1=="Search"){
?>
<hr size="1">
<table class="main">
<?php
$counter=0;
$data = "";
$data = array();
$row2 = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $sales_filter";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);


  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Quoted' and $sales_daterange and display=1";
  $totalquoted = getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Sold' and $opp_sold_daterange and display=1";
  $totalsold = getsingleresult($sql);
  
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Dead' and $sales_daterange and display=1";
  $totaldead = getsingleresult($sql);
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where user_id='$user_id' and status='Sold' and amount > 0 and $opp_sold_daterange and display=1";
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
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=17">
	  $<?=number_format($realtotalquoted, 0)?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td style="font-size:12px;" nowrap="nowrap">Open Quoted</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=14">
	  $<?=number_format($totalquoted, 0)?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td>Sold</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=15">
	  $<?=number_format($totalsold, 0)?> (<?=$numbersold?>)
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td>Dead</td>
	  <td align="right">
	  <a href="report_salesandactivity_ind_detail.php?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=16">
	  $<?=number_format($totaldead, 0)?>
	  </a>
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
<?php } // end if submit1== search ?>

</form>


