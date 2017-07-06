<?php

require_once(PathIncludes() . "mantenimiento.php"); 

class ca_empleado_rol extends mantenimiento{
	var $rol_codigo ="";
	var $area_codigo ="";
	var $empleado_codigo ="";
	var $fecha_reg ="";
	var $empleado_rol_activo ="";  
    var $usuario_codigo = "";
   
function Save_All($codigos){
	$rpta="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){ 
    	$ssql= "UPDATE ca_empleado_rol ";
    	$ssql.= " SET empleado_rol_activo= 0 ";
    	$ssql.= " WHERE rol_codigo=".$this->rol_codigo;
    	if($codigos!='') $ssql.= " and empleado_codigo not in (" . $codigos . ")";
    	
        $r = $cn->Execute($ssql);
    	
        if(!$r){
    		  $rpta = "Error al Actualizar empleado_rol.";
    		  return $rpta;
    	}else{
    		 $rpta= "OK";
    	}

        if($codigos!=''){
	        //-- reactivo a antiguos	
			$ssql= "UDPATE ca_empleado_rol ";
			$ssql.= " SET empleado_rol_activo= 1 ";
			$ssql.= " WHERE empleado_codigo in (" . $codigos . ") and rol_codigo=".$this->rol_codigo." ";
			$r = $cn->Execute($ssql);
            if(!$r){
				  $rpta = "Error al reactivar a antiguos.";
				  return $rpta;
			}else{
				 $rpta= "OK";
			}
			
            //insertar nuevos registros
			$ssql = "INSERT INTO ca_empleado_rol(rol_codigo, empleado_codigo, fecha_reg, empleado_rol_activo)";
			$ssql.= " SELECT " . $this->rol_codigo . ", empleado_codigo, getdate(), 1 FROM empleados ";
			$ssql.= " WHERE empleado_codigo in (" . $codigos . ") and empleado_codigo not ";
			$ssql.= " 		in (SELECT empleado_codigo FROM ca_empleado_rol WHERE rol_codigo=" . $this->rol_codigo . " and empleado_codigo in (" . $codigos . "))";
            $r = $cn->Execute($ssql);
			if(!$r){
				  $rpta = "Error al insertar a antiguos.";
				  return $rpta;
			}else{
				 $rpta= "OK";
			}
			
			if($this->rol_codigo==1){
			$ssql = "INSERT INTO ca_asignaciones(responsable_codigo,empleado_codigo_asigna,fecha_reg,asignacion_activo)";
			$ssql.= " SELECT  empleado_codigo,empleado_codigo, getdate(), 1 FROM empleados ";
			$ssql.= " WHERE empleado_codigo in (" . $codigos . ") and empleado_codigo not ";
			$ssql.= " 		in (SELECT responsable_codigo FROM ca_asignaciones WHERE responsable_codigo in (" . $codigos . "))";
			
            $r = $cn->Execute($ssql);
			if(!$r){
				  $rpta = "Error al insertar a responsables.";
				  return $rpta;
			}else{
				 $rpta= "OK";
			}
			
			}	
			
	   }
	}
	return $rpta;
 }
 
