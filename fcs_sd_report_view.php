<?php include "includes/header_white.php"; ?>
<head>
<?php


$leak_id = $_GET['leak_id'];
/*
$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
if($invoice_type==2) meta_redirect("fcs_sd_report_view2.php?leak_id=$leak_id");
*/
meta_redirect("fcs_sd_report_view2.php?leak_id=$leak_id");

$sql = "SELECT b.zip from am_leakcheck a, properties b where a.property_id=b.property_id and a.leak_id='$leak_id'";
$zip = getsingleresult($sql);
$zip = substr($zip, 0, 5);
$sql = "SELECT latitude, longitude from zipcodes where zipcode='$zip'";
$result = executequery($sql);
$record = go_fetch_array($result);
$latitude = $record['latitude'];
$longitude = $record['longitude'];
$zoomlevel = 8;

if($latitude == ""){
$latitude = 37.25;
  $longitude = -96.857788;
  $zoomlevel = 4;
}

$order_by = "distance";
function compare($x, $y)
{
global $order_by;
if ( strtoupper($x[$order_by]) == strtoupper($y[$order_by]) )
 return 0;
else if ( strtoupper($x[$order_by]) < strtoupper($y[$order_by]) )
 return -1;
else
 return 1;
}
        $sql = "SELECT priority_id from am_leakcheck where leak_id='$leak_id'";
		$priority_id = getsingleresult($sql);
        $xlatitude = array();
		$xlongitute = array();
		$sqlzips = "SELECT * from zipcodes";
		$resultzips = executequery($sqlzips);
		while($record = go_fetch_array($resultzips)){
		  $x = $record['zipcode'];
		  $xlatitude[$x] = $record['latitude'];
		  $xlongitude[$x] = $record['longitude'];
		}
		$sql_new="select * from	zipcodes where zipcode='$zip' limit 1";
		$result2=executeQuery($sql_new);
		if($line2=go_fetch_array($result2))
		{
			$search_longitude=$line2["longitude"];
			$search_latitude=$line2["latitude"];
		}
		$lr = $priority_id;
	if($lr==0) $lr = "";
	if($lr==1) $lr = "";
	$sql = "SELECT a.prospect_id, a.company_name, b.labor_rate" . $lr . " as xlr from prospects a left join prospects_resources b on a.prospect_id=b.prospect_id where
	a.resource=1 and a.display=1 and a.master_id='" . $SESSION_MASTER_ID . "' order by a.company_name";
	$result = executequery($sql);
	$counter = 0;
	while($record = go_fetch_array($result)){
	  $sql = "SELECT zip from prospects where prospect_id='" . $record['prospect_id'] . "'";
	  $xzip = getsingleresult($sql);
	  $xzip = substr($xzip, 0, 5);
	  $distance2=great_circle_distance($search_latitude,$xlatitude[$xzip],$search_longitude,$xlongitude[$xzip]);
	  $resources[$counter]['distance'] = $distance2;
	  $resources[$counter]['prospect_id'] = stripslashes($record['prospect_id']);
	  $resources[$counter]['company_name'] = stripslashes($record['company_name']);
	  $resources[$counter]['labor_rate'] = stripslashes($record['xlr']);
	  $counter++;
	}
	usort($resources, "compare"); 
	  

?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script type="text/javascript">
    //<![CDATA[



// ======== New map variable ========
    var citymap = {};

  var cityCircle;
  var map;

