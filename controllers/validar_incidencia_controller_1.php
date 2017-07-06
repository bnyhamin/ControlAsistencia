<?php
set_time_limit(30000);
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Validar_Incidencia.php");
require_once("../includes/clsCA_Usuarios.php");

$action="";
$respuesta='';
$fecha="";
$start=0;
$limit=0;
$arr=array();
$responsable_codigo=0;
$fecha="";
$empleado_codigo=0;
$resumen_rac="";
$asistencia_codigo=0;
$tiempo_efectivo=0;
$extension_tiempo=0;

$v = new ca_validar_incidencia();
$v->MyUrl = db_host();
$v->MyUser= db_user();
$v->MyPwd = db_pass();
$v->MyDBName= db_name();

if (isset($_POST["action"])) $action=$_POST["action"];
if (isset($_POST["empleado_codigo"])) $empleado_codigo=$_POST["empleado_codigo"];
if (isset($_POST["asistencia_codigo"])) $asistencia_codigo=$_POST["asistencia_codigo"];
if (isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if (isset($_POST["tiempo_efectivo"])) $tiempo_efectivo = $_POST["tiempo_efectivo"];
if (isset($_POST["extension_tiempo"])) $extension_tiempo = $_POST["extension_tiempo"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if (!isset($_POST["f"])) $fecha = $fecha;   
else $fecha = $_POST["f"];
if (isset($_GET["empleado_codigo"])) $empleado_codigo=$_GET["empleado_codigo"];
if (isset($_GET["asistencia_codigo"])) $asistencia_codigo=$_GET["asistencia_codigo"];
if (isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if (isset($_GET["action"])) $action=$_GET["action"];

switch ($action) {
    case 'datagrupo':
        $v->responsable_codigo=$responsable_codigo;
        $v->fecha=$fecha;
        
        $start = isset($_POST['start']) ? $_POST['start'] : 0;
        $limit = isset($_POST['limit']) ? $_POST['limit'] : 3;
        $find = isset($_POST['find']) ? $_POST['find'] : '';
        
        if($find!=''){
            $arr=explode("|",$find);
            $find=$arr[1];
        }
        $v->find=$find;
        $v->tipo=1;
        
        $ejecutivos=$v->listar_Grupo();
        $response = array(
            'success'=>true,
            'total'=>count($ejecutivos),
            'data'=> array_splice($ejecutivos,$start,$limit)
        );
        
        $output = json_encode($response);
        echo $output;
    break;
    
    case 'datacesados':
        $v->responsable_codigo=$responsable_codigo;
        $v->fecha=$fecha;
        $v->find='';
        $v->tipo=3;
        
        $cesados=$v->_listar_cesados();
        $response = array(
            'success'=>true,
            'total'=>count($cesados),
            'data'=> $cesados
        );
        
        $output = json_encode($response);
        echo $output;
    break;
    
    case 'dataotros':
        $v->responsable_codigo=$responsable_codigo;
        $v->fecha=$fecha;
        $v->find='';
        $v->tipo=2;
        
        $otros=$v->_listar_otros();
        $response = array(
            'success'=>true,
            'total'=>count($otros),
            'data'=> $otros
        );
        $output = json_encode($response);
        echo $output;
    break;
    case 'resumenrac':
        $v->empleado_codigo=41753;
        $v->asistencia_codigo=661;
        $v->responsable_codigo=7861;
        $arr=array();
        $asistencia_codigo=661;
        if($asistencia_codigo*1==0){
            $hh_sin_asistencia{"incidencia"}="Sin Asistencia";
            $hh_sin_asistencia{"total"}=100;
            array_push($arr,$hh_sin_asistencia);  
        }else{
            $arr_inc=array();
            $arr_inc=$v->resumeRac();
            
            $codigos=array();
            $z=0;
            foreach ($arr_inc as $indice => $incidencia){
                if($z!=0){
                    if(in_array($incidencia['incidencia_codigo'],$codigos)){
                        $hh_incidencia{"incidencia"}=$arr[$incidencia['incidencia_codigo']]['incidencia'];
                        $hh_incidencia{"total"}=($arr[$incidencia['incidencia_codigo']]['total']*1)+($incidencia['tiempo_minutos']*1);
                        $arr[$incidencia['incidencia_codigo']]=$hh_incidencia;
                    }else{
                        $hh_incidencia{"incidencia"}=$incidencia['incidencia'];
                        $hh_incidencia{"total"}=$incidencia['tiempo_minutos'];
                        $arr[$incidencia['incidencia_codigo']]=$hh_incidencia;
                    }
                }
                $codigos[$z]=$incidencia['incidencia_codigo'];
                if($z==0){
                    $hh_incidencia{"incidencia"}=$incidencia['incidencia'];
                    $hh_incidencia{"total"}=$incidencia['tiempo_minutos'];
                    $arr[$incidencia['incidencia_codigo']]=$hh_incidencia;
                }
                $z++;
            }
            $arr=array_values($arr);
            $hh_tiempo_efectivo{"incidencia"}="Tiempo programado";
            $hh_tiempo_efectivo{"total"}=($tiempo_efectivo*1)+($extension_tiempo*1);
            array_push($arr,$hh_tiempo_efectivo);
        }
        $final{"data"} = $arr;
        $output = json_encode($final);
        echo $output;
    break;
    case 'resumensupervisor':
        $v->fecha=$fecha;
        $v->responsable_codigo=$responsable_codigo;
        $data=$v->resumenSupervisor();
        $final{"data"} = $data;
        $output = json_encode($final);
        echo $output;
    break;
    case 'incidenciasXejecutivo':
        $v->empleado_codigo=$empleado_codigo;
        $v->asistencia_codigo=$asistencia_codigo;
        $v->responsable_codigo=$responsable_codigo;
        
        $incidencias=$v->_listado_registros_dia();
        $response = array(
            'success'=>true,
            'total'=>count($incidencias),
            'data'=>$incidencias
        );
        $output = json_encode($response);
        echo $output;
    break;
}
?>