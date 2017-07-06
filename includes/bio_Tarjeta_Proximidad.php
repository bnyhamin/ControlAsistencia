<?php

class bio_TarjetaProximidad extends mantenimiento{

var $tarjeta_proximidad_id ="";
var $tarjeta_proximidad_codigo="";
var $tarjeta_proximidad_dni="";
var $tarjeta_proximidad_nombre="";
var $tarjeta_proximidad_activo="";
var $tarjeta_proximidad_empleado_codigo="";
var $tarjeta_proximidad_tipo_asignacion="";

function Addnew()
{
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    //insertar nuevo registro
	
	$rpta_tmp = $this->verificaUnicidadTarjetaCodigoDNI($this->tarjeta_proximidad_codigo, $this->tarjeta_proximidad_dni);
	
	if($rpta_tmp !== FALSE) {
	
		return $rpta_tmp;
	
	}

    $ssql = "INSERT INTO bio_tarjeta_proximidad";
    $ssql .= " (codigo, tarjeta_dni, tarjeta_nombre, tarjeta_activo, usuario_registro, empleado_codigo, tipo_asignacion) ";
    $ssql .= " VALUES (?,?,?,?,?,?,?)";
        
    $params=array($this->tarjeta_proximidad_codigo, $this->tarjeta_proximidad_dni, $this->tarjeta_proximidad_nombre,
    			        $this->tarjeta_proximidad_activo, $_SESSION["empleado_codigo"], $this->tarjeta_proximidad_empleado_codigo, $this->tarjeta_proximidad_tipo_asignacion);
    
    $rs=$cn->Execute($ssql,$params);
	
    if(!$rs) $rpta="Error";

    return $rpta;
    
}

function Update()
{
        $rpta="OK";
	    $cn=$this->getMyConexionADO();
        
        $ssql = "UPDATE bio_tarjeta_proximidad ";
        $ssql .= " SET fecha_modificacion  = getdate() , ";
        $ssql .= "     codigo = ?,";
        
		$tipo_asig  = $this->obten_tipo_asignacion($this->tarjeta_proximidad_id);
		
		if( $tipo_asig == "EXTERNO") {
			$ssql .= "     tarjeta_dni = ?, ";
			$ssql .= "     tarjeta_nombre = ?, ";
		}
		
		$ssql .= "     tarjeta_activo = ?, ";
    	$ssql .= "     usuario_modificacion = ?, ";
		$ssql .= "     empleado_codigo = ? ";
		//$ssql .= "     tipo_asignacion = ? ";
        $ssql .= " Where tarjeta_id = ? ";
		
	
		if($tipo_asig == "EXTERNO") {
		
			$params=array($this->tarjeta_proximidad_codigo, $this->tarjeta_proximidad_dni, $this->tarjeta_proximidad_nombre, $this->tarjeta_proximidad_activo, $_SESSION["empleado_codigo"] , $this->tarjeta_proximidad_empleado_codigo,  $this->tarjeta_proximidad_id);
        
		}else {
		
			$params=array($this->tarjeta_proximidad_codigo, $this->tarjeta_proximidad_activo, $_SESSION["empleado_codigo"] , $this->tarjeta_proximidad_empleado_codigo,  $this->tarjeta_proximidad_id);
        
		}
			
        $rs=$cn->Execute($ssql,$params);
	
	if(!$rs) $rpta="Error";
        
	return $rpta;
}

function Query()
{

        $rpta="OK";
	      $cn=$this->getMyConexionADO();
        $ssql = "SELECT codigo, tarjeta_dni, tarjeta_nombre , tarjeta_activo, empleado_codigo, tipo_asignacion  ";
        $ssql .= " FROM bio_tarjeta_proximidad ";
        $ssql .= " WHERE tarjeta_id = ? ";
        $params=array($this->tarjeta_proximidad_id);
		
        $rs = $cn->Execute($ssql,$params);
        if ($rs->RecordCount()>0){
            $this->tarjeta_proximidad_codigo=$rs->fields[0];
            $this->tarjeta_proximidad_dni=$rs->fields[1];
			$this->tarjeta_proximidad_nombre=$rs->fields[2];
            $this->tarjeta_proximidad_activo=$rs->fields[3];
			$this->tarjeta_proximidad_empleado_codigo=$rs->fields[4];
			$this->tarjeta_proximidad_tipo_asignacion=$rs->fields[5];
			
        }else{
            $rpta='No Existe Registro de TarjetaProximidad: '.$this->tarjeta_proximidad_id;
        }
	
	return $rpta;
}
  
function listarTarjetasSeleccionados($ids)
{
          $sRpta = "OK";
          $sSql ="";
          $cn = $this->getMyConexionADO();
  
          $sSql=" SELECT codigo, tarjeta_dni, tarjeta_nombre   
                  FROM bio_tarjeta_proximidad
                  WHERE tarjeta_id in ($ids)";												   
  					
          $rs = $cn->Execute($sSql );
  		  
          if ($rs->RecordCount()>0){
              $n = 0;
              while(!$rs->EOF){
                  $n++;
                  echo "<tr><td class='DataTD'>".$n."</td><td class='DataTD'>".$rs->fields[0]."</td><td class='DataTD'>".$rs->fields[1]."</td><td class='DataTD'>".$rs->fields[2]."</td></tr>";
                 
                  $rs->MoveNext();
              }
          }else{
          	$sRpta="Error al consultar Accesos.";
          }
}   
 

function asignarTarjetasPlataformas($arr_tarjetas = array(), $arr_plataformas = array(), $tipo_acceso, $fecha_inicio, $fecha_fin, $observacion, $permiso_activo)
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
    
    
    for ($i=0; $i<count($arr_tarjetas); $i++){
    
            $tarjeta_id  = $arr_tarjetas[$i];
			$empleado_codigo = $this->obtenEmpleadoID($tarjeta_id);
			$empleado_codigo = ($empleado_codigo == 0) ? null:$empleado_codigo;
			
            for ($j=0; $j<count($arr_plataformas); $j++){
             
                  $plataforma_id = $arr_plataformas[$j];
                
                  $sSql0=" SELECT permiso_id   
                          FROM bio_biometrico_permisos
                          WHERE  tarjeta_id = ? and plataforma_id = ? and fecha_inicio = ? and fecha_fin =? and permiso_activo=1";												   
  					
                  $params0=array($tarjeta_id, $plataforma_id, $fecha_inicio, $fecha_fin) ;
                  
                  $rs0 = $cn->Execute($sSql0, $params0 );
          		  
                  if (!$rs0->RecordCount()){
                  
                      $ssql = "INSERT INTO bio_biometrico_permisos";
                      $ssql .= " (tarjeta_id, plataforma_id, empleado_codigo ,fecha_inicio, fecha_fin, observacion, permiso_activo, usuario_registro, fecha_registro)";
                      $ssql .= " VALUES (?,?,?,?,?,?,?,?, getdate() )";
                     
                      $params=array($tarjeta_id, $plataforma_id, $empleado_codigo, $fecha_inicio, $fecha_fin, $observacion , $permiso_activo, $_SESSION["empleado_codigo"] );
                      $rs=$cn->Execute($ssql,$params);
                      
                      //echo $ssql."<br>";

                  }

            }
    }

}  

