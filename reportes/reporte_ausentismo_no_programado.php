<?php
    header("Expires: 0");
    set_time_limit("36000");
    $filename='reporte_ausentismo_no_programado_'.date('YmdHis').'.xls';
    header( "Content-Type: application/octet-stream");
    header( "Content-Disposition: attachment; filename=".$filename."");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Reportes.php");
	// ini_set('display_errors', 'On');
 //    error_reporting(E_ALL);
    $mes="";
    $anio="";
    
	$codigo_area="";
    $nombre="";
    
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
    

    
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Bandeja de Reportes</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<script type="text/javascript" src="app/app.js"></script>
<style type="text/css">
    
.text{
  mso-number-format:"\@";/*force text*/
}
    
</style>
</head>
<body class="PageBODY">
<center style="text-align: center; font-weight: bold;font-size: 16px;">REPORTE DE AUSENTISMOS NO PROGRAMADOS</center>
<center style="text-align: center; font-weight: bold;font-size: 16px;">PERIODO REPORTADO  <?php echo $fechai;?> al <?php echo $fechaf;?></center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    

	<!--reporte de turno especial-->
	<table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
		<td style="border:1px solid" align=center ><b>DNI</b></td>
		<td style="border:1px solid" align=center ><b>EMPLEADO</b></td>
		<td style="border:1px solid" align=center ><b>AREA</b></td>
        <td style="border:1px solid" align=center ><b>CARGO</b></td>
        <td style="border:1px solid" align=center ><b>INCIDENCIA</b></td>
        <td style="border:1px solid" align=center ><b>FECHA</b></td>
		<td style="border:1px solid" align=center ><b>TIEMPO(min.)</b></td>
	</tr>
<?php
    echo $rep->reporte_ausentismo_no_programado($fechai,$fechaf,$codigo_area);
?>
</table>
    


</form>
</body>
</html>
