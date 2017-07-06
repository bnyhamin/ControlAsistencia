<?php header("Expires: 0");?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyGrilla.php");
require_once("../includes/clsCA_Campanas.php");
require_once("../includes/clsCA_Areas.php");

$area='';
$area_descripcion='';
$body="";
$npag = 1;
$orden = "U_Servicio";
$buscam = "";
$torder="ASC";

$a = new areas();
$a->setMyUrl(db_host());
$a->setMyUser(db_user());
$a->setMyPwd(db_pass());
$a->setMyDBName(db_name());

$o = new ca_campanas();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

if (isset($_GET['area'])){
    $area= $_GET['area'];
}else{
    $area= $_POST['area'];
    if ($_POST['hddaccion']!=''){
        $o->cod_campana=$_POST['servicio'];
        $o->exp_activo=$_POST['hddaccion'];
        $rpta= $o->Activacion();
        if ($rpta!='OK') echo $rpta;
    }
}

$a->area_codigo = $area;
$r= $a->Query();
$area_descripcion= $a->area_descripcion;

	if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
	if (isset($_POST["orden"])) $orden = $_POST["orden"];
	if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];
	if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
	if (isset($_GET["orden"])) $orden = $_GET["orden"];
	if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];
	if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
	

?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Unidades de Servicio</title>
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../default.js"></script>
<script language="javascript">
function activacion(valor){
	var regs = Registro();
	if (regs=='') return false;
	if (confirm('Confirme modificar valor activo de Unidad de Servicio')==false) return false;
	document.frm.hddaccion.value=valor;
	document.frm.servicio.value=regs;
	
	document.frm.submit();
}
</script>
</head>
<body class="PageBODY" >
<center class="FormHeaderFont">Unidades de Servicio</Center>

<form id='frm' name='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post'>
<center class="CA_FormHeaderFont"><?php echo $area_descripcion; ?></Center>
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=button type='button' value='Activar' id='cmdAdicionar' name='cmdAdicionar'  LANGUAGE=javascript onclick='return activacion(1)' style='width:80px;'/>
            <INPUT class=button type='button' value='Desactivar' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return activacion(0)' style='width:80px;'/>
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
    $from = " v_campanas_clientes "; 
    $objr->setFrom($from);
    $where= "coordinacion_codigo=" . $area;
    $objr->setWhere($where);
    $objr->setSize(20);
    $objr->setUrl($_SERVER["PHP_SELF"]);
    $objr->setPage($npag);
    $objr->setMultipleSeleccion(false);
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "U_Servicio";
    $arrAlias[3] = "Expediente";
    $arrAlias[4] = "Cliente";
    $arrAlias[5] = "Estado";
    // Arreglo de los Campos de la consulta
    $arrCampos[0] = "cod_campana";
    $arrCampos[1] = "cod_campana";
    $arrCampos[2] =	"exp_NombreCorto";
    $arrCampos[3] = "exp_codigo";
    $arrCampos[4] = "Cliente_Razon_Social";
    $arrCampos[5] = "CASE WHEN Exp_Activo = 1 then 'Activo' else 'Desactivo' end ";
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);
    $body = $objr->Construir();  //ejecutar
    //echo $objr->getmssql();
    $objr = null;
    echo $body;
    echo "<br>";
	
?>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="area" name="area" value="<?php echo $area; ?>"/>
<input type="hidden" id="servicio" name="servicio" value=""/>

</form>

</body>
</html>