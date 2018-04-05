<?php include "includes/header_white.php"; ?>
<head>
<link href="fileuploader_fcs/fileuploader.css" rel="stylesheet" type="text/css">
<?php


$leak_id = $_GET['leak_id'];
$sql = "UPDATE am_leakcheck set show_bold=0 where leak_id='$leak_id'";
executeupdate($sql);

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
a.materials_cost, a.other_cost, a.resource_materials_cost, a.resource_other_cost, a.travel_desc, a.labor_desc, a.other_desc, a.travel_time, a.labor_time, a.travel_rate, a.tax_percent, a.include_docs, 
a.withholding_percent, a.resource_withholding_percent, a.resource_tax_percent, a.resource_invoice_locked, a.proposal_locked, 
date_format(a.resource_due_date, \"%m/%d/%Y\") as resource_due_date_pretty, a.proofdoc, b.image_front, a.invoice_id, a.upsell, 
a.bid_notes, a.bid_scope, a.bid_value, a.include_bid, a.literature, a.rr_upsell, a.allow_proposal_done, a.po_number, 
a.check_inout, a.check_ncp, a.check_assess, a.check_gaf, a.check_other, a.check_recall, a.check_approved_repair, a.nrp_eyes_only, a.dis_id, 
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
$image_front = $record['image_front'];
$invoice_id = $record['invoice_id'];
$dis_id = $record['dis_id'];

$site_user_id = stripslashes($record['site_user_id']);
$corporate_user_id = stripslashes($record['corporate_user_id']);
$section_id = stripslashes($record['section_id']);
$dispatchedby = stripslashes($record['dispatchedby']);
$notes = stripslashes($record['notes']);
$nrp_eyes_only = stripslashes($record['nrp_eyes_only']);
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
$proofdoc = stripslashes($record['proofdoc']);
$po_number = stripslashes($record['po_number']);

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

$check_inout = stripslashes($record['check_inout']);
$check_ncp = stripslashes($record['check_ncp']);
$check_assess = stripslashes($record['check_assess']);
$check_gaf = stripslashes($record['check_gaf']);
$check_other = stripslashes($record['check_other']);
$check_recall = stripslashes($record['check_recall']);
$check_approved_repair = stripslashes($record['check_approved_repair']);

$nte = stripslashes($record['nte']);
$labor_rate = stripslashes($record['labor_rate']);
$materials = stripslashes($record['materials']);
$extra_cost = stripslashes($record['extra_cost']);
$sub_total = stripslashes($record['sub_total']);
$discount_amount = stripslashes($record['discount_amount']);
$promotional_amount = stripslashes($record['promotional_amount']);
$tax_amount = stripslashes($record['tax_amount']);
$invoice_total = stripslashes($record['invoice_total']);
$upsell = stripslashes($record['upsell']);
$rr_upsell = stripslashes($record['rr_upsell']);

$resource_submit = stripslashes($record['resource_submit']);
$resource_materials = stripslashes($record['resource_materials']);
$resource_extra_cost = stripslashes($record['resource_extra_cost']);
$resource_sub_total = stripslashes($record['resource_sub_total']);
$discount_amount = stripslashes($record['discount_amount']);
$resource_promotional_amount = stripslashes($record['resource_promotional_amount']);
$resource_tax_amount = stripslashes($record['resource_tax_amount']);
$resource_invoice_total = stripslashes($record['resource_invoice_total']);
$resource_withholding_amount = stripslashes($record['resource_withholding_amount']);
$resource_withholding_percent = stripslashes($record['resource_withholding_percent']);
$resource_tax_percent = stripslashes($record['resource_tax_percent']);

$resource_invoice_locked = stripslashes($record['resource_invoice_locked']);
$resource_due_date_pretty = stripslashes($record['resource_due_date_pretty']);
$proposal_locked = stripslashes($record['proposal_locked']);
$bid_notes = stripslashes($record['bid_notes']);
$bid_scope = stripslashes($record['bid_scope']);
$bid_value = stripslashes($record['bid_value']);
$include_bid = stripslashes($record['include_bid']);
$literature = stripslashes($record['literature']);
$allow_proposal_done = stripslashes($record['allow_proposal_done']);

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

$tax_percent = stripslashes($record['tax_percent']);
$withholding_percent = stripslashes($record['withholding_percent']);
$materials_cost = stripslashes($record['materials_cost']);
$other_cost = stripslashes($record['other_cost']);
$resource_materials_cost = stripslashes($record['resource_materials_cost']);
$resource_other_cost = stripslashes($record['resource_other_cost']);
$travel_desc = stripslashes($record['travel_desc']);
$labor_desc = stripslashes($record['labor_desc']);
$other_desc = stripslashes($record['other_desc']);
$travel_time = stripslashes($record['travel_time']);
$labor_time = stripslashes($record['labor_time']);
$travel_rate = stripslashes($record['travel_rate']);
$travel_time = number_format($travel_time, 2);
$labor_time = number_format($labor_time, 2);
$travel_cost = $travel_rate * $travel_time;
$labor_cost = $labor_rate * $labor_time;
//$travel_cost = number_format($travel_cost, 2);
//$labor_cost = number_format($labor_cost, 2);
$include_docs = stripslashes($record['include_docs']);

$dotax = 1;
if($tax_amount != 0) $dotax=0;

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
	// hopefully not a db killer when a lot of resources are around
	$sql = "SELECT a.prospect_id from vendor_to_dis a, prospects b where a.prospect_id=b.prospect_id and a.dis_id='$dis_id' and b.master_id='" . $SESSION_MASTER_ID . "'";
	$result = executequery($sql);
	$RESOURCE_DIS = array();
	while($record = go_fetch_array($result)){
	  $RESOURCE_DIS[] = $record['prospect_id'];
	}
	$sql = "SELECT a.prospect_id, a.company_name, b.labor_rate" . $lr . " as xlr from prospects a left join prospects_resources b on a.prospect_id=b.prospect_id where
	a.resource=1 and a.display=1 and a.master_id='" . $SESSION_MASTER_ID . "' order by a.company_name";
	$result = executequery($sql);
	$counter = 0;
	while($record = go_fetch_array($result)){
	  if(!(in_array($record['prospect_id'], $RESOURCE_DIS))) continue;
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
	
	
	
	
$sql = "SELECT withholding from prospects_resources where prospect_id='$resource_id'";
$withholding = getsingleresult($sql);
$original_withholding = $withholding;
if($withholding_percent != 0) $withholding = $withholding_percent; // default to previously set value for this invoice
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
$result = executequery($sql);
$record = go_fetch_array($result);
$prospect_company_name = stripslashes($record['company_name']);
//$pricing_notes = stripslashes($record['pricing_notes']);

$sql = "SELECT concat(firstname, ' ', lastname) as fullname from am_users where user_id='$site_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_user_name = stripslashes($record['fullname']);
$sql = "SELECT concat(firstname, ' ', lastname) as fullname from am_users where user_id='$corporate_user_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$corporate_user_name = stripslashes($record['fullname']);

$sql = "SELECT date_format(date_add(eta_date, interval $timezone hour), \"%m/%d/%Y\") as eta_date_pretty, date_format(date_add(eta_date, interval $timezone hour), \"%h\") as hour, 
date_format(eta_date, \"%i\") as minute, date_format(date_add(eta_date, interval $timezone hour), \"%p\") as ampm from am_leakcheck where leak_id='$leak_id'";
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
if($priority_id==4) $show_invoice = 0; // don't show invoice section on no cost proposal

$show_contlink = 0;
if($status=="In Progress" || $status=="Resolved" || $status=="Confirmed" || $status=="Invoiced" || $status=="Closed Out")  $show_contlink = 1;

  //$timezone = 0; // timezone is always cst now
  if($timezone=="") $timezone=0;  
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

$sql = "SELECT coordinates from sections_def where leak_id='$leak_id' and coordinates != '' and complete=0 order by def_id";
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


$ppgreen = "";
$sql = "SELECT coordinates from am_leakcheck_problems where leak_id='$leak_id' and coordinates != '' order by problem_id";
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
  
  $foo = $def_counter;
  if($foo > 29) $foo = "X";
  $ppgreen .= "<div class='ppgreen' id='ppgreen" . $foo . "'>";
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
	
    //$ppgreen .= "<div style='position:absolute; left:" . $x_co . "px; top:" . $y_co . "px; width:13px; height:13px;' class='ppgreen' id='ppgreen" . $foo . "'><img src='images/ppgreen" . $foo . ".png'></div>\n";
	$ppgreen .= "<div style='position:absolute; left:" . $x_co . "px; top:" . $y_co . "px; width:13px; height:13px;'><img src='images/ppgreen" . $foo . ".png'></div>\n";
  }
  $ppgreen .= "</div>";
}


?>
<link href="fileuploader_attachsd/fileuploader.css" rel="stylesheet" type="text/css">	
<script src="fileuploader_fcs/fileuploader.js" type="text/javascript"></script>
<script src="fileuploader_attachsd/fileuploader.js" type="text/javascript"></script>

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


function ShowHide(area, x){
  if(x==1){
    document.getElementById(area).style.display="";
	document.getElementById(area + '_arrow').innerHTML = "<a href=\"javascript:ShowHide('" + area + "', '0')\"><img src='images/redexpand_down.png' border='0'></a>";
  }
  else {
    document.getElementById(area).style.display="none";
	document.getElementById(area + '_arrow').innerHTML = "<a href=\"javascript:ShowHide('" + area + "', '1')\"><img src='images/redexpand_right.png' border='0'></a>";
  }
  url = "fcs_sd_report_view_savearrow.php?leak_id=<?=$leak_id?>&area=" + area + "&x=" + x;
  url=url+"&sid="+Math.random();
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
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
  document.getElementById('contract_total_cost').style.display = "none";
  
  document.getElementById('billing').style.display = "";
  document.getElementById('billing_rate').style.display = "";
	
  if(x=="Billable - TM"){ 
    document.getElementById('bnte').style.display = "";
	
	//document.getElementById('materials_name').innerHTML = "Materials";
  }
  
  if(x=="Billable - Contract"){ 
    document.getElementById('contract_total_cost').style.display = "";
	document.getElementById('billing_rate').style.display = "none";
  }
  

  
  //show_next_rtm();
}

function PromotionalType(){
  x = document.fcsreport.promotional_type.value;
  
  document.getElementById('promo_replacement').style.display = "none";
  document.getElementById('promo_management').style.display = "none";
  
  if(x=="Replacement") { document.getElementById('promo_replacement').style.display = "";}
  if(x=="Management") { document.getElementById('promo_management').style.display = "";}
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

function CurrencyFormatted(amount)
{
	var i = parseFloat(amount);
	if(isNaN(i)) { i = 0.00; }
	var minus = '';
	if(i < 0) { minus = '-'; }
	i = Math.abs(i);
	i = parseInt((i + .005) * 100);
	i = i / 100;
	s = new String(i);
	if(s.indexOf('.') < 0) { s += '.00'; }
	if(s.indexOf('.') == (s.length - 2)) { s += '0'; }
	s = minus + s;
	return s;
}

function CalcInvoice(dotax){
  
  dotax = typeof(dotax) != 'undefined' ? dotax : 0;
  
  labor_cost = parseFloat(document.getElementById('labor_cost').value);
  travel_cost = parseFloat(document.getElementById('travel_cost').value);
  materials_cost = parseFloat(document.getElementById('materials_cost').value);
  other_cost = parseFloat(document.getElementById('other_cost_field').value);
  discount_amount = parseFloat(document.getElementById('discount_amount').value);
  //tax = parseFloat(document.getElementById('tax_amount').value);
  tp = parseFloat(document.getElementById('tax_percent').value);
  <?php if($withholding){ ?>
  wp = parseFloat(document.getElementById('withholding_percent').value);
  if(isNaN(wp)) wp = 0;
  wp = wp / 100;
  <?php } ?>
  taxable_amount = parseFloat(document.getElementById('taxable_amount').value);

  if(isNaN(labor_cost)) labor_cost = 0;
  if(isNaN(travel_cost)) travel_cost = 0;
  labor_plus_travel = labor_cost + travel_cost;
  if(isNaN(labor_plus_travel)) labor_plus_travel = 0;
  if(isNaN(materials_cost)) materials_cost = 0;
  if(isNaN(other_cost)) other_cost = 0;
  if(isNaN(discount_amount)) discount_amount = 0;
  
  if(isNaN(tp)) tp = 0;
  if(isNaN(taxable_amount)) taxable_amount=0;
  
  tp = tp / 100;
  
  labor_plus_travel = Math.round(labor_plus_travel*100)/100;
  
  subtotal = labor_plus_travel + materials_cost + other_cost;
  subtotal = Math.round(subtotal*100)/100;
  
  if(dotax==1){
    tax = taxable_amount * tp;
  }
  else {
    tax = parseFloat(document.getElementById('tax_amount').value);
  }
  
  tax = Math.round(tax*100)/100;
  
  
  grand_total = subtotal;
  grand_total = grand_total + tax;
  <?php if($withholding){ 
	?>
    wh = subtotal * wp;
	wh = Math.round(wh*100)/100;
	wh = CurrencyFormatted(wh);
	document.getElementById('withholding_amount').value=wh;
	//grand_total = grand_total - wh;
  <?php } ?>
  grand_total = grand_total - discount_amount;
  //if(grand_total < 0) grand_total = 0;
  /*
  if(document.fcsreport.nte.checked==true && grand_total > nte_amount){
     promotional = promotional + (grand_total - nte_amount);
	 promotional = Math.round(promotional*100)/100;
     grand_total = nte_amount;
	 document.getElementById('promotional_amount').value=promotional;
  }
  */
  
  grand_total=Math.round(grand_total*100)/100;
  <?php if($invoice_type=="Billable - Contract" && $priority_id != 4){?>
  if(grand_total > <?=$contract_amount?>){
    var credit = grand_total - <?=$contract_amount?>;
	credit = CurrencyFormatted(credit);
	grand_total = <?=$contract_amount?>;
	document.getElementById('credit').value=credit;
	document.getElementById('credit_div').style.display="";
  }
  <?php } ?>
  
  subtotal = CurrencyFormatted(subtotal);
  grand_total = CurrencyFormatted(grand_total);
  labor_plus_travel = CurrencyFormatted(labor_plus_travel);
  //gross = CurrencyFormatted(gross);
  tax = CurrencyFormatted(tax);
  other_cost = CurrencyFormatted(other_cost);
  materials_cost = CurrencyFormatted(materials_cost);
  taxable_amount = CurrencyFormatted(taxable_amount);
  
  document.getElementById('sub_total').value=subtotal;
  document.getElementById('invoice_total').value=grand_total;
  document.getElementById('labor_plus_travel').value=labor_plus_travel;
  //document.getElementById('gross').value=gross;
  
  document.getElementById('other_cost_field').value=other_cost;
  document.getElementById('materials_cost').value=materials_cost;
  document.getElementById('taxable_amount').value=taxable_amount;
  document.getElementById('tax_amount').value=tax;
  
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



function proposal(){
  url="fcs_sd_report_view_proposal.php?leak_id=<?=$leak_id?>";
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function proposal_viewedit(){
  url="fcs_sd_report_view_proposal.php?leak_id=<?=$leak_id?>&action=viewedit";
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function proposal_lock(){
  url="fcs_sd_report_view_proposal.php?leak_id=<?=$leak_id?>&action=lock";
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}


function NextPhoto(problem_id, photo_id, type){
  url="fcs_sd_invoice_documents_photo.php?leak_id=<?=$leak_id?>&problem_id=" + problem_id + "&photo_id=" + photo_id + "&type=" + type;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function createUploader(id, type){
  if(type==1) { divname="problem";}
  if(type==2) { divname="correction";}
        
            var uploader = new qq.FileUploader({
                element: document.getElementById(divname + '_' + id),
                action: 'fileuploader_fcs/php2.php',
				params: {
                  leak_id: '<?=$leak_id?>',
				  problem_id: id,
                  table: 'am_leakcheck_photos',
				  id: 'photo_id',
				  description: '',
				  path: 'leakcheck',
				  additional: '0',
				  type: type
                }
            });           
}

 function startuploaders(){
		  createUploaderSD();
		}
		  
        function createUploaderSD(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('fup'),
                action: 'fileuploader_attachsd/php.php',
				params: {
                  leak_id: '<?=$leak_id?>'
				}

            });           
        }
		
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = startuploaders;
		
function ajax_def (x, def_id) {
    y = x.value;
    url = "def_populate.php?id=" + y + "&def_id=" + def_id;
	url=url+"&sid="+Math.random();

	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
       if(y != ""){ document.body.appendChild (jsel);}

}

function ajax_def_category (x, def_id) {
    y = x.value;
    url = "def_populate_category.php?category_id=" + y + "&def_id=" + def_id;
	url=url+"&sid="+Math.random();

	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
       if(y != ""){ document.body.appendChild (jsel);}

}

function show_ppgreen(){
  var allHTMLTags=document.getElementsByTagName("*");
//Loop through all tags using a for loop
  for (i=0; i<allHTMLTags.length; i++) {
//Get all tags with the specified class name.
    //var string = allHTMLTags[i].className;
	//if(string.search("ppgreen") != -1){
    if (allHTMLTags[i].className=="ppgreen") {
      allHTMLTags[i].style.display="none";
    }
  }
  var foo = document.fcsreport.ppgreencheck.length;
  if(isNaN(foo)) {
    if(document.fcsreport.ppgreencheck.checked){
	  var bar =document.fcsreport.ppgreencheck.value;
	  document.getElementById('ppgreen' + bar).style.display="";
	}
  }
  else {
    for(var i=0; i < foo; i++){
      if(document.fcsreport.ppgreencheck[i].checked){
        var bar =document.fcsreport.ppgreencheck[i].value;
	    document.getElementById('ppgreen' + bar).style.display="";
	  }
    }
  }
}

function showall_ppgreen(x){
  var form = "fcsreport";
  var chkName = "ppgreencheck";
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
  show_ppgreen();
}

function include_bid_show(x){
  if(x.checked==true){
    document.getElementById('include_bid_info').style.display="";
  }
  else {
    document.getElementById('include_bid_info').style.display="none";
  }
}

function DelLiterature(){
  cf = confirm("Are you sure you want to delete this literature?");
  if(cf){
    document.location.href="fcs_sd_report_delliterature.php?leak_id=<?=$leak_id?>";
  }
}

function check_additional_notes(){
  check_inout = 0;
  check_ncp = 0;
  check_assess = 0;
  check_gaf = 0;
  check_other = 0;
  check_recall = 0;
  check_approved_repair = 0;
  if(document.getElementById('check_inout').checked==true) { check_inout=1;}
  if(document.getElementById('check_ncp').checked==true) { check_ncp=1;}
  if(document.getElementById('check_assess').checked==true) { check_assess=1;}
  if(document.getElementById('check_gaf').checked==true) { check_gaf=1;}
  //if(document.getElementById('check_other').checked==true) { check_other=1;}
  if(document.getElementById('check_recall').checked==true) { check_recall=1;}
  if(document.getElementById('check_approved_repair').checked==true) { check_approved_repair=1;}
  
  url="fcs_sd_report_additional_notes.php?prospect_id=<?=$prospect_id?>&check_inout=" + check_inout + "&check_ncp=" + check_ncp + "&check_assess=" + check_assess + "&check_gaf=" + check_gaf + "&check_other=" + check_other;
  url = url + "&check_recall=" + check_recall + "&check_approved_repair=" + check_approved_repair;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function DelAdditional(x){
  cf = confirm("Are you sure you want to delete this file?");
  if(cf){
    document.location.href="fcs_sd_report_deladditional.php?leak_id=<?=$leak_id?>&photo_id=" + x;
  }
}

function AdditionalRename(x){
  url="fcs_sd_report_additional_rename.php?leak_id=<?=$leak_id?>&photo_id=" + x;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
  //document.getElementById('invoice_debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

</script>
<?php include "fcs_sd_report_view_js.php"; ?>
<script src="includes/calendar.js"></script>
<script src="includes/grayout.js"></script>
<form action="fcs_sd_report_view_action.php" method="post" name="fcsreport" enctype="multipart/form-data">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">
<input type="hidden" name="old_status" value="<?=$status?>">
<input type="hidden" name="submit2" value="">

<div style="position:relative; width:100%;">
<div style="float:left;">
<p style='font-size:1.2em;font-weight:bold; font-family:Arial, Helvetica, sans-serif;'>Invoice ID: <?=$invoice_id?></p>
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
<div class="error" style="color:red; font-weight:bold;"><?=$_SESSION['sess_msg']?></div>
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
	<?=$tz_display?>
	
	</div>
	<br><br>
	<div style="position:relative;">
	<div style="float:left;">
	Internal ETA Notes<br>
	<textarea name="eta_message" rows="3" cols="50"><?=$eta_message?></textarea>
	</div>

	</div>
	<div style="clear:both;"></div>
	<br>
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
	<option value="4"<?php if($priority_id==4) echo " selected";?>>No Cost Proposal</option>
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
	<a href="view_company.php?prospect_id=<?=$resource_id?>" target="_blank">View</a>
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

	?>
	</select>
	<?php 

	if($SESSION_ISADMIN==1 && $SESSION_USE_RESOURCES==1){ ?>
	<a href="add_resource_link.php?leak_id=<?=$leak_id?>">Add resource</a>
	<?php } 

	?>
	</td>

	<td>
	<div id="resource_email" style="display:none;">
	<?php if($priority_id==4){ ?>
	<font color="red">
	Complete By:
	</font>
	<input size="10" type="text" name="resource_due_date" value="<?=$resource_due_date_pretty?>"> 
    <img src="images/calendar.gif" onClick="KW_doCalendar('resource_due_date',0)" align="absmiddle">
	<?php } ?>
	<input type="submit" name="submit1" value="Send Confirmation" id="confirmationbutton">
	</div>
	</tr>
	<tr>
	<td colspan="3">
	<span id="resourcelog_arrow"><a href="javascript:ShowHide('resourcelog', '1')"><img src='images/redexpand_right.png' border='0'></a></span> Resource Confirmation History
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




	
	<tr<?php if($priority_id==4) echo " style='display:none;'";?>>
	<td align="right">Invoice Type:</td>
	<td>
  <select name="invoice_type" onChange="InvoiceType()">
  <option value=""></option>
  <option value="Billable - TM"<?php if($invoice_type=="Billable - TM") echo " selected";?>>T&amp;M</option>
  <option value="Billable - Contract"<?php if($invoice_type=="Billable - Contract") echo " selected";?>>Contract</option>
  <option value="Warranty"<?php if($invoice_type=="Warranty") echo " selected";?>>Warranty</option>
  <option value="2 Year"<?php if($invoice_type=="2 Year") echo " selected";?>>2 Year</option>
  <option value="RTM"<?php if($invoice_type=="RTM") echo " selected";?>>RTM</option>
  </select>
    </td>
	
	<td>
	<div id="bnte" style="display:none;">
    <input type="checkbox" name="nte" value="1"<?php if($nte==1) echo " checked";?> onChange="CalcInvoice('<?=$dotax?>')">
    Not to Exceed $<input type="text" name="nte_amount" value="<?=$nte_amount?>" size="10" onKeyUp="CalcInvoice('<?=$dotax?>')">
    </div>
    </td>
	<td>
	<div id="billing_rate" style="display:none;">
	Labor Rate: $<input type="text" name="labor_rate" value="<?=$labor_rate?>" size="3" id="labor_rate_text" onKeyUp="CalcInvoice('<?=$dotax?>')">/hr
	</div>
	</td>
	
    <td>
	<div id="bt_billing_rate" style="display:none;">
	Hourly Rate:
	$<input type="text" name="bt_rate" value="<?=$labor_rate?>" id="bt_rate" size="10">
	</div>
	<div id="contract_total_cost" style="display:none;">
	Contract: $ <input type="text" name="contract_amount" value="<?=$contract_amount?>" size="10">
	<?php if($upsell){?>
	<br>Includes $<?=number_format($upsell, 2)?> upsell
	<?php } ?>
	</div>
	</td>
	</tr>
	<?php 
	/*if($pricing_notes != ""){ ?>
	<tr>
	<td colspan="2"></td>
	<td colspan="2">Pricing Notes: <?=nl2br($pricing_notes)?></td>
	</tr>
	<?php } 
	*/?>
	
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
	
	<tr>
	<td align="right">PO Number:</td>
	<td><input type="text" name="po_number" value="<?=$po_number?>" size="15" maxlength="255"></td>
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
	<a href="javascript:gosubmit()"><img src="images/update_status_btn.png" height="20" width="96" border="0" alt="Update Status" /></a>
	<!--<input type="submit" name="submit1" value="Update Status" style="background-color:#FF3300; border-color:#000000;">-->
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
  <?=$ppgreen?>
  </div>
  <?php if($ppgreen != ""){ ?>
  <span id="green_arrow"><a href="javascript:ShowHide('green', '1')"><img src="images/redexpand_right.png" border="0"></a></span> <strong>Problem Legend</strong>
  <div id="green" style="display:none;">
  <div style="width:100%; height:250px; overflow:auto;">
  <table class="main" width="100%" cellpadding="3" cellspacing="0">
  <tr>
  <td></td>
  <td><input type="checkbox" name="showallgreen" onChange="showall_ppgreen(this)" checked="checked"></td>
  <td>Show/Hide All</td>
  </tr>
  <?php
  $sql = "SELECT problem_name, problem_desc, correction from am_leakcheck_problems where leak_id='$leak_id' and coordinates != '' order by problem_id";
  $result = executequery($sql);
  $def_counter = 0;
  while($record = go_fetch_array($result)){
    $def_counter++;
	$foo = $def_counter;
	if($foo > 29) $foo = "X";
	?>
	<tr<?php if($def_counter % 2) echo " bgcolor=\"$ALT_ROW_COLOR\"";?>>
	<td valign="top"><img src="images/ppgreen<?=$foo?>.png"></td>
	<td valign="top"><input type="checkbox" name="ppgreencheck" value="<?=$foo?>" checked="checked" onChange="show_ppgreen()"></td>
	<td valign="top"><?=nl2br(stripslashes($record['problem_name']))?></td>
	</tr>
	<?php
  }
  ?>
  </table>
	
  </div>
  </div>
  <?php } ?>
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
  Service Dispatch Notes:<br>
  <textarea name="notes" rows="5" cols="90"><?=$notes?></textarea>
  
  <br><br>
  <div style="position:relative;">
  <div style="float:left;">
  Additional Notes:<br>
  <input type="hidden" name="old_additional_notes" value="<?=$additional_notes?>">
  <textarea name="additional_notes" id="additional_notes" rows="5" cols="90"><?=$additional_notes?></textarea>
  </div>
  <div style="float:left; padding-left:35px;">
  <?php /*
  <strong>Include in Additional Notes</strong><br>
  (manually entered text will be overwritten)<br>
  <input type="checkbox" value="1" name="check_inout" id="check_inout"<?php if($check_inout==1) echo " checked";?> onChange="check_additional_notes()">Check In/Out<br>
  <input type="checkbox" value="1" name="check_ncp" id="check_ncp"<?php if($check_ncp==1) echo " checked";?> onChange="check_additional_notes()">NCP Instructions<br>
  <input type="checkbox" value="1" name="check_assess" id="check_assess"<?php if($check_assess==1) echo " checked";?> onChange="check_additional_notes()">Assessment<br>
  <input type="checkbox" value="1" name="check_gaf" id="check_gaf"<?php if($check_gaf==1) echo " checked";?> onChange="check_additional_notes()">GAF<br>
  <input type="checkbox" value="1" name="check_recall" id="check_recall"<?php if($check_recall==1) echo " checked";?> onChange="check_additional_notes()">Recall<br>
  <input type="checkbox" value="1" name="check_approved_repair" id="check_approved_repair"<?php if($check_approved_repair==1) echo " checked";?> onChange="check_additional_notes()">Approved Repair<br>
  */?>
  </div>
  </div>
  <div style="clear:both;"></div>
  <br><br>
  
  
      <table class="main" width="804">
  <?php
  $sql = "SELECT *, (cost + upsell) as totalcost from sections_def where get_done_leak_id='$leak_id' order by complete, def_id";
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
  $<?=number_format($record['totalcost'], 2)?>
  
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
  <input type="hidden" name="rename_file_photo_id" id="rename_file_photo_id" value="">
  <span id="attach_arrow"><a href="javascript:ShowHide('attach', '0')"><img src="images/redexpand_down.png" border="0"></a></span> <strong>Attached Files/Photos</strong>
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
	<span id="rename_file_photo_<?=$record['photo_id']?>">
	<a href="<?=$FCS_URL?>uploaded_files/leakcheck/<?=$record['photo']?>" target="_blank"><img src="images/doc.png" border="0"><br>
	
	<?=stripslashes($record['docname'])?></a>
	</span>
	<a href="javascript:DelAdditional('<?=$record['photo_id']?>')" style="color:red;">[DELETE]</a> 
	<a href="javascript:AdditionalRename('<?=$record['photo_id']?>')">[RE-NAME]</a>
	<br>
	<?php
  }
  ?>
  
  <?php if($priority_id==4){ ?>
  <div style="position:relative; width:850px;">
  <div style="float:left;">
    <?php
	$sql = "SELECT * from sections where property_id='$property_id' and display=1 and section_type='$section_type' and multiple='' order by CAST(map_reference AS UNSIGNED)";
    $result = executequery($sql);
	$total_replace_cost=$total_repair_cost = 0;
    while($record = go_fetch_array($result)){
	  $x_section_id = $record['section_id'];
	  $replace_cost = stripslashes($record['replace_cost']);
      $sf_cost = stripslashes($record['sf_cost']);
      $sqft = $record['sqft'];
      if($replace_cost=="" || $replace_cost=="0") { $replace_cost = $sf_cost * $sqft; }
	  $total_replace_cost += $replace_cost;
	  $sql = "SELECT sum(cost) from sections_def where complete=0 and section_id='$x_section_id'";
	  $total_repair_cost += getsingleresult($sql);
	}
	?>
	<br>
	<strong>Roof Management Report</strong> <a href="am_ghost_login.php?property_id=<?=$property_id?>&section_id=<?=$section_id?>&prospect_id=<?=$prospect_id?>" target="_blank">Edit</a>
	<br>
	<a href="proofdoc.php?leak_id=<?=$leak_id?>" target="_blank">Proof Document</a><br>
	<table class="main">
	<?php if($total_repair_cost != 0){?>
	<tr>
	<td>Roof Repairs</td>
	<td align="right">$<?=number_format($total_repair_cost, 0)?></td>
	</tr>
	<?php } ?>
	<?php if($total_replace_cost != 0){?>
	<tr>
	<td>Roof Replace</td>
	<td align="right">$<?=number_format($total_replace_cost, 0)?></td>
	</tr>
	<?php } ?>
	</table>
	<br>
	<input type="checkbox" name="include_bid" value="1" onChange="include_bid_show(this)"<?php if($include_bid==1) echo " checked";?>> Include Roof Replacement Bid (optional)
	<br>
	<div id="include_bid_info" style="<?php if($include_bid != 1) echo "display:none;";?>">
	Enter value: $<input type="text" name="bid_value" value="<?=$bid_value?>"<?php if($_SESSION['rr_edit_mode'] != 1) echo " readonly style=\"background-color:#CCCCCC;\"";?>> &nbsp;
	Upsell: $<input type="text" name="rr_upsell" value="<?=$rr_upsell?>"<?php if($_SESSION['rr_edit_mode'] != 1) echo " readonly style=\"background-color:#CCCCCC;\"";?>><br><br>
	Cut and Paste Roof Replacement Scope of Work and Pricing<br>
	<textarea name="bid_scope" rows="7" cols="60"<?php if($_SESSION['rr_edit_mode'] != 1) echo " readonly style=\"background-color:#CCCCCC;\"";?>><?=$bid_scope?></textarea>
	<br>
	</div>
	<br>
	<?php if($_SESSION['rr_edit_mode']==1){?>
	Upload Literature (optional, please use pdf) <input type="file" name="literature">
	<?php } ?>
	<?php if($literature != ""){ ?>
	<br>
	<a href="uploaded_files/proposals/<?=$literature?>" target="_blank">Uploaded Literature</a>
	<a href="javascript:DelLiterature()">[delete]</a>
	<?php } ?>

	</div>
	<div style="float:right;">
	<?php if($allow_proposal_done==1){?>
	<input type="submit" name="submit1" value="Get Replacement Done"><br>
	<?php } ?>
	
	<?php if($_SESSION['rr_edit_mode']==1){ ?>
	<input type="submit" name="submit1" value="Roof Replacement View Mode">
	<?php } else { ?>
	<input type="submit" name="submit1" value="Roof Replacement Edit Mode">
	<?php } ?>
	</div>
	</div>
	<div style="clear:both;"></div>
  <?php } ?>
  
  </div>
  <?php /*
  Additional File Upload: <input type="file" name="extrafile">
  <br>
  */?>
  <div id="fup">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
</div>
  
  
  
  
  
  
  
<?php if($resource_submit==1){ ?>

  <span id="resourceinvoice_arrow"><a href="javascript:ShowHide('resourceinvoice', '1')"><img src='images/redexpand_right.png' border='0'></a></span> <strong><font color="#0000FF">Resource Invoice</font></strong>
  
  <div id="resourceinvoice" style="display:none;">
  <div style="width:900px;">
  <div align="right">
  <?php if($resource_invoice_locked==1){
    $lock_label = "Unlock Invoice";
	$show_lock = "Invoice is <span id='showlock'><font color='red'>Locked</font></span>";
  }
  else {
    $lock_label = "Lock Invoice";
	$show_lock = "Invoice is <span id='showlock'><font color='green'>Unlocked</font></span>";
  }
  ?>
  <?=$show_lock?> <input type="button" name="lockbutton" id="lockbutton" value="<?=$lock_label?>" onClick="lockinvoice()">
  </div>
  <?=$resource_name?><br>
    <table width="100%" class="main" cellpadding="2" cellspacing="0">
	<tr bgcolor="#CFE7E5">
	<td><strong>Type</strong></td>
	<td><strong>Description</strong></td>
	<td align="right"><strong>Qty</strong></td>
	<td><strong>Units</strong></td>
	<td align="right"><strong>Unit Cost</strong></td>
	<td align="right"><strong>Total Cost</strong></td>
	<td></td>
	</tr>
	<?php if($invoice_type != "Billable - Contract"){ ?>
	<tr>
	<td valign="top">Travel</td>
	<td valign="top">
	<?=$travel_desc?>
	</td>
	<td valign="top" align="right">
	<?=$travel_time?>
	</td>
	<td valign="top">Hrs</td>
	<td valign="top" align="right">
	$<?=number_format($travel_rate, 2)?>
	</td>
	<td valign="top" align="right">$<?=number_format($travel_cost, 2)?></td>
	<td width="50">&nbsp;</td>
	</tr>
	<tr>
	<td valign="top">Labor</td>
	<td valign="top">
	<?=$labor_desc?>
	</td>
	<td valign="top" align="right">
	<?=$labor_time?>
	</td>
	<td valign="top">Hrs</td>
	<td valign="top" align="right">
	$<?=number_format($labor_rate, 2)?>
	</td>
	<td valign="top" align="right">$<?=number_format($labor_cost, 2)?></td>
	<td></td>
	</tr>
	<?php
	$labor_plus_travel = $labor_cost + $travel_cost;
	$sql = "SELECT * from am_leakcheck_materials where leak_id='$leak_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $subtotal = $record['quantity'] * $record['cost'];
	  $materials_cost += $subtotal;
	  ?>
	  <tr>
	  <td valign="top">Materials</td>
	  <td valign="top">
	  <?=stripslashes($record['description'])?>
	  </td>
	  <td valign="top" align="right">
	  <?=stripslashes($record['quantity'])?>
	  </td>
	  <td valign="top">
	  <?=stripslashes($record['units'])?>
	  </td>
	  <td valign="top" align="right">
	  $<?=number_format($record['cost'], 2)?>
	  </td>
	  <td valign="top" align="right">$<?=number_format($subtotal, 2)?></td>
	  <td>
	  </td>
	  </tr>
	  <?php
	}
	?>
	<?php } 
	else {
	$labor_cost = 0;
	   $travel_cost = 0;
	   $materials_cost = 0;
	   $labor_cost = $contract_amount;
	 }?>
	 <?php
	 $other_cost = 0;
	$sql = "SELECT * from am_leakcheck_othercost where leak_id='$leak_id'";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $subtotal = $record['quantity'] * $record['cost'];
	  $other_cost += $subtotal;
	  ?>
	  <tr>
	  <td valign="top">Other</td>
	  <td valign="top">
	  <?=stripslashes($record['description'])?>
	  </td>
	  <td valign="top" align="right">
	  <?=stripslashes($record['quantity'])?>
	  </td>
	  <td valign="top">
	  <?=stripslashes($record['units'])?>
	  </td>
	  <td valign="top" align="right">
	  $<?=number_format($record['cost'], 2)?>
	  </td>
	  <td valign="top" align="right">$<?=number_format($subtotal, 2)?></td>
	  <td>
	  </td>
	  </tr>
	  <?php
	}
	?>
	</table>
    <?php
	$invoice_subtotal = $labor_plus_travel + $materials_cost + $other_cost;
	?>
	<div align="right" style="padding-right:50px;">
	Labor + Travel &nbsp; 
	$<?=number_format($labor_plus_travel, 2)?>
	<br>
    Materials &nbsp; 
	$<?=number_format($materials_cost, 2)?>
	<br>
	Other &nbsp; 
	$<?=number_format($other_cost, 2)?>
	<br>
	SubTotal &nbsp; 
	$<?=number_format($invoice_subtotal, 2)?>
	<br>
	<?=$resource_tax_percent?>% 
	Tax:  &nbsp; 
	$<?=number_format($resource_tax_amount, 2)?>
	<br>
	<font<?php if($resource_withholding_percent != $original_withholding) echo " color=\"red\"";?>>
	<?=$resource_withholding_percent?>% Withholding &nbsp; 
	$<?=number_format($resource_withholding_amount, 2)?>
	</font>
	<br>
	<?php
	$balance_due_to_resource = $resource_invoice_total;
	$balance_due_from_client = $resource_invoice_total + $resource_withholding_amount;
	
	// not go over contract
	if($invoice_type=="Billable - Contract" && $balance_due_from_client > $other_cost) $balance_due_from_client = $other_cost;
	?>
    Balance Due to Resource &nbsp; 
	$<?=number_format($balance_due_to_resource, 2)?>
	<br>
	Balance Due from Client &nbsp; 
	$<?=number_format($balance_due_from_client, 2)?>
	</div>
  
  </div>
  </div>
  <br><br>
<?php } ?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
<div id="thewholeinvoicesection"<?php if($show_invoice==0) echo " style='display:none;'";?>>  
  <span id="newinvoice_arrow"><a href="javascript:ShowHide('newinvoice', '1')"><img src='images/redexpand_right.png' border='0'></a></span> <strong>Client Invoice</strong>
  
  <div id="newinvoice" style="display:none;">
  
  
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
  
  <div id="billing" style="display:none; width:900px;">
    <div align="right">
	<?php if($SESSION_ISADMIN){ 
	  if($SESSION_EDIT_SD_MODE==1){
	    $vieweditfiller = "Switch to View Mode";
	  }
	  else{
	    $vieweditfiller = "Switch to Edit Mode";
	  }?>
	<input type="button" name="vieweditbutton" value="<?=$vieweditfiller?>" id="vieweditbutton" onClick="viewedit()">
	<?php } ?>

	</div>
	<div id="invoice_debug"></div>
	<div id="invoice_info_table">
	</div>
	<div id="invoice_additem"<?php if($SESSION_EDIT_SD_MODE != 1) echo " style='display:none;'";?>>
	<?php if($invoice_type!="Billable - Contract"){ ?>
	<a href="javascript:additem()">add materials</a>
	<?php } ?>
	</div>
	<div id="invoice_additem_info" style="display:none;">
	<select name="mat_dropdown" id="mat_dropdown" onChange="mat_dropdown_go(this)">
	<option value="0"></option>
	<?php
	$sql = "SELECT id, material from material_list where master_id='" . $SESSION_MASTER_ID . "' order by material";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=$record['id']?>"><?=stripslashes($record['material'])?></option>
	  <?php
	}
	?>
	</select>
	Material: <input type="text" name="mat_description" maxlength="100" id="mat_description"> 
	Quantity: <input type="text" name="mat_quantity" id="mat_quantity" size="2">
	Unit:
	<select name="mat_unit" id="mat_unit">
	<option value="SF">Sq/Ft</option>
	<option value="LF">LF</option>
	<option value="EA">EA</option>
	</select>
	Cost per Unit: $<input type="text" name="mat_cost" id="mat_cost" size="4">
	<input type="button" name="buttonnewmat" value="Add Material" onClick="invoicenewmat()">
	</div>
	
	<div id="invoice_addother"<?php if($SESSION_EDIT_SD_MODE != 1) echo " style='display:none;'";?>>
	<a href="javascript:addother()">add other item</a>
	</div>
	<div id="invoice_addother_info" style="display:none;">
	Other: 
	<input type="text" name="other_descriptionx" maxlength="100" id="other_descriptionx"> 
	Quantity: <input type="text" name="other_quantityx" id="other_quantityx" size="2">
	Unit:
	<select name="other_unitx" id="other_unitx">
	<option value="SF">Sq/Ft</option>
	<option value="LF">LF</option>
	<option value="EA">EA</option>
	<option value="Hrs">Hrs</option>
	<?php
	$sql = "SELECT unit from material_list where master_id='" . $SESSION_MASTER_ID . "'  and unit != 'SF' and unit !='LF' and unit != 'EA' group by unit order by unit";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=stripslashes($record['unit'])?>"><?=stripslashes($record['unit'])?></option>
	  <?php
	}
	?>
	</select>
	Cost: $<input type="text" name="other_costx" id="other_costx" size="4">
	<input type="button" name="buttonnewother" value="Add Other" onClick="invoicenewother()">
	</div>
	
	<?php
	$invoice_subtotal = $labor_cost + $travel_cost + $materials_cost + $other_cost;
	?>
	<input type="hidden" name="labor_cost" id="labor_cost" value="<?=$labor_cost?>">
	<input type="hidden" name="travel_cost" id="travel_cost" value="<?=$travel_cost?>">
	<div style="width:100%; position:relative;">
	<div style="float:left; padding-top:60px;">
	<input type="checkbox" name="include_docs" value="1"<?php if($include_docs==1) echo " checked";?>>Include Supporting Documents
	<br><br><br>
	<?=$resource_name?>
	<br><br>
	<?php if($signature_image != ""){ ?>
	<img src="uploaded_files/signatures/<?=$signature_image?>">
	<?php } ?>
	</div>
	<div align="right" style="padding-left:50px; float:right;">
	Labor + Travel
	<input type="text" name="labor_plus_travel" id="labor_plus_travel" size="10" readonly style="background-color:#CCCCCC;" value="<?=$labor_plus_travel?>">
	<br>
    Materials
	<input type="text" name="materials_cost" id="materials_cost" size="10" readonly style="background-color:#CCCCCC;" value="<?=$materials_cost?>">
	<br>
	Other
	<input type="text" name="other_cost" id="other_cost_field" size="10" readonly style="background-color:#CCCCCC;" value="<?=$other_cost?>">
	<br>
	SubTotal
	<input type="text" name="sub_total" id="sub_total" size="10" readonly style="background-color:#CCCCCC;" value="<?=$invoice_subtotal?>">
	<br>
	Taxable
	<input type="text" name="taxable_amount" id="taxable_amount" size="10" readonly style="background-color:#CCCCCC;">
	<br>
	<?php if($dotax==0){ ?>
	<input type="button" name="buttondotax" value="Re-Calculate Tax" onClick="CalcInvoice('1')">
	&nbsp;
	<?php } ?>
	<input type="text" name="tax_percent" id="tax_percent" size="2" value="<?=$tax_percent?>" onKeyUp="CalcInvoice('<?=$dotax?>')">% 
	Tax: $<input type="text" name="tax_amount" id="tax_amount" value="<?=$tax_amount?>" size="10" onKeyUp="CalcInvoice('0')">
	<br>
	<?php /*
	Gross
	<input type="text" name="gross" id="gross" size="10" readonly style="background-color:#CCCCCC;">
	<br>
	*/?>
	<?php if($withholding){ ?>
	<input type="text" name="withholding_percent" id="withholding_percent" size="2" value="<?=$withholding?>" onKeyUp="CalcInvoice('<?=$dotax?>')">% Withholding
	<input type="text" name="withholding_amount" id="withholding_amount" size="10" value="<?=$withholding_amount?>" readonly style="background-color:#CCCCCC;">
	<br>
	<?php } ?>
	Discount: $<input type="text" name="discount_amount" id="discount_amount" value="<?=$discount_amount?>" size="10" onKeyUp="CalcInvoice('<?=$dotax?>')">
	<br>
	<div id="credit_div" style="display:none;">
	Credit
	<input type="text" name="credit" id="credit" readonly size="10">
	</div>
    Invoice Total
	<input type="text" name="invoice_total" id="invoice_total" value="<?=$invoice_total?>" readonly size="10">
	<br>
	PAYMENT: $<input type="text" name="payment" value="<?=$payment?>" size="12">
	<br>
	<input type="submit" name="submit1" value="Send Client Invoice">
	</div>
	</div>
	<div style="clear:both;"></div>
  <script>
  refreshinvoice();
  //CalcInvoice();
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
  
  </div> <?php // close of collapsable invoice div ?>
  <br><br>
</div><?php // end of the whole invoice section?>   
  
  



  

  <?php if($priority_id != 4){ ?>
  <span id="man_problems_arrow"><a href="javascript:ShowHide('man_problems', '1')"><img src="images/redexpand_right.png" border="0"></a></span> <strong>Supporting Documents</strong>
  <div id="man_problems" style="display:none;">
  <div style="width:800px;" align="right">
  <input type="button" name="buttondocpreview" value="Preview Support Doc" onClick="window.open('fcs_sd_invoice_pdf_documents.php?leak_id=<?=$leak_id?>')">
  </div>
  <table class="main">
  <tr>
  <td>Building Front Image:</td>
  <td><input type="file" name="imagefront"><input type="submit" name="submit1" value="Update"></td>
  </tr>
  <?php if($image_front != ""){ ?>
  <tr>
  <td colspan="2"><a href="<?=$CORE_URL?>uploaded_files/properties/<?=$image_front?>" target="_blank">View current building front image</a></td>
  </tr>
  <?php } ?>
  <tr>
  <td>Proof Doc:</td>
  <td><input type="file" name="proofdoc"><input type="submit" name="submit1" value="Update"></td>
  </tr>
  <?php if($proofdoc != ""){ ?>
  <tr>
  <td colspan="2"><a href="uploaded_files/proofdoc/<?=$proofdoc?>" target="_blank">Existing Proof Doc</a></td>
  </tr>
  <?php } ?>
  </table>
  <a href="fcs_sd_report_addproblem.php?leak_id=<?=$leak_id?>">Add Problem</a><br><br>
  <a href="javascript:refreshdocuments()">Refresh Images</a><br>
  <div id="supporting_documents_display">
  
  </div>
  </div> <?php // end div app_problems ?>
  <?php } ?>


  <?php if($priority_id==4 && $proposal_locked==1){  ?>
  <br><br>
  <a name="proposal"></a>
  <span id="proposal_arrow"><a href="javascript:ShowHide('proposal', '1')"><img src="images/redexpand_right.png" border="0"></a></span> <strong>Proposal</strong>
  <div id="proposal" style="display:none;">
  <table class="main">
  <tr>
  <td>Building Front Image:</td>
  <td><input type="file" name="imagefront"><input type="submit" name="submit1" value="Update"></td>
  </tr>
  <?php if($image_front != ""){ ?>
  <tr>
  <td colspan="2"><a href="<?=$CORE_URL?>uploaded_files/properties/<?=$image_front?>" target="_blank">View current building front image</a></td>
  </tr>
  <?php } ?>
  <tr>
  <td>Proof Doc:</td>
  <td><input type="file" name="proofdoc"><input type="submit" name="submit1" value="Update"></td>
  </tr>
  <?php if($proofdoc != ""){ ?>
  <tr>
  <td colspan="2"><a href="uploaded_files/proofdoc/<?=$proofdoc?>" target="_blank">Existing Proof Doc</a></td>
  </tr>
  <?php } ?>
  </table>
  <div id="proposal_info"></div>
  </div><?php // end collapsable proposal?>
  <script>
  proposal();
  </script>
  <?php } // end of priority 4 ?>
<br><br> 
  

  

</td>
</tr>
</table>
</form>

<script>
InvoiceType();
PromotionalType();
ChangeServiceman(document.fcsreport.servicemen_id);
refreshdocuments();
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
<script>
<?php
$arrow_array = array("resourcelog", "attach", "resourceinvoice", "newinvoice", "man_problems", "proposal");
for($x=0;$x<sizeof($arrow_array);$x++){
  $foo = $arrow_array[$x];
  if($_SESSION[$leak_id . '_' . $foo] == 1){
    ?>
	ShowHide('<?=$foo?>', 1);
	<?php
  }
}
?>
</script>
</body>
