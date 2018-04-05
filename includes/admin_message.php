<div style="width:100%; position:relative;" id="admin_message_area">

<div style="float:left; padding-right:20px; padding-left:20px; margin-top:20px;">
<?php if($HEADSHOT != ""){ ?>
<img src="<?=$UPLOAD?>headshots/<?=$HEADSHOT?>">
<?php } ?>
</div>

<div style="width:85%; float:left;">
  <div class="whiteround" style="min-height:100px; max-height:180px;">
  <div style="width:100%; min-height:100px; max-height:180px; overflow:auto;">
  <?php
  $sql = "SELECT message, date_format(date, \"%m/%d/%y\") as datepretty 
  from admin_message";
  $result = executequery($sql);
  $record = go_fetch_array($result);
  $datepretty = $record['datepretty'];
  $message = stripslashes($record['message']);
  
  echo $datepretty . "<br>" . $message;

  ?>
  </div>
  </div>
</div>

</div>
<div style="clear:both;"></div>