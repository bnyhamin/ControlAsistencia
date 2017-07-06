//**************************
//**************************
//AQUI EMPIEZA NUESTRO CODIGO
//**************************
//**************************

function changeValue( i )
{
	myCheckBox = document.all(i);
	with( myCheckBox )
	{
		if( myCheckBox.value == 'True' )
			myCheckBox.value = 'False';
		else
			myCheckBox.value = 'True';
	}
}

function StatusOver( s )
{
	window.status = s;
}

function StatusOut()
{
	window.status = '';
}

function over( c )
{
	document.all(c).className = 'over';
}

function out( c )
{
	document.all(c).className = 'out';
}

function test( frm )
{
	bool = true
	with( document.forms(frm) )
	{
		for( i = 0; i < length; i++ )
		{
			if( elements(i).value == '' && elements(i).alt == '1' )
			{
				bool = false;
				alert( "Debe ingresar un valor!!!" );
				break;
			}
		}
	}
	return bool;
}

function delivery( frm )
{
	bool = false;
	with( document.forms(frm) )
	for( i = 0; i < elements.length; i++ )
	{
		if( elements(i).checked )
		{
			action = elements(i).value;
			bool = true
			break;
		}
	}

	return bool;
}

function cantDiasdelMes(mes, ano){
  if (mes==2){
    var res = (ano*1 / 4) + '';
    if (res.indexOf('.') > -1) return 28;
    else return 29;
  }else if (mes==4 || mes==6 || mes==9 || mes==11) return 30;
  else return 31;
}

function sumaDiasaFecha(inicio, dias, fin){
  var fechafinal = 0;
  var anofinal = '';
  var mesfinal = '';
  var diafinal = '';
  
  var fecha = document.getElementById(inicio).value;
  var dias = dias;
  
  var arrfecha = fecha.split('/');
  if (arrfecha.length!=3) return false
       
  var ano = arrfecha[2];
  if (isNaN(ano)) return false
  
  var mes = arrfecha[1];
  if (isNaN(mes)) return false
  
  var dia = arrfecha[0];
  if (isNaN(dia)) return false
  
  anofinal = ano;
  mesfinal = mes;
  
  var diasmes = cantDiasdelMes(mes, ano);
  var diassaldo = (diasmes*1 - dia*1);
  
  if (dias*1 <= diassaldo*1) diafinal = (dia*1 + dias*1);
  else{
    dias -= diassaldo*1;
    for (var i=(mes*1); i<=12; i++){
      if (i == 12){
        i=0;
        anofinal++;
      }
      var dmes = cantDiasdelMes((i+1), anofinal);
      if (dias*1 <= dmes*1){
        diafinal = dias;
        mesfinal = (i+1);
        break;
      }else{
        dias -= dmes*1;
      }
    }
  }
  
  if (diafinal*1 < 10) diafinal = '0' + diafinal*1;
  if (mesfinal*1 < 10) mesfinal = '0' + mesfinal*1;
  
  fechafinal = diafinal + '/' + mesfinal + '/' + anofinal;
  
  document.getElementById(fin).value = fechafinal;
}

/****
**** Para el menu en �rbol
****/

function clickchildrens(root)
{
	var myRoot, myObj, myCheck;
	myRoot = document.all(root);
	if( myRoot.all.length == 0 ) return;
	myCheck = document.all('ck'+root);
	with(myRoot.all)
	{
		for( i = 0; i < length; i++ )
		{
			if( item(i).type == 'checkbox' )
			{
				myObj = document.all(item(i).id);
				myObj.checked = myCheck.checked;
			}
		}
	}
}


function overarbol(capa)
{
	var myDiv, myD, myImg;
	myDiv = document.all(capa);
	if( myDiv.all.length == 0 ) return;
	myD = document.all('p'+capa);
	myImg = document.all('i'+capa);
	if( myDiv.className == 'show' )
	{
		myDiv.className = 'hide';
		myD.className = 'plus';

		begin = myImg.src.lastIndexOf('/');
		end = 8;
		link = myImg.src.substr(begin+1, end);

		if( link != 'none.gif' )
			myImg.src = '../images/plus.gif';
	}
	else
	{
		myDiv.className = 'show';
		myD.className = 'minus';

		begin = myImg.src.lastIndexOf('/');
		end = 8;
		link = myImg.src.substr(begin+1, end);

		if( link != 'none.gif' )
			myImg.src = '../images/minus.gif';
	}
}

