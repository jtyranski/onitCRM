<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];

$sql = "SELECT *, date_format(lastaction, \"%m/%d/%y\") as datepretty from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_status = stripslashes($record['prospect_status']);
$company_name = stripslashes($record['company_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
  

$lastaction = $record['datepretty'];

$logo = $record['logo'];
$manager = $record['user_id'];
$google_search = $company_name . " " . $address . " " . $city . " " . $state;
$superpages = "http://yellowpages.superpages.com/listings.jsp?";
$superpages .= "STYPE=S&search=Find+It&SRC=&channelId=&sessionId=&MCBP=true&CS=L&C=" . $company_name;
$superpages .= "&L=" . $city . " " . $state . "&searchbutton.x=48&searchbutton.y=22&searchbutton=Find+It";
$googlemap = "http://maps.google.com/maps?q=";
$googlemap .= $address . ", " . $city . ", " . $state . ", " . $zip;
?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">

</td>
</tr>
</table>
<div class="breadcrumb">
<a href="company.php" class="breadcrumb">Company</a> > <?=$company_name?></div>
<table width="100%" class="main">
<tr>
<td valign="top">
<div class="main_superlarge"><?=$company_name?></div>
<div class="main_large"><?=$address?><br><?=$city?>, <?=$state?> <?=$zip?></div>
</td>
<td valign="top" align="right">
<a href="company_details_edit.php?prospect_id=<?=$prospect_id?>">Edit</a><br>
<a href="http://www.google.com/search?q=<?=$google_search?>">Google</a><br>
<a href="<?=$googlemap?>">Map</a><br>
<?php /*<a href="prospecting.php?prospect_id=<?=$prospect_id?>">Prospecting</a>*/ ?>
</td>
</tr>
</table>

<?php 
$sql = "SELECT * from contacts where prospect_id='$prospect_id' order by id";
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
<table class="main" width="100%">
<tr>
<td><strong>Property</strong></td>
<td><strong>Location</strong></td>
</tr>
<?php
$irep_clause = " 1=1 ";
if($SESSION_IREP==1) $irep_clause = " irep = '" . $SESSION_USER_ID . "' ";

$groups_clause = " 1=1 ";
if($SESSION_USE_GROUPS==1){
  $groups_array = explode(",", $SESSION_GROUPS);
  $groups_search = "";
  for($x=0;$x<sizeof($groups_array);$x++){
    if($groups_array[$x]=="") continue;
	$groups_search .= $groups_array[$x] . ",";
  }
  $groups_search = go_reg_replace("\,$", "", $groups_search);
  if($groups_search != "") $groups_clause = " XXX.groups in ($groups_search) ";
  if($groups_clause == "") $groups_clause = " 1=1 ";
  
  if($SESSION_SUBGROUPS != ''){

    $subgroups_array = explode(",", $SESSION_SUBGROUPS);
    $subgroups_search = "";
    for($x=0;$x<sizeof($subgroups_array);$x++){
      if($subgroups_array[$x]=="") continue;
	  $subgroups_search .= $subgroups_array[$x] . ",";
    }
    $subgroups_search = go_reg_replace("\,$", "", $subgroups_search);
    if($subgroups_search != "") $groups_clause .= " and XXX.subgroups in ($subgroups_search)";
  }
}
$property_groups_clause = go_reg_replace("XXX", "properties", $groups_clause);
$prospect_groups_clause = go_reg_replace("XXX", "prospects", $groups_clause);

$sql = "SELECT property_id, site_name, city, state from properties where corporate=0 and prospect_id='$prospect_id' and display=1 
and $irep_clause 
and $property_groups_clause 
order by site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $site_name = stripslashes($record['site_name']);
  if($site_name=="") $site_name = "(None)";
  if(strlen($site_name) > 17) $site_name = substr($site_name, 0, 17) . "..";
  $city = stripslashes($record['city']);
  if(strlen($city) > 12) $city = substr($city, 0, 12) . "..";
  ?>
  <tr>
  <td><a href="property_details.php?property_id=<?=$record['property_id']?>"><?=$site_name?></a></td>
  <td><?=$city?>, <?=$record['state']?></td>
  </tr>
  <?php
}
?>
</table>

<?php include "includes/footer.php"; ?>