<?php include "includes/header_white.php"; ?>
<?php
$ranking = $_GET['ranking'];
if($ranking=="") $ranking = "Sold";
$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "lastweek";
$submit1 = $_GET['submit1'];
$search_user_id = $_GET['search_user_id'];
$details = $_GET['details'];
if($details=="") $details = "prospecting";
if($search_user_id==""){
  $search_user_id = $SESSION_USER_ID;
}


$sql = "SELECT servicemen from users where user_id='$search_user_id' and master_id='$SESSION_MASTER_ID'";
$test = getsingleresult($sql);
if($search_user_id==0) $test = 1;

if($test==0){
  $sql = "SELECT user_id from users where enabled=1 and servicemen=1 and master_id='$SESSION_MASTER_ID' order by lastname limit 1";
  $search_user_id = getsingleresult($sql);
}

if($search_user_id==0 || $search_user_id==""){
  $sql = "This report is designed to show statistics for your service men.  You currently have no users marked as service men in your user list";
  exit;
}

$act_tm_clause = " 1=1 ";
$opp_tm_clause = " 1=1 ";

$special_user_query = " user_id='$search_user_id' ";
$servicemen_special_user_query = "servicemen_id = '$search_user_id' or servicemen_id2 = '$search_user_id'";




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

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by2 == "") {
    $order_by2 = "asc";
}
if($order_by == "") {
    $order_by = "bid_id";
}

if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

