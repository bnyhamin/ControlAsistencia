<?php
class ca_asignacion_empleados extends mantenimiento{
var $asignacion_codigo=0;
var $responsable_codigo=0;
var $responsable="";
var $empleado_codigo=0;
var $empleado_codigo_asigna=0;
var $asignacion_activo ="";
var $maximo_operadores=0;
var $total_operadores_grupo=0;

var $empleado_jefe=0;
var $empleado_email_responsable="";
var $empleado_apellido_paterno_responsable="";
var $empleado_apellido_materno_responsable="";
var $empleado_nombres_responsable="";
var $empleado_ip="";

var $empleado_email="";
var $empleado_apellido_paterno="";
var $empleado_apellido_materno="";
var $empleado_nombres="";

var $incidencia_codigo=0;
var $incidencia_descripcion="";
var $validable=0;
var $area_codigo_vbo=0;
var $horas_vbo=0;
var $flag_validable=0;
var $evento_codigo=0;
var $estado_evento=0;
var $texto_descripcion=0;
var $aprobado="";
var $fecha_inicio_evento="";
var $mando=0;

var $hora=NULL;
var $minuto=NULL;
var $incidencia_codigo_sustituye=NULL;
var $realizado=NULL;

var $incidencia_descripcion_sustituye="";
var $area=0;
var $tipo="";
var $tipo_incidencia="";
var $asistencia_codigo=0;
var $incidencia_proceso="";

function Addnew(){
    $rpta="OK";
    $ssql="";
	$cn = $this->getMyConexionADO();
	if($rpta=="OK"){
    	$ssql ="select isnull(max(Asignacion_codigo)+1,1) as id from ca_asignacion_empleados(nolock) ";
    	$ssql .="where responsable_codigo=". $this->responsable_codigo;
        $rs = $cn->Execute($ssql);
    	$this->asignacion_codigo=$rs->fields[0];
        $params = array(
                        $this->asignacion_codigo,
                        $this->responsable_codigo,
                        $this->empleado_codigo,
                        $this->empleado_codigo_asigna
                    );
    	$ssql =" INSERT INTO ca_asignacion_empleados ";
    	$ssql .=" (Asignacion_codigo, Responsable_codigo, Empleado_Codigo,Responsable_Origen,fecha_reg, Asignacion_activo) ";
    	$ssql .=" values (?,?,?,?,getdate(), 1 )";
	    $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
    		       $rpta = "Error al Insertar empleados.";
    		       return $rpta;
    	}else{
    		     $rpta= "OK";
    	}
    	//$rs->close();
    	$rs=null;
    	//$cmd=null;
    }
	return $rpta;
}

function Desactivar_asignacion(){
     $rpta="OK";
     $cn = $this->getMyConexionADO();
     if($cn){
        $params = array($this->empleado_codigo);
    	$ssql =" UPDATE ca_asignacion_empleados set Asignacion_activo=0, fecha_modifica=getdate() 
                 WHERE Empleado_Codigo=? and Asignacion_activo=1 ";
    	$rs=$cn->Execute($ssql, $params);
    	if(!$rs){
    	  $rpta = "Error al Desactivar Asignacion.";
    	  return $rpta;
    	}else{
    	   $rpta= "OK";
    	}
    }
	return $rpta;
}

function Quitar_empleado_grupo(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
    $ssql =" update ca_asignacion_empleados set asignacion_activo=0, fecha_modifica=getdate() where Responsable_Codigo=? and Asignacion_Codigo=? ";
    $cmd->ActiveConnection = $this->cnnado;
    $cmd->CommandText = $ssql;
    $cmd->Parameters[0]->value = $this->responsable_codigo;
    $cmd->Parameters[1]->value = $this->asignacion_codigo;
    $r=$cmd->Execute();
    
    if(!$r){
        $rpta = "Error al Quitar Empleado del grupo.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    $cmd=null;
}
    return $rpta;*/
    //migracion a adodb php mcortezc
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    if($cn){
        $ssql =" update ca_asignacion_empleados set asignacion_activo=0, fecha_modifica=getdate() where Responsable_Codigo=? and Asignacion_Codigo=? ";
        $params = array($this->responsable_codigo,$this->asignacion_codigo);
        $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
    	  $rpta = "Error al Quitar Empleado del grupo.";
    	  return $rpta;
    	}else{
    	   $rpta= "OK";
    	}
        $rs=null;
        
    }
    
    return $rpta;
    
}

function Desactivar_empleado_grupo(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
 $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	    $ssql =" update ca_asignacion_empleados set asignacion_activo=0, " .
	    		" fecha_modifica=getdate(), " .
	    		" usuario_modifica=" . $this->responsable_codigo .
	    		" where Empleado_Codigo=? and Asignacion_Activo=1 ";
	    $cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
    	$cmd->Parameters[0]->value = $this->empleado_codigo;
    	$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Quitar Empleado del grupo.";
		  return $rpta;
		}else{
		 $rpta= "OK";
		}
		$cmd=null;
}
return $rpta;*/

//migracion a adodb php mcortezc
$rpta="OK";
$cn = $this->getMyConexionADO();
//$cn->debug=true;
if($cn){
    $ssql =" update ca_asignacion_empleados set asignacion_activo=0, " .
        " fecha_modifica=getdate(), " .
        " usuario_modifica=" . $this->responsable_codigo .
        " where Empleado_Codigo=? and Asignacion_Activo=1 ";
        
    $params = array($this->empleado_codigo);
    $rs=$cn->Execute($ssql, $params);
    if(!$rs){
        $rpta = "Error al Quitar Empleado del grupo.";
	return $rpta;    
    }else{
        $rpta= "OK";
    }
}    
return $rpta;

}

function identificar_responsable_asignado(){
  /*$codigo=0;
 $rpta="OK";
 $cn = $this->getMyConexionADO();
 if($rpta=="OK"){
    $ssql=" select ca_asignacion_empleados.Responsable_Codigo,vca_empleado_area.Empleado, ";
	$ssql.=" vca_empleado_area.Area_Codigo, vca_empleado_area.Area_Descripcion ";
	$ssql.=" FROM  ca_asignacion_empleados(nolock) ";
	$ssql.=" inner join vca_empleado_area(nolock) on ca_asignacion_empleados.responsable_codigo=vca_empleado_area.empleado_codigo";
	$ssql.=" WHERE vca_empleado_area.Estado_Codigo =1 and ca_asignacion_empleados.Empleado_Codigo =" . $this->empleado_codigo;
    $ssql.=" and ca_asignacion_empleados.asignacion_activo=1 ";

	//echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
		 $this->responsable_codigo=$rs->fields[0]->value; //responsable_codigo
		 $this->responsable= $rs->fields[1]->value;//nombre_responsable
	  }else{
		$this->responsable_codigo=0;
		$this->responsable="";
	  }
	$codigo=$this->responsable_codigo;

	$rs->close();
	$rs=null;
 }
return $codigo;*/
    
    
    $codigo=0;
    $rpta="OK";
    $cn = $this->getMyConexionADO();
 
    $ssql=" select ca_asignacion_empleados.Responsable_Codigo,vca_empleado_area.Empleado, ";
    $ssql.=" vca_empleado_area.Area_Codigo, vca_empleado_area.Area_Descripcion ";
    $ssql.=" FROM  ca_asignacion_empleados(nolock) ";
    $ssql.=" inner join vca_empleado_area(nolock) on ca_asignacion_empleados.responsable_codigo=vca_empleado_area.empleado_codigo";
    $ssql.=" WHERE vca_empleado_area.Estado_Codigo =1 and ca_asignacion_empleados.Empleado_Codigo =" . $this->empleado_codigo;
    $ssql.=" and ca_asignacion_empleados.asignacion_activo=1 ";

    //echo $ssql;
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
        $this->responsable_codigo=$rs->fields[0]; //responsable_codigo
        $this->responsable= $rs->fields[1];//nombre_responsable
    }else{
        $this->responsable_codigo=0;
        $this->responsable="";
    }
    
    $codigo=$this->responsable_codigo;

    $rs->close();
    $rs=null;

    return $codigo;

}



function obtener_area_supervisor(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    $ssql=" select area_codigo_responsable from CA_Asistencia_Responsables ";
    $ssql.=" where Empleado_Codigo = ".$this->empleado_codigo." and Asistencia_codigo = ".$this->asistencia_codigo." ";
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
        $area_codigo=$rs->fields[0];
    }
    $rs=null;
    return $area_codigo;
}



