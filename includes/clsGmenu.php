<?php
//ca_controller -> asigna areas a controller
//require_once(PathIncludes() . "mantenimiento.php"); 

class gmenu extends mantenimiento{
	var $opcion ="";
	var $fecha ="";
	var $area_codigo ="";
	var $responsable_codigo ="";
	var $turno_codigo ="";
	var $lista_sel =""; 
	var $incidencia_descripcion = "";
	var $anio_codigo = "";
	var $mes_codigo = "";
	var $empleado_sel = "";
	var $rol_codigo = "";
        
	function ListarConsulta(){
		switch ($this->lista_sel){
			
			case 'Entradas':
				$this->listar_entradas();
				break;
			case 'Salidas':
			    $this->listar_salidas();
				break;
			case 'Faltas':
			    $this->listar_faltas();
				break;
			case 'Vacaciones':
			    $this->listar_vacaciones();
				break;
			case 'Otros':
			    $this->listar_otros();
				break;
		}
		return 'OK';
	}

	
function ListarEvento(){

    switch ($this->opcion){

            case '2.1':
                    $this->listar_evento_abierto();
                    break;

            case '2.2':
               $this->listar_evento_cerrado();
                    break;
    }

    return 'OK';
}

		
function listar_entradas(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
		
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo " \n<td align=center class=ColumnTD>Hora entrada</td>";
    echo "\n</tr>";
			
    $ssql= "select dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre, ar.area_descripcion,t.turno_descripcion,"; 
    $ssql.= "isnull(convert(varchar(10),asistencia_entrada, 108),'') as hora_entrada ";
    $ssql.= " FROM CA_Asistencias a (nolock)  INNER JOIN ";
    $ssql.= " CA_Asistencia_Responsables r (nolock) "; 
    $ssql.= " ON a.Empleado_Codigo =	r.Empleado_Codigo AND "; 
    $ssql.= " a.Asistencia_codigo = r.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.asistencia_entrada is not null";
    $ssql.= " and a.ca_estado_codigo = 1 ";
    $ssql.= " order by 1 ";
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[1] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[2] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[3] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}


function listar_salidas(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
		
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo " \n<td align=center class=ColumnTD>Hora salida</td>";
    echo "\n</tr>";
			
    $ssql= "select dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre, ar.area_descripcion,t.turno_descripcion,"; 
    //$ssql.= "isnull(substring(cast(cast(asistencia_salida as smalldatetime) as char),13,7),'') as hora_sal ";
    $ssql.= "isnull(convert(varchar(10),asistencia_salida, 108),'') as hora_salida ";
    $ssql.= " FROM CA_Asistencias a (nolock)  INNER JOIN ";
    $ssql.= " CA_Asistencia_Responsables r (nolock) "; 
    $ssql.= " ON a.Empleado_Codigo = r.Empleado_Codigo AND "; 
    $ssql.= " a.Asistencia_codigo = r.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.asistencia_salida is not null";
    $ssql.= " and a.ca_estado_codigo = 1 ";
    $ssql.= " order by 1 ";
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[1] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[2] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[3] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}

function listar_faltas(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Fecha</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo "\n</tr>";

    $ssql= "select dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre, ar.area_descripcion, convert(varchar(10),a.asistencia_fecha,103) as fecha,t.turno_descripcion "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON  ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " INNER JOIN CA_Turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo=1 and ai.Incidencia_codigo = 38 order by 1";

    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[1] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[2] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[3] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}
	

function listar_vacaciones(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
		
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo "\n</tr>";
			
    $ssql= "select dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre, ar.area_descripcion, t.turno_descripcion "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON  ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " INNER JOIN CA_Turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo=1 and ai.Incidencia_codigo = 15 order by 1";
	
    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[1] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[2] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";

    return $rpta;
}


function obtenerAreasPermitidasController(){
    
    $ssql = "";
    $cn=$this->getMyConexionADO();
    $arrAreas=array();
    
    $ssql= "select area_codigo, area_descripcion from vdatos ";
    $ssql.= " where ";
    $ssql.= " 1= ";
       $ssql.= " case when exists(select Area_Codigo ";
          $ssql.= " from CA_Controller ";
          $ssql.= " where Area_Codigo = 0 ";
             $ssql.= " and Empleado_Codigo=".$this->empleado_codigo." and activo=1) ";
       $ssql.= " then 1 else ";
          $ssql.= " case when exists(select area_codigo ";
             $ssql.= " from CA_Controller where Area_Codigo = vdatos.area_codigo ";
                $ssql.= " and Empleado_Codigo = ".$this->empleado_codigo." ";
                $ssql.= " and activo=1) ";
          $ssql.= " then 1 else ";
             $ssql.= " case when exists(select v.area_codigo ";
                $ssql.= " from vdatos as v where v.empleado_codigo = ".$this->empleado_codigo." ";
                   $ssql.= " and v.area_codigo = vdatos.area_codigo and v.Empleado_Codigo=vdatos.Empleado_Codigo) ";
             $ssql.= " then 1 else 0 end ";
          $ssql.= " end ";
       $ssql.= " end ";
    $ssql.= " group by area_codigo, area_descripcion ";
    $ssql.= " Order by 2 ";
    
    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $arrAreas[$i]=$rs->fields[0];
        $i+=1;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    return $arrAreas;
}


function listar_otros(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
		
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo "\n</tr>";
			
    $ssql= "select dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre, ar.area_descripcion, t.turno_descripcion "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON  ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " INNER JOIN CA_Turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo= 1 and ai.Incidencia_codigo not in (1,15,38) order by 1";
	
    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[1] . "</td>";
        $cadena .=" <td align=center>&nbsp;" .$rs->fields[2] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
    
}

        
        
function Consultar(){
    
    switch ($this->opcion){
    case '1.1':
            $this->imprimir_empleados();
            break;
    case '1.2':
            $this->imprimir_marcaciones();
            break;
    case '1.3':
            $this->imprimir_incidencias();
            break;
    case '1.4':
            $this->listar_marcaciones_mensuales();
            break;
    case '1.5':
        $this->listar_acumulado_general();
            break;
    case '2.1':
        $this->imprimir_eventos_abiertos();
            break;
    case '2.2':
        $this->imprimir_eventos_cerrados();
            break;
    case '3.1':
        $this->listar_posicion_tparcial();
            break;
    case '3.2':
        $this->listar_acumulado_tparcial();
            break;
    }
    return 'OK';
    
}
			

function query_analista(){
    
/*$rpta="OK";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){
        $ssql = "select count(*) as areas from ca_controller ";
$ssql .= "where empleado_codigo = " . $this->empleado_codigo; 
        $ssql .= " AND ACTIVO=1 ";

$rs = $this->cnnado->Execute($ssql);
if (!$rs->EOF){
        $this->n_areas = $rs->fields[0]->value;
}else{
    $rpta='No es usuario analista: ' . $this->empleado_codigo;
}
}
return $rpta;*/

    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "select count(*) as areas from ca_controller ";
    $ssql .= "where empleado_codigo = " . $this->empleado_codigo; 
    $ssql .= " AND ACTIVO=1 ";

    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $this->n_areas = $rs->fields[0];
    }else{
        $rpta='No es usuario analista: ' . $this->empleado_codigo;
    }
    return $rpta;
    
}

function query_supervisor(){

    /*$rpta="OK";
    $rpta=$this->conectarme_ado();
    if($rpta=="OK"){
            $ssql = "SELECT EA.AREA_CODIGO as area_id, A.area_descripcion FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ON ";
    $ssql .= " R.EMPLEADO_CODIGO=EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON EA.AREA_CODIGO=A.AREA_CODIGO ";
    $ssql .= "WHERE R.EMPLEADO_ROL_ACTIVO=1 AND R.EMPLEADO_CODIGO= " . $this->empleado_codigo; 
            $ssql .= " AND EA.EMPLEADO_AREA_ACTIVO=1 ";
            $ssql .= "ORDER BY 2"; 

            $rs = $this->cnnado->Execute($ssql);
            if (!$rs->EOF){
                    $this->area_id = $rs->fields[0]->value;
                    $this->area_descripcion= $rs->fields[1]->value;
    }else{
            $rpta='No es usuario supervisor: ' . $this->empleado_codigo;
    }
    } 
    return $rpta;*/
    
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "SELECT EA.AREA_CODIGO as area_id, A.area_descripcion FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ON ";
    $ssql .= " R.EMPLEADO_CODIGO=EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON EA.AREA_CODIGO=A.AREA_CODIGO ";
    $ssql .= "WHERE R.EMPLEADO_ROL_ACTIVO=1 AND R.EMPLEADO_CODIGO= " . $this->empleado_codigo;
    $ssql .= " AND EA.EMPLEADO_AREA_ACTIVO=1 ";
    $ssql .= "ORDER BY 2";
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $this->area_id = $rs->fields[0];
        $this->area_descripcion= $rs->fields[1];
    }else{
        $rpta='No es usuario supervisor: ' . $this->empleado_codigo;
    }
    return $rpta;

}

