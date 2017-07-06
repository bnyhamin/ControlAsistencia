<?php
//require_once(PathIncludes() . "mantenimiento.php");
//require_once("../../includes/mantenimiento.php"); 
class ca_roles extends mantenimiento{
var $rol_codigo="";
var $rol_descripcion="";
var $rol_activo="";

function Addnew(){
    $sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();

    //obtener nuevo codigo de registro a insertar
    $sSql = "SELECT isnull(max(rol_codigo), 0) + 1 as maximo FROM ca_roles ";
    $rs = $cn->Execute($sSql);
    $this->rol_codigo= $rs->fields[0];
    
    //insertar nuevo registro
    $params = array($this->rol_codigo,
                    $this->rol_descripcion,
                    $this->rol_activo);
    
    $sSql = "INSERT INTO ca_roles";
	$sSql .= " (rol_Codigo, rol_Descripcion, rol_activo) ";
	$sSql .= " VALUES (?,?,?)";
    $rs = $cn->Execute($sSql, $params);
	
    if($rs==false) $sRpta="Error al Insertar registro";
    return $sRpta;
}

function Update(){
	$sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array($this->rol_descripcion,
                    $this->rol_activo,
                    $this->rol_codigo);

	$sSql = "UPDATE ca_roles ";
	$sSql .= " SET Rol_Descripcion = ? ";
	$sSql .= "     ,Rol_Activo = ? "; 
	$sSql .= " WHERE Rol_Codigo = ? "; 
			
    $rs = $cn->Execute($sSql, $params);
    //echo $sSql;
    if ($rs==false) $sRpta="Error al Actualizar registro";
    return $sRpta;
}

function Query(){
	$sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    
	$params = array($this->rol_codigo);
    $sSql = "SELECT rol_Codigo, rol_Descripcion, abs(rol_activo) ";
    $sSql .= " FROM ca_roles ";
    $sSql .= " WHERE Rol_Codigo = ? ";
    
    $rs = $cn->Execute($sSql, $params);
    
    if ($rs->RecordCount()>0){
		$this->rol_descripcion= $rs->fields[1];
		$this->rol_activo= $rs->fields[2];
    }else{
		   $sRpta='No Existe Registro de Rol: ' . $this->rol_codigo;
	}
    return $sRpta; 
   

  }

}
?>
