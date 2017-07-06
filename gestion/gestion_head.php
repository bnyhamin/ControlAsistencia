<?php header("Expires: 0"); ?>
<?php
//session_start();
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsGmenu.php"); 
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/Seguridad.php");

$u = new ca_usuarios();
$u->setMyUrl(db_host());
$u->setMyUser(db_user());
$u->setMyPwd(db_pass());
$u->setMyDBName(db_name());

$m = new mantenimiento();
$m->setMyUrl(db_host());
$m->setMyUser(db_user());
$m->setMyPwd(db_pass());
$m->setMyDBName(db_name());
$empleado_id="";
$u->empleado_codigo = $_SESSION["empleado_codigo"];
$empleado_id=$_SESSION["empleado_codigo"];

$r = $u->Identificar();
$nombre_usuario= $u->empleado_nombre;
$area= $u->area_codigo;
$area_descripcion= $u->area_nombre;
$jefe= $u->empleado_jefe; 
$fecha= $u->fecha_actual;

$area_codigo = '0';
$responsable_codigo='0';
$turno_codigo='0';
$area_id = '0';
$rol_codigo = '0';

if (isset($_POST["txtFecha"])){
    $fecha = $_POST["txtFecha"];
    echo "fecrec".$fecha;
} 
if (isset($_POST["empleado_id"])) $empleado_id = $_POST["empleado_id"];
if (isset($_POST["area_codigo"])) $area_codigo = $_POST["area_codigo"];
if (isset($_POST["rol_codigo"])) $rol_codigo = $_POST["rol_codigo"];
if (isset($_POST["area_id"])) $area_id = $_POST["area_id"];
if (isset($_POST["responsable_codigo"])) $responsable_codigo = $_POST["responsable_codigo"];
if (isset($_POST["turno_codigo"])) $turno_codigo = $_POST["turno_codigo"];

$gc = new gmenu();
$gc->setMyUrl(db_host());
$gc->setMyUser(db_user());
$gc->setMyPwd(db_pass());
$gc->setMyDBName(db_name());

$gc->empleado_codigo=$empleado_id;
//$gc->n_areas=$n_areas;
$rpta=$gc->query_analista();
//$sw_reg = $gc->n_areas;
$gm = new gmenu();
$gm->setMyUrl(db_host());
$gm->setMyUser(db_user());
$gm->setMyPwd(db_pass());
$gm->setMyDBName(db_name());
$gm->empleado_codigo=$empleado_id;
$gm->area_id=$area_id;
$rpta=$gm->query_supervisor();
$area_id = $gm->area_id;
$gj = new gmenu();
$gj->setMyUrl(db_host());
$gj->setMyUser(db_user());
$gj->setMyPwd(db_pass());
$gj->setMyDBName(db_name());
$gj->empleado_codigo=$empleado_id;
$rpta=$gj->query_jefe();
$rol_codigo = $gj->rol_codigo;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Panel de Consulta GAP</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<!-- llama al calendario  -->
<link rel="stylesheet" type="text/css" media="all" href="../js/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/lang/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>
<!-- llama funciones generales -->
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<script language="JavaScript" type="text/javascript">
var fecha_seleccion='';
//agregar_options("area_codigo","0","---Seleccionar---");
function ref_page(){
  sel_area();
  //alert(urlpagina);
  var urlpagina="gestion_right.php";
   window.parent.frames[2].location=urlpagina;
}

function salir(){
   window.parent.location.href='../menu.php';
}

function sel_area(){
    
  borrar_options("responsable_codigo");
  borrar_options("turno_codigo");
  agregar_options("responsable_codigo","0","---Seleccionar---");
  agregar_options("turno_codigo","0","---Seleccionar---");
  document.frames["ift"].document.location.href = "transaccion.php?tipo=1&area_codigo=" + document.frm.area_codigo.value + "&fecha=" + document.frm.txtFecha.value + "&empleado_id=<?php echo $empleado_id ?>&rol_codigo=<?php echo $rol_codigo ?>";
}

