<?php 
require_once(PathIncludes() . "mantenimiento.php");
require_once(PathIncludes() . "MyWritefile.php"); 

class ca_eventos extends mantenimiento{
var $asistencia_codigo=0;
var $empleado_codigo=0;
var $incidencia_codigo=0;
var $incidencia="";
var $evento_hora_inicio="";
var $evento_hora_fin="";
var $evento_inicio="";
var $evento_fin="";
var $e_inicio="";
var $e_fin="";
var $responsable_codigo="0";
var $fecha="";
var $saldo="";
var $fecha_reg="";
var $area_codigo="";
var $ip_registro="";

function registrar_inicio_evento(){
/* $rpta="OK";
 //$rpta=$this->conectarme_ado();
 $cn = $this->getMyConexionADO();
    if($cn){
	 // crea un nuevo registro
        $ssql="select isnull(max(evento_codigo),0)+1 id from ca_eventos ";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
		$rs= $this->cnnado->Execute($ssql);
		$this->evento_codigo = $rs->fields[0]->value;
		
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	    $sql =" insert into ca_eventos ";
	    $sql .=" (Empleado_codigo, Asistencia_codigo,Evento_Codigo,Incidencia_codigo,evento_hora_inicio,fecha_reg_inicio,evento_activo,ip_inicio_evento) ";
	    $sql .=" values(" . $this->empleado_codigo . ",";
		$sql .=" " . $this->asistencia_codigo . ",";
		$sql .=" " . $this->evento_codigo . ",";
		$sql .=" " . $this->incidencia_codigo . ",";
		$sql .=" getdate() , ";
	    //if($this->evento_hora_inicio.length ==0) $sql .=" getdate(),";
		//else $sql .=" CONVERT(DATETIME,'" . $this->evento_hora_inicio . "', 103), ";
		$sql .=" getdate(),1 , ";
		$sql .=" '" . $this->ip_registro . "')";
		
		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $sql;
    	//$cmd->Parameters[0]->value = $this->empleado_codigo;
		$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Insertar inicio Evento.";
		  return $rpta;
		}else{
		 $rpta= "OK";
		}
		$rs->close();
		$rs=null;
		$cmd=null;
	}
	  return $rpta;*/
 $rpta="OK";
 $cn = $this->getMyConexionADO();
    if($cn){
	 // crea un nuevo registro
        $ssql="select isnull(max(evento_codigo),0)+1 id from ca_eventos ";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
		$rs= $cn->Execute($ssql);
		$this->evento_codigo = $rs->fields[0];
	    
        $sql =" INSERT INTO ca_eventos ";
	    $sql .=" (Empleado_codigo, Asistencia_codigo,Evento_Codigo,Incidencia_codigo,evento_hora_inicio,fecha_reg_inicio,evento_activo,ip_inicio_evento) ";
	    $sql .=" values(" . $this->empleado_codigo . ",";
		$sql .=" " . $this->asistencia_codigo . ",";
		$sql .=" " . $this->evento_codigo . ",";
		$sql .=" " . $this->incidencia_codigo . ",";
		$sql .=" getdate() , ";
		$sql .=" getdate(),1 , ";
		$sql .=" '" . $this->ip_registro . "')";

		$r=$cn->Execute($sql);
	    if(!$r){
		  $rpta = "Error al Insertar inicio Evento.";
		  return $rpta;
		}else{
		  $rpta= "OK";
		}
	}
	  return $rpta;
 }

function registrar_fin_evento(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
  
        $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	    $ssql ="update ca_eventos ";
		$ssql .=" set evento_hora_fin=getdate(), ";
		$ssql .="    fecha_reg_fin=getdate(), ";
		$ssql .="    ip_fin_evento=? ";
		$ssql .=" where Empleado_Codigo=? ";
	    $ssql .=" and Asistencia_Codigo=? ";
		$ssql .=" and Evento_Codigo=? ";
		
		
		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
    	$cmd->Parameters[0]->value = $this->ip_registro;
    	$cmd->Parameters[1]->value = $this->empleado_codigo;
		$cmd->Parameters[2]->value = $this->asistencia_codigo;
		$cmd->Parameters[3]->value = $this->evento_codigo;

		$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Registrar fin de evento.";
		  return $rpta;
		}else{
		 $rpta= "OK";
		}
		$cmd=null;	
	}
	return $rpta;*/	  
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $params = array($this->ip_registro,
                        $this->empleado_codigo,  
                        $this->asistencia_codigo,
                        $this->evento_codigo );
        $ssql ="UPDATE ca_eventos ";
        $ssql .=" SET evento_hora_fin=getdate(), ";
        $ssql .="    fecha_reg_fin=getdate(), ";
        $ssql .="    ip_fin_evento=? ";
        $ssql .=" WHERE Empleado_Codigo=? ";
        $ssql .=" AND Asistencia_Codigo=? ";
        $ssql .=" AND Evento_Codigo=? ";
    
        $r=$cn->Execute($ssql,$params);
        if(!$r){
            $rpta = "Error al Registrar fin de evento.";
            return $rpta;
        }else{
            $rpta= "OK";
        }
        $cmd=null;	
    }
    return $rpta; 
 
 
 }
 
 
 
