<?php include "includes/functions.php"; ?>
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
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<?php
 // this is just for fcs login


$searchby = $_GET['searchby'];
$order_by = $_GET['order_by'];
if($order_by=="") $order_by = "dials";

$submit1 = $_GET['submit1'];

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

$c_daterange = $daterange;
$c_daterange = str_replace("XXX", "complete_date", $c_daterange);


if($forceblack) $totalbg = "roundreport_thumbup.gif";

$sql = "SELECT * from activities_met_options order by met_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $met_id = $record['met_id'];
  $met_name = stripslashes($record['met_name']);
  $MET[$met_id] = $met_name;
}
?>
<div class="main">
<div style="font-size:18px; font-weight:bold;">Prospecting Report</div>
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
for($x=2008;$x<date("Y");$x++){
  ?>
  <option value="<?=$x?>"<?php if($searchby==$x) echo " selected"; ?>><?=$x?></option>
  <?php
}
?>
</select>
&nbsp;
Sort By:
<input type="radio" name="order_by" value="dials"<?php if($order_by=="dials") echo " checked";?>>Dials &nbsp;
<input type="radio" name="order_by" value="connections"<?php if($order_by=="connections") echo " checked";?>>Connections &nbsp;
<input type="radio" name="order_by" value="webinar"<?php if($order_by=="webinar") echo " checked";?>><?=$MET[1]?> &nbsp;
<input type="radio" name="order_by" value="goto"<?php if($order_by=="goto") echo " checked";?>><?=$MET[2]?> &nbsp;
<input type="radio" name="order_by" value="app"<?php if($order_by=="app") echo " checked";?>><?=$MET[3]?> &nbsp;


<input type="submit" name="submit1" value="Search">
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
if($submit1=="Search"){
?>

<?php
$sql = "SELECT user_id, met1_goal, met2_goal, met3_goal from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and on_call_report=1 and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_user_id = $record['user_id'];
  $met1_goal += $record['met1_goal'];
  $met2_goal += $record['met2_goal'];
  $met3_goal += $record['met3_goal'];
  
  $sql = "SELECT count(*) from activities where user_id='$x_user_id' and event='Contact' and $c_daterange and display=1";
  $total_attempted += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$x_user_id' and event='Contact' and (act_result='Completed' or act_result='Objective Met') and $c_daterange and display=1";
  $total_completed += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$x_user_id' and event='Contact' and act_result='Objective Met' and met_id=1 and $c_daterange and display=1";
  $total_webinar += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$x_user_id' and event='Contact' and act_result='Objective Met' and met_id=2 and $c_daterange and display=1";
  $total_goto += getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$x_user_id' and event='Contact' and act_result='Objective Met' and met_id=3 and $c_daterange and display=1";
  $total_app += getsingleresult($sql);
  
}

$corner = "cornerbox";
  $web_goal = "(" . $met1_goal . ")";
  $goto_goal = "(" . $met2_goal . ")";
  $app_goal = "(" . $met3_goal . ")";
  if($order_by=="webinar"){
    if($total_webinar >= $met1_goal){
	  $corner = "cornerbox_green";
	}
	else {
	  $corner = "cornerbox_red";
	}
  }
  if($order_by=="goto"){
    if($total_goto >= $met2_goal){
	  $corner = "cornerbox_green";
	}
	else {
	  $corner = "cornerbox_red";
	}
  }
  if($order_by=="app"){
    if($total_app >= $met3_goal){
	  $corner = "cornerbox_green";
	}
	else {
	  $corner = "cornerbox_red";
	}
  }

if($corner=="cornerbox_green"){
  $totalbg = "background-image:url(images/roundreport_thumbup2.gif);";
}

if($corner=="cornerbox_red"){
  $totalbg = "background-image:url(images/roundreport_thumbdown2.gif);";
}

if($forceblack==1) {
    $corner = "cornerbox";
	$web_goal = "";
	$goto_goal = "";
	$app_goal = "";
	$totalbg = "";
  }
?>
<?php if($forceblack != 1) echo "<br>Weekly goals in parentheses<br>"; ?>
<table class="main">
<tr>
<td valign="top" style="padding-right:15px;">
    <div style="width:200px; height:210px; <?=$totalbg?> background-repeat:no-repeat; background-position:top;" class="<?=$corner?>">
	<div style="padding:65px 8px 5px 5px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
	  <table class="main" width="100%">
	  <tr>
	  <td>Dials</td>
	  <td align="right"><?=$total_attempted?></td>
	  </tr>
	  <tr>
	  <td>Connections</td>
	  <td align="right"><?=$total_completed?></td>
	  </tr>
	  <tr>
	  <td><?=$MET[1]?></td>
	  <td align="right"><?=$total_webinar?><?=$web_goal?></td>
	  </tr>
	  <tr>
	  <td><?=$MET[2]?></td>
	  <td align="right"><?=$total_goto?><?=$goto_goal?></td>
	  </tr>
	  <tr>
	  <td><?=$MET[3]?></td>
	  <td align="right"><?=$total_app?><?=$app_goal?></td>
	  </tr>
	  </table>
	  <strong>Combined</strong>
    </td>
    </tr>
    </table>
	</div>
	</div>
</td>

</tr>
</table>


<hr size="1">