function obtenEmpleadoID($tarjeta_id)
{
	   $empleado_codigo = 0;
       
       $cn=$this->getMyConexionADO();
        
       $ssql = "SELECT empleado_codigo";
       $ssql .= " FROM  bio_tarjeta_proximidad ";
       $ssql .= " WHERE tarjeta_id = ? ";
		
       $params=array( $tarjeta_id);

	   $rs = $cn->Execute($ssql, $params);
        	  
       if($rs->RecordCount()) {
            	
		    $empleado_codigo = $rs->fields[0];
	   }
		   
       return $empleado_codigo;
}
 
function listarPlataformasAsignadasTarjetas($tarjeta_id)
{
      $sRpta = "OK";
      $sSql  ="";
      $cn = $this->getMyConexionADO();
  
      $sSql=" SELECT Plataforma_Descrip, convert(varchar(10), fecha_inicio,103), convert(varchar(10), fecha_fin,103), observacion, (CASE WHEN permiso_activo = 1 then 'Si' else 'No' end) as activo, usuario_registro,  convert(varchar, fecha_registro,20), permiso_id, usuario_modificacion, convert(varchar, fecha_modificacion,20)      
              FROM bio_biometrico_permisos bp join [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas vp on  bp.plataforma_id = vp.plataforma_id
              WHERE tarjeta_id = ?";												   
  					
      $params=array($tarjeta_id) ;

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
                  
				  $hoy=time();
				  
				  $arr_tmp_fecha_fin = explode("/", $rs->fields[2]);
				  $fecha_fin_tmp =$arr_tmp_fecha_fin[2]."-".$arr_tmp_fecha_fin[1]."-".$arr_tmp_fecha_fin[0];
				  
				  $fecha_fin = strtotime($fecha_fin_tmp); 
                   
				  $date2 = time();
				  $subTime = $fecha_fin - $hoy;
				  $y = ($subTime/(60*60*24*365));
				   
				  $fecha_fin = ($y>3) ? "-":$rs->fields[2];
				   
				   
                  echo "<tr><td class='DataTD'>".$n."</td><td class='DataTD' nowrap>".$rs->fields[0]."</td><td class='DataTD'>".$rs->fields[1]."</td><td class='DataTD' align='center'>".$fecha_fin."</td><td class='DataTD' ".$align_observ.">".$rs->fields[3]."</td><td class='DataTD' align='center'>".$activo."</td><td class='DataTD'>".$this->obtenNombreEmpleadoPlataforma($rs->fields[5])."</td><td class='DataTD'>".$rs->fields[6]."</td><td class='DataTD' ".$align_usu_mod.">".$rs->fields[8]."</td><td class='DataTD' ".$align_fech_mod.">".$rs->fields[9]."</td><td $align_observ_checked>".$checked."
                        </td></tr>";
                  $rs->MoveNext(); 
              }
      }else{
          	
            $sRpta="Error al consultar asignaci&oacute;n.";
      }
} 
 
