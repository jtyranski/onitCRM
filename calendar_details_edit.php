<?php include "includes/functions.php"; ?>
<script src="includes/calendar.js"></script>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">
<?php
$act_id = $_GET['act_id'];

if($act_id != "new"){
  $sql = "SELECT *, 
  date_format(date, \"%m/%d/%Y\") as datepretty,
  date_format(date, \"%h\") as hourpretty, 
  date_format(date, \"%i\") as minutepretty, 
  date_format(date, \"%p\") as ampm, 
  date_format(span_date, \"%m/%d/%Y\") as spanpretty
  from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $property_id = stripslashes($record['property_id']);
  $prospect_id = stripslashes($record['prospect_id']);
  $datepretty = stripslashes($record['datepretty']);
  $hourpretty = stripslashes($record['hourpretty']);
  $minutepretty = stripslashes($record['minutepretty']);
  $ampm = stripslashes($record['ampm']);
  $event = stripslashes($record['event']);
  $contact = stripslashes($record['contact']);
  $user_id = stripslashes($record['user_id']);
  $regarding = stripslashes($record['regarding']);
  $scheduled_by = stripslashes($record['scheduled_by']);
  $tm = stripslashes($record['tm']);
  $rollover = stripslashes($record['rollover']);
  
  $regarding = go_reg_replace("\"", "", $regarding);


  $sql = "SELECT site_name from properties where property_id='$property_id'";
  $site_name = stripslashes(getsingleresult($sql));
  $sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
  $company_name = stripslashes(getsingleresult($sql));
}

?>
<script>
function change_prospect(x){
  y = x.value;


  url="calendar_details_loadproperty.php?prospect_id=" + y;
    url=url+"&sid="+Math.random();
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}
</script>

<span style="font-size:16px;" class="main">Calendar</span><br>
<a href="../supercali">Return to Month view</a>
<br><br>

<form action="calendar_details_edit_action.php" method="post">
<input type="hidden" name="act_id" value="<?=$act_id?>">


<?php if($act_id != "new"){ ?>
<div style="position:relative;" class="main">
<div style="float:left;"><img src="images/contact_icon.png"></div>
<div class="main_large" style="float:left;">
<?=$company_name?><br><?=$site_name?>
</div>
</div>
<div style="clear:both;"></div>
<?php } ?>
  <table class="main" width="100%">
<?php /*
  <?php if($act_id=="new"){ ?>
  <tr>
  <td>Company</td>
  <td>
  <select name="prospect_id" onchange="change_prospect(this)">
<option value=""></option>
<option value="0"<?php if($prospect_id=="0") echo " selected"; ?>>Personal</option>
<?php
$sql = "SELECT * from prospects where display=1 order by company_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['prospect_id']?>"><?=stripslashes($record['company_name'])?></option>
  <?php
}
?>
</select>
  </td>
  </tr>
  <tr>
  <td>Property</td>
  <td id="property_area"></td>
  </tr>
  <?php } else { ?>
  <input type="hidden" name="property_id" value="<?=$property_id?>">
  <input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
  <?php } ?>
  
  <tr>
  <td>Event</td>
  <td>
  <select name="event">
  <option value="Contact"<?php if($event=="Contact") echo " selected";?>>Contact</option>
  <option value="Meeting"<?php if($event=="Meeting") echo " selected";?>>Meeting</option>
  <option value="To Do"<?php if($event=="To Do") echo " selected";?>>To Do</option>
  <option value="Bid Presentation"<?php if($event=="Bid Presentation") echo " selected";?>><?=$BP?></option>
  <option value="Inspection"<?php if($event=="Inspection") echo " selected";?>><?=$I?></option>
  <option value="Risk Report Presentation"<?php if($event=="Risk Report Presentation") echo " selected";?>><?=$RRP?></option>
  <option value="FCS Meetings"<?php if($event=="FCS Meetings") echo " selected";?>><?=$UM?></option>
  <option value="Calendar - Other"<?php if($event=="Calendar - Other") echo " selected";?>>Calendar - Other</option>
  </select>
  </td>
  </tr>
*/?>
  <tr>
  <td valign="top">Date</td>
  <td>
  
  <input size="10" type="text" name="datepretty" value="<?=$datepretty?>"> 
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
  <select name="contact">
  <option value="N/A">N/A</option>
  <?php
  $thelist = GetContacts("", $prospect_id);
  for($x=0;$x<sizeof($thelist);$x++){
    ?>
    <option value="<?=$thelist[$x]['name']?>"<?php if($thelist[$x]['name']==$contact) echo " selected";?>><?=$thelist[$x]['name']?></option>
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
    <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
    <?php
  }
  ?>
  </select>
  </td>
  </tr>
  
  <tr>
  <td>Details</td>
  <td><input type="text" name="regarding" size="50" maxlength="250" value="<?=$regarding?>"></td>
  </tr>
  <?php /*
  <tr>
  <td>Scheduled By</td>
  <td>
  <select name="scheduled_by">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
    <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$scheduled_by) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
    <?php
  }
  ?>
  </select>
  </td>
  </tr>
  <tr>
  <td colspan="2">
  <input type="checkbox" name="tm" value="1"<?php if($tm==1) echo " checked";?>>Originated by TM
  </td>
  </tr>
  <tr>
  <td colspan="2">
  <input type="checkbox" name="rollover" value="1"<?php if($rollover==1) echo " checked";?>>Automatically roll over to today's date
  </td>
  </tr>
  */?>
  </table>
<input type="submit" name="submit1" value="Edit Activity">
</form>