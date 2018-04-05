<?php 
include "includes/functions.php";

/*
function jsclean($x){

  $x = go_reg_replace("\n", "", $x);
  $x = go_reg_replace("\r", "", $x);
  $x = go_reg_replace("'", "\\'", $x);
  //$x = go_reg_replace("\"", "\"", $x);
 // $x = go_reg_replace("\<", "&lt;", $x);
 // $x = go_reg_replace("\>", "&gt;", $x);
 $x = go_reg_replace(chr(12), "", $x);
 $x = go_reg_replace(chr(15), "", $x);
 $x = trim($x);
 
 //$x = trim(str_replace("\r\n", "", $x));
 
 
 //return (string)str_replace(array("\r", "\r\n", "\n"), '', $x);
  return $x;
}
*/
$event = $_GET['event'];
$property_id = $_GET['property_id'];
$prospect_id = $_GET['prospect_id'];

$html = "";

switch($event){
//**************************************************************************** SEND EMAIL *********************************************************

  case "Send Email":{
    ob_start();
	?>
	
	<input type="hidden" name="email_html" id="email_html" value="0">
<table class="main">
<tr>
<td>Company Header</td>
<td>
<select name="email_company_logo" onChange="company_header(this)">
<option value="encite_logo.png">Encite</option>
<option value="roofoptions_header.jpg">RoofOptions</option>
<option value="fcs_header_800.jpg">FCS</option>
<option value="phencon_logo.jpg">PhenCon</option>
<option value="DekTek_header.jpg">DekTek</option>
</select>
</td>
</tr>
<tr>
<td>Footer</td>
<td>
<select name="email_footer">
<option value="">None</option>
<option value="solarpros_footer.png">RoofOptions Solar Pros</option>
</select>
</td>
</tr>

<tr>
<td align="right" valign="top">Bcc</td>
<td>
  <table class="main">
  <tr>
  <?php
  $counter=0;
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    if($counter==0) echo "<td valign='top'>\n";
    ?>
    <input type="checkbox" name="email_users[]" value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
    <?php
	$counter++;
	if($counter==5){
	  echo "</td>\n";
	  $counter=0;
	}
  }
  ?>
  </tr>
  </table>
</td>
</tr>

<tr>
<td align="right" valign="top">To</td>
<td>
<?php
$selected = $_GET['selected'];
guestList("email", $prospect_id, $property_id, $selected);
?>
<br>
More: (separate each by a comma)<br>
<textarea name="email_more" rows="3" cols="40"></textarea>
</td>
</tr>

<?php 
$email_special_message = "Or, attach your own image and link it to a url";
include "includes/email_specials.php";
?>

<tr>
<td align="right">Default Text</td>
<td id="default_text_area">
<select name="defaulttext" onchange="ajax_do_email_content(this.value)">
<option value="0"></option>
<?php
$sql = "SELECT * from email_default_text where (company='Encite' or company='') order by title";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['id']?>"><?=stripslashes($record['title'])?></option>
  <?php
}
?>
</select>
<?php if($SESSION_ISADMIN){ ?>
<a href="email_default_list.php">Add/Edit Entries</a>
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
<input type="button" name="buttonpreview" value="Preview" onclick="email_preview_message()">
</td>
<td id="email_textbox"><textarea name="email_message" rows="10" cols="80" id="email_message"></textarea></td>
</tr>
<tr>
<td valign="top" align="right">Include Signature?</td>
<td><input type="checkbox" name="email_include_signature" value="1" checked="checked">Yes</td>
</tr>
<tr>
<td valign="top" align="right">Attach</td>
<td><input type="file" name="emailattachment"></td>
</tr>
<tr>
</table>

  <table class="main">
  <tr>
  <td colspan="3">
  Test Cuts <a href="javascript:SetCheckedEmailDiv(1, 'check_testcut[]')">Check All</a> - 
  <a href="javascript:SetCheckedEmailDiv(0, 'check_testcut[]')">Uncheck All</a>
  </td>
  </tr>
  <?php
  $counter = 0;
  $sql = "SELECT section_id, section_name from sections where property_id='$property_id' and display=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x_section_id = $record['section_id'];
    $section_name = stripslashes($record['section_name']);
  
    
    $sql = "SELECT * from sections_testcuts where section_id='$x_section_id'";
    $res_photo = executequery($sql);
    while($photo = go_fetch_array($res_photo)){
      if($counter==0) echo "<tr>\n";
	  ?>
	  <td valign="top">
	  <img src="uploaded_files/testcuts/<?=$photo['photo']?>"><br>
	  <?=$section_name?><br>
	  <input type="checkbox" name="check_testcut[]" value="<?=$photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
  }
  ?>
  </table>
  
  <table class="main">
  <tr>
  <td colspan="3">
  Photos <a href="javascript:SetCheckedEmailDiv(1, 'check_photo[]')">Check All</a> - 
  <a href="javascript:SetCheckedEmailDiv(0, 'check_photo[]')">Uncheck All</a>
  </td>
  </tr>
  <?php
  $counter = 0;
  $sql = "SELECT section_id, section_name from sections where property_id='$property_id' and display=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x_section_id = $record['section_id'];
    $section_name = stripslashes($record['section_name']);
  
    
    $sql = "SELECT * from sections_photos where section_id='$x_section_id'";
    $res_photo = executequery($sql);
    while($photo = go_fetch_array($res_photo)){
      if($counter==0) echo "<tr>\n";
	  ?>
	  <td valign="top">
	  <img src="uploaded_files/photos/<?=$photo['photo']?>"><br>
	  <?=$section_name?><br>
	  <input type="checkbox" name="check_photo[]" value="<?=$photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
  }
  ?>
  </table>
  
  <table class="main">
  <tr>
  <td colspan="3">
  Interior Photos<a href="javascript:SetCheckedEmailDiv(1, 'check_interiorphoto[]')">Check All</a> - 
  <a href="javascript:SetCheckedEmailDiv(0, 'check_interiorphoto[]')">Uncheck All</a>
  </td>
  </tr>
  <?php
  $counter = 0;
  $sql = "SELECT section_id, section_name from sections where property_id='$property_id' and display=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $x_section_id = $record['section_id'];
    $section_name = stripslashes($record['section_name']);
  
    
    $sql = "SELECT * from sections_interiorphotos where section_id='$x_section_id'";
    $res_photo = executequery($sql);
    while($photo = go_fetch_array($res_photo)){
      if($counter==0) echo "<tr>\n";
	  ?>
	  <td valign="top">
	  <img src="uploaded_files/interior_photos/<?=$photo['photo']?>"><br>
	  <?=$section_name?><br>
	  <input type="checkbox" name="check_interiorphoto[]" value="<?=$photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
  }
  ?>
  </table>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  break;
}

