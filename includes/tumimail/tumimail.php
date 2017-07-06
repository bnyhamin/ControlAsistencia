<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
</head>
<body>
<?php
require_once("class.phpmailer.php");
/*
$host = $_GET["host"];
$from = $_GET["from"];
$to = $_GET["to"];
$asunto = $_GET["asunto"];
$mensaje = $_GET["mensaje"];
*/

$mail = new PHPMailer();

$mail->IsSMTP();                                      // set mailer to use SMTP

//$mail->Host = "10.252.130.35"; //"10.226.5.103";//"10.252.130.35";  // specify main and backup server
$host = "10.252.130.35";
$from = "iatento@atentoperu.com.pe";
$to = "99260404@bellsouth.net.pe";
$asunto="atento";
$mensaje="cuerpo de prueba phpmail";

$mail->Host = $host;
$mail->SMTPAuth = false;     // turn on SMTP authentication
//$mail->Username = "jswan";  // SMTP username
//$mail->Password = "secret"; // SMTP password

$mail->From = $from;
$mail->FromName = "Intranet Atento";
$mail->AddAddress($to);
//$mail->AddAddress("cquintana@tp.com.pe", "Camilo");
//$mail->AddAddress("ellen@example.com");                  // name is optional
//$mail->AddReplyTo("info@example.com", "Information");

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = $asunto; //"(Atento - ECCUS) Mensaje del Sistema";
$mail->Body    = $mensaje; //"Ejemplo de mensaje <b>En Negrita</b>";
$mail->AltBody = $mensaje; //"This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent";
?>
<script language=javascript>
//window.close();
</script>

</body>