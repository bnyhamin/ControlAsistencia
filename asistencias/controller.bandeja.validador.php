<?php
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    require_once("json/json.php");
    
    $empleado_codigo="0";
    $incidencia_proceso="0";
    $area="0";
    $reporte=0;
    $option="";
    $flag = 0;
    
    $ase = new ca_asignacion_empleados();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();
    
    $json=new Services_JSON();
    
    if(isset($_POST["empleado_codigo_s"])) $empleado_codigo=$_POST["empleado_codigo_s"];
    else if(isset($_GET["empleado_codigo_s"])) $empleado_codigo=$_GET["empleado_codigo_s"];
    
    if(isset($_POST["action"])) $option=$_POST["action"];
    else if(isset($_GET["action"])) $option=$_GET["action"];
    
    if(isset($_POST["incidencia_proceso"])) $incidencia_proceso=$_POST["incidencia_proceso"];
    else if(isset($_GET["incidencia_proceso"])) $incidencia_proceso=$_GET["incidencia_proceso"];
    
    if(isset($_POST["area"])) $area=$_POST["area"];
    else if(isset($_GET["area"])) $area=$_GET["area"];
    
    if(isset($_POST["reporte"])) $reporte=$_POST["reporte"];
    else if(isset($_GET["reporte"])) $reporte=$_GET["reporte"];
    
    if(isset($_POST["hddflag"])) $flag=$_POST["hddflag"];
    else if(isset($_GET["hddflag"])) $flag=$_GET["hddflag"];
    
switch ($option){
    case "load_eventos"://area de apoyo
        //ok
        $ase->empleado_codigo=$empleado_codigo;
        $ase->incidencia_proceso=$incidencia_proceso;
        $ase->area = $area;
        $ase->mando = 0;
        //$data=$ase->listado_eventos_validador();
        $data=$ase->listado_eventos_validador_apoyo();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status,
            "cantidad"=>$ase->row
        );
        echo $json->encode($rpta);
    break;
    case "load_eventos_mando"://mandos
        $ase->empleado_codigo=$empleado_codigo;
        $ase->estado_evento=2;
        $ase->area = $area;
        $ase->mando = 1;
        $data=$ase->listado_eventos_validador();
        if(count($data)>0) $status=1;
        else $status=0;
        
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
        
    break;
    case "listado_eventos_gerente"://gerentes
        $ase->empleado_codigo=$empleado_codigo;
        $ase->mando = $flag;
        $ase->area = $area;
        $data=$ase->listado_eventos_validador();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    case "listado_eventos_mando":
        $ase->area = $area;
        $ase->empleado_codigo=$empleado_codigo;
        if($reporte=="M") $ase->mando = 1;
        if($reporte=="V") $ase->mando = 0;
        if($reporte=="G") $ase->mando = 2;
        $data=$ase->listado_eventos_mando();
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    case "load_log":
        $ase->empleado_codigo=$_POST["empleado_codigo"];//ok
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
        $data=$ase->listado_reporte_validador();//ok
        if(count($data)>0) $status=1;
        else $status=0;
        $rpta=array(
            "data"=>$data,
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
    
    case "reportexmando":
        $ase->area = $area;
        $data=$ase->reportexmando();
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
