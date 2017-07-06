<?php
    header("Expires: 0");
    set_time_limit("36000");
    header( "Content-Type: application/octet-stream");
    header( "Content-Disposition: attachment; filename=jornadas_pasivas.xls");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/mantenimiento.php");
	require_once("../includes/clsCA_Eventos.php");
    require_once("../includes/clsCA_Reportes.php");
    require_once("../../Includes/MyCombo.php");
	
    $fechai="";
    $fechaf="";
    $reporte=0;
    $nombre="";
	$area_codigo = 0;
    
    if(isset($_GET["reporte"])) $reporte=$_GET["reporte"];
    if(isset($_GET["fechai"])) $fechai=$_GET["fechai"];
    if(isset($_GET["fechaf"])) $fechaf=$_GET["fechaf"];
	if(isset($_GET["area"])) $area_codigo=$_GET["area"]*1;
	
    
    $fi = explode("-", $fechai);
    $fechai=$fi[2]."/".$fi[1]."/".$fi[0];
    
    $ff = explode("-", $fechaf);
    $fechaf=$ff[2]."/".$ff[1]."/".$ff[0];
    
    $ase = new ca_reportes();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();
    
    if($reporte==1) $nombre="CONSOLIDADO DE EVENTOS VALIDABLES DEL ";
    if($reporte==2) $nombre="REPORTE DETALLADO POR INCIDENCIA DEL ";
    if($reporte==3) $nombre="REPORTE CONSOLIDADO POR INCIDENCIA DEL ";
    if($reporte==4) $nombre="REPORTE CONSOLIDADO POR VALIDADOR DEL ";
	if($reporte==5) $nombre="REPORTE DE EVENTOS EN PROCESO DE VALIDACION ";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Bandeja de Reportes</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<script type="text/javascript" src="app/app.js"></script>

</head>
<body class="PageBODY">
    <?php
		if($reporte==5){
	?>
			<center style="text-align: center; font-weight: bold;font-size: 13px;"><?php echo $nombre;?></center>
	<?php
		}else{
	?>
			<center style="text-align: center; font-weight: bold;font-size: 13px;"><?php echo $nombre;?> <?php echo $fechai;?> al <?php echo $fechaf;?></center>
	<?php
		}
	?>
   
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    
    <?php
        if($reporte==1){
    ?>
    
<table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
  <tr>
        <td align=center width="200"><b>NOMBRE INCIDENCIA</b></td>
        <td align=center width="200"><b>VALIDADOR ENCARGADO</b></td>
        <td align=center width="140"><b>APROB. MANUALES</b></td>
        <td align=center width="85"><b>RECHAZOS</b></td>
        <td align=center width="100"><b>TIPO</b></td>
  </tr>
<?php
    echo $ase->resumen_incidencias_validables($fechai,$fechaf);
?>
</table>
    
    <?php
        }
        if($reporte==2){        
    ?>
    
    <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td align=center width="80"><b>FECHA INCIDENCIA</b></td>
        <td align=center width="80"><b>FECHA INGRESO</b></td>
        <td align=center width="90"><b>RUT EJECUTIVO</b></td>
        <td align=center width="160"><b>NOMBRE EJECUTIVO</b></td>
        <td align=center width="160"><b>SUPERVISOR</b></td>
        <td align=center width="170"><b>JEFE</b></td>
        <td align=center width="100"><b>TIPO INCIDENCIA</b></td>
        <td align=center width="160"><b>VALIDADOR</b></td>
        <td align=center width="100"><b>FECHA VALIDACION O RECHAZO</b></td>
        <td align=center width="85"><b>CANTIDAD DE HORAS INGRESADAS</b></td>
        <td align=center width="85"><b>CANTIDAD DE HORAS APROBADAS</b></td>
        <td align=center width="90"><b>CANTIDAD DE HORAS RECHAZADAS</b></td>
        <td align=center width="90"><b>COMENTARIO SUPERVISOR</b></td>
        <td align=center width="90"><b>COMENTARIO APROBACION</b></td>
        <td align=center width="90"><b>ESTADO</b></td>
  </tr>
    <?php
         echo $ase->resumen_detalladoxincidencia($fechai,$fechaf);
    ?>
  </table>
    <?php
        }
        if($reporte==3){
    ?>
    
    <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td align=center width="250"><b>TIPO INCIDENCIA</b></td>
        <td align=center width="85"><b>CANTIDAD RECHAZOS</b></td>
        <td align=center width="85"><b>CANTIDAD APROBADOS MANUAL</b></td>
        <td align=center width="100"><b>CANTIDAD APROBADOS AUTOMATICOS</b></td>
        <td align=center width="95"><b>CANTIDAD HORAS RECHAZADAS</b></td>
        <td align=center width="85"><b>CANTIDAD HORAS APROBADAS MANUAL</b></td>
        <td align=center width="105"><b>CANTIDAD HORAS APROBADAS AUTOMATICAS</b></td>
        <td align=center width="90"><b>% HORAS RECHAZADAS</b></td>
        <td align=center width="90"><b>% HORAS APROBADAS</b></td>
  </tr>
  
    <?php
         echo $ase->resumen_consolidadoxincidencia($fechai,$fechaf);
    ?>
    </table>
    <?php
        }
    ?>
    <?php  
        if($reporte==4){
    ?>
    <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td align=center width="250"><b>TIPO INCIDENCIA</b></td>
        <td align=center width="80"><b>RUT VALIDADOR</b></td>
        <td align=center width="300"><b>NOMBRE VALIDADOR</b></td>
        <td align=center width="85"><b>CANTIDAD RECHAZOS</b></td>
        <td align=center width="85"><b>CANTIDAD APROBADOS MANUAL</b></td>
        <td align=center width="100"><b>CANTIDAD APROBADOS AUTOMATICOS</b></td>
        <td align=center width="95"><b>CANTIDAD HORAS RECHAZADAS</b></td>
        <td align=center width="85"><b>CANTIDAD HORAS APROBADAS MANUAL</b></td>
        <td align=center width="105"><b>CANTIDAD HORAS APROBADAS AUTOMATICAS</b></td>
        <td align=center width="90"><b>% HORAS RECHAZADAS</b></td>
        <td align=center width="90"><b>% HORAS APROBADAS</b></td>
  </tr>
    <?php
         echo $ase->resumen_consolidadoxvalidador($fechai,$fechaf);
    ?>
    </table>
    <?php
        }
    ?>
	
	<?php
        if($reporte==5){
    ?>
    <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
		<td align=center width="160"><b>SUPERVISOR</b></td>
        <td align=center width="160"><b>NOMBRE EJECUTIVO</b></td>
		<td align=center width="80"><b>FECHA ASISTENCIA</b></td>
		<td align=center width="160"><b>AREA</b></td>
        <td align=center width="100"><b>FECHA DE REGISTRO</b></td>
        <td align=center width="100"><b>INCIDENCIA</b></td>
		<td align=center width="100"><b>NIVEL APROBACION</b></td>
  </tr>
    <?php
         echo $ase->reporte_eventos_proceso($area_codigo);
    ?>
    </table>
    <?php
        }
    ?>
	
    <input type="hidden" name="hddaccion" id="hddaccion" value=""/>
</form>
</body>
</html>
