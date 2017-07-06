<?php header("Expires: 0"); 
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv='pragma' content='no-cache'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>
<title>Registro de Diario de Gestión</title>	
	<style>
TABLE
	{
		BORDER-BOTTOM: #00415d 0pt solid;
		BORDER-LEFT: #00415d 0pt solid;
		BORDER-RIGHT: #00415d 0pt solid;
		BORDER-TOP: #00415d 0pt solid;
		FONT-SIZE: 8pt;
		MARGIN: 4px;
		PADDING-BOTTOM: 0px;
		PADDING-LEFT: 0px;
		PADDING-RIGHT: 0px;
		PADDING-TOP: 0px
	}
	</style>
<SCRIPT LANGUAGE=javascript>
<!--
function formatohora(o){ 
 var ok=false; 
 var a=window.event.keyCode; 
 var texto= o.value; 
 if (texto.length > 4){
	o.value = texto.substr(0,4);
	ok=true;
	return ok;
 } 
 if (texto.length == 2){ 
	o.value += ":"; 
 } 
 if (a>=48 && a<=57){
	ok=true; 
 } 
 return ok; 
}

function cmdCerrar_onclick() {
	self.returnValue = 0
	self.close();
}

function activar(radio){
var objeto="radio" + radio;
//alert (objeto);
	for (i=0; i<=document.frm.length-1;i++)
		if (document.frm.elements[i].checked){
			document.frm.elements[i].checked=0;
		}

	document.frm.elements[objeto].checked=1;
}
function cmdEliminar_onclick() {
var i=0;
var nombreRadio="";
	for (i=0; i<=document.frm.length-1;i++)
		if (document.frm.elements[i].checked){
			nombreRadio = document.frm.elements[i].name;
			//alert(nombreRadio);
		}
	eliminarVB(nombreRadio)	
}

//-->
</SCRIPT>

<SCRIPT LANGUAGE=javascript FOR=cmdEliminar EVENT=onclick>
<!--
 cmdEliminar_onclick()
//-->
</SCRIPT>

<SCRIPT LANGUAGE=vbscript>
dim serverURL
serverURL = "TimeSheetRemote.asp"
dim aspObject

sub myCallBack(co)
	msgbox "CALLBACK " & _
	"status = " & co.status & chr(10) &	"message = " & co.message & chr(10) & _
	"context = " & co.context & chr(10) & "data = " & co.data & chr(10) & _
	"return_value = " & co.return_value,16,"Mensaje"
end sub

sub eliminarVB(radio)
	'msgbox radio & " "  & len(radio)
	if msgbox("Seguro de Eliminar el Registro?",4,"Eliminar")=7 then exit sub
	frm.hddAccion.value="ELI"
	frm.sEliminar.value = radio
	frm.submit()
end sub

sub cmdSave_onclick()
	if frm.txtHoras.value="" then 
		msgbox "Indique numero de Horas de Atencion del Servicio", 16, "Error"
		frm.txtHoras.focus 
		exit sub
	end if
	frm.hddAccion.value="save"
	frm.submit()
end sub

sub cmdguardar_onclick()
	if frm.cboServicios.value=0 then 
		msgbox "Seleccione el servicio", 16, "Error"
		frm.cboservicios.focus 
		exit sub
	end if
	if frm.cboTipo.value <> 5 and frm.cboTipo.value <> 4 and frm.cboTipo.value <> 0 then
		if not isnumeric(frm.txtMinutos0.value) then
			msgbox "Error, Tiempo en minutos debe ser un valor entero y numérico mayor que Cero(0)"
			frm.txtMinutos0.focus
			frm.txtMinutos0.select 
			exit sub
		end if
		if frm.txtMinutos0.value = 0 then
			msgbox "Error, Tiempo en minutos debe ser un valor entero y numérico mayor que Cero(0)"
			frm.txtMinutos0.focus
			frm.txtMinutos0.select 
			exit sub
		end if
		if not isnumeric(frm.txtPosi0.value) then
			msgbox "Error, Numero de Posiciones debe ser un valor entero y numérico mayor que Cero(0)"
			frm.txtPosi0.focus
			frm.txtPosi0.select 
			exit sub
		end if
		if frm.txtPosi0.value = 0 then
			msgbox "Error, Numero de Posiciones debe ser un valor entero y numérico mayor que Cero(0)"
			frm.txtPosi0.focus
			frm.txtPosi0.select 
			exit sub
		end if
	else
		if frm.txtDesc0.value="" then
			msgbox "Error, Escriba descripcion de incidencia"
			frm.txtDesc0.focus
			frm.txtDesc0.select 
			exit sub
		end if
	end if
	if msgbox("Confirma Guardar Datos",4,"Guardar")=7 then exit sub
	'---------------------* guardar Registros de Gestion *----------------
	frm.hddAccion.value="Guardar"
	frm.submit()
