<?php
require_once("tumimail/class.phpmailer.php");
class ca_envio_correos extends mantenimiento{
var $responsable_codigo=0;    
var $hora=0;
var $tipo=0;
var $tiempo_inicio="";
var $tiempo_fin="";
var $validador_codigo=0;
var $flag=0;
var $host_mail="";
var $from_mail="";

function obtener_asesores(){
//obtiene las jornadas pasivas que han sido rechazadas por un validador de area de soporte o mando o gerente
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $ssql="";
    
    $ssql="set dateformat dmy";
    $cn->Execute($ssql);
    /*
    $ssql=" select ";
    $ssql.=" ca_asistencia_responsables.responsable_codigo,ca_incidencias.incidencia_descripcion, ";
    $ssql.=" empleados.Empleado_Apellido_Paterno,empleados.Empleado_Apellido_Materno,empleados.Empleado_Nombres, ";
    $ssql.=" ca_incidencias.horas_vbo,ca_eventos.ee_codigo ";
    $ssql.=" from ";
        $ssql.=" ca_eventos ";
        $ssql.=" inner join ca_evento_log on ";
        $ssql.=" ca_eventos.Empleado_Codigo = ca_evento_log.Empleado_Codigo and ";
        $ssql.=" ca_eventos.Asistencia_codigo = ca_evento_log.Asistencia_codigo and ";
        $ssql.=" ca_eventos.Evento_Codigo = ca_evento_log.Evento_Codigo ";
		$ssql.=" inner join ca_asistencia_responsables on ca_asistencia_responsables.Empleado_Codigo = CA_Eventos.Empleado_Codigo ";
			$ssql.=" and CA_Asistencia_Responsables.Asistencia_codigo = CA_Eventos.Asistencia_codigo ";
        $ssql.=" inner join ca_incidencias on ca_eventos.incidencia_codigo=ca_incidencias.incidencia_codigo and ca_incidencias.incidencia_activo=1 ";
        $ssql.=" inner join empleados on ca_eventos.empleado_codigo = empleados.empleado_codigo and empleados.Estado_Codigo=1 ";
        $ssql.=" where ";
                $ssql.=" ca_eventos.evento_activo = 0 and ";
                $ssql.=" (ca_incidencias.validable=1 or CA_Incidencias.validable_mando = 1 or CA_Incidencias.validable_gerente = 1) and ca_eventos.ee_codigo = 4 and ca_asistencia_responsables.responsable_codigo = ". $this->responsable_codigo." ";
    $ssql.=" group by ";
    $ssql.=" ca_asistencia_responsables.responsable_codigo,ca_incidencias.incidencia_descripcion, ";
    $ssql.=" empleados.Empleado_Apellido_Paterno,empleados.Empleado_Apellido_Materno,empleados.Empleado_Nombres, ";
    $ssql.=" ca_incidencias.horas_vbo,ca_eventos.ee_codigo, ";
    $ssql.=" ca_evento_log.Empleado_Codigo,ca_evento_log.Asistencia_codigo,ca_evento_log.Evento_Codigo ";
    $ssql.=" having ";
	$ssql.=" datediff(hh, max(ca_evento_log.fecha_registro),(select dateadd(minute,-1,tiempo_auxiliar) from ca_evento_rango)) < ca_incidencias.horas_vbo ";
    $ssql.=" and convert(varchar,max(ca_evento_log.fecha_registro),108) >= (select convert(varchar,tiempo_fin,108) from ca_evento_rango) ";
    $ssql.=" and  convert(varchar,max(ca_evento_log.fecha_registro),108) < (select convert(varchar,tiempo_auxiliar,108) from ca_evento_rango) ";
    */   
    
    $ssql = "exec spCA_Correo_Rechazo_Supervisor ".$this->responsable_codigo." ";
    
    $padre=array();
    $arrPendiente=array();
    $arrRechazado=array();

    $rs = $cn->Execute($ssql);

    while (!$rs->EOF){
        
        
        $hijo=array();
        $hijo["responsable_codigo"]=$rs->fields[0];
        $hijo["incidencia_descripcion"]=$rs->fields[1];
        $hijo["asesor"]=$rs->fields[2]." ".$rs->fields[3]." ".$rs->fields[4];
        $hijo["horas_vbo"]=$rs->fields[5];
        $hijo["proceso"]=$rs->fields[6];
        $hijo["comentario"]=$rs->fields[10];
        if($rs->fields[6]==1) array_push($arrPendiente, $hijo);
        else array_push ($arrRechazado, $hijo);
        $rs->MoveNext();
    }

    $padre["pen"]=$arrPendiente;
    $padre["rec"]=$arrRechazado;
        
    return $padre;
}
/*
CREADO POR  : Banny Solano
FECHA       : 31/10/2013 
DESCRIPCION : Obtiene todos los respomsables que intervinieron en el flujo
            menos el que rechazo el evento.*/
function obtiene_log_envio_correo(){
    $cn = $this->getMyConexionADO();
    $params = array($empleado_codigo, $asistencia_codigo, $evento_codigo);
    $sql = "SELECT v.empleado, v.Empleado_Email, l.* 
            FROM ca_evento_log l INNER JOIN vDatos v ON l.usuario_registro = v.Empleado_Codigo
            WHERE l.Empleado_Codigo = ? AND l.Asistencia_codigo = ? AND l.Evento_Codigo = ? and l.aprobado <> 'N'";
    $rs = $cn->Execute($sql, $params);
    return $rs;
}


function obtener_validadores(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="set dateformat dmy";
    $cn->Execute($ssql);
    $ssql=" select empleados.empleado_codigo,empleados.empleado_email
       from CA_Eventos 
		inner join ca_evento_log on 
			CA_Eventos.Empleado_Codigo = ca_evento_log.Empleado_Codigo 
			and CA_Eventos.Asistencia_codigo = ca_evento_log.Asistencia_codigo 
			and CA_Eventos.Evento_Codigo = ca_evento_log.Evento_Codigo
			and CA_Eventos.evento_activo = 1
		inner join ca_incidencias 
			on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo and ca_incidencias.validable=1
		inner join CA_Asistencias on CA_Eventos.Empleado_Codigo = CA_Asistencias.Empleado_Codigo
			and CA_Eventos.Asistencia_codigo = CA_Asistencias.Asistencia_codigo
		inner join ca_incidencia_areas on ca_incidencia_areas.incidencia_codigo = ca_incidencias.incidencia_codigo 
			and ca_incidencia_areas.area_codigo = CA_Asistencias.area_codigo
		inner join empleados on empleados.empleado_codigo = ca_incidencia_areas.empleado_codigo 
				and empleados.estado_codigo = 1
        where 
			CA_Eventos.ee_codigo = 2
            group by empleados.empleado_codigo,empleados.empleado_email,ca_incidencias.horas_vbo
            having 
				datediff(hh, max(ca_evento_log.fecha_registro),(select dateadd(minute,-1,tiempo_auxiliar) from ca_evento_rango)) < ca_incidencias.horas_vbo
				and convert(varchar,max(ca_evento_log.fecha_registro),108) >= (select convert(varchar,tiempo_fin,108) from ca_evento_rango)
				and  convert(varchar,max(ca_evento_log.fecha_registro),108) < (select convert(varchar,tiempo_auxiliar,108) from ca_evento_rango) 
            ";
    
    $padre=array();
    $rs = $cn->Execute($ssql);
    while (!$rs->EOF){
        $hijo=array();
        $hijo["empleado_validador"]=$rs->fields[0];
        $hijo["validador_correo"]=$rs->fields[1];
        array_push($padre, $hijo);
        $rs->MoveNext();
    }
    return $padre;
}

function obtener_incidencias_validador(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="set dateformat dmy";
    $rs1 = $cn->Execute($ssql);
    
    $ssql=" select	e.empleado_codigo,e.empleado_apellido_paterno,e.empleado_apellido_materno,e.empleado_nombres,
            ci.incidencia_descripcion,ee.ee_codigo,
			ar.area_descripcion,
			ci.horas_vbo,
            (select es.empleado_apellido_materno+' '+es.empleado_apellido_paterno+' '+es.empleado_nombres 
				from empleados as es where es.empleado_codigo = ae.responsable_codigo )
       from CA_Eventos ev 
			  inner join ca_evento_log l on 
			  ev.Empleado_Codigo = l.Empleado_Codigo and 
			  ev.Asistencia_codigo = l.Asistencia_codigo and
			  ev.Evento_Codigo = l.Evento_Codigo
			  inner join empleados e on ev.empleado_codigo=e.empleado_codigo 
              inner join ca_incidencias ci on ev.incidencia_codigo = ci.incidencia_codigo 
              inner join ca_evento_estado ee on ev.ee_codigo = ee.ee_codigo
			  inner join ca_asistencia_responsables ae on ev.Empleado_Codigo = ae.Empleado_Codigo
					and ev.Asistencia_codigo = ae.Asistencia_codigo
              inner join ca_asistencias cas on ev.empleado_codigo = cas.empleado_codigo and ev.asistencia_codigo = cas.asistencia_codigo
              inner join areas ar on cas.area_codigo = ar.area_codigo 
              inner join ca_incidencia_areas iia on iia.incidencia_codigo = ci.incidencia_codigo 
				and iia.empleado_codigo = ".$this->validador_codigo." and iia.area_codigo = cas.area_codigo    
       where 
			convert(varchar(10),ev.fecha_reg_inicio,103)=CONVERT(varchar(10),GETDATE(),103)
			and ev.evento_activo = 1     
            and ev.ee_codigo = 2
			and e.Estado_Codigo = 1 
			and ci.validable = 1 
			group by 
				e.empleado_codigo,e.empleado_apellido_paterno,e.empleado_apellido_materno,e.empleado_nombres,
				ci.incidencia_descripcion,ee.ee_codigo,
				ar.area_descripcion,ae.responsable_codigo,
				ci.horas_vbo,
				l.Empleado_Codigo,l.Asistencia_codigo,l.Evento_Codigo
			having 
				datediff(hh, max(l.fecha_registro),(select dateadd(minute,-1,tiempo_auxiliar) from ca_evento_rango)) < ci.horas_vbo
				and (convert(varchar,max(l.fecha_registro),108) >= (select convert(varchar,tiempo_fin,108) from ca_evento_rango)
                and  convert(varchar,max(l.fecha_registro),108) < (select convert(varchar,tiempo_auxiliar,108) from ca_evento_rango) )
        ";
    
				        
    $padre=array();

    $rs = $cn->Execute($ssql);
    $arrProceso=array();
    $arrObservado=array();

    while (!$rs->EOF){
        $hijo=array();
        $hijo["asesor_codigo"]=$rs->fields[0];
        $hijo["asesor_nombre"]=$rs->fields[1]." ".$rs->fields[2]." ".$rs->fields[3];
        $hijo["incidencia_descripcion"]=$rs->fields[4];
        $hijo["supervisor_nombre"]=$rs->fields[8];
        $hijo["horas_visacion"]=$rs->fields[7];
        $hijo["area"]=$rs->fields[6];
        $hijo["proceso"]=$rs->fields[5];
        if($rs->fields[5]==2) array_push($arrProceso,$hijo);//proceso
        else array_push ($arrObservado, $hijo);//observado
        $rs->MoveNext();
    }
    $padre["proc"]=$arrProceso;
    $padre["obs"]=$arrObservado;
    
    return $padre;
}

function obtener_responsable(){
    $cn=$this->getMyConexionADO();
    $ssql="set dateformat dmy";
    $rs1 = $cn->Execute($ssql);
    /*
    $ssql="select asr.responsable_codigo,em.empleado_email 
	 from ca_eventos e 
		inner join ca_evento_log l on 
			  e.Empleado_Codigo = l.Empleado_Codigo and 
			  e.Asistencia_codigo = l.Asistencia_codigo and
			  e.Evento_Codigo = l.Evento_Codigo
	 inner join ca_asistencia_responsables asr on asr.empleado_codigo = e.empleado_codigo 
         and asr.asistencia_codigo = e.asistencia_codigo 
         inner join empleados em on asr.responsable_codigo = em.empleado_codigo and em.Estado_Codigo = 1 
         inner join ca_incidencias ci on e.incidencia_codigo = ci.incidencia_codigo 
	 where 
          e.evento_activo=0 and 
         (ci.validable = 1 or ci.validable_mando = 1 or ci.validable_gerente = 1) and e.ee_codigo = 4
	 group by asr.responsable_codigo, em.empleado_email ,ci.horas_vbo
	 having 
		datediff(hh, max(l.fecha_registro),(select dateadd(minute,-1,tiempo_auxiliar) from ca_evento_rango)) < ci.horas_vbo
		and (convert(varchar,max(l.fecha_registro),108) >= (select convert(varchar,tiempo_fin,108) from ca_evento_rango)
		and  convert(varchar,max(l.fecha_registro),108) < (select convert(varchar,tiempo_auxiliar,108) from ca_evento_rango) )";
	*/
    $ssql = "SELECT asr.responsable_codigo,em.empleado_email 
            FROM ca_eventos e 
            	INNER JOIN ca_evento_log l on e.Empleado_Codigo = l.Empleado_Codigo and e.Asistencia_codigo = l.Asistencia_codigo and e.Evento_Codigo = l.Evento_Codigo
            	INNER JOIN ca_asistencia_responsables asr on asr.empleado_codigo = e.empleado_codigo and asr.asistencia_codigo = e.asistencia_codigo 
            	INNER JOIN empleados em on asr.responsable_codigo = em.empleado_codigo and em.Estado_Codigo = 1 
            	INNER JOIN ca_incidencias ci on e.incidencia_codigo = ci.incidencia_codigo 
            WHERE e.evento_activo=0 and  (ci.validable = 1 or ci.validable_mando = 1 or ci.validable_gerente = 1) and e.ee_codigo = 4
            GROUP BY asr.responsable_codigo, em.empleado_email ,ci.horas_vbo
            HAVING max(l.fecha_registro) >= (select tiempo_fin from ca_evento_rango) and  max(l.fecha_registro) < (select tiempo_auxiliar from ca_evento_rango)";
    $padre=array();
    $rs = $cn->Execute($ssql);
    
    while (!$rs->EOF){
        $hijo=array();
        $hijo["empleado_jefe"]=$rs->fields[0];
        $hijo["empleado_correo"]=$rs->fields[1];
        array_push($padre, $hijo);
        $rs->MoveNext();
    }
    
    return $padre;
}
/*CREADO POR    : BANNY SOLANO  */
/*FECHA         : 05/11/2013    */
/*DESCRIPCION   : OBTIENE EL LISTADO PARA EL ENVIO DE CORREOS
                DE RECHAZO DE EVENTOS    */
function obtener_listado_rechazos(){
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $sql = "exec SPCA_EVENTOS_RECHAZADOS";
    $rs = $cn->Execute($sql);
    return $rs;
}

/*CREADO POR    : BANNY SOLANO  */
/*FECHA         : 05/11/2013    */
/*DESCRIPCION   : OBTIENE EL LISTADO PARA EL ENVIO DE CORREOS
                DE EVENTOS POR APROBAR PARA EL AREA APROBADORA    */
function obtener_listado_valida(){
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $sql = "exec SPCA_EVENTOS_VALIDAR_APOYO";
    $rs = $cn->Execute($sql);
    return $rs;
}

/*CREADO POR    : BANNY SOLANO  */
/*FECHA         : 06/11/2013    */
/*DESCRIPCION   : OBTIENE EL LISTADO PARA EL ENVIO DE CORREOS
                DE EVENTOS POR APROBAR PARA EL MANDO   */
function obtener_listado_valida_mando(){
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $sql = "exec SPCA_EVENTOS_VALIDAR_MANDO";
    $rs = $cn->Execute($sql);
    return $rs;
}

/*CREADO POR    : BANNY SOLANO  */
/*FECHA         : 06/11/2013    */
/*DESCRIPCION   : OBTIENE EL LISTADO PARA EL ENVIO DE CORREOS
                DE EVENTOS POR APROBAR PARA EL MANDO   */
function obtener_listado_valida_gerente(){
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $sql = "exec SPCA_EVENTOS_VALIDAR_GERENTE";
    $rs = $cn->Execute($sql);
    return $rs;
}

function get_cab_mando_gerente($tipo){
//obtener las jornadas pasivas pendientes de validar por el mando o gerente
	$rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="set dateformat dmy";
    $cn->Execute($ssql);
	
	$ssql=" select vDatos.Empleado_Codigo,vDatos.Empleado_Email ";
    $ssql.=" from CA_Eventos ";
    $ssql.=" inner join ca_evento_log on ";
    $ssql.=" CA_Eventos.Empleado_Codigo = ca_evento_log.Empleado_Codigo ";
    $ssql.=" and CA_Eventos.Asistencia_codigo = ca_evento_log.Asistencia_codigo ";
    $ssql.=" and CA_Eventos.Evento_Codigo = ca_evento_log.Evento_Codigo ";
    $ssql.=" inner join ca_incidencias ";
    $ssql.=" on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo ";
    $ssql.=" inner join CA_Asistencias on CA_Eventos.Empleado_Codigo = CA_Asistencias.Empleado_Codigo ";
    $ssql.=" and CA_Eventos.Asistencia_codigo=CA_Asistencias.Asistencia_codigo ";
    $ssql.=" inner join areas ar on ar.area_codigo = CA_Asistencias.area_codigo ";
	if($tipo=="M") $ssql.=" inner join vDatos on ar.empleado_responsable = vDatos.Empleado_Codigo ";
	if($tipo=="G"){
		$ssql.=" inner join Areas j on ar.Area_Jefe = j.Area_Codigo ";
		$ssql.=" inner join vDatos on j.empleado_responsable = vDatos.Empleado_Codigo ";
	}
    $ssql.=" where ";
	$ssql.=" cast(CA_Eventos.evento_activo as integer) = 1 ";
	$ssql.=" and CA_Eventos.ee_codigo = 2 ";
	if($tipo=="M") $ssql.=" and ca_incidencias.validable_mando=1 ";
	if($tipo=="G") $ssql.=" and ca_incidencias.validable_gerente=1 ";
	$ssql.=" and ar.Area_Activo=1 ";
	if($tipo=="G") $ssql.=" and j.Area_Activo = 1 ";
    $ssql.=" group by vDatos.Empleado_Codigo,vDatos.Empleado_Email,ca_incidencias.horas_vbo ";
	$ssql.=" having ";
	$ssql.=" datediff(hh, max(ca_evento_log.fecha_registro),(select dateadd(minute,-1,tiempo_auxiliar) from ca_evento_rango)) < ca_incidencias.horas_vbo ";
	$ssql.=" and convert(varchar,max(ca_evento_log.fecha_registro),108) >= (select convert(varchar,tiempo_fin,108) from ca_evento_rango) ";
	$ssql.=" and  convert(varchar,max(ca_evento_log.fecha_registro),108) < (select convert(varchar,tiempo_auxiliar,108) from ca_evento_rango) ";
	$padre=array();
    $rs = $cn->Execute($ssql);
    while (!$rs->EOF){
        $hijo=array();
        $hijo["empleado_validador"]=$rs->fields[0];
        $hijo["validador_correo"]=$rs->fields[1];
        array_push($padre, $hijo);
        $rs->MoveNext();
    }
    return $padre;
}


function get_det_mando_gerente($tipo){
	$rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="set dateformat dmy";
    $cn->Execute($ssql);
	
	$ssql=" select	Empleados.empleado_codigo ";
	$ssql.=" ,dbo.UDF_EMPLEADO_NOMBRE(Empleados.empleado_codigo) as empleado ";
    $ssql.=" ,ca_incidencias.incidencia_descripcion ";
    $ssql.=" ,ca_evento_estado.ee_codigo ";
	$ssql.=" ,ar.area_descripcion ";
	$ssql.=" ,ca_incidencias.horas_vbo ";
    $ssql.=" ,dbo.UDF_EMPLEADO_NOMBRE(ca_asistencia_responsables.responsable_codigo) as responsable ";
$ssql.=" from CA_Eventos ";
	$ssql.=" inner join ca_evento_log on CA_Eventos.Empleado_Codigo = ca_evento_log.Empleado_Codigo  ";
		$ssql.=" and CA_Eventos.Asistencia_codigo = ca_evento_log.Asistencia_codigo  ";
		$ssql.=" and CA_Eventos.Evento_Codigo = ca_evento_log.Evento_Codigo ";
	$ssql.=" inner join Empleados on CA_Eventos.empleado_codigo=Empleados.empleado_codigo  ";
    $ssql.=" inner join ca_incidencias on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo ";
    $ssql.=" inner join ca_evento_estado on CA_Eventos.ee_codigo = ca_evento_estado.ee_codigo ";
    $ssql.=" inner join ca_asistencia_responsables on CA_Eventos.empleado_codigo = ca_asistencia_responsables.empleado_codigo ";
		$ssql.=" and CA_Eventos.asistencia_codigo = ca_asistencia_responsables.asistencia_codigo ";
    $ssql.=" inner join ca_asistencias on CA_Eventos.empleado_codigo = ca_asistencias.empleado_codigo ";
		$ssql.=" and CA_Eventos.asistencia_codigo = ca_asistencias.asistencia_codigo ";
    $ssql.=" inner join areas ar on ar.area_codigo = CA_Asistencias.area_codigo ";
	if($tipo=="G") $ssql.=" inner join Areas j on ar.Area_Jefe = j.Area_Codigo ";
$ssql.=" where ";
	$ssql.=" convert(varchar(10),CA_Eventos.fecha_reg_inicio,103)=CONVERT(varchar(10),GETDATE(),103) ";
	$ssql.=" and cast(CA_Eventos.evento_activo as integer) = 1     ";
    $ssql.=" and CA_Eventos.ee_codigo = 2 ";
	$ssql.=" and Empleados.Estado_Codigo = 1 ";
	if($tipo=="M") $ssql.=" and ca_incidencias.validable_mando = 1 ";
	if($tipo=="G") $ssql.=" and ca_incidencias.validable_gerente = 1 ";
	if($tipo=="M") $ssql.=" and ar.empleado_responsable=".$this->validador_codigo." ";
	if($tipo=="G") $ssql.=" and j.empleado_responsable= ".$this->validador_codigo."	";
	$ssql.=" and ar.Area_Activo=1 ";
	if($tipo=="G") $ssql.=" and j.Area_Activo = 1 ";
$ssql.=" group by ";
	$ssql.=" Empleados.empleado_codigo,Empleados.empleado_apellido_paterno,Empleados.empleado_apellido_materno ";
	$ssql.=" ,Empleados.empleado_nombres,ca_incidencias.incidencia_descripcion,ca_evento_estado.ee_codigo ";
	$ssql.=" ,ar.area_descripcion,ca_asistencia_responsables.responsable_codigo ";
	$ssql.=" ,ca_incidencias.horas_vbo,ca_evento_log.Empleado_Codigo,ca_evento_log.Asistencia_codigo,ca_evento_log.Evento_Codigo ";
$ssql.=" having ";
	$ssql.=" datediff(hh, max(ca_evento_log.fecha_registro),(select dateadd(minute,-1,tiempo_auxiliar) from ca_evento_rango)) < ca_incidencias.horas_vbo ";
	$ssql.=" and (convert(varchar,max(ca_evento_log.fecha_registro),108) >= (select convert(varchar,tiempo_fin,108) from ca_evento_rango) ";
    $ssql.=" and  convert(varchar,max(ca_evento_log.fecha_registro),108) < (select convert(varchar,tiempo_auxiliar,108) from ca_evento_rango) ) ";
    
	$padre=array();

    $rs = $cn->Execute($ssql);
    $arrProceso=array();
    
    while (!$rs->EOF){
        $hijo=array();
        $hijo["asesor_codigo"]=$rs->fields[0];
        $hijo["asesor_nombre"]=$rs->fields[1];
        $hijo["incidencia_descripcion"]=$rs->fields[2];
        $hijo["supervisor_nombre"]=$rs->fields[6];
        $hijo["horas_visacion"]=$rs->fields[5];
        $hijo["area"]=$rs->fields[4];
        $hijo["proceso"]=$rs->fields[3];
        array_push($arrProceso,$hijo);//proceso
        $rs->MoveNext();
    }
    $padre["proc"]=$arrProceso;
    return $padre;
}


function actualiza_hora(){
    $ssql="";
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
         $ssql.= " update ca_evento_rango set ";
        if($this->flag==1){
            $ssql.= " tiempo_auxiliar = getdate() ";
        }else{
            $ssql.= " tiempo_inicio = ( select tiempo_fin from ca_evento_rango ),";
            $ssql.= " tiempo_fin =  tiempo_auxiliar ";//tiempo auxiliar se convierte en tiempo fin
        }
        
        $rs=$cn->Execute($ssql);
    	if(!$rs) $rpta = "Error al Actualizar.";
    	else $rpta= "OK";
    	
    }
    return $rpta;
}

