<?php header("Expires: 0"); 

//require_once("../includes/Seguridad.php");
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
$empleado_id="";
$te_semana="";
$te_anio="";
$tc_codigo="";
$empleado_nombre="";

$cturno_dia1="0";$tturno_dia1="";$fturno_dia1="";
$cturno_dia2="0";$tturno_dia2="";$fturno_dia2="";
$cturno_dia3="0";$tturno_dia3="";$fturno_dia3="";
$cturno_dia4="0";$tturno_dia4="";$fturno_dia4="";
$cturno_dia5="0";$tturno_dia5="";$fturno_dia5="";
$cturno_dia6="0";$tturno_dia6="";$fturno_dia6="";
$cturno_dia7="0";$tturno_dia7="";$fturno_dia7="";
$lturno_dia1="";$dturno_dia1="";$nturno_dia1="";
$lturno_dia2="";$dturno_dia2="";$nturno_dia2="";
$lturno_dia3="";$dturno_dia3="";$nturno_dia3="";
$lturno_dia4="";$dturno_dia4="";$nturno_dia4="";
$lturno_dia5="";$dturno_dia5="";$nturno_dia5="";
$lturno_dia6="";$dturno_dia6="";$nturno_dia6="";
$lturno_dia7="";$dturno_dia7="";$nturno_dia7="";
$total_horas="";$total_minutos="";
$eturno_dia1="0";$sturno_dia1="";$tferiado1="";
$eturno_dia2="0";$sturno_dia2="";$tferiado2="";
$eturno_dia3="0";$sturno_dia3="";$tferiado3="";
$eturno_dia4="0";$sturno_dia4="";$tferiado4="";
$eturno_dia5="0";$sturno_dia5="";$tferiado5="";
$eturno_dia6="0";$sturno_dia6="";$tferiado6="";
$eturno_dia7="0";$sturno_dia7="";$tferiado7="";
$horas_refrigerio="";$minutos_refrigerio="";
$te_fecha_inicio="";$te_fecha_fin="";
$ttotal_horas="";$ttotal_minutos="";

if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];
if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];
if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
if (isset($_GET["te_fecha_inicio"])) $te_fecha_inicio = $_GET["te_fecha_inicio"];
if (isset($_GET["te_fecha_fin"])) $te_fecha_fin = $_GET["te_fecha_fin"];
//if (isset($_GET["empleado_nombre"])) $empleado_nombre = $_GET["empleado_nombre"];

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

