<?php header("Expires: 0"); ?>
<?php
session_start();

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsGmenu.php"); 
require_once("../includes/clsIncidencias.php"); 

$oi = new incidencias();
$oi->setMyUrl(db_host());
$oi->setMyUser(db_user());
$oi->setMyPwd(db_pass());
$oi->setMyDBName(db_name());

$gm = new gmenu();
$gm->setMyUrl(db_host());
$gm->setMyUser(db_user());
$gm->setMyPwd(db_pass());
$gm->setMyDBName(db_name());

/*if ($msconnect = mssql_pconnect(db_host(),db_user(),db_pass()) or die("No puedo conectarme a servidor")){
	$cnn = mssql_select_db(db_name(),$msconnect) or die("No puedo seleccionar BD");
} else {
  echo "Error al tratar de conectarse a la bd.";
}*/
$mes_codigo = "";
$lista_sel = "0";
$fecha = "";
$area_codigo = "0";
$responsable_codigo = "0";
$turno_codigo = "0";
$opcion = "0";
$incidencia_descripcion = "";

if (isset($_GET["lista_sel"])) $lista_sel = $_GET["lista_sel"];
if (isset($_GET["area_codigo"])) $area_codigo = $_GET["area_codigo"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if (isset($_GET["turno_codigo"])) $turno_codigo = $_GET["turno_codigo"];
if (isset($_GET["opcion"])) $opcion = $_GET["opcion"];
if (isset($_GET["incidencia_descripcion"])) $incidencia_descripcion = $_GET["incidencia_descripcion"];
     
switch($opcion){
    case '2.1':
        $titulo = "Eventos Abiertos";
        break;
    case '2.2':
        $titulo = "Eventos Cerrados";
        break;
}
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Lista de Personal que Registra <?php echo $titulo; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript" src="../../default.js"></script>
</head>

<body class="PageBODY"  >
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>

<?php

$oi->incidencia_codigo=$lista_sel;	
$rpta=$oi->Query();

?>

<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<center class="FormHeaderFont"><?php echo $titulo ?> - <?php echo $oi->incidencia_descripcion ?></center></b>
<p>
<img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
</p>

<?php 
    
$gm->lista_sel=$lista_sel;
$gm->opcion=$opcion;
$gm->fecha=$fecha;
$gm->area_codigo=$area_codigo;
$gm->responsable_codigo=$responsable_codigo;
$gm->turno_codigo=$turno_codigo; 

$rpta=$gm->ListarEvento();
 
?>
<input type="hidden" id="mes_codigo" name="mes_codigo" value="<?php echo $mes_codigo ?>" />
<input type="hidden" id="lista_sel" name="lista_sel" value="<?php echo $lista_sel ?>" />
<input type="hidden" id="opcion" name="opcion" value="<?php echo $opcion ?>" />
<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha ?>" />
<input type="hidden" id="area_codigo" name="area_codigo" value="<?php echo $area_codigo ?>" />
<input type="hidden" id="responsable_codigo" name="responsable_codigo" value="<?php echo $responsable_codigo ?>" />
<input type="hidden" id="turno_codigo" name="turno_codigo" value="<?php echo $turno_codigo ?>" />
<input type="hidden" id="incidencia_descripcion" name="incidencia_descripcion" value="<?php echo $incidencia_descripcion ?>" />

</form>
</body>
</html>