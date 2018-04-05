<?php
include "includes/functions.php";


$sql = "SELECT word from capitalize";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $replace[] = $record['word'];
	  }
$description = "this is the Southwest corner of the epdm.";
echo $description . "<br>";
$description = capitalizer($replace, $description);
echo $description;
?>