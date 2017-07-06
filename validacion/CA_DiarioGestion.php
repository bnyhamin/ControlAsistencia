<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
//require_once("../../includes/Seguridad.php"); 
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Hoja_Gestion.php");
require_once("../includes/clsCA_Diario_Servicio.php");

$o = new ca_hoja_gestion();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$ob = new ca_diario_servicio();
$ob->setMyUrl(db_host());
$ob->setMyUser(db_user());
$ob->setMyPwd(db_pass());
$ob->setMyDBName(db_name());

$usr = new ca_usuarios();
$usr->setMyUrl(db_host());
$usr->setMyUser(db_user());
$usr->setMyPwd(db_pass());
$usr->setMyDBName(db_name());

$id = $_SESSION["empleado_codigo"];

$usr->empleado_codigo = $id;
$r = $usr->Identificar();
$empleado= $usr->empleado_nombre;
$area= $usr->area_codigo;
$area_descripcion= $usr->area_nombre;
$jefe= $usr->empleado_jefe; // responsable area
$fecha= $usr->fecha_actual;
$dia= $usr->numero_dia;
$THoja_Codigo=0;
$cod_campana=0;
$posiciones = 0;
$minutos = 0;
if (isset($_GET["THoja_Codigo"])) $THoja_Codigo = $_GET["THoja_Codigo"];
if (isset($_GET["cod_campana"])) $cod_campana = $_GET["cod_campana"];
if (isset($_GET["txtFecha"])) $fecha = $_GET["txtFecha"];
if (isset($_POST["THoja_Codigo"])) $THoja_Codigo = $_POST["THoja_Codigo"];

if (isset($_POST["txtFecha"])) $fecha = $_POST["txtFecha"];
if (isset($_POST["cod_campana"])) $cod_campana = $_POST["cod_campana"];
if (isset($_POST["txtMinutos0"])) $minutos= $_POST["txtMinutos0"];
if (isset($_POST["txtPosi0"])) $posiciones = $_POST["txtPosi0"];
if (isset($_POST["txtDesc0"])) $descripcion = $_POST["txtDesc0"];

if (isset($_POST['hddAccion'])){
    if ($_POST['hddAccion']=='Guardar'){
        
        $o->cod_campana=$cod_campana;
        $o->THoja_Codigo=$THoja_Codigo;
        $o->HGestion_Fecha=$fecha;
        $o->HGestion_Minutos=$minutos; //Total de Minutos
        $o->HGestion_Posiciones=$posiciones; //Total de Posiciones
        $o->HGestion_Responsable=$id; //codigo del coordinador o supervisor
        $o->HGestion_Descripcion=$descripcion; 
        $rpta=$o->Addnew();
        if($rpta=="OK"){
            echo "Registro insertado";	  
        }else{
            echo "Error:" . $rpta;
        }

    }
}	
if (isset($_POST['hddAccion'])){
    if ($_POST['hddAccion']=='save'){
        
        $horas = $_POST["txtHoras"];
        $horas=ereg_replace(':','.',$horas);

        $obs= $_POST["txtObs"];
        $ob->cod_campana=$cod_campana;
        $ob->THoja_Codigo=$THoja_Codigo;
        $ob->Diario_Fecha=$fecha;
        $ob->Diario_Horas=$horas;
        $ob->Diario_Comentario=$obs;
        $ob->Diario_Responsable=$id;

        $rpta=$ob->Save();
        if($rpta=="OK"){
            echo "Registro de Diario Servicio Agregado";	  
        }else{
            echo "Error:" . $rpta;
        }
    }
}	
	
