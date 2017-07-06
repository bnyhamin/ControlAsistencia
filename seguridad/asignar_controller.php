<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
//require_once("../../includes/mantenimiento.php");
require_once("../../Includes/Constantes.php");
//require_once("../../includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Controller.php");
require_once("../../Includes/clsLibreria.php");

$cboempleado_codigo=0;   // ex $rol_codigo
$area_codigo="";         //ex empleado_codigo
$emp_codigo="";
$ruta="";
$mensaje="";


$lib = new libreria();
$lib->MyUrl = db_host();
$lib->MyUser= db_user();
$lib->MyPwd = db_pass();
$lib->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area      			= $u->area_codigo;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; 
$fecha     			= $u->fecha_actual;

$o = new ca_controller_area();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

if (isset($_POST["cboempleado_codigo"])) $cboempleado_codigo = $_POST["cboempleado_codigo"];

//--- grabar datos ---
if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='OK'){
	 $cboempleado_codigo = $_POST['cboempleado_codigo'];
	 $area_codigo = $_POST['emp_codigo'];
	 $ruta=$_POST['hddruta'];
	 $o->empleado_codigo = $cboempleado_codigo;
     $o->area_codigo = $area_codigo;
     $o->usuario_modifica=$_SESSION["empleado_codigo"];
     $o->usuario_registro=$_SESSION["empleado_codigo"];
     //echo $ruta;
	 if($ruta=="1-2") $rpta=$o->Asignar_area();
     else{
        if($ruta=="2-1") $rpta=$o->Desasignar_area();
        }
     if ($rpta!='OK') echo $rpta;
  }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asignar Areas</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript">

function guardar(){
	//-- obtener codigos seleccionados
	if(document.frm.cboempleado_codigo.value==0){
		alert('Seleccione Empleado');
		document.frm.cboempleado_codigo.focus();
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
	if (document.frm.cboempleado_codigo.value!=0){
		document.frm.hddaccion.value = "CGE";
	}
	document.frm.submit();
}

function Mover(frm,origen,destino,ret)
{   var myForm, mySelect, myElement;
	myForm = document.forms(frm);
	mySelect = myForm.item(destino);

	with(myForm.item(origen).options)
	{
		for( i = 0; i < length; i++ )
		{
			if( item(i).selected )
			{
				myElement = document.createElement("option");
				myElement.text = options(i).text;
				myElement.value = options(i).value;
				mySelect.add( myElement );
				asignar(myElement.value,ret);
				remove(i);
				i--;
			}
		}
	}
}

function asignar(codigo,ret){
   document.frm.emp_codigo.value=codigo;
   document.frm.hddruta.value=ret;
   document.frm.hddaccion.value="OK";
   document.frm.submit();
}

</script>

</head>



<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<CENTER class="FormHeaderFont">Asignación de Areas</CENTER>

<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>

<!-- Nombre de usuario logueado -->
<table width="400" border="0" cellspacing="1" cellpadding="1" class="FormTABLE" align="center">
  <tr>
    <td class="ColumnTD" align="right">Usuario&nbsp;</td>
    <td class="DataTD" align="left">&nbsp;<?php echo $nombre_usuario ?></td>
  </tr>
</table>

<br>
<?php
	if ($mensaje!='OK') echo $mensaje;
?>

<table width="80%" border="1" cellspacing="0" cellpadding="0" align="center" class="FormTABLE">
  <tr>
	<!-- lista izquierda (areas) -->
	<td width="350px" align="center" class="ColumnTD">Areas</td>
	
	<td width="40px" rowspan="3" class="FieldCaptionTD" align="center" valign="middle">
		<input type="button" id="cmd1" value=">>" class="Button" onclick="return Mover('frm', 'lstPersonal','lstSeleccionados','1-2')" />
		<br><br>
		<input type="button" id="cmd2" value="<<" class="Button" onclick="return Mover('frm', 'lstSeleccionados','lstPersonal','2-1')" />
	</td>
    
	<td width="100px" align="center" class="ColumnTD">Seleccione Empleado</td>
    <td width="200px"  class="ColumnTD">
		<?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

            //query del combo de seleccion de empleado
			$sql4="SELECT ca.empleado_codigo as codigo, ";
			$sql4 .="empleado_apellido_paterno+' '+empleado_apellido_materno+' '+empleado_nombres as empleado ";
			$sql4 .="FROM ca_empleado_rol ca left outer join empleados e on ";
			$sql4 .="ca.empleado_codigo = e.empleado_codigo ";
			$sql4 .="WHERE ca.rol_codigo= 9 and ca.empleado_rol_activo=1 order by 2 asc";
			$combo->query = $sql4;
			$combo->name = "cboempleado_codigo";
			$combo->value = $cboempleado_codigo."";
			$combo->more = "class=select style='width:275px;' onchange=cambiar()";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
	</td>
  </tr>
  <tr>
    <td height="300px">
	
	   <!-- seleccion de primera lista (areas) -->
		<select class="Select" id="lstPersonal" name="lstPersonal" size="25" style="width:100%" ondblclick="return Mover('frm', 'lstPersonal','lstSeleccionados','1-2')">
		<?php
	     
	   if ($cboempleado_codigo!='0'){
		  $ssql="select areas.area_codigo, areas.area_descripcion";
		  $ssql.=" from areas ";
		  $ssql.=" where area_activo = 1 ";
                  //$ssql.=" and area_codigo <> 0 ";//mcortezc
		  $ssql.=" and area_codigo not in (select ca_controller.area_codigo ";
		  $ssql.=" from ca_controller where empleado_codigo=" . $cboempleado_codigo . " and activo=1)";
		  $ssql.=" order by 2 ";
          
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
		
		<!-- lista seleccionada de la derecha (areas) -->
		<select class="Select" id="lstSeleccionados" name="lstSeleccionados" size="25" style="width:100%" ondblclick="return Mover('frm', 'lstSeleccionados','lstPersonal','2-1')">
		<?php
		if ($cboempleado_codigo!='0'){
			$ssql="SELECT c.area_codigo, a.area_descripcion ";
			$ssql.=" FROM ca_controller c inner join areas a on c.area_codigo=a.area_codigo ";
			$ssql.=" WHERE a.area_activo =1 and c.activo=1 and empleado_codigo=" . $cboempleado_codigo;
			$ssql.=" order by 2 ";
            
            $resul = $lib->consultar_datos_sql($ssql);
        if (count($resul)>0){
            foreach($resul as $rs){
            	echo "<option value =" . $rs[0] . " >" . $rs[1] . "</option>/n";
            }
        }
            // echo $ssql;  
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
  </tr>
  <?php //echo $ssql; ?>
  <tr class="FieldCaptionTD">
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<br>

<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<input class=button type="button"  align="center"  id="cmd4" onClick="self.location.href='../menu.php'" value="Cerrar"  style="width:80px" />
	</td>
  </tr>
</table>

<input type="hidden" id="hddcodigos" name="hddcodigos" value="" />
<input type="hidden" id="hddaccion" name="hddaccion" value="" />
<input type="hidden" id="hddruta" name="hddruta" value="" />
<input type="hidden" id="emp_codigo" name="emp_codigo"  value="" />

</form>

</body>
</html>