<?php
class ca_asistencia_incidencias extends mantenimiento{
var $empleado_codigo='';
var $asistencia_codigo='';
var $responsable_codigo='';
var $incidencia_codigo='';
var $asistencia_incidencia_codigo='';
var $incidencia_hh_dd='';
var $tiempo_minutos=0;
var $cod_campana='';
var $campana='';
var $horas=0;
var $minutos=0;
var $incidencia_hora_inicio="";
var $incidencia_hora_fin="";
var $evento_codigo="";
var $fecha='';
var $vacaciones=0;
var $area_codigo='';
var $area_codigo_responsable='';
var $tiempo_derg='';
var $ip_registro="";
var $extension_tiempo="";
var $empresa = "";
var $incidencia_observacion='';
var $texto_descripcion='';
var $aprobado='';
var $empleado_jefe=0;
var $empleado_ip='';
var $ticket='';
var $realizado=NULL;
var $flgproceso=0;
var $empleado_tipo_pago=NULL;

function procesar(){
	  $rpta = "";
	  $this->conectarme();	  
	  
	  $sql="SELECT  CA_Asistencias.Empleado_Codigo, CA_Asistencias.Asistencia_codigo, "; 
	  $sql.="       CA_Asistencia_Incidencias.responsable_codigo, CA_Asistencia_Incidencias.cod_campana, CA_Asistencias.Asistencia_fecha ";
	  $sql.=" FROM    CA_Asistencias INNER JOIN ";
	  $sql.="         CA_Asistencia_Incidencias ON CA_Asistencias.Empleado_Codigo = CA_Asistencia_Incidencias.Empleado_Codigo AND "; 
	  $sql.="         CA_Asistencias.Asistencia_codigo = CA_Asistencia_Incidencias.Asistencia_codigo INNER JOIN ";
	  $sql.="         Empleado_Area ON CA_Asistencias.Empleado_Codigo = Empleado_Area.Empleado_Codigo ";
	  $sql.=" WHERE   (CA_Asistencias.Asistencia_fecha = CONVERT(DATETIME, '" . $this->fecha . "', 103)) AND "; 
	  $sql.=" 		(CA_Asistencia_Incidencias.Incidencia_codigo = 38) AND "; 
	  $sql.="       (Empleado_Area.Empleado_Area_Activo = 1) AND  ";
	  $sql.=" 		(Empleado_Area.Area_Codigo = " . $this->area_codigo . ") ";
	  $sql.=" Order by CA_Asistencias.Empleado_Codigo ";
	  
	  $result = mssql_query($sql);
		$i=0;
		$incidencia_codigo=11; //Incidencia de Justificacion de Asistencia
		$incidencia_hh_dd=0;
		while($rs = mssql_fetch_row($result)){
			$i++;
			$this->empleado_codigo=$rs[0]; //empleado_codigo;
			$this->incidencia_codigo = $incidencia_codigo;
			$this->asistencia_codigo =$rs[1]; //asistencia_codigo; 
			$this->responsable_codigo =$rs[2]; //responsable_codigo;			
			$this->incidencia_hh_dd=$incidencia_hh_dd;
		    $this->horas =0;
		    $this->minutos=0;
			$this->cod_campana=$rs[3]; //cod_campana;
			$this->fecha=$this->fecha;		
			//echo "$num,$ip_entrada,$ip_salida";
		  $mensaje = $this->registrar_incidencia(1,'','');
		  //echo $i . " : " . $mensaje;
			//if($mensaje=='OK'){
		}
} 


function esValidable(){
    $i="0";
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql=" select validable,validable_mando,validable_gerente from ca_incidencias ";
        $ssql.=" where incidencia_codigo = ".$this->incidencia_codigo." ";
            
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            if(''.$rs->fields[0]=="1") $i="1";
            if(''.$rs->fields[1]=="1") $i="1";
            if(''.$rs->fields[2]=="1") $i="1";
        }
        $rs->close();
        $rs=null;
    }
    return $i;
}



function registrar_incidencia($num,$ip_entrada,$ip_salida){
    
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $cn->BeginTrans();
    $o=new ca_asistencia();
    $o->MyUrl = $this->getMyUrl();
    $o->MyUser= $this->getMyUser();
    $o->MyPwd = $this->getMyPwd();
    $o->MyDBName= $this->getMyDBName();
    
    $o->empleado_codigo=$this->empleado_codigo;
    $o->asistencia_codigo=$this->asistencia_codigo;
    $o->asistencia_fecha=$this->fecha;
    $o->responsable_codigo=$this->responsable_codigo;
    
    if($num==0){
        
        $o->ip_entrada = $ip_entrada;
        if($this->incidencia_codigo==150 || $this->incidencia_codigo==151){
            $rpta="¡Error! No existe falta o tardanza a justificar.";
        }else{
            $mensaje = $o->registrar_nueva_asistencia($this->responsable_codigo,$this->incidencia_codigo);
        }
        
        if($mensaje=="OK"){
            $this->asistencia_codigo =$o->asistencia_codigo; 
        }
    }
		
    //preguntar si es validable
        //if(($this->incidencia_Validable()=="1" || $this->validable_mando()=="1") && $this->flgproceso==0){
        if(($this->esValidable()=="1") && $this->flgproceso==0){
            if($this->horas=="-1"){
                $this->horas=0;
                $this->minutos=0;
            }
         
            $this->registrar_inicio_evento();//registro de evento
            $this->registrar_evento_log();//registro de evento en log
            
        }else{
            
            if($num!=0){
                if($this->incidencia_codigo==11 || $this->incidencia_codigo==42 || $this->incidencia_codigo==166 ||
                    $this->incidencia_codigo==43 || $this->incidencia_codigo==66 ||
                    $this->incidencia_codigo==67 || $this->incidencia_codigo==150 ||
                    $this->incidencia_codigo==151 || $this->incidencia_codigo==170 ||
                     $this->incidencia_codigo==79 || $this->incidencia_codigo==172 ){
                    //172 => Evento de la Empresa Dia Completo
                    if($this->incidencia_codigo==11 || $this->incidencia_codigo==170 || $this->incidencia_codigo==172){ // Justificacion de Asistencia (Entrada y Salida)
                        $rpta=$this->Justificar_asistencia_diaria();
                        $rpta=$o->registrar_incidencia_horas();//***VALIDAR EN EL STORE
                    }

                    if($this->incidencia_codigo==151){ // Falta Justificada (Entrada y Salida)
                        $rpta=$this->Falta_justificada($ip_entrada);
                        //$rpta=$o->registrar_incidencia_horas();
                    }

                    //Hora de Entrada justificada
                    if($this->incidencia_codigo==42) $rpta=$this->Justificar_entrada_asistencia();
                    if($this->incidencia_codigo==79) $rpta=$this->Justificar_entrada_asistencia();
                    if($this->incidencia_codigo==150) $rpta=$this->Justificar_tardanza($ip_entrada);
					if($this->incidencia_codigo==166) $rpta=$this->Justificar_entrada_asistencia();

                    //Hora Lactancia(Inicio Turno)
                    if($this->incidencia_codigo==66) $rpta=$this->Justificar_hora_entrada_maternidad();
                    //Hora de Salida justificada o Hora Lactancia(Fin Turno)
                    if($this->incidencia_codigo==43 || $this->incidencia_codigo==67){
                        $rpta=$this->Justificar_salida_asistencia($ip_salida);
                        if($this->incidencia_codigo==67){
                            $rpta=$this->Justificar_Incidencia_Desc_Hora_Anticipada_Lactancia($ip_entrada);
                            if($rpta != "OK"){
                                return $rpta;
                            }
                        }
                        $rpta=$o->registrar_incidencia_horas();
                    }

                }  
            }
                
            //$this->incidencia_hh_dd=1
            if($this->incidencia_hh_dd==0){
                
                $ssql="select asistencia_incidencia_codigo ";
                $ssql .=" from CA_Asistencia_Incidencias ";
                $ssql .=" where empleado_codigo=" . $this->empleado_codigo . " and ";
                $ssql .=" asistencia_codigo=" . $this->asistencia_codigo . "";
                $ssql .=" and incidencia_codigo=38";
                //echo "$ssql\n";
                $rst= $cn->Execute($ssql);
                
                if($rst->EOF){ //si no existe la falta
                    if($this->incidencia_codigo==150 || $this->incidencia_codigo==151 || $this->incidencia_codigo==152){
                        //echo 'no hace nada';//no crear incidencia para otros casos sigue igual
                    }else{
                        if($this->incidencia_codigo == 38){
                            $rpta = $this->Insertar_Nueva_Asistencia();
                        }else{
                            $rpta=$this->Insertar_incidencia_diaria();
                        }	
                        //$rpta=$this->Insertar_incidencia_diaria();		
                    }
                }else{ //si existe
                    if($this->incidencia_codigo==150 || $this->incidencia_codigo==151){
                        //no modificar incidencia para otros casos sigue igual
                    }else{
                        if($this->incidencia_codigo==152){ //si se esta ingresando una falta justificada
                            $rpta = $this->Falta_Justificada_Incidencia(152); //actualizamos la falta por la incidencia 152(solo la incidencia)
                        }else{
                            $this->asistencia_incidencia_codigo = $rst->fields[0];
                            $rpta=$this->Modificar_incidencia_falta();//actualizamos la falta por la incidencia 152(varios campos)
                        }
                    }
                }

                $rst->close();
                $rst=null;

            }else{
                if($this->flgproceso==1){
                    $this->obtenerHorasMinutos();//obtener horas y minutos
                }
                if($this->incidencia_hh_dd==1 and $this->incidencia_codigo != 150) $rpta=$this->Insertar_incidencia_horaria($ip_entrada);
            }
        }      
    
    $cn->CommitTrans();
    $o=null;
    return $rpta;
        
}


function obtenerHorasMinutos(){
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql=" select horas,minutos from ca_eventos ";
        $ssql.=" where Empleado_Codigo = ".$this->empleado_codigo." and Asistencia_codigo = ".$this->asistencia_codigo." ";
        $ssql.=" and Evento_Codigo = ".$this->evento_codigo." ";
        
        $rs= $cn->Execute($ssql);
        $this->horas = $rs->fields[0];
        $this->minutos = $rs->fields[1];
        $rs=null;    
    }
}

