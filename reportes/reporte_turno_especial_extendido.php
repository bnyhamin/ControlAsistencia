<?php
    header("Expires: 0");
    set_time_limit("36000");
    header( "Content-Type: application/octet-stream");
    header( "Content-Disposition: attachment; filename=consolidado_reporte.xls");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Reportes.php");
	
    $fechai="";
    $fechaf="";
    $reporte=0;
	$codigo_area="";
    $nombre="";
    
    if(isset($_GET["reporte"])) $reporte=$_GET["reporte"];
    if(isset($_GET["fechai"])) $fechai=$_GET["fechai"];
    if(isset($_GET["fechaf"])) $fechaf=$_GET["fechaf"];
	if(isset($_GET["area"])) $codigo_area=$_GET["area"];
    
    $fi = explode("-", $fechai);
    $fechai=$fi[2]."/".$fi[1]."/".$fi[0];
    
    $ff = explode("-", $fechaf);
    $fechaf=$ff[2]."/".$ff[1]."/".$ff[0];
    
    $rep = new ca_reportes();
    $rep->MyUrl = db_host();
    $rep->MyUser= db_user();
    $rep->MyPwd = db_pass();
    $rep->MyDBName= db_name();
    
    if($reporte==1) $nombre="REPORTE TURNO ESPECIAL DEL ";
    if($reporte==2) $nombre="REPORTE TURNO EXTENDIDO DEL ";
    
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
    <center style="text-align: center; font-weight: bold;font-size: 13px;"><?php echo $nombre;?> <?php echo $fechai;?> al <?php echo $fechaf;?></center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    
<?php
if($reporte==1){
?>
	<!--reporte de turno especial-->
	<table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td align=center ><b>EMPLEADO</b></td>
		<td align=center ><b>DNI</b></td>
		<td align=center ><b>AREA</b></td>
		<td align=center ><b>CARGO</b></td>
		<td align=center ><b>FECHA INICIO PROGRAMADO</b></td>
		<td align=center ><b>FECHA FIN PROGRAMADO</b></td>
		<td align=center ><b>FECHA INICIO EJECUTADO</b></td>
		<td align=center ><b>FECHA FIN EJECUTADO</b></td>
		<td align=center ><b>HORAS PROGRAMADAS</b></td>
		<td align=center ><b>HORAS EJECUTADAS</b></td>
		<td align=center ><b>IP ENTRADA</b></td>
		<td align=center ><b>IP SALIDA</b></td>
	</tr>
<?php
    echo $rep->reporte_Turno_Especial($fechai,$fechaf,$codigo_area);
?>
</table>
    
<?php
}
if($reporte==2){        
?>
	<table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td align=center ><b>EMPLEADO</b></td>
		<td align=center ><b>DNI</b></td>
		<td align=center ><b>AREA</b></td>
		<td align=center ><b>CARGO</b></td>
		<td align=center ><b>TURNO</b></td>
		<td align=center ><b>FECHA ASISTENCIA</b></td>
		<td align=center ><b>FECHA REGISTRO</b></td>
		<td align=center ><b>TIEMPO EXTENSION</b></td>
		<td align=center ><b>TIPO EXTENSION</b></td>
	</tr>
    <?php
         echo $rep->reporte_Turno_Extendio($fechai,$fechaf,$codigo_area);
    ?>
  </table>
<?php
}
?>
</form>
</body>
</html>
