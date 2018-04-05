<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
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
  if($groups_search != "") $groups_clause = " b.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and b.subgroups in ($subgroups_search)";
  }
}  // end groups filter

$sql = "SELECT a.leak_id, a.property_id, b.site_name, a.approval_reason, b.city, b.state, b.zip from am_leakcheck a, properties b, 
prospects c where 
a.ready_for_approval = 1 and a.property_id=b.property_id and a.prospect_id=c.prospect_id and c.master_id='" . $SESSION_MASTER_ID . "' and $groups_clause";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  $row[$counter]['site_name'] = stripslashes($record['site_name']);
  $row[$counter]['city'] = stripslashes($record['city']);
  $row[$counter]['state'] = stripslashes($record['state']);
  $row[$counter]['zip'] = stripslashes($record['zip']);
  $row[$counter]['property_id'] = stripslashes($record['property_id']);
  $row[$counter]['leak_id'] = stripslashes($record['leak_id']);
  $row[$counter]['approval_reason'] = stripslashes($record['approval_reason']);
  
  $counter++;
}

usort($row, $function);
?>
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
  <form action="report_sdapproval_action.php" method="post">
  <input type="hidden" name="leak_id" value="<?=$row[$x]['leak_id']?>">
  <tr<?php if($x % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$row[$x]['site_name']?></td>
  <td valign="top"><?=$row[$x]['city']?></td>
  <td valign="top"><?=$row[$x]['state']?></td>
  <td valign="top"><a href="fcs_sd_report_view.php?leak_id=<?=$row[$x]['leak_id']?>" target="_blank">View</a>
  </td>
  <td>
  Reason<br>
  <textarea name="approval_reason" rows="3" cols="40"><?=$row[$x]['approval_reason']?></textarea>
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

  
  
  
  
