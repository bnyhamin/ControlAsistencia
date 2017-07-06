<?php
require_once(PathIncludes() . "mantenimiento.php");

class Justifica extends mantenimiento{
    function Listar_Areas(){
        $cn = $this->getMyConexionADO();
        $sql = "select area_codigo, Area_Descripcion 
                from areas 
                where area_activo = 1 and Area_Codigo <> 0
                order by 2 asc";
        $rs = $cn->Execute($sql);
        return $rs;
    }
    function  Listar_Tardanzas($fecha, $inicio, $fin,$dni,$local_codigo,$areas_seleccionadas){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $params = array($fecha,$inicio, $fin);
        $sql = "select e.Empleado_Codigo as empleado_codigo,
                	e.Empleado_Dni as DNI,
                	e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres as Empleado,
                	a.Area_Descripcion as Area,
                	l.Local_Descripcion as Local,
                	convert(varchar(10),dateadd(mi,t.turno_minuto_inicio,dateadd(hh,t.turno_hora_inicio,asistencia_fecha)),108) as Inicio_Turno,
                	convert(varchar(10),ca.Asistencia_entrada,108) as Marcacion_Entrada,
                	cai.tiempo_minutos as Minutos_Tardanza
                from CA_Asistencias ca 
                	inner join CA_Asistencia_Incidencias cai on ca.Empleado_Codigo = cai.Empleado_Codigo and ca.Asistencia_codigo = cai.Asistencia_codigo
                	inner join empleados e on ca.Empleado_Codigo = e.Empleado_Codigo
                	inner join CA_Turnos t on t.Turno_Codigo = ca.turno_codigo
                	inner join Empleado_Area ea on e.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1
                	inner join areas a on ea.Area_Codigo = a.Area_Codigo
                	inner join locales l on e.Local_Codigo = l.Local_Codigo
                where ca.Asistencia_fecha = convert(datetime,?,103) 
                	and ca.CA_Estado_Codigo = 1 
                	and cai.Incidencia_codigo = 7
                	and dateadd(mi,t.turno_minuto_inicio,dateadd(hh,t.turno_hora_inicio,asistencia_fecha))
                		between convert(datetime,?,103) and convert(datetime,?,103)";
        if($dni !=""){
            $sql .= " and e.Empleado_Dni like '%".$dni."%'";
        }
        if($local_codigo != 0){
            $params[] = $local_codigo;
            $sql .= " and e.Local_Codigo = ?";
        }
        if($areas_seleccionadas != ""){
            $sql .= " and ea.Area_Codigo in (".$areas_seleccionadas.")";
        }
        $sql .= " order by 3 asc";
        $rs = $cn->Execute($sql, $params);
        return $rs;
    }
    
