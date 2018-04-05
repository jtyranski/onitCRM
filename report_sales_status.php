<?php include "includes/header.php"; ?>
<div align="center">
  <div class="whiteround" style="height:1620px;">
  <div style="width:100%; height:1620px; overflow:auto; text-align:left;">
<?php
/*
if($SESSION_USER_ID != 1){
  echo "This report is undergoing maintenance.  It will be back up shortly.";
  exit;
}
*/
?>
<?php

$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "todate";

$user_id = $_GET['user_id'];


if($user_id=="") {
  if($_SESSION[$sess_header . '_ss_user_id'] != ""){
    $user_id = $_SESSION[$sess_header . '_ss_user_id'];
  }
  else {
    $user_id = $SESSION_USER_ID;
    //$user_id="all";
  }
}



$view = $_GET['view'];
if($view=="") {
  if($_SESSION[$sess_header . '_ss_view'] != ""){
    $view = $_SESSION[$sess_header . '_ss_view'];
  }
  else {
    $view = "All";
  }
}

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") {
  if($_SESSION[$sess_header . '_ss_order_by'] != ""){
    $order_by = $_SESSION[$sess_header . '_ss_order_by'];
  }
  else {
    $order_by = "lastaction";
  }
}
if($order_by2 == "") {
  if($_SESSION[$sess_header . '_ss_order_by2'] != ""){
    $order_by2 = $_SESSION[$sess_header . '_ss_order_by2'];
  }
  else {
    $order_by2 = "desc";
  }
}
$order_clause = " order by $order_by $order_by2";





$product = $_GET['product'];
if($product == "") {
  if($_SESSION[$sess_header . '_ss_product'] != ""){
    $product = $_SESSION[$sess_header . '_ss_product'];
  }
  else {
    $product = "0";
  }
}
if($product) {
  $product_clause = " and a.opp_product_id = '$product' ";
}




if($user_id == "all") {
  $totalclause = " 1=1 ";
}
else {
  $totalclause = " a.user_id = '$user_id' ";

}

$zero = $_GET['zero'];
if($zero==""){
  if($_SESSION[$sess_header . '_ss_zero'] != ""){
    $zero = $_SESSION[$sess_header . '_ss_zero'];
  }
  else {
    $zero = "yes";
  }
}
if($zero=="no") {
  $zero_clause = " and a.amount > 0 ";
}



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
	$goal_multiplier = 1 / 7;
	break;
  }
  case "yesterday":{
    $yesterday = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$daterange = "XXX like '" . $yesterday . "%' ";
	$goal_multiplier = 1 / 7;
	break;
  }
case "thisweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval 0 DAY) and XXX <= date_add(curdate(), interval 7 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -1 DAY) and XXX <= date_add(curdate(), interval 6 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -2 DAY) and XXX <= date_add(curdate(), interval 5 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -3 DAY) and XXX <= date_add(curdate(), interval 4 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -4 DAY) and XXX <= date_add(curdate(), interval 3 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -5 DAY) and XXX <= date_add(curdate(), interval 2 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -6 DAY) and XXX <= date_add(curdate(), interval 1 DAY)';
    break;
  }
}
// end this week
$goal_multiplier = 1;
break;
}

case "lastweek":{
// last week
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval -7 DAY) and XXX <= date_add(curdate(), interval 0 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -8 DAY) and XXX <= date_add(curdate(), interval -1 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -9 DAY) and XXX <= date_add(curdate(), interval -2 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -10 DAY) and XXX <= date_add(curdate(), interval -3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -11 DAY) and XXX <= date_add(curdate(), interval -4 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -12 DAY) and XXX <= date_add(curdate(), interval -5 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -13 DAY) and XXX <= date_add(curdate(), interval -6 DAY)';
    break;
  }
}
$goal_multiplier = 1;
// end last week
break;
}
  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	$goal_multiplier = 4.3;
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
	$goal_multiplier = 4.3;
	break;
  }
  
  case "ytd":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	$goal_multiplier = 52;
	break;
  }
  case "Custom":{
    $custom_startdate = $_GET['custom_startdate'];
	$custom_enddate = $_GET['custom_enddate'];
	$start_parts = explode("/", $custom_startdate);
    $startdate_pretty = $start_parts[2] . "-" . $start_parts[0] . "-" . $start_parts[1];
	$end_parts = explode("/", $custom_enddate);
    $enddate_pretty = $end_parts[2] . "-" . $end_parts[0] . "-" . $end_parts[1];
	$daterange = " XXX >= '$startdate_pretty' and XXX <= '$enddate_pretty 23:59:59' ";
	break;
  }
  default:{
    $daterange = "XXX like '$searchby%'";
	$forceblack = 1;
	$goal_multiplier = 52;
	break;
  }
  
} // end switch of searchby

