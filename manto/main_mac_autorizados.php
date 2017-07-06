<?php
header('Expires: 0');
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../includes/MyGrillaEasyUI.php");
require_once("../includes/Seguridad.php");

$body = '';
$npag = 1;
$orden = 'mac_numero';
$buscam = '';
$torder = 'ASC';

if(isset($_GET['pagina'])) $npag = $_GET['pagina'];
elseif(isset($_POST['pagina'])) $npag = $_POST['pagina'];

if(isset($_GET['orden'])) $orden = $_GET['orden'];
elseif(isset($_POST['orden'])) $orden = $_POST['orden'];

if(isset($_GET['buscam'])) $buscam = $_GET['buscam'];
elseif(isset($_POST['buscam'])) $buscam = $_POST['buscam'];

if(isset($_GET['cboTOrden'])) $torder = $_GET['cboTOrden'];
elseif(isset($_POST['cboTOrden'])) $torder = $_POST['cboTOrden'];

?>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv='pragma' content='no-cache'/>
<title>Maestro de Mac Autorizadas</title>
<!--librerias easyui-->
<?php require_once('../includes/librerias_easyui.php');?>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript" src="../../default.js"></script>
<script type="text/javascript" src="../jscript/main_mac_autorizados.js"></script>
<style type="text/css">
    .window {
        /*background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #eff5ff 0px, #e0ecff 20%) repeat-x scroll 0 0;*/
        background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #DCBB83 0px, #8F6B32 20%) repeat-x scroll 0 0;
        /*#EAF2FF*/
    }
    .window-body {
        border-color: #E7E7D6;
    }
</style>
</head>
<body>
<center class=TITOpciones>Mac Autorizadas</center>
<form name='frm' id='frm' action=''>

    

<TABLE class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <TR>
        <TD class='ColumnTD'>
            <INPUT class=buttons type='button' value='Adicionar' id='cmdAdicionar' name='cmdAdicionar' onclick="javascript:Ext_Mac_Autorizados.levantaModalMacs('G');" style='width:80px;'/>
            <INPUT class=buttons type='button' value='Modificar' id='cmdModificar' name='cmdModificar' onclick="javascript:Ext_Mac_Autorizados.levantaModalMacs('U');" style='width:80px;'/>
            <INPUT class=buttons type='button' value='Salir' id='cmdSalir' name='cmdSalir' onclick="javascript:Ext_Mac_Autorizados.Salir();" style='width:80px;'/>
        </TD>
    </TR>
</TABLE>

<?php
$objr = new MyGrilla();
// Parametros de la clase
$objr->setDriver_Coneccion(db_name());
$objr->setUrl_Coneccion(db_host());
$objr->setUser(db_user());
$objr->setPwd(db_pass());

$objr->setOrder($orden);
$objr->setFindm($buscam);
$objr->setFont('');
$objr->setFormatoBto("class=boton");
$objr->setFormaTabla(FormaTabla());
$objr->setFormaCabecera(FormaCabecera());
$objr->setFormaTCabecera(FormaTCabecera());
$objr->setFormaItems(FormaItems());

$objr->setTOrder($torder);

$from =  "CA_MacAutorizados";

$objr->setFrom($from);
$where = '';
$objr->setWhere($where);
$objr->setSize(15);

$objr->setUrl($_SERVER['PHP_SELF']);
$objr->setPage($npag);
$objr->setMultipleSeleccion(false);

$Alias[0] = "root";
$Alias[1] = "mac_numero";
$Alias[2] = "activo";
$objr->setAlias($Alias);

$Campos[0] = "mac_numero";
$Campos[1] = "mac_numero";
$Campos[2] = "case when mac_activo=1 then 'Si' else 'No' end";
$objr->setCampos($Campos);

$body = $objr->Construir();

$objr = null;

    echo $body;

    echo "<br>";
    echo Menu("../menu.php");


?>
</form>
<div id="dialog_agrupacion_macs"></div>
<br/>
<?php
    echo $body;
?>
</body>
</html>