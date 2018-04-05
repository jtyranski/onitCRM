<?php
/*
<?xml version="1.0" encoding="utf-8"?>
<module>
        <name>Month View</name>
        <author>Dana C. Hutchins</author>
        <url>http://supercali.inforest.com/</url>
        <version>1.0.0</version>
        <link_name>Month</link_name>
        <description>The original month calendar grid</description>
        <image></image>
		<install_script></install_script>     
</module>
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


function showGantt ($calmonth,$calyear) {

}

include "includes/header.php";

$wd = date('w') ;
$daterange = "1=1";
$searchby = $_GET['searchby'];
if($searchby=="") $searchby = "thisweek";

// this week
switch($searchby){
  case "todate":{
    $daterange = "1=1";
	break;
  }
  case "today":{
    $daterange = "date = curdate()";
	break;
  }
case "thisweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'date >= date_add(curdate(), interval 0 DAY) and date <= date_add(curdate(), interval 6 DAY)';
    break;
  }

  case 2: {
    $daterange = 'date >= date_add(curdate(), interval -1 DAY) and date <= date_add(curdate(), interval 5 DAY)';
    break;
  }

  case 3: {
    $daterange = 'date >= date_add(curdate(), interval -2 DAY) and date <= date_add(curdate(), interval 4 DAY)';
    break;
  }

  case 4: {
    $daterange = 'date >= date_add(curdate(), interval -3 DAY) and date <= date_add(curdate(), interval 3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'date >= date_add(curdate(), interval -4 DAY) and date <= date_add(curdate(), interval 2 DAY)';
    break;
  }

  case 6: {
    $daterange = 'date >= date_add(curdate(), interval -5 DAY) and date <= date_add(curdate(), interval 1 DAY)';
    break;
  }

  case 0: {
    $daterange = 'date >= date_add(curdate(), interval -6 DAY) and date <= date_add(curdate(), interval 0 DAY)';
    break;
  }
}
// end this week
break;
}

case "lastweek":{
// last week
switch ($wd)
{

  case 1: {
    $daterange = 'date >= date_add(curdate(), interval -7 DAY) and date <= date_add(curdate(), interval -1 DAY)';
    break;
  }

  case 2: {
    $daterange = 'date >= date_add(curdate(), interval -8 DAY) and date <= date_add(curdate(), interval -2 DAY)';
    break;
  }

  case 3: {
    $daterange = 'date >= date_add(curdate(), interval -9 DAY) and date <= date_add(curdate(), interval -3 DAY)';
    break;
  }

  case 4: {
    $daterange = 'date >= date_add(curdate(), interval -10 DAY) and date <= date_add(curdate(), interval -4 DAY)';
    break;
  }

  case 5: {
    $daterange = 'date >= date_add(curdate(), interval -11 DAY) and date <= date_add(curdate(), interval -5 DAY)';
    break;
  }

  case 6: {
    $daterange = 'date >= date_add(curdate(), interval -12 DAY) and date <= date_add(curdate(), interval -6 DAY)';
    break;
  }

  case 0: {
    $daterange = 'date >= date_add(curdate(), interval -13 DAY) and date <= date_add(curdate(), interval -7 DAY)';
    break;
  }
}
// end last week
break;
}

case "nextweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'date >= date_add(curdate(), interval 6 DAY) and date <= date_add(curdate(), interval 12 DAY)';
    break;
  }

  case 2: {
    $daterange = 'date >= date_add(curdate(), interval 5 DAY) and date <= date_add(curdate(), interval 11 DAY)';
    break;
  }

  case 3: {
    $daterange = 'date >= date_add(curdate(), interval 4 DAY) and date <= date_add(curdate(), interval 10 DAY)';
    break;
  }

  case 4: {
    $daterange = 'date >= date_add(curdate(), interval 3 DAY) and date <= date_add(curdate(), interval 9 DAY)';
    break;
  }

  case 5: {
    $daterange = 'date >= date_add(curdate(), interval 2 DAY) and date <= date_add(curdate(), interval 8 DAY)';
    break;
  }

  case 6: {
    $daterange = 'date >= date_add(curdate(), interval 1 DAY) and date <= date_add(curdate(), interval 7 DAY)';
    break;
  }

  case 0: {
    $daterange = 'date >= date_add(curdate(), interval 0 DAY) and date <= date_add(curdate(), interval 6 DAY)';
    break;
  }
}
// end next week
break;
}



  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "date like '$timesearch%'";
	break;
  }
  
  case "lastmonth":{
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
	$timesearch = date("Y-m", $lastmonth);
	$daterange = "date like '$timesearch%'";
	break;
  }
  
} // end switch of searchby

?>
  
<?php
if($calendar_type=="Sales"){ 
?>
<script>
function load_search(x){
  y = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>&searchby=" + y;
}
</script>



<select name="searchby" onchange="load_search(this)">
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected";?>>This Week</option>
<option value="lastweek"<?php if($searchby=="lastweek") echo " selected";?>>Last Week</option>
<option value="nextweek"<?php if($searchby=="nextweek") echo " selected";?>>Next Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected";?>>This Month</option>
<option value="lastmonth"<?php if($searchby=="lastmonth") echo " selected";?>>Last Month</option>
<option value="todate"<?php if($searchby=="todate") echo " selected";?>>To Date</option>
</select>
<br>


<?php
$sql = "SELECT a.what, count(a.what) as count, sum(a.value) as total from supercali_events a, supercali_dates b 
where a.event_id=b.event_id and a.what != '' and a.what != 'O' and $daterange group by a.what";
$result = executequery($sql);
//echo "<!-- $sql -->\n";
?>
<table style="border:1px solid #000000;" bgcolor="#FFFFFF" width="300">
<?php
while($record = go_fetch_array($result)){
  $what = $record['what'];
  switch($what){
    case "BP":{
	  $what_display = $BP;
	  $total_display = "$" . number_format($record['total'], 2);
	  break;
	}
	case "I":{
	  $what_display = $I;
	  $total_display = "";
	  break;
	}
	case "RRP":{
	  $what_display = $RRP;
	  $total_display = "";
	  break;
	}
	case "UM":{
	  $what_display = $UM;
	  $total_display = "";
	  break;
	}
  }
  ?>
  <tr>
  <td><?=$what_display?></td>
  <td><?=$record['count']?></td>
  <td><?=$total_display?></td>
  </tr>
  <?php
}
?>
</table>
<?php
} // end if calendar type is sales

if($calendar_type=="Operations"){
  $sql = "SELECT a.amount, a.bid_id
  from opportunities a
  where 
  a.status='Sold' 
  and a.prodstat_id != 0
  and a.bid_id != 0
  and a.display=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $amount = $record['amount'];
	$bid_id = $record['bid_id'];
	
	$sql = "SELECT opm_id from opm where bid_id='$bid_id'";
	$opm_id = getsingleresult($sql);
	
	$percent = 0;
	if($opm_id){
	  $sql = "SELECT percent_field from opm where opm_id='$opm_id'";
      $percent_field = getsingleresult($sql);
	  if($percent_field=="") $percent_field = "field_1";
	  $sql = "SELECT sqft from opm where opm_id='$opm_id'";
      $sqft = getsingleresult($sql);
      if($sqft==0) $sqft = 1;
      $sql = "SELECT sum($percent_field) from opm_entry where opm_id='" . $opm_id . "' and approved=1";
      $done = getsingleresult($sql);
      $percent = round(($done / $sqft) * 100);
	}
	$subtotal = $percent * $amount;
	$total += $subtotal;
  }
  ?>
  <table style="border:1px solid #000000;" bgcolor="#FFFFFF" width="300">
  <tr>
  <td>Produced</td>
  <td align="right">$<?=number_format($total, 2)?></td>
  </tr>
  </table>
  <?php
} // end if calendar type is operations
?>
<div align="right" id="debug" style="color:white; display:none;"></div>
<script>
function ShowHide(id, count, on){
  if(on==1){
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '" + count + "', '0')\" class=\"ganttlink\">-</a>";
    for(i=1;i<=count;i++){
	  document.getElementById(id + "[" + i + "]").style.display="";
	  document.getElementById("data_" + id + "[" + i + "]").style.display="";
	}
	
	
  }
  else {
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '" + count + "', '1')\" class=\"ganttlink\">+</a>";
    for(i=1;i<=count;i++){
	  document.getElementById(id + "[" + i + "]").style.display="none";
	  document.getElementById("data_" + id + "[" + i + "]").style.display="none";
	}
	
  }
}
</script>
<?php

//echo $sql;
$thismonth = $y."-".$m;
$nextmonth =  $next["month"]["y"]."-".$next["month"]["m"];
grab($thismonth."-01",$nextmonth."-01",$c);
//showMonth($m,$y);
showGantt($m,$y);
?>
<style>
.drag {cursor: move;}
</style>
	<script language="JavaScript" type="text/javascript"> 
<!--
 
// this is simply a shortcut for the eyes and fingers
function $(id)
{
	return document.getElementById(id);
}
 
var _startX = 0;			// mouse starting positions
var _startY = 0;
var _offsetX = 0;			// current element offset
var _offsetY = 0;
var _dragElement;			// needs to be passed from OnMouseDown to OnMouseMove
var _oldZIndex = 0;			// we temporarily increase the z-index during drag
var _debug = $('debug');	// makes life easier
 
 
InitDragDrop();
 
function InitDragDrop()
{
	document.onmousedown = OnMouseDown;
	document.onmouseup = OnMouseUp;
}
 
function OnMouseDown(e)
{
	// IE is retarded and doesn't pass the event object
	if (e == null) 
		e = window.event; 
	
	// IE uses srcElement, others use target
	var target = e.target != null ? e.target : e.srcElement;
	
	/*
	_debug.innerHTML = target.className == 'drag' 
		? 'draggable element clicked' 
		: 'NON-draggable element clicked';
 */
	// for IE, left click == 1
	// for Firefox, left click == 0
	if ((e.button == 1 && window.event != null || 
		e.button == 0) && 
		target.className == 'drag')
	{
		// grab the mouse position
		_startX = e.clientX;
		_startY = e.clientY;
		
		// grab the clicked element's position
		_offsetX = ExtractNumber(target.style.left);
		_offsetY = ExtractNumber(target.style.top);
		
		// bring the clicked element to the front while it is being dragged
		_oldZIndex = target.style.zIndex;
		target.style.zIndex = 10000;
		
		// we need to access the element in OnMouseMove
		_dragElement = target;
 
		// tell our code to start moving the element with the mouse
		document.onmousemove = OnMouseMove;
		
		// cancel out any text selections
		document.body.focus();
		
		// prevent text selection in IE
		document.onselectstart = function () { return false; };
		// prevent IE from trying to drag an image
		target.ondragstart = function() { return false; };
		
		// prevent text selection (except IE)
		return false;
	}
}
 
