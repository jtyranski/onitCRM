<?php

$contacts_image = "contacts-button-reflect_off.png";
$opp_image = "fcs-button-reflect_off.png";
$cal_image = "calendar-button-reflect_off.png";
$stat_image = "stats-button-reflect_off.png";
$tool_image = "toolbox-button-reflect_off.png";
$support_image = "fcs_support-button-reflect_off.png";

$contacts_array = array("contacts.php", "search.php", "view_property.php", "view_company.php");
$opp_array = array("report_sales_status.php");
$cal_array = array("calendar.php");
$stat_array = array("statistics.php");
$tool_array = array("toolbox.php", "user_edit.php");
$support_array = array("support.php");

if(in_array($current_file_name, $contacts_array)) $contacts_image = "contacts-button-reflect_on.png";
if(in_array($current_file_name, $opp_array)) $opp_image = "fcs-button-reflect_on.png";
if(in_array($current_file_name, $cal_array)) $cal_image = "calendar-button-reflect_on.png";
if(in_array($current_file_name, $stat_array)) $stat_image = "stats-button-reflect_on.png";
if(in_array($current_file_name, $tool_array)) $tool_image = "toolbox-button-reflect_on.png";
if(in_array($current_file_name, $support_array)) $support_image = "fcs_support-button-reflect_on.png";

$sql = "SELECT show_new_support from users where user_id='" . $SESSION_USER_ID . "'";
$show_new_support = getsingleresult($sql);
if($show_new_support) $support_image = "fcs_support-button-reflect_newitems.png";

$contacts_image = "spacer.gif";
$opp_image = "spacer.gif";
$cal_image = "spacer.gif";
$stat_image = "spacer.gif";
$tool_image = "spacer.gif";
$support_image = "spacer.gif";

?>

<div style="width:100%; height:133px; text-align:center; position:relative; overflow:auto; white-space:nowrap; overflow-y:hidden;" class="main_nav" align="center" id="NAVIGATION_DIV">


<table align="center">
<tr>
<td valign="top">
Contacts<div style="height:10px;"><img src="images/spacer.gif"></div>
<?php
ImageLink("contacts.php", "contacts-button", 1, 0);
?>
<br>
<img src="images/<?=$contacts_image?>" width="64" />
</td>
<td width="50"><img src="images/spacer.gif" width="45"></td>

<?php if($SESSION_OPPORTUNITIES==1){ ?>
<td valign="top">
Opportunity<div style="height:10px;"><img src="images/spacer.gif"></div>
<?php
ImageLink("report_sales_status.php", "ro-button", 1, 0);
?>
<br>
<img src="images/<?=$opp_image?>">
</td>
<td width="50"><img src="images/spacer.gif" width="45"></td>
<?php } ?>

<td valign="top">
Calendar<div style="height:10px;"><img src="images/spacer.gif"></div>
<?php
ImageLink("calendar.php", "calendar-button", 1, 0);
?>
<br>
<img src="images/<?=$cal_image?>" width="64" />
</td>
<td width="50"><img src="images/spacer.gif" width="45"></td>

<td valign="top">
ToolBox<div style="height:10px;"><img src="images/spacer.gif"></div>
<?php
ImageLink("toolbox.php", "toolbox-button", 1, 0);
?>
<br>
<img src="images/<?=$tool_image?>" width="64" />
</td>
<td width="50"><img src="images/spacer.gif" width="45"></td>

<?php /*
<td valign="top">
Support<div style="height:10px;"><img src="images/spacer.gif"></div>
<!--Start AliveChat Button Code-->
<div style='padding:0px;margin:0px;width:auto'>
<img src="https://images.websitealive.com/images/hosted/upload/38696.png" border="0" onClick="document.location.href='support.php'" style="cursor:pointer"><br>
<div style='background-color:; padding:4px; font-size:8px; color:#fff; font-family:Verdana, Helvetica, sans-serif;'><img src="images/<?=$support_image?>" width="64" height="32" alt=""/ border="0"></div>
</div>
<!--End AliveChat Button Code-->
</td>
<td width="50"><img src="images/spacer.gif" width="45"></td>
*/?>
<?php
$sql = "SELECT disable_caps from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$dc = getsingleresult($sql);

$sql = "SELECT tools_active, show_new_dispatch from users where user_id='" . $SESSION_USER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$tools_active = $record['tools_active'];
$show_new_dispatch = $record['show_new_dispatch'];

/*
if($show_new_dispatch){
$sql = "SELECT count(a.leak_id) from am_leakcheck a, prospects b where a.prospect_id=b.prospect_id and b.master_id='" . $SESSION_MASTER_ID . "' and a.status='Dispatched' and a.archive=0";
$test = getsingleresult($sql);
if($test==0){
  $sql = "UPDATE users set show_new_dispatch=0 where master_id='" . $SESSION_MASTER_ID . "'";
  executeupdate($sql);
  $show_new_dispatch = 0;
}
}
*/

$active_array = explode(",", $tools_active);
for($x=0;$x<sizeof($active_array);$x++){ 
  $tool_id = $active_array[$x];
  if($tool_id=="") continue;
  $sql = "SELECT name, url, on_icon, tool_master_id from toolbox_items where id='$tool_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  if($dc==1 && $record['tool_master_id']==4) continue;
  
?>
<td nowrap="nowrap" valign="top">
<?php if($current_file_name=="toolbox_edit.php"){ ?>
<div style="float:left; position:relative; background:url(images/toolbox/<?=stripslashes($record['on_icon'])?>); background-repeat:no-repeat;" class="drag" id="X_<?=$tool_id?>">
<br><br><br><br>
<?=stripslashes($record['name'])?>
</div>
<?php } else { 
  $use_image = stripslashes($record['on_icon']);
  //if($record['tool_master_id']==3 && $show_new_dispatch==1) $use_image = "toolbox_blink.gif";
  ?>
<?=stripslashes($record['name'])?><div style="height:10px;"><img src="images/spacer.gif"></div>
<a href="toolbox.php?go=<?=stripslashes($record['url'])?>"><img src="images/toolbox/<?=$use_image?>" border="0"></a>
<?php } ?>
</td>
<td width="50"><img src="images/spacer.gif" width="45"></td>
<?php }?>

</tr>
</table>


</div>

<div style="clear:both;"></div>
