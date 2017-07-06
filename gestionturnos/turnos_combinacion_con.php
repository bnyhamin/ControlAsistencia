<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Combinacion.php"); 
require_once("../../Includes/MyCombo.php");

$tc_codigo="";
$tc_codigo_sap="";
$tipo_area_codigo="1";
$turno_hora_inicio="-1";
$turno_minuto_inicio="-1";
$turno_horas="0";
$turno_minutos="0";
$tc_activo="0";

$Dias="0";
$Dia1="0";
$Dia2="0";
$Dia3="0";
$Dia4="0";
$Dia5="0";
$Dia6="0";
$Dia7="0";
$turno_dia1="-1";
$cturno_dia1="0";$tturno_dia1="";
$cturno_dia2="0";$tturno_dia2="";
$cturno_dia3="0";$tturno_dia3="";
$cturno_dia4="0";$tturno_dia4="";
$cturno_dia5="0";$tturno_dia5="";
$cturno_dia6="0";$tturno_dia6="";
$cturno_dia7="0";$tturno_dia7="";
$lturno_dia1="";$dturno_dia1="";$nturno_dia1="";$eturno_dia1="";$sturno_dia1="";
$lturno_dia2="";$dturno_dia2="";$nturno_dia2="";$eturno_dia2="";$sturno_dia2="";
$lturno_dia3="";$dturno_dia3="";$nturno_dia3="";$eturno_dia3="";$sturno_dia3="";
$lturno_dia4="";$dturno_dia4="";$nturno_dia4="";$eturno_dia4="";$sturno_dia4="";
$lturno_dia5="";$dturno_dia5="";$nturno_dia5="";$eturno_dia5="";$sturno_dia5="";
$lturno_dia6="";$dturno_dia6="";$nturno_dia6="";$eturno_dia6="";$sturno_dia6="";
$lturno_dia7="";$dturno_dia7="";$nturno_dia7="";$eturno_dia7="";$sturno_dia7="";
$total_horas="";$total_minutos="";
$horas_refrigerio="";$minutos_refrigerio="";
$ttotal_horas="";$ttotal_minutos="";

$npag = isset($_GET["pagina"])?$_GET["pagina"]:"";
$buscam = isset($_GET["buscam"])?$_GET["buscam"]:"";
$orden = isset($_GET["orden"])?$_GET["orden"]:"";
$torder = isset($_GET["torder"])?$_GET["torder"]:"";
$mensaje = "";

if (isset($_POST["tc_codigo"])) $tc_codigo = $_POST["tc_codigo"];
if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];

if (isset($_POST["tc_codigo_sap"])) $tc_codigo_sap = $_POST["tc_codigo_sap"];
if (isset($_POST["turno_hora_inicio"])) $turno_hora_inicio = $_POST["turno_hora_inicio"];
if (isset($_POST["turno_minuto_inicio"])) $turno_minuto_inicio = $_POST["turno_minuto_inicio"];
if (isset($_POST["turno_horas"])) $turno_horas = $_POST["turno_horas"];
if (isset($_POST["turno_minutos"])) $turno_minutos = $_POST["turno_minutos"];
if (isset($_POST["tipo_area_codigo"])) $tipo_area_codigo= $_POST["tipo_area_codigo"];
if (isset($_POST["turno_dia1"])) $turno_dia1 = $_POST["turno_dia1"];
if (isset($_POST["cturno_dia1"])) $cturno_dia1 = $_POST["cturno_dia1"];
if (isset($_POST["cturno_dia2"])) $cturno_dia2 = $_POST["cturno_dia2"];
if (isset($_POST["cturno_dia3"])) $cturno_dia3 = $_POST["cturno_dia3"];
if (isset($_POST["cturno_dia4"])) $cturno_dia4 = $_POST["cturno_dia4"];
if (isset($_POST["cturno_dia5"])) $cturno_dia5 = $_POST["cturno_dia5"];
if (isset($_POST["cturno_dia6"])) $cturno_dia6 = $_POST["cturno_dia6"];
if (isset($_POST["cturno_dia7"])) $cturno_dia7 = $_POST["cturno_dia7"];