function listar_eventos_validables(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    
    $ssql=" SELECT --cast(CA_Eventos.horas as varchar)+':'+cast(CA_Eventos.minutos as varchar) as tiempo,
                (case when LEN(cast(CA_Eventos.horas as varchar)) =1 THEN '0' + cast(CA_Eventos.horas as varchar)	ELSE STR(CA_Eventos.horas) END
		          +':'+ LTRIM( case when LEN(cast(CA_Eventos.minutos as varchar)) = 1 then '0' + str(CA_Eventos.minutos) else STR(CA_Eventos.minutos) end) )as tiempo,
                CA_Eventos.observacion,
                case when CA_Eventos.num_ticket is null then '' else CA_Eventos.num_ticket end ticket,
                case when ca_incidencias.validable=1 then 'Por Persona' 
                else case when ca_incidencias.validable_mando=1 then 'Por Mando' 
                else 'Por Gerente' end end validador,
                ca_incidencias.incidencia_descripcion,
                ca_evento_estado.ee_descripcion,
            	CA_Eventos.Empleado_Codigo, 
            	CA_Eventos.Asistencia_codigo,
            	CA_Eventos.Incidencia_Codigo,
            	CA_Eventos.Evento_Codigo,
                isnull(Empleados.Empleado_Apellido_Paterno+' '+Empleados.Empleado_Apellido_Materno+' '+Empleados.Empleado_Nombres,'') as Supervisor_Inicia
                ,convert(varchar,CA_Eventos.fecha_reg_inicio,103)+' '+convert(varchar,CA_Eventos.fecha_reg_inicio,108) as Fecha_Registro
                ,CONVERT(VARCHAR,CA_Eventos.evento_hora_inicio,108) AS HORA_INICIO
                ,CONVERT(VARCHAR,CA_Eventos.evento_hora_fin,108) AS HORA_FIN
            FROM CA_asistencia_responsables 
                INNER JOIN CA_Eventos on CA_asistencia_responsables.empleado_codigo = CA_Eventos.empleado_codigo and CA_asistencia_responsables.asistencia_codigo = CA_Eventos.asistencia_codigo 
                INNER JOIN ca_incidencias on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo  and (ca_incidencias.validable = 1 or ca_incidencias.validable_mando=1 or ca_incidencias.validable_gerente = 1 ) 
                INNER JOIN ca_evento_estado on CA_Eventos.ee_codigo = ca_evento_estado.ee_codigo 
                LEFT JOIN Empleados ON CA_Eventos.codigo_supervisor_inicio = Empleados.Empleado_Codigo
            WHERE  CA_asistencia_responsables.responsable_codigo = ".$this->responsable_codigo."
                and CA_Eventos.empleado_codigo = ".$this->empleado_codigo." 
                and CA_Eventos.asistencia_codigo = ".$this->asistencia_codigo;
    //echo $ssql;
    
    if($cn){  
        $lista = "      <tr>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
        $lista .= "	         Tiempo";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	         Observacion";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	      Ticket";
        $lista .= "         </td>\n";
        
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
	$lista .= "	       Inicio";
	$lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
	$lista .= "	       Fin";
	$lista .= "         </td>\n";
        
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	       Validable por";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	       Evento";
        $lista .= "         </td>\n";
        
        
        
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	       Estado";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	       Usuario Registra";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	       Fecha Registro";
        $lista .= "         </td>\n";
        $lista .= "     </tr>\n";
        $rs= $cn->Execute($ssql);
        if(!$rs->EOF) {
            while(!$rs->EOF){
                $lista .="<tr >\n";
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[0]. "</b></td>\n";
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[1]. "</b></td>\n";
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[2]. "</b></td>\n";
                /*
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[3]. "</b></td>\n";
                */
                
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[12]."</b></td>\n";
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[13]."</b></td>\n";
                
                
                
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $this->Estado_Evento_Aprobacion($rs->fields[8],$rs->fields[6],$rs->fields[7],$rs->fields[9]). "</b></td>\n";
                
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[4]."</b></td>\n";
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[5]."</b></td>\n";
                
                
                
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[10]."</b></td>\n";
                
                $lista .="	   <td class='ca_datatd' align='left'>\n";
                $lista .="	   <b>" . $rs->fields[11]."</b></td>\n";
                
                $lista .="<tr >\n";
                $rs->MoveNext();
            }
        }
    }
    $rs=null;
    return $lista;
        
}
/*CREADO POR: BANNY SOLANO AREVALO  */
/*FECHA     : 17/10/2013            */
/*DESCRIPCION   : OBTIENE EL ESTADO DE APROBACION EN EL QUE SE ENCUENTRA
                EL EVENTO DEACUERDO A LA INCIDENCIA Y AL LOG            */
function Estado_Evento_Aprobacion($incidencia, $Empleado_Codigo, $Asistencia_Codigo, $Evento_Codigo){
    $cn = $this->getMyConexionADO();
    
    $params = array($Empleado_Codigo, $Asistencia_Codigo, $Evento_Codigo);
    $sql = "SELECT TOP 1 realizado 
            FROM ca_evento_log
            WHERE Empleado_Codigo = ? and Asistencia_codigo = ? and Evento_Codigo = ?
            ORDER BY el_codigo DESC";
    $rs = $cn->Execute($sql, $params);
    if($rs->RecordCount() > 0){
        $realizado = $rs->fields[0];
        $params = array($incidencia);
        
        $sql = "SELECT validable_mando, 
                        validable_gerente,
                        validable
                FROM CA_Incidencias 
                WHERE Incidencia_codigo = ?";
        $_rs = $cn->Execute($sql, $params);
        $estado = "Sin estado valido";
        switch($realizado){
            case 'S':
                if($_rs->fields[0]== 1){
                    $estado = "Mando";
                    return $estado;
                }
                if($_rs->fields[1]== 1){
                    $estado = "Gerente";
                    return $estado;
                }
                if($_rs->fields[2]== 1){
                    $estado = "Area de Apoyo";
                    return $estado;
                }
                return $estado;
                break;
            case 'M':
                $estado = "Mando";
                if($_rs->fields[1]== 1){
                    $estado = "Gerente";
                    return $estado;
                }
                if($_rs->fields[2]== 1){
                    $estado = "Area de Apoyo";
                    return $estado;
                }
                break;
            case 'G':
                $estado = "Gerente";
                if($_rs->fields[2]== 1){
                    $estado = "Area de Apoyo";
                    return $estado;
                }
                break;
            case 'V':
                $estado = "Area de Apoyo";
                return $estado;
                break;
        }
        
    }
    return $estado;
} 
 
 
 
 
function saldo_refrigerio(){
    $codigo=0;
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
    	$ssql="SELECT datediff(minute,evento_hora_inicio,evento_hora_fin),";
		$ssql .=" convert(varchar(10),evento_hora_fin,103) + ' ' + convert(varchar(8),evento_hora_fin,108), ";
		$ssql .=" convert(varchar(10),dateadd(minute,45,evento_hora_inicio),103) + ' ' + convert(varchar(8),dateadd(minute,45,evento_hora_inicio),108) ";
		$ssql .=" FROM ca_eventos ";
		$ssql .=" WHERE empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
		$ssql .=" and Evento_Codigo=" . $this->evento_codigo;
		$rs= $cn->Execute($ssql);  	
		if (!$rs->EOF){
	  	  $this->saldo=45 - $rs->fields[0];
	  	  $this->evento_hora_inicio=$rs->fields[1];
	  	  $this->evento_hora_fin=$rs->fields[2];
	    }else{
		    $rpta="nOK";
	   }
    }
    return $rpta;
/*    $codigo=0;
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    	$ssql="select datediff(minute,evento_hora_inicio,evento_hora_fin),";
		$ssql .=" convert(varchar(10),evento_hora_fin,103) + ' ' + convert(varchar(8),evento_hora_fin,108), ";
		$ssql .=" convert(varchar(10),dateadd(minute,45,evento_hora_inicio),103) + ' ' + convert(varchar(8),dateadd(minute,45,evento_hora_inicio),108) ";
		$ssql .=" from ca_eventos ";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
		$ssql .=" and Evento_Codigo=" . $this->evento_codigo;
		$rs= $this->cnnado->Execute($ssql);  	
		if (!$rs->EOF){
	  	  $this->saldo=45 - $rs->fields[0]->value;
	  	  $this->evento_hora_inicio=$rs->fields[1]->value;
	  	  $this->evento_hora_fin=$rs->fields[2]->value;
	    }else{
		    $rpta="nOK";
	   }
	   $rs->close();
	   $rs=null;
    }
return $rpta;
*/
}

