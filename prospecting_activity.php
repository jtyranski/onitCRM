<?php include "includes/functions.php"; ?>
<script src="includes/calendar.js"></script>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
$sql = "SELECT site_name from properties where property_id='$property_id'";
$site_name = stripslashes(getsingleresult($sql));
$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));
?>

<form action="prospecting_activity_action.php" method="post">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">

<div style="position:relative;" class="main">
<div style="float:left;"><img src="images/contact_icon.png"></div>
<div class="main_large" style="float:left;">
<?=$company_name?><br><?=$site_name?>
</div>
</div>
<div style="clear:both;"></div>
  <table class="main" width="100%">
  <tr>
  <td>Event</td>
  <td>
  <select name="event">
  <option value="Contact">Contact</option>
  <option value="Meeting">Meeting</option>
  <option value="To Do">To Do</option>
  <option value="Bid Presentation"><?=$BP?></option>
  <option value="Inspection"><?=$I?></option>
  <option value="Risk Report Presentation"><?=$RRP?></option>
  <option value="FCS Meetings"><?=$UM?></option>
  <option value="Calendar - Other">Calendar - Other</option>
  </select>
  </td>
  </tr>
  
  <tr>
  <td valign="top">Date</td>
  <td>
  
  <input size="10" type="text" name="datepretty" value="<?=date("m/d/Y", time() + (7 * 24 * 60 * 60)) ?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
  
  <?php /*
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
  */ ?>
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
  <tr>
  <td>For</td>
  <td>
  <select name="user_id">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
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
  <td>Details</td>
  <td><input type="text" name="regarding" size="50" maxlength="250"></td>
  </tr>
  
  <tr>
  <td>Scheduled By</td>
  <td>
  <select name="scheduled_by">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
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
  <td colspan="2">
  <input type="checkbox" name="tm" value="1" checked="checked">Originated by TM
  </td>
  </tr>
  <tr>
  <td colspan="2">
  <input type="checkbox" name="rollover" value="1" checked="checked">Automatically roll over to today's date
  </td>
  </tr>
  
  </table>
<input type="submit" name="submit1" value="Add Activity">
</form>