<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_turnos extends mantenimiento{
var $turno_codigo="";
var $turno_hora_inicio=0;
var $turno_minuto_inicio=0;
var $turno_hora_fin=0;
var $turno_minuto_fin=0;
var $turno_descripcion="";
var $turno_refrigerio="0";
var $turno_descanzo="0";
var $turno_tolerancia="0";
var $turno_permiso_marca_entrada="0";
var $Dia1="";
var $Dia2="";
var $Dia3="";
var $Dia4="";
var $Dia5="";
var $Dia6="";
var $Dia7="";
var $turno_activo="";
var $turno_duo="";
var $turno_modalidad="0";
var $turno_horario="0";
var $tipo_area_codigo="0";
var $empleado_codigo="0";
var $turno_id="";
var $turno_descanso2="0";
var $turno_hora_refrigerio=0;
var $turno_minuto_refrigerio=0;


function Addnew(){

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    
    //obtener nuevo codigo de registro a insertar
    $ssql = "select isnull(max(turno_codigo), 0) + 1 as maximo from ca_turnos ";
    $rs= $cn->Execute($ssql);
    $this->turno_codigo = $rs->fields[0];

    $ssql = "INSERT INTO ca_turnos";
    $ssql .= " (Turno_Codigo, Turno_Descripcion,Turno_Hora_Inicio,Turno_Minuto_Inicio,Turno_Hora_Fin,";
    $ssql .= " Turno_Minuto_Fin,Turno_Activo, Dia1, Dia2, Dia3,"; 
    $ssql .= " Dia4, Dia5, Dia6, Dia7,Turno_Tolerancia,";
    $ssql .= " Turno_Duo,Tipo_Area_codigo,turno_refrigerio,turno_descanzo,fecha_registro,";
    $ssql .= " empleado_codigo_registro,Turno_id,Turno_descanso2,Turno_hora_refrigerio,Turno_minuto_refrigerio) ";
    $ssql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,getdate(),?,?,?,?,?)";
    
    $params=array(
        $this->turno_codigo,
        $this->turno_descripcion,
        $this->turno_hora_inicio,
        $this->turno_minuto_inicio,
        $this->turno_hora_fin,
        
        $this->turno_minuto_fin,
        $this->turno_activo,
        $this->Dia1,
        $this->Dia2,
        $this->Dia3,
        
        $this->Dia4,
        $this->Dia5,
        $this->Dia6,
        $this->Dia7,
        $this->turno_tolerancia,
        
        $this->turno_duo,
        $this->tipo_area_codigo,
        $this->turno_refrigerio,
        $this->turno_descanzo, 
        
        $this->empleado_codigo,
        $this->turno_id,
        $this->turno_descanso2,
        $this->turno_hora_refrigerio,
        $this->turno_minuto_refrigerio
          
      );
    
    $rs=$cn->Execute($ssql,$params);
    
    if(!$rs) $rpta="Error";
   
    return $rpta;
    
}

