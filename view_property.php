<?php include "includes/header.php"; ?>
<?php
$property_id = $_GET['property_id'];
if($property_id=="") exit;
$sql = "SELECT * from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$site_name = stripslashes($record['site_name']);
$address = stripslashes($record['address']);
$address2 = stripslashes($record['address2']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
$roof_size = stripslashes($record['roof_size']);
$image = stripslashes($record['image']);
$image_front = stripslashes($record['image_front']);
$logo = $record['logo'];
$sd_contact = $record['sd_contact'];
$budgetmatrix_in_report = $record['budgetmatrix_in_report'];
$billto_address = stripslashes($record['billto_address']);
$irep = $record['irep'];
$custom_id = $record['custom_id'];
$report_image = stripslashes($record['report_image']);
if($budgetmatrix_in_report==1){
  $bm_display = "Budget Matrix: Yes";
}
else {
  $bm_display = "Budget Matrix: No";
}

if($report_image=='image'){
  $report_image_display = "Report Cover: Overhead Image";
}
if($report_image=="image_front"){
  $report_image_display = "Report Cover: Front Image";
}

if($irep != 0){
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$irep'";
  $irep_name = stripslashes(getsingleresult($sql));
}

$google_search = $site_name . " " . $address . " " . $city . " " . $state;
$googlemap = "http://maps.google.com/maps?q=";
$googlemap .= $address . ", " . $city . ", " . $state . ", " . $zip;
$bingmap = "http://www.bing.com/maps/?v=2&where1=" . $address . " " . $city . " " . $state . " " . $zip;

$googlemap = go_reg_replace("\'", "", $googlemap);
$googlemap = go_reg_replace("\"", "", $googlemap);

$bingmap = go_reg_replace("\'", "", $bingmap);
$bingmap = go_reg_replace("\"", "", $bingmap);

$google_search = go_reg_replace("\'", "", $google_search);
$google_search = go_reg_replace("\"", "", $google_search);
/*
$sql = "SELECT count(user_id) from users where user_id='" . $SESSION_USER_ID . "' and tools_active like '%,22,%'";
$check_youtube = getsingleresult($sql);
*/
if($SESSION_IPAD_EDIT_MODE == 1){
  if($site_name=="" || $site_name==" ") $site_name = "[SITE NAME]";
  if($address=="" || $address==" ") $address = "[ADDRESS]";
  if($address2=="" || $address2==" ") $address2 = "[ADDRESS2]";
  if($city=="" || $city== " ") $city = "[CITY]";
  if($state=="" || $state==" ") $state = "XX";
  if($zip=="" || $zip==" ") $zip = "[ZIP]";
  if($irep_name=="" || $irep_name == " ") $irep_name = "[NONE]";
  if($custom_id=="" || $custom_id == " ") $custom_id = "[NONE]";
  if($billto_address=="" || $billto_address==" ") $billto_address = "[BILL TO ADDRESS]";
}

$sql = "SELECT prospect_id from properties where property_id='$property_id'";
$prospect_id = getsingleresult($sql);

$sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
$company_name = stripslashes(getsingleresult($sql));

?>
<link rel="stylesheet" href="lytebox/lytebox.css" type="text/css" media="screen" />
<script>
function editField(field){
  url="property_edit.php?property_id=<?=$property_id?>&action=form&field=" + field;
    url=url+"&sid="+Math.random();
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function updateField(f){
  
  field = document.forms[f].field.value;
  newvalue = document.forms[f].newvalue.value;
  newvalue = newvalue.replace(/\&/g, "AMPERSAND");
  newvalue = newvalue.replace(/\#/g, "POUNDSIGN");
  newvalue = newvalue.replace(/\"/g, "DBLQUOTE");
  newvalue = newvalue.replace(/\n/g, "NEWLINE");
  newvalue = newvalue.replace(/\(/g, "PLEFT");
  newvalue = newvalue.replace(/\)/g, "PRIGHT");
  url = "property_edit.php?property_id=<?=$property_id?>&action=update&field=" + field + "&newvalue=" + newvalue;
    url=url+"&sid="+Math.random();
	 //document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function editContact(field, id){
  url="contact_edit.php?id=" + id + "&action=form&field=" + field;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function updateContact(f, id){
  
  field = document.forms[f].field.value;
  newvalue = document.forms[f].newvalue.value;
  url = "contact_edit.php?id=" + id + "&action=update&field=" + field + "&newvalue=" + newvalue;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function delContact(id){
  cf = confirm("Are you sure you want to delete this contact?");
  if(cf){
    document.location.href="delete_contact.php?id=" + id + "&type=property&pid=<?=$property_id?>";
  }
}

function goMenu(x, url, total){
  for(z=0;z<total;z++){
    document.getElementById('sub_' + z).setAttribute("class", "sub_off");
  }
  document.getElementById('sub_' + x).setAttribute("class", "sub_on");
  document.getElementById('welcomeframe').src=url + "?property_id=<?=$property_id?>";
}

function mapswap(){
  document.getElementById('us_show').style.display="none";
  document.getElementById('us_expand').style.display="";
}

function gomap(x){
  document.getElementById('us_show').style.display="";
  document.getElementById('us_expand').style.display="none";
  
  if(x=='google'){
    window.open('<?=$googlemap?>');
  }
  if(x=='bing'){
    window.open('<?=$bingmap?>');
  }
}

function DelImage(){
  cf = confirm("Warning! Deleting this image will also delete all section images and all section deficiency coordinates.  Do you want to continue?");
  if(cf){
    document.location.href="view_property_delete_image.php?property_id=<?=$property_id?>";
  }
}

function DelImageFront(){
  cf = confirm("Are you sure you wish to delete this image?");
  if(cf){
    document.location.href="view_property_delete_image_front.php?property_id=<?=$property_id?>";
  }
}

</script>
<script src="includes/jquery-1.6.4.js"></script>
<script src="includes/jquery.maskedinput-1.3.js"></script>
<script src="includes/jquery.livequery.js"></script>
<script type="text/javascript"> 
$(".phoneext").livequery(function(){
    $(this).mask('(999) 999-9999? x99999');
});
</script>

<div align="center">
  <div class="whiteround" style="height:300px; text-align:left;">
  <div style="position:relative;">
  <div style="float:left;">
  <span class="main_large"><a href="view_company.php?prospect_id=<?=$prospect_id?>" class="blankclick"><?=$company_name?></a> > </span>
  <span id="site_name_display"  class="main_large"><?=$site_name?></span>
  
    <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
      <br>Building ID: <span id="custom_id"><a href="javascript:editField('custom_id')" class="blankclick_red"><?=$custom_id?></a></span>
	<?php } else {
	  if($custom_id != ""){ 
	    echo "<br>Building ID: " . $custom_id;
	  }
	?>
  <?php } ?>
  </div>
  <div style="float:right; position:relative;">
  <?php // buttons ?>
  <?php
  ImageLink("editmode.php?type=property&pid=$property_id", "gear-icon", 0, 0, "Switch Edit/View Mode");
  ?>
  <?php
  ImageLink("direct_email.php?property_id=$property_id", "mail-icon", 0, 0, "Email");
  ?>
  <?php
  ImageLink("window.open('am_ghost_login.php?prospect_id=$prospect_id&property_id=$property_id')", "fcs-icon", 1, 1, "See Client View Mode");
  ?>
  <?php
  ImageLink("window.open('http://www.google.com/search?q=$google_search')", "google-icon", 1, 1, "Google Search");
  ?>
  <?php
  /*
  if($check_youtube){
    ImageLink("toolbox.php?go=youtube_edit.php*prospect_id=$prospect_id*property_id=$property_id", "youtube-icon", 1, 0);
  }
  */
  ?>
  <?php
  ImageLink("contacts.php", "back-icon", 1, 0, "Go Back");
  ?>
  

  </div>
    <div id="us_show" style="float:right;">
	<a href="javascript:mapswap()" title="Search on Map">
	<img src="images/us-icon_off.png" border="0">
	</a>
	</div>
	<div id="us_expand" style="display:none; float:right;">
	<a href="javascript:gomap('google')" title="Search on Google Map">
	<img src="images/googlemap-icon.png" border="0">
	</a>
	<br>
	<a href="javascript:gomap('bing')" title="Search on Bing Map">
	<img src="images/bingmap-icon.png" border="0">
	</a>
	</div>
	
	<div style="float:right;">
	<?php
	$sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
    $test = getsingleresult($sql);
    if($test != 0){
      ImageLink("am_ghost_login.php?prospect_id=$prospect_id&property_id=$property_id&redirect=leakcheck.php**property_id=$property_id*force_internal=1", "sd-icon", 0, 0, "Service Dispatch");
	}
    ?>
	</div>
  </div>
  <div style="clear:both;"></div>
  <hr size="1" color="#9a000c">
  
  
  <div style="height:250px; overflow:auto; width:100%;">
  
  <table width="100%">
  <tr>
  <td valign="top">
  <div style="position:relative;">
  <div style="float:left;">
  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
  <span id="site_name" style="font-weight:bold;"><a href="javascript:editField('site_name')" class="blankclick_red"><?=$site_name?></a></span><br>
  <span id="address"><a href="javascript:editField('address')" class="blankclick_red"><?=$address?></a></span><br>
  <span id="address2"><a href="javascript:editField('address2')" class="blankclick_red"><?=$address2?></a></span><br>
  <div style="float:left;"><span id="city"><a href="javascript:editField('city')" class="blankclick_red"><?=$city?></a></span>,&nbsp;</div>
  <div style="float:left;"><span id="state"><a href="javascript:editField('state')" class="blankclick_red"><?=$state?></a></span>&nbsp;</div> 
  <div id="zip" style="float:left;"><a href="javascript:editField('zip')" class="blankclick_red"><?=$zip?></a></div><br>
  <div style="float:left;">Total Squares: <span id="roof_size"><a href="javascript:editField('roof_size')" class="blankclick_red"><?=$roof_size?></a></span></div><br>
  Independent Rep: <span id="irep"><a href="javascript:editField('irep')" class="blankclick_red"><?=$irep_name?></a></span>
  <?php } else { ?>
  <span id="site_name" style="font-weight:bold;"><a href="javascript:gomap('bing')" class="blankclick"><?=$site_name?></a></span><br>
  <span id="address"><a href="javascript:gomap('bing')" class="blankclick"><?=$address?></a></span><br>
  <?php if($address2 != ""){ ?>
  <span id="address2"><a href="javascript:gomap('bing')" class="blankclick"><?=$address2?></a></span><br>
  <?php } ?>
  <div style="float:left;"><span id="city"><a href="javascript:gomap('bing')" class="blankclick"><?=$city?></a></span>,&nbsp;</div>
  <div style="float:left;"><span id="state"><a href="javascript:gomap('bing')" class="blankclick"><?=$state?></a></span>&nbsp;</div> 
  <div id="zip" style="float:left;"><a href="javascript:gomap('bing')" class="blankclick"><?=$zip?></a></div><br>
    <?php if($irep != 0) echo "Independent Rep: $irep_name<br>"; ?>
  <?php } ?>

  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
  <br>
  <form action="view_property_edit_action.php" method="post">
  <input type="hidden" name="property_id" value="<?=$property_id?>">
  <select name="sd_contact">
  <option value="0">None</option>
  <?php
  $sql = "SELECT id, concat(firstname, ' ', lastname) as fullname from contacts where property_id='$property_id' and firstname != '' order by id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	<option value="<?=$record['id']?>"<?php if($record['id']==$sd_contact) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
	<?php
  }
  ?>
  </select>
  <input type="submit" name="submit1" value="Edit Service Dispatch Contact">
  </form>
  <?php } ?>
  
  </div>
  <div style="float:left; padding-left:25px;">
  <?php if($billto_address != "") echo "Bill To Address:<br>"; ?>
  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
  <span id="billto_address"><a href="javascript:editField('billto_address')" class="blankclick_red"><?=nl2br($billto_address)?></a></span>
  <?php } else { ?>
  <?=nl2br($billto_address)?>
  <?php } ?>
  </div>
  </div>
  <div style="clear:both;"></div>
  
  <br>
  <a href="add_contact.php?property_id=<?=$property_id?>&prospect_id=0">add new contact</a>
  <?php
  $sql = "SELECT * from contacts where property_id='$property_id' order by id";
  $result = executequery($sql);
  $counter = 0;
  while($record = go_fetch_array($result)){
    $id = $record['id'];
    $firstname = stripslashes($record['firstname']);
	$lastname = stripslashes($record['lastname']);
	$position = stripslashes($record['position']);
	$phone = stripslashes($record['phone']);
	$fax = stripslashes($record['fax']);
	$mobile = stripslashes($record['mobile']);
	$email = stripslashes($record['email']);
	$businesscard = stripslashes($record['businesscard']);
	
	$phone_raw = $phone;
	$mobile_raw = $mobile;
	
	if($phone != "") $phone = "p:" . $phone;
	if($fax != "") $fax = "f:" . $fax;
	if($mobile != "") $mobile = "m:" . $mobile;
	
	if($SESSION_IPAD_EDIT_MODE == 1){
	  if($firstname=="") $firstname = "[FIRSTNAME]";
	  if($lastname=="") $lastname = "[LASTNAME]";
	  if($position=="") $position = "[POSITION]";
	  if($phone=="") $phone = "[PHONE]";
	  if($fax=="") $fax = "[FAX]";
	  if($mobile=="") $mobile = "[MOBILE]";
	  if($email=="") $email = "[EMAIL]";
	}
	
	if($counter==0) echo "<div style='position:relative; width:100%'>";
    ?>
	<div style="width:25%; float:left;">
	  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
	  <a href="javascript:delContact('<?=$id?>')">[DELETE]</a><br>
	  <?php } ?>
	  <div style="float:left;">
	  <span id="<?=$id?>_firstname"><a href="javascript:editContact('firstname', '<?=$id?>')" class="blankclick"><?=$firstname?></a></span> 
	  &nbsp;
	  </div>
	  <div style="float:left;">
	  <span id="<?=$id?>_lastname"><a href="javascript:editContact('lastname', '<?=$id?>')" class="blankclick"><?=$lastname?></a></span>
	  <?php if($businesscard != ""){ ?>
	  &nbsp;(<a href="uploaded_files/businesscards/<?=$businesscard?>" target="_blank">card</a>)
	  <?php } ?>
	  </div>
	  <div style="clear:both;"></div>
	  <div id="<?=$id?>_position"><a href="javascript:editContact('position', '<?=$id?>')" class="blankclick"><?=$position?></a></div>
	  <div id="<?=$id?>_phone">
	  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
	  <a href="javascript:editContact('phone', '<?=$id?>')" class="blankclick">
	  <?php } else { ?>
	  <a href="tel:<?=$phone_raw?>" class='blankclick'>
	  <?php } ?>
	  <?=$phone?></a></div>
	  <div id="<?=$id?>_fax"><a href="javascript:editContact('fax', '<?=$id?>')" class="blankclick"><?=$fax?></a></div>
	  <div id="<?=$id?>_mobile">
	  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
	  <a href="javascript:editContact('mobile', '<?=$id?>')" class="blankclick">
	  <?php } else { ?>
	  <a href="tel:<?=$mobile_raw?>" class='blankclick'>
	  <?php } ?>
	  <?=$mobile?></a></div>
	  <div id="<?=$id?>_email"><a href="javascript:editContact('email', '<?=$id?>')" class="blankclick"><?=$email?></a></div>
	</div>
	<?php
	$counter++;
	if($counter==4){
	  echo "</div><div style='clear:both;'></div>";
	  $counter = 0;
	}
  }
  
  if($counter != 0) echo "</div>"; //<div style='clear:both;'></div>";
  ?>
	  
	
  </td>
  
  <td valign="top" width="150">
  <?php if($image_front != ""){ 
  $size = getimagesize($UPLOAD . "properties/" . $image_front);
  $width = $size[0];
  if($width > 150) $width = 150;
  ?>
  <a href="<?=$UPLOAD?>properties/<?=$image_front?>" target="_blank"><img src="<?=$UPLOAD?>properties/<?=$image_front?>" width="<?=$width?>" border="0"></a><br>
  <?php } ?>
  <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
  <a href="rotate.php?image=<?=$image_front?>&path=properties&degrees=90&redirect=<?=$_SERVER['SCRIPT_NAME']?>&property_id=<?=$property_id?>&idname=property_id&id=<?=$property_id?>&table=properties&photoname=image_front">
  <img src="images/rotate270.png" border="0">
  </a>
  <a href="rotate.php?image=<?=$image_front?>&path=properties&degrees=270&redirect=<?=$_SERVER['SCRIPT_NAME']?>&property_id=<?=$property_id?>&idname=property_id&id=<?=$property_id?>&table=properties&photoname=image_front">
  <img src="images/rotate90.png" border="0">
  </a>
  <?php } ?>
  <?php if($SESSION_ISADMIN==1 && $image_front != ""){?>
    <div id="image_front"><a href="javascript:DelImageFront()" class="blankclick">DELETE IMAGE</a></div>
  <?php } ?>
  
  <?php 
  
  if($image_front==""){ ?>
  <div id="image_front"><a href="javascript:editField('image_front')" class="blankclick">ADD FRONT IMAGE</a></div>
  <?php } 
  else {
    ?>
    <div id="report_image"><a href="javascript:editField('report_image')" class="blankclick"><?=$report_image_display?></a></div>
  <?php }
  ?>
  </td>
  
  
  
  <td valign="top" width="150">
  <?php if($image != ""){ 
  $size = getimagesize($UPLOAD . "properties/" . $image);
  $width = $size[0];
  if($width > 150) $width = 150;
  ?>
  <a href="<?=$UPLOAD?>properties/<?=$image?>" target="_blank"><img src="<?=$UPLOAD?>properties/<?=$image?>" width="<?=$width?>" border="0"></a><br>
  <?php } ?>
  <?php if($SESSION_ISADMIN==1 && $image != ""){?>
    <div id="image"><a href="javascript:DelImage()" class="blankclick">DELETE IMAGE</a></div>
  <?php } ?>
  <?php 
  
  if($image==""){ ?>
  <div id="image"><a href="javascript:editField('image')" class="blankclick">ADD IMAGE</a></div>
  <?php } 
  
  ?>
  <?php if($SESSION_MULTILOGO==1){?>
    <div id="logo"><a href="javascript:editField('logo')" class="blankclick">CHANGE LOGO</a></div>
  <?php } ?>
  <div id="budgetmatrix_in_report"><a href="javascript:editField('budgetmatrix_in_report')" class="blankclick"><?=$bm_display?></a></div>
  </td>
  </tr>
  </table>
  <?php
  /* Moved up to icon bar
  $sql = "SELECT count(*) from toolbox_items where tool_master_id=3 and master_id='" . $SESSION_MASTER_ID . "'";
  $test = getsingleresult($sql);
  if($test != 0){
  ?>
  <a href="am_ghost_login.php?prospect_id=<?=$prospect_id?>&property_id=<?=$property_id?>&redirect=leakcheck.php**property_id=<?=$property_id?>*force_internal=1">
  <img src="images/service_dispatch.gif" border="0">
  </a>
  <?php } 
  */
  ?>
  </div><?php // end of overflow div ?>
  </div>
