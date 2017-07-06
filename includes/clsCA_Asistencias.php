<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_asistencia extends mantenimiento{
var $asistencia_codigo=0;
var $empleado_codigo=0;
var $evento_codigo=0;
var $supervisor_registra=NULL;
var $asistencia_fecha="";
var $asistencia_entrada="";
var $asistencia_salida="";
var $turno_codigo=0;
//var $turno="";
var $fecha_reg="";
var $ip_entrada="";
var $ip_salida="";
var $ca_estado_codigo="";
var $hora_entrada="";
var $tip=1;
var $responsable_codigo="";
var $empleado_modifica_turno="";
var $num_asistencias=0;
var $tardanza=0;
var $turno_tolerancia=0;
var $turno_duo=0;
//var $hora_entrada="";
var $hora_salida="";
var $area_codigo="";
//var $minutos_registrados=0;
//var $minutos_trabajo=0;
var $saldo_tiempo=0;
var $turno_diario=0;
var $diario_horas=0;
var $diario_minutos=0;
var $data_saldo=0;
var $minutos_turno=0;
var $tt_inicio='';
var $tt_final='';

//Para verificar vacaciones
var $fecha='';
var $vac=0;
var $turno=0;
var $tiempo_permitido_marca_ingreso =0;
var $empleado_dni="";

function registrar_entrada_new(){
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	    $ssql =" exec spCA_REGISTRAR_ENTRADA_GAP ".$this->empleado_codigo;
		$ssql.=",'".$this->asistencia_fecha."','".$this->ip_entrada."',0,0,0, " . $this->turno_codigo .",'G'";
		$rs= $this->cnnado->Execute($ssql);

		if(!$rs){
			$rpta = "Error al Insertar asistencia.";
			return $rpta;
		}else{
			if ($rs->EOF()){
				$rpta = "Error al Insertar asistencia.";
				return $rpta;
			}else{
				//$rpta = "OK";
				$this->asistencia_codigo = $rs->fields[2]->value;
				$rpta = $rs->fields[1]->value;
			}
		}
		$rs->close();
		$rs=null;
	}
	return $rpta;
}

function registrar_entrada($supervisores_codigos){
 $rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
  $this->cnnado->BeginTrans();
	 // crea un nuevo registro
	    $ssql="select isnull(max(asistencia_codigo),0)+1 id from ca_asistencias  (nolock)";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo;
		$rs= $this->cnnado->Execute($ssql);
		$this->asistencia_codigo = $rs->fields[0]->value;

		$ssql ="select turno_duo ";
		$ssql .=" from ca_turnos where turno_codigo=" . $this->turno_codigo;
		$rs= $this->cnnado->Execute($ssql);
		$this->turno_duo = $rs->fields[0]->value;

		if($this->turno_duo==1){
		   $ssql ="select case when getdate()>=CONVERT(DATETIME, CONVERT(varchar(10), getdate(), 103) + ' 00:00:00', 103) and ";
           $ssql .=" getdate()<dateadd(hour,2,CONVERT(DATETIME, CONVERT(varchar(10), getdate(), 103) + ' 00:00:00', 103)) then 1 ";
           $ssql .=" else 0 end from ca_turnos where turno_codigo=" . $this->turno_codigo;
		   $rs= $this->cnnado->Execute($ssql);
		   $this->turno_duo = $rs->fields[0]->value;
		}

		//-- obtener area_codigo del empleado
		$ssql="select area_codigo from empleado_area where empleado_codigo= " . $this->empleado_codigo . " and empleado_area_activo=1";
		$rsa= $this->cnnado->Execute($ssql);
		if ($rsa->EOF()){
			$this->area_codigo = 'null';
		}else{
			$this->area_codigo = $rsa->fields[0]->value;
		}

                $sql =" insert into ca_asistencias";
                $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Asistencia_Entrada,Asistencia_Salida,Turno_Codigo,Ip_entrada,ca_estado_codigo,fecha_reg_entrada,Fecha_Reg, area_codigo) ";
                $sql .=" select " . $this->empleado_codigo . ",";
                $sql .=" " . $this->asistencia_codigo . ",";
                if($this->turno_duo==0) $sql .=" CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103) ,";
                if($this->turno_duo==1) $sql .=" CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103) -1,";
                $sql .=" getdate(), ";
                $sql .=" null ,";
                $sql .=" " . $this->turno_codigo . ",";
                $sql .=" '" .$this->ip_entrada . "',";
                $sql .=" 1,";
                $sql .=" getdate(), ";
                $sql .=" getdate(), ";
                $sql .=" " .$this->area_codigo;
                $sql .=" from empleados ";
                $sql .=" where Empleado_Codigo = ? ";

                $params=array($this->empleado_codigo);
                $r=$this->cnnado->Execute($sql, $params);
                if(!$r){
                    $rpta = "Error al Insertar asistencia.";
                    $this->cnnado->RollbackTrans();
                    return $rpta;
		}else{
                    $rpta= "OK";
		}


		//Inserta responsables a la asistencia
		if($supervisores_codigos!=''){
			$sql =" insert into ca_asistencia_responsables ";
			$sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,fecha_reg, area_codigo_responsable) ";
			$sql .=" select " . $this->empleado_codigo . ",e.empleado_codigo," . $this->asistencia_codigo . " ,getdate(), ea.area_codigo ";
			$sql .=" from empleados e inner join empleado_area ea on e.empleado_codigo = ea.empleado_codigo";
			$sql .=" where e.empleado_codigo in (" .$supervisores_codigos . ") and ea.empleado_area_activo=1";

			$cmd->CommandText = $sql;
		    $r=$cmd->Execute();
	       if(!$r){
		       $rpta = "Error al Insertar supervisores.";
		       $this->cnnado->RollbackTrans();
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }
		 }
	  $this->cnnado->CommitTrans();
	  $rs->close();
	  $rsa->close();

	  $rs=null;
	  $rsa=null;
	  $cmd=null;

	  }
	  return $rpta;
 }

function registrar_salida_new(){
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
	    $ssql =" exec spCA_REGISTRAR_SALIDA ".$this->empleado_codigo;
		$ssql.=",'".$this->ip_salida."'";
		$rs= $this->cnnado->Execute($ssql);
	    if(!$rs){
			$rpta = "Error al Insertar asistencia.";
			return $rpta;
		}else{
			if ($rs->EOF()){
				$rpta = "Error al Insertar asistencia.";
				return $rpta;
			}else{
				//$rpta = "OK";
				$this->asistencia_salida = $rs->fields[2]->value;
				$rpta = $rs->fields[1]->value;
			}
		}
		$rs->close();
		$rs=null;
	}
	return $rpta;
}

function registrar_salida(){
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$this->cnnado->BeginTrans();

        $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	    $ssql ="update ca_asistencias ";
		$ssql .=" set asistencia_salida=getdate(), ";
		$ssql .="    fecha_reg_salida=getdate(),ip_salida='" . $this->ip_salida . "' ";
		$ssql .=" where Empleado_Codigo=? ";
	    $ssql .=" and Asistencia_Codigo=? ";

		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
    	$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->asistencia_codigo;
		$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Actualizar asistencia.";
		  $this->cnnado->RollbackTrans();
		  return $rpta;
		}else{
		 $ssql ="  select convert(varchar(8),asistencia_salida,108) as salida  from ca_asistencias ";
		 $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo;
	     $ssql .=" and Asistencia_Codigo=" . $this->asistencia_codigo;
		 $rs= $this->cnnado->Execute($ssql);
		 $this->asistencia_salida= $rs->fields[0]->value;
		 $rpta= "OK";
		 $rs->close();
	  	 $rs=null;
		}

	    //Agregar mas responsables o quitar responsables a la asistencia;
		$sql =" delete from ca_asistencia_responsables ";
		$sql .=" where empleado_codigo=? and asistencia_codigo=?";
		if($supervisores_codigos!='') $sql .=" and responsable_codigo not in (". $supervisores_codigos . ")";
        $cmd->CommandText = $sql;
    	$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->asistencia_codigo;
		$r=$cmd->Execute();
	       if(!$r){
		       $rpta = "Error al Eliminar supervisores.";
		       $this->cnnado->RollbackTrans();
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }

        if($supervisores_codigos!=''){
			$sql =" insert into ca_asistencia_responsables ";
			$sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,fecha_reg) ";
			$sql .=" select " . $this->empleado_codigo . ",empleados.empleado_codigo," . $this->asistencia_codigo . " ,getdate() ";
			$sql .=" from empleados ";
			$sql .=" where empleados.empleado_codigo in(" . $supervisores_codigos. ")";
			$sql .=" and empleados.empleado_codigo not in (select responsable_codigo from ca_asistencia_responsables ";
			$sql .="     where empleado_codigo=". $this->empleado_codigo . "  and asistencia_codigo=" . $this->asistencia_codigo . ")";

			$cmd->ActiveConnection = $this->cnnado;
			$cmd->CommandText = $sql;
			//$cmd->Parameters[0]->value = $supervisores_codigos ;
			$r=$cmd->Execute();
	       if(!$r){
		       $rpta = "Error al Insertar supervisores.";
		       $this->cnnado->RollbackTrans();
		       return $rpta;
		   }else{
		     $rpta= "OK";
		   }
		}
		$this->cnnado->CommitTrans();
	  	$cmd=null;
	}
	return $rpta;
 }


