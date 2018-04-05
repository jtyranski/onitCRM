<?php include "includes/header.php"; ?>
<?php
$todo_id = $_GET['id'];
$redirect = $_GET['redirect'];

$sql = "SELECT prospect_id, property_id, notes, date_format(last_action, \"%m/%d/%Y\") as lastaction 
from prospecting_todo where id='$todo_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_id = $record['prospect_id'];
$property_id = $record['property_id'];


$sql = "SELECT prospecting_rating from properties where property_id='$property_id'";
$prospecting_rating = getsingleresult($sql);

if($todo_id){
  $sql = "SELECT prospect_type from prospecting_todo where id='$todo_id'";
  $prospect_type = getsingleresult($sql);
}

if(in_array($SESSION_USER_ID, $onlyjim)){
  $block = "<tr id='prospecting_type'>
  <td align=\"right\">ID</td>
  <td>
  <select name=\"beazer_claim_id\" style='width:190px;'>";
  $sql = "SELECT * from properties_prospectingtype_beazer order by beazer_claim_id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $block .= "<option value=\"" . $record['beazer_claim_id'] . "\">" . stripslashes($record['prospecting_desc']) . "</option>";
  }
  $block .= "</select>
  </td>
  </tr>";
  $next_activity_filler = "No next activity scheduled if Beazer Claim ID is Contingency";
}
else {
  $block = "<tr id='prospecting_type'>
  <td align=\"right\">ID</td>
  <td>
  <select name=\"prospecting_type\" onchange=\"shownext(this)\" style='width:190px;'>";
  $sql = "SELECT * from properties_prospectingtype order by prospecting_type";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $block .= "<option value=\"" . $record['prospecting_type'] . "\">" . stripslashes($record['prospecting_desc']) . "</option>";
  }
  $block .= "</select>
  </td>
  </tr>";
  $next_activity_filler = "No next activity scheduled if Prospecting ID is Not a Candidate";
}


