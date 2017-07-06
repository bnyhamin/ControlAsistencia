<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Roles.php"); 

$rol_codigo="";
$rol_descripcion="";
$rol_activo="0";
$torder="";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
//$torder = $_GET["torder"];
$mensaje = "";

if (isset($_GET["torder"])) $torder = $_GET["torder"];

if (isset($_POST["rol_codigo"])) $rol_codigo = $_POST["rol_codigo"];
if (isset($_GET["rol_codigo"])) $rol_codigo = $_GET["rol_codigo"];

if (isset($_POST["rol_descripcion"])) $rol_descripcion = $_POST["rol_descripcion"];
if (isset($_POST["rol_activo"])) $rol_activo = $_POST["rol_activo"];

//$rol_descripcion = isset($_POST["rol_descripcion"]) ? $_POST["rol_descripcion"] : "";
//$rol_activo= isset($_POST["rol_activo"]) ? $_POST["rol_activo"] : "";

$o = new ca_roles();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='SVE'){
				
		$o->rol_codigo = $rol_codigo;
		$o->rol_descripcion = $rol_descripcion;
		$o->rol_activo = $rol_activo;
		if ($rol_codigo==''){
			$mensaje = $o->Addnew();
			$rol_codigo = $o->rol_codigo;
		}else{
			$mensaje = $o->Update();
		}
        //echo "mr- " .$mensaje;
        
		if($mensaje=='OK'){
		?><script language='javascript'>
		  self.location.href='main_roles.php';
		  </script>
		<?php
		}
	}
}
if ($rol_codigo!=""){
	//recuperar datos
	$o->rol_codigo = $rol_codigo;
	$mensaje = $o->Query();
	$rol_descripcion = $o->rol_descripcion;
	$rol_activo = $o->rol_activo;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Rol</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>

<script language='javascript'>
function ok(){
    if (validarCampo('frm','rol_descripcion')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}
function cancelar(){
	self.location.href = "main_roles.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
</script>

</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Rol</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='rol_codigo' id='rol_codigo' value="<?php echo $rol_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='rol_descripcion' id='rol_descripcion' value="<?php echo $rol_descripcion?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='rol_activo' id='rol_activo' value='1' <?php if ($rol_activo=="1") echo "Checked"; ?>>
	</td>
</tr>
<tr>
	<td colspan=2  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=2  class='FieldCaptionTD'>
		<input class=button name='cmdGuardar' id='cmdGuardar' type='submit' value='Aceptar'   style='width:80px' />
		<input class=button name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar' style='width:80px' onclick="cancelar();" />
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="" />
</form>

</body>
</html>