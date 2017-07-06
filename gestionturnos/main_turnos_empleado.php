<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
//require_once("../../includes/Seguridad.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Turnos_Empleado.php"); 
require_once("../../Includes/clsEmpleados.php");
require_once("../includes/MyGrillaEasyUI.php");
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
//echo $e->te_semana;
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
$hddini="";
$hddfin="";

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
}

$esadmin="NO";
$e->empleado_codigo_registro = $_SESSION["empleado_codigo"];
$esadmin=$e->Query_Rol_Admin();
$essuper="NO";
$essuper=$e->Query_Rol_Super();
$esppp="NO";
$esppp=$e->Query_Rol_Numero(15);
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

//echo $te_semana;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Programación de Turnos al Personal</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="AUTHOR" Content="TUMI Solutions S.A.C.">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link rel="stylesheet" type="text/css" href="../style/tstyle.css"/>

<?php  require_once('../includes/librerias_easyui.php');?>

<script>
var url = 'cambiar_horario_lactancia.php';
function cmdAdicionar_onclick() {
	self.location.href="turnos_empleado_job.php?hddbuscar=OK";
}

function cmdModificar_onclick() {
    var rpta=PooGrilla.Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
    	self.location.href="turnos_empleado_upd.php?pagina=<?php echo $npag ?>&buscam=<?php echo $buscam ?>&orden=<?php echo $orden ?>&torden=<?php echo $torder ?>&empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio="+arr[2] + "&tc_codigo=" + arr[3] + "&te_fecha_inicio=" + arr[5] + "&te_fecha_fin=" + arr[6];
    }
}

function cmdGestiondia_onclick(){
    var rpta=PooGrilla.Registro();
    if (rpta != '' ) {
        var arr = rpta.split("_");
		CenterWindow("../gestionturnos/turnos_empleado_dia.php?empleado_id=" + arr[0] + "&te_semana=" + arr[1] + "&te_anio=" + arr[2], "ModalChild",400,250,"yes","center");
	}
}

function cmdVersap_onclick(valor){
    //alert(valor);
    var arr = valor.split("_");
    //alert(arr[1]);

    Ext_Dialogo.dialogCenter(700,350,'VerSAP',"../gestionturnos/programacion_empleado.php?empleado_id=" + arr[1] + "&te_semana=" + arr[2] + "&te_anio=" + arr[3] + "&te_fecha_inicio=" + arr[4] + "&te_fecha_fin=" + arr[5], 'div_ver');


}

function Finalizar(){
    Ext_Dialogo.close_Dialog('div_ver');
}

