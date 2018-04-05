<?php include "includes/header.php"; 
meta_redirect("contacts.php"); // no need for anything on this index page, yet.... 4/8/11 JW

$sql = "SELECT report, admin, speedbid from users where user_id='" . $SESSION_USER_ID . "'";
$result = executequery($sql);
$record = go_fetch_array($result);
$report = $record['report'];
$admin = $record['admin'];
$speedbid = $record['speedbid'];
?>
<script>
function ShowHide(id, x){
  if(x==0){
    document.getElementById(id + "_arrow").innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '1')\"><img src='images/arrow-button-up_off.png' border='0'></a>";
	document.getElementById(id + "_list").style.display = "none";
  }
  else {
    document.getElementById(id + "_arrow").innerHTML = "<a href=\"javascript:ShowHide('" + id + "', '0')\"><img src='images/arrow-button-down_off.png' border='0'></a>";
	document.getElementById(id + "_list").style.display = "";
  }
}

function newMessage(user_id){
  <?php
  $sql = "SELECT user_id from users where enabled=1 and user_id != '" . $SESSION_USER_ID . "'";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    ?>
	document.getElementById("user_<?=$record['user_id']?>").setAttribute("class", "chat_user_off");
	<?php
  }
  ?>
  document.getElementById("user_multiple").setAttribute("class", "chat_user_off");
  
  document.getElementById("user_" + user_id).setAttribute("class", "chat_user_on");
  
  document.getElementById("company_list_area").style.display="none";
  document.getElementById("admin_message_area").style.display="none";
  
  url="index_message.php?to_user_id=" + user_id + "&action=form";
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

