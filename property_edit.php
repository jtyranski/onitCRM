<?php
include "includes/functions.php";
$property_id = $_GET['property_id'];
$field = $_GET['field'];
$action = $_GET['action'];

if($action=="form"){

  $sql = "SELECT $field from properties where property_id='$property_id'";
  $value = stripslashes(getsingleresult($sql));

  $html = "<form name='update" . $field . "' action='view_property_edit_action.php' method='post' enctype='multipart/form-data'>";
  $html .= "<input type='hidden' name='field' value='" . $field . "'>";
  switch($field){
    case "roof_size":{
	$value = str_replace(",", "", $value);
	$html .= "<input type='text' size='10' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\">";
	$html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	break;
    }
    case "state":{
	  $html .= "<select name='newvalue'>";
	  $html .= "<option value=''>Select a State</option>";
      $sql2 = "SELECT * from states order by state_name";
      $result2 = executequery($sql2);
      while($record2 = go_fetch_array($result2)){
        $html .= "<option value='" . $record2['state_code'] . "'";
		if ($value==$record2['state_code']) $html.= " selected";
		$html .= ">" . $record2['state_name'] . "</option>";
      }

	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "image":{
	  $html .= "<input type='hidden' name='property_id' value='" . $property_id . "'>";
	  $html .= "<input type='file' name='image'>";
	  $html .= "<input type='submit' name='submit1' value='Update Image'>";
	  break;
	}
	case "image_front":{
	  $html .= "<input type='hidden' name='property_id' value='" . $property_id . "'>";
	  $html .= "<input type='file' name='image'>";
	  $html .= "<input type='submit' name='submit1' value='Update Front Image'>";
	  break;
	}
	case "report_image":{
	  $html .= "<select name='newvalue'>";
      $html .= "<option value='image'"; 
	  if($value=='image') $html .=" selected";
	  $html .=">Overhead</option>";
	  $html .= "<option value='image_front'"; 
	  if($value=='image_front') $html .=" selected";
	  $html .=">Front</option>";
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "logo":{
	  $html .= "<input type='hidden' name='property_id' value='" . $property_id . "'>";
	  $html .= "<select name='logo'>";
	  $html .= "<option value='1'"; 
	  if($value==1) $html .=" selected";
	  $html .=">Main Logo</option>";
	  $html .= "<option value='2'"; 
	  if($value==2) $html .=" selected";
	  $html .=">Alt Logo</option>";
	  $html .="</select>";
	  
	  $html .= "<input type='submit' name='submit1' value='Change Logo'>";
	  break;
	}
	case "budgetmatrix_in_report":{
	  $html .= "<select name='newvalue'>";
      $html .= "<option value='1'"; 
	  if($value==1) $html .=" selected";
	  $html .=">Yes</option>";
	  $html .= "<option value='0'"; 
	  if($value==0) $html .=" selected";
	  $html .=">No</option>";
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "irep":{
	  $html .= "<select name='newvalue'>";
	  $html .= "<option value='0'>None</option>";
      $sql2 = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' and irep=1 order by lastname";
      $result2 = executequery($sql2);
      while($record2 = go_fetch_array($result2)){
        $html .= "<option value='" . $record2['user_id'] . "'";
		if ($value==$record2['user_id']) $html.= " selected";
		$html .= ">" . stripslashes($record2['fullname']) . "</option>";
      }

	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "billto_address":{
	  $value = go_reg_replace("\n", "ZZZZ", $value);
	  $html .= "<textarea name='newvalue' rows='4' cols='40' id='bta'>" . $value . "</textarea>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	default:{
      $value = go_reg_replace("\"", "DBLQUOTE", $value);
      $html .= "<input type='text' size='30' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\">";
      $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
  }
  $html .= "</form>";
}

if($action=="update"){
  $newvalue = $_GET['newvalue'];
  $newvalue = stripslashes($newvalue);
  $newvalue = go_reg_replace("AMPERSAND", "&", $newvalue);
  $newvalue = go_reg_replace("POUNDSIGN", "#", $newvalue);
  $newvalue = go_reg_replace("DBLQUOTE", "\"", $newvalue);
  $newvalue = go_reg_replace("NEWLINE", "\n", $newvalue);
  $newvalue = go_reg_replace("PLEFT", "(", $newvalue);
  $newvalue = go_reg_replace("PRIGHT", ")", $newvalue);
  $newvalue = str_replace(",", "", $newvalue);
  $newvalue = go_escape_string($newvalue);
  
  if($field=="site_name"){ // record the change of site name to the notes of company. And, I guess, property. 7/13/12 jw
    $sql = "SELECT site_name from properties where property_id='$property_id'";
	$old_site_name = stripslashes(getsingleresult($sql));
	$note = "Property: $old_site_name is now $newvalue";
	$note = go_escape_string($note);
	$sql = "SELECT prospect_id from properties where property_id='$property_id'";
	$prospect_id = getsingleresult($sql);
	$sql = "INSERT into notes(prospect_id, property_id, user_id, date, event, note) values('$prospect_id', '$property_id', '" . $SESSION_USER_ID . "', now(), 'Note', \"$note\")";
	executeupdate($sql);
  }
  
  $sql = "UPDATE properties set $field=\"$newvalue\" where property_id='$property_id'";
  executeupdate($sql);
  
  $sql = "INSERT into check_latlng(property_id) values('$property_id')";
  executeupdate($sql);
  
  $sql = "SELECT $field from properties where property_id='$property_id'";
  $value = stripslashes(getsingleresult($sql));
  if($value=="") $value = "[" . strtoupper($field) . "]";
  
  if($field=="irep") {
    $sql = "SELECT prospect_id from properties where property_id='$property_id'";
	$prospect_id = getsingleresult($sql);
	
	$sql = "SELECT irep from properties where prospect_id='$prospect_id' and display=1 and irep!= 0 group by irep";
	$result = executequery($sql);
	$irep = "";
	while($record = go_fetch_array($result)){
	  $irep .= "," . stripslashes($record['irep']) . ",";
	}
	$sql = "UPDATE prospects set irep='$irep' where prospect_id='$prospect_id'";
	executeupdate($sql);
	
    if($newvalue==0){
	  $value = "[NONE]";
	}
	else {
	  
      $sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='$newvalue'";
	  $value = stripslashes(getsingleresult($sql));
	}
    
	
  }
  
  if($field=="budgetmatrix_in_report"){
    if($newvalue==1){
	  $value = "Budget Matrix: Yes";
	}
	else {
	  $value = "Budget Matrix: No";
	}
  }
  
  if($field=="report_image"){
    if($newvalue=='image'){
	  $value = "Report Cover: Overhead Image";
	}
	if($newvalue=="image_front"){
	  $value = "Report Cover: Front Image";
	}
  }
  
  if($field=="billto_address") $value = nl2br($value);
  
  $value = addslashes($value);
  $html = "<a href=\\\"javascript:editField('" . $field . "')\\\" class='blankclick_red'>" . $value . "</a>";
}

$html = jsclean($html);

$sql = "SELECT site_name from properties where property_id='$property_id'";
$site_name = stripslashes(getsingleresult($sql));
$site_name = addslashes($site_name);
?>

div = document.getElementById('<?=$field?>');
div.innerHTML = "<?php echo $html; ?>";

div = document.getElementById('site_name_display');
div.innerHTML = "<?php echo $site_name; ?>";

<?php 

if($action=="form" && $field!="billto_address"){?>
newdesc = document.getElementById('newvalue').value;
newdesc = newdesc.replace(/DBLQUOTE/g, "\"");
document.getElementById('newvalue').value = newdesc;
<?php } ?>
<?php 

if($action=="form" && $field=="billto_address"){?>
newdesc = document.getElementById('bta').value;
newdesc = newdesc.replace(/ZZZZ/g, "\n");
document.getElementById('bta').value = newdesc;
<?php } 

?>
