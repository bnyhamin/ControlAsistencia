<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/clsEmpleados.php");

$emp = $_GET['emp'];
$ex = new empleados();
$ex->MyUrl = db_host();
$ex->MyUser= db_user();
$ex->MyPwd = db_pass();
$ex->MyDBName= db_name();

$ex->empleado_codigo = $emp;

$rs_empleado  = $ex->ObtenerDatosLactancia();
if ($rs_empleado[2] == 'I') {
	$horario_actual = 'Inicio de turno';
}else{
	$horario_actual =  'Fin de turno';
}
$rs_fecha = '';
$rs_horario = '';
// Si la fecha de aplicacion es mayor a la fecha actual
if((int) $rs_empleado[5] >  (int) $rs_empleado[4]){
	$rs_fecha =  trim($rs_empleado[1]);
	$rs_horario =  $rs_empleado[2];
}

if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if (isset($_POST["empleado_cod"])) $empleado_cod = $_POST["empleado_cod"];
if (isset($_POST["cmdHorario"])) $horario = $_POST["cmdHorario"];
if (isset($_POST["hhorario"])) $horario_anterior = $_POST["hhorario"];
if (isset($_POST["hfecha"])) $fecha_anterior = $_POST["hfecha"];
if (isset($_POST["hfactual"])) $hfecha_actual = $_POST["hfactual"];
if (isset($_POST["hfanterior"])) $hfecha_anterior = $_POST["hfanterior"];
if (isset($_GET["error"])) $msg_error = $_GET["error"];
if (isset($_GET["result"])) $msg_result = $_GET["result"];

if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
  		if($horario_anterior == $horario && $fecha_anterior == $fecha){
  			echo json_encode(array('success' =>'NO','msg' => 'Ud. no ha cambiado el horario ni la fecha'));
			 die();

  		}
  		$ex->empleado_codigo = $empleado_cod;
  		$ex->usuario_id = $_SESSION["empleado_codigo"];
  		$ex->Registrar_Horario_Lactancia($fecha, $horario, $hfecha_anterior, $hfecha_actual);
  			echo json_encode(array('success' =>'OK','msg' => 'Se realizo el cambio de horario y/o Fecha de aplicacion'));
			 die();

  }
}
$ex->empleado_codigo = $emp;

$lst_histo = $ex->Historico_Horario_Lactancia();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Cambiar Horario de Lactancia</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<!--<script language="JavaScript" src="../no_teclas.js"></script>-->
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/modalfiles/modal.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/modalfiles/modal.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" media="all" href="../../views/js/librerias/datepicker/calendar-win2k-cold-1.css" title="win2k-cold-1" />


</head>
<body>
<form id="fm" name="fm" method="post">
<p style="text-align:center"><?php echo $emp  ?> - <?php echo $rs_empleado[0]; ?></p>
<table class='DataTD' align='center'  width='95%' border='0' cellpadding='1' cellspacing='0'>

	<tr>
		<td colspan="3">Actualmente: <?php echo '<b>'.$horario_actual.'</b> Fecha aplicada: <b>'.$rs_empleado[1].'</b>'; ?> </td>
	</tr>
	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='4' >Cambiar Horario de Lactancia Materna
		</td>
	</tr>

	<tr>
		<td colspan="2">Cambie el horario y fecha de aplicaci&oacute;n:</td>
	</tr>
	<tr>
		<td><br></td>
	</tr>
	<tr>
        <td align="left" width="80px">Horario:</td>
		<td >
		<select name="cmdHorario" id="cmdHorario">
		<option value="">Seleccionar...</option>
		<option value="I" <?php echo $rs_horario=='I'?'selected="selected"':'' ?>>Al inicio de turno</option>
		<option value="F" <?php echo $rs_horario=='F'?'selected="selected"':'' ?>>Al fin de turno</option>
		</select>
		
		</td>
		<td align="left" width="100">Fecha Aplicaci&oacute;n:</td>
		<td>
			<input  type='text' class='easyui-datebox' id='fecha' name='fecha' size='15' value='<?php echo $rs_fecha?$rs_fecha:$rs_empleado[3] ?>' >
		</td>
		<input type='hidden' id="hddaccion" name="hddaccion" value=''/>
		<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $emp ?>"/>
		<input type='hidden' id='hhorario' name='hhorario' value="<?php echo $rs_empleado[2] ?>"/>
		<input type='hidden' id='hfecha' name='hfecha' value="<?php echo $rs_empleado[1] ?>"/>
		<input type='hidden' id='hfactual' name='hfactual' value="<?php echo $rs_empleado[4] ?>"/>
		<input type='hidden' id='hfanterior' name='hfanterior' value="<?php echo $rs_empleado[5] ?>"/>
		<input type='hidden' id='hddaccion' name='hddaccion' value="INS"/>


	</tr>
	<tr>

	</tr>
	<tr>

		<td  colspan="4" align=center>

			<input name='cmdGuardar' id='cmdGuardar' type='button' value='Guardar'  class='buttons' onclick="saveUser();">
		</td>
	</tr>
</table>
</form>
<table class='DataTD' align='center'  width='95%' border='0' cellpadding='1' cellspacing='0'>

	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='5' >Historial de cambios Horario de lactancia
		</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align="center">Horario</td>
		<td class='FieldCaptionTD' align="center">Fecha Aplicada</td>
		<td class='FieldCaptionTD' align="center">Estado</td>
		<td class='FieldCaptionTD' align="center">Usuario Reg.</td>
	</tr>
	<?php foreach ($lst_histo as $key => $value): ?>
	<tr>
		<td><?php echo $value['horario'] ?></td>
		<td align="center"><?php echo $value['fecha'] ?></td>
		<td align="center"><?php echo $value['estado'] ?></td>
		<td align="center"><?php echo $value['usuario_reg'] ?></td>
	</tr>

	<?php endforeach ?>
	<tr>
	<td colspan="5" align="center"><br>
		<input name='cmdCancelar' id='cmdCancelar' type='button' value='Cancelar'  class='buttons' onclick="Finalizar2();">
	</td>
	</tr>
</table>

<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar-setup.js"></script>

<script type="text/javascript">


if (msg = '<?php echo $msg_error ?>') {
	alert(msg);
};

if(result = '<?php echo $msg_result ?>'){
	alert(result);
}
function Cancelar(){
	window.close();
}
function Guardar(){
	var fecha_adt = document.fm.fecha.value.split('/');
	// var fecha_dt = fecha_adt[1]+'/'+fecha_adt[0]+'/'+fecha_adt[2];
	var fecha_dt = fecha_adt[2]+fecha_adt[1]+fecha_adt[0];
	// var today = new Date();
	// month_today = today.getMonth();
	// day_today = today.getDay();
	// if (today.getMonth()<10) {month_today = '0'+today.getMonth() };
	// if (today.getDay()<10) {day_today = '0'+today.getDay() };
	// var format_today = today.getFullYear()+''+month_today+''+day_today;

	var format_today = '<?php echo $rs_empleado[4] ?>'
	if (fecha_dt <= format_today) {
		alert('Erro! no puedes colocar una fecha menor o igual a la actual');
		return false;
	}

    	document.fm.hddaccion.value="INS";
        document.fm.submit()

	// document.frm.fecha.value=;

}
</script>


</body>
</html>