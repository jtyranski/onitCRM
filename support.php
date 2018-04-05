<?php include "includes/header.php"; ?>
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
    url = "support_pop.php?event_id=" + x;
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

<div align="center">
  <div class="whiteround" style="height:1200px;">
  <div align="left">
  
  <div style="width:100%; position:relative;">
  <div style="float:left; width:80%;">
  <span style="font-size:18px; font-weight:bold;">Support</span>
  <p style="width:80%;">This page has been designed to answer common questions as well as give you the information needed to refer back to. Also, Companies going through the Certification Process can use this page to refer to.  Homework summaries are listed at the bottom. If you can't find what you're looking for on this page please contact Will Riley at 855-633-3327 ext. 584.</p>

<!--<p><h1 style="font-size:16px; font-weight:bold;">Tutorial</h1><a href="training_tutorial.php"><img src="/images/fcs_tutorial_icon.png" width="341" height="229" /></a><br />
Click to view</p>-->

<div style="width:1150px;">
<p><h1 style="font-size:1.2em; font-weight:bold;">Evolution Of The Roofing Industry</h1><iframe width="560" height="315" src="http://www.youtube.com/embed/wruZZfLsa7o" frameborder="0" allowfullscreen></iframe><span style="float:right;margin-top:-43px;"><h1 style="font-size:1.2em; font-weight:bold;"><?=$MAIN_CO_NAME?> Sales Technologies</h1><iframe width="560" height="315" src="http://www.youtube.com/embed/rwGy6wBDEsA" frameborder="0" allowfullscreen></iframe></span></p>
<p><h1 style="font-size:1.2em; font-weight:bold;">Admin Basics (Setting up your core)</h1><iframe width="560" height="315" src="http://www.youtube.com/embed/JEj759or2uI" frameborder="0" allowfullscreen></iframe><span style="float:right;margin-top:-43px;"><h1 style="font-size:1.2em; font-weight:bold;">Populating your Core</h1><iframe width="560" height="315" src="http://www.youtube.com/embed/8mhN0YBuILI" frameborder="0" allowfullscreen></iframe></span></p>
<p><h1 style="font-size:1.2em; font-weight:bold;">Benefits of the Core</h1><iframe width="560" height="315" src="http://www.youtube.com/embed/IQpL19V4YBA" frameborder="0" allowfullscreen></iframe><span style="float:right;margin-top:-43px;"><h1 style="font-size:1.2em; font-weight:bold;">Navigating the Core</h1><iframe width="560" height="315" src="http://www.youtube.com/embed/3fIwPoqKSFM" frameborder="0" allowfullscreen></iframe></iframe></span></p>
</div>
  

  </div>

  <div style="float:right; width:300px;margin:10px;" align="center">
    <p style="font-weight:bold;">Call for Support: 855-633-3327, dial 2</p>
    <p><span style="font-size:1em; font-weight:bold;">Install <?=$MAIN_CO_NAME?> Connect on your IOS device</span><br><br>
  This app can only be installed on an IOS mobile devices (ipad 3rd gen, ipad 2, iphone 4s, iphone 4, ipod touch)<br>
- If you are viewing this page from your IOS mobile device, just click this link:  
<a href="itms-services://?action=download-manifest&url=http://encitegroup.com/iphone/contractor/fcsconnect.plist">INSTALL</a><br/><br/>
    OR<br/><br/>
- Open Safari web browser on your IOS mobile device and go to: <a href="http://app.fcscontrol.com">http://app.fcscontrol.com</a></p>
  </div>
  </div>
  <div style="clear:both;"></div>

</div>
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

<?php include "includes/footer.php"; ?>
