<?php

require_once "../includes/functions.php"; 

/*
Supercali Event Calendar

Copyright 2006 Dana C. Hutchins

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

For further information visit:
http://supercali.inforest.com/
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<title><?php echo $calendar_title." - ".$page_title; ?></title>


<link rel="stylesheet" type="text/css" href="css/supercali.css">

<meta http-equiv="refresh" content="900;url=<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>">
<?php if ($_REQUEST["size"] == "small") echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/small.css\">\n"; ?>
<?php if ($css) echo $css; ?>

<!-- calendar ipad: <?=$_SESSION['calendar_ipad']?> get ipad: <?=$ipad?> -->
<script language="JavaScript" src="js/CalendarPopup.js"></script>
<script language="JavaScript">document.write(getCalendarStyles());</script>
<script language="JavaScript" src="js/ColorPicker2.js"></script>
<script language="JavaScript" src="js/miscfunctions.js"></script>
<?php if ($javascript) echo $javascript; ?>
<?php echo "<!-- O= $o cal= $calendar_type-->\n";
?>

</head>
<script>
function load_userfilter(x){
  y = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>&searchby=" + y;
}

function load_calendartype(x){
  y = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?calendar_type=" + y;
}

function LeftNavShowHide(x, savestate){
  //alert(x);
  if(x=="1"){
    document.getElementById("leftnav_arrow").innerHTML = "<a href=\"javascript:LeftNavShowHide('0', '1')\" style=\"color:white; text-decoration:none;\"><img src=\"../images/arrow_left.gif\" border=\"0\"></a>";
    document.getElementById("leftnav").style.display = "";
	<?php if($o==5){ ?>
    t = document.getElementById('gantt_total').style.width;
	t = t.replace(/px/i, "");
	t = parseInt(t);
	t = t - 140;
	r = document.getElementById('gantt_rs_display').style.width;
	r = r.replace(/px/i, "");
	r = parseInt(r);
	r = r - 140;
	document.getElementById('gantt_total').style.width = t + "px";
	document.getElementById('gantt_rs_display').style.width = r + "px";
    <?php } ?>
  }
  else {
    document.getElementById('leftnav_arrow').innerHTML = "<a href=\"javascript:LeftNavShowHide('1', '1')\" style=\"color:white; text-decoration:none;\"><img src=\"../images/arrow_right.gif\" border=\"0\"></a>";
	document.getElementById("leftnav").style.display = "none";
	<?php if($o==5){ ?>
    t = document.getElementById('gantt_total').style.width;
	t = t.replace(/px/i, "");
	t = parseInt(t);
	t = t + 140;
	r = document.getElementById('gantt_rs_display').style.width;
	r = r.replace(/px/i, "");
	r = parseInt(r);
	r = r + 140;
	document.getElementById('gantt_total').style.width = t + "px";
	document.getElementById('gantt_rs_display').style.width = r + "px";
    <?php } ?>
	
  }
  if(savestate==1){
    url = "leftnav_state.php?x=" + x;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
		//document.getElementById('debug_act').style.display="";
		//document.getElementById('debug_act').innerHTML = url;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
  }
}
</script>
<script type="text/javascript" src="../includes/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table width="100%">
<tr>
<?php
//if($current_file_name=="index.php"){
?>
<td valign="top" width="180" bgcolor="#000000" id="leftnav" nowrap="nowrap">
<?php
$sql = "SELECT category_id from supercali_categories where calendar_type='Sales'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $sales_category_ids[] = $record['category_id'];
}
$sql = "SELECT category_id from supercali_categories where calendar_type='Operations'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $operations_category_ids[] = $record['category_id'];
}
//$operations_category_ids[] = 26; // the General type will be for both - now it won't

$calendar_type = $_GET['calendar_type'];
if($calendar_type==""){
  if($_SESSION['supercali_calendar_type'] != ""){
    $calendar_type = $_SESSION['supercali_calendar_type'];
  }
  else {
    $calendar_type = "Sales";
  }
}



if($calendar_type=="Sales") $current_category_ids = $sales_category_ids;
if($calendar_type=="Operations") $current_category_ids = $operations_category_ids;


$show_userid = $_GET['show_userid'];
$show_what = $_GET['show_what'];
$show_type = $_GET['show_type'];
$show_crew = $_GET['show_crew'];
$show_complete = $_GET['show_complete'];
if(!(is_array($show_userid))){
  $show_userid = $current_category_ids;
}

if($show_what=="") $show_what = $_SESSION['supercali_show_what_array'];
if($show_what=="") $show_what[] = $SESSION_USER_ID;
$_SESSION['supercali_show_what_array'] = $show_what;

?>
<script>
function DelProdQ(x){
  document.location.href="delprodq.php?show_userid=<?=$show_userid?>&show_what=<?=$show_what?>&show_type=<?=$show_type?>&show_crew=<?=$show_crew?>&show_complete=<?=$show_complete?>&opp_id=" + x;
}

function DelEvent(x){
  cf = confirm("Are you sure you want to remove this event from the schedule?");
  if(cf){
    document.location.href="delevent.php?show_userid=<?=$show_userid?>&show_what=<?=$show_what?>&show_type=<?=$show_type?>&show_crew=<?=$show_crew?>&show_complete=<?=$show_complete?>&event_id=" + x;
  }
}
function EndDate(event_id, x){
  document.location.href="enddate.php?show_userid=<?=$show_userid?>&show_what=<?=$show_what?>&show_type=<?=$show_type?>&show_crew=<?=$show_crew?>&show_complete=<?=$show_complete?>&event_id=" + event_id + "&move=" + x;
}
function MoveAll(user_id, x, cat){
  document.location.href="moveall.php?show_userid=<?=$show_userid?>&show_what=<?=$show_what?>&show_type=<?=$show_type?>&show_crew=<?=$show_crew?>&show_complete=<?=$show_complete?>&user_id=" + user_id + "&move=" + x + "&cat=" + cat;
}
</script>

<?php /*
<form action="<?=$_SERVER['SCRIPT_NAME']?>?searchby=<?=$_GET['searchby']?>" method="get" name="showuseridform">



<select name="show_what" onchange="document.showuseridform.submit()">
<option value="">All</option>
<?php

if($calendar_type=="Sales"){
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where report=1 order by lastname";
}
else {
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where project_manager=1 order by lastname";
}
// JimT says show all users 3/3/11 JW
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($show_what == $record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<br><br>


<br><br>
<?php
$sql = "SELECT category_id, name, color, background from supercali_categories where category_id in(" . implode(",", $current_category_ids) . ") order by name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $jw_name = stripslashes($record['name']);
  $jw_color = stripslashes($record['color']);
  $jw_background = stripslashes($record['background']);
  ?>
  <div style="padding-top:3px; padding-bottom:3px; background-color:#FFFFFF;<?php if($jw_color != "") echo " color:" . $jw_color . ";";?>">
  <input type="checkbox" name="show_userid[]" value="<?=$record['category_id']?>"<?php if(in_array($record['category_id'], $show_userid)) echo " checked";?> onchange="document.showuseridform.submit()">
  <?=$jw_name?>
  </div>
  <?php
}

?>
</form>
*/?>

