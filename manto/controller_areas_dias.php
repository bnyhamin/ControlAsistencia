<?php
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Areas.php");
    require_once("../asistencias/json/json.php");
    
    $a = new areas();
    $a->MyUrl = db_host();
    $a->MyUser= db_user();
    $a->MyPwd = db_pass();
    $a->MyDBName= db_name();
    
    $json=new Services_JSON();
    $option=$_POST["action"];
    
switch ($option){
    case "load_areas":
        $data=$a->getAreas();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    case "actualiza_dia":
        $a->area_codigo=$_POST["area_codigo"];
        $a->dias=$_POST["dias"];
        
        $mensaje=$a->updDiasArea();
        if($mensaje=="OK"){
            $status=1;
        }else{
            $status=0;
        }    
        $rpta=array(
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    
}
?>