function registrar_evento_completo(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        // crea un nuevo registro
        $ssql="SELECT isnull(max(evento_codigo),0)+1 id FROM ca_eventos ";
        $ssql .=" WHERE empleado_codigo=" . $this->empleado_codigo . " AND asistencia_codigo=" . $this->asistencia_codigo;
        $rs= $cn->Execute($ssql);
        $this->evento_codigo = $rs->fields[0];
        
        $sql =" INSERT INTO ca_eventos ";
        $sql .=" (Empleado_codigo, Asistencia_codigo,Evento_Codigo,Incidencia_codigo,";
        $sql .=" evento_hora_inicio,fecha_reg_inicio,evento_hora_fin,fecha_reg_fin,evento_activo) ";
        $sql .=" values(" . $this->empleado_codigo . ",";
        $sql .=" " . $this->asistencia_codigo . ",";
        $sql .=" " . $this->evento_codigo . ",";
        $sql .=" " . $this->incidencia_codigo . ",";
        $sql .=" convert(datetime,'" . $this->evento_hora_inicio . "',103), ";
        $sql .=" convert(datetime,'" . $this->evento_hora_inicio . "',103), ";
        $sql .=" convert(datetime,'" . $this->evento_hora_fin . "',103), ";
        $sql .=" convert(datetime,'" . $this->evento_hora_fin . "',103),1)";
        $r=$cn->Execute($sql);
        if(!$r){
            $rpta = "Error al Insertar Evento.";
            return $rpta;
        }else{
            $rpta= "OK";
        }
    }
    return $rpta;
 /* $rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
	 // crea un nuevo registro
	    $ssql="select isnull(max(evento_codigo),0)+1 id from ca_eventos ";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
		$rs= $this->cnnado->Execute($ssql);
		$this->evento_codigo = $rs->fields[0]->value;
		
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	    $sql =" insert into ca_eventos ";
	    $sql .=" (Empleado_codigo, Asistencia_codigo,Evento_Codigo,Incidencia_codigo,";
		$sql .=" evento_hora_inicio,fecha_reg_inicio,evento_hora_fin,fecha_reg_fin,evento_activo) ";
	    $sql .=" values(" . $this->empleado_codigo . ",";
		$sql .=" " . $this->asistencia_codigo . ",";
		$sql .=" " . $this->evento_codigo . ",";
		$sql .=" " . $this->incidencia_codigo . ",";
		$sql .=" convert(datetime,'" . $this->evento_hora_inicio . "',103), ";
		$sql .=" convert(datetime,'" . $this->evento_hora_inicio . "',103), ";
		$sql .=" convert(datetime,'" . $this->evento_hora_fin . "',103), ";
		$sql .=" convert(datetime,'" . $this->evento_hora_fin . "',103),1)";
		
		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $sql;
		$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Insertar Evento.";
		  return $rpta;
		}else{
		 $rpta= "OK";
		}
		$rs->close();
		$rs=null;
		$cmd=null;
	}
	  return $rpta;*/
}
  
function validar_inicio(){
    /*$codigo=0;
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    $ssql="SELECT evento_codigo, convert(varchar(8),evento_hora_inicio, 108) as entrada, ";
	$ssql .="  convert(varchar(10),evento_hora_inicio, 103) + ' ' + convert(varchar(8),evento_hora_inicio, 108) as evento_inicio, ";
	$ssql .=" convert(varchar(5),evento_hora_fin, 108) as salida,ca_eventos.incidencia_codigo,ca_incidencias.incidencia_descripcion ";
	$ssql .=" FROM ca_eventos inner join ca_incidencias on ca_incidencias.incidencia_codigo=ca_eventos.incidencia_codigo ";
	$ssql .=" WHERE (Empleado_Codigo = " . $this->empleado_codigo  . ") and asistencia_codigo=" . $this->asistencia_codigo . " AND evento_hora_fin is null AND ";
	$ssql .="  fecha_reg_inicio=(select max(fecha_reg_inicio) from ca_eventos ";
	$ssql .="  where empleado_codigo=". $this->empleado_codigo  ." and asistencia_codigo=" . $this->asistencia_codigo . ")";

    //echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
		//if($rs) echo "Error";
		if (!$rs->EOF){
			$this->evento_codigo=$rs->fields[0]->value;//asistencia_codigo
			$this->evento_hora_inicio = $rs->fields[1]->value;// entrada
			$this->evento_inicio = $rs->fields[2]->value;// entrada
			$this->incidencia_codigo = $rs->fields[4]->value;// entrada
			$this->incidencia=$rs->fields[5]->value;//turno_descripcion
	  }else{
		   $this->evento_codigo=0;
	  } 
	$codigo=$this->evento_codigo;
	
	$rs->close();
	$rs=null;
 }
return $codigo;*/
    
    
    
    $codigo=0;
    $rpta="OK";
    $cn=$this->getMyConexionADO();
	
    $ssql="SELECT evento_codigo, convert(varchar(8),evento_hora_inicio, 108) as entrada, ";
    $ssql .="  convert(varchar(10),evento_hora_inicio, 103) + ' ' + convert(varchar(8),evento_hora_inicio, 108) as evento_inicio, ";
    $ssql .=" convert(varchar(5),evento_hora_fin, 108) as salida,ca_eventos.incidencia_codigo,ca_incidencias.incidencia_descripcion ";
    $ssql .=" FROM ca_eventos inner join ca_incidencias on ca_incidencias.incidencia_codigo=ca_eventos.incidencia_codigo ";
    $ssql .=" WHERE (Empleado_Codigo = " . $this->empleado_codigo  . ") and asistencia_codigo=" . $this->asistencia_codigo . " AND evento_hora_fin is null AND ";
    $ssql .="  fecha_reg_inicio=(select max(fecha_reg_inicio) from ca_eventos ";
    $ssql .="  where empleado_codigo=". $this->empleado_codigo  ." and asistencia_codigo=" . $this->asistencia_codigo . ")";

    $rs=$cn->Execute($ssql);	
    if ($rs->RecordCount()>0){
        $this->evento_codigo=$rs->fields[0];//asistencia_codigo
        $this->evento_hora_inicio = $rs->fields[1];// entrada
        $this->evento_inicio = $rs->fields[2];// entrada
        $this->incidencia_codigo = $rs->fields[4];// entrada
        $this->incidencia=$rs->fields[5];//turno_descripcion
    }else{
        $this->evento_codigo=0;
    } 
    
    $codigo=$this->evento_codigo;
    $rs->close();
    $rs=null;
 
    return $codigo;
    
}

function validar_i(){
    /*$codigo=0;
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    	$ssql="select convert(varchar(8),evento_hora_inicio,108) as inicio from ca_eventos ";
		$ssql .=" where (Empleado_Codigo = " . $this->empleado_codigo  . ")";
		$ssql .=" and (asistencia_codigo=" . $this->asistencia_codigo . ")";
		$ssql .=" and evento_hora_fin is null ";
		$ssql .=" and fecha_reg_inicio=(select max(fecha_reg_inicio) from ca_eventos ";
	    $ssql .=" where empleado_codigo=". $this->empleado_codigo  ." and asistencia_codigo=" . $this->asistencia_codigo . ")";

	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$rpta="OK";
			$this->e_inicio=$rs->fields[0]->value;
	  }else{
		    $rpta="nOK";
	  }
	  $rs->close();
	  $rs=null;
    }
return $rpta;*/
    
    $codigo=0;
    $rpta="OK";
    $cn=$this->getMyConexionADO();
	
    $ssql="select convert(varchar(8),evento_hora_inicio,108) as inicio from ca_eventos ";
    $ssql .=" where (Empleado_Codigo = " . $this->empleado_codigo  . ")";
    $ssql .=" and (asistencia_codigo=" . $this->asistencia_codigo . ")";
    $ssql .=" and evento_hora_fin is null ";
    $ssql .=" and fecha_reg_inicio=(select max(fecha_reg_inicio) from ca_eventos ";
    $ssql .=" where empleado_codigo=". $this->empleado_codigo  ." and asistencia_codigo=" . $this->asistencia_codigo . ")";

    $rs = $cn->Execute($ssql);
    if ($rs->RecordCount()>0){
        $rpta="OK";
        $this->e_inicio=$rs->fields[0];
    }else{
        $rpta="nOK";
    }
    
    $rs->close();
    $rs=null;
    
    return $rpta;

}