if (isset($_POST['hddAccion'])){
    if ($_POST['hddAccion']=='ELI'){
        $codigo = $_GET["codigo"];
        $o->HGestion_Codigo=$codigo;
        $rpta=$o->Delete();
        if($rpta=="OK"){
            echo "Registro Eliminado";	  
        }else{
            echo "Error:" . $rpta;
        }

    }
}	
?>
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<title>Registro de Diario de Gestión</title>	
<style type="text/css">
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
	//self.location.href='../menu.php';
	window.close();
}
function escribirFecha() {
  campoDeRetorno.value = dia + "/" + mes + "/" + ano;
  var campana=document.frm.cod_campana.value;
  var thc=document.frm.THoja_Codigo.value;
  var fecha=document.frm.txtFecha.value;
  //var fecha=campoDeRetorno.value;
  //document.frm.action +="?THoja_Codigo=" + thc + "&cod_campana=" + campana + "&txtFecha=" + fecha;
  //document.frm.submit()		
}

function activar(radio){
var objeto="radio_" + radio;
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
		}
			
	if(nombreRadio!="")	eliminarVB(nombreRadio);	
    else{
	     alert('Seleccione Registro');
		 return false;
	} 
}

function Campana(codigo){
var thc=document.frm.THoja_Codigo.value;
var fecha=document.frm.txtFecha.value;
document.frm.action +="?cod_campana=" + codigo + "&THoja_Codigo=" + thc + "&txtFecha=" + fecha;
document.frm.submit()	
}

function Thc(codigo){
var campana=document.frm.cod_campana.value;
var fecha=document.frm.txtFecha.value;
document.frm.action +="?THoja_Codigo=" + codigo + "&cod_campana=" + campana + "&txtFecha=" + fecha;
document.frm.submit()	
}


function inicializar_parametros(){
  document.frm.cod_campana.value	="0";
  document.frm.THoja_Codigo.value	="0";
}

function Guardar(){
	if(document.frm.cod_campana.value==0){
		alert('Seleccione el servicio');
		document.frm.cod_campana.focus(); 
		return false;
	}
	if(document.frm.THoja_Codigo.value==0){
		alert('Seleccione Tipo de Incidencia');
		document.frm.THoja_Codigo.focus(); 
		return false;
	}
	
	
	if (frm.THoja_Codigo.value!= 5 && frm.THoja_Codigo.value!=4 && frm.THoja_Codigo.value != 0 ){
		if(document.frm.txtMinutos0.value=="") {
		    alert('Indique Tiempo en minutos');
			document.frm.txtMinutos0.focus();
			return false;
		}else{	
			if(document.frm.txtMinutos0.value<=0){
				alert('Error, Tiempo en minutos debe ser mayor que Cero');
		    	document.frm.txtMinutos0.focus();
				return false;
			}
		}
			
		if(document.frm.txtPosi0.value=="") {
		    alert('Indique Numero de Posiciones');
			document.frm.txtPosi0.focus();
			return false;
		}else{	
			if(document.frm.txtPosi0.value<=0){
				alert('Error, Numero de Posiciones debe ser mayor que Cero');
		    	document.frm.txtPosi0.focus();
				return false;
				}
			}	
		
	}else{
		if(document.frm.txtDesc0.value==""){
			alert('Error, Escriba descripcion de incidencia');
			document.frm.txtDesc0.focus();
			return false;
		}
	}
	if (confirm('Confirma Guardar Datos')==false) return false;
	document.frm.hddAccion.value="Guardar";
	document.frm.submit();
}
function eliminarVB(radio){
	 var r=radio.split("_");
	 var cod=r[1];
    if (confirm('Seguro de Eliminar Registro')==false) return false;
	document.frm.action +="?codigo=" + cod;
	document.frm.hddAccion.value="ELI";
	document.frm.submit()
}

function cmdSave_onclick(){
    if(document.frm.cod_campana.value==0){
		alert('Seleccione el servicio');
		document.frm.cod_campana.focus(); 
		return false;
	}
	
	if(document.frm.txtHoras.value==""){ 
		alert("Indique numero de Horas de Atencion del Servicio");
		document.frm.txtHoras.focus(); 
		return false;
	}
	if (confirm('Confirma Guardar Datos')==false) return false;
	document.frm.hddAccion.value="save";
	document.frm.submit();
}
function pedirFecha(campoTexto,nombreCampo) {
  ano = anoHoy();
  mes = mesHoy();
  dia = diaHoy();
  campoDeRetorno = campoTexto;
  titulo = nombreCampo;
  dibujarMes(ano,mes);
}

