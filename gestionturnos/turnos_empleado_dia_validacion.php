<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../includes/clsCA_Turnos_combinacion.php"); 
require_once("../../Includes/MyCombo.php");

$turno_descripcion="";
$turno_limite="";
$empleado_id="";
$te_semana="";
$te_anio="";
$tc_codigo="";
$empleado_nombre="";
$dia_i="";
$existe_cambios="NO";

$turno_dia1="-1";
$cturno_dia="0";
$te_fecha_inicio="";$te_fecha_fin="";

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
$torder = $_GET["torder"];
$mensaje = "";

if (isset($_POST["tc_codigo"])) $tc_codigo = $_POST["tc_codigo"];
if (isset($_GET["tc_codigo"])) $tc_codigo = $_GET["tc_codigo"];
if (isset($_GET["empleado_id"])) $empleado_id = $_GET["empleado_id"];
if (isset($_GET["te_semana"])) $te_semana = $_GET["te_semana"];
if (isset($_GET["te_anio"])) $te_anio = $_GET["te_anio"];
if (isset($_GET["te_fecha_inicio"])) $te_fecha_inicio = $_GET["te_fecha_inicio"];
if (isset($_GET["te_fecha_fin"])) $te_fecha_fin = $_GET["te_fecha_fin"];
//if (isset($_GET["empleado_nombre"])) $empleado_nombre = $_GET["empleado_nombre"];
if (isset($_POST["empleado_id"])) $empleado_id = $_POST["empleado_id"];
if (isset($_POST["te_semana"])) $te_semana = $_POST["te_semana"];
if (isset($_POST["te_anio"])) $te_anio = $_POST["te_anio"];
//if (isset($_POST["empleado_nombre"])) $empleado_nombre = $_POST["empleado_nombre"];
if (isset($_POST["dia_i"])) $dia_i = $_POST["dia_i"];
if (isset($_POST["turno_dia1"])) $turno_dia1 = $_POST["turno_dia1"];
if (isset($_POST["te_fecha_inicio"])) $te_fecha_inicio = $_POST["te_fecha_inicio"];
if (isset($_POST["te_fecha_fin"])) $te_fecha_fin = $_POST["te_fecha_fin"];

