<?php
require_once(PathIncludes() . "mantenimiento.php");

class ca_turnos_empleado extends mantenimiento{
    var $empleado_codigo="";
    var $te_semana="";
    var $tc_codigo="";
    var $te_fecha_inicio="";
    var $te_fecha_fin="";
    var $empleado_codigo_registro="";
    var $turno_Dia1=0;
    var $turno_Dia2=0;
    var $turno_Dia3=0;
    var $turno_Dia4=0;
    var $turno_Dia5=0;
    var $turno_Dia6=0;
    var $turno_Dia7=0;
    var $modalidad_codigo=0;
    var $cargo_codigo=0;
    var $area_codigo=0;
    var $nombres="";
    var $te_anio=0;
    var $tc_codigo_sap="";
    var $tc_activo="";
    var $turno_descripcion="";
    var $turno_limite="";
    var $empleado_dni="";
    var $carga_codigo="";
    var $te_aniomes="";
    var $thorase=0;
    var $thorasp=0;
    
    var $tturno_Dia1="";
    var $tturno_Dia2="";
    var $tturno_Dia3="";
    var $tturno_Dia4="";
    var $tturno_Dia5="";
    var $tturno_Dia6="";
    var $tturno_Dia7="";
    var $lturno_Dia1="";
    var $lturno_Dia2="";
    var $lturno_Dia3="";
    var $lturno_Dia4="";
    var $lturno_Dia5="";
    var $lturno_Dia6="";
    var $lturno_Dia7="";
    var $dturno_Dia1="";
    var $dturno_Dia2="";
    var $dturno_Dia3="";
    var $dturno_Dia4="";
    var $dturno_Dia5="";
    var $dturno_Dia6="";
    var $dturno_Dia7="";
    var $eturno_Dia1="";
    var $eturno_Dia2="";
    var $eturno_Dia3="";
    var $eturno_Dia4="";
    var $eturno_Dia5="";
    var $eturno_Dia6="";
    var $eturno_Dia7="";
    var $nturno_Dia1="";
    var $nturno_Dia2="";
    var $nturno_Dia3="";
    var $nturno_Dia4="";
    var $nturno_Dia5="";
    var $nturno_Dia6="";
    var $nturno_Dia7="";
    var $total_horas="";
    var $total_minutos="";
    var $hdia1 = "";
    var $hdia2 = "";
    var $hdia3 = "";
    var $hdia4 = "";
    var $hdia5 = "";
    var $hdia6 = "";
    var $hdia7 = "";
    var $dia_i = "";
    var $empleado_nombres = "";
    var $agrupacion_id = "";
    var $sturno_Dia1="";
    var $sturno_Dia2="";
    var $sturno_Dia3="";
    var $sturno_Dia4="";
    var $sturno_Dia5="";
    var $sturno_Dia6="";
    var $sturno_Dia7="";
    var $horas_refrigerio="";
    var $minutos_refrigerio="";
    var $tferiado1="";
    var $tferiado2="";
    var $tferiado3="";
    var $tferiado4="";
    var $tferiado5="";
    var $tferiado6="";
    var $tferiado7="";
    var $fturno_Dia1="";
    var $fturno_Dia2="";
    var $fturno_Dia3="";
    var $fturno_Dia4="";
    var $fturno_Dia5="";
    var $fturno_Dia6="";
    var $fturno_Dia7="";
    var $nombre_archivo_origen="";
    var $nombre_archivo_carga="";
    var $ttotal_horas="";
    var $ttotal_minutos="";

function AddnewUpdate(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="SELECT tc_codigo from ca_turno_empleado(nolock) ";
		$ssql .=" where empleado_codigo = ". $this->empleado_codigo;
		$ssql .=" and te_semana = ". $this->te_semana;
		$ssql .=" and te_anio = ". $this->te_anio;
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$rpta=$this->Update();
		}else{
			$rpta=$this->Addnew();
		}
	}
	return $rpta;
}

function AddnewUpdate_Semana(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $rpta = "OK";
		$ssql =" select cast(Item_Default2 as float) as horas_cont ";
		$ssql.=" from atributos a (nolock) inner join items i (nolock) on a.item_codigo=i.item_codigo ";
		$ssql.=" and i.tabla_codigo=10 and i.item_activo=1 and a.estado_codigo=1 ";
		$ssql.=" where empleado_codigo = ". $this->empleado_codigo;
		$ssql.=" and cast(Item_Default2 as float)= ";
		$ssql.=" (select cast(total_horas as float)+(cast(total_minutos as float)/60) as total_horas ";
		$ssql.=" from vca_turnos_combinacionthf (nolock) ";
		$ssql.=" where tc_codigo=" . $this->tc_codigo . ") ";
	    $rs1 = $cn->Execute($ssql);
		if (!$rs1->EOF){
			$ssql ="SELECT tc_codigo from ca_turno_empleado(nolock) ";
			$ssql.=" where empleado_codigo = ". $this->empleado_codigo;
			$ssql.=" and te_semana = ". $this->te_semana;
			$ssql.=" and te_anio = ". $this->te_anio;
		    $rs = $cn->Execute($ssql);
			if (!$rs->EOF){
				$rpta=$this->Update_semana();			
			}else{
				$rpta=$this->Addnew_semana();
			}
		}else{
			$rpta="No coincide horas efectivas";
		}
	}
	return $rpta;
}

function Addnew(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
        $params = array($this->empleado_codigo,
                        $this->te_semana,
                        $this->te_anio,
                        $this->tc_codigo,
                        $this->te_fecha_inicio,
                        $this->te_fecha_fin,
                        $this->turno_Dia1,
                        $this->turno_Dia2,
                        $this->turno_Dia3,
                        $this->turno_Dia4,
                        $this->turno_Dia5,
                        $this->turno_Dia6,
                        $this->turno_Dia7,
                        $this->empleado_codigo_registro);
		
        $ssql = "INSERT INTO ca_turno_empleado";
		$ssql .= " (empleado_codigo, te_semana, te_anio, tc_codigo, te_fecha_inicio, te_fecha_fin, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, fecha_registro, empleado_codigo_registro) ";
		$ssql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,getdate(),?)";
		
        $rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
    /*	$rpta="";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$ssql = "INSERT INTO ca_turno_empleado";
		$ssql .= " (empleado_codigo, te_semana, te_anio, tc_codigo, te_fecha_inicio, te_fecha_fin, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, fecha_registro, empleado_codigo_registro) ";
		$ssql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,getdate(),?)";
		$cmd->ActiveConnection = $this->cnnado;
		$cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->te_semana;
		$cmd->Parameters[2]->value = $this->te_anio;
		$cmd->Parameters[3]->value = $this->tc_codigo;
		$cmd->Parameters[4]->value = $this->te_fecha_inicio;
		$cmd->Parameters[5]->value = $this->te_fecha_fin;
		$cmd->Parameters[6]->value = $this->turno_Dia1;
		$cmd->Parameters[7]->value = $this->turno_Dia2;
		$cmd->Parameters[8]->value = $this->turno_Dia3;
		$cmd->Parameters[9]->value = $this->turno_Dia4;
		$cmd->Parameters[10]->value = $this->turno_Dia5;
		$cmd->Parameters[11]->value = $this->turno_Dia6;
		$cmd->Parameters[12]->value = $this->turno_Dia7;
		$cmd->Parameters[13]->value = $this->empleado_codigo_registro;
		$cmd->Execute();
	}
	return $rpta;*/
}
function Addnew_semana(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $rpta = "OK";
		$ssql = "INSERT INTO ca_turno_empleado";
		$ssql .= " (empleado_codigo, te_semana, te_anio, tc_codigo, te_fecha_inicio, te_fecha_fin, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, fecha_registro, empleado_codigo_registro) ";
		$ssql .= " VALUES (";
		$ssql .= $this->empleado_codigo;
		$ssql .= "," . $this->te_semana;
		$ssql .= "," . trim($this->te_anio);
		$ssql .= "," . $this->tc_codigo;
		$ssql .= "," . " convert(datetime,'" . trim($this->te_fecha_inicio) . "',103),convert(datetime,'" . trim($this->te_fecha_fin) . "',103)";
		$ssql .= "," . $this->turno_Dia1;
		$ssql .= "," . $this->turno_Dia2;
		$ssql .= "," . $this->turno_Dia3;
		$ssql .= "," . $this->turno_Dia4;
		$ssql .= "," . $this->turno_Dia5;
		$ssql .= "," . $this->turno_Dia6;
		$ssql .= "," . $this->turno_Dia7;
		$ssql .= ",getdate()," . $this->empleado_codigo_registro . ")";
		
		$rs = $cn->Execute($ssql);
		if ($rs->EOF){
			$this->Update_Tccodigo();
		}
	}
	return $rpta;
}
function Update(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array(
                        $this->tc_codigo,
                        $this->te_fecha_inicio,
                        $this->te_fecha_fin,
                        $this->turno_Dia1 ,
                        $this->turno_Dia2,
                        $this->turno_Dia3,
                        $this->turno_Dia4,
                        $this->turno_Dia5,
                        $this->turno_Dia6,
                        $this->turno_Dia7,
                        $this->empleado_codigo_registro ,
                        $this->empleado_codigo,
                        $this->te_semana,
                        $this->te_anio
                    );
		$ssql = "UPDATE ca_turno_empleado ";
		$ssql .= " SET tc_codigo = ?,";
		$ssql .= "     te_fecha_inicio = ?,";
		$ssql .= "     te_fecha_fin = ?,";
		$ssql .= "     turno_dia1 = ?,";
		$ssql .= "     turno_dia2 = ?,";
		$ssql .= "     turno_dia3 = ?,";
		$ssql .= "     turno_dia4 = ?,";
		$ssql .= "     turno_dia5 = ?,";
		$ssql .= "     turno_dia6 = ?,";
		$ssql .= "     turno_dia7 = ?,";
		$ssql .= "     Fecha_Modificacion = getdate(),";
		$ssql .= "     Empleado_Codigo_Modificacion = ?";
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
		$rs = $cn->Execute($ssql);
	}
	return $rpta;
}

function Update_semana(){
	$rpta="OK";
    $cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql = "UPDATE ca_turno_empleado ";
		$ssql .= " SET tc_codigo = " . $this->tc_codigo . ",";
		$ssql .= "     te_fecha_inicio = convert(datetime,'" . trim($this->te_fecha_inicio) . "',103),";
		$ssql .= "     te_fecha_fin = convert(datetime,'" . trim($this->te_fecha_fin) . "',103),";
		$ssql .= "     turno_dia1 = " . $this->turno_Dia1 . ",";
		$ssql .= "     turno_dia2 = " . $this->turno_Dia2 . ",";
		$ssql .= "     turno_dia3 = " . $this->turno_Dia3 . ",";
		$ssql .= "     turno_dia4 = " . $this->turno_Dia4 . ",";
		$ssql .= "     turno_dia5 = " . $this->turno_Dia5 . ",";
		$ssql .= "     turno_dia6 = " . $this->turno_Dia6 . ",";
		$ssql .= "     turno_dia7 = " . $this->turno_Dia7 . ",";
		$ssql .= "     Fecha_Modificacion = getdate()" . ",";
		$ssql .= "     Empleado_Codigo_Modificacion = " . $this->empleado_codigo_registro ;
		$ssql .= " Where empleado_codigo=" . $this->empleado_codigo;
		$ssql .= " and te_semana=" . $this->te_semana;
		$ssql .= " and te_anio=" . $this->te_anio;
		$rs = $cn->Execute($ssql);
		if ($rs->EOF){
			$this->Update_Tccodigo();
		}
	}
	return $rpta;
}

function Add_Update_Especial(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
        //$cn->debug=true;
	if($cn){
		/*
		$ssql =" select empleado_codigo from ca_asistencia_especial ";
		$ssql.=" Where ae_activo=1 and empleado_codigo=".$this->empleado_codigo;
		$ssql.=" and ae_fecha=convert(datetime,'".$this->te_fecha_inicio."',103) ";
	    $rs1 = $this->cnnado->Execute($ssql);
		if (!$rs1->EOF){
			$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
			$cmd->ActiveConnection = $this->cnnado;	
			$ssql = " update ca_asistencia_especial set ae_activo=0, ";
			$ssql.= " usuario_modifica=".$this->empleado_codigo_registro.",fecha_modifica=getdate()"; 
			$ssql.= " Where ae_activo=1 and empleado_codigo=? "; 
			$ssql.= " and ae_fecha=convert(datetime,'".$this->te_fecha_inicio."',103) ";
		    $cmd->CommandText = $ssql;
			$cmd->Parameters[0]->value = $this->empleado_codigo;
			$cmd->Execute();
		}
		$cmd1 = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd1->ActiveConnection = $this->cnnado;	
		$ssql = " insert into ca_asistencia_especial (ae_codigo,empleado_codigo,ae_fecha,ae_inicio,ae_termino,";
		$ssql.= " ae_activo,usuario_registro,fecha_registro)";
		$ssql.= " select (select isnull(max(ae_codigo),0)+1 as codigo from ca_asistencia_especial),";
		$ssql.= " ".$this->empleado_codigo.",convert(datetime,'".$this->te_fecha_inicio."',103),"; 
		$ssql.= " CONVERT(DATETIME,('".$this->te_fecha_inicio."'+' '+cast(".$this->total_horas." as varchar) ";
		$ssql.= " +':'+cast(".$this->total_minutos." as varchar)),103), ";
		$ssql.= " DATEADD(hour,".$this->thorasp.", CONVERT(DATETIME,('".$this->te_fecha_inicio."'+' '";
		$ssql.= " +cast(".$this->total_horas." as varchar)+':'+cast(".$this->total_minutos." as varchar)),103)), ";
		$ssql.= " 1,".$this->empleado_codigo_registro.",getdate() ";
		*/
		$params = array(
                        $this->empleado_codigo,
                        $this->te_fecha_inicio,
                        $this->empleado_codigo_registro,
                        $this->total_horas,
                        $this->total_minutos,
                        $this->thorasp
                    );
		$ssql = "exec spCA_ADD_UPDATE_ESPECIAL ?, ?, ?, ?, ?, ?";
		$rs = $cn->Execute($ssql, $params);		
	}
	return $rpta;
}

function Update_Tccodigo(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
        $params = array(
                        $this->tc_codigo,
                        $this->empleado_codigo
                    );	
		$ssql = "UPDATE empleados ";
		$ssql .= " SET tc_codigo = ?";
		$ssql .= " Where empleado_codigo=? "; 
		$rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Update_Dias(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array(
                        $this->turno_Dia1,
                        $this->turno_Dia2 ,
                        $this->turno_Dia3,
                        $this->turno_Dia4,
                        $this->turno_Dia5,
                        $this->turno_Dia6,
                        $this->turno_Dia7,
                        $this->empleado_codigo_registro,
                        $this->empleado_codigo,
                        $this->te_semana,
                        $this->te_anio
                    );
		$ssql = "UPDATE ca_turno_empleado ";
		$ssql .= " SET turno_dia1 = ?,";
		$ssql .= "     turno_dia2 = ?,";
		$ssql .= "     turno_dia3 = ?,";
		$ssql .= "     turno_dia4 = ?,";
		$ssql .= "     turno_dia5 = ?,";
		$ssql .= "     turno_dia6 = ?,";
		$ssql .= "     turno_dia7 = ?,";
		$ssql .= "     Fecha_Modificacion = getdate(),";
		$ssql .= "     Empleado_Codigo_Modificacion = ?";
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
		$rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Update_Dias_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array(
                        $this->turno_Dia1 ,
                        $this->turno_Dia2,
                        $this->turno_Dia3 ,
                        $this->turno_Dia4,
                        $this->turno_Dia5 ,
                        $this->turno_Dia6 ,
                        $this->turno_Dia7,
                        $this->empleado_codigo_registro,
                        $this->empleado_codigo,
                        $this->te_semana,
                        $this->te_anio
                    );
		$ssql = "UPDATE ca_turno_empleado_Cambios ";
		$ssql .= " SET turno_dia1 = ?,";
		$ssql .= "     turno_dia2 = ?,";
		$ssql .= "     turno_dia3 = ?,";
		$ssql .= "     turno_dia4 = ?,";
		$ssql .= "     turno_dia5 = ?,";
		$ssql .= "     turno_dia6 = ?,";
		$ssql .= "     turno_dia7 = ?,";
		$ssql .= "     Fecha_Modificacion = getdate(),";
		$ssql .= "     Empleado_Codigo_Modificacion = ?";
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
		$rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Addnew_Dias_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array(
                        $this->empleado_codigo,
                        $this->te_semana,
                        $this->te_anio
                    );
		$ssql = "insert into ca_turno_empleado_cambios ";
		$ssql.= " select * from ca_turno_empleado ";
		$ssql.= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
		$cn = $cn->Execute($ssql, $params);
		$rpta = $this->Update_Dias_Cambios();
	}
	return $rpta;
}

function Delete_Dias_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array($this->empleado_codigo ,
                       $this->te_semana ,
                       $this->te_anio );
		$ssql = "DELETE from ca_turno_empleado_cambios ";
		$ssql.= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
		$rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Update_Dia(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->empleado_codigo ,
                       $this->te_semana ,
                       $this->te_anio  );
        
        $ssql = "UPDATE ca_turno_empleado ";
		$ssql .= " SET turno_dia".$this->dia_i." = " . $this->turno_Dia1 . ",";
		$ssql .= "     Fecha_Modificacion = getdate()" . ",";
		$ssql .= "     Empleado_Codigo_Modificacion = " . $this->empleado_codigo_registro ;
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
        $rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
    /*	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = "UPDATE ca_turno_empleado ";
		$ssql .= " SET turno_dia".$this->dia_i." = " . $this->turno_Dia1 . ",";
		$ssql .= "     Fecha_Modificacion = getdate()" . ",";
		$ssql .= "     Empleado_Codigo_Modificacion = " . $this->empleado_codigo_registro ;
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
	    $cmd->CommandText = $ssql;
	    //echo $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->te_semana;
		$cmd->Parameters[2]->value = $this->te_anio;
		$cmd->Execute();
		
		
	}
	return $rpta;*/
}

