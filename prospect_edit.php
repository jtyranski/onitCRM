<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
if($prospect_id=="") $prospect_id = "new";

if($prospect_id != "new"){
  $sql = "SELECT * from prospects where prospect_id='$prospect_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $property_type = stripslashes($record['property_type']);
  $prospect_status = stripslashes($record['prospect_status']);
  $company_name = stripslashes($record['company_name']);
  $address = stripslashes($record['address']);
  $city = stripslashes($record['city']);
  $state = stripslashes($record['state']);
  $zip = stripslashes($record['zip']);
  
  
  $logo = $record['logo'];
  
  $topfiller = "Edit Prospect";
}
else {
  $topfiller = "Add New Prospect";
}

if($_SESSION[$sess_header . '_prospect']['property_type'] != "") $property_type = $_SESSION[$sess_header . '_prospect']['property_type'];
if($_SESSION[$sess_header . '_prospect']['company_name'] != "") $company_name = $_SESSION[$sess_header . '_prospect']['company_name'];
if($_SESSION[$sess_header . '_prospect']['address'] != "") $address = $_SESSION[$sess_header . '_prospect']['address'];
if($_SESSION[$sess_header . '_prospect']['city'] != "") $city = $_SESSION[$sess_header . '_prospect']['city'];
if($_SESSION[$sess_header . '_prospect']['state'] != "") $state = $_SESSION[$sess_header . '_prospect']['state'];
if($_SESSION[$sess_header . '_prospect']['zip'] != "") $zip = $_SESSION[$sess_header . '_prospect']['zip'];


