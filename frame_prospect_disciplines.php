<?php
include "includes/header_white.php";

$prospect_id = go_escape_string($_GET['prospect_id']);

?>
<form action="frame_prospect_disciplines_action.php" method="post">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<div class="main">
<strong>This resource deals in the following:</strong><br><br>
<?php
  $sql = "SELECT dis_id, discipline from disciplines order by dis_id";
  $result = executequery($sql);
  $DIS_NAMES = array();
  while($record = go_fetch_array($result)){
    $xdis_id = $record['dis_id'];
	$DIS_NAMES[$xdis_id] = stripslashes($record['discipline']);
  }
  $sql = "SELECT dis_id from vendor_to_dis where prospect_id='$prospect_id'";
  $result = executequery($sql);
  $DIS_USE = array();
  while($record = go_fetch_array($result)){
    $xdis_id = $record['dis_id'];
	$DIS_USE[$xdis_id] = stripslashes($record['dis_id']);
  }
  
  for($x=0;$x<sizeof($SESSION_DIS);$x++){ 
    $xdis_id = $SESSION_DIS[$x];?>
	<input type="checkbox" name="dis_id[]" value="<?=$xdis_id?>"<?php if(in_array($xdis_id, $DIS_USE)) echo " checked";?>><?=$DIS_NAMES[$xdis_id]?><br>
	<?php
  }
?>
</div>
<input type="submit" name="submit1" value="Update">
</form>