<?php include "includes/header_white.php"; ?>
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
$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "todate";
$order_by = "rank_points";
$submit1 = $_GET['submit1'];
$search_user_id = $_GET['search_user_id'];
$details = $_GET['details'];
if($details=="") $details = "activity";
if($search_user_id==""){
  $sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname limit 1";
  $search_user_id = getsingleresult($sql);
}

$show_activity=1;
$show_sales=1;
$show_contact=0;
if($SESSION_MASTER_ID==1) $show_contact=1;



$filter = $_GET['filter'];
$table = $_GET['table'];


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

// ********* PROSPECTING activity **************

$order_by_prospect = $_GET['order_by_prospect'];

$p_daterange = $daterange;
$p_daterange = str_replace("XXX", "prospect_date", $p_daterange);

$sales_daterange = $daterange;
$sales_daterange = str_replace("XXX", "a.lastaction", $sales_daterange);


$opp_sold_daterange = $daterange;
$opp_sold_daterange = str_replace("XXX", "sold_date", $opp_sold_daterange);


$c_daterange = $daterange;
$c_daterange = str_replace("XXX", "complete_date", $c_daterange);

$sched_daterange = $daterange;
$sched_daterange = str_replace("XXX", "date", $sched_daterange);


$opp_budget_daterange = $daterange;
$opp_budget_daterange = str_replace("XXX", "budget_set_date", $opp_budget_daterange);

$opp_pro_daterange = $daterange;
$opp_pro_daterange = str_replace("XXX", "pro_set_date", $opp_pro_daterange);

$opp_won_daterange = $daterange;
$opp_won_daterange = str_replace("XXX", "won_set_date", $opp_won_daterange);

$budget_snapshot_daterange = $daterange;
$budget_snapshot_daterange = str_replace("XXX", "ts", $budget_snapshot_daterange);
?>
<div class="main_superlarge">Individual Sales Report Data</div>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
For: 
<select name="search_user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($search_user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select><br>

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
for($x=2008;$x<date("Y");$x++){
  ?>
  <option value="<?=$x?>"<?php if($searchby==$x) echo " selected"; ?>><?=$x?></option>
  <?php
}
?>
</select>
<br>
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
<br>
<input type="submit" name="submit1" value="Search">
<?php if($service==1) { echo "<input type='hidden' name='service' value='$service' />"; } ?>

<div style="width:100%; position:relative;">



<?php if($show_contact){ ?>
<div style="float:left; width:33%;" align="center" class="main">
<strong>Contact</strong><br>

<?php  
// ******** CALL activity ******************* 




?>

<?php
$counter=0;
$data = "";
$row2 = "";

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
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
  if($user_id != $search_user_id) continue;
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
  ?>
  <div style="width:250px;height:275px;" class="cornerbox">
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
	  <td id="sectiona_5">Calls Attempted</td>
	  <td align="right" id="section_5">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=contact&table=activities&filter=5">
	  <?=$total_attempted?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_6">Calls Completed</td>
	  <td align="right" id="section_6">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=contact&table=activities&filter=6">
	  <?=$total_completed?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_7">Objective Met</td>
	  <td align="right" id="section_7">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=contact&table=activities&filter=7">
	  <?=$total_met?>
	  </a>
	  </td>
	  </tr>
	  </table>
    </td>
    </tr>
    </table>
	</div>
	</div>
  <?php
  $counter++;

}
?>
</div>
<?php } ?>

<?php if($show_activity){ ?>
<div style="float:left; width:33%;" align="center" class="main">
<strong>Activity</strong><br>

<?php // Schedule activities area ?>



<?php
$searchby = $_GET['searchby'];
$order_by_sched = $_GET['order_by_sched'];
$submit1 = $_GET['submit1'];




?>

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

    array_multisort($data_total, SORT_DESC, $data_meeting, SORT_DESC, $data_inspection, SORT_DESC, $data);


$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  if($user_id != $search_user_id) continue;
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
  ?>
  <div style="width:250px;height:275px;" class="cornerbox">
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
	  <td id="sectiona_9">Inspections</td>
	  <td align="right" id="section_9">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=9">
	  <?=$inspection?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_10"> Quickbids </td>
	  <td align="right" id="section_10">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=10">
	  <?=$qb?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_11"> Contacts </td>
	  <td align="right" id="section_11">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=11">
	  <?=$contact?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_12"> Meetings</td>
	  <td align="right" id="section_12">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=activity&table=activities&filter=12">
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
	</td>
	</tr>
	</table>
	</div>
	</div>
  <?php
  $counter++;

}
?>
</div>
<?php } ?>

