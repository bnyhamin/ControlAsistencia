<?php 
require_once(PathIncludes() . "mantenimiento.php");

class ca_reportes extends mantenimiento{
var $Rep_Codigo="";
var $Rep_Binario="";
var $Rep_Fecha="";
var $Rep_Nombre="";
var $Usuario_Id="";

function Query(){
$rpta="OK";
//$rpta=$this->conectarme_ado();
$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "SELECT * FROM CA_Reportes ";
		$ssql .= " WHERE Rep_Codigo = " . $this->Rep_Codigo;
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$this->Usuario_Id = $rs->fields[1];
			$this->Rep_Fecha= $rs->fields[2];
			$this->Rep_Nombre= $rs->fields[3];		
	  }else{
		   $rpta='No Existe Registro de Reporte';
	  }
	 } 
	return $rpta;
 }
 
 
 
 
 
 function resumen_incidencias_validables($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        $ssql="
        select incidencia_codigo,usuario_registro,incidencia_descripcion,empleado,
		sum(case when ee_codigo=3 then cantidad end) as  aprobado,
		sum(case when ee_codigo=4 or ee_codigo=6 then cantidad end) as rechazado
		from (
			select ca_incidencias.incidencia_codigo 
			 ,ca_evento_log.usuario_registro 
			 ,ca_incidencias.incidencia_descripcion, vdatos.empleado ,
			 --,case when ca_eventos.ee_codigo = 3 then count(ca_eventos.ee_codigo) end as aprobado, 
			 --case when ca_eventos.ee_codigo = 4 or ca_eventos.ee_codigo = 6 then count(ca_eventos.ee_codigo) end as rechazado 
			 ca_eventos.ee_codigo,
			 count(ca_eventos.ee_codigo) as cantidad
			 from ca_eventos 
			 inner join ca_evento_log on ca_eventos.Empleado_Codigo = ca_evento_log.Empleado_Codigo 
				and ca_eventos.Asistencia_codigo = ca_evento_log.Asistencia_codigo 
				and ca_eventos.Evento_Codigo = ca_evento_log.Evento_Codigo 
				and (ca_evento_log.realizado = 'V' or ca_evento_log.realizado = 'M' ) and ca_evento_log.em_codigo !=2
				and ca_eventos.evento_activo = 0 
			 inner join ca_incidencias on ca_eventos.incidencia_codigo = ca_incidencias.incidencia_codigo 
				and (ca_incidencias.validable = 1 or ca_incidencias.validable_mando = 1 ) 
			 inner join vdatos on ca_evento_log.usuario_registro= vdatos.empleado_codigo 
			 where 
				convert(varchar,fecha_registro,103) >= '".$fechai."' 
				and convert(varchar,fecha_registro,103) <= '".$fechaf."' 
			 group by ca_incidencias.incidencia_codigo 
			 ,ca_incidencias.incidencia_descripcion 
			 ,ca_evento_log.usuario_registro 
			 ,vdatos.empleado 
			 ,ca_eventos.ee_codigo 
			 having ca_eventos.ee_codigo in (3,4,6)
         ) as tx
         group by incidencia_codigo,usuario_registro,incidencia_descripcion,empleado
        ";
        

        
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
       
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            
            
            $text=" SELECT CASE WHEN VALIDABLE=1 THEN 'Por Persona' else 'Por Mando' end as tipo ";
            $text.=" FROM CA_INCIDENCIAS WHERE INCIDENCIA_CODIGO = ".$rs->fields[0]." ";
            $r = $cn->Execute($text);
            
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $r->fields[0]. "\n";
            $reg .="</td>\n";
            
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
          

        }

        $rs=null;
    }
    
    return $lista;
    
}

 


function resumen_detalladoxincidencia($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="exec sp_reporte_incidencias_validables '".$fechai."','".$fechaf."',1";
        
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[10]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[11]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[13]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[14]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[15]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}

 
 
 
function resumen_consolidadoxincidencia($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="exec sp_reporte_incidencias_validables '".$fechai."','".$fechaf."',2";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}

function resumen_consolidadoxvalidador($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="exec sp_reporte_incidencias_validables '".$fechai."','".$fechaf."',3";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[10]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}



function Delete(){
$rpta="OK";
//$rpta=$this->conectarme_ado();
$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "DELETE FROM CA_Reportes ";
		$ssql .= " WHERE Rep_Codigo =".$this->Rep_Codigo." ";

		$r= $cn->Execute($ssql);
	   
		if (!$r){
           $rpta="Error al eliminar";
	    }else{
		   $rpta='OK';
	  }
	 } 
	return $rpta;
 }
}