function listado_eventos_supervisor(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    $formato="set dateformat dmy";
    $rsx = $cn->Execute($formato); 
    $ssql=" Select cast(ev.empleado_codigo as varchar(10))+'-'+cast(ev.asistencia_codigo as varchar(10))+'-'+cast(ev.evento_codigo as varchar(10))+'-'+cast(ev.incidencia_codigo as varchar(10)) as codigo, ";
		$ssql.=" ev.empleado_codigo, e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as nombre, ";
		$ssql.=" ci.incidencia_descripcion, ";
		$ssql.=" case when horas<10 then '0' else '' end + cast(horas as varchar(10))+':'+case when minutos<10 then '0' else '' end + cast(minutos as varchar(10)) as horainicio, ";
		$ssql.=" minutos as horafin, ";
		$ssql.=" ee.ee_descripcion,ee.ee_codigo , ";
		$ssql.=" convert(varchar(10),cas.asistencia_fecha,103) as freg , ev.num_ticket, ";
		//$ssql.=" case when ci.validable=1 then 'Por Persona' else 'Por Mando' end as tipo ";
                $ssql.=" case when ci.validable=1 then 'Por Persona' else case when ci.validable_mando=1 then 'Por Mando' else 'Por Gerente' end end as tipo ";
                $ssql.=" from CA_Eventos ev inner join empleados e on e.empleado_codigo = ev.empleado_codigo ";
                $ssql.=" inner join ca_asistencia_responsables ae on ae.Empleado_Codigo = ev.Empleado_Codigo ";
                        $ssql.=" and ae.Asistencia_codigo = ev.Asistencia_codigo ";
                $ssql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
                $ssql.=" inner join ca_evento_estado ee on ev.ee_codigo = ee.ee_codigo ";
                $ssql.=" inner join ca_asistencias cas on ev.empleado_codigo = cas.empleado_codigo and ev.asistencia_codigo = cas.asistencia_codigo ";
        $ssql.=" where ae.responsable_codigo =".$this->empleado_codigo." ";
        if($this->tipo_incidencia!="0"){
            if($this->tipo_incidencia=="P"){//P-M-G
                $ssql.=" and (ci.validable = 1) ";
            }else if($this->tipo_incidencia=="M"){
                $ssql.=" and (ci.validable_mando=1) ";
            }else if($this->tipo_incidencia=="G"){
                $ssql.=" and (ci.validable_gerente=1) ";
            }
        }else{
            $ssql.=" and (ci.validable = 1 or ci.validable_mando=1 or ci.validable_gerente=1) ";
        }
        if($this->estado_evento!="0"){
            $ssql.=" and ev.ee_codigo = ".$this->estado_evento." ";
        }
        $ssql.=" and cast(convert(varchar(10),ev.fecha_reg_inicio,103) as datetime) ";
            $ssql.=" between cast(convert(varchar(10),dateadd(day,-31,getdate()),103) as datetime) and cast(convert(varchar(10),getdate(),103) as datetime) ";
            $ssql.=" order by convert(varchar(10),cas.asistencia_fecha,103) desc,e.empleado_apellido_paterno asc ";
       $rs = $cn->Execute($ssql);
       
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            $marcado=($rs->fields[7]==2 || $rs->fields[7]== 3  ) ? "disabled" : "";
            $hijo["desactivado"]=$marcado;
            $hijo["codigo"]=$rs->fields[0];
            $hijo["nombre"]=$rs->fields[2];
            $hijo["evento"]=$rs->fields[3];
            $hijo["inicio"]=$rs->fields[4];
            $hijo["estado"]=$rs->fields[6];
            $hijo["tipo"]=$rs->fields[7];
            $hijo["freg"]=$rs->fields[8];
            $hijo["ticket"]=$rs->fields[9];
            $hijo["tipos"]=$rs->fields[10];
            array_push($padre, $hijo);
            $rs->MoveNext();
        }
        $rs=null;
        return $padre;
}


function listado_reporte(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
    $formato="set dateformat dmy";
    $rsx = $cn->Execute($formato); 
    /*$sql=" select sum(cast(ci.validable as int)) as persona ";
                            $sql.=" ,sum(cast(ci.validable_mando as int)) as mando ";
                    $sql.=" from CA_Asignacion_Empleados ae ";
                             $sql.=" inner join empleados e  on ae.empleado_codigo=e.empleado_codigo ";
                                     $sql.=" and ae.responsable_codigo = ".$this->empleado_codigo." and ae.asignacion_activo=1 and e.Estado_Codigo = 1 ";
                             $sql.=" inner join CA_Eventos ev on ae.empleado_codigo = ev.empleado_codigo ";
                             $sql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
                     $sql.=" where ";
                            $sql.=" cast(convert(varchar(10),ev.fecha_reg_inicio,103) as datetime) ";
                                    $sql.=" between cast(convert(varchar(10),dateadd(day,-31,getdate()),103) as datetime) and cast(convert(varchar(10),getdate(),103) as datetime) ";
                            $sql.=" and (ci.validable = 1 or ci.validable_mando = 1) ";*/
        
        $sql=" select sum(cast(ci.validable as int)) as persona ";
               $sql.=" ,sum(cast(ci.validable_mando as int)) as mando ";
               $sql.=" ,sum(cast(ci.validable_gerente as int)) as gerente ";
        $sql.=" from CA_Eventos ev ";
			$sql.=" inner join ca_asistencia_responsables ae on ev.Empleado_Codigo = ae.Empleado_Codigo ";
				$sql.=" and ev.Asistencia_codigo = ae.Asistencia_codigo ";
			$sql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
        $sql.=" where ";
			$sql.=" ae.responsable_codigo = ".$this->empleado_codigo." and ";
			$sql.=" cast(convert(varchar(10),ev.fecha_reg_inicio,103) as datetime) ";
				 $sql.=" between cast(convert(varchar(10),dateadd(day,-31,getdate()),103) as datetime) and cast(convert(varchar(10),getdate(),103) as datetime) ";
			$sql.=" and (ci.validable = 1 or ci.validable_mando = 1 or ci.validable_gerente=1) ";
        $rs = $cn->Execute($sql);
        $padre=array();
        if(!$rs->EOF){
            $hijo=array();
            $hijo["persona"]=$rs->fields[0];
            $hijo["mando"]=$rs->fields[1];
            $hijo["gerente"]=$rs->fields[2];
            array_push($padre, $hijo);
        }else{
            $hijo=array();
            $hijo["persona"]="0";
            $hijo["mando"]="0";
            $hijo["gerente"]="0";
            array_push($padre, $hijo);
        }

        $rs=null;
        
    return $padre;
}


function detalle_validadores(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        $ssql=" select ca_incidencias.incidencia_descripcion,vdatos.empleado,vdatos.Empleado_Email ";
	$ssql.=" from ca_incidencia_areas  ";
		$ssql.=" inner join ca_incidencias on ca_incidencia_areas.incidencia_codigo = ca_incidencias.incidencia_codigo ";
        $ssql.=" inner join vdatos on vdatos.empleado_codigo = ca_incidencia_areas.empleado_codigo ";
        $ssql.=" inner join areas on ca_incidencia_areas.area_codigo = areas.area_codigo  ";
			$ssql.=" and areas.area_activo = 1 ";
	$ssql.=" where ca_incidencia_areas.incidencia_codigo = ".$this->incidencia_codigo." ";
        $ssql.=" group by ca_incidencias.incidencia_descripcion,vdatos.empleado,vdatos.Empleado_Email ";
        $ssql.=" order by vdatos.empleado asc ";
        
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
       
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}

function listado_evento_log(){
    
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        $ssql=" select ";
	$ssql.=" case ca_evento_log.aprobado when 'N' then 'No' else 'Si' end as  aprobado ";
	$ssql.=" ,ca_evento_log.observacion ";
	$ssql.=" ,convert(varchar,ca_evento_log.fecha_registro,103) +'-'+convert(varchar,ca_evento_log.fecha_registro,108) as registro ";
	$ssql.=" ,ca_evento_motivo.em_descripcion ";
        $ssql.=" ,case when ca_evento_log.realizado = 'S' then ";
		$ssql.=" case when ca_evento_log.horas<10 then '0' else '' end + cast(ca_evento_log.horas as varchar)+':'+case when ca_evento_log.minutos<10 then '0' else '' end + cast(ca_evento_log.minutos as varchar) ";
	$ssql.=" else ";
                $ssql.=" case when ca_evento_log.aprobado = 'N' then '' ";
                    $ssql.=" else ";
                    $ssql.=" case when ca_eventos.horas<10 then '0' else '' end + cast(ca_eventos.horas as varchar)+':'+case when ca_eventos.minutos<10 then '0' else '' end + cast(ca_eventos.minutos as varchar) ";
                    $ssql.=" end ";
	$ssql.=" end	";
        $ssql.=" ,case ca_evento_log.realizado when 'S' then 'Supervisor' when 'V' then 'Validador' when 'M' then 'Mando' end  as realizado ";
        $ssql.=" ,cast(ca_evento_log.empleado_codigo as varchar)+','+cast(ca_evento_log.asistencia_codigo as varchar)+','+cast(ca_evento_log.evento_codigo as varchar)+','+cast(ca_eventos.incidencia_codigo as varchar) as registro    ";
        $ssql.=" ,case when ca_evento_log.horas is null then -1 else ca_evento_log.horas end as h ";
        $ssql.=" ,case when ca_evento_log.minutos is null then -1 else ca_evento_log.minutos end as m ";
        $ssql.=" ,case when ca_evento_log.incidencia_codigo_sustituye is null then -1 else ca_evento_log.incidencia_codigo_sustituye end as incidencia_sustituye ";
        $ssql.=" from ca_evento_log ";
	$ssql.=" inner join ca_evento_motivo on ca_evento_log.em_codigo = ca_evento_motivo.em_codigo  ";
	$ssql.=" inner join ca_eventos on ca_evento_log.empleado_codigo = ca_eventos.empleado_codigo ";
		$ssql.=" and ca_evento_log.asistencia_codigo = ca_eventos.asistencia_codigo and ca_evento_log.evento_codigo = ca_eventos.evento_codigo ";
	$ssql.=" where ca_evento_log.empleado_codigo = ".$this->empleado_codigo." and ca_evento_log.asistencia_codigo = ".$this->asistencia_codigo." and ca_evento_log.evento_codigo = ".$this->evento_codigo." ";
        
        $rs = $cn->Execute($ssql);
       
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            $hijo["aprobado"]=$rs->fields[0];
            $hijo["observacion"]=$rs->fields[1];
            $hijo["registro"]=$rs->fields[2];
            $hijo["motivo"]=$rs->fields[3];
            $hijo["cant_horas"]=$rs->fields[4];
            $hijo["realizado"]=$rs->fields[5];
            $hijo["codigos"]=$rs->fields[6];
            $hijo["h"]=$rs->fields[7];
            $hijo["m"]=$rs->fields[8];
            $hijo["incidencia_sustituye"]=$rs->fields[9];
            array_push($padre, $hijo);
            $rs->MoveNext();
        }

        $rs=null;
     
    return $padre;
    
}


