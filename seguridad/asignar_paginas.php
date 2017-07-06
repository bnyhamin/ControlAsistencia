<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
//require_once("../../includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Pagina_Rol.php"); 
require_once("../../Includes/clsLibreria.php");

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
<title><?php echo tituloGAP() ?>- Asignar Páginas</title>
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
<CENTER class="FormHeaderFont">Asignación de Páginas</CENTER>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>

<table width="400" border="0" cellspacing="1" cellpadding="1" class="FormTABLE" align="center">
  <tr>
    <td class="ColumnTD" align="right">Usuario&nbsp;</td>
    <td class="DataTD" align="left">&nbsp;<?php echo $nombre_usuario ?></td>
  </tr>
  <!--<tr>
    <td class="ColumnTD" align="right">Area&nbsp;</td>
    <td class="DataTD" align="left">&nbsp;<?php //echo $area_descripcion ?></td>
  </tr>-->
</table>
<br>
<?php 
	if ($mensaje!='OK') echo $mensaje;
?>
<table width="80%" border="1" cellspacing="0" cellpadding="0" align="center" class="FormTABLE">
  <tr>
    <td width="350px" align="center" class="ColumnTD">Páginas</td>
    <td width="40px" rowspan="3" class="FieldCaptionTD" align="center" valign="middle">
		<input type="button" id="cmd1" value=">>" class="Button" onclick="return Mover('frm', 'lstPersonal','lstSeleccionados')" />
		<br><br>
		<input type="button" id="cmd2" value="<<" class="Button" onclick="return Mover('frm', 'lstSeleccionados','lstPersonal')" />
	</td>
    <td width="100px" align="center" class="ColumnTD">Seleccione Rol</td>
    <td width="200px"  class="ColumnTD">
		<?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			
			$sql4="SELECT rol_codigo as codigo, rol_descripcion FROM ca_roles "; 
			$sql4 .=" WHERE rol_activo=1 order by 2 asc"; 

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
		  $ssql="select Pagina_Codigo, Pagina_Url";
		  $ssql.=" FROM ca_paginas ";
		  $ssql.=" WHERE pagina_activo =1 ";
		  $ssql .=" and pagina_codigo not in (select pagina_codigo ";
		  $ssql .=" from ca_pagina_rol where rol_codigo=" . $rol_codigo . " and pagina_rol_activo=1)";
		  $ssql.=" Order by 2 ";
          
          $resul = $lib->consultar_datos_sql($ssql);
          if (count($resul)>0){
            foreach($resul as $rs){
            	echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
            }
          }
		/*$result = consultar_sql($ssql);
		if (mssql_num_rows($result)>0){
			$rs= mssql_fetch_row($result);
			while ($rs){
				echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
				$rs= mssql_fetch_row($result);
			}
		  }*/
		}
		?>
		</select>
	</td>
    <td colspan="2">
		<select class="Select" id="lstSeleccionados" name="lstSeleccionados" size="25" style="width:100%" multiple ondblclick="return Mover('frm', 'lstSeleccionados','lstPersonal')">
		<?php 
		if ($rol_codigo!='0'){
			$ssql="select ca_paginas.Pagina_Codigo,ca_paginas.Pagina_Url";
			$ssql.=" FROM ca_pagina_rol ";
			$ssql.=" INNER JOIN ca_paginas on ca_paginas.pagina_codigo=ca_pagina_rol.pagina_codigo ";
			$ssql.=" WHERE ca_paginas.pagina_activo=1 and pagina_rol_activo=1 and rol_codigo=" . $rol_codigo;
			$ssql.=" Order by 2 ";
			
            $resul = $lib->consultar_datos_sql($ssql);
            if (count($resul)>0){
                foreach($resul as $rs){
                	echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
                }
            }
            
			/*$result = consultar_sql($ssql);
			if (mssql_num_rows($result)>0){
				$rs= mssql_fetch_row($result);
				while ($rs){
					echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
					$rs= mssql_fetch_row($result);
				}
			}*/
		}
		?>
		</select>
	</td>
	<?php //echo $ssql;?>
  </tr>
  <tr class="FieldCaptionTD">
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<input class=button type="button" id="cmd3" onClick="guardar()" value="Guardar" style="width:80px" />&nbsp;
		<input class=button type="button" id="cmd4" onClick="self.location.href='../menu.php'" value="Cerrar"  style="width:80px" />
	</td>
  </tr>
</table>

<input type="hidden" id="hddaccion" name="hddaccion" value="" />
<input type="hidden" id="hddcodigos" name="hddcodigos" value="" />

</form>

</body>
</html>