function validar_f(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    $ssql="SELECT convert(varchar(8),evento_hora_fin,108) as fin ";
	$ssql .=" from ca_eventos  ";
	$ssql .=" where empleado_codigo=" . $this->empleado_codigo. " and asistencia_codigo=" . $this->asistencia_codigo;
	$ssql .=" and evento_codigo=" . $this->evento_codigo;
	$ssql .=" and evento_hora_fin is not null";
	$rs = $this->cnnado->Execute($ssql);
	if (!$rs->EOF){
			$rpta="OK";
			$this->e_fin=$rs->fields[0]->value;
	}else{
		   $rpta="nOK";
	}
	$rs->close();
	$rs=null;

	}
	return $rpta;*/
    $rpta="OK";
    $cn=$this->getMyConexionADO();
	
    $ssql="SELECT convert(varchar(8),evento_hora_fin,108) as fin ";
    $ssql .=" from ca_eventos  ";
    $ssql .=" where empleado_codigo=" . $this->empleado_codigo. " and asistencia_codigo=" . $this->asistencia_codigo;
    $ssql .=" and evento_codigo=" . $this->evento_codigo;
    $ssql .=" and evento_hora_fin is not null";
    
    $rs = $cn->Execute($ssql);
    
    if ($rs->RecordCount()>0){
        $rpta="OK";
        $this->e_fin=$rs->fields[0];
    }else{
       $rpta="nOK";
    }
    $rs->close();
    $rs=null;

    return $rpta;
  } 

function listado_eventos(){

    
    /*$cadena="";
$ssql="";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){ 
	$ssql="SELECT incidencia_descripcion,convert(varchar(8),evento_hora_inicio, 108) as entrada, convert(varchar(8),evento_hora_fin, 108) as salida,incidencia_icono ";
	$ssql.=  " FROM ca_eventos inner join ca_incidencias on ca_incidencias.incidencia_codigo=ca_eventos.incidencia_codigo ";
	$ssql.=  " WHERE (Empleado_Codigo = " . $this->empleado_codigo . ") AND Asistencia_codigo=" . $this->asistencia_codigo;
	//$ssql.=  " AND evento_hora_fin is not null ";
	$ssql.=  " Order by 2 desc ";
	$rs = $this->cnnado->Execute($ssql);
	if(!$rs->EOF()) {
	  while(!$rs->EOF()){
		$cadena .= "<tr>\n";
		$cadena .="<td class=dataTd align='left'>\n";
		if($rs->fields[3]->value !=""){
			$cadena .="     <img  src='../Images/" . $rs->fields[3]->value . "' width='15' height='15' border='0'>";
		}else{
			$cadena .="     <img  src='../Images/stop_hand.png' width='15' height='15' border='0'>";
		}
		//$cadena .="	 <img  src='../Images/" . $rs->fields[3]->value. "' width='15' height='15' border='0'>\n";
		$cadena .= "". $rs->fields[0]->value .  " </td>\n";
		$cadena .="<td class=dataTd align='center'>\n";
		$cadena .= "&nbsp;" . $rs->fields[1]->value .  " </td>\n";
		$cadena .="<td class=dataTd align='center'>\n";
		$cadena .= "&nbsp;" . $rs->fields[2]->value .  " </td>\n";
		$cadena .= "</tr>\n";
	    $rs->movenext();
	  }
	}
  $rs->close();
  $rs=null;
 }	
	return $cadena;*/
        
    
    $cadena="";
    $ssql="";
    $cn=$this->getMyConexionADO();
    
    $ssql="SELECT incidencia_descripcion,convert(varchar(8),evento_hora_inicio, 108) as entrada, convert(varchar(8),evento_hora_fin, 108) as salida,incidencia_icono ";
    $ssql.=  " FROM ca_eventos inner join ca_incidencias on ca_incidencias.incidencia_codigo=ca_eventos.incidencia_codigo ";
    $ssql.=  " WHERE (Empleado_Codigo = " . $this->empleado_codigo . ") AND Asistencia_codigo=" . $this->asistencia_codigo;
    $ssql.=  " Order by 2 desc ";
    
    $rs = $cn->Execute($ssql);
    if($rs->RecordCount()>0) {
      while(!$rs->EOF){
        $cadena .="<tr>";
        $cadena .="<td class=dataTd align='left'>";
        if($rs->fields[3]!=""){
            $cadena .="<img  src='../Images/" . $rs->fields[3] . "' width='15' height='15' border='0'>";
        }else{
            $cadena .="<img  src='../Images/stop_hand.png' width='15' height='15' border='0'>";
        }
        
        $cadena .= "".$rs->fields[0]." </td>";
        $cadena .="<td class=dataTd align='center'>";
        $cadena .="&nbsp;" . $rs->fields[1]." </td>";
        $cadena .="<td class=dataTd align='center'>";
        $cadena .="&nbsp;" . $rs->fields[2]."</td>";
        $cadena .="</tr>\n";
        $rs->MoveNext();
      }
    }
    
    $rs->close();
    $rs=null;

    return $cadena;
        
}

function getListaSupervidores($area,$id,$asistencia_codigo){
    $ssql="";
    $cn=$this->getMyConexionADO();
    
    $sql="select responsable_codigo, Responsable,  ";
    $sql.=" case when area_codigo=". $area ." then 1 else 2 end as tipo_area ";
    $sql.=  " from vca_asistencia_responsables(nolock) where empleado_codigo=" . $id . " and asistencia_codigo =" . $asistencia_codigo;
    $sql.=  " Order by 2 ";
    
    $rsc=$cn->Execute($ssql);
    if($rsc->RecordCount()>0){
        while (!$rsc->EOF){
            echo "<option value='" .$rsc->fields[0]."_".$rsc->fields[2]."'>".$rsc->fields[1]."</option>";
            $rsc->MoveNext();
        }
					
    }
    
    
}

