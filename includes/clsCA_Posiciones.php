<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_posiciones extends mantenimiento{
var $cod_campana="";
var $posicion_empleado_codigo="";
var $subt_posicion_codigo="";
var $tposicion_codigo="";
var $posicion_fecha="";
var $total="";
var $posicion_personal="";


function Buscar_Posiciones(){
	/*$rpta="OK";
	$str="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	  $ssql = "SELECT P.Cod_Campana, P.Sub_TPosicion_Codigo, P.TPosicion_Codigo, P.Posicion_Fecha, P.Total ";
	  $ssql .=" FROM CA_Posiciones P inner join CA_Sub_Tipo_Posicion s ";
	  $ssql .=" on p.Sub_TPosicion_Codigo = s.Sub_TPosicion_Codigo and p.TPosicion_Codigo = S.TPosicion_Codigo ";
	  $ssql .=" where P.Cod_Campana = " . $this->cod_campana . " and P.Posicion_Fecha=convert(datetime,'" . $this->posicion_fecha . "', 103) ";
	  $ssql .=" and s.Sub_TPosicion_Opera=" . $this->posicion_personal;
	  
	  
	  
	  $rs = $this->cnnado->Execute($ssql);
	  if (!$rs->EOF()){
		 $i=0;
		 while(!$rs->EOF()){
		  if($str==""){
		   $str=$rs->fields[1]->value . "," . $rs->fields[2]->value . "," . $rs->fields[4]->value;
		  }else{
		    $str .="_" . $rs->fields[1]->value . "," . $rs->fields[2]->value . "," . $rs->fields[4]->value;
		  }
		  $rs->movenext(); 
	    }
	   }else{
		   $rpta='No Existe Registros';
	  }
	 } 
	return $str;*/
    
    $rpta="OK";
    $str="";
    $cn=$this->getMyConexionADO();
    $ssql = "SELECT P.Cod_Campana, P.Sub_TPosicion_Codigo, P.TPosicion_Codigo, P.Posicion_Fecha, P.Total ";
    $ssql .=" FROM CA_Posiciones P inner join CA_Sub_Tipo_Posicion s ";
    $ssql .=" on p.Sub_TPosicion_Codigo = s.Sub_TPosicion_Codigo and p.TPosicion_Codigo = S.TPosicion_Codigo ";
    $ssql .=" where P.Cod_Campana = " . $this->cod_campana . " and P.Posicion_Fecha=convert(datetime,'" . $this->posicion_fecha . "', 103) ";
    $ssql .=" and s.Sub_TPosicion_Opera=" . $this->posicion_personal;
	  
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $i=0;
        while(!$rs->EOF){
            if($str==""){
                $str=$rs->fields[1] . "," . $rs->fields[2] . "," . $rs->fields[4];
            }else{
                $str .="_" . $rs->fields[1] . "," . $rs->fields[2] . "," . $rs->fields[4];
            }
            $rs->MoveNext(); 
        }
    }else{
        $rpta='No Existe Registros';
    }

    return $str;
    
  }
  
 function Save(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$ssql ="Exec spCA_ts_GuardarPosiciones  ?,'" . $this->posicion_fecha . "',?,?,? ";

		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->cod_campana;
		$cmd->Parameters[1]->value = $this->subt_posicion_codigo;
		if($this->total=="")
		      $cmd->Parameters[2]->value =null;
		else  $cmd->Parameters[2]->value = $this->total;
		$cmd->Parameters[3]->value = $this->posicion_empleado_codigo ;
		$r=$cmd->Execute();
		  if(!$r){
		       $rpta = "Error al Guardar Posiciones .";
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }
   }
   return $rpta;*/
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="Exec spCA_ts_GuardarPosiciones  ?,'" . $this->posicion_fecha . "',?,?,? ";
    //$cmd->Parameters[0]->value = $this->cod_campana;
    //$cmd->Parameters[1]->value = $this->subt_posicion_codigo;
    $params=array(
        $this->cod_campana,
        $this->subt_posicion_codigo
    );
    if($this->total=="")
    //$cmd->Parameters[2]->value =null;
    $params[]=null;
    else  $params[]=$this->total;//$cmd->Parameters[2]->value = $this->total;
    
    $params[]=$this->posicion_empleado_codigo;
    //$cmd->Parameters[3]->value = $this->posicion_empleado_codigo ;
    $rs=$cn->Execute($ssql,$params);
    if(!$rs){
        $rpta = "Error al Guardar Posiciones .";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    return $rpta;
}
 
}
?>