end sub

sub cboServicios_onchange
	if frm.cboServicios.value= 0 then
		exit sub
	else
		frm.hddAccion.value=""
		frm.submit()	
	end if
end sub

sub cboTipo_onchange
	if frm.cboServicios.value= 0 then
		msgbox "Seleccione Servicio"
		frm.cboServicios.focus()
		'frm.cboTipo.value=0
		exit sub
	end if
	if frm.cboTipo.value= 0 then
		exit sub
	else
		frm.hddAccion.value=""
		frm.submit()	
	end if
end sub

</SCRIPT>
<%

codigoServicio = Request.Form("cboServicios")
if not isnull(codigoServicio) and codigoServicio <>"" then
	codigoServicio = Request.Form("cboServicios")
	codigoTipo = Request.Form("cboTipo")
	strDescripcion = Request.Form("txtDesc0")
else
	codigoServicio = 0
	codigoTipo = 0
end if

'------- Grabar registro ----------------------
if Request.Form("hddAccion") = "Guardar" then
	set cm=server.CreateObject("ADODB.command")
	with cm
		.ActiveConnection=session("cngestion")
		.CommandText = "Execute ? = sp_ts_Insert_Hoja_Gestion ?, ?, ?, ?, ?, ?, ? "
		.Parameters (1)= clng(trim(codigoServicio)) 'codigo de Servicio
		.Parameters (2)= cint(trim(codigoTipo))		 'Codigo de Tipo de hoja
		.Parameters (3)= trim(Session("dtFechaModifica"))'Fecha seleccionada para modificar
		if codigoTipo = 5 or codigoTipo = 4 then
			.Parameters (4)= null	'Total de Minutos
			.Parameters (5)= null	'Total de Posiciones
		else
			.Parameters (4)= cint(trim(Request.Form("txtMinutos0")))	'Total de Minutos
			.Parameters (5)= cint(trim(Request.Form("txtPosi0")))		'Total de Posiciones
		end if
		.parameters (6)= clng(session("EmpleadoCodigo")) 'codigo del coordinador o supervisor
		.parameters (7)= Trim(strDescripcion) 'Descripcion de la incidencia
		.Execute 
		done=.Parameters(0)
	end with
	set cm=nothing
	select case done
	case 1:	Response.Write "<strong>Error al insertar registro.</strong>"
			Response.End()
	case 2: Response.Write "<br><strong>Error, no existen datos de Registro Diario de Operadores para esta fecha.</strong>"
			Response.Write "<br><strong>Para el registro de las incidencias, necesario primero ingresar los datos del Registro Diario de Operadores  del servicio.</strong>"
			Response.End()
	end select

end if
'*-------------------- Eliminar Registro de la base de datos--------
if Request.Form("hddAccion") = "ELI" then
	'Response.Write strEliminar & "<br>" 
	ServDelete = mid(Request.Form("sEliminar"), 6)

	set cm=server.CreateObject("ADODB.command")
	with cm
		.ActiveConnection=session("cngestion")
		.CommandText = "Execute ? = sp_ts_Delete_Hoja_Gestion ? "
		.Parameters (1)= clng(trim(ServDelete))
		.Execute 
		done=.Parameters(0)
	end with
	set cm=nothing
	select case done
	case 1:	Response.Write "<strong>Error al intentar eliminar el registro.</strong>"
		
	end select

