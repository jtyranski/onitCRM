<?php include "includes/header_white.php"; 
//$_SESSION['act'] = "";
$prospect_id = $_GET['prospect_id'];
$property_id = $_GET['property_id'];
if($property_id == "") $property_id = 0;
$act_id = $_GET['act_id'];
if($act_id=="") $act_id="new";

$redirect = $_GET['redirect'];
$redirect = go_reg_replace("\*", "&", $redirect);



$event = $_GET['event'];


if($act_id != "new"){
  $sql = "SELECT *, 
  date_format(date, \"%m/%d/%Y\") as datepretty,
  date_format(date, \"%h\") as hourpretty, 
  date_format(date, \"%i\") as minutepretty, 
  date_format(date, \"%p\") as ampm, 
  date_format(span_date, \"%m/%d/%Y\") as spanpretty
  from activities where act_id='$act_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $event = stripslashes($record['event']);
  $contact = stripslashes($record['contact']);
  $regarding = stripslashes($record['regarding']);
  $regarding_large = stripslashes($record['regarding_large']);
  $attachment = stripslashes($record['attachment']);
  if($_GET['property_id'] == "") $property_id = $record['property_id'];
  if($_GET['prospect_id'] == "")$prospect_id = $record['prospect_id'];
  $datepretty = $record['datepretty'];
  $hourpretty = $record['hourpretty'];
  $minutepretty = $record['minutepretty'];
  $ampm = $record['ampm'];
  $topfiller = "Edit Activity";
  $user_id = $record['user_id'];
  $priority = $record['priority'];
  $complete = $record['complete'];
  $act_result = $record['act_result'];
  $repeat_type = $record['repeat_type'];
  $spanpretty = $record['spanpretty'];
  $act_type = stripslashes($record['act_type']);
  $dis_id = $record['dis_id'];
  
  $tm = $record['tm'];
  $tm_user_id = $record['tm_user_id'];
  
  if($event=="Conference Call"){
    $cc_act_id = $record['cc_act_id'];
	$sql = "SELECT *, date_format(date_time, \"%m/%d/%Y\") as cc_date, 
	date_format(date_time, \"%l\") as cc_hour, 
	date_format(date_time, \"%i\") as cc_minute, 
	date_format(date_time, \"%p\") as cc_ampm 
	from activities_conferencecall where act_id='$cc_act_id'";
	$result = executequery($sql);
	$record = go_fetch_array($result);
	$cc_date = stripslashes($record['cc_date']);
	$cc_hour = stripslashes($record['cc_hour']);
	$cc_minute = stripslashes($record['cc_minute']);
	$cc_ampm = stripslashes($record['cc_ampm']);
	$phone_number = stripslashes($record['phone_number']);
	$phone_code = stripslashes($record['phone_code']);
	$users = stripslashes($record['users']);
	$users_email = stripslashes($record['users_email']);
	$guests = stripslashes($record['guests']);
	$guests_email = stripslashes($record['guests_email']);
	$claim_type = stripslashes($record['claim_type']);
  }
  

  
  
}
else {
  $user_id = $SESSION_USER_ID;
  $topfiller = "Add New Activity";
  $phone_number = "1-800-391-1709";
  $sql = "SELECT timezone from master_list where master_id='" . $SESSION_MASTER_ID . "'";
  $tz = getsingleresult($sql);

  $st = time() + ($tz * 3600);
  $hourpretty = date("g", $st);
  $minutepretty = date("i");
  $ampm = date("A", $st);
  // dennis says default to 00:00
  //$hourpretty = "00";
  //$minutepretty = "00";
  //$ampm = "AM";
  $event="Contact"; //jjb wanted this to show up as default on new activities

}
if($prospect_id=="") $prospect_id=0;
$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));

$sql = "SELECT industry from prospects where prospect_id='$prospect_id'";
$industry = getsingleresult($sql);

$sql = "SELECT comres from prospects where prospect_id='$prospect_id'";
$comres = getsingleresult($sql);
if($comres=="R") $residential = 1;
if($residential && $act_id=="new") $event = $RESAPPT; // if new act for a res, default to res appt

$test = GetContacts("", $prospect_id);
if(sizeof($test)==0){
  $create_demo_block = "<input type='checkbox' name='create_demo' value='1' disabled>Create Core Demo ";
  $create_demo_block .= "<input type='button' name='buttondemo' value='Must Create a Contact First' onclick=\"document.location.href='view_company.php?prospect_id=$prospect_id&view=activities'\">";
}
else {
  $create_demo_block = "<input type='checkbox' name='create_demo' value='1'>Create Core Demo ";
}

$sql = "SELECT count(*) from activities where prospect_id='$prospect_id' and complete=0 and display=1 and demo_master_id !=0";
$test = getsingleresult($sql);
if($test){
  $create_demo_block = "";
}
/*
if($_SESSION['act']['event'] != "") $event = $_SESSION['act']['event'];
if($_SESSION['act']['contact'] != "") $contact = $_SESSION['act']['contact'];
if($_SESSION['act']['regarding'] != "") $regarding = $_SESSION['act']['regarding'];
if($_SESSION['act']['property_id'] != "") $property_id = $_SESSION['act']['property_id'];
if($_SESSION['act']['prospect_id'] != "") $prospect_id = $_SESSION['act']['prospect_id'];
if($_SESSION['act']['date'] != "") $datepretty = $_SESSION['act']['date'];
*/

?>
<head>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
    //<![CDATA[



// ======== New map variable ========
    var citymap = {};

  var cityCircle;
  var map;

// =========== Initialize our map, set center, zoom level, hybrid view, UI controls, and allow info window =============
    function initialize(xlat, xlng, zl) {

      // defines zoom, center, map type, etc
      var latlng = new google.maps.LatLng(xlat, xlng);
      var mapOptions = {
        zoom: zl,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.TERRAIN
      };
	  
	  
      map = new google.maps.Map(document.getElementById("map_canvas"),
	mapOptions);

      for (var city in citymap) {
        // Construct the circle for each value in citymap. We scale population by 20.
        var radiusOptions = {
          strokeColor: "#FF0000",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "#000000",
          fillOpacity: 0.0,
          map: map,
          center: citymap[city].center,
          radius: citymap[city].radius
        };
        cityCircle = new google.maps.Circle(radiusOptions);
      }
      
      var georssLayer = new google.maps.KmlLayer("http://www.google.com/latitude/apps/badge/api?user=<?=$googlemap_ids?>&type=kml");
      georssLayer.setMap(map);
	  georssLayer.set('preserveViewport', true);
	  
	  
	  
    }
	
	

// ============ display a new marker ================
    function placeMarker(lat, lng, html, icon) {
	  
	  //alert(foo);
	  switch(icon){
	  case "tack":{
	    image = "<?=$CORE_URL?>googlemap/thumb_red.png";
		imagesize1 = 16;
	    imagesize2 = 16;
		break;
	  }
	  default:{
	    image = "<?=$CORE_URL?>googlemap/ro_public_orange.png";
		imagesize1 = 16;
	    imagesize2 = 16;
		break;
	  }
	  }
     
     var iconimage = new google.maps.MarkerImage(image, null, null, null, new google.maps.Size(imagesize1, imagesize2));

	  var infowindow = new google.maps.InfoWindow({
        content: html
      });

	  var latlng = new google.maps.LatLng(lat, lng);
      var marker = new google.maps.Marker({
        position: latlng, 
        map: map,
		icon: iconimage
      });
	  
	  google.maps.event.addListener(marker, 'click', function() {
        infowindow.open(map,marker);
      });
	  
	  //map.setCenter(location);

    }
	
	function placeBadge(lat, lng, html, icon) {
      var iconimage2 = new google.maps.MarkerImage(icon, null, null, null, new google.maps.Size(24, 24));

	  var infowindow2 = new google.maps.InfoWindow({
        content: html
      });

	  var latlng2 = new google.maps.LatLng(lat, lng);
      var marker2 = new google.maps.Marker({
        position: latlng2, 
        map: map,
		icon: iconimage2
      });
	  
	  google.maps.event.addListener(marker2, 'click', function() {
        infowindow2.open(map,marker2);
      });
	  
	  //map.setCenter(location);

    }

    </script>
	
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

function checkform(f){
  errmsg = "";
  
  <?php if($SESSION_MASTER_ID==1){ ?>
  if(f.create_demo.checked==true){
    if(f.artistName.value=="N/A"){ errmsg += "Please select a user to have this meeting with.\n"; }
  }
  <?php } ?>
  
  //if(f.prospect_id.value==""){ errmsg += "Please select prospect. \n"; }
  //if(f.property_id.value==""){ errmsg += "Please select property. \n"; }
  switch(f["event"].value){
    case "Conference Call":{
	  if(f.cc_date.value==""){ errmsg += "Please enter date. \n"; }
	  if(f.phone_number.value==""){ errmsg += "Please enter conference call phone number. \n"; }
	  if(f.phone_code.value==""){ errmsg += "Please enter conference call phone number code. \n"; }
	  break;
	}
	case "Send Info":{
	  break;
	}
	case "Send Email":{
	  if(f.email_subject.value==""){ errmsg += "Please enter subject. \n"; }
	  if(f.email_message.value==""){ errmsg += "Please enter message. \n"; }
	  break;
	}
	case "Get Bids":{
	  if(f.bid_section_id.value==""){ errmsg += "Please choose a section. \n"; }
	  break;
	}
	case "OPM":{
	  if(f.opm_bid_number.value==""){ errmsg += "Please enter bid number. \n"; }
	  if(f.opm_section_id.value==""){ errmsg += "Please choose a section. \n"; }
	  break;
	}
	default:{
      if(f.datepretty.value=="" || f.datepretty.value=="MM/DD/YYYY"){ errmsg += "Please enter date. \n"; }
      if(f.artistName.value==""){ errmsg += "Please enter contact. \n"; }
      if(f.regarding.value==""){ errmsg += "Please enter what this is regarding. \n"; }
	  break;
    }
  }
  
  
  
  if(errmsg == ""){
    return(true);
  }
  else {
    alert(errmsg);
	return(false);
  }
}

function change_prospect(x){
  y = x.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?act_id=<?=$act_id?>&prospect_id=" + y;
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
  //document.getElementById('div_repeat_act').style.display="none";
  document.getElementById('for_area').style.display="none";
  <?php if($SESSION_MASTER_ID==1){ ?>
	document.getElementById('create_demo_div').style.display="none";
  <?php } ?>
  
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
  
  case "Meeting":{
    document.getElementById('regular').style.display="";
	document.getElementById('for_area').style.display="";
	<?php if($SESSION_MASTER_ID==1){ ?>
	document.getElementById('create_demo_div').style.display="";
	<?php } ?>
	break;
  }
  
  default:{
    document.getElementById('regular').style.display="";
	document.getElementById('for_area').style.display="";
	break;
  }
  
  }
  
  if(document.form1["event"].value=="Inspection"){
    document.getElementById('needs_approval_box').style.display="";
  }
  else{
    //document.getElementById('needs_approval').checked=false;
	document.getElementById('needs_approval_box').style.display="none";
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

<script>

var form='form1'; //Give the form name here

function SetCheckedEmailDiv(val,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=val;
    }
  }
}

function Preview(x){
  y = x.value;
  window.open(y, '', 'width=800,height=600,scrollbars=1;');
}

function ajax_do_email (x, pid) {
  //alert(x + pid);
  
  switch(x){
  case "http://www.roofoptions.com/pfri_consalesflash.php":{
    contmsg = 1;
	break;
  }
  case "http://www.encitegroup.com/roofoptions/beazer_commence.php":{
    contmsg = 2;
	break;
  }
  default:{
    contmsg = 0;
	break;
  }
  }
  
  //alert(contmsg);
    url = "si_letterpop.php?prospect_id=" + pid + "&contmsg=" + contmsg;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function ajax_do_email_content (x) {
  //alert(x);
  
 
  //alert(contmsg);
    url = "activity_email_letterpop.php?property_id=<?=$property_id?>&id=" + x;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function email_preview_message(){
  document.emailpreview.email_message.value = document.form1.email_message.value;
  document.emailpreview.user_id.value = document.form1.user_id.value;
  document.emailpreview.email_html.value = document.form1.email_html.value;
  document.emailpreview.email_company_logo.value = document.form1.email_company_logo.value;
  document.emailpreview.email_footer.value = document.form1.email_footer.value;
  if(document.form1.email_include_signature.checked==true){
    document.emailpreview.email_include_signature.value = 1;
  }
  else {
    document.emailpreview.email_include_signature.value = 0;
  }
  
  
  len = document.form1.elements.length;
  var i=0;
  var foo = "";
  for( i=0 ; i<len ; i++) {
    if (document.form1.elements[i].name=="email_banner" && document.form1.elements[i].checked==true) {
      foo = document.form1.elements[i].value;
    }
  }
  document.emailpreview.email_banner.value = foo;
  var i=0;
  var foo = "";
  for( i=0 ; i<len ; i++) {
    if (document.form1.elements[i].name=="email_movie" && document.form1.elements[i].checked==true) {
      foo = document.form1.elements[i].value;
    }
  }
  document.emailpreview.email_movie.value = foo;
  
 
  document.emailpreview.submit();
  
}
</script>

<script>

function ajax_do (url, qStr) {
    url=url+"?"+qStr; 
    url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
		url2 = "bid_contractor_pop.php";
		url2=url2+"?"+qStr; 
        url2=url2+"&sid="+Math.random();
        // Create new JS element
        var jsel2 = document.createElement('SCRIPT');
        jsel2.type = 'text/javascript';
        jsel2.src = url2;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel2);
		
		// third item is for OPM section pop
		url3 = "activity_opm_pop.php";
		url3=url3+"?"+qStr; 
        url3=url3+"&sid="+Math.random();
        // Create new JS element
        var jsel3 = document.createElement('SCRIPT');
        jsel3.type = 'text/javascript';
        jsel3.src = url3;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel3);
		
		
		// forth item is for OPM contractor pop
		url4 = "activity_opm_pop_contractor.php";
		url4=url4+"?"+qStr; 
        url4=url4+"&sid="+Math.random();
        // Create new JS element
        var jsel4 = document.createElement('SCRIPT');
        jsel4.type = 'text/javascript';
        jsel4.src = url4;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel4);
		
}

function ajax_do_bid_sectionswitch (x) {
 //alert(x);
    url="bid_contractor_pop.php?property_id=<?=$property_id?>&section_id=" + x;
    url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}
		
function ajax_do_test (url, qStr) {
  alert (url + "?" + qStr);
		
}

var form='form1'; //Give the form name here

function SetChecked(val,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=val;
    }
  }
}
</script>

<script>

function ajax_doOPM (url, qStr) {
    url=url+"?"+qStr; 
    url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

var form='form1'; //Give the form name here

function SetChecked(val,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=val;
    }
  }
}

function OPM_del_extraemail(x){
  url = "activity_opm_extraemail.php?go=0&email=" + x;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function OPM_add_extraemail(){
  x = document.form1.opm_extraemail_add.value;
  url = "activity_opm_extraemail.php?go=1&email=" + x;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}
</script>


<script>
function pm_email_preview(){

  propid = document.form1.property_id.value;
  prosid = document.form1.prospect_id.value;
  d = document.form1.pm_datepretty.value;
  h = document.form1.pm_hourpretty.value;
  m = document.form1.pm_minutepretty.value;
  ampm = document.form1.pm_ampm.value;
  //e = document.form1.productionmeeting_email.value;
  u = document.form1.user_id.value;
  b = document.form1.productionmeeting_bid_id.value;
  
  window.open("activity_productionmeeting_emailpreview.php?prospect_id=" + prosid + "&property_id=" + propid + "&date=" + d + "&hour=" + h + "&minute=" + m + "&ampm=" + ampm + "&user_id=" + u + "&bid_id=" + b);
}

function company_header (x) {
  //alert(x);
  y = x.value;
  
 
  //alert(contmsg);
    url = "frame_user_email_company_header.php?id=" + y;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function requireapproval(x){

   y = x.value;
   var e = document.form1["event"].value;
 
  //alert(contmsg);
    url = "activity_edit_require_approval.php?user_id=" + y + "&event=" + e;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function Openwin(x){
  grayOut(true, {'zindex':'50', 'opacity':'25'});
  document.getElementById(x).style.display="";
}

function Closewin(x){
  grayOut(false);
  if(x=="map_wrapper") document.getElementById('map_wrapper').style.top="20px";
  document.getElementById(x).style.display="none";
}

function Closeboth(){
  Closewin('schedzoom');
  Closewin('sched');
  Closewin('map_wrapper');
}

function pop_sched_zoom(user_id, date){

 
  //alert(contmsg);
    url = "activity_edit_pop_sched_zoom.php?act_id=<?=$act_id?>&property_id=<?=$property_id?>&prospect_id=<?=$prospect_id?>&user_id=" + user_id + "&date=" + date;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

  Openwin('schedzoom');
}


function Setwin(){
  timer = setTimeout("Closewin('map_wrapper')",200); // 3 secs
  <?php
$openmap = $_GET['openmap'];
if($openmap==1){?>
timer = setTimeout("Openwin('map_wrapper')",300);
<?php } ?>
  return false;
}

function SetResUser(user_id, date){
  document.getElementById('user_id').value=user_id;
  document.getElementById('datepretty').value=date;
  Closeboth();
}

function ReturnToSched(){
  Closeboth();
  Openwin('sched');
}
</script>   
<script src="includes/grayout.js"></script>
</head>

<body onLoad="Setwin()">
  <div id="debug_act" style="display:none;"></div>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
<?php /*

<form action="activity_email_preview.php" method="post" name="emailpreview" target="_blank">
<input type="hidden" name="email_message" value="">
<input type="hidden" name="user_id" value="">
<input type="hidden" name="email_html" value="">
<input type="hidden" name="email_include_signature" value="">
<input type="hidden" name="email_company_logo" value="">
<input type="hidden" name="email_banner" value="">
<input type="hidden" name="email_movie" value="">
<input type="hidden" name="email_footer" value="">
<div style="display:none;"><input type="submit" name="submit1"></div>
</form>
*/?>
<form action="activity_edit_action.php"  method="post" onSubmit="return checkform(this)" enctype="multipart/form-data" name="form1">
<input type="hidden" name="act_id" value="<?=$act_id?>">
<input type="hidden" name="redirect" value="<?=$redirect?>">
<table class="main">
<tr>
<td align="right">Company</td>
<td>

  <input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
  <?=$company_name?>
</td>
</tr>
<?php /*
<?php if($prospect_id==""){ 
  echo "</table>";
  echo "</form>";
  include "includes/footer.php";
  exit;
}
?>
*/?>
<?php if($prospect_id != 0){ ?>
<tr>
<td align="right">Property</td>
<td>
<select name="property_id">
<?php

  $corp_sql = " corporate = 0 ";

$sql = "SELECT * from properties where prospect_id='$prospect_id' and display=1";
if($SESSION_MASTER_ID != 1) $sql .= " and corporate=0";
$sql .= " order by corporate desc, site_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['property_id']?>"<?php if($property_id == $record['property_id']) echo " selected"; ?>><?=stripslashes($record['site_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>
<?php } ?>


<?php if($complete){ ?>
<input type="hidden" name="complete" value="1">
<tr>
<td align="right">Result</td>
<td>
<select name="act_result">
<option value="Completed"<?php if($act_result=="Completed") echo " selected";?>>Completed</option>
<option value="Attempted"<?php if($act_result=="Attempted") echo " selected";?>>Attempted</option>
<option value="Objective Met"<?php if($act_result=="Objective Met") echo " selected";?>>Objective Met</option>
</select>
</td>
</tr>

<?php } ?>
<tr>
<td align="right">Event</td>
<td>
<?php if($industry){ ?>
<select name="event" id="event" onChange="event_change(this)">
<option value="Contact"<?php if($event == "Contact") echo " selected"; ?>>Contact</option>
<option value="Meeting"<?php if($event == "Meeting") echo " selected"; ?>>Meeting</option>
</select>
<?php } else { ?>
<select name="event" id="event" onChange="event_change(this)">
<?php if($SESSION_RESIDENTIAL && $residential){?>
<option value="<?=$RESAPPT?>"<?php if($event == $RESAPPT) echo " selected"; ?>><?=$RESAPPT?></option>
<?php } ?>
<option value="Inspection"<?php if($event == "Inspection") echo " selected"; ?>><?=$I?></option>
<option value="To Do"<?php if($event == "To Do") echo " selected"; ?>>To Do</option>
<?php
$sql = "SELECT b.activity_name from activities_items a, activities_master b where 
a.activity_master_id = b.activity_master_id and a.master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['activity_name']?>"<?php if($event == $record['activity_name']) echo " selected";?>><?=$record['activity_name']?></option>
  <?php
}
?>

</select>
<?php } ?>
<?php if($SESSION_MASTER_ID==1){ ?>
<span id="create_demo_div" style="display:none;">
<?=$create_demo_block?>
</span>
<?php } ?>
</td>
</tr>
<tr>
<td align="right">Type</td>
<td>
<select name="dis_id">
<?php
if(is_array($SESSION_DIS)){
  $sql = "SELECT dis_id, discipline from disciplines order by dis_id";
  $result = executequery($sql);
  $DIS_NAMES = array();
  while($record = go_fetch_array($result)){
    $xdis_id = $record['dis_id'];
	$DIS_NAMES[$xdis_id] = stripslashes($record['discipline']);
  }
  
  for($x=0;$x<sizeof($SESSION_DIS);$x++){ 
    $xdis_id = $SESSION_DIS[$x]?>
    <option value="<?=$xdis_id?>"<?php if($dis_id==$xdis_id) echo " selected";?>><?=$DIS_NAMES[$xdis_id]?></option>
	<?php
  }
}
?>
</select>
</td>
</tr>
<?php
$sql = "SELECT require_pre_approval from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER and user_id='$user_id'";
$ara = getsingleresult($sql);
?>
<tr id="needs_approval_box" style="display:none;">
<td colspan="2">Require Approval in <?=$MAIN_CO_NAME?> Connect App <input type="checkbox" name="needs_approval" id="needs_approval" value="1"<?php if($ara==1) echo " checked";?>></td>
</tr>

<tr id="for_area">
<td align="right">For</td>
<td>
<select name="user_id" onChange="requireapproval(this)" id="user_id">
<?php
$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_id) echo " selected"; ?>><?=stripslashes($record['fullname'])?></option>
  <?php
}
?>
</select>
<?php if($SESSION_RESIDENTIAL && $residential){?>
<a href="javascript:Openwin('sched')">check schedule</a>
<?php } ?>
</td>
</tr>

</table>

<table class="main" id="regular">
<tr>
<td align="right">Date</td>
<td>
<input size="10" type="text" id="datepretty" name="datepretty" <?php if($datepretty=="") { echo" value=\"" . date("m/d/Y") . "\" onClick=\"cleardate(this);\""; } else {?> value="<?=$datepretty?>"<?php } ?>> 
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
<div id="div_repeat_act">
Repeat frequency: 
<select name="repeat_type" onChange="check_repeat_type(this)">
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
<td align="right" valign="top">With</td>
<td>
<select name="artistName">
<option value="N/A">N/A</option>
<?php
if($property_id){
  $thelist = GetContacts_Property($property_id);
}
else {
  $thelist = GetContacts("", $prospect_id);
}

for($x=0;$x<sizeof($thelist);$x++){
  ?>
  <option value="<?=$thelist[$x]['name']?>"<?php if($contact==$thelist[$x]['name']) echo " selected";?>><?=$thelist[$x]['name']?></option>
  <?php
}
?>
</select>

</td>
</tr>
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
<input type='button' name='buttoncancel' value='Cancel' onClick="top.location.href='view_company.php?prospect_id=<?=$prospect_id?>&view=activities'"> &nbsp;
<input type="submit" name="submit1" value="<?=$topfiller?>">
</td>
</tr>
</table>
</form>
</body>

<?php



function MonthDays($someMonth, $someYear)
    {
        return date("t", strtotime($someYear . "-" . $someMonth . "-01"));
    }

function showSched ($calmonth,$calyear) {
    global $SESSION_MASTER_ID, $RESOURCE_FILTER, $prospect_id, $property_id, $act_id, $RESAPPT;
	
	$sql = "SELECT full_appointment from master_list where master_id='" . $SESSION_MASTER_ID . "'";
	$full_appointment = getsingleresult($sql);
	//$full_appointment = 1;
	
	$headernextmonth = $calmonth + 1;
	if($headernextmonth > 12){
	  $headernextmonth = 1;
	  $headernextyear = $calyear + 1;
	}
	else {
	  $headernextyear = $calyear;
	}
	
	$headerprevmonth = $calmonth - 1;
	if($headerprevmonth < 1){
	  $headerprevmonth = 12;
	  $headerprevyear = $calyear - 1;
	}
	else {
	  $headerprevyear = $calyear;
	}
	
	if(date("n-Y") == $calmonth . "-" . $calyear){
	  $firstday = date("j");
	}
	else {
	  $firstday = 1;
	}
	

	if($firstday < 15){
	  $threemonth = $calmonth + 2;
	  $threeyear = $calyear;
	  if($threemonth > 12){
	    $threemonth -= 12;
	    $threeyear += 1;
	  }
	  $threeday = MonthDays($threemonth, $threeyear);
	  $cal_end = date("Y-m-d", mktime(0, 0, 0, $threemonth, $threeday, $threeyear));
	  $cal_begin = date("Y-m-d", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	  $daterange = "b.date >= '$calyear-$calmonth-$firstday' and b.date <= '$threeyear-$threemonth' ";
	  $showmonths = 3;
	}
	else {
	  $threemonth = $calmonth + 3;
	  $threeyear = $calyear;
	  if($threemonth > 12){
	    $threemonth -= 12;
	    $threeyear += 1;
	  }
	  $threeday = MonthDays($threemonth, $threeyear);
	  $cal_end = date("Y-m-d", mktime(0, 0, 0, $threemonth, $threeday, $threeyear));
	  $cal_begin = date("Y-m-d", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	  $daterange = "b.date >= '$calyear-$calmonth-$firstday' and b.date <= '$threeyear-$threemonth' ";
	  $showmonths = 4;
	}
	

	$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $user_id = $record['user_id'];
	  $USERS[] = $user_id;
	  $USERS_NAMES[] = stripslashes($record['fullname']);
	}
	
	
	$dh = "18px"; // div height
	$dh2 = "9px"; // div height 2, space between new users
	$dh3 = "26px"; // div height 3, the month name row
	$dw = "25px"; // div width
	$dw_val = 25; // div width, just number, plus 2 for border
	
	
	$totaldays = 0;

	$daysmonth1 = MonthDays($calmonth, $calyear) - $firstday + 1;
	
	$nextmonth = $calmonth +1;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth2 = MonthDays($nextmonth, $nextmonthyear);
	
	$nextmonth = $calmonth +2;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth3 = MonthDays($nextmonth, $nextmonthyear);
	
	$nextmonth = $calmonth +3;
	$nextmonthyear = $calyear;
	if($nextmonth > 12){
	  $nextmonth -= 12;
	  $nextmonthyear += 1;
	}

	$daysmonth4 = MonthDays($nextmonth, $nextmonthyear);
	

	
	
	$header_month1 = $daysmonth1;
	$header_month2 = $daysmonth2;
	$header_month3 = $daysmonth3;
	$header_month4 = $daysmonth4;
	
	
	
	$header_month1 = (($header_month1 * $dw_val) + (2 * $header_month1)) - 2;
	$header_month2 = (($header_month2 * $dw_val) + (2 * $header_month2)) - 2;
	$header_month3 = (($header_month3 * $dw_val) + (2 * $header_month3)) - 2;
	$header_month4 = (($header_month4 * $dw_val) + (2 * $header_month4)) - 2;
	
	$month_info[1]['days'] = $daysmonth1;
	$month_info[2]['days'] = $daysmonth2;
	$month_info[3]['days'] = $daysmonth3;
	$month_info[4]['days'] = $daysmonth4;
	
	$month_info[1]['header'] = $header_month1;
	$month_info[2]['header'] = $header_month2;
	$month_info[3]['header'] = $header_month3;
	$month_info[4]['header'] = $header_month4;
	
	for($x=1;$x<=$showmonths;$x++){
	  $totaldays += $month_info[$x]['days'];
	}
	$day_abbr[0] = "Su";
	$day_abbr[1] = "M";
	$day_abbr[2] = "Tu";
	$day_abbr[3] = "W";
	$day_abbr[4] = "Th";
	$day_abbr[5] = "F";
	$day_abbr[6] = "Sa";
	
	$right_side_width = ($totaldays) * ($dw_val + 2);
	$name_width = 170;
	$name_width_px = $name_width . "px";
	$left_side_width = $name_width + 2;
	//$right_side_display_width = 750;
	$right_side_display_width = $right_side_width;
	$total_width = $left_side_width + $right_side_display_width;
	
	// number of divs in the month, times the width.  Then add 2 times number of divs for border widths.  Subtract 2 for month name borders
	?>
	<div style="width:<?=$total_width?>px; position:relative; background-color:#FFFFFF;" id="gantt_total">
    <div style="float:left; width:<?=$left_side_width?>px; background-color:#FFFFFF;">
	
	<?php
	echo "<div style='position:relative; background-color:#FFFFFF; height:$dh3;'>";
	echo "<div style='width:$name_width_px; background-color:#FFFFFF; float:left; border:1px solid black; height:$dh3;'>&nbsp;</div>";
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	

	
	for($foo=0;$foo<2;$foo++){

	    echo "<div style='position:relative; height:$dh;'>";
		echo "<div style='width:$name_width_px; float:left; border:1px solid black; height:$dh; z-index:100; position:relative;'><strong>&nbsp;</strong>";
		echo "</div>";
		echo "</div>";
		echo "<div style='clear:both;'></div>";
	  
	  

	 }
	for($foo=0;$foo<sizeof($USERS);$foo++){
	  $user_id = $USERS[$foo];
	  $name = $USERS_NAMES[$foo];

	    echo "<div style='position:relative; height:$dh;'>";
		echo "<div style='width:$name_width_px; float:left; border:1px solid black; height:$dh; z-index:100; position:relative;'><strong>$name</strong>";
		echo "</div>";
		echo "</div>";
		echo "<div style='clear:both;'></div>";
	  
	  

	 }
	

	?>
	</div>
	<?php // ***********  START OF RIGHT DIV, WITH ALL THE DATES ************************************************* ?>
	<div style="float:right; width:<?=$right_side_display_width?>px; overflow:auto;" id="gantt_rs_display">
	<div style="width:<?=$right_side_width?>px; background-color:#FFFFFF;">
	
	
	<div style="position:relative; width:100%;">
	<?php
	$prev_click = '<a href="' . $PHP_SELF . '?act_id=' . $act_id . '&property_id=' . $property_id . '&prospect_id=' . $prospect_id . '&m=' . $headerprevmonth . '&y=' . $headerprevyear . '&sched=1">&lt;</a> ';
	$next_click = ' <a href="' . $PHP_SELF . '?act_id=' . $act_id . '&property_id=' . $property_id . '&prospect_id=' . $prospect_id . '&m=' . $headernextmonth . '&y=' . $headernextyear . '&sched=1">&gt;</a>';
	for($x=1;$x<=$showmonths;$x++){
	  $z = $x - 1;
	  $header_display = $prev_click . date('F', mktime(0,0,0,$calmonth + $z,1,$calyear)) . '&nbsp;' . date('Y', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] < 10) $header_display = $prev_click . date('M', mktime(0,0,0,$calmonth + $z,1,$calyear)) . '&nbsp;' . date('Y', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] < 5) $header_display = $prev_click . date('M', mktime(0,0,0,$calmonth + $z,1,$calyear)) . $next_click;
	  if($x==1 && $month_info[$x]['days'] <=2) $header_display = "";
	  ?>
	  <div style="border:1px solid black; height:<?=$dh3?>; width:<?=$month_info[$x]['header']?>px; float:left; background-color:#CCCCCC;" class="cal_top">
	  <?=$header_display?>
	  </div>
	  <?php
	}
	?>
	
	</div>
	<div style="clear:both;"></div>
<?php
    $bar = date("w", mktime(0, 0, 0, $calmonth, $firstday, $calyear));
	echo "<div style='position:relative; height:$dh;'>";
	for($x=1;$x<=$totaldays;$x++){
	  echo "<div style='background-color:white; width:$dw; border:1px solid black; float:left; height:$dh;' align='center'>" . $day_abbr[$bar] . "</div>";
	  $bar++;
	  if($bar > 6) $bar = 0;
	}

	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	echo "<div style='position:relative; height:$dh;'>";

	$day=$firstday - 1;
	$month = $calmonth;
	$year = $calyear;
	for($x=1;$x<=$totaldays;$x++){
	  $day++;
	  if(!(checkdate($month, $day, $year))){
	    $day = 1;
		$month++;
		if($month > 12){
		  $month=1;
		  $year++;
		}
	  }
	  
	  $bg = "white";
	  if("$day - $month"==date("j - n")) $bg = "#FFFF99";
	  echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh;' align='center'>$day</div>\n";
	}

	echo "</div>";
	echo "<div style='clear:both;'></div>";
	
	
	
	for($foo=0;$foo<sizeof($USERS);$foo++){
	echo "<div style='position:relative; height:$dh;'>";
	$day=$firstday - 1;
	$month = $calmonth;
	$year = $calyear;
	for($x=1;$x<=$totaldays;$x++){
	  $day++;
	  if(!(checkdate($month, $day, $year))){
	    $day = 1;
		$month++;
		if($month > 12){
		  $month=1;
		  $year++;
		}
	  }
	  
	  $bg = "white";
	  $fontcolor = "black";
	  $testday = $day;
	  $testmonth = $month;
	  if(strlen($testday)==1) $testday = "0" . $day;
	  if(strlen($testmonth)==1) $testmonth = "0" . $month;
	  $testdate = $year . "-" . $testmonth . "-" . $testday;
	  
	  $sql = "SELECT count(act_id) from activities where user_id='" . $USERS[$foo] . "' and event='$RESAPPT' and date like '$year-$testmonth-$testday%' and display=1";
	  $count = getsingleresult($sql);
	  if($count >= $full_appointment) {
	    $bg = "#0000FF";
		$fontcolor = "white";
	  }
	  //if("$day - $month"==date("j - n")) $bg = "#FFFF99";
	  echo "<div style='background-color:$bg; width:$dw; border:1px solid black; float:left; height:$dh; font-size:10px; font-weight:bold; color:$fontcolor;' align='center' ";
	  echo "onMouseOver=\"this.style.cursor='pointer'\" onclick=\"javascript:pop_sched_zoom('" . $USERS[$foo] . "', '" . $testdate . "')\">$count</div>\n";
	  
	}
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	} // end for
	
	
	
	

	echo '</div>';
	echo "</div>";
	echo "</div>";
}
?>
<div id="sched" style="position:absolute; left:50px; top:10px; width:1000px; height:525px; z-index:151; border: 1px solid black; display:none; background-color:white;">
  <div align="right">
  <a href="javascript:Closewin('sched')" style="font-weight:bold; color:#FF0000;">X</a>
  </div>
  <div style="width:100%; overflow:auto;">
  <?php
  $m = $_GET['m'];
  $y = $_GET['y'];
  if($m=="") $m = date("n");
  if($y=="") $y = date("Y");
  ?>
  <?=showSched($m, $y);?>
  </div>
</div>


<div id="schedzoom" style="position:absolute; left:50px; top:10px; width:500px; height:325px; z-index:151; border: 1px solid black; display:none; background-color:white;">
  <div align="right">
  <a href="javascript:Closewin('schedzoom')" style="font-weight:bold; color:#FF0000;">X</a>
  </div>
  <div style="width:100%; overflow:auto;" id="schedzoom_cali">
  </div>
</div>

<?php if($residential){ ?>
<?php
$map_user_id = go_escape_string($_GET['map_user_id']);
$map_date = $_GET['map_date'];
?>
<div id="map_wrapper" style="position:absolute; left:50px; top:-620px; width:1000px; height:545px; z-index:151; border: 1px solid black; background-color:white;">
  
  <div style="position:relative; width:100%;" class="main">
  <div style="float:left;">
  <form name="map_change" action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
  <input type="hidden" name="act_id" value="<?=$act_id?>">
  <input type="hidden" name="property_id" value="<?=$property_id?>">
  <input type="hidden" name="prospect_id" value="<?=$prospect_id?>">
  <input type="hidden" name="openmap" value="1">
  <select name="map_user_id">
  <?php
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and master_id='" . $SESSION_MASTER_ID . "' and $RESOURCE_FILTER order by lastname";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<option value="<?=$record['user_id']?>"<?php if($map_user_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
	<?php
  }
  ?>
  </select>
  Date
  <input size="10" type="text" id="map_date" name="map_date" value="<?=$map_date?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('map_date',0)" align="absmiddle">
  <input type="submit" name="submit1" value="Filter">
  <?php if($map_user_id != ""){ ?>
  <a href="javascript:SetResUser('<?=$map_user_id?>', '<?=$map_date?>')">Assign</a> &nbsp; &nbsp;
  <?php } ?>
  <a href="javascript:ReturnToSched()">Return to Schedule</a>
  </form>
  
  </div>
  <div style="float:right;">
  <a href="javascript:Closewin('map_wrapper')" style="font-weight:bold; color:#FF0000;">X</a>
  </div>
  </div>
  <div style="clear:both;"></div>
  <div style="width:1000px; height:500px;" id="map_canvas"></div>
</div>
<?php } ?>
<script>

event_change(document.form1.elements["event"]);
<?php
$sched = $_GET['sched'];
if($sched==1){
?>
Openwin('sched');
<?php } ?>
<?php

if($map_user_id != ""){
  $date_parts = explode("/", $map_date);
  $map_date_search = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
  $sql = "SELECT property_id from activities where user_id=\"$map_user_id\" and date like '$map_date_search%' and event='$RESAPPT' order by date limit 1";
  $map_property_id = getsingleresult($sql);
  $sql = "SELECT latitude, longitude from geocode where property_id='$property_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $latitude = $record['latitude'];
  $longitude = $record['longitude'];
  $zoomlevel = 9;
}
if($latitude == ""){
  $latitude = 37.25;
  $longitude = -96.857788;
  $zoomlevel = 5;
}
?>
initialize(<?=$latitude?>, <?=$longitude?>, <?=$zoomlevel?>);

</script>
<?php
if($residential){
$sql = "SELECT b.latitude, b.longitude, c.site_name, d.company_name, date_format(a.date, \"%m/%d/%Y %h:%i\") as datepretty, c.city from activities a, geocode b, properties c, prospects d where
a.property_id=b.property_id and a.property_id=c.property_id and a.prospect_id=d.prospect_id and a.user_id=\"$map_user_id\" and a.date like '$map_date_search%' and a.event='$RESAPPT'";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $xlat = $record['latitude'];
  $xlng = $record['longitude'];
  $x_site_name = stripslashes($record['site_name']);
  $x_company_name = stripslashes($record['company_name']);
  $x_datepretty = stripslashes($record['datepretty']);
  $x_city = stripslashes($record['city']);


  $html = "$x_company_name<br>$x_site_name<br />$x_city<br>$x_datepretty<br>";
  $html = go_reg_replace("\'", "", $html);
  echo "<script>";
  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$html', 'marker');\n";
  echo "</script>";
}

$x_property_id = $property_id;
if($x_property_id == "" || $x_property_id==0){
  $sql = "SELECT property_id from properties where prospect_id='$prospect_id' and corporate=1";
  $x_property_id = getsingleresult($sql);
}
$sql = "SELECT a.latitude, a.longitude, b.city, b.site_name, c.company_name from
geocode a, properties b, prospects c where
a.property_id=b.property_id and b.prospect_id=c.prospect_id and a.property_id='$x_property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
  $xlat = $record['latitude'];
  $xlng = $record['longitude'];
  $x_site_name = stripslashes($record['site_name']);
  $x_company_name = stripslashes($record['company_name']);
  $x_city = stripslashes($record['city']);


  $html = "$x_company_name<br>$x_site_name<br />$x_city<br>";
  $html = go_reg_replace("\'", "", $html);
  echo "<script>";
  echo "placeMarker(" . $xlat . ", " . $xlng . ",'$html', 'tack');\n";
  echo "</script>";

} // end if res
?>

<?php include "includes/footer.php"; ?>