<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/bio_Tarjeta_Proximidad.php"); 

$tarjeta_proximidad_id ="";
$tarjeta_proximidad_codigo="";
$tarjeta_proximidad_dni="";
$tarjeta_proximidad_nombre="";
$tarjeta_proximidad_activo="0";
$tarjeta_proximidad_empleado_codigo="";
$tarjeta_proximidad_tipo_asignacion="";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
if(isset($_GET["torder"])){
    $torder = $_GET["torder"];
}

$mensaje = "";

if (isset($_POST["tarjeta_proximidad_id"])) $tarjeta_proximidad_id = $_POST["tarjeta_proximidad_id"];
if (isset($_GET["tarjeta_id"])) $tarjeta_proximidad_id = $_GET["tarjeta_id"];

if (isset($_POST["tarjeta_proximidad_codigo"])) $tarjeta_proximidad_codigo = $_POST["tarjeta_proximidad_codigo"];
if (isset($_POST["tarjeta_proximidad_dni"])) $tarjeta_proximidad_dni = $_POST["tarjeta_proximidad_dni"];
if (isset($_POST["tarjeta_proximidad_nombre"])) $tarjeta_proximidad_nombre = $_POST["tarjeta_proximidad_nombre"];
if (isset($_POST["tarjeta_proximidad_activo"])) $tarjeta_proximidad_activo = $_POST["tarjeta_proximidad_activo"];
if (isset($_POST["tarjeta_proximidad_empleado_codigo"])) {
	$tarjeta_proximidad_empleado_codigo = $_POST["tarjeta_proximidad_empleado_codigo"];
}else {
	$tarjeta_proximidad_empleado_codigo = null;
}
if (isset($_POST["tarjeta_proximidad_tipo_asignacion"])) $tarjeta_proximidad_tipo_asignacion = $_POST["tarjeta_proximidad_tipo_asignacion"];

	

$o = new bio_TarjetaProximidad();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
        $o->tarjeta_proximidad_id = $tarjeta_proximidad_id;
        $o->tarjeta_proximidad_codigo = $tarjeta_proximidad_codigo;
		$o->tarjeta_proximidad_dni = $tarjeta_proximidad_dni;
		$o->tarjeta_proximidad_nombre = $tarjeta_proximidad_nombre;
        $o->tarjeta_proximidad_activo = $tarjeta_proximidad_activo;
		$o->tarjeta_proximidad_empleado_codigo = $tarjeta_proximidad_empleado_codigo;
		$o->tarjeta_proximidad_tipo_asignacion = $tarjeta_proximidad_tipo_asignacion;
	
		
        //--* guardar registro
        //echo 'save!!';
        if ($tarjeta_proximidad_id==''){
                $mensaje = $o->Addnew();
                $tarjeta_proximidad_id = $o->tarjeta_proximidad_id;
        }else{
                $mensaje = $o->Update();
        }
        if($mensaje=='OK'){
?>
    <script language='javascript'>
        self.location.href='bio_main_tarjeta_proximidad.php';
    </script>
<?php
        }
    }
}