function sel_responsable(){
    //alert('xx'+document.frm.responsable_codigo.value);
  borrar_options("turno_codigo");
  agregar_options("turno_codigo","0","---Seleccionar---");
  if (<?php echo $area_id ?> != document.frm.area_codigo.value) {
     //alert('1');
     document.frames["ift"].document.location.href = "transaccion.php?tipo=2&area_codigo=" + document.frm.area_codigo.value + "&fecha=" + document.frm.txtFecha.value + "&empleado_id=<?php echo $empleado_id; ?>&responsable_codigo=" + document.frm.responsable_codigo.value;
  } else {
     //alert('2');
     //document.frames["ift"].document.location.href = "transaccion.php?tipo=2&area_codigo=" + document.frm.area_codigo.value + "&fecha=" + document.frm.txtFecha.value + "&empleado_id=<?php echo $empleado_id ?>&responsable_codigo=<?php echo $empleado_id ?>";
     document.frames["ift"].document.location.href = "transaccion.php?tipo=2&area_codigo=" + document.frm.area_codigo.value + "&fecha=" + document.frm.txtFecha.value + "&empleado_id=<?php echo $empleado_id ?>&responsable_codigo=" + document.frm.responsable_codigo.value;
  }
}

function cambiar() {
 	//document.frm.submit();
 	//transferir();
}

function transferir() {
   var fecha="<?php echo $fecha ?>";
   var empleado="<?php echo $empleado_id ?>";
   var area_codigo="<?php echo $area_codigo ?>";
   var responsable_codigo="<?php echo $responsable_codigo ?>"; 
   var turno_codigo="<?php echo $turno_codigo ?>"; 
   window.parent.frames[1].location="gestion_right.php?fecha=" + fecha + "&empleado_id=" + empleado + "&area_codigo=" + area_codigo + "&responsable_codigo=" + responsable_codigo + "&turno_codigo=" + turno_codigo + "";

}

</script>
</head>
<body class="PageBODY" onLoad="return WindowParentResize(10,20,'center')">
<div id="popCalF" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; VISIBILITY: hidden; WIDTH: 10px">
	<iframe name="popFrameF" id="popFrameF" src="../popcj.htm" frameborder="1" scrolling="no" width="190" height="188"></iframe>
</div>
<form id="frm" name="frm" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<p>
<center class="FormHeaderFont">Panel de Consulta GAP</center></b>
<p>
<!--
<table align='center' width="60%" border="0">
 <tr>
    <td width='45%' align=right><b>Usuario : </b></td>
	<td align=left><font color=#3366CC><b><?php //echo $nombre_usuario?></b></font></td>
 </tr>
</table>
-->
<table width="85%"  border="1" cellspacing="1" cellpadding="0" align="center" >
  <tr>
    <td class="ColumnTD" align="left"  width="50%">
	Fecha :&nbsp;&nbsp;
     <Input  class='Input' type='text' name='txtFecha' id='txtFecha' value="<?php echo $fecha ?>" maxlength='11' style="width:85px;height:18px;" readonly>
     <input id="cmd_fecha_inicio" type="button" value="^" Class="Button"  style="cursor:hand" alt="Seleccionar Fecha" title="Seleccionar Fecha" style="width:18px; height:18px" />&nbsp;&nbsp; 			
	 <button type="button" style="cursor:hand" title="Refresh" style="width:20px; height:21px" onclick="ref_page();"><img src="../images/loading_et.GIF" height="15"></button>&nbsp;&nbsp;
    </td>
    <td class="ColumnTD" align="left" width="50%" >
	<button type="button" style="cursor:hand" onclick="salir();" title="Salir a menú principal"    style="width:24px; height:20px"><img src="../images/biGoingOnline.gif" height="15">
	</button>
	</td>
  </tr>
  </b>
  <tr>
	<td width="50%" align="left" class="ColumnTD">Areas :&nbsp;&nbsp;
