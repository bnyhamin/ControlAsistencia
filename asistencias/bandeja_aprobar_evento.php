<?php
    require_once("../../Includes/Connection.php");
    require_once("../../Includes/Constantes.php"); 
    require_once("../../Includes/mantenimiento.php");
    require_once("../includes/clsCA_Asistencias.php");
    require_once("../includes/clsCA_Asistencia_Incidencias.php");
    require_once("../includes/clsCA_Asignacion_Empleados.php");
    require_once("json/json.php");

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
	
	$a=new ca_asistencia();//N
    $a->setMyUrl(db_host());//N
    $a->setMyUser(db_user());//N
    $a->setMyPwd(db_pass());//N
    $a->setMyDBName(db_name());//N
    
    $asistencia_fecha="";//N
	$mensaje="OK";
    $indicador="";
    
    if (isset($_GET["registro"])) $registro = $_GET["registro"];
    if (isset($_GET["h"])) $h = $_GET["h"];
    if (isset($_GET["m"])) $m = $_GET["m"];
    if (isset($_GET["s"])) $s = $_GET["s"];
    if (isset($_GET["indicador"])) $indicador = $_GET["indicador"];
    
    $usuario_registra=$_GET["empleado_codigo_s"];
    $arrRegistro=split(",",$registro);
    $empleado_codigo=$arrRegistro[0];
    $asistencia_codigo=$arrRegistro[1];
    $evento_codigo=$arrRegistro[2];
    $incidencia_codigo=$arrRegistro[3];
    
    $ainc->empleado_codigo=$empleado_codigo;
    $rpta=$ainc->Obtener_servicio_empleado();
    $servicio=$ainc->cod_campana;
    
    $ainc->empleado_codigo=$empleado_codigo;
    $ainc->evento_codigo=$evento_codigo;
    $ainc->asistencia_codigo=$asistencia_codigo;
    //$ainc->incidencia_codigo=$incidencia_codigo;
    $ainc->incidencia_codigo=0;
    $ainc->cod_campana = $servicio;
	
	$a->empleado_codigo=$empleado_codigo;//N
    $a->asistencia_codigo=$asistencia_codigo;//N
    $asistencia_fecha=$a->get_Asistencia_Fecha();//N
	
    //obtener el codigo del supervisor responsable de la incidencia
    $responsable=$ainc->obtener_codigo_supervisor();
    $ainc->responsable_codigo = $responsable;
    $ainc->tiempo_derg=0;
    
    //log
    $ase->empleado_codigo=$empleado_codigo;
    $ase->asistencia_codigo=$asistencia_codigo;
    $ase->evento_codigo=$evento_codigo;
    $ase->aprobado='S';
    
    if($indicador=="M"){
        $ase->texto_descripcion='Aprobada por Mando';
        $ase->realizado='M';
    }else if($indicador=="G"){
        $ase->texto_descripcion='Aprobada por Gerente';
        $ase->realizado='G';
    }
    
    $ase->empleado_jefe=$usuario_registra;
    $ase->empleado_ip=$_SERVER['REMOTE_ADDR'];
    if($h==-1) $ase->hora=NULL; else $ase->hora=$h;
    if($m==-1) $ase->minuto=NULL; else $ase->minuto=$m;
    if($s==-1) $ase->incidencia_codigo_sustituye=NULL; else $ase->incidencia_codigo_sustituye=$s;
    
    
    $flujo = $ase->flujo_validacion($incidencia_codigo,$indicador);
    
    if($flujo == "OK"){
        $ase->estado_evento = 3; //aprobado
    }else{
        $ase->estado_evento = 2; //sigue en proceso
    }
    
    $rpta=$ase->registrar_evento();
    $ase->cambiar_estado_evento();
	if($flujo == "OK"){            
	     $ase->desactivar_evento(); 
    } 
    //actualiza hora y incidencia_codigo_sustituye
	if($ase->flag_validable*1==1){
		$rtpa=$ase->actualizar_evento();
		$ase->incidencia_codigo=$incidencia_codigo;
		//$ase->desactivar_evento();
		//variables para el nuevo metodo*
		$num="1";
		$ip_entrada=$_SERVER['REMOTE_ADDR'];
		$ip_salida=$_SERVER['REMOTE_ADDR'];
		$fecha=date("d/m/Y");
		$flgproceso=1;
		$ainc->incidencia_codigo=$incidencia_codigo;
		$incidencia_hh_dd=$ainc->tipoIncidencia();
		//incidencias horarias diarias...
		$ainc->fecha=$asistencia_fecha;
		$ainc->flgproceso=$flgproceso;
		$ainc->incidencia_hh_dd=$incidencia_hh_dd;
		$area=$ase->obtener_area_supervisor();
		$ainc->area_codigo=$area;
		$ainc->codigo_empresa=1;
		$ainc->ip_entrada=$ip_entrada;
		$ainc->ip_registro=$ip_entrada;
        if($flujo == "OK"){
		  $mensaje=$ainc->registrar_incidencia($num,$ip_entrada,$ip_salida);
        }
	}
    $json=new Services_JSON();
    $option=$_GET["action"];
switch ($option){
    case "genera_incidencia":
        if($mensaje=="OK"){
            $status=1;
        }else{
            $status=0;
        }
        $rpta=array(
            "status"=>$status
        );
        echo $json->encode($rpta);
    break;
}
?>
