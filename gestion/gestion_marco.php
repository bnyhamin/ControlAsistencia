<?php header("Expires: 0"); ?>
<?php
require_once("../../Includes/Connection.php");
require_once("../includes/Seguridad.php");
?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>

<frameset rows="140,*" cols="*" frameborder="NO" border="1" framespacing="1">
  <frame src="gestion_head.php" name="topFrame" scrolling="no" noresize >
  <!--<frame src="gestion_head.php" name="topFrame" scrolling="yes" >-->
  <frameset cols="280,*" frameborder="YES" border="1" framespacing="1" >
    <frame src="gestion_left.php" name="leftFrame" scrolling="no">
    <frame src="gestion_right.php" name="rightFrame" scrolling="yes">
  </frameset>
</frameset>
<noframes>
<body >

</body>
</noframes>
</html>