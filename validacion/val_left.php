<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../../Includes/mantenimiento.php");
require_once("../includes/clsCA_Validacion.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Empleado_Rol.php");

$dni="";
$supervisor_codigo = "";
$rpta="";
$mostrar_mi_grupo="NO";
$mostrar_grupo="NO";//otro grupo
$mostrar_dni="NO";
$tab = "";
$supervisor=0;
$empleado=0;
$indica = "";
$areas_subordinadas="";
if (isset($_POST["supervisor_codigo"])) $supervisor_codigo = $_POST["supervisor_codigo"];
if (isset($_POST["txtDni"])) $dni= $_POST["txtDni"];
if (isset($_GET["tab"])) $tab = $_GET["tab"];
if (isset($_GET["indica"])) $indica = $_GET["indica"];

if($indica=="GS"):
    $mostrar_mi_grupo="SI";
    $tab="";
endif;

if($tab=="OG"):
    $mostrar_grupo = "SI";
    if (isset($_GET["hddsup"])){
        $supervisor_codigo = $_GET["hddsup"];
        $supervisor = $_GET["hddsup"];
    } 
endif;
if($tab=="DNI"):
    $mostrar_dni = "SI";
    if (isset($_GET["hddemp"])) $empleado = $_GET["hddemp"];
    if (isset($_GET["hdddni"])) $dni = $_GET["hdddni"];
endif;


$emp=0;
$o = new ca_validacion();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$id=$_SESSION["empleado_codigo"];
$o->responsable_codigo=$id;

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$u_empleado = new ca_usuarios();
$u_empleado->setMyUrl(db_host());
$u_empleado->setMyUser(db_user());
$u_empleado->setMyPwd(db_pass());
$u_empleado->setMyDBName(db_name());

$u->empleado_codigo = $id;
$r = $u->Identificar();
$nombre_usuario = $u->empleado_nombre;
$area = $u->area_codigo;
$area_descripcion = $u->area_nombre;
$jefe = $u->empleado_jefe; // responsable area
$fecha = $u->fecha_actual;

if (isset($_GET["f"])){
    $fecha = $_GET["f"];
    if (isset($_GET["e"])) $emp = $_GET["e"];//$indica
?>
    <script language="javascript">
        var fecha="<?php echo $fecha ?>";
        window.parent.frames[2].location="val_right.php?fecha=" + fecha + "&empleado_cod=<?php echo $emp ?>"+ "&indica=<?php echo $indica ?>"; 
    </script>
<?php
}