//*********************************************************************  CONFERENCE CALL **********************************************

  case "Conference Call":{
    ob_start();
	?>
	<table class="main">
<tr>
<td align="right">Date</td>
<td>
<input size="10" type="text" name="cc_date" <?php if($cc_date =="") {echo" value=\"" . date("m/d/Y", time() + (7 * 24 * 60 * 60)) . "\"";} else { echo " value=\"" . $cc_date . "\"";}?> onClick="cleardate(this)"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('cc_date',0)" align="absmiddle">
&nbsp;&nbsp;
Time: 
<select name="cc_hour">
<?php for($x=1;$x<=12;$x++){ ?>
<option value="<?=$x?>"<?php if($x==$cc_hour) echo " selected";?>><?=$x?></option>
<?php } ?>
</select>
:
<select name="cc_minute">
<option value="00"<?php if($cc_minute=="00") echo " selected";?>>00</option>
<option value="15"<?php if($cc_minute=="15") echo " selected";?>>15</option>
<option value="30"<?php if($cc_minute=="30") echo " selected";?>>30</option>
<option value="45"<?php if($cc_minute=="45") echo " selected";?>>45</option>
</select>
<select name="cc_ampm">
<option value="AM"<?php if($cc_ampm=="AM") echo " selected";?>>AM</option>
<option value="PM"<?php if($cc_ampm=="PM") echo " selected";?>>PM</option>
</select>
&nbsp;
<select name="cc_zone">
<option value="EST"<?php if($cc_zone=="EST") echo " selected";?>>Eastern</option>
<option value="CST"<?php if($cc_zone=="CST") echo " selected";?>>Central</option>
<option value="MST"<?php if($cc_zone=="MST") echo " selected";?>>Mountain</option>
<option value="PST"<?php if($cc_zone=="PST") echo " selected";?>>Pacific</option>
</select>
</td>
</tr>
<?php if($act_id != "new"){ ?>
<tr>
<td colspan="2" class="error">
Notice: The following users and guests are invited to this conference call:<br>
Guests:<?=$guests?><br>
Guests Emails: <?=$guests_email?><br>
Users:<?=$users?><br>
Users Emails: <?=$users_email?><br>
<input type="radio" name="keep" value="1" checked="checked"> Keep existing users and guests &nbsp; &nbsp; &nbsp; 
<input type="radio" name="keep" value="0"> Create new list of users and guests below
<input type="hidden" name="keep_guests" value="<?=$guests?>">
<input type="hidden" name="keep_guests_email" value="<?=$guests_email?>">
<input type="hidden" name="keep_users" value="<?=$users?>">
<input type="hidden" name="keep_users_email" value="<?=$users_email?>">
</td>
</tr>
<?php } ?>
<tr>
<td align="right" valign="top">Users</td>
<td>
  <table class="main">
  <tr>
  <?php
  $counter=0;
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    if($counter==0) echo "<td valign='top'>\n";
    ?>
    <input type="checkbox" name="users[]" value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
    <?php
	$counter++;
	if($counter==5){
	  echo "</td>\n";
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
guestList("", $prospect_id, $property_id);
?>
</td>
</tr>

<tr>
<td align="right">Call In Number</td>
<td><input type="text" name="phone_number" value="<?=$phone_number?>" size="40" maxlength="30"></td>
</tr>
<tr>
<td align="right">Call In Code</td>
<td><input type="text" name="phone_code" value="<?=$phone_code?>" size="40" maxlength="20"></td>
</tr>
<tr>
<td colspan="2">
<input type="checkbox" name="sendemail" value="1" checked="checked">Generate Email Confirmation regarding:<br>
<select name="claim_type">
<option value="Manville"<?php if($claim_type=="Manville") echo " selected";?>>Manville</option>
<option value="Beazer"<?php if($claim_type=="Beazer") echo " selected";?>>Beazer</option>
<option value="Non-PFRI"<?php if($claim_type=="Non-PFRI") echo " selected";?>>Non-PFRI</option>
</select>
</td>
</tr>
</table>
<?php
$html = ob_get_contents();
  ob_end_clean();
  break;
}

//***********************************************************   GET BIDS *******************************************************************
  case "Get Bids":{
    ob_start();
	?>
	<table class="main">
<tr>
<td align="right">Section</td>
<td>

<div id="txtResult">
<?php if($property_id){
  $sql = "SELECT * from sections where property_id='$property_id' and display=1 order by section_name";
  $result = executequery($sql);

  $xhtml = "<select name=\"bid_section_id\" onchange=\"ajax_do_bid_sectionswitch(this.value)\">";

  while($record = go_fetch_array($result)){
    $sql = "SELECT bid_id from sections_bidinfo where section_id='" . $record['section_id'] . "' and is_oldbid=0";
	$x_bid_id = getsingleresult($sql);
	$x_bid_display = "";
	if($x_bid_id) $x_bid_display = " (Bid #$x_bid_id)";
    $xhtml .= "<option value=\"" . $record['section_id'] . "\">" . stripslashes($record['section_name']) . $x_bid_display . "</option>";

  }
  $xhtml .= "</select>";
  echo $xhtml;
}
?>
</div>
</td>
</tr>

<tr>
<td align="right">Bid Due Date</td>
<td>

<input size="10" type="text" name="bid_date_pretty"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('bid_date_pretty',0)" align="absmiddle">
</td>
</tr>

<tr>
<td align="right" valign="top">Users</td>
<td>

  <table class="main">
  <tr>
  <?php
  $counter=0;
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    if($counter==0) echo "<td valign='top'>\n";
    ?>
    <input type="checkbox" name="bid_users[]" value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
    <?php
	$counter++;
	if($counter==5){
	  echo "</td>\n";
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
guestList("bid", $prospect_id, $property_id);
?>
</td>
</tr>

<tr>
<td align="right" valign="top">Contractors</td>
<td>
<a href="javascript:SetChecked(1,'bid_contractors[]')">Check All</a> &nbsp; &nbsp;
<a href="javascript:SetChecked(0,'bid_contractors[]')">Uncheck All</a>
<div style="width:100%; height:300px; overflow:auto;" id="bid_contractorlist">
<?php if($property_id){
  $sql = "SELECT section_type, section_id from sections where property_id='$property_id' and display=1 order by section_name limit 1";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $section_type = $record['section_type'];
  $x_section_id = $record['section_id'];
  $sql = "SELECT rebid_cont_ids from sections_bidinfo where section_id='$x_section_id' and is_oldbid=0";
  $rebid_cont_ids = getsingleresult($sql);
  
  $sql = "SELECT state, corporate from properties where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $state = $record['state'];
  $corporate = $record['corporate'];
  if($corporate) $state = "XXXX";
  if($property_id == "") $state = "XXXX";

  $sql = "SELECT cont_id, company_name, work_area_notes from contractors where work_area_states like '%$state%' 
  and contractor_section_type='$section_type' order by company_name";
  $result = executequery($sql);
  $xhtml = "<table width=\"100%\" class=\"main\" cellpadding=\"2\">";
  $xhtml .= "<tr><td></td><td><strong>Contractor</strong></td><td><strong>Work Area Notes</strong></td></tr>";
  $counter = 0;
  while($record = go_fetch_array($result)){
  $sql_active = "SELECT contractor_status FROM prospects WHERE cont_id = ". $record['cont_id'];
  $result_active = executequery($sql_active);
  $rec_active = go_fetch_array($result_active);
  $active_stat = $rec_active['contractor_status'];

  if($active_stat != "1" && $active_stat != "2" && $active_stat != "9")
  {
    $xhtml .= "<tr><td valign=\"middle\">";
    $xhtml .= "<input type=\"checkbox\" name=\"bid_contractors[]\" value=\"" . $record['cont_id'] . "\"";
	if(go_reg("," . $record['cont_id'] . ",", $rebid_cont_ids)) $xhtml .= " checked";
	$xhtml .= "></td>";
    $xhtml .= "<td valign=\"middle\">" . stripslashes(go_reg_replace("\'", "&#39", $record['company_name']));
    $xhtml .= "</td><td valign=\"middle\">" . stripslashes(go_reg_replace("\'", "&#39", $record['work_area_notes'])) . "</td></tr>";
  
    $counter++;
  }
  }
  if($counter == 0) $xhtml .= "<tr><td colspan=\"3\">No contractors found working in state: $state for type: $section_type</td></tr>";

  $xhtml .= "</table>";

  if($state == "XXXX"){
    $xhtml = "Please select a property location.";
  }
  echo $xhtml;
}
?>
</div>
</td>
</tr>

</table>
<?php
  $html = ob_get_contents();
  ob_end_clean();
  break;
}


//*****************************************************************************   OPM   ***********************************************

  case "OPM":{
    ob_start();
	?>
	<table class="main">
<tr>
<td align="right">Company Header</td>
<td>
<select name="opm_logo_id">
<option value="2">FCS</option>
<option value="1">RoofOptions</option>
<option value="3">PhenCon</option>
</select>
</td>
</tr>
<tr>
<td align="right">Section</td>
<td>

<div id="opm_section_div">
<?php if($property_id){
  $sql = "SELECT * from sections where property_id='$property_id' and display=1 order by section_name";
  $result = executequery($sql);

  $xhtml = "<select name=\"opm_section_id\" onchange=\"ajax_doOPM('activity_opm_pop_contractor.php', 'section_id='+this.value)\">";

  while($record = go_fetch_array($result)){
    $xhtml .= "<option value=\"" . $record['section_id'] . "\">" . stripslashes($record['section_name']) . "</option>";

  }
  $xhtml .= "</select>";
  echo $xhtml;
}
?>
</div>
</td>
</tr>

<tr>
<td align="right">Bid Number</td>
<td>
<?php
$sql = "SELECT section_id from sections where property_id='$property_id' and display=1 order by section_name limit 1";
$test_opm_section_id = getsingleresult($sql);
$sql = "SELECT bid_id from sections_bidinfo where section_id='$test_opm_section_id' and is_oldbid=0";
$test_bid_id = getsingleresult($sql);
?>
<div id="opm_bidnumber_div">
<input size="10" type="text" name="opm_bid_number" value="<?=$test_bid_id?>"> 
</div>
</td>
</tr>
<tr>
<td colspan="2">Email To</td>
</tr>
<tr>
<td align="right" valign="top">Bcc</td>
<td>
<a href="javascript:SetChecked(1,'opm_users[]')">Check All</a> &nbsp; &nbsp;
<a href="javascript:SetChecked(0,'opm_users[]')">Uncheck All</a>
  <table class="main">
  <tr>
  <?php $counter = 0; ?>
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname, default_check_opm from users where enabled=1 and show_opm=1 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($counter==0) echo "<td valign='top'>\n";
  ?>
  <input type="checkbox" name="opm_users[]" value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id || $record['default_check_opm']==1) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
  <?php
  $counter++;
  if($counter==5){
    echo "</td>\n";
	$counter = 0;
  }
}
?>
  </tr>
  </table>
</td>
</tr>

<tr>
<td align="right" valign="top">To</td>
<td>
<?php
guestList("opm", $prospect_id, $property_id);
?>

<div id="opm_extraemail_div">
<?php
if(is_array($_SESSION['ro_opm_extraemail'])){
  for($x=0;$x<sizeof($_SESSION['ro_opm_extraemail']);$x++){
    ?>
	<a href="javascript:OPM_del_extraemail('<?=$_SESSION['ro_opm_extraemail'][$x]?>')" style="color:#FF0000;">X</a> 
	<?=$_SESSION['ro_opm_extraemail'][$x]?>
	<br>
	<?php
  }
}

?>
</div>
<input type="text" name="opm_extraemail_add">
<a href="javascript:OPM_add_extraemail()">add</a>
</td>
</tr>

<tr>
<td align="right" valign="top">Contractor</td>
<td>
<div id="opm_contractor_div">
<select name="opm_contractor">
<option value="0">FCS</option>
<?php

$sql = "SELECT winning_cont_id from sections_bidinfo where section_id='$test_opm_section_id' and is_oldbid=0";
$opm_winning_cont_id = getsingleresult($sql);

$sql = "SELECT cont_id, company_name from contractors order by company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['cont_id']?>"<?php if($opm_winning_cont_id==$record['cont_id']) echo " selected";?>><?=stripslashes($record['company_name'])?></option>
  <?php
}
?>
</select>
</div>
<input type="checkbox" name="managed_by_ro" value="1"<?php if($managed_by_ro) echo " checked";?>>Managed by RoofOptions
</td>
</tr>
<tr>
<td>Contract Amount:</td>
<td>$<input type="text" name="opm_roofing_contract"></td>
</tr>
<tr>
<td>Sq/Ft of Project:</td>
<td id="opm_sqft_div"><input type="text" name="opm_sqft"></td>
</tr>
<tr>
<td valign="top">OPM Approval Notifications</td>
<td>
<?php
$sql = "SELECT email, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and allow_opm_approve=1 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="opm_notification_id[]" value="<?=$record['email']?>"><?=stripslashes($record['fullname'])?><br>
  <?php
}
?>
</td>
</tr>
<tr>
<td colspan="2">
<input type="checkbox" name="opm_show_extended" value="1" checked="checked"> Show Extended column on Cost Analysis
<br>
<input type="checkbox" name="opm_show_actual" value="1" checked="checked"> Show Actual column on Cost Analysis
<br>
<input type="checkbox" name="opm_show_projected" value="1" checked="checked"> Show Projected column on Cost Analysis
</td>
</tr>
<tr>
<td colspan="2">
Required Entry Fields
<?php
$sql = "SELECT property_type from sections_bidinfo where section_id = '$test_opm_section_id' and is_oldbid=0";
$test_property_type = getsingleresult($sql);
if($test_property_type=="Manville" || $test_property_type=="Beazer" || $test_property_type=="Beazer B") $forcecheck = 1;
//$forcecheck=1;
?>
<div id="opm_entry_div">
<input type="checkbox" name="check_1" value="1" checked="checked" readonly="true">
<input type="text" maxlength="255" size="40" name="desc_1" value="Roofing Removed" readonly="true">
<select name="unit_1" readonly="true">
<option value="Gallons">Gallons</option>
<option value="Sq/Ft" selected="selected">Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_1" size="4">
Projected % <input type="text" name="projected_1" size="2">

