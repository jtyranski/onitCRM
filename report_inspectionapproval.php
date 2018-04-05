<?php include "includes/functions.php"; ?>
<?php
$show_all_needing_final = $_GET['show_all_needing_final'];
if($show_all_needing_final ==""){
  if($_SESSION['temp_show_all_needing_final'] != ""){
    $show_all_needing_final = $_SESSION['temp_show_all_needing_final'];
  }
  else {
    $show_all_needing_final = 0;
  }
}
$_SESSION['temp_show_all_needing_final'] = $show_all_needing_final;

$rfe_clause = " a.ready_for_email=1 ";
if($show_all_needing_final ==1) $rfe_clause = " 1=1 ";
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<div style="width:100%; position:relative;">
<div class="main" style="float:left;"><strong>Inspection Approval</strong></div>
<div style="float:right;">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="mainform">
Show all inspections needing final approval?
<select name="show_all_needing_final" onchange="document.mainform.submit()">
<option value="0"<?php if($show_all_needing_final==0) echo " selected";?>>No</option>
<option value="1"<?php if($show_all_needing_final==1) echo " selected";?>>Yes</option>
</select>
</form>
</div>
</div>
<div style="clear:both;"></div>
<?php

$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
if($order_by == "") $order_by = "site_name";
if($order_by2 == "") $order_by2 = "asc";
if($order_by2 == "asc"){
  $function = "compare";
}
else {
  $function = "rcompare";
}

function compare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}

function rcompare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return 1;
else
 return -1;
}

// only display properties for the groups the user is member of
$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " a.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and a.subgroups in ($subgroups_search)";
  }
}  // end groups filter


$sql = "SELECT a.site_name, a.city, a.state, a.zip, a.property_id, a.prospect_id, a.pre_approval_reason from properties a, prospects b 
where a.ready_for_pre_approval = 1 and a.prospect_id=b.prospect_id and b.master_id='" . $SESSION_MASTER_ID . "' and $groups_clause";

$result = executequery($sql);
//echo "<!-- $sql -->\n";
$counter = 0;
while($record = go_fetch_array($result)){
  $row[$counter]['site_name'] = stripslashes($record['site_name']);
  $row[$counter]['city'] = stripslashes($record['city']);
  $row[$counter]['state'] = stripslashes($record['state']);
  $row[$counter]['zip'] = stripslashes($record['zip']);
  $row[$counter]['property_id'] = stripslashes($record['property_id']);
  $row[$counter]['prospect_id'] = stripslashes($record['prospect_id']);
  $row[$counter]['pre_approval_reason'] = stripslashes($record['pre_approval_reason']);
  
  $counter++;
}

usort($row, $function);
?>
<div class="main"><strong>Pending Pre-Approval</strong></div>
<div class="main">The following sites have just been submitted for inspection.  If all necessary pictures and videos are up, please approve.</div>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Name</strong></td>
<td><strong>City</strong></td>
<td><strong>State</strong></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php
for($x=0;$x<sizeof($row);$x++){
?>
  <form action="report_inspectionapproval_action.php" method="post">
  <input type="hidden" name="property_id" value="<?=$row[$x]['property_id']?>">
  <input type="hidden" name="type" value="pre_approve">

  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$row[$x]['site_name']?></td>
  <td valign="top"><?=$row[$x]['city']?></td>
  <td valign="top"><?=$row[$x]['state']?></td>
  <td valign="top"><a href="am_ghost_login.php?prospect_id=<?=$row[$x]['prospect_id']?>&property_id=<?=$row[$x]['property_id']?>" target="_blank">View</a>
  </td>
  <td>
  Reason<br>
  <textarea name="pre_approval_reason" rows="3" cols="40"><?=$row[$x]['pre_approval_reason']?></textarea>
  </td>
  <td valign="top">
  <input type="submit" name="submit1" value="Approve">
  <input type="submit" name="submit1" value="Deny">
  </td>
  </tr>

  </form>
  <?php
}
?>
</table>
<br><br>
<?php
$row = "";


$sql = "SELECT a.site_name, a.city, a.state, a.zip, a.property_id, a.prospect_id, ready_for_email, 
final_approval_reason
from properties a, prospects c where a.final_approval != 1 and a.corporate=0 
and a.prospect_id=c.prospect_id and c.master_id='" . $SESSION_MASTER_ID . "' 
and a.pre_approval = 1 and $rfe_clause group by a.property_id";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $row[$counter]['site_name'] = stripslashes($record['site_name']);
  $row[$counter]['city'] = stripslashes($record['city']);
  $row[$counter]['state'] = stripslashes($record['state']);
  $row[$counter]['zip'] = stripslashes($record['zip']);
  $row[$counter]['property_id'] = stripslashes($record['property_id']);
  $row[$counter]['prospect_id'] = stripslashes($record['prospect_id']);
  $row[$counter]['ready_for_email'] = stripslashes($record['ready_for_email']);
  $row[$counter]['final_approval_reason'] = stripslashes($record['final_approval_reason']);
  
  $sql = "SELECT date, date_format(date, \"%m/%d/%Y\") as datepretty from activities where property_id='" . $record['property_id'] . "' 
  and (event='Inspection' or event='Quickbid') and display=1 order by act_id desc limit 1";
  $res = executequery($sql);
  $rec = go_fetch_array($res);
  
  $row[$counter]['date'] = $rec['date'];
  $row[$counter]['datepretty'] = $rec['datepretty'];
  
  $counter++;
}

usort($row, $function);
?>
<div class="main"><strong>Pending Final Approval</strong></div>
<div class="main">The following sites have had Inspections scheduled and need to be marked off with Final Approval in order to be displayed on the client's end.</div>
<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Name</strong></td>
<td><strong>City</strong></td>
<td><strong>State</strong></td>
<td><strong>Inspection Date</strong></td>
<td></td>
<td></td>
</tr>
<?php
if(is_array($row)){
for($x=0;$x<sizeof($row);$x++){
?>
  <form action="report_inspectionapproval_action.php" method="post">
  <input type="hidden" name="property_id" value="<?=$row[$x]['property_id']?>">
  <input type="hidden" name="type" value="final_approve">
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top">
  <a href="view_property.php?property_id=<?=$row[$x]['property_id']?>" target="_blank">
  <?=$row[$x]['site_name']?>
  </a>
  </td>
  <td valign="top"><?=$row[$x]['city']?></td>
  <td valign="top"><?=$row[$x]['state']?></td>
  <td valign="top">
  <?=$row[$x]['datepretty']?>
  </td>
  <td valign="top"><a href="am_ghost_login.php?prospect_id=<?=$row[$x]['prospect_id']?>&property_id=<?=$row[$x]['property_id']?>" target="_blank">View</a>
  </td>
  <td>
  <?php if($row[$x]['ready_for_email']==1){ ?>
  Reason<br>
  <textarea name="final_approval_reason" rows="3" cols="40"><?=$row[$x]['final_approval_reason']?></textarea>
  <?php } ?>
  </td>
  <td valign="top">
  <?php if($row[$x]['ready_for_email']==1){ ?>
  <input type="submit" name="submit1" value="Approve">
  <input type="submit" name="submit1" value="Deny">
  <?php } else { ?>
  <input type="submit" name="submit1" value="Submit For Final">
  <?php } ?>
  </td>
  </tr>
  </form>
  <?php
}
}
?>
</table>

  
  
  
  
