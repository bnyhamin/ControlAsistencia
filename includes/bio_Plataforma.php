<?php

class BIO_Plataforma extends mantenimiento {
var $acceso =0;
var $observacion = "";
var $empleado_codigo = 0;
var $cb_codigo = 0;
var $cb_correctas = 0;
var $cb_fallidas = 0;
 
function listarLectoresAsignados($plataforma_id)
{
	  $cn=$this->getMyConexionADO();
        
    $ssql = "SELECT plataforma_lector_id as codigo, codigo_equipo as codigoEquipo, l.lector_id as lector, lector_ip as ip, lector_puerto as puerto, (CASE WHEN lector_tipo_acceso = 'E' then 'Entrada' else 'Salida' end) as tipoAcceso, plataforma_lector_activo as activo, lector_nombre";
    $ssql .= " FROM  (bio_plataforma_lector pl inner join bio_lector l on pl.lector_id=l.lector_id) inner join [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas as p  on pl.plataforma_id = p.plataforma_id  ";
    $ssql .= " WHERE pl.plataforma_id = ? ";

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
       
	     $ssql .= " WHERE  lector_id not in (select lector_id from bio_plataforma_lector pl where plataforma_id=? and pl.lector_id = l.lector_id   $ssql2) and lector_activo = 1 ";
	   

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
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

          $sRpta = "OK";
          $sSql ="";
          $cn = $this->getMyConexionADO();
		  
		  //$cn->debug=true;
     
	
  
          $sSql=" SELECT Plataforma_Id, plataforma_descrip 
                  FROM [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas 
                  WHERE Plataforma_Estado=1 and local_id=3 or (Local_Id = 17 and (Plataforma_Id = 111 or Plataforma_Id = 110)) order by plataforma_descrip asc";												   
  					
          $cn->Execute("Set ANSI_NULLS ON ");  
          $cn->Execute("Set ANSI_WARNINGS ON ");
            
          $rs = $cn->Execute($sSql);
  		  if ($rs->RecordCount()>0){
              while(!$rs->EOF){
                  
                  echo "<tr><td class='DataTD'>";
                  
                  if($this->acceso == 1){
                    echo     "<input type='checkbox' name='chkPlataformas" . $rs->fields[0] . "' id='chk" . $rs->fields[0] . "' value='".$rs->fields[0]."'></td><td class='DataTD'>".$rs->fields[1]."</td></tr>";
                  }
                  else{
                    echo     "<input type='radio' name='chkPlataformas' id='chk" . $rs->fields[0] . "' value='".$rs->fields[0]."'></td><td class='DataTD'>".$rs->fields[1]."</td></tr>";  
                  }  
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
                  WHERE Empleado_Codigo in ($ids) ";
        $empleado_codigo =  $_SESSION["empleado_codigo"]; 
        if($this->acceso == 0) $sSql .=" and empleado_codigo not in (select empleado_codigo from bio_biometrico_permisos where permiso_activo = 1 and usuario_registro = $empleado_codigo and empleado_codigo in ($ids) ) ";
        
        $sSql .="  order by empleado asc";												   
        	
        $rs = $cn->Execute($sSql );
        $Allempleadocodigo = "";
        if ($rs->RecordCount()>0){
            $n = 0;
            while(!$rs->EOF){
                $n++;
                echo "<tr><td class='DataTD'>".$n."</td><td class='DataTD'>".$rs->fields[1]."</td><td class='DataTD'>".$rs->fields[2]."</td><td class='DataTD'>".$rs->fields[3]."</td></tr>";
                if($n==1) $Allempleadocodigo =$rs->fields[0]; 
                else $Allempleadocodigo .= ",".$rs->fields[0];
                //$Allempleadocodigo =explode(",",$rs->fields[0]);
                    
            $rs->MoveNext();
        }
        //print_r(explode(",",$Allempleadocodigo));
        echo "<tr><td ><input type='hidden' name='allempleadocodigo' value=$Allempleadocodigo /></td></tr>";
        }else{
            echo  " 
                <script language='JavaScript'> 
                alert('Colaborador(es) seleccionado ya tiene acceso a plataforma(s)');
                self.close(); 
                </script>";
            $sRpta="Error al consultar Accesos.";
        }
}  
 
function asignarEmpleadosPlataformas($arr_empleados = array(), $arr_plataformas = array(), $tipo_acceso, $fecha_inicio, $fecha_fin, $observacion, $permiso_activo)
{
      if(!isset($permiso_activo)){
      
         $permiso_activo = 0 ;
      }

      if($tipo_acceso == 'p'){
      
           $fecha_inicio = date("Y-m-d") ;
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
                          WHERE  plataforma_id = ? and empleado_codigo = ? and fecha_inicio = ? and fecha_fin =? and permiso_activo=1";												   
  					
                  $params0=array($plataforma_id, $empleado_codigo, $fecha_inicio, $fecha_fin ) ;
                  
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

function listarPlataformasAsignadasEmpleados($empleado_codigo, $solo_vista=false)
{
      $sRpta = "OK";
      $sSql  ="";
      $cn = $this->getMyConexionADO();
  
      $sSql=" SELECT Plataforma_Descrip, convert(varchar(10), fecha_inicio,103), convert(varchar(10), fecha_fin,103), observacion, (CASE WHEN permiso_activo = 1 then 'Si' else 'No' end) as activo, usuario_registro,  convert(varchar, fecha_registro,20), permiso_id, usuario_modificacion, convert(varchar, fecha_modificacion,20)      
              FROM bio_biometrico_permisos bp join [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas vp on  bp.plataforma_id = vp.plataforma_id
              WHERE empleado_codigo = ? order by Plataforma_Descrip asc";												   
  					
      $params=array($empleado_codigo) ;
      
      $cn->Execute("Set ANSI_NULLS ON ");  
      $cn->Execute("Set ANSI_WARNINGS ON ");
      
      $rs = $cn->Execute($sSql, $params);
  		  
      if ($rs->RecordCount()>0){
              
            $n = 0;
             while(!$rs->EOF){
                  $n++;
                       
                   $arr_freg     = explode(" ", $rs->fields[6]);   
                   $arr_freg_tmp = explode('-', $arr_freg[0]);
                   $arr_fhreg_tmp = explode(':', $arr_freg[1]);
                   
                   $rs->fields[6] = $arr_freg_tmp[2]."/".$arr_freg_tmp[1]."/".$arr_freg_tmp[0]." ".$arr_fhreg_tmp[0].":".$arr_fhreg_tmp[1]; 
                   $rs->fields[6] = ( $rs->fields[6] =='// :') ? "":$rs->fields[6];
                    
                   $arr_fmod     = explode(" ", $rs->fields[9]);   
                   $arr_fmod_tmp = explode('-', $arr_fmod[0]);
                   $arr_fhmod_tmp = explode(':', $arr_fmod[1]);
                   
                   $align_fech_mod = ($rs->fields[9]) ? "":"align='center'"; 
                   $rs->fields[9] = $arr_fmod_tmp[2]."/".$arr_fmod_tmp[1]."/".$arr_fmod_tmp[0]." ".$arr_fhmod_tmp[0].":".$arr_fhmod_tmp[1]; 
                   $rs->fields[9] = ( $rs->fields[9] =='// :') ? "":$rs->fields[9];     
                  
                  
                  $activo = ($rs->fields[4] == 'No') ? "<b style='color:red'>".$rs->fields[4]."</b>":"<b style='color:green'>".$rs->fields[4]."</b>";
                  $checked = ($rs->fields[4] =='No') ?  "-":"<input id='chkpermiso_".$rs->fields[7]."' type='checkbox' name='chkpermiso_".$rs->fields[7]."' value='".$rs->fields[7]."' onclick='desactivaPermisoEmpleadoPlataforma(".$rs->fields[7].");'  />" ;
                  $align_observ_checked  = ($rs->fields[4] =='No') ? "align='center'":"align='right'";
                  
                  $align_observ  = (strlen(trim($rs->fields[3]))) ? "":"align='center'";
                  $rs->fields[3] = (strlen($rs->fields[3])) ? $rs->fields[3]:"-";
                  $align_usu_mod  = ($rs->fields[8]) ? "":"align='center'"; 
                  $rs->fields[8] = ($rs->fields[8]) ? $this->obtenNombreEmpleadoPlataforma($rs->fields[8]):"-";
                  $rs->fields[9] = ($rs->fields[9]) ? $rs->fields[9]:"-";
                  
                  $checkboxes_adm = '';
                  if(!$solo_vista){

                      $checkboxes_adm = '<td '.$align_observ_checked.'>'.$checked.'</td>';

                  } 
					
				  $hoy=time();
				  
				  $arr_tmp_fecha_fin = explode("/", $rs->fields[2]);
				  $fecha_fin_tmp =$arr_tmp_fecha_fin[2]."-".$arr_tmp_fecha_fin[1]."-".$arr_tmp_fecha_fin[0];
				  
				  $fecha_fin = strtotime($fecha_fin_tmp); 
                   
				  $date2 = time();
				  $subTime = $fecha_fin - $hoy;
				  $y = ($subTime/(60*60*24*365));
				   
				  $fecha_fin = ($y>3) ? "-":$rs->fields[2];	
					
					
                  echo "<tr><td class='DataTD'>".$n."</td><td class='DataTD' nowrap>".$rs->fields[0]."</td><td class='DataTD'>".$rs->fields[1]."</td><td class='DataTD' align='center'>".$fecha_fin."</td><td class='DataTD' ".$align_observ.">".$rs->fields[3]."</td><td class='DataTD' align='center'>".$activo."</td><td class='DataTD'>".$this->obtenNombreEmpleadoPlataforma($rs->fields[5])."</td><td class='DataTD'>".$rs->fields[6]."</td><td class='DataTD' ".$align_usu_mod.">".$rs->fields[8]."</td><td class='DataTD' ".$align_fech_mod.">".$rs->fields[9]."</td>".$checkboxes_adm."</tr>";
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

function desactivarEmpleadoPlataforma($permisos_ids)
{
          $arr_permisos_ids =explode(",",$permisos_ids);

          $cn=$this->getMyConexionADO();

            if(count($arr_permisos_ids)){
            
              for ($j=0; $j<count($arr_permisos_ids); $j++){
             
                      $permiso_id = $arr_permisos_ids[$j];
                
                      $ssql = "UPDATE bio_biometrico_permisos set permiso_activo=0 ,fecha_modificacion=getdate(), usuario_modificacion=?";
                      $ssql .= " WHERE permiso_id=? ";
                  
                      $params=array($_SESSION["empleado_codigo"], $permiso_id );
                      	
                      $rs=$cn->Execute($ssql,$params);
                      
              }

            }
}
    function cargarEmpleadoPlataformas($arr_empleados = array(), $arr_plataformas = array(), $tipo_acceso, $fecha_inicio, $fecha_fin, 
                    $observacion, $permiso_activo,$nombrearchivo){
        $cn=$this->getMyConexionADO();
        //$cn->debug = true;
        
        if(!isset($permiso_activo))
         $permiso_activo = 0 ;
        
        if($tipo_acceso == 'p'){
           $fecha_inicio = date("Y-m-d") ;
           $fecha_fin    = date("Y-m-d H:i:s",mktime(0, 0, 0, date("m"),   date("d"),   date("Y")+20) ) ;
        }else{
            $arr_finicio = explode("/",$fecha_inicio);
            $fecha_inicio = $arr_finicio[2]."-".$arr_finicio[1]."-".$arr_finicio[0];
            $arr_ffin = explode("/",$fecha_fin);
            $fecha_fin = $arr_ffin[2]."-".$arr_ffin[1]."-".$arr_ffin[0];
        }
        
        $this->Inserta_Cabecera_Log_Carga($nombrearchivo);
        $correctos = 0;
        $fallidos = 0;
        for ($i=0; $i<count($arr_empleados); $i++){
            //$empleado_codigo = $arr_empleados[$i];
            $documento = $arr_empleados[$i];
            $this->Valida_Documento_Cargado($documento);
            if($this->observacion == "OK"){
                $correctos = $correctos + 1;
                $empleado_codigo = $this->empleado_codigo;
                for ($j=0; $j<count($arr_plataformas); $j++){
                    $plataforma_id = $arr_plataformas[$j];
                    $sSql0=" SELECT permiso_id   
                          FROM bio_biometrico_permisos
                          WHERE  plataforma_id = ? and empleado_codigo = ? and permiso_activo=1";												   
            			
                    $params0=array($plataforma_id, $empleado_codigo, $fecha_inicio, $fecha_fin ) ;
                    $rs0 = $cn->Execute($sSql0, $params0 );
                    if ($rs0->RecordCount() == 0){ //si aun no tiene permisos
                      $ssql = "INSERT INTO bio_biometrico_permisos(
                                        empleado_codigo,
                                        plataforma_id,
                                        fecha_inicio,
                                        fecha_fin,
                                        observacion,
                                        permiso_activo,
                                        usuario_registro,
                                        fecha_registro)
                             VALUES (?,?,?,?,?,?,?, getdate() )";
                      $params=array($empleado_codigo, $plataforma_id, $fecha_inicio, $fecha_fin, $observacion , $permiso_activo, $_SESSION["empleado_codigo"] );
                      $rs=$cn->Execute($ssql,$params);
                    }
                }
            }else{
               $fallidos = $fallidos + 1; 
               $this->Inserta_Detalle_Log_Carga($documento); 
            }
        }
        //actualizamos la situcion de carga final del archivo
        $this->cb_correctas = $correctos;
        $this->cb_fallidas  = $fallidos;
        $this->Actualiza_Situacion_Final_Carga();
        
    }
    
    function DesactivarEmpleadoPlataformas($arr_empleados = array(), $arr_plataformas = array(), $tipo_acceso, $observacion, $permiso_activo,$nombrearchivo){
        $cn=$this->getMyConexionADO();
        //$cn->debug = true;
        
        $this->Inserta_Cabecera_Log_Carga($nombrearchivo);
        $correctos = 0;
        $fallidos = 0;
        for ($i=0; $i<count($arr_empleados); $i++){
            //$empleado_codigo = $arr_empleados[$i];
            $documento = $arr_empleados[$i];
            $this->Valida_Documento_Cargado($documento);
            if($this->observacion == "OK"){
                $correctos = $correctos + 1;
                $empleado_codigo = $this->empleado_codigo;
                for ($j=0; $j<count($arr_plataformas); $j++){
                    $plataforma_id = $arr_plataformas[$j];
                    $sSql0=" SELECT permiso_id   
                          FROM bio_biometrico_permisos
                          WHERE  plataforma_id = ? and empleado_codigo = ? and permiso_activo=1";												   
            			
                    $params0=array($plataforma_id, $empleado_codigo) ;
                    $rs0 = $cn->Execute($sSql0, $params0 );
                    if ($rs0->RecordCount() == 1){ //si aun no tiene permisos
                      $permiso_id = $rs0->fields[0];
                      $params=array("Desactivacion masiva" ,$_SESSION["empleado_codigo"], $permiso_id, $empleado_codigo);
                      $ssql = "UPDATE bio_biometrico_permisos 
                               set permiso_activo = 0,
                                    observacion = ?,
                                    usuario_modificacion = ?,
                                    fecha_modificacion = getdate()
                               where permiso_id = ? and empleado_codigo = ?";
                      $rs=$cn->Execute($ssql,$params);
                    }
                }
            }else{
               $fallidos = $fallidos + 1; 
               $this->Inserta_Detalle_Log_Carga($documento); 
            }
        }
        //actualizamos la situcion de carga final del archivo
        $this->cb_correctas = $correctos;
        $this->cb_fallidas  = $fallidos;
        $this->Actualiza_Situacion_Final_Carga();
        
    }
    
    
    
    
    function Valida_Documento_Cargado($documento){
        if(strlen($documento) == 8){
            if(is_numeric($documento)){
                $cn = $this->getMyConexionADO();
                //$cn->debug = true;
                $params = array($documento);
                $sql = "select empleado_codigo from empleados where empleado_dni = ?";
                $rs = $cn->Execute($sql, $params);
                if($rs->RecordCount() > 0){
                    $this->empleado_codigo = $rs->fields[0];
                    $this->observacion = "OK";
                }else{
                    $this->observacion = "Documento de Identidad no encontrado";
                }
            }else{
                $this->observacion = "Documento tiene caracteres no validos";
            }
        }else{
            $this->observacion = "Longitud(".strlen($documento)."): Documento no tiene la longitud adecuada.";
        }
    }
    
    function Inserta_Cabecera_Log_Carga($nombre_archivo){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $sql = "select isnull(max(cb_codigo),0)+1 as codigo from CA_Cargas_Biometrico";
        $rs = $cn->Execute($sql);
        $this->cb_codigo = $rs->fields[0];
        $params = array($this->cb_codigo, $nombre_archivo,$_SESSION["empleado_codigo"], $_SERVER["REMOTE_ADDR"]);
        $sql = "insert into CA_Cargas_Biometrico(
                    CB_Codigo,
                    CB_Nombre_Archivo,
                    CB_Usuario_Carga,	
                    CB_IP_Carga)
                values(?,?,?,?)";
        $rs = $cn->Execute($sql, $params);
                        
    }
    
    function Inserta_Detalle_Log_Carga($documento){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $params = array($this->cb_codigo, $documento,$this->observacion);
        $sql = "insert into CA_Detalle_Carga_Biometrico(
                    CB_Codigo,
                    DB_Documento,	
                    DB_Observacion)
                values(?,?,?)";
        $rs = $cn->Execute($sql, $params);
    }
    
    function Actualiza_Situacion_Final_Carga(){
        $cn = $this->getMyConexionADO();
        $params = array($this->cb_correctas, $this->cb_fallidas,$this->cb_codigo);
        $sql = "update CA_Cargas_Biometrico 
                set CB_Correctas = ?,
                    CB_Fallidas = ?
                where CB_Codigo = ?";
        $cn->Execute($sql, $params);
    }
    
    function Listado_Log_Carga($CB_Codigo){
        $cn = $this->getMyConexionADO();
        $params = array($CB_Codigo);
        $sql = "select dcb.DB_Documento , dcb.DB_Observacion 
                from CA_Cargas_Biometrico cb 
                	inner join empleados e on cb.CB_Usuario_Carga = e.Empleado_Codigo 
                	inner join CA_Detalle_Carga_Biometrico dcb on cb.CB_Codigo = dcb.CB_Codigo
                where cb.CB_Codigo = ?";
        $rs = $cn->Execute($sql, $params);
        return $rs;
    }
}
?>
