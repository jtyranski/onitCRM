<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
if($prospect_id=="") exit;
$sql = "SELECT * from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company_name = stripslashes($record['company_name']);
$address = stripslashes($record['address']);
$address2 = stripslashes($record['address2']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
$website = stripslashes($record['website']);
$logo = stripslashes($record['logo']);
$master_stage_id = $record['master_stage_id'];
$master_status_id = $record['master_status_id'];
$resource = $record['resource'];
$billto_address = stripslashes($record['billto_address']);
$cust_id = stripslashes($record['cust_id']);

$sql = "SELECT irep from properties where prospect_id='$prospect_id' and corporate=1";
$irep = getsingleresult($sql);
if($irep != 0){
  $sql = "SELECT concat(firstname, ' ', lastname) from users where user_id='$irep'";
  $irep_name = stripslashes(getsingleresult($sql));
}

$sql = "SELECT master_stage from master_stage where master_stage_id='$master_stage_id'";
$master_stage = stripslashes(getsingleresult($sql));

$sql = "SELECT master_status from master_status where master_status_id='$master_status_id'";
$master_status = stripslashes(getsingleresult($sql));
/*
$has_act = $record['has_act'];
$has_opp = $record['has_opp'];
$fcs_rep = $record['fcs_rep'];

$sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='$fcs_rep'";
$fcs_rep_name = stripslashes(getsingleresult($sql));
*/
$google_search = $company_name;


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
  if($company_name=="" || $company_name==" ") $company_name = "[COMPANY NAME]";
  if($address=="" || $address==" ") $address = "[ADDRESS]";
  if($address2=="" || $address2==" ") $address2 = "[ADDRESS2]";
  if($city=="" || $city== " ") $city = "[CITY]";
  if($state=="" || $state==" ") $state = "XX";
  if($zip=="" || $zip==" ") $zip = "[ZIP]";
  if($website=="" || $website==" ") $website = "[WEBSITE]";
  if($fcs_rep_name=="" || $fcs_rep_name==" ") $fcs_rep_name = "[REP]";
  if($billto_address=="" || $billto_address==" ") $billto_address = "[BILL TO ADDRESS]";
  if($cust_id=="" || $cust_id == " ") $cust_id = "[NONE]";
  if($irep_name=="" || $irep_name == " ") $irep_name = "[NONE]";
}


?>
<script>
function editField(field){
  url="company_edit.php?prospect_id=<?=$prospect_id?>&action=form&field=" + field;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').style.display="";
	//document.getElementById('debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
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
  url = "company_edit.php?prospect_id=<?=$prospect_id?>&action=update&field=" + field + "&newvalue=" + newvalue;
    url=url+"&sid="+Math.random();
	//document.getElementById('debug').style.display="";
	//document.getElementById('debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
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
    document.location.href="delete_contact.php?id=" + id + "&type=company&pid=<?=$prospect_id?>";
  }
}

