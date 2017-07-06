<?php header("Expires: 0"); ?>
<?php
require_once("../Includes/Connection.php");
require_once("../Includes/librerias.php");

$opt= $_GET["opt"];
$fecha_server= "";
$hora_server= "";

//-- obtener hora del servidor
$ssql="SELECT top 1 empleado_codigo, convert(varchar(10), getdate(),103) as Fecha, convert(varchar(8), getdate(),108) as hora ";
$ssql.= " FROM empleados ";
//echo $ssql;
/*
$rs_e = consultar_sql($ssql);
if (mssql_num_rows($rs_e)>=0){
	$rs = mssql_fetch_row($rs_e);
	$fecha_server= $rs[1]; //("fecha")
	$hora_server= $rs[2]; //("hora")
}
*/

$rs = consultar_sql2($ssql);
$fecha_server= $rs->Fields['Fecha']->Value; //("fecha")
$hora_server= $rs->Fields['hora']->Value; //("hora")
?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Obtener Hora del Servidor</title>
<meta http-equiv='pragma' content='no-cache'>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="mantenimiento/style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
	//alert('<?php echo $fecha_server . " " . $hora_server ?>');
    window.parent.hora('<?php echo $opt ?>', '<?php echo $fecha_server ?>', '<?php echo $hora_server ?>');
    //window.parent.hora('<?php echo $opt ?>', '', '');
    //window.opener.hora('<%=opt%>', '<%=horaserver%>');
    //self.close();
</script>
<body class="PageBODY">
<?php echo $fecha_server . " " . $hora_server ?>
</body>
</html>