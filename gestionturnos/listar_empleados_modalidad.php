<?php header("Expires: 0"); 
  // ini_set('display_errors', 'On');
  //   error_reporting(E_ALL);
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../includes/MyGrillaEasyUI.php");

require_once("../../Includes/MyCombo.php");



$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$body="";
$npag = 1;
$orden = "empleado";
$buscam = "";
$torder="ASC";
$hddini="";
$hddfin="";

$modalidad_id = 0;
$cbohorarios = 0;

if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["modalidad_id"])) $modalidad_id = $_POST["modalidad_id"];
if (isset($_POST["cbohorarios"])) $cbohorarios = $_POST["cbohorarios"];

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
    	//$te_fecha_inicio = "";
    	//$te_fecha_fin = "";
    }
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
			alert('!ERROR! Hubo problemas al realizar esta operacion, verifique los datos y reintente');
			</script>
			<?php
		}
	}
    
    if ($_POST["hddaccion"]=='EXP'){
        ?>
        <script>
        var usuario = '<?php echo $_SESSION["empleado_codigo"]?>';
        window.open('exportar_empleados_modalidad.php?usuario=' + usuario);
        //location.href='exportar_empleados_modalidad.php?usuario=' + usuario;
        </script>
        <?php
	}
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <title><?php echo tituloGAP() ?>Listado de personal con cambio de modalidad</title>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <script language="JavaScript" src="../../default.js"></script>
    <script language="JavaScript" src="../jscript.js"></script>
<?php  require_once('../includes/librerias_easyui.php');?>



    <link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>


<script>
function MostrarGrilla(){
    document.frm.submit();
}

function cmdSeleccionar(){
    var registro = SeleccionMultiple();
    var arr = 1;
    var horario = document.frm.cbohorarios.value;
    CenterWindow("listar_turnos.php?empleados=" + registro + "&horario=" + horario , "ModalChild",900,450,"yes","center");
    /*
    if (rpta != '' ) {
        var arr = rpta.split("_");
		CenterWindow("../gestionturnos/turnos_empleado_dia.php?empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio=" + arr[2], "ModalChild",400,250,"yes","center");
	}
    */
}

function cmdLog_onclick() {
    self.location.href="main_turnos_log.php";
}

function sel_fila(n){
    alert(n);
}


function exportar(){
	document.frm.hddaccion.value="EXP";
	document.frm.submit();
}
</script>

</head>


<body class="PageBODY" onload="return WindowResize(10,20,'center')" >

<center class="FormHeaderFont">Consulta de Movimientos de Cambio de Modalidad  <br/> 
</center>
<br />
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
<tr>
    <td><input class="buttons" type='button' value='Exportar Excel' id='cmdExportar' name='cmdExportar' onclick='return exportar()' style='width:150px;'/></td>
</tr>
</table>    
<!--
<table class='FormTable' border="0" cellpadding="1" cellspacing="1" width='100%' id='tblOpciones'>
<tr>
    <td><input class="button" type='button' value='Asignar CS' id='cmdAsignarCS' name='cmdAsignarCS' onclick='return cmdSeleccionar()' style='width:150px;'/></td>
    <td width="50%">&nbsp;</td>
    <td  class="texto"><b>Modalidad:</b></td>
    <td class='ColumnTD' >
    <?php 
        $sql = "select 	distinct modalidad.Item_Codigo, modalidad.Item_Descripcion
                from Empleado_Movimiento em
                	inner join Movimiento_cambios mc on mc.Emp_Mov_codigo = em.Emp_Mov_codigo 
                	inner join empleados e on em.Empleado_Codigo = e.empleado_codigo and e.Estado_Codigo = 1
                	inner join items modalidad on modalidad.Item_Codigo = mc.cc_modalidad_codigo
                	inner join Empleado_Area ea on e.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1
                	inner join areas a on a.Area_Codigo = ea.Area_Codigo
                where em.Movimiento_codigo = 28 and em.Estado_codigo in (1,6) 
                	and em.Emp_Mov_Fecha_Inicio > getdate() ";
        
        $combo->query = $sql;
        $combo->name = "modalidad_id";
        $combo->value = $modalidad_id."";
        $combo->more = " class='Select'  alt='Modalidad' onchange='MostrarGrilla()'";
        $rpta = $combo->Construir();
        echo $rpta;
    ?>
    </td>
    <td class="texto"><b>Horario:</b></td>
    <td class='ColumnTD'>
        <?php
            //$modalidad = isset($modalidad)?$modalidad:0;
			$ssql=" select items.item_codigo , items.item_descripcion ";
			$ssql.=" from modalidad_horario ";
			$ssql.=" inner join items on modalidad_horario.horario_codigo = items.item_codigo ";
			$ssql.=" where modalidad_horario.modalidad_codigo in ('74', '77') ";
			$ssql.=" and modalidad_horario.modalidad_codigo in ( '" . $modalidad_id . "') and items.item_activo=1";
            $ssql.=" and items.item_codigo in (492,254,704,84,706) ";
			$combo->query = $ssql;
			$combo->name = "cbohorarios";
			$combo->value = $cbohorarios;
			$combo->more = "class=input onchange='MostrarGrilla()'";
			$rpta = $combo->Construir();
			echo $rpta;

		?>
    </td>
