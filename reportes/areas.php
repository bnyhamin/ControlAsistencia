<?php header("Expires: 0");  ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Areas.php"); 
$area="";
$anio="";
$usuario="";
$opcion="";

$CodCadena="";

$obj = new areas();
$obj->MyUrl = db_host();
$obj->MyUser= db_user();
$obj->MyPwd = db_pass();
$obj->MyDBName= db_name();

$area= $_GET["area_codigo"];
$anio= $_GET["anio"];
$usuario= $_GET["usuario"];
$opcion= $_GET["opcion"];

if($area!="0") $CodCadena=$obj->TreeRecursivo($area);
else $CodCadena=0;

?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Areas de Gerencia o Atento</title>
<meta http-equiv='pragma' content='no-cache'>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<link href="mantenimiento/style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language="JavaScript">
	window.parent.areas('<?php echo $anio ?>','<?php echo $area ?>','<?php echo $usuario ?>','<?php echo $opcion ?>','<?php echo $CodCadena ?>');
</script>
</head>

<body class="PageBODY">
</body>
</html>