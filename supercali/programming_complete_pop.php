<?php
require_once "../includes/functions.php";

$event_id = $_GET['event_id'];

if($event_id != "new"){
  $sql = "SELECT title, description, ro_user_id, attachment, date_format(complete_date, \"%m/%d/%Y\") as complete_pretty, publish_type from supercali_events where event_id='$event_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $title = stripslashes($record['title']);
  $description = stripslashes($record['description']);
  $ro_user_id = stripslashes($record['ro_user_id']);
  $attachment = stripslashes($record['attachment']);
  $complete_pretty = stripslashes($record['complete_pretty']);
  $publish_type = stripslashes($record['publish_type']);
  
  $description = go_reg_replace("\n", "ZZZZ", $description);
  
	
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
else{
  $publish_type="core";
  $complete_pretty = date("m/d/Y");
}

$title = go_reg_replace("\"", "&quot;", $title);
$title = go_reg_replace("\'", "&#39;", $title);

$description = go_reg_replace("\"", "&quot;", $description);
$description = go_reg_replace("\'", "&#39;", $description);





ob_start();

?>
<input type="hidden" name="event_id" value="<?=$event_id?>">
Project Name: <input type="text" name="title" value="<?=$title?>" size="60">
<br>
Description:<br>
<textarea name="description" rows="10" cols="60"><?=$description?></textarea>
<br>
<div style="position:relative;">
<div style="float:left;">
<table>
<tr>
<td>Start Date:</td>
<td><?=$startdate?></td>
</tr>
<tr>
<td>Complete Date:</td>
<td><input size="10" type="text" name="enddate" value="<?=$complete_pretty?>"> 
<img src="../images/calendar.gif" onClick="KW_doCalendar('enddate',0)" align="absmiddle"></td>
</tr>
</table>
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
<input type="radio" name="publish_type" value="core"<?php if($publish_type=="core") echo " checked";?>>Core &nbsp;
<input type="radio" name="publish_type" value="fcs"<?php if($publish_type=="fcs") echo " checked";?>>FCS Connect
<br>
<input type="submit" name="submit1" value="Publish"> &nbsp;
<input type="submit" name="submit1" value="Update Description"> &nbsp;
<input type="submit" name="submit1" value="NOT COMPLETE">

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