function goMenu(x, url, total){
  for(z=0;z<total;z++){
    document.getElementById('sub_' + z).setAttribute("class", "sub_off");
  }
  document.getElementById('sub_' + x).setAttribute("class", "sub_on");
  document.getElementById('welcomeframe').src=url + "?prospect_id=<?=$prospect_id?>";
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
  <div style="float:left;"><span class="main_large" id="company_name_display"><?=$company_name?></span>
  
    <?php if($SESSION_IPAD_EDIT_MODE == 1){ ?>
      <br>Customer ID: <span id="cust_id"><a href="javascript:editField('cust_id')" class="blankclick_red"><?=$cust_id?></a></span>
	<?php } else {
	   if($cust_id != ""){ 
	  echo "<br>Customer ID: " . $cust_id;
	   }
	?>
  <?php } ?>
  </div>
  <div style="float:right;">
  <?php // buttons ?>
  <?php
  ImageLink("editmode.php?type=company&pid=$prospect_id", "gear-icon", 1, 0, "Switch Edit/View Mode");
  ?>
  <?php
  ImageLink("direct_email.php?prospect_id=$prospect_id", "mail-icon", 0, 0, "Email");
  ?>
  <?php
  ImageLink("window.open('am_ghost_login.php?prospect_id=$prospect_id')", "fcs-icon", 1, 1, "See Client View Mode");
  ?>
  <?php
  ImageLink("http://www.google.com/search?q=$google_search", "google-icon", 1, 0, "Google Search");
  ?>
  <?php
  /*
  if($check_youtube){
    ImageLink("toolbox.php?go=youtube_edit.php*prospect_id=$prospect_id", "youtube-icon", 1, 0);
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
  <span id="company_name" style="font-weight:bold;"><a href="javascript:editField('company_name')" class="blankclick_red"><?=$company_name?></a></span><br>
  <span id="address"><a href="javascript:editField('address')" class="blankclick_red"><?=$address?></a></span><br>
  <span id="address2"><a href="javascript:editField('address2')" class="blankclick_red"><?=$address2?></a></span><br>
  <div style="float:left;"><span id="city"><a href="javascript:editField('city')" class="blankclick_red"><?=$city?></a></span>, &nbsp;</div>
  <div style="float:left;"><span id="state"><a href="javascript:editField('state')" class="blankclick_red"><?=$state?></a></span>&nbsp;</div> 
  <div id="zip" style="float:left;"><a href="javascript:editField('zip')" class="blankclick_red"><?=$zip?></a></div><br>
  Independent Rep: <span id="irep"><a href="javascript:editField('irep')" class="blankclick_red"><?=$irep_name?></a></span>
  <?php } else { ?>
  <span id="company_name" style="font-weight:bold;"><a href="javascript:gomap('bing')" class="blankclick"><?=$company_name?></a></span><br>
  <span id="address"><a href="javascript:gomap('bing')" class="blankclick"><?=$address?></a></span><br>
  <?php if($address2 != ""){ ?>
  <span id="address2"><a href="javascript:gomap('bing')" class="blankclick"><?=$address2?></a></span><br>
  <?php } ?>
  <div style="float:left;"><span id="city"><a href="javascript:gomap('bing')" class="blankclick"><?=$city?></a></span>, &nbsp;</div>
  <div style="float:left;"><span id="state"><a href="javascript:gomap('bing')" class="blankclick"><?=$state?></a></span>&nbsp;</div> 
  <div id="zip" style="float:left;"><a href="javascript:gomap('bing')" class="blankclick"><?=$zip?></a></div><br>
  <?php } ?>
  <div style="clear:both;"></div>
  <div id="website"><a href="javascript:editField('website')" class="blankclick"><?=$website?></a></div>
  <?php /*
  Rep: <span id="fcs_rep"><a href="javascript:editField('fcs_rep')" class="blankclick"><?=$fcs_rep_name?></a></span>
  */ ?>
  
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
  <a href="add_contact.php?prospect_id=<?=$prospect_id?>&property_id=0">add new contact</a>
  <?php
  $sql = "SELECT * from contacts where prospect_id='$prospect_id' order by id";
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
	  <span id="<?=$id?>_firstname"><a href="javascript:editContact('firstname', '<?=$id?>')" class="blankclick"><?=$firstname?></a></span>&nbsp;
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
  <?php if($logo != ""){ ?>
  <img src="<?=$UPLOAD?>logos/<?=$logo?>"><br>
  <?php } ?>
  <div id="logo"><a href="javascript:editField('logo')" class="blankclick">EDIT LOGO</a></div>
  <?php if($SESSION_MASTER_ID==1){?>
  <div id="master_status_id"><a href="javascript:editField('master_status_id')" class="blankclick">Status: <?=$master_status?></a></div>
  <div id="master_stage_id"><a href="javascript:editField('master_stage_id')" class="blankclick">Stage :<?=$master_stage?></a></div>
  <?php } ?>
  </td>
  </tr>
  </table>
  
	  
	
  
  
  
  </div><?php // end of overflow div ?>
  </div>
</div>

<div style="height:10px;"><img src="images/spacer.gif"></div>

<?php
if($resource==0){
$prop_filler = "Properties";
if($SESSION_MASTER_ID==1) $prop_filler = "Associates";

$sql = "SELECT industry from prospects where prospect_id='$prospect_id'";
$industry = getsingleresult($sql);

if($industry==1){
  $menu = array("Notes", "Activities");
  $urls = array("frame_prospect_notes.php", "frame_prospect_activities.php");
}
else {
  $menu = array($prop_filler, "Notes", "Activities");
  $urls = array("frame_prospect_properties.php", "frame_prospect_notes.php", "frame_prospect_activities.php");
}

if($SESSION_OPPORTUNITIES==1){
  $menu[] = "Opportunities";
  $urls[] = "frame_prospect_opportunities.php";
}
$menu[] = "Contract";
$urls[] = "frame_prospect_contract.php";

if($industry==0){
  $menu[] = "Portal Users";
  $urls[] = "frame_prospect_portalusers.php";
}



}
else {
  $menu = array("Dispatch", "Activities", "Profile", "Users", "Notes", "Disciplines");
  $urls = array("frame_prospect_dispatch.php", "frame_prospect_activities.php", "frame_prospect_profile.php", "frame_prospect_resourceusers.php", "frame_prospect_notes.php", "frame_prospect_disciplines.php");
}

if($SESSION_USE_GROUPS){
  $menu[] = "Groups";
  $urls[] = "frame_prospect_groups.php";
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