function Update_Dia_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->empleado_codigo ,
                       $this->te_semana ,
                       $this->te_anio  );
        $ssql = "UPDATE ca_turno_empleado_cambios ";
		$ssql .= " SET turno_dia".$this->dia_i." = " . $this->turno_Dia1 . ",";
		$ssql .= "     Fecha_Modificacion = getdate()" . ",";
		$ssql .= "     Empleado_Codigo_Modificacion = " . $this->empleado_codigo_registro ;
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? ";
        $rs = $cn->Execute($ssql, $params); 
	}
	return $rpta;
    /*	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = "UPDATE ca_turno_empleado_cambios ";
		$ssql .= " SET turno_dia".$this->dia_i." = " . $this->turno_Dia1 . ",";
		$ssql .= "     Fecha_Modificacion = getdate()" . ",";
		$ssql .= "     Empleado_Codigo_Modificacion = " . $this->empleado_codigo_registro ;
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
	    $cmd->CommandText = $ssql;
	    //echo $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->te_semana;
		$cmd->Parameters[2]->value = $this->te_anio;
		$cmd->Execute();
	}
	return $rpta;*/
}

function Addnew_Dia_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->empleado_codigo ,
                       $this->te_semana ,
                       $this->te_anio  );
        $ssql = "INSERT INTO ca_turno_empleado_cambios ";
		$ssql.= " SELECT * FROM ca_turno_empleado ";
		$ssql.= " WHERE empleado_codigo=? and te_semana=? and te_anio=? "; 
        $rs = $cn->Execute($ssql, $params); 
		$rpta = $this->Update_Dia_Cambios();
	}
	return $rpta;
	/*	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		
		$ssql = "insert into ca_turno_empleado_cambios ";
		$ssql.= " select * from ca_turno_empleado ";
		$ssql.= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
	    $cmd->CommandText = $ssql;
	    //echo $ssql;
	    //echo $this->empleado_codigo. " -".$this->te_semana." -".$this->te_anio;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->te_semana;
		$cmd->Parameters[2]->value = $this->te_anio;
		$cmd->Execute();
		$rpta = $this->Update_Dia_Cambios();
	}
	return $rpta;*/
}

function Quitar_Turno(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
        $params = array($this->empleado_codigo ,
                       $this->te_semana ,
                       $this->te_anio  );
		$ssql = "DELETE  from ca_turno_empleado ";
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
        $rs = $cn->Execute($ssql, $params); 
	}
	return $rpta;
    /*	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = "DELETE  from ca_turno_empleado ";
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? "; 
		
	    $cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->te_semana;
		$cmd->Parameters[2]->value = $this->te_anio;
		$cmd->Execute();
	}
	return $rpta;*/
}

function Quitar_Turno_Especial(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->empleado_codigo );
        $ssql = " UPDATE ca_asistencia_especial SET ae_activo=0 ";
		$ssql.= " WHERE ae_activo=1 and empleado_codigo=? ";
		$ssql.= " and ae_fecha=convert(datetime,'".$this->te_fecha_inicio."',103)"; 
	    $rs = $cn->Execute($ssql, $params); 
	}
	return $rpta;
    /*	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = " update ca_asistencia_especial set ae_activo=0 ";
		$ssql.= " Where ae_activo=1 and empleado_codigo=? ";
		$ssql.= " and ae_fecha=convert(datetime,'".$this->te_fecha_inicio."',103)"; 
	    $cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Execute();
	}
	return $rpta;*/
}

function Query(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "SELECT empleado_codigo, te_semana, te_anio, tc_codigo, te_fecha_inicio, te_fecha_fin, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7 ";
		$ssql .= " FROM ca_turno_empleado ";
		$ssql .= " WHERE empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana . " and te_anio = " . $this->te_anio;
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$this->tc_codigo = $rs->fields[2];
			$this->te_fecha_inicio = $rs->fields[3];
			$this->te_fecha_fin = $rs->fields[4];
			$this->turno_Dia1 = $rs->fields[6];
			$this->turno_Dia2 = $rs->fields[6];
			$this->turno_Dia3 = $rs->fields[7];
			$this->turno_Dia4 = $rs->fields[8];
			$this->turno_Dia5 = $rs->fields[9];
			$this->turno_Dia6 = $rs->fields[10];
			$this->turno_Dia7 = $rs->fields[11];
				
		}else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
		}
	} 
	return $rpta;
}

function Traer_Empleados($modalidad_codigo, $cargo_codigo, $area_codigo, $nombres, $te_semana, $te_anio, $responsable_codigo, $esadmin, $empleado_dni){
$cadena="";
$rpta="OK";
$cn = $this->getMyConexionADO();
//$cn->debug=true;
if( $cn){
	$ssql=" select e.empleado_codigo, e.empleado, e.area_descripcion, e.cargo_descripcion, e.modalidad_descripcion, ";
	$ssql.= " isnull((select tc_codigo_sap from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'') as tc_codigo, ";
	$ssql.= " isnull(e.agrupacion_id,'') as agrupacion_id, "; 
	$ssql.= " e.horario_descripcion as horas_semanal, e.empleado_dni, ";
	$ssql.= "isnull((select convert(varchar(10),te_fecha_inicio, 103) from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'') as te_fecha_inicio, ";
	$ssql.= "isnull((select convert(varchar(10),te_fecha_fin, 103) from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'')  as te_fecha_fin "; 
	$ssql.= " from vdatostotal e left join ca_asignacion_empleados cae on e.empleado_codigo=cae.empleado_codigo and cae.asignacion_activo=1 ";
	if ($esadmin!='OK'){
		$ssql .= " inner join areas a on a.area_codigo=e.area_codigo ";
	}
	$ssql.= " where e.empleado_codigo>0 ";
	if ($esadmin!='OK'){
		//$ssql.= " and a.tipo_area_codigo=1 and a.area_codigo in "; 
		$ssql.=" and a.area_codigo in "; 
		$ssql.=" (select area_codigo from ca_controller ";
		$ssql.=" where empleado_codigo=".$this->empleado_codigo_registro." and activo=1 ";
		$ssql.=" union select area_codigo from vdatostotal where empleado_codigo=".$this->empleado_codigo_registro.") ";
	}
	if($modalidad_codigo !=0) $ssql.= " and modalidad_codigo=" . $modalidad_codigo;
	if($cargo_codigo !=0) $ssql.= " and cargo_codigo=" . $cargo_codigo;
	if($area_codigo !=0) $ssql.= " and e.area_codigo=" . $area_codigo;
	if($responsable_codigo !=0) $ssql.= " and cae.responsable_codigo=" . $responsable_codigo;
	if($nombres!='') $ssql.= " and empleado like '%" . $nombres . "%'";
	//if($agrupacion_id!='') $ssql.= " and e.agrupacion_id like '%" . $agrupacion_id . "%'";
	if($empleado_dni!='') $ssql.= " and e.empleado_dni like '%" . $empleado_dni . "%'";
	$ssql.= " order by 2 ";
	//echo $ssql;
	$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
		  $i=0;
		  while(!$rs->EOF){
            $i+=1;
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td nowrap>";
			$cadena .="<input type='checkbox' id='chk" . $rs->fields[0]  . "' name='chk" . $rs->fields[0]  . "' value='" . $rs->fields[0] . "' title='Cod: ".$rs->fields[0]."'>";
			//}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs->fields[8]  . "&nbsp;</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[1] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[2] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[3] ;
			$cadena .="</td>";
			//$cadena .="<td >&nbsp;" . $rs->fields[7] ;
			$cadena .="<td>&nbsp;<font id=".$rs->fields[0]." style=cursor:hand onclick=cmdHorario_onclick(this.id) title=cambiar_horario><b>".$rs->fields[7]."</b></font>";
			$cadena .="</td>";
			$cadena .="<td align='center' >&nbsp";
			if ($rs->fields[5]!=''){ //si tiene asignado 
				$cadena .=$rs->fields[5];
			}else{
				$cadena .="S/CS";
			}
			$cadena .="</td>";

            $fechainicio=$rs->fields[9];
            $fechafin=$rs->fields[10];
            
            if ($fechainicio != ''){
                $cadena .="<td align='center' ><img border='0' src='../../Images/asistencia/inline011.gif' onclick=cmdVersap_onclick(". $rs->fields[0] .",". $te_semana .",". $te_anio .",'". $fechainicio ."','". $fechafin ."') style='cursor:hand' title='Programación Semanal'></td>";
			}
            else{
                $cadena .="<td align='center' ></td>";
            }
            
/*            $cadena .="<td align='center' >&nbsp";
			if ($rs->fields[5]->value !=''){ //si tiene asignado
				$cadena .="<img border='0' src='../../Images/asistencia/delete_small.gif' onclick='Quitar(". $rs->fields[0]->value .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
			}else{
				$cadena .="";
			}
			$cadena .="</td>";	*/
			$cadena .="</tr>";
			$rs->MoveNext();
	      }
       }
 return $cadena;
 }
}

function Traer_Empleados_Fechas($modalidad_codigo, $cargo_codigo, $area_codigo, $nombres, $te_ini, $te_fin, $responsable_codigo, $esadmin, $empleado_dni,$te_semana, $te_anio){
$cadena="";
$rpta="OK";
$cn = $this->getMyConexionADO();
//$cn->debug=true;
if( $cn){
	$ssql=" select e.empleado_codigo, e.empleado, e.area_descripcion, e.cargo_descripcion, e.modalidad_descripcion, ";
	$ssql.= " isnull(v.tc_codigo_sap,'') as tc_codigo, ";
	$ssql.= " isnull(e.agrupacion_id,'') as agrupacion_id, "; 
	$ssql.= " e.horario_descripcion as horas_semanal, e.empleado_dni, ";
	$ssql.= " case when te_fecha_inicio IS null then '' else convert(varchar(10),te_fecha_inicio, 103) end as te_fecha_inicio, ";
	$ssql.= " case when te_fecha_fin IS null then '' else convert(varchar(10),te_fecha_fin, 103) end as te_fecha_fin  "; 
	$ssql.= " from vdatostotal e left join ca_asignacion_empleados cae on e.empleado_codigo=cae.empleado_codigo and cae.asignacion_activo=1 left join vCA_Turnos_EmpleadoTH v on e.empleado_codigo=v.empleado_codigo and v.te_fecha_inicio=convert(datetime,'".$te_ini."',103) and v.te_fecha_fin=convert(datetime,'".$te_fin."',103)";
	if ($esadmin!='OK'){
		$ssql .= " inner join areas a on a.area_codigo=e.area_codigo ";
	}
	$ssql.= " where e.empleado_codigo>0 ";
	if ($esadmin!='OK'){
		//$ssql.= " and a.tipo_area_codigo=1 and a.area_codigo in "; 
		$ssql.=" and a.area_codigo in "; 
		$ssql.=" (select area_codigo from ca_controller ";
		$ssql.=" where empleado_codigo=".$this->empleado_codigo_registro." and activo=1 ";
		$ssql.=" union select area_codigo from vdatostotal where empleado_codigo=".$this->empleado_codigo_registro.") ";
	}
	if($modalidad_codigo !=0) $ssql.= " and modalidad_codigo=" . $modalidad_codigo;
	if($cargo_codigo !=0) $ssql.= " and cargo_codigo=" . $cargo_codigo;
	if($area_codigo !=0) $ssql.= " and e.area_codigo=" . $area_codigo;
	if($responsable_codigo !=0) $ssql.= " and cae.responsable_codigo=" . $responsable_codigo;
	if($nombres!='') $ssql.= " and empleado like '%" . $nombres . "%'";
	//if($agrupacion_id!='') $ssql.= " and e.agrupacion_id like '%" . $agrupacion_id . "%'";
	if($empleado_dni!='') $ssql.= " and e.empleado_dni like '%" . $empleado_dni . "%'";
	$ssql.= " order by 2 ";
	//echo $ssql;
	$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
		  $i=0;
		  while(!$rs->EOF){
            $i+=1;
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td nowrap>";
			$cadena .="<input type='checkbox' id='chk" . $rs->fields[0]  . "' name='chk" . $rs->fields[0]  . "' value='" . $rs->fields[0] . "' title='Cod: ".$rs->fields[0]."'>";
			//}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs->fields[8]  . "&nbsp;</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[1] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[2] ;
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs->fields[3] ;
			$cadena .="</td>";
			//$cadena .="<td >&nbsp;" . $rs->fields[7] ;
			// $cadena .="<td>&nbsp;<font id=".$rs->fields[0]." style=cursor:hand onclick=cmdHorario_onclick(this.id) title=cambiar_horario><b>".$rs->fields[7]."</b></font>";
			$cadena .="<td>&nbsp;".$rs->fields[7];
			$cadena .="</td>";
			$cadena .="<td align='center' >&nbsp";
			if ($rs->fields[5]!=''){ //si tiene asignado 
				$cadena .=$rs->fields[5];
			}else{
				$cadena .="S/CS";
			}
			$cadena .="</td>";

            $fechainicio=$rs->fields[9];
            $fechafin=$rs->fields[10];
            
            if ($fechainicio != ''){
                $cadena .="<td align='center' ><img border='0' src='../../Images/asistencia/inline011.gif' onclick=cmdVersap_onclick(". $rs->fields[0] .",". $te_semana .",". $te_anio .",'". $fechainicio ."','". $fechafin ."') style='cursor:hand' title='Programación Semanal'></td>";
			}
            else{
                $cadena .="<td align='center' ></td>";
            }
            
/*            $cadena .="<td align='center' >&nbsp";
			if ($rs->fields[5]->value !=''){ //si tiene asignado
				$cadena .="<img border='0' src='../../Images/asistencia/delete_small.gif' onclick='Quitar(". $rs->fields[0]->value .")' style='cursor:hand' title='".$rs->fields[5]->value . "'>";
			}else{
				$cadena .="";
			}
			$cadena .="</td>";	*/
			$cadena .="</tr>";
			$rs->MoveNext();
	      }
       }
 return $cadena;
 }
}

/*function Traer_Empleados_Especial($cargo_codigo, $area_codigo, $nombres, $h_inicio, $m_inicio, $responsable_codigo, $esadmin, $empleado_dni, $fecha){
$cadena="";
$rpta="OK";
$connectionInfo = array(
                    "UID" => $this->getMyUser(), 
                    "PWD" => $this->getMyPwd(),
                    "Database"=>$this->getMyDBName()
                    ); 

$conn = SQLSRV_CONNECT( $this->getMyUrl(), $connectionInfo);
if($conn){
	
	
	$ssql="exec spCA_Turno_Especial '". $nombres ."','".$empleado_dni."',".$cargo_codigo.",";
	$ssql.="".$area_codigo.",".$responsable_codigo.",".$this->empleado_codigo_registro.",'".$fecha."','".$h_inicio."','".$m_inicio."','".$esadmin."'";
	
	//echo $ssql;
    $result = SQLSRV_QUERY($conn,$ssql);
  
		if(SQLSRV_HAS_ROWS($result)) {
		  $i=0;
		  while($rs = SQLSRV_FETCH_ARRAY($result)){
            $i+=1;
			
			$Query=" select Turno_Hora_Fin,Turno_Minuto_Fin from vCA_TurnosTH where Turno_Codigo =  ".$rs[10];
			$record = SQLSRV_QUERY($conn,$Query);
			$r = SQLSRV_FETCH_ARRAY($record);
			$turno_minutos=($r[0]*60)+$r[1];
			//echo $Query;
			
			
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
			$cadena .="<td align=center>" . $i . "&nbsp;</td>";
			$cadena .="<td nowrap>";
			$cadena .="<input type='checkbox' id='chk" . $rs[0]  . "' name='chk" . $rs[0]  . "' value='" . $rs[0]."_" .$turno_minutos."_".$rs[6]."' title='Cod: ".$rs[0]."'>";
			//}
			$cadena .="</td>";
			$cadena .="<td align=center>" . $rs[6]  . "&nbsp;</td>";//OK
			$cadena .="<td >&nbsp;" . $rs[1];//OK
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs[3];//OK
			$cadena .="</td>";
			$cadena .="<td >&nbsp;" . $rs[9];//OK
			$cadena .="</td>";
			$cadena .="<td align='center' >&nbsp";
			if ($rs[11]!=''){ //si tiene asignado 
				$cadena .=$rs[13];
			}else{
				$cadena .="S/TE";
			}
			$cadena .="</td>";
			
			$cadena .="<td >&nbsp;" . $rs[14];//entrada
			$cadena .="</td>";
			
			$cadena .="<td >&nbsp;" . $rs[15];//salida
			$cadena .="</td>";
			
            $cadena .="<td align='center' >&nbsp";
			if ($rs[11] !=''){ //si tiene asignado
				$cadena .="<img border='0' src='../../Images/asistencia/delete_small.gif' onclick='Quitar(". $rs[0] .")' style='cursor:hand' title='".$rs[10] . "'>";
			}else{
				$cadena .="";
			}
			$cadena .="</td>";	
			//campos 14 y 15
			$cadena .="</tr>";
			$r=null;
	      }
       }
 return $cadena;
 }
}*/



function Traer_Empleados_Especial($cargo_codigo, $area_codigo, $nombres, $h_inicio, $m_inicio, $responsable_codigo, $esadmin, $empleado_dni, $fecha){
$cadena="";
$rpta="OK";
$conn = $this->getMyConexionADO();
//echo $this->MyUrl;
//$conn->debug=true;
if($conn){
	
    $ssql="exec spCA_Turno_Especial '". $nombres ."','".$empleado_dni."',".$cargo_codigo.",";
    $ssql.="".$area_codigo.",".$responsable_codigo.",".$this->empleado_codigo_registro.",'".$fecha."','".$h_inicio."','".$m_inicio."','".$esadmin."'";
    //echo $ssql;
    $rs = $conn->Execute($ssql);
    if($rs->RecordCount() > 0){
        $i=0;
        while(!$rs->EOF){
            $i+=1;
            $Query=" select Turno_Hora_Fin,Turno_Minuto_Fin from vCA_TurnosTH where Turno_Codigo =  ".$rs->fields[10];
            $r = $conn->Execute($Query);
            $turno_minutos=($r->fields[0]*60)+$r->fields[1];
            $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
            $cadena .="<td align=center>" . $i . "&nbsp;</td>";
            $cadena .="<td nowrap>";
            $cadena .="<input type='checkbox' id='chk" . $rs->fields[0]  . "' name='chk" . $rs->fields[0]  . "' value='" . $rs->fields[0]."_" .$turno_minutos."_".$rs->fields[6]."' title='Cod: ".$rs->fields[0]."'>";
            
            $cadena .="</td>";
            $cadena .="<td align=center>" . $rs->fields[6]  . "&nbsp;</td>";//OK
            $cadena .="<td >&nbsp;" . $rs->fields[1];//OK
            $cadena .="</td>";
            $cadena .="<td >&nbsp;" . $rs->fields[3];//OK
            $cadena .="</td>";
            $cadena .="<td >&nbsp;" . $rs->fields[9];//OK
            $cadena .="</td>";
            $cadena .="<td align='center' >&nbsp";
            if ($rs->fields[11]!=''){ //si tiene asignado 
                    $cadena .=$rs->fields[13];
            }else{
                    $cadena .="S/TE";
            }
            $cadena .="</td>";

            $cadena .="<td >&nbsp;" . $rs->fields[14];//entrada
            $cadena .="</td>";

            $cadena .="<td >&nbsp;" . $rs->fields[15];//salida
            $cadena .="</td>";

            $cadena .="<td align='center' >&nbsp";
            
            if ($rs->fields[11] !=''){ //si tiene asignado
                    $cadena .="<img border='0' src='../../Images/asistencia/delete_small.gif' onclick='Quitar(". $rs->fields[0] .")' style='cursor:hand' title='".$rs->fields[10] . "'>";
            }else{
                    $cadena .="";
            }
            $cadena .="</td>";	
            
            $cadena .="</tr>";
            $r=null;
            $rs->MoveNext();
          }
    }
 return $cadena;
 }
}

