<?php
include "includes/header_white.php";
$property_id = go_escape_string($_GET['property_id']);
$opm_id = go_escape_string($_GET['opm_id']);
$opm_entry_id = go_escape_string($_GET['opm_entry_id']);

if($opm_entry_id != "new"){
  $sql = "SELECT date_format(opm_date, \"%m/%d/%Y\") as opm_date_pretty, removed, replaced, comments from opm_entry where opm_entry_id='$opm_entry_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $opm_date_pretty = stripslashes($record['opm_date_pretty']);
  $removed = stripslashes($record['removed']);
  $replaced = stripslashes($record['replaced']);
  $comments = stripslashes($record['comments']);
}
?>
<script src="includes/calendar.js"></script>
<script>
function checkform(f){
  
  if(f.opm_date_pretty.value==""){ errmsg += "Please enter date of work.\n";}
  if(f.removed.value==""){ errmsg += "Please enter amount removed.\n";}
  if(f.replaced.value==""){ errmsg += "Please enter amount replaced.\n";}
  
  if(errmsg == ""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>

<div class="main">
<a href="frame_property_production.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>">Return to Production</a><br>
<form action="frame_property_production_opm_action.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="opm_id" value="<?=$opm_id?>">
<input type="hidden" name="opm_entry_id" value="<?=$opm_entry_id?>">
<table class="main">
<tr>
<td align="right"><strong>Date of Work</strong></td>
<td>
<input type="text" name="opm_date_pretty" value="<?=$opm_date_pretty?>" size="10">
<img src="images/calendar.gif" onClick="KW_doCalendar('opm_date_pretty',0)" align="absmiddle">
</td>
</tr>
<tr>
<td align="right"><strong>Amount Removed</strong></td>
<td><input type="text" name="removed" value="<?=$removed?>"></td>
</tr>
<tr>
<td align="right"><strong>Amount Replaced</strong></td>
<td><input type="text" name="replaced" value="<?=$replaced?>"></td>
</tr>
<tr>
<td align="right" valign="top"><strong>Comments</strong></td>
<td>
<textarea name="comments" rows="6" cols="80"><?=$comments?></textarea>
</td>
</tr>
</table>
<input type="submit" name="submit1" value="Continue to Photos">
</form>
</div>