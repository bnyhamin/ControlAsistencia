<?php
class CA_Macs_Autorizados extends mantenimiento{
var $mac_numero="";
var $mac_activo="";

function Addnew(){
    
    $rpta="1";
    $cn=$this->getMyConexionADO();
    $existe=0;
    $ssql = "select count(mac_numero) as existe from CA_MacAutorizados where mac_numero = '".$this->mac_numero."'";
    $rs= $cn->Execute($ssql);
    $existe = intval($rs->fields[0]);
    $rs=null;
    
    if($existe==0){
        $ssql = "insert into CA_MacAutorizados(mac_numero,mac_activo)";
        $ssql.= "values(?,?)";

        $params=array(
            $this->mac_numero,
            $this->mac_activo
        );

        $rs=$cn->Execute($ssql,$params);
        if(!$rs) $rpta="0";
    }else{
        $rpta="2";
    }
    
    

    return $rpta;
    
}

function Update(){

    $rpta="1";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;

    $ssql = "UPDATE CA_MacAutorizados ";
    $ssql .= " SET mac_activo = ?  ";
    $ssql .= " Where mac_numero = ? ";

    $params=array(
        $this->mac_activo,
        $this->mac_numero
    );

    $rs=$cn->Execute($ssql,$params);
    if(!$rs) $rpta="0";

    return $rpta;
    
}

function Query(){
	
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql = "SELECT mac_numero, mac_activo ";
    $ssql .= " FROM CA_MacAutorizados ";
    $ssql .= " WHERE mac_numero = ? ";

    $params=array(
        $this->mac_numero
    );

    $padre=array();
    $rs = $cn->Execute($ssql,$params);
    if ($rs->RecordCount()>0){
        $hijo=array();
        $hijo["mac_numero"]=$rs->fields[0];
        $hijo["mac_activo"]=$rs->fields[1];
        array_push($padre, $hijo);
    }
    $rs=null;
    return $padre;
}
 
}
?>
