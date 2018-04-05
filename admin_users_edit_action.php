<?php
include "includes/functions.php";

$user_id = $_POST['user_id'];
if($user_id != "new"){
  $sql = "SELECT master_id from users where user_id='$user_id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
}

$admin = $_POST['admin'];
if($admin != 1) $admin=0;



$firstname = go_escape_string($_POST['firstname']);
$lastname = go_escape_string($_POST['lastname']);
$company = go_escape_string($_POST['company']);
$email = go_escape_string($_POST['email']);
$password = go_escape_string($_POST['password']);
$office = go_escape_string($_POST['office']);
$extension = go_escape_string($_POST['extension']);
$title = go_escape_string($_POST['title']);
$cellphone = go_escape_string($_POST['cellphone']);
$cell_id = go_escape_string($_POST['cell_id']);
$submit1 = go_escape_string($_POST['submit1']);
$googlemap_zipcode = go_escape_string($_POST['googlemap_zipcode']);
$user_level = go_escape_string($_POST['user_level']);
$language = go_escape_string($_POST['language']);
$vehicle_id = go_escape_string($_POST['vehicle_id']);
$signature_block = go_escape_string($_POST['signature_block']);

$alert_inspection_approval = go_escape_string($_POST['alert_inspection_approval']);
$alert_inspection_approval_email = go_escape_string($_POST['alert_inspection_approval_email']);
$alert_inspection_approval_text = go_escape_string($_POST['alert_inspection_approval_text']);
  
$alert_dispatch_approval = go_escape_string($_POST['alert_dispatch_approval']);
$alert_dispatch_approval_email = go_escape_string($_POST['alert_dispatch_approval_email']);
$alert_dispatch_approval_text = go_escape_string($_POST['alert_dispatch_approval_text']);

if($alert_inspection_approval != 1) $alert_inspection_approval = 0;
if($alert_inspection_approval_email != 1) $alert_inspection_approval_email = 0;
if($alert_inspection_approval_text != 1) $alert_inspection_approval_text = 0;
if($alert_dispatch_approval != 1) $alert_dispatch_approval = 0;
if($alert_dispatch_approval_email != 1) $alert_dispatch_approval_email = 0;
if($alert_dispatch_approval_text != 1) $alert_dispatch_approval_text = 0;

if($alert_dispatch_approval_text==1 || $alert_dispatch_approval_email==1) $alert_dispatch_approval = 1;
if($alert_inspection_approval_text==1 || $alert_inspection_approval_email==1) $alert_inspection_approval = 1;

$alert_inspection_scheduled_text = go_escape_string($_POST['alert_inspection_scheduled_text']);
if($alert_inspection_scheduled_text != "1") $alert_inspection_scheduled_text = 0;
$alert_inspection_scheduled_email = go_escape_string($_POST['alert_inspection_scheduled_email']);
if($alert_inspection_scheduled_email != "1") $alert_inspection_scheduled_email = 0;

$alert_meeting_text = go_escape_string($_POST['alert_meeting_text']);
if($alert_meeting_text != "1") $alert_meeting_text = 0;
$alert_meeting_email = go_escape_string($_POST['alert_meeting_email']);
if($alert_meeting_email != "1") $alert_meeting_email = 0;

$alert_contact_text = go_escape_string($_POST['alert_contact_text']);
if($alert_contact_text != "1") $alert_contact_text = 0;
$alert_contact_email = go_escape_string($_POST['alert_contact_email']);
if($alert_contact_email != "1") $alert_contact_email = 0;

$customer_portal_contact = go_escape_string($_POST['customer_portal_contact']);
if($customer_portal_contact != 1) $customer_portal_contact = 0;
$gets_sdemail = go_escape_string($_POST['gets_sdemail']);
if($gets_sdemail != "1") $gets_sdemail = 0;
$on_sdemail_list = go_escape_string($_POST['on_sdemail_list']);
if($on_sdemail_list != "1") $on_sdemail_list = 0;

$always_require_approval = go_escape_string($_POST['always_require_approval']);
if($always_require_approval != "1") $always_require_approval = 0;

$bcc_productionmeeting = go_escape_string($_POST['bcc_productionmeeting']);
if($bcc_productionmeeting != "1") $bcc_productionmeeting = 0;
$bcc_opm = go_escape_string($_POST['bcc_opm']);
if($bcc_opm != "1") $bcc_opm = 0;


$enabled = go_escape_string($_POST['enabled']);
if($enabled != "1") $enabled = 0;

$irep = go_escape_string($_POST['irep']);
if($irep != "1") $irep = 0;

