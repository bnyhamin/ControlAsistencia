<?php 
require_once(PathIncludes() . "mantenimiento.php");

class ca_reportes extends mantenimiento{
var $Rep_Codigo="";
var $Rep_Binario="";
var $Rep_Fecha="";
var $Rep_Nombre="";
var $Usuario_Id="";

function Query(){
$rpta="OK";
//$rpta=$this->conectarme_ado();
$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "SELECT * FROM CA_Reportes ";
		$ssql .= " WHERE Rep_Codigo = " . $this->Rep_Codigo;
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$this->Usuario_Id = $rs->fields[1];
			$this->Rep_Fecha= $rs->fields[2];
			$this->Rep_Nombre= $rs->fields[3];		
	  }else{
		   $rpta='No Existe Registro de Reporte';
	  }
	 } 
	return $rpta;
 }
 

 function resumen_incidencias_validables($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        $ssql="
        select incidencia_codigo,usuario_registro,incidencia_descripcion,empleado,
		sum(case when ee_codigo=3 then cantidad end) as  aprobado,
		sum(case when ee_codigo=4 or ee_codigo=6 then cantidad end) as rechazado
		from (
			select ca_incidencias.incidencia_codigo 
			 ,ca_evento_log.usuario_registro 
			 ,ca_incidencias.incidencia_descripcion, vdatos.empleado ,
			 --,case when ca_eventos.ee_codigo = 3 then count(ca_eventos.ee_codigo) end as aprobado, 
			 --case when ca_eventos.ee_codigo = 4 or ca_eventos.ee_codigo = 6 then count(ca_eventos.ee_codigo) end as rechazado 
			 ca_eventos.ee_codigo,
			 count(ca_eventos.ee_codigo) as cantidad
			 from ca_eventos 
			 inner join ca_evento_log on ca_eventos.Empleado_Codigo = ca_evento_log.Empleado_Codigo 
				and ca_eventos.Asistencia_codigo = ca_evento_log.Asistencia_codigo 
				and ca_eventos.Evento_Codigo = ca_evento_log.Evento_Codigo 
				and (ca_evento_log.realizado = 'V' or ca_evento_log.realizado = 'M' ) and ca_evento_log.em_codigo !=2
				and ca_eventos.evento_activo = 0 
			 inner join ca_incidencias on ca_eventos.incidencia_codigo = ca_incidencias.incidencia_codigo 
				and (ca_incidencias.validable = 1 or ca_incidencias.validable_mando = 1 ) 
			 inner join vdatos on ca_evento_log.usuario_registro= vdatos.empleado_codigo 
			 where 
				convert(varchar,fecha_registro,103) >= '".$fechai."' 
				and convert(varchar,fecha_registro,103) <= '".$fechaf."' 
			 group by ca_incidencias.incidencia_codigo 
			 ,ca_incidencias.incidencia_descripcion 
			 ,ca_evento_log.usuario_registro 
			 ,vdatos.empleado 
			 ,ca_eventos.ee_codigo 
			 having ca_eventos.ee_codigo in (3,4,6)
         ) as tx
         group by incidencia_codigo,usuario_registro,incidencia_descripcion,empleado
        ";
        
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
       
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            
            
            $text=" SELECT CASE WHEN VALIDABLE=1 THEN 'Por Persona' else 'Por Mando' end as tipo ";
            $text.=" FROM CA_INCIDENCIAS WHERE INCIDENCIA_CODIGO = ".$rs->fields[0]." ";
            $r = $cn->Execute($text);
            
            
            $reg .="<td align='left'>\n";
            $reg .=" " . $r->fields[0]. "\n";
            $reg .="</td>\n";
            
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
          

        }

        $rs=null;
    }
    
    return $lista;
    
}

