<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Asignaciones.php"); 
require_once("../includes/clsCA_Asignacion_Empleados.php"); 
require_once("../includes/clsCA_Usuarios.php");

$o = new ca_asignaciones();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$ae = new ca_asignacion_empleados();
$ae->MyUrl = db_host();
$ae->MyUser= db_user();
$ae->MyPwd = db_pass();
$ae->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

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

if (isset($_GET["cargo"])) $cargo_codigo = $_GET["cargo"];
if (isset($_GET["t"])) $tipo = $_GET["t"];

if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='SVE'){
		//-- agrupar
		$o->responsable_codigo =$_SESSION["empleado_codigo"];
		$o->empleado_codigo_asigna = $_SESSION["empleado_codigo"];
		$rpta = $o->Addnew();
	    if($rpta=='OK'){
			//-- se creo cabecera, ahora crear hijos
			$arr = split(',',$_POST["hddcodigos"]);
			for($i=0; $i<sizeof($arr); $i++){
				$ae->empleado_codigo=$arr[$i];
				$ae->responsable_codigo = $_SESSION["empleado_codigo"];
				$ae->empleado_codigo_asigna = $_SESSION["empleado_codigo"];
				//--desactivar en otros grupos
				$rptad = $ae->Desactivar_asignacion();
				if ($rptad=='OK'){
					$rptaa = $ae->Addnew();
					//echo $sql;
					if ($rptaa!='OK'){
						 $rpta = "Error al asignar empleados.";
						  echo $rpta;
					 }else{
						$rpta= "OK";
					 }
				}
			} //exit for
		}
		if($rpta=='OK'){
		?><script language='javascript'>
		   alert('Asignacion Grupal satisfactorio!!');
		  </script>
		<?
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
		alert('Seleccione registros de operadores');
		return false
	}
	if (confirm('Enviar empleados seleccionados al grupo?')==false) return false;
	document.frm.hddaccion.value='SVE';
	document.frm.hddcodigos.value= codigos;
	document.frm.submit();
	
}
function Consultar_equipo(){
 self.location.href="mi_equipo.php";
}


function BuscarCargo(){
 	var valor = window.showModalDialog("cargos.php?nombre=" + document.frm.cargo_descripcion.value , "Cargos","dialogWidth:500px; dialogHeight:500px");
	if (valor == "" || valor == "0" ){
		 return false;
	}
	arr_valor = valor.split("¬");
	document.frm.cargo_codigo.value = arr_valor[0];
	document.frm.cargo_descripcion.value =  arr_valor[1];
 }
 function Buscar(){
  var cargo_codigo=document.frm.cargo_codigo.value;
  if(document.frm.rdo1.checked){
  	tipo=document.frm.rdo1.value;
  }
  else{ 
      if(document.frm.rdo2.checked) tipo=document.frm.rdo2.value;
     }
   document.frm.hddbuscar.value='OK';
   document.frm.action +="?t=" + tipo + "&cargo=" + cargo_codigo;
   document.frm.submit();
 }

 function ver_supervisor(codigo){
  var valor = window.showModalDialog("ver_supervisor.php?codigo=" + codigo, "Equipo","dialogWidth:350px; dialogHeight:350px");
 }
</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']  ?>' method='post' onSubmit='javascript:return ok();'>
<CENTER class="FormHeaderFont">Formar Grupo</CENTER>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" class='CA_FormHeaderFont' colspan='2'><b><?php echo $area_descripcion ?></b></td>
  </tr>
</table>
<br>
<table align='center' width="60%" border="0">
 <tr>
    <td width='40%' align=right><b>Supervisor : </b></td>
	<td class='CA_FormHeaderFont' align=left><?php echo $nombre_usuario?></td>
 </tr>
</table>
<br>
<table class='DataTD' align=center  width='53%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='2' >Filtro de Empleados
		</td>
	 </tr>
	<tr>
        <td align="right" width="80px">Por Nombre&nbsp;</td>
		<td ><input class='Input' name='txtNombre' id='txtNombre' type='text' value='<?php echo $nombre ?>'   style='width:80px'>
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
						
			$ssql = "SELECT Item_Codigo, Item_Descripcion FROM Items ";
			$ssql.= " WHERE (Tabla_Codigo = 11) AND (Item_Activo = 1) ";
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
	<TR>
	    <TD align='right'>Area:
        </TD>
		<TD align="left">
		  &nbsp;&nbsp;<b>Mi area</b><input type='radio' id='rdo1' name=rdo value="1" <?php if($tipo==1) echo "checked" ?>>
		  &nbsp;&nbsp;&nbsp;<b>Otras areas</b><input type='radio' id='rdo2' name=rdo value="2" <?php if($tipo==2) echo "checked" ?>>
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar'  class='Button' onclick="return Buscar();" style='width:50px'>
	   </td>
	</TR>
</table>
<br>
<table class="FormTABLE" align="center" border="1" cellPadding="0" cellSpacing="1" style="width:95%">
 <tr align="left" >
    <td class="DataTD">
	 <input class=button type="button" id="cmdAgrupar" onClick="agrupar()" value="Agrupar"  style="width:80px">
     <input class=button type="button" id="cmdVer" onClick="Consultar_equipo()" value="Mi Grupo" style="width:80px">
	 <input class=button type="button" id="cmdCerrar" onClick="self.location.href='../menu.php'" value="Cerrar" style="width:80px">
	</td>
<tr>
</table>
<table class='FormTable' align="center" border="0" cellPadding="0" cellSpacing="0" style="width:95%">
 <tr align="center" >
    <td class="ColumnTD" style='width:10px'>Nro.
    </td>
    <td class="ColumnTD" style='width:10px'>Sel
    </td>
    <td class="ColumnTD" >Código
    </td>
    <td class="ColumnTD">Nombre
    </td>
	<td class="ColumnTD">Area
    </td>
	<td class="ColumnTD">Cargo
    </td>
	<td class="ColumnTD">Asignaci&oacute;n
    </td>		
</tr>
<?php
if (isset($_POST['hddbuscar'])){
	if($_POST["hddbuscar"]=='OK'){	
	 $cargo_codigo=$_POST["cargo_codigo"];
	 $nombre=$_POST["txtNombre"];
	 $tipo=$_POST["rdo"];
	}
 }	 
	$cadena=$o->empleados_para_equipo($area,$cargo_codigo,$nombre,$tipo);
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
</form>
</body>
</html>