<table class="main_black">
<?php
$counter=0;
$data = "";
$row2 = "";

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, met1_goal, met2_goal, met3_goal 
from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and on_call_report=1 and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  $fullname = stripslashes($record['fullname']);
  $met1_goal = $record['met1_goal'];
  $met2_goal = $record['met2_goal'];
  $met3_goal = $record['met3_goal'];
  
  $total_attempted = $total_completed = $total_webinar = $total_goto = $total_app = 0;
  $sql = "SELECT count(*) from activities where user_id='$user_id' and event='Contact' and $c_daterange and display=1";
  $total_attempted = getsingleresult($sql);

  $sql = "SELECT count(*) from activities where user_id='$user_id' and event='Contact' and (act_result='Completed' or act_result='Objective Met') and $c_daterange and display=1";
  $total_completed = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$user_id' and event='Contact' and act_result='Objective Met' and met_id=1 and $c_daterange and display=1";
  $total_webinar = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$user_id' and event='Contact' and act_result='Objective Met' and met_id=2 and $c_daterange and display=1";
  $total_goto = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where user_id='$user_id' and event='Contact' and act_result='Objective Met' and met_id=3 and $c_daterange and display=1";
  $total_app = getsingleresult($sql);

  

  
  $data[$counter]['user_id'] = $record['user_id'];
  $data[$counter]['fullname'] = $record['fullname'];
  $data[$counter]['attempted'] = $total_attempted;
  $data[$counter]['completed'] = $total_completed;
  $data[$counter]['webinar'] = $total_webinar;
  $data[$counter]['goto'] = $total_goto;
  $data[$counter]['app'] = $total_app;
  $data[$counter]['met1'] = $met1_goal;
  $data[$counter]['met2'] = $met2_goal;
  $data[$counter]['met3'] = $met3_goal;
  
  $counter++;
}
//usort($row, "rcompare");


foreach ($data as $key => $row2) {
	$data_user_id[$key] = $row2['user_id'];
	$data_fullname[$key] = $row2['fullname'];
	$data_attempted[$key] = $row2['attempted'];
	$data_completed[$key] = $row2['completed'];
	$data_webinar[$key] = $row2['webinar'];
	$data_goto[$key] = $row2['goto'];
	$data_app[$key] = $row2['app'];
	$data_met1[$key] = $row2['met1'];
	$data_met2[$key] = $row2['met2'];
	$data_met3[$key] = $row2['met3'];
}

switch($order_by){
  case "dials":{
    array_multisort($data_attempted, SORT_DESC, $data_completed, SORT_DESC, $data);
	break;
  }
  case "connections":{
    array_multisort($data_completed, SORT_DESC, $data_attempted, SORT_DESC, $data);
	break;
  }
  case "webinar":{
    array_multisort($data_webinar, SORT_DESC, $data_completed, SORT_DESC, $data);
	break;
  }
  case "goto":{
    array_multisort($data_goto, SORT_DESC, $data_completed, SORT_DESC, $data);
	break;
  }
  case "app":{
    array_multisort($data_app, SORT_DESC, $data_completed, SORT_DESC, $data);
	break;
  }
  default:{
    array_multisort($data_attempted, SORT_DESC, $data_completed, SORT_DESC, $data);
	break;
  }
}
$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  $fullname = $data[$x]['fullname'];
  $total_attempted = $data[$x]['attempted'];
  $total_completed = $data[$x]['completed'];
  $webinar = $data[$x]['webinar'];
  $goto = $data[$x]['goto'];
  $app = $data[$x]['app'];
  $met1 = $data[$x]['met1'];
  $met2 = $data[$x]['met2'];
  $met3 = $data[$x]['met3'];
  if($counter==0) echo "<tr>\n";

  $corner = "cornerbox";
  $web_goal = "(" . $met1 . ")";
  $goto_goal = "(" . $met2 . ")";
  $app_goal = "(" . $met3 . ")";
  if($order_by=="webinar"){
    if($webinar >= $met1){
	  $corner = "cornerbox_green";
	}
	else {
	  $corner = "cornerbox_red";
	}
  }
  if($order_by=="goto"){
    if($goto >= $met2){
	  $corner = "cornerbox_green";
	}
	else {
	  $corner = "cornerbox_red";
	}
  }
  if($order_by=="app"){
    if($app >= $met3){
	  $corner = "cornerbox_green";
	}
	else {
	  $corner = "cornerbox_red";
	}
  }
  
  if($forceblack==1) {
    $corner = "cornerbox";
	$web_goal = "";
	$goto_goal = "";
	$app_goal = "";
  }
  ?>
  <td width="200" valign="top" align="center">
  <div style="width:193px;height:275px;" class="<?=$corner?>">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main" width="100%">
    <tr>
    <td align="center">
	  <table width="100%">
	  <tr>
	  <td valign="top">
	  <?php if($photo != "") { ?>
	  <img src="uploaded_files/headshots/<?=$photo?>">
	  <?php } ?>
	  </td>
	  <td valign="middle" align="center">
      <div style="background:url(images/roundreport_circle.gif); width:68px; height:68px; background-repeat:no-repeat; padding-top:15px; font-weight:bold; font-size:24px;">
	  <?=$place?>
	  </div>
	  </td>
	  </tr>
	  </table>
	  <table class="main_black" width="100%">
	  <tr>
	  <td>Dials</td>
	  <td align="right">
	  <?=$total_attempted?>
	  </td>
	  </tr>
	  <tr>
	  <td>Connections</td>
	  <td align="right">
	  <?=$total_completed?>
	  </td>
	  </tr>
	  <tr>
	  <td><?=$MET[1]?></td>
	  <td align="right">
	  <?=$webinar?><?=$web_goal?>
	  </td>
	  </tr>
	  <tr>
	  <td><?=$MET[2]?></td>
	  <td align="right">
	  <?=$goto?><?=$goto_goal?>
	  </td>
	  </tr>
	  <tr>
	  <td><?=$MET[3]?></td>
	  <td align="right">
	  <?=$app?><?=$app_goal?>
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

</div>