function Update(){
	/*$rpta="Ok";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = "UPDATE ca_turnos ";
		$ssql .= " SET Turno_Descripcion = '" . $this->turno_descripcion . "',";
		$ssql .= "     Turno_Hora_Inicio = " . $this->turno_hora_inicio . ","; 
		$ssql .= "     Turno_Minuto_Inicio = " . $this->turno_minuto_inicio . ","; 
		$ssql .= "     Turno_Hora_Fin = " . $this->turno_hora_fin . ","; 
		$ssql .= "     Turno_Minuto_Fin = " . $this->turno_minuto_fin . "," ;
		$ssql .= "     Turno_Activo = " . $this->turno_activo . ",";
		$ssql .= "     Turno_Refrigerio = " . $this->turno_refrigerio . ",";
		$ssql .= "     Turno_Descanzo = " . $this->turno_descanzo . ",";
		$ssql .= "     Turno_Tolerancia = " . $this->turno_tolerancia . ",";
		$ssql .= "     Turno_Duo = " . $this->turno_duo . ",";
		$ssql .= "     Tipo_Area_Codigo = " . $this->tipo_area_codigo;
		//$ssql .= "     Turno_Modalidad = " . $this->turno_modalidad . "," ;
		//$ssql .= "     Turno_Horario = " . $this->turno_horario ;
		$ssql .= "     ,Dia1 = " . $this->Dia1 ;
		$ssql .= "     ,Dia2 = " . $this->Dia2 ;
		$ssql .= "     ,Dia3 = " . $this->Dia3 ;
		$ssql .= "     ,Dia4 = " . $this->Dia4 ;
		$ssql .= "     ,Dia5 = " . $this->Dia5 ;
		$ssql .= "     ,Dia6 = " . $this->Dia6 ;
		$ssql .= "     ,Dia7 = " . $this->Dia7 ;
		$ssql .= "     ,Fecha_Modificacion = getdate()" ;
		$ssql .= "     ,Empleado_Codigo_Modificacion = " . $this->empleado_codigo ;
		$ssql .= "     ,Turno_id = '" . $this->turno_id . "'";
		$ssql .= "     ,Turno_descanso2 = " . $this->turno_descanso2 ;
		$ssql .= "     ,Turno_hora_refrigerio = " . $this->turno_hora_refrigerio ;
		$ssql .= "     ,Turno_minuto_refrigerio = " . $this->turno_minuto_refrigerio ;
		$ssql .= " Where Turno_Codigo =?"; 
		
	    $cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->turno_codigo;
		$cmd->Execute();
	}
	return $rpta;*/
        
    
        $rpta="OK";
	$cn=$this->getMyConexionADO();
	
        $ssql = "UPDATE ca_turnos ";
        $ssql .= " SET Turno_Descripcion = ?,";
        $ssql .= "     Turno_Hora_Inicio = ?,"; 
        $ssql .= "     Turno_Minuto_Inicio = ?,"; 
        $ssql .= "     Turno_Hora_Fin = ?,"; 
        $ssql .= "     Turno_Minuto_Fin = ?," ;
        $ssql .= "     Turno_Activo = ?,";
        $ssql .= "     Turno_Refrigerio = ?,";
        $ssql .= "     Turno_Descanzo = ?,";
        $ssql .= "     Turno_Tolerancia = ?,";
        $ssql .= "     Turno_Duo = ?,";
        $ssql .= "     Tipo_Area_Codigo = ? ";
        $ssql .= "     ,Dia1 = ? " ;
        $ssql .= "     ,Dia2 = ? " ;
        $ssql .= "     ,Dia3 = ? " ;
        $ssql .= "     ,Dia4 = ? " ;
        $ssql .= "     ,Dia5 = ? " ;
        $ssql .= "     ,Dia6 = ? " ;
        $ssql .= "     ,Dia7 = ? " ;
        $ssql .= "     ,Fecha_Modificacion = getdate()" ;
        $ssql .= "     ,Empleado_Codigo_Modificacion = ? ";
        $ssql .= "     ,Turno_id = ? ";
        $ssql .= "     ,Turno_descanso2 = ? " ;
        $ssql .= "     ,Turno_hora_refrigerio = ? ";
        $ssql .= "     ,Turno_minuto_refrigerio = ? " ;
        $ssql .= " Where Turno_Codigo = ? "; 
        
        
        $params=array(
            $this->turno_descripcion,
            $this->turno_hora_inicio,
            $this->turno_minuto_inicio,
            $this->turno_hora_fin,
            $this->turno_minuto_fin,
            $this->turno_activo,
            $this->turno_refrigerio,
            $this->turno_descanzo, 
            $this->turno_tolerancia,
            $this->turno_duo,
            $this->tipo_area_codigo,
            $this->Dia1,
            $this->Dia2,
            $this->Dia3,
            $this->Dia4,
            $this->Dia5,
            $this->Dia6,
            $this->Dia7,
            $this->empleado_codigo,
            $this->turno_id,
            $this->turno_descanso2,
            $this->turno_hora_refrigerio,
            $this->turno_minuto_refrigerio,
            $this->turno_codigo
        );
        
        $rs=$cn->Execute($ssql,$params);
        if(!$rs) $rpta="Error";
	
	return $rpta;
    
}

