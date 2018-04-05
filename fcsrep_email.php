<?php
include "includes/header.php";

$ids = $_POST['ids'];
$list_type = $_POST['list_type'];

$_SESSION['sess_ids'] = $ids;
$_SESSION['sess_list_type'] = $list_type;

$action = $_POST['action'];
if($action=="mapit"){
  $_SESSION['map_generic_property_id'] = $_POST['ids'];
  meta_redirect("map_generic.php");
}

$submit1 = $_POST['submit1'];
if($submit1=="Add Candidate") {
  if($list_type=="property") meta_redirect("frame_user_prospecting_mass.php");
  if($list_type=="prospect") meta_redirect("frame_user_prospecting_mass_byprospect.php");
}

if($list_type=="property"){
  $email_list = array();
  for($x=0;$x<sizeof($ids);$x++){
    $property_id = $ids[$x];
	$email_list = array_merge_recursive($email_list, PropertyEmails($property_id));
	$sql = "SELECT prospect_id from properties where property_id='$property_id'";
	$prospect_id = getsingleresult($sql);
	$email_list = array_merge_recursive($email_list, ProspectEmails($prospect_id));
  }
}

if($list_type=="prospect"){
  $email_list = array();
  for($x=0;$x<sizeof($ids);$x++){
    $prospect_id = $ids[$x];
	$email_list = array_merge_recursive($email_list, ProspectEmails($prospect_id));
  }
}

if($list_type=="contact"){
  $email_list = array();
  for($x=0;$x<sizeof($ids);$x++){
    $sql = "SELECT email from contacts where id='" . $ids[$x] . "'";
	$x_email = stripslashes(getsingleresult($sql));
	if($x_email != "") $email_list[] = $x_email;
  }
}

$email_list = array_unique($email_list);
$email_list = array_values($email_list);
$_SESSION['sess_email_list'] = $email_list;
/*
for($x=0;$x<sizeof($email_list);$x++){
  echo $email_list[$x] . "<br>";
}
*/
?>
<div align="center">
  <div class="whiteround" style="height:800px; overflow:auto;" align="left">

<script>

function email_preview_message(){

  
  document.emailpreview.email_message.value = document.form1.message.value;
  document.emailpreview.user_id.value = 1;
  document.emailpreview.email_company_logo.value = document.form1.email_company_logo.value;
  document.emailpreview.email_footer.value = document.form1.email_footer.value;
  
  
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



function checkform(f){
  errmsg = "";
  
	  if(f.subject.value==""){ errmsg += "Please enter subject. \n"; }
	  if(f.email_message.value==""){ errmsg += "Please enter message. \n"; }
  
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}
</script>

<form action="activity_email_preview.php" method="post" name="emailpreview" target="_blank">
<input type="hidden" name="email_message" value="">
<input type="hidden" name="user_id" value="">
<input type="hidden" name="email_html" value="0">
<input type="hidden" name="email_include_signature" value="0">
<input type="hidden" name="email_company_logo" value="">
<input type="hidden" name="email_footer" value="">
<input type="hidden" name="email_banner" value="">
<input type="hidden" name="email_movie" value="">
<div style="display:none;"><input type="submit" name="submit1"></div>
</form>

<form action="fcsrep_email_action.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkform(this)">
<input type="hidden" name="email_html" id="email_html" value="0">
<table class="main">
<tr>
<td align="right" valign="top"><strong>Bcc</strong></td>
<td>
  <table class="main">
  <tr>
<?php
$counter = 0;
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname, firstname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  if($counter==0) echo "<td valign='top'>\n";
  ?>
  <input type="checkbox" name="users[]" value="<?=$record['user_id']?>"><?=stripslashes($record['fullname'])?><br>
  <?php
  $counter++;
  if($counter==5){
    echo "</td>\n";
	$counter = 0;
  }
}
?>
  <td valign="top">
<?php if($list_type != ""){ ?>
<input type="checkbox" name="selected" value="1" checked="checked">Selected Records<br>
<?php } ?>
  </td>
  </tr>
  </table>
</td>
</tr>

</table>
<div id="emailstuff">
<table class="main">

<tr>
<td align="right">
<strong>Subject:</strong>
</td>
<td>
<input type="text" name="subject" size="60">
</td>
</tr>
<tr>
<td valign="top" align="right"><strong>Message:</strong>
<?php /*
<br>
<input type="button" name="buttonpreview" value="Preview" onClick="email_preview_message()">
*/?>
</td>
<td>

<textarea name="message" rows="10" cols="80" id="email_message"></textarea>

</td>
</tr>
<tr>
<td colspan="2">
  <div style="position:relative;">
  <div style="float:left;" align="center">
  <input type="checkbox" name="include_evolution" value="1"><br>
  Evolution of the Roofing Industry<br>
  <img src="<?=$CORE_URL?>uploaded_files/videos/evolution/evolution_vid.png">
  </div>
  <div style="float:left;" align="center">
  <input type="checkbox" name="include_service" value="1"><br>
  FCSCore Service Technologies<br>
  <img src="<?=$CORE_URL?>uploaded_files/videos/fcscore_tech/fcscore_tech_vid.png">
  </div>
  </div>
</td>
</tr>
<tr>
<td valign="top" align="right">Attach Brochures/Order Form</td>
<td>
<select name="attachbrochure">
<option value="0">None</option>
<option value="sample_roof_report.pdf">Roof Report</option>
<option value="sales_packet.pdf">Sales Packet</option>
<option value="contractor_marketing.pdf">Contractor Marketing</option>
<option value="order_form.pdf">Order Form</option>
</select>
</td>
</tr>
  
<tr>
<td colspan="2"><input type="checkbox" name="email_include_signature" value="1">Include Signature</td>
</tr>
</table>
</div>

<input type="submit" name="submit1" value="Send">

</form>

  </div>
</div>
<?php include "includes/footer.php"; ?>