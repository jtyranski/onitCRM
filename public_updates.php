<?php include "includes/header_white.php"; ?>
<?php
$sql = "UPDATE users set show_new_support=0 where user_id='" . $SESSION_USER_ID . "'";
executeupdate($sql);
?>
<script>
function Openwin(x){
  grayOut(true, {'zindex':'50', 'opacity':'25'});
  document.getElementById(x).style.display="block";
}

function Closewin(x){
  grayOut(false);
  document.getElementById(x).style.display="none";
}

function load_event(x) {
  //alert(x);
  
   
  //alert(contmsg);
    url = "public_updates_pop.php?event_id=" + x;
	url=url+"&sid="+Math.random();
	
	//document.getElementById('debug').style.display="";
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

  Openwin('contact');
}

	</script> 
<script src="includes/grayout.js"></script>
<body style="margin:10px 10px 10px 10px;">
<a href="http://www.fcscontrol.com"><img src="images/fcs_logo.jpg" border="0"></a><br>
<div class="main">

  
  <div>
  <span style="font-size:18px; font-weight:bold;">Support</span>
  <br><br>

  
  <span style="font-size:16px; font-weight:bold;">New Features Released This Week</span> (click on item for more detailed description)<br>
    <div style="width:90%; position:relative;">
	<div style="width:50%; float:left;">
	<u>Core Changes</u>
	<br>
	<?php
	$shown = array();
	$sql = "SELECT a.event_id, a.title, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty 
	from supercali_events a, supercali_categories b
	where a.category_id = b.category_id
	and b.calendar_type='Programming'
	and a.publish=1
	and a.publish_type='core'
	and a.complete_date >= date_sub(now(), interval 7 day) order by complete_date desc";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $shown[] = $record['event_id'];
	  $title = stripslashes($record['title']);
	  if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  ?>
	  <?=$record['complete_pretty']?> <a href="javascript:load_event('<?=$record['event_id']?>')" style="text-decoration:none;"><?=$title?></a><br>
	  <?php
	}
	?>
	</div>
	<div style="width:50%; float:left;">
	<u><?=$MAIN_CO_NAME?> Connect Changes</u>
	<br>
	<?php
	$sql = "SELECT a.event_id, a.title, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty 
	from supercali_events a, supercali_categories b
	where a.category_id = b.category_id
	and b.calendar_type='Programming'
	and a.publish=1
	and a.publish_type='fcs'
	and a.complete_date >= date_sub(now(), interval 7 day) order by complete_date desc";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  $shown[] = $record['event_id'];
	  $title = stripslashes($record['title']);
	  if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  ?>
	  <?=$record['complete_pretty']?> <a href="javascript:load_event('<?=$record['event_id']?>')" style="text-decoration:none;"><?=$title?></a><br>
	  <?php
	}
	?>
	</div>
	</div>
	<div style="clear:both;"></div>
  <br><br>
  
  <span style="font-size:16px; font-weight:bold;">Past Release Features</span> (click on item for more detailed description)<br>
    <div style="width:90%; position:relative;">
	<div style="width:50%; float:left;">
	<u>Core Changes</u>
	<br>
	<?php
	$sql = "SELECT a.event_id, a.title, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty 
	from supercali_events a, supercali_categories b
	where a.category_id = b.category_id
	and b.calendar_type='Programming'
	and a.publish=1
	and a.publish_type='core'
	and a.complete_date >= date_sub(now(), interval 2 month) order by complete_date desc";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  if(in_array($record['event_id'], $shown)) continue;
	  $title = stripslashes($record['title']);
	  if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  ?>
	  <?=$record['complete_pretty']?> <a href="javascript:load_event('<?=$record['event_id']?>')" style="text-decoration:none;"><?=$title?></a><br>
	  <?php
	}
	?>
	</div>
	<div style="width:50%; float:left;">
	<u><?=$MAIN_CO_NAME?> Connect Changes</u>
	<br>
	<?php
	$sql = "SELECT a.event_id, a.title, date_format(a.complete_date, \"%m/%d/%Y\") as complete_pretty 
	from supercali_events a, supercali_categories b
	where a.category_id = b.category_id
	and b.calendar_type='Programming'
	and a.publish=1
	and a.publish_type='fcs'
	and a.complete_date >= date_sub(now(), interval 2 month) order by complete_date desc";
	$result = executequery($sql);
	while($record = go_fetch_array($result)){
	  if(in_array($record['event_id'], $shown)) continue;
	  $title = stripslashes($record['title']);
	  if(strlen($title) > 35) $title = substr($title, 0, 35) . "...";
	  ?>
	  <?=$record['complete_pretty']?> <a href="javascript:load_event('<?=$record['event_id']?>')" style="text-decoration:none;"><?=$title?></a><br>
	  <?php
	}
	?>
	</div>
	</div>
	<div style="clear:both;"></div>
  <br><br>
  

  </div>



</div>

<div id="contact" style="position:absolute; left:100px; top:50px; z-index:151; display:none; border:2px solid #000000; padding:15px 15px 15px 15px; background-color:#FFFFFF; width:600px; height:350px; overflow:auto;" class="main">
  <div align="right">
  <a href="javascript:Closewin('contact')">X</a>
  </div>
  <form action="#" name="programmingform">
  <div id="pform">
  </div>
  
  </form>
</div>
</body>
