<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../includes/clsCA_Feriados.php");
require_once("../../Includes/MyCombo.php"); 

$feriado_codigo="";
$feriado_descripcion="";
$feriado_activo="0";
$fecha_feriado=date("d/m/Y",time());

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$npag = $_GET["pagina"];
$buscam = $_GET["buscam"];
$orden = $_GET["orden"];
if(isset($_GET["torder"])){
    $torder = $_GET["torder"];
}

$mensaje = "";

if (isset($_POST["anio"])) $anio = $_POST["anio"];
if (isset($_GET["anio"])) $anio = $_GET["anio"];


if (isset($_POST["feriado_codigo"])) $feriado_codigo = $_POST["feriado_codigo"];
if (isset($_GET["feriado_codigo"])) $feriado_codigo = $_GET["feriado_codigo"];

if (isset($_POST["feriado_descripcion"])) $feriado_descripcion = $_POST["feriado_descripcion"];
if (isset($_POST["feriado_activo"])) $feriado_activo = $_POST["feriado_activo"];

if (isset($_POST["txtFecha"])) $fecha_feriado = $_POST["txtFecha"];

if (isset($_POST["Tipo_Codigo"])) $tipo_codigo = $_POST["Tipo_Codigo"];
if (isset($_POST["CODDPTO"])) $coddpto = $_POST["CODDPTO"];
if (isset($_POST["pais_codigo"])) $pais_codigo = $_POST["pais_codigo"];

$o = new ca_feriados();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());


