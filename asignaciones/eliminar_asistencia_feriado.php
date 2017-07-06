<?php header("Expires: 0"); 

//require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/clsEmpleado_Asistencia_Feriado.php");

  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);

if (isset($_GET["eaf_codigo"])) $eaf_codigo = $_GET["eaf_codigo"];
$e = new Empleado_Asistencia_Feriado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();


$e->eaf_codigo = $eaf_codigo;
$rs = $e->Elimina_Asistencia_Feriado();
if ($rs == 'OK') {
	echo "Se elimino correctamente";
}else{
	echo $rs;
}

?>