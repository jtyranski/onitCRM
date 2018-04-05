<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
if($property_id == "") $property_id = 0;
if($property_id != 0){
  $sql = "SELECT prospect_id from properties where property_id='$property_id'";
  $prospect_id = getsingleresult($sql);
}

if($property_id==0){
  $thelist = GetContacts('', $prospect_id, 1, 0);
}
else {
  $thelist = GetContacts_Property($property_id, 1);
}
?>
<div align="center">
  <div class="whiteround" style="height:800px; overflow:auto;" align="left">
  
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
function checkform(f){
  errmsg = "";
  
	  if(f.subject.value==""){ errmsg += "Please enter subject. \n"; }
	  if(f.email_message.value==""){ errmsg += "Please enter message. \n"; }
  
  var elLength = f.elements.length;
  var somethingchecked = 0;
  
    for (i=0; i<elLength; i++)
    {
        var type = f.elements[i].type;
        if (type=="checkbox" && f.elements[i].checked){
            somethingchecked=1;
        }

    }
  
  if(somethingchecked==0){ errmsg += "Please select at least one email recipient.\n";}
  
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
<form action="direct_email_action.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkform(this)">
<input type="hidden" name="email_html" id="email_html" value="0">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">

<?php if($_SESSION[$sess_header . '_document_attach'] != ""){ 
$sql = "SELECT type, file from drawings where drawing_id='" . $_SESSION[$sess_header . '_document_attach'] . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$type = stripslashes($record['type']);
$file = stripslashes($record['file']);
?>
<div class="main">
You are sending a <?=$type?> file attachment: <a href="uploaded_files/drawings/<?=$file?>" target="_blank">view</a>
</div>
<?php } ?>


<table class="main">
<tr>
<td align="right" valign="top"><strong>To</strong></td>
<td>
  <table class="main">
  <tr>
<?php
$counter = 0;
for($x=0;$x<sizeof($thelist);$x++){
  if($counter==0) echo "<td valign='top'>\n";
  ?>
  <input type="checkbox" name="to[]" value="<?=$thelist[$x]['email']?>"><?=stripslashes($thelist[$x]['name'])?><br>
  <?php
  $counter++;
  if($counter==5){
    echo "</td>\n";
	$counter = 0;
  }
}
?>
  <td valign="top">

  </td>
  </tr>

  </table>
</td>
</tr>

<tr>
<td align="right" valign="top"><strong>Bcc</strong></td>
<td>
  <table class="main">
  <tr>
  <td valign="top">
  <input type='checkbox' onchange="SetChecked(this, 'users[]')"><strong>All</strong><br>
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
<td valign="top" align="right">Attach<?php if($_SESSION[$sess_header . '_document_attach'] != "") { echo " additional file";}?></td>
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


  </div>
</div>
<?php include "includes/footer.php"; ?>