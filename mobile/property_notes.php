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

$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $fullname = stripslashes($record['fullname']);
  $user_id = $record['user_id'];
  $userlist[$user_id] = $fullname;
}
?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<?php include "includes/property_nav.php"; ?>
<br>
<a href="property_notes_add.php?property_id=<?=$property_id?>">Add</a>
</td>
</tr>
</table>
<div class="breadcrumb">
<a href="company_details.php?prospect_id=<?=$prospect_id?>" class="breadcrumb"><?=$company_name?></a> > 
<a href="property_details.php?property_id=<?=$property_id?>" class="breadcrumb"><?=$site_name?></a> > Notes</div>

<table width="100%" class="main">
<?php
$sql = "SELECT *, date_format(date, \"%b %d, %Y\") as datepretty from notes 
where 1=1 and display=1 and property_id='$property_id' order by date desc, note_id desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
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
	  $image = "note.jpg";
	  break;
	}
  }
  
  switch($record['result']){
    case "Attempted":{
	  $border = " border='1' style='border-color:#FF0000;'";
	  break;
	}
	case "Completed":{
	  $border = " border='1' style='border-color:#FFFF00;'";
	  break;
	}
	case "Objective Met":{
	  $border = " border='1' style='border-color:#00FF00;'";
	  break;
	}
  }
  if($record['event']=="Note") $border = "";
  $user_id = $record['user_id'];

    ?>
	<tr>
	<td valign="top">
	<img src="images/<?=$image?>"<?=$border?>>
	</td>
	<td valign="top" style="padding-bottom:8px;">
	<?=$record['datepretty']?> [<?=$userlist[$user_id]?>]<br>
	<?=stripslashes($record['contact'])?> - <?=stripslashes($record['regarding'])?> <?=stripslashes(nl2br($record['note']))?>
	</td>
	</tr>
	<?php
}
?>
</table>

<?php include "includes/footer.php"; ?>