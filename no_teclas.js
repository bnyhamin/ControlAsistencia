//--* Deshabilita tecla F5
document.onkeydown=keyPressed; 
//alert(document.onkeydown);
function keyPressed(evt) { 
var f5 = 116; // Para F5 
var BACK = 8; // Para tecla BACKSPACE
var f6 = 117; // Para F6
var N=78;  //N
var U=85;  //U
var S=83;  //S
var f11 = 122;
var f12 = 123;
//alert(event.keyCode);
if(document.all) { 
    // Guardamos pulsaciones del teclado y comparamos a las de restringir 
	if (event.keyCode == f5) { 
         // Con event.keyCode anulamos la accion 
         event.keyCode = 0; 
		 var message=  "Funcion F5 desabilitada en esta paginasss!"; 
		 alert(message);
         window.event.returnValue = false; 
     }
	 if (event.keyCode == BACK) { 
         // Con event.keyCode anulamos la accion 
         event.keyCode = 0; 
		 var message=  "Funcion Back-Space desabilitada en esta pagina!, utilice tecla DEL O SUPR"; 
		 alert(message);
         window.event.returnValue = false; 
     }
     if(event.ctrlKey && event.keyCode == N){ 
    	 event.keyCode = 0; 
	     var message=  "Funcion CTRL + N bloqueada en esta pagina!"; 
	     alert(message);
	     window.event.returnValue = false; 
	}
	if(event.ctrlKey && event.keyCode == U){ 
         event.keyCode = 0; 
	     var message=  "Funcion CTRL + U bloqueada en esta pagina!"; 
	     alert(message);
	     window.event.returnValue = false; 
	}
    
    if(event.ctrlKey && event.keyCode == S){ 
         event.keyCode = 0; 
	     var message=  "Funcion CTRL + S bloqueada en esta pagina!"; 
	     alert(message);
	     window.event.returnValue = false; 
	}
     
	if(event.keyCode == f6) { // Con event.keyCode anulamos la accion 
	     event.keyCode = 0; 
		 window.event.returnValue = false; 
	}
    
    if(event.keyCode == f11) {//  Con event.keyCode anulamos la accion 
	     event.keyCode = 0; 
		 var message=  "Funcion F11 desabilitada en esta pagina!"; 
		 alert(message);
         window.event.returnValue = false; 
	} 
	if(event.keyCode == f12) {//  Con event.keyCode anulamos la accion 
	     event.keyCode = 0; 
		 var message=  "Funcion F12 desabilitada en esta pagina!"; 
		 alert(message);
         window.event.returnValue = false; 
	} 
 }
}


//////////F12 disable code Force!////////////////////////
    document.onkeypress = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
           //alert('No F-12');
            return false;
        }
    }
    document.onmousedown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
	document.onkeydown = function (event) {
        event = (event || window.event);
        if (event.keyCode == 123) {
            //alert('No F-keys');
            return false;
        }
    }
/////////////////////end///////////////////////

