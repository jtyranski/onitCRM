<?php include "includes/header_white.php"; ?>
<?php
$property_id = $_GET['property_id'];
$section_id = $_GET['section_id'];
$drawing_id = $_GET['drawing_id'];
$section_type = $_GET['section_type'];

if($drawing_id != "new"){
  $sql = "SELECT * from drawings where drawing_id='$drawing_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $note = stripslashes($record['note']);
  $name = stripslashes($record['name']);
  $file = stripslashes($record['file']);
  $type = stripslashes($record['type']);
  $bill_to = stripslashes($record['bill_to']);
  $bt_manufacturer = stripslashes($record['bt_manufacturer']);
  $bt_installer = stripslashes($record['bt_installer']);
  $bt_term = stripslashes($record['bt_term']);
  $bt_start = stripslashes($record['bt_start']);
  $bt_contact = stripslashes($record['bt_contact']);
  $bt_phone = stripslashes($record['bt_phone']);
  
  $property_id = $record['property_id'];
  $section_id = $record['section_id'];
  $topfiller = "Edit Document";

}
else {
  $topfiller = "Add New Document";
}

$sql = "SELECT site_name from properties where property_id='$property_id'";
$site_name = stripslashes(getsingleresult($sql));


?>
<script>
function checkform(f){
  errmsg = "";
  if(f.type.value=="General" && f.name.value==""){ errmsg += "Please enter descriptive name of drawing. \n"; }
  if(f.file.value=="") { errmsg += "Please select a file to upload.\n"; }
  if(f.type.value=="Warranty" && f.section_id.value==0){ errmsg += "You must select a specific section in order to add a Warranty document.\n";}
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}

function doctypexx(x){
  //alert("test");
  
  document.getElementById("general").style.display="none";
  document.getElementById("warranty").style.display="none";
  if(x.value=="General"){
    document.getElementById("general").style.display = "";
  }
  if(x.value=="Warranty"){
    document.getElementById("warranty").style.display = "";
  }
  
}

function testo(x){
  alert(x.value);
}

</script>

<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<form action="drawing_edit_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="docform">
<input type="hidden" name="drawing_id" value="<?=$drawing_id?>">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="section_type" value="<?=$section_type?>">
<a href="frame_property_document_new.php?property_id=<?=$property_id?>&section_type=<?=$section_type?>">Create New Document</a><br>
<table class="main">
<tr>
<td align="right">Type</td>
<td>
<select name="type" onchange="doctypexx(this)">
<option value="General"<?php if($type=="General") echo " selected";?>>General</option>
<option value="Warranty"<?php if($type=="Warranty") echo " selected";?>>Warranty</option>
</select>
</td>
</tr>
<tr>
<td align="right">Section</td>
<td>
<select name="section_id">
<option value="0">[None]</option>
<?php
$sql = "SELECT * from sections where property_id='$property_id' and multiple='' and section_type='$section_type' order by section_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['section_id']?>"<?php if($section_id == $record['section_id']) echo " selected"; ?>><?=stripslashes($record['section_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">File (20MB Max)</td>
<td><input type="file" name="file"></td>
</tr>
</table>

<table class="main" id="general">
<tr>
<td align="right">Description of File</td>
<td><input type="text" name="name" value="<?=$name?>" size="40" maxlength="200"></td>
</tr>

<tr>
<td align="right" valign="top">Note</td>
<td><textarea name="note" rows="7" cols="40"><?=$note?></textarea></td>
</tr>
</table>

<table class="main" id="warranty" style="display:none;">
  <tr>
  <td>Bill To</td>
  <td>
  <select name="bill_to">
  <option value="Manufacturer"<?php if($bill_to=="Manufacturer") echo " selected";?>>Manufacturer</option>
  <option value="Installer"<?php if($bill_to=="Installer") echo " selected";?>>Installer</option>
  </select>
  </td>
  <td align="right">Manufacturer</td>
  <td>
  <select name="bt_manufacturer">
  <?php
  $sql = "SELECT company_name from prospects where master_id='" . $SESSION_MASTER_ID . "' and industry=1 and manufacturer=1 and display=1 order by company_name";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<option value="<?=stripslashes($record['company_name'])?>"><?=stripslashes($record['company_name'])?></option>
	<?php
  }
  ?>
  </select>
  </td>
  <td align="right">Term</td>
  <td><input type="text" name="bt_term" value="<?=$bt_term?>"></td>
  <td align="right">Start</td>
  <td><input type="text" name="bt_start" value="<?=$bt_start?>"></td>
  </tr>
  <tr>
  <td></td>
  <td></td>
  <td align="right">Installer</td>
  <td>
  <select name="bt_installer">
  <?php
  $sql = "SELECT company_name from prospects where master_id='" . $SESSION_MASTER_ID . "' and industry=1 and installer=1 and display=1 order by company_name";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<option value="<?=stripslashes($record['company_name'])?>"><?=stripslashes($record['company_name'])?></option>
	<?php
  }
  ?>
  </select>
  </td>
  <td align="right">Contact</td>
  <td><input type="text" name="bt_contact" value="<?=$bt_contact?>"></td>
  <td align="right">Phone</td>
  <td><input type="text" name="bt_phone" value="<?=$bt_phone?>"></td>
  </tr>
</table>

<input type="submit" name="submit1" value="<?=$topfiller?>">
</form>
<script>
doctypexx(document.docform.type);
</script>
<?php include "includes/footer.php"; ?>