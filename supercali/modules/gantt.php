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
function MonthDays($someMonth, $someYear)
    {
        return date("t", strtotime($someYear . "-" . $someMonth . "-01"));
    }

function showGantt ($calmonth,$calyear) {
	global $week_titles, $o, $m, $a, $y, $w, $c, $next, $prev,$ly, $lm, $le, $la;
	/* determine total number of days in a month */
	
	global $show_userid;
	global $show_what;
	global $show_type;
	global $show_crew;
	global $show_complete;
	global $ipad_uplink;
	global $SESSION_MASTER_ID;
	global $whichcalendar;
	global $SESSION_USE_GROUPS, $SESSION_GROUPS, $SESSION_SUBGROUPS, $SESSION_GROUP_PROSPECT_ID;
	$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  for($x=0;$x<sizeof($SESSION_GROUP_PROSPECT_ID);$x++){
    $pg .= $SESSION_GROUP_PROSPECT_ID[$x] . ",";
  }
  $pg = go_reg_replace("\,$", "", $pg);
  $groups_clause = " f.prospect_id in ($pg) ";
}
	
	/*
	$show_what = 0;
	if($show_what != ""){
	  $show_what_clause = "a.ro_user_id = '$show_what' ";
	}
	else {
	  $show_what_clause = " 1=1 ";
	}
	
	if($show_type != ""){
	  $show_type_table = ", d.properties";
	  if($show_type=="Manville"){
	    $show_type_clause = " a.property_id = d.property_id and (d.property_type='Manville' or d.property_type='Beazer B') ";
	  }
	  else {
	    $show_type_clause = " a.property_id = d.property_id and d.property_type='$show_type' ";
	  }
	}
	else {
	  $show_type_clause = " 1=1 ";
	  $show_type_table = "";
	}
	
	if($show_crew != ""){
      $show_crew_clause = " a.operations_crew='$show_crew' ";
	}
	else {
	  $show_crew_clause = " 1=1 ";
	}
	
	if($show_complete){
	  $show_complete_clause = " 1=1 ";
	}
	else {
	  $show_complete_clause = " a.complete=0 ";
	}
	$cats = implode(",",$show_userid);
	
	
	
	
	$cats_clause = "a.category_id in (".$cats.") ";
	$jw_clause = "a.ro_user_id != 1 ";
	//$jw_clause = " 1=1 ";
	*/
	
	if(date("m-Y") == $calmonth . "-" . $calyear){
	  $firstday = date("j");
	}
	else {
	  $firstday = 1;
	}
	$firstday = 1;

	if($firstday < 15){
	  $threemonth = $calmonth + 2;
	  $threeyear = $calyear;
	  if($threemonth > 12){
	    $threemonth -= 12;
	    $threeyear += 1;
	  }
	  $threeday = MonthDays($threemonth, $threeyear);
	  $cal_end = date("Y-m-d", mktime(0, 0, 0, $threemonth, $threeday, $threeyear));
	  $cal_begin = date("Y-m-d", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	  $daterange = "b.date >= '$calyear-$calmonth-$firstday' and b.date <= '$threeyear-$threemonth' ";
	  $showmonths = 3;
	}
	else {
	  $threemonth = $calmonth + 3;
	  $threeyear = $calyear;
	  if($threemonth > 12){
	    $threemonth -= 12;
	    $threeyear += 1;
	  }
	  $threeday = MonthDays($threemonth, $threeyear);
	  $cal_end = date("Y-m-d", mktime(0, 0, 0, $threemonth, $threeday, $threeyear));
	  $cal_begin = date("Y-m-d", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	  $daterange = "b.date >= '$calyear-$calmonth-$firstday' and b.date <= '$threeyear-$threemonth' ";
	  $showmonths = 4;
	}
	
	$sql = "SELECT a.event_id, a.ro_user_id, a.operations_crew, c.color, concat(e.firstname, ' ', e.lastname) as fullname, a.property_id,
	a.what, a.act_id, a.repeat_type, a.leak_id
	from supercali_events a, supercali_dates b, supercali_categories c, users e, prospects f
	where a.event_id=b.event_id and 
	a.category_id=c.category_id and
	$daterange
	and a.ro_user_id = e.user_id
	and a.prospect_id = f.prospect_id
	and c.calendar_type='$whichcalendar'
	and e.master_id = '" . $SESSION_MASTER_ID . "'
	and $groups_clause
	group by b.event_id
	order by a.ro_user_id, a.operations_crew
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
	  $leak_id = $record['leak_id'];
	  $user_id = $record['ro_user_id'];
	  $crew = stripslashes($record['operations_crew']);
	  $repeat_type = stripslashes($record['repeat_type']);
	  
	  //$sql = "SELECT date from supercali_dates where event_id='$event_id' order by date asc limit 1";
	  //$firstdate = getsingleresult($sql);
	  
	  
	  if($repeat_type=="Weekly" || $repeat_type=="Monthly" || $repeat_type=="Yearly"){
	    $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='$event_id' and date >= '" . date("Y-m-d") . "' order by date asc limit 1";
	    $start_date = getsingleresult($sql);
	    $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='$event_id' and date >= '" . date("Y-m-d") . "' order by date asc limit 1";
		$end_date = getsingleresult($sql);
	  }
	  else {
	    $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='$event_id' order by date asc limit 1";
	    $start_date = getsingleresult($sql);
	    $sql = "SELECT date_format(date, \"%Y-%m-%d\") from supercali_dates where event_id='$event_id' order by date desc limit 1";
		$end_date = getsingleresult($sql);
	  }
	  
	  $firstdate = $start_date;
	  if($end_date > $cal_end){
		$end_date = $cal_end;
	  }
	  $canmove = 1;
	  if($start_date < $cal_begin){
		$start_date = $cal_begin;
		$canmove = 0;
	  }
	  
	  
	  
	  $row[$counter]['event_id'] = $event_id;
	  $row[$counter]['color'] = $color;
	  $row[$counter]['name'] = $name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['what'] = $what;
	  $row[$counter]['act_id'] = $act_id;
	  $row[$counter]['user_id'] = $user_id;
	  $row[$counter]['crew'] = $crew;
	  $row[$counter]['firstdate'] = $firstdate;
	  $row[$counter]['start_date'] = $start_date;
	  $row[$counter]['end_date'] = $end_date;
	  $row[$counter]['canmove'] = $canmove;
	  $row[$counter]['leak_id'] = $leak_id;
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
	  $data_crew[$key] = $row2['crew'];
	  $data_firstdate[$key] = $row2['firstdate'];
	  $data_startdate[$key] = $row2['start_date'];
	  $data_enddate[$key] = $row2['end_date'];
	  $data_canmove[$key] = $row2['canmove'];
	  $data_leak_id[$key] = $row2['leak_id'];
	}
	
	array_multisort($data_user_id, SORT_ASC, $data_firstdate, SORT_ASC, $row);
	}
	$dh = "18px"; // div height
	$dh2 = "9px"; // div height 2, space between new users
	$dh3 = "26px"; // div height 3, the month name row
	$dw = "25px"; // div width
	$dw_val = 25; // div width, just number, plus 2 for border
	
	
	$totaldays = 0;

	$daysmonth1 = MonthDays($calmonth, $calyear) - $firstday + 1;
	
	$nextmonth = $calmonth +1;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth2 = MonthDays($nextmonth, $nextmonthyear);
	
	$nextmonth = $calmonth +2;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth3 = MonthDays($nextmonth, $nextmonthyear);
	
	$nextmonth = $calmonth +3;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth4 = MonthDays($nextmonth, $nextmonthyear);
	

	
	
	$header_month1 = $daysmonth1;
	$header_month2 = $daysmonth2;
	$header_month3 = $daysmonth3;
	$header_month4 = $daysmonth4;
	
	
	
	$header_month1 = (($header_month1 * $dw_val) + (2 * $header_month1)) - 2;
	$header_month2 = (($header_month2 * $dw_val) + (2 * $header_month2)) - 2;
	$header_month3 = (($header_month3 * $dw_val) + (2 * $header_month3)) - 2;
	$header_month4 = (($header_month4 * $dw_val) + (2 * $header_month4)) - 2;
	
	$month_info[1]['days'] = $daysmonth1;
	$month_info[2]['days'] = $daysmonth2;
	$month_info[3]['days'] = $daysmonth3;
	$month_info[4]['days'] = $daysmonth4;
	
	$month_info[1]['header'] = $header_month1;
	$month_info[2]['header'] = $header_month2;
	$month_info[3]['header'] = $header_month3;
	$month_info[4]['header'] = $header_month4;
	
	for($x=1;$x<=$showmonths;$x++){
	  $totaldays += $month_info[$x]['days'];
	}
	$day_abbr[0] = "Su";
	$day_abbr[1] = "M";
	$day_abbr[2] = "Tu";
	$day_abbr[3] = "W";
	$day_abbr[4] = "Th";
	$day_abbr[5] = "F";
	$day_abbr[6] = "Sa";
	
	$right_side_width = ($totaldays) * ($dw_val + 2);
	$name_width = 310;
	$name_width_px = $name_width . "px";
	$left_side_width = $name_width + 34;
	//$right_side_display_width = 750;
	$right_side_display_width = $right_side_width;
	$total_width = $left_side_width + $right_side_display_width;
	
	// number of divs in the month, times the width.  Then add 2 times number of divs for border widths.  Subtract 2 for month name borders
	?>
	<div style="width:<?=$total_width?>px; position:relative; background-color:#FFFFFF;" id="gantt_total">
    <div style="float:left; width:<?=$left_side_width?>px; background-color:#FFFFFF;">
	
	<?php
	echo "<div style='position:relative; background-color:#FFFFFF; height:$dh3;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh3;'>&nbsp;</div>
	<div style='width:30px; background-color:#FFFFFF; float:right; border:1px solid black; height:$dh3;'></div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "<div style='position:relative; height:$dh;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh;'>&nbsp;</div>
	<div style='width:30px; background-color:#FFFFFF; float:right; border:1px solid black; height:$dh;'>#</div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "<div style='position:relative; height:$dh;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh;'>&nbsp;</div>
	<div style='width:30px; background-color:#FFFFFF; float:right; border:1px solid black; height:$dh;'>Days</div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	
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
	  $crew = $row[$foo]['crew'];
	  $leak_id = $row[$foo]['leak_id'];
	  
	  switch($what){
	  case "O":{
	  	$url = "\"show_event.php?id=".$event_id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$event_id."&size=small','pop','650','600'); window.newWindow.focus(); return false\"";
	    
		$sql = "SELECT regarding from activities where act_id='$act_id'";
		$regarding = stripslashes(getsingleresult($sql));
	    if(strlen($regarding) > 40) $regarding = substr($regarding, 0, 40) . "...";
		$show_title = $regarding;
		break;
	  }
	  case "SD":{
	    $url = "../toolbox.php?go=fcs_sd_report_view2.php*leak_id=$leak_id";
		
		$sql = "SELECT site_name from properties where property_id='$property_id'";
	    $site_name = stripslashes(getsingleresult($sql));
	    if(strlen($site_name) > 40) $site_name = substr($site_name, 0, 40) . "...";
		$sql = "SELECT invoice_id from am_leakcheck where leak_id='$leak_id'";
		$invoice_id = getsingleresult($sql);
		$show_title = "(" . $invoice_id . ") " . $site_name;
		
		break;
	  }
	  default: {
		if($property_id){
		  $url = "'../" . $ipad_uplink . "view_property.php?property_id=$property_id&view=activities'";
		}
		else {
		  $url = "'../" . $ipad_uplink . "view_prospect.php?prospect_id=$prospect_id&view=activities'";
		}
		
		$sql = "SELECT site_name from properties where property_id='$property_id'";
	    $site_name = stripslashes(getsingleresult($sql));
	    if(strlen($site_name) > 40) $site_name = substr($site_name, 0, 40) . "...";
		$show_title = $site_name;
		
		break;
	  }
	  }
	  

	  
	  $sql = "SELECT count(*) from supercali_dates where event_id='$event_id'";
	  $num_days = getsingleresult($sql);
	  if($name != $oldname){
	    if($oldname != ""){
	      echo "<div style='position:relative; height:$dh2;'>
		  <div style='width:$name_width_px; float:left; height:$dh2; border:1px solid black;'>&nbsp;</div>
		  <div style='width:30px; float:right; height:$dh2; border:1px solid black;'>&nbsp;</div>
		  </div>";
		  echo "<div style='clear:both;'></div>";
		}
	    echo "<div style='position:relative; height:$dh;'>";
		echo "<div style='width:$name_width_px; float:left; border:1px solid black; height:$dh; z-index:100; position:relative;'><strong>$name</strong>";
		//echo "&nbsp; <a href=\"javascript:MoveAll('$user_id', 0)\" alt=\"Move All Back\"><img src=\"images/left.gif\" border='0'></a>";
		//echo "&nbsp; <a href=\"javascript:MoveAll('$user_id', 1)\" alt=\"Move All Forward\"><img src=\"images/right.gif\" border='0'></a>";
		echo "</div>";
		echo "<div style='width:30px; float:right; border:1px solid black; height:$dh;'></div>
		</div>";
		echo "<div style='clear:both;'></div>";
		$oldname = $name;
		$oldcrew = "";
	  }
	  
	  if($oldcrew != $crew){
	    $crew_id = str_replace(" ", "", $crew);
		$sql = "SELECT count(*) from supercali_events a, supercali_dates b where a.operations_crew='$crew' and a.ro_user_id='$user_id' 
		and b.date like '$calyear-$calmonth%' and a.event_id=b.event_id";
		$count = getsingleresult($sql);
	    echo "<div style='position:relative; height:$dh;'>";
		echo "<div style='width:$name_width_px; float:left; border:1px solid black; height:$dh; z-index:100; position:relative;'>";
		echo "&nbsp; &nbsp;<span id='arrow_" . $user_id . "_" . $crew_id . "'><a href=\"javascript:ShowHide('" . $user_id . "_" . $crew_id . "', '$count', '0')\" class=\"ganttlink\">-</a></span>&nbsp;";
		echo "$crew</div>";
		echo "<div style='width:30px; float:right; border:1px solid black; height:$dh;'></div>
		</div>";
		echo "<div style='clear:both;'></div>";
		$oldcrew = $crew;
		
	  }
	  
	  echo "<div style='position:relative; height:$dh;";
	  //if($crew) echo " display:none;";
	  echo "'";
	  if($crew) {
	    $counter++;
		echo " id='" . $user_id . "_" . $crew_id . "[$counter]'";
	  }
	  echo ">";
	  echo "<div style='width:$name_width_px; float:left; border:1px solid black; height:$dh;'>";
	  echo "<div style=\"width:100%; position:relative; height:$dh;\">";
	  echo "<div style=\"float:left; height:$dh; z-index:100; position:relative;\">";
	  //echo "<a href=\"javascript:DelEvent('" . $event_id . "')\" style=\"color:red;text-decoration:none;\">x</a> ";
	  echo "<a href=" . $url . " class=\"ganttlink\" target=\"_top\">$show_title</a>";
	  echo "</div>";
	  echo "<div style=\"float:right; height:$dh; z-index:100; position:relative;\">";
	  //echo "<a href=\"javascript:EndDate('$event_id', '0')\" style=\"color:red;text-decoration:none;\">-</a>";
	  //echo " ";
	  //echo "<a href=\"javascript:EndDate('$event_id', '1')\" style=\"color:green;text-decoration:none;\">+</a>";
	  echo "</div>";
	  echo "</div>";
	  echo "</div>";
	  echo "<div style='width:30px; float:right; border:1px solid black; height:$dh;' align='center'>$num_days</div>
	  </div>";
	  echo "<div style='clear:both;'></div>";
	  	}
	
	echo "<div style='position:relative; height:$dh;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh;'>&nbsp;</div>
	<div style='width:30px; background-color:#FFFFFF; float:right; border:1px solid black; height:$dh;'>#</div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "<div style='position:relative; height:$dh;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh;'>&nbsp;</div>
	<div style='width:30px; background-color:#FFFFFF; float:right; border:1px solid black; height:$dh;'>Days</div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "<div style='position:relative; background-color:#FFFFFF; height:$dh3;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh3;'>&nbsp;</div>
	<div style='width:30px; background-color:#FFFFFF; float:right; border:1px solid black; height:$dh3;'></div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	?>
	</div>
	<?php // ***********  START OF RIGHT DIV, WITH ALL THE DATES ************************************************* ?>
	<div style="float:right; width:<?=$right_side_display_width?>px; overflow:auto;" id="gantt_rs_display">
	<div style="width:<?=$right_side_width?>px; background-color:#FFFFFF;">
	
	
	<div style="position:relative; width:100%;">
	<?php
	$prev_click = '<a href="' . $PHP_SELF . '?o=' . $o . '&w=' . $w . '&c=' . $c . '&m=' . $prev["month"]["m"] . '&a=1&y=' . $prev["month"]["y"] . '">&lt;</a> ';
	$next_click = ' <a href="' . $PHP_SELF . '?o=' . $o . '&w=' . $w . '&c=' . $c . '&m=' . $next["month"]["m"] . '&a=1&y=' . $next["month"]["y"] . '">&gt;</a>';
	for($x=1;$x<=$showmonths;$x++){
	  $z = $x - 1;
	  $header_display = $prev_click . date('F', mktime(0,0,0,$calmonth + $z,1,$calyear)) . '&nbsp;' . date('Y', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] < 10) $header_display = $prev_click . date('M', mktime(0,0,0,$calmonth + $z,1,$calyear)) . '&nbsp;' . date('Y', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] < 5) $header_display = $prev_click . date('M', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] <=2) $header_display = "";
	  ?>
	  <div style="border:1px solid black; height:<?=$dh3?>; width:<?=$month_info[$x]['header']?>px; float:left; background-color:#CCCCCC;" class="cal_top">
	  <?=$header_display?>
	  </div>
	  <?php
	}
	?>
	
	</div>
	<div style="clear:both;"></div>