function Query_Existe_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array(
                        $this->empleado_codigo ,
                        $this->te_semana,
                        $this->te_anio
                    );
		$ssql = " select empleado_codigo "; 
		$ssql.= " from ca_turno_empleado_cambios "; 
		$ssql.= " where empleado_codigo = ? and te_semana = ?"; 
		$ssql.= " and te_anio= ?"; 
	    $rs = $cn->Execute($ssql, $params);
		if (!$rs->EOF){
			$rpta = "OK";
		}else{
			$rpta = "NOT";
		}
	}
	return $rpta;
}

function Query_Horas_Permitido(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select cast(Item_Default2 as float) as item_default, "; 
		$ssql.= " (select cast(total_horas as float)+(cast(total_minutos as float)/60) as total_horas ";  
		$ssql.= " from vca_turnos_empleadoth_cambios ";
		$ssql.= " where empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
		$ssql.= " and te_anio=" . $this->te_anio ." ) as prog "; 
		$ssql.= " from atributos a inner join items i on a.item_codigo=i.item_codigo and i.tabla_codigo=10 "; 
		$ssql.= " and i.item_activo=1 and a.estado_codigo=1 "; 
		$ssql.= " where empleado_codigo = " . $this->empleado_codigo; 
		$ssql.= " and cast(Item_Default2 as float)>= ";
		$ssql.= " (select cast(total_horas as float)+(cast(total_minutos as float)/60) as total_horas ";  
		$ssql.= " from vca_turnos_empleadoth_cambios ";
		$ssql.= " where empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
		$ssql.= " and te_anio=" . $this->te_anio ." )";
		//echo $ssql; 
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$this->thorase = $rs->fields[0];
			$this->thorasp = $rs->fields[1];
//			$this->thorasp = $rs->fields[0];
			$rpta = "OK";
		}else{
			$rpta = "NOT";
		}
	}
	return $rpta;
}

function Query_Descanso_Obligado(){ //1 dia de descanso y no debe trabajar 7 dias seguido
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	//$cn->debug = true;
	if($cn){
/*		$ssql = " select empleado_codigo "; 
		$ssql.= " from ca_turno_empleado_cambios "; 
		$ssql.= " where empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
		$ssql.= " and te_anio=" . $this->te_anio; 
		$ssql.= " and (turno_dia1=0 or turno_dia2=0 or turno_dia3=0 or ";
		$ssql.= " turno_dia4=0 or turno_dia5=0 or turno_dia6=0 or turno_dia7=0)";
*/
		$ssql = "set datefirst 1";
		$rs = $cn->Execute($ssql);
		
		$ssql = "select count(descanso_actual) as total from "; 
		$ssql.= " (select case when turno_dia1=0 then dia1 else "; 
		$ssql.= "  	case when turno_dia2=0 then dia2 else "; 
		$ssql.= "  	  case when turno_dia3=0 then dia3 else "; 
		$ssql.= "  	    case when turno_dia4=0 then dia4 else "; 
		$ssql.= "  	      case when turno_dia5=0 then dia5 else "; 
		$ssql.= "	  		case when turno_dia6=0 then dia6 else ";
		$ssql.= "  			  case when turno_dia7=0 then dia7 else ";
		$ssql.= "    		    dia7+1 ";
		$ssql.= "    		  end ";
		$ssql.= "    		end ";
		$ssql.= "  	      end ";
		$ssql.= "  	    end ";
		$ssql.= "  	  end "; 
		$ssql.= "  	end "; 
		$ssql.= "  end as descanso_actual ";
		$ssql.= " from vca_turnos_empleadoth_cambios "; 
		$ssql.= " where empleado_codigo=".$this->empleado_codigo." and te_semana = ".$this->te_semana;
		$ssql.= " and te_anio=".$this->te_anio.") a ";
		$ssql.= "where a.descanso_actual- ";
		$ssql.= "( ";
		$ssql.= "select isnull( "; 
		$ssql.= "(select case when turno_dia7=0 then dia7 else "; 
		$ssql.= "  	case when turno_dia6=0 then dia6 else "; 
		$ssql.= "  	  case when turno_dia5=0 then dia5 else "; 
		$ssql.= "  	    case when turno_dia4=0 then dia4 else "; 
		$ssql.= "  	      case when turno_dia3=0 then dia3 else "; 
		$ssql.= "   		case when turno_dia2=0 then dia2 else ";
		$ssql.= "   		  case when turno_dia1=0 then dia1 else ";
		$ssql.= "   		    dia1 ";
		$ssql.= "    		  end ";
		$ssql.= "    		end ";
		$ssql.= "  	      end ";
		$ssql.= "  	    end ";
		$ssql.= "  	  end "; 
		$ssql.= "  	end "; 
		$ssql.= " end as descanso_anterior ";
		$ssql.= "from vca_turnos_empleadoth "; 
		$ssql.= "where empleado_codigo=".$this->empleado_codigo." and te_semana= ";
		$ssql.= " (select datepart(ww,te_fecha_inicio-2) as te_semana from vca_turnos_empleadoth_cambios ";
		$ssql.= " where empleado_codigo=".$this->empleado_codigo." and te_semana=".$this->te_semana;
		$ssql.= " and te_anio=".$this->te_anio.") ";
		$ssql.= "and te_anio= ";
		$ssql.= " (select year(te_fecha_inicio-1) as te_anio from vca_turnos_empleadoth_cambios ";
		$ssql.= " where empleado_codigo=".$this->empleado_codigo." and te_semana=".$this->te_semana;
		$ssql.= " and te_anio=".$this->te_anio.") ";
		$ssql.= ") ";
		$ssql.= ",'".$this->te_fecha_inicio."' ) as descanso_anterior ";
		$ssql.= ")<=14 ";
		
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){	//existe minimo 1 registro
			if ($rs->fields[0]>0){
				$rpta = "OK";
			}else{
				$rpta = "NOTOK";
			}
		}else{
			$rpta = "NOT";
		}
	}
	return $rpta;
}

function Query_Restriccion_Jornada(){ //8 horas de descanso antes de su proxima jornada
	$rpta="OK";
	$tmpo="NO";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Validar_Jornada " . $this->empleado_codigo;
		$ssql.="," . $this->te_semana . "," . $this->te_anio;
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF){
			$tmpo = $rs->fields[0];
		}
		if ($tmpo=="NO"){
			$rpta="OK";
		}else{
			$rpta="NOT";
		}
	}
	return $rpta;
}

function Query_Descanso_Dominical(){ //descanso dominical
	$rpta="OK";
	$tmpo="NO";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Validar_Dominical " . $this->empleado_codigo;
		$ssql.="," . $this->te_semana . ",'" . $this->te_aniomes . "'";
		$rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$tmpo = $rs->fields[0];
		}
		if ($tmpo=="NO"){
			$rpta="OK";
		}else{
			$rpta="NOT";
		}
	}
	return $rpta;
}

function Query_Turno_Empleado_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select *, "; 
		$ssql .= " case when convert(varchar(10),dia1,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia1, "; 
		$ssql .= " case when convert(varchar(10),dia2,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia2, "; 
		$ssql .= " case when convert(varchar(10),dia3,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia3, "; 
		$ssql .= " case when convert(varchar(10),dia4,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia4, "; 
		$ssql .= " case when convert(varchar(10),dia5,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia5, "; 
		$ssql .= " case when convert(varchar(10),dia6,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia6, "; 
		$ssql .= " case when convert(varchar(10),dia7,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia7 "; 
		$ssql .= " from vCA_Turnos_EmpleadoTH_Cambios "; 
		$ssql .= " where empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
		$ssql .= " and te_anio=" . $this->te_anio; 
	    $rs = $cn->Execute($ssql);
		//echo $ssql;
		if (!$rs->EOF){
			$this->tc_codigo_sap = $rs->fields[1];
			$this->turno_Dia1 = $rs->fields[2];
			$this->turno_Dia2 = $rs->fields[3];
			$this->turno_Dia3 = $rs->fields[4];
			$this->turno_Dia4 = $rs->fields[5];
			$this->turno_Dia5 = $rs->fields[6];
			$this->turno_Dia6 = $rs->fields[7];
			$this->turno_Dia7 = $rs->fields[8];
			$this->tc_activo= $rs->fields[9];
			$this->tturno_Dia1 = $rs->fields[10];
			$this->tturno_Dia2 = $rs->fields[11];
			$this->tturno_Dia3 = $rs->fields[12];
			$this->tturno_Dia4 = $rs->fields[13];
			$this->tturno_Dia5 = $rs->fields[14];
			$this->tturno_Dia6 = $rs->fields[15];
			$this->tturno_Dia7 = $rs->fields[16];
			$this->lturno_Dia1 = $rs->fields[17];
			$this->lturno_Dia2 = $rs->fields[18];
			$this->lturno_Dia3 = $rs->fields[19];
			$this->lturno_Dia4 = $rs->fields[20];
			$this->lturno_Dia5 = $rs->fields[21];
			$this->lturno_Dia6 = $rs->fields[22];
			$this->lturno_Dia7 = $rs->fields[23];
			$this->dturno_Dia1 = $rs->fields[24];
			$this->dturno_Dia2 = $rs->fields[25];
			$this->dturno_Dia3 = $rs->fields[26];
			$this->dturno_Dia4 = $rs->fields[27];
			$this->dturno_Dia5 = $rs->fields[28];
			$this->dturno_Dia6 = $rs->fields[29];
			$this->dturno_Dia7 = $rs->fields[30];
			$this->nturno_Dia1 = $rs->fields[31];
			$this->nturno_Dia2 = $rs->fields[32];
			$this->nturno_Dia3 = $rs->fields[33];
			$this->nturno_Dia4 = $rs->fields[34];
			$this->nturno_Dia5 = $rs->fields[35];
			$this->nturno_Dia6 = $rs->fields[36];
			$this->nturno_Dia7 = $rs->fields[37];
			$this->total_horas = $rs->fields[38];
			$this->total_minutos = $rs->fields[39];
			$this->te_fecha_inicio = $rs->fields[40];
			$this->te_fecha_fin = $rs->fields[41];
			$this->horas_refrigerio = $rs->fields[52];
			$this->minutos_refrigerio = $rs->fields[53];
			$this->eturno_Dia1 = $rs->fields[54];
			$this->eturno_Dia2 = $rs->fields[55];
			$this->eturno_Dia3 = $rs->fields[56];
			$this->eturno_Dia4 = $rs->fields[57];
			$this->eturno_Dia5 = $rs->fields[58];
			$this->eturno_Dia6 = $rs->fields[59];
			$this->eturno_Dia7 = $rs->fields[60];
			$this->sturno_Dia1 = $rs->fields[61];
			$this->sturno_Dia2 = $rs->fields[62];
			$this->sturno_Dia3 = $rs->fields[63];
			$this->sturno_Dia4 = $rs->fields[64];
			$this->sturno_Dia5 = $rs->fields[65];
			$this->sturno_Dia6 = $rs->fields[66];
			$this->sturno_Dia7 = $rs->fields[67];
			$this->hdia1 = $rs->fields[68];
			$this->hdia2 = $rs->fields[69];
			$this->hdia3 = $rs->fields[70];
			$this->hdia4 = $rs->fields[71];
			$this->hdia5 = $rs->fields[72];
			$this->hdia6 = $rs->fields[73];
			$this->hdia7 = $rs->fields[74];
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
	  }
	 } 
	return $rpta;
}

function Query_Turno_Empleado(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select *, "; 
		$ssql .= " case when convert(varchar(10),dia1,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia1, "; 
		$ssql .= " case when convert(varchar(10),dia2,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia2, "; 
		$ssql .= " case when convert(varchar(10),dia3,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia3, "; 
		$ssql .= " case when convert(varchar(10),dia4,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia4, "; 
		$ssql .= " case when convert(varchar(10),dia5,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia5, "; 
		$ssql .= " case when convert(varchar(10),dia6,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia6, "; 
		$ssql .= " case when convert(varchar(10),dia7,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia7 "; 
		$ssql .= " from vCA_Turnos_EmpleadoTH "; 
		$ssql .= " where empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
		$ssql .= " and te_anio=" . $this->te_anio; 
	    $rs = $cn->Execute($ssql);
		//echo $ssql;
		if (!$rs->EOF){
			$this->tc_codigo_sap = $rs->fields[1];
			$this->turno_Dia1 = $rs->fields[2];
			$this->turno_Dia2 = $rs->fields[3];
			$this->turno_Dia3 = $rs->fields[4];
			$this->turno_Dia4 = $rs->fields[5];
			$this->turno_Dia5 = $rs->fields[6];
			$this->turno_Dia6 = $rs->fields[7];
			$this->turno_Dia7 = $rs->fields[8];
			$this->tc_activo= $rs->fields[9];
			$this->tturno_Dia1 = $rs->fields[10];
			$this->tturno_Dia2 = $rs->fields[11];
			$this->tturno_Dia3 = $rs->fields[12];
			$this->tturno_Dia4 = $rs->fields[13];
			$this->tturno_Dia5 = $rs->fields[14];
			$this->tturno_Dia6 = $rs->fields[15];
			$this->tturno_Dia7 = $rs->fields[16];
			$this->lturno_Dia1 = $rs->fields[17];
			$this->lturno_Dia2 = $rs->fields[18];
			$this->lturno_Dia3 = $rs->fields[19];
			$this->lturno_Dia4 = $rs->fields[20];
			$this->lturno_Dia5 = $rs->fields[21];
			$this->lturno_Dia6 = $rs->fields[22];
			$this->lturno_Dia7 = $rs->fields[23];
			$this->dturno_Dia1 = $rs->fields[24];
			$this->dturno_Dia2 = $rs->fields[25];
			$this->dturno_Dia3 = $rs->fields[26];
			$this->dturno_Dia4 = $rs->fields[27];
			$this->dturno_Dia5 = $rs->fields[28];
			$this->dturno_Dia6 = $rs->fields[29];
			$this->dturno_Dia7 = $rs->fields[30];
			$this->nturno_Dia1 = $rs->fields[31];
			$this->nturno_Dia2 = $rs->fields[32];
			$this->nturno_Dia3 = $rs->fields[33];
			$this->nturno_Dia4 = $rs->fields[34];
			$this->nturno_Dia5 = $rs->fields[35];
			$this->nturno_Dia6 = $rs->fields[36];
			$this->nturno_Dia7 = $rs->fields[37];
			$this->total_horas = $rs->fields[38];
			$this->total_minutos = $rs->fields[39];
			$this->te_fecha_inicio = $rs->fields[40];
			$this->te_fecha_fin = $rs->fields[41];
			$this->horas_refrigerio = $rs->fields[52];
			$this->minutos_refrigerio = $rs->fields[53];
			$this->eturno_Dia1 = $rs->fields[54];
			$this->eturno_Dia2 = $rs->fields[55];
			$this->eturno_Dia3 = $rs->fields[56];
			$this->eturno_Dia4 = $rs->fields[57];
			$this->eturno_Dia5 = $rs->fields[58];
			$this->eturno_Dia6 = $rs->fields[59];
			$this->eturno_Dia7 = $rs->fields[60];
			$this->sturno_Dia1 = $rs->fields[61];
			$this->sturno_Dia2 = $rs->fields[62];
			$this->sturno_Dia3 = $rs->fields[63];
			$this->sturno_Dia4 = $rs->fields[64];
			$this->sturno_Dia5 = $rs->fields[65];
			$this->sturno_Dia6 = $rs->fields[66];
			$this->sturno_Dia7 = $rs->fields[67];
			$this->hdia1 = $rs->fields[68];
			$this->hdia2 = $rs->fields[69];
			$this->hdia3 = $rs->fields[70];
			$this->hdia4 = $rs->fields[71];
			$this->hdia5 = $rs->fields[72];
			$this->hdia6 = $rs->fields[73];
			$this->hdia7 = $rs->fields[74];
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
	  }
	 } 
	return $rpta;
}