if($search_user_id != 0){
  $sql = "SELECT photo, concat(firstname, ' ', lastname) as fullname from users where $special_user_query";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $photo = stripslashes($record['photo']);
  $fullname = stripslashes($record['fullname']);
}
else {
  $photo = "";
  $fullname = "All Servicemen";
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


$quota_daterange = $daterange;
$quota_daterange = str_replace("XXX", "c.date", $quota_daterange);

$pay_date_daterange = $daterange;
$pay_date_daterange = str_replace("XXX", "pay_date", $pay_date_daterange);

$exp_date_daterange = $daterange;
$exp_date_daterange = str_replace("XXX", "enter_date", $exp_date_daterange);

$leakcheck_invoice_daterange = $daterange;
$leakcheck_invoice_daterange = str_replace("XXX", "invoice_date", $leakcheck_invoice_daterange);

/*
$opp_sold_daterange_ytd = " sold_date like '" . date("Y") . "%' ";
$sales_daterange_ytd = " a.lastaction like '" . date("Y") . "%' ";
*/

$opp_sold_daterange_ytd = " 1=1 ";
$sales_daterange_ytd = " 1=1 ";

$sql = "SELECT count(*) from users where servicemen=1 and enabled=1 and master_id='$SESSION_MASTER_ID'";
$totalusers = getsingleresult($sql);

switch($ranking){
  case "Sold":{
    $ranking_query = "SELECT sum(a.amount) as gtotal, a.user_id from opportunities a, prospects b where a.prospect_id=b.prospect_id and b.master_id='$SESSION_MASTER_ID' and a.status='Sold' and a.display=1 group by a.user_id order by gtotal desc";
	break;
  }
  case "Quoted":{
    $ranking_query = "SELECT sum(a.amount) as gtotal, a.user_id from opportunities a, prospects b where a.prospect_id=b.prospect_id and b.master_id='$SESSION_MASTER_ID' and a.status='Quoted' and a.display=1 group by a.user_id order by gtotal desc";
	break;
  }
  case "NumSold":{
    $ranking_query = "SELECT count(a.amount) as gtotal, a.user_id from opportunities a, prospects b where a.prospect_id=b.prospect_id and b.master_id='$SESSION_MASTER_ID' and a.status='Sold' and a.display=1 group by a.user_id order by gtotal desc";
	break;
  }
  case "Clients":{
    $ranking_query = "SELECT COUNT(DISTINCT a.prospect_id) as gtotal, a.servicemen_id as user_id from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='$SESSION_MASTER_ID' and a.servicemen_id != 0 group by a.servicemen_id order by gtotal desc";
	break;
  }
  case "Produced":{
    $ranking_query = "SELECT sum(a.invoice_total) as gtotal, a.servicemen_id as user_id from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='$SESSION_MASTER_ID' and a.servicemen_id!=0 group by a.servicemen_id order by gtotal desc";
	break;
  }
  default:{
    $ranking_query = "SELECT sum(a.amount) as gtotal, a.user_id from opportunities a, prospects b where a.prospect_id=b.prospect_id and b.master_id='$SESSION_MASTER_ID' and a.status='Sold' and a.display=1 group by a.user_id order by gtotal desc";
	break;
  }
}

$counter=0;
$result = executequery($ranking_query);
while($record = go_fetch_array($result)){
  $sql = "SELECT servicemen from users where user_id='" . $record['user_id'] . "'";
  $test = getsingleresult($sql);
  if($test==0) continue;
  $counter++;
  if($record['user_id']==$search_user_id){
    $place = $counter;
  }
}
if($place=="") $place = $totalusers;


$sql = "SELECT sum(amount) from opportunities a where status='Quoted' and product='Roof Repair' and $sales_daterange_ytd and $special_user_query and display=1";
$totalquoted = getsingleresult($sql);
$sql = "SELECT sum(amount) from opportunities  where status='Sold' and product='Roof Repair' and $opp_sold_daterange_ytd and $special_user_query and display=1";
$totalsold = getsingleresult($sql);
$close_rate = ($totalsold / $totalquoted) * 100;
$sql = "SELECT sum(amount) from opportunities a where status='Dead' and product='Roof Repair' and $sales_daterange_ytd and $special_user_query and display=1";
$totaldead = getsingleresult($sql);
$sql = "SELECT count(*) from opportunities  where status='Sold' and product='Roof Repair' and amount > 0 and $opp_sold_daterange_ytd and $special_user_query and display=1";
$selltotal = getsingleresult($sql);

/*
$sql = "SELECT count(*) from opportunities where budget_set_date != '0000-00-00' and display=1 and product='Roof Repair'  
and $special_user_query and $opp_tm_clause";
$agreements = getsingleresult($sql);
*/

$sql = "SELECT count(*) from am_leakcheck where $servicemen_special_user_query group by prospect_id";
$result = executequery($sql);
$clients = mysql_num_rows($result);


  $sql = "SELECT sum(invoice_total)
  from am_leakcheck where ($servicemen_special_user_query) 
  and (status='Invoiced' or status='Closed Out')";
  //echo "<!-- ZZZ $sql -->\n";
  $produced = getsingleresult($sql);

?>
<script src="includes/calendar.js"></script>
<script>
function DelExpense(x){
  cf = confirm("Are you sure you want to delete this expense?");
  if(cf){
    document.location.href="report_commission_individual_expense_delete.php?user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&id=" + x + "&redirect=report_serviceman_individual.php";
  }
}

function AddPayout (clear) {
    check_num = document.addpayoutform.check_num.value;
	paydatepretty = document.addpayoutform.paydatepretty.value;
	pay_type = document.addpayoutform.pay_type.value;
	pay_type_id = document.addpayoutform.pay_type_id.value;
	amount = document.addpayoutform.amount.value;
	
    url = "report_commission_individual_addpayout.php?check_num=" + check_num + "&paydatepretty=" + paydatepretty + "&pay_type=" + pay_type + "&pay_type_id=" + pay_type_id + "&amount=" + amount + "&clear=" + clear;
    url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		//alert(url);
}

function ShowHide(id, x){
  if(x==1){
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '0')\">-</a>";
	document.getElementById("info_" + id).style.display = "";
  }
  else {
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '1')\">+</a>";
	document.getElementById("info_" + id).style.display = "none";
  }
}

function ptd_display(x){
  y = x.value;
  if(y=="Draw"){
    document.getElementById("ptd").style.display="none";
  }
  else {
    document.getElementById("ptd").style.display="";
  }
}

