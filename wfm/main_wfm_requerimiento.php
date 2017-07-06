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
require_once("../includes/clsCA_Turnos_Empleado.php"); 
//require_once("../../includes/clsEmpleados.php");
require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/MyCombo.php");
//require_once("../../includes/clswfm_empleado_restricciones.php");

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

/*$er = new wfm_empleado_restricciones();
$er->MyUrl = db_host();
$er->MyUser= db_user();
$er->MyPwd = db_pass();
$er->MyDBName= db_name();*/

$rpta = $e->Query_Numero_Semana();
if ($rpta=='OK'){
    $te_semana= $e->te_semana + 1;
    $te_anio=$e->te_anio;
    $semana_actual=$e->te_semana + 1;
}

$rpta = "";
$body="";
$npag = 1;
$orden = "2";
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

/*$temp = explode(" ",$_POST["hddrango"]);
$te_fecha_inicio = $temp[3];
$te_fecha_fin = $temp[5];
*/

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
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/windowfiles/dhtmlwindow.js"></script>
<link rel="stylesheet" href="../../jscripts/dhtmlmodal/modalfiles/modal.css" type="text/css"/>
<script type="text/javascript" src="../../jscripts/dhtmlmodal/modalfiles/modal.js"></script>

<script>
function cmdImportarWFM_onclick(){
    var fechas = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(' ');
    var semana=fechas[0];
    var fecha_inicio = fechas[3];
    var fecha_fin = fechas[5];
    //CenterWindow("subir_archivoWFM.php?te_semana=" + semana + "&te_anio=" + document.getElementById('te_anio').value + "&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin ,"Reporte",600,200,"yes","center");
    windowv=dhtmlmodal.open('Importar Requerimiento WFM', 'iframe', "subir_archivoWFM.php?te_semana=" + semana + "&te_anio=" + document.getElementById('te_anio').value + "&fecha_inicio=" + fecha_inicio + "&fecha_fin=" + fecha_fin, 'Importar Requerimiento WFM', 'width=600px,height=350px,center=1,resize=0,scrolling=1')
    //document.frm.submit();
}
function consultar(){
    document.frm.submit();
}
</script>

