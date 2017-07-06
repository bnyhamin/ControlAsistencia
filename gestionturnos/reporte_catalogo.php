<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
$tipo_area_codigo="1";
if (isset($_POST["tipo_area_codigo"])) $tipo_area_codigo= $_POST["tipo_area_codigo"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Reporte de Catalogo de Turnos</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>

<script LANGUAGE=javascript>

function cmdEjecutar_onclick(){
	var tipo=0;
	if (document.getElementById('tipo_operativo').checked){
		tipo=1;
	}
        
    frames['ifr_procesos'].location.href = "procesos.php?opcion=Ex_Turnos&tc_codigo="+tipo;
}

</script>

</head>

<body class="PageBODY" >
<center class="FormHeaderFont">Seleccione Tipo</center>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr align="center">
        <td class='DataTD'>
            <input type="radio" name="tipo_area_codigo" id="tipo_operativo" value="1" <?php if ($tipo_area_codigo=="1") echo "Checked"; ?>>Operativo
            <input type="radio" name="tipo_area_codigo" id="tipo_administrativo" value="0" <?php if ($tipo_area_codigo=="0") echo "Checked"; ?>>Administrativo
        </td>
    </tr>
    <tr align="center">
        <td>
            <br>
        </td>
    </tr>
    <tr align="center">
        <td class='ColumnTD'>
            <INPUT class=button type='button' value='Ejecutar' id='cmdEjecutar' name='cmdEjecutar' LANGUAGE=javascript onclick='return cmdEjecutar_onclick()' style='width:80px;'>
            <INPUT class=button type='button' value='Cerrar' id='cmdCerrar' name='cmdCerrar' LANGUAGE=javascript onclick='window.close()' style='width:80px;'>
        </td>
    </tr>
</table>
</form>
<div style='position:absolute;left:100px;top:500px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>