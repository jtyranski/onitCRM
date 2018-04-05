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


/* Grab Dates Function */
// Queries database and dumps events into an array.
// $start and $end are date ranges in 20060118 format
// $category_array is arrays of categories to be included
function grabDates($start,$end,$category_array) {
	//$cats = implode(",",$category_array);
	global $table_prefix, $supergroup;
	global $title, $niceday, $start_time, $end_time, $venue, $city, $state, $cat,$ed, $usr, $color, $background,$lang, $w, $ap, $status, $what, $operations_crew, $complete, $event_property_id;
	global $show_userid;
	global $show_what;
	global $show_type;
	global $show_crew;
	global $show_complete;
	global $SESSION_SUPERCALI_USER_ID;
	global $SESSION_MASTER_ID;
	global $SESSION_USE_GROUPS, $SESSION_GROUPS, $SESSION_SUBGROUPS, $SESSION_GROUP_PROSPECT_ID;
	$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  for($x=0;$x<sizeof($SESSION_GROUP_PROSPECT_ID);$x++){
    $pg .= $SESSION_GROUP_PROSPECT_ID[$x] . ",";
  }
  $pg = go_reg_replace("\,$", "", $pg);
  $groups_clause = " prospects.prospect_id in ($pg) ";
}

	//if($show_what != "" && $show_what != "0"){
	if(is_array($show_what)){
	  $x_show_what = "";
	  for($x=0;$x<sizeof($show_what);$x++){
	    $x_show_what .= $show_what[$x] . ",";
	  }
	  $x_show_what = go_reg_replace("\,$", "", $x_show_what);
	  //$show_what_clause = "supercali_events.ro_user_id = '$show_what' and ";
	  $show_what_clause = "supercali_events.ro_user_id in ($x_show_what) and ";
	}
	if($show_type != ""){
	  $show_type_table = ", properties";
	  if($show_type=="Manville"){
	    $show_type_clause = " supercali_events.property_id = properties.property_id and (properties.property_type='Manville' or properties.property_type='Beazer B') and ";
	  }
	  else {
	    $show_type_clause = " supercali_events.property_id = properties.property_id and properties.property_type='$show_type' and ";
	  }
	}
	if($show_crew != ""){
      $show_type_clause = " supercali_events.operations_crew='$show_crew' and ";
	}
	if($show_complete==1){
	  $show_complete_clause = " 1=1 and ";
	}
	else {
	  $show_complete_clause = " supercali_events.complete=0 and ";
	}
	//$cats = implode(",",$show_userid);
	/* get applicable events */
	$superedit = false;
	if (!$supergroup) {
		$q = "select moderate from ".$table_prefix."users_to_groups where group_id = ".$w." and user_id = ".$SESSION_SUPERCALI_USER_ID;
		$query = mysql_query($q);
		if (mysql_num_rows($query) > 0) {
			$mod = mysql_result($query,0,0);
			if ($mod > 2) {
				$superedit = true;
			}
		}
	} else {
		$superedit = true;
	}
