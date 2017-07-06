<?php
require_once("mantenimiento.php");
class Areas extends mantenimiento{
var $area_codigo = "";
var $area_descripcion = "";
var $area_jefe = "";
var $area_orden = "";
var $area_nivel_org = "";
var $tipo_area_codigo = "";
var $ccto_codigo = "";
var $ccr_codigo = "";
var $ccb_codigo = "";
var $area_activo = "";
var $area_padre = "";
var $area_funcional_gestion = "";
var $coordinacion_codigo_gestion = "";
var $usuario_responsable='';
//--reponsable de area
var $empleado_responsable='';
var $usuario_asigna_responsable='';
var $empleado_responsable_nombre='';
var $supervisor_id='0';
var $cargo_id='0';
//--jerarquia
var $jerarquia_id='';
var $area_nivel_org_jefe='';
var $ccto_descripcion = "";
var $areas = array();
var $nuevo_area_codigo="";
function query(){ //recuperar registro
	$rpta="Ok";

	$sSql = "SELECT Area_Codigo, Area_Descripcion, Area_Jefe, Area_Orden, Area_Nivel_Org, abs(Tipo_Area_Codigo), Areas.Ccto_Codigo, Ccr_Codigo, ";
	$sSql .= "  Areas.Ccb_Codigo, Area_Activo, Area_Padre, Area_Funcional_gestion, Coordinacion_Codigo_gestion, ";
	$sSql .= "  empleado_responsable, dbo.udf_empleado_nombre(empleado_responsable) as responsable,jerarquia_id ";
	$sSql .= " , centro_costo.ccto_descripcion ";
	$sSql .= " FROM Areas(nolock) ";
	$sSql .= " inner join centro_costo on centro_costo.ccto_codigo=areas.ccto_codigo ";
	$sSql .= " WHERE Area_Codigo = ?";

	$params = array(
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

	if ($rs->RecordCount() > 0)
	{
		$this->area_descripcion = $rs->fields[1];
		$this->area_jefe = $rs->fields[2];
		$this->area_orden = $rs->fields[3];
		$this->area_nivel_org = $rs->fields[4];
		$this->tipo_area_codigo = $rs->fields[5];
		$this->ccto_codigo = $rs->fields[6];
		$this->ccr_codigo = $rs->fields[7];
		$this->ccb_codigo = $rs->fields[8];
		$this->area_activo = $rs->fields[9];
		$this->area_padre = $rs->fields[10];
		$this->area_funcional_gestion = $rs->fields[11];
		$this->coordinacion_codigo_gestion = $rs->fields[12];
		$this->empleado_responsable=$rs->fields[13];
		$this->empleado_responsable_nombre=$rs->fields[14];
		$this->jerarquia_id=$rs->fields[15];
		$this->ccto_descripcion=$rs->fields[16];

	}else{
		$rpta='No Existe Registro de Area: ' . $this->area_codigo;
	}
	return $rpta;
}



function getDiaActual(){
    $cn=$this->getMyConexionADO();
    //-- Obtener dia actual
    $sSql="SELECT top 1 day(getdate()) as dia FROM estados(nolock)";
    $rs=$cn->Execute($sSql);
    $dia_actual=$rs->fields[0];
    return $dia_actual;
}


function getDiaPermitido(){
    $cn=$this->getMyConexionADO();
    //--Obtener dia permitido
    $dia=16; //<----- fecha de cierre **
    $sSql="SELECT Item_Default";
    $sSql.=" FROM Items";
    $sSql.=" WHERE (Tabla_Codigo = 45) AND (Item_Codigo = 516) AND (Item_Activo = 1)";
    
    $rs=$cn->Execute($sSql);
    if($rs->RecordCount()>0){
        $dia=$rs->fields[0];
    }
    
    return $dia;
}


function query_jefe(){ //recuperar registro
	$rpta="Ok";

	$sSql = "SELECT     Areas.Area_Codigo, Areas.Area_Descripcion,
                        Areas.Area_Nivel_Org, Areas.Area_Jefe,
                        Areas_1.Area_Descripcion AS area_descripcion_jefe,
                        Areas_1.Area_Nivel_Org AS nivel_area_jefe
            FROM        Areas INNER JOIN
                        Areas Areas_1 ON Areas.Area_Jefe = Areas_1.Area_Codigo
            WHERE     (Areas.Area_Codigo = ?)";


	$params = array(
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

	if ($rs && $rs->RecordCount() > 0)
	{
		$this->area_descripcion = $rs->fields[4];
		$this->area_jefe = $rs->fields[3];
		$this->area_nivel_org_jefe = $rs->fields[5];

	}else{
		$rpta='No Existe Registro de Area: ' . $this->area_codigo;
	}
	return $rpta;
}

function Addnew(){
	$rpta="Ok";
	$rpta="Ok";
    
	//Obtener el Nivel organico del area
	$sSql = "SELECT Area_Nivel_Org FROM areas(nolock) WHERE area_codigo = ?";
	$params = array($this->area_codigo);
	$cn = $this->getMyConexionADO();
	$cn->BeginTrans();
	$rs = $cn->Execute($sSql, $params);

	//obtener nuevo codigo de registro a insertar
	$sSql = "SELECT isnull(max(area_codigo), 0) + 1 as maximo FROM areas(nolock) ";
	$rs1 = $cn->Execute($sSql);

	//Obtener el orden a ocupar por la nueva area
	$sSql = "select isnull(max(area_orden), 0) + 1 as orden from areas(nolock) where area_jefe = ?";
	$params = array($this->area_codigo);
	$rs2 = $cn->Execute($sSql, $params);
    $this->nuevo_area_codigo = $rs1->fields[0];

	//insertar nuevo registro
	$sSql = "INSERT INTO Areas";
	$sSql .= " (Area_Codigo, Area_Descripcion, Tipo_Area_Codigo, coordinacion_codigo_gestion, Ccto_Codigo,";
	$sSql .= " Ccr_Codigo, Ccb_Codigo, Area_Jefe, Area_Orden, Area_Nivel_Org, area_fecha_reg, usuario_responsable,";
	$sSql .= " empleado_responsable, fecha_asigna_responsable,usuario_asigna_responsable,jerarquia_id)";
	$sSql .= " VALUES ('" . $rs1->fields[0] . "',";
	$sSql .= " upper('" . $this->area_descripcion . "'),";
	$sSql .= " '" . $this->tipo_area_codigo . "',";
	$sSql .= " '" . $this->coordinacion_codigo_gestion . "',";
	if ($this->ccto_codigo == '0'){
		$sSql .= " null,";
	}else{
		$sSql .= " '" . $this->ccto_codigo . "',";
	}

	if ($this->ccr_codigo == '0'){
		$sSql .= " null,";
	}else{
		$sSql .= " '" . $this->ccr_codigo . "',";
	}

	if ($this->ccb_codigo == '0'){
		$sSql .= " null,";
	}else{
		$sSql .= " '" . $this->ccb_codigo . "',";
	}
	$sSql .= " '" . $this->area_codigo . "',";
	$sSql .= " '" . $rs2->fields[0] . "',";
	$sSql .=  ($rs->fields[0] +1) . ", getdate(),";
	$sSql .= $this->usuario_responsable . ",";
	$sSql .= $this->empleado_responsable . ", getdate(),";
	$sSql .= $this->usuario_responsable . ",";
	$sSql .= $this->jerarquia_id . ")";

	$rsExec = $cn->Execute($sSql);
	if (!$rsExec)
	{
		$rpta = "Error al Insertar nuevo Registro de Area.";
		$cn->RollbackTrans();
		return $rpta;
	}
	//--actualizar falg de empleado responsable de area en personal
	$sSql="UPDATE Empleados SET Empleado_Responsable_Area = 1 " .
		" WHERE Empleado_Codigo = ?";

	$params = array(
		$this->empleado_responsable
	);

	$rsExec = $cn->Execute($sSql, $params);
	if (!$rsExec)
	{
		$rpta = "Error al actualizar Empleado Responsable de Area.";
		$cn->RollbackTrans();
		return $rpta;
	}
	//-- crear unidad de servicio administrativo en gestion
	$sSql = "exec SP_RRHH_Area_Gestion ?";

	$params = array(
		$rs1->fields[0]
	);

	$rsExec = $cn->Execute($sSql, $params);
	if (!$rsExec)
	{
		$rpta = "Error al crear unidad de servicio administrativo en gestion.";
		$cn->RollbackTrans();
		return $rpta;
	}

	$sSql = "spRRHH_Areas_Padres";
	$rsExec = $cn->Execute($sSql);
	if (!$rsExec)
	{
		$rpta = "Error al procesar areas-padres.";
		$cn->RollbackTrans();
		return $rpta;
	}
	$cn->CommitTrans();
	return $rpta;
}

function Update(){
	$rpta="Ok";

	//-- obtener codigo de responsable de area actual
	$sSql="select isnull(empleado_responsable,0) from Areas " .
		" WHERE Area_Codigo = ?";

	$params = array(
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$cn->BeginTrans();
	$rs = $cn->Execute($sSql, $params);

	if ($rs && $rs->RecordCount() > 0)
	{
		//-- preguntar si es responsable de otras areas
		$sSql="select count(area_codigo) as t from areas where area_codigo<>$this->area_codigo and empleado_responsable=$rs->fields[0] and area_activo=1";

		$rst = $cn->Execute($sSql);
		if ($rst && $rst->RecordCount() > 0)
		{
			if ($rst->fields[0] == 0)
			{ //-- si no hay otras areas desactivamos
				$sSql="UPDATE Empleados SET Empleado_Responsable_Area = 0 " .
					" WHERE Empleado_Codigo = ?";

				$params = array(
					$rst->fields[0]
				);
				$rsExec = $cn->Execute($sSql, $params);

				if (!$rsExec)
				{
					$rpta = "Error al desactivar Empleado Responsable de Area.";
					$cn->RollbackTrans();
					return $rpta;
				}
			}
		}
	}

	//--
	$sSql = "UPDATE Areas ";
	$sSql .= " SET Area_Descripcion = upper('" . $this->area_descripcion . "'),";
	if ($this->ccto_codigo == '0'){
		$sSql .= " 	   Ccto_Codigo = null,";
	}else{
		$sSql .= " 	   Ccto_Codigo = '" . $this->ccto_codigo . "',";
	}

	if ($this->ccr_codigo == '0'){
		$sSql .= " 	   Ccr_Codigo = null,";
	}else{
		$sSql .= " 	   Ccr_Codigo = '" . $this->ccr_codigo . "',";
	}

	if ($this->ccb_codigo == '0'){
		$sSql .= " 	   Ccb_Codigo = null,";
	}else{
		$sSql .= " 	   Ccb_Codigo = '" . $this->ccb_codigo . "',";
	}
	$sSql .= "     coordinacion_codigo_gestion=" . $this->coordinacion_codigo_gestion . ",";
	$sSql .= "     Tipo_Area_Codigo= " . $this->tipo_area_codigo . ",";
	// $sSql .= "     Area_fecha_reg= getdate(),";
	$sSql .= "     usuario_responsable= " . $this->usuario_responsable . ",";
	$sSql .= "     empleado_responsable= " . $this->empleado_responsable . ",";
	$sSql .= "     fecha_asigna_responsable= getdate(),";
	$sSql .= "     usuario_asigna_responsable= " . $this->usuario_responsable.",";
	$sSql .= "     jerarquia_id= " . $this->jerarquia_id;
	$sSql .= " Where Area_Codigo = " . $this->area_codigo;

	$rsExec = $cn->Execute($sSql);
	if (!$rsExec){
		$rpta = "Error al Actualizar Datos del Area.";
		$cn->RollbackTrans();
		return $rpta;
	}

	$sSql="UPDATE Empleados SET Empleado_Responsable_Area = 1 " .
		" WHERE Empleado_Codigo = ?";

	$params = array(
		$this->empleado_responsable
	);

	$rsExec = $cn->Execute($sSql, $params);
	if (!$rsExec)
	{
		$rpta = "Error al actualizar Empleado Responsable de Area.";
		$cn->RollbackTrans();
		return $rpta;
	}

	 //-- crear unidad de servicio administrativo en gestion
	$sSql = "exec SP_RRHH_Area_Gestion ?";
	$params = array(
		$this->area_codigo
	);

	$rsExec = $cn->Execute($sSql, $params);
	if (!$rsExec){
		$rpta = "Error al crear unidad de servicio administrativo en gestion.";
		$cn->RollbackTrans();
		return $rpta;
	}

	//inicio actualizacion de empleados
/*	$sSql = "UPDATE empleados ";
	$sSql .= " SET ";
	if ($this->ccto_codigo == '0'){
		$sSql .= " 	   Ccto_Codigo = null,";
	}else{
		$sSql .= " 	   Ccto_Codigo = '" . $this->ccto_codigo . "',";
	}
	if ($this->ccr_codigo == '0'){
		$sSql .= " 	   Ccr_Codigo = null,";
	}else{
		$sSql .= " 	   Ccr_Codigo = '" . $this->ccr_codigo . "',";
	}
	if ($this->ccb_codigo == '0'){
		$sSql .= " 	   Ccb_Codigo = null,";
	}else{
		$sSql .= " 	   Ccb_Codigo = '" . $this->ccb_codigo . "',";
	}
	$sSql .= " empleado_fecha_modifica = getdate()";
	$sSql .= " from empleados e join empleado_area ea on e.empleado_codigo=ea.empleado_codigo ";
	$sSql .= " where empleado_area_activo=1 and e.estado_codigo=1 and area_codigo = " . $this->area_codigo;

	$rsExec = $cn->Execute($sSql);
	if (!$rsExec)
	{
		$rpta = "Error al Actualizar Datos de los Empleados.";
		$cn->RollbackTrans();
		return $rpta;
	}*/
	//fin actualizacion de empleados

	$cn->CommitTrans();
	return $rpta;
}

function MoverArea(){
	$rpta="Ok";
	$cadena="";

	//verificar que el area destino es su actual jefe
	$sSql = "select area_jefe from areas(nolock) where area_codigo = ?";

	$params = array(
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

    if ($rs && $rs->RecordCount() > 0)
	{
		if ($this->area_jefe *1 == $rs->fields[0] *1 ){
			return 'Ok';
		}
	}

	//verificar que area origen no contenga al area destino
	$cadena = $this->TreeRecursivo();
	$sSql = "select * from areas where area_codigo = ? and area_codigo in (" . $cadena . ") ";

	$params = array(
		$this->area_jefe
	);
	$rs = $cn->Execute($sSql, $params);

    if ($rs && $rs->RecordCount() > 0)
	{
		return "Error, &Aacute;rea Origen no debe estar contenido en &Aacute;rea Destino\n No se puede Mover el &Aacute;rea";
	}

	//Realizar movimiento de Area
	$sSql="SELECT v.Area_Codigo, v.Area_Nivel_Org, ISNULL";
	$sSql .= " ((SELECT MAX(area_orden) FROM areas a";
	$sSql .= "     WHERE a.area_jefe = v.area_codigo), 0) AS maxOrden";
	$sSql .= " FROM Areas v";
	$sSql .= " Where v.area_codigo = ?";

	$params = array(
		$this->area_jefe
	);
	$rs = $cn->Execute($sSql, $params);

	//echo $sSql;
	$sSql = "UPDATE Areas";
	$sSql .= " SET Area_Jefe = ?";
	$sSql .= ", Area_Nivel_Org = ?";
	$sSql .= ", Area_Orden = ?";
	$sSql .= " Where Area_Codigo = ?";

	$params = array(
		$this->area_jefe,
		$rs->fields[1] + 1,
		$rs->fields[2] + 1,
		$this->area_codigo
	);
	$rs = $cn->Execute($sSql, $params);

	$sSql = "spRRHH_Areas_Padres";
	$rsExec = $cn->Execute($sSql);
	return $rpta;
}

function Areas_Dependientes(){
	$rpta="OK";
	$cadena="";
	$subcadena="";
	$rpta="Ok";

	$sSql="SELECT Area_Codigo FROM Areas(nolock) " .
		" WHERE empleado_responsable = ?" .
		"	AND Area_Activo =1" .
		" Order by area_codigo";

	$params = array(
		$this->empleado_responsable
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

    if ($rs && $rs->RecordCount() > 0)
	{
		while (!$rs->EOF)
		{
			$this->area_codigo=$rs->fields[0];
			$subcadena=$this->TreeRecursivo();
			if ($cadena=='') $cadena=$subcadena;
			else $cadena.=','.$subcadena;

			$rs->MoveNext();
		}
	}

	return $cadena;
}

function TreeRecursivo(){
    $rpta="OK";
	$cadena="";
	$subcadena="";
	$rpta="Ok";

	$cadena = $this->area_codigo;
	$subcadena = $cadena;
	$sw=1;
	while($sw=1){
		$sSql ="SELECT area_codigo from areas(nolock) ";
		$sSql .=" where  area_jefe in (" . $subcadena . ") and area_activo=1";

		$cn = $this->getMyConexionADO();
		$rs = $cn->Execute($sSql);

		$subcadena="";
		if ($rs && $rs->RecordCount() > 0)
		{
			while (!$rs->EOF)
			{
				if($subcadena=="") $subcadena=$rs->fields[0];
				else $subcadena .="," . $rs->fields[0];

				$rs->MoveNext();
			}
		}
		else
		{
			$sw=0;
			if($sw==0) break;
		}
		if($cadena=="") $cadena=$subcadena;
		else  $cadena .= "," . $subcadena;
  	}
 return $cadena;
}
function TreeSoloHijos(){
	$rpta="OK";
	$cadena="";
	$subcadena="";
	$rpta="Ok";
    $cn = $this->getMyConexionADO();
    
	$cadena = $this->area_codigo;
	
	//echo $cadena;
	$subcadena = $cadena;
	$sw=1;
	while($sw=1)
	{
		$sSql ="SELECT area_codigo from areas(nolock) ";
		$sSql .=" where  area_jefe in (" . $subcadena . ") and area_activo=1";

		
		$rs = $cn->Execute($sSql);

		$subcadena="";
		if ($rs && $rs->RecordCount() > 0)
		{

			while (!$rs->EOF)
			{
				if($subcadena=="") $subcadena=$rs->fields[0];
				else $subcadena .="," . $rs->fields[0];

				$rs->MoveNext();
			}

		}
		else
		{
			$sw=0;
			if($sw==0) break;
		}
		if($cadena=="") $cadena=$subcadena;
		else  $cadena .= "," . $subcadena;
  	}
  	
    /*  //echo $cadena;
  	if ($this->area_codigo!=$cadena){
  		$cadena=str_replace($this->area_codigo.',','',$cadena);
  	}else{
  		$cadena='';
  	}*/
    if ($this->area_codigo==$cadena){
		$cadena='';
  	}
    return $cadena;
}
/*
function TreeSoloHijos(){
	$cadena="";
	$subcadena="";
	$rpta="";
	$linkatree = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
    msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");

	$cadena = $this->area_codigo;
	$subcadena ="";
	$sw=1;
	while($sw=1){
		$sSql ="SELECT area_codigo from areas ";
		if ($subcadena!=''){
			$sSql .=" where  area_jefe in (" . $subcadena . ") and area_activo=1";
		}else{
			$sSql .=" where  area_jefe in (" . $cadena . ") and area_activo=1";
		}
		//echo $sSql;
		$res = msSql_query($sSql);
		$subcadena="";
		if (msSql_num_rows($res)>0){

			while ($rs= msSql_fetch_row($res)){
			 if($subcadena=="") $subcadena=$rs->fields[0];
			 else $subcadena .="," . $rs->fields[0];
			}

		}else{
			$sw=0;
			if($sw==0) break;
		}
		$rpta=$subcadena;
  	}
 return $rpta;
}
*/

function AreasHijos(){
	$rpta="OK";
	$cadena="";
	$subcadena="";
	$rpta="Ok";

	$cadena = $this->area_codigo;
	$subcadena = $cadena;

	$sSql ="SELECT area_codigo, area_descripcion  from areas(nolock) ";
	$sSql .=" where  area_jefe = (" . $subcadena . ") and area_activo=1";
	$sSql .=" order by 2";

	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql);

	$subcadena="";
	if ($rs && $rs->RecordCount() > 0)
	{
		while (!$rs->EOF)
		{
			if($subcadena=="") $subcadena=$rs->fields[0];
			else $subcadena .="," . $rs->fields[0];

			$rs->MoveNext();
		}
	}
	if($subcadena!="") $cadena .= "," . $subcadena;

 return $cadena;
}

function DireccionHijos(){
	$rpta="OK";
	$cadena="";
	$subcadena="";
	$rpta="Ok";

	$cadena = '';

	$sSql ="SELECT area_codigo, area_descripcion  from areas(nolock) ";
	$sSql .=" where  area_jefe = (?) and area_activo=1";
	$sSql .=" order by 2";

	$params = array(
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

	$subcadena="";
	if ($rs && $rs->RecordCount() > 0)
	{

		while (!$rs->EOF)
		{
			if($subcadena=="") $subcadena=$rs->fields[0];
			else $subcadena .="," . $rs->fields[0];

			$rs->MoveNext();
		}
	}
	if($subcadena!="") $cadena = $subcadena;

 return $cadena;
}

function Delete(){
	$rpta="Ok";
	//verificar que no haya personal activo en area a desactivar
	$sSql = "Select empleado_codigo from vw_area_empleados(nolock) where estado_codigo=1 and ";
	$sSql .= " empleado_area_activo = 1 and area_codigo = ?";
    //echo "mr- ".$this->area_codigo."- mr -".$sSql;
	$params = array($this->area_codigo);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

    if ($rs && $rs->RecordCount() == 0)
	{
		$sSql = "UPDATE Areas ";
    	$sSql .= " SET Area_Activo = 0 ";
		$sSql .= " Where Area_Codigo = ?";
		$params = array( $this->area_codigo );
		$rs = $cn->Execute($sSql, $params);

		if (!$rs)
		{
			$rpta = "Error al Desactivar Area.";
			return $rpta;
		}

		$sSql = "spRRHH_Areas_Padres";
		$rs = $cn->Execute($sSql);
	}
	else
	{
		$rpta = "Error10";
		return $rpta;
	}
	return $rpta;
}

function Area_Empleado($empleado){
	$rpta="Ok";

	$sSql="SELECT Empleado_Area.Area_Codigo, Areas.Area_Descripcion ";
	$sSql.=" FROM Empleado_Area(nolock) INNER join ";
	$sSql.="     Areas(nolock) ON Empleado_Area.Area_Codigo = Areas.Area_Codigo ";
	$sSql.=" WHERE Empleado_Area.Empleado_Codigo = ? AND (Empleado_Area.Empleado_Area_Activo = 1)";

	$params = array(
		$empleado
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

	if ($rs && $rs->RecordCount() > 0)
	{
		$this->area_codigo=$rs->fields[0];
		$this->area_descripcion=$rs->fields[1];
	}
	return $rpta;
}

function Area_responsable(){
	$rpta="Ok";

	$sSql="SELECT Area_Codigo, Area_Descripcion ";
	$sSql.=" FROM Areas(nolock) ";
	$sSql.=" WHERE Empleado_Responsable = ? AND (Area_Activo = 1)";

	$params = array(
		$this->empleado_responsable
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

	if ($rs && $rs->RecordCount() > 0)
	{
		$this->area_codigo=$rs->fields[0];
		$this->area_descripcion=$rs->fields[1];
	}else{
		$this->area_codigo='';
		$this->area_descripcion='';
	}
	return $rpta;
}

function Listar_Centro_Beneficio_Area()
{
  $rpta="Ok";

  $sSql = "SELECT Ccb_Codigo, Ccb_Descripcion, Ccto_Codigo ";
  $sSql .= " FROM Centro_Beneficio(nolock)  ";
  $sSql .= " WHERE Centro_Beneficio.Ccb_activo=1 and ccto_codigo is not null ";
  $sSql .= " ORDER BY Centro_Beneficio.Ccb_Descripcion, Centro_Beneficio.Ccto_Codigo ";

  $params = array(
		$this->ier_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

    if (!$rs || $rs->RecordCount() == 0)
	{
		echo "<center><b>No existen Centros de Beneficio para esta Area</b><center>" ;
	}
	else
	{
		echo "\n<table class=table cellspacing='1' cellpadding='0' border='0' align=center style='width:95%'>";
		echo "\n<tr Class=Cabecera align=center>";
		echo "\n  <td>C�digo</td>";
		echo "\n  <td>Centro de Beneficio</td>";
		echo "\n  <td>Centro de Costo</td>";
		echo "\n</tr>";
		do{
			echo "\n<tr class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#ffebd7'>";
			echo "\n   <td class=DataTD><font color=red style='CURSOR: hand' onclick=\"guardar('". $rs->fields[0] . "','" . $rs->fields[1] . "','" . $rs->fields[2] . "')\"><b>" . $rs->fields[0] . "</b></font></td> ";
			echo "\n   <td class=DataTD>" . $rs->fields[1] . "</td>";
			echo "\n   <td class=DataTD>" . $rs->fields[2] . "</td>";
			echo "\n</tr>";

			$rs->MoveNext();
		}while (!$rs->EOF);
		echo "</table><br>";
	}
	return $rpta;
}

function Listar_Mando_y_Supervisores($filtro){
  $rpta="Ok";

  $sSql = "SELECT  CA_Empleado_Rol.Empleado_codigo, " .
	"     Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS empleado " .
	" FROM  CA_Empleado_Rol INNER JOIN  " .
	"       Empleado_Area ON CA_Empleado_Rol.Empleado_codigo = Empleado_Area.Empleado_Codigo INNER JOIN " .
	"       Empleados ON Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo  " .
	" WHERE (Empleado_Area.Empleado_Area_Activo = 1)  " .
	" 	AND (CA_Empleado_Rol.Rol_Codigo = 1)  " .
	" 	and area_codigo = ?" .
	" 	and empleados.estado_codigo=1 AND CA_Empleado_Rol.EMPLEADO_ROL_ACTIVO=1 " .
	" 	and (Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres like'%$filtro%')" .
	" union  " .
	" SELECT Empleados.Empleado_Codigo, " .
	"        Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS responsable " .
	" FROM Areas INNER JOIN " .
	"      Empleados ON Areas.empleado_responsable = Empleados.Empleado_Codigo " .
	" WHERE Areas.Area_Codigo = ?" .
	" 		AND Empleados.Estado_Codigo =1 " .
	" 		and (Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres like'%$filtro%')" .
	" order by 2";

  $params = array(
		$this->area_codigo,
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

	if (!$rs || $rs->RecordCount() == 0)
	{
		echo "<center><b>No existen Centros de Beneficio para esta Area</b><center>" ;
	}
	else
	{
		echo "\n<table class=table cellspacing='1' cellpadding='0' border='0' align=center style='width:95%'>";
		echo "\n<tr Class=Cabecera align=center>";
		echo "\n  <td>C&oacute;digo</td>";
		echo "\n  <td>Jefe/Supervisor</td>";
		echo "\n</tr>";

		do
		{
			echo "\n<tr class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#ffebd7'>";
			echo "\n   <td class=DataTD><font color=red style='CURSOR: hand' onclick=\"guardar('". $rs->fields[0] . "','" . $rs->fields[1] . "')\"><b>" . $rs->fields[0] . "</b></font></td> ";
			echo "\n   <td class=DataTD>" . $rs->fields[1] . "</td>";
			echo "\n</tr>";

			$rs->MoveNext();
		}while (!$rs->EOF);
		echo "</table><br>";
	}
	return $rpta;
}



function Numero_empleados(){
	$rpta=0;
	$cods="";

	$cods=$this->TreeRecursivo();

	$sSql="SELECT  COUNT(Empleado_Codigo) AS t ";
	$sSql.=" FROM vDatos(nolock) ";
	$sSql.=" WHERE Area_Codigo in (" . $cods . ")";

	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql);

    if ($rs && $rs->RecordCount() > 0)
	{
		$rpta=$rs->fields[0];
	}
	return $rpta;
}

function Numero_empleados_cargos_area(){
	$rpta=0;
    $i=0;

	$sSql="SELECT  cargo_descripcion, COUNT(Empleado_Codigo) AS t, cargo_codigo ";
	$sSql.=" FROM vDatos(nolock) ";
	$sSql.=" WHERE Area_Codigo = ?";
	$sSql.=" GROUP BY cargo_descripcion, cargo_codigo ";
	$sSql.=" ORDER BY cargo_descripcion ";

	$params = array(
		$this->area_codigo
	);
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute($sSql, $params);

    if ($rs && $rs->RecordCount() > 0){

  	echo "\n<table width=80% align=center class=TABLE border=0 cellspacing='1' cellpadding='1'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=85%>Cargos del &aacute;rea</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$i+=0;
	while (!$rs->EOF)
	{
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
	    echo "\n  <td class=$estilo align=left width=85%>" . $rs->fields[0] . "</td> ";
	    //echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"personal_cargo_area('$this->area_codigo','" . $rs->fields[2] . "')\"  title='ver detalle'><u>" . number_format($rs->fields[1]) . "</u></font></td> ";
        echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"personal_cargo_area('".$this->area_codigo."','" . $rs->fields[2] . "')\"  title='ver detalle'><u>" . number_format($rs->fields[1]) . "</u></font></td> ";
	    echo "\n</tr>";

		$rs->MoveNext();
	}
	echo "\n<table> ";
  }else{
  	echo "<br><center><b>No hay registros</b></center>";
  }
  return $rpta;
}

function Numero_empleados_cargos_total()
{
	$rpta=0;
    $i=0;
	$supervisor_cargo='';
	$supervisor_total='';
	$asesor_cargo='';
	$asesor_total='';

	$cods="";
	$cods=$this->TreeSoloHijos();

	$sSql="SELECT  cargo_descripcion, COUNT(Empleado_Codigo) AS t, cargo_codigo ";
	$sSql.=" FROM vDatos(nolock) ";
	$sSql.=" WHERE Area_Codigo in (" . $cods . ")";
	$sSql.=" GROUP BY cargo_descripcion, cargo_codigo ";
	$sSql.=" ORDER BY cargo_descripcion ";

	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	$rs = $cn->Execute($sSql);

    if ($rs && $rs->RecordCount() > 0)
	{
		echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
		echo "\n<tr class=texto_fondo_gris> ";
		echo "\n  <td class=encabezado align=center width=85%>Cargo Totales Dependientes</td> ";
		echo "\n  <td class=encabezado align=center>Empleados</td> ";
		echo "\n</tr>";

		$i+=0;
		while (!$rs->EOF)
		{
			$i+=1;
			if ($i % 2==0)
			{
				$estilo='texto_fondo_gris';
			}
			else
			{
				$estilo='texto_fondo_blanco';
			}

			echo "\n<tr> ";
			echo "\n  <td class=" . $estilo . " align=left width=85% title='" . $rs->fields[2] . "'>" . $rs->fields[0] . "</td> ";
			echo "\n  <td class=" . $estilo . " align=right><font style='cursor:hand' onclick=\"personal_cargo_dependientes('" . $cods . "','" . $rs->fields[2] . "')\" title='ver detalle'><u>" . number_format($rs->fields[1]). "</u></font></td> ";
			echo "\n</tr>";

			if ($this->supervisor_id==$rs->fields[2])
			{
				$supervisor_cargo=$rs->fields[0];
				$supervisor_total=$rs->fields[1];
			}
			if ($this->cargo_id==$rs->fields[2])
			{
				$asesor_cargo=$rs->fields[0];
				$asesor_total=$rs->fields[1];
			}
			$rs->MoveNext();
		}

		echo "\n</table> ";
		echo "\n<br> ";

		/*if ($asesor_total>0 and $supervisor_total>0){
		echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
		echo "\n<tr><td class=encabezado> ";
		echo "\nRatio ( $asesor_cargo / $supervisor_cargo )&nbsp;:&nbsp;&nbsp;" . number_format(($asesor_total/$supervisor_total),2) . " </center> ";
		echo "\n</td></tr>";
		echo "\n</table>";
		}*/

		//--ratio de asesores vs supervisores
		$num_asesores=0;
		$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
		$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
		" vdatos.cargo_codigo= gc.cargo_codigo ";
		$sSql.=" WHERE Area_Codigo in (" . $cods . ") " .
		"	and gc.grupo_cargo_activo=1 " .
		"	and gc.grupo_o_codigo in (5)"; // -- supervisor

		$rssup = $cn->Execute($sSql);
		if ($rssup && $rssup->RecordCount() > 0)
		{
			if ($rssup->fields[0] > 0)
			{
				//--obtenemos total de asesores y pj
				$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
				$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
				" vdatos.cargo_codigo= gc.cargo_codigo ";
				$sSql.=" WHERE Area_Codigo in (" . $cods . ") " .
				"	and gc.grupo_cargo_activo=1 " .
				"	and gc.grupo_o_codigo in (2,15)"; // -- asesores y pj

				$rsoper=$cn->Execute($sSql);
				if ($rsoper && $rsoper->RecordCount() > 0)
				{
					//$rsoper=msSql_fetch_row($resu_oper);
					$num_asesores=$rsoper->fields[0];
					echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
					echo "\n<tr><td class=encabezado> ";
					echo "\nRatio ( Asesores y PJ[" . $rsoper->fields[0] . "] / Supervisores [" . $rssup->fields[0] . "])&nbsp;:&nbsp;&nbsp;" . number_format(($rsoper->fields[0]/$rssup->fields[0]),2) . " </center> ";
					echo "\n</td></tr>";
					echo "\n</table>";

					$rsoper->MoveNext();
				}
			}
		}

		//--ratio de (asesores + pj) Vs (Analistas + Administrativos)
		//--obtenemos total de asesores y pj

		if ($num_asesores>0)
		{
			$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
			$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
			" vdatos.cargo_codigo= gc.cargo_codigo ";
			$sSql.=" WHERE Area_Codigo in (" . $cods . ") " .
			"	and gc.grupo_cargo_activo=1 " .
			"	and gc.grupo_o_codigo in (1,21)"; // -- analistas +administrativos

			$rsadm=$cn->Execute($sSql);
			if ($rsadm && $rsadm->RecordCount() > 0)
			{
				//$rsadm=msSql_fetch_row($resu_adm);
				if ($rsadm->fields[0]>0)
				{
					//$rsoper=msSql_fetch_row($resu_oper);
					echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
					echo "\n<tr><td class=encabezado> ";
					echo "\nRatio ( Asesores y PJ[" . $num_asesores . "] / Analistas y Administrativos [" . $rsadm->fields[0] . "])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$rsadm->fields[0]),2) . " </center> ";
					echo "\n</td></tr>";
					echo "\n</table>";
					$rsoper->MoveNext();
				}
				$rsadm->MoveNext();
			}
		}

		//--ratio de (asesores) Vs (Los dem�s)

		if ($num_asesores>0)
		{
			$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
			$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
			" vdatos.cargo_codigo= gc.cargo_codigo ";
			$sSql.=" WHERE Area_Codigo in (" . $cods . ") " .
			"	and gc.grupo_cargo_activo=1 " .
			"	and gc.grupo_o_codigo not in (2,15)"; // -- todos menos asesores y pj

			$rsadm=$cn->Execute($sSql);
			if ($rsadm && $rsadm->RecordCount() > 0)
			{
				if ($rsadm->fields[0]>0)
				{
					//$rsoper=msSql_fetch_row($resu_oper);
					echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
					echo "\n<tr><td class=encabezado> ";
					echo "\nRatio ( Asesores y PJ[" . $num_asesores . "] / Los dem&aacute;s [" . $rsadm->fields[0] . "])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$rsadm->fields[0]),2) . " </center> ";
					echo "\n</td></tr>";
					echo "\n</table>";
					$rsoper->MoveNext();
				}
				$rsadm->MoveNext();
			}
		}

	}
	return $rpta;
}
//(*)
function seleccioneArea($filtro){
   //$filtro=JEFATURA DESARROLLO DE  SISTEMAS CORPORATIVOS
   $sms="";
   $cn = $this->getMyConexionADO();
   $ssql="select Area_Codigo, Area_Descripcion, Tipo_Area_Codigo";
   $ssql.=" from Areas where Area_Activo=1 and Area_Descripcion like '%".$filtro."%'";
   $rs=$cn->Execute($ssql);
   if($rs->RecordCount() > 0){
      $sms ='<table class="table" cellspacing="1" cellpadding="0" border="0" align="center" style="width:450px">';
      $sms.='<tr class="cabecera" align="center">';
      $sms.='<td>Codigo</td>';
      $sms.='<td>Areas</td>';
      $sms.='</tr>';
      while(!$rs->EOF){
         $sms.='<tr onMouseout=this.style.backgroundColor="" onMouseover=this.style.backgroundColor="#ffebd7">';
         $sms.='<td class="texto"><font style="CURSOR: hand" onclick="guardar('.$rs->fields[0].',\''.$rs->fields[1].'\',\''.$rs->fields[2].'\')">'. $rs->fields[0].'</font></td>';
         $sms.='<td class="texto">'. $rs->fields[1].'</td>';
         $sms.='</tr>';
         $rs->MoveNext();
      }
   }
   $sms.="</table>";
   return $sms;
}
//(*)
function seleccionSolicitante($area,$filtro){
   //$area=178 $filtro=DE DESARROLLO USUARIO
   $sms="";
   $cn=$this->getMyConexionADO();
   $ssql = "select distinct Empleados.Empleado_Codigo, ";
   $ssql .= " Empleado_Apellido_Paterno + ' ' + Empleado_Apellido_Materno + ' ' + Empleado_Nombres AS Empleado, ";
   $ssql .= " Empleado_Area.Area_Codigo ";
   $ssql .= " from Empleados INNER JOIN Empleado_Area ON ";
   $ssql .= " Empleados.Empleado_Codigo = Empleado_Area.Empleado_Codigo ";
   $ssql .= " where ";
   if ($area!='0'){
      $ssql .= " Empleado_Area.Area_Codigo = '" . $area . "' and ";
   }
   $ssql .= " Empleados.estado_codigo = 1 and Empleado_Area.Empleado_Area_Activo = 1 and ";
   $ssql .= " Empleado_Apellido_Paterno + ' ' + Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres LIKE '%" . trim($filtro) . "%'";

   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
      $sms='<table class="table" cellspacing="1" cellpadding="0" border="0" align="center" style="width:450px">';
      $sms.='<tr class="cabecera" align="center">';
      $sms.='<td>C&oacute;digo</td>';
      $sms.='<td>Empleado</td>';
      $sms.='</tr>';
      while(!$rs->EOF){
         $sms.='<tr onMouseout=this.style.backgroundColor="" onMouseover=this.style.backgroundColor="#ffebd7">';
         $sms.='<td class="texto"><font style="CURSOR: hand" onclick="guardar('.$rs->fields[0].',\''.$rs->fields[1].'\')">'.$rs->fields[0].'</font></td>';
         $sms.='<td class="texto">'.$rs->fields[1].'</td>';
         $rs->MoveNext();
      }
      $sms.='</table>';
   }
   return $sms;
}

//(*)
function seleccioneAsignar($area,$filtro){
   //$area = 178 $filtro=DE DESARROLLO USUARIO
   $sms="";
   $cn=$this->getMyConexionADO();
   $ssql = "SELECT vDatos.Empleado_Codigo, vDatos.empleado";
	$ssql.= " FROM Grupos_Cargos";
	$ssql.= " INNER JOIN Grupos_Ocupacionales ON Grupos_Cargos.Grupo_O_Codigo = Grupos_Ocupacionales.Grupo_O_Codigo";
	$ssql.= " INNER JOIN Items ON Grupos_Cargos.Cargo_Codigo = Items.Item_Codigo";
	$ssql.= " INNER JOIN vDatos ON Grupos_Cargos.Cargo_Codigo = vDatos.cargo_codigo";
	$ssql.= " WHERE (Grupos_Cargos.Grupo_Cargo_Activo = 1) AND (Grupos_Ocupacionales.Grupo_O_Activo = 1) AND";
	$ssql.= " (Grupos_Ocupacionales.Grupo_O_Codigo = 5) AND vDatos.Area_Codigo =".$area;
	$ssql.= " union";
	$ssql.= " SELECT Empleados.Empleado_Codigo,Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS empleado";
	$ssql.= " FROM Areas";
	$ssql.= " INNER JOIN Empleados ON Areas.empleado_responsable = Empleados.Empleado_Codigo";
	$ssql.= " WHERE Areas.Area_Codigo= ".$area;
	$ssql.= " and (Empleado_Apellido_Paterno + ' ' + Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres LIKE '%" . trim($filtro) . "%')";
	$ssql.= " order by 2";

   $rs=$cn->Execute($ssql);
   if($rs->RecordCount()>0){
      $sms ='<table class="table" cellspacing="1" cellpadding="0" border="0" align="center" style="width:450px">';
   	$sms.='<tr class="cabecera" align="center">';
   	$sms.='<td>C&oacute;digo</td>';
   	$sms.='<td>Empleado</td>';
   	$sms.='</tr>';
      while(!$rs->EOF){
         $sms.='<tr onMouseout=this.style.backgroundColor="" onMouseover=this.style.backgroundColor="#ffebd7">';
         $sms.='<td class="texto"><font style="CURSOR: hand" onclick="guardar('.$rs->fields[0].',\''.$rs->fields[1].'\')">'.$rs->fields[0].'</font></td>';
         $sms.='<td class="texto">'.$rs->fields[1].'</td>';
         $sms.='</tr>';
         $rs->MoveNext();
      }

      $sms.='</table>';
   }
   return $sms;
}


function Numero_empleados_cargos_total_operaciones(){ //mcamayoc
    $sRpta = 0;
    //$sRpta = "OK";
    $sSql ="";
    $cn = $this->getMyConexionADO();
    
    $supervisor_cargo='';
    $supervisor_total='';
    $asesor_cargo='';
    $asesor_total='';
    
    $cods="";
    $cods=$this->TreeSoloHijos();
    
    $sSql="SELECT  cargo_descripcion, COUNT(Empleado_Codigo) AS t, cargo_codigo ";
    $sSql.=" FROM vDatos(nolock) inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo ";
    $sSql.=" WHERE a.tipo_area_codigo=1 ";
    $sSql.=" GROUP BY cargo_descripcion, cargo_codigo ";
    $sSql.=" ORDER BY cargo_descripcion ";
    
    $rs = $cn->Execute($sSql);
    if ($rs->RecordCount()>0){
        echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
        echo "\n<tr class=texto_fondo_gris> ";
        echo "\n  <td class=encabezado align=center  ?>Cargo Totales Dependientes</td> ";
        echo "\n  <td class=encabezado align=center>Empleados</td> ";
        echo "\n</tr>";
        $i=0;
        while(!$rs->EOF){
        	$i+=1;
        	if ($i % 2==0){
        		$estilo='texto_fondo_gris';
        	}else{
        		$estilo='texto_fondo_blanco';
        	}
        	echo "\n<tr> ";
            echo "\n  <td class='" . $estilo . "' align=left width=85% title='".$rs->fields[2]."'>" . $rs->fields[0] . "</td> ";
            echo "\n  <td class='" . $estilo . "' align=right><font style='cursor:hand' onclick=\"personal_cargo_operaciones('".$rs->fields[2]."')\" title='ver detalle'><u>" . number_format($rs->fields[1]). "</u></font></td> ";
            echo "\n</tr>";
            $rs->MoveNext();
        }
        echo "\n</table> ";
        echo "\n<br> ";
        //--ratio de asesores vs supervisores
        $num_asesores=0;
        $sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
        $sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc (nolock)on " .
        	" vdatos.cargo_codigo= gc.cargo_codigo " .
        	" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
        $sSql.=" WHERE a.tipo_area_codigo=1 " .
        		"	and gc.grupo_cargo_activo=1 " .
        		"	and gc.grupo_o_codigo in (5)"; // -- supervisor
        
        $resu_sup = $cn->Execute($sSql);
        if ($resu_sup->RecordCount()>0){
        	if ($resu_sup->fields[0]>0){
        		//--obtenemos total de asesores y pj
        		$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
        		$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
        			" vdatos.cargo_codigo= gc.cargo_codigo " .
        			" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
        		$sSql.=" WHERE a.tipo_area_codigo=1 " .
        				"	and gc.grupo_cargo_activo=1 " .
        				"	and gc.grupo_o_codigo in (2,15)"; // -- asesores y pj
        		$resu_oper = $cn->Execute($sSql);
                if ($resu_oper->RecordCount()>0){
        			//$rsoper=msSql_fetch_row($resu_oper);
        			$num_asesores=$resu_oper->fields[0];
        			echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
        		    echo "\n<tr><td class=encabezado> ";
        		    //echo "\nRatio ( Asesores y PJ[$resu_oper->fields[0]] / Supervisores [$resu_sup->fields[0]])&nbsp;:&nbsp;&nbsp;" . number_format(($resu_oper->fields[0]/$resu_sup->fields[0]),2) . " </center> ";
                    echo "\nRatio ( Asesores y PJ[".$resu_oper->fields[0]."] / Supervisores [".$resu_sup->fields[0]."])&nbsp;:&nbsp;&nbsp;" . number_format(($resu_oper->fields[0]/$resu_sup->fields[0]),2) . " </center> ";
        		    echo "\n</td></tr>";
        		    echo "\n</table>";
        		}
        	}
        }
        
        //--ratio de (asesores + pj) Vs (Analistas + Administrativos)
        //--obtenemos total de asesores y pj
        if ($num_asesores>0){
        	$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
        	$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
        		" vdatos.cargo_codigo= gc.cargo_codigo " .
        		" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
        	$sSql.=" WHERE a.tipo_area_codigo=1 " .
        			"	and gc.grupo_cargo_activo=1 " .
        			"	and gc.grupo_o_codigo in (1,21)"; // -- analistas +administrativos
        	
            $resu_adm = $cn->Execute($sSql);
            if ($resu_adm->RecordCount()>0){
        		//$rsadm=msSql_fetch_row($resu_adm);
        		if ($resu_adm->fields[0]>0){
        			//$rsoper=msSql_fetch_row($resu_oper);
        			echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
        		    echo "\n<tr><td class=encabezado> ";
        		    //echo "\nRatio ( Asesores y PJ[$num_asesores] / Analistas y Administrativos [$resu_adm->fields[0]])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$resu_adm->fields[0]),2) . " </center> ";
                    echo "\nRatio ( Asesores y PJ[$num_asesores] / Analistas y Administrativos [".$resu_adm->fields[0]."])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$resu_adm->fields[0]),2) . " </center> ";
        		    echo "\n</td></tr>";
        		    echo "\n</table>";
        		}
        	}
        }
        
        //--ratio de (asesores) Vs (Los demas)
        if ($num_asesores>0){
        
        	$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
        	$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
        		" vdatos.cargo_codigo= gc.cargo_codigo " .
        		" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
        	$sSql.=" WHERE a.tipo_area_codigo=1 " .
        			"	and gc.grupo_cargo_activo=1 " .
        			"	and gc.grupo_o_codigo not in (2,15)"; // -- todos menos asesores y pj
        	$resu_adm = $cn->Execute($sSql);
            if ($resu_adm->RecordCount()>0){
        		if ($resu_adm->fields[0]>0){
        			//$rsoper=msSql_fetch_row($resu_oper);
        			echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
        		    echo "\n<tr><td class=encabezado> ";
        		    //echo "\nRatio ( Asesores y PJ[$num_asesores] / Los demas [$resu_adm->fields[0]])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$resu_adm->fields[0]),2) . " </center> ";
                    echo "\nRatio ( Asesores y PJ[$num_asesores] / Los demas [".$resu_adm->fields[0]."])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$resu_adm->fields[0]),2) . " </center> ";
        		    echo "\n</td></tr>";
        		    echo "\n</table>";
        		}
        	}
        }
    
    }
    return $sRpta;  
  /******************************
  $rpta=0;
  $linklcaexx = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
  msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");
  $supervisor_cargo='';
  $supervisor_total='';
  $asesor_cargo='';
  $asesor_total='';

  $cods="";
  $cods=$this->TreeSoloHijos();

  $sSql="SELECT  cargo_descripcion, COUNT(Empleado_Codigo) AS t, cargo_codigo ";
  $sSql.=" FROM vDatos(nolock) inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo ";
  $sSql.=" WHERE a.tipo_area_codigo=1 ";
  $sSql.=" GROUP BY cargo_descripcion, cargo_codigo ";
  $sSql.=" ORDER BY cargo_descripcion ";

  //echo $sSql;
  $res=msSql_query($sSql);
  if (msSql_num_rows($res)>0){

  	echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=85 ?>Cargo Totales Dependientes</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$i=0;
	while ($rs=msSql_fetch_row($res)){
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
	    echo "\n  <td class='" . $estilo . "' align=left width=85% title='$rs->fields[2]'>" . $rs->fields[0] . "</td> ";
	    echo "\n  <td class='" . $estilo . "' align=right><font style='cursor:hand' onclick=\"personal_cargo_operaciones('$rs->fields[2]')\" title='ver detalle'><u>" . number_format($rs->fields[1]). "</u></font></td> ";
	    echo "\n</tr>";

	}
	echo "\n</table> ";
	echo "\n<br> ";
	//--ratio de asesores vs supervisores
	$num_asesores=0;
	$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
	$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc (nolock)on " .
		" vdatos.cargo_codigo= gc.cargo_codigo " .
		" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
	$sSql.=" WHERE a.tipo_area_codigo=1 " .
			"	and gc.grupo_cargo_activo=1 " .
			"	and gc.grupo_o_codigo in (5)"; // -- supervisor
	$resu_sup=msSql_query($sSql);
	if (msSql_num_rows($resu_sup)>0){
		$rssup=msSql_fetch_row($resu_sup);
		if ($rssup->fields[0]>0){
			//--obtenemos total de asesores y pj
			$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
			$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
				" vdatos.cargo_codigo= gc.cargo_codigo " .
				" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
			$sSql.=" WHERE a.tipo_area_codigo=1 " .
					"	and gc.grupo_cargo_activo=1 " .
					"	and gc.grupo_o_codigo in (2,15)"; // -- asesores y pj
			$resu_oper=msSql_query($sSql);
			if (msSql_num_rows($resu_oper)>0){
				$rsoper=msSql_fetch_row($resu_oper);
				$num_asesores=$rsoper->fields[0];
				echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
			    echo "\n<tr><td class=encabezado> ";
			    echo "\nRatio ( Asesores y PJ[$rsoper->fields[0]] / Supervisores [$rssup->fields[0]])&nbsp;:&nbsp;&nbsp;" . number_format(($rsoper->fields[0]/$rssup->fields[0]),2) . " </center> ";
			    echo "\n</td></tr>";
			    echo "\n</table>";
			}
		}
	}

	//--ratio de (asesores + pj) Vs (Analistas + Administrativos)
	//--obtenemos total de asesores y pj

	if ($num_asesores>0){

		$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
		$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
			" vdatos.cargo_codigo= gc.cargo_codigo " .
			" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
		$sSql.=" WHERE a.tipo_area_codigo=1 " .
				"	and gc.grupo_cargo_activo=1 " .
				"	and gc.grupo_o_codigo in (1,21)"; // -- analistas +administrativos
		$resu_adm=msSql_query($sSql);
		if (msSql_num_rows($resu_adm)>0){
			$rsadm=msSql_fetch_row($resu_adm);
			if ($rsadm->fields[0]>0){
				$rsoper=msSql_fetch_row($resu_oper);
				echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
			    echo "\n<tr><td class=encabezado> ";
			    echo "\nRatio ( Asesores y PJ[$num_asesores] / Analistas y Administrativos [$rsadm->fields[0]])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$rsadm->fields[0]),2) . " </center> ";
			    echo "\n</td></tr>";
			    echo "\n</table>";
			}
		}
	}

	//--ratio de (asesores) Vs (Los dem�s)

	if ($num_asesores>0){

		$sSql="SELECT COUNT(vdatos.Empleado_Codigo) AS t ";
		$sSql.=" FROM vDatos(nolock) inner join grupos_cargos gc on " .
			" vdatos.cargo_codigo= gc.cargo_codigo " .
			" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
		$sSql.=" WHERE a.tipo_area_codigo=1 " .
				"	and gc.grupo_cargo_activo=1 " .
				"	and gc.grupo_o_codigo not in (2,15)"; // -- todos menos asesores y pj
		$resu_adm=msSql_query($sSql);
		if (msSql_num_rows($resu_adm)>0){
			$rsadm=msSql_fetch_row($resu_adm);
			if ($rsadm->fields[0]>0){
				$rsoper=msSql_fetch_row($resu_oper);
				echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
			    echo "\n<tr><td class=encabezado> ";
			    echo "\nRatio ( Asesores y PJ[$num_asesores] / Los dem�s [$rsadm->fields[0]])&nbsp;:&nbsp;&nbsp;" . number_format(($num_asesores/$rsadm->fields[0]),2) . " </center> ";
			    echo "\n</td></tr>";
			    echo "\n</table>";
			}
		}
	}

  }
  msSql_close($linklcaexx);
  return $rpta;*/
}


function Grupos_supervision_mando_area(){ //mcamayoc
    $rpta=0;
    $i=0;
    $sSql ="";
    $cn = $this->getMyConexionADO();
    
    $params = array($this->area_codigo,$this->area_codigo);
    $sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
    $sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
    $sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
    $sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
    $sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
    $sSql.=" 		AND Area_Codigo =? ";
    $sSql.=" 		AND (empleados.Empleado_Codigo in (select empleado_codigo from vdatos where Area_Codigo = ? and empleado_responsable_area=1)) ";
    $sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
    $sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
    $sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";
    
    $rs = $cn->Execute($sSql, $params);
    if ($rs->RecordCount()>0){    
        echo "\n<table width=100% align=center class=table border=1 cellspacing='0' cellpadding='0'> ";
        echo "\n<tr class=texto_fondo_gris> ";
        echo "\n  <td class=encabezado align=center width=40%>Grupos de Supervisi&oacute;n del Mando del &aacute;rea</td> ";
        echo "\n  <td class=encabezado align=center width=50%>Cargo</td> ";
        echo "\n  <td class=encabezado align=center>Empleados</td> ";
        echo "\n</tr>";
        $respId='';
        $i+=0;
        while(!$rs->EOF){
        //while ($rs=msSql_fetch_row($res)){
        	$i+=1;
        	if ($i % 2==0){
        		$estilo='texto_fondo_gris';
        	}else{
        		$estilo='texto_fondo_blanco';
        	}
        	echo "\n<tr> ";
        	if ($respId!=$rs->fields[0]){
        		//echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
                echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('".$rs->fields[4]."')\" title='ver detalle'><u>".$rs->fields[0]."</u></font></td> ";
        		$respId=$rs->fields[0];
        	}else{
            	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
        	}
            echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
            //echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_area('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
            echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_area('".$rs->fields[4]."','".$rs->fields[3]."')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
            echo "\n</tr>";
            $rs->MoveNext();
        }
        echo "\n<table> ";
    }
    return $rpta;


/*********************************************************************************
  $rpta=0;
  $linklcaexx = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
  msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND Area_Codigo = " . $this->area_codigo;
	$sSql.=" 		AND (empleados.Empleado_Codigo in (select empleado_codigo from vdatos where Area_Codigo = " . $this->area_codigo . " and empleado_responsable_area=1)) ";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";
  $res=msSql_query($sSql);
  if (msSql_num_rows($res)>0){

  	echo "\n<table width=100% align=center class=table border=1 cellspacing='0' cellpadding='0'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=40 ?>Grupos de Supervisi�n del Mando del �rea</td> ";
    echo "\n  <td class=encabezado align=center width=50 ?>Cargo</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$respId='';
	$i+=0;
	while ($rs=msSql_fetch_row($res)){
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
		if ($respId!=$rs->fields[0]){
			echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
			$respId=$rs->fields[0];
		}else{
	    	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
		}
	    echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
	    echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_area('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
	    echo "\n</tr>";
	}
	echo "\n<table> ";
  }
  msSql_close($linklcaexx);
  return $rpta;*/
}




function Grupos_supervision_mandos_dependientes(){ // mcamayoc
    $rpta=0;
    $sSql ="";
    $i=0;
    $cn = $this->getMyConexionADO();
    
    $cods="";
  	$cods=$this->TreeSoloHijos();
    //print_r($cods);
    if(!empty($cods)){
    	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
        $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
    	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
    	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
    	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
    	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
    	$sSql.=" 		AND Area_Codigo in (" . $cods . ")";
    	$sSql.=" 		AND (empleados.Empleado_Codigo in (select empleado_codigo from vdatos where Area_Codigo in (" . $cods . ") and empleado_responsable_area=1)) ";
    	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
    	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
    	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";
    
        $rs = $cn->Execute($sSql);    
        if ($rs->RecordCount()>0){
          	echo "\n<table width=100% align=center class=table border=0 cellspacing='1' cellpadding='1'> ";
            echo "\n<tr class=texto_fondo_gris> ";
            echo "\n  <td class=encabezado align=center width=40%>Grupos de Supervisi&oacute;n de Mandos</td> ";
            echo "\n  <td class=encabezado align=center width=50%>Cargo</td> ";
            echo "\n  <td class=encabezado align=center>Empleados</td> ";
            echo "\n</tr>";
        	$respId='';
        	while(!$rs->EOF){
            //while ($rs=msSql_fetch_row($res)){
        		$i+=1;
        		if ($i % 2==0){
        			$estilo='texto_fondo_gris';
        		}else{
        			$estilo='texto_fondo_blanco';
        		}
        		echo "\n<tr> ";
        		if ($respId!=$rs->fields[0]){
        			//echo "\n  <td class=texto align=left >" . $rs->fields[0] . "</td> ";
        			//echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
                    echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('".$rs->fields[4]."')\" title='ver detalle'><u>".$rs->fields[0]."</u></font></td> ";
        			$respId=$rs->fields[0];
        		}else{
        	    	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
        		}
        	    echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
        	    //echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
                echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes('".$rs->fields[4]."','".$rs->fields[3]."')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
        	    echo "\n</tr>";
                $rs->MoveNext();
        	}
        	echo "\n<table> ";
      }
      return $rpta;
  }  
  
  
  /*
  $rpta=0;
  $linklcaexx = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
  msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");
	$cods="";
  	$cods=$this->TreeSoloHijos();

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND Area_Codigo in (" . $cods . ")";
	$sSql.=" 		AND (empleados.Empleado_Codigo in (select empleado_codigo from vdatos where Area_Codigo in (" . $cods . ") and empleado_responsable_area=1)) ";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";

  //echo $sSql;
  $res=msSql_query($sSql);
  if (msSql_num_rows($res)>0){

  	echo "\n<table width=100% align=center class=table border=0 cellspacing='1' cellpadding='1'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=40 ?>Grupos de Supervisi�n de Mandos</td> ";
    echo "\n  <td class=encabezado align=center width=50 ?>Cargo</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$respId='';
	while ($rs=msSql_fetch_row($res)){
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
		if ($respId!=$rs->fields[0]){
			//echo "\n  <td class=texto align=left >" . $rs->fields[0] . "</td> ";
			echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
			$respId=$rs->fields[0];
		}else{
	    	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
		}
	    echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
	    echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
	    echo "\n</tr>";
	}
	echo "\n<table> ";
  }
  msSql_close($linklcaexx);
  return $rpta;*/
}

function Grupos_supervision_area(){ //mcamayoc
    $rpta=0;
    $sSql ="";
    $i=0;
    $cn = $this->getMyConexionADO();
    
    $params = array($this->area_codigo,$this->area_codigo);

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND Area_Codigo =? ";
	$sSql.=" 		AND (empleados.Empleado_Codigo not in (select empleado_codigo from vdatos where Area_Codigo = ?  and empleado_responsable_area=1))";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";

    $rs = $cn->Execute($sSql, $params);    
    if ($rs->RecordCount()>0){
        echo "\n<table width=100% align=center class=table border=0 cellspacing='1' cellpadding='1'> ";
        echo "\n<tr class=texto_fondo_gris> ";
        echo "\n  <td class=encabezado align=center width=40%>Grupos de Supervisi&oacute;n del &aacute;rea</td> ";
        echo "\n  <td class=encabezado align=center width=50%>Cargos</td> ";
        echo "\n  <td class=encabezado align=center>Empleados</td> ";
        echo "\n</tr>";
        $respId='';
        $i+=0;
        while(!$rs->EOF){
        //while ($rs=msSql_fetch_row($res)){
        	$i+=1;
        	if ($i % 2==0){
        		$estilo='texto_fondo_gris';
        	}else{
        		$estilo='texto_fondo_blanco';
        	}
        	echo "\n<tr> ";
        	if ($respId!=$rs->fields[0]){
        		//echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
                echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('".$rs->fields[4]."')\" title='ver detalle'><u>".$rs->fields[0]."</u></font></td> ";
        		$respId=$rs->fields[0];
        	}else{
            	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
        	}
            echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
            //echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_area('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
            echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_area('".$rs->fields[4]."','".$rs->fields[3]."')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
            echo "\n</tr>";
            $rs->MoveNext();
        }
        echo "\n<table> ";
    }
    return $rpta;

  /*******************************************************************************
  $rpta=0;
  $linklcaexx = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
  msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND Area_Codigo = " . $this->area_codigo;
	$sSql.=" 		AND (empleados.Empleado_Codigo not in (select empleado_codigo from vdatos where Area_Codigo = " . $this->area_codigo . "  and empleado_responsable_area=1))";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";
  //echo $sSql;
  $res=msSql_query($sSql);
  if (msSql_num_rows($res)>0){

  	echo "\n<table width=100% align=center class=table border=0 cellspacing='1' cellpadding='1'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=40 ?>Grupos de Supervisi�n del �rea</td> ";
    echo "\n  <td class=encabezado align=center width=50 ?>Cargos</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$respId='';
	$i+=0;
	while ($rs=msSql_fetch_row($res)){
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
		if ($respId!=$rs->fields[0]){
			echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
			$respId=$rs->fields[0];
		}else{
	    	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
		}
	    echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
	    echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_area('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
	    echo "\n</tr>";
	}
	echo "\n<table> ";
  }
  msSql_close($linklcaexx);
  return $rpta;*/
}

function Grupos_supervision_total(){ //mcamayoc
    $rpta=0;
    $sSql ="";
    $i=0;
    $cn = $this->getMyConexionADO();
    $cods="";
    $cods=$this->TreeSoloHijos();
    if(!empty($cods)){
        $sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
        $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
        $sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
        $sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
        $sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
        $sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
        $sSql.=" 		AND Area_Codigo in (" . $cods . ")";
        $sSql.=" 		AND (empleados.Empleado_Codigo not in (select empleado_codigo from vdatos(nolock) where Area_Codigo in (" . $cods . ")  and empleado_responsable_area=1))";
        $sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
        $sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
        $sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";
        
        $rs = $cn->Execute($sSql);    
        if ($rs->RecordCount()>0){
            echo "\n<table width=100% align=center class=table border=0 cellspacing='1' cellpadding='1'> ";
            echo "\n<tr class=texto_fondo_gris> ";
            echo "\n  <td class=encabezado align=center width=40%>Grupos de Supervisi&oacute;n de Dependientes</td> ";
            echo "\n  <td class=encabezado align=center width=50%>Cargos</td> ";
            echo "\n  <td class=encabezado align=center>Empleados</td> ";
            echo "\n</tr>";
            $respId='';
            $i+=0;
            while(!$rs->EOF){
            //while ($rs=msSql_fetch_row($res)){
            	$i+=1;
            	if ($i % 2==0){
            		$estilo='texto_fondo_gris';
            	}else{
            		$estilo='texto_fondo_blanco';
            	}
            	echo "\n<tr> ";
            	if ($respId!=$rs->fields[0]){
            		//echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
                    echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('".$rs->fields[4]."')\" title='ver detalle'><u>".$rs->fields[0]."</u></font></td> ";
            		$respId=$rs->fields[0];
            	}else{
                	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
            	}
                echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
                //echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
                echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes('".$rs->fields[4]."','".$rs->fields[3]."')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
                echo "\n</tr>";
                $rs->MoveNext();
            }
            echo "\n<table> ";
        }
    }
    return $rpta;

    /****************************************
  $rpta=0;
  $linklcaexx = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
  msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");
	$cods="";
  	$cods=$this->TreeSoloHijos();

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo ";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND Area_Codigo in (" . $cods . ")";
	$sSql.=" 		AND (empleados.Empleado_Codigo not in (select empleado_codigo from vdatos(nolock) where Area_Codigo in (" . $cods . ")  and empleado_responsable_area=1))";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";

  //echo $sSql;
  $res=msSql_query($sSql);
  if (msSql_num_rows($res)>0){

  	echo "\n<table width=100% align=center class=table border=0 cellspacing='1' cellpadding='1'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=40 ?>Grupos de Supervisi�n de Dependientes</td> ";
    echo "\n  <td class=encabezado align=center width=50 ?>Cargos</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$respId='';
	$i+=0;
	while ($rs=msSql_fetch_row($res)){
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
		if ($respId!=$rs->fields[0]){
			//echo "\n  <td class=texto align=left >" . $rs->fields[0] . "</td> ";
			echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
			$respId=$rs->fields[0];
		}else{
	    	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
		}
	    echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
	    echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
	    echo "\n</tr>";
	}
	echo "\n<table> ";
  }
  msSql_close($linklcaexx);
  return $rpta;*/
}

function Grupos_supervision_total_operaciones(){
    $rpta=0;
    $i=0;
    $sSql ="";
    $cn = $this->getMyConexionADO();
    $cods="";
    $cods=$this->TreeSoloHijos();

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo " .
			" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND a.tipo_area_codigo=1 ";
	$sSql.=" 		AND (empleados.Empleado_Codigo not in (select empleado_codigo from vdatos(nolock) where empleado_responsable_area=1))";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";

    //echo $sSql;
    $rs = $cn->Execute($sSql);
    if ($rs->RecordCount()>0){
        echo "\n<table width=100% align=center class=table border=1 cellspacing='0' cellpadding='0'> ";
        echo "\n<tr class=texto_fondo_gris> ";
        echo "\n  <td class=encabezado align=center width=40% >Grupos de Supervisi&oacute;n de Dependientes</td> ";
        echo "\n  <td class=encabezado align=center width=50% >Cargos</td> ";
        echo "\n  <td class=encabezado align=center>Empleados</td> ";
        echo "\n</tr>";
        $respId='';
        $i+=0;
        while(!$rs->EOF){
        //while ($rs=msSql_fetch_row($res)){
        	$i+=1;
        	if ($i % 2==0){
        		$estilo='texto_fondo_gris';
        	}else{
        		$estilo='texto_fondo_blanco';
        	}
        	echo "\n<tr> ";
        	if ($respId!=$rs->fields[0]){
        		//echo "\n  <td class=texto align=left >" . $rs->fields[0] . "</td> ";
        		//echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area_operaciones('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
                echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area_operaciones('".$rs->fields[4]."')\" title='ver detalle'><u>".$rs->fields[0]."</u></font></td> ";
        		$respId=$rs->fields[0];
        	}else{
            	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
        	}
            echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
            //echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes_operaciones('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
            echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes_operaciones('".$rs->fields[4]."','".$rs->fields[3]."')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
            echo "\n</tr>";
            $rs->MoveNext();
        }
        echo "\n<table> ";
    }
    return $rpta;
  
  /*********************************************************************************
  $rpta=0;
  $linklcaexx = msSql_connect($this->MyUrl, $this->MyUser, $this->MyPwd) or die("No puedo conectarme a servidor");
  msSql_select_db($this->MyDBName) or die("No puedo seleccionar BD");
	$cods="";
  	$cods=$this->TreeSoloHijos();

	$sSql=" SELECT Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable, ";
    $sSql.="        vDatos.Cargo_descripcion, COUNT(vDatos.Empleado_Codigo) AS t, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" FROM CA_Asignacion_Empleados(nolock) INNER JOIN ";
	$sSql.="      vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN ";
	$sSql.="      Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo " .
			" inner join areas a (nolock) on vdatos.area_codigo=a.area_codigo";
	$sSql.=" WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1 ";
	$sSql.=" 		AND a.tipo_area_codigo=1 ";
	$sSql.=" 		AND (empleados.Empleado_Codigo not in (select empleado_codigo from vdatos(nolock) where empleado_responsable_area=1))";
	$sSql.=" GROUP BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres, ";
	$sSql.="          vDatos.Cargo_descripcion, vDatos.Cargo_codigo, CA_Asignacion_Empleados.Responsable_codigo ";
	$sSql.=" ORDER BY Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres ";

  //echo $sSql;
  $res=msSql_query($sSql);
  if (msSql_num_rows($res)>0){

  	echo "\n<table width=100% align=center class=table border=1 cellspacing='0' cellpadding='0'> ";
    echo "\n<tr class=texto_fondo_gris> ";
    echo "\n  <td class=encabezado align=center width=40 ?>Grupos de Supervisi�n de Dependientes</td> ";
    echo "\n  <td class=encabezado align=center width=50 ?>Cargos</td> ";
    echo "\n  <td class=encabezado align=center>Empleados</td> ";
    echo "\n</tr>";
	$respId='';
	$i+=0;
	while ($rs=msSql_fetch_row($res)){
		$i+=1;
		if ($i % 2==0){
			$estilo='texto_fondo_gris';
		}else{
			$estilo='texto_fondo_blanco';
		}
		echo "\n<tr> ";
		if ($respId!=$rs->fields[0]){
			//echo "\n  <td class=texto align=left >" . $rs->fields[0] . "</td> ";
			echo "\n  <td class=$estilo align=left ><font style='cursor:hand' onclick=\"supervision_area_operaciones('$rs->fields[4]')\" title='ver detalle'><u>$rs->fields[0]</u></font></td> ";
			$respId=$rs->fields[0];
		}else{
	    	echo "\n  <td class=$estilo align=left >&nbsp;</td> ";
		}
	    echo "\n  <td class=$estilo align=left >" . $rs->fields[1] . "</td> ";
	    echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"supervision_cargo_dependientes_operaciones('$rs->fields[4]','$rs->fields[3]')\" title='ver detalle'><u>" . number_format($rs->fields[2]). "</u></font></td> ";
	    echo "\n</tr>";
	}
	echo "\n<table> ";
  }
  msSql_close($linklcaexx);
  return $rpta;*/
}

function Area_Controller($usuario){
    $rpta="Ok";
    $cadena="";
    $cn=$this->getMyConexionADO();
    $sSql =" select areas.area_codigo , areas.area_descripcion";
    $sSql .= " from ca_controller ";
    $sSql .= " inner join areas on areas.area_codigo = ca_controller.area_codigo ";
    $sSql .= " where ca_controller.empleado_codigo=" . $usuario . " and ca_controller.activo=1";
    $rs=$cn->Execute($sSql);
    $padre = array();
    while(!$rs->EOF){
        // We fill the $value array with the data.
        $hijo = array();
        $hijo["Codigo"]= $rs->fields[0];
        $hijo["Descripcion"]= utf8_encode($rs->fields[1]);
        array_push($padre,$hijo);
        $rs->MoveNext();
    }
    return $padre;
}

function servicios_controller($area){
    $rpta="Ok";
    $cadena="";
    $cn=$this->getMyConexionADO();
    $sSql="select v_campanas_clientes.Cod_Campana,";
    $sSql .= " 		Exp_Codigo + ' - ' + v_campanas_clientes.Exp_NombreCorto + ' ' + '(' + isnull(v_campanas_clientes.Cliente_DesCorta,' ') + ')' as nombre, ";
    $sSql .= " 		v_campanas_clientes.Ebitda, v_campanas_clientes.IngresoEstimado ";
    $sSql .= " From Areas";
    $sSql .= " 		INNER JOIN v_campanas_clientes ON Areas.Area_Codigo = v_campanas_clientes.coordinacion_codigo";
    $sSql .= " where exp_activo=1 " ;
    $sSql .= " and Areas.Area_Codigo='" . $area ."'";
    //$sSql .= " Exp_Codigo + ' - ' + v_campanas_clientes.Exp_NombreCorto like '%" . $filtro . "%'";
    $sSql .= " order by 2 ";
    //$cn->debug=true;
    
    $rs=$cn->Execute($sSql);
    $padre = array();
    while (!$rs->EOF){
        // We fill the $value array with the data.
        $hijo = array();
        $hijo["Codigo"]= $rs->fields[0];
        $hijo["Descripcion"]= utf8_encode($rs->fields[1]);
        array_push($padre,$hijo);
        //array_push($value{"Descricpion"},"nombre "+$i);
        $rs->MoveNext();
    }

    return $padre;
}

function calcula_semana(){
    $rpta="Ok";
    $cn=$this->getMyConexionADO();
    $ssql=" set datefirst 1 ";
    $rs=$cn->Execute($ssql);
    $sSql="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3";
    $rs=$cn->Execute($sSql);
    $padre = array();
    while (!$rs->EOF){
        // We fill the $value array with the data.
        $hijo = array();
        $hijo["Codigo"]= $rs->fields[0];
        $hijo["Descripcion"]= $rs->fields[1];
        array_push($padre,$hijo);
        //array_push($value{"Descricpion"},"nombre "+$i);
        $rs->MoveNext();
    }
	
    return $padre;
}


//flr 18-03-2013
function Grupos_Ocupacional_area(){
    $rpta=0;
    $cn=$this->getMyConexionADO();
	$cods="";
  	$cods=$this->TreeSoloHijos();
	
	//echo $cods;
	
    if($cods==''){
        $cods=$this->area_codigo;
    }else{
        $cods.=','.$this->area_codigo;
    }

	$ssql="SELECT	g.Grupo_O_Codigo,g.Grupo_O_Descripcion,v.cargo_codigo,v.Cargo_descripcion,count(v.Empleado_Codigo)
            from	vdatos v (NOLOCK)
            inner JOIN Grupos_Cargos gc (NOLOCK) ON v.cargo_codigo=gc.Cargo_Codigo
            inner JOIN Grupos_Ocupacionales g (NOLOCK) ON gc.Grupo_O_Codigo=g.Grupo_O_Codigo
            where	gc.Grupo_Cargo_Activo=1 
            and v.area_codigo in ($cods)
            group by g.Grupo_O_Codigo,g.Grupo_O_Descripcion,v.cargo_codigo,v.Cargo_descripcion
            order by 2,4 ";

    //echo $ssql;
    $totales = 0;
    //$res=mssql_query($ssql);
    $rs=$cn->Execute($ssql);
    
    //if (mssql_num_rows($res)>0){
    if ($rs->RecordCount()>0){
        echo "\n<table width=80% align=center class=table border=0 cellspacing='1' cellpadding='0'> ";
        $i=0;
        
        $codigo_antiguo = 0;
    	while(!$rs->EOF){
    		 if($rs->fields[0]!=$codigo_antiguo){
                echo "\n<tr class=texto_fondo_gris> ";
                echo "\n  <td class=encabezado align=left width=85%>&nbsp;".$rs->fields[1]."</td> ";
                echo "\n  <td class=encabezado align=center>&nbsp;</td> ";
                echo "\n</tr>";
                $i=1;
    		 }
            if ($i % 2==0){
    			$estilo='texto_fondo_gris';
    		}else{
    			$estilo='texto_fondo_blanco';
    		}
    		echo "\n<tr> ";
    	    echo "\n  <td class=$estilo align=left width=85% title='".$rs->fields[2]."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rs->fields[3] . "</td> ";
    	    echo "\n  <td class=$estilo align=right><font style='cursor:hand' onclick=\"personal_cargo_dependientes('$cods','".$rs->fields[2]."')\" title='ver detalle'><u>" . number_format($rs->fields[4]). "</u></font></td> ";
    	    echo "\n</tr>";
    	    
            $codigo_antiguo = $rs->fields[0]*1;
            $totales=$totales+($rs->fields[4]*1);
            $i=+1;
            $rs->MoveNext();
    	}
        
        echo "\n<tr class=texto_fondo_gris> ";
        echo "\n  <td class=encabezado align=right width=85%>&nbsp;Total</td> ";
        echo "\n  <td class=encabezado align=center>".$totales."</td> ";
        echo "\n</tr>";
    	echo "\n</table> ";
    	echo "\n<br> ";
        
    }
    
    //mssql_close($linklcaexx);
    return $rpta;
}


    function NivelOrganizacional(){
        $cn=$this->getMyConexionADO();
        //--Obtener dia permitido
        $params = array($this->area_codigo );
        $sSql="SELECT Area_Nivel_Org";
        $sSql.=" FROM Areas";
        $sSql.=" WHERE Area_codigo = ? ";
        $rs=$cn->Execute($sSql, $params);
        return $rs->fields[0];
    }
    
    function Obtener_Areas_Jerarquia($area){
        $cn=$this->getMyConexionADO();
        $params = array($area);
        $sql = "select Area_Codigo
                from Areas  
                where Area_Jefe = ? and Area_Activo = 1";
        $rs = $cn->Execute($sql, $params);
        $this->areas[] = $area;
        if($rs->RecordCount() > 0){ //si hay hijos 
            while(!$rs->EOF){
                $this->Obtener_Areas_Jerarquia($rs->fields[0]);
                $rs->MoveNext();
            }
        }
        return;
    }
    
    function Obtener_Nombre_Area($area){
        $cn = $this->getMyConexionADO();
        $params = array($area);
        $sql = "select Area_Descripcion
                from Areas  
                where area_codigo = ?";
        $rs = $cn->Execute($sql, $params);
        if($rs->RecordCount() > 0){
            return $rs->fields[0];
        }else{
            return "Area no Existe";
        }
    }
    
    function Obtener_Unidades_Area($area){
        $cn = $this->getMyConexionADO();
        $areas_seleccionadas = $this->Areas_Seleccionadas();
        $params = array($area);
        $sql = "SELECT v.Cod_Campana,
                    v.Exp_NombreCorto ,
                    VP.ca_nombre as programa
                FROM Areas INNER JOIN v_campanas_clientes v ON Areas.Area_Codigo = v.coordinacion_codigo
                	LEFT OUTER JOIN DB_GESTION.dbo.v_campanas_ca_gg vgg on	v.cod_campana=vgg.cod_campana and vgg.ca_tipo='GG'
                	LEFT JOIN V_PROGRAMAS VP ON VP.Cod_Campana = v.Cod_Campana
                WHERE Areas.Area_Codigo in ($areas_seleccionadas)
                	and exp_activo=1 
                ORDER BY 2";
        $rs = $cn->Execute($sql, $params);
        return $rs; 
    }
    
    function Areas_Seleccionadas(){
        $seleccionadas = "";
        foreach($this->areas as $area){
            $seleccionadas .= $area.",";
        }
        return substr($seleccionadas, 0, -1);
    }

} //fin de clase
?>