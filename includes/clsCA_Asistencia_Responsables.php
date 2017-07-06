<?php 
//require_once(PathIncludes() . "mantenimiento.php");
class ca_asistencia_responsables extends mantenimiento{
var $empleado_codigo='';
var $asistencia_codigo='';
var $responsable_codigo='';
var $fecha='';
var $responsable_asistencia='';
var $area_codigo_responsable="";
var $area_codigo='';
var $codigo='';

function Mostrar_Responsables(){//-- listar los campos del modulo solicitado
	$rpta='';
	$titulo='';
	$ssql ='';
 	$rpta=$this->conectarme_ado();
    if($rpta=="OK"){
	    if ($this->responsable_asistencia=='1'){ //-- Responsable de mi area
	    	$ssql=  "select vca_empleado_area.empleado_codigo as codigo, Empleado  ";
			$ssql.=  " from vca_empleado_area ";
			$ssql.=  " inner join ca_empleado_rol on vca_empleado_area.empleado_codigo=ca_empleado_rol.empleado_codigo";
			$ssql.=  " where Estado_Codigo=1 and area_codigo=" . $this->area_codigo ." and rol_codigo=1";
			if($this->asistencia_codigo !=0 ){ 
			    $ssql .=" and vca_empleado_area.empleado_codigo not in (select responsable_codigo from ";
				$ssql.= " ca_asistencia_responsables ";
	        	$ssql.= " where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo . ")";
			}
			$ssql.=  " and vca_empleado_area.empleado_codigo <> " . $this->responsable_codigo . "";
			$ssql.=  " Order by 2 ";
		}else{ //-- responsable de otra area
			$ssql=  "select Responsable_codigo as codigo, Empleado ";
			$ssql.=  " from vca_empleado_responsables ";
			$ssql.=  " where Estado_Codigo=1 and area_codigo<>" . $this->area_codigo ."";
			if($this->asistencia_codigo !=0 ){ 
			    $ssql .=" and responsable_codigo not in (select responsable_codigo from ";
				$ssql.= " ca_asistencia_responsables ";
	        	$ssql.= " where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo . ")";
			}
			$ssql.=  " and responsable_codigo <> " . $this->responsable_codigo . "";
			$ssql.=  " Order by 2 ";
		}
	    $rs = $this->cnnado->Execute($ssql);
	    if(!$rs->EOF()) {
		  echo "<script language='javascript' >";
	      while (!$rs->EOF()){
		  	if ($rs->fields['codigo']->value!='')	$titulo = $rs->fields['Empleado']->value;
		  	echo "window.parent.agregarItem('" . $rs->fields['codigo']->value . "', '" . $titulo . "');";
		  	$rs->movenext();
		  }
		  echo "</script>";
	    }
	    $rs->close();
	    $rs=null;
    }
	return $rpta;
}

function registrar_responsable_asistencia(){
    
    
 /*$rpta="";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
            $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
			$sql =" insert into ca_asistencia_responsables ";
			$sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,Area_Codigo_Responsable,fecha_reg) ";
			$sql .=" select " . $this->empleado_codigo . ",empleados.empleado_codigo," . $this->asistencia_codigo . ",ea.area_codigo,getdate() ";
			$sql .=" from empleados inner join empleado_area ea on empleados.empleado_codigo = ea.empleado_codigo";
			$sql .=" where empleados.empleado_codigo=? and ea.empleado_area_activo=1";
	
			$cmd->ActiveConnection = $this->cnnado;
			$cmd->CommandText = $sql;
    		$cmd->Parameters[0]->value = $this->responsable_codigo;
			$r=$cmd->Execute();
	    	if(!$r){
		  		$rpta = "Error al registrar responsable.";
		  	   return $rpta;
			}else{
		     $rpta= "OK";
		   }
		   $cmd=null;
	}		  
 return $rpta;*/
    $rpta="";
    $cn=$this->getMyConexionADO();
    $sql =" insert into ca_asistencia_responsables ";
    $sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,Area_Codigo_Responsable,fecha_reg) ";
    $sql .=" select " . $this->empleado_codigo . ",empleados.empleado_codigo," . $this->asistencia_codigo . ",ea.area_codigo,getdate() ";
    $sql .=" from empleados inner join empleado_area ea on empleados.empleado_codigo = ea.empleado_codigo";
    $sql .=" where empleados.empleado_codigo=? and ea.empleado_area_activo=1";
    $params=array(
        $this->responsable_codigo
    );
    $r=$cn->Execute($sql,$params);
    
    if(!$r){
        $rpta = "Error al registrar responsable.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    return $rpta;
    
 }
 
