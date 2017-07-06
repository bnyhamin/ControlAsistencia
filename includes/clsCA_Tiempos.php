<?php 

//require_once(PathIncludes() . "mantenimiento.php");
class ca_tiempos extends mantenimiento{
var $tiempo_codigo='0';
var $empleado_codigo='';
var $cod_campana='';
var $tiempo_fecha='';
var $tiempo_total='0';
var $tiempo_atencion='';
var $esavaya='1';
var $responsable_codigo="0";
//opcion deshabilitada
function registrar_tiempo($horas,$minutos){
 $rpta="OK";
 $rpta=$this->conectarme_ado(); 
 if($rpta=="OK"){
			$sql =" select * from ca_tiempos";
			$sql .=" where Empleado_Codigo=" . $this->empleado_codigo;
			$sql .=" and cod_campana=" . $this->cod_campana . "";
            $sql .=" and tiempo_fecha=convert(datetime,'" . $this->tiempo_fecha . "',103)";
            $sql .=" and esavaya is not null ";		
			$rs= $this->cnnado->Execute($sql);
			$this->tiempo_total=($horas*60 + $minutos)/60;
			
			$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
			if(!$rs->EOF()) {
			//update	
			$ssql ="update ca_tiempos ";
			$ssql .="    set tiempo_total=" . $this->tiempo_total;
			$ssql .="    ,horas=" . $horas;
			$ssql .="    ,minutos=" . $minutos;
			$ssql .=" where Empleado_Codigo=" .$this->empleado_codigo;
			$ssql .=" and cod_campana=" . $this->cod_campana;
			$ssql .=" and tiempo_fecha=convert(datetime,'" . $this->tiempo_fecha . "',103) ";
			$ssql .=" and esavaya is not null ";
			$cmd->ActiveConnection = $this->cnnado;
			$cmd->CommandText = $ssql;
		    }
			else{
		     //insert		
			$sql =" insert into ca_tiempos(tiempo_codigo,tiempo_fecha,empleado_codigo,";
			$sql .=" cod_campana,tiempo_total,horas,minutos,tiempo_atencion,fecha_reg,esavaya,esmanual,responsable_codigo) ";
			$sql .=" select top 1 max(Tiempo_Codigo)+1,convert(datetime,'" . $this->tiempo_fecha . "',103), ";
			$sql .=" " . $this->empleado_codigo . " ," . $this->cod_campana . "," . $this->tiempo_total . "";
			$sql .=" ," . $horas . "," . $minutos . ",null,getdate(),1,null," . $this->responsable_codigo . "";
	        $sql .=" from ca_tiempos ";
		
			$cmd->ActiveConnection = $this->cnnado;
			$cmd->CommandText = $sql;
		    }		
		    //echo $ssql;

			$r=$cmd->Execute();
	        if(!$r){
			   $rpta =" Error al registrar tiempo.";
			   return $rpta;
		    }else{
		     $rpta= "OK";
		    }
		    $rs->close();
		    $rs=null;
		    
		    $cmd=null;
		   
		  }   
return $rpta;
}

function Listar_tiempos(){
    
    /*
$rpta="OK";
$rpta=$this->conectarme_ado();

if($rpta=="OK"){			
      
	$lista = "      <tr>\n";
	$lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
	$lista .= "	         Codigo";
	$lista .= "         </td>\n";
	$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
	$lista .= "	         Fecha";
	$lista .= "         </td>\n";
	$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
	$lista .= "	      Unidad de Servicio";
	$lista .= "         </td>\n";
	$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
	$lista .= "	       Tiempo";
	$lista .= "         </td>\n";
	$lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
	$lista .= "	       Eliminar";
	$lista .= "         </td>\n";
	$lista .= "     </tr>\n";
		
	$ssql="SELECT tiempo_codigo,convert(varchar(10),tiempo_fecha,103) as fecha, ";
	$ssql .=" v.exp_nombrecorto + '(' + v.exp_codigo + ')', ";
	$ssql .="   case when len(horas)=1 then '0' + cast(horas as char) else cast(horas as char) end as hora, ";
	$ssql .="   case when len(minutos)=1 then '0' + cast(minutos as char) else cast(minutos as char) end as minutos ";
	$ssql .=" from ca_tiempos inner join v_campanas v on  v.cod_campana=ca_tiempos.cod_campana ";
	$ssql .=" where Empleado_Codigo=" . $this->empleado_codigo;
    $ssql .=" and tiempo_fecha=convert(datetime,'" . $this->tiempo_fecha . "',103)";

	$rs = $this->cnnado->Execute($ssql);
	if(!$rs->EOF()){
		while(!$rs->EOF()){
		
		    $lista .="<tr >\n";
			$lista .="	   <td class='CA_DataTD' align='left'>\n";
			$lista .="	   <b>" . $rs->fields[0]->value . "</b></td>\n";
			$lista .="	   <td class='CA_DataTD'>\n";
			$lista .=" " . $rs->fields[1]->value . "\n";
			$lista .="	   </td>\n";
			$lista .="	   <td bgcolor='#FFFF00'>\n";
			$lista .=" <b>" . $rs->fields[2]->value . "</b></td>\n";
			$lista .="	   <td class='CA_DataTD' align=center>\n";
			$lista .= $rs->fields[3]->value.':' . $rs->fields[4]->value . "</td>\n";
			$lista .="     <td width='5%' class='CA_DataTD' align='center'>\n";
			$lista .="        <img onClick='cmdEliminar_tiempo_onclick(" . $rs->fields[0]->value . ")' src='../Images/stock_delete.png'  width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar Tiempo'>";
			$lista .="	   </td>\n";
			$lista .= "</tr>\n";
			
			$rs->movenext();
		}
	}else{
		    $lista .="<tr>\n";
			$lista .="<td class='ca_datatd' align='center' colspan=5>\n";
			$lista .="	   <b>No hay registros de Tiempos de conexión !!</b></td>\n";
			$lista .= "</tr>\n";
			
	}
	$rs->close();
	$rs=null;
}	
return $lista;*/

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $lista = "      <tr>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center'>\n";
    $lista .= "	         Codigo";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	         Fecha";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	      Unidad de Servicio";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	       Tiempo";
    $lista .= "         </td>\n";
    $lista .= "        <td class='CA_FieldCaptionTD' align='center' >\n";
    $lista .= "	       Eliminar";
    $lista .= "         </td>\n";
    $lista .= "     </tr>\n";
    $ssql="SELECT tiempo_codigo,convert(varchar(10),tiempo_fecha,103) as fecha, ";
    $ssql .=" v.exp_nombrecorto + '(' + v.exp_codigo + ')', ";
    $ssql .="   case when len(horas)=1 then '0' + cast(horas as char) else cast(horas as char) end as hora, ";
    $ssql .="   case when len(minutos)=1 then '0' + cast(minutos as char) else cast(minutos as char) end as minutos ";
    $ssql .=" from ca_tiempos inner join v_campanas v on  v.cod_campana=ca_tiempos.cod_campana ";
    $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo;
    $ssql .=" and tiempo_fecha=convert(datetime,'" . $this->tiempo_fecha . "',103)";
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
        while(!$rs->EOF){
            $lista .="<tr >\n";
            $lista .="	   <td class='CA_DataTD' align='left'>\n";
            $lista .="	   <b>" . $rs->fields[0] . "</b></td>\n";
            $lista .="	   <td class='CA_DataTD'>\n";
            $lista .=" " . $rs->fields[1] . "\n";
            $lista .="	   </td>\n";
            $lista .="	   <td bgcolor='#FFFF00'>\n";
            $lista .=" <b>" . $rs->fields[2] . "</b></td>\n";
            $lista .="	   <td class='CA_DataTD' align=center>\n";
            $lista .= $rs->fields[3].':' . $rs->fields[4] . "</td>\n";
            $lista .="     <td width='5%' class='CA_DataTD' align='center'>\n";
            $lista .="        <img onClick='cmdEliminar_tiempo_onclick(" . $rs->fields[0] . ")' src='../Images/stock_delete.png'  width='15' height='15' border='0'  style='cursor:hand;' alt='Eliminar Tiempo'>";
            $lista .="	   </td>\n";
            $lista .= "</tr>\n";
            $rs->MoveNext();
        }
    }else{
        $lista .="<tr>\n";
        $lista .="<td class='ca_datatd' align='center' colspan=5>\n";
        $lista .="	   <b>No hay registros de Tiempos de conexión !!</b></td>\n";
        $lista .= "</tr>\n";
    }
	$rs->close();
	$rs=null;

    return $lista;

}

function eliminar_tiempo(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql ="delete from ca_tiempos ";
    $ssql .=" where tiempo_Codigo=?";
    
    $params=array($this->tiempo_codigo);
		
    $r=$cn->Execute($ssql,$params);
    if(!$r){
        $rpta = "Error al Eliminar tiempo";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    $cn=null;
    return $rpta;

}


}
?>