function obtener_evento(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        /*$ssql="select ev.empleado_codigo,";
        $ssql.="e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as Nombre,";
        $ssql.="i.incidencia_descripcion as Evento";
        $ssql.=" from ca_eventos ev(nolock) inner join empleados e(nolock)";
        $ssql.=" on ev.empleado_codigo = e.empleado_codigo ";
        $ssql.=" inner join ca_incidencias i(nolock)";
        $ssql.=" on ev.incidencia_codigo = i.incidencia_codigo";
        $ssql.=" where ev.empleado_codigo = ".$this->empleado_codigo." and ev.asistencia_codigo = ".$this->asistencia_codigo." and ev.evento_codigo = ".$this->evento_codigo." and ev.incidencia_codigo = ".$this->incidencia_codigo." ";
        $ssql.=" and e.Estado_Codigo = 1 and (i.validable = 1 or i.validable_mando=1) and ev.evento_activo = 1";
        $ssql.=" order by e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres asc";*/
        
        
        $ssql="select ev.empleado_codigo,";
        $ssql.="e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as Nombre,";
        $ssql.="i.incidencia_descripcion as Evento";
        $ssql.=" from ca_eventos ev(nolock) inner join empleados e(nolock)";
        $ssql.=" on ev.empleado_codigo = e.empleado_codigo ";
        $ssql.=" inner join ca_incidencias i(nolock) ";
        $ssql.=" on ev.incidencia_codigo = i.incidencia_codigo ";
        $ssql.=" where ev.empleado_codigo = ".$this->empleado_codigo." and ev.asistencia_codigo = ".$this->asistencia_codigo." and ev.evento_codigo = ".$this->evento_codigo." and ev.incidencia_codigo = ".$this->incidencia_codigo." ";
        $ssql.=" and e.Estado_Codigo = 1 and i.validable = 1 and ev.evento_activo = 1 ";
        $ssql.=" order by e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres asc ";
        
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
       
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            
            $reg .="  </tr>\n";
            $lista .= $reg;
            //$rs->MoveNext();
            $rs->MoveNext();
          

        }

        $rs->close();
        $rs=null;   
    
    
    return $lista;
    
}


function listado_eventos_mando(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        //ADM
        $formato="set dateformat dmy";
        $rsx = $cn->Execute($formato);
       
        $ssql=" select ";
        $ssql.=" cast(CA_Eventos.empleado_codigo as varchar)+'-'+cast(CA_Eventos.asistencia_codigo as varchar)+'-'+cast(CA_Eventos.evento_codigo as varchar)+'-'+cast(CA_Eventos.incidencia_codigo as varchar) as codigo ";
        $ssql.=" ,CA_Eventos.empleado_codigo ";
        $ssql.=" ,dbo.UDF_EMPLEADO_NOMBRE(CA_Eventos.empleado_codigo) as nombre ";
        $ssql.=" ,ca_incidencias.incidencia_descripcion ";
        $ssql.=" ,convert(varchar(10),ca_asistencias.asistencia_fecha,103) as asistencia_fecha ";
        $ssql.=" ,case when CA_Eventos.horas<10 then '0' else '' end + cast(CA_Eventos.horas as varchar)+':'+case when CA_Eventos.minutos<10 then '0' else '' end + cast(CA_Eventos.minutos as varchar) as horainicio ";
        $ssql.=" ,ca_evento_estado.ee_descripcion,ca_evento_estado.ee_codigo ";
        $ssql.=" ,ca_asistencia_responsables.responsable_codigo as supervisor_codigo ";
        $ssql.=" ,dbo.UDF_EMPLEADO_NOMBRE(ca_asistencia_responsables.responsable_codigo) as supervisor_descripcion ";
        $ssql.=" ,areas.area_descripcion, CA_Eventos.ee_codigo ,CA_Eventos.num_ticket ";
        $ssql.=" from CA_Eventos ";
        $ssql.=" inner join ca_incidencias on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo ";
        $ssql.=" inner join ca_evento_estado on CA_Eventos.ee_codigo = ca_evento_estado.ee_codigo ";
        
        //SE AGREGO EL CAMPO LOCAL DESCRIPCION
        //$ssql.=" left join Locales loc on loc.Local_Codigo = e.Local_Codigo ";
        //SE AGREGO EL CAMPO LOCAL DESCRIPCION
        
        $ssql.=" inner join ca_asistencia_responsables on CA_Eventos.empleado_codigo = ca_asistencia_responsables.empleado_codigo ";
        $ssql.=" and CA_Eventos.asistencia_codigo = ca_asistencia_responsables.asistencia_codigo ";
        $ssql.=" inner join ca_asistencias on CA_Eventos.empleado_codigo = ca_asistencias.empleado_codigo ";
        $ssql.=" and CA_Eventos.asistencia_codigo = ca_asistencias.asistencia_codigo ";
        //$ssql.=" inner join empleado_area on CA_Eventos.empleado_codigo = empleado_area.empleado_codigo ";
        //$ssql.=" inner join areas on empleado_area.area_codigo = areas.area_codigo ";
        $ssql.=" inner join areas on ca_asistencias.area_codigo = areas.area_codigo ";
        if($this->mando==2){
            $ssql.=" inner join  Areas g on areas.Area_Jefe=g.Area_Codigo ";
        }
        
        if($this->mando==0){
            $ssql.=" inner join ca_incidencia_areas on ca_incidencia_areas.incidencia_codigo = ca_incidencias.incidencia_codigo ";
            //$ssql.=" and ca_incidencia_areas.area_codigo = empleado_area.area_codigo ";
            $ssql.=" and ca_incidencia_areas.area_codigo = areas.area_codigo ";   
        }
        $ssql.=" where ";
        if($this->mando==0) $ssql.=" ca_incidencia_areas.empleado_codigo = ".$this->empleado_codigo." and ";
        if($this->mando==1) $ssql.=" ca_incidencias.validable_mando = 1 ";
        if($this->mando==0) $ssql.=" ca_incidencias.validable = 1 ";
        if($this->mando==2) $ssql.=" ca_incidencias.validable_gerente = 1 ";
        //$ssql.=" and empleado_area.empleado_area_Activo = 1 ";
        $ssql.=" and areas.Area_Activo = 1 ";
        if($this->mando==1) $ssql.=" and Areas.area_codigo in (".$this->area.") ";
        if($this->mando==2) $ssql.=" and g.empleado_responsable =  ".$this->empleado_codigo." ";
        
        $ssql.=" and CA_Eventos.fecha_reg_inicio ";
                        $ssql.=" between cast(convert(varchar(10),dateadd(day,-31,getdate()),103) as datetime) and cast(convert(varchar(10),getdate()+1,103) as datetime) ";
        $ssql.=" order by convert(varchar(10),ca_asistencias.asistencia_fecha,103) desc,nombre asc ";
        
        $rs = $cn->Execute($ssql);
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            $marcado=$rs->fields[7]==3 ? "disabled" : "";
            
            $hijo["codigo"]=$rs->fields[0];
            $hijo["empleado_codigo"]=$rs->fields[1];
            $hijo["nombre"]=$rs->fields[2];
            $hijo["incidencia_descripcion"]=$rs->fields[3];
            $hijo["fecha_registro"]=$rs->fields[4];
            $hijo["horas"]=$rs->fields[5];
            $hijo["proceso_descripcion"]=$rs->fields[6];
            $hijo["proceso_codigo"]=$rs->fields[7];
            $hijo["marcado"]=$marcado;
            $hijo["supervisor_codigo"]=$rs->fields[8];
            $hijo["supervisor_descripcion"]=$rs->fields[9];
            $hijo["areas_descripcion"]=$rs->fields[10];
            $hijo["tipo"]=$rs->fields[11];
            $hijo["ticket"]=$rs->fields[12];
            array_push($padre, $hijo);
            $rs->MoveNext();
        }

        $rs=null;
    }
    
    return $padre;
    
}


function reportexmando(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
    
    $formato="set dateformat dmy";
    $rsx = $cn->Execute($formato);
    
    $ssql.=" select ";
        $ssql.=" count(case when CA_Eventos.ee_codigo=3 then CA_Eventos.ee_codigo end) as  aprobados ";
        $ssql.=" ,count(case when CA_Eventos.ee_codigo=4 then CA_Eventos.ee_codigo end) as  rechazados ";
        $ssql.=" from CA_Eventos ";
                $ssql.=" inner join ca_incidencias on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo ";
                $ssql.=" inner join empleado_area on CA_Eventos.empleado_codigo = empleado_area.empleado_codigo ";
        $ssql.=" where ";
        $ssql.=" ca_incidencias.validable_mando = 1 ";
        $ssql.=" and empleado_area.empleado_area_Activo = 1 ";
        $ssql.=" and empleado_area.area_codigo in (".$this->area.") ";
        //$ssql.=" and cast(convert(varchar(10),CA_Eventos.fecha_reg_inicio,103) as datetime) ";
        $ssql.=" and CA_Eventos.fecha_reg_inicio ";
                $ssql.=" between cast(convert(varchar(10),dateadd(day,-31,getdate()),103) as datetime) and cast(convert(varchar(10),getdate()+1,103) as datetime) ";

        $rs = $cn->Execute($ssql);
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            $hijo["aprobados"]=$rs->fields[0];   
            $hijo["rechazados"]=$rs->fields[1];   
            array_push($padre, $hijo);
            $rs->MoveNext();
        }
        $rs=null;
    }
    return $padre;
}



