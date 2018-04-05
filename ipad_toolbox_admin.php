<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php include "includes/functions.php"; ?>
<?php

if($SESSION_ISADMIN==0) meta_redirect("ipad_toolbox.php");

$groups_filter = " 1=1 ";
if($SESSION_USE_GROUPS){
  if($SESSION_GROUPS != "" || $SESSION_SUBGROUPS != "") $groups_filter = " user_id in(" . $SESSION_GROUP_ALLOWED_USERS . ") ";
}

$user_id = $_GET['user_id'];
if($user_id==""){
  $sql = "SELECT user_id from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $groups_filter order by lastname";
  $user_id = getsingleresult($sql);
}

$sql = "SELECT tools_available, tools_active from users where user_id='" . $user_id . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$tools_available = $record['tools_available'];

?>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="form1">
<select name="user_id" onchange="document.form1.submit()">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and $groups_filter order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</form>

<?php
$sql = "SELECT * from toolbox_cat order by cat_id";
$result = executequery($sql);
while($cat = go_fetch_array($result)){
  $cat_id = $cat['cat_id'];
  $cat_name = stripslashes($cat['name']);
  $show_name = 1;
  $sql = "SELECT * from toolbox_items where cat_id='$cat_id' and master_id='" . $SESSION_MASTER_ID . "' order by id";
  $res2 = executequery($sql);
  //echo "<!-- $sql -->\n";
  while($item = go_fetch_array($res2)){
    $id = $item['id'];
	if($show_name == 1){
	  ?>
	  <br>
	  <div class="main_large" style="background-color:#CCCCCC; width:100%;">
	  <a href="ipad_toolbox_admin_edit.php?user_id=<?=$user_id?>&cat_id=<?=$cat_id?>&action=allon">All On</a>
	  &nbsp; &nbsp; &nbsp;
	  <a href="ipad_toolbox_admin_edit.php?user_id=<?=$user_id?>&cat_id=<?=$cat_id?>&action=alloff">All Off</a>
	  &nbsp; &nbsp; &nbsp;
	  <?=$cat_name?>
	  </div>
	  <div style="position:relative; width:100%;" class="main">
	  <?php
	  $show_name = 0;
	}
	if(go_reg("," . $id . ",", $tools_available)){
	  $image = $item['on_icon'];
	}
	else {
	  $image = $item['off_icon'];
	}
	
	?>
	<div style="float:left; width:100px;">
	<a href="ipad_toolbox_admin_edit.php?id=<?=$id?>&user_id=<?=$user_id?>&action=single">
	<img src="images/toolbox/<?=$image?>" border="0">
	</a>
	<br>
	<?=stripslashes($item['name'])?>
	</div>
	<?php
  }
  if($show_name==0) echo "</div><div style='clear:both;'></div>";
}
?>
