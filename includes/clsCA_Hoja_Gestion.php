<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_hoja_gestion extends mantenimiento{
var $HGestion_Codigo="";
var $HGestion_Fecha="";
var $HGestion_Minutos="";
var $HGestion_Posiciones="";
var $HGestion_Descripcion="";
var $HGestion_Fecha_Modi="";
var $HGestion_Fecha_Reg=""; 
var $HGestion_Responsable="";
var $cod_campana="";
var $THoja_Codigo="";

function Listar_Hoja_Gestion_dia($THoja_Codigo){
	/*$rpta="OK";
	$i=0;
	$cadena="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	  $ssql = " SELECT HGestion_Codigo, Cod_Campana, THoja_Codigo, ";
      $ssql .= " HGestion_Fecha, HGestion_Minutos, HGestion_Posiciones,  HGestion_Descripcion, ";
      $ssql .= " HGestion_Fecha_Modi, HGestion_Fecha_Reg, "; 
      $ssql .= " HGestion_Responsable ";
	  $ssql .= " FROM CA_Hoja_Gestion ";
	  $ssql .= " WHERE (Cod_Campana = " . $this->cod_campana . " ) AND (THoja_Codigo = " . $this->THoja_Codigo . " ) AND ";
      $ssql .= " (HGestion_Fecha = CONVERT(DATETIME,'" . $this->HGestion_Fecha ."', 103)) ";
      $ssql .= " order by HGestion_Codigo ";
	  
	  $rs = $this->cnnado->Execute($ssql);
		//if($rs) echo "Error";
		if (!$rs->EOF()){
		 $i=0;
		 while(!$rs->EOF()){
		  $i+=1;		
		  $cadena .="<TR >";
		  $cadena .="<TD class='DataTD' align=right><STRONG>" . $i. "</STRONG></TD>";
		  $cadena .="<TD class='DataTD' ><INPUT class='Input' readOnly type=text style='width: 80px'  id='txtMinutos" .$rs->fields[0]->value . "' name='txtMinutos" .$rs->fields[0]->value . "' value='" .$rs->fields[4]->value . "'></TD>";
		  $cadena .="<TD class='DataTD' ><INPUT class='Input' readOnly type=text style='width: 80px'  id='txtPosi" .$rs->fields[0]->value . "'   name='txtPosi" .$rs->fields[0]->value . "'  value='" . $rs->fields[5]->value . "'></TD>";
		  $cadena .="<TD class='DataTD' ><INPUT class='Input' type=text style='width: 250px' maxlength='250' id='txtDesc" .$rs->fields[0]->value . "' name='txtDesc" .$rs->fields[0]->value . "' value='" . $rs->fields[6]->value . "'></TD>";
		  $cadena .="<TD class='DataTD'><INPUT type=radio style='width: 80px' id='radio_" .$rs->fields[0]->value . "'  name='radio_" .$rs->fields[0]->value . "'  onclick='Javascript:activar(". $rs->fields[0]->value . ");'></TD>";
		  $cadena .="</TR>";		
		  $rs->movenext(); 
		  
	    }
	   }else{
		   $rpta='No Existe Registros';
	  }
	     $i+=1;
	     $cadena .="<TR >";
		 $cadena .="<TD align=right><STRONG>" . $i ."</STRONG></TD>";

		 if ($THoja_Codigo==5 || $THoja_Codigo== 4 ){ 
		 	$cadena .=" <TD class='DataTD' ><INPUT class='Input' type='text' readOnly style='width: 80px' disabled id='txtMinutos0' name='txtMinutos0' value=''></TD>";
		 	$cadena .=" <TD class='DataTD' ><INPUT class='Input' type='text' readOnly style='width: 80px' disabled id='txtPosi'0 name='txtPosi0' value=''></TD>";
		}else{ 
			$cadena .=" <TD class='DataTD'><INPUT class='Input' type='text' style='width: 80px' id='txtMinutos0' name='txtMinutos0' value='' onKeyPress='return esnumero()'></TD>";
			$cadena .=" <TD class='DataTD'><INPUT class='Input' type='text' style='width: 80px' id='txtPosi0' name='txtPosi0' value='' onKeyPress='return esnumero()'></TD>";
			}
		 $cadena .="<TD class='DataTD'><INPUT class='Input' type='text' style='width: 250px' maxlength=500 id='txtDesc0' name='txtDesc0' value=''></TD>";
		 $cadena .="<TD class='DataTD'>&nbsp;</TD>";
		 $cadena .="</TR>";
	 } 
	return $cadena;*/
    
    $rpta="OK";
    $i=0;
    $cadena="";
    $cn=$this->getMyConexionADO();
	
    $ssql = " SELECT HGestion_Codigo, Cod_Campana, THoja_Codigo, ";
    $ssql .= " HGestion_Fecha, HGestion_Minutos, HGestion_Posiciones,  HGestion_Descripcion, ";
    $ssql .= " HGestion_Fecha_Modi, HGestion_Fecha_Reg, "; 
    $ssql .= " HGestion_Responsable ";
    $ssql .= " FROM CA_Hoja_Gestion ";
    $ssql .= " WHERE (Cod_Campana = " . $this->cod_campana . " ) AND (THoja_Codigo = " . $this->THoja_Codigo . " ) AND ";
    $ssql .= " (HGestion_Fecha = CONVERT(DATETIME,'" . $this->HGestion_Fecha ."', 103)) ";
    $ssql .= " order by HGestion_Codigo ";	  
    $rs = $cn->Execute($ssql);
		
    if (!$rs->EOF){
        $i=0;
        while(!$rs->EOF){
            
            $i+=1;	
            $cadena.="<TR >";
            $cadena.="<TD class='DataTD' align=right><STRONG>" . $i. "</STRONG></TD>";
            $cadena.="<TD class='DataTD' ><INPUT class='Input' readOnly type=text style='width: 80px'  id='txtMinutos" .$rs->fields[0]."' name='txtMinutos" .$rs->fields[0]. "' value='".$rs->fields[4]."'></TD>";
            $cadena.="<TD class='DataTD' ><INPUT class='Input' readOnly type=text style='width: 80px'  id='txtPosi" .$rs->fields[0]."'   name='txtPosi" .$rs->fields[0]."'  value='".$rs->fields[5]."'></TD>";
            $cadena.="<TD class='DataTD' ><INPUT class='Input' type=text style='width: 250px' maxlength='250' id='txtDesc" .$rs->fields[0]."' name='txtDesc" .$rs->fields[0]."' value='".$rs->fields[6]."'></TD>";
            $cadena.="<TD class='DataTD'><INPUT type=radio style='width: 80px' id='radio_" .$rs->fields[0] . "'  name='radio_" .$rs->fields[0]."'  onclick='Javascript:activar(".$rs->fields[0].");'></TD>";
            $cadena.="</TR>";
            $rs->MoveNext();
            
        }
    }else{
        $rpta='No Existe Registros';
    }
    
    $i+=1;
    
    $cadena .="<TR >";
    $cadena .="<TD align=right><STRONG>" . $i ."</STRONG></TD>";

    if ($THoja_Codigo==5 || $THoja_Codigo== 4 ){ 
        $cadena .=" <TD class='DataTD' ><INPUT class='Input' type='text' readOnly style='width: 80px' disabled id='txtMinutos0' name='txtMinutos0' value=''></TD>";
        $cadena .=" <TD class='DataTD' ><INPUT class='Input' type='text' readOnly style='width: 80px' disabled id='txtPosi'0 name='txtPosi0' value=''></TD>";
    }else{ 
        $cadena .=" <TD class='DataTD'><INPUT class='Input' type='text' style='width: 80px' id='txtMinutos0' name='txtMinutos0' value='' onKeyPress='return esnumero()'></TD>";
        $cadena .=" <TD class='DataTD'><INPUT class='Input' type='text' style='width: 80px' id='txtPosi0' name='txtPosi0' value='' onKeyPress='return esnumero()'></TD>";
    }
    
    $cadena .="<TD class='DataTD'><INPUT class='Input' type='text' style='width: 250px' maxlength=500 id='txtDesc0' name='txtDesc0' value=''></TD>";
    $cadena .="<TD class='DataTD'>&nbsp;</TD>";
    $cadena .="</TR>";

    return $cadena;
    
}


function Addnew(){
 /*$rpta="OK";
	$cadena="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		//insertar nuevo registro
		$ssql ="Exec spCA_Insert_Hoja_Gestion ?,?,'" .$this->HGestion_Fecha . "',?,?,?,'" . $this->HGestion_Descripcion . "'";
		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
    	$cmd->Parameters[0]->value = $this->cod_campana;
    	$cmd->Parameters[1]->value = $this->THoja_Codigo;
		if($this->THoja_Codigo==5 || $this->THoja_Codigo==4 ){
			$cmd->Parameters[2]->value=null;	//Total de Minutos
			$cmd->Parameters[3]->value=null;	//Total de Posiciones
		}else{
			$cmd->Parameters[2]->value= $this->HGestion_Minutos; //Total de Minutos
			$cmd->Parameters[3]->value= $this->HGestion_Posiciones; //Total de Posiciones
		}
		$cmd->Parameters[4]->value= $this->HGestion_Responsable; //codigo del coordinador o supervisor
		$r=$cmd->Execute();
		  if(!$r){
		       $rpta = "Error al Insertar Hoja de Gestion.";
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }
   }
   return $rpta;*/
    
    
    
    
    
    //->$rpta="OK";
    $rpta="";
    $cadena="";
    $cn=$this->getMyConexionADO();
    //insertar nuevo registro
    $ssql ="Exec spCA_Insert_Hoja_Gestion ?,?,'" .$this->HGestion_Fecha . "',?,?,?,'" . $this->HGestion_Descripcion . "'";
    
    $params=array(
        $this->cod_campana,
        $this->THoja_Codigo
    );
    
    //$cmd->Parameters[0]->value = $this->cod_campana;
    //$cmd->Parameters[1]->value = $this->THoja_Codigo;
    
    if($this->THoja_Codigo==5 || $this->THoja_Codigo==4 ){
        //$cmd->Parameters[2]->value=null;	//Total de Minutos
        //$cmd->Parameters[3]->value=null;	//Total de Posiciones
        $params[]=null;
        $params[]=null;
    }else{
        $params[]=$this->HGestion_Minutos;
        $params[]=$this->HGestion_Posiciones;
        //$cmd->Parameters[2]->value= $this->HGestion_Minutos; //Total de Minutos
        //$cmd->Parameters[3]->value= $this->HGestion_Posiciones; //Total de Posiciones
    }
    
    //$cmd->Parameters[4]->value= $this->HGestion_Responsable; //codigo del coordinador o supervisor
    $params[]=$this->HGestion_Responsable;
    
    $rs=$cn->Execute($ssql,$params);
    if(!$rs){
        $rpta = "Error al Insertar Hoja de Gestion.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    
    return $rpta;
    
    /*$r=$cmd->Execute();
    if(!$r){
        $rpta = "Error al Insertar Hoja de Gestion.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
   
   return $rpta;*/
    
}

function Delete(){
 /*$rpta="OK";
	$cadena="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$ssql =" delete from CA_hoja_Gestion ";
        $ssql .=" where HGestion_Codigo = ? ";
		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->HGestion_Codigo;
		$r=$cmd->Execute();
		  if(!$r){
		       $rpta = "Error al Eliminar Hoja de Gestion.";
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }
   }
   return $rpta;*/
   
    
    $rpta="OK";
    $cadena="";
    $cn=$this->getMyConexionADO();
    $ssql =" delete from CA_hoja_Gestion ";
    $ssql .=" where HGestion_Codigo = ? ";
    
    $params=array(
        $this->HGestion_Codigo
    );
				
    $rs=$cn->Execute($ssql,$params);
    
    if(!$rs){
        $rpta = "Error al Eliminar Hoja de Gestion.";
        return $rpta;
    }else{
        $rpta= "OK";
    }
   
    return $rpta;
   
}



}
?>
