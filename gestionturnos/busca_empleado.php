<?php header("Expires: 0"); 

require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Auditor.php"); 

$e = new ca_auditor();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$term = $_GET['term'];

$res_empleado = $e->lista_empleados($term);

echo json_encode($res_empleado)


?>