<?php
exit;
include "includes/functions.php";

$handle = @fopen("def_list_spanish.csv", "r");
if ($handle) {
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        $items = explode("\t", $buffer);
		
		$def_name_spanish = chop($items[0]);
		$def_name = chop($items[1]);
		

		$sql = "SELECT id from def_list where def_name=\"$def_name\"";
		$old_id = getsingleresult($sql);
		if($old_id != ""){
		 $sql = "UPDATE def_list set def_name_spanish=\"$def_name_spanish\" where id='$old_id'";
		 executeupdate($sql);
		}
		

		
	}
	fclose($handle);
}


?>