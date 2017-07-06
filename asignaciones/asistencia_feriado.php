<?php header("Expires: 0"); ?>
<?php
$turno_codigo="";
$servicio_codigo="";
$responsable_codigo="";
$tipo_area_codigo="1";
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Empleados.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/MyGrillaEasyUI.php");
require_once("../includes/clsCA_Turnos_Empleado.php");
require_once("../../Includes/clsEmpleado_Asistencia_Feriado.php");
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				/*
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				echo "***";

																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				echo "<pre>";
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				print_r($_SESSION);
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				echo "</pre>";


																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				echo "***";
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																				*/

$o = new ca_empleados();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$es = new Empleado_Asistencia_Feriado();
$es->MyUrl = db_host();
$es->MyUser= db_user();
$es->MyPwd = db_pass();
$es->MyDBName= db_name();

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();
$rpta = $e->Query_Numero_Semana();

if ($rpta=='OK'){
	$te_semana= $e->te_semana;
	$te_anio=$e->te_anio;
}


$id=$_SESSION["empleado_codigo"];
$responsable_codigo=0;
// $te_semana= date('W');
// $te_anio=date('Y');

$u->empleado_codigo = $_SESSION["empleado_codigo"];
$r = $u->Identificar();
$nombre_usuario  	= $u->empleado_nombre;
$area      			= $u->area_codigo;
$area_descripcion 	= $u->area_nombre;
$jefe 				= $u->empleado_jefe; // responsable area
$fecha     			= $u->fecha_actual;
$tipo_area_codigo   = $u->tipo_area_codigo;

$tipo_codigo = 1;

if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='CBO'){ //Cambiar turno
		$arr = split(',',$_POST['hddcodigos']);
		$narr = sizeof($arr);
		$o->turno_codigo = $_POST['turno_codigo'];
		for ($i=0;$i<$narr;$i++){
			$o->empleado_codigo = $arr[$i];
			$o->empleado_codigo_reg = $id;
			$rpta= $o->modificar_turno_ver();
			if ($rpta!='OK') echo "<br><b>" . $rpta . "</b>";
		}
	}
	if ($_POST['hddaccion']=='RSR'){ //Cambiar responsable_codigo;
	    if($_POST['rdo']==1) $responsable_codigo=$_POST['responsable_codigo_area'];
	    if($_POST['rdo']==2) $responsable_codigo=$_POST['responsable_codigo_otros'];

		$arr = split(',',$_POST['hddcodigos']);
		$ae->responsable_codigo=$responsable_codigo;
		$ae->empleado_codigo_asigna=$id;

			for($j=0; $j<sizeof($arr); $j++){
				$ae->empleado_codigo=$arr[$j];
				//--desactivar en otros grupos
				$rptad = $ae->Desactivar_asignacion();
				if ($rptad=='OK'){
					$rptaa = $ae->Addnew();
					if ($rptaa!='OK'){
						 $rpta = "Error al asignar empleados.";
					 }else{
						$rpta= "OK";
					 }
				}
				if ($rpta!='OK') echo "<br><b>" . $rpta . "</b>";
			 }
	}


	if ($_POST['hddaccion']=='AUT'){ //-- Asignar/Transferir Unid. de Servicio
		$es->Usuario_Responsable= $id;
		$es->tipo_codigo = $_POST['Tipo_Codigo'];
		$arr_feriado = explode('-', $_POST['Feriado_Codigo']);
		$es->anio_codigo = $arr_feriado[0];
		$es->feriado_codigo = $arr_feriado[1];

		$arr = split(',',$_POST['hddcodigos']);
		// print_r($arr);
			for($j=0; $j<sizeof($arr); $j++){
				$es->Empleado_Codigo= $arr[$j];
				$rpta= $es->AutorizarAsistencia();
				// echo $rpta;die();
				if ($rpta!='OK') echo "<br><b>" . $arr[$j] . ' - ' . $rpta . "</b><br>";
				else echo '<b>'.$arr[$j].'</b> Se registro correctamente <br>';
			}
	}
}
?>	
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asistencia en Feriado</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<?php  require_once('../includes/librerias_easyui.php');?>