if (isset($_POST["hddaccion"])){
    if ($_POST["hddaccion"]=='SVE'){
        $o->anio= $anio;			
        $o->feriado_codigo = $feriado_codigo;
        $o->fecha_feriado = $fecha_feriado;
        
        $o->feriado_descripcion = $feriado_descripcion;
        $o->feriado_activo = $feriado_activo;
		$o->tipo_feriado  = $tipo_codigo;
		$o->coddpto  = $coddpto;
		$o->pais_codigo  = $pais_codigo;
        //--* guardar registro
        //echo 'save!!';
        if ($feriado_codigo==''){
            $mensaje = $o->Addnew();
            $feriado_codigo = $o->feriado_codigo;
        }else{
            $mensaje = $o->Update();
        }
        
        if($mensaje=="OK"){
?>
    <script language='javascript'>
        self.location.href='main_feriados.php';
    </script>
<?php
        }
    }
}
if ($feriado_codigo!=""){
	//recuperar datos
	$o->anio=$anio;
	$o->feriado_codigo = $feriado_codigo;
	$mensaje = $o->Query();
	$feriado_descripcion = $o->feriado_descripcion;
	$feriado_activo = $o->feriado_activo;
	$fecha_feriado=$o->fecha_feriado;
	$pais_codigo=$o->pais_codigo;
	$coddpto=$o->coddpto;
	$tipo_codigo=$o->tipo_feriado;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Feriado</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>


<?php  require_once('../includes/librerias_easyui.php');?>
<link rel="stylesheet" type="text/css" media="all" href="../../views/js/librerias/datepicker/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script language='javascript'>
function ok(){

    if (validarCampo('frm','feriado_descripcion')!=true) return false;
    if (validarCampo('frm','Tipo_Codigo')!=true) return false;
    if (document.frm.Tipo_Codigo.value ==2){
    	if (validarCampo('frm','CODDPTO')!=true) return false;
    }

    if (validarCampo('frm','pais_codigo')!=true) return false;
	if (confirm('confirme guardar los datos')== false){
		return false;
	}

	
	document.frm.action += "?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)) { echo $torder; } ?>";
	document.frm.hddaccion.value="SVE";
	return true;
}
function cancelar(){
	self.location.href = "main_feriados.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php if(isset($torder)){ echo $torder; } ?>";
}

function pedirFecha(campoTexto,nombreCampo) {
  ano = anoHoy();
  mes = mesHoy();
  dia = diaHoy();
  campoDeRetorno = campoTexto;
  titulo = nombreCampo;
  dibujarMes(ano,mes);
}
</script>

</head>


<body Class='PageBODY'>

<?php
	// echo $mensaje;

if ($mensaje!="OK") echo getMensaje($mensaje);
?>
<center class=FormHeaderFont>Registro de Feriado</center>
<form name='frm' id='frm' action='<?php echo $_SERVER["PHP_SELF"] ?>' method='post' onSubmit='javascript:return ok();'>
<table  class='FormTable' width='55%' align='center' cellspacing='0' cellpadding='0' border='1'>
<tr>
	<td class='FieldCaptionTD' align='right'>Código</td>
	<td class=''>
		<Input  class='' type='text' name='feriado_codigo' id='feriado_codigo' value="<?php echo $feriado_codigo ?>" maxlength='0' style='width:80px;' <?php echo  lectura("D")?>>
	</td>
</tr>
<tr>
    <td class="FieldCaptionTD" align="right" >
			Fecha&nbsp;</td>
    <td align="left" class="DataTD">
	  	<input type='text' class='' id="txtFecha" name="txtFecha" readOnly size=11 value="<?php echo $fecha_feriado ?>"> 
		<img  src="../images/columnselect.gif" width="15" height="15" border="0"  style='cursor:hand;' id="calendario" alt="Seleccionar Fecha">
	
	</td>
  </tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Descripcion</td>
	<td class='DataTD'>
		<Input  class='' type='text' name='feriado_descripcion' id='feriado_descripcion' value="<?php echo $feriado_descripcion?>" maxlength='80' style='width:300px;' >
	</td>
</tr>


<tr>
	<td class='FieldCaptionTD' align='right'>Tipo Feriado </td>
    <td class='DataTD' colspan="3">
    <?php
		$sSql = "select Tipo_Codigo, Tipo_Descripcion";
		$sSql = $sSql . " from CA_Tipo_Feriados ";
		$sSql = $sSql . " order by 2";

		$combo->query = $sSql;
		$combo->name = "Tipo_Codigo";
		$combo->value = $tipo_codigo."";
		$combo->more = "";
		$rpta = $combo->Construir();
		echo $rpta;
	?>    
    </td>

</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Departamento</td>
    <td class='DataTD' colspan="3">
    <?php
		$sSql = "select CODDPTO,NOMBRE from ubigeo";
		$sSql = $sSql . " where CODDIST= 00 and CODPROV =00 and CODDPTO <> 00 ";
		$sSql = $sSql . " order by 2";

		$combo->query = $sSql;
		$combo->name = "CODDPTO";
		$combo->value = $coddpto."";
		$combo->more = "";
		$rpta = $combo->Construir();
		echo $rpta;
	?>    
    </td>

</tr>
 
<tr>
	<td class='FieldCaptionTD' align='right'>Pais</td>
    <td class='DataTD' colspan="3">
    <?php
		$sSql = "select pais_codigo, pais_nombre";
		$sSql = $sSql . " from paises ";
		$sSql = $sSql . " where pais_activo = 1 order by 2";

		$combo->query = $sSql;
		$combo->name = "pais_codigo";
		$combo->value = $pais_codigo."";
		$combo->more = "style='width:200px;'";
		$rpta = $combo->Construir();
		echo $rpta;
	?>    
    </td>

</tr>
<tr>
	<td class='FieldCaptionTD' align='right'>Activo</td>
	<td class='DataTD'>
		<Input class='Input' type='checkbox' name='feriado_activo' id='feriado_activo' value='1' <?php if ($feriado_activo=="1") echo "Checked"; ?>>
	</td>
</tr>
<tr>
	<td colspan=2  class='FieldCaptionTD'>&nbsp;
</td>
</tr>
<tr align='center'>
	<td colspan=2  class='FieldCaptionTD'>
		<input class=buttons name='cmdGuardar' id='cmdGuardar' type='submit' value='Aceptar'  style='width:80px'>
		<input class=buttons name='cmdCerrar' id='cmdCerrar' type='button' value='Cerrar'   style='width:80px' onclick="cancelar();">
	</td>
</tr>
</table>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<input type="hidden" id="anio" name="anio" value="<?php if(isset($anio)){ echo $anio;}?>">
</form>

<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../views/js/librerias/datepicker/calendar-setup.js"></script>

<script type="text/javascript">
Calendar.setup({
    inputField     :    "txtFecha",
    ifFormat       :    "%d/%m/%Y",
    showsTime      :    false,
    button         :    "calendario",
    singleClick    :    true,
    step           :    1
});


		$(function() {
			$('#Tipo_Codigo').on('change',function(){
				if ($(this).val() == 3) {
					$('#CODDPTO').attr('disabled','disabled');	
					$('#pais_codigo').val(0);
					$('#CODDPTO').val(0);

				}else if ($(this).val() == 1){
					$('#CODDPTO').attr('disabled','disabled');	
					$('#pais_codigo').val(1);
				}else{
					$('#CODDPTO').attr('disabled',false);	
					$('#pais_codigo').val(1);
				}
			})
		});
	</script>

</body>
</html>