function getHMV(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
    $ssql="select horas,minutos,incidencia_codigo_sustituye ,";
    $ssql.="(select ca_incidencias.incidencia_descripcion from ca_incidencias 
                            where ca_incidencias.incidencia_codigo = ca_evento_log.incidencia_codigo_sustituye )
                            as descripcion,incidencia_codigo ";
    $ssql.=" from ca_evento_log where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." ";
    $ssql.=" and evento_codigo = ".$this->evento_codigo." and realizado = 'V' ";
    
    
    
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
         $this->incidencia_codigo_sustituye=$rs->fields[2];
         $this->incidencia_descripcion=$rs->fields[3];
         $this->incidencia_codigo=$rs->fields[4];
    }

    $rs->close();
    $rs=null;
    
}


function getCHMS(){
    $rpta="OK";
    $cantidad=0;
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql=" select count(el_codigo) as cantidad ";
        $ssql.=" from ca_evento_log where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." ";
        $ssql.=" and evento_codigo = ".$this->evento_codigo." and realizado = 'S'";
        
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $cantidad=$rs->fields[0];
        }
        
        $rs->close();
        $rs=null;
        
    }
    return $cantidad;
}

function getHMS(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
    if($cn){
        
        $ssql=" select horas,minutos ";
        $ssql.=" from ca_evento_log where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." ";
        $ssql.=" and evento_codigo = ".$this->evento_codigo." and realizado = 'S' ";
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $this->hora=$rs->fields[0];
             $this->minuto=$rs->fields[1];
        }
        $rs=null;
            
    }
}

function getHMLog(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        $ssql=" select top 1 horas,minutos,incidencia_codigo, ";
        $ssql.=" case when incidencia_codigo_sustituye is null then -1 else incidencia_codigo_sustituye end ";
        $ssql.=" as incidencia_codigo_sustituye ";
        $ssql.=" from ca_evento_log ";
        $ssql.=" where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." ";
        $ssql.=" and evento_codigo = ".$this->evento_codigo." order by fecha_registro desc ";
        
    
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $this->hora=$rs->fields[0];
             $this->minuto=$rs->fields[1];
             $this->incidencia_codigo=$rs->fields[2];
             $this->incidencia_codigo_sustituye=$rs->fields[3];
        }
        
        $rs->close();
        $rs=null;
        
    }
}


function registrar_Silencios(){
    $rpta="OK";
    $ssql="";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    if($cn){
	
	$ssql="insert into ca_evento_log(empleado_codigo,asistencia_codigo,evento_codigo,";
        $ssql.="aprobado,observacion,em_codigo,usuario_registro,";
        $ssql.="fecha_registro,ip_registro,horas,minutos,incidencia_codigo,incidencia_codigo_sustituye,realizado) ";
        $ssql.=" values(?,?,?,?,?,";
        $ssql.="2,?,GETDATE(),null,null,null,null,null,?)";
        
        $params = array(
            $this->empleado_codigo,
            $this->asistencia_codigo,
            $this->evento_codigo,
            $this->aprobado,
            $this->texto_descripcion,
            $this->empleado_jefe,
            $this->realizado
        );
        
        $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
            $rpta = "Error al Insertar empleados.";
        }
    }
    return $rpta;
}

function validaEstadoEvento(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        $ssql="select ee_codigo from ca_eventos ";
            $ssql.="where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." and evento_codigo = ".$this->evento_codigo." and incidencia_codigo = ".$this->incidencia_codigo." ";
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $this->estado_evento=$rs->fields[0];
        }
        
        $rs->close();
        $rs=null;
}

function cambiar_estado_evento(){
    
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    
    if($cn){
        
        $ssql="update ca_eventos set ee_codigo = ".$this->estado_evento." where empleado_codigo = ? and asistencia_codigo = ? and evento_codigo = ? ";
        
        $params = array(
            $this->empleado_codigo,
            $this->asistencia_codigo,
            $this->evento_codigo
        );
        
        
        $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
            $rpta = "Error al cambiar de estado";
        }
        
    }
    
    return $rpta;
}



function obtener_incidencia(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        //obtener datos de la incidencia marcada como evento
        $ssql="select incidencia_descripcion,";
	$ssql.=" validable,area_codigo_vbo,horas_vbo";
	$ssql.=" from ca_incidencias where incidencia_codigo = ".$this->incidencia_codigo." ";
        
        
        $rs = $cn->Execute($ssql);
        
        if (!$rs->EOF){
             $this->incidencia_descripcion =$rs->fields[0];
             $this->validable = $rs->fields[1];
             $this->area_codigo_vbo= $rs->fields[2];
             $this->horas_vbo = $rs->fields[3];
        }
        $rs->close();
        $rs=null;
    
}


function obtener_empleado(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        $ssql="select empleado_codigo,empleado_email,";
	$ssql.=" empleado_apellido_paterno,empleado_apellido_materno,empleado_nombres   ";
        $ssql.=" from empleados where empleado_codigo=".$this->empleado_codigo;
        
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $this->empleado_codigo =$rs->fields[0]; //responsable_codigo
             $this->empleado_email = $rs->fields[1];
             $this->empleado_apellido_paterno = $rs->fields[2];
             $this->empleado_apellido_materno = $rs->fields[3];
             $this->empleado_nombres = $rs->fields[4];
        }
        $rs->close();
        $rs=null;
    
}


function actualizar_evento(){
        
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        
        $ssql=" update ca_eventos set ";
        
        if($this->hora!=NULL && $this->incidencia_codigo_sustituye!=NULL){
            $ssql.=" horas =  ".$this->hora.", minutos =  ".$this->minuto." , ";
            $ssql.=" incidencia_codigo =  ".$this->incidencia_codigo_sustituye." ";
        }else{
            //if($this->hora!=NULL && $this->minuto!=NULL){
            if($this->hora!=NULL){
                $ssql.=" horas =  ".$this->hora.", minutos =  ".$this->minuto."  ";
            } 
            if($this->incidencia_codigo_sustituye!=NULL){
                $ssql.=" incidencia_codigo =  ".$this->incidencia_codigo_sustituye." ";
            }
        }
        
        $ssql.=" where empleado_codigo = ? and asistencia_codigo = ? and evento_codigo = ? ";
        
        
         $params = array(
            $this->empleado_codigo,
            $this->asistencia_codigo,
            $this->evento_codigo
        );
        
        $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
            $rpta = "Error al actualizar evento";
        }
        
    }
    //return $ssql;
    return $rpta;
}


/*
function registrar_evento(){
    $rpta="OK";
    $ssql="";
    $cn = $this->getMyConexionADO();
    if($cn){
		$ssql=" select case when (select empleado_codigo from ca_eventos ";
                    $ssql.=" where empleado_codigo = ".$this->empleado_codigo."  and asistencia_codigo = ".$this->asistencia_codigo." and evento_codigo = ".$this->evento_codigo." ) ";
            $ssql.=" is not null then 1 else 0 end as existe, ";
            $ssql.=" case when (select empleado_codigo from ca_eventos ";
                    $ssql.=" where empleado_codigo = ".$this->empleado_codigo."  and asistencia_codigo = ".$this->asistencia_codigo." and evento_codigo = ".$this->evento_codigo." and ee_codigo = 2 and evento_activo = 1) ";
            $ssql.=" is not null then 1 else 0 end as activo ";
		
		$r = $cn->Execute($ssql);
		if(($r->fields[0]*1==0 && $r->fields[1]*1==0) || ($r->fields[0]*1==1 && $r->fields[1]*1==1)){
		
			$ssql="insert into ca_evento_log(empleado_codigo,asistencia_codigo,evento_codigo,";
			$ssql.="aprobado,observacion,em_codigo,usuario_registro,";
			$ssql.="fecha_registro,ip_registro,horas,minutos,incidencia_codigo,incidencia_codigo_sustituye,realizado) ";
			$ssql.=" values(?,?,?,?,?,";
			$ssql.="1,?,GETDATE(),?,?,?,?,?,?)";
        
			$params = array(
				$this->empleado_codigo,
				$this->asistencia_codigo,
				$this->evento_codigo,
				$this->aprobado,
				$this->texto_descripcion,
				$this->empleado_jefe,
				$this->empleado_ip,
				$this->hora,
				$this->minuto,
				$this->incidencia_codigo,
				$this->incidencia_codigo_sustituye,
				$this->realizado
			);        
			$this->flag_validable=1;
			$rs=$cn->Execute($ssql, $params);
			if(!$rs) $rpta = "Error al Insertar empleados.";
			$rs=null;
			$r=null;
		}else{
			$this->flag_validable=0;
		}
    }
    return $rpta;
}
*/

