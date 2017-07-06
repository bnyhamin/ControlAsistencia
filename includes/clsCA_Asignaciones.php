<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_asignaciones extends mantenimiento{
var $asignacion_codigo=0;
var $responsable_codigo=0;
var $empleado_codigo=0;
var $empleado_codigo_asigna=0;
var $asignacion_activo ="";

function Addnew(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($rpta=="OK"){
    	$ssql ="select responsable_codigo from ca_asignaciones(nolock) ";
    	$ssql .="where responsable_codigo=". $this->responsable_codigo ." and asignacion_activo=1";
    	$rs = $cn->Execute($ssql);
    	if($rs->EOF){//si no existe, insertar responsable
    	  $params = array(
                        $this->responsable_codigo,
                        $this->empleado_codigo_asigna
                    );
    	  $ssql =" insert into ca_asignaciones(responsable_codigo,empleado_codigo_asigna,asignacion_activo, fecha_reg) ";
          $ssql .=" values(?,?,1, getdate())";
	      $rs=$cn->Execute($ssql, $params);
    	  if(!$rs){
    		  $rpta = "Error al Insertar responsable.";
    		  return $rpta;
    	  }else{
    		  $rpta= "OK";
    	   }
    	}
	}
	return $rpta;
  }

function  empleados_area_responsabilidad($areas_subordinadas,$cargo_codigo,$nombre,$tipo){
$cadena="";
$rpta="OK";
// print_r(array($areas_subordinadas,$cargo_codigo,$nombre,$tipo));die();
$cn = $this->getMyConexionADO();
if($cn){
	$ssql="SELECT Empleados.Empleado_Codigo, Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno     + ' ' + Empleados.Empleado_Nombres AS empleado, ";
	$ssql.= " 	vw_empleado_area_cargo.area_descripcion,vw_empleado_area_cargo.Cargo_Descripcion, ca_ae.responsable_codigo, ca_ae.Responsable";
	$ssql.= " FROM Empleado_Area(nolock) INNER JOIN Empleados(nolock) ON ";
	$ssql.= " 	Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo INNER JOIN vw_empleado_area_cargo(nolock) ON ";
	$ssql.= " 	vw_empleado_area_cargo.Empleado_Codigo = Empleados.Empleado_Codigo  LEFT OUTER JOIN";
	$ssql.= " 		(SELECT a.empleado_codigo, a.responsable_codigo, e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres AS Responsable, a.asignacion_activo";
	$ssql.= " 	  	FROM CA_Asignacion_Empleados a INNER JOIN empleados e ON  a.responsable_codigo = e.empleado_codigo";
	$ssql.= " 		WHERE asignacion_activo = 1) ca_ae ON ";
	$ssql.= " 		Empleados.Empleado_Codigo = ca_ae.empleado_codigo";
	$ssql.= " WHERE Empleados.Estado_Codigo = 1 AND ";
	$ssql.= " 	Empleado_Area.Empleado_Area_Activo = 1 AND ";
	if($tipo ==1 ) $ssql.=  " Empleado_Area.Area_Codigo in (" . $areas_subordinadas .")";
	if($cargo_codigo !=0) $ssql .= " and vw_empleado_area_cargo.cargo_codigo=" . $cargo_codigo;
	if($nombre!='') $ssql .= " and Empleados.Empleado_Apellido_Paterno + ' '  + Empleados.Empleado_Apellido_Materno  + ' '  +   Empleados.Empleado_Nombres like '%" . $nombre . "%'";
	$ssql.= " order by 2 ";
	//echo $ssql;
	$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
		  $i=0;
		  while(!$rs->EOF){
            $i+=1;
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td  nowrap>&nbsp;&nbsp;";
			//if ($rs->fields[4]->value ==0){ //si no tiene asignado responsable es seleccionable
				$cadena .="<input type='checkbox' id='chk" . $rs->fields[0]  . "' name='chk" . $rs->fields[0]  . "' value='" . $rs->fields[0] . "'>";
			//}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs->fields[0]  . "&nbsp;</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[1] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[2] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[3] ;
			$cadena .="</td>";
			$cadena .="<td align='center' >&nbsp";
			  if ($rs->fields[4] !=0){ //si tiene asignado responsable
				 //$cadena .="<img border='0' src='../../Images/asistencia/invite.gif'  onclick='ver_supervisor(".$rs->fields[4] .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
				 $cadena .=$rs->fields[5];
			  }else{
				 $cadena .="No Asignado";
			  }
			$cadena .="</td>";
/*			$cadena .="<td align='center' >&nbsp";
			  if ($rs->fields[4]->value !=0){ //si tiene asignado responsable
				 $cadena .="<img border='0' src='../../Images/asistencia/delete_small.gif'  onclick='Quitar(". $rs->fields[0]->value .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
			  }else{
				 $cadena .="";
			  }
			$cadena .="</td>";
*/
			$cadena .=
            "</tr>";
			$rs->MoveNext();
	      }
       }
 return $cadena;
 }
}

