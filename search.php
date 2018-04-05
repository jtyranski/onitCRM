<?php include "includes/header.php"; ?>
<?php

?>
<script>
function load_cand_type(x){
  document.location.href="contacts.php?cand_type=" + x;
}


function search_function(){
  x = document.getElementById('searchfor').value;
  y = document.getElementById('searchby').value;
  url="search_filter.php?searchfor=" + x + "&searchby=" + y;
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
	


  <div style="float:right; position:relative;">
    <div id="sub_search" style="float:left;" class="sub_on" onclick="document.location.href='search.php'" onMouseOver="this.style.cursor='pointer'">
	Search
	</div>
  </div>
  <div style="clear:both;"></div>

</div>
</div>



<div align="center">
  <div class="whiteround" style="height:800px; text-align:left;" id="search_results_wrapper">
    
    <select name="searchby" id="searchby" onchange="search_function()">
	<option value="Company">Company</option>
	<option value="Company Address">Company Address</option>
	<option value="Property">Property</option>
	<option value="Property Address">Property Address</option>
	<option value="Contact">Contact</option>
	<option value="Contact Email">Contact Email</option>
	<option value="Contact Phone">Contact Phone</option>
	<?php if($SESSION_MASTER_ID==1){ ?>
	<option value="Identifier">Import Identifier</option>
	<?php } ?>
	</select>
	: <input type="text" name="searchfor" id="searchfor" size="30" onkeyup="search_function()">
	<input type="button" name="buttonsearch" value="Search" onclick="search_function()">
    <div style="width:100%;" id="search_results">
	</div>
  </div>
</div>

<div class="whiteround" id="debug" style="display:none;">
</div>


 
<?php include "includes/footer.php"; ?>