function query_jefe(){
	
		/*$rpta="OK";
		$rpta=$this->conectarme_ado();
		if($rpta=="OK"){
			$ssql = "SELECT EMPLEADO_CODIGO,ROL_CODIGO ";
        	$ssql .= " FROM CA_EMPLEADO_ROL ";
        	$ssql .= "WHERE ROL_CODIGO in (2,6) AND EMPLEADO_CODIGO = " . $this->empleado_codigo; 
			$ssql .= " AND EMPLEADO_ROL_ACTIVO=1 ";
			
			$rs = $this->cnnado->Execute($ssql);
			if (!$rs->EOF){
				$this->empleado_gj = $rs->fields[0]->value;
				$this->rol_codigo = $rs->fields[1]->value;
	  		}else{
		    	$this->rol_codigo = 0;
	    	}
	 	} 
	 	return $rpta;*/
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "SELECT EMPLEADO_CODIGO,ROL_CODIGO ";
    $ssql .= " FROM CA_EMPLEADO_ROL ";
    $ssql .= "WHERE ROL_CODIGO in (2,6) AND EMPLEADO_CODIGO = " . $this->empleado_codigo;
    $ssql .= " AND EMPLEADO_ROL_ACTIVO=1 ";	
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $this->empleado_gj = $rs->fields[0];
        $this->rol_codigo = $rs->fields[1];
    }else{
        $this->rol_codigo = 0;
    }
    return $rpta;
}


function getCodigoRol($empleado_id){
    
    $rol_code="0"; 
    $rpta="OK";
    $ssql="";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $ssql="select rol_codigo FROM CA_EMPLEADO_ROL where empleado_codigo = " . $empleado_id . " and rol_codigo = 10 and empleado_rol_activo=1";
    $rs = $cn->Execute($ssql);
    $rol_code = $rs->fields[0];	        
    $rs->close();
    $rs=null;
    return $rol_code;
}

//nuevo metodo
function getVerificaRol($empleado_codigo=0,$rol_codigo=0){
    
    $existe=0; 
    
    $ssql="";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $ssql="select rol_codigo FROM CA_EMPLEADO_ROL where empleado_codigo = ? and rol_codigo = ? and empleado_rol_activo=1";
    $params = array(
        $empleado_codigo,
        $rol_codigo
    );
    
    $rs = $cn->Execute($ssql,$params);
    if (!$rs->EOF) $existe=1;
    
    $rs->close();
    $rs=null;
    return $existe;
}


function getVerificaAtentoPeruController($empleado_codigo=0){
    $existe=0;
    $ssql="";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $ssql=" select Area_Codigo ";
         $ssql.=" from CA_Controller ";
         $ssql.=" where Area_Codigo = 0 ";
            $ssql.=" and Empleado_Codigo = ? and activo=1 ";
    
    $params = array(
        $empleado_codigo
    );
    
    $rs = $cn->Execute($ssql,$params);
    if (!$rs->EOF) $existe=1;
    
    $rs->close();
    $rs=null;
    return $existe;
}






function imprimir_empleados(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();		
?>
		  
    <img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
    <p>		  
<?php

    echo "<table id='idTabla' width=50% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=35px>Nro.</td>";
    echo " \n<td align=left class=ColumnTD>Relación de Empleados</td>";
    echo " \n<td colspan=2 align=center class=ColumnTD>Ver</td>";
    echo "\n</tr>";
			
    // Total de empleados registrados en el dia 

    $ssql= "SELECT a.Empleado_Codigo,dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre "; 
    $ssql.= " FROM ca_asistencias a (nolock) left outer join ca_turnos t (nolock) on t.turno_codigo=a.turno_codigo ";
    $ssql.= " left outer join CA_Asistencia_Responsables r (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo = 1 ";
    $ssql.= "group by a.Empleado_Codigo,dbo.udf_empleado_nombre(a.empleado_codigo) ";
    $ssql.= "order by 2 ";
		
    $rs = $cn->Execute($ssql);
    $i=0;
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $i+=1;
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td BORDER=3 >" . $i . "&nbsp;</td>";
            $cadena .=" <td align=left class=DataTD>" . $rs->fields[1] . "</td>";
            $cadena .=" <td align=left class=DataTD><font style='cursor:hand' onclick=\"ver_detalle(". $rs->fields[0] . ")\"><u>Dia</u></font></td>";
            $cadena .=" <td align=left class=DataTD ><font style='cursor:hand' onclick=\"ver_detalle_mes(". $rs->fields[0] . ")\"><u>Mes</u></font></td>";
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
       } 
    }else{
       $lista = "<tr >\n";
       $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
       $lista .="&nbsp;No tiene datos para este turno\n";
       $lista .= "	   </td>\n";
       $lista .= "</tr>\n";        
       echo $lista;
    }
        
    $rs->close();
    $rs=null;
    
    echo "\n</table>";
    return $rpta;
}


function imprimir_marcaciones(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
?>
		  
    <img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
    <p>
<?php
    echo "<table id='idTabla' width=60% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTD>Tipo de marcación</td>";
    echo " \n<td align=center class=ColumnTD>Nro. de empleados</td>";
    echo "\n</tr>";
    //Marcaciones Entradas
    $ssql= "select 'Entradas' as op_marca, count(*) as empleados "; 
    $ssql.= " FROM CA_Asistencias a (nolock)  INNER JOIN ";
    $ssql.= " CA_Asistencia_Responsables r (nolock)   "; 
    $ssql.= " ON a.Empleado_Codigo =	r.Empleado_Codigo AND "; 
    $ssql.= " a.Asistencia_codigo = r.Asistencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.asistencia_entrada is not null";
    $ssql.= " and a.ca_estado_codigo=1 ";
    $ssql.= " order by 1 ";
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        echo "\n<tr>";
        echo " \n<td align=right class=DataTD>Registros de Entradas </td>";
        echo " \n<td align=center class=DataTD >";
        if ($rs->fields[1] > 0){
            echo "\n  <font style='cursor:hand' onclick=\"ver_detalle('Entradas')\"><u>".$rs->fields[1]."</u></font>";
        } else {
            echo "\n ".$rs->fields[1];
        }
        echo "\n</td>";
        echo "\n</tr>";
    }
    // Marcaciones Salidas 
    $ssql= "";
    $ssql= "select 'Salidas' as op_marca, count(*) as empleados "; 
    $ssql.= " FROM CA_Asistencias a (nolock)  INNER JOIN ";
    $ssql.= "      CA_Asistencia_Responsables r (nolock)   "; 
    $ssql.= " 		ON a.Empleado_Codigo =	r.Empleado_Codigo AND "; 
    $ssql.= "      a.Asistencia_codigo = r.Asistencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " 	and a.asistencia_salida is not null";
    $ssql.= " 	and a.ca_estado_codigo=1 ";
    $ssql.= " order by 1 ";
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        echo "\n<tr>";
        echo " \n<td align=right class=DataTD>Registros de Salidas </td>";
        echo " \n<td align=center class=DataTD >";
        if ($rs->fields[1] > 0){
            echo "\n  <font style='cursor:hand' onclick=\"ver_detalle('Salidas')\"><u>".$rs->fields[1]."</u></font>";
        } else {
            echo "\n ".$rs->fields[1];
        }
        echo "\n</td>";
        echo "\n</tr>";
    }
    // Faltas
    $ssql= "select 'Faltas' as op_marca, count(*) as empleados "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON  ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo=1 and ai.Incidencia_codigo = 38 ";
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        echo "\n<tr>";
        echo " \n<td align=right class=DataTD>Registros de Faltas </td>";
        echo " \n<td align=center class=DataTD >";
        if ($rs->fields[1] > 0){
            echo "\n  <font style='cursor:hand' onclick=\"ver_detalle('Faltas')\"><u>" . $rs->fields[1] . "</u></font>";
        } else {
            echo "\n " . $rs->fields[1] ;
        } 	
        //echo "\n  <font style='cursor:hand' onclick=\"ver_detalle('Faltas')\"><u>" . $rs->fields[1]->value . "</u></font>";
        echo "\n</td>";
        echo "\n</tr>";
    }   
    // Vacaciones
    $ssql= "select 'Vacaciones' as op_marca, count(*) as empleados "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON  ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo=1 and ai.Incidencia_codigo=15 ";
    //	echo $ssql;
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        echo "\n<tr>";
        echo " \n<td align=right class=DataTD>Registros de Vacaciones</td>";
        echo " \n<td align=center class=DataTD >";
        if ($rs->fields[1] > 0){
            echo "\n  <font style='cursor:hand' onclick=\"ver_detalle('Vacaciones')\"><u>".$rs->fields[1]."</u></font>";
        } else {
            echo "\n " . $rs->fields[1] ;
        } 	
        echo "\n</td>";
        echo "\n</tr>";
    }	   
    // Otros
    $ssql= "select 'Faltas' as op_marca, count(*) as empleados "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON  ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103)";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo=1 and ai.Incidencia_codigo not in (1,15,38) ";
    //	echo $ssql;
    $rs = $cn->Execute($ssql);
    
    if (!$rs->EOF){
        echo "\n<tr>";
        echo " \n<td align=right class=DataTD>Registros de otras incidencias </td>";
        echo " \n<td align=center class=DataTD >";
        if ($rs->fields[1] > 0){
            echo "\n  <font style='cursor:hand' onclick=\"ver_detalle('Otros')\"><u>" . $rs->fields[1] . "</u></font>";
        } else {
            echo "\n " . $rs->fields[1] ;
        }
        echo "\n</td>";
        echo "\n</tr>";
    }		   
    echo "\n</table>";		
    return $rpta;
}