</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >
<CENTER class="FormHeaderFont">WFM Requerimiento</CENTER>
<form id=frm name=frm method=post>
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="left">
		Año:&nbsp;<select class='select' name='te_anio' id='te_anio' onchange="consultar();">
		  <?php
		   for($a=$te_anio; $a <= $te_anio; $a++){
                        if ($a==$te_anio) echo "\t<option value=". $a . " selected>". $a ."</option>" . "\n";
                        else echo "\t<option value='". $a . "'>". $a ."</option>" . "\n";
		   }
		  ?>
		 </select> 
		 Semana:&nbsp;
			<?php
                            $ssql=" select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3 ";
                            //$ssql ="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by inicio";
                            $combo->query = $ssql;
                            $combo->datefirst="set datefirst 1 ";
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
        	<INPUT class=button type='button' value='Importar WFM' id='cmdImportarWFM' name='cmdImportarWFM'  LANGUAGE=javascript onclick='return cmdImportarWFM_onclick()' style='width:120px;' <?php if($te_semana < $semana_actual) echo 'disabled';  ?>>
     
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
        $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4' ");
        $objr->setTOrder($torder);
	
	$from  = " wfm_requerimiento ";
	$from .= " inner join v_campanas_clientes on wfm_requerimiento.codigo_campana = v_campanas_clientes.cod_campana  ";
	$from .= " inner join areas on areas.area_codigo = wfm_requerimiento.area";
	$objr->setFrom($from);
	
	//$where = " wfm_requerimiento.semana= " . $te_semana . " and wfm_requerimiento.anio=" . $te_anio . " and wfm_requerimiento.usuario_registro=" . $usuario . " and v_campanas_clientes.exp_activo=1";
	
	$where = " wfm_requerimiento.semana= " . $te_semana . " and wfm_requerimiento.anio=" . $te_anio . " and v_campanas_clientes.coordinacion_codigo in ( select area_codigo from ca_controller where empleado_codigo=" . $usuario .  " and activo=1) and v_campanas_clientes.exp_activo=1";
	
 	$objr->setWhere($where);
 	
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	
        // Arreglo de Alias de los Campos de la consulta
        $arrAlias[0] = "root";
        $arrAlias[1] = "Codigo";
        //$arrAlias[2] = "Anio";
        $arrAlias[2] = "Servicio";
        $arrAlias[3] = "Area";
    
        //$arrAlias[4] = "Hora_Inicio";
        //$arrAlias[5] = "Minuto_Inicio";
        //$arrAlias[6] = "Hora_Fin";
        //$arrAlias[7] = "Minuto_FIn";
        $arrAlias[4] = "Horario";
        $arrAlias[5] = "Lun";
        $arrAlias[6] = "Mar";
        $arrAlias[7] = "Mié";
        $arrAlias[8] = "Jue";
        $arrAlias[9] = "Vie";
        $arrAlias[10] = "Sáb";
        $arrAlias[11] = "Dom";

	// Arreglo de los Campos de la consulta
        $arrCampos[0] = "wfm_requerimiento.wfm_codigo";
        //$arrCampos[1] = "wfm_requerimiento.semana";
        $arrCampos[1] = "v_campanas_clientes.Cod_Campana";
        //$arrCampos[2] =	"wfm_requerimiento.anio";
	$arrCampos[2] = "v_campanas_clientes.Exp_Codigo + ' - ' + v_campanas_clientes.Exp_NombreCorto + ' ' + '(' + isnull(v_campanas_clientes.Cliente_DesCorta,' ') + ')'";
	//$arrCampos[4] = "wfm_requerimiento.hora_inicio";
	//$arrCampos[5] = "wfm_requerimiento.minuto_inicio";
	//$arrCampos[6] = "wfm_requerimiento.hora_fin";
	//$arrCampos[7] = "wfm_requerimiento.minuto_fin ";
	$arrCampos[3] = "areas.area_descripcion";
	$arrCampos[4] = "case when wfm_requerimiento.hora_inicio <= 9 then ('0' + cast(wfm_requerimiento.hora_inicio as varchar)) else (cast(hora_inicio as varchar)) end + ':'+ case  when wfm_requerimiento.minuto_inicio <= 9 then ('0' + cast(wfm_requerimiento.minuto_inicio as varchar))  else (cast(wfm_requerimiento.minuto_inicio as varchar)) end + ' a ' +  case  when wfm_requerimiento.hora_fin <= 9 then ('0' + cast(wfm_requerimiento.hora_fin as varchar))  else (cast(wfm_requerimiento.hora_fin as varchar)) end + ':'+case  when wfm_requerimiento.minuto_fin <= 9 then ('0' + cast(wfm_requerimiento.minuto_fin as varchar))  else (cast(wfm_requerimiento.minuto_fin as varchar))  end";
	$arrCampos[5] = "wfm_requerimiento.rq_dia1";
	$arrCampos[6] = "wfm_requerimiento.rq_dia2";
	$arrCampos[7] = "wfm_requerimiento.rq_dia3";
	$arrCampos[8] = "wfm_requerimiento.rq_dia4";
	$arrCampos[9] = "wfm_requerimiento.rq_dia5";
	$arrCampos[10] = "wfm_requerimiento.rq_dia6";
	$arrCampos[11] = "wfm_requerimiento.rq_dia7";
    
        $objr->setAlias($arrAlias);
        $objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo '<br>SQL: '. $objr->getmssql() . '<br>';
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");

?>

<!--<input type="hidden" id="hddrango" name="hddrango" value="">-->
<input type="hidden" id="hddinicio" name="hddinicio" value="<?php echo $te_fecha_inicio ?>">
<input type="hidden" id="hddfin" name="hddfin" value="<?php echo $te_fecha_fin ?>">

</form>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
</body>
</html>