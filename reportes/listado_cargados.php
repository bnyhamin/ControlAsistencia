<?php 
require_once("../../Includes/Connection.php");

require_once("../includes/clsCA_Reportes.php");


$fecha_carga = date("YmdHis");
if(isset($_GET['u'])) $usuario = $_GET['u'];

$o = new ca_reportes();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Lista de cargados</title>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css" />
<!--Fecha-->
<link rel="stylesheet" type="text/css" media="all" href="../js/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../asistencias/app/app.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/lang/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>

<style type="text/css">
	.oculto{display:none}
	.no_oculto{display:block}
</style>

<body>
	
	<table class="FormTABLE" width="100%">
	<tr>
		<th>DNI</th>
		<th>EMPLEADO</th>
	</tr>
<?php 
$lista = $o->Cargados_Temp($usuario);
if (!$lista) {
	echo '<tr><td colspan="2">No hay datos cargados</td></tr>';
}else{
	echo $lista;
}

?>
	
	</table>
	<button class="Button" onclick="self.close()">Cerrar</button>
	<a href="eliminar_cargados.php" class="Button" style="padding: 1px 6px;"><span  >Eliminar</span></a>
</body>