function DelComment(x, user_id){
  cf = confirm("Are you sure you want to delete this comment?");
  if(cf){
    url="index_message.php?to_user_id=" + user_id + "&action=delmessage&comment_id=" + x;
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
}

function DelThread(user_id){
  cf = confirm("Are you sure you want to delete this entire thread?");
  if(cf){
    url="index_message.php?to_user_id=" + user_id + "&action=delthread";
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
}



function SetChecked(x,chkName) {
  var form='newmessage'; //Give the form name here
  dml=document.forms[form];
  len = dml.elements.length;
  var i=0;
  for( i=0 ; i<len ; i++) {
    if (dml.elements[i].name==chkName && dml.elements[i].disabled==false) {
      dml.elements[i].checked=x.checked;
    }
  }
}
</script>
<div style="height:5px;"><img src="images/spacer.gif"></div>
<div id="company_list_area">
<?php if($report) include "includes/user_company.php"; ?>
</div>
<div style="height:5px;"><img src="images/spacer.gif"></div>

<div align="center">
  <div class="whiteround2" style="height:430px; text-align:left;">
    <div style="position:relative; height:30px; width:100%; padding-top:10px;">
	<div style="float:left; width:200px; font-weight:bold;">Users</div>
	<div style="float:left; font-weight:bold;">Messages</div>
	</div>
	<div style="clear:both"></div>
	
	<div style="position:relative; width:100%; height:380px;">
	<div style="float:left; height:100%; width:200px; overflow:auto;">
	
	  <?php //*********  NEW MSGS DIV ********************** ?>
	  <?php
	  $sql = "SELECT from_user_id from comments where to_user_id='" . $SESSION_USER_ID . "' and owner='" . $SESSION_USER_ID . "' 
	  and new=1 group by from_user_id";
	  $result = executequery($sql);
	  //echo "<!-- yyy $sql -->\n";
	  while($record = go_fetch_array($result)){
	    $new_messages[] = $record['from_user_id'];
	  }
	  $show_new = 0;
	  if(is_array($new_messages)){
	    $show_new = 1;
	    $not_in_clause = " user_id not in(" . implode(",", $new_messages) . ") ";
	  }
	  else {
	    $not_in_clause = " 1=1 ";
	  }
	  ?>
	  <?php if($show_new){ ?>
	  <div id="newmsg_list">
	  <?php
	  $sql = "SELECT user_id, firstname, lastname, photo from users where 
	  enabled=1 and user_id in(" . implode(",", $new_messages) . ") 
	  order by lastname";
	  $result = executequery($sql);
	  //echo "<!-- yyy $sql -->\n";
	  while($record = go_fetch_array($result)){
	    $photo = $record['photo'];
		if($photo == "") $photo = "fcs.png";
	    if($photo != ""){
	      $max_width = 80;
          $max_height = 78;
          list($width, $height) = getimagesize($UPLOAD . "headshots/" . $photo);
          $ratioh = $max_height/$height;
          $ratiow = $max_width/$width;
          $ratio = min($ratioh, $ratiow);
          if($width > $max_width || $height > $max_height){
            $width = intval($ratio*$width);
            $height = intval($ratio*$height); 
          }
		}
	    ?>
		<div id="user_<?=$record['user_id']?>" class="chat_user_off" style="position:relative;" onclick="newMessage('<?=$record['user_id']?>')" onMouseOver="this.style.cursor='pointer'">
		
		<div style="float:left; width:<?=$max_width?>px;">
		<?php if($photo != ""){ ?>
		<img src="<?=$UPLOAD?>headshots/<?=$photo?>" width="<?=$width?>" height="<?=$height?>">
		<?php } else { echo "&nbsp;"; }?>
		</div>
		
		<div style="float:left; height:100%;">
		<?=stripslashes($record['firstname'])?>
		<br>
		<?=stripslashes($record['lastname'])?>
		<div style="position:absolute; bottom:0px;">NEW MSG</div>
		</div>
		</div>
		<div style="clear:both;"></div>
		<?php
	  }
	  ?>
	  </div><?php // end newmsg div ?>
	  <?php } ?>
	  
	  <?php // start of spot for multiple ?>
	  <div id="multiple_list">
	    <div id="user_multiple" class="chat_user_off" style="position:relative;" onclick="newMessage('multiple')" onMouseOver="this.style.cursor='pointer'">
		
		<div style="float:left; width:80px;">
		<img src="<?=$UPLOAD?>headshots/fcs.png" width="64" height="65">
		</div>
		
		<div style="float:left; height:100%;">
		Multiple
		</div>
		</div>
		<div style="clear:both;"></div>

	  </div><?php // end multiple div ?>
	  
	  <div>
	  <span id="online_arrow"><a href="javascript:ShowHide('online', '0')"><img src="images/arrow-button-down_off.png" border="0"></a></span> 
	  <strong>Online</strong>
	  </div>
	  <div id="online_list">
	  <?php
	  $sql = "SELECT user_id, firstname, lastname, photo from users where 
	  lastaction >= date_add(now(), interval -15 minute) 
	  and enabled=1 and $not_in_clause and user_id != '" . $SESSION_USER_ID . "' 
	  order by lastname";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $photo = $record['photo'];
		if($photo == "") $photo = "fcs.png";
	    if($photo != ""){
	      $max_width = 80;
          $max_height = 78;
          list($width, $height) = getimagesize($UPLOAD . "headshots/" . $photo);
          $ratioh = $max_height/$height;
          $ratiow = $max_width/$width;
          $ratio = min($ratioh, $ratiow);
          if($width > $max_width || $height > $max_height){
            $width = intval($ratio*$width);
            $height = intval($ratio*$height); 
          }
		}
	    ?>
		<div id="user_<?=$record['user_id']?>" class="chat_user_off" style="position:relative;" onclick="newMessage('<?=$record['user_id']?>')" onMouseOver="this.style.cursor='pointer'">
		
		<div style="float:left; width:<?=$max_width?>px;">
		<?php if($photo != ""){ ?>
		<img src="<?=$UPLOAD?>headshots/<?=$photo?>" width="<?=$width?>" height="<?=$height?>">
		<?php } else { echo "&nbsp;"; }?>
		</div>
		
		<div style="float:left;">
		<?=stripslashes($record['firstname'])?>
		<br>
		<?=stripslashes($record['lastname'])?>
		</div>
		</div>
		<div style="clear:both;"></div>
		<?php
	  }
	  ?>
	  </div><?php // end online div ?>
	  
	  
	  <div>
	  <span id="offline_arrow"><a href="javascript:ShowHide('offline', '1')"><img src="images/arrow-button-up_off.png" border="0"></a></span> 
	  <strong>Offline</strong>
	  </div>
	  <div id="offline_list" style="display:none;">
	  <?php
	  $sql = "SELECT user_id, firstname, lastname, photo from users where 
	  lastaction < date_add(now(), interval -15 minute) 
	  and enabled=1 and $not_in_clause and user_id != '" . $SESSION_USER_ID . "' 
	  order by lastname";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $photo = $record['photo'];
		if($photo == "") $photo = "fcs.png";
	    if($photo != ""){
	      $max_width = 80;
          $max_height = 78;
          list($width, $height) = getimagesize($UPLOAD . "headshots/" . $photo);
          $ratioh = $max_height/$height;
          $ratiow = $max_width/$width;
          $ratio = min($ratioh, $ratiow);
          if($width > $max_width || $height > $max_height){
            $width = intval($ratio*$width);
            $height = intval($ratio*$height); 
          }
		}
	    ?>
		<div id="user_<?=$record['user_id']?>" class="chat_user_off" style="position:relative;" onclick="newMessage('<?=$record['user_id']?>')" onMouseOver="this.style.cursor='pointer'">
		<div style="float:left; width:<?=$max_width?>px;">
		<?php if($photo != ""){ ?>
		<img src="<?=$UPLOAD?>headshots/<?=$photo?>" width="<?=$width?>" height="<?=$height?>">
		<?php } else { echo "&nbsp;"; }?>
		</div>
		<div style="float:left;">
		<?=stripslashes($record['firstname'])?>
		<br>
		<?=stripslashes($record['lastname'])?>
		</div>
		</div>
		<div style="clear:both;"></div>
		<?php
	  }
	  ?>
	  </div><?php // end offline div ?>
	  
	</div>
	<div style="height:100%; overflow:auto;">
	  <div id="convo_area" style="width:100%; height:100%;"></div>
	</div>
	</div>
	
	
	<div style="clear:both"></div>
	
  </div>
</div>
<?php include "includes/footer.php"; ?>