<?php include "includes/header_white.php"; ?>
<div class="main">
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div style="color:red;"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}

$sent = $_GET['sent'];
if($sent=="") $sent = 0;
if($sent==0){
  $sentlink = "<a href='" . $_SERVER['SCRIPT_NAME'] . "?sent=1'>View Sent Email</a>";
}
else {
  $sentlink = "<a href='" . $_SERVER['SCRIPT_NAME'] . "?sent=0'>View Unsent Email</a>";
}

$master_id = $_GET['master_id'];
$to = $_GET['to'];
$from = $_GET['from'];
$searchby = $_GET['searchby'];
$custom_startdate = $_GET['custom_startdate'];
$custom_enddate = $_GET['custom_enddate'];
$timerange = $_GET['timerange'];
$custom_starttime = $_GET['custom_starttime'];
$custom_endtime = $_GET['custom_endtime'];
$custom_starttime_ampm = $_GET['custom_starttime_ampm'];
$custom_endtime_ampm = $_GET['custom_endtime_ampm'];

$sql = "SELECT email_threshhold from global_variables";
$email_threshhold = getsingleresult($sql);

$sql = "SELECT can_email_q from users where user_id='" . $SESSION_USER_ID . "'";
$CAN_EMAIL_Q = getsingleresult($sql);

?>
<script src="includes/calendar.js"></script>
<script>
function filter(){
  master_id = document.getElementById("master_id").value;
  from = document.getElementById("from_field").value;
  to = document.getElementById("to_field").value;
  searchby = document.getElementById("searchby").value;
  custom_startdate = document.getElementById("custom_startdate").value;
  custom_enddate = document.getElementById("custom_enddate").value;
  
  if(document.getElementById("timerange").checked==true){
    use_timerange=1;
  }
  else {
    use_timerange=0;
  }
  custom_starttime = document.getElementById("custom_starttime").value;
  custom_endtime = document.getElementById("custom_endtime").value;
  custom_starttime_ampm = document.getElementById("custom_starttime_ampm").value;
  custom_endtime_ampm = document.getElementById("custom_endtime_ampm").value;
  
  url = "tool_emailq_filter.php?master_id=" + master_id + "&from=" + from + "&to=" + to + "&sent=<?=$sent?>&searchby=" + searchby + "&custom_startdate=" + custom_startdate + "&custom_enddate=" + custom_enddate;
  if(use_timerange==1){
  url = url + "&use_timerange=" + use_timerange + "&custom_starttime=" + custom_starttime + "&custom_endtime=" + custom_endtime + "&custom_starttime_ampm=" + custom_starttime_ampm + "&custom_endtime_ampm=" + custom_endtime_ampm;
  }
	url=url+"&sid="+Math.random();
	//document.getElementById("emaildebug").innerHTML = "<a href='" + url + "' target='_blank'>" + url + "</a>";
	//alert(url);
        // Create new JS element
		//document.getElementById('debug').innerHTML = url;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function clearfilters(){
  document.getElementById('master_id').value=0;
  document.getElementById('to_field').value="";
  document.getElementById('from_field').value="";
  document.getElementById('custom_startdate').value="";
  document.getElementById('custom_enddate').value="";
  document.getElementById('custom_starttime').value="";
  document.getElementById('custom_endtime').value="";
  document.getElementById('searchby').value="todate";
  document.getElementById('timerange').checked=false;
  
  document.getElementById("customdate").style.display="none";
  document.getElementById("customtime").style.display="none";
  
  filter();
}

function colon(x){
  y = x.value;
  var testx = y.search(":");
  if(testx==-1){
    x.value = y + ":00";
  }
}

function selectRecord(id, x, xclass){
  if(x.checked==true){
    document.getElementById(id).setAttribute("class", "hilite");
  }
  else {
    document.getElementById(id).setAttribute("class", xclass);
  }
}

function selectRecordRow(id, xclass){
  x = document.getElementById("check_" + id);
  if(x.checked==false){
    document.getElementById(id).setAttribute("class", "hilite");
	x.checked=true;
  }
  else {
    document.getElementById(id).setAttribute("class", xclass);
	x.checked=false;
  }
}