end if

'*-------------------- Registrar Diario de Servicio --------
if Request.Form("hddAccion") = "save" then
	'Response.Write "DiarioServicio" & "<br>" 
	set cm=server.CreateObject("ADODB.command")
	with cm
		.ActiveConnection=session("cngestion")
		.CommandText = "Execute ? = sp_ts_IU_Diario_Servicio ?, ?, ?, ?, ?, ? "
		.Parameters (1)= clng(trim(codigoServicio)) 'codigo de Servicio
		.Parameters (2)= cint(trim(codigoTipo))		 'Codigo de Tipo de hoja
		.Parameters (3)= trim(Session("dtFechaModifica"))'Fecha seleccionada para modificar
		cadHoras = replace(Request.Form("txtHoras"),":",",")
		.Parameters (4)= cadHoras	'Total de horas
		.Parameters (5)= Request.Form("txtObs")	'observaciones
		.parameters (6)= clng(session("EmpleadoCodigo")) 'codigo del coordinador o supervisor
		.Execute 
		done=.Parameters(0)
	end with
	set cm=nothing
	select case done
	case 1:	Response.Write "<strong>Error al insertar registro.</strong>"
			Response.End()
	case 2: Response.Write "<br><strong>Error al actualizar registro</strong>"
			Response.End()
	end select

end if


%>
</HEAD>
<BODY>
<form id=frm name=frm action="DiarioGestion.asp" method=post>

<TABLE align=center border=0 cellPadding=1 cellSpacing=1 width=400 bordercolor=Goldenrod>
  <TR>
    <TD style="with:100px"><STRONG>Seleccione Servicio</STRONG></TD>
    <TD colspan=2>
		<SELECT id=cboServicios name=cboServicios style="HEIGHT: 18px; WIDTH: 259px"> 
			<OPTION value=0 selected>-- Seleccione Servicio --</OPTION>
			<%
			set rs=server.CreateObject("ADODB.Recordset")
			'sSql="sp_ts_Servicios " & session("EmpleadoCodigo")
			ssql="sp_ts_Servicios_Filtrados " & session("EmpleadoCodigo") & ",'" &  trim(cstr(session("dtFechaModifica"))) & "'"
			rs.open sSql,session("cngestion"),3
			do while not rs.EOF
				if rs("dias") = 1 then
					if cint(codigoServicio) = cint(rs("cod_Campana"))	then%>
						<OPTION selected value=<%=rs("cod_Campana")%>><%=rs("exp_Descripcion")%></OPTION>
					<%else%>
						<OPTION value=<%=rs("cod_Campana")%>><%=rs("exp_Descripcion")%></OPTION>
			<%		end if
				end if
			rs.MoveNext 
			loop
			%>
        </SELECT></TD>
    </TR>
  <TR>
    <TD style="with:100px"><STRONG>Fecha</STRONG></TD>
    <TD colspan=2>
		<input style="TEXT-ALIGN: center; WIDTH: 100px" name="txtFecha" id="txtFecha" readOnly value="<%=Session("dtFechaModifica")%>"  disabled>
	</TD>
  </TR>
</table>

<TABLE border=0 cellPadding=1 cellSpacing=1 width=400 align=center style="HEIGHT: 24px; WIDTH: 468px">
  <TR  bgcolor=steelblue align=middle>
    <TD colspan=2><font size=3 color=gold><STRONG>Diario de Gestión</STRONG></font></TD>
  </TR>
  <TR align=center style="Height:30px">
  <td><STRONG>Seleccione Tipo de Incidencia</STRONG></td>
  <td>
	<SELECT id=cboTipo name=cboTipo>
    <%
	set rsT = server.CreateObject("adodb.recordset")
	ssql = "SELECT THoja_Codigo, THoja_Descripcion, THoja_Activo FROM Tipo_Hoja WHERE (THoja_Activo = 1) order by THoja_Descripcion"
	rsT.Open ssql, session("cngestion"), 3 %>
		<OPTION value=0>- Seleccione Tipo de Incidencia -</OPTION>
	<%do while not rsT.EOF
		if cint(codigoTipo) = cint(rst("THoja_Codigo")) then%>
			<OPTION selected value=<%=rst("THoja_Codigo")%>><%=rst("THoja_Descripcion")%></OPTION>
		<%else%>
			<OPTION value=<%=rst("THoja_Codigo")%>><%=rst("THoja_Descripcion")%></OPTION>
		<%end if
		rsT.MoveNext 
	loop
	%>
	</SELECT>
  </td>	
  </TR>