<br>

<input type="checkbox" name="check_2" value="1"<?php if($forcecheck) echo " checked";?>>
<input type="text" maxlength="255" size="40" name="desc_2" value="<?php if($forcecheck) echo "Decking Removed and Replaced";?>">
<select name="unit_2">
<option value="Gallons">Gallons</option>
<option value="Sq/Ft"<?php if($forcecheck) echo " selected";?>>Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_2" size="4">
Projected % <input type="text" name="projected_2" size="2">

<br>

<input type="checkbox" name="check_3" value="1"<?php if($forcecheck) echo " checked";?>>
<input type="text" maxlength="255" size="40" name="desc_3" value="<?php if($forcecheck) echo "Decking Overlaid";?>">
<select name="unit_3">
<option value="Gallons">Gallons</option>
<option value="Sq/Ft"<?php if($forcecheck) echo " selected";?>>Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_3" size="4">
Projected % <input type="text" name="projected_3" size="2">

<br>

<input type="checkbox" name="check_4" value="1"<?php if($forcecheck) echo " checked";?>>
<input type="text" maxlength="255" size="40" name="desc_4" value="<?php if($forcecheck) echo "Decking Prepared and Coated";?>">
<select name="unit_4">
<option value="Gallons">Gallons</option>
<option value="Sq/Ft"<?php if($forcecheck) echo " selected";?>>Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_4" size="4">
Projected % <input type="text" name="projected_4" size="2">