//silencios administrativos por dia
function obtenerSilencios(){
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql=" EXEC spCA_Silencios_Administrativos ";
        $rs= $cn->Execute($ssql);
        $padre=array();
        while (!$rs->EOF){
            $hijo=array();
            $hijo["empleado_codigo"]=$rs->fields[0];
            $hijo["asistencia_codigo"]=$rs->fields[1];
            $hijo["evento_codigo"]=$rs->fields[2];
            $hijo["ee_codigo"]=$rs->fields[3];
            $hijo["ultimo"]=$rs->fields[4];
            $hijo["dias"]=$rs->fields[5];
            $hijo["responsable"]=$rs->fields[6];
            $hijo["incidencia_codigo"]=$rs->fields[7];
            $hijo["validable"]=$rs->fields[8];
            $hijo["validable_mando"]=$rs->fields[9];
            $hijo["dias_utiles"]=$rs->fields[10];
            $hijo["validable_gerente"]=$rs->fields[11];
            $hijo["silencio_vbo"]=$rs->fields[12];
            array_push($padre, $hijo); 
            $rs->movenext();
        }
        
    }
    $rs=null;
    return $padre;
}


function tipoIncidencia(){
    $cn=$this->getMyConexionADO();
    $incidencia_hh_dd=0;
    if($cn){
        $ssql=" select incidencia_codigo as codigo,incidencia_descripcion AS descripcion,incidencia_hh_dd 
                from CA_Incidencias where Incidencia_codigo=".$this->incidencia_codigo." and incidencia_activo=1 ";
        
        $rs= $cn->Execute($ssql);
        $incidencia_hh_dd = $rs->fields[2];
        $rs=null;
        //$rs->close();
    }
    return $incidencia_hh_dd;
}

//prio
function registrar_inicio_evento(){
 $rpta="OK";
 $cn=$this->getMyConexionADO();
 
 if($cn){
	 // crea un nuevo registro
	    $ssql="select isnull(max(evento_codigo),0)+1 id from ca_eventos ";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
                
		$rs= $cn->Execute($ssql);
		$this->evento_codigo = $rs->fields[0];
        $sql =" INSERT INTO ca_eventos(
                    Empleado_codigo,
                    Asistencia_codigo,
                    Evento_Codigo,
                    Incidencia_codigo,
                    horas,
                    minutos,
                    fecha_reg_inicio,
                    evento_activo,
                    ee_codigo,
                    num_ticket,
                    observacion) ";
        $sql .=" values(" . $this->empleado_codigo . ",";
        $sql .=" " . $this->asistencia_codigo . ",";
        $sql .=" " . $this->evento_codigo . ",";
        $sql .=" " . $this->incidencia_codigo . ",";
        $sql .=" " . $this->horas . ",";
        $sql .=" " . $this->minutos . ",";
        $sql .=" getdate(),1,2,";
        if($this->ticket==NULL) $sql.="null,"; else  $sql .=" '" . $this->ticket . "',";
        //$sql .=" '" . $this->ticket . "',";
        $sql .=" upper('".$this->texto_descripcion."') )";      
        $r=$cn->Execute($sql);
        if(!$r){
            $rpta = "Error al Insertar inicio Evento.";
        }else{
            $rpta= "OK";
        }
        $rpta.=$sql;
		$r->close();
		$r=null;
	}
	  return $rpta;
 }

 //flag
 function registrar_evento_log(){
    $rpta="OK";
    $ssql="";
    $cn=$this->getMyConexionADO();
    
    if($cn){
	
	$ssql="insert into ca_evento_log(empleado_codigo,asistencia_codigo,evento_codigo,";
        $ssql.="aprobado,observacion,em_codigo,usuario_registro,";
        $ssql.="fecha_registro,ip_registro, horas,minutos,incidencia_codigo,realizado) ";
        $ssql.=" values(?,?,?,?,upper('".$this->texto_descripcion."'),";
        $ssql.="1,?,GETDATE(),?,?,?,?,?)";
        
        
        $params=array(
            $this->empleado_codigo,
            $this->asistencia_codigo,
            $this->evento_codigo,
            $this->aprobado,
            $this->empleado_jefe,
            $this->empleado_ip,
            $this->horas,
            $this->minutos,
            $this->incidencia_codigo,
            $this->realizado
        );
        
               
        $r=$cn->Execute($ssql,$params);
        if(!$r){
            $rpta = "Error al Insertar empleados.";
        }
        
    }
    return $rpta;
}
 
 
function asignarEmpresa(){
	
    
    
    $cn=$this->getMyConexionADO();
    $ssql ="select empresa_codigo from empresa where empresa_activo = 1";
    $ra = $cn->Execute($ssql);
    $codigo_empresa = $ra->fields[0];
    $this->codigo_empresa = $codigo_empresa;
    
}

function Justificar_Incidencia($ip, $cod_incidencia_actual, $cod_incidencia_justificacion){
    
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $codigo_empresa = $this->codigo_empresa;
    //echo "codigo_empresa".$codigo_empresa."area_codigoxxxx".$this->area_codigo;
	
    if($codigo_empresa == 1){
        //si existe incidencia de tardanza Actualizar
        $ssql ="select * ";
        $ssql .=" from ca_asistencia_incidencias ";
        $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
        $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
        $ssql .=" and incidencia_codigo= ".$cod_incidencia_actual;
        $rst = $cn->Execute($ssql);
        //echo "QUERY".$ssql;
        if (!$rst->EOF){
            if($cod_incidencia_actual == 154 && $cod_incidencia_justificacion == 157){
                $ssql ="exec SPCA_JUSTIFICA_SALIDA_ANTICIPADA_GESTION_EMPRESA ?, ?, ?, ?";
                $params=array(
                    $this->empleado_codigo,
                    $this->asistencia_codigo,
                    $ip,
                    $this->fecha
                );
                $rt=$cn->Execute($ssql,$params);
                if(!$rt){
                    $rpta = "Error al justificar incidencia ".$cod_incidencia_actual;
                    $cn->RollbackTrans();
                    return $rpta;
                }else{
                    $resultado = $rt->fields[0];
                    if($resultado == 0){
                        $rpta= "OK";
                    } else {
                        $rpta = "Error al justificar incidencia ".$cod_incidencia_actual;
                        $cn->RollbackTrans();
                        return $rpta;
                    }
                }
            }else{
                $ssql =" UPDATE ca_asistencia_incidencias ";
                $ssql.=" SET incidencia_codigo=".$cod_incidencia_justificacion.", fecha_reg=getdate(), ip_incidencia='" . $ip . "'";
                $ssql.=" WHERE empleado_codigo=" . $this->empleado_codigo;
                $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=".$cod_incidencia_actual;
				
                $rt=$cn->Execute($ssql);
                if(!$rt){
                        $rpta = "Error al justificar incidencia ".$cod_incidencia_actual;
                        $cn->RollbackTrans();
                        return $rpta;
                }else{
                        $rpta= "OK";
                }
            }
			
        }else {
            //else if($cod_incidencia_justificacion != 157){
            $rpta= "NO_JUSTIFICACION";
        }			
    }else{
        //empresa -> sucursal
        $ssql ="select * ";
        $ssql .=" from ca_asistencia_incidencias ";
        $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
        $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
        $ssql .=" and incidencia_codigo=".$cod_incidencia_justificacion;
        $ra = $cn->Execute($ssql);
        //actualizamos compensacion
        if (!$ra->EOF){
            $ssql="select asistencia_incidencia_codigo from ca_asistencia_incidencias ";
            $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=".$cod_incidencia_justificacion;
            $rb= $cn->Execute($ssql);
            $this->asistencia_incidencia_codigo = $rb->fields[0];
            $ssql =" update ca_asistencia_incidencias ";
            $ssql.=" set fecha_reg=getdate(), ip_incidencia='" . $ip . "',";
            $ssql.=" tiempo_minutos='" . (($this->horas * 60) + $this->minutos) . "',";
            $ssql.=" horas='" . $this->horas . "',";
            $ssql.=" minutos='" . $this->minutos . "'";
            $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=".$cod_incidencia_justificacion;
            $ssql.=" and asistencia_incidencia_codigo=" . $this->asistencia_incidencia_codigo;
			
            $rc=$cn->Execute($ssql);
            if(!$rc){
                $rpta = "Error al actualizar incidencia ".$cod_incidencia_justificacion;
                $cn->RollbackTrans();
                return $rpta;
            }else{
                $rpta= "OK";
            }
            $rc->close();
            $rc = null;
        }else{
            $ssql="SELECT area_codigo FROM empleado_area ";
            $ssql.= " WHERE Empleado_Codigo=". $this->responsable_codigo . " AND empleado_area_activo=1";
            //echo $ssql;
            $ru= $cn->Execute($ssql);
            $this->area_codigo_responsable = $ru->fields[0];

            $ssql="select isnull(max(asistencia_incidencia_codigo),0)+1 id from ca_asistencia_incidencias ";
            $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
            $re= $cn->Execute($ssql);
            $this->asistencia_incidencia_codigo = $re->fields[0];

            $ssql =" insert into ca_asistencia_incidencias ";
            $ssql .=" (Empleado_codigo, Asistencia_Incidencia_Codigo,Asistencia_Codigo,Incidencia_Codigo,Responsable_Codigo,cod_campana,Tiempo_minutos,horas,minutos,fecha_reg,reg_automatico,area_codigo, area_codigo_responsable, ip_incidencia) ";
            $ssql.=" values('" . $this->empleado_codigo . "', " . $this->asistencia_incidencia_codigo . ", " . $this->asistencia_codigo . ", ".$cod_incidencia_justificacion.",";
            $ssql.=$this->responsable_codigo . "," . $this->cod_campana . ", " . $this->tiempo_minutos . ", " . $this->horas . ", " . $this->minutos . ", ";
            $ssql.="convert(datetime, " . $this->fecha . ", 103), 0, ";
            $ssql.=$this->area_codigo . "," . $this->area_codigo_responsable . ", '" . $ip . "')";
            
            $r=$cn->Execute($ssql);
            if(!$r){
                $rpta = "Error al ingresar incidencia ".$cod_incidencia_justificacion;
                $cn->RollbackTrans();
                return $rpta;
            }else{
                $rpta= "OK";
            }
            
            $r->close();
            $r = null;
        }
        
        $ra->close();
        $ra = null;
    }
    $rst=null;	
    return $rpta;
        
}

