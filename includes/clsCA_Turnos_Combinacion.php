<?php
//require_once(PathIncludes() . "mantenimiento.php");
class ca_turnos_combinacion extends mantenimiento{
var $tc_codigo="";
var $tc_codigo_sap="";
var $turno_Dia1=0;
var $turno_Dia2=0;
var $turno_Dia3=0;
var $turno_Dia4=0;
var $turno_Dia5=0;
var $turno_Dia6=0;
var $turno_Dia7=0;
var $tc_activo="";
var $empleado_codigo="";
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
var $horas_refrigerio="";
var $minutos_refrigerio="";
var $sturno_Dia1="";
var $sturno_Dia2="";
var $sturno_Dia3="";
var $sturno_Dia4="";
var $sturno_Dia5="";
var $sturno_Dia6="";
var $sturno_Dia7="";
var $ttotal_horas="";
var $ttotal_minutos="";

function Addnew(){
	$rpta="";
	$cn = $this->getMyConexionADO();
	//$cn->debug=true;
	if($cn){
		$ssql = "select isnull(max(tc_codigo), 0) + 1 as maximo from ca_turnos_combinacion ";
		$rs= $cn->Execute($ssql);
		$this->tc_codigo = $rs->fields[0];
        
        $params = array(
                        $this->tc_codigo,
                        $this->tc_codigo_sap,
                        $this->turno_Dia1,
                        $this->turno_Dia2,
                        $this->turno_Dia3,
                        $this->turno_Dia4,
                        $this->turno_Dia5,
                        $this->turno_Dia6,
                        $this->turno_Dia7,
                        $this->tc_activo,
                        $this->empleado_codigo
                    );
		$ssql = "INSERT INTO ca_turnos_combinacion(
		                  tc_codigo, 
                          tc_codigo_sap, 
                          turno_dia1, 
                          turno_dia2, 
                          turno_dia3, 
                          turno_dia4, 
                          turno_dia5, 
                          turno_dia6, 
                          turno_dia7, 
                          tc_activo, 
                          fecha_registro, 
                          empleado_codigo_registro) 
                VALUES (?,?,?,?,?,?,?,?,?,?,getdate(),?)";
		$rs = $cn->Execute($ssql, $params);
        if($rs == false)
            $rpta = "Error al insertar";    
	}
	return $rpta;
}

function Update(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
        //$cn->debug=true;
	if($cn){
        $params = array(
                        $this->tc_codigo_sap,
                        $this->turno_Dia1,
                        $this->turno_Dia2,
                        $this->turno_Dia3,
                        $this->turno_Dia4,
                        $this->turno_Dia5,
                        $this->turno_Dia6 ,
                        $this->turno_Dia7,
                        $this->tc_activo,
                        $this->empleado_codigo,
                        $this->tc_codigo                                                
                        );
		$ssql = "UPDATE ca_turnos_combinacion 
        		 SET tc_codigo_sap = ?,
        		     turno_dia1 = ?,
        		     turno_dia2 = ?,
        		     turno_dia3 = ?,
        		     turno_dia4 = ?,
        		     turno_dia5 = ?,
        		     turno_dia6 = ?,
        		     turno_dia7 = ?,
        		     tc_activo = ?,
        		     Fecha_Modificacion = getdate(),
        		     Empleado_Codigo_Modificacion = ?
        		  Where tc_codigo =?"; 
		$rs = $cn->Execute($ssql, $params);
	}
	return $rpta;
}

function Query(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($cn){
		$ssql = "SELECT tc_codigo, tc_codigo_sap, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, abs(tc_activo) ";
		$ssql .= " FROM ca_turnos_combinacion ";
		$ssql .= " WHERE tc_codigo = " . $this->tc_codigo;
	    $rs = $cn->Execute($ssql);
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
				
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
	  }
	 } 
	return $rpta;
    /*	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = "SELECT tc_codigo, tc_codigo_sap, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, abs(tc_activo) ";
		$ssql .= " FROM ca_turnos_combinacion ";
		$ssql .= " WHERE tc_codigo = " . $this->tc_codigo;
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$this->tc_codigo_sap = $rs->fields[1]->value;
			$this->turno_Dia1 = $rs->fields[2]->value;
			$this->turno_Dia2 = $rs->fields[3]->value;
			$this->turno_Dia3 = $rs->fields[4]->value;
			$this->turno_Dia4 = $rs->fields[5]->value;
			$this->turno_Dia5 = $rs->fields[6]->value;
			$this->turno_Dia6 = $rs->fields[7]->value;
			$this->turno_Dia7 = $rs->fields[8]->value;
			$this->tc_activo= $rs->fields[9]->value;
				
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
	  }
	 } 
	return $rpta;*/
}

