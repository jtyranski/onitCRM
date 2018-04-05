<?php include "includes/header.php"; ?>
<?php
$sd_user_id = $_GET['sd_user_id'];
if($sd_user_id == "") $sd_user_id = $SESSION_USER_ID;

$sql = "SELECT user_id from users where user_id='$sd_user_id' and servicemen=1 and enabled=1 and master_id='" . $SESSION_MASTER_ID . "'";
$test = getsingleresult($sql);
if($test=="") $sd_user_id = 0;

if($sd_user_id == 0){
  $user_clause = " 1=1 ";
}
else {
  $user_clause = " (a.servicemen_id='$sd_user_id' or a.servicemen_id2='$sd_user_id') ";
}

$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "todate";

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


$sd_daterange = $daterange;
$sd_daterange = str_replace("XXX", "a.dispatch_date", $sd_daterange);
?>

<form name="form1" action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<select name="sd_user_id" onchange="document.form1.submit()">
<option value="0"<?php if($sd_user_id==0) echo " selected";?>>All</option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where servicemen=1 and enabled=1 and master_id='" . $SESSION_MASTER_ID . "' order by user_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($sd_user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<br>
Dispatched:
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
$sql = "SELECT a.leak_id, a.status, b.site_name, date_format(a.dispatch_date, \"%a, %b %d, %Y\") as datepretty 
from am_leakcheck a, properties b, prospects c
where a.property_id=b.property_id
and a.prospect_id = c.prospect_id
and c.master_id='" . $SESSION_MASTER_ID . "' 
and a.mobile_display=1 
and a.demo = 0
and a.servicemen_id != 0
and (a.status='Dispatched' or a.status='Arrival ETA' or a.status='In Progress')
and $sd_daterange
and $user_clause
order by a.dispatch_date";
echo "<!-- $sql -->\n";
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
  
  $color = "red";
  if($record['status']=="Arrival ETA") $color = "orange";
  if($record['status']=="In Progress") $color = "yellow";

  

	$site_name = stripslashes($record['site_name']);
	if(strlen($site_name) > 30) $site_name = substr($site_name, 0, 20);
    ?>
	<tr>
	<td valign="top">
	<a href="service_dispatch_details.php?leak_id=<?=$record['leak_id']?>" class="large"><?=$site_name?></a><br>
	<font color="<?=$color?>">Service Dispatch - <?=$record['status']?></font>
	</td>
	</tr>
	<?php
}
?>
</table>
	
<?php include "includes/footer.php"; ?>