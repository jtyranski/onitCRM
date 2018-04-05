<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
$todo_id = $_GET['todo_id'];
$redirect = $_GET['redirect'];

if(!$prospect_id){
  $sql = "SELECT prospect_id from prospects where display=1 order by company_name limit 1";
  $prospect_id = getsingleresult($sql);
}

if(!$property_id){
  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and display=1 order by corporate desc, site_name limit 1";
  $property_id = getsingleresult($sql);
}


if($_SESSION['ro_use_corporate']){
  $corpsql = " 1=1 ";
}
else {
  $corpsql = " corporate = 0 ";
}

$sql = "SELECT user_id from prospecting_todo where id='$todo_id'";
$last_action_user_id = getsingleresult($sql);
if($last_action_user_id==""){
  $sql = "SELECT user_id from activities where prospect_id = '$prospect_id' and complete=1 order by act_id desc limit 1";
  $last_action_user_id = getsingleresult($sql);
}
if($last_action_user_id=="") $last_action_user_id = $_GET['default_lastaction'];
if($last_action_user_id=="") $last_action_user_id = $SESSION_USER_ID;

$sql = "SELECT address, city, state, zip from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);

$address = go_reg_replace("\"", "", $address);
$city = go_reg_replace("\"", "", $city);
$state = go_reg_replace("\"", "", $state);
$zip = go_reg_replace("\"", "", $zip);



?>
<script>

function cleardate(f){
  f.value="";
}

function checkform(f){
  var errmsg = "";
  
  if(f.property_id.value==""){ errmsg += "Please select a property.\n";}
  if(f.property_id.value=="*"){
    if(f.newproperty_name.value=="" || f.newproperty_name.value=="Property Name"){ errmsg += "Please enter name for new property.\n";}
	if(f.newproperty_address.value=="" || f.newproperty_address.value=="Address"){ errmsg += "Please enter address for new property.\n";}
	if(f.newproperty_city.value=="" || f.newproperty_city.value=="City"){ errmsg += "Please enter city for new property.\n";}
	if(f.newproperty_zip.value=="" || f.newproperty_zip.value=="Zip"){ errmsg += "Please enter zip for new property.\n";}
  }
  
  if(f.prospect_result.value=="Candidate"){
    if(f.opp_user_id.value=="0") { errmsg += "Please select who this opportunity will be For\n";}
  }
  
  if(errmsg != ""){
    alert(errmsg);
	return(false);
  }
  else {
    return(true);
  }
}

function result_change(x){
  y = x.value;
  
  switch(y){
  case "Candidate":{

	for(z=2;z<=11;z++){
	  document.getElementById("prospecting" + z).style.display = "";
	}
	//document.getElementById("nonnewact").style.display="none";
        document.getElementById("hide").style.display="none";
   break;
  }
  case "Attempt":{
    document.getElementById("hide").style.display="";
for(z=2;z<=11;z++){
	  document.getElementById("prospecting" + z).style.display = "none";
	}
break;
  }
  default:{
	for(z=2;z<=11;z++){
	  document.getElementById("prospecting" + z).style.display = "none";
	}
        document.getElementById("hide").style.display="";
  break;
  }
}
}

function propertycheck(x){
  y = x.value;
  if(y=="*"){
    document.getElementById("addproperty").style.display="";
  }
  else {
    document.getElementById("addproperty").style.display="none";
  }
  z=document.getElementById('property_id')[document.getElementById('property_id').selectedIndex].innerHTML;
  
  var foo = / Corporate/.test(z);
  if(foo){ 
    document.form1.prospect_type.options.length=0;
	document.form1.prospect_type.options[0]=new Option("Dial", "Dial", true, false);
	document.form1.prospect_type.options[1]=new Option("Visit", "Visit", false, false);
	document.form1.prospect_type.options[2]=new Option("FCS Meetings", "<?=$UM?>", false, false);
	document.form1.prospect_type.options[3]=new Option("Email", "Email", false, false);
  }
  else {
    document.form1.prospect_type.options.length=0;
	document.form1.prospect_type.options[0]=new Option("Dial", "Dial", true, false);
	document.form1.prospect_type.options[1]=new Option("Visit", "Visit", false, false);
	document.form1.prospect_type.options[2]=new Option("Inspection", "<?=$I?>", false, false);
	document.form1.prospect_type.options[3]=new Option("FCS Meetings", "<?=$UM?>", false, false);
	document.form1.prospect_type.options[4]=new Option("Risk Report Presentation", "<?=$RRP?>", false, false);
	document.form1.prospect_type.options[5]=new Option("Bid Presentation", "<?=$BP?>", false, false);
	document.form1.prospect_type.options[6]=new Option("Email", "Email", false, false);
  }
  
  //alert(z);
}

