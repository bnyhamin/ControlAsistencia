<?php header("Expires: 0"); ?>
<?php
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>	 <script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
	<?php
	exit;
}

require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php"); 

$tc_codigo_sap="";
$tipo_area_codigo="1";
$turno_hora_inicio="-1";
$turno_minuto_inicio="-1";
$turno_horas="0";
$turno_minutos="0";
$tc_activo="0";
$empleado_codigo="";
$te_semana="";
$te_anio="";
$tc_codigo="";
$empleado_nombre="";

$cturno_dia1="0";$tturno_dia1="";
$cturno_dia2="0";$tturno_dia2="";
$cturno_dia3="0";$tturno_dia3="";
$cturno_dia4="0";$tturno_dia4="";
$cturno_dia5="0";$tturno_dia5="";
$cturno_dia6="0";$tturno_dia6="";
$cturno_dia7="0";$tturno_dia7="";
$lturno_dia1="";$dturno_dia1="";$nturno_dia1="";
$lturno_dia2="";$dturno_dia2="";$nturno_dia2="";
$lturno_dia3="";$dturno_dia3="";$nturno_dia3="";
$lturno_dia4="";$dturno_dia4="";$nturno_dia4="";
$lturno_dia5="";$dturno_dia5="";$nturno_dia5="";
$lturno_dia6="";$dturno_dia6="";$nturno_dia6="";
$lturno_dia7="";$dturno_dia7="";$nturno_dia7="";
$total_horas="";$total_minutos="";

if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];
if (isset($_GET["empleado_codigo"])) $empleado_codigo = $_GET["empleado_codigo"];
if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
if (isset($_GET["empleado_nombre"])) $empleado_nombre = $_GET["empleado_nombre"];
if (isset($_GET["tturno_dia1"])) $tturno_dia1 = $_GET["tturno_dia1"];
if (isset($_GET["tturno_dia2"])) $tturno_dia2 = $_GET["tturno_dia2"];
if (isset($_GET["tturno_dia3"])) $tturno_dia3 = $_GET["tturno_dia3"];
if (isset($_GET["tturno_dia4"])) $tturno_dia4 = $_GET["tturno_dia4"];
if (isset($_GET["tturno_dia5"])) $tturno_dia5 = $_GET["tturno_dia5"];
if (isset($_GET["tturno_dia6"])) $tturno_dia6 = $_GET["tturno_dia6"];
if (isset($_GET["tturno_dia7"])) $tturno_dia7 = $_GET["tturno_dia7"];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Consulta de Turno</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>

<script language='javascript'>

function Finalizar(){
    window.close();
}

</script>

</head>

<body Class='PageBODY'>
<center class=FormHeaderFont>Combinacion de Turnos</center>
<form name="frm" id="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
</table>
<table  class='FormTable' width='98%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='center' width='35%' colspan="2"><b>Dias&nbsp;</td>
	<td class='FieldCaptionTD' align='center' width='70%'><b>Turnos</td>
</tr>
<tr align="center">
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Lunes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia1'><?php echo $tturno_dia1?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Martes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia2'><?php echo $tturno_dia2?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Miercoles&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia3'><?php echo $tturno_dia3?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Jueves&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia4'><?php echo $tturno_dia4?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Viernes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia5'><?php echo $tturno_dia5?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Sabado&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia6'><?php echo $tturno_dia6?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2"><br>Domingo&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia7'><?php echo $tturno_dia7?></td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>
</body>
</html>