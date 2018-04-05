<body style="margin:0px 0px 0px 0px;">
<meta http-equiv="refresh" content="90;url=check_dispatch_approval.php">
<?php
include "includes/functions.php";


$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
$test = getsingleresult($sql);
if($test==0) exit;

$sql = "SELECT alert_dispatch_approval from users where user_id='" . $SESSION_USER_ID . "'";
$alert = getsingleresult($sql);
if($alert){

// only display for groups the user is a member of
$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " c.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and c.subgroups in ($subgroups_search)";
  }
}  // end groups filter


  $sql = "SELECT count(a.leak_id) from am_leakcheck a, prospects b, properties c where
  a.property_id=c.property_id and b.prospect_id=c.prospect_id and b.master_id='" . $SESSION_MASTER_ID . "' 
  and a.ready_for_approval=1 and $groups_clause";
  $test = getsingleresult($sql);
  //echo "<!-- $sql -->\n";
  if($test){

  ?>
  <div style="position:relative;">
  <div style="float:left;">
  <a href="toolbox.php?go=report_sdapproval.php" target="_parent"><img src="images/alert-icon-blue.png" border="0"></a>
  </div>
  <div style="float:left; padding-left:4px;"><?=$test?></div>
  </div>
  <?php
  }
  
}





  
?>