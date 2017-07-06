<?php
    header("Expires: 0");
    set_time_limit("36000");
    header( "Content-Type: application/octet-stream");
    $filename='reporte_salida_anticipada'.date('YmdHis').'.xls';

    header( "Content-Disposition: attachment; filename=".$filename."");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Reportes.php");
	
    $mes="";
    $anio="";
    
	$codigo_area="";
    $nombre="";
    
    
    if(isset($_GET["anio"])) $anio=$_GET["anio"];
    if(isset($_GET["mes"])) $mes=$_GET["mes"];
	  if(isset($_GET["area"])) $codigo_area=$_GET["area"];
    

    
    $rep = new ca_reportes();
    $rep->MyUrl = db_host();
    $rep->MyUser= db_user();
    $rep->MyPwd = db_pass();
    $rep->MyDBName= db_name();
    

    
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
<center style="text-align: center; font-weight: bold;font-size: 13px;">REPORTE DE SALIDAS ANTICIPADAS MES: <?php echo $mes;?> A&Ntilde;O <?php echo $anio;?></center>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    

	<!--reporte de turno especial-->
	<table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td align=center ><b>DNI</b></td>
		<td align=center ><b>EMPLEADO</b></td>
		<td align=center ><b>AREA</b></td>
		<td align=center ><b>CARGO</b></td>
		<td align=center ><b>INCIDENCIA</b></td>
		<td align=center ><b>TIEMPO MINUTOS</b></td>
		<td align=center ><b>ASISTENCIA FECHA</b></td>
		<td align=center ><b>ESTADO EMPLEADO</b></td>
	</tr>
<?php
    echo $rep->reporte_salida_anticipada($mes,$anio,$codigo_area);
?>
</table>
    


</form>
</body>
</html>
