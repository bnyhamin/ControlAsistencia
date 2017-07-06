<?php header("Expires: 0");

require_once("../includes/Seguridad.php");  
require('../../Includes/Connection.php');
require('../../Includes/Constantes.php');
//require('../../Includes/MyCombo.php');
require_once("../../Includes/mantenimiento.php");
require('../includes/bio_Plataforma.php');

 
$o = new BIO_Plataforma();
$o->MyDBName= db_name();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$CB_Codigo = isset($_GET["CB_Codigo"])?$_GET["CB_Codigo"]:0;
?>
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	
  <link rel="stylesheet" type="text/css" href="../../default.css"/>
  <script language="JavaScript" src="../../default.js"></script>
  <script language="JavaScript" src="../jscript.js"></script>
  <title>Asignar Acceso Empleados a Plataformas</title>
 
</head>
	
<body>
<center class="TITOpciones">Log de carga masiva de Biom&eacute;trico</center>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>?hddaccion=asignar' method='post' enctype="multipart/form-data">
<table  style='width:650px' border="0" align="center">
<tr>
  <td colspan="2">
    <table style='width:650px'  >
       <tr><td colspan="4" class="TITOpciones" align='left'><font size="2">Empleados Seleccionados</font></td></tr>
       <tr class='cabecera'>
          <td>Nro</td>
          <td align="left">Documento</td> 
          <td align="left">Error</td>
          
      </tr>
      
          <?php
            $rs = $o->Listado_Log_Carga($CB_Codigo);
            $i=0;
            if($rs->RecordCount() > 0):?>
                <?php while(!$rs->EOF):?>
                    <tr>
                        <td class='DataTD'><?php echo ++$i?></td>
                        <td class='DataTD'><?php echo $rs->fields[0]?></td>
                        <td class='DataTD'><?php echo $rs->fields[1]?></td>
                        <?php $rs->MoveNext();?>
                    </tr>
                <?php endwhile;?>
           <?php else: ?>
                <tr>
                    <td>No se encontraron registos</td>
                </tr>
           <?php endif; ?> 
      </table>
    </table>
  </td>
</tr>

</table>

<br/>
</form>
</body>
</html>