<?php
/*
ALTER TABLE  `properties` ADD  `groups_temp` TEXT NOT NULL AFTER  `subgroups` ,
ADD  `subgroups_temp` TEXT NOT NULL AFTER  `groups_temp` ;

UPDATE properties SET groups_temp = groups,
subgroups_temp = subgroups

ALTER TABLE  `properties` CHANGE  `subgroups`  `subgroups` INT NOT NULL COMMENT  'ONLY SINGLE of subgroups from assoc props'
ALTER TABLE  `properties` CHANGE  `groups`  `subgroups` INT NOT NULL COMMENT  'ONLY SINGLE of groups from assoc props'

includes/functions
mobile/company_details
mobile/search
supercali/index
supercali/includes/header
supercali/modules/gantt
supercali/modules/operations
admin_groups_pop
adming_groups_action
contacts2_results
contacts_fcs_results
fcs_sd_report
frame_property_groups_action
frame_property_groups
frame_prospect_addproperty_action
frame_prospect_groups
frame_prospect_groups_action
frame_prospect_properties
login_action
prospect_edit_action
report_sales_status
search_filter

view/frame_user_sites
view/frame_user_leakcheck
view/frame_user_budget_matrix
view/includes/functions
*/
exit;
include "includes/functions.php";

$sql = "SELECT property_id, groups_temp, subgroups_temp from properties where (groups_temp != '' or subgroups_temp != '')";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $property_id = $record['property_id'];
  $groups_temp = $record['groups_temp'];
  $subgroups_temp = $record['subgroups_temp'];
  
  $groups = go_reg_replace("\,", "", $groups_temp);
  $subgroups = go_reg_replace("\,", "", $subgroups_temp);
  
  $sql = "UPDATE properties set groups='$groups', subgroups='$subgroups' where property_id='$property_id'";
  executeupdate($sql);
}
?>
  