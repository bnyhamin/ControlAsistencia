<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Empleados.php");
require_once("../includes/clsCA_Asignaciones.php");
require_once("../includes/clsCA_Asignacion_Empleados.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../../Includes/clsEmpleado_Servicio.php");

$o = new ca_empleados();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$a = new ca_asignaciones();
$a->MyUrl = db_host();
$a->MyUser= db_user();
$a->MyPwd = db_pass();
$a->MyDBName= db_name();

$ae = new ca_asignacion_empleados();
$ae->MyUrl = db_host();
$ae->MyUser= db_user();
$ae->MyPwd = db_pass();
$ae->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$es = new Empleado_Servicio();
$es->MyUrl = db_host();
$es->MyUser= db_user();
$es->MyPwd = db_pass();
$es->MyDBName= db_name();

$responsable_codigo=$_GET["codigo"];

$u->empleado_codigo = $responsable_codigo;
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area      			= $u->area_codigo;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; // responsable area
$fecha     			= $u->fecha_actual;
$tipo_area_codigo   = $u->tipo_area_codigo;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Designar Grupo</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
var browser=navigator.appName;
// function sel_supervisores(){
// var codigos = '';
// if(browser =='Microsoft Internet Explorer'){
// 	myInclusive=document.frm.elements('lstsupervisores');
// }
// 	  for (var i=0; i< myInclusive.length; i++){
// 		var valor = myInclusive.options[i].value;
// 		var arr=valor.split('_');
// 		if (codigos == ''){
// 			codigos = arr[0];
// 		}else{
// 			codigos += ',' + arr[0];
// 		}
// 	  }

//    if(codigos =='') {
//     	alert('Seleccione al menos un Responsable!!');
// 	return false;
// 	}else{
// 	 document.frm.hddcodigossup.value=codigos;
// 	 return true;
// 	}
// }

// function Seleccion_multiple(){
// 	var codigos='';
// 	for(i=0; i< document.frm.length; i++ ){
// 		if (frm.item(i).type=='checkbox'){
// 			 if ( frm.item(i).checked ){
// 				if (codigos==''){
// 					codigos= frm.item(i).value;
// 				}else{
// 					codigos+= ',' + frm.item(i).value;
// 				}
// 			}
// 		}
// 	}
// 	if (codigos==''){
// 		alert('Seleccione registros de empleados');
// 		return "";
// 	}
// 	return codigos
// }

// function cambiar_turno(){
// 	var codigos='';
// 	if (document.frm.turno_codigo.value==0){
// 		alert('Seleccione Turno');
// 		return false;
// 	}
// 	codigos=Seleccion_multiple()
// 	if (codigos=='') return false;
// 	if (confirm('Seguro de cambiar turno a personal seleccionado')==false) return false;
// 	document.frm.hddaccion.value='CBO';
// 	document.frm.hddcodigos.value=codigos;
// 	document.frm.submit();
// }
// function cmdOtros(valor){
//   switch(valor){
//     case '1': spanma.style.display='block';
// 	          spanoa.style.display='none';
// 			break;
// 	case '2': spanma.style.display='none';
// 	          spanoa.style.display='block';
// 			  break;
//   }
// }
// function reasignar(){
// 	var codigos='';
// 	codigos=Seleccion_multiple()
// 	if (codigos=='') return false;
// 	//alert(document.frm.responsable_codigo.value);
// 	if(frm.rdo1.checked){
// 		if (frm.responsable_codigo_area.value=='0'){
// 			alert("Seleccione supervisor!!");
// 			frm.responsable_codigo_area.focus();
// 			return false;
// 		}
// 	}

//     if(frm.rdo2.checked){
// 		if (frm.responsable_codigo_otros.value=='0'){
// 			alert("Seleccione supervisor!!");
// 			frm.responsable_codigo_otros.focus();
// 			return false;
// 		}
// 	}

// 	if (confirm('Seguro de reasignar a personal seleccionado')==false) return false;
// 	document.frm.hddaccion.value='RSR';
// 	document.frm.hddcodigos.value=codigos;
// 	document.frm.submit();
// }

// function transferir(){
// 	var codigos='';
// 	if (document.frm.servicio_codigo.value==0){
// 		alert('Seleccione Unidad de Servicio a reasignar');
// 		document.frm.servicio_codigo.focus();
// 		return false;
// 	}
// 	codigos=Seleccion_multiple()
// 	if (codigos=='') return false;
// 	if (confirm('Confirme Asignar Unidad de Servicio a empleados seleccionados')==false) return false;
// 	document.frm.hddaccion.value='AGR';
// 	document.frm.hddcodigos.value=codigos;
// 	document.frm.submit();
// }

// function Quitar(codigo){
// 	if (confirm('Seguro de quitar al empleado del grupo')==false) return false;
// 	document.frm.hddaccion.value='DEL';
// 	document.frm.asignacion_codigo.value=codigo;
// 	document.frm.submit();
// }

</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
    <td class='CA_FormHeaderFont' align=center colspan=2>Supervisor : <?php echo $nombre_usuario?></td>
 </tr>
 <tr>
    <td align="center" colspan='2'><b><?php echo $area_descripcion ?></b></td>
  </tr>
</table>
<table class='FormTable' align="center" border="0" cellPadding="0" cellSpacing="1" style="width:100%">
 <tr align="center" >
    <td class="ColumnTD" >Nro.</td>
    <td class="ColumnTD" >Código</td>
    <td class="ColumnTD" width="250px">Nombre</td>
    <!--<td class="ColumnTD" width="250px">Area</td>-->
	<td class="ColumnTD">Cargo</td>
	<td class="ColumnTD" width="150px">Turno</td>
</tr>
<?php
	$ae->responsable_codigo=$responsable_codigo;
	$cadena=$ae->ver_equipo();
	echo $cadena;
?>
</table>
<br>
<br>
<center><input class=buttons type="button" id="cmdAgrupar" onClick="cerrar()" value="Cerrar"  style="width:80px"></center>
<input type="hidden" id="hddaccion" name="hddaccion">
<input type="hidden" id="hddcodigos" name="hddcodigos">
<input type="hidden" id="asignacion_codigo" name="asignacion_codigo" value="">
</body>
</html>