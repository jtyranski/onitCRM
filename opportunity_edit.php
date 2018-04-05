<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
$opp_id = $_GET['opp_id'];

$redirect = $_GET['redirect'];
$redirect = go_reg_replace("\*", "&", $redirect);

if($opp_id != "new"){
  $sql = "SELECT *, date_format(lastaction, \"%m/%d/%Y\") as lastaction_pretty, date_format(sold_date, \"%m/%d/%Y\") as sold_date_pretty
   from opportunities where opp_id='$opp_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $product = stripslashes($record['product']);
  $amount = stripslashes($record['amount']);
  $property_id = $record['property_id'];
  $prospect_id = $record['prospect_id'];
  $description = stripslashes($record['description']);
  $probability = stripslashes($record['probability']);
  $lastaction_pretty = stripslashes($record['lastaction_pretty']);
  $sold_date_pretty = stripslashes($record['sold_date_pretty']);
  $user_id = $record['user_id'];
  $projected_replacement = $record['projected_replacement'];
  $srmanager = $record['srmanager'];
  $status = stripslashes($record['status']);
  $loi_flag = stripslashes($record['loi_flag']);
  if($status=="Sold" && $loi_flag) $status = "Sold-LOI";
  $topfiller = "Edit Opportunity";
  $scheduled_by = $record['scheduled_by'];
  $opp_stage_id= $record['opp_stage_id'];
  
  $prodstat_id = $record['prodstat_id'];
  $project_manager = $record['project_manager'];
  $crew = $record['crew'];
  $bid_id = $record['bid_id'];
  $leak_id = $record['leak_id'];
  
  $hourly_rate = $record['hourly_rate'];
  $force_production_queue = $record['force_production_queue'];
  
  $opp_product_id = $record['opp_product_id'];
  
  $sql = "SELECT project_id from opm where master_id='" . $SESSION_MASTER_ID . "' and opp_id='$opp_id'";
  $project_id = stripslashes(getsingleresult($sql));
  
  
}
else {
  $topfiller = "Add New Opportunity";
  $user_id = $SESSION_USER_ID;
  $scheduled_by = $SESSION_USER_ID;
}
if($_GET['property_id']) $property_id = $_GET['property_id'];


$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));

$sql = "SELECT site_name from properties where property_id='$property_id'";
$site_name = stripslashes(getsingleresult($sql));

?>

<script src="includes/calendar.js"></script>
<script>
function checkform(f){
  errmsg = "";
  if(f.product.value==""){ errmsg += "Please enter product. \n"; }
  if(f.amount.value=="" || isNaN(f.amount.value)){ errmsg += "Please enter amount. \n"; }
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}

function check_opm(){
<?php if($SESSION_USE_OPS){ ?>
  x = document.getElementById("status").value;
  y = document.getElementById("opp_product_id").value;
  
  if(x=="Sold" && y==-1){
    document.getElementById("project_id_area").style.display="";
  }
<?php } ?>
}


</script>
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
  <strong><?=$company_name?> - <?=$site_name?></strong>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="opportunity_edit_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="opp_id" value="<?=$opp_id?>">
<input type="hidden" name="redirect" value="<?=$redirect?>">
<table class="main">
<tr>
<td align="right">For</td>
<td>
<select name="user_id">
<?php
if($opp_id != "new"){ ?>
<option value=""></option>
<?php } ?>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Status</td>
<td id="status_area">
<select name="status" id="status" onchange="check_opm()">
<option value="Quoted"<?php if($status=="Quoted") echo " selected";?>>Quoted</option>
<option value="Sold"<?php if($status=="Sold") echo " selected";?>>Sold</option>
<option value="Dead"<?php if($status=="Dead") echo " selected";?>>Dead</option>
</select>
</td>
</tr>

<tr>
<td align="right">Product</td>
<td>
<select name="opp_product_id" id="opp_product_id" onchange="check_opm()">
<?php
$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
$test = getsingleresult($sql);
$sql = "SELECT quickbid from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$test_qb = getsingleresult($sql);

$sql = "SELECT * from opportunities_master";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($test==0 && $record['opp_product_id']==-3) continue;
  if($test_qb==0 && $record['opp_product_id']==-4) continue;
  ?>
  <option value="<?=$record['opp_product_id']?>"<?php if($record['opp_product_id']==$opp_product_id) echo " selected";?>><?=stripslashes($record['opp_name'])?></option>
  <?php
}
?>
<?php

$sql = "SELECT * from opportunities_items where master_id='" . $SESSION_MASTER_ID . "' order by opp_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['opp_product_id']?>"<?php if($record['opp_product_id']==$opp_product_id) echo " selected";?>><?=stripslashes($record['opp_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr id="project_id_area" style="display:none;">
<td align="right">Project ID</td>
<td><input type="text" name="project_id" value="<?=$project_id?>" size="20">
</td>
</tr>

</table>


<table class="main">
<tr>
<td align="right">Amount $</td>
<td><input type="text" name="amount" value="<?=$amount?>" size="40" id="amount"></td>
</tr>
<tr>
<td align="right">Date</td>
<td>
<input size="10" type="text" name="lastaction_pretty" <?php if($lastaction_pretty=="") { echo " value=\"" . date("m/d/Y") . "\""; } else {?> value="<?=$lastaction_pretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('lastaction_pretty',0)" align="absmiddle">
</td>
</tr>
<tr>
<td align="right" valign="top">Description</td>
<td><textarea rows="4" cols="60" name="description"><?=$description?></textarea>
</td>
</tr>
</table>


<table class="main">
<tr>
<td colspan="2">&nbsp;
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" name="submit1" value="<?=$topfiller?>">
</td>
</tr>
</table>
</form>

</div>
</div>
</div>

<script>
check_opm();
</script>
<?php include "includes/footer.php"; ?>