function registrar_salida_old($supervisores_codigos){
 $rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
  $this->cnnado->BeginTrans();

        $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
	    $ssql ="update ca_asistencias ";
		$ssql .=" set asistencia_salida=getdate(), ";
		$ssql .="    fecha_reg_salida=getdate(),ip_salida='" . $this->ip_salida . "' ";
		$ssql .=" where Empleado_Codigo=? ";
	    $ssql .=" and Asistencia_Codigo=? ";

		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
    	$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->asistencia_codigo;
		$r=$cmd->Execute();
	    if(!$r){
		  $rpta = "Error al Actualizar asistencia.";
		  $this->cnnado->RollbackTrans();
		  return $rpta;
		}else{
		 $ssql ="  select convert(varchar(8),asistencia_salida,108) as salida  from ca_asistencias ";
		 $ssql .=" where Empleado_Codigo=" . $this->empleado_codigo;
	     $ssql .=" and Asistencia_Codigo=" . $this->asistencia_codigo;
		 $rs= $this->cnnado->Execute($ssql);
		 $this->asistencia_salida= $rs->fields[0]->value;
		 $rpta= "OK";
		 $rs->close();
	  	 $rs=null;
		}

	    //Agregar mas responsables o quitar responsables a la asistencia;
		$sql =" delete from ca_asistencia_responsables ";
		$sql .=" where empleado_codigo=? and asistencia_codigo=?";
		if($supervisores_codigos!='') $sql .=" and responsable_codigo not in (". $supervisores_codigos . ")";
        $cmd->CommandText = $sql;
    	$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->asistencia_codigo;
		$r=$cmd->Execute();
	       if(!$r){
		       $rpta = "Error al Eliminar supervisores.";
		       $this->cnnado->RollbackTrans();
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }

        if($supervisores_codigos!=''){
			$sql =" insert into ca_asistencia_responsables ";
			$sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,fecha_reg) ";
			$sql .=" select " . $this->empleado_codigo . ",empleados.empleado_codigo," . $this->asistencia_codigo . " ,getdate() ";
			$sql .=" from empleados ";
			$sql .=" where empleados.empleado_codigo in(" . $supervisores_codigos. ")";
			$sql .=" and empleados.empleado_codigo not in (select responsable_codigo from ca_asistencia_responsables ";
			$sql .="     where empleado_codigo=". $this->empleado_codigo . "  and asistencia_codigo=" . $this->asistencia_codigo . ")";

			$cmd->ActiveConnection = $this->cnnado;
			$cmd->CommandText = $sql;
			//$cmd->Parameters[0]->value = $supervisores_codigos ;
			$r=$cmd->Execute();
	       if(!$r){
		       $rpta = "Error al Insertar supervisores.";
		       $this->cnnado->RollbackTrans();
		       return $rpta;
		   }else{
		     $rpta= "OK";
		   }
		}
		$this->cnnado->CommitTrans();
	  	$cmd=null;
	}
	return $rpta;
 }

function registrar_incidencia_horas(){
    $cn=$this->getMyConexionADO();
    $rpta="OK";
    $indica = 0;
    $supervisor = 0;
    if($this->supervisor_registra==NULL){
        $indica = 0;
    }else{
        $indica = 1;
        $supervisor = $this->supervisor_registra;
    }

    $text="exec spCA_Insertar_Horas_Trabajadas ?,?,?,?,?,?";
    $params=array(
        $this->empleado_codigo,
        $this->asistencia_codigo,
        $this->responsable_codigo,
        $this->evento_codigo,
        $indica,
        $supervisor
    );

    $r=$cn->Execute($text,$params);

    if(!$r){
        $rpta = "Error al Insertar Incidencia de Horas.";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }
    return $rpta;
}


function validar(){
    $codigo=0;
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	$params = array(
                    $this->empleado_codigo,
                    $this->empleado_codigo
                );
	$ssql ="SELECT asistencia_codigo,case when convert(varchar(10), getdate(),103)=convert(varchar(10),asistencia_fecha,103) then '1' else '0' end  as tip ";
	$ssql .=" FROM ca_asistencias (nolock) ";
	$ssql .="  inner join ca_turnos (nolock) ";
	$ssql .="  on ca_turnos.turno_codigo=ca_asistencias.turno_codigo ";
	$ssql .=" WHERE (Empleado_Codigo =  ?) AND ";
	$ssql .=" fecha_reg_entrada=(select max(fecha_reg_entrada) ";
	$ssql .=" from ca_asistencias (nolock) where empleado_codigo= ?";
	$ssql .="   and asistencia_fecha<getdate() and ca_estado_codigo=1 and asistencia_salida is null ) ";
	$ssql .=" AND datediff(minute,CONVERT(datetime, ";
	$ssql .=" case when turno_duo=0 then convert(varchar(10),asistencia_fecha,103) ";
	$ssql .=" else convert(varchar(10),asistencia_fecha +1 ,103) end  + ' ' ";
	$ssql .=" + cast(ca_turnos.turno_hora_fin AS varchar(2)) ";
	$ssql .=" + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103),getdate())<=479 ";

	$rs = $cn->Execute($ssql, $params);
	  if (!$rs->EOF){
			$this->asistencia_codigo=$rs->fields[0];//asistencia_codigo
			$this->tip=$rs->fields[1];
	  }else{
		   $this->asistencia_codigo=0;
		   $this->tip=0;
	  }
	 $rs->close();
	 $rs=null;
 }
return $rpta;
}


function validar_entrada(){
    $codigo=0;
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    	$ssql="select convert(varchar(8),asistencia_entrada,108) as entrada from ca_asistencias (nolock)";
		$ssql .=" where (Empleado_Codigo = " . $this->empleado_codigo  . ")";
		$ssql .=" and asistencia_fecha=convert(datetime,'" . $this->asistencia_fecha . "',103) and ca_estado_codigo=1";
		$ssql .=" and asistencia_salida is null ";
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$rpta="OK";
			$this->hora_entrada=$rs->fields[0]->value;
	  }else{
		    $rpta="nOK";
	  }

	  $rs->close();
	  $rs=null;
    }
return $rpta;
}
 function validar_salida(){
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    $ssql="SELECT convert(varchar(8),asistencia_salida,108) as salida ";
	$ssql .=" from ca_asistencias (nolock) ";
	$ssql .=" where empleado_codigo=" . $this->empleado_codigo. " and asistencia_codigo=" . $this->asistencia_codigo;
	$ssql .=" and asistencia_salida is not null";
	$rs = $this->cnnado->Execute($ssql);
	if (!$rs->EOF){
			$rpta="OK";
			$this->hora_salida=$rs->fields[0]->value;
	}else{
		   $rpta="nOK";
		}
	$rs->close();
	$rs=null;

	}
	return $rpta;
  }



function entrada_anterior(){
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
    $ssql="SELECT case when convert(varchar(10), getdate(),103)=convert(varchar(10),asistencia_fecha,103) then '1' else '0' end as tip,abs(ca_turnos.turno_duo) ";
	$ssql .=" from ca_asistencias ";
	$ssql .=" inner join ca_turnos on ca_turnos.turno_codigo=ca_asistencias.turno_codigo ";
	$ssql .=" WHERE Empleado_Codigo = " . $this->empleado_codigo  . " and asistencia_codigo=" . $this->asistencia_codigo;
	//echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
	$this->tip=$rs->fields[0]->value;
	$this->turno_duo=$rs->fields[1]->value;

	$rs->close();
	$rs=null;

   }
return $rpta;
}

function obtener(){
    /*
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
  $ssql="SELECT asistencia_codigo,convert(varchar(8),asistencia_entrada, 108) as entrada,convert(varchar(10),asistencia_entrada,103) as fecha_entrada, ";
	$ssql .=" convert(varchar(5),asistencia_salida, 108) as salida,ca_turnos.turno_descripcion ";
	$ssql .=" FROM ca_asistencias inner join ca_turnos on ca_turnos.turno_codigo=ca_asistencias.turno_codigo ";
	$ssql .=" WHERE Empleado_Codigo = " . $this->empleado_codigo  . " AND Asistencia_Codigo= " . $this->asistencia_codigo;
   //echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
	$this->asistencia_codigo=$rs->fields[0]->value;//asistencia_codigo
	$this->hora_entrada=$rs->fields[1]->value;// hora
	$this->fecha_entrada =$rs->fields[2]->value;// fecha
	$this->turno=$rs->fields[4]->value;//turno_descripcion

	$rs->close();
	$rs=null;
 }
return $rpta;*/

    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql="SELECT asistencia_codigo,convert(varchar(8),asistencia_entrada, 108) as entrada,convert(varchar(10),asistencia_entrada,103) as fecha_entrada, ";
    $ssql .=" convert(varchar(5),asistencia_salida, 108) as salida,ca_turnos.turno_descripcion ";
    $ssql .=" FROM ca_asistencias inner join ca_turnos on ca_turnos.turno_codigo=ca_asistencias.turno_codigo ";
    $ssql .=" WHERE Empleado_Codigo = " . $this->empleado_codigo  . " AND Asistencia_Codigo= " . $this->asistencia_codigo;

    $rs = $cn->Execute($ssql);

    $this->asistencia_codigo=$rs->fields[0];//asistencia_codigo
    $this->hora_entrada=$rs->fields[1];// hora
    $this->fecha_entrada =$rs->fields[2];// fecha
    $this->turno=$rs->fields[4];//turno_descripcion

    $rs->close();
    $rs=null;

    return $rpta;

}

