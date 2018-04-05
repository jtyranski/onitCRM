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
  
  if(f.settlement_number.value != ""){
    if(isNaN(f.settlement_number.value)) { errmsg += "Please enter only numbers for settlement number. \n"; }
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

function status_date_change(){
  document.propform.status_change_date_pretty.value="<?=date("m/d/Y")?>";
}

function sales_status_date_change(){
  document.propform.sales_status_change_date_pretty.value="<?=date("m/d/Y")?>";
}

</script>

<?php
$user_id = $SESSION_USER_ID;
// in future, check for property type to know which table to pull info
$sql = "SELECT *, 
    date_format(status_change_date, \"%m/%d/%Y\") as status_change_date_pretty, 
    date_format(ca_change_date, \"%m/%d/%Y\") as ca_change_date_pretty, 
	date_format(sales_status_change_date, \"%m/%d/%Y\") as sales_status_change_date_pretty, 
    date_format(install_date, \"%m/%d/%Y\") as install_date_pretty 
    from properties_beazer where property_id='$property_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
	
	$adjuster = stripslashes($record['adjuster']);
	$beazer_file = stripslashes($record['beazer_file']);
	$id_status = stripslashes($record['id_status']);
	$beazer_id_status = stripslashes($record['beazer_id_status']);
	$status_change_date_pretty = stripslashes($record['status_change_date_pretty']);
	$claim_analysis_stage = stripslashes($record['claim_analysis_stage']);
	$ca_change_date_pretty = stripslashes($record['ca_change_date_pretty']);
	$install_date_pretty = stripslashes($record['install_date_pretty']);
	$sales_status_change_date_pretty = stripslashes($record['sales_status_change_date_pretty']);
	$settlement_number = stripslashes($record['settlement_number']);
	$record_creator = stripslashes($record['record_creator']);
	$sales_status = stripslashes($record['sales_status']);
    $beazer_status = $record['beazer_status'];



if($status_change_date_pretty == "00/00/0000") $status_change_date_pretty = "";
if($ca_change_date_pretty == "00/00/0000") $ca_change_date_pretty = "";
if($install_date_pretty == "00/00/0000") $install_date_pretty = "";

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
<form action="property_info_beazer_action.php" method="post" onSubmit="return checkform(this)" name="propform">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="old_beazer_id_status" value="<?=$beazer_id_status?>">
<table class="main">
<tr>
<td valign="top">
<table class="main" id="beazer">
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Sales Status</td>
<td>
<select name="sales_status" onChange="sales_status_date_change()">
<?php
$sql = "SELECT * from sales_stage order by sales_stage";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['sales_stage_id']?>"<?php if($record['sales_stage_id']==$sales_status) echo " selected";?>><?=stripslashes($record['sales_stage'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Sales Status Change Date</td>
<td>
<input size="10" type="text" class="largerbox" name="sales_status_change_date_pretty" <?php if($sales_status_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$sales_status_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('sales_status_change_date_pretty',0)" align="absmiddle">
</td>
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
<td align="right" valign="top">Adjuster</td>
<td><input type="text" class="largerbox" name="adjuster" value="<?=$adjuster?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Beazer File</td>
<td><input type="text" class="largerbox" name="beazer_file" value="<?=$beazer_file?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">ID Status</td>
<td>


<select name="beazer_id_status" onChange="status_date_change()">
<?php
$sql = "SELECT * from beazer_id_status order by status";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['id']?>"<?php if($beazer_id_status == $record['id']) echo " selected";?>><?=$record['status']?></option>
  <?php
}
?>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Status Change Date</td>
<td>
<input size="10" type="text" class="largerbox" name="status_change_date_pretty" <?php if($status_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$status_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('status_change_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Claim Analysis Stage</td>
<td><input type="text" class="largerbox" name="claim_analysis_stage" value="<?=$claim_analysis_stage?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">CA Change Date</td>
<td>
<input size="10" type="text" class="largerbox" name="ca_change_date_pretty" <?php if($ca_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$ca_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('ca_change_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Install Date</td>
<td>
<input size="10" type="text" class="largerbox" name="install_date_pretty" <?php if($install_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$install_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('install_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Settlement Number$</td>
<td><input type="text" class="largerbox" name="settlement_number" value="<?=$settlement_number?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top">Record Creator</td>
<td><input type="text" class="largerbox" name="record_creator" value="<?=$record_creator?>"></td>
</tr>
<?php $counter++; ?>
</table>
</td>
</table>
<input type="submit" name="submit1" value="Update Information">
</form>

<?php include "includes/footer.php"; ?>