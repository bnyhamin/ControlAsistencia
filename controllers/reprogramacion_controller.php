<?php
header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../includes/clsCA_Reprogramacion.php");
    
$rep = new Reprogramacion();
$rep->setMyUrl(db_host());
$rep->setMyUser(db_user());
$rep->setMyPwd(db_pass());
$rep->setMyDBName(db_name());
    
$action="";
$usuario=0;
if(isset($_POST["action"])) $action=$_POST["action"];
    
switch ($action) {
    case "datasemana":
        $semanas=$rep->calcula_Semana();
        $final{"data"} = $semanas;
        $output = json_encode($final);
        echo $output;
    break;
    case "dataarea":
        $usuario=3300;
        $areas=$rep->area_Controller($usuario);
        $final{"data"} = $areas;
        $output = json_encode($final);
        echo $output;
    break;
    default:
    break;
}
?>
