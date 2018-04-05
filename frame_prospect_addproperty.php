<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
if($property_id=="") $property_id = "new";

  $topfiller = "Add New Property";


$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));

$sql = "SELECT address, city, state, zip from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);

$address = go_reg_replace("\"", "DOUBLEQUOTE", $address);
$city = go_reg_replace("\"", "DOUBLEQUOTE", $city);
$address = go_reg_replace("\'", "SINGLEQUOTE", $address);
$city = go_reg_replace("\'", "SINGLEQUOTE", $city);






?>
<script src="includes/calendar.js"></script>
<script>
function checkform(f){
  errmsg = "";
  if(f.site_name.value==""){ errmsg += "Please enter site name. \n"; }
  /*
  if(f.address.value==""){ errmsg += "Please enter address. \n"; }
  if(f.city.value==""){ errmsg += "Please enter city. \n"; }
  if(f.state.value==""){ errmsg += "Please enter state. \n"; }
  if(f.zip.value==""){ errmsg += "Please enter zip. \n"; }
  
  if(f.contact.value==""){ errmsg += "Please enter primary contact. \n"; }
  if(f.phone.value==""){ errmsg += "Please enter primary contact phone. \n"; }
  if(f.email.value==""){ errmsg += "Please enter primary contact email. \n"; }
  var atsign = /\@/.test(f.email.value);
  var dot = /\./.test(f.email.value);
  if(atsign==false || dot==false) { errmsg += "Please enter a valid email address. \n"; }
  */

  
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

function sameas(x){
  
  if(x.checked==true){
    var xaddress = "<?=$address?>";
	xaddress = xaddress.replace(/DOUBLEQUOTE/g, "\"");
	xaddress = xaddress.replace(/SINGLEQUOTE/g, "\'");
	var xcity = "<?=$city?>";
	xcity = xcity.replace(/DOUBLEQUOTE/g, "\"");
	xcity = xcity.replace(/SINGLEQUOTE/g, "\'");
    document.getElementById('address').value=xaddress;
	document.getElementById('city').value=xcity;
	document.getElementById('state').value="<?=$state?>";
	document.getElementById('zip').value="<?=$zip?>";
  }
  
}
	

function OpenClose(group, x){
  if(x==1){
    document.getElementById('group_' + group).style.display="";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById('group_' + group).style.display="none";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
  }
}

function group_check(){
  document.getElementById('submit1').disabled=false;
  var form='prop';
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  var c=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name=="group_list[]" && dml.elements[i].checked==true) {
      c = c+1;
    }
  }
  if(c > 1){
    document.getElementById('submit1').disabled=true;
	alert("Each property can only belong to one group.");
  }
}

