<?php
include "includes/header_white.php";

$property_id = $_GET['property_id'];
$section_type = $_GET['section_type'];
if($section_type=="") $section_type = "roof";

$section_id = $_GET['section_id'];
$template_id = go_escape_string($_GET['template_id']);
if($template_id != ""){
  $sql = "SELECT template from document_template where id='$template_id' and master_id='" . $SESSION_MASTER_ID . "'";
  $template = stripslashes(getsingleresult($sql));
}

?>
<script>
function load_template(x){
  y = x.value;
  section_id = document.form1.section_id.value;
  document.location.href="<?=$_SERVER['SCRIPT_NAME']?>?property_id=<?=$property_id?>&section_type=<?=$section_type?>&section_id=" + section_id + "&template_id=" + y;
/*
  y = x.value;
  url = "frame_property_document_new_template.php?id=" + y;
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
		//document.getElementById('fdebug').style.display="";
		//document.getElementById('fdebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		*/
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
  if(x=="send" || x=="save" || x=="sendblank" || x=="saveblank"){
    if(document.form1.include_report.checked==true){
	  Openwin('pleasewait');
	}
  }
  document.form1.submit1.value = x;
  document.form1.submit();
}
</script>
<script src="includes/grayout.js"></script>
<!-- TinyMCE -->
<script type="text/javascript" src="tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave,visualblocks",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->

<div id="fdebug" style="display:none;"></div>
<form action="frame_property_document_new_action.php" method="post" name="form1">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<input type="hidden" name="section_type" value="<?=$section_type?>">
<input type="hidden" name="submit1" value="">
<div style="position:relative;" class="main">
<div style="float:left;">
Type
<select name="type">
<option value="Proposal"<?php if($type=="Proposal") echo " selected";?>>Proposal</option>
</select>
&nbsp; &nbsp;
Section
<select name="section_id">
<option value="0">[None]</option>
<?php
$sql = "SELECT * from sections where property_id='$property_id' and multiple='' and section_type='$section_type' order by section_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['section_id']?>"<?php if($section_id == $record['section_id']) echo " selected"; ?>><?=stripslashes($record['section_name'])?></option>
  <?php
}
?>
</select>
<div style="height:8px;"><img src="images/spacer.gif"></div>

<div style="position:relative; width:800px;" class="main">
<div style="float:left; padding-right:8px;">
Template
<select name="template_id" onchange="load_template(this)">
<option value="0">None</option>
<?php
$sql = "SELECT id, template_name from document_template where master_id='" . $SESSION_MASTER_ID . "' order by template_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['id']?>"<?php if($template_id==$record['id']) echo " selected";?>><?=stripslashes($record['template_name'])?></option>
  <?php
}
?>
</select>
</div>
<div style="float:left;">
<input type="checkbox" name="header" value="1" checked="checked">Include Company Header
</div>
<div style="float:right;">
<a href="javascript:go_form('send')">Send</a> &nbsp; &nbsp; &nbsp;
<a href="javascript:go_form('sendblank')">Send (blank template)</a> &nbsp; &nbsp; &nbsp;
<a href="javascript:go_form('save')">Save</a> &nbsp; &nbsp; &nbsp;
<a href="javascript:go_form('saveblank')">Save (blank template)</a> &nbsp; &nbsp; &nbsp;
<a href="javascript:Openwin('saveas')">Save as Template</a>
</div>
</div>
<div style="clear:both;"></div>



<div>
<textarea id="content" name="content" rows="15" cols="120"><?=$template?></textarea>
</div>

</div>
<div style="float:left; padding-left:15px;">
<strong>Include in Proposal</strong>
<br>
<input type="checkbox" name="include_report" value="1">Inspection Report<br>
<?php
$sql = "SELECT attach_id, description from attachment_library where master_id='" . $SESSION_MASTER_ID . "' order by description";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <input type="checkbox" name="include[]" value="<?=$record['attach_id']?>"><?=stripslashes($record['description'])?><br>
  <?php
}
?>
</div>
</div>
<div style="clear:both;"></div>

<div id="saveas" style="position:absolute; left:100px; top:20px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:400px; height:150px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('saveas')">X</a>
  </div>
  Enter a name for this template:<br>
  <input type="text" name="template_name"><br>
  <input type="button" name="buttonsaveas" value="Save Template" onclick="go_form('save_template')">
</div>

<div id="pleasewait" style="position:absolute; left:100px; top:20px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:400px; height:150px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('pleasewait')">X</a>
  </div>
  Generating pdf document <img src="images/loading.gif">
</div>


</form>