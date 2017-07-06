<?php
header("Expires: 0"); 
session_start();
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require('../../Includes/MyCombo.php');
require_once("../../Includes/clsEmpleados.php");

    //$usuario=3300;
    $usuario=$_SESSION["empleado_codigo"];

    $e = new Empleados();
    $e->setMyUrl(db_host());
    $e->setMyUser(db_user());
    $e->setMyPwd(db_pass());
    $e->setMyDBName(db_name());

    $combo = new MyCombo();
    $combo->setMyUrl(db_host());
    $combo->setMyUser(db_user());
    $combo->setMyPwd(db_pass());
    $combo->setMyDBName(db_name());

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>  
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Importar Servicio</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">

<link rel="stylesheet" href="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/modalfiles/modal.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/modalfiles/modal.js"></script>
<script language="javascript">
 function tecla(opt){
 	//alert(opt);
	var a=window.event.keyCode;
	if (a!=13) return;
	switch (opt){
	case 1:	BuscarArea();
		break;
	case 2:	BuscarServicio();
		break;
	}
	return;
}
function ver_archivo() {
    CenterWindow("mapa_servicio.txt",'Texto',670,400,0)
}
</script>
</head>
<body Class='PageBODY'>
<!--<center class=FormHeaderFont>Importar Servicio</center>-->
<form name=frm id=frm enctype="multipart/form-data" action='uploadFileServicio.php' method="POST">  
            <table align="center" >
                <tr><td colspan="2"><input type="hidden" name="MAX_FILE_SIZE" value="5000000"></td></tr>
                <tr><td colspan="2">Seleccione archivo de Servicios</td></tr> 
                <tr><td colspan="2"><input type="file" name="pix" size="53">  </td></tr>
                <!--<tr>
                <td colspan="2"><Input class="Input" type="checkbox" name="pasado" id="pasado"> Activar para cargar programacion antigua, se obviará Restriccion de fecha</td>
                </tr>-->
                <tr>
                    <td colspan="2" align="center"><input type="submit" name="Upload" value="subir archivo" ></td>
                </tr>
            </table>
		  <br> 
          <br>
<table width='80%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<tr>
		<td align='center'><b>Plantilla del Archivo TXT&nbsp;
		<img src="../../Images/listado.gif" width="25" height="25" border="0" style="cursor:hand" onclick="javascript:ver_archivo();">
		</td>
	</tr>
</table>
		  
</form>  
</body>
</html> 
