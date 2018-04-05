<?php include "includes/functions.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php
$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">


<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<div class="main">
<?php
$user_id = $SESSION_USER_ID;
?>

<div align="right">
<a href="activity_edit.php?act_id=new&prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>" target="_parent">Add new activity</a>
</div>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Event</strong></td>
<td><strong>Date</strong></td>
<td><strong>Time</strong></td>
<td><strong>For</strong></td>
<td><strong>Regarding</strong></td>
<td><strong>Scheduled By</strong></td>
<td></td>
</tr>
<?php

  
$sql = "SELECT *, date_format(date, \"%m/%d/%y\") as datepretty, date_format(date, \"%l:%i %p\") as timepretty from activities 
where 1=1 and display=1 and property_id='$property_id' order by complete, date asc";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  if($record['user_id']==0) continue; // method for "approval" of inspection acts.  user_id won't be assigned until it's approved
  $counter++;
  $regarding = stripslashes($record['regarding']);
  if($record['regarding_large'] != "" || $record['attachment'] != ""){ 
    $regarding = "<a href=\"#\" onClick=\"window.open('regarding_pop.php?act_id=" . $record['act_id'] . "','','menubar=no,width=430,height=360,toolbar=no,scrollbars=yes')\">";
	$regarding .= stripslashes($record['regarding']) . "</a>";
  }
  if($record['demo_master_id'] != 0 && $SESSION_MASTER_ID==1){
    $regarding = "<a href='fcs_contractor_login_as.php?master_id=" . $record['demo_master_id'] . "' target='_blank'>Core Demo Login</a>";
  }
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='" . $record['user_id'] . "'";
  $result2 = executequery($sql);
  $record2 = go_fetch_array($result2);
  $fullname = stripslashes($record2['fullname']);
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='" . $record['scheduled_by'] . "'";
  $result3 = executequery($sql);
  $record3 = go_fetch_array($result3);
  $scheduled_by = stripslashes($record3['fullname']);
  
  $event = $record['event'];
  if($event=="FCS Meetings") $event = $UM;
  if($event=="Inspection") $event = $I;
  if($event=="Risk Report Presentation") $event = $RRP;
  if($event=="Bid Presentation") $event = $BP;
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?><?php if($record['complete']) echo " style=\"text-decoration: line-through;\" class=\"strike\"";?>>
  <td><?=$event?></td>
  <td><?=$record['datepretty']?></td>
  <td><?=$record['timepretty']?></td>

  <td><?=$fullname?></td>
  <td>
  <?=$regarding?>
  </td>
  <td><?=$scheduled_by?></td>
  <td>
  <?php if($record['complete'] != 1) { ?>
  <?php if($record['user_id'] == $user_id || $SESSION_USER_LEVEL=="Manager"){ ?>
  <a href="activity_edit.php?act_id=<?=$record['act_id']?>&prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>" target="_parent">edit</a> - 
  <a href="activity_complete.php?act_id=<?=$record['act_id']?>&prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>" target="_parent">complete</a> - 
  <a href="activity_delete.php?act_id=<?=$record['act_id']?>&property_id=<?=$property_id?>" target="_parent">delete</a>
  <?php } ?>
  <?php } ?>
  </td>
  </tr>
  <?php
}
?>
</table>
</div>
</body>
</html>
