<?php include "includes/header.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php
$sql = "SELECT a.site_name, b.company_name, b.prospect_id from properties a, prospects b where 
a.prospect_id = b.prospect_id and a.property_id = '$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$company_name = stripslashes($record['company_name']);
$prospect_id = stripslashes($record['prospect_id']);
?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<?php include "includes/property_nav.php"; ?>
<br>
<a href="calendar_add.php?property_id=<?=$property_id?>">Add</a>
</td>
</tr>
</table>
<div class="breadcrumb">
<a href="company_details.php?prospect_id=<?=$prospect_id?>" class="breadcrumb"><?=$company_name?></a> > 
<a href="property_details.php?property_id=<?=$property_id?>" class="breadcrumb"><?=$site_name?></a> > Calendar</div>
<table class="main" width="100%">
<?php

$olddate = "";

$sql = "SELECT a.act_id, a.property_id, a.user_id, a.event, a.regarding, a.complete, a.contact, a.prospect_id, 
date_format(a.date, \"%a, %b %d, %Y\") as datepretty, date_format(a.date, \"%r\") as timepretty, a.priority, 
date_format(a.date, \"%Y%m%d\") as datesort  
from activities a
where a.property_id='$property_id'  and complete=0  and a.display=1 order by datesort, priority";
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
	default:{
	  $image = "todo.jpg";
	  break;
	}
  }
  
    ?>
	<tr>
	<td valign="top">
	<a href="calendar_details.php?act_id=<?=$record['act_id']?>">
	<img src="images/<?=$image?>" border="0">
	</a>
	</td>
	<td valign="top">
	<a href="calendar_details.php?act_id=<?=$record['act_id']?>"><?=$record['timepretty']?><br>
	<?=stripslashes($record['contact'])?> - <?=stripslashes($record['regarding'])?></a>
	</td>
	</tr>
	<?php
}
?>
</table>
	
<?php include "includes/footer.php"; ?>