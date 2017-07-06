<?php
class ca_validar_incidencia extends mantenimiento{
var $responsable_codigo=0;
var $fecha="";
var $find="";
var $sql="";
var $empleado_codigo=0;
var $asistencia_fecha="";
var $asistencia_codigo=0;
var $tipo=0;

function listar_Grupo(){
    $data="";
    $cn=$this->getMyConexionADO();
    $ssql=" exec spCA_Listar_Grupo_Supervisor " . $this->responsable_codigo .", '" . $this->fecha ."','".$this->find."',".$this->tipo." ";
    
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        $data="OK";
        $padre=array();
        while(!$rs->EOF){
            
            $hijo=array();
            $hijo['responsable_codigo']=$rs->fields[0];
            $hijo['empleado_codigo']=$rs->fields[1];
            $hijo['asistencia_codigo']=$rs->fields[2];
            $hijo['empleado']=utf8_encode($rs->fields[3]);
            $hijo['asistencia']=$rs->fields[4];
            $hijo['asistencia_fecha']=$rs->fields[5];
            $hijo['asistencia_entrada']=$rs->fields[6];
            $hijo['asistencia_salida']=$rs->fields[7];
            $hijo['extension_turno']=$rs->fields[8];
            $hijo['extension_tiempo']=$rs->fields[9];
            
            $extension=$rs->fields[8]*1==0 ? 0 : $rs->fields[9];
            if(($rs->fields[17]*1==1)){
                $hijo['extension']=($extension!=0) ? 'Si - '.$rs->fields[9]:'No - '.$rs->fields[9];
                $hijo['tardanza_minutos']=($rs->fields[12].''!='') ? 'Si - '.$rs->fields[12]:'No';
            }else{
                $hijo['extension']='';
                $hijo['tardanza_minutos']='';
            }
            
            $hijo['turno_codigo']=$rs->fields[10];
            $hijo['turno_descripcion']=$rs->fields[11];
            $hijo['tipo_extension']=$rs->fields[16];
            $hijo['num']=$rs->fields[17];
            $hijo['vacaciones']=$rs->fields[18];
            $hijo['falta']=$rs->fields[21];
            $turno_minutos=$rs->fields[20].''!='' ? $rs->fields[20] : 0;
            $hijo['responsable_asistencia']=$rs->fields[19];
            $asistencia=$rs->fields[2].''!='' ? $rs->fields[2] : 0;
            
            $hijo['radio'] ='' . $rs->fields[0]. '_' .$rs->fields[1].'_'.$asistencia.'_'.$rs->fields[17].'_'.$rs->fields[18].'_'.$rs->fields[19].'_'.$turno_minutos.'_'.$extension.'_'.utf8_encode($rs->fields[3]).'';//N
            array_push($padre,$hijo);
            $rs->MoveNext();
            
            
        }        
    }
    
    $rs->close();
    $rs=null;
    return $padre;
}

function resumeRac(){
    $incidencias=array();
    $incidencias=$this->_listar_incidencias_asistencia();
    return $incidencias;
}

function resumenSupervisor(){
    $ssql="";
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql=" exec spCA_Resumen_Supervisor ".$this->responsable_codigo.", '".$this->fecha."' ";
        $rs = $cn->Execute($ssql);
        $padre=array();
        if(!$rs->EOF) {
            $hijo=array();
            $hijo['codigo']=$rs->fields[0];
            $hijo['total_empleados']=$rs->fields[1];
            $hijo['asistencia_fecha']=$this->fecha;
            $hijo['asistencias']=$rs->fields[2];
            $hijo['extensiones']=$rs->fields[3];
            $hijo['tardanzas']=$rs->fields[4];
            $hijo['turnos_especiales']=$rs->fields[5];
            array_push($padre,$hijo);
        }
    }
    $rs->close();
    $rs=null;
    return $padre;
}


