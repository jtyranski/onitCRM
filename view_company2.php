<?php include "includes/header.php"; ?>
<?php
$prospect_id = $_GET['prospect_id'];
$sql = "SELECT * from prospects where prospect_id='$prospect_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$company_name = stripslashes($record['company_name']);
$address = stripslashes($record['address']);
$city = stripslashes($record['city']);
$state = stripslashes($record['state']);
$zip = stripslashes($record['zip']);
$website = stripslashes($record['website']);
$logo = stripslashes($record['logo']);
$google_search = $company_name;

if($company_name=="") $company_name = "[COMPANY NAME]";
if($address=="") $address = "[ADDRESS]";
if($city=="") $city = "[CITY]";
if($state=="") $state = "XX";
if($zip=="") $zip = "[ZIP]";
if($website=="") $website = "[WEBSITE]";



?>
<script>
function editField(field){
  url="company_edit.php?prospect_id=<?=$prospect_id?>&action=form&field=" + field;
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

function updateField(f){
  
  field = document.forms[f].field.value;
  newvalue = document.forms[f].newvalue.value;
  url = "company_edit.php?prospect_id=<?=$prospect_id?>&action=update&field=" + field + "&newvalue=" + newvalue;
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

function goMenu(x, url, total){
  for(z=0;z<total;z++){
    document.getElementById('sub_' + z).setAttribute("class", "sub_off");
  }
  document.getElementById('sub_' + x).setAttribute("class", "sub_on");
  document.getElementById('welcomeframe').src=url + "?prospect_id=<?=$prospect_id?>";
}

</script>
<div align="center">
  <div class="whiteround" style="height:300px; text-align:left;">
  <div style="position:relative;">
  <div id="company_name_display" style="float:left;" class="main_large"><?=$company_name?></div>
  <div style="float:right;">
  <?php // buttons ?>
  <?php
  ImageLink("../am_ghost_login.php?prospect_id=$prospect_id", "fcs-icon", 1, 0);
  ?>
  <?php
  ImageLink("http://www.google.com/search?q=$google_search", "google-icon", 1, 0);
  ?>
  <?php
  ImageLink("makemap.php?boxtype=prospect&pid=$prospect_id", "us-icon", 1, 0);
  ?>
  <?php
  ImageLink("contacts.php", "back-icon", 1, 0);
  ?>
  </div>
  </div>
  <div style="clear:both;"></div>
  <hr size="1" color="#9a000c">
  
  
  <div style="height:250px; overflow:auto; width:100%;">
  
  <table width="100%">
  <tr>
  <td valign="top">
  <span id="company_name" style="font-weight:bold;"><a href="javascript:editField('company_name')" class="blankclick"><?=$company_name?></a></span><br>
  <span id="address"><a href="javascript:editField('address')" class="blankclick"><?=$address?></a></span><br>
  <div style="float:left;"><span id="city"><a href="javascript:editField('city')" class="blankclick"><?=$city?></a></span>, &nbsp;</div>
  <div style="float:left;"><span id="state"><a href="javascript:editField('state')" class="blankclick"><?=$state?></a></span>&nbsp;</div> 
  <div id="zip" style="float:left;"><a href="javascript:editField('zip')" class="blankclick"><?=$zip?></a></div><br>
  <div style="clear:both;"></div>
  <div id="website"><a href="javascript:editField('website')" class="blankclick"><?=$website?></a></div>
  
  
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
	
	if($firstname=="") $firstname = "[FIRSTNAME]";
	if($lastname=="") $lastname = "[LASTNAME]";
	if($position=="") $position = "[POSITION]";
	if($phone=="") $phone = "[PHONE]";
	if($fax=="") $fax = "[FAX]";
	if($mobile=="") $mobile = "[MOBILE]";
	if($email=="") $email = "[EMAIL]";
	
	if($counter==0) echo "<div style='position:relative; width:100%'>";
    ?>
	<div style="width:25%; float:left;">
	  <div style="float:left;">
	  <span id="<?=$id?>_firstname"><a href="javascript:editContact('firstname', '<?=$id?>')" class="blankclick"><?=$firstname?></a></span>&nbsp;
	  </div>
	  <div style="float:left;">
	  <span id="<?=$id?>_lastname"><a href="javascript:editContact('lastname', '<?=$id?>')" class="blankclick"><?=$lastname?></a></span>
	  </div>
	  <div style="clear:both;"></div>
	  <div id="<?=$id?>_position"><a href="javascript:editContact('position', '<?=$id?>')" class="blankclick"><?=$position?></a></div>
	  <div id="<?=$id?>_phone"><a href="javascript:editContact('phone', '<?=$id?>')" class="blankclick"><?=$phone?></a></div>
	  <div id="<?=$id?>_fax"><a href="javascript:editContact('fax', '<?=$id?>')" class="blankclick"><?=$fax?></a></div>
	  <div id="<?=$id?>_mobile"><a href="javascript:editContact('mobile', '<?=$id?>')" class="blankclick"><?=$mobile?></a></div>
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
  
  </td>
  </tr>
  </table>
  
	  
	
  
  
  
  </div><?php // end of overflow div ?>
  </div>
</div>

<div style="height:10px;"><img src="images/spacer.gif"></div>

<?php
$menu = array("Properties", "Notes", "Opportunities", "Calendar");
$urls = array("frame_prospect_properties.php", "frame_prospect_notes.php", "frame_prospect_opportunities.php", "frame_prospect_activities.php");

$sql = "SELECT property_type from prospects where prospect_id='$prospect_id'";
$property_type = getsingleresult($sql);
if($property_type=="Contractor"){
  $menu = array("Notes", "Activities", "Profile Info", "Profile Preview", "View Projects");
  $urls = array("frame_prospect_notes.php", "frame_prospect_activities.php", "../frame_prospect_contractorprofileinfo.php", 
  "../frame_prospect_contractorprofilepreview.php", "../frame_prospect_contractorprojects.php");
}

?>
<div style="width:100%; height:50px; background:url(images/menu-bar-pattern.png); background-repeat:repeat-x; padding-top:6px;">
  <div style="float:left; height:38px; width:4px;"><img src="images/menu-bar-left.png"></div>
  <?php
  for($x=0;$x<sizeof($menu);$x++){ ?>
    <div id="sub_<?=$x?>" style="float:left;" class="sub_off" onclick="goMenu('<?=$x?>', '<?=$urls[$x]?>', '<?=sizeof($menu)?>')" onMouseOver="this.style.cursor='pointer'">
	<?=$menu[$x]?>
	</div>
	<?php
  }
  ?>
  <div style="float:left; height:38px; width:4px;"><img src="images/menu-bar-right.png"></div>
  <div style="clear:both;"></div>
</div>
<div align="center">
  <div class="whiteround" style="height:300px; text-align:left;">
    <iframe width="100%" height="298" name="welcomeframe" id="welcomeframe" style="border:none; height:298px;"></iframe>
  </div>
</div>

<div class="whiteround" id="debug" style="display:none;">
</div>
<script>
goMenu('0', '<?=$urls[0]?>', '<?=sizeof($menu)?>');
</script>
<?php include "includes/footer.php"; ?>