function Query_Detalle(){
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	if($rpta=="OK"){
		$ssql  = " SELECT tc_codigo, tc_codigo_sap, "; 
		$ssql .= " turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, "; 
		$ssql .= " tc_activo,"; 
		$ssql .= " isnull(t1.turno_descripcion,'') as tturno_dia1, "; 
		$ssql .= " isnull(t2.turno_descripcion,'') as tturno_dia2, "; 
		$ssql .= " isnull(t3.turno_descripcion,'') as tturno_dia3, "; 
		$ssql .= " isnull(t4.turno_descripcion,'') as tturno_dia4, "; 
		$ssql .= " isnull(t5.turno_descripcion,'') as tturno_dia5, "; 
		$ssql .= " isnull(t6.turno_descripcion,'') as tturno_dia6, "; 
		$ssql .= " isnull(t7.turno_descripcion,'') as tturno_dia7, "; 
		$ssql .= " isnull(t1.turno_refrigerio,'') as lturno_dia1, "; 
		$ssql .= " isnull(t2.turno_refrigerio,'') as lturno_dia2, "; 
		$ssql .= " isnull(t3.turno_refrigerio,'') as lturno_dia3, "; 
		$ssql .= " isnull(t4.turno_refrigerio,'') as lturno_dia4, "; 
		$ssql .= " isnull(t5.turno_refrigerio,'') as lturno_dia5, "; 
		$ssql .= " isnull(t6.turno_refrigerio,'') as lturno_dia6, "; 
		$ssql .= " isnull(t7.turno_refrigerio,'') as lturno_dia7, "; 
		$ssql .= " isnull(t1.turno_descanzo,'') as dturno_dia1, "; 
		$ssql .= " isnull(t2.turno_descanzo,'') as dturno_dia2, "; 
		$ssql .= " isnull(t3.turno_descanzo,'') as dturno_dia3, "; 
		$ssql .= " isnull(t4.turno_descanzo,'') as dturno_dia4, "; 
		$ssql .= " isnull(t5.turno_descanzo,'') as dturno_dia5, "; 
		$ssql .= " isnull(t6.turno_descanzo,'') as dturno_dia6, "; 
		$ssql .= " isnull(t7.turno_descanzo,'') as dturno_dia7, ";
		$ssql .= " isnull(t1.turno_descanso2,'') as eturno_dia1, "; 
		$ssql .= " isnull(t2.turno_descanso2,'') as eturno_dia2, "; 
		$ssql .= " isnull(t3.turno_descanso2,'') as eturno_dia3, "; 
		$ssql .= " isnull(t4.turno_descanso2,'') as eturno_dia4, "; 
		$ssql .= " isnull(t5.turno_descanso2,'') as eturno_dia5, "; 
		$ssql .= " isnull(t6.turno_descanso2,'') as eturno_dia6, "; 
		$ssql .= " isnull(t7.turno_descanso2,'') as eturno_dia7, ";
		$ssql .= " isnull(convert(varchar(5),t1.Turno_Thoras,108),'') as nturno_dia1, ";
		$ssql .= " isnull(convert(varchar(5),t2.Turno_Thoras,108),'') as nturno_dia2, ";
		$ssql .= " isnull(convert(varchar(5),t3.Turno_Thoras,108),'') as nturno_dia3, ";
		$ssql .= " isnull(convert(varchar(5),t4.Turno_Thoras,108),'') as nturno_dia4, ";
		$ssql .= " isnull(convert(varchar(5),t5.Turno_Thoras,108),'') as nturno_dia5, ";
		$ssql .= " isnull(convert(varchar(5),t6.Turno_Thoras,108),'') as nturno_dia6, ";
		$ssql .= " isnull(convert(varchar(5),t7.Turno_Thoras,108),'') as nturno_dia7, ";
		$ssql .= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t7.Turno_Horas,0.0)*60)+ "; 
		$ssql .= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Minutos,0.0)) -  ";
		$ssql .= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Refrigerio,0.0)) )/60 as total_horas, ";
		$ssql .= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t7.Turno_Horas,0.0)*60)+ "; 
		$ssql .= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Minutos,0.0))  -  ";
		$ssql .= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Refrigerio,0.0)) )%60 as total_minutos, ";
		$ssql .= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Refrigerio,0.0))/60 as horas_refrigerio, ";
		$ssql .= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Refrigerio,0.0))%60 as minutos_refrigerio, ";
		$ssql .= " isnull(t1.Turno_id,'') as sturno_dia1, "; 
		$ssql .= " isnull(t2.Turno_id,'') as sturno_dia2, "; 
		$ssql .= " isnull(t3.Turno_id,'') as sturno_dia3, "; 
		$ssql .= " isnull(t4.Turno_id,'') as sturno_dia4, "; 
		$ssql .= " isnull(t5.Turno_id,'') as sturno_dia5, "; 
		$ssql .= " isnull(t6.Turno_id,'') as sturno_dia6, "; 
		$ssql .= " isnull(t7.Turno_id,'') as sturno_dia7, ";
		$ssql .= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t7.Turno_Horas,0.0)*60)+  ";
		$ssql .= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Minutos,0.0)) )/60 as ttotal_horas, ";
		$ssql .= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql .= " (isnull(t7.Turno_Horas,0.0)*60)+  ";
		$ssql .= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql .= " isnull(t7.Turno_Minutos,0.0)) )%60 as ttotal_minutos ";
		$ssql .= " FROM CA_Turnos_Combinacion tc "; 
		$ssql .= " left join vCA_TurnosTH t1 on tc.turno_dia1=t1.turno_codigo "; 
		$ssql .= " left join vCA_TurnosTH t2 on tc.turno_dia2=t2.turno_codigo "; 
		$ssql .= " left join vCA_TurnosTH t3 on tc.turno_dia3=t3.turno_codigo "; 
		$ssql .= " left join vCA_TurnosTH t4 on tc.turno_dia4=t4.turno_codigo "; 
		$ssql .= " left join vCA_TurnosTH t5 on tc.turno_dia5=t5.turno_codigo "; 
		$ssql .= " left join vCA_TurnosTH t6 on tc.turno_dia6=t6.turno_codigo "; 
		$ssql .= " left join vCA_TurnosTH t7 on tc.turno_dia7=t7.turno_codigo "; 
		$ssql .= " WHERE tc_codigo = " . $this->tc_codigo;
	    $rs = $cn->Execute($ssql);
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
			$this->eturno_Dia1 = $rs->fields[31];
			$this->eturno_Dia2 = $rs->fields[32];
			$this->eturno_Dia3 = $rs->fields[33];
			$this->eturno_Dia4 = $rs->fields[34];
			$this->eturno_Dia5 = $rs->fields[35];
			$this->eturno_Dia6 = $rs->fields[36];
			$this->eturno_Dia7 = $rs->fields[37];
			$this->nturno_Dia1 = $rs->fields[38];
			$this->nturno_Dia2 = $rs->fields[39];
			$this->nturno_Dia3 = $rs->fields[40];
			$this->nturno_Dia4 = $rs->fields[41];
			$this->nturno_Dia5 = $rs->fields[42];
			$this->nturno_Dia6 = $rs->fields[43];
			$this->nturno_Dia7 = $rs->fields[44];
			$this->total_horas = $rs->fields[45];
			$this->total_minutos = $rs->fields[46];
			$this->horas_refrigerio = $rs->fields[47];
			$this->minutos_refrigerio = $rs->fields[48];
			$this->sturno_Dia1 = $rs->fields[49];
			$this->sturno_Dia2 = $rs->fields[50];
			$this->sturno_Dia3 = $rs->fields[51];
			$this->sturno_Dia4 = $rs->fields[52];
			$this->sturno_Dia5 = $rs->fields[53];
			$this->sturno_Dia6 = $rs->fields[54];
			$this->sturno_Dia7 = $rs->fields[55];
			$this->ttotal_horas = $rs->fields[56];
			$this->ttotal_minutos = $rs->fields[57];
	  }else{
		   $rpta='No Existe Registro de turno: ' . $this->tc_codigo;
	  }
	 } 
	return $rpta;
}

