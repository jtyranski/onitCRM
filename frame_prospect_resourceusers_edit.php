<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$prospect_id=$_GET['prospect_id'];
  
$user_id = $_GET['user_id'];
if($user_id != "new"){
  $sql = "SELECT master_id from users where user_id='$user_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT *, date_format(password_change_date, \"%m/%d/%Y\") as pwdate from users where user_id='$user_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $firstname = stripslashes($record['firstname']);
  $lastname = stripslashes($record['lastname']);
  $email = stripslashes($record['email']);
  $office = stripslashes($record['office']);
  $extension = stripslashes($record['extension']);
  $title = stripslashes($record['title']);
  $cellphone = stripslashes($record['cellphone']);
  $cell_id = $record['cell_id'];
  $admin = $record['admin'];
  $report = $record['report'];
  $photo = $record['photo'];
  $pwdate = $record['pwdate'];
  $force_change = $record['force_change'];
  $enabled = $record['enabled'];
  $servicemen = $record['servicemen'];
  $resource = $record['resource'];
  
  
  $signature = stripslashes($record['signature']);
  $googlemap_zipcode = stripslashes($record['googlemap_zipcode']);
  
 
  
  
  
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
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}




</script>


<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<a href="frame_prospect_resourceusers.php?prospect_id=<?=$prospect_id?>">Back to User List</a><br>
<form action="frame_prospect_resourceusers_edit_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="user_id" value="<?=$user_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">

<div style="width:100%; position:relative;" class="main">

<table class="main">
<tr id="firstname">
<td align="right">First Name</td>
<td><input type="text" name="firstname" value="<?=$firstname?>"></td>
</tr>
<tr id="lastname">
<td align="right">Last Name</td>
<td><input type="text" name="lastname" value="<?=$lastname?>"></td>
</tr>
<tr>
<td align="right">Email</td>
<td><input type="text" name="email" value="<?=$email?>"></td>
</tr>
<tr>
<td align="right">Password</td>
<td><input type="password" name="password"></td>
</tr>


<tr>
<td align="right">Zip Code at<br>Current Location</td>
<td><input type="text" name="googlemap_zipcode" value="<?=$googlemap_zipcode?>"></td>
</tr>


</table>



<input type="submit" name="submit1" value="Update User">






</div>
</form>