if (isset($_POST["existe_cambios"])) $existe_cambios = $_POST["existe_cambios"];
if (isset($_POST["cturno_dia"])) $cturno_dia = $_POST["cturno_dia"];

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());
$tmp_inicio = explode("/",$te_fecha_inicio);

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='SVE'){
		$e->empleado_codigo=$empleado_id;
		$e->te_semana=$te_semana;
		$e->te_anio=$te_anio;
		$e->turno_Dia1 = $turno_dia1;
		$e->dia_i = $dia_i;
		$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
		if ($existe_cambios=="YES"){
			$mensaje = $e->Update_Dia_Cambios();
		}else{
			$mensaje = $e->Addnew_Dia_Cambios();
		}
		//$mensaje = $e->Update_Dia();
		if($mensaje=="OK"){
			$mensaje = $e->Query_cambio_modalidad();
			if($mensaje!="OK"){
			
			$mensaje = $e->Query_Horas_Permitido();
			if($mensaje!="OK"){
				$mensaje = $e->Delete_Dias_Cambios();
				?>
				<script language='javascript'>
				alert('El cambio que esta intentando realizar no coincide con la jornada asignado al empleado, verifique numero de horas o la ficha del personal, probablemente no este asignado correctamente la jornada');
				</script>
				<?php
			}else{
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
					$mensaje = $e->Query_Restriccion_Jornada();
					if($mensaje!="OK"){
						$mensaje = $e->Delete_Dias_Cambios();
						?>
						<script language='javascript'>
						alert('el cambio que esta intentando realizar, quiebra el descanso entre jornadas que debe ser minimo 8 horas, verifique la programacion semanal');
						</script>
						<?php
					}else{
						?>
						<script language='javascript'>
						window.open("solicitud_cambios.php?empleado_id=<?php echo $empleado_id;?>&te_semana=<?php echo $te_semana;?>&te_anio=<?php echo $te_anio;?>&te_fecha_inicio=<?php echo $te_fecha_inicio;?>&te_fecha_fin=<?php echo $te_fecha_fin;?>","Cambios","Width=950,Height=700");
						//window.open("../gestionturnos/reporte_cambios.php?empleado_id=<?php echo $empleado_id;?>&te_semana=<?php echo $te_semana;?>&te_anio=<?php echo $te_anio;?>&te_fecha_inicio=<?php echo $te_fecha_inicio;?>&te_fecha_fin=<?php echo $te_fecha_fin;?>","Cambios","Width=600,Height=520");
						</script>
						<?php
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
		$e->turno_Dia1 = $cturno_dia;
		$e->dia_i = $dia_i;
		$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
		$mensaje = $e->Delete_Dias_Cambios();
		$mensaje = $e->Update_Dia();
		if($mensaje=='OK'){
			$r=$e->Update_Asistencia_Programada($empleado_id);
			?>
			<script language='javascript'>
				window.close();
			</script>
			<?php
		}else{
			echo $mensaje;
		}
	}
}
if ($empleado_id!=""){
	$e->empleado_codigo = $empleado_id;
	if ($e->Query_Existe_Asistencia()==true){
		?>
		<script language='javascript'>
			alert('Imposible realizar cambios, porque ya se registro la asistencia.');
			window.close();
		</script>
		<?php
	}
}
if ($te_semana!="" && $te_anio!="" && $empleado_id!=""){
	//aqui agregado para invocar que jale semana_anio fecha inicio
	$te_anio = $tmp_inicio[2];
	$e->te_aniomes = $tmp_inicio[2].$tmp_inicio[1].$tmp_inicio[0];
	$e->Query_Semana();
	$te_semana = $e->te_semana;
	//
	$e->empleado_codigo = $empleado_id;
	$mensaje = $e->Query_Empleado_Nombres();
	$empleado_nombre = $e->empleado_nombres;
	$e->te_semana = $te_semana;
	$e->te_anio = $te_anio;
	$mensaje = $e->Query_Existe_Cambios();
	if ($mensaje=="OK"){
		$existe_cambios="YES";
		$mensaje = $e->Query_Turno_Dia_Cambios();
	}else{
		$existe_cambios="NO";
		$mensaje = $e->Query_Turno_Dia();
	}
	//$mensaje = $e->Query_Turno_Dia();
	$turno_descripcion = $e->turno_descripcion;
	$turno_limite = $e->turno_limite;
	$dia_i = $e->dia_i;
	$cturno_dia = $e->turno_Dia1;
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
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script language='javascript'>
var mensaje='';

function Grabar(){
    if (validarCampo('frm','turno_dia1')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="SVE";
	document.frm.submit();
}

function Firmar(){
	if (confirm('confirme Actualizar datos')== false){
		return false;
	}
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>";
	document.frm.hddaccion.value="UPD";
	document.frm.submit();
}

function Finalizar(){
	window.close();
}


function seleccionarDato(combo,dia){
	if (dia=='1'){
		if (document.getElementById(combo).length>1 && document.getElementById(combo).selectedIndex==0) document.getElementById(combo).selectedIndex=1;
	}else{
		document.getElementById(combo).selectedIndex=0;
	}
}

</script>
</head>

<body Class='PageBODY'>
<?php
if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<?php
	$sql  =" select turno_codigo, turno_descripcion + ";
	$sql .=" ' Cod('+isnull(turno_id,'')+')'+ ";
	$sql .=" ' Col('+isnull(cast(Turno_Refrigerio as varchar),'')+' Min)'+ ";
	$sql .=" ' Des1('+isnull(cast(Turno_Descanzo as varchar),'')+' Min)'+ ";
	$sql .=" ' Des2('+isnull(cast(Turno_Descanso2 as varchar),'')+' Min)' as turno_descripcion ";
	$sql .=" from vCA_turnosTH ";
	$sql .=" where turno_id is not null ";
if ($e->Query_cambio_modalidad()!="OK"){
	$sql .=" and (convert(datetime, convert(varchar(10),getdate(),103)+' '+convert(varchar,Turno_Hora_Inicio) ";
	$sql .=" +':'+convert(varchar,Turno_Minuto_Inicio),103)) < ";
	$sql .=" (select convert(datetime, convert(varchar(10),fechap,103)+' '+convert(varchar,Turno_Hora_Inicio) ";
	$sql .=" +':'+convert(varchar,Turno_Minuto_Inicio),103) as limite ";
  if($existe_cambios=='YES'){ //validando para que seleccione filtrando desde cambios quitar si verifica del real
	$sql .=" from  vCA_turnos_programado_cambios tp inner join ca_turnos t on ";
  }else{
	$sql .=" from  vCA_turnos_programado tp inner join ca_turnos t on ";
  }
	$sql .=" tp.turno_codigo=t.turno_codigo ";
	$sql .=" where convert(varchar(10),fechap,112)=convert(varchar(10),getdate(),112) ";
	$sql .=" and empleado_codigo=" . $empleado_id;
	$sql .=" and te_semana=" . $te_semana;
	$sql .=" and te_anio=" . $te_anio;
	$sql .=" ) and ";
	$sql .=" (convert(datetime, convert(varchar(10),getdate(),103)+' '+convert(varchar,Turno_Hora_Inicio) ";
	$sql .=" +':'+convert(varchar,Turno_Minuto_Inicio),103))>getdate() ";
	$sql .=" and convert(varchar(5),turno_thoras,108)= ";
	$sql .=" (select convert(varchar(5),vt.turno_thoras,108) ";
	$sql .=" from vCA_turnos_programado vtp inner join vCA_turnosth vt on vtp.turno_codigo=vt.turno_codigo ";
	$sql .=" where convert(varchar(10),fechap,112)=convert(varchar(10),getdate(),112) ";
	$sql .=" and vtp.empleado_codigo=" . $empleado_id;
	$sql .=" and vtp.te_semana=" . $te_semana;
	$sql .=" and vtp.te_anio=" . $te_anio;
	$sql .=" ) ";
}
	$sql .=" order by 2 asc";
	$combo->query = $sql;

?>
<center class=FormHeaderFont>Programación Semana:&nbsp;<?php echo $te_semana ?><br><?php echo $empleado_nombre ?><br><?php echo $turno_limite ?> </center>
<center><font size='3' color='red'><?php if($existe_cambios=='YES') echo 'Registro Modificado';?></font></center>
<form name="frm" id="frm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
<table  class='FormTable' width='99%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<tr>
		<td class='FieldCaptionTD' align='center'><b>Turno Programado&nbsp;</td>
	</tr>
	<tr>
		<td class='DataTD' align="center"><?php echo $turno_descripcion ?></td>
	</tr>
	<tr>
		<td class='FieldCaptionTD'>&nbsp;</td>
	</tr>
</table>
<table  class='FormTable' width='99%' align='center' cellspacing='0' cellpadding='0' border='1'>
	<tr>
		<td class='FieldCaptionTD' align='center'><b>Turnos Disponibles&nbsp;</td>
	</tr>
	<tr>
		<td class='DataTD' align="center" >
			<?php
			$combo->name = "turno_dia1"; 
			$combo->value = $turno_dia1 ."";
			$combo->more = "class='Select' style='width:400px' ";
			$rpta = $combo->Construir();
			echo $rpta;
			?>
			<script>
			seleccionarDato('turno_dia1','1');
			</script>
		</td>
	</tr>
	<tr>
		<td class='FieldCaptionTD'>&nbsp;</td>
	</tr>
	<tr align='center'>
		<td class='FieldCaptionTD'>
			<input name='cmdGuardar' id='cmdGuardar' type='button' value='Aplicar' class='Button' style='width:80px' onclick="Grabar();">
			<input name='cmdCerrar' id='cmdCerrar' type='button' value='Cancelar' class='Button' style='width:80px' onclick="Finalizar();">
	<?php if($existe_cambios=='YES'){?>
		<input name='cmdFirmar' id='cmdFirmar' type='button' value='Firmar'  class='Button' style='width:80px'  onclick="Firmar();">
	<?php }?>
		</td>
	</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<Input class='Input' type='hidden' name='te_semana' id='te_semana' value="<?php echo $te_semana?>"/>
<Input class='Input' type='hidden' name='te_anio' id='te_anio' value="<?php echo $te_anio?>"/>
<Input class='Input' type='hidden' name='empleado_id' id='empleado_id' value="<?php echo $empleado_id?>"/>
<Input class='Input' type='hidden' name='empleado_nombre' id='empleado_nombre' value="<?php echo $empleado_nombre?>"/>
<Input class='Input' type='hidden' name='dia_i' id='dia_i' value="<?php echo $dia_i?>">
<Input class='Input' type='hidden' name='existe_cambios' id='existe_cambios' value="<?php echo $existe_cambios?>"/>
<Input class='Input' type='hidden' name='cturno_dia' id='cturno_dia' value="<?php echo $cturno_dia ?>"/>
<Input class='Input' type='hidden' name='te_fecha_inicio' id='te_fecha_inicio' value="<?php echo $te_fecha_inicio?>"/>
<Input class='Input' type='hidden' name='te_fecha_fin' id='te_fecha_fin' value="<?php echo $te_fecha_fin?>"/>
</form>
</body>
</html>