function  empleados_rac($areas_subordinadas,$cargo_codigo,$nombre,$tipo){
$cadena="";
$rpta="OK";
$cn = $this->getMyConexionADO();
//$cn->debug=true;
if($cn){
	$ssql="SELECT Empleados.Empleado_Codigo, Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno     + ' ' + Empleados.Empleado_Nombres AS empleado, ";
	$ssql.= " 	vw_empleado_area_cargo.area_descripcion,vw_empleado_area_cargo.Cargo_Descripcion, ca_ae.responsable_codigo, ca_ae.Responsable";
	$ssql.= " FROM Empleado_Area(nolock) INNER JOIN Empleados(nolock) ON ";
	$ssql.= " 	Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo INNER JOIN vw_empleado_area_cargo(nolock) ON ";
	$ssql.= " 	vw_empleado_area_cargo.Empleado_Codigo = Empleados.Empleado_Codigo  LEFT OUTER JOIN";
	$ssql.= " 		(SELECT a.empleado_codigo, a.responsable_codigo, e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres AS Responsable, a.asignacion_activo";
	$ssql.= " 	  	FROM CA_Asignacion_Empleados a INNER JOIN empleados e ON  a.responsable_codigo = e.empleado_codigo";
	$ssql.= " 		WHERE asignacion_activo = 1) ca_ae ON ";
	$ssql.= " 		Empleados.Empleado_Codigo = ca_ae.empleado_codigo";
	$ssql.= " 		inner join Grupos_Cargos gc on vw_empleado_area_cargo.Cargo_Codigo=gc.Cargo_Codigo "; 
	$ssql.= " WHERE Empleados.Estado_Codigo = 1 AND ";
	$ssql.= " 	Empleado_Area.Empleado_Area_Activo = 1 and gc.Grupo_O_Codigo=2	and gc.Grupo_Cargo_Activo=1 AND ";
    $ssql.= " 	 vw_empleado_area_cargo.Cargo_Codigo in (754,633) AND  ";
	if($tipo ==1 ) $ssql.=  " Empleado_Area.Area_Codigo in (" . $areas_subordinadas .")";
	if($cargo_codigo !=0) $ssql .= " and vw_empleado_area_cargo.cargo_codigo=" . $cargo_codigo;
	if($nombre!='') $ssql .= " and Empleados.Empleado_Apellido_Paterno + ' '  + Empleados.Empleado_Apellido_Materno  + ' '  +   Empleados.Empleado_Nombres like '%" . $nombre . "%'";
	$ssql.= " order by 2 ";
	//echo $ssql;
	$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
		  $i=0;
		  while(!$rs->EOF){
            $i+=1;
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td  nowrap>&nbsp;&nbsp;";
			//if ($rs->fields[4]->value ==0){ //si no tiene asignado responsable es seleccionable
				$cadena .="<input type='checkbox' id='chk" . $rs->fields[0]  . "' name='chk" . $rs->fields[0]  . "' value='" . $rs->fields[0] . "'>";
			//}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs->fields[0]  . "&nbsp;</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[1] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[2] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[3] ;
			$cadena .="</td>";
			$cadena .="<td align='center' >&nbsp";
			  if ($rs->fields[4] !=0){ //si tiene asignado responsable
				 //$cadena .="<img border='0' src='../../Images/asistencia/invite.gif'  onclick='ver_supervisor(".$rs->fields[4] .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
				 $cadena .=$rs->fields[5];
			  }else{
				 $cadena .="No Asignado";
			  }
			$cadena .="</td>";
/*			$cadena .="<td align='center' >&nbsp";
			  if ($rs->fields[4]->value !=0){ //si tiene asignado responsable
				 $cadena .="<img border='0' src='../../Images/asistencia/delete_small.gif'  onclick='Quitar(". $rs->fields[0]->value .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
			  }else{
				 $cadena .="";
			  }
			$cadena .="</td>";
*/
			$cadena .=
            "</tr>";
			$rs->MoveNext();
	      }
       }
 return $cadena;
 }
}

