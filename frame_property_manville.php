<?php include "includes/functions.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">


<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
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

<div class="main">
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

/*
if($_SESSION['property']['comp_name'] != "") $comp_name = $_SESSION['property']['comp_name'];
if($_SESSION['property']['comp_address'] != "") $comp_address = $_SESSION['property']['comp_address'];
if($_SESSION['property']['comp_city'] != "") $comp_city = $_SESSION['property']['comp_city'];
if($_SESSION['property']['comp_state'] != "") $comp_state = $_SESSION['property']['comp_state'];
if($_SESSION['property']['comp_zip'] != "") $comp_zip = $_SESSION['property']['comp_zip'];
if($_SESSION['property']['comp_company'] != "") $comp_company = $_SESSION['property']['comp_company'];
if($_SESSION['property']['comp_amount'] != "") $comp_amount = $_SESSION['property']['comp_amount'];
if($_SESSION['property']['comp_number'] != "") $comp_number = $_SESSION['property']['comp_number'];
if($_SESSION['property']['key_number'] != "") $key_number = $_SESSION['property']['key_number'];
if($_SESSION['property']['claim_status'] != "") $claim_status = $_SESSION['property']['claim_status'];
if($_SESSION['property']['pfri_shipped'] != "") $pfri_shipped = $_SESSION['property']['pfri_shipped'];
if($_SESSION['property']['corrosion_level'] != "") $corrosion_level = $_SESSION['property']['corrosion_level'];
if($_SESSION['property']['completion_date_pretty'] != "") $completion_date_pretty = $_SESSION['property']['completion_date_pretty'];
if($_SESSION['property']['jm_guarantee'] != "") $jm_guarantee = $_SESSION['property']['jm_guarantee'];
if($_SESSION['property']['pfri_shipped_date_pretty'] != "") $pfri_shipped_date_pretty = $_SESSION['property']['pfri_shipped_date_pretty'];
if($_SESSION['property']['eligible'] != "") $eligible = $_SESSION['property']['eligible'];
if($_SESSION['property']['eligibility_comments1'] != "") $eligibility_comments1 = $_SESSION['property']['eligibility_comments1'];
if($_SESSION['property']['remediated'] != "") $remediated = $_SESSION['property']['remediated'];
if($_SESSION['property']['inspected'] != "") $inspected = $_SESSION['property']['inspected'];
if($_SESSION['property']['inspection_status'] != "") $inspection_status = $_SESSION['property']['inspection_status'];
if($_SESSION['property']['repairs_required'] != "") $repairs_required = $_SESSION['property']['repairs_required'];
if($_SESSION['property']['repairs_completed'] != "") $repairs_completed = $_SESSION['property']['repairs_completed'];
if($_SESSION['property']['layers_pfri'] != "") $layers_pfri = $_SESSION['property']['layers_pfri'];
if($_SESSION['property']['eligibility_comments2'] != "") $eligibility_comments2 = $_SESSION['property']['eligibility_comments2'];
if($_SESSION['property']['inspected_date_pretty'] != "") $inspected_date_pretty = $_SESSION['property']['inspected_date_pretty'];
if($_SESSION['property']['test_cuts'] != "") $test_cuts = $_SESSION['property']['test_cuts'];
if($_SESSION['property']['remed_comp_pretty'] != "") $remed_comp_pretty = $_SESSION['property']['remed_comp_pretty'];
if($_SESSION['property']['inspection_number'] != "") $inspection_number = $_SESSION['property']['inspection_number'];
*/

$sql = "SELECT identifier, ro_status, public_type from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ro_status = stripslashes($record['ro_status']);
$identifier = stripslashes($record['identifier']);
$public_type = stripslashes($record['public_type']);
$counter=0;
?>