function Justificar_Incidencia_Desc_Hora_Anticipada_Lactancia($ip){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $codigo_empresa = $this->codigo_empresa;
    if($codigo_empresa == 1){
        //si existe incidencia de tardanza Actualizar
        $ssql ="select * ";
        $ssql .=" from ca_asistencia_incidencias ";
        $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
        $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo . "";
        $ssql .=" and incidencia_codigo=154";
        $rst = $cn->Execute($ssql);
        if (!$rst->EOF){
            $rst = null;
            $ssql="select isnull(tiempo_minutos, 0) from ca_asistencia_incidencias ";
            $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=154";
            $rst=$cn->Execute($ssql);
            $minutos_incidencia_descuento = $rst->fields[0];
            $minutos_justificacion = 60;
            $minutos_efectivos_justificacion = 0;
            $crear_incidencia_nueva = false;
            $replace_incidencia = 0;
            
            if($minutos_incidencia_descuento > $minutos_justificacion){
                $minutos_efectivos_justificacion = $minutos_incidencia_descuento - $minutos_justificacion;
                $crear_incidencia_nueva = true;
            }else if($minutos_incidencia_descuento < $minutos_justificacion){			
                $minutos_efectivos_justificacion = $minutos_incidencia_descuento;
                $replace_incidencia = 1;
            }else if($minutos_incidencia_descuento == $minutos_justificacion){
                $minutos_efectivos_justificacion = $minutos_justificacion;
                $replace_incidencia = 1;
            }
            $ssql="select asistencia_incidencia_codigo from ca_asistencia_incidencias ";
            $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=154";
			
            $rst= $cn->Execute($ssql);
            $this->asistencia_incidencia_codigo = $rst->fields[0];
			
            $ssql =" update ca_asistencia_incidencias set";
            if($replace_incidencia == 1){
                $ssql.=" incidencia_codigo = 67, ";
            }
            
            $ssql.=" fecha_reg=getdate(), ip_incidencia='" . $ip . "',";
            $ssql.=" tiempo_minutos='" . $minutos_efectivos_justificacion . "',";
            $ssql.=" horas='" . intval($minutos_efectivos_justificacion/60) . "',";
            $ssql.=" minutos='" . intval($minutos_efectivos_justificacion - intval($minutos_efectivos_justificacion/60)*60). "'";
            $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=154";
            $ssql.=" and asistencia_incidencia_codigo=" . $this->asistencia_incidencia_codigo;
			
            $rst = $cn->Execute($ssql);
            if(!$rst){
                $rpta = "Error al justificar incidencia 154";
                $cn->RollbackTrans();
                return $rpta;
            }else{
                $rpta= "OK";
            }
			
            if ($rpta == "OK" && $crear_incidencia_nueva == true){
                $ssql="select isnull(max(asistencia_incidencia_codigo),0)+1 id from ca_asistencia_incidencias ";
                $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
                $rst = null;
                $rst= $cn->Execute($ssql);
                $this->asistencia_incidencia_codigo = $rst->fields[0];

                $ssql="SELECT area_codigo FROM empleado_area ";
                $ssql.= " WHERE Empleado_Codigo=". $this->responsable_codigo . " AND empleado_area_activo=1";
                $rst = null;
                $rst= $cn->Execute($ssql);
                $this->area_codigo_responsable = $rst->fields[0];

                $ssql =" insert into ca_asistencia_incidencias ";
                $ssql.=" (Empleado_codigo, Asistencia_Incidencia_Codigo,Asistencia_Codigo,Incidencia_Codigo,Responsable_Codigo,cod_campana,Tiempo_minutos,horas,minutos,fecha_reg,reg_automatico,area_codigo, area_codigo_responsable, ip_incidencia) ";
                $ssql.=" values('" . $this->empleado_codigo . "', ".$this->asistencia_incidencia_codigo.", " . $this->asistencia_codigo . ", 67," . $this->responsable_codigo . "," ;
                $ssql.=$this->cod_campana . ", " . $minutos_justificacion . ", " . intval($minutos_justificacion/60) . ", " . intval($minutos_justificacion - (intval($minutos_justificacion/60)*60)) . ", ";
                $ssql.="convert(datetime, '" . $this->fecha . "', 103), 0, ";
                $ssql.=$this->area_codigo . "," . $this->area_codigo_responsable . ", '" . $ip . "')";

                $rst= $cn->Execute($ssql);
                if(!$rst){
                    $rpta = "Error al registrar justificacion 67";
                    $cn->RollbackTrans();
                    return $rpta;
                }else{
                    $rpta= "OK";
                }
            }
        }else{
            $rpta = "NO_JUSTIFICACION";
        }
        
            $rst->close();
            $rst=null;	
    }else{
        
        $ssql ="select * ";
        $ssql .=" from ca_asistencia_incidencias ";
        $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
        $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
        $ssql .=" and incidencia_codigo=67";
        $ra = $cn->Execute($ssql);
	
        //actualizamos compensacion
        if (!$ra->EOF){
            if(($this->horas == null || $this->horas == '') and ($this->minutos == null || $this->minutos == '')){
                $this->horas = 1;
                $this->minutos = 0;
            }
            $ssql="select asistencia_incidencia_codigo from ca_asistencia_incidencias ";
            $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=67";
            
            $rb= $cn->Execute($ssql);
            $this->asistencia_incidencia_codigo = $rb->fields[0];		

            $ssql =" update ca_asistencia_incidencias ";
            $ssql.=" set fecha_reg=getdate(), ip_incidencia='" . $ip . "',";
            $ssql.=" tiempo_minutos='" . (($this->horas * 60) + $this->minutos) . "',";
            $ssql.=" horas='" . $this->horas . "',";
            $ssql.=" minutos='" . $this->minutos . "'";
            $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
            $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=67";
            $ssql.=" and asistencia_incidencia_codigo=" . $this->asistencia_incidencia_codigo;

            $rc=$cn->Execute($ssql);
            if(!$rc){
                $rpta = "Error al actualizar incidencia 67";
                $cn->RollbackTrans();
                return $rpta;
            }else{
                $rpta= "OK";
            }
        }else{
            
            if(($this->horas == null || $this->horas == '') and ($this->minutos == null || $this->minutos == '')){
                $this->horas = 1;
                $this->minutos = 0;
            }
            $ssql="SELECT area_codigo FROM empleado_area ";
            $ssql.= " WHERE Empleado_Codigo=". $this->responsable_codigo . " AND empleado_area_activo=1";
            $ru= $cn->Execute($ssql);
            $this->area_codigo_responsable = $ru->fields[0];
			
            $ssql="select isnull(max(asistencia_incidencia_codigo),0)+1 id from ca_asistencia_incidencias ";
            $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
            $re= $cn->Execute($ssql);
            $this->asistencia_incidencia_codigo = $re->fields[0];
			
            $ssql =" insert into ca_asistencia_incidencias ";
            $ssql .=" (Empleado_codigo, Asistencia_Incidencia_Codigo,Asistencia_Codigo,Incidencia_Codigo,Responsable_Codigo,cod_campana,Tiempo_minutos,horas,minutos,fecha_reg,reg_automatico,area_codigo, area_codigo_responsable, ip_incidencia) ";
            $ssql.=" values('" . $this->empleado_codigo . "', " . $this->asistencia_incidencia_codigo . ", " . $this->asistencia_codigo . ", 67,";
            $ssql.=$this->responsable_codigo . "," . $this->cod_campana . ", " . $this->tiempo_minutos . ", " . $this->horas . ", " . $this->minutos . ", ";
            $ssql.="convert(datetime, " . $this->fecha . ", 103), 0, ";
            $ssql.=$this->area_codigo . "," . $this->area_codigo_responsable . ", '" . $ip . "')";
            
            $r=$cn->Execute($ssql);
            if(!$r){
                $rpta = "Error al registrar incidencia 67";
                $cn->RollbackTrans();
                return $rpta;
            }else{
                $rpta= "OK";
            }
        }
    }		
	
    return $rpta;
}

