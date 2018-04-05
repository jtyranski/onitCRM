<?php
include "includes/functions.php";

$property_id = go_escape_string($_GET['property_id']);
$opm_id = go_escape_string($_GET['opm_id']);
$produced_sqft = go_escape_string($_GET['produced_sqft']);
$action = $_GET['action'];

if($action=="reset"){
  $sql = "UPDATE opm set produced_sqft=0 where opm_id='$opm_id'";
  executeupdate($sql);
  $action = "view";
}

if($action=="form"){
  $sql = "SELECT user_id, 
project_sqft, produced_sqft, in_q
from opm where opm_id='$opm_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$project_sqft = stripslashes($record['project_sqft']);
$produced_sqft = stripslashes($record['produced_sqft']);

if($produced_sqft==0){
  $sql = "SELECT sum(replaced) from opm_entry where opm_id='$opm_id'";
  $show_produced_sqft = getsingleresult($sql);
}
else {
  $show_produced_sqft = $produced_sqft;
}

  $html = "<input type='text' name='produced_sqft' id='produced_sqft' value='" . $show_produced_sqft . "' size='10'>";
  $html .= "<input type='button' name='button1' value='Override' onclick=\\\"updatevalue()\\\">";
  $html = jsclean($html);
  ?>
  document.getElementById('show_produced_sqft').innerHTML = '<?=$html?>';
  document.getElementById('override_area').innerHTML = '';
  <?php
}

if($action=="update"){
  $sql = "UPDATE opm set produced_sqft=\"$produced_sqft\" where opm_id='$opm_id'";
  executeupdate($sql);
  $action = "view";
}

if($action=="view"){
$sql = "SELECT user_id, 
project_sqft, produced_sqft, in_q, opp_id, user_id
from opm where opm_id='$opm_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$project_sqft = stripslashes($record['project_sqft']);
$produced_sqft = stripslashes($record['produced_sqft']);
$opp_id = stripslashes($record['opp_id']);
$user_id = stripslashes($record['user_id']);

$CAN_EDIT_THIS_OPM = 0;
if($SESSION_ISADMIN) $CAN_EDIT_THIS_OPM = 1;
if($SESSION_USER_LEVEL=="Manager") $CAN_EDIT_THIS_OPM = 1;
if($CAN_EDIT_THIS_OPM==0){
  $sql = "SELECT can_edit_ops from users where user_id='" . $SESSION_USER_ID . "'";
  $CAN_EDIT_THIS_OPM = getsingleresult($sql);
}
if($CAN_EDIT_THIS_OPM==0){
  $sql = "SELECT user_id, scheduled_by from opportunities where opp_id='$opp_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $x_user_id = $record['user_id'];
  $x_scheudled_by = $record['scheduled_by'];
  if($x_user_id==$SESSION_USER_ID || $x_scheduled_by==$SESSION_USER_ID || $user_id==$SESSION_USER_ID) $CAN_EDIT_THIS_OPM = 1;
}

if($produced_sqft==0){
  $sql = "SELECT sum(replaced) from opm_entry where opm_id='$opm_id'";
  $show_produced_sqft = getsingleresult($sql);
  $show_override = 1;
}
else {
  $show_produced_sqft = $produced_sqft;
  $show_override = 0;
}
$percent = ($show_produced_sqft / $project_sqft) * 100;
$percent = round($percent, 1);
if($show_override==1){
  $override_area = "<input type='button' name='button2' value='Override Calculated Values' onclick=\\\"showpercent('form')\\\">";
}
else {
  $override_area = "<input type='button' name='button3' value='Reset Override, Use Calculated Values' onclick=\\\"showpercent('reset')\\\">";
}
if($show_produced_sqft==0) $override_area = "";
if($CAN_EDIT_THIS_OPM==0) $override_area = "";

$override_area = jsclean($override_area);
?>
document.getElementById('percent').innerHTML = '<?=$percent?>%';
document.getElementById('show_produced_sqft').innerHTML = '<?=$show_produced_sqft?>';
document.getElementById('override_area').innerHTML = '<?=$override_area?>';
<?php } ?>