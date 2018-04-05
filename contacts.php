<?php include "includes/header.php"; ?>
<?php
$filterby = $_GET['filterby'];
$searchfor = $_GET['searchfor'];
$zip = $_GET['zip'];
$distance = $_GET['distance'];

$cand_type = $_GET['cand_type'];

$show_hidden = $_GET['show_hidden'];


$show_all_cand = $_GET['show_all_cand'];


$order_by = $_GET['order_by'];
$order_by2 = $_GET['order_by2'];
//if($order_by2 == "") $order_by2 = "asc";
$submit1 = $_GET['submit1'];
//if($submit1=="filter"){

//}

$stage_filter = $_GET['stage_filter'];
$status_filter = $_GET['status_filter'];
$identifier_filter = $_GET['identifier_filter'];
$user_filter = $_GET['user_filter'];
$obj_filter = $_GET['obj_filter'];
$active_filter = $_GET['active_filter'];
$resource_filter = $_GET['resource_filter'];

if($stage_filter=="") $stage_filter = $_SESSION[$sess_header . '_contacts2_stage_filter'];
if($status_filter=="") $status_filter = $_SESSION[$sess_header . '_contacts2_status_filter'];
if($identifier_filter=="") $identifier_filter = $_SESSION[$sess_header . '_contacts2_identifier_filter'];
if($user_filter=="") $user_filter = $_SESSION[$sess_header . '_contacts2_user_filter'];
if($obj_filter=="") $obj_filter = $_SESSION[$sess_header . '_contacts2_obj_filter'];
if($active_filter=="") $active_filter = $_SESSION[$sess_header . '_contacts2_active_filter'];
if($resource_filter=="") $resource_filter = $_SESSION[$sess_header . '_contacts2_resource_filter'];

if($cand_type=="") $cand_type = $_SESSION[$sess_header . '_contacts2_cand_type'];

if($searchfor=="")  $searchfor = $_SESSION[$sess_header . '_contacts2_searchfor'];
if($zip=="")  $zip = $_SESSION[$sess_header . '_contacts2_zip'];
if($distance=="")  $distance = $_SESSION[$sess_header . '_contacts2_distance'];
if($filterby=="")  $filterby = $_SESSION[$sess_header . '_contacts2_filterby'];
if($show_hidden=="")  $show_hidden = $_SESSION[$sess_header . '_contacts2_show_hidden'];
if($show_all_cand=="")  $show_all_cand = $_SESSION[$sess_header . '_contacts2_show_all_cand'];

if($order_by=="")  $order_by = $_SESSION[$sess_header . '_contacts2_order_by'];
if($order_by2=="")  $order_by2 = $_SESSION[$sess_header . '_contacts2_order_by2'];
  //if($order_by2 == "") $order_by2 = "asc";
  //$submit1 = "search";

  if($filterby=="") $filterby = 0;
  if($cand_type=="") {
    if($SESSION_OPPORTUNITIES==1) {
	  $cand_type = 2;
	}
	else {
	  $cand_type = 0;
	}
  }
  if($show_hidden=="") $show_hidden = 0;
  if($show_all_cand=="") $show_all_cand = 0;

$qs = "filterby=$filterby&searchfor=$searchfor&zip=$zip&distance=$distance&order_by=$order_by&order_by2=$order_by2&submit1=search&show_hidden=$show_hidden&show_all_cand=$show_all_cand";
$qs .= "&stage_filter=$stage_filter&status_filter=$status_filter&identifier_filter=$identifier_filter&user_filter=$user_filter&obj_filter=$obj_filter&active_filter=$active_filter&resource_filter=$resource_filter";

$qs2 = $qs . "&cand_type=$cand_type";

$show_filter = $_SESSION[$sess_header . '_contacts2_show_filter'];
if($show_filter=="") $show_filter = 1;
//if($filterby=="0" && $zip=="" && $show_hidden==0 && $show_all_cand==0) $show_filter = 0;
?>
<script>


function selectRecord(id){
  var loTable=document.getElementById("results_table");
  for(var liCount=0;liCount<loTable.rows.length;liCount++)
  {
    if(liCount % 2){
      loTable.rows[liCount].setAttribute("class", "altrow");
	}
	else {
	    loTable.rows[liCount].setAttribute("class", "mainrow");
	}
  }
  document.getElementById(id).setAttribute("class", "hilite");
  document.getElementById('contacts_info').style.display = "";
  slideGenerate(id, '1', 'contacts_info.php', '<?=$cand_type?>');
}

function highlightRecord(id){
  var loTable=document.getElementById("results_table");
  for(var liCount=0;liCount<loTable.rows.length;liCount++)
  {
    if(liCount % 2){
      loTable.rows[liCount].setAttribute("class", "altrow");
	}
	else {
	    loTable.rows[liCount].setAttribute("class", "mainrow");
	}
  }
  document.getElementById(id).setAttribute("class", "hilite");
}

