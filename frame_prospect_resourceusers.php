<?php
include "includes/functions.php";
$prospect_id=$_GET['prospect_id'];

?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

  

<br>
<div class="main">
<a href="frame_prospect_resourceusers_edit.php?user_id=new&prospect_id=<?=$prospect_id?>">Add a new user</a>
<table cellpadding="4" cellspacing="0">
<tr>
<td><strong>User</strong></td>
<td><strong>Edit</strong></td>
</tr>
<?php
$counter=1;
$sql = "SELECT user_id, concat(lastname, ', ', firstname) as fullname, resource, firstname, date_format(password_change_date, \"%m/%d/%Y\") as pwdate, enabled
from users WHERE enabled = '1' and master_id='" . $SESSION_MASTER_ID . "' and resource_id='$prospect_id' and resource=1 order by resource desc, lastname, firstname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  $showname = stripslashes($record['fullname']);
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
  <td><?=$showname?></td>

  <td><a href="frame_prospect_resourceusers_edit.php?user_id=<?=$record['user_id']?>&prospect_id=<?=$prospect_id?>">Edit</a></td>
  </tr>
  <?php
}
?>
</table>
</div>

