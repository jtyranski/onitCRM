<?php
require_once "../includes/variables.php";


$h = $DB["host"];
$d = $DB["dbName"];
$u = $DB["user"];
$p = $DB["pass"];

$calendar_title = "Calendar";

$calendar_email = "admin";

$calendar_url = $CORE_URL . "supercali/";

$table_prefix = "supercali_";

$default_module = 2;

// display sub-category events along with events in selected category
$include_child_categories = true;

// display events in parent category along with events in selected category
$include_parent_categories = true;

// How to display the titles on the header of the calendar
$week_titles[] = "Sunday";
$week_titles[] = "Monday";
$week_titles[] = "Tuesday";
$week_titles[] = "Wednesday";
$week_titles[] = "Thursday";
$week_titles[] = "Friday";
$week_titles[] = "Saturday";

//used with the quarter view
$week_titles_s[] = "Sun";
$week_titles_s[] = "Mon";
$week_titles_s[] = "Tue";
$week_titles_s[] = "Wed";
$week_titles_s[] = "Thu";
$week_titles_s[] = "Fri";
$week_titles_s[] = "Sat";

//used with the year view
$week_titles_ss[] = "S";
$week_titles_ss[] = "M";
$week_titles_ss[] = "T";
$week_titles_ss[] = "W";
$week_titles_ss[] = "T";
$week_titles_ss[] = "F";
$week_titles_ss[] = "S";

// FCK Editor can be used for HTML of event text
$fck_editor_path = "";

$fck_editor_toolbar = "Basic"; // Basic or Default

// The default start category for the event calendar
$start_category_id = 1;

// Language File
$language = "lang/en_us.php";

// Day/week view start hour
$day_week_start_hour = 8;

?>