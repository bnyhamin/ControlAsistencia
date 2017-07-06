<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../includes/clsCA_Turnos_Combinacion.php"); 
require_once("../../Includes/MyCombo.php");
set_time_limit(30000);
$empleado_id="";
$te_semana="";
$tc_codigo="";
$te_anio="";
$hddfini="";
$hddffin="";
//$empleado_codigo_registro="13625";
$empleado_codigo_registro=$_SESSION["empleado_codigo"];
$cargo_codigo="";
$area_codigo="";
$modalidad_codigo="";
$responsable_codigo=$empleado_codigo_registro;
$nombres="";
$csemanal="";
$hddbuscar="";
$todos="0";
$turno_dia1=0;
$turno_dia2=0;
$turno_dia3=0;
$turno_dia4=0;
$turno_dia5=0;
$turno_dia6=0;
$turno_dia7=0;
$iturno_dia1="";
$iturno_dia2="";
$iturno_dia3="";
$iturno_dia4="";
$iturno_dia5="";
$iturno_dia6="";
$iturno_dia7="";
$fturno_dia1="";
$fturno_dia2="";
$fturno_dia3="";
$fturno_dia4="";
$fturno_dia5="";
$fturno_dia6="";
$fturno_dia7="";
$total_horas="";
$h_refrigerio="";
$h_efectivas="";
$agrupacion_id="";
$empleado_dni="";
$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();



$o = new ca_turnos_empleado();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

