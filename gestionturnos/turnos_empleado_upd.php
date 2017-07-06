<?php header("Expires: 0"); ?>
<?php

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../includes/clsCA_Turnos_Combinacion.php"); 
require_once("../../Includes/MyCombo.php");

$tc_codigo_sap="";
$tipo_area_codigo="1";
$turno_hora_inicio="-1";
$turno_minuto_inicio="-1";
$turno_horas="0";
$turno_minutos="0";
$tc_activo="0";
$empleado_id="";
$te_semana="";
$te_anio="";
$tc_codigo="";
$empleado_nombre="";
$existe_cambios="NO";
$acepta_horas="";
$turno_refrigerio="0";
$dni="";
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
$lturno_dia1="";$dturno_dia1="";$nturno_dia1="";
$lturno_dia2="";$dturno_dia2="";$nturno_dia2="";
$lturno_dia3="";$dturno_dia3="";$nturno_dia3="";
$lturno_dia4="";$dturno_dia4="";$nturno_dia4="";
$lturno_dia5="";$dturno_dia5="";$nturno_dia5="";
$lturno_dia6="";$dturno_dia6="";$nturno_dia6="";
$lturno_dia7="";$dturno_dia7="";$nturno_dia7="";
$total_horas="";$total_minutos="";
$eturno_dia1="0";$sturno_dia1="";$tferiado1="";
$eturno_dia2="0";$sturno_dia2="";$tferiado2="";
$eturno_dia3="0";$sturno_dia3="";$tferiado3="";
$eturno_dia4="0";$sturno_dia4="";$tferiado4="";
$eturno_dia5="0";$sturno_dia5="";$tferiado5="";
$eturno_dia6="0";$sturno_dia6="";$tferiado6="";
$eturno_dia7="0";$sturno_dia7="";$tferiado7="";              
$hdia1="";$hdia2="";$hdia3="";$hdia4="";
$hdia5="";$hdia6="";$hdia7="";
$horas_refrigerio="";$minutos_refrigerio="";
$te_fecha_inicio="";$te_fecha_fin="";
$ttotal_horas="";$ttotal_minutos="";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
$torder = isset($_GET["torder"])?$_GET["torder"]:"";
$mensaje = "";
$thorase = "";
$thorasp = "";


if (isset($_POST["tc_codigo"])) $tc_codigo = $_POST["tc_codigo"];
if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];
if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];
if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
if (isset($_GET["te_fecha_inicio"])) $te_fecha_inicio = $_GET["te_fecha_inicio"];
if (isset($_GET["te_fecha_fin"])) $te_fecha_fin = $_GET["te_fecha_fin"];
if (isset($_GET["dni"])) $dni = $_GET["dni"];
if (isset($_POST["empleado_id"])) $empleado_id = $_POST["empleado_id"];
if (isset($_POST["te_semana"])) $te_semana = $_POST["te_semana"];
if (isset($_POST["te_anio"])) $te_anio = $_POST["te_anio"];
if (isset($_POST["te_fecha_inicio"])) $te_fecha_inicio = $_POST["te_fecha_inicio"];
if (isset($_POST["te_fecha_fin"])) $te_fecha_fin = $_POST["te_fecha_fin"];
if (isset($_POST["dni"])) $dni = $_POST["dni"];

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
if (isset($_POST["turno_refrigerio"])) $turno_refrigerio = $_POST["turno_refrigerio"];

if (isset($_POST["tc_activo"])) $tc_activo = $_POST["tc_activo"];
if (isset($_POST["Dia1"])) $Dia1 = $_POST["Dia1"];
if (isset($_POST["Dia2"])) $Dia2 = $_POST["Dia2"];
if (isset($_POST["Dia3"])) $Dia3 = $_POST["Dia3"];
if (isset($_POST["Dia4"])) $Dia4 = $_POST["Dia4"];
if (isset($_POST["Dia5"])) $Dia5 = $_POST["Dia5"];
if (isset($_POST["Dia6"])) $Dia6 = $_POST["Dia6"];
if (isset($_POST["Dia7"])) $Dia7 = $_POST["Dia7"];

