<?php

require_once(PathIncludes() . "mantenimiento.php");

class ca_empleados extends mantenimiento{
var $empleado_codigo='';
var $empleado_nombre='';
var $empleado_dni='';
var $empleado_clave_modificada='';
var $empleado_clave_acceso='';
var $area_codigo='';
var $empleado_codigo_reg='';
var $turno_codigo ='';
var $turno_descripcion='';
var $turno_duo='';
var $tipo_clave='';
var $modalidad='';
var $horario='';
var $area_gerencia='';
var $area_gerencia_codigo='';
var $t_codigo=0;
var $e_turno=0;
var $extension_tiempo=0;

var $modalidad_descripcion='';
var $horario_descripcion='';

function modificar_turno(){
	$rpta="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){ 
		$ssql="UPDATE empleados SET ";	    
		if ($this->turno_codigo=='-1'){
			$ssql.=" turno_codigo=null ";
		}else{
			$ssql.=" turno_codigo=" . $this->turno_codigo;
		}
		$ssql.= " WHERE empleado_codigo=" . $this->empleado_codigo;
        
        $rs = $cn->Execute($ssql);
	}
	return $rpta;
}

function modificar_turno_ver(){
	$rpta="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){ 
		$ssql =" exec spCA_modificar_turno_ver ".$this->turno_codigo." , ".$this->empleado_codigo;	    
		$ssql.=" , ".$this->empleado_codigo_reg;	    
		$rs = $cn->Execute($ssql);
	}
	return $rpta;
}

function Asignar_clave(){
	$rpta="OK";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){ 
	    $params = array($this->empleado_codigo);
		$ssql="UPDATE empleados SET empleado_clave_acceso=dbo.udf_md5('123'),empleado_clave_modificada=0 ";
		$ssql.= " WHERE empleado_codigo=?";
        $rs = $cn->Execute($ssql, $params);
		
        //INSERTAMOS
        $params = array($this->empleado_codigo,$this->empleado_codigo_reg);
		$ssql="INSERT INTO ca_log_claves(empleado_codigo,empleado_codigo_reg,fecha_reg) ";
		$ssql.= " values(?,?,getdate())";		
		$rs = $cn->Execute($ssql, $params);
		
		$ssql="INSERT INTO CA_CAMBIO_CLAVE (empleado_dni,empleado_nueva_clave) SELECT empleado_dni, '123' as empleado_nueva_clave FROM empleados WHERE empleado_codigo=" . $this->empleado_codigo;
		$rs = $cn->Execute($ssql);
	}
	return $rpta;
}

function Update_clave(){
	$rpta="";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){ 
	    //$ssql="UPDATE empleados set empleado_clave_acceso=?,empleado_clave_modificada=? ";
	    //$ssql.= " WHERE empleado_codigo=?";
		if($this->tipo_clave==1){
		  $ssql="UPDATE empleados SET empleado_clave_acceso='123',empleado_clave_modificada=0 ";
		  //$cmd->Parameters[0]->value="123";
		  //$cmd->Parameters[1]->value="0"; 
		}else{
		  $ssql="UPDATE empleados SET empleado_clave_acceso=".$this->empleado_clave_acceso.",empleado_clave_modificada=1 ";
		  //$cmd->Parameters[0]->value=$this->empleado_clave_acceso;
		  //$cmd->Parameters[1]->value="1"; 		
		}
        $ssql.= " WHERE empleado_codigo=".$this->empleado_codigo;
        $rs = $cn->Execute($ssql);
		//$cmd->Parameters[2]->value = $this->empleado_codigo;
		//$cmd->Execute();
	}
	return $rpta;
}

function Query(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
    $params = array($this->empleado_codigo);
    $ssql ="SELECT vca_empleado_area.Empleado,turno_codigo,turno_descripcion,empleado_dni,empleado_clave_acceso,empleado_clave_modificada";
    $ssql .=" FROM vca_empleado_area ";
    $ssql .=" WHERE  vca_empleado_area.Empleado_Codigo = ?";
    $rs = $cn->Execute($ssql, $params);
        if (!$rs->EOF){
            $this->empleado_nombre= $rs->fields[0];
            $this->turno_codigo= $rs->fields[1];
            $this->turno_descripcion= $rs->fields[2];
            $this->empleado_dni= $rs->fields[3];
            $this->empleado_clave_acceso= $rs->fields[4];
            $this->empleado_clave_modificada= $rs->fields[5];
        }else{
            $rpta='No Existe Registro de empleado: ' . $this->empleado_codigo;
        }
    }
    return $rpta;
}

