<?php
header("Expires: 0"); ?>
<?php
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>
    <script language="JavaScript">
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
$cadena="";
$e = new ca_turnos_empleado();
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());
$empleado_codigo_registro=$_SESSION["empleado_codigo"];
if (isset($_GET["fecha_inicio"])) $fecha_inicio = $_GET["fecha_inicio"];
if (isset($_GET["fecha_fin"])) $fecha_fin = $_GET["fecha_fin"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Reporte Semanal</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript">
var mensaje='';
function exportarExcel(tableID) {
	var i;
	var j;
	var mycell;
	var objXL = new ActiveXObject("Excel.Application");
	var objWB = objXL.Workbooks.Add();
	var objWS = objWB.ActiveSheet;
	for (i=0; i < document.getElementById(tableID).rows.length; i++)
	{
	    for (j=0; j < document.getElementById(tableID).rows(i).cells.length; j++)
	    {
	        mycell = document.getElementById(tableID).rows(i).cells(j)
	        objWS.Cells(i+1,j+1).Value = mycell.innerText;
	    }
	}
	objWS.Range("A1", "Z1").EntireColumn.AutoFit();
	objXL.Visible = true;
}
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action='<?php echo $PHP_SELF ?>' method='post' onSubmit='javascript:return ok();'>
<CENTER class="FormHeaderFont">Programación Semana&nbsp;<?php if(isset($te_semana)) echo $te_semana ?><br>Del:&nbsp;<?php if(isset($te_fecha_inicio)) echo $te_fecha_inicio ?>&nbsp;Al:&nbsp;<?php if(isset($te_fecha_fin)) echo $te_fecha_fin ?></CENTER>
<br>
<?php
		$e->empleado_codigo_registro = $empleado_codigo_registro;
		$e->te_fecha_inicio = $fecha_inicio;
		$e->te_fecha_fin = $fecha_fin;
		$cadena=$e->Reporte_Programacion2();
		echo $cadena;
	?>
<?php
if (file_exists("datos.xls")){ 
    unlink("datos.xls");
}
$ar=fopen("datos.xls","a") or die("Problemas en la creacion");
fputs($ar,$cadena);
fputs($ar,"\n");
fclose($ar);
?>
<!--</table>-->
<br>
</form>
<script type="text/javascript">
	window.open("datos.xls",18,"width=950px, height=600px, toolbar=yes, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
	//self.location.href = "datos.xls";
	window.close();
</script>
</body>
</html>