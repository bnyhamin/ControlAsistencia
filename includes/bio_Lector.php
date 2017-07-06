<?php

class bio_Lector extends mantenimiento{

var $lector_id ="";
var $lector_codigo_equipo="";
var $lector_nombre="";
var $lector_ip="";
var $lector_puerto="";
var $lector_tipoacceso="";
var $lector_marca="";
var $lector_modelo="";
var $lector_nro_serie="";
var $lector_activo="0";

function Addnew()
{
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    //insertar nuevo registro
    $ssql = "INSERT INTO bio_lector";
    $ssql .= " (codigo_equipo, lector_nombre, lector_ip, lector_puerto, lector_tipo_acceso, marca, modelo, nro_serie, lector_activo, usuario_registro) ";
    $ssql .= " VALUES (?,?,?,?,?,?,?,?,?,?)";
        
    $params=array($this->lector_codigo_equipo, $this->lector_nombre, $this->lector_ip, 
                  $this->lector_puerto, $this->lector_tipoacceso, $this->lector_marca,
    			        $this->lector_modelo, $this->lector_nro_serie, $this->lector_activo, 
                  $_SESSION["empleado_codigo"]);
    

    $rs=$cn->Execute($ssql,$params);
	
    if(!$rs) $rpta="Error ";

    return $rpta;
    
}

function Update()
{
      $rpta="OK";
	    $cn=$this->getMyConexionADO();
        
      $ssql = "UPDATE bio_lector ";
      $ssql .= " SET fecha_modificacion  = getdate(), ";
      $ssql .= "     codigo_equipo = ?,";
      $ssql .= "     lector_nombre = ?, ";
		  $ssql .= "     lector_ip = ?, ";
		  $ssql .= "     lector_puerto = ?, ";
	    $ssql .= "     lector_tipo_acceso = ?, ";
		  $ssql .= "     marca = ?, ";
		  $ssql .= "     modelo = ?, ";
		  $ssql .= "     nro_serie = ?, ";
		  $ssql .= "     lector_activo = ?, ";
		  $ssql .= "     usuario_modificacion = ? ";
      $ssql .= " Where lector_id = ? ";
				
      $params=array($this->lector_codigo_equipo, $this->lector_nombre,$this->lector_ip,
                    $this->lector_puerto, $this->lector_tipoacceso, $this->lector_marca,
    			          $this->lector_modelo, $this->lector_nro_serie, $this->lector_activo,
                    $_SESSION["empleado_codigo"],$this->lector_id);
        
      $rs=$cn->Execute($ssql,$params);
	    
      if(!$rs) $rpta="Error";
        
    	return $rpta;
}

function Query()
{
        $rpta="OK";
	      $cn=$this->getMyConexionADO();
        $ssql = "SELECT codigo_equipo, lector_nombre, marca , modelo, nro_serie,  abs(lector_activo), lector_ip, lector_puerto, lector_tipo_acceso ";
        $ssql .= " FROM bio_lector ";
        $ssql .= " WHERE lector_id = ? ";
		
        $params=array($this->lector_id);
		
        $rs = $cn->Execute($ssql,$params);
        
        if ($rs->RecordCount()>0){
        
            $this->lector_codigo_equipo=$rs->fields[0];
            $this->lector_nombre=$rs->fields[1];
      			$this->lector_marca=$rs->fields[2];
            $this->lector_modelo=$rs->fields[3];
      			$this->lector_nro_serie=$rs->fields[4];
      			$this->lector_activo=$rs->fields[5];
      			$this->lector_ip=$rs->fields[6];
      			$this->lector_puerto=$rs->fields[7];
      			$this->lector_tipoacceso=$rs->fields[8];
        
        }else{
            $rpta='No Existe Registro de Lector: '.$this->lector_id;
        }
	
	      return $rpta;
  }


}
?>
