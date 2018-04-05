<?php include "includes/header.php"; ?>
<?php
$property_id = $_GET['property_id'];

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
  

$roof_size = stripslashes($record['roof_size']);
$roof_type = stripslashes($record['roof_type']);
$deck_type = stripslashes($record['deck_type']);
$installation_type = stripslashes($record['installation_type']);

$property_type = stripslashes($record['property_type']);
$region = stripslashes($record['region']);

$building_use = stripslashes($record['building_use']);
$image = stripslashes($record['image']);
$google_search = $site_name . " " . $address . " " . $city . " " . $state;
$lastaction = $record['datepretty'];
$googlemap = "http://maps.google.com/maps?q=";
$googlemap .= $address . ", " . $city . ", " . $state . ", " . $zip;
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
<a href="property_details.php?property_id=<?=$property_id?>" class="breadcrumb"><?=$site_name?></a> > Edit</div>
<form action="property_details_edit_action.php" method="post">
<input type="hidden" name="property_id" value="<?=$property_id?>">

<input type="text" class="largerbox" name="site_name" value="<?=$site_name?>"><br>
<input type="text" class="largerbox" name="address" value="<?=$address?>"><br>
<input type="text" class="largerbox" name="city" value="<?=$city?>"><br>
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
<br>
<input type="text" class="largerbox" name="zip" value="<?=$zip?>"><br>
Region: <input type="text" class="largerbox" name="region" value="<?=$region?>" size="10"><br>
<br>


Roof Size: <input type="text" class="largerbox" name="roof_size" value="<?=$roof_size?>"><br>
Roof Type: <input type="text" class="largerbox" name="roof_type" value="<?=$roof_type?>"><br>
Deck Type: <input type="text" class="largerbox" name="deck_type" value="<?=$deck_type?>"><br>
Installation Type: <input type="text" class="largerbox" name="installation_type" value="<?=$installation_type?>"><br>
Building Use: <input type="text" class="largerbox" name="building_use" value="<?=$building_use?>"><br>

<input type="submit" name="submit1" value="Submit">
</form>
<?php include "includes/footer.php"; ?>