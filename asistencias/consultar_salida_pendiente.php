<?php header("Expires: 0"); ?>
<?php
session_start();
//echo $_SERVER['REMOTE_ADDR'];
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Asistencias.php");

$id = $_SESSION["empleado_codigo"];
$sizewidth=2;
$sizeheight=2;
$sizex=500;
$sizey=300;
$fecha='';

if (isset($_GET["r"])) $r = $_GET["r"];
if (isset($_GET["cod"])) $asistencia_codigo = $_GET["cod"];
if (isset($_GET["tip"])) $tip = $_GET["tip"];

$o = new ca_asistencia();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$o->empleado_codigo= $id;
$o->asistencia_codigo= $asistencia_codigo;

$rpta= $o->obtener();
$fecha= $o->fecha_entrada;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>Consulta Salida</title>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>

</head>
<script language='javascript'>
var SmallSizeWidth  = <?php echo $sizewidth ?>;
var SmallSizeHeight = <?php echo $sizeheight ?>;
var SmallSizeX      = <?php echo $sizex ?>;
var SmallSizeY      = <?php echo $sizey ?>;
var r=<?php echo $r ?>;
function ok(){
var cod="<?php echo $asistencia_codigo?>";
if(r==1){
CenterWindow("registrar_asistencia.php?cod=" + cod + "&tip=0&t=0","registro",500,400,"yes","center");
window.close();
}
else{
   //var valor = window.showModalDialog("registrar_asistencia.php?t=0", "Registro","dialogWidth:400px; dialogHeight:350px");
	window.resizeTo(window.screen.availWidth/SmallSizeWidth, window.screen.availHeight/SmallSizeHeight);
	window.moveTo(SmallSizeX , SmallSizeY);
	document.location.href = "registrar_asistencia.php?cod=" + cod + "&tip=0&t=0";
 }
}

function no(){
if(r==1){
CenterWindow("registrar_asistencia.php?cod=0&tip=1&t=1","registro",500,400,"yes","center");
window.close();
}
else{
//var valor = window.showModalDialog("registrar_asistencia.php?t=1", "Registro","dialogWidth:400px; dialogHeight:350px");
  window.resizeTo(window.screen.availWidth/SmallSizeWidth, window.screen.availHeight/SmallSizeHeight);
  window.moveTo(SmallSizeX , SmallSizeY);
  document.location.href = "registrar_asistencia.php?cod=0&tip=1&t=1";
  }
}
</script>

<BODY class="PageBODY">
<form id="frm" name="frm" action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
<br>
<center class="FormHeaderFont">Tiene salida pendiente del dia: <?php echo $fecha ?>.<br>Pulse 'Si' si desea cerrar la salida pendiente<br>Pulse 'No' si desea registrar la entrada de hoy.</center>
<table border="0" align="center" >
    <br>
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Si" style="width:80px" onClick="ok()"/>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="No" style="width:80px" onClick="no()"/>
		</td>
	</tr>
</table>
<input type='hidden' id='hddarea' name='hddarea' value="<?php if(isset($area)) echo $area ?>"/>
<iframe src="" id="ifrm" name="ifrm" width="0px" height="0px"></iframe>
</form>
</body>
</html>