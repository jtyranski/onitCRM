<?php
include "includes/header_white.php";

$property_id = go_escape_string($_GET['property_id']);
$opm_id = go_escape_string($_GET['opm_id']);
$opm_entry_id = go_escape_string($_GET['opm_entry_id']);
?>
<script type="text/javascript" language="javascript" src="lytebox/lytebox.js"></script>
<script src="fileuploader_opm/fileuploader.js" type="text/javascript"></script>
    <script>
	    function startuploaders(){
		  createUploader();
		}
		  
        function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('fup'),
                action: 'fileuploader_opm/php2.php',
				params: {
                  opm_id: '<?=$opm_id?>',
				  opm_entry_id: '<?=$opm_entry_id?>',
                  table: 'opm_entry_photos',
				  id: 'photo_id',
				  description: '',
				  path: 'opm_photos',
				  additional: '0',
				  type: '1'
                }
            });           
        }
		
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = startuploaders;
		
		function DelPhoto(x){
		  cf = confirm("Are you sure you want to delete this daily report photo?");
		  if(cf){
		    document.location.href="frame_property_production_opm_photos_delete.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>&opm_entry_id=<?=$opm_entry_id?>&photo_id=" + x;
		  }
		} 
function load_photos () {
  //alert(x);
  
 
  //alert(contmsg);
    url = "frame_property_production_opm_photos_generate.php?opm_entry_id=<?=$opm_entry_id?>";
	url=url+"&sid="+Math.random();
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);

}

function editField(photo_id){
  url="frame_property_production_opm_photos_edit.php?opm_entry_id=<?=$opm_entry_id?>&action=form&photo_id=" + photo_id;
    url=url+"&sid="+Math.random();
	//document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}

function updateField(photo_id){
  
  newvalue = document.getElementById('newvalue').value;
  newvalue = newvalue.replace(/\&/g, "AMPERSAND");
  newvalue = newvalue.replace(/\#/g, "POUNDSIGN");
  newvalue = newvalue.replace(/\"/g, "DBLQUOTE");
  newvalue = newvalue.replace(/\n/g, "NEWLINE");
  url = "frame_property_production_opm_photos_edit.php?opm_entry_id=<?=$opm_entry_id?>&action=update&photo_id=" + photo_id + "&newvalue=" + newvalue;
    url=url+"&sid="+Math.random();
	 //document.getElementById('maindebug').style.display="";
	//document.getElementById('maindebug').innerHTML = "<a href='" + url + "'>" + url + "</a>";
	//alert(url);
        // Create new JS element
        var jsel = document.createElement('SCRIPT');
        jsel.type = 'text/javascript';
        jsel.src = url;

        // Append JS element (therefore executing the 'AJAX' call)
        document.body.appendChild (jsel);
		
}
    </script>
	
  <link href="fileuploader_opm/fileuploader.css" rel="stylesheet" type="text/css">	
<div class="main">

<strong>Please enter the daily progress photos:</strong>
<div id="fup">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
</div>

<br><br>

<a href="javascript:load_photos()">Refresh uploaded images</a><br>
<a href="frame_property_production.php?property_id=<?=$property_id?>&opm_id=<?=$opm_id?>">Return to Production</a><br>

<br>

<div id="photo_area"></div>
  
</div>
<script>load_photos();</script>