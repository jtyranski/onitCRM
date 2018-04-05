<?php include "includes/functions.php";?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
/*
$code = $_SERVER['QUERY_STRING'];

$sql = "SELECT id from document_proposal_fcs where code='$code'";
$doc_id = getsingleresult($sql);

if($doc_id == ""){
  echo "There was an error accessing your document.  Please check the link in your email and try again.";
  exit;
}
*/
$doc_id = $_GET['doc_id'];

$sql = "SELECT check1, check2, name, email, def_selected, section_id, subtotal, total, intro_credit, multisection, 
date_format(sign_date, \"%m/%d/%Y %r\") as sign_date_pretty, property_id, user_id, 
date_format(proposal_date, \"%m/%d/%Y\") as proposal_date_pretty 
from document_proposal_fcs where id='$doc_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$check1 = stripslashes($record['check1']);
$check2 = stripslashes($record['check2']);
$name = stripslashes($record['name']);
$email = stripslashes($record['email']);
$def_selected = stripslashes($record['def_selected']);
$sign_date_pretty = stripslashes($record['sign_date_pretty']);
$section_id = stripslashes($record['section_id']);
$subtotal = stripslashes($record['subtotal']);
$total = stripslashes($record['total']);
$intro_credit = stripslashes($record['intro_credit']);
$multisection = stripslashes($record['multisection']);
$property_id = stripslashes($record['property_id']);
$ro_user_id = stripslashes($record['user_id']);
$proposal_date_pretty = stripslashes($record['proposal_date_pretty']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='$ro_user_id'";
$serviceman = stripslashes(getsingleresult($sql));

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name, address, city, state, zip, logo, master_id from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company['name'] = stripslashes($record['company_name']);
$company['logo'] = stripslashes($record['logo']);
$company['master_id'] = stripslashes($record['master_id']);

$sql = "SELECT logo, master_name, payment_terms from master_list where master_id='" . $company['master_id'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$master['master_name'] = stripslashes($record['master_name']);
$master['master_logo'] = stripslashes($record['logo']);
$master['payment_terms'] = stripslashes($record['payment_terms']);


$sql = "SELECT site_name, address, city, state, zip, roof_size from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property['site_name'] = stripslashes($record['site_name']);
$property['address'] = stripslashes($record['address']);
$property['city'] = stripslashes($record['city']);
$property['state'] = stripslashes($record['state']);
$property['zip'] = stripslashes($record['zip']);
$property['roof_size'] = stripslashes($record['roof_size']);

$sitepoints = 0;
$sitesections = 0;
$sql = "SELECT sqft, grade, roof_type from sections where property_id='$property_id' and display=1 and section_type='$section_type'";
$res_sec = executequery($sql);
while($sections = go_fetch_array($res_sec)){
  if($sections['grade'] != "0") $sitesections++;
  $property['sqft'] += $sections['sqft'];
  $grade = $sections['grade'];
  $this_points = $gradevalue[$grade];
  $sitepoints += $this_points;
}
$siteavg = round($sitepoints / $sitesections);
$property['grade'] = $gradevalue_reverse[$siteavg];
  
if($property['sqft'] == 0 || $property['sqft']=="") $property['sqft'] = $property['roof_size'] * 100;


if($company['logo'] != ""){ 
  $max_width = 150;
  $max_height = 100;
  list($width, $height) = getimagesize("uploaded_files/logos/" . $company['logo']);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }
  
  $company_logo = "<img src=\"" . $SITE_URL . "uploaded_files/logos/" . $company['logo'] . "\" width=\"" . $width . "\" height=\"" . $height . "\">";
} else { 
  $company_logo = "<div class=\"main_large\">" . $company['name'] . "</div>";
}

?>
<script>
function formatCurrency(num) {
num = num.toString().replace(/\$|\,/g,'');
if(isNaN(num))
num = "0";
sign = (num == (num = Math.abs(num)));
num = Math.floor(num*100+0.50000000001);
cents = num%100;
num = Math.floor(num/100).toString();
if(cents<10)
cents = "0" + cents;
for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
num = num.substring(0,num.length-(4*i+3))+','+
num.substring(num.length-(4*i+3));
return (((sign)?'':'-') + num + '.' + cents);
}

function Calc(id, x){
  x = parseFloat(x);
  x = Math.round(x*100)/100
  subtotal = parseFloat(document.proposal.subtotal.value);
  subtotal = Math.round(subtotal*100)/100
  if(id.checked==true){
    subtotal = subtotal + x;
  }
  else {
    subtotal = subtotal - x;
  }
  if(subtotal < 0) subtotal = 0;
  
  total = subtotal - <?=$intro_credit?>;
  if(total < 0) total = 0;
  
  total = Math.round(total*100)/100
  
  document.proposal.subtotal.value = subtotal;
  document.proposal.total.value = total;
  
  document.getElementById('totaldisplay').innerHTML = "$" + formatCurrency(subtotal);
  document.getElementById('discounttotal').innerHTML = "$" + formatCurrency(total);
}

function check_firstbox(){
  var onecheck=0;
  if(document.proposal.check1.checked==true) {onecheck=1;}
  //if(document.proposal.check2.checked==true) {onecheck=1;}
  
  if(onecheck==1){
    document.proposal.send_unsigned.checked=false;
	document.proposal.send_unsigned.disabled=true;
  }
  else {
    document.proposal.send_unsigned.disabled=false;
  }
}

function check_unsigned(x){
  if(x.checked==true){
    document.proposal.check1.checked=false;
	document.proposal.check1.disabled=true;
	//document.proposal.check2.checked=false;
	//document.proposal.check2.disabled=true;
  }
  else {
    document.proposal.check1.disabled=false;
	//document.proposal.check2.disabled=false;
  }
}

function checkform(f){
  len = f.elements.length;
  var errmsg = "";
  
  var i=0;
  var foo=0;
  for( i=0 ; i<len ; i++) {
    if (f.elements[i].name=="def_id[]" && f.elements[i].checked==true) {
      foo = foo + 1;
    }
  }
  if(foo ==0) { errmsg += "You need to select at least one deficiency.\n"; }
  if(f.check1.checked==false && f.send_unsigned.checked==false) { errmsg += "Please select at least one option.\n";}
  if(f.check1.checked==true && f.name.value=="") { errmsg += "Please enter your name as a digital signature.\n";}
  if(f.email.value=="") { errmsg += "Please enter an email address to receive the form.\n";}
  
  if(errmsg == ""){
    return true;
  }
  else {
    alert(errmsg);
    return false;
  }

}

</script>

<form name="proposal" action="public_proposal_fcs_action.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="doc_id" value="<?=$doc_id?>">

<STYLE TYPE="text/css">
     P.breakhere {page-break-before: always}
	 .bluetype{
	   font-family:"Times New Roman", Times, serif;
	   color:#4B4994;
	 }
	 .jjb{
	   font-family:Arial, Helvetica, sans-serif;
	   color:#0000FF;
	   text-decoration:underline;
	 }
.main{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:12px;
  color:black;
}

.main_bigger{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:14px;
  color:black;
}

.main_small{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;
  color:black;
}

a.smaller:link{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;
  color:#0000FF;
  text-decoration:none;
}
a.smaller:hover{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;
  color:#0000FF;
  text-decoration:none;
}
a.smaller:visited{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;
  color:#0000FF;
  text-decoration:none;
}

.error{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:12px;
  color:red;
}

.main_large{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:16px;
  color:black;
  font-weight:bold;
  text-decoration:none;
}

.main_superlarge{
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:20px;
  color:black;
  font-weight:bold;
  text-decoration:none;
}
.mobi_checkbox{
  width:40px;
  height:40px;
}
</STYLE> 
<body>
<div style="font-family:Arial, Helvetica, sans-serif; width:850px;">

<table width="100%">
<tr>
<td>

</td>
<td align="right">
<?php if($MASTER_LOGO != ""){ ?>
<img src="<?=$SITE_URL?>uploaded_files/master_logos/<?=$MASTER_LOGO?>">
<?php } ?>
</td>
</tr>
</table>
<div>Proposal #<?=$doc_id?></div>
<table class="main" width="100%">
<tr>
<td valign="middle">
<?=$company_logo?>
</td>
<td align="left" valign="middle"> <!-- changed align to left (JT) -->
<div style="float:right;">  <!-- moves to right side of page (JT) -->
<strong>
<?=$property['site_name']?><br>
<?=$property['address']?><br>
<?=$property['city']?>, <?=$property['state']?>
</strong>
</div>  <!-- ends DVI (JT) -->
</td>
</tr>
</table>

<br>
</div>
<?php
//***********************************************  start of multisection loop  ********************************************************

$multisection_array = explode(",", $multisection);
for($loop=0;$loop<sizeof($multisection_array);$loop++){
  $section_id = $multisection_array[$loop];
  //echo "<!-- YYY section_id=$section_id -->\n";
  if($section_id=="") continue;
  
$sql = "SELECT section_name, sqft, property_id, roof_type, grade, inspector, notes, main_photo, property_type, 
date_format(installation_date, \"%m/%d/%Y\") as installation_date_pretty, multiple, section_type, 
date_format(inspection_date, \"%m/%d/%Y\") as inspection_date_pretty 
from sections where section_id='$section_id'";
$result = executequery($sql);
$section = go_fetch_array($result);
$sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='" . $section['inspector'] . "'";
$section['inspector_name'] = stripslashes(getsingleresult($sql));
if($section['grade']==0) $section['grade'] = "";

$multiple = $section['multiple'];
$section_type = $section['section_type'];
$sql = "SELECT count(*) from opportunities where opp_stage_id=10 and property_id='$property_id' and display=1";
$active_rtm = getsingleresult($sql);



$section_report = "
<strong>Roof Section:</strong> [SECTION NAME]<br>
<strong>Roof Size:</strong> [SQFT] sqft<br>
<br>
<strong>Serviceman:</strong> [SERVICEMAN]<br>
<strong>Proposal Date:</strong> [PROPOSAL DATE]
";

$section_report = str_replace("[SECTION NAME]", stripslashes($section['section_name']), $section_report);
$section_report = str_replace("[SQFT]", stripslashes(number_format($section['sqft'], 0)), $section_report);
$section_report = str_replace("[ROOF TYPE]", stripslashes($section['roof_type']), $section_report);
$section_report = str_replace("[INSTALLATION DATE PRETTY]", stripslashes($section['installation_date_pretty']), $section_report);
$section_report = str_replace("[GRADE]", stripslashes($section['grade']), $section_report);
$section_report = str_replace("[INSPECTION DATE PRETTY]", stripslashes($section['inspection_date_pretty']), $section_report);
$section_report = str_replace("[INSPECTOR NAME]", stripslashes($section['inspector_name']), $section_report);
$section_report = str_replace("[SERVICEMAN]", stripslashes($serviceman), $section_report);
$section_report = str_replace("[PROPOSAL DATE]", stripslashes($proposal_date_pretty), $section_report);

$def_photo = "";
$def_name = "";
$def_foo = "";
$def_def = "";
$def_action = "";
$def_cost = "";
$def_bar = "";
$def_type = "";
$def_quantity = "";
$def_quantity_unit = "";

if($multiple == ""){
  $counter = 0;
  $sql = "SELECT *, date_format(date_recorded, \"%m/%d/%Y\") as daterec from sections_def where section_id='$section_id' and complete=0 order by def_id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $def_photo[$counter] = $record['photo'];
	$def_name[$counter] = stripslashes($record['name']);
	$def_foo[$counter] = stripslashes($record['name']);
	$def_def[$counter] = stripslashes(nl2br($record['def']));
	$def_action[$counter] = stripslashes(nl2br($record['action']));
	$def_cost[$counter] = $record['cost'];
	$def_bar[$counter] = $record['cost'];
	$def_type[$counter] = $record['def_type'];
	$def_quantity[$counter] = $record['quantity'];
	$def_quantity_unit[$counter] = $record['quantity_unit'];
	$def_id[$counter] = $record['def_id'];
	$def_daterec[$counter] = $record['daterec'];
	$counter++;
  }
}
else {
  $counter = 0;
  $multiple_array = explode(",", $multiple);
  for($x=0;$x<sizeof($multiple_array);$x++){
    $x_section_id = $multiple_array[$x];
    if($x_section_id == "") continue;
    $sql = "SELECT *, date_format(date_recorded, \"%m/%d/%Y\") as daterec from sections_def where section_id='$x_section_id' and complete=0 order by def_id";
    $result = executequery($sql);
    while($record = go_fetch_array($result)){
      $def_photo[$counter] = $record['photo'];
	  $def_foo[$counter] = stripslashes($record['name']);
	  $def_def[$counter] = stripslashes(nl2br($record['def']));
	  $def_action[$counter] = stripslashes(nl2br($record['action']));
	  $def_cost[$counter] = $record['cost'];
	  $def_bar[$counter] = $record['cost'];
	  $def_type[$counter] = $record['def_type'];
	  $def_quantity[$counter] = $record['quantity'];
	  $def_quantity_unit[$counter] = $record['quantity_unit'];
	  $def_id[$counter] = $record['def_id'];
	  $def_daterec[$counter] = $record['daterec'];
	  $counter++;
    }
  }
}

$def_table = "<table class=\"main\">";

$def_counter = 0;
for($x=0;$x<=sizeof($def_photo);$x++){
  if($def_id[$x] == "") continue;
  //if($def_cost[$x] == "" || $def_cost[$x]==0) continue;
  if($def_def[$x]=="") continue;
  $def_counter++;
  $checked = "";
  if(go_reg("," . $def_id[$x] . ",", $def_selected)) $checked = " checked";
  $def_table .= "  
  <tr>
  <td valign='top'>$def_counter<br>
  <input type='checkbox' class=\"mobi_checkbox\" name='def_id[]' value='" . $def_id[$x] . "' onclick=\"Calc(this, '" . $def_bar[$x] . "')\" $checked>
  </td>
  <td valign=\"top\">";
  if($def_photo[$x] != ""){
  $max_width = 300;
  $max_height = 200;
  list($width, $height) = getimagesize("uploaded_files/def/" . $def_photo[$x]);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }
  $def_table .= "
  <img src=\"" . $SITE_URL . "uploaded_files/def/" . $def_photo[$x] . "\" width=\"" . $width . "\" height=\"" . $height . "\">";
  }
  $def_table .= "
  </td>
  <td valign=\"top\">
  " . $def_foo[$x] . "";
  if($def_type[$x]=="R") {
    $def_table .= "(Remedial)";
  }
  else{
    $def_table .= "(Emergency)";
  }
  if($def_daterec[$x] != "00/00/0000") $def_table .= " - " . $def_daterec[$x];
  $def_table .= "<br>";
  if($def_quantity[$x] != ""){ 
    $def_table .= "Quantity: " . $def_quantity[$x] . " " . $def_quantity_unit[$x] . "<br><br>";
  }
  $def_table .= "Deficiency:";
  $def_table .= $def_def[$x] . "<br><br>
  Corrective Action:";
  $def_table .= $def_action[$x] . "<br><br>
  Estimated Repair Cost:
  $" . number_format($def_bar[$x], 2) . "<br><br>
  </td>
  </tr>";
  
}

$def_table .= "</table>";




if($section['main_photo'] != ""){

  $max_width = 440;
  $max_height = 250;
  list($width, $height) = getimagesize("uploaded_files/sections/" . $section['main_photo']);
  $ratioh = $max_height/$height;
  $ratiow = $max_width/$width;
  $ratio = min($ratioh, $ratiow);
  // New dimensions
  if($width > $max_width || $height > $max_height){
    $width = intval($ratio*$width);
    $height = intval($ratio*$height); 
  }

  $section_photo = "<img src=\"" . $SITE_URL . "uploaded_files/sections/" . $section['main_photo'] . "\" border=\"0\" width=\"" . $width . "\" height=\"" . $height . "\">";
  
}
?>

<?php

$sql = "SELECT body from templates where name='Proposal FCS 3.0'";
$body = stripslashes(getsingleresult($sql));

$body = str_replace("[SECTION REPORT]", stripslashes($section_report), $body);
$body = str_replace("[SECTION PHOTO]", $section_photo, $body);
$body = str_replace("[DEF TABLE]", $def_table, $body);
?>
<div class="main">
<?=$body?>
<br><br>

<?php } ?>
<?php //****************************************  end of multisection loop *****************************************************?>


<div style="width:100%; position:relative;">
<div style="width:50%; float:left;">
  <table class="main">
  <tr>
  <td valign="top"><input type="checkbox" name="check1" value="1"<?php if($check1) echo " checked";?> class="mobi_checkbox" onChange="check_firstbox()"></td>
  <td valign="top">
  <?=nl2br($master['payment_terms'])?>
  </td>
  </tr>

  <tr>
  <td colspan="2" align="center"><strong>OR</strong></td>
  </tr>
  <tr>
  <td valign="top">
  <input type="checkbox" name="send_unsigned" value="1" class="mobi_checkbox" onChange="check_unsigned(this)"></td>
  <td valign="top">Send unsigned copy of proposal</td>
  </tr>
  </table>

  
</div>
<div style="width:50%; float:right;">
  <table class="main">
  <tr<?php if($intro_credit == 0) echo " style=\"display:none;\""; ?>>
  <td align="right"><strong>Subtotal</strong></td>
  <td width="20">&nbsp;</td>
  <input type="hidden" name="subtotal" value="<?=$subtotal?>">
  <td align="right" id="totaldisplay">$<?=number_format($subtotal, 2)?></td>
  </tr>
  <?php if($intro_credit != 0){ ?>
  <tr>
  <td align="right"><strong>Credit</strong></td>
  <td></td>
  <td align="right" style="color:red;">$<?=number_format($intro_credit, 2)?></td>
  </tr>
  <?php } ?>
  <tr>
  <td align="right"><strong>Total</strong></td>
  <td></td>
  <input type="hidden" name="total" value="<?=$total?>">
  <td align="right" id="discounttotal">$<?=number_format($total, 2)?></td>
  </tr>
  </table>
  
</div>
</div>
<div style="clear:both;"></div>
  <table class="main" width="100%">
  <tr>
  <td align="right"><strong>Name</strong></td>
  <td><input type="text" name="name" value="<?=$name?>" size="35" maxlength="200" style="height:50px; font-size:18px;"></td>

  <td align="right"><strong>Email</strong></td>
  <td><input type="text" name="email" value="<?=$email?>" size="35" maxlength="200" style="height:50px; font-size:18px;"></td>
  <td><input type="submit" name="submit1" value="Submit" style="height:50px; width:110px; font-size:18px;"></td>
  </tr>
  
  
  </table>
  <?php if($email != ""){ ?>
  <br>
  <div class="main">Signed on <?=$sign_date_pretty?></div>
  <?php } ?>
 
</div>
</form>