$daterange_query = $daterange;
$daterange_query = str_replace("XXX", "a.lastaction", $daterange_query);


$_SESSION[$sess_header . '_ss_user_id'] = $user_id;
$_SESSION[$sess_header . '_ss_order_by'] = $order_by;
$_SESSION[$sess_header . '_ss_order_by2'] = $order_by2;
$_SESSION[$sess_header . '_ss_product'] = $product;
$_SESSION[$sess_header . '_ss_zero'] = $zero;

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

if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

// due to the new sessions storing query string variables, I'm making my own query string for the export line, 
// since the actual qs may not have these session values - 4/27/10 JW Happy Birthday!
$qs = "user_id=" . $user_id . "&view=" . $view . "&order_by=" . $order_by . "&order_by2=" . $order_by2;
$qs .= "&projected=" . $projected . "&type_search=" . $type_search . "&srmanager=" . $srmanager . "&hide_zero_amount=" . $hide_zero_amount . "&product=" . $product . "&report_view=" . $report_view;
$qs .= "&opp_stage_id=" . $opp_stage_id . "&tm=" . $tm . "&territory=" . $territory;

//$rd = $_SERVER['QUERY_STRING'];
$rd = $qs;
$rd = go_reg_replace("\&", "*", $rd);


$irep_clause = "";
if($SESSION_IREP==1) $irep_clause = " and c.irep = '" . $SESSION_USER_ID . "' ";

$groups_clause = " 1=1 ";
$groups_filter_clause = " 1=1 ";
$user_groups_filter = " 1=1 ";
if($SESSION_USE_GROUPS==1){
 
  
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " c.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and c.subgroups in ($subgroups_search)";
  }
  
  
  if($SESSION_GROUPS != ""){
    $groups_raw = explode(",", $SESSION_GROUPS);
    $groups = array();
    for($x=0;$x<sizeof($groups_raw);$x++){
      if($groups_raw[$x] == "") continue;
	  $groups[] = $groups_raw[$x];
    }
  }
  
  if($SESSION_SUBGROUPS != ""){
    $subgroups_raw = explode(",", $SESSION_SUBGROUPS);
    $subgroups = array();
    for($x=0;$x<sizeof($subgroups_raw);$x++){
      if($subgroups_raw[$x] == "") continue;
	  $subgroups[] = $subgroups_raw[$x];
    }
  }

  
  $group_filter = $_GET['group_filter'];
  $subgroup_filter = $_GET['subgroup_filter'];
  if(!(is_array($group_filter))) $group_filter = $_SESSION[$sess_header . '_ss_group_filter'];
  if(!(is_array($subgroup_filter))) $subgroup_filter = $_SESSION[$sess_header . '_ss_subgroup_filter'];
  if(!(is_array($group_filter))) $group_filter = array();
  if(!(is_array($subgroup_filter))) $subgroup_filter = array();
  
  $_SESSION[$sess_header . '_ss_group_filter'] = $group_filter;
  $_SESSION[$sess_header . '_ss_subgroup_filter'] = $subgroup_filter;
  
  
  $groups_array = $group_filter;
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_filter_clause = " c.groups in($groups_search) ";;
  if($groups_filter_clause == "") $groups_filter_clause = " 1=1 ";
  
  if(sizeof($subgroup_filter) > 0){
    if($groups_filter_clause == " 1=1 "){
	  $groups_filter_clause = "(";
	}
	else {
      $groups_filter_clause = "(" . $groups_filter_clause;
	}
    $subgroups_array = $subgroup_filter;
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($groups_filter_clause != "(") $groups_filter_clause .= " or ";
    $groups_filter_clause = $groups_filter_clause .= " c.subgroups in($subgroups_search)) ";
  }
  
  
  if($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != "") $user_groups_filter = " user_id in(" . $SESSION_GROUP_ALLOWED_USERS . ") ";
}


