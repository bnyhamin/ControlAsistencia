<?php
    set_time_limit(0);	
    
    require_once(dirname(dirname(dirname(__FILE__)))."/Includes/Connection.php");
    require_once(PathIncludes()."Constantes.php");
    require_once(PathIncludes()."mantenimiento.php");  
    require_once(PathIncludesGAP()."clsCA_Asistencias.php");
    require_once(PathIncludesGAP()."clsCA_Asistencia_Incidencias.php");
    require_once(PathIncludesGAP()."clsCA_Asignacion_Empleados.php");
    

    $ainc=new ca_asistencia_incidencias();
    $ainc->MyUrl = db_host();
    $ainc->MyUser= db_user();
    $ainc->MyPwd = db_pass();
    $ainc->MyDBName= db_name();
    
    $ase = new ca_asignacion_empleados();
    $ase->MyUrl = db_host();
    $ase->MyUser= db_user();
    $ase->MyPwd = db_pass();
    $ase->MyDBName= db_name();
	
	$a=new ca_asistencia();
    $a->setMyUrl(db_host());
    $a->setMyUser(db_user());
    $a->setMyPwd(db_pass());
    $a->setMyDBName(db_name());
    
    $asistencia_fecha="";
    
    $rs=$ainc->obtenerSilencios();
    
    
    
    foreach ($rs as $key => $value) {
        $ase->empleado_codigo=$value["empleado_codigo"];
        $ase->asistencia_codigo=$value["asistencia_codigo"];
        $ase->evento_codigo = $value["evento_codigo"];
        $ase->incidencia_codigo = $value["incidencia_codigo"];
		
		$a->empleado_codigo=$value["empleado_codigo"];//N
        $a->asistencia_codigo=$value["asistencia_codigo"];//N
        $asistencia_fecha=$a->get_Asistencia_Fecha();//N
		
        $ase->texto_descripcion="Silencio Administrativo";
        $ase->empleado_jefe=3300;
        
        if($value["validable"]==1) $ase->realizado="V";
        else if($value["validable_mando"]==1) $ase->realizado="M";
        else if($value["validable_gerente"]==1) $ase->realizado="G";
        
        if($value["silencio_vbo"]==1){
            //cambiar el estado del evento
            $ase->estado_evento = 3;
            $ase->cambiar_estado_evento();
            //registrar log
            $ase->aprobado="S";
            $ase->registrar_Silencios();
            //desactivar
            $ase->desactivar_evento();
            //parte2
            $num="1";
            $ip_entrada='AUTOMATICO';
            $ip_salida='AUTOMATICO';
            $ainc->incidencia_codigo=$value["incidencia_codigo"];
            $incidencia_hh_dd=$ainc->tipoIncidencia();
            $ainc->empleado_codigo=$value["empleado_codigo"];
            $rpta=$ainc->Obtener_servicio_empleado();
            $servicio=$ainc->cod_campana;
            $ainc->empleado_codigo=$value["empleado_codigo"];
            $ainc->evento_codigo=$value["evento_codigo"];
            $ainc->asistencia_codigo=$value["asistencia_codigo"];
            $ainc->incidencia_codigo=$value["incidencia_codigo"];
            $ainc->cod_campana = $servicio;
            $ainc->responsable_codigo = $value["responsable"];
            //$ainc->fecha=date("d/m/Y");
			$ainc->fecha=$asistencia_fecha;//N
            $ainc->flgproceso=1;
            $ainc->incidencia_hh_dd=$incidencia_hh_dd;
            $area=$ase->obtener_area_supervisor();
            $ainc->area_codigo=$area;
            $ainc->codigo_empresa=1;
            $ainc->ip_entrada=$ip_entrada;
            $ainc->ip_registro=$ip_entrada;
            $mensaje=$ainc->registrar_incidencia($num,$ip_entrada,$ip_salida);
			echo "silencio positivo";
            echo $mensaje;
        }else{
            $ase->estado_evento = 4;
            $ase->cambiar_estado_evento();
            $ase->desactivar_evento();
            $ase->aprobado="N";
            $ase->registrar_Silencios();
			echo "silencio negativo";
        }
        
        
    }
?>
