<?php 
require_once("../../Includes/Connection.php");

require_once("../includes/clsCA_Reportes.php");


$fecha_carga = date("YmdHis");

$o = new ca_reportes();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();


$del = $o->Delete_Temp();
echo 'Registros eliminados';

header('Location:listado_cargados.php');
?>