<br>

<input type="checkbox" name="check_5" value="1"<?php if($forcecheck) echo " checked";?>>
<input type="text" maxlength="255" size="40" name="desc_5" value="<?php if($forcecheck) echo "Deck No Coating";?>">
<select name="unit_5">
<option value="Gallons">Gallons</option>
<option value="Sq/Ft"<?php if($forcecheck) echo " selected";?>>Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_5" size="4">
Projected % <input type="text" name="projected_5" size="2">

<br>

<input type="checkbox" name="check_6" value="1"<?php if($forcecheck) echo " checked";?>>
<input type="text" maxlength="255" size="40" name="desc_6" value="<?php if($forcecheck) echo "Paint Used";?>">
<select name="unit_6">
<option value="Gallons"<?php if($forcecheck) echo " selected";?>>Gallons</option>
<option value="Sq/Ft">Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_6" size="4">
Projected % <input type="text" name="projected_6" size="2">

<br>

<input type="checkbox" name="check_7" value="1">
<input type="text" maxlength="255" size="40" name="desc_7" value="">
<select name="unit_7">
<option value="Gallons">Gallons</option>
<option value="Sq/Ft">Sq/Ft</option>
</select>
Unit Cost: <input type="text" name="unitcost_7" size="4">
Projected % <input type="text" name="projected_7" size="2">