function imprimir_incidencias(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
?> 
    <img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
    <p>
<?php
    echo "<table id='idTabla' width=60% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTD>Tipo de incidencia</td>";
    echo " \n<td align=center class=ColumnTD>Nro. de empleados</td>";
    echo "\n</tr>";
    // Total de incidencias 
				
    $ssql= "SELECT i.Incidencia_codigo,i.Incidencia_descripcion,count(*) as empleados "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " INNER JOIN CA_Turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo = 1 and ai.Incidencia_codigo not in (1,15,38,42,43) and i.incidencia_activo = 1 ";
    $ssql.= " GROUP BY i.Incidencia_codigo,i.Incidencia_descripcion ";
    $ssql.= " order by 2 ";
    $rs = $cn->Execute($ssql);
    $i=0;
    if(!$rs->EOF){
        while(!$rs->EOF){
            $i+=1;
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td align=right class=DataTD>&nbsp;" .$rs->fields[1] . "</td>";
            $cadena .=" <td align=center class=DataTD><font style='cursor:hand' onclick=\"ver_detalle(". $rs->fields[0] . ")\"><u>" . $rs->fields[2] . "</u></font></td>";
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
        }
    }else{
        $lista = "<tr >\n";
        $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
        $lista .="&nbsp;No tiene datos de incidencia reportada\n";
        $lista .= "	   </td>\n";
        $lista .= "</tr>\n";
        echo $lista;
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}


function listar_evento_abierto(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
	
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Responsable</td>";
    echo " \n<td align=center class=ColumnTD>Fecha</td>";
    echo " \n<td align=center class=ColumnTD>Hora ini</td>";
    echo " \n<td align=center class=ColumnTD>Hora fin</td>";
    echo "\n</tr>";
			
    $ssql = " SELECT dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre,"; 
    $ssql.= " ar.area_descripcion, dbo.udf_empleado_nombre(r.responsable_codigo) as empleado_responsable,";
    $ssql.= " convert(varchar(10),a.Asistencia_fecha,103) as fecha,";
    //$ssql.= " isnull(substring(cast(cast(e.evento_hora_inicio as smalldatetime) as char),13,7),'') as hora_ini,"; 
    //$ssql.= " isnull(substring(cast(cast(e.evento_hora_fin as smalldatetime) as char),13,7),'') as hora_fin ";
    $ssql.= " isnull(convert(varchar(10),e.evento_hora_inicio, 108),'') as hora_ini,"; 
    $ssql.= " isnull(convert(varchar(10),e.evento_hora_fin, 108),'') as hora_fin ";
    $ssql.= " FROM CA_Eventos e (nolock) INNER JOIN ";
    $ssql.= " CA_Asistencias a (nolock) ON  e.Empleado_Codigo = a.Empleado_Codigo AND "; 
    $ssql.= " e.Asistencia_codigo =  a.Asistencia_codigo INNER JOIN "; 
    $ssql.= " CA_Asistencia_Responsables r (nolock) ON  a.Empleado_Codigo =  r.Empleado_Codigo AND ";
    $ssql.= " a.Asistencia_codigo =  r.Asistencia_codigo INNER JOIN ";
    $ssql.= " CA_Incidencias i (nolock) ON  e.Incidencia_Codigo =  i.Incidencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    $ssql.= " and e.evento_activo = 1 and a.ca_estado_codigo = 1 and e.incidencia_codigo = " . $this->lista_sel;
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    //if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " order by 3,1 ";
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    $i=0;		    
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[1] . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[2] . "&nbsp;</td>";
              $cadena .=" <td align=center>" .$rs->fields[3] . "</td>";
        $cadena .=" <td align=center>" .$rs->fields[4] . "</td>";
        if ($rs->fields[5] <> "") {
            $cadena .=" <td align=center>" .$rs->fields[5] . "</td>";
        } else {
            $cadena .=" <td BORDER=3 BORDERCOLOR='#C0C0C0'></td>";
        } 	  
                  $cadena .="</tr>";
        echo $cadena;
        $rs->movenext();
    }
        
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;	
}
	
		
function listar_evento_cerrado(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
	
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Responsable</td>";
    echo " \n<td align=center class=ColumnTD>Fecha</td>";
    echo " \n<td align=center class=ColumnTD>Hora ini</td>";
    echo " \n<td align=center class=ColumnTD>Hora fin</td>";
    echo "\n</tr>";
			
    $ssql = " SELECT dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre,"; 
    $ssql.= " ar.area_descripcion, dbo.udf_empleado_nombre(r.responsable_codigo) as empleado_responsable,";
    $ssql.= " convert(varchar(10),a.Asistencia_fecha,103) as fecha,";
    //$ssql.= " isnull(substring(cast(cast(e.evento_hora_inicio as smalldatetime) as char),13,7),'') as hora_ini,"; 
    //$ssql.= " isnull(substring(cast(cast(e.evento_hora_fin as smalldatetime) as char),13,7),'') as hora_fin "; 
    $ssql.= " isnull(convert(varchar(10),e.evento_hora_inicio, 108),'') as hora_ini,";
    $ssql.= " isnull(convert(varchar(10),e.evento_hora_fin, 108),'') as hora_fin ";
    $ssql.= " FROM CA_Eventos e (nolock) INNER JOIN ";
    $ssql.= " CA_Asistencias a (nolock) ON  e.Empleado_Codigo = a.Empleado_Codigo AND "; 
    $ssql.= " e.Asistencia_codigo =  a.Asistencia_codigo INNER JOIN "; 
    $ssql.= " CA_Asistencia_Responsables r (nolock) ON  a.Empleado_Codigo =  r.Empleado_Codigo AND ";
    $ssql.= " a.Asistencia_codigo =  r.Asistencia_codigo INNER JOIN ";
    $ssql.= " CA_Incidencias i (nolock) ON  e.Incidencia_Codigo =  i.Incidencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    $ssql.= " and e.evento_activo = 0 and a.ca_estado_codigo = 1 and e.incidencia_codigo = " . $this->lista_sel;
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    //if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " order by 3,1 ";		
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    $i=0;
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[1] . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[2] . "&nbsp;</td>";
        $cadena .=" <td align=center>" .$rs->fields[3] . "</td>";
        $cadena .=" <td align=center>" .$rs->fields[4] . "</td>";
        $cadena .=" <td align=center>" .$rs->fields[5] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}
		
