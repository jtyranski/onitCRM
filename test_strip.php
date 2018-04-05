<?php
$string = "A.1. Contractor Company";
$stripped = go_reg_replace("[^A-Za-z0-9]", "", $string);
$stripped = go_reg_replace(" ", "", $stripped);

echo $stripped;
?>