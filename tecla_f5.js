//--* Deshabilita tecla F5
document.onkeydown=keyPressed; 
//alert(document.onkeydown);
function keyPressed(evt) { 
var f5 = 116; // Para F5 
var BACK = 8; // Para tecla BACKSPACE
var f6 = 117; // Para F6
//alert(event.keyCode);
if(document.all) { 
    // Guardamos pulsaciones del teclado y comparamos a las de restringir 
	if (event.keyCode == f5) { 
         // Con event.keyCode anulamos la accion 
         event.keyCode = 0; 
		 var message=  "Funcion F5 desabilitada en esta pagina!"; 
		 alert(message);
         window.event.returnValue = false; 
     }
	 if (event.keyCode == BACK) { 
         // Con event.keyCode anulamos la accion 
         event.keyCode = 0; 
		 var message=  "Funcion Back-Space desabilitada en esta pagina!"; 
		 alert(message);
         window.event.returnValue = false; 
     }
	if(event.keyCode == f6) { // Con event.keyCode anulamos la accion 
	     event.keyCode = 0; 
		 window.event.returnValue = false; 
	} 
 }
} 

