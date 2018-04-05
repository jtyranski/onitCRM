<?php
require_once "../includes/functions.php";

$sql = "SELECT programming_schedule from users where user_id='" . $SESSION_USER_ID . "'";
$ps = getsingleresult($sql);

$event_id = $_GET['event_id'];

if($event_id != "new"){
  $sql = "SELECT title, description, ro_user_id, attachment, created_by, programming_type, urgency, stage, 
  date_format(created_date, \"%m/%d/%Y\") as created_date, date_format(stage_date, \"%m/%d/%y\") as stage_date from supercali_events where event_id='$event_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $title = stripslashes($record['title']);
  $description = stripslashes($record['description']);
  $ro_user_id = stripslashes($record['ro_user_id']);
  $old_ro_user_id = $ro_user_id;
  $attachment = stripslashes($record['attachment']);
  $created_by = stripslashes($record['created_by']);
  $programming_type = stripslashes($record['programming_type']);
  $urgency = stripslashes($record['urgency']);
  $stage = stripslashes($record['stage']);
  $created_date = stripslashes($record['created_date']);
  $stage_date = stripslashes($record['stage_date']);
  
  
  $description = go_reg_replace("\n", "ZZZZ", $description);
  
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$created_by'";
  $created_by_name = stripslashes(getsingleresult($sql));
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$ro_user_id'";
  $assign_to_name = stripslashes(getsingleresult($sql));
  
  $sql = "SELECT date_format(date, \"%m/%d/%Y\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$event_id' order by date desc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $enddate = $record['datepretty'];
	$endmonth = $record['month'];
	$endday = $record['day'];
	$endyear = $record['year'];
	
	$sql = "SELECT date_format(date, \"%m/%d/%Y\") as datepretty, 
	date_format(date, \"%m\") as month, date_format(date, \"%d\") as day, date_format(date, \"%Y\") as year 
	from supercali_dates where event_id='$event_id' order by date asc limit 1";
	$result = executequery($sql);
	$record = go_fetch_array($result);
    $startdate = $record['datepretty'];
	$startmonth = $record['month'];
	$startday = $record['day'];
	$startyear = $record['year'];
}
else {
  $old_ro_user_id = 0;
  $urgency = 5;
}

$title = go_reg_replace("\"", "&quot;", $title);
$title = go_reg_replace("\'", "&#39;", $title);

$description = go_reg_replace("\"", "&quot;", $description);
$description = go_reg_replace("\'", "&#39;", $description);



$select = "";
$select .= "<option value='0'";
if($ro_user_id==0) $select .= " selected";
$select .= ">In Queue</option>";


$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id=1 and programming_schedule=1 and enabled=1 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $select .= "<option value='" . $record['user_id'] . "'";
  if($ro_user_id==$record['user_id']) $select .= " selected";
  $select .= ">" . stripslashes($record['fullname']) . "</option>";
}

ob_start();

?>
<input type="hidden" name="event_id" value="<?=$event_id?>">
<input type="hidden" name="old_ro_user_id" value="<?=$old_ro_user_id?>">

<div style="width:100%; position:relative;">
<div style="width:50%; float:left;">
<?php if($event_id != "new"){?>
<strong>Created By:</strong> <?=$created_by_name?><br><br>
<?php } ?>
<?php if($ps){ ?>
<strong>Assign To:</strong> 
<select name="ro_user_id">
<?=$select?>
</select>
<?php } else { ?>
  <?php if($event_id=="new"){ ?>
  <input type="hidden" name="ro_user_id" value="0">
  <?php } else { ?>
  <input type="hidden" name="ro_user_id" value="<?=$ro_user_id?>">
  <strong>Assigned To:</strong> <?=$assign_to_name?>
  <?php } ?>
<?php } ?>
<br><br>
<strong>Urgency:</strong>
<select name="urgency">
<?php for($x=1;$x<=5;$x++){ ?>
<option value="<?=$x?>"<?php if($x==$urgency) echo " selected";?>><?=$x?></option>
<?php } ?>
</select>
</div>
<div style="width:50%; float:left;">
<?php if($event_id != "new"){?>
<strong>Created Date:</strong> <?=$created_date?><br><br>
<?php } ?>
<strong>Type:</strong>
<input type="radio" name="programming_type" value="F"<?php if($programming_type=="F") echo " checked";?><?php if($event_id=="new" || $ps==0) {?> onchange="programming_stage_change(this)"<?php } ?>>Fix &nbsp;
<input type="radio" name="programming_type" value="I"<?php if($programming_type=="I" || $programming_type=="") echo " checked";?><?php if($event_id=="new" || $ps==0) {?> onchange="programming_stage_change(this)"<?php } ?>>Improvement
<br><br>
<input type="hidden" name="stage_old" value="<?=$stage?>">
<?php if($ps){ ?>
<strong>Stage:</strong>
<select name="stage" id="stage">
<option value="Requested"<?php if($stage=="Requested" || $event_id=="new") echo " selected";?>>Requested</option>
<option value="Under Review"<?php if($stage=="Under Review") echo " selected";?>>Under Review</option>
<option value="Approved"<?php if($stage=="Approved") echo " selected";?>>Approved</option>
<option value="Conceptual"<?php if($stage=="Conceptual") echo " selected";?>>Conceptual</option>
<option value="Final Approved"<?php if($stage=="Final Approved") echo " selected";?>>Final Approved</option>
</select>
<?php if($event_id != "new"){ ?>(<?=$stage_date?>)<?php } ?>
<?php } else { ?>
  <?php if($event_id=="new"){ ?>
  <input type="hidden" name="stage" id="stage"<?php if($event_id=="new") echo " value=\"Requested\"";?>>
  <?php } else { ?>
  <strong>Stage:</strong>
  <input type="hidden" name="stage" id="stage" value="<?=$stage?>">
  <?=$stage?>
  (<?=$stage_date?>)
  <?php } ?>
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>
<br>
Project Name: <input type="text" name="title" value="<?=$title?>" size="60">
<br>
Description:<br>
<textarea name="description" rows="10" cols="60"><?=$description?></textarea>
<br>
<div style="position:relative;">
<div style="float:left;">
<?php if($ps && $stage=="Final Approved"){ ?>
<table>
<tr>
<td>Start Date:</td>
<td><input size="10" type="text" name="startdate" value="<?=$startdate?>"> 
<img src="../images/calendar.gif" onClick="KW_doCalendar('startdate',0)" align="absmiddle"></td>
</tr>
<tr>
<td>Complete Date:</td>
<td><input size="10" type="text" name="enddate" value="<?=$enddate?>"> 
<img src="../images/calendar.gif" onClick="KW_doCalendar('enddate',0)" align="absmiddle"></td>
</tr>
</table>
<?php } ?>
</div>
<div style="float:left; padding-left:10px;">
Attach: <input type="file" name="attachment">
<br>
<?php if($attachment != ""){ ?>
<a href="../uploaded_files/programming/<?=$attachment?>" target="_blank">View Attachment</a>
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>
<?php if($ps==1){ ?>
<input type="submit" name="submit1" value="Submit">
<?php } else { 
    if(($stage=="Requested" && $created_by==$SESSION_USER_ID) || $event_id=="new"){ ?>
	<input type="submit" name="submit1" value="Submit">
	<?php
	}
}
?>
<?php if($ro_user_id !=0 && $ps==1){ ?>
<input type="submit" name="submit1" value="Complete">
<input type="submit" name="submit1" value="Complete Dev">
<?php } ?>
<?php
$html = ob_get_contents();
  ob_end_clean();
  $html = jsclean($html);
?>

div = document.getElementById('pform');
div.innerHTML = '<?php echo $html; ?>';
foo = document.programmingform.description.value;
bar = foo.replace(/ZZZZ/g, "\n");
document.programmingform.description.value = bar;