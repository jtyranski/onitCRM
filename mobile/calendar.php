<?php include "includes/header.php"; ?>
<?php

$order_by = "datesort, a.prospect_id, priority";
$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "thisweek";

if($SESSION_USER_LEVEL=="Manager") $user_id = $_GET['user_id'];
if($user_id=="") $user_id = $SESSION_USER_ID;

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


$cal_daterange = $daterange;
$cal_daterange = str_replace("XXX", "a.date", $cal_daterange);

?>

<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">

<form action="<?=$_SERVER['SCRIPT_NAME']?>" name="form1" method="get">
<select name="searchby" onchange="document.form1.submit()">
<option value="todate"<?php if($searchby=="todate") echo " selected";?>>All</option>
<option value="today"<?php if($searchby=="today") echo " selected";?>>Today</option>
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected";?>>This Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected";?>>This Month</option>
</select>
<?php if($SESSION_USER_LEVEL=="Manager"){?>
<br>
<select name="user_id" onchange="document.form1.submit()">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<?php } ?>
</form>
</td>
</tr>
<tr>
<td colspan="2"><a href="calendar_add_personal.php">Add Personal Item to Core Calendar</a></td>
</tr>
</table>
<table class="main" width="100%">
<?php
$olddate = "";

$sql = "SELECT a.act_id, a.property_id, a.user_id, a.event, a.regarding, a.complete, a.contact, a.prospect_id, 
date_format(a.date, \"%a, %b %d, %Y\") as datepretty, date_format(a.date, \"%r\") as timepretty, a.priority, 
date_format(a.date, \"%Y%m%d\") as datesort, 
b.site_name from activities a left join properties b
 on a.property_id=b.property_id where a.user_id='$user_id'  and complete=0  and a.display=1 
 and $cal_daterange
 order by $order_by";
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
  switch($record['event']){
    case "Contact":{
	  $image = "contact.jpg";
	  break;
	}
	case "Meeting":{
	  $image = "meeting.jpg";
	  break;
	}
	case "Conference Call":{
	  $image = "conferencecall.jpg";
	  break;
	}
	case "To Do":{
	  $image = "todo.jpg";
	  break;
	}
	default:{
	  $image = "meeting.jpg";
	  break;
	}
  }
  


    $sql = "SELECT company_name from prospects where prospect_id='" . $record['prospect_id'] . "'";
	$company_name = stripslashes(getsingleresult($sql));
	if(strlen($company_name) > 20) $company_name = substr($company_name, 0, 25);
    ?>
	<tr>
	<td valign="top">
	<a href="calendar_details.php?act_id=<?=$record['act_id']?>"&personal=<?=$personal?>>
	<img src="images/<?=$image?>" border="0">
	</a>
	</td>
	<td valign="top">
	<a href="calendar_details.php?act_id=<?=$record['act_id']?>&personal=<?=$personal?>" class="large"><?=$company_name?></a><br>
	<a href="calendar_details.php?act_id=<?=$record['act_id']?>&personal=<?=$personal?>"><?=stripslashes($record['event'])?></a><br>
	<a href="calendar_details.php?act_id=<?=$record['act_id']?>&personal=<?=$personal?>"><?=stripslashes($record['regarding'])?></a>
	</td>
	</tr>
	<?php
}
?>
</table>
	
<?php include "includes/footer.php"; ?>