function validar_responsable_asistencia(){
    
    $codigo=0;
    $rpta="OK";
    $cn=$this->getMyConexionADO();
	
    $ssql="SELECT responsable_codigo ";
    $ssql .=" FROM CA_Asistencia_Responsables ";
    $ssql .=" WHERE Empleado_Codigo = " . $this->empleado_codigo  . " AND Asistencia_codigo=" . $this->asistencia_codigo; 
    $ssql .=" AND responsable_codigo=" . $this->responsable_codigo; 
	
    $rs = $cn->Execute($ssql);
    
    if (!$rs->EOF){
        $codigo=1;
    }else{
       $codigo=0;
    }
    $rs->close();
    $rs=null;
    return $codigo;
}
 

function Listar_responsables_asistencia(){
    
    /*
$lista="";
$rpta="OK";

$obj=new ca_asistencia_incidencias();
$obj->MyUrl = $this->getMyUrl();
$obj->MyUser= $this->getMyUser();
$obj->MyPwd = $this->getMyPwd();
$obj->MyDBName= $this->getMyDBName();

$rpta=$this->conectarme_ado();
if($rpta=="OK"){	
			
	$ssql="SELECT responsable_codigo,responsable, ";
	$ssql .=" case when exists(select * from ca_asistencia_incidencias ";
    $ssql .="      where empleado_codigo=" . $this->empleado_codigo ." and asistencia_codigo=" . $this->asistencia_codigo ."";
	$ssql .="          and responsable_codigo=vwca_asistencias.responsable_codigo)";
    $ssql .="      then 1 else 0 end as inc ";
	$ssql.=  " FROM vwca_asistencias ";
	$ssql.=  " WHERE (empleado_Codigo = " . $this->empleado_codigo .") and ";
	$ssql.=  " (Asistencia_Codigo = " . $this->asistencia_codigo .") ";
	$ssql.=  " Order by 2";
	        $lista .="<tr>\n";
			$lista .="  <td  width='30%' class='CA_DataTD' align='left' colspan='4'>\n";
		    $lista .= "    <table width='100%' align='center' border='0' cellspacing='1'>\n";	
			$lista .= "      <tr>\n";
			$lista .= "        <td  class='ColumnTD' align='center' colspan='3'>\n";
			$lista .= "           <b>Responsables</b>&nbsp;<img onClick='Agregar_onclick(".$this->asistencia_codigo.")' src='../Images/add_buddy_small.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Agregar'>";
			$lista .= "         </td>\n";
			$lista .= "     </tr>\n";
		    $lista .= "    </table>\n";	
	//echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
	if(!$rs->EOF()) {
		while(!$rs->EOF()){
			$lista .= "<table class='ColumnTD' width='100%' align='center' border='1'>\n";	
			$lista .=" <tr>\n";
			$lista .="     <td  width='60%' class='CA1_DataTD' align='left'>\n";
		    $lista .=" <b>" . $rs->fields[1]->value. "</b>&nbsp;&nbsp;\n";
			$lista .="        <img onClick='Quitar_onclick(" . $this->asistencia_codigo .",".$rs->fields[0]->value .",".$rs->fields[2]->value.")' src='../Images/delete_small.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar'>";
			$lista .="	   </td>\n";
			$lista .= " </tr>\n";
			$lista .= " <tr>\n";
			$lista .= "   <td  class='DataTD' align='left' colspan='2'>\n";
			$obj->empleado_codigo=$this->empleado_codigo;
			$obj->asistencia_codigo=$this->asistencia_codigo;
			$obj->responsable_codigo=$rs->fields[0]->value; //responsables de la asistencia
			$lista .=$obj->Listar_incidencias_asistencia($this->responsable_codigo);     
			$lista .= "   </td>\n";
			$lista .= " </tr>\n";
			$lista .= "</table>\n";
			$lista .= "<br>\n";
		    $rs->movenext();
		}
		$lista .="  </td>\n";
		$lista .="</tr>\n";
	  }//else{ //Es un registro de asistencia de incidencias programadas
		//$lista .="<tr>\n";
		//$lista .="  <td>\n";
		//$lista .="   &nbsp;";              
		//$lista .="  </td>\n";
		//$lista .="</tr>\n";
		//$lista .="<tr>\n";
		//$lista .="  <td>\n";
		    //$obj->empleado_codigo=$this->empleado_codigo;
			//$obj->asistencia_codigo=$this->asistencia_codigo;
			//$lista .=$obj->Listar_incidencia_programada();     
		//$lista .="  </td>\n";
		//$lista .="</tr>\n";
	  //}
	}  
    $rs->close();
	$rs=null;
	$obj=null;	
	
	return $lista;
        */
        
    $lista="";
    $rpta="OK";
    $obj=new ca_asistencia_incidencias();
    $obj->setMyUrl($this->getMyUrl());
    $obj->setMyUser($this->getMyUser());
    $obj->setMyPwd($this->getMyPwd());
    $obj->setMyDBName($this->getMyDBName());
    $cn=$this->getMyConexionADO();
			
    $ssql="SELECT responsable_codigo,responsable, ";
    $ssql .=" case when exists(select * from ca_asistencia_incidencias ";
    $ssql .="      where empleado_codigo=" . $this->empleado_codigo ." and asistencia_codigo=" . $this->asistencia_codigo ."";
    $ssql .="          and responsable_codigo=vwca_asistencias.responsable_codigo)";
    $ssql .="      then 1 else 0 end as inc ";
    $ssql.=  " FROM vwca_asistencias ";
    $ssql.=  " WHERE (empleado_Codigo = " . $this->empleado_codigo .") and ";
    $ssql.=  " (Asistencia_Codigo = " . $this->asistencia_codigo .") ";
    $ssql.=  " Order by 2";
    
    $lista .="<tr>\n";
    $lista .="  <td  width='30%' class='CA_DataTD' align='left' colspan='4'>\n";
    $lista .= "    <table width='100%' align='center' border='0' cellspacing='1'>\n";	
    $lista .= "      <tr>\n";
    $lista .= "        <td  class='ColumnTD' align='center' colspan='3'>\n";
    //$lista .= "           <b>Responsables</b>&nbsp;<img onClick='Agregar_onclick(".$this->asistencia_codigo.")' src='../Images/add_buddy_small.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Agregar'>";
    $lista .= "           <b>Responsables</b>&nbsp;";
    $lista .= "         </td>\n";
    $lista .= "     </tr>\n";
    $lista .= "    </table>\n";	
	
    $rs = $cn->Execute($ssql);
        
    if(!$rs->EOF) {
        while(!$rs->EOF){
            $lista .= "<table class='ColumnTD' width='100%' align='center' border='1'>\n";	
            $lista .=" <tr>\n";
            $lista .="     <td  width='60%' class='CA1_DataTD' align='left'>\n";
            $lista .=" <b>" . $rs->fields[1]. "</b>&nbsp;&nbsp;\n";
            //$lista .="        <img onClick='Quitar_onclick(" . $this->asistencia_codigo .",".$rs->fields[0] .",".$rs->fields[2].")' src='../Images/delete_small.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar'>";
            $lista .="	   </td>\n";
            $lista .= " </tr>\n";
            $lista .= " <tr>\n";
            $lista .= "   <td  class='DataTD' align='left' colspan='2'>\n";
            $obj->empleado_codigo=$this->empleado_codigo;
            $obj->asistencia_codigo=$this->asistencia_codigo;
            $obj->responsable_codigo=$rs->fields[0]; //responsables de la asistencia
            $lista .=$obj->Listar_incidencias_asistencia($this->responsable_codigo);     
            $lista .= "   </td>\n";
            $lista .= " </tr>\n";
            $lista .= "</table>\n";
            $lista .= "<br>\n";
            $rs->MoveNext();
        }
        $lista .="  </td>\n";
        $lista .="</tr>\n";
      }//else{ //Es un registro de asistencia de incidencias programadas
            //$lista .="<tr>\n";
            //$lista .="  <td>\n";
            //$lista .="   &nbsp;";              
            //$lista .="  </td>\n";
            //$lista .="</tr>\n";
            //$lista .="<tr>\n";
            //$lista .="  <td>\n";
                //$obj->empleado_codigo=$this->empleado_codigo;
                    //$obj->asistencia_codigo=$this->asistencia_codigo;
                    //$lista .=$obj->Listar_incidencia_programada();     
            //$lista .="  </td>\n";
            //$lista .="</tr>\n";
      //}
	
    $rs->close();
    $rs=null;
    $obj=null;	
    return $lista;

  }