?>
<script>
function checkform(f){
  errmsg = "";
  <?php if($SESSION_RESIDENTIAL){?>
  if(document.getElementById('com').checked==true){
    if(f.company_name.value==""){ errmsg += "Please enter company name. \n"; }
  }
  if(document.getElementById('res').checked==true){
    if(f.prospect_firstname.value==""){ errmsg += "Please enter first name. \n"; }
	if(f.prospect_lastname.value==""){ errmsg += "Please enter last name. \n"; }
  }
  <?php } else { ?>
    if(f.company_name.value==""){ errmsg += "Please enter company name. \n"; }
  <?php } ?>
  if(f.phone.value=="" && f.mobile.value==""){ errmsg += "Please enter a contact phone number. \n"; }
  /*
  if(f.address.value==""){ errmsg += "Please enter address. \n"; }
  if(f.city.value==""){ errmsg += "Please enter city. \n"; }
  if(f.state.value==""){ errmsg += "Please enter state. \n"; }
  if(f.zip.value==""){ errmsg += "Please enter zip. \n"; }
  
  if(f.contact1.value==""){ errmsg += "Please enter primary contact. \n"; }
  if(f.phone1.value==""){ errmsg += "Please enter primary contact phone. \n"; }
  if(f.email1.value==""){ errmsg += "Please enter primary contact email. \n"; }
  var atsign = /\@/.test(f.email1.value);
  var dot = /\./.test(f.email1.value);
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

var form='form1'; //Give the form name here

function NoOthers(chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  x = document.form1.property_type_contractor.checked;
  if(x==true){
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name==chkName) {
        dml.elements[i].checked=0;
	    dml.elements[i].disabled=1;
      }
    }
  }
  else {
    for( i=0 ; i<len ; i++) {
      if (dml.elements[i].name==chkName) {
	    dml.elements[i].disabled=0;
      }
    }
  }
}
</script>
<script src="includes/jquery-1.6.4.js"></script>
<script src="includes/jquery.maskedinput-1.3.js"></script>
<script src="includes/jquery.livequery.js"></script>
<script type="text/javascript"> 
$(".phoneext").livequery(function(){
    $(this).mask('(999) 999-9999? x99999');
});
</script>
<script>
function prospect_type_change(x){
document.getElementById('manuf').style.display="none";
document.getElementById('resourcedis').style.display="none";
  switch(x.value){
    case "Prospect":{
	  document.getElementById('prospect_type_area').innerHTML = "On Prospect List";
	  break;
	}
	case "Vendor":{
	  document.getElementById('prospect_type_area').innerHTML = "On Vendor List";
	  document.getElementById('manuf').style.display="";
	  break;
	}
	case "Resource":{
	  document.getElementById('prospect_type_area').innerHTML = "On Vendor List, available as Resource";
	  document.getElementById('resourcedis').style.display="";
	  break;
	}
  }
}
function gocomres(x){
  if(x.value=="C"){
    document.getElementById('residential1').style.display='none';
	document.getElementById('residential2').style.display='none';
	document.getElementById('commercial1').style.display='';
  }
  else {
    document.getElementById('residential1').style.display='';
	document.getElementById('residential2').style.display='';
	document.getElementById('commercial1').style.display='none';
  }
}

function firstnamefill(){
  if(document.getElementById('firstname').value==""){
    document.getElementById('firstname').value = document.getElementById('prospect_firstname').value;
  }
}
function lastnamefill(){
  if(document.getElementById('lastname').value==""){
    document.getElementById('lastname').value = document.getElementById('prospect_lastname').value;
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
  var form='form1';
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
  var form='form1';
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
<div align="center">
  <div class="whiteround" style="height:600px;" align="left">
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<div class="main" style="height:100%; overflow:auto;">
<form action="prospect_edit_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<table class="main">
<?php if($SESSION_RESIDENTIAL){?>
<tr>
<td colspan="2">
<input type="radio" name="comres" value="C" checked="checked" onchange="gocomres(this)" id="com">Commercial &nbsp;
<input type="radio" name="comres" value="R" onchange="gocomres(this)" id="res">Residential
</td>
</tr>
<?php } ?>
<tr id="residential1" style="display:none;">
<td align="right">First Name</td>
<td><input type="text" name="prospect_firstname" id="prospect_firstname" onblur="firstnamefill()"></td>
</tr>
<tr id="residential2" style="display:none;">
<td align="right">Last Name</td>
<td><input type="text" name="prospect_lastname" id="prospect_lastname" onblur="lastnamefill()"></td>
</tr>

<tr id="commercial1">
<td align="right">Company Name</td>
<td><input type="text" name="company_name" value="<?=$company_name?>" size="40"></td>
</tr>
<tr>
<td align="right">Address</td>
<td><input type="text" name="address" value="<?=$address?>" size="40"></td>
</tr>
<tr>
<td align="right">City</td>
<td><input type="text" name="city" value="<?=$city?>" size="40"></td>
</tr>
<tr>
<td align="right">State</td>
<td>
<select name="state">
<option value="">Select a State</option>
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
</tr>

<tr>
<td align="right">Zip</td>
<td><input type="text" name="zip" value="<?=$zip?>" size="40"></td>
</tr>

<tr>
<td align="right">Type</td>
<td>
<select name="prospect_type" onchange="prospect_type_change(this)">
<option value="Prospect">Prospect</option>
<option value="Vendor">Vendor</option>
<?php if($SESSION_USE_RESOURCES==1){ ?>
<option value="Resource"<?php if($_GET['resource']==1) echo " selected";?>>Resource</option>
<?php } ?>
</select>
<span id="prospect_type_area">On Prospect List</span>
</td>
</tr>
<tr id="manuf" style="display:none;">
<td></td>
<td>
<input type="checkbox" name="manufacturer" value="1">Manufacturer<br>
<input type="checkbox" name="installer" value="1">Installer

</td>
</tr>
<tr id="resourcedis" style="display:none;">
<td></td>
<td>

This Resource deals in the following:
<br>
<?php
  $sql = "SELECT dis_id, discipline from disciplines order by dis_id";
  $result = executequery($sql);
  $DIS_NAMES = array();
  while($record = go_fetch_array($result)){
    $xdis_id = $record['dis_id'];
	$DIS_NAMES[$xdis_id] = stripslashes($record['discipline']);
  }
  
  for($x=0;$x<sizeof($SESSION_DIS);$x++){ 
    $xdis_id = $SESSION_DIS[$x];?>
	<input type="checkbox" name="dis_id[]" value="<?=$xdis_id?>"><?=$DIS_NAMES[$xdis_id]?><br>
	<?php
  }
?>
</td>
</tr>

<tr>
<td align="right">Logo</td>
<td><input type="file" name="logo"></td>
</tr>
<tr>
<td colspan="2">Contact Info</td>
</tr>
<tr>
<td>Position</td>
<td><input type="text" name="position"></td>
</tr>
<tr>
<td>First Name</td>
<td><input type="text" name="firstname" id="firstname"></td>
</tr>
<tr>
<td>Last Name</td>
<td><input type="text" name="lastname" id="lastname"></td>
</tr>
<tr>
<td>Office</td>
<td><input type="text" name="phone" class='phoneext'></td>
</tr>
<tr>
<td>Mobile</td>
<td><input type="text" name="mobile" class='phoneext'></td>
</tr>
<tr>
<td>Fax</td>
<td><input type="text" name="fax" class='phoneext'></td>
</tr>
<tr>
<td>Email</td>
<td><input type="text" name="email"></td>
</tr>

</table>

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
	  <div class="main"><strong>Default Group/Subgroup for this company</strong><br>
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
	<div class="main"><strong>Default Group/Subgroup for this company</strong><br>
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
	

<input type="submit" name="submit1" id="submit1" value="<?=$topfiller?>">
</form>
</div>
</div>
</div>
<script>
prospect_type_change(document.form1.prospect_type);
</script>
<?php include "includes/footer.php"; ?>