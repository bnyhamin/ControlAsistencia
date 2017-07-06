<?php
//require_once(PathIncludes() . "mantenimiento.php");
class incidencias extends mantenimiento{
var $incidencia_codigo="";
var $incidencia_descripcion="";
var $incidencia_operacion="";
var $incidencia_hh_dd="";
var $incidencia_activo="";
var $incidencia_icono="";
var $Incidencia_manual='';
var $area_codigo='';
var $evento='';
var $incidencia_signo='';
var $incidencia_editable='';
var $usuario_id='';

function Addnew(){
	$rpta="";
	
	$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
	mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD" . mysql_error());
	
	//obtener nuevo codigo de registro a insertar
	$ssql = "select isnull(max(incidencia_codigo), 0) + 1 as maximo from ca_incidencias ";
	$rs1_result = mssql_query($ssql);
	$rs1=mssql_fetch_row($rs1_result);
	$this->incidencia_codigo = $rs1[0];
	//insertar nuevo registro
	$ssql = "INSERT INTO ca_incidencias";
	$ssql .= " (incidencia_Codigo, incidencia_Descripcion,Incidencia_HH_DD,incidencia_icono,incidencia_activo, Incidencia_manual, evento,incidencia_signo,incidencia_editable,usuario_id,area_codigo) ";
	$ssql .= " VALUES (" . $this->incidencia_codigo . ",";
	$ssql .= " '" . $this->incidencia_descripcion . "',";
	$ssql .=        $this->incidencia_hh_dd . ",";
	$ssql .= " '" . $this->incidencia_icono . "',";
	$ssql .= $this->incidencia_activo . ",";
	$ssql .= " '" . $this->Incidencia_manual . "',";
	$ssql .= " '" . $this->evento . "',";
	$ssql .= " '" . $this->incidencia_signo . "',";	
	$ssql .= " '" . $this->incidencia_editable . "',";
	$ssql .= " " . $this->usuario_id . ",";
	$ssql .= " " . $this->area_codigo . ")";
	if (mssql_query($ssql)==false){
		
		$rpta = "Error al Insertar nuevo Registro de incidencia.";
		return $rpta;
	}else{
		$rpta= "OK";
	}
	return $rpta;
}

function Update(){
	$rpta="Ok";
	$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
	mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD" . mysql_error());
	
	$ssql = "UPDATE ca_incidencias ";
	$ssql .= " SET incidencia_Descripcion = '" . $this->incidencia_descripcion . "',";
	$ssql .= "     incidencia_hh_dd = " . $this->incidencia_hh_dd . ",";
	$ssql .= "     incidencia_activo = " . $this->incidencia_activo . "," ;
	$ssql .= "     incidencia_icono = '" . $this->incidencia_icono . "',";
	$ssql .= "     Incidencia_manual = '" . $this->Incidencia_manual . "',";
	$ssql .= "     incidencia_editable = '" . $this->incidencia_editable . "',";
	$ssql .= "     incidencia_signo = '" . $this->incidencia_signo . "',";
	$ssql .= "     evento = '" . $this->evento . "', ";
	$ssql .= "     area_codigo = " . $this->area_codigo . "";
	$ssql .= " Where incidencia_Codigo = " . $this->incidencia_codigo; 
	//echo $ssql;
	if (mssql_query($ssql)==false){
		$rpta = "Error al Actualizar Datos de Incidencia.";
		return $rpta;
	}
	return $rpta;
}

function Query(){
    /*
	$rpta="OK";
	$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
	mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD" . mysql_error());
	
	$ssql = "SELECT Incidencia_codigo, Incidencia_descripcion,abs(Incidencia_HH_DD),incidencia_icono,abs(incidencia_activo), Incidencia_manual, evento, incidencia_editable,area_codigo,incidencia_signo ";
	$ssql .= " FROM ca_incidencias ";
	$ssql .= " WHERE incidencia_Codigo = " . $this->incidencia_codigo;
	
	//echo $ssql;
	$rs_result = mssql_query($ssql);
	if (mssql_num_rows($rs_result)>0){
		$rs = mssql_fetch_row($rs_result);
		$this->incidencia_descripcion = $rs[1];
		$this->incidencia_hh_dd = $rs[2];
		$this->incidencia_icono= $rs[3]; 
		$this->incidencia_activo= $rs[4]; 
		$this->Incidencia_manual= $rs[5];
		$this->evento = $rs[6];
		$this->incidencia_editable=$rs[7];
		$this->area_codigo= $rs[8];
		$this->incidencia_signo= $rs[9];
	}else{
		$rpta='No Existe Registro de Incidencia: ' . $this->incidencia_codigo;
	}
	return $rpta;*/
    
    
    
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql = "SELECT Incidencia_codigo, Incidencia_descripcion,abs(Incidencia_HH_DD),incidencia_icono,abs(incidencia_activo), Incidencia_manual, evento, incidencia_editable,area_codigo,incidencia_signo ";
    $ssql .= " FROM ca_incidencias ";
    $ssql .= " WHERE incidencia_Codigo = " . $this->incidencia_codigo;

	$rs=$cn->Execute($ssql);
	if ($rs->RecordCount()>0){
		
		$this->incidencia_descripcion = $rs->fields[1];
		$this->incidencia_hh_dd = $rs->fields[2];
		$this->incidencia_icono= $rs->fields[3]; 
		$this->incidencia_activo= $rs->fields[4]; 
		$this->Incidencia_manual= $rs->fields[5];
		$this->evento = $rs->fields[6];
		$this->incidencia_editable=$rs->fields[7];
		$this->area_codigo= $rs->fields[8];
		$this->incidencia_signo= $rs->fields[9];
	}else{
		$rpta='No Existe Registro de Incidencia: ' . $this->incidencia_codigo;
	}
	return $rpta;
    
    
    
}
function Lista_Incidencias(){
    $rpta="OK";
	$cadena="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
             $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
             $cadena  .="<TR><TD class='FieldCaptionTD' align='left' colspan='2'><b>Sel.</b></TD>\n";
             $cadena  .="<TD class='FieldCaptionTD' align='left' ><b>Incidencia</b></TD>\n";
             $cadena  .="</TR>";
             
                $ssql .="SELECT incidencia_codigo,incidencia_descripcion from ca_incidencias ";
                $ssql .=" where (area_codigo=0 or area_codigo=" . $this->area_codigo . ") and incidencia_activo=1";
                $ssql .=" order by 2 ";
                
                
				$rs = $this->cnnado->Execute($ssql);
		        if (!$rs->EOF){
				    $cadena .="<TR class='DataTD'>\n";
                    $cadena .="<TD align='left' colspan='3'>\n";
                    $cadena .="<INPUT type=CHECKBOX align=center id='chk_todos' name='chk_todos' value='0' onclick='checkear()'>TODOS";
                    $cadena .="</TD></TR>";
                   while (!$rs->EOF){
                        $cadena .= "<TR class='DataTD'>\n";
                        $cadena .="<TD width='20%'>&nbsp;</TD>\n";
                        $cadena .="<TD>\n"; 
                        $cadena .="<INPUT type=CHECKBOX align=center id='chk' name='chk' value=" . $rs->fields[0]->value . " onclick='check()'>";
                        $cadena .="</TD>\n<TD >" . $rs->fields[1]->value; 
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
   
}
?>
