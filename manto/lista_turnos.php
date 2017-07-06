<?php header("Expires: 0"); ?>
<?php
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?><script language="JavaScript">
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
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Lista de Turnos</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
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
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' onSubmit='javascript:return ok();'>
<CENTER class="FormHeaderFont">Lista de Turnos</CENTER>
<br>
<img style='CURSOR: hand' src='../../images/contratos/excel_ico.gif' onclick='javascript:return exportarExcel("listado");' WIDTH='25' HEIGHT='25' alt='Imprimir' title='Exportar Excel'>
	<?php
		$cadena=$e->Lista_Turnos();
		echo $cadena;
	?>
<br>
</form>
</body>
</html>