/*MODIFICADO POR BANNY SOLANO*/
function registrar_evento(){
    $rpta="OK";
    $ssql="";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    if($cn){
		$ssql=" select case when (select empleado_codigo 
                                  from ca_eventos 
                                  where empleado_codigo = ".$this->empleado_codigo."  
                                    and asistencia_codigo = ".$this->asistencia_codigo." 
                                    and evento_codigo = ".$this->evento_codigo." ) 
                            is not null then 1 else 0 end as existe, 
                        case when (select empleado_codigo
                                   from ca_eventos 
                                   where empleado_codigo = ".$this->empleado_codigo."  
                                        and asistencia_codigo = ".$this->asistencia_codigo." 
                                        and evento_codigo = ".$this->evento_codigo." 
                                        and ee_codigo = 2 
                                        and evento_activo = 1) 
                is not null then 1 else 0 end as activo ";
		$r = $cn->Execute($ssql);
		if(($r->fields[0]*1==0 && $r->fields[1]*1==0) || ($r->fields[0]*1==1 && $r->fields[1]*1==1)){
		
			$ssql="insert into ca_evento_log(empleado_codigo,asistencia_codigo,evento_codigo,";
			$ssql.="aprobado,observacion,em_codigo,usuario_registro,";
			$ssql.="fecha_registro,ip_registro,horas,minutos,incidencia_codigo,incidencia_codigo_sustituye,realizado) ";
			$ssql.=" values(?,?,?,?,?,";
			$ssql.="1,?,GETDATE(),?,?,?,?,?,?)";
            
			$params = array(
				$this->empleado_codigo,
				$this->asistencia_codigo,
				$this->evento_codigo,
				$this->aprobado,
				$this->texto_descripcion,
				$this->empleado_jefe,
				$this->empleado_ip,
				$this->hora,
				$this->minuto,
				$this->incidencia_codigo,
				$this->incidencia_codigo_sustituye,
				$this->realizado
			);        
			$this->flag_validable=1;
			$rs=$cn->Execute($ssql, $params);
			if(!$rs) $rpta = "Error al Insertar Log.";
			$rs=null;
			$r=null;
		}else{
			$this->flag_validable=0;
		}
    }
    return $rpta;
}


function desactivar_evento(){
    
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    if($cn){
        
        
        $ssql=" update ca_eventos set evento_activo = 0 
	            where empleado_codigo = ? and asistencia_codigo = ? and evento_codigo = ? and incidencia_codigo = ? ";
        
        
        
        $params = array(
            $this->empleado_codigo,
            $this->asistencia_codigo,
            $this->evento_codigo,
            $this->incidencia_codigo
        );
        
        
        $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
            $rpta = "Error al cambiar de estado";
        }
        
    }
    
    return $rpta;
}


function Dias_Valida(){
    $rpta="";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql=" select area_codigo,diasvalidaciongap from areas where area_codigo in(".$this->area.") and area_activo = 1 ";    
        $rs = $cn->Execute($ssql);
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            $hijo["area_codigo"]=$rs->fields[0];
            $dias=$rs->fields[1];
            $text=" exec Validacion_Dias  ".$dias." ";
            $r = $cn->Execute($text);
            $ndias=$r->fields[0];
            $hijo["dias_validacion"]=$ndias;
            array_push($padre, $hijo);
            $rs->MoveNext();
        }
        return $padre;
    }
}

function listado_eventos_validador_(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        $arrdias=$this->Dias_Valida();
    
        $ssql=" select cast(e.empleado_codigo as varchar(10))+'-'+cast(ev.asistencia_codigo as varchar(10))+'-'+cast(ev.evento_codigo as varchar(10))+'-'+cast(ev.incidencia_codigo as varchar(10)) as codigo, ";
        $ssql.=" e.empleado_codigo, e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as nombre, ";
        $ssql.=" ci.incidencia_descripcion, ";
        $ssql.=" convert(varchar(10),cas.asistencia_fecha,103) as horainicio, ";
        $ssql.=" case when horas<10 then '0' else '' end + cast(horas as varchar(10))+':'+case when minutos<10 then '0' else '' end + cast(minutos as varchar(10)) as horainicio, ";
        $ssql.=" ee.ee_descripcion,ee.ee_codigo, ";
        $ssql.=" ae.responsable_codigo as supervisor_codigo, ";
        $ssql.=" ( select es.empleado_apellido_paterno+' '+es.empleado_apellido_materno+' '+es.empleado_nombres 
                        from empleados as es where es.empleado_codigo = ae.responsable_codigo ) as supervisor_descripcion ,ar.area_descripcion, ev.ee_codigo ,ev.num_ticket ";
        $ssql.=" from CA_Eventos ev inner join empleados e ";
        $ssql.=" on ev.empleado_codigo=e.empleado_codigo ";
        $ssql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
        $ssql.=" inner join ca_evento_estado ee on ev.ee_codigo = ee.ee_codigo ";
        $ssql.=" inner join ca_asistencia_responsables ae on  ev.empleado_codigo = ae.empleado_codigo ";
        $ssql.=" and ev.asistencia_codigo = ae.asistencia_codigo ";
        $ssql.=" inner join ca_asistencias cas on ev.empleado_codigo = cas.empleado_codigo and ev.asistencia_codigo = cas.asistencia_codigo ";
        $ssql.=" inner join areas ar on ar.area_codigo = cas.area_codigo ";
        
        if($this->mando==2){
            $ssql.=" inner join  Areas g on ar.Area_Jefe=g.Area_Codigo ";
        }
        
        if($this->mando==0){
            $ssql.=" inner join ca_incidencia_areas iia on iia.incidencia_codigo = ci.incidencia_codigo ";
            $ssql.=" and iia.area_codigo = ar.area_codigo ";
        } 
        
        $ssql.=" where ";
                
        if($this->mando==0) $ssql.="  iia.empleado_codigo = ".$this->empleado_codigo." and ";
        $ssql.=" cast (ev.evento_activo as integer) = 1 and ev.ee_codigo = 2 ";
        
        if($this->mando==0){
            if($this->incidencia_proceso!=0){
                $ssql.=" and ci.incidencia_codigo = ".$this->incidencia_proceso;
            }
        }
        
	$ssql.=" and e.Estado_Codigo = 1 ";
        
        if($this->mando==0) $ssql.=" and ci.validable = 1 ";
        if($this->mando==1) $ssql.=" and ci.validable_mando = 1 ";
        if($this->mando==2) $ssql.=" and ci.validable_gerente = 1 ";
        
        $ssql.=" and ar.Area_Activo=1 ";
        
        if($this->mando==0){
            if($this->area!="0") $ssql.=" and ar.area_codigo = ".$this->area;
        }
        if($this->mando==1){
            if($this->area!="0") $ssql.=" and ar.area_codigo in (".$this->area.")";
        }
        
        if($this->mando==2){
            $ssql.=" and g.empleado_responsable = ".$this->empleado_codigo." ";
        }
        
        if($this->mando==2){
            if($this->area!="0") $ssql.=" and ar.Area_Codigo = ".$this->area." ";
        }
        
        $ssql.=" order by cas.asistencia_fecha desc,e.empleado_apellido_paterno asc  ";
        
        $padre=array();
        $rs = $cn->Execute($ssql);
        
        while(!$rs->EOF){
            $hijo=array();
            $marcado=$rs->fields[7]==3 ? "disabled" : "";
            
            $hijo["codigo"]=$rs->fields[0];
            $hijo["empleado_codigo"]=$rs->fields[1];
            $hijo["nombre"]=$rs->fields[2];
            $hijo["incidencia_descripcion"]=$rs->fields[3];
            $hijo["fecha_registro"]=$rs->fields[4];
            $hijo["horas"]=$rs->fields[5];
            $hijo["proceso_descripcion"]=utf8_decode($rs->fields[6]);
            $hijo["proceso_codigo"]=$rs->fields[7];
            $hijo["marcado"]=$marcado;
            $hijo["supervisor_codigo"]=$rs->fields[8];
            $hijo["supervisor_descripcion"]=$rs->fields[9];
            $hijo["areas_descripcion"]=$rs->fields[10];
            $hijo["tipo"]=$rs->fields[11];
            $hijo["ticket"]=$rs->fields[12];
            array_push($padre, $hijo);
            $rs->MoveNext();
        }
        
        $rs=null;
    return $padre;
}


