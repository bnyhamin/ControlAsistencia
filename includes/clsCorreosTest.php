<?php
require_once("tumimail/class.phpmailer.php");
class ca_envio_correos extends mantenimiento{

function enviar_mail($para, $asunto, $mensaje){
	$rpta="OK";
	$mail = new PHPMailer();
	//$host = "localhost";
	//$host = "10.252.130.35";
	//$from = "iatento@atentoperu.com.pe";
	//$from = "itumi@tumihost.com";
	$host = $this->host_mail;
	$from = $this->from_mail;
	//echo "host: " . $host;
	//echo "\nfrom: " . $from; 
	
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
	//$mail->AddAddress($para, 'Atento Peru'); 
	if(!$mail->Send()){
	   $rpta = "Mensaje no puede ser enviado!. " . $mail->ErrorInfo;
	}
	$mail=null;
	return $rpta;
}

}
?>