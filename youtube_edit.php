<?php include "includes/functions.php"; ?>
<link href="styles/css_white.css" type="text/css" rel="stylesheet">

<?php
$video_id = $_GET['video_id'];
if($video_id=="") $video_id = "new";
if($video_id != "new"){
  $sql = "SELECT * from videos_embed where video_id='$video_id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $close_link = stripslashes($record['close_link']);
  $embed_code = stripslashes($record['embed_code']);
  $property_id = stripslashes($record['property_id']);
  $prospect_id = stripslashes($record['prospect_id']);
  $video_name = stripslashes($record['video_name']);
  $page = stripslashes($record['page']);
  $video_name = go_reg_replace("\"", "&quot;", $video_name);
  $sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
  $company_name = stripslashes(getsingleresult($sql));
}
else {
  $prospect_id = $_GET['prospect_id'];
  $sql = "SELECT company_name from prospects where prospect_id='$prospect_id'";
  $company_name = stripslashes(getsingleresult($sql));
  $property_id = $_GET['property_id'];
}
?>
<script>
function loadproperties(x){
  y = x.value;


  url="youtube_properties.php?prospect_id=" + y + "&property_id=<?=$property_id?>&video_id=<?=$video_id?>";
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

function loadcompanies(x){
  y = x.value;


  url="youtube_companies.php?master_id=" + y + "&video_id=<?=$video_id?>";
    url=url+"&sid="+Math.random();
	//document.getElementById('ydebug').style.display="";
	//document.getElementById('ydebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}
</script>
<div id="ydebug"></div>
<div style="position:relative;">
<div style="float:left; padding-right:10px;">
<img src="images/youtube.png">
</div>
<div style="float:left;" class="main_superlarge">
Video Page Creator
</div>
</div>
<div style="clear:both;"></div>

<div class="main">

<form action="youtube_edit_action.php" method="post" name="form1">
<input type="hidden" name="video_id" value="<?=$video_id?>">
<table class="main" cellpadding="4">
<tr>
<td align="right"><strong>FCS Client</strong></td>
<td>
<select name="master_id" onchange="loadcompanies(this)">
<?php
$sql = "SELECT master_id, master_name from master_list where active=1 order by master_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  ?>
  <option value="<?=$record['master_id']?>"<?php if($master_id==$record['master_id']) echo " selected";?>><?=stripslashes($record['master_name'])?></option>
  <?php
}
?>
</select>
</td>
</tr>

<tr>
<td align="right"><strong>Company</strong></td>
<td id="prospectarea">
</td>
</tr>
<?php /*
<tr>
<td align="right"><strong>Property</strong></td>
<td id="propertyarea"></td>
</tr>
*/?>
<tr>
<td align="right"><strong>From</strong></td>
<td>
<select name="page">
<option value="EnciteGroup"<?php if($page=="EnciteGroup") echo " selected";?>>EnciteGroup</option>
</select>
</td>
</tr>
<tr>
<td align="right"><strong>Video Name</strong></td>
<td><input type="text" name="video_name" size="50" value="<?=$video_name?>"></td>
</tr>
<tr>
<td align="right" valign="top"><strong>Embed Code</strong></td>
<td>
<textarea name="embed_code" rows="7" cols="50"><?=$embed_code?></textarea>
</td>
</tr>
<tr>
<td align="right"><strong>Close Link</strong></td>
<td><input type="text" name="close_link" size="50" value="<?=$close_link?>"></td>
</tr>
</table>
<input type="submit" name="submit1" value="Create Video Link">
</form>
</div>
<script>
loadcompanies(document.form1.master_id);
//loadproperties(document.form1.prospect_id);
</script>
  
