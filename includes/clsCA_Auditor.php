<?php
require_once(PathIncludes() . "mantenimiento.php");

class ca_auditor extends mantenimiento{
var $empleado_dni="";
var $asistencia_fecha="";
var $empleado_nombre = "";

function Reporte_Auditor(){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
        // $cn->debug = true;
  
        $cn->Execute("SET ANSI_WARNINGS ON");
        $cn->Execute("SET ANSI_NULLS ON");
  	$dia_actual = $cn->Execute(" SELECT CONVERT(VARCHAR(10),GETDATE(),103) ");
  	$dia_anterior = $cn->Execute(" SELECT CONVERT(VARCHAR(10),DATEADD(DAY,-1,GETDATE()),103) ");
  	$hora_actual = $cn->Execute(" SELECT CONVERT(VARCHAR(10),GETDATE(),108) ");
	if($cn){
		//caso 1 datos del empleado
		$ssql = " exec spCA_Auditor '".$this->asistencia_fecha."','".$this->empleado_dni."', '".$this->empleado_nombre."', 1 ";
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
		
				$empleado_codigo = $rs->fields[0];
				$cadena ="
				<b>Datos del Empleado ".$rs->fields[2]."  
				<table class='FormTable' width='200%' border='0' cellPadding='0'
				cellSpacing='0' style='width:100%'>
				<tr align='center' style='background-color:#D8DAB4'>
			    	<td class='ColumnTD'><b>Area</td>
			    	<td class='ColumnTD'><b>Modlidad</td>
		    		<td class='ColumnTD'><b>Cargo</td>
		    		<td class='ColumnTD'><b>Servicio</td>
					<td class='ColumnTD'><b>Combinacion default</td>
				</tr>";
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center>" . $rs->fields[3] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[4] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[5] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[6] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[7] . "&nbsp;</td>";
				
				$cadena.="
				</table>
				<br>
				<br>
				<b>Datos Programacion  ......  Supervisor  [  ".$rs->fields[24]."  ]
				<table class='FormTable' width='200%' border='0' cellPadding='0'
				cellSpacing='0' style='width:100%'>
				<tr align='center' style='background-color:#D8DAB4'>
					<td class='ColumnTD'><b>Detalle</td>
					<td class='ColumnTD'><b>Semana</td>
					<td class='ColumnTD'><b>Año</td>
					<td class='ColumnTD'><b>Turno</td>
					<td class='ColumnTD'><b>Turno Duo</td>
					<td class='ColumnTD'><b>Inicio</td>
					<td class='ColumnTD'><b>Fin</td>
					<td class='ColumnTD'><b>Movimiento</td>
				</tr>";
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center><img id=img_".$rs->fields[0]."_".$rs->fields[25]."_".$rs->fields[26]."_".$rs->fields[27]."_".$rs->fields[28]." src='../../Images/asistencia/inline011.gif' border=0 style=cursor:hand onclick=cmdVersap_onclick(this.id) title=Programacion_Semanal ></td>";
				$cadena.="<td align=center>" . $rs->fields[8] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[9] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[10]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[11]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[12]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[13]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[14]. "&nbsp;</td>";
				
				$cadena.="
				</table>
				<br>
				<br>
				<b>Datos Asistencia
				<table class='FormTable' width='200%' border='0' cellPadding='0'
				cellSpacing='0' style='width:100%'>
				<tr align='center' style='background-color:#D8DAB4'>
					<td class='ColumnTD'><b>Dia</td>
					<td class='ColumnTD'><b>Entrada</td>
					<td class='ColumnTD'><b>Ip entrada</td>
					<td class='ColumnTD'><b>Salida</td>
					<td class='ColumnTD'><b>Ip salida</td>
					<td class='ColumnTD'><b>Extension</td>
					<td class='ColumnTD'><b>Entrada origen</td>
					<td class='ColumnTD'><b>Salida origen</td>
				</tr>";
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center><font style='cursor:hand' title='Ver mas detalle' onclick=ver_detalle(".$rs->fields[0].",'".$this->asistencia_fecha."',".$rs->fields[23].",0,0)><b><u>".$this->asistencia_fecha."</u></b>&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[16]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[17]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[18]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[19]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[20]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[21]. "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[22]. "&nbsp;</td>";
				$cadena.="</tr>";
				$cadena.="
				</table>
				<br>
				<br>
				";

		}
		$rs = null;
		
		//caso 2 datos de Asistencia Especial
		$cadena.="
		<b>Datos de Asistencia Especial  
		<table class='FormTable' width='200%' border='0' cellPadding='0'
		cellSpacing='0' style='width:100%'>
		<tr align='center' style='background-color:#D8DAB4'>
	    	<td class='ColumnTD'><b>Inicia</td>
	    	<td class='ColumnTD'><b>Finaliza</td>
    		<td class='ColumnTD'><b>Entrada</td>
    		<td class='ColumnTD'><b>Ip Entrada</td>
    		<td class='ColumnTD'><b>Salida</td>
    		<td class='ColumnTD'><b>Ip Salida</td>
		</tr>";
		$ssql = " exec spCA_Auditor '".$this->asistencia_fecha."','".$this->empleado_dni."', '".$this->empleado_nombre."', 2 ";
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			while(!$rs->EOF){
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center>" . $rs->fields[0] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[1] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[2] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[3] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[4] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[5] . "&nbsp;</td>";
				$rs->MoveNext();
			}
		}
		$rs = null;
		
