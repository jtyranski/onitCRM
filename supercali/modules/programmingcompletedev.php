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


function showComplete ($calmonth,$calyear) {
	global $week_titles, $o, $m, $a, $y, $w, $c, $next, $prev,$ly, $lm, $le, $la;
	/* determine total number of days in a month */
	
	global $show_userid;
	global $show_what;
	global $show_type;
	global $show_crew;
	global $show_complete;
	global $ipad_uplink;
    global $searchby;
	if($searchby=="") $searchby = "todate";
	
	$wd = date('w') ;
$daterange = "1=1";
$forceblack = 0;
// this week
switch($searchby){
  case "todate":{
    $daterange = "1=1";
	$forceblack = 1;
	break;
  }
  case "today":{
    $daterange = "XXX like '" . date("Y") . "-" . date("m") . "-" . date("d") . "%'";
	$forceblack = 1;
	break;
  }
  case "yesterday":{
    $yesterday = date("Y-m-d",mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
	$daterange = "XXX like '" . $yesterday . "%' ";
	break;
  }
case "thisweek":{
switch ($wd)
{

  case 1: {
    $daterange = 'XXX >= date_add(curdate(), interval 0 DAY) and XXX <= date_add(curdate(), interval 6 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -1 DAY) and XXX <= date_add(curdate(), interval 5 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -2 DAY) and XXX <= date_add(curdate(), interval 4 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -3 DAY) and XXX <= date_add(curdate(), interval 3 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -4 DAY) and XXX <= date_add(curdate(), interval 2 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -5 DAY) and XXX <= date_add(curdate(), interval 1 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -6 DAY) and XXX <= date_add(curdate(), interval 0 DAY)';
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
    $daterange = 'XXX >= date_add(curdate(), interval -7 DAY) and XXX <= date_add(curdate(), interval -1 DAY)';
    break;
  }

  case 2: {
    $daterange = 'XXX >= date_add(curdate(), interval -8 DAY) and XXX <= date_add(curdate(), interval -2 DAY)';
    break;
  }

  case 3: {
    $daterange = 'XXX >= date_add(curdate(), interval -9 DAY) and XXX <= date_add(curdate(), interval -3 DAY)';
    break;
  }

  case 4: {
    $daterange = 'XXX >= date_add(curdate(), interval -10 DAY) and XXX <= date_add(curdate(), interval -4 DAY)';
    break;
  }

  case 5: {
    $daterange = 'XXX >= date_add(curdate(), interval -11 DAY) and XXX <= date_add(curdate(), interval -5 DAY)';
    break;
  }

  case 6: {
    $daterange = 'XXX >= date_add(curdate(), interval -12 DAY) and XXX <= date_add(curdate(), interval -6 DAY)';
    break;
  }

  case 0: {
    $daterange = 'XXX >= date_add(curdate(), interval -13 DAY) and XXX <= date_add(curdate(), interval -7 DAY)';
    break;
  }
}
// end last week
break;
}
  case "thismonth":{
	$timesearch = date("Y-m");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	break;
  }
  
  case "lastmonth":{
    $d = date("j");
    $foo = 0;
    if($d==30 || $d==31) $foo = 3;
    $lastmonth = mktime(0, 0, 0, date("m")-1, date("d") - $foo,   date("Y"));
	$timesearch = date("Y-m", $lastmonth);
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	break;
  }
  
  case "ytd":{
	$timesearch = date("Y");
	$daterange = "XXX like '$timesearch%'";
	$forceblack = 1;
	break;
  }
  case "Custom":{
    $custom_startdate = $_GET['custom_startdate'];
	$custom_enddate = $_GET['custom_enddate'];
	$start_parts = explode("/", $custom_startdate);
    $startdate_pretty = $start_parts[2] . "-" . $start_parts[0] . "-" . $start_parts[1];
	$end_parts = explode("/", $custom_enddate);
    $enddate_pretty = $end_parts[2] . "-" . $end_parts[0] . "-" . $end_parts[1];
	$daterange = " XXX >= '$startdate_pretty' and XXX <= '$enddate_pretty' ";
	$forceblack=1;
	break;
  }
  
  default:{
    $daterange = "XXX like '$searchby%'";
	$forceblack = 1;
	break;
  }
  
} // end switch of searchby

$c_daterange = $daterange;
$c_daterange = str_replace("XXX", "a.complete_dev_date", $c_daterange);
	

	

	// need check for programming calendar flag, and new category
	$sql = "SELECT a.event_id, a.ro_user_id, c.color, concat(e.firstname, ' ', e.lastname) as fullname,
	a.what, a.act_id, a.title, a.description, a.complete_date, date_format(a.complete_dev_date, \"%m/%d/%Y\") as complete_pretty
	from supercali_events a, supercali_dates b, supercali_categories c, users e
	where a.event_id=b.event_id and 
	a.category_id=c.category_id
	and a.ro_user_id = e.user_id
	and e.master_id=1
	and e.programming_schedule=1
	and c.calendar_type='Programming'
	and a.complete_dev=1
	and $c_daterange
	group by b.event_id
	order by a.ro_user_id
	";
	$result = executequery($sql);
	$counter = 0;
	while($record = go_fetch_array($result)){
	  $event_id = $record['event_id'];
	  $color = $record['color'];
	  $name = stripslashes($record['fullname']);
	  $property_id = $record['property_id'];
	  $what = $record['what'];
	  $act_id = $record['act_id'];
	  $user_id = $record['ro_user_id'];
	  $title = stripslashes($record['title']);
	  $description = stripslashes($record['description']);
	  $complete_date = stripslashes($record['complete_date']);
	  $complete_pretty = stripslashes($record['complete_pretty']);
	  
	  //$sql = "SELECT date from supercali_dates where event_id='$event_id' order by date asc limit 1";
	  //$firstdate = getsingleresult($sql);
	  
  
	  
	  
	  $row[$counter]['event_id'] = $event_id;
	  $row[$counter]['color'] = $color;
	  $row[$counter]['name'] = $name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['what'] = $what;
	  $row[$counter]['act_id'] = $act_id;
	  $row[$counter]['user_id'] = $user_id;
	  $row[$counter]['title'] = $title;
	  $row[$counter]['description'] = $description;
	  $row[$counter]['firstdate'] = $firstdate;
	  $row[$counter]['start_date'] = $start_date;
	  $row[$counter]['end_date'] = $end_date;
	  $row[$counter]['canmove'] = $canmove;
	  $row[$counter]['complete_date'] = $complete_date;
	  $row[$counter]['complete_pretty'] = $complete_pretty;
	  
	  $counter++;
	}
	
	if(is_array($row)){
	foreach ($row as $key => $row2) {
	  $data_user_id[$key] = $row2['user_id'];
	  $data_event_id[$key] = $row2['event_id'];
	  $data_color[$key] = $row2['color'];
	  $data_name[$key] = $row2['name'];
	  $data_property_id[$key] = $row2['property_id'];
	  $data_what[$key] = $row2['what'];
	  $data_act_id[$key] = $row2['act_id'];
	  $data_title[$key] = $row2['title'];
	  $data_description[$key] = $row2['description'];
	  $data_firstdate[$key] = $row2['firstdate'];
	  $data_startdate[$key] = $row2['start_date'];
	  $data_enddate[$key] = $row2['end_date'];
	  $data_canmove[$key] = $row2['canmove'];
	  $data_complete_date[$key] = $row2['complete_date'];
	  $data_complete_pretty[$key] = $row2['complete_pretty'];
	}
	
	array_multisort($data_user_id, SORT_ASC, $data_complete_date, SORT_ASC, $row);
	}

	
	?>
	<div style="width:100%; color:#000000; background-color:#FFFFFF;">
	<br>
	<?php
	$oldname = "";
	$oldcrew = "";
	$counter = 0;
	for($foo=0;$foo<sizeof($row);$foo++){
	  $event_id = $row[$foo]['event_id'];
	  $color = $row[$foo]['color'];
	  $name = $row[$foo]['name'];
	  $property_id = $row[$foo]['property_id'];
	  $what = $row[$foo]['what'];
	  $act_id = $row[$foo]['act_id'];
	  $user_id = $row[$foo]['user_id'];
	  $title = $row[$foo]['title'];
	  $description = $row[$foo]['description'];
	  $complete_pretty = $row[$foo]['complete_pretty'];
	  
	  //if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  $url = "\"javascript:load_programming('$event_id')\"";
	  
	  $sql = "SELECT count(*) from supercali_dates where event_id='$event_id'";
	  $num_days = getsingleresult($sql);
	  if($name != $oldname){
	    if($oldname != ""){
		  echo "<br><br>";
		}
		echo "<strong>$name</strong>";
		echo "<br>";
		$oldname = $name;
		$oldcrew = "";
	  }
	  

	  
	  
	  echo "<a href=\"javascript:DelEvent('" . $event_id . "')\" style=\"color:red;text-decoration:none;\">x</a> &nbsp;\n";
	  echo $complete_pretty . " ";
	  echo "<a href=" . $url . " class=\"ganttlink\">$title</a>\n";
	  echo "<br>";

	}
    echo "</div>";
}


include "includes/header.php";


?>
  
<div id="debug" style="color:white; width:100%; display:none;"></div>
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
<div>
<?php $searchby = $_GET['searchby']; 
if($searchby=="") $searchby="thisweek";
?>
<?php if($SESSION_MASTER_ID==1){ ?>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="formchangetype">
<select name="calendar_type" onChange="document.formchangetype.submit()">
<option value="Sales">Sales</option>
<option value="Operations">Operations</option>
<option value="Programming">Programming</option>
<option value="ProgrammingComplete">Programming Complete</option>
<option value="ProgrammingDev" selected="selected">Programming Dev Complete</option>
<option value="ProgrammingIncomplete">Programming Incomplete</option>
</select>
<br>
<select name="searchby"  onChange="document.formchangetype.submit()">
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected";?>>This Week</option>
<option value="lastweek"<?php if($searchby=="lastweek") echo " selected";?>>Last Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected";?>>This Month</option>
<option value="lastmonth"<?php if($searchby=="lastmonth") echo " selected";?>>Last Month</option>
<option value="todate"<?php if($searchby=="todate") echo " selected";?>>All</option>
</select>
</form>
<?php } ?>
</div>
<br>
<?php

//echo $sql;
$thismonth = $y."-".$m;
$nextmonth =  $next["month"]["y"]."-".$next["month"]["m"];
grab($thismonth."-01",$nextmonth."-01",$c);
//showMonth($m,$y);
?>
<a href="javascript:load_programming('new')">Add New</a><br>
<?php
showComplete($m,$y);
?>

<script>
function Openwin(x){
  grayOut(true, {'zindex':'50', 'opacity':'25'});
  document.getElementById(x).style.display="block";
}

function Closewin(x){
  grayOut(false);
  document.getElementById(x).style.display="none";
}

function load_programming(x) {
  //alert(x);
  
   
  //alert(contmsg);
    url = "programming_completedev_pop.php?event_id=" + x;
	url=url+"&sid="+Math.random();
	
	//document.getElementById('debug').style.display="";
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

  Openwin('contact');
}

	</script> 
<script src="../includes/grayout.js"></script>
<script src="../includes/calendar.js"></script>
<div id="contact" style="position:absolute; left:100px; top:50px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:600px; height:350px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('contact')">X</a>
  </div>
  <form action="programming_completedev_action.php" name="programmingform" enctype="multipart/form-data" method="post">
  <div id="pform">
  </div>
  
  </form>
</div>
<?php
include "includes/footer.php";
?>
