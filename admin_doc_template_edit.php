<?php
include "includes/header_white.php";

$id = go_escape_string($_GET['id']);

if($id != "new"){
  $sql = "SELECT master_id from document_template where id='$id'";
  $test = getsingleresult($sql);
  if($test != $SESSION_MASTER_ID) exit;
  
  $sql = "SELECT * from document_template where id='$id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $template = stripslashes($record['template']);
  $template_name = stripslashes($record['template_name']);
  $template_name = go_reg_replace("\"", "&quot;", $template_name);
}
?>
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

<script>
function checkform(f){
  var errmsg = "";
  if(f.template_name.value==""){ errmsg += "Please enter a name for the template.\n";}
  
  if(errmsg==""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}
</script>
<div class="main">
<form action="admin_doc_template_edit_action.php" method="post" enctype="multipart/form-data" onsubmit="return checkform(this)">
<input type="hidden" name="id" value="<?=$id?>">
Template Name:<br>
<input type="text" name="template_name" size="50" maxlength="250" value="<?=$template_name?>">
<br><br>

<div>
<textarea id="template" name="template" rows="15" cols="80" style="width: 80%"><?=$template?></textarea>
</div>
		
<br><br>
<input type="submit" name="submit1" value="Submit">
</form>
</div>