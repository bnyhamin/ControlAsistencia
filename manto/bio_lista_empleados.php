<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/MyGrilla.php");

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
<title><?php echo tituloGAP() ?>- Asignaci&oacute;n de Empleados a Plataformas </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>

<script language='javascript'>

 function cmdSeleccionarEmpleado_onclick(empleado) {
     
	  var arrTmp0 = empleado.split('@');
	  var empleadoCodigo = arrTmp0[0];
	  var empleadoDNI = arrTmp0[1];
      var arrTmp1 = arrTmp0[2].split('_');
	  var empleadoNombre = arrTmp1[0]+' '+arrTmp1[1]+' '+arrTmp1[2];
	  
	  var posicion_x; 
      var posicion_y; 
      posicion_x=(screen.width/2)-(680/2); 
      posicion_y=(screen.height/2)-(500/2); 
      
      var vm=window.opener;
	  vm.document.forms[0]['empleado_codigo'].value=empleadoCodigo;
	  vm.document.forms[0]['tarjeta_proximidad_dni'].value=empleadoDNI;
	  vm.document.forms[0]['tarjeta_proximidad_nombre'].value=empleadoNombre;
	  
	  window.close();
	
	}
  

</script>

</head>


<body class="PageBODY" >

<CENTER class="FormHeaderFont">Listado de Empleados</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">


<?php

	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	$objr->setNoSeleccionable(true); 
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from  = "  vdatos e  ";
	
	$objr->setFrom($from);
  
	$where = "";	

	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Empleado";
    $arrAlias[2] = "Dni";
   	$arrAlias[3] = "Area";
    $arrAlias[4] = "Selecccionar";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "e.empleado_codigo";
    $arrCampos[1] = "e.empleado"; 
    $arrCampos[2] =	"e.empleado_dni";
	$arrCampos[3] = "e.area_descripcion";
    $arrCampos[4] = "'<center><img id=' + cast(e.empleado_codigo as varchar)+'@'+cast(e.empleado_dni as varchar) +'@'+replace(e.empleado,' ','_')+' src=\"../../Images/asistencia/inline011.gif\" border=0 style=cursor:hand onclick=\"cmdSeleccionarEmpleado_onclick(this.id)\" title=Seleccionar></center>'";
    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
 
  
?>
     
</form>
</body>
</html>
