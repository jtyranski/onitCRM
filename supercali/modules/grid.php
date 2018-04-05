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

function showGrid($date) {
	global $title, $niceday, $start_time, $end_time, $venue, $city, $state, $cat, $color, $background, $ed, $usr, $o, $c, $m, $a, $y, $w, $lang, $ap, $status, $what, $event_property_id, $operations_crew, $complete, $ipad_uplink;
	global $SESSION_USER_LEVEL;
	global $SESSION_USER_ID;
	if ($start_time[$date]) {
		ksort($start_time[$date]);
		echo "<ul>\n";
		while (list($t) = each($start_time[$date])) {
			while (list($id,$value) = each($start_time[$date][$t])) {
			    $sql = "SELECT property_id, ro_user_id, prospect_id, act_id from supercali_events where event_id='$id'";
				$result = executequery($sql);
				$record = go_fetch_array($result);
				$property_id = $record['property_id'];
				$prospect_id = $record['prospect_id'];
				$ro_user_id = $record['ro_user_id'];
				$act_id = $record['act_id'];
				$sql = "SELECT firstname from users where user_id='$ro_user_id'";
				$firstname = stripslashes(getsingleresult($sql));
				$sr_extra = "";
				$opp_product_extra = "";
				if($property_id){
				  $sql = "SELECT site_name, city, state from properties where property_id = '" . $property_id . "'";
				  $result = executequery($sql);
				  $record = go_fetch_array($result);
				  $site_name = stripslashes($record['site_name']);
				  $city = stripslashes($record['city']);
				  $state = stripslashes($record['state']);
				  
				  /*
				  $sr = "";
				  $sql = "SELECT srmanager from opportunities where property_id='$property_id' and srmanager != '' group by srmanager";
				  $res2 = executequery($sql);
				  while($rec2 = go_fetch_array($res2)){
				    $sr[] = $rec2['srmanager'];
				  }
				  
				  if(is_array($sr)){
				    $sr_extra = "[";
				    for($foo=0;$foo<sizeof($sr);$foo++){
					  $sql = "SELECT firstname from users where user_id='" . $sr[$foo] . "'";
					  $bar = stripslashes(getsingleresult($sql));
					  $initial = substr($bar, 0, 1);
					  $sr_extra .= $initial;
					}
					$sr_extra .= "]";
				  }
				  */
				  
				  
				  
				}
				else {
				  $sql = "SELECT company_name, city, state from prospects where prospect_id='$prospect_id'";
				  $result = executequery($sql);
				  $record = go_fetch_array($result);
				  $site_name = stripslashes($record['company_name']);
				  $city = stripslashes($record['city']);
				  $state = stripslashes($record['state']);
				}
				if(strlen($site_name) > 40) $site_name = substr($site_name, 0, 40) . "...";
				$showtitle = $firstname . " - " . $site_name;
				if($sr_extra != "") $showtitle .= " " . $sr_extra;
				if($opp_product_extra != "") $showtitle .= " " . $opp_product_extra;
				$sid = $_GET['sid'];
				if($what[$id]=="X"){
				  $showtitle = "$firstname - $site_name - " . $operations_crew[$id];
				}
				if($sid) $showtitle .= " - " . $id;
				
				$sql = "SELECT date_format(a.date, \"%l:%i %p\") as tp, a.demo_master_id from activities a, supercali_events b where b.act_id = a.act_id and 
				  b.event_id='$id'";
				  $result = executequery($sql);
				  $record = go_fetch_array($result);
				  $timepretty = stripslashes($record['tp']);
				  $demo_master_id = $record['demo_master_id'];
				  if($demo_master_id) $showtitle .= " (Core Demo)";
				  $hover = $timepretty . "<br>" . $city . ", " . $state;
				if($what[$id]=="O"){
				  $sql = "SELECT description from supercali_events where event_id='$id'";
				  $regarding = stripslashes(getsingleresult($sql));
				  $showtitle = $firstname . " - " . $regarding;
				  $sql = "SELECT date_format(date, \"%l:%i %p\") from supercali_dates where event_id='$id'";
				  $hover = getsingleresult($sql);
				}
				echo "<li>";
				echo "<div class=\"item\"";
				//if ($color[$id]) echo " style=\"color: ".$color[$id]."; background: ".$background[$id].";\"";
				if ($color[$id] && $complete[$id]==0) echo " style=\"color: ".$background[$id].";\"";
				if($complete[$id]==1) echo " style=\"color:#666666;\"";
				echo ">";
				//echo "<div class=\"time\">".$value;
				//if ($end_time[$date][$t][$id]) echo " - ".$end_time[$date][$t][$id];
				//echo "</div>\n";
				//echo "<div class=\"title\"><a href=\"show_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$id."&size=small','pop','650','600'); window.newWindow.focus(); return false\"";
				echo "<div class=\"title\"><a href=";
				if($what[$id]=="O"){
				  //echo "\"show_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('show_event.php?id=".$id."&size=small','pop','650','600'); window.newWindow.focus(); return false\"";
				  if($SESSION_USER_LEVEL=="Manager" || $ro_user_id==$SESSION_USER_ID){
				    echo "../calendar_details.php?act_id=$act_id";
				  }
				  else {
				    echo "#";
				  }
				}
				else {
				  if($property_id){
				    echo "'../" . $ipad_uplink . "view_property.php?property_id=$property_id&view=activities'";
				  }
				  else {
				    echo "'../" . $ipad_uplink . "view_prospect.php?prospect_id=$prospect_id&view=activities'";
				  }
				}
				//if ($color[$id]) echo " style=\"color: ".$color[$id]."; background: ".$background[$id].";\"";
				if ($color[$id] && $complete[$id]==0) echo " style=\"color: ".$background[$id].";\"";
				if($complete[$id]==1) echo " style=\"color:#666666;\"";
				


				  

				echo " class='info'";
				if($what[$id] != "O") echo " target='_top'";
				echo ">".$showtitle."<span>" . $hover . "</span></a></div>\n";
				/*
				if ($venue[$id]) {
					echo "<div class=\"venue\">".$venue[$id]."</div>\n";
					if ($city[$id]) {
						echo "<div class=\"location\">".$city[$id];
						if ($state[$id]) echo ", ".$state[$id];
						echo "</div>\n";
					}
				}
				*/
				echo "</div>";
				/*
				if ($ed[$id]==true) {
					echo "<div class=\"edit\">";
					if (($ap[$id]==true) && (($status[$id] == 2) || ($status[$id] == 3))) echo "[<a href=\"admin_actions.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."&mode=".approve."\">".$lang["approve"]."</a>]&nbsp;&nbsp;";
					echo "[<a href=\"edit_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('edit_event.php?id=".$id."&size=small','pop','650','600'); window.newWindow.focus(); return false\">".$lang["edit"]."</a>]&nbsp;&nbsp;[<a href=\"delete_event.php?id=".$id."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\">".$lang["delete"]."</a>]</div>\n";
				}
				*/
				echo "</li>\n";
			}
		}
		echo "</ul>\n";
	}
}