$sql = "SELECT count(DISTINCT a.opp_id) as count, sum(a.amount) as total from opportunities a, properties c, prospects d
where $totalclause and a.display=1
 and a.status='Quoted'
 and a.property_id=c.property_id
 and a.prospect_id=d.prospect_id
 and d.master_id='" . $SESSION_MASTER_ID . "'
 $product_clause
 $zero_clause
 $irep_clause
 and $groups_clause
 and $groups_filter_clause
 and $daterange_query";
$result = executequery($sql);
$record = go_fetch_array($result);
$count_quoted = $record['count'];
$total_quoted = $record['total'];


$sql = "SELECT count(DISTINCT a.opp_id) as count, sum(a.amount) as total from opportunities a, properties c, prospects d
where $totalclause and a.status='Sold' and a.display=1
 and a.property_id=c.property_id
 and a.prospect_id=d.prospect_id
 and d.master_id='" . $SESSION_MASTER_ID . "'
 $product_clause
 $zero_clause
 $irep_clause
 and $groups_clause
 and $groups_filter_clause
 and $daterange_query";
$result = executequery($sql);
$record = go_fetch_array($result);
$count_sold = $record['count'];
$total_sold = $record['total'];


$sql = "SELECT count(a.opp_id) as count, sum(a.amount) as total from opportunities a, properties c, prospects d
where $totalclause and a.status='Dead' and a.display=1
 and a.property_id=c.property_id
 and a.prospect_id=d.prospect_id
 and d.master_id='" . $SESSION_MASTER_ID . "'
 $product_clause
 $zero_clause
 $irep_clause
 and $groups_clause
 and $groups_filter_clause
 and $daterange_query";
$result = executequery($sql);
$record = go_fetch_array($result);
$count_dead = $record['count'];
$total_dead = $record['total'];

?>
<script src="includes/calendar.js"></script>
<script>
function go_infoform(x){
  document.infoform.action.value="test";
  document.infoform.clicktype.value=x;
  document.infoform.target= "_blank";
  document.infoform.submit();
}

var form='infoform'; //Give the form name here

function SetChecked(x,chkName) {
  if(x.checked==true){ 
    val = 1; 
  }
  else {
    val = 0;
  }
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=val;
    }
  }
}

function datefilter_change(x){
  if(x.value=="Custom"){
    document.getElementById("customdate").style.display="";
  }
  else {
    document.getElementById("customdate").style.display="none";
  }
}

function OpenClose(group, x){
  if(x==1){
    document.getElementById('group_' + group).style.display="";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById('group_' + group).style.display="none";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
  }
}

function OpenClose_Main(group, x){
  if(x==1){
    document.getElementById(group + '_list').style.display="";
	document.getElementById(group + '_list_arrow').innerHTML = "<a href=\"javascript:OpenClose_Main('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById(group + '_list').style.display="none";
	document.getElementById(group + '_list_arrow').innerHTML = "<a href=\"javascript:OpenClose_Main('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
  }
}
</script>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
<table class="main" cellpadding="0" cellspacing="0" width="100%">
<tr>
<td>
<div class="main_superlarge">Current Sales Status Detail</div>
<td align="right">
Number of Records: <span id="numrecords"></span>
</td>
</tr>
</table>
<?php if($SESSION_USER_LEVEL == "Manager"){ ?>
Data for: <select name="user_id">
<option value="all"<?php if($user_id=="all") echo " selected"; ?>>All</option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users a where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $user_groups_filter";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($user_id == $record['user_id']) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
&nbsp; &nbsp; 
<?php } ?>

<div style="position:relative;">
<div style="float:left;">
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
</div>







<div style="float:right; padding-right:10px;" class="main">
<input type="submit" name="submit1" value="Filter">

&nbsp;
<input type="button" name="buttonclearfilter" value="Clear Filter" onclick="document.location.href='report_sales_status_clearfilter.php'">
</div>

<?php 

