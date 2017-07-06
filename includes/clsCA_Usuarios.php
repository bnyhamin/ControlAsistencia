<?php
require_once(PathIncludes() . "mantenimiento.php");
class ca_usuarios extends mantenimiento{
var $empleado_codigo='';
var $empleado_dni='';
var $responsable_origen='';
var $empleado_nombre='';
var $area_codigo='';
var $tipo_area_codigo='';
var $area_nombre='';
var $empleado_jefe='';
var $fecha_actual='';
var $numero_dia='';
var $turno_codigo='';
var $turno_descripcion='';
var $turno_duo='';
var $rol_code='';
var $rol_codigo='';
var $nivel='';


function Identificar(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="select Empleado_Carnet, Empleado, Area_Codigo, Area_Descripcion, Empleado_Responsable_Area,";
        $ssql.=" convert(varchar(10), getdate(),103) as Fecha,datepart(dw,getdate())-1 as num_dia,tipo_area_codigo, ";
        $ssql.=" turno_codigo, turno_descripcion, turno_duo ";
        $ssql.=" FROM vca_empleado_area(nolock) ";
        $ssql.=" WHERE Empleado_Codigo =? and Estado_Codigo =1 ";
        $params = array($this->empleado_codigo);
        $rs = $cn->Execute($ssql, $params);
        if (!$rs->EOF){
            $this->empleado_nombre= $rs->fields[1];
            $this->area_codigo = $rs->fields[2];
            $this->area_nombre = $rs->fields[3];
            $this->empleado_jefe= $rs->fields[4];
            $this->fecha_actual= $rs->fields[5];
            $this->numero_dia= $rs->fields[6];
            $this->tipo_area_codigo= $rs->fields[7];
            $this->turno_codigo= $rs->fields[8];
            $this->turno_descripcion= $rs->fields[9];
            $this->turno_duo= $rs->fields[10];
        }else{
            $rpta='No Existe Registro de Usuario';
        }
      $rs->close();
      $rs=null;
    }
    
    return $rpta;
}


function getEmpleadoCodigo(){
    $rpta="1";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    $ssql="";
    if($cn){
        $ssql = "select Empleado_Codigo from vDatos where Empleado_Dni = ? ";
        $params = array($this->empleado_dni);
        $rs = $cn->Execute($ssql, $params);
        if (!$rs->EOF){
            $this->empleado_codigo = $rs->fields[0];
        }else{
            $rpta='0';
        }
        $rs=null;
    }
    return $rpta;
}

function getResponsableCodigo(){
    $rpta="1";
    $cn = $this->getMyConexionADO();
    $ssql="";
    if($cn){
        $ssql = " SELECT Responsable_Codigo FROM CA_Asignacion_Empleados (nolock) WHERE Empleado_Codigo = ? AND Asignacion_Activo = 1 ";
        $params = array($this->empleado_codigo);
        $rs = $cn->Execute($ssql, $params);
        if (!$rs->EOF){
            $this->responsable_origen = $rs->fields[0];
        }else{
            $rpta='0';
        }
        $rs=null;
    }
    return $rpta;
}

//
/*
function esMando(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    if($cn){
        
        $params = array($this->area_codigo);
        $sql = "SELECT AREA_JEFE FROM AREAS WHERE AREA_CODIGO = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0)
            $area_jefe = $rs->fields[0];
        else
            $area_jefe = 0;
        
        //buscamos si existen jefaturas dpendientes
        $params = array($this->area_codigo);
        $sql = "SELECT * FROM AREAS WHERE AREA_JEFE = ? AND AREA_NIVEL_ORG = 4";
        $rs_jefaturas = $cn->Execute($sql, $params);
        
        //buscamos si el area jefe es gerencia
        $params = array($area_jefe);
        $sql = "SELECT * FROM AREAS WHERE AREA_CODIGO = ? AND AREA_NIVEL_ORG = 3";
        $rs_gerencias = $cn->Execute($sql, $params);
        
        //si ocurre cualquiera de estas consultas entonces se aplica la validacion normal
        if($rs_jefaturas->RecordCount() > 0 || $rs_gerencias->RecordCount() > 0){
            $ssql="select Area_Nivel_Org from Areas where Area_Codigo=? and Area_Activo=1";
            $params = array($this->area_codigo);
            $rs = $cn->Execute($ssql, $params);
            if (!$rs->EOF){
                $this->nivel= $rs->fields[0];
            }else{
                $rpta='No Existe Registro de Usuario';
            }
        }else{
            //caso contrario  verificamos si el empleado es mando de area
            $params = array($this->empleado_codigo);
            $sql = "SELECT * FROM AREAS WHERE EMPLEADO_RESPONSABLE = ?";
            $rs = $cn->execute($sql, $params);
            if($rs->RecordCount() > 0){
                $this->nivel = 4; //devolvemos 4 para que no afecte la funcionalidad inicial del script bandeja_validador_mando.php
            }else{
                $rpta='Empleado no es responsable de area';
            }
        }
        
      $rs->close();
      $rs=null;
    }
    
    return $rpta;
}
*/
function esMando(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="select Area_Nivel_Org from Areas where Area_Codigo=? and Area_Activo=1";
        $params = array($this->area_codigo);
        $rs = $cn->Execute($ssql, $params);
        if (!$rs->EOF){
            $this->nivel= $rs->fields[0];
        }else{
            $rpta='No Existe Registro de Usuario';
        }
      $rs->close();
      $rs=null;
    }
    
    return $rpta;
}