function eliminar_responsable_elegido(){
    
    
    /*
 $rpta="OK";
$rpta=$this->conectarme_ado();
	if($rpta="OK"){
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
	    $ssql ="delete from ca_asistencia_responsables ";
		$ssql .=" where Empleado_Codigo=?";
	    $ssql .=" and Asistencia_Codigo=?";
		$ssql .=" and Responsable_Codigo=?";
		
		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->asistencia_codigo;
        $cmd->Parameters[2]->value = $this->responsable_codigo;
		$r=$cmd->Execute();
		if(!$r){
		    $rpta = "Error al Eliminar responsable.";
             return $rpta;
		  }else{
		     $rpta= "OK";
		 }
		 $cmd=null;
  }		  
   return $rpta;	*/
   
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="delete from ca_asistencia_responsables ";
    $ssql .=" where Empleado_Codigo=?";
    $ssql .=" and Asistencia_Codigo=?";
    $ssql .=" and Responsable_Codigo=?";
		
    $params=array(
        $this->empleado_codigo,
        $this->asistencia_codigo,
        $this->responsable_codigo
    );
    
    $r=$cn->Execute($ssql,$params);
    
    if(!$r){
        $rpta = "Error al Eliminar responsable.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    return $rpta;	
   
}

function lista_empleados_para_responsables($area_codigo,$tipo){
    
    
    
/*$cadena="";
$rpta="OK";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){
	$ssql=  "select Responsable_codigo, Empleado,Area_Codigo,Area_Descripcion ";
	$ssql.=  " from vca_empleado_responsables ";
	$ssql.=  " where Estado_Codigo=1 and  ";
	if($tipo ==1 ) $ssql.=  " area_codigo=" . $area_codigo ."";
	if($tipo ==2 ) $ssql.=  " area_codigo<>" . $area_codigo ."";
	$ssql .=" and responsable_codigo not in (select responsable_codigo from ";
	$ssql.= " ca_asistencia_responsables ";
	$ssql.= " where empleado_codigo=" . $this->empleado_codigo . " and asistencia_codigo=" . $this->asistencia_codigo . ")";
    $ssql.=  " Order by 2 ";

	$rs = $this->cnnado->Execute($ssql);
		if(!$rs->EOF()) {
		  $i=0;
		  while(!$rs->EOF()){
			$i+=1;
			$cadena .="<tr class='CA_DataTD'>";
			$cadena .="	<td   nowrap>&nbsp;&nbsp;";
			$cadena .="<input type='radio' id='rdo" . $rs->fields[0]->value. "' name='rdo" . $rs->fields[0]->value . "' value='" . $rs->fields[1]->value . "' LANGUAGE=javascript onclick=cmdsupervisor(" . $rs->fields[0]->value . "," . $rs->fields[2]->value . ")  style='cursor:hand'>";
			$cadena .="</td>";
			$cadena .="	<td  align=center>" . $rs->fields[0]->value . "&nbsp;</td>";
			$cadena .="	<td >&nbsp;" . $rs->fields[1]->value;
			$cadena .="	</td >";
			$cadena .="	<td >&nbsp;" . $rs->fields[3]->value;
			$cadena .="	</td>";
			$cadena .="</tr>";
			$rs->movenext();
		   }
        }
    $rs->close();    
    $rs=null;   
	} 
 return $cadena; */
 
    $cadena="";
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    $ssql=  "select Responsable_codigo, Empleado,Area_Codigo,Area_Descripcion ";
    $ssql.=  " from vca_empleado_responsables ";
    $ssql.=  " where Estado_Codigo=1 and  ";
    if($tipo ==1 ) $ssql.=  " area_codigo='" . $area_codigo ."'";
    if($tipo ==2 ) $ssql.=  " area_codigo<>'" . $area_codigo ."'";
    $ssql .=" and responsable_codigo not in (select responsable_codigo from ";
    $ssql.= " ca_asistencia_responsables ";
    $ssql.= " where empleado_codigo='" . $this->empleado_codigo . "' and asistencia_codigo='" . $this->asistencia_codigo . "')";
    $ssql.=  " Order by 2 ";
    
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        $i=0;
        while(!$rs->EOF){
            $i+=1;
            $cadena .="<tr class='CA_DataTD'>";
            $cadena .="	<td   nowrap>&nbsp;&nbsp;";
            $cadena .="<input type='radio' id='rdo" . $rs->fields[0]. "' name='rdo" . $rs->fields[0] . "' value='" . $rs->fields[1] . "' LANGUAGE=javascript onclick=cmdsupervisor(".$rs->fields[0].",".$rs->fields[2].")  style='cursor:hand'>";
            $cadena .="</td>";
            $cadena .="	<td  align=center>" . $rs->fields[0] . "&nbsp;</td>";
            $cadena .="	<td >&nbsp;" . $rs->fields[1];
            $cadena .="	</td >";
            $cadena .="	<td >&nbsp;" . $rs->fields[3];
            $cadena .="	</td>";
            $cadena .="</tr>";
            $rs->MoveNext();
        }
    }
    $rs->close();    
    $rs=null;   
	
 return $cadena; 
 
}



}
?>
