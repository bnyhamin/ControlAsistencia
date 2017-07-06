<?php

function fontTitulos(){
	$cadena = "#E1F0FF";
 	return $cadena;
}

function bgTitulos(){
	$cadena = "#628DB9";
 	return $cadena;
}

function Font()
{
	$cadena = " class=Cabecera ";
 	return $cadena;
}

function CabeceraGrilla()
{
	$cadena = "color=#000000;";
 	return $cadena;
}

function FormaTabla()
{
	$cadena = "class=TABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ";
	//$cadena = "class=table style='width:100%'  border='1' ";
 	return $cadena;
}
function FormaCabecera()
{
	$cadena = " Class=Cabecera ";
 	return $cadena;
}
function FormaTCabecera()
{
	$cadena = " Class=TABLE ";
 	return $cadena;
}
function FormaItems()
{
	$cadena = "class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F5F5E2'";
	//$cadena = "class=ColumnTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#6da4c3'";
 	return $cadena;
}

function chequeado($valor){
	if ($valor == 0 || $valor==""){
		return " ";
	}
	if ($valor == 1 ){
		return " checked ";
	}
	return;
}

function checkOpcion($opcion, $valor){
$cadena = "";
if ($opcion=="") return $cadena;

if ($opcion == $valor ){
	$cadena = " checked ";
}

return $cadena;
}

//inicio funcion accesosPersonal
function accesosPersonal($acceso, $Codigo, $Empleado){
$cadena="";
	if ($acceso == 206 || $acceso == 237 ){
		//206=administrador, 237=Administrador Especial
		$cadena="";
	}
	if ($acceso == 234){
		//234=Resp. Area
		//$cadena=" (empleado_Area.Area_Codigo = $Codigo ) and ";
		$cadena=" (Areas.empleado_responsable = $Empleado ) and ";
		
		$cadena=" (Areas.empleado_responsable in ( ";
		$cadena.=" select empleado_responsable ";
		$cadena.=" from areas ";
		$cadena.=" where empleado_responsable= $Empleado and Area_Activo=1 ";
		$cadena.=" union ";
		$cadena.=" select empleado_responsable ";
		$cadena.=" from areas ";
		$cadena.=" where Area_Jefe in ( ";
		$cadena.=" select Area_Codigo ";
		$cadena.=" from areas ";
		$cadena.=" where empleado_responsable= $Empleado and Area_Activo=1 ";
		$cadena.=" ) ";
		$cadena.=" and Area_Activo=1 ";
		$cadena.=" union ";
		$cadena.=" select empleado_responsable ";
		$cadena.=" from areas ";
		$cadena.=" where area_jefe in ( ";
		$cadena.=" select Area_Codigo ";
		$cadena.=" from areas ";
		$cadena.=" where Area_Jefe in ( ";
		$cadena.=" select Area_Codigo ";
		$cadena.=" from areas ";
		$cadena.=" where empleado_responsable= $Empleado and Area_Activo=1 ";
		$cadena.=" ) ";
		$cadena.=" and Area_Activo=1 ";
		$cadena.=" ) ";
		$cadena.=" and Area_Activo=1 ";
		$cadena.=" ) ) and ";

	}
	if ($acceso == 207){
		//Consulta
		$cadena=" (empleados.empleado_codigo = $Empleado ) and ";
	}
return $cadena;
}
//final funcion accesosPersonal

function NombreMes($mes){
$nombre="";
	switch ($mes){
		case 1:
			$nombre = "Enero";
			break;
		case 2:
			$nombre = "Febrero";
			break;
		case 3:
			$nombre = "Marzo";
			break;
		case 4:
			$nombre = "Abril";
			break;
		case 5:
			$nombre = "Mayo";
			break;
		case 6:
			$nombre = "Junio";
			break;
		case 7:
			$nombre = "Julio";
			break;
		case 8:
			$nombre = "Agosto";
			break;
		case 9:
			$nombre = "Setiembre";
			break;
		case 10:
			$nombre = "Octubre";
			break;
		case 11:
			$nombre = "Noviembre";
			break;
		case 12:
			$nombre = "Diciembre";
			break;
	}
	return $nombre;
}

function concatenar($cadena, $subcadena, $signo){
$cad=$cadena;
 if ($cad == ""){
 	$cad = $subcadena;
 }else{
 	if ($subcadena=="") return $cad;
 	$cad = $cad . $signo . $subcadena;
 }
 return $cad;
}

function enviaEmail($Email,$Asunto,$Cuerpo){
if ($Email =='') return;

	$e = new COM("AtentoEmail.NewEmail");
    $e->para = $Email;
    $e->asunto = $Asunto;
    $e->mensaje = $Cuerpo;
    $e->enviar();
    $e = null;
}

function Fotografias(){
	$cadena= URLZeusFoto();
	return $cadena;
}

function UsuarioNick($usuario_login){
$cadena ="";

$cadena = substr( $usuario_login, strpos($usuario_login, chr(92))+1, strlen($usuario_login) - strpos($usuario_login, chr(92)) );

return $cadena;

}