function _listar_responsables_asistencia(){//--M1
    $ssql="";
    $cn=$this->getMyConexionADO();
    
    $ssql=" SELECT responsable_codigo,responsable, ";
    $ssql.=" case when exists(select Asistencia_Incidencia_codigo from ca_asistencia_incidencias ";
    $ssql.="      where empleado_codigo=" . $this->empleado_codigo ." and asistencia_codigo=" . $this->asistencia_codigo ." ";
    $ssql.="          and responsable_codigo=vwca_asistencias.responsable_codigo) ";
    $ssql.="      then 1 else 0 end as inc ";
    $ssql.=" FROM vwca_asistencias ";
    $ssql.=" WHERE (empleado_Codigo = " . $this->empleado_codigo .") and ";
    $ssql.=" (Asistencia_Codigo = " . $this->asistencia_codigo .") ";
    $ssql.=" Order by 2 ";
    
    $rs = $cn->Execute($ssql);
    $padre=array();
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $hijo=array();
            $hijo['responsable_codigo']=$rs->fields[0];
            $hijo['responsable']=$rs->fields[1];
            $hijo['inc']=$rs->fields[2];
            array_push($padre,$hijo);
            $rs->MoveNext();
        }
    }
    $rs->close();
    $rs=null;
    return $padre;
}

function _listar_incidencias_asistencia(){//--M2
    $cn=$this->getMyConexionADO();
    $ssql="";
    $ssql="SELECT CA_Asistencia_Incidencias.Empleado_Codigo, ";
    $ssql.="	CA_Asistencia_Incidencias.Asistencia_codigo, ";
    $ssql.="	CA_Asistencia_Incidencias.Incidencia_codigo, ";
    $ssql.="   (convert(varchar(10),CA_Asistencia_Incidencias.fecha_reg,103) + ' ' + convert(varchar(8), CA_Asistencia_Incidencias.fecha_reg,108)), ";
    $ssql.="	CA_Incidencias.Incidencia_descripcion AS incidencia, "; 
    $ssql.="	CA_Asistencia_Incidencias.responsable_codigo, ";
    $ssql.="	CA_Asistencia_Incidencias.asistencia_incidencia_codigo, ";
    $ssql.="	CA_incidencias.incidencia_icono, ";
    $ssql.="	convert(varchar(8),incidencia_hora_inicio, 108) as inicio, ";
    $ssql.="	convert(varchar(8),incidencia_hora_fin, 108) as fin, ";
    $ssql.="   case when len(horas)=1 then '0' + cast(horas as char) else cast(horas as char) end as hora, ";
    $ssql.="   case when len(minutos)=1 then '0' + cast(minutos as char) else cast(minutos as char) end as minutos, ";
    $ssql.="   abs(CA_Incidencias.Incidencia_Editable) as Incidencia_Editable, ";
    $ssql.="   case when exists(select responsable_codigo from ca_asistencia_responsables ";
    $ssql.="   				 where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
    $ssql.="   				 and responsable_codigo=" . $this->responsable_codigo . ") then 1 else 0 end as activo,";
    //$ssql.=" CA_Asistencia_Incidencias.tiempo_minutos ";
    $ssql.=" case when CA_Asistencia_Incidencias.incidencia_hora_inicio is null then CA_Asistencia_Incidencias.tiempo_minutos ";
       				$ssql.=" else DATEDIFF(minute,CA_Asistencia_Incidencias.incidencia_hora_inicio,CA_Asistencia_Incidencias.incidencia_hora_fin) end ";
       				$ssql.=" as tiempo_minutos ";
    $ssql.="   FROM CA_Asistencia_Incidencias ";
    $ssql.="	INNER JOIN CA_Incidencias ON ";
    $ssql.="   CA_Asistencia_Incidencias.Incidencia_codigo = CA_Incidencias.Incidencia_codigo ";
    $ssql.=  "  WHERE (CA_Asistencia_Incidencias.empleado_Codigo = " . $this->empleado_codigo .") and ";
    $ssql.=  "  (CA_Asistencia_Incidencias.Asistencia_Codigo = " . $this->asistencia_codigo .") and ";
    $ssql.=  "  (CA_Asistencia_Incidencias.Responsable_Codigo = " . $this->responsable_codigo .") ";
    $ssql.=  "  Order by 4 ";

    $padre=array();
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $hijo=array();
            $hijo['empleado_codigo']=$rs->fields[0];
            $hijo['asistencia_codigo']=$rs->fields[1];
            $hijo['incidencia_codigo']=$rs->fields[2];
            $hijo['incidencia']=$rs->fields[4];
            $hijo['responsable_codigo']=$rs->fields[5];
            $hijo['asistencia_incidencia_codigo']=$rs->fields[6];
            $hijo['incidencia_icono']=$rs->fields[7];
            $hijo['inicio']=$rs->fields[8].''!='' ? $rs->fields[8] : '';
            $hijo['fin']=$rs->fields[9].''!='' ? $rs->fields[9] : '';
            $hijo['hora']=$rs->fields[10];
            $hijo['minutos']=$rs->fields[11];
            $hijo['incidencia_editable']=$rs->fields[12];
            $hijo['activo']=$rs->fields[13];
            if($rs->fields[7].''!='') $hijo['imagen'] = " <img  src='../images/" . $rs->fields[7]. "' width='15' height='15' border='0'/> ";
            else $hijo['imagen'] = " <img  src='../images/stop_hand.png' width='15' height='15' border='0'/> ";
            if($rs->fields[10].''!='') $hijo['tiempo'] = $rs->fields[10] . ':' . $rs->fields[11];
            else $hijo['tiempo'] = "&nbsp;";
            
            $hijo['modificar']="<img onClick='cmdModificar_incidencia_onclick(" . $rs->fields[1] . "," .$rs->fields[6] ."," .$this->empleado_codigo ."," .$this->responsable_codigo ."," . $rs->fields[2] ."," . $rs->fields[12] ."," . $rs->fields[13] .")' src='../images/ico/edit.png' width='15' height='15' border='0'  style='cursor:pointer;' alt='Modificar incidencia'/>";
            $hijo['eliminar']="<img onClick='cmdEliminar_onclick(" . $rs->fields[1] ."," . $rs->fields[6] ."," .$this->empleado_codigo ."," .$this->responsable_codigo ."," . $rs->fields[2] ."," . $rs->fields[12] ."," . $rs->fields[13] .")' src='../images/ico/delete.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar incidencia'/>";
            $hijo['tiempo_minutos']=$rs->fields[14];
            
            array_push($padre,$hijo);
            $rs->MoveNext();
        }
    }
    $rs->close();
    $rs=null;
    return $padre;
}

