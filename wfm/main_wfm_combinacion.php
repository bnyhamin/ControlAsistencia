<?php
header("Expires: 0"); 
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
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/MyCombo.php");
require_once("../../Includes/clswfm_empleado_restricciones.php");

$usuario=$_SESSION["empleado_codigo"];

//$te_semana= date('W')+2;
//$te_anio=date('Y');
$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$e = new ca_turnos_empleado();
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());

$er = new wfm_empleado_restricciones();
$er->setMyUrl(db_host());
$er->setMyUser(db_user());
$er->setMyPwd(db_pass());
$er->setMyDBName(db_name());

$rpta = $e->Query_Numero_Semana();
if ($rpta=='OK'){
	$te_semana= $e->te_semana + 1;
	$te_anio=$e->te_anio;
}

$rpta = "";
$body="";
$npag = 1;
$orden = "4";
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

if (isset($_POST["hddinicio"])) $te_fecha_inicio = $_POST["hddinicio"];
if (isset($_POST["hddfin"])) $te_fecha_fin = $_POST["hddfin"];

if(isset($_POST["hddrango"])){
    $temp = explode(" ",$_POST["hddrango"]);
    if(count($temp)>0){
        if(count($temp)==4) $te_fecha_inicio=$temp[3];
        if(count($temp)==6) $te_fecha_fin=$temp[5];
    }
    //$te_fecha_inicio = $temp[3];
    //$te_fecha_fin = $temp[5];
}