if (isset($_POST["existe_cambios"])) $existe_cambios = $_POST["existe_cambios"];
if (isset($_POST["acepta_horas"])) $acepta_horas = $_POST["acepta_horas"];

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$tiempo= $e->valida_hora();
if ($tiempo==0){
  ?>
	<script language='javascript'>
		alert('Esta opcion esta temporalmente deshabilitado intente ingresar en unos minutos');
		self.location.href = "main_turnos_gestion.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	</script>
  <?php
}

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());
$tmp_inicio = split("/",$te_fecha_inicio);
$tmp_fin = split("/",$te_fecha_fin);
//echo $tmp_inicio[2].$tmp_inicio[1].$tmp_inicio[0];
if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='SVE'){
		if ($Dia1=="1") $cturno_dia1=$turno_dia1; 		
		if ($Dia2=="1") $cturno_dia2=$turno_dia1; 		
		if ($Dia3=="1") $cturno_dia3=$turno_dia1; 		
		if ($Dia4=="1") $cturno_dia4=$turno_dia1; 		
		if ($Dia5=="1") $cturno_dia5=$turno_dia1; 		
		if ($Dia6=="1") $cturno_dia6=$turno_dia1; 		
		if ($Dia7=="1") $cturno_dia7=$turno_dia1; 		
		$e->empleado_codigo=$empleado_id;
		$e->te_semana=$te_semana;
		$e->te_anio=$te_anio;
		$e->turno_Dia1 = $cturno_dia1;
		$e->turno_Dia2 = $cturno_dia2;
		$e->turno_Dia3 = $cturno_dia3;
		$e->turno_Dia4 = $cturno_dia4;
		$e->turno_Dia5 = $cturno_dia5;
		$e->turno_Dia6 = $cturno_dia6;
		$e->turno_Dia7 = $cturno_dia7;
		$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
                //echo $existe_cambios;exit(0);
		if ($existe_cambios=="YES"){
			$mensaje = $e->Update_Dias_Cambios();
		}else{
			$mensaje = $e->Addnew_Dias_Cambios();
		}
		//$mensaje = $e->Update_Dias();
		if($mensaje=="OK"){
			$mensaje = $e->Query_cambio_modalidad();
			if($mensaje!="OK"){
				
			$mensaje = $e->Query_Horas_Permitido();
			if($mensaje!="OK"){
				$acepta_horas = '';
				$mensaje = $e->Delete_Dias_Cambios();
				?>
				<script language='javascript'>
				alert('El cambio que esta intentando realizar no coincide con la jornada asignado al empleado, verifique numero de horas o la ficha del personal, probablemente no este asignado correctamente la jornada');
				</script>
				<?php
			}else{
				$thorase = $e->thorase;
				$thorasp = $e->thorasp;
				if ($thorase!=$thorasp){
					$e->te_fecha_inicio = $tmp_inicio[2] . $tmp_inicio[1] . $tmp_inicio[0];
					$e->te_fecha_fin = $tmp_fin[2] . $tmp_fin[1] . $tmp_fin[0];
					$e->Query_Turno_Feriados();
					if ($e->tferiado1!='' || $e->tferiado2!='' || $e->tferiado3!='' || $e->tferiado4!='' || $e->tferiado5!='' || $e->tferiado6!='' || $e->tferiado7!=''){
						//$acepta_horas = ''; // Deshabilitado para cuando hay feriado igual valide
						$acepta_horas = 'Total Horas Diferente al permitido, debe ser: '.$thorase;
						echo "<script> alert('¡Atención!. Horas registradas NO coincide con horas efectivas, ¡CORREGIR!'); </script>";
					}else{
						$acepta_horas = 'Total Horas Diferente al permitido, debe ser: '.$thorase;
						echo "<script> alert('¡Atención!. Horas registradas NO coincide con horas efectivas, ¡CORREGIR!'); </script>";
					}
				}else{
					$acepta_horas = ''; 
				}
				$e->te_fecha_inicio = $tmp_inicio[2].$tmp_inicio[1].$tmp_inicio[0];
				$mensaje = $e->Query_Descanso_Obligado();
				if($mensaje!="OK"){
					$mensaje = $e->Delete_Dias_Cambios();
					?>
					<script language='javascript'>
					alert('El cambio que esta intentando realizar no contempla el descanso de 1 dia minimo en la semana, verifique la programacion semanal');
					</script>
					<?php
				}else{
					$mensaje = $e->Query_Restriccion_Jornada(); //--obviado para que no valide
					//$mensaje = "OK";
					if($mensaje!="OK"){
						$mensaje = $e->Delete_Dias_Cambios();
						$acepta_horas = ''; 
						?>
						<script language='javascript'>
						alert('el cambio que esta intentando realizar, quiebra el descanso entre jornadas que debe ser minimo 8 horas, verifique la programacion semanal');
						</script>
						<?php
					}else{
						$e->te_aniomes=$tmp_fin[2].$tmp_fin[1];
						$mensaje = $e->Query_Descanso_Dominical();
						if($mensaje!="OK"){
							$mensaje = $e->Delete_Dias_Cambios();
							?>
							<script language='javascript'>
							alert('el cambio no contempla el descanso minimo de 2 domingos en el mes');
							</script>
							<?php
						}else{
							if ($acepta_horas==''){
							?>
							<script language='javascript'>
							//window.open("solicitud_cambios.php?empleado_id=<?php echo $empleado_id;?>&te_semana=<?php echo $te_semana;?>&te_anio=<?php echo $te_anio;?>&te_fecha_inicio=<?php echo $te_fecha_inicio;?>&te_fecha_fin=<?php echo $te_fecha_fin;?>","Cambios","Width=950,Height=700");
							//window.open("../gestionturnos/reporte_cambios.php?empleado_id=<?php echo $empleado_id;?>&te_semana=<?php echo $te_semana;?>&te_anio=<?php echo $te_anio;?>&te_fecha_inicio=<?php echo $te_fecha_inicio;?>&te_fecha_fin=<?php echo $te_fecha_fin;?>","Cambios","Width=600,Height=520");
							</script>
							<?php
							}
						}
					}
				}
			}
			}
		}else{
			echo $mensaje;
		}
	}
	if ($_POST["hddaccion"]=='UPD'){
		$e->empleado_codigo=$empleado_id;
		$e->te_semana=$te_semana;
		$e->te_anio=$te_anio;
		$e->turno_Dia1 = $cturno_dia1;
		$e->turno_Dia2 = $cturno_dia2;
		$e->turno_Dia3 = $cturno_dia3;
		$e->turno_Dia4 = $cturno_dia4;
		$e->turno_Dia5 = $cturno_dia5;
		$e->turno_Dia6 = $cturno_dia6;
		$e->turno_Dia7 = $cturno_dia7;
		$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
		$mensaje = $e->Delete_Dias_Cambios();
		$mensaje = $e->Update_Dias();
		if($mensaje=="OK"){
			$r=$e->Update_Asistencia_Programada($empleado_id);
			?>
			<script language='javascript'>
			
			</script>
			<?php
		}else{
			echo $mensaje;
		}
	}
}
if ($te_semana!="" && $te_anio!="" && $empleado_id!=""){
	$e->empleado_codigo = $empleado_id;
	$mensaje = $e->Query_Empleado_Nombres();
	$empleado_nombre = $e->empleado_nombres;
	$e->te_semana = $te_semana;
	$e->te_anio = $te_anio;
	$mensaje = $e->Query_Existe_Cambios();
	if ($mensaje=="OK"){
		$existe_cambios="YES";
		//agregado para ver total de horas
		$mensaje = $e->Query_Turno_Empleado_Sap('C');
		$ttotal_horas = $e->ttotal_horas;
		$ttotal_minutos = $e->ttotal_minutos;
		//fin agregado
		$mensaje = $e->Query_Turno_Empleado_Cambios();
	}else{
		$existe_cambios="NO";
		//agregado para ver total de horas
		$mensaje = $e->Query_Turno_Empleado_Sap();
		$ttotal_horas = $e->ttotal_horas;
		$ttotal_minutos = $e->ttotal_minutos;
		//fin agregado
		$mensaje = $e->Query_Turno_Empleado();
	}
	$tc_codigo_sap = $e->tc_codigo_sap;
	$cturno_dia1 = $e->turno_Dia1;
	$cturno_dia2 = $e->turno_Dia2;
	$cturno_dia3 = $e->turno_Dia3;
	$cturno_dia4 = $e->turno_Dia4;
	$cturno_dia5 = $e->turno_Dia5;
	$cturno_dia6 = $e->turno_Dia6;
	$cturno_dia7 = $e->turno_Dia7;
	$tc_activo = $e->tc_activo;
	$tturno_dia1 = $e->tturno_Dia1;
	$tturno_dia2 = $e->tturno_Dia2;
	$tturno_dia3 = $e->tturno_Dia3;
	$tturno_dia4 = $e->tturno_Dia4;
	$tturno_dia5 = $e->tturno_Dia5;
	$tturno_dia6 = $e->tturno_Dia6;
	$tturno_dia7 = $e->tturno_Dia7;
	$lturno_dia1 = $e->lturno_Dia1;
	$lturno_dia2 = $e->lturno_Dia2;
	$lturno_dia3 = $e->lturno_Dia3;
	$lturno_dia4 = $e->lturno_Dia4;
	$lturno_dia5 = $e->lturno_Dia5;
	$lturno_dia6 = $e->lturno_Dia6;
	$lturno_dia7 = $e->lturno_Dia7;
	$dturno_dia1 = $e->dturno_Dia1;
	$dturno_dia2 = $e->dturno_Dia2;
	$dturno_dia3 = $e->dturno_Dia3;
	$dturno_dia4 = $e->dturno_Dia4;
	$dturno_dia5 = $e->dturno_Dia5;
	$dturno_dia6 = $e->dturno_Dia6;
	$dturno_dia7 = $e->dturno_Dia7;
	$nturno_dia1 = $e->nturno_Dia1;
	$nturno_dia2 = $e->nturno_Dia2;
	$nturno_dia3 = $e->nturno_Dia3;
	$nturno_dia4 = $e->nturno_Dia4;
	$nturno_dia5 = $e->nturno_Dia5;
	$nturno_dia6 = $e->nturno_Dia6;
	$nturno_dia7 = $e->nturno_Dia7;
	$total_horas = $e->total_horas;
	$total_minutos = $e->total_minutos;
	$eturno_dia1 = $e->eturno_Dia1;
	$eturno_dia2 = $e->eturno_Dia2;
	$eturno_dia3 = $e->eturno_Dia3;
	$eturno_dia4 = $e->eturno_Dia4;
	$eturno_dia5 = $e->eturno_Dia5;
	$eturno_dia6 = $e->eturno_Dia6;
	$eturno_dia7 = $e->eturno_Dia7;
	$sturno_dia1 = $e->sturno_Dia1;
	$sturno_dia2 = $e->sturno_Dia2;
	$sturno_dia3 = $e->sturno_Dia3;
	$sturno_dia4 = $e->sturno_Dia4;
	$sturno_dia5 = $e->sturno_Dia5;
	$sturno_dia6 = $e->sturno_Dia6;
	$sturno_dia7 = $e->sturno_Dia7;
	$hdia1 = $e->hdia1;
	$hdia2 = $e->hdia2;
	$hdia3 = $e->hdia3;
	$hdia4 = $e->hdia4;
	$hdia5 = $e->hdia5;
	$hdia6 = $e->hdia6;
	$hdia7 = $e->hdia7;
	$horas_refrigerio = $e->horas_refrigerio;
	$minutos_refrigerio = $e->minutos_refrigerio;
	
	$tmp_inicio = split("/",$te_fecha_inicio);
	$tmp_fin = split("/",$te_fecha_fin);
	$e->te_fecha_inicio = $tmp_inicio[2] . $tmp_inicio[1] . $tmp_inicio[0];
	$e->te_fecha_fin = $tmp_fin[2] . $tmp_fin[1] . $tmp_fin[0];
	$e->Query_Turno_Feriados();
	$tferiado1 = $e->tferiado1;
	$tferiado2 = $e->tferiado2;
	$tferiado3 = $e->tferiado3;
	$tferiado4 = $e->tferiado4;
	$tferiado5 = $e->tferiado5;
	$tferiado6 = $e->tferiado6;
	$tferiado7 = $e->tferiado7;

	$e->Query_Horas_Permitido();	
	$thorase = $e->thorase;
	$thorasp = $e->thorasp;
	if ($thorase!=$thorasp){
		$acepta_horas = 'Corregir: horas efectivas deben ser: '.$thorase;
	}else{
		$acepta_horas = ''; 
	}
	$mensaje = $e->Query_cambio_modalidad();
	if ($mensaje=="OK"){
		$acepta_horas = '';
	}else{ $mensaje="OK"; }
	if ($tferiado1!='' || $tferiado2!='' || $tferiado3!='' || $tferiado4!='' || $tferiado5!='' || $tferiado6!='' || $tferiado7!=''){
		//$acepta_horas = ''; // Deshabilitado para cuando hay feriado igual valide
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Modificacion de Turno</title>
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
    //frames['ifr_procesos'].location.href = "procesos.php?opcion=existe_tc&tc_codigo_sap="+document.getElementById("tc_codigo_sap").value+"&turno_dia1="+turno_dia1+"&turno_dia2="+turno_dia2+"&turno_dia3="+turno_dia3+"&turno_dia4="+turno_dia4+"&turno_dia5="+turno_dia5+"&turno_dia6="+turno_dia6+"&turno_dia7="+turno_dia7;
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	document.frm.submit();
}

function GrabarV(){
	document.getElementById("Dia1").checked=false;
	document.getElementById("Dia2").checked=false;
	document.getElementById("Dia3").checked=false;
	document.getElementById("Dia4").checked=false;
	document.getElementById("Dia5").checked=false;
	document.getElementById("Dia6").checked=false;
	document.getElementById("Dia7").checked=false;
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	document.frm.submit();
}

function Firmar(){
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
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="UPD";
	document.frm.submit();
}

function aplicar(){
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	document.frm.submit();
}

function Finalizar(){
	self.location.href = "main_turnos_gestion.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
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

function cmdVerHis_onclick(){
    //alert('hi');
    var empleado_id = <?php echo $empleado_id; ?>;
    var te_semana = <?php echo $te_semana; ?>;
    var te_anio = <?php echo $te_anio; ?>;
    //alert('te_semana=' + te_semana);
    //CenterWindow("../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana + "&te_anio=" + te_anio,'Historial','dialogWidth:800px; dialogHeight:800px');
    var valor = window.showModalDialog("../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana + "&te_anio=" + te_anio,"Historial", 'dialogWidth:1200px; dialogHeight:600px');
}

</script>
<body Class='PageBODY'>
<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<?php
	if ($turno_refrigerio=='') $turno_refrigerio='0';
	$sql=" select turno_codigo as codigo,turno_descripcion +  ";
	$sql .=" ' Cod('+isnull(turno_id,'')+')'+ ";
	$sql .=" case when Turno_Refrigerio=0 then '' else ";
	$sql .=" ' Ref('+isnull(cast(Turno_Refrigerio as varchar),'')+' Min)' end +  ";
	$sql .=" case when Turno_Descanzo=0 then '' else ";
	$sql .=" ' Des1('+isnull(cast(Turno_Descanzo as varchar),'')+' Min)' end +  ";
	$sql .=" case when Turno_Descanso2=0 then '' else ";
	$sql .=" ' Des2('+isnull(cast(Turno_Descanso2 as varchar),'')+' Min)' end as descripcion from vCA_turnosTH ";
	$sql .=" where turno_id is not null and Tipo_Area_Codigo=" . $tipo_area_codigo . " and ";
	$sql .=" Turno_Hora_Inicio=" . $turno_hora_inicio . " and ";
	$sql .=" Turno_Minuto_Inicio=" . $turno_minuto_inicio;
	$sql .=" and Turno_Refrigerio=" . $turno_refrigerio;
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
<center class=FormHeaderFont>Programación Semana:&nbsp;<?php echo $te_semana ?><br>Del:&nbsp;<?php echo $te_fecha_inicio?>&nbsp;Al:&nbsp;<?php echo $te_fecha_fin?><br> <?php echo $empleado_nombre." DNI:".$dni ?> </center>
<center><font size='3' color='red'><?php if($existe_cambios=='YES') echo ''; ?></font></center>
<form name="frm" id="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table  class='FormTable' width='65%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='center' colspan="3"><strong>FILTROS</strong>&nbsp;</td>
</tr>
<tr>
	<td class='FieldCaptionTD' width='30%' align='right'>Tipo&nbsp;</td>
	<td class='DataTD' width='45%'>
		<input type="radio" name="tipo_area_codigo" id="tipo_operativo" value="1" <?php if ($tipo_area_codigo=="1") echo "Checked"; ?>>Operativo
		<input type="radio" name="tipo_area_codigo" id="tipo_administrativo" value="0" <?php if ($tipo_area_codigo=="0") echo "Checked"; ?>>Administrativo
	</td>
	<td rowspan="3" class='FieldCaptionTD' width='30%' valign="middle" align="center" ><input name='cmdBuscar' id='cmdBuscar' type='button' value='Buscar' class='Button' style='width:80px'  onclick="Buscar()"></td>
</tr>
<tr>
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
<tr>
	<td class='FieldCaptionTD' align='right'>Cantidad Horas Dia&nbsp;</td>
	<td class='DataTD'>Horas
		<Input class='Input' type='text' name='turno_horas' id='turno_horas' maxlength='2' value='<?php echo $turno_horas ?>' style="width='35px'" onKeyPress='return esnumero(event)'>&nbsp;:&nbsp;Minutos&nbsp;
		<Input class='Input' type='text' name='turno_minutos' id='turno_minutos' maxlength='2' value='<?php echo $turno_minutos ?>' style="width='35px'" onKeyPress='return esnumero(event)'>
	</td>
	
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Refrigerio&nbsp;</td>
	<td class='DataTD'>Minutos
		<Input class='Input' type='text' name='turno_refrigerio' id='turno_refrigerio' maxlength='4' value='<?php echo $turno_refrigerio ?>' style="width='35px'" onKeyPress='return esnumero(event)'>&nbsp;&nbsp;
	</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Turnos Encontrados&nbsp;</td>
	<td class='DataTD' colspan="2">
		<?php
		$combo->name = "turno_dia1"; 
		$combo->value = $turno_dia1 ."";
		$combo->more = "class='Select' style='width:420px' ";
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
<table width='65%' cellspacing='0' cellpadding='0' align='center' border='1'>
<tr>
	<td colspan='2' class='DataTD' type='text' align='center' ondblclick='manejarDiv("div_asignar")'><b>ASIGNAR&nbsp;</b></td>
</tr>
</table>
<div style='display:block' id='div_asignar'>
<table  class='FormTable' width='65%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<Input  class='Input' type='hidden' name='tc_codigo' id='tc_codigo' value="<?php echo $tc_codigo ?>" maxlength='0' style='width:80px;' <?php echo lectura("D")?>>
<tr>
	<td class='FieldCaptionTD' align='right' colspan="2">Código Com&nbsp;</td>
	<td class='DataTD' colspan="1">
		<Input  class='Input' type='text' name='tc_codigo_sap' id='tc_codigo_sap' value="<?php echo $tc_codigo_sap?>" maxlength='15' style='width:80px;' <?php echo lectura("I")?>>
	</td>
	<?php		
		$sfirma=''; 
		if($existe_cambios=='YES' && $acepta_horas=='') $sfirma=' - Firmar'; 		
	?>
	<td align='center' colspan='5' style='color:red;'><?php echo $acepta_horas==''?'Total horas efectivas Correcto'.$sfirma:$acepta_horas ?>&nbsp;</td>
</tr>
<tr>
	<td class='FieldCaptionTD' align='center' width='8%'>
	<Input class='Input' type='checkbox' name='Dias' id='Dias' value='1' <?php if ($Dias=="1") echo "Checked"; ?> onclick='cambiarcheck();' <?php echo $hdia1?>><br>Todos
	</td>
	<td class='FieldCaptionTD' align='center' width='15%'>Dias</td>
	<td class='FieldCaptionTD' align='center' width='35%'>Turnos</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Cod.Tur</td>
	<td class='FieldCaptionTD' align='center' width='10%'># Horas</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Refrigerio</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Descan_1</td>
	<td class='FieldCaptionTD' align='center' width='10%'>Descan_2</td>
</tr>
<tr>
	<td class='DataTD' align='center'>
	<Input class='Input' type='checkbox' name='Dia1' id='Dia1' value='1' <?php if($Dia1=="1") echo "Checked";?> <?php echo $hdia1?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado1?> >Lunes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia1' <?php echo $tferiado1?> ><?php echo $tturno_dia1?>
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
	<Input class='Input' type='checkbox' name='Dia2' id='Dia2' value='1' <?php if ($Dia2=="1") echo "Checked";?> <?php echo $hdia2?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado2?> >Martes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia2' <?php echo $tferiado2?> ><?php echo $tturno_dia2?>
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
	<Input class='Input' type='checkbox' name='Dia3' id='Dia3' value='1' <?php if ($Dia3=="1") echo "Checked";?> <?php echo $hdia3?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado3?> >Miercoles&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia3' <?php echo $tferiado3?> ><?php echo $tturno_dia3?>
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
	<Input class='Input' type='checkbox' name='Dia4' id='Dia4' value='1' <?php if ($Dia4=="1") echo "Checked";?> <?php echo $hdia4?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado4?> >Jueves&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia4' <?php echo $tferiado4?> ><?php echo $tturno_dia4?>
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
	<Input class='Input' type='checkbox' name='Dia5' id='Dia5' value='1' <?php if ($Dia5=="1") echo "Checked";?> <?php echo $hdia5?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado5?> >Viernes&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia5' <?php echo $tferiado5?> ><?php echo $tturno_dia5?>
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
	<Input class='Input' type='checkbox' name='Dia6' id='Dia6' value='1' <?php if ($Dia6=="1") echo "Checked";?> <?php echo $hdia6?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado6?> >Sabado&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia6' <?php echo $tferiado6?> ><?php echo $tturno_dia6?>
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
	<Input class='Input' type='checkbox' name='Dia7' id='Dia7' value='1' <?php if ($Dia7=="1") echo "Checked";?> <?php echo $hdia7?>>
	</td>
	<td class='FieldCaptionTD' align='right' <?php echo $tferiado7?> >Domingo&nbsp;</td>
	<td class='DataTD' align='left' id='tturno_dia7' <?php echo $tferiado7?> ><?php echo $tturno_dia7?>
		<Input  class='Input' type='hidden' name='cturno_dia7' id='cturno_dia7' value="<?php echo $cturno_dia7?>">
	</td>
	<td class='DataTD' align='center' id='sturno_dia7'><?php if($sturno_dia7!="") echo $sturno_dia7?></td>
	<td class='DataTD' align='center' id='nturno_dia7'><?php echo $nturno_dia7?></td>
	<td class='DataTD' align='center' id='lturno_dia7'><?php if($lturno_dia7!="0") echo $lturno_dia7?></td>
	<td class='DataTD' align='center' id='dturno_dia7'><?php if($dturno_dia7!="0") echo $dturno_dia7?></td>
	<td class='DataTD' align='center' id='eturno_dia7'><?php if($eturno_dia7!="0") echo $eturno_dia7?></td>
</tr>
<!--<tr>
	<td class='FieldCaptionTD' align='right' colspan="4">Total Horas Semanales Efectivas&nbsp;</td>
	<td class='DataTD' align='center' id='total_horas'><?php echo $total_horas?>:<?php echo $total_minutos?></td>
	<td class='DataTD' align='center' id='horas_refrigerio'><?php echo $horas_refrigerio?>:<?php echo $minutos_refrigerio?></td>
	<td class='FieldCaptionTD' align='right' colspan="3">&nbsp;</td>
</tr>-->
<tr>
	<td class='FieldCaptionTD' align='right' colspan="4">Total Horas Semanales &nbsp;</td>
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
	<?php if($hdia7!='disabled' ){?>
		<input name='cmdGuardar' id='cmdGuardar' type='button' value='Modificar' <?php if ($tiempo==0) echo 'disabled'; ?> class='Button' style='width:80px'  onclick="Grabar(); ">
	<?php }?>
		<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cancelar' class='Button' style='width:80px' onclick="Finalizar();">
	<?php if($existe_cambios=='YES' && $acepta_horas==''){
	?>
		<input name='cmdFirmar' id='cmdFirmar' type='button' value='Firmar' class='Button' style='width:80px'  onclick="Firmar();">
	<?php }?>
	<?php if($existe_cambios=='YES' && $acepta_horas!=''){
	?>
		<input name='cmdCheck' id='cmdCheck' type='button' value='Check' class='Button' style='width:80px'  onclick="GrabarV();">
	<?php }?>
        <input name='cmdHistorial' id='cmdHistorial' type='button' value='Historial' onclick='return cmdVerHis_onclick()' title='Historial de cambios y suplencias' class='Button' style='width:80px'/>
	</td>
</tr>
</table>
</div>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<Input class='Input' type='hidden' name='te_semana' id='te_semana' value="<?php echo $te_semana?>">
<Input class='Input' type='hidden' name='te_anio' id='te_anio' value="<?php echo $te_anio?>">
<Input class='Input' type='hidden' name='empleado_id' id='empleado_id' value="<?php echo $empleado_id?>">
<Input class='Input' type='hidden' name='empleado_nombre' id='empleado_nombre' value="<?php echo $empleado_nombre?>">
<Input class='Input' type='hidden' name='te_fecha_inicio' id='te_fecha_inicio' value="<?php echo $te_fecha_inicio?>">
<Input class='Input' type='hidden' name='te_fecha_fin' id='te_fecha_fin' value="<?php echo $te_fecha_fin?>">
<Input class='Input' type='hidden' name='existe_cambios' id='existe_cambios' value="<?php echo $existe_cambios?>">
<Input class='Input' type='hidden' name='acepta_horas' id='acepta_horas' value="<?php echo $acepta_horas?>">
<Input class='Input' type='hidden' name='dni' id='dni' value="<?php echo $dni?>">
</form>
<div style='position:absolute;left:100px;top:500px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>