</SCRIPT>

<SCRIPT LANGUAGE=javascript FOR=cmdEliminar EVENT=onclick>
 cmdEliminar_onclick()

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

</SCRIPT>
</HEAD>
<BODY class="PageBODY">
<form id=frm name=frm action="<?php echo $_SERVER['PHP_SELF'] ?>" method=post>

<center class='FormHeaderFont'>Diario de Gestión</center>
<table align='center' width="60%" border="0">
 <tr>
    <td width='40%' align=right><b>Supervisor:</b></td>
	<td class='CA_FormHeaderFont' align=left><?php echo $empleado?></td>
 </tr>
</table>
<br>
<TABLE class='FormTable' align=center border=0 cellPadding=0 cellSpacing=1 width='95%' >
   <TR>
    <TD class='ColumnTD' width='80%' align='right'><STRONG>Fecha</STRONG>&nbsp;</TD>
    <TD class='DataTD' colspan=2>&nbsp;
		<input class='input' style="TEXT-ALIGN: center; WIDTH: 70px" name="txtFecha" id="txtFecha" readOnly value='<?php echo $fecha ?>'>
	    <img onClick="javascript:pedirFecha(txtFecha,'Cambiar Fecha');inicializar_parametros();" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha">
			
	</TD>
  </TR>
  <TR>
    <TD class='ColumnTD' align='right'><STRONG>Servicio</STRONG>&nbsp;</TD>
    <TD class='DataTD' colspan=2>&nbsp;
		<?php 
                    $combo = new MyCombo();
                    $combo->setMyUrl(db_host());
                    $combo->setMyUser(db_user());
                    $combo->setMyPwd(db_pass());
                    $combo->setMyDBName(db_name());
			 
                    $sql ="select v_campanas.cod_campana as codigo, ";
                    $sql .=" v_campanas.exp_codigo + '-' + v_campanas.exp_nombrecorto + ' (' + convert(varchar,v_campanas.exp_codigo) + ')' as descripcion"; 
                    $sql .=" from v_campanas ";
                    $sql .=" where  v_campanas.exp_activo=1 and coordinacion_codigo=" . $area;                   
                    $sql .=" order by 2 asc"; 
                    
                    $combo->query = $sql;
                    $combo->name = "cod_campana"; 
                    $combo->value = $cod_campana."";
                    $combo->more = " class='select' onchange='Campana(this.value)'";
                    $rpta = $combo->Construir();
                    
                    echo $rpta;
			  
                ?>
    </TD>
</TR>
  <TR >
  <td class='ColumnTD' align='right' nowrap><STRONG>Tipo de Incidencia</STRONG>&nbsp;</td>
  <td class='DataTD'>&nbsp;
            <?php  
                    $sql = "SELECT THoja_Codigo, THoja_Descripcion, THoja_Activo FROM CA_Tipo_Hoja WHERE (THoja_Activo = 1) order by THoja_Descripcion";
                    $combo->query = $sql;
                    $combo->name = "THoja_Codigo"; 
                    $combo->value = $THoja_Codigo."";
                    $combo->more = " class='select' style='width:300px' onchange='Thc(this.value)'";
                    $rpta = $combo->Construir();
                    echo $rpta;
            ?>
  </td>	
  </TR>