function esentero(e){
	var a = (isNN) ? e.which : e.keyCode;
	var ok=false;

	if ((a>=48 && a<=57) || (a==8) || (a==0) || a==45 ){
		ok=true;
	}
	return ok;
}

function validaEntero(o){
  var valor = parseInt(o.value)

  //Compruebo si es un valor num�rico
  if (isNaN(valor)) {
     //entonces (no es numero) devuelvo el valor cadena vacia
     alert('Valor de campo no es un n�mero Entero v�lido');
     o.focus();
     o.select();
     return false;
  }else{
     //En caso contrario (Si era un n�mero) devuelvo el valor
     return true;
  }

}

function esnumero(e){
    //alert("x");
	var ok=false;  
        var a = (document.all) ? e.keyCode : e.which; // 2
	//var a=window.event.keyCode;
	if (a>=48 && a<=57){
		ok=true;
	}
	return ok;
}

function esdecimal(e){
	var ok=false;
	var a=window.event.keyCode;
	if ((a>=48 && a<=57) || (a==46)){
		ok=true;
	}
	return ok;
}

function isValidDate(dateStr)
{
 var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/;
 var matchArray = dateStr.match(datePat); // is the format ok?

 if (matchArray == null) {
    return true;
 }

 day = matchArray[1];
 month = matchArray[3]; // parse date into variables
 year = matchArray[4];

 if (month < 1 || month > 12) { // check month range
    alert("Mes debe estar entre 1 y 12");
    return false;
 }

 if (day < 1 || day > 31) {
    alert("Dia debe estar entre 1 y 31");
    return false;
 }

 if ((month==4 || month==6 || month==9 || month==11) && day==31) {
    alert("El Mes "+month+" no tiene 31 dias!")
    return false
 }

 if (month == 2) { // check for february 29th
    var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
    if (day>29 || (day==29 && !isleap)) {
       alert("Febrero " + year + " no tiene " + day + " dias!");
       return false;
    }
 }
 return true;  // date is valid
}
function ValidaHora(hourStr)
{
//hora entrada en formato hh:mm  ***--> este es mejor
 hourStr = hourStr.replace(":", "/") + "/0000";
 //alert(hourStr);
 var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/;
 var matchArray = hourStr.match(datePat); // is the format ok?

 if (matchArray == null) {
	return false;
 }
 hour = matchArray[1];
 min = matchArray[3]; // parse date into variables
 if (hour < 0 || hour > 23) {
	alert("Hora debe ser entre 0 y 23");
	return false;
 }
 if (min < 0 || min > 59) {
	alert("Minuto debe ser entre 0 y 59");
	return false;
 }
 return true;
}

function isValidHour(hourStr)
{
// hora de entrada: hh/mm/sssss
 var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/;
 var matchArray = hourStr.match(datePat); // is the format ok?

 if (matchArray == null) {
	return false;
 }
 hour = matchArray[1];
 min = matchArray[3]; // parse date into variables
 if (hour < 0 || hour > 23) {
	alert("Hora debe ser entre 0 y 23");
	return false;
 }
 if (min < 0 || min > 59) {
	alert("Minuto debe ser entre 0 y 59");
	return false;
 }
 return true;
}

function randomizeURL(url) {
	now = new Date();
	timeStampParam = "ts" + now.getTime();
	return url + timeStampParam;
}

function Mover(frm,origen,destino)
{
	var myForm, mySelect, myElement;
	myForm = document.forms(frm);
	mySelect = myForm.item(destino);

	with(myForm.item(origen).options)
	{
		for( i = 0; i < length; i++ )
		{
			if( item(i).selected )
			{
				myElement = document.createElement("option");
				myElement.text = options(i).text;
				myElement.value = options(i).value;
				mySelect.add( myElement );
				remove(i);
				i--;
			}
		}
	}
}

function validar( frm, source )
{
	var myObj, mySource;
	var myObj = document.forms( frm );
	var mySource = myObj.elements( source );

	for( i = 0; i < mySource.length; i++ )
	{
		mySource[i].selected = true;
	}

	return true;
}

function validarCampo(formulario, campo){
	o=document.getElementById(campo);
	if (o.value==0){
                alert('Indique valor');
		o.focus();
		return false;
	}
	return true
}