function searchResults(qs){
document.getElementById('search_results_wrapper').style.display="";

div = document.getElementById('search_results');
div.innerHTML = "<img src=\"images/loading.gif\">Loading... Please Wait";
div.style.display = "";
  <?php if($SESSION_MASTER_ID==1){ ?>
  url="contacts_fcs_results.php" + qs;
  <?php } else { ?>
  url="contacts2_results.php" + qs;
  <?php } ?>
    url=url+"&sid="+Math.random();
	<?php //if($_SESSION[$sess_header . '_backup_login']==10){ ?>
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
	<?// } ?>
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
		selectRecord('0');
}


function showHideSearch(){
  var setting=1;
  if(document.getElementById('search_box_main').style.display=="none"){
    document.getElementById('search_box_main').style.display = "";
	setting = 1;
  }
  else {
    document.getElementById('search_box_main').style.display = "none";
	setting = 0;
  }
  // run a function to store in session to turn box off
  var url = "contacts_session_filter.php?setting=" + setting;
  var jsel = document.createElement('SCRIPT');
  jsel.type = 'text/javascript';
  jsel.src = url;

  // Append JS element (therefore executing the 'AJAX' call)
  document.body.appendChild (jsel);
}

var form='email'; //Give the form name here

function SetChecked(x,chkName) {
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
  showdelete();
}

function formsubmit(clicktype, newwin){
  document.forms["email"].clicktype.value=clicktype;
  if(newwin == "new"){
    document.forms["email"].target="_blank";
  }
  document.forms["email"].submit();
}

function confirmdelete(clicktype, newwin){
  var count=0;
  var cb = document.getElementsByTagName('input');
  for(i = 0; i < cb.length; i++)
 {
    if(cb[i].type == 'checkbox' && cb[i].checked==true && cb[i].id != 'checkallbox')
    {
        count++;
    }
  }
	cf = confirm("Are you sure you want to delete these (" + count + ") companies?");
	if(cf){
	  formsubmit(clicktype, newwin);
	}
}

function showdelete(){
  var count=0;
  var cb = document.getElementsByTagName('input');
  for(i = 0; i < cb.length; i++)
 {
    if(cb[i].type == 'checkbox' && cb[i].checked==true && cb[i].id != 'checkallbox')
    {
        count++;
    }
  }

  if(count !=0){
    document.getElementById('deletebutton').style.display="";
  }
  else {
    document.getElementById('deletebutton').style.display="none";
  }
}
	 
function load_cand_type(x){
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?cand_type=" + x;
}

function entsub(e, myform) {
  if(window.event)
keyPressed = window.event.keyCode;	// IE
else
keyPressed = e.which;	 // Firefox

  if (keyPressed == 13)
    myform.submit();
  else
    return true;}

function completeProspect(prospect_id){
  document.getElementById('search_results_wrapper').style.height="800px";
  url="contacts_complete_prospect.php?prospect_id=" + prospect_id;
    url=url+"&sid="+Math.random();
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
}

function master_active(master_id, act){
  if(act==1){
    cf = confirm("Are you sure you want to deactivate this client?");
	if(cf){
	  document.location.href="contacts_deactivate.php?master_id=" + master_id + "&active=0";
	}
  }
  else {
    document.location.href="contacts_deactivate.php?master_id=" + master_id + "&active=1";
  }
}

function nextGroup(x, order_by, order_by2, total_records){
  url="contacts_nextgroup.php?x=" + x + "&total_records=" + total_records;
    url=url+"&sid="+Math.random();
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
		searchResults('?<?=$qs2?>&order_by=' + order_by + '&order_by2=' + order_by2);
}
</script>
<?php
$menu = array("Prospects");
$cand_type_array = array("0");
if($SESSION_OPPORTUNITIES==1){
  $menu[] = "Candidate";
  $menu[] = "Client";
  $cand_type_array[] = "1";
  $cand_type_array[] = "2";
}
$menu[] = "Vendors";
$menu[] = "Help";
$cand_type_array[] = "3";
$cand_type_array[] = "4";
if($SESSION_MASTER_ID==1){
$menu[]="Demo";
$cand_type_array[]="5";
}

