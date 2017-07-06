<?php header("Expires: 0");
//require_once("../includes/seguridad.php");
require_once("../../includes/Connection.php");
require_once("../../includes/Constantes.php"); 
require_once("../../includes/MyCombo.php"); 
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Pagina_Rol.php"); 
require_once("../../includes/clsLibreria.php");

$mensaje="";
$rol_codigo=0;

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$lib = new libreria();
$lib->MyUrl = db_host();
$lib->MyUser= db_user();
$lib->MyPwd = db_pass();
$lib->MyDBName= db_name();

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area      			= $u->area_codigo;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; // responsable area
$fecha     			= $u->fecha_actual;
 
$o = new ca_pagina_rol();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

//--- grabar datos ---
if (isset($_POST['hddaccion'])){
	$rol_codigo= $_POST['rol_codigo'];
	$codigos = $_POST['hddcodigos'];
	if ($_POST['hddaccion']=='SVE'){
		$o->rol_codigo = $rol_codigo;
		//$o->area_codigo = $area;
		$mensaje = $o->Save_All($codigos);
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asignar Lectores a Plataforma</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
function guardar(){
	//-- obtenr codigos seleccionados
	if(document.frm.rol_codigo.value==0){
		alert('Seleccione Rol');
		document.frm.rol_codigo.focus();
		return false;
	}
	var cods="";
	for (var i=0; i< document.frm.lstSeleccionados.length; i++){
		var valor = document.frm.lstSeleccionados.options[i].value;
		if (cods == ''){ 
			cods = valor; 
		}else{ 
			cods += ',' + valor; 
		}
	 }
	if (confirm('Guardar registros?')==false) return false;	 
	document.frm.hddcodigos.value=cods;
	document.frm.hddaccion.value="SVE";
	document.frm.submit();
}

function cambiar(){
	if (document.frm.rol_codigo.value!=0){
		document.frm.hddaccion.value = "CGE";
	}
	document.frm.submit();
}

</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<CENTER class="FormHeaderFont">Asignaci&oacute;n de Plataformas a Lectores</CENTER>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>

<?php 
	if ($mensaje!='OK') echo $mensaje;
?>
<table width="80%" border="1" cellspacing="0" cellpadding="0" align="center" class="FormTABLE">
  <tr>
    <td width="350px" align="center" class="ColumnTD">Plataformas</td>
    <td width="40px" rowspan="3" class="FieldCaptionTD" align="center" valign="middle">
		<input type="button" id="cmd1" value=">>" class="Button" onclick="return Mover('frm', 'lstPersonal','lstSeleccionados')" />
		<br><br>
		<input type="button" id="cmd2" value="<<" class="Button" onclick="return Mover('frm', 'lstSeleccionados','lstPersonal')" />
	</td>
    <td width="100px" align="center" class="ColumnTD">Seleccione Lector	</td>
    <td width="200px"  class="ColumnTD">
		<?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			
			$sql4="SELECT lector_id as codigo, lector_nombre FROM lector"; 
			$sql4 .=" WHERE lector_activo=1 order by 2 asc"; 

			$combo->query = $sql4;
			$combo->name = "rol_codigo"; 
			$combo->value = $rol_codigo."";
			$combo->more = "class=select style='width:250px;' onchange=cambiar()";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
	</td>
  </tr>
  <tr>
    <td height="300px">
		<select class="Select" id="lstPersonal" name="lstPersonal" size="25" style="width:100%" multiple ondblclick="return Mover('frm', 'lstPersonal','lstSeleccionados')">
		<?php 
		if ($rol_codigo!='0'){ 
		  $ssql="select plataforma_id, plataforma_descrip";
		  $ssql.=" FROM [SAPLCCC320].mapa_activo_puestos.dbo.vplataformas ";
		  $ssql.=" WHERE plataforma_estado =1 ";
		  $ssql.=" Order by 2 ";
          
          $resul = $lib->consultar_datos_sql($ssql);
          if (count($resul)>0){
            foreach($resul as $rs){
            	echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
            }
          }
		}
		?>
		</select>
	</td>
    <td colspan="2">
		<select class="Select" id="lstSeleccionados" name="lstSeleccionados" size="25" style="width:100%" multiple ondblclick="return Mover('frm', 'lstSeleccionados','lstPersonal')">
		<?php 
		if ($rol_codigo!='0'){
			$ssql="select pl.plataforma_id, pv.plataforma_descrip";
			$ssql.=" FROM plataforma_lector pl inner join [SAPLCCC320].mapa_activo_puestos.dbo.vplataformas vp on pl.plataforma_id=vp.plataforma_id";
			$ssql.=" WHERE  pl.plataforma_id=" . $rol_codigo;
			$ssql.=" Order by 2 ";
			
            $resul = $lib->consultar_datos_sql($ssql);
            if (count($resul)>0){
                foreach($resul as $rs){
                	echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
                }
            }
		}
		?>
		</select>
	</td>
	
  </tr>
  <tr class="FieldCaptionTD">
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<br>
<table width="400" border="0" cellspacing="1" cellpadding="1" class="FormTABLE" align="center">
<tr>
	<td class='FieldCaptionTD' align='right'>IP</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_modelo' id='lector_modelo' value="<?php echo $lector_modelo?>" maxlength='15' style='width:120px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Puerto</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='lector_modelo' id='lector_modelo' value="<?php echo $lector_modelo?>" maxlength='80' style='width:50px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Tipo Acceso</td>
	<td class='DataTD'>
		<input type="radio" name="group2" value="E"> Entrada
		<input type="radio" name="group2" value="S"> Salida	
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='lector_activo' id='lector_activo' value='1' <?php if ($lector_activo=="1") echo "Checked"; ?>>
		
	</td>
</tr>
</table>
<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<input class=button type="button" id="cmd3" onClick="guardar()" value="Guardar" style="width:80px" />&nbsp;
		<input class=button type="button" id="cmd4" onClick="self.location.href='main_plataforma_lector.php'" value="Cerrar"  style="width:80px" />
	</td>
  </tr>
</table>

<input type="hidden" id="hddaccion" name="hddaccion" value="" />
<input type="hidden" id="hddcodigos" name="hddcodigos" value="" />

</form>

</body>
</html>