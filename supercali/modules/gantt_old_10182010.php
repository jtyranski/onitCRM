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
	
	$calday = 0;
	$totaldays = 0;
	while ( checkdate( $calmonth, $calday + 1, $calyear ) ){
	        $totaldays++;
			$calday++;
	}
	$daysmonth1 = MonthDays($calmonth, $calyear);
	
	$nextmonth = $calmonth +1;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}
	$calday = 0;
	while ( checkdate( $nextmonth, $calday + 1, $nextmonthyear ) ){
	        $totaldays++;
			$calday++;
	}
	$daysmonth2 = MonthDays($nextmonth, $nextmonthyear);
	
	$nextmonth = $calmonth +2;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}
	$calday = 0;
	while ( checkdate( $nextmonth, $calday + 1, $nextmonthyear ) ){
	        $totaldays++;
			$calday++;
	}
	$daysmonth3 = MonthDays($nextmonth, $nextmonthyear);
	$startfiller = date( "w", mktime( 0, 0, 0, $calmonth, 1, $calyear ) );
	$endfiller = 105 - ($totaldays + $startfiller);
	
	$header_month1 = $startfiller + $daysmonth1;
	$header_month2 = $daysmonth2;
	$header_month3 = 105 - ($header_month1 + $header_month2);
	
	/* build table */
	echo '<table class="gantt" width="2630">'; 
	echo '<tr><td></td><td></td>
	<th colspan="' . $header_month1 . '" class="cal_top">
	<a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["month"]["m"],'&a=1&y=',$prev["month"]["y"],'">&lt;</a> ',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth,1,$calyear)),' <a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["month"]["m"],'&a=1&y=',$next["month"]["y"],'">&gt;</a>
	</th>
	<th colspan="' . $header_month2 . '" class="cal_top">
	',date('F', mktime(0,0,0,$calmonth +1 ,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth + 1,1,$calyear)),' 
	</th>
	<th colspan="' . $header_month3 . '" class="cal_top">
	',date('F', mktime(0,0,0,$calmonth + 2,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth + 2,1,$calyear)),' 
	</th>
	
	</tr>';

	echo "<tr>";
	echo "<td bgcolor='white'></td>";
	echo "<td bgcolor='white' align='center'>#</td>";
	for($x=1;$x<=15;$x++){
	  echo "<td bgcolor='white'>Su</td>";
	  echo "<td bgcolor='white'>M</td>";
	  echo "<td bgcolor='white'>Tu</td>";
	  echo "<td bgcolor='white'>W</td>";
	  echo "<td bgcolor='white'>Th</td>";
	  echo "<td bgcolor='white'>F</td>";
	  echo "<td bgcolor='white'>Sa</td>";
	}
	/*
	echo "<td bgcolor='white' colspan='7' align='center'>Week 1</td>";
	echo "<td bgcolor='white' colspan='7' align='center'>Week 2</td>";
	echo "<td bgcolor='white' colspan='7' align='center'>Week 3</td>";
	echo "<td bgcolor='white' colspan='7' align='center'>Week 4</td>";
	echo "<td bgcolor='white' colspan='7' align='center'>Week 5</td>";
	*/
	echo "</tr>";
	
	echo "<tr>";
	echo "<td bgcolor='white' width='500'></td>";
	echo "<td bgcolor='white' width='30' align='center'>Days</td>";
	for($x=0;$x<$startfiller;$x++){
	  echo "<td bgcolor='white' width='20'>&nbsp;</td>\n";
	}
	$day=0;
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
	  echo "<td bgcolor='$bg' width='20' align='center'>$day</td>\n";
	}
	for($x=0;$x<$endfiller;$x++){
	  echo "<td bgcolor='white' width='20'>&nbsp;</td>\n";
	}
	echo "</tr>";
	
	$threemonth = $calmonth + 2;
	$threeyear = $calyear;
	if($threemonth > 12){
	  $threemonth -= 12;
	  $threeyear += 1;
	}
	$jw_clause = "a.ro_user_id != 1 ";
	//$jw_clause = " 1=1 ";
	
	$sql = "SELECT a.event_id, a.ro_user_id, a.operations_crew, c.color, concat(e.firstname, ' ', e.lastname) as fullname, a.property_id,
	a.what, a.act_id
	from supercali_events a, supercali_dates b, supercali_categories c" . $show_type_table . ", users e
	where a.event_id=b.event_id and 
	a.category_id=c.category_id and
	b.date >= '$calyear-$calmonth' and b.date <= '$threeyear-$threemonth' 
	and a.ro_user_id = e.user_id
	and $show_what_clause
	and $show_type_clause
	and $show_crew_clause
	and $show_complete_clause
	and $cats_clause
	and $jw_clause
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
	  $user_id = $record['ro_user_id'];
	  $crew = stripslashes($record['operations_crew']);
	  
	  $sql = "SELECT date from supercali_dates where event_id='$event_id' order by date asc limit 1";
	  $firstdate = getsingleresult($sql);
	  
	  $row[$counter]['event_id'] = $event_id;
	  $row[$counter]['color'] = $color;
	  $row[$counter]['name'] = $name;
	  $row[$counter]['property_id'] = $property_id;
	  $row[$counter]['what'] = $what;
	  $row[$counter]['act_id'] = $act_id;
	  $row[$counter]['user_id'] = $user_id;
	  $row[$counter]['crew'] = $crew;
	  $row[$counter]['firstdate'] = $firstdate;
	  
	  $counter++;
	}
	
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
	}
	
	array_multisort($data_user_id, SORT_ASC, $data_firstdate, SORT_ASC, $row);
	  
	  
	$oldname = "";
	$oldcrew = "";
	for($foo=0;$foo<sizeof($row);$foo++){
	  $event_id = $row[$foo]['event_id'];
	  $color = $row[$foo]['color'];
	  $name = $row[$foo]['name'];
	  $property_id = $row[$foo]['property_id'];
	  $what = $row[$foo]['what'];
	  $act_id = $row[$foo]['act_id'];
	  $user_id = $row[$foo]['user_id'];
	  $crew = $row[$foo]['crew'];
	  
	  if($what=="O"){
		$url = "\"show_event.php?id=".$event_id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$event_id."&size=small','pop','650','600'); window.newWindow.focus(); return false\"";
	  }
	  else {
		if($property_id){
		  $url = "'../view_property.php?property_id=$property_id&view=production'";
		}
		else {
		  $url = "'../view_prospect.php?prospect_id=$prospect_id&view=activities'";
		}
	  }
	  
	  if($what=="O"){
	    $sql = "SELECT regarding from activities where act_id='$act_id'";
		$regarding = stripslashes(getsingleresult($sql));
	    if(strlen($regarding) > 40) $regarding = substr($regarding, 0, 40) . "...";
		$show_title = $regarding;
	  }
	  else {
	    $sql = "SELECT site_name from properties where property_id='$property_id'";
	    $site_name = stripslashes(getsingleresult($sql));
	    if(strlen($site_name) > 40) $site_name = substr($site_name, 0, 40) . "...";
		$show_title = $site_name;
	  }
	  
	  $sql = "SELECT count(*) from supercali_dates where event_id='$event_id'";
	  $num_days = getsingleresult($sql);
	  if($name != $oldname){
	    if($oldname != ""){
	      echo "<tr>";
		  echo "<td bgcolor='white'></td>";
		  echo "<td bgcolor='white'></td>";
		  for($x=0;$x<$startfiller;$x++){
	        echo "<td bgcolor='white'>&nbsp;</td>\n";
	      }
		  $day=0;
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
		    echo "<td bgcolor='$bg'>&nbsp;</td>";
		  }
		  for($x=0;$x<$endfiller;$x++){
	        echo "<td bgcolor='white'>&nbsp;</td>\n";
	      }
	      echo "</tr>";
		}
	    echo "<tr>";
		echo "<td bgcolor='white'><strong>$name</strong>";
		echo "&nbsp; <a href=\"javascript:MoveAll('$user_id', 0)\" alt=\"Move All Back\"><img src=\"images/left.gif\" border='0'></a>";
		echo "&nbsp; <a href=\"javascript:MoveAll('$user_id', 1)\" alt=\"Move All Forward\"><img src=\"images/right.gif\" border='0'></a>";
		echo "</td>";
		echo "<td bgcolor='white'></td>";
		for($x=0;$x<$startfiller;$x++){
	      echo "<td bgcolor='white'>&nbsp;</td>\n";
	    }
		$day=0;
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
		  echo "<td bgcolor='$bg'></td>";
		}
		$oldname = $name;
		$oldcrew = "";
		for($x=0;$x<$endfiller;$x++){
	      echo "<td bgcolor='white'>&nbsp;</td>\n";
	    }
	    echo "</tr>";
	  }
	  
	  if($oldcrew != $crew){
	    $crew_id = str_replace(" ", "", $crew);
		$sql = "SELECT count(*) from supercali_events a, supercali_dates b where a.operations_crew='$crew' and a.ro_user_id='$user_id' 
		and b.date like '$calyear-$calmonth%' and a.event_id=b.event_id";
		echo "<!-- YYY $sql -->\n";
		$count = getsingleresult($sql);
	    echo "<tr>";
		echo "<td bgcolor='white' style='padding-left:10px;'>";
		echo "<span id='arrow_" . $user_id . "_" . $crew_id . "'><a href=\"javascript:ShowHide('" . $user_id . "_" . $crew_id . "', '$count', '0')\" class=\"ganttlink\">-</a></span>";
		echo "$crew</td>";
		echo "<td bgcolor='white'></td>";
		for($x=0;$x<$startfiller;$x++){
	      echo "<td bgcolor='white'>&nbsp;</td>\n";
	    }
		$day=0;
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
		  echo "<td bgcolor='$bg'></td>";
		}
		$oldcrew = $crew;
		$counter = 0;
		for($x=0;$x<$endfiller;$x++){
	      echo "<td bgcolor='white'>&nbsp;</td>\n";
	    }
	    echo "</tr>";
	  }
	  
	  echo "<tr";
	  if($crew) {
	    $counter++;
		echo " id='" . $user_id . "_" . $crew_id . "[$counter]'";
	  }
	  echo ">";
	  echo "<td bgcolor='white'>";
	  echo "<div style=\"width:100%; position:relative;\">";
	  echo "<div style=\"float:left;\">";
	  echo "<a href=\"javascript:DelEvent('" . $event_id . "')\" style=\"color:red;text-decoration:none;\">x</a> ";
	  echo "<a href=" . $url . " class=\"ganttlink\">$show_title</a>";
	  echo "</div>";
	  echo "<div style=\"float:right;\">";
	  echo "<a href=\"javascript:EndDate('$event_id', '0')\" style=\"color:red;text-decoration:none;\">-</a>";
	  echo " ";
	  echo "<a href=\"javascript:EndDate('$event_id', '1')\" style=\"color:green;text-decoration:none;\">+</a>";
	  echo "</div>";
	  echo "</div>";
	  echo "</td>";
	  echo "<td bgcolor='white' align='center'>$num_days</td>";
	  for($x=0;$x<$startfiller;$x++){
	    echo "<td bgcolor='white'>&nbsp;</td>\n";
	  }
	  $day=0;
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
		if($day<=9) $day = "0" . $day;
	    $sql = "SELECT count(*) from supercali_dates where event_id='$event_id' and date like '$year-$month-$day%'";
		$test = getsingleresult($sql);
		$bgcolor="white";
		$class="";
		if($test){
		  $bgcolor=$color;
		}
		if($bgcolor=="white"){
	      if("$day - $month"==date("d - n")) $bgcolor = "#FFFF99";
		}
		
		echo "<td bgcolor=\"$bgcolor\">&nbsp;</td>\n";
	  }
	  for($x=0;$x<$endfiller;$x++){
	    echo "<td bgcolor='white'>&nbsp;</td>\n";
	  }
	  echo "</tr>";
	}
	
	
	echo '</table>';
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
	  $sql = "SELECT b.sqft from opm a, sections b where opm_id='$opm_id' and a.section_id=b.section_id";
	  $sqft = getsingleresult($sql);
      $sql = "SELECT sum(field_1) from opm_entry where opm_id='" . $opm_id . "'";
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
<script>
function ShowHide(id, count, on){
  if(on==1){
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '" + count + "', '0')\" class=\"ganttlink\">-</a>";
    for(i=1;i<=count;i++){
	  document.getElementById(id + "[" + i + "]").style.display="";
	}
	
	
  }
  else {
    document.getElementById("arrow_" + id).innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '" + count + "', '1')\" class=\"ganttlink\">+</a>";
    for(i=1;i<=count;i++){
	  document.getElementById(id + "[" + i + "]").style.display="none";
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

include "includes/footer.php";
?>
