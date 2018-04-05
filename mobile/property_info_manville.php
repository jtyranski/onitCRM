<?php include "includes/header.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php
$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT * from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company_name = stripslashes($record['company_name']);
$logo = $record['logo'];

$sql = "SELECT *, date_format(lastaction, \"%m/%d/%y\") as datepretty from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
?>
<script src="includes/calendar.js"></script>
<script>
function checkform(f){
  errmsg = "";
  
  if(f.comp_amount.value != ""){
    if(isNaN(f.comp_amount.value)) { errmsg += "Please enter only numbers for comp amount. \n"; }
  }
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}

function cleardate(x){
  x.value="";
}

function ra_date_change(){
  document.manvilleform.ra_change_date_pretty.value="<?=date("m/d/Y")?>";
}
</script>

<?php
$user_id = $SESSION_USER_ID;
// in future, check for property type to know which table to pull info
$sql = "SELECT *, 
date_format(ra_change_date, \"%m/%d/%Y\") as ra_change_date_pretty,
date_format(pfri_shipped_date, \"%m/%d/%Y\") as pfri_shipped_date_pretty, 
date_format(completion_date, \"%m/%d/%Y\") as completion_date_pretty, 
date_format(inspected_date, \"%m/%d/%Y\") as inspected_date_pretty, 
date_format(remed_comp, \"%m/%d/%Y\") as remed_comp_pretty
 from properties_manville where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);

$comp_name = stripslashes($record['comp_name']);
$comp_address = stripslashes($record['comp_address']);
$comp_city = stripslashes($record['comp_city']);
$comp_state = stripslashes($record['comp_state']);
$comp_zip = stripslashes($record['comp_zip']);
//$comp_company = stripslashes($record['comp_company']);
$comp_amount = stripslashes($record['comp_amount']);
$comp_number = stripslashes($record['comp_number']);
$key_number = stripslashes($record['key_number']);
$claim_status = stripslashes($record['claim_status']);
		
$pfri_shipped = stripslashes($record['pfri_shipped']);
$corrosion_level = stripslashes($record['corrosion_level']);
$completion_date_pretty = stripslashes($record['completion_date_pretty']);




if($completion_date_pretty == "00/00/0000") $completion_date_pretty = "";

$jm_guarantee = stripslashes($record['jm_guarantee']);
/*
if($jm_guarantee==1){
  $jm_guarantee = "Yes";
}
else {
  $jm_guarantee = "No";
}
*/
$pfri_shipped_date_pretty = stripslashes($record['pfri_shipped_date_pretty']);
if($pfri_shipped_date_pretty == "00/00/0000") $pfri_shipped_date_pretty = "";
$eligible = stripslashes($record['eligible']);
/*
if($eligible==1){
  $eligible = "Yes";
}
else {
  $eligible = "No";
}
*/
$eligibility_comments1 = stripslashes($record['eligibility_comments1']);
$remediated = stripslashes($record['remediated']);
/*
if($remediated==1){
  $remediated = "Yes";
}
else {
  $remediated = "No";
}
*/
$inspected = stripslashes($record['inspected']);
/*
if($inspected==1){
  $inspected = "Yes";
}
else {
  $inspected = "No";
}
*/
$inspection_status = stripslashes($record['inspection_status']);
$repairs_required = stripslashes($record['repairs_required']);
/*
if($repairs_required==1){
  $repairs_required = "Yes";
}
else {
  $repairs_required = "No";
}
*/
$repairs_completed = stripslashes($record['repairs_completed']);
$layers_pfri = stripslashes($record['layers_pfri']);
$eligibility_comments2 = stripslashes($record['eligibility_comments2']);
		
$inspected_date_pretty = stripslashes($record['inspected_date_pretty']);
if($inspected_date_pretty == "00/00/0000") $inspected_date_pretty = "";
$test_cuts = stripslashes($record['test_cuts']);
$remed_comp_pretty = stripslashes($record['remed_comp_pretty']);
if($remed_comp_pretty == "00/00/0000") $remed_comp_pretty = "";
//$inspection_number = stripslashes($record['inspection_number']);

$ra_change_date_pretty = stripslashes($record['ra_change_date_pretty']);
if($ra_change_date_pretty == "00/00/0000") $ra_change_date_pretty = "";
$ra_stage = stripslashes($record['ra_stage']);



$sql = "SELECT identifier, ro_status from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ro_status = stripslashes($record['ro_status']);
$identifier = stripslashes($record['identifier']);
$counter=0;
?>

<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<?php include "includes/property_nav.php"; ?>
</td>
</tr>
</table>
<div class="breadcrumb">
<a href="company.php" class="breadcrumb">Company</a> > <a href="company_details.php?prospect_id=<?=$prospect_id?>" class="breadcrumb">
<?=$company_name?></a> > 
<a href="property_details.php?property_id=<?=$property_id?>" class="breadcrumb"><?=$site_name?></a> > Property Info</div>
<table width="100%" class="main">
<tr>
<td valign="top">
<div class="main_superlarge"><?=$site_name?></div>
<div class="main_large"><?=$address?><br><?=$city?>, <?=$state?> <?=$zip?></div>
</td>
</tr>
</table>
<form action="property_info_manville_action.php" method="post" onSubmit="return checkform(this)" name="manvilleform">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<table class="main">
<tr>
<td valign="top">
<table class="main" id="manville">
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Sales Status</td>
<td>
<select name="ra_stage" onChange="ra_date_change()" style="max-width:150px;">
<?php
$sql = "SELECT * from sales_stage order by sales_stage";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['sales_stage_id']?>"<?php if($record['sales_stage_id']==$ra_stage) echo " selected";?>><?=stripslashes($record['sales_stage'])?></option>
  <?php
}
?>

</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">RA Change Date</td>
<td>
<input size="10" type="text" class="largerbox" name="ra_change_date_pretty" <?php if($ra_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$ra_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('ra_change_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Key Number</td>
<td><input type="text" class="largerbox" name="key_number" value="<?=$key_number?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">RO Status</td>
<td>
<select name="ro_status">
<option value=""<?php if($ro_status=="") echo " selected";?>></option>
<option value="Open"<?php if($ro_status=="Open") echo " selected";?>>Open</option>
<option value="Closed"<?php if($ro_status=="Closed") echo " selected";?>>Closed</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Identifer</td>
<td><input type="text" class="largerbox" name="identifier" value="<?=$identifier?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Claim Status</td>
<td>
<select name="claim_status">
<option value="OPEN"<?php if($claim_status=="OPEN") echo " selected";?>>OPEN</option>
<option value="CLOSED"<?php if($claim_status=="CLOSED") echo " selected";?>>CLOSED</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Eligible</td>
<td>
<select name="eligible">
<option value="1"<?php if($eligible==1) echo " selected";?>>YES</option>
<option value="0"<?php if($eligible==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Eligibility Comments 1</td>
<td><textarea name="eligibility_comments1" rows="2" cols="20"><?=$eligibility_comments1?></textarea></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Eligibility Comments 2</td>
<td><textarea name="eligibility_comments2" rows="2" cols="20"><?=$eligibility_comments2?></textarea></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Remediated</td>
<td>
<select name="remediated">
<option value="1"<?php if($remediated==1) echo " selected";?>>YES</option>
<option value="0"<?php if($remediated==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">PFRI Shipped</td>
<td><input type="text" class="largerbox" name="pfri_shipped" value="<?=$pfri_shipped?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">PFRI Shipped Date</td>
<td>
<input size="10" type="text" class="largerbox" name="pfri_shipped_date_pretty" <?php if($pfri_shipped_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$pfri_shipped_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('pfri_shipped_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Completion Date</td>
<td>
<input size="10" type="text" class="largerbox" name="completion_date_pretty" <?php if($completion_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$completion_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('completion_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">JM Guarantee</td>
<td>
<select name="jm_guarantee">
<option value="1"<?php if($jm_guarantee==1) echo " selected";?>>YES</option>
<option value="0"<?php if($jm_guarantee==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp Number</td>
<td><input type="text" class="largerbox" name="comp_number" value="<?=$comp_number?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp Amount$</td>
<td><input type="text" class="largerbox" name="comp_amount" value="<?=$comp_amount?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Corrosion Level</td>
<td><input type="text" class="largerbox" name="corrosion_level" value="<?=$corrosion_level?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp Name</td>
<td><input type="text" class="largerbox" name="comp_name" value="<?=$comp_name?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp Address</td>
<td><input type="text" class="largerbox" name="comp_address" value="<?=$comp_address?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp City</td>
<td><input type="text" class="largerbox" name="comp_city" value="<?=$comp_city?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp State</td>
<td>
<select name="comp_state">
<option value="">Select a State</option>
<?php
$sql2 = "SELECT * from states order by state_name";
$result2 = executequery($sql2);
while($record2 = go_fetch_array($result2)){
  ?><option value="<?=$record2['state_code']?>"<?php if ($comp_state==$record2['state_code']) echo " selected"; ?>><?=$record2['state_name']?></option>
  <?php
}
?>
</select>
</td>

</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Comp Zip</td>
<td><input type="text" class="largerbox" name="comp_zip" value="<?=$comp_zip?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Inspected</td>
<td>
<select name="inspected">
<option value="1"<?php if($inspected==1) echo " selected";?>>YES</option>
<option value="0"<?php if($inspected==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Inspection Status</td>
<td><input type="text" class="largerbox" name="inspection_status" value="<?=$inspection_status?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Inspected Date</td>
<td>
<input size="10" type="text" class="largerbox" name="inspected_date_pretty" <?php if($inspected_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$inspected_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('inspected_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Test Cuts</td>
<td><input type="text" class="largerbox" name="test_cuts" value="<?=$test_cuts?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Repairs Required</td>
<td>
<select name="repairs_required">
<option value="1"<?php if($repairs_required==1) echo " selected";?>>YES</option>
<option value="0"<?php if($repairs_required==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Layers PFRI</td>
<td><input type="text" class="largerbox" name="layers_pfri" value="<?=$layers_pfri?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Repairs Completed</td>
<td>
<input type="text" class="largerbox" name="repairs_completed" value="<?=$repairs_completed?>">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Remed Comp</td>
<td>
<input size="10" type="text" class="largerbox" name="remed_comp_pretty" <?php if($remed_comp_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$remed_comp_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('remed_comp_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
</table>
</td>
</table>
<input type="submit" name="submit1" value="Update Information">
</form>
<?php include "includes/footer.php"; ?>