function Query_asistencia(){
 $rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
    $ssql="SELECT asistencia_codigo,convert(varchar(10),asistencia_fecha,103) as asistencia_fecha,asistencia_entrada, ";
	$ssql .=" convert(varchar(8),asistencia_salida, 108) as salida,turno_codigo,ca_estado_codigo,ip_entrada,ip_salida ";
	$ssql .=" FROM ca_asistencias ";
	$ssql .=" left join ca_turnos on ca_asistencias.turno_codigo=ca_turnos.turno_codigo ";
	$ssql .="  where empleado_codigo=". $this->empleado_codigo  ." and asistencia_codigo=" . $this->asistencia_codigo;
	$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->asistencia_codigo= $rs->fields[0]->value; //asistencia_codigo
			$this->asistencia_fecha =$rs->fields[1]->value; // fecha
			$this->asistencia_entrada =$rs->fields[2]->value; // entrada
			$this->asistencia_salida = $rs->fields[3]->value;// salida
			$this->turno_codigo=$rs->fields[4]->value;//turno
			$this->ca_estado_codigo=$rs->fields[5]->value; //estado
			$this->ip_entrada=$rs->fields[6]->value;
			$this->ip_salida=$rs->fields[7]->value;
	  }else{
		   $this->asistencia_codigo=0;
	  }

	$rs->close();
	$rs=null;
}
return $rpta;
}

function Query_fecha(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
    $ssql="SELECT asistencia_codigo, convert(varchar(10),asistencia_fecha,103) as asistencia_fecha,asistencia_entrada, ";
  	$ssql .=" convert(varchar(8),asistencia_salida, 108) as salida, ca_asistencias.turno_codigo, ip_entrada, ip_salida ";
  	$ssql .=" FROM ca_asistencias ";
  	$ssql .=" left join ca_turnos on ca_asistencias.turno_codigo=ca_turnos.turno_codigo ";
  	$ssql .="  where empleado_codigo=". $this->empleado_codigo;
    $ssql .="     and asistencia_fecha=convert(datetime,'" . $this->asistencia_fecha . "',103)";
    $ssql .="     and ca_estado_codigo=1";
  	$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->asistencia_codigo= $rs->fields[0]->value; //asistencia_codigo
			$this->asistencia_fecha =$rs->fields[1]->value; // fecha
			$this->asistencia_entrada =$rs->fields[2]->value; // entrada
			$this->asistencia_salida = $rs->fields[3]->value;// salida
			$this->turno_codigo=$rs->fields[4]->value;//turno
			$this->ip_entrada=$rs->fields[5]->value;
			$this->ip_salida=$rs->fields[6]->value;
	  }else{
		   $this->asistencia_codigo=0;
	  }

	$rs->close();
	$rs=null;
}
return $rpta;*/

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="SELECT asistencia_codigo, convert(varchar(10),asistencia_fecha,103) as asistencia_fecha,asistencia_entrada, ";
    $ssql .=" convert(varchar(8),asistencia_salida, 108) as salida, ca_asistencias.turno_codigo, ip_entrada, ip_salida ";
    $ssql .=" FROM ca_asistencias ";
    $ssql .=" left join ca_turnos on ca_asistencias.turno_codigo=ca_turnos.turno_codigo ";
    $ssql .="  where empleado_codigo=". $this->empleado_codigo;
    $ssql .="     and asistencia_fecha=convert(datetime,'" . $this->asistencia_fecha . "',103)";
    $ssql .="     and ca_estado_codigo=1";

    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        $this->asistencia_codigo= $rs->fields[0]; //asistencia_codigo
        $this->asistencia_fecha =$rs->fields[1]; // fecha
        $this->asistencia_entrada =$rs->fields[2]; // entrada
        $this->asistencia_salida = $rs->fields[3];// salida
        $this->turno_codigo=$rs->fields[4];//turno
        $this->ip_entrada=$rs->fields[5];
        $this->ip_salida=$rs->fields[6];
    }else{
        $this->asistencia_codigo=0;
    }

    $rs->close();
    $rs=null;
    return $rpta;

}

function obtener_tardanza(){
 $rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
    $ssql="SELECT asistencia_entrada,tardanza,turno_tolerancia from vCA_tardanza_asistencia_empleado ";
	$ssql .="  where empleado_codigo=". $this->empleado_codigo  ." and asistencia_codigo=" . $this->asistencia_codigo;
	$rs = $this->cnnado->Execute($ssql);
		//if($rs) echo "Error";
		if (!$rs->EOF){
		    $this->asistencia_entrada= $rs->fields[0]->value;
		    $this->tardanza= $rs->fields[1]->value;
			$this->turno_tolerancia=$rs->fields[2]->value;
	  }else{
		   $this->tardanza=0;
	  }

	$rs->close();
	$rs=null;
  }
return $rpta;
}



