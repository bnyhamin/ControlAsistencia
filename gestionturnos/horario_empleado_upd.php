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
require_once("../includes/clsCA_Empleados.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../../Includes/MyCombo.php");

$empleado_id="";
$empleado_dni="";
$empleado_nombre="";
$modalidad_codigo="";
$modalidad_descripcion="";
$horario_codigo="";
$horario_descripcion="";
$rpta="";

$oe = new Empleados;
$oe->MyDBName= db_name();
$oe->MyUrl = db_host();
$oe->MyUser= db_user();
$oe->MyPwd = db_pass();

$e = new ca_empleados();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];
if (isset($_POST["empleado_id"])) $empleado_id = $_POST["empleado_id"];
if (isset($_POST["horario_codigo"])) $horario_codigo = $_POST["horario_codigo"];
//if (isset($_POST["empleado_nombre"])) $empleado_nombre = $_POST["empleado_nombre"];
if (isset($_POST["hddAccion"])){
	if ($_POST["hddAccion"]=="UPD"){
		$oe->empleado_codigo=$empleado_id;
		$rpta = $oe->Actualizar_Campo(10, $horario_codigo, '', $_SESSION["empleado_codigo"]);
		if($rpta !="OK"){
			echo $rpta;
			//echo "Error Actualizando Datos";
		}else{
			?>
			<script>
				window.parent.Buscar();
				window.parent.windowv.hide();
			</script>
			<?php
		}
	}
}
$e->empleado_codigo=$empleado_id;
$rpta = $e->Query_vdatos();
if($rpta!="OK"){
	echo "Error al leer los datos";
}else{
	$empleado_dni=$e->empleado_dni;
	$empleado_nombre=$e->empleado_nombre;
	$modalidad_codigo=$e->modalidad;
	$modalidad_descripcion=$e->modalidad_descripcion;
	$horario_codigo=$e->horario;
	$horario_descripcion=$e->horario_descripcion;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Cambio de Horario</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language='javascript'>

function Finalizar(){
	parent.windowv.hide();
}

function asignar(){
    if (validarCampo('frm','horario_codigo')!=true) return false;
	if (confirm('Seguro de modificar el horario?')== false){
		return false;
	}
	document.frm.hddAccion.value='UPD';
	document.frm.submit();
}

</script>
</head>

<body Class='PageBODY'>
<center class=FormHeaderFont>Horario Actual</center>
<form name="frm" id="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table  class='FormTable' width='98%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='center' width='35%' colspan="2"><b>Empleado&nbsp;</td>
	<td class='FieldCaptionTD' align='center' width='70%'><?php echo $empleado_nombre?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' width='35%' colspan="2"><b>DNI&nbsp;</td>
	<td class='FieldCaptionTD' align='center' width='70%'><?php echo $empleado_dni?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' width='35%' colspan="2"><b>Modalidad&nbsp;</td>
	<td class='FieldCaptionTD' align='center' width='70%'><?php echo $modalidad_descripcion ?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' width='35%' colspan="2"><b>Horario&nbsp;</td>
	<td class='FieldCaptionTD' align='center' width='70%'><?php echo $horario_descripcion ?></td>
</tr>
</table>
<br/>
<br/>
<center class=FormHeaderFont>Cambiar Por:</center>
<br/>
<table  class='FormTable' width='98%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='center' colspan="3"><b>Selecione Horario&nbsp;</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' colspan="3">
	<?php
                /*
                 * original para devolver
		$ssql = " select i.item_codigo,i.item_descripcion from modalidad_horario m join items i ";
		$ssql.= " on m.horario_codigo=i.item_codigo ";
		$ssql.= " where i.item_activo=1 and modalidad_codigo=".$modalidad_codigo;
		$ssql.= " order by 2 ";
                */
                /*quitar esta logica*/
                $cadena="";
                if($modalidad_codigo=="74") $cadena=" and horario_codigo in (492,254,84,706) ";
                if($modalidad_codigo=="77") $cadena=" ";
                $ssql = " select i.item_codigo,i.item_descripcion from modalidad_horario m join items i ";
		$ssql.= " on m.horario_codigo=i.item_codigo ";
		$ssql.= " where i.item_activo=1 and modalidad_codigo=".$modalidad_codigo." ".$cadena;
		$ssql.= " order by 2 ";
                /*quitar esta logica*/
        echo $ssql;
		$combo->query = $ssql;
		$combo->name = "horario_codigo";
		$combo->value = $horario_codigo."";
		$combo->more = "class=select style='width:300px'";
		$rpta = $combo->Construir();
		echo $rpta;
	?>
	</td>
</tr>
</table>
<br/>
<br/>
<table width='98%' align='center' cellspacing='0' cellpadding='0' border='none'>
<tr align="center" >
	<td colspan='3'>
 		<input class='button' type='button' onClick='asignar()' value='Asignar'  style='width:80px'>
 		<input class='button' type='button' onClick='Finalizar()' value='Cerrar' style='width:80px'>
	</td>
<tr>
</table>
<input type="hidden" id="hddAccion" name="hddAccion" value="">
<input type="hidden" id="empleado_id" name="empleado_id" value="<?php echo $empleado_id; ?>" >
</form>
</body>
</html>