function es_duo(){ 
    $duo=0;
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    if($cn){
        $ssql =" select turno_duo from CA_Turnos (nolock) where Turno_Codigo = ".$this->t_codigo." and Turno_Activo = 1 ";
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF) $duo= $rs->fields[0];
    }
    return $duo;
}

function get_extension_turno($empleado,$asistencia,$fecha,$num){
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $formato="set dateformat dmy";
    $cn->Execute($formato);
    
    if($cn){
        if($num*1==1){
            $ssql =" select turno_codigo,extension_turno,extension_tiempo ";
            $ssql.=" from CA_Asistencias (nolock) where Empleado_Codigo = ".$empleado." and Asistencia_codigo = ".$asistencia." and CA_Estado_Codigo = 1 ";
        }else{
            $ssql=" select turno_codigo,0 as extension_turno,0 as extension_tiempo ";
            $ssql.=" from vca_turnos_programado where empleado_codigo = ".$empleado." and fechap=convert(varchar(10),'".$fecha."',103) ";
        }
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            $this->t_codigo=$rs->fields[0];
            $this->e_turno= $rs->fields[1];
            $this->extension_tiempo= $rs->fields[2];
        } 
    }
    
}


function Query_Turno(){ //habilitado para jalar turno de vca_turnos_prograamado
    $rpta="OK";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql =" select vea.Empleado,vtp.turno_codigo,t.turno_descripcion,vea.empleado_dni,";
		$ssql.=" vea.empleado_clave_acceso,vea.empleado_clave_modificada ";
        $ssql.=" FROM vca_empleado_area vea(nolock) left join vca_turnos_programado vtp(nolock) on ";
//        $ssql.=" vea.empleado_codigo=vtp.empleado_codigo and convert(varchar(10), fechap,103)='".$this->fecha."'";
        $ssql.=" vea.empleado_codigo=vtp.empleado_codigo and fechap=convert(datetime,'".$this->fecha."',103)";
        $ssql.=" left join ca_turnos t(nolock) on vtp.turno_codigo=t.turno_codigo ";
        $ssql.=" WHERE  vea.Empleado_Codigo =" . $this->empleado_codigo;
        $rs = $cn->Execute($ssql);
        
        if (!$rs->EOF){
            $this->empleado_nombre= $rs->fields[0];
            $this->turno_codigo= $rs->fields[1];
            $this->turno_descripcion= $rs->fields[2];
            $this->empleado_dni= $rs->fields[3];
            $this->empleado_clave_acceso= $rs->fields[4];
            $this->empleado_clave_modificada= $rs->fields[5];
        }else{
            $rpta='No Existe Registro de empleado: ' . $this->empleado_codigo;
        }
    }
    return $rpta;
}

