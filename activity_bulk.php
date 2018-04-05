<?php include "includes/header.php"; 
//$_SESSION['act'] = "";
 // this is just for fcs login

$act_id="new";

  $user_id = $SESSION_USER_ID;
  $topfiller = "Add New Activity";
  $phone_number = "1-800-391-1709";
  $hourpretty = date("g");
  $minutepretty = date("i");
  $ampm = date("A");
  // dennis says default to 00:00
  $hourpretty = "00";
  $minutepretty = "00";
  $ampm = "AM";
  $event="Contact"; //jjb wanted this to show up as default on new activities



?>
<script src="includes/calendar.js"></script>
<script>
function check_repeat_type(x){
	y=x.value;
	if(y=="Span"){
		document.getElementById('span_date').style.display="";
	}
	else{
		document.getElementById('span_date').style.display="none";
	}
}





function cleardate(f){
  f.value="";
}



function change_property(){
  x=document.form1.prospect_id.value;
  y=document.form1.property_id.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?act_id=<?=$act_id?>&prospect_id=" + x + "&property_id=" + y;
}


function event_change(x){
  document.getElementById('regular').style.display="none";
  document.getElementById('div_repeat_act').style.display="none";
  document.getElementById('for_area').style.display="none";
  
  switch(document.form1["event"].value){
  case "Conference Call":{
	document.getElementById('for_area').style.display="";
	break;
  }
  case "Send Info":{
	document.getElementById('for_area').style.display="";
	break;
  }
  case "Get Bids":{
	document.getElementById('for_area').style.display="";
	break;
  }
  case "Send Email":{
	document.getElementById('for_area').style.display="";
	break;
  }
  case "OPM":{
	document.getElementById('for_area').style.display="";
	break;
  }
  case "Bid Presentation":{
    document.getElementById('regular').style.display="";
	document.getElementById('for_area').style.display="";
	break;
  }
  case "Send Letter":{
	document.getElementById('for_area').style.display="";
	break;
  }
  
  case "Calendar - Other":{
	document.getElementById('div_repeat_act').style.display="";
    document.getElementById('regular').style.display="";
	document.getElementById('for_area').style.display="";
	break;
  }
  
  case "Operations":{
	break;
  }
  
  case "Production Meeting":{
	document.getElementById('for_area').style.display="";
	break;
  }
  
  default:{
    document.getElementById('regular').style.display="";
	document.getElementById('for_area').style.display="";
	break;
  }
  
  }

  div = document.getElementById('special_item');
  div.innerHTML = "<img src=\"images/loading.gif\">Loading... Please Wait";

  y = x.value;
  url = "activity_event_pop.php?prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>&event=" + y;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
		//document.getElementById('debug_act').style.display="";
		//document.getElementById('debug_act').innerHTML = url;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
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

<form action="activity_bulk_action.php"  method="post"  name="form1">
<input type="hidden" name="act_id" value="<?=$act_id?>">
<input type="hidden" name="redirect" value="<?=$redirect?>">
<table class="main">
<tr>
<td align="right">Event</td>
<td>
<select name="event" id="event" onchange="event_change(this)">

<option value="Contact"<?php if($event == "Contact") echo " selected"; ?>>Contact</option>
<option value="Meeting"<?php if($event == "Meeting") echo " selected"; ?>>Meeting</option>
</select>
</td>
</tr>


<tr id="for_area">
<td align="right">For</td>
<td>
<select name="user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
</td>
</tr>

</table>

<table class="main" id="regular">
<tr>
<td align="right">Date</td>
<td>
<input size="10" type="text" name="datepretty" <?php if($datepretty=="") { echo" value=\"" . date("m/d/Y", time() + (7 * 24 * 60 * 60)) . "\" onClick=\"cleardate(this);\""; } else {?> value="<?=$datepretty?>"<?php } ?>> 
<img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">
<br>
<input type="text" name="hourpretty" value="<?=$hourpretty?>" size="2">:
<input type="text" name="minutepretty" value="<?=$minutepretty?>" size="2">
<select name="ampm">
<option value="AM"<?php if($ampm=="AM") echo " selected";?>>AM</option>
<option value="PM"<?php if($ampm=="PM") echo " selected";?>>PM</option>
</select>
<br>
<?php /*
<input type="checkbox" name="rollover" value="1" checked="checked">Automatically rollover to today's date
*/?>
<div id="div_repeat_act" style="display:none;">
Repeat frequency: 
<select name="repeat_type" onchange="check_repeat_type(this)">
<option value="Never"<?php if($repeat_type=="Never") echo " selected";?>>Never</option>
<option value="Daily"<?php if($repeat_type=="Daily") echo " selected";?>>Daily</option>
<option value="Weekly"<?php if($repeat_type=="Weekly") echo " selected";?>>Weekly</option>
<option value="Monthly"<?php if($repeat_type=="Monthly") echo " selected";?>>Monthly</option>
<option value="Yearly"<?php if($repeat_type=="Yearly") echo " selected";?>>Yearly</option>
<option value="Span"<?php if($repeat_type=="Span") echo " selected";?>>Span</option>
</select>
&nbsp; 
<span id="span_date" <?php if($repeat_type != "Span") echo "style=\"display:none;\"";?>>
Until: 
<input size="10" type="text" name="spanpretty" value="<?=$spanpretty?>"> 
<img src="images/calendar.gif" onClick="KW_doCalendar('spanpretty',0)" align="absmiddle">
</span>
</div>

</td>
</tr>
<?php

?>

<tr>
<td align="right">Regarding</td>
<td><input type="text" name="regarding" value="<?=$regarding?>" size="40" maxlength="250"></td>
</tr>
<tr>
<td align="right" valign="top">Details</td>
<td><textarea name="regarding_large" rows="5" cols="50"><?=$regarding_large?></textarea>
</td>
</tr>

</table>
<div id="special_item"></div>

<table class="main">


<tr>
<td colspan="2">&nbsp;

</td>
</tr>

<tr>
<td colspan="2">
<input type="submit" name="submit1" value="<?=$topfiller?>">
</td>
</tr>
</table>
</form>
  </div>
</div>
</div>
<script>

event_change(document.form1.elements["event"]);
</script>
<?php include "includes/footer.php"; ?>