function Query_Existe(){
	/*$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = "SELECT * FROM ca_turnos_combinacion ";
		$ssql.= " WHERE tc_codigo_sap = '" . $this->tc_codigo_sap . "'";
		if ($this->tc_codigo==''){
			$ssql.= " AND tc_codigo <> ''";
		}else{
			$ssql.= " AND tc_codigo <> " . $this->tc_codigo;
		}
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			$rpta='NOT';
		}else{
		$ssql = "SELECT count(*) as n ";
		$ssql.= " FROM ca_turnos_combinacion ";
		$ssql.= " WHERE tc_codigo_sap = '" . $this->tc_codigo_sap . "'";
		$ssql.= " AND turno_dia1 = " . $this->turno_dia1;
		$ssql.= " AND turno_dia2 = " . $this->turno_dia2;
		$ssql.= " AND turno_dia3 = " . $this->turno_dia3;
		$ssql.= " AND turno_dia4 = " . $this->turno_dia4;
		$ssql.= " AND turno_dia5 = " . $this->turno_dia5;
		$ssql.= " AND turno_dia6 = " . $this->turno_dia6;
		$ssql.= " AND turno_dia7 = " . $this->turno_dia7;
		if ($this->tc_codigo==''){
			$ssql.= " AND tc_codigo <> ''";
		}else{
			$ssql.= " AND tc_codigo <> " . $this->tc_codigo;
		}
		//echo $ssql;
	    $rs = $this->cnnado->Execute($ssql);
		if (!$rs->EOF){
			if ($rs->fields[0]->value>0){
   				$rpta='NOT';
			}else{
   				$rpta='OK';
			}	
		}
		}
	}
	return $rpta;*/
    
    $rpta="OK";
    $cn=$this->getMyConexionADO();

    $ssql = "SELECT * FROM ca_turnos_combinacion ";
    $ssql.= " WHERE tc_codigo_sap = '" . $this->tc_codigo_sap . "'";
    
    if ($this->tc_codigo==''){
        $ssql.= " AND tc_codigo <> ''";
    }else{
        $ssql.= " AND tc_codigo <> " . $this->tc_codigo;
    }
        $rs = $cn->Execute($ssql);
        if (!$rs->EOF){
            $rpta='NOT';
        }else{
            $ssql = "SELECT count(*) as n ";
            $ssql.= " FROM ca_turnos_combinacion ";
            $ssql.= " WHERE tc_codigo_sap = '" . $this->tc_codigo_sap . "'";
            $ssql.= " AND turno_dia1 = " . $this->turno_dia1;
            $ssql.= " AND turno_dia2 = " . $this->turno_dia2;
            $ssql.= " AND turno_dia3 = " . $this->turno_dia3;
            $ssql.= " AND turno_dia4 = " . $this->turno_dia4;
            $ssql.= " AND turno_dia5 = " . $this->turno_dia5;
            $ssql.= " AND turno_dia6 = " . $this->turno_dia6;
            $ssql.= " AND turno_dia7 = " . $this->turno_dia7;
            if ($this->tc_codigo==''){
                $ssql.= " AND tc_codigo <> ''";
            }else{
                $ssql.= " AND tc_codigo <> " . $this->tc_codigo;
            }
            
            $rs = $cn->Execute($ssql);
            if (!$rs->EOF){
                if ($rs->fields[0]>0){
                    $rpta='NOT';
                }else{
                    $rpta='OK';
                }	
            }
        }
	
	return $rpta;
    
    
}