<?php
    $bar = date("w", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	echo "<div style='position:relative; height:$dh;'>";
	for($x=1;$x<=$totaldays;$x++){
	  echo "<div style='background-color:white; width:$dw; border:1px solid black; float:left; height:$dh;' align='center'>" . $day_abbr[$bar] . "</div>";
	  $bar++;
	  if($bar > 6) $bar = 0;
	}

	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	echo "<div style='position:relative; height:$dh;'>";

	$day=$firstday - 1;
	$month = $calmonth;
	$year = $calyear;
	for($x=1;$x<=$totaldays;$x++){
	  $day++;
	  if(!(checkdate($month, $day, $year))){
	    $day = 1;
		$month++;
		if($month > 12){
		  $month=1;
		  $year++;
		}
	  }
	  
	  $bg = "white";
	  if("$day - $month"==date("j - n")) $bg = "#FFFF99";
	  echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh;' align='center'>$day</div>\n";
	}

	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	
	  
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
	  $crew = $row[$foo]['crew'];
	  $start_date = $row[$foo]['start_date'];
	  $end_date = $row[$foo]['end_date'];
	  $canmove = $row[$foo]['canmove'];
	  
	  
	  $sql = "SELECT count(*) from supercali_dates where event_id='$event_id'";
	  $num_days = getsingleresult($sql);
	  if($name != $oldname){
	    if($oldname != ""){
	      echo "<div style='position:relative; height:$dh2;'>";

		  $day=$firstday - 1;
	      $month = $calmonth;
	      $year = $calyear;
		  for($x=1;$x<=$totaldays;$x++){
		    $day++;
	        if(!(checkdate($month, $day, $year))){
	          $day = 1;
		      $month++;
		      if($month > 12){
		        $month=1;
		        $year++;
		      }
	        }
	  
	        $bg = "white";
	        if("$day - $month"==date("j - n")) $bg = "#FFFF99";
		    echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh2;'>&nbsp;</div>\n";
		  }

	      echo "</div>";
		  echo "<div style='clear:both;'></div>";
		}
	    echo "<div style='position:relative; height:$dh;'>";


		$day=$firstday - 1;
	    $month = $calmonth;
	    $year = $calyear;
		for($x=1;$x<=$totaldays;$x++){
		  $day++;
	      if(!(checkdate($month, $day, $year))){
	        $day = 1;
		    $month++;
		    if($month > 12){
		      $month=1;
		      $year++;
		    }
	      }
	  
	      $bg = "white";
	      if("$day - $month"==date("j - n")) $bg = "#FFFF99";
		  echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh;'>&nbsp;</div>\n";
		}
		$oldname = $name;
		$oldcrew = "";

	    echo "</div>";
		echo "<div style='clear:both;'></div>";
	  }
	  
	  if($oldcrew != $crew){
	    $crew_id = str_replace(" ", "", $crew);
		$sql = "SELECT count(*) from supercali_events a, supercali_dates b where a.operations_crew='$crew' and a.ro_user_id='$user_id' 
		and b.date like '$calyear-$calmonth%' and a.event_id=b.event_id";
		$count = getsingleresult($sql);
	    echo "<div style='position:relative; height:$dh;'>";

		$day=$firstday - 1;
	    $month = $calmonth;
	    $year = $calyear;
		for($x=1;$x<=$totaldays;$x++){
		  $day++;
	      if(!(checkdate($month, $day, $year))){
	        $day = 1;
		    $month++;
		    if($month > 12){
		      $month=1;
		      $year++;
		    }
	      }
	  
	      $bg = "white";
	      if("$day - $month"==date("j - n")) $bg = "#FFFF99";
		  echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh;'>&nbsp;</div>\n";
		}
		$oldcrew = $crew;
		$counter = 0;

	    echo "</div>";
		echo "<div style='clear:both;'></div>";
	  }
	  
	  echo "<div style='position:relative; height:$dh;";
	  //if($crew) echo " display:none;";
	  echo "'";
	  if($crew) {
	    $counter++;
		echo " id='data_" . $user_id . "_" . $crew_id . "[$counter]'";
	  }
	  echo ">";

	  $day=$firstday - 1;
	  $month = $calmonth;
	  $year = $calyear;
	  for($x=1;$x<=$totaldays;$x++){
	    $day++;
	    if(!(checkdate($month, $day, $year))){
	      $day = 1;
		  $month++;
		  if($month > 12){
		    $month=1;
		    $year++;
		  }
	    }
		//if($day<=9) $day = "0" . $day;
	    //$sql = "SELECT count(*) from supercali_dates where event_id='$event_id' and date like '$year-$month-$day%'";
		//$test = getsingleresult($sql);
		$bgcolor="white";
		//if($test){
		  //$bgcolor=$color;
		//}
		//if($bgcolor=="white"){
	      if("$day - $month"==date("j - n")) $bgcolor = "#FFFF99";
		//}
		
		echo "<div style='background-color:$bgcolor; width:$dw; border:1px solid black; float:left; height:$dh;'>&nbsp;</div>\n";
	  }

	  // kill the above checking date and using color.  At this point we'll grab the date span, and make an absolute positioned
	  // div in this row.
	  $start_span = GetDays($calyear . "-" . $calmonth . "-" . $firstday, $start_date, "+1 day");
	  if(is_array($start_span)){
	    $start_size = sizeof($start_span) - 1;
	  }
	  else {
	    $start_size = 0;
	  }
	  $event_span = GetDays($start_date, $end_date, "+1 day");
	  $event_size = sizeof($event_span);
	  
	  $start_size = (($start_size * $dw_val) + (2 * $start_size));
	  $event_size = (($event_size * $dw_val) + (2 * $event_size));
	  $class = " class=\"drag\" ";
	  if($canmove==0) $class = "";
	  $class = ""; // just move none for now. 4/17/2012 jw
	  echo '<div style="width:' . $event_size . 'px; height:' . $dh . '; background:url(images/box.gif); background-repeat:repeat-x; background-color:' . $color . '; position:absolute; left:' . $start_size . 'px; top:1px; z-index:100;"' .  $class . ' id="' . $event_id . '">&nbsp;</div>';
	  echo "</div>";
	  echo "<div style='clear:both;'></div>";
	}
	?>
	
