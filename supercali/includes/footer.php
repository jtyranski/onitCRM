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
?>
</div>
<?php /*
<div class="bottom">
<?php if (($include_child_categories) || ($include_parent_category)) { ?>
<p style="float: right"><?php echo $lang["calendar_display_start"]; ?>
<?php
if ($include_child_categories) {
	echo "sub categories";
	$sub = 1;
}
if ($include_parent_categories) {
	if ($sub) echo " and ";
	echo "parent categories";
}
?>
<?php echo $lang["calendar_display_end"]; ?></p>
<?php } ?>
<p><a href="http://supercali.inforest.com/" target="_blank">SuperCali Event Calendar</a></p>
</div>
*/ ?>

</td>
</tr>
</table>


<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<SCRIPT LANGUAGE="JavaScript">cp.writeDiv()</SCRIPT>
</body>
</html>
<?php
require_once "../includes/functions.php"; 
if($_SESSION[$sess_header . '_cali_left']==""){
  $sql = "SELECT count(c.property_id)
  from opm a, prospects b, properties c 
  where a.prospect_id=b.prospect_id and a.property_id=c.property_id and a.master_id = '" . $SESSION_MASTER_ID . "' and a.in_q=1";
  $test = getsingleresult($sql);
  if($test != 0){
    $leftnavshow_hide = 1;
	$jr = "from having some";
  }
  else {
    $leftnavshow_hide = 0;
	$jr = "from having none";
  }
}
else {
  $leftnavshow_hide = $_SESSION[$sess_header . '_cali_left'];
  $jr = "from session";
}
?>
<script>

<?php
switch($calendar_type){
  case "Operations":{
    ?>
    LeftNavShowHide('<?=$leftnavshow_hide?>', '0');
    <?php
    break;
  }
  case "Programming":{
    ?>
    LeftNavShowHide('1', '0');
    <?php
    break;
  }
  default:{
    ?>
    LeftNavShowHide('0', '0');
	<?php
	break;
  }
}
?>
//document.getElementById("leftnav_arrow").style.display="none";
</script>
<?php mysql_close($link); ?>