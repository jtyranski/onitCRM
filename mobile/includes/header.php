<?php
ob_start("ob_gzhandler");
session_start();
include "includes/functions.php";
if($_SESSION['ro_force_change'] == 1 && $current_file_name != 'password_change.php') meta_redirect("password_change.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
                      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<title>roof management solutions</title>
<link href="styles/mobile_css.css" type="text/css" rel="stylesheet">
<meta name="viewport" content="width=320,user-scalable=false" />
</head>
<body leftMargin=0 topMargin=0>


<table border="0" cellpadding="0" cellspacing="0" width="320" class="main">
<tr>
<td>