function listado_eventos_empleados_dia($area){
    
   
    
    
    $cadena="";
    $i=0;
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $combo = new MyCombo();
    $combo->setMyUrl($this->getMyUrl());
    $combo->setMyUser( $this->getMyUser());
    $combo->setMyPwd( $this->getMyPwd());
    $combo->setMyDBName($this->getMyDBName());

    $ssql ="select ca_eventos.asistencia_codigo,CA_Eventos.evento_codigo, ";
    $ssql .= " ca_eventos.empleado_codigo,vca_empleado_area.empleado,incidencia_descripcion,convert(varchar(8),evento_hora_inicio, 108) as entrada,convert(varchar(8),evento_hora_fin, 108) as fin,  ";
    $ssql .= " datediff(minute,evento_hora_inicio,evento_hora_fin)  as tiempo,ca_eventos.incidencia_codigo, ";
    $ssql .= "   convert(varchar(10), evento_hora_inicio,103)  + ' ' +  convert(varchar(8),evento_hora_inicio, 108), ";
    $ssql .= "   convert(varchar(10), evento_hora_fin ,103)  +  ' '  +  convert(varchar(8),evento_hora_fin, 108),empleado_servicio.cod_campana, ";
    $ssql .= "  case when len(cast((  (datediff(minute,evento_hora_inicio,evento_hora_fin))   /60) as int) )=1 then '0' + cast(cast((   (datediff(minute,evento_hora_inicio,evento_hora_fin))   /60) as int) as char) ";
    $ssql .= " else cast(cast((   (datediff(minute,evento_hora_inicio,evento_hora_fin))/60) as int) as char) end as horas, ";
    $ssql .= " case when len(datediff(minute,evento_hora_inicio,evento_hora_fin) - ( ";
    $ssql .= " cast((   (datediff(minute,evento_hora_inicio,evento_hora_fin))   /60) as int)*60 ";
    $ssql .= " ))=1 then '0' + cast(  datediff(minute,evento_hora_inicio,evento_hora_fin) - ( ";
    $ssql .= " cast((   (datediff(minute,evento_hora_inicio,evento_hora_fin))   /60) as int)*60 ";
    $ssql .= " )as char) ";
    $ssql .= " else cast( datediff(minute,evento_hora_inicio,evento_hora_fin) - ( ";
    $ssql .= " cast((   (datediff(minute,evento_hora_inicio,evento_hora_fin))/60) as int)*60 ";
    $ssql .= " ) as char) end as minutos	";
    $ssql .=  " from ca_eventos ";
    $ssql .=  "	INNER JOIN CA_Incidencias ON ";
    $ssql .=  " CA_Eventos.Incidencia_codigo = CA_Incidencias.Incidencia_codigo ";
    $ssql .=  " inner join vca_empleado_area on vca_empleado_area.empleado_codigo=ca_eventos.empleado_codigo ";
    $ssql .=  " inner join ca_asistencias on ca_asistencias.empleado_codigo=ca_eventos.empleado_codigo ";
    $ssql .=  "                      and ca_asistencias.asistencia_codigo=ca_eventos.asistencia_codigo ";
    $ssql .=  " inner join ca_asistencia_responsables ";
    $ssql .=  "                      on ca_asistencia_responsables.empleado_codigo=ca_asistencias.empleado_codigo ";
    $ssql .=  "                      and ca_asistencia_responsables.asistencia_codigo=ca_asistencias.asistencia_codigo ";
    $ssql .=  " left join empleado_servicio ";
    $ssql .=  "                      on ca_eventos.empleado_codigo=empleado_servicio.empleado_codigo ";
    $ssql .=  "                      and empleado_servicio.empleado_servicio_activo=1 ";	
    $ssql .=  " where ca_asistencias.asistencia_fecha=convert(datetime,'" . $this->fecha . "',103) ";
    if($this->incidencia_codigo!=0) $ssql .=  "   and CA_Eventos.incidencia_codigo=" . $this->incidencia_codigo;
    $ssql .=  "   and ca_asistencia_responsables.responsable_codigo=" . $this->responsable_codigo;
    $ssql .=  "   and CA_Eventos.Evento_Hora_Fin is not null and ca_eventos.evento_activo=1 ";
    $ssql .=  "   order by ca_eventos.evento_codigo ";
    //echo $ssql;
    
    $rs = $cn->Execute($ssql);
        
    if(!$rs->EOF) {
        while (!$rs->EOF){
            $i++;
            $cadena .= "<TR class='DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $cadena .="<TD align='center'>\n"; 
            $cadena .="" . $i . " <INPUT type=CHECKBOX align=center id='chk' name='chk' value='". $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' onclick='check()'>";
            $cadena .="</TD>\n";
            $cadena .="<TD >" . $rs->fields[2]; 
            $cadena .="</TD>\n";
            $cadena .="<TD >" . $rs->fields[3]; 
            $cadena .="</TD>\n";
            $cadena .="<TD "; 
            //#FFCC00
            if($rs->fields[8]==41){
                $cadena .=" >" . $rs->fields[4] ."&nbsp;&nbsp;";	
                $cadena .="<select id='tiempo_derg_". $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] ."' name='tiempo_derg_". $rs->fields[2] . "_".$rs->fields[0]."_".$rs->fields[1]."' class='select'  style='width:155px;background:#F4DBA6'>";
                $cadena .="  <option value='0'>Solo</option>";
                $cadena .="  <option value='15'>+ Desc.Ergo 15 Minutos</option>";
                $cadena .="  <option value='30'> + Desc.Ergo 30 Minutos</option>";
                $cadena .="</select>";
            }else{
                $cadena .=">" . $rs->fields[4] ."&nbsp";    	
            }
            $cadena .="</TD>\n";
            $cadena .="<TD  align='center'>".$rs->fields[5]; 
            $cadena .="</TD>\n";
            $cadena .="<TD  align='center'>".$rs->fields[6];
            $cadena .="</TD>\n";
            $cadena .="<td align='center'>\n";
            $cadena .= $rs->fields[12] .':'.$rs->fields[13];
            $cadena .="	   </td>\n";
            $cadena .="<TD  align='left'>\n";
            if ($rs->fields[11] !="null"){
                $ssql="select exp_codigo + '-' + exp_nombrecorto + ' (' + convert(varchar,cod_campana) + ')' as campana ";
                $ssql.=" from v_campanas where cod_campana=" . $rs->fields[11];
                $rss = $cn->Execute($ssql);
                if (!$rss->EOF){
                    $servicio_nombre= $rss->fields[0];
                    $servicio=$rs->fields[11];
                }
            }else{
                $servicio='';
            }
					
            $cadena .="\n	<input class=input type=text size=25 id='txt_cod_campana_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' name='txt_cod_campana_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' value='" . $servicio_nombre . "' style='width:180px'>";
            $cadena .="\n	<input type=hidden id='cod_campana_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' name='cod_campana_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' value='" . $servicio . "'>";
            $cadena .="\n	<img src='../images/buscaroff.gif' onclick='javascript: buscar_servicio(\"" . $rs->fields[2] . "\",\"" . $rs->fields[0] . "\",\"" . $rs->fields[1] . "\")'  style='cursor:hand' title='Buscar Unidad de Servicio del Área'>";
            $cadena .="\n	</td>\n";
            $cadena .="    </tr>\n";			
            $cadena .="\n	  <input type='hidden' id='incidencia_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' name='incidencia_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] ."' value='" . $rs->fields[8] . "'>\n";
            $cadena .="\n	  <input type='hidden' id='tiempo_"  . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' name='tiempo_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' value='" . $rs->fields[7] . "'>\n";
            $cadena .="\n	  <input type='hidden' id='hora_inicio_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' name='hora_inicio_" . $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' value='" . $rs->fields[9] . "'>\n";
            $cadena .="\n	  <input type='hidden' id='hora_fin_". $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' name='hora_fin_". $rs->fields[2] . "_" . $rs->fields[0] . "_" . $rs->fields[1] . "' value='" . $rs->fields[10] . "'>\n";

            $rs->MoveNext();
        }
    }else{
        $cadena .="<tr >\n";
        $cadena .="   <TD class='ca_datatd' align='center' colspan='8'>\n"; 
        $cadena .="   <b>No existen registros en este momento!!</b>";
        $cadena .="    </TD>\n";
        $cadena .= "</tr>\n";
    }

    $rs->close();
    $rs=null;
    $combo=null;
    return $cadena;
}

