<?php header("Expires: 0"); 
  require_once("../includes/Seguridad.php");
  require_once("../includes/Connection.php");
  require_once("../includes/Constantes.php"); 
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
<title><?php echo tituloGAP() ?>- Catálogo de Estados</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
<SCRIPT LANGUAGE=javascript>
<!--
function cmdAdicionar_onclick() {
    self.location.href="estados_job.php?Estado_codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
function cmdModificar_onclick() {
    var rpta=PooGrilla.Registro();
    if (rpta != '' ) {
    self.location.href="estados_job.php?Estado_codigo=" + rpta + "&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
    }
}
//-->
</SCRIPT>

<?php  require_once('../includes/librerias_easyui.php');?>


</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<CENTER class="FormHeaderFont">Catálogos de Estados</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=buttons type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar'  LANGUAGE=javascript onclick='return cmdAdicionar_onclick()' style='width:80px;'>
            <INPUT class=buttons type='button' value='Modificar' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return cmdModificar_onclick()' style='width:80px;'>
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
    $from = " CA_Estados ";
    $objr->setFrom($from);
    $where= "";
    $objr->setWhere($where);
    $objr->setSize(15);
    $objr->setUrl($_SERVER["PHP_SELF"]);
    $objr->setPage($npag);
    $objr->setMultipleSeleccion(false);
    // Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Estado";
    $arrAlias[3] = "Activo";
    // Arreglo de los Campos de la consulta
    $arrCampos[0] = "CA_Estado_Codigo";
    $arrCampos[1] = "CA_Estado_Codigo";
    $arrCampos[2] =	"CA_Estado_Descripcion";
    $arrCampos[3] = "CASE WHEN CA_Estado_Activo = 1 then 'Si' else 'No' end ";
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);
    $body = $objr->Construir();  //ejecutar
    echo $objr->getmssql();
    $objr = null;
    echo $body;
    echo "<br>";
    echo Menu("../menu.php");
?>
</form>
</body>
</html>