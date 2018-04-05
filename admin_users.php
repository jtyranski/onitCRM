<?php
include "includes/functions.php";
if($_GET['active']=='0') {
	$active = $_GET['active'];
} else {
	$active = '1';
}
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

  
<div style='float:right;'>
<?php
if($active=='1') {
	echo "<a href=\"admin_users.php?active=0\">Show inactive</a>";
} else {
	echo "<a href=\"admin_users.php\">Show active</a>";
}
?>
</div>
<br>
<div class="main">
<a href="admin_users_edit.php?user_id=new">Add a new user</a>
<table cellpadding="4" cellspacing="0">
<tr>
<td><strong>User</strong></td>
<td><strong>Edit</strong></td>
</tr>
<?php
$groups_filter = " 1=1 ";
if($SESSION_USE_GROUPS){
  if($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != "") $groups_filter = " user_id in(" . $SESSION_GROUP_ALLOWED_USERS . ") ";
}

$counter=1;
$sql = "SELECT user_id, concat(lastname, ', ', firstname) as fullname, resource, firstname, date_format(password_change_date, \"%m/%d/%Y\") as pwdate, enabled
from users WHERE enabled = '$active' and master_id='" . $SESSION_MASTER_ID . "' and resource=0 and $groups_filter order by resource desc, lastname, firstname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  $showname = stripslashes($record['fullname']);
  if($record['resource']==1) $showname = stripslashes($record['firstname']);
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td><?=$showname?></td>

  <td><a href="admin_users_edit.php?user_id=<?=$record['user_id']?>">Edit</a></td>
  </tr>
  <?php
}
?>
</table>

</div>

