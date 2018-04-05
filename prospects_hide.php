<?php
include "includes/functions.php";

for($x=0;$x<sizeof($_SESSION['list_prospect_id']);$x++){
  $pid = $_SESSION['list_prospect_id'][$x];
  $sql = "SELECT prospect_hidden from prospects where prospect_id='$pid'";
  $foo = getsingleresult($sql);
  if($foo==0) $new = 1;
  if($foo==1) $new = 0;
  $sql = "UPDATE prospects set prospect_hidden='$new' where prospect_id='$pid'";
  executeupdate($sql);
}

meta_redirect("contacts.php");
?>