function Lista_Empleados($campana,$anio,$mes){
    $rpta="OK";
    $cadena="";
    $cn = $this->getMyConexionADO();
    //$rpta=$this->conectarme_ado();
    if($cn){
        $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
        $cadena  .="<TR><TD class='FieldCaptionTD' align='left' colspan='2'><b>Sel.</b></TD>\n";
        $cadena  .="<TD class='FieldCaptionTD' align='left' ><b>Incidencia</b></TD>\n";
        $cadena  .="</TR>";
        
        $ssql ="SELECT v1.empleado_codigo,v1.empleado,v1.empleado ";
        $ssql .=" FROM vCA_Empleado_Area v1 ";
        $ssql .=" INNER JOIN vCA_Empleado_Modalidad_Horario vemh on v1.empleado_codigo=vemh.empleado_codigo ";
        $ssql .=" INNER JOIN vCA_Empleado_incidencia_horas veh on v1.empleado_codigo=veh.empleado_codigo ";
        $ssql .=" LEFT JOIN vCA_Empleado_Area v2 on v2.empleado_codigo=veh.responsable_codigo ";
        $ssql .=" LEFT JOIN ca_incidencias ci on ci.incidencia_codigo=veh.incidencia_codigo ";
        $ssql .=" LEFT JOIN v_campanas vc on vc.cod_campana=veh.cod_campana ";
        $ssql .=" WHERE ";
        
        if($campana!=0) $ssql .=" vc.cod_campana=" . $campana . " and ";
        
        $ssql .=" v1.area_codigo=" . $this->area_codigo . "";
        $ssql .=" and asistencia_fecha between  CONVERT(DATETIME,'01-". $mes ."-" . $anio . "',103)  and ";
        $ssql .="  CONVERT(DATETIME,dateadd(month,1,CONVERT(DATETIME,'01-". $mes ."-" .$anio ."',103)),103)-1 ";                              
        $ssql .=" group by v1.empleado_codigo,v1.empleado,vemh.item_descripcion ";
        $ssql .=" order by 2 ";
        //echo $ssql;
        
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
    }
    return $cadena;        
}
  
function Lista_Empleados_con_compensacion($campana,$anio,$mes){
    $rpta="OK";
    $cadena="";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    if($rpta=="OK"){
        $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
        $cadena  .="<TR><TD class='FieldCaptionTD' align='left' colspan='2'><b>Sel.</b></TD>\n";
        $cadena  .="<TD class='FieldCaptionTD' align='left' ><b>Empleados</b></TD>\n";
        $cadena  .="</TR>";
        
        $ssql =" SELECT c.empleado_codigo,v.empleado ";
        $ssql .=" FROM ca_asistencia_incidencias c inner join ";
        $ssql .=" ca_asistencias ca on ca.empleado_codigo=c.empleado_codigo and ";
        $ssql .="                      ca.asistencia_codigo=c.asistencia_codigo ";
        $ssql .=" INNER JOIN vca_empleado_area v on c.empleado_codigo=v.empleado_codigo ";
        $ssql .=" WHERE c.incidencia_codigo in (3,4) ";
        $ssql .=" and v.area_codigo=" . $this->area_codigo . "";
        $ssql .=" and ca.asistencia_fecha between  CONVERT(DATETIME,'01-". $mes ."-" . $anio . "',103)  and ";
        $ssql .="  CONVERT(DATETIME,dateadd(month,1,CONVERT(DATETIME,'01-". $mes ."-" .$anio ."',103)),103)-1 ";  
        if($campana!=0) $ssql .=" and c.cod_campana=" . $campana;
        $ssql .=" GROUP BY c.empleado_codigo,v.empleado ";
        
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
    }
    return $cadena;        
}
  
function Query_Gerencia(){
    $rpta="OK";
    //$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql  =" (SELECT a.area_codigo,a.area_descripcion ";
        $ssql .=" 	FROM Empleados INNER JOIN Empleado_Area ON ";
        $ssql .=" 	Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo ";
        $ssql .=" 	INNER JOIN Areas on Areas.Area_Jefe=Empleado_Area.Area_codigo ";
        $ssql .=" 	INNER JOIN Areas a on Areas.Area_Jefe=a.Area_codigo ";
        $ssql .=" 	WHERE (Empleados.Empleado_Responsable_Area=1) AND ";
        $ssql .=" 	(Empleado_Area.Empleado_Area_Activo = 1) AND ";
        $ssql .=" 	(Empleados.Empleado_Codigo = " . $this->empleado_codigo . ")) ";
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){ 
            $this->area_gerencia_codigo=$rs->fields[0];
            $this->area_gerencia = $rs->fields[1];
        }else{
            $ssql  =" select a.area_codigo,a.area_descripcion ";
            $ssql .=" from empleado_area ";
            $ssql .=" INNER JOIN Areas on Areas.Area_Codigo=Empleado_Area.Area_codigo ";
            $ssql .=" INNER JOIN Areas a on Areas.Area_Jefe=a.Area_codigo ";
            $ssql .=" where empleado_codigo=" . $this->empleado_codigo ;
            $ssql .=" and empleado_area_activo=1 ";
            
            $rst = $cn->Execute($ssql);
            $this->area_gerencia_codigo = $rst->fields[0];
            $this->area_gerencia = $rst->fields[1];
        }
    }
    return $rpta;
}

