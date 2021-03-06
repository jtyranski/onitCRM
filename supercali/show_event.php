<?php
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
include "includes/start.php";
$page_title = $lang["title_event"];


if ((!$_REQUEST["id"]) or (!ctype_digit($_REQUEST["id"]))) {
	echo "<p class=\"warning\">".$lang["no_event_selected"]."</p>\n";
} else {
	$q = "SELECT * from ".$table_prefix."events where event_id =".$_REQUEST["id"];
	$query = mysql_query($q);
	if (mysql_num_rows($query) < 1) {
		echo "<p class=\"warning\">".$lang["event_not_found"]."</p>\n";
	} else {
		$row = go_fetch_array($query);
		if (!$query) echo "<p class=\"warning\">Database Error : ".$q."</p>\n";
		
		$q = "SELECT DATE_FORMAT(date, '%W, %M %e, %Y'), DATE_FORMAT(date,' - %l:%i %p'),  DATE_FORMAT(end_date, ' - %l:%i %p') from ".$table_prefix."dates where event_id =".$_REQUEST["id"]." order by date";
		$squery = mysql_query($q);
		if (!$squery) echo "<p class=\"warning\">Database Error : ".$q."</p>\n";
		else {
			while ($srow = mysql_fetch_row($squery)) {
				if (($srow[1] == " - 12:00 AM") && ($srow[2] == " - 11:59 PM")) $nicedate[] = $srow[0]." - ".$lang["all_day"];
				elseif (($srow[1] == " - 12:00 AM") && ($srow[2] == " - 12:00 AM")) $nicedate[] = $srow[0]." - ".$lang["tba"];
				//elseif ($srow[2]) $nicedate[] = $srow[0].$srow[1].$srow[2];
				elseif ($srow[2]) $nicedate[] = $srow[0].$srow[1];
				//else $nicedate[] = $srow[0].$srow[1];
				else $nicedate[] = $srow[0];
				
			}
		}
		$page_title = $row["title"];
		$category_id = $row["category_id"];
		$venue_id = $row["venue_id"];
		$contact_id = $row["contact_id"];
		$description = $row["description"];
	}
}
if ($_REQUEST["size"] == "small") $javascript = "<base target=\"_blank\">\n";
include "includes/header.php";
?>
<div style="color:#FFFFFF;">
<?php
echo $lang["category"].": \n";
$cate = mysql_result(mysql_query("select name from ".$table_prefix."categories where category_id = ".$category_id),0,0);
echo "<strong>".$cate."</strong><br />\n";
if ($venue_id > 1) {
	$q = "select url, company, description, address1, address2, city, state, zip, phone, fax  FROM ".$table_prefix."links where link_id = ".$venue_id;
	$lq = mysql_query($q);
	
	echo $lang["venue"].": \n";
	$li = mysql_fetch_row($lq);
	if ($li[0]) { 
		echo "<strong><a href=\"".$li[0]."\">".$li[1]."</a></strong>";
	} else {
		echo "<strong>".$li[1]."</strong>";
	}
	if ($li[3]) echo ", ".$li[3];
	if ($li[4]) echo ", ".$li[4];
	if ($li[5]) echo ", ".$li[5].", ".$li[6]."  ".$li[7];
	if ($li[8]) echo ", ".$lang["phone"].": ".$li[8];
	if ($li[9])echo ", ".$lang["fax"].": ".$li[9];
	echo "<br />\n";
} 
if ($contact_id > 1) {
	$q = "select url, company, description, address1, address2, city, state, zip, phone, fax  FROM ".$table_prefix."links where link_id = ".$contact_id;
	$lq = mysql_query($q);
	
	echo $lang["contact_sponsor"].": \n";
	$li = mysql_fetch_row($lq);
	if ($li[0]) { 
		echo "<strong><a href=\"".$li[0]."\">".$li[1]."</a></strong>";
	} else {
		echo "<strong>".$li[1]."</strong>";
	}
	if ($li[3]) echo ", ".$li[3];
	if ($li[4]) echo ", ".$li[4];
	if ($li[5]) echo ", ".$li[5].", ".$li[6]."  ".$li[7];
	if ($li[8]) echo ", ".$lang["phone"].": ".$li[8];
	if ($li[9])echo ", ".$lang["fax"].": ".$li[9];
	echo "<br />\n";
}
if ($nicedate[1]) {
	echo $lang["dates"].":<ul>\n";
	while (list($k,$v) = each($nicedate)) {
		echo "<strong><li>".$v."</li></strong>\n";
	}
	echo "</ul>\n";
} elseif ($nicedate[0]) {
	echo $lang["date"].": <strong>".$nicedate[0]."</strong><br />";
}

echo "<p>".$description."</p>\n";

/*
$sql = "SELECT b.site_name, c.company_name from supercali_events a, properties b, prospects c where 
a.property_id = b.property_id and a.prospect_id = c.prospect_id and a.event_id = '" . $_REQUEST["id"] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
echo stripslashes($record['company_name']) . " - " . stripslashes($record['site_name']);
*/
if($SESSION_ISADMIN){
echo "[<a href=\"edit_event_jw.php?id=".$_REQUEST["id"]."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" onClick=\"openPic('edit_event_jw.php?id=".$_REQUEST["id"]."&size=small','pop','650','600'); window.newWindow.focus(); return false\">".$lang["edit"]."</a>]&nbsp;&nbsp;[<a href=\"delete_event.php?id=".$_REQUEST["id"]."&o=".$o."&c=".$c."&m=".$m."&a=".$a."&y=".$y."&w=".$w."\" target=\"_parent\">".$lang["delete"]."</a>]";
}
?>
</div>
<?php
include "includes/footer.php";
?>