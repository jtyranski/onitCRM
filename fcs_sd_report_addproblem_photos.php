<?php include "includes/header_white.php"; ?>
<?php
$leak_id = $_GET['leak_id'];
$problem_id = $_GET['problem_id'];

$special_invoice = "";
$sql = "SELECT invoice_type from master_list where master_id='" . $SESSION_MASTER_ID . "'";
$invoice_type = getsingleresult($sql);
if($invoice_type==2) $special_invoice = 2;
//$special_invoice = "";

?>
<script src="fileuploader_fcs/fileuploader.js" type="text/javascript"></script>
    <script>
	    function startuploaders(){
		  createUploader();
		  createUploader2();
		}
		  
        function createUploader(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('fup'),
                action: 'fileuploader_fcs/php2.php',
				params: {
                  leak_id: '<?=$leak_id?>',
				  problem_id: '<?=$problem_id?>',
                  table: 'am_leakcheck_photos',
				  id: 'photo_id',
				  description: '',
				  path: 'leakcheck',
				  additional: '0',
				  type: '1'
                }
            });           
        }
		
		function createUploader2(){            
            var uploader = new qq.FileUploader({
                element: document.getElementById('fup2'),
                action: 'fileuploader_fcs/php2.php',
				params: {
                  leak_id: '<?=$leak_id?>',
				  problem_id: '<?=$problem_id?>',
                  table: 'am_leakcheck_photos',
				  id: 'photo_id',
				  description: '',
				  path: 'leakcheck',
				  additional: '0',
				  type: '2'
                }
            });           
        }
		
        
        // in your app create uploader as soon as the DOM is ready
        // don't wait for the window to load  
        window.onload = startuploaders;     
    </script>
	
  <link href="fileuploader_fcs/fileuploader.css" rel="stylesheet" type="text/css">	
<div class="main">

<strong>Please enter the PROBLEM photos:</strong>
<div id="fup">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
</div>

<br><br>


<strong>Please enter the CORRECTION photos:</strong>
<div id="fup2">		
		<noscript>			
			<p>Please enable JavaScript to use file uploader.</p>
			<!-- or put a simple form for upload here -->
		</noscript>         
</div>

<br><br>

<input type="button" name="button1" value="Finished Uploading Photos" onClick="document.location.href='fcs_sd_report_view<?=$special_invoice?>.php?leak_id=<?=$leak_id?>'">
</div>