<form action="frame_property_manville_action.php" method="post" onSubmit="return checkform(this)" name="manvilleform">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="old_sales_status" value="<?=$ra_stage?>">
<table class="main">
<tr>
<td valign="top">
<table class="main" id="manville">
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Sales Status</strong></td>
<td>
<select name="ra_stage" onChange="ra_date_change()">
<?php
$sql = "SELECT * from sales_stage order by sales_stage";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['sales_stage_id']?>"<?php if($record['sales_stage_id']==$ra_stage) echo " selected";?>><?=stripslashes($record['sales_stage'])?></option>
  <?php
}
?>
<?php /*
<?php foreach($sales_stage as $key => $value){ ?>
<option value="<?=$key?>"<?php if($ra_stage==$key) echo " selected"; ?>><?=$value?></option>
<?php } ?>
*/ ?>
<?php /*
<option value=""<?php if($ra_stage=="") echo " selected"; ?>></option>
<option value="1"<?php if($ra_stage==1) echo " selected"; ?>>1. Candidate</option>
<option value="2"<?php if($ra_stage==2) echo " selected"; ?>>2. CC Scheduled</option>
<option value="3"<?php if($ra_stage==3) echo " selected"; ?>>3. CC Complete</option>
<option value="4"<?php if($ra_stage==4) echo " selected"; ?>>4. Inspection Quoted</option>
<option value="5"<?php if($ra_stage==5) echo " selected"; ?>>5. Inspection Sold</option>
<option value="9"<?php if($ra_stage==9) echo " selected"; ?>>9. Ineligible</option>
<option value="9.9"<?php if($ra_stage=="9.9") echo " selected"; ?>>9.9. Replaced</option>
*/ ?>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>RA Change Date</strong></td>
<td>
<input size="10" type="text" name="ra_change_date_pretty" <?php if($ra_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$ra_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('ra_change_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Key Number</strong></td>
<td><input type="text" name="key_number" value="<?=$key_number?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>RO Status</strong></td>
<td>
<select name="ro_status">
<option value=""<?php if($ro_status=="") echo " selected";?>></option>
<option value="Open"<?php if($ro_status=="Open") echo " selected";?>>Open</option>
<option value="Closed"<?php if($ro_status=="Closed") echo " selected";?>>Closed</option>
<option value="Closed - RO"<?php if($ro_status=="Closed - RO") echo " selected";?>>Closed - RO</option>
<option value="Closed - DT"<?php if($ro_status=="Closed - DT") echo " selected";?>>Closed - DT</option>
</select>
&nbsp;
<input type="button" name="dupecheck" onClick="window.open('dupe_check.php?property_id=<?=$property_id?>', '', 'height=400,width=1000,scrollbars=yes')" value="Dupe Check">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Identifer</strong></td>
<td><input type="text" name="identifier" value="<?=$identifier?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Public Type</strong></td>
<td>
<select name="public_type">
<option value=""<?php if($public_type=="") echo " selected";?>></option>
<option value="Military"<?php if($public_type=="Military") echo " selected";?>>Military</option>
<option value="Municipality"<?php if($public_type=="Municipality") echo " selected";?>>Municipality</option>
<option value="Postal Service"<?php if($public_type=="Postal Service") echo " selected";?>>Postal Service</option>
<option value="School"<?php if($public_type=="School") echo " selected";?>>School</option>
<option value="University"<?php if($public_type=="University") echo " selected";?>>University</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Claim Status</strong></td>
<td>
<select name="claim_status">
<option value="OPEN"<?php if($claim_status=="OPEN") echo " selected";?>>OPEN</option>
<option value="CLOSED"<?php if($claim_status=="CLOSED") echo " selected";?>>CLOSED</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Eligible</strong></td>
<td>
<select name="eligible">
<option value="1"<?php if($eligible==1) echo " selected";?>>YES</option>
<option value="0"<?php if($eligible==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Eligibility Comments 1</strong></td>
<td><textarea name="eligibility_comments1" rows="4" cols="40"><?=$eligibility_comments1?></textarea></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Eligibility Comments 2</strong></td>
<td><textarea name="eligibility_comments2" rows="4" cols="40"><?=$eligibility_comments2?></textarea></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Remediated</strong></td>
<td>
<select name="remediated">
<option value="1"<?php if($remediated==1) echo " selected";?>>YES</option>
<option value="0"<?php if($remediated==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>PFRI Shipped</strong></td>
<td><input type="text" name="pfri_shipped" value="<?=$pfri_shipped?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>PFRI Shipped Date</strong></td>
<td>
<input size="10" type="text" name="pfri_shipped_date_pretty" <?php if($pfri_shipped_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$pfri_shipped_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('pfri_shipped_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Completion Date</strong></td>
<td>
<input size="10" type="text" name="completion_date_pretty" <?php if($completion_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$completion_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('completion_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>JM Guarantee</strong></td>
<td>
<select name="jm_guarantee">
<option value="1"<?php if($jm_guarantee==1) echo " selected";?>>YES</option>
<option value="0"<?php if($jm_guarantee==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Comp Number</strong></td>
<td><input type="text" name="comp_number" value="<?=$comp_number?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Comp Amount</strong>$</td>
<td><input type="text" name="comp_amount" value="<?=$comp_amount?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Corrosion Level</strong></td>
<td><input type="text" name="corrosion_level" value="<?=$corrosion_level?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Comp Name</strong></td>
<td><input type="text" name="comp_name" value="<?=$comp_name?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Comp Address</strong></td>
<td><input type="text" name="comp_address" value="<?=$comp_address?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Comp City</strong></td>
<td><input type="text" name="comp_city" value="<?=$comp_city?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Comp State</strong></td>
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
<td align="right" valign="top"><strong>Comp Zip</strong></td>
<td><input type="text" name="comp_zip" value="<?=$comp_zip?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Inspected</strong></td>
<td>
<select name="inspected">
<option value="1"<?php if($inspected==1) echo " selected";?>>YES</option>
<option value="0"<?php if($inspected==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Inspection Status</strong></td>
<td><input type="text" name="inspection_status" value="<?=$inspection_status?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Inspected Date</strong></td>
<td>
<input size="10" type="text" name="inspected_date_pretty" <?php if($inspected_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$inspected_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('inspected_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Test Cuts</strong></td>
<td><input type="text" name="test_cuts" value="<?=$test_cuts?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Repairs Required</strong></td>
<td>
<select name="repairs_required">
<option value="1"<?php if($repairs_required==1) echo " selected";?>>YES</option>
<option value="0"<?php if($repairs_required==0) echo " selected";?>>NO</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Layers PFRI</strong></td>
<td><input type="text" name="layers_pfri" value="<?=$layers_pfri?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Repairs Completed</strong></td>
<td>
<input type="text" name="repairs_completed" value="<?=$repairs_completed?>">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Remed Comp</strong></td>
<td>
<input size="10" type="text" name="remed_comp_pretty" <?php if($remed_comp_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$remed_comp_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('remed_comp_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
</table>
</td>
<td valign="top">
<input type="submit" name="submit1" value="Update Information">
</td>
</tr>
</table>
</form>
</div>
</body>
</html>