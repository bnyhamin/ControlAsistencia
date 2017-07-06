<?php header("Expires: 0"); 


require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../includes/MyGrillaEasyUI.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();
  
$body="";
$npag = 1;
$orden = "id";
$buscam = "";
$torder="DESC";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
$esadmin="NO";
$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin = $e->Query_Rol_Admin();
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>Resultado Archivos Cargados</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<?php  require_once('../includes/librerias_easyui.php');?>

<script>

function cmdDetalle_onclick() {
    var rpta=PooGrilla.Registro();
    if (rpta != '' ){
    	var arr = rpta.split('¬');
		CenterWindow("procesos.php?opcion=log01&carga_codigo=" + arr[2] ,"Reporte",200,180,"yes","center");
    }
}
function cmdContenido_onclick() {
    var rpta=PooGrilla.Registro();
    //alert(rpta.split('¬')[1]);
    if (rpta != '' ){
    	var arr = rpta.split('¬');
	    CenterWindow("cargas/"+arr[1],'Texto',600,400,1)
    }
}

</script>

</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<CENTER class="FormHeaderFont">Resultado Archivos Cargados</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=buttons type='button' value='Ver Detalle de la Carga' id='cmdDetalle' name='cmdDetalle' LANGUAGE=javascript onclick='return cmdDetalle_onclick()' style='width:160px;'>
            <INPUT class=buttons type='button' value='Ver Contenido del Archivo' id='cmdContenido' name='cmdContenido'  LANGUAGE=javascript onclick='return cmdContenido_onclick()' style='width:160px;'>
        </TD>
    </TR>
</TABLE>

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
	$from = " CA_Turno_Carga tc inner join vdatos e on tc.empleado_codigo_carga=e.empleado_codigo ";
	$objr->setFrom($from);
	if ($esadmin=='OK'){
        $where= " tc.empleado_codigo_carga = tc.empleado_codigo_carga ";
	}else{
        $where= " tc.empleado_codigo_carga = ".$_SESSION['empleado_codigo'];
	}
	$objr->setWhere($where);
	$objr->setSize(20);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "id";
    $arrAlias[2] = "Empleado_Que_Carga";
    $arrAlias[3] = "F_Registro";
	$arrAlias[4] = "Archivo";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "cast(id as varchar)+'¬'+nombre_archivo_carga+'¬'+tl_carga_codigo";
    $arrCampos[1] = "id"; 
    $arrCampos[2] = "e.empleado"; 
    $arrCampos[3] =	"convert(varchar(20),tc.fecha_registro,113)";
	$arrCampos[4] = "nombre_archivo_origen";
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	
	echo $body;
	echo "<br>";
	echo RetornoMenu("../menu.php","main_turnos_empleado.php");
?>
</form>
</body>
</html>