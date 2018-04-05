<?php
include "includes/functions.php";


$_SESSION[$sess_header . '_user_id'] = "";
$_SESSION[$sess_header . '_master_id'] = "";
$_SESSION[$sess_header . '_isadmin'] = "";
$_SESSION[$sess_view_header . '_demo'] = "";
$_SESSION[$sess_header . '_contacts_search_results'] = "";
$_SESSION[$sess_header . '_opportunities'] = "";
$_SESSION[$sess_header . '_multilogo'] = "";
$_SESSION[$sess_header . '_use_resources'] = "";
$_SESSION[$sess_header . '_user_level'] = "";

$_SESSION[$sess_header . '_contacts2_filterby'] = "";
$_SESSION[$sess_header . '_contacts2_zip'] = "";
$_SESSION[$sess_header . '_contacts2_distance'] = "";
$_SESSION[$sess_header . '_contacts2_order_by'] = "";
$_SESSION[$sess_header . '_contacts2_order_by2'] = "";
$_SESSION[$sess_header . '_contacts2_searchfor'] = "";
$_SESSION[$sess_header . '_contacts2_cand_type'] = "";
$_SESSION[$sess_header . '_contacts2_show_hidden'] = "";
$_SESSION[$sess_header . '_contacts2_show_all_cand'] = "";
$_SESSION[$sess_header . '_contacts2_stage_filter'] = "";
$_SESSION[$sess_header . '_contacts2_status_filter'] ="";
$_SESSION[$sess_header . '_contacts2_start_record'] = "";
$_SESSION[$sess_header . '_contacts_order_by'] = "";
$_SESSION[$sess_header . '_contacts_order_by2'] = "";
$_SESSION[$sess_header . '_contacts_search_display'] = "";
$_SESSION[$sess_header . '_move_property'] = "";
$_SESSION[$sess_header . '_document_attach'] = "";

$_SESSION[$sess_header . '_use_groups'] = "";
$_SESSION[$sess_header . '_groups'] = "";
$_SESSION[$sess_header . '_subgroups'] = "";
$_SESSION[$sess_header . '_group_prospect_id'] = "";
$_SESSION[$sess_header . '_group_allowed_users'] = "";
$_SESSION[$sess_header . '_disciplines'] = "";

if($_SESSION[$sess_header . '_backup_login'] != ""){
  $_SESSION[$sess_header . '_user_id'] = $_SESSION[$sess_header . '_backup_login'];
  $_SESSION[$sess_header . '_master_id'] = 1;
  $_SESSION[$sess_header . '_isadmin'] = 1;
  $_SESSION[$sess_header . '_opportunities'] = 1;
  $_SESSION[$sess_header . '_multilogo'] = 1;
  $_SESSION[$sess_header . '_use_resources'] = 1;
  $_SESSION[$sess_header . '_use_ops'] = 1;
  $_SESSION[$sess_header . '_residential'] = 1;
  $_SESSION[$sess_header . '_user_level'] = $_SESSION[$sess_header . '_backup_user_level'];
  $_SESSION[$sess_header . '_use_groups'] = $_SESSION[$sess_header . '_backup_use_groups'];
  $_SESSION[$sess_header . '_groups'] = $_SESSION[$sess_header . '_backup_groups'];
  $_SESSION[$sess_header . '_subgroups'] = $_SESSION[$sess_header . '_backup_subgroups'];
  $_SESSION[$sess_header . '_group_prospect_id'] = $_SESSION[$sess_header . '_backup_group_prospect_id'];
  $_SESSION[$sess_header . '_group_allowed_users'] = $_SESSION[$sess_header . '_backup_group_allowed_users'];
  $_SESSION[$sess_header . '_disciplines'] = $_SESSION[$sess_header . '_backup_disciplines'];
}


$_SESSION['master_list_total'] = "";

meta_redirect("login.php");
?>