function _listar_eventos_validables(){//--M3
    $cn = $this->getMyConexionADO();
    
    $ssql="";
    $ssql=" select cast(CA_Eventos.horas as varchar)+':'+cast(CA_Eventos.minutos as varchar) as tiempo ";
    $ssql.=" ,CA_Eventos.observacion ";
    $ssql.=" ,case when CA_Eventos.num_ticket is null then '' else CA_Eventos.num_ticket end ticket ";
    $ssql.=" ,case when ca_incidencias.validable=1 then 'Por Persona' else case when ca_incidencias.validable_mando=1 then 'Por Mando' else 'Por Gerente' end end validador ";
    $ssql.=" ,ca_incidencias.incidencia_descripcion ";
    $ssql.=" ,ca_evento_estado.ee_descripcion ";
    $ssql.=" from CA_asistencia_responsables ";
    $ssql.=" inner join CA_Eventos on CA_asistencia_responsables.empleado_codigo = CA_Eventos.empleado_codigo ";
    $ssql.=" and CA_asistencia_responsables.asistencia_codigo = CA_Eventos.asistencia_codigo ";
    $ssql.=" inner join ca_incidencias on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo ";
    $ssql.=" and (ca_incidencias.validable = 1 or ca_incidencias.validable_mando=1 or ca_incidencias.validable_gerente = 1 ) ";
    $ssql.=" inner join ca_evento_estado on CA_Eventos.ee_codigo = ca_evento_estado.ee_codigo ";
    $ssql.=" where ";
    $ssql.=" CA_asistencia_responsables.responsable_codigo = ".$this->responsable_codigo." ";
    $ssql.=" and CA_Eventos.empleado_codigo = ".$this->empleado_codigo." and CA_Eventos.asistencia_codigo = ".$this->asistencia_codigo." ";
    
    if($cn){
        $rs= $cn->Execute($ssql);
        $padre=array();
        if(!$rs->EOF) {
            while(!$rs->EOF){
                $hijo=array();
                $hijo['tiempo']=$rs->fields[0];
                $hijo['observacion']=$rs->fields[1];
                $hijo['ticket']=$rs->fields[2];
                $hijo['validador']=$rs->fields[3];
                $hijo['incidencia_descripcion']=$rs->fields[4];
                $hijo['ee_descripcion']=$rs->fields[5];
                array_push($padre,$hijo);
                $rs->MoveNext();
            }
        }
    }
    $rs->close();
    $rs=null;
    return $padre;
        
}