function SelectFind(object,value)
{
	obj = document.all(object);
	for( i = 0; i < obj.length; i++ )
	{
		if( obj(i).value == value )
		{
			obj.selectedIndex = i;
			break;
		}
	}
}

function Fecha_Mas_Hora(){
	var s="" ;
	var now=new Date() ;
	var dia=now.getDate() ;
	var mes=now.getMonth() + 1 ;
	var anio=now.getYear() ;
	var hora=now.getHours() ;
	var min=now.getMinutes() ;
	var sec=now.getSeconds() ;
	s=dia + "/" + mes + "/" + anio + " - " + hora + ":" + min + ":" + sec ;
	return s;
}

//****
//**** INICIO: Sobre el SCROLL vertical
//****

// configure the below five variables to change the style of the scroller

var scrollerwidth = 150;
var scrollerheight = 80;
var scrollerbgcolor = '';

//set below to '' if you don't wish to use a background image

var scrollerbackground = 'scrollerback.gif';

//configure the below variable to change the contents of the scroller

var messages=new Array()
messages[0] = "<a href='../intranet/default.asp' target='middle'>Sistema de Seguridad</a><br>Soporte a la Intranet"
messages[1] = "<a href='../petinformaticas/default.htm' target='middle'>Sistema de Peticiones Informaticas</a><br>Permite realizar solucitudes de intervencion t�cnica"
messages[2] = "<a href='../evalper/default.asp' target='middle'>Evaluaci�n de Personal</a><br>Piloto para la evaluaci�n de los operadores"
messages[3] = "<a href='../afphorizonte/default.asp'>AFP Horizonte</a><br>Aplicativo para el ingreso de datos"
messages[4] = "<a href='../sistema_comercial/default.asp'>Sistema Comercial</a><br>Sistema en desarrollo"
messages[5] = "<a href='../sistemadegestion/default.asp'>Sistema de Gesti�n</a><br>Sistema en desarrollo"

///////Do not edit pass this line///////////////////////

if( messages.length > 1 ) i = 2;
else i = 0;

function move1(whichlayer)
{
	tlayer = eval( whichlayer );
	if( tlayer.top > 0 && tlayer.top <= 5 )
	{
		tlayer.top=0;
		setTimeout( "move1(tlayer)", 3000 );
		setTimeout( "move2(document.main.document.second)", 3000 );
		return;
	}
	if( tlayer.top >= tlayer.document.height * -1 )
	{
		tlayer.top -= 5;
		setTimeout( "move1(tlayer)", 100 );
	}
	else
	{
		tlayer.top = scrollerheight;
		tlayer.document.write( messages[i] );
		tlayer.document.close();
		if( i == messages.length - 1 ) i = 0;
		else i++;
	}
}

function move2(whichlayer)
{
	tlayer2 = eval( whichlayer );
	if( tlayer2.top > 0 && tlayer2.top <= 5)
	{
		tlayer2.top = 0;
		setTimeout( "move2(tlayer2)", 3000 );
		setTimeout( "move1(document.main.document.first)", 3000 );
		return;
	}
	if( tlayer2.top >= tlayer2.document.height * -1 )
	{
		tlayer2.top -= 5;
		setTimeout( "move2(tlayer2)", 100 );
	}
	else
	{
		tlayer2.top=scrollerheight
		tlayer2.document.write(messages[i])
		tlayer2.document.close()
		if(i == messages.length - 1 ) i = 0;
		else i++;
	}
}

function move3(whichdiv)
{
	tdiv = eval( whichdiv );
	if( tdiv.style.pixelTop > 0 && tdiv.style.pixelTop <= 5 )
	{
		tdiv.style.pixelTop = 0;
		setTimeout( "move3(tdiv)", 3000 );
		setTimeout( "move4(second2)", 3000 );
		return;
	}
	if( tdiv.style.pixelTop >= tdiv.offsetHeight * -1 )
	{
		tdiv.style.pixelTop -= 5;
		setTimeout( "move3(tdiv)", 100 );
	}
	else
	{
		tdiv.style.pixelTop = scrollerheight;
		tdiv.innerHTML = messages[i];
		if( i == messages.length - 1 ) i = 0;
		else i++;
	}
}

