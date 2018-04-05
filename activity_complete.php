<?php include "includes/header.php"; 

$user_id = $SESSION_USER_ID;

$act_id = $_GET['act_id'];
if($act_id=="") meta_redirect("welcome.php");

$redirect = $_GET['redirect'];
$redirect = go_reg_replace("\*", "&", $redirect);

$sql = "SELECT *, date_format(date, \"%m/%d/%Y\") as datepretty from activities where act_id='$act_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$property_id = $record['property_id'];
$prospect_id = $record['prospect_id'];
$event = stripslashes($record['event']);
$contact = stripslashes($record['contact']);
$regarding = stripslashes($record['regarding']);
$tm = $record['tm'];

$sql = "SELECT concat(firstname, ' ', lastname) as fullname, email from users where user_id='" . $SESSION_USER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$fullname = stripslashes($record['fullname']);
$useremail = stripslashes($record['email']);

$hourpretty = date("g");
  $minutepretty = date("i");
  $ampm = date("A");

$sql = "SELECT property_type from prospects where prospect_id='$prospect_id'";
$test_type = getsingleresult($sql);
if(go_reg("Contractor", $test_type)){
  $show_properties = 0;
}
else {
  $show_properties = 1;
}
 
?>
<script>
function met_options(x){
<?php if($SESSION_MASTER_ID==1 && $event=="Contact"){ ?>
  if(x.value=="Objective Met"){
    document.getElementById("met_options_area").style.display="";
	document.getElementById("user_options_area").style.display="";
  }
  else {
    document.getElementById("met_options_area").style.display="none";
	document.getElementById("user_options_area").style.display="none";
  }
<?php } ?>
}

</script>   
<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
  <div id="debug_act" style="display:none;"></div>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>

<form action="activity_complete_action.php"  method="post" onsubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="act_id" value="<?=$act_id?>">
<input type="hidden" name="redirect" value="<?=$redirect?>">
<input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<table class="main">
<tr>
<td colspan="2">
<?=$event?> with <?=$contact?> regarding <?=$regarding?>
</td>
</tr>
<tr>
<td align="right">Result</td>
<td>
<select name="act_result" onchange="met_options(this)">
<option value="Completed">Completed</option>
<option value="Attempted">Attempted</option>
<?php //if($SESSION_MASTER_ID==1){?>
<option value="Objective Met">Objective Met</option>
<?php //} ?>
</select>
</td>
</tr>
<?php if($SESSION_MASTER_ID==1 && $event=="Contact"){ ?>
<tr id="met_options_area" style="display:none;">
<td align="right">Objective Met:</td>
<td>
<select name="met_id">
<?php
$sql = "SELECT * from activities_met_options order by met_id";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['met_id']?>"><?=stripslashes($record['met_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr id="user_options_area" style="display:none;">
<td align="right">Assign to:</td>
<td>
<select name="user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id=1 and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$SESSION_USER_ID) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<?php } ?>

<?php if($prospect_id != 0){ ?>

<tr>
<td align="right" valign="top">
Notes</td>
<td><textarea name="notes" cols="40" rows="5"></textarea></td>
</tr>
<?php } ?>
</table>

<input type="hidden" name="no_new_act" value="1">


<table class="main">
<tr>
<td colspan="2"><input type="checkbox" name="schedule_next_act" value="1">Schedule another activity

</td>
</tr>

<tr>
<td colspan="2">
<input type="submit" name="submit1" value="Complete Activity">
</td>
</tr>
</table>
</form>
  </div>
</div>
</div>
<script>

//event_change(document.form1.elements["event"]);
</script>
<?php include "includes/footer.php"; ?>