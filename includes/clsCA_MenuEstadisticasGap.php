<?php

require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("clsCA_Estadisticas.php");
require_once('../../Includes/JSON/JSON.php');

header('Content-type: text/json');
set_time_limit (300);

$json = new services_JSON();

$action = $_REQUEST["action"];
$superiorEmpleados = $_REQUEST["superiorEmpleados"];

if ($action == "obtenerDetalleEstadisticas")
{
	$flag_consulta = $_REQUEST["consulta"];
	$flag_tipo_usuario = $_REQUEST["flag_tipo_usuario"];
	
	$obj = new ca_estadisticas();
	$obj->setMyUrl(db_host());
	$obj->setMyUser(db_user());
	$obj->setMyPwd(db_pass());
	$obj->setMyDBName(db_name());
	
	if($flag_consulta != "6")
	{
		$empleados = obtenerDetalleEstadisticas($superiorEmpleados, $obj, $flag_consulta, $flag_tipo_usuario);
	}
	else
	{
		$empleados = obtenerDetalleEstadisticasLicencias($superiorEmpleados, $obj, $flag_tipo_usuario);
	}
	echo $json->encode($empleados, 'JSON_FORCE_OBJECT');
}


function obtenerDetalleEstadisticas($superiorEmpleados, $obj, $flag_consulta, $flag_tipo_usuario)
{
	$result = null;
	$rpta = "";
	
	if($flag_consulta == "1")
	{
		$rs = $obj->retornaEmpleadosDependientes($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	else if($flag_consulta == "2")
	{
		$rs = $obj->retornaEmpleadosDependientesAsistencias($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	else if($flag_consulta == "3")
	{
		$rs = $obj->retornaEmpleadosDependientesInasistencias($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	else if($flag_consulta == "4")
	{
		$rs = $obj->retornaEmpleadosDependientesTardanzas($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	else if($flag_consulta == "5")
	{
		$rs = $obj->retornaEmpleadosDependientesVacaciones($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	else if($flag_consulta == "7")
	{
		$rs = $obj->retornaEmpleadosNoSujetoControl($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	else if($flag_consulta == "8")
	{
		$rs = $obj->retornaEmpleadosDiferenciaLoggerMarcacion($superiorEmpleados, 1, $flag_tipo_usuario);
	}
	if($rs)
	{
		$result = array();
		$temporal = array();
		$counter = 0;
		while(!$rs->EOF)
		{
			$counter = $counter + 1;
			$temporal['numeroRegistro'] = $counter;
			$temporal['empleadoCodigo'] = $rs->fields[0];
			$temporal['nombres'] = $rs->fields[1];
			$temporal['area'] = $rs->fields[2];
			$result[] = $temporal;
			$rs->MoveNext();
		}
		$rs->close();
		$rs=null;
	}
	return $result;
}

function obtenerDetalleEstadisticasLicencias($superiorEmpleados, $obj, $flag_tipo_usuario)
{
	$result = null;
	$rpta = "";
	
	$rs = $obj->retornaEmpleadosDependientesLicencias($superiorEmpleados, 1, $flag_tipo_usuario);
	
	if($rs)
	{
		$result = array();
		$temporal = array();
		$counter = 0;
		while(!$rs->EOF)
		{
			$counter = $counter + 1;
			$temporal['numeroRegistro'] = $counter;
			$temporal['empleadoCodigo'] = $rs->fields[0];
			$temporal['nombres'] = $rs->fields[1];
			$temporal['area'] = $rs->fields[2];
			$temporal['movimiento'] = $rs->fields[3];
			$result[] = $temporal;
			$rs->MoveNext();
		}
		$rs->close();
		$rs=null;
	}
	return $result;
}
?>