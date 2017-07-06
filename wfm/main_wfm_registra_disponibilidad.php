<?php header("Expires: 0"); 
session_start();
if (!isset($_SESSION["empleado_codigo"])){
?>
    <script language="JavaScript">
        alert("Su sesión a caducado!!, debe volver a registrarse.");
        document.location.href = "../login.php";
    </script>
<?php
exit;
}

require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/MyCombo.php");

$usuario=$_SESSION["empleado_codigo"];

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$rpta = "";
$body="";
$npag = 1;
$orden = "Empleado";
$buscam = "";
$torder="ASC";

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Disponibilidad  - <?php echo tituloGAP() ?>- WFM </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css">

<link rel="stylesheet" href="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/modalfiles/modal.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/modalfiles/modal.js"></script>

<script>

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

function cmdRegistrar_onclick(){
    var rpta=Registro();
    if (rpta != '' ) {
        //CenterWindow("wfm_registra_disponibilidad.php?empleado=" + rpta , "ModalChild",500,400,"yes","center");
        windowv=dhtmlmodal.open('Modificar', 'iframe', "wfm_registra_disponibilidad.php?empleado=" + rpta, 'Modificar Disponibilidad', 'width=450px,height=240px,center=1,resize=0,scrolling=1');		
    }
    //document.frm.submit();
}

function cmdImportar_onclick(){
	
	//CenterWindow("subir_archivoDisponibilidad.php","Reporte",600,200,"yes","center");
		windowv=dhtmlmodal.open('Importar Dsiponibilidad', 'iframe', "subir_archivoDisponibilidad.php" , 'Importar Disponibilidad','width=500px,height=250px,center=1,resize=0,scrolling=1')
}

function cmdRegistrarServicio_onclick(){
    var rpta=Registro();
   
    if (rpta != '' ) {
    	
        /*var arr = rpta.split("@");
        var emp = arr[0];
        var area = arr[1];
        
        alert (emp);
        alert (area);*/
        
		CenterWindow("empleado_disponibilidad_servicio.php?empleado=" + rpta , "ModalChild",500,400,"yes","center");
		//CenterWindow("empleado_disponibilidad_servicio.php?empleado=" + emp + "&area=" + area , "ModalChild",500,400,"yes","center");
	}
}

function cmdImportarServicio_onclick(){
    //CenterWindow("subir_archivoServicio.php","Reporte",600,200,"yes","center");
    windowv=dhtmlmodal.open('Importar Servicio', 'iframe', "subir_archivoServicio.php" , 'Importar Servicio','width=500px,height=250px,center=1,resize=0,scrolling=1')
}

</script>

</head>

<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >
<CENTER class="FormHeaderFont">WFM Disponibilidad Diaria , Horaria y Certificación de Servicio</CENTER>