<?php
    echo "<div style='position:relative; height:$dh;'>";

	$day=$firstday - 1;
	$month = $calmonth;
	$year = $calyear;
	for($x=1;$x<=$totaldays;$x++){
	  $day++;
	  if(!(checkdate($month, $day, $year))){
	    $day = 1;
		$month++;
		if($month > 12){
		  $month=1;
		  $year++;
		}
	  }
	  
	  $bg = "white";
	  if("$day - $month"==date("j - n")) $bg = "#FFFF99";
	  echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh;' align='center'>$day</div>\n";
	}

	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	$bar = date("w", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	echo "<div style='position:relative; height:$dh;'>";
	for($x=1;$x<=$totaldays;$x++){
	  echo "<div style='background-color:white; width:$dw; border:1px solid black; float:left; height:$dh;' align='center'>" . $day_abbr[$bar] . "</div>";
	  $bar++;
	  if($bar > 6) $bar = 0;
	}
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	?>
	<div style="position:relative; width:100%;">
	<?php
	$prev_click = '<a href="' . $PHP_SELF . '?o=' . $o . '&w=' . $w . '&c=' . $c . '&m=' . $prev["month"]["m"] . '&a=1&y=' . $prev["month"]["y"] . '">&lt;</a> ';
	$next_click = ' <a href="' . $PHP_SELF . '?o=' . $o . '&w=' . $w . '&c=' . $c . '&m=' . $next["month"]["m"] . '&a=1&y=' . $next["month"]["y"] . '">&gt;</a>';
	for($x=1;$x<=$showmonths;$x++){
	  $z = $x - 1;
	  $header_display = $prev_click . date('F', mktime(0,0,0,$calmonth + $z,1,$calyear)) . '&nbsp;' . date('Y', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] < 10) $header_display = $prev_click . date('M', mktime(0,0,0,$calmonth + $z,1,$calyear)) . '&nbsp;' . date('Y', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] < 5) $header_display = $prev_click . date('M', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] <=2) $header_display = "";
	  ?>
	  <div style="border:1px solid black; height:<?=$dh3?>; width:<?=$month_info[$x]['header']?>px; float:left; background-color:#CCCCCC;" class="cal_top">
	  <?=$header_display?>
	  </div>
	  <?php
	}
	?>
	
	</div>
	<div style="clear:both;"></div>
	<?php
	echo '</div>';
	echo "</div>";
	echo "</div>";
}