function show_paid_details(x){
  y = x.value;
  document.form1.show_paid_details.value = y;
  document.form1.submit();
}

function show_paid_expenses(x){
  y = x.value;
  document.form1.show_paid_expenses.value = y;
  document.form1.submit();
}

function DelLineItem(x){
  cf = confirm("Are you sure you want to delete this line item?");
  if(cf){
    document.location.href="report_commission_individual_deletelineitem.php?user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&redirect=report_commission_individual.php&id=" + x;
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
</script>
<script>
function MarkPaid(x,y){
  document.location.href="report_commission_individual_expense_markpaid.php?user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&id=" + x + "&p=" + y;
}
</script>
<div class="main_superlarge">Individual Serviceman Report</div>

<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
<select name="ranking" onchange="document.form1.submit()">
<option value="Quoted"<?php if($ranking=="Quoted") echo " selected"; ?>>Quoted</option>
<option value="Sold"<?php if($ranking=="Sold") echo " selected"; ?>>Sold</option>
<option value="NumSold"<?php if($ranking=="NumSold") echo " selected"; ?>>Number Sold</option>
<option value="Produced"<?php if($ranking=="Produced") echo " selected"; ?>>Produced</option>
<option value="Agreements"<?php if($ranking=="Agreements") echo " selected"; ?>>Agreements</option>
<option value="Clients"<?php if($ranking=="Clients") echo " selected"; ?>>Clients</option>
</select>
&nbsp; &nbsp;

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
<a href="report_serviceman_individual_print.php?<?=$_SERVER['QUERY_STRING']?>" target="_blank">print</a>

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
<div style="width:100%; position:relative;">
<div style="float:left; width:25%;" class="main">
<table class="cornerbox" width="175">
    <tr>
    <td align="center">
    Lifetime
    <br>
	<table width="100%">
	  <tr>
	  <td valign="top">
	  <?php if($photo != "") { ?>
	  <img src="uploaded_files/headshots/<?=$photo?>">
	  <?php } ?>
	  </td>
	  <td valign="middle" align="center">
      <div style="background:url(images/roundreport_circle.gif); width:68px; height:68px; background-repeat:no-repeat; padding-top:15px;" class="main_megalarge_black">
	  <?=$place?>
	  </div>
	  </td>
	  </tr>
	  </table>
	<br><br>
	  <div class="main_superlarge_black">
	  <?=number_format($close_rate, 1)?>%
	  </div>
	  Close Rate
	  <table class="main_black" width="100%">
	  <tr>
	  <td<?php if($ranking=="Quoted") echo " style='font-weight:bold;'";?>>Quoted</td>
	  <td align="right"<?php if($ranking=="Quoted") echo " style='font-weight:bold;'";?>>$<?=number_format($totalquoted, 2)?></td>
	  </tr>
	  <tr>
	  <td<?php if($ranking=="Sold") echo " style='font-weight:bold;'";?>>Sold</td>
	  <td align="right"<?php if($ranking=="Sold") echo " style='font-weight:bold;'";?>>$<?=number_format($totalsold, 2)?></td>
	  </tr>
	  <tr>
	  <td<?php if($ranking=="NumSold") echo " style='font-weight:bold;'";?>>Number Sold</td>
	  <td align="right"<?php if($ranking=="NumSold") echo " style='font-weight:bold;'";?>><?=$selltotal?></td>
	  </tr>
	  <tr>
	  <td>Dead</td>
	  <td align="right">$<?=number_format($totaldead, 2)?></td>
	  </tr>
	  
	  <tr>
	  <td colspan="2">&nbsp;</td>
	  </tr>
	  
	  <tr>
	  <td<?php if($ranking=="Produced") echo " style='font-weight:bold;'";?>>Produced</td>
	  <td align="right"<?php if($ranking=="Produced") echo " style='font-weight:bold;'";?>>$<?=number_format($produced, 0)?></td>
	  </tr>
	  <tr>
	  <td<?php if($ranking=="Agreements") echo " style='font-weight:bold;'";?>>Agreements</td>
	  <td align="right"<?php if($ranking=="Agreements") echo " style='font-weight:bold;'";?>><?=$agreements?></td>
	  </tr>
	  <tr>
	  <td<?php if($ranking=="Clients") echo " style='font-weight:bold;'";?>>Clients</td>
	  <td align="right"<?php if($ranking=="Clients") echo " style='font-weight:bold;'";?>><?=$clients?></td>
	  </tr>
	  
	  </table>
<?php if($SESSION_ISADMIN && $SESSION_IREP==0){?>
<select name="search_user_id" onchange="document.form1.submit()">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and servicemen=1 and master_id='$SESSION_MASTER_ID' and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($search_user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<?php } else { 
?>
  <strong><?=$fullname?></strong>
  <?php
}
?>
	  
	  
    </td>
    </tr>
</table>
</div>

<div style="float:left; width:75%;">


<div style="width:100%; position:relative;">


<div style="float:left; width:25%;" align="center" class="main">

<?php  
// ******** CALL activity ******************* 




?>

<?php
$counter=0;
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where $special_user_query";
if($search_user_id==0) $sql = "SELECT now()";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);
  $objective_3 = stripslashes($record['objective_3']);
  $goal = stripslashes($record['objective_3_value']);
  if($goal =="") $goal = 0;
  
  $loop_user_query = " user_id='$user_id' ";
  if($search_user_id==0) $loop_user_query = " 1=1 ";
  

  $total_attempted = $total_completed = $total_email = 0;
  $sql = "SELECT count(*) from activities where user_id='$user_id' and $c_daterange and display=1";
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
	$data_meeting[$key] = $row2['meeting'];
}

array_multisort($data_met, SORT_DESC, $data_completed, SORT_DESC, $data_attempted, SORT_DESC, $data);
$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  //if($user_id != $search_user_id) continue;
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
  $meeting = $data[$x]['meeting'];
  //$numbersold = $row[$x]['numbersold'];
  ?>
  <div style="width:200px;" class="cornerbox">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main_black" width="100%">
    <tr>
    <td align="center">
	  <table class="main_black" width="100%">
	  <tr>
	  <td>Calls Attempted</td>
	  <td align="right">
	  <?=$total_attempted?>
	  </td>
	  </tr>
	  <tr>
	  <td>Calls Completed</td>
	  <td align="right">
	  <?=$total_completed?>
	  </td>
	  </tr>
	  <tr>
	  <td>Objective Met</td>
	  <td align="right">
	  <?=$total_met?>
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

<div style="float:left; width:25%;" align="center" class="main">

<?php // Schedule activities area ?>



<?php




?>

<?php
$data = "";
$counter=0;
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where $special_user_query";
if($search_user_id==0) $sql = "SELECT now()";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);
  
  $loop_user_query = " user_id='$user_id' ";
  if($search_user_id==0) $loop_user_query = " 1=1 ";
  
  $sql = "SELECT count(*) from activities where event='Contact' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $contact_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Inspection' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $inspection_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Quickbid' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $qb_total = getsingleresult($sql);
  $sql = "SELECT count(*) from activities where event='Meeting' and $sched_daterange and complete=1 and display=1 and act_result != 'Attempted' and user_id='$user_id'";
  $meeting_total = getsingleresult($sql);
  
  
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
  $selltotal = $data[$x]['selltotal'];
  $fullname = $data[$x]['fullname'];
  $contact = $data[$x]['contact'];
  $inspection = $data[$x]['inspection'];
  $qb = $data[$x]['qb'];
  $meeting = $data[$x]['meeting'];
  $total = $data[$x]['total'];
  $photo = $data[$x]['photo'];
  ?>
  <div style="width:200px;" class="cornerbox">
  <div style="padding:10px 10px 10px 10px;">
    <table class="main_black" width="100%">
	<tr>
	<td align="center">

	  <table class="main_black" width="100%">
	  <tr>
	  <td>Inspections</td>
	  <td align="right">
	  <?=$inspection?>
	  </td>
	  </tr>

	  <tr>
	  <td> Quickbids </td>
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

