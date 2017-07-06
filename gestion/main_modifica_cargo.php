<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/clsEmpleados.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Asignaciones.php");

$responsable_codigo=0;
$nuevo_cargo_codigo=0;
$o = new Empleados;
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$a = new ca_asignaciones();
$a->MyUrl = db_host();
$a->MyUser= db_user();
$a->MyPwd = db_pass();
$a->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

//$Usuario = $_SESSION['usuario_id'];

$nombre_usuario="";
$area_descripcion="";
$cargo_codigo="";
$hddbuscar="";
$nombre="";
$jefe = ""; // responsable area
$fecha = "";
$tipo=1;
$area ="";
$arr="";

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area      			= $u->area_codigo;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; // responsable area
$fecha     			= $u->fecha_actual;

//areas de su responsable
$areas_subordinadas=$u->obtener_areas_responsable();

if (isset($_GET["cargo"])) $cargo_codigo = $_GET["cargo"];
if (isset($_GET["t"])) $tipo = $_GET["t"];

if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='SVE'){
		$arr = split(',',$_POST["hddcodigos"]);
		for($i=0; $i<sizeof($arr); $i++){
		      
			$o->empleado_codigo=$arr[$i];
			
			//echo "Usuario".$_SESSION["empleado_codigo"]."cboCargo".$_POST["nuevo_cargo_codigo"]."EMPLEADO".$o->empleado_codigo;
			$rptad = $o->Actualizar_Campo(11, $_POST["nuevo_cargo_codigo"], '', $_SESSION["empleado_codigo"]);
			$rptad = 'OK';
			if ($rptad=='OK'){
                $rpta= "OK";
			}
		}

	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Formar Grupo</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
function agrupar(){
	var codigos='';
	
	if (document.frm.nuevo_cargo_codigo.value==0){
		alert('Seleccione Responsable');
		document.frm.nuevo_cargo_codigo.focus();
		return false;
	}

	for(i=0; i< document.frm.length; i++ ){
		if (frm.item(i).type=='checkbox'){
			 if ( frm.item(i).checked ){
				if (codigos==''){
					codigos= frm.item(i).value;
				}else{
					codigos+= ',' + frm.item(i).value;
				}
			}
		}
	}
	if (codigos==''){
		alert('Seleccione registros de Empleados');
		return false
	}
	
	if (confirm('Enviar empleados seleccionados al Responsable?')==false) return false;
	
	document.frm.hddaccion.value='SVE';
	document.frm.hddcodigos.value= codigos;
	document.frm.submit();
	
}
function Consultar_equipo(){
 //self.location.href="mi_equipo.php";
 if (document.frm.responsable_codigo.value==0){
		alert('Seleccione Responsable');
		document.frm.responsable_codigo.focus();
		return false;
	}
 pagina="ver_equipo.php?codigo=" + document.frm.responsable_codigo.value;
 var valor = window.showModalDialog(pagina, "Equipo","dialogWidth:800px; dialogHeight:600px");

}


function BuscarCargo(){
 	var valor = window.showModalDialog("cargos.php?nombre=" + document.frm.cargo_descripcion.value , "Cargos","dialogWidth:500px; dialogHeight:500px");
	if (valor == "" || valor == "0" ){
		 return false;
	}
	arr_valor = valor.split("�");
	document.frm.cargo_codigo.value = arr_valor[0];
	document.frm.cargo_descripcion.value =  arr_valor[1];
 }
 function Buscar(){
  var cargo_codigo=document.frm.cargo_codigo.value;
   document.frm.hddbuscar.value='OK';
   document.frm.action +="?t=" + document.frm.hddtipo.value + "&cargo=" + cargo_codigo;
   document.frm.submit();
 }

 function ver_supervisor(codigo){
  var valor = window.showModalDialog("ver_supervisor.php?codigo=" + codigo, "Equipo","dialogWidth:350px; dialogHeight:350px");
 }
 function Quitar(codigo){
	if (confirm('Seguro de quitar al empleado del grupo')==false) return false;
	document.frm.hddaccion.value='DLT';
	document.frm.hddcodigos.value= codigo;
	document.frm.submit();
}
</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' onSubmit='javascript:return ok();'>
<CENTER class="FormHeaderFont">Modificar Cargos</CENTER>
<table class='DataTD' align=center  width='53%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='2' >Filtro de Empleados
		</td>
	 </tr>
	<tr>
        <td align="right" width="80px">Por Nombre&nbsp;</td>
		<td ><input class='Input' name='txtNombre' id='txtNombre' type='text' value='<?php echo $nombre ?>' size=51>
        <td>
      </tr>
     <tr>
		<td align='right'>Cargo</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			$ssql = "SELECT cargo_codigo, cargo_descripcion from vdatos ";
			$ssql.= " WHERE area_codigo in (" . $areas_subordinadas . ") ";
            $ssql.= " AND cargo_codigo in (754,633) ";
			$ssql.= " group by cargo_codigo, cargo_descripcion ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "cargo_codigo";
			$combo->value = $cargo_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
		<td colspan=2 align=right>
			<input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar'  class='Button' onclick="return Buscar();" style='width:50px'>
		</td>
	</tr>
</table>
<br>
<table class='DataTD' align=center  width='53%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
      <td class='CA_FieldCaptionTD' align="center">
		Seleccionar Cargo&nbsp;
	   </td>
	</tr>
	<tr>
		<td align=center class="CA_DataTD" >
			<?php
			
			$ssql = "select gc.Cargo_Codigo, i.Item_Descripcion from Grupos_Cargos gc inner join Items i on ";
			$ssql.= " gc.Cargo_Codigo = i.Item_Codigo";
			$ssql.= " where Grupo_O_Codigo = 2 and Grupo_Cargo_Activo = 1 and i.Item_Activo = 1 ";
            $ssql.= "  and gc.Cargo_Codigo IN (754,633) ";
			$ssql.= " order by 2 ";	
			
			

			$combo->query = $ssql;
			$combo->name = "nuevo_cargo_codigo";
			$combo->value = $nuevo_cargo_codigo."";
			$combo->more = "class=select style='width:90%''";
			$rpta = $combo->Construir();
			echo $rpta;
		    ?>
		</td>

  </tr>
  <tr align="center" >
    <td class="DataTD">
	 <input class=button type="button" id="cmdAgrupar" onClick="agrupar()" value="Asignar"  style="width:80px">
     <!--<input class=button type="button" id="cmdVer" onClick="Consultar_equipo()" value="Ver Grupo" style="width:80px">-->
	 <input class=button type="button" id="cmdCerrar" onClick="self.location.href='../menu.php'" value="Cerrar" style="width:80px">
	</td>
<tr>
</table>

<br>
<table class='FormTable' align="center" border="0" cellPadding="0" cellSpacing="0" style="width:98%">
 <tr align="center" >
    <td class="ColumnTD" style='width:10px'>Nro.</td>
    <td class="ColumnTD" style='width:10px'>Sel</td>
    <td class="ColumnTD" >C�digo</td>
    <td class="ColumnTD">Nombre</td>
	<td class="ColumnTD">Area</td>
	<td class="ColumnTD">Cargo</td>
	<td class="ColumnTD">Asignaci&oacute;n</td>
<!--	<td class="ColumnTD" width="40px">Quitar</td> -->
</tr>
<?php
if (isset($_POST['hddbuscar'])){
	if($_POST["hddbuscar"]=='OK'){
	 $cargo_codigo=$_POST["cargo_codigo"];
	 $nombre=$_POST["txtNombre"];
	}
 }
	$cadena=$a->empleados_rac($areas_subordinadas,$cargo_codigo,$nombre,$tipo);
	echo $cadena;
?>
</table>
<br>
<br>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center">
		<input type="button" id="cmd4" onClick="self.location.href='../menu.php'" value="Cerrar" class="Button" style="width:80px">
	</td>
  </tr>
</table>
<input type="hidden" id="hddarea" name="hddarea" value="<?php echo $area ?>">
<input type="hidden" id="hddgrupo" name="hddgrupo" value="">
<input type="hidden" id="hddrol" name="hddrol" value="">
<input type="hidden" id="hddcodigos" name="hddcodigos" value="">
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<input type="hidden" id="hddbuscar" name="hddbuscar" value="">
<input type='hidden' id='hddtipo' name='hddtipo' value="1" >
</form>
</body>
</html>