if (isset($_POST["hdd_action"])){
    if (isset($_POST["hdd_fecha_selected"])) $fecha= $_POST["hdd_fecha_selected"];
    if ($_POST["hdd_action"]=='VLA'){
        $supervisor = $supervisor_codigo;
        $mostrar_grupo = "SI";
    }
    
    if ($_POST["hdd_action"]=='VLD'){
        $u_empleado->empleado_dni=$dni;
        $rpta=$u_empleado->getEmpleadoCodigo();
        if($rpta=="1"){
            $u_empleado->empleado_codigo;
            $u_empleado->Identificar();
            if($u_empleado->area_codigo==$u->area_codigo){
                $mostrar_dni="SI";
                $empleado=$u_empleado->empleado_codigo;           
            }else{
                $mostrar_dni="SI";
?>
                <script type="text/javascript">
                    alert("El ejecutivo no pertenece a la jefatura");
                </script>
<?php
            }
        }
    }
    
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Listado de Personal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="../../default.js"></script>

<script type="text/javascript" src="../js/tab/tabber.js"></script>
<link href="../js/tab/example.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="../../views/js/librerias/jquery/app.js"></script>
<script language="JavaScript" src="../tecla_f5.js"></script>
<script language="JavaScript" src="../mouse_keyright.js"></script>
<script language="JavaScript">
$(document).ready(function(){
    //$("#txtDni").keyup(Ext_Sistema.validaN);
    $("#txtDni").keyup(function(e){
        if(e.keyCode!=37 && e.keyCode!=39 && e.keyCode!=46){
            Ext_Sistema.validaN();
        }
    });
});
Ext_Sistema={
    validaN:function(){
        if ($("#txtDni").val()!=""){
            $("#txtDni").val($("#txtDni").val().replace(/[^0-9\.]/g, ""));
        }
    },consultaEjecutivoArea:function(){
        if(document.frm.supervisor_codigo.value=="0"){
            alert('Seleccione Supervisor');
            document.frm.supervisor_codigo.focus();
        }else{
            document.frm.hdd_action.value='VLA';
            document.frm.submit();
        }
    },consultaEjecutivoDNI:function(){
        if(document.frm.txtDni.value.length<8){
            alert("El Dni debe contener 8 digitos");
            document.frm.txtDni.focus();
        }else{
            document.frm.hdd_action.value='VLD';
            document.frm.submit();
        }
    }
}

function Registrar_diario_onclick(){
	//var valor = window.showModalDialog("CA_DiarioGestion.php", "Diario Gestion","dialogWidth:600px; dialogHeight:300px");
	CenterWindow("CA_DiarioGestion.php","ModalChild",600,400,"yes","center");
}
function Registrar_posicion_onclick(){
	//var valor = window.showModalDialog("CA_DiarioGestion.php", "Diario Gestion","dialogWidth:600px; dialogHeight:300px");
	CenterWindow("PosicionesDia.php","ModalChild",600,400,"yes","center");
}

function cmdAprobar_onclick(){
var fecha="<?php echo $fecha ?>";
var responsable_codigo="<?php echo $id ?>";
var area_codigo="<?php echo $area ?>";
CenterWindow("../asistencias/eventos_dia_empleado.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area_codigo=" + area_codigo + "&incidencia_codigo=0","ModalChild",990,700,"yes","center");
}

function cmdVerPermisosPlataformas_onclick(){
 
	 var arr='';
	 var empleadoCodigo = '';
	 for(i=0; i< document.frm.length; i++ ){
	  if (frm.item(i).type=='radio'){
	   if (frm.item(i).checked){

	   	   arr=(frm.item(i).value).split('_');
	   	   empleadoCodigo = arr[0];
	   }
	  }
	 }
	
	if (empleadoCodigo==''){
  		
  		alert('Seleccione Algun Registros de Empleados');
  		return false
 	}

     CenterWindow("../asignaciones/bio_plataforma_empleado_listado.php?empleadoCodigo="+empleadoCodigo,"ModalChild",850,350,"yes","center");

}



function cmdJustificar_onclick(){
var fecha="<?php echo $fecha ?>";
var responsable_codigo="<?php echo $id ?>";
var area_codigo="<?php echo $area ?>";
CenterWindow("../asistencias/Justificacion_Asistencia.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area_codigo=" + area_codigo,"ModalChild",990,700,"yes","center");
}

function cmdRegistrosBatch_onclick(){
var fecha="<?php echo $fecha ?>";
var responsable_codigo="<?php echo $id ?>";
var area_codigo="<?php echo $area ?>";
//CenterWindow("registro_incidencias.php?asistencia=" + arr[0] + "&responsable=" + responsable_codigo + "&empleado=" + empleado_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");
CenterWindow("registro_incidencias_batch.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area=" + area_codigo,"Batch",600,650,"yes","center");
}

function cmdExtensionTurno_onclick(){
	var fecha="<?php echo $fecha ?>";
	var responsable_codigo="<?php echo $id ?>";
	var area_codigo="<?php echo $area ?>";
	CenterWindow("extension_turno.php?responsable_codigo=" +  responsable_codigo + "&fecha=" + fecha + "&area=" + area_codigo,"Batch",700,650,"yes","center");
}

function escribirFecha() {
  campoDeRetorno.value = dia + "/" + mes + "/" + ano;
}

function asistencias(valor){
arr=valor.split('_');
var indica=''+arr[3];//GS = GRUPO DE SUPERVISION A = AREA
var tab_grupo="<?php echo $mostrar_grupo ?>";
var tab_dni="<?php echo $mostrar_dni ?>";
var empleado="<?php echo $empleado ?>";
var dni="<?php echo $dni ?>";
var supervisor="<?php echo $supervisor_codigo ?>";
var tab = '';
if(tab_grupo=="SI") tab='OG';
else if(tab_dni=="SI") tab='DNI';

//alert(indica+tab_grupo+tab_dni+tab);
var fecha="<?php echo $fecha ?>";
  window.parent.frames[2].location="val_right.php?empleado_cod=" + arr[0] + "&fecha=" + fecha + "&tipo=" + arr[2]+"&indica="+indica+"&tab="+tab+"&hddemp="+empleado+"&hdddni="+dni+"&hddsup="+supervisor;
}

function actualizar(){
document.frm.action +="?f=<?php echo $fecha ?>";
document.frm.submit();
}

function nada(){
return;
}

function cmdCambiarTurno_onclick(){
 var arr='';
 for(i=0; i< document.frm.length; i++ ){
  if (frm.item(i).type=='radio'){
   if (frm.item(i).checked){
    arr=(frm.item(i).value).split('_');
   }
  }
 }
 if (arr==''){
  alert('Seleccione Algun Registros de Empleados');
  return false
 }
 if (arr[3]=='1'){
  alert('No se puede modificar turno porque ya se registro el ingreso');
  return false
 }
 var fecha="<?php echo $fecha ?>";
 CenterWindow("../gestionturnos/turnos_empleado_dia_validacion.php?empleado_id=" + arr[0] + "&te_semana=" + 0 + "&te_anio=" + 0 + "&tc_codigo=" + 0 + "&te_fecha_inicio=" + fecha + "&te_fecha_fin=" + fecha, "ModalChild",450,250,"yes","center");
}

</script>
</head>
<body class="PageBODY">
<script type='text/javascript'>
function Go(){return}
</script>
<script type='text/javascript' src='menu_responsable.js'></script>
<script type='text/javascript' src='menu_com_left.js'></script>
<noscript>Your browser does not support script</noscript>
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<br/>
<div class="tabber">
    <div class="tabbertab <?php if($mostrar_mi_grupo == "SI") echo "tabbertabdefault"; ?>">
        <h2>Mi Grupo</h2>
        <br/>
        <center><font color=#333399 STYLE='font-size:12.5px'><b>Mi Grupo&nbsp;</b></font><img border='0' src='../images/invite.gif' title='Mi Grupo'></center>
        <table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
          <tr>
                <td class='ColumnTD' align=center width="5%">Sel</td>
                <td class="ColumnTD" align=center width="10%">Código</td>
                <td class='ColumnTD' align=center width="48%">Nombre</td>
                <td class='ColumnTD' align=center width="7%">Tx.</td>
                <td class='ColumnTD' align=center width="30%">Incidencias</td>
          </tr>
          <?php
            $o->fecha=$fecha;
            $rpta=$o->Listar_mi_grupo($emp);
            echo $rpta;
          ?>
        </table>
        <br/>
        
        <center ><font color=#333399 STYLE='font-size:12.5px'><b>Otros&nbsp;</b></font><img border='0' src='../images/block.gif'  title='Otro Grupo'></center>
        <table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
           <tr>
                <td class="ColumnTD" align="center" width="5%">Sel</td>
                <td class="ColumnTD" align="center" width="10%">Código</td>
                <td class="ColumnTD" align="center" width="48%">Nombre</td>
                <td class="ColumnTD" align="center" width="7%">Tx.</td>
                <td class="ColumnTD" align="center" width="30%">Incidencias</td>
          </tr>
          <?php
            $rpta=$o->Listar_otros($emp);
            echo $rpta;
          ?>
        </table>
        <br/>
		
        <center><font color=#333399 STYLE='font-size:12.5px'><b>Cesados&nbsp;</b></font><img border='0' src='../images/im-aim.png'  title='Otro Grupo'></center>
        <table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
           <tr>
                <td class='ColumnTD' align=center width="5%">Sel</td>
                <td class="ColumnTD" align=center width="10%">Código</td>
                <td class='ColumnTD' align=center width="85%">Nombre</td>
          </tr>
          <?php
            $rpta=$o->Listar_cesados($emp);
            echo $rpta;
          ?>
        </table>
        <input type="button" style="width:0; heigth:0" value="ok" id="cmdx" name="cmdx" onClick="actualizar()"/>
    </div>

    <div class="tabbertab <?php if($mostrar_grupo == "SI") echo "tabbertabdefault"; ?>">
        <h2>Otro Grupo</h2>
        <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td class='ColumnTD' align="left">
            Supervisor&nbsp;
            <?php
                $combo = new MyCombo();
                $combo->setMyUrl(db_host());
                $combo->setMyUser(db_user());
                $combo->setMyPwd(db_pass());
                $combo->setMyDBName(db_name());
                $areas_subordinadas=$u->obtener_areas_responsable();
                        
                $ssql="SELECT  CA_Empleado_Rol.Empleado_codigo,  ";
                $ssql.= "     Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS empleado ";
                $ssql.= " FROM  CA_Empleado_Rol INNER JOIN ";
                $ssql.= "       Empleado_Area ON CA_Empleado_Rol.Empleado_codigo = Empleado_Area.Empleado_Codigo INNER JOIN ";
                $ssql.= "       Empleados ON Empleado_Area.Empleado_Codigo = Empleados.Empleado_Codigo ";
                $ssql.= " WHERE (Empleado_Area.Empleado_Area_Activo = 1) ";
                $ssql.= " 	AND (CA_Empleado_Rol.Rol_Codigo = 1) ";
                $ssql.= " 	and area_codigo in (" . $areas_subordinadas . ") ";
                $ssql.= " 	and Empleados.Empleado_Codigo <> ".$id." ";
                $ssql.= " 	and empleados.estado_codigo=1 AND CA_Empleado_Rol.EMPLEADO_ROL_ACTIVO=1";
                $ssql.= " order by 2 ";

                $combo->query = $ssql;
                $combo->name = "supervisor_codigo";
                $combo->value = $supervisor_codigo."";
                $combo->more = "class=select style='width:80%' onchange='javascript:Ext_Sistema.consultaEjecutivoArea();'";
                $rpta = $combo->Construir();        
                echo $rpta;
            ?>
            <img src="../images/search16.png" style="cursor:pointer;" onClick="javascript:Ext_Sistema.consultaEjecutivoArea();" title="Consulta Grupo"/>
            </td>
	</tr>
        </table>
        
        <table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td class='ColumnTD' align=center width="5%">Sel</td>
            <td class="ColumnTD" align=center width="10%">Código</td>
            <td class='ColumnTD' align=center width="48%">Nombre</td>
            <td class='ColumnTD' align=center width="7%">Tx.</td>
            <td class='ColumnTD' align=center width="30%">Incidencias</td>
        </tr>
          <?php
            if($mostrar_grupo == "SI"):
                $o->indica = "A";
                $o->responsable_codigo=$supervisor;
                $o->fecha=$fecha;
                $rpta=$o->Listar_mi_grupo($supervisor);
                echo $rpta;
            endif;
          ?>
        </table>
        
    </div>
    
    <div class="tabbertab <?php if($mostrar_dni == "SI") echo "tabbertabdefault"; ?>">
        <h2>Por DNI</h2>
        <table width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td class="ColumnTD" align="left" width="30%">
                Dni&nbsp;
                <input type="text" class="input" id="txtDni" name="txtDni" size="11" maxlength="8" value="<?php echo $dni;?>"/>
            </td>
            <td class="ColumnTD" align="left" width="5%">
                <img src="../images/search16.png" style="cursor:pointer;" onClick="javascript:Ext_Sistema.consultaEjecutivoDNI();" title="Consulta Ejecutivo"/>
            </td>
            <td class="ColumnTD" width="60%"></td>
        </tr>
        </table>
        
        <table class='FormTable' width="100%" align='center' border="0" cellspacing="1" cellpadding="0">
        <tr>
            <td class='ColumnTD' align=center width="5%">Sel</td>
            <td class="ColumnTD" align=center width="10%">Código</td>
            <td class='ColumnTD' align=center width="48%">Nombre</td>
            <td class='ColumnTD' align=center width="7%">Tx.</td>
            <td class='ColumnTD' align=center width="30%">Incidencias</td>
        </tr>
          <?php
            if($mostrar_dni == "SI"):
                $o->indica = "A";
                $o->fecha=$fecha;
                $rpta=$o->Listar_Empleado_Area($empleado);
                echo $rpta;
            endif;
          ?>
        </table>
    </div>
</div>
	<input type="hidden" name="hdd_fecha_selected" id="hdd_fecha_selected" value="<?php echo $fecha;?>"/>
        <input type="hidden" name="hdd_action" id="hdd_action" value=""/>
</form>
</body>
</html>