function move4(whichdiv)
{
	tdiv2 = eval(whichdiv);
	if ( tdiv2.style.pixelTop > 0 && tdiv2.style.pixelTop <= 5 )
	{
		tdiv2.style.pixelTop = 0;
		setTimeout( "move4(tdiv2)", 3000 );
		setTimeout( "move3(first2)", 3000 );
		return;
	}
	if( tdiv2.style.pixelTop >= tdiv2.offsetHeight * -1 )
	{
		tdiv2.style.pixelTop -= 5;
		setTimeout("move4(second2)", 100 );
	}
	else
	{
		tdiv2.style.pixelTop = scrollerheight;
		tdiv2.innerHTML = messages[i];
		if( i == messages.length - 1 ) i = 0;
		else i ++;
	}
}

function startscroll()
{
	if( document.all )
	{
		move3(first2);
		second2.style.top = scrollerheight;
		second2.style.visibility = 'visible';
	}
	else if(document.layers)
	{
		document.main.visibility = 'show';
		move1( document.main.document.first );
		document.main.document.second.top = scrollerheight + 5;
		document.main.document.second.visibility = 'show';
	}
}

function start_onload()
{
	window.onload = startscroll;
}

//****
//**** FIN: Sobre el SCROLL vertical
//****

function jump(myselect,myframe)
{
	my = document.all(myselect);
	op = my.selectedIndex;
	var newWindow;  //window.resizeTo(825,450);
	if ( op == 7 ) {
		var wd=new Date();
		var wnombre=wd.getSeconds();
		window.open("http://saplccc109/atentoapp/default.asp","n"+ wnombre,"toolbar=no,width=450,height=230,menubar=no,resizable=no")
		return true;
	}
	if ( op == 10 ) {
		window.open("http://saplccc107/tesoro/kiosco/default.asp","n","toolbar=no,menubar=no,resizable=yes")
		return true;
	}
	if( op > 0 ) {
		switch(myframe)
		{
			case 'middle':
				window.parent.parent.frames('middle').document.location = my[op].value;
			case 'cover':
				window.parent.frames('cover').document.location = my[op].value;
		}
	}
}


//*******
//*******

function DataLoad( theRs, object, parent )
{
	var obj = document.all(object);

	iRun = "";
	iRun = iRun + "for( i = 0; i < " + theRs + ".length; i++ )";
	iRun = iRun + "{";
	iRun = iRun + "  if( " + theRs + "[i][0] == parent )";
	iRun = iRun + "  {"
	iRun = iRun + "    var e = document.createElement('option');";
	iRun = iRun + "    e.value = " + theRs + "[i][1];";
	iRun = iRun + "    e.text = " + theRs + "[i][2];";
	iRun = iRun + "    obj.add(e);";
	iRun = iRun + "  }";
	iRun = iRun + "}";
	eval( iRun );
}

function FindSelected( object, value )
{
	obj = document.all(object);
	for( i = 0; i < obj.length; i++ )
	{
		if( obj(i).value == value )
		{
			obj.selectedIndex = i;
			break;
		}
	}
}

function iTest( frm )
{
	bool = true
	with( document.forms(frm) )
	{
		for( i = 0; i < length; i++ )
		{
			if( elements(i).value == '' && elements(i).alt != '' )
			{
				bool = false;
				alert( elements(i).alt );
				break;
			}
		}
	}
	return bool;
}

function ChangeSelected( theRs, myParent, myChild )
{
	var obj = document.forms(0);
	ichildLength = obj.elements(myChild).options.length;
	iselectedIndex = obj.elements(myParent).selectedIndex;
	iselectedValue = obj.elements(myParent).options(iselectedIndex).value;

	for( i = 1; i < ichildLength; i++ )
	{
		obj.elements(myChild).remove(1);
	}

	iRun = "";
	iRun = iRun + "for( i = 0; i < " + theRs + ".length; i++ )";
	iRun = iRun + "{";
	iRun = iRun + "  if( " + theRs + "[i][0] == iselectedValue )";
	iRun = iRun + "  {";
	iRun = iRun + "     var e = document.createElement('option');";
	iRun = iRun + "     e.value = " + theRs + "[i][1];";
	iRun = iRun + "     e.text = " + theRs + "[i][2];";
	iRun = iRun + "     obj.elements(myChild).add(e);";
	iRun = iRun + "   }";
	iRun = iRun + "}";
	eval( iRun );

	obj.elements(myChild).selectedIndex = 0;
}

