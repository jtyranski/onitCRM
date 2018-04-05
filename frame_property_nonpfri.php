<?php include "includes/functions.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<script src="includes/calendar.js"></script>

<script>
function checkform(f){

  return true;
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
<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>

<div class="main">
<?php
$user_id = $SESSION_USER_ID;
// in future, check for property type to know which table to pull info
$sql = "SELECT *, date_format(sales_status_change_date, \"%m/%d/%Y\") as sales_status_change_date_pretty 
    from properties_nonpfri where property_id='$property_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
	
	$sales_status = stripslashes($record['sales_status']);
    $sales_status_change_date_pretty = stripslashes($record['sales_status_change_date_pretty']);



$sql = "SELECT identifier, ro_status, public_type from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$ro_status = stripslashes($record['ro_status']);
$identifier = stripslashes($record['identifier']);
$public_type = stripslashes($record['public_type']);

$counter=0;
?>
<form action="frame_property_nonpfri_action.php" method="post" onSubmit="return checkform(this)" name="propform">
<input type="hidden" name="property_id" value="<?=$property_id?>">
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