//funciones javaScript

nombresMes = Array("","january","february","march","april","may","june","july","august","september","october","november","december");

var anoInicial = 1900;
var anoFinal = 2100;
var ano;
var mes;
var dia;
var campoDeRetorno;
var titulo;

function diasDelMes(ano,mes) {
       if ((mes==1)||(mes==3)||(mes==5)||(mes==7)||(mes==8)||(mes==10)||(mes==12)) dias=31
  else if ((mes==4)||(mes==6)||(mes==9)||(mes==11)) dias=31
  else if ((((ano % 100)==0) && ((ano % 400)==0)) || (((ano % 100)!=0) && ((ano % 4)==0))) dias = 29
  else dias = 28;
  return dias;
};

function crearSelectorMes(mesActual) {
  var selectorMes = "";
  selectorMes = "<select class='select' name='mes' size='1' onChange='javascript:opener.dibujarMes(self.document.Forma1.ano[self.document.Forma1.ano.selectedIndex].value,self.document.Forma1.mes[self.document.Forma1.mes.selectedIndex].value);'>\r\n";
  for (var i=1; i<=12; i++) {
    selectorMes = selectorMes + "  <option value='" + i + "'";
    if (i == mesActual) selectorMes = selectorMes + " selected";
    selectorMes = selectorMes + ">" + nombresMes[i] + "</option>\r\n";
  }
  selectorMes = selectorMes + "</select>\r\n";
  return selectorMes;
}

function crearSelectorAno(anoActual) {
  var selectorAno = "";
  selectorAno = "<select class='select' name='ano' size='1' onChange='javascript:opener.dibujarMes(self.document.Forma1.ano[self.document.Forma1.ano.selectedIndex].value,self.document.Forma1.mes[self.document.Forma1.mes.selectedIndex].value);'>\r\n";
  for (var i=anoInicial; i<=anoFinal; i++) {
    selectorAno = selectorAno + "  <option value='" + i + "'";
    if (i == anoActual) selectorAno = selectorAno + " selected";
    selectorAno = selectorAno + ">" + i + "</option>\r\n";
  }
  selectorAno = selectorAno + "</select>";
  return selectorAno;
}

function crearTablaDias(numeroAno,numeroMes) {
  var tabla = "<table border='0' cellpadding='2' cellspacing='0' bgcolor='#ffffff'>\r\n  <tr>";
  var fechaInicio = new Date();
  fechaInicio.setYear(numeroAno);
  fechaInicio.setMonth(numeroMes-1);
  fechaInicio.setDate(1);
  ajuste = fechaInicio.getDay();
  tabla = tabla + "\r\n    <td align='center'>Su</td><td align='center'>Mo</td><td align='center'>Tu</td><td align='center'>We</td><td align='center'>Th</td><td align='center'>Fr</td><td align='center'>Sa</td></div>\r\n  <tr>";
  for (var j=1; j<=ajuste; j++) {
    tabla = tabla + "\r\n    <td></td>";
  }
  for (var i=1; i<10; i++) {
    tabla = tabla + "\r\n    <td"
    if ((i == diaHoy()) && (numeroMes == mesHoy()) && (numeroAno == anoHoy())) tabla = tabla + " bgcolor='#ff0000'";
    tabla = tabla + "><input class='button' type='button' value='0" + i + "' onClick='javascript:opener.ano=self.document.Forma1.ano[self.document.Forma1.ano.selectedIndex].value; opener.mes=self.document.Forma1.mes[self.document.Forma1.mes.selectedIndex].value; opener.dia=" + i + "; opener.cmdCambiar_onclick(" + i + ", self.document.Forma1.mes[self.document.Forma1.mes.selectedIndex].value , self.document.Forma1.ano[self.document.Forma1.ano.selectedIndex].value); self.close();'></td>";
    if (((i+ajuste) % 7)==0) tabla = tabla + "\r\n  </tr>\r\n\  <tr>";
  }
  for (var i=10; i<=diasDelMes(numeroAno,numeroMes); i++) {
    tabla = tabla + "\r\n    <td"
    if ((i == diaHoy()) && (numeroMes == mesHoy()) && (numeroAno == anoHoy())) tabla = tabla + " bgcolor='#ff0000'";
    tabla = tabla + "><input class='button' type='button' value='" + i + "' onClick='javascript:opener.ano=self.document.Forma1.ano[self.document.Forma1.ano.selectedIndex].value; opener.mes=self.document.Forma1.mes[self.document.Forma1.mes.selectedIndex].value; opener.dia=" + i + "; opener.cmdCambiar_onclick(" + i + ", self.document.Forma1.mes[self.document.Forma1.mes.selectedIndex].value , self.document.Forma1.ano[self.document.Forma1.ano.selectedIndex].value); self.close();' id='button'1 name='button'1></td>";
    if (((i+ajuste) % 7)==0) tabla = tabla + "\r\n  </tr>\r\n\  <tr>";
  }
  tabla = tabla + "\r\n  </tr>\r\n</table>";
  return tabla;
}

