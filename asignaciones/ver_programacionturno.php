<?php header("Expires: 0"); 
  ini_set('display_errors', 'On');
    error_reporting(E_ALL);
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?><script language="JavaScript">
    alert("Su sesión a caducado!!, debe volver a registrarse.");
    document.location.href = "../login.php";
  </script>
<?php
exit;
}

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
// require_once("../../Includes/MyGrilla.php");
require_once("../includes/MyGrillaEasyUI.php");
require_once("../../Includes/MyCombo.php");
$te_semana= date('W');
$te_anio=date('Y');
 

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

$rango = "";
$rpta = "";
$body="";
$npag = 1;
$orden = "te_semana,empleado";
$buscam = "";
$torder="ASC";
$te_fecha_inicio="";
$te_fecha_fin="";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
if (isset($_POST["te_semana"])) $te_semana = $_POST["te_semana"];
if (isset($_POST["te_anio"])) $te_anio = $_POST["te_anio"];

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());
if(isset($_POST["hddrango"])){
	if($_POST["hddrango"] != ""){
	    $temp = explode(" ",$_POST["hddrango"]);
	    $te_fecha_inicio = $temp[3];
	    $te_fecha_fin = $temp[5];
    }else{
    	$te_fecha_inicio = "";
    	$te_fecha_fin = "";
    }
}
/*$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();*/
$e->te_anio = $te_anio;
$e->te_semana = $te_semana;
$rpta = $e->Query_Turno_Fechas();

if ($rpta!='OK') $rango = "No Se Ha Programado Esta Semana";
else{
	$rango = "Desde : " . $e->te_fecha_inicio . " Hasta : " . $e->te_fecha_fin;
	$te_fecha_inicio = $e->te_fecha_inicio;
	$te_fecha_fin = $e->te_fecha_fin;
}

if (isset($_POST["hddaccion"])){
	if ($_POST["hddaccion"]=='UPD'){
		$e->te_semana=$te_semana;
		$e->te_anio=$te_anio;
		$e->te_fecha_inicio = $te_fecha_inicio;
		$e->te_fecha_fin = $te_fecha_fin;
		$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
		$mensaje = $e->Update_Replicar();
		if($mensaje=="OK"){
			?>
			<script language='javascript'>
			alert('Proceso Satisfactorio');
			</script>
			<?php
		}else{
			?>
			<script language='javascript'>
			alert('¡ERROR! Hubo problemas al realizar esta operacion, verifique los datos y reintente');
			</script>
			<?php
		}
	}
}

$esadmin="NO";
$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin = $e->Query_Rol_Admin();
$essuper="NO";
$essuper=$e->Query_Rol_Super();
$area=0;
$ex = new empleados();
$ex->MyUrl = db_host();
$ex->MyUser= db_user();
$ex->MyPwd = db_pass();
$ex->MyDBName= db_name();
$ex->empleado_codigo = $_SESSION["empleado_codigo"];
$rpta = $ex->Empleado_Area();
if ($rpta=='OK'){
	$area=$ex->area_codigo;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Programaci&oacute;n de Turnos al Personal</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<?php  require_once('../includes/librerias_easyui.php');?>


<script>

function cmdAdicionar_onclick() {
	self.location.href="turnos_empleado_job.php?hddbuscar=OK";
}

function cmdModificar_onclick() {
    var rpta=Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
    	self.location.href="turnos_empleado_upd.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>&empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio="+arr[2] + "&tc_codigo=" + arr[3] + "&te_fecha_inicio=" + arr[5] + "&te_fecha_fin=" + arr[6];
    }
}

function cmdGestiondia_onclick(){
    var rpta=Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
		CenterWindow("../gestionturnos/turnos_empleado_dia.php?empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio=" + arr[2], "ModalChild",400,250,"yes","center");
	}
}

function cmdVersap_onclick(valor){
    //alert(valor);
    var arr = valor.split("_");
    //alert(arr[1]);
    Ext_Dialogo.genera_Dialog(700,350,'VerSAP',"../gestionturnos/programacion_empleado.php?empleado_id=" + arr[1] + "&te_semana=" + arr[2] + "&te_anio=" + arr[3] + "&te_fecha_inicio=" + arr[4] + "&te_fecha_fin=" + arr[5], 'div_ver',100,100);

}

function Finalizar(){
    Ext_Dialogo.close_Dialog('div_ver');
}

