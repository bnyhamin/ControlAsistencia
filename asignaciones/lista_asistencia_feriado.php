<?php header("Expires: 0"); 

//require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clsEmpleado_Asistencia_Feriado.php");

  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);

if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];
if (isset($_GET["tf"])) $tipo_feriado = $_GET["tf"];
if (isset($_GET["feriado"])) $feriado_codigo = $_GET["feriado"];
if (isset($_GET["responsable"])) $responsable = $_GET["responsable"];
$ar_feriado = array(0,0);
if ($feriado_codigo) {
	$ar_feriado = explode("-", $feriado_codigo);
}

$e = new Empleado_Asistencia_Feriado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Consulta de Turno</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language='javascript'>

function Finalizar(){
    window.close();
}


</script>

</head>

<body Class='PageBODY'>
<center class=FormHeaderFont><br> Lista de asistencias en Feriados </center>
</table>

<div style='display:block' id='div_asignar'>
<div id="result"></div>
<?php if ($_GET['t']=='detalle'): ?>
	
	<?php 
		$e->Empleado_Codigo = $empleado_id;
		$feriados = $e->Lista_Feriado_por_Empleado();
	?>

	<table  class='FormTable' width='100%' align='center' cellspacing='0' cellpadding='0' border='1'>

	<tr>
		<th class='thstyle' align='center' ><b>Feriado</th>
		<th class='thstyle' align='center' ><b>Fecha Feriado</th>
		<th class='thstyle' align='center' ><b>Tipo Feriado</th>
		<th class='thstyle' align='center' ><b>Eliminar</th>
	</tr>
	<?php if (count($feriados)>0): ?>
		
	<?php foreach ($feriados as $key => $value): ?>
		
	<tr>
		<td class='DataTD' align='left'><?php echo $value['feriado'] ?></td>
		<td class='DataTD' align='left'><?php echo $value['fecha'] ?></td>
		<td class='DataTD' align='center'><?php echo $value['tipo'] ?></td>
		<td class='DataTD' align='center'>

		<input type="button" class="buttons del" value="Eliminar" data-id="<?php echo $value['eaf_codigo'] ?>"></td>
		
	</tr>

	<?php endforeach ?>
	<?php else: ?>
	<tr>
		<td colspan="4" align="center">No hay asistencia en feriado</td>
	</tr>
	<?php endif ?>
	<tr align='center'>
		<td colspan=4  class='thstyle'>
			<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'  class='buttons' style='width:80px' onclick="Finalizar();"/>
		</td>
	</tr>
	</table>
<?php elseif($_GET['t'] == 'lista'): ?>
	<?php 
		$e->tipo_codigo = $tipo_feriado;
		$e->anio_codigo = $ar_feriado[0];
		$e->feriado_codigo = $ar_feriado[1];
		$e->Usuario_Responsable = $responsable;
		$feriados = $e->Lista_Feriados();
	?>

	<table  class='FormTable' width='100%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<tr>
		<th class='thstyle' align='center' ><b>Empleado</th>
		<th class='thstyle' align='center' ><b>Feriado</th>
		<th class='thstyle' align='center' ><b>Fecha Feriado</th>
		<th class='thstyle' align='center' ><b>Tipo Feriado</th>
		<th class='thstyle' align='center' ><b>Eliminar</th>
	</tr>
	<?php foreach ($feriados as $key => $value): ?>
	<tr>
		<td class='DataTD' align='left'><?php echo $value['empleado'] ?></td>
		<td class='DataTD' align='left'><?php echo $value['feriado'] ?></td>
		<td class='DataTD' align='left'><?php echo $value['fecha'] ?></td>
		<td class='DataTD' align='center'><?php echo $value['tipo'] ?></td>
		<td class='DataTD' align='center'>

		<input type="button" class="buttons del" value="Eliminar" data-id="<?php echo $value['eaf_codigo'] ?>"></td>
		
	</tr>

	<?php endforeach ?>

	<tr align='center'>
		<td colspan=5  class='thstyle'>
			<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'  class='buttons' style='width:80px' onclick="Finalizar();"/>
		</td>
	</tr>
	</table>

<?php endif ?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('.del').on('click',function(){
	if (confirm('Confirme eliminar Autorizacion de asistencia en Feriado')==false) return false;
		var $this = $(this);
		$.ajax({url:'eliminar_asistencia_feriado.php', data:{eaf_codigo:$this.data('id')}
		}).done(function(rs){
			$('#result').html(rs);
			$this.parent().parent().remove();

		});

	});


});	
</script>
</body>
</html>