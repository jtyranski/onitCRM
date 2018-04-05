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

<?php
$user_id = $SESSION_USER_ID;
// in future, check for property type to know which table to pull info


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

<?php include "includes/footer.php"; ?>