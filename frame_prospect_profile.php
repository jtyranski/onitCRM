<?php
include "includes/header_white.php";

$prospect_id = $_GET['prospect_id'];

$sql = "SELECT *, date_format(insurance_exp, \"%m/%d/%Y\") as insurance_exp_pretty from prospects_resources where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);

$resource_type = stripslashes($record['resource_type']);
$labor_rate = stripslashes($record['labor_rate']);
$labor_rate2 = stripslashes($record['labor_rate2']);
$labor_rate3 = stripslashes($record['labor_rate3']);
$discount = stripslashes($record['discount']);
$con_new = stripslashes($record['con_new']);
$con_reroof = stripslashes($record['con_reroof']);
$con_service = stripslashes($record['con_service']);
$rt_tpo = stripslashes($record['rt_tpo']);
$rt_epdm = stripslashes($record['rt_epdm']);
$rt_bur = stripslashes($record['rt_bur']);
$rt_modified = stripslashes($record['rt_modified']);
$rt_metal = stripslashes($record['rt_metal']);
$l_firestone = stripslashes($record['l_firestone']);
$l_gaf = stripslashes($record['l_gaf']);
$l_carlisle = stripslashes($record['l_carlisle']);
$l_manville = stripslashes($record['l_manville']);
$l_genflex = stripslashes($record['l_genflex']);
$l_duralast = stripslashes($record['l_duralast']);
$l_versico = stripslashes($record['l_versico']);
$insurance = stripslashes($record['insurance']);
$insurance_file = stripslashes($record['insurance_file']);
$insurance_exp_pretty = stripslashes($record['insurance_exp_pretty']);
$notes = stripslashes($record['notes']);
$withholding = stripslashes($record['withholding']);
$os_snow = stripslashes($record['os_snow']);
$os_solar = stripslashes($record['os_solar']);
$os_green = stripslashes($record['os_green']);

$sql = "SELECT priority1, priority2, priority3 from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$priority1 = stripslashes($record['priority1']);
$priority2 = stripslashes($record['priority2']);
$priority3 = stripslashes($record['priority3']);
?>
<script src="includes/calendar.js"></script>
<script>
function DelLicense(id){
  cf = confirm("Are you sure you want to delete this license?");
  if(cf){
    document.location.href="frame_prospect_profile_dellicense.php?prospect_id=<?=$prospect_id?>&id=" + id;
  }
}
</script>
<div class="main">

<form action="frame_prospect_profile_action.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<div style="width:100%; position:relative;">
<div style="width:20%; float:left;">
<strong>Type:</strong>
<select name="resource_type">
<option value="Member"<?php if($resource_type=="Member") echo " selected";?>>Member</option>
<option value="Associate"<?php if($resource_type=="Associate") echo " selected";?>>Associate</option>
<option value="Affiliate"<?php if($resource_type=="Affiliate") echo " selected";?>>Affiliate</option>
<option value="Strategic Partner"<?php if($resource_type=="Strategic Partner") echo " selected";?>>Strategic Partner</option>
<option value="Resource"<?php if($resource_type=="Resource") echo " selected";?>>Resource</option>
</select>
</div>
<div style="width:20%; float:left;">
<strong><?=$priority1?> Labor Rate:</strong>
$<input type="text" name="labor_rate" value="<?=$labor_rate?>" size="5">
</div>
<div style="width:20%; float:left;">
<strong><?=$priority2?> Labor Rate:</strong>
$<input type="text" name="labor_rate2" value="<?=$labor_rate2?>" size="5">
</div>
<div style="width:20%; float:left;">
<strong><?=$priority3?> Labor Rate:</strong>
$<input type="text" name="labor_rate3" value="<?=$labor_rate3?>" size="5">
</div>
<div style="width:20%; float:left;">

<strong>Withholding %:</strong>
<input type="text" name="withholding" value="<?=$withholding?>" size="5">%

