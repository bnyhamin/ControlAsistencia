<?php
header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Tiempos.php"); 
require_once("../includes/clsCA_Empleados.php"); 
require_once("../includes/clsCA_Asistencia_Incidencias.php"); 

$empleado_codigo="0";
$empleado_nombre="";
$cod_campana="0";
$asistencia_codigo="0";
$responsable_codigo="0";
$fecha="";
$horas="";
$minutos="";
$area="0";
$sql="";
$cod="";


$o=new ca_tiempos();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$c = new ca_asistencia_incidencias();
$c->MyUrl = db_host();
$c->MyUser= db_user();
$c->MyPwd = db_pass();
$c->MyDBName= db_name();


$emp = new ca_empleados();
$emp->MyUrl = db_host();
$emp->MyUser= db_user();
$emp->MyPwd = db_pass();
$emp->MyDBName= db_name();

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());


if (isset($_GET["empleado"])) $empleado_codigo = $_GET["empleado"];
if (isset($_POST["empleado_cod"])) $empleado_codigo = $_POST["empleado_cod"];


if (isset($_GET["responsable"])) $responsable_codigo = $_GET["responsable"];
if (isset($_POST["responsable_cod"])) $responsable_codigo = $_POST["responsable_cod"];


if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];


if (isset($_POST["horas"])) $horas = $_POST["horas"];
if (isset($_POST["minutos"])) $minutos = $_POST["minutos"];

if (isset($_GET["area"])) $area=$_GET["area"];
if (isset($_POST["area_codigo"])) $area = $_POST["area_codigo"];



$emp->empleado_codigo=$empleado_codigo;
$rpta=$emp->Query();
$empleado=$emp->empleado_nombre;
 
$c->empleado_codigo=$empleado_codigo;
$rpta=$c->Obtener_servicio_empleado();
$cod_campana=$c->cod_campana;

if($cod_campana=='') $cod_campana=0;

if (isset($_POST["cod_campana"])) {
	$cod = $_POST["cod_campana"];
	$cod_campana=$cod;
}

if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
		//-- registrar
	    $o->empleado_codigo=$empleado_codigo;
		$o->cod_campana=$cod_campana;
		$o->tiempo_fecha=$fecha;
		$o->responsable_codigo=$responsable_codigo;		
	    $mensaje = $o->registrar_tiempo($horas,$minutos);
		if($mensaje=='OK'){
		?>
		<script language='javascript'>
		 alert('Guardado exitosamente!!');
		 window.opener.document.forms['frm'].submit();
		 window.opener.document.frm.cmdx.click();
		 window.close();
		 //window.parent.frames[1].submit="val_left.php"; 
		</script>
		<?php
		}else{
		  echo $mensaje;
		}
	}
}
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Control Presencial - Registro  de Tiempos de Conexion</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<SCRIPT ID=clientEventHandlersJS LANGUAGE=javascript>

function cmdCancelar_onclick() {
	self.returnValue = 0
	self.close();
}
function cmdGrabar_onclick(){
var cod_campana="<?php echo $cod_campana ?>";
if(document.frm.cod_campana.disabled==false){
 if (validarCampo('frm','cod_campana')!=true) return false;
}else{
    if(document.frm.cod_campana.disabled){
      if(cod_campana==0){
	   alert('No tiene asignado, seleccione otro servicio');
	   return false;
	  }
	}
 }

 if(document.frm.horas.value==-1){
	  alert('Indique  valor');
	  document.frm.horas.focus();
	  return false;
	}
	if(document.frm.minutos.value==-1){
	  alert('Indique  valor');
	  document.frm.minutos.focus();
	  return false;
	}


if (confirm('Confirme datos de registro?')==false) return false;
	document.frm.hddaccion.value="INS";
    document.frm.submit()
  }
function habilitar(tipo){

switch(tipo){
case '1': document.frm.cod_campana.disabled=true;
          document.frm.cod_campana.value=0;
        break;
case '2': document.frm.cod_campana.disabled=false;
          break;

 }
}  

</SCRIPT>
</HEAD>
<BODY class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF']  ?>" method="post">
<center class='FormHeaderFont' >Registro de Tiempos de Conexion</center>
<br>
<center class="CA_FormHeaderFont"><?php echo $empleado?></Center>
<TABLE WIDTH='100%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
		<TD  align=right width=50%>
		 Fecha :&nbsp;
		</TD>
		<TD  align=left>
		 <b><?php echo $fecha ?></b>
		</TD>
	</TR>
</table>	
<br>
<TABLE WIDTH='100%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
		<TD class='ColumnTD' align=center colspan="2">
		 Unidad de Servicio&nbsp;
		</TD>
	</TR>
	<TR>
		<TD class='DataTD' align=center colspan='2'><br>
		  <table class='FormTable' width='90%' border='0' cellspacing="1">
		    <tr>
			  <td align='right' width="20%" ><b>Asignado</b></td>
			  <td>
			  <input type='radio' id='rdo' name='rdo' value='1' onClick="habilitar(this.value)" checked>
			  <?php echo $c->campana ?>
			  </td>
			</tr>
			<tr>
			  <td align="right"><b>Otros</b></td>
			  <td>
			  <input type='radio' id='rdo' name='rdo' value='2' onClick="habilitar(this.value)">
			  
		<?php  
			$sql ="select v_campanas.cod_campana as codigo, ";
		    $sql .=" v_campanas.exp_nombrecorto + '(' + v_campanas.exp_codigo + ')' as descripcion"; 
		    $sql .=" from v_campanas ";
		    $sql .=" where  v_campanas.exp_activo=1 and coordinacion_codigo=" . $area;                   
			$sql .=" order by 2 asc"; 
			$combo->query = $sql;
			$combo->name = "cod_campana"; 
			$combo->value = $cod."";
			$combo->more = " class='select' style='width:400px' disabled";
			$rpta = $combo->Construir();
			echo $rpta;
			  
			  ?>
			  </td>
			</tr>
		  </table>
		  <br>
		</TD>
	</TR>
	
	<TR>
		<TD class='ColumnTD' align=right>
			Tiempo&nbsp;
		</TD>
		<TD class='DataTD' align=left>
		  &nbsp;Horas&nbsp;<select   name='horas' id='horas' >
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
			 echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  name='minutos' id='minutos' >
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
             if(strlen($m)<=1) $mm="0".$m;
		      echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 	
		</TD>
	</TR>
	<TR>
		<TD class='CA_FieldCaptionTD' align=center colspan="2">&nbsp;
		</TD>
	</TR>
</table>
<br>
<table align="center">
	<TR>
		<TD align=center>
			<img src="../images/botonGUARDAR-off.gif" onMouseOver="this.src='../images/botonGUARDAR-on.gif'" onMouseOut="this.src='../images/botonGUARDAR-off.gif'" style="cursor:hand " onClick="return cmdGrabar_onclick()">&nbsp;&nbsp;
			<img src="../images/botonCANCELAR-off.gif" onMouseOver="this.src='../images/botonCANCELAR-on.gif'" onMouseOut="this.src='../images/botonCANCELAR-off.gif'" style="cursor:hand " onClick="return cmdCancelar_onclick()">
		</TD>
	</TR>
</table>
<input type='hidden' id="hddaccion" name="hddaccion" value=''>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_codigo ?>">
<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>">
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>">
<input type='hidden' id='area_codigo' name='area_codigo' value="<?php echo $area ?>">

</form>
</BODY>
</HTML>