?>
<?php //if($show_filter==1){ ?>
<div align="center">
<div style="height:48px;" class="contactnavbox">

    <?php for($x=0;$x<sizeof($menu);$x++){
	$class="sub_off";
	if($cand_type_array[$x]==$cand_type) $class="sub_on";
	?>
    <div id="sub_<?=$x?>" style="float:left;" class="<?=$class?>" onclick="load_cand_type('<?=$cand_type_array[$x]?>')" onMouseOver="this.style.cursor='pointer'">
	<?=$menu[$x]?>
	</div>
    <?php } ?>
	

    <div style="float:left; padding-left:25px; height:38px; padding-top:10px;<?php if($show_filter==0) echo " display:none;";?>" class="main_nav">
	Filtered Results <span id="filter_results_number"></span>
	</div>

  <div style="float:right; position:relative;">
    <div id="sub_search" style="float:left;" class="sub_off" onclick="document.location.href='search.php'" onMouseOver="this.style.cursor='pointer'">
	Search
	</div>
  </div>
  <div style="clear:both;"></div>

</div>
</div>
<?php  // } ?>

<div align="center" class="main">
  <div class="whiteround" style="height:100px;<?php if($show_filter==0) echo " display:none;";?>" id="search_box_main">
    <div style="position:relative; width:100%;">
      <div style="float:left;" class="main_large">Filter</div>
      <div style="float:right;" id="numrecords" class="main_large">
	  0 Records</div>
	</div>
	<div style="clear:both;"></div>
	<hr size="1" color="#9a000c">
	
	<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name="searchform">
	<input type="hidden" name="cand_type" value="<?=$cand_type?>">
	<input type="hidden" name="submit1" value="filter">
	<div  id="search_box">
	
	
	<div style="position:relative;">
	<div style="float:left;">
<select name="filterby">
<option value="company_name"<?php if($filterby=="company_name") echo " selected"; ?>>Company</option>
<option value="city"<?php if($filterby=="city") echo " selected"; ?>>City</option>
<option value="state"<?php if($filterby=="state") echo " selected"; ?>>State</option>
</select>
    </div>
	
	<div id="searchby_main" style="width:240px; float:left;">
	<input type="text" name="searchfor" value="<?=$searchfor?>" onkeypress="return entsub(event, this.form)">
	</div>

<?php /*	
	<div style="float:left;" align="left">
	<input type="checkbox" name="show_hidden" value="1"<?php if($show_hidden==1) echo " checked";?>>Show Hidden Prospects<br>
	<input type="checkbox" name="show_all_cand" value="1"<?php if($show_all_cand==1) echo " checked";?>>Show All Candidates/Clients
	</div>
*/ ?>	
    <?php if($SESSION_MASTER_ID==1){ ?>
	<div style="float:left;" align="left">
    User:
	<select name="user_filter">
	<option value="0">All</option>
	<?php
	$sql = "SELECT user_id, concat(firstname, ' ' , lastname) as fullname from users where master_id=1 and enabled=1 order by lastname";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=$record['user_id']?>"<?php if($record['user_id']==$user_filter) echo " selected";?>><?=stripslashes($record['fullname'])?></option>
	  <?php
	}
	?>
	</select>
	</div>
	<div style="float:left;" align="left">
	Import Identifier:
	<select name="identifier_filter">
	<option value="0"></option>
	<?php /*
	<option value="Jeff"<?php if($identifier_filter=="Jeff") echo " selected";?>>Jeff</option>
	<option value="BidPad"<?php if($identifier_filter=="BidPad") echo " selected";?>>BidPad</option>
	<option value="Webinar Attended"<?php if($identifier_filter=="Webinar Attended") echo " selected";?>>Webinar Attended</option>
	*/?>
	<?php
	$sql = "SELECT identifier from prospects where identifier != '' and master_id=1 group by identifier order by identifier";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  ?>
	  <option value="<?=stripslashes($record['identifier'])?>"<?php if($identifier_filter==stripslashes($record['identifier'])) echo " selected";?>><?=stripslashes($record['identifier'])?></option>
	  <?php
	}
	?>
	</select>
	</div>
	<?php if($cand_type==1){ ?>
	  <div style="float:left;" align="left">
      Objective:
	  <select name="obj_filter">
	  <option value="0">All</option>
	  <?php
	  $sql = "SELECT met_id, met_name from activities_met_options";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    ?>
	    <option value="<?=$record['met_id']?>"<?php if($record['met_id']==$obj_filter) echo " selected";?>><?=stripslashes($record['met_name'])?></option>
	    <?php
	  }
	  ?>
	  </select>
	  </div>
	<?php } ?>
	<?php if($cand_type==2){ ?>
	  <div style="float:left;" align="left">
	  <select name="active_filter">
	  <option value="1"<?php if($active_filter==1) echo " selected";?>>Active</option>
	  <option value="0"<?php if($active_filter==0) echo " selected";?>>Inactive</option>
	  <option value="-1"<?php if($active_filter==-1) echo " selected";?>>All</option>
	  </select>
	  </div>
	<?php } ?>
	<?php } ?>
	<?php if($cand_type==3){ ?>
	  <div style="float:left;" align="left">
	  <select name="resource_filter">
	  <option value="0"<?php if($resource_filter==0) echo " selected";?>>All</option>
	  <option value="1"<?php if($resource_filter==1) echo " selected";?>>Vendor Only</option>
	  <option value="2"<?php if($resource_filter==2) echo " selected";?>>Resource Only</option>
	  </select>
	  </div>
	<?php } ?>
	</div>
	<div style="clear:both;"></div>
	
	<div style="position:relative; width:100%;" class="main">
	<div style="float:left;">
	Zip: <input type="text" name="zip" value="<?=$zip?>" size="5" onkeypress="return entsub(event, this.form)">
    <input type="text" name="distance" value="<?=$distance?>" size="3" onkeypress="return entsub(event, this.form)">miles
	</div>
	
	<div style="float:right;">
	<input type="submit" name="submitbutton" value="filter">
	<input type="button" name="buttonclear" value="clear filter" onclick="document.location.href='contacts_clearfilter.php'">
	</div>
	
	</div>
	<div style="clear:both;"></div>
	</form>
	
	
	
  </div>