<?php if($show_sales){ ?>
<div style="float:left; width:33%;" align="center" class="main">
<strong>Sales</strong><br>

<?php // Sales area ?>

<?php
$searchby = $_GET['searchby'];
$order_by_sales = $_GET['order_by_sales'];
$submit1 = $_GET['submit1'];



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


  $sql = "SELECT sum(amount) from opportunities a where user_id='$user_id' and status='Quoted' and $sales_daterange and display=1";
  $totalquoted = getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where user_id='$user_id' and status='Sold' and $opp_sold_daterange and display=1";
  $totalsold = getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities a where user_id='$user_id' and status='Dead' and $sales_daterange and display=1";
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

    array_multisort($data_totalsold, SORT_DESC, $data_totalquoted, SORT_DESC, $data_numbersold, SORT_DESC, $data);


$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  if($user_id != $search_user_id) continue;
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
  ?>
    <div style="width:250px;height:275px;" class="cornerbox">
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
	  <td id="sectiona_17">Total Quoted</td>
	  <td align="right" id="section_17">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=17">
	  $<?=number_format($realtotalquoted, 0)?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_14">Open Quoted</td>
	  <td align="right" id="section_14">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=14">
	  $<?=number_format($totalquoted, 0)?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_15">Sold</td>
	  <td align="right" id="section_15">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=15">
	  $<?=number_format($totalsold, 0)?> (<?=$numbersold?>)
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_16">Dead</td>
	  <td align="right" id="section_16">
	  <a href="<?=$_SERVER['SCRIPT_NAME']?>?custom_startdate=<?=$custom_startdate?>&custom_enddate=<?=$custom_enddate?>&search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=16">
	  $<?=number_format($totaldead, 0)?>
	  </a>
	  </td>
	  </tr>
	  </table>
    </td>
    </tr>
    </table>
	</div>
	</div>
  <?php
  $counter++;

}

?>
</div>
<?php } ?>

</div>
<div style="clear:both;"></div>


</form>

<?php

switch($filter){

  case 5:{  // Calls attempted
    $filter_clause = " a.event!='Other' ";
	$date_filter = $c_daterange;
	break;
  }
  case 6:{  // Calls completed
    $filter_clause = " (a.act_result='Completed' or a.act_result='Objective Met') ";
	$date_filter = $c_daterange;
	break;
  }
  case 7:{  // objective met
    $filter_clause = " (a.act_result='Objective Met') ";
	$date_filter = $c_daterange;
	break;
  }

  case 9:{  // Inspection
    $filter_clause = " a.event='Inspection' and a.act_result != 'Attempted'";
	$date_filter = $sched_daterange;
	$complete_clause = " and a.complete=1 ";
	break;
  }
  case 10:{  // Quickbid
    $filter_clause = " a.event='Quickbid' and a.act_result != 'Attempted'";
	$date_filter = $sched_daterange;
	$complete_clause = " and a.complete=1 ";
	break;
  }
  case 11:{  // Contact
    $filter_clause = " a.event='Contact' and a.act_result != 'Attempted'";
	$date_filter = $sched_daterange;
	$complete_clause = " and a.complete=1 ";
	break;
  }
  case 12:{  // Meeting
    $filter_clause = " a.event='Meeting' and a.act_result != 'Attempted'";
	$date_filter = $sched_daterange;
	$complete_clause = " and a.complete=1 ";
	break;
  }

  case 14:{  // Open Quoted
    $filter_clause = " a.status='Quoted' ";
	$date_filter = $sales_daterange;
	break;
  }
  case 15:{  // Sold
    $filter_clause = " a.status='Sold' and a.amount > 0 ";
	$date_filter = $opp_sold_daterange;
	$display_date = "a.sold_date";
	break;
  }
  case 16:{  // Dead
    $filter_clause = " a.status='Dead' ";
	$date_filter = $sales_daterange;
	break;
  }
  case 16:{  // All quoted... all
    $filter_clause = " 1=1 ";
	$date_filter = $sales_daterange;
	break;
  }
  
  default:{
    $filter_clause = " 1=1 ";
	break;
  }
}



$filter_rd = "table=" . $table . "&filter=" . $filter;
if($service=1) { $filter_rd .= "&service=" . $service; }

