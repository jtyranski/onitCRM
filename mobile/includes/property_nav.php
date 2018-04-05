<?php
$sql = "SELECT prospect_id from properties where property_id='" . $_GET['property_id'] . "'";
$x_prospect_id = getsingleresult($sql);

$filename_array = explode("/", $_SERVER['SCRIPT_NAME']);
$current_file_name = array_pop($filename_array);

$contact_array = array("property.php", "property_details.php", "property_details_edit.php");
$info_array = array("property_info_beazer.php", "property_info_manville.php", "property_info_nonpfri.php");
$calendar_array = array("property_calendar.php");
$notes_array = array("property_notes.php", "property_notes_add.php");

if(in_array($current_file_name, $contact_array)){
  $contact_nav = " selected";
}

if(in_array($current_file_name, $info_array)){
  $info_nav = " selected";
}

if(in_array($current_file_name, $calendar_array)){
  $calendar_nav = " selected";
}

if(in_array($current_file_name, $notes_array)){
  $notes_nav = " selected";
}


?>
<script>
function loadpage_property(x){
  y = x.value;
  document.location.href=y;
}
</script>
<select name="mainnav" onChange="loadpage_property(this)" onfocus="this.style.backgroundColor='rgb(255,102,0)';" onblur="this.style.backgroundColor='';">
<option value="property_details.php?property_id=<?=$_GET['property_id']?>"<?=$contact_nav?>>Contacts</option>
<option value="property_calendar.php?property_id=<?=$_GET['property_id']?>"<?=$calendar_nav?>>Calendar</option>
<option value="property_notes.php?property_id=<?=$_GET['property_id']?>"<?=$notes_nav?>>Notes</option>
</select>