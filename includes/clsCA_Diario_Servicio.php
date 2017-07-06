<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_diario_servicio extends mantenimiento{
var $Diario_Codigo="";
var $cod_campana="";
var $THoja_Codigo="";
var $Diario_Fecha="";
var $Diario_Horas="";
var $Diario_Comentario="";
var $Diario_Responsable="";

function Listar_Diario_Servicio(){
    /*$rpta="OK";
    $cadena="";
    $rpta=$this->conectarme_ado();
    if($rpta=="OK"){
      $ssql = "select * from ca_diario_servicio where cod_campana = " . $this->cod_campana . " and THoja_Codigo = " . $this->THoja_Codigo . "  and ";
      $ssql .= "	Diario_Fecha >= CONVERT(DATETIME, '" . $this->Diario_Fecha . "', 103) and Diario_Fecha < CONVERT(DATETIME, '" . $this->Diario_Fecha ."', 103)+1 ";	
      $rs = $this->cnnado->Execute($ssql);
            if (!$rs->EOF){
                    $this->Diario_Horas= $rs->fields[4]->value;
                    $this->Diario_Comentario= $rs->fields[5]->value;
                $this->Diario_Responsable=$rs->fields[6]->value;

       }else{
               $rpta='No Existe Registros';
      }
     } 
    return $cadena;*/
    
    $rpta="OK";
    $cadena="";
    $cn=$this->getMyConexionADO();
	
    $ssql = "select * from ca_diario_servicio where cod_campana = " . $this->cod_campana . " and THoja_Codigo = " . $this->THoja_Codigo . "  and ";
    $ssql .= "	Diario_Fecha >= CONVERT(DATETIME, '" . $this->Diario_Fecha . "', 103) and Diario_Fecha < CONVERT(DATETIME, '" . $this->Diario_Fecha ."', 103)+1 ";	
          
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $this->Diario_Horas= $rs->fields[4];
        $this->Diario_Comentario= $rs->fields[5];
        $this->Diario_Responsable=$rs->fields[6];
    }else{
        $rpta='No Existe Registros';
    }
    return $cadena;
  }
  
 function Save(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$ssql ="Exec spCA_IU_Diario_Servicio ?,?,'" . $this->Diario_Fecha . "','" . $this->Diario_Horas ."','" . $this->Diario_Comentario . "',? ";

		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->cod_campana;
		$cmd->Parameters[1]->value = $this->THoja_Codigo;
		$cmd->Parameters[2]->value = $this->Diario_Responsable;
		$r=$cmd->Execute();
		  if(!$r){
		       $rpta = "Error al Insertar Diario Servicio .";
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }
   }
   return $rpta;*/
     
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql ="Exec spCA_IU_Diario_Servicio ?,?,'" . $this->Diario_Fecha . "','" . $this->Diario_Horas ."','" . $this->Diario_Comentario . "',? ";

    $params=array(
        $this->cod_campana,
        $this->THoja_Codigo,
        $this->Diario_Responsable
    );

    $rs=$cn->Execute($ssql,$params);

    if(!$rs){
        $rpta = "Error al Insertar Diario Servicio .";
        return $rpta;
    }else{
        $rpta= "OK";
    }

    return $rpta;
     
}
 
}
?>