function showMonth ($calmonth,$calyear) {
	global $week_titles, $o, $m, $a, $y, $w, $c, $next, $prev,$ly, $lm, $le, $la;
	/* determine total number of days in a month */
	
	$calday = 0;
	$totaldays = 0;
	while ( checkdate( $calmonth, $totaldays + 1, $calyear ) )
	        $totaldays++;
	
	/* build table */
	echo '<table width="100%" class="grid""><tr>'; 
	echo '<th colspan="7" class="cal_top"><a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$prev["month"]["m"],'&a=1&y=',$prev["month"]["y"],'">&lt;</a> ',date('F', mktime(0,0,0,$calmonth,1,$calyear)),'&nbsp;',date('Y', mktime(0,0,0,$calmonth,1,$calyear)),' <a href="',$PHP_SELF,'?o=',$o,'&w=',$w,'&c=',$c,'&m=',$next["month"]["m"],'&a=1&y=',$next["month"]["y"],'">&gt;</a></th></tr><tr>';
	for ( $x = 0; $x < 7; $x++ )
	        echo '<th>', $week_titles[ $x ], '</th>';
	
	/* ensure that a number of blanks are put in so that the first day of the month
	   lines up with the proper day of the week */
	$off = date( "w", mktime( 0, 0, 0, $calmonth, $calday, $calyear ) );
	$offset = $off + 1;
	echo '</tr><tr>';
	if ($offset > 6) $offset = 0;
	for ($t=0; $t < $offset; $t++) {
		if ($t == 0) {
			$offyear = date( "Y", mktime( 0, 0, 0, $calmonth, $calday-$off, $calyear ) );
			$offmonth = date( "m", mktime( 0, 0, 0, $calmonth, $calday-$off, $calyear ) );
			$offday = date( "d", mktime( 0, 0, 0, $calmonth, $calday-$off, $calyear ) );
			echo '<td class="day">';
			// <div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$offmonth,'&a=',$offday,'&y=',$offyear,'">week</a></div>
			echo '</td>';
		} else {
			echo '<td class="day">&nbsp;</td>';
		}
	}
	/* start entering in the information */
	for ( $d = 1; $d <= $totaldays; $d++ )
	{
			if (($d == date(j)) && ($calmonth == date(m)) && ($calyear == date(Y))) {
				echo '<td class="day" id="today"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
			} else {
				echo '<td class="day"><div class="day_of_month"><a href="index.php?o=',$la,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">', $d, '</a></div>';
				//if ($offset == 0) echo '<div class="week"><a href="index.php?o=',$le,'&w=',$w,'&c=',$c,'&m=',$calmonth,'&a=',$d,'&y=',$calyear,'">week</a></div>';
				
			}
			/* correct date format */
			$coredate = date( "Ymd", mktime( 0, 0, 0, $calmonth, $d, $calyear ) );
			showGrid($coredate);
			echo "</td>";
	        $offset++;
	
	        /* if we're on the last day of the week, wrap to the other side */
	        if ( $offset > 6 )
	        {
	                $offset = 0;
	                echo '</tr>';
	                if ( $day < $totaldays )
	                        echo '<tr>';
	        }
	}
	
	/* fill in the remaining spaces for the end of the month, just to make it look
	   pretty */
	if ( $offset > 0 )
	        $offset = 7 - $offset;
	
	for ($t=0; $t < $offset; $t++) {
		echo "<td>&nbsp;</td>";
	}
	/* end the table */
	echo '</tr></table>';
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
$show_complete = $_GET['show_complete'];
if($calendar_type=="Sales"){ 
?>
<script>
function load_search(x){
  y = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>&searchby=" + y;
}



function SetChecked(x,chkName) {
  var form='gridform'; //Give the form name here
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}
</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
    //<![CDATA[



// ======== New map variable ========
    var citymap = {};

  var cityCircle;
  var map;

// =========== Initialize our map, set center, zoom level, hybrid view, UI controls, and allow info window =============
    function initialize(xlat, xlng, zl) {

      // defines zoom, center, map type, etc
      var latlng = new google.maps.LatLng(xlat, xlng);
      var mapOptions = {
        zoom: zl,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };
	  
	  
      map = new google.maps.Map(document.getElementById("map_canvas"),
	mapOptions);

      for (var city in citymap) {
        // Construct the circle for each value in citymap. We scale population by 20.
        var radiusOptions = {
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#000000",
          fillOpacity: 0.0,
          map: map,
          center: citymap[city].center,
          radius: citymap[city].radius
        };
        cityCircle = new google.maps.Circle(radiusOptions);
      }
      
      var georssLayer = new google.maps.KmlLayer("http://www.google.com/latitude/apps/badge/api?user=<?=$googlemap_ids?>&type=kml");
      georssLayer.setMap(map);
	  georssLayer.set('preserveViewport', true);
	  
	  
	  
    }
	
	

// ============ display a new marker ================
    function placeMarker(lat, lng, html, icon) {
	  
	  //alert(foo);
	  switch(icon){
	  case "tack":{
	    image = "<?=$CORE_URL?>googlemap/thumb_red.png";
		imagesize1 = 16;
	    imagesize2 = 16;
		break;
	  }
	  default:{
	    image = "<?=$CORE_URL?>googlemap/ro_public_orange.png";
		imagesize1 = 16;
	    imagesize2 = 16;
		break;
	  }
	  }
     
     var iconimage = new google.maps.MarkerImage(image, null, null, null, new google.maps.Size(imagesize1, imagesize2));

	  var infowindow = new google.maps.InfoWindow({
        content: html
      });

	  var latlng = new google.maps.LatLng(lat, lng);
      var marker = new google.maps.Marker({
        position: latlng, 
        map: map,
		icon: iconimage
      });
	  
	  google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
      });
	  
	  //map.setCenter(location);

    }
	
	function placeBadge(lat, lng, html, icon) {
      var iconimage2 = new google.maps.MarkerImage(icon, null, null, null, new google.maps.Size(24, 24));

	  var infowindow2 = new google.maps.InfoWindow({
        content: html
      });

	  var latlng2 = new google.maps.LatLng(lat, lng);
      var marker2 = new google.maps.Marker({
        position: latlng2, 
        map: map,
		icon: iconimage2
      });
	  
	  google.maps.event.addListener(marker2, 'click', function() {
        infowindow2.open(map,marker2);
      });
	  
	  //map.setCenter(location);

    }

    </script>

