<?php include "includes/functions.php"; ?>
<?php
include_once("agent.php");
$agent->init();
?>
<?php $property_id = $_GET['property_id']; ?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">


<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
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

<div class="main">
<?php
$user_id = $SESSION_USER_ID;
// in future, check for property type to know which table to pull info
$sql = "SELECT *, 
    date_format(status_change_date, \"%m/%d/%Y\") as status_change_date_pretty, 
    date_format(ca_change_date, \"%m/%d/%Y\") as ca_change_date_pretty, 
	date_format(sales_status_change_date, \"%m/%d/%Y\") as sales_status_change_date_pretty, 
    date_format(install_date, \"%m/%d/%Y\") as install_date_pretty,
	date_format(beazer_audit_date, \"%m/%d/%Y\") as beazer_audit_date_pretty
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
	$beazer_audit_date_pretty = stripslashes($record['beazer_audit_date_pretty']);
	$phencon_client = stripslashes($record['phencon_client']);
	$audit_identifier = stripslashes($record['audit_identifier']);
	$beazer_stage = stripslashes($record['beazer_stage']);
	$replacement = stripslashes($record['replacement']);
	$probability = stripslashes($record['probability']);
	$opportunity = stripslashes($record['opportunity']);
	$beazer_type = stripslashes($record['beazer_type']);
	$opp_rating = stripslashes($record['opp_rating']);
	$roof_settlement = stripslashes($record['roof_settlement']);
	$ip_settlement = stripslashes($record['ip_settlement']);
	$paint = stripslashes($record['paint']);
	$overlay = stripslashes($record['overlay']);
	$remove_replace = stripslashes($record['remove_replace']);



if($status_change_date_pretty == "00/00/0000") $status_change_date_pretty = "";
if($ca_change_date_pretty == "00/00/0000") $ca_change_date_pretty = "";
if($install_date_pretty == "00/00/0000") $install_date_pretty = "";
if($beazer_audit_date_pretty == "00/00/0000") $beazer_audit_date_pretty = "";

$sql = "SELECT identifier, ro_status, public_type from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ro_status = stripslashes($record['ro_status']);
$identifier = stripslashes($record['identifier']);
$public_type = stripslashes($record['public_type']);
$counter=0;
?>


<form action="frame_property_beazer_action.php" method="post" onSubmit="return checkform(this)" name="propform">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="old_beazer_id_status" value="<?=$beazer_id_status?>">
<input type="hidden" name="old_sales_status" value="<?=$sales_status?>">
<table class="main">
<tr>
<td valign="top">
<table class="main" id="beazer">

<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Sales Status</strong></td>
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
<?php /*
<?php foreach($sales_stage as $key => $value){ ?>
<option value="<?=$key?>"<?php if($sales_status==$key) echo " selected"; ?>><?=$value?></option>
<?php } ?>
*/ ?>
<?php /*
<option value=""<?php if($sales_status=="") echo " selected"; ?>></option>
<option value="1"<?php if($sales_status==1) echo " selected"; ?>>1. Candidate</option>
<option value="2"<?php if($sales_status==2) echo " selected"; ?>>2. CC Scheduled</option>
<option value="3"<?php if($sales_status==3) echo " selected"; ?>>3. CC Complete</option>
<option value="4"<?php if($sales_status==4) echo " selected"; ?>>4. Inspection Quoted</option>
<option value="5"<?php if($sales_status==5) echo " selected"; ?>>5. Inspection Sold</option>
<option value="9"<?php if($sales_status==9) echo " selected"; ?>>9. Ineligible</option>
<option value="9.9"<?php if($sales_status=="9.9") echo " selected"; ?>>9.9. Replaced</option>
*/ ?>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Sales Status Change Date</strong></td>
<td>
<input size="10" type="text" name="sales_status_change_date_pretty" <?php if($sales_status_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$sales_status_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('sales_status_change_date_pretty',0)" align="absmiddle">
</td>
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
<td align="right" valign="top"><strong>Adjuster</strong></td>
<td><input type="text" name="adjuster" value="<?=$adjuster?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Beazer File</strong></td>
<td><input type="text" name="beazer_file" value="<?=$beazer_file?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>ID Status</strong></td>
<td>
<?php /*
<input name="id_status" id="id_status" size="20" type="text" 
onkeyup="GetStatus();return false;" autocomplete="off" value="<?=$id_status?>" maxlength="200">
<br>
<select id="matches" style="VISIBILITY: hidden" 
onclick="MatchSelected(this);" ></select>
*/
?>

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
<td align="right" valign="top"><strong>Status Change Date</strong></td>
<td>
<input size="10" type="text" name="status_change_date_pretty" <?php if($status_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$status_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('status_change_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Claim Analysis Stage</strong></td>
<td><input type="text" name="claim_analysis_stage" value="<?=$claim_analysis_stage?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>CA Change Date</strong></td>
<td>
<input size="10" type="text" name="ca_change_date_pretty" <?php if($ca_change_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$ca_change_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('ca_change_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Install Date</strong></td>
<td>
<input size="10" type="text" name="install_date_pretty" <?php if($install_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$install_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('install_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Settlement Number</strong>$</td>
<td><input type="text" name="settlement_number" value="<?=$settlement_number?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Record Creator</strong></td>
<td><input type="text" name="record_creator" value="<?=$record_creator?>"></td>
</tr>
<?php $counter++; ?>
</table>
</td>
<td valign="top">


<table class="main">
<?php $counter = 0; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>PhenCon Client</strong></td>
<td><input type="text" name="phencon_client" value="<?=$phencon_client?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Beazer Audit Date</strong></td>
<td>
<input size="10" type="text" name="beazer_audit_date_pretty" <?php if($beazer_audit_date_pretty=="") { echo" value=\"MM/DD/YYYY\" onClick=\"cleardate(this);\""; } else {?> value="<?=$beazer_audit_date_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('beazer_audit_date_pretty',0)" align="absmiddle">
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Audit Identifier</strong></td>
<td><input type="text" name="audit_identifier" value="<?=$audit_identifier?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Beazer Stage</strong></td>
<td><input type="text" name="beazer_stage" value="<?=$beazer_stage?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Beazer Status</strong></td>
<td style='font-size:12px;'>
<?php
$sql = "SELECT beazer_status from properties_beazer where property_id='$property_id'";
//$sql = "SELECT * from beazer_status where id='$beazer_status'";
$result = executequery($sql);
$record = go_fetch_array($result);
echo $record['beazer_status'];
//echo $record['status'];
?>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Replacement</strong></td>
<td><input type="text" name="replacement" value="<?=$replacement?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Probability</strong></td>
<td>%<input type="text" name="probability" value="<?=$probability?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Opportunity</strong></td>
<td><input type="text" name="opportunity" value="<?=$opportunity?>"></td>
</tr>
<?php $counter++; ?>

<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Opportunity Rating</strong></td>
<td>
<select name="opp_rating">
<option value=""></option>
<option value="1"<?php if($opp_rating=="1") echo " selected";?>>1</option>
<option value="2"<?php if($opp_rating=="2") echo " selected";?>>2</option>
<option value="3"<?php if($opp_rating=="3") echo " selected";?>>3</option>
</select>
</td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Roof Settlement</strong></td>
<td><input type="text" name="roof_settlement" value="<?=$roof_settlement?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>IP Settlement</strong></td>
<td><input type="text" name="ip_settlement" value="<?=$ip_settlement?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Paint</strong></td>
<td><input type="text" name="paint" value="<?=$paint?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Overlay</strong></td>
<td><input type="text" name="overlay" value="<?=$overlay?>"></td>
</tr>
<?php $counter++; ?>
<tr<?php if($counter %2) echo " bgcolor=\"$ALT_ROW_COLOR\""; ?>>
<td align="right" valign="top"><strong>Remove and Replace</strong></td>
<td><input type="text" name="remove_replace" value="<?=$remove_replace?>"></td>
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
<script>

var matchList = document.getElementById("matches");
 
function GetStatus() {
var id_status = document.getElementById('id_status').value;
agent.call('','GetStatus','GetContacts_Callback',id_status);
}

function GetContacts_Callback(obj) {
matchList.style.visibility = "visible";
matchList.options.length = 0; //reset the states dropdown
matchList.size = obj.length;
 
for (var i = 0; i < obj.length; i++)
{
matchList.options[matchList.options.length] =new Option(obj[i]);
}
}

function MatchSelected(matches) {
var id_status = document.getElementById("id_status");
id_status.value = matches.options[matches.selectedIndex].text;
//GetAlbumByArtist(id_status.value);
}

</script>

</body>
</html>
