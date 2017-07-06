<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 
require_once("../../Includes/clsIncidencias.php");
require_once("../includes/clsCA_Asistencias.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php");  
require_once("../includes/clsCA_Empleados.php");

$disabled="";
$empleado_codigo="0";
$empleado="0";
$responsable_codigo="0";
$asistencia_incidencia_codigo="0";
$asistencia_codigo="0";
$incidencia_codigo="0";
$incidencia_hh_dd="0";
$incidencia="";
$h="0";
$hh="0";
$m="0";
$mm="0";
$tiempo_minutos="0";
$area="0";
$cod=0;
$cod_campana="0";
$campana="";
$horas="";
$minutos="";
$fecha="";
$sql="";
$asistencia="0";

$o = new ca_asistencia_incidencias();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());

$as=new ca_asistencia();
$as->setMyUrl(db_host());
$as->setMyUser(db_user());
$as->setMyPwd(db_pass());
$as->setMyDBName(db_name());

$emp = new ca_empleados();
$emp->setMyUrl(db_host());
$emp->setMyUser(db_user());
$emp->setMyPwd(db_pass());
$emp->setMyDBName(db_name());

$in = new incidencias();
$in->setMyUrl(db_host());
$in->setMyUser(db_user());
$in->setMyPwd(db_pass());
$in->setMyDBName(db_name());

if (isset($_GET["empleado"])) $empleado_codigo = $_GET["empleado"];
if (isset($_POST["empleado_cod"])) $empleado_codigo = $_POST["empleado_cod"];

if (isset($_GET["responsable"])) $responsable_codigo = $_GET["responsable"];
if (isset($_POST["responsable_cod"])) $responsable_codigo = $_POST["responsable_cod"];

if (isset($_GET["asistencia_incidencia_codigo"])) $asistencia_incidencia_codigo = $_GET["asistencia_incidencia_codigo"];
if (isset($_POST["asistencia_incidencia_cod"])) $asistencia_incidencia_codigo = $_POST["asistencia_incidencia_cod"];

if (isset($_GET["asistencia"])) $asistencia_codigo=$_GET["asistencia"];
if (isset($_POST["asistencia_cod"])) $asistencia_codigo = $_POST["asistencia_cod"];

if (isset($_GET["area"])) $area=$_GET["area"];
if (isset($_POST["area_codigo"])) $area = $_POST["area_codigo"];

$emp->empleado_codigo=$empleado_codigo;
$rpta=$emp->Query();
$empleado=$emp->empleado_nombre;
 
$o->empleado_codigo=$empleado_codigo;
$rpta=$o->Obtener_servicio_empleado();
$cod=$o->cod_campana;
$campana=$o->campana;
$cod_campana=$cod;
if($cod=='') $cod=0;

    //recuperar datos de incidencia
    $o->empleado_codigo = $empleado_codigo;
    $o->asistencia_incidencia_codigo = $asistencia_incidencia_codigo;
    $mensaje = $o->Query();
    $incidencia_codigo=$o->incidencia_codigo;
    $cod_camp=$o->cod_campana;
    $tiempo_minutos=$o->tiempo_minutos;
    $horas=$o->horas;
    $minutos=$o->minutos;
    if($horas<0) $horas=-1;
    if($minutos<0) $minutos=-1;
    //recuperar datos de incidencia
    $in->incidencia_codigo=$incidencia_codigo;
    $mensaje=$in->Query();
    $incidencia=$in->incidencia_descripcion;
    $incidencia_hh_dd=$in->incidencia_hh_dd;
    if($incidencia_hh_dd==0) $disabled="disabled";

if (isset($_POST["cod_campana"])) {
    $cod = $_POST["cod_campana"];
    $cod_campana=$cod;
}

if (isset($_POST["horas"])) $horas = $_POST["horas"];
if (isset($_POST["minutos"])) $minutos = $_POST["minutos"];

