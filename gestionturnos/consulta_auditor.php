<?php header("Expires: 0"); 
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Auditor.php"); 
require_once("../../Includes/MyCombo.php");
set_time_limit(30000);
$empleado_dni="";
$empleado_nombre = "";

$empleado_codigo_registro=$_SESSION["empleado_codigo"];
$hddbuscar="";


$e = new ca_auditor();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();


$actual = $e->Dia_Actual();
$fecha=$actual[0];

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

if (isset($_POST["empleado_dni"])) $empleado_dni = $_POST["empleado_dni"];
if (isset($_POST["empleado_nombre"])) $empleado_nombre = $_POST["empleado_nombre"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?> Consulta Auditor </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<!--<script language="JavaScript" src="../no_teclas.js"></script>-->
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="../../views/css/smoothness/jquery-ui-1.10.4.custom.css">
<link rel="stylesheet" type="text/css" media="all" href="../../views/js/librerias/datepicker/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script language="JavaScript">
var mensaje='';

function Buscar(){
	var empleado_dni=document.frm.empleado_dni.value;
	var empleado_nombre=document.frm.empleado_nombre.value;
	if (document.getElementById('sel_dni').checked == true && empleado_dni =='') {alert('Ingresa numero de DNI/Cedula'); return false;}
	if (document.getElementById('sel_nombre').checked == true && empleado_nombre =='') {alert('Ingresa Nombre del Empleado'); return false;}

	var fecha=document.frm.fecha.value;
	document.frm.hddbuscar.value="OK";
	document.frm.action +="?empleado_dni=" + empleado_dni + "&fecha=" + fecha;
	document.frm.submit();
}


function pedirFecha(campoTexto,nombreCampo) {
	fecha_seleccion=campoTexto.value;
	ano = anoHoy();
	mes = mesHoy();
	dia = diaHoy();
	campoDeRetorno = campoTexto;
	titulo = nombreCampo;
	dibujarMes(ano,mes);
}

function Calcular_fecha_fin(){

    //document.frames['ifrm'].location.href="calcular_fecha_fin.php?inicio=" + document.frm.fecha_ingreso.value + "&duracionvalor=" + document.frm.er_duracion_valor.value + "&duraciontipo=" + document.frm.cto_periodicidad_codigo.value;
    //ibm
    ifrm.location.href="calcular_fecha_fin.php?inicio=" + document.frm.fecha_ingreso.value + "&duracionvalor=" + document.frm.er_duracion_valor.value + "&duraciontipo=" + document.frm.cto_periodicidad_codigo.value+"&modalidad="+document.frm.modalidad_id.value;
    //console.debug(ifrm);
    //document.frames['ifrm'].location.href="calcular_fecha_fin.php?inicio=" + document.frm.fecha_ingreso.value + "&duracionvalor=" + document.frm.er_duracion_valor.value + "&duraciontipo=" + document.frm.cto_periodicidad_codigo.value+"&modalidad="+document.frm.modalidad_id.value;
    
    return false;
}

function ver_detalle(lista,f,a,r,t) {
    window.open("../gestion/lista_empleados.php?lista_sel=" + lista + "&fecha="+f+"&area_codigo="+a+"&responsable_codigo="+r+"&turno_codigo="+t+"&opcion=1.1","nombre","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable=1 width=700, height=480, center=yes")
}

function cmdVersap_onclick(valor){
    var arr = valor.split("_");
	window.showModalDialog("../gestionturnos/programacion_empleado.php?empleado_id=" + arr[1] + "&te_semana=" + arr[2] + "&te_anio=" + arr[3] + "&te_fecha_inicio=" + arr[4] + "&te_fecha_fin=" + arr[5]+'&to=p','VerSAP','dialogWidth:600px; dialogHeight:320px');
}

function cmdVerBioAccesos_onclick(empleadoCodigo) {
	window.showModalDialog("../asignaciones/bio_plataforma_empleado_listado.php?empleadoCodigo=" + empleadoCodigo ,'VerBioAccesos','dialogWidth:810px; dialogHeight:350px');
}
function cmdHorasExtras(empleado_codigo){
	//window.showModalDialog('../../Admin/detalle_horasextras.php?empleado_codigo_seleccionado='+empleado_codigo,'Horas_Extras','dialogWidth:750px; dialogHeight:350px')
    window.open("../../Admin/detalle_horasextras.php?empleado_codigo_seleccionado=" + empleado_codigo ,"Horas_Extras","toolbar=0, menubar=0, status=1, location=0, hotkeys=0, scrollbars=1, resizable=1 width=750, height=350, center=yes")
}
function cmdSaldoActual(empleado_codigo){

	window.showModalDialog('../../../endirecto/app/controller/saldo_actual_frame.php?empleado_codigo='+empleado_codigo,'Saldo_actual_horas','dialogWidth:950px; dialogHeight:500px')
}
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="183" height="188"></iframe>
</div>
<form name='frm' id='frm' method='post' action='<?php echo $_SERVER['PHP_SELF'] ?>' >
<CENTER class="FormHeaderFont">Consulta Auditor</CENTER>
<br />
<table class='DataTD' align='center'  width='95%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='5' >Auditar Empleado
		</td>
	</tr>
	<tr>
		<td width="20%">&nbsp;</td>
        <td  width="20%" align="right">
           <input type="radio" name="sel" id="sel_dni" value="on_dni" <?php echo $_POST['sel'] == 'on_dni'?'checked="checked"':'' ?>>
        </td>
        <td width="20%">
			DNI/Cedula:	<input class='Input' name='empleado_dni' id='empleado_dni' type='text' style='width=90px' maxlength='12' value='<?php echo $empleado_dni ?>' >
		</td>
		<td width="20%">&nbsp;&nbsp;Fecha&nbsp;
			<input type='text' class='input' id='fecha' name='fecha' size='11' value='<?php echo $fecha?>' readOnly >
			<img  src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' id="calendario" alt="Seleccionar Fecha">
		</td>
		<td width="20%">&nbsp;</td>
	</tr>
	<tr>
		<td width="20%">&nbsp;</td>
        <td  width="20%" align="right">
        <input type="radio" name="sel" id="sel_nombre" value="on_nombre" <?php echo $_POST['sel'] == 'on_nombre'?'checked="checked"':'' ?>>
        </td>
        <td width="25%">
		Empleado: <input class='Input' name='empleado_nombre' id='empleado_nombre' type='text' size="40"  maxlength='25' value='<?php echo $empleado_nombre ?>' >
		</td>
		<td width="20%">&nbsp;&nbsp;&nbsp;&nbsp;<input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar' class='Button' onclick="return Buscar();" style='width:100px' title='Mostrar datos ahora.'>
		</td>
		<td width="20%" align="right">
		<input type='button' id='cmd4' onClick="self.location.href='../menu.php'" value='Cerrar' class='Button' >
		</td>


	</tr>

</table>
<br>
<?php
if (isset($_POST['hddbuscar'])){
	if($_POST["hddbuscar"]=='OK'){
		$e->empleado_dni=$_POST["empleado_dni"];
		$e->empleado_nombre=$_POST["empleado_nombre"];
		$e->asistencia_fecha=$_POST["fecha"];
		$cadena=$e->Reporte_Auditor();
		echo $cadena;
	}
}
?>
<br>
<br>
<input type="hidden" id="hddbuscar" name="hddbuscar" value="">
<input type="hidden" id="hddcodigos" name="hddcodigos" value="">
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>

<script type="text/javascript" src="../../views/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="../../views/js/jquery-ui-1.10.4.custom.min.js"></script>


<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar-setup.js"></script>


<?php
if (isset($_POST['sel'])) {
	if ($_POST['sel'] == 'on_dni') {?>
		<script type="text/javascript">
		document.getElementById('empleado_nombre').disabled = true;
		document.getElementById('empleado_dni').focus();

		 </script>
	<?php 
	}if ($_POST['sel'] == 'on_nombre') {
		?>

		<script type="text/javascript">
		document.getElementById('empleado_dni').disabled = true;
		document.getElementById('empleado_nombre').focus();

		 </script>
	<?php 
	}
}else{ ?>
<script type="text/javascript">
	document.getElementById('empleado_nombre').disabled = true;
	document.getElementById('sel_dni').checked = true;
	document.getElementById('empleado_dni').focus();
</script>

<?php } ?>

<script type="text/javascript">

Calendar.setup({
    inputField     :    "fecha",
    ifFormat       :    "%d/%m/%Y",
    showsTime      :    false,
    button         :    "calendario",
    singleClick    :    true,
    step           :    1
});




	$('input[name=sel]').click(function() {
	   if($('#sel_dni').is(':checked')) { $('#empleado_nombre').val(''); $('#empleado_nombre').prop("disabled", true); $('#empleado_dni').prop("disabled", false);  }
	   if($('#sel_nombre').is(':checked')) { $('#empleado_dni').val(''); $('#empleado_dni').prop("disabled", true); $('#empleado_nombre').prop("disabled", false);  }
	});


	$("#empleado_nombre").autocomplete({
      source: "busca_empleado.php",
      minLength: 2
    });

</script>

</body>
</html>