$corpsql = " 1=1 ";
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
  if(y=="Prospect"){
    document.getElementById("prospecting_type").style.display = "none";
	for(z=2;z<=6;z++){
	  document.getElementById("prospecting" + z).style.display = "";
	}
	//document.getElementById("nonnewact").style.display="none";
	document.getElementById("nextactivity").style.display = "none";
  }
  else {
    document.getElementById("prospecting_type").style.display = "";
	document.getElementById("nextactivity").style.display = "";
	for(z=2;z<=6;z++){
	  document.getElementById("prospecting" + z).style.display = "none";
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



/*
function email_preview_message(){
  document.emailpreview.email_message.value = document.form1.email_message.value;
  document.emailpreview.user_id.value = document.form1.user_id.value;
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
    url = "activity_email_letterpop.php?property_id=<?=$property_id?>&id=" + x;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}
*/
</script>
<script src="includes/calendar.js"></script>
<?php /*
<form action="activity_email_preview.php" method="post" name="emailpreview" target="_blank">
<input type="hidden" name="email_message" value="">
<input type="hidden" name="user_id" value="">
<input type="hidden" name="email_html" value="0">
<input type="hidden" name="email_include_signature" value="">
<input type="hidden" name="email_company_logo" value="">
<input type="hidden" name="email_banner" value="">
<input type="hidden" name="email_movie" value="">
<div style="display:none;"><input type="submit" name="submit1"></div>
</form>
*/ ?>

<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">

</td>
</tr>
</table>
<br><br>
<form action="candidate_complete_action.php"  method="post" name="form1" onsubmit="return checkform(this)">
<input type="hidden" name="todo_id" value="<?=$todo_id?>">
<input type="hidden" name="redirect" value="<?=$redirect?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<table class="main">

<tr>
<td align="right">Result:</td>
<td>
<select name="prospect_result" onchange="result_change(this)">
<option value="Connection">Connection</option>
<option value="Prospect">Prospect</option>
<option value="Attempt" selected="selected">Attempt</option>
</select>
</td>
</tr>

<tr>
<td align="right">Level</td>
<td>
<select name="prospecting_rating">
<option value="1"<?php if($prospecting_rating==1) echo " selected";?>>1</option>
<option value="2"<?php if($prospecting_rating==2) echo " selected";?>>2</option>
<option value="3"<?php if($prospecting_rating==3) echo " selected";?>>3</option>
</select>
</td>
</tr>

<?=$block?>



<tr id="prospecting2">
<td align="right">Doing a Roof</td>
<td>
<select name="prospecting_roof">
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
</td>
</tr>

<tr id="prospecting3">
<td align="right">Project Start</td>
<td>
<select name="prospecting_start_month">
<?php for($x=1;$x<=12;$x++){ ?>
  <option value="<?=$x?>"><?=$monthname[$x]?></option>
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
<option value="No">No</option>
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
<option value="No">No</option>
<option value="No">Maybe</option>
</select>
</td>
</tr>

<tr>
<td align="right">Property</td>
<td id="propertyarea">
<select name="property_id" onchange="propertycheck(this)" style='width:190px;'>
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
?>
<option value="*"<?php if($foundprop==0) echo " selected";?>>Add a Property</option>
</select>
</td>
</tr>

<tr id="addproperty" style="display:none;">
<td></td>
<td>
<input type="text" name="newproperty_name" value="Property Name" onfocus="cleardate(this)">
<br>
<input type="text" name="newproperty_address" value="Address" onfocus="cleardate(this)">
<br>
<input type="text" name="newproperty_city" value="City" onfocus="cleardate(this)">
<br>
<select name="newproperty_state">
<?php
$sql2 = "SELECT * from states order by state_name";
$result2 = executequery($sql2);
while($record2 = go_fetch_array($result2)){
  ?><option value="<?=$record2['state_code']?>"><?=$record2['state_name']?></option>
  <?php
}
?>
</select>
<input type="text" name="newproperty_zip" value="Zip" onfocus="cleardate(this)" size="10">
</td>
</tr>


<tr>
<td align="right" valign="top">Notes</td>
<td><textarea name="notes" rows="3" cols="20"></textarea></td>
</tr>
<?php /*
<tr id="nonnewact" style="display:none;">
<td colspan="2"><input type="checkbox" name="no_act" value="1" checked="checked">Do not schedule next activity</td>
</tr>
*/ ?>

</table>
<div id="nextactivity" style="display:none;">
<table class="main">
<tr>
<td colspan="2">Follow up Candidate Activity<br><?=$next_activity_filler?></td>
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
<td align="right" valign="middle">Date</td>
<td>
<input size="10" type="text" name="datepretty" value="<?=date("m/d/Y", time() + (7 * 24 * 60 * 60))?>"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
</td>
</tr>

<tr>
<td align="right">Type:</td>
<td>
<select name="prospect_type" onchange="prospect_type_change(this)">
<option value="Dial">Dial</option>
<option value="Visit">Visit</option>
<option value="Inspection"><?=$I?></option>
<option value="FCS Meetings"><?=$UM?></option>
<option value="Risk Report Presentation"><?=$RRP?></option>
<option value="Bid Presentation"><?=$BP?></option>
<?php /*<option value="Email">Email</option> */ ?>
</select>
</td>
</tr>
</table>

<table class="main" id="type_regular">
<tr>
<td align="right">Regarding</td>
<td><input type="text" name="regarding" size="20"></td>
</tr>
<tr>
<td valign="top" align="right">Details</td>
<td><textarea name="details" cols="20" rows="3"></textarea></td>
</tr>
</table>
<?php /*
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
    <input type="checkbox" name="email_users[]" value="<?=$record['user_id']?>"><?=stripslashes($record['fullname'])?><br>
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
include "includes/email_specials.php";
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
*/ ?>
</div>


<input type="submit" name="submit1" value="Complete Activity" style="width:290px;">
<br><br>
<input type="button" name="button1" onclick="history.go(-1)" value="Go Back" style="width:290px;">
</form>
<script>
result_change(document.form1.prospect_result);
propertycheck(document.form1.property_id);
</script>
<?php include "includes/footer.php"; ?>

