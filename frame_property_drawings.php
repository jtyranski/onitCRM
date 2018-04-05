<?php include "includes/header_white.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php
$section_type = $_GET['section_type'];
if($section_type=="") $section_type="roof";
if($property_id=="" || $property_id==0){
  echo "There was an error uploading your file.  The maximum file size is 20MB.";
  exit;
}
?>

<script>
function DelDrawing(x){
  cf = confirm("Are you sure you wish to delete this drawing?");
  if(cf){
    document.location.href="drawing_delete.php?drawing_id=" + x + "&property_id=<?=$property_id?>&section_type=<?=$section_type?>";
  }
}
</script>
<body bgColor='#FFFFFF' leftMargin=0 topMargin=0>
<div class="main">

<table class="main" width="100%">
<tr>
<td align="right">
<a href="drawing_edit.php?property_id=<?=$property_id?>&drawing_id=new&section_type=<?=$section_type?>">Add New Document</a>
</td>
</tr>
</table>

<table class="main" width="100%" cellpadding="2" cellspacing="0">
<tr>
<td><strong>Name</strong></td>
<td><strong>Notes</strong></td>
<td><strong>File</strong></td>
<td><strong>Send</strong></td>
<td></td>
</tr>
<?php
$sql = "SELECT a.user_id, a.name, a.file, a.drawing_id, a.note, a.type, a.bill_to, 
a.bt_manufacturer, a.bt_installer, a.bt_term, a.bt_start, a.bt_contact, a.bt_phone from drawings a left join sections b
 on a.section_id = b.section_id where 1=1 and a.property_id='$property_id' order by name";
$result = executequery($sql);
$counter=0;
while($record = go_fetch_array($result)){
  $counter++;
  $name = stripslashes($record['name']);
  $notes = stripslashes(nl2br($record['note']));
  if($record['type']=="Warranty"){
    $name = "Warranty";
	$notes = stripslashes($record['bt_term']) . " - started " . stripslashes($record['bt_start']) . "<br>Bill to: " . stripslashes($record['bill_to']) . " - ";
	$notes .= stripslashes($record['bt_contact']) . " " . stripslashes($record['bt_phone']);
  }
  ?>
  <tr<?php if($counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
  <td valign="top"><?=$name?></td>
  
  <td valign="top">
  <?=$notes?>
  </td>
  <td valign="top"><a href="<?=$CORE_URL?>uploaded_files/drawings/<?=stripslashes($record['file'])?>" target="_blank">View</a></td>
  <td valign="top"><a href="frame_property_document_email.php?property_id=<?=$property_id?>&drawing_id=<?=$record['drawing_id']?>">Send</a></td>
  <td valign="top">

  <a href="javascript:DelDrawing('<?=$record['drawing_id']?>')">delete</a>

  </td>
  </tr>
  <?php
}
?>
</table>
</div>
</body>
</html>