function registrar_nueva_asistencia($supervisores_codigos,$incidencia_codigo){
    /*
 $rpta="";
	     // crea un nuevo registro
	    $ssql="select isnull(max(asistencia_codigo),0)+1 id from ca_asistencias ";
		$ssql .=" where empleado_codigo=" . $this->empleado_codigo;
		$rs= $this->cnnado->Execute($ssql);
		$this->asistencia_codigo = $rs->fields[0]->value;
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");

		//-- obtener area_codigo del empleado
		$ssql="select area_codigo from empleado_area where empleado_codigo= " . $this->empleado_codigo . " and empleado_area_activo=1";
		$rsa= $this->cnnado->Execute($ssql);
		if ($rsa->EOF()){
			$this->area_codigo = 'null';
		}else{
			$this->area_codigo = $rsa->fields[0]->value;
		}

		if($incidencia_codigo==42 || $incidencia_codigo==66){
	         $sql =" insert into ca_asistencias";
			 $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Asistencia_Entrada,ip_entrada,Turno_Codigo,ca_estado_codigo,fecha_reg_entrada,fecha_reg, area_codigo ) ";
			 $sql .=" select " . $this->empleado_codigo . ",". $this->asistencia_codigo . ",CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103), ";
			 $sql .=" CONVERT(DATETIME, CONVERT(varchar(10),'" . $this->asistencia_fecha . "', 103) + ' ' + cast(ca_turnos.turno_hora_inicio AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_inicio AS varchar(2)), 103),";
			 $sql .=" '" . $this->ip_entrada . "',ca_turnos.turno_codigo,1,CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103),getdate(), " . $this->area_codigo;
			 $sql .=" from empleados inner join ca_turnos on ca_turnos.turno_codigo=empleados.turno_codigo ";
			 $sql .=" where Empleado_Codigo=?";

		}else{
		     if($incidencia_codigo==11){
		     	$ssql="SELECT ct.turno_duo ";
				$ssql .=" FROM  empleados e ";
				$ssql .=" inner join  ca_turnos ct on ct.turno_codigo=e.turno_codigo ";
				$ssql .= " WHERE e.Empleado_Codigo = " . $this->empleado_codigo;
				$rs= $this->cnnado->Execute($ssql);
				$duo= $rs->fields[0]->value;
				//echo "duo :" . $duo;
				 $sql =" insert into ca_asistencias";
				 $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Asistencia_Entrada,fecha_reg_entrada,ip_entrada,Asistencia_Salida,fecha_reg_salida,ip_salida,Turno_Codigo,ca_estado_codigo,fecha_reg, area_codigo) ";
				 $sql .=" select " . $this->empleado_codigo . ",". $this->asistencia_codigo . ",CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103), ";
				 $sql .=" CONVERT(DATETIME, CONVERT(varchar(10),'" . $this->asistencia_fecha . "', 103) + ' ' + cast(ca_turnos.turno_hora_inicio AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_inicio AS varchar(2)), 103),";
				 $sql .=" CONVERT(DATETIME, CONVERT(varchar(10),'" . $this->asistencia_fecha . "', 103) + ' ' + cast(ca_turnos.turno_hora_inicio AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_inicio AS varchar(2)), 103),";
				 $sql .=" '" . $this->ip_entrada . "',";
				 if ($duo==0){
					$sql .="  CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103), ";
				    $sql .="  CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103), ";
				 }
				 if ($duo==1){
					$sql .="  dateadd(day,1,CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103)), ";
					$sql .="  dateadd(day,1,CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103)), ";
				 }
				 $sql .=" '" . $this->ip_salida . "',ca_turnos.turno_codigo,1,getdate(), " . $this->area_codigo;
				 $sql .=" from empleados inner join ca_turnos on ca_turnos.turno_codigo=empleados.turno_codigo ";
				 $sql .=" where Empleado_Codigo=?";

			}else{
			   $sql =" insert into ca_asistencias";
			   $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Turno_Codigo,ca_estado_codigo,fecha_reg_entrada,fecha_reg, area_codigo ) ";
			   $sql .=" select " . $this->empleado_codigo . "," . $this->asistencia_codigo . ",CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103),";
			   $sql .=" turno_codigo,1,CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103),getdate(), " . $this->area_codigo;
			   $sql .=" from empleados where Empleado_Codigo=?";
			  }
			}

			//echo "---->" . $sql;
			$cmd->ActiveConnection = $this->cnnado;
			$cmd->CommandText = $sql;
    		$cmd->Parameters[0]->value = $this->empleado_codigo;
			$r=$cmd->Execute();
	    	if(!$r){
		  	  $rpta = "Error al Insertar nueva asistencia.";
		  	  $this->cnnado->RollbackTrans();
		  	  return $rpta;
			}else{
		 	  $rpta= "OK";
			}
			  //echo "supervisores_codigos==>" . $supervisores_codigos;
	     if($supervisores_codigos!=''){

			$sql =" insert into ca_asistencia_responsables ";
			$sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,fecha_reg, area_codigo_responsable) ";
			$sql .=" select " . $this->empleado_codigo . ",e.empleado_codigo," . $this->asistencia_codigo . " ,getdate(), ea.area_codigo ";
			$sql .=" from empleados e inner join empleado_area ea on e.empleado_codigo = ea.empleado_codigo  ";
			$sql .=" where e.empleado_codigo in (?) and ea.empleado_area_activo=1";
			//echo $sql;
	        $cmd->CommandText = $sql;
    	    $cmd->Parameters[0]->value = $supervisores_codigos;
		    $r=$cmd->Execute();
	       if(!$r){
		       $rpta = "Error al Insertar supervisores.";
		       $this->cnnado->RollbackTrans();
		       return $rpta;
		  }else{
		     $rpta= "OK";
		   }
		   //echo "rpta==>" . $rpta;
		   if($incidencia_codigo==11){
				$rpta=$this->registrar_incidencia_horas();
			}

		   $rs->close();
	  	   $rsa->close();

	  	   $rs=null;
	       $rsa=null;
	       $cmd=null;

	}
  return $rpta;
 */
    $rpta="";
    // crea un nuevo registro
    $ssql="select isnull(max(asistencia_codigo),0)+1 id from ca_asistencias ";
    $ssql .=" where empleado_codigo=" . $this->empleado_codigo;
    $cn=$this->getMyConexionADO();
    $rs= $cn->Execute($ssql);
    $this->asistencia_codigo = $rs->fields[0];

    //-- obtener area_codigo del empleado
    $ssql="select area_codigo from empleado_area where empleado_codigo= " . $this->empleado_codigo . " and empleado_area_activo=1";
    $rsa= $cn->Execute($ssql);
    if ($rsa->EOF){
        $this->area_codigo = 'null';
    }else{
        $this->area_codigo = $rsa->fields[0];
    }

    if($incidencia_codigo==42 || $incidencia_codigo==66 || $incidencia_codigo==176){
        $sql =" insert into ca_asistencias";
        $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Asistencia_Entrada,ip_entrada,Turno_Codigo,ca_estado_codigo,fecha_reg_entrada,fecha_reg, area_codigo ) ";
        $sql .=" select " . $this->empleado_codigo . ",". $this->asistencia_codigo . ",CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103), ";
        $sql .=" CONVERT(DATETIME, CONVERT(varchar(10),'" . $this->asistencia_fecha . "', 103) + ' ' + cast(ca_turnos.turno_hora_inicio AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_inicio AS varchar(2)), 103),";
        $sql .=" '" . $this->ip_entrada . "',ca_turnos.turno_codigo,1,CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103),getdate(), " . $this->area_codigo;
        $sql .=" from empleados inner join ca_turnos on ca_turnos.turno_codigo=empleados.turno_codigo ";
        $sql .=" where Empleado_Codigo=?";

    }else{
        if($incidencia_codigo==11){
            $ssql="SELECT ct.turno_duo ";
            $ssql .=" FROM  empleados e ";
            $ssql .=" inner join  ca_turnos ct on ct.turno_codigo=e.turno_codigo ";
            $ssql .= " WHERE e.Empleado_Codigo = " . $this->empleado_codigo;
            $rs= $cn->Execute($ssql);
            $duo= $rs->fields[0];
            //echo "duo :" . $duo;
            $sql =" insert into ca_asistencias";
            $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Asistencia_Entrada,fecha_reg_entrada,ip_entrada,Asistencia_Salida,fecha_reg_salida,ip_salida,Turno_Codigo,ca_estado_codigo,fecha_reg, area_codigo) ";
            $sql .=" select " . $this->empleado_codigo . ",". $this->asistencia_codigo . ",CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103), ";
            $sql .=" CONVERT(DATETIME, CONVERT(varchar(10),'" . $this->asistencia_fecha . "', 103) + ' ' + cast(ca_turnos.turno_hora_inicio AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_inicio AS varchar(2)), 103),";
            $sql .=" CONVERT(DATETIME, CONVERT(varchar(10),'" . $this->asistencia_fecha . "', 103) + ' ' + cast(ca_turnos.turno_hora_inicio AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_inicio AS varchar(2)), 103),";
            $sql .=" '" . $this->ip_entrada . "',";

        if ($duo==0){
            $sql .="  CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103), ";
            $sql .="  CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103), ";
        }

        if ($duo==1){
            $sql .="  dateadd(day,1,CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103)), ";
            $sql .="  dateadd(day,1,CONVERT(datetime,'" . $this->asistencia_fecha . "'  + ' ' + cast(ca_turnos.turno_hora_fin AS varchar(2)) + ':' + cast(ca_turnos.turno_minuto_fin AS varchar(2)), 103)), ";
        }

        $sql .=" '".$this->ip_salida."',ca_turnos.turno_codigo,1,getdate(), ".$this->area_codigo;
        $sql .=" from empleados inner join ca_turnos on ca_turnos.turno_codigo=empleados.turno_codigo ";
        $sql .=" where Empleado_Codigo=?";

        }else{
            //bitacora
            //bitacora1
            $sql =" insert into ca_asistencias";
            $sql .=" (Empleado_codigo, Asistencia_codigo,Asistencia_Fecha,Turno_Codigo,ca_estado_codigo,fecha_reg_entrada,fecha_reg, area_codigo ) ";
            $sql .=" select " . $this->empleado_codigo . "," . $this->asistencia_codigo . ",CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103),";
            $sql .=" turno_codigo,1,CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103),getdate(), " . $this->area_codigo;
            $sql .=" from empleados where Empleado_Codigo=?";
        }
    }

    $params=array($this->empleado_codigo);

    $r=$cn->Execute($sql,$params);

    if(!$r){
        $rpta = "Error al Insertar nueva asistencia.";
        $cn->RollbackTrans();
        return $rpta;
    }else{
        $rpta= "OK";
    }

    if($supervisores_codigos!=''){
        //bitacora
        //bitacora1
        $sql =" insert into ca_asistencia_responsables ";
        $sql .=" (Empleado_codigo, Responsable_codigo,Asistencia_Codigo,fecha_reg, area_codigo_responsable) ";
        $sql .=" select " . $this->empleado_codigo . ",e.empleado_codigo," . $this->asistencia_codigo . " ,getdate(), ea.area_codigo ";
        $sql .=" from empleados e inner join empleado_area ea on e.empleado_codigo = ea.empleado_codigo  ";
        $sql .=" where e.empleado_codigo in (".$supervisores_codigos.") and ea.empleado_area_activo=1";

        $r=$cn->Execute($sql);
        if(!$r){
            $rpta = "Error al Insertar supervisores.";
            $cn->RollbackTrans();
            return $rpta;
        }else{
            $rpta= "OK";
        }

        if($incidencia_codigo==11){

            $rpta=$this->registrar_incidencia_horas();
        }

        $rs->close();
        $rsa->close();

        $rs=null;
        $rsa=null;

    }

    return $rpta;

 }

function listado_registros_dia(){
$cadena="";
$rpta=$this->conectarme_ado();
if($rpta=="OK"){
	$ssql="SELECT asistencia_codigo, convert(varchar(5),asistencia_entrada, 108) as entrada, convert(varchar(5),asistencia_salida, 108) as salida ";
	$ssql.=  " FROM ca_asistencias ";
	$ssql.=  " WHERE (Empleado_Codigo = " . $this->empleado_codigo . ") AND Asistencia_Salida is not null AND ";
	$ssql.=  "     (Asistencia_Fecha = CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103)) and ca_estado_codigo<>2";
	$ssql.=  " Order by 2 desc ";
	$rs = $this->cnnado->Execute($ssql);
	if(!$rs->EOF()) {
		$cadena = "<tr>\n";
		$cadena .="<td colspan=8 class=dataTd align='center'>\n";
		$cadena .="		<ol>\n";
		while(!$rs->EOF()){
			$cadena .= "			<li>Hora Entrada: " . $rs->fields[1]->value . " Salida: " . $rs->fields[2]->value. "</li>\n";
			$rs->movenext();
		}
		$cadena .= "		</ol>\n";
		$cadena .= "	</td>\n";
		$cadena .= "</tr>\n";
	}
	$rs->close();
	$rs=null;
}
	return $cadena;
}