function ExtractNumber(value)
{
	var n = parseInt(value);
	
	return n == null || isNaN(n) ? 0 : n;
}
 
function OnMouseMove(e)
{
	if (e == null) 
		var e = window.event; 
 
	// this is the actual "drag code"
	_dragElement.style.left = (_offsetX + e.clientX - _startX) + 'px';
	//_dragElement.style.top = (_offsetY + e.clientY - _startY) + 'px';
	
	//_debug.innerHTML = '(' + _dragElement.style.left + ', ' + _dragElement.style.top + ')';	
}
 
function OnMouseUp(e)
{
	if (_dragElement != null)
	{
		_dragElement.style.zIndex = _oldZIndex;
 
		// we're done with these events until the next OnMouseDown
		document.onmousemove = null;
		document.onselectstart = null;
		_dragElement.ondragstart = null;
		
		x = _dragElement.style.left;
		id = _dragElement.id;
 
		// this is how we know we're not dragging
		_dragElement = null;
		
		document.location.href= "moveone.php?show_userid=<?=$show_userid?>&show_what=<?=$show_what?>&show_type=<?=$show_type?>&show_crew=<?=$show_crew?>&show_complete=<?=$show_complete?>&month=<?=$m?>&year=<?=$y?>&event_id=" + id + "&x=" + x;
		
		//_debug.innerHTML = 'mouse up';
	}
}
//-->
	</script> 

<?php
include "includes/footer.php";
?>