function Query_Turno_Empleado_Sap($p1=""){ // $p=C jala desde cambios sin nada jala del real
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select *, "; 
		$ssql .= " case when convert(varchar(10),dia1,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia1, "; 
		$ssql .= " case when convert(varchar(10),dia2,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia2, "; 
		$ssql .= " case when convert(varchar(10),dia3,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia3, "; 
		$ssql .= " case when convert(varchar(10),dia4,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia4, "; 
		$ssql .= " case when convert(varchar(10),dia5,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia5, "; 
		$ssql .= " case when convert(varchar(10),dia6,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia6, "; 
		$ssql .= " case when convert(varchar(10),dia7,112)>convert(varchar(10),getdate(),112) then ' ' else 'disabled' end as hdia7 ";
		if ($p1=='C'){
			$ssql .= " from vCA_Turnos_EmpleadoTHF_Cambios ";
		}else{  
			$ssql .= " from vCA_Turnos_EmpleadoTHF ";
		}
		if ($this->te_fecha_inicio==''){
			$ssql .= " where empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
			$ssql .= " and te_anio=" . $this->te_anio; 
		}else{
			$ssql .= " where empleado_codigo = " . $this->empleado_codigo ;
			$ssql .= " and te_fecha_inicio = convert(datetime,'" . $this->te_fecha_inicio . "',103) "; 
			$ssql .= " and te_fecha_fin=convert(datetime,'" . $this->te_fecha_fin . "',103) "; 
		}
	    $rs = $cn->Execute($ssql);
		//echo $ssql;
		if (!$rs->EOF){
			$this->tc_codigo_sap = $rs->fields[1];
			$this->turno_Dia1 = $rs->fields[2];
			$this->turno_Dia2 = $rs->fields[3];
			$this->turno_Dia3 = $rs->fields[4];
			$this->turno_Dia4 = $rs->fields[5];
			$this->turno_Dia5 = $rs->fields[6];
			$this->turno_Dia6 = $rs->fields[7];
			$this->turno_Dia7 = $rs->fields[8];
			$this->tc_activo= $rs->fields[9];
			$this->tturno_Dia1 = $rs->fields[10];
			$this->tturno_Dia2 = $rs->fields[11];
			$this->tturno_Dia3 = $rs->fields[12];
			$this->tturno_Dia4 = $rs->fields[13];
			$this->tturno_Dia5 = $rs->fields[14];
			$this->tturno_Dia6 = $rs->fields[15];
			$this->tturno_Dia7 = $rs->fields[16];
			$this->lturno_Dia1 = $rs->fields[17];
			$this->lturno_Dia2 = $rs->fields[18];
			$this->lturno_Dia3 = $rs->fields[19];
			$this->lturno_Dia4 = $rs->fields[20];
			$this->lturno_Dia5 = $rs->fields[21];
			$this->lturno_Dia6 = $rs->fields[22];
			$this->lturno_Dia7 = $rs->fields[23];
			$this->dturno_Dia1 = $rs->fields[24];
			$this->dturno_Dia2 = $rs->fields[25];
			$this->dturno_Dia3 = $rs->fields[26];
			$this->dturno_Dia4 = $rs->fields[27];
			$this->dturno_Dia5 = $rs->fields[28];
			$this->dturno_Dia6 = $rs->fields[29];
			$this->dturno_Dia7 = $rs->fields[30];
			$this->nturno_Dia1 = $rs->fields[31];
			$this->nturno_Dia2 = $rs->fields[32];
			$this->nturno_Dia3 = $rs->fields[33];
			$this->nturno_Dia4 = $rs->fields[34];
			$this->nturno_Dia5 = $rs->fields[35];
			$this->nturno_Dia6 = $rs->fields[36];
			$this->nturno_Dia7 = $rs->fields[37];
			$this->total_horas = $rs->fields[38];
			$this->total_minutos = $rs->fields[39];
			$this->te_fecha_inicio = $rs->fields[40];
			$this->te_fecha_fin = $rs->fields[41];
			$this->fturno_Dia1 = $rs->fields[45];
			$this->fturno_Dia2 = $rs->fields[46];
			$this->fturno_Dia3 = $rs->fields[47];
			$this->fturno_Dia4 = $rs->fields[48];
			$this->fturno_Dia5 = $rs->fields[49];
			$this->fturno_Dia6 = $rs->fields[50];
			$this->fturno_Dia7 = $rs->fields[51];
			$this->horas_refrigerio = $rs->fields[52];
			$this->minutos_refrigerio = $rs->fields[53];
			$this->eturno_Dia1 = $rs->fields[54];
			$this->eturno_Dia2 = $rs->fields[55];
			$this->eturno_Dia3 = $rs->fields[56];
			$this->eturno_Dia4 = $rs->fields[57];
			$this->eturno_Dia5 = $rs->fields[58];
			$this->eturno_Dia6 = $rs->fields[59];
			$this->eturno_Dia7 = $rs->fields[60];
			$this->sturno_Dia1 = $rs->fields[61];
			$this->sturno_Dia2 = $rs->fields[62];
			$this->sturno_Dia3 = $rs->fields[63];
			$this->sturno_Dia4 = $rs->fields[64];
			$this->sturno_Dia5 = $rs->fields[65];
			$this->sturno_Dia6 = $rs->fields[66];
			$this->sturno_Dia7 = $rs->fields[67];
			$this->ttotal_horas = $rs->fields[75];
			$this->ttotal_minutos = $rs->fields[76];
			$this->hdia1 = $rs->fields[77];
			$this->hdia2 = $rs->fields[78];
			$this->hdia3 = $rs->fields[79];
			$this->hdia4 = $rs->fields[80];
			$this->hdia5 = $rs->fields[81];
			$this->hdia6 = $rs->fields[82];
			$this->hdia7 = $rs->fields[83];
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
	  }
	 } 
	return $rpta;
}

function Query_Turno_Dia(){
	$rpta="OK";
	$cn = $this->getMyconexionADO();
	if($cn){
        $ssql = " set datefirst 1 ";
        $rs=$cn->Execute($ssql);
        $ssql = " select tp.turno_descripcion, "; 
        $ssql.= " convert(varchar(10),fechap,103) as limite, cast(DATEPART(w, fechap) as varchar) as dia_i, "; 
        $ssql.= " tp.turno_codigo from vCA_turnos_programado tp(nolock) inner join ca_turnos t(nolock) on "; 
        $ssql.= " tp.turno_codigo=t.turno_codigo "; 
        //$ssql.= " where convert(varchar(10),fechap,112)=convert(varchar(10),getdate(),112) "; 
        $ssql.= " where fechap=convert(datetime,convert(varchar(10),getdate(),103),103) ";
        $ssql.= " and empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
        $ssql.= " and te_anio=" . $this->te_anio; 
        $rs = $cn->Execute($ssql);
        
        if (!$rs->EOF){
	        $this->turno_descripcion = $rs->fields[0];
	        $this->turno_limite = $rs->fields[1];
	        $this->dia_i = $rs->fields[2];
	        $this->turno_Dia1 = $rs->fields[3];
        }else{
	        $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
        }
	 } 
	return $rpta;
}

function Query_Turno_Dia_Cambios(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
        $ssql = " set datefirst 1 ";
        $rs=$cn->Execute($ssql);
        
        $ssql = " select tp.turno_descripcion, "; 
        $ssql.= " convert(varchar(10),fechap,103) as limite, cast(DATEPART(w, fechap) as varchar) as dia_i, "; 
        $ssql.= " tp.turno_codigo from vCA_turnos_programado_Cambios tp(nolock) inner join ca_turnos t(nolock) on ";
        $ssql.= " tp.turno_codigo=t.turno_codigo "; 
        //$ssql .= " where convert(varchar(10),fechap,112)=convert(varchar(10),getdate(),112) "; 
        $ssql.= " where fechap=convert(datetime, convert(varchar(10),getdate(),103), 103)";
        $ssql.= " and empleado_codigo = " . $this->empleado_codigo . " and te_semana = " . $this->te_semana; 
        $ssql.= " and te_anio=" . $this->te_anio; 
		$rs = $cn->Execute($ssql);

        if (!$rs->EOF){
            $this->turno_descripcion = $rs->fields[0];
            $this->turno_limite = $rs->fields[1];
            $this->dia_i = $rs->fields[2];
            $this->turno_Dia1 = $rs->fields[3];
        }else{
           $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
        }
	} 
    return $rpta;
}

function Query_cambio_modalidad(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " SELECT emp_mov_fecha_inicio "; 
		$ssql.= " FROM empleado_movimiento em join ca_turno_empleado cte on ";
		$ssql.= " em.empleado_codigo=cte.empleado_codigo ";
		$ssql.= " WHERE movimiento_codigo=28 AND estado_codigo=1 ";
		$ssql.= " AND em.empleado_codigo = " . $this->empleado_codigo . " AND te_semana = " . $this->te_semana; 
		$ssql.= " AND te_anio = " . $this->te_anio; 
		$ssql.= " AND emp_mov_fecha_inicio between te_fecha_inicio AND te_fecha_fin "; 
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$rpta = "OK";
		}else{
			$rpta = "NOT";
		}
	}
	
	return $rpta;
}

function Reporte_Turnos(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	if($cn){
		$ssql = " select area_descripcion,Empleado_Dni, empleado, tturno_dia1, tturno_dia2, tturno_dia3, ";
		$ssql.= "  tturno_dia4, tturno_dia5, tturno_dia6, tturno_dia7, ";
		$ssql.= " case when len(cast(Total_Horas as varchar))<=1 then '0'+cast(Total_Horas as varchar) ";
		$ssql.= " else cast(Total_Horas as varchar) end +'.'+cast((Total_minutos*100/60) as varchar) as total, ";
		//$ssql.= " then '0'+cast(Total_minutos as varchar) else cast(Total_minutos as varchar) end as total, ";
		$ssql.= " vte.te_semana, convert(varchar(10),vte.te_fecha_inicio,112) as te_fecha_inicio, ";
		$ssql.= " convert(varchar(10),vte.te_fecha_fin,112) as te_fecha_fin, ";
		$ssql.= " 'Del '+convert(varchar(10),vte.te_fecha_inicio,103)+' Al '+convert(varchar(10),vte.te_fecha_fin,103) as rango, vte.tc_codigo_sap ";
		$ssql.= " from vCA_Turnos_EmpleadoTH vte ";
		$ssql.= " inner join vdatos vd on vte.empleado_codigo=vd.empleado_codigo ";
		$ssql.= " where vte.te_fecha_inicio=convert(datetime,'".$this->te_fecha_inicio."',103)";
		$ssql.= " and vte.te_fecha_fin=convert(datetime,'".$this->te_fecha_fin."',103)";
		$ssql.= " order by 1,2 ";
		//echo $ssql;
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			$cadena = "<table class='FormTable' id='listado' width='200%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:200%'>
			<tr>
			    	<td colspan='13' align=center><b>Programacion Semana &nbsp;".$rs->fields[11]." </td>
			</tr>
			<tr>
			    	<td colspan='13' align=center><b>".$rs->fields[14]."</td>
			</tr>
			<tr align='center' style='background-color:#D8DAB4'>
	    	<td class='ColumnTD' style='width:80px'><b>Nro.</td>
	    	<td class='ColumnTD'><b>Area</td>
	    	<td class='ColumnTD'><b>DNI</td>
    		<td class='ColumnTD'><b>Nombres</td>
    		<td class='ColumnTD'><b>Cod.comb</td>
				<td class='ColumnTD'><b>Lunes</td>
				<td class='ColumnTD'><b>Martes</td>
				<td class='ColumnTD'><b>Miercoles</td>
				<td class='ColumnTD'><b>Jueves</td>
				<td class='ColumnTD'><b>Viernes</td>
				<td class='ColumnTD'><b>Sabado</td>
				<td class='ColumnTD'><b>Domingo</td>
				<td class='ColumnTD'><b>H.Efectivas</td>
			</tr>";

			$i=0;
			while(!$rs->EOF){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[15]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[4]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[5]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[6]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[7]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[8]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[9]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[10]  . "&nbsp;</td>";
				$cadena .="</tr>";
				$this->te_semana = $rs->fields[10];
				$this->te_fecha_inicio = $rs->fields[11];
				$this->te_fecha_fin = $rs->fields[12];
				$rs->MoveNext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Reporte_Programacion(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select t.empleado_dni ,  wfm.tc_codigo , convert(varchar(10) , wfm.fecha_inicio , 103) , ";
		$ssql.= " convert(varchar(10), wfm.fecha_fin , 103) ";
		$ssql.= "  from wfm_combinacion_empleado wfm ";
		$ssql.= " inner join empleados t on wfm.empleado_codigo=t.empleado_codigo ";
		$ssql.= " where wfm.fecha_inicio = convert(datetime , '" . $this->te_fecha_inicio . "' , 103) and ";
		$ssql.= " wfm.fecha_fin= convert(datetime , '" . $this->te_fecha_fin . "' , 103) and ";
		$ssql.= " wfm.usuario_registra=" . $this->empleado_codigo_registro;
		$rs = $cn->Execute($ssql);
		
		if(!$rs->EOF()) {
			$cadena = "<table class='FormTable' id='listado' width='80%' align='center' border='0' cellPadding='0' cellSpacing='0' >
			<tr>
  				<td colspan='12' align=center><b>Programacion Semana &nbsp;".$this->te_fecha_inicio ." - ". $this->te_fecha_fin . " </td>
			</tr>
			<tr align='center' style='background-color:#D8DAB4'>
			    	<td class='ColumnTD' style='width:80px'><b>Nro.</td>
			    	<td class='ColumnTD'><b>Codigo</td>
			    	<td class='ColumnTD'><b>Combinacion</td>
		    		<td class='ColumnTD'><b>Fecha Inicio</td>
		    		<td class='ColumnTD'><b>Fecha Fin</td>
				
			</tr>";

			$i=0;

			while(!$rs->EOF()){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]  . "&nbsp;</td>";

				$cadena .="</tr>";
				
				//$this->te_fecha_inicio = $rs->fields[2]->value;
				//$this->te_fecha_fin = $rs->fields[3]->value;
				$rs->movenext();
			}
			$cadena .= "</table>";

	 	return $cadena;
	}
}

}

function Reporte_Programacion2(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select t.empleado_dni ,  wfm.tc_codigo , convert(varchar(10) , wfm.fecha_inicio , 103) , ";
		$ssql.= " convert(varchar(10), wfm.fecha_fin , 103) ";
		$ssql.= "  from wfm_combinacion_empleado wfm ";
		$ssql.= " inner join empleados t on wfm.empleado_codigo=t.empleado_codigo ";
		$ssql.= " where wfm.fecha_inicio = convert(datetime , '" . $this->te_fecha_inicio . "' , 103) and ";
		$ssql.= " wfm.fecha_fin= convert(datetime , '" . $this->te_fecha_fin . "' , 103) and ";
		$ssql.= " wfm.usuario_registra=" . $this->empleado_codigo_registro;
		$rs = $cn->Execute($ssql);
		
		if(!$rs->EOF) {
			$cadena = "<table class='FormTable' id='listado' width='80%' align='center' border='0' cellPadding='0' cellSpacing='0' >";

			while(!$rs->EOF){
				$cadena .="<tr>";
				$cadena .="<td align=left>" . $rs->fields[0]  . "</td>";
				$cadena .="<td align=left>" . $rs->fields[1]  . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2]  . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3]  . "</td>";
				$cadena .="</tr>";
				
				//$this->te_fecha_inicio = $rs->fields[2]->value;
				//$this->te_fecha_fin = $rs->fields[3]->value;
				$rs->movenext();
			}
			$cadena .= "</table>";

	 	return $cadena;
	}
}

}

