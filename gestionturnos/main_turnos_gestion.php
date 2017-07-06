<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../includes/MyGrillaEasyUI.php");
// require_once("../../Includes/MyGrilla.php");
require_once("../../Includes/MyCombo.php");
$te_semana= date('W');
$te_anio=date('Y');
$te_fecha_inicio="";
$te_fecha_fin="";

$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();
$rpta = $e->Query_Numero_Semana();
if ($rpta=='OK'){
	$te_semana= $e->te_semana;
	$te_anio=$e->te_anio;
	$te_fecha_inicio=$e->te_fecha_inicio;
	$te_fecha_fin=$e->te_fecha_fin;
}
$rango = "";
$rpta = "";
$body="";
$npag = 1;
$orden = "te_semana,empleado";
$buscam = "";
$torder="ASC";


if (isset($_POST["pagina"])) $npag = $_POST["pagina"];
if (isset($_POST["orden"])) $orden = $_POST["orden"];
if (isset($_POST["buscam"])) $buscam = $_POST["buscam"];

if (isset($_GET["pagina"])) $npag = $_GET["pagina"];
if (isset($_GET["orden"])) $orden = $_GET["orden"];
if (isset($_GET["buscam"])) $buscam = $_GET["buscam"];

if (isset($_POST["cboTOrden"])) $torder = $_POST["cboTOrden"];
if (isset($_POST["te_semana"])) $te_semana = $_POST["te_semana"];
if (isset($_POST["te_anio"])) $te_anio = $_POST["te_anio"];
if (isset($_POST["te_fecha_inicio"])) $te_fecha_inicio = $_POST["te_fecha_inicio"];
if (isset($_POST["te_fecha_fin"])) $te_fecha_fin = $_POST["te_fecha_fin"];

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
/*$e = new ca_turnos_empleado();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();*/
$e->te_fecha_inicio = $te_fecha_inicio;
$e->te_fecha_fin = $te_fecha_fin;
$e->te_anio = $te_anio;
$e->te_semana = $te_semana;
$rpta = $e->Query_Turno_Fechas();
if ($rpta!='OK') $rango = "No Se Ha Programado Esta Semana";
else{
	$rango = "Desde : " . $e->te_fecha_inicio . " Hasta : " . $e->te_fecha_fin;
	$te_fecha_inicio = $e->te_fecha_inicio;
	$te_fecha_fin = $e->te_fecha_fin;
}

$esadmin="NO";
$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin=$e->Query_Rol_Admin();
$essuper="NO";
$essuper=$e->Query_Rol_Super();
$esgtr="NO";
$esgtr=$e->Query_Rol_Numero(16);
//mcortezc@atentoperu.com.pe
$esgtr_area="NO";
$esgtr_area=$e->Query_Rol_Numero(17);


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
<title><?php echo tituloGAP() ?>- Gestion de Turnos </title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<script language="JavaScript" src="../no_teclas.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>

<?php  require_once('../includes/librerias_easyui.php');?>

<script>
var url_jr="<?php echo $url_jreportes ?>";

function cmdAdicionar_onclick() {
    self.location.href="turnos_empleado_job.php";
}
function cmdModificar_onclick() {
    var rpta=PooGrilla.Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
    	self.location.href="turnos_empleado_upd.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>&empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio="+arr[2] + "&tc_codigo=" + arr[3] + "&te_fecha_inicio=" + arr[5] + "&te_fecha_fin=" + arr[6] + "&dni=" + arr[7];
    }
}
function cmdGestiondia_onclick(){
    var rpta=PooGrilla.Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
		CenterWindow("../gestionturnos/turnos_empleado_dia.php?empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio=" + arr[2]  + "&tc_codigo=" + arr[3] + "&te_fecha_inicio=" + arr[5] + "&te_fecha_fin=" + arr[6], "ModalChild",450,250,"yes","center");
	}
}
/**
 * function cmdVersap_onclick(){
 *     var rpta=Registro();
 *     if (rpta != '' ) {
 *         var arr = rpta.split("_");
 * 		window.showModalDialog("../gestionturnos/programacion_empleado.php?empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio=" + arr[2] + "&te_fecha_inicio=" + arr[5] + "&te_fecha_fin=" + arr[6],'VerSAP','dialogWidth:600px; dialogHeight:320px');
 * 	}
 * }
 */
 
 function cmdVersap_onclick(valor){
    //alert(valor);
    var arr = valor.split("_");
    //alert(arr[1]);
    Ext_Dialogo.genera_Dialog(700,350,'VerSAP',"../gestionturnos/programacion_empleado.php?empleado_id=" + arr[1] + "&te_semana=" + arr[2] + "&te_anio=" + arr[3] + "&te_fecha_inicio=" + arr[4] + "&te_fecha_fin=" + arr[5], 'div_ver',100,100);

}