</div>
</td>
</tr>

<tr>
<td colspan="2">
Required Photo Fields
<div id="opm_photo_div">
<input type="checkbox" name="check_photo1" value="1"<?php if($forcecheck) echo " checked"; ?>>
<input type="text" maxlength="255" size="40" name="photo_desc1" value="<?php if($forcecheck) echo "General Overview";?>">
<br>

<input type="checkbox" name="check_photo2" value="1"<?php if($forcecheck) echo " checked"; ?>>
<input type="text" maxlength="255" size="40" name="photo_desc2" value="<?php if($forcecheck) echo "Deckwork Overview";?>">
<br>

<input type="checkbox" name="check_photo3" value="1"<?php if($forcecheck) echo " checked"; ?>>
<input type="text" maxlength="255" size="40" name="photo_desc3" value="<?php if($forcecheck) echo "Paint Overview";?>">
<br>

<input type="checkbox" name="check_photo4" value="1">
<input type="text" maxlength="255" size="40" name="photo_desc4" value="">
</div>
(additional, non-required, photos can be uploaded)
</td>
</tr>

</table>
<?php
  $html = ob_get_contents();
  ob_end_clean();
  break;
}

//*******************************************************************    BID PRESENTATION *******************************************************

  case "Bid Presentation":{
    ob_start();
	?>
	<?php
if($act_id != "new"){
  $sql = "SELECT bp_value from activities where act_id='$act_id'";
  $bp_value = getsingleresult($sql);
}
?>
<table class="main">
<tr>
<td align="right">Value</td>
<td>
$<input type="text" name="bp_value" value="<?=$bp_value?>">
</td>
</tr>
</table>
<?php
  $html = ob_get_contents();
  ob_end_clean();
  break;
}

