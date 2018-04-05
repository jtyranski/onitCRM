<link href="styles/css_white.css" type="text/css" rel="stylesheet">


	
<?php include "includes/functions.php"; ?>
<?php

$sql = "SELECT tools_available, tools_active from users where user_id='" . $SESSION_USER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$tools_available = $record['tools_available'];
$tools_active = $record['tools_active'];
?>
<div style="position:relative; width:100%;">
<?php if($_SESSION[$sess_header . '_ipad_toolbox_edit']==1){?>
<div style="float:left; font-weight:bold;" class="main_large">Tool Shop</div>
<?php } ?>
<div style="float:right;">
<a href="toolbox_edit.php" target="_top"><img src="images/gear-icon.png" border="0"></a>
<?php
  //ImageLink("ipad_toolbox_editmode.php", "gear-icon", 0, 0, "ipad/");
  ?>
</div>
</div>
<div style="clear:both;"></div>



<div style="position:relative; width:100%;" class="main">
<?php if($SESSION_ISADMIN){ ?>
    <div style="float:left; width:100px;">
	<a href="javascript:parent.go_toolbox('admin_index.php')">
	<img src="images/toolbox/administration_up.png" border="0">
	</a>
	<br>
	Admin
	</div>
<?php } ?>

    <div style="float:left; width:100px;">
	<a href="javascript:parent.go_toolbox('user_edit.php')">
	<img src="images/toolbox/administration_up.png" border="0">
	</a>
	<br>
	Edit My Account
	</div>


<?php
$sql = "SELECT disable_caps from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$dc = getsingleresult($sql);

$tool_counter = 0;
$sql = "SELECT * from toolbox_cat where cat_id != 6 order by cat_id"; // 6 is for stats page
$result = executequery($sql);
while($cat = go_fetch_array($result)){
  $cat_id = $cat['cat_id'];
  $cat_name = stripslashes($cat['name']);
  $sql = "SELECT * from toolbox_items where cat_id='$cat_id' and master_id='" . $SESSION_MASTER_ID . "' order by id";
  $res2 = executequery($sql);
  while($item = go_fetch_array($res2)){
    $id = $item['id'];
	//if(!go_reg("," . $id . ",", $tools_active)) continue;
    if(!go_reg("," . $id . ",", $tools_available)) continue;
	if($dc==1 && $item['tool_master_id']==4) continue;
	$tool_counter++;
	if($tool_counter==10){ ?>
	  </div>
	  <div style="clear:both;"></div>
	  <div style="position:relative; width:100%;" class="main">
	  <?php
	  $tool_counter = 0;
	}
	?>
	<div style="float:left; width:100px;">
	<a href="javascript:parent.go_toolbox('<?=$item['url']?>')">
	<img src="images/toolbox/<?=$item['on_icon']?>" border="0">
	</a>
	<br>
	<?=stripslashes($item['name'])?>
	</div>
	<?php
  }
}
?>
</div>
<div style="clear:both;"></div>
