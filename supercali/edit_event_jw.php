<?php
require_once "../includes/functions.php";
$id = $_GET['id'];

if($id != "new"){
  $sql = "SELECT act_id from supercali_events where event_id='$id'";
  $act_id = getsingleresult($sql);

  $sql = "SELECT date_format(date, \"%m/%d/%Y\") as datepretty,
  date_format(date, \"%h\") as hourpretty, 
  date_format(date, \"%i\") as minutepretty, 
  date_format(date, \"%p\") as ampm, regarding, user_id, 
  date_format(span_date, \"%m/%d/%Y\") as spanpretty, repeat_type from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $datepretty = stripslashes($record['datepretty']);
  $hourpretty = stripslashes($record['hourpretty']);
  $minutepretty = stripslashes($record['minutepretty']);
  $ampm = stripslashes($record['ampm']);

  $regarding = stripslashes($record['regarding']);
  
  $repeat_type = $record['repeat_type'];
  $spanpretty = $record['spanpretty'];
  $user_id = $record['user_id'];
}
else {
  $act_id = "new";
  $datepretty = date("m/d/Y");
  $hourpretty = "00";
  $minutepretty = "00";
  $ampm = "AM";
  $user_id = $_SESSION['user_id'];
}

?>
<script src="../includes/calendar.js"></script>
<script>
function check_repeat_type(x){
	y=x.value;
	if(y=="Span"){
		document.getElementById('span_date').style.display="";
	}
	else{
		document.getElementById('span_date').style.display="none";
	}
}
</script>
<form action="edit_event_jw_action.php" method="post">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="act_id" value="<?=$act_id?>">
<table class="main" id="regular">
<tr>
<td align="right">For</td>
<td>
<select name="user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Date</td>
<td>
<input size="10" type="text" name="datepretty" value="<?=$datepretty?>"> 
<img src="../images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
<input type="hidden" name="hourpretty" value="01">
<input type="hidden" name="minutepretty" value="00">
<input type="hidden" name="ampm" value="PM">
</td>
</tr>
<tr>
<td colspan="2">
<input type="hidden" name="old_repeat_type" value="<?=$repeat_type?>">
Repeat frequency: 
<select name="repeat_type" onchange="check_repeat_type(this)">
<option value="Never"<?php if($repeat_type=="Never") echo " selected";?>>Never</option>
<option value="Daily"<?php if($repeat_type=="Daily") echo " selected";?>>Daily</option>
<option value="Weekly"<?php if($repeat_type=="Weekly") echo " selected";?>>Weekly</option>
<option value="Monthly"<?php if($repeat_type=="Monthly") echo " selected";?>>Monthly</option>
<option value="Yearly"<?php if($repeat_type=="Yearly") echo " selected";?>>Yearly</option>
<option value="Span"<?php if($repeat_type=="Span") echo " selected";?>>Span</option>
</select>
&nbsp; 
<span id="span_date" <?php if($repeat_type != "Span") echo "style=\"display:none;\"";?>>
Until: 
<input size="10" type="text" name="spanpretty" value="<?=$spanpretty?>"> 
<img src="../images/calendar.gif" onClick="KW_doCalendar('spanpretty',0)" align="absmiddle">
</span>
</td>
</tr>

<tr>
<td align="right">Regarding</td>
<td><input type="text" name="regarding" value="<?=$regarding?>" size="40" maxlength="250"></td>
</tr>
</table>
<input type="submit" name="submit1" value="Submit">
</form>