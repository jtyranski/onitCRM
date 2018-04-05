<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];
$area = $_GET['area'];
$x = $_GET['x'];

$_SESSION[$leak_id . "_" . $area] = $x;
?>