function Desactivar_evento(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
 $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	$ssql =" Update ca_eventos set evento_activo=0 ";
	$ssql .=" where Empleado_Codigo=? ";
	$ssql .=" and Asistencia_Codigo=? ";
	$ssql .=" and Evento_Codigo=? ";
	$ssql .=" and evento_activo=1 ";
	    $cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
    	$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->asistencia_codigo;
		$cmd->Parameters[2]->value = $this->evento_codigo;
		$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Desactivar Evento.";
		  return $rpta;
		}else{
		 $rpta= "OK";
		}
		$cmd=null;	
}		 	  
	return $rpta;*/
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        //$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
        $params = array($this->empleado_codigo,
                        $this->asistencia_codigo, 
                        $this->evento_codigo  
                        );
        $ssql =" UPDATE ca_eventos SET evento_activo=0 ";
        $ssql .=" WHERE Empleado_Codigo=? ";
        $ssql .=" and Asistencia_Codigo=? ";
        $ssql .=" and Evento_Codigo=? ";
        $ssql .=" and evento_activo=1 ";
        
        $r=$cn->Execute($ssql,$params);
        if(!$r){
            $rpta = "Error al Desactivar Evento.";
            return $rpta;
        }else{
            $rpta= "OK";
        }
    }		 	  
    return $rpta;    
    
}

