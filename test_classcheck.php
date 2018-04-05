<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<script>
function checkclass(classname, x){
  var allHTMLTags=document.getElementsByTagName("*");
//Loop through all tags using a for loop
  for (i=0; i<allHTMLTags.length; i++) {
//Get all tags with the specified class name.
    if (allHTMLTags[i].className==classname) {
	  if(x==1){
        allHTMLTags[i].checked=true;
	  }
	  else{
	    allHTMLTags[i].checked=false;
	  }
    }
  }
}
</script>
<input type="checkbox" name="1" class="classname">check me<br>
<input type="checkbox" name="5" class="classname">check me<br>
<input type="checkbox" name="6" class="classname">check me<br>
<input type="checkbox" name="7" class="classname">check me<br>
<input type="checkbox" name="2">Not me
<br><br>
<a href="javascript:checkclass('classname', 1)" id="checkall">check</a><br>
<a href="javascript:checkclass('classname', 0)" id="checkall">uncheck</a><br>
</body>
</html>
