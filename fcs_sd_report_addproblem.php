<?php include "includes/header_white.php"; ?>
<?php
$leak_id = $_GET['leak_id'];
?>
<script>
function checkform(f){
  var errmsg = "";
  
  if(f.problem_desc.value == ""){ errmsg += "Please enter the problem description.\n";}
  if(f.correction.value == ""){ errmsg += "Please enter the correction details.\n";}
  
  if(errmsg==""){
    return true;
  }
  else {
    alert(errmsg);
	return false;
  }
}

function ajax_def (x, def_id) {
    y = x.value;
    url = "def_populate.php?id=" + y + "&def_id=" + def_id;
	url=url+"&sid="+Math.random();

	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
       if(y != ""){ document.body.appendChild (jsel);}

}

function ajax_def_category (x, def_id) {
    y = x.value;
    url = "def_populate_category.php?category_id=" + y + "&def_id=" + def_id;
	url=url+"&sid="+Math.random();

	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
       if(y != ""){ document.body.appendChild (jsel);}

}

</script>
<div class="main">
<form action="fcs_sd_report_addproblem_action.php" method="post" onsubmit="return checkform(this)">
<input type="hidden" name="leak_id" value="<?=$leak_id?>">

<div id="def_category_select_0">
<select name="def_category" onChange="ajax_def_category(this, '0')">
<option value="0">General</option>
<?php
$sql = "SELECT category_id from def_list_master where def_name=\"" . go_escape_string($record['name']) . "\" and master_id = '" . $SESSION_MASTER_ID . "'";
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
<div id="def_list_select_0">
<select name="def_list" onChange="ajax_def(this, '0')">
<option value=""></option>
<?php
$counter=0;
$sql = "SELECT id, def_name from def_list_master where visible=1 and master_id='" . $SESSION_MASTER_ID . "' and category_id='$category_id'";
$result_list = executequery($sql);
while($record_list = go_fetch_array($result_list)){
  $counter++;
  ?>
  <option value="<?=$record_list['id']?>"<?php if($record_list['def_name']==$record['name']) echo " selected";?>><?=$counter?> <?=stripslashes($record_list['def_name'])?></option>
  <?php
}
?>
</select>
</div>

Please enter the problem description:<br>
<input type="hidden" name="problem_name" id="name_0">
<textarea name="problem_desc" rows="6" cols="70" id="def_0"></textarea>
<br><br>
Please enter the correction details:<br>
<textarea name="correction" rows="6" cols="70" id="action_0"></textarea>
<br><br>
<input type="submit" name="submit1" value="Continue to Photo Upload">
</form>
</div>