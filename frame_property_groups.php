<?php
include "includes/header_white.php";

$property_id = $_GET['property_id'];

$sql = "SELECT groups, subgroups from properties where property_id='$property_id'";
$result = executequery($sql);
$record = go_fetch_array($result);
$groups = $record['groups'];
$subgroups = $record['subgroups']
?>
<script>
function OpenClose(group, x){
  if(x==1){
    document.getElementById('group_' + group).style.display="";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '0')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">-</a>";
  }
  else {
    document.getElementById('group_' + group).style.display="none";
	document.getElementById('group_' + group + '_arrow').innerHTML = "<a href=\"javascript:OpenClose('" + group + "', '1')\" style=\"text-decoration:none; font-weight:bold; font-size:14px;\">+</a>";
  }
}

function group_check(){
  document.getElementById('submit1').disabled=false;
  var form='form1';
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  var c=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name=="group_list[]" && dml.elements[i].checked==true) {
      c = c+1;
    }
  }
  if(c > 1){
    document.getElementById('submit1').disabled=true;
	alert("Each property can only belong to one group.");
  }
}

function subgroup_check(){
  document.getElementById('submit1').disabled=false;
  var form='form1';
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  var c=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name=="subgroup_list[]" && dml.elements[i].checked==true) {
      c = c+1;
    }
  }
  if(c > 1){
    document.getElementById('submit1').disabled=true;
	alert("Each property can only belong to one subgroup.");
  }
}
</script>
<div class="main">
<form action="frame_property_groups_action.php" method="post" name="form1">
<input type="hidden" name="property_id" value="<?=$property_id?>">
<?php
$sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of=0 order by group_name";
$result = executequery($sql);
while($record = go_fetch_array($result)){
  $group_id = $record['id'];
  ?>
  <input type="checkbox" name="group_list[]" value="<?=$group_id?>"<?php if($group_id==$groups) echo " checked";?> onchange="group_check()"> 
  <span id="group_<?=$group_id?>_arrow" style="display:none;"><a href="javascript:OpenClose('<?=$group_id?>', '1')" style="text-decoration:none; font-weight:bold; font-size:14px;">+</a></span>
  <?=stripslashes($record['group_name'])?>
  <br>
  <div id="group_<?=$group_id?>" style="padding-left:20px; display:none;">
  <?php
  $sql = "SELECT id, group_name from groups where master_id='" . $SESSION_MASTER_ID . "' and sub_of='$group_id' order by group_name";
  $result_sub = executequery($sql);
  if(mysql_num_rows($result_sub)) $SHOWARROW[] = $group_id;
  while($record_sub = go_fetch_array($result_sub)){
    $subgroup_id = $record_sub['id'];
	?>
	<input type="checkbox" name="subgroup_list[]" value="<?=$subgroup_id?>"<?php if($subgroup_id==$subgroups) echo " checked";?> onchange="subgroup_check()"> <?=stripslashes($record_sub['group_name'])?><br>
	<?php
	if($subgroup_id==$subgroups) $OPEN[] = $group_id;
  }
  ?>
  </div>
  <?php
}
?>
<input type="submit" name="submit1" value="Update Groups" id="submit1">
</form>
</div>
<script>
<?php
$OPEN2 = array_unique($OPEN);
$OPEN2 = array_values($OPEN2);

for($x=0;$x<sizeof($SHOWARROW);$x++){
  if($SHOWARROW[$x]=="") continue;
  ?>
  document.getElementById('group_<?=$SHOWARROW[$x]?>_arrow').style.display="";
  <?php
}

for($x=0;$x<sizeof($OPEN2);$x++){
  if($OPEN2[$x]=="") continue;
  ?>
  OpenClose('<?=$OPEN2[$x]?>', '1');
  <?php
}
?>
</script>