function _listado_registros_dia($area,$servicio,$turno,$tipo_area_codigo){//--Main
    $padre=array();
    $_responsable_asistencia=array();
    $_incidencias_asistencia=array();
    $_jornadas_asistencia=array();
    
    $_responsable_asistencia=$this->_listar_responsables_asistencia();
    $_incidencias_asistencia=$this->_listar_incidencias_asistencia();
    $_jornadas_asistencia=$this->_listar_eventos_validables();
    
    $_o_inc_resp{"responsable_asistencia"}=$_responsable_asistencia;
    $_o_inc_asis{"incidencia_asistencia"}=$_incidencias_asistencia;
    $_o_inc_jorn{"jornadas_asistencia"}=$_jornadas_asistencia;
    
    array_push($padre,$_o_inc_resp);
    array_push($padre,$_o_inc_asis);
    array_push($padre,$_o_inc_jorn);
    return $padre;
}

function _listar_cesados(){
    /*$cn=$this->getMyConexionADO();
    $ssql ="SELECT     dbo.Empleados.Empleado_Codigo,dbo.Empleados.Empleado_Apellido_Paterno + ' ' + "; 
    $ssql .="                ' ' + dbo.Empleados.Empleado_Apellido_Materno + ' ' + dbo.Empleados.Empleado_Nombres as empleado, "; 
    $ssql .=" dbo.CA_Asistencia_Responsables.responsable_codigo, "; 
    $ssql .=" dbo.CA_Asistencias.Asistencia_codigo "; 
    $ssql .=" FROM         dbo.CA_Asistencias WITH (nolock) INNER JOIN "; 
    $ssql .="                  dbo.Empleados ON dbo.CA_Asistencias.Empleado_Codigo = dbo.Empleados.Empleado_Codigo INNER JOIN "; 
    $ssql .="                  dbo.CA_Asistencia_Responsables ON dbo.CA_Asistencias.Empleado_Codigo = dbo.CA_Asistencia_Responsables.Empleado_Codigo AND "; 
    $ssql .="                  dbo.CA_Asistencias.Asistencia_codigo = dbo.CA_Asistencia_Responsables.Asistencia_codigo "; 
    $ssql .=" WHERE     (dbo.Empleados.Estado_Codigo = 2) AND (dbo.CA_Asistencia_Responsables.responsable_codigo = " . $this->responsable_codigo .") AND "; 
    $ssql .="                  (dbo.CA_Asistencias.Asistencia_fecha = CONVERT(DATETIME,'" . $this->fecha ."', 103)) AND dbo.CA_Asistencias.ca_estado_codigo=1"; 
    $ssql .=" order by empleado ";
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        $padre=array();
        while(!$rs->EOF){
            $hijo=array();
            $hijo['radio_cesado']=' <input type="radio" id="rdo" name="rdo" value="' . $rs->fields[2]. '_' .$rs->fields[0].'_'.$rs->fields[3].'_0_0_0_0_0_1_C" style="cursor:pointer;" onclick="javascript:com.atento.gap.ValidarIncidencia.cargarStorePivot(this.value)" title="Gestionar Asistencias"/>';
            $hijo['empleado_codigo_cesado']=$rs->fields[0];
            $hijo['empleado_nombre_cesado']=$rs->fields[1];
            array_push($padre,$hijo);
            $rs->MoveNext();
        }
    }
 
    $rs->close();
    $rs=null;
    return $padre;*/
    return $this->listar_Grupo();
    
}

function _listar_otros(){
    /*$cn=$this->getMyConexionADO();
    if($cn){
        $ssql="exec spca_listar_OtroGrupo " . $this->responsable_codigo .", '" . $this->fecha ."'";
        $rs = $cn->Execute($ssql);
        if(!$rs->EOF) {
            $padre=array();
            while(!$rs->EOF){
                $hijo=array();
                if ($rs->fields[9]==1){
                    $hijo['radio_otros']=' <input type="radio" id="rdo" name="rdo" value="' . $rs->fields[0]. '_' .$rs->fields[1].'_'.$rs->fields[3].'_0_0_0_0_0_1_C" style="cursor:pointer;" onclick="javascript:com.atento.gap.ValidarIncidencia.cargarStorePivot(this.value)" title="Gestionar Asistencias"/>';
                    $hijo['empleado_codigo_otros']=$rs->fields[0];
                    $hijo['empleado_nombre_otros']=$rs->fields[1];
                    $rs->MoveNext();
                }
                array_push($padre,$hijo);
                if (!$rs->EOF){
                    if ($rs->fields[9]==2){
                        while (!$rs->EOF && $rs->fields[9]==2){
                            $rs->MoveNext();
                        }
                    }
                }
            } // end while
        }
        $rs->close();
        $rs=null;   
        return $padre;
    }*/
    return $this->listar_Grupo();
}

}
?>


