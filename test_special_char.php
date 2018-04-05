<?php
include "includes/functions.php";

$test = "this is a test and here I go & tra la la\nNext line";

$test = specialChar($test);

echo "test is " . $test;

?>