function Query(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = "SELECT Turno_Codigo, Turno_Descripcion,Turno_Hora_Inicio,Turno_Minuto_Inicio,Turno_Hora_Fin,Turno_Minuto_Fin,abs(Turno_Duo),abs(Turno_activo),Turno_Tolerancia,abs(Dia1), abs(Dia2), abs(Dia3), abs(Dia4), abs(Dia5), abs(Dia6), abs(Dia7),tipo_area_codigo, Turno_Refrigerio, Turno_Descanzo, Turno_id, Turno_descanso2, Turno_hora_refrigerio, Turno_minuto_refrigerio ";
		$ssql .= " FROM ca_turnos ";
		$ssql .= " WHERE turno_Codigo = " . $this->turno_codigo;
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->turno_descripcion = $rs->fields[1]->value;
			$this->turno_hora_inicio = $rs->fields[2]->value;
			$this->turno_minuto_inicio = $rs->fields[3]->value;
			$this->turno_hora_fin= $rs->fields[4]->value;
			$this->turno_minuto_fin= $rs->fields[5]->value;
			$this->turno_duo= $rs->fields[6]->value;
			$this->turno_activo= $rs->fields[7]->value;
			$this->turno_tolerancia=$rs->fields[8]->value;
			$this->Dia1= $rs->fields[9]->value;
			$this->Dia2= $rs->fields[10]->value;
			$this->Dia3= $rs->fields[11]->value;
			$this->Dia4= $rs->fields[12]->value;
			$this->Dia5= $rs->fields[13]->value;
			$this->Dia6= $rs->fields[14]->value;
			$this->Dia7= $rs->fields[15]->value;
			$this->tipo_area_codigo= $rs->fields[16]->value;
			$this->turno_refrigerio=$rs->fields[17]->value;
			$this->turno_descanzo=$rs->fields[18]->value;
			$this->turno_id=$rs->fields[19]->value;
			$this->turno_descanso2=$rs->fields[20]->value;
			$this->turno_hora_refrigerio=$rs->fields[21]->value;
			$this->turno_minuto_refrigerio=$rs->fields[22]->value;
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->turno_codigo;
	  }
	 } 
	return $rpta;*/
        
        
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql = "SELECT Turno_Codigo, Turno_Descripcion,Turno_Hora_Inicio,Turno_Minuto_Inicio,Turno_Hora_Fin,Turno_Minuto_Fin,abs(Turno_Duo),abs(Turno_activo),Turno_Tolerancia,abs(Dia1), abs(Dia2), abs(Dia3), abs(Dia4), abs(Dia5), abs(Dia6), abs(Dia7),tipo_area_codigo, Turno_Refrigerio, Turno_Descanzo, Turno_id, Turno_descanso2, Turno_hora_refrigerio, Turno_minuto_refrigerio ";
    $ssql .= " FROM ca_turnos ";
    $ssql .= " WHERE turno_Codigo = " . $this->turno_codigo;
    $rs = $cn->Execute($ssql);
    if ($rs->RecordCount()>0){
        $this->turno_descripcion = $rs->fields[1];
        $this->turno_hora_inicio = $rs->fields[2];
        $this->turno_minuto_inicio = $rs->fields[3];
        $this->turno_hora_fin= $rs->fields[4];
        $this->turno_minuto_fin= $rs->fields[5];
        $this->turno_duo= $rs->fields[6];
        $this->turno_activo= $rs->fields[7];
        $this->turno_tolerancia=$rs->fields[8];
        $this->Dia1= $rs->fields[9];
        $this->Dia2= $rs->fields[10];
        $this->Dia3= $rs->fields[11];
        $this->Dia4= $rs->fields[12];
        $this->Dia5= $rs->fields[13];
        $this->Dia6= $rs->fields[14];
        $this->Dia7= $rs->fields[15];
        $this->tipo_area_codigo= $rs->fields[16];
        $this->turno_refrigerio=$rs->fields[17];
        $this->turno_descanzo=$rs->fields[18];
        $this->turno_id=$rs->fields[19];
        $this->turno_descanso2=$rs->fields[20];
        $this->turno_hora_refrigerio=$rs->fields[21];
        $this->turno_minuto_refrigerio=$rs->fields[22];
    }else{
        $rpta='No Existe Registro de turno: '.$this->turno_codigo;
    }
	 
	return $rpta;
}