function reporte_Turno_Especial($fechai,$fechaf,$codigo_area){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
	//$cn->debug=true;
    if($cn){
        $ssql="exec spCA_Reporte_Especial '".$fechai."','".$fechaf."',".$codigo_area." ";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr>\n";
            $reg .="<td align='left'>\n";//EMPLEADO
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//CEDULA
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//AREA
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//CARGO
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//FECHA INICIO PROGRAMADO
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//FECHA FIN PROGRAMADO
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//FECHA INICIO EJECUTADO
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//FECHA FIN EJECUTADO
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//HORAS PROGRAMADAS
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//HORAS EJECUTADAS
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//IP ENTRADA
            $reg .=" " . $rs->fields[10]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//IP SALIDA
            $reg .=" " . $rs->fields[11]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}
function reporte_Turno_Extendio($fechai,$fechaf,$codigo_area){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
	//$cn->debug=true;
    if($cn){
        $ssql="exec spCA_Reporte_Extendido '".$fechai."','".$fechaf."',".$codigo_area." ";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr>\n";
            $reg .="<td align='left'>\n";//EMPLEADO
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//CEDULA
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//AREA
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//CARGO
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
			$reg .="<td align='left'>\n";//TURNO
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//FECHA ASISTENCIA
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//FECHA EXTENSION
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//TIEMPO EXTENSION
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
			$reg .="<td align='center'>\n";//TIPO EXTENSION
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
} 
 
 
 function reporte_Permisos_Biometrico($fechai,$fechaf,$codigo_area) {
    
    $rpta="OK";
    $cn = $this->getMyConexionADO();
	  //$cn->debug=true;
    
    if($cn) {
        
        $and_sql = "";
        
        if(strlen($codigo_area) && $codigo_area !='0') {
        
             $and_sql = " and  Area_Codigo=".$codigo_area."";
        }
        
        $arr_tmp_fecha_inicio = explode("/", $fechai);
        $fecha_inicio = $arr_tmp_fecha_inicio[2]."".$arr_tmp_fecha_inicio[1]."".$arr_tmp_fecha_inicio[0];
        
        $arr_tmp_fecha_fin = explode("/", $fechaf);
        $fecha_fin = $arr_tmp_fecha_fin[2]."".$arr_tmp_fecha_fin[1]."".$arr_tmp_fecha_fin[0];
        

        $ssql="select empleado_dni, empleado, Area_Descripcion, Cargo_descripcion, 
                      vp.plataforma_descrip, convert(varchar(10), fecha_inicio,103) as fecha_inicio , convert(varchar(10), fecha_fin,103) as fecha_fin, 
                      observacion, CASE WHEN permiso_activo = 1 then 'si' else 'no' end as situacion ,(select empleado from vDatos where empleado_codigo=usuario_registro) as usuario_registro,
                      convert(varchar(16) , fecha_registro,113) as fecha_registro, (select empleado from vDatos where empleado_codigo=usuario_modificacion) as usuario_modificacion, convert(varchar(16) , fecha_modificacion,113) as fecha_modificacion   
               from (vDatosTotal as d join bio_biometrico_permisos as p on  d.empleado_codigo =p.empleado_codigo ) join [SAPLCCC320].MAPA_ACTIVO_PUESTOS.dbo.vPlataformas as  vp on  p.plataforma_id=vp.plataforma_id
               where not((fecha_inicio<'".$fecha_inicio."' and fecha_fin<'".$fecha_inicio."') or (fecha_inicio>'".$fecha_fin."' and fecha_fin>'".$fecha_fin."'))  ".$and_sql."  order by Area_Descripcion, empleado, vp.plataforma_descrip  asc";
        
        
        $reg  ="";
        $lista="";
        
        $cn->Execute("Set ANSI_NULLS ON ");  
        $cn->Execute("Set ANSI_WARNINGS ON ");
        
        $rs = $cn->Execute($ssql);
        
        while(!$rs->EOF){

            $reg  = "<tr>\n";
			 $reg .="<td align='left'>\n";//DNI
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//EMPLEADO
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//AREA
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//CARGO
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//PLATAFORMA
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
		        $reg .="<td align='left'>\n";//FECHA INICIO
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//FECHA FIN
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//OBSERVACION
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//SITUACION
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
		        $reg .="<td align='center'>\n";//USUARIO REGISTRO
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//FECHA ASIGNACION
            $reg .=" " . $rs->fields[10]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//USUARIO DESACTIVACION
            $reg .=" " . $rs->fields[11]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";//FECHA DESACTIVACION
            $reg .=" " . $rs->fields[12]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }

    return $lista;
    
    
} 
 

function resumen_detalladoxincidencia($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="exec sp_reporte_incidencias_validables '".$fechai."','".$fechaf."',1";
        
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[10]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[11]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[13]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[14]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[15]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}

 
 
 
function resumen_consolidadoxincidencia($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql="exec sp_reporte_incidencias_validables '".$fechai."','".$fechaf."',2";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}


function reporte_eventos_proceso($area){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
	//$cn->debug=true;
    if($cn){
		
		$o=new ca_eventos();
		$o->setMyUrl($this->getMyUrl());
		$o->setMyUser($this->getMyUser());
		$o->setMyPwd($this->getMyPwd());
		$o->setMyDBName($this->getMyDBName());
		
		$ssql=" SELECT CE.EMPLEADO_CODIGO,CE.ASISTENCIA_CODIGO,CE.EVENTO_CODIGO ";
		$ssql.=" ,CE.INCIDENCIA_CODIGO,dbo.UDF_EMPLEADO_NOMBRE(CEL.USUARIO_REGISTRO) AS SUPERVISOR ";
		$ssql.=" ,dbo.UDF_EMPLEADO_NOMBRE(CE.EMPLEADO_CODIGO) AS EJECUTIVO ";
		$ssql.=" ,CONVERT(VARCHAR,CA.ASISTENCIA_FECHA,103) AS ASISTENCIA_FECHA ";
		$ssql.=" ,v_areas.Area_Descripcion,CONVERT(VARCHAR,CEL.FECHA_REGISTRO,103) AS FECHA_REGISTRO,CI.INCIDENCIA_DESCRIPCION ";
		$ssql.=" FROM CA_EVENTOS AS CE(NOLOCK) ";
		$ssql.=" INNER JOIN CA_INCIDENCIAS AS CI(NOLOCK) ON CE.INCIDENCIA_CODIGO = CI.INCIDENCIA_CODIGO ";
		$ssql.=" INNER JOIN CA_ASISTENCIAS AS CA(NOLOCK) ON CE.EMPLEADO_CODIGO = CA.EMPLEADO_CODIGO ";
		$ssql.=" AND CE.ASISTENCIA_CODIGO = CA.ASISTENCIA_CODIGO ";
		$ssql.=" INNER JOIN CA_EVENTO_LOG AS CEL(NOLOCK) ON CE.EMPLEADO_CODIGO = CEL.EMPLEADO_CODIGO ";
		$ssql.=" AND CE.ASISTENCIA_CODIGO = CEL.ASISTENCIA_CODIGO AND CE.EVENTO_CODIGO = CEL.EVENTO_CODIGO ";
		$ssql.=" INNER JOIN v_areas ON CE.EMPLEADO_CODIGO = v_areas.Empleado_Codigo ";
		$ssql.=" WHERE CE.EVENTO_ACTIVO=1 AND CE.EE_CODIGO=2 AND CEL.REALIZADO = 'S' ";
		
		if($area!=0) $ssql.=" AND v_areas.Area_Codigo = ".$area." ";
		
		$ssql.=" ORDER BY CA.ASISTENCIA_FECHA DESC ";
		
		$nivel="";
		
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
			$nivel = $o->Estado_Evento_Aprobacion($rs->fields[3],$rs->fields[0],$rs->fields[1],$rs->fields[2]);
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
			$reg .="<td align='center'>\n";
            $reg .=" " . $nivel . "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }
        $rs=null;   
    }
    return $lista;
}


function resumen_consolidadoxvalidador($fechai,$fechaf){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    if($cn){
        $ssql="exec sp_reporte_incidencias_validables '".$fechai."','".$fechaf."',3";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $reg  = "<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";
            $reg .=" " . $rs->fields[2]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[3]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[4]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[5]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[6]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[7]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[8]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[9]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='center'>\n";
            $reg .=" " . $rs->fields[10]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }

        $rs=null;   
    }
    return $lista;
}


function Delete(){
$rpta="OK";
//$rpta=$this->conectarme_ado();
$cn = $this->getMyConexionADO();
	if($cn){
        
		$ssql = "DELETE FROM CA_Reportes ";
		$ssql .= " WHERE Rep_Codigo =".$this->Rep_Codigo." ";

		$r= $cn->Execute($ssql);
	   
		if (!$r){
           $rpta="Error al eliminar";
	    }else{
		   $rpta='OK';
	  }
	 } 
	return $rpta;
 }


    function reporte_saldo_actual($area_codigo){
        $rpta="OK";
        $cn = $this->getMyConexionADO();
        if($cn){
            $ssql="exec spCA_SALDO_ACTUAL_EMPLEADO ".$area_codigo;
            $reg="";
            $lista="";
            $rs = $cn->Execute($ssql);
            while(!$rs->EOF){
                $reg  ="<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
                $reg .="<td align='left'>\n";
                $reg .=" " . $rs->fields[0]. "\n";
                $reg .="</td>\n";
                $reg .="<td>\n";
                $reg .=" " . $rs->fields[1]. "\n";
                $reg .="</td>\n";
                $reg .="<td>\n";
                $reg .=" " . $rs->fields[2]. "\n";
                $reg .="</td>\n";
                $reg .="<td>\n";
                $reg .=" " . $rs->fields[3]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[4]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[5]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[6]. "\n";
                $reg .="</td>\n";
                $reg .="</tr>\n";
                $lista .= $reg;
                $rs->MoveNext();
            }
            $rs=null;   
        }
        return $lista;



    }


    function reporte_salida_anticipada($mes,$anio,$area_codigo){

        $rpta="OK";
        $cn = $this->getMyConexionADO();
        if($cn){

        $ssql ="SELECT e.Empleado_Dni,e.Empleado_Apellido_Paterno+' '+e.Empleado_Apellido_Materno+' '+e.Empleado_Nombres as EMP, ";
        $ssql .="ar.Area_Descripcion, i.Item_Descripcion, ci.Incidencia_descripcion, ai.tiempo_minutos, CONVERT(VARCHAR(10),a.Asistencia_Fecha,103), "; 
        $ssql .="CASE WHEN e.Estado_Codigo = 1 THEN 'Activo' ELSE 'Cesado' END FROM CA_Asistencias a ";      
        $ssql .="INNER JOIN  CA_Asistencia_Incidencias ai ON a.Asistencia_codigo = ai.Asistencia_codigo "; 
        $ssql .="and ai.Empleado_Codigo = a.Empleado_Codigo and ai.Incidencia_codigo in (154,178) ";
        $ssql .="INNER JOIN CA_Incidencias ci on ci.Incidencia_codigo = ai.Incidencia_codigo ";  
        $ssql .="INNER JOIN vGrupo_Cargos_Amonestacion gc on gc.Cargo_Codigo = a.cargo_codigo "; 
        $ssql .="INNER JOIN Empleados e (nolock)  on e.Empleado_Codigo = a.Empleado_Codigo ";     
        $ssql .="INNER JOIN Areas ar on ar.Area_Codigo = a.area_codigo ";
        $ssql .="INNER JOIN Items i on i.Item_Codigo = a.cargo_codigo and Tabla_Codigo = 11 ";
        $ssql .="where a.Asistencia_fecha between CONVERT(VARCHAR(8),DATEADD(month,".$mes."-1,DATEADD(year,".$anio."-1900,0)),112) and CONVERT(VARCHAR(8),DATEADD(day,-1,DATEADD(month,".$mes.",DATEADD(year,".$anio."-1900,0))),112)";
        $ssql .="and CA_Estado_Codigo = 1 and a.Asistencia_entrada is not null ";
        if ($area_codigo != 0) {
            $ssql .= " and a.Area_codigo = ".$area_codigo;
        }
        // echo $ssql;die();
        $ssql .=" ORDER BY EMP ";
            $reg="";
            $lista="";
            $rs = $cn->Execute($ssql);
            while(!$rs->EOF){
                $reg  ="<tr class='CA_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>\n";
                $reg .="<td align='left'>\n";
                $reg .=" " . $rs->fields[0]. "\n";
                $reg .="</td>\n";
                $reg .="<td>\n";
                $reg .=" " . $rs->fields[1]. "\n";
                $reg .="</td>\n";
                $reg .="<td>\n";
                $reg .=" " . $rs->fields[2]. "\n";
                $reg .="</td>\n";
                $reg .="<td>\n";
                $reg .=" " . $rs->fields[3]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[4]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[5]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[6]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";
                $reg .=" " . $rs->fields[7]. "\n";
                $reg .="</td>\n";                
                $reg .="</tr>\n";
                $lista .= $reg;
                $rs->MoveNext();
            }
            $rs=null;   
        }
        return $lista;


    }


    function reporte_ausentismo_programado($fechai,$fechaf,$codigo_area){
        $rpta="OK";
        $cn = $this->getMyConexionADO();
        // $cn->debug=true;
        if($cn){

            $ssql = "select e.Empleado_Dni, e.empleado, e.Area_Descripcion, e.Modalidad_descripcion, e.Cargo_descripcion,Movimiento_Descripcion, ";
            $ssql .= "CONVERT(VARCHAR(10),Emp_Mov_Fecha_Inicio,103), CONVERT(VARCHAR(10),Emp_Mov_Fecha_Fin,103), CASE WHEN Emp_Mov_Fecha_Inicio < CONVERT(DATETIME,'".$fechai."',103) THEN ";
            $ssql .= "'".$fechai. "' ELSE CONVERT(VARCHAR(10),Emp_Mov_Fecha_Inicio,103) END, CASE WHEN Emp_Mov_Fecha_Fin > CONVERT(DATETIME,'".$fechaf."',103) THEN ";
            $ssql .= "'".$fechaf. "' ELSE CONVERT(VARCHAR(10),Emp_Mov_Fecha_Fin,103) END, DATEDIFF(DAY,(CASE WHEN Emp_Mov_Fecha_Inicio < CONVERT(DATETIME,'".$fechai."',103) THEN ";
            $ssql .= "CONVERT(DATETIME,'".$fechai."',103)  ELSE Emp_Mov_Fecha_Inicio END),CASE WHEN Emp_Mov_Fecha_Fin > CONVERT(DATETIME,'".$fechaf."',103) THEN ";
            $ssql .= "CONVERT(DATETIME,'".$fechaf."',103) ELSE Emp_Mov_Fecha_Fin END)+1 ";
            $ssql .= "FROM Empleado_Movimiento em inner join Movimiento m on m.Movimiento_codigo = em.Movimiento_codigo "; 
            $ssql .= "inner join vDatos e on e.Empleado_Codigo = em.Empleado_Codigo ";
            $ssql .= "where ((Emp_Mov_Fecha_Inicio >= CONVERT(DATETIME,'".$fechai."',103) and Emp_Mov_Fecha_Fin <= CONVERT(DATETIME,'".$fechaf."',103) and em.Movimiento_codigo in(5, 6, 8, 9 ) and Estado_codigo in (1,2)) OR ";
            $ssql .= "(Emp_Mov_Fecha_Inicio < CONVERT(DATETIME,'".$fechai."',103) and Emp_Mov_Fecha_Fin BETWEEN CONVERT(DATETIME,'".$fechai."',103) AND CONVERT(DATETIME,'".$fechaf."',103) and em.Movimiento_codigo in(5, 6, 8, 9 ) and Estado_codigo in (1,2)) OR ";
            $ssql .= "(Emp_Mov_Fecha_Fin > CONVERT(DATETIME,'".$fechaf."',103) and Emp_Mov_Fecha_Inicio BETWEEN CONVERT(DATETIME,'".$fechai."',103) AND CONVERT(DATETIME,'".$fechaf."',103) and em.Movimiento_codigo in(5, 6, 8, 9 ) and Estado_codigo in (1,2)) OR ";
            $ssql .= "(Emp_Mov_Fecha_Inicio < CONVERT(DATETIME,'".$fechai."',103) and Emp_Mov_Fecha_Fin > CONVERT(DATETIME,'".$fechaf."',103) and em.Movimiento_codigo in(5, 6, 8, 9 ) and Estado_codigo in (1,2))) ";
            if ($codigo_area != 0) {
                $ssql .= " and (e.Area_codigo = ".$codigo_area.')';
            }
            $ssql .= "UNION select e.Empleado_Dni,e.empleado, Area_Descripcion, Modalidad_descripcion, Cargo_descripcion , 'Vacaciones', ";
            $ssql .= "CONVERT(VARCHAR(10),fecha_inicio,103), CONVERT(VARCHAR(10),fecha_fin,103),CASE WHEN fecha_inicio < CONVERT(DATETIME,'".$fechai."',103) THEN ";
            $ssql .= "'".$fechai."' ELSE CONVERT(VARCHAR(10),fecha_inicio,103) END, CASE WHEN fecha_fin > CONVERT(DATETIME,'".$fechaf."',103) THEN '".$fechaf."' ELSE CONVERT(VARCHAR(10),fecha_fin,103) END, ";
            $ssql .= "DATEDIFF(DAY,(CASE WHEN fecha_inicio < CONVERT(DATETIME,'".$fechai."',103) THEN CONVERT(DATETIME,'".$fechai."',103) ELSE fecha_inicio END),CASE WHEN fecha_fin > CONVERT(DATETIME,'".$fechaf."',103) THEN ";
            $ssql .= "CONVERT(DATETIME,'".$fechaf."',103) ELSE fecha_fin END)+1 ";
            $ssql .= "FROM vacaciones_solicitud vs inner join vDatos e on e.Empleado_Codigo = vs.Empleado_codigo ";
            $ssql .= "where ((fecha_inicio >= CONVERT(DATETIME,'".$fechai."',103) and fecha_fin <= CONVERT(DATETIME,'".$fechaf."',103) and Estado_codigo = 1) OR ";
            $ssql .= "(fecha_inicio < CONVERT(DATETIME,'".$fechai."',103) and fecha_fin  BETWEEN CONVERT(DATETIME,'".$fechai."',103) AND CONVERT(DATETIME,'".$fechaf."',103) and Estado_codigo = 1) OR ";
            $ssql .= "(fecha_fin > CONVERT(DATETIME,'".$fechaf."',103) and fecha_inicio BETWEEN CONVERT(DATETIME,'".$fechai."',103) AND CONVERT(DATETIME,'".$fechaf."',103) and Estado_codigo = 1) OR ";
            $ssql .= "(fecha_inicio < CONVERT(DATETIME,'".$fechai."',103) and fecha_fin > CONVERT(DATETIME,'".$fechaf."',103) and Estado_codigo = 1)) ";
            if ($codigo_area != 0) {
                $ssql .= " and (e.Area_codigo = ".$codigo_area.')';
            }
            // echo $ssql;die();
            $ssql .= "order by 6 ";

            $reg="";
            $lista="";
            $rs = $cn->Execute($ssql);
            while(!$rs->EOF){
                $reg  = "<tr>\n";
                $reg .="<td class='text' align='left'>\n";//DNI
                // $reg .=" " . str_pad($rs->fields[0],8,'0',STR_PAD_LEFT). "\n";
                $reg .=" " . $rs->fields[0]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//EMPLEADO
                $reg .=" " . $rs->fields[1]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//AREA
                $reg .=" " . $rs->fields[2]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//MODALIDAD
                $reg .=" " . $rs->fields[3]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//CARGO
                $reg .=" " . $rs->fields[4]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//MOVIMIENTO
                $reg .=" " . $rs->fields[5]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";//FECHA INICIO
                $reg .=" " . $rs->fields[6]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";//FECHA FIN
                $reg .=" " . $rs->fields[7]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";//AUSENTISMO INICIO
                $reg .=" " . $rs->fields[8]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";//AUSENTISMO FIN
                $reg .=" " . $rs->fields[9]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='right'>\n";//DIAS AUSENTISMO
                $reg .=" " . $rs->fields[10]. "\n";
                $reg .="</td>\n";
                $reg .="  </tr>\n";
                $lista .= $reg;
                $rs->MoveNext();
            }
            $rs=null;   
        }
        return $lista;
    }
    
    function reporte_ausentismo_no_programado($fechai,$fechaf,$codigo_area){
        $rpta="OK";
        $cn = $this->getMyConexionADO();
        //$cn->debug=true;
        if($cn){
            $params = array($fechai, $fechaf);
            $ssql = "select e.Empleado_Dni, 
                    	e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres as empleado, 
                    	a.Area_Descripcion as area,
                        i.Item_Descripcion as cargo,
                    	ci.Incidencia_descripcion as incidencia,
                    	convert(varchar(10),ca.Asistencia_fecha,103) as asistencia,
                    	cai.tiempo_minutos 
                    from ca_asistencias ca 
                    	inner join ca_asistencia_incidencias cai on ca.Empleado_Codigo = cai.Empleado_Codigo and ca.Asistencia_codigo = cai.Asistencia_codigo
                    	inner join empleados e on ca.empleado_codigo = e.empleado_codigo
                    	inner join Areas a on ca.Area_Codigo = a.Area_Codigo 
                    	inner join CA_Incidencias ci on ci.Incidencia_codigo = cai.Incidencia_codigo
                        left join Items i on ca.cargo_codigo = i.Item_Codigo
                    where cai.Incidencia_codigo in (38,7,154,178) 
                        and ca.Asistencia_fecha between convert(datetime,?,103) and convert(datetime,?,103) 
                        and ca.CA_Estado_Codigo = 1";
            if ($codigo_area != 0) {
                $params[] = $codigo_area;
                $ssql .= " and (a.Area_codigo = ".$codigo_area.')';
            }
            $ssql .= " order by ca.Asistencia_fecha,2";

            $reg="";
            $lista="";
            $rs = $cn->Execute($ssql,$params);
            while(!$rs->EOF){
                $reg  = "<tr>\n";
                $reg .="<td class='text' align='left'>\n";//DNI
                // $reg .=" " . str_pad($rs->fields[0],8,'0',STR_PAD_LEFT). "\n";
                $reg .=" " . $rs->fields[0]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//EMPLEADO
                $reg .=" " . $rs->fields[1]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//AREA
                $reg .=" " . $rs->fields[2]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//CARGO
                $reg .=" " . $rs->fields[3]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='left'>\n";//INCIDENCIA
                $reg .=" " . $rs->fields[4]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";//FECHA 
                $reg .=" " . $rs->fields[5]. "\n";
                $reg .="</td>\n";
                $reg .="<td align='center'>\n";//TIEMPO MINUTOS
                $reg .=" " . $rs->fields[6]. "\n";
                $reg .="</td>\n";
                $reg .="  </tr>\n";
                $lista .= $reg;
                $rs->MoveNext();
            }
            $rs=null;   
        }
        return $lista;
    }
    function Delete_Temp(){
      $rpta="OK";
      $cn = $this->getMyConexionADO();

      $params = array($this->empleado_codigo);
      if($cn){
        $ssql = "DELETE from Reporte_Tmp where Empleado_Codigo = ?";
        $rs =$cn->Execute($ssql,$params);
      }
      return $rpta;
    }
    function  Addnew_Temp(){
      $rpta="OK";
      $cn = $this->getMyConexionADO();

        // $cn->debug = true;
      $params = array(  
          $this->dni, 
          $this->nombres,
          $this->empleado_codigo
      );
      $ssql = "INSERT INTO Reporte_Tmp";
      $ssql.= " (DNI, NOMBRES, Empleado_Codigo) ";
      $ssql.= " VALUES (?,?,?)";
      $rs= $cn->Execute($ssql, $params);
      return $rpta;
    }    

    function Cargados_Temp($usuario){

        $rpta="OK";
        $cn = $this->getMyConexionADO();
        $params = array($usuario);
        $ssql = "SELECT DNI, NOMBRES from Reporte_Tmp WHERE Empleado_Codigo = ?";
        $reg="";
        $lista="";
        $rs = $cn->Execute($ssql,$params);
        while(!$rs->EOF){
            $reg  = "<tr>\n";
            $reg .="<td class='text' align='left'>\n";//DNI
            $reg .=" " . $rs->fields[0]. "\n";
            $reg .="</td>\n";
            $reg .="<td align='left'>\n";//EMPLEADO
            $reg .=" " . $rs->fields[1]. "\n";
            $reg .="</td>\n";
            $reg .="  </tr>\n";
            $lista .= $reg;
            $rs->MoveNext();
        }

      return $lista;

    }
}