function ListarIncidencia(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
	
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Responsable</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo " \n<td align=center class=ColumnTD>Hora ini</td>";
    echo " \n<td align=center class=ColumnTD>Hora fin</td>";
    echo " \n<td align=center class=ColumnTD>Tiempo min</td>";
    echo "\n</tr>";
			
    $ssql = " SELECT dbo.udf_empleado_nombre(a.empleado_codigo) as empleado_nombre,"; 
    $ssql.= " ar.area_descripcion, dbo.udf_empleado_nombre(r.responsable_codigo) as empleado_responsable,";
    $ssql.= " t.turno_descripcion,";
    $ssql.= " isnull(convert(varchar(10),ai.incidencia_hora_inicio, 108),''),";
    $ssql.= " isnull(convert(varchar(10),ai.incidencia_hora_fin, 108),''),";
    $ssql.= "ai.tiempo_minutos "; 
    $ssql.= " FROM CA_Asistencias a (nolock) INNER JOIN CA_Asistencia_Responsables r (nolock) ON ";
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo AND a.Asistencia_codigo = r.Asistencia_codigo "; 
    $ssql.= " INNER JOIN CA_Asistencia_Incidencias ai (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = ai.Empleado_Codigo AND a.Asistencia_codigo = ai.Asistencia_codigo ";
    $ssql.= " INNER JOIN Areas ar (nolock) ON a.area_codigo = ar.area_codigo ";
    $ssql.= " INNER JOIN CA_Incidencias i (nolock) ON ai.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " INNER JOIN CA_Turnos t (nolock) ON a.turno_codigo = t.turno_codigo ";
    $ssql.= " WHERE a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.ca_estado_codigo = 1 and i.incidencia_activo = 1 and ai.Incidencia_codigo = ". $this->lista_sel; "";
    $ssql.= " order by 2,3,1 ";
		
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    $i=0; 
    while(!$rs->EOF){
        $i+=1;
        $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $cadena .=" <td >" . $i . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[1] . "&nbsp;</td>";
        $cadena .=" <td >" . $rs->fields[2] . "&nbsp;</td>";
        $cadena .=" <td align=center>" .$rs->fields[3] . "</td>";
        $cadena .=" <td align=center>" .$rs->fields[4] . "</td>";
        $cadena .=" <td align=center>" .$rs->fields[5] . "</td>";
        $cadena .=" <td align=center>" .$rs->fields[6] . "</td>";
        $cadena .="</tr>";
        echo $cadena;
        $rs->MoveNext();
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}
		
		
function ListarEmpleadoAsistencia(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
    $responsable_codigo = 0;
    $asistencia_codigo = 0;
    //$cn->debug=true;
    //class=FormTABLE
    //echo "<b>Registro de Asistencia</b>";
    //echo "</br>";
    echo "<table width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    echo "<tr>";
    echo "<td colspan='7' class='titulo'>Registro de Asistencia";
    echo "</td>";
    echo "</tr>";
    echo " </table>";
    echo "<table class='FormTABLA' id='idTabla' width=99% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    /*echo "<tr>";
    echo " <td align=center class=ColumnTD>Código</td>";
    echo " <td align=center class=ColumnTD>Fecha</td>";
    echo " <td align=center class=ColumnTD>Entrada</td>";
    echo " <td align=center class=ColumnTD>Salida</td>";
    echo " <td align=center class=ColumnTD>Turno Registrado</td>";
    echo " <td align=center class=ColumnTD>IP Entrada</td>";
    echo " <td align=center class=ColumnTD>IP Salida</td>";
    echo "</tr>";*/
			
    echo "<tr>";
    //echo " <td class=Columna align=center >Código</td>";
    echo " <td class=Columna align=center >Fecha</td>";
    echo " <td class=Columna align=center >Entrada</td>";
    echo " <td class=Columna align=center >Salida</td>";
    echo " <td class=Columna align=center >Turno Registrado</td>";
    echo " <td class=Columna align=center >IP Entrada</td>";
    echo " <td class=Columna align=center >IP Salida</td>";
    echo " <td class=Columna align=center >Ori.E</td>";
    echo " <td class=Columna align=center >Ori.S</td>";
    echo " <td class=Columna align=center >Exten.</td>";
    echo " <td class=Columna align=center >Tipo.</td>";
    echo " <td class=Columna align=center >Tiempo</td>";
    echo "</tr>";

    $ssql = " SELECT asistencia_codigo, convert(varchar(10),Asistencia_fecha,103) as fecha ,isnull(convert(varchar(10),asistencia_entrada, 108),'') as entrada,"; 
    $ssql.= " isnull(convert(varchar(10),asistencia_salida, 108),'') as salida,
            t.turno_descripcion,
            t.turno_codigo,
            ip_entrada,
            ip_salida,
            isnull(origen_entrada,''),isnull(origen_salida,''),case when extension_turno=0 then 'NO' else 'SI' end as extension_turno,
              extension_tiempo, 
              CASE a.tipo_extension_codigo WHEN 1 THEN 'Por Tiempo' WHEN 2 THEN 'Por Monetario' ELSE '' END as tipo  ";
    $ssql.= " FROM ca_asistencias a (nolock) ";
    $ssql.= " left outer join ca_turnos t (nolock) on t.turno_codigo=a.turno_codigo "; 
    $ssql.= " WHERE asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) and empleado_codigo = " . $this->lista_sel . " ";
    $ssql.= " AND ca_estado_codigo=1 ";
    $ssql.= " Order by asistencia_entrada desc ";
    //echo $ssql;  class='ca_DataTD'			
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $asistencia_codigo = $rs->fields[0];
        $cadena ="<tr  onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        //echo " <td class=fila align=center>" .$rs->fields[0]->value . "</td>";
        echo " <td class=fila align=center>" .$rs->fields[1]."</td>";
        echo " <td class=fila align=center>" .$rs->fields[2]."</td>";
        //echo " <td class=fila align=center>" .$rs->fields[3]->value . "</td>";
        if ($rs->fields[3] <> ""){
            echo "<td class='fila' align=center>".$rs->fields[3] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        echo " <td class=fila align=center>" .$rs->fields[4] . "</td>";
        echo " <td class=fila align=center>" .$rs->fields[6] . "</td>";
        //echo " <td class=fila align=center>" .$rs->fields[7]->value . "</td>";
        if ($rs->fields[7] <> ""){
            echo "<td class='fila' align=center>".$rs->fields[7] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        echo "<td class='fila' align=center>".$rs->fields[8] . "</td>";
        echo "<td class='fila' align=center>".$rs->fields[9] . "</td>";
        echo "<td class='fila' align=center>".$rs->fields[10] . "</td>";
        echo "<td class='fila' align=center>".$rs->fields[12] . "</td>";
        echo "<td class='fila' align=center>".$rs->fields[11] . "</td>";
        echo "</tr>";
        echo $cadena;
    }
            
    //->previo$responsable_nombre=$er->empleado_nombre;
    echo "</table>";
    //lista el responsable:
    // echo "<p>";
    //echo "<br><br/>";
    echo "<br/>";
    $ssql = " SELECT responsable_codigo,responsable "; 
    $ssql.= " FROM vwca_asistencias ";
    $ssql.= " WHERE empleado_codigo = " . $this->lista_sel . " ";
    $ssql.= " and asistencia_codigo = " . $rs->fields[0] . " ";
    $ssql.= " ORDER BY 2 ";
    //echo $ssql;
    $rs1 = $cn->Execute($ssql);
    if(!$rs1->EOF){	
        echo "<table id='idTabla1' width=99% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px>";
        echo "<tr>";
        echo " <td align=left class='titulo'>Responsable:&nbsp;" . $rs1->fields[1] . "</td>";
        echo "</tr>";
        echo "</table>";
        $responsable_codigo=$rs1->fields[0];
    }	
							
    //incidencias registradas: class=FormTABLE
    //echo "<p>";
    //echo "<br/>";
    //echo "<br/><b>Incidencias Registradas</b>";
    echo "<table class='FormTABLA' id='idTabla2' width=99% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    echo "<tr>";
    echo " <td align=center class=Columna>Incidencias Registradas</td>";
    echo " <td align=center class=Columna>Inicio</td>";
    echo " <td align=center class=Columna>Fin</td>";
    echo " <td align=center class=Columna>Tiempo</td>";
    echo " <td align=center class=Columna>IP</td>";
    echo " <td align=center class=Columna>Sup. Inicio</td> ";//--16
    echo " <td align=center class=Columna>Sup. Fin</td> ";//--17
    
    echo " <td align=center class=Columna>Fecha Registro</td>";
    
    
    echo "</tr>";
	//Mvv	
    $ssql = " SELECT a.Empleado_Codigo,a.Asistencia_codigo,a.Incidencia_codigo,"; 
    $ssql.= " convert(varchar(10),a.fecha_reg,103) + ' ' + convert(varchar(8), a.fecha_reg,108) AS fecha,";
    $ssql.= " i.Incidencia_descripcion AS incidencia,a.responsable_codigo,a.asistencia_incidencia_codigo,";
    $ssql.= " i.incidencia_icono,isnull(convert(varchar(8),incidencia_hora_inicio, 108),'') as inicio,";
    $ssql.= " isnull(convert(varchar(8),incidencia_hora_fin, 108),'') as fin,";
    $ssql.= " case when len(a.horas)=1 then '0' + rtrim(cast(a.horas as char)) else rtrim(cast(a.horas as char)) end as hora,";//--
    $ssql.= " case when len(a.minutos)=1 then '0' + rtrim(cast(a.minutos as char)) else rtrim(cast(a.minutos as char)) end as minutos,";//--
    $ssql.= " abs(i.Incidencia_Editable) as editable,";
    $ssql.= " case when exists(select responsable_codigo from ca_asistencia_responsables ";
    $ssql.= " where empleado_codigo = " . $this->lista_sel . " ";
    $ssql.= " and asistencia_codigo = " . $rs->fields[0] . " ";
    $ssql.= " and responsable_codigo = " . $this->responsable_codigo . ") ";
    $ssql.= " then 1 else 0 end as activo , ";
    $ssql.= " a.ip_incidencia , ";
    $ssql.= " convert(varchar(10),a.fecha_reg,103)+ ' ' + convert(varchar(10),a.fecha_reg,108) ";
    $ssql.= " ,case when ev.codigo_supervisor_inicio is null then '' ";//--
    $ssql.= " else dbo.UDF_EMPLEADO_NOMBRE(ev.codigo_supervisor_inicio) end as codigo_supervisor_inicio ";//--
    $ssql.= " ,case when ev.codigo_supervisor_fin is null then '' ";//--
    $ssql.= " else dbo.UDF_EMPLEADO_NOMBRE(ev.codigo_supervisor_fin) end as codigo_supervisor_fin ";//--
    $ssql.= " FROM CA_Asistencia_Incidencias a (nolock) INNER JOIN CA_Incidencias i (nolock) ON ";
    $ssql.= " a.Incidencia_codigo = i.Incidencia_codigo ";
    $ssql.= " left join CA_Eventos ev(nolock) on ev.Empleado_Codigo = a.Empleado_Codigo ";//--
    $ssql.= " and ev.Asistencia_codigo = a.Asistencia_codigo and ev.Incidencia_Codigo = a.Incidencia_codigo  ";//--
    $ssql.= " and ev.Evento_Codigo=a.evento_codigo ";//--
    $ssql.= " WHERE a.empleado_codigo = " . $this->lista_sel . " ";//--
    $ssql.= " and a.asistencia_codigo = " . $rs->fields[0] . " ";
    $ssql.= " ORDER BY 4 ";
    //echo $ssql; class='ca_DataTD'
    $rs1 = $cn->Execute($ssql);

    $cuentabr=0;
    while(!$rs1->EOF){
        echo "<tr  onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        echo " <td class='fila' align=left>" .$rs1->fields[4] . "</td>";
        //echo " <td class='fila' align=center>" .$rs1->fields[8]->value . "</td>";
        if ($rs1->fields[8] <> ""){
            echo "<td class='fila' align=center>".$rs1->fields[8] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        //echo " <td class='fila' align=center>" .$rs1->fields[9]->value . "</td>";
        if ($rs1->fields[9] <> ""){
            echo "<td class='fila' align=center>".$rs1->fields[9] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        if ($rs1->fields[10] <> ""){
            echo "<td class='fila' align=center>".$rs1->fields[10].":".$rs1->fields[11]."</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        if ($rs1->fields[14] <> ""){
            echo "<td class='fila' align=center>".$rs1->fields[14] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        if ($rs1->fields[16] <> ""){//--
            echo "<td class='fila' align=center>".$rs1->fields[16] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        if ($rs1->fields[17] <> ""){//--
            echo "<td class='fila' align=center>".$rs1->fields[17] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        if ($rs1->fields[15] <> ""){
            echo "<td class='fila' align=center>".$rs1->fields[15] . "</td>";
        } else {
            echo "<td class='fila' align=center>&nbsp;</td>";
        }
        
        
        
        echo "</tr>";
        //echo $cadena;
        $cuentabr=$cuentabr+1;
        $rs1->MoveNext();

    }
    echo "</table>";
            
    /*for($xy=0;$xy<$cuentabr;$xy++){
        echo "</br>";
    }*/
    //Eventos Registrados sin validar: class=FormTABLE

    $datos_eventos_sv="";  
    //$datos_eventos_sv.= "<p>&nbsp;</p>";  
    //$datos_eventos_sv.= "<br/><b>Eventos Registrados sin validar</b>";
    $datos_eventos_sv.= "<br/><table width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    $datos_eventos_sv.= "<tr><td colspan='3' class='titulo'>Eventos Registrados sin validar</td></tr></table>";
    $datos_eventos_sv.= "<table class='FormTABLA' id='idTablass' width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    //   echo "<th align=left>Eventos Registrados sin validar</th>";
    //$datos_eventos_sv.= "<tr>";
    //$datos_eventos_sv.= "</tr>";
    //$datos_eventos_sv.= "<tr>";
    //$datos_eventos_sv.= "<td align=center colspan='3' class=ColumnTD></td>";
    $datos_eventos_sv.= "<tr align=center >";
    $datos_eventos_sv.= "<td align=left class=Columna>Evento</td>";
    $datos_eventos_sv.= "<td align=center class=Columna>Inicio</td>";
    $datos_eventos_sv.= "<td align=center class=Columna>Fin</td>";
    $datos_eventos_sv.= "</tr>";
    $ssql = " SELECT e.Empleado_Codigo,e.Asistencia_codigo,e.Incidencia_codigo,"; 
    $ssql.= " i.Incidencia_descripcion AS incidencia,i.incidencia_icono,";
    $ssql.= " isnull(convert(varchar(8),evento_hora_inicio, 108),'') as inicio,";
    $ssql.= " isnull(convert(varchar(8),evento_hora_fin, 108),'') as fin,";
    $ssql.= " isnull(datediff(minute,evento_hora_inicio,evento_hora_fin),0)  as tiempo, e.Evento_codigo,";
    $ssql.= " convert(varchar(10), evento_hora_inicio,103)  + ' ' +  convert(varchar(8),evento_hora_inicio, 108),";
    $ssql.= " convert(varchar(10), evento_hora_fin ,103)  +  ' '  +  convert(varchar(8),evento_hora_fin, 108) ";
    $ssql.= " FROM CA_Eventos e (nolock) INNER JOIN CA_Incidencias i (nolock) ON ";
    $ssql.= " e.Incidencia_codigo = i.Incidencia_codigo "; 
    $ssql.= " WHERE e.empleado_Codigo = " . $this->lista_sel . " ";
    $ssql.= " and e.Asistencia_Codigo = " . $rs->fields[0] . " ";
    $ssql.= " and e.evento_activo=1 and e.Evento_Hora_Fin is not null ";
    $ssql.= " ORDER BY 3 ";
    //echo $ssql;  //class='ca_DataTD'
    $cuenta_sv=0;
    $rs1 = $cn->Execute($ssql);
    while(!$rs1->EOF){
        $datos_eventos_sv.="<tr onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $datos_eventos_sv.= " <td class='fila' align=left >" .$rs1->fields[3] . "</td>";
        $datos_eventos_sv.= " <td class='fila' align=center>" .$rs1->fields[5] . "</td>";
        $datos_eventos_sv.= " <td class='fila' align=center>" .$rs1->fields[6] . "</td>";
        $datos_eventos_sv.= "</tr>";
        $cuenta_sv=$cuenta_sv+1;
        $rs1->MoveNext();
    }
			
    $datos_eventos_sv.= "</table><br/>";
    if($cuenta_sv>0){
        echo $datos_eventos_sv;
    }
    
    //--@@mcortezc eventos validables
    $datos_eventos_validables="";
    $datos_eventos_validables.= "<br/><table width=99% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    $datos_eventos_validables.= "<tr><td colspan='3' class='titulo'>Eventos  válidables</td></tr></table>";
    $datos_eventos_validables.= "<table class='FormTABLA' id='idTablass' width=99% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    $datos_eventos_validables.= "<tr align=center >";
    $datos_eventos_validables.= "<td align=left class=Columna>Tiempo</td>";
    $datos_eventos_validables.= "<td align=center class=Columna>Observacion</td>";
    $datos_eventos_validables.= "<td align=center class=Columna>Ticket</td>";
    $datos_eventos_validables.= "<td align=left class=Columna>Validable por</td>";
    $datos_eventos_validables.= "<td align=center class=Columna>Evento</td>";
    $datos_eventos_validables.= "<td align=center class=Columna>Estado</td>";
    $datos_eventos_validables.= "<td align=center class=Columna>Usuario Registra</td>";
    $datos_eventos_validables.= "<td align=center class=Columna>Fecha Registro</td>";
    $datos_eventos_validables.= "</tr>";
    
    require_once(PathIncludesGAP() . "clsCA_Eventos.php");
    $ob=new ca_eventos();
    $ob->setMyUrl($this->getMyUrl());
    $ob->setMyUser($this->getMyUser());
    $ob->setMyPwd($this->getMyPwd());
    $ob->setMyDBName($this->getMyDBName());
    
    $ssql = " SELECT cast(CA_Eventos.horas as varchar)+':'+cast(CA_Eventos.minutos as varchar) as tiempo, ";
    $ssql.= " CA_Eventos.observacion, ";
    $ssql.= " case when CA_Eventos.num_ticket is null then '' else CA_Eventos.num_ticket end ticket, ";
    $ssql.= " case when ca_incidencias.validable=1  ";
    $ssql.= " then 'Por Persona'  ";
    $ssql.= " else case when ca_incidencias.validable_mando=1  ";
    $ssql.= " then 'Por Mando'  ";
    $ssql.= " else 'Por Gerente' end end validador, ";
    $ssql.= " ca_incidencias.incidencia_descripcion, ";
    $ssql.= " ca_evento_estado.ee_descripcion, ";
    $ssql.= " CA_Eventos.Empleado_Codigo,  ";
    $ssql.= " CA_Eventos.Asistencia_codigo, ";
    $ssql.= " CA_Eventos.Incidencia_Codigo, ";
    $ssql.= " CA_Eventos.Evento_Codigo, ";
    $ssql.= " isnull(Empleados.Empleado_Apellido_Paterno+' '+Empleados.Empleado_Apellido_Materno+' '+Empleados.Empleado_Nombres,'') as Supervisor_Inicia ";
    $ssql.= " ,convert(varchar,CA_Eventos.fecha_reg_inicio,103)+' '+convert(varchar,CA_Eventos.fecha_reg_inicio,108) as Fecha_Registro ";
    $ssql.= " FROM CA_asistencia_responsables  ";
    $ssql.= " INNER JOIN CA_Eventos on CA_asistencia_responsables.empleado_codigo = CA_Eventos.empleado_codigo and CA_asistencia_responsables.asistencia_codigo = CA_Eventos.asistencia_codigo  ";
    $ssql.= " INNER JOIN ca_incidencias on CA_Eventos.incidencia_codigo = ca_incidencias.incidencia_codigo  and (ca_incidencias.validable = 1 or ca_incidencias.validable_mando=1 or ca_incidencias.validable_gerente = 1 )  ";
    $ssql.= " INNER JOIN ca_evento_estado on CA_Eventos.ee_codigo = ca_evento_estado.ee_codigo  ";
    $ssql.= " LEFT JOIN Empleados ON CA_Eventos.codigo_supervisor_inicio = Empleados.Empleado_Codigo ";
    $ssql.= " WHERE  CA_asistencia_responsables.responsable_codigo = ".$responsable_codigo." ";
    $ssql.= " and CA_Eventos.empleado_codigo = ".$this->lista_sel."   ";
    $ssql.= " and CA_Eventos.asistencia_codigo = ".$asistencia_codigo." ";
    
    $cuenta_validables=0;
    $rs2 = $cn->Execute($ssql);
    while(!$rs2->EOF){
        $datos_eventos_validables.="<tr onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $datos_eventos_validables.= " <td class='fila' align=left >".$rs2->fields[0] . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=center>".$rs2->fields[1] . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=center>".$rs2->fields[2] . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=left >".$ob->Estado_Evento_Aprobacion($rs2->fields[8],$rs2->fields[6],$rs2->fields[7],$rs2->fields[9]) . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=center>".$rs2->fields[4] . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=center>".$rs2->fields[5] . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=center>".$rs2->fields[10] . "</td>";
        $datos_eventos_validables.= " <td class='fila' align=center>".$rs2->fields[11] . "</td>";
        $datos_eventos_validables.= "</tr>";
        $cuenta_validables=$cuenta_validables+1;
        $rs2->MoveNext();
    }
    
    $datos_eventos_validables.= "</table><br/>";
    if($cuenta_validables>0){
        echo $datos_eventos_validables;
    }
    
						
    //Tiempo de Conexion:
    /*
    $datos_tiempo_con = "";
    //$datos_tiempo_con .= "<p>&nbsp;</p>";
    //$datos_tiempo_con .= "<br/><b>Tiempos de Conexión</b>"; 
    $datos_tiempo_con.= "<table width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    $datos_tiempo_con.= "<tr><td colspan='4' class='titulo'>Tiempos de Conexión</td></tr></table"; 
    $datos_tiempo_con .= "<table class='FormTABLA' id='idTablasx' width=95% border=0 cellspacing=0 cellpadding=0 align=left style=font-size:12px >";
    //echo "<th align=left>Tiempos de Conexión</th>";
    //$datos_tiempo_con .= "<tr>";
    //echo " <td align=left colspan='4' class=ColumnTD>Responsable:&nbsp;" . $this->responsable_nombre . "</td>";
    //$datos_tiempo_con .= "</tr>";
    //$datos_tiempo_con .= "<tr>";
    //$datos_tiempo_con .= "<td align=center class=ColumnTD></td>";
    $datos_tiempo_con .= "<tr align=center>";
    $datos_tiempo_con .= "<td align=left class=Columna>Código</td>";
    $datos_tiempo_con .= "<td align=center class=Columna>Fecha</td>";
    $datos_tiempo_con .= "<td align=center class=Columna>Unidad de Servicio</td>";
    $datos_tiempo_con .= "<td align=center class=Columna>Tiempo</td>";
    $datos_tiempo_con .= "</tr>";

    $ssql = " SELECT tiempo_codigo,convert(varchar(10),tiempo_fecha,103) as fecha,v.exp_nombrecorto + '(' + v.exp_codigo + ')',"; 
    $ssql.= " case when len(horas)=1 then '0' + rtrim(cast(horas as char)) else rtrim(cast(horas as char)) end as hora,";
    $ssql.= " case when len(minutos)=1 then '0' + rtrim(cast(minutos as char)) else rtrim(cast(minutos as char)) end as minutos ";
    $ssql.= " from ca_tiempos (nolock) inner join v_campanas v (nolock) on v.cod_campana=ca_tiempos.cod_campana  ";
    $ssql.= " WHERE empleado_Codigo = " . $this->lista_sel . " ";
    $ssql.= " and tiempo_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    $ssql.= " ORDER BY 1 ";
			
    $rs1 = $this->cnnado->Execute($ssql);
    //echo $ssql;
    $cuenta_sv=0;
    $rs1 = $this->cnnado->Execute($ssql);
    while(!$rs1->EOF()){
    $datos_tiempo_con.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
    $datos_tiempo_con.= " <td class='fila' align=left >" .$rs1->fields[0]->value . "</td>";
    $datos_tiempo_con.= " <td class='fila' align=center>" .$rs1->fields[1]->value . "</td>";
    $datos_tiempo_con.= " <td class='fila' align=center>" .$rs1->fields[2]->value . "</td>";
              $datos_tiempo_con.= " <td class='fila' align=center>" .$rs1->fields[3]->value . ":" .$rs1->fields[4]->value . "</td>";
              $datos_tiempo_con.= "</tr>";
    $cuenta_sv=$cuenta_sv+1;
              $rs1->movenext();
    }

    $datos_tiempo_con.= "</table>";
    if($cuenta_sv>0){
            echo $datos_tiempo_con;
    }*/			 
    $rs1->close();
    $rs1=null;   
    return $rpta;
}
	

function ListarTiempoConexion(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
    //Tiempo de Conexion:
    $datos_tiempo_con = "";
    //$datos_tiempo_con .= "<p>&nbsp;</p>";
    //$datos_tiempo_con .= "<br/><b>Tiempos de Conexión</b>"; 
    //$datos_tiempo_con.= "<table width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    //$datos_tiempo_con.= "<tr><td colspan='3' class='titulo'>CPSA</td></tr></table>"; 
    $datos_tiempo_con .= "<table class='FormTABLA' id='idTablasx' width=95% border=0 cellspacing=0 cellpadding=0 align=center style=font-size:12px >";
    //echo "<th align=left>Tiempos de Conexión</th>";
    //$datos_tiempo_con .= "<tr>";
    //echo " <td align=left colspan='4' class=ColumnTD>Responsable:&nbsp;" . $this->responsable_nombre . "</td>";
    //$datos_tiempo_con .= "</tr>";
    //$datos_tiempo_con .= "<tr>";
    //$datos_tiempo_con .= "<td align=center class=ColumnTD></td>";
    $datos_tiempo_con .= "<tr align=center>";
    $datos_tiempo_con .= "<td align=center class=Columna>Origen</td>";
    $datos_tiempo_con .= "<td align=center class=Columna>Fecha</td>";
    $datos_tiempo_con .= "<td align=center class=Columna>Unidad de Servicio</td>";
    $datos_tiempo_con .= "<td align=center class=Columna>Tiempo</td>";
    $datos_tiempo_con .= "</tr>";
					
    $ssql = " SELECT tiempo_codigo,convert(varchar(10),tiempo_fecha,103) as fecha,v.exp_nombrecorto + '(' + v.exp_codigo + ')',"; 
    $ssql.= " case when len(horas)=1 then '0' + rtrim(cast(horas as char)) else rtrim(cast(horas as char)) end as hora,";
    $ssql.= " case when len(minutos)=1 then '0' + rtrim(cast(minutos as char)) else rtrim(cast(minutos as char)) end as minutos , ";
    $ssql.= " case when esavaya=1 then 'AVAYA' else 'CPSA' end as plataforma ";
    $ssql.= " from ca_tiempos (nolock) inner join v_campanas v (nolock) on v.cod_campana=ca_tiempos.cod_campana  ";
    $ssql.= " WHERE empleado_Codigo = " . $this->lista_sel . " ";
    $ssql.= " and tiempo_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    //$ssql.= " and tiempo_fecha = convert(datetime,'08/06/2011',103) ";
    $ssql.= " ORDER BY 1 ";
			
    $rs1 = $cn->Execute($ssql);
    //echo $ssql;
    $cuenta_sv=0;
    $rs1 = $cn->Execute($ssql);
    while(!$rs1->EOF){
        $datos_tiempo_con.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
        $datos_tiempo_con.= " <td class='fila' align=center >" .$rs1->fields[5] . "</td>";
        $datos_tiempo_con.= " <td class='fila' align=center>" .$rs1->fields[1] . "</td>";
        $datos_tiempo_con.= " <td class='fila' align=center>" .$rs1->fields[2] . "</td>";
        $datos_tiempo_con.= " <td class='fila' align=center>" .$rs1->fields[3] . ":" .$rs1->fields[4] . "</td>";
        $datos_tiempo_con.= "</tr>";
        $cuenta_sv=$cuenta_sv+1;
        $rs1->MoveNext();
    }
			
    $datos_tiempo_con.= "</table>";
    if($cuenta_sv>0){
        echo $datos_tiempo_con;
    }
			 
    $rs1->close();
    $rs1=null;   
    return $rpta;
}
	
	
function imprimir_eventos_abiertos(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
?>
    <img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
    <p>		  
<?php

    echo "<table id='idTabla' width=60% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTD>Tipo de evento abierto</td>";
    echo " \n<td align=center class=ColumnTD>Nro. de empleados</td>";
    echo "\n</tr>";
    // Total de eventos abiertos 			
    $ssql= "SELECT i.Incidencia_codigo,i.Incidencia_descripcion, COUNT(e.Empleado_Codigo) AS empleados  "; 
    $ssql.= " FROM CA_Eventos e (nolock) INNER JOIN CA_Asistencias a (nolock) ON  ";
    $ssql.= " e.Empleado_Codigo =  a.Empleado_Codigo AND e.Asistencia_codigo =  a.Asistencia_codigo INNER JOIN CA_Asistencia_Responsables r ON "; 
    $ssql.= " a.Empleado_Codigo =  r.Empleado_Codigo AND a.Asistencia_codigo =  r.Asistencia_codigo INNER JOIN CA_Incidencias i ON "; 
    $ssql.= " e.Incidencia_Codigo = i.Incidencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) and e.evento_activo = 1 ";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    $ssql.= " and a.asistencia_entrada is not null and a.ca_estado_codigo = 1 ";
    $ssql.= " GROUP BY i.Incidencia_codigo,  i.Incidencia_descripcion ";
    $ssql.= " order by 2 ";
    //echo $ssql;
		
    $rs = $cn->Execute($ssql);
    $i=0;		
    if(!$rs->EOF){
        while(!$rs->EOF){
            $i+=1;   
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td align=right class=DataTD>&nbsp;".$rs->fields[1]."</td>";
            $cadena .=" <td align=center class=DataTD><font style='cursor:hand' onclick=\"ver_detalle(". $rs->fields[0].")\"><u>".$rs->fields[2]."</u></font></td>";
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
        }
    }else{
            $lista = "<tr >\n";
            $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
            $lista .="&nbsp;No tiene datos de eventos abiertos\n";
            $lista .= "	   </td>\n";
            $lista .= "</tr>\n";
            echo $lista;	
    }
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}


function imprimir_eventos_cerrados(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
?>
    <img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
    <p>
<?php
    echo "<table id='idTabla' width=60% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTD>Tipo de evento cerrado</td>";
    echo " \n<td align=center class=ColumnTD>Nro. de empleados</td>";
    echo "\n</tr>";
    // Total de eventos cerrados
    $ssql= "SELECT i.Incidencia_codigo,i.Incidencia_descripcion, COUNT(e.Empleado_Codigo) AS empleados  ";
    $ssql.= " FROM CA_Eventos e (nolock) INNER JOIN CA_Asistencias a (nolock) ON  ";
    $ssql.= " e.Empleado_Codigo =  a.Empleado_Codigo AND e.Asistencia_codigo =  a.Asistencia_codigo INNER JOIN CA_Asistencia_Responsables r ON ";
    $ssql.= " a.Empleado_Codigo =  r.Empleado_Codigo AND a.Asistencia_codigo =  r.Asistencia_codigo INNER JOIN CA_Incidencias i ON ";
    $ssql.= " e.Incidencia_Codigo = i.Incidencia_codigo ";
    $ssql.= " where a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) and e.evento_activo = 0 ";
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    $ssql.= " and a.asistencia_entrada is not null and a.ca_estado_codigo = 1 ";
    $ssql.= " GROUP BY i.Incidencia_codigo,  i.Incidencia_descripcion ";
    $ssql.= " order by 2 ";
    //	echo $ssql;	
    $rs = $cn->Execute($ssql);
    $i=0;
    if(!$rs->EOF){
        while(!$rs->EOF){
            $i+=1;
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td align=right class=DataTD>&nbsp;" .$rs->fields[1] . "</td>";
            $cadena .=" <td align=center class=DataTD><font style='cursor:hand' onclick=\"ver_detalle(". $rs->fields[0] . ")\"><u>" . $rs->fields[2] . "</u></font></td>";
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
        }
    }else{
        $lista = "<tr >\n";
        $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
        $lista .="&nbsp;No tiene datos de eventos cerrados\n";
        $lista .= "	   </td>\n";
        $lista .= "</tr>\n";
        echo $lista;
    }
		
    $rs->close();
    $rs=null;
    echo "\n</table>";
    return $rpta;
}


function listar_posicion_tparcial(){
    $rpta="OK";
    $ssql = "";
    $cn=$this->getMyConexionADO();
?>
		  
    <img src="../images/excel_ico.GIF" style="cursor:hand" onclick="return exportarExcel('idTabla')" title='Exportar Excel'/>
    <p>
<?php
		  
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Fecha</td>";
    echo " \n<td align=center class=ColumnTD>Hora ing.</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo "\n</tr>";

    $ssql = " SELECT v.area_descripcion, v.empleado, convert(varchar(10),a.asistencia_fecha,103) as fecha,"; 
    //	$ssql.= " isnull(substring(cast(cast(a.Asistencia_entrada as smalldatetime) as char),13,7),'') as hora_ing,t.turno_descripcion,";
    $ssql.= " isnull(convert(varchar(10),a.Asistencia_entrada, 108),'') as hora_ing,t.turno_descripcion,";
    $ssql.= " DATEPART(hh, GETDATE()) * 60 + DATEPART(mi, GETDATE()) as hora_actual_min,";
    $ssql.= " t.turno_hora_fin * 60 + t.turno_minuto_fin as hora_salida_min,"; 
    $ssql.= " ((t.turno_hora_fin * 60 + t.turno_minuto_fin) - (DATEPART(hh, GETDATE()) * 60 + DATEPART(mi, GETDATE()))) as dif_salida_min "; 
    $ssql.= " FROM vDatos v (nolock) INNER JOIN CA_Asistencias a (nolock) ON ";
    $ssql.= " v.Empleado_Codigo = a.Empleado_Codigo INNER JOIN CA_Turnos t (nolock) ON "; 
    $ssql.= " a.turno_codigo = t.turno_codigo INNER JOIN CA_Asistencia_Responsables r (nolock) ON "; 
    $ssql.= " a.Empleado_Codigo = r.Empleado_Codigo and a.Asistencia_codigo =  r.Asistencia_codigo ";
    $ssql.= " WHERE v.modalidad_codigo = 77 "; 
    if ($this->area_codigo*1>0) $ssql.= " and a.area_codigo = " . $this->area_codigo;
    if ($this->responsable_codigo*1>0) $ssql.= " and r.responsable_codigo = " . $this->responsable_codigo;
    if ($this->turno_codigo*1>0) $ssql.= " and a.turno_codigo = ". $this->turno_codigo;
    $ssql.= " and a.asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) ";
    $ssql.= " AND a.Asistencia_entrada is not null AND a.Asistencia_salida is null ";
    $ssql.= " order by 1,2 ";

    $rs = $cn->Execute($ssql);
    $i=0;

    if(!$rs->EOF) {
        while(!$rs->EOF){
            $i+=1;
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td >" . $i . "&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[1] . "&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[2] . "&nbsp;</td>";
            $cadena .=" <td align=center>" .$rs->fields[3] . "</td>";

            if ($rs->fields[7] > 0 and $rs->fields[7] <= 15)  {
                $cadena .=" <td align=center style='background-color:green'>" .$rs->fields[4] . "</td>";	
            }elseif ($rs->fields[7] <= 0) {
                $cadena .=" <td align=center style='background-color:red'>" .$rs->fields[4] . "</td>";	  			     	
            } else {
                $cadena .=" <td align=center>".$rs->fields[4]."</td>";
            }	  
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
        }
    }else{
        $lista = "<tr >\n";
        $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
        $lista .="&nbsp;No se tienen datos para esta selección\n";
        $lista .= "	   </td>\n";
        $lista .= "</tr>\n";
        echo $lista;
    }
			
    $rs->close();
    $rs=null;
    echo "\n</table>";	   
    return $rpta;
}
	
function listar_acumulado_tparcial(){
    $rpta="OK";
    $ssql = "";
    $lista="";
    $cn=$this->getMyConexionADO();
    echo "<P><P>";
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>DNI</td>";
    echo " \n<td align=center class=ColumnTD>Semana</td>";
    echo " \n<td align=center class=ColumnTD>Del</td>";
    echo " \n<td align=center class=ColumnTD>Al</td>";
    echo " \n<td align=center class=ColumnTD>Total horas</td>";
    echo " \n<td align=center class=ColumnTD>Exceso horas</td>";
    echo "\n</tr>";
		
    $ssql="exec spCA_Consulta_Asistencias_Partime_Semanal '" . $this->anio_codigo ."', '" . $this->mes_codigo . "', " . $this->area_codigo . ", " . $this->responsable_codigo . "";
    //     echo $ssql;
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $i+=1;
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td >" . $i . "&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[0]."&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[1]."&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[2]."&nbsp;</td>";
            $cadena .=" <td align=center>" .$rs->fields[3]."</td>";
            $cadena .=" <td >" .$rs->fields[4]."</td>";
            $cadena .=" <td >" .$rs->fields[5]."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[6],2)."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[7],2)."</td>";
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
        }
    }else{
        $lista = "<tr >\n";
        $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
        $lista .="&nbsp;No se tienen datos para esta selección\n";
        $lista .= "	   </td>\n";
        $lista .= "</tr>\n";
        echo $lista;			
    }
    
    $rs->close();
    $rs=null;  
    echo "\n</table>";
    return $rpta;
}

function listar_acumulado_general(){
    $rpta="OK";
    $ssql = "";
    $lista="";
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    $i = 0;
    echo "<P><P>";
    echo "<table id='idTabla' width=95% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTDb width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Area</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>DNI</td>";
    echo " \n<td align=center class=ColumnTD>Semana</td>";
    echo " \n<td align=center class=ColumnTD>Del</td>";
    echo " \n<td align=center class=ColumnTD>Al</td>";
    echo " \n<td align=center class=ColumnTD>Hr.Prog.</td>";
    echo " \n<td align=center class=ColumnTD>Hr.Ejec.</td>";
    echo " \n<td align=center class=ColumnTD>Hr.Refr.</td>";
    echo " \n<td align=center class=ColumnTD>Tot Hr.Efec</td>";
    echo " \n<td align=center class=ColumnTD>Exceso horas</td>";
    echo "\n</tr>";
		
    $ssql="exec spCA_Consulta_Asistencias_General_Semanal '" . $this->anio_codigo ."', '" . $this->mes_codigo . "', " . $this->area_codigo . ", " . $this->responsable_codigo . "";
    //     echo $ssql;
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $i+=1;
            $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .=" <td >" . $i . "&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[0]."&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[1]."&nbsp;</td>";
            $cadena .=" <td >" . $rs->fields[2]."&nbsp;</td>";
            $cadena .=" <td align=center>" .$rs->fields[3]."</td>";
            $cadena .=" <td >" .$rs->fields[4]."</td>";
            $cadena .=" <td >" .$rs->fields[5]."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[6],2)."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[7],2)."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[8],2)."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[9],2)."</td>";
            $cadena .=" <td align=center>" . number_format($rs->fields[10],2)."</td>";
            $cadena .="</tr>";
            echo $cadena;
            $rs->MoveNext();
        }
    }else{
        $lista = "<tr >\n";
        $lista .="     <td align='center' class='Ca_DataTD' colspan='4'>\n";
        $lista .="&nbsp;No se tienen datos para esta selección\n";
        $lista .= "	   </td>\n";
        $lista .= "</tr>\n";
        echo $lista;			
    }
    
    //$rs->close();
    $rs=null;  
    echo "\n</table>";
    return $rpta;
}
    
