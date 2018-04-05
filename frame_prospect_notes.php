<?php include "includes/functions.php"; ?>

<?php $prospect_id = $_GET['prospect_id']; 
if($prospect_id=="" || $prospect_id==0){
  echo "There was an error uploading your file.  The maximum file size is 20MB.";
  exit;
}
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">


<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<div class="main">
<?php
$user_id = $SESSION_USER_ID;

?>

<div align="right">
<a href="frame_addnote.php?prospect_id=<?=$prospect_id?>&note_id=new">Add New Note</a>
</div>


<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Event</strong></td>
<td><strong>Date</strong></td>
<td><strong>For</strong></td>
<td><strong>Property</strong></td>
<td><strong>Regarding</strong></td>
<td><strong>Notes</strong></td>
<td></td>
</tr>
<?php

  
$sql = "SELECT *, date_format(date, \"%m/%d/%y %h:%i\") as datepretty from notes 
where 1=1 and display=1 and prospect_id='$prospect_id' order by date desc, note_id desc";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $attachment = stripslashes($record['attachment']);
  $counter++;
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='" . $record['user_id'] . "'";
  $result2 = executequery($sql);
  $record2 = go_fetch_array($result2);
  $fullname = stripslashes($record2['fullname']);
  
  $property_id = $record['property_id'];
  if($property_id){
    $sql = "SELECT site_name from properties where property_id='$property_id'";
	$site_name = getsingleresult($sql);
	$site_url = "<a href='view_property.php?property_id=$property_id' target='_top'>$site_name</a>";
  }
  else {
    $site_url = "Company";
  }
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$record['event']?></td>
  <td valign="top"><?=$record['datepretty']?></td>
  <td valign="top"><?=$fullname?></td>
  <td valign="top"><?=$site_url?></td>
  <td valign="top"><?=stripslashes($record['regarding'])?></td>
  <td valign="top">
  <?php if($record['note_large'] != ""){ ?>
  <a href="#" onClick="window.open('note_pop.php?note_id=<?=$record['note_id']?>','','menubar=no,width=430,height=360,toolbar=no,scrollbars=yes')">
  <?=stripslashes(nl2br($record['note']))?>
  </a>
  <?php } else { ?>
  <?=stripslashes(nl2br($record['note']))?>
  <?php } ?>
  <?php if($attachment != ""){ ?>
  <br>
  <a href="<?=$UPLOAD?>attachments/<?=$attachment?>" target="_blank">Attachment</a>
  <?php } ?>
  </td>
  <td valign="top">
  <?php
  if($record['user_id'] == $user_id || $SESSION_ISADMIN==1){ ?>
  <a href="frame_addnote.php?note_id=<?=$record['note_id']?>&prospect_id=<?=$prospect_id?>">edit</a> - 
  <a href="frame_addnote_delete.php?note_id=<?=$record['note_id']?>&prospect_id=<?=$prospect_id?>">delete</a>
  <?php } 
  
  ?>
  </td>
  </tr>
  <?php
}
?>
</table>
</div>
</body>
</html>