</div>

<div style="height:5px;"><img src="images/spacer.gif"></div>

<div align="center">
  <div class="whiteround" style="height:100px;" id="contacts_info">
  <div style="text-align:left;">
  <strong>Record <span id="record_number"></span> of <span id="total_records"></span></strong>
  <hr size="1" color="#9a000c">
  </div>
    <div style="width:100%; height:80px; position:relative;">
	  <div style="float:left; vertical-align:middle; height:100px; width:50px;" id="leftarrow">
      </div>

      <div id="contacts1" style="display:block; overflow:hidden; height:80px; float:left; white-space: no-wrap; width:730px; position:relative;">
      </div>
      <div id="contacts2" style="display:none; overflow:hidden; height:100px; float:left; white-space: no-wrap; width:730px; position:relative;">
      </div>
	  <div style="float:right; position:relative; height:100px; width:20px;" id="expandarrow">
	  </div>
      <div style="float:right; vertical-align:middle; height:100px; width:50px;" id="rightarrow">
      </div>
	  
      
	</div>
    <div style="clear:both;"></div>
  </div>
</div>
<div style="clear:both;"></div>

<div style="height:5px;"><img src="images/spacer.gif"></div>

<div align="center">
  <div class="whiteround" style="min-height:450px; text-align:left;" id="search_results_wrapper">
    
	<form action="contacts_action.php" method="post" name="email">
	<input type="hidden" name="clicktype" value="">
	<div style="position:relative; width:100%;" id="main_buttons">
	<div style="float:left;" id="filter_button">
	<?=ImageLink("showHideSearch()", "filter-icon", 1, 1, "Show/Hide Filter Options");?>
	</div>
	<div style="float:left;" id="num_property_div">
	</div>
	
	<div style="float:right;" id="action_buttons">
	<?php if($cand_type==0 || $cand_type==3){ // only show add button for prospects and vendors ?>
	<a href="prospect_edit.php">Add</a> &nbsp;
	<?php } ?>
	
	<?php if($SESSION_MASTER_ID==1){?>
	<a href="javascript:formsubmit('bulkact', '0')" title="Add Activity"><img src="images/calendar-icon.jpg" border="0"></a>
	  <?php if($cand_type==2){ ?>
	  <a href="javascript:formsubmit('setup', '0')" title="Add to Core"><img src="images/cube-icon.jpg" border="0"></a>
	  <?php } ?>
	<a href="javascript:formsubmit('fcsrep', '0')" title="Mail FCS Rep Videos"><img src="images/fcsmail-icon_off.png" border="0"></a>
	<?php } ?>
	
	<?php
    ImageLink("formsubmit('print', 'new')", "print-icon", 0, 1, "Print");
    ?>
	<?php
	ImageLink("formsubmit('email', '0')", "mail-icon", 0, 1, "Email");
    ?>
	<?php
	if($SESSION_CAN_EXPORT){
    ImageLink("formsubmit('excel', 'new')", "excel-icon", 0, 1, "Export to Spreadsheet");
	}
    ?>
	<?php
    ImageLink("formsubmit('map', '0')", "us-icon", 0, 1, "Show on Map");
    ?>
	</div>
	<div style="float:right; display:none;" id="deletebutton">
	<?php if($SESSION_ISADMIN == 1){ ?>
	  <a href="javascript:confirmdelete('delete', '0')" title="Delete Selected"><img src="images/delete-icon.png" border="0"></a>
	<?php } ?>
	</div>
	</div>
	<div style="clear:both;"></div>
    <div style="width:100%;" id="search_results">
	</div>
	</form>
  </div>
</div>

<div class="whiteround" id="debug" style="display:none;">
</div>

<script>
searchResults('?<?=$qs2?>');
</script>

<?php include "includes/footer.php"; ?>
