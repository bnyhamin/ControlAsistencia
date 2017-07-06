<?php header("Expires: 0"); 
  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  //require_once("../../includes/Seguridad.php");
 require_once("../includes/MyGrillaEasyUI.php");
  
	$body="";
	$npag = 1;
	$orden = "Nombres";
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
<title><?php echo tituloGAP() ?>- Catálogo de Personal</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<?php  require_once('../includes/librerias_easyui.php');?>

</head>
<body class="PageBODY"  onLoad="return WindowResize(10,20,'center')">

<CENTER class="FormHeaderFont">Catálogo de Personal</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
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

	$objr->setFrom($from);
	$where= "(Empleado_Area.Empleado_Area_Activo = 1) ";
	$objr->setWhere($where);
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Nombres";
	$arrAlias[3] = "DNI";
	$arrAlias[4] = "Area";
	$arrAlias[5] = "Estado";
	
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "Empleados.Empleado_Codigo";
    $arrCampos[1] = "Empleados.Empleado_Codigo";
    $arrCampos[2] =	"Empleados.Empleado_Apellido_Paterno  + ' ' + Empleados.Empleado_Apellido_Materno +  ' '  + Empleados.Empleado_Nombres";
	$arrCampos[3] =	"Empleado_Dni";   
	$arrCampos[4] =	"Area_descripcion";
    $arrCampos[5] = "CASE WHEN Empleados.Estado_Codigo= 1 then 'Activo' else 'Desactivo' end ";
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
</body>
</html>