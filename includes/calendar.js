<!--
// Kaosweaver Calendar Settings - do not remove
// by Paul Davis - http://www.kaosweaver.com
// KW_lang[English]
// KW_order[0,1,2]
// KW_del1[/]
// KW_del2[/]
// KW_dd[false]
// KW_cWidth[170]
// KW_fd[-1]

var sDate = new Array();
var mName = new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var wName = new Array("Su","Mo","Tu","We","Th","Fr","Sa")
var cFontName = "Arial, Helvetica, sans-serif;"
var KW_color = new Array("#ffffff","#ffcccc","#999999","#ffffff","#ccffcc","#cccccc","#000000","#000000")
var KW_cl=0;
var KW_od=-1;
var KW_tmo=0;
var KW_cWidth=170;
var KW_fd=-1;
var KW_ss=0;
var KW_sw=0;
var descx=-1;
var descy=-1;
var bwNN=(document.captureEvents)?1:0;
function popmousemove(e){descx=(bwNN)?e.pageX:event.x;descy=(bwNN)?e.pageY:event.y}
function KW_mouseInit(){
	if(bwNN)document.captureEvents(Event.MOUSEMOVE);document.onmousemove=popmousemove;
}
function m_class(m,d,y) { 
	this.month=(m<10)?"0"+m:m;if (d) this.day=(d<10)?"0"+d:d;else this.day="";this.year=y;
	this.output=this.month+"/"+this.day+"/"+this.year;	var kd=new Date();this.special=checkDates(this.month,this.day,this.year)
	this.today=((kd.getMonth()+1)==this.month && kd.getDate()==this.day && kd.getFullYear()==this.year)
	var td=new Date(this.year, (this.month-1), Number(this.day)+KW_od);this.past=(KW_od==-1)?0:(kd>td)
	var tf=new Date(kd.getFullYear(),kd.getMonth(),kd.getDate()+KW_fd);
	var d1=new Date(this.year, (this.month-1), this.day);
	this.future=(KW_fd==-1)?0:(tf<d1);this.ss=(this.special && KW_ss);
	this.display=(this.past || this.future || this.ss);
}

// Kaosweaver End of Calendar Settings - do not remove