function listado_eventos_validador(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    $ssql="";
    
    $ssql.=" select cast(e.empleado_codigo as varchar(10))+'-'+cast(ev.asistencia_codigo as varchar(10))+'-'+cast(ev.evento_codigo as varchar(10))+'-'+cast(ev.incidencia_codigo as varchar(10)) as codigo, ";
    $ssql.=" e.empleado_codigo, e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as nombre, ";
    $ssql.=" ci.incidencia_descripcion, ";
    $ssql.=" convert(varchar(10),cas.asistencia_fecha,103) as horainicio, ";
    $ssql.=" case when horas<10 then '0' else '' end + cast(horas as varchar(10))+':'+case when minutos<10 then '0' else '' end + cast(minutos as varchar(10)) as horainicio, ";
    $ssql.=" ee.ee_descripcion,ee.ee_codigo, ";
    $ssql.=" ae.responsable_codigo as supervisor_codigo, ";
    $ssql.=" ( select es.empleado_apellido_paterno+' '+es.empleado_apellido_materno+' '+es.empleado_nombres ";
    $ssql.=" from empleados as es where es.empleado_codigo = ae.responsable_codigo ) as supervisor_descripcion ,ar.area_descripcion, ev.ee_codigo ,ev.num_ticket,loc.Local_Descripcion as local_descripcion ";
    $ssql.=" from CA_Eventos ev inner join empleados e ";
    $ssql.=" on ev.empleado_codigo=e.empleado_codigo ";
    //SE AGREGO EL CAMPO LOCAL DESCRIPCION
    $ssql.=" left join Locales loc on loc.Local_Codigo = e.Local_Codigo ";
    //SE AGREGO EL CAMPO LOCAL DESCRIPCION
    $ssql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
    $ssql.=" inner join ca_evento_estado ee on ev.ee_codigo = ee.ee_codigo ";
    $ssql.=" inner join ca_asistencia_responsables ae on  ev.empleado_codigo = ae.empleado_codigo ";
    $ssql.=" and ev.asistencia_codigo = ae.asistencia_codigo ";
    $ssql.=" inner join ca_asistencias cas on ev.empleado_codigo = cas.empleado_codigo and ev.asistencia_codigo = cas.asistencia_codigo ";
    $ssql.=" inner join areas ar on ar.area_codigo = cas.area_codigo ";
    $ssql.=" inner join ca_incidencia_areas iia on iia.incidencia_codigo = ci.incidencia_codigo ";
    $ssql.=" and iia.area_codigo = ar.area_codigo ";
    $ssql.=" where ";
    $ssql.=" iia.empleado_codigo = ".$this->empleado_codigo." and ";
    $ssql.=" cast (ev.evento_activo as integer) = 1 and ev.ee_codigo = 2 ";
    $ssql.=" and e.Estado_Codigo = 1 ";
    $ssql.=" and ci.validable = 1 ";
    $ssql.=" and ar.Area_Activo=1 ";
            if($this->incidencia_proceso!=0){
                $ssql.=" and ci.incidencia_codigo = ".$this->incidencia_proceso;
            }
            if($this->area!="0") $ssql.=" and ar.area_codigo = ".$this->area;
            
    $ssql.=" union ";
    $ssql.=" select cast(e.empleado_codigo as varchar(10))+'-'+cast(ev.asistencia_codigo as varchar(10))+'-'+cast(ev.evento_codigo as varchar(10))+'-'+cast(ev.incidencia_codigo as varchar(10)) as codigo, ";
    $ssql.=" e.empleado_codigo, e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as nombre, ";
    $ssql.=" ci.incidencia_descripcion, ";
    $ssql.=" convert(varchar(10),cas.asistencia_fecha,103) as horainicio, ";
    $ssql.=" case when horas<10 then '0' else '' end + cast(horas as varchar(10))+':'+case when minutos<10 then '0' else '' end + cast(minutos as varchar(10)) as horainicio, ";
    $ssql.=" ee.ee_descripcion,ee.ee_codigo, ";
    $ssql.=" ae.responsable_codigo as supervisor_codigo, ";
    $ssql.=" ( select es.empleado_apellido_paterno+' '+es.empleado_apellido_materno+' '+es.empleado_nombres ";
    $ssql.=" from empleados as es where es.empleado_codigo = ae.responsable_codigo ) as supervisor_descripcion ,ar.area_descripcion, ev.ee_codigo ,ev.num_ticket,loc.Local_Descripcion as local_descripcion ";
    $ssql.=" from CA_Eventos ev inner join empleados e ";
    $ssql.=" on ev.empleado_codigo=e.empleado_codigo ";
    //SE AGREGO EL CAMPO LOCAL DESCRIPCION
    $ssql.=" left join Locales loc on loc.Local_Codigo = e.Local_Codigo ";
    //SE AGREGO EL CAMPO LOCAL DESCRIPCION
    $ssql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
    $ssql.=" inner join ca_evento_estado ee on ev.ee_codigo = ee.ee_codigo ";
    $ssql.=" inner join ca_asistencia_responsables ae on  ev.empleado_codigo = ae.empleado_codigo ";
    $ssql.=" and ev.asistencia_codigo = ae.asistencia_codigo ";
    $ssql.=" inner join ca_asistencias cas on ev.empleado_codigo = cas.empleado_codigo and ev.asistencia_codigo = cas.asistencia_codigo ";
    $ssql.=" inner join areas ar on ar.area_codigo = cas.area_codigo  ";
    $ssql.=" where ar.empleado_responsable=".$this->empleado_codigo." and ";
    $ssql.=" cast (ev.evento_activo as integer) = 1 and ev.ee_codigo = 2 ";
    $ssql.=" and ci.validable_mando = 1 ";
    $ssql.=" and e.Estado_Codigo = 1 ";
    $ssql.=" and ar.Area_Activo=1 ";
    
    if($this->area!="0") $ssql.=" and ar.area_codigo in (".$this->area.")";
    
    $ssql.=" union ";
    $ssql.=" select cast(e.empleado_codigo as varchar(10))+'-'+cast(ev.asistencia_codigo as varchar(10))+'-'+cast(ev.evento_codigo as varchar(10))+'-'+cast(ev.incidencia_codigo as varchar(10)) as codigo, ";
    $ssql.=" e.empleado_codigo, e.empleado_apellido_paterno+' '+e.empleado_apellido_materno+' '+e.empleado_nombres as nombre, ";
    $ssql.=" ci.incidencia_descripcion, ";
    $ssql.=" convert(varchar(10),cas.asistencia_fecha,103) as horainicio, ";
    $ssql.=" case when horas<10 then '0' else '' end + cast(horas as varchar(10))+':'+case when minutos<10 then '0' else '' end + cast(minutos as varchar(10)) as horainicio, ";
    $ssql.=" ee.ee_descripcion,ee.ee_codigo, ";
    $ssql.=" ae.responsable_codigo as supervisor_codigo, ";
    $ssql.=" ( select es.empleado_apellido_paterno+' '+es.empleado_apellido_materno+' '+es.empleado_nombres ";
    $ssql.=" from empleados as es where es.empleado_codigo = ae.responsable_codigo ) as supervisor_descripcion ,ar.area_descripcion, ev.ee_codigo ,ev.num_ticket,loc.Local_Descripcion as local_descripcion ";
    $ssql.=" from CA_Eventos ev inner join empleados e ";
    $ssql.=" on ev.empleado_codigo=e.empleado_codigo ";
    //SE AGREGO EL CAMPO LOCAL DESCRIPCION
    $ssql.=" left join Locales loc on loc.Local_Codigo = e.Local_Codigo ";
    //SE AGREGO EL CAMPO LOCAL DESCRIPCION
    $ssql.=" inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo ";
    $ssql.=" inner join ca_evento_estado ee on ev.ee_codigo = ee.ee_codigo ";
    $ssql.=" inner join ca_asistencia_responsables ae on  ev.empleado_codigo = ae.empleado_codigo ";
    $ssql.=" and ev.asistencia_codigo = ae.asistencia_codigo ";
    $ssql.=" inner join ca_asistencias cas on ev.empleado_codigo = cas.empleado_codigo and ev.asistencia_codigo = cas.asistencia_codigo ";
    $ssql.=" inner join areas ar on ar.area_codigo = cas.area_codigo ";
    $ssql.=" inner join Areas j on ";
    $ssql.=" ar.Area_Jefe=j.Area_Codigo ";
    $ssql.=" where j.empleado_responsable=".$this->empleado_codigo." ";
    $ssql.=" and cast (ev.evento_activo as integer) = 1 and ev.ee_codigo = 2 ";
    $ssql.=" and ci.validable_gerente = 1 ";
    $ssql.=" and ar.Area_Activo=1 ";
    $ssql.=" and j.Area_Activo=1 ";
    $ssql.=" and e.Estado_Codigo = 1 ";
    
    if($this->area!="0") $ssql.=" and j.area_codigo in (".$this->area.")";
    
        
     
        //echo $ssql;
        $padre=array();
        $rs = $cn->Execute($ssql);
        
        while(!$rs->EOF){
            $hijo=array();
            $marcado=$rs->fields[7]==3 ? "disabled" : "";
            /************************************AGREGADO POR BANNY SOLANO************************************/
            $parametros = explode("-",$rs->fields[0]);
            $p_empleado     = $parametros[0];
            $p_asistencia   = $parametros[1];
            $p_evento       = $parametros[2];
            $p_incidencia   = $parametros[3];
            $mostrar = $this->verifica_flujo_evento($p_empleado, $p_asistencia, $p_evento,$p_incidencia, $this->mando);
            /****************************************************************************************/
            if($mostrar == "SI"):
            //if(1):
                $hijo["codigo"]=$rs->fields[0];
                $hijo["empleado_codigo"]=$rs->fields[1];
                $hijo["nombre"]=$rs->fields[2];
                $hijo["incidencia_descripcion"]=$rs->fields[3];
                $hijo["fecha_registro"]=$rs->fields[4];
                $hijo["horas"]=$rs->fields[5];
                $hijo["proceso_descripcion"]=utf8_decode($rs->fields[6]);
                $hijo["proceso_codigo"]=$rs->fields[7];
                $hijo["marcado"]=$marcado;
                $hijo["supervisor_codigo"]=$rs->fields[8];
                $hijo["supervisor_descripcion"]=$rs->fields[9];
                $hijo["areas_descripcion"]=$rs->fields[10];
                $hijo["tipo"]=$rs->fields[11];
                //$hijo["ticket"]=$rs->fields[12].$p_empleado."-".$p_asistencia."-".$p_evento."-".$p_incidencia."-".$this->mando;
                $hijo["ticket"]=$rs->fields[12];
                $hijo["local_descripcion"]=$rs->fields[13];
                array_push($padre, $hijo);
            endif;
            $rs->MoveNext();
        }
        
        $rs=null;
    return $padre;
}
/*CREADO POR: Banny Solano */
/*FECHA : 01/10/2013*/
/*DESCRIPCION:  verificamos el flujo de la incidencia para 
                saber deacuerdo a quien lo $ejecuta si se 
                debe mostrar o no el evento en la bandeja
                $ejecuta : 0 = area de apoyo
                           1 = mando
                           2 = gerente 
*/
function verifica_flujo_evento($empleado,$asistencia, $evento,$incidencia, $ejecuta){
    $cn = $this->getMyConexionADO();
    $mostrar = "NO";
    $params = array($incidencia);
    $sql = "SELECT VALIDABLE_MANDO, VALIDABLE_GERENTE,VALIDABLE 
            FROM CA_INCIDENCIAS 
            WHERE INCIDENCIA_CODIGO = ?";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0){
        $secuencia = "".$rs->fields[0].$rs->fields[1].$rs->fields[2]; //formato: Mando|Gerente|Apoyo
        switch($secuencia){
            case '000':
                    //no deberia entrar a este flag
                    break;
            case '001'://SOLO PASA POR AREA DE APOYO
                    if($ejecuta == 0) //EL EJECUTOR DEBE SER EL AREA DE APOYO
                        $mostrar = "SI";
                        
                    break;
            case '010'://SOLO PASA POR GERENTE
                    if($ejecuta == 2) //EL EJECUTOR DEBE SER EL GERENTE
                        $mostrar = "SI";
                    break;
            case '011'://FLUJO GERENTE->APOYO
                    if($ejecuta == 2 ) //SI LO EJECUTA EL GERENTE
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'S');//debe haberlo aprobado el S(supervisor)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    if($ejecuta == 0 ) //SI LO EJECUTA EL AREA DE APOYO
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'G');//debe haberlo aprobado el G(gerente)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    break;
            case '100'://SOLO PASA POR MANDO
                    if($ejecuta == 1) //EL EJECUTOR DEBE SER EL MANDO
                        $mostrar = "SI";break;
            case '101'://FLUJO MANDO->APOYO
                    if($ejecuta == 1 ) //SI LO EJECUTA EL MANDO
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'S');//debe haberlo aprobado el S(supervisor)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    if($ejecuta == 0 ) //SI LO EJECUTA EL AREA DE APOYO
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'M');//debe haberlo aprobado el M(mando)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    break;  
            case '110'://FLUJO MANDO->GERENTE
                    if($ejecuta == 1 ) //SI LO EJECUTA EL MANDO
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'S');//debe haberlo aprobado el S(supervisor)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    if($ejecuta == 2 ) //SI LO EJECUTA EL GERENTE
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'M');//debe haberlo aprobado el M(mando)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    break;
            case '111'://FLUJO MANDO->GERENTE->AREA DE APOYO
                    if($ejecuta == 1 ) //SI LO EJECUTA EL MANDO
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'S');//debe haberlo aprobado el S(supervisor)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    if($ejecuta == 2 ) //SI LO EJECUTA EL GERENTE
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'M');//debe haberlo aprobado el M(mando)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    if($ejecuta == 0 ) //SI LO EJECUTA EL AREA DE APOYO
                        $rpta = $this->verifica_ejecutor_evento($empleado,$asistencia, $evento,'G');//debe haberlo aprobado el M(mando)
                        if($rpta == 1){
                            $mostrar = "SI";
                        }
                    break;
        }
    }
    return $mostrar;
}

