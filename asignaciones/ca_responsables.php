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
<link rel="stylesheet" type="text/css" href="../../asistencia/grupos/default.css">
<script language="JavaScript" src="../../default.js"></script>
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
window.returnValue = "";

var cual= 0;
var descrip='';
function font01_onclick(font, codigo, descripcion, rol) {	 
	if (cual>0) {
		var a=document.getElementById("font"+cual);
		a.color="black";
	}
	cual=codigo 
	descrip = descripcion;
	font.color="red";
	self.returnValue = cual + '¬' + rol + '¬' + descrip;
	self.close();
}
function cmdCancelar_onclick() {
	self.returnValue = 0
	self.close();
}
function cmdAceptar_onclick() {
	if (cual==0) {
		alert("seleccione un registro");
		return false;
	}
		self.returnValue = cual + '¬' + descrip;
		self.close();
}
</SCRIPT>
<script language=vbscript>
</script>

<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>

<?php

$area = $_GET["area"];
$nombre = $_GET["nombre"];

$ssql = "select Responsable_codigo, Responsable, Rol_Codigo, Rol_descripcion ";
$ssql.=" from vca_responsable_area where area_codigo=" . $area ;
if ($nombre!='') $ssql.= " and responsable like '%$nombre%'";

$ssql.= "order by Responsable ";

?>
<center class='FormHeaderFont"' >Seleccione Código</center>
<TABLE class="FormTABLE" style="WIDTH:90%" ALIGN=center BORDER=1 CELLSPACING=1 CELLPADDING=1>
	<TR align="center">
		<TD class="FieldCaptionTD">Código</TD>
		<TD class="FieldCaptionTD">Responsable</TD>
		<TD class="FieldCaptionTD">Rol</TD>
	</TR>
	
	<?php 
	
	//echo $ssql; 	
	$rs_result = consultar_sql($ssql);
	$rs = mssql_fetch_row($rs_result);
	while ($rs){ 
	?>
	<TR bgcolor=white id=tr<?php echo $rs[0]?>>
		<TD class="DataTD">
			<font id=font<?php echo $rs[0] ?> title=<?php echo $rs[0] ?> style="CURSOR: hand" LANGUAGE=javascript onclick="return font01_onclick(<?php echo "font" . $rs[0] ?>,'<?php echo $rs[0] ?>', '<?php echo $rs[1] ?>', '<?php echo $rs[2] ?>')" color=Black><?php echo $rs[0] ?></font>
		</TD>
		<TD class="DataTD"><?php echo $rs[1] ?></TD>
		<TD class="DataTD"><?php echo $rs[3] ?></TD>
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