<div style="float:left; width:25%;" align="center" class="main">

<?php // Sales area ?>

<?php



$counter=0;
$data = "";
$sql = "SELECT concat(firstname, ' ', lastname) as fullname, user_id, photo from users where $special_user_query";
if($search_user_id==0) $sql = "SELECT now()";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $user_id = $record['user_id'];
  //$selltotal = $record['selltotal'];
  $fullname = stripslashes($record['fullname']);
  $photo = stripslashes($record['photo']);
  
  $loop_user_query = " user_id='$user_id' ";
  if($search_user_id==0) $loop_user_query = " 1=1 ";
  

  $sql = "SELECT sum(amount) from opportunities a where $loop_user_query and status='Quoted' and $sales_daterange and display=1";
  $totalquoted = getsingleresult($sql);
  $sql = "SELECT sum(amount) from opportunities where $loop_user_query and status='Sold' and $opp_sold_daterange and display=1";
  $totalsold = getsingleresult($sql);
  $close_rate = ($totalsold / $totalquoted) * 100;
  $sql = "SELECT sum(amount) from opportunities a where $loop_user_query and status='Dead' and $sales_daterange and display=1";
  $totaldead = getsingleresult($sql);
  $sql = "SELECT count(DISTINCT opp_id) from opportunities where $loop_user_query and status='Sold' and amount > 0 and $opp_sold_daterange and display=1";
  $numbersold = getsingleresult($sql);
  
    
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
}

    array_multisort($data_totalsold, SORT_DESC, $data_totalquoted, SORT_DESC, $data_numbersold, SORT_DESC, $data);


