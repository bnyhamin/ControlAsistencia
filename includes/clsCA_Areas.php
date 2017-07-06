<?php
//require_once(PathIncludes() . "mantenimiento.php");
class areas extends mantenimiento{
var $area_codigo="";
var $area_descripcion="";
var $area_activo="";
var $area_orden="";
var $area_jefe="";

function Query(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = "SELECT Area_Codigo, Area_Descripcion, Area_Jefe, Area_Orden,Area_Activo ";
		$ssql .= " FROM Areas ";
		$ssql .= " WHERE Area_Codigo = " . $this->area_codigo;
		$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->area_codigo = $rs->fields[0]->value;
			$this->area_descripcion = $rs->fields[1]->value;
			$this->area_jefe= $rs->fields[2]->value;
			$this->area_orden= $rs->fields[3]->value;
			$this->area_activo= $rs->fields[4]->value;
	  }else{
		   $rpta='No Existe Registro de Usuario';
	  }	  		
	}
	return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "SELECT Area_Codigo, Area_Descripcion, Area_Jefe, Area_Orden,Area_Activo ";
    $ssql .= " FROM Areas ";
    $ssql .= " WHERE Area_Codigo = ".$this->area_codigo;
    $rs = $cn->Execute($ssql);
    if ($rs->RecordCount()>0){
        $this->area_codigo = $rs->fields[0];
        $this->area_descripcion = $rs->fields[1];
        $this->area_jefe= $rs->fields[2];
        $this->area_orden= $rs->fields[3];
        $this->area_activo= $rs->fields[4];
    }else{
        $rpta='No Existe Registro de Usuario';
    }	  		
	
    return $rpta;
    
  }
  
function getAreas(){   
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    if($cn){
        
        $ssql=" select Area_Codigo,Area_Descripcion,DIASVALIDACIONGAP, ";
        $ssql.=" CASE WHEN Area_Activo = 1 then 'Activo' else 'Desactivo' end as Estado ";
        $ssql.=" from areas where Area_Activo = 1 and Area_Codigo > 0";
        $ssql.=" order by Area_Descripcion ASC ";
        
        $padre=array();
        $rs = $cn->Execute($ssql);
        while(!$rs->EOF){
            $hijo=array();
            $hijo["area_codigo"]=$rs->fields[0];
            $hijo["area_descripcion"]=utf8_encode($rs->fields[1]);
            $hijo["diasvalidaciongap"]=$rs->fields[2];
            $hijo["estado"]=$rs->fields[3];
            array_push($padre, $hijo);
            $rs->MoveNext();
        }
        $rs->close();
        $rs=null;
    }
    return $padre;
}
  

function updDiasArea(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){   
        $ssql="update areas set DIASVALIDACIONGAP = ? where Area_Codigo = ? ";
        $params = array(
            $this->dias,
            $this->area_codigo
        );
        
        $rs=$cn->Execute($ssql, $params);
    	if(!$rs){
            $rpta = "Error al cambiar de estado";
        }
    }
    
    return $rpta;
}



function updAll(){
    
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        
        $ssql="update areas set DIASVALIDACIONGAP = ? ";
        
        $params = array(
            $this->dias
        );
        
        $rs=$cn->Execute($ssql, $params);
        if(!$rs) $rpta = "Error al cambiar de estado";
        $rs=null;
    }
    return $rpta;    
}