if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='MOD'){
        //-- registrar
        $o->empleado_codigo=$empleado_codigo;
        $o->asistencia_codigo=$asistencia_codigo;
        $o->asistencia_incidencia_codigo =$asistencia_incidencia_codigo;
        $o->incidencia_hh_dd=$incidencia_hh_dd;
        if($incidencia_hh_dd==1){
            $o->horas =$horas;
            $o->minutos =$minutos;
        }
        $o->cod_campana=$cod_campana;
        $o->responsable_codigo=$responsable_codigo;
        $mensaje = $o->modificar_incidencia();
        
        //echo "empleado_codigo:" . $empleado_codigo;
        if($mensaje=='OK'){
?>
    <script language='javascript'>
        alert('Modificado exitosamente!!');
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
<title>Control Presencial - Modificar Incidencias</title>
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
var cod_campana="<?php echo $cod ?>";
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
	//document.frm.action +="?codigo=" + asistencia + "&responsable=" + responsable;
	document.frm.hddaccion.value='MOD';
    document.frm.submit()
  }
function habilitar(tipo){
//alert(tipo);
switch(tipo){
case '1': document.frm.cod_campana.disabled=true;
          document.frm.cod_campana.value=0;
		  //document.frm.cod_campana.value=0;
        break;
case '2': document.frm.cod_campana.disabled=false;
         break;

 }
}  
function validar_incidencia(codigo){
if(codigo==7){
   document.frm.horas.value=-1;
   document.frm.minutos.value=-1;
   document.frm.horas.disabled=true;
   document.frm.minutos.disabled=true;
 }else{
   document.frm.horas.disabled=false;
   document.frm.minutos.disabled=false;
 }
 
}  

  
</SCRIPT>
</HEAD>
<BODY class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<center class='FormHeaderFont' >Modificar Incidencia </center>
<br>
<center class="CA_FormHeaderFont"><?php echo $empleado?></Center> 
<?php 
$check_asignado="";
$select_otros="";
$check_otros="";

if($cod==$cod_camp){
	 	$check_asignado="checked";
	 	$select_otros="disabled";
    }	
    else{
	 $cod_campana=$cod_camp;
	 $check_otros="checked";
}	

?> 
<table align="center">
		 <tr>
			<td><b>Nro. Asistencia:</b></td>
			<td><?php echo $asistencia_codigo?>
		   </td>
		 </tr>
</table>
<br>
<TABLE WIDTH='98%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
		<TD class='ColumnTD' align=right>
		 Incidencia&nbsp;
		</TD>
		<TD class='DataTD' align=left>
		  <b><?php
		    echo $incidencia;
		  ?></b>
		</TD>
	</TR>
	<TR>
		<TD class='ColumnTD' align=center colspan="2">
		 Servicio&nbsp;
		</TD>
	</TR>
	<TR>
		<TD class='DataTD' align=center colspan='2'><br>
		  <table class='FormTable' width='95%' border='0' cellspacing="1">
		    <tr>
			  <td align='right' width="70px" ><b>Asignado</b></td>
			  <td>
			  <input type='radio' id='rdo' name='rdo' value='1' onClick="habilitar(this.value)" <?php echo $check_asignado ?>>
			   <?php echo $campana ?>
			  </td>
			</tr>
			<tr>
			  <td align="right"><b>Otros</b></td>
			  <td>
			  <input type='radio' id='rdo' name='rdo' value='2' onClick="habilitar(this.value)" <?php echo $check_otros ?>>
			  
		<?php 
		    $combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			 
			$sql ="select v_campanas.cod_campana as codigo, ";
		    $sql .=" v_campanas.exp_codigo + '-' + v_campanas.exp_nombrecorto + '(' + convert(varchar,v_campanas.cod_campana) + ')' as descripcion"; 
		    $sql .=" from v_campanas ";
		    $sql .=" where  v_campanas.exp_activo=1 and coordinacion_codigo=" . $area ;
			if($cod!=0) $sql .=" and cod_campana<>". $cod;                   
			$sql .=" order by 2 asc"; 
			//echo $sql;
			$combo->query = $sql;
			$combo->name = "cod_campana"; 
			$combo->value = $cod_campana."";
			$combo->more = " class='select' " . $select_otros . "";
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
			Tiempo &nbsp;
		</TD>
		<TD class='DataTD' align=left>
		  &nbsp;Horas&nbsp;<select   name='horas' id='horas' <?php echo $disabled ?>>
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
		     $hh=$h;
             if(strlen($h)<=1) $hh="0".$h;
			 
		     if($h==$horas) echo "\t<option value=". $h." selected>". $hh ."</option>" . "\n";
			  else echo "\t<option value=". $h . ">". $hh."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  name='minutos' id='minutos' <?php echo $disabled ?>>
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
		     $mm=$m;
                     if(strlen($m)<=1) $mm="0".$m;
		      
			  if($m==$minutos) echo "\t<option value=". $m ." selected>". $mm ."</option>" . "\n";
			  else echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
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
<input type='hidden' id="hddaccion" name="hddaccion" value=''/>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_codigo ?>"/>
<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>"/>
<input type='hidden' id='asistencia_incidencia_cod' name='asistencia_incidencia_cod' value="<?php echo $asistencia_incidencia_codigo ?>"/>
<input type='hidden' id='asistencia_cod' name='asistencia_cod' value="<?php echo $asistencia_codigo ?>"/>
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>"/>
<input type='hidden' id='area_codigo' name='area_codigo' value="<?php echo $area ?>"/>

</form>
</BODY>
</HTML>