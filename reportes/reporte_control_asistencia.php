<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Empleado_Rol.php");
$id = $_SESSION["empleado_codigo"];
$area_codigo="";
$area_descripcion="";
$salida_noche="";
$disabled="";
$rol_ger='0';
$rol_sup='0';

$usr = new ca_usuarios();
$usr->setMyUrl(db_host());
$usr->setMyUser(db_user());
$usr->setMyPwd(db_pass());
$usr->setMyDBName(db_name());

$o = new ca_empleado_rol();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());



$usr->empleado_codigo = $id;
$r = $usr->Identificar();
$empleado=$usr->empleado_nombre;
$fecha=$usr->fecha_actual;
$area=$usr->area_codigo;
$area_desc=$usr->area_nombre;

$o->empleado_codigo=$id;
$o->rol_codigo=3;
$r=$o->Verifica_rol();

$o->rol_codigo=5;
$r5=$o->Verifica_rol(); // buscar si es administrador funcional

if($r=="OK" OR $r5=="OK"){
	$rol_ger='0';
	$rol_sup='0';
}else{
	$o->rol_codigo=6;
	$r=$o->Verifica_rol();
    if($r=="OK"){
		$rol_ger='1';
        $rol_sup='0';
	}else {
		$o->rol_codigo=1;
		$r=$o->Verifica_rol();
	    if($r=="OK"){
		 $rol_ger='0';
		 $rol_sup='1';
	    }else{
	     $o->rol_codigo=8;
		 $r=$o->Verifica_rol();
	     if($r=="OK"){
		 $rol_ger='0';
		 $rol_sup='0';
	     }else{
	         $o->rol_codigo=2;
			 $r=$o->Verifica_rol();
		     if($r=="OK"){
			 $rol_ger='0';
			 $rol_sup='1';
	        }
	    }
    }
  }
}

?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<title><?php echo tituloGAP() ?>-Control de Asistencia - Personal</title>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>
<style>
.CA_Input1 { border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; border-top: 1px solid #000000; font-color: #000000;  font-size: 10px; font-family: Verdana, Arial, Helvetica;font-weight: bold}

</style>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" >
var server="<?php echo $url_jreportes ?>";
function cancelar(){
self.location.href="../menu.php";
}
function Ir(){
	self.location.href="reportes_generados.php";
}

function generar(){
var usuario="<?php echo $id ?>";
if (validarCampo('frm','txtFecha')!=true) return false;
var fecha=document.frm.txtFecha.value;

if(document.frm.area_codigo.value==''){
	 alert('Busque area');
	 document.frm.area_descripcion.focus();
	 return false;
}

var area_codigo=document.frm.area_codigo.value;
if (!document.frm.salida_noche.checked){
  var valor = window.showModalDialog(server + "Gap/ca_generar.jsp?fecha=" + fecha + "&area_codigo=" + area_codigo + "&usuario_id=" + usuario + "&opcion=21", "Reporte","dialogWidth:400px; dialogHeight:150px");
} else {
  //alert("Reporte Con Salida >=7:00pm\nAun en Desarrollo");
  var valor = window.showModalDialog(server + "Gap/ca_generar.jsp?fecha=" + fecha + "&area_codigo=" + area_codigo + "&usuario_id=" + usuario + "&opcion=23", "Reporte","dialogWidth:400px; dialogHeight:150px");
}

}

function buscararea(search){
	CenterWindow("../manto/listaAreas.php?strbuscar=" + search + "&flag_gerente=0&area_codigo=0&todos=1","ModalChild",600,500,"yes","center");
	return true;
}

function filtroAreas(codigo, descripcion){
	document.frm.area_codigo.value=codigo;
	document.frm.area_descripcion.value=descripcion;
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
</HEAD>

<body class="PageBODY"  onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action="<?php echo $_SERVER['PHP_SELF']  ?>" method='post'>
<?php
   	 $disabled="";
   	 if($rol_sup=='1'){
            $area_codigo=$area;
            $area_descripcion=$area_desc;
            $disabled="disabled";
   	 }
	 ?>
<center class=FormHeaderFont>Reporte de Asistencia - Personal</center>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
   <tr>
    <td align="right" width='45%'><b>Usuario&nbsp;:</b></td>
	<td align="left" ><font color=#3366CC><b><?php echo $empleado?></b></font></td>
  </tr>
</table>
<br>
<table width='75%' align="center" border=0 cellspacing="0" cellpadding="1">
<tr>
	<td class='ColumnTD' colspan='2' align='center'>Selecccione Parámetros&nbsp;</td>
</tr>
<tr>
    <td  align="right" width="35%">
			<b>Fecha</b>&nbsp;:&nbsp;</td>
	<td >
			<input type='text' class='Input' id="txtFecha" name="txtFecha" readOnly size=12 value='<?php echo $fecha ?>'>
   			<img onClick="javascript:pedirFecha(txtFecha,'Cambiar Fecha');" src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' alt="Seleccionar Fecha"/>
	</td>
</tr>
<tr>
	<td align='right'><b>Area&nbsp;:&nbsp;</b></td>
	<td>
		   <Input  class='Input' type='hidden' name='area_codigo' id='area_codigo' value="<?php echo $area_codigo ?>">
           <Input  class='Input' type='text' name='area_descripcion' id='area_descripcion' value='<?php echo $area_descripcion  ?>' maxlength="120" style='width:350px;' <?php  echo $disabled ?>>
      <?php if($rol_sup=='0'){?>
	       <img id='bus' src="../images/buscaroff.gif" alt="buscar area" onclick="return buscararea(document.frm.area_descripcion.value)" style="cursor:hand">
	  <?php } else{?>
	  	    <img id='bus' src="../images/buscaroff.gif" alt="buscar area" onclick="return buscararea(document.frm.area_descripcion.value)" style="cursor:hand" style='visibility:hidden'>
	  <?php
	       }
	  ?>
	</td>
</tr>
<tr>
	<td align='right'><b>¿Salida >= 7:00pm?&nbsp;:&nbsp;</b></td>
	<td>
		   <Input class='Input' type='checkbox' name='salida_noche' id='salida_noche' value="1" <?php if ($salida_noche=="1") echo " checked" ?>>
	</td>
</tr>
<tr>
	<td class='ColumnTD' colspan='2'>&nbsp;</td>
</tr>
</table>
<br>
<table width='400px' align='center' cellspacing='0' cellpadding='0' border='0'>
<tr align='center'>
 <td colspan=2  >
 <input name='cmdGuardar' id='cmdGuardar' type='button' value='Generar'  class='Button' style='WIDTH: 90px;' onclick="generar()">
 <input name='cmdCancelar' id='cmdCancelar' type='button' value='Cancelar'  class='Button' onclick="cancelar()"style='WIDTH: 90px;'>
 <INPUT class=button type="button" value="Ir a Bandeja" id=cmdIr name=cmdIr style="width=90px" LANGUAGE=javascript onclick="Ir()">

 </td>
</tr>
</table>
</form>
</body>
</HTML>
<!-- TUMI Solutions S.A.C.-->
