<?php

$html = "<input type='text' class='phoneext' name='mobile' value='2225554444'>";
?>

$(".phoneext").livequery(function(){
    $(this).mask('(999) 999-9999? x99999');
});

div = document.getElementById('testdiv');
div.innerHTML = "<?php echo $html; ?>";