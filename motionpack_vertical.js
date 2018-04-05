/********************************************************
  Based on sliding div script by Harry Maugans
  
  http://www.harrymaugans.com/2007/03/06/how-to-create-an-animated-sliding-collapsible-div-with-javascript-and-css/
  
  Added horizontal sliding option by John Wert Jr
  http://www.whobetterthanjunior.com
********************************************************/

var timerlen = 5;
var slideAniLen = 250;
var slideDirection = "vertical";

var timerID = new Array();
var startTime = new Array();
var obj = new Array();
var endHeight = new Array();
var moving = new Array();
var dir = new Array();


function slidedown(objname){
        if(moving[objname])
                return;

        if(document.getElementById(objname).style.display != "none")
                return; // cannot slide down something that is already visible

        moving[objname] = true;
        dir[objname] = "down";
        startslide(objname);
}

function slideup(objname){
        if(moving[objname])
                return;

        if(document.getElementById(objname).style.display == "none")
                return; // cannot slide up something that is already hidden

        moving[objname] = true;
        dir[objname] = "up";
        startslide(objname);
}

function startslide(objname){
        obj[objname] = document.getElementById(objname);

		
		if(slideDirection=="horizontal"){
		  endHeight[objname] = parseInt(obj[objname].style.width);
		}
		else {
		  endHeight[objname] = parseInt(obj[objname].style.height);
	    }
		
        startTime[objname] = (new Date()).getTime();

        if(dir[objname] == "down"){
				
				if(slideDirection=="horizontal"){
				  obj[objname].style.width = "1px";
				}
				else {
				  obj[objname].style.height = "1px";
				}
				
        }

        obj[objname].style.display = "block";

        timerID[objname] = setInterval('slidetick(\'' + objname + '\');',timerlen);
}

function slidetick(objname){
        var elapsed = (new Date()).getTime() - startTime[objname];

        if (elapsed > slideAniLen)
                endSlide(objname)
        else {
                var d =Math.round(elapsed / slideAniLen * endHeight[objname]);
                if(dir[objname] == "up")
                        d = endHeight[objname] - d;

				
				if(slideDirection=="horizontal"){
				  obj[objname].style.width = d + "px";
				}
				else {
				  obj[objname].style.height = d + "px";
				}
				
        }

        return;
}

function endSlide(objname){
        clearInterval(timerID[objname]);

        if(dir[objname] == "up")
                obj[objname].style.display = "none";

		
		if(slideDirection=="horizontal"){
		  obj[objname].style.width = endHeight[objname] + "px";
		}
		else {
		  obj[objname].style.height = endHeight[objname] + "px";
		}
		

        delete(moving[objname]);
        delete(timerID[objname]);
        delete(startTime[objname]);
        delete(endHeight[objname]);
        delete(obj[objname]);
        delete(dir[objname]);

        return;
}

function toggleSlide(objname){
  if(document.getElementById(objname).style.display == "none"){
    // div is hidden, so let's slide down
    slidedown(objname);
  }else{
    // div is not hidden, so slide up
    slideup(objname);
  }
}


function slideThrough(objname, totalObj){
  for(x=1;x<=totalObj;x++){
    if(document.getElementById(objname + x).style.display == "block") {current_display = x;}
  }
  
  next = current_display + 1;
  var exists = document.getElementById(objname + next);
  if(exists == null) next = 1;
  
  
  for(x=1;x<=totalObj;x++){
    if(x==next){
    // div is hidden, so let's slide down
      slidedown(objname + x);
    }else{
    // div is not hidden, so slide up
      slideup(objname + x);
    }
  }
  

}