</table>
<br>
<?php if($THoja_Codigo !=6){
?>
	<TABLE  class='FormTABLE' align=center border=0 cellPadding=0 cellSpacing=1 width=500 bordercolordark=DarkSlateBlue>
	  <TR style="align: middle" align=center>
			<TD class='ColumnTD' style="width: 30px;align: center"><STRONG>Nro.</STRONG></TD>
			<TD class='ColumnTD' style="width: 100px;align: center"><STRONG>Duración en Minutos</STRONG></TD>
			<TD class='ColumnTD' style="width: 100px;align: center"><STRONG>Posiciones Afectadas</STRONG></TD>
			<TD class='ColumnTD' style="width: 150px;align: center"><STRONG>Descripción de Incidencia</STRONG></TD>
			<TD class='ColumnTD' style="width: align: center"><STRONG>Eliminar</STRONG></TD>
	  </TR>
	    <?php
		//Recupero los registros con datos de hoja_Gestion 
		$o->cod_campana=$cod_campana;
		$o->THoja_Codigo=$THoja_Codigo;
		$o->HGestion_Fecha=$fecha;
		$texto=$o->Listar_Hoja_Gestion_dia($THoja_Codigo);
		echo $texto;
		?>
	</table> 
	
	<TABLE align=center border=0 cellPadding=1 cellSpacing= 1 width=400>
	  <TR style="HEIGHT: 40px"  align=center>
		<TD></TD>
		<TD  align=center>
			<INPUT class=button id=cmdGuardar name=cmdGuardar type=button value=Guardar style="width:80px" onClick="javascript:Guardar();">&nbsp;
			<INPUT class=button id=cmdEliminar name=cmdEliminar type=button value=Eliminar style="width:80px" onClick="javascript:eliminar();">&nbsp;
			<INPUT class=button type="button" value="Cerrar" id=cmdCerrar name=cmdCerrar style="width:80px" LANGUAGE=javascript onClick="return cmdCerrar_onclick()">
		</TD>
	  </TR>
	 </TABLE>
<?php }
else{?>
	<TABLE align=center border=1 cellPadding=2 cellSpacing=0 width=500 bordercolordark=DarkSlateBlue>
	  <TR style="align: middle" align=center>
			<TD class='ColumnTD' style="width: 100px;align: center"><STRONG>Duración en Horas</STRONG></TD>
			<TD class='ColumnTD' style="width: 150px;align: center"><STRONG>Comentarios</STRONG></TD>
	  </TR>
	  <?php
	  
	  $ob->THoja_Codigo=$THoja_Codigo;
	  $ob->cod_campana=$cod_campana;
	  $ob->Diario_Fecha=$fecha;
	  $ob->Listar_Diario_Servicio();	  
	  $horas=$ob->Diario_Horas;
	  //$horas=ereg_replace( "." , ";", $horas);
	  $obs=$ob->Diario_Comentario;
	  ?>
	  <TR align=center>
	  		<TD class='DataTD'><INPUT class='input' type="text" style="width:50px" id=txtHoras name=txtHoras value='<?php echo $horas ?>' onKeyPress="return formatohora(this);"></TD>
			<td class='DataTD'><textarea class='input' cols=50 rows=3 name=txtObs id=txtObs><?php echo $obs ?></textarea></td>
	  </tr>
	  </table>
	  
	  <TABLE align=center border=0 cellPadding=1 cellSpacing= 1 width=400 bordercolordark=IndianRed>
	  <TR style="HEIGHT: 40px"  align=center>
		<TD></TD>
		<TD  align=center>
			<INPUT class=button id=cmdSave name=cmdSave type=button value=Guardar style="width:80px" onClick="return cmdSave_onclick()">&nbsp;
			<INPUT class=button type="button" value="Cerrar" id=cmdCerrar name=cmdCerrar style="width:80px" LANGUAGE=javascript onClick="return cmdCerrar_onclick()"></TD>
		<TD></TD>
	  </TR>
	 </TABLE>
	  
<?php
}
?>
<INPUT type="hidden" id=hddAccion name=hddAccion value=>
<INPUT type="hidden" id=sEliminar name=sEliminar value=>
</form>
</BODY>
</HTML>