</head>
<script language="JavaScript">
var browser=navigator.appName;
function sel_supervisores(){
var codigos = '';
if(browser =='Microsoft Internet Explorer'){
	myInclusive=document.frm.elements('lstsupervisores');
}
	  for (var i=0; i< myInclusive.length; i++){
		var valor = myInclusive.options[i].value;
		var arr=valor.split('_');
		if (codigos == ''){
			codigos = arr[0];
		}else{
			codigos += ',' + arr[0];
		}
	  }

   if(codigos =='') {
    	alert('Seleccione al menos un Responsable!!');
	return false;
	}else{
	 document.frm.hddcodigossup.value=codigos;
	 return true;
	}
}
function cmdVerHistoFeriadoTrabajado(empleado_id){
    //var rpta=Registro();
    //if (rpta != '' ) {
        //var arr = rpta.split("_");
        
		// window.open("../gestionturnos/programacion_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana + "&te_anio=" + te_anio + "&te_fecha_inicio=" + te_fecha_inicio + "&te_fecha_fin=" + te_fecha_fin,'VerSAP','width:600px,height:320px,toolbar=no,location=no,status=no,menubar=no');
	//}

	Ext_Dialogo.genera_Dialog(800,350,'Lista de feriados trabajados',"../asignaciones/lista_asistencia_feriado.php?t=detalle&empleado_id=" + empleado_id , 'div_programa',300,50);


}
function importar(){
	var session_id = document.frm.session_id.value;
	Ext_Dialogo.genera_Dialog(800,350,'Importar Asistencia en Feriados',"importar_asistencia_feriado.php?responsable="+session_id, 'div_programa',300,50);


}
function consultar(){
	var tipo_feriado = document.frm.Tipo_Codigo.value;
	var feriado_codigo = document.frm.Feriado_Codigo.value;
	var session_id = document.frm.session_id.value;
	Ext_Dialogo.genera_Dialog(800,350,'Lista de feriados trabajados',"lista_asistencia_feriado.php?t=lista&tf=" + tipo_feriado+"&feriado="+feriado_codigo+"&responsable="+session_id, 'div_programa',300,50);


}
function autorizar(){
	var codigos='';
	if (document.frm.Tipo_Codigo.value==0){
		alert('Seleccione Tipo de Feriado');
		document.frm.Tipo_Codigo.focus();
		return false;
	}
	if (document.frm.Feriado_Codigo.value==0){
		alert('Seleccione Feriado');
		document.frm.Feriado_Codigo.focus();
		return false;
	}

	codigos=PooGrilla.SeleccionMultiple()
	if (codigos=='') return false;
	if (confirm('Confirme Autorizacion de asistencia en Feriado a los empleados seleccionados')==false) return false;
	document.frm.hddaccion.value='AUT';
	document.frm.hddcodigos.value=codigos;
	document.frm.submit();
}

function Quitar(codigo){
	if (confirm('Seguro de quitar al empleado del grupo')==false) return false;
	document.frm.hddaccion.value='DEL';
	document.frm.asignacion_codigo.value=codigo;
	document.frm.submit();
}



function Finalizar(){
    Ext_Dialogo.close_Dialog('div_programa');
}

function showformato(){
	Ext_Dialogo.genera_Dialog(800,350,'Formato de carga Asistencia feriados',"formato.txt", 'div_showformat',300,50);


}



</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<CENTER class="FormHeaderFont">Autorizar Asistencia en Feriado</CENTER>
<table width="80%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
    <td class='CA_FormHeaderFont' align=center colspan=2>Supervisor : <?php echo $nombre_usuario?></td>
 </tr>
 <tr>
    <td align="center" colspan='2'><b><?php echo $area_descripcion ?></b></td>
  </tr>