function Listar_eventos_asistencia($area,$servicio){
    
/*
$rpta="OK";
$rpta=$this->conectarme_ado();
$combo = new MyCombo();
$combo->setMyUrl($this->getMyUrl());
$combo->setMyUser( $this->getMyUser());
$combo->setMyPwd( $this->getMyPwd());
$combo->setMyDBName($this->getMyDBName());

$servicio_nombre='';

if($rpta=="OK"){			
    
	$ssql="SELECT CA_Eventos.Empleado_Codigo, ";
    $ssql .="	CA_Eventos.Asistencia_codigo, ";
    $ssql .="	CA_Eventos.Incidencia_codigo, ";
    $ssql .="	CA_Incidencias.Incidencia_descripcion AS incidencia, "; 
	$ssql .="	CA_incidencias.incidencia_icono ,";
	$ssql .="	convert(varchar(8),evento_hora_inicio, 108) as inicio, ";
    $ssql .="	convert(varchar(8),evento_hora_fin, 108) as fin ,";
	$ssql .="	datediff(minute,evento_hora_inicio,evento_hora_fin)  as tiempo, ";
	$ssql .="	CA_Eventos.Evento_codigo, ";
	$ssql .="   convert(varchar(10), evento_hora_inicio,103)  + ' ' +  convert(varchar(8),evento_hora_inicio, 108), ";
	$ssql .="   convert(varchar(10), evento_hora_fin ,103)  +  ' '  +  convert(varchar(8),evento_hora_fin, 108),";
	$ssql .="   case when exists(select responsable_codigo from ca_asistencia_responsables ";
    $ssql .="   				 where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
	$ssql .="   				 and responsable_codigo=" . $this->responsable_codigo . ") then 1 else 0 end as activo";
	$ssql .="   FROM CA_Eventos ";
    $ssql .="	INNER JOIN CA_Incidencias ON ";
    $ssql .="   CA_Eventos.Incidencia_codigo = CA_Incidencias.Incidencia_codigo ";
	$ssql .="   WHERE (CA_Eventos.empleado_Codigo = " . $this->empleado_codigo .") and ";
	$ssql .="   (CA_Eventos.Asistencia_Codigo = " . $this->asistencia_codigo .") and ca_eventos.evento_activo=1";
	$ssql .="   and CA_Eventos.Evento_Hora_Fin is not null";
	$ssql .="   Order by 3";
    //echo $ssql;
    	$lista = "      <tr>\n";
		$lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
		$lista .= "	         Evento";
		$lista .= "         </td>\n";
		$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
		$lista .= "	         Inicio";
		$lista .= "         </td>\n";
		$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
		$lista .= "	     Fin ";
		$lista .= "         </td>\n";
		$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
		$lista .= "	      Servicio";
		$lista .= "         </td>\n";
		$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
		$lista .= "	      Aprobar";
		$lista .= "         </td>\n";
		$lista .= "     </tr>\n";
	$rs = $this->cnnado->Execute($ssql);
	//--- obtener nombre de Unidad de servicio Asignado
	if ($servicio!=0){
		$ssql="select exp_codigo + '-' + exp_nombrecorto + ' (' + convert(varchar,cod_campana) + ')' as campana ";
		$ssql.=" from v_campanas where cod_campana=" . $servicio;
		//echo $ssql;
		$rss = $this->cnnado->Execute($ssql);
		if (!$rss->EOF()){
			$servicio_nombre= $rss->fields[0]->value;
		}
		$rss->close();
		$rss=null;
		
	}else{
		$servicio='';
	}
	
	if(!$rs->EOF()) {
		while(!$rs->EOF()){
		
		    $lista .="<tr >\n";
			$lista .="	   <td class='ca_datatd' align='left'>\n";
			if($rs->fields[4]->value!=""){
				$lista .="     <img  src='../Images/" . $rs->fields[4]->value. "' width='15' height='15' border='0'>";
			}else{
			    $lista .="     <img  src='../Images/stop_hand.png' width='15' height='15' border='0'>";
			}
			$lista .="	   <b>" . $rs->fields[3]->value . "</b></td>\n";
			$lista .="	   <td class='CA_DataTD'>\n";
			$lista .=" " . $rs->fields[5]->value. "\n";
			$lista .="	   </td>\n";
			$lista .="	   <td class='CA_DataTD'>\n";
			$lista .=" " . $rs->fields[6]->value. "\n";
			$lista .="	   </td>\n";
			$lista .="	   <td class='CA_DataTD' nowrap>\n";
		    $lista .="\n		<input class=input type=text size=25 id='txt_cod_campana_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' name='txt_cod_campana_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' value='" . $servicio_nombre . "'>";
			$lista .="\n		<input type=hidden id='cod_campana_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' name='cod_campana_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' value='" . $servicio . "'>";
			$lista .="\n		<img src='../images/buscaroff.gif' onclick='javascript: buscar_servicio(\"" . $rs->fields[1]->value . "\",\"" . $rs->fields[8]->value . "\")'  style='cursor:hand' title='Buscar Unidad de Servicio del Área'>";
			$lista .="\n	   </td>\n";
				
				if($rs->fields[11]->value==1){
					$lista .="<td align='center' class='ca_datatd' ><img  src='../Images/stock_mark.png' onclick='aprobar_evento(" . $rs->fields[1]->value . "," . $rs->fields[2]->value . "," . $rs->fields[8]->value .")' width='15' height='15' border='0' style='cursor:hand;' alt='Aprobar'></td>\n";
				}else{
					$lista .="<td align='center' class='ca_datatd' >&nbsp;</td>\n";
				}
			$lista .="	  <input type='hidden' id='tiempo_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' name='tiempo_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' value='" . $rs->fields[7]->value . "'>\n";
			$lista .="	  <input type='hidden' id='hora_inicio_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' name='hora_inicio_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' value='" . $rs->fields[9]->value . "'>\n";
			$lista .="	  <input type='hidden' id='hora_fin_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' name='hora_fin_" . $rs->fields[1]->value . "_" . $rs->fields[8]->value . "' value='" . $rs->fields[10]->value . "'>\n";
			$lista .= "</tr>\n";
			
			$rs->movenext();
		}
	}
	$rs->close();
	$rs=null;

	$combo=null;
	
}	
return $lista;
*/

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $combo = new MyCombo();
    $combo->setMyUrl($this->getMyUrl());
    $combo->setMyUser( $this->getMyUser());
    $combo->setMyPwd( $this->getMyPwd());
    $combo->setMyDBName($this->getMyDBName());
    $servicio_nombre='';

    $ssql="SELECT CA_Eventos.Empleado_Codigo, ";
    $ssql .="	CA_Eventos.Asistencia_codigo, ";
    $ssql .="	CA_Eventos.Incidencia_codigo, ";
    $ssql .="	CA_Incidencias.Incidencia_descripcion AS incidencia, "; 
    $ssql .="	CA_incidencias.incidencia_icono ,";
    $ssql .="	convert(varchar(8),evento_hora_inicio, 108) as inicio, ";
    $ssql .="	convert(varchar(8),evento_hora_fin, 108) as fin ,";
    $ssql .="	datediff(minute,evento_hora_inicio,evento_hora_fin)  as tiempo, ";
    $ssql .="	CA_Eventos.Evento_codigo, ";
    $ssql .="   convert(varchar(10), evento_hora_inicio,103)  + ' ' +  convert(varchar(8),evento_hora_inicio, 108), ";
    $ssql .="   convert(varchar(10), evento_hora_fin ,103)  +  ' '  +  convert(varchar(8),evento_hora_fin, 108),";
    $ssql .="   case when exists(select responsable_codigo from ca_asistencia_responsables ";
    $ssql .="   				 where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
    $ssql .="   				 and responsable_codigo=" . $this->responsable_codigo . ") then 1 else 0 end as activo ,CA_incidencias.validable";
    $ssql .="   FROM CA_Eventos ";
    $ssql .="	INNER JOIN CA_Incidencias ON ";
    $ssql .="   CA_Eventos.Incidencia_codigo = CA_Incidencias.Incidencia_codigo ";
    $ssql .="   WHERE (CA_Eventos.empleado_Codigo = " . $this->empleado_codigo .") and ";
    $ssql .="   (CA_Eventos.Asistencia_Codigo = " . $this->asistencia_codigo .") and ca_eventos.evento_activo=1";
    $ssql .="   and CA_Eventos.Evento_Hora_Fin is not null";
    $ssql .="   Order by 3";
    
    $lista = "      <tr>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
    $lista .= "	         Evento";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	         Inicio";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	     Fin ";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	      Servicio";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	      Aprobar";
    $lista .= "         </td>\n";
    $lista .= "     </tr>\n";
    
    $rs = $cn->Execute($ssql);
    //--- obtener nombre de Unidad de servicio Asignado
    if ($servicio!=0){
        $ssql="select exp_codigo + '-' + exp_nombrecorto + ' (' + convert(varchar,cod_campana) + ')' as campana ";
        $ssql.=" from v_campanas where cod_campana=" . $servicio;
        //echo $ssql;
        $rss = $cn->Execute($ssql);
        if (!$rss->EOF){
            $servicio_nombre= $rss->fields[0];
        }
        $rss->close();
        $rss=null;
    }else{
        $servicio='';
    }
	
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $lista .="<tr >\n";
            $lista .="	   <td class='ca_datatd' align='left'>\n";
            if($rs->fields[4]!=""){
                $lista .="     <img  src='../Images/" . $rs->fields[4]. "' width='15' height='15' border='0'>";
            }else{
                $lista .="     <img  src='../Images/stop_hand.png' width='15' height='15' border='0'>";
            }
            $lista .="	   <b>" . $rs->fields[3] . "</b></td>\n";
            $lista .="	   <td class='CA_DataTD'>\n";
            $lista .=" " . $rs->fields[5]. "\n";
            $lista .="	   </td>\n";
            $lista .="	   <td class='CA_DataTD'>\n";
            $lista .=" " . $rs->fields[6]. "\n";
            $lista .="	   </td>\n";
            $lista .="	   <td class='CA_DataTD' nowrap>\n";
            $lista .="\n		<input class=input type=text size=25 id='txt_cod_campana_" . $rs->fields[1] . "_" . $rs->fields[8] . "' name='txt_cod_campana_" . $rs->fields[1] . "_" . $rs->fields[8] . "' value='" . $servicio_nombre . "'>";
            $lista .="\n		<input type=hidden id='cod_campana_" . $rs->fields[1] . "_" . $rs->fields[8] . "' name='cod_campana_" . $rs->fields[1] . "_" . $rs->fields[8] . "' value='" . $servicio . "'>";
            $lista .="\n		<img src='../images/buscaroff.gif' onclick='javascript: buscar_servicio(\"" . $rs->fields[1] . "\",\"" . $rs->fields[8] . "\")'  style='cursor:hand' title='Buscar Unidad de Servicio del Área'>";
            $lista .="\n	   </td>\n";

            if($rs->fields[11]==1){
                if($rs->fields[12]!=1){
                    $lista .="<td align='center' class='ca_datatd' ><img  src='../Images/stock_mark.png' onclick='aprobar_evento(" . $rs->fields[1] . "," . $rs->fields[2] . "," . $rs->fields[8] .")' width='15' height='15' border='0' style='cursor:hand;' alt='Aprobar'></td>\n";
                }
            }else{
                $lista .="<td align='center' class='ca_datatd' >&nbsp;</td>\n";
            }
            
            $lista .="	  <input type='hidden' id='tiempo_" . $rs->fields[1] . "_" . $rs->fields[8] . "' name='tiempo_" . $rs->fields[1] . "_" . $rs->fields[8] . "' value='" . $rs->fields[7] . "'>\n";
            $lista .="	  <input type='hidden' id='hora_inicio_" . $rs->fields[1] . "_" . $rs->fields[8] . "' name='hora_inicio_" . $rs->fields[1] . "_" . $rs->fields[8] . "' value='" . $rs->fields[9] . "'>\n";
            $lista .="	  <input type='hidden' id='hora_fin_" . $rs->fields[1] . "_" . $rs->fields[8] . "' name='hora_fin_" . $rs->fields[1] . "_" . $rs->fields[8] . "' value='" . $rs->fields[10] . "'>\n";
            $lista .= "</tr>\n";

            $rs->MoveNext();
        }
    }
    $rs->close();
    $rs=null;
    $combo=null;
    return $lista;

}