$e->te_anio = $te_anio;
$e->te_semana = $te_semana;
$rpta = $e->Obtener_Fechas();
if ($rpta=='OK'){
    $te_fecha_inicio = $e->te_fecha_inicio;
    $te_fecha_fin = $e->te_fecha_fin;
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- WFM </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>
<script>

function cmdAdicionar_onclick() {
    self.location.href="turnos_empleado_job.php";
}

function VerDetalle(v1 , v2 , v3 ){
	
    var valor = window.showModalDialog("restricciones_detalle.php?empleado=" + v1  +"&semana="+ v2 + "&anio=" + v3,"Historico",'dialogWidth:600px; dialogHeight:300px');
    
    //var valor = CenterWindow("combinacion_detalle.php?empleado=" + v1  +"&semana="+ v2 + "&anio=" + v3,"Historico",'dialogWidth:600px; dialogHeight:300px');

	
}

function VerDetalleCombinacion(v1 , v2 , v3 ){
	
    var valor = window.showModalDialog("combinacion_detalle.php?empleado=" + v1  +"&semana="+ v2 + "&anio=" + v3,"Historico",'dialogWidth:600px; dialogHeight:300px');
    
    //var valor = CenterWindow("combinacion_detalle.php?empleado=" + v1  +"&semana="+ v2 + "&anio=" + v3,"Historico",'dialogWidth:600px; dialogHeight:300px');

	
}
function cmdGenerarCombinacion_onclick(){

	var fechas = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(' ');
	var semana=fechas[0];
	var fecha_inicio = fechas[3];
	var fecha_fin = fechas[5];
	
	var paginaparametros="generar_combinacion.php?&inicio=" + fecha_inicio + "&fin=" + fecha_fin + "&semana=" + semana;
	
	var valor = window.showModalDialog( paginaparametros,"Confirmar",'dialogWidth:300px; dialogHeight:300px');
	//var valor = window.open( paginaparametros,"Confirmar",'dialogWidth:300px; dialogHeight:300px');
	//alert(valor);	
	/*document.frm.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	document.frm.hddaccion.value="GEN";*/
	document.frm.submit();
	return true;
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

function cmdRegistrarServicio_onclick(){
    var rpta=Registro();
    if (rpta != '' ) {
    	
        //var arr = rpta.split("_");
		CenterWindow("empleado_disponibilidad_servicio.php?empleado=" + rpta , "ModalChild",500,400,"yes","center");
	}
}

function cmdVersap_onclick(valor){
    //alert(valor);
    var arr = valor.split("_");
    //alert(arr[1]);
	window.showModalDialog("../gestionturnos/programacion_empleado.php?empleado_id=" + arr[1] + "&te_semana=" + arr[2] + "&te_anio=" + arr[3] + "&te_fecha_inicio=" + arr[4] + "&te_fecha_fin=" + arr[5],'VerSAP','dialogWidth:600px; dialogHeight:320px');
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
	document.frm.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	document.frm.hddaccion.value="UPD";
	document.frm.submit();
}

function cmdVercronograma_onclick(){
	CenterWindow("../gestionturnos/reporte_turnos.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",900,600,"yes","center");

//    frames['ifr_procesos'].location.href = "procesos.php?opcion=exportar&te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value;
}

function cmdExportar_onclick(){
	
	var fechas = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(' ');
	var semana=fechas[0];
	var fecha_inicio = fechas[3];
	var fecha_fin = fechas[5];
	
	CenterWindow("reporte_programacion.php?fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin ,"Reporte",900,600,"yes","center");

//    frames['ifr_procesos'].location.href = "procesos.php?opcion=exportar&te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value;
}

function cmdPublicar_onclick(){
	CenterWindow("../gestionturnos/publicar_turnos.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",500,300,"yes","center");
}

function cmdImportar_onclick(){
	CenterWindow("../gestionturnos/subir_archivo.php" ,"Reporte",800,600,"yes","center");
}

function cmdImportarWFM_onclick(){
	
	CenterWindow("subir_archivoWFM.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",600,200,"yes","center");
}

function cmdImportarg_onclick(){
	CenterWindow("../gestionturnos/subir_archivog.php" ,"Reporte",800,600,"yes","center");
}

function cmdDisponibilidad_onclick() {
    	CenterWindow("carga_disponibilidad_empleado.php?te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value ,"Reporte",800,600,"yes","center");
}

function sel_fila(n){
    alert(n);
}

function consultar(){
	document.frm.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	document.frm.submit();
	
}
</script>
</head>
<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >
<CENTER class="FormHeaderFont">WFM Combinación</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="center">
		Año:&nbsp;<select class='select' name='te_anio' id='te_anio' onchange="consultar();">
		  <?php
		   for($a=2009; $a < 2050; $a++){
			 if ($a==$te_anio) echo "\t<option value=". $a . " selected>". $a ."</option>" . "\n";
		     else echo "\t<option value='". $a . "'>". $a ."</option>" . "\n";
		   }
		  ?>
		 </select> 
		 Semana:&nbsp;
			<?php
				$ssql="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3 ";
				//$ssql ="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by inicio";
				$combo->query = $ssql;
                                $combo->datefirst="set datefirst 1";
				//echo $ssql . ' -> ' . $te_semana;
				$combo->name = "te_semana"; 
				$combo->value = $te_semana ."";
				$combo->more = "class='Select' style='width:230px' onchange='consultar()'";
				//$rpta = $combo->Construir_Opcion("---Seleccione---");
				$rpta = $combo->Construir();
				echo $rpta;
				
			?>
        </td>
        <td class='ColumnTD' align="center">
        	<!--<INPUT class=button type='button' value='Importar WFM' id='cmdImportarWFM' name='cmdImportarWFM'  LANGUAGE=javascript onclick='return cmdImportarWFM_onclick()' style='width:120px;'>-->
                <INPUT class=button type='button' value='Generar Combinación' id='cmdGenerar' name='cmdGenerar'  LANGUAGE=javascript onclick='return cmdGenerarCombinacion_onclick()' style='width:180px;'>
        	<!--<INPUT class=button type='button' value='Programación' id='cmdProgramacion' name='cmdProgramacion'  LANGUAGE=javascript onclick='return cmdGenerarProgramacion_onclick()' style='width:100px;'>-->
        	<INPUT class=button type='button' value='Exportar' id='cmdExportar' name='cmdExportar' LANGUAGE=javascript onclick='return cmdExportar_onclick()' style='width:60px;' title='Exportar programación de la siguiente semana'>
    	</td>
        <!--<td class='ColumnTD' align="center">
        	
<INPUT class=button type='button' value='Ver SAP' id='cmdVersap' name='cmdVersap'  LANGUAGE=javascript onclick='return cmdVersap_onclick()' style='width:60px;'>

        	<INPUT class=button type='button' value='Exportar' id='cmdVercronograma' name='cmdVercronograma'  LANGUAGE=javascript onclick='return cmdVercronograma_onclick()' style='width:60px;'>
       	</td>-->
        <!--<td class='ColumnTD' align="center">
        	<INPUT class=button type='button' value='Registrar Servicio' id='cmdRegistrarServicio' name='cmdRegistrarServicio'  LANGUAGE=javascript onclick='return cmdRegistrarServicio_onclick()' style='width:60px;'>
       	</td>-->
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

    $from  = " empleado_area ";
    $from .= " inner join empleados on empleado_area.empleado_codigo = empleados.empleado_codigo ";
    $from .= " inner join areas on areas.area_codigo = empleado_area.area_codigo";

    $objr->setFrom($from);
    $where = " empleado_area.area_codigo in ( select area_codigo from ca_controller where empleado_codigo= " . $usuario . " and activo=1) and " ;
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
    $arrAlias[4] = "Disponibilidad";
    $arrAlias[5] = "Combinación";

    // Arreglo de los Campos de la consulta
    //$cadena="cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + cast(te.tc_codigo as varchar) + '_' + e.empleado  + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103)";
    $arrCampos[0] = "empleados.empleado_codigo";
    $arrCampos[1] = "empleados.empleado_apellido_paterno + ' ' + empleados.empleado_apellido_materno + ' ' + empleados.empleado_nombres "; 
    $arrCampos[2] =	"empleados.empleado_dni";
    //$arrCampos[3] =	"te_semana";
    //$arrCampos[4] = "tc.tc_codigo_sap";
    $arrCampos[3] = "areas.area_descripcion";
    //$arrCampos[4] = "case when empleados.empleado_codigo = (select empleado_codigo from wfm_combinacion_empleado where empleado_codigo = empleados.empleado_codigo and fecha_inicio = convert(datetime , '" . $te_fecha_inicio . "' , 103) and fecha_fin= convert(datetime,'" . $te_fecha_fin . "',103) group by empleado_codigo) then 'SI' else 'NO' end";

    $arrCampos[4] = "case 
                               when empleados.empleado_codigo = (select empleado_codigo from wfm_empleado_disponibilidad where empleado_codigo = empleados.empleado_codigo and fecha between convert(datetime , '" . $te_fecha_inicio . "' , 103) and convert(datetime,'" . $te_fecha_fin . "',103) group by empleado_codigo) then '<center><img src=../../Images/asistencia/inline011.gif border=0 style=cursor:hand title=Disponibilidad border=0 onclick=VerDetalle(''' + convert(varchar , empleados.empleado_codigo)+ ''',' + '''$te_semana''' + ',' + '''$te_anio''' +  ') /></center>' end";

    $arrCampos[5] = "case when empleados.empleado_codigo = (select empleado_codigo from wfm_combinacion_empleado where empleado_codigo = empleados.empleado_codigo and fecha_inicio = convert(datetime , '" . $te_fecha_inicio . "' , 103) and fecha_fin= convert(datetime,'" . $te_fecha_fin . "',103) group by empleado_codigo) then '<center><img src=../../Images/historia.gif border=0 style=cursor:hand title=Combinacion Programada border=0 onclick=VerDetalleCombinacion(''' + convert(varchar , empleados.empleado_codigo)+ ''',' + '''$te_semana'''  +  ',' + '''$te_anio'''  + ') /></center>' end";
    $objr->setAlias($arrAlias);
    $objr->setCampos($arrCampos);

    $body = $objr->Construir();  //ejecutar
    //echo '<br>SQL: '. $objr->getmssql() . '<br>';
    $objr = null;
    echo $body;
    echo "<br>";
    echo Menu("../menu.php");

?>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="hddrango" name="hddrango" value=""/>
<input type="hidden" id="hddinicio" name="hddinicio" value="<?php echo $te_fecha_inicio ?>"/>
<input type="hidden" id="hddfin" name="hddfin" value="<?php echo $te_fecha_fin ?>"/>
<input type="hidden" id="hddarea" name="hddarea" value="<?php if(isset($area)) echo $area ?>"/>
</form>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>