    function Justifica_Tardanza($fecha, $inicio, $fin,$dni,$local_codigo, $incidencia_justifica,$areas_seleccionadas,$usuario){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        //$params = array($incidencia_justifica,$fecha,$inicio, $fin);
        $params = array($fecha,$inicio, $fin);
        $sql = " from CA_Asistencias ca 
                	inner join CA_Asistencia_Incidencias cai on ca.Empleado_Codigo = cai.Empleado_Codigo and ca.Asistencia_codigo = cai.Asistencia_codigo
                	inner join empleados e on ca.Empleado_Codigo = e.Empleado_Codigo
                	inner join CA_Turnos t on t.Turno_Codigo = ca.turno_codigo
                	inner join Empleado_Area ea on e.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1
                	inner join areas a on ea.Area_Codigo = a.Area_Codigo
                	inner join locales l on e.Local_Codigo = l.Local_Codigo
                where ca.Asistencia_fecha = convert(datetime,?,103) 
                	and ca.CA_Estado_Codigo = 1 
                	and cai.Incidencia_codigo = 7
                	and dateadd(mi,t.turno_minuto_inicio,dateadd(hh,t.turno_hora_inicio,asistencia_fecha))
                		between convert(datetime,?,103) and convert(datetime,?,103)";
        if($dni !=""){
            $sql .= " and e.Empleado_Dni like '%".$dni."%'";
        }
        if($local_codigo != 0){
            $params[] = $local_codigo;
            $sql .= " and e.Local_Codigo = ?";
        }
        if($areas_seleccionadas != ""){
            $sql .= " and ea.Area_Codigo in (".$areas_seleccionadas.")";
        }

        if($rs = $cn->Execute("select e.empleado_codigo ".$sql, $params)){
            if($rs->RecordCount() > 0){
                while(!$rs->EOF){
                    
                    $_sql = "select isnull(max(log_codigo),0) + 1 from log_empleado";
                    $_rs = $cn->Execute($_sql);
                    $log_codigo = $_rs->fields[0];
                    
                    $params = array($log_codigo,$rs->fields[0],8,"JUSTIFICACION DE INCIDENCIA",$usuario,$_SERVER["REMOTE_ADDR"]);
                    $__sql = "insert into log_empleado(
                                		log_codigo,
                                		empleado_codigo,
                                		log_proceso_codigo,
                                		log_comentario,
                                		usuario_reg,
                                        fecha_registro,
                                		ip_reg)
                               values(?,?,?,?,?,getdate(),?)";
                    $__rs = $cn->Execute($__sql,$params);
                    $cn->Execute($__sql, $params);
                    
                    $rs->MoveNext();
                }
                $params = array($incidencia_justifica,$fecha,$inicio, $fin);
                if($cn->Execute("update ca_asistencia_incidencias set incidencia_codigo=? ".$sql, $params)){
                    return "OK";
                }
            }else{
                return "NO REGISTROS";
            }
        }else{
            return "ERROR";
        }
        
    }
    
    function Listar_Salidas($fecha, $inicio, $fin,$dni,$local_codigo,$areas_seleccionadas){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $params = array($fecha,$inicio, $fin);
        $sql = "select e.Empleado_Codigo as empleado_codigo,
                	e.Empleado_Dni as DNI,
                	e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres as Empleado,
                	a.Area_Descripcion as Area,
                	l.Local_Descripcion as Local,
                	convert(varchar(10),dateadd(mi,t.turno_minuto_fin,dateadd(hh,t.turno_hora_fin,asistencia_fecha)),108) as Fin_Turno,
                	convert(varchar(10),ca.Asistencia_salida,108) as Marcacion_Salida,
                	cai.tiempo_minutos as Minutos_Salida
                from CA_Asistencias ca 
                	inner join CA_Asistencia_Incidencias cai on ca.Empleado_Codigo = cai.Empleado_Codigo and ca.Asistencia_codigo = cai.Asistencia_codigo
                	inner join empleados e on ca.Empleado_Codigo = e.Empleado_Codigo
                	inner join CA_Turnos t on t.Turno_Codigo = ca.turno_codigo
                	inner join Empleado_Area ea on e.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1
                	inner join areas a on ea.Area_Codigo = a.Area_Codigo
                	inner join locales l on e.Local_Codigo = l.Local_Codigo
                where ca.Asistencia_fecha = convert(datetime,?,103) 
                	and ca.CA_Estado_Codigo = 1 
                	and cai.Incidencia_codigo = 154
                	and ( dateadd(mi,ca.extension_tiempo,(dateadd(mi,t.turno_minuto_fin,dateadd(hh,t.turno_hora_fin,asistencia_fecha)))) between 
                		 convert(datetime,?,103) and convert(datetime,?,103) ) 
                	and Asistencia_entrada is not null
                    and ca.asistencia_salida is not null";
        if($dni !=""){
            $sql .= " and e.Empleado_Dni like '%".$dni."%'";
        }
        if($local_codigo != 0){
            $params[] = $local_codigo;
            $sql .= " and e.Local_Codigo = ?";
        }
        if($areas_seleccionadas != ""){
            $sql .= " and ea.Area_Codigo in (".$areas_seleccionadas.")";
        }
        
        $sql .= " order by 3 asc";

        $rs = $cn->Execute($sql, $params);
        return $rs;
    }
    
    function Justificar_Salida($fecha, $inicio, $fin,$dni,$local_codigo,$areas_seleccionadas,$usuario){
        $cn = $this->getMyConexionADO();
        //$cn->debug = true;
        $params = array($fecha, $inicio, $fin, $dni,$local_codigo, $areas_seleccionadas,$usuario, $_SERVER["REMOTE_ADDR"]);
        $sql = "exec spca_justifica_salida ?,?,?,?,?,?,?,?";
        if($cn->Execute($sql, $params))
            return "OK";
        else
            return "ERROR";
       
    }
    
    function Paginacion($rs, $start, $limit){
        $filas = array();
        if($rs->RecordCount() > 0){
            $i = 0;
            while(!$rs->EOF){
                $dato["N"]          = ++$i;
                $dato["DNI"]        = $rs->fields[1];
                $dato["EMPLEADO"]   = $rs->fields[2];
                $dato["AREA"]       = $rs->fields[3];
                $dato["LOCAL"]      = $rs->fields[4];
                $dato["INICIO_FIN"] = $rs->fields[5];
                $dato["MARCACION"]  = $rs->fields[6];
                $dato["TIEMPO"]     = $rs->fields[7];
                $filas[] = $dato;
                $rs->MoveNext();
            }
            $data = array_splice($filas, $start, $limit);
        }
        return $data;
    }
        
}

 