<div style="width:100%; position:relative;">
<div style="float:left; width:33%;">
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="formchangetype">
<select name="calendar_type" onchange="document.formchangetype.submit()">
<option value="Sales" selected="selected">Sales</option>
<option value="SalesGantt">Sales Gantt</option>
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

<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="gridform">
<select name="searchby" onchange="load_search(this)">
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected";?>>This Week</option>
<option value="lastweek"<?php if($searchby=="lastweek") echo " selected";?>>Last Week</option>
<option value="nextweek"<?php if($searchby=="nextweek") echo " selected";?>>Next Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected";?>>This Month</option>
<option value="lastmonth"<?php if($searchby=="lastmonth") echo " selected";?>>Last Month</option>
<option value="todate"<?php if($searchby=="todate") echo " selected";?>>To Date</option>
</select>
<br>
<?php if($SESSION_USER_LEVEL=="Manager"){?>
<?php
/*
<select name="show_what" onchange="document.gridform.submit()">
<option value="0">All</option>
<?php


$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($show_what == $record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
*/?>
<span id="userlist_arrow"><a href="javascript:ShowHideUsers('userlist', '1')" style='color:white; text-decoration:none; font-weight:bold; font-size:14px;'>+ Users</a></span>
<div style="background-color:#FFFFFF; display:none;" id="userlist">
<input type='checkbox' onchange="SetChecked(this, 'show_what[]')">All<br>
<?php
$groups_filter = " 1=1 ";
if($SESSION_USE_GROUPS){
  if($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != "") $groups_filter = " user_id in(" . $SESSION_GROUP_ALLOWED_USERS . ") ";
}
  
  
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $groups_filter order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="show_what[]" value="<?=$record['user_id']?>"<?php if(in_array($record['user_id'], $show_what)) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
  <?php
}
?>
<input type="submit" name="submit1" value="Filter">
</div>

