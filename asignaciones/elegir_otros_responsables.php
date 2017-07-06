<?php
header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");

$asistencia_codigo = $_GET['asistencia'];
$responsable_codigo = $_GET['responsable'];
$empleado_codigo = $_GET['empleado'];
//$= $_GET[''];


?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<link rel="stylesheet" type="text/css" href="../../asistencia/grupos/default.css" >
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../../asistencia/grupos/jscript.js"></script>
<SCRIPT ID='clientEventHandlersJS' LANGUAGE=javascript>
function cmdCancelar_onclick() {
	self.returnValue = 0
	self.close();
}
</SCRIPT>

<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>

<center class='FormHeaderFont"' >Otros Responsables</center>
<TABLE class="FormTABLE" style="WIDTH:90%" ALIGN=center BORDER=1 CELLSPACING=1 CELLPADDING=1>
	<TR align="center">
		<TD class="DataTD">
		  <input type="radio" id="rdo" name='rdo' value='1' onClick="buscar(this.value)">
		  <input type="radio" id="rdo" name='rdo' value='2' onClick="buscar(this.value)">
		</TD>
	</TR>
</table>
<?php
                $ssql=  "select Responsable_codigo, Empleado, Area_Descripcion ";
				$ssql.=  " from vca_empleado_responsables ";
				$ssql.=  " where Estado_Codigo=1 and area_codigo<>" . $area ."";
				if($asistencia_codigo !=0 ){
				    $ssql .=" and responsable_codigo not in (select responsable_codigo from ";
					$ssql.= " ca_asistencia_responsables ";
                	$ssql.= " where empleado_codigo=" . $empleado_codigo . " and asistencia_codigo=" . $asistencia_codigo . ")";
				}
				$ssql.=  " and responsable_codigo <> " . $responsable_codigo . "";
				$ssql.=  " Order by 2 ";
				$rs_e = consultar_sql($ssql);
			   if (mssql_num_rows($rs_e)>0){ //-- mostrar resultados
				$i=0;
				while ($rs= mssql_fetch_row($rs_e)){
					$i+=1;
					?>
					<tr class='ca_DataTD' onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'>
					<?php
					echo "	<td align=right >" . $i . "&nbsp;</td>";
					echo "	<td align=center>&nbsp;<input type=radio id='rdo" . $rs[0] . "' name='chk" . $rs[0] . "' value='" . $rs[0] . "'>&nbsp;</td>";
					echo "	<td align=right>" . $rs[0] . "&nbsp;</td>";
					echo "	<td >&nbsp;" . $rs[1] . "</td>";
					echo "	<td >&nbsp;" . $rs[2] . "</td>";
					echo "</tr>";
				}
			}

?>
<TABLE class="FormTABLE" style="WIDTH:90%" ALIGN='center' BORDER='1' CELLSPACING='1' CELLPADDING='1'>
	<TR align='center'>
		 <td>
		  <input type='button' name='cmdBuscar' id='cmdBuscar'>
		 </td>
	</TR>
</table>
</TABLE>
<TABLE class="FormTABLE" WIDTH=280 ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=1>
	<TR>
		<TD align=center>
			<INPUT class=button type="button" value="Cancelar" id=cmdCancelar name=cmdCancelar style="width=80px" LANGUAGE=javascript onclick="return cmdCancelar_onclick()">
			</TD>
	</TR>
</table>
<input id="hddArea" name="hddArea" value=''>
</form>
</BODY>
</HTML>