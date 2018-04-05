<?php
$DB["dbName"] = "onit_crm";
	$DB["host"] = "onit-crm.cwm1cjadvm2y.us-east-2.rds.amazonaws.com";
	
	$DB["user"] = "master_user";
	$DB["pass"] = "cJkGOjrkc39l";

$MAIN_CO_NAME = "RoofOptions";
	
$sess_header = "glory";
$sess_view_header = $sess_header . "_view";

$SESSION_MASTER_ID = $_SESSION[$sess_header . '_master_id'];
$SESSION_USER_ID = $_SESSION[$sess_header . '_user_id'];
$SESSION_ISADMIN = $_SESSION[$sess_header . '_isadmin'];
$SESSION_SUPERCALI_USER_ID = $_SESSION[$sess_header . '_supercali_user_id'];
$SESSION_OPPORTUNITIES = $_SESSION[$sess_header . '_opportunities'];
$SESSION_MULTILOGO = $_SESSION[$sess_header . '_multilogo'];
$SESSION_TIMEZONE = $_SESSION[$sess_header . '_timezone'];
$SESSION_USE_RESOURCES = $_SESSION[$sess_header . '_use_resources'];
$SESSION_USER_LEVEL = $_SESSION[$sess_header . '_user_level'];
$SESSION_CAN_EXPORT = $_SESSION[$sess_header . '_can_export'];

$SESSION_EDIT_SD_MODE = $_SESSION[$sess_header . '_edit_sd_mode'];
$SESSION_IREP = $_SESSION[$sess_header . '_irep'];
$SESSION_IPAD_EDIT_MODE = $_SESSION[$sess_header . '_ipad_edit_mode'];
$SESSION_USE_OPS = $_SESSION[$sess_header . '_use_ops'];
$SESSION_RESIDENTIAL = $_SESSION[$sess_header . '_residential'];

$SESSION_USE_GROUPS = $_SESSION[$sess_header . '_use_groups'];
$SESSION_GROUPS = $_SESSION[$sess_header . '_groups'];
$SESSION_SUBGROUPS = $_SESSION[$sess_header . '_subgroups'];
$SESSION_GROUP_PROSPECT_ID = $_SESSION[$sess_header . '_group_prospect_id'];
$SESSION_GROUP_ALLOWED_USERS = $_SESSION[$sess_header . '_group_allowed_users'];
if($SESSION_GROUP_ALLOWED_USERS=="") $SESSION_GROUP_ALLOWED_USERS = "0";

$SESSION_DIS = $_SESSION[$sess_header . '_disciplines'];

$GOOGLE_API = "AIzaSyDuD1ZZ5ETrBO4QIu3anRI1cFx8pdiWeuE";
$SITE_URL = "http://www.onitapp.com/scripts/glorymaintenance/";
$CORE_URL = "http://www.onitapp.com/scripts/glorymaintenance/";
$FCS_URL = "http://www.roofoptions.com/glorymaintenanceview/";
$UP_FCSVIEW = "../glorymaintenanceview/";
$EMBED_VIDEO = "devzonevideo";
?>