</div>
</div>
<div style="clear:both;"></div>
<strong>Construction:</strong> &nbsp;
<input type="checkbox" name="con_new" value="1"<?php if($con_new==1) echo " checked";?>>New Construction &nbsp; &nbsp;
<input type="checkbox" name="con_reroof" value="1"<?php if($con_reroof==1) echo " checked";?>>Re-roof &nbsp; &nbsp;
<input type="checkbox" name="con_service" value="1"<?php if($con_service==1) echo " checked";?>>Service &nbsp; &nbsp;
<br>
<strong>Roof Types:</strong> &nbsp;
<input type="checkbox" name="rt_tpo" value="1"<?php if($rt_tpo==1) echo " checked";?>>TPO &nbsp; &nbsp;
<input type="checkbox" name="rt_epdm" value="1"<?php if($rt_epdm==1) echo " checked";?>>EPDM &nbsp; &nbsp;
<input type="checkbox" name="rt_bur" value="1"<?php if($rt_bur==1) echo " checked";?>>BUR &nbsp; &nbsp;
<input type="checkbox" name="rt_modified" value="1"<?php if($rt_modified==1) echo " checked";?>>Modified &nbsp; &nbsp;
<input type="checkbox" name="rt_metal" value="1"<?php if($rt_metal==1) echo " checked";?>>Metal &nbsp; &nbsp;
<br>
<strong>Other Services:</strong> &nbsp;
<input type="checkbox" name="os_snow" value="1"<?php if($os_snow==1) echo " checked";?>>Snow Removal &nbsp; &nbsp;
<input type="checkbox" name="os_solar" value="1"<?php if($os_solar==1) echo " checked";?>>Solar Panels &nbsp; &nbsp;
<input type="checkbox" name="os_green" value="1"<?php if($os_green==1) echo " checked";?>>Green Roofing &nbsp; &nbsp;

<br>
<strong>Manufacturers:</strong> &nbsp;
<input type="checkbox" name="l_firestone" value="1"<?php if($l_firestone==1) echo " checked";?>>Firestone &nbsp; &nbsp;
<input type="checkbox" name="l_gaf" value="1"<?php if($l_gaf==1) echo " checked";?>>GAF &nbsp; &nbsp;
<input type="checkbox" name="l_carlisle" value="1"<?php if($l_carlisle==1) echo " checked";?>>Carlisle &nbsp; &nbsp;
<input type="checkbox" name="l_manville" value="1"<?php if($l_manville==1) echo " checked";?>>Manville &nbsp; &nbsp;
<input type="checkbox" name="l_genflex" value="1"<?php if($l_genflex==1) echo " checked";?>>Genflex &nbsp; &nbsp;
<input type="checkbox" name="l_duralast" value="1"<?php if($l_duralast==1) echo " checked";?>>Duralast &nbsp; &nbsp;
<input type="checkbox" name="l_versico" value="1"<?php if($l_versico==1) echo " checked";?>>Versico &nbsp; &nbsp;
<br>
<div style="width:80%; position:relative;">
<div style="width:33%; float:left;">
<strong>Insurance:</strong>
<select name="insurance">
<option value="No"<?php if($insurance=="No") echo " selected";?>>No</option>
<option value="Yes"<?php if($insurance=="Yes") echo " selected";?>>Yes</option>
</select>
</div>
<div style="width:33%; float:left;">
<strong>Insurance File:</strong>
<input type="file" name="insurance_file">
<?php if($insurance_file != ""){ ?>
<br><a href="uploaded_files/insurance/<?=$insurance_file?>" target="_blank">File</a> &nbsp;
[<a href="frame_prospect_profile_delinsurance.php?prospect_id=<?=$prospect_id?>" style="color:red; text-decoration:none;">DELETE</a>]
<?php } ?>
</div>
<div style="width:33%; float:left;">
<strong>Insurance Expiration:</strong>
<input size="10" type="text" name="insurance_exp_pretty" value="<?=$insurance_exp_pretty?>"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('insurance_exp_pretty',0)" align="absmiddle">
</div>
</div>
<div style="clear:both;"></div>

<div style="width:100%; position:relative;">

<div style="width:50%; float:left;">
<strong>Notes:</strong><br>
<textarea name="notes" rows="5" cols="60"><?=$notes?></textarea>
</div>

<div style="width:50%; float:left;">
<strong>Licenses:</strong>
<table class="main">
<tr>
<td>State</td>
<td>License Number</td>
<td></td>
</tr>
<tr>
<td>
<select name="state">
<?php
$sql2 = "SELECT * from states order by state_name";
$result2 = executequery($sql2);
while($record2 = go_fetch_array($result2)){
  ?><option value="<?=$record2['state_code']?>"<?php if ($state==$record2['state_code']) echo " selected"; ?>><?=$record2['state_name']?></option>
  <?php
}
?>
</select>
</td>
<td><input type="text" name="license"></td>
<td><input type="submit" name="submit1" value="Add License"></td>
</tr>

<?php
$sql = "SELECT b.state_name, a.id, a.license from prospects_licenses a, states b where a.state=b.state_code and a.prospect_id='$prospect_id' order by b.state_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <tr>
  <td><?=$record['state_name']?></td>
  <td><?=stripslashes($record['license'])?></td>
  <td><a href="javascript:DelLicense('<?=$record['id']?>')">delete</a></td>
  </tr>
  <?php
}
?>
</table>
</div>
</div>
<div style="clear:both;"></div>
<input type="submit" name="submit1" value="Update">
</form>

</div>





		 	 	 				
