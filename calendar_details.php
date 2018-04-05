<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$act_id = $_GET['act_id'];

$sql = "SELECT prospect_id from activities where act_id='$act_id'";
$prospect_id = getsingleresult($sql);
if($prospect_id > 0){
  $sql = "SELECT a.event, a.regarding, date_format(a.date, \"%a, %b %d, %Y - %r\") as datepretty, a.property_id, 
  b.site_name, c.company_name, a.contact, a.priority, a.scheduled_by, a.tm
  from activities a, properties b, prospects c 
  where a.property_id=b.property_id and a.prospect_id=c.prospect_id and a.act_id='$act_id'";
}
else {
  $sql = "SELECT a.event, a.regarding, date_format(a.date, \"%a, %b %d, %Y - %r\") as datepretty, a.property_id, 
  a.contact, a.priority, a.scheduled_by, a.tm
  from activities a
  where a.act_id='$act_id'";
}
$result = executequery($sql);
$record = go_fetch_array($result);
$event = stripslashes($record['event']);
$regarding = stripslashes($record['regarding']);
$datepretty = stripslashes($record['datepretty']);
$site_name = stripslashes($record['site_name']);
$company_name = stripslashes($record['company_name']);
$contact = stripslashes($record['contact']);
$property_id = stripslashes($record['property_id']);
$priority = stripslashes($record['priority']);
$scheduled_by = stripslashes($record['scheduled_by']);
$tm = stripslashes($record['tm']);

if($scheduled_by = 0){
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$scheduled_by'";
  $scheduled_by_name = stripslashes(getsingleresult($sql));
}


$sql = "SELECT b.color from supercali_events a, supercali_categories b where a.category_id=b.category_id and a.act_id='" . $act_id . "'";
  $color = getsingleresult($sql);
  if($color=="") $color = "black";

switch($event){
    case "Contact":
	case "Conference Call":{
	  $image = "contact_icon.png";
	  break;
	}
	case "Meeting":
	case "Inspection":
	case "Production Meeting":
	case "Risk Report Presentation":
	case "Roof Report Presentation":
	case "FCS Meetings":
	case "Bid Presentation":{
	  $image = "meeting_icon.png";
	  break;
	}
	default:{
	  $image = "todo_icon.png";
	  break;
	}
}

?>
<span style="font-size:16px;" class="main">Calendar</span><br>
<a href="calendar_activities.php">Return to Activities</a><br>
<a href="supercali">Return to Month view</a>
<br><br>

<div style="position:relative;" class="main">
<div style="float:left;"><img src="images/<?=$image?>"></div>
<div class="main_large" style="float:left;">
<?=$company_name?><br><?=$site_name?>
</div>
</div>
<div style="clear:both;"></div>
<br>
<div class="main">
<div style="color:<?=$color?>; font-size:14px;">
<?=$event?> - <?=$datepretty?>
</div>
<br>
<?=$contact?>
<br>
<br>
<?php if($regarding != "") echo $regarding . "<br><br>"; ?>
<?php if($scheduled_by_name != "") echo "Scheduled by: $scheduled_by_name<br>";?>


<br><br>

<table class="main" width="300">
<tr>
<td width="33%" align="center">

<a href="calendar_details_edit.php?act_id=<?=$act_id?>">Edit</a>

</td>
<td width="33%" align="center">
<a href="calendar_details_complete.php?act_id=<?=$act_id?>">Complete</a>
</td>
<td width="33%" align="center">
<a href="calendar_details_delete.php?act_id=<?=$act_id?>">Delete</a>
</td>
</tr>
</table>

