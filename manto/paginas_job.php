<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Paginas.php"); 

$pagina_codigo="";
$pagina_descripcion="";
$pagina_url="";
$pagina_activo="0";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
$torder = isset($_GET["torder"])? $_GET["torder"]:"";
$mensaje = "";

if (isset($_POST["pagina_codigo"])) $pagina_codigo = $_POST["pagina_codigo"];
if (isset($_GET["pagina_codigo"])) $pagina_codigo = $_GET["pagina_codigo"];

if (isset($_POST["pagina_descripcion"])) $pagina_descripcion = $_POST["pagina_descripcion"];
if (isset($_POST["pagina_url"])) $pagina_url = $_POST["pagina_url"];
if (isset($_POST["pagina_activo"])) $pagina_activo = $_POST["pagina_activo"];

$o = new ca_paginas();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='SVE'){
				
		$o->pagina_codigo = $pagina_codigo;
		$o->pagina_descripcion = $pagina_descripcion;
		$o->pagina_url = $pagina_url;
		$o->pagina_activo = $pagina_activo;
		
		//--* guardar registro
		//echo 'save!!';
		if ($pagina_codigo==''){
			$mensaje = $o->Addnew();
			$pagina_codigo = $o->pagina_codigo;
		}else{
			$mensaje = $o->Update();
		}
		if($mensaje=='OK'){
		?><script language='javascript'>
		  self.location.href='main_paginas.php';
		  </script>
		<?php
		}
	}
}
if ($pagina_codigo!=""){
	//recuperar datos
	$o->pagina_codigo = $pagina_codigo;
	$mensaje = $o->Query();
	$pagina_descripcion = $o->pagina_descripcion;
	$pagina_url=$o->pagina_url;
	$pagina_activo = $o->pagina_activo;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de pagina</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language='javascript'>
function ok(){
    if (validarCampo('frm','pagina_descripcion')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}
function cancelar(){
	self.location.href = "main_paginas.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
</script>

<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Pagina</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código</td>
	<td class='DataTD'>
		<input  class='Input' type='text' name='pagina_codigo' id='pagina_codigo' value="<?php echo $pagina_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?> />
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
	<td class='DataTD'>
		<input  class='Input' type='text' name='pagina_descripcion' id='pagina_descripcion' value="<?php echo $pagina_descripcion?>" maxlength='80' style='width:300px;' />
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Url</td>
	<td class='DataTD'>
		<input  class='Input' type='text' name='pagina_url' id='pagina_url' value="<?php echo $pagina_url?>" maxlength='255' style='width:300px;' />
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<input class='Input' type='checkbox' name='pagina_activo' id='pagina_activo' value='1' <?php if ($pagina_activo=="1") echo "Checked"; ?> />
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