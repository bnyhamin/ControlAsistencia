<?php
//require_once(PathIncludes() . "mantenimiento.php");
//require_once("../../includes/mantenimiento.php"); 
class ca_paginas extends mantenimiento{
var $pagina_codigo="";
var $pagina_descripcion="";
var $pagina_url="";
var $pagina_activo="";

function Addnew(){
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();

    //obtener nuevo codigo de registro a insertar
    $sSql = "SELECT isnull(max(pagina_codigo), 0) + 1 as maximo FROM ca_paginas ";
    $rs = $cn->Execute($sSql);
    $this->pagina_codigo= $rs->fields[0];
    
    //insertar nuevo registro
    $params = array($this->pagina_codigo,
                    $this->pagina_descripcion,
                    $this->pagina_url,
                    $this->pagina_activo);
    
    $sSql = "INSERT INTO ca_paginas";
	$sSql .= " (Pagina_Codigo, Pagina_Descripcion,Pagina_Url,Pagina_Activo) ";
	$sSql .= " VALUES (?,?,?,?)";
        
    $rs = $cn->Execute($sSql, $params);
	
    if($rs==false) $sRpta="Error al Insertar registro";
    return $sRpta;
}

function Update(){
	$sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array($this->pagina_descripcion,
                    $this->pagina_url,
                    $this->pagina_activo,
                    $this->pagina_codigo);
	
    $sSql = "UPDATE CA_Paginas ";
	$sSql .= " SET Pagina_Descripcion = ? ";
	$sSql .= "     ,Pagina_Url = ? " ;	
	$sSql .= "     ,Pagina_Activo = ? "; 
	$sSql .= " Where Pagina_Codigo =? "; 
			
    $rs = $cn->Execute($sSql, $params);
    if ($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
//	$ssql = "UPDATE CA_Paginas ";
//	$ssql .= " SET Pagina_Descripcion = '" . $this->pagina_descripcion . "',";
//	$ssql .= "     Pagina_Url = '" . $this->pagina_url . "',";	
//	$ssql .= "     Pagina_Activo = " . $this->pagina_activo; 
//	$ssql .= " Where Pagina_Codigo =? "; 

}

function Query(){
	$sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    
	$params = array($this->pagina_codigo);
    
    $sSql = "SELECT Pagina_Codigo,Pagina_Url,Pagina_Descripcion, abs(Pagina_Activo) ";
	$sSql .= " FROM CA_Paginas ";
	$sSql .= " WHERE Pagina_Codigo = ? ";
   
    $rs = $cn->Execute($sSql, $params);
    
    if ($rs->RecordCount()>0){
		$this->pagina_url= $rs->fields[1];
		$this->pagina_descripcion= $rs->fields[2];
        $this->pagina_activo= $rs->fields[3];
    }else{
		   $sRpta='No Existe Registro de Pagina: ' . $this->pagina_codigo;
	}
    return $sRpta; 
  }
 
}
?>