function listar_marcaciones_mensuales(){
    $rpta="OK";
    $ssql = "";
    $lista="";
    $cn=$this->getMyConexionADO();
    $i=0;
    echo "<P><P>";
    echo "<table id='idTabla' width=99% border=1 cellspacing=0 cellpadding=0 align=center style=font-size:12px class=FormTABLE>";
    echo "\n<tr>";
    echo " \n<td align=center class=ColumnTD width=30px>Nro.</td>";
    echo " \n<td align=center class=ColumnTD>Empleado</td>";
    echo " \n<td align=center class=ColumnTD>Fecha</td>";
    echo " \n<td align=center class=ColumnTD>Hr_Entrada</td>";
    echo " \n<td align=center class=ColumnTD>Hr_Salida</td>";
    echo " \n<td align=center class=ColumnTD>Turno</td>";
    echo " \n<td align=center class=ColumnTD>IP_Entrada</td>";
    echo " \n<td align=center class=ColumnTD>IP_Salida</td>";
    echo " \n<td align=center class=ColumnTD>Total_Horas</td>";
    echo " \n<td align=center class=ColumnTD>Refrigerio</td>";
    echo " \n<td align=center class=ColumnTD>Hr_Efectivas</td>";
    echo "\n</tr>";
            
$ssql = " SELECT
            e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres AS Empleado,
            convert(varchar(10),Asistencia_fecha,103) as fecha ,
            isnull(convert(varchar(10),asistencia_entrada, 108),'') as entrada,
            isnull(convert(varchar(10),asistencia_salida, 108),'') as salida,
            t.turno_descripcion,
            ip_entrada,
            ip_salida, 

                                    isnull(convert(varchar(8),asistencia_salida-asistencia_entrada, 108),'') as thoras,
                                    isnull(t.turno_refrigerio,0) as refri, 
                                    case when asistencia_salida-asistencia_entrada<
                                    (dateadd(mi,isnull(t.turno_refrigerio,0),convert(varchar(10),asistencia_salida-asistencia_entrada,102)))
                                    then ' ' else
                                    isnull( convert(varchar(10),(asistencia_salida-asistencia_entrada) - 
                                    (dateadd(mi,isnull(t.turno_refrigerio,0),convert(varchar(10),asistencia_salida-asistencia_entrada,102))),108), '')
                                    end as efectivo,
                                    ( SELECT top 1  i.Incidencia_descripcion AS incidencia
FROM CA_Asistencia_Incidencias ai (nolock) 
INNER JOIN CA_Incidencias i (nolock) ON 
ai.Incidencia_codigo = i.Incidencia_codigo 
WHERE empleado_codigo = a.empleado_codigo
and ai.asistencia_codigo = a.asistencia_codigo
and i.incidencia_hh_dd=0)


          FROM 
            ca_asistencias a (nolock) 
            left outer join ca_turnos t (nolock) on t.turno_codigo=a.turno_codigo 
            left outer join empleados e (nolock) on a.empleado_codigo = e.empleado_codigo
          WHERE
            a.empleado_codigo = ".$this->empleado_sel. " and 
            a.area_codigo = ".$this->area_codigo. " and 
            (a.Asistencia_fecha >= CONVERT(datetime, '1/' + '".$this->mes_codigo."' +'/'+ '".$this->anio_codigo."', 103) and 
             a.Asistencia_fecha < DATEADD(MONTH,1, CONVERT(datetime, '1/' + '".$this->mes_codigo."' +'/'+ '".$this->anio_codigo."', 103))) 
                   AND (a.CA_Estado_Codigo = 1) ";
        //echo $ssql;
        //        $ssql = " SELECT asistencia_codigo, convert(varchar(10),Asistencia_fecha,103) as fecha ,isnull(convert(varchar(10),asistencia_entrada, 108),'') as entrada,"; 
        //		$ssql.= " isnull(convert(varchar(10),asistencia_salida, 108),'') as salida,t.turno_descripcion,t.turno_codigo,ip_entrada,ip_salida ";
        //		$ssql.= " FROM ca_asistencias a (nolock) ";
        //		$ssql.= " left outer join ca_turnos t (nolock) on t.turno_codigo=a.turno_codigo ";
        //        $ssql.= " left outer join empleados e (nolock) on a.empleado_codigo = e.empleado_codigo ";
        //		$ssql.= " WHERE asistencia_fecha = convert(datetime,'" . $this->fecha . "',103) and empleado_codigo = " . $this->lista_sel . " ";
        //		$ssql.= " AND ca_estado_codigo=1 ";
        //		$ssql.= " Order by asistencia_entrada desc ";

        //     $ssql="spCA_Consulta_Asistencias_Partime_Semanal '" . $this->anio_codigo ."', '" . $this->mes_codigo . "', " . $this->area_codigo . ", " . $this->responsable_codigo . "";
        //     echo $ssql;
            $rs = $cn->Execute($ssql);
            if(!$rs->EOF) {
                while(!$rs->EOF){
                    $i+=1;
                    $cadena ="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
                    $cadena .=" <td >" . $i . "&nbsp;</td>";
                    $cadena .=" <td >" . $rs->fields[0] . "&nbsp;</td>";
                    //$cadena .=" <td align=left class=DataTD><font style='cursor:hand' onclick=\"ver_detalle(". $rs->fields[0]->value . ")\"><u>Dia</u></font></td>";
                    $cadena .=" <td align=center><font style='cursor:hand' onclick=\"ver_detalle('". $rs->fields[1] . "')\"><u>" . $rs->fields[1] . "</u></font>&nbsp;</td>";
                    if ($rs->fields[2] == '' && $rs->fields[3] == ''){
                        $cadena .=" <td align=center>" . $rs->fields[10] . "&nbsp;</td>";
                        $cadena .=" <td align=center>&nbsp;</td>";
                    }else{
                        $cadena .=" <td align=center>" . $rs->fields[2] . "&nbsp;</td>";
                        $cadena .=" <td align=center>" . $rs->fields[3] . "</td>";
                    }
                    $cadena .=" <td align=center>" . $rs->fields[4]."</td>";
                    $cadena .=" <td align=center>" . $rs->fields[5]."</td>";
                    $cadena .=" <td align=center>" . $rs->fields[6]."</td>";
                    $cadena .=" <td align=center>" . $rs->fields[7]."</td>";
                    $cadena .=" <td align=center>" . $rs->fields[8]."</td>";
                    $cadena .=" <td align=center>" . $rs->fields[9]."</td>";
                    $cadena .="</tr>";
                    echo $cadena;
                  $rs->MoveNext();
            	}
            }else{
                $lista = "<tr >\n";
                $lista .="     <td align='center' class='Ca_DataTD' colspan='11'>\n";
                $lista .="&nbsp;No se tienen datos para esta selección\n";
                $lista .= "	   </td>\n";
                $lista .= "</tr>\n";			

                //echo $lista;
            }
            
            $rs->close();
            $rs=null;
            echo "\n</table>";
            return $rpta;
}



}
?>
