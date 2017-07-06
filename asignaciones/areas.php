<?php
header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
?>

<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<link rel="stylesheet" type="text/css" href="../../asistencia/default.css">
<script language="JavaScript" src="../../default.js"></script>
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
window.returnValue = "";

var cual= 0;
var descrip='';

function Area_onclick(codigo,descripcion){
	if (codigo==0){
		alert("seleccione un registro");
		return;
	}
	self.returnValue = codigo + '¬' + descripcion;
	self.close();
  }
  
function cmdCancelar_onclick() {
	self.returnValue = 0
	self.close();
}
</SCRIPT>
<script language=vbscript>
</script>

<link href="../../style/tstyle.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>

<?php

$nombre = $_GET["nombre"];

$ssql = "select Area_codigo, Area_descripcion  as Descripcion ";
$ssql.=" from Area where Area_activo=1 ";
if ($nombre!='') $ssql.= " and  Area_descripcion like '%$nombre%'";
$ssql.= " order by Descripcion ";
echo $ssql;
?>
<center class='FormHeaderFont"' >Seleccione Código</center>
<TABLE class="FormTABLE" style="WIDTH:90%" ALIGN=center BORDER=1 CELLSPACING=1 CELLPADDING=1>
	<TR align="center">
		<TD class="FieldCaptionTD">Código</TD>
		<TD class="FieldCaptionTD">Area</TD>
	</TR>
	
	<?php 
	
	//echo $ssql; 	
	$rs_result = consultar_sql($ssql);
	$rs = mssql_fetch_row($rs_result);
	while ($rs){ 
	?>
	<TR bgcolor=white id=tr<?php echo $rs[0]?>>
		<TD class="DataTD">
			<font id=font<?php echo $rs[0] ?> title=<?php echo $rs[0] ?> style="CURSOR: hand" LANGUAGE=javascript onclick="return Area_onclick(<?php echo $rs[0] ?>', '<?php echo $rs[1] ?>'>')" color=Black><?php echo $rs[0] ?></font>
		</TD>
		<TD class="DataTD"><?php echo $rs[1] ?></TD>
	</TR>
	<?php
	$rs=mssql_fetch_row($rs_result);
	}
	?>
</TABLE>
<TABLE class="FormTABLE" WIDTH=280 ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=1>
	<TR>
		<TD align=center>
			<INPUT class=button type="button" value="Cancelar" id=cmdCancelar name=cmdCancelar style="width=80px" LANGUAGE=javascript onclick="return cmdCancelar_onclick()">
			</TD>
	</TR>
</table>
</BODY>
</HTML>