</div>

<div style="height:10px;"><img src="images/spacer.gif"></div>

<?php
$menu = array("Sections", "Notes", "Activities");
$urls = array("frame_property_sections.php", "frame_property_notes.php", "frame_property_activities.php");
if($SESSION_OPPORTUNITIES==1){
  $menu[] = "Opportunities";
  $urls[] = "frame_property_opportunities.php";
}
$menu[]="Documents";
$urls[] = "frame_property_drawings.php";
if($SESSION_USE_OPS){
  $sql = "SELECT count(*) from opm where property_id='$property_id'";
  $test = getsingleresult($sql);
  if($test){
    $menu[] = "Production";
	$urls[] = "frame_property_production.php";
  }
}
if($SESSION_USE_GROUPS){
  $menu[] = "Groups";
  $urls[] = "frame_property_groups.php";
}

?>
<div style="height:48px;" class="contactnavbox">
  <?php
  for($x=0;$x<sizeof($menu);$x++){ ?>
    <div id="sub_<?=$x?>" style="float:left;" class="sub_off" onclick="goMenu('<?=$x?>', '<?=$urls[$x]?>', '<?=sizeof($menu)?>')" onMouseOver="this.style.cursor='pointer'">
	<?=$menu[$x]?>
	</div>
	<?php
  }
  ?>
  <div style="clear:both;"></div>
</div>
<div align="center">
  <div class="whiteround" style="height:300px; text-align:left;">
    <iframe frameborder="0" width="100%" height="298" name="welcomeframe" id="welcomeframe" style="border:none; height:298px;"></iframe>
  </div>
</div>

<div class="whiteround" id="debug" style="display:none;">
</div>
<script>
<?php
if($_GET['view'] != ""){
  $view = $_GET['view'];
  $view = ucfirst($view);
  $key = array_search($view, $menu);
  ?>
  goMenu('<?=$key?>', '<?=$urls[$key]?>', '<?=sizeof($menu)?>');
  <?php
} else {
?>
goMenu('0', '<?=$urls[0]?>', '<?=sizeof($menu)?>');

<?php } ?>
</script>
<?php include "includes/footer.php"; ?>
