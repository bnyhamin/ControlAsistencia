<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
 require_once("../../Includes/Connection.php");
 require_once("../../Includes/mantenimiento.php");
 require_once("../../Includes/Constantes.php"); 
 require_once("../includes/clsCA_Campanas.php");
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
	<script language="JavaScript" src="../../default.js"></script>
    <title>Servicios del Área </title>
</head>

<?php
$filtro	= $_GET["filtro"];
$area	= $_GET["area"];
?>

<script language=javascript>
 window.returnValue = "";
 
 function guardar(codigo,descripcion){
	var cual=0;
    cual=codigo;
	descrip=descripcion;
	if (cual==0){
		alert("seleccione un registro");
		return;
	}
	self.returnValue = cual + '¬' + descrip;
	self.close();
  }
  
  function cancelar(){
	self.returnValue = 0;
	self.close();
  }
 
</script>

<body class="PageBODY">

<Center class=FormHeaderFont>Seleccione código de Unidad de Servicio </center>
<form name=frm id=frm action="Servicios.php" method="post">
<?php

	//PROCESO PARA OBTENER LOS SERVICIOS DE LA ORGANIZACION
	$o = new ca_campanas();
        $o->setMyUrl(db_host());
	$o->setMyUser(db_user());
	$o->setMyPwd(db_pass());
	$o->setMyDBName(db_name());
	
	 
	$o->coordinacion_codigo= $area;  
	$result = $o->Seleccionar_servicios_area($filtro);
	
	?>

</form>
</body>
</html>