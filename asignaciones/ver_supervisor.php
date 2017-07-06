<?php header("Expires: 0"); ?>
<?php
session_start();
//echo session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>	 <script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
	<?php
	exit;
}

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");


$supervisor_codigo = $_GET["codigo"];

$ssql="select Empleado_Codigo, Empleado";
$ssql.=" FROM vca_empleado_area ";
$ssql.=" WHERE Estado_Codigo =1 and Empleado_Codigo =" . $supervisor_codigo;
//echo $ssql;
$rs_e = consultar_sql($ssql);
$rs= mssql_fetch_row($rs_e);
$nombre  = $rs[1];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>-Supervisor Asignado</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<body class="PageBODY">
<CENTER class="FormHeaderFont">Supervisor Asignado</CENTER>
<br>
<table class='DataTD' width="80%" border="0" cellspacing="0" cellpadding="0"  align="center">
  <tr>
    <td align="center">&nbsp;<?php echo $nombre ?></td>
  </tr>
  <tr>
    <td class='ColumnTD'>&nbsp;</td>
  </tr>
</table>
<br>
<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<input type="button" id="cmd4" onClick="window.close()" value="Cerrar" class="Button" style="width:80px">
	</td>
  </tr>
</table>
</body>
</html>