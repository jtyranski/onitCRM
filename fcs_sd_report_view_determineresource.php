<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];
$servicemen_id = $_GET['servicemen_id'];
$labor_rate = $_GET['labor_rate'];
$priority_id = $_GET['priority_id'];
$open_resource = $_GET['open_resource'];

/*
$sql = "SELECT resource from users where user_id='$servicemen_id'";
$resource = getsingleresult($sql);
*/
if(go_reg("\*", $servicemen_id)){
$resource_id = go_reg_replace("\*", "", $servicemen_id);
$newlr = $labor_rate;
if($labor_rate==0 || $labor_rate==""){
  $lr = $priority_id;
  if($lr==1) $lr = "";
  $sql = "SELECT labor_rate" . $lr . " from prospects_resources where prospect_id='$resource_id'";
  $newlr = getsingleresult($sql);
}

$show_button = 1;
if($open_resource==1){
  $sql = "SELECT resource_id from am_leakcheck where leak_id='$leak_id'";
  $existing_resource = getsingleresult($sql);
  if($existing_resource==$resource_id){
    ?>
	
	document.getElementById('confirmationbutton').value="Resend Confirmation";
	<?php
  }
  else {
    $show_button = 0;
  }
}

$sql = "SELECT accept_resource from am_leakcheck where leak_id='$leak_id'";
$accept_resource = getsingleresult($sql);
if($accept_resource) $show_button = 0;

if($show_button){
?>
  document.getElementById('resource_email').style.display="";
  document.getElementById('labor_rate').value="<?=$newlr?>";
  document.getElementById('bt_rate').value="<?=$newlr?>";
  <?php
}
?>
document.getElementById('linktoresource').innerHTML = "<a href='view_company.php?prospect_id=<?=$resource_id?>' target='_blank'>View</a>";
//alert("<?=$existing_resource?> , <?=$resource_id?>");
<?php /*
document.getElementById('buttonresource').onclick=function(){ document.location.href='fcs_sd_report_resourceconfirm.php?leak_id=<?=$leak_id?>&servicemen_id=<?=$servicemen_id?>';}
*/?>
<?php } ?>