//*******************************************************************   SEND LETTER  ***********************************************************

  case "Send Letter":{
    ob_start();
	?>
	<?php
if($property_id){
  $sql = "SELECT roof_size from properties where property_id='$property_id'";
  $x_roof_size = getsingleresult($sql);
  $x_roof_size = preg_replace('/\D/', '', $x_roof_size);
  $sql = "SELECT roof_settlement, ip_settlement, paint, overlay, remove_replace from properties_beazer where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $rs = preg_replace('/\D/', '', $record['roof_settlement']);
  $ips = preg_replace('/\D/', '', $record['ip_settlement']);
  $p = preg_replace('/\D/', '', $record['paint']);
  $ov = preg_replace('/\D/', '', $record['overlay']);
  $rr = preg_replace('/\D/', '', $record['remove_replace']);
  
  $sendletter_squares = $x_roof_size;
  $sendletter_roofsettlement = $rs;
  $sendletter_intprotection = $ips;
  $sendletter_deckwork = ($p * $x_roof_size) + ($ov * $x_roof_size) + ($rr * $x_roof_size);
}
?>
<table class="main">
<tr>
<td align="right">Type</td>
<td>

<select name="sendletter_type">
<option value="gilman1">Gilman 1</option>
</select>
</td>
</tr>

<tr>
<td align="right" valign="top">Users</td>
<td>
  <table class="main">
  <tr>
  <?php
  $counter=0;
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    if($counter==0) echo "<td valign='top'>\n";
    ?>
    <input type="checkbox" name="sendletter_users[]" value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " checked";?>><?=stripslashes($record['fullname'])?><br>
    <?php
	$counter++;
	if($counter==5){
	  echo "</td>\n";
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
$selected = $_GET['selected'];
guestList_address("sendletter", $prospect_id, $property_id, $selected);
?>
</td>
</tr>

</table>
<div id="sendletter_gilman1">
<table class="main">
<tr>
<td align="right">Squares</td>
<td><input type="text" name="sendletter_gilman1_squares" value="<?=$sendletter_squares?>" readonly="true"></td>
</tr>
<tr>
<td align="right">Roof Settlement</td>
<td>$<input type="text" name="sendletter_gilman1_roofsettlement" value="<?=$sendletter_roofsettlement?>" readonly="true"></td>
</tr>
<tr>
<td align="right">Deck Work</td>
<td>$<input type="text" name="sendletter_gilman1_deckwork" value="<?=$sendletter_deckwork?>"></td>
</tr>
<tr>
<td align="right">Int Protection</td>
<td>$<input type="text" name="sendletter_gilman1_intprotection" value="<?=$sendletter_intprotection?>" readonly="true"></td>
</tr>
</table>
</div>
<?php
   $html = ob_get_contents();
   ob_end_clean();
   break;
}

//***************************************************************   OPERATIONS  ***********************************************

  case "Operations":{
    ob_start();
	?>
	<?php
if($act_id != "new"){
  $sql = "SELECT operations_schedule, operations_crew, date_format(operations_start, \"%m/%d/%Y\") as operations_start_pretty, 
  date_format(operations_finish, \"%m/%d/%Y\") as operations_finish_pretty, user_id, regarding
  from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $operations_schedule = stripslashes($record['operations_schedule']);
  $operations_crew = stripslashes($record['operations_crew']);
  $operations_start_pretty = stripslashes($record['operations_start_pretty']);
  $operations_finish_pretty = stripslashes($record['operations_finish_pretty']);
  $operations_user_id = $record['user_id'];
  $operations_regarding = stripslashes($record['regarding']);
}
?>
  
<table class="main">
<tr>
<td align="right">Manager</td>
<td>
<select name="operations_user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and project_manager=1 order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$operations_user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>

</select>
</td>
</tr>
<tr>
<td align="right">Schedule</td>
<td>

<select name="operations_schedule">
<option value="Projected"<?php if($operations_schedule=="Projected") echo " selected";?>>Projected</option>
<option value="Scheduled"<?php if($operations_schedule=="Scheduled") echo " selected";?>>Scheduled</option>
<option value="In Production"<?php if($operations_schedule=="In Production") echo " selected";?>>In Production</option>
<option value="Waiting for Mfg Inspection"<?php if($operations_schedule=="Waiting for Mfg Inspection") echo " selected";?>>Waiting for Mfg Inspection</option>
<option value="Waiting for Warranty"<?php if($operations_schedule=="Waiting for Warranty") echo " selected";?>>Waiting for Warranty</option>
</select>
</td>
</tr>

<tr>
<td align="right">Crew</td>
<td>

<select name="operations_crew">
<option value=""></option>
<option value="Crew 1"<?php if($operations_crew=="Crew 1") echo " selected";?>><?=$CREW1?></option>
<option value="Crew 2"<?php if($operations_crew=="Crew 2") echo " selected";?>><?=$CREW2?></option>
<option value="Crew 3"<?php if($operations_crew=="Crew 3") echo " selected";?>><?=$CREW3?></option>
</select>
</td>
</tr>

<tr>
<td align="right">Start Date</td>
<td>
<input size="10" type="text" name="operations_start_pretty"  value="<?=$operations_start_pretty?>"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('operations_start_pretty',0)" align="absmiddle">
</td>
</tr>

<tr>
<td align="right">Finish Date</td>
<td>
<input size="10" type="text" name="operations_finish_pretty"  value="<?=$operations_finish_pretty?>"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('operations_finish_pretty',0)" align="absmiddle">
</td>
</tr>

<tr>
<td align="right">Regarding</td>
<td>
<input type="text" name="operations_regarding"  value="<?=$operations_regarding?>" maxlength="250"> 
</td>
</tr>

</table>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  break;
}

//*****************************************************************   PRODUCTION MEETING  *********************************************************

  case "Production Meeting":{
    ob_start();
	?>
	<?php
if($act_id != "new"){
  $sql = "SELECT productionmeeting_email, productionmeeting_bid_id,
  date_format(date, \"%m/%d/%Y\") as datepretty,
  date_format(date, \"%h\") as hourpretty, 
  date_format(date, \"%i\") as minutepretty, 
  date_format(date, \"%p\") as ampm
  from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $pm_datepretty = stripslashes($record['datepretty']);
  $pm_hourpretty = stripslashes($record['hourpretty']);
  $pm_minutepretty = stripslashes($record['minutepretty']);
  $pm_ampm = stripslashes($record['ampm']);
  $productionmeeting_email = stripslashes($record['productionmeeting_email']);
  $productionmeeting_bid_id = stripslashes($record['productionmeeting_bid_id']);
}
else {
  $pm_hourpretty = "00";
  $pm_minutepretty = "00";
  $pm_ampm = "AM";
}
?>
  
<table class="main">
<tr>
<td>Bid ID</td>
<td>
<select name="productionmeeting_bid_id">
<?php
$sql = "SELECT bid_id from sections_bidinfo where property_id='$property_id'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['bid_id']?>"<?php if($record['bid_id']==$productionmeeting_bid_id) echo " selected";?>><?=$record['bid_id']?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right" valign="middle">Date</td>
<td>
<input size="10" type="text" name="pm_datepretty" <?php if($pm_datepretty=="") { echo" value=\"" . date("m/d/Y", time() + (7 * 24 * 60 * 60)) . "\" onClick=\"cleardate(this);\""; } else {?> value="<?=$pm_datepretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('pm_datepretty',0)" align="absmiddle">
<br>
<input type="text" name="pm_hourpretty" value="<?=$pm_hourpretty?>" size="2">:
<input type="text" name="pm_minutepretty" value="<?=$pm_minutepretty?>" size="2">
<select name="pm_ampm">
<option value="AM"<?php if($pm_ampm=="AM") echo " selected";?>>AM</option>
<option value="PM"<?php if($pm_ampm=="PM") echo " selected";?>>PM</option>
</select>
</td>
</tr>
<tr>
<td align="right" valign="top">With</td>
<td>
<?php
$thelist = GetContacts("", $prospect_id, 1);
for($x=0;$x<sizeof($thelist);$x++){
  ?>
  <input type="checkbox" name="productionmeeting_email[]" value="<?=$thelist[$x]['email']?>"<?php if(go_reg($thelist[$x]['email'], $productionmeeting_email)) echo " checked";?>><?=$thelist[$x]['name']?><br>
  <?php
}
?>

</td>
</tr>
<tr>
<td colspan="2">
<input type="button" name="button_pm_email_preview" value="Email Preview" onclick="pm_email_preview()">
<input type="checkbox" name="pm_sendemail" value="1" checked="checked">
Send Email
</td>
</tr>



</table>
<?php
  $html = ob_get_contents();
  ob_end_clean();
  break;
}


} // end switch

$html = jsclean($html);
  
  
?>

div = document.getElementById('special_item');
div.innerHTML = '<?php echo $html; ?>';