function Lista_Turnos(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select turno_id,convert(varchar(5),cast(cast(Turno_Hora_Inicio ";
		$ssql.= " as varchar)+':'+cast(Turno_Minuto_Inicio as varchar) as datetime),108) as hora_inicio, ";
		$ssql.= " convert(varchar(5),cast(cast(Turno_Hora_Fin as varchar)+':'+cast(Turno_Minuto_Fin ";
		$ssql.= " as varchar) as datetime),108) as hora_termino, ";
		$ssql.= " convert(varchar(5),turno_thoras,108) as total_horas,turno_refrigerio as duracion_colacion ";
		$ssql.= " from vca_turnosth ";
		$ssql.= " order by 1 ";
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF()) {
			$cadena = "<table class='FormTable' id='listado' width='80%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:80%'>
			<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD' style='width:100px'><b>Nro.</td>
		    	<td class='ColumnTD'><b>Turno_ID</td>
		    	<td class='ColumnTD'><b>Hora_Inicio</td>
		    	<td class='ColumnTD'><b>Hora_Termino</td>
				<td class='ColumnTD'><b>Total_Horas</td>
				<td class='ColumnTD'><b>Colacion</td>
			</tr>";

			$i=0;
			while(!$rs->EOF()){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[4]  . "&nbsp;</td>";
				$cadena .="</tr>";
				$rs->movenext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

//Desde aca inicia los metodos para los reportes del GAP.
function Suplex_Turnos(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	//$cn->debug = true;
	if($cn){
		$ssql = " select e.empleado_dni as rut, convert(varchar(10),v.fechap,103) as fecha, t.turno_id as isap, ";
		$ssql.= " t.turno_descripcion, e.area_descripcion ";
		$ssql.= " from vca_turnos_programado v inner join vdatos e on v.empleado_codigo=e.empleado_codigo ";
		$ssql.= " inner join ca_turnos t on v.turno_codigo=t.turno_codigo ";
		$ssql.= " where v.te_semana=" . $this->te_semana . " and v.te_anio=" . $this->te_anio ."";
		$ssql.= " union ";
		$ssql.= " select e.empleado_dni as rut, convert(varchar(10),v.fechap,103) as fecha, 'FREI' as isap, ";
		$ssql.= " '' as turno_descripcion, e.area_descripcion ";
		$ssql.= " from vCA_Turnos_Descanso v inner join vdatos e on v.empleado_codigo=e.empleado_codigo ";
		$ssql.= " where v.te_semana=" . $this->te_semana . " and v.te_anio=" . $this->te_anio ."";
		$ssql.= " order by 1,2 ";
		//echo $ssql;
		$rs = $cn->Execute($ssql);
		
		if(!$rs->EOF) {
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'>
			<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD' style='width:60px'><b>Nro.</td>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>Fecha</td>
				<td class='ColumnTD'><b>Ctur</td>
				<td class='ColumnTD'><b>Descripcion</td>
				<td class='ColumnTD'><b>Area</td>
			</tr>";
			$i=0;
			while(!$rs->EOF){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[4]  . "&nbsp;</td>";
				$cadena .="</tr>";
				$rs->MoveNext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Suplex_Turnos_Fechas(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	//$cn->debug = true;
	if($cn){
		$ssql = " select e.empleado_dni as rut, convert(varchar(10),v.fechap,103) as fecha, t.turno_id as isap, ";
		$ssql.= " t.turno_descripcion, e.area_descripcion ";
		$ssql.= " from vca_turnos_programado v inner join vdatos e on v.empleado_codigo=e.empleado_codigo ";
		$ssql.= " inner join ca_turnos t on v.turno_codigo=t.turno_codigo ";
		$ssql.= " where v.fechap between convert(datetime,'" . $this->te_fecha_inicio . "',103) and convert(datetime,'" . $this->te_fecha_fin ."',103) ";
		$ssql.= " union ";
		$ssql.= " select e.empleado_dni as rut, convert(varchar(10),v.fechap,103) as fecha, 'FREI' as isap, ";
		$ssql.= " '' as turno_descripcion, e.area_descripcion ";
		$ssql.= " from vCA_Turnos_Descanso v inner join vdatos e on v.empleado_codigo=e.empleado_codigo ";
		$ssql.= " where v.fechap between convert(datetime,'" . $this->te_fecha_inicio . "',103) and convert(datetime,'" . $this->te_fecha_fin ."',103) ";
		$ssql.= " order by 1,2 ";
		//echo $ssql;
		$rs = $cn->Execute($ssql);
		
		if(!$rs->EOF) {
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'>
			<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD' style='width:60px'><b>Nro.</td>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>Fecha</td>
				<td class='ColumnTD'><b>Ctur</td>
				<td class='ColumnTD'><b>Descripcion</td>
				<td class='ColumnTD'><b>Area</td>
			</tr>";
			$i=0;
			while(!$rs->EOF){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]  . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[4]  . "&nbsp;</td>";
				$cadena .="</tr>";
				$rs->MoveNext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Reporte01(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Reporte_Asis_Ausente '".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
        $rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>TURNOS AUSENTES Y ATRASOS DESDE ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." HASTA ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
				<td class='ColumnTD'><b>TCARGADOS</td>
				<td class='ColumnTD'><b>TAUSENTES</td>
				<td class='ColumnTD'><b>TATRASOS</td>
				<td class='ColumnTD'><b>M_ATRASOS</td>
				<td class='ColumnTD'><b>TEXTENSION</td>
			</tr>";
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[0] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[9] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[10] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[11] . "</td>";
				$cadena .="</tr>";
				$rs->MoveNext(); 
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
    /*	$cadena="";
	$rpta="OK";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	if($rpta=="OK"){
		$ssql ="exec spCA_Reporte_Asis_Ausente '".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
		$rs_result = mssql_query($ssql);
		if (mssql_num_rows($rs_result)>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>TURNOS AUSENTES Y ATRASOS DESDE ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." HASTA ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
				<td class='ColumnTD'><b>TCARGADOS</td>
				<td class='ColumnTD'><b>TAUSENTES</td>
				<td class='ColumnTD'><b>TATRASOS</td>
				<td class='ColumnTD'><b>M_ATRASOS</td>
				<td class='ColumnTD'><b>TEXTENSION</td>
			</tr>";
			$rs = mssql_fetch_row($rs_result);
			while($rs){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs[0] . "</td>";
				$cadena .="<td align=left>" . $rs[1] . "</td>";
				$cadena .="<td align=left>" . $rs[2] . "</td>";
				$cadena .="<td align=left>" . $rs[3] . "</td>";
				$cadena .="<td align=left>" . $rs[4] . "</td>";
				$cadena .="<td align=left>" . $rs[5] . "</td>";
				$cadena .="<td align=left>" . $rs[7] . "</td>";
				$cadena .="<td align=left>" . $rs[8] . "</td>";
				$cadena .="<td align=left>" . $rs[9] . "</td>";
				$cadena .="<td align=left>" . $rs[10] . "</td>";
				$cadena .="<td align=left>" . $rs[11] . "</td>";
				$cadena .="</tr>";
				$rs = mssql_fetch_row($rs_result);
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}*/
}

function Reporte02(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Reporte_Cambio_Turno '".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
        $rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>CAMBIO DE TURNOS DESDE ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." HASTA ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
				<td class='ColumnTD'><b>PERSONA QUE CAMBIO</td>
				<td class='ColumnTD'><b>DIA CAMBIO</td>
				<td class='ColumnTD'><b>T ORIGINAL</td>
				<td class='ColumnTD'><b>T NUEVO</td>
			</tr>";
			//$rs = mssql_fetch_row($rs_result);
            while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[0] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[11] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[12] . "</td>";
				$cadena .="</tr>";
                $rs->MoveNext(); 
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
    /*	$cadena="";
	$rpta="OK";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	if($rpta=="OK"){
		$ssql ="exec spCA_Reporte_Cambio_Turno '".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
		$rs_result = mssql_query($ssql);
		if (mssql_num_rows($rs_result)>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>CAMBIO DE TURNOS DESDE ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." HASTA ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
				<td class='ColumnTD'><b>PERSONA QUE CAMBIO</td>
				<td class='ColumnTD'><b>DIA CAMBIO</td>
				<td class='ColumnTD'><b>T ORIGINAL</td>
				<td class='ColumnTD'><b>T NUEVO</td>
			</tr>";
			$rs = mssql_fetch_row($rs_result);
			while($rs){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs[0] . "</td>";
				$cadena .="<td align=left>" . $rs[1] . "</td>";
				$cadena .="<td align=left>" . $rs[2] . "</td>";
				$cadena .="<td align=left>" . $rs[3] . "</td>";
				$cadena .="<td align=left>" . $rs[4] . "</td>";
				$cadena .="<td align=left>" . $rs[5] . "</td>";
				$cadena .="<td align=left>" . $rs[7] . "</td>";
				$cadena .="<td align=left>" . $rs[8] . "</td>";
				$cadena .="<td align=left>" . $rs[11] . "</td>";
				$cadena .="<td align=left>" . $rs[12] . "</td>";
				$cadena .="</tr>";
				$rs = mssql_fetch_row($rs_result);
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}*/
}

function Reporte03(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Reporte_Trab_Contra ".$this->te_semana.",".$this->te_anio.",'".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>HORAS TRABAJADAS vs HORAS CONTRACTUALES SEMANA ".$this->te_semana." DEL ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." AL ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>SEMANA</td>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
				<td class='ColumnTD'><b>H CONTRACTUALES</td>
				<td class='ColumnTD'><b>H CON LOGGER</td>
				<td class='ColumnTD'><b>DIFERENCIA</td>
			</tr>";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[0] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[13] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[14] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[15] . "</td>";
				$cadena .="</tr>";
				$rs->MoveNext(); 
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
    
    /*	$cadena="";
	$rpta="OK";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	if($rpta=="OK"){
		$ssql ="exec spCA_Reporte_Trab_Contra ".$this->te_semana.",".$this->te_anio.",'".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
		$rs_result = mssql_query($ssql);
		if (mssql_num_rows($rs_result)>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>HORAS TRABAJADAS vs HORAS CONTRACTUALES SEMANA ".$this->te_semana." DEL ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." AL ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>SEMANA</td>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
				<td class='ColumnTD'><b>H CONTRACTUALES</td>
				<td class='ColumnTD'><b>H CON LOGGER</td>
				<td class='ColumnTD'><b>DIFERENCIA</td>
			</tr>";
			$rs = mssql_fetch_row($rs_result);
			while($rs){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs[0] . "</td>";
				$cadena .="<td align=left>" . $rs[3] . "</td>";
				$cadena .="<td align=left>" . $rs[4] . "</td>";
				$cadena .="<td align=left>" . $rs[5] . "</td>";
				$cadena .="<td align=left>" . $rs[6] . "</td>";
				$cadena .="<td align=left>" . $rs[7] . "</td>";
				$cadena .="<td align=left>" . $rs[8] . "</td>";
				$cadena .="<td align=left>" . $rs[13] . "</td>";
				$cadena .="<td align=left>" . $rs[14] . "</td>";
				$cadena .="<td align=left>" . $rs[15] . "</td>";
				$cadena .="</tr>";
				$rs = mssql_fetch_row($rs_result);
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}*/
}

function Reporte04(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Reporte_Turno_Conexion '".$this->te_fecha_inicio."','".$this->te_fecha_fin."','".$this->empleado_dni."'";
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='12'> ";
			$cadena.= "<b>DIARIO DE TURNO Y CONEXION A LOGGER DEL ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." AL ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
		    	<td class='ColumnTD'><b>DIA</td>
				<td class='ColumnTD'><b>T ASIGNADO</td>
				<td class='ColumnTD'><b>M BIOMETRICO</td>
				<td class='ColumnTD'><b>¿CONECTADO A LOGGER?</td>
				<td class='ColumnTD'><b>A LOGGER</td>
				<td class='ColumnTD'><b>H DESCONEXION</td>
			</tr>";
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[0] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[9] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[10] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[11] . "</td>";
				$cadena .="</tr>";
				$rs->MoveNext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
    /*	$cadena="";
	$rpta="OK";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	if($rpta=="OK"){
		$ssql ="exec spCA_Reporte_Turno_Conexion '".$this->te_fecha_inicio."','".$this->te_fecha_fin."','".$this->empleado_dni."'";
		$rs_result = mssql_query($ssql);
		if (mssql_num_rows($rs_result)>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='12'> ";
			$cadena.= "<b>DIARIO DE TURNO Y CONEXION A LOGGER DEL ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." AL ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>SERVICIO</td>
				<td class='ColumnTD'><b>SUPERVISOR</td>
				<td class='ColumnTD'><b>JEFATURA</td>
				<td class='ColumnTD'><b>GERENTE</td>
		    	<td class='ColumnTD'><b>DIA</td>
				<td class='ColumnTD'><b>T ASIGNADO</td>
				<td class='ColumnTD'><b>M BIOMETRICO</td>
				<td class='ColumnTD'><b>¿CONECTADO A LOGGER?</td>
				<td class='ColumnTD'><b>A LOGGER</td>
				<td class='ColumnTD'><b>H DESCONEXION</td>
			</tr>";
			$rs = mssql_fetch_row($rs_result);
			while($rs){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs[0] . "</td>";
				$cadena .="<td align=left>" . $rs[1] . "</td>";
				$cadena .="<td align=left>" . $rs[2] . "</td>";
				$cadena .="<td align=left>" . $rs[3] . "</td>";
				$cadena .="<td align=left>" . $rs[4] . "</td>";
				$cadena .="<td align=left>" . $rs[5] . "</td>";
				$cadena .="<td align=left>" . $rs[6] . "</td>";
				$cadena .="<td align=left>" . $rs[7] . "</td>";
				$cadena .="<td align=left>" . $rs[8] . "</td>";
				$cadena .="<td align=left>" . $rs[9] . "</td>";
				$cadena .="<td align=left>" . $rs[10] . "</td>";
				$cadena .="<td align=left>" . $rs[11] . "</td>";
				$cadena .="</tr>";
				$rs = mssql_fetch_row($rs_result);
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}*/
}

function Reporte05(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Reporte_05 '".$this->te_fecha_inicio."','".$this->te_fecha_fin."'";
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='12'> ";
			$cadena.= "<b>HORAS DE PERMANENCIA EN LA EMPRESA DESDE ".substr($this->te_fecha_inicio,6,2)."/".substr($this->te_fecha_inicio,4,2)."/".substr($this->te_fecha_inicio,0,4)." HASTA ".substr($this->te_fecha_fin,6,2)."/".substr($this->te_fecha_fin,4,2)."/".substr($this->te_fecha_fin,0,4)." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>FECHA</td>
		    	<td class='ColumnTD'><b>CODIGO TURNO</td>
				<td class='ColumnTD'><b>HORA INICIO</td>
				<td class='ColumnTD'><b>HORA TERMINO</td>
				<td class='ColumnTD'><b>COLACION</td>
				<td class='ColumnTD'><b>HORAS TURNO</td>
				<td class='ColumnTD'><b>H INICIO M</td>
				<td class='ColumnTD'><b>H TERMINO M</td>
				<td class='ColumnTD'><b>H TURNO M</td>
				<td class='ColumnTD'><b>H INICIO C</td>
				<td class='ColumnTD'><b>H TERMINO C</td>
				<td class='ColumnTD'><b>H TURNO C</td>
			</tr>";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[0] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[9] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[10]. "</td>";
				$cadena .="<td align=left>" . $rs->fields[11]. "</td>";
				$cadena .="<td align=left>" . $rs->fields[12]. "</td>";
				$cadena .="</tr>";
				$rs->MoveNext();    
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Reporte06(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_estadistica '".$this->te_fecha_inicio."'";
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='11'> ";
			$cadena.= "<b>CUADRO RESUMEN DEL DIA ".$this->te_fecha_inicio." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>CODIGO</td>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>EJECUTIVO</td>
		    	<td class='ColumnTD'><b>AREA</td>
				<td class='ColumnTD'><b>LICENCIAS</td>
				<td class='ColumnTD'><b>VACACIONES</td>
				<td class='ColumnTD'><b>AENTR</td>
				<td class='ColumnTD'><b>ASALI</td>
				<td class='ColumnTD'><b>ASISTIO</td>
				<td class='ColumnTD'><b>FALTAS</td>
				<td class='ColumnTD'><b>TPROGRAMADO</td>
				<td class='ColumnTD'><b>TAUSENTE</td>
				<td class='ColumnTD'><b>LOG ENTR</td>
				<td class='ColumnTD'><b>LOG SALI</td>
				<td class='ColumnTD'><b>LOGER</td>
				<td class='ColumnTD'><b>TARDANZAS</td>
			</tr>";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[0] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[9] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[10] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[11] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[12] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[13] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[14] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[15] . "</td>";
				$cadena .="</tr>";
				$rs->MoveNext();    
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Reporte07(){
	$cadena="No hay registros en este rango de fechas";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql =" SELECT EMPLEADO_DNI, ".
		$ssql.=" CAST(DATEPART(DD,ASISTENCIA_FECHA) AS VARCHAR)+'.'+CAST(DATEPART(MM,ASISTENCIA_FECHA) AS VARCHAR)";
		$ssql.=" + '.' + CAST(DATEPART(YYYY,ASISTENCIA_FECHA) AS VARCHAR) AS FECHA, ";
		$ssql.=" 'CMS' AS TIPO, '' AS SKILL, '' AS LOG_ID, STR(SUM(TMINUTOS)/60,5,2) AS THORAS ";
		$ssql.=" FROM vCA_INCIDENCIAS_REMUNERACION V ";
		$ssql.=" WHERE Asistencia_fecha BETWEEN CONVERT(DATETIME, '".$this->te_fecha_inicio."' ,103) "; 
		$ssql.=" AND CONVERT(DATETIME, '".$this->te_fecha_fin."', 103) ";
		$ssql.=" AND INCIDENCIA_CODIGO in (137) ";
		$ssql.=" GROUP BY EMPLEADO_DNI, ASISTENCIA_FECHA "; 
		//$ssql.=" HAVING SUM(TMINUTOS)/60>=0 ";
		$ssql.=" ORDER BY 6 DESC ";
        $rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .=$rs->fields[0]." ";
				$cadena .=$rs->fields[1]." ";
				$cadena .=$rs->fields[2]." ";
				$cadena .=trim($rs->fields[3])." ";
				$cadena .=trim($rs->fields[4])." ";
				$cadena .=trim($rs->fields[5])."\n";
				$rs->MoveNext();   
			}
			//$cadena.= "";
		}
	 	return $cadena;
	}
}

function Reporte08(){
	$cadena="No hay registros en este rango de fechas";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql =" SELECT EMPLEADO_DNI, ".
		$ssql.=" CAST(DATEPART(DD,ASISTENCIA_FECHA) AS VARCHAR)+'.'+CAST(DATEPART(MM,ASISTENCIA_FECHA) AS VARCHAR)";
		$ssql.=" + '.' + CAST(DATEPART(YYYY,ASISTENCIA_FECHA) AS VARCHAR) AS FECHA, ";
		$ssql.=" 'BITA' AS TIPO, '' AS SKILL, '' AS LOG_ID, STR(SUM(TMINUTOS)/60,5,2) AS THORAS ";
		$ssql.=" FROM vCA_INCIDENCIAS_REMUNERACION V ";
		$ssql.=" WHERE Asistencia_fecha BETWEEN CONVERT(DATETIME, '".$this->te_fecha_inicio."' ,103) "; 
		$ssql.=" AND CONVERT(DATETIME, '".$this->te_fecha_fin."', 103) ";
		$ssql.=" AND INCIDENCIA_CODIGO not in (137) ";
		$ssql.=" GROUP BY EMPLEADO_DNI, ASISTENCIA_FECHA ";
		$ssql.=" ORDER BY 6 DESC ";
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .=$rs->fields[0]." ";
				$cadena .=$rs->fields[1]." ";
				$cadena .=$rs->fields[2]." ";
				$cadena .=trim($rs->fields[3])." ";
				$cadena .=trim($rs->fields[4])." ";
				$cadena .=trim($rs->fields[5])."\n";
				$rs->MoveNext(); 
			}
			//$cadena.= "";
		}
	 	return $cadena;
	}
}

function Reporte32(){
	$cadena="No hay registros en este rango de fechas";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql =" select e.empleado_codigo , e.empleado_dni as RUT,  ".
		$ssql.=" e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres as Empleado,";
		$ssql.=" a.area_descripcion as area, a2.area_descripcion as gerencia, ";
		$ssql.=" e2.empleado_apellido_paterno + ' ' + e2.empleado_apellido_materno + ' ' + e2.empleado_nombres as Supervisor_Actual,";
		$ssql.=" e3.empleado_apellido_paterno + ' ' + e2.empleado_apellido_materno + ' ' + e2.empleado_nombres as Supervisor_Anterior, ";
		$ssql.=" convert (varchar(10),ae.fecha_reg,103), case ae.asignacion_activo when 0 then 'Historico' when 1 then 'Activo' end "; 
		$ssql.=" from ca_asignacion_empleados ae ";
		$ssql.=" inner join empleados e on ae.empleado_codigo=e.empleado_codigo ";
		$ssql.=" inner join empleado_area ea on ea.empleado_codigo=e.empleado_codigo ";
		$ssql.=" inner join areas a on a.area_codigo=ea.area_codigo ";
		$ssql.=" inner join areas a2 on a2.area_codigo=a.area_jefe ";
		$ssql.=" inner join empleados e2 on e2.empleado_codigo = ae.responsable_codigo";
		$ssql.=" inner join empleados e3 on e3.empleado_codigo = ae.responsable_origen ";
		$ssql.=" where ea.empleado_area_activo=1 and ae.fecha_reg > convert(datetime, '" . $this->te_fecha_inicio . "',103) and ae.fecha_reg <= convert(datetime, '" . $this->te_fecha_fin . "' ,103) ";
		$ssql.=" order by 1";
		
        $rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
            $estilo="style='FONT-WEIGHT: bold; FONT-SIZE: 10px; COLOR: white; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; background: #0066CC;'";
            $estilo2="style='FONT-SIZE: 10px; COLOR: black; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; '";

			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'><tr><td colspan='7'>&nbsp;</td></tr> ";
			$cadena.= "<tr align='center'><td style='FONT-WEIGHT: bold; FONT-SIZE: 10px; COLOR: black; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; ' colspan='7'> ";
			$cadena.= "REPORTE DE TRANSFERENCIA DE SUPERVISION DEL  ". $this->te_fecha_inicio ." AL ".$this->te_fecha_fin." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' >
		    	<td " . $estilo . " ><b>DNI</td>
		    	<td " . $estilo . " ><b>EJECUTIVO</td>
		    	<td " . $estilo . " ><b>JEFATURA</td>
				<td " . $estilo . " ><b>GERENCIA</td>
				<td " . $estilo . " ><b>SUPERVISOR ACTUAL</td>
				<td " . $estilo . " ><b>SUPERVISOR ANTERIOR</td>
		    	<td " . $estilo . " ><b>FECHA REGISTRO</td>
		    	<td " . $estilo . " ><b>SITUACION</td>
				
			</tr>";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[8] . "</td>";
						
				$cadena .="</tr>";
				$rs->MoveNext(); 
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Reporte33(){
	$cadena="No hay registros en este rango de fechas";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql =" select e.empleado_codigo , e.empleado_dni as RUT,   ";
		$ssql.=" e.empleado_apellido_paterno + ' ' + e.empleado_apellido_materno + ' ' + e.empleado_nombres as Empleado,";
		$ssql.=" a.area_descripcion as area, a2.area_descripcion as gerencia, ";
		$ssql.=" vc.exp_descripcion, convert (varchar(10),es.empleado_servicio_fecha,103),";
		$ssql.=" case empleado_servicio_activo when 0 then 'Historico' when 1 then 'Activo' end  ";
		$ssql.=" from empleado_servicio es ";
		$ssql.=" inner join empleados e on es.empleado_codigo=e.empleado_codigo ";
		$ssql.=" inner join empleado_area ea on ea.empleado_codigo=e.empleado_codigo ";
		$ssql.=" inner join areas a on a.area_codigo=ea.area_codigo ";
		$ssql.=" inner join areas a2 on a2.area_codigo=a.area_jefe ";
		$ssql.=" inner join v_campanas vc on vc.cod_campana=es.cod_campana   ";
		$ssql.=" where ea.empleado_area_activo=1 and es.empleado_servicio_fecha > convert(datetime,'" . $this->te_fecha_inicio . "',103) and es.empleado_servicio_fecha <= convert(datetime,'" . $this->te_fecha_fin . "' ,103) ";
		$ssql.=" order by 1";
		
		//echo $ssql;
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
    		$estilo="style='FONT-WEIGHT: bold; FONT-SIZE: 10px; COLOR: white; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; background: #0066CC;'";
       
            $estilo2="style='FONT-SIZE: 10px; COLOR: black; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; '";

			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'><tr><td colspan='7'>&nbsp;</td></tr> ";
			$cadena.= "<tr align='center'><td style='FONT-WEIGHT: bold; FONT-SIZE: 10px; COLOR: black; FONT-FAMILY: Verdana, Arial, Helvetica, sans-serif; ' colspan='7'> ";
			$cadena.= "REPORTE DE TRANSFERENCIA DE UNIDAD DE SERVICIO DEL  ". $this->te_fecha_inicio ." AL ".$this->te_fecha_fin." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' >
		    	<td " . $estilo . " ><b>DNI</td>
		    	<td " . $estilo . " ><b>EJECUTIVO</td>
		    	<td " . $estilo . " ><b>JEFATURA</td>
				<td " . $estilo . " ><b>GERENCIA</td>
				<td " . $estilo . " ><b>SERVICIO</td>
				<td " . $estilo . " ><b>FECHA REGISTRO</td>
		    	<td " . $estilo . " ><b>SITUACION</td>
				
			</tr>";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td " . $estilo2 . " align=left>" . $rs->fields[7] . "</td>";

				$cadena .="</tr>";
				$rs->MoveNext(); 
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Reporte37(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql =" select 1 as tipo, e.empleado_dni as Rut, e.empleado_apellido_paterno + ' ' + ";
		$ssql.=" e.empleado_apellido_materno + ' ' + e.empleado_nombres as nombres, v.te_semana, ";
		$ssql.=" v.te_anio, v.tc_codigo_sap, convert(varchar(10),v.te_fecha_inicio,103) as inicio, ";
		$ssql.=" convert(varchar(10),v.te_fecha_fin,103) as fin, ";
		$ssql.=" tc.tturno_dia1, tc.tturno_dia2, tc.tturno_dia3, tc.tturno_dia4, tc.tturno_dia5, ";
		$ssql.=" tc.tturno_dia6, tc.tturno_dia7, ";
		$ssql.=" tc.lturno_dia1, tc.lturno_dia2, tc.lturno_dia3, tc.lturno_dia4, tc.lturno_dia5, ";
		$ssql.=" tc.lturno_dia6, tc.lturno_dia7 ";
		$ssql.=" from vCA_Turnos_EmpleadoTHF v join empleados e on v.empleado_codigo=e.empleado_codigo ";
		$ssql.=" join vca_turnos_combinacionth tc on v.tc_codigo=tc.tc_codigo ";
		$ssql.=" where e.empleado_dni='".$this->empleado_dni."' and v.te_anio=".$this->te_anio;
		$ssql.=" union ";
		$ssql.=" select 2 as tipo, e.empleado_dni as Rut, e.empleado_apellido_paterno + ' ' + ";
		$ssql.=" e.empleado_apellido_materno + ' ' + e.empleado_nombres as nombres, te_semana, ";
		$ssql.=" te_anio, tc_codigo_sap, convert(varchar(10),v.te_fecha_inicio,103) as inicio, ";
		$ssql.=" convert(varchar(10),v.te_fecha_fin,103) as fin, ";
		$ssql.=" tturno_dia1, tturno_dia2, tturno_dia3, tturno_dia4, tturno_dia5, tturno_dia6, ";
		$ssql.=" tturno_dia7, ";
		$ssql.=" lturno_dia1, lturno_dia2, lturno_dia3, lturno_dia4, lturno_dia5, lturno_dia6, ";
		$ssql.=" lturno_dia7 ";
		$ssql.=" from vCA_Turnos_EmpleadoTHF v join empleados e on v.empleado_codigo=e.empleado_codigo ";
		$ssql.=" where e.empleado_dni='".$this->empleado_dni."' and v.te_anio=".$this->te_anio;
		$ssql.=" order by 1,4 ";
		$rs = $cn->Execute($ssql);
        if ($rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='21'> ";
			$cadena.= "<b>DETALLE DE PROGRAMACION DE TURNOS PUBLICADO AÑO : " . $this->te_anio . " 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>RUT</td>
		    	<td class='ColumnTD'><b>NOMBRES</td>
		    	<td class='ColumnTD'><b>SEMANA</td>
				<td class='ColumnTD'><b>AÑO</td>
				<td class='ColumnTD'><b>COD_COMB.</td>
				<td class='ColumnTD'><b>INICIO</td>
				<td class='ColumnTD'><b>FIN</td>
				<td class='ColumnTD'><b>LUNES</td>
				<td class='ColumnTD'><b>MARTES</td>
				<td class='ColumnTD'><b>MIERCOLES</td>
				<td class='ColumnTD'><b>JUEVES</td>
				<td class='ColumnTD'><b>VIERNES</td>
				<td class='ColumnTD'><b>SABADO</td>
				<td class='ColumnTD'><b>DOMINGO</td>
				<td class='ColumnTD'><b>COL_LUN</td>
				<td class='ColumnTD'><b>COL_MAR</td>
				<td class='ColumnTD'><b>COL_MIE</td>
				<td class='ColumnTD'><b>COL_JUE</td>
				<td class='ColumnTD'><b>COL_VIE</td>
				<td class='ColumnTD'><b>COL_SAB</td>
				<td class='ColumnTD'><b>COL_DOM</td>
			</tr>";
			$tmp="";
			//$rs = mssql_fetch_row($rs_result);
			while(!$rs->EOF){
				if ($rs->fields[0]=="2" and $tmp == ""){
					$tmp = "X";
					$cadena.= "<tr align='center'><td class='ColumnTD' colspan='21'>&nbsp;</td></tr>";
					$cadena.= "<tr align='center'><td class='ColumnTD' colspan='21'> ";
					$cadena.= "<b>DETALLE DE PROGRAMACION DE TURNOS EJECUTADO AÑO : " . $this->te_anio . " 
								 </td>
				 			   </tr>";
					$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
						    	<td class='ColumnTD'><b>RUT</td>
						    	<td class='ColumnTD'><b>NOMBRES</td>
						    	<td class='ColumnTD'><b>SEMANA</td>
								<td class='ColumnTD'><b>AÑO</td>
								<td class='ColumnTD'><b>COD_COMB.</td>
								<td class='ColumnTD'><b>INICIO</td>
								<td class='ColumnTD'><b>FIN</td>
								<td class='ColumnTD'><b>LUNES</td>
								<td class='ColumnTD'><b>MARTES</td>
								<td class='ColumnTD'><b>MIERCOLES</td>
								<td class='ColumnTD'><b>JUEVES</td>
								<td class='ColumnTD'><b>VIERNES</td>
								<td class='ColumnTD'><b>SABADO</td>
								<td class='ColumnTD'><b>DOMINGO</td>
								<td class='ColumnTD'><b>COL_LUN</td>
								<td class='ColumnTD'><b>COL_MAR</td>
								<td class='ColumnTD'><b>COL_MIE</td>
								<td class='ColumnTD'><b>COL_JUE</td>
								<td class='ColumnTD'><b>COL_VIE</td>
								<td class='ColumnTD'><b>COL_SAB</td>
								<td class='ColumnTD'><b>COL_DOM</td>
							</tr>";
				}
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[1] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[2] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[4] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[5] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[6] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[7] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[8] . "</td>"; //lunes
				$cadena .="<td align=left>" . $rs->fields[9] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[10] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[11] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[12] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[13] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[14] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[15] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[16] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[17] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[18] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[19] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[20] . "</td>";
				$cadena .="<td align=left>" . $rs->fields[21] . "</td>";
				$cadena .="</tr>";
				$rs->MoveNext(); 
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Log01(){
	$cadena="";
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="select * from ca_turno_log where tl_carga_codigo='".$this->carga_codigo."' order by tl_codigo";
		$rs = $cn->Execute($ssql);
		if( $rs->RecordCount()>0){
			$cadena = "<table class='FormTable' id='listado' width='100%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:100%'> ";
			$cadena.= "<tr align='center'><td class='ColumnTD' colspan='2'> ";
			$cadena.= "<b>LOG DE CARGA PARA EL REGITRO ".$this->carga_codigo." 
						 </td>
		 			   </tr>";
			$cadena.= "<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>DNI</td>
		    	<td class='ColumnTD'><b>DESCRIPCION</td>
			</tr>";
			while(!$rs->EOF){
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=left>" . $rs->fields[2] . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3] . "&nbsp;</td>";
				$cadena .="</tr>";
				$rs->MoveNext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
}

function Query_Empleado_Nombres(){
	$rpta="OK";
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql  = " select empleado,empleado_dni "; 
		$ssql .= " from vdatos "; 
		$ssql .= " where empleado_codigo = " . $this->empleado_codigo; 
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$this->empleado_nombres = $rs->fields[0];
			$this->empleado_dni = $rs->fields[1];
		}else{
			$rpta='No Existe Nombre de: ' . $this->empleado_codigo;
		}
	 } 
	return $rpta;
}

function Importar_Programacion(){
	$rpta='OK';
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql ="exec spCA_Importar '" . $this->empleado_dni . "','" . $this->tc_codigo_sap;
		$ssql.="','" . $this->te_fecha_inicio . "','" . $this->te_fecha_fin;
		$ssql.="'," . $this->empQuery_Existe_Cambiosleado_codigo_registro . ",'" . $this->carga_codigo . "'";
		//echo $ssql;
		$rs = $cn->Execute($ssql);
/*		$result_store = mssql_query($ssql);
		$rs_store= mssql_fetch_row($result_store);
		$rpta='0';
		if ($rs_store[0]>0){
			$rpta = $rs_store[0];
		}
		switch ($rpta){
			case '1': $msgActualizacion="<br>Error al Modificar Registro de Empleado ";
				break;
		}*/
	}
}

function Importar_Gestion(){
	$rpta='OK';
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql ="exec spCA_Importarg '" . $this->empleado_dni;
		$ssql.="','" . $this->te_fecha_inicio . "','" . $this->tc_codigo_sap;
		$ssql.="'," . $this->empleado_codigo_registro . ",'" . $this->carga_codigo . "'";
		$rs = $cn->Execute($ssql);
	}
}

function Importar_Gestion_Tmp(){
	$rpta='OK';
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql ="exec spCA_Importar_tmp " . $this->empleado_codigo_registro . ",'" . $this->carga_codigo . "'";
		$rs = $cn->Execute($ssql);
	}
}

function Importar_Gestion_Tmp_Rezagados(){
	$rpta='OK';
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql ="exec spCA_Importar_tmp_rezagados ".$this->empleado_codigo_registro.",'".$this->carga_codigo."'";
		$rs = $cn->Execute($ssql);
	}
}

function Importar_Turnos_Tmp(){
	$rpta='OK';
	$ssql = '';
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	if($cn){
	    $params = array($this->empleado_codigo_registro,$this->carga_codigo );
		$ssql ="exec spCA_Importar_tmp_t ?,?";
		$rs = $cn->Execute($ssql, $params);
	}
}

function Importar_Turnos_Tmp_Rezagados(){
	$rpta='OK';
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	if($cn){
		$ssql ="exec spCA_Importar_tmp_t_rezagados ".$this->empleado_codigo_registro.",'".$this->carga_codigo."'";
		$rs = $cn->Execute($ssql);
	}
}

function Importar_Turno_Extendido(){
/*	$rpta="OK";
    $link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	$ssql ="exec spCA_Importar_Turno_Extendido ".$this->empleado_codigo_registro.",'".$this->carga_codigo."'";
    $r=mssql_query($ssql);
	return $rpta;
*/
	$rpta='OK';
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql ="exec spCA_Importar_Turno_Extendido ".$this->empleado_codigo_registro.",'".$this->carga_codigo."'";
	    $rs = $cn->Execute($ssql);
	}
	return $rpta;
}

function Delete_Importar_Tmp(){
	$rpta="OK";
	$cn = $this->getMyConexionADO(); 
	if($cn){
		$ssql = "DELETE from ca_turno_tmp where tl_carga_codigo=".$this->carga_codigo;
	    $rs = $cn->Execute($ssql);
	}
	return $rpta;
}

function Delete_Importar_Tmp_T(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "DELETE from ca_turno_tmp_t where tl_carga_codigo=".$this->carga_codigo;
		$rs =$cn->Execute($ssql);
	}
	return $rpta;
}

function Addnew_Tmp(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->carga_codigo,
                        $this->empleado_dni,
                        $this->te_fecha_inicio,
                        $this->tc_codigo_sap,
                        $this->te_fecha_fin);
		
        $ssql = "INSERT INTO ca_turno_tmp ";
		$ssql .= " (tl_carga_codigo, dato1, dato2, dato3, dato4) ";
		$ssql .= " VALUES (?,?,?,?,?)";
		$rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Addnew_Tmp_Nuevo(){

	$rpta="OK";
    $cn = $this->getMyConexionADO();
   // $cn->debug = true;
	$ssql = "INSERT INTO ca_turno_tmp ";
	$ssql.= " (tl_carga_codigo, dato1, dato2, dato3, dato4) ";
	$ssql.= " VALUES ('".$this->carga_codigo."','".$this->empleado_dni."','".$this->te_fecha_inicio;
	$ssql.= "','".$this->tc_codigo_sap."','".$this->te_fecha_fin."')";
    $rs =$cn->Execute($ssql);
	return $rpta;
}

function Addnew_Tmp_T_Nuevo(){
	$rpta="OK";
	$rs = '';
	$params = array();
	$ssql = '';
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $params = array(
                    $this->carga_codigo,
                    $this->empleado_dni,
                    $this->tc_codigo_sap,
                    $this->te_fecha_inicio,
                    $this->te_fecha_fin
                );
	$ssql = " INSERT INTO ca_turno_tmp_t ";
	$ssql .= " (tl_carga_codigo, dato1, dato2, dato3, dato4) ";
	$ssql .= " VALUES (?,?,?,?,?)";
    $rs= $cn->Execute($ssql, $params);
	return $rpta;
}

function Addnew_Tmp_T(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
		$params = array($this->carga_codigo,
                        $this->empleado_dni,
                        $this->tc_codigo_sap,
                        $this->te_fecha_inicio,
                        $this->te_fecha_fin);
		
        
        $ssql = "INSERT INTO ca_turno_tmp_t ";
		$ssql .= " (tl_carga_codigo, dato1, dato2, dato3, dato4) ";
		$ssql .= " VALUES (?,?,?,?,?)";
		$rs= $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Update_PorDefecto(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " set dateformat dmy ";
		$ssql.= " insert into ca_turno_empleado ";
		$ssql.= " select e.empleado_codigo, ".$this->te_semana." as te_semana, ".$this->te_anio." as te_anio, ";
		$ssql.= " e.tc_codigo, '".$this->te_fecha_inicio."' as te_fecha_inicio, ";
		$ssql.= " '".$this->te_fecha_fin."' as te_fecha_fin, tc.turno_dia1, tc.turno_dia2, ";
		$ssql.= " tc.turno_dia3, tc.turno_dia4, tc.turno_dia5, ";
		$ssql.= " tc.turno_dia6, tc.turno_dia7, getdate() as fecha_registro, ";
		$ssql.= " ".$this->empleado_codigo_registro." as empleado_codigo_registro, ";
		$ssql.= " null as fecha_modificacion, null as empleado_codigo_modificacion";
		$ssql.= " from empleados e inner join ca_turnos_combinacion tc on e.tc_codigo=tc.tc_codigo ";
		$ssql.= " where not exists ";
		$ssql.= " (select empleado_codigo from ca_turno_empleado te ";
		$ssql.= " where e.empleado_codigo=te.empleado_codigo ";
		$ssql.= " and te.te_semana=".$this->te_semana." and te.te_anio=".$this->te_anio.") ";
	    $rs= $cn->Execute($ssql);
	}
	return $rpta;
}

function Update_Replicar(){ //Replicar
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql ="exec spCA_Carga_Programacion " . $this->empleado_codigo_registro;
		$rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$rpta = $rs->fields[0];
		}
	}
	return $rpta;
}

function Addnew_Tmp_Extendido(){
	$rpta="OK";
    $cn = $this->getMyConexionADO();
	$ssql = "INSERT INTO ca_turno_tmp_t ";
	$ssql .= " (tl_carga_codigo, dato4) ";
	$ssql .= " VALUES (".$this->carga_codigo.",'".$this->empleado_dni."')";
    $rs = $cn->Execute($ssql);
	return $rpta;
}

function xxAddnew_Tmp_Extendido(){ //para carga masiva no funciona la conexion ADO
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "INSERT INTO ca_turno_tmp_t ";
		//$ssql = "INSERT INTO ca_turno_temporal ";
		$ssql .= " (tl_carga_codigo, dato4) ";
		$ssql .= " VALUES (".$this->carga_codigo.",".$this->empleado_dni.")";
        $rs = $cn->Execute($ssql);
	}
	return $rpta;
}


function Addnew_Turno_Carga(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    //$cn->debug = true;
    $ssql = "INSERT INTO ca_turno_carga ";
    $ssql .= " (empleado_codigo_carga,fecha_registro,nombre_archivo_origen,nombre_archivo_carga,tl_carga_codigo) ";
    $ssql .= " VALUES (".$this->empleado_codigo_registro.",getdate(),'";
    $ssql .= $this->nombre_archivo_origen."','".$this->nombre_archivo_carga."','".$this->carga_codigo."')";
    $rs= $cn->Execute($ssql);
    return $rpta;
}


function Query_Turnos_Log(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	if($cn){
	    $params = array($this->carga_codigo);
		$ssql = " select tl_empleado_dni,tl_cadena_error from ca_turno_log ";
		$ssql.= " where tl_carga_codigo= ?";
		$ssql.= " ORDER BY tl_codigo ";
		//echo $ssql;
		$rs = $cn->Execute($ssql, $params);
		if(!$rs->EOF) {
			while(!$rs->EOF){
				//$cadena .=$rs->fields[0]->value . "<br>";
				echo "<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				echo "<td align=center>" . $rs->fields[0]  . "&nbsp;</td>";
				echo "<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				echo "</tr>";
				if (trim($rs->fields[1]) === '(OK)'){
					//echo "entro";
					$emp_cod=$this->EmpleadoDNI($rs->fields[0]);
					$this->Update_Asistencia_Programada($emp_cod);
				}
				$rs->MoveNext();
			}
		}
	 	//return $cadena;
	}
}

function Query_Turnos_Log_Nuevo(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = " select tl_empleado_dni,tl_cadena_error from ca_turno_log ";
		$ssql.= " where tl_carga_codigo=" . $this->carga_codigo;
		$ssql.= " order by 1 ";
		//echo $ssql;
		$rs = $cn->Execute($ssql);
		if ($rs->RecordCount()>0){
			while(!$rs->EOF){
				//$cadena .=$rs->fields[0]->value . "<br>";
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center>" . $rs->fields[0]  . "&nbsp;</td>";
				$cadena.="<td align=left>" . $rs->fields[1]  . "&nbsp;</td>";
				$cadena.="</tr>";
				$rs->MoveNext(); 
			}
		}
	 	return $cadena;
	}
}

function Query_Turno_Feriados(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $ssql = " set datefirst 1  ";
        $rs = $cn->Execute($ssql);
        
		$ssql = " Select datepart(w,fecha_feriado) "; 
		$ssql .= " from ca_feriados "; 
		$ssql .= " where convert(varchar(8),fecha_feriado,112) between " . $this->te_fecha_inicio . " and " . $this->te_fecha_fin; 
	    $rs = $cn->Execute($ssql);
		//echo $ssql;
		if (!$rs->EOF){
			while(!$rs->EOF){
				if ($rs->fields[0] == 1) $this->tferiado1 = " style='color:red;' ";
				if ($rs->fields[0] == 2) $this->tferiado2 = " style='color:red;' ";
				if ($rs->fields[0] == 3) $this->tferiado3 = " style='color:red;' ";
				if ($rs->fields[0] == 4) $this->tferiado4 = " style='color:red;' ";
				if ($rs->fields[0] == 5) $this->tferiado5 = " style='color:red;' ";
				if ($rs->fields[0] == 6) $this->tferiado6 = " style='color:red;' ";
				if ($rs->fields[0] == 7) $this->tferiado7 = " style='color:red;' ";
				$rs->movenext();
			}
		}else{
		   $rpta='No Existe Feriados En Esta Semana';
		}
	} 
	return $rpta;
}

function Query_Semana(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
            $ssql=" set datefirst 1 ";
            $rs=$cn->Execute($ssql);
            $ssql = "select datepart(wk,'".$this->te_aniomes."') as te_semana "; 
            $rs = $cn->Execute($ssql);
            if (!$rs->EOF){
                $this->te_semana = $rs->fields[0];
            }else{
                $rpta='NE';
            }
	}
	return $rpta;
}

function Query_Turno_Fechas(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	if($cn){
		if ($this->te_fecha_inicio==''){
		    $params = array(
	                        $this->te_semana,
	                        $this->te_anio
	                    );
			$ssql  = " select top 1 "; 
			$ssql .= " convert(varchar(10),te_fecha_inicio,103),convert(varchar(10),te_fecha_fin,103) "; 
			$ssql .= " from ca_turno_empleado  "; 
			$ssql .= " where te_semana = ? "; 
			$ssql .= " and te_anio= ?";
   		}else{
		    $params = array(
	                        $this->te_fecha_inicio,
	                        $this->te_fecha_fin
	                    );
			$ssql  = " select top 1 "; 
			$ssql .= " convert(varchar(10),te_fecha_inicio,103),convert(varchar(10),te_fecha_fin,103) "; 
			$ssql .= " from ca_turno_empleado  "; 
			$ssql .= " where te_fecha_inicio = convert(datetime,?,103) "; 
			$ssql .= " and te_fecha_fin= convert(datetime,?,103) ";
   		}
	    $rs = $cn->Execute($ssql, $params);
		//echo $ssql;
		if ($rs->RecordCount() > 0){
			$this->te_fecha_inicio = $rs->fields[0];
			$this->te_fecha_fin = $rs->fields[1];
	  }else{
		   $rpta='No Existe Programacion de Esta Semana';
	  }
	 } 
	return $rpta;
}

function Obtener_Fechas(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    if($cn){
        $ssql  = " SELECT convert (varchar(10) , DATEADD(wk, DATEDIFF(wk, 6, '1/1/" . $this->te_anio . "' ) + ("  . $this->te_semana . "-1), 6) + 1 , 103) AS StartOfWeek , convert (varchar (10) , DATEADD(wk, DATEDIFF(wk, 5, '1/1/"  . $this->te_anio . "' ) + (" . $this->te_semana. "-1), 5) + 1 , 103 ) AS EndOfWeek  ";
        $rs = $cn->Execute($ssql);
        //echo $ssql;
        if (!$rs->EOF){
            $this->te_fecha_inicio = $rs->fields[0];
            $this->te_fecha_fin = $rs->fields[1];
        }else{
            $rpta='No Existe ';
        }
    } 
    
    return $rpta;
}

function Query_Turno_Publicar(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
    //$cn->debug = true;
	if($cn){
		$ssql  = " select top 1 DATEPART(ww, getdate()+14), year(getdate()+14), "; 
		$ssql .= " convert(varchar(10),te_fecha_inicio,103), convert(varchar(10),te_fecha_fin,103) "; 
		$ssql .= " from ca_turno_empleado "; 
		$ssql .= " where te_semana=DATEPART(ww, getdate()+14) and te_anio=year(getdate()+14) "; 
	    $rs = $cn->Execute($ssql);
		//echo $ssql;
		if (!$rs->EOF){
			$this->te_semana = $rs->fields[0];
			$this->te_anio = $rs->fields[1];
			$this->te_fecha_inicio = $rs->fields[2];
			$this->te_fecha_fin = $rs->fields[3];
	  }else{
		   $rpta='No Existe Programacion de Esta Semana';
	  }
	 } 
	return $rpta;
}

function Query_Rol_Admin(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array($this->empleado_codigo_registro);
		$ssql = " select rol_codigo "; 
		$ssql.= " from ca_empleado_rol where empleado_rol_activo=1 "; 
		$ssql.= " and empleado_codigo=? and rol_codigo=3"; 
	    $rs = $cn->Execute($ssql, $params);
		if(!$rs->EOF){
			$rpta = "OK";
		}else{
			$rpta = "NOT";
		}
	}
	return $rpta;
}

