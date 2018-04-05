<?php
include "includes/header_white.php";
$dis_id = $_GET['dis_id'];

if($dis_id==1) exit;
if($dis_id != "new"){
  $sql = "SELECT * from disciplines where dis_id='$dis_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $discipline = stripslashes($record['discipline']);
  $membrane_filler = stripslashes($record['membrane_filler']);
  $flashings_filler = stripslashes($record['flashings_filler']);
  $sheetmetal_filler = stripslashes($record['sheetmetal_filler']);
}
?>

<div class="main">
<form action="tool_discipline_edit_action.php" method="post">
<input type="hidden" name="dis_id" value="<?=$dis_id?>">
Leaving Membrane, Flashings, or Sheet Metal values blank will hide them entirely from view<br>
<table class="main">
<tr>
<td align="right">Discipline Name</td>
<td><input type="text" name="discipline" value="<?=$discipline?>"></td>
</tr>
<tr>
<td align="right">Membrane</td>
<td><input type="text" name="membrane_filler" value="<?=$membrane_filler?>"></td>
</tr>
<tr>
<td align="right">Flashings</td>
<td><input type="text" name="flashings_filler" value="<?=$flashings_filler?>"></td>
</tr>
<tr>
<td align="right">Sheet Metal</td>
<td><input type="text" name="sheetmetal_filler" value="<?=$sheetmetal_filler?>"></td>
</tr>
</table>
<input type="submit" name="submit1" value="Update">
</form>
</div>

  