function subgroup_check(){
  document.getElementById('submit1').disabled=false;
  var form='prop';
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  var c=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name=="subgroup_list[]" && dml.elements[i].checked==true) {
      c = c+1;
    }
  }
  if(c > 1){
    document.getElementById('submit1').disabled=true;
	alert("Each property can only belong to one subgroup.");
  }
}
</script>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="frame_prospect_addproperty_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="prop">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<div style="position:relative;" class="main">
<div style="float:left;">
<table class="main">
<tr>
<td align="right">Site Name</td>
<td><input type="text" name="site_name" size="40"></td>
</tr>
<tr>
<td align="right">Address</td>
<td><input type="text" name="address" size="40" id="address"></td>
</tr>
<tr>
<td align="right">City</td>
<td><input type="text" name="city" size="40" id="city"></td>
</tr>
<tr>
<td align="right">State</td>
<td>
<select name="state" id="state">
<option value="">Select a State</option>
<?php
$sql2 = "SELECT * from states order by state_name";
$result2 = executequery($sql2);
while($record2 = go_fetch_array($result2)){
  ?><option value="<?=$record2['state_code']?>"><?=$record2['state_name']?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Zip</td>
<td><input type="text" name="zip" size="40" id="zip"></td>
</tr>



<tr>
<td align="right">Roof Size SQS</td>
<td><input type="text" name="roof_size" size="40"></td>
</tr>
<?php /*
<tr>
<td align="right">Roof Type</td>
<td><input type="text" name="roof_type" value="<?=$roof_type?>" size="40"></td>
</tr>
<tr>
<td align="right">Deck Type</td>
<td><input type="text" name="deck_type" value="<?=$deck_type?>" size="40"></td>
</tr>
<tr>
<td align="right">Installation Type</td>
<td><input type="text" name="installation_type" value="<?=$installation_type?>" size="40"></td>
</tr>
<tr>
<td align="right">Building Use</td>
<td><input type="text" name="building_use" value="<?=$building_use?>" size="40"></td>
</tr>
<tr>
<td align="right">Image</td>
<td><input type="file" name="image"></td>
</tr>
*/?>
</table>
</div>
<div style="float:left;">
<input type="checkbox" name="sameasbox" onchange="sameas(this)">Same as Company
</div>
</div>
<div style="clear:both;"></div>

<?php
if($SESSION_USE_GROUPS){
  $groups_raw = explode(",", $SESSION_GROUPS);
  $groups = array();
  for($x=0;$x<sizeof($groups_raw);$x++){
    if($groups_raw[$x] == "") continue;
	$groups[] = $groups_raw[$x];
  }
  
  $subgroups_raw = explode(",", $SESSION_SUBGROUPS);
  $subgroups = array();
  for($x=0;$x<sizeof($subgroups_raw);$x++){
    if($subgroups_raw[$x] == "") continue;
	$subgroups[] = $subgroups_raw[$x];
  }
  $checktype = 0; // belongs to multiple groups and or subgroups, display only SESSION_GROUPS and SESSION_SUBGROUPS
  if(sizeof($groups) <=1 && sizeof($subgroups) <=1) $checktype=1; // belongs to only one group and/or subgroup, don't even display boxes
  if($SESSION_GROUPS=="" && $SESSION_SUBGROUPS=="") $checktype=2; // must be core master, no group filters
  switch($checktype){
    case 2:{
	  ?>
	  <div class="main"><strong>Default Group/Subgroup for this property</strong><br>
	  <?php
$sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of=0 order by group_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $group_id = $record['id'];
  ?>
  <input type="checkbox" name="group_list[]" value="<?=$group_id?>" onchange="group_check()"> 
  <span id="group_<?=$group_id?>_arrow" style="display:none;"><a href="javascript:OpenClose('<?=$group_id?>', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
  <?=stripslashes($record['group_name'])?>
  <br>
  <div id="group_<?=$group_id?>" style="padding-left:20px; display:none;">
  <?php
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id' order by group_name";
  $result_sub = executequery($sql);
  if(mysql_num_rows($result_sub)) $SHOWARROW[] = $group_id;
  while($record_sub = go_fetch_array($result_sub)){
    $subgroup_id = $record_sub['id'];
	?>
	<input type="checkbox" name="subgroup_list[]" value="<?=$subgroup_id?>" onchange="subgroup_check()"> <?=stripslashes($record_sub['group_name'])?><br>
	<?php
  }
  ?>
  </div>
  <?php
}
?>
</div>
<script>
<?php
for($x=0;$x<sizeof($SHOWARROW);$x++){
  if($SHOWARROW[$x]=="") continue;
  ?>
  document.getElementById('group_<?=$SHOWARROW[$x]?>_arrow').style.display="";
  <?php
}
?>
</script>
    <?php
	break;
  }
  
  
  case 0:{
    ?>
	<div class="main"><strong>Default Group/Subgroup for this property</strong><br>
	Groups:<br>
	<?php
	for($x=0;$x<sizeof($groups);$x++){
	  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and id='" . $groups[$x] . "'";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $group_id = $record['id'];
      ?>
      <input type="checkbox" name="group_list[]" value="<?=$group_id?>" onchange="group_check()"> 
	  <?=stripslashes($record['group_name'])?>
      <br>
	  <?php
	}
	?>
	<br>
	Subgroups:<br>
	<?php
	for($x=0;$x<sizeof($subgroups);$x++){
	  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and id='" . $subgroups[$x] . "'";
      $result = executequery($sql);
      $record = go_fetch_array($result);
      $group_id = $record['id'];
      ?>
      <input type="checkbox" name="subgroup_list[]" value="<?=$group_id?>" onchange="subgroup_check()"> 
	  <?=stripslashes($record['group_name'])?>
      <br>
	  <?php
	}
	?>
	</div>
	<?php
	break;
  }
  
  case 1:{
    ?>
	<div style="display:none;">
	<?php
	for($x=0;$x<sizeof($groups);$x++){?>
	  <input type="checkbox" name="group_list[]" value="<?=$groups[$x]?>" checked="checked">
	  <?php
	}
	for($x=0;$x<sizeof($subgroups);$x++){?>
	  <input type="checkbox" name="subgroup_list[]" value="<?=$subgroups[$x]?>" checked="checked">
	  <?php
	}
	?>
	</div>
	<?php
	break;
  }
}

} // end if using groups
?>

<table class="main">
<tr>
<td colspan="2">
<input type="submit" name="submit1" id="submit1" value="<?=$topfiller?>">
</td>
</tr>
</table>
</form>
