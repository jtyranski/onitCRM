<?php include "includes/header_white.php"; ?>
<script src="includes/video-js/video.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="includes/video-js/video-js.css" type="text/css" media="screen" title="Video JS" charset="utf-8">

<script type="text/javascript" charset="utf-8">
    // Add VideoJS to all video tags on the page when the DOM is ready
    VideoJS.setupAllWhenReady();
  </script>


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
<?php include "includes/footer.php"; ?>