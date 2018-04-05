<?php
include "includes/functions.php";

$leak_id = $_GET['leak_id'];
$problem_id = $_GET['problem_id'];
$photo_id = $_GET['photo_id'];
$type = $_GET['type'];

if($type=="problem") $type_id = 1;
if($type=="correction") $type_id=2;

$sql = "SELECT photo from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and photo_id='$photo_id'";
$photo = getsingleresult($sql);
$sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type='$type_id' and photo_id < '$photo_id' order by photo_id desc limit 1";
$prev_id = getsingleresult($sql);
$sql = "SELECT photo_id from am_leakcheck_photos where leak_id='$leak_id' and problem_id='$problem_id' and type='$type_id' and photo_id > '$photo_id' order by photo_id asc limit 1";
$next_id = getsingleresult($sql);
ob_start();
?>
      <?php if($prev_id != ""){ ?>
	    <a href="javascript:NextPhoto('<?=$problem_id?>', '<?=$prev_id?>', '<?=$type?>')">Prev</a>
	  <?php } else { ?>
	    Prev
	  <?php } ?> 
	  <?php if($next_id != ""){ ?>
	    <a href="javascript:NextPhoto('<?=$problem_id?>', '<?=$next_id?>', '<?=$type?>')">Next</a>
	  <?php } else { ?>
	    Next
	  <?php } ?>
	  <br>
	  <img src="<?=$UP_FCSVIEW?>uploaded_files/leakcheck/<?=$photo?>">
	  <br>
	  <a href="javascript:RotatePhoto('<?=$problem_id?>', '<?=$photo_id?>', '<?=$type?>', '90')"><img src="images/rotate270.png" border="0"></a>
	  <a href="javascript:RotatePhoto('<?=$problem_id?>', '<?=$photo_id?>', '<?=$type?>', '270')"><img src="images/rotate90.png" border="0"></a>
	  <br>
	  <?php if($resource_invoice_locked==0){ ?>
	  <span id="delete_problem_<?=$problem_id?>"><a href="javascript:deletedocuments('<?=$photo_id?>')">delete</a></span>
	  <?php } ?>
<?php
$html = ob_get_contents();
ob_end_clean();
$html = jsclean($html);
?>
div = document.getElementById('<?=$type?>_<?=$problem_id?>_photo');
div.innerHTML = '<?php echo $html; ?>';