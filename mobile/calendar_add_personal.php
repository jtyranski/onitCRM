<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<br>

  <form action="calendar_add_personal_action.php" method="post">
  <?php if($SESSION_USER_LEVEL=="Manager"){?>
  For:
  <select name="other_user_id">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
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
  <select name="month">
  <?php 
  for($x=1;$x<=12;$x++){ 
    $y = $x;
	if(strlen($y) < 2) $y = "0" . $y;
	?>
	<option value="<?=$y?>"<?php if(date("n")==$x) echo " selected";?>><?=$y?></option>
	<?php
  }
  ?>
  </select>
  /
  <select name="day">
  <?php 
  for($x=1;$x<=31;$x++){ 
    $y = $x;
	if(strlen($y) < 2) $y = "0" . $y;
	?>
	<option value="<?=$y?>"<?php if(date("j")==$x) echo " selected";?>><?=$y?></option>
	<?php
  }
  ?>
  </select>
  /
  <select name="year">
  <?php 
  for($x=date("Y");$x<=date("Y") + 1;$x++){ 
	?>
	<option value="<?=$x?>"<?php if(date("Y")==$x) echo " selected";?>><?=$x?></option>
	<?php
  }
  ?>
  </select>
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
  <textarea name="description" rows="4" cols="30"></textarea>
  <br>
  <input type="submit" name="submit1" value="Add" style="width:310px; height:50px;">
  
  </form>
<?php include "includes/footer.php"; ?>