<?php
                $combo = new MyCombo();
                $combo->setMyUrl(db_host());
                $combo->setMyUser(db_user());
                $combo->setMyPwd(db_pass());
                $combo->setMyDBName(db_name());

                $rol_code=$gc->getCodigoRol($empleado_id);
                //CONTROLAR COMBO AREAS
                $controlar=TRUE;
                if($gc->getVerificaRol($empleado_id,3)==1){//EXISTE 3.ROL Administrador
                    //echo "rol admin";
                    $controlar=FALSE;
                }else if($gc->getVerificaRol($empleado_id,16)==1){//EXISTE ROL 16.GTR - Suplencia Diaria
                    //echo "rol gtr";
                    $controlar=FALSE;
                }else if($gc->getVerificaRol($empleado_id,9)==1){//EXISTE ROL 9.Analista Multiarea
                    //echo "rol analista multi";
                    $controlar=TRUE;
                }else{//DEFAULT CUALQUIER OTRO ROL SERA VERIFICADO POR EL CONTROLLER
                    //echo "otro rol";
                    $controlar=TRUE;
                }
                
                
                if ($controlar){
                    //query del combo de seleccion de area
                    //echo "controlado";
                    
                    //mcortezc
                    //CONTROLAR ATENTO PERU TODAS LAS AREAS
                    $controlarAtentoPeru=FALSE;
                    if($gc->getVerificaAtentoPeruController($empleado_id)==1){//EXISTE CONTROLLER PARA TODA LA EMPRESA
                        
                        $sql4=" SELECT Area_Codigo,Area_Descripcion FROM Areas WHERE Area_Codigo<>0 and Area_Activo=1 ";
                        $sql4 .=" order by 2 ";
                        $controlarAtentoPeru=TRUE;
                    }else{
                        
                        $sql4="select area_codigo, area_descripcion from vdatos ";
                        $sql4 .="where empleado_codigo = " . $empleado_id . " ";
                        $sql4 .="union ";
                        $sql4 .="select c.area_codigo,a.area_descripcion ";
                        $sql4 .="from ca_controller c left outer join areas a on ";
                        $sql4 .="c.area_codigo = a.area_codigo ";
                        $sql4 .="where empleado_codigo =" . $empleado_id . "  and activo=1 ";
                        $sql4 .=" union ";
                        $sql4 .="SELECT EA.AREA_CODIGO AS area_codigo, A.area_descripcion  ";
                        $sql4 .="FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ";
                        $sql4 .="ON R.EMPLEADO_CODIGO=EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON ";
                        $sql4 .="EA.AREA_CODIGO=A.AREA_CODIGO ";
                        $sql4 .="WHERE R.EMPLEADO_CODIGO= " . $empleado_id . " AND R.ROL_CODIGO=1 ";
                        $sql4 .="AND R.EMPLEADO_ROL_ACTIVO=1 AND EA.EMPLEADO_AREA_ACTIVO=1 ";
                        $sql4 .=" union ";
                        $sql4 .="SELECT DISTINCT A.AREA_CODIGO AS area_codigo, A.area_descripcion ";   
                        $sql4 .="FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ";
                        $sql4 .="ON R.EMPLEADO_CODIGO = EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON ";
                        $sql4 .="EA.AREA_CODIGO=A.AREA_CODIGO or A.AREA_JEFE = EA.AREA_CODIGO ";
                        $sql4 .="WHERE R.EMPLEADO_CODIGO = " . $empleado_id . " AND R.ROL_CODIGO IN (2,6) ";
                        $sql4 .="AND R.EMPLEADO_ROL_ACTIVO=1 AND EA.EMPLEADO_AREA_ACTIVO=1 AND A.AREA_ACTIVO = 1 ";
                        $sql4 .="ORDER BY 2 ";
                    }
                    
                    /*
                    $sql4="select c.area_codigo,a.area_descripcion ";
                    $sql4 .="from ca_controller c left outer join areas a on ";
                    $sql4 .="c.area_codigo = a.area_codigo ";
                    $sql4 .="where empleado_codigo =" . $empleado_id . "  and activo=1 ";
                    $sql4 .=" union ";
                    $sql4 .="SELECT EA.AREA_CODIGO AS area_codigo, A.area_descripcion  ";
                    $sql4 .="FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ";
                    $sql4 .="ON R.EMPLEADO_CODIGO=EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON ";
                    $sql4 .="EA.AREA_CODIGO=A.AREA_CODIGO ";
                    $sql4 .="WHERE R.EMPLEADO_CODIGO= " . $empleado_id . " AND R.ROL_CODIGO=1 ";
                    $sql4 .="AND R.EMPLEADO_ROL_ACTIVO=1 AND EA.EMPLEADO_AREA_ACTIVO=1 ";
                    $sql4 .=" union ";
                    $sql4 .="SELECT DISTINCT A.AREA_CODIGO AS area_codigo, A.area_descripcion ";   
                    $sql4 .="FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ";
                    $sql4 .="ON R.EMPLEADO_CODIGO = EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON ";
                    $sql4 .="EA.AREA_CODIGO=A.AREA_CODIGO or A.AREA_JEFE = EA.AREA_CODIGO ";
                    $sql4 .="WHERE R.EMPLEADO_CODIGO = " . $empleado_id . " AND R.ROL_CODIGO IN (2,6) ";
                    $sql4 .="AND R.EMPLEADO_ROL_ACTIVO=1 AND EA.EMPLEADO_AREA_ACTIVO=1 AND A.AREA_ACTIVO = 1 ";
                    $sql4 .="ORDER BY 2 ";
                    */
                    
        	
        	}else{
                    //echo "no controlado";
                    $sql4="select area_codigo,area_descripcion from areas where area_codigo > 0 order by 2 ";
                }
                

                /*previo
                $rol_code ="0"; 
                $rpta="OK";
                $ssql = "";
                $rpta=$m->conectarme_ado();
                if($rpta=="OK"){
                    $ssql = "select rol_codigo FROM CA_EMPLEADO_ROL where empleado_codigo = " . $empleado_id . " and rol_codigo = 10 and empleado_rol_activo=1";
                    $rs = $m->cnnado->Execute($ssql);
                    $rol_code = $rs->fields[0]->value;
                }
                $rs->close();
                $rs=null;*/
                
                //combo 18.11.2014
                /*if ($rol_code!=10){
                    //query del combo de seleccion de area
                    $sql4="select c.area_codigo,a.area_descripcion ";
                    $sql4 .="from ca_controller c left outer join areas a on ";
                    $sql4 .="c.area_codigo = a.area_codigo ";
                    $sql4 .="where empleado_codigo =" . $empleado_id . "  and activo=1 ";
                    $sql4 .=" union ";
                    $sql4 .="SELECT EA.AREA_CODIGO AS area_codigo, A.area_descripcion  ";
                    $sql4 .="FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ";
                    $sql4 .="ON R.EMPLEADO_CODIGO=EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON ";
                    $sql4 .="EA.AREA_CODIGO=A.AREA_CODIGO ";
                    $sql4 .="WHERE R.EMPLEADO_CODIGO= " . $empleado_id . " AND R.ROL_CODIGO=1 ";
                    $sql4 .="AND R.EMPLEADO_ROL_ACTIVO=1 AND EA.EMPLEADO_AREA_ACTIVO=1 ";
                    $sql4 .=" union ";
                    $sql4 .="SELECT DISTINCT A.AREA_CODIGO AS area_codigo, A.area_descripcion ";   
                    $sql4 .="FROM CA_EMPLEADO_ROL R INNER JOIN EMPLEADO_AREA EA ";
                    $sql4 .="ON R.EMPLEADO_CODIGO = EA.EMPLEADO_CODIGO INNER JOIN AREAS A ON ";
                    $sql4 .="EA.AREA_CODIGO=A.AREA_CODIGO or A.AREA_JEFE = EA.AREA_CODIGO ";
                    $sql4 .="WHERE R.EMPLEADO_CODIGO = " . $empleado_id . " AND R.ROL_CODIGO IN (2,6) ";
                    $sql4 .="AND R.EMPLEADO_ROL_ACTIVO=1 AND EA.EMPLEADO_AREA_ACTIVO=1 AND A.AREA_ACTIVO = 1 ";
                    $sql4 .="ORDER BY 2 ";
        	
        	}else{
                    $sql4="select area_codigo,area_descripcion from areas where area_activo = 1 and area_codigo > 0 order by 2 ";
                }*/
                
                //echo $sql4; 
                $combo->query = $sql4;
                $combo->name = "area_codigo";
                $combo->value = $area_codigo."";
                
                $combo->more = "class=select style='width:415px;' onchange=sel_area()";
                $rpta = $combo->Construir();
                echo $rpta;
