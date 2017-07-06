<?php
//ca_controller -> asigna areas a controller
require_once(PathIncludes() . "mantenimiento.php"); 

class ca_controller_area extends mantenimiento{
	var $controller_codigo ="";
	var $area_codigo ="";
	var $rol_codigo ="";
	var $emp_codigo ="";
	var $activo ="";  
	var $fecha_registro ="";
	var $usuario_registro ="";
	var $fecha_modifica ="";
	var $usuario_modifica ="";
 
 Function Asignar_area(){
 $rpta = "OK";
 $ssql = "";
 //$rpta=$this->conectarme_ado();
 $cn = $this->getMyConexionADO();
 if($cn){ 
 			$cn->BeginTrans();
 			
            $ssql = "SELECT * FROM ca_controller ";
            $ssql .=" WHERE area_codigo=" . $this->area_codigo;
            $ssql .=" and empleado_codigo=" . $this->empleado_codigo . " and activo=0";
            $rs = $cn->Execute($ssql);
            //echo $ssql;
	        if (!$rs->EOF){
				$ssql = "UPDATE ca_controller SET activo=1,";
				$ssql .=" fecha_modifica=getdate(), usuario_modifica=" . $this->usuario_modifica;
                $ssql .=" WHERE area_codigo=" . $this->area_codigo;
                $ssql .=" and empleado_codigo=" . $this->empleado_codigo;
                $r = $cn->Execute($ssql);

				if(!$r){
					  $rpta = "Error al asignar area a empleado.";
					  $cn->RollbackTrans();
					  return $rpta;
				}else{
					 $rpta= "OK";
				}
		    }else{
			        
				 $ssql="SELECT isnull(max(controller_codigo),0)+1 as id FROM ca_controller ";
		         $rs= $cn->Execute($ssql);
		         $this->controller_codigo = $rs->fields[0];
									
                 $ssql="INSERT INTO ca_controller";
                 $ssql .=" (controller_codigo, area_codigo, empleado_codigo, activo, fecha_registro, usuario_registro)";
	             $ssql .=" values (". $this->controller_codigo . ",". $this->area_codigo . "," . $this->empleado_codigo . ", 1, getdate(),". $this->usuario_registro . ")";

		         $r=$cn->Execute($ssql);
				 if(!$r){
					  $rpta = "Error al insertar area a empleado.";
					  $cn->RollbackTrans();
					  return $rpta;
				 }else{
					 $rpta= "OK";
				 }
			}
            
         $cn->CommitTrans();  
         }
        return $rpta;
}
     
 function Desasignar_area(){
    $rpta = "OK";
    $ssql = "";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql = "UPDATE ca_controller SET activo=0, ";
        $ssql .=" fecha_modifica=getdate(), usuario_modifica=" . $this->usuario_modifica;
        $ssql .=" WHERE empleado_codigo=" . $this->empleado_codigo;
        $ssql .=" and area_codigo=" . $this->area_codigo;
        $ssql .=" and activo=1";

        $r=$cn->Execute($ssql);
        
		if(!$r){
			$rpta = "Error al desactivar empleados a em.";
			return $rpta;
		}else{
			$rpta= "OK";
		}
      }
      return $rpta;
     }
     
 function Devolver_area(){
	$rpta="OK";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
	$ssql= "SELECT empleado_codigo FROM CA_Controller ";
	$ssql.= " WHERE area_codigo =" . $this->area_codigo . "  AND Activo = 1 ";
	$rs = $cn->Execute($ssql);
       if (!$rs->EOF){
			$this->empleado_codigo= $rs->fields[0];
	   }else{
	       $this->empleado_codigo=0;
	   }
	}
	return $rpta;
 }

 function Verifica_area(){
	$rpta="OK";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
	$ssql= "SELECT * FROM CA_Controller ";
	$ssql.= " WHERE area_codigo =" . $this->area_codigo . "  AND empleado_codigo =" . $this->empleado_codigo . "  AND Activo = 1 ";
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