// add properties table to query, link to events.property_id?
	if (($mod > 0) || ($superedit)) {
		$q = "select DATE_FORMAT(".$table_prefix."dates.date, '%Y%m%d'), DATE_FORMAT(".$table_prefix."dates.date, '%H%i'), ".$table_prefix."events.event_id, ".$table_prefix."events.title, DATE_FORMAT(".$table_prefix."dates.date, '%W, %M %e, %Y'), DATE_FORMAT(".$table_prefix."dates.date, '%l:%i %p'), ";
		$q .= "DATE_FORMAT(".$table_prefix."dates.end_date, '%l:%i %p'), ".$table_prefix."links.company, ".$table_prefix."links.city, ".$table_prefix."links.state, ".$table_prefix."events.category_id, ".$table_prefix."events.user_id, ".$table_prefix."dates.date, ".$table_prefix."categories.color, ".$table_prefix."categories.background, ".$table_prefix."events.status_id";
		$q .= ", supercali_events.what, supercali_events.property_id, supercali_events.operations_crew, supercali_events.complete  ";
		$q .= "from ".$table_prefix."events, ".$table_prefix."dates, ".$table_prefix."links, ".$table_prefix."categories, ".$table_prefix."groups";
		$q .= ", prospects ";
		//$q .= $show_type_table;
		$q .= " where ".$table_prefix."dates.date >= '$start' and ".$table_prefix."dates.date < '$end' and ".$table_prefix."dates.event_id = ".$table_prefix."events.event_id and ".$table_prefix."events.venue_id = ".$table_prefix."links.link_id and ";
		//$q .= "supercali_events.property_id = properties.property_id and properties.prospect_id=prospects.prospect_id and prospects.master_id='" . $SESSION_MASTER_ID . "' and ";
		$q .= "supercali_events.prospect_id = prospects.prospect_id and ";
		$q .= "supercali_events.master_id='" . $SESSION_MASTER_ID . "' and ";
		$q .= $table_prefix."categories.calendar_type='Sales' and ";
		$q .= $show_what_clause;
		//$q .= $show_type_clause;
		
		$q .= $show_complete_clause;
		$q .= " $groups_clause and ";
		$q .= $table_prefix."events.category_id = ".$table_prefix."categories.category_id and ".$table_prefix."events.group_id = ".$table_prefix."groups.group_id and ".$table_prefix."events.group_id = ".$w." order by ".$table_prefix."dates.date";
		$query = mysql_query($q);
		//echo $q."<br>";
		//echo "<!-- MMM $q -->\n";
		while ($row = mysql_fetch_row($query)) {
			$edit = false;
			if ($row[11] == $SESSION_SUPERCALI_USER_ID) {
				$edit = true;
			} elseif ($superedit) {
				$edit = true;
			}
			if ($edit==true) $ed[$row[2]]=true;
			if ($superedit==true) $ap[$row[2]]=true;
			$title[$row[2]]=strip_tags($row[3]);
			$niceday[$row[0]][$row[12]][$row[2]]=$row[4];
			if (($row[5] == "12:00 AM") && ($row[6] == "11:59 PM")) {
				$start_time[$row[0]][$row[12]][$row[2]]=$lang["all_day"];
			} elseif (($row[5] == "12:00 AM") && ($row[6] == "12:00 AM")) {
				$start_time[$row[0]][$row[12]][$row[2]]=$lang["tba"];
			} else {	
				$start_time[$row[0]][$row[12]][$row[2]]=$row[5];
				if ($row[6]) $end_time[$row[0]][$row[12]][$row[2]]=$row[6];
			}
			if ($row[7]) $venue[$row[2]]=$row[7];
			if ($row[7] && $row[8]) $city[$row[2]]=$row[8];
			if ($row[7] && $row[8] && $row[9]) $state[$row[2]]=$row[9];
			$cat[$row[2]]=$row[10];
			$usr[$row[2]]=$row[11];
			$color[$row[2]]=$row[13];
			$background[$row[2]]=$row[14];
			$status[$row[2]]=$row[15];
			$what[$row[2]]=$row[16];
			$event_property_id[$row[2]]=$row[17];
			//$event_property_id[] = $row[17];
			$operations_crew[$row[2]]=$row[18];
			$complete[$row[2]]=$row[19];
		}
		
		
		
		
		$q = "select DATE_FORMAT(".$table_prefix."dates.date, '%Y%m%d'), DATE_FORMAT(".$table_prefix."dates.date, '%H%i'), ".$table_prefix."events.event_id, ".$table_prefix."events.title, DATE_FORMAT(".$table_prefix."dates.date, '%W, %M %e, %Y'), DATE_FORMAT(".$table_prefix."dates.date, '%l:%i %p'), ";
		$q .= "DATE_FORMAT(".$table_prefix."dates.end_date, '%l:%i %p'), ".$table_prefix."links.company, ".$table_prefix."links.city, ".$table_prefix."links.state, ".$table_prefix."events.category_id, ".$table_prefix."events.user_id, ".$table_prefix."dates.date, ".$table_prefix."categories.color, ".$table_prefix."categories.background, ".$table_prefix."events.status_id";
		$q .= ", supercali_events.what, supercali_events.property_id, supercali_events.operations_crew, supercali_events.complete  ";
		$q .= "from ".$table_prefix."events, ".$table_prefix."dates, ".$table_prefix."links, ".$table_prefix."categories, ".$table_prefix."groups";
		$q .= " where ".$table_prefix."dates.date >= '$start' and ".$table_prefix."dates.date < '$end' and ".$table_prefix."dates.event_id = ".$table_prefix."events.event_id and ".$table_prefix."events.venue_id = ".$table_prefix."links.link_id and ";
		//$q .= "supercali_events.property_id = properties.property_id and properties.prospect_id=prospects.prospect_id and prospects.master_id='" . $SESSION_MASTER_ID . "' and ";
		$q .= "supercali_events.master_id='" . $SESSION_MASTER_ID . "' and ";
		$q .= $table_prefix."categories.calendar_type='Sales' and ";
		$q .= $show_what_clause;
		//$q .= $show_type_clause;
		
		$q .= $show_complete_clause;
		$q .= " supercali_events.prospect_id=0 and ";
		$q .= $table_prefix."events.category_id = ".$table_prefix."categories.category_id and ".$table_prefix."events.group_id = ".$table_prefix."groups.group_id and ".$table_prefix."events.group_id = ".$w." order by ".$table_prefix."dates.date";
		$query = mysql_query($q);
		//echo $q."<br>";
		//echo "<!-- MMM $q -->\n";
		while ($row = mysql_fetch_row($query)) {
			$edit = false;
			if ($row[11] == $SESSION_SUPERCALI_USER_ID) {
				$edit = true;
			} elseif ($superedit) {
				$edit = true;
			}
			if ($edit==true) $ed[$row[2]]=true;
			if ($superedit==true) $ap[$row[2]]=true;
			$title[$row[2]]=strip_tags($row[3]);
			$niceday[$row[0]][$row[12]][$row[2]]=$row[4];
			if (($row[5] == "12:00 AM") && ($row[6] == "11:59 PM")) {
				$start_time[$row[0]][$row[12]][$row[2]]=$lang["all_day"];
			} elseif (($row[5] == "12:00 AM") && ($row[6] == "12:00 AM")) {
				$start_time[$row[0]][$row[12]][$row[2]]=$lang["tba"];
			} else {	
				$start_time[$row[0]][$row[12]][$row[2]]=$row[5];
				if ($row[6]) $end_time[$row[0]][$row[12]][$row[2]]=$row[6];
			}
			if ($row[7]) $venue[$row[2]]=$row[7];
			if ($row[7] && $row[8]) $city[$row[2]]=$row[8];
			if ($row[7] && $row[8] && $row[9]) $state[$row[2]]=$row[9];
			$cat[$row[2]]=$row[10];
			$usr[$row[2]]=$row[11];
			$color[$row[2]]=$row[13];
			$background[$row[2]]=$row[14];
			$status[$row[2]]=$row[15];
			$what[$row[2]]=$row[16];
			$event_property_id[$row[2]]=$row[17];
			//$event_property_id[] = $row[17];
			$operations_crew[$row[2]]=$row[18];
			$complete[$row[2]]=$row[19];
		}
		
	}
}

