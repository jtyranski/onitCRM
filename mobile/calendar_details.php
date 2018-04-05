<?php include "includes/header.php"; ?>
<?php include "includes/main_nav.php"; ?>
<br>
<?php
$act_id = $_GET['act_id'];
$personal = $_GET['personal']; // for go back link

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
	$html .= "<td valign='top'><strong>";
	if($prospect_id==0){
	  $html .= "Personal - " . $priority;
	}
	else {
	  $html .= $event . "<br>";
	  $html .= "<a href='company_details.php?prospect_id=" . $prospect_id . "'>" . $company_name . "</a> - ";
	  $html .= "<a href='property_details.php?property_id=" . $property_id . "'>" . $site_name . "</a></strong>";
	}
	$html .= "<br>" . $contact . "</td>";
	$html .= "</tr></table><br>";
	if($info['phone'] != "") $html .= "Phone: " . $info['phone'] . "<br><br>";
	if($info['mobile'] != "") $html .= "Mobile: " . $info['mobile'] . "<br><br>";
	if($info['email'] != "") $html .= "Email: " . $info['email'] . "<br><br>";
	$html .= "<br>" . $regarding;
	break;
  }
  case "Meeting":
  case "Bid Presentation":
  case "Inspection":
  case "Risk Report Presentation":
  case "FCS Meetings":{
    $html = "<table width='320' class='main'>";
	$html .= "<tr>";
	$html .= "<td valign='top'><img src='images/meeting.jpg'></td>";
	$html .= "<td valign='top'><strong>";
	if($prospect_id==0){
	  $html .= "Personal - " . $priority;
	}
	else {
	  $html .= $event . "<br>";
	  $html .= "<a href='company_details.php?prospect_id=" . $prospect_id . "'>" . $company_name . "</a> - ";
	  $html .= "<a href='property_details.php?property_id=" . $property_id . "'>" . $site_name . "</a></strong>";
	}
	$html .= "<br>" . $contact . "</td>";
	$html .= "</tr></table>";
	$html .= "<br>" . $datepretty . "<br>" . $regarding;
	break;
  }
  default:{
    $html = "<table width='320' class='main'>";
	$html .= "<tr>";
	$html .= "<td valign='top'><img src='images/todo.jpg'></td>";
	$html .= "<td valign='top'><strong>" . $priority . " - " . $regarding . "</strong></td>";
	$html .= "</tr></table>";
	$html .= "<br>" . $datepretty . "<br>";
	if($prospect_id==0){
	  $html .= "Personal";
	}
	else {
	  $html .= $event . "<br>";
	  $html .= "<a href='company_details.php?prospect_id=" . $prospect_id . "'>" . $company_name . "</a> - ";
	  $html .= "<a href='property_details.php?property_id=" . $property_id . "'>" . $site_name . "</a>";
	}
	break;
  }
}
?>
<div class="breadcrumb">
<a href="calendar.php?personal=<?=$personal?>" class="breadcrumb">Calendar</a> > <?=$event?></div>
<?php
echo $html;
?>
<br><br>
<?php if($prospect_id==0){ ?>
<form action="calendar_details_action.php" method="post">
<input type="hidden" name="act_id" value="<?=$act_id?>">
Priority: <input type="text" class="largerbox" name="priority" value="<?=$priority?>" size="3">
<br>
<textarea name="regarding" rows="3" cols="20"><?=$regarding?></textarea>
<input type="submit" name="submit1" value="Update">
</form>
<?php } ?>
<table class="main" width="100%">
<tr>
<td width="33%">
<a href="calendar.php?personal=<?=$personal?>">Go Back</a>
</td>
<td width="33%">
<a href="calendar_complete.php?act_id=<?=$act_id?>&personal=<?=$personal?>">Complete</a>
</td>
<td width="33%">
<a href="calendar_delete.php?act_id=<?=$act_id?>&personal=<?=$personal?>">Delete</a>
</td>
</tr>
</table>
<?php
include "includes/footer.php";
?>