function listaEmpleado($search){
    $rpta="OK";
    $cadena="";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        $cadena .= "<table class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center'>";
        $cadena  .="<tr><td class='ColumnTD' align='center'>Sel</b></td>\n";
        $cadena  .="<td class='ColumnTD' align='center'>Codigo</b></td>\n";
        $cadena  .="<td class='ColumnTD' align='center'>Area</b></td>\n";
        $cadena  .="</tr>";
        
        $ssql="select empleado_codigo,empleado ";
        $ssql.="from vdatos where empleado like '%".$search."%'";
        
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            while (!$rs->EOF){
                $cadena .= "<tr class='DataTD'>\n";
                $cadena .="<td>\n"; 
                $cadena .="<input type=radio align=center id='rdo' name='rdo' value=" . $rs->fields[0]. " onclick='cmdEnviar(\"" . $rs->fields[0]. "\",\"". $rs->fields[1]."\")'>";
                $cadena .="</td>\n<td >" . $rs->fields[0]; 
                $cadena .="</td>\n<td >" . $rs->fields[1]; 
                $cadena .="</td>\n</tr>";
                $rs->movenext();
            }
            $cadena .="</table>";
        }
    }
    
    return $cadena;
}

  function Query_Area_Piloto($area){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    $cn->SetFetchMode(ADODB_FETCH_ASSOC);
    if($cn){
        //$ssql =" select Item_Default2 from items where item_codigo=517 and "; 
        //$ssql.=" Item_Default2 like '%".$area."%' ";
        $ssql =" select Item_Default2 from items where item_codigo=517";
        $rs = $cn->Execute($ssql);
        $AREAS_PILOTO='';
        if (!$rs->EOF){
            $AREAS_PILOTO=$rs->fields['Item_Default2'];
        }
        if ($AREAS_PILOTO=='') return true;

        $ssql ="select area_codigo from areas where area_codigo in (" . $AREAS_PILOTO . ") and area_codigo=".$area;
        //echo $ssql;
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            return false;
        }else{
            return true;
        }
    }else{
        return true;
    }
    
/*	if ($area!=213 and $area!=215){
		return true;
	}else{
		return false;
	}
*/
  }

