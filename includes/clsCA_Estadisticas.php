<?php 

class ca_estadisticas extends mantenimiento{

	function retornaAreasDependientes($empleado_codigo)
	{
		$oArea = new Areas();
		$oArea->MyUrl = $this->getMyUrl();
		$oArea->MyUser= $this->getMyUser();
		$oArea->MyPwd = $this->getMyPwd();
		$oArea->MyDBName= $this->getMyDBName();
		
		$oArea->empleado_responsable=$empleado_codigo;
		$codCadena =$oArea->Areas_Dependientes();
		
		return $codCadena;
	}
	
function retornaAsignacionesSupervision($empleado_codigo){
        /*$rpta=$this->conectarme_ado();
        $ssql = "SELECT DISTINCT C.RESPONSABLE_CODIGO FROM VDATOS E  ";
        $ssql .= "INNER JOIN CA_ASIGNACION_EMPLEADOS C (NOLOCK) ";
        $ssql .= "	ON C.EMPLEADO_CODIGO = E.EMPLEADO_CODIGO ";
        $ssql .= "WHERE C.RESPONSABLE_CODIGO= " . $empleado_codigo . " AND C.ASIGNACION_ACTIVO=1 ";

        $rs= $this->cnnado->Execute($ssql);
        $cadena = "";
        while(!$rs->EOF)
        {
                $cadena .= $rs->fields[0]->value;
                $rs->MoveNext();
        }
        return $cadena;*/
            
        $cn=$this->getMyConexionADO();
        
        $ssql = "SELECT DISTINCT C.RESPONSABLE_CODIGO FROM VDATOS E  ";
        $ssql .= "INNER JOIN CA_ASIGNACION_EMPLEADOS C (NOLOCK) ";
        $ssql .= "	ON C.EMPLEADO_CODIGO = E.EMPLEADO_CODIGO ";
        $ssql .= "WHERE C.RESPONSABLE_CODIGO= " . $empleado_codigo . " AND C.ASIGNACION_ACTIVO=1 ";
		
        $rs= $cn->Execute($ssql);
        $cadena = "";
        while(!$rs->EOF){
            $cadena .= $rs->fields[0];
            $rs->MoveNext();
        }
        return $cadena;
}
	
