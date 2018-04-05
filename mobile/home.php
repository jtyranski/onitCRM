<?php include "includes/header.php"; ?>
<?php
/*
$sql = "SELECT admin, servicemen from users where user_id='" . $SESSION_USER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$admin = $record['admin'];
$servicemen = $record['servicemen'];
*/
$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
$sd_access = getsingleresult($sql);

$sql = "SELECT logo from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$MASTER_LOGO = getsingleresult($sql);

?>
<div align="center" style="width:100%;">
<?php
if($MASTER_LOGO != ""){
	      $max_width = 200;
          $max_height = 50;
          list($width, $height) = getimagesize($UPLOAD . "master_logos/" . $MASTER_LOGO);
          $ratioh = $max_height/$height;
          $ratiow = $max_width/$width;
          $ratio = min($ratioh, $ratiow);
          if($width > $max_width || $height > $max_height){
            $width = intval($ratio*$width);
            $height = intval($ratio*$height); 
          }
		  echo "<img src='" . $UPLOAD . "master_logos/" . $MASTER_LOGO . "' width='$width' height='$height' border='0'>";
		  
}
?>
<br><br>
<?php /* sched and calendar are essentiall the same thing on the portal
  <div style="width:250px; height:50px; border:3px inset white; text-align:center; display:table-cell; vertical-align:middle;">
  <a href="schedule.php" class="home">Schedule</a>
  </div>
  <div style="clear:both;"></div>
  <div style="height:10px;">&nbsp;</div>
  */?>
  
  <div style="width:250px; height:50px; border:3px inset white; text-align:center; display:table-cell; vertical-align:middle;">
  <a href="calendar.php" class="home">Calendar</a>
  </div>
  <div style="clear:both;"></div>
  <div style="height:10px;">&nbsp;</div>
  <?php /*
  <div style="width:250px; height:50px; border:3px inset white; text-align:center; display:table-cell; vertical-align:middle;">
  <a href="candidate.php" class="home">Prospecting</a>
  </div>
  <div style="clear:both;"></div>
  <div style="height:10px;">&nbsp;</div>
  */?>
  <div style="width:250px; height:50px; border:3px inset white; text-align:center; display:table-cell; vertical-align:middle;">
  <a href="search.php" class="home">Search</a>
  </div>
  <div style="clear:both;"></div>
  <div style="height:10px;">&nbsp;</div>
  
  <div style="width:250px; height:50px; border:3px inset white; text-align:center; display:table-cell; vertical-align:middle;">
  <a href="contact.php" class="home">Company Contacts</a>
  </div>
  <div style="clear:both;"></div>
  
  <div style="height:10px;">&nbsp;</div>
  <?php 
  /*
  if($sd_access){?>
  <div style="width:250px; height:50px; border:3px inset white; text-align:center; display:table-cell; vertical-align:middle;">
  <a href="service_dispatch.php" class="home">Service Dispatch</a>
  </div>
  <div style="clear:both;"></div>
  <div style="height:10px;">&nbsp;</div>
  <?php } 
  */
  ?>
  
</div>
<?php include "includes/footer.php"; ?>