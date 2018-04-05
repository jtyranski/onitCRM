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
	

	

	

	// need check for programming calendar flag, and new category
	$sql = "SELECT a.event_id, a.ro_user_id, c.color, concat(e.firstname, ' ', e.lastname) as fullname,
	a.what, a.act_id, a.title, a.description, a.complete_date, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty
	from supercali_events a, supercali_dates b, supercali_categories c, users e
	where a.event_id=b.event_id and 
	a.category_id=c.category_id
	and a.ro_user_id = e.user_id
	and e.master_id=1
	and e.programming_schedule=1
	and c.calendar_type='Programming'
	and a.complete=0
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
	  //echo $complete_pretty . " ";
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
<option value="ProgrammingDev">Programming Dev Complete</option>
<option value="ProgrammingIncomplete" selected="selected">Programming Incomplete</option>
</select>
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
    url = "programming_pop.php?event_id=" + x;
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
  <form action="#" name="programmingform" enctype="multipart/form-data" method="post">
  <div id="pform">
  </div>
  
  </form>
</div>
<?php
include "includes/footer.php";
?>
