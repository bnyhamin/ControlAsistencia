<?php header("Expires: 0"); 
  //require_once("../includes/Seguridad.php"); comentado, preguntar a cesar como activar esta página en GAP
  require_once("../../includes/Connection.php");
  require_once("../../includes/Constantes.php"); 
  require_once("../../includes/MyGrilla.php");
  
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
<title><?php echo tituloGAP() ?>- Asignaci&oacute;n de Lectores a Plataforma</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
<SCRIPT LANGUAGE=javascript>
<!--
function cmdAdicionar_onclick() {
    self.location.href="plataformas_lectores_job.php?lector_codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
}
function cmdModificar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
    self.location.href="plataformas_lectores_job.php?lector_codigo=&pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
    }
}
//-->
</SCRIPT>
</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<CENTER class="FormHeaderFont">Asignaci&oacute;n de Lectores a Plataforma</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=button type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar'  LANGUAGE=javascript onclick='return cmdAdicionar_onclick()' style='width:80px;'>
            <INPUT class=button type='button' value='Modificar' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return cmdModificar_onclick()' style='width:80px;'>
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
    $from = "(plataforma_lector inner join lector on plataforma_lector.lector_id=lector	.lector_id) inner join [SAPLCCC320].mapa_activo_puestos.dbo.vplataformas as plataforma  on plataforma_lector.plataforma_id = plataforma.plataforma_id "; 
    $objr->setFrom($from);
    $where= " plataforma_lector.plataforma_id = ".$_GET['plataforma_id'];
    $objr->setWhere($where);
    $objr->setSize(25);
    $objr->setUrl($_SERVER["PHP_SELF"]);
    $objr->setPage($npag);
    $objr->setMultipleSeleccion(false);
    // Arreglo de Alias de los Campos de la consulta
 
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
	$arrAlias[2] = "Lector";
	$arrAlias[3] = "Plataforma";
	$arrAlias[4] = "IP";
	$arrAlias[5] = "Puerto";
	$arrAlias[6] = "TipoAcceso";
    $arrAlias[7] = "Activo";

    // Arreglo de los Campos de la consulta
    $arrCampos[0] = "plataforma_lector_id";
    $arrCampos[1] = "plataforma_lector_id";
    $arrCampos[2] = "lector_nombre";
	$arrCampos[3] = "plataforma_descrip";
	$arrCampos[4] = "lector_ip";
	$arrCampos[5] = "lector_puerto";
	$arrCampos[6] = "CASE WHEN lector_tipo_acceso = 'E' then 'Entrada' else 'Salida' end ";
    $arrCampos[7] =	"CASE WHEN plataforma_lector_activo = 1 then 'Si' else 'No' end ";
	
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);
    $body = $objr->Construir();  //ejecutar
    //echo $objr->getmssql();
    $objr = null;
    echo $body;
    echo "<br>";
    echo Menu("main_plataforma.php");
?>
</form>
</body>
</html>