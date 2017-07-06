<?php header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/clswfm_empleado_restricciones.php");
require_once("../../Includes/clsempleados.php");
require_once("../includes/clsCA_Turnos_Empleado.php");

//$codigo = $_GET['codigo'];
$empleado_codigo = $_GET['empleado'];
$semana= $_GET['semana'];
$anio = $_GET['anio'];

//echo $empleado_codigo . '-' . $semana .'-'. $anio;


$e = new Empleados;
$e->MyDBName= db_name();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();

$te = new ca_turnos_empleado();
$te->MyUrl = db_host();
$te->MyUser= db_user();
$te->MyPwd = db_pass();
$te->MyDBName= db_name();

$er = new wfm_empleado_restricciones;
$er->MyDBName= db_name();
$er->MyUrl = db_host();
$er->MyUser= db_user();
$er->MyPwd = db_pass();

$e->empleado_codigo = $empleado_codigo;
$e->Query();

$empleado=$e->empleado_apellido_paterno . ' ' . $e->empleado_apellido_materno . ' ' . $e->empleado_nombres;

$te->te_semana=$semana;
$te->te_anio=$anio;
$te->Obtener_Fechas();

$fecha_inicio= $te->te_fecha_inicio;
$fecha_fin=$te->te_fecha_fin;

$er->empleado_codigo = $empleado_codigo;
//$er->semana = $semana;
//$er->anio=$anio;




?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Detalle de Programacion</title>
<meta http-equiv="pragma" content="no-cache">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<center><strong>DETALLE DE PROGRAMACIÓN</strong></center>
<br />
<center><strong><?php echo $empleado_codigo . ' - ' . $empleado; ?></strong></center>
<br />
<center><strong><?php echo $fecha_inicio . ' - ' . $fecha_fin; ?></strong></center>
<br />
<table class="FormTable" width="100%"  border="0" cellspacing="1" cellpadding="0">
  <tr >
    <td  class="FieldCaptionTD" style="width:150px" align="center">Lun</td>
    <td  class="FieldCaptionTD" style="width:150px" align="center">Mar</td>
    <td  class="FieldCaptionTD" style="width:150px" align="center">Mié</td>
    <td  class="FieldCaptionTD" style="width:150px" align="center">Jue</td>
    <td  class="FieldCaptionTD" style="width:150px" align="center">Vie</td>
    <td  class="FieldCaptionTD" style="width:150px" align="center">Sáb</td>
    <td  class="FieldCaptionTD" style="width:150px" align="center">Dom</td>
  </tr>
  <?php

  		$er->mostrar_programacion_empleado($semana , $anio , $empleado_codigo);
  ?>
</table>

</body>
</html>