<br>
<?php } ?>
<input type="checkbox" name="show_complete" value="1" onchange="document.gridform.submit()"<?php if($show_complete) echo " checked";?>>
<span class="main"><font color="#FFFFFF">Show Complete<br></font></span>
<br>
</form>

<?php
    if($show_complete==1){
	  $show_complete_clause = " 1=1 ";
	}
	else {
	  $show_complete_clause = " a.complete=0 ";
	}
$sql = "SELECT a.what, count(a.what) as count, sum(a.value) as total, d.color from supercali_events a, supercali_dates b, prospects c, supercali_categories d
where a.event_id=b.event_id and a.prospect_id=c.prospect_id and a.category_id=d.category_id and a.what != '' and a.what != 'O' and $daterange and a.what !='X' and a.what != 'SD'
and $show_complete_clause
and c.master_id='" . $SESSION_MASTER_ID . "' group by a.what";
$result = executequery($sql);
//echo "<!-- $sql -->\n";
?>
<table style="border:1px solid #000000;" bgcolor="#FFFFFF" width="300">
<?php
while($record = go_fetch_array($result)){
  $xwhat = $record['what'];
  switch($xwhat){
    case "BP":{
	  $what_display = $BP;
	  $total_display = "$" . number_format($record['total'], 2);
	  break;
	}
	case "I":{
	  $what_display = "<font color='" . $record['color'] . "'>" . $I . "</font>";
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
	case "PCO":{
	  $what_display = $PCO;
	  $total_display = "";
	  break;
	}
	case "M":{
	  $what_display = "<font color='" . $record['color'] . "'>" . "Meeting" . "</font>";
	  $total_display = "";
	  break;
	}
	case "C":{
	  $what_display = "<font color='" . $record['color'] . "'>" . "Contact" . "</font>";
	  $total_display = "";
	  break;
	}
	case "Q":{
	  $what_display = "<font color='" . $record['color'] . "'>" . "Quickbid" . "</font>";
	  $total_display = "";
	  break;
	}
	case "TD":{
	  $what_display = "<font color='" . $record['color'] . "'>" . "To Do" . "</font>";
	  $total_display = "";
	  break;
	}
	case "RS":{
	  $what_display = "<font color='" . $record['color'] . "'>" . "$RESAPPT" . "</font>";
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
?>
</div>
<div style="float:left; width:33%; color:white; font-size:28px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;" align="center">Schedule</div>
<div style="float:right; width:33%;" align="right">
<a href="javascript:Openwin('map_wrapper')"><img src="../images/us-icon_black.png" border="0"></a><br>
<a href="javascript:Openwin('contact')" style="color:#FFFFFF; text-decoration:none;">Add Personal Activity</a><br>
<a href="../calendar_activities.php" style="color:#FFFFFF; text-decoration:none;"><img src="../images/activities_icon.png" border="0"></a><br>



</div>
</div>
<div style="clear:both;"></div>

<?php


//echo $sql;
$thismonth = $y."-".$m;
$nextmonth =  $next["month"]["y"]."-".$next["month"]["m"];
grab($thismonth."-01",$nextmonth."-01",$c);
showMonth($m,$y);
?>
<script>
function Openwin(x){
  grayOut(true, {'zindex':'50', 'opacity':'25'});
  document.getElementById(x).style.display="block";
}

function Closewin(x){
  grayOut(false);
  if(x=="map_wrapper") document.getElementById('map_wrapper').style.top="20px";
  document.getElementById(x).style.display="none";
}


	</script> 
<script src="../includes/grayout.js"></script>
<script src="../includes/calendar.js"></script>
<script>
function ShowHideUsers(area, x){
  if(x==1){
    document.getElementById(area).style.display="";
	document.getElementById(area + '_arrow').innerHTML = "<a href=\"javascript:ShowHideUsers('" + area + "', '0')\" style='color:white; text-decoration:none; font-weight:bold; font-size:14px;'>- Users</a>";
  }
  else {
    document.getElementById(area).style.display="none";
	document.getElementById(area + '_arrow').innerHTML = "<a href=\"javascript:ShowHideUsers('" + area + "', '1')\" style='color:white; text-decoration:none; font-weight:bold; font-size:14px;'>+ Users</a>";
  }
}
</script>

<div id="contact" style="position:absolute; left:100px; top:50px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:550px; height:300px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('contact')">X</a>
  </div>
  <form action="addother_action.php" method="post">
  <?php if($SESSION_USER_LEVEL=="Manager"){?>
  For:
  <select name="other_user_id">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $groups_filter order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
    <option value="<?=$record['user_id']?>"<?php if($SESSION_USER_ID == $record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
    <?php
  }
  ?>
  </select><br>
  <?php } else { ?>
  <input type="hidden" name="other_user_id" value="<?=$SESSION_USER_ID?>">
  <?php } ?>
  Date:
  <input size="10" type="text" name="otherdate"> 
  <img src="../images/calendar.gif" onClick="KW_doCalendar('otherdate',0)" align="absmiddle">
  <br>
  Time:
  <input type="text" name="hourpretty" value="<?=date("g")?>" size="1">:
  <input type="text" name="minutepretty" value="<?=date("i")?>" size="1">
  <select name="ampm">
  <option value="AM"<?php if(date("A")=="AM") echo " selected";?>>AM</option>
  <option value="PM"<?php if(date("A")=="AM") echo " selected";?>>PM</option>
  </select>
  <br>
  Description:<br>
  <textarea name="description" rows="5" cols="50"></textarea>
  <br>
  <input type="submit" name="submit1" value="Add">
  
  </form>
</div>


<div id="map_wrapper" style="position:absolute; left:50px; top:-620px; width:1200px; height:645px; z-index:151; border: 1px solid black; background-color:white;">
  
  <div style="position:relative; width:100%;" class="main">
  <div style="float:left;">
  
  </div>
  <div style="float:right;">
  <a href="javascript:Closewin('map_wrapper')" style="font-weight:bold; color:#FF0000;">X</a>
  </div>
  </div>
  <div style="clear:both;"></div>
  <div style="width:1200px; height:600px;" id="map_canvas"></div>
</div>

<?php
  $latitude = 37.25;
  $longitude = -96.857788;
  $zoomlevel = 5;
?>
<script>
timer = setTimeout("Closewin('map_wrapper')",200); // 3 secs
initialize(<?=$latitude?>, <?=$longitude?>, <?=$zoomlevel?>);
</script>

<?php
function map_property_ids(){
  global $event_property_id;
  

  foreach($event_property_id as $key=>$value){
    $event_id = $key;
	$property_id = $value;
	if($property_id){
	  $sql = "SELECT a.site_name, a.city, a.state, b.latitude, b.longitude from properties a, geocode b where a.property_id=b.property_id and a.property_id='$property_id'";
	  $sql = "SELECT date_format(b.date, \"%m/%d/%Y %h:%i\") as datepretty, concat(c.firstname, ' ', c.lastname) as fullname, d.latitude, d.longitude, e.site_name, e.state, e.city from 
	  supercali_events a, supercali_dates b, users c, geocode d, properties e where
	  a.event_id=b.event_id and a.ro_user_id = c.user_id and a.property_id=d.property_id and a.property_id=e.property_id and a.event_id='$event_id'";
	  $result = executequery($sql);
	  $record = go_fetch_array($result);
	  $site_name = stripslashes($record['site_name']);
	  $latitude = stripslashes($record['latitude']);
	  $longitude = stripslashes($record['longitude']);
	  $city = stripslashes($record['city']);
	  $state = stripslashes($record['state']);
	  $datepretty = stripslashes($record['datepretty']);
	  $fullname = stripslashes($record['fullname']);
	  $html = "$fullname<br>$datepretty<br>$site_name<br />$city, $state<br>";
      $html = go_reg_replace("\'", "", $html);
      echo "<script>";
      echo "placeMarker(" . $latitude . ", " . $longitude . ",'$html', 'marker');\n";
      echo "</script>";
	}
	
  }
}

map_property_ids();
?>
<?php
include "includes/footer.php";
?>