function Reporte_Combinacion(){
        /*
	$cadena="";
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = " SELECT tc_codigo, tc_codigo_sap,  "; 
		$ssql.= " isnull(t1.turno_descripcion,'Descanso') as tturno_dia1, ";
		$ssql.= " isnull(t2.turno_descripcion,'Descanso') as tturno_dia2, ";
		$ssql.= " isnull(t3.turno_descripcion,'Descanso') as tturno_dia3, ";
		$ssql.= " isnull(t4.turno_descripcion,'Descanso') as tturno_dia4, ";
		$ssql.= " isnull(t5.turno_descripcion,'Descanso') as tturno_dia5, ";
		$ssql.= " isnull(t6.turno_descripcion,'Descanso') as tturno_dia6, ";
		$ssql.= " isnull(t7.turno_descripcion,'Descanso') as tturno_dia7, ";
		$ssql.= " CASE WHEN Tc_Activo = 1 then 'Si' else 'No' end as tc_activo, ";
		$ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
		$ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Minutos,0.0)) - ";
		$ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Refrigerio,0.0)) )/60 as total_horas, ";
		$ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
		$ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Minutos,0.0)) - ";
		$ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Refrigerio,0.0)) )%60 as total_minutos, ";
		$ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Refrigerio,0.0))/60 as horas_refrigerio, ";
		$ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Refrigerio,0.0))%60 as minutos_refrigerio, ";
		$ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
		$ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Minutos,0.0)) )/60 as ttotal_horas, ";
		$ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
		$ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
		$ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
		$ssql.= " isnull(t7.Turno_Minutos,0.0)) )%60 as ttotal_minutos ";
		$ssql.= " FROM CA_Turnos_Combinacion tc ";
		$ssql.= " left join vCA_TurnosTH t1 on tc.turno_dia1=t1.turno_codigo ";
		$ssql.= " left join vCA_TurnosTH t2 on tc.turno_dia2=t2.turno_codigo ";
		$ssql.= " left join vCA_TurnosTH t3 on tc.turno_dia3=t3.turno_codigo ";
		$ssql.= " left join vCA_TurnosTH t4 on tc.turno_dia4=t4.turno_codigo ";
		$ssql.= " left join vCA_TurnosTH t5 on tc.turno_dia5=t5.turno_codigo ";
		$ssql.= " left join vCA_TurnosTH t6 on tc.turno_dia6=t6.turno_codigo ";
		$ssql.= " left join vCA_TurnosTH t7 on tc.turno_dia7=t7.turno_codigo ";
		$ssql.= " order by 2,1 ";
		//echo $ssql;
		$rs = $this->cnnado->Execute($ssql);
		if(!$rs->EOF()) {
			$cadena = "<table class='FormTable' id='listado' width='200%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:200%'>
			<tr>
			    	<td colspan='14' align=center><b>Combinacion semanal de turnos</td>
			</tr>
			<tr>
			    	<td colspan='14' align=center><b>&nbsp;</td>
			</tr>
			<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD'><b>Nro.</td>
		    	<td class='ColumnTD'><b>Codigo</td>
		    	<td class='ColumnTD'><b>Cod_comb</td>
				<td class='ColumnTD'><b>Lunes</td>
				<td class='ColumnTD'><b>Martes</td>
				<td class='ColumnTD'><b>Miercoles</td>
				<td class='ColumnTD'><b>Jueves</td>
				<td class='ColumnTD'><b>Viernes</td>
				<td class='ColumnTD'><b>Sabado</td>
				<td class='ColumnTD'><b>Domingo</td>
				<td class='ColumnTD'><b>Total Hr</td>
				<td class='ColumnTD'><b>Refrigerio</td>
				<td class='ColumnTD'><b>Hr.Efectivas</td>
	    		<td class='ColumnTD'><b>Activo</td>
			</tr>";

			$i=0;
			while(!$rs->EOF()){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[4]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[5]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[6]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[7]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[8]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[14]->value . ":" . $rs->fields[15]->value."&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[12]->value . ":" . $rs->fields[13]->value."&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[10]->value . ":" . $rs->fields[11]->value."&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[9]->value . "&nbsp;</td>";
				$cadena .="</tr>";
				$rs->movenext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
        */
        
    
    
	$cadena="";
	$rpta="OK";
	$cn=$this->getMyConexionADO();
	
        $ssql = " SELECT tc_codigo, tc_codigo_sap,  "; 
        $ssql.= " isnull(t1.turno_descripcion,'Descanso') as tturno_dia1, ";
        $ssql.= " isnull(t2.turno_descripcion,'Descanso') as tturno_dia2, ";
        $ssql.= " isnull(t3.turno_descripcion,'Descanso') as tturno_dia3, ";
        $ssql.= " isnull(t4.turno_descripcion,'Descanso') as tturno_dia4, ";
        $ssql.= " isnull(t5.turno_descripcion,'Descanso') as tturno_dia5, ";
        $ssql.= " isnull(t6.turno_descripcion,'Descanso') as tturno_dia6, ";
        $ssql.= " isnull(t7.turno_descripcion,'Descanso') as tturno_dia7, ";
        $ssql.= " CASE WHEN Tc_Activo = 1 then 'Si' else 'No' end as tc_activo, ";
        $ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
        $ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Minutos,0.0)) - ";
        $ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Refrigerio,0.0)) )/60 as total_horas, ";
        $ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
        $ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Minutos,0.0)) - ";
        $ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Refrigerio,0.0)) )%60 as total_minutos, ";
        $ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Refrigerio,0.0))/60 as horas_refrigerio, ";
        $ssql.= " (isnull(t1.Turno_Refrigerio,0.0)+isnull(t2.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Refrigerio,0.0)+isnull(t4.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Refrigerio,0.0)+isnull(t6.Turno_Refrigerio,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Refrigerio,0.0))%60 as minutos_refrigerio, ";
        $ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
        $ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Minutos,0.0)) )/60 as ttotal_horas, ";
        $ssql.= " (convert(int,(isnull(t1.Turno_Horas,0.0)*60)+(isnull(t2.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t3.Turno_Horas,0.0)*60)+(isnull(t4.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t5.Turno_Horas,0.0)*60)+(isnull(t6.Turno_Horas,0.0)*60)+ ";
        $ssql.= " (isnull(t7.Turno_Horas,0.0)*60)+ ";
        $ssql.= " isnull(t1.Turno_Minutos,0.0)+isnull(t2.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t3.Turno_Minutos,0.0)+isnull(t4.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t5.Turno_Minutos,0.0)+isnull(t6.Turno_Minutos,0.0)+ ";
        $ssql.= " isnull(t7.Turno_Minutos,0.0)) )%60 as ttotal_minutos ";
        $ssql.= " FROM CA_Turnos_Combinacion tc ";
        $ssql.= " left join vCA_TurnosTH t1 on tc.turno_dia1=t1.turno_codigo ";
        $ssql.= " left join vCA_TurnosTH t2 on tc.turno_dia2=t2.turno_codigo ";
        $ssql.= " left join vCA_TurnosTH t3 on tc.turno_dia3=t3.turno_codigo ";
        $ssql.= " left join vCA_TurnosTH t4 on tc.turno_dia4=t4.turno_codigo ";
        $ssql.= " left join vCA_TurnosTH t5 on tc.turno_dia5=t5.turno_codigo ";
        $ssql.= " left join vCA_TurnosTH t6 on tc.turno_dia6=t6.turno_codigo ";
        $ssql.= " left join vCA_TurnosTH t7 on tc.turno_dia7=t7.turno_codigo ";
        $ssql.= " order by 2,1 ";
		
        $rs = $cn->Execute($ssql);
        if(!$rs->EOF){
            $cadena = "<table class='FormTable' id='listado' width='200%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:200%'>
            <tr>
                    <td colspan='14' align=center><b>Combinacion semanal de turnos</td>
            </tr>
            <tr>
                    <td colspan='14' align=center><b>&nbsp;</td>
            </tr>
            <tr align='center' style='background-color:#D8DAB4'>
            <td class='ColumnTD'><b>Nro.</td>
            <td class='ColumnTD'><b>Codigo</td>
            <td class='ColumnTD'><b>Cod_comb</td>
                    <td class='ColumnTD'><b>Lunes</td>
                    <td class='ColumnTD'><b>Martes</td>
                    <td class='ColumnTD'><b>Miercoles</td>
                    <td class='ColumnTD'><b>Jueves</td>
                    <td class='ColumnTD'><b>Viernes</td>
                    <td class='ColumnTD'><b>Sabado</td>
                    <td class='ColumnTD'><b>Domingo</td>
                    <td class='ColumnTD'><b>Total Hr</td>
                    <td class='ColumnTD'><b>Refrigerio</td>
                    <td class='ColumnTD'><b>Hr.Efectivas</td>
            <td class='ColumnTD'><b>Activo</td>
            </tr>";

            $i=0;
            while(!$rs->EOF){
                $i+=1;
                $cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
                $cadena.="<td align=center>".$i."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[0]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[1]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[2]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[3]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[4]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[5]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[6]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[7]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[8]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[14].":".$rs->fields[15]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[12].":".$rs->fields[13]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[10].":".$rs->fields[11]."&nbsp;</td>";
                $cadena.="<td align=left>".$rs->fields[9]."&nbsp;</td>";
                $cadena.="</tr>";
                $rs->MoveNext();
                
            }
                $cadena .= "</table>";
        }
        
        return $cadena;
	
}

