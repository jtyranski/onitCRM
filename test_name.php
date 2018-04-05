<?php
$contact = "John Wert";
$space = strrpos($contact, " ");
  $lastname = substr($contact, $space + 1);
  $firstname = substr($contact, 0, $space);
  echo "*" . $firstname . "*" . "<br>";
  echo "*" . $lastname . "*";
?>