<?php /*
<?php 
if($current_file_name=="index.php" && $calendar_type=="Sales"){ ?>
<br>
<div style="height:600px; overflow:auto;">
<table width="100%" bgcolor="#000000" style="color:#FFFFFF;">
<?php
$user_id = $SESSION_USER_ID;
$olddate = "";
if($_SESSION['ro_irep'] != "") $irep_clause = " and d.user_id='" . $_SESSION['ro_irep'] . "' ";

$sql = "SELECT a.event_id, a.act_id, a.description, date_format(b.date, \"%a, %b %d, %Y\") as datepretty, c.color, d.firstname, 
a.property_id, a.prospect_id from 
supercali_events a, supercali_dates b, supercali_categories c, users d where
a.event_id = b.event_id 
and a.category_id = c.category_id 
and a.ro_user_id = d.user_id 
and a.category_id in(" . implode(",", $current_category_ids) . ") 
and a.category_id != 26 
and b.date < now() 
$irep_clause
order by b.date";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  $prospect_id = $record['prospect_id'];
  if($property_id){
    $x_link = "../" . $ipad_uplink . "view_property.php?property_id=$property_id&view=activities";
	$sql = "SELECT site_name from properties where property_id='$property_id'";
	$x_name = stripslashes(getsingleresult($sql));
  }
  else {
    $x_link = "../" . $ipad_uplink . "view_prospect.php?prospect_id=$prospect_id&view=activities";
	$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
	$x_name = stripslashes(getsingleresult($sql));
  }
  
  if($olddate != $record['datepretty']){
    ?>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2" align="center" bgcolor="#666666" class="main_large">
	<?=$record['datepretty']?>
	</td>
	</tr>
	<?php
	$olddate = $record['datepretty'];
  }
  

    ?>
	<tr>
	<td valign="top">
	<a href="<?=$x_link?>" style="color:<?=$record['color']?>;" target="_top"><?=stripslashes($record['firstname'])?> - <?=$x_name?></a>
	<?php if($_GET['sid']==1){ ?>
	<br>
	Event ID: <?=$record['event_id']?>
	<?php } ?>
	</td>
	<td valign="top">
	<?php if($SESSION_ISADMIN) {?>
	<a href="../activity_edit.php?act_id=<?=$record['act_id']?>&redirect=supercali" target="_top">edit</a><br>
	<a href="../activity_delete.php?act_id=<?=$record['act_id']?>&redirect=supercali" target="_top">delete</a>
	<?php } ?>
	</td>
	</tr>
	<?php

}
?>
</table>
</div>
<?php } 
?>
<?php */ ?>

