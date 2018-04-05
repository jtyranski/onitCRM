<?php
if($create_demo==1){
  $sql = "SELECT prospect_id, contact, user_id from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $prospect_id = stripslashes($record['prospect_id']);
  $contact = stripslashes($record['contact']);
  $user_id = stripslashes($record['user_id']);
  $space = strrpos($contact, " ");
  $lastname = substr($contact, $space + 1);
  $firstname = substr($contact, 0, $space);
  $sql = "SELECT email from users where user_id='$user_id'";
  $user_email = stripslashes(getsingleresult($sql));
  
  
  
  $sql = "SELECT company_name, address, city, state, zip, logo from prospects where prospect_id='$prospect_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $company_name = go_escape_string(stripslashes($record['company_name']));
  $address = go_escape_string(stripslashes($record['address']));
  $city = go_escape_string(stripslashes($record['city']));
  $state = go_escape_string(stripslashes($record['state']));
  $zip = go_escape_string(stripslashes($record['zip']));
  $logo = go_escape_string(stripslashes($record['logo']));
  if($logo != "") @copy("uploaded_files/logos/$logo", "uploaded_files/master_logos/$logo");
  
  $sql = "INSERT into master_list(master_name, address, city, state, zip, logo, demo, date_created, active, properties) values(
  \"$company_name\", \"$address\", \"$city\", \"$state\", \"$zip\", \"$logo\", 1, now(), 1, 1)";
  executeupdate($sql);
  $master_id = go_insert_id();
  
  $sql = "UPDATE prospects set created_master_id='$master_id' where prospect_id='$prospect_id'";
  executeupdate($sql);
  
  $tool_master = array("1", "2", "3", "8"); // default master tools to add
  $tool_on = array("3", "8"); // default tools to be turned on
  
  for($x=0;$x<sizeof($tool_master);$x++){
    $sql = "SELECT * from toolbox_master where tool_master_id='" . $tool_master[$x] . "'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$sql = "INSERT into toolbox_items(master_id, tool_master_id, cat_id, name, url, on_icon, off_icon) values(
	'$master_id', '" . $tool_master[$x] . "', '" . $record['cat_id'] . "', \"" . $record['name'] . "\", 
	\"" . $record['url'] . "\", \"" . $record['on_icon'] . "\", \"" . $record['off_icon'] . "\")";
	executeupdate($sql);
  }
  
  $tools_available = "";
  for($x=0;$x<sizeof($tool_on);$x++){
    $sql = "SELECT id from toolbox_items where master_id='$master_id' and tool_master_id='" . $tool_on[$x] . "'";
	$id = getsingleresult($sql);
	$tools_available .= "," . $id . ",";
  }
  
  $stripped = go_reg_replace("[^A-Za-z0-9]", "", $company_name);
  $stripped = go_reg_replace(" ", "", $stripped);
  $stripped = strtolower($stripped);
  $stripped = substr($stripped, 0, 4);
  $password = md5($stripped);
  
  $sql = "INSERT into users(master_id, firstname, lastname, email, password, admin, enabled, tools_available, alert_inspection_approval, alert_dispatch_approval) values(
  '$master_id', \"$firstname\", \"$lastname\", \"$user_email\", \"$password\", 1, 1, \"$tools_available\", 1, 1)";
  executeupdate($sql);
  $sql = "INSERT into users(master_id, firstname, lastname, email, password, enabled, servicemen, require_pre_approval) values(
  '$master_id', 'Joe', 'Servicetech', 'demo@demo.com', \"$password\", 1, 1, 1)";
  executeupdate($sql);
  $joetech = go_insert_id();
  
  @copy("images/demo_sears.gif", "uploaded_files/logos/demo_" . $master_id . ".gif");
  $sql = "INSERT into prospects(master_id, company_name, address, city, state, zip, logo, properties) values(
  '$master_id', 'Sears Holdings', '3333 Beverly Rd', 'Hoffman Estates', 'IL', '60179', 'demo_" . $master_id . ".gif', 1)";
  executeupdate($sql);
  $prospect_id = go_insert_id();
  $sql = "INSERT into properties(prospect_id, site_name, address, city, state, zip) values(
  '$prospect_id', 'Sears Store #22', '11800 Sears Drive', 'Livonia', 'MI', '48152')";
  executeupdate($sql);
  $property_id = go_insert_id();
  
  $sql = "INSERT into contacts(master_id, prospect_id, firstname, lastname, email) values('$master_id', '$prospect_id', 'Joe', 'Sears', \"$user_email\")";
  executeupdate($sql);
  $sql = "INSERT into am_users(prospect_id, firstname, lastname, email, password, admin, edit_mode, reports, enabled, level_access, sd_notify) values(
  '$prospect_id', 'Joe', 'Sears', \"$user_email\", \"" . md5(strtolower($lastname)) . "\", 1, 1, 1, 1, 'Master Admin', 1)";
  executeupdate($sql);
  
  $sql = "INSERT into activities(user_id, prospect_id, property_id, date, event, contact, regarding) values(
  '$joetech', '$prospect_id', '$property_id', now(), 'Inspection', 'Joe Sears', 'Inspection')";
  executeupdate($sql);
  $newact = go_insert_id();
  
  $sauce = md5(time());
  $sql = "INSERT into supercali_events (title, venue_id, contact_id, description, category_id, user_id, group_id, status_id, 
  quick_approve, prospect_id, property_id, what, value, ro_user_id, act_id, master_id) 
  values 
  (\"Inspection - $newact\", '1', '1', \"Inspection\", '34', '2', '1', '1', '".$sauce."', '".$prospect_id."', 
   '".$property_id."', 'I','0', '".$joetech."', '$newact', '$master_id')";
  executeupdate($sql);
  
  $newevent = go_insert_id();
  
  $sql = "INSERT into supercali_dates(event_id, date, end_date) values ('$newevent', now(), now())";
  executeupdate($sql);
  
  $sql = "SELECT * from def_list where visible=1";
    $res2 = executequery($sql);
    while($rec2 = go_fetch_array($res2)){
      $def_name = go_escape_string($rec2['def_name']);
	  $def_name_spanish = go_escape_string($rec2['def_name_spanish']);
	  $def = go_escape_string($rec2['def']);
	  $corrective_action = go_escape_string($rec2['corrective_action']);
	
	  $sql = "INSERT into def_list_master(master_id, def_name, def, corrective_action, def_name_spanish) values('$master_id', 
	  \"$def_name\", \"$def\", \"$corrective_action\", \"$def_name_spanish\")";
	  executeupdate($sql);
    }
  
  $sql = "UPDATE activities set demo_master_id = '$master_id' where act_id='$act_id'";
  executeupdate($sql);
  
  $redirect = "contacts.php?cand_type=5";
  
}
?>
  
  