function getArea(){
    $rpta="OK";
    $ssql="";
    $cn=$this->getMyConexionADO();
    if($cn){
        
        $ssql=" select area_codigo ";
        $ssql.=" from areas where empleado_responsable = ".$this->empleado_codigo." and area_activo = 1 ";
        $ssql.=" order by area_codigo asc ";
        
        
        $padre=array();
        $rs = $cn->Execute($ssql);
        while (!$rs->EOF){
            $hijo=array();
            $hijo["area"]=$rs->fields[0];
            array_push($padre, $hijo);
            $rs->MoveNext();
        }
        $rs->close();
        $rs=null;
    }
    return $padre;
}


function getRolCode($empleado_id){
    $rol_code="0";
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
    //$cn->debug = true;
    $ssql = "SELECT rol_codigo 
             FROM CA_EMPLEADO_ROL 
             WHERE empleado_codigo = " . $empleado_id . " AND rol_codigo = 10 AND empleado_rol_activo=1";
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
        if($rs->RecordCount() > 0){
            $rol_code = $rs->fields[0];
        }
    }
    
    $this->rol_code=$rol_code;
    $rs=null;
}



function getAgregaOptions1($fecha,$area_codigo,$empleado){
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $sql4="";
    
    if ($this->rol_code!=10){
	$sql4="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
	$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
	$sql4 .=" a.turno_codigo = t.turno_codigo ";
        //$sql4 .=" inner join ca_controller c on a.area_codigo=c.area_codigo ";//mcortezc
	$sql4 .=" inner join ca_asistencia_responsables r on ";
	$sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
	$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
	$sql4 .=" and a.area_codigo =" . $area_codigo;
	//$sql4 .=" and c.activo=1 ";//mcortezc
	//$sql4 .=" and c.empleado_codigo = " . $empleado . " ";//mcortezc
        $sql4 .=" group by r.responsable_codigo ";
	$sql4 .=" Union ";
	$sql4 .="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
	$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
	$sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
	$sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
	$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
	$sql4 .=" and a.area_codigo =" . $area_codigo;
	$sql4 .=" and r.responsable_codigo =" . $empleado;//mcortezc
	$sql4 .=" group by r.responsable_codigo ";
        if ($this->rol_codigo>0){
            
            $sql4 .=" Union ";
            $sql4 .="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
            $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
            $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
            $sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
            $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
            $sql4 .=" and a.area_codigo =" . $area_codigo;
            $sql4 .=" group by r.responsable_codigo ";
        }
            $sql4 .="Order by 2";
    }else{
        $sql4 ="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
        $sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
        $sql4 .=" and a.area_codigo =" . $area_codigo;
        $sql4 .=" group by r.responsable_codigo ";
        $sql4 .="Order by 2";
    }
    
    
    //echo $sql4;
    
    $rs=$cn->Execute($sql4);
    while(!$rs->EOF){
        echo "<script language=javascript>";
        echo "window.parent.agregar_options('responsable_codigo','".$rs->fields[0]."','".$rs->fields[1]."');";
        echo "</script>";
        $rs->MoveNext();
    }   
}

function getAgregaOptions2($fecha,$area_codigo,$empleado,$responsable_codigo){
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $sql4="";
    
    
    if ($this->rol_code!=10){
        $sql4="select a.turno_codigo,t.turno_descripcion ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .=" a.turno_codigo = t.turno_codigo ";
        //$sql4 .=" inner join ca_controller c on a.area_codigo=c.area_codigo ";//mcortezc
        $sql4 .=" inner join ca_asistencia_responsables r on ";
        $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
        if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
        //$sql4 .="and c.activo = 1 and c.empleado_codigo = " . $empleado . " ";//mcortezc
        $sql4 .=" and t.turno_activo = 1 ";
        $sql4 .=" group by a.turno_codigo,t.turno_descripcion ";
        $sql4 .=" Union ";
        $sql4 .="select a.turno_codigo,t.turno_descripcion ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
        $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
        if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
        $sql4 .=" and t.turno_activo = 1 ";
        $sql4 .=" group by a.turno_codigo,t.turno_descripcion order by t.turno_descripcion";
    }else{
        $sql4 ="select a.turno_codigo,t.turno_descripcion ";
        $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
        $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
        $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
        $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
        if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
        $sql4 .=" and t.turno_activo = 1 ";
        $sql4 .=" group by a.turno_codigo,t.turno_descripcion order by t.turno_descripcion";
    }
    
    $rs=$cn->Execute($sql4);
    while(!$rs->EOF){
        echo "<script language=javascript>";
            echo "window.parent.agregar_options('turno_codigo','".$rs->fields[0]."','".$rs->fields[1]."');";
        echo "</script>";
        $rs->MoveNext();
    }
    
}

