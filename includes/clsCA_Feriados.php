<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_feriados extends mantenimiento{
var $anio="";
var $mes="";
var $feriado_codigo="";
var $fecha_feriado=""; 
var $feriado_descripcion="";
var $feriado_activo="";
var $tipo_feriado = "";
var $pais_codigo = "";
var $coddpto = "";
function Addnew(){
 /*$rpta="";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){	
	
	$ssql = "select isnull(max(feriado_codigo), 0) + 1 as maximo from ca_feriados ";
	$ssql .= " where anio=(select year(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))) ";
	//echo $ssql;
	$rs= $this->cnnado->Execute($ssql);
	$this->feriado_codigo = $rs->fields['maximo']->value;
		
	//insertar nuevo registro
	$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	$ssql = "INSERT INTO ca_feriados";
	$ssql .= " (feriado_Codigo,Anio,Mes,Fecha_Feriado,feriado_Descripcion, feriado_activo) ";
	$ssql .= " SELECT " . $this->feriado_codigo . ",";
	$ssql .= " (select year(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
    $ssql .= " (select month(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
	$ssql .= " CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103),";
	$ssql .= " '" . $this->feriado_descripcion . "',";
	$ssql .= $this->feriado_activo . "";
	$cmd->ActiveConnection = $this->cnnado;
    $cmd->CommandText = $ssql;
	$cmd->Execute();
	}
	return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $ssql = "select isnull(max(feriado_codigo), 0) + 1 as maximo from ca_feriados ";
    $ssql .= " where anio=(select year(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))) ";
    //echo $ssql;
    //exit(0);
    $rs= $cn->Execute($ssql);
    $this->feriado_codigo = $rs->fields[0];
	if ($this->coddpto == 0) {
		$region = "00";
	}else{
		$region = $this->coddpto;
	}

    $ssql = "INSERT INTO ca_feriados";
    $ssql .= " (feriado_Codigo,Anio,Mes,Fecha_Feriado,feriado_Descripcion, feriado_activo, tipo_feriado, Pais_Codigo, CODDPTO, CODPROV,CODDIST) ";
    $ssql .= " SELECT " . $this->feriado_codigo . ",";
    $ssql .= " (select year(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
    $ssql .= " (select month(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
    $ssql .= " CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103),";
    $ssql .= " '" . $this->feriado_descripcion . "',";

    $ssql .= $this->feriado_activo . ", ".$this->tipo_feriado.",".$this->pais_codigo.",'".$region."','00','00'";
    // echo $ssql;die();
    $rs=$cn->Execute($ssql);
    
    if(!$rs) $rpta="Error";
	
    return $rpta;
    
}

function Update(){
	/*$rpta="";
	$rpta=$this->conectarme_ado();
	if($rpta="OK"){ 
	$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	$cmd->ActiveConnection = $this->cnnado;	
	
    $ssql = "UPDATE ca_feriados ";
	$ssql .= " SET Anio = (select year(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
	$ssql .= "     Mes =  (select month(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
	$ssql .= "     fecha_feriado=CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103),";
    $ssql .= "     feriado_Descripcion = '" . $this->feriado_descripcion . "',";
	$ssql .= "     feriado_activo = " . $this->feriado_activo ; 
	$ssql .= " Where anio=" . $this->anio . " and  feriado_Codigo = " . $this->feriado_codigo; 
	//echo $ssql;
	$cmd->CommandText = $ssql;
	$cmd->Execute();	
  }
 return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
	
    $ssql = "UPDATE ca_feriados ";
    $ssql .= " SET Anio = (select year(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
    $ssql .= "     Mes =  (select month(CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103))),";
    $ssql .= "     fecha_feriado=CONVERT(DATETIME,'" . $this->fecha_feriado . "', 103),";
    $ssql .= "     feriado_Descripcion = '" . $this->feriado_descripcion . "',";
    $ssql .= "     feriado_activo = " . $this->feriado_activo . ","; 
    $ssql .= "     tipo_feriado = " . $this->tipo_feriado . ",";
    $ssql .= "     pais_codigo = " . $this->pais_codigo . ",";
    $ssql .= "     CODDPTO = '" . $this->coddpto . "', CODPROV = '00', CODDIST = '00' ";
    $ssql .= " Where anio=" . $this->anio . " and  feriado_Codigo = " . $this->feriado_codigo; 
    
    
    $rs=$cn->Execute($ssql);	
    if(!$rs) $rpta="Error";
  
 return $rpta;
}

function Query(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	
	$ssql = "SELECT feriado_Codigo,anio, mes,convert(varchar(10),fecha_feriado,103),feriado_Descripcion, abs(feriado_activo) ";
	$ssql .= " FROM ca_feriados ";
	$ssql .= " WHERE anio=" . $this->anio . " and  feriado_Codigo = " . $this->feriado_codigo;
	
	//echo $ssql;
     $rs = $this->cnnado->Execute($ssql);
	 if (!$rs->EOF){
			$this->fecha_feriado = $rs->fields[3]->value;
			$this->feriado_descripcion= $rs->fields[4]->value;
			$this->feriado_activo=  $rs->fields[5]->value;
	  }else{
		   $rpta='No Existe Registro de feriado: ' . $this->feriado_codigo;
	  }
	}
	return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "SELECT feriado_Codigo,anio, mes,convert(varchar(10),fecha_feriado,103),feriado_Descripcion, abs(feriado_activo), ";
    $ssql .= " Pais_Codigo, CODDPTO, Tipo_Feriado ";

    $ssql .= " FROM ca_feriados ";
    $ssql .= " WHERE anio='".$this->anio."' and  feriado_Codigo = " . $this->feriado_codigo;
    
    $rs=$cn->Execute($ssql);
    
    if ($rs->RecordCount()>0){
        $this->fecha_feriado = $rs->fields[3];
        $this->feriado_descripcion= $rs->fields[4];
        $this->feriado_activo=  $rs->fields[5];
        $this->pais_codigo=  $rs->fields[6];
        $this->coddpto=  $rs->fields[7];
        $this->tipo_feriado=  $rs->fields[8];

    }else{
        $rpta='No Existe Registro de feriado: ' . $this->feriado_codigo;
    }	
	
    return $rpta;
    
}
}
