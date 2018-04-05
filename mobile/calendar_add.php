<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<br>
<?php
$property_id = $_GET['property_id'];
if($property_id == ""){
  $property_id = 0;
  $prospect_id = 0;
  $breadcrumb = "Add Personal To Do";
}
else {
  $sql = "SELECT prospect_id, site_name from properties where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $prospect_id = $record['prospect_id'];
  $site_name = stripslashes($record['site_name']);
  $breadcrumb = "<a href='property_details.php?prospect_id=$prospect_id'>$site_name</a> > Add Activity";
}
?>
<script src="includes/calendar.js"></script>
<div class="breadcrumb">
<a href="calendar.php" class="breadcrumb">Calendar</a> > <?=$breadcrumb?>
</div>
<form action="calendar_add_action.php" method="post">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">


  <table class="main" width="100%">
  <tr>
  <td>Event</td>
  <td>
  <select name="event">
<option value="Inspection"<?php if($event == "Inspection") echo " selected"; ?>><?=$I?></option>
<option value="To Do"<?php if($event == "To Do") echo " selected"; ?>>To Do</option>
<?php
$sql = "SELECT b.activity_name from activities_items a, activities_master b where 
a.activity_master_id = b.activity_master_id and a.master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['activity_name']?>"<?php if($event == $record['activity_name']) echo " selected";?>><?=$record['activity_name']?></option>
  <?php
}
?>

</select>
  </td>
  </tr>
  <tr>
  <td>For</td>
  <td>
  <select name="user_id">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
    <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$SESSION_USER_ID) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
    <?php
  }
  ?>
  </select>
  </td>
  </tr>
  <tr>
  <td valign="top">Date</td>
  <td>
  <?php /*
  <input size="10" type="text" name="datepretty" value="<?=date("m/d/Y", time() + (7 * 24 * 60 * 60)) ?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
  */ ?>
  <select name="month">
  <?php 
  for($x=1;$x<=12;$x++){ 
    $y = $x;
	if(strlen($y) < 2) $y = "0" . $y;
	?>
	<option value="<?=$y?>"<?php if(date("n")==$x) echo " selected";?>><?=$y?></option>
	<?php
  }
  ?>
  </select>
  /
  <select name="day">
  <?php 
  for($x=1;$x<=31;$x++){ 
    $y = $x;
	if(strlen($y) < 2) $y = "0" . $y;
	?>
	<option value="<?=$y?>"<?php if(date("j")==$x) echo " selected";?>><?=$y?></option>
	<?php
  }
  ?>
  </select>
  /
  <select name="year">
  <?php 
  for($x=date("Y");$x<=date("Y") + 1;$x++){ 
	?>
	<option value="<?=$x?>"<?php if(date("Y")==$x) echo " selected";?>><?=$x?></option>
	<?php
  }
  ?>
  </select>
  
  <br>
  <input type="text" class="largerbox" name="hourpretty" value="<?=$hourpretty?>" size="2">:
  <input type="text" class="largerbox" name="minutepretty" value="<?=$minutepretty?>" size="2">
  <select name="ampm">
  <option value="AM"<?php if($ampm=="AM") echo " selected";?>>AM</option>
  <option value="PM"<?php if($ampm=="PM") echo " selected";?>>PM</option>
  </select>
  </td>
  </tr>
  <tr>
  <td>With</td>
  <td>
  <select name="artistName">
  <option value="N/A">N/A</option>
  <?php
  $thelist = GetContacts("", $prospect_id);
  for($x=0;$x<sizeof($thelist);$x++){
    ?>
    <option value="<?=$thelist[$x]['name']?>"><?=$thelist[$x]['name']?></option>
    <?php
  }
  ?>
  </select>
  </td>
  </tr>
  </table>

<table class="main" width="100%">
<tr>
<td valign="top">Regarding</td>
<td><input type="text" class="largerbox" name="regarding" size="20" maxlength="250"></td>
</tr>
</table>
<input type="submit" name="submit1" value="Submit">
</form>
<?php include "includes/footer.php"; ?>