<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php

$order_by = "datesort, a.prospect_id, priority";
$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "thisweek";

$user_id = $_GET['user_id'];
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
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
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

$personal_check = $_GET['personal_check'];
$personal_query = " 1=1 ";
if($personal_check==1) $personal_query = " a.prospect_id=0 ";

$complete_check = $_GET['complete_check'];
$complete_query = " a.complete=0 ";
if($complete_check==1) $complete_query = " 1=1 ";
?>

<table class="main" width="100%">
<tr>
<td valign="top">
<span style="font-size:16px; font-weight:bold;">Activity List</span><br>
<a href="supercali">Return to Month view</a>
</td>
<td align="right" valign="top">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" name="form1" method="get">
<input type="checkbox" name="personal_check" value="1"<?php if($personal_check==1) echo " checked";?> onchange="document.form1.submit()">Show Only Personal Activities
&nbsp; &nbsp; &nbsp;
<input type="checkbox" name="complete_check" value="1"<?php if($complete_check==1) echo " checked";?> onchange="document.form1.submit()">Show Completed Activities
&nbsp; &nbsp;

<?php if($SESSION_USER_LEVEL=="Manager"){ ?>
<select name="user_id" onchange="document.form1.submit()">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER
 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
&nbsp; &nbsp;
<?php } ?>
<select name="searchby" onchange="document.form1.submit()">
<option value="todate"<?php if($searchby=="todate") echo " selected";?>>All</option>
<option value="today"<?php if($searchby=="today") echo " selected";?>>Today</option>
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected";?>>This Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected";?>>This Month</option>
</select>
</form>
</td>
</tr>
</table>
<form action="calendar_activities_action.php" method="post">
<input type="hidden" name="searchby" value="<?=$searchby?>">
<input type="hidden" name="user_id" value="<?=$user_id?>">
<div class="main" style="padding-left:10px; width:98%;">
<?php

$olddate = "";

$sql = "SELECT a.act_id, a.property_id, a.user_id, a.event, a.regarding, a.complete, a.contact, a.prospect_id, 
date_format(a.date, \"%a, %b %d, %Y\") as datepretty, date_format(a.date, \"%l:%i %p\") as timepretty, a.priority, 
date_format(a.date, \"%Y%m%d\") as datesort, 
b.site_name from activities a left join properties b
 on a.property_id=b.property_id where a.user_id='$user_id'   and a.display=1 
 and $cal_daterange
 and $personal_query
 and $complete_query
 order by $order_by";
//echo "<!-- $sql -->\n";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($olddate != $record['datepretty']){
    ?>
	<div style="width:100%; border:1px solid black; height:25px; color:white; font-size:18px; background-color:#999999;">
	<?=$record['datepretty']?>
	</div>
	<?php
	$olddate = $record['datepretty'];
  }
  switch($record['event']){
    case "Contact":
	case "Conference Call":{
	  $image = "contact_icon_small.png";
	  break;
	}
	case "Meeting":
	case "Inspection":
	case "Production Meeting":
	case "Risk Report Presentation":
	case "Roof Report Presentation":
	case "FCS Meetings":
	case "Bid Presentation":{
	  $image = "meeting_icon_small.png";
	  break;
	}
	default:{
	  $image = "todo_icon_small.png";
	  break;
	}
  }
  $sql = "SELECT b.color from supercali_events a, supercali_categories b where a.category_id=b.category_id and a.act_id='" . $record['act_id'] . "'";
  $color = getsingleresult($sql);
  if($color=="") $color = "black";
  
  if($record['prospect_id'] == 0) { // personal
    $company_name = "Personal";
  }
  else {
    $sql = "SELECT company_name from prospects where prospect_id='" . $record['prospect_id'] . "'";
	$company_name = stripslashes(getsingleresult($sql));
  }
  if($record['complete']==1){
    $sql = "SELECT note from notes where act_id='" . $record['act_id'] . "'";
	$note = nl2br(stripslashes(getsingleresult($sql)));
  }
    ?>
	<div style="position:relative;">
	<?php if($SESSION_USER_LEVEL=="Manager"){?>
	<div style="float:left; padding-right:5px;">
	<input type="checkbox" name="act_id[]" value="<?=$record['act_id']?>">
	</div>
	<?php } ?>
	<div style="float:left; padding-right:10px;">
	<?php if($record['complete']==1){ ?>
	  <img src="images/<?=$image?>" border="0">
	<?php } else { ?>
	  <a href="calendar_details.php?act_id=<?=$record['act_id']?>">
	  <img src="images/<?=$image?>" border="0">
	  </a>
	<?php } ?>
	</div>
	<div style="float:left;<?php if($record['complete']==1) echo "  text-decoration:line-through;";?>">
	<?=$record['timepretty']?><br>
	<?php if($record['prospect_id'] != 0 && $record['complete']==0){ ?>
	<a href="view_company.php?prospect_id=<?=$record['prospect_id']?>" target="_parent">
	<?php } ?>
	<span style="font-size:18px; font-weight:bold;"><?=$company_name?></span>
	<?php if($record['prospect_id'] != 0 && $record['complete']==0){ ?>
	</a>
	<?php } ?>
	<br>
	<span style="font-size:14px; color:<?=$color?>;"><?=stripslashes($record['regarding'])?></span>
	<?php if($record['complete']==1){?>
	<br>Notes:<?=$note?>
	<?php } ?>
	</div>
	</div>
	<div style="clear:both;"></div>
	<?php

}
?>
<?php if($SESSION_USER_LEVEL=="Manager"){?>
Assign checked items to: 
<select name="new_user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "'
 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<br>
<input type="submit" name="submit1" value="Assign">
<br>
<input type="submit" name="submit1" value="Delete Checked">
<?php } ?>
</form>

</div>
	
<?php include "includes/footer.php"; ?>