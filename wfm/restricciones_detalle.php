<?php header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/clswfm_empleado_restricciones.php");
require_once("../../Includes/clsempleados.php");
require_once("../includes/clsCA_Turnos_Empleado.php");

//$codigo = $_GET['codigo'];
$empleado_codigo = $_GET['empleado'];
$semana= $_GET['semana'];
$anio = $_GET['anio'];

//$anio=2010;//hay que pasarlo por parametro

$e = new Empleados;
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());

$te = new ca_turnos_empleado();
$te->setMyUrl(db_host());
$te->setMyUser(db_user());
$te->setMyPwd(db_pass());
$te->setMyDBName(db_name());

$er = new wfm_empleado_restricciones;
$er->setMyUrl(db_host());
$er->setMyUser(db_user());
$er->setMyPwd(db_pass());
$er->setMyDBName(db_name());

$e->empleado_codigo = $empleado_codigo;
$e->Query();

$empleado=$e->empleado_apellido_paterno . ' ' . $e->empleado_apellido_materno . ' ' . $e->empleado_nombres;

$te->te_semana=$semana;
$te->te_anio=$anio;
$te->Obtener_Fechas();

$fecha_inicio= $te->te_fecha_inicio;
$fecha_fin=$te->te_fecha_fin;

$er->empleado_codigo = $empleado_codigo;
$er->semana = $semana;
$er->anio=$anio;

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Detalle de Restricciones</title>
<meta http-equiv="pragma" content="no-cache">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language=javascript>
function lanzarflujo(f){
  var valor = window.showModalDialog("historico_movimientos_flujo.php?codigo=" + f,"Flujo",'dialogWidth:800px; dialogHeight:400px');
}
function lanzarver(em,e,m){
  var valor = window.showModalDialog("../Movimientos/movimiento_detalles.php?emp_mov_codigo="  + em + "&codigo=" + e + "&mov_codigo=" +m ,"Detalle_Movimiento",'dialogWidth:600px; dialogHeight:550px');
}
</script>
</head>
<body>
<center><strong>DETALLE DE RESTRICCIONES</strong></center>
<br />
<center><strong><?php echo $empleado_codigo . ' - ' . $empleado; ?></strong></center>
<br />
<center><strong><?php echo $fecha_inicio . ' - ' . $fecha_fin; ?></strong></center>
<br />
<table class="FormTable" width="100%"  border="0" cellspacing="1" cellpadding="0">
  <tr >
    <td  class="FieldCaptionTD" style="width:30px" align="center">Código</td>
    <td  class="FieldCaptionTD" style="width:200px" align="center">Restricción</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Lun</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Mar</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Mié</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Jue</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Vie</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Sáb</td>
    <td  class="FieldCaptionTD" style="width:80px" align="center">Dom</td>
    
    <!--<td style="width:20px">Flujo</td>
    <td style="width:20px">ver</td>-->
    </tr>
  <?php

  	//$oe->Listar_Movimientos_Detalle();
  	$er->mostrar_resumen_restricciones_empleado();
  ?>
</table>

</body>
</html>