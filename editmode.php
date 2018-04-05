<?php
include "includes/functions.php";

$type = $_GET['type'];
$pid = $_GET['pid'];

if($_SESSION[$sess_header . '_ipad_edit_mode']==1){
  $_SESSION[$sess_header . '_ipad_edit_mode'] = 0;
}
else {
  $_SESSION[$sess_header . '_ipad_edit_mode'] = 1;
}

if($type=="property") $redirect = "view_property.php?property_id=$pid";
if($type=="company") $redirect = "view_company.php?prospect_id=$pid";
meta_redirect($redirect);
?>