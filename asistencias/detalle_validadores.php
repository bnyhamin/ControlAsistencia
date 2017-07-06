<?php
    header("Expires: 0");
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    $empleado=0;
    $incidencia=0;
    
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
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>

</head>
<body class="PageBODY">
    <center style="text-align: center; font-weight: bold;font-size: 13px;">Responsable de eventos validables</center>
   
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
    
<table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
  <tr>
        
        <td class="ColumnTD" align=center width="200">Incidencia</td>
        <td class='ColumnTD' align=center width="200">Validador</td>
        <td class='ColumnTD' align=center width="200">Email</td>

  </tr>
<?php

    $ase = new ca_asignacion_empleados();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();

    if(isset($_GET["incidencia"])) $incidencia=$_GET["incidencia"];
    if(isset($_GET["empleado"])) $empleado=$_GET["empleado"];
    
    $ase->incidencia_codigo=$incidencia;
    $ase->empleado_codigo=$empleado;
    
    echo $ase->detalle_validadores();
?>
</table>

</form>
<br/>

</body>
</html>