</TABLE>  
<% if codigoTipo <>6 then %>
	<TABLE align=center border=1 cellPadding=2 cellSpacing=0 width=500 bordercolordark=DarkSlateBlue>
	  <TR  bordercolordark=Firebrick  bgcolor=Wheat style="align: middle" align=center>
			<TD style="width: 30px;align: center"><STRONG>Nro.</STRONG></TD>
			<TD style="width: 100px;align: center"><STRONG>Duración en Minutos</STRONG></TD>
			<TD style="width: 100px;align: center"><STRONG>Posiciones Afectadas</STRONG></TD>
			<TD style="width: 150px;align: center"><STRONG>Descripción de Incidencia</STRONG></TD>
			<TD style="width: align: center"><STRONG>Eliminar</STRONG></TD>
	  </TR>
	   
		<%'recupero los registros con datos de hoja_Gestion ******************
		set rsGestion = server.CreateObject("adodb.recordset")
		ssql = "sp_ts_Detalle_Gestion " & codigoServicio & ", " & codigoTipo & ", '" & Session("dtFechaModifica") & "'"
		'Response.Write ssql
		rsGestion.Open ssql, session("cngestion")
		ix=0
		do while not rsGestion.EOF
		ix= ix + 1
		%>
		<TR  bordercolordark=Firebrick  bgcolor=Oldlace>
			<TD align=right><STRONG><%=ix%></STRONG></TD>
			<TD ><INPUT readonly type="text" style="width: 80px"  id=<%="txtMinutos" & cstr(rsGestion("HGestion_Codigo"))%>  name=<%="txtMinutos" & cstr(rsGestion("HGestion_Codigo"))%> value=<%=rsGestion("HGestion_Minutos")%>></TD>
			<TD ><INPUT readonly type="text" style="width: 80px"  id=<%="txtPosi" & cstr(rsGestion("HGestion_Codigo"))%>   name=<%="txtPosi" & cstr(rsGestion("HGestion_Codigo"))%> value=<%=rsGestion("HGestion_Posiciones")%>></TD>
			<TD ><INPUT type="text" style="width: 250px" maxlength=250 id=<%="txtDesc" & cstr(rsGestion("HGestion_Codigo"))%>  name=<%="txtDesc" & cstr(rsGestion("HGestion_Codigo"))%> value="<%=rsGestion("HGestion_Descripcion")%>"></TD>
			<TD ><INPUT type="radio" style="width: 80px" id=<%="radio" & cstr(rsGestion("HGestion_Codigo"))%> name=<%="radio" & cstr(rsGestion("HGestion_Codigo"))%> onclick="<%="Javascript:activar(" & cstr(rsGestion("HGestion_Codigo")) & ");"%>"></TD>
			
		</TR>
		<%rsGestion.MoveNext 
		loop
		ix= ix + 1
		' --- coloco la linea de entrada de datos ****************************
		%>
		<TR  bordercolordark=Firebrick  bgcolor=Oldlace>
			<TD align=right><STRONG><%=ix%></STRONG></TD>
			<%if codigoTipo= 5 or codigoTipo= 4 then 'incidencia Accion Correctora o Incidencias Varias %>
				<TD ><INPUT type="text" class=disabled style="width: 80px" disabled id=txtMinutos0 name=txtMinutos0 value=></TD>
				<TD ><INPUT type="text" class=disabled style="width: 80px" disabled id=txtPosi0 name=txtPosi0 value=></TD>
			<%else%>
				<TD ><INPUT type="text" style="width: 80px" id=txtMinutos0 name=txtMinutos0 value=></TD>
				<TD ><INPUT type="text" style="width: 80px" id=txtPosi0 name=txtPosi0 value=></TD>
			<%end if%>
				<TD ><INPUT type="text" style="width: 250px" maxlength=250 id=txtDesc0 name=txtDesc0 value=""></TD>
			<TD >&nbsp;</TD>
		</TR>
	</table> 
	
	<TABLE align=center border=0 cellPadding=1 cellSpacing= 1 width=400 bordercolordark=IndianRed>
	  <TR style="HEIGHT: 40px"  align=center>
		<TD></TD>
		<TD  align=center>
			<INPUT id=cmdGuardar name=cmdGuardar type=button value=Guardar style="width:80px">&nbsp;
			<INPUT id=cmdEliminar name=cmdEliminar type=button value=Eliminar style="width:80px" onclick="javascript:eliminar();">&nbsp;
			<INPUT type="button" value="Cerrar" id=cmdCerrar name=cmdCerrar style="width:80px" LANGUAGE=javascript onclick="return cmdCerrar_onclick()">
		</TD>
	  </TR>
	 </TABLE>