function CenterWindow(mypage,myname,w,h,scroll,pos){
/*parametros= MyPage: Nombre de Pagina
			  myname: nombre de window o ventana
			  w: ancho
			  h:alto
			  scroll: tiene scroll(yes/no)
			  pos: posicion en pantalla(center/random)*/
	var win=null;
	if(pos=="random"){
		LeftPosition=(screen.width)?Math.floor(Math.random()*(screen.width-w)):100;
		TopPosition=(screen.height)?Math.floor(Math.random()*((screen.height-h)-75)):100;
	}
	if(pos=="center"){
		LeftPosition=(screen.width)?(screen.width-w)/2:100;
		TopPosition=(screen.height)?(screen.height-h)/2:100;
	}
	else if ((pos!="center" && pos!="random") || pos==null){
		LeftPosition=0;TopPosition=20
	}
	settings='width='+w+',height='+h+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no';
	win=window.open(mypage,myname,settings);
}

function Round(Numero, decimales)
{
  //Convertimos el valor a entero de acuerdo al # de decimales
  Valor = Numero;
  ndecimales = Math.pow(10,decimales);
  Valor = Valor * ndecimales;

  //Redondeamos y luego dividimos por el # de decimales
  Valor = Math.round(Valor);
  Valor = Valor /ndecimales
  return Valor;
}

function formatofecha(o){
var ok=false;
var a=window.event.keyCode;
var texto= o.value;
if (texto.length > 9){
	o.value = texto.substr(0,9);
	ok=true;
	return ok;
}

if (texto.length == 2 || texto.length == 5){
	o.value += "/";
}
if (a>=48 && a<=57){
	ok=true;
}
return ok;
}

function formatohora(o){
var ok=false;
var a=window.event.keyCode;
var texto= o.value;
if (texto.length > 4){
	o.value = texto.substr(0,4);
	ok=true;
	return ok;
}
if (texto.length == 2){
	o.value += ":";
}
if (a>=48 && a<=57){
	ok=true;
}
return ok;
}

function validarEntrada(campo){
 o=document.getElementById(campo);
 
 if (o.value==0){
   alert('Indique valor: ' + o.alt );
   o.focus();
   return false;
 }
 return true
}

function valida_fecha_rango(inicio, fin){
	var xdel = inicio
	var xarrdel = xdel.split("/")

	var ddia= xarrdel[0];
	if(ddia.length < 2) ddia= "0" + xarrdel[0];

	var dmes= xarrdel[1];
	if(dmes.length<2) dmes= "0" + xarrdel[1];
	var xnumdel= xarrdel[2]+dmes+ddia;

	var xal = fin
	var xarral = xal.split("/")

	var adia= xarral[0];
	if(adia.length < 2) adia= "0" + xarral[0];

	var ames= xarral[1];
	if(ames.length<2) ames= "0" + xarral[1];
	var xnumal= xarral[2]+ames+adia;

	//alert(xnumdel + ' ' + xnumal)

	if ((xnumdel *1) > (xnumal *1 )) {
		alert("Error en Rango de fechas");
		return false;
	}
	return true;
}

function letras(e) {
tecla = (document.all) ? e.keyCode : e.which;
if (tecla==8) return true;//Tecla de retroceso (para poder borrar)
patron =/[A-Z a-z ����� 1234567890 . /]/; // Solo acepta letras
te = String.fromCharCode(tecla);
return patron.test(te);
}

function WindowResize(w,h){
/*
parametros=
        w: ancho
	h: alto
*/
	var win=null;
	window.resizeTo(w, h);

}

function WindowPosition(w,h){
/*
parametros=
        w: ancho
	h: alto
*/
	window.moveTo(w , h);
}

function borrar_options(obj){
	var elSel = document.getElementById(obj);
	while (elSel.length > 0){
		elSel.remove(elSel.length - 1);
	}
}

function agregar_options(obj,codigo,descripcion){
  var elOptNew = document.createElement('option');
  elOptNew.text = descripcion;
  elOptNew.value = codigo;
  var elSel = document.getElementById(obj);
  try {
    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  }
  catch(ex) {
    elSel.add(elOptNew); // IE only
  }
}