function Lista_Areas($search,$flag_gerente,$area_codigo,$todos){
    
    
    /*$rpta="OK";
	$cadena="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
             $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
             $cadena  .="<TR><TD class='ColumnTD' align='center'>Sel</b></TD>\n";
			 $cadena  .="<TD class='ColumnTD' align='center'>Codigo</b></TD>\n";
			 $cadena  .="<TD class='ColumnTD' align='center'>Area</b></TD>\n";
             $cadena  .="</TR>";
             
                $ssql ="SELECT area_codigo,area_descripcion from areas ";
                $ssql .=" WHERE " ;
                if($todos==0){
	                if($flag_gerente =="1") $ssql .=" (Area_Codigo in (" . $this->TreeRecursivo($area_codigo) . ")) and (area_codigo<>" . $area_codigo . ") and ";
	                else $ssql .=" area_codigo<>0 and ";
                }else{
                	 if($todos==1) $ssql .="";
                     }
                $ssql .=" upper(area_descripcion) like '%" . strtoupper($search) . "%' and area_activo=1";
                $ssql .=" order by 2 ";
				//echo $ssql;
				$rs = $this->cnnado->Execute($ssql);
		        if (!$rs->EOF){
                   while (!$rs->EOF){
                        $cadena .= "<TR class='DataTD'>\n";
                        $cadena .="<TD>\n"; 
                        $cadena .="<INPUT type=radio align=center id='rdo' name='rdo' value=" . $rs->fields[0]->value . " onclick='cmdEnviar(\"" . $rs->fields[0]->value . "\",\"". $rs->fields[1]->value."\")'>";
                        $cadena .="</TD>\n<TD >" . $rs->fields[0]->value; 
						$cadena .="</TD>\n<TD >" . $rs->fields[1]->value; 
                        $cadena .="</TD>\n</TR>";
						$rs->movenext();
                    }
                $cadena .="</table>";
              }else{
		       $cadena .="";
	         }
          }
          return $cadena;*/
    
    
    $rpta="OK";
    $cadena="";
    $cn=$this->getMyConexionADO();

    $cadena .= "<TABLE class='FormTable' style='width:90%' cellspacing='0' cellpadding='0' border='0' align='center' >";
    $cadena .="<TR><TD class='ColumnTD' align='center'>Sel</b></TD>\n";
    $cadena .="<TD class='ColumnTD' align='center'>Codigo</b></TD>\n";
    $cadena .="<TD class='ColumnTD' align='center'>Area</b></TD>\n";
    $cadena .="</TR>";
             
    $ssql ="SELECT area_codigo,area_descripcion from areas ";
    $ssql .=" WHERE " ;
    if($todos==0){
        if($flag_gerente =="1") $ssql .=" (Area_Codigo in (".$this->TreeRecursivo($area_codigo).")) and (area_codigo<>".$area_codigo.") and ";
        else $ssql .=" area_codigo<>0 and ";
    }else{
        if($todos==1) $ssql .="";
    }
    
    $ssql .=" upper(area_descripcion) like '%" . strtoupper($search) . "%' and area_activo=1";
    $ssql .=" order by 2 ";
				
    $rs = $cn->Execute($ssql);
    if ($rs->RecordCount()>0){
       while (!$rs->EOF){
            $cadena .= "<TR class='DataTD'>";
            $cadena .="<TD>"; 
            $cadena .="<INPUT type=radio align=center id='rdo' name='rdo' value=" . $rs->fields[0]. " onclick='cmdEnviar(\"" . $rs->fields[0] . "\",\"". $rs->fields[1]."\")'>";
            $cadena .="</TD><TD >".$rs->fields[0]; 
            $cadena .="</TD><TD >".$rs->fields[1];
            $cadena .="</TD></TR>";
            $rs->MoveNext();
        }
            $cadena .="</table>";
    }else{
        $cadena .="";
    }
          
    return $cadena;
          
  }
    
function TreeRecursivo($area_codigo){
/*$rpta="OK";
    $cadena="";
    $subcadena="";
    $rpta=$this->conectarme_ado();
    if($rpta=="OK"){
    $cadena = $area_codigo;	
    $subcadena = $cadena;
    $sw=1;
    while($sw=1){	
    $ssql ="SELECT area_codigo from areas ";
$ssql .=" where  area_jefe in (" . $subcadena . ")";
$ssql .=" and area_activo=1";
$rs = $this->cnnado->Execute($ssql);
$subcadena="";	
    if (!$rs->EOF){
    while (!$rs->EOF){	
             if($subcadena=="") $subcadena=$rs->fields[0]->value;
             else $subcadena .="," . $rs->fields[0]->value;
             $rs->movenext();
        }
    }else{
            $sw=0;
            if($sw==0) break;		
            }
            if($cadena=="") $cadena=$subcadena;
            else  $cadena .= "," . $subcadena;

}
}
return $cadena;*/
    
    $rpta="OK";
    $cadena="";
    $subcadena="";
    $cn=$this->getMyConexionADO();
    $cadena = $area_codigo;	
    $subcadena = $cadena;
    $sw=1;
    while($sw=1){
    $ssql ="SELECT area_codigo from areas ";
    $ssql .=" where  area_jefe in (".$subcadena.")";
    $ssql .=" and area_activo=1";
    $rs = $cn->Execute($ssql);
    $subcadena="";	
    if ($rs->RecordCount()>0){
        while (!$rs->EOF){
            if($subcadena=="") $subcadena=$rs->fields[0];
            else $subcadena .="," . $rs->fields[0];
            $rs->MoveNext();
        }
    }else{
        $sw=0;
        if($sw==0) break;
    }
        if($cadena=="") $cadena=$subcadena;
        else  $cadena .= "," . $subcadena;
    }
    
    return $cadena;

}  


 
}
?>
