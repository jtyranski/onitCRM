<?php
include "includes/functions.php";

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
	if($property_id==0 || $property_id=="") continue;
	$email_list = array_merge_recursive($email_list, PropertyEmails($property_id));
	$sql = "SELECT prospect_id from properties where property_id='$property_id'";
	$prospect_id = getsingleresult($sql);
	if($prospect_id==0 || $prospect_id=="") continue;
	$email_list = array_merge_recursive($email_list, ProspectEmails($prospect_id));
  }
}

if($list_type=="prospect"){
  $email_list = array();
  for($x=0;$x<sizeof($ids);$x++){
    $prospect_id = $ids[$x];
	if($prospect_id==0 || $prospect_id=="") continue;
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
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<script src="fileuploader_attach/fileuploader.js" type="text/javascript"></script>
<script>
function SetChecked(x,chkName) {
  var form='form1'; //Give the form name here
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}

function viewselect(x){
  y=x.value;
  if(y == 0){
    document.getElementById('emailstuff').style.display = "";
  }
  else {
    document.getElementById('emailstuff').style.display = "none";
  }
}

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

function company_header (x) {
  //alert(x);
  y = x.value;
  
 
  //alert(contmsg);
    url = "frame_user_email_company_header.php?id=" + y;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

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

 function startuploaders(){
		  createUploader();
		}
		  
        function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('fup'),
                action: 'fileuploader_attach/php.php',

            });           
        }
		
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = startuploaders;
</script>
<link href="fileuploader_opm/fileuploader.css" rel="stylesheet" type="text/css">	
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

<form action="frame_user_email_action.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkform(this)">
<input type="hidden" name="email_html" id="email_html" value="0">
<table class="main">
<tr>
<td align="right" valign="top"><strong>Bcc</strong></td>
<td>
  <table class="main">
  <tr>
  <td valign="top">
  <input type='checkbox' onChange="SetChecked(this, 'users[]')"><strong>All</strong><br>
<?php
$counter = 1;
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
<td valign="top" align="right">Banner Image</td>
<td><input type="file" name="email_attachimage"></td>
</tr>
<tr>
<td align="right">Banner Image URL</td>
<td><input type="text" name="banner_url" size="50"></td>
</tr>
<tr>
<td valign="top" align="right">Attach</td>
<td>
<div id="fup">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
</div>
</td>
</tr>
<tr>
<td colspan="2"><input type="checkbox" name="email_include_signature" value="1">Include Signature</td>
</tr>
</table>
</div>

<input type="submit" name="submit1" value="Send">

</form>

</body>
</html>