</table>
<br>
<table class='FormTable' align=center  width='100%' border='0' cellpadding='1' cellspacing='0'>

      <tr>
        <td align="right">Tipo Feriado</td>
		<td>
		<?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			$ssql = "select Tipo_Codigo, Tipo_Descripcion from CA_Tipo_Feriados ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "Tipo_Codigo";
			$combo->value = $tipo_codigo."";
			$combo->more = "class=select";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
		</td>
		<td align="right">Fecha Feriado</td>
		<td id="cboferiado">
			<?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			$ssql = "select CAST(Anio as varchar)+'-'+CAST(Feriado_Codigo as varchar), ";
			$ssql .= "'['+(select pais_nombre from paises p where p.pais_codigo = f.Pais_Codigo)+'] '+Feriado_Descripcion+' ('+CONVERT(varchar(10),Fecha_Feriado,103)+')' ";
			$ssql .= "from CA_Feriados f where Feriado_Activo = 1 and Tipo_Feriado = ".$tipo_codigo;
			$ssql .= " and Fecha_Feriado >= CONVERT(DATETIME, CONVERT(VARCHAR(8),GETDATE(),112),103)";

			$ssql .= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "Feriado_Codigo";
			$combo->value = $feriado_codigo."";
			$combo->more = "class=select ";
			$rpta = $combo->Construir();
			echo $rpta;
		  	?>
		</td>

		<td align=center width=''>
			<input class="buttons" type="button" id="cmdTransferir" onClick="autorizar()" value="Autorizar asistencia" title='Autorizar'>
		</td>
		<td align=center width=''>
			<input class="buttons" type="button" id="cmdConsultar" onClick="consultar()" value="Consultar Asist. Feriado" title='Autorizar'>
		</td>
		<td align=center width=''>
			<input class="buttons" type="button" id="cmdTransferir" onClick="importar()" value="Importar Asist. Feriado" title='Autorizar'>
		</td>
		
      </tr>
    
</table>
<br>

<?php
$body="";
$npag = 1;
$orden = "e.empleado";
$buscam = "";
$torder="ASC";
$hddini="";
$hddfin="";
$npag = 1;

$objr = new MyGrilla;
$objr->setDriver_Coneccion(db_name());
$objr->setUrl_Coneccion(db_host());
$objr->setUser(db_user());
$objr->setPwd(db_pass());
$objr->setOrder($orden);
$objr->setFindm($buscam);
$objr->setNoSeleccionable(true);
$objr->setFont("color=#000000");
$objr->setFormatoBto("class=button");
$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
$objr->setFormaCabecera(" class=ColumnTD ");
$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
$objr->setTOrder($torder);

$from  = "vdatostotal e";
$from .= " left join ca_asignacion_empleados cae on e.empleado_codigo=cae.empleado_codigo and cae.asignacion_activo=1 where cae.Responsable_Codigo = ".$id;
$objr->setFrom($from);
$where= "";
$objr->setWhere($where);
$objr->setSize(20);
$objr->setUrl($_SERVER["PHP_SELF"]);
$objr->setPage($npag);
$objr->setMultipleSeleccion(true);
	// Arreglo de Alias de los Campos de la consulta
$arrAlias[0] = "root";
$arrAlias[1] = "Empleado";
$arrAlias[2] = "DNI";
$arrAlias[3] = "Area";
$arrAlias[4] = "Cargo";
$arrAlias[5] = "Horas";
$arrAlias[6] = "Turno";
$arrAlias[7] = "Nro_Feriados";
$arrAlias[8] = "Ver_Feriados";
// Arreglo de los Campos de la consulta
$arrCampos[0] = "e.Empleado_Codigo";
$arrCampos[1] = "e.empleado";
$arrCampos[2] = "e.Empleado_Dni"; 
$arrCampos[3] =	"e.Area_Descripcion";
$arrCampos[4] =	"e.Cargo_descripcion";
$arrCampos[5] = "e.Horario_descripcion";
$arrCampos[6] = "isnull(e.turno_descripcion,'')";
$arrCampos[7] = "(select COUNT(eaf_codigo) from Empleado_Asistencia_Feriado where Empleado_Codigo = e.Empleado_Codigo)";
$arrCampos[8] = "'<img onclick=''cmdVerHistoFeriadoTrabajado('+CAST(e.Empleado_Codigo as VARCHAR)+')'' src=''../../Images/asistencia/inline011.gif''  border=0 style=cursor:hand />'";
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	$body = $objr->Construir();  //ejecutar
	echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo '<br>';
	echo Menu("../menu.php");

?>

<br>
<br>
<div id="div_programa"></div>
<input type="hidden" id="hddaccion" name="hddaccion">
<input type="hidden" id="hddcodigos" name="hddcodigos">
<input type="hidden" id="asignacion_codigo" name="asignacion_codigo" value="">
<input type="hidden" id="session_id" name="session_id" value="<?php echo $id ?>">
</form>

<script type="text/javascript">
$(document).ready(function() {
	$('#Tipo_Codigo').change(function(){
		$.ajax({
			url: 'carga_combo.php',
			data: { tipo: $(this).val()}
		}).done(function(rs){
			$('#cboferiado').html(rs);
		})
	})
});
</script>

</body>
</html>		