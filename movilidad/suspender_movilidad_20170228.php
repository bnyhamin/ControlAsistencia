<?PHP header('Expires: 0');
  require_once('../../Includes/Seguridad.php');
  require_once('../../Includes/Connection.php');
  require_once('../../Includes/Constantes.php');
  require_once('../includes/clsca_movilidad.php');

  $movil_codigo='';
  $movil_fecha='';
  $ruta_codigo='';
  $ruta_descripcion='';
  $movil_unidad_codigo='';
  $movil_unidad_descripcion='';
  $movil_tipo_codigo='';
  $movil_tipo_descripcion='';
  $movilidad_asistencia='';
  $fecha_sanciona='';
  $usuario_sanciona='';
  $fecha='';

 $Empleado_id='';
 $fecha_registro='';
 $usuario_registro='';
 $fecha_modifica='';
 $usuario_modifica='';

   $o = new ca_movilidad();
   $o->MyDBName= db_name();
   $o->MyUrl = db_host();
   $o->MyUser= db_user();
   $o->MyPwd = db_pass();

   $sRpta="";
   $pagina= "";
   $orden= "";
   $buscam= "";

  if (isset($_POST["empleado_codigo"])) $Empleado_id = $_POST["empleado_codigo"];
  if (isset($_POST["txtFecha"])) $fecha = $_POST["txtFecha"];
  if (isset($_POST["txtEmpleado"])) $empleado = $_POST["txtEmpleado"];

  if (isset($_POST["hddaccion"])){
  	if ($_POST["hddaccion"]=="FND"){//la orden es buscar
  		  $o->Empleado_Codigo=$Empleado_id;
  		  $o->movilidad_fecha=$fecha;
  		  $rpta=$o->Query_asistencia();
  		  if ($rpta!='OK') echo '<center><font color=#cc0000><b>' . $rpta . '<b></font></center><br>';
		  $movil_tipo_codigo= $o->movil_tipo_codigo;
		  $movil_tipo_descripcion= $o->movil_tipo_descripcion;
		  $ruta_codigo=$o->ruta_codigo;
		  $ruta_descripcion=$o->ruta_descripcion;
		  $movil_unidad_codigo= $o->movil_unidad_codigo;
		  $movil_unidad_descripcion= $o->movil_unidad_descripcion;
		  $movilidad_asistencia= $o->movilidad_asistencia;
		  $fecha_sanciona= $o->fecha_sanciona;
		  $usuario_sanciona=$o->usuario_sanciona;
  	}
  	if ($_POST["hddaccion"]=="NEW"){//la orden es nuevo
  		$Empleado_id ='';
  		$empleado='';
    }
    if ($_POST["hddaccion"]=="SVE"){//la orden es grabar
       $o->Empleado_Codigo= $Empleado_id;
       $o->movilidad_fecha= $fecha;
       $o->movilidad_asistencia= $_POST["rdo"];
       $o->usuario_sanciona= $id_usuario;

       $sRpta = $o->Update_asistencia();
       if ($sRpta!="OK"){
           echo $sRpta;
       }else{
       	?>
       	<script language=javascript>
       	alert('Se guardo registro');
       	</script>
       	<?php
       }
     }
  }
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<meta http-equiv='pragma' content='no-cache'>
<META NAME=author CONTENT='TUMI Solutions S.A.C.'>
<title>Maestro de Unidades de Transporte</title>
<link rel="stylesheet" type="text/css" href="../../default.css">
<script language="JavaScript" src="../../default.js"></script>
</HEAD>
<body Class='PageBody'  >
<center class=TITOpciones>Registro de Inasistencia de Movilidad</center>

<script language='javascript'>
function limpiarEmpleado(){
	frm.empleado_codigo.value="";
 }

function BuscarEmpleado(){
 	if (document.frm.txtEmpleado.value == 0){
 		alert('Escriba apellido o nombre de empleado a buscar');
 		document.frm.txtEmpleado.focus();
 		return false;
 	}

	var valor = window.showModalDialog("../../Requerimientos/Coordinar.php?filtro=" + document.frm.txtEmpleado.value + "&area=0","Empleado",'dialogWidth:500px; dialogHeight:500px');
	if (valor == "" || valor == "0" ){
		 return;
	}

	arr_valor = valor.split("¬");
	document.frm.empleado_codigo.value = arr_valor[0];
	document.frm.txtEmpleado.value =  arr_valor[1];

}
function buscar(){
	if(document.frm.empleado_codigo.value==''){
		alert('Seleccione empleado');
		document.frm.txtEmpleado.focus();
		return false;
	}
	if(document.frm.txtFecha.value==''){
		alert('Seleccione fecha');
		document.frm.cmdFecha.focus();
		return false;
	}
	document.frm.hddaccion.value='FND';
	document.frm.submit();
}
function otro(){
	document.frm.empleado_codigo.value='';
	document.frm.txtEmpleado.value='';
	document.frm.hddaccion.value='NEW';
	document.frm.submit();
}
function confirmar(){
	if(document.frm.empleado_codigo==''){
		alert('Seleccione empleado');
		document.frm.txtEmpleado.focus();
		return false;
	}
	if(document.frm.txtFecha.value==''){
		alert('Seleccione fecha');
		document.frm.cmdFecha.focus();
		return false;
	}
 	if (confirm('confirme guardar los datos')== false) return false;
	document.frm.hddaccion.value='SVE';
	return true;
}

