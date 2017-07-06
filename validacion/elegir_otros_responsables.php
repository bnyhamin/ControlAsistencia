<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Asistencia_Responsables.php");

$o = new ca_asistencia_responsables();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

if (isset($_GET["empleado"])) $empleado_codigo = $_GET["empleado"];
if (isset($_POST["empleado_cod"])) $empleado_codigo = $_POST["empleado_cod"];

if (isset($_GET["responsable"])) $responsable_codigo = $_GET["responsable"];
if (isset($_POST["responsable_cod"])) $responsable_codigo = $_POST["responsable_cod"];

if (isset($_GET["asistencia"])) $asistencia_codigo = $_GET["asistencia"];
if (isset($_POST["asistencia_cod"])) $asistencia_codigo = $_POST["asistencia_cod"];

if (isset($_GET["area"])) $area_codigo = $_GET["area"];
if (isset($_POST["area_cod"])) $area_codigo = $_POST["area_cod"];

if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
    //-- registrar
    $o->empleado_codigo=$empleado_codigo;
    $o->asistencia_codigo=$asistencia_codigo;
    $o->responsable_codigo =$_GET["codigo"];
    $mensaje = $o->registrar_responsable_asistencia();
    //echo "empleado_codigo:" . $empleado_codigo;
    if($mensaje=='OK'){
?>
    <script language='javascript'>
        alert('Agregado exitosamente!!');
        window.opener.document.forms['frm'].submit();
        window.close();
    </script>
<?php
        }
    }
}
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<link rel='stylesheet' type='text/css' href='../style/tstyle.css'>

<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../../asistencia/grupos/jscript.js"></script>
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>
function cerrar() {
	self.close();
}
function BuscarArea(){
 	var valor = window.showModalDialog("areas.php?nombre=" + frm.txtArea.value, "Areas",'dialogWidth:500px; dialogHeight:500px');
	if (valor == "" || valor == "0" ){
		 return false;
	}
	
	arr_valor = valor.split("¬");
	frm.hddArea.value = arr_valor[0];
	frm.txtArea.value =  arr_valor[1];
 
 }
 function area(tipo){
 document.frm.action +="?tipo=" + tipo;
 document.frm.submit();
 }
 function cmdsupervisor(codigo){
 if (confirm('Confirme agregar otro responsable?')==false) return false;
	document.frm.action +="?codigo=" + codigo;
	document.frm.hddaccion.value='INS';
    document.frm.submit()
 }
</SCRIPT>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY class="PageBODY">
<center class='FormHeaderFont'>Otros Responsables</center><br>
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF']  ?>" method="post">
<TABLE style="WIDTH:90%" ALIGN=center BORDER=0 CELLSPACING=0 CELLPADDING=0>
	<TR align="center">
	    <TD CLASS="CA_FormHeaderFont" align="right">Seleccione:
        </TD>
		<TD align="left">
		  &nbsp;&nbsp;<b>Mi area</b><input type='radio' id=rdo name=rdo value="1" onclick='area(this.value)' <?php if(isset($tipo)) if($tipo==1) echo "checked" ?> >
		  <b>Otra Area</b><input type='radio' id=rdo name=rdo value="2" onclick='area(this.value)' <?php if(isset($tipo)) if($tipo==2) echo "checked" ?>>
	   </td>
	</TR>
</TABLE>
<br>
<TABLE class="FormTABLE" WIDTH='100%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
<tr align="center" >
    <td class="ColumnTD" style='width:10px'>Sel
    </td>
    <td class="ColumnTD" >Código
    </td>
    <td class="ColumnTD">Nombre
    </td>
	<td class="ColumnTD">Area
    </td>
</tr>
<?php
if (isset($_GET["tipo"])){
    $tipo = $_GET["tipo"];
    
    if(isset($empleado_codigo)){
        $o->empleado_codigo=$empleado_codigo;
    }
    
    if(isset($asistencia_codigo)){
        $o->asistencia_codigo=$asistencia_codigo;
    }
    
    $cadena=$o->lista_empleados_para_responsables($area_codigo,$tipo);
    echo $cadena;
 }  
?>
</TR>
</table>
<br>
<table border="0" align="center" >
	<tr align="center">
		<td>
			<input type="button" class="Button" id="cmdg" name="cmdg" value="Cancelar" style="width:80px"  onClick="cerrar()">
		</td>
	</tr>
</table>
<input type='hidden' id="hddaccion" name="hddaccion" value=''/>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_codigo ?>"/>
<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>"/>
<input type='hidden' id='asistencia_cod' name='asistencia_cod' value="<?php echo $asistencia_codigo ?>"/>
<input type='hidden' id='area_cod' name='area_cod' value="<?php echo $area_codigo ?>"/>
</form>
</BODY>
</HTML>