function modificar_incidencia(){
    
    
     
    $rpta="OK";
    $cn=$this->getMyConexionADO();	
    $this->tiempo_minutos=$this->calcular_tiempo($this->horas,$this->minutos);
    //Inserta log de modificacion
    $rpta=$this->Insertar_log_operacion('U');
    if($rpta!='OK'){
        $rpta = "Error al insertar log de modificacion." . $rpta;
        return $rpta;
    }

    $sql =" UPDATE ca_asistencia_incidencias ";
    $sql .=" SET cod_campana=" . $this->cod_campana . ",";
    $sql .="     tiempo_minutos=" . $this->tiempo_minutos . ",";
    $sql .="     responsable_codigo=" . $this->responsable_codigo . "";
    
    if($this->incidencia_hh_dd==1){
        $sql .="  ,horas=" . $this->horas . ",";
        $sql .="   minutos=" . $this->minutos;
    }
    
    $sql .=" where empleado_codigo=?";
    $sql .="       AND asistencia_incidencia_codigo=?";
				
    $params=array(
        $this->empleado_codigo,
        $this->asistencia_incidencia_codigo
    );
                
    $r=$cn->Execute($sql,$params);
    
    if(!$r){
        $rpta = "Error al Modificar Incidencia.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
		
    $text="exec spCA_Recalculo_Hora_Registrada  ". $this->empleado_codigo . ",
            ". $this->asistencia_codigo . "";		
    $r=$cn->Execute($text);
    if(!$r){
        $rpta = "Error al Modificar Incidencia de Horas.";
    }else{
        $rpta= "OK";
    }
		  
    return $rpta;
        
}

function registrar_evento_a_incidencia(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    if ($this->tiempo_derg=='') $this->tiempo_derg='0';
    $ssql="exec spCA_Insertar_Evento_Incidencia  ". $this->empleado_codigo . ",
                                                 ". $this->asistencia_codigo . ",
                                                 ". $this->responsable_codigo . ",
                                                 ". $this->incidencia_codigo . ",
                                                 ". $this->cod_campana . ",
                                                 ". $this->evento_codigo . ",
                                                 ". $this->tiempo_derg . ",'".$this->ip_registro."'";
    
    $result = $cn->Execute($ssql);
    if ($result==false) $sRpta="Error al registrar";   	 
    return $rpta;
}

function Listar_incidencias_asistencia($responsable){
    
   
    $lista="";
    $cn=$this->getMyConexionADO();	
    $ssql="SELECT CA_Asistencia_Incidencias.Empleado_Codigo, ";
    $ssql .="	CA_Asistencia_Incidencias.Asistencia_codigo, ";
    $ssql .="	CA_Asistencia_Incidencias.Incidencia_codigo, ";
    $ssql .="   (convert(varchar(10),CA_Asistencia_Incidencias.fecha_reg,103) + ' ' + convert(varchar(8), CA_Asistencia_Incidencias.fecha_reg,108)), ";
    $ssql .="	CA_Incidencias.Incidencia_descripcion AS incidencia, "; 
    $ssql .="	CA_Asistencia_Incidencias.responsable_codigo, ";
    $ssql .="	CA_Asistencia_Incidencias.asistencia_incidencia_codigo, ";
    $ssql .="	CA_incidencias.incidencia_icono, ";
    $ssql .="	convert(varchar(8),incidencia_hora_inicio, 108) as inicio, ";
    $ssql .="	convert(varchar(8),incidencia_hora_fin, 108) as fin, ";
    $ssql .="   case when len(horas)=1 then '0' + cast(horas as char) else cast(horas as char) end as hora, ";
    $ssql .="   case when len(minutos)=1 then '0' + cast(minutos as char) else cast(minutos as char) end as minutos, ";
    $ssql .="   abs(CA_Incidencias.Incidencia_Editable), ";
    $ssql .="   case when exists(select responsable_codigo from ca_asistencia_responsables ";
    $ssql .="   				 where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo;
    $ssql .="   				 and responsable_codigo=" . $responsable . ") then 1 else 0 end as activo";
    $ssql .="   FROM CA_Asistencia_Incidencias ";
    $ssql .="	INNER JOIN CA_Incidencias ON ";
    $ssql .="   CA_Asistencia_Incidencias.Incidencia_codigo = CA_Incidencias.Incidencia_codigo ";
    $ssql.=  "  WHERE (CA_Asistencia_Incidencias.empleado_Codigo = " . $this->empleado_codigo .") and ";
    $ssql.=  "  (CA_Asistencia_Incidencias.Asistencia_Codigo = " . $this->asistencia_codigo .") and ";
    $ssql.=  "  (CA_Asistencia_Incidencias.Responsable_Codigo = " . $this->responsable_codigo .") ";
    $ssql.=  "  Order by 4";

    
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
		
        $lista .="<table class='FormTable' border='1' width='100%' cellspacing='3' align=center>\n";
        $lista .= "      <tr>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
        $lista .= "	         Incidencia";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
        $lista .= "	         Inicio";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
        $lista .= "	         Fin";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
        $lista .= "	         Tiempo";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	         Modificar ";
        $lista .= "         </td>\n";
        $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
        $lista .= "	         Eliminar ";
        $lista .= "         </td>\n";
        $lista .= "     </tr>\n";		

        while(!$rs->EOF){
            $lista .="<tr >\n";
            $lista .="    <td  width='35%' class='CA_DataTD' align='left'>\n";
            $img=$rs->fields[7];
            if($img !='') $lista .="     <img  src='../images/" . $rs->fields[7]. "' width='15' height='15' border='0'>";
            else $lista .="     <img  src='../images/stop_hand.png' width='15' height='15' border='0'>";
            //$lista .="     <img  src='../images/" .$rs->fields[7]->value . "' width='15' height='15' border='0'>";
            $lista .=" <b>" .$rs->fields[4]. "</b>\n";
            $lista .="	   </td>\n";
            $lista .="    <td  width='10%' class='CA_DataTD' align='left'>\n";
            $lista .=" &nbsp;" .$rs->fields[8]. "\n";
            $lista .="	   </td>\n";
            $lista .="    <td  width='10%' class='CA_DataTD' align='left'>\n";
            $lista .=" &nbsp;" .$rs->fields[9]. "\n";
            $lista .="	   </td>\n";
            $lista .="    <td  width='10%' class='CA_DataTD' align='center'>\n";
            if( $rs->fields[10]!="") $lista .= $rs->fields[10] . ':' . $rs->fields[11];
            else $lista .= "&nbsp;";
            $lista .="	   </td>\n";
            $lista .="     <td width='5%' class='CA_DataTD' align='center'>\n";
            $lista .="        <img onClick='cmdModificar_incidencia_onclick(" . $rs->fields[1] . "," .$rs->fields[6] ."," .$this->empleado_codigo ."," .$this->responsable_codigo ."," . $rs->fields[2] ."," . $rs->fields[12] ."," . $rs->fields[13] .")' src='../images/khexedit.png' width='15' height='15' border='0'  style='cursor:hand;' alt='Modificar Incidencia'>";
            $lista .="	   </td>\n";
            $lista .="     <td width='5%' class='CA_DataTD' align='center'>\n";
            $lista .="        <img onClick='cmdEliminar_onclick(" . $rs->fields[1] ."," . $rs->fields[6] ."," .$this->empleado_codigo ."," .$this->responsable_codigo ."," . $rs->fields[2] ."," . $rs->fields[12] ."," . $rs->fields[13] .")' src='../images/stock_delete.png' width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar Incidencia'>";
            $lista .="	   </td>\n";
            $lista .= "</tr>\n";
            //$lista .= "			lenght<li>Incidencia: " . $rs[4] . " Fecha: " . $rs[3] . "   <img onClick='cmdEliminar_onclick(" . $rs[6] ."," .$this->empleado_codigo ."," .$this->responsable_codigo .")' src='../images/delete.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar Incidencia'></li>\n"; 
            $rs->MoveNext();
        }
	$lista.="		</table>\n";
	}
  $rs->close();
  $rs=null;	
	
 
return $lista;




}
 
function Listar_incidencia_programada(){
$rpta="OK";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){			
    
	$ssql="SELECT CA_Asistencia_Incidencias.Empleado_Codigo, ";
    $ssql .="	CA_Asistencia_Incidencias.Asistencia_codigo, ";
    $ssql .="	CA_Asistencia_Incidencias.Incidencia_codigo, ";
    $ssql .="   (convert(varchar(10),CA_Asistencia_Incidencias.fecha_reg,103) + ' ' + convert(varchar(8), CA_Asistencia_Incidencias.fecha_reg,108)), ";
    $ssql .="	CA_Incidencias.Incidencia_descripcion AS incidencia, "; 
	$ssql .="	CA_Asistencia_Incidencias.asistencia_incidencia_codigo, ";
	$ssql .="	CA_incidencias.incidencia_icono ";
    $ssql .=" FROM CA_Asistencia_Incidencias ";
    $ssql .="	INNER JOIN CA_Incidencias ON ";
    $ssql .="   CA_Asistencia_Incidencias.Incidencia_codigo = CA_Incidencias.Incidencia_codigo ";
	$ssql.=  " WHERE (CA_Asistencia_Incidencias.empleado_Codigo = " . $this->empleado_codigo .") and ";
	$ssql.=  " (CA_Asistencia_Incidencias.Asistencia_Codigo = " . $this->asistencia_codigo .") ";
	$ssql.=  " Order by 4";

	$rs = $this->cnnado->Execute($ssql);
	if(!$rs->EOF()) {
		$lista .="<table  border='0' width='100%' cellspacing='0' align=center>\n";
		while(!$rs->EOF()){
		   $lista .="<tr >\n";
			$lista .="    <td  width='50%' align='right'>\n";
			$lista .="	   Incidencia Programada-><td>\n";
			$lista .="	   </td>\n";
			$lista .="	   <td class='CA_FormHeaderFont'>\n";
			if($rs->fields[6]->value!=""){
				$lista .="     <img  src='../images/" . $rs->fields[6]->value. "' width='15' height='15' border='0'>";
			}else{
			    $lista .="     <img  src='../images/stop_hand.png' width='15' height='15' border='0'>";
			}
			$lista .=" " . $rs->fields[4]->value. "\n";
			$lista .="	   </td>\n";
			$lista .= "</tr>\n";
			$rs->movenext();
		}
	$lista.="		</table>\n";
	}
	$rs->close();
	$rs=null;
	
}	
return $lista;
} 
  
function Query(){

    
    $rpta='OK';
    $cn=$this->getMyConexionADO();

    $ssql="select incidencia_codigo,cod_campana,tiempo_minutos,horas,minutos ";
    $ssql.=" FROM ca_asistencia_incidencias ";
    $ssql.=" WHERE Empleado_Codigo =" . $this->empleado_codigo;
    $ssql.=" and Asistencia_incidencia_codigo=" . $this->asistencia_incidencia_codigo;

    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $this->incidencia_codigo= $rs->fields[0];
        $this->cod_campana= $rs->fields[1];
        $this->tiempo_minutos= $rs->fields[2];
        $this->horas= $rs->fields[3];
        $this->minutos= $rs->fields[4];
    }else{
        $rpta='No Existe Registro de Incidencia';
    }
	  
    $rs->close();
    $rs=null;
    return $rpta;
}  
  
function eliminar_incidencia(){
   
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    //Inserta log de eliminacion
    $rpta=$this->Insertar_log_operacion('D');
    if($rpta!='OK'){
        $rpta = "Error al insertar log de eliminacion." . $rpta;
        return $rpta;
    }
    $ssql ="delete from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=?";
    $ssql .=" and Asistencia_Incidencia_Codigo=?";      
    $params=array(
        $this->empleado_codigo,
        $this->asistencia_incidencia_codigo
    );
    $r=$cn->Execute($ssql,$params);
    if(!$r){
        $rpta = "Error al Eliminar incidencia.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    $text="exec spCA_Recalculo_Hora_Registrada  ". $this->empleado_codigo . ",
            ". $this->asistencia_codigo . "";
    $r=$cn->Execute($text);
    if(!$r){
        $rpta = "Error al Actualizar Incidencia de Horas Registradas.";
    }else{
        $rpta= "OK";
    }
    return $rpta;
}


function obtener_codigo_supervisor(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $responsable="0";
    
    
        $ssql="select responsable_codigo from CA_Asistencia_Responsables ";
        $ssql.="where empleado_codigo = ".$this->empleado_codigo." and asistencia_codigo = ".$this->asistencia_codigo." ";
        
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
             $responsable = $rs->fields[0];
        }
        $rs->close();
        $rs=null;
    
    
    return $responsable;
    
}