/*CREADO POR: Banny Solano */
/*FECHA : 01/10/2013*/
/*DESCRIPCION:  verificamos si existe el registro del evento*/
function verifica_ejecutor_evento($empleado, $asistencia, $evento, $realizado){
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    //OBTENEMOS LA ULTIMA APROBACION DEL LOG
    $params = array($empleado, $asistencia, $evento);
    $sql = "SELECT TOP 1  EL_CODIGO
            FROM CA_EVENTO_LOG 
            WHERE EMPLEADO_CODIGO = ? AND ASISTENCIA_CODIGO = ? AND EVENTO_CODIGO = ?
            ORDER BY CA_EVENTO_LOG.EL_CODIGO DESC";
    $rs = $cn->Execute($sql, $params);
    $el_codigo = $rs->fields[0];
    
    $params = array($el_codigo, $empleado, $asistencia, $evento, $realizado);
    $sql = "SELECT * 
            FROM CA_EVENTO_LOG 
            WHERE EL_CODIGO = ? AND EMPLEADO_CODIGO = ? AND ASISTENCIA_CODIGO = ? AND EVENTO_CODIGO = ? AND REALIZADO = ?";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0){
        return 1;
    }
    return 0;            
}

/*CREADO POR: Banny Solano */
/*FECHA : 01/10/2013*/
/*DESCRIPCION:  verificamos el flujo*/
function flujo_validacion($incidencia, $validador){
    $cn = $this->getMyConexionADO();
    $params = array($incidencia);
    $sql = "SELECT VALIDABLE_GERENTE,VALIDABLE 
            FROM CA_INCIDENCIAS 
            WHERE INCIDENCIA_CODIGO = ?";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0){
        if($rs->fields[0] == 1 and $validador == 'M'){
            return 'G';//continua con validable por gerente
        }
        if($rs->fields[1] == 1){
            return 'V';//continua con validable por area de apoyo
        }
        return "OK"; //si devuelve ok no hay mas flujo
    }else{
        echo "Error en el flujo de validacion";
    }
}

/*CREADO POR: Banny Solano */
/*FECHA : 01/10/2013*/
/*DESCRIPCION:  verificamos el flujo*/
function siguiente_aprobador($incidencia, $validador){
    $cn = $this->getMyConexionADO();
    $params = array($incidencia);
    $sql = "SELECT VALIDABLE_MANDO, VALIDABLE_GERENTE,VALIDABLE 
            FROM CA_INCIDENCIAS 
            WHERE INCIDENCIA_CODIGO = ?";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0){
        $flujo = $rs->fields[0]."".$rs->fields[1]."".$rs->fields[2]; //mando-gerente-validable
        switch($validador){
            case 'S':
                if($rs->fields[0] == 1){ //siguiente validador es el mando, verificamos si con el mando se termina el flujo
                    $this->realizado = 'M'; //en el log debir ir M, como siguiente aprobador
                    if($flujo == '100'){
                        return "NO"; //continua el flujo : SI
                    }else{
                        return "SI"; //continua el flujo : NO
                    }
                }
                if($rs->fields[1] == 1){ //siguiente validador es el gerente, verificamos si con el gerente se termina el flujo
                    $this->realizado = 'G';//en el log debir ir G, como siguiente aprobador
                    if($rs->fields[2] == 1){
                        return "SI";//continua el flujo : SI
                    }else{
                        return "NO"; //continua el flujo : no
                    }
                }
                if($rs->fields[2] == 1){ //siguiente validador es el area de apoyo, verificamos si con el area se termina el flujo
                    $this->realizado = 'V'; //en el log debir ir V, como siguiente aprobador
                    return "NO"; //el ultimo nivel nunca continua el flujo
                }
                break;
            case 'M':
                if($rs->fields[1] == 1){ //siguiente validador es el gerente, verificamos si con el gerente se termina el flujo
                    $this->realizado = 'G';
                    if($rs->fields[2] == 1){
                        return "SI"; //continua el flujo : SI
                    }else{
                        return "NO"; //continua el flujo : NO
                    }
                }
                if($rs->fields[2] == 1){ //siguiente validador es el area de apoyo, verificamos si con el area se termina el flujo
                    $this->realizado = 'V'; //continua el flujo : NO
                    return "NO";
                }
                break;
            case 'G':
                if($rs->fields[2] == 1){
                    $this->realizado = 'V'; 
                    return "NO"; //continua el flujo : NO
                }
                break;
            case 'V'://fin del flujo
                $this->realizado = 'V';
                return "NO";
                break;
            
        }
        return "OK"; //si devuelve ok no hay mas flujo
    }else{
        echo "Error en el flujo de validacion";
    }
}
/*obtenemos la ultimo nivel en el que se encuentra la aprobacion*/
function flujo_silencio_administrativo($empleado, $asistencia, $evento, $incidencia){
    $cn = $this->getMyConexionADO();
    $params = array($empleado, $asistencia, $evento);
    $sql = "SELECT top 1 REALIZADO 
            FROM ca_evento_log 
            WHERE EMPLEADO_CODIGO=? AND ASISTENCIA_CODIGO = ? AND EVENTO_CODIGO = ?
            ORDER BY EL_CODIGO DESC";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0){
        $realizado = $rs->fields[0];
        $flujo = $this->siguiente_aprobador($incidencia, $realizado);
        return $flujo;
    }else{
        echo "Error al validar el flujo de silencio administrativo";
    }
    
}

function listado_reporte_validador(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    $ssql="
                select count(ev.empleado_codigo) as procval
				from CA_Eventos ev 
				  inner join ca_incidencias ci on ci.incidencia_codigo = ev.incidencia_codigo 
					and ci.validable = 1 and ev.evento_activo = 1 and ev.ee_codigo=2 
				  inner join empleado_area empa on ev.empleado_codigo = empa.empleado_codigo 
					and empa.empleado_area_Activo = 1 
				  inner join ca_incidencia_areas iia on iia.incidencia_codigo = ev.incidencia_codigo 
					and iia.empleado_codigo = ".$this->empleado_codigo." and iia.area_codigo = empa.area_codigo 
        ";
    
        $rs = $cn->Execute($ssql);
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            
            $hijo["cantidad"]=$rs->fields[0];   
            array_push($padre, $hijo);
            $rs->MoveNext();
        }

        $rs->close();
        $rs=null;
    
    
    
    return $padre;
    
}


function obtener_incidencia_sustituible(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        
        $ssql=" SELECT CI.INCIDENCIA_CODIGO_SUSTITUYE, ";
			$ssql.=" (SELECT CD.INCIDENCIA_DESCRIPCION FROM CA_INCIDENCIAS AS CD  ";
				$ssql.=" WHERE CD.INCIDENCIA_CODIGO = CI.INCIDENCIA_CODIGO_SUSTITUYE ) AS DESCRIPCION_SUSTITUYE ";
			$ssql.=" FROM CA_INCIDENCIAS AS CI WHERE CI.INCIDENCIA_CODIGO = ".$this->incidencia_codigo;
                        
                        
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $this->incidencia_codigo_sustituye=$rs->fields[0];
             $this->incidencia_descripcion_sustituye=$rs->fields[1];
        }
        $rs->close();
        $rs=null;
        
}


function cantidad_rechazos(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
        $ssql  = " select count(aprobado) as rechazados from ca_evento_log ";
        $ssql .= " where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." and evento_codigo = ".$this->evento_codigo." ";
        $ssql .=" and aprobado = 'N'";
        
        
        $rs = $cn->Execute($ssql);
        $indicador = $rs->fields[0];
    
    return $indicador;
}