if ($te_semana!="" && $te_anio!="" && $empleado_id!=""){
	$e->empleado_codigo = $empleado_id;
	$mensaje = $e->Query_Empleado_Nombres();
	$empleado_nombre = $e->empleado_nombres;
	$e->te_semana = $te_semana;
	$e->te_anio = $te_anio;
	$e->te_fecha_inicio = $te_fecha_inicio;
	$e->te_fecha_fin = $te_fecha_fin;
	$mensaje = $e->Query_Turno_Empleado_Sap();
	$tc_codigo_sap = $e->tc_codigo_sap;
	$cturno_dia1 = $e->turno_Dia1;
	$cturno_dia2 = $e->turno_Dia2;
	$cturno_dia3 = $e->turno_Dia3;
	$cturno_dia4 = $e->turno_Dia4;
	$cturno_dia5 = $e->turno_Dia5;
	$cturno_dia6 = $e->turno_Dia6;
	$cturno_dia7 = $e->turno_Dia7;
	$tc_activo = $e->tc_activo;
	$tturno_dia1 = $e->tturno_Dia1;
	$tturno_dia2 = $e->tturno_Dia2;
	$tturno_dia3 = $e->tturno_Dia3;
	$tturno_dia4 = $e->tturno_Dia4;
	$tturno_dia5 = $e->tturno_Dia5;
	$tturno_dia6 = $e->tturno_Dia6;
	$tturno_dia7 = $e->tturno_Dia7;
	$lturno_dia1 = $e->lturno_Dia1;
	$lturno_dia2 = $e->lturno_Dia2;
	$lturno_dia3 = $e->lturno_Dia3;
	$lturno_dia4 = $e->lturno_Dia4;
	$lturno_dia5 = $e->lturno_Dia5;
	$lturno_dia6 = $e->lturno_Dia6;
	$lturno_dia7 = $e->lturno_Dia7;
	$dturno_dia1 = $e->dturno_Dia1;
	$dturno_dia2 = $e->dturno_Dia2;
	$dturno_dia3 = $e->dturno_Dia3;
	$dturno_dia4 = $e->dturno_Dia4;
	$dturno_dia5 = $e->dturno_Dia5;
	$dturno_dia6 = $e->dturno_Dia6;
	$dturno_dia7 = $e->dturno_Dia7;
	$nturno_dia1 = $e->nturno_Dia1;
	$nturno_dia2 = $e->nturno_Dia2;
	$nturno_dia3 = $e->nturno_Dia3;
	$nturno_dia4 = $e->nturno_Dia4;
	$nturno_dia5 = $e->nturno_Dia5;
	$nturno_dia6 = $e->nturno_Dia6;
	$nturno_dia7 = $e->nturno_Dia7;
	$total_horas = $e->total_horas;
	$total_minutos = $e->total_minutos;
	$eturno_dia1 = $e->eturno_Dia1;
	$eturno_dia2 = $e->eturno_Dia2;
	$eturno_dia3 = $e->eturno_Dia3;
	$eturno_dia4 = $e->eturno_Dia4;
	$eturno_dia5 = $e->eturno_Dia5;
	$eturno_dia6 = $e->eturno_Dia6;
	$eturno_dia7 = $e->eturno_Dia7;
	$sturno_dia1 = $e->sturno_Dia1;
	$sturno_dia2 = $e->sturno_Dia2;
	$sturno_dia3 = $e->sturno_Dia3;
	$sturno_dia4 = $e->sturno_Dia4;
	$sturno_dia5 = $e->sturno_Dia5;
	$sturno_dia6 = $e->sturno_Dia6;
	$sturno_dia7 = $e->sturno_Dia7;
	$horas_refrigerio = $e->horas_refrigerio;
	$minutos_refrigerio = $e->minutos_refrigerio;
	$fturno_dia1 = $e->fturno_Dia1;
	$fturno_dia2 = $e->fturno_Dia2;
	$fturno_dia3 = $e->fturno_Dia3;
	$fturno_dia4 = $e->fturno_Dia4;
	$fturno_dia5 = $e->fturno_Dia5;
	$fturno_dia6 = $e->fturno_Dia6;
	$fturno_dia7 = $e->fturno_Dia7;
	$ttotal_horas = $e->ttotal_horas;
	$ttotal_minutos = $e->ttotal_minutos;
	$tmp_inicio = split("/",$te_fecha_inicio);
	$tmp_fin = split("/",$te_fecha_fin);
	$e->te_fecha_inicio = $tmp_inicio[2] . $tmp_inicio[1] . $tmp_inicio[0];
	$e->te_fecha_fin = $tmp_fin[2] . $tmp_fin[1] . $tmp_fin[0];
	$e->Query_Turno_Feriados();
	$tferiado1 = $e->tferiado1;
	$tferiado2 = $e->tferiado2;
	$tferiado3 = $e->tferiado3;
	$tferiado4 = $e->tferiado4;
	$tferiado5 = $e->tferiado5;
	$tferiado6 = $e->tferiado6;
	$tferiado7 = $e->tferiado7;
	
}
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

