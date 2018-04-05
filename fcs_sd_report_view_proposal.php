<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];
$action = $_GET['action'];

if($action=="viewedit"){
  $edit_proposal = $_SESSION['edit_proposal'];
  
  if($edit_proposal == "" || $edit_proposal==0) {
    $_SESSION['edit_proposal'] = 1;
  }
  if($edit_proposal==1) {
    $_SESSION['edit_proposal'] = 0;
  }
}
$sql = "SELECT proposal_locked from am_leakcheck where leak_id='$leak_id'";
$proposal_locked = getsingleresult($sql);

if($action=="lock"){
  if($proposal_locked==1){
    $sql = "UPDATE am_leakcheck set proposal_locked=0 where leak_id='$leak_id'";
	$proposal_locked=0;
  }
  else {
    $sql = "UPDATE am_leakcheck set proposal_locked=1 where leak_id='$leak_id'";
	$proposal_locked=1;
  }
  executeupdate($sql);
}

$sql = "SELECT section_id, property_id, allow_proposal_done from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$section_id = stripslashes($record['section_id']);
$property_id = stripslashes($record['property_id']);
$allow_proposal_done = stripslashes($record['allow_proposal_done']);

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name, address, city, state, zip, logo, master_id from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['logo'] = stripslashes($record['logo']);
$company['master_id'] = stripslashes($record['master_id']);

$sql = "SELECT logo, master_name, payment_terms from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master['master_name'] = stripslashes($record['master_name']);
$master['master_logo'] = stripslashes($record['logo']);
$master['payment_terms'] = stripslashes($record['payment_terms']);


$sql = "SELECT site_name, address, city, state, zip, roof_size from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['roof_size'] = stripslashes($record['roof_size']);

$sitepoints = 0;
$sitesections = 0;
$sql = "SELECT sqft, grade, roof_type from sections where property_id='$property_id' and display=1 and section_type='$section_type'";
$res_sec = executequery($sql);
while($sections = go_fetch_array($res_sec)){
  if($sections['grade'] != "0") $sitesections++;
  $property['sqft'] += $sections['sqft'];
  $grade = $sections['grade'];
  $this_points = $gradevalue[$grade];
  $sitepoints += $this_points;
}
$siteavg = round($sitepoints / $sitesections);
$property['grade'] = $gradevalue_reverse[$siteavg];
  
if($property['sqft'] == 0 || $property['sqft']=="") $property['sqft'] = $property['roof_size'] * 100;


if($company['logo'] != ""){ 
  $max_width = 150;
  $max_height = 100;
  list($width, $height) = getimagesize($CORE_URL . "uploaded_files/logos/" . $company['logo']);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }
  
  $company_logo = "<img src=\"" . $CORE_URL . "uploaded_files/logos/" . $company['logo'] . "\" width=\"" . $width . "\" height=\"" . $height . "\">";
} else { 
  $company_logo = "<div class=\"main_large\">" . $company['name'] . "</div>";
}


