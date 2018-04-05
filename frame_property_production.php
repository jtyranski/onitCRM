<?php
include "includes/header_white.php";

$property_id = $_GET['property_id'];

$opm_id = $_GET['opm_id'];
if($opm_id == ""){
  $sql = "SELECT opm_id from opm where property_id='$property_id' order by project_id limit 1";
  $opm_id = getsingleresult($sql);
}

$sql = "SELECT user_id, 
date_format(pm_start, \"%m/%d/%Y\") as pm_start_pretty,
date_format(pm_finish, \"%m/%d/%Y\") as pm_finish_pretty,
date_format(ip_start, \"%m/%d/%Y\") as ip_start_pretty,
date_format(ip_finish, \"%m/%d/%Y\") as ip_finish_pretty,
date_format(fi_start, \"%m/%d/%Y\") as fi_start_pretty,
date_format(fi_finish, \"%m/%d/%Y\") as fi_finish_pretty,
date_format(ip_email_start, \"%m/%d/%Y\") as ip_email_start_pretty,
project_sqft, produced_sqft, in_q, opp_id
from opm where opm_id='$opm_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$user_id = stripslashes($record['user_id']);
$pm_start_pretty = stripslashes($record['pm_start_pretty']);
$pm_finish_pretty = stripslashes($record['pm_finish_pretty']);
$ip_start_pretty = stripslashes($record['ip_start_pretty']);
$ip_finish_pretty = stripslashes($record['ip_finish_pretty']);
$fi_start_pretty = stripslashes($record['fi_start_pretty']);
$fi_finish_pretty = stripslashes($record['fi_finish_pretty']);
$ip_email_start_pretty = stripslashes($record['ip_email_start_pretty']);
$project_sqft = stripslashes($record['project_sqft']);
$produced_sqft = stripslashes($record['produced_sqft']);
$in_q = stripslashes($record['in_q']);
$opp_id = stripslashes($record['opp_id']);

if($pm_start_pretty=="00/00/0000") $pm_start_pretty = "";
if($pm_finish_pretty=="00/00/0000") $pm_finish_pretty = "";
if($ip_start_pretty=="00/00/0000") $ip_start_pretty = "";
if($ip_finish_pretty=="00/00/0000") $ip_finish_pretty = "";
if($fi_start_pretty=="00/00/0000") $fi_start_pretty = "";
if($fi_finish_pretty=="00/00/0000") $fi_finish_pretty = "";
if($ip_email_start_pretty=="00/00/0000") $ip_email_start_pretty = "";

if($_SESSION['sess_error_pm_start_pretty'] != "") $pm_start_pretty = $_SESSION['sess_error_pm_start_pretty'];
if($_SESSION['sess_error_pm_finish_pretty'] != "") $pm_finish_pretty = $_SESSION['sess_error_pm_finish_pretty'];
if($_SESSION['sess_error_ip_start_pretty'] != "") $ip_start_pretty = $_SESSION['sess_error_ip_start_pretty'];
if($_SESSION['sess_error_ip_finish_pretty'] != "") $ip_finish_pretty = $_SESSION['sess_error_ip_finish_pretty'];
if($_SESSION['sess_error_fi_start_pretty'] != "") $fi_start_pretty = $_SESSION['sess_error_fi_start_pretty'];
if($_SESSION['sess_error_fi_finish_pretty'] != "") $fi_finish_pretty = $_SESSION['sess_error_fi_finish_pretty'];

if($produced_sqft==0){
  $sql = "SELECT sum(replaced) from opm_entry where opm_id='$opm_id'";
  $show_produced_sqft = getsingleresult($sql);
  $show_override = 1;
}
else {
  $show_produced_sqft = $produced_sqft;
  $show_override = 0;
}
$percent = ($show_produced_sqft / $project_sqft) * 100;
$percent = round($percent, 1);

$CAN_EDIT_THIS_OPM = 0;
if($SESSION_ISADMIN) $CAN_EDIT_THIS_OPM = 1;
if($SESSION_USER_LEVEL=="Manager") $CAN_EDIT_THIS_OPM = 1;
if($CAN_EDIT_THIS_OPM==0){
  $sql = "SELECT can_edit_ops from users where user_id='" . $SESSION_USER_ID . "'";
  $CAN_EDIT_THIS_OPM = getsingleresult($sql);
}
if($CAN_EDIT_THIS_OPM==0){
  $sql = "SELECT user_id, scheduled_by from opportunities where opp_id='$opp_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $x_user_id = $record['user_id'];
  $x_scheudled_by = $record['scheduled_by'];
  if($x_user_id==$SESSION_USER_ID || $x_scheduled_by==$SESSION_USER_ID || $user_id==$SESSION_USER_ID) $CAN_EDIT_THIS_OPM = 1;
}

?>
<script src="includes/calendar.js"></script>
<script>
function checkform(f){
  var errmsg = "";
  //if(f.pm_start_pretty.value != "" && f.pm_finish_pretty.value==""){ errmsg += "Please enter a date for the Production Meeting finish.\n";}
  //if(f.pm_start_pretty.value == "" && f.pm_finish_pretty.value!=""){ errmsg += "Please enter a date for the Production Meeting start.\n";}
  
  if(f.ip_start_pretty.value != "" && f.ip_finish_pretty.value==""){ errmsg += "Please enter a date for the In Production finish.\n";}
  if(f.ip_start_pretty.value == "" && f.ip_finish_pretty.value!=""){ errmsg += "Please enter a date for the In Production start.\n";}
  
  //if(f.fi_start_pretty.value != "" && f.fi_finish_pretty.value==""){ errmsg += "Please enter a date for the Final Inspection finish.\n";}
  //if(f.fi_start_pretty.value == "" && f.fi_finish_pretty.value!=""){ errmsg += "Please enter a date for the Final Inspection start.\n";}
  
  if(f.user_id.value==0){ errmsg += "Please select a Foreman.\n";}
  if(f.project_sqft.value=="" || f.project_sqft.value==0){ errmsg += "Please enter a numeric value for the Project SqFt.\n";}
  
  if(errmsg == ""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}

function DelEntry(x){
  cf = confirm("Are you sure you want to delete this daily progress entry?");
  if(cf){
    document.location.href="frame_property_production_opm_delete.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&opm_entry_id=" + x;
  }
}

function pm_finish(){
  if(document.getElementById('pm_finish_pretty').value==""){ document.getElementById('pm_finish_pretty').value = document.getElementById('pm_finish_pretty').value;}
}

function fi_finish(){
  //alert("test");
  if(document.getElementById('fi_finish_pretty').value==""){ document.getElementById('fi_finish_pretty').value = document.getElementById('fi_start_pretty').value;}
}

function updatevalue () {
  
   x = document.getElementById('produced_sqft').value;
  //alert(contmsg);
    url = "frame_property_production_override.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&action=update&produced_sqft=" + x;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function showpercent (action) {
    url = "frame_property_production_override.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&action=" + action;
	url=url+"&sid="+Math.random();
	//document.getElementById('db').style.display="";
	//document.getElementById('db').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

</script>
  
<div class="main">
<div id="db" style="display:none;"></div>
<div style="width:100%; position:relative;">
<div style="float:left;"><strong>Project Setup</strong></div>
<div style="float:right;">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="loadprojectform">
<input type="hidden" name="property_id" value="<?=$property_id?>">
Project ID:
<select name="opm_id" onchange="document.loadprojectform.submit()">
<?php
$sql = "SELECT opm_id, project_id from opm where property_id='$property_id' and property_id != 0 order by project_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['opm_id']?>"<?php if($opm_id==$record['opm_id']) echo " selected";?>><?=stripslashes($record['project_id'])?></option>
  <?php
}
?>
</select>
</form>
</div>
</div>
<div style="clear:both;"></div>
<?php if($_SESSION['sess_msg'] != ""){?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php 
$_SESSION['sess_msg'] = "";
}

$action = "frame_property_production_action.php";
if($CAN_EDIT_THIS_OPM == 0) $action = "#";
?>
<br><br>

<form action="<?=$action?>" method="post" name="mainform" onsubmit="return checkform(this)">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="opm_id" value="<?=$opm_id?>">
Foreman:
<select name="user_id">
<option value="0"></option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' and enabled=1 and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<br>
<table class="main" cellpadding="4" cellspacing="0">
<tr>
<?php /*
<td> Production Meeting </td>
<td> Start Date
<input type="text" name="pm_start_pretty" id="pm_start_pretty" value="<?=$pm_start_pretty?>" size="10"<?php if($_SESSION['sess_error_pm']==1) echo " style=\"color:red;\"";?>>
<img src="images/calendar.gif" onClick="KW_doCalendar('pm_start_pretty',0)" align="absmiddle">

</td>
<td> Finish Date
<input type="text" name="pm_finish_pretty" id="pm_finish_pretty" value="<?=$pm_finish_pretty?>" size="10"<?php if($_SESSION['sess_error_pm']==1) echo " style=\"color:red;\"";?>>
<img src="images/calendar.gif" onClick="KW_doCalendar('pm_finish_pretty',0)" align="absmiddle">
</td>
*/?>

<td colspan="3">Start sending "Project Start" notification emails on:
<input type="text" name="ip_email_start_pretty" id="ip_email_start_pretty" value="<?=$ip_email_start_pretty?>" size="10">
<img src="images/calendar.gif" onClick="KW_doCalendar('ip_email_start_pretty',0)" align="absmiddle">
</td>
<td align="right">% Complete</td>
<td id="percent"></td>
</tr>

<tr>
<td>In Production</td>
<td>Start Date
<input type="text" name="ip_start_pretty" id="ip_start_pretty" value="<?=$ip_start_pretty?>" size="10"<?php if($_SESSION['sess_error_ip']==1) echo " style=\"color:red;\"";?>>
<img src="images/calendar.gif" onClick="KW_doCalendar('ip_start_pretty',0)" align="absmiddle">
</td>
<td>Finish Date
<input type="text" name="ip_finish_pretty" id="ip_finish_pretty" value="<?=$ip_finish_pretty?>" size="10"<?php if($_SESSION['sess_error_ip']==1) echo " style=\"color:red;\"";?>>
<img src="images/calendar.gif" onClick="KW_doCalendar('ip_finish_pretty',0)" align="absmiddle">
</td>

<td align="right">Project SqFt</td>
<td><input type="text" name="project_sqft" value="<?=$project_sqft?>" size="10"></td>
</tr>

<tr>
<td><?php /* Final Inspection */?></td>
<td><?php /* Start Date
<input type="text" name="fi_start_pretty" id="fi_start_pretty" value="<?=$fi_start_pretty?>" size="10"<?php if($_SESSION['sess_error_fi']==1) echo " style=\"color:red;\"";?>>
<img src="images/calendar.gif" onClick="KW_doCalendar('fi_start_pretty',0)" align="absmiddle">
*/?>
</td>
<td><?php /* Finish Date
<input type="text" name="fi_finish_pretty" id="fi_finish_pretty" value="<?=$fi_finish_pretty?>" size="10"<?php if($_SESSION['sess_error_fi']==1) echo " style=\"color:red;\"";?>>
<img src="images/calendar.gif" onClick="KW_doCalendar('fi_finish_pretty',0)" align="absmiddle">
*/?>
</td>
<td align="right">Produced SqFt</td>
<td>
<span id="show_produced_sqft">
</span>
<span id="override_area"></span>
</td>
</tr>
</table>
<?php if($CAN_EDIT_THIS_OPM){?>
<input type="submit" name="submit1" value="Update Values">
<?php } ?>
</form>

<?php if($in_q==0){ ?>
<?php if($CAN_EDIT_THIS_OPM){?>
<a href="frame_property_production_opm.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&opm_entry_id=new">Add a Day</a>
<?php } ?>
<table class="main" cellpadding="4" cellspacing="0">
<tr>
<td><strong>Date of Work</strong></td>
<td><strong>Roofing Removed (SF)</strong></td>
<td><strong>Roofing Replaced (SF)</strong></td>
<td><strong>Photos</strong></td>
<td></td>
<td></td>
<td></td>
</tr>
<?php
$counter = 0;
$sql = "SELECT date_format(opm_date, \"%m/%d/%Y\") as opm_date, removed, replaced, opm_entry_id from opm_entry where opm_id='$opm_id' order by opm_date";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  $sql = "SELECT count(*) from opm_entry_photos where opm_entry_id='" . $record['opm_entry_id'] . "'";
  $photos = getsingleresult($sql);
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td><?=$record['opm_date']?></td>
  <td><?=number_format($record['removed'], 1)?></td>
  <td><?=number_format($record['replaced'], 1)?></td>
  <td>
  <?php if($CAN_EDIT_THIS_OPM){?>
  <a href="frame_property_production_opm_photos.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&opm_entry_id=<?=$record['opm_entry_id']?>">
  <?php } ?>
  <?=$photos?></a></td>
  <td>
  <?php if($CAN_EDIT_THIS_OPM){?>
  <a href="frame_property_production_opm_view.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&opm_entry_id=<?=$record['opm_entry_id']?>">
  <?php } ?>
  view</a></td>
  <td>
  <?php if($CAN_EDIT_THIS_OPM){?>
  <a href="frame_property_production_opm.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&opm_entry_id=<?=$record['opm_entry_id']?>">
  <?php } ?>
  edit</a></td>
  <td>
  <?php if($CAN_EDIT_THIS_OPM){?>
  <a href="javascript:DelEntry('<?=$record['opm_entry_id']?>')">
  <?php } ?>
  delete</a></td>
  </tr>
  <?php
}
?>
</table>

<?php } // if inq=0.  only show photos if not in prod q ?>
</div>

<script>
//setInterval("fi_finish()", 500);
//setInterval("pm_finish()", 500);
showpercent('view');
</script>


