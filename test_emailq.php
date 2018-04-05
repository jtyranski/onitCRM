<?php
exit;
include "includes/functions.php";

$subject = "This is my first q message";
$message = "This is some kind of message<br>It is important<br><br>John";

$headers = "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: John Wert Jr <jwert@encitegroup.com>\n";
  $headers .= "Return-Path: jwert@encitegroup.com\n";  // necessary for some emails such as aol


$headers .= "Bcc: wertjr@encitegroup.com, ";
$to = "jtt@encitegroup.com,dpr@encitegroup.com, ";

email_q($to, $subject, $message, $headers);
?>