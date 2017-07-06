<?php header("Expires: 0"); ?>
<?php

?>

<html> 
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Seleccione Fechas</title>
<meta http-equiv='pragma' content='no-cache'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>

<script language="JavaScript">
 window.returnValue = "0";
 function cmdAceptar_onclick(){
 if (validarCampo('frm','txtFechaInicio')!=true) return false;
 if (validarCampo('frm','txtFechaFin')!=true) return false;
 self.returnValue = document.frm.txtFechaInicio.value + '@' + document.frm.txtFechaFin.value;
 self.close();
}
	
 function cerrar(){
	self.returnValue = '0';
	self.close();
 }
 
function escribirFecha() {
dia=dia + "";
mes=mes + "";
ano=ano + "";

if((dia + "").length==1) dia="0" + dia;
if(mes.length==1) mes="0" + mes;
campoDeRetorno.value = dia + "/" + mes + "/" + ano;

}

function pedirFecha(campoTexto,nombreCampo) {
  ano = anoHoy();
  mes = mesHoy();
  dia = diaHoy();
  campoDeRetorno = campoTexto;
  titulo = nombreCampo;
  dibujarMes(ano,mes);

}
 
</script>
</head>

<body class="PageBODY">
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcjs.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method='post'>
<br>
<!--<center><font style='font-size:12px' ><b>Seleccione Fechas</b></font></center>-->
<table width='80%'border="0" align="center" >
  <tr>
       <td class='DataTD' align='center'>
		      <b>Del</b>
          		<input type='text' class='input' id="txtFechaInicio" name="txtFechaInicio" readOnly size=11 value=''>
   				<img onClick="javascript:pedirFecha(txtFechaInicio,'Cambiar Fecha1');" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha" />	
	    
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Al<b>
        <input type='text' class='input' id="txtFechaFin" name="txtFechaFin" readOnly size=11 value='' />
   		<img onClick="javascript:pedirFecha(txtFechaFin,'Cambiar Fecha2');" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha" />	
	</td>
    </tr>
	<tr align="center">
		<td class='ColumnTD' colspan=2>&nbsp;
		</td>
	</tr>
	<tr align="center">
		<td  colspan=2>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Aceptar" style="width:80px" onclick="cmdAceptar_onclick()" />
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onclick="cerrar()" />
		</td>
	</tr>
</table>
<font size=2 color=DarkSlateBlue id=lblDescripcion name=lblDescripcion>&nbsp;</font>
</form>
</body>
</html>


