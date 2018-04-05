<?php include "includes/header_white.php"; ?>

<table class="main" width="100%">
<tr>
<?php //include "includes/stats.php"; ?>
<td valign="top">


<?php
$user_id = $_GET['user_id'];
$prospect_id = $_GET['prospect_id'];

if($user_id != "new"){
  $sql = "SELECT * from am_users where user_id='$user_id' and prospect_id='" . $prospect_id . "'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $edit_firstname = stripslashes($record['firstname']);
  $edit_lastname = stripslashes($record['lastname']);
  $edit_email = stripslashes($record['email']);
  $edit_title = stripslashes($record['title']);
  $edit_phone = stripslashes($record['phone']);
  $edit_cell = stripslashes($record['cell']);
  $edit_admin = $record['admin'];
  $edit_photo = $record['photo'];
  $edit_divisions = $record['divisions'];
  $edit_sites = $record['sites'];
  $edit_edit_mode = $record['edit_mode'];
  $edit_reports = $record['reports'];
  $edit_enabled = $record['enabled'];
  $edit_force_change = $record['force_change'];
  $level_access = $record['level_access'];
  $sd_resource = $record['sd_resource'];
  $sd_notify = $record['sd_notify'];
}

$sql = "SELECT id, concat(firstname, ' ', lastname) as fullname from contacts where prospect_id='" . $prospect_id . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $userlist_id[] = $record['id'];
  $userlist_name[] = stripslashes($record['fullname']);
}
$sql = "SELECT property_id from properties where prospect_id='" . $prospect_id . "' and display=1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_property_id = $record['property_id'];
  $sql = "SELECT id, concat(firstname, ' ', lastname) as fullname from contacts where property_id='" . $x_property_id . "'";
  $result2 = executequery($sql);
  while($record2 = go_fetch_array($result2)){
    $userlist_id[] = $record2['id'];
    $userlist_name[] = stripslashes($record2['fullname']);
  }
}
?>
<script>
function checkform(f){
  errmsg = "";
  //if(f.firstname.value==""){ errmsg += "Please enter first name. \n"; }
  //if(f.lastname.value==""){ errmsg += "Please enter last name. \n"; }
  if(f.email.value==""){ errmsg += "Please enter email. \n"; }
  <?php if($user_id=="new"){ ?>
  if(f.password.value==""){ errmsg += "Please enter a password. \n"; }
  <?php } ?>
  if(f.password.value != ""){
    var a = f.password.value.length;
	if(a < 5){
	  errmsg += "Password must be at least 5 characters\n";
	}
  }
  /*
  if(f.sd_resource.checked==true){
    var onedivision = 0;
	var onesite = 0;
    len = f.elements.length;
    var i=0;
    for( i=0 ; i<len ; i++) {
      if (f.elements[i].name=="divisions[]" && f.elements[i].checked==true) {
        onedivision = 1;
      }
	  if (f.elements[i].name=="sites[]" && f.elements[i].checked==true) {
        onesite = 1;
      }
    }
	if(onedivision==0){ errmsg += "If this user is an Internal Maintenance Resource, please check at least one accessable division.\n";}
	if(onesite==0){ errmsg += "If this user is an Internal Maintenance Resource, please check at least one accessable site.\n";}
  }
  */
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}
</script>

<script>
var form='form1'; //Give the form name here

function ajax_do () {

  dml=document.forms[form];
  len = dml.elements.length;
  var x = "";
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name=="divisions[]" && dml.elements[i].disabled==false && dml.elements[i].checked==true) {
      x = x + "," + dml.elements[i].value + ",";
    }
  }
  //alert(x);
  var url = "frame_prospect_portalusers_sites.php";
  var qStr = "prospect_id=<?=$prospect_id?>&divisions=" + x + "&edit_sites=<?=$edit_sites?>";
    url=url+"?"+qStr; 
    url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

</script>

<script>
var form='form1'; //Give the form name here

function SetChecked(val,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=val;
    }
  }
  if(chkName=="divisions[]") ajax_do();
}

function go_name(x){
  if(x.value==0){
    document.getElementById('firstname_area').style.display="";
	document.getElementById('lastname_area').style.display="";
	document.getElementById('firstname').value="";
	document.getElementById('lastname').value="";
	document.getElementById('email').value="";
  }
  else {
    document.getElementById('firstname_area').style.display="none";
	document.getElementById('lastname_area').style.display="none";
  }
  
  if(x.value != 0){
    var url = "frame_prospect_portalusers_namefill.php?id=" + x.value;
    url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
  }
}

function sitestable_display(x){
  if(x.value=="Master Admin") { 
    document.getElementById('sitestable').style.display='none';
  }
  else {
    document.getElementById('sitestable').style.display='';
  }
}
</script>

<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error" style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="frame_prospect_portalusers_edit_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="user_id" value="<?=$user_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<table class="main">
<tr>
<td valign="top">



<table class="main">
<tr>
<td align="right">Name</td>
<td>
<select name="nameselect" onchange="go_name(this)">
<option value="0">Other</option>
<?php
for($x=0;$x<sizeof($userlist_id);$x++){
?>
<option value="<?=$userlist_id[$x]?>"><?=$userlist_name[$x]?></option>
<?php } ?>
</select>
</td>
</tr>

