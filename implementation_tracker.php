<?php include "includes/functions.php"; ?>
<?php  // this is just for fcs login ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$dw = 175;
$dh = 25;
$main_h = $dh + 2;
$imp_complete = $_GET['imp_complete'];
if($imp_complete=="") $imp_complete = 0;

if($imp_complete==0){
  $mark_complete=1;
  $switch_filler = "Show Implementations already marked as complete";
}
else {
  $mark_complete = 0;
  $switch_filler = "Show incomplete Implemenations";
}

?>
<script>
function implement(stage, turnon, master_id){
  if(turnon==0){
    cf = confirm("Are you sure you want to turn off this stage of implementation?");
	if(cf){
	  document.location.href="implementation_tracker_action.php?master_id=" + master_id + "&turnon=" + turnon + "&stage=" + stage + "&imp_complete=<?=$imp_complete?>";
	}
  }
  else {
    document.location.href="implementation_tracker_action.php?master_id=" + master_id + "&turnon=" + turnon + "&stage=" + stage + "&imp_complete=<?=$imp_complete?>";
  }
}
</script>

<a href="<?=$_SERVER['SCRIPT_NAME']?>?imp_complete=<?=$mark_complete?>" style="font-family:Arial, Helvetica, sans-serif; text-decoration:none;"><?=$switch_filler?></a><br>
<div style="width:100%; position:relative;">
<div style="float:left; width:300px; font-family:Arial, Helvetica, sans-serif;"><strong>Client</strong></div>
<div style="float:left; width:<?=$dw?>px; font-family:Arial, Helvetica, sans-serif;" align="center"><strong>Intro</strong></div>
<div style="float:left; width:<?=$dw?>px; font-family:Arial, Helvetica, sans-serif;" align="center"><strong>Inspections</strong></div>
<div style="float:left; width:<?=$dw?>px; font-family:Arial, Helvetica, sans-serif;" align="center"><strong>Core</strong></div>
<div style="float:left; width:<?=$dw?>px; font-family:Arial, Helvetica, sans-serif;" align="center"><strong>Dispatch</strong></div>
</div>
<div style="clear:both;"></div>
<?php
$counter = 0;
$sql = "SELECT master_id, master_name, imp_intro, imp_inspection, imp_core, imp_dispatch,
date_format(imp_intro_date, \"%m/%d/%Y\") as imp_intro_date_pretty, 
date_format(imp_inspection_date, \"%m/%d/%Y\") as imp_inspection_date_pretty, 
date_format(imp_core_date, \"%m/%d/%Y\") as imp_core_date_pretty, 
date_format(imp_dispatch_date, \"%m/%d/%Y\") as imp_dispatch_date_pretty, 
date_format(imp_complete_date, \"%m/%d/%Y\") as imp_complete_date_pretty
from master_list where active=1 and imp_complete='$imp_complete' and demo=0 order by master_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $counter++;
  
  $intro_bg = $inspection_bg = $core_bg = $dispatch_bg = $main_bg = "white";
  $intro_date = $inspection_date = $core_date = $dispatch_date = " ";
  if($counter % 2) $main_bg = $ALT_ROW_COLOR;
  
  if($record['imp_intro']==1) {
    $intro_bg = "#B80000";
	$intro_date = $record['imp_intro_date_pretty'];
	$intro_click = "implement('intro', '0', '" . $record['master_id'] . "')";
  }
  else {
    $intro_click = "implement('intro', '1', '" . $record['master_id'] . "')";
  }
  
  if($record['imp_inspection']==1) {
    $inspection_bg = "#B84F00";
	$inspection_date = $record['imp_inspection_date_pretty'];
	$inspection_click = "implement('inspection', '0', '" . $record['master_id'] . "')";
  }
  else {
    $inspection_click = "implement('inspection', '1', '" . $record['master_id'] . "')";
  }
  
  if($record['imp_core']==1) {
    $core_bg = "#B8B700";
	$core_date = $record['imp_core_date_pretty'];
	$core_click = "implement('core', '0', '" . $record['master_id'] . "')";
  }
  else {
    $core_click = "implement('core', '1', '" . $record['master_id'] . "')";
  }
  
  if($record['imp_dispatch']==1) {
    $dispatch_bg = "#078300";
	$dispatch_date = $record['imp_dispatch_date_pretty'];
	$dispatch_click = "implement('dispatch', '0', '" . $record['master_id'] . "')";
  }
  else {
    $dispatch_click = "implement('dispatch', '1', '" . $record['master_id'] . "')";
  }
  
  $showdispatch = 1;
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $record['master_id'] . "'";
  $test = getsingleresult($sql);
  if($test ==0) $showdispatch = 0;
  
  $complete_filler = "complete";
  if($imp_complete==1) $complete_filler = $record['imp_complete_date_pretty'];
  
  ?>
  <div style="width:100%; position:relative;">
  <div style="float:left; width:300px; background-color:<?=$main_bg?>; height:<?=$main_h?>px; vertical-align:middle; font-family:Arial, Helvetica, sans-serif;"><?=stripslashes($record['master_name'])?></div>
  <div style="float:left; width:<?=$dw?>px; border:1px solid black; height:<?=$dh?>px; background-color:<?=$intro_bg?>; color:#000000; font-family:Arial, Helvetica, sans-serif;" align="center" onClick="<?=$intro_click?>" onMouseOver="this.style.cursor='pointer'"><?=$intro_date?></div>
  <div style="float:left; width:<?=$dw?>px; border:1px solid black; height:<?=$dh?>px; background-color:<?=$inspection_bg?>; color:#000000; font-family:Arial, Helvetica, sans-serif;" align="center" onClick="<?=$inspection_click?>" onMouseOver="this.style.cursor='pointer'"><?=$inspection_date?></div>
  <div style="float:left; width:<?=$dw?>px; border:1px solid black; height:<?=$dh?>px; background-color:<?=$core_bg?>; color:#000000; font-family:Arial, Helvetica, sans-serif;" align="center" onClick="<?=$core_click?>" onMouseOver="this.style.cursor='pointer'"><?=$core_date?></div>
  <?php if($showdispatch==1){ ?>
  <div style="float:left; width:<?=$dw?>px; border:1px solid black; height:<?=$dh?>px; background-color:<?=$dispatch_bg?>; color:#000000; font-family:Arial, Helvetica, sans-serif;" align="center" onClick="<?=$dispatch_click?>" onMouseOver="this.style.cursor='pointer'"><?=$dispatch_date?></div>
  <?php } else { ?>
  <div style="float:left; width:<?=$dw?>px; border:1px solid white; height:<?=$dh?>px; background-color:<?=$dispatch_bg?>; color:#000000; font-family:Arial, Helvetica, sans-serif;" align="center"> </div>
  <?php } ?>
  <div style="float:left; width:<?=$dw?>px; background-color:<?=$main_bg?>; height:<?=$main_h?>px;" align="center"><a href="javascript:implement('complete', '<?=$mark_complete?>', '<?=$record['master_id']?>')" style="font-family:Arial, Helvetica, sans-serif; text-decoration:none;"><?=$complete_filler?></a></div>
  </div>
  <div style="clear:both;"></div>
  <?php
}
?>