		$cadena.="
		</table>
		<br>
		<br>";
		//caso 4 datos de biometrico total
		$cadena.="
		<img id='".$empleado_codigo."' src='../../Images/asistencia/inline011.gif' border=0 style=cursor:hand onclick=cmdVerBioAccesos_onclick(this.id) title='Ver accesos a plataformas' >	<b>Datos de Biometrico en General 
		<table class='FormTable' width='200%' border='0' cellPadding='0'
		cellSpacing='0' style='width:100%'>
		<tr align='center' style='background-color:#D8DAB4'>
	    	<td class='ColumnTD'><b>Checktime</td>
	    	<td class='ColumnTD'><b>Checktype</td>
            <td class='ColumnTD'><b>Plataforma</td>
    		<td class='ColumnTD'><b>EnGAP</td>
    		<td class='ColumnTD'><b>Fecha Registro</td>
		</tr>";
		$ssql = " exec spCA_Auditor '".$this->asistencia_fecha."','".$this->empleado_dni."', '".$this->empleado_nombre."', 4";
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			while(!$rs->EOF){
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center>" . $rs->fields[1] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[2] . "&nbsp;</td>";
                $cadena.="<td align=center>" . $rs->fields[5] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[3] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[4] . "&nbsp;</td>";
				$rs->MoveNext();
			}
		}
		$rs = null;
		
		$cadena.="
		</table>
		<br>
		<br>";
		//caso 5 datos de biometrico filtrado
		$cadena.="
		<b>Datos de Biometrico Habiles 
		<table class='FormTable' width='200%' border='0' cellPadding='0'
		cellSpacing='0' style='width:100%'>
		<tr align='center' style='background-color:#D8DAB4'>
	    	<td class='ColumnTD'><b>Checktime</td>
	    	<td class='ColumnTD'><b>Checktype</td>
    		<td class='ColumnTD'><b>EnGAP</td>
    		<td class='ColumnTD'><b>Fecha Registro</td>
		</tr>";
		$ssql = " exec spCA_Auditor '".$this->asistencia_fecha."','".$this->empleado_dni."', '".$this->empleado_nombre."', 5";
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			while(!$rs->EOF){
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center>" . $rs->fields[1] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[2] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[3] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[4] . "&nbsp;</td>";
				$rs->MoveNext();
			}
		}
		$rs = null;
		
		$cadena.="
		</table>
		<br>
		<br>";
		//caso 6 datos de Logger
		$cadena.="
		<b>Datos de Logger  
		<table class='FormTable' width='200%' border='0' cellPadding='0'
		cellSpacing='0' style='width:100%'>
		<tr align='center' style='background-color:#D8DAB4'>
	    	<td class='ColumnTD'><b>Operador id</td>
	    	<td class='ColumnTD'><b>Fecha</td>
    		<td class='ColumnTD'><b>Inicio</td>
    		<td class='ColumnTD'><b>Fin</td>
    		<td class='ColumnTD'><b>Uso Tipo</td>
		</tr>";
		$ssql = " exec spCA_Auditor '".$this->asistencia_fecha."','".$this->empleado_dni."', '".$this->empleado_nombre."', 6";
		$rs = $cn->Execute($ssql);
		if(!$rs->EOF) {
			while(!$rs->EOF){
				$cadena.="<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' 
							onMouseover=this.style.backgroundColor='#F4F4F4'>";
				$cadena.="<td align=center>" . $rs->fields[0] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[4] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[5] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[6] . "&nbsp;</td>";
				$cadena.="<td align=center>" . $rs->fields[7] . "&nbsp;</td>";
				$rs->MoveNext();
			}
		}
		$rs=null;
		$dia_mostrar = '';
		$h_part = explode(':', $hora_actual->fields[0]);

		if ($h_part[0] > 11) {
			$dia_mostrar = $dia_actual->fields[0];

		}elseif($h_part[0] == 11){
			if ($h_part[1] > 40) {
				$dia_mostrar = $dia_actual->fields[0];

			}else {
				$dia_mostrar = $dia_anterior->fields[0];
			}
		}else{
			$dia_mostrar = $dia_anterior->fields[0];
		}

		$cadena.="
		</table>
		<br>
		<br>";
                
        $cadena .="<table border='0'>";
		$cadena .="<tr align='center' style='background-color:#D8DAB4'><th>Consulta de horas adicionales y compensadas</th></tr>";
		$cadena .="<tr class='ca_DataTD'>";
		$cadena .="<td><img id='".base64_encode($empleado_codigo)."' src='../../Images/asistencia/inline011.gif' border=0 style=cursor:hand onclick=cmdHorasExtras(this.id) title='Ver accesos a plataformas' >	<b>Datos de horas extras pagadas </td>";
		$cadena .="</tr>";
		$cadena .="<tr></tr>";
		$cadena .="<tr class='ca_DataTD'>";
		$cadena .="<td colspan=2 ><img id='".$empleado_codigo."' src='../../Images/asistencia/inline011.gif' border=0 style=cursor:hand onclick=cmdSaldoActual(this.id) title='Ver accesos a plataformas' >	<b>Saldo de horas al dia de hoy ".$dia_mostrar."</td>";
		$cadena .="</tr>";
		$cadena .="</table>";
	 	return $cadena;
	}
}

function lista_empleados($term){
	$cadena="";
	$rpta="OK";
	$cn = $this->getMyConexionADO();
	$rs = $cn->Execute("SELECT empleado_codigo, empleado from vDatosActivosyCesados where empleado like '".$term."%' and empleado is not null");
    $ar_total = array();
	if($rs->RecordCount() >0){
		while(!$rs->EOF){
			$ar_total[] = array('id' =>  $rs->fields[0], 'label' => utf8_encode($rs->fields[1]), 'value' => utf8_encode($rs->fields[1]));
            $rs->MoveNext();
		}
    }

	return  $ar_total;
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


}
?>
 