<tr id="firstname_area">
<td align="right">First Name</td>
<td><input type="text" name="firstname" value="<?=$edit_firstname?>" id="firstname"></td>
</tr>
<tr id="lastname_area">
<td align="right">Last Name</td>
<td><input type="text" name="lastname" value="<?=$edit_lastname?>" id="lastname"></td>
</tr>
<tr>
<td align="right">Email</td>
<td><input type="text" name="email" value="<?=$edit_email?>" id="email"></td>
</tr>
<tr>
<td align="right"><?php if($user_id != "new") echo "New ";?>Password</td>
<td><input type="text" name="password">(5 character minimum)</td>
</tr>
<?php if($user_id != "new"){ ?>
<tr>
<td colspan="2">Leave blank to keep existing password</td></tr>
<?php } ?>
<tr>
<td align="right">Allow Login?</td>
<td><input type="checkbox" name="enabled" value="1"<?php if($edit_enabled || $user_id=="new") echo " checked";?>> Yes</td>
</tr>
<?php /*
<tr>
<td align="right">Force Password Change<br>On Next Login?</td>
<td valign="top"><input type="checkbox" name="force_change" value="1"<?php if($edit_force_change || $user_id=="new") echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Internal Maintenance Resource?</td>
<td><input type="checkbox" name="sd_resource" value="1"<?php if($sd_resource) echo " checked";?>> Yes</td>
</tr>
*/?>
<tr>
<td align="right">Service Dispatch Emails?</td>
<td><input type="checkbox" name="sd_notify" value="1"<?php if($sd_notify) echo " checked";?>> Yes</td>
</tr>
<?php /*
<tr>
<td align="right">Login to Admin?</td>
<td><input type="checkbox" name="admin" value="1"<?php if($edit_admin) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">Edit Records?</td>
<td><input type="checkbox" name="edit_mode" value="1"<?php if($edit_edit_mode) echo " checked";?>> Yes</td>
</tr>
<tr>
<td align="right">View Reports?</td>
<td><input type="checkbox" name="reports" value="1"<?php if($edit_reports) echo " checked";?>> Yes</td>
</tr>
*/ ?>
<tr>
<td align="right">User Access</td>
<td>
<select name="level_access" onchange="sitestable_display(this)">
<option value="User"<?php if($level_access=="User") echo " selected";?>>User</option>
<?php /*
<option value="User - Reports"<?php if($level_access=="User - Reports") echo " selected";?>>User - Reports</option>
<option value="Site Admin"<?php if($level_access=="Site Admin") echo " selected";?>>Site Admin</option>
*/ ?>
<option value="Master Admin"<?php if($level_access=="Master Admin") echo " selected";?>>Master Admin</option>
</select>
</td>
</tr>
</table>


</td>
<td valign="top">
<?php /*
User Access definitions:<br><br>
<strong>User</strong> : Has access to view information for all selected divisions/sites.<br><br>
<strong>User - Reports</strong> : Has access to view information for all selected divisions/sites, and expense/budget reports.<br><br>
<strong>Site Admin</strong> : Has access to view/edit information for all selected divisions/sites, and expense/budget reports.<br><br>
<strong>Master Admin</strong> : Has access to view/edit all information, and access admin features.
*/?>
</td>
</table>

<table class="main" id="sitestable">
<tr>
<?php /*
<td valign="top">
<?php
$sql = "SELECT count(*) from am_divisions where prospect_id='" . $SESSION_VIEW_PROSPECT_ID . "'";
$test = getsingleresult($sql);
if($test){
?>
Site Divisions Access:<br><br>
<a href="javascript:SetChecked(1,'divisions[]')">Check All</a> &nbsp; &nbsp;
<a href="javascript:SetChecked(0,'divisions[]')">Uncheck All</a><br><br>
<?php
$sql = "SELECT division_id, division_name from am_divisions where prospect_id='" . $SESSION_VIEW_PROSPECT_ID . "' order by division_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="divisions[]" value="<?=$record['division_id']?>"<?php if(go_reg("\," . $record['division_id'] . "\,", $edit_divisions)) echo " checked";?> onchange="ajax_do()">
  <?=stripslashes($record['division_name'])?><br>
  <?php
}
?>
<?php } ?>
</td>
<td width="20">&nbsp;</td>
*/?>
<td valign="top">
<?php
$sql = "SELECT count(*) from properties where prospect_id='" . $prospect_id . "' and display=1 and corporate=0";
$test = getsingleresult($sql);
if($test){
?>
Site Access:<br><br>
<a href="javascript:SetChecked(1,'sites[]')">Check All</a> &nbsp; &nbsp;
<a href="javascript:SetChecked(0,'sites[]')">Uncheck All</a><br><br>
<div id="sitelist">
<?php
$sql = "SELECT property_id, site_name, division_id from properties where prospect_id='" . $prospect_id . "' 
and display=1 and corporate=0 order by site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
 // if(!go_reg("\," . $record['division_id'] . "\,", $edit_divisions) && $record['division_id'] != 0) continue;
  ?>
  <input type="checkbox" name="sites[]" value="<?=$record['property_id']?>"<?php if(go_reg("\," . $record['property_id'] . "\,", $edit_sites)) echo " checked";?>>
  <?=stripslashes($record['site_name'])?><br>
  <?php
}
?>
</div>
<?php } ?>
</td>
</tr>
</table>
<input type="submit" name="submit1" value="Update User">
</form>


</td>
</tr>
</table>
<br>

<script>
sitestable_display(document.form1.level_access);
</script>