$place=0;
$counter=0;

for($x=0;$x< sizeof($data); $x++){
  $place++;
  $user_id = $data[$x]['user_id'];
  //if($user_id != $search_user_id) continue;
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
  ?>
    <div style="width:200px;" class="cornerbox">
    <div style="padding:10px 10px 10px 10px;">
    <table class="main_black" width="100%">
    <tr>
    <td align="center">


	  <div class="main_superlarge_black">
	  <?=number_format($close_rate, 1)?>%
	  </div>
	  Close Rate
	  <table class="main_black" width="100%">
	  <tr>
	  <td id="sectiona_14">Quoted</td>
	  <td align="right" id="section_14">
	  <a href="report_salesandactivity_ind_detail.php?search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=14&service=1">
	  $<?=number_format($totalquoted, 2)?>
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_15">Sold</td>
	  <td align="right" id="section_15">
	  <a href="report_salesandactivity_ind_detail.php?search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=15&service=1">
	  $<?=number_format($totalsold, 2)?> (<?=$numbersold?>)
	  </a>
	  </td>
	  </tr>
	  <tr>
	  <td id="sectiona_16">Dead</td>
	  <td align="right" id="section_16">
	  <a href="report_salesandactivity_ind_detail.php?search_user_id=<?=$search_user_id?>&searchby=<?=$searchby?>&details=sales&table=opportunities&filter=16&service=1">
	  $<?=number_format($totaldead, 2)?>
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

</div>

</div> <?php // end of prospecting bubbles div?>
<div style="clear:both;"></div>
</form>

<?php
$act1 = array("Time and Material", "Contract", "Warranty - Billable", "RoofTop Maintenance");
$query1 = array("invoice_type='Billable - TM'", "invoice_type='Billable - Contract'", "invoice_type='Warranty'", "invoice_type='RTM'");
$invoicetype1 = array("Billable - TM", "Billable - Contract", "Warranty", "RTM");

$act2 = array("Sales/Promo", "Warranty - Non Billable");
$query2 = array("invoice_type='Promotional'", "invoice_type='2 Year'");
$invoicetype2 = array("All", "2 Year");
?>
<br>


<div>
<br>
<div class="main_large">Billing Activity</div>
<table class="cornerbox" width="90%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Activity</strong></td>
<td><strong>Hours</strong></td>
<td><strong>Produced</strong></td>
</tr>
<?php
$total_hours = 0;
$total_sales = 0;
$total_credits = 0;
$total_adjusted = 0;
$total_earnings = 0;


$sub_hours = 0;
$sub_sales = 0;
$sub_credits = 0;
$sub_adjusted = 0;
$sub_earnings = 0;


for($x=0;$x<sizeof($act1);$x++){
  $sql = "SELECT sum(invoice_total) as produced, sum(travel_time) as ttime, sum(labor_time) as ltime
  from am_leakcheck where ($servicemen_special_user_query) and $leakcheck_invoice_daterange 
  and (status='Invoiced' or status='Closed Out')
  and " . $query1[$x];
  $result = executequery($sql);
  $hours = 0;
  $sales = 0;
  while($record = go_fetch_array($result)){
    $sales = $record['produced'];
	$ttime = $record['ttime'];
	$ltime = $record['ltime'];
	$hours = $ttime + $ltime;
  }

  
  $sub_hours += $hours;
  $sub_sales += $sales;
  
  $total_hours += $hours;
  $total_sales += $sales;
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=$act1[$x]?></td>
  <td>
  <a href="fcs_sd_report.php?invoicetypefilter=<?=$invoicetype1[$x]?>&servicemanfilter=<?=$search_user_id?>&invoicedatefilter=<?=$searchby?>&startdate=<?=$custom_startdate?>&enddate=<?=$custom_enddate?>">
  <?=$hours?>
  </a>
  </td>
  <td>$<?=$sales?></td>
  </tr>
  <?php
}

?>
<tr><td colspan="6"><hr size="1"></td></tr>
<tr>
<td></td>
<td><?=$sub_hours?></td>
<td>$<?=$sub_sales?></td>

</tr>

<tr><td colspan="6">&nbsp;</td></tr>
<?php
$sub_hours = 0;
$sub_sales = 0;
$sub_credits = 0;
$sub_adjusted = 0;
$sub_earnings = 0;
for($x=0;$x<sizeof($act2);$x++){
  $sql = "SELECT sum(invoice_total) as produced, sum(travel_time) as ttime, sum(labor_time) as ltime
  from am_leakcheck where ($servicemen_special_user_query) and $leakcheck_invoice_daterange 
  and (status='Invoiced' or status='Closed Out')
  and " . $query2[$x];
  $result = executequery($sql);
  $hours = 0;
  $sales = 0;
  while($record = go_fetch_array($result)){
    $sales = $record['produced'];
	$ttime = $record['ttime'];
	$ltime = $record['ltime'];
	$hours = $ttime + $ltime;
  }
  

  $sub_hours += $hours;
  $sub_sales += $sales;
  
  $total_hours += $hours;
  $total_sales += $sales;
  ?>
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=$act2[$x]?></td>
  <td>
  <a href="fcs_sd_report.php?invoicetypefilter=<?=$invoicetype2[$x]?>&servicemanfilter=<?=$search_user_id?>&invoicedatefilter=<?=$searchby?>&startdate=<?=$custom_startdate?>&enddate=<?=$custom_enddate?>">
  <?=$hours?>
  </a>
  </td>
  <td>$<?=$sales?></td>
  <td></td>
  </tr>
  <?php
}
?>
<tr><td colspan="6"><hr size="1"></td></tr>
<tr>
<td></td>
<td><?=$sub_hours?></td>
<td>$<?=$sub_sales?></td>
</tr>


<tr><td colspan="6"><hr size="1"></td></tr>
<tr>
<td></td>
<td><?=$total_hours?></td>
<td>$<?=$total_sales?></td>
</tr>
</table>
</div>


  
<?php include "includes/footer.php"; ?>
