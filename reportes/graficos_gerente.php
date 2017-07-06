<?php
header("Expires: 0");

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Areas.php");  
require_once("../includes/clsCA_Empleados.php");  
require_once("../includes/clsCA_Empleado_Rol.php");

$t=1;
$rdo=1;
$area_codigo="0";
$opcion="1";
$incidencia_codigo="";

$id = $_SESSION["empleado_codigo"];
$o = new ca_empleados();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$obj = new areas();
$obj->MyUrl = db_host();
$obj->MyUser= db_user();
$obj->MyPwd = db_pass();
$obj->MyDBName= db_name();


$usr = new ca_usuarios();
$usr->MyUrl = db_host();
$usr->MyUser= db_user();
$usr->MyPwd = db_pass();
$usr->MyDBName= db_name();

$ob = new ca_empleado_rol();
$ob->MyUrl = db_host();
$ob->MyUser= db_user();
$ob->MyPwd = db_pass();
$ob->MyDBName= db_name();

$usr->empleado_codigo = $id;
$r = $usr->Identificar();
$empleado  	= $usr->empleado_nombre;
$area_nombre = $usr->area_nombre;
$fecha     	= $usr->fecha_actual;
$anio=substr($fecha,6); 


$ob->empleado_codigo=$id;
$ob->rol_codigo=3;
$r=$ob->Verifica_rol();

$ob->rol_codigo=5;
$r5=$ob->Verifica_rol(); // buscar si es administrador funcional

if($r=="OK" OR $r5=="OK"){ $op="0";
              $area ="0";
}
else {
		$ob->rol_codigo=6;
		$r=$ob->Verifica_rol();
	    if($r=="OK"){ $op="1";
		              $area= $usr->area_codigo;
		}	
}


if (isset($_POST["area_codigo"]))  $area_codigo= $_POST["area_codigo"];
if (isset($_POST["area"]))  $area= $_POST["area"];
if (isset($_POST["opcion"]))  $opcion= $_POST["opcion"];
if (isset($_POST["incidencia_codigo"]))  $incidencia_codigo= $_POST["incidencia_codigo"];
if (isset($_POST["anio"]))  $anio= $_POST["anio"];
if (isset($_POST["t"]))  $t= $_POST["t"];


//echo $t;

if($area!="0") $CodCadena=$obj->TreeRecursivo($area);
else $CodCadena=0;

?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Consultas Rol Gerente</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script language='javascript'>
var server="<?php echo $ip_app ?>";
var op="<?php echo $op ?>";

function cmdCancelar_onclick() {
 self.location.href='../menu.php';
}

function Graficar(){
var opcion="";
opcion=document.frm.opcion.value; 

if(opcion==1){
  if (document.frm.area_codigo.value == 0){
    alert("Seleccione Area");
    document.frm.area_codigo.focus();
    return false;
  }
   var area_codigo="<?php echo $area_codigo ?>";
   
  if (document.frm.incidencia_codigo_a.value == 0){
	    alert("Seleccione Incidencia");
	    document.frm.incidencia_codigo_a.focus();
	    return false;
	  }
  var anio=document.frm.anio_a.value;
  var incidencia_codigo=document.frm.incidencia_codigo_a.value;	  
}

if(opcion==2){
  if(op==0){
		if (document.frm.area.value == 0){
	    alert("Seleccione Gerencia");
	    document.frm.area.focus();
	    return false;
	   }
  }
	if (document.frm.incidencia_codigo_g.value == 0){
	    alert("Seleccione Incidencia");
	    document.frm.incidencia_codigo_g.focus();
	    return false;
	  }
	var anio=document.frm.anio_g.value;
    var incidencia_codigo=document.frm.incidencia_codigo_g.value;
	var gerencia_codigo="";
	if(op==0) gerencia_codigo=document.frm.area.value;
	else gerencia_codigo="<?php echo $area?>";
}
  if(opcion==1) var valor = window.showModalDialog("http://" + server + "/tmreportes/Gap/grafico_1.jsp?anio=" + anio + "&area_codigo=" + area_codigo + "&incidencia_codigo=" + incidencia_codigo, "Grafico","dialogWidth:900px; dialogHeight:700px");
  if(opcion==2) var valor = window.showModalDialog("http://" + server + "/tmreportes/Gap/grafico_2.jsp?anio=" + anio + "&incidencia_codigo=" + incidencia_codigo + "&gerencia_codigo=" + gerencia_codigo, "Grafico","dialogWidth:900px; dialogHeight:700px");
  
}
function cerrar_flash(){
  document.all("div_consolidado").style.display = "NONE";
}


