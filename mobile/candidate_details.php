<?php include "includes/header.php"; ?>
<?php
$id = $_GET['id'];
$sql = "SELECT prospect_id, property_id, notes, date_format(last_action, \"%m/%d/%Y\") as lastaction 
from prospecting_todo where id='$id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_id = $record['prospect_id'];
$property_id = $record['property_id'];
$lastaction = $record['lastaction'];
$notes = stripslashes($record['notes']);
$sql = "SELECT notes from prospecting_todo where complete=1 and property_id='$property_id' order by id desc limit 1";
$latest_notes = stripslashes(getsingleresult($sql));
  
$usenotes = $latest_notes;
if($notes != "") $usenotes = $notes;

$sql = "SELECT *, date_format(lastaction, \"%m/%d/%y\") as datepretty from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_status = stripslashes($record['prospect_status']);
$company_name = stripslashes($record['company_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
  

?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<br><br>
</td>
</tr>
</table>
<a href="candidate_complete.php?id=<?=$id?>" class="large">Complete</a>
&nbsp; &nbsp; &nbsp; &nbsp;
<a href="candidate.php" class="large">Go Back</a>
<table width="100%" class="main">
<tr>
<td valign="top">
<div class="main_superlarge"><?=$company_name?></div>
<div class="main_large"><?=$address?><br><?=$city?>, <?=$state?> <?=$zip?></div>
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
$sql = "SELECT property_id, site_name, city, state from properties where corporate=0 and prospect_id='$prospect_id' and display=1 
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
<br>
<strong>Last Action - Notes</strong>
<br>
<?=$lastaction?> - <?=$usenotes?>
<?php include "includes/footer.php"; ?>