<?php
include "includes/functions.php";

$_SESSION['sdreport_archivefilter'] = "";
$_SESSION['sdreport_datefilter'] = "";
$_SESSION['sdreport_statusfilter'] = "";
$_SESSION['sdreport_typefilter'] = "";
$_SESSION['sdreport_order_by'] = "";
$_SESSION['sdreport_order_by2'] = "";
$_SESSION['sdreport_servicemanfilter'] = "";
$_SESSION['sdreport_contractorfilter'] = "";
$_SESSION['sdreport_account_manager_filter'] = "";
$_SESSION['sdreport_extra_searchby'] = "";
$_SESSION['sdreport_extra_searchfor'] = "";
$_SESSION['sdreport_priorityfilter'] = "";
$_SESSION['sdreport_group_filter'] = "";
$_SESSION['sdreport_subgroup_filter'] = "";

meta_redirect("fcs_sd_report.php");
?>