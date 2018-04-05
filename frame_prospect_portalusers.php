<?php
include "includes/header_white.php";
$prospect_id = $_GET['prospect_id'];

?>
<script>
function DelUser(x){
  cf = confirm("Are you sure you want to delete this user?");
  if(cf){
    document.location.href="frame_prospect_portalusers_delete.php?prospect_id=<?=$prospect_id?>&user_id=" + x;
  }
}
</script>

<div class="main">
<a href="frame_prospect_portalusers_edit.php?prospect_id=<?=$prospect_id?>&user_id=new">Add New User</a>
	<br>
	<div id="admin_user_list">
    <table cellpadding="4" cellspacing="0" class="main">
	<tr>
	<td><strong>Name</strong></td>
	<td><strong>Rights</strong></td>
	<td><strong>Edit</strong></td>
	<td><strong>Delete</strong></td>
	</tr>
    <?php
    $counter=1;
    $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, level_access 
	from am_users where prospect_id='" . $prospect_id . "' order by lastname";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $counter++;
      ?>
      <tr<?php if($counter % 2) echo " bgcolor=\"#ececec\"";?>>
      <td><?=stripslashes($record['fullname'])?></td>
	  <td>
	  <?=$record['level_access']?>
	  </td>
      <td><a href="frame_prospect_portalusers_edit.php?prospect_id=<?=$prospect_id?>&user_id=<?=$record['user_id']?>">Edit</a></td>
	  <td><a href="javascript:DelUser('<?=$record['user_id']?>')">Delete</a></td>
      </tr>
      <?php
    }
    ?>
    </table>
	</div>
</div>