$deletelink = "";
switch($table){

  case "activities":{
    if($date_filter=="") $date_filter = $c_daterange;
    $sql = "SELECT date_format(a.complete_date, \"%m/%d/%Y\") as datepretty, a.event as type, a.act_result as result, 
	a.prospect_id, a.property_id, b.company_name, c.site_name, c.corporate, b.city as companycity, b.state as companystate, 
	c.city as propertycity, c.state as propertystate, a.regarding as notes, a.act_id as id
	from activities a, prospects b, properties c where a.prospect_id=b.prospect_id and a.property_id=c.property_id
	and $date_filter
	and a.display=1
	and $filter_clause
	$complete_clause
	and a.user_id='$search_user_id'";
	$idname = "act_id";
	$editlink = "activity_edit.php";
	break;
  }
  case "opportunities":{
    $sql = "SELECT * from opportunities_master";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $opp_product_id = $record['opp_product_id'];
	  $opp_name = stripslashes($record['opp_name']);
	  $OPP_ITEMS[$opp_product_id] = $opp_name;
	}
	$sql = "SELECT * from opportunities_items where master_id='" . $SESSION_MASTER_ID . "'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $opp_product_id = $record['opp_product_id'];
	  $opp_name = stripslashes($record['opp_name']);
	  $OPP_ITEMS[$opp_product_id] = $opp_name;
	}
	
	
    if($date_filter=="") $date_filter = $sales_daterange;
	if($user_clause == "") $user_clause = " a.user_id ";
	if($display_date == "") $display_date = "a.lastaction";
    $sql = "SELECT date_format($display_date, \"%m/%d/%Y\") as datepretty, a.opp_product_id as type, a.status as result, 
	a.prospect_id, a.property_id, b.company_name, c.site_name, c.corporate, b.city as companycity, b.state as companystate, 
	c.city as propertycity, c.state as propertystate, a.description as notes, a.opp_id as id, a.amount
	from opportunities a, prospects b, properties c 
	where a.prospect_id=b.prospect_id and a.property_id=c.property_id and a.display=1
	and $date_filter
	and $filter_clause
	and $user_clause ='$search_user_id'
	group by a.opp_id";
	$idname = "opp_id";
	$editlink = "opportunity_edit.php";
	break;
  }
}


//echo "<!-- XXX $sql -->\n";
?>
<table class="main" width="100%" cellpadding="3" cellspacing="0">
<tr>
<td><strong>Date</strong></td>
<td><strong>Type</strong></td>
<td><strong>Result</strong></td>
<td><strong>Company</strong></td>
<td><strong>Property</strong></td>
<td><strong>Location</strong></td>
<td><strong>Notes</strong></td>
<?php if($table=="opportunities"){ ?>
<td><strong>Amount</strong></td>
<?php } ?>
</tr>
<?php
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $counter++;
  $corporate = $record['corporate'];
  if($corporate){
    $city = stripslashes($record['companycity']);
	$state = stripslashes($record['companystate']);
  }
  else {
    $city = stripslashes($record['propertycity']);
	$state = stripslashes($record['propertystate']);
  }
  $result_display = stripslashes($record['result']);
  
  $type = stripslashes($record['type']);
  if($table=="opportunities"){
    $t = $record['type'];
	$type = $OPP_ITEMS[$t];
  }
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$record['datepretty']?></td>
  <td valign="top"><?=$type?></td>
  <td valign="top"><?=$result_display?></td>
  <td valign="top">
  <a href="view_prospect.php?prospect_id=<?=$record['prospect_id']?>" target="_blank">
  <?=stripslashes($record['company_name'])?>
  </a>
  </td>
  <td valign="top">
  <a href="view_property.php?property_id=<?=$record['property_id']?>" target="_blank">
  <?=stripslashes($record['site_name'])?>
  </a>
  </td>
  <td valign="top">
  <?=$city?>, <?=$state?>
  </td>
  <td valign="top">
  <?=stripslashes(nl2br($record['notes']))?>
  </td>
  <?php if($table=="opportunities"){ ?>
  <td>$<?=number_format($record['amount'], 2)?></td>
  <?php } ?>
  </tr>
  <?php
}
?>
</table>
<?php if($filter != ""){ ?>
<script>
document.getElementById("sectiona_<?=$filter?>").style.fontWeight='bold';
document.getElementById("section_<?=$filter?>").style.fontWeight='bold';
</script>
<?php } ?>
<?php include "includes/footer.php"; ?>