Function Asignar_rol(){
    $rpta = "OK";
    $ssql = "";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    if($cn){ 
        $cn->BeginTrans();
        //$this->cnnado->BeginTrans();
        $ssql = "SELECT * FROM ca_empleado_rol ";
        $ssql .=" WHERE empleado_codigo=" . $this->empleado_codigo;
        $ssql .=" and rol_codigo=" . $this->rol_codigo . " and empleado_rol_activo=0";
        $rs = $cn->Execute($ssql);
        //$rs = $this->cnnado->Execute($ssql);
        
        if (!$rs->EOF){
        	$ssql = "UPDATE ca_empleado_rol SET empleado_rol_activo=1,usuario_modifica = ".$this->usuario_codigo .", fecha_modifica=getdate() ";
            $ssql .=" WHERE empleado_codigo=" . $this->empleado_codigo;
            $ssql .=" and rol_codigo=" . $this->rol_codigo;
            $r = $cn->Execute($ssql);
            
        	if(!$r){
                $rpta = "Error al activar rol a empleado.";
                $cn->RollbackTrans();
                //$this->cnnado->RollbackTrans();
                return $rpta;
        	}else{
        		$ssql = " UPDATE CA_Asignaciones SET asignacion_activo=1 ";
        		$ssql.= " WHERE responsable_codigo=" . $this->empleado_codigo;
        		$r = $cn->Execute($ssql);
        		
                if(!$r){
        			$rpta = "Error al activar a empleado como responsable.";
                    $cn->RollbackTrans();
        			//$this->cnnado->RollbackTrans();
        			return $rpta;
        		}else{
        			$rpta= "OK";
        		}
        	}
        }else{
            $ssql="INSERT INTO ca_empleado_rol(empleado_codigo,rol_codigo,fecha_reg,empleado_rol_activo,usuario_registro) ";
            $ssql .=" values(" . $this->empleado_codigo ."," . $this->rol_codigo . ",getdate(),1,".$this->usuario_codigo.")";
            $r = $cn->Execute($ssql);

    		if(!$r){
                $rpta = "Error al insertar rol a empleado.";
                $cn->RollbackTrans();
    			//$this->cnnado->RollbackTrans();
    			return $rpta;
    		}else{
    			$rpta= "OK";
    		}
    	    if($this->rol_codigo==1){
                $ssql = "INSERT INTO ca_asignaciones(responsable_codigo,empleado_codigo_asigna,fecha_reg,asignacion_activo)";
                $ssql.= " SELECT  empleado_codigo,empleado_codigo, getdate(), 1 FROM empleados ";
                $ssql.= " WHERE empleado_codigo=" . $this->empleado_codigo  . " and empleado_codigo not ";
                $ssql.= " 		in (SELECT responsable_codigo FROM ca_asignaciones WHERE responsable_codigo=" . $this->empleado_codigo . ")";
                $r = $cn->Execute($ssql);
                
                if(!$r){
                    $rpta = "Error al insertar a responsable.";
                    $cn->RollbackTrans();
                    //$this->cnnado->RollbackTrans();
                    return $rpta;
                }else{
                    $rpta= "OK";
                }
                
            }
        }
        $cn->CommitTrans();    
        //$this->cnnado->CommitTrans();  
    }
    return $rpta;
}
     
 function Desasignar_rol(){
    $rpta = "OK";
    $ssql = "";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if($cn){ 
        $ssql = "UPDATE ca_empleado_rol SET empleado_rol_activo=0,usuario_modifica = ".$this->usuario_codigo .", fecha_modifica=getdate() ";
        $ssql .=" WHERE empleado_codigo=" . $this->empleado_codigo;
        $ssql .=" and rol_codigo=" . $this->rol_codigo;
        $ssql .=" and empleado_rol_activo=1";
        $r = $cn->Execute($ssql);
        
        if ($this->rol_codigo==1){
            $ssql =" UPDATE CA_Asignaciones ";
            $ssql .=" SET    Asignacion_Activo = 0 ";
            $ssql .=" WHERE  Responsable_codigo = " . $this->empleado_codigo;
            $r = $cn->Execute($ssql);
        }
        
        if(!$r){
        	$rpta = "Error al desactivar rol a empleado.";
        	return $rpta;
        }else{
        	$rpta= "OK";
        }
    }
    return $rpta;
}
     
 function Devolver_rol(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array($this->empleado_codigo);
    	$ssql= "SELECT Rol_Codigo FROM CA_Empleado_Rol ";
    	$ssql.= " WHERE Empleado_codigo =?  AND Empleado_Rol_Activo = 1 ";
    	$rs = $cn->Execute($ssql, $params);
           if (!$rs->EOF){
    			$this->rol_codigo= $rs->fields[0];
    	   }else{
    	       $this->rol_codigo =0;
    	   }
	}
	return $rpta;
 }
 
 function Devolver_roles(){
 
  $rpta="OK";
	$cn = $this->getMyConexionADO();
  $arr_roles = array();
  
	if($cn){
  
	    $params = array($this->empleado_codigo);
    	$ssql= "SELECT Rol_Codigo FROM CA_Empleado_Rol ";
    	$ssql.= " WHERE Empleado_codigo =?  AND Empleado_Rol_Activo = 1 ";
    	$rs = $cn->Execute($ssql, $params);

      if ($rs->RecordCount()){
              
              while(!$rs->EOF){
                 $arr_roles[] =  $rs->fields[0];
                 $rs->MoveNext();
              }
      }     
	}
	return $arr_roles;

 }
 
 function Verifica_rol(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql= "SELECT * FROM CA_Empleado_Rol ";
        $ssql.= " WHERE Empleado_codigo =" . $this->empleado_codigo . "  AND Rol_Codigo =" . $this->rol_codigo . "  AND Empleado_Rol_Activo = 1 ";
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            $rpta='OK';
        }else{
            $rpta='nOK';
        }
    }
    return $rpta;
 }
}
?>
