<?php
$user_id = $SESSION_USER_ID;
?>
 <script type="text/javascript" src="overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
 <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
 
<div align="center">
  <div class="whiteround" style="height:80px;">
    <div style="width:100%; height:80px; position:relative;">
	  <div style="float:left; vertical-align:middle; height:80px; width:50px;" id="leftarrow">
	  <?=stripslashes(ScrollingArrow("L", "index_company.php", "0"));?>
      </div>

      <div id="companies1" style="display:block; overflow:hidden; height:80px; float:left; white-space: no-wrap; width:750px; position:relative;">
	  <?php
      $sql = "SELECT a.prospect_id, a.logo, a.company_name 
      from prospects a, opportunities b where a.prospect_id=b.prospect_id and b.user_id='$user_id' 
      and b.status='Sold' and b.display=1 group by b.prospect_id order by prospect_id limit 0, 5";
	  $result = executequery($sql);
	  while($record = go_fetch_array($result)){
	    $company_name = stripslashes($record['company_name']);
		$company_name = go_reg_replace("\'", "", $company_name);
		$company_name = go_reg_replace("\"", "", $company_name);
		$company_name_sub = substr($company_name, 0, 18);
	    ?>
		<div style="float:left;">
		<a href="view_company.php?prospect_id=<?=$record['prospect_id']?>">
	    <?php if($record['logo'] != ""){ ?>
		<img src="crop.php?x=0&y=0&w=64&h=65&src=<?=$UPLOAD?>/logos/<?=$record['logo']?>" onmouseover="return overlib('<?=$company_name?>');" onmouseout="return nd();" border="0">
	    <?php } else { ?>
		<img src="images/fcs-button_off.png" onmouseover="return overlib('<?=$company_name?>');" onmouseout="return nd();" border="0">
		<?php } ?>
		</a>
		<br>
		<?=$company_name_sub?>
		</div>
		<div style="float:left; width:40px;">
		<img src="images/spacer.gif">
		</div>
	    <?php
      }
      ?>
	  
	  
      </div>
      <div id="companies2" style="display:none; overflow:hidden; height:80px; float:left; white-space: no-wrap; width:750px; position:relative;">
      </div>
      <div style="float:right; vertical-align:middle; height:80px; width:50px;" id="rightarrow">
      <?=stripslashes(ScrollingArrow("R", "index_company.php", "0"));?>
      </div>
      
	</div>
    <div style="clear:both;"></div>
  </div>
</div>