// =========== Initialize our map, set center, zoom level, hybrid view, UI controls, and allow info window =============
    function initialize() {

      // defines zoom, center, map type, etc
      var latlng = new google.maps.LatLng(<?=$latitude?>, <?=$longitude?>);
      var mapOptions = {
        zoom: <?=$zoomlevel?>,
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
</head>
<body onLoad="Setwin()">
<style type="text/css"><!--
 .boxpopup {
   font-family:Arial,sans-serif; /*TEXT FONT*/
   font-size:10px;
   color:black; background:#FFFF99; /*COLOURS*/
   width:200px;			/*BOX WIDTH*/
   text-align:center; 		/*ALIGNMENT*/
   padding:4px 5px 4px 5px; 	/*SPACING*/
   font-weight:bold;	 	/*BOLD TEXT*/
   border:1px solid gray; 	/*BORDER STYLE*/
   }
  #pdqbox {position:absolute; visibility:hidden; z-index:200;}
-->
</style>
<div id="pdqbox"></div>
<script TYPE="text/javascript"><!--// patdrummond.org
OffsetX=20; // MODIFY THESE VALUES TO 
OffsetY=20;   // CHANGE THE POSITION.
var old,skn,iex=(document.all),yyy=-1000;
var ns4=document.layers
var ns6=document.getElementById&&!document.all
var ie4=document.all
if (ns4) skn=document.pdqbox
else if (ns6) skn=document.getElementById("pdqbox").style
else if (ie4) skn=document.all.pdqbox.style
if (ns4) document.captureEvents(Event.MOUSEMOVE);
else {
skn.visibility="visible"
skn.display="none"
}
document.onmousemove=get_mouse;
function popup(msg){
var content="<div class=boxpopup>"+msg+"</div>";
yyy=OffsetY;
if(ns4){skn.document.write(content);skn.document.close();skn.visibility="visible"}
if(ns6){document.getElementById("pdqbox").innerHTML=content;skn.display=''}
if(ie4){document.all("pdqbox").innerHTML=content;skn.display=''}
}
function get_mouse(e){
var x=(ns4||ns6)?e.pageX:event.x+document.body.scrollLeft;
skn.left=x+OffsetX;
var y=(ns4||ns6)?e.pageY:event.y+document.body.scrollTop;
skn.top=y+yyy;
}
function remove_popup(){
yyy=-1000;
if(ns4){skn.visibility="hidden";}
else if (ns6||ie4)
skn.display="none"
}
//-->
</script>
<?php


$sql = "SELECT section_type from am_leakcheck where leak_id=\"$leak_id\"";
$section_type = getsingleresult($sql);

switch($section_type){
  case "paving":{
    $imagequery = " b.image_paving as ximage ";
	break;
  }
  case "mech":{
    $imagequery = " b.image_mech as ximage ";
	break;
  }
  case "ww":{
    $imagequery = " b.image_ww as ximage ";
	break;
  }
  default:{
    $imagequery = " b.image as ximage ";
	break;
  }
}

$sql = "SELECT b.site_name, b.am_user_id as site_user_id, a.section_id, a.admin_resolve, a.admin_invoice, 
b.am_user_id_corporate as corporate_user_id, a.alert, a.alert_desc, a.servicemen_id, a.servicemen_id2, 
concat(e.firstname, ' ', e.lastname) as dispatchedby, a.fix_contractor, 
a.notes, date_format(a.dispatch_date, \"%m/%d/%Y\") as datepretty, a.invoice, 
date_format(dispatch_date, \"%h:%i %p\") as timepretty, a.property_id, a.problem_desc, a.correction, a.status, a.section_type, 
a.additional, a.add_desc, a.add_priority, a.add_cost, a.add_proposal, a.demo, b.city, b.state, a.code, 
a.invoice_type, a.nte_amount, a.contract_amount, a.bill_to, a.bt_manufacturer, a.bt_installer, a.bt_term,
a.bt_start, a.bt_contact, a.bt_phone, a.promotional_type, a.pt_credit, a.pt_project_name, 
a.nte, a.labor_rate, a.materials, a.extra_cost, a.sub_total, a.discount_amount, a.promotional_amount, a.tax_amount, a.invoice_total, a.rtm, 
a.resource_submit, a.resource_materials, a.resource_extra_cost, a.resource_sub_total, a.resource_promotional_amount, a.resource_tax_amount, a.resource_invoice_total, a.resource_withholding_amount, 
a.rtm_billing, a.rtm_amount, a.billto, a.prospect_id, a.payment, b.billto_default, a.rtm_customer, a.rtm_customer_percent, a.eta_message, 
b.timezone, a.use_subcontractor, a.subcontractor_amount, a.silent_mode, a.serviceman1_materials, a.serviceman2_materials, a.serviceman_helper, 
a.serviceman2_labor_rate, a.next_rtm_scheduled, a.next_rtm_leak_id, a.signature_image, a.priority_id, a.review_def, a.app_data, a.manual_data, 
date_format(a.sent_resource_date, \"%m/%d/%Y %h:%i %p\") as sent_resource_date_pretty, a.deny_resource, 
date_format(a.deny_resource_date, \"%m/%d/%Y %h:%i %p\") as deny_resource_date_pretty, a.additional_notes, a.resource_id, a.open_resource, a.withholding_amount, 
$imagequery 
from am_leakcheck a, properties b, am_users e 
where a.property_id=b.property_id and a.user_id = e.user_id and 
a.leak_id=\"$leak_id\"";
$result = executequery($sql);


$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$property_id = stripslashes($record['property_id']);
$prospect_id = stripslashes($record['prospect_id']);

$sf = $record['ximage'];

$site_user_id = stripslashes($record['site_user_id']);
$corporate_user_id = stripslashes($record['corporate_user_id']);
$section_id = stripslashes($record['section_id']);
$dispatchedby = stripslashes($record['dispatchedby']);
$notes = stripslashes(nl2br($record['notes']));
$problem_desc = stripslashes(nl2br($record['problem_desc']));
$correction = stripslashes(nl2br($record['correction']));
$datepretty = stripslashes($record['datepretty']);
$timepretty = stripslashes($record['timepretty']);
$status = stripslashes($record['status']);
$section_type = stripslashes($record['section_type']);
$invoice = stripslashes($record['invoice']);
$additional = stripslashes($record['additional']);
$add_desc = stripslashes($record['add_desc']);
$add_priority = stripslashes($record['add_priority']);
$add_cost = stripslashes($record['add_cost']);
$add_proposal = stripslashes($record['add_proposal']);
$admin_resolve = stripslashes($record['admin_resolve']);
$admin_invoice = stripslashes($record['admin_invoice']);
$alert = stripslashes($record['alert']);
$alert_desc = stripslashes($record['alert_desc']);
$demo = stripslashes($record['demo']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$code = stripslashes($record['code']);
$fix_contractor = stripslashes($record['fix_contractor']);
$servicemen_id = stripslashes($record['servicemen_id']);
$servicemen_id2 = stripslashes($record['servicemen_id2']);
$eta_message = stripslashes($record['eta_message']);

$invoice_type = stripslashes($record['invoice_type']);
$total_hours = stripslashes($record['total_hours']);
$nte_amount = stripslashes($record['nte_amount']);
$contract_amount = stripslashes($record['contract_amount']);
$bill_to = stripslashes($record['bill_to']);
$bt_manufacturer = stripslashes($record['bt_manufacturer']);
$bt_installer = stripslashes($record['bt_installer']);
$bt_term = stripslashes($record['bt_term']);
$bt_start = stripslashes($record['bt_start']);
$bt_contact = stripslashes($record['bt_contact']);
$bt_phone = stripslashes($record['bt_phone']);
$promotional_type = stripslashes($record['promotional_type']);
$pt_credit = stripslashes($record['pt_credit']);
$pt_project_name = stripslashes($record['pt_project_name']);

$nte = stripslashes($record['nte']);
$labor_rate = stripslashes($record['labor_rate']);
$materials = stripslashes($record['materials']);
$extra_cost = stripslashes($record['extra_cost']);
$sub_total = stripslashes($record['sub_total']);
$discount_amount = stripslashes($record['discount_amount']);
$promotional_amount = stripslashes($record['promotional_amount']);
$tax_amount = stripslashes($record['tax_amount']);
$invoice_total = stripslashes($record['invoice_total']);

$resource_submit = stripslashes($record['resource_submit']);
$resource_materials = stripslashes($record['resource_materials']);
$resource_extra_cost = stripslashes($record['resource_extra_cost']);
$resource_sub_total = stripslashes($record['resource_sub_total']);
$discount_amount = stripslashes($record['discount_amount']);
$resource_promotional_amount = stripslashes($record['resource_promotional_amount']);
$resource_tax_amount = stripslashes($record['resource_tax_amount']);
$resource_invoice_total = stripslashes($record['resource_invoice_total']);
$resource_withholding_amount = stripslashes($record['resource_withholding_amount']);

$rtm = stripslashes($record['rtm']);
$rtm_billing = stripslashes($record['rtm_billing']);
$rtm_amount = stripslashes($record['rtm_amount']);
$billto = stripslashes($record['billto']);
$billto_default = stripslashes($record['billto_default']);
$payment = stripslashes($record['payment']);
$rtm_customer = stripslashes($record['rtm_customer']);
$rtm_customer_percent = stripslashes($record['rtm_customer_percent']);
$timezone = stripslashes($record['timezone']);
$use_subcontractor = stripslashes($record['use_subcontractor']);
$subcontractor_amount = stripslashes($record['subcontractor_amount']);
$silent_mode = stripslashes($record['silent_mode']);
$serviceman1_materials = stripslashes($record['serviceman1_materials']);
$serviceman2_materials = stripslashes($record['serviceman2_materials']);
$serviceman_helper = stripslashes($record['serviceman_helper']);
$serviceman2_labor_rate = stripslashes($record['serviceman2_labor_rate']);
$next_rtm_scheduled = stripslashes($record['next_rtm_scheduled']);
$next_rtm_leak_id = stripslashes($record['next_rtm_leak_id']);
$signature_image = stripslashes($record['signature_image']);
$priority_id = stripslashes($record['priority_id']);
$review_def = stripslashes($record['review_def']);
$app_data = stripslashes($record['app_data']);
$manual_data = stripslashes($record['manual_data']);
$sent_resource_date_pretty = stripslashes($record['sent_resource_date_pretty']);
$deny_resource = stripslashes($record['deny_resource']);
$deny_resource_date_pretty = stripslashes($record['deny_resource_date_pretty']);
$additional_notes = stripslashes($record['additional_notes']);
$resource_id = stripslashes($record['resource_id']);
$open_resource = stripslashes($record['open_resource']);
$withholding_amount = stripslashes($record['withholding_amount']);

$bt_manufacturer = go_reg_replace("\"", "&quot;", $bt_manufacturer);
$bt_installer = go_reg_replace("\"", "&quot;", $bt_installer);
$bt_term = go_reg_replace("\"", "&quot;", $bt_term);
$bt_start = go_reg_replace("\"", "&quot;", $bt_start);
$bt_contact = go_reg_replace("\"", "&quot;", $bt_contact);
$bt_phone = go_reg_replace("\"", "&quot;", $bt_phone);
$pt_project_name = go_reg_replace("\"", "&quot;", $pt_project_name);

$fix_contractor = go_reg_replace("\"", "&quot;", $fix_contractor);

$sql = "SELECT company_name from prospects where prospect_id='$resource_id'";
$resource_name = stripslashes(getsingleresult($sql));

$sql = "SELECT withholding from prospects_resources where prospect_id='$resource_id'";
$withholding = getsingleresult($sql);
if($withholding=="") $withholding = 0;


/* I think this is RO only
if($labor_rate==0 || $labor_rate==""){
  $sql = "SELECT hourly_rate from opportunities where property_id='$property_id' and display=1 order by opp_id desc limit 1";
  $hourly_rate = getsingleresult($sql);
  if($hourly_rate != 0){
    $labor_rate = $hourly_rate;
	$sql = "UPDATE am_leakcheck set labor_rate='$labor_rate' where leak_id='$leak_id'";
	executeupdate($sql);
  }
}
*/

$sql = "SELECT priority1, priority2, priority3 from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$priority1 = stripslashes($record['priority1']);
$priority2 = stripslashes($record['priority2']);
$priority3 = stripslashes($record['priority3']);

/*
$show_rtm_discount = 0;
$sql = "SELECT count(*) from opportunities where opp_stage_id=10 and property_id='$property_id' and display=1";
$test_rtm = getsingleresult($sql);
if($test_rtm > 1) {
  //$rtm_customer = 1;
  $show_rtm_discount = 1;
}

if($leak_id==940){
  //$rtm_customer = 1;
  $show_rtm_discount = 1;
}
*/

/*
if($status == "Resolved" && $admin_resolve == 0){
    $sql = "SELECT date_format(eta_date, \"%Y\") from am_leakcheck where leak_id='$leak_id'";
	$testeta = getsingleresult($sql);
	if($testeta=="0000"){
	  $status = "Acknowledged";
	}
	else {
	  $status = "Arrival ETA";
	}
}
*/

if($section_id != 0){
  $sql = "SELECT section_name from sections where section_id='$section_id'";
  $section_name = stripslashes(getsingleresult($sql));
}
else {
  $section_name = "Unknown";
}

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$prospect_company_name = stripslashes(getsingleresult($sql));

$sql = "SELECT concat(firstname, ' ', lastname) as fullname from am_users where user_id='$site_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_user_name = stripslashes($record['fullname']);
$sql = "SELECT concat(firstname, ' ', lastname) as fullname from am_users where user_id='$corporate_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$corporate_user_name = stripslashes($record['fullname']);

$sql = "SELECT date_format(eta_date, \"%m/%d/%Y\") as eta_date_pretty, date_format(eta_date, \"%h\") as hour, 
date_format(eta_date, \"%i\") as minute, date_format(eta_date, \"%p\") as ampm from am_leakcheck where leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$eta_date_pretty = $record['eta_date_pretty'];
$hour = $record['hour'];
$minute = $record['minute'];
$ampm = $record['ampm'];

if($eta_date_pretty == "00/00/0000"){
  $eta_date_pretty = date("m/d/Y");
  $hour = date("g");
  $ampm = date("A");
}

$sql = "SELECT resource_id from am_leakcheck_resourcelog where leak_id='$leak_id' and response=-1";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $DENY[] = $record['resource_id'];
}


$show_invoice = 0;
if($status=="Confirmed" || $status=="Invoiced" || $status=="Closed Out")  $show_invoice = 1;

$show_contlink = 0;
if($status=="In Progress" || $status=="Resolved" || $status=="Confirmed" || $status=="Invoiced" || $status=="Closed Out")  $show_contlink = 1;

  //$timezone = 0; // timezone is always cst now
  
  switch($timezone){
    case 1:{
	  $tz_display = "EST";
	  break;
	}
	case 0:{
	  $tz_display = "CST";
	  break;
	}
	case -1:{
	  $tz_display = "MST";
	  break;
	}
	case -2:{
	  $tz_display = "PST";
	  break;
	}
	default:{
	  $tz_display = "CST";
	  break;
	}
  }
  
  //$tz_display = "";
  
  $sql = "SELECT 
  date_format(date_add(a.dispatch_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as dispatch, 
  date_format(date_add(a.fix_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as resolved, 
  date_format(date_add(a.acknowledge_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as acknowledge, 
  date_format(date_add(a.eta_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as eta, 
  date_format(date_add(a.confirm_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as confirm, 
  date_format(date_add(a.invoice_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as invoice, 
  date_format(date_add(a.inprogress_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as inprogress, 
  date_format(date_add(a.closed_date, interval $timezone hour), \"%m/%d/%Y<br>%h:%i %p\") as closed
  from am_leakcheck a where leak_id='$leak_id'";
  $result_times = executequery($sql);
  $record_times = go_fetch_array($result_times);
  $dispatch = stripslashes($record_times['dispatch']) . " " . $tz_display;
  $acknowledge = stripslashes($record_times['acknowledge']) . " " . $tz_display;
  $eta = stripslashes($record_times['eta']) . " " . $tz_display;
  $resolved = stripslashes($record_times['resolved']) . " " . $tz_display;
  $confirm = stripslashes($record_times['confirm']) . " " . $tz_display;
  $invoice = stripslashes($record_times['invoice']) . " " . $tz_display;
  $inprogress = stripslashes($record_times['inprogress']) . " " . $tz_display;
  $closed = stripslashes($record_times['closed']) . " " . $tz_display;
  
  $show_resolution = 0;
  
  switch($status){
    case "Dispatched":
	case "Acknowledged":{
	  $dis_bg = "red";
	  $dis_name = "<strong>Dispatched</strong>";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "white";
	  $eta_name = "Arrival ETA";
	  $eta_time = "";
	  
	  $ip_bg = "white";
	  $ip_name = "In Progress";
	  $ip_time = "";
	  
	  $res_bg = "white";
	  $res_name = "Resolved";
	  $res_time = "";
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "Arrival ETA":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "<strong>Arrival ETA</strong>";
	  $eta_time = $eta;
	  
	  $ip_bg = "white";
	  $ip_name = "In Progress";
	  $ip_time = "";
	  
	  $res_bg = "white";
	  $res_name = "Resolved";
	  $res_time = "";
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "In Progress":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "Arrival ETA";
	  $eta_time = $eta;
	  
	  $ip_bg = "yellow";
	  $ip_name = "<strong>In Progress</strong>";
	  $ip_time = $inprogress;
	  
	  $res_bg = "white";
	  $res_name = "Resolved";
	  $res_time = "";
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "Resolved":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "Arrival ETA";
	  $eta_time = $eta;
	  
	  $ip_bg = "yellow";
	  $ip_name = "In Progress";
	  $ip_time = $inprogress;
	  
	  $res_bg = "#00FF00";
	  $res_name = "<strong>Resolved</strong>";
	  $res_time = $resolved;
	  
	  $con_bg = "white";
	  $con_name = "Confirmed";
	  $con_time = "";
	  
	  break;
	}
	
	case "Invoiced":
	case "Closed Out":
	case "Confirmed":{
	  $dis_bg = "red";
	  $dis_name = "Dispatched";
	  $dis_time = $dispatch;
	  
	  $eta_bg = "#FF9146";
	  $eta_name = "Arrival ETA";
	  $eta_time = $eta;
	  
	  $ip_bg = "yellow";
	  $ip_name = "In Progress";
	  $ip_time = $inprogress;
	  
	  $res_bg = "#00FF00";
	  $res_name = "Resolved";
	  $res_time = $resolved;
	  
	  $con_bg = "blue";
	  $con_name = "<strong>Confirmed</strong>";
	  $con_time = $confirm;
	  
	  $show_resolution = 1;
	  
	  break;
	}
  }
  $table = "
<div align='left'>
  <div style='width:710px; position:relative; height:32px;'>
    <div style='width:130px; border:1px solid black; background-color:" . $dis_bg . "; height:30px; float:left;'></div>
	<div style='width:130px; border:1px solid black; background-color:" . $eta_bg . "; height:30px; float:left;'></div>
	<div style='width:130px; border:1px solid black; background-color:" . $ip_bg . "; height:30px; float:left;'></div>
	<div style='width:130px; border:1px solid black; background-color:" . $res_bg . "; height:30px; float:left;'></div>
	<div style='width:130px; border:1px solid black; background-color:" . $con_bg . "; height:30px; float:left;'></div>
  </div>
  <div style='clear:both;'></div>
  <div style='width:710px; position:relative;'>
    <div style='width:132px; float:left;' align='center'>" . $dis_name . "</div>
	<div style='width:132px; float:left;' align='center'>" . $eta_name . "</div>
	<div style='width:132px; float:left;' align='center'>" . $ip_name . "</div>
	<div style='width:132px; float:left;' align='center'>" . $res_name . "</div>
	<div style='width:132px; float:left;' align='center'>" . $con_name . "</div>
  </div>
  <div style='clear:both;'></div>
  <div style='width:710px; position:relative;'>
    <div style='width:132px; float:left;' align='center'>" . $dis_time . "</div>
	<div style='width:132px; float:left;' align='center'>" . $eta_time . "</div>
	<div style='width:132px; float:left;' align='center'>" . $ip_time . "</div>
	<div style='width:132px; float:left;' align='center'>" . $res_time . "</div>
	<div style='width:132px; float:left;' align='center'>" . $con_time . "</div>
  </div>
  <div style='clear:both;'></div>
	
</div>";

$sql = "SELECT coordinates from sections_def where leak_id='$leak_id' order by def_id";
$result = executequery($sql);
$def_counter = 0;
$pp = "";
while($record = go_fetch_array($result)){
  $def_counter++;
  $co = $record['coordinates'];
  $co = go_reg_replace("^\{", "", $co);
  $co = go_reg_replace("\, \}$", "", $co);
  $co = go_reg_replace("\},", "*", $co);
  $co_array = explode("*", $co);
 // echo $co_orig . "<br>";

  for($x=0;$x<sizeof($co_array);$x++){
    $set = $co_array[$x];
    $set = go_reg_replace("\{", "", $set);
    $set = go_reg_replace("\}", "", $set);
    $set_array = explode(",", $set);
    $x_co = chop($set_array[0]);
    $y_co = chop($set_array[1]);
    $x_co = go_reg_replace(" ", "", $x_co);
    $y_co = go_reg_replace(" ", "", $y_co);
	$x_co -= 6.5;
	$y_co -= 6.5;
	$foo = $def_counter;
	if($foo > 40) $foo = "x";
    $pp .= "<div style='position:absolute; left:" . $x_co . "px; top:" . $y_co . "px; width:13px; height:13px;'><img src='images/pp" . $foo . ".png'></div>\n";
  }
}


$sql = "SELECT coordinates from am_leakcheck_problems where leak_id='$leak_id'";
$result = executequery($sql);
$def_counter = 0;
while($record = go_fetch_array($result)){
  $def_counter++;
  $co = $record['coordinates'];
  $co = go_reg_replace("^\{", "", $co);
  $co = go_reg_replace("\, \}$", "", $co);
  $co = go_reg_replace("\},", "*", $co);
  $co_array = explode("*", $co);
  //echo $co_orig . "<br>";

  for($x=0;$x<sizeof($co_array);$x++){
    $set = $co_array[$x];
    $set = go_reg_replace("\{", "", $set);
    $set = go_reg_replace("\}", "", $set);
    $set_array = explode(",", $set);
    $x_co = chop($set_array[0]);
    $y_co = chop($set_array[1]);
    $x_co = go_reg_replace(" ", "", $x_co);
    $y_co = go_reg_replace(" ", "", $y_co);
	$x_co -= 6.5;
	$y_co -= 6.5;
	$foo = $def_counter;
	if($foo > 9) $foo = "x";
    $pp .= "<div style='position:absolute; left:" . $x_co . "px; top:" . $y_co . "px; width:13px; height:13px;'><img src='images/ppgreen.png'></div>\n";
  }
}
?>
<script src="includes/calendar.js"></script>
<script>
function display_eta(x){
  y = x.value;
  if(y=="Arrival ETA"){
    document.getElementById("eta_section").style.display = "";
	//document.getElementById("eta_message").style.display = "";
  }
  else {
    document.getElementById("eta_section").style.display = "none";
	//document.getElementById("eta_message").style.display = "none";
  }
  document.getElementById("updatets").checked=true;
  
  show_next_rtm();
}

function ContRes(x){
  if(x==1){
    document.getElementById('cont_res').style.display="";
	document.getElementById('cont_res_arrow').innerHTML = "<a href=\"javascript:ContRes('0')\">-</a>";
  }
  else {
    document.getElementById('cont_res').style.display="none";
	document.getElementById('cont_res_arrow').innerHTML = "<a href=\"javascript:ContRes('1')\">+</a>";
  }
}

function ShowHide(area, x){
  if(x==1){
    document.getElementById(area).style.display="";
	document.getElementById(area + '_arrow').innerHTML = "<a href=\"javascript:ShowHide('" + area + "', '0')\">-</a>";
  }
  else {
    document.getElementById(area).style.display="none";
	document.getElementById(area + '_arrow').innerHTML = "<a href=\"javascript:ShowHide('" + area + "', '1')\">+</a>";
  }
}

function InvoiceType(){
  x = document.fcsreport.invoice_type.value;
  //alert(x);
  document.getElementById('bnte').style.display = "none";
  document.getElementById('bcont').style.display = "none";
  document.getElementById('billing').style.display = "none";
  document.getElementById('twoyear').style.display = "none";
  document.getElementById('promotional').style.display = "none";
  document.getElementById('brtm').style.display = "none";
  document.getElementById('billing_rate').style.display = "none";
  document.getElementById('bt_billing_rate').style.display = "none";
  
  if(x=="Billable - TM"){ 
    document.getElementById('bnte').style.display = "";
	document.getElementById('billing').style.display = "";
	document.getElementById('billing_rate').style.display = "";
	document.getElementById('materials_name').innerHTML = "Materials";
  }
  if(x=="Billable - Contract" || x=="Billable - Contract(minor)" || x=="Billable - Contract(major)") { 
    document.getElementById('bcont').style.display = "";
	document.getElementById('billing').style.display = "";
	document.getElementById('billing_rate').style.display = "";
	document.getElementById('materials_name').innerHTML = "Description";
  }
  if(x=="2 Year" || x=="Warranty" || x=="Billable - Warranty") { 
    document.getElementById('twoyear').style.display = "";
	document.getElementById('bt_billing_rate').style.display = "";
  }
  if(x=="Promotional" || x=="Project Mgmt") { document.getElementById('promotional').style.display = "";}
  if(x=="RTM" || x=="RTM - Prevent") { document.getElementById('brtm').style.display = "";}
  
  show_next_rtm();
}

function PromotionalType(){
  x = document.fcsreport.promotional_type.value;
  
  document.getElementById('promo_replacement').style.display = "none";
  document.getElementById('promo_management').style.display = "none";
  
  if(x=="Replacement") { document.getElementById('promo_replacement').style.display = "";}
  if(x=="Management") { document.getElementById('promo_management').style.display = "";}
}

function TimeCalc(){
  h1 = parseInt(document.fcsreport.in_hour.value);
  m1 = parseInt(document.fcsreport.in_minute.value);
  a1 = document.fcsreport.in_ampm.value;
  
  h2 = parseInt(document.fcsreport.out_hour.value);
  m2 = parseInt(document.fcsreport.out_minute.value);
  a2 = document.fcsreport.out_ampm.value;
  
  if(a1=="PM" && h1 !=12) h1 = h1 + 12;
  if(a2=="PM" && h2 !=12) h2 = h2 + 12;
  if(a1=="AM" && h1 == 12) h1 = 0;
  if(a2=="AM" && h2 == 12) h2 = 0;
  
  
  if(m1==15) m1 = .25;
  if(m1==30) m1 = .5;
  if(m1==45) m1 = .75;
  
  if(m2==15) m2 = .25;
  if(m2==30) m2 = .5;
  if(m2==45) m2 = .75;
  
  var t1;
  var t2;
  
  t1 = h1 + m1;
  t2 = h2 + m2;
  
  t1 = parseFloat(t1);
  t2 = parseFloat(t2);
  
  var diff;
  diff = t2 - t1;
  if(diff < 0) diff = diff + 24;
  diff = parseFloat(diff);
  
  
  document.fcsreport.total_hours.value = diff;
}

function addtime(serviceman){
  h1 = parseInt(document.fcsreport.in_hour.value);
  m1 = parseInt(document.fcsreport.in_minute.value);
  a1 = document.fcsreport.in_ampm.value;
  
  h2 = parseInt(document.fcsreport.out_hour.value);
  m2 = parseInt(document.fcsreport.out_minute.value);
  a2 = document.fcsreport.out_ampm.value;
  
  d = document.fcsreport.datepretty.value;
  hours = document.fcsreport.total_hours.value;
  
  tz = document.fcsreport.timezone.value;
  
  url = "fcs_sd_report_view_addtime.php?leak_id=<?=$leak_id?>&h1=" + h1 + "&h2=" + h2 + "&m1=" + m1 + "&m2=" + m2 + "&a1=" + a1 + "&a2=" + a2 + "&d=" + d + "&hours=" + hours + "&go=1&serviceman=" + serviceman + "&timezone=" + tz;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function DelTime(id, serviceman){
  url = "fcs_sd_report_view_addtime.php?leak_id=<?=$leak_id?>&time_id=" + id + "&go=0&serviceman=" + serviceman;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function billto_pop(x){
  url = "fcs_sd_report_view_billto.php?property_id=<?=$property_id?>&prospect_id=<?=$prospect_id?>&x=" + x.value;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function billto_pop_force(x){
  url = "fcs_sd_report_view_billto.php?property_id=<?=$property_id?>&prospect_id=<?=$prospect_id?>&x=" + x;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function CalcInvoice(){
  
  
  //hours = parseFloat(document.getElementById('gtotal_hours').value);
  app_hours = parseFloat(document.getElementById('app_hours').value);
  manual_hours = parseFloat(document.getElementById('manual_hours').value);
  var hours = 0;
  if(document.getElementById('app_data').checked==true) hours = hours + app_hours;
  if(document.getElementById('manual_data').checked==true) hours = hours + manual_hours; 
  hours = Math.round(hours*100)/100;
  document.getElementById('gtotal_hours').value = hours;
  
  rate = parseFloat(document.getElementById('labor_rate').value);
  extra = parseFloat(document.getElementById('extra_cost').value);
  discount = parseFloat(document.getElementById('discount_amount').value);
  promotional = parseFloat(document.getElementById('promotional_amount').value);
  tax = parseFloat(document.getElementById('tax_amount').value);
  subcontractor_amount = parseFloat(document.getElementById('subcontractor_amount').value);
  nte_amount = parseFloat(document.fcsreport.nte_amount.value);
  percent = parseFloat(document.getElementById('rtm_customer_percent').value);
  percent = percent / 100;
  
  if(isNaN(hours)) hours = 0;
  if(isNaN(rate)) rate = 0;
  if(isNaN(extra)) extra = 0;
  if(isNaN(discount)) discount = 0;
  if(isNaN(promotional)) promotional = 0;
  if(isNaN(nte_amount)) nte_amount = 0;
  if(isNaN(tax)) tax = 0;
  
  x = document.fcsreport.invoice_type.value;
  if(x=="Billable - Contract" || x=="Billable - Contract(minor)" || x=="Billable - Contract(major)") { 
    rate = 0;
  }
  subtotal = (rate * hours) + extra + subcontractor_amount;
  
  if(document.getElementById('rtm_customer').checked==true){
    discount = subtotal * percent;
	discount = Math.round(discount*100)/100;
	document.getElementById('discount_amount').value=discount;
  }
  //alert("test");
  grand_total = subtotal - (discount + promotional);
  <?php if($withholding){ 
    $whpercent = $withholding / 100;
	?>
    wh = grand_total * <?=$whpercent?>;
	wh = Math.round(wh*100)/100;
	document.getElementById('withholding_amount').value=wh;
	grand_total = grand_total - wh;
  <?php } ?>
  grand_total = grand_total + tax;
  
  if(grand_total < 0) grand_total = 0;
  if(document.fcsreport.nte.checked==true && grand_total > nte_amount){
     promotional = promotional + (grand_total - nte_amount);
	 promotional = Math.round(promotional*100)/100;
     grand_total = nte_amount;
	 document.getElementById('promotional_amount').value=promotional;
  }
  
  subtotal=Math.round(subtotal*100)/100;
  grand_total=Math.round(grand_total*100)/100;
  
  document.getElementById('sub_total').value=subtotal;
  document.getElementById('invoice_total').value=grand_total;
  document.getElementById('invoice_total_display').innerHTML=grand_total;
}

function Punch(id, serviceman){
  url = "fcs_sd_report_view_punch.php?leak_id=<?=$leak_id?>&id=" + id + "&serviceman=" + serviceman;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function modifyrate(id, rate){
  xrate = rate.value;
  document.location.href="fcs_sd_report_view_modifyrate.php?leak_id=<?=$leak_id?>&id=" + id + "&rate=" + xrate;
}

function show_subcontractor(x){
  if(x.checked==true){
    document.getElementById('subcontractor_amount_div').style.display="";
  }
  else {
    document.getElementById('subcontractor_amount_div').style.display="none";
  }
}

function show_serviceman_helper(x){
  if(x.checked==true){
    document.getElementById('serviceman_helper_div').style.display="";
  }
  else {
    document.getElementById('serviceman_helper_div').style.display="none";
  }
}

function show_next_rtm(){
  var invoice = 0;
  var status = 0;
  if(document.fcsreport.status.value=="Confirmed" || document.fcsreport.status.value=="Invoiced" || document.fcsreport.status.value=="Closed Out"){
    status = 1;
  }
  if(document.fcsreport.invoice_type.value=="RTM" || document.fcsreport.invoice_type.value=="RTM - Prevent"){
    invoice = 1;
  }

}

function Openwin(x){
  grayOut(true, {'zindex':'50', 'opacity':'25'});
  document.getElementById(x).style.display="";
}

function Closewin(x){
  grayOut(false);
  document.getElementById('map_wrapper').style.top="20px";
  document.getElementById(x).style.display="none";
}

function Setwin(){
  timer = setTimeout("Closewin('map_wrapper')",400); // 3 secs
  
  return false;
}

function Assign(resource_id, resource){
  Closewin('map_wrapper');
  
  if(resource==1){
    document.fcsreport.servicemen_id.value= "*" + resource_id;
	priority_id = document.fcsreport.priority_id.value;
    url = "fcs_sd_report_view_determineresource.php?leak_id=<?=$leak_id?>&labor_rate=<?=$labor_rate?>&open_resource=<?=$open_resource?>&servicemen_id=*" + resource_id + "&priority_id=" + priority_id;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
	/*
    document.getElementById('buttonresource').onclick=function(){
      document.location.href='fcs_sd_report_resourceconfirm.php?leak_id=<?=$leak_id?>&servicemen_id=' + user_id;
    }
	*/
  }
  else {
    document.fcsreport.servicemen_id.value= resource_id;
  }
}

function ChangeServiceman(x){
  document.getElementById('resource_email').style.display="none";
  priority_id = document.fcsreport.priority_id.value;
  y=x.value;
  url = "fcs_sd_report_view_determineresource.php?leak_id=<?=$leak_id?>&labor_rate=<?=$labor_rate?>&open_resource=<?=$open_resource?>&servicemen_id=" + y + "&priority_id=" + priority_id;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function gosubmit(){
  document.fcsreport.submit2.value="Update";
  document.fcsreport.submit();
}

</script>
<script src="includes/calendar.js"></script>
<script src="includes/grayout.js"></script>
<form action="fcs_sd_report_view_action.php" method="post" name="fcsreport" enctype="multipart/form-data">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
<input type="hidden" name="old_status" value="<?=$status?>">
<input type="hidden" name="submit2" value="">

<div style="position:relative; width:100%;">
<div style="float:left;">
<p style='font-size:1.2em;font-weight:bold; font-family:Arial, Helvetica, sans-serif;'>Dispatch ID: <?=$leak_id?></p>
</div>
<div style="float:right;">
<a href="javascript:Openwin('map_wrapper')" title="Find Resource on Map"><img src="images/us-icon_off.png" border="0"></a>
</div>
<div style="float:right;">
<a href="<?=$_SERVER['SCRIPT_NAME']?>?<?=$_SERVER['QUERY_STRING']?>" title="Refresh"><img src="images/refresh.png" border="0"></a>
</div>
</div>
<div style="clear:both;"></div>
<table border="1" class="main" bordercolor="#CCCCCC" style="border-collapse:collapse;" width="100%" cellpadding="6">
<tr>
<td>
<?php if($_SESSION['sess_msg'] != ""){ ?>
<div class="error"><?=$_SESSION['sess_msg']?></div>
<?php
$_SESSION['sess_msg'] = "";
}
?>
  <table class="main" width="100%">
  <tr>
  <td valign="top" class="main">
  <a href="fcs_sd_report.php">Return to List</a><br>

  <br>
  Status: 
  <select name="status" onChange="display_eta(this)">
	<option value="Dispatched"<?php if($status=="Dispatched") echo " selected";?>>Dispatched</option>
	<option value="Acknowledged"<?php if($status=="Acknowledged") echo " selected";?>>Acknowledged</option>
	<option value="Arrival ETA"<?php if($status=="Arrival ETA") echo " selected";?>>Arrival ETA</option>
	<option value="In Progress"<?php if($status=="In Progress") echo " selected";?>>In Progress</option>
	<option value="Resolved"<?php if($status=="Resolved") echo " selected";?>>Resolved</option>
	<option value="Confirmed"<?php if($status=="Confirmed") echo " selected";?>>Confirmed</option>
	<option value="Invoiced"<?php if($status=="Invoiced") echo " selected";?>>Invoiced</option>
	<option value="Closed Out"<?php if($status=="Closed Out") echo " selected";?>>Closed Out</option>
	</select>
	<div id="eta_section"<?php if($status != "Arrival ETA") echo " style=\"display:none;\"";?>>
	ETA:
	<?php
		if($eta_date_pretty=="") {
			$eta_date_pretty = date("m/d/Y");
		}
	?>
	<input size="10" type="text" name="eta_date_pretty" value="<?=$eta_date_pretty?>"> 
    <img src="images/calendar.gif" onClick="KW_doCalendar('eta_date_pretty',0)" align="absmiddle">
    <br>
    <select name="hour">
    <?php
    for($x=1;$x<=12;$x++){
      ?>
	  <option value="<?=$x?>"<?php if($x== $hour) echo " selected";?>><?=$x?></option>
	  <?php
    }
    ?>
    </select> : 
    <select name="minute">
    <option value="0">00</option>
    <option value="15"<?php if($minute==15) echo " selected";?>>15</option>
    <option value="30"<?php if($minute==30) echo " selected";?>>30</option>
    <option value="45"<?php if($minute==45) echo " selected";?>>45</option>
    </select>
    <select name="ampm">
    <option value="AM"<?php if($ampm=="AM") echo " selected";?>>AM</option>
    <option value="PM"<?php if($ampm=="PM") echo " selected";?>>PM</option>
    </select>
	
	
	</div>
	<br><br>Internal ETA Notes<br>
	<textarea name="eta_message" rows="3" cols="50"><?=$eta_message?></textarea>
	<br><br>
  <?=$table?>
    <br>
    <table class="main">
	<tr>
	<td align="right">Priority:</td>
  	<td>
  	<select name="priority_id" onChange="gosubmit()">
  	<option value="3"<?php if($priority_id==3) echo " selected";?>><?=$priority3?></option>
	<option value="2"<?php if($priority_id==2) echo " selected";?>><?=$priority2?></option>
	<option value="1"<?php if($priority_id==1) echo " selected";?>><?=$priority1?></option>
  	</select>
  	</td>
        <td align="right">Timezone:
  	<select name="timezone1">
  	<option value="1"<?php if($timezone==1) echo " selected";?>>Eastern</option>
  	<option value="0"<?php if($timezone==0) echo " selected";?>>Central</option>
  	<option value="-1"<?php if($timezone==-1) echo " selected";?>>Mountain</option>
  	<option value="-2"<?php if($timezone==-2) echo " selected";?>>Pacific</option>
  	</select>
  	</td>
	</tr>
	
	
	

	<tr>
	<td align="right">Assign to:</td>
	<td>
	<span id="linktoresource"<?php if($resource_id==0) echo " style='display:none;'";?>>
	<?php /*
	<a href="view_company.php?prospect_id=<?=$resource_id?>" target="_blank">View</a>
	*/?>
	</span>
	<select name="servicemen_id" onChange="ChangeServiceman(this)">
	<option value="0">None</option>
	<?php
	$sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where (servicemen=1 and resource=0) and enabled=1 and master_id='" . $SESSION_MASTER_ID . "' order by lastname";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=$record['user_id']?>"<?php if($servicemen_id==$record['user_id']) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
	  <?php
	}
	?>
	<?php
	/*
	$lr = $priority_id;
	if($lr==1) $lr = "";
	$sql = "SELECT a.prospect_id, a.company_name, b.labor_rate" . $lr . " as xlr from prospects a left join prospects_resources b on a.prospect_id=b.prospect_id where
	a.resource=1 and a.display=1 and a.master_id='" . $SESSION_MASTER_ID . "' order by a.company_name";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="*<?=$record['prospect_id']?>"<?php if($resource_id==$record['prospect_id']) echo " selected";
	  if(is_array($DENY)){
	    if(in_array($record['prospect_id'], $DENY)) echo " disabled";
	  }?>><?=stripslashes($record['company_name'])?> $<?=number_format($record['xlr'], 2)?>/hr</option>
	  <?php
	}
	*/
	/*
	for($x=0;$x<sizeof($resources);$x++){
	  ?>
	  <option value="*<?=$resources[$x]['prospect_id']?>"<?php if($resource_id==$resources[$x]['prospect_id']) echo " selected";
	  ?>><?=stripslashes($resources[$x]['company_name'])?> $<?=number_format($resources[$x]['labor_rate'], 2)?>/hr
	  <?php
	  if(is_array($DENY)){
	    if(in_array($resources[$x]['prospect_id'], $DENY)) echo " (declined)";
	  }?>
	  </option>
	  
	  <?php
	}
	*/
	?>
	</select>
	<?php 
	/*
	if($SESSION_ISADMIN==1 && $SESSION_USE_RESOURCES==1){?>
	<a href="add_resource_link.php?leak_id=<?=$leak_id?>">Add resource</a>
	<?php } 
	*/
	?>
	</td>
	<?php /*
	<td>
	<div id="resource_email" style="display:none;">
	<input type="submit" name="submit1" value="Send Confirmation" id="confirmationbutton">
	</div>
	</tr>
	<tr>
	<td colspan="3">
	<span id="resourcelog_arrow"><a href="javascript:ShowHide('resourcelog', '1')">+</a></span> Resource Confirmation History
    <div id="resourcelog" style="display:none;">
	  <table class="main">
	  <?php
	  $sql = "SELECT date_format(a.ts, \"%m/%d/%Y %h:%i %p\") as tspretty, a.response, a.notes, b.company_name from 
	  am_leakcheck_resourcelog a, prospects b where a.resource_id=b.prospect_id and a.leak_id='$leak_id' order by id";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $response = "";
		if($record['response'] == 1) $response = "Accept";
		if($record['response'] == -1) $response = "Decline";
	    ?>
		<tr>
		<td valign="top"><?=stripslashes($record['company_name'])?></td>
		<td valign="top"><?=$record['tspretty']?></td>
		<td valign="top"><?=$response?></td>
		<td valign="top"><?=nl2br(stripslashes($record['notes']))?></td>
		</tr>
		<?php
	  }
	  ?>
	  </table>
	</div>
	</td>
	</tr>
	*/?>
	<?php /*
	<?php if(!go_reg("00/00/0000", $sent_resource_date_pretty)){ ?>
	<tr>
	<td colspan="2">Email Sent: <?=$sent_resource_date_pretty?></td>
	</tr>
	<?php } ?>
	<?php if($deny_resource==1){ ?>
	<tr>
	<td colspan="2"><font color="red">Not able to Dispatch Request <?=$deny_resource_date_pretty?></font></td>
	</tr>
	<?php } ?>
	*/?>


	
	<tr>
	<td align="right">Invoice Type:</td>
	<td>
  <select name="invoice_type" onChange="InvoiceType()">
  <option value=""></option>
  <option value="Billable - TM"<?php if($invoice_type=="Billable - TM") echo " selected";?>>Billable - T&amp;M</option>
  <option value="Billable - Contract(minor)"<?php if($invoice_type=="Billable - Contract(minor)") echo " selected";?>>Billable - Contract(minor)</option>
  <option value="Billable - Contract(major)"<?php if($invoice_type=="Billable - Contract(major)") echo " selected";?>>Billable - Contract(major)</option>
  <option value="Billable - Warranty"<?php if($invoice_type=="Billable - Warranty") echo " selected";?>>Billable - Warranty</option>
  <option value="2 Year"<?php if($invoice_type=="2 Year") echo " selected";?>>2 Year</option>
  <option value="Warranty"<?php if($invoice_type=="Warranty") echo " selected";?>>Warranty</option>
  <option value="Promotional"<?php if($invoice_type=="Promotional") echo " selected";?>>Promotional</option>
  <option value="Project Mgmt"<?php if($invoice_type=="Project Mgmt") echo " selected";?>>Project Mgmt</option>
  <option value="RTM"<?php if($invoice_type=="RTM") echo " selected";?>>RTM</option>
  <option value="RTM - Prevent"<?php if($invoice_type=="RTM - Prevent") echo " selected";?>>RTM Preventative</option>
  </select>
    </td>
	
	<td>
	<div id="bnte" style="display:none;">
    <input type="checkbox" name="nte" value="1"<?php if($nte==1) echo " checked";?> onChange="CalcInvoice()">
    Not to Exceed $<input type="text" name="nte_amount" value="<?=$nte_amount?>" size="10" onKeyUp="CalcInvoice()">
    </div>
    </td>
	<td>
	<div id="billing_rate" style="display:none;">
	Labor Rate: $<input type="text" name="labor_rate" value="<?=$labor_rate?>" size="3" id="labor_rate" onKeyUp="CalcInvoice()">/hr
	</div>
	</td>
	
    <td>
	<div id="bt_billing_rate" style="display:none;">
	Hourly Rate:
	$<input type="text" name="bt_rate" value="<?=$labor_rate?>" id="bt_rate" size="10">
	</div>
	</td>
	</tr>
	
	<tr>
	<td align="right">Property:</td>
	<td><?=$site_name?></td>
	</tr>

	<tr>
	<td align="right">Section:</td>
	<td>
	<select name="section_id">
	<option value="0">Unknown</option>
	<?php
	$sql = "SELECT section_id, section_name, map_reference from sections where property_id='$property_id' and display=1 
	order by map_reference";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=$record['section_id']?>"<?php if($section_id==$record['section_id']) echo " selected";?>>(<?=stripslashes($record['map_reference'])?>) <?=stripslashes($record['section_name'])?></option>
	<?php } ?>
	</select>
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>


	
	<tr><td colspan="2">&nbsp;</td></tr>
	
	<tr>
	<td colspan="2">
	<input type="checkbox" name="updatets" value="1" id="updatets">Update Timestamp 
	<?php //<a href="#" alt="" onMouseOver="popup('Only valid when NOT changing status. Status changes will always update timestamp.')"; onMouseOut="remove_popup()">[?]</a>?>
    </td>
	</tr>
	<tr>
	<td colspan="2">
	<input type="checkbox" name="review_def" value="1"<?php if($review_def==1) echo " checked";?>>Review Deficiencies in App 
    </td>
	</tr>
	<tr>
	<td colspan="2">
	<input type="checkbox" name="silent_mode" value="1"<?php if($silent_mode) echo " checked";?>>Silent Mode (no email alerts for this dispatch)
	</td>
	</tr>
	<tr>
	<td colspan="2">
	<input type="submit" name="submit1" value="Update Status">
	<input type="checkbox" name="send_email" value="1" checked="checked">
	Send Email Update (if status changed)
	</td>
	</tr>
	
	<tr id="next_rtm" style="display:none;">
	<td colspan="2">
	<?php if($next_rtm_scheduled == 1){ ?>
	  Next RTM Scheduled: <a href="fcs_sd_report_view.php?leak_id=<?=$next_rtm_leak_id?>">ID <?=$next_rtm_leak_id?></a>
	<?php } else { /*?>
	  <input type="checkbox" name="schedule_next" value="1"> Schedule next 
	  <select name="inspection_frequency">
      <option value="3"<?php if($inspection_frequency==3) echo " selected";?>>Quarterly</option>
      <option value="6"<?php if($inspection_frequency==6) echo " selected";?>>Semi</option>
      <option value="12"<?php if($inspection_frequency==12) echo " selected";?>>Annual</option>
      </select>
	  RTM inspection
	<?php */ } ?>
	</td>
	</tr>
	
	</table>
	
	
  </td>
  <td align="right" valign="top">
  <a href="view_company.php?prospect_id=<?=$prospect_id?>" target="_top" style="font-size:24px; font-weight:bold;"><?=$prospect_company_name?></a><br>
  <?php if($sf != ""){ ?>
  <?php
  list($width, $height) = getimagesize("uploaded_files/properties/$sf");
  ?>
  <br><br>
  <div style="width:<?=$width?>px; height:<?=$height?>px; position:relative; background:url(uploaded_files/properties/<?=$sf?>);">
  <?=$pp?>
  </div>
  <?php } ?>
    <table>
    <tr>
	<td align="right">Distributed By:</td>
	<td><?=$dispatchedby?></td>
	</tr>
	<tr>
	<td align="right">Date Dispatched:</td>
	<td>
	<?=$datepretty?>
	</td>
	</tr>
	<tr>
	<td align="right">Time Dispatched:</td>
	<td>
	<?=$timepretty?>
	</td>
	</tr>
	</table>
  </td>
  </tr>
  </table>
  
  <div id="map_wrapper" style="position:absolute; left:300px; top:-620px; width:800px; height:525px; z-index:151; border: 1px solid black;">
  <div align="right">
  <a href="javascript:Closewin('map_wrapper')" style="font-weight:bold; color:#FF0000;">X</a>
  </div>
<div id="map_canvas" style="width:800px; height:500px;"></div>
</div>
  Additional Notes:<br>
  <textarea name="additional_notes" rows="5" cols="90"><?=$additional_notes?></textarea><br><br>
  Service Dispatch Notes:<br>
  <?=$notes?>
  
  <br><br>
    <table class="main" width="804">
  <?php
  $sql = "SELECT * from sections_def where get_done_leak_id='$leak_id' order by complete, def_id";
$result = executequery($sql);
$counter = 0;
while($record = go_fetch_array($result)){
  if($counter==0) echo "<tr>";
  $counter++;
  ?>
  <td valign="top" width="400">
  <?php if($record['photo'] != ""){ ?>
  <?php
  $size = getimagesize($CORE_URL . "uploaded_files/def/" . $record['photo']);
  $width = $size[0];
  if($width > 400) $width = 400;
  ?>
  <img src="<?=$CORE_URL?>uploaded_files/def/<?=$record['photo']?>" width="<?=$width?>">
  <?php } ?>
  <br>


  <strong>Deficiency:</strong><br>
  <?=stripslashes(nl2br($record['def']))?><br><br>
  <strong>Corrective Action:</strong><br>
  <?=stripslashes(nl2br($record['action']))?><br><br>
  Estimated Repair Cost:
  $<?=number_format($record['cost'], 2)?>
  
  <?php if($record['complete']==1){ ?>
  <font color="red">This deficiency has been fixed</font>
  <?php } ?>
  </td>
  <?php
  if($counter==2){
    echo "</tr>";
	$counter=0;
  }
}
?>
</table>

  
  <?php // attached photos or additional file?>
  <span id="attach_arrow"><a href="javascript:ShowHide('attach', '0')">-</a></span> <strong>Attached Files/Photos</strong>
  <div id="attach">
  <?php
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=2";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<img src="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record['photo']?>"><br>
	<?php
  }
  ?>
  <?php
  $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and additional=3";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<a href="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record['photo']?>" target="_blank"><img src="images/doc.png" border="0"><br>Important Document</a><br>
	<?php
  }
  ?>
  </div>
  Additional File Upload: <input type="file" name="extrafile">
  <br>
  

  <?php
  $subtotal_hours = 0;
  $html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%h:%i %p\") as inpretty, 
date_format(time_out, \"%h:%i %p\") as outpretty, total_hours, id, timezone, rate, travel
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and from_app=1 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_timezone = $record['timezone'];
  switch($x_timezone){
    case 1:{
	  $xtz_display = "EST";
	  break;
	}
	case 0:{
	  $xtz_display = "CST";
	  break;
	}
	case -1:{
	  $xtz_display = "MST";
	  break;
	}
	case -2:{
	  $xtz_display = "PST";
	  break;
	}
	default:{
	  $xtz_display = "CST";
	  break;
	}
  }
  $html .= "<tr>";
    $html .= "<td>";
	$html .= "<select name='rate' onchange=\"javascript:modifyrate('" . $record['id'] . "', this)\">";
	$html .= "<option value='1'";
	if($record['rate']==1) {
	  $html .= " selected";
	  $actual_hours = $record['total_hours'];
	  $actual_rate = $labor_rate;
	}
	$html .= ">normal</option>";
	$html .= "<option value='1.5'";
	if($record['rate']==1.5) {
	  $html .= " selected";
	  $actual_hours = ($record['total_hours'] / 1.5);
	  $actual_rate = $labor_rate * 1.5;
	}
	$html .= ">x 1.5</option>";
	$html .= "<option value='2'";
	if($record['rate']==2) {
	  $html .= " selected";
	  $actual_hours = ($record['total_hours'] / 2);
	  $actual_rate = $labor_rate * 2;
	}
	$html .= ">x 2</option>";
	$html .= "</select></td>";
	
    $html .= "<td>";
    $html .= "<a href=\"javascript:DelTime('" . $record['id'] . "', '1')\" style='color:red;'>X</a>";
    $html .= "</td>";
  $html .= "<td>";
  $html .= $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>Out: " . $record['outpretty'] . " $xtz_display</td>";
  $html .= "<td>" . number_format($record['total_hours'], 2) . " hrs ($actual_hours hrs x $" . number_format($actual_rate, 2) . "/hr)";
  if($record['travel']==1) $html .= "(travel)";
  $html .= "</td>";
  $html .= "</tr>";
  if($app_data==1) $gtotal_hours += $record['total_hours'];
  $subtotal_hours += $record['total_hours'];
}
$html .= "<tr><td colspan='4'></td><td align='right'>Total:</td><td>" . number_format($subtotal_hours, 2) . " hrs</td></tr>";
$html .= "</table>";
$subtotal_hours = number_format($subtotal_hours, 2);

  ?>
  <input type="hidden" name="app_hours" value="<?=$subtotal_hours?>" id="app_hours">
  <input type="checkbox" name="app_data" value="1"<?php if($app_data==1) echo " checked";?> style="width:25px; height:25px;" onChange="CalcInvoice()" id="app_data">
  <span style="font-weight:bold; font-size:16px;"><?=$MAIN_CO_NAME?> Remotely Captured Data</span><br>
  
  <span id="app_problems_arrow"><a href="javascript:ShowHide('app_problems', '1')">+</a></span> <strong>Problems/Corrections</strong>
  <div id="app_problems" style="display:none;">
  <?php
  $sql = "SELECT * from am_leakcheck_problems where leak_id='$leak_id' and from_app=1";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $problem_id = $record['problem_id'];
	$x_problem_desc = stripslashes(nl2br($record['problem_desc']));
	$x_correction = stripslashes(nl2br($record['correction']));
	?>
	Problem Description:<br>
	<textarea name="app_problem_desc" rows="5" cols="90"><?=$x_problem_desc?></textarea>
    <br><br>
    Correction:<br>
    <textarea name="app_correction" rows="5" cols="90"><?=$x_correction?></textarea>
    <br>
	Problem Photos:
	<table>
    <?php
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=1";
    $result_photo = executequery($sql);
    $counter = 0;
    while($record_photo = go_fetch_array($result_photo)){
      if($counter == 0) echo "<tr>\n";
	  ?>
	  <td valign="top" align="center">
	  <img src="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record_photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
    ?>
    </table>
	<br>
	
	Correction Photos:
	<table>
    <?php
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=2";
    $result_photo = executequery($sql);
    $counter = 0;
    while($record_photo = go_fetch_array($result_photo)){
      if($counter == 0) echo "<tr>\n";
	  ?>
	  <td valign="top" align="center">
	  <img src="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record_photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
    ?>
    </table>
	<br>
	
	<?php
  }
  ?>
  </div> <?php // end div app_problems ?>
  
  <div style="position:relative;">
  <div id="past_times1" style="width:800px; float:left;">
  <?=$html?>
  </div>
  <div style="float:left; padding-left:25px;">
  	<?php if($signature_image != ""){ ?>
	<img src="uploaded_files/signatures/<?=$signature_image?>">
	<?php } ?>
  </div>
  </div>
  <div style="clear:both;"></div>
  <hr size="5" color="#000000">
  <?php
  $subtotal_hours = 0;
  $html = "<table class='main' width='100%'>";
$sql = "SELECT date_format(time_in, \"%m/%d/%Y\") as datepretty, date_format(time_in, \"%h:%i %p\") as inpretty, 
date_format(time_out, \"%h:%i %p\") as outpretty, total_hours, id, timezone, rate, travel
from am_leakcheck_time where leak_id='$leak_id' and complete=1 and from_app=0 order by time_out desc";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $x_timezone = $record['timezone'];
  switch($x_timezone){
    case 1:{
	  $xtz_display = "EST";
	  break;
	}
	case 0:{
	  $xtz_display = "CST";
	  break;
	}
	case -1:{
	  $xtz_display = "MST";
	  break;
	}
	case -2:{
	  $xtz_display = "PST";
	  break;
	}
	default:{
	  $xtz_display = "CST";
	  break;
	}
  }
  $html .= "<tr>";
    $html .= "<td>";
	$html .= "<select name='rate' onchange=\"javascript:modifyrate('" . $record['id'] . "', this)\">";
	$html .= "<option value='1'";
	if($record['rate']==1) {
	  $html .= " selected";
	  $actual_hours = $record['total_hours'];
	  $actual_rate = $labor_rate;
	}
	$html .= ">normal</option>";
	$html .= "<option value='1.5'";
	if($record['rate']==1.5) {
	  $html .= " selected";
	  $actual_hours = ($record['total_hours'] / 1.5);
	  $actual_rate = $labor_rate * 1.5;
	}
	$html .= ">x 1.5</option>";
	$html .= "<option value='2'";
	if($record['rate']==2) {
	  $html .= " selected";
	  $actual_hours = ($record['total_hours'] / 2);
	  $actual_rate = $labor_rate * 2;
	}
	$html .= ">x 2</option>";
	$html .= "</select></td>";
	
    $html .= "<td>";
    $html .= "<a href=\"javascript:DelTime('" . $record['id'] . "', '1')\" style='color:red;'>X</a>";
    $html .= "</td>";
  $html .= "<td>";
  $html .= $record['datepretty'] . " - </td>";
  $html .= "<td>In: " . $record['inpretty'] . " $xtz_display</td>";
  $html .= "<td>Out: " . $record['outpretty'] . " $xtz_display</td>";
  $html .= "<td>" . number_format($record['total_hours'], 2) . " hrs ($actual_hours hrs x $" . number_format($actual_rate, 2) . "/hr)";
  $html .= "<input type='checkbox' name='travel[]' value='" . $record['id'] . "'";
  if($record['travel']==1) $html .= " checked";
  $html .= ">Travel";
  $html .= "</td>";
  $html .= "</tr>";
  if($manual_data==1) $gtotal_hours += $record['total_hours'];
  $subtotal_hours += $record['total_hours'];
}
$html .= "<tr><td colspan='4'></td><td align='right'>Total:</td><td>" . number_format($subtotal_hours, 2) . " hrs</td></tr>";
$html .= "</table>";
$gtotal_hours = number_format($gtotal_hours, 2);
$subtotal_hours = number_format($subtotal_hours, 2);
  ?>
  <input type="hidden" name="manual_hours" value="<?=$subtotal_hours?>" id="manual_hours">
  <input type="checkbox" name="manual_data" value="1"<?php if($manual_data==1) echo " checked";?> style="width:25px; height:25px;" onChange="CalcInvoice()" id="manual_data">
  <span style="font-weight:bold; font-size:16px;">Manual Data Entry</span> <a href="fcs_sd_report_addproblem.php?leak_id=<?=$leak_id?>">Add Problem</a><br>
  
  <span id="man_problems_arrow"><a href="javascript:ShowHide('man_problems', '1')">+</a></span> <strong>Problems/Corrections</strong>
  <div id="man_problems" style="display:none;">
  <?php
  $sql = "SELECT * from am_leakcheck_problems where leak_id='$leak_id' and from_app=0";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $problem_id = $record['problem_id'];
	$x_problem_desc = stripslashes(nl2br($record['problem_desc']));
	$x_correction = stripslashes(nl2br($record['correction']));
	?>
	Problem Description:<br>
	<textarea name="problem_desc" row="5" column="90"><?=$x_problem_desc?></textarea>
    <br><br>
    Correction:<br>
    <textarea name="correction" row="5" column="90"><?=$x_correction?></textarea>
    <br>
	Problem Photos:
	<table>
    <?php
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=1";
    $result_photo = executequery($sql);
    $counter = 0;
    while($record_photo = go_fetch_array($result_photo)){
      if($counter == 0) echo "<tr>\n";
	  ?>
	  <td valign="top" align="center">
	  <img src="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record_photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
    ?>
    </table>
	<br>
	
	Correction Photos:
	<table>
    <?php
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=2";
    $result_photo = executequery($sql);
    $counter = 0;
    while($record_photo = go_fetch_array($result_photo)){
      if($counter == 0) echo "<tr>\n";
	  ?>
	  <td valign="top" align="center">
	  <img src="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record_photo['photo']?>">
	  </td>
	  <?php
	  $counter++;
	  if($counter==3){
	    echo "</tr>\n";
	    $counter = 0;
	  }
    }
    ?>
    </table>
	<br>
	
	<?php
  }
  ?>
  </div> <?php // end div app_problems ?>
  
  
  
  <div style="position:relative;">
  <div id="manual_past_times1" style="width:800px; float:left;">
  <?=$html?>
  </div>
  </div>
  <div style="clear:both;"></div>
  
  
  <input size="10" type="text" name="datepretty" value="<?=date("m/d/Y")?>"> 
  <img src="images/calendar.gif" onClick="KW_doCalendar('datepretty',0)" align="absmiddle">

  <select name="in_hour" onChange="TimeCalc()">
  <?php for($x=1;$x<=12;$x++){ ?>
  <option value="<?=$x?>"<?php if($x==9) echo " selected";?>><?=$x?></option>
  <?php } ?>
  </select>
  <select name="in_minute" onChange="TimeCalc()">
  <option value="00">00</option>
  <option value="15">15</option>
  <option value="30">30</option>
  <option value="45">45</option>
  </select>
  <select name="in_ampm" onChange="TimeCalc()">
  <option value="AM" selected="selected">AM</option>
  <option value="PM">PM</option>
  </select>
  
  &nbsp; &nbsp;
  <select name="out_hour" onChange="TimeCalc()">
  <?php for($x=1;$x<=12;$x++){ ?>
  <option value="<?=$x?>"<?php if($x==5) echo " selected";?>><?=$x?></option>
  <?php } ?>
  </select>
  <select name="out_minute" onChange="TimeCalc()">
  <option value="00">00</option>
  <option value="15">15</option>
  <option value="30">30</option>
  <option value="45">45</option>
  </select>
  <select name="out_ampm" onChange="TimeCalc()">
  <option value="AM">AM</option>
  <option value="PM" selected="selected">PM</option>
  </select>
  <select name="timezone">
  <option value="1"<?php if($timezone==1) echo " selected";?>>EST</option>
  <option value="0"<?php if($timezone==0) echo " selected";?>>CST</option>
  <option value="-1"<?php if($timezone==-1) echo " selected";?>>MST</option>
  <option value="-2"<?php if($timezone==-2) echo " selected";?>>PST</option>
  </select>
  &nbsp; &nbsp;
  Total
  <input type="text" name="total_hours" size="3">hrs
  &nbsp; &nbsp;
  <a href="javascript:addtime('1')">add time</a>
  <br>

<hr size="5" color="#000000">

<div style="width:600px;" align="right">
  Grand Total Hours: <span id="gtotal_hours_display"><?=$gtotal_hours?></span>
  </div>
  
  <input type="hidden" name="gtotal_hours" id="gtotal_hours" value="<?=$gtotal_hours?>">

<div style="width:100%; position:relative;">
<div<?php //if ($show_invoice==0) echo " style=\"display:none;\"";?> style="float:left; width:50%;">
  <strong>Invoice</strong>
  <br><br>
  <strong><?=$MASTER_NAME?></strong><br><br>
  
  <div id="bcont" style="display:none;">
  </div>
  
  <div id="brtm" style="display:none;">
  Billing: 
  <select name="rtm_billing">
  <option value="Quarterly"<?php if($rtm_billing=="Quarterly") echo " selected";?>>Quarterly</option>
  <option value="Bi-Annual"<?php if($rtm_billing=="Bi-Annual") echo " selected";?>>Bi-Annual</option>
  </select>
  &nbsp; &nbsp;
  Amount: $<input type="text" name="rtm_amount" value="<?=$rtm_amount?>">
  <br>
  Discount: $<input type="text" name="rtm_discount" value="<?=$discount_amount?>" size="10">
  <br>
  Other Comments:<br>
  <textarea name="rtm_materials" rows="4" cols="45"><?=$materials?></textarea>
  <?php if($rtm_customer){ ?>
	<br>RTM 20% discount
	<?php } ?>
  </div>
  
  <div id="billing" style="display:none; width:400px;">
    
	<span id="materials_name">Materials</span><br>
	<textarea name="materials" rows="4" cols="45"><?=$materials?></textarea>
	<div align="right">
	Cost: $<input type="text" name="extra_cost" id="extra_cost" value="<?=$extra_cost?>" size="10" onKeyUp="CalcInvoice()">
	<br>
	<div id="subcontractor_amount_div"<?php if($use_subcontractor==0) echo " style='display:none;'";?>>
	SubContractor: $<input type="text" name="subcontractor_amount" id="subcontractor_amount" value="<?=$subcontractor_amount?>" size="10" onKeyUp="CalcInvoice()">
	<br>
	</div>
	Total: $<input type="text" name="sub_total" id="sub_total" value="<?=$sub_total?>" size="10" onKeyUp="CalcInvoice()">
	<br>
	<input type="checkbox" name="rtm_customer" value="1"<?php if($rtm_customer==1) echo " checked";?> onChange="CalcInvoice()" id="rtm_customer">
	<input type="text" name="rtm_customer_percent" value="<?=$rtm_customer_percent?>" size="2" onKeyUp="CalcInvoice()" id="rtm_customer_percent">%

	Discount: $<input type="text" name="discount_amount" id="discount_amount" value="<?=$discount_amount?>" size="10" onKeyUp="CalcInvoice()">
	<br>
	Promotional: $<input type="text" name="promotional_amount" id="promotional_amount" value="<?=$promotional_amount?>" size="10" onKeyUp="CalcInvoice()">
	<br>
	<?php if($withholding){ ?>
	% Withholding: <?=$withholding?> <input type="text" name="withholding_amount" id="withholding_amount" size="10" value="<?=$withholding_amount?>"><br>
	<?php } ?>
	Tax: $<input type="text" name="tax_amount" id="tax_amount" value="<?=$tax_amount?>" size="10" onKeyUp="CalcInvoice()">
	<br>

	<strong>Invoice Total: $<span id="invoice_total_display"><?=$invoice_total?></span></strong>
	<input type="hidden" name="invoice_total" id="invoice_total" value="<?=$invoice_total?>">
	</div>
  <script>
  CalcInvoice();
  </script>
  </div>
  
  <div id="twoyear" style="display:none;">
  <table class="main">
  <tr>
  <td>Bill To</td>
  <td>
  <select name="bill_to">
  <option value="Manufacturer"<?php if($bill_to=="Manufacturer") echo " selected";?>>Manufacturer</option>
  <option value="Installer"<?php if($bill_to=="Installer") echo " selected";?>>Installer</option>
  </select>
  </td>
  <td align="right">Manufacturer</td>
  <td><input type="text" name="bt_manufacturer" value="<?=$bt_manufacturer?>"></td>
  <td align="right">Term</td>
  <td><input type="text" name="bt_term" value="<?=$bt_term?>"></td>
  <td align="right">Start</td>
  <td><input type="text" name="bt_start" value="<?=$bt_start?>"></td>
  </tr>
  <tr>
  <td></td>
  <td></td>
  <td align="right">Installer</td>
  <td><input type="text" name="bt_installer" value="<?=$bt_installer?>"></td>
  <td align="right">Contact</td>
  <td><input type="text" name="bt_contact" value="<?=$bt_contact?>"></td>
  <td align="right">Phone</td>
  <td><input type="text" name="bt_phone" value="<?=$bt_phone?>"></td>
  </tr>
  </table>
  <table class="main">
  
  <tr>
  <td>Materials Cost:</td>
  <td>$<input type="text" name="bt_extra_cost" value="<?=$extra_cost?>" size="10"></td>
  </tr>
  </table>
  <br>
  Materials<br>
  <textarea name="bt_materials" rows="4" cols="45"><?=$materials?></textarea>
  </div>
  
  <div id="promotional" style="display:none;">
  <select name="promotional_type" onChange="PromotionalType()">
  <option value="Replacement"<?php if($promotional_type=="Repair") echo " selected";?>>Repair</option>
  <option value="Management"<?php if($promotional_type=="Management") echo " selected";?>>Management</option>
  </select> &nbsp; &nbsp;
    <span id="promo_replacement" style="display:none;">
	Credit $<input type="text" name="pt_credit" value="<?=$pt_credit?>">
	</span>
	<span id="promo_management" style="display:none;">
	Project Name <input type="text" name="pt_project_name" value="<?=$pt_project_name?>">
	</span>
  </div>
  
  
  <br>
  Bill To: (enter below)
  <select name="billto_drop" onChange="billto_pop(this)">
  <option value="0"></option>
  <option value="property">Property</option>
  <option value="company">Company</option>
  </select>
  (must first save via Update Status button before clicking invoice link below)
  <br>
  <textarea name="billto" id="billto" rows="5" cols="50"><?=$billto?></textarea>
  <br><br>
  PAYMENT: $<input type="text" name="payment" value="<?=$payment?>" size="12"><br>
  <input type="submit" name="submit1" value="Process Invoice">
  <br>
  <?php if($billto == ""){ ?>
  <script>
  billto_pop_force("<?=strtolower($billto_default)?>");
  </script>
  <?php } ?>
  <a href="fcs_sd_invoice.php?leak_id=<?=$leak_id?>">Click here to send/print invoice</a>
</div> <?php // end of invoice div ?>
<div style="float:left;width:50%;"> <?php // resource's invoice div ?>
<?php if($resource_submit==1){ ?>
    <br><br>
    <strong><?=$resource_name?></strong><br><br>
    <span>Materials</span><br>
	<textarea rows="4" cols="45"><?=$resource_materials?></textarea>
	<br>
	<div style="width:400px;" align="right">
	Cost: $<input type="text"  value="<?=$resource_extra_cost?>" size="10">
	<br>

	Total: $<input type="text" value="<?=$resource_sub_total?>" size="10">
	<br>
	<?php /*
	<br>
	<input type="checkbox" name="rtm_customer" value="1"<?php if($rtm_customer==1) echo " checked";?> onChange="CalcInvoice()" id="rtm_customer">RTM &nbsp;
	<input type="text" name="rtm_customer_percent" value="<?=$rtm_customer_percent?>" size="2" onKeyUp="CalcInvoice()" id="rtm_customer_percent">%

	Discount: $<input type="text" name="discount_amount" id="discount_amount" value="<?=$discount_amount?>" size="10" onKeyUp="CalcInvoice()">
	*/ ?>
	<br>
	Promotional: $<input type="text" size="10" value="<?=$resource_promotional_amount?>">
	<?php if($withholding){ ?>
	<br>% Withholding: <?=$withholding?> $<?=number_format($resource_withholding_amount, 2)?>
	<?php } ?>
	<br>
	Tax: $<input type="text" value="<?=$resource_tax_amount?>" size="10">
	<br>

	<strong>Invoice Total: $<?=$resource_invoice_total?></strong>
	</div>
<?php } ?>
</div>
</div>
<div style="clear:both;"></div>
  

</td>
</tr>
</table>

</form>
<script>
InvoiceType();
PromotionalType();
TimeCalc();
ChangeServiceman(document.fcsreport.servicemen_id);
</script>


<script type='text/javascript' language='JavaScript'>

initialize();  // Call function to setup map

</script>
<?php 

$sql = "SELECT b.latitude, b.longitude from am_leakcheck a, geocode b where
a.property_id=b.property_id and a.leak_id='$leak_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$xlat = $record['latitude'];
$xlng = $record['longitude'];

if($xlat==0 || $xlat==""){
  $xlat = $latitude;
  $xlng = $longitude;
}

$html = "<b>$site_name</b><br />";
$html = go_reg_replace("\'", "", $html);
echo "<script>";
echo "placeMarker(" . $xlat . ", " . $xlng . ",'$html', 'marker');\n";
echo "</script>";

include "map_markerdispatch.php"; ?>
</body>
