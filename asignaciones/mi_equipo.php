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
require_once("../includes/clsCA_Asignaciones.php");
require_once("../includes/clsCA_Asignacion_Empleados.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/MyGrillaEasyUI.php");
require_once("../includes/clsCA_Turnos_Empleado.php");
require_once("../../Includes/clsEmpleado_Servicio.php");
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

$a = new ca_asignaciones();
$a->MyUrl = db_host();
$a->MyUser= db_user();
$a->MyPwd = db_pass();
$a->MyDBName= db_name();

$ae = new ca_asignacion_empleados();
$ae->MyUrl = db_host();
$ae->MyUser= db_user();
$ae->MyPwd = db_pass();
$ae->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$es = new Empleado_Servicio();
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


/*
echo "***";

echo "<pre>";
print_r($u);
echo "</pre>";


echo "***";
*/


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
	if ($_POST['hddaccion']=='DEL'){ //quitar de grupo
		$asignacion_codigo = $_POST['asignacion_codigo'];
        $ae->responsable_codigo = $id;
		$ae->asignacion_codigo = $asignacion_codigo ;
		$rpta= $ae->Quitar_empleado_grupo();
		if ($rpta!='OK') echo "<br><b>" . $rpta . "</b>";
	}

	if ($_POST['hddaccion']=='AGR'){ //-- Asignar/Transferir Unid. de Servicio
		$es->Usuario_Responsable= $id;
		$es->Cod_Campana = $_POST['servicio_codigo'];
  
		$arr = split(',',$_POST['hddcodigos']);
		// print_r($arr);
			for($j=0; $j<sizeof($arr); $j++){
				$es->Empleado_Codigo= $arr[$j];
				$rpta= $es->Registrar();
				if ($rpta!='OK') echo "<br><b>" . $arr[$j] . ' - ' . $rpta . "</b>";
			}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Designar Grupo</title>
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
function cmdVersap_onclick(empleado_id, te_semana, te_anio, te_fecha_inicio, te_fecha_fin){
    //var rpta=Registro();
    //if (rpta != '' ) {
        //var arr = rpta.split("_");
        
		// window.open("../gestionturnos/programacion_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana + "&te_anio=" + te_anio + "&te_fecha_inicio=" + te_fecha_inicio + "&te_fecha_fin=" + te_fecha_fin,'VerSAP','width:600px,height:320px,toolbar=no,location=no,status=no,menubar=no');
	//}

	Ext_Dialogo.genera_Dialog(800,350,'Programacion Empleado',"../gestionturnos/programacion_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana + "&te_anio=" + te_anio + "&te_fecha_inicio=" + te_fecha_inicio + "&te_fecha_fin=" + te_fecha_fin, 'div_programa',100,50);


}

function cmdProgramacion_turno(){
    //alert('x');
	CenterWindow("ver_programacionturno.php" ,"Reporte",1000,600,"yes","center");        
	// Ext_Dialogo.genera_Dialog(1000,500,'Historico',"ver_programacionturno.php", 'modal_ver_programacion',20,20);


}

function cambiar_turno(){
	var codigos='';
	if (document.frm.turno_codigo.value==0){
		alert('Seleccione Turno');
		return false;
	}
	codigos=PooGrilla.SeleccionMultiple()
	if (codigos=='') return false;
	if (confirm('Seguro de cambiar turno a personal seleccionado')==false) return false;
	document.frm.hddaccion.value='CBO';
	document.frm.hddcodigos.value=codigos;
	document.frm.submit();
}
function cmdOtros(valor){
  switch(valor){
    case '1': spanma.style.display='block';
	          spanoa.style.display='none';
			break;
	case '2': spanma.style.display='none';
	          spanoa.style.display='block';
			  break;
  }
}
function reasignar(){
	var codigos='';
	codigos=PooGrilla.SeleccionMultiple()
	if (codigos=='') return false;
	//alert(document.frm.responsable_codigo.value);
	if(frm.rdo1.checked){
		if (frm.responsable_codigo_area.value=='0'){
			alert("Seleccione supervisor!!");
			frm.responsable_codigo_area.focus();
			return false;
		}
	}

    if(frm.rdo2.checked){
		if (frm.responsable_codigo_otros.value=='0'){
			alert("Seleccione supervisor!!");
			frm.responsable_codigo_otros.focus();
			return false;
		}
	}

	if (confirm('Seguro de reasignar a personal seleccionado')==false) return false;
	document.frm.hddaccion.value='RSR';
	document.frm.hddcodigos.value=codigos;
	document.frm.submit();
}

function transferir(){
	var codigos='';
	if (document.frm.servicio_codigo.value==0){
		alert('Seleccione Unidad de Servicio a reasignar');
		document.frm.servicio_codigo.focus();
		return false;
	}
	codigos=PooGrilla.SeleccionMultiple()
	
	if (codigos=='') return false;
	if (confirm('Confirme Asignar Unidad de Servicio a empleados seleccionados')==false) return false;
	document.frm.hddaccion.value='AGR';
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

function cmdVerHis_onclick(empleado_id, te_semana, te_anio){
    //alert('hi');	

	Ext_Dialogo.genera_Dialog(800,350,'Programacion Empleado',"../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio, 'div_histo',110,100);

}


</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<CENTER class="FormHeaderFont">Mi Grupo</CENTER>
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
	  
      <!--
		<td align="right">Unidad de Servicio del Area</td>
		<td>
		<?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			$ssql = "SELECT cod_campana, convert(varchar, cod_campana) + ' - ' + exp_nombrecorto + '(' + exp_codigo + ')' FROM v_campanas ";
			$ssql.= " WHERE coordinacion_codigo=" . $area . " and exp_Activo = 1 ";
			$ssql.= " Order by 2";
                        
			$combo->query = $ssql;
			$combo->name = "servicio_codigo";
			$combo->value = $servicio_codigo."";
			$combo->more = "class=select style='width:450px'";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
		</td>
		<td align=center width='120px'>
			<input class="buttons" type="button" id="cmdTransferir" onClick="transferir()" value="Asignar U.S."  style="width:80px" title='Asignar/Transferir Unidad de Servicio'>
		</td>-->	
		<td align="right">
        	<INPUT class="buttons" type='button' value='Ver Programacion' id='cmdProgramacion' name='cmdProgramacion'  LANGUAGE=javascript onclick='return cmdProgramacion_turno()' style='width:120px;'>
		</td>
      </tr>
	  
      <!--
      <tr>
         <td class='CA_FieldCaptionTD' align="center" colspan=3>
		  Responsables&nbsp;
	   </td>
   </tr>
   <tr>
      <td class="CA_DataTD" align=center colspan=3>
        <input type="radio" name="rdo" id="rdo1" value="1"  onclick='cmdOtros(this.value)' checked>Mi area
		<input type="radio" name="rdo" id="rdo2" value="2"  onclick='cmdOtros(this.value)'>Otra area
  	<td>
   </tr>

   <tr>
  	<td class='DataTD' colspan=3>
	    <span id="spanma" style="position:relative; DISPLAY:block">
		<table border="0" class="sinborde" align="center">
		 <tr>
			<td class="CA_DataTD" >
				<?php
				$ssql=  "select responsable_codigo, Empleado ";
				$ssql.=  " from vca_empleado_responsables ";
				$ssql.=  " where asignacion_activo=1 and Estado_Codigo=1 and Area_Codigo=" . $area . " and responsable_codigo<> " . $_SESSION["empleado_codigo"];
				$ssql.=  " Order by 2 ";

				$combo->query = $ssql;
				$combo->name = "responsable_codigo_area";
				$combo->value = $responsable_codigo."";
				$combo->more = "class=select style='width:350px'";
				//$rpta = $combo->Construir();
				//echo $rpta;
			    ?>
			</td>
			<td align=center width='120px'>
				<input class=button type="button" id="cmdVer" onClick="reasignar()" value="Reasignar"  style="width:80px" title='Reasignar empleado a otro supervisor'>
			</td>
		  </tr>
		  </table>
		  </span>
		   <span id="spanoa" style="position:relative; DISPLAY:none">
		   <table border="0" class="sinborde" align="center">
		   <tr>
			<td class="CA_DataTD" >
				<?php
				$ssql=  "select responsable_codigo, Empleado ";
				$ssql.=  " from vca_empleado_responsables ";
				$ssql.=  " where asignacion_activo=1 and Estado_Codigo=1 and Area_Codigo<>" . $area . " and responsable_codigo<> " . $_SESSION["empleado_codigo"];
				$ssql.=  " Order by 2 ";

				$combo->query = $ssql;
				$combo->name = "responsable_codigo_otros";
				$combo->value = $responsable_codigo."";
				$combo->more = "class=select style='width:350px'";
				//$rpta = $combo->Construir();
				//echo $rpta;
		     ?>
			</td>
			<td align=center width='120px'>
				<input class=button type="button" id="cmdVer" onClick="reasignar()" value="Reasignar"  style="width:80px" title='Reasignar empleado a otro supervisor'>
			</td>
		   </tr>
		 </table>
		</span>
	</td>
  </tr>
  -->
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
$arrAlias[7] = "CS";
$arrAlias[8] = "Ver_Pro";
// Arreglo de los Campos de la consulta
$arrCampos[0] = "e.Empleado_Codigo";
$arrCampos[1] = "e.empleado";
$arrCampos[2] = "e.Empleado_Dni"; 
$arrCampos[3] =	"e.Area_Descripcion";
$arrCampos[4] =	"e.Cargo_descripcion";
$arrCampos[5] = "e.Horario_descripcion";
$arrCampos[6] = "isnull(e.turno_descripcion,'')";
$arrCampos[7] = "CASE WHEN (select tc_codigo_sap from vCA_Turnos_EmpleadoTH where empleado_codigo=e.Empleado_Codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ) IS NULL  THEN  'S/C' ELSE (select tc_codigo_sap from vCA_Turnos_EmpleadoTH where empleado_codigo=e.Empleado_Codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ) END ";
$arrCampos[8] = "CASE WHEN (select tc_codigo_sap from vCA_Turnos_EmpleadoTH where empleado_codigo=e.Empleado_Codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ) IS NULL  THEN  '' ELSE '<img onclick=cmdVersap_onclick('+cast(e.Empleado_Codigo as varchar)+',". $te_semana .",". $te_anio .",'''+ isnull((select convert(varchar(10),te_fecha_inicio, 103) from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'')+''','''+isnull((select convert(varchar(10),te_fecha_fin, 103) from vCA_Turnos_EmpleadoTH where empleado_codigo=e.empleado_codigo and te_semana=".$te_semana." and te_anio=".$te_anio." ),'')+''') src=''../../Images/asistencia/inline011.gif''  border=0 style=cursor:hand onclick=cmdVersap_onclick(this.id) title=Programacion_Semanal />' END ";
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
</form>
</body>
</html>