$sql = "SELECT section_name, sqft, property_id, roof_type, grade, inspector, notes, main_photo, property_type, 
date_format(installation_date, \"%m/%d/%Y\") as installation_date_pretty, multiple, section_type, 
date_format(inspection_date, \"%m/%d/%Y\") as inspection_date_pretty 
from sections where section_id='$section_id'";
$result = executequery($sql);
$section = go_fetch_array($result);
$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $section['inspector'] . "'";
$section['inspector_name'] = stripslashes(getsingleresult($sql));
if($section['grade']==0) $section['grade'] = "";

$multiple = $section['multiple'];
$section_type = $section['section_type'];
$sql = "SELECT count(*) from opportunities where opp_stage_id=10 and property_id='$property_id' and display=1";
$active_rtm = getsingleresult($sql);



$section_report = "";

$section_report = str_replace("[SECTION NAME]", stripslashes($section['section_name']), $section_report);
$section_report = str_replace("[SQFT]", stripslashes(number_format($section['sqft'], 0)), $section_report);
$section_report = str_replace("[ROOF TYPE]", stripslashes($section['roof_type']), $section_report);
$section_report = str_replace("[INSTALLATION DATE PRETTY]", stripslashes($section['installation_date_pretty']), $section_report);
$section_report = str_replace("[GRADE]", stripslashes($section['grade']), $section_report);
$section_report = str_replace("[INSPECTION DATE PRETTY]", stripslashes($section['inspection_date_pretty']), $section_report);
$section_report = str_replace("[INSPECTOR NAME]", stripslashes($section['inspector_name']), $section_report);
$section_report = str_replace("[SERVICEMAN]", stripslashes($serviceman), $section_report);
$section_report = str_replace("[PROPOSAL DATE]", stripslashes($proposal_date_pretty), $section_report);

$def_photo = "";
$def_name = "";
$def_foo = "";
$def_def = "";
$def_action = "";
$def_cost = "";
$def_bar = "";
$def_type = "";
$def_quantity = "";
$def_quantity_unit = "";
$def_upsell = "";

$sql = "SELECT date_format(inprogress_date, \"%Y-%m-%d\") from am_leakcheck where leak_id='$leak_id'";
$proposaldate = getsingleresult($sql);

  $counter = 0;
$sql = "SELECT section_id from sections where property_id='$property_id'";
$res_sections = executequery($sql);
while($rec_sections = go_fetch_array($res_sections)){
    $x_section_id = $rec_sections['section_id'];
    if($x_section_id == "") continue;
    $sql = "SELECT *, date_format(date_recorded, \"%m/%d/%Y\") as daterec from sections_def where section_id='$x_section_id' and complete=0 and (date_recorded like '$proposaldate%' or from_app=0) order by def_id";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $def_photo[$counter] = $record['photo'];
	  $def_foo[$counter] = stripslashes($record['name']);
	  $def_def[$counter] = stripslashes(nl2br($record['def']));
	  $def_action[$counter] = stripslashes(nl2br($record['action']));
	  $def_cost[$counter] = $record['cost'];
	  $def_bar[$counter] = $record['cost'];
	  $def_type[$counter] = $record['def_type'];
	  $def_quantity[$counter] = $record['quantity'];
	  $def_quantity_unit[$counter] = $record['quantity_unit'];
	  $def_id[$counter] = $record['def_id'];
	  $def_daterec[$counter] = $record['daterec'];
	  $def_upsell[$counter] = $record['upsell'];
	  $counter++;
    }
}

$def_table = "<table class=\"main\">";
$total_def_cost = 0;
$def_counter = 0;
for($x=0;$x<=sizeof($def_photo);$x++){
  if($def_id[$x] == "") continue;
  //if($def_cost[$x] == "" || $def_cost[$x]==0) continue;
  if($def_def[$x]=="") continue;
  $total_def_cost += $def_bar[$x];
  $total_def_upsell += $def_upsell[$x];
  $def_counter++;
  $def_table .= "  
  <tr>
  <td valign='top'>$def_counter<br>
  </td>
  <td valign=\"top\">";
  if($def_photo[$x] != ""){
  $max_width = 300;
  $max_height = 200;
  list($width, $height) = getimagesize($CORE_URL . "uploaded_files/def/" . $def_photo[$x]);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }
  $def_table .= "
  <img src=\"" . $CORE_URL . "uploaded_files/def/" . $def_photo[$x] . "\" width=\"" . $width . "\" height=\"" . $height . "\">";
  if($_SESSION['edit_proposal']==1){
    $def_table .= "<br>
    <a href='rotate.php?image=" . $def_photo[$x] . "&path=def&degrees=90&leak_id=$leak_id'>
    <img src='" . $FCS_URL . "images/rotate270.png' border='0'>
    </a>
    <a href='rotate.php?image=" . $def_photo[$x] . "&path=def&degrees=270&leak_id=$leak_id'>
    <img src='" . $FCS_URL . "images/rotate90.png' border='0'>
    </a>";
  }
  }
  $def_table .= "
  </td>
  <td valign=\"top\">";
  if($_SESSION['edit_proposal']==0){
    $def_table .= "<input type='checkbox' name='defdone[]' value='" . $def_id[$x] . "'>Get Done<br>";
  }
  $def_table .= "
  <input type='hidden' name='def_id[]' value='" . $def_id[$x] . "'>
  " . $def_foo[$x] . "";
  if($def_type[$x]=="R") {
    $def_table .= "(Remedial)";
  }
  else{
    $def_table .= "(Emergency)";
  }
  if($def_daterec[$x] != "00/00/0000") $def_table .= " - " . $def_daterec[$x];
  $def_table .= "<br>";
  if($_SESSION['edit_proposal']==1){
    $def_table .= "Quantity: <input type='text' name='quantity[]' size='5' value='" . $def_quantity[$x] . "'> " . $def_quantity_unit[$x] . "<br><br>";
  }
  else {
    $def_table .= "Quantity: " . $def_quantity[$x] . " " . $def_quantity_unit[$x] . "<br><br>";
  }
  
  ob_start();
  ?>
  <input type="hidden" name="def_name[]" value="<?=$def_foo[$x]?>" id="name_<?=$def_id[$x]?>">
  <?php
  if($_SESSION['edit_proposal']==1){
  ?>
  <div id="def_category_select_<?=$def_id[$x]?>">
<select name="def_category" onChange="ajax_def_category(this, '<?=$def_id[$x]?>')">
<option value="0">General</option>
<?php
$sql = "SELECT category_id from def_list_master where def_name=\"" . $def_foo[$x] . "\" and master_id = '" . $SESSION_MASTER_ID . "'";
$category_id = getsingleresult($sql);
if($category_id=="") $category_id = 0;

$sql = "SELECT category_id, category_name from def_list_categories where master_id='" . $SESSION_MASTER_ID . "' order by category_name";
$result_cat = executequery($sql);
while($record_cat = go_fetch_array($result_cat)){
  ?>
  <option value="<?=$record_cat['category_id']?>"<?php if($record_cat['category_id']==$category_id) echo " selected";?>><?=stripslashes($record_cat['category_name'])?></option>
  <?php
}
?>
</select>
</div>
<div id="def_list_select_<?=$def_id[$x]?>">
<select name="def_list" onChange="ajax_def(this, '<?=$def_id[$x]?>')">
<option value=""></option>
<?php
$counter=0;
$sql = "SELECT id, def_name from def_list_master where visible=1 and master_id='" . $SESSION_MASTER_ID . "' and category_id='$category_id'";
$result_list = executequery($sql);
while($record_list = go_fetch_array($result_list)){
  $counter++;
  ?>
  <option value="<?=$record_list['id']?>"<?php if($record_list['def_name']==$def_foo[$x]) echo " selected";?>><?=$counter?> <?=stripslashes($record_list['def_name'])?></option>
  <?php
}
?>
</select>
</div>
  <?php
  } // end if in edit mode
  $extra_stuff = ob_get_contents();
  ob_end_clean();
  //$extra_stuff = jsclean($extra_stuff);
  $def_table .= $extra_stuff;
  
  $def_table .= "Deficiency:<br>";
  if($_SESSION['edit_proposal']==1){
    $def_table .= "<textarea name='def[]' rows='6' cols='40' id='def_" . $def_id[$x] . "'>" . $def_def[$x] . "</textarea><br><br>";
  }
  else {
    $def_table .= nl2br($def_def[$x]) . "<br><br>";
  }
  
  $def_table .= "Corrective Action:<br>";
  if($_SESSION['edit_proposal']==1){
    $def_table .= "<textarea name='action[]' rows='6' cols='40' id='action_" . $def_id[$x] . "'>" . $def_action[$x] . "</textarea><br><br>";
  }
  else {
    $def_table .= nl2br($def_action[$x]) . "<br><br>";
  }
  $def_table .= "Estimated Repair Cost:<br>$";

  if($_SESSION['edit_proposal']==1){
    $def_table .= "<input type='text' name='cost[]' value='" . $def_bar[$x] . "' size='10'>";
	$def_table .= "&nbsp; &nbsp;";
	$def_table .= "Upsell: $<input type='text' name='upsell[]' value='" . $def_upsell[$x] . "' size='10'><br><br>";
  }
  else {
    $def_table .= number_format($def_bar[$x], 2);
	if($def_upsell[$x]){
	  $def_table .= "&nbsp; &nbsp;";
	  $def_table .= "Upsell: $" . number_format($def_upsell[$x], 2) . "<br><br>";
	}
  }

  $def_table .= "
  </td>
  </tr>";
  
}

