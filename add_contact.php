<?php
include "includes/header.php";
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];

$cancel = "view_property.php?property_id=$property_id";
if($prospect_id != 0) $cancel = "view_company.php?prospect_id=$prospect_id";

$position = $_SESSION['fcs_add_contact']['position'];
$firstname = $_SESSION['fcs_add_contact']['firstname'];
$lastname = $_SESSION['fcs_add_contact']['lastname'];
$phone = $_SESSION['fcs_add_contact']['phone'];
$mobile = $_SESSION['fcs_add_contact']['mobile'];
$fax = $_SESSION['fcs_add_contact']['fax'];
$email = $_SESSION['fcs_add_contact']['email'];

?>
<script src="includes/jquery-1.6.4.js"></script>
<script src="includes/jquery.maskedinput-1.3.js"></script>
<script src="includes/jquery.livequery.js"></script>
<script type="text/javascript"> 
$(".phoneext").livequery(function(){
    $(this).mask('(999) 999-9999? x99999');
});

function officefill(x){
  y=x.value;
  document.getElementById('phone').value=y;
}
function faxfill(x){
  y=x.value;
  document.getElementById('fax').value=y;
}
</script>
<div align="center">
  <div class="whiteround" style="height:250px; text-align:left;">
  <?php if($_SESSION['sess_msg'] != ""){ ?>
  <div style="color:red;"><?=$_SESSION['sess_msg']?></div>
  <?php
  $_SESSION['sess_msg'] = "";
  }
  ?>
  <form action="add_contact_action.php" method="post">
  <input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
  <input type="hidden" name="property_id" value="<?=$property_id?>">
<table class="main">
<tr>
<td align="right">Position</td>
<td><input type="text" name="position" value="<?=$position?>"></td>
</tr>
<tr>
<td align="right">First Name</td>
<td><input type="text" name="firstname" value="<?=$firstname?>"></td>
</tr>
<tr>
<td align="right">Last Name</td>
<td><input type="text" name="lastname" value="<?=$lastname?>"></td>
</tr>
<tr>
<td align="right">Office</td>
<td><input type="text" name="phone" value="<?=$phone?>" class='phoneext' id="phone"></td>
<td>
<select name="officepre" onchange="officefill(this)">
<option value=""></option>
<?php
if($prospect_id != 0) $sql = "SELECT phone from contacts where prospect_id='$prospect_id' and phone != '' group by phone";
if($property_id != 0) $sql = "SELECT phone from contacts where property_id='$property_id' and phone != '' group by phone";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['phone']?>"><?=$record['phone']?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Mobile</td>
<td><input type="text" name="mobile" value="<?=$mobile?>" class='phoneext'></td>
</tr>
<tr>
<td align="right">Fax</td>
<td><input type="text" name="fax" value="<?=$fax?>" class='phoneext' id="fax"></td>
<td>
<select name="faxpre" onchange="faxfill(this)">
<option value=""></option>
<?php
if($prospect_id != 0) $sql = "SELECT fax from contacts where prospect_id='$prospect_id' and fax != '' group by fax";
if($property_id != 0) $sql = "SELECT fax from contacts where property_id='$property_id' and fax != '' group by fax";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['fax']?>"><?=$record['fax']?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Email</td>
<td><input type="text" name="email" value="<?=$email?>"></td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="button" name="button1" value="Cancel" onclick="document.location.href='<?=$cancel?>'"> &nbsp;
<input type="submit" name="submit1" value="Add">
</td>
</tr>
</table>
  </form>
  </div>
</div>

<?php include "includes/footer.php"; ?>

  