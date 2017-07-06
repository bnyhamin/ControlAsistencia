<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Usuarios.php");

$id = $_SESSION["empleado_codigo"];

$usr = new ca_usuarios();
$usr->MyUrl = db_host();
$usr->MyUser= db_user();
$usr->MyPwd = db_pass();
$usr->MyDBName= db_name();
$usr->empleado_codigo = $id;

$r = $usr->Identificar();
$empleado  	= $usr->empleado_nombre;
$fecha     	= $usr->fecha_actual;

?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<title><?php echo tituloGAP() ?>-Carga de Tiempos de conexion</title>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>

<script language="JavaScript" >
function cancelar(){
self.location.href="../menu.php";
}
function descargar_plantilla(){
var settings='width=900,height=700,top=0,left=0,scrollbars=yes,location=no,directories=no,status=no,menubar=yes,toolbar=no,resizable=yes,dependent=yes';
window.open("../reportes/plantillaTX.xls","1",settings);
}

function testp(id) {
	var a=test(id);
	if (a == true) {
	      if (validarCampo('frm','file')!=true) return false;	
	      if (confirm('Confirme Procesar Excel')== false){
          return false;
          }
		divPublicar.style.display="none";
		divTimer.style.display="block";
	}	
	return a;
}
function cargar_archivo(){
	
	var strpagina='<?php echo $url_jrpt_cargaTx ?>?usu=<?php echo $id ?>';
	
	CenterWindow(strpagina, 'Carga',450,200, 'true', 'center');
	
}
</script>

</HEAD>

<body class="PageBODY"  onLoad="return WindowResize(10,20,'center')">
<center class=FormHeaderFont>Carga de Tiempos de Conexión</center>
<div id=divPublicar style="display: block;">
<!--form name='frm' id='frm' enctype="multipart/form-data" action="upload.php" method='post' onsubmit="return testp(this.id)"-->
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' enctype="multipart/form-data" >
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
    <td align="right" width='45%'><b>Usuario&nbsp;:</b></td>
	<td align="left" ><font color=#3366CC><b><?php echo $empleado?></b></font></td>
  </tr>
  <tr>
    <td align='right'><b>&nbsp;Fecha&nbsp;:</b>
	<td align="left" ><input type="text" class="CA_Input" id="txtfecha" name="txtfecha" value='<?php echo $fecha ?>' size="11" readonly></td>
   </tr>
</table>
<br>
<table  align="center" border=0 cellspacing="0" cellpadding="1">
		<tr class='FontAzul' height="30">
			<td colspan="2" color='#3399FF'  ><font color=#005279>
			Pulse click en el icono 
			<img src="../images/excel_ico.GIF" align="center" style='cursor:hand;' alt='plantillaTX.xls' onClick="descargar_plantilla()" /> para mostrar Plantilla de Carga
			</font>
		  </td>
       </tr>
</table>
<table width='550px' align='center' border='0' cellpadding="2">
<tr>
<td align='left'><font color=#005279>
<b>Instrucciones para la carga:</b><br><br></font>
-Debe seleccionar el archivo unicamente pulsando click en el botón "Browse"<br>
-Solo se aceptan archivos de extension "xls" de excel.<br> 
-Se le avisara el resultado de su carga. <br>

</td>
</tr>
</table>
<table class='FormTable' width='55%' align='center' cellspacing='3' cellpadding='0' border='0'>
<tr height="30">
<td class='FieldCaptionTD' width='30%' align=right><b>Archivo:&nbsp;</b></td>
<!--td class='DataTD'>
<input  class='text' id='file' name="file" type="file" alt="0" style='WIDTH: 350px;font-size:11px;font-family: Verdana, Tahoma, Helvetica, Arial'>&nbsp;(*)
</td-->
<td   >
 <input name='cmdSubir' id='cmdSubir' type='submit' value='Seleccione'  class='Button' onclick="cargar_archivo()" style='WIDTH: 90px;'>
 
 </td>
</tr>
<tr>
 <td colspan=2  class='FieldCaptionTD'>&nbsp;
 </td>
</tr>
</table>
<br>
<!--table width='400px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan=2  >
 <input name='cmdGuardar' id='cmdGuardar' type='submit' value='Cargar'  class='Button' style='WIDTH: 90px;'>
 <input name='cmdCancelar' id='cmdCancelar' type='button' value='Cancelar'  class='Button' onclick="cancelar()"style='WIDTH: 90px;'>
 </td>
</tr>
</table-->
<table width='400px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan=2  >
 
 <input name='cmdCancelar' id='cmdCancelar' type='button' value='Salir'  class='Button' onclick="cancelar()"style='WIDTH: 90px;'>
 </td>
</tr>
</table>
</form>
</div>
<div  id=divTimer style="display: none;top:50">
<center ><IMG src="../images/reloj_espera.gif"  align=middle ><br>
<font color=#005279>Espere un momento<br>
por favor...</font></CENTER>
</div>
</body>
</HTML>
<!-- TUMI Solutions S.A.C.-->