function Reporte_Turnos_Detalle($tipo){
        /*
	$cadena="";
	$rpta="OK";
	$rpta=$this->conectarme_ado();
	if($rpta=="OK"){
		$ssql = " select turno_codigo,turno_id,turno_descripcion,convert(varchar(5),cast(cast(Turno_Hora_Inicio ";
		$ssql.= " as varchar)+':'+cast(Turno_Minuto_Inicio as varchar) as datetime),108) as hora_inicio, ";
		$ssql.= " convert(varchar(5),cast(cast(Turno_Hora_Fin as varchar)+':'+cast(Turno_Minuto_Fin ";
		$ssql.= " as varchar) as datetime),108) as hora_termino, ";
		$ssql.= " convert(varchar(5),turno_thoras,108) as total_horas,turno_refrigerio as duracion_colacion, ";
		$ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ isnull(Turno_Minutos,0.0)) - ";
		$ssql.= " (isnull(Turno_Refrigerio,0.0)) )/60 as total_horas, ";
		$ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ isnull(Turno_Minutos,0.0)) - ";
		$ssql.= " (isnull(Turno_Refrigerio,0.0)) )%60 as total_minutos, ";
		$ssql.= " (isnull(Turno_Refrigerio,0.0))/60 as horas_refrigerio, ";
		$ssql.= " (isnull(Turno_Refrigerio,0.0))%60 as minutos_refrigerio, ";
		$ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ ";
		$ssql.= " isnull(Turno_Minutos,0.0)))/60 as ttotal_horas, ";
		$ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ ";
		$ssql.= " isnull(Turno_Minutos,0.0)))%60 as ttotal_minutos, turno_descanzo,turno_descanso2 ";
		$ssql.= " from vca_turnosth ";
		$ssql.= " where tipo_area_codigo=".$tipo;
		$ssql.= " order by 2 ";
		//echo $ssql;
		$rs = $this->cnnado->Execute($ssql);
		if(!$rs->EOF()) {
			$cadena = "<table class='FormTable' id='listado' width='80%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:80%'>
			<tr>
			    	<td colspan='11' align=center><b>Catalogo de turnos</td>
			</tr>
			<tr>
			    	<td colspan='11' align=center><b>&nbsp;</td>
			</tr>
			<tr align='center' style='background-color:#D8DAB4'>
		    	<td class='ColumnTD' style='width:100px'><b>Nro.</td>
		    	<td class='ColumnTD'><b>Codigo</td>
		    	<td class='ColumnTD'><b>Turno_ID</td>
		    	<td class='ColumnTD'><b>Descripcion</td>
		    	<td class='ColumnTD'><b>Hora_Inicio</td>
		    	<td class='ColumnTD'><b>Hora_Termino</td>
		    	<td class='ColumnTD'><b>Descanso1</td>
		    	<td class='ColumnTD'><b>Descanso2</td>
				<td class='ColumnTD'><b>Total_Horas</td>
				<td class='ColumnTD'><b>Refrigerio</td>
				<td class='ColumnTD'><b>H.Efectivas</td>
			</tr>";

			$i=0;
			while(!$rs->EOF()){
				$i+=1;
				$cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena .="<td align=center>" . $i . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[0]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[1]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[2]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[3]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[4]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[13]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[14]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[5]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[6]->value . "&nbsp;</td>";
				$cadena .="<td align=left>" . $rs->fields[7]->value . ":" . $rs->fields[8]->value ."&nbsp;</td>";
				$cadena .="</tr>";
				$rs->movenext();
			}
			$cadena .= "</table>";
		}
	 	return $cadena;
	}
        */
        
    
        
	$cadena="";
	$rpta="OK";
	$cn=$this->getMyConexionADO();
	
        $ssql = " select turno_codigo,turno_id,turno_descripcion,convert(varchar(5),cast(cast(Turno_Hora_Inicio ";
        $ssql.= " as varchar)+':'+cast(Turno_Minuto_Inicio as varchar) as datetime),108) as hora_inicio, ";
        $ssql.= " convert(varchar(5),cast(cast(Turno_Hora_Fin as varchar)+':'+cast(Turno_Minuto_Fin ";
        $ssql.= " as varchar) as datetime),108) as hora_termino, ";
        $ssql.= " convert(varchar(5),turno_thoras,108) as total_horas,turno_refrigerio as duracion_colacion, ";
        $ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ isnull(Turno_Minutos,0.0)) - ";
        $ssql.= " (isnull(Turno_Refrigerio,0.0)) )/60 as total_horas, ";
        $ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ isnull(Turno_Minutos,0.0)) - ";
        $ssql.= " (isnull(Turno_Refrigerio,0.0)) )%60 as total_minutos, ";
        $ssql.= " (isnull(Turno_Refrigerio,0.0))/60 as horas_refrigerio, ";
        $ssql.= " (isnull(Turno_Refrigerio,0.0))%60 as minutos_refrigerio, ";
        $ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ ";
        $ssql.= " isnull(Turno_Minutos,0.0)))/60 as ttotal_horas, ";
        $ssql.= " (convert(int,(isnull(Turno_Horas,0.0)*60)+ ";
        $ssql.= " isnull(Turno_Minutos,0.0)))%60 as ttotal_minutos, turno_descanzo,turno_descanso2 ";
        $ssql.= " from vca_turnosth ";
        $ssql.= " where tipo_area_codigo=".$tipo;
        $ssql.= " order by 2 ";
        
		
        $rs = $cn->Execute($ssql);
        $cn->debug=true;
        if(!$rs->EOF){
            $cadena = "<table class='FormTable' id='listado' width='80%' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:80%'>
            <tr>
                    <td colspan='11' align=center><b>Catalogo de turnos</td>
            </tr>
            <tr>
                    <td colspan='11' align=center><b>&nbsp;</td>
            </tr>
            <tr align='center' style='background-color:#D8DAB4'>
            <td class='ColumnTD' style='width:100px'><b>Nro.</td>
            <td class='ColumnTD'><b>Codigo</td>
            <td class='ColumnTD'><b>Turno_ID</td>
            <td class='ColumnTD'><b>Descripcion</td>
            <td class='ColumnTD'><b>Hora_Inicio</td>
            <td class='ColumnTD'><b>Hora_Termino</td>
            <td class='ColumnTD'><b>Descanso1</td>
            <td class='ColumnTD'><b>Descanso2</td>
            <td class='ColumnTD'><b>Total_Horas</td>
            <td class='ColumnTD'><b>Refrigerio</td>
            <td class='ColumnTD'><b>H.Efectivas</td>
            </tr>";

            $i=0;
            while(!$rs->EOF){
                $i+=1;
                $cadena .="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>";
                $cadena .="<td align=center>".$i."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[0]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[1]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[2]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[3]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[4]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[13]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[14]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[5]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[6]."&nbsp;</td>";
                $cadena .="<td align=left>".$rs->fields[7].":".$rs->fields[8]."&nbsp;</td>";
                $cadena .="</tr>";
                $rs->MoveNext();
            }
            
            $cadena .= "</table>";
        }
        
        return $cadena;
	
}

}
?>
