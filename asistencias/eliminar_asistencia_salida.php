<?php header("Expires: 0"); ?>
<?php

  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  //require_once("../../includes/mantenimiento.php");
  require_once("../../Includes/Constantes.php"); 
  require_once("../../Includes/MyGrilla.php");
  require_once("../includes/clsCA_Empleados.php");
  require_once("../includes/clsCA_Usuarios.php");
  require_once("../includes/clsCA_Empleado_Rol.php"); 
  require_once("../includes/clsCA_Asistencias.php"); 
$body="";
$npag = 1;
$orden = "Nombres";
$buscam = "";
$torder="ASC";



if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
if (isset($_GET["cboTOrden"])) $torder = $_GET["cboTOrden"];
if (isset($_GET["codigo"])) $codigo = $_GET["codigo"];

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$o = new ca_empleados();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$obj = new ca_empleado_rol();
$obj->MyUrl = db_host();
$obj->MyUser= db_user();
$obj->MyPwd = db_pass();
$obj->MyDBName= db_name();

$ca = new ca_asistencia();
$ca->MyUrl = db_host();
$ca->MyUser= db_user();
$ca->MyPwd = db_pass();
$ca->MyDBName= db_name();

$id=$_SESSION["empleado_codigo"];
$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; // responsable area
$fecha     			= $u->fecha_actual;

$obj->empleado_codigo=$id;
$obj->rol_codigo=3;
$rpta=$obj->Verifica_rol(); //rol administrador
if($rpta=='OK'){
 $area=0;
}else{
  $obj->rol_codigo=7; //rol supervisor rol cambio_clave_todos
  $rpta=$obj->Verifica_rol();
  if($rpta=='OK') $area = 0;
  else{
     $obj->rol_codigo=4; //rol apoyo //rol cambio_clave_area
  	 $rpta=$obj->Verifica_rol();
  	 if($rpta=='OK') $area = $u->area_codigo;
     else{
		 $area=$u->area_codigo;
	 }
  }
}


if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='ELISAL'){
	  $ca->empleado_codigo =$codigo;
	  $rpta = $ca->Eliminar_Salida();
	  if($rpta=='OK'){
	  ?><script language='javascript'>
		  alert('La marcacion de salida fue eliminada para este usuario!!');
	  </script>
	  <?php
   }else{
     echo "Error : " . $rpta;
   }
  }
}


$actual = $ca->Dia_Actual();
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Eliminar marca de fin de turno</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
</head>
<SCRIPT LANGUAGE=javascript c>
<!--
function cmdModificar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
    self.location.href="cambiar_clave.php?codigo=" + rpta + "&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&cboTOrden=<?php echo $torder ?>";
    //&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	}
}
function cmdAsignar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
    	msj = '¿Esta seguro de eliminar la asistencia de salida de este empleado?';
	if (confirm(msj)== false){
		return false;
	}
	document.frm.action +="?codigo=" + rpta;
	document.frm.hddaccion.value="ELISAL";
	document.frm.submit();
	}
}
//-->
</SCRIPT>
<body class="PageBODY"  onLoad="return WindowResize(10,20,'center')">
<CENTER class="FormHeaderFont">Eliminar marca de fin de turno: <?php echo $actual[0] ?></CENTER>
<br>
<?php if($area!=0){?>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td align="center" class='CA_FormHeaderFont' colspan='2'><b><?php echo $area_descripcion ?></b></td>
  </tr>
</table>
<?php } ?>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
		    <input class=button type='button' value='Eliminar salida' id='cmdAsignar' name='cmdAsignar'  LANGUAGE=javascript onclick='return cmdAsignar_onclick()' style='width:120px;' />
            <!--<INPUT class=button type='button' value='Modificar Clave' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return cmdModificar_onclick()' style='width:120px;'>-->
        </TD>
    </TR>
</table>
<?php
	
	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	$objr->setNoSeleccionable(false);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
    $from = " Empleados "; 
    $from .= " INNER JOIN ";
    $from .= " Empleado_Area ON ";
    $from .= " Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo ";
    $from .= " INNER JOIN ";
    $from .= " Areas ON ";
    $from .= " Empleado_Area.Area_Codigo = Areas.Area_Codigo ";
    $from .= " INNER JOIN ";
    $from .= " ca_asistencias ON ";
    $from .= " ca_asistencias.empleado_codigo = Empleados.empleado_codigo ";
	$objr->setFrom($from);
	$where= "(Empleado_Area.Empleado_Area_Activo = 1) and (empleados.estado_codigo=1) and (ca_asistencias.asistencia_fecha = '".$actual[1]."' and ca_asistencias.asistencia_salida is not null)";
	if($area !=0) $where .=" and Empleado_Area.Area_Codigo=" . $area;
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Nombres";
	$arrAlias[3] = "DNI";
	$arrAlias[4] = "Area";
	$arrAlias[5] = "Activo";
	
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "Empleados.Empleado_Codigo";
    $arrCampos[1] = "Empleados.Empleado_Codigo";
    $arrCampos[2] =	"Empleados.Empleado_Apellido_Paterno  + ' ' + Empleados.Empleado_Apellido_Materno +  ' '  + Empleados.Empleado_Nombres";
	$arrCampos[3] =	"Empleado_Dni";   
	$arrCampos[4] =	"Area_descripcion";
    $arrCampos[5] = "CASE WHEN Empleados.Estado_Codigo= 1 then 'Si' else 'No' end ";
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	// echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
?>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>
</body>
</html>