function cmdVerHis_onclick(empleado_id, te_semana, te_anio){
    //alert('hi');  

  Ext_Dialogo.dialogCenter(800,350,'Programacion Empleado',"../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio, 'div_histo');

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
    var tmpf = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(" ");
    CenterWindow("../gestionturnos/reporte_turnos.php?te_fecha_inicio="+tmpf[3]+"&te_fecha_fin="+tmpf[5] ,"Reporte",900,600,"yes","center");
//  frames['ifr_procesos'].location.href = "procesos.php?opcion=exportar&te_semana=" + document.getElementById('te_semana').value + "&te_anio=" + document.getElementById('te_anio').value;
}

function cmdPublicar_onclick(){
	var tmpf = document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(" ");
	CenterWindow("../gestionturnos/publicar_turnos.php?te_fecha_inicio="+tmpf[3]+"&te_fecha_fin="+tmpf[5] ,"Reporte",900,600,"yes","center");
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

function enviar_semana(){
document.frm.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
document.frm.submit();
}

function cmdLog_onclick() {
    self.location.href="main_turnos_log.php";
}

function sel_fila(n){
    alert(n);
}
function cmdHorarioLactancia(val){
    Ext_Dialogo.dialogCenter(500,300,'Cambiar horario lactancia','cambiar_horario_lactancia.php?emp='+$(val).data('empleado'), 'div_horario');


}


function Finalizar2(){
    Ext_Dialogo.close_Dialog('div_horario');
}

function saveUser(){
            $('#fm').form('submit',{
                url: url,
                onSubmit: function(){

                    return $(this).form('validate');
                },
                success: function(result){
                    var result = eval('('+result+')');
                    if (result.success == 'NO'){
                        // $.messager.show({
                        //     title: 'Error',
                        //     msg: result.msg
                        // });
                        alert(result.msg);
                    } else {
                        // $.messager.show({
                        //     title: 'Exito!',
                        //     msg: result.msg
                        // });
                        alert(result.msg);
                        $('#div_horario').dialog('close');      // close the dialog
                    }
                }
            });
        }



$.fn.datebox.defaults.formatter = function(date){
    // date = new Date();
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();

    if(m <= 9){
        m = '0'+m;
    }
    if (d <= 9) {
        d = '0'+d;
    }
    return d+'/'+m+'/'+y;
}


$.fn.datebox.defaults.parser = function(s){
    if (!s) return new Date();
    var ss = s.split('/');
        var y = parseInt(ss[2],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[0],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }
}

</script>

</head>


<body class="PageBODY" onLoad="return WindowResize(10,20,'center')" >
<?php
	$sql ="select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3 ";
	$combo->query = $sql;
?>
<CENTER class="FormHeaderFont">Programaci&oacute;n de Turnos <br> <?php echo $rango ?></CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="center">
		A&ntilde;o:&nbsp;<select class='select' name='te_anio' id='te_anio' onchange="javascrip:enviar_semana();">
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
				$combo->datefirst='set datefirst 1';
				$rpta = $combo->Construir_Opcion("---Seleccione---");
				echo $rpta;
			?>
        </td>
        <td class='ColumnTD' align="center">
            <INPUT class="buttons" type='button' value='Programar Semana' id='cmdAdicionar' name='cmdAdicionar' LANGUAGE=javascript onclick='return cmdAdicionar_onclick()' style='width:130px;'>
        	<INPUT class="buttons" type='button' value='Importar Semana' id='cmdImportar' name='cmdImportar'  LANGUAGE=javascript onclick='return cmdImportar_onclick()' style='width:120px;'>
        		<!--<INPUT class=button type='button' value='Importar WFM' id='cmdImportarWFM' name='cmdImportarWFM'  LANGUAGE=javascript onclick='return cmdImportarWFM_onclick()' style='width:120px;'>-->
        	<INPUT class="buttons" type='button' value='Log Cargas' id='cmdLog' name='cmdLog'  LANGUAGE=javascript onclick='return cmdLog_onclick()' style='width:75px;'>
        	<!--<INPUT class=button type='button' value='Replicar' id='cmdDefecto' name='cmdDefecto' LANGUAGE=javascript onclick='return cmdDefecto_onclick()' style='width:60px;' title='Replica programaci�n actual a la siguiente semana'>-->
    	</td>
        <td class='ColumnTD' align="center">
        	<!--
<INPUT class=button type='button' value='Ver SAP' id='cmdVersap' name='cmdVersap'  LANGUAGE=javascript onclick='return cmdVersap_onclick()' style='width:60px;'>
-->
        	<INPUT class="buttons" type='button' value='Exportar' id='cmdVercronograma' name='cmdVercronograma'  LANGUAGE=javascript onclick='return cmdVercronograma_onclick()' style='width:60px;'>
       	</td>
        <td class='ColumnTD' align="center">
        	<INPUT class="buttons" type='button' value='Publicar' id='cmdPublicar' name='cmdPublicar'  LANGUAGE=javascript onclick='return cmdPublicar_onclick()' style='width:60px;'>
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
	if ($esadmin!='OK' and $esppp!='OK'){
		$from .= " inner join areas a on a.area_codigo=e.area_codigo ";
	}
	$objr->setFrom($from);
//	$where = " te.te_semana=" . $te_semana . " and te.te_anio=" . $te_anio;
	$where = " te.te_fecha_inicio=convert(datetime,'".$te_fecha_inicio."',103) and te.te_fecha_fin=convert(datetime,'".$te_fecha_fin."',103) ";	
	if ($esadmin!='OK' and $esppp!='OK'){
		if ($essuper!='OK'){
                        //mcortezc
                        /*
			$where.=" and a.area_codigo in "; 
			$where.=" (select area_codigo from ca_controller ";
			$where.=" where empleado_codigo=".$_SESSION['empleado_codigo']." and activo=1 ";
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
        $arrAlias[8] = "Hr_Lact";
        $arrAlias[9] = "Ver_Pro";
        $arrAlias[10] = "Mod_HLact";

    
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
        $arrCampos[8] = "CASE WHEN EXISTS(SELECT Emp_Mov_codigo FROM Empleado_Movimiento em  where em.Empleado_Codigo = e.empleado_codigo AND Movimiento_codigo = 36 AND Estado_codigo = 1 AND Emp_Mov_Fecha_Fin > GETDATE()) THEN 'SI' ELSE 'NO' END ";
        $arrCampos[9] = "'<center><img id=img_' + cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) + ' src=''../../Images/asistencia/inline011.gif'' border=0 style=cursor:hand onclick=cmdVersap_onclick(this.id) title=Programacion_Semanal ></center>'";
        $arrCampos[10] = "CASE WHEN EXISTS(SELECT Emp_Mov_codigo FROM Empleado_Movimiento em  where Movimiento_codigo in (36,40) AND em.Empleado_Codigo = e.empleado_codigo AND Estado_codigo = 1 AND Emp_Mov_Fecha_Fin > GETDATE()) THEN '<img data-empleado='+RTRIM(LTRIM(STR(e.Empleado_Codigo)))+' border=0 width=15 src=../../Images/asistencia/clock.png  style=cursor:hand onclick=cmdHorarioLactancia(this) />' ELSE '' END ";
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
<div id="div_ver"></div>
<div id="div_horario"></div>
<div style='position:absolute;left:100px;top:900px;display:none' id='div_procesos'>
    <iframe id='ifr_procesos' name='ifr_procesos' width='700px' height='100px' src=''></iframe>
</div>
<script type="text/javascript">
$(function() {  

})

</script>
</body>
</html>