function filtroIncidencias(codigo){
	document.frm.area_codigo.value=codigo;
	document.frm.t.value=1;
	document.frm.submit();
}


function filtroAreas(codigo){	
	if(document.frm.opcion.value==1){
	     document.frm.area.value=codigo;
		 document.frm.t.value=1;
		 document.frm.submit();	 
	}else{
		document.frm.t.value=0;
	  }
}


function habilitar(valor){
switch(valor){
    case '1': document.frm.opcion.value=1;
              //if(op==0) document.frm.area.value=0;
	          span_a.style.display='block';
    		  span_g.style.display='none';
			  break;
	case '2': 
	          document.frm.opcion.value=2;
	          if(op==0) document.frm.area.value=0;
              span_g.style.display='block';
    		  span_a.style.display='none';
			  break;
  } 
 //document.frm.submit();
}

</SCRIPT>
</HEAD>
<BODY class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<center class='FormHeaderFont'>Gráficos</center>
<br>
<table align='center' width="60%" border="0">
 <?php 
 if($op=="1"){
?>
 <tr>
    <td width='45%' align=right><b>Area : </b></td>
	<td class='CA_FormHeaderFont' align=left><?php echo $area_nombre ?></td>
 </tr>
 <?php 
 }
 ?>
</table>
<br>
<br>
<table class='DataTD' align=center  width='40%' border='0' cellpadding='1' cellspacing='0'>
	<tr>
		<td  class='ColumnTD'  align='center' colspan='2' >Seleccione gráfico</td>
	 </tr>
	<tr>
	     <td align='right' width='90px'>
		   <Input type='radio' name='rdo' id='rdo1' value='1' onclick='habilitar(this.value)' <?php if($t==1) echo "checked" ?>>
		 </td>
		 <td>  
		   Consolidado Anual por Incidencia y Area
		</td>
      </tr>
     <tr>
		<td align='right'>
		   	<Input type='radio' name='rdo' id='rdo2' value='2' onclick='habilitar(this.value)' <?php if($t==0) echo "checked" ?>>	
        </td>
		 <td> Consolidado Anual por Areas
		</td>          
	</tr>
</table>
<br>
<br>
<table width='40%' align='center'>
 <?php 
 if($op=="0"){
   ?>
  <tr>
    <td  class='ColumnTD'align=left><b>Gerencia:&nbsp;&nbsp;<?php
	        
			$c = new MyCombo();
			$c->setMyUrl(db_host());
			$c->setMyUser(db_user());
			$c->setMyPwd(db_pass());
			$c->setMyDBName(db_name());
			
	  	    $sql="SELECT Areas.area_codigo as codigo,Areas.area_descripcion as descripcion ";
			$sql .=" FROM Areas ";
			$sql .=" WHERE (Area_Padre=1) ";
			$sql .=" and (Areas.Area_Activo = 1) ";
			$sql .=" order by 2 asc";
			
			$c->query = $sql;
			$c->name = "area"; 
			$c->value = $area. "";
			$c->more = " style='width:500px' class='Select' onchange='filtroAreas(this.value)'";
			$rpta = $c->Construir();
			echo $rpta;
			
		  ?>
	</td>
 </tr>
 <?php
 }?>
