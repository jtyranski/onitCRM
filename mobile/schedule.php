<?php include "includes/header.php"; ?>
<?php
$sched_user_id = $_GET['sched_user_id'];
if($sched_user_id == "") $sched_user_id = $SESSION_USER_ID;



if($sched_user_id == 0){
  $user_clause = " 1=1 ";
}
else {
  $user_clause = " a.ro_user_id='$sched_user_id' ";
}

$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "thisweek";

$wd = date('w') ;
$daterange = "1=1";
$forceblack = 0;
// this week
switch($searchby){
  case "todate":{
    $daterange = "1=1";
	break;
  }
  case "today":{
    $daterange = "XXX like '" . date("Y") . "-" . date("m") . "-" . date("d") . "%'";
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
	break;
  }
  
  case "lastmonth":{
    $d = date("j");
    $foo = 0;
    if($d==30 || $d==31) $foo = 3;
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d") - $foo,   date("Y"));
	$timesearch = date("Y-m", $lastmonth);
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  case "ytd":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	break;
  }
  
  default:{
    $daterange = "XXX like '$searchby%'";
	break;
  }
  
} // end switch of searchby


$sched_daterange = $daterange;
$sched_daterange = str_replace("XXX", "b.date", $sched_daterange);
?>

<form name="form1" action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<select name="sched_user_id" onchange="document.form1.submit()">
<option value="0"<?php if($sched_user_id==0) echo " selected";?>>All</option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' and enabled=1 order by user_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($sched_user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<br>
<select name="searchby" onchange="document.form1.submit()">
<option value="todate"<?php if($searchby=="todate") echo " selected";?>>All</option>
<option value="today"<?php if($searchby=="today") echo " selected";?>>Today</option>
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected";?>>This Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected";?>>This Month</option>
</select>
</td>
</tr>
</table>
</form>
<table class="main" width="100%">
<?php
$olddate = "";

// for now, don't include "Other", a.what = "O"
// for now, only show yesterday date and onward
//and b.date >= date_sub(now(), interval 1 day) 
$sql = "SELECT a.event_id, a.value, date_format(b.date, \"%a, %b %d, %Y\") as datepretty, c.name, d.company_name, e.roof_size, f.firstname, 
c.color, a.what, concat(f.firstname, ' ', f.lastname) as fullname 
from supercali_events a, supercali_dates b, supercali_categories c, prospects d, properties e, users f
where a.event_id = b.event_id and a.category_id=c.category_id and a.prospect_id = d.prospect_id and a.property_id=e.property_id 
and a.ro_user_id = f.user_id 
and a.what != 'O' 
and $sched_daterange
and c.calendar_type='Sales' 
and a.complete=0
and $user_clause
order by b.date";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($olddate != $record['datepretty']){
    ?>
	<tr>
	<td colspan="2" align="center" bgcolor="#666666" class="main_large">
	<?=$record['datepretty']?>
	</td>
	</tr>
	<?php
	$olddate = $record['datepretty'];
  }

  

	$company_name = stripslashes($record['company_name']);
	if(strlen($company_name) > 30) $company_name = substr($company_name, 0, 20);
    ?>
	<tr>
	<td valign="top">
	<a href="schedule_details.php?event_id=<?=$record['event_id']?>" class="large"><?=$company_name?></a><br>
	<font color="<?=$record['color']?>"><?=$record['name']?></font> - <?=stripslashes($record['fullname'])?><br>
	<?=$record['roof_size']?> SQS 
	<?php if($record['what']=="BP") { ?>
	  $<?=number_format($record['value'], 2)?>
	<?php } ?>
	</td>
	</tr>
	<?php
}
?>
</table>
	
<?php include "includes/footer.php"; ?>