function empleados_para_equipo($area,$cargo_codigo,$nombre,$tipo){
$cadena="";
$rpta="OK";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){
	$ssql="SELECT Empleados.Empleado_Codigo, Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno     + ' ' + Empleados.Empleado_Nombres AS empleado, ";
	$ssql.= " 	vw_empleado_area_cargo.area_descripcion,vw_empleado_area_cargo.Cargo_Descripcion, ca_ae.responsable_codigo, ca_ae.Responsable";
	$ssql.= " FROM Empleado_Area(nolock) INNER JOIN Empleados(nolock) ON ";
	$ssql.= " 	Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo INNER JOIN vw_empleado_area_cargo(nolock) ON ";
	$ssql.= " 	vw_empleado_area_cargo.Empleado_Codigo = Empleados.Empleado_Codigo  LEFT OUTER JOIN";
	$ssql.= " 		(SELECT a.empleado_codigo, a.responsable_codigo, e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres AS Responsable, a.asignacion_activo";
	$ssql.= " 	  	FROM CA_Asignacion_Empleados a INNER JOIN empleados e ON  a.responsable_codigo = e.empleado_codigo";
	$ssql.= " 		WHERE asignacion_activo = 1) ca_ae ON ";
	$ssql.= " 		Empleados.Empleado_Codigo = ca_ae.empleado_codigo";
	$ssql.= " WHERE Empleados.Estado_Codigo = 1 AND ";
	$ssql.= " 	Empleado_Area.Empleado_Area_Activo = 1 AND ";
	//$ssql.= " 	Empleado_Area.Area_Codigo = " . $area . " AND ";
	if($tipo ==1 ) $ssql.=  " Empleado_Area.Area_Codigo=" . $area ."";
	if($tipo ==2 ) $ssql.=  " Empleado_Area.Area_Codigo<>" . $area ."";
	/*
	$ssql.= " 	Empleados.Empleado_Codigo NOT IN";
	$ssql.= " 		(SELECT ca_empleado_rol.empleado_codigo";
	$ssql.= " 		  FROM ca_empleado_rol";
	$ssql.= " 		  WHERE empleado_rol_activo = 1)";*/
	if($cargo_codigo !=0) $ssql .= " and vw_empleado_area_cargo.cargo_codigo=" . $cargo_codigo;
	if($nombre!='') $ssql .= " and Empleados.Empleado_Apellido_Paterno + ' '  + Empleados.Empleado_Apellido_Materno  + ' '  +   Empleados.Empleado_Nombres like '%" . $nombre . "%'";
	$ssql.= " order by 2 ";
	//echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
		if(!$rs->EOF()) {
		  $i=0;
		  while(!$rs->EOF()){
            $i+=1;
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td  nowrap>&nbsp;&nbsp;";
				if ($rs->fields[4]->value ==0){ //si no tiene asignado responsable es seleccionable
					$cadena .="<input type='checkbox' id='chk" . $rs->fields[0]->value  . "' name='chk" . $rs->fields[0]->value  . "' value='" . $rs->fields[0]->value . "'>";
				}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs->fields[0]->value  . "&nbsp;</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[1]->value ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[2]->value ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[3]->value ;
			$cadena .="</td>";
			$cadena .="<td align='center' >&nbsp";
			  if ($rs->fields[4]->value !=0){ //si tiene asignado responsable
				 $cadena .="<img border='0' src='../../Images/asistencia/invite.gif'  onclick='ver_supervisor(".$rs->fields[4]->value .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
			  }else{
				 $cadena .="No Asignado";
			  }
			$cadena .="</td>";
			$cadena .="</tr>";
			$rs->movenext();
	      }
       }
 return $cadena;
 }
}

function empleados_para_supervisor($area,$cargo_codigo,$nombre){
$cadena="";
$rpta="OK";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){
	$ssql="SELECT vca_Empleado_Area.Empleado_Codigo, vca_Empleado_Area.empleado, ";
	$ssql.= " 	vw_empleado_area_cargo.Cargo_Descripcion";
	$ssql.= " FROM vca_Empleado_Area(nolock) ";
	$ssql.= " 	INNER JOIN vw_empleado_area_cargo(nolock) ON ";
	$ssql.= " 	vw_empleado_area_cargo.Empleado_Codigo = vca_Empleado_Area.Empleado_Codigo ";
	$ssql.= "   WHERE Empleados.Estado_Codigo = 1 AND ";
	$ssql.= " 	vCA_Empleado_Area.Empleado_Area_Activo = 1 AND ";
	$ssql.= " 	vCA_Empleado_Area.Area_Codigo = " . $area . " AND ";
	$ssql.= " 	vCA_Empleados_Area.Empleado_Codigo NOT IN ";
	$ssql.= "   (select empleado_codigo from vca_responsable_area(nolock) ";
	$ssql.= "   where rol_codigo=1 and area_codigo=" . $area . " and empleado_rol_activo=1 ";
	$ssql.= "   and estado_codigo=1 )";
	if($cargo_codigo !=0) $ssql .= " and  vw_empleado_area_cargo.cargo_codigo=" . $cargo_codigo;
	if($nombre!='') $ssql .= " and vca_Empleado_Area.empleado like '%" . $nombre . "%'";
	$ssql.= " order by 2 ";

	$rs = $this->cnnado->Execute($ssql);
		if(!$rs->EOF()) {
		  $i=0;
		  while(!$rs->EOF()){
            $i+=1;
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td  nowrap>&nbsp;&nbsp;";
				if ($rs->fields[3]->value ==0){ //si no tiene asignado responsable es seleccionable
					$cadena .="<input type='checkbox' id='chk" . $rs->fields[0]->value  . "' name='chk" . $rs->fields[0]->value  . "' value='" . $rs->fields[0]->value . "'>";
				}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs->fields[0]->value  . "&nbsp;</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[1]->value ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[2]->value ;
			$cadena .="</td>";
			$cadena .="</tr>";
			$rs->movenext();
	      }
       }
 return $cadena;
 }
}



}
?>
