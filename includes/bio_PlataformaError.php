<?php

class BIO_Plataforma extends mantenimiento {

function listarLectoresAsignados($plataforma_id)
{

	     $cn=$this->getMyConexionADO();
        
       $ssql = "SELECT plataforma_lector_id as codigo, codigo_equipo as codigoEquipo, bio_lector.lector_id as lector, lector_ip as ip, lector_puerto as puerto, (CASE WHEN lector_tipo_acceso = 'E' then 'Entrada' else 'Salida' end) as tipoAcceso, plataforma_lector_activo  as activo, lector_nombre";
       $ssql .= " FROM  (bio_plataforma_lector inner join bio_lector on bio_plataforma_lector.lector_id=bio_lector.lector_id) inner join [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas as plataforma  on bio_plataforma_lector.plataforma_id = plataforma.plataforma_id  ";
       $ssql .= " WHERE bio_plataforma_lector.plataforma_id = ? ";

        $params=array(
            $plataforma_id
        );
       
	   $cn->Execute("Set ANSI_NULLS ON ");  
       $cn->Execute("Set ANSI_WARNINGS ON ");
	   $cn->SetFetchMode(ADODB_FETCH_ASSOC);
	   $rs = $cn->Execute($ssql, $params);
        
	   $data = array();
				
   
        if($rs->RecordCount()>0) {
            	
          while (!$rs->EOF) {
				
			$data[] = $rs->fields;
				
			$rs->MoveNext(); 
		   
		  }
		   

		 
		}
		
		  echo json_encode(array("data" => $data)); 
  }
  
