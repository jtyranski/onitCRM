<?php include "includes/header.php"; ?>
<?php $property_id = $_GET['property_id']; ?>
<?php
$sql = "SELECT a.site_name, b.company_name, b.prospect_id from properties a, prospects b where 
a.prospect_id = b.prospect_id and a.property_id = '$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$company_name = stripslashes($record['company_name']);
$prospect_id = stripslashes($record['prospect_id']);
?>
<table class="main" width="100%">
<tr>
<td valign="top">
<?php include "includes/main_nav.php"; ?>
</td>
<td align="right" valign="top">
<?php include "includes/property_nav.php"; ?>
<br>
<a href="property_notes_add.php?property_id=<?=$property_id?>">Add</a>
</td>
</tr>
</table>
<div class="breadcrumb">
<a href="company_details.php?prospect_id=<?=$prospect_id?>" class="breadcrumb"><?=$company_name?></a> > 
<a href="property_details.php?property_id=<?=$property_id?>" class="breadcrumb"><?=$site_name?></a> > Add Note</div>

<form action="property_notes_add_action.php" method="post">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
Note:<br>
<textarea name="note" rows="3" cols="35"></textarea>
<br>
<input type="submit" name="submit1" value="Add Note" style="width:310px;">
</form>

<?php include "includes/footer.php"; ?>