$can_export = go_escape_string($_POST['can_export']);
if($can_export != "1") $can_export = 0;

$can_email_q = go_escape_string($_POST['can_email_q']);
if($can_email_q != "1") $can_email_q = 0;
$can_edit_ops = go_escape_string($_POST['can_edit_ops']);
if($can_edit_ops != "1") $can_edit_ops = 0;
$sales_activity_report = go_escape_string($_POST['sales_activity_report']);
if($sales_activity_report != "1") $sales_activity_report = 0;


$fcs_leakcheck = go_escape_string($_POST['fcs_leakcheck']);
if($fcs_leakcheck != "1") $fcs_leakcheck = 0;

$servicemen = go_escape_string($_POST['servicemen']);
if($servicemen != "1") $servicemen = 0;


$forcetime = go_escape_string($_POST['forcetime']);
if($forcetime != "1") $forcetime = 0;
$forcetime_start = go_escape_string($_POST['forcetime_start']);
$forcetime_end = go_escape_string($_POST['forcetime_end']);



$require_final_approval = go_escape_string($_POST['require_final_approval']);
if($require_final_approval != 1) $require_final_approval = 0;

$require_pre_approval = go_escape_string($_POST['require_pre_approval']);
if($require_pre_approval != 1) $require_pre_approval = 0;

$require_sd_approval = go_escape_string($_POST['require_sd_approval']);
if($require_sd_approval != 1) $require_sd_approval = 0;

$programming_schedule = go_escape_string($_POST['programming_schedule']);
if($programming_schedule != 1) $programming_schedule = 0;
$programming_alert = go_escape_string($_POST['programming_alert']);
if($programming_alert != 1) $programming_alert = 0;


$always_supercali = go_escape_string($_POST['always_supercali']);
if($always_supercali != 1) $always_supercali = 0;
$on_call_report = go_escape_string($_POST['on_call_report']);
if($on_call_report != 1) $on_call_report = 0;
$met1_goal = go_escape_string($_POST['met1_goal']);
$met2_goal = go_escape_string($_POST['met2_goal']);
$met3_goal = go_escape_string($_POST['met3_goal']);

$group_list = $_POST['group_list'];
$subgroup_list = $_POST['subgroup_list'];

$prospects_per_page = go_escape_string($_POST['prospects_per_page']);
$sd_results_per_page = go_escape_string($_POST['sd_results_per_page']);

$resource = go_escape_string($_POST['resource']);

if($resource != 1) $resource = 0;
if($resource==1) {
  $admin = 0;
  $enabled = 1;
  $firstname = $company;
  $lastname = "";
  $alert_inspection_approval = 0;
  $alert_inspection_approval_email = 0;
  $alert_inspection_approval_text = 0;
  $alert_dispatch_approval = 0;
  $alert_dispatch_approval_email = 0;
  $alert_dispatch_approval_text = 0;
  
  $alert_inspection_scheduled=0;
  $servicemen=0;
  $programming_schedule=0;
  $forcetime=0;
  $require_pre_approval=0;
  $require_final_approval=0;
  $require_sd_approval=0;
  $always_supercali = 0;
  
}

if($user_level=="Manager"){
  $can_edit_ops = 1;
}
if($admin==1){
  $can_edit_ops = 1;
}

