<?php
include "includes/functions.php";
$prospect_id = $_GET['prospect_id'];
$field = $_GET['field'];
$action = $_GET['action'];

if($action=="form"){

  $sql = "SELECT $field from prospects where prospect_id='$prospect_id'";
  $value = stripslashes(getsingleresult($sql));

  $html = "<form name='update" . $field . "' action='view_company_edit_action.php' method='post' enctype='multipart/form-data'>";
  $html .= "<input type='hidden' name='field' value='" . $field . "'>";
  switch($field){
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
	case "logo":{
	  $html .= "<input type='hidden' name='prospect_id' value='" . $prospect_id . "'>";
	  $html .= "<input type='file' name='logo'>";
	  $html .= "<input type='submit' name='submit1' value='Update Logo'>";
	  break;
	}
	case "master_status_id":{
	  $html .= "<select name='newvalue'>";
	  $sql = "SELECT * from master_status";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $html .= "<option value='" . $record['master_status_id'] . "'";
		if($value==$record['master_status_id']) $html .= " selected";
		$html .= ">" . superclean(stripslashes($record['master_status'])) . "</option>";
	  }
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "master_stage_id":{
	  $html .= "<select name='newvalue'>";
	  $sql = "SELECT * from master_stage";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $html .= "<option value='" . $record['master_stage_id'] . "'";
		if($value==$record['master_stage_id']) $html .= " selected";
		$html .= ">" . superclean(stripslashes($record['master_stage'])) . "</option>";
	  }
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "fcs_rep":{
	  $html .= "<select name='newvalue'>";
	  $html .= "<option value='0'></option>";
	  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $html .= "<option value='" . $record['user_id'] . "'";
		if($value==$record['user_id']) $html .= " selected";
		$html .= ">" . superclean(stripslashes($record['fullname'])) . "</option>";
	  }
	  $html .= "</select>";
	  $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('update" . $field . "')\\\">";
	  break;
	}
	case "irep":{
	  $sql = "SELECT irep from properties where prospect_id='$prospect_id' and corporate=1";
	  $value = getsingleresult($sql);
	  
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
  $newvalue = go_escape_string($newvalue);
  if($field != "irep") { // we want to update the irep of properties, corporate, not of prospects
    $sql = "UPDATE prospects set $field=\"$newvalue\" where prospect_id='$prospect_id'";
    executeupdate($sql);
  }
  
  $sql = "INSERT into check_latlng(prospect_id) values('$prospect_id')";
  executeupdate($sql);
  
  $sql = "SELECT $field from prospects where prospect_id='$prospect_id'";
  $value = stripslashes(getsingleresult($sql));
  if($field=="fcs_rep"){
    $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$value'";
	$value = superclean(stripslashes(getsingleresult($sql)));
  }
  if($field=="master_stage_id"){
    $sql = "SELECT master_stage from master_stage where master_stage_id='$value'";
	$value = "Stage: " . superclean(stripslashes(getsingleresult($sql)));
  }
  if($field=="master_status_id"){
    $sql = "SELECT master_status from master_status where master_status_id='$value'";
	$value = "Status: " . superclean(stripslashes(getsingleresult($sql)));
  }
  if($field=="irep") {
    $sql = "UPDATE properties set irep='$newvalue' where prospect_id='$prospect_id' and corporate=1";
	executeupdate($sql);
	
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
  
  if($field=="billto_address") $value = nl2br($value);
  
  if($SESSION_MASTER_ID==1 && $field=="company_name"){
    $sql = "SELECT created_master_id from prospects where prospect_id='$prospect_id'";
	$created_master_id = getsingleresult($sql);
	if($created_master_id){
	  $sql = "UPDATE master_list set master_name=\"$value\" where master_id='$created_master_id'";
	  executeupdate($sql);
	}
  }
  
  if($value=="") $value = "[" . strtoupper($field) . "]";
  $value = addslashes($value);
  $html = "<a href=\\\"javascript:editField('" . $field . "')\\\" class='blankclick_red'>" . $value . "</a>";
}

$html = jsclean($html);

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));
$company_name = addslashes($company_name);
?>


div = document.getElementById('<?=$field?>');
div.innerHTML = "<?php echo $html; ?>";

div = document.getElementById('company_name_display');
div.innerHTML = "<?php echo $company_name; ?>";

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