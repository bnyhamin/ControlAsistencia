<?php
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    require_once("json/json.php");
    
    $ase = new ca_asignacion_empleados();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();
    
    $empleado_codigo="";
    $estado_proceso="0";
    $option="";
    $tip_proceso="0";
    
    if(isset($_POST["empleado_codigo_s"])) $empleado_codigo=$_POST["empleado_codigo_s"];
    if(isset($_POST["estado_proceso"])) $estado_proceso=$_POST["estado_proceso"];
    if(isset($_POST["tip_proceso"])) $tip_proceso=$_POST["tip_proceso"];
    
    $ase->empleado_codigo=$empleado_codigo;
    $ase->tipo_incidencia=$estado_proceso;
    $ase->estado_evento=$tip_proceso;
    
    $json=new Services_JSON();
    
    $option=$_POST["action"];
    
switch ($option){
    case "load_eventos":
        $data=$ase->listado_eventos_supervisor();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    case "load_log":
        $ase->empleado_codigo=$_POST["empleado_codigo"];
        $ase->asistencia_codigo=$_POST["asistencia_codigo"];
        $ase->evento_codigo=$_POST["evento_codigo"];
        $ase->incidencia_codigo=$_POST["incidencia_codigo"];
        $data=$ase->listado_evento_log();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    case "load_reportes":
        $ase->empleado_codigo=$empleado_codigo;
        $data=$ase->listado_reporte();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
}
?>