if($SESSION_USE_GROUPS){ ?>
<div style="float:right; padding-right:10px;" class="main">
Groups:
<span id="group_list_arrow"><a href="javascript:OpenClose_Main('group', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
<div id="group_list" style="display:none;">
<?php
$sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of=0 order by group_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $group_id = $record['id'];
  if(is_array($groups)){
    if(in_array($group_id, $groups)){
      ?>
      <input type="checkbox" name="group_filter[]" value="<?=$group_id?>"<?php if(in_array($group_id, $group_filter)) echo " checked";?>> 
	  <?php
	}
  }
  else {
    ?>
	<input type="checkbox" name="group_filter[]" value="<?=$group_id?>"<?php if(in_array($group_id, $group_filter)) echo " checked";?>> 
	<?php
  }
  ?>
  <span id="group_<?=$group_id?>_arrow" style="display:none;"><a href="javascript:OpenClose('<?=$group_id?>', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
  <?=stripslashes($record['group_name'])?>
  <br>
  <div id="group_<?=$group_id?>" style="padding-left:20px; display:none;">
  <?php
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id' order by group_name";
  $result_sub = executequery($sql);
  if(mysql_num_rows($result_sub)) $SHOWARROW[] = $group_id;
  while($record_sub = go_fetch_array($result_sub)){
    $subgroup_id = $record_sub['id'];
	if(is_array($subgroups)){
	  if(in_array($subgroup_id, $subgroups)){
	    ?>
	    <input type="checkbox" name="subgroup_filter[]" value="<?=$subgroup_id?>"<?php if(in_array($subgroup_id, $subgroup_filter)) echo " checked";?>> <?=stripslashes($record_sub['group_name'])?><br>
		<?php
	  }
	}
	else {
	  ?>
      <input type="checkbox" name="subgroup_filter[]" value="<?=$subgroup_id?>"<?php if(in_array($subgroup_id, $subgroup_filter)) echo " checked";?>> <?=stripslashes($record_sub['group_name'])?><br>
	  <?php
	}
  }
  ?>
  </div>
  <?php
}
?>
</div>
</div>
<?php } // end if using groups 

?>

<div style="float:right; padding-right:10px;" class="main">
$0 Amounts?
<select name="zero">
<option value="yes"<?php if($zero=="yes") echo " selected";?>>Yes</option>
<option value="no"<?php if($zero=="no") echo " selected";?>>No</option>
</select>
</div>

<div style="float:right; padding-right:10px;" class="main">
Status:
<select name="view">
<option value="All"<?php if($view=="All") echo " selected";?>>All</option>
<option value="Quoted"<?php if($view=="Quoted") echo " selected";?>>Quoted</option>
<option value="Sold"<?php if($view=="Sold") echo " selected";?>>Sold</option>
<option value="Dead"<?php if($view=="Dead") echo " selected";?>>Dead</option>
</select>
</div>

<div style="float:right; padding-right:10px;" class="main">

Product:
<select name="product">
<option value="0">All</option>
<?php
$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
$test = getsingleresult($sql);
$sql = "SELECT quickbid from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$test_qb = getsingleresult($sql);

$sql = "SELECT * from opportunities_master";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($test==0 && $record['opp_product_id']==-3) continue;
  if($test_qb==0 && $record['opp_product_id']==-4) continue;
  ?>
  <option value="<?=$record['opp_product_id']?>"<?php if($record['opp_product_id']==$product) echo " selected";?>><?=stripslashes($record['opp_name'])?></option>
  <?php
}
?>
<?php
$sql = "SELECT * from opportunities_items where master_id='" . $SESSION_MASTER_ID . "' order by opp_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['opp_product_id']?>"<?php if($record['opp_product_id']==$product) echo " selected";?>><?=stripslashes($record['opp_name'])?></option>
  <?php
}
?>
</select>
</div>


</div>
<div style="clear:both;"></div>

<div style="width:100%; position:relative;">
<div class="main" style="float:left;">
  <table class="main">
  <tr>
  <td><strong>Quoted</strong></td>
  <td>$<?=number_format($total_quoted, 0)?> (<?=$count_quoted?>)</td>
  </tr>
  <tr>
  <td><strong>Sold</strong></td>
  <td>$<?=number_format($total_sold, 0)?> (<?=$count_sold?>)</td>
  </tr>
  <tr>
  <td><strong>Dead</strong></td>
  <td>$<?=number_format($total_dead, 0)?> (<?=$count_dead?>)</td>
  </tr>
  </table>