	function retornaEmpleadosDependientes($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();

		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql = "SELECT COUNT(*) FROM VDATOS V (NOLOCK) WHERE ";
			$ssql .= $flag_tipo_usuario == 1 ? "AREA_CODIGO IN (" . $dependientes . ")" : "EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
		}
		else //listado de dependientes
		{
			$ssql = "SELECT V.EMPLEADO_CODIGO, V.EMPLEADO, V.AREA_DESCRIPCION FROM VDATOS V (NOLOCK) WHERE ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " ORDER BY 3, 2";
		}
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
        
	}

	function retornaEmpleadosDependientesAsistencias($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql =  "SELECT COUNT(*) FROM VDATOS V  (NOLOCK)";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON	CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";			
			$ssql .= "INNER JOIN CA_ASISTENCIA_PROGRAMADA CAP (NOLOCK) ";
			$ssql .= "ON	CAP.EMPLEADO_CODIGO = CA.EMPLEADO_CODIGO AND ";
			$ssql .= "		CAP.ASISTENCIA_FECHA = CA.ASISTENCIA_FECHA  ";
			$ssql .= "WHERE  ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "CA.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= "AND CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CA.ASISTENCIA_ENTRADA IS NOT NULL ";
			$ssql .= "AND V.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA)";
		}
		else //listado de dependientes
		{
			$ssql =  "SELECT V.EMPLEADO_CODIGO,V.EMPLEADO,V.AREA_DESCRIPCION FROM VDATOS V  (NOLOCK) ";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON	CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";			
			$ssql .= "INNER JOIN CA_ASISTENCIA_PROGRAMADA CAP ";
			$ssql .= "ON	CAP.EMPLEADO_CODIGO = CA.EMPLEADO_CODIGO AND ";
			$ssql .= "		CAP.ASISTENCIA_FECHA = CA.ASISTENCIA_FECHA  ";
			$ssql .= "WHERE  ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "CA.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= "AND CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CA.ASISTENCIA_ENTRADA IS NOT NULL ";
			$ssql .= "AND V.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA)";
			$ssql .= "ORDER BY 3, 2 ";
		}
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
	}	


	function retornaEmpleadosDependientesInasistencias($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql = "SELECT COUNT(*) FROM VDATOS V (NOLOCK) ";
			$ssql .= "	INNER JOIN CA_ASISTENCIA_PROGRAMADA CAP (NOLOCK) ";
			$ssql .= "	ON	CAP.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO AND ";
			$ssql .= "	CAP.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) WHERE ";
			$ssql .=  $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " AND CAP.ASISTENCIA_ENTRADA IS NULL ";
			$ssql .= " AND CAP.EMPLEADO_CODIGO NOT IN ( ";
			$ssql .= " SELECT EMPLEADO_CODIGO FROM VCA_ASISTENCIA_RESPONSABLES (NOLOCK) ";
			$ssql .= " WHERE ASISTENCIA_FECHA=CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) AND ";
			$ssql .=  $flag_tipo_usuario == 1 ? " AREA_CODIGO IN (" . $dependientes . ")" : "RESPONSABLE_CODIGO = " . $dependientes;		
			$ssql .= ")";
			$ssql .= " AND V.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA)";
		}
		else //listado de dependientes
		{
			$ssql = "SELECT V.EMPLEADO_CODIGO,V.EMPLEADO,V.AREA_DESCRIPCION FROM VDATOS V (NOLOCK) ";
			$ssql .= "INNER JOIN CA_ASISTENCIA_PROGRAMADA CAP (NOLOCK) ";
			$ssql .= "ON	CAP.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO AND ";
			$ssql .= "CAP.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) WHERE ";
			$ssql .= $flag_tipo_usuario == 1 ? " V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " AND CAP.ASISTENCIA_ENTRADA IS NULL ";
			$ssql .= " AND CAP.EMPLEADO_CODIGO NOT IN ( ";
			$ssql .= " SELECT EMPLEADO_CODIGO FROM VCA_ASISTENCIA_RESPONSABLES ";
			$ssql .= " WHERE ASISTENCIA_FECHA=CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) AND  ";
			$ssql .= $flag_tipo_usuario == 1 ? " AREA_CODIGO IN (" . $dependientes . ")" : "RESPONSABLE_CODIGO = " . $dependientes;
			$ssql .= " ) AND V.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA) ORDER BY 3, 2 ";
		}
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
	}

	function retornaEmpleadosDependientesTardanzas($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();
		// print_r(array($dependientes, $flag_detalle, $flag_tipo_usuario));
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql = "select count(*) from CA_Asistencia_Incidencias cai  (NOLOCK) ";
			$ssql .= "inner join ca_asistencias ca (NOLOCK) on ca.Empleado_codigo = cai.Empleado_codigo ";
			$ssql .= "and ca.Asistencia_codigo = cai.Asistencia_codigo ";
			$ssql .= "WHERE ";
			$ssql .= "ca.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= $flag_tipo_usuario == 1 ? "AND cai.AREA_CODIGO IN (" . $dependientes . ")" : "AND cai.responsable_codigo =".$dependientes;
			$ssql .= " AND Incidencia_codigo = 7 ";
			$ssql .= "AND CA.ASISTENCIA_ENTRADA IS NOT NULL ";			
			$ssql .= "AND cai.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA)";
		}
		else //listado de dependientes
		{
			$ssql = "select v.Empleado_Codigo,v.empleado, v.Area_Descripcion from CA_Asistencia_Incidencias cai  (NOLOCK) ";
			$ssql .= "inner join ca_asistencias ca (NOLOCK) on ca.Empleado_codigo = cai.Empleado_codigo ";
			$ssql .= "and ca.Asistencia_codigo = cai.Asistencia_codigo ";			
			$ssql .= "inner join vDatos v (NOLOCK) on v.Empleado_Codigo = cai.Empleado_Codigo ";			
			$ssql .= "WHERE ";
			$ssql .= "ca.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= $flag_tipo_usuario == 1 ? "AND cai.AREA_CODIGO IN (" . $dependientes . ")" : "AND cai.responsable_codigo =".$dependientes;
			$ssql .= " AND Incidencia_codigo = 7 ";
			$ssql .= "AND CA.ASISTENCIA_ENTRADA IS NOT NULL ";			
			$ssql .= "AND cai.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA) ";
			$ssql .= "ORDER BY 3, 2 ";			
		}
		// echo $ssql;die();
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
	}

	function retornaEmpleadosDependientesVacaciones($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql = "SELECT COUNT(*) FROM VDATOS V (NOLOCK)";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";
			$ssql .= "INNER JOIN CA_ASISTENCIA_INCIDENCIAS CAI (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = CAI.EMPLEADO_CODIGO AND ";
			$ssql .= "CA.ASISTENCIA_CODIGO = CAI.ASISTENCIA_CODIGO ";
			$ssql .= "WHERE CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CAI.INCIDENCIA_CODIGO = 15 AND ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " AND CA.CA_ESTADO_CODIGO = 1 ";
		}
		else //listado de dependientes
		{
			$ssql = "SELECT V.EMPLEADO_CODIGO,V.EMPLEADO,V.AREA_DESCRIPCION FROM VDATOS V (NOLOCK) ";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";
			$ssql .= "INNER JOIN CA_ASISTENCIA_INCIDENCIAS CAI (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = CAI.EMPLEADO_CODIGO AND ";
			$ssql .= "CA.ASISTENCIA_CODIGO = CAI.ASISTENCIA_CODIGO ";
			$ssql .= "WHERE CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CAI.INCIDENCIA_CODIGO = 15 AND ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " AND CA.CA_ESTADO_CODIGO = 1 ";
			$ssql .= "ORDER BY 3, 2 ";
		}
		
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
	}

	function retornaEmpleadosDependientesLicencias($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql = "SELECT COUNT(*) FROM VDATOS V (NOLOCK) ";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";
			$ssql .= "INNER JOIN CA_ASISTENCIA_INCIDENCIAS CAI (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = CAI.EMPLEADO_CODIGO AND ";
			$ssql .= "CA.ASISTENCIA_CODIGO = CAI.ASISTENCIA_CODIGO ";
			$ssql .= "WHERE CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CAI.INCIDENCIA_CODIGO IN (5,6,9,14) AND ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " AND CA.CA_ESTADO_CODIGO = 1 ";
		}
		else //listado de dependientes
		{
			$ssql = "SELECT V.EMPLEADO_CODIGO,V.EMPLEADO,V.AREA_DESCRIPCION, CI.INCIDENCIA_DESCRIPCION FROM VDATOS V ";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";
			$ssql .= "INNER JOIN CA_ASISTENCIA_INCIDENCIAS CAI (NOLOCK) ";
			$ssql .= "ON CA.EMPLEADO_CODIGO = CAI.EMPLEADO_CODIGO AND ";			
			$ssql .= "CA.ASISTENCIA_CODIGO = CAI.ASISTENCIA_CODIGO ";
			$ssql .= "INNER JOIN CA_INCIDENCIAS CI (NOLOCK) ";
			$ssql .= "ON CAI.INCIDENCIA_CODIGO = CI.INCIDENCIA_CODIGO ";
			$ssql .= "WHERE CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CAI.INCIDENCIA_CODIGO IN (5,6,9,14) AND ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= " AND CA.CA_ESTADO_CODIGO = 1 ";
			$ssql .= "ORDER BY 3, 2 ";
		}
		
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
	}
	
	function retornaEmpleadosNoSujetoControl($dependientes, $flag_detalle, $flag_tipo_usuario)
	{
		$cn = $this->getMyConexionADO();
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql = "SELECT COUNT(*) FROM VCA_NOSUJETO_CONTROLASISTENCIA V ";
			$ssql .= "INNER JOIN VDATOS VD (NOLOCK) ";
			$ssql .= "	ON V.EMPLEADO_CODIGO = VD.EMPLEADO_CODIGO AND ";
			$ssql .= $flag_tipo_usuario == 1 ? "VD.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
		}
		else //listado de dependientes
		{
			$ssql = "SELECT V.EMPLEADO_CODIGO, VD.EMPLEADO, VD.AREA_DESCRIPCION FROM VCA_NOSUJETO_CONTROLASISTENCIA V ";
			$ssql .= "INNER JOIN VDATOS VD (NOLOCK) ";
			$ssql .= "	ON V.EMPLEADO_CODIGO = VD.EMPLEADO_CODIGO AND ";
			$ssql .= $flag_tipo_usuario == 1 ? "VD.AREA_CODIGO IN (" . $dependientes . ")" : "V.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1)";
			$ssql .= "ORDER BY 3, 2 ";
		}
		
		$rs= $cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
	}


	
	function retornaEmpleadosDiferenciaLoggerMarcacion($dependientes, $flag_detalle, $flag_tipo_usuario)
	{


		$cn = $this->getMyConexionADO();
		// $cn->debug = true;
		$ssql = "SET ANSI_NULLS ON SET ANSI_WARNINGS ON ";
		$cn->Execute($ssql);
		if($flag_detalle == 0) //numero de dependientes
		{
			$ssql =  "SELECT COUNT(*) FROM VDATOS V  (NOLOCK) ";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON	CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";			
			$ssql .= "INNER JOIN CA_ASISTENCIA_PROGRAMADA CAP (NOLOCK) ";
			$ssql .= "ON	CAP.EMPLEADO_CODIGO = CA.EMPLEADO_CODIGO AND ";
			$ssql .= "		CAP.ASISTENCIA_FECHA = CA.ASISTENCIA_FECHA  ";
			$ssql .= "WHERE  ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "CA.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1) ";
			$ssql .= "AND CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CA.ASISTENCIA_ENTRADA IS NOT NULL ";
			$ssql .= "AND v.Empleado_Codigo IN (select Empleado_Codigo from [SAPLCCc320].[SEGURIDAD].[dbo].[vLOGGER_USO] where Empleado_Codigo= V.Empleado_Codigo and Uso_Tipo = 'L' and FECHA = CA.Asistencia_fecha and INICIO >= DATEADD(MINUTE,CAST((SELECT item_default from Items WHERE Item_codigo = 818) as INT),CA.Asistencia_entrada)) ";
			$ssql .= "AND V.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA)";
		}
		else //listado de dependientes
		{
			$ssql =  "SELECT V.EMPLEADO_CODIGO,V.EMPLEADO,V.AREA_DESCRIPCION FROM VDATOS V  (NOLOCK) ";
			$ssql .= "INNER JOIN CA_ASISTENCIAS CA (NOLOCK) ";
			$ssql .= "ON	CA.EMPLEADO_CODIGO = V.EMPLEADO_CODIGO ";			
			$ssql .= "INNER JOIN CA_ASISTENCIA_PROGRAMADA CAP ";
			$ssql .= "ON	CAP.EMPLEADO_CODIGO = CA.EMPLEADO_CODIGO AND ";
			$ssql .= "		CAP.ASISTENCIA_FECHA = CA.ASISTENCIA_FECHA  ";
			$ssql .= "WHERE  ";
			$ssql .= $flag_tipo_usuario == 1 ? "V.AREA_CODIGO IN (" . $dependientes . ")" : "CA.EMPLEADO_CODIGO IN (SELECT EMPLEADO_CODIGO FROM CA_ASIGNACION_EMPLEADOS (NOLOCK)  WHERE RESPONSABLE_CODIGO=" . $dependientes . " AND ASIGNACION_ACTIVO=1) ";
			$ssql .= "AND CA.ASISTENCIA_FECHA = CONVERT(DATETIME, CONVERT(VARCHAR(8), GETDATE(), 112), 103) ";
			$ssql .= "AND CA.ASISTENCIA_ENTRADA IS NOT NULL ";
			$ssql .= "AND v.Empleado_Codigo IN (select Empleado_Codigo from [SAPLCCc320].[SEGURIDAD].[dbo].[vLOGGER_USO] where Empleado_Codigo= V.Empleado_Codigo and Uso_Tipo = 'L' and FECHA = CA.Asistencia_fecha and INICIO >= DATEADD(MINUTE,CAST((SELECT item_default from Items WHERE Item_codigo = 818) as INT),CA.Asistencia_entrada)) ";
			$ssql .= "AND V.EMPLEADO_CODIGO NOT IN (SELECT EMPLEADO_CODIGO FROM VCA_NOSUJETO_CONTROLASISTENCIA) ";
			$ssql .= "ORDER BY 3, 2 ";
		}
		$rs= $cn->Execute($ssql);
		$ssql = "SET ANSI_NULLS OFF SET ANSI_WARNINGS OFF ";
		$cn->Execute($ssql);
		if($flag_detalle == 0)
		{
			$valor = $rs->fields[0];
			$valor = $valor == null || $valor == "" ? 0 : $valor;
			return $valor;
		}
		else
		{
			return $rs;
		}
		

	}

	function minutoparametroGAP(){
		$cn = $this->getMyConexionADO();
		$ssql =  "SELECT item_default FROM Items where item_codigo = 818";
		$rs= $cn->Execute($ssql);
		return $rs->fields[0];
	}


}
?>