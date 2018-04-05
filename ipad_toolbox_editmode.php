<?php
include "includes/functions.php";

if($_SESSION[$sess_header . '_ipad_toolbox_edit']==1){
  $_SESSION[$sess_header . '_ipad_toolbox_edit'] = 0;
}
else {
  $_SESSION[$sess_header . '_ipad_toolbox_edit'] = 1;
}

meta_redirect("ipad_toolbox.php");
?>