if($submit1 != ""){
  if($user_id=="new"){
    for($x=0;$x<=9;$x++){
      $chars[] = $x;
    }

    for($x=65;$x<=90;$x++){
      $chars[] = chr($x);
    }

    for($x=97;$x<=122;$x++){
      $chars[] = chr($x);
    }
	for($x=0;$x<25;$x++){
      $y = rand(0, sizeof($chars));
	  $token .= $chars[$y];
    }
	$sql = "SELECT user_id from users where email=\"$email\"";
	$test = getsingleresult($sql);
	if($test){
	  $_SESSION['sess_msg'] = "A user with the email of $email already exists in the system.";
	  meta_redirect("admin_users_edit.php?user_id=new");
	}

	$sql = "INSERT into users(master_id, email) values('" . $SESSION_MASTER_ID . "', \"$email\")";
	executeupdate($sql);
	$user_id = go_insert_id();

  }
  
    $sql = "UPDATE users set firstname=\"$firstname\", lastname=\"$lastname\", email=\"$email\", ";
	if($password != "") $sql .= " password = \"" . md5($password) . "\", ";
	$sql .= " admin='$admin', 
	enabled=\"$enabled\", office=\"$office\", extension=\"$extension\", 
	title=\"$title\", cellphone=\"$cellphone\",
	forcetime='$forcetime', forcetime_start='$forcetime_start', forcetime_end='$forcetime_end', 
	fcs_leakcheck='$fcs_leakcheck', servicemen='$servicemen', cell_id='$cell_id',
	alert_dispatch_approval='$alert_dispatch_approval', alert_dispatch_approval_email='$alert_dispatch_approval_email', alert_dispatch_approval_text='$alert_dispatch_approval_text',
	alert_inspection_approval='$alert_inspection_approval', alert_inspection_approval_email='$alert_inspection_approval_email', alert_inspection_approval_text='$alert_inspection_approval_text',
	require_pre_approval='$require_pre_approval', require_final_approval='$require_final_approval', require_sd_approval='$require_sd_approval', 
	alert_inspection_scheduled_text='$alert_inspection_scheduled_text', alert_inspection_scheduled_email='$alert_inspection_scheduled_email', programming_schedule='$programming_schedule', 
	on_call_report='$on_call_report', met1_goal=\"$met1_goal\", met2_goal=\"$met2_goal\", met3_goal=\"$met3_goal\", googlemap_zipcode=\"$googlemap_zipcode\", resource=\"$resource\", 
	always_supercali='$always_supercali', customer_portal_contact='$customer_portal_contact', user_level='$user_level', irep='$irep', gets_sdemail='$gets_sdemail', 
	alert_meeting_text='$alert_meeting_text', alert_meeting_email='$alert_meeting_email', alert_contact_text='$alert_contact_text', alert_contact_email='$alert_contact_email', 
	always_require_approval='$always_require_approval', language=\"$language\", vehicle_id=\"$vehicle_id\", on_sdemail_list=\"$on_sdemail_list\", can_export='$can_export',
	bcc_productionmeeting='$bcc_productionmeeting', bcc_opm='$bcc_opm', can_email_q='$can_email_q', can_edit_ops='$can_edit_ops', programming_alert='$programming_alert',
	prospects_per_page=\"$prospects_per_page\", signature_block=\"$signature_block\", sales_activity_report='$sales_activity_report', sd_results_per_page=\"$sd_results_per_page\"
	where user_id='$user_id' and master_id='" . $SESSION_MASTER_ID . "'";
	executeupdate($sql);
	
	if($SESSION_USE_GROUPS){
	if($SESSION_GROUPS=="" && $SESSION_SUBGROUPS==""){ // only core users can do group assignments
	  $groups = "";
	  if(is_array($group_list)){
        for($x=0;$x<sizeof($group_list);$x++){
          $groups .= "," . $group_list[$x] . ",";
        }
	  }
  
      $subgroups = "";
	  if(is_array($subgroup_list)){
        for($x=0;$x<sizeof($subgroup_list);$x++){
          $subgroups .= "," . $subgroup_list[$x] . ",";
        }
	  }
  
      $sql = "UPDATE users set groups='$groups', subgroups='$subgroups' where user_id='$user_id' and master_id='" . $SESSION_MASTER_ID . "'";
      executeupdate($sql);
	}
	}
  
  
  if (is_uploaded_file($_FILES['photo']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['photo']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("admin_users_edit.php?user_id=$user_id");
    }
  }
  if (is_uploaded_file($_FILES['photo']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['photo']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['photo']['tmp_name'], $UPLOAD . "temp/". $filename);
  	resizeimage($UPLOAD . "temp/$filename", $UPLOAD . "headshots/", 75);
  	@unlink($UPLOAD . "temp/". $filename);
	
	$sql = "UPDATE users set photo='$filename' where user_id='$user_id'";
	executeupdate($sql);
  }
  
  if (is_uploaded_file($_FILES['signature']['tmp_name']))
  {
    if(!(is_image_valid($_FILES['signature']['name']))){
	  $_SESSION['sess_msg'] = "You can only upload jpg, gif, or png images";
	  meta_redirect("admin_users_edit.php?user_id=$user_id");
    }
  }
  if (is_uploaded_file($_FILES['signature']['tmp_name']))
  {
	
	$ext = explode(".", $_FILES['signature']['name']);
  	$ext = array_pop($ext);
  	$filename = uniqueTimeStamp() . "." . $ext;
	move_uploaded_file($_FILES['signature']['tmp_name'], $UPLOAD . "headshots/". $filename);
	
	$sql = "UPDATE users set signature='$filename' where user_id='$user_id'";
	executeupdate($sql);
  }
}

if($_SESSION['temp_redirect'] != ""){
  $rd = $_SESSION['temp_redirect'];
  $_SESSION['temp_redirect'] = "";
  meta_redirect($rd);
}

meta_redirect("admin_users.php");
?>