</div>
<div style="float:right;">
<?php
if($SESSION_MASTER_ID==1){
  ImageLink("go_infoform('fcsrep')", "fcsmail-icon", 0, 1);
}
?>
<?php
ImageLink("go_infoform('map')", "us-icon", 1, 1);
?>

</div>
</div>
<div style="clear:both;"></div>

</form>
<form action="contacts_action.php" method="post" name="infoform"> <?php // using contacts_action because we're just using same map feature ?>
<input type="hidden" name="action" value="Save Changes">
<input type="hidden" name="boxtype" value="property">
<input type="hidden" name="clicktype" value="">

<div style="width:100%; height:1400px; overflow:auto;">
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><input type='checkbox' onchange="SetChecked(this, 'ids[]')"></td>
<td><strong><?=sort_header('lastaction', 'Date')?></strong></td>
<td><strong><?=sort_header('status', 'Status')?></strong></td>
<td><strong><?=sort_header('site_name', 'Property')?></strong></td>
<td><strong><?=sort_header('product', 'Product')?></strong></td>
<td><strong><?=sort_header('amount', 'Amount')?></strong></td>
<td><strong><?=sort_header('firstname', 'User')?></strong></td>

<td></td>
</tr>
<?php
switch($view){
  case "All":{
    $whereclause = " And 1=1 ";
	break;
  }
  case "Sold":{
    $whereclause = " And a.status='Sold' ";
	break;
  }

  default:{
    $whereclause = " And a.status='$view' ";
	break;
  }
}

$sql = "SELECT * from opportunities_master";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $opp_product_id = $record['opp_product_id'];
  $opp_name = stripslashes($record['opp_name']);
  $OPP_PRODUCT[$opp_product_id] = $opp_name;
}

$sql = "SELECT * from opportunities_items where master_id='" . $SESSION_MASTER_ID . "' order by opp_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $opp_product_id = $record['opp_product_id'];
  $opp_name = stripslashes($record['opp_name']);
  $OPP_PRODUCT[$opp_product_id] = $opp_name;
}