function Query_Rol_Super(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array($this->empleado_codigo_registro);
		$ssql = " select rol_codigo "; 
		$ssql.= " from ca_empleado_rol where empleado_rol_activo=1 "; 
		$ssql.= " and empleado_codigo=? and rol_codigo=1"; 
	    $rs = $cn->Execute($ssql, $params);
		if (!$rs->EOF){
			$rpta = "OK";
		}else{
			$rpta = "NOT";
		}
	}
	return $rpta;
}

function Query_Rol_Numero($rol_codigo){
	$rpta="";
	$cn = $this->getMyConexionADO();
	if($cn){
	    $params = array($this->empleado_codigo_registro,$rol_codigo);
		$ssql = " select rol_codigo "; 
		$ssql.= " from ca_empleado_rol where empleado_rol_activo=1 "; 
		$ssql.= " and empleado_codigo=? and rol_codigo=?"; 
	    $rs = $cn->Execute($ssql, $params);
		if(!$rs->EOF){
			$rpta = "OK";
		}else{
			$rpta = "NOT";
		}
	}
	return $rpta;
}

function Query_Numero_Semana(){
    $rpta="OK";
    $cn = $this->getMyConexionADO();
    
    if($cn){
        $ssql  = "set datefirst 1";
        $rs = $cn->Execute($ssql);
        $ssql  = "select DATEPART(ww, getdate()) as te_semana, year(getdate()) as te_anio, convert(varchar(10),getdate()+(1-DATEPART(w, getdate())),103) as te_fecha_inicio, convert(varchar(10),getdate()+(7-DATEPART(w, getdate())),103) as te_fecha_fin "; 
        $rs = $cn->Execute($ssql);
        if($rs->RecordCount() > 0){
            $this->te_semana = $rs->fields[0];
            $this->te_anio = $rs->fields[1];
            $this->te_fecha_inicio = $rs->fields[2];
            $this->te_fecha_fin = $rs->fields[3];
        }else{
           $rpta='No Se Puede Calcular Semana';
        }
    }
    
    return $rpta;
}

