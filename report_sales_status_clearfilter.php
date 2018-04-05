<?php
include "includes/functions.php";

$_SESSION[$sess_header . '_ss_user_id'] = "";
$_SESSION[$sess_header . '_ss_view'] = "";
$_SESSION[$sess_header . '_ss_order_by'] = "";
$_SESSION[$sess_header . '_ss_order_by2'] = "";
$_SESSION[$sess_header . '_ss_product'] = "";
$_SESSION[$sess_header . '_ss_zero'] = "";

$_SESSION[$sess_header . '_ss_group_filter'] = "";
$_SESSION[$sess_header . '_ss_subgroup_filter'] = "";

meta_redirect("report_sales_status.php");
?>