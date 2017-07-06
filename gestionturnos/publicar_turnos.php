<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php");
$rpta="";
$cadena="";
$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();
$te_semana="";
$te_anio="";
$te_fecha_inicio="";
$te_fecha_fin="";
if (isset($_GET["te_fecha_inicio"])) $te_fecha_inicio = $_GET["te_fecha_inicio"];
if (isset($_GET["te_fecha_fin"])) $te_fecha_fin = $_GET["te_fecha_fin"];

$rpta = $e->Query_Turno_Publicar();
$te_semana = $e->te_semana;
$te_anio = $e->te_anio;
$te_fecha_inicio = $e->te_fecha_inicio;
$te_fecha_fin = $e->te_fecha_fin;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Publicación de Programación Semanal</title>
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
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<?php if($te_semana==""){?>
<CENTER class="FormHeaderFont">No Existe Programacion Para Publicar. Verifique! </CENTER>
<?php }else{ ?>
<CENTER class="FormHeaderFont">Publicación Semana&nbsp;<?php echo $te_semana?><br>Del:&nbsp;<?php echo $te_fecha_inicio?>&nbsp;Al:&nbsp;<?php echo $te_fecha_fin?></CENTER>
<?php } ?>
<br>
<!--<img style='CURSOR: hand' src='../../images/contratos/excel_ico.gif' onclick='javascript:return exportarExcel("listado");' WIDTH='25' HEIGHT='25' alt='Imprimir' title='Exportar Excel'>
<table class='FormTable' id="listado" width='150%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:150%'>
	<tr align='center' >
    	<td class='ColumnTD' style='width:10px'>Nro.</td>
    	<td class='ColumnTD'>Area</td>
    	<td class='ColumnTD'>Nombres</td>
		<td class='ColumnTD'>Lunes</td>
		<td class='ColumnTD'>Martes</td>
		<td class='ColumnTD'>Miercoles</td>
		<td class='ColumnTD'>Jueves</td>
		<td class='ColumnTD'>Viernes</td>
		<td class='ColumnTD'>Sabado</td>
		<td class='ColumnTD'>Domingo</td>
		<td class='ColumnTD'>Total Hr</td>
	</tr>-->
	<?php
       if($rpta == "OK"){ 
        $e->te_fecha_inicio = $te_fecha_inicio;
        $e->te_fecha_fin = $te_fecha_fin;
    		$e->te_semana = $te_semana;
    		$e->te_anio = $te_anio;
    		$cadena=$e->Reporte_Turnos();
    		echo $cadena;
       

            if (file_exists("datos.xls")){ 
                unlink("datos.xls");
            }
            $ar=fopen("datos.xls","a") or die("Problemas en la creacion");
            fputs($ar,$cadena);
            fputs($ar,"\n");
            fclose($ar);
        }
?>
<!--</table> -->
<br>
</form>
<script type="text/javascript">
	window.open("datos.xls",18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
	//self.location.href = "datos.xls";
	window.close();
</script>
</body>
</html>