<%else%>
	<TABLE align=center border=1 cellPadding=2 cellSpacing=0 width=500 bordercolordark=DarkSlateBlue>
	  <TR  bordercolordark=Firebrick  bgcolor=Wheat style="align: middle" align=center>
			<TD style="width: 100px;align: center"><STRONG>Duración en Horas</STRONG></TD>
			<TD style="width: 150px;align: center"><STRONG>Comentarios</STRONG></TD>
	  </TR>
	  <%
	  	ssql = "select * from diario_servicio where cod_campana = " & codigoServicio & " and THoja_Codigo = " & codigoTipo & " and "
		ssql = ssql & "	Diario_Fecha >= CONVERT(DATETIME, '" & Session("dtFechaModifica") & "', 103) and Diario_Fecha < CONVERT(DATETIME, '" & Session("dtFechaModifica") & "', 103)+1 "
		set rsDS = server.CreateObject("adodb.recordset")
		'response.Write(ssql)
		rsDS.open ssql, session("cnGestion")
		if not rsDS.eof then
			xhrs = rsDS("Diario_Horas")
			xhrs = replace(xhrs, ".", ":")
			xhrs = replace(xhrs, ",", ":")
			xobs = rsDS("Diario_Comentario")
		else
			xhrs = ""
			xobs = ""
		end if
		set rsDS=nothing
		
	  %>
	  <TR  bordercolordark=Firebrick  bgcolor=Oldlace align=center>
	  		<TD ><INPUT type="text" style="width:50px" id=txtHoras name=txtHoras value='<%=xhrs%>'  onkeypress="return formatohora(this);"></TD>
			<td><textarea cols=50 rows=3 name=txtObs id=txtObs><%=xobs%></textarea></td>
	  </tr>
	  </table>
	  
	  <TABLE align=center border=0 cellPadding=1 cellSpacing= 1 width=400 bordercolordark=IndianRed>
	  <TR style="HEIGHT: 40px"  align=center>
		<TD></TD>
		<TD  align=center>
			<INPUT id=cmdSave name=cmdSave type=button value=Guardar style="width:80px">&nbsp;
			<INPUT type="button" value="Cerrar" id=cmdCerrar name=cmdCerrar style="width:80px" LANGUAGE=javascript onclick="return cmdCerrar_onclick()"></TD>
		<TD></TD>
	  </TR>
	 </TABLE>
	  
<% end if%>
<INPUT type="hidden" id=CodCord name=CodCord value=<%=session("EmpleadoCodigo")%>>
<INPUT type="hidden" id=hddAccion name=hddAccion value=>
<INPUT type="hidden" id=sEliminar name=sEliminar value=>

</form>
<script language="JavaScript" src="../RemoteScripting/rs.htm"></script>
<script language="JavaScript">RSEnableRemoteScripting("../RemoteScripting/");</script>

</BODY>
</HTML>