function valida_marca_entrada(){
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql =" select asistencia_fecha from CA_Asistencias ";
        $ssql.=" where Empleado_Codigo = ".$this->empleado_codigo." ";
        $ssql.=" and Asistencia_fecha=CONVERT(datetime,'".$this->asistencia_fecha."',103) and ca_estado_codigo=1 ";
        $ssql.=" union ";
        $ssql.=" select fecha_feriado from ca_feriados ";
        $ssql.=" where fecha_Feriado=CONVERT(datetime,'".$this->asistencia_fecha."',103) and Feriado_Activo=1 and Tipo_Feriado=1";
        $rs = $cn->Execute($ssql);
        //echo $ssql;
        if(!$rs->EOF) $k=1;
        else $k=0;
    }
    return $k;
}


function get_Asistencia_Fecha(){
    $cn=$this->getMyConexionADO();
    $fecha="";
    if($cn){
        $ssql=" select CONVERT(varchar(10),Asistencia_fecha,103) as asistencia_fecha ";
	$ssql.=" from CA_Asistencias where Empleado_Codigo = ".$this->empleado_codigo." and Asistencia_codigo = ".$this->asistencia_codigo." ";
        $rs = $cn->Execute($ssql);
        if(!$rs->EOF){
            $fecha=$rs->fields[0];
        }
        $rs=null;
    }
    return $fecha;
}

function saldo_tiempo($campo){
    $cn=$this->getMyConexionADO();
    $this->saldo_tiempo=0;
    $this->turno_diario=0;
    $this->diario_horas=0;
    $this->diario_minutos=0;
    if($cn){
        //mcortezc@atentoperu.com.pe
        //Ajustes a eventos y registro de incidencias
        $ssql=" select saldo_tiempo ";
        //$ssql.=" ,isnull(turno_minutos,0)-isnull(Turno_Refrigerio,0) as turno_diario ";
        //$ssql.=" ,cast(( (isnull(turno_minutos,0)-isnull(Turno_Refrigerio,0)) /60) as int ) as diario_horas ";
        //$ssql.=" ,(isnull(turno_minutos,0)-isnull(Turno_Refrigerio,0))-( cast(( (isnull(turno_minutos,0)-isnull(Turno_Refrigerio,0)) /60) as int ) *60 ) as diario_minutos ";
        //$ssql.=" ,isnull(turno_minutos,0) as turno ";
        //$ssql.=" ,isnull(Turno_Refrigerio,0) as refrigerio ";
        $ssql.=" ,isnull(turno_diario,0)-isnull(Turno_Refrigerio,0) as turno_diario ";
        $ssql.=" ,cast(( (isnull(turno_diario,0)-isnull(Turno_Refrigerio,0)) /60) as int ) as diario_horas ";
        $ssql.=" ,(isnull(turno_diario,0)-isnull(Turno_Refrigerio,0))-( cast(( (isnull(turno_diario,0)-isnull(Turno_Refrigerio,0)) /60) as int ) *60 ) as diario_minutos ";
        $ssql.=" ,isnull(turno_diario,0) as turno ";
        $ssql.=" ,isnull(Turno_Refrigerio,0) as refrigerio ";
        $ssql.=" from vCA_TiempoJornadaIncidencia where empleado_codigo = ? ";

        if($campo==1) $ssql.=" and asistencia_codigo = ".$this->asistencia_codigo." ";
        if($campo==2) $ssql.=" and asistencia_fecha=CONVERT(datetime,'".$this->asistencia_fecha."',103)";
        $params=array(
            $this->empleado_codigo
        );
        $rs=$cn->Execute($ssql,$params);
        if(!$rs->EOF){
            $this->saldo_tiempo=$rs->fields[0];
            $this->turno_diario=$rs->fields[1];
            $this->diario_horas=$rs->fields[2];
            $this->diario_minutos=$rs->fields[3];
        }else{
            $this->saldo_tiempo=999999;
        }
    }
    $rs=null;
}


function tiempo_disponible($tiempo){
    $t="";
    $hhor=intval($tiempo/60);//horas enteras
    $mmin=round(round(($tiempo/60)-$hhor,2)*60);//minutos
    if($mmin<0 || $hhor<0){
        $t="-".((abs($hhor)*1<10)?'0'.abs($hhor):abs($hhor)).":".((abs($mmin)*1<10)?'0'.abs($mmin):abs($mmin));
    }else{
        $t=(($hhor*1<10)?'0'.$hhor:$hhor).":".(($mmin*1<10)?'0'.$mmin:$mmin);
    }
    return $t;
}

function minutos_turno(){
    $cn=$this->getMyConexionADO();
    $this->turno_diario=0;
    $this->diario_horas=0;
    $this->diario_minutos=0;
    if($cn){
        //mcortezc@atentoperu.com.pe
        //Ajustes a eventos y registro de incidencias
        /*$ssql=" select (Turno_Horas*60)+Turno_Minutos as minutos_turno ";
        $ssql.= " ,((Turno_Horas*60)+Turno_Minutos)-Turno_Refrigerio as turno_diario ";
        $ssql.= " ,cast(( (isnull(((Turno_Horas*60)+Turno_Minutos),0)-isnull(Turno_Refrigerio,0)) /60) as int ) as diario_horas ";
        $ssql.= " ,(isnull(((Turno_Horas*60)+Turno_Minutos),0)-isnull(Turno_Refrigerio,0))-( cast(( (isnull(((Turno_Horas*60)+Turno_Minutos),0)-isnull(Turno_Refrigerio,0)) /60) as int ) *60 ) as diario_minutos ";
        $ssql.= " from vCA_TurnosTH where Turno_Codigo = ? ";*/

        $ssql=" select (Turno_Horas*60)+Turno_Minutos as minutos_turno ";
        $ssql.=" ,(Turno_Hora_Inicio*60)+Turno_Minuto_Inicio as turno_inicio ";
        $ssql.=" ,(Turno_Hora_Fin*60)+Turno_Minuto_Fin as turno_fin ";
        $ssql.=" ,((Turno_Horas*60)+Turno_Minutos)-Turno_Refrigerio as turno_diario ";
        $ssql.=" ,cast(( (isnull(((Turno_Horas*60)+Turno_Minutos),0)-isnull(Turno_Refrigerio,0)) /60) as int ) as diario_horas ";
        $ssql.=" ,(isnull(((Turno_Horas*60)+Turno_Minutos),0)-isnull(Turno_Refrigerio,0))-( cast(( (isnull(((Turno_Horas*60)+Turno_Minutos),0)-isnull(Turno_Refrigerio,0)) /60) as int ) *60 ) as diario_minutos ";
        $ssql.=" from vCA_TurnosTH where Turno_Codigo = ? ";

        $params=array(
            $this->turno_codigo
        );
        $rs=$cn->Execute($ssql,$params);
        if(!$rs->EOF){
            /*$this->minutos_turno=$rs->fields[0];
            $this->turno_diario=$rs->fields[1];
            $this->diario_horas=$rs->fields[2];
            $this->diario_minutos=$rs->fields[3];*/

            $this->minutos_turno=$rs->fields[0];
            $this->tt_inicio=$rs->fields[1];
            $this->tt_final=$rs->fields[2];
            $this->turno_diario=$rs->fields[3];
            $this->diario_horas=$rs->fields[4];
            $this->diario_minutos=$rs->fields[5];
        }
    }
    $rs=null;
}



function obtnerTurnoInicioFin(){
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    if($cn){
        $ssql=" select (Turno_Horas*60)+Turno_Minutos as minutos_turno ";
        $ssql.=" ,(Turno_Hora_Inicio*60)+Turno_Minuto_Inicio as turno_inicio ";
        $ssql.=" ,(Turno_Hora_Fin*60)+Turno_Minuto_Fin as turno_fin ";
        $ssql.=" from vCA_TurnosTH where Turno_Codigo = ? ";
        $params=array(
            $this->turno_codigo
        );
        $rs=$cn->Execute($ssql,$params);
        if(!$rs->EOF){
            $this->minutos_turno=$rs->fields[0];
            $this->tt_inicio=$rs->fields[1];
            $this->tt_final=$rs->fields[2];
        }
    }
    $rs=null;
}