function getAgregaOptions3($anio_codigo){
    $cn=$this->getMyConexionADO();
    $sql4="";
    
    
    $sql4="SELECT DATEPART(wk, Asistencia_fecha) AS semana_codigo,DATEPART(wk, Asistencia_fecha) AS semana_descripcion ";
    $sql4 .=" FROM  dbo.CA_Asistencias WITH (nolock) ";
    $sql4 .=" WHERE (CA_Estado_Codigo = 1) AND (Asistencia_entrada BETWEEN CONVERT(datetime, '01/01/' + '" . $anio_codigo . "',103)" ;
    $sql4 .=" AND CONVERT(datetime, '31/12/' + '" . $anio_codigo . "',103)) ";
    $sql4 .=" GROUP BY DATEPART(wk, Asistencia_fecha),";
    $sql4 .=" CONVERT(varchar(10), Asistencia_fecha - (DATEPART(w, Asistencia_fecha + 7) - 1), 103), ";
    $sql4 .=" CONVERT(varchar(10), Asistencia_fecha + 7 - DATEPART(w, Asistencia_fecha + 7), 103) ORDER BY 2 DESC";
    
    $rs=$cn->Execute($sql4);
    while(!$rs->EOF){
        echo "<script language=javascript>";
            echo "window.parent.agregar_options('semana_codigo','".$rs->fields[0]."','".$rs->fields[1]."');";
        echo "</script>";
    
        $rs->MoveNext();
    }
    
}


function IdentificarN(){
	
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $ssql="select Empleado_Carnet, Empleado, Area_Codigo, Area_Descripcion, Empleado_Responsable_Area,";
    $ssql.=" convert(varchar(10), getdate(),103) as Fecha, datepart(dw,getdate())-1 as num_dia, tipo_area_codigo, ";
    $ssql.=" vp.turno_codigo, vp.turno_descripcion, vp.turno_duo ";
    $ssql.=" FROM vca_empleado_area ve left join vCA_Turnos_Programado vp on  ";
    $ssql.=" ve.empleado_codigo=vp.empleado_codigo ";
    //$ssql.=" and convert(varchar(10),vp.fechap,112)=convert(varchar(10),getdate(),112) ";
    $ssql.=" and vp.fechap=convert(datetime,convert(varchar(10),getdate(),103), 103) ";
    $ssql.=" WHERE ve.Empleado_Codigo=" . $this->empleado_codigo . " and ve.Estado_Codigo=1 ";
    
    $rs = $cn->Execute($ssql);
    
    if (!$rs->EOF){
        $this->empleado_nombre= $rs->fields[1];
        $this->area_codigo = $rs->fields[2];
        $this->area_nombre = $rs->fields[3];
        $this->empleado_jefe= $rs->fields[4];
        $this->fecha_actual= $rs->fields[5];
        $this->numero_dia= $rs->fields[6];
        $this->tipo_area_codigo= $rs->fields[7];
        $this->turno_codigo= $rs->fields[8];
        $this->turno_descripcion= $rs->fields[9];
        $this->turno_duo= $rs->fields[10];
    }else{
        $rpta='No Existe Registro de Usuario';
    }

    $rs->close();
    $rs=null;
    return $rpta;
    
}

function Actualizacion_dias(){
  
    
    $rpta="";
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql=" select diasvalidaciongap from areas where area_codigo = ".$this->area_codigo." and area_activo = 1 ";
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            $dias=$rs->fields[0];
        }
        
        $text=" exec Validacion_Dias  ".$dias." ";
        $r = $cn->Execute($text);
        $ndias=$r->fields[0];
        
        $rs->close();
        $rs=null;
        $r->close();
        $r=null;
        return $ndias;
        
    }
    
    
}

function obtener_areas_responsable(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	$areas="";
	if($cn){
		$ssql="SELECT empleado_responsable ";
	    $ssql.=" FROM areas(nolock) WHERE area_codigo=" . $this->area_codigo;
	    
		$rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$responsable=$rs->fields[0];
			$ssql="SELECT area_codigo ";
	    	$ssql.=" FROM areas(nolock) WHERE empleado_responsable=" . $responsable;
	    	
			$rs2 = $cn->Execute($ssql);
			while (!$rs2->EOF){
				if ($areas==''){
					$areas=$rs2->fields[0];
				}else{
					$areas.=','.$rs2->fields[0];
				}
				$rs2->MoveNext();
			}
			$rpta=$areas;
		}

	  $rs2->close();
	  $rs2=null;
	  $rs->close();
	  $rs=null;
	}
	return $rpta;
}




}
?>