<script>
function ProdQ(x){
  //alert(x);
  if(x=="1"){
    document.getElementById("prodq_arrow").innerHTML = "<a href=\"javascript:ProdQ('0')\" style=\"color:white; text-decoration:none;\">-</a>";
    document.getElementById("prodq_list").style.display = "";
  }
  else {
    document.getElementById('prodq_arrow').innerHTML = "<a href=\"javascript:ProdQ('1')\" style=\"color:white; text-decoration:none;\">+</a>";
	document.getElementById("prodq_list").style.display = "none";
	
  }
}
</script>
<?php 

if($current_file_name=="index.php" && $calendar_type=="Operations"){ ?>
<?php
/*
<?php
$sql = "SELECT category_id, name, color, background from supercali_categories where category_id in(" . implode(",", $current_category_ids) . ") order by category_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $jw_name = stripslashes($record['name']);
  $jw_color = stripslashes($record['color']);
  $jw_background = stripslashes($record['background']);
  ?>
  <div style="padding-top:3px; padding-bottom:3px; background-color:#FFFFFF;<?php if($jw_color != "") echo " color:" . $jw_color . ";";?>">
  <strong><?=$jw_name?></strong>
  </div>
  <?php
}
*/
?>

<br>
<div class="main_large" style="width:100%; background-color:#666666; color:#FFFFFF;" align="center">
Production Queue
</div>
<div style="height:600px; overflow:auto;" id="prodq_list">
<table width="100%" bgcolor="#000000" style="color:#FFFFFF;">
<?php
// this query only works if project manager is selected - which I think works good for me
// prodstat_id of 8 is complete

$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " c.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and c.subgroups in ($subgroups_search)";
  }
}

$sql = "SELECT b.company_name, c.site_name, c.property_id 
from opm a, prospects b, properties c 
where a.prospect_id=b.prospect_id and a.property_id=c.property_id and a.master_id = '" . $SESSION_MASTER_ID . "' and a.in_q=1 and $groups_clause order by b.company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  
  ?>
  <tr>
  <td valign="top">
  <?=stripslashes($record['company_name'])?><br><?=stripslashes($record['site_name'])?></td>
  <td valign="top">
  <a href="../view_property.php?property_id=<?=$record['property_id']?>&view=production" target="_top">
  Edit</a>
  </td>
  </tr>
  <?php
}
?>
</table>
</div>
<?php } ?>