function sel_listado_registros_dia($area,$servicio,$turno,$tipo_area_codigo){

    $lista="";
    $rpta="OK";

    $o=new ca_asistencia_responsables();
    $o->setMyUrl($this->getMyUrl());
    $o->setMyUser($this->getMyUser());
    $o->setMyPwd($this->getMyPwd());
    $o->setMyDBName($this->getMyDBName());

    $ob=new ca_eventos();
    $ob->setMyUrl($this->getMyUrl());
    $ob->setMyUser($this->getMyUser());
    $ob->setMyPwd($this->getMyPwd());
    $ob->setMyDBName($this->getMyDBName());

    $ot=new ca_tiempos();
    $ot->setMyUrl($this->getMyUrl());
    $ot->setMyUser($this->getMyUser());
    $ot->setMyPwd($this->getMyPwd());
    $ot->setMyDBName($this->getMyDBName());

    $combo = new MyCombo();
    $combo->setMyUrl($this->getMyUrl());
    $combo->setMyUser( $this->getMyUser());
    $combo->setMyPwd( $this->getMyPwd());
    $combo->setMyDBName($this->getMyDBName());

    $e=new ca_empleados();
    $e->setMyUrl($this->getMyUrl());
    $e->setMyUser($this->getMyUser());
    $e->setMyPwd($this->getMyPwd());
    $e->setMyDBName($this->getMyDBName());

    $cn=$this->getMyConexionADO();

    $ssql="SELECT asistencia_codigo, convert(varchar(10),asistencia_entrada, 108) as entrada, convert(varchar(10),asistencia_salida, 108) as salida, ";
    $ssql.=  " ca_turnos.turno_descripcion,ca_turnos.turno_codigo , ";
    $ssql.=  "		case when exists(select * from ca_asistencia_responsables ";
    $ssql.=  "          where empleado_codigo=ca_asistencias.empleado_codigo ";
    $ssql.=  "             and asistencia_codigo=ca_asistencias.asistencia_codigo ";
    $ssql.=  "             and responsable_codigo=" . $this->responsable_codigo . " ";
    $ssql.=  " 	)  then 1 else 0 end as r , ";
    $ssql.=  " case when asistencia_entrada is not null then 1 else 0 end as f";
    $ssql.=  " FROM ca_asistencias ";
    $ssql.=  " left outer join ca_turnos on ca_turnos.turno_codigo=ca_asistencias.turno_codigo ";
    $ssql.=  " WHERE (Empleado_Codigo = " . $this->empleado_codigo . ") AND ";
    $ssql.=  "     (Asistencia_Fecha = CONVERT(DATETIME,'" . $this->asistencia_fecha . "', 103)) ";
    $ssql.=  " AND ca_estado_codigo=1 ";
    $ssql.=  " Order by asistencia_entrada desc ";

    $rs = $cn->Execute($ssql);
    if(!$rs->EOF) {
        $i=0;
        while(!$rs->EOF){
            $i+=1;
            $lista .= "<table class='FormTable' width='95%' align='center' border='0' cellspacing='1' cellpadding='0'>\n";
            $lista .= "<tr>\n";
            //$lista .="    <td class=ColumnTD align='center'>\n";
            //$lista .="	    Sel";
            //$lista .="	  </td>\n";
            $lista .="    <td class=ColumnTD align='center'>\n";
            $lista .="      Codigo";
            $lista .= "	  </td>\n";
            $lista .="    <td class=ColumnTD align='center'>\n";
            $lista .="    Entrada\n";
            $lista .= "	   </td>\n";
            $lista .="    <td class=ColumnTD align='center'>\n";
            $lista .="     Salida";
            $lista .= "	   </td>\n";
            $lista .="    <td class=ColumnTD  align='center'>\n";
            $lista .="      Turno Registrado";
            $lista .= "	   </td>\n";
            $lista .= "</tr>\n";
            $lista .= "<tr >\n";
            $lista .="    <td   class='DataTD' align='center' style='display:none;'>\n";
            $lista .="	    <input type='radio' id='rdo' name='rdo' value='". $rs->fields[0]."_" . $rs->fields[5] ."' style='cursor:hand' checked>\n";
            $lista .="	  </td>\n";
            $lista .="    <td   class='DataTD'  align='center'>\n";
            $lista .="      <input class='input' type='text' style='width:40px' id='txtCodigo_" . $rs->fields[0] . "'  name='txtCodigo_" . $rs->fields[0]  . "'  value='" . $rs->fields[0] . "' readonly>\n";
            $lista .= "	  </td>\n";
            $lista .="    <td   class='DataTD'  align='center'>\n";
            $lista .="    <input class='input' type='text'  style='width:80px' id='txtEntrada_" .$rs->fields[0] . "'  name='txtEntrada_" . $rs->fields[0]  . "'  value='" . $rs->fields[1] . "' readonly>\n";
            $lista .= "	   </td>\n";
            $lista .="    <td   class='DataTD'  align='center'>\n";
            $lista .="    <input class='input' type='text'  style='width:80px' id='txtSalida_" . $rs->fields[0] . "'  name='txtSalida_" . $rs->fields[0]. "'  value='" . $rs->fields[2]  . "' readonly>\n";
            $lista .= "	   </td>\n";
            //echo $turno;
            //echo $tipo_area_codigo;
            if($turno== $rs->fields[4]){
                $lista .="    <td id='tdc' class='DataTD'  align='center'>\n";
            }else{
                $lista .="    <td id='tdc' bgcolor=#FF3300 align='center'>\n";
            }

            if (!$e->Query_Area_Piloto($area)){
                $sql="SELECT turno_codigo as codigo,turno_descripcion  AS descripcion FROM ca_turnos ";
                $sql .=" WHERE turno_activo=1 and tipo_area_codigo='" . $tipo_area_codigo . "' order by 2 asc";

            }else{

                $sql="SELECT turno_codigo as codigo,turno_descripcion  AS descripcion FROM ca_turnos ";
                $sql .=" WHERE turno_activo=1 and turno_codigo='" . $turno . "' order by 2 asc";
            }

            $combo->query = $sql;
            $combo->name = "turno_codigo_" . $rs->fields[0] . "";
            $combo->value = $rs->fields[4]. "";
            $combo->more = " class='select' onchange=verificar(this.value)";

            //echo $sql;

            $rpta = $combo->Construir();
            $lista .=$rpta;

            $lista .= "	   </td>\n";
            $lista .= "</tr>\n";
            $lista .= "<tr >\n";
            $lista .="    <td  colspan='5'>\n";
            $lista .= "    <table width='100%' align='center' border='0'>\n";
            $o->empleado_codigo=$this->empleado_codigo;
            $o->asistencia_codigo=$rs->fields[0];
            $o->responsable_codigo=$this->responsable_codigo;

            $lista .=$o->Listar_responsables_asistencia();
            $lista .= "     </table>\n";
            $lista .= "	   </td>\n";
            $lista .= "</tr>\n";
            /*hourminute
            $lista .="<tr>\n";
            $lista .="  <td colspan='5'>\n";
            $lista .= "    <table width='100%' align='center' border='1' cellspacing='1'>\n";
            $lista .= "      <tr>\n";
            $lista .= "        <td class='ColumnTD' align='center' colspan='5'>Eventos Registrados";
            $lista .= "        </td>\n";
            $lista .= "     </tr>\n";
             */

            $ob->empleado_codigo=$this->empleado_codigo;
            $ob->asistencia_codigo=$rs->fields[0];
            $ob->responsable_codigo=$this->responsable_codigo;

            /*hourminute
            $lista .=$ob->Listar_eventos_asistencia($area,$servicio);
            $lista .= "    </table><br>\n";
            $lista .="  </td>\n";
            $lista .="</tr>\n";
            $lista .= "</table>\n";
            $lista .= "<br>\n";
            $lista .= "    <table class='Formtable' width='95%' align='center' border='1' cellspacing='3'>\n";
            $lista .= "      <tr>\n";
            $lista .= "        <td class='ColumnTD' align='center' colspan='5'>Tiempos de Conexión Registrados por Responsable";
            $lista .= "        </td>\n";
            $lista .= "     </tr>\n";*/


            $ot->empleado_codigo=$this->empleado_codigo;
            $ot->tiempo_fecha=$this->asistencia_fecha;
            $ot->responsable_codigo=$this->responsable_codigo;
            /*hourminute
            $lista .=$ot->Listar_tiempos();
            $lista .= "</table>\n";*/

            //////

            //eventos validables registrados por dia
            $lista .= "<br>\n";
            $lista .= "    <table class='Formtable' width='95%' align='center' border='1' cellspacing='3'>\n";
            $lista .= "      <tr>\n";
            $lista .= "        <td class='ColumnTD' align='center' colspan='8'>Eventos  válidables";
            $lista .= "        </td>\n";
            $lista .= "     </tr>\n";

            $ob->responsable_codigo=$this->responsable_codigo;
            $ob->empleado_codigo=$this->empleado_codigo;
            $ob->asistencia_codigo=$rs->fields[0];

            $lista .=$ob->listar_eventos_validables();

            $lista .= "</table>\n";
            //eventos validables registrados por dia



            $rs->MoveNext();

        }
        $this->num_asistencias=$i;
    }else{
        $lista .= "<table width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>\n";
        $lista .= " <tr>\n";
        $lista .= "   <td align='center' colspan='5'><b>No hay registros de asistencias activas al momento!!<b>\n";
        $lista .= "	 </td>\n";
        $lista .= " </tr>\n";
        $lista .= "</table><br><br><br>\n";
        $lista .= "    <table class='Formtable' width='95%' align='center' border='0' cellspacing='3'>\n";
        $lista .= "      <tr>\n";
        $lista .= "        <td class='ColumnTD' align='center' colspan='5'>Tiempos de Conexión Registrados";
        $lista .= "        </td>\n";
        $lista .= "     </tr>\n";
        $ot->empleado_codigo=$this->empleado_codigo;
        $ot->tiempo_fecha=$this->asistencia_fecha;
        $ot->responsable_codigo=$this->responsable_codigo;
        $lista .=$ot->Listar_tiempos();
        $lista .= "</table>\n";
        $this->num_asistencias=0;
    }

	$rs->close();
	$rs=null;

	$o=null;
	$ob=null;
	$ot=null;
	$combo=null;

	return $lista;

}