function Mostrar_turnos($cadena_dia){//-- listar los campos del modulo solicitado
	$rpta='';
	$titulo='';
	$rango=$this->turno_permiso_marca_entrada;
	$ssql ='';
 	$rpta=$this->conectarme_ado();
    if($rpta=="OK"){
	    if ($this->tipo_area_codigo=='1'){ //turnos operativos
			$ssql=" SELECT turno_codigo as codigo,turno_descripcion AS descripcion FROM ca_turnos ";
			$ssql .=" WHERE " . $cadena_dia . "=1 and tipo_area_codigo=" . $this->tipo_area_codigo . " and turno_activo=1 and  ";
			$ssql .=" turno_codigo <> " . $this->turno_codigo;
			$ssql .=" and (datediff( mi, getdate(), convert(datetime, convert(varchar(10), getdate(), 103) + ' ' + convert(varchar, Turno_Hora_Inicio ) + ':' + convert(varchar, Turno_Minuto_Inicio ), 103)))<=" . $rango;
	    $ssql .=" and (datediff( mi, getdate(), convert(datetime, convert(varchar(10), getdate(), 103) + ' ' + convert(varchar, Turno_Hora_Inicio ) + ':' + convert(varchar, Turno_Minuto_Inicio ), 103)))>-" . $rango;
			//$ssql .=" ca_turnos.turno_hora_inicio>=(datepart(hour,dateadd(minute,-" . $rango . ",getdate()))) and "; 
			//$ssql .=" ca_turnos.turno_hora_inicio<(datepart(hour,dateadd(minute," . $rango . ",getdate()))) and ";
			//$ssql .=" convert(int,convert(varchar,ca_turnos.turno_hora_inicio) + convert(varchar,ca_turnos.turno_minuto_inicio))>=  ";
			//$ssql .=" convert(int,convert(varchar,datepart(hour,dateadd(minute,-" . $rango . ",getdate())))+ convert(varchar,datepart(minute,dateadd(minute,-" . $rango . ",getdate())))) ";
			//$ssql .=" UNION  ";
			//$ssql .=" SELECT turno_codigo as codigo,turno_descripcion AS descripcion FROM ca_turnos ";
			//$ssql .=" WHERE " . $cadena_dia . "=1 and tipo_area_codigo=" . $this->tipo_area_codigo . "  and turno_activo=1 and ";
			//$ssql .=" turno_codigo <> " . $this->turno_codigo . " and ";
			//$ssql .=" ca_turnos.turno_hora_inicio>(datepart(hour,dateadd(minute,-" . $rango . ",getdate()))) and ";
			//$ssql .=" ca_turnos.turno_hora_inicio<=(datepart(hour,dateadd(minute," . $rango . ",getdate()))) and ";
			//$ssql .=" convert(int,convert(varchar,ca_turnos.turno_hora_inicio) + convert(varchar,ca_turnos.turno_minuto_inicio))<=  ";
			//$ssql .=" convert(int,convert(varchar,datepart(hour,dateadd(minute," . $rango . ",getdate())))+ convert(varchar,datepart(minute,dateadd(minute," . $rango . ",getdate())))) "; 
			$ssql .=" Order by turno_descripcion ";
		}else{ //turnos administrativos
		  $ssql="SELECT turno_codigo as codigo, turno_descripcion  AS descripcion FROM ca_turnos "; 
			$ssql .=" WHERE turno_activo=1 and tipo_area_codigo=" . $this->tipo_area_codigo;
			$ssql .=" 	and turno_codigo <> " . $this->turno_codigo;
			$ssql .=" order by turno_descripcion";
		}
		//echo $ssql;
	    $rs = $this->cnnado->Execute($ssql);
	    if(!$rs->EOF()) {
		  echo "<script language='javascript' >";
	      while (!$rs->EOF()){
		  	if ($rs->fields['codigo']->value!='')	$titulo = $rs->fields['descripcion']->value;
		  	echo "window.parent.Agregar_Item_Combo('" . $rs->fields['codigo']->value . "', '" . $titulo . "');";
		  	$rs->movenext();
		  }
		  echo "</script>";
	    }
    }
	return $rpta;
}


}
?>
