<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../includes/Connection.php");
require_once("../includes/mantenimiento.php");
require_once("../includes/Constantes.php"); 
require_once("../includes/clsCA_Estados.php"); 

$CA_Estado_codigo="";
$CA_Estado_descripcion="";
$CA_Estado_activo="0";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
if(isset($_GET["torder"])){
    $torder = $_GET["torder"];
}

$mensaje = "";

if (isset($_POST["CA_Estado_codigo"])) $CA_Estado_codigo = $_POST["CA_Estado_codigo"];
if (isset($_GET["Estado_codigo"])) $CA_Estado_codigo = $_GET["Estado_codigo"];

if (isset($_POST["CA_Estado_descripcion"])) $CA_Estado_descripcion = $_POST["CA_Estado_descripcion"];
if (isset($_POST["CA_Estado_activo"])) $CA_Estado_activo = $_POST["CA_Estado_activo"];

$o = new CA_Estados();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
        $o->CA_Estado_codigo = $CA_Estado_codigo;
        $o->CA_Estado_descripcion = $CA_Estado_descripcion;
        $o->CA_Estado_activo = $CA_Estado_activo;
        //--* guardar registro
        //echo 'save!!';
        if ($CA_Estado_codigo==''){
                $mensaje = $o->Addnew();
                $CA_Estado_codigo = $o->CA_Estado_codigo;
        }else{
                $mensaje = $o->Update();
        }
        if($mensaje=='OK'){
?>
    <script language='javascript'>
        self.location.href='main_estados.php';
    </script>
<?php
        }
    }
}

if ($CA_Estado_codigo!=""){
	//recuperar datos
	$o->CA_Estado_codigo = $CA_Estado_codigo;
	$mensaje = $o->Query();
	$CA_Estado_descripcion = $o->CA_Estado_descripcion;
	$CA_Estado_activo = $o->CA_Estado_activo;
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
<script language="JavaScript" src="../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<?php  require_once('../includes/librerias_easyui.php');?>

<script language='javascript'>
function ok(){
    if (validarCampo('frm','CA_Estado_descripcion')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)) { echo $torder; } ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}
function cancelar(){
	self.location.href = "main_estados.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; } ?>";
}
</script>
</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Estado</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='CA_Estado_codigo' id='CA_Estado_codigo' value="<?php echo $CA_Estado_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='CA_Estado_descripcion' id='CA_Estado_descripcion' value="<?php echo $CA_Estado_descripcion?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='CA_Estado_activo' id='CA_Estado_activo' value='1' <?php if ($CA_Estado_activo=="1") echo "Checked"; ?>>
	</td>
</tr>
<tr>
	<td colspan=2  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=2  class='FieldCaptionTD'>
		<input name='cmdGuardar' id='cmdGuardar' type='submit' class=buttons value='Aceptar'   style='width:80px'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' class=buttons value='Cerrar'  style='width:80px' onclick="cancelar();">
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>

</body>
</html>