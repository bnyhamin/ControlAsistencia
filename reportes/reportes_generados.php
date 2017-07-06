<?php header("Expires: 0"); 
  
  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/Constantes.php"); 
  require_once("../../Includes/mantenimiento.php"); 
  //require_once("../../Includes/Seguridad.php");
require_once("../includes/MyGrillaEasyUI.php");
  require_once("../includes/clsCA_Reportes.php");
   
	$body="";
	$npag = 1;
	$orden = "Codigo";
	$buscam = "";
	$torder="DESC";
	$registro="";

$id = $_SESSION["empleado_codigo"];
	
	if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
	if (isset($_POST["orden"])) $orden = $_POST["orden"];
	if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
	
	if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
	if (isset($_GET["orden"])) $orden = $_GET["orden"];
	if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
	if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
	
	//tereportes tere = new tereportes();
	if (isset($_GET["eliminar"])) $eliminar = $_GET["eliminar"];
	if (isset($_GET["registro"])) $registro = $_GET["registro"];
	
	$r = new ca_reportes();
	if ($registro!=0) {
        if ($eliminar=="ok") { 
		
			$r->MyUrl = db_host();
			$r->MyUser= db_user();
			$r->MyPwd = db_pass();
			$r->MyDBName= db_name();

            $r->Rep_Codigo=$registro;
            $rpta = $r->Delete();
            if($rpta!="OK"){
			 echo "Error: " . $rpta;
			}
          }
		}
?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Bandeja de Reportes</title>
<meta http-equiv="pragma" content="no-cache" />
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css" />
<?php  require_once('../includes/librerias_easyui.php');?>

<script language="javascript">
var server="<?php echo $ip_app ?>";
function Ver() {
	var rpta=PooGrilla.Registro();
	if (rpta != '' ) {
    frames['ifrm'].location.href ="http://" + server + "/tmreportes/Gap/ver.jsp?rep_codigo=" + rpta;
	}
}
function Eliminar() {
	var rpta=PooGrilla.Registro();
	if (rpta != '' ) {
    document.frm.action += "?eliminar=ok&registro=" + rpta ;
    document.frm.submit();
	}
}
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<CENTER class="FormHeaderFont">Bandeja de Reportes</CENTER>
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <input class=buttons type='button' value='Ver' id='cmdVer' name='cmdAdicionar'  LANGUAGE=javascript onClick="return Ver()" style='width:80px;' />
            <input class=buttons type='button' value='Eliminar' id='cmdEliminar' name='cmdEliminar'  LANGUAGE=javascript onClick="return Eliminar()" style='width:80px;' />
        </TD>
    </TR>
</table>
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
	
    $from = " CA_Reportes"; 
    $objr->setFrom($from);
	$where= " usuario_id=" . $id . " ";
	$objr->setWhere($where);
	$objr->setSize(15);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Nombre";
	$arrAlias[3] = "Fecha";
	
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "Rep_Codigo";
    $arrCampos[1] = "Rep_Codigo";
    $arrCampos[2] =	"Rep_Nombre";
	$arrCampos[3] = "convert(varchar(10),Rep_Fecha,103) + ' ' +  convert(varchar(8),Rep_Fecha, 108)";
    $objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");

?>
<iframe src="" id="ifrm" name="ifrm" width="0px" height="0px"></iframe>
<input type='hidden' name="hddaccion" id='hddaccion' value="">
</form>
</body>
</html>