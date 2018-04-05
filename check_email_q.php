<body style="margin:0px 0px 0px 0px;">
<meta http-equiv="refresh" content="90;url=check_email_q.php">
<?php
include "includes/functions.php";


$sql = "SELECT id from toolbox_items where tool_master_id=12 and master_id='" . $SESSION_MASTER_ID . "'";
$test = getsingleresult($sql);
if($test=="") exit;


$sql = "SELECT count(*) from users where user_id='" . $SESSION_USER_ID . "' and tools_available like '%," . $test . ",%'";
$alert = getsingleresult($sql);
if($alert){

  $sql = "SELECT email_threshhold from global_variables";
  $email_threshhold = getsingleresult($sql);


  $sql = "SELECT count(*) from email_q where num_recipients >= $email_threshhold and sent=0";
  $test = getsingleresult($sql);
  //echo "<!-- $sql -->\n";
  if($test){

  ?>
  <div style="position:relative;">
  <div style="float:left;">
  <a href="toolbox.php?go=tool_emailq.php" target="_parent"><img src="images/mail-icon_off.png" border="0"></a>
  </div>
  </div>
  <?php
  }
  
}





  
?>