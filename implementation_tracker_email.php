<?php
include "includes/functions.php";
$master_id = $_GET['master_id'];

?>
<?php  // this is just for fcs login ?>
<html>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>

<script>
function viewselect(x){
  y=x.value;
  if(y == 0){
    document.getElementById('emailstuff').style.display = "";
  }
  else {
    document.getElementById('emailstuff').style.display = "none";
  }
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



<form action="implementation_tracker_email_action.php" method="post" enctype="multipart/form-data" name="form1" onSubmit="return checkform(this)">
<input type="hidden" name="email_html" id="email_html" value="0">
<input type="hidden" name="master_id" value="<?=$master_id?>">
<table class="main">


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

</td>
<td>

<textarea name="message" rows="10" cols="80" id="email_message"></textarea>

</td>
</tr>
<tr>
<td>Attachment</td>
<td><input type="file" name="attachment"></td>
</tr>



</table>
</div>

<input type="submit" name="submit1" value="Send">

</form>

</body>
</html>