//if (isset($_SESSION["empleado_codigo"])) $empleado_codigo_registro=$_SESSION["empleado_codigo"];
if (isset($_POST["tc_codigo"])) $tc_codigo = $_POST["tc_codigo"];
if (isset($_POST["te_semana"])) $te_semana = $_POST["te_semana"];
if (isset($_POST["te_anio"])) $te_anio = $_POST["te_anio"];
if (isset($_POST["hddfini"])) $hddfini = $_POST["hddfini"];
if (isset($_POST["hddffin"])) $hddffin = $_POST["hddffin"];
if (isset($_POST["cargo_codigo"])) $cargo_codigo = $_POST["cargo_codigo"];
if (isset($_POST["modalidad_codigo"])) $modalidad_codigo = $_POST["modalidad_codigo"];
if (isset($_POST["area_codigo"])) $area_codigo = $_POST["area_codigo"];
if (isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if (isset($_POST["nombres"])) $nombres = $_POST["nombres"];
if (isset($_POST["csemanal"])) $csemanal = $_POST["csemanal"];
if (isset($_POST["turno_dia1"])) $turno_dia1 = $_POST["turno_dia1"];
if (isset($_POST["turno_dia2"])) $turno_dia2 = $_POST["turno_dia2"];
if (isset($_POST["turno_dia3"])) $turno_dia3 = $_POST["turno_dia3"];
if (isset($_POST["turno_dia4"])) $turno_dia4 = $_POST["turno_dia4"];
if (isset($_POST["turno_dia5"])) $turno_dia5 = $_POST["turno_dia5"];
if (isset($_POST["turno_dia6"])) $turno_dia6 = $_POST["turno_dia6"];
if (isset($_POST["turno_dia7"])) $turno_dia7 = $_POST["turno_dia7"];
if (isset($_POST["iturno_dia1"])) $iturno_dia1 = $_POST["iturno_dia1"];
if (isset($_POST["iturno_dia2"])) $iturno_dia2 = $_POST["iturno_dia2"];
if (isset($_POST["iturno_dia3"])) $iturno_dia3 = $_POST["iturno_dia3"];
if (isset($_POST["iturno_dia4"])) $iturno_dia4 = $_POST["iturno_dia4"];
if (isset($_POST["iturno_dia5"])) $iturno_dia5 = $_POST["iturno_dia5"];
if (isset($_POST["iturno_dia6"])) $iturno_dia6 = $_POST["iturno_dia6"];
if (isset($_POST["iturno_dia7"])) $iturno_dia7 = $_POST["iturno_dia7"];
if (isset($_POST["fturno_dia1"])) $fturno_dia1 = $_POST["fturno_dia1"];
if (isset($_POST["fturno_dia2"])) $fturno_dia2 = $_POST["fturno_dia2"];
if (isset($_POST["fturno_dia3"])) $fturno_dia3 = $_POST["fturno_dia3"];
if (isset($_POST["fturno_dia4"])) $fturno_dia4 = $_POST["fturno_dia4"];
if (isset($_POST["fturno_dia5"])) $fturno_dia5 = $_POST["fturno_dia5"];
if (isset($_POST["fturno_dia6"])) $fturno_dia6 = $_POST["fturno_dia6"];
if (isset($_POST["fturno_dia7"])) $fturno_dia7 = $_POST["fturno_dia7"];
if (isset($_POST["total_horas"])) $total_horas = $_POST["total_horas"];
if (isset($_POST["agrupacion_id"])) $agrupacion_id = $_POST["agrupacion_id"];
if (isset($_POST["empleado_dni"])) $empleado_dni = $_POST["empleado_dni"];
if (isset($_POST["h_refrigerio"])) $h_refrigerio = $_POST["h_refrigerio"];
if (isset($_POST["h_efectivas"])) $h_efectivas = $_POST["h_efectivas"];

if (isset($_POST['hddaccion'])){
	if ($_POST['hddaccion']=='SVE'){
		$arr = split(',',$_POST["hddcodigos"]);
		for($i=0; $i<sizeof($arr); $i++){
			$o->empleado_codigo=$arr[$i];
			$o->te_semana=$te_semana;
			$o->te_anio=$te_anio;
			$o->tc_codigo=$tc_codigo;
			$o->te_fecha_inicio=$_POST['hddfini'];
			$o->te_fecha_fin=$_POST['hddffin'];
			$o->turno_Dia1=$turno_dia1;
			$o->turno_Dia2=$turno_dia2;
			$o->turno_Dia3=$turno_dia3;
			$o->turno_Dia4=$turno_dia4;
			$o->turno_Dia5=$turno_dia5;
			$o->turno_Dia6=$turno_dia6;
			$o->turno_Dia7=$turno_dia7;
			$o->empleado_codigo_registro = $empleado_codigo_registro; //$_SESSION["empleado_codigo"];
			if (!$o->ExisteProgramacion()) {
				$o->Query_Empleado_Nombres();
				$rpta = "Primero debe programar la semana actual por archivo de texto DNI: ".$o->empleado_dni.", ";
				echo $rpta;
			}else{
				$rptaa = $o->AddnewUpdate_Semana();
				//echo $sql;
				if ($rptaa!='OK'){
					$o->Query_Empleado_Nombres();
					$rpta = "No coincide horas efectivas DNI: ".$o->empleado_dni.", ";
					echo $rpta;
			 	}else{
					$rpta= "OK";
				}
			}
		}
		if($rpta=='OK'){
		?>
		<script language='javascript'>
		   alert('Asignacion De Turnos Satisfactorio!!');
		</script>
		<?php
		}
	}
	if ($_POST['hddaccion']=='DLT'){
		$o->empleado_codigo = $_POST["hddcodigos"];
		$o->tc_codigo=$tc_codigo;
		$o->te_semana=$te_semana;
		$o->te_anio=$te_anio;
		$rpta= $o->Quitar_Turno();
		if ($rpta!='OK') echo "<br><b>" . $rpta . "</b>";
	}
}

$esadmin="NO";
$o->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin = $o->Query_Rol_Admin();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asignar Turnos</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<!--<script language="JavaScript" src="../no_teclas.js"></script>-->
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/modalfiles/modal.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/modalfiles/modal.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language="JavaScript">
var mensaje='';

function asignar(){
	/*if('<?php echo $esadmin;?>'!='OK'){
		alert('No esta Autorizado Asignar o Reasignar Turnos, Comuniquese con el Administrador del Sistema');
		return false;
	}*/
	var codigos='';
	posicionarcombo();
	if (document.frm.tc_codigo.value==0){
		alert('Seleccione Combinacion Semanal');
		document.frm.tc_codigo.focus();
		return false;
	}
	document.frm.todos.checked=false;
	var fec = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	var fechas = fec.split("-");
	var anio = fechas[1].split("/")
	document.frm.te_anio.value = anio[2];
	for(i=0; i< document.frm.length; i++ ){
		if (frm.item(i).type=='checkbox'){
			 if ( frm.item(i).checked ){
				if (codigos==''){
					codigos=frm.item(i).value;
				}else{
					codigos+= ',' + frm.item(i).value;
				}
			}
		}
	}
	if (codigos==''){
		alert('Seleccione Algun Registros de Empleados');
		return false
	}
	if (confirm('Asignar Turno a Empleados Seleccionados?')==false) return false;
	document.frm.hddfini.value=fechas[1];
	document.frm.hddffin.value=fechas[2];
	document.frm.hddbuscar.value='OK';
	document.frm.hddaccion.value='SVE';
	document.frm.hddcodigos.value= codigos;
	document.frm.submit();
}

function Quitar(codigo){
	if('<?php echo $esadmin;?>'!='OK'){
		alert('No esta Autorizado Eliminar Turno Programado, Comuniquese con el Administrador del Sistema');
		return false;
	}
	if(confirm('Seguro de Quitar el Turno del Empleado')==false) return false;
	document.frm.hddbuscar.value='OK';
	document.frm.hddaccion.value='DLT';
	document.frm.hddcodigos.value= codigo;
	document.frm.submit();
}

function buscarcs(search){
	CenterWindow("../gestionturnos/listacs.php?tc_codigo_sap=" + search ,"ModalChild",990,600,"yes","center");
	//window.open("../gestionturnos/listacs.php?tc_codigo_sap=" + search ,"av");
	//window.open.showmodal("../gestionturnos/listacs.php?tc_codigo_sap=" + search ,"av");
	return true;
}

function filtroCS(codigo, descripcion, turno_dia1, turno_dia2, turno_dia3, turno_dia4, turno_dia5, turno_dia6, turno_dia7, titurno_dia1,tfturno_dia1, titurno_dia2,tfturno_dia2, titurno_dia3,tfturno_dia3, titurno_dia4,tfturno_dia4, titurno_dia5,tfturno_dia5, titurno_dia6,tfturno_dia6, titurno_dia7,tfturno_dia7, ttotal_horas, hrefrigerio, hefectivas){
	document.frm.tc_codigo.value=codigo;
	document.frm.csemanal.value=descripcion;
	document.frm.turno_dia1.value=turno_dia1;
	document.frm.turno_dia2.value=turno_dia2;
	document.frm.turno_dia3.value=turno_dia3;
	document.frm.turno_dia4.value=turno_dia4;
	document.frm.turno_dia5.value=turno_dia5;
	document.frm.turno_dia6.value=turno_dia6;
	document.frm.turno_dia7.value=turno_dia7;
	iturno_dia1.innerHTML = titurno_dia1; fturno_dia1.innerHTML = tfturno_dia1;
	iturno_dia2.innerHTML = titurno_dia2; fturno_dia2.innerHTML = tfturno_dia2;
	iturno_dia3.innerHTML = titurno_dia3; fturno_dia3.innerHTML = tfturno_dia3;
	iturno_dia4.innerHTML = titurno_dia4; fturno_dia4.innerHTML = tfturno_dia4;
	iturno_dia5.innerHTML = titurno_dia5; fturno_dia5.innerHTML = tfturno_dia5;
	iturno_dia6.innerHTML = titurno_dia6; fturno_dia6.innerHTML = tfturno_dia6;
	iturno_dia7.innerHTML = titurno_dia7; fturno_dia7.innerHTML = tfturno_dia7;
	total_horas.innerHTML = ttotal_horas;
	h_refrigerio.innerHTML = hrefrigerio;
	h_efectivas.innerHTML = hefectivas;
	document.frm.iturno_dia1.value=titurno_dia1;
	document.frm.iturno_dia2.value=titurno_dia2;
	document.frm.iturno_dia3.value=titurno_dia3;
	document.frm.iturno_dia4.value=titurno_dia4;
	document.frm.iturno_dia5.value=titurno_dia5;
	document.frm.iturno_dia6.value=titurno_dia6;
	document.frm.iturno_dia7.value=titurno_dia7;
	document.frm.fturno_dia1.value=tfturno_dia1;
	document.frm.fturno_dia2.value=tfturno_dia2;
	document.frm.fturno_dia3.value=tfturno_dia3;
	document.frm.fturno_dia4.value=tfturno_dia4;
	document.frm.fturno_dia5.value=tfturno_dia5;
	document.frm.fturno_dia6.value=tfturno_dia6;
	document.frm.fturno_dia7.value=tfturno_dia7;
	document.frm.total_horas.value=ttotal_horas;
	document.frm.h_refrigerio.value=hrefrigerio;
	document.frm.h_efectivas.value=hefectivas;
}

function Buscar(){
	posicionarcombo();
	var nombres=document.frm.nombres.value;
	var modalidad_codigo=document.frm.modalidad_codigo.value;
	var cargo_codigo=document.frm.cargo_codigo.value;
	var area_codigo=document.frm.area_codigo.value;
	var responsable_codigo=document.frm.responsable_codigo.value;
	var fec = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	var fechas = fec.split("-");
	var anio = fechas[1].split("/")
	document.frm.te_anio.value = anio[2];
	document.frm.hddfini.value=fechas[1];
	document.frm.hddffin.value=fechas[2];
	document.frm.hddbuscar.value="OK";
	document.frm.action +="?nombres=" + nombres + "&modalidad_codigo=" + modalidad_codigo + "&cargo=" + cargo_codigo + "&area_codigo=" + area_codigo + "&responsable_codigo=" + responsable_codigo;
	document.frm.submit();
}

function cambiarcheck(){
	if(document.frm.todos.checked){
		checkear_todos_empleados(true);
	}else{
		checkear_todos_empleados(false);
	}
}

function checkear_todos_empleados(flag){
	var r=document.getElementsByTagName('input');
	for (var i=0; i< r.length; i++) {
		var o=r[i];
		var oo=o.id;
		if(oo.substring(0,3)=='chk'){
			if(o.checked){
				o.checked=flag;
			}else{
				o.checked=flag;
			}
		}    
	}
}

function posicionarcombo(){
	if (document.getElementById('te_semana').selectedIndex<=0) document.getElementById('te_semana').selectedIndex=1;
}

function cmdVersap_onclick(empleado_id, te_semana, te_anio, te_fecha_inicio, te_fecha_fin){
    //var rpta=Registro();
    //if (rpta != '' ) {
        //var arr = rpta.split("_");
        
		window.showModalDialog("../gestionturnos/programacion_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana + "&te_anio=" + te_anio + "&te_fecha_inicio=" + te_fecha_inicio + "&te_fecha_fin=" + te_fecha_fin,'VerSAP','dialogWidth:600px; dialogHeight:320px');
	//}
}

function cmdHorario_onclick(codigo){
	//alert(codigo);
	windowv=dhtmlmodal.open('Modificar', 'iframe', "horario_empleado_upd.php?empleado_id="+codigo, 'Actualizar Horario', 'width=400px,height=300px,top=10,left=10,center=0,resize=0,scrolling=1');
}

</script>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post' onSubmit='javascript:return ok();'>
<CENTER class="FormHeaderFont">Asignar Turnos</CENTER>
<table  width='99%'>
<tr>
<td>
<table class='DataTD' align='center'  width='95%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
		<td  class='FieldCaptionTD'  align='center' colspan='2' >Filtro de Empleados
		</td>
	</tr>
	<tr>
        <td align="right" width="80px">Por Nombre&nbsp;</td>
		<td ><input class='Input' name='nombres' id='nombres' type='text' style='width: 200px' value='<?php echo $nombres ?>' >
		<input class='Input' name='agrupacion_id' id='agrupacion_id' type='hidden' style='width: 60px' maxlength='10' value='<?php echo $agrupacion_id ?>' >
		Dni:
		<input class='Input' name='empleado_dni' id='empleado_dni' type='text' style='width: 60px' maxlength='10' value='<?php echo $empleado_dni ?>' >
        <td>
	</tr>
	<tr>
		<td align='right'>Modalidad</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			$ssql = "select modalidad_codigo, modalidad_descripcion from vdatostotal ";
			$ssql.= " where area_codigo in (select area_codigo from ca_controller where empleado_codigo=" . $empleado_codigo_registro . " and activo=1  ";
			$ssql.= " union select area_codigo from vdatostotal where empleado_codigo=".$empleado_codigo_registro." ) ";
			$ssql.= " group by modalidad_codigo, modalidad_descripcion ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "modalidad_codigo";
			$combo->value = $modalidad_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_todos();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
		<td align='right'>Cargo</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			$ssql = "select cargo_codigo, cargo_descripcion from vdatostotal ";
			$ssql.= " where area_codigo in (select area_codigo from ca_controller where empleado_codigo=" . $empleado_codigo_registro . " and activo=1 ";
			$ssql.= " union select area_codigo from vdatostotal where empleado_codigo=".$empleado_codigo_registro." ) ";
			$ssql.= " group by cargo_codigo, cargo_descripcion ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "cargo_codigo";
			$combo->value = $cargo_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_todos();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
		<td align='right'>Area</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());

			$ssql = "select area_codigo, area_descripcion from vdatostotal ";
			$ssql.= " where area_codigo in (select area_codigo from ca_controller where empleado_codigo=" . $empleado_codigo_registro . " and activo=1 ";
			$ssql.= " union select area_codigo from vdatostotal where empleado_codigo=".$empleado_codigo_registro." ) ";
			$ssql.= " group by area_codigo, area_descripcion ";
			$ssql.= " Order by 2";
			$combo->query = $ssql;
			$combo->name = "area_codigo";
			$combo->value = $area_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_todos();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
		<td align='right'>Responsable</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			//$is_admin = $e->esAdministrador($empleado_codigo_registro);
			
			// if($is_admin){	
				/*$ssql = " select cae.responsable_codigo,v.empleado ";
				$ssql.= " from ca_asignacion_empleados cae join vdatostotal v on ";
				$ssql.= " cae.responsable_codigo=v.empleado_codigo ";
				$ssql.= " where cae.asignacion_activo=1 and cae.responsable_codigo in ";
				$ssql.= " ( ";
				$ssql.= " 	select empleado_codigo from vdatostotal where area_codigo in ";
				$ssql.= " 	( ";				
				$ssql.= " 		select area_codigo from ca_controller where activo=1 ";
				$ssql.= " 		and empleado_codigo=" . $empleado_codigo_registro;
				$ssql.= " 		union ";
				$ssql.= " 		select area_codigo from vdatostotal where empleado_codigo=" . $empleado_codigo_registro;
				$ssql.= " 	) ";
				$ssql.= " ) ";
				$ssql.= " group by cae.responsable_codigo,v.empleado ";
				$ssql.= " order by 2";*/
				
				$ssql = " select cae.responsable_codigo,v.empleado ";
				$ssql.= " from ca_asignacion_empleados cae join vdatostotal v on ";
				$ssql.= " cae.responsable_codigo=v.empleado_codigo ";
				$ssql.= " where cae.asignacion_activo=1 and cae.responsable_codigo in ";
				$ssql.= " ( ";
				$ssql.= "              select empleado_codigo from vdatostotal where area_codigo in ";
				$ssql.= "              ( ";                                                         
				$ssql.= "                              select area_codigo from ca_controller where ";
				$ssql.= "                              empleado_codigo=" . $empleado_codigo_registro . " and activo=1 ";
				$ssql.= "                              union ";
				$ssql.= "                              select area_codigo from Empleado_Area where empleado_codigo=" . $empleado_codigo_registro . " and Empleado_Area_Activo=1";
				$ssql.= "              ) ";
				$ssql.= " ) ";
				$ssql.= " group by cae.responsable_codigo,v.empleado ";
				$ssql.= " order by 2";

				
				
			// }
			// else{
			// 	$ssql = " select cae.responsable_codigo,v.empleado";
			// 	$ssql .= " from ca_asignaciones cae join vdatostotal v on cae.responsable_codigo=v.empleado_codigo";
			// 	$ssql .= " where cae.asignacion_activo=1 and v.empleado_codigo=" . $empleado_codigo_registro;
			// 	$ssql .= " group by cae.responsable_codigo,v.empleado order by 2";

			// }
			$combo->query = $ssql;
			$combo->name = "responsable_codigo";
			$combo->value = $responsable_codigo."";
			$combo->more = "class=select style='width:300px'";
			$rpta = $combo->Construir_sin_selecc();
			echo $rpta;
		  ?>
        </td>
	</tr>
	<tr>
        <td align="right" width="80px">Semana&nbsp;</td>
		<td><?php
			$combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
//			$ssql = "set datefirst 1 select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3";
//Para cambiar a este tipo se necesita arreglar el envio de fechas y año			
/*			$ssql = " set datefirst 1 select DATEPART(ww, getdate()) as codigo, ";
			$ssql.= " cast(DATEPART(ww, getdate()) as varchar(2)) + ' - ' + ";
			$ssql.= " convert(varchar(10),(getdate())-(DATEPART(w, getdate())-1),103) + ' - ' + ";
			$ssql.= " convert(varchar(10),(getdate())+(7-DATEPART(w, getdate())),103) as descripcion ";
			$ssql.= " union ";*/
			//$ssql = " set datefirst 1 ";
			$ssql = " select DATEPART(ww, getdate()+7) as codigo, ";
			$ssql.= " cast(DATEPART(ww, getdate()+7) as varchar(2)) + ' - ' + ";
			$ssql.= " convert(varchar(10),(getdate()+7)-(DATEPART(w, getdate()+7)-1),103) + '-' + ";
			$ssql.= " convert(varchar(10),(getdate()+7)+(7-DATEPART(w, getdate()+7)),103) as descripcion, ";
			$ssql.= " getdate()+7 as dia union ";
			$ssql.= " select DATEPART(ww, getdate()+7*2) as codigo, ";
			$ssql.= " cast(DATEPART(ww, getdate()+7*2) as varchar(2)) + ' - ' + ";
			$ssql.= " convert(varchar(10),(getdate()+7*2)-(DATEPART(w, getdate()+7*2)-1),103) + '-' + ";
			$ssql.= " convert(varchar(10),(getdate()+7*2)+(7-DATEPART(w, getdate()+7*2)),103) as descripcion, ";
			$ssql.= " getdate()+7*2 as dia union ";
			$ssql.= " select DATEPART(ww, getdate()+7*3) as codigo, ";
			$ssql.= " cast(DATEPART(ww, getdate()+7*3) as varchar(2)) + ' - ' + ";
			$ssql.= " convert(varchar(10),(getdate()+7*3)-(DATEPART(w, getdate()+7*3)-1),103) + '-' + ";
			$ssql.= " convert(varchar(10),(getdate()+7*3)+(7-DATEPART(w, getdate()+7*3)),103) as descripcion, ";
			$ssql.= " getdate()+7*3 as dia order by 3 ";
			//echo $ssql;
			$combo->query = $ssql;
			$combo->name = "te_semana";
			$combo->value = $te_semana."";
			$combo->more = "class=select style='width:300px'";
			$combo->datefirst= 'set datefirst 1';
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
		  <script>
		  posicionarcombo();
		  </script>
        </td>
	</tr>
	<tr>
		<td colspan=2 align=right>
			<input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar'  class='Button' onclick="return Buscar();" style='width:50px'>
		</td>
	</tr>
</table>
<br>
</td>
<td width="55%">
<table class='DataTD' align=center  width='100%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
      <td class='CA_FieldCaptionTD' align='center' colspan='2'>Combinacion Semanal de Turnos&nbsp;</td>
	</tr>
	<tr>
        <td align="right" width="80px">CSemanal&nbsp;</td>
		<td >
		<input class='Input' name='csemanal' id='csemanal' type='text' value='<?php echo $csemanal?>' size='51'>
		<img src="../images/buscaroff.gif" alt="buscar combinacion semanal" onclick="return buscarcs(document.frm.csemanal.value)" style="cursor:hand" >
		<input class='Input' name='tc_codigo' id='tc_codigo' type='hidden' value='<?php echo $tc_codigo?>' >
		<input class='Input' name='turno_dia1' id='turno_dia1' type='hidden' value='<?php echo $turno_dia1?>' >
		<input class='Input' name='turno_dia2' id='turno_dia2' type='hidden' value='<?php echo $turno_dia2?>' >
		<input class='Input' name='turno_dia3' id='turno_dia3' type='hidden' value='<?php echo $turno_dia3?>' >
		<input class='Input' name='turno_dia4' id='turno_dia4' type='hidden' value='<?php echo $turno_dia4?>' >
		<input class='Input' name='turno_dia5' id='turno_dia5' type='hidden' value='<?php echo $turno_dia5?>' >
		<input class='Input' name='turno_dia6' id='turno_dia6' type='hidden' value='<?php echo $turno_dia6?>' >
		<input class='Input' name='turno_dia7' id='turno_dia7' type='hidden' value='<?php echo $turno_dia7?>' >
		<input class='Input' name='te_anio' id='te_anio' type='hidden' value='<?php echo $te_anio?>' >
        </td>
	</tr>
	<tr>
	<td align="center" colspan='2' align='center' id='descripcion'>
	
	<table  class='FormTable' width='95%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<tr>
		<td class='FieldCaptionTD' align='center' width='8%'>&nbsp;</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Lun</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Mar</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Mie</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Jue</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Vie</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Sab</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Dom</td>
		<td class='FieldCaptionTD' align='center' width='10%'>THr</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Ref</td>
		<td class='FieldCaptionTD' align='center' width='10%'>Efe</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right'>De:&nbsp;</td>
		<td class='DataTD' align='center' id='iturno_dia1'><?php echo $iturno_dia1?></td>
		<td class='DataTD' align='center' id='iturno_dia2'><?php echo $iturno_dia2?></td>
		<td class='DataTD' align='center' id='iturno_dia3'><?php echo $iturno_dia3?></td>
		<td class='DataTD' align='center' id='iturno_dia4'><?php echo $iturno_dia4?></td>
		<td class='DataTD' align='center' id='iturno_dia5'><?php echo $iturno_dia5?></td>
		<td class='DataTD' align='center' id='iturno_dia6'><?php echo $iturno_dia6?></td>
		<td class='DataTD' align='center' id='iturno_dia7'><?php echo $iturno_dia7?></td>
		<td rowspan="2" class='DataTD' align='center' id='total_horas'><?php echo $total_horas?></td>
		<td rowspan="2" class='DataTD' align='center' id='h_refrigerio'><?php echo $h_refrigerio?></td>
		<td rowspan="2" class='DataTD' align='center' id='h_efectivas'><?php echo $h_efectivas?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD' align='right'>A:&nbsp;</td>
		<td class='DataTD' align='center' id='fturno_dia1'><?php echo $fturno_dia1?></td>
		<td class='DataTD' align='center' id='fturno_dia2'><?php echo $fturno_dia2?></td>
		<td class='DataTD' align='center' id='fturno_dia3'><?php echo $fturno_dia3?></td>
		<td class='DataTD' align='center' id='fturno_dia4'><?php echo $fturno_dia4?></td>
		<td class='DataTD' align='center' id='fturno_dia5'><?php echo $fturno_dia5?></td>
		<td class='DataTD' align='center' id='fturno_dia6'><?php echo $fturno_dia6?></td>
		<td class='DataTD' align='center' id='fturno_dia7'><?php echo $fturno_dia7?></td>
	</tr>
	</table>
	
	</td>
	</tr>
	<tr>
		<td align=center class="CA_DataTD" ></td>
	</tr>
	<tr align="center" >
    	<td class="DataTD" colspan='2'>
	 		<input class='button' type='button' id='cmdAgrupar' onClick='asignar()' value='Asignar'  style='width:80px'>
	 		<input class='button' type='button' id='cmdCerrar' onClick="self.location.href='main_turnos_empleado.php'" value='Cerrar' style='width:80px'>
		</td>
	<tr>
</table>
</td>
</tr>
</table>
<br>
<table class='FormTable' align='center' border='0' cellPadding='0' cellSpacing='0' style='width:98%'>
	<tr align='center' >
    	<td class='ColumnTD' style='width:10px'>Nro.</td>
    	<td class='ColumnTD' ><Input class='Input' type='checkbox' name='todos' id='todos' title='Marcar Todos Los Empleados' value='1' <?php if ($todos=="1") echo "Checked"; ?> onclick='cambiarcheck();'></td>
    	<td class='ColumnTD'>DNI</td>
    	<td class='ColumnTD'>Nombres</td>
        <td class='ColumnTD'>Area</td>
        <td class='ColumnTD'>Cargo</td>
        <td class='ColumnTD' width='45px'>Horas</td>
        <td class='ColumnTD' width='40px' title="Combinacion Semanal">CS</td>
        <td class='ColumnTD' width='40px'>Ver Pro</td>
<!--		<td class='ColumnTD' width='40px'>Quitar</td> -->
	</tr>
	<?php
	if (isset($_POST['hddbuscar'])){
		if($_POST["hddbuscar"]=='OK'){
			$nombres=$_POST["nombres"];
			$modalidad_codigo=$_POST["modalidad_codigo"];
			$cargo_codigo=$_POST["cargo_codigo"];
			$area_codigo=$_POST["area_codigo"];
			$te_semana=$_POST["te_semana"];
			$te_anio=$_POST["te_anio"];
			$hddfini = $_POST["hddfini"];
			$hddffin = $_POST["hddffin"];
			$e->empleado_codigo_registro=$_SESSION['empleado_codigo'];
                        //echo "1";
			$cadena=$e->Traer_Empleados_Fechas($modalidad_codigo,$cargo_codigo,$area_codigo,$nombres,$hddfini,$hddffin,$responsable_codigo,$esadmin,$empleado_dni,$te_semana,$te_anio);
			echo $cadena;
		}
	}
	if (isset($_GET['hddbuscar'])){
		if($_GET["hddbuscar"]=='OK'){
                    //echo "2";
			$rpta = $e->Query_Numero_Semana_siguiente();
                        //echo $rpta;
			if ($rpta=='OK'){
				$te_semana= $e->te_semana;
				$te_anio=$e->te_anio;
				$hddfini = $e->te_fecha_inicio;
				$hddffin = $e->te_fecha_fin;
				$e->empleado_codigo_registro=$_SESSION['empleado_codigo'];
				$cadena=$e->Traer_Empleados_Fechas($modalidad_codigo,$cargo_codigo,$area_codigo,$nombres,$hddfini,$hddffin, $responsable_codigo,$esadmin,$empleado_dni,$te_semana,$te_anio);
				echo $cadena;
			}
		}
	}
	?>
</table>
<br>
<br>
<table width='80%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td align='center'>
		<input type='button' id='cmd4' onClick="self.location.href='main_turnos_empleado.php'" value='Cerrar' class='Button' style='width:80px'>
	</td>
  </tr>
</table>
<input type="hidden" id="hddbuscar" name="hddbuscar" value="">
<input type="hidden" id="hddcodigos" name="hddcodigos" value="">
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<input type="hidden" id="hddfini" name="hddfini" value="">
<input type="hidden" id="hddffin" name="hddffin" value="">
<input type="hidden" id="iturno_dia1" name="iturno_dia1" value="<?php echo $iturno_dia1 ?>">
<input type="hidden" id="iturno_dia2" name="iturno_dia2" value="<?php echo $iturno_dia2 ?>">
<input type="hidden" id="iturno_dia3" name="iturno_dia3" value="<?php echo $iturno_dia3 ?>">
<input type="hidden" id="iturno_dia4" name="iturno_dia4" value="<?php echo $iturno_dia4 ?>">
<input type="hidden" id="iturno_dia5" name="iturno_dia5" value="<?php echo $iturno_dia5 ?>">
<input type="hidden" id="iturno_dia6" name="iturno_dia6" value="<?php echo $iturno_dia6 ?>">
<input type="hidden" id="iturno_dia7" name="iturno_dia7" value="<?php echo $iturno_dia7 ?>">
<input type="hidden" id="fturno_dia1" name="fturno_dia1" value="<?php echo $fturno_dia1 ?>">
<input type="hidden" id="fturno_dia2" name="fturno_dia2" value="<?php echo $fturno_dia2 ?>">
<input type="hidden" id="fturno_dia3" name="fturno_dia3" value="<?php echo $fturno_dia3 ?>">
<input type="hidden" id="fturno_dia4" name="fturno_dia4" value="<?php echo $fturno_dia4 ?>">
<input type="hidden" id="fturno_dia5" name="fturno_dia5" value="<?php echo $fturno_dia5 ?>">
<input type="hidden" id="fturno_dia6" name="fturno_dia6" value="<?php echo $fturno_dia6 ?>">
<input type="hidden" id="fturno_dia7" name="fturno_dia7" value="<?php echo $fturno_dia7 ?>">
<input type="hidden" id="total_horas" name="total_horas" value="<?php echo $total_horas ?>">
<input type="hidden" id="h_refrigerio" name="h_refrigerio" value="<?php echo $h_refrigerio ?>">
<input type="hidden" id="h_efectivas" name="h_efectivas" value="<?php echo $h_efectivas ?>">
</form>
</body>
</html>
