<?php
exit;
include "includes/functions.php";

$headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: John Wert <jwert@encitegroup.com>\n";
  $headers .= "Return-Path: jwert@encitegroup.com\n";  // necessary for some emails such as ao
  $headers .= "Bcc:jwert@encitegroup.com\n";
  $subject = "testing bcc with no recipient";
  $message = "I am just testing this stuff now<br>No need to reply";
  
  email_q("", $subject, $message, $headers);
  ?>