  function obtenerNombrePlataforma($plataforma_id)
  {
  	  $cn=$this->getMyConexionADO();
        
       $ssql = "SELECT Plataforma_Descrip";
       $ssql .= " FROM  [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas ";
       $ssql .= " WHERE Plataforma_Id = ? ";
		
        $params=array(
            $plataforma_id
        );
       
	   $cn->Execute("Set ANSI_NULLS ON ");  
       $cn->Execute("Set ANSI_WARNINGS ON ");
	 
	   $rs = $cn->Execute($ssql, $params);
        
	  
        if($rs->RecordCount()>0) {
            	
		   echo $rs->fields[0];

		}
			

  }
 
function listarLectores($plataforma_id, $lector_id = NULL)
{	
	   $cn=$this->getMyConexionADO();
       $cn->SetFetchMode(ADODB_FETCH_ASSOC);
        
       $ssql = "SELECT lector_id, lector_nombre";
       $ssql .= " FROM bio_lector l";
      
       $ssql2 = "";
       
       if(!empty($lector_id)) {
            		
            $ssql2 = "and pl.lector_id not in(?)";	
 
            $params=array( $plataforma_id, $lector_id);
            
       }else{
       			
			 $params=array( $plataforma_id);
       
       }
       
	    $ssql .= " WHERE  lector_activo = 1 and  lector_id not in (select lector_id from bio_plataforma_lector pl where pl.lector_id = l.lector_id and plataforma_id=? $ssql2)";
	   

	    $rs = $cn->Execute($ssql, $params);
	   
	    $data = array();
				
   
        if($rs->RecordCount()>0) {
            	
          while (!$rs->EOF) {
				
    			$data[] = $rs->fields;
    				
    			$rs->MoveNext(); 
		   
		    }
		}
		
		echo json_encode(array("data" => $data)); 
	
} 
  
function asignarLectorPlataforma( $lector_id, $plataforma_id, $activo)
{
	
    $cn=$this->getMyConexionADO();
    
    //insertar nuevo registro
    $ssql = "INSERT INTO bio_plataforma_lector";
    $ssql .= " (plataforma_id, lector_id, plataforma_lector_activo, usuario_registro)";
    $ssql .= " VALUES (?,?,?,?)";
        
    $params=array( $plataforma_id, $lector_id, $activo, $_SESSION["empleado_codigo"] );
    	
    $rs=$cn->Execute($ssql,$params);
	
    if($rs)
            echo json_encode(array("success" => true, "msg" => 'Se inserto correctamente'));
        else
            echo json_encode(array("success" => false, "msg" => 'Ocurrió un error al insertar'));
    
}

function editarLectorPlataforma($lector_id, $id, $activo)
{

	      $cn=$this->getMyConexionADO();
        
        $ssql = "UPDATE bio_plataforma_lector ";
        $ssql .= " SET fecha_modificacion  = getdate() , ";
        $ssql .= "     lector_id = ?,";
		    $ssql .= "     plataforma_lector_activo = ?, ";
	   	  $ssql .= "     usuario_modificacion = ? ";
        $ssql .= " Where plataforma_lector_id = ? ";
				
        
		
        
        $params=array( $lector_id, $activo, $_SESSION["empleado_codigo"], $id );
        	
        $rs=$cn->Execute($ssql,$params);
		
	    if($rs)
            echo json_encode(array("success" => true, "msg" => 'Se Actualizó correctamente'));
        else
            echo json_encode(array("success" => false, "msg" => 'Ocurrió un error al actualizar'));
}


function eliminarLectorPlataforma($id)
{
	    $cn=$this->getMyConexionADO();
        
        $ssql = "DELETE FROM bio_plataforma_lector ";
        $ssql .= " Where plataforma_lector_id = ? ";
				
        $params=array( $id );
        	
        $rs=$cn->Execute($ssql,$params);
		
	    if($rs)
            echo json_encode(array("success" => true, "msg" => 'Se eliminó correctamente'));
        else
            echo json_encode(array("success" => false, "msg" => 'Ocurrió un error al eliminar'));
}

function verificarPlataformasAsignadas($lector_id, $plataforma_id, $edicion=false)
{
      $plataforma_str = "";
       
      $cn=$this->getMyConexionADO();
            
      $ssql = "SELECT p.Plataforma_Id, p.Plataforma_Descrip";
      $ssql .= " FROM  [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas as p join bio_plataforma_lector as pl on p.Plataforma_Id=pl.plataforma_id  ";
      $ssql .= " WHERE pl.lector_id = ? ";
    	
      //echo $ssql;
      	  
      $params=array( $lector_id );
        
    	$cn->Execute("Set ANSI_NULLS ON ");  
      $cn->Execute("Set ANSI_WARNINGS ON ");
    	 
    	$rs = $cn->Execute($ssql, $params);
           
      $data = array();
      $plataforma_arr = array(); 
           
      if($rs->RecordCount()>0) {
                	
    		  while (!$rs->EOF) {
    				
        		$data[] = $rs->fields[1];	
            $plataforma_arr[] = $rs->fields[0];	
        		$rs->MoveNext(); 
    		   
    		  }              
            $plataforma_str = implode("|*|", $data);
    		}
      
         if(count($plataforma_arr)){
         
             if (in_array($plataforma_id, $plataforma_arr)) {
             
                  $plataforma_str = "" ;
             
             }  

         }
      
        echo $plataforma_str;
}

function listarAccesos()
{ 
          $sRpta = "OK";
          $sSql ="";
          $cn = $this->getMyConexionADO();
  
          $sSql=" SELECT Plataforma_Id, plataforma_descrip 
                  FROM [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas 
                  WHERE Plataforma_Estado=1";												   
  					
          $cn->Execute("Set ANSI_NULLS ON ");  
          $cn->Execute("Set ANSI_WARNINGS ON ");
            
          $rs = $cn->Execute($sSql);
  		  
          if ($rs->RecordCount()>0){
              while(!$rs->EOF){
                  
                  echo "<tr><td class='DataTD'>
                        <input type='checkbox' name='chkPlataformas" . $rs->fields[0] . "' id='chk" . $rs->fields[0] . "' value='".$rs->fields[0]."'></td><td class='DataTD'>".$rs->fields[1]."</td></tr>";
                 
                  $rs->MoveNext();
              }
          }else{
          	$sRpta="Error al consultar Accesos.";
          }
}

function listarEmpleadosSeleccionados($ids)
{
          $sRpta = "OK";
          $sSql ="";
          $cn = $this->getMyConexionADO();
  
          $sSql=" SELECT Empleado_Codigo, empleado, Empleado_Dni ,Area_Descripcion   
                  FROM vdatos
                  WHERE Empleado_Codigo in ($ids)";												   
  					
          $rs = $cn->Execute($sSql );
  		  
          if ($rs->RecordCount()>0){
              $n = 0;
              while(!$rs->EOF){
                  $n++;
                  echo "<tr><td class='DataTD'>".$n."</td><td class='DataTD'>".$rs->fields[1]."</td><td class='DataTD'>".$rs->fields[2]."</td><td class='DataTD'>".$rs->fields[3]."</td></tr>";
                 
                  $rs->MoveNext();
              }
          }else{
          	$sRpta="Error al consultar Accesos.";
          }
}  
 
function asignarEmpleadosPlataformas($arr_empleados = array(), $arr_plataformas = array(), $tipo_acceso, $fecha_inicio, $fecha_fin, $observacion, $permiso_activo)
{
      if(!isset($permiso_activo)){
      
         $permiso_activo = 0 ;
      }

      if($tipo_acceso == 'p'){
      
           $fecha_inicio = date("Y-m-d H:i:s") ;
           $fecha_fin    = date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+20) ) ;
      
      }else{
      
            $arr_finicio = explode("/",$fecha_inicio);
            $fecha_inicio = $arr_finicio[2]."-".$arr_finicio[1]."-".$arr_finicio[0];
            
            $arr_ffin = explode("/",$fecha_fin);
            $fecha_fin = $arr_ffin[2]."-".$arr_ffin[1]."-".$arr_ffin[0];
      }