<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="left">
            <INPUT class=button type='button' value='Registrar Disponibilidad' id='cmdRegistrar' name='cmdRegistrar'  LANGUAGE=javascript onclick='return cmdRegistrar_onclick()' style='width:150px;'>
            <INPUT class=button type='button' value='Importar Disponibilidad' id='cmdImportar' name='cmdImportar'  LANGUAGE=javascript onclick='return cmdImportar_onclick()' style='width:180px;'>
            <!--<INPUT class=button type='button' value='Exportar' id='cmdExportar' name='cmdExportar' LANGUAGE=javascript onclick='return cmdExportar_onclick()' style='width:60px;' title='Exportar programación de la siguiente semana'>-->
    	</td>

        <td class='ColumnTD' align="center">
            <INPUT class=button type='button' value='Certificar en Servicio' id='cmdRegistrarServicio' name='cmdRegistrarServicio'  LANGUAGE=javascript onclick='return cmdRegistrarServicio_onclick()' style='width:150px;'>
            <INPUT class=button type='button' value='Importar Certificación Servicio' id='cmdImportarServicio' name='cmdImportarServicio'  LANGUAGE=javascript onclick='return cmdImportarServicio_onclick()' style='width:200px;'>
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
	
	$from  = " empleado_area  (NOLOCK) ";
	$from .= " inner join empleados (NOLOCK) on empleado_area.empleado_codigo = empleados.empleado_codigo ";
	$from .= " inner join areas (NOLOCK) on areas.area_codigo = empleado_area.area_codigo";
	$from .= " inner join vdatos (NOLOCK) on vdatos.empleado_codigo = empleados.empleado_codigo";
	$from .= " inner join empleado_indicador (NOLOCK) on empleado_indicador.empleado_codigo = empleados.empleado_codigo";
	$from .= " left join disponibilidad_horas (NOLOCK) on disponibilidad_horas.dh_codigo = empleado_indicador.dh_codigo";
	$from .= " left join disponibilidad_dias (NOLOCK) on disponibilidad_dias.dd_codigo = empleado_indicador.dd_codigo";

	$objr->setFrom($from);
	// $where = " empleado_area.area_codigo in ( select area_codigo from ca_controller where empleado_codigo= " . $usuario . " and activo=1) and " ;
	$where = " empleado_area.area_codigo in ( select a.area_codigo from areas a INNER join Empleado_Area ea ON a.Area_Codigo=ea.Area_Codigo where ea.Empleado_Codigo = " . $usuario . " and empleado_area_activo=1) and  " ;
    $where .= " empleado_area.empleado_area_activo=1 and ";
    $where .= " empleados.estado_codigo=1  and";
    $where .= " areas.area_activo=1 ";
    
	$objr->setWhere($where);
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Empleado";
    $arrAlias[2] = "Dni";
    //$arrAlias[3] = "Sem";
	//$arrAlias[4] = "Com_Sem";
	$arrAlias[3] = "Area";
	$arrAlias[4] = "Modalidad";
	$arrAlias[5] = "Horario";
	$arrAlias[6] = "Disp_Diaria";
	$arrAlias[7] = "Disp_Horaria";
	
        
	// Arreglo de los Campos de la consulta
    //$cadena="cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + cast(te.tc_codigo as varchar) + '_' + e.empleado  + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103)";
    
    $arrCampos[0] = "empleados.empleado_codigo";
    //$arrCampos[0] = "cast(empleados.empleado_codigo as varchar) + '@' + cast(areas.area_codigo as varchar) ";
    $arrCampos[1] = "empleados.empleado_apellido_paterno + ' ' + empleados.empleado_apellido_materno + ' ' + empleados.empleado_nombres "; 
    $arrCampos[2] =	"empleados.empleado_dni";
	$arrCampos[3] = "areas.area_descripcion";
	$arrCampos[4] = "modalidad_descripcion";
	//$arrCampos[4] = "case when empleados.empleado_codigo = (select empleado_codigo from wfm_empleado_disponibilidad where empleado_codigo = empleados.empleado_codigo and fecha between convert(datetime , '" . $te_fecha_inicio . "' , 103) and convert(datetime,'" . $te_fecha_fin . "',103) group by empleado_codigo) then 'SI' else 'NO' 	end";
    /*$arrCampos[5] = "case 
				   when empleados.empleado_codigo = (select empleado_codigo from wfm_empleado_disponibilidad where empleado_codigo = empleados.empleado_codigo and fecha between convert(datetime , '" . $te_fecha_inicio . "' , 103) and convert(datetime,'" . $te_fecha_fin . "',103) group by empleado_codigo) then '<center><img src=../../Images/asistencia/inline011.gif border=0 style=cursor:hand title=Situación_Semana border=0 onclick=VerDetalle(''' + convert(varchar , empleados.empleado_codigo)+ ''',' + '''$te_semana''' + ',' + '''$te_anio''' +  ') /></center>' end"; */
	$arrCampos[5] = "turno_descripcion"; 
	$arrCampos[6] = "disponibilidad_dias.dd_descripcion";
	$arrCampos[7] = "disponibilidad_horas.dh_descripcion";
    
    $objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo '<br>SQL: '. $objr->getmssql() . '<br>';
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");

?>

</form>

</body>
</html>