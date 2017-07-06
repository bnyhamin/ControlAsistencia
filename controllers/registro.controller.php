<?php
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php");

$o = new ca_asistencia_incidencias();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$operacion = isset($_POST["operacion"])?$_POST["operacion"]:"";
switch($operacion){
    case "OBTIENE_FLAGS":
        $o->incidencia_codigo = $_POST["incidencia"];
        $o->Obtener_Incidencia_Flag();
        $Incidencia_Inicio_Fin = $o->Incidencia_Inicio_Fin;
        $Incidencia_NroTicket  = $o->Incidencia_NroTicket;
        echo $Incidencia_Inicio_Fin."-".$Incidencia_NroTicket;      
        break;
}
?>