function detectarAsesor(){
    $rpta="OK";
    $flagasesor=0;
    $cn = $this->getMyConexionADO();
    $ssql=" select count(aprobado) as rechazados from ca_evento_log ";
    $ssql.=" where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." and evento_codigo = ".$this->evento_codigo." ";
    $ssql.=" and cast(observacion as varchar(100))='GENERADO POR ASESOR' ";
    $rs = $cn->Execute($ssql);
    $flagasesor=$rs->fields[0];
    return $flagasesor;
}

function mi_equipo($area,$te_anio,$te_semana){
    $cadena="";
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
    if($cn){
        $params = array($this->responsable_codigo);
		// $ssql="SELECT Empleados.Empleado_Codigo,  ";
		// $ssql.= "   Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres as empleado,  ";
		// $ssql.= "   Area_descripcion,Cargo_descripcion,";
		// $ssql.=" 	isnull(t.turno_descripcion,''),ca_asignacion_empleados.asignacion_codigo, isnull((select tc_codigo_sap from vCA_Turnos_EmpleadoTH where empleado_codigo=Empleados.Empleado_Codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'') as tc_codigo";
		// $ssql.= " FROM Empleado_Area(nolock) INNER JOIN Empleados(nolock) ON  ";
		// $ssql.= "   Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo ";
  //       // $ssql.= " LEFT JOIN ca_turno_empleado(nolock) on ca_turno_empleado.empleado_codigo=Empleados.empleado_codigo ";
  //       // $ssql.= " inner join CA_Turnos_combinacion(nolock) on ca_turno_empleado.tc_codigo=CA_Turnos_combinacion.tc_codigo ";



		// $ssql.= " 	INNER JOIN vw_empleado_area_cargo(nolock) on vw_empleado_area_cargo.Empleado_Codigo=Empleados.Empleado_Codigo ";
		// $ssql .=" 	LEFT OUTER JOIN ca_asignacion_empleados(nolock) ON ca_asignacion_empleados.empleado_codigo=Empleados.empleado_codigo ";
		// $ssql .=" 	LEFT OUTER JOIN ca_turnos t on empleados.turno_codigo = t.turno_codigo ";
		// $ssql.= " WHERE (Empleados.Estado_Codigo = 1) AND (Empleado_Area.Empleado_Area_Activo = 1) and ";
		// $ssql.= "		 ca_asignacion_empleados.asignacion_activo=1 and ";
		// $ssql.= "		ca_asignacion_empleados.responsable_codigo= ?";
		// $ssql.= " order by 2 ";


        $ssql = "select e.Empleado_Codigo, empleado, e.Area_Descripcion,";
        $ssql.= "e.Cargo_descripcion, e.Horario_descripcion, isnull(e.turno_descripcion,''),";
        $ssql.= " isnull((select tc_codigo_sap from vCA_Turnos_EmpleadoTH where empleado_codigo=e.Empleado_Codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'') as tc_codigo, ";
        $ssql.= " isnull((select convert(varchar(10),te_fecha_inicio, 103) from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'') as te_fecha_inicio, ";
        $ssql.= " isnull((select convert(varchar(10),te_fecha_fin, 103) from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'')  as te_fecha_fin "; 
        $ssql.= " from vdatostotal e";
        $ssql.= " left join ca_asignacion_empleados cae on e.empleado_codigo=cae.empleado_codigo and cae.asignacion_activo=1 where cae.Responsable_Codigo = ?";

        $rs = $cn->Execute($ssql, $params);
		if(!$rs->EOF) {
		  $i=0;
		  while(!$rs->EOF){
            $i+=1;

            $fechainicio=$rs->fields[7];
            $fechafin=$rs->fields[8];

            if($rs->fields[6]){
                $combinacion_turno= $rs->fields[6];
                $combinacion_turno.= " &nbsp;<img border='0' src='../../Images/asistencia/inline011.gif' onclick=cmdVersap_onclick(". $rs->fields[0] .",". $te_semana .",". $te_anio .",'". $fechainicio ."','". $fechafin ."') style='cursor:hand' title='Programaci&oacute;n Semanal'>";
            }else{
                $combinacion_turno = 'S/C';
            }
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="	<td align=right >" . $i . "&nbsp;</td>";
			$cadena .="	<td align=center>&nbsp;<input type=checkbox id='chk" .$rs->fields[0]  . "' name='chk" . $rs->fields[0]  . "' value='" . $rs->fields[0]  . "'>&nbsp;</td>";
			$cadena .="	<td align=right>" . $rs->fields[0] . "&nbsp;</td>";
			$cadena .="	<td >&nbsp;" .$rs->fields[1] . "</td>";
			$cadena .="	<td >&nbsp;" . $rs->fields[2]  . "</td>";
			$cadena .="	<td >&nbsp;" . $rs->fields[3] . "</td>";
            $cadena .=" <td >&nbsp;" . $rs->fields[4] . "</td>";
            $cadena .=" <td >&nbsp;" . $rs->fields[5] . "</td>";
			$cadena .="	<td >&nbsp;" . $combinacion_turno . "</td>";
			$cadena .="</tr>";
			$rs->MoveNext();
	      }
       }
       $rs->close();
       $rs=null;
    }
    return $cadena;
 }

 function ver_equipo(){
    $cadena="";
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
    		$ssql="SELECT Empleados.Empleado_Codigo,  ";
    		$ssql.= "   Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres as empleado,  ";
    		$ssql.= "   Area_descripcion,Cargo_descripcion,";
    		$ssql.=" 	t.turno_descripcion,ca_asignacion_empleados.asignacion_codigo";
    		$ssql.= " FROM Empleado_Area(nolock) INNER JOIN Empleados(nolock) ON  ";
    		$ssql.= "   Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo ";
    		$ssql.= " 	INNER JOIN vw_empleado_area_cargo(nolock) on vw_empleado_area_cargo.Empleado_Codigo=Empleados.Empleado_Codigo ";
    		$ssql .=" 	LEFT OUTER JOIN ca_asignacion_empleados(nolock) ON ca_asignacion_empleados.empleado_codigo=Empleados.empleado_codigo ";
    		$ssql .=" 	LEFT OUTER JOIN ca_turnos t on empleados.turno_codigo = t.turno_codigo ";
    		$ssql.= " WHERE (Empleados.Estado_Codigo = 1) AND (Empleado_Area.Empleado_Area_Activo = 1) and ";
    		$ssql.= "		 ca_asignacion_empleados.asignacion_activo=1 and ";
    		$ssql.= "		ca_asignacion_empleados.responsable_codigo=" . $this->responsable_codigo;
    		$ssql.= " order by 2 ";
            $rs = $cn->Execute($ssql);
    		if(!$rs->EOF) {
    		  $i=0;
    		  while(!$rs->EOF){
                $i+=1;
                $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
    			$cadena .="	<td align=right >" . $i . "&nbsp;</td>";
    			$cadena .="	<td align=right>" . $rs->fields[0] . "&nbsp;</td>";
    			$cadena .="	<td >&nbsp;" .$rs->fields[1] . "</td>";
    			//$cadena .="	<td >&nbsp;" . $rs->fields[2]->value  . "</td>";
    			$cadena .="	<td >&nbsp;" . $rs->fields[3] . "</td>";
    			$cadena .="	<td >&nbsp;" . $rs->fields[4] . "</td>";
    			$cadena .="</tr>";
    			$rs->MoveNext();
    	      }
           }
           $rs->close();
           $rs=null;
        }
     return $cadena;
 }

function Numero_maximo_operadores(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql="SELECT item_default FROM items(nolock) WHERE (Item_Codigo = 642)";
		//echo $ssql;
        $rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			$this->maximo_operadores=$rs->fields[0];
		}else{
			$this->maximo_operadores=0;
		}
		$rs->close();
        $rs=null;
	}
	return $rpta;
}

function Total_operadores_grupo(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		//$ssql="SELECT COUNT(Asignacion_Codigo) AS total FROM CA_Asignacion_Empleados(nolock) ";
//		$ssql.=" WHERE (Responsable_Codigo = " . $this->responsable_codigo . ") AND (Asignacion_Activo = 1)";
        
        $ssql=" SELECT COUNT(CA_Asignacion_Empleados.Asignacion_Codigo) AS total 
                FROM CA_Asignacion_Empleados(nolock) inner join empleados(nolock) on
                    CA_Asignacion_Empleados.empleado_codigo = Empleados.empleado_codigo
                WHERE CA_Asignacion_Empleados.Responsable_Codigo = $this->responsable_codigo AND CA_Asignacion_Empleados.Asignacion_Activo = 1
    	           and empleados.estado_codigo=1";
        
		//echo $ssql;
        $rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			$this->total_operadores_grupo=$rs->fields[0];
		}else{
			$this->total_operadores_grupo=0;
		}
		$rs->close();
        $rs=null;
	}
	return $rpta;
}

function es_mismo_responsable(){
  $rpta="OK";
  $cn = $this->getMyConexionADO();
  if($rpta=="OK"){
    $ssql =" select asignacion_codigo from ca_asignacion_empleados(nolock) ";
    $ssql.=" WHERE empleado_codigo=" . $this->empleado_codigo;
    $ssql.=" and responsable_codigo=" . $this->responsable_codigo;
    $ssql.=" and asignacion_activo=1 ";
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
      //Existe Datos no se reemplaza
      $rpta="OK";
    }else{
      //No existen Datos si se puede cambiar
      $rpta="NO";
    }
    $rs->close();
    $rs=null;
  }
  return $rpta;
}


}
?>
