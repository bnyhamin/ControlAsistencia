<?php
require_once("tumimail/class.phpmailer.php");
class ca_envio_correos_asistencia extends mantenimiento{
var $responsable_codigo=0;    
var $hora=0;
var $tipo=0;
var $tiempo_inicio="";
var $tiempo_fin="";
var $validador_codigo=0;
var $flag=0;
var $host_mail="";
var $from_mail="";
var $lista_empleados="";

function obtener_responsable(){
    $cn=$this->getMyConexionADO();
    //$cn->debug=true;
    //$ssql="set dateformat dmy";
    //$rs1 = $cn->Execute($ssql);
    $lista_empleados="";
    $lista_dnis="";
    $lista_turnos="";
    $ssql=" SELECT
            RES.Empleado_Apellido_Paterno+' '+RES.Empleado_Apellido_Materno+' '+RES.Empleado_Nombres AS RESPONSABLE,  
            RES.Empleado_Email AS CORREO,     
            CONVERT(CHAR(10), A.Asistencia_fecha, 103) AS FECHA,
            EMP.Empleado_Apellido_Paterno+' '+EMP.Empleado_Apellido_Materno+' '+EMP.Empleado_Nombres AS EMPLEADO, 
            EMP.Empleado_Dni AS DNI,
            T.Turno_Descripcion AS TURNO 
            
            FROM CA_Asistencias A (NOLOCK)
            
            INNER JOIN CA_Asistencia_Incidencias AI ON A.Empleado_Codigo=AI.Empleado_Codigo
            AND A.Asistencia_codigo=AI.Asistencia_codigo
            
            INNER JOIN Areas AR(NOLOCK) ON AR.Area_Codigo=AI.Area_Codigo
            INNER JOIN empleados RES(NOLOCK) ON  RES.empleado_codigo=AR.empleado_responsable
			
			INNER JOIN empleados EMP(NOLOCK) ON A.empleado_codigo=EMP.Empleado_Codigo
			inner JOIN CA_Turnos T(NOLOCK) ON A.turno_codigo=T.Turno_Codigo 
                                    
            WHERE Asistencia_fecha=getdate()-1 AND AI.Incidencia_codigo=155
            
            ORDER BY 1,4 
        ";
	
    $padre=array();
    $rs = $cn->Execute($ssql);
    
    while (!$rs->EOF){
        
        $hijo=array();
        $hijo["empleado_jefe"]=$rs->fields[0];
        $hijo["empleado_correo"]=$rs->fields[1];
        $hijo["fecha"]=$rs->fields[2];
        //$lista_empleados=$lista_empleados.'<tr><td>'.$rs->fields[3].' '.$rs->fields[4].' '.$rs->fields[5]'</tr></td>';
        $lista_empleados=$lista_empleados.'<tr style=background-color:#E8A335;><td>'.$rs->fields[3].'</td><td>'.$rs->fields[4].'</td><td>'.$rs->fields[5].'</td></tr>';
        $rs->MoveNext();
        
        while ($hijo["empleado_jefe"]==$rs->fields[0]){
             
            //$lista_empleados=$lista_empleados.'<tr><td>'.$rs->fields[3].'</tr></td>';
            $lista_empleados=$lista_empleados.'<tr style=background-color:#E8A335;><td>'.$rs->fields[3].'</td><td>'.$rs->fields[4].'</td><td>'.$rs->fields[5].'</td></tr>';
            $rs->MoveNext();
            
            //echo $lista_empleados;
        
        }

        $hijo["empleados"]=$lista_empleados;
        $lista_empleados="";
        
        array_push($padre, $hijo);
        //ECHO $rs->fields[0];
        //echo $lista_empleados;
        
    }
    
    return $padre;
    
}


function enviar_mail($para, $asunto, $mensaje){
       
	$rpta="OK";
	$mail = new PHPMailer();
	//$host = "tumisolutions.com";
	//$host = "10.252.130.35";
	//$from = "iatento@atentoperu.com.pe";
	//$from = "itumisol@tumisolutions.com";
	$host = $this->host_mail;
	$from = $this->from_mail;
	
	$mail->IsSMTP(); 
	$mail->Host = $host;
	$mail->SMTPAuth = false;			
	$mail->From = $from;
	$mail->FromName = "Atento";
	$mail->AddAddress($para);
	$mail->WordWrap = 50;
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->IsHTML(true);
	$mail->Subject = $asunto; 
	$mail->Body    = $mensaje; 
	$mail->AltBody = $mensaje;
	
	if(!$mail->Send()){
	   $rpta = "Mensaje no puede ser enviado. <p>" . $mail->ErrorInfo;
       }
    //echo "cuenta=".$para ." host=" . $host . " from=" . $from ;
	$mail=null;
	return $rpta;
        
}


}
?>