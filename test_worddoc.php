<?php 
 $fp = fopen("uploaded_files/temp/test.doc", 'w'); 
    $str = "<html><body><B>This is the text for the word 

         file created through php programming</B>
		 <br>
		 <table width='100%'>
		 <tr>
		 <td>col 1</td>
		 <td>col 2</td>
		 </tr>
		 </table>
		 <br>
		 <img src='http://www.encitegroup.com/devzone/images/bingmap-icon.png'>
		 </body></html>"; 

    fwrite($fp, $str); 

    fclose($fp); 
?>