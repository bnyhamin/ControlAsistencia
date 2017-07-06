<?php
//require_once(PathIncludes() . "mantenimiento.php");
class CA_Estados extends mantenimiento{
var $CA_Estado_codigo="";
var $CA_Estado_descripcion="";
var $CA_Estado_activo="";

function Addnew(){
/*$rpta="";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){
        //obtener nuevo codigo de registro a insertar
        $ssql = "select isnull(max(ca_estado_codigo), 0) + 1 as maximo from ca_estados ";
        $rs= $this->cnnado->Execute($ssql);
        $this->CA_Estado_codigo = $rs->fields[0]->value;
        $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
        //insertar nuevo registro
        $ssql = "INSERT INTO ca_estados";
        $ssql .= " (ca_estado_Codigo, ca_Estado_Descripcion, ca_estado_activo) ";
        $ssql .= " VALUES (?,?,?)";
    $cmd->ActiveConnection = $this->cnnado;
        $cmd->CommandText = $ssql;
$cmd->Parameters[0]->value = $this->CA_Estado;
$cmd->Parameters[1]->value = $this->CA_Estado_descripcion;
$cmd->Parameters[2]->value = $this->CA_Estado_activo;
        $cmd->Execute();
}
return $rpta;  */
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    //obtener nuevo codigo de registro a insertar
    $ssql = "select isnull(max(ca_estado_codigo), 0) + 1 as maximo from ca_estados ";
    $rs= $cn->Execute($ssql);
    $this->CA_Estado_codigo = $rs->fields[0];
    
    //insertar nuevo registro
    $ssql = "INSERT INTO ca_estados";
    $ssql .= " (ca_estado_Codigo, ca_Estado_Descripcion, ca_estado_activo) ";
    $ssql .= " VALUES (?,?,?)";
        
    $params=array(
        $this->CA_Estado_codigo,
        $this->CA_Estado_descripcion,
        $this->CA_Estado_activo
    );
    
    $rs=$cn->Execute($ssql,$params);
    if(!$rs) $rpta="Error";

    return $rpta;
    
}

function Update(){
	/*$rpta="";
	$rpta=$this->conectarme_ado();
	if($rpta="OK"){ 
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = "UPDATE CA_Estados ";
		$ssql .= " SET CA_Estado_Descripcion = '" . $this->CA_Estado_descripcion . "',";
		$ssql .= "     CA_Estado_activo = " . $this->CA_Estado_activo; 
		$ssql .= " Where CA_Estado_Codigo =? "; 
				
		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->CA_Estado_codigo;
		$cmd->Execute();
	}	
	return $rpta;*/

        $rpta="OK";
	$cn=$this->getMyConexionADO();
        
        $ssql = "UPDATE CA_Estados ";
        $ssql .= " SET CA_Estado_Descripcion = ? , ";
        $ssql .= "     CA_Estado_activo = ? ";
        $ssql .= " Where CA_Estado_Codigo = ? ";
				
        $params=array(
            $this->CA_Estado_descripcion,
            $this->CA_Estado_activo,
            $this->CA_Estado_codigo
        );
        	
        $rs=$cn->Execute($ssql,$params);
	if(!$rs) $rpta="Error";
        
	return $rpta;
}

function Query(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = "SELECT CA_Estado_Codigo, CA_Estado_Descripcion, abs(CA_Estado_activo) ";
		$ssql .= " FROM CA_Estados ";
		$ssql .= " WHERE CA_Estado_Codigo = " . $this->CA_Estado_codigo;
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->CA_Estado_descripcion = $rs->fields[1]->value;
			$this->CA_Estado_activo= $rs->fields[2]->value;
	  }else{
		   $rpta='No Existe Registro de CA_Estado: ' . $this->CA_Estado_codigo;
	  }
	 } 
	return $rpta;*/
        $rpta="OK";
	$cn=$this->getMyConexionADO();
        $ssql = "SELECT CA_Estado_Codigo, CA_Estado_Descripcion, abs(CA_Estado_activo) ";
        $ssql .= " FROM CA_Estados ";
        $ssql .= " WHERE CA_Estado_Codigo = ? ";
        $params=array(
            $this->CA_Estado_codigo
        );
        $rs = $cn->Execute($ssql,$params);
        if ($rs->RecordCount()>0){
            $this->CA_Estado_descripcion=$rs->fields[1];
            $this->CA_Estado_activo=$rs->fields[2];
        }else{
            $rpta='No Existe Registro de CA_Estado: '.$this->CA_Estado_codigo;
        }
	
	return $rpta;
  }
 
}
?>