function shownext(x){
  y = x.value;
  if(y=="2"){
    document.getElementById("nextactivity").style.display="none";
  }
  else {
    document.getElementById("nextactivity").style.display="";
  }
}
/*
function activitycheck(x){
  y = x.value;
  if(y=="2"){
    document.getElementById("nonnewact").style.display="";
  }
  else {
    document.getElementById("nonnewact").style.display="none";
  }
}
*/

function prospect_type_change(x){
  y = x.value;
  switch(y){
    case "Email":{
	  document.getElementById('type_email').style.display="";
	  document.getElementById('type_regular').style.display="none";
	  break;
	}
	default:{
	  document.getElementById('type_email').style.display="none";
	  document.getElementById('type_regular').style.display="";
	  break;
	}
  }
}

function show_email(x){
  if(x.checked==true){
    document.getElementById('type_email').style.display="";
  }
  else {
    document.getElementById('type_email').style.display="none";
  }
}


function bingmap(){
  var f = document.form1;
  var errmsg = "";
  
	if(f.newproperty_address.value=="" || f.newproperty_address.value=="Address"){ errmsg += "Please enter address for new property.\n";}
	if(f.newproperty_city.value=="" || f.newproperty_city.value=="City"){ errmsg += "Please enter city for new property.\n";}
	if(f.newproperty_zip.value=="" || f.newproperty_zip.value=="Zip"){ errmsg += "Please enter zip for new property.\n";}
  
  var bingaddress = "http://www.bing.com/maps/default.aspx?v=2&FORM=LMLTCP&cp=41.88415~-87.632409&style=r&lvl=10&tilt=-90&dir=0&alt=-1000&phx=0&phy=0&phscl=1&where1=";
  bingaddress += f.newproperty_address.value + "," + f.newproperty_city.value + "," + f.newproperty_state.value + "," + f.newproperty_zip.value;
  bingaddress += "&encType=1";
  
  if(errmsg != ""){
    alert(errmsg);
  }
  else{
    window.open(bingaddress);
  }
}


function email_preview_message(){
  document.emailpreview.email_message.value = document.form1.email_message.value;
  //document.emailpreview.user_id.value = document.form1.user_id.value;
  document.emailpreview.email_company_logo.value = document.form1.email_company_logo.value;
  if(document.form1.email_include_signature.checked==true){
    document.emailpreview.email_include_signature.value = 1;
  }
  else {
    document.emailpreview.email_include_signature.value = 0;
  }
  
  
  len = document.form1.elements.length;
  var i=0;
  var foo = "";
  for( i=0 ; i<len ; i++) {
    if (document.form1.elements[i].name=="email_banner" && document.form1.elements[i].checked==true) {
      foo = document.form1.elements[i].value;
    }
  }
  document.emailpreview.email_banner.value = foo;
  var i=0;
  var foo = "";
  for( i=0 ; i<len ; i++) {
    if (document.form1.elements[i].name=="email_movie" && document.form1.elements[i].checked==true) {
      foo = document.form1.elements[i].value;
    }
  }
  document.emailpreview.email_movie.value = foo;
  
 
  document.emailpreview.submit();
  
}

