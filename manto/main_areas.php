<?php header("Expires: 0"); 
  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  //require_once("../../includes/Seguridad.php");
require_once("../includes/MyGrillaEasyUI.php");
  
	$body="";
	$npag = 1;
	$orden = "Codigo";
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
<title><?php echo tituloGAP() ?>- Catálogo de Areas</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
<?php  require_once('../includes/librerias_easyui.php');?>

</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">

<CENTER class="FormHeaderFont">Catálogo de Areas</CENTER>
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
	
        $from = " Areas "; 
        $objr->setFrom($from);
	$where= "";
	$objr->setWhere($where);
	$objr->setSize(15);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	//$objr->setMultipleSeleccion(false);
	$objr->setNoSeleccionable(true);
	$objr->setMultipleSeleccion(true);
	// Arreglo de Alias de los Campos de la consulta
	$arrAlias[0] = "root";
	$arrAlias[1] = "Codigo";
	$arrAlias[2] = "Area";
	$arrAlias[3] = "Estado";
	
	// Arreglo de los Campos de la consulta
	$arrCampos[0] = "Area_Codigo";
	$arrCampos[1] = "Area_Codigo";
	$arrCampos[2] =	"Area_Descripcion";
	$arrCampos[3] = "CASE WHEN Area_Activo = 1 then 'Activo' else 'Desactivo' end ";
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$objr->noCheckCampos[0] = "Estado";
	$objr->noCheckCondicion[0] = "Desactivo";
	
	$body = $objr->Construir();  //ejecutar
	
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
?>
</form>
</body>
</html>