<?php
include "includes/functions.php";

 $_SESSION[$sess_header . '_contacts2_filterby'] = "0";
  $_SESSION[$sess_header . '_contacts2_zip'] ="";
  $_SESSION[$sess_header . '_contacts2_distance'] = "";
  $_SESSION[$sess_header . '_contacts2_order_by'] = "";
  $_SESSION[$sess_header . '_contacts2_order_by2'] = "";
  $_SESSION[$sess_header . '_contacts2_searchfor'] = "";
  //$_SESSION[$sess_header . '_contacts2_cand_type'] = "";
  $_SESSION[$sess_header . '_contacts2_show_hidden'] = "";
  $_SESSION[$sess_header . '_contacts2_show_all_cand'] = "";
  $_SESSION[$sess_header . '_contacts2_stage_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_status_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_identifier_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_user_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_obj_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_active_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_resource_filter'] = "";
  $_SESSION[$sess_header . '_contacts2_start_record'] = "";
meta_redirect("contacts.php");
?>