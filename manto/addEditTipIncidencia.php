<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clsIncidencias.php"); 

$tipo_codigo="";
$tipo_descripcion="";
$tipo_estado="0";
$mensaje='';

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
if(isset($_GET["torder"])){
    $torder = $_GET["torder"];
}

if (isset($_POST["tipo_codigo"])) $tipo_codigo = $_POST["tipo_codigo"];
if (isset($_POST["tipo_descripcion"])) $tipo_descripcion = $_POST["tipo_descripcion"];
if (isset($_POST["tipo_estado"])) $tipo_estado = $_POST["tipo_estado"];
if (isset($_GET["tipo_codigo"])) $tipo_codigo = $_GET["tipo_codigo"];

$o = new incidencias();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
        $o->tipo_incidencia_codigo = $tipo_codigo;
        $o->tipo_descripcion = $tipo_descripcion;
        $o->tipo_estado = $tipo_estado;
        if ($tipo_codigo==''){
                $mensaje = $o->addTipIncidencia();
                $tipo_codigo = $o->tipo_incidencia_codigo;
        }else{
                $mensaje = $o->updTipIncidencia();
        }
        if($mensaje=='OK'){
?>
    <script language='javascript'>
		self.location.href='main_tipo_incidencia.php';
    </script>
<?php
        }
    }
}
if ($tipo_codigo!=""){
	$o->tipo_incidencia_codigo = $tipo_codigo;
	$mensaje = $o->obtenerTipoIncidencia();
	$tipo_descripcion = $o->tipo_descripcion;
	$tipo_estado = $o->tipo_estado;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Estados</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language='javascript'>
function ok(){
    if (validarCampo('frm','tipo_descripcion')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)) { echo $torder; } ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}
function cancelar(){
	self.location.href = "main_tipo_incidencia.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; } ?>";
}
</script>
</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Tipo Incidencia</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>C&oacute;digo</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='tipo_codigo' id='tipo_codigo' value="<?php echo $tipo_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='tipo_descripcion' id='tipo_descripcion' value="<?php echo $tipo_descripcion?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='tipo_estado' id='tipo_estado' value='1' <?php if ($tipo_estado=="1") echo "Checked"; ?>>
	</td>
</tr>
<tr>
	<td colspan=2  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=2  class='FieldCaptionTD'>
		<input name='cmdGuardar' id='cmdGuardar' type='submit' class=button value='Aceptar'   style='width:80px'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' class=button value='Cerrar'  style='width:80px' onclick="cancelar();">
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>

</body>
</html>