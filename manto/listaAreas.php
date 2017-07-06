<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsCA_Areas.php");

$search="";
$flag_gerente="0";
$area_codigo="0";
$todos="0";


$o = new areas();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$search= $_GET["strbuscar"];
$flag_gerente= $_GET["flag_gerente"];
$area_codigo= $_GET["area_codigo"];
$todos= $_GET["todos"];

?>

<html> 
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Seleccionar Area</title>
<meta http-equiv='pragma' content='no-cache'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript">
function cmdEnviar(codigo,descripcion){
	window.opener.filtroAreas(codigo, descripcion);
        window.close();
}
function cerrar(){
    window.close();
}
</script>
</head>

<body class="PageBODY">
<center class="FormHeaderFont">Seleccione Area</center>
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post'>
<?php 
$cadena=$o->Lista_Areas($search,$flag_gerente,$area_codigo,$todos);
echo $cadena;
?>
<br>
<table border="0" align="center" >
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onClick="cerrar()" />
		</td>
	</tr>
</table>
</form>
</body>
</html>