function Obtener_servicio_empleado(){

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="select empleado_servicio.cod_campana, ";
    $ssql .="  v_campanas.exp_codigo + '-' + v_campanas.exp_nombrecorto + '(' + convert(varchar,v_campanas.cod_campana) + ')' as campana "; 
    $ssql .=" from empleado_servicio inner join v_campanas ";
    $ssql .=" 		on empleado_servicio.cod_campana=v_campanas.cod_campana ";
    $ssql .=" where empleado_servicio.Empleado_Codigo= ".$this->empleado_codigo."";
    $ssql .=" and empleado_servicio.empleado_servicio_activo=1";
    
    
    $rs = $cn->Execute($ssql);
    if ($rs->RecordCount()>0){
        $this->cod_campana= $rs->fields[0];
        $this->campana =$rs->fields[1]; 
    }else{
        $this->cod_Campana='0';
        $this->campana='';
    }
    //$rs->close();
    $rs=null; 
    return $rpta;
    
}

  
function calcular_tiempo($horas,$minutos){
	$tiempo=0;
	$tiempo=$horas*60 + $minutos;
	return $tiempo;
}

function Insertar_incidencia_diaria(){
           
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="select isnull(max(asistencia_incidencia_codigo),0)+1 id from ca_asistencia_incidencias ";
    $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
    $rs= $cn->Execute($ssql);
    $this->asistencia_incidencia_codigo = $rs->fields[0];
		
    $ssql="SELECT area_codigo FROM empleado_area ";
    $ssql.= " WHERE Empleado_Codigo = " . $this->empleado_codigo . " AND empleado_area_activo= 1";
    $rsa= $cn->Execute($ssql);
    $this->area_codigo = $rsa->fields[0];
    $ssql="SELECT area_codigo FROM empleado_area ";
    $ssql.= " WHERE Empleado_Codigo=". $this->responsable_codigo . " AND empleado_area_activo=1";
    $rsa= $cn->Execute($ssql);
    $this->area_codigo_responsable = $rsa->fields[0];
		        
    $sql =" insert into ca_asistencia_incidencias ";
    $sql .=" (Empleado_codigo, Asistencia_Incidencia_Codigo,Asistencia_Codigo,Incidencia_Codigo,Responsable_Codigo,cod_campana,Tiempo_minutos,horas,minutos,fecha_reg,reg_automatico,area_codigo, area_codigo_responsable, ip_incidencia) ";
    $sql .=" select " . $this->empleado_codigo . "," . $this->asistencia_incidencia_codigo. "," . $this->asistencia_codigo . "," . $this->incidencia_codigo . ",";
    $sql .=" " .$this->responsable_codigo . "," . $this->cod_campana . ",";
    $sql .=" case when turno_duo=0 then (turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio) ";
    $sql .=" else  (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin)  ";
    $sql .=" end as tiempo,  ";
    $sql .=" case when turno_duo=0 then cast(((turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio))/60   as int) "; 
    $sql .=" else  cast( ( (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin) )/60 as int) ";
    $sql .=" end as horas, ";
    $sql .=" case when turno_duo=0 then  ";
    $sql .=" ((turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio)) - ( ";
    $sql .=" cast(((turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio))/60   as int)*60  ) ";
    $sql .=" else ( (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin)) - ( ";
    $sql .=" cast( ( (24 * 60) -(turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin) )/60 as int)*60 ) ";
    $sql .=" end as minutos, ";
    $sql .=" getdate(),0,".$this->area_codigo.", ".$this->area_codigo_responsable.",'".$this->ip_registro."' ";
    $sql .=" from ca_asistencias a inner join ";
    $sql .=" ca_turnos t on a.turno_codigo = t.turno_codigo ";
    $sql .=" where a.Empleado_Codigo=" . $this->empleado_codigo . " AND a.asistencia_codigo=" . $this->asistencia_codigo . "";
		
    $r=$cn->Execute($sql);
    if(!$r){
        $rpta =" Error al Insertar Incidencia!.";
        $cn->RollbackTrans();
    }else{
        $rpta= "OK";
    }
    
    $rs->close();
    $rsa->close();

    $rs=null;
    $rsa=null;
		  	      		  
    return $rpta;
}

//marcacion2
function Insertar_incidencia_horaria($ip_entrada){
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    if($this->incidencia_codigo==157 || $this->incidencia_codigo==4 || $this->incidencia_codigo==67 || $this->incidencia_codigo == 42 || $this->incidencia_codigo==2 || $this->incidencia_codigo==79 || $this->incidencia_codigo == 166 ){
        if($this->incidencia_codigo==4){
            $rpta=$this->Justificar_Incidencia($ip_entrada,154,4);
        }else if($this->incidencia_codigo==157){
            $rpta=$this->Justificar_Incidencia($ip_entrada,154,157);
        }
        if($this->incidencia_codigo==2){
            $rpta=$this->Justificar_Incidencia($ip_entrada,3,2);    
        }
        return $rpta;
    }else{
        //incidencia_codigo = 24
        $ssql="select isnull(max(asistencia_incidencia_codigo),0)+1 id from ca_asistencia_incidencias ";
        $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
        //echo $this->empleado_codigo;exit(0);
        $rs= $cn->Execute($ssql);
        
        $this->asistencia_incidencia_codigo = $rs->fields[0];	
        $this->tiempo_minutos=$this->calcular_tiempo($this->horas,$this->minutos);
        //echo $this->tiempo_minutos;exit(0);
        //$this->tiempo_minutos = 122
        if($this->incidencia_codigo==66 || $this->incidencia_codigo==67){
            $this->tiempo_minutos=60;
            $this->horas=1;
            $this->minutos=0;
        }
				 
        //--obtener areas
        $ssql="SELECT area_codigo FROM CA_Asistencias ";
        $ssql.= " WHERE Empleado_Codigo = " . $this->empleado_codigo . " AND Asistencia_codigo = " . $this->asistencia_codigo;
        $rsa= $cn->Execute($ssql);
        //echo $this->empleado_codigo."-".$this->asistencia_codigo;exit(0);
        $this->area_codigo = $rsa->fields[0];
				
        $ssql="SELECT area_codigo_responsable ";
        $ssql.=" FROM  CA_Asistencia_Responsables ";
        $ssql.= " WHERE Empleado_Codigo = " . $this->empleado_codigo . " AND Asistencia_codigo = " . $this->asistencia_codigo . " AND responsable_codigo = " . $this->responsable_codigo;
        //echo $this->empleado_codigo."-".$this->asistencia_codigo."-".$this->responsable_codigo;
        //exit(0);
        
        $rsa= $cn->Execute($ssql);
        $this->area_codigo_responsable = $rsa->fields[0];
        
        $sql =" insert into ca_asistencia_incidencias ";
        $sql .=" (Empleado_codigo, Asistencia_Incidencia_Codigo,Asistencia_Codigo,Incidencia_Codigo,Responsable_Codigo,cod_campana,Tiempo_minutos,horas,minutos,fecha_reg,reg_automatico,area_codigo, area_codigo_responsable,ip_incidencia ) ";
        $sql .=" values(?,?,?,?,?,?,?,?,?,getdate(),?,?,?,?)";

        $params=array(
            $this->empleado_codigo,
            $this->asistencia_incidencia_codigo,
            $this->asistencia_codigo,
            $this->incidencia_codigo,
            $this->responsable_codigo,
            $this->cod_campana,
            $this->tiempo_minutos,
            $this->horas,
            $this->minutos,
            0,
            $this->area_codigo,
            $this->area_codigo_responsable,
            $this->ip_registro
        );
        			
        $r=$cn->Execute($sql,$params);
        if(!$r){
            $rpta =" Error al Insertar Incidencia.";
           $cn->RollbackTrans();
        }else{
            $rpta= "OK";
        }
				  
        $text="exec spCA_Recalculo_Hora_Registrada  ? , ? ";
        $params=array(
            $this->empleado_codigo,
            $this->asistencia_codigo
        );
        				
        $r=$cn->Execute($text,$params);
        if(!$r){
            $rpta = "Error al Actualizar Incidencia de Horas Registradas.";
            $cn->RollbackTrans();
        }else{
            $rpta= "OK";
        }
        
        $rs->close();
        $rsa->close();

        $rs=null;
        $rsa=null;
        				
        return $rpta;
    }
    
}	    