    $cn=$this->getMyConexionADO();
    
    
    for ($i=0; $i<count($arr_empleados); $i++){
    
              $empleado_codigo = $arr_empleados[$i];
             for ($j=0; $j<count($arr_plataformas); $j++){
             
                $plataforma_id = $arr_plataformas[$j];
                
                 $sSql0=" SELECT permiso_id   
                          FROM bio_biometrico_permisos
                          WHERE empleado_codigo = ? and plataforma_id = ?";												   
  					
                  $params0=array($empleado_codigo, $plataforma_id) ;
                  
                  $rs0 = $cn->Execute($sSql0, $params0 );
          		  
                  if (!$rs0->RecordCount()){
                  
                      $ssql = "INSERT INTO bio_biometrico_permisos";
                      $ssql .= " (empleado_codigo, plataforma_id, fecha_inicio, fecha_fin, observacion, permiso_activo, usuario_registro, fecha_registro)";
                      $ssql .= " VALUES (?,?,?,?,?,?,?, getdate() )";
                     
          
                      $params=array($empleado_codigo, $plataforma_id, $fecha_inicio, $fecha_fin, $observacion , $permiso_activo, $_SESSION["empleado_codigo"] );
                      	
                      $rs=$cn->Execute($ssql,$params);


                  }

             }
    }

} 

function listarPlataformasAsignadasEmpleados($empleado_codigo)
{
      $sRpta = "OK";
      $sSql  ="";
      $cn = $this->getMyConexionADO();
  
      $sSql=" SELECT Plataforma_Descrip, convert(varchar(10), fecha_inicio,103), convert(varchar(10), fecha_fin,103), observacion, (CASE WHEN permiso_activo = 1 then 'Si' else 'No' end) as activo, usuario_registro,  convert(varchar(10), fecha_registro,103), permiso_id     
                  FROM bio_biometrico_permisos bp join [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas vp on  bp.plataforma_id = vp.plataforma_id
                  WHERE empleado_codigo = ?";												   
  					
      $params=array($empleado_codigo) ;
      
      $cn->Execute("Set ANSI_NULLS ON ");  
      $cn->Execute("Set ANSI_WARNINGS ON ");
      
      $rs = $cn->Execute($sSql, $params);
  		  
      if ($rs->RecordCount()>0){
              
            $n = 0;
             while(!$rs->EOF){
                  $n++;
                  
                  $checked = ($rs->fields[4]) ? "checked":"" ;
                  echo "<tr><td class='DataTD'>".$n."</td><td class='DataTD'>".$rs->fields[0]."</td><td class='DataTD'>".$rs->fields[1]."</td><td class='DataTD'>".$rs->fields[2]."</td><td class='DataTD'>".$rs->fields[3]."</td><td class='DataTD'>".$rs->fields[4]."</td><td class='DataTD'>".$this->obtenNombreEmpleadoPlataforma($rs->fields[5])."</td><td class='DataTD'>".$rs->fields[6]."</td><td><input id='permiso_".$rs->fields[7]."' type='checkbox' name='permiso_".$rs->fields[7]."' value='".$rs->fields[7]."' $checked /></td></tr>";
                  $rs->MoveNext(); 
              }
      }else{
          	
            $sRpta="Error al consultar asignaci&oacute;n.";
      }
}

function obtenNombreEmpleadoPlataforma($empleado_codigo)
{
       $nombre_empleado = "";
       
       $cn=$this->getMyConexionADO();
        
       $ssql = "SELECT empleado";
       $ssql .= " FROM  vdatos ";
       $ssql .= " WHERE Empleado_Codigo = ? ";
		
       $params=array( $empleado_codigo);
       

	     $rs = $cn->Execute($ssql, $params);
        
	  
       if($rs->RecordCount()>0) {
            	
		       $nombre_empleado = $rs->fields[0];
		   }

       return $nombre_empleado;

}


}
?>
