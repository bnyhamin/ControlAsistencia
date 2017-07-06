<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Validacion.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Empleado_Rol.php");

$emp=0;
$o = new ca_validacion();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$id=$_SESSION["empleado_codigo"];
$o->responsable_codigo=$id;

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$u->empleado_codigo = $id;
$r = $u->Identificar();
$nombre_usuario = $u->empleado_nombre;
$area = $u->area_codigo;
$area_descripcion = $u->area_nombre;
$jefe = $u->empleado_jefe; // responsable area
$fecha = $u->fecha_actual;


if (isset($_GET["f"])){
    $fecha = $_GET["f"];
    if (isset($_GET["e"])) $emp = $_GET["e"];
?>
    <script language="javascript">
        var fecha="<?php echo $fecha ?>";
        window.parent.frames[2].location="val_right.php?fecha=" + fecha + "&empleado_cod=<?php echo $emp ?>"; 
    </script>
<?php
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Listado de Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" src="../mouse_keyright.js"></script>
<script language="JavaScript">
function Registrar_diario_onclick(){
	//var valor = window.showModalDialog("CA_DiarioGestion.php", "Diario Gestion","dialogWidth:600px; dialogHeight:300px");
	CenterWindow("CA_DiarioGestion.php","ModalChild",600,400,"yes","center");
}
function Registrar_posicion_onclick(){
	//var valor = window.showModalDialog("CA_DiarioGestion.php", "Diario Gestion","dialogWidth:600px; dialogHeight:300px");
	CenterWindow("PosicionesDia.php","ModalChild",600,400,"yes","center");
}

function cmdAprobar_onclick(){
var fecha="<?php echo $fecha ?>";
var responsable_codigo="<?php echo $id ?>";
var area_codigo="<?php echo $area ?>";
CenterWindow("../asistencias/eventos_dia_empleado.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area_codigo=" + area_codigo + "&incidencia_codigo=0","ModalChild",990,700,"yes","center");
}

function cmdVerPermisosPlataformas_onclick(){
 
	 var arr='';
	 var empleadoCodigo = '';
	 for(i=0; i< document.frm.length; i++ ){
	  if (frm.item(i).type=='radio'){
	   if (frm.item(i).checked){

	   	   arr=(frm.item(i).value).split('_');
	   	   empleadoCodigo = arr[0];
	   }
	  }
	 }
	
	if (empleadoCodigo==''){
  		
  		alert('Seleccione Algun Registros de Empleados');
  		return false
 	}

     CenterWindow("../asignaciones/bio_plataforma_empleado_listado.php?empleadoCodigo="+empleadoCodigo,"ModalChild",850,350,"yes","center");

}



function cmdJustificar_onclick(){
var fecha="<?php echo $fecha ?>";
var responsable_codigo="<?php echo $id ?>";
var area_codigo="<?php echo $area ?>";
CenterWindow("../asistencias/Justificacion_Asistencia.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area_codigo=" + area_codigo,"ModalChild",990,700,"yes","center");
}

function cmdRegistrosBatch_onclick(){
var fecha="<?php echo $fecha ?>";
var responsable_codigo="<?php echo $id ?>";
var area_codigo="<?php echo $area ?>";
//CenterWindow("registro_incidencias.php?asistencia=" + arr[0] + "&responsable=" + responsable_codigo + "&empleado=" + empleado_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");
CenterWindow("registro_incidencias_batch.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area=" + area_codigo,"Batch",600,650,"yes","center");
}

function cmdExtensionTurno_onclick(){
	var fecha="<?php echo $fecha ?>";
	var responsable_codigo="<?php echo $id ?>";
	var area_codigo="<?php echo $area ?>";
	CenterWindow("extension_turno.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area=" + area_codigo,"Batch",700,650,"yes","center");
}

function escribirFecha() {
  campoDeRetorno.value = dia + "/" + mes + "/" + ano;
}

function asistencias(valor){
arr=valor.split('_');
var fecha="<?php echo $fecha ?>";
  window.parent.frames[2].location="val_right.php?empleado_cod=" + arr[0] + "&fecha=" + fecha + "&tipo=" + arr[2]; 
}
function actualizar(){
document.frm.action +="?f=<?php echo $fecha ?>";
document.frm.submit();
}

function nada(){
return;
}

function cmdCambiarTurno_onclick(){
 var arr='';
 for(i=0; i< document.frm.length; i++ ){
  if (frm.item(i).type=='radio'){
   if (frm.item(i).checked){
    arr=(frm.item(i).value).split('_');
   }
  }
 }
 if (arr==''){
  alert('Seleccione Algun Registros de Empleados');
  return false
 }
 if (arr[3]=='1'){
  alert('No se puede modificar turno porque ya se registro el ingreso');
  return false
 }
 var fecha="<?php echo $fecha ?>";
 CenterWindow("../gestionturnos/turnos_empleado_dia_validacion.php?empleado_id=" + arr[0] + "&te_semana=" + 0 + "&te_anio=" + 0 + "&tc_codigo=" + 0 + "&te_fecha_inicio=" + fecha + "&te_fecha_fin=" + fecha, "ModalChild",450,250,"yes","center");
}

</script>
</head>
<body class="PageBODY">
<script type='text/javascript'>
function Go(){return}
</script>
<script type='text/javascript' src='menu_responsable.js'></script>
<script type='text/javascript' src='menu_com_left.js'></script>
<noscript>Your browser does not support script</noscript>
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<br><br>
        <center><font color=#333399 STYLE='font-size:12.5px'><b>Mi Grupo&nbsp;</b></font><img border='0' src='../images/invite.gif' title='Mi Grupo'></Center>
		<table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
		  <tr>
			<td class='ColumnTD' align=center width="5%">Sel</td>
			<td class="ColumnTD" align=center width="10%">Código</td>
			<td class='ColumnTD' align=center width="48%">Nombre</td>
			<td class='ColumnTD' align=center width="7%">Tx.</td>
			<td class='ColumnTD' align=center width="30%">Incidencias</td>
		  </tr>
		  <?php
		  $o->fecha=$fecha; 
                  //echo "zzz".$emp;
		   $rpta=$o->Listar_mi_grupo($emp);

		  echo $rpta;
		  ?>
		</table>
  		<br>
		<br>
		 <center ><font color=#333399 STYLE='font-size:12.5px'><b>Otros&nbsp;</b></font><img border='0' src='../images/block.gif'  title='Otro Grupo'></Center>
		<table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
		   <tr>
			<td class="ColumnTD" align="center" width="5%">Sel</td>
			<td class="ColumnTD" align="center" width="10%">Código</td>
			<td class="ColumnTD" align="center" width="48%">Nombre</td>
			<td class="ColumnTD" align="center" width="7%">Tx.</td>
			<td class="ColumnTD" align="center" width="30%">Incidencias</td>
		  </tr>
		  <?php
		  
		  $rpta=$o->Listar_otros($emp);
		  echo $rpta;
		  ?>
		</table>
		<br>
		<br>
		 <center ><font color=#333399 STYLE='font-size:12.5px'><b>Cesados&nbsp;</b></font><img border='0' src='../images/im-aim.png'  title='Otro Grupo'></Center>
		<table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
		   <tr>
			<td class='ColumnTD' align=center width="5%">Sel</td>
			<td class="ColumnTD" align=center width="10%">Código</td>
			<td class='ColumnTD' align=center width="85%">Nombre</td>
		  </tr>
		  <?php
		  
		  $rpta=$o->Listar_cesados($emp);
		  echo $rpta;
		  ?>
		</table>
<input type="button" style="width:0; heigth:0" value="ok" id="cmdx" name="cmdx" onClick="actualizar()">		
</form>
</body>
</html>