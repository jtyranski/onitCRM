<?php
include "includes/functions.php";

$id = $_GET['id'];
$field = $_GET['field'];
$action = $_GET['action'];

if($action=="form"){

  $sql = "SELECT $field from contacts where id='$id'";
  $value = stripslashes(getsingleresult($sql));

  $html = "<form name='contact" . $field . $id . "' action='#' method='post' enctype='multipart/form-data'>";
  $html .= "<input type='hidden' name='field' value='" . $field . "'>";
  switch($field){
	default:{
      $html .= "<input type='text'";
	  if($field=="phone") $html .= " class='phoneext'";
	  if($field=="fax") $html .= " class='phoneext'";
	  if($field=="mobile") $html .= " class='phoneext'";
	  $html .= " size='20' name='newvalue' value=\\\"" . $value . "\\\">";
      $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateContact('contact" . $field . $id . "', '$id')\\\">";
	  break;
	}
  }
  $html .= "</form>";
}

if($action=="update"){
  $newvalue = go_escape_string($_GET['newvalue']);
  if($field=="firstname" || $field=="lastname" || $field=="position") $newvalue = ucfirst($newvalue);
  $sql = "UPDATE contacts set $field=\"$newvalue\" where id='$id'";
  executeupdate($sql);
  
  $sql = "SELECT $field from contacts where id='$id'";
  $value = stripslashes(getsingleresult($sql));
  if($field=="phone" && $value != "") $value = "p:" . $value;
  if($field=="fax" && $value != "") $value = "f:" . $value;
  if($field=="mobile" && $value != "") $value = "m:" . $value;
  
  
  if($value=="") $value = "[" . strtoupper($field) . "]";
  
  $html = "<a href=\\\"javascript:editContact('" . $field . "', '" . $id . "')\\\" class='blankclick'>" . $value . "</a>";
}

?>


div = document.getElementById('<?=$id?>_<?=$field?>');
div.innerHTML = "<?php echo $html; ?>";

$(".phoneext").livequery(function(){
    $(this).mask('(999) 999-9999? x99999');
});
