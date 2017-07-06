<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/bio_Lector.php"); 

$lector_id ="";
$lector_codigo_equipo="";
$lector_nombre="";
$lector_ip="";
$lector_puerto="";
$lector_tipoacceso="";
$lector_marca="";
$lector_modelo="";
$lector_nro_serie="";
$lector_activo="0";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
if(isset($_GET["torder"])){
    $torder = $_GET["torder"];
}

$mensaje = "";

if (isset($_POST["lector_id"])) $lector_id = $_POST["lector_id"];
if (isset($_GET["lector_id"])) $lector_id = $_GET["lector_id"];
if (isset($_POST["lector_codigo_equipo"])) $lector_codigo_equipo = $_POST["lector_codigo_equipo"];
if (isset($_POST["lector_nombre"])) $lector_nombre = $_POST["lector_nombre"];
if (isset($_POST["lector_ip"])) $lector_ip = $_POST["lector_ip"];
if (isset($_POST["lector_puerto"])) $lector_puerto = $_POST["lector_puerto"];
if (isset($_POST["lector_tipoacceso"])) $lector_tipoacceso = $_POST["lector_tipoacceso"];
if (isset($_POST["lector_marca"])) $lector_marca = $_POST["lector_marca"];
if (isset($_POST["lector_modelo"])) $lector_modelo = $_POST["lector_modelo"];
if (isset($_POST["lector_nro_serie"])) $lector_nro_serie = $_POST["lector_nro_serie"];
if (isset($_POST["lector_activo"])) $lector_activo = $_POST["lector_activo"];

$o = new bio_Lector();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

if (isset($_POST["hddaccion"])){
	
	
    if ($_POST["hddaccion"]=='SVE'){
        	
        $o->lector_id = $lector_id;
        $o->lector_codigo_equipo = $lector_codigo_equipo;
    		$o->lector_nombre = $lector_nombre;
    		$o->lector_ip = $lector_ip;
    		$o->lector_puerto = $lector_puerto;
    		$o->lector_tipoacceso = $lector_tipoacceso;
    		$o->lector_marca = $lector_marca;
    		$o->lector_modelo = $lector_modelo;
    		$o->lector_nro_serie = $lector_nro_serie;
    		$o->lector_activo = $lector_activo;
		
		

        //--* guardar registro
        //echo 'save!!';
        if ($lector_id==''){
                $mensaje = $o->Addnew();
                $lector_id = $o->lector_id;
        }else{
                $mensaje = $o->Update();
        }
        if($mensaje=='OK'){
?>
    <script language='javascript'>
        self.location.href='bio_main_lector.php';
    </script>
<?php
        }
    }
}

if ($lector_id !="" ){
	//recuperar datos
	$o->lector_id = $lector_id;
	$mensaje = $o->Query();
	$lector_codigo_equipo = $o->lector_codigo_equipo;
	$lector_nombre = $o->lector_nombre;
	$lector_ip = $o->lector_ip;
	$lector_puerto = $o->lector_puerto;
	$lector_tipoacceso = $o->lector_tipoacceso;
	$lector_marca = $o->lector_marca;
	$lector_modelo = $o->lector_modelo;
	$lector_nro_serie = $o->lector_nro_serie;
	$lector_activo = $o->lector_activo;	
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Lectores</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language='javascript'>
function ok(){
    
    if (validarCampo('frm','lector_codigo_equipo')!=true) return false;
    if (validarCampo('frm','lector_nombre')!=true) return false;
    if (validarCampo('frm','lector_ip')!=true) return false;
    if (validarCampo('frm','lector_puerto')!=true) return false;

  	if(!validateIp('lector_ip')){
  		alert("Error:\nIP no es valida");
  		frm.lector_ip.focus();
  		return false;
  	}
	
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)) { echo $torder; } ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}

function cancelar(){
	self.location.href = "bio_main_lector.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; } ?>";
}
</script>
</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);

?>
<center class=FormHeaderFont>Registro de Lectores</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>C&oacute;digo</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_id' id='lector_id' value="<?php echo $lector_id ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'> C&oacute;digo Equipo</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_codigo_equipo' id='lector_codigo_equipo' onkeypress="return esnumero(event)" value="<?php echo $lector_codigo_equipo?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripci&oacute;n</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_nombre' id='lector_nombre' value="<?php echo $lector_nombre?>" maxlength='80' style='width:300px;' >
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>IP</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_ip' id='lector_ip' onkeypress="return esdecimal(event)" value="<?php echo $lector_ip?>" maxlength='15' style='width:150px;' >
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Puerto</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_puerto' id='lector_puerto' onkeypress="return esnumero(event)" value="<?php echo $lector_puerto?>" maxlength='6' style='width:40px;' >
	</td>
</tr>

<tr>
	<td class='FieldCaptionTD' align='right'>Tipo Acceso</td>
	<td class='DataTD'>
		<?php
			$checked1 = "";
			$checked2 = "";
			$checked3 = "";
			
			if($lector_tipoacceso == "A") {
				
				$checked3 = "selected";
				
			}else if($lector_tipoacceso == "S"){
				
				$checked2 = "selected";
				
			}else {
			
				$checked1 = "selected";
			}
		?>
		<select name="lector_tipoacceso" class="Input">
			<option value="E" <?php echo $checked1 ;?>>Entrada</option>
			<option value="S" <?php echo $checked2 ;?>>Salida</option>
			<option value="A" <?php echo $checked3 ;?>>Mixta</option>
		</select>
		
	</td>
</tr>


<tr>
	<td class='FieldCaptionTD' align='right'>Marca</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_marca' id='lector_marca' value="<?php echo $lector_marca?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Modelo</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_modelo' id='lector_modelo' value="<?php echo $lector_modelo?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Nro Serie</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_nro_serie' id='lector_nro_serie' value="<?php echo $lector_nro_serie?>" maxlength='80' style='width:300px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='lector_activo' id='lector_activo' value='1' <?php if ($lector_activo=="1") echo "Checked"; ?>>
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