function cmdVerHis_onclick(empleado_id, te_semana, te_anio){
    //alert('hi');  

  Ext_Dialogo.genera_Dialog(800,350,'Programacion Empleado',"../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio, 'div_histo',150,100);

}

/*
function cmdVersap_onclick(){
    var rpta=Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
		window.showModalDialog("../gestionturnos/programacion_empleado.php?empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio=" + arr[2] + "&te_fecha_inicio=" + arr[5] + "&te_fecha_fin=" + arr[6],'VerSAP','dialogWidth:600px; dialogHeight:320px');
	}
}
*/

function cmdDefecto_onclick(){
	//if (confirm('confirme REPLICAR DATOS POR DEFECTO para semana: '+document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text)== false){
	if (confirm('Esta opcion carga la programacion en curso hacia la siguiente semana y para aquellos colaboradores \nque no esten programados se cargara en base a la combinacion por defecto asignado al empleado. \nConfirma REPLICAR PROGRAMACION DE LA SEMANA EN CURSO para la SIGUIENTE SEMANA?')==false){
		return false;
	}
	document.frmp.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	document.frmp.hddaccion.value="UPD";
	document.frmp.submit();
}

function cmdVercronograma_onclick(){
	CenterWindow("../gestionturnos/reporte_turnos.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",900,600,"yes","center");

//    frames['ifr_procesos'].location.href = "procesos.php?opcion=exportar&te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value;
}

function cmdPublicar_onclick(){
	CenterWindow("../gestionturnos/publicar_turnos.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",900,600,"yes","center");
}

function cmdImportar_onclick(){
	CenterWindow("../gestionturnos/subir_archivo.php" ,"Reporte",800,600,"yes","center");
}

/*function cmdImportarWFM_onclick(){
	
	CenterWindow("../gestionturnos/subir_archivoWFM.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",800,600,"yes","center");
}*/

function cmdImportarg_onclick(){
	CenterWindow("../gestionturnos/subir_archivog.php" ,"Reporte",800,600,"yes","center");
}

function cmdLog_onclick() {
    self.location.href="main_turnos_log.php";
}

function sel_fila(n){
    alert(n);
}

</script>

</head>


<body class="PageBODY" >
<?php
	$sql ="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3 ";
	$combo->query = $sql;
?>
<CENTER class="FormHeaderFont">Programaci&oacute;n de Turnos <br> <?php echo $rango ?></CENTER>
<form id="frmp" name="frmp" method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="center">
		A&ntilde;o:&nbsp;<select class='select' name='te_anio' id='te_anio' onchange="javascrip:document.frmp.submit();">
		  <?php
		   for($a=2009; $a < 2050; $a++){
			 if ($a==$te_anio) echo "\t<option value=". $a . " selected>". $a ."</option>" . "\n";
		     else echo "\t<option value=". $a . ">". $a ."</option>" . "\n";
		   }
		  ?>
		 </select>Semana:&nbsp;
			<?php
				$combo->name = "te_semana"; 
				$combo->value = $te_semana ."";
				$combo->more = "class='Select' style='width:230px' onchange='javascrip:document.frmp.submit();'";
				$combo->datefirst='set datefirst 1';
				$rpta = $combo->Construir_Opcion("---Seleccione---");
				echo $rpta;
			?>
        </td>

    </tr>
</table>

<?php
        $objr = new MyGrilla;
        $objr->setDriver_Coneccion(db_name());
        $objr->setUrl_Coneccion(db_host());
        $objr->setUser(db_user());
        $objr->setPwd(db_pass());
        $objr->setOrder($orden);
        $objr->setFindm($buscam);
        $objr->setNoSeleccionable(false);
        $objr->setFont("color=#000000");
        $objr->setFormatoBto("class=button");
        $objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
        $objr->setFormaCabecera(" class=ColumnTD ");
        $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
        $objr->setTOrder($torder);
	
	$from  = " ca_turno_empleado te inner join vdatos e on te.empleado_codigo=e.empleado_codigo ";
	$from .= " inner join CA_Turnos_combinacion tc on te.tc_codigo=tc.tc_codigo ";
	if ($esadmin!='OK'){
		$from .= " inner join areas a on a.area_codigo=e.area_codigo ";
	}
	$objr->setFrom($from);
	$where = " te.te_semana=" . $te_semana . " and te.te_anio=" . $te_anio;
	if ($esadmin!='OK'){
		if ($essuper!='OK'){
			$where.=" and a.area_codigo in "; 
			$where.=" (select area_codigo from ca_controller ";
			$where.=" where empleado_codigo=".$_SESSION['empleado_codigo']." and activo=1 ";
			$where.=" union select area_codigo from vdatos where empleado_codigo=".$_SESSION['empleado_codigo']." ) ";
		}else{
			$where.= " and a.area_codigo = ".$area; 
		}
	}
	$objr->setWhere($where);
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
        $arrAlias[0] = "root";
        $arrAlias[1] = "Empleado";
        $arrAlias[2] = "Dni";
        $arrAlias[3] = "Sem";
        $arrAlias[4] = "Com_Sem";
        $arrAlias[5] = "Area";
        $arrAlias[6] = "Cargo";
        $arrAlias[7] = "Modalidad";
        $arrAlias[8] = "Ver_Pro";
        //$arrAlias[9] = "Ver_SAP";

    
        // Arreglo de los Campos de la consulta
        $cadena="cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + cast(te.tc_codigo as varchar) + '_' + e.empleado  + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103)";
        $arrCampos[0] = $cadena;
        $arrCampos[1] = "empleado"; 
        $arrCampos[2] =	"empleado_dni";
        $arrCampos[3] =	"te_semana";
        $arrCampos[4] = "tc.tc_codigo_sap";
        $arrCampos[5] = "e.area_descripcion";
        $arrCampos[6] = "cargo_descripcion";
        $arrCampos[7] = "modalidad_descripcion";
        $arrCampos[8] = "'<center><img id=img_' + cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) + ' src=''../../Images/asistencia/inline011.gif'' border=0 style=cursor:hand onclick=cmdVersap_onclick(this.id) title=Programacion_Semanal ></center>'";
        //$arrCampos[9] = "'<font id=fnt_' + cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) +  ' style=cursor:hand onclick=cmdVersap_onclick(this.id) title=ver_detalle>Ver</font>'";
    
        $objr->setAlias($arrAlias);
        $objr->setCampos($arrCampos);

        $body = $objr->Construir();  //ejecutar
        //echo $objr->getmssql();
        $objr = null;
        echo $body;
        echo "<br>";
        // echo Menu("../menu.php");
?>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="hddrango" name="hddrango" value=""/>
</form>
<div id="div_ver"></div>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>
