<?php
include "../includes/functions.php";

$x = $_GET['x'];
$on = $_GET['on'];

$_SESSION[$sess_header . '_programmingmenu_' . $x] = $on;
?>