</tr>
</table>
-->


<?php
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
	
	$from  = " Empleado_Movimiento em
            	inner join Movimiento_cambios mc on mc.Emp_Mov_codigo = em.Emp_Mov_codigo 
            	inner join empleados e on em.Empleado_Codigo = e.empleado_codigo and e.Estado_Codigo = 1
            	inner join items modalidad on modalidad.Item_Codigo = mc.cc_modalidad_codigo
            	inner join Empleado_Area ea on e.Empleado_Codigo = ea.Empleado_Codigo and ea.Empleado_Area_Activo = 1
            	inner join areas a on a.Area_Codigo = ea.Area_Codigo 
                inner join items horario on horario.item_codigo = mc.cc_horario_codigo
	            inner join estados  on em.Estado_codigo = estados.Estado_Codigo";
	$objr->setFrom($from);
	
    $where = " em.Movimiento_codigo = 28 and em.Estado_codigo in (1,6) ";
	$where .= "           and em.Emp_Mov_Fecha_Inicio > getdate() ";
    
	$objr->setWhere($where);
    
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(true);
	// Arreglo de Alias de los Campos de la consulta
        $arrAlias[0] = "root";
        $arrAlias[1] = "DNI";
        $arrAlias[2] = "Empleado";
        $arrAlias[3] = "area";
        $arrAlias[4] = "fecha_inicio";
        $arrAlias[5] = "modalidad";
        $arrAlias[6] = "horario";
        //$arrAlias[7] = "CS_Actual";
        $arrAlias[7] = "estado";
    
        // Arreglo de los Campos de la consulta
        $arrCampos[0] = "rtrim(ltrim(str(e.Empleado_Codigo))) + '-' + rtrim(ltrim(str(mc.Emp_Mov_codigo)))";
        $arrCampos[1] = "e.Empleado_Dni"; 
        $arrCampos[2] =	"e.Empleado_Apellido_Paterno + ' ' + e.Empleado_Apellido_Materno + ' ' + e.Empleado_Nombres";
        $arrCampos[3] =	"a.Area_Descripcion";
        $arrCampos[4] = "CONVERT(varchar(10), em.emp_mov_fecha_inicio,103)";
        $arrCampos[5] = "modalidad.Item_Descripcion";
        $arrCampos[6] = "horario.Item_Descripcion";
        //$arrCampos[7] = "ctc.tc_codigo_sap";
        $arrCampos[7] = "estados.Estado_descripcion";
        //$arrCampos[9] = "'<font id=fnt_' + cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) +  ' style=cursor:hand onclick=cmdVersap_onclick(this.id) title=ver_detalle>Ver</font>'";
    
        $objr->setAlias($arrAlias);
        $objr->setCampos($arrCampos);

        $body = $objr->Construir();  //ejecutar
        //echo $objr->getmssql();
        $objr = null;
        echo $body;
        echo "<br>";
        echo Menu("../menu.php");
?>
<input type="hidden" id="hddaccion" name="hddaccion" value=""/>
<input type="hidden" id="hddrango" name="hddrango" value=""/>
</form>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
<script type="text/javascript">
$(function() {  
	$('.cambiar_hl').click(function(){
		window.open('cambiar_horario_lactancia.php?emp='+$(this).data('empleado'),'Cambiar_Horario_lactancia','width=500,height=300,scrollbars=1');
	})
})

</script>
</body>
</html>
