<?php
include "includes/header_white.php";
?>
<script src="includes/jquery-1.6.4.js"></script>
<script src="includes/jquery.maskedinput-1.3.js"></script>
<script src="includes/jquery.livequery.js"></script>
<script>
function go_pop(action, group_id, subgroup_id, counter){
  url = "admin_groups_pop.php?action=" + action + "&group_id=" + group_id + "&subgroup_id=" + subgroup_id;
	url=url+"&sid="+Math.random();
	//alert(url);
	//document.getElementById('debug').style.display="";
    //document.getElementById('debug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
  if(counter != 0){
    if(action=="groupinfo"){
      var numItems = $('.grouplist_class').length;
	  for(x=1;x<=numItems;x++){
	    document.getElementById('group_' + x).style.backgroundColor = "#FFFFFF";
	  }
	  document.getElementById('group_' + counter).style.backgroundColor = "#B4D2F8";
	}
	
	if(action=="subgroupinfo"){
      var numItems = $('.subgrouplist_class').length;
	  for(x=1;x<=numItems;x++){
	    document.getElementById('subgroup_' + x).style.backgroundColor = "#FFFFFF";
	  }
	  document.getElementById('subgroup_' + counter).style.backgroundColor = "#B4D2F8";
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

function go_form(x){
  document.form1.submit1.value = x;
  document.form1.submit();
}


function SetChecked(x,chkName) {
  var form='form1'; //Give the form name here
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}

function delgroup(warning, x){
  if(warning != ""){
    cf = confirm("This group has " + warning + " still associated with it. Deleting this group will remove the group's association with these items. Do you wish to continue?");
  }
  else {
    cf = true;
  }
  if(cf){
    go_form(x);
  }
}

function force_custom(x){
  if(x.value=="ComputerEase"){
    document.getElementById("custom_sd_field").value = "PO #";
	document.getElementById("custom_sd_field2").value = "Job #";
  }
  
  document.getElementById('ar_account_area').style.display="none";
  document.getElementById('sales_account_area').style.display="none";
  document.getElementById('timberline_area').style.display="none";
  
  switch(x.value){
    case "ComputerEase2":{
      document.getElementById('ar_account_area').style.display="";
	  document.getElementById('sales_account_area').style.display="";
	  break;
    }
	case "Timberline":{
	  document.getElementById('timberline_area').style.display="";
	  break;
	}
  }

}

</script>
<script src="includes/grayout.js"></script>
<script type="text/javascript" src="includes/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<form action="admin_groups_action.php" method="post" name="form1" enctype="multipart/form-data">
<input type="hidden" name="submit1">
<input type="hidden" name="group_id" id="group_id">
<input type="hidden" name="subgroup_id" id="subgroup_id">
<div id="debug" style="display:none;"></div>

<div style="width:805px; position:relative;" class="main">
<div style="width:400px; float:left;">
  <div style="width:100%; position:relative; height:25px;">
  <div style="float:left;"><strong>Groups</strong></div>
  <div style="float:right;"><a href="javascript:Openwin('addgroupwindow')">Add a Group</a></div>
  </div>
  <div style="clear:both;"></div>
<div id="grouplist" style="height:200px; border:2px solid black; overflow:auto;"></div>
</div>

<div style="width:5px; float:left;">&nbsp;</div>

<div style="width:400px; float:left;">
<div id="groupinfo"></div>
<div id="group_memberlist"></div>
</div>

</div>
<div style="clear:both;"></div>

<div style="height:20px;">&nbsp;</div>


<div style="width:805px; position:relative; display:none;" class="main" id="subgroup_main">
<div style="width:400px; float:left;">
  <div style="width:100%; position:relative; height:25px;">
  <div style="float:left;"><strong>Sub Groups</strong></div>
  <div style="float:right;"><a href="javascript:Openwin('addsubgroupwindow')">Add a Sub Group</a></div>
  </div>
  <div style="clear:both;"></div>
<div id="subgrouplist" style="height:200px; border:2px solid black; overflow:auto;"></div>
</div>

<div style="width:5px; float:left;">&nbsp;</div>

<div style="width:400px; float:left;">
<div id="subgroupinfo"></div>
<div id="subgroup_memberlist"></div>
</div>

</div>
<div style="clear:both;"></div>

<div style="height:20px;">&nbsp;</div>

<div style="width:1000px;" id="group_company_info" class="main"></div>


<div id="addgroupwindow" style="position:absolute; left:100px; top:20px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:400px; height:150px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('addgroupwindow')">X</a>
  </div>
  Enter a name for this new group:<br>
  <input type="text" name="add_group_name" maxlength="255"><br>
  <input type="button" name="buttonaddgroup" value="Add Group" onclick="go_form('add_group')">
</div>

<div id="addsubgroupwindow" style="position:absolute; left:100px; top:20px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:400px; height:150px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('addsubgroupwindow')">X</a>
  </div>
  Enter a name for this new sub group:<br>
  <input type="text" name="add_subgroup_name" maxlength="255"><br>
  <input type="button" name="buttonaddsubgroup" value="Add Sub Group" onclick="go_form('add_subgroup')">
</div>


</form>

<script>go_pop('grouplist', '0', '0', '0');</script>