<?php header("Expires: 0"); ?>
<?php
session_start();

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsGmenu.php"); 
require_once("../includes/clsIncidencias.php"); 
require_once("../includes/clsCA_Empleados.php"); 
require_once("../includes/GestionMetricas/clsGM_cms_dia.php"); 

$em = new ca_empleados();
$em->setMyUrl(db_host());
$em->setMyUser(db_user());
$em->setMyPwd(db_pass());
$em->setMyDBName(db_name());

$er = new ca_empleados();
$er->setMyUrl(db_host());
$er->setMyUser(db_user());
$er->setMyPwd(db_pass());
$er->setMyDBName(db_name());

$oi = new incidencias();
$oi->setMyUrl(db_host());
$oi->setMyUser(db_user());
$oi->setMyPwd(db_pass());
$oi->setMyDBName(db_name());

$gm = new gmenu();
$gm->setMyUrl(db_host());
$gm->setMyUser(db_user());
$gm->setMyPwd(db_pass());
$gm->setMyDBName(db_name());

$me = new gm_cms_dia();
$me->MyUrl = $BD_SERVER_PG;
$me->MyUser= $BD_USER_PG;
$me->MyPwd = $BD_PASSWORD_PG;
$me->MyDBName= $BD_CATALOG_PG;

$mes_codigo="";
$lista_sel = "0";
$fecha = "";
$area_codigo = "0";
$empleado_sel = "0";
$responsable_codigo = "0";
$turno_codigo = "0";
$opcion = "0";
$incidencia_descripcion = "";
$empleado_nombre="";
$responsable_nombre="";
$asistencia_codigo = "0";

if (isset($_GET["lista_sel"])) $lista_sel = $_GET["lista_sel"];
if (isset($_GET["area_codigo"])) $area_codigo = $_GET["area_codigo"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if (isset($_GET["turno_codigo"])) $turno_codigo = $_GET["turno_codigo"];
if (isset($_GET["opcion"])) $opcion = $_GET["opcion"];
if (isset($_GET["incidencia_descripcion"])) $incidencia_descripcion = $_GET["incidencia_descripcion"];
if (isset($_GET["empleado_nombre"])) $empleado_nombre = $_GET["empleado_nombre"];

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<?php
$em->empleado_codigo=$lista_sel;	
$rpta=$em->Query();
$er->empleado_codigo=$responsable_codigo;	
$rpta=$er->Query();
$responsable_nombre=$er->empleado_nombre;
?>
<title>Registro de Asistencias GAP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript" src="../../default.js"></script>
</head>
<body >
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<center class="titulolegend"><b>Empleado:&nbsp;<?php echo $em->empleado_nombre; ?></b></center></b>
<br />
<!--class="FormHeaderFont"<p><img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
<p>
-->
<fieldset>
<legend align="left" class="titulolegend" >GAP</legend>
<br />
<?php
$gm->lista_sel=$lista_sel;
$gm->opcion=$opcion;
$gm->fecha=$fecha;
$gm->area_codigo=$area_codigo;
$gm->responsable_codigo=$responsable_codigo;
$gm->turno_codigo=$turno_codigo; 
$gm->responsable_nombre=$responsable_nombre;
$gm->asistencia_codigo=$asistencia_codigo;
$rpta=$gm->ListarEmpleadoAsistencia();
?>
</fieldset>
<br />
<fieldset>
<legend align="left" class="titulolegend" >PLATAFORMA</legend>
<br />
<?php
  $gm->lista_sel=$lista_sel;
  $gm->fecha=$fecha;
  $rpta=$gm->ListarTiempoConexion();
  $me->empleado_codigo=$lista_sel;
  $me->fecha=$fecha;
  //$me->empleado_codigo=5814;
  //$me->fecha='05/06/2011';
  $resultado=$me->verifica_avaya3();
?>
</fieldset>
<?php if ($datos_mov = $em->obtenerLicenciaLactancia()): ?>
	

<fieldset>
<legend align="left" class="titulolegend" >BENEFICIO HORARIO LACTANCIA
</legend>
<br />
<table class='FormTable' style='width:99%' cellspacing='0' cellpadding='0' border='0' align='center'>
<tr>
<td class='Columna' align='center'>Emp Cod Mov.</b></td>
<td class='Columna' align='center'>Movimiento</b></td>
<td class='Columna' align='center'>Inicio</b></td>
<td class='Columna' align='center'>Fin</b></td>
</tr>
<?php echo $datos_mov ?>

</table>
<?php if($datos_horario_lac = $em->ObtenerHistorialHorarioLactancia()): ?>
<table class='FormTable' style='width:99%' cellspacing='0' cellpadding='0' border='0' align='center'>
<tr>
<td class='Columna' align='center'>Fecha Aplicacion</b></td>
<td class='Columna' align='center'>Horario</b></td>
<td class='Columna' align='center'>Usuario reg.</b></td>
</tr>
<?php echo $datos_horario_lac ?>

</table>
<?php endif; ?>
</fieldset>
<?php endif ?>

<input type="hidden" id="mes_codigo" name="mes_codigo" value="<?php echo $mes_codigo ?>" />
<input type="hidden" id="lista_sel" name="lista_sel" value="<?php echo $lista_sel ?>" />
<input type="hidden" id="opcion" name="opcion" value="<?php echo $opcion ?>" />
<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha ?>" />
<input type="hidden" id="area_codigo" name="area_codigo" value="<?php echo $area_codigo ?>" />
<input type="hidden" id="responsable_codigo" name="responsable_codigo" value="<?php echo $responsable_codigo ?>"/>
<input type="hidden" id="turno_codigo" name="turno_codigo" value="<?php echo $turno_codigo ?>" />
<input type="hidden" id="incidencia_descripcion" name="incidencia_descripcion" value="<?php echo $incidencia_descripcion ?>" />
<input type="hidden" id="empleado_nombre" name="empleado_nombre" value="<?php echo $empleado_nombre ?>" />
<input type="hidden" id="responsable_nombre" name="responsable_nombre" value="<?php echo $responsable_nombre ?>" />
<input type="hidden" id="asistencia_codigo" name="asistencia_codigo" value="<?php echo $asistencia_codigo ?>" />
</form>
</body>
</html>