function SetChecked(x,chkName) {
  dml=document.forms["emailform"];
  len = dml.elements.length;
  var i=0;
  var counter=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
	  counter++;
      dml.elements[i].checked=x.checked;
	  z = dml.elements[i].value;
	  if(x.checked==true){
		document.getElementById(z).setAttribute("class", "hilite");
	  }
	  else {
	    if(counter % 2){
		  document.getElementById(z).setAttribute("class", "altrow");
		}
		else {
		  document.getElementById(z).setAttribute("class", "mainrow");
		}
	  }
    }
  }
}

function Openwin(x){
  grayOut(true, {'zindex':'50', 'opacity':'25'});
  document.getElementById(x).style.display="block";
}

function Closewin(x){
  grayOut(false);
  document.getElementById(x).style.display="none";
}

function showMessages(){
  var form="emailform";
  var chkName="email_ids[]";
  var selected = "";
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false && dml.elements[i].checked==true) {
      selected =selected + dml.elements[i].value + ",";
    }
  }
  
  url = "tool_emailq_messages.php?selected=" + selected + "&action=view";
	url=url+"&sid="+Math.random();
	//document.getElementById("emaildebug").innerHTML = "<a href='" + url + "' target='_blank'>" + url + "</a>";
	//alert(url);
        // Create new JS element
		//document.getElementById('debug').innerHTML = url;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function showMessagesNext(id){

  
  url = "tool_emailq_messages.php?action=next&message=" + id;
	url=url+"&sid="+Math.random();
	//document.getElementById("emaildebug").innerHTML = "<a href='" + url + "' target='_blank'>" + url + "</a>";
	//alert(url);
        // Create new JS element
		//document.getElementById('debug').innerHTML = url;
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function go_form(action){
  document.getElementById('submit1').value=action;
  document.emailform.submit();
}

function datefilter_change(x){
  if(x.value=="Custom"){
    document.getElementById("customdate").style.display="";
  }
  else {
    document.getElementById("customdate").style.display="none";
  }
}

function timerange_display(x){
  if(x.checked==true){
    document.getElementById("customtime").style.display="";
  }
  else {
    document.getElementById("customtime").style.display="none";
  }
}
</script>
<script src="includes/grayout.js"></script>
<div id="emaildebug"></div>

<form name="emailform" action="tool_emailq_action.php" method="post">
<input type="hidden" name="submit1" id="submit1" value="">
<input type="hidden" name="sent" value="<?=$sent?>">

