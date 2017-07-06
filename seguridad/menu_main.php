<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Asistencias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset rows="50%,50%"  frameborder="SI" border="0" framespacing="0" cols="*" >
  <frame id='frame_sup'    name="frame_sup" src="menu_opciones.php" frameborder="si" marginwidth="0" marginheight="0"  scrolling='yes'>
  <frame id='frame_inf' name="frame_inf"  src="menu_opciones_job.php?menu_codigo=&menu_codigo_padre=" frameborder="0" marginwidth="1" marginheight="0" SCROLLING='yes'>
</frameset>
<noframes>
<body>

</body>
</noframes>
</html