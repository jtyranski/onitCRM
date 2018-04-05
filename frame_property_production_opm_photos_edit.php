<?php
include "includes/functions.php";
$opm_entry_id = $_GET['opm_entry_id'];
$photo_id = $_GET['photo_id'];
$action = $_GET['action'];

if($action=="form"){

  $sql = "SELECT description from opm_entry_photos where photo_id='$photo_id'";
  $value = stripslashes(getsingleresult($sql));

  $html = "";

      $value = go_reg_replace("\"", "DBLQUOTE", $value);
	  $html .= "<input type='text' size='30' name='newvalue' id='newvalue' value=\\\"" . $value . "\\\" maxlength='255'>";
      $html .= "<input type='button' name='button1' value='Edit' onclick=\\\"updateField('" . $photo_id . "')\\\">";

}

if($action=="update"){
  $newvalue = $_GET['newvalue'];
  $newvalue = stripslashes($newvalue);
  $newvalue = go_reg_replace("AMPERSAND", "&", $newvalue);
  $newvalue = go_reg_replace("POUNDSIGN", "#", $newvalue);
  $newvalue = go_reg_replace("DBLQUOTE", "\"", $newvalue);
  $newvalue = go_reg_replace("NEWLINE", "\n", $newvalue);
  $newvalue = go_escape_string($newvalue);
  
  
  $sql = "UPDATE opm_entry_photos set description=\"$newvalue\" where photo_id='$photo_id'";
  executeupdate($sql);
  
}

$html = jsclean($html);


if($action=="form"){?>
div = document.getElementById('<?=$photo_id?>');
div.innerHTML = "<?php echo $html; ?>";
newdesc = document.getElementById('newvalue').value;
newdesc = newdesc.replace(/DBLQUOTE/g, "\"");
document.getElementById('newvalue').value = newdesc;
<?php } ?>

<?php
if($action=="update"){
?>
load_photos();
<?php
}
?>
