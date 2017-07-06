<?php
require_once(PathIncludes().'mantenimiento.php');
class Reprogramacion extends mantenimiento{
    
    
function calcula_Semana(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql=" set datefirst 1 ";
    $rs=$cn->Execute($ssql);
    $sSql="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3";
    $rs=$cn->Execute($sSql);
    $padre = array();
    while (!$rs->EOF){
        $hijo = array();
        $hijo["codigo"]= $rs->fields[0];
        $hijo["descripcion"]= $rs->fields[1];
        array_push($padre,$hijo);
        $rs->MoveNext();
    }
    return $padre;
}

function area_Controller($usuario){
    $rpta="Ok";
    $cadena="";
    $cn=$this->getMyConexionADO();
    $sSql =" select areas.area_codigo , areas.area_descripcion";
    $sSql .= " from ca_controller ";
    $sSql .= " inner join areas on areas.area_codigo = ca_controller.area_codigo ";
    $sSql .= " where ca_controller.empleado_codigo=" . $usuario . " and ca_controller.activo=1";
    $rs=$cn->Execute($sSql);
    $padre = array();
    while(!$rs->EOF){
        // We fill the $value array with the data.
        $hijo = array();
        $hijo["codigo"]= $rs->fields[0];
        $hijo["descripcion"]= utf8_encode($rs->fields[1]);
        array_push($padre,$hijo);
        $rs->MoveNext();
    }
    return $padre;
}


    
}

?>