function checkDates(m1,d1,y1) { //v2.1.5
	var rStr=false;for(var i=0;i<sDate.length;i++) {var tDate=sDate[i].split(",");
		if (tDate[2]=="*" || tDate[2]==y1) {if (tDate[1]==d1 && tDate[0]==m1)	rStr=true;
		}}return rStr;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function KW_doCalendar(obj,E,m,y) { //v2.5.3
  var d=new Date();f=0;d.setDate(1);if (!m && m!=0) {m=d.getMonth();f=KW_cl}
  if (f==1 && MM_findObj('KW_selectedMonth').value!=-1)
  m=MM_findObj('KW_selectedMonth').value-1;d.setMonth(m);
  if (!y) y=d.getFullYear();if (f==1 && MM_findObj('KW_selectedYear').value!=-1)
  y=MM_findObj('KW_selectedYear').value;d.setFullYear(y);dy=d.getDay();if (!E) E=0;
  if(E==1)dy=(dy==0)?6:dy-1;dP=new Date();dP.setMonth(m);dP.setDate(0);
  pStart=dP.getDate()-dy+1;dStr=new Array();for (i=pStart;i<dP.getDate()+1;i++) { tmo=(KW_tmo)?"":i;
  tMonth=(m==0)?"12":m;tYear=(m==0)?y-1:y;dStr[dStr.length]=new m_class(tMonth,tmo,tYear);
  }EOM=false;for (i=1;!EOM;i++){d.setDate(i);if (m!=d.getMonth()) EOM=true; else {
  dStr[dStr.length]=new m_class((Number(m)+1),i,y);}}cnt=1;si=0;
  if(E==1)si=(d.getDay()==0)?6:d.getDay()-1;else si=d.getDay();	for (i=si;i<7;i++) {
  tMonth=(m==11)?"1":Number(m)+2;tYear=(m==11)?Number(y)+1:y; tmo=(KW_tmo)?"":cnt;
  dStr[dStr.length]=new m_class(tMonth,tmo,tYear); cnt++;}pM=(m==0)?11:m-1;
  pY=(m==0)?y-1:y;nM=(m==11)?0:Number(m)+1;nY=(m==11)?Number(y)+1:y;
  wStr="<ht"+""+"ml><he"+""+"ad><st"+""+"yle type=\"text/css\"><!--  body { background-color: "+KW_color[6]+"}\ntd {  font-family: "+cFontName+" font-size: 12px; }\n.tblHdr { font-weight: bold; color: "+KW_color[0]+"; background-color: "+KW_color[6]+" }\n.subTbl{ color: "+KW_color[0]+"; background-color: "+KW_color[7]+";  text-align: center}-->\n</st"+""+"yle>\n<ti"+""+"tle>"+mName[m]+", "+y+"</ti"+""+"tle>\n</he"+""+"ad>\n<bo"+""+"dy  topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" onLoad=\"window.focus()\">\n<table width=\"100%\" border=\"0\" cellspacing=\"0\">\n<tr>\n<td bgcolor=\""+KW_color[6]+"\">\n<table width=\"100%\" border=0>\n"
  wStr+="<tr>\n<td align=center bgcolor=\""+KW_color[6]+"\" class=\"tblHdr\" colspan=\"4\"><a href=\"javascript:window.opener.KW_doCalendar('"+obj+"',"+E+",'"+pM+"','"+pY+"');\" class=\"tblHdr\">&laquo</a>&nbsp;&nbsp;"+mName[m]+"&nbsp;&nbsp;<a href=\"javascript:window.opener.KW_doCalendar('"+obj+"',"+E+",'"+nM+"','"+nY+"');\"  class=\"tblHdr\">&raquo</a></td>\n<td colspan=3 align=center class=\"tblHdr\"><a href=\"javascript:window.opener.KW_doCalendar('"+obj+"',"+E+",'"+m+"','"+(y-1)+"');\" class=\"tblHdr\">&laquo</a>&nbsp;&nbsp;"+y+"&nbsp;&nbsp;<a href=\"javascript:window.opener.KW_doCalendar('"+obj+"',"+E+",'"+m+"','"+(Number(y)+1)+"');\"  class=\"tblHdr\">&raquo</a></td>\n</tr>\n"
  wStr+="<tr>\n";for(wdn=0;wdn<7;wdn++)wStr+="<td class=\"subTbl\">"+wName[wdn]+"</td>\n";wStr+="</tr>\n"
  for (x=0;x<parseInt(dStr.length/7);x++) {	wStr+="<tr>\n";	for (y=0;y<7;y++) {
  yT=(E==1)?5:0;bC=(y==yT||y==6)?KW_color[2]:KW_color[3];
  if ((y==yT||y==6) && KW_sw && !dStr[x*7+y].display) dStr[x*7+y].display=true;
  if ((Number(m)+1)!=dStr[x*7+y].month) bC=KW_color[5]; if (dStr[x*7+y].special)
  bC=KW_color[4];if (dStr[x*7+y].today) bC=KW_color[1];
  a0=(KW_cl!=1)?"":"window.opener.MM_findObj('KW_selectedMonth',window.opener.document).value='"+dStr[x*7+y].month+"';window.opener.MM_findObj('KW_selectedYear',window.opener.document).value='"+dStr[x*7+y].year+"'; "
  a1=(dStr[x*7+y].display)?"":"<a href=\"javascript:window.opener.MM_findObj('"+obj+"',window.opener.document).value='"+dStr[x*7+y].output+"';"+a0+" window.close();\" >";
  a2=(dStr[x*7+y].display)?"":"</a>";
  wStr+="<td align=\"center\" bgcolor="+bC+">"+a1+dStr[x*7+y].day+a2+"</td>\n";
	}	wStr+="</tr>\n";}	wStr+="<tr><td colspan=7></td></tr></table></td></tr></table></bo"+""+"dy></ht"+""+"ml>";
	var screenX=(self.screenX)?self.screenX+20:window.screenLeft
	var screenY=(self.screenY)?self.screenY+70:window.screenTop
	var w =(descx==-1)?parseInt(screen.width/2-75):descx+screenX;
	var h=(descy==-1)?parseInt(screen.height/2-75):descy+screenY;
	var look='width='+KW_cWidth+',height=155,left='+w+',top='+h;	popwin=window.open('','calendar',look);
	popwin.document.open();	popwin.document.write(wStr);	popwin.document.close();
}

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->