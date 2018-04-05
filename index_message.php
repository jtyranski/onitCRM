<?php
include "includes/functions.php";

$to_user_id = $_GET['to_user_id'];
$comment_id = go_escape_string($_GET['comment_id']);
$action = $_GET['action'];

function clean($x){
  $x = go_reg_replace("\n", "", $x);
  $x = go_reg_replace("\r", "", $x);
  $x = go_reg_replace("\"", "&quot;", $x);
  $x = go_reg_replace("\<", "&lt;", $x);
  $x = go_reg_replace("\>", "&gt;", $x);
  return $x;
}

if($to_user_id == $SESSION_USER_ID){
  $action = "self";
}

if($to_user_id=="multiple"){
  $action = "multiple";
}

if($action=="delmessage"){
  $sql = "DELETE from comments where comment_id='$comment_id' and owner = '" . $SESSION_USER_ID . "'";
  executeupdate($sql);
  $action = "form";
}

if($action=="delthread"){
  $sql = "DELETE from comments where (to_user_id='$to_user_id' or from_user_id='$to_user_id') and owner='" . $SESSION_USER_ID . "'";
  executeupdate($sql);
  $action = "form";
}



if($action=="form"){
  $sql = "SELECT concat(firstname, ' ', lastname) as fullname from users where user_id='$to_user_id'";
  $fullname = stripslashes(getsingleresult($sql));
  
  $html = "";
  
  $sql = "SELECT date_format(comment_time, \"%m/%d/%Y %r\") as datepretty, comment, from_user_id, to_user_id, comment_id from comments 
  where (to_user_id='$to_user_id' or from_user_id='$to_user_id') and owner='" . $SESSION_USER_ID . "' order by comment_id";
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    $datepretty = stripslashes($record['datepretty']);
	$comment = stripslashes($record['comment']);
	$comment = clean($comment);
	$class = "chat_from_you";
	if($record['from_user_id']==$SESSION_USER_ID) $class = "chat_from_me";
	$html .= "<div class='$class'>";
	$html .= "<div style='float:left;'>";
	$html .= "<em>" . $datepretty . "</em>";
	$html .= "</div>";
	$html .= "<div style='float:right;'>";
	$html .= "<a href=\\\"javascript:DelComment('" . $record['comment_id'] . "', '" . $to_user_id . "')\\\" class='blankclick'>X</a>";
	$html .= "</div>";
	$html .= "<div style='clear:both;'></div><br>";
	$html .= $comment . "<br><br>";
	$html .= "</div>";
	$html .= "<div style='clear:both; height:5px;'><img src='images/spacer.gif'></div>";
  }
  //$html .= "<div style='clear:both;'></div>";
  $html .= "<div id='scrollhere'></div>";
  $html .= "<div align='center'>";
  $html .= "<form name='newmessage' action='index_message_action.php' method='post'>";
  $html .= "<input type='hidden' name='to_user_id' value='" . $to_user_id . "'>";
  $html .= "<textarea name='message' rows='4' cols='70'></textarea>";
  $html .= "<br><input type='submit' name='submit1' value='Send'></form>";
  $html .= "</div>";
  $html .= "<div align='right'>";
  $html .= "<a href=\\\"javascript:DelThread('" . $to_user_id . "')\\\" class='blankclick'>Delete Thread</a>";
  $html .= "</div>";
  
  $sql = "UPDATE comments set new=0 where from_user_id='$to_user_id' and owner = '" . $SESSION_USER_ID . "'";
  executeupdate($sql);
  
 
}

if($action=="multiple"){
  $html = "";
  $html .= "<div id='scrollhere'></div>";
  $html .= "<form name='newmessage' action='index_message_action.php' method='post'>";
  $html .= "<input type='checkbox' name='all' onchange=\\\"SetChecked(this, 'multi_ids[]')\\\">All<br>";
  $html .= "<table width='100%' cellpadding='2'>";
  $sql = "SELECT user_id, concat(firstname, ' ', lastname) as fullname from users where enabled=1 and user_id != '" . $SESSION_USER_ID . "'
  order by lastname";
  $counter=0;
  $result = executequery($sql);
  while($record = go_fetch_array($result)){
    if($counter==0) $html .= "<tr>";
	$fullname = stripslashes($record['fullname']);
	$fullname = clean($fullname);
	$html .= "<td valign='top'><input type='checkbox' name='multi_ids[]' value='" . $record['user_id'] . "'>" . $fullname . "</td>";
	$counter++;
	if($counter==4){
	  $html .= "</tr>";
	  $counter = 0;
	}
  }
  if($counter != 0) $html .= "</tr>";
  $html .= "</table>";
  $html .= "<div align='center'>";
  $html .= "<textarea name='message' rows='4' cols='70'></textarea>";
  $html .= "<br><input type='submit' name='submit1' value='Send Multiple'></form>";
  $html .= "</div>";
}

if($action=="self"){
  $html = "If you need to write a message to yourself, I suggest getting a pen and a piece of paper.";
}


?>

div = document.getElementById('convo_area');
div.innerHTML = "<?php echo $html; ?>";
document.getElementById('scrollhere').scrollIntoView(true);
document.forms["newmessage"].message.focus();
  