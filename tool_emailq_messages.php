<?php
include "includes/functions.php";

$selected = $_GET['selected'];
$action = $_GET['action'];
$message = $_GET['message'];

if($action=="view"){
  $selected = go_reg_replace("\,$", "", $selected);

  $s_array = explode(",", $selected);

  $_SESSION[$sess_header . '_emailq'] = $s_array;
  $message = 0;
}

if(is_array($_SESSION[$sess_header . '_emailq'])){
  $id = $_SESSION[$sess_header . '_emailq'][$message];
  $sql = "SELECT *, date_format(ts, \"%m/%d/%Y %r\") as ts_pretty from email_q where id='$id'";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $showcounter = $message + 1;
  $totalmessages = sizeof($_SESSION[$sess_header . '_emailq']);
  $showprev = "Prev";
  $shownext = "Next";
  if($message > 0) {
    $x_prev = $message - 1;
	$showprev = "<a href=\"javascript:showMessagesNext('$x_prev')\">Prev</a>";
  }
  if($showcounter < $totalmessages){
    $x_next = $message + 1;
	$shownext = "<a href=\"javascript:showMessagesNext('$x_next')\">Next</a>";
  }
  ob_start();
  ?>
  <div align="right">
  <?=$showcounter?> of <?=$totalmessages?> &nbsp; &nbsp;
  <?=$showprev?> &nbsp; <?=$shownext?>
  </div>
  <?=$record['ts_pretty']?>
  <br><br>
  <strong>Headers:</strong>
  <br>
  To: <?=stripslashes(nl2br($record['to_field']))?><br>
  <?=stripslashes(nl2br($record['headers']))?>
  <br><br>
  <strong>Subject:</strong>
  <br>
  <?=stripslashes(nl2br($record['subject']))?>
  <br><br>
  <?php if($record['attachment'] != ""){ ?>
  <strong>Attachment:</strong> &nbsp;
  <a href="<?=$record['attachment']?>" target="_blank"><?=$record['attachment']?></a>
  <br><br>
  <?php } ?>
  <strong>Message:</strong>
  <br>
  <div style="height:350px; overflow:auto;">
  <?=stripslashes($record['message'])?>
  </div>
  <br><br>
  <?php
  $html = ob_get_contents();
  ob_end_clean();
  $html = jsclean($html);
}
  
?>
div = document.getElementById('messages_content');
div.innerHTML = '<?php echo $html; ?>';
Openwin('messages');