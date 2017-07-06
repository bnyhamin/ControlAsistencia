<?php header("Expires: 0"); 

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
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

$rango = "";
$rpta = "";
$body="";
$npag = 1;
$orden = "e.empleado";
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

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

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
    Ext_Dialogo.dialogCenter(800,350,'Programacion Empleado',"../gestionturnos/historial_empleado.php?empleado_id=" + empleado_id + "&te_semana=" + te_semana+  "&te_anio=" + te_anio, 'div_histo');
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
	CenterWindow("../gestionturnos/subir_archivoe.php" ,"Reporte",800,600,"yes","center");
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
<CENTER class="FormHeaderFont">CARGA MASIVA DE EVENTOS</CENTER>
<form id=frm name=frm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
    <tr>
        <td class='ColumnTD' align="center"></td>
        <td class='ColumnTD' align="left">184&nbsp;&nbsp;&nbsp;&nbsp;DESASTRE NATURAL - INICIO DE TURNO</td>
        <td class='ColumnTD' align="center"></td>
        <td class='ColumnTD' align="left">DNI[tab]FECHA[tab]EVENTO</td>
    </tr>
    <tr>
        <td class='ColumnTD' align="center">CODIGO DE EVENTOS</td>
        <td class='ColumnTD' align="left">185&nbsp;&nbsp;&nbsp;&nbsp;DESASTRE NATURAL - FIN DE TURNO</td>
        <td class='ColumnTD' align="center">PLANTILLA PARA ARCHIVO DE TEXTO</td>
        <td class='ColumnTD' align="left">-----------------------------------</td>
    </tr>
    <tr>
        <td class='ColumnTD' align="center"></td>
        <td class='ColumnTD' align="left">186&nbsp;&nbsp;&nbsp;&nbsp;DESASTRE NATURAL - DIA COMPLETO</td>
        <td class='ColumnTD' align="center">(separador TABULADOR)</td>
        <td class='ColumnTD' align="left">01234567&nbsp;&nbsp;&nbsp;&nbsp;29/03/2017&nbsp;&nbsp;&nbsp;&nbsp;184</td>
    </tr>
    <tr>
        <td class='ColumnTD' align="center"></td>
        <td class='ColumnTD' align="center"></td>
        <td class='ColumnTD' align="center"></td>
        <td class='ColumnTD' align="left">07654321&nbsp;&nbsp;&nbsp;&nbsp;28/03/2017&nbsp;&nbsp;&nbsp;&nbsp;185</td>
    </tr>
    <tr>
        <td class='ColumnTD' align="center" colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td class='ColumnTD' align="center" colspan="4">
        	<INPUT class="buttons" type='button' value='Importar Eventos Desde Archivo De Texto' id='cmdImportar' name='cmdImportar'  LANGUAGE=javascript onclick='return cmdImportar_onclick()' style='width:320px;cursor:hand;'>
        	<INPUT class="buttons" type='button' value='Log Cargas' id='cmdLog' name='cmdLog'  LANGUAGE=javascript onclick='return cmdLog_onclick()' style='width:75px;'>
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
        $objr->setNoSeleccionable(true);
        $objr->setFont("color=#000000");
        $objr->setFormatoBto("class=button");
        $objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
        $objr->setFormaCabecera(" class=ColumnTD ");
        $objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
        $objr->setTOrder($torder);
	
	$from  = " CA_Eventos ce join CA_Asistencias ca on ce.Empleado_Codigo=ca.Empleado_Codigo and ce.Asistencia_codigo=ca.Asistencia_codigo
                join vDatosTotal e on ce.Empleado_Codigo=e.Empleado_Codigo
                join CA_Incidencias ci on ce.Incidencia_Codigo=ci.Incidencia_codigo 
                join Empleados r on cast(right(cast(ce.observacion as varchar(52)),6) as int)=r.Empleado_Codigo ";
	$objr->setFrom($from);
	$where = " ce.evento_activo=1 and ce.Incidencia_Codigo in (184,185,186) and left(cast(ce.observacion as varchar),5)='CARGA' ";	
	$objr->setWhere($where);
	$objr->setSize(25);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
        $arrAlias[0] = "root";
        $arrAlias[1] = "Dni";
        $arrAlias[2] = "Empleado";
        $arrAlias[3] = "Fecha";
        $arrAlias[4] = "Incidencia";
        $arrAlias[5] = "Desripcion";
        $arrAlias[6] = "Area";
        $arrAlias[7] = "Cargo";
        $arrAlias[8] = "Usuario_Registro";
        // Arreglo de los Campos de la consulta
        $cadena="cast(e.empleado_codigo as varchar) + '_' + e.empleado  + '_' + convert(varchar(10),ca.asistencia_fecha,103)";
        $arrCampos[0] = $cadena;
        $arrCampos[1] = "e.Empleado_Dni"; 
        $arrCampos[2] =	"e.empleado";
        $arrCampos[3] =	"convert(varchar(10),ca.Asistencia_fecha,103)";
        $arrCampos[4] = "ci.Incidencia_codigo";
        $arrCampos[5] = "ci.Incidencia_descripcion";
        $arrCampos[6] = "e.Area_Descripcion";
        $arrCampos[7] = "e.Cargo_descripcion";
        $arrCampos[8] = "r.Empleado_Apellido_Paterno+' '+r.Empleado_Apellido_Materno+' '+r.Empleado_Nombres";
//        $arrCampos[9] = "'<center><img id=img_' + cast(te.empleado_codigo as varchar) + '_' + cast(te.te_semana as varchar) + '_' + cast(te.te_anio as varchar) + '_' + convert(varchar(10),te_fecha_inicio,103) + '_' + convert(varchar(10),te_fecha_fin,103) + ' src=''../../Images/asistencia/inline011.gif'' border=0 style=cursor:hand onclick=cmdVersap_onclick(this.id) title=Programacion_Semanal ></center>'";
//        $arrCampos[10] = "CASE WHEN EXISTS(SELECT Emp_Mov_codigo FROM Empleado_Movimiento em  where Movimiento_codigo in (36,40) AND em.Empleado_Codigo = e.empleado_codigo AND Estado_codigo = 1 AND Emp_Mov_Fecha_Fin > GETDATE()) THEN '<img data-empleado='+RTRIM(LTRIM(STR(e.Empleado_Codigo)))+' border=0 width=15 src=../../Images/asistencia/clock.png  style=cursor:hand onclick=cmdHorarioLactancia(this) />' ELSE '' END ";
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