/*function incidencia_Validable(){
    $rpta="0";//no validable
    $cn=$this->getMyConexionADO();
    
    if($cn){
        $ssql="select validable from ca_incidencias ";
            $ssql.="where incidencia_codigo = ".$this->incidencia_codigo." ";
        
        $rs= $cn->Execute($ssql);
        if (!$rs->EOF){
             $rpta=''.$rs->fields[0];
        }
        
        $rs->close();
        $rs=null;
    }
    
    return $rpta;
}*/

function escribir_fin_evento($dia,$mes,$anio){
	$mensaje="";
	$archivo="d:\\ApacheGroup\Apache2\htdocs\sistemarrhh\ControlAsistencia\logs\log_fin_evento" . $dia . $mes .  $anio . ".txt";
    $mensaje =" Ocurrio un error en Eventos en el cual Hora_Fin: " . $this->evento_hora_fin . " es menor o igual a Hora_Inicio:" . $this->evento_hora_inicio . "\n";
	$mensaje .=" Parametros : empleado_codigo :" . $this->empleado_codigo . ",asistencia_codigo :" . $this->asistencia_codigo . "\n";

	$o=new writefile();
	
	$o->archivo=$archivo;
	$o->mensaje=$mensaje;
	$o->escribir();
}

function validar_fin_evento(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
    $ssql="select evento_codigo from ca_eventos where empleado_codigo=". $this->empleado_codigo;
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo;
    $ssql .=" and evento_hora_fin is null ";
	
	$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
		    $this->evento_codigo= $rs->fields[0]->value;
	  }else{
		    $this->evento_codigo=0;
	  }	
	  $rs->close();
	  $rs=null;
  }	
return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="select evento_codigo from ca_eventos where empleado_codigo=". $this->empleado_codigo;
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo;
    $ssql .=" and evento_hora_fin is null ";
	
    $rs = $cn->Execute($ssql);
    if ($rs->RecordCount()>0){
        $this->evento_codigo= $rs->fields[0];
    }else{
        $this->evento_codigo=0;
    }
    
    $rs->close();
    $rs=null;
    
    return $rpta;
}

function Lista_Eventos(){
    /*$rpta="OK";
	$cadena="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
         $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
         $cadena  .="<TR><TD class='FieldCaptionTD' align='left' colspan='2'><b>Sel.</b></TD>\n";
         $cadena  .="<TD class='FieldCaptionTD' align='left' ><b>Evento</b></TD>\n";
         $cadena  .="</TR>";
        
            $ssql="SELECT incidencia_codigo,incidencia_descripcion FROM ca_incidencias "; 
        	$ssql .=" WHERE ";
        	if($this->area_codigo!=0) $ssql .=" (area_codigo=0 or area_codigo=" . $this->area_codigo . ") and ";
        	$ssql .=" evento=1 and incidencia_activo=1 ";
        	$ssql .=" order by 2 ";
        	
        	$rs = $this->cnnado->Execute($ssql);
            if (!$rs->EOF){
        	    $cadena .="<TR class='DataTD'>\n";
                $cadena .="<TD align='left' colspan='3'>\n";
                $cadena .="<INPUT type=CHECKBOX align=center id='chk_todos' name='chk_todos' value='0' onclick='checkear()'>TODOS";
                $cadena .="</TD></TR>";
               while (!$rs->EOF){
                    $cadena .= "<TR class='DataTD'>\n";
                    $cadena .="<TD width='20%'>&nbsp;</TD>\n";
                    $cadena .="<TD>\n"; 
                    $cadena .="<INPUT type=CHECKBOX align=center id='chk' name='chk' value=" . $rs->fields[0]->value . " onclick='check()'>";
                    $cadena .="</TD>\n<TD >" . $rs->fields[1]->value; 
                    $cadena .="</TD>\n</TR>";
        			$rs->movenext();
                }
            $cadena .="</table>";
          }else{
           $cadena .="";
         }
        $rs->close();
        $rs=null;   
    }
    return $cadena;   */
    
    $rpta="OK";
	$cadena="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
        $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
        $cadena  .="<TR><TD class='FieldCaptionTD' align='left' colspan='2'><b>Sel.</b></TD>\n";
        $cadena  .="<TD class='FieldCaptionTD' align='left' ><b>Evento</b></TD>\n";
        $cadena  .="</TR>";
        
        $ssql="SELECT incidencia_codigo,incidencia_descripcion FROM ca_incidencias "; 
    	$ssql .=" WHERE ";
    	if($this->area_codigo!=0) $ssql .=" (area_codigo=0 or area_codigo=" . $this->area_codigo . ") and ";
    	$ssql .=" evento=1 and incidencia_activo=1 ";
    	$ssql .=" order by 2 ";
        	
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
    	    $cadena .="<TR class='DataTD'>\n";
            $cadena .="<TD align='left' colspan='3'>\n";
            $cadena .="<INPUT type=CHECKBOX align=center id='chk_todos' name='chk_todos' value='0' onclick='checkear()'>TODOS";
            $cadena .="</TD></TR>";
            while (!$rs->EOF){
                $cadena .= "<TR class='DataTD'>\n";
                $cadena .="<TD width='20%'>&nbsp;</TD>\n";
                $cadena .="<TD>\n"; 
                $cadena .="<INPUT type=CHECKBOX align=center id='chk' name='chk' value=" . $rs->fields[0] . " onclick='check()'>";
                $cadena .="</TD>\n<TD >" . $rs->fields[1]; 
                $cadena .="</TD>\n</TR>";
    			$rs->movenext();
            }
        $cadena .="</table>";
        }else{
            $cadena .="";
        }
        $rs->close();
        $rs=null;   
    }
    return $cadena;     
}
  
function validar_hora_turno(){
 
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		
    	$ssql="select * from ca_asistencia_programada ";
		$ssql .=" where (Empleado_Codigo = " . $this->empleado_codigo  . ")";
		$ssql .=" and (asistencia_fecha = convert(datetime , convert(varchar(10),getdate(), 103) , 103) )";
		$ssql .=" and getdate() > turno_fin ";
		
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$rpta="S";
			
	  }else{
		    $rpta="N";
	  }
	  $rs->close();
	  $rs=null;
    }

	return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql="select * from ca_asistencia_programada ";
    $ssql .=" where (Empleado_Codigo = " . $this->empleado_codigo  . ")";
    $ssql .=" and (asistencia_fecha = convert(datetime , convert(varchar(10),getdate(), 103) , 103) )";
    $ssql .=" and getdate() > turno_fin ";

    $rs = $cn->Execute($ssql);

    if ($rs->RecordCount()>0){
        $rpta="S";
    }else{
        $rpta="N";
    }
    
    $rs->close();
    $rs=null;

    return $rpta;
        
}

}
?>
