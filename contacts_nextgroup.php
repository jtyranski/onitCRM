<?php
include "includes/functions.php";

$x = $_GET['x'];
$total_records = $_GET['total_records'];

$sql = "SELECT prospects_per_page from users where user_id='" . $SESSION_USER_ID . "'";
$prospects_per_page = getsingleresult($sql);
if($prospects_per_page=="" || $prospects_per_page < 1) $prospects_per_page = 2000;

$start_record = $_SESSION[$sess_header . '_contacts2_start_record'];
if($start_record=="") $start_record = 0;

switch($x){
  case 1:{ // next
    $start_record += $prospects_per_page;
	break;
  }
  case -1:{ //prev
    $start_record -= $prospects_per_page;
	break;
  }
  case 2:{ //end
    $y = floor($total_records / $prospects_per_page);
	$start_record = ($y * $prospects_per_page);
	break;
  }
  case -2:{ //first
    $start_record = 0;
	break;
  }
}

$_SESSION[$sess_header . '_contacts2_start_record'] = $start_record;
?>