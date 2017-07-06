<?php
    header("Expires: 0");
    set_time_limit("36000");
    header( "Content-Type: application/octet-stream");
    header( "Content-Disposition: attachment; filename=Reporte_Saldo_actual".date('Y-m-d').".xls");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php");
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Areas.php");
    require_once("../includes/clsCA_Reportes.php");

    $fechai="";
    $fechaf="";
    $reporte=0;
    $nombre="";
	$area_codigo = 0;
    
	if(isset($_GET["area"])) $area_codigo=$_GET["area"]*1;
    
    $ase = new ca_reportes();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();

    $a = new areas();
    $a->MyUrl = db_host();
    $a->MyUser= db_user();
    $a->MyPwd = db_pass();
    $a->MyDBName= db_name();
    $a->area_codigo = $area_codigo;
    $a->Query();

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
    <center style="text-align: center; font-weight: bold;font-size: 13px;">Reporte de Saldo actual de Horas adicionales</center><br/>
    <center style="text-align: center; font-weight: bold;font-size: 13px;">Area: <?php echo $a->area_descripcion ?></center>

<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
    

    <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
    <tr>
		<td align=center width="60"><b>DNI</b></td>
        <td width="250"><b>EMPLEADO</b></td>
		<td width="250"><b>AREA</b></td>
		<td width="250"><b>CARGO</b></td>
        <td align=center width="100"><b>HORAS ADICIONALES</b></td>
        <td align=center width="100"><b>HORAS COMPENSADAS</b></td>
		<td align=center width="100"><b>SALDO HORAS</b></td>
  </tr>
    <?php
         echo $ase->reporte_saldo_actual($area_codigo);
    ?>
    </table>

	
    <input type="hidden" name="hddaccion" id="hddaccion" value=""/>
</form>
</body>
</html>