function cmdVerHis_onclick(empleado_id, te_semana, te_anio){
    //alert('hi');	

	Ext_Dialogo.genera_Dialog(800,350,'Programacion Empleado',"../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio, 'div_histo',110,60);

}
function cmdVerHis_onclick2(empleado_id, te_semana, te_anio){
    //alert('hi');	

	window.showModalDialog("../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio,'VerHisto','dialogWidth:600px; dialogHeight:320px');

}
</script>

</head>

<body Class='PageBODY'>
<center class=FormHeaderFont>Programaci&oacute;n Semana:&nbsp;<?php echo $te_semana ?><br>Del:&nbsp;<?php echo $te_fecha_inicio?>&nbsp;Al:&nbsp;<?php echo $te_fecha_fin?><br> <?php echo $empleado_nombre ?> </center>
</table>
<div style='display:block' id='div_asignar'>
<table  class='FormTable' width='98%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2">Código Com&nbsp;</td>
	<td class='DataTD' colspan="6">
		<Input  class='Input' type='text' name='tc_codigo_sap' id='tc_codigo_sap' value="<?php echo $tc_codigo_sap?>" maxlength='15' style='width:120px;' readonly >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' width='30%' colspan="2"><b>Dias</td>
	<td class='FieldCaptionTD' align='center' width='20%'><b>Turnos</td>
	<td class='FieldCaptionTD' align='center' width='10%'><b>Cod Tur</td>
	<td class='FieldCaptionTD' align='center' width='10%'><b># Horas</td>
	<td class='FieldCaptionTD' align='center' width='10%'><b>Refrigerio</td>
	<td class='FieldCaptionTD' align='center' width='10%'><b>Descan_1</td>
	<td class='FieldCaptionTD' align='center' width='10%'><b>Descan_2</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado1?> >Lunes&nbsp;<?php echo $fturno_dia1?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia1' <?php echo $tferiado1?> ><?php echo $tturno_dia1?></td>
	<td class='DataTD' align='center' id='sturno_dia1'><?php if($sturno_dia1!="") echo $sturno_dia1?></td>
	<td class='DataTD' align='center' id='nturno_dia1'><?php echo $nturno_dia1?></td>
	<td class='DataTD' align='center' id='lturno_dia1'><?php if($lturno_dia1!="0") echo $lturno_dia1?></td>
	<td class='DataTD' align='center' id='dturno_dia1'><?php if($dturno_dia1!="0") echo $dturno_dia1?></td>
	<td class='DataTD' align='center' id='eturno_dia1'><?php if($eturno_dia1!="0") echo $eturno_dia1?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado2?> >Martes&nbsp;<?php echo $fturno_dia2?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia2' <?php echo $tferiado2?> ><?php echo $tturno_dia2?></td>
	<td class='DataTD' align='center' id='sturno_dia2'><?php if($sturno_dia2!="") echo $sturno_dia2?></td>
	<td class='DataTD' align='center' id='nturno_dia2'><?php echo $nturno_dia2?></td>
	<td class='DataTD' align='center' id='lturno_dia2'><?php if($lturno_dia2!="0") echo $lturno_dia2?></td>
	<td class='DataTD' align='center' id='dturno_dia2'><?php if($dturno_dia2!="0") echo $dturno_dia2?></td>
	<td class='DataTD' align='center' id='eturno_dia2'><?php if($eturno_dia2!="0") echo $eturno_dia2?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado3?> >Miercoles&nbsp;<?php echo $fturno_dia3?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia3' <?php echo $tferiado3?> ><?php echo $tturno_dia3?></td>
	<td class='DataTD' align='center' id='sturno_dia3'><?php if($sturno_dia3!="") echo $sturno_dia3?></td>
	<td class='DataTD' align='center' id='nturno_dia3'><?php echo $nturno_dia3?></td>
	<td class='DataTD' align='center' id='lturno_dia3'><?php if($lturno_dia3!="0") echo $lturno_dia3?></td>
	<td class='DataTD' align='center' id='dturno_dia3'><?php if($dturno_dia3!="0") echo $dturno_dia3?></td>
	<td class='DataTD' align='center' id='eturno_dia3'><?php if($eturno_dia3!="0") echo $eturno_dia3?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado4?> >Jueves&nbsp;<?php echo $fturno_dia4?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia4' <?php echo $tferiado4?> ><?php echo $tturno_dia4?></td>
	<td class='DataTD' align='center' id='sturno_dia4'><?php if($sturno_dia4!="") echo $sturno_dia4?></td>
	<td class='DataTD' align='center' id='nturno_dia4'><?php echo $nturno_dia4?></td>
	<td class='DataTD' align='center' id='lturno_dia4'><?php if($lturno_dia4!="0") echo $lturno_dia4?></td>
	<td class='DataTD' align='center' id='dturno_dia4'><?php if($dturno_dia4!="0") echo $dturno_dia4?></td>
	<td class='DataTD' align='center' id='eturno_dia4'><?php if($eturno_dia4!="0") echo $eturno_dia4?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado5?> >Viernes&nbsp;<?php echo $fturno_dia5?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia5' <?php echo $tferiado5?> ><?php echo $tturno_dia5?></td>
	<td class='DataTD' align='center' id='sturno_dia5'><?php if($sturno_dia5!="") echo $sturno_dia5?></td>
	<td class='DataTD' align='center' id='nturno_dia5'><?php echo $nturno_dia5?></td>
	<td class='DataTD' align='center' id='lturno_dia5'><?php if($lturno_dia5!="0") echo $lturno_dia5?></td>
	<td class='DataTD' align='center' id='dturno_dia5'><?php if($dturno_dia5!="0") echo $dturno_dia5?></td>
	<td class='DataTD' align='center' id='eturno_dia5'><?php if($eturno_dia5!="0") echo $eturno_dia5?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado6?> >Sabado&nbsp;<?php echo $fturno_dia6?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia6' <?php echo $tferiado6?> ><?php echo $tturno_dia6?></td>
	<td class='DataTD' align='center' id='sturno_dia6'><?php if($sturno_dia6!="") echo $sturno_dia6?></td>
	<td class='DataTD' align='center' id='nturno_dia6'><?php echo $nturno_dia6?></td>
	<td class='DataTD' align='center' id='lturno_dia6'><?php if($lturno_dia6!="0") echo $lturno_dia6?></td>
	<td class='DataTD' align='center' id='dturno_dia6'><?php if($dturno_dia6!="0") echo $dturno_dia6?></td>
	<td class='DataTD' align='center' id='eturno_dia6'><?php if($eturno_dia6!="0") echo $eturno_dia6?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2" <?php echo $tferiado7?> >Domingo&nbsp;<?php echo $fturno_dia7?>&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia7' <?php echo $tferiado7?> ><?php echo $tturno_dia7?></td>
	<td class='DataTD' align='center' id='sturno_dia7'><?php if($sturno_dia7!="") echo $sturno_dia7?></td>
	<td class='DataTD' align='center' id='nturno_dia7'><?php echo $nturno_dia7?></td>
	<td class='DataTD' align='center' id='lturno_dia7'><?php if($lturno_dia7!="0") echo $lturno_dia7?></td>
	<td class='DataTD' align='center' id='dturno_dia7'><?php if($dturno_dia7!="0") echo $dturno_dia7?></td>
	<td class='DataTD' align='center' id='eturno_dia7'><?php if($eturno_dia7!="0") echo $eturno_dia7?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="4">Total Horas Semanales &nbsp;</td>
	<td class='DataTD' align='center' id='ttotal_horas'><?php echo $ttotal_horas?>:<?php echo $ttotal_minutos?></td>
	<td class='DataTD' align='center' id='horas_refrigerio'><?php echo $horas_refrigerio?>:<?php echo $minutos_refrigerio?></td>
	<td class='FieldCaptionTD' align='right' colspan="1">Efectivas</td>
	<td class='DataTD' align='center' id='total_horas'><?php echo $total_horas?>:<?php echo $total_minutos?></td>
</tr>
<tr>
	<td colspan=8  class='FieldCaptionTD'>&nbsp;</td>
</tr>
<tr align='center'>
	<td colspan=8  class='FieldCaptionTD'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'  class='Button' style='width:80px' onclick="Finalizar();"/>
        <?php if (isset($_GET['to'])): ?>
        <input name='cmdHistorial' id='cmdHistorial' type='button' value='Historial' onclick='return cmdVerHis_onclick2(<?php echo $empleado_id; ?>,<?php echo $te_semana; ?>,<?php echo $te_anio; ?>)' title='Historial de cambios y suplencias' class='Button' style='width:80px'/>
        <?php else: ?>	
        <input name='cmdHistorial' id='cmdHistorial' type='button' value='Historial' onclick='return cmdVerHis_onclick(<?php echo $empleado_id; ?>,<?php echo $te_semana; ?>,<?php echo $te_anio; ?>)' title='Historial de cambios y suplencias' class='Button' style='width:80px'/>
		<?php endif; ?>

	</td>
</tr>
</table>
</div>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<div id="div_histo"></div>
<div style='position:absolute;left:100px;top:500px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>