if (isset($_POST["tc_activo"])) $tc_activo = $_POST["tc_activo"];
if (isset($_POST["Dia1"])) $Dia1 = $_POST["Dia1"];
if (isset($_POST["Dia2"])) $Dia2 = $_POST["Dia2"];
if (isset($_POST["Dia3"])) $Dia3 = $_POST["Dia3"];
if (isset($_POST["Dia4"])) $Dia4 = $_POST["Dia4"];
if (isset($_POST["Dia5"])) $Dia5 = $_POST["Dia5"];
if (isset($_POST["Dia6"])) $Dia6 = $_POST["Dia6"];
if (isset($_POST["Dia7"])) $Dia7 = $_POST["Dia7"];

$o = new ca_turnos_combinacion();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='SVE'){
		if ($Dia1=="1") $cturno_dia1=$turno_dia1; 		
		if ($Dia2=="1") $cturno_dia2=$turno_dia1; 		
		if ($Dia3=="1") $cturno_dia3=$turno_dia1; 		
		if ($Dia4=="1") $cturno_dia4=$turno_dia1; 		
		if ($Dia5=="1") $cturno_dia5=$turno_dia1; 		
		if ($Dia6=="1") $cturno_dia6=$turno_dia1; 		
		if ($Dia7=="1") $cturno_dia7=$turno_dia1; 		
		$o->tc_codigo = $tc_codigo;
		$o->tc_codigo_sap = $tc_codigo_sap;
		$o->empleado_codigo=$empleado_codigo;
		$o->turno_Dia1 = $cturno_dia1;
		$o->turno_Dia2 = $cturno_dia2;
		$o->turno_Dia3 = $cturno_dia3;
		$o->turno_Dia4 = $cturno_dia4;
		$o->turno_Dia5 = $cturno_dia5;
		$o->turno_Dia6 = $cturno_dia6;
		$o->turno_Dia7 = $cturno_dia7;
		$o->tc_activo = $tc_activo;
		if ($tc_codigo==''){
			$mensaje = $o->Addnew();
			$tc_codigo = $o->tc_codigo;
		}else{
			$mensaje = $o->Update();
		}
		if($mensaje=='OK'){
		?>
		<script language='javascript'>
		</script>
		<?php
		}else{
			echo $mensaje;
		}
	}
}
if ($tc_codigo!=""){
	$o->tc_codigo = $tc_codigo;
	$mensaje = $o->Query_Detalle();
	$tc_codigo_sap = $o->tc_codigo_sap;
	$cturno_dia1 = $o->turno_Dia1;
	$cturno_dia2 = $o->turno_Dia2;
	$cturno_dia3 = $o->turno_Dia3;
	$cturno_dia4 = $o->turno_Dia4;
	$cturno_dia5 = $o->turno_Dia5;
	$cturno_dia6 = $o->turno_Dia6;
	$cturno_dia7 = $o->turno_Dia7;
	$tc_activo = $o->tc_activo;
	$tturno_dia1 = $o->tturno_Dia1;
	$tturno_dia2 = $o->tturno_Dia2;
	$tturno_dia3 = $o->tturno_Dia3;
	$tturno_dia4 = $o->tturno_Dia4;
	$tturno_dia5 = $o->tturno_Dia5;
	$tturno_dia6 = $o->tturno_Dia6;
	$tturno_dia7 = $o->tturno_Dia7;
	$lturno_dia1 = $o->lturno_Dia1;
	$lturno_dia2 = $o->lturno_Dia2;
	$lturno_dia3 = $o->lturno_Dia3;
	$lturno_dia4 = $o->lturno_Dia4;
	$lturno_dia5 = $o->lturno_Dia5;
	$lturno_dia6 = $o->lturno_Dia6;
	$lturno_dia7 = $o->lturno_Dia7;
	$dturno_dia1 = $o->dturno_Dia1;
	$dturno_dia2 = $o->dturno_Dia2;
	$dturno_dia3 = $o->dturno_Dia3;
	$dturno_dia4 = $o->dturno_Dia4;
	$dturno_dia5 = $o->dturno_Dia5;
	$dturno_dia6 = $o->dturno_Dia6;
	$dturno_dia7 = $o->dturno_Dia7;
	$eturno_dia1 = $o->eturno_Dia1;
	$eturno_dia2 = $o->eturno_Dia2;
	$eturno_dia3 = $o->eturno_Dia3;
	$eturno_dia4 = $o->eturno_Dia4;
	$eturno_dia5 = $o->eturno_Dia5;
	$eturno_dia6 = $o->eturno_Dia6;
	$eturno_dia7 = $o->eturno_Dia7;
	$nturno_dia1 = $o->nturno_Dia1;
	$nturno_dia2 = $o->nturno_Dia2;
	$nturno_dia3 = $o->nturno_Dia3;
	$nturno_dia4 = $o->nturno_Dia4;
	$nturno_dia5 = $o->nturno_Dia5;
	$nturno_dia6 = $o->nturno_Dia6;
	$nturno_dia7 = $o->nturno_Dia7;
	$total_horas = $o->total_horas;
	$total_minutos = $o->total_minutos;
	$horas_refrigerio = $o->horas_refrigerio;
	$minutos_refrigerio = $o->minutos_refrigerio;
	$sturno_dia1 = $o->sturno_Dia1;
	$sturno_dia2 = $o->sturno_Dia2;
	$sturno_dia3 = $o->sturno_Dia3;
	$sturno_dia4 = $o->sturno_Dia4;
	$sturno_dia5 = $o->sturno_Dia5;
	$sturno_dia6 = $o->sturno_Dia6;
	$sturno_dia7 = $o->sturno_Dia7;
	$ttotal_horas = $o->ttotal_horas;
	$ttotal_minutos = $o->ttotal_minutos;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Registro de Turno</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
</head>
<script language='javascript'>
var mensaje='';
var turno_dia1=0;
var turno_dia2=0;
var turno_dia3=0;
var turno_dia4=0;
var turno_dia5=0;
var turno_dia6=0;
var turno_dia7=0;

function Buscar(){
	if(document.frm.turno_hora_inicio.value==-1){
	  alert('Indique  valor');
	  document.frm.turno_hora_inicio.focus();
	  return false;
	}
	if(document.frm.turno_minuto_inicio.value==-1){
	  alert('Indique  valor');
	  document.frm.turno_minuto_inicio.focus();
	  return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.submit();
}

function Grabar(){
    if (validarCampo('frm','tc_codigo_sap')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	if (document.getElementById("Dia1").checked) turno_dia1=document.getElementById("turno_dia1").value;
	else turno_dia1=document.getElementById("cturno_dia1").value; 
	if (document.getElementById("Dia2").checked) turno_dia2=document.getElementById("turno_dia1").value;
	else turno_dia2=document.getElementById("cturno_dia2").value; 
	if (document.getElementById("Dia3").checked) turno_dia3=document.getElementById("turno_dia1").value;
	else turno_dia3=document.getElementById("cturno_dia3").value; 
	if (document.getElementById("Dia4").checked) turno_dia4=document.getElementById("turno_dia1").value;
	else turno_dia4=document.getElementById("cturno_dia4").value; 
	if (document.getElementById("Dia5").checked) turno_dia5=document.getElementById("turno_dia1").value;
	else turno_dia5=document.getElementById("cturno_dia5").value; 
	if (document.getElementById("Dia6").checked) turno_dia6=document.getElementById("turno_dia1").value;
	else turno_dia6=document.getElementById("cturno_dia6").value; 
	if (document.getElementById("Dia7").checked) turno_dia7=document.getElementById("turno_dia1").value;
	else turno_dia7=document.getElementById("cturno_dia7").value;
    frames['ifr_procesos'].location.href = "procesos.php?opcion=existe_tc&tc_codigo_sap="+document.getElementById("tc_codigo_sap").value+"&turno_dia1="+turno_dia1+"&turno_dia2="+turno_dia2+"&turno_dia3="+turno_dia3+"&turno_dia4="+turno_dia4+"&turno_dia5="+turno_dia5+"&turno_dia6="+turno_dia6+"&turno_dia7="+turno_dia7+"&tc_codigo="+document.getElementById("tc_codigo").value;
    
}

function aplicar(){
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	document.frm.submit();
}

function Finalizar(){
	window.close();
}

function manejarDiv(div){
  if (document.getElementById(div).style.display == 'block') document.getElementById(div).style.display='none';
  else document.getElementById(div).style.display='block';
}

function seleccionarDato(combo,dia){
	if (dia=='1'){
		if (document.getElementById(combo).length>1 && document.getElementById(combo).selectedIndex==0) document.getElementById(combo).selectedIndex=1;
	}else{
		document.getElementById(combo).selectedIndex=0;
	}
}

function Nuevo(){
	document.getElementById("tc_codigo").value = "";
	document.getElementById("tc_codigo_sap").value = "";
	document.getElementById("cturno_dia1").value = "0";
	document.getElementById("cturno_dia2").value = "0";
	document.getElementById("cturno_dia3").value = "0";
	document.getElementById("cturno_dia4").value = "0";
	document.getElementById("cturno_dia5").value = "0";
	document.getElementById("cturno_dia6").value = "0";
	document.getElementById("cturno_dia7").value = "0";
	tturno_dia1.innerHTML ='';nturno_dia1.innerHTML ='';lturno_dia1.innerHTML ='';dturno_dia1.innerHTML ='';
	tturno_dia2.innerHTML ='';nturno_dia2.innerHTML ='';lturno_dia2.innerHTML ='';dturno_dia2.innerHTML ='';
	tturno_dia3.innerHTML ='';nturno_dia3.innerHTML ='';lturno_dia3.innerHTML ='';dturno_dia3.innerHTML ='';
	tturno_dia4.innerHTML ='';nturno_dia4.innerHTML ='';lturno_dia4.innerHTML ='';dturno_dia4.innerHTML ='';
	tturno_dia5.innerHTML ='';nturno_dia5.innerHTML ='';lturno_dia5.innerHTML ='';dturno_dia5.innerHTML ='';
	tturno_dia6.innerHTML ='';nturno_dia6.innerHTML ='';lturno_dia6.innerHTML ='';dturno_dia6.innerHTML ='';
	tturno_dia7.innerHTML ='';nturno_dia7.innerHTML ='';lturno_dia7.innerHTML ='';dturno_dia7.innerHTML ='';
	eturno_dia1.innerHTML ='';sturno_dia1.innerHTML ='';
	eturno_dia2.innerHTML ='';sturno_dia2.innerHTML ='';
	eturno_dia3.innerHTML ='';sturno_dia3.innerHTML ='';
	eturno_dia4.innerHTML ='';sturno_dia4.innerHTML ='';
	eturno_dia5.innerHTML ='';sturno_dia5.innerHTML ='';
	eturno_dia6.innerHTML ='';sturno_dia6.innerHTML ='';
	eturno_dia7.innerHTML ='';sturno_dia7.innerHTML ='';
	total_horas.innerHTML ='';horas_refrigerio.innerHTML ='';
	document.getElementById("Dias").checked=false;
	cambiarcheck();
}

function cambiarcheck(){
	if (document.getElementById("Dias").checked){
		document.getElementById("Dia1").checked = true;
		document.getElementById("Dia2").checked = true;
		document.getElementById("Dia3").checked = true;
		document.getElementById("Dia4").checked = true;
		document.getElementById("Dia5").checked = true;
		document.getElementById("Dia6").checked = true;
		document.getElementById("Dia7").checked = true;
	}else{
		document.getElementById("Dia1").checked = false;
		document.getElementById("Dia2").checked = false;
		document.getElementById("Dia3").checked = false;
		document.getElementById("Dia4").checked = false;
		document.getElementById("Dia5").checked = false;
		document.getElementById("Dia6").checked = false;
		document.getElementById("Dia7").checked = false;
	}
}
</script>
<body Class='PageBODY'>
<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<?php
	$sql=" select turno_codigo as codigo,turno_descripcion +  ";
	$sql .=" ' Cod('+isnull(turno_id,'')+')'+ ";
	$sql .=" ' Ref('+isnull(cast(Turno_Refrigerio as varchar),'')+' Min)'+ ";
	$sql .=" ' Des1('+isnull(cast(Turno_Descanzo as varchar),'')+' Min)'+ ";
	$sql .=" ' Des2('+isnull(cast(Turno_Descanso2 as varchar),'')+' Min)' as descripcion from vCA_turnosTH ";
	$sql .=" where turno_id is not null and Tipo_Area_Codigo=" . $tipo_area_codigo . " and ";
	$sql .=" Turno_Hora_Inicio=" . $turno_hora_inicio . " and ";
	$sql .=" Turno_Minuto_Inicio=" . $turno_minuto_inicio;
	if ($turno_horas!="0" && $turno_minutos!="0"){
		$sql .=" and turno_horas>=". $turno_horas;
		$sql .=" and turno_minutos>=" . $turno_minutos ;
	}else{
		if ($turno_horas!="0" && $turno_minutos=="0"){
			$sql .=" and turno_horas>=". $turno_horas;
		}				
	}      
	$sql .=" order by 2 asc";
	$combo->query = $sql;
?>
<center class=FormHeaderFont>Combinacion de Turnos</center>
<form name="frm" id="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table  class='FormTable' width='85%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr style="display:none">
	<td class='FieldCaptionTD' align='center' colspan="3"><strong>FILTROS</strong>&nbsp;</td>
</tr>
<tr style="display:none">
	<td class='FieldCaptionTD' width='30%' align='right'>Tipo&nbsp;</td>
	<td class='DataTD' width='45%'>
		<input type="radio" name="tipo_area_codigo" id="tipo_operativo" value="1" <?php if ($tipo_area_codigo=="1") echo "Checked"; ?>>Operativo
		<input type="radio" name="tipo_area_codigo" id="tipo_administrativo" value="0" <?php if ($tipo_area_codigo=="0") echo "Checked"; ?>>Administrativo
	</td>
	<td rowspan="3" class='FieldCaptionTD' width='30%' valign="middle" align="center" ><input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar' class='Button' style='width:80px'  onclick="Buscar()"></td>
</tr>
<tr style="display:none">
	<td class='FieldCaptionTD' align='right'>Inicio&nbsp;</td>
	<td class='DataTD'>
		Horas&nbsp;<select  class='select' name='turno_hora_inicio' id='turno_hora_inicio' >
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
				 if ($h==$turno_hora_inicio) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
			     else 
				 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  class='select' name='turno_minuto_inicio' id='turno_minuto_inicio' >
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
		     //if ($m==0 || $m==30){
              if(strlen($m)<=1) $mm="0".$m;
		      if($m==$turno_minuto_inicio) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		     //}
		   }
		  ?>
		 </select> 
	</td>
	
</tr>
<tr style="display:none">
	<td class='FieldCaptionTD' align='right'>Cantidad Horas Dia&nbsp;</td>
	<td class='DataTD'>Horas
		<Input class='Input' type='text' name='turno_horas' id='turno_horas' maxlength='2' value='<?php echo $turno_horas ?>' style="width='35px'" onKeyPress='return esnumero()'>&nbsp;:&nbsp;Minutos&nbsp;
		<Input class='Input' type='text' name='turno_minutos' id='turno_minutos' maxlength='2' value='<?php echo $turno_minutos ?>' style="width='35px'" onKeyPress='return esnumero()'>
	</td>
	
</tr>
<tr style="display:none">
	<td class='FieldCaptionTD' align='right'>Turnos Encontrados&nbsp;</td>
	<td class='DataTD' colspan="2">
		<?php
		$combo->name = "turno_dia1"; 
		$combo->value = $turno_dia1 ."";
		$combo->more = "class='Select' style='width:420px' ";
		//$rpta = $combo->Construir();
		$rpta = $combo->Construir_Opcion("---Descanso---");
		echo $rpta;
		?>
		<script>
		seleccionarDato('turno_dia1','1');
		</script>
	</td>
</tr>
<tr>
	<td colspan=3  class='FieldCaptionTD'>&nbsp;</td>
</tr>
</table>
<table width='85%' cellspacing='0' cellpadding='0' align='center' border='1'>
<tr style="display:none">
	<td colspan='2' class='DataTD' type='text' align='center' ondblclick='manejarDiv("div_asignar")'><b>ASIGNAR&nbsp;</b></td>
</tr>
</table>
<div style='display:block' id='div_asignar'>
<table  class='FormTable' width='85%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' colspan="2" align='right'>Código&nbsp;</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='tc_codigo' id='tc_codigo' value="<?php echo $tc_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2">Cód Comb&nbsp;</td>
	<td class='DataTD'>
		<Input  class='Input' type='text' name='tc_codigo_sap' id='tc_codigo_sap' value="<?php echo $tc_codigo_sap?>" maxlength='15' style='width:120px;' >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' width='8%'>
	<Input class='Input' type='checkbox' name='Dias' id='Dias' title='Marcar Para Todos Los Dias' value='1' <?php if ($Dias=="1") echo "Checked"; ?> onclick='cambiarcheck();'><br>Todos
	</td>
	<td class='FieldCaptionTD' align='center' width='15%'>Dias</td>
	<td class='FieldCaptionTD' align='center' width='35%'>Turnos</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Cod.Turno</td>
	<td class='FieldCaptionTD' align='center' width='10%'># Horas</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Refrigerio</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Descan_1</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Descan_2</td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia1' id='Dia1' value='1' <?php if($Dia1=="1") echo "Checked";?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Lunes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia1'><?php echo $tturno_dia1?>
		<Input  class='Input' type='hidden' name='cturno_dia1' id='cturno_dia1' value="<?php echo $cturno_dia1?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia1'><?php if($sturno_dia1!="") echo $sturno_dia1?></td>
	<td class='DataTD' align='center' id='nturno_dia1'><?php echo $nturno_dia1?></td>
	<td class='DataTD' align='center' id='lturno_dia1'><?php if($lturno_dia1!="0") echo $lturno_dia1?></td>
	<td class='DataTD' align='center' id='dturno_dia1'><?php if($dturno_dia1!="0") echo $dturno_dia1?></td>
	<td class='DataTD' align='center' id='eturno_dia1'><?php if($eturno_dia1!="0") echo $eturno_dia1?></td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia2' id='Dia2' value='1' <?php if ($Dia2=="1") echo "Checked"; ?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Martes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia2'><?php echo $tturno_dia2?>
		<Input  class='Input' type='hidden' name='cturno_dia2' id='cturno_dia2' value="<?php echo $cturno_dia2?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia2'><?php if($sturno_dia2!="") echo $sturno_dia2?></td>
	<td class='DataTD' align='center' id='nturno_dia2'><?php echo $nturno_dia2?></td>
	<td class='DataTD' align='center' id='lturno_dia2'><?php if($lturno_dia2!="0") echo $lturno_dia2?></td>
	<td class='DataTD' align='center' id='dturno_dia2'><?php if($dturno_dia2!="0") echo $dturno_dia2?></td>
	<td class='DataTD' align='center' id='eturno_dia2'><?php if($eturno_dia2!="0") echo $eturno_dia2?></td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia3' id='Dia3' value='1' <?php if ($Dia3=="1") echo "Checked"; ?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Miercoles&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia3'><?php echo $tturno_dia3?>
		<Input  class='Input' type='hidden' name='cturno_dia3' id='cturno_dia3' value="<?php echo $cturno_dia3?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia3'><?php if($sturno_dia3!="") echo $sturno_dia3?></td>
	<td class='DataTD' align='center' id='nturno_dia3'><?php echo $nturno_dia3?></td>
	<td class='DataTD' align='center' id='lturno_dia3'><?php if($lturno_dia3!="0") echo $lturno_dia3?></td>
	<td class='DataTD' align='center' id='dturno_dia3'><?php if($dturno_dia3!="0") echo $dturno_dia3?></td>
	<td class='DataTD' align='center' id='eturno_dia3'><?php if($eturno_dia3!="0") echo $eturno_dia3?></td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia4' id='Dia4' value='1' <?php if ($Dia4=="1") echo "Checked"; ?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Jueves&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia4'><?php echo $tturno_dia4?>
		<Input  class='Input' type='hidden' name='cturno_dia4' id='cturno_dia4' value="<?php echo $cturno_dia4 ?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia4'><?php if($sturno_dia4!="") echo $sturno_dia4?></td>
	<td class='DataTD' align='center' id='nturno_dia4'><?php echo $nturno_dia4?></td>
	<td class='DataTD' align='center' id='lturno_dia4'><?php if($lturno_dia4!="0") echo $lturno_dia4?></td>
	<td class='DataTD' align='center' id='dturno_dia4'><?php if($dturno_dia4!="0") echo $dturno_dia4?></td>
	<td class='DataTD' align='center' id='eturno_dia4'><?php if($eturno_dia4!="0") echo $eturno_dia4?></td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia5' id='Dia5' value='1' <?php if ($Dia5=="1") echo "Checked"; ?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Viernes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia5'><?php echo $tturno_dia5?>
		<Input  class='Input' type='hidden' name='cturno_dia5' id='cturno_dia5' value="<?php echo $cturno_dia5?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia5'><?php if($sturno_dia5!="") echo $sturno_dia5?></td>
	<td class='DataTD' align='center' id='nturno_dia5'><?php echo $nturno_dia5?></td>
	<td class='DataTD' align='center' id='lturno_dia5'><?php if($lturno_dia5!="0") echo $lturno_dia5?></td>
	<td class='DataTD' align='center' id='dturno_dia5'><?php if($dturno_dia5!="0") echo $dturno_dia5?></td>
	<td class='DataTD' align='center' id='eturno_dia5'><?php if($eturno_dia5!="0") echo $eturno_dia5?></td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia6' id='Dia6' value='1' <?php if ($Dia6=="1") echo "Checked"; ?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Sabado&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia6'><?php echo $tturno_dia6?>
		<Input  class='Input' type='hidden' name='cturno_dia6' id='cturno_dia6' value="<?php echo $cturno_dia6?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia6'><?php if($sturno_dia6!="") echo $sturno_dia6?></td>
	<td class='DataTD' align='center' id='nturno_dia6'><?php echo $nturno_dia6?></td>
	<td class='DataTD' align='center' id='lturno_dia6'><?php if($lturno_dia6!="0") echo $lturno_dia6?></td>
	<td class='DataTD' align='center' id='dturno_dia6'><?php if($dturno_dia6!="0") echo $dturno_dia6?></td>
	<td class='DataTD' align='center' id='eturno_dia6'><?php if($eturno_dia6!="0") echo $eturno_dia6?></td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia7' id='Dia7' value='1' <?php if ($Dia7=="1") echo "Checked"; ?>>
	</td>
	<td class='FieldCaptionTD' align='right'>Domingo&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia7'><?php echo $tturno_dia7?>
		<Input  class='Input' type='hidden' name='cturno_dia7' id='cturno_dia7' value="<?php echo $cturno_dia7?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia7'><?php if($sturno_dia7!="") echo $sturno_dia7?></td>
	<td class='DataTD' align='center' id='nturno_dia7'><?php echo $nturno_dia7?></td>
	<td class='DataTD' align='center' id='lturno_dia7'><?php if($lturno_dia7!="0") echo $lturno_dia7?></td>
	<td class='DataTD' align='center' id='dturno_dia7'><?php if($dturno_dia7!="0") echo $dturno_dia7?></td>
	<td class='DataTD' align='center' id='eturno_dia7'><?php if($eturno_dia7!="0") echo $eturno_dia7?></td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2">Activo&nbsp;</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='tc_activo' id='tc_activo' value='1' <?php if ($tc_activo=="1") echo "Checked"; ?> >
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="4">Total Horas Semanales&nbsp;</td>
	<td class='DataTD' align='center' id='ttotal_horas'><?php echo $ttotal_horas?>:<?php echo $ttotal_minutos?></td>
	<td class='DataTD' align='center' id='horas_refrigerio'><?php echo $horas_refrigerio?>:<?php echo $minutos_refrigerio?></td>
	<td class='FieldCaptionTD' align='right' colspan="1">Efectivas</td>
	<td class='DataTD' align='center' id='total_horas'><?php echo $total_horas?>:<?php echo $total_minutos?></td>
</tr>
<tr>
	<td colspan=8  class='FieldCaptionTD'>&nbsp;</td>
</tr>
<tr align='center'>
	<td colspan=8  class='FieldCaptionTD'>
		<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'  class='Button' style='width:80px' onclick="Finalizar();">
	</td>
</tr>
</table>
</div>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
</form>
<div style='position:absolute;left:100px;top:500px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>