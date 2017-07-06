<?php
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Usuarios.php");
    require_once("../asistencias/json/json.php");
    
    $ou = new ca_usuarios();
    $ou->setMyUrl(db_host());
    $ou->setMyUser(db_user());
    $ou->setMyPwd(db_pass());
    $ou->setMyDBName(db_name());
    
    $data=0;
    $option="";
    $area=0;
    
    $json=new Services_JSON();
    
    if(isset($_POST["action"])) $option=$_POST["action"];
    if(isset($_POST["area"])) $area=$_POST["area"];
    if(isset($_GET["action"])) $option=$_GET["action"];
    if(isset($_GET["area"])) $area=$_GET["area"];
    
switch ($option){
    case "load_nro_dias":
        $ou->area_codigo=$area;
        $data=$ou->Actualizacion_dias();
        $rpta=array(
            "data"=>$data
        );
        echo $json->encode($rpta);
    break;
}
?>