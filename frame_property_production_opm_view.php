<?php
include "includes/header_white.php";
$property_id = go_escape_string($_GET['property_id']);
$opm_id = go_escape_string($_GET['opm_id']);
$opm_entry_id = go_escape_string($_GET['opm_entry_id']);

$sql = "SELECT opm_id, opm_entry_id, removed, replaced, comments, date_format(opm_date, \"%m/%d/%Y\") as opm_date, sent, date_format(last_sent_date, \"%m/%d/%Y %r\") as last_sent_date, 
date_format(opm_date, \"%Y-%m-%d\") as opm_date_raw from opm_entry where 
opm_entry_id='$opm_entry_id'";
$result_main = executequery($sql);
while($record_main = go_fetch_array($result_main)){
  $sent = $record_main['sent'];
  $last_sent_date = $record_main['last_sent_date'];
  include "opm_email_guts.php";
}
?>

<div class="main" style="position:relative;">
<div style="float:left;">
<a href="frame_property_production.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>">back</a>
</div>

<div style="float:left; padding-left:30px;">
<form action="frame_property_production_opm_view_send.php" method="post">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="opm_id" value="<?=$opm_id?>">
<input type="hidden" name="opm_entry_id" value="<?=$opm_entry_id?>">
This has been sent <?=$sent?> time(s).  
<?php if($sent ==0){ ?>
All unsent entries are scheduled to send at 6:00 PM Central.
<?php } else { ?>
It was last sent on <?=$last_sent_date?>
<?php } ?>
<br>
Would you like to send it now? 
<input type="submit" name="submit1" value="Send Now">
</form>
</div>
</div>
<div style="clear:both;"></div>
<br>

<?php
echo $body;
?>