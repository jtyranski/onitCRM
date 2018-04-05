<?php include "includes/header.php"; ?>
<script>
function a(x){
  y = x.value;
  if(y==1){
    document.getElementById('div1').style.display="";
	document.getElementById('div2').style.display="none";
  }
  else {
    document.getElementById('div1').style.display="none";
	document.getElementById('div2').style.display="";
  }
}
</script>
<form name="form1">
<select name="test" onchange="a(this)">
<option value="1">First</option>
<option value="2">Second</option>
</select>
</form>
<br>
<div id="div1" style="display:none;">
First option
</div>
<div id="div2" style="display:none;">
Second option
</div>
<script>
a(document.form1.test);
</script>
<?php include "includes/footer.php"; ?>