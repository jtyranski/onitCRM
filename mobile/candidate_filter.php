<?php include "includes/header.php"; 
$user_id = $SESSION_USER_ID;
?>
<script>
function clearall(){
  //document.form1.import_identifier.value = "";
  document.form1.region.value = "";
  document.form1.prospect_type.value = "";
  document.form1.zip.value = "";
  document.form1.distance.value = "";
  
  document.form1.submit();
}
</script>

<form action="candidate_filter_action.php" method="post" name="form1">
<div class="main" style="width:100%;">
<div class="main_large">Prospecting Filters</div>
Region:<br>
<select name="region" style="width:290px;">
<option value="">All</option>
<option value="1"<?php if($_SESSION['cand_region']==1) echo " selected";?>>1</option>
<option value="2"<?php if($_SESSION['cand_region']==2) echo " selected";?>>2</option>
<option value="3"<?php if($_SESSION['cand_region']==3) echo " selected";?>>3</option>
</select>
<br><br>
<?php /*
Identifier:<br>
<select name="import_identifier" style="width:290px;">
<option value=""></option>
<?php
$sql = "SELECT b.identifier
FROM prospecting_todo a, prospects b
WHERE a.prospect_id = b.prospect_id
AND a.user_id ='$user_id' and b.identifier != '' 
GROUP BY b.identifier order by b.identifier desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $foo = stripslashes($record['identifier']);
  ?>
  <option value="<?=$foo?>"<?php if($_SESSION['cand_import']==$foo) echo " selected";?>><?=$foo?></option>
  <?php
}
?>
</select>
<br>
<br>
*/?>
Originated:<br>
<select name="prospect_type" style="width:290px;">
<option value="">All</option>
<option value="Dial"<?php if($_SESSION['cand_prospect_type']=="Dial") echo " selected";?>>Dial</option>
<option value="Visit"<?php if($_SESSION['cand_prospect_type']=="Visit") echo " selected";?>>Visit</option>
</select>
<br><br>
Zip Code: <input type="text" name="zip" value="<?=$_SESSION['cand_zip']?>" size="5">
Radius: <input type="text" name="distance" value="<?=$_SESSION['cand_distance']?>" size="3"> Miles
<br><br>
<input type="submit" name="submit1" value="Submit" style="width:290px;">
<br><br>
<input type="button" name="button1" value="Clear All" style="width:290px;" onclick="clearall()">
</div>
</form>
<?php include "includes/footer.php"; ?>