</table>
<br>
<span id="span_a" style="<?php if($t==0) echo "display:none"; else echo "display"; ?>">
<TABLE class='formtable' align=center border='0' width='5%' cellpadding='0' cellspacing='1'>
	   <TR>
		<TD class='CA_FieldCaptionTD' align=center colspan="2">&nbsp;
		</TD>
	   </TR>
	   <TR>
        <TD class='ColumnTD' width='40%' align=right>
		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Anio&nbsp;:&nbsp;
		 <select id='anio_a' name='anio_a'  class='select' style='width:60px'>
           <?php 
		    $anno=$anio;
		    for($i=$anio-4;$i<=$anio+4;$i++){
			  if($i==$anno) echo "\t<option value=". $i." selected>". $i ."</option>" . "\n";
			  else echo "\t<option value=". $i . ">". $i."</option>" ."\n";
	        }?>
        </select>
       </td>
       <TD class='ColumnTD' width='40%' align=right>
		 Dependencias&nbsp;:&nbsp;
		  <?php
		  
		    //echo $CodCadena;
		    $combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			
			$sql="SELECT Areas.area_codigo as codigo,Areas.area_descripcion as descripcion ";
			$sql .=" FROM Areas ";
			$sql .=" WHERE (Area_Codigo in (" . $CodCadena . ")) and (area_codigo<>" . $area . ")" ;
			$sql .=" and (Areas.Area_Activo = 1) ";
			$sql .=" order by 2 asc";
			
			$combo->query = $sql;
			$combo->name = "area_codigo"; 
			$combo->value = $area_codigo."";
			$combo->more = "class='Select' style='width:350px' onchange='filtroIncidencias(this.value)'";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
		</TD>
      </TR>
	  <TR>

		<TD class='ColumnTD' colspan=2 width='80%' align=right>
		 Incidencia&nbsp;:&nbsp;
		  <?php
			$sql="SELECT CA_Incidencias.incidencia_codigo as codigo,CA_Incidencias.incidencia_descripcion as descripcion ";
			$sql .=" FROM ca_incidencias WHERE (area_codigo=0 ";
			$sql .=" or area_codigo=" . $area_codigo . " ) ";
            $sql .=" AND incidencia_activo=1";
			$sql .=" order by 2 asc";
			
			$combo->query = $sql;
			$combo->name = "incidencia_codigo_a"; 
			$combo->value = $incidencia_codigo."";
			$combo->more = "class='Select' style='width:350px' ";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
		</TD>
	</TR>
</table>
</span>
<span id="span_g" style="<?php if($t==1) echo "display:none"; else echo "display"; ?>">
<TABLE class='formtable' align=center border='0' width='5%' cellpadding='0' cellspacing='1'>
	   <TR>
		<TD class='CA_FieldCaptionTD' align=center colspan="2">&nbsp;
		</TD>
	   </TR>
	   <TR>
        <TD class='ColumnTD' width='40%' align=right>
		 Anio&nbsp;:&nbsp;
		</TD>
		<td>
		 <select id='anio_g' name='anio_g'  class='select' style='width:60px'>
           <?php 
		    $anno=$anio;
		    for($i=$anio-4;$i<=$anio+4;$i++){
			  if($i==$anno) echo "\t<option value=". $i." selected>". $i ."</option>" . "\n";
			  else echo "\t<option value=". $i . ">". $i."</option>" ."\n";
	        }?>
        </select>
       </td>
      </TR>
	  <TR>

		<TD class='ColumnTD' width='40%' align=right>
		 Incidencia&nbsp;:&nbsp;
		</TD>
		<TD class='DataTD' align=left>
		  <?php
			$sql="SELECT CA_Incidencias.incidencia_codigo as codigo,CA_Incidencias.incidencia_descripcion as descripcion ";
			$sql .=" FROM ca_incidencias WHERE (area_codigo=0 ";
			$sql .=" or area_codigo  in (" . $CodCadena . ") )";
			$sql .=" AND incidencia_activo=1";
			$sql .=" order by 2 asc";	
			$combo->query = $sql;
			$combo->name = "incidencia_codigo_g"; 
			$combo->value = $incidencia_codigo."";
			$combo->more = "class='Select' style='width:350px' ";
			$rpta = $combo->Construir();
			echo $rpta;
		  ?>
		</TD>
	</TR>
</table>
</span>


<br>

<table align="center">
	<TR>
		<TD align=center>
		    <INPUT class=button type="button" value="Graficar" id=cmdGraficar name=cmdGraficar style="width=80px" LANGUAGE=javascript onClick="return Graficar()">
			<INPUT class=button type="button" value="Cancelar" id=cmdCancelar name=cmdCancelar style="width=80px" LANGUAGE=javascript onClick="return cmdCancelar_onclick()">

		</TD>
	</TR>
</table>
<input type='hidden' id='t' name='t' value='<?php $t ?>'>
<input type='hidden' id='opcion' name='opcion' value='1'>
</form>
<br>
<div id="div_consolidado" style="BORDER-BOTTOM: 2px ridge; Z-INDEX:200; BORDER-LEFT: 2px ridge; BORDER-RIGHT: 2px ridge; BORDER-TOP: 2px ridge; POSITION: absolute; DISPLAY: none; ">
<iframe name="if_consolidado" id="if_consolidado" src="" frameborder="1" scrolling="auto" width="810" height="520"></iframe>
</div>
</BODY>
</HTML>