function obtenNombreTarjetaPlataforma($tarjeta_id)
{
       $nombre_tarjeta = "";
       
       $cn=$this->getMyConexionADO();
        
       $ssql = "SELECT tarjeta_nombre";
       $ssql .= " FROM  bio_tarjeta_proximidad ";
       $ssql .= " WHERE tarjeta_id = ? ";
		
       $params=array( $tarjeta_id);
       

	     $rs = $cn->Execute($ssql, $params);
        
	  
       if($rs->RecordCount()>0) {
            	
		       $nombre_tarjeta = $rs->fields[0];
		   }

       return $nombre_tarjeta;

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

function verificaUnicidadTarjetaCodigoDNI($tarjeta_codigo, $tarjeta_dni)
{
	   $cn=$this->getMyConexionADO();
	
	   $ssql = "SELECT codigo";
       $ssql .= " FROM  bio_tarjeta_proximidad ";
       $ssql .= " WHERE codigo = ? ";
		
       $params=array( $tarjeta_codigo);
	   $rs = $cn->Execute($ssql, $params);
        
       if($rs->RecordCount()) {
			return "Ya existe otro registro con el mismo codigo de tarjeta";		   
	   }else{
	   
		   $ssql = "SELECT tarjeta_dni";
		   $ssql .= " FROM  bio_tarjeta_proximidad ";
		   $ssql .= " WHERE codigo =? and tarjeta_dni = ? ";
			
		   $params=array($tarjeta_codigo , $tarjeta_dni);
		   $rs = $cn->Execute($ssql, $params);
			
		   if($rs->RecordCount()) {
				return "Ya existe otro registro con el mismo codigo y tarjeta DNI";	
	        }
	   }
	  
	   return FALSE;

}

function desactivarTarjetaPlataforma($permisos_ids)
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

function obten_tipo_asignacion($tarjeta_proximidad_id)
{
	   $tipo_asig = "";
       
       $cn=$this->getMyConexionADO();
        
       $ssql = "SELECT tipo_asignacion";
       $ssql .= " FROM  bio_tarjeta_proximidad ";
       $ssql .= " WHERE tarjeta_id = ? ";
		
       $params=array( $tarjeta_proximidad_id);
      
	   $rs = $cn->Execute($ssql, $params);
        
       if($rs->RecordCount()) {
            	
		       $tipo_asig = $rs->fields[0];
		}

       return $tipo_asig;


}

}
?>
