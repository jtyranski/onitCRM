<?php include "includes/functions.php"; ?>
<?php $prospect_id = $_GET['prospect_id']; ?>
<?php
$sql = "SELECT industry from prospects where prospect_id='$prospect_id'";
$industry = getsingleresult($sql);
?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script>
function loadview(x){
  viewtype = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?prospect_id=<?=$prospect_id?>&view=" + viewtype;
}
</script>

<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<div class="main">
<?php
$user_id = $SESSION_USER_ID;
?>

<div align="right">
<?php if($SESSION_MASTER_ID==1 || $industry==1){ ?>
<a href="activity_edit.php?act_id=new&prospect_id=<?=$prospect_id?>" target="_parent">Add new activity</a>
<?php } ?>
</div>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Event</strong></td>
<td><strong>Date</strong></td>
<?php if($industry==0){ ?>
<td><strong>Property</strong></td>
<?php } ?>
<td><strong>For</strong></td>
<td><strong>Regarding</strong></td>
<td><strong>Scheduled By</strong></td>
<?php if($industry==1){ ?>
<td></td>
<?php } ?>
</tr>
<?php

  
$sql = "SELECT *, date_format(date, \"%m/%d/%y\") as datepretty from activities 
where 1=1 and display=1 and prospect_id='$prospect_id' order by complete, date asc";
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
  
  $prop_sql = "SELECT site_name FROM properties WHERE property_id = '". $record['property_id'] ."'";
  $prop_result = executequery($prop_sql);
  $prop_record = go_fetch_array($prop_result);
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?><?php if($record['complete']) echo " style=\"text-decoration: line-through; class=\"strike\"";?>>
  <td><?=$event?></td>
  <td><?=$record['datepretty']?></td>
  <?php if($industry==0){ ?>
  <td>
  <a href="frame_property_activities.php?property_id=<?=$record['property_id']?>"><?=stripslashes($prop_record['site_name'])?></a>
  </td>
  <?php } ?>
  <td><?=$fullname?></td>
  <td>
  <?=$regarding?>
  </td>
  <td><?=$scheduled_by?></td>
  <?php if($industry==1){?>
  <td>
  <?php if($record['complete']==0){?>
  <a href="activity_edit.php?act_id=<?=$record['act_id']?>&prospect_id=<?=$prospect_id?>" target="_parent">edit</a> - 
  <a href="activity_complete.php?act_id=<?=$record['act_id']?>&prospect_id=<?=$prospect_id?>" target="_parent">complete</a> - 
  <?php } ?>
  <a href="activity_delete.php?act_id=<?=$record['act_id']?>&prospect_id=<?=$prospect_id?>" target="_parent">delete</a>
  </td>
  <?php }?>

  </tr>
  <?php
}
?>
</table>
</div>
</body>
</html>
