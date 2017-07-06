<?php
require_once(PathIncludes() . "mantenimiento.php"); 

class ca_pagina_rol extends mantenimiento{
	var $rol_codigo ="";
	var $pagina_codigo ="";
	var $fecha_reg ="";
	var $pagina_rol_activo ="";  
   
function Save_All($codigos){
	$rpta="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){ 
    	$ssql= "UPDATE ca_pagina_rol ";
    	$ssql.= " SET pagina_rol_activo= 0 ";
    	$ssql.= " WHERE rol_codigo=".$this->rol_codigo ;
        if($codigos!='') $ssql.= " and pagina_codigo not in (" . $codigos . ")";
    	$r = $cn->Execute($ssql);
    	if(!$r){
    		  $rpta = "Error al Actualizar pagina_rol.";
    		  return $rpta;
    	}else{
    		 $rpta= "OK";
    	}
    
        if($codigos!=''){
            //-- reactivo a antiguos	
            $params = array($this->rol_codigo);
    		$ssql= "UPDATE ca_pagina_rol ";
    		$ssql.= " SET pagina_rol_activo= 1 ";
    		$ssql.= " WHERE pagina_codigo in (" . $codigos . ") and rol_codigo=?";
            $r = $cn->Execute($ssql, $params);
    		if(!$r){
    			  $rpta = "Error al reactivar a antiguos.";
    			  return $rpta;
    		}else{
    			 $rpta= "OK";
    		}
    			
    		//insertar nuevos registros
			$ssql = "INSERT INTO ca_pagina_rol(rol_codigo, pagina_codigo, fecha_reg, pagina_rol_activo)";
			$ssql.= " SELECT " . $this->rol_codigo . ", pagina_codigo, getdate(), 1 FROM ca_paginas ";
			$ssql.= " WHERE pagina_codigo in (" . $codigos . ") and pagina_codigo not ";
			$ssql.= " 		in (SELECT pagina_codigo FROM ca_pagina_rol WHERE rol_codigo=" . $this->rol_codigo . " and pagina_codigo in (" . $codigos . "))";
			$r = $cn->Execute($ssql);
			if(!$r){
				  $rpta = "Error al insertar a antiguos.";
				  return $rpta;
			}else{
				 $rpta= "OK";
			}
        }
	}
	return $rpta;
 }

 function Devolver_rol(){
	$rpta="OK";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
        $ssql= "SELECT Rol_Codigo FROM CA_pagina_Rol ";
        $ssql.= " WHERE pagina_codigo =" . $this->pagina_codigo . "  AND pagina_Rol_Activo = 1 ";
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
        	$this->rol_codigo= $rs->fields[0]->value;
        }else{
           $this->rol_codigo =0;
        }
	}
	return $rpta;
 }
 
}
?>