function Query_Numero_Semana_siguiente(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
    $ssql  = " set datefirst 1";
    $rs = $cn->Execute($ssql);
    $ssql = " select DATEPART(ww, getdate()+7) as te_semana, year(getdate()+7) as te_anio, "; 
    $ssql.= " convert(varchar(10),getdate()+((7+1)-DATEPART(w, getdate())),103) as inicio, "; 
    $ssql.= " convert(varchar(10),getdate()+(7*2-DATEPART(w, getdate())),103) as fin "; 
    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$this->te_semana = $rs->fields[0];
			$this->te_anio = $rs->fields[1];
			$this->te_fecha_inicio = $rs->fields[2];
			$this->te_fecha_fin = $rs->fields[3];
	  }else{
		   $rpta='No Se Puede Calcular Semana';
	  }
	 } 
	return $rpta;
}

function Generar_Semana(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	//$cn->debug=true;
	if($cn){
		
            $ssql="exec wfm_Generar_Semana_DD '" . $this->te_fecha_inicio . "', '" . $this->te_fecha_fin . "'," . $this->empleado_codigo_registro;

            //$ssql="exec wfm_Generar_Semana_LM '" . $this->te_fecha_inicio . "', '" . $this->te_fecha_fin . "'," . $this->empleado_codigo_registro;

            //$ssql="exec wfm_Generar_Semana_VA '" . $this->te_fecha_inicio . "', '" . $this->te_fecha_fin . "'," . $this->empleado_codigo_registro;

            // $result1=mssql_query($ssql);
            //$rs1= mssql_fetch_row($result1);
            $rs1 = $cn->Execute($ssql);
            if ($rs1->RecordCount()>0){
                $rpta1 = $rs1->fields[1];
                //echo $ssql;
                
            }
	    
            $ssql="exec wfm_Generar_Semana_LM '" . $this->te_fecha_inicio . "', '" . $this->te_fecha_fin . "'," . $this->empleado_codigo_registro;

	    $rs2 = $cn->Execute($ssql);
            if ($rs2->RecordCount()>0){
                $rpta2 = $rs2->fields[1];
                //echo $ssql;
            }

            $ssql="exec wfm_Generar_Semana_VA '" . $this->te_fecha_inicio . "', '" . $this->te_fecha_fin . "'," . $this->empleado_codigo_registro;

            $rs3 = $cn->Execute($ssql);
            if ($rs3->RecordCount()>0){
                $rpta3 = $rs3->fields[1];
                //echo $ssql;
            }

	}else{
            echo 'Error al establecer conexion:' . $rpta;
	}
	
	if ($rpta1 == "OK" && $rpta2 == "OK" && $rpta3 == "OK" ){
            $rpta= "OK";
	}else{
            echo $rpta1."-".$rpta2."-".$rpta3;
            $rpta="NOT";
	}
	
	return $rpta;
}

function eliminar_carga_semana(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	//$cn->debug=true;
	if($cn){
            $ssql=" delete wfm_empleado_restricciones ";
            $ssql.= " where fecha between convert(datetime , '" . $this->te_fecha_inicio . "' , 103) and convert(datetime , '" . $this->te_fecha_fin . "' , 103) and ";
            $ssql.= " area in (select area_codigo from ca_controller
               where empleado_codigo=" . $this->empleado_codigo_registro ."  and activo=1)";
		
            $rs = $cn->Execute($ssql);
            //$result=mssql_query($ssql);
		  
            $ssql=" delete wfm_empleado_disponibilidad ";
            $ssql.= " where fecha between convert(datetime , '" . $this->te_fecha_inicio . "' , 103) and convert(datetime , '" . $this->te_fecha_fin . "' , 103) and ";
            $ssql.= " area in (select area_codigo from ca_controller
               where empleado_codigo=" . $this->empleado_codigo_registro ."  and activo=1)";   

            //$result2=mssql_query($ssql);
            $rs2 = $cn->Execute($ssql);
            //$ssql= " select  * from empleado_restricciones ";
            //$ssql.=" ";
	}
	
	return $rpta;
}

function Genera_Programacion_Automatica_Semanal(){

    $rpta="";
    $cn = $this->getMyConexionADO();
    //$cn->debug=true;
    //$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    //mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
    if($cn){
        $rpta="OK";
        /*$ssql="exec wfm_procesar_programacion " . $this->te_semana . " , " . $this->te_anio . " , " . $this->empleado_codigo_registro;
        echo $ssql;
        $result = mssql_query($ssql);
        $rs=mssql_fetch_row($result);
        echo 'Resultado: ' . $rs[1];
        */
        $ssql ="exec wfm_programacion " . $this->te_semana . " , " . $this->te_anio . " , " . $this->empleado_codigo_registro;
        //echo $ssql;
        //$result = mssql_query($ssql);
        $rs = $cn->Execute($ssql);
        /*
        $ssql ="exec wfm_combinacion " . $this->te_semana . " , " . $this->te_anio . " , " . $this->empleado_codigo_registro;
        echo $ssql;
        $result2 = mssql_query($ssql);
        */
        return $rpta;
    }
}

function Genera_Combinacion_Automatica_Semanal(){

	$rpta="";
	$cn = $this->getMyConexionADO();
    //$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
    //mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD " . mysql_error());
	if($cn){
		/*$ssql="exec wfm_procesar_programacion " . $this->te_semana . " , " . $this->te_anio . " , " . $this->empleado_codigo_registro;
		echo $ssql;
		$result = mssql_query($ssql);
		$rs=mssql_fetch_row($result);
		echo 'Resultado: ' . $rs[1];
		*/
                $rpta="OK";
		
		$ssql ="exec wfm_combinacion " . $this->te_semana . " , " . $this->te_anio . " , " . $this->empleado_codigo_registro;
		//echo $ssql;
		//$result = mssql_query($ssql);
        $rs = $cn->Execute($ssql);
		/*
		$ssql ="exec wfm_combinacion " . $this->te_semana . " , " . $this->te_anio . " , " . $this->empleado_codigo_registro;
		echo $ssql;
		$result2 = mssql_query($ssql);
		*/
	 	return $rpta;
	}
}