function exportarExcel(tableID) {
var i;
var j;
var mycell;
//tableID: nombre de tabla
var objXL = new ActiveXObject("Excel.Application");
var objWB = objXL.Workbooks.Add();
var objWS = objWB.ActiveSheet;
for (i=0; i < document.getElementById(tableID).rows.length; i++)
{
    for (j=0; j < document.getElementById(tableID).rows(i).cells.length; j++)
    {
        mycell = document.getElementById(tableID).rows(i).cells(j)
        objWS.Cells(i+1,j+1).Value = mycell.innerText;
    }
}
//objWS.Range("A1", "L1").Font.Bold = true;
objWS.Range("A1", "Z1").EntireColumn.AutoFit();
//objWS.Range("C1", "C1").ColumnWidth = 50;
objXL.Visible = true;
}


function convierteMayuscula(obj){
	
	obj.value	= obj.value.toUpperCase();
    
}

function validateIp(id) {
    //Creamos un objeto 
    object=document.getElementById(id);
    valueForm=object.value;

    // Patron para la ip
    var patronIp=new RegExp("^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$");
    //window.alert(valueForm.search(patronIp));
    // Si la ip consta de 4 pares de números de máximo 3 dígitos
    
    if(valueForm.search(patronIp)==0)
    {
        // Validamos si los números no son superiores al valor 255
        valores=valueForm.split(".");
        if(valores[0]<=255 && valores[1]<=255 && valores[2]<=255 && valores[3]<=255)
        {
            //Ip correcta
            object.style.color="#000";
            return true;
        }
    }
    
    //Ip incorrecta
    object.style.color="#f00";
    
    return false;
      
}

function utf8_decode (str_data) {

  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Norman "zEh" Fuchs
  // +   bugfixed by: hitwork
  // +   bugfixed by: Onno Marsman
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: kirilloid
  // *     example 1: utf8_decode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'

  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0,
    c4 = 0;

  str_data += '';

  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 <= 191) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 <= 223) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else if (c1 <= 239) {
      // http://en.wikipedia.org/wiki/UTF-8#Codepage_layout
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      c4 = str_data.charCodeAt(i + 3);
      c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
      c1 -= 0x10000;
      tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1>>10) & 0x3FF));
      tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
      i += 4;
    }
  }

  return tmp_arr.join('');
}

function utf8_encode (argString) {
  
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: sowberry
  // +    tweaked by: Jack
  // +   bugfixed by: Onno Marsman
  // +   improved by: Yves Sucaet
  // +   bugfixed by: Onno Marsman
  // +   bugfixed by: Ulrich
  // +   bugfixed by: Rafal Kukawski
  // +   improved by: kirilloid
  // +   bugfixed by: kirilloid
  // *     example 1: utf8_encode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'

  if (argString === null || typeof argString === "undefined") {
    return "";
  }

  var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
  var utftext = '',
    start, end, stringl = 0;

  start = end = 0;
  stringl = string.length;
  for (var n = 0; n < stringl; n++) {
    var c1 = string.charCodeAt(n);
    var enc = null;

    if (c1 < 128) {
      end++;
    } else if (c1 > 127 && c1 < 2048) {
      enc = String.fromCharCode(
         (c1 >> 6)        | 192,
        ( c1        & 63) | 128
      );
    } else if (c1 & 0xF800 != 0xD800) {
      enc = String.fromCharCode(
         (c1 >> 12)       | 224,
        ((c1 >> 6)  & 63) | 128,
        ( c1        & 63) | 128
      );
    } else { // surrogate pairs
      if (c1 & 0xFC00 != 0xD800) { throw new RangeError("Unmatched trail surrogate at " + n); }
      var c2 = string.charCodeAt(++n);
      if (c2 & 0xFC00 != 0xDC00) { throw new RangeError("Unmatched lead surrogate at " + (n-1)); }
      c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
      enc = String.fromCharCode(
         (c1 >> 18)       | 240,
        ((c1 >> 12) & 63) | 128,
        ((c1 >> 6)  & 63) | 128,
        ( c1        & 63) | 128
      );
    }
    if (enc !== null) {
      if (end > start) {
        utftext += string.slice(start, end);
      }
      utftext += enc;
      start = end = n + 1;
    }
  }

  if (end > start) {
    utftext += string.slice(start, stringl);
  }

  return utftext;
}