<div style="width:100%; position:relative;">
<div style="float:left; width:33%;">
<strong>Email Queue</strong>
</div>
<div style="float:left; width:33%;">
<?php if($CAN_EMAIL_Q){?>
Require send approval emails > <input type="text" name="email_threshhold" value="<?=$email_threshhold?>" size="2">
<input type="button" name="buttonthreshhold" value="Update" onclick="go_form('threshhold')">
<?php } else { ?>
&nbsp;
<?php } ?>
</div>
<div style="float:left; width:33%;">
<a href="javascript:showMessages()">view selected</a> &nbsp; &nbsp; &nbsp; 
<?php if($sent==0){ ?>
<a href="javascript:go_form('delete')">delete selected</a>  &nbsp; &nbsp; &nbsp; 
<a href="javascript:go_form('send')">send selected</a> &nbsp; &nbsp; &nbsp; 
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>




<div style="width:100%; position:relative;">
<div style="float:left; padding-right:80px;">
<table class="main">
<tr>
<td align="right">Contractor:</td>
<td>
<select name="master_id" id="master_id" onchange="filter()">
<option value="0">All</option>
<?php
$sql = "SELECT master_id, master_name from master_list where active=1 order by master_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['master_id']?>"<?php if($master_id==$record['master_id']) echo " selected";?>><?=stripslashes($record['master_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<tr>
<td align="right">From:</td>
<td><input type="text" name="from" id="from_field" onkeyup="filter()" value="<?=$from?>"></td>
</tr>
<tr>
<td align="right">To:</td>
<td><input type="text" name="to" id="to_field" onkeyup="filter()" value="<?=$to?>"></td>
</tr>
</table>
</div>

<div style="float:left; padding-right:80px;">
Date Range:
<select name="searchby" id="searchby" onchange="datefilter_change(this)">
<?php if($searchby=="") $searchby="thisweek"; ?>
<option value="todate"<?php if($searchby=="todate") echo " selected"; ?>>To Date</option>
<option value="today"<?php if($searchby=="today") echo " selected"; ?>>Today</option>
<option value="yesterday"<?php if($searchby=="yesterday") echo " selected"; ?>>Yesterday</option>
<option value="thisweek"<?php if($searchby=="thisweek") echo " selected"; ?>>This Week</option>
<option value="lastweek"<?php if($searchby=="lastweek") echo " selected"; ?>>Last Week</option>
<option value="thismonth"<?php if($searchby=="thismonth") echo " selected"; ?>>This Month</option>
<option value="lastmonth"<?php if($searchby=="lastmonth") echo " selected"; ?>>Last Month</option>
<option value="ytd"<?php if($searchby=="ytd") echo " selected"; ?>>Year to Date</option>
<option value="Custom"<?php if($searchby=="Custom") echo " selected"; ?>>Custom</option>
<?php
for($x=2011;$x<date("Y");$x++){
  ?>
  <option value="<?=$x?>"<?php if($searchby==$x) echo " selected"; ?>><?=$x?></option>
  <?php
}
?>
</select>
  <div id="customdate" <?php if($searchby != "Custom") {?> style="display:none;"<?php } ?>>
  <table class="main">
  <tr>
  <td>
  From:
  </td>
  <td>
  <input size="10" type="text" name="custom_startdate" id="custom_startdate" value="<?=$custom_startdate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('custom_startdate',0)" align="absmiddle">
  </td>
  </tr>
  <tr>
  <td>
  To:
  </td>
  <td>
  <input size="10" type="text" name="custom_enddate" id="custom_enddate" value="<?=$custom_enddate?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('custom_enddate',0)" align="absmiddle">
  </td>
  </tr>
  </table>
  </div>

</div>

<div style="float:left; width:250px;">
Time Range 
<input type="checkbox" name="timerange" value="1"<?php if($timerange) echo " checked";?> onchange="timerange_display(this)" id="timerange">
  <div id="customtime" <?php if($timerange != 1) {?> style="display:none;"<?php } ?>>
  <input type="text" size="4" name="custom_starttime" id="custom_starttime" value="<?=$custom_starttime?>" onblur="colon(this)">
  <select name="custom_starttime_ampm" id="custom_starttime_ampm">
  <option value="AM"<?php if($custom_starttime_ampm=="AM") echo " selected";?>>AM</option>
  <option value="PM"<?php if($custom_starttime_ampm=="PM") echo " selected";?>>PM</option>
  </select>
  to
  <input type="text" size="4" name="custom_endtime" id="custom_endtime" value="<?=$custom_endtime?>" onblur="colon(this)">
  <select name="custom_endtime_ampm" id="custom_endtime_ampm">
  <option value="AM"<?php if($custom_endtime_ampm=="AM") echo " selected";?>>AM</option>
  <option value="PM"<?php if($custom_endtime_ampm=="PM") echo " selected";?>>PM</option>
  </select>
  <br>
  <em>Ex: 9:00 AM to 5:00 PM</em>
  </div>
</div>

<div style="float:left; padding-right:80px;">
<input type="button" name="buttonfilter" value="Add Date/Time Filters" onclick="filter()">
</div>

<div style="float:left; padding-right:80px;">
<input type="button" name="buttonclearfilters" value="Clear Filters" onclick="clearfilters()">
</div>

</div>
<div style="clear:both;"></div>




<div style="position:relative;" class="main">
<div style="float:left; width:30px;"><input type='checkbox' onchange="SetChecked(this, 'email_ids[]')"></div>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:200px;"><strong>Timestamp</strong></div>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:350px;"><strong>From</strong></div>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:100px;"><strong>To, Cc, Bcc</strong></div>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:30px;"><strong>#</strong></div>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:350px;"><strong>Target</strong></div>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:300px;"><strong>Subject</strong></div>
<?php if($sent==1){?>
<div style="float:left; width:4px;">&nbsp;</div>
<div style="float:left; width:100px;"><strong>Approved</strong></div>
<?php } ?>
<div style="float:right; padding-right:10px;"><?=$sentlink?></div>
</div>
<div style="clear:both;"></div>

<div style="height:640px; overflow:auto; width:100%;">
<div id="contentdiv">

</div>
</div>
</form>

<div id="messages" style="position:absolute; left:100px; top:0px; z-index:151; display:none; border:2px solid #000000; padding:10px 10px 10px 10px; background-color:#FFFFFF; width:1000px; height:680px; overflow:auto;" class="main">
  
  <div id="messages_content">
  </div>
  <div align="center">
  <a href="javascript:Closewin('messages')">Close</a>
  </div>
  
</div>
<script>filter();</script>