<?php
if($current_file_name=="index.php" && $calendar_type=="Programming"){ ?>
<script>
function ProgrammingMenu(x, on, save){
  if(on==1){
    document.getElementById('stage_' + x).style.display="";
	document.getElementById('arrow_stage_' + x).innerHTML = "<a href=\"javascript:ProgrammingMenu('" + x + "', '0', '1')\" style='font-weight:bold; text-decoration:none; color:white; font-size:16px;'>-</a>";
  }
  else {
    document.getElementById('stage_' + x).style.display="none";
	document.getElementById('arrow_stage_' + x).innerHTML = "<a href=\"javascript:ProgrammingMenu('" + x + "', '1', '1')\" style='font-weight:bold; text-decoration:none; color:white; font-size:16px;'>+</a>";
  }
  
  if(save==1){
  url = "programming_menu_save.php?x=" + x + "&on=" + on;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
	}	
}
</script>
<?php
$PROGRAMMING = array("Requested", "Under Review", "Approved", "Conceptual", "Final Approved");
?>

<br>
<div class="main_large" style="width:100%; background-color:#666666; color:#FFFFFF;" align="center">
Programming Queue
</div>
<div style="color:white; position:relative;">
<?php
for($x=0;$x<sizeof($PROGRAMMING);$x++){
  $stage = $PROGRAMMING[$x];
  $OPENMENU[$x] = 0;
  echo "<br>";
  echo "<span id='arrow_stage_$x'><a href=\"javascript:ProgrammingMenu('$x', '0', '1')\" style='font-weight:bold; text-decoration:none; color:white; font-size:16px;'>-</a></span>";
  echo "<span style='font-weight:bold; font-size:14px;'>$stage</span><br>";
  echo "<div id='stage_$x' style='padding-left:5px;'>";
  $sql = "SELECT a.event_id, a.urgency, a.title, date_format(a.created_date, \"%m/%d/%y\") as created_date, concat(b.firstname, ' ', b.lastname) as fullname, a.programming_type from supercali_events a, users b where 
  a.created_by = b.user_id and a.category_id=37 and a.ro_user_id=0 and a.stage=\"$stage\" order by a.urgency";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $OPENMENU[$x] = 1;
    $title = stripslashes($record['title']);
	$title_short = substr($title, 0, 30);
	$title = go_reg_replace("\'", "", $title);
	$urgency = stripslashes($record['urgency']);
	$fullname = stripslashes($record['fullname']);
	$created_date = stripslashes($record['created_date']);
	$programming_type = stripslashes($record['programming_type']);
	if($programming_type=="F") $type = "Fix"; 
	if($programming_type=="I") $type = "Improvement";
	$popup = $title . "<br><br>" . $type . "<br>Created By: " . $fullname . "<br>" . $created_date . "<br>Urgency: " . $urgency;
	$popup = go_reg_replace("\'", "", $popup);
	$style = "style='color:white; text-decoration:none;'";
	if($type=="Fix") $style="style='color:#CC9900; text-decoration:none;'";  // make fixes yellow
	if($stage=="Final Approved" && $urgency==1) $style = "style='color:red; text-decoration:none;'";
	?>
	<a href="javascript:load_programming('<?=$record['event_id']?>')" <?=$style?> alt="" onMouseOver="return overlib('<?=$popup?>')"; onMouseOut="nd();"><?=$urgency?> <?=$title_short?></a><br>
	<?php
  }
  echo "</div>";
}
?>
<br><br><br><br>
<?php } ?>
</div>
<script>
<?php for($x=0;$x<sizeof($OPENMENU);$x++){ 
  if($OPENMENU[$x]==0){?>
  ProgrammingMenu('<?=$x?>', '0', '0');
  <?php }
}

for($x=0;$x<sizeof($PROGRAMMING);$x++){
  if($_SESSION[$sess_header . '_programmingmenu_' . $x] != ""){
    ?>
	ProgrammingMenu('<?=$x?>', '<?=$_SESSION[$sess_header . '_programmingmenu_' . $x]?>', '0');
	<?php
  }
}
?>
</script>
</td>
<td valign="top">
 
<?php /*
<div class="top">
<div class="top_nav">
<?php 
if ($_REQUEST["size"] == "small") {
	echo "<a href=\"javascript:self.close()\" target=\"_self\">".$lang["close_window"]."</a>\n";
} else { 
	include "includes/top_nav.php";
}

?>
</div>

<h4>
<span id="leftnav_arrow"><a href="javascript:LeftNavShowHide('0')" style="color:white; text-decoration:none;"><img src="../images/arrow_left.gif" border="0"></a></span> 
<font color="#000000">Calendar</font></h4>
<a href="../welcome.php">Home</a>
</div>
<div class="nav">
<?php include "includes/nav.php"; ?>
</div>
*/ ?>
<?php
$show_leftnav_arrow = " style='display:none;'";
if($calendar_type=="Operations" || $calendar_type=="Programming") $show_leftnav_arrow = "";
?>
<div class="content">
<span id="leftnav_arrow"<?=$show_leftnav_arrow?>><a href="javascript:LeftNavShowHide('0', '1')" style="color:white; text-decoration:none;"><img src="../images/arrow_left.gif" border="0"></a></span>
<?php if ($msg) echo "<p class=\"warning\">".$msg."</p>\n"; ?>