$def_table .= "</table>";




if($section['main_photo'] != ""){

  $max_width = 440;
  $max_height = 250;
  list($width, $height) = getimagesize($CORE_URL . "uploaded_files/sections/" . $section['main_photo']);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }

  $section_photo = "<img src=\"" . $CORE_URL . "uploaded_files/sections/" . $section['main_photo'] . "\" border=\"0\" width=\"" . $width . "\" height=\"" . $height . "\">";
  
}

ob_start();
?>

<div style="font-family:Arial, Helvetica, sans-serif; width:850px;">
<?php if($allow_proposal_done==1){?>
<div align="right">
<input type="submit" name="submit1" value="Get Proposal Done">
<input type="submit" name="submit1" value="Proposal Declined"><br>

</div>
<?php } ?>
<?php if($_SESSION['edit_proposal'] != 1 && $_SESSION['rr_edit_mode'] != 1){?>
<div align="right">
<input type="button" name="buttonsendproposal" value="Send Client Proposal" onclick="document.location.href='fcs_sd_proposal.php?leak_id=<?=$leak_id?>'">
</div>
<?php } ?>
<div align="right">
<?php if($proposal_locked==1){ ?>
Proposal is <font color="red">LOCKED</font> <input type="button" name="buttonlockproposal" value="Unlock Proposal" onclick="proposal_lock()">
<?php } else { ?>
Proposal is <font color="green">UNLOCKED</font> <input type="button" name="buttonlockproposal" value="Lock Proposal" onclick="proposal_lock()">
<?php } ?>
<br>
<?php if($_SESSION['edit_proposal']==1){ ?>
<input type="submit" name="submit1" value="Update Proposal / View Mode">
<?php } else { ?>
<input type="button" name="buttonproposalviewedit" value="Edit Mode" onclick="proposal_viewedit()">
<?php } ?>
</div>
<table width="100%">
<tr>
<td>

