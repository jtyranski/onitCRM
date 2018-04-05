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
<?=$site_name?></div>
<table width="100%" class="main">
<tr>
<td valign="top">
<div class="main_superlarge"><?=$site_name?></div>
<div class="main_large"><?=$address?><br><?=$city?>, <?=$state?> <?=$zip?></div>
</td>
<td valign="top" align="right">
<a href="property_details_edit.php?property_id=<?=$property_id?>">Edit</a><br>
<a href="http://www.google.com/search?q=<?=$google_search?>">Google</a><br>
<a href="<?=$googlemap?>">Map</a><br>
<?php /*<a href="prospecting.php?prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>">Prospecting</a>*/ ?>
</td>
</tr>
</table>

<?php 
$sql = "SELECT * from contacts where property_id='$property_id' order by id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $firstname = stripslashes($record['firstname']);
  $lastname = stripslashes($record['lastname']);
  $position = stripslashes($record['position']);
  $phone = stripslashes($record['phone']);
  $mobile = stripslashes($record['mobile']);
  $fax = stripslashes($record['fax']);
  $email = stripslashes($record['email']);
  
  echo $firstname . " " . $lastname;
  if($position != "") echo " - " . $position;
  echo "<br><br>";
  if($phone != "") echo "P: " . $phone . "<br><br>";
  if($mobile != "") echo "C: " . $mobile . "<br><br>";
  if($fax != "") echo "F: " . $fax . "<br><br>";
  if($email != "") echo "E: <a href='mailto:$email'>" . $email . "</a><br><br>";
}

?>
<br>
<?php
if($roof_size != "") echo "Roof Size: " . number_format($roof_size, 0) . " SF<br>";
if($roof_type != "") echo "Roof Type: " . $roof_type . "<br>";
if($deck_type != "") echo "Deck Type: " . $deck_type . "<br>";
if($installation_type != "") echo "Installation Type: " . $installation_type . "<br>";
if($building_use != "") echo "Building Use: " . $building_use . "<br>";
?>

<?php include "includes/footer.php"; ?>