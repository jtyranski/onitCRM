<?php include "includes/header_white.php"; ?>
<script src="includes/video-js/video.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="includes/video-js/video-js.css" type="text/css" media="screen" title="Video JS" charset="utf-8">

<script type="text/javascript" charset="utf-8">
    // Add VideoJS to all video tags on the page when the DOM is ready
    VideoJS.setupAllWhenReady();
  </script>

<div id="fcsrep_content" style="background-color:#000;margin:0 20px;">
<h1 id="ind_rep_support" style="color:red;font-weight:normal;">Independent Rep Support</h1>

<div id="videos" style="width:80%;text-justify:left;">

<h2 class="vid_title" style="color:grey;font-weight:normal;float:left;width:30%;">
1. Evolution of the Roofing Industry<br />
<!-- video -->
<div id="videodisp" class="video-js-box" style="background-color: #000000; text-decoration: none; border: 0px;" onclick="">
	<video class="video-js" style="border: 0px;" id="video" width="398" height="221" controls preload poster="<?=$CORE_URL?>uploaded_files/videos/evolution/evolution_vid.png">
        	<!-- MP4 must be first for iPad! -->
                                   	
                <source src="<?=$CORE_URL?>uploaded_files/videos/evolution/evolution.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' /><!-- Safari / iOS, IE9 -->
                <source src="<?=$CORE_URL?>uploaded_files/videos/evolution/evolution.webm" type='video/webm; codecs="vp8, vorbis"' /><!-- Chrome10+, Ffx4+, Opera10.6+ -->
                <source src="<?=$CORE_URL?>uploaded_files/videos/evolution/evolution.ogv" type='video/ogg; codecs="theora, vorbis"' /><!-- Firefox3.6+ / Opera 10.5+ -->
                                   	
                <!-- this A tag is where your Flowplayer will be placed. it can be anywhere -->
                                   	
                <a  
                	class="imagelink"
                        href="<?=$CORE_URL?>uploaded_files/videos/evolution/evolution.flv"
                        style="text-decoration: none;display:block;width:398px;height:221px;"  
                        id="player">                </a>
                                   	
                <!-- this will install flowplayer inside previous A- tag. -->
                <script>
                        flowplayer("player", "flowplayer/flowplayer-3.2.7.swf", 
                        {onFinish: function() { 
                        	videoEndHandler();
                                indexFadeLogo();
                         }
                         }    
                         );
                </script>
	</video>
</div>
</h2>
<h2 class="vid_title" style="color:grey;font-weight:normal;float:left;width:30%;">
2. FCSCore Service Technologies<br />
<!-- video -->
<div id="videodisp2" class="video-js-box" style="background-color: #000000; text-decoration: none; border: 0px;" onclick="">
	<video class="video-js" style="border: 0px;" id="video" width="398" height="221" controls poster="<?=$CORE_URL?>uploaded_files/videos/fcscore_tech/fcscore_tech_vid.png">
        	<!-- autoplay -->
        	<!-- MP4 must be first for iPad! -->
                                   	
                <source src="<?=$CORE_URL?>uploaded_files/videos/fcscore_tech/fcscore_tech.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' /><!-- Safari / iOS, IE9 -->
                <source src="<?=$CORE_URL?>uploaded_files/videos/fcscore_tech/fcscore_tech.webm" type='video/webm; codecs="vp8, vorbis"' /><!-- Chrome10+, Ffx4+, Opera10.6+ -->
                <source src="<?=$CORE_URL?>uploaded_files/videos/fcscore_tech/fcscore_tech.ogv" type='video/ogg; codecs="theora, vorbis"' /><!-- Firefox3.6+ / Opera 10.5+ -->
                                   	
                <!-- this A tag is where your Flowplayer will be placed. it can be anywhere -->
                                   	
                <a  
                	class="imagelink"
                        href="<?=$CORE_URL?>uploaded_files/videos/fcscore_tech/fcscore_tech.flv"
                        style="text-decoration: none;display:block;width:398px;height:221px;"  
                        id="player">                </a>
                                   	
                <!-- this will install flowplayer inside previous A- tag. -->
                <script>
                        flowplayer("player", "flowplayer/flowplayer-3.2.7.swf", 
                        {onFinish: function() { 
                        	videoEndHandler();
                                indexFadeLogo();
                         }
                         }    
                         );
                </script>
	</video>
</div>
</h2>
</div>

<div id="videos" style="width:60%;text-justify:left;clear:left; background-color: #000000;">
<h2 class="doc_title" style="color:white;font-weight:normal;float:left;width:20%;">
<a href="uploaded_files/docs/sample_roof_report.pdf" target="_blank"><img src="images/roof_report.png" alt="Roof Report" width:168px height:217px /></a><br />
Roof Report
</h2>
<h2 class="doc_title" style="color:white;font-weight:normal;float:left;width:20%;">
<a href="uploaded_files/docs/sales_packet.pdf" target="_blank"><img src="images/sales_packet.png" alt="Sales Packet" width:168px height:217px /></a><br />
Sales Packet
</h2>
<h2 class="doc_title" style="color:white;font-weight:normal;float:left;width:20%;">
<a href="uploaded_files/docs/contractor_marketing.pdf" target="_blank"><img src="images/contractor_marketing.png" alt="Contractor Marketing" width:168px height:217px /></a><br />
Contractor Marketing
</h2>
<h2 class="doc_title" style="color:white;font-weight:normal;float:left;width:20%;">
<a href="uploaded_files/docs/order_form.pdf" target="_blank"><img src="images/order_form.png" alt="Order Form" width:168px height:217px /></a><br />
Order Form
</h2>
</div>

<div id="videos" style="width:80%;text-justify:left;clear:left; background-color: #000000;">
<h2 class="vid_title" style="color:grey;font-weight:normal;float:left;width:30%;">
FCS For Large Companies<br />
<img src="images/fcs_large_companies.png" alt="FCS Large Companies" width:345px height:180px />
</h2>
<h2 class="vid_title" style="color:grey;font-weight:normal;float:left;width:30%;">
FCS For Small Companies<br />
<img src="images/fcs_small_companies.png" alt="FCS Small Companies" width:345px height:180px />
</h2>
</div>

</div>

<?php include "includes/footer.php"; ?>
