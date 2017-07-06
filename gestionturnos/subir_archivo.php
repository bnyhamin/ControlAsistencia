<?php header("Expires: 0"); 
  require_once("../includes/Seguridad.php");
  require_once("../../Includes/Connection.php");
  require_once("../../Includes/mantenimiento.php");
  require_once("../../Includes/Constantes.php");
  require_once("../includes/clsCA_Turnos_Empleado.php");
  
$o = new ca_turnos_empleado();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$tiempo= $o->valida_hora();
if ($tiempo==0){
?>
	<script language='javascript'>
		alert('Esta opcion esta temporalmente deshabilitado intente ingresar en unos minutos');

	</script>
<?php
}
  $pasado="1"; 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>  
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Importar Programacion Semanal</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">
</head>  
<body Class='PageBODY'>
<center class=FormHeaderFont>Importar Programacion Semanal</center>
<div align="center"><hr>  
<form enctype="multipart/form-data" action="uploadFile.php" method="POST">  
Plantilla Semanal:<input type="hidden" name="MAX_FILE_SIZE" value="5000000">  
<input type="file" name="pix" size="60">  
<p>
<Input class="Input" type="hidden" name="pasado" id="pasado" disabled > <!--Activar para cargar programacion antigua, se obviará Restriccion de fecha -->
<br> 
<input type="submit" name="Upload" value="subir archivo" <?php if ($tiempo==0) echo 'disabled'; ?>/> 
</form>  
</body>
</html> 

