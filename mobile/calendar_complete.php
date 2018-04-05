<?php include "includes/header.php";?>
<?php include "includes/main_nav.php";?>
<br>
<?php
$act_id = $_GET['act_id'];
$personal = $_GET['personal']; // for redirect
$sql = "SELECT prospect_id from activities where act_id='$act_id'";
$prospect_id = getsingleresult($sql);
if($prospect_id > 0){
  $sql = "SELECT a.event, a.regarding, date_format(a.date, \"%a, %b %d, %Y - %r\") as datepretty, a.property_id, 
  b.site_name, c.company_name, a.contact, a.priority 
  from activities a, properties b, prospects c 
  where a.property_id=b.property_id and a.prospect_id=c.prospect_id and a.act_id='$act_id'";
}
else {
  $sql = "SELECT a.event, a.regarding, date_format(a.date, \"%a, %b %d, %Y - %r\") as datepretty, a.property_id, 
  a.contact, a.priority 
  from activities a
  where a.act_id='$act_id'";
}
$result = executequery($sql);
$record = go_fetch_array($result);
$event = stripslashes($record['event']);
$regarding = stripslashes($record['regarding']);
$datepretty = stripslashes($record['datepretty']);
$site_name = stripslashes($record['site_name']);
$company_name = stripslashes($record['company_name']);
$contact = stripslashes($record['contact']);
$property_id = stripslashes($record['property_id']);
$priority = stripslashes($record['priority']);

switch($event){
  case "Contact":{
    $info = getContactInfo($contact, $prospect_id, $property_id);
	$html = "<table width='320' class='main'>";
	$html .= "<tr>";
	$html .= "<td valign='top'><img src='images/contact.jpg'></td>";
	$html .= "<td valign='top'><strong>" . $company_name . " - " . $site_name . "</strong><br>" . $contact . "</td>";
	$html .= "</tr></table>";
	break;
  }
  case "Meeting":{
    $html = "<table width='320' class='main'>";
	$html .= "<tr>";
	$html .= "<td valign='top'><img src='images/meeting.jpg'></td>";
	$html .= "<td valign='top'><strong>" . $company_name . " - " . $site_name . "</strong><br>" . $contact . "</td>";
	$html .= "</tr></table>";
	break;
  }
  default:{
    $html = "<table width='320' class='main'>";
	$html .= "<tr>";
	$html .= "<td valign='top'><img src='images/todo.jpg'></td>";
	$html .= "<td valign='top'><strong>" . $priority . " - " . $regarding . "</strong></td>";
	$html .= "</tr></table>";
	break;
  }
}
?>
<script>
function act_change(y){
  x = y.value;
  if(x=="Inspection"){
    document.getElementById('inspection_rank').style.display="block";
  }
  else {
    document.getElementById('inspection_rank').style.display="none";
  }
}

function show_next(x){
  if(x.checked==true){
    document.getElementById('next_act').style.display="";
  }
  else {
    document.getElementById('next_act').style.display="none";
  }
}

</script>
<div class="breadcrumb">
<a href="calendar.php?personal=<?=$personal?>" class="breadcrumb">Calendar</a> > <a href="calendar_details.php?act_id=<?=$act_id?>&personal=<?=$personal?>" class="breadcrumb">
<?=$event?></a> > Complete</div>
<?=$html?>
<form action="calendar_complete_action.php" method="post">
<input type="hidden" name="act_id" value="<?=$act_id?>">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="personal" value="<?=$personal?>">
<table class="main">
<tr>
<td>Result</td>
<td>
<select name="act_result">
<option value="Completed">Completed</option>
<option value="Attempted">Attempted</option>
<option value="Objective Met">Objective Met</option>
</select>
</td>
</tr>
<?php if($prospect_id > 0){ ?>

  <tr>
  <td valign="top">Notes</td>
  <td><textarea name="notes" rows="2" cols="20"></textarea></td>
  </tr>
  <tr>
  </table>
  <div class="main">
  <input type="checkbox" name="schedule_next" value="1" checked="checked" onchange="show_next(this)">Schedule Next Activity</div>
  <table class="main" id="next_act">
  <td>Next</td>
  <td>
  <select name="event">
  <option value="Contact">Contact</option>
  <option value="Meeting">Meeting</option>
  <option value="To Do">To Do</option>
  <option value="Bid Presentation"><?=$BP?></option>
  <option value="Inspection"><?=$I?></option>
  <option value="Risk Report Presentation"><?=$RRP?></option>
  <option value="FCS Meetings"><?=$UM?></option>
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
  <input size="10" type="text" name="datepretty" value="<?=date("m/d/Y", time() + (7 * 24 * 60 * 60)) ?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
  <br>
  <input type="text" name="hourpretty" value="<?=$hourpretty?>" size="2">:
  <input type="text" name="minutepretty" value="<?=$minutepretty?>" size="2">
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
  <td colspan="2">
  Regarding<br>
  <input type="text" class="largerbox" name="regarding" size="30" maxlength="250">
  </td>
  </tr>
  </table>
<?php } ?>
<input type="submit" name="submit1" value="Submit">
</form>
<?php include "includes/footer.php"; ?>