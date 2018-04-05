<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$section_id = $_GET['section_id'];
$dis_id = $_GET['dis_id'];
if($dis_id=="") $dis_id=1;
if($section_id != "new"){

$sql = "SELECT property_id from sections where section_id='$section_id'";
$property_id = getsingleresult($sql);

  $sql = "SELECT * from sections where section_id='$section_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $section_name = stripslashes($record['section_name']);
  $property_id = stripslashes($record['property_id']);
  $inspection_status = stripslashes($record['inspection_status']);
  $grade = stripslashes($record['grade']);
  $sqft = stripslashes($record['sqft']);
  $roof_type = stripslashes($record['roof_type']);
  $map_reference = stripslashes($record['map_reference']);
  $projected_replacement = stripslashes($record['projected_replacement']);
  $installation_date_pretty = stripslashes($record['installation_date_pretty']);
  $manufacturer = stripslashes($record['manufacturer']);
  $warranty = stripslashes($record['warranty']);
  $contractor = stripslashes($record['contractor']);
  $cont_contact = stripslashes($record['cont_contact']);
  $cont_phone = stripslashes($record['cont_phone']);
  $cont_email = stripslashes($record['cont_email']);
  $cont_mobile = stripslashes($record['cont_mobile']);
  $roof_system = stripslashes($record['roof_system']);
  $insulation = stripslashes($record['insulation']);
  $deck = stripslashes($record['deck']);
  $property_type = stripslashes($record['property_type']);
  $topfiller = "Edit Section";
}
else {
  $topfiller = "Add New Section";
  //$site_manager = $_SESSION['user_id'];
  $property_id = $_GET['property_id'];
  
}


?>
<script src="includes/calendar.js"></script>
<script>
function checkform(f){
  errmsg = "";
  if(f.section_name.value==""){ errmsg += "Please enter section name. \n"; }
  //if(isNaN(f.sqft.value)) { errmsg += "Please enter only numeric values for sq/ft. \n"; }

  
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
<form action="frame_property_section_add_action.php"  method="post" onSubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="section_id" value="<?=$section_id?>">
<input type="hidden" name="property_type" value="Non-PFRI">
<table class="main">
<tr>
<td align="right">Type</td>
<td>
<select name="dis_id">
<?php
if(is_array($SESSION_DIS)){
  $sql = "SELECT dis_id, discipline from disciplines order by dis_id";
  $result = executequery($sql);
  $DIS_NAMES = array();
  while($record = go_fetch_array($result)){
    $xdis_id = $record['dis_id'];
	$DIS_NAMES[$xdis_id] = stripslashes($record['discipline']);
  }
  
  for($x=0;$x<sizeof($SESSION_DIS);$x++){ 
    $xdis_id = $SESSION_DIS[$x]?>
    <option value="<?=$xdis_id?>"<?php if($dis_id==$xdis_id) echo " selected";?>><?=$DIS_NAMES[$xdis_id]?></option>
	<?php
  }
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">Section Name</td>
<td><input type="text" name="section_name" value="<?=$section_name?>" size="40" maxlength="200"></td>
</tr>
<?php /*
<tr>
<td align="right">Inspection Status</td>
<td>
<select name="inspection_status">
  <option value="Not Inspected"<?php if($inspection_status=="Not Inspected") echo " selected"; ?>>Not Inspected</option>
  <option value="Inspected"<?php if($inspection_status=="Inspected") echo " selected"; ?>>Inspected</option>
  <option value="Visual Inspection"<?php if($inspection_status=="Visual Inspection") echo " selected"; ?>>Visual Inspection</option>
  <option value="Completed"<?php if($inspection_status=="Completed") echo " selected"; ?>>Completed</option>
  </select>
</td>
</tr>
*/ ?>
<tr>
<td align="right">Grade</td>
<td>
<select name="grade">
<option value="0"<?php if($grade=="0") echo " selected"; ?>>None</option>
<option value="A"<?php if($grade=="A") echo " selected"; ?>>A</option>
<option value="B"<?php if($grade=="B") echo " selected"; ?>>B</option>
<option value="C"<?php if($grade=="C") echo " selected"; ?>>C</option>
<option value="D"<?php if($grade=="D") echo " selected"; ?>>D</option>
<option value="F"<?php if($grade=="F") echo " selected"; ?>>F</option>
</select>
</td>
</tr>
<tr>
<td align="right">Projected Replacement</td>
<td>
<select name="projected_replacement">
  <option value="0"<?php if($projected_replacement=="0") echo " selected"; ?>>Unknown</option>
  <?php
  for($x=date("Y");$x<(date("Y") + 11);$x++){
    ?>
	<option value="<?=$x?>"<?php if($x==$projected_replacement) echo " selected"; ?>><?=$x?></option>
	<?php
  }
  ?>
  </select>
</td>
</tr>

<tr>
<td align="right">Sq/Ft</td>
<td><input type="text" name="sqft" value="<?=$sqft?>" size="10" maxlength="200"></td>
</tr>
<?php /*
<tr>
<td align="right">Roof Type</td>
<td><input type="text" name="roof_type" value="<?=$roof_type?>" size="40" maxlength="50"></td>
</tr>
*/ ?>
<tr>
<td>Estimated Install:</td>
<td>
<select name="estimated_install">
<?php
for($x=date("Y");$x>=(date("Y")-30);$x--){
?>
<option value="<?=$x?>"><?=$x?></option>
<?php } ?>
</select>
</td>
</tr>


<tr>
<td align="right">Map Reference</td>
<td><input type="text" name="map_reference" value="<?=$map_reference?>" size="4" maxlength="20"></td>
</tr>

<tr>
<td align="right">Image</td>
<td><input type="file" name="photo"></td>
</tr>

</table>


<table class="main">
<tr>
<td colspan="2">
<input type="submit" name="submit1" value="<?=$topfiller?>">
</td>
</tr>
</table>
</form>
