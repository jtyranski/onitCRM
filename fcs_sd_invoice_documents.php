<?php
include "includes/functions.php";

$leak_id = go_escape_string($_GET['leak_id']);

$action = $_GET['action'];
$photo_id = go_escape_string($_GET['photo_id']);

if($action=="delete"){
  $sql = "SELECT photo from am_leakcheck_photos where photo_id='$photo_id'";
  $photo = getsingleresult($sql);
  if($photo != "") @unlink($UP_FCSVIEW . "uploaded_files/leakcheck/$photo");
  $sql = "DELETE from am_leakcheck_photos where photo_id='$photo_id'";
  executeupdate($sql);
  $action="refresh";
}

if($action=="refresh"){
  ob_start();
  
  $def_counter = 0;
  $sql = "SELECT * from am_leakcheck_problems where leak_id='$leak_id'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $problem_id = $record['problem_id'];
	$x_problem_desc = stripslashes($record['problem_desc']);
	$x_correction = stripslashes($record['correction']);
	$x_problem_name = stripslashes($record['problem_name']);
	$x_coordinates = $record['coordinates'];
	if($x_coordinates != "") $def_counter++;
	$foo = $def_counter;
	if($foo > 29) $foo="X";
	?>
	<div style="position:relative;">
	<div style="float:left;">
	<div id="problem_<?=$problem_id?>_photo">
    <?php
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=1 order by photo_id limit 1";
    $result_photo = executequery($sql);
    $record_photo = go_fetch_array($result_photo);
	$sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=1 and photo_id > '" . $record_photo['photo_id'] . "' order by photo_id limit 1";
	$next_id = getsingleresult($sql);
	  ?>
	  Prev 
	  <?php if($next_id != ""){ ?>
	    <a href="javascript:NextPhoto('<?=$problem_id?>', '<?=$next_id?>', 'problem')">Next</a>
	  <?php } else { ?>
	    Next
	  <?php } ?>
	  <br>
	  
	  <?php if($record_photo['photo'] != ""){ ?>
	  <img src="<?=$UP_FCSVIEW?>uploaded_files/leakcheck/<?=$record_photo['photo']?>">
	  <br>
	  <a href="javascript:RotatePhoto('<?=$problem_id?>', '<?=$record_photo['photo_id']?>', 'problem', '90')"><img src="images/rotate270.png" border="0"></a>
	  <a href="javascript:RotatePhoto('<?=$problem_id?>', '<?=$record_photo['photo_id']?>', 'problem', '270')"><img src="images/rotate90.png" border="0"></a>
	  <br>
	  <span id="delete_problem_<?=$problem_id?>"><a href="javascript:deletedocuments('<?=$record_photo['photo_id']?>')">delete</a></span>
      <?php } ?>

	</div>
	<a href="javascript:createUploader('<?=$problem_id?>', '1')">Add New Problem Image</a>
	<div id="problem_<?=$problem_id?>">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
    </div>
	</div>
	<div style="float:left;">
	<?php if($x_coordinates != "") { ?>
	<img src="images/ppgreen<?=$foo?>.png">
	<?php } ?>
	<input type="hidden" name="problem_ids[]" value="<?=$problem_id?>">
	<input type="hidden" name="problem_names[]" value="<?=$x_problem_name?>" id="name_<?=$problem_id?>">
	Problem Description:
	<a href="javascript:DelSDProblem('<?=$problem_id?>')">Delete this problem from supporting documents</a>
	<br>
	<div id="def_category_select_<?=$problem_id?>">
<select name="def_category" onChange="ajax_def_category(this, '<?=$problem_id?>')">
<option value="0">General</option>
<?php
$sql = "SELECT category_id from def_list_master where def_name=\"" . go_escape_string($x_problem_name) . "\" and master_id = '" . $SESSION_MASTER_ID . "'";
$category_id = getsingleresult($sql);
if($category_id=="") $category_id = 0;

$sql = "SELECT category_id, category_name from def_list_categories where master_id='" . $SESSION_MASTER_ID . "' order by category_name";
$result_cat = executequery($sql);
while($record_cat = go_fetch_array($result_cat)){
  ?>
  <option value="<?=$record_cat['category_id']?>"<?php if($record_cat['category_id']==$category_id) echo " selected";?>><?=stripslashes($record_cat['category_name'])?></option>
  <?php
}
?>
</select>
</div>
<div id="def_list_select_<?=$problem_id?>">
<select name="def_list" onChange="ajax_def(this, '<?=$problem_id?>')">
<option value=""></option>
<?php
$counter=0;
$sql = "SELECT id, def_name from def_list_master where visible=1 and master_id='" . $SESSION_MASTER_ID . "' and category_id='$category_id'";
$result_list = executequery($sql);
while($record_list = go_fetch_array($result_list)){
  $counter++;
  ?>
  <option value="<?=$record_list['id']?>"<?php if($record_list['def_name']==$x_problem_name) echo " selected";?>><?=$counter?> <?=stripslashes($record_list['def_name'])?></option>
  <?php
}
?>
</select>
</div>
	<textarea name="problem_descs[]" rows="3" cols="90" id="def_<?=$problem_id?>"><?=$x_problem_desc?></textarea>
    </div>
	</div>
	<div style="clear:both;"></div>
	
	<br><br>
	
	<div style="position:relative;">
	<div style="float:left;">
	<div id="correction_<?=$problem_id?>_photo">
	<?php
    $sql = "SELECT * from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=2 order by photo_id limit 1";
    $result_photo = executequery($sql);
    $record_photo = go_fetch_array($result_photo);
	$sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type=2 and photo_id > '" . $record_photo['photo_id'] . "' order by photo_id limit 1";
	$next_id = getsingleresult($sql);
	  ?>
	  Prev
	  <?php if($next_id != ""){ ?>
	    <a href="javascript:NextPhoto('<?=$problem_id?>', '<?=$next_id?>', 'correction')">Next</a>
	  <?php } else { ?>
	    Next
	  <?php } ?>
	  <br>
	  <?php if($record_photo['photo'] != ""){ ?>
	  <img src="<?=$UP_FCSVIEW?>uploaded_files/leakcheck/<?=$record_photo['photo']?>">
	  <br>
	  <a href="javascript:RotatePhoto('<?=$problem_id?>', '<?=$record_photo['photo_id']?>', 'correction', '90')"><img src="images/rotate270.png" border="0"></a>
	  <a href="javascript:RotatePhoto('<?=$problem_id?>', '<?=$record_photo['photo_id']?>', 'correction', '270')"><img src="images/rotate90.png" border="0"></a>
	  <br>
	  <span id="delete_correction_<?=$problem_id?>"><a href="javascript:deletedocuments('<?=$record_photo['photo_id']?>')">delete</a></span>
      <?php } ?>

	</div>
	<a href="javascript:createUploader('<?=$problem_id?>', '2')">Add New Correction Image</a>
	<div id="correction_<?=$problem_id?>">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
    </div>
	</div>
	<div style="float:left;">
    Correction:<br>
	<textarea name="corrections[]" rows="3" cols="90" id="action_<?=$problem_id?>"><?=$x_correction?></textarea>
    </div>
	</div>
	<div style="clear:both;"></div>
	
	<br>
	


	<input type="submit" name="submit1" value="Update Problem/Correction Descriptions">
	<br>
	<hr size="1">
	
	<?php
  }

$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
div = document.getElementById('supporting_documents_display');
div.innerHTML = '<?php echo $html; ?>';
<?php } // end refresh?>