function ajax_do_email_content (x) {
  //alert(x);
  
 
  //alert(contmsg);
    url = "../activity_email_letterpop.php?property_id=<?=$property_id?>&id=" + x;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function doingaroof(x){
  y = x.value;
  if(y=="Yes"){
    document.form1.prospecting_product.value = "Roof Replacement";
  }
  if(y=="No"){
    document.form1.prospecting_product.value = "Roof Management";
  }
  if(y=="Solar"){
    document.form1.prospecting_product.value = "Solar";
  }
}
</script>
<script src="../includes/calendar.js"></script>
<form action="../activity_email_preview.php" method="post" name="emailpreview" target="_blank">
<input type="hidden" name="email_message" value="">
<input type="hidden" name="user_id" value="<?=$SESSION_USER_ID?>">
<input type="hidden" name="email_html" value="0">
<input type="hidden" name="email_include_signature" value="">
<input type="hidden" name="email_company_logo" value="">
<input type="hidden" name="email_banner" value="">
<input type="hidden" name="email_movie" value="">
<div style="display:none;"><input type="submit" name="submit1"></div>
</form>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="main" style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="prospecting_action.php"  method="post" name="form1" enctype="multipart/form-data" onsubmit="return checkform(this)">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="redirect" value="<?=$redirect?>">
<table class="main">

<tr>
<td align="right">Candidate Result:</td>
<td>
<select name="prospect_result" onchange="result_change(this)">
<option value="Connection">Connection</option>
<option value="Candidate">Candidate</option>
<option value="Attempt" selected="selected">Attempt</option>
</select>
</td>
</tr>


<tr id="prospecting2">
<td align="right">Doing a Roof</td>
<td>
<select name="prospecting_roof" onchange="doingaroof(this)">
<option value="No">No</option>
<option value="Yes">Yes</option>
<option value="Solar">Solar</option>
</select>
</td>
</tr>

<tr id="prospecting3">
<td align="right">Project Start</td>
<td>
<select name="prospecting_start_month">
<?php for($x=1;$x<=12;$x++){ ?>
  <option value="<?=$x?>"<?php if($x== date("n")) echo " selected";?>><?=$monthname[$x]?></option>
<?php } ?>
</select>
<select name="prospecting_start_year">
<?php for($x=date("Y");$x<=date("Y") + 5;$x++){ ?>
  <option value="<?=$x?>"><?=$x?></option>
<?php } ?>
</select>
</td>
</tr>

<tr id="prospecting4">
<td align="right">Funded</td>
<td>
<select name="prospecting_funded">
<option value="Yes">Yes</option>
<option value="No" selected="selected">No</option>
</select>
</td>
</tr>

<tr id="prospecting5">
<td align="right">Squares</td>
<td>
<input type="text" name="prospecting_squares" size="10">
</td>
</tr>

<tr id="prospecting6">
<td align="right">Procurement?</td>
<td>
<select name="prospecting_procurement">
<option value="Yes">Yes</option>
<option value="No" selected="selected">No</option>
<option value="No">Maybe</option>
</select>
</td>
</tr>

<tr id="prospecting7">
<td align="right">Attachment</td>
<td>
<input type="file" name="attachment">
</td>
</tr>



<tr id="prospecting8">
<td align="right">Scheduled By</td>
<td>
<select name="scheduled_by">
<option value="0"></option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$SESSION_USER_ID) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>

<tr id="prospecting9">
<td align="right">For</td>
<td>
<select name="opp_user_id">
<option value="0"></option>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and telemarketing=0 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr id="prospecting10">

</tr>
<tr id="prospecting11">
<td colspan="2">
<input type="checkbox" name="send_notification" value="1" checked="checked">Send Notification
</td>
</tr>


<input type="hidden" name="prospecting_product" value="Roof Management">


<tr>
<td align="right">Property</td>
<td id="propertyarea">
<select name="property_id" onchange="propertycheck(this)" id="property_id">
<?php
$foundprop = 0;
$sql = "SELECT * from properties where prospect_id='$prospect_id' and display=1 and $corpsql order by corporate desc, site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['property_id']?>"<?php if($property_id == $record['property_id']) {
    echo " selected";
	$foundprop=1; 
  }?>><?=stripslashes($record['site_name'])?></option>
  <?php
}
if($foundprop==0){
  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and corporate=1";
  $x_property_id = getsingleresult($sql);
  ?>
  <option value="<?=$x_property_id?>"></option>
  <?php
}
?>
<option value="*">Add a Property</option>
</select>
</td>
</tr>