/*function enviar_mail($para, $asunto, $mensaje){
	$rpta="OK";
	$mail = new PHPMailer();
	//$host = "localhost";
	//$host = "10.252.130.35";
	//$from = "iatento@atentoperu.com.pe";
	//$from = "itumi@tumihost.com";
	$host = $this->host_mail;
	$from = $this->from_mail;
	//echo "host: " . $host;
	//echo "\nfrom: " . $from; 
	
	$mail->IsSMTP(); 
	$mail->Host = $host;
	$mail->SMTPAuth = false;			
	$mail->From = $from;
	$mail->FromName = "Atento";
	$mail->AddAddress($para);
	$mail->WordWrap = 50;
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);
	$mail->Subject = $asunto; 
	$mail->Body    = $mensaje; 
	$mail->AltBody = $mensaje;
	//$mail->AddAddress($para, 'Atento Peru'); 
	if(!$mail->Send()){
	   $rpta = "Mensaje no puede ser enviado!. " . $mail->ErrorInfo;
	}
	$mail=null;
	return $rpta;
}*/


function enviar_mail($para, $asunto, $mensaje){
       
	$rpta="OK";
	$mail = new PHPMailer();
	//$host = "tumisolutions.com";
	//$host = "10.252.130.35";
	//$from = "iatento@atentoperu.com.pe";
	//$from = "itumisol@tumisolutions.com";
	$host = $this->host_mail;
	$from = $this->from_mail;
	
	$mail->IsSMTP(); 
	$mail->Host = $host;
	$mail->SMTPAuth = false;			
	$mail->From = $from;
	$mail->FromName = "Atento";
	$mail->AddAddress($para);
	$mail->WordWrap = 50;
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);
	$mail->Subject = $asunto; 
	$mail->Body    = $mensaje; 
	$mail->AltBody = $mensaje;
	
	if(!$mail->Send()){
	   $rpta = "Mensaje no puede ser enviado. <p>" . $mail->ErrorInfo;
       }
    //echo "cuenta=".$para ." host=" . $host . " from=" . $from ;
	$mail=null;
	return $rpta;
        
}


}
?>