function Query_vdatos(){ //para cambio de horario desde gap
	$rpta="OK";
	//$rpta=$this->conectarme_ado();
    $cn = $this->getMyConexionADO();
	if($cn){
    	$ssql =" SELECT empleado_codigo,empleado_dni,empleado,modalidad_codigo, ";
    	$ssql.=" modalidad_descripcion,horario_codigo,horario_descripcion ";
    	$ssql.=" FROM vdatostotal ";
    	$ssql.=" WHERE empleado_codigo = " . $this->empleado_codigo;
    	$rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            $this->empleado_dni = $rs->fields[1];
            $this->empleado_nombre = $rs->fields[2];
            $this->modalidad = $rs->fields[3];
            $this->modalidad_descripcion = $rs->fields[4];
            $this->horario = $rs->fields[5];
            $this->horario_descripcion = $rs->fields[6];
        }else{
            $rpta='No Existe Registro de empleado: ' . $this->empleado_codigo;
        }
	}
	return $rpta;
}
/*
function verifica_avaya3(){
echo "idem";
	//$msconnect = pg_connect($BD_SERVER_PG . $BD_CATALOG_PG . $BD_USER_PG) or die("No puedo conectarme a servidor");
	$connect = pg_connect("host=10.252.194.84 user=usermetricas password=usermetricas dbname=db_gestion_metricas")
    or die('No se ha podido conectar:' . pg_last_error());
    
    //$cn=$this->getMyConexionADO1();
     
	
	$ssql = "SELECT empleado_codigo, fecha, row_date, acdtime, acwtime, ti_auxtime0, 
       ti_availtime, ti_stafftime, i_acdtime, i_acwtime, i_availtime
  FROM cms_dia.hagent_dia
where empleado_codigo=5814 and fecha= to_date('2011-06-05','yyyy-mm-dd')" ;

echo $ssql;*/

     /*$rs= $cn->Execute($ssql);
     echo $rs;
     while(!$rs->EOF){
     	echo "a";
     	echo $rs->fields[1];
     	$rs->MoveNext();
     	
     }*/
	/*$result = pg_query($ssql) or die('La consulta fallo: ' . pg_last_error());
	if (pg_num_rows($result)>0){
		$rs = pg_fetch_row($result);
		return $rs[0];
	}else{
		return "";
	}
}*/
    function obtenerLicenciaLactancia(){
        $rpta="OK";
        $cadena="";
        $cn = $this->getMyConexionADO();
        if($cn){
            
            $ssql = "select Empleado_Movimiento.Emp_Mov_codigo, Movimiento.Movimiento_Descripcion, CONVERT(VARCHAR(10),Emp_Mov_Fecha_Inicio,103), ";
            $ssql .= "CONVERT(VARCHAR(10),Emp_Mov_Fecha_Fin,103) from Empleado_Movimiento ";
            $ssql .= "inner join Movimiento on Movimiento.Movimiento_codigo = Empleado_Movimiento.Movimiento_codigo ";
            $ssql .= "where Estado_codigo = 1 and Empleado_Movimiento.Movimiento_codigo = 36 ";
            $ssql .= "and Emp_Mov_Fecha_Inicio <= CONVERT(DATETIME, CONVERT(VARCHAR(8),GETDATE(),112)) ";
            $ssql .= "and Emp_Mov_Fecha_Fin >=  CONVERT(DATETIME, CONVERT(VARCHAR(8),GETDATE(),112)) ";
            $ssql.=" and empleado_codigo  = ". $this->empleado_codigo;
            
            $rs = $cn->Execute($ssql);
            if (!$rs->EOF){
                while (!$rs->EOF){
                    $cadena .= "<tr>\n";
                    $cadena .="<td class='fila' align='center'>\n"; 
                    $cadena .= $rs->fields[0];
                    $cadena .="</td>\n<td class='fila' align='center'>" . $rs->fields[1]; 
                    $cadena .="</td>\n<td class='fila' align='center'>" . $rs->fields[2]; 
                    $cadena .="</td>\n<td class='fila' align='center'>" . $rs->fields[3]; 
                    $cadena .="</td>\n</tr>";
                    $rs->movenext();
                }
                $cadena .="</table>";
            }
        }
        
        return $cadena;


    }

    function ObtenerHistorialHorarioLactancia(){
        $rpta="OK";
        $cadena="";
        $cn = $this->getMyConexionADO();
        if($cn){
            
            $ssql  = "select CONVERT(VARCHAR(10),Fecha_Aplicacion,103),CASE WHEN Horario ='I' THEN 'Inicio de turno' ELSE 'Fin de turno' END,";
            $ssql .= "e.Empleado_Apellido_Paterno+' '+e.Empleado_Apellido_Materno+' '+e.Empleado_Nombres ";
            $ssql .= "from Empleado_Horario_Lactancia  ehl inner join Empleados e on e.Empleado_Codigo = ehl.Usuario_reg  and ehl.Empleado_Codigo = ". $this->empleado_codigo;
            $ssql .= "order by Fecha_reg desc";
            
            $rs = $cn->Execute($ssql);
            if (!$rs->EOF){
                while (!$rs->EOF){
                    $cadena .= "<tr>\n";
                    $cadena .="<td class='fila' align='center'>\n" .$rs->fields[0]; 
                    $cadena .="</td>\n<td class='fila' align='center'>" . $rs->fields[1]; 
                    $cadena .="</td>\n<td class='fila' align='center'>" . $rs->fields[2]; 
                    $cadena .="</td>\n</tr>";
                    $rs->movenext();
                }
                $cadena .="</table>";
            }
        }
        
        return $cadena;



            
    }

}
?>
