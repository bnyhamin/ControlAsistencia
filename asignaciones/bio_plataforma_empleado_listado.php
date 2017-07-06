<?php header("Expires: 0");

require('../../Includes/Connection.php');
require('../../Includes/Constantes.php');
require_once("../../Includes/mantenimiento.php");
require('../includes/bio_Plataforma.php');

$o = new BIO_Plataforma();
$o->MyDBName= db_name();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();

?>
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
  <link rel="stylesheet" type="text/css" href="../../default.css"/>
  <title>Plataformas Asignadas a Empleado</title>
 
</head>

<body>
    
    <table class='table' style='width:810px'>
       <tr>
          <td colspan=10 class=TITOpciones align='left'><font size="2"><?php echo $o->obtenNombreEmpleadoPlataforma($_GET['empleadoCodigo'])?></font></td>
       </tr>
      
       <tr class='cabecera'>
          <td style='width:10px'>Nro</td>
          <td align="left">Plataforma</td>
          <td align="left">Desde</td> 
          <td align="left">Hasta</td>
          <td align="left">Observaci&oacute;n</td>
          <td align="left">Activo</td>
          <td align="left">Usuario Registro</td>
          <td align="left">Fecha Asignaci&oacute;n</td>
          <td align="left">Usuario Desactivaci&oacute;n</td>
          <td align="left">Fecha Desactivaci&oacute;n</td>

      </tr>
          <?php
              $o->listarPlataformasAsignadasEmpleados($_GET['empleadoCodigo'], TRUE);
          ?>
      </table>
      <br>
     <table class='sinborde' cellspacing='0' cellpadding='0' border='0' align='center'>
      <tr align='left'>
       <td>
          <input name='cmdCerra'  id='cmdCerra'  type='button' value='Cerrar' class='Button' style='width:70px' onclick='window.close();'/> 
       </td>
      </tr>
      </table>
      </form>
</body>
</html>