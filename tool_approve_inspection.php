<?php include "includes/header_white.php"; ?>
<div class="main">
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
The following inspections have been assigned to you, and require your approval.
<br><br>
<form action="tool_approve_inspection_action.php" method="post">
<table class="main" cellpadding="4" cellspacing="0">
<?php
$sql = "SELECT a.act_id, a.property_id, b.site_name, b.address, b.city, b.state, b.zip, date_format(a.date, \"%m/%d/%Y %r\") as datepretty 
from activities a, properties b 
where a.property_id=b.property_id and a.user_id='" . $SESSION_USER_ID . "' and a.needs_approval=1 order by a.date";
$result = executequery($sql);
$counter = 0;
while($record=go_fetch_array($result)){
  $counter++;
  ?>
  <tr<?php if($counter%2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><input type="checkbox" name="act_ids[]" value="<?=$record['act_id']?>"></td>
  <td valign="top"><?=$record['datepretty']?></td>
  <td valign="top"><a href="view_property.php?property_id=<?=$record['property_id']?>" target="_blank"><?=stripslashes($record['site_name'])?></a></td>
  <td valign="top"><?=stripslashes($record['address'])?><br><?=stripslashes($record['city'])?>, <?=$record['state']?> <?=$record['zip']?></td>
  <td valign="top"><a href="tool_approve_inspection_decline.php?act_id=<?=$record['act_id']?>">Decline</a></td>
  </tr>
  <?php
}
?>
</table>
<?php if($counter > 0){ ?>
<input type="submit" name="submit1" value="Approve Checked">
<?php } ?>
</form>

</div>
  