if ($tarjeta_proximidad_id !="" ){
	//recuperar datos
	$o->tarjeta_proximidad_id = $tarjeta_proximidad_id;
	$mensaje = $o->Query();
	$tarjeta_proximidad_codigo = $o->tarjeta_proximidad_codigo;
	$tarjeta_proximidad_nombre = $o->tarjeta_proximidad_nombre;
	$tarjeta_proximidad_dni    = $o->tarjeta_proximidad_dni;
	$tarjeta_proximidad_activo = $o->tarjeta_proximidad_activo;
	$tarjeta_proximidad_empleado_codigo = $o->tarjeta_proximidad_empleado_codigo;
	$tarjeta_proximidad_tipo_asignacion = $o->tarjeta_proximidad_tipo_asignacion;
	
	
}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Tarjeta de Proximidad</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language='javascript'>
function ok(){
    
	if (validarCampo('frm','tarjeta_proximidad_codigo')!=true) return false;
    if (validarCampo('frm','tarjeta_proximidad_nombre')!=true) return false;
    if (validarCampo('frm','tarjeta_proximidad_dni')!=true) return false;
	

	if(document.getElementById('tipo_asignacion').value == 'INTERNO') {
	
		if(document.getElementById('empleado_codigo').value== '') {
		 alert("Selecciona un empleado existente");
		 return false;
		}
	
	}

    if(document.frm.tarjeta_proximidad_dni.value.length <8){
          alert("Tarjeta DNI debe tener 8 digitos");
          document.frm.tarjeta_proximidad_dni.select();
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
	self.location.href = "bio_main_tarjeta_proximidad.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; } ?>";
}

function verificaOpcion() {

	document.getElementById('tarjeta_proximidad_dni').value='';
	document.getElementById('tarjeta_proximidad_nombre').value='';
	
	if(document.getElementById('tipo_asignacion').value == 'INTERNO') {
	
		document.getElementById('internoLink').style.display = '';
		document.getElementById('tarjeta_proximidad_dni').readOnly = true;  
		document.getElementById('tarjeta_proximidad_nombre').readOnly = true; 
	}else {
	
		 document.getElementById('internoLink').style.display = 'none';
		 document.getElementById('empleado_codigo').value='';
		 document.getElementById('tarjeta_proximidad_dni').readOnly = false;
		 document.getElementById('tarjeta_proximidad_nombre').readOnly = false; 
	}
	
}

function mostrarPopUpListaEmpleado () {
 
	  var posicion_x; 
      var posicion_y; 
      posicion_x=(screen.width/2)-(680/2); 
      posicion_y=(screen.height/2)-(500/2); 
      
      var valor = window.open('bio_lista_empleados.php','listadoEmpleados','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=500,left='+posicion_x+',top='+posicion_y);
      if (valor == "" || valor == "0" || valor == undefined){return false;}
      
      valor.focus();

}

</script>
</head>


<body Class='PageBODY'>

<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Tarjeta de Proximidad</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>C&oacute;digo</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='tarjeta_proximidad_id' id='tarjeta_proximidad_id' value="<?php echo $tarjeta_proximidad_id ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Tarjeta C&oacute;digo</td>
	<td class='DataTD'>
		 <?php
	
			if(!empty($tarjeta_proximidad_codigo) && !$_POST['tarjeta_proximidad_codigo']) {
			
			   $read_only_tarjeta_codigo = lectura("D");
			}
		 ?>
		<Input  class='Input' type='text' name='tarjeta_proximidad_codigo' id='tarjeta_proximidad_codigo' value="<?php echo $tarjeta_proximidad_codigo?>" maxlength='80' style='width:80px;' <?php echo $read_only_tarjeta_codigo?> >
	</td>
</tr>
</tr>
	<td class='FieldCaptionTD' align='right'>Tipo Asignaci&oacute;n</td>
	<td class='DataTD'>
	
		<?php
			$interno_selected = '';
			$externo_selected = '';
			$interno_link = 'none';
			
			if($tarjeta_proximidad_tipo_asignacion == 'INTERNO') {
				$interno_selected = 'selected';	
				$interno_link = '';
			}else{
				$externo_selected = 'selected';	
			}
		?>
		<?php
			$read_only_dni = "";
			$read_only_nombre = "";
			

			if(strlen($tarjeta_proximidad_tipo_asignacion) ) {
					
					if($tarjeta_proximidad_tipo_asignacion == "INTERNO") {
					
						$read_only_dni = "disabled";
						$read_only_nombre = "disabled";
					}
					if(!$_POST['tarjeta_proximidad_tipo_asignacion']) {
						$read_only_tipo_Asignacion = "disabled";
					}
					
			}

		?>
			<select id='tipo_asignacion' name= 'tarjeta_proximidad_tipo_asignacion' onchange="verificaOpcion()"  <?php echo $read_only_tipo_Asignacion; ?> >
				<option value="INTERNO" <?php echo $interno_selected; ?>>Interno</option>
				<option value="EXTERNO" <?php echo $externo_selected;?>>Externo</option>
			</select>
		<?php
		  if($_POST['tarjeta_proximidad_tipo_asignacion'] || $tarjeta_proximidad_tipo_asignacion == "" ) {
		?>
		<a id='internoLink' href='javascript:void(0)' onclick="mostrarPopUpListaEmpleado()" style='display:<?php echo $interno_link ?>'>Seleccionar un empleado</a>
		<?php } ?>
		<input id='empleado_codigo' name='tarjeta_proximidad_empleado_codigo' value ='<?php echo $tarjeta_proximidad_empleado_codigo;?>' type='hidden'>
		
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Tarjeta DNI</td>
	<td class='DataTD'>
		<Input  class='Input' type='text'  name='tarjeta_proximidad_dni' id='tarjeta_proximidad_dni' maxlength="8" onkeypress="return esnumero(event)" value="<?php echo $tarjeta_proximidad_dni?>" maxlength='80' style='width:70px;' <?php echo $read_only_dni?> >
	</td>
</tr>
	<td class='FieldCaptionTD' align='right'>Tarjeta Nombre</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='tarjeta_proximidad_nombre' id='tarjeta_proximidad_nombre' onkeyup="convierteMayuscula(this);" value="<?php echo $tarjeta_proximidad_nombre?>" maxlength='80' style='width:180px;' <?php echo $read_only_nombre?> >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='tarjeta_proximidad_activo' id='tarjeta_proximidad_activo' value='1' <?php if ($tarjeta_proximidad_activo=="1") echo "Checked"; ?>>
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