<?php include "includes/header_white.php"; ?>
<script>
function OnOff(message_id){
  document.location.href="homepage_news_onoff.php?message_id=" + message_id;
}

function DelItem(message_id){
  cf = confirm("Are you sure you want to delete this news item?");
  if(cf){
    document.location.href="homepage_news_delete.php?message_id=" + message_id;
  }
}
</script>

<div class="main">
<a href="homepage_news_edit.php?message_id=new">Add new item</a><br>

<table class="main" cellpadding="4" cellspacing="0">
<tr>
<td>Date</td>
<td>News Item</td>
<td>Edit</td>
<td>On/Off</td>
<td>Delete</td>
</tr>
<?php
$counter = 0;
$sql = "SELECT *, date_format(message_date, \"%m/%d/%Y\") as datepretty from homepage_messages order by message_id desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $style="";
  $onoff = "On";
  if($record['display']==0) {
    $style="style='text-decoration:line-through;'";
	$onoff = "Off";
  }
  $counter++;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top" <?=$style?>><?=$record['datepretty']?></td>
  <td valign="top" <?=$style?>><?=nl2br(stripslashes($record['message']))?></td>
  <td valign="top" <?=$style?>><a href="homepage_news_edit.php?message_id=<?=$record['message_id']?>">Edit</a></td>
  <td valign="top" <?=$style?>><a href="javascript:OnOff('<?=$record['message_id']?>')"><?=$onoff?></a></td>
  <td valign="top" <?=$style?>><a href="javascript:DelItem('<?=$record['message_id']?>')">Delete</a></td>
  </tr>
  <?php
}
?>
</table>
</div>
  