<!--
/*Disable right mouse click
*/
var message=  "Funcion del Mouse desabilitada en esta pagina!"; 
function click(e) 
{ if (document.all) 
	{if(event.button==2||event.button==3) 
		{
		alert(message);
		return false;
		}
	}
	if (document.layers) {
		if (e.which == 3) {
		alert(message);
		return false;
		}
	}
}
if (document.layers) {
document.captureEvents(Event.MOUSEDOWN);
}
document.onmousedown=click;
// --> 
