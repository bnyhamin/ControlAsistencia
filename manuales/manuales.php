<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache"/>
<title><?php echo tituloGAP() ?>-Control de Asistencia - Personal</title>
<META NAME=author CONTENT="TUMI Solutions S.A.C.">
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
    
<script type="text/javascript">
     function abrir(opt){
         var manual='';
         switch (opt){
             case 1: manual='Guia de Usuario Gestion de Turno Semanal.pdf';
             break;
             case 2: manual='tutorial_incidencias_gap_2012.pdf';
             break;
             case 3: manual='Guia_incidencias.pdf';
             break;
             case 4: manual='asignacion_empleado_plataforma.pdf';
             break;
             case 5: manual='Catalogo de Incidencias.xls';
             break;
             case 6: manual='Guia_Intercambio_Turnos.pdf';
             break;
         }
         var x=window.open(manual, 'win'+opt,'width=630px, resizable=yes, left=400px, align=center');
         x.focus();
         return true;
     }
     
     function salir(){
         window.location.href='../menu.php';
     }
 </script>
</HEAD>
<body class="PageBODY"  onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
<table  border="0" align="center" cellpadding="0" cellspacing="1" >
<tr>
    <td align="center">
        <button type="button" style="cursor:hand" title="Salir" style="width:20px; height:21px" onclick="javascript:salir();">
            <img src="../images/biGoingOnline.gif" height="15">
            &nbsp;
        </button>&nbsp;
    </td>
</tr>
</table>
<br/>
<table  align="center" border="0" cellspacing="0" cellpadding="1" width="50%">
<tr>
    <td class="ColumnTD" colspan="2" align="center">Listado de Manuales GAP</td>
</tr>
<tr>
    <td align="left" width="300">
        <b>Gu&iacute;a de usuario gesti&oacute;n de turno semanal</b>
    </td>
    <td>
        <img  src="../images/Doc_Pdf.gif" alt="Ver Manual" onclick="abrir(1)" style="cursor:hand;text-decoration: none; color: #000000;" />
    </td>
</tr>
<tr>
    <td align="left">
        <b>Gu&iacute;a de usuario gesti&oacute;n de incidencias validables</b>
    </td>
    <td>
        <img  src="../images/Doc_Pdf.gif" alt="Ver Manual" onclick="abrir(2)" style="cursor:hand;" />
    </td>  
</tr>
<tr>
    <td align="left">
        <b>Gu&iacute;a de incidencias</b>
    </td>
    <td>
        <img  src="../images/Doc_Pdf.gif" alt="Ver Manual" onclick="abrir(3)" style="cursor:hand;" />
    </td>  
</tr>
<tr>
    <td align="left">
        <b>Gu&iacute;a  asignaci&oacute;n de empleados a plataformas </b>
    </td>
    <td>
        <img  src="../images/Doc_Pdf.gif" alt="Ver Manual" onclick="abrir(4)" style="cursor:hand;" />
    </td>  
</tr>
<tr>
    <td align="left">
        <b>Cat&aacute;logo de Incidencias</b>
    </td>
    <td>
        <img  src="../images/excel_ico.GIF" alt="Ver Manual" onclick="abrir(5)" style="cursor:hand;" />
    </td>  
</tr>
<tr>
    <td align="left">
        <b>Gu&iacute;a de Intercambio de Turno</b>
    </td>
    <td>
        <img  src="../images/Doc_Pdf.gif" alt="Ver Manual" onclick="abrir(6)" style="cursor:hand;" />
    </td>  
</tr>

<tr>
    <td class='ColumnTD' colspan='2'>&nbsp;</td>
</tr>
</table>
<br>
</form>
</body>
</HTML>


