<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<frameset rows="100,*" cols="*" frameborder="NO" border="1" framespacing="1">
  <frame src="val_head.php" name="topFrame" scrolling="NO" noresize >
  <frameset cols="470,*" frameborder="NO" border="1" framespacing="1" >
    <frame src="val_left.php" name="leftFrame" scrolling="auto">
    <frame src="val_right.php?empleado_cod=0&fecha=&tipo=" name="rightFrame" scrolling="auto">
  </frameset>
</frameset>
<noframes>
<body >

</body>
</noframes>
</html>