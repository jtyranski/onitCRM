<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];

if(!$prospect_id){
  $sql = "SELECT prospect_id from prospects where display=1 order by company_name limit 1";
  $prospect_id = getsingleresult($sql);
}

if(!$property_id){
  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and display=1 order by corporate desc, site_name limit 1";
  $property_id = getsingleresult($sql);
}

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = getsingleresult($sql);


if(in_array($SESSION_USER_ID, $onlyjim)){
  $block = "<tr>
  <td align=\"right\">Beazer Claim ID</td>
  <td>
  <select name=\"beazer_claim_id\">";
  $sql = "SELECT * from properties_prospectingtype_beazer order by beazer_claim_id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $block .= "<option value=\"" . $record['beazer_claim_id'] . "\">" . stripslashes($record['prospecting_desc']) . "</option>";
  }
  $block .= "</select>
  </td>
  </tr>";
}
else {
  $block = "<tr>
  <td align=\"right\">Prospecting ID</td>
  <td>
  <select name=\"prospecting_type\">";
  $sql = "SELECT * from properties_prospectingtype order by prospecting_type";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $block .= "<option value=\"" . $record['prospecting_type'] . "\">" . stripslashes($record['prospecting_desc']) . "</option>";
  }
  $block .= "</select>
  </td>
  </tr>";
}

?>
<script>


function cleardate(f){
  f.value="";
}

function checkform(f){
  var errmsg = "";
  
  if(f.property_id.value==""){ errmsg += "Please select a property.\n";}
  
  if(errmsg != ""){
    alert(errmsg);
	return(false);
  }
  else {
    return(true);
  }
}
</script>
<script src="includes/calendar.js"></script>
<?php include "includes/main_nav.php"; ?>
<form action="prospecting_action.php"  method="post" name="form1" onsubmit="return checkform(this)">
<table class="main">
<tr>
<td align="right">Company</td>
<td>
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<?=$company_name?>
</td>
</tr>

<tr>
<td align="right">Property</td>
<td id="propertyarea">
<select name="property_id">
<?php
$sql = "SELECT * from properties where prospect_id='$prospect_id' and display=1 order by corporate desc, site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['property_id']?>"<?php if($property_id == $record['property_id']) echo " selected"; ?>><?=stripslashes($record['site_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right" valign="top">For</td>
<td>
<select name="user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$SESSION_USER_ID) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>

<tr>
<td align="right">Prospecting Type:</td>
<td>
<select name="prospect_type">
<option value="Dial">Dial</option>
<option value="Visit">Visit</option>
</select>
</td>
</tr>

<tr>
<td align="right">Prospecting Result:</td>
<td>
<select name="prospect_result">
<option value="Connection">Connection</option>
<option value="Prospect">Prospect</option>
<option value="Attempt">Attempt</option>
</select>
</td>
</tr>

<?=$block?>

<tr>
<td align="right">Date</td>
<td>
<input size="10" type="text" name="datepretty" value="<?=date("m/d/Y")?>" onClick="cleardate(this);"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
<br>
<input type="text" name="hourpretty" value="<?=$hourpretty?>" size="2">:
<input type="text" name="minutepretty" value="<?=$minutepretty?>" size="2">
<select name="ampm">
<option value="AM"<?php if($ampm=="AM") echo " selected";?>>AM</option>
<option value="PM"<?php if($ampm=="PM") echo " selected";?>>PM</option>
</select>
</td>
</tr>

<tr>
<td align="right" valign="top">Notes</td>
<td><textarea name="notes" rows="3" cols="20"></textarea></td>
</tr>
<tr>
<td colspan="2"><input type="checkbox" name="no_act" value="1">Do not schedule next activity</td>
</tr>
<tr>
<td colspan="2"><input type="submit" name="submit1" value="Add Prospecting Item"></td>
</tr>
</table>
</form>

<?php include "includes/footer.php"; ?>

