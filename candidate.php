<?php include "includes/header.php"; ?>

<div style="width:100%; height:50px; background:url(images/menu-bar-pattern.png); background-repeat:repeat-x; padding-top:6px;">
  <div style="float:left; height:38px; width:4px;"><img src="images/menu-bar-left.png"></div>
    <div id="sub_0" style="float:left;" class="sub_off" onclick="document.location.href='contacts.php'" onMouseOver="this.style.cursor='pointer'">
	Search
	</div>
	<div id="sub_1" style="float:left;" class="sub_on">
	Candidates
	</div>

  <div style="float:left; height:38px; width:4px;"><img src="images/menu-bar-right.png"></div>
  <div style="clear:both;"></div>
</div>

<div align="center">
  <div class="whiteround" style="height:800px;">
  <iframe frameborder="0" width="100%" height="800" style="border:none;" src="../frame_user_activities.php?eventfor=Candidates"></iframe>
  </div>
</div>
<?php include "includes/footer.php"; ?>