function grab($start,$end,$category) {
    global $SESSION_SUPERCALI_USER_ID;
	global $include_child_categories, $include_parent_categories, $category_array,$supercategory,$supergroup,$category_permissions,$w,$table_prefix;
	$canview = false;
	$groupview = false;
	if (!$supergroup) {
		$q = "SELECT * from ".$table_prefix."users_to_groups where group_id = ".$w." and  user_id = ".$SESSION_SUPERCALI_USER_ID;
		$query = mysql_query($q);
		if (mysql_num_rows($query) > 0) $groupview = true;
	} else {
		$groupview = true;
	}

	if ($groupview) {
		if (!$supercategory) {
			//build permission array
			$q = "SELECT category_id from ".$table_prefix."users_to_categories where user_id = ".$SESSION_SUPERCALI_USER_ID;
			//echo $q."<br>";
			$query = mysql_query($q);
			if (mysql_num_rows($query) > 0) {
				while ($row = mysql_fetch_row($query)) {
					$category_permissions[] = $row[0];
					
				}
			}
			if (in_array($category,$category_permissions)) $canview = true;
		} else {
			$canview = true;
		}
		if ($canview) {
			$category_array[] = $category;
			if ($include_child_categories) grab_child($start,$end,$category,true);
			if ($include_parent_categories) grab_parent($start,$end,$category,true);
			grabDates($start,$end,$category_array);
			
		}
	
	}
}