</script>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post'  onSubmit='javascript:return confirmar();'>
<table class='Table' width='70%' align='center' cellspacing='1' cellpadding='1' border='0'>
<tr>
 <td  class='ColumnTD'  align='right'>
     Empleado &nbsp;</td>
 <td  class='DataTD'>
     <input type="text" name="txtEmpleado" style="TEXT-ALIGN: left" id="txtEmpleado" class="input" size=51 value="<?php if(isset($empleado)) echo $empleado; ?>" onchange="javascript: limpiarEmpleado();">
     &nbsp;<img src="../../Images/buscaroff.gif" width="16" height="15" border="0" alt="Buscar Empleado " style="cursor:hand" onclick="javascript:BuscarEmpleado();">
     <input type="hidden" name="empleado_codigo" id="empleado_codigo" value="<?php echo $Empleado_id ?>">
 </td>
</tr>
<tr>
 <td  class='ColumnTD'  align='right'>
     Fecha &nbsp;</td>
 <td  class='DataTD'>
    <input class=input size=11 name="txtFecha" readOnly style="TEXT-ALIGN: left" value="<?php echo $fecha ?>" alt='Fecha de servicio'>
   	<input type=button id=cmdFecha class=button onclick="popFrame.fPopCalendar(txtFecha,txtFecha,popCal);return false" alt="Fecha de Servicio" value='V'>
 </td>
</tr>
<tr>
 <td colspan=2 class='ColumnTD' align='center'>&nbsp;<input type=button class=button value='Buscar' id=cmdbuscar onclick='buscar()'></td>
</tr>
<tr>
 <td colspan=2 class='DataTD' align='center'>&nbsp;</td>
</tr>
</table>
<br>
<?php
if ($movil_tipo_codigo!=''){
	?>
	<table class='Table' align='center' cellspacing='1' cellpadding='1' border='0'>
	<tr align='center'>
	 <td class='ColumnTD' width=200px>Servicio</td>
	 <td class='ColumnTD' width=200px>Ruta</td>
	 <td class='ColumnTD' width=150px>Unidad</td>
	 <td class='ColumnTD' width=120px>Asistió</td>
	 <td class='ColumnTD' width=100px>Fecha Registro</td>
	</tr>
	<tr align='center'>
	 <td class='DataTD'><?php echo $movil_tipo_descripcion ?></td>
	 <td class='DataTD'><?php echo $ruta_descripcion ?></td>
	 <td class='DataTD'><?php echo $movil_unidad_descripcion ?></td>
	 <td class='DataTD'>
	 	Si <input type="radio" name="rdo" name="rdo" value="1" <?php if ($movilidad_asistencia==1) echo 'checked' ?>/> &nbsp;&nbsp;
  		No <input type="radio" name="rdo" name="rdo" value="2" <?php if ($movilidad_asistencia==2) echo 'checked' ?>/>
	 </td>
	 <td class='DataTD'><?php echo $fecha_sanciona ?></td>
	</tr>
	</table>
	<?php
}
?>
<br>
<table class='Table' align='center' cellspacing='1' cellpadding='1' border='0'>
<tr align='center'>
 <td colspan=2  class='ColumnTD'>
   <input name='cmdGuardar' id='cmdGuardar' type='submit' value='Guardar'  class='Button' style='width:70px' >&nbsp;&nbsp;
   <input name='cmdNuevo' id='cmdNuevo' type='button' value='Nuevo'  class='Button' style='width:70px' onclick="otro()" >
 </td>
</tr>
<input type='hidden' name='hddaccion' id='hddaccion' value=''>
</table>
<br>
<!--<img style="CURSOR: hand" src="../../images/left.gif" onclick="javascript:location.href='../../index.php';" WIDTH="18" HEIGHT="18">-->
<br><br>

<div id="popCal" style="POSITION:absolute;visibility:hidden;border:2px ridge;width:10">
	<iframe name="popFrame" id="popFrame" src="../../popcj.htm" frameborder="0" scrolling="no" width="183" height="188"></iframe>
</div>
<script event="onclick()" for="document">popCal.style.visibility = "hidden";</script>


</form>
</body>
</HTML>
<!-- TUMI Solutions S.A.C.-->