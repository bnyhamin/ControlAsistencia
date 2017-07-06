<?php
header("Expires: 0");
$rutaClases='D:\ApacheGroup\Apache2.2\htdocs\sispersonal01';
require_once($rutaClases."\includes\Connection.php");
require_once($rutaClases."\includes\mantenimiento.php");
require_once($rutaClases."\controlasistencia\includes\clsCA_Envio_Correos_Asistencia.php");

$ase = new ca_envio_correos_asistencia();
$ase->MyUrl = db_host();
$ase->MyUser= db_user();
$ase->MyPwd = db_pass();
$ase->MyDBName= db_name();
$ase->host_mail = mail_host();
$ase->from_mail = mail_from();

echo "iniciando...<br/><br/>";
//$ase->flag=1;
//$ase->actualiza_hora();

$arrRESPONSABLES=$ase->obtener_responsable();


for($i=0;$i<count($arrRESPONSABLES);$i++){
    
    $arrRESPONSABLE=$arrRESPONSABLES[$i];
    
    $empleado_jefe=$arrRESPONSABLE["empleado_jefe"];
    $empleado_correo=$arrRESPONSABLE["empleado_correo"];
    $fecha=$arrRESPONSABLE["fecha"];
    $empleados=$arrRESPONSABLE["empleados"];
    
    echo "enviando correos responsables...<br/><br/>";
        
        $textoRe="";
        $textoRe.="<span style=font-family:Arial;font-size:14px;>Estimado(a) ".$empleado_jefe.", <br/><br/>";
        $textoRe.="Le informamos que el dia ".$fecha." los siguientes Sr(es) no marcaron su fin de turno: <br/><br/>";
                
        $tasesorRe="";
        $tasesorRe.='<table cellpadding="0" cellspacing="0" border="1" style="border:1px solid #8BC3F7;font-size:14px;font-family:Arial;">';
        $tasesorRe.="<tr style=background-color:#8BC3F7;><td>EMPLEADO</td><td>DNI</td><td>TURNO</td></tr>"; 
        $tasesorRe.=$empleados;
        $tasesorRe.="</table><br/>";

        $textoRe.=$tasesorRe;
        $textoRe.="ATTE <br/><br/> ".SistemaNombreGap()." <br/><br/>";
        
		$asuntoRe="";
        $asuntoRe="Fin de asistencia no marcada";      
        
        $msgsend=$ase->enviar_mail($empleado_correo,$asuntoRe, $textoRe);
        echo "Envio: ".$msgsend."<br/><br/>";

}


$ase->flag=0;
$ase->actualiza_hora();


?>