include "includes/header.php";

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
<div style="position:relative;">
<div style="float:left;">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="formchangetype">
<select name="calendar_type" onchange="document.formchangetype.submit()">
<option value="Sales">Sales</option>
<option value="SalesGantt" selected="selected">Gantt</option>
<?php if($SESSION_USE_OPS){ ?>
<option value="Operations">Operations</option>
<?php } ?>
<?php if($SESSION_MASTER_ID==1){ ?>
<option value="Programming">Programming</option>
<option value="ProgrammingComplete">Programming Complete</option>
<option value="ProgrammingDev">Programming Dev Complete</option>
<option value="ProgrammingIncomplete">Programming Incomplete</option>
<?php } ?>
</select>
</form>
</div>

<?php
$whichcalendar = $_GET['whichcalendar'];
if($whichcalendar=="") $whichcalendar = $_SESSION['cali_whichcalendar'];
if($whichcalendar=="") $whichcalendar = "Sales";
$_SESSION['cali_whichcalendar'] = $whichcalendar;
$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
$USING_SD = getsingleresult($sql);
?>
<div style="float:left; padding-left:20px;">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="whichcalendartype">
<select name="whichcalendar" onchange="document.whichcalendartype.submit()">
<option value="Sales"<?php if($whichcalendar=="Sales") echo " selected";?>>Sales</option>
<?php if($USING_SD){ ?>
<option value="Service Dispatch"<?php if($whichcalendar=="Service Dispatch") echo " selected";?>>Service Dispatch</option>
<?php } ?>
</select>
</form>
</div>
</div>
<div style="clear:both;"></div>

<div style="background-color:#FFFFFF; padding:5px 5px 5px 5px; position:relative; height:20px;">
<?php
$sql = "SELECT category_id, name, color, background from supercali_categories where calendar_type='$whichcalendar' order by name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $jw_name = stripslashes($record['name']);
  $jw_color = stripslashes($record['color']);
  $jw_background = stripslashes($record['background']);
  ?>
  <div style="float:left; color:<?=$jw_color?>; padding-left:10px; font-weight:bold;">
  <?=$jw_name?>
  </div>
  <?php
}

?>
</div>
<div style="clear:both;"></div>
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