function Modificar_incidencia_falta(){
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $sql =" UPDATE ca_asistencia_incidencias ";
    $sql .=" SET incidencia_codigo=" . $this->incidencia_codigo  . ",";
    $sql .=" cod_campana=" . $this->cod_campana . ",";
    $sql .=" tiempo_minutos = case when turno_duo=0 ";
    $sql .=" then (turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio) ";
    $sql .=" else  (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin) ";
    $sql .=" end , "; 
    $sql .=" horas = case when turno_duo=0 ";
    $sql .=" then cast(((turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio))/60   as int) ";  
    $sql .=" else  cast( ( (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin) )/60 as int) ";
    $sql .=" end, ";
    $sql .=" minutos = case when turno_duo=0 ";
    $sql .=" then ((turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio)) - ( ";
    $sql .=" cast(((turno_hora_fin*60 + turno_minuto_fin)- (turno_hora_inicio*60 + turno_minuto_inicio))/60   as int)*60  ) ";
    $sql .=" else ( (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin)) - ( ";
    $sql .=" cast( ( (24 * 60) - (turno_hora_inicio*60 + turno_minuto_inicio) + (turno_hora_fin*60 + turno_minuto_fin) )/60 as int)*60 ) "; 
    $sql .=" end, ";
    $sql .=" responsable_codigo=".$this->responsable_codigo." ,ip_incidencia='".$this->ip_registro."' ";
    $sql .=" from ca_asistencia_incidencias ai inner join ";
    $sql .=" ca_asistencias a on a.empleado_codigo= ai.empleado_codigo and a.asistencia_codigo= ai.asistencia_codigo inner join ";
    $sql .=" ca_turnos t on a.turno_codigo = t.turno_codigo ";
    $sql .=" where ai.empleado_codigo=" . $this->empleado_codigo;
    $sql .="       AND ai.asistencia_incidencia_codigo=" . $this->asistencia_incidencia_codigo;
    
    $r=$cn->Execute($sql);
    
    if(!$r){
        $rpta = "Error al Modificar Incidencia.";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    return $rpta;
    
 }

function Falta_justificada($ip){
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    //si existe incidencia de tardanza eliminarla
    $ssql ="select * ";
    $ssql .=" from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .=" and incidencia_codigo=38 ";
        
    $rst = $cn->Execute($ssql);
    if (!$rst->EOF){
        $ssql =" update ca_asistencia_incidencias ";
        $ssql.=" set incidencia_codigo=151, fecha_reg=getdate(), ip_incidencia='" . $ip . "'";
        $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
        $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=38 ";
        
        $r=$cn->Execute($ssql);
        if(!$r){
            $rpta = "Error al actualizar falta.";
            $cn->RollbackTrans();
            return $rpta;
        }else{
            $rpta= "OK";
        }
    }else{
        $rpta= "NO_FALTA";
    }
    $rst->close();
    $rst=null;
    return $rpta; 
}

function Falta_Justificada_Incidencia($nueva_incidencia){
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    //si existe incidencia de tardanza eliminarla
    $ssql ="select * ";
    $ssql .=" from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .=" and incidencia_codigo=38 ";
    $rst = $cn->Execute($ssql);
    
    if (!$rst->EOF){ //validamos si existe la falta
        $ssql =" update ca_asistencia_incidencias ";
        $ssql.=" set incidencia_codigo= ".$nueva_incidencia;
        $ssql.=" where empleado_codigo=" . $this->empleado_codigo;
        $ssql.=" and asistencia_codigo=" . $this->asistencia_codigo . " and incidencia_codigo=38 ";
        
        $r=$cn->Execute($ssql);
        if(!$r){
            $rpta = "Error al actualizar falta.";
            $cn->RollbackTrans();
            return $rpta;
        }else{
            $rpta= "OK";
        }
    }else{
        $rpta= "NO_FALTA";
    }
    
    $rst->close();
    $rst=null;
    
    return $rpta; 
    
}

function Justificar_asistencia_diaria(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="SELECT ct.turno_duo ";
    $ssql .=" FROM  CA_Asistencias c ";
    $ssql .=" inner join  ca_turnos ct on ct.turno_codigo=c.turno_codigo ";
    $ssql .= " WHERE c.Empleado_Codigo = " . $this->empleado_codigo . " AND c.Asistencia_codigo = " . $this->asistencia_codigo;
    $rs= $cn->Execute($ssql);
    $duo= $rs->fields[0];
    $sql =" update ca_asistencias set asistencia_entrada=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_inicio AS varchar(2)) + ':' + cast(ct.turno_minuto_inicio AS varchar(2)), 103), ";
    $sql .=" fecha_reg_entrada=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_inicio AS varchar(2)) + ':' + cast(ct.turno_minuto_inicio AS varchar(2)), 103), ";
    if ($duo==0){
        $sql .="  asistencia_salida=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_fin AS varchar(2)) + ':' + cast(ct.turno_minuto_fin AS varchar(2)), 103), ";
        $sql .="   fecha_reg_salida=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_fin AS varchar(2)) + ':' + cast(ct.turno_minuto_fin AS varchar(2)), 103) ";
    }
    
    if ($duo==1){
        $sql .="  asistencia_salida=dateadd(day,1,CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_fin AS varchar(2)) + ':' + cast(ct.turno_minuto_fin AS varchar(2)), 103)), ";
        $sql .="  fecha_reg_salida=dateadd(day,1,CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_fin AS varchar(2)) + ':' + cast(ct.turno_minuto_fin AS varchar(2)), 103)) ";
    }
    
    $sql .=" from ca_asistencias c ";
    $sql .=" inner join  ca_turnos ct on ct.turno_codigo=c.turno_codigo ";
    $sql .=" where c.Empleado_Codigo=" . $this->empleado_codigo . " and c.Asistencia_Codigo=" . $this->asistencia_codigo . "";

    $r=$cn->Execute($sql);
    if(!$r){
        $rpta = "Error al justificar asistencia.";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    //si existe incidencia de tardanza eliminarla
    $ssql ="select * ";
    $ssql .=" from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .=" and incidencia_codigo=7 ";
					
    $rst = $cn->Execute($ssql);
    if (!$rst->EOF){

        $ssql ="delete from ca_asistencia_incidencias ";
        $ssql .=" where Empleado_Codigo=? ";
        $ssql .=" and asistencia_codigo=? ";
        $ssql .=" and incidencia_codigo=7 ";
        $params=array(
          $this->empleado_codigo,
          $this->asistencia_codigo
        );

        $r=$cn->Execute($ssql,$params);

        if(!$r){
            $rpta = "Error al Eliminar incidencia.";
            $cn->RollbackTrans();
            return $rpta;
        }else{
            $rpta= "OK";
        }
    }
    
    $rs->close();
    $rst->close();
    $rs=null;
    $rst=null;
    
    return $rpta;
    
}

function Justificar_hora_entrada_maternidad(){
    
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="update ca_asistencias ";
    $ssql .=" set asistencia_entrada=dateadd(mi, -60, asistencia_entrada) ";
    $ssql .=" from ca_asistencias c ";
    $ssql .=" inner join  ca_turnos ct on ct.turno_codigo=c.turno_codigo ";
    $ssql .=" where c.Empleado_Codigo=" . $this->empleado_codigo . " and c.Asistencia_Codigo=" . $this->asistencia_codigo;
  
    $re=$cn->Execute($ssql);
    if(!$re){
        $rpta =" Error al insertar incidencia";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }				
    //si existe incidencia de tardanza, restarle 60 minutos
    $ssql ="select asistencia_incidencia_codigo, tiempo_minutos, horas, minutos ";
    $ssql .=" from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .=" and incidencia_codigo=7 ";
					
	$rst = $cn->Execute($ssql);
    if (!$rst->EOF){
        if ($rst->fields[1] >60){
            //Si tiene mas de 60 minutos de tardanzas, actualizarlo.
            $tminutos= $rst->fields[1] - 60;
            $thoras=intval($tminutos/60);
            $tmints=($tminutos % 60);

            $ssql="update ca_asistencia_incidencias set "; 
            $ssql .="   tiempo_minutos=" . $tminutos;
            $ssql .="   , horas=" . $thoras;
            $ssql .="   , minutos=" . $tmints;
            $ssql .="   , ip_incidencia='" . $this->ip_registro . "'";
            $ssql .="  where Empleado_Codigo= ? ";
            $ssql .=" 	and asistencia_incidencia_codigo=? "; 
            $ssql .="   and asistencia_codigo= ? ";
            $ssql .="   and incidencia_codigo=7";

            $params=array(
                $this->empleado_codigo,
                $rst->fields[0],
                $this->asistencia_codigo
            );

            $r=$cn->Execute($ssql,$params);

            if(!$r){
                $rpta = "Error al Modificar Tardanza.";
                $cn->RollbackTrans();
                return $rpta;
            }
		  
        }else{
            // si tiene menos de 60 minutos de tardanza, Eliminar
            
            $ssql ="delete from ca_asistencia_incidencias ";
            $ssql .=" where Empleado_Codigo=? ";
            $ssql .=" and asistencia_codigo=? ";
            $ssql .=" and incidencia_codigo=7 ";
            
            $params=array(
                $this->empleado_codigo,
                $this->asistencia_codigo
            );
            
            $r=$cn->Execute($ssql,$params);

            if(!$r){
                $rpta="Error al Eliminar Tardanza.";
                $cn->RollbackTrans();
                return $rpta;
            }
        }				
	  //$this->cnnado->CommitTrans();
    }
    $rpta= "OK";
    $rst->close();
    $rst=null;
    return $rpta;
    
} 

function Justificar_tardanza($ip){

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "exec spCA_MODIFICA_INCIDENCIA_GAP ?, ?, ?, ?, ?";
    
    $params=array(
        $this->empleado_codigo,
        $this->asistencia_codigo,
        7,
        150,
        $ip
    );
    
    $r=$cn->Execute($ssql,$params);	
    $resultado = $r->fields[0];	
	
    if(!$r){
        $rpta = "Error al actualizar incidencia.";
        echo $rpta;
        $cn->RollbackTrans();
        return $rpta;
    }else{
        if($resultado != 1) $rpta = "NO_TARDANZA";
    }
    
    $r->close();
    $r=null;
    return $rpta;
}

/* Autor : Banny Solano                 */
/* Si existe la incidencia devuelve OK  */
/* 27/09/2012                           */
function existe_incidencia($incidencia){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $params = array(
                $this->empleado_codigo,
                $this->asistencia_codigo,
                $incidencia
                );
    $ssql ="SELECT asistencia_incidencia_codigo, tiempo_minutos, horas, minutos 
            FROM ca_asistencia_incidencias 
            WHERE Empleado_Codigo= ? and asistencia_codigo= ? and incidencia_codigo= ?";
    $rsa = $cn->Execute($ssql, $params);
    if($rsa->RecordCount() > 0){//existe la incidencia
        $tiempo = array();
        $tiempo["horas"] = $rsa->fields[2];
        $tiempo["minutos"] = $rsa->fields[3];                
        return $tiempo;
    }else{
        return false;
    }
}


function valida_incidencia_extra(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="select asistencia_incidencia_codigo, tiempo_minutos, horas, minutos ";
    $ssql .=" from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .=" and incidencia_codigo=7 ";
    $rsa = $cn->Execute($ssql);
    if($rsa->RecordCount() > 0){
        if( ($rsa->fields[2])*1 == 0 ) //si la tardanza no supera al menos 1 hora
            return "NO_SUPERA_HORA"; 
        else
            return "OK";
    }else{
        return "OK";
    }
}

function Justificar_entrada_asistencia(){

     
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    //fin agregado
    
    $ssql ="update ca_asistencias ";
    $ssql .=" set asistencia_entrada=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_inicio AS varchar(2)) + ':' + cast(ct.turno_minuto_inicio AS varchar(2)), 103), ";
    $ssql .=" fecha_reg_entrada=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_inicio AS varchar(2)) + ':' + cast(ct.turno_minuto_inicio AS varchar(2)), 103) ";
    $ssql .=" from ca_asistencias c ";
    $ssql .=" inner join  ca_turnos ct on ct.turno_codigo=c.turno_codigo ";
    $ssql .=" where c.Empleado_Codigo=" . $this->empleado_codigo . " and c.Asistencia_Codigo=" . $this->asistencia_codigo;
    $re=$cn->Execute($ssql);
    
    if(!$re){
        $rpta =" Error al insertar incidencia";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }				
    //si existe incidencia de tardanza eliminarla
    //si existe incidencia de tardanza, restarle 60 minutos
    
    $ssql ="select * ";
    $ssql .=" from ca_asistencia_incidencias ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo . "";
    $ssql .=" and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .=" and incidencia_codigo=7 ";
					
    $rst = $cn->Execute($ssql);
    if (!$rst->EOF){
        if($this->incidencia_codigo == 42 || $this->incidencia_codigo == 79 || $this->incidencia_codigo == 166 ){ 
            //modifico la tardanza por gestion fuera de empresa de inicio de turno
            $ssql ="UPDATE ca_asistencia_incidencias SET incidencia_codigo = ".$this->incidencia_codigo;
            $ssql .=" where Empleado_Codigo=? ";
            $ssql .=" and asistencia_codigo=? ";
            $ssql .=" and incidencia_codigo=7 ";
        }else{
            //elimino la tardanza
            $ssql ="delete from ca_asistencia_incidencias ";
            $ssql .=" where Empleado_Codigo=? ";
            $ssql .=" and asistencia_codigo=? ";
            $ssql .=" and incidencia_codigo=7 ";
        }
        $params=array(
            $this->empleado_codigo,
            $this->asistencia_codigo
        );
        $r=$cn->Execute($ssql,$params);
        if(!$r){
            $rpta = "Error al Eliminar incidencia.";
            $cn->RollbackTrans();
            return $rpta;
        }else{
            $rpta= "OK";
        }
    }
    $rst->close();
    $rst=null;	
    return $rpta;
    
} 


function Justificar_salida_asistencia($ip_salida){

    
    $rpta="OK"; 
    $cn=$this->getMyConexionADO();
    $ssql="SELECT ct.turno_duo ";
    $ssql .=" FROM  CA_Asistencias c ";
    $ssql .=" inner join  ca_turnos ct on ct.turno_codigo=c.turno_codigo ";
    $ssql .= " WHERE c.Empleado_Codigo = " . $this->empleado_codigo . " AND c.Asistencia_codigo = " . $this->asistencia_codigo;
    $rs= $cn->Execute($ssql);
    $duo= $rs->fields[0];
    $ssql ="update ca_asistencias ";
    if ($duo==0) $ssql .=" set asistencia_salida=CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_fin AS varchar(2)) + ':' + cast(ct.turno_minuto_fin AS varchar(2)), 103), ";
    if ($duo==1) $ssql .=" set asistencia_salida=dateadd(day,1,CONVERT(datetime,'" . $this->fecha . "'  + ' ' + cast(ct.turno_hora_fin AS varchar(2)) + ':' + cast(ct.turno_minuto_fin AS varchar(2)), 103)), ";
    $ssql .="    fecha_reg_salida=getdate(),ip_salida='" . $ip_salida . "' ";
    $ssql .=" from ca_asistencias c ";
    $ssql .=" inner join  ca_turnos ct on ct.turno_codigo=c.turno_codigo ";
    $ssql .=" where c.Empleado_Codigo=" . $this->empleado_codigo . " and c.Asistencia_Codigo=" . $this->asistencia_codigo;
    $r=$cn->Execute($ssql);
    if(!$r){
        $rpta =" Error al justificar salida asistencia";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    $rs->close();
    $rs=null;
    
}

function Insertar_log_operacion($operacion){
    
    
    $cn=$this->getMyConexionADO();    
    $rpta="OK";
    
    $sql =" INSERT INTO CA_Log(Empleado_codigo, Asistencia_Incidencia_Codigo,Asistencia_Codigo,Operacion,Incidencia_Codigo,Responsable_Codigo,Tiempo_minutos,cod_campana,fecha_reg) ";
    $sql .=" SELECT empleado_codigo,asistencia_incidencia_codigo,asistencia_codigo,'" . $operacion . "', ";
    $sql .=" incidencia_codigo," . $this->responsable_codigo  . ",tiempo_minutos,cod_campana,getdate()";
    $sql .=" from ca_asistencia_incidencias";
    $sql .=" where empleado_codigo=?";
    $sql .="       AND asistencia_incidencia_codigo=?";

    $params=array(
        $this->empleado_codigo,
        $this->asistencia_incidencia_codigo
    );

    $r=$cn->Execute($sql,$params);
    if(!$r){
        $rpta = "Error al Insertar Log.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    return $rpta;

 }

function Listar_personal_batch(){
    $rpta="OK";
    $lista="";
    $cn=$this->getMyConexionADO();
    
    $obj = new ca_asistencia();
    $obj->setMyUrl(db_host());
    $obj->setMyUser(db_user());
    $obj->setMyPwd(db_pass());
    $obj->setMyDBName(db_name());
    
    $ssql="exec spCA_Listar_Personal " . $this->responsable_codigo .", '" . $this->fecha ."'";
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        $reg="";
        $inc="";
        while(!$rs->EOF){
            $reg = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg.="    <td align='center' >\n";
            $reg.="      <input type='checkbox' id='chk_".$rs->fields[0]."' name='chk_".$rs->fields[0]."' value='".$rs->fields[0]."' >\n";
            $reg.="	  </td>\n";
            $reg.="    <td  align='center'>\n";
            $reg.=" " . $rs->fields[0]."\n";
            $reg.= "	  </td>\n";
            $reg.="    <td    align='left'>\n";
            $reg.=" " . $rs->fields[1]."\n";
            $reg.= "	   </td>\n";
            //N
            $t_disponible="";
            $obj->empleado_codigo=$rs->fields[0];
            $obj->asistencia_fecha=$this->fecha;
            $obj->saldo_tiempo(2);
            if($obj->saldo_tiempo!=999999 && $obj->saldo_tiempo!=0){
                $t_disponible=$obj->tiempo_disponible($obj->saldo_tiempo);
            }else if($obj->saldo_tiempo==0){
                $t_disponible="0:0";
            }
            $reg.="    <td    align='left'>\n";
            $reg.=" " . $t_disponible."\n";
            $reg.= "	   </td>\n";
            //N
            $reg.="  </tr>\n";
            $rs->MoveNext();
            $lista .= $reg;
        } // end while

      }
    $rs->close();
    $rs=null;
    return $lista;
}

function Lista_justificable(){
    $rpta = "";
    $cn=$this->getMyConexionADO();
	  
    $sql.=" SELECT CA_Asistencias.Empleado_Codigo, ";
    $sql.=" Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres as Empleado, e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres as Responsable, ";
    $sql.=" CA_Asistencia_Incidencias.Responsable_Codigo,CA_Asistencias.Asistencia_codigo, ";
    $sql.="  CONVERT(varchar(10),CA_Asistencias.Asistencia_fecha, 103) , ";
    $sql.=" CA_Asistencia_Incidencias.Cod_Campana,Areas.Area_Descripcion "; 
    $sql.=" FROM CA_Asistencias "; 
    $sql.=" INNER JOIN CA_Asistencia_Incidencias ON CA_Asistencias.Empleado_Codigo = CA_Asistencia_Incidencias.Empleado_Codigo ";
    $sql.="                                     AND CA_Asistencias.Asistencia_codigo = CA_Asistencia_Incidencias.Asistencia_codigo "; 
    $sql.=" INNER JOIN CA_Asignacion_Empleados ON  CA_Asignacion_Empleados.Empleado_Codigo = CA_Asistencias.Empleado_Codigo "; 
    $sql.=" INNER JOIN Empleado_Area ON CA_Asistencias.Empleado_Codigo = Empleado_Area.Empleado_Codigo "; 
    $sql.=" INNER JOIN Empleados ON Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo "; 
    $sql.=" INNER JOIN Areas ON Areas.Area_Codigo = Empleado_Area.Area_Codigo "; 
    $sql.=" INNER JOIN Empleados e ON CA_Asistencia_Incidencias.Responsable_Codigo = e.Empleado_Codigo "; 
    $sql.=" WHERE (CA_Asistencias.Asistencia_fecha = CONVERT(DATETIME,'" . $this->fecha . "', 103)) "; 
    $sql.=" AND (CA_Asistencia_Incidencias.Incidencia_codigo = 38) AND (Empleado_Area.Empleado_Area_Activo = 1) "; 
    $sql.=" AND (CA_Asignacion_Empleados.Responsable_Codigo =" . $this->responsable_codigo . ")"; 
    $sql.=" AND (CA_Asignacion_Empleados.asignacion_activo=1)  ";
    $sql.=" Order by Empleado,CA_Asistencias.Empleado_Codigo  ";
	    
    $rs= $cn->Execute($sql);
    if (!$rs->EOF){	
        while(!$rs->EOF){	
            $lista .="<tr >\n";
            $lista .="   <TD class='ca_datatd' align='center'>\n"; 
            $lista .="   <INPUT type=CHECKBOX align=center id='chk' name='chk' value='". $rs->fields[0] . "_" . $rs->fields[4] . "' onclick='check()'>";
            $lista .="    </TD>\n";
            $lista .="	   <td class='ca_datatd' align='left'><b>\n";
            $lista .="	   " . $rs->fields[0] . "</b></td>\n"; //$rs[0]
            $lista .="	   <td class='CA_DataTD'><b>\n";
            $lista .=" " . $rs->fields[1] . "\n"; //$rs[1]
            $lista .="	   </b></td>\n";
            $lista .="	   <td class='CA_DataTD'>\n";
            $lista .=" " . $rs->fields[5]. "\n"; //$rs[5]
            $lista .="	   </td>\n"; 
            $lista .="	   <td class='CA_DataTD'>\n";
            $lista .=" " . $rs->fields[2]. "\n"; //$rs[2]
            $lista .="\n	   </td>\n";
            $lista .="	   <td class='CA_DataTD'>\n";
            $lista .=" " . $rs->fields[7]. "\n"; //$rs[7]
            $lista .="\n	   </td>\n";
            $lista .="	  <input type='hidden' id='responsable_" . $rs->fields[0]. "_" . $rs->fields[4]. "' name='responsable_" . $rs->fields[0]. "_" . $rs->fields[4] . "' value='" . $rs->fields[3]. "'>\n";
            $lista .="	  <input type='hidden' id='cod_campana_" . $rs->fields[0]. "_" . $rs->fields[4]. "' name='cod_campana_" . $rs->fields[0]. "_" . $rs->fields[4] . "' value='" . $rs->fields[6] . "'>\n";
            $lista .= "</tr>\n";
            $rs->MoveNext();
        }
	//}
    }else{
            $lista .="<tr >\n";
            $lista .="   <TD class='ca_datatd' align='center' colspan='6'>\n"; 
            $lista .="   <b>No existen registros en este momento!!</b>";
            $lista .="    </TD>\n";
            $lista .= "</tr>\n";
    }
	
    return $lista;
  
}  
         
function Listar_personal_asiste(){
    

  
    
    $rpta="OK";
    $lista="";
    $cn=$this->getMyConexionADO();
      
    $ssql="exec spCA_Listar_Personal_extension " . $this->responsable_codigo .", '" . $this->fecha ."'";
    
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
        $reg="";
        $inc="";
        while(!$rs->EOF){
            $reg = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg .="    <td align='center' >\n";
            if ($rs->fields[3]=='Si'){
                $reg .="      <input type='checkbox' id='chk_" . $rs->fields[0] . "' name='chk_" . $rs->fields[0] . "' value='" . $rs->fields[0] . "' checked >\n";
            }else{
                $reg .="      <input type='checkbox' id='chk_" . $rs->fields[0] . "' name='chk_" . $rs->fields[0] . "' value='" . $rs->fields[0] . "' >\n";
            }
            
            $reg .="      <input type='hidden' id='hdd_" . $rs->fields[0] . "' name='hdd_" . $rs->fields[0] . "' value='" . $rs->fields[6] . "'>\n";
            
            $reg .="	  </td>\n";
            $reg .="    <td align='center'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .= "	  </td>\n";
            $reg .="    <td align='left'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .= "	   </td>\n";
            $reg .="    <td align='center'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .= "	  </td>\n";
            $reg .="    <td align='center'><input type='text' id='txt_".$rs->fields[0]."' ";
            $reg .=" class='input' onkeyup='this.value=solodecimal(this.value);validar(this.id);' size=5 ";
            $reg .=" onblur=validar1(this.id) alt='". $rs->fields[4]."' ";
            if ($rs->fields[3]=='Si'){
                $reg .=" value='".$rs->fields[4]."'> \n";
            }else{
                $reg .=" value='".$this->extension_tiempo."'> \n";
            }
            $reg .= "	  </td>\n";
            $reg .="    <td align='center'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .= "	  </td>\n";
            
            $reg .="    <td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .= "	  </td>\n";
            
            $reg .="  </tr>\n";
            $rs->MoveNext();
            $lista .= $reg;	
        } // end while
		
    }
    $rs->close();
    $rs=null;
    return $lista;
  
}

function Registrar_ET(){
	
    
    $rpta="";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $ssql = "exec spCA_REGISTRAR_EXTENSION_TURNO ?, 1, " . $this->extension_tiempo.",?";
    $params=array($this->empleado_codigo,$this->empleado_tipo_pago);
    $rs=$cn->Execute($ssql,$params);
    
    if(!$rs->EOF){
        $rpta=$rs->fields[0];
    }
    
    return $rpta;
    
}

function Eliminar_ET(){
	
    
    $rpta="";
    $cn=$this->getMyConexionADO();
    
    $ssql = "exec spCA_REGISTRAR_EXTENSION_TURNO ?, 0, 0,?";
    $params=array($this->empleado_codigo,$this->empleado_tipo_pago);
    $rs=$cn->Execute($ssql,$params);
    
    if(!$rs->EOF){
        $rpta=$rs->fields[0];
    }
    
    return $rpta;
    
}

    function Verifica_Plataforma_Avaya(){
    
        
        
        $rpta="OK";
        $cn=$this->getMyConexionADO();
    
        $ssql =" select * ";
        $ssql .=" from v_campanas_plataforma "; 
        $ssql .=" where cod_campana in (select cod_campana ";
        $ssql .=" from v_campanas ";
        $ssql .=" where exp_activo=1 and coordinacion_codigo=" . $this->area_codigo . " ) ";
        $ssql .=" and plataforma_codigo in (1,2,5)";		
        $rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
            $rpta='S';
        }else{
            $rpta='N';
        }
        
        $rs->close();
        $rs=null;
        
        return $rpta;
        
    }
    
    /*  CREADO POR  : BANNY SOLANO*/
    /*  FECHA       : 20/06/2013*/
    /*  OBJETIVO    : DESCATIVA LA ASISTENCIA Y GENERA UNA NUEVA A PARTIR DE ESTA EN CA_ASISTENCIA*/
    function Desactivar_Asistencia(){
        $rpta="OK";
        $cn=$this->getMyConexionADO();
        
        $params = array($this->empleado_codigo, $this->asistencia_codigo);
        $sql = "UPDATE CA_ASISTENCIAS
                SET CA_ESTADO_CODIGO = 2
                WHERE EMPLEADO_CODIGO = ? AND ASISTENCIA_CODIGO = ?";
        $rs = $cn->Execute($sql, $params);
    }
    /*  CREADO POR  : BANNY SOLANO*/
    /*  FECHA       : 20/06/2013*/
    /*  OBJETIVO    : INserta LA ASISTENCIA previa desactivacion de la anterior*/
    function Insertar_Nueva_Asistencia(){
        $rpta="OK";
        $cn=$this->getMyConexionADO();
        
        /*desactivamos la asistencia*/
        $this->Desactivar_Asistencia();
        
        $params = array($this->empleado_codigo);
        $sql =" SELECT ISNULL(MAX(asistencia_codigo),0)+1 id 
                FROM ca_asistencias  (nolock)
		        WHERE empleado_codigo= ?";
        $rs = $cn->Execute($sql, $params);
        $asistencia_codigo = $rs->fields[0];
        
        $params = array($asistencia_codigo,$this->empleado_codigo, $this->asistencia_codigo);
        $sql = "INSERT INTO CA_ASISTENCIAS(
            		Empleado_Codigo,
            		Asistencia_codigo,
            		Asistencia_fecha,
            		Ip_entrada,
            		Asistencia_entrada,
            		Asistencia_salida,
            		turno_codigo,
            		CA_Estado_Codigo,
            		Ip_salida,
            		fecha_reg_entrada,
            		fecha_reg_salida,
            		empleado_modifica_turno,
            		fecha_reg_modifica_turno,
            		Fecha_Reg,
            		area_codigo, 
            		extension_turno,
            		extension_tiempo, 
            		ORIGEN_ENTRADA,
            		ORIGEN_SALIDA, 
            		UPD_EXTENSION,           
            		cargo_codigo, 
            		modalidad_codigo, 
            		tipo_extension_codigo
            	)SELECT Empleado_Codigo,
            		?,
            		Asistencia_fecha,
            		Ip_entrada,
            		NULL,
            		NULL,
            		turno_codigo,
            		1,
            		Ip_salida,
            		fecha_reg_entrada,
            		fecha_reg_salida,
            		empleado_modifica_turno,
            		fecha_reg_modifica_turno,
            		Fecha_Reg,
            		area_codigo, 
            		extension_turno,
            		extension_tiempo, 
            		ORIGEN_ENTRADA,
            		ORIGEN_SALIDA, 
            		UPD_EXTENSION,           
            		cargo_codigo, 
            		modalidad_codigo, 
            		tipo_extension_codigo
            	FROM CA_ASISTENCIAS
            	WHERE EMPLEADO_CODIGO = ? AND ASISTENCIA_CODIGO = ?";
    		
        $r=$cn->Execute($sql, $params);
        if(!$r){
            $rpta =" Error al Insertar nueva Asistencia!.";
            $cn->RollbackTrans();
        }else{
            /*insertamos en asistencia_responsable*/
            $this->Insertar_Nueva_Asistencia_Responsable($asistencia_codigo);
            
            /*insertamos la incidencia*/
            $this->asistencia_codigo = $asistencia_codigo;
            $rpta=$this->Insertar_incidencia_diaria();
        }
        return $rpta;
    }
    /*  CREADO POR  : BANNY SOLANO*/
    /*  FECHA       : 20/06/2013*/
    /*  OBJETIVO    : INSERTAR INCIDENCIA RESPONSABLE*/
    function Insertar_Nueva_Asistencia_Responsable($asistencia_codigo){
        $cn = $this->getMyConexionADO();
        
        $params = array(
                    $asistencia_codigo,
                    $this->empleado_codigo,
                    $this->asistencia_codigo
                );
        $sql = "INSERT INTO CA_Asistencia_Responsables(
                        Empleado_Codigo,
                        Asistencia_codigo,
                        responsable_codigo,
                        fecha_reg,
                        area_codigo_responsable)
                SELECT Empleado_Codigo,
                        ?,
                        responsable_codigo,
                        fecha_reg,
                        area_codigo_responsable
                FROM CA_Asistencia_Responsables 
                WHERE Empleado_Codigo = ? AND Asistencia_codigo = ?";
        $rs = $cn->Execute($sql, $params);
    }
}
?>