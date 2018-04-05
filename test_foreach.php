<?php
//$array = array("one", "two", "three", "four");

$array[13] = "one";
$array[56] = "two";
$array[99] = "three";
$array[98] = "four";

foreach($array as $key => $value){
  echo "Key: $key _ Value: $value<br>";
}
?>