<tr id="addproperty" style="display:none;">
<td></td>
<td>
<input type="text" name="newproperty_name" value="Property Name" onfocus="cleardate(this)">
<br>
<input type="text" name="newproperty_address" value="<?=$address?>">
<br>
<input type="text" name="newproperty_city" value="<?=$city?>">
<select name="newproperty_state">
<?php
$sql2 = "SELECT * from states order by state_name";
$result2 = executequery($sql2);
while($record2 = go_fetch_array($result2)){
  ?><option value="<?=$record2['state_code']?>"<?php if($state==$record2['state_code']) echo " selected";?>><?=$record2['state_name']?></option>
  <?php
}
?>
</select>
<input type="text" name="newproperty_zip" value="<?=$zip?>" size="10">
<br>
Contact<br>
First Name: <input type="text" name="newproperty_firstname" size="20"><br>
Last Name: <input type="text" name="newproperty_lastname" size="20"><br>
Phone: <input type="text" name="newproperty_phone" size="20"><br>
Property Image: <input type="file" name="image"><br>
<a href="javascript:bingmap()">Bing map</a>
</td>
</tr>


<tr>
<td align="right" valign="top">Notes</td>
<td><textarea name="notes" rows="3" cols="50"></textarea></td>
</tr>
<?php /*
<tr id="nonnewact" style="display:none;">
<td colspan="2"><input type="checkbox" name="no_act" value="1" checked="checked">Do not schedule next activity</td>
</tr>
*/ ?>

</table>

<div class="main">
<div id="hide"><input type="checkbox" name="hide" value="1">Hide</div>
<input type="checkbox" name="send_email" value="1" onchange="show_email(this)">Send Email
</div>

<div id="type_email" style="display:none;">
<table class="main">
<tr>
<td align="right">Company Header</td>
<td>
<select name="email_company_logo">
<option value="roofoptions_header.jpg">RoofOptions</option>
<option value="fcs_header_800.jpg">FCS</option>
<option value="phencon_logo.jpg">PhenCon</option>
<option value="DekTek_header.jpg">DekTek</option>
</select>
</td>
</tr>
<tr>
<td align="right" valign="top">Bcc</td>
<td valign="top">
  <table cellpadding="0" cellspacing="0" class="main">
  <tr>
  <?php
  $counter = 0;
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    if($counter==0) echo "<td valign='top'>\n";
    ?>
    <input type="checkbox" name="email_users[]" value="<?=$record['user_id']?>"<?php if($record['user_id']==7) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
    <?php
	$counter++;
	if($counter==5){
	  echo "</td>";
	  $counter=0;
	}
  }
  ?>
  </tr>
  </table>
</td>
</tr>

<tr>
<td align="right" valign="top">Guests</td>
<td>
<?php
guestList("email", $prospect_id, $property_id);
?>
<br>
Other emails: (please separate by a comma)<br>
<textarea name="email_others" rows="3" cols="50"></textarea>
</td>
</tr>

<input type="hidden" name="email_html" id="email_html" value="0">

<tr>
<td align="right">Default Text</td>
<td>
<select name="defaulttext" onchange="ajax_do_email_content(this.value)">
<option value="0"></option>
<?php
$sql = "SELECT * from email_default_text order by title";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['id']?>"><?=stripslashes($record['title'])?></option>
  <?php
}
?>
</select>
<?php if($SESSION_ISADMIN){ ?>
<a href="../email_default_list.php" target="_blank">Add/Edit Entries</a>
<?php } ?>
</td>
</tr>
<tr>
<td align="right">Subject</td>
<td><input type="text" name="email_subject" size="40" id="email_subject"></td>
</tr>
<tr>
<td valign="top" align="right">Message
<br>
<input type="button" name="buttonpreview" value="Preview" onClick="email_preview_message()">
</td>
<td id="email_textbox"><textarea name="email_message" rows="10" cols="80" id="email_message"></textarea></td>
</tr>

<?php 
$email_special_message = "Or, attach your own image and link it to a url";
include "../includes/email_specials.php";
?>


<tr>
<td valign="top" align="right">Include Signature?</td>
<td><input type="checkbox" name="email_include_signature" value="1" checked="checked">Yes</td>
</tr>
<tr>
<td valign="top" align="right">Attach</td>
<td><input type="file" name="emailattachment"></td>
</tr>
</table>
</div>

<input type="submit" name="submit1" value="Complete">
</form>
<script>
result_change(document.form1.prospect_result);
propertycheck(document.form1.property_id);
</script>
<?php include "includes/footer.php"; ?>