$sql = "SELECT a.status, a.product, a.amount, a.opp_id, a.user_id, concat(b.firstname, ' ', b.lastname) as fullname, date_format(a.lastaction, \"%m/%d/%y\") as datepretty, 
a.property_id, a.prospect_id, a.lastaction, a.opp_product_id, 
a.opp_stage_changedate, date_format(a.opp_stage_changedate, \"%m/%d/%y\") as changedate_pretty, 
date_format(a.paid_date, \"%m/%d/%y\") as paiddate_pretty, a.paid_date, c.site_name, c.corporate, b.resource_id, d.irep
from opportunities a, users b, properties c, prospects d
where a.prospect_id = d.prospect_id and d.master_id='" . $SESSION_MASTER_ID . "'
and a.property_id = c.property_id 
and a.user_id=b.user_id
$whereclause and a.display=1
$zero_clause
$product_clause
and $daterange_query
$irep_clause
and $groups_clause
and $groups_filter_clause
and $totalclause";
$result = executequery($sql);
//echo "<!-- XXX $sql -->\n";
$counter=0;
while($record = go_fetch_array($result)){
  $irep = stripslashes($record['irep']);
  if($SESSION_IREP==1){
	if(!(go_reg("," . $SESSION_USER_ID . ",", $irep))) continue;
  }
  $property_id = $record['property_id'];
  
  $stage_name = stripslashes($record['stage_name']);
  $display_order = stripslashes($record['display_order']);
  $changedate_raw = stripslashes($record['opp_stage_changedate']);
  $changedate = stripslashes($record['changedate_pretty']);
  $paiddate_raw = stripslashes($record['paid_date']);
  $paiddate = stripslashes($record['paiddate_pretty']);
  
  $territory = stripslashes($record['territory']);
  
  $projected_replacement = $record['projected_replacement'];
  if($projected_replacement=="0") $projected_replacement = "";
  $amount = $record['amount'];
  $probability = $record['probability'] / 100;
  $subtotal = $amount * $probability;
  //$total += $subtotal;
  $total += $amount;
  $site_name = stripslashes($record['site_name']);
  $corporate = $record['corporate'];
  $opp_product_id = $record['opp_product_id'];
  $product = $OPP_PRODUCT[$opp_product_id];
  $old_product = $record['product'];
  if($opp_product_id==-3){
    $old_product = go_reg_replace("Leak ", "", $old_product);
	$product .= " ID: " . $old_product;
  }
  
  $fullname = stripslashes($record['fullname']);
  $resource_id = $record['resource_id'];
  if($resource_id != 0){
    $sql = "SELECT company_name from prospects where prospect_id='$resource_id'";
	$fullname = stripslashes(getsingleresult($sql));
  }
  /*
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $record['srmanager'] . "'";
  $srmanager = stripslashes(getsingleresult($sql));
  */
  
  
  $row[$counter]['territory'] = $territory;
  $row[$counter]['stage_name'] = $stage_name;
  $row[$counter]['display_order'] = $display_order;
  $row[$counter]['changedate_raw'] = $changedate_raw;
  $row[$counter]['changedate'] = $changedate;
  $row[$counter]['paiddate_raw'] = $paiddate_raw;
  $row[$counter]['paiddate'] = $paiddate;
  
  $row[$counter]['lastaction'] = $record['lastaction'];
  $row[$counter]['datepretty'] = $record['datepretty'];

  $row[$counter]['status'] = $record['status'];
  $row[$counter]['loi_flag'] = $record['loi_flag'];
  $row[$counter]['corporate'] = $corporate;
  $row[$counter]['property_id'] = $record['property_id'];
  $row[$counter]['prospect_id'] = $record['prospect_id'];
  $row[$counter]['site_name'] = $site_name;
  if($corporate == 0){ 
    $url = '<a href="view_property.php?property_id=' . $record['property_id'] . '&view=Opportunities" target="_blank" class="blankclick">';
  } else { 
    $url = '<a href="view_prospect.php?prospect_id=' . $record['prospect_id'] . '" target="_blank" class="blankclick">';
  }
  
  $row[$counter]['url'] = $url;
  $row[$counter]['product'] = $product;
  $row[$counter]['amount'] = $amount;
  $row[$counter]['firstname'] = $fullname;
  $row[$counter]['srmanager'] = $srmanager;
  $row[$counter]['probability'] = $record['probability'];
  $row[$counter]['projected_replacement'] = $projected_replacement;
  $row[$counter]['opp_id'] = $record['opp_id'];
  
  
  $counter++;
}
usort($row, $function);

for($x=0;$x<sizeof($row);$x++){
  
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><input type="checkbox" name="ids[]" value="<?=$row[$x]['property_id']?>"></td>
  <td><?=$row[$x]['datepretty']?></td>
  <td><?=$row[$x]['status']?></td>
  <td>
  <?=$row[$x]['url']?>
  <?=$row[$x]['site_name']?>
    </a>
  </td>
  <td><?=stripslashes($row[$x]['product'])?></td>
  <td style="padding-right:10px;">

    $<?=number_format($row[$x]['amount'], 0)?>
  </td>
  <td><?=$row[$x]['firstname']?></td>
  <td>
  <?php if($SESSION_ISADMIN){/* ?>
  <a href="opportunity_edit.php?prospect_id=<?=$row[$x]['prospect_id']?>&opp_id=<?=$row[$x]['opp_id']?>&redirect=<?=$_SERVER['SCRIPT_NAME']?>?<?=$rd?>" target="_parent">edit</a>
  <?php */} ?>
  </td>
  </tr>
  <?php
}
?>
</table>

<div align="right">
<strong>Total: $<?=number_format($total, 2)?><br></strong>
</div>

</div>
</form>
<script>
document.getElementById("numrecords").innerHTML = "<?=sizeof($row)?>";
</script>
<script>
<?php
for($x=0;$x<sizeof($SHOWARROW);$x++){
  if($SHOWARROW[$x]=="") continue;
  ?>
  document.getElementById('group_<?=$SHOWARROW[$x]?>_arrow').style.display="";
  <?php
}
?>
</script>
</div>
</div>
</div>
<?php include "includes/footer.php"; ?>