function Generar_Programacion_Semanal(){
	$rpta="";
	//$link = mssql_connect($this->getMyUrl(), $this->getMyUser(), $this->getMyPwd()) or die("No puedo conectarme a la BD" . mysql_error());
	//mssql_select_db($this->getMyDBName()) or die("No puedo seleccionar BD" . mysql_error());
	
	$cn = $this->getMyConexionADO();
	if ($cn){
		//Para obtener los servicios a cargo del controller 
		$ssql = " select distinct codigo_campana ";
	    $ssql .= " from vWFM_Requerimiento ";
	    $ssql .= " where usuario_registro=" . $this->empleado_codigo_registro . " and ";
	    $ssql .= " semana =" . $this->te_semana . " and anio=" . $this->te_anio . " and codigo_campana=14585";
   		//echo $ssql;
   		//$result = mssql_query($ssql);
        $rs = $cn->Execute($ssql);
	    
        while(!$rs->EOF){
	    //while ($rs= mssql_fetch_row($result)){
	    	//Para obtener lo requerido por el servicio por fecha
	    	$ssql = " select hora_inicio , hora_fin , minuto_inicio , minuto_fin , req , semana , anio , convert(varchar(10),fecha,103) ";
	    	$ssql .= " from vWFM_Requerimiento ";
	    	$ssql .= " where codigo_campana=" . $rs->fields[0] . " and ";
			$ssql .= " semana= " . $this->te_semana . " and ";
			$ssql .= " anio=" . $this->te_anio . " and " ; 
			$ssql .= " usuario_registro=" . $this->empleado_codigo_registro;
			$ssql .= " order by fecha";

	    	//$result2 = mssql_query($ssql);
            $rs2 = $cn->Execute($ssql);
	    	while(!$rs2->EOF){
	    	//while ($rs2= mssql_fetch_row($result2)){
	    		$hi=$rs2->fields[0];
	    		$hf=$rs2->fields[1];
	    		$mi=$rs2->fields[2];
	    		$mf=$rs2->fields[3];
	    		$req= $rs2->fields[4];
	    		$semana=$rs2->fields[5];
	    		$anio=$rs2->fields[6];
	    		$fecha=$rs2->fields[7];
	    		
	    		$th=($hf - $hi)*60 + ($mf - $mi);

				if ($req != 0){
					$ssql = " select count(*) as cant";
					$ssql.= " from wfm_empleado_disponibilidad  t ";
	                $ssql.= " inner join ca_turnos tu on tu.turno_hora_inicio = " . $hi . " and ";
					$ssql.= " tu.turno_minuto_inicio = " . $mi .  " and tu.turno_hora_fin = " . $hf . " and ";
					$ssql.= " tu.turno_minuto_fin = " . $mf . " and ";
					$ssql.= " t.modalidad = tu.turno_modalidad ";
					$ssql.= " where  t.hora_inicio <= " . $hi . " and t.hora_fin >= " . $hf . " and ";
					$ssql.= " t.minuto_inicio <= " . $mi . " and t.minuto_fin >= " . $mf . " and ";
					$ssql.= " t.semana= " . $semana . " and t.anio= " . $anio . " and ";
					$ssql.= " t.fecha= cast('" . $fecha ."' as datetime) and t.estado = 1 and ";
					$ssql.= " t.empleado_codigo not in ( select empleado_codigo from wfm_programacion_empleado";						$ssql.= " where fecha= cast( '" . $fecha . "' as datetime) )";
					$ssql.= " and ((select isnull(sum(horas),0) from wfm_programacion_empleado " ;
					$ssql.= " where empleado_codigo=t.empleado_codigo and semana= " . $semana . " and ";
					$ssql.= " anio= " . $anio . " ) < (select sum(total_horas) from wfm_empleado_disponibilidad ";						$ssql.= " where empleado_codigo=t.empleado_codigo and semana= " . $semana . " and ";
					$ssql.= " anio= " . $anio . " )) ";
					$ssql.= " and " . $rs->fields[0] . " in (select codigo_campana from disponibilidad_servicio where empleado_codigo=t.empleado_codigo )";
					
					//echo $ssql;
					
					//$result3 = mssql_query($ssql);
					//$rs3=mssql_fetch_row($result3);
					$rs3 = $cn->Execute($ssql);
					$cant=$rs3->fields[0];
										
					$ssql = " insert into wfm_programacion_empleado ";					
					$ssql.= " select top " . $req . " t.empleado_codigo ,  t.fecha , tu.turno_codigo ,";
					$ssql.= " t.semana , t.anio , " . $th . ", " . $this->empleado_codigo_registro . ", getdate()" ;
	                $ssql.= " from wfm_empleado_disponibilidad  t ";
	                $ssql.= " inner join ca_turnos tu on tu.turno_hora_inicio = " . $hi . " and ";
					$ssql.= " tu.turno_minuto_inicio = " . $mi .  " and tu.turno_hora_fin = " . $hf . " and ";
					$ssql.= " tu.turno_minuto_fin = " . $mf . " and ";
					$ssql.= " t.modalidad = tu.turno_modalidad ";
					$ssql.= " where  t.hora_inicio <= " . $hi . " and t.hora_fin >= " . $hf . " and ";
					$ssql.= " t.minuto_inicio <= " . $mi . " and t.minuto_fin >= " . $mf . " and ";
					$ssql.= " t.semana= " . $semana . " and t.anio= " . $anio . " and ";
					$ssql.= " t.fecha= cast('" . $fecha ."' as datetime) and t.estado = 1 and ";
					$ssql.= " t.empleado_codigo not in ( select empleado_codigo from wfm_programacion_empleado";						$ssql.= " where fecha= cast( '" . $fecha . "' as datetime) )";
					$ssql.= " and ((select isnull(sum(horas),0) from wfm_programacion_empleado " ;
					$ssql.= " where empleado_codigo=t.empleado_codigo and semana= " . $semana . " and ";
					$ssql.= " anio= " . $anio . " ) < (select sum(total_horas) from wfm_empleado_disponibilidad ";						$ssql.= " where empleado_codigo=t.empleado_codigo and semana= " . $semana . " and ";
					$ssql.= " anio= " . $anio . " )) ";
					$ssql.= " and " . $rs->fields[0] . " in (select codigo_campana from disponibilidad_servicio where empleado_codigo=t.empleado_codigo )";
					$ssql.= " order by t.empleado_codigo ";
				
					//echo $ssql;
					
					//$result4 = mssql_query($ssql);
                    $rs4 = $cn->Execute($ssql);
					
					if ($cant<=$req){
						echo " ";
					}

				}//end if
                $rs2->MoveNext(); 
   			}//end while 2
            
            $rs->MoveNext(); 
		}//end while 1
		
				$ssql = " insert into wfm_combinacion_empleado ";
				$ssql .= " select v.empleado_codigo , t.tc_codigo , ";
				$ssql .= " convert(datetime ,'" . $this->te_fecha_inicio . "' , 103) as fecha_inicio ," ;
				$ssql .= " convert(datetime ,'" . $this->te_fecha_fin . "' , 103) as fecha_fin , ";
				$ssql .= $this->empleado_codigo_registro . " , getdate() ";  
				$ssql .= " from vWFM_Programacion v ";
				$ssql .= " inner join ca_turnos_combinacion t on v.d1 = t.turno_dia1 and v.d2 = t.turno_dia2 ";
				$ssql .= " and v.d3 = t.turno_dia3 and v.d4 = t.turno_dia4  and v.d5 = t.turno_dia5 ";
				$ssql .= " and v.d6 = t.turno_dia6 and v.d7 = t.turno_dia7 ";  
				$ssql .= " where semana=" . $this->te_semana ." and anio=" . $this->te_anio . " and t.tc_activo=1 ";
				
                //$result5 = mssql_query($ssql);
                $rs5 = $cn->Execute($ssql);
			
}

	return $rpta;
}

function Query_Existe_Asistencia(){
	$rpta="OK";
	$cn = $this->getMyconexionADO();
	if($cn){
		$ssql =" select empleado_codigo from ca_asistencias where empleado_codigo=".$this->empleado_codigo;
		$ssql.=" and asistencia_fecha=convert(datetime,convert(varchar(10),getdate(),103),103) ";
		$ssql.=" and ca_estado_codigo=1 ";
		$rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function Update_Asistencia_Programada($empleado_id){ 
	$rpta="OK";
	//$tmpo="NO";
	$cn = $this->getMyConexionADO();
	if($rpta=="OK"){
		$ssql ="exec spCA_Actualizar_Asistencia_Programada " . $empleado_id;
		//$ssql.="," . $this->te_semana . "," . $this->te_anio;
		$rs = $cn->Execute($ssql);

	}
	
	return $rpta;
}

function EmpleadoDNI($empleado_dni){
	$rpta="OK";
	$cn = $this->getMyconexionADO();
	if($cn){
		$ssql = "Select Empleado_Codigo from empleados where empleado_dni='" . $empleado_dni."'";
		$rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			return $rs->fields[0];
		}else{
			return "";
		}
	}
}

/*function actualizar_flag($flag){
	$rpta="Ok";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$cmd = new COM("ADODB.Command") or die("No se puede crear ADODB.Command");
		$cmd->ActiveConnection = $this->cnnado;	
		$ssql = "UPDATE ca_turno_empleado_Cambios ";
		$ssql .= " SET flag = '" . $flag . "'" ;
		$ssql .= " Where empleado_codigo=? and te_semana=? and te_anio=? ";
	
	    $cmd->CommandText = $ssql;
		$cmd->Parameters[0]->value = $this->empleado_codigo;
		$cmd->Parameters[1]->value = $this->te_semana;
		$cmd->Parameters[2]->value = $this->te_anio;
		$cmd->Execute();
	}
	
}*/

function valida_hora(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "select case when getdate() < convert(datetime, convert(varchar(10) , getdate(), 103) + ' 23:39:00', 103) then 1 else 0 end ";
	    $rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			$rpta = $rs->fields[0];
		}else{
		   $rpta='Error en validar hora: ';
		}
	} 
	return $rpta;
}

function historial_empleado($te_anio,$te_semana,$empleado_id ){
    $cn = $this->getMyConexionADO();
    /*
    $ssql =" Select tc.tc_codigo_sap as root,tc.tc_codigo_sap as Codigo_SAP,convert(varchar(10),a.te_fecha_inicio ";
	$ssql.=" ,103) as Inicio,convert(varchar(10),a.te_fecha_fin,103) as Fin,isnull(t1.Turno_Descripcion, ";
    $ssql.=" 'Descanso') as Lunes,isnull(t2.Turno_Descripcion,'Descanso') as Martes,isnull(t3.Turno_Descripcion, ";
	$ssql.=" 'Descanso') as Miercoles,isnull(t4.Turno_Descripcion,'Descanso') as Jueves,isnull(t5. ";
    $ssql.=" Turno_Descripcion,'Descanso') as Viernes,isnull(t6.Turno_Descripcion,'Descanso') as Sabado, ";
    $ssql.=" isnull(t7.Turno_Descripcion,'Descanso') as Domingo,convert(varchar(10),a.fecha_modificacion,103) ";
    $ssql.=" +' '+convert(varchar(10),a.fecha_modificacion,108) as Fec_Mod,ee.Empleado_Nombres + ' ' + "; 
    $ssql.=" ee.Empleado_Apellido_Paterno + ' ' + ee.Empleado_Apellido_Materno as Usuario_Mod from empleados ";
    $ssql.=" e inner join ca_turno_empleado a on e.empleado_codigo = a.empleado_codigo left join ";
    $ssql.=" empleados ee on ee.empleado_codigo = a.empleado_codigo_modificacion inner join ";
    $ssql.=" ca_turnos_combinacion tc on tc.tc_codigo = a.tc_codigo left join CA_Turnos t1 on ";
    $ssql.=" t1.Turno_Codigo = a.turno_dia1 left join CA_Turnos t2 on t2.Turno_Codigo = a.turno_dia2 left join ";
	$ssql.=" CA_Turnos t3 on t3.Turno_Codigo = a.turno_dia3 left join CA_Turnos t4 on t4.Turno_Codigo = ";
    $ssql.=" a.turno_dia4 left join CA_Turnos t5 on t5.Turno_Codigo = a.turno_dia5 left join CA_Turnos t6 on "; 
    $ssql.=" t6.Turno_Codigo = a.turno_dia6 left join CA_Turnos t7 on t7.Turno_Codigo = a.turno_dia7 ";
    $ssql.=" where a.te_anio=".$te_anio." and a.te_semana=".$te_semana." and a.empleado_codigo=".$empleado_id; 
    $ssql.=" union ";
    $ssql.=" Select tc.tc_codigo_sap as root,tc.tc_codigo_sap as Codigo_SAP,convert(varchar(10),a.te_fecha_inicio ";
	$ssql.=" ,103) as Inicio,convert(varchar(10),a.te_fecha_fin,103) as Fin,isnull(t1.Turno_Descripcion, ";
    $ssql.=" 'Descanso') as Lunes,isnull(t2.Turno_Descripcion,'Descanso') as Martes,isnull(t3.Turno_Descripcion, ";
	$ssql.=" 'Descanso') as Miercoles,isnull(t4.Turno_Descripcion,'Descanso') as Jueves,isnull(t5. ";
    $ssql.=" Turno_Descripcion,'Descanso') as Viernes,isnull(t6.Turno_Descripcion,'Descanso') as Sabado, ";
    $ssql.=" isnull(t7.Turno_Descripcion,'Descanso') as Domingo,convert(varchar(10),a.fecha_modificacion,103) ";
    $ssql.=" +' '+convert(varchar(10),a.fecha_modificacion,108) as Fec_Mod,ee.Empleado_Nombres + ' ' + "; 
    $ssql.=" ee.Empleado_Apellido_Paterno + ' ' + ee.Empleado_Apellido_Materno as Usuario_Mod from empleados ";
    $ssql.=" e inner join ca_turno_empleado_auditor a on e.empleado_codigo = a.empleado_codigo left join ";
    $ssql.=" empleados ee on ee.empleado_codigo = a.empleado_codigo_modificacion inner join ";
    $ssql.=" ca_turnos_combinacion tc on tc.tc_codigo = a.tc_codigo left join CA_Turnos t1 on ";
    $ssql.=" t1.Turno_Codigo = a.turno_dia1 left join CA_Turnos t2 on t2.Turno_Codigo = a.turno_dia2 left join ";
	$ssql.=" CA_Turnos t3 on t3.Turno_Codigo = a.turno_dia3 left join CA_Turnos t4 on t4.Turno_Codigo = ";
    $ssql.=" a.turno_dia4 left join CA_Turnos t5 on t5.Turno_Codigo = a.turno_dia5 left join CA_Turnos t6 on "; 
    $ssql.=" t6.Turno_Codigo = a.turno_dia6 left join CA_Turnos t7 on t7.Turno_Codigo = a.turno_dia7 ";
    $ssql.=" where a.te_anio=".$te_anio." and a.te_semana=".$te_semana." and a.empleado_codigo=".$empleado_id; 
    $ssql.=" Order by Fec_Mod DESC ";
	*/
    $ssql="
      Select tc.tc_codigo_sap as root,tc.tc_codigo_sap as Codigo_SAP,
      convert(varchar(10),a.te_fecha_inicio,103) as Inicio,
      convert(varchar(10),a.te_fecha_fin,103) as Fin,
      isnull(t1.Turno_Descripcion,'Descanso') as Lunes,
      isnull(t2.Turno_Descripcion,'Descanso') as Martes,
      isnull(t3.Turno_Descripcion,'Descanso') as Miercoles,
      isnull(t4.Turno_Descripcion,'Descanso') as Jueves,
      isnull(t5.Turno_Descripcion,'Descanso') as Viernes,
      isnull(t6.Turno_Descripcion,'Descanso') as Sabado,
      isnull(t7.Turno_Descripcion,'Descanso') as Domingo,
      case when a.empleado_codigo_modificacion<>0 
      then 
      convert(varchar(10),a.fecha_modificacion,103)+' '+convert(varchar(8),a.fecha_modificacion,108) 
      else
      convert(varchar(10),a.fecha_registro,103)+' '+convert(varchar(8),a.fecha_registro,108)
      end as Fec_Mod,
      case when a.empleado_codigo_modificacion<>0 
      then 
      ee.Empleado_Nombres+' '+ee.Empleado_Apellido_Paterno+' '+ee.Empleado_Apellido_Materno 
      else
      er.Empleado_Nombres+' '+er.Empleado_Apellido_Paterno+' '+er.Empleado_Apellido_Materno 
      end as Usuario_Mod, 
      case when a.empleado_codigo_modificacion<>0 
      then a.fecha_modificacion else a.fecha_registro end as fecha_orden
      from empleados e 
      inner join ca_turno_empleado a on e.empleado_codigo=a.empleado_codigo 
      left join empleados ee on ee.empleado_codigo=a.empleado_codigo_modificacion 
      LEFT join empleados er on er.empleado_codigo=a.empleado_codigo_registro
      inner join ca_turnos_combinacion tc on tc.tc_codigo=a.tc_codigo 
      left join CA_Turnos t1 on t1.Turno_Codigo=a.turno_dia1 
      left join CA_Turnos t2 on t2.Turno_Codigo=a.turno_dia2 
      left join CA_Turnos t3 on t3.Turno_Codigo=a.turno_dia3 
      left join CA_Turnos t4 on t4.Turno_Codigo=a.turno_dia4 
      left join CA_Turnos t5 on t5.Turno_Codigo=a.turno_dia5 
      left join CA_Turnos t6 on t6.Turno_Codigo=a.turno_dia6 
      left join CA_Turnos t7 on t7.Turno_Codigo=a.turno_dia7
      where a.te_anio=".$te_anio." and a.te_semana=".$te_semana." and a.empleado_codigo=".$empleado_id."
      union
      Select tc.tc_codigo_sap as root,tc.tc_codigo_sap as Codigo_SAP,
      convert(varchar(10),a.te_fecha_inicio,103) as Inicio,
      convert(varchar(10),a.te_fecha_fin,103) as Fin,
      isnull(t1.Turno_Descripcion,'Descanso') as Lunes,
      isnull(t2.Turno_Descripcion,'Descanso') as Martes,
      isnull(t3.Turno_Descripcion,'Descanso') as Miercoles,
      isnull(t4.Turno_Descripcion,'Descanso') as Jueves,
      isnull(t5.Turno_Descripcion,'Descanso') as Viernes,
      isnull(t6.Turno_Descripcion,'Descanso') as Sabado,
      isnull(t7.Turno_Descripcion,'Descanso') as Domingo,
      case when a.empleado_codigo_modificacion<>0 
      then 
      convert(varchar(10),a.fecha_modificacion,103)+' '+convert(varchar(8),a.fecha_modificacion,108) 
      else
      convert(varchar(10),a.fecha_registro,103)+' '+convert(varchar(8),a.fecha_registro,108)
      end as Fec_Mod,
      case when a.empleado_codigo_modificacion<>0 
      then 
      ee.Empleado_Nombres+' '+ee.Empleado_Apellido_Paterno+' '+ee.Empleado_Apellido_Materno 
      else
      er.Empleado_Nombres+' '+er.Empleado_Apellido_Paterno+' '+er.Empleado_Apellido_Materno 
      end as Usuario_Mod, 
      case when a.empleado_codigo_modificacion<>0 
      then a.fecha_modificacion else a.fecha_registro end as fecha_orden
      from empleados e 
      inner join ca_turno_empleado_auditor a on e.empleado_codigo=a.empleado_codigo 
      left join empleados ee on ee.empleado_codigo=a.empleado_codigo_modificacion 
      LEFT join empleados er on er.empleado_codigo=a.empleado_codigo_registro
      inner join ca_turnos_combinacion tc on tc.tc_codigo=a.tc_codigo 
      left join CA_Turnos t1 on t1.Turno_Codigo=a.turno_dia1 
      left join CA_Turnos t2 on t2.Turno_Codigo=a.turno_dia2 
      left join CA_Turnos t3 on t3.Turno_Codigo=a.turno_dia3 
      left join CA_Turnos t4 on t4.Turno_Codigo=a.turno_dia4 
      left join CA_Turnos t5 on t5.Turno_Codigo=a.turno_dia5 
      left join CA_Turnos t6 on t6.Turno_Codigo=a.turno_dia6 
      left join CA_Turnos t7 on t7.Turno_Codigo=a.turno_dia7
      where a.te_anio=".$te_anio." and a.te_semana=".$te_semana." and a.empleado_codigo=".$empleado_id."
      Order by fecha_orden DESC
    ";    
	
    //echo $ssql;
    $rs = $cn->Execute($ssql);
    $data = array();
    while(!$rs->EOF){
        $data[] = $rs->fields;
        $rs->MoveNext();
    }
    return $data;
}


// function EmpleadosACargo($empleado_codigo){

// 	$rpta="OK";
// 	$cn = $this->getMyconexionADO();
// 	if($cn){
// 		$ssql = "select Empleado_Dni  from ca_asignacion_empleados ca inner join vDatosTotal vt on vt.Empleado_Codigo = ca.Empleado_Codigo where Asignacion_Activo = 1 and Responsable_Codigo = " . $empleado_codigo;
// 		$lst_result = $cn->Execute($ssql);
// 		$arresult = array();
// 		if ($lst_result){
// 			foreach ($lst_result as $key => $value) {
// 				$arresult[] = $value[0];
// 			}
// 			return $arresult;
// 		}else{
// 			return false;			
// 		}

// 	}


// }

  function esAdministrador($empl_codigo){
    $rpta = "";
    $cn = $this->getMyConexionADO();
    $ssql = "select rol_codigo FROM CA_Empleado_Rol where Rol_Codigo=3 and Empleado_codigo=".$empl_codigo;
    $rs = $cn->Execute($ssql);
    if ($rs->fields[0] == 3){
      return true;
    }else{
       return false;
    }
  }

	function ExisteProgramacion(){
		$cn = $this->getMyConexionADO();
		$ssql = " select empleado_codigo from CA_turno_empleado ";
		$ssql.= " where convert(datetime,convert(varchar(10),GETDATE(),103),103) between ";
		$ssql.= " te_fecha_inicio and te_fecha_fin and Empleado_codigo=".$this->empleado_codigo;
		$rs = $cn->Execute($ssql);
		if (!$rs->EOF){
			return true;
		}else{
			return false;
		}
	}


}
?>
