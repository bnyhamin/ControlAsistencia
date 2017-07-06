<?php header("Expires: 0"); 
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?><script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
<?php
exit;
}
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../../Includes/MyGrilla.php");

//$id_usuario=17041;
$id_usuario=3300;

$e = new Empleados();
$e->MyUrl = db_host();
$e->MyUser = db_user();
$e->MyPwd = db_pass();
$e->MyDBName = db_name();



$rpta = "";
$body="";
$npag = 1;
$orden = "empleado";
$buscam = "";
$torder="ASC";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Registra restricciones empleado </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
</head>
<script>


function cmdRegistra_onclick(){
    var rpta=Registro();
    if (rpta != '' ) {
        
		//CenterWindow("empleado_restricciones.php?empleado_codigo=" + rpta , "ModalChild",600,600,"yes","center");
		//CenterWindow("../../wfm/atributos.php?empleado_codigo=" + rpta , "ModalChild",450,400,"yes","center");
		var xwin=window.open("empleado_restricciones.php?empleado_codigo=" + rpta  , "Ficha","width=700px,height=400px,status=yes,toolbar=no,menubar=no,location=no, align=center, scrollbars=no, resize=no, left=100" );
	xwin.focus();
	}
}

function cmdConsulta_onclick(){
    var rpta=Registro();
    
    if (rpta != '' ) {
    	
   		var xwin=window.open("consulta_restricciones_empleado.php?empleado_codigo=" + rpta  , "Ficha","width=700px,height=400px,status=yes,toolbar=no,menubar=no,location=no, align=center, scrollbars=no, resize=no, left=100" );
	xwin.focus();
    	

		
	}
}

</script>

<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<CENTER class="FormHeaderFont">Registro de Restricciones - Empleado </CENTER>
<form id=frm name=frm method=post action='<?php echo $_SERVER['PHP_SELF'] ?>'>
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="left">

        	<INPUT class=button type='button' value='Adicionar Restricción' id='cmdRegistra' name='cmdRegistra'  LANGUAGE=javascript onclick='return cmdRegistra_onclick()' style='width:180px;' title='Registrar Restricciones'>
        	<INPUT class=button type='button' value='Consultar Restricciones' id='cmdConsulta' name='cmdConsulta'  LANGUAGE=javascript onclick='return cmdConsulta_onclick()' style='width:180px;' title='Consultar Restricciones'>
       	</td>

    </tr>
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
	
	$from= "Empleado_Area(nolock)  ";
    $from .= " INNER JOIN Empleados(nolock) ON Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo ";
    $from .= " INNER JOIN Atributos ON Atributos.empleado_codigo = Empleados.empleado_codigo ";
    $from .= " INNER JOIN Items ON Items.item_codigo= Atributos.item_codigo  ";
    $from .= " INNER JOIN vw_empleado_area_cargo(nolock) on vw_empleado_area_cargo.Empleado_Codigo=Empleados.Empleado_Codigo ";
    $from .= " LEFT OUTER JOIN ca_asignacion_empleados(nolock) ON ca_asignacion_empleados.empleado_codigo=Empleados.empleado_codigo 
 ";
	
	$objr->setFrom($from);
	
 	$where= " (Empleados.Estado_Codigo = 1) AND ";
	$where .= " (Empleado_Area.Empleado_Area_Activo = 1) AND ";
	$where .= " (ca_asignacion_empleados.asignacion_activo=1) AND ";
	$where .= " (items.tabla_codigo=7) AND ";
	$where .= " (atributos.estado_codigo=1) AND";
	$where .= " (ca_asignacion_empleados.responsable_codigo= " . $id_usuario . ")";
	
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
	
 	$arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Empleado";
	$arrAlias[3] = "Modalidad";
	$arrAlias[4] = "Area";
	$arrAlias[5] = "Cargo";  

	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "Empleados.Empleado_Codigo";
    $arrCampos[1] = "Empleados.Empleado_Codigo";
	$arrCampos[2] = "Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres";
	$arrCampos[3] = "items.item_descripcion";
	$arrCampos[4] = "Area_descripcion ";
	$arrCampos[5] = "cargo_descripcion";

    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
?>
</form>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>