function actualizar_turno(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql ="update ca_asistencias set turno_codigo=" . $this->turno_codigo . ",";
    $ssql .="  empleado_modifica_turno=" . $this->empleado_modifica_turno . ",";
    $ssql .="  fecha_reg_modifica_turno=getdate() ";
    $ssql .="  where Empleado_Codigo=?";
    $ssql .="  and Asistencia_Codigo=?";

    $params=array(
        $this->empleado_codigo,
        $this->asistencia_codigo
    );

    $r=$cn->Execute($ssql,$params);

    if(!$r){
        $rpta = "Error al Actualizar turno en la asistencia.";
        return $rpta;
    }else{
        $rpta= "OK";
    }

    $cn=null;
    return $rpta;
 }

function anular_asistencia(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql ="update ca_asistencias set ca_estado_codigo=2 ";
    $ssql .="  where Empleado_Codigo=?";
    $ssql .="  and Asistencia_Codigo=?";

    $params=array(
        $this->empleado_codigo,
        $this->asistencia_codigo
    );

    $r=$cn->Execute($ssql,$params);
    if(!$r){
        $rpta = "Error al Anular asistencia.";
        return $rpta;
    }else{
        $rpta= "OK";
    }

    $cn=null;
    return $rpta;

 }

   function validar_asistencia(){
    $codigo=0;
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    $params = array( $this->empleado_codigo);
	if($cn){
        $ssql="SELECT asistencia_codigo,asistencia_entrada,asistencia_salida ";
    	$ssql .=" FROM ca_asistencias (nolock)";
    	$ssql .=" WHERE Empleado_Codigo = ?";
        $ssql .="   AND asistencia_fecha=convert(datetime,convert(varchar(10),getdate(),103),103) ";
        $ssql .="   AND ca_estado_codigo=1 ";
        $ssql .="   AND Asistencia_Salida is not null";
        //echo $ssql;
    	$rs = $cn->Execute($ssql, $params);
    		//if($rs) echo "Error";
    	  if (!$rs->EOF){
    			$codigo=1;
    	  }else{
    		   $codigo=0;
    	  }
    	  $rs->close();
    	  $rs=null;
 	 }
    return $codigo;
}

   function escribir_salida($dia,$mes,$anio){
	$mensaje="";
	$archivo="d:\\ApacheGroup\Apache2\htdocs\sistemarrhh\ControlAsistencia\logs\log_salida_asistencia" . $dia . $mes .  $anio . ".txt";
    $mensaje =" Ocurrio un error en Asistencias en el cual Entrada: " . $this->asistencia_entrada . " es menor o igual a Salida:" . $this->asistencia_salida . "\n";
	$mensaje .=" Parametros : empleado_codigo :" . $this->empleado_codigo . ",asistencia_codigo :" . $this->asistencia_codigo . "\n";

	$o=new writefile();

	$o->archivo=$archivo;
	$o->mensaje=$mensaje;
	$o->escribir();
 }

 function verificar_incidencia_vacaciones(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
 if($rpta=="OK"){
    $ssql="SELECT * from vCA_asistencia_incidencia_vacaciones ";
	$ssql .="  where empleado_codigo=". $this->empleado_codigo;
	$ssql .="  and asistencia_fecha = CONVERT(DATETIME,'" . $this->fecha . "',103)";
	//echo $ssql;
	$rs = $this->cnnado->Execute($ssql);
		if ($rs->EOF){
		    $this->vac=0;
	  }else{
		  $this->vac=1;
	  }
	  $rs->close();
	  $rs=null;
	 }
	return $rpta;*/

    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql="SELECT * from vCA_asistencia_incidencia_vacaciones ";
    $ssql .="  where empleado_codigo=". $this->empleado_codigo;
    $ssql .="  and asistencia_fecha = CONVERT(DATETIME,'" . $this->fecha . "',103)";
    $rs = $cn->Execute($ssql);
    if ($rs->EOF){
        $this->vac=0;
    }else{
        $this->vac=1;
    }

    $rs->close();
    $rs=null;
    return $rpta;
}

function verificar_turno(){
 /*$rpta="OK";
 $rpta=$this->conectarme_ado();
	 if($rpta=="OK"){
	    $ssql="SELECT isnull(turno_codigo,0) as turno from empleados ";
		$ssql .="  where empleado_codigo=". $this->empleado_codigo;
		$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			if($rs->fields[0]->value==0)
			    $this->turno=1;
			else
				$this->turno=0;
		 }
	  $rs->close();
  	  $rs=null;
	 }
  return $rpta;*/
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql="SELECT isnull(turno_codigo,0) as turno from empleados ";
    $ssql .="  where empleado_codigo=". $this->empleado_codigo;
    $rs = $cn->Execute($ssql);
    if(!$rs->EOF){
        if($rs->fields[0]==0)
            $this->turno=1;
        else
            $this->turno=0;
    }

    $rs->close();
    $rs=null;
    return $rpta;

 }

function verificar_turno_programado($fecha){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql =" SELECT isnull(turno_codigo,0) as turno from vca_turnos_programado_ultimo ";
		$ssql.=" where empleado_codigo=".$this->empleado_codigo." ";
		$ssql.=" and fechap=convert(datetime,'".$fecha."',103) ";
		$rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			if($rs->fields[0]->value==0)
				$this->turno=1;
			else
				$this->turno=0;
		}else{
			$this->turno=1;
		}
		$rs->close();
		$rs=null;
	}
	return $rpta;*/

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql =" SELECT isnull(turno_codigo,0) as turno from vca_turnos_programado_ultimo ";
    $ssql.=" where empleado_codigo=".$this->empleado_codigo." ";
    $ssql.=" and fechap=convert(datetime,'".$fecha."',103) ";
    $rs = $cn->Execute($ssql);
    if (!$rs->EOF){
        if($rs->fields[0]==0)
            $this->turno=1;
        else
            $this->turno=0;
    }else{
        $this->turno=1;
    }
    $rs->close();
    $rs=null;
    return $rpta;

}

function update_turno_empleado_fecha($fecha){


	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta="OK"){
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;
	    $ssql ="update empleados set turno_codigo=case when vtp.turno_codigo=0 then null else vtp.turno_codigo end";
		$ssql.=" from vca_turnos_programado_ultimo vtp ";
	    $ssql.=" where empleados.empleado_codigo=vtp.empleado_codigo ";
	    $ssql.=" and empleados.empleado_codigo=? ";
	    $ssql.=" and vtp.fechap=convert(datetime,'".$fecha."',103) ";

		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$r=$cmd->Execute();
		if(!$r){
		    $rpta = "Error al actualizar turno a empleado";
			return $rpta;
		}else{
			$rpta= "OK";
		}
	    $cmd=null;
		return $rpta;
	}*/



    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="update empleados set turno_codigo=case when vtp.turno_codigo=0 then null else vtp.turno_codigo end";
    $ssql.=" from vca_turnos_programado_ultimo vtp ";
    $ssql.=" where empleados.empleado_codigo=vtp.empleado_codigo ";
    $ssql.=" and empleados.empleado_codigo=? ";
    $ssql.=" and vtp.fechap=convert(datetime,'".$fecha."',103) ";
    $params=array(
        $this->empleado_codigo
    );
    $r=$cn->Execute($ssql,$params);
    if(!$r){
        $rpta = "Error al actualizar turno a empleado";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    return $rpta;
}

function update_turno_empleado_hoy(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta="OK"){
	    $cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;
	    $ssql ="update empleados set turno_codigo=case when vtp.turno_codigo=0 then null else vtp.turno_codigo end";
		$ssql.=" from vca_turnos_programado_ultimo vtp ";
	    $ssql.=" where empleados.empleado_codigo=vtp.empleado_codigo ";
	    $ssql.=" and empleados.empleado_codigo=? ";
	    $ssql.=" and vtp.fechap=(select CONVERT(DATETIME,CONVERT(varchar(10),getdate(),103),103)) ";

		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$r=$cmd->Execute();
		if(!$r){
		    $rpta = "Error al actualizar turno a empleado";
			return $rpta;
		}else{
			$rpta= "OK";
		}
	    $cmd=null;
		return $rpta;
	} */

    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $ssql ="update empleados set turno_codigo=case when vtp.turno_codigo=0 then null else vtp.turno_codigo end";
    $ssql.=" from vca_turnos_programado_ultimo vtp ";
    $ssql.=" where empleados.empleado_codigo=vtp.empleado_codigo ";
    $ssql.=" and empleados.empleado_codigo=? ";
    $ssql.=" and vtp.fechap=(select CONVERT(DATETIME,CONVERT(varchar(10),getdate(),103),103)) ";
    $params=array(
       $this->empleado_codigo
    );
    $r=$cn->Execute($ssql,$params);
    if(!$r){
        $rpta = "Error al actualizar turno a empleado";
        return $rpta;
    }else{
        $rpta= "OK";
    }
    return $rpta;
}

