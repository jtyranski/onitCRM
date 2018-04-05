<?php
$filename_array = explode("/", $_SERVER['SCRIPT_NAME']);
$current_file_name = array_pop($filename_array);

$calendar_array = array("calendar.php", "calendar_add.php", "calendar_details.php", "calendar_complete.php", "calendar_add_personal.php");
$company_array = array("company.php", "company_details.php", "company_details_edit.php", "prospecting.php");
$property_array = array("property.php", "property_details.php", "property_details_edit.php", "property_info_beazer.php", 
"property_info_manville.php", "property_info_nonpfri.php", "property_calendar.php", "property_notes.php", "property_notes_add.php");
$search_array = array("search.php");
$schedule_array = array("schedule.php", "schedule_details.php");
$candidate_array = array("candidate.php", "candidate_details.php", "candidate_complete.php", "candidate_filter.php");
$contact_array = array("contact.php", "contact_details.php");
$sd_array = array("service_dispatch.php", "service_dispatch_details.php");

if(in_array($current_file_name, $calendar_array)){
  $calendar_nav = " selected";
  $breadcrumb = "<a href='calendar.php' class='breadcrumb_main'>Calendar</a>";
}

if(in_array($current_file_name, $company_array)){
  $company_nav = " selected";
  $breadcrumb = "<a href='company.php' class='breadcrumb_main'>Company</a>";
}

if(in_array($current_file_name, $property_array)){
  $property_nav = " selected";
  $breadcrumb = "<a href='property.php' class='breadcrumb_main'>Property</a>";
}

if(in_array($current_file_name, $search_array)){
  $search_nav = " selected";
  $breadcrumb = "<a href='search.php' class='breadcrumb_main'>Search</a>";
}

if(in_array($current_file_name, $schedule_array)){
  $schedule_nav = " selected";
  $breadcrumb = "<a href='schedule.php' class='breadcrumb_main'>Schedule</a>";
}

if(in_array($current_file_name, $candidate_array)){
  $candidate_nav = " selected";
  $breadcrumb = "<a href='candidate.php' class='breadcrumb_main'>Prospecting</a>";
}

if(in_array($current_file_name, $contact_array)){
  $breadcrumb = "<a href='contact.php' class='breadcrumb_main'>Company Contacts</a>";
}

if(in_array($current_file_name, $sd_array)){
  $breadcrumb = "<a href='service_dispatch.php' class='breadcrumb_main'>Service Dispatch</a>";
}
?>
<script>
function loadpage(x){
  y = x.value;
  document.location.href=y;
}
</script>
<?php /*
<select name="mainnav" onchange="loadpage(this)" onfocus="this.style.backgroundColor='rgb(255,102,0)';" onblur="this.style.backgroundColor='';">
<option value="calendar.php"<?=$calendar_nav?>>Calendar</option>
<option value="company.php"<?=$company_nav?>>Company</option>
<option value="property.php"<?=$property_nav?>>Property</option>
<option value="search.php"<?=$search_nav?>>Search</option>
<option value="schedule.php"<?=$schedule_nav?>>Schedule</option>
</select>
*/?>
<div class="breadcrumb_main">
<a href="home.php" class="breadcrumb_main">Home</a> > <?=$breadcrumb?>
</div>