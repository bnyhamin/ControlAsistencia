
/*****************************************
* LCD Clock script- by Javascriptkit.com
* Featured on/available at http://www.dynamicdrive.com/
* This notice must stay intact for use
*****************************************/


var isNN = (navigator.appName.indexOf("Netscape")!=-1);
var browser=navigator.appName;
var alternate=0;
var currentsec=0;	
var currentmin=0;	
var currentmil=0;
var keepgoin=false;

function timer(){
 if(keepgoin){
  currentmil+=1;		// increment
   if (currentmil==10){		// de 10 miliseg
    currentmil=0;		// Cambiar miliseg a cero
    currentsec+=1;		// y annadir uno
   }
   //if (currentsec==60){		 
   // currentsec=0;		
   // currentmin+=1;		
   //}
  Strsec=""+currentsec;		
  //Strmin=""+currentmin;		  
  //Strmil=""+currentmil;		
  // if (Strsec.length!=2){	
  //  Strsec="0"+currentsec;	
  // }				
  // if (Strmin.length!=2){	
  //  Strmin="0"+currentmin;
  //}

  
  //document.display.seconds.value=Strsec;
    document.frm.crono.value=Strsec;	
  //document.display.minutes.value=Strmin;
  //document.display.milsecs.value=Strmil;
  setTimeout("timer()", 100);	
 }
}
function startover(){		
keepgoin=false;			
//currentsec=0;
//currentmin=0;
//currentmil=0;
Strsec="00";
//Strmin="00";
//Strmil="00";
//document.frm.crono.value=Strsec;
}

function show(){
if(browser =='Microsoft Internet Explorer'){
 var o=document.frm.elements('clock');
}

if(browser =='Netscape'){
 var o=document.getElementById('clock');

}

var Digital=new Date();

var anio=Digital.getYear();
var mes=Digital.getMonth() +1 ;
var day=Digital.getDate();

if(mes<10) mes="0" + mes;
if(day<10) day="0" + day;

var hours=Digital.getHours();
var minutes=Digital.getMinutes();
var seconds = Digital.getSeconds();
var dn="AM";

if (hours==12){ 
    dn="PM"; 
}
if (hours>12){
    dn="PM";
 }


if (hours==0) hours=12;
if (hours.toString().length==1){
    hours="0" + hours;
}
if (minutes<=9){
   minutes="0" + minutes;
}

if (seconds<=9){
   seconds="0" + seconds;
 }

if (alternate==0){
     o.value="" + anio + "-" + mes + "-" + day + " " +hours +":"+ minutes +":"+ seconds +"";
}
else{
     o.value="" + anio + "-" + mes + "-" + day + " " +hours +":"+ minutes +":"+ seconds +"";
}

alternate = (alternate==0)? 1 : 0;
setTimeout("show()",1000);
}
