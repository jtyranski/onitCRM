<?php
include "includes/functions.php";

$user_id = $SESSION_USER_ID;
$action = $_GET['goaction'];
$return = $_GET['return'];

if($return=="") $return = "search.php";

if($action=="search"){
  $search_id = $_GET['search_id'];
  
  if($search_id != 0){
    $sql = "SELECT * from search_saves where user_id='$user_id' and id='$search_id'";
    $result = executequery($sql);
    $record = go_fetch_array($result);
    $searchby = stripslashes($record['searchby']);
    $searchfor = stripslashes($record['searchfor']);
    $prospect_status_searchby = stripslashes($record['prospect_status_searchby']);
    $sales_stage_searchby = stripslashes($record['sales_stage_searchby']);
    $prospecting_searchby = stripslashes($record['prospecting_searchby']);
    $prospecting_beazer_searchby = stripslashes($record['prospecting_beazer_searchby']);
    $prospect_result_searchby = stripslashes($record['prospect_result_searchby']);
    $zip = stripslashes($record['zip']);
    $distance = stripslashes($record['distance']);
    $searchby2 = stripslashes($record['searchby2']);
    $searchfor2 = stripslashes($record['searchfor2']);
    $order_by = stripslashes($record['order_by']);
    $order_by2 = stripslashes($record['order_by2']);
    $view_filter = stripslashes($record['view_filter']);
	meta_redirect($return . "?searchby=$searchby&searchfor=$searchfor&prospect_status_searchby=$prospect_status_searchby&sales_stage_searchby=$sales_stage_searchby&prospecting_searchby=$prospecting_searchby&zip=$zip&distance=$distance&searchby2=$searchby2&searchfor2=$searchfor2&order_by=$order_by&order_by2=$order_by2&view_filter=$view_filter&prospecting_beazer_searchby=$prospecting_beazer_searchby&prospect_result_searchby=$prospect_result_searchby&submit1=search&search_id=$search_id");
	
  }
  else{
    meta_redirect($return);
  }
}

if($action=="add"){
    $searchby = go_escape_string($_GET['searchby']);
    $searchfor = go_escape_string($_GET['searchfor']);
    $prospect_status_searchby = go_escape_string($_GET['prospect_status_searchby']);
    $sales_stage_searchby = go_escape_string($_GET['sales_stage_searchby']);
    $prospecting_searchby = go_escape_string($_GET['prospecting_searchby']);
    $prospecting_beazer_searchby = go_escape_string($_GET['prospecting_beazer_searchby']);
    $prospect_result_searchby = go_escape_string($_GET['prospect_result_searchby']);
    $zip = go_escape_string($_GET['zip']);
    $distance = go_escape_string($_GET['distance']);
    $searchby2 = go_escape_string($_GET['searchby2']);
    $searchfor2 = go_escape_string($_GET['searchfor2']);
    $order_by = go_escape_string($_GET['order_by']);
    $order_by2 = go_escape_string($_GET['order_by2']);
    $view_filter = go_escape_string($_GET['view_filter']);
	
	$search_name = go_escape_string($_GET['search_name']);
	
	$sql = "INSERT into search_saves(user_id, search_name, searchfor, searchby, prospect_status_searchby, prospecting_searchby, 
	sales_stage_searchby, prospecting_beazer_searchby, prospect_result_searchby, zip, distance, searchby2, 
	searchfor2, order_by, order_by2, view_filter) values (
	\"$user_id\", \"$search_name\", \"$searchfor\", \"$searchby\", \"$prospect_status_searchby\", \"$prospecting_searchby\", 
	\"$sales_stage_searchby\", \"$prospecting_beazer_searchby\", \"$prospect_result_searchby\", \"$zip\", \"$distance\", \"$searchby2\", 
	\"$searchfor2\", \"$order_by\", \"$order_by2\", \"$view_filter\")";
	executeupdate($sql);
	$sql = "SELECT id from search_saves where user_id='$user_id' order by id desc limit 1";
	$search_id = getsingleresult($sql);
	meta_redirect($return . "?searchby=$searchby&searchfor=$searchfor&prospect_status_searchby=$prospect_status_searchby&sales_stage_searchby=$sales_stage_searchby&prospecting_searchby=$prospecting_searchby&zip=$zip&distance=$distance&searchby2=$searchby2&searchfor2=$searchfor2&order_by=$order_by&order_by2=$order_by2&view_filter=$view_filter&prospecting_beazer_searchby=$prospecting_beazer_searchby&prospect_result_searchby=$prospect_result_searchby&submit1=search&search_id=$search_id");
}

if($action=="delete"){
	$search_id = $_GET['search_id'];
	if($search_id != 0){
	  $sql = "DELETE from search_saves where id='$search_id' and user_id='$user_id'";
	  executeupdate($sql);
	}
	meta_redirect($return);
}
?>
  