function cmdExportar_onclick(){
    var tmpf = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(" ");
    var x = screen.width/2 - 200/2;
    var y = screen.height/2 - 150/2;

    window.open(url_jr + "Gap/exportar_gestion_turno.jsp?fecha_desde=" + tmpf[3] + "&fecha_hasta=" + tmpf[5],'Exporta','height=150,width=200,left='+x+',top='+y);
}

function cmdImportar_onclick(){
	CenterWindow("../gestionturnos/subir_archivo.php" ,"Reporte",800,600,"yes","center");
}

function cmdImportarg_onclick(){
	CenterWindow("../gestionturnos/subir_archivog.php" ,"Reporte",800,600,"yes","center");
}

function enviar_semana(){
document.frm.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
document.frm.submit();
}

function Finalizar(){
    Ext_Dialogo.close_Dialog('div_ver');
}

function cmdVerHis_onclick(empleado_id, te_semana, te_anio){
    //alert('hi');  

  Ext_Dialogo.genera_Dialog(800,350,'Programacion Empleado',"../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio, 'div_histo',110,100);

}


</script>

</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >

<?php
	$sql =" select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3 ";
    $combo->datefirst = " set datefirst 1";
	$combo->query = $sql;
?>
<CENTER class="FormHeaderFont">Gestión de Turnos <br> <?php echo $rango ?></CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="center">
		Año:&nbsp;<select class='select' name='te_anio' id='te_anio' onchange="javascrip:enviar_semana();">
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
			$combo->more = "class='Select' style='width:230px' onchange='javascrip:enviar_semana();'";
			$rpta = $combo->Construir_Opcion("---Seleccione---");
			echo $rpta;
		  ?>
        </td>
        <td class='ColumnTD' align="center" >
            <INPUT class="buttons" type='button' value='Modificar Programacion' id='cmdModificar' name='cmdModificar'  LANGUAGE=javascript onclick='return cmdModificar_onclick()' style='width:150px;'>
        	<INPUT class="buttons" type='button' value='Importar Suplencia' id='cmdImportarg' name='cmdImportarg'  LANGUAGE=javascript onclick='return cmdImportarg_onclick()' style='width:130px;'>
        	<INPUT class="buttons" type='button' value='Modificar Turno Del Dia' id='cmdGestiondia' name='cmdGestiondia'  LANGUAGE=javascript onclick='return cmdGestiondia_onclick()' style='width:150px;'>
       	</td>
        <td class='ColumnTD' align="center">
        	<!--
<INPUT class=button type='button' value='Ver SAP' id='cmdVersap' name='cmdVersap'  LANGUAGE=javascript onclick='return cmdVersap_onclick()' style='width:80px;'>
-->
        	<INPUT class="buttons" type='button' value='Exportar' id='cmdExportar' name='cmdExportar'  LANGUAGE=javascript onclick='return cmdExportar_onclick()' style='width:80px;' title='Exportar En Formato Suplencias'>
			<input type="hidden" id="te_fecha_inicio" name="te_fecha_inicio" value="<?php echo $te_fecha_inicio;?>">
			<input type="hidden" id="te_fecha_fin" name="te_fecha_fin" value="<?php echo $te_fecha_fin;?>">
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
if ($esadmin!='OK' and $esgtr!='OK'){//no es administrador y no es gtr toda la empresa
        $from .= " inner join areas a on a.area_codigo=e.area_codigo ";
}
$objr->setFrom($from);
//	$where = " te.te_semana=" . $te_semana . " and te.te_anio=" . $te_anio;
$where = " te.te_fecha_inicio=convert(datetime,'".$te_fecha_inicio."',103) and te.te_fecha_fin=convert(datetime,'".$te_fecha_fin."',103) ";	
	// $where.= " and te.empleado_codigo IN (select ca.empleado_codigo  from ca_asignacion_empleados ca inner join vDatosTotal vt on vt.Empleado_Codigo = ca.Empleado_Codigo where Asignacion_Activo = 1 and Responsable_Codigo = ".$_SESSION['empleado_codigo'].")";
	if ($esadmin!='OK' && $esgtr!='OK'){//no es administrador y no es gtr toda la empresa
            //echo 'no es admin ni gtr';
		if ($essuper!='OK'){//no es supervisor
                    //mcortezc@atentoperu.com.pe
                    if($esgtr_area=='OK'){//tiene rol gtr area
                        //echo 'filtrando rol gtr_area';
                        $where.= " and a.area_codigo = ".$area;
                    }else{
                        //echo 'other';
                        //mcortezc
                        /*
                        $where.= " and a.area_codigo in "; 
			$where.= " (select area_codigo from ca_controller ";
			$where.= " where empleado_codigo=".$_SESSION['empleado_codigo']." and activo=1 ";
			$where.=" union select area_codigo from vdatos where empleado_codigo=".$_SESSION['empleado_codigo']." ) ";
                        */
                        
                        //mcortezc
                        $where.=" and 1= ";
                           $where.=" case when exists(select Area_Codigo ";
                              $where.=" from CA_Controller ";
                              $where.=" where Area_Codigo = 0 ";
                                 $where.=" and Empleado_Codigo=".$_SESSION['empleado_codigo']." and activo=1) ";
                           $where.=" then 1 else ";
                              $where.=" case when exists(select area_codigo ";
                                 $where.=" from CA_Controller where Area_Codigo = a.area_codigo ";
                                    $where.=" and Empleado_Codigo = ".$_SESSION['empleado_codigo']." ";
                                    $where.=" and activo=1) ";
                                    $where.=" OR ";
                                 $where.=" exists(select area_codigo ";
                                 $where.=" from vdatos where empleado_codigo = ".$_SESSION['empleado_codigo']." ";
                                    $where.=" and area_codigo = a.area_codigo) ";
                              $where.=" then 1 else 0 end ";
                           $where.=" end ";
                        
                        
                    }	
                    
		}else{
                    //echo 'es super';
			$where.= " and a.area_codigo = ".$area; 
		}
	}
        
$objr->setWhere($where);
$objr->setSize(20);
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
// Arreglo de los Campos de la consulta
$arrCampos[0] = "cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + cast(te.tc_codigo as varchar) + '_' + e.empleado_dni  + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) + '_' + e.empleado_dni ";
$arrCampos[1] = "empleado"; 
$arrCampos[2] =	"empleado_dni";
$arrCampos[3] =	"te_semana";
$arrCampos[4] = "tc.tc_codigo_sap";
$arrCampos[5] = "e.area_descripcion";
$arrCampos[6] = "cargo_descripcion";
$arrCampos[7] = "modalidad_descripcion";
$arrCampos[8] = "'<center><img id=img_' + cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) + ' src=''../../Images/asistencia/inline011.gif'' border=0 style=cursor:hand onclick=cmdVersap_onclick(this.id) title=Programacion_Semanal></center>'";
    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
?>
<input type="hidden" id="hddaccion" name="hddaccion" value="">
<input type="hidden" id="hddrango" name="hddrango" value="">
</form>
<div id="div_ver"></div>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div> 
</body>
</html>