?>
	</td>
    <td width="50%" align="left" class="ColumnTD">Responsables :&nbsp;
       <?php
            
            if ($rol_code!=10){//cualquier rol diferente de Validador areas de soporte
                $sql4="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
                $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
                $sql4 .=" a.turno_codigo = t.turno_codigo inner join ca_controller c on ";
                $sql4 .=" a.area_codigo=c.area_codigo inner join ca_asistencia_responsables r on ";
                $sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
                $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
                $sql4 .=" and a.area_codigo =" . $area_codigo;
                $sql4 .=" and c.activo=1 ";
                $sql4 .=" and c.empleado_codigo = " . $empleado_id . " group by r.responsable_codigo ";
                $sql4 .=" Union ";
                $sql4 .="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
                $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
                $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
                $sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
                $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
                $sql4 .=" and a.area_codigo =" . $area_codigo;
                $sql4 .=" and r.responsable_codigo =" . $empleado_id;
                $sql4 .=" group by r.responsable_codigo ";
                if ($rol_codigo>0){
                    $sql4 .=" Union ";
                    $sql4 .="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
                    $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
                    $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
                    $sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
                    $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
                    $sql4 .=" and a.area_codigo =" . $area_codigo;
                    $sql4 .=" group by r.responsable_codigo ";
		}
		$sql4 .="Order by 2";
            }else{
		
                $sql4 ="select r.responsable_codigo, dbo.udf_empleado_nombre(r.responsable_codigo) as responsable ";
		$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
		$sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
		$sql4 .=" a.empleado_codigo=r.empleado_codigo and a.asistencia_codigo=r.asistencia_codigo ";
		$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) ";
		$sql4 .=" and a.area_codigo =" . $area_codigo;
		$sql4 .=" group by r.responsable_codigo ";
                $sql4 .="Order by 2";
            }
            
		$combo->query = $sql4;
		$combo->name = "responsable_codigo";
		$combo->value = $responsable_codigo."";
		$combo->more = "class=select style='width:380px;' onchange=sel_responsable()";
		$rpta = $combo->Construir();
		echo $rpta;	
	   ?>
	</td>
  </tr>
  <tr>
    <td width="50%" align="left" class="ColumnTD">Turnos :&nbsp;
      <?php
	    //query del combo de seleccion de turnos
            if ($rol_code!=10){
                $sql4="select a.turno_codigo,t.turno_descripcion ";
                $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
                $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_controller c on ";
                $sql4 .="a.area_codigo=c.area_codigo inner join ca_asistencia_responsables r on ";
                $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
                $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
                if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
                $sql4 .="and c.activo = 1 and c.empleado_codigo = " . $empleado_id . " and t.turno_activo = 1 ";
                $sql4 .="group by a.turno_codigo,t.turno_descripcion ";
                $sql4 .=" Union ";
                $sql4 .="select a.turno_codigo,t.turno_descripcion ";
                $sql4 .="from ca_asistencias a inner join ca_turnos t on ";
                $sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
                $sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
                $sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
                if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
                $sql4 .=" and t.turno_activo = 1 ";
                $sql4 .=" group by a.turno_codigo,t.turno_descripcion order by t.turno_descripcion";
	    }else{
                $sql4="select a.turno_codigo,t.turno_descripcion ";
  		$sql4 .="from ca_asistencias a inner join ca_turnos t on ";
  		$sql4 .="a.turno_codigo = t.turno_codigo inner join ca_asistencia_responsables r on ";
  		$sql4 .="a.empleado_codigo = r.empleado_codigo and a.asistencia_codigo = r.asistencia_codigo ";
  		$sql4 .="where a.asistencia_fecha = convert(datetime,'" . $fecha . "',103) and a.area_codigo = " . $area_codigo . " ";
  		if ($responsable_codigo*1>0) $sql4 .="and r.responsable_codigo = " . $responsable_codigo . " ";
  		$sql4 .=" and t.turno_activo = 1 ";
  		$sql4 .=" group by a.turno_codigo,t.turno_descripcion order by t.turno_descripcion";
	    }
		$combo->query = $sql4;
		$combo->name = "turno_codigo";
		$combo->value = $turno_codigo."";
		$combo->more = "class=select style='width:200px;' onchange=cambiar()";
		$rpta = $combo->Construir();
		echo $rpta;
	  ?>
	</td>
  </tr>
</table>
<input type="hidden" id="area_id" name="area_id" value="<?php echo $area_id ?>" /> 
<input type="hidden" id="rol_codigo" name="rol_codigo" value="<?php echo $rol_codigo ?>" /> 
</form>
<script type="text/javascript">
Calendar.setup({
    inputField     :    "txtFecha",      // id of the input field
    ifFormat       :    "%d/%m/%Y",       // format of the input field
    showsTime      :    false,            // will display a time selector
    button         :    "cmd_fecha_inicio",   // trigger for the calendar (button ID)
    singleClick    :    true,           // double-click mode
    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
});
</script>
<iframe id='ift' name='ift' style='width:600px;display:none;'></iframe>
<!--<iframe id='ift' name='ift' style='width:600px;height:600px;'></iframe>-->
</body>
</html>