function Consulta_biometrico($l_ip){
	$rpta="";
    $cn=$this->getMyConexionADO();
	$msj="";
    //$cn->debug=true;
    $cn->Execute("SET ANSI_WARNINGS ON");
    $cn->Execute("SET ANSI_NULLS ON");
	$ssql="EXEC dbo.sp_Consulta_Biometrica ?,?,?";
    $params=array($this->empleado_codigo,$this->tiempo_permitido_marca_ingreso,$l_ip);
    $rs=$cn->Execute($ssql,$params);
	if(!$rs->EOF) {
	 	$valores = explode('|', $rs->fields[0]);
		//echo "valores[0]:".$valores[0].",valores[1]:".$valores[1];
		if($valores[0]== '0')
			$rpta = $valores[1];
		return $rpta;
    }

    return $rpta;
}

/*function Consulta_biometrico($l_ip){
	$rpta="";
    $cn=$this->getMyConexionADO();
	$msj="";
	//Verifica si tiene IP la máquina
	if($l_ip <> ''){
		//Consultando si hay PANICO
	    $text="exec sp_Consulta_Panico ?,?";
	    $params=array($l_ip,$this->empleado_codigo);
	    $rs=$cn->Execute($text,$params);
		if(!$rs->EOF){
			//El Resultado indicará si
			// 0 No hay panico activo, por lo tanto validar biometrico
			// 1 Existe pánico, por lo tanto dejará pasar
			if($rs->fields[0]== 1){
				//el sistema se encuentra en pánico, deja pasar
				return $rpta;
			}
		}
	}

	//CONSULTANDO SI HAY TURNO
    $params=array($this->empleado_codigo);
    $ssql = "SELECT	SUBSTRING(CONVERT(VARCHAR, turno_inicio, 14),1,8) as turno from ca_asistencia_programada (nolock)";
    $ssql.= "where empleado_codigo= ? and asistencia_fecha= convert(datetime,convert(varchar(10),GETDATE(), 103), 103) ";
    $r=$cn->Execute($ssql,$params);
	$this->turno = $r->fields[0];

	//Si el empleado no tiene turno asignado, no podrá validar Biometrico, por lo tanto dejará pasar
    if($this->turno ==''){
    	 //"No tiene turno, dejar pasar";
    	 return $rpta;
	}



	//Validar si el empleado está adentro de la empresa
	$params = array($this->empleado_dni);
	$ssql = "SELECT	COUNT(*) from vUltimo_Biometrico where BADGENUMBER =  ?  and convert(varchar(10),ultEntrada,121) = convert(varchar(10),getdate(),121) and Presente = 1";
	$rs=$cn->Execute($ssql,$params);
	//$rs->fields[0] = 0 No Presente, su ultima salida es mayor a la entrada ó su entrada no corresponde al dia actual, no dejar pasar
	//$rs->fields[0] = 1 Presente
	if ($rs->fields[0] == 0){ // NO PRESENTE, NO DEJARA PASAR
		$rpta = "El Sistema Biométrico reporta que el usuario no está presente.";// Su última Entrada fue ".$res->fields[0] ." y salida ".$res->fields[1];
		return $rpta;
	}

	//tiempo permitido para marcar antes del inicio de turno
	// asesor 30 minutos
	// otros(supervisor) sin tiempo

	//validar si es asesor
	$params = array($this->empleado_codigo);
	$ssql = "select vDatos.Empleado_Codigo FROM vDatos inner join vRac on vDatos.cargo_codigo = vRac.Cargo_Codigo ";
	$ssql.= " where vDatos.Empleado_Codigo = ?";
	$r=$cn->Execute($ssql,$params);
	//Si no hay resultado inidica que  No es RAC, por lo tanto no se le valida tiempo de marcación, deja pasar
	//si hay resultado no entra acá, valida el tiempo de 30 minutos(continua)
	if($r->EOF) {//if($rs->RecordCount()==0){
		//no hay resultado, no es RAC, deja pasar
		return $rpta;
    }


    //CONSULTANDO SI LA HUELLA BIOMETRICA ESTA DENTRO DEL TURNO DEL EMPLEADO RAC
    $ssql = "SELECT	* ";
	$ssql.=" FROM	CHECKINOUT CH ";
	$ssql.=" WHERE	CH.BADGENUMBER = ?";
	$ssql.=" AND CH.CHECKTYPE =	'I' ";
	$ssql.=" AND CH.CHECKTIME >= DATEADD(minute, ?*-1, ";
	$ssql.=" 	( ";
	$ssql.=" 	select turno_inicio ";
	$ssql.=" 		from ca_asistencia_programada ";
	$ssql.=" 	where empleado_codigo= ?";
	$ssql.=" 	and asistencia_fecha= convert(datetime,convert(varchar(10),GETDATE(), 103), 103) ";
	$ssql.=" 	) ";
	$ssql.=" )    ";

    $params=array(
       $this->empleado_dni,
       $this->tiempo_permitido_marca_ingreso,
       $this->empleado_codigo
    );
	$rs=$cn->Execute($ssql,$params);
	//Si el resultado obtiene registro, quiere decir que SI tiene registro biometrico dentro del tiempo permitido antes de su turno inicial
	//Si el resultado no obtiene registro, quiere decir que NO tiene registro biometrico dentro del tiempo permitido antes de su turno inicial
    if($rs->EOF) {//if($rs->RecordCount()==0){
		$rpta ="Su turno empieza a las ".$this->turno.", marque biometrico hasta ".$this->tiempo_permitido_marca_ingreso." minutos antes que inicie este turno.";
		//no hay huella biometrica dentro del turno inicial, por lo tanto no dejará pasar
    }
    return $rpta;
}*/

function Eliminar_Salida(){
    $rpta="OK";
    $cn=$this->getMyConexionADO();
    $msj="";
    if($cn){
        $text="exec sp_CA_eliminar_asistencia_salida ?";
        $params=array($this->empleado_codigo);
        $rs=$cn->Execute($text,$params);
        if(!$rs){
            $rpta = "Error";
        }
    }

    return $rpta;
}

function Dia_Actual(){
    $rpta=Array();
    $cn=$this->getMyConexionADO();
    $msj="";
    if($cn){
        $text="SELECT CONVERT(VARCHAR(10),GETDATE(),103), CONVERT(VARCHAR(8),GETDATE(),112) ";
        $rs=$cn->Execute($text);
        if(!$rs){
            $rpta = Array('Error');
        }else{
            $rpta = array($rs->fields[0],$rs->fields[1]);
        }
    }

    return $rpta;
}
function empleado_rol_pagina(){
    $rpta = "";
	$cn = $this->getMyConexionADO();
    $params = array($this->empleado_codigo);
    //$ssql = "SELECT ISNULL('/sispersonal01'+ca_paginas.Pagina_Url,'') AS pagina_url
    $ssql = "SELECT ISNULL(ca_paginas.Pagina_Url,'') AS pagina_url
			FROM ca_pagina_rol
			INNER JOIN ca_paginas on ca_paginas.pagina_codigo=ca_pagina_rol.pagina_codigo
			WHERE ca_paginas.pagina_activo=1 and pagina_rol_activo=1 and rol_codigo in (select Rol_Codigo FROM dbo.CA_Empleado_Rol where Empleado_codigo = ? and empleado_rol_activo=1)
			and ca_paginas.pagina_codigo not in (0)
			GROUP BY pagina_url
            ORDER BY pagina_url";
    $rs = $cn->Execute($ssql, $params);

    if($rs){
        if($rs->RecordCount() > 0){
            $sCad = "";
            while(!$rs->EOF){
                if($sCad == ""){
                    $sCad = trim($rs->fields[0]);
                }else{
                    $sCad = $sCad."|".trim($rs->fields[0]);
                }
                $rs->MoveNext();
            }
            $rpta = $sCad;
        }else{
            $rpta = "No hay registros encontrados";
        }
    }else{
        $rpta = "Ocurrio un Error al consultar";
    }
    return $rpta;
}

//Obtener Tiempo permitido marca ingreso turno.
function tpmi(){
	$k="0";
    $cn=$this->getMyConexionADO();
    if($cn){
        $ssql =" select item_default from Items where Item_Codigo=517 ";
        $rs = $cn->Execute($ssql);
        if(!$rs->EOF) $k = $rs->fields[0];
        else $k = "0";
    }
    return $k;
}


}
?>
