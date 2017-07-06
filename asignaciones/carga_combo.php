<?php 
  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);


header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/MyCombo.php");

if (isset($_GET["tipo"])) $tipo_codigo = $_GET["tipo"];


	$combo = new MyCombo();
	$combo->setMyUrl(db_host());
	$combo->setMyUser(db_user());
	$combo->setMyPwd(db_pass());
	$combo->setMyDBName(db_name());
	$ssql = "select CAST(Anio as varchar)+'-'+CAST(Feriado_Codigo as varchar), ";
	$ssql .= "'['+(select pais_nombre from paises p where p.pais_codigo = f.Pais_Codigo)+'] '+Feriado_Descripcion+' ('+CONVERT(varchar(10),Fecha_Feriado,103)+')' ";
	$ssql .= "from CA_Feriados f where Feriado_Activo = 1 and Tipo_Feriado = ".$tipo_codigo;
	$ssql .= " and Fecha_Feriado >= CONVERT(DATETIME, CONVERT(VARCHAR(8),GETDATE(),112),103)";

	$ssql .= " Order by 2";
	$combo->query = $ssql;
	$combo->name = "Feriado_Codigo";
	$combo->value = "";
	$combo->more = "class=select style='width:180px'";
	$rpta = $combo->Construir();
	echo $rpta;


 ?>
 <!DOCTYPE html>
 <html>
 <head>
 <meta http-equiv="Content-Type" content="text/html; charset=utf8">

 </head>
 <body>
 
 </body>
 </html>