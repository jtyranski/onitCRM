<script src="includes/jquery-1.6.4.js"></script>
<script src="includes/jquery.maskedinput-1.3.js"></script>
<script src="includes/jquery.livequery.js"></script>
<script type="text/javascript">/*<![CDATA[*/// 

function gotest(){
  
  url = "test_mask_pop.php";
    //url=url+"&sid="+Math.random();
	//document.getElementById('debug').innerHTML = url;
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

$(".phoneext").livequery(function(){
    $(this).mask('(999) 999-9999? x99999');
});


</script>

<form action="test_mask_action.php" method="post">
<input class="phoneext" type="text" name="phone" />
<br>
<input type="submit">
<br>
<div id="testdiv"></div>
</form>
<a href="javascript:gotest()">go</a>