function Ebitda(){
	return "27";
}

function IngresoMinimo(){
	return "2000";
}

function atento_email(){
	return "rrodriguez@tumisolutions.com"; //-- configurar con iatento@tp.com.pe
}



//----* Funciones Modulo Control Asistencia *---//
function RetornoMenu($inicio, $menu){
 //* -- Utilizado para navegar entre el menu principal, menu del modulo y las paginas -- */
 //* -- inicio: ruta del menu principal -- */
 //* -- menu: ruta del menu del modulo -- */
  $texto="";
  $texto.= "<table width='140' border=0 cellspacing=0 cellpadding=0 align='right'>";
  $texto.= "  <tr>";
  $texto.= "    <td><a class='NavigatorLink' href='" . $inicio . "' title='Menu Principal' target='_self'>[ Principal ]</a></td>";
  $texto.= "    <td><a class='NavigatorLink' href='" . $menu . "' target='_self'>[ Anterior ]</a></td>";
  $texto.= "  </tr>";
  $texto.= "</table>";
  return $texto;
}

function Menu($inicio){
 //* -- Utilizado para navegar entre el menu principal, menu del modulo y las paginas -- */
 //* -- inicio: ruta del menu principal -- */
  $texto="";
  $texto.= "<table width='140' border=0 cellspacing=0 cellpadding=0 align='right'>";
  $texto.= "  <tr>";
  $texto.= "    <td><a class='NavigatorLink' href='" . $inicio . "' title='Menu Principal' target='_self'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>[ Principal ]</font></a></td>";
  $texto.= "  </tr>";
  $texto.= "</table>";
  return $texto;
}

function lectura($alinear){
 //* -- Utilizado para alinear los Inputs de Codigos. de solo lectura con color gris claro -- */
 //* -- alinear="D" : alinea el texto a la derecha, otro valor sera a la izquierda -- */
  $bgcolor= "#F3F3F3";
  $texto= " readonly style='background-color:" . $bgcolor . "' ";
  if ($alinear=="D"){
    $texto=" readonly style='background-color:" . $bgcolor . "; TEXT-ALIGN:RIGHT;' ";
  }
  if ($alinear=="C"){
    $texto=" readonly style='background-color:" . $bgcolor . "; TEXT-ALIGN:CENTER;' ";
  }
  if ($alinear=="L"){
    $texto=" readonly style='background-color:" . $bgcolor . "; TEXT-ALIGN:LEFT;' ";
  }
  return $texto;
}

function scrollGrid(){
  $texto ="";
  $bgcolor= "#d6e7f3";
  $texto = " onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='" . $bgcolor . "'";
  return $texto;
}

function getMensaje($valor){
  return "<center><font color=red>" . $valor . "</font></center>";
}

function DiaEspanol()
{
  $dia=date('d');$mes=date('n');$ano=date('Y');
  $meses=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
  $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
  $hoy = $dias[date('w')].' ' .$dia.' de '.$meses[$mes].' de '.$ano;
  return $hoy;
}

function FechaEspanol($sFecha)
{
  $esFecha='';
  $arrFecha=split('/',$sFecha);
  $esMes= NombreMes($arrFecha[1]*1);
  $esFecha= $arrFecha[0] . ' de ' . $esMes . ' de ' . $arrFecha[2];
  return $esFecha;
}

function letraRenovacion($valor){
	$letras="";
	
	//centenas
	if($valor>=100){
		$letras="Numero mayor a cien";
		$valor=0;
	}
	
	//Decenas
	if(($valor<100) and ($valor>9)){
		$tempo=decenas(Intval(($valor/10))*10);
		$letras.=$tempo;
		$valor=$valor-(Intval(($valor/10))*10);
		if($valor>0){
			//$letras.=' ';
			$tempo=decenas(Intval($valor));
			$letras.=$tempo;
			$valor=$valor-$valor;
		}
	}
	
	//Unidades
	if(($valor<10) And ($valor>0)){
		$tempo=unidades(Intval($valor));
		$letras.=$tempo;
		//$valor=$valor-intval($valor);
	}
	
	return $letras;
}


function unidades($unidad){
	$valores = array('','Primera','Segunda','Tercera','Cuarta','Quinta','Sexta','Septima',
	'Octava','Novena',10=>'Decima ',20=>'Vigesima ',30=>'Trigesima ',40=>'Cuadragesima ',50=>'Quincuagesima ',
	60=>'Sexagesima ',70=>'Septuagesima ',80=>'Octogesima ',90=>'Nonagesima ');
	return $valores[$unidad];
}

function decenas($decena){
	$valores = array('','primera','segunda','tercera','cuarta','quinta','sexta','septimao',
	'octava','novena',10=>'Decima ',20=>'Vigesima ',30=>'Trigesima ',40=>'Cuadragesima ',50=>'Quincuagesima ',
	60=>'Sexagesima ',70=>'Septuagesima ',80=>'Octogesima ',90=>'Nonagesima ');

	return $valores[$decena];
}

//---* fin funciones Control Asistencia *--//



?>