function dibujarMes(numeroAno,numeroMes) {
  var html = "";
  html = html + "<html>\r\n<head>\r\n  <title>" + titulo + "</title>\r\n<link href='../style/tstyle.css' rel='stylesheet' type='text/css'>\r\n</head>\r\n<body class='PageBODY' onUnload='opener.escribirFecha();'>\r\n  <div align='center'>\r\n  <form name='Forma1'>\r\n";
  html = html + crearSelectorMes(numeroMes);
  html = html + crearSelectorAno(numeroAno);
  html = html + crearTablaDias(numeroAno,numeroMes);
  html = html + "<center><p><input class='button' type='button' name='hoy' value='Hoy : " + dia + "/" + mes + "/" + ano + "' onClick='javascript: opener.cmdCambiar_onclick(" + dia + "," + mes + "," + ano + " ); self.close();'></center>";
  html = html + "\r\n  </form>\r\n</div>\r\n";
  html = html + "</body>\r\n</html>\r\n";
  ventana = open("","calendario","width=220,height=270");
  ventana.document.open();
  ventana.document.writeln(html);
  ventana.document.close();
  ventana.focus();
}

function anoHoy() {
  var fecha = new Date();
  if (navigator.appName == "Netscape") return fecha.getYear() + 1900
  else return fecha.getYear();
}

function mesHoy() {
  var fecha = new Date();
  return fecha.getMonth()+1;
}

function diaHoy() {
  var fecha = new Date();
  return fecha.getDate();
}

function escribirFecha() {
  campoDeRetorno.value = dia + "/" + mes + "/" + ano;

}
function cmdCambiar_onclick(dia, mes, anio){
	//msgbox dia & "/" & mes & "/" & anio
	if (dia*1 < 10 && dia.length == 1 ){
		cd="0" + dia;
	}else{
		cd=dia;
	}
	if (mes*1 < 10 && mes.length == 1 ){
		cm="0" + mes;
	}else{
		cm= mes;
	}
	cy=anio;
	//document.frm.txtFecha.value = cd & "/" & cm & "/" & cy
}
function imgOnOff(boton, imagen)  {
		boton.src=imagen;
}
function WindowResizeXY(w,h){
/*
parametros=
        w: ancho
	h: alto
*/
	var win=null;
	window.resizeTo(w, h);

}
function WindowResize(w,h,pos){
/*parametros= w: ancho
			  h:alto
			  pos: posicion en pantalla(center/random)*/
	var win=null;
	if(pos=="random"){
		LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
		TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	}
	if(pos=="center"){ 
		LeftPosition=5;
		TopPosition= 5;
	}
	else if ((pos!="center" && pos!="random") || pos==null){
		LeftPosition=500;TopPosition=300
	}
	window.resizeTo(screen.width-w, screen.height-h);
	window.moveTo(LeftPosition , TopPosition);
}

function WindowParentResize(w,h,pos){
/*parametros= w: ancho
			  h:alto
			  pos: posicion en pantalla(center/random/otro)*/
	var win=null;
	if(pos=="random"){
		LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
		TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	}
	if(pos=="center"){ 
		LeftPosition=5;
		TopPosition= 5;
	}
	else if ((pos!="center" && pos!="random") || pos==null){
		LeftPosition=500;TopPosition=300
	}
	window.parent.resizeTo(screen.width-w, screen.height-h);
	window.parent.moveTo(LeftPosition , TopPosition);
}