function grab_child($start,$end,$category,$starter=false) {
	global $table_prefix, $category_array,$supercategory,$category_permissions;
	$canview = false;
	if (!$supercategory) {
		if ($category_permissions) {
			if (in_array($category,$category_permissions)) $canview = true;
		}
	} else {
		$canview = true;
	}

	if ($canview) {
		if (!$starter) $category_array[] = $category;
		$q = "select category_id from ".$table_prefix."categories where sub_of = ".$category;
		//echo $q."<br>";
		$query = mysql_query($q);
		if (!$query) $msg = "Database Error : ".$q;
		else {
			while ($row = mysql_fetch_row($query)) {
				grab_child($start,$end,$row[0],false);
			}
		}
	}
}

function grab_parent($start,$end,$category,$starter=false) {
	global $table_prefix, $category_array, $supercategory,$category_permissions;
	$canview = false;
	if (!$supercategory) {
		if ($category_permissions) {
			if (in_array($category,$category_permissions)) $canview = true;
		}
	} else {
		$canview = true;
	}

	if ($canview) {
		if (!$starter) $category_array[] = $category;
		
		$q = "select sub_of from ".$table_prefix."categories where category_id = ".$category;
		//echo $q."<br>";
		$query = mysql_query($q);
		if (!$query) $msg = "Database Error : ".$q;
		else {
			while ($row = mysql_fetch_row($query)) {
				grab_parent($start,$end,$row[0],false);
			}
		}
	}
}


include "includes/start.php";
$canview = false;
//if no access, then kick them out!
if (!$superview) {
	mysql_close($link);
	$msg = $lang["must_log_in"];
	header("Location: login.php?msg=".$msg."&".$common_get);
}

if (($supergroup) && ($supercategory)) {
	$canview = true;
	
} else {
	
	if (!$supercategory) {
		$canview = false;
		$q = "select * from ".$table_prefix."users_to_categories where category_id = ".$c." and user_id = ".$SESSION_SUPERCALI_USER_ID;
		//echo $q;
		$qu = mysql_query($q);
		if (mysql_num_rows($qu) > 0) {
			$canview = true;
			
		} else {
			$msg .= "<p>".$lang["no_permission_to_view_category"]."</p>";
			$canview = false;
			
		}
	}
	if ((!$supergroup) && $canview) {
		$q = "select * from ".$table_prefix."users_to_groups where group_id = ".$w." and user_id = ".$SESSION_SUPERCALI_USER_ID;
		//echo $q;
		$qu = mysql_query($q);
		if (mysql_num_rows($qu) > 0) {
			$canview = true;
			
		} else {
			$msg .= "<p>".$lang["no_permission_to_view_group"]."</p>";
			$canview = false;
			
		}
	}
}
if (($canview == true)&& $script) {
	include "modules/".$script;
} else {
	include "includes/header.php";
	include "includes/footer.php";
}



?>