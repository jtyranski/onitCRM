<?php include "includes/functions.php"; ?>
<?php
$sql = "SELECT count(*) from toolbox_items where tool_master_id=19 and master_id='" . $SESSION_MASTER_ID . "'";
$USING_ACTIONPLAN = getsingleresult($sql);

$sql = "SELECT use_groups from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$USING_GROUPS = getsingleresult($sql);

$USING_GROUPS_MASTER = 1;
if($USING_GROUPS){
  $USING_GROUPS_MASTER = 0;
  if($SESSION_GROUPS=="" && $SESSION_SUBGROUPS=="") $USING_GROUPS_MASTER=1;
}
?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
  
  <strong>Admin Features</strong>
  <br><br>
  <?php if($USING_GROUPS_MASTER){?>
  <a href="admin_info.php">Edit Company Info</a><br><br>
  <?php } ?>
  
  <a href="admin_users.php">User Edit</a><br><br>
  <a href="ipad_toolbox_admin.php">Toolbox Admin</a><br><br>
  <?php if($SESSION_OPPORTUNITIES==1 && $USING_GROUPS_MASTER){?>
  <a href="admin_opportunities.php">Opportunities Products Admin</a><br><br>
  <?php } ?>
  
  <?php if($USING_GROUPS_MASTER){?>
  <a href="admin_def.php">Edit Default Deficiency List</a><br><br>
  <a href="admin_mat.php">Edit Default Materials List</a><br><br>
  <a href="admin_proposaltemplate.php">Company Proposal Templates</a><br><br>
  <?php } ?>
  
  <a href="admin_doc_template.php">Saved Document Templates</a><br><br>
  <?php if($USING_ACTIONPLAN && $USING_GROUPS_MASTER){?>
  <a href="admin_actionplan.php">ActionPlan Report Values</a><br><br>
  <?php } ?>
  <?php if($USING_GROUPS){?>
  <a href="admin_groups.php">Manage Groups</a><br><br>
  <?php } ?>
  <?php if($SESSION_MASTER_ID==1870){ // Holland export ?>
  <a href="holland_export.php" target="_blank">Manual Service Dispatch Export (Holland Roofing Only)</a><br><br>
  <?php } ?>

  