</td>
<td align="right">
<?php if($MASTER_LOGO != ""){ ?>
<img src="<?=$CORE_URL?>uploaded_files/master_logos/<?=$MASTER_LOGO?>">
<?php } ?>
</td>
</tr>
</table>
<table class="main" width="100%">
<tr>
<td valign="middle">
<?=$company_logo?>
</td>
<td align="left" valign="middle"> <!-- changed align to left (JT) -->
<div style="float:right;">  <!-- moves to right side of page (JT) -->
<strong>
<?=$property['site_name']?><br>
<?=$property['address']?><br>
<?=$property['city']?>, <?=$property['state']?>
</strong>
</div>  <!-- ends DVI (JT) -->
</td>
</tr>
</table>

<br>
</div>

<div class="main">
<div style="font-family:Arial, Helvetica, sans-serif; width:850px;">
<table class="main" width="100%">
<tr>
<td valign="top" align="right">
<?=stripslashes($section_report)?>
</td>
<td valign="top" align="right">
<?=$section_photo?>
</td>
</tr>
</table>
<br>
<?php
$grand_total_def_cost = $total_def_cost + $total_def_upsell;
?>
<div class="main_large">Deficiencies</div>
<div class="main">
<?php if($total_def_upsell){ ?>
Total: $<?=number_format($total_def_cost, 2)?> + $<?=number_format($total_def_upsell, 2)?> = $<?=number_format($grand_total_def_cost, 2)?>
<?php } else { ?>
Total: $<?=number_format($total_def_cost, 2)?>
<?php } ?>
<br></div>
<?=$def_table?>

</div>
</div>
<br><br>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
div = document.getElementById('proposal_info');
div.innerHTML = '<?php echo $html; ?>';