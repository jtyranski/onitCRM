<?php include "includes/header.php"; ?>

<div align="center">
  <div class="whiteround" style="height:800px;">
  <div align="left">
  
<?php

$sql = "SELECT tools_available, tools_active from users where user_id='" . $SESSION_USER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$tools_available = $record['tools_available'];
$tools_active = $record['tools_active'];
?>
<div style="position:relative; width:100%;">
<div style="float:left; font-weight:bold;" class="main_large">Tool Shop Edit Mode - Drag a tool up to the main navigation bar, or drag one from the navigation bar back to the Tool Shop</div>
<div style="float:right;">
<a href="toolbox.php"><img src="images/x-icon.png" border="0"></a>
<?php
  //ImageLink("ipad_toolbox_editmode.php", "gear-icon", 0, 0, "ipad/");
  ?>
</div>
</div>
<div style="clear:both;"></div>

<?php
$sql = "SELECT * from toolbox_cat order by cat_id";
$result = executequery($sql);
while($cat = go_fetch_array($result)){
  $cat_id = $cat['cat_id'];
  $cat_name = stripslashes($cat['name']);
  $show_name = 1;
  $sql = "SELECT * from toolbox_items where cat_id='$cat_id' and master_id='" . $SESSION_MASTER_ID . "' order by id";
 // echo "<!-- $sql -->\n";
  $res2 = executequery($sql);
  while($item = go_fetch_array($res2)){
    $id = $item['id'];
	if(!go_reg("," . $id . ",", $tools_available)) continue;
	$canmove = 1;
	if(go_reg("," . $id . ",", $tools_active)) $canmove=0;
	if($show_name == 1){
	  ?>
	  <br>
	  <div class="main_large" style="background-color:#CCCCCC; width:100%;"><?=$cat_name?></div>
	  <div style="position:relative; width:100%;" class="main">
	  <?php
	  $show_name = 0;
	}
	/*
	if(go_reg("," . $id . ",", $tools_active)){
	  $image = $item['on_icon'];
	}
	else {
	  $image = $item['off_icon'];
	}
	*/
	if($canmove){
	  $image = $item['on_icon'];
	  $class = "class=\"drag\"";
	}
	else {
	  $image = $item['off_icon'];
	  $class="";
	}
	?>
	<div style="float:left; width:100px; position:relative; z-index:100; background:url(images/toolbox/<?=$image?>); background-repeat:no-repeat;" <?=$class?> id="<?=$id?>">
	<br><br><br><br>
	<?=stripslashes($item['name'])?>
	</div>
	<?php
  }
  if($show_name==0) echo "</div><div style='clear:both;'></div>";
}

?>

	
<style>
.drag {cursor: move;}
</style>
	<script language="JavaScript" type="text/javascript"> 
<!--
 
// this is simply a shortcut for the eyes and fingers
function $(id)
{
	return document.getElementById(id);
}
 
var _startX = 0;			// mouse starting positions
var _startY = 0;
var _offsetX = 0;			// current element offset
var _offsetY = 0;
var _dragElement;			// needs to be passed from OnMouseDown to OnMouseMove
var _oldZIndex = 0;			// we temporarily increase the z-index during drag
var _debug = $('debug');	// makes life easier
 
 
InitDragDrop();
 
function InitDragDrop()
{
	document.onmousedown = OnMouseDown;
	document.onmouseup = OnMouseUp;
}
 
function OnMouseDown(e)
{
	// IE is retarded and doesn't pass the event object
	if (e == null) 
		e = window.event; 
	
	// IE uses srcElement, others use target
	var target = e.target != null ? e.target : e.srcElement;
	
	/*
	_debug.innerHTML = target.className == 'drag' 
		? 'draggable element clicked' 
		: 'NON-draggable element clicked';
 */
	// for IE, left click == 1
	// for Firefox, left click == 0
	if ((e.button == 1 && window.event != null || 
		e.button == 0) && 
		target.className == 'drag')
	{
		// grab the mouse position
		_startX = e.clientX;
		_startY = e.clientY;
		
		// grab the clicked element's position
		_offsetX = ExtractNumber(target.style.left);
		_offsetY = ExtractNumber(target.style.top);
		
		// bring the clicked element to the front while it is being dragged
		_oldZIndex = target.style.zIndex;
		target.style.zIndex = 10000;
		
		// we need to access the element in OnMouseMove
		_dragElement = target;
 
		// tell our code to start moving the element with the mouse
		document.onmousemove = OnMouseMove;
		
		// cancel out any text selections
		document.body.focus();
		
		// prevent text selection in IE
		document.onselectstart = function () { return false; };
		// prevent IE from trying to drag an image
		target.ondragstart = function() { return false; };
		
		// prevent text selection (except IE)
		return false;
	}
}
 
function ExtractNumber(value)
{
	var n = parseInt(value);
	
	return n == null || isNaN(n) ? 0 : n;
}
 
function OnMouseMove(e)
{
	if (e == null) 
		var e = window.event; 
 
	// this is the actual "drag code"
	_dragElement.style.left = (_offsetX + e.clientX - _startX) + 'px';
	_dragElement.style.top = (_offsetY + e.clientY - _startY) + 'px';
	
	//_debug.innerHTML = '(' + _dragElement.style.left + ', ' + _dragElement.style.top + ')';	
}
 
function OnMouseUp(e)
{
	if (_dragElement != null)
	{
		_dragElement.style.zIndex = _oldZIndex;
 
		// we're done with these events until the next OnMouseDown
		document.onmousemove = null;
		document.onselectstart = null;
		_dragElement.ondragstart = null;
		
		x = _dragElement.style.left;
		y = _dragElement.style.top;
		id = _dragElement.id;
 
		// this is how we know we're not dragging
		_dragElement = null;
		
        //alert("x=" + x + "\ny=" + y + "\nid=" + id);
		document.location.href= "toolbox_edit_action.php?tool_id=" + id + "&x=" + x + "&y=" + y;
		
		
		//_debug.innerHTML = 'mouse up';
	}
}
//-->
	</script> 
	
  
</div>
</div>
</div>

