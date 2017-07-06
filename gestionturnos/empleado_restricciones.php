<?php
header("Expires: 0");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../includes/clsCA_Empleados.php");
require_once("../../Includes/clswfm_empleado_restricciones.php"); 
require_once("../../Includes/clswfm_restriccion.php");
require_once("../../Includes/MyCombo.php"); 
require_once("../../Includes/MyGrilla.php");
 
$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$emp = new ca_empleados();
$emp->MyUrl = db_host();
$emp->MyUser= db_user();
$emp->MyPwd = db_pass();
$emp->MyDBName= db_name();


$er = new wfm_empleado_restricciones();
$er->MyUrl = db_host();
$er->MyUser= db_user();
$er->MyPwd = db_pass();
$er->MyDBName= db_name();

$r= new wfm_restriccion();
$r->MyDBName= db_name();
$r->MyUrl = db_host();
$r->MyUser= db_user();
$r->MyPwd = db_pass();



if (isset($_GET["empleado_codigo"])) $empleado_codigo = $_GET["empleado_codigo"];
if (isset($_POST["empleado_codigo"])) $empleado_codigo = $_POST["empleado_codigo"];

if(isset($_POST["te_semana"])){
	$te_semana = $_POST["te_semana"];
}

if (isset($_POST["te_anio"])){
	$te_anio = $_POST["te_anio"];
}

if (isset($_POST["txtFechaInicio"])){ 
 $fecha_inicio = $_POST["txtFechaInicio"];
}

if (isset($_POST["txtFechaFin"])){ 
 $fecha_fin = $_POST["txtFechaFin"];
}

if (isset($_POST["hddrestriccion"])){ 
 $restriccion = $_POST["hddrestriccion"];
}

if (isset($_POST["rc_codigo"])){
	$rc_codigo=$_POST["rc_codigo"];
}


if (isset($_POST["hddrango"])){
	$temp = explode(" ",$_POST["hddrango"]);
	$te_fecha_inicio = $temp[3];
	$te_fecha_fin = $temp[5];
	
	//echo $te_fecha_inicio; 
	//echo $te_fecha_fin;
		
}

if ($rc_codigo == 4){
	
	$er->empleado_codigo=$empleado_codigo;
echo $er->verificar_presenta_restricciones_especiales($fecha_inicio , $fecha_fin);
	
}

$emp->empleado_codigo=$empleado_codigo;
$rpta=$emp->Query();
$empleado=$emp->empleado_nombre;


if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
		//-- registrar

	  	$er->empleado_codigo=$empleado_codigo;
	  	if ($rc_codigo==1 || $rc_codigo==2){
	  		$er->restriccion_codigo=$rc_codigo;
	  		$er->codigo_asociado=$restriccion;
	  	}else{
	  		$er->restriccion_codigo=$restriccion;
	  	}
	  	
		$er->semana=$te_semana;
		$er->anio=$te_anio;
		
		if ($er->restriccion_codigo == 1 || $er->restriccion_codigo == 2 || $er->restriccion_codigo == 3 || $er->restriccion_codigo == 4 || $er->restriccion_codigo == 5 ){
			$er->valor=1;
			
		}else{
			$r->restriccion_codigo=$er->restriccion_codigo;
			$r->Query();
			$er->valor=$r->minutos;
		}
		
 		$tmp1=split("/",$fecha_inicio);
 		if ($tmp1[0]*1 <=9){$tmp1[0] = '0' . $tmp1[0];}
 		if ($tmp1[1]*1 <=9){$tmp1[1] = '0' . $tmp1[1];}
 		
		$tmp2=split("/",$fecha_fin);
		if ($tmp2[0]*1 <=9){$tmp2[0] = '0' . $tmp2[0];}
 		if ($tmp2[1]*1 <=9){$tmp2[1] = '0' . $tmp2[1];}
 		
  		$xfecha=$tmp1[2].$tmp1[1].$tmp1[0];
     	$yfecha=$tmp2[2].$tmp2[1].$tmp2[0];
				
		while ($xfecha <= $yfecha)
		{ 
			$er->fecha= $xfecha;
			$er->Addnew();
		   	$xfecha = $xfecha + 1;
		}
		
	
		
		
	 
		
		
		/*if($incidencia_codigo!=42 && $incidencia_codigo!=43 && $incidencia_codigo!=11){
			$o->horas =$horas;
			$o->minutos =$minutos;
		}else{
		   $o->horas =0;
		   $o->minutos=0;
		}
		$o->cod_campana=$cod_campana;
		$o->fecha=$fecha;
		$o->ip_registro=$ip_entrada;
		//echo "$num,$ip_entrada,$ip_salida";
	    $mensaje = $o->registrar_incidencia($num,$ip_entrada,$ip_salida);
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
		}*/
	}
}
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro  de Restriccion - Empleado</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
<script>
function cancelar() {
	self.returnValue = 0
	self.close();
}
function cmdRegistra_onclick(){
    var rpta=Registro();
    if (rpta != '' ) {
        
		CenterWindow("empleado_restricciones.php?empleado_codigo=" + rpta , "ModalChild",600,600,"yes","center");
		//CenterWindow("../../wfm/atributos.php?empleado_codigo=" + rpta , "ModalChild",450,400,"yes","center");
	}
}

function calcular(){
	//document.frm.submit();

	//document.frm.hddaccion.value="UPD";
	//document.frm.submit();
}

function guardar(){
	
if (document.frm.te_semana.value == 0)
{
	alert('Ingrese Semana');
	document.frm.te_semana.focus();
	return false;
}

if (document.frm.txtFechaInicio.value == '')
{
	alert('Ingrese Fecha Inicio');
	document.frm.txtFechaInicio.focus();
	return false;
}

if (document.frm.txtFechaFin.value == '')
{
	alert('Ingrese Fecha Fin');
	document.frm.txtFechaFin.focus();
	return false;
}

if (document.frm.rc_codigo.value == 0)
{
	alert('Ingrese Clase');
	document.frm.rc_codigo.focus();
	return false;
}

if (document.frm.rc_codigo != 0){
	var rpta=Registro();
	document.frm.hddrestriccion.value=rpta;
	alert(document.frm.hddrestriccion.value);

document.frm.hddrango.value=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text;
	//alert (document.frm.hddrango.value);
}
	

if (confirm('Confirme datos de registro?')==false) return false;
	
	document.frm.hddaccion.value="INS";
    document.frm.submit();
}

function MostrarGrilla()
{
	document.frm.submit();

}

function validar_onClick(){
    var semana=document.getElementById('te_semana').options[document.getElementById('te_semana').selectedIndex].text.split(' ');
    var fi_semana = semana[3];
    var fi = fi_semana.split('/');
    var ff_semana = semana[5];
    var ff = ff_semana.split('/');
	
	if (fi[0]*1 <=9){dia_i = '0'+fi[0]*1; }else{dia_i = fi[0];}
	if (fi[1]*1 <=9){mes_i = '0'+fi[1]*1; }else{mes_i = fi[1];}
	if (ff[0]*1 <=9){dia_f = '0'+ff[0]*1; }else{dia_f = ff[0];}
	if (ff[1]*1 <=9){mes_f = '0'+ff[1]*1; }else{mes_f = ff[1];}
	
	var f_inicio = fi[2]+''+mes_i+''+dia_i;
	var f_fin = ff[2]+''+mes_f+''+dia_f;
	
	if (document.frm.txtFechaInicio.value != ''){
		var fecha_inicio = document.getElementById('txtFechaInicio').value.split('/');
		
		if (fecha_inicio[0]*1 <= 9){dia = '0'+fecha_inicio[0]*1; }else{dia = fecha_inicio[0];}
		if (fecha_inicio[1]*1 <= 9){mes = '0'+fecha_inicio[1]*1; }else{mes = fecha_inicio[1];}
		
		var inicio = fecha_inicio[2]+''+mes+''+dia;
		
		if (inicio < f_inicio || inicio > f_fin){
		alert('La Fecha ingresada se encuentra fuera de la semana seleccionada');
		document.frm.txtFechaInicio.value='';
		return false;
		}
	}
	
	if (document.frm.txtFechaFin.value != ''){
		var fecha_fin = document.getElementById('txtFechaFin').value.split('/');
		
		if (fecha_fin[0]*1 <= 9){dia_m = '0'+fecha_fin[0]*1; }else{dia_m = fecha_fin[0];}
		if (fecha_fin[1]*1 <= 9){mes_m = '0'+fecha_fin[1]*1; }else{mes_m = fecha_fin[1];}
		
		var fin = fecha_fin[2]+''+mes_m+''+dia_m;
		
		if (fin < f_inicio || fin > f_fin){
		alert('La Fecha ingresada se encuentra fuera de la semana seleccionada');
		document.frm.txtFechaFin.value='';
		return false;
		}
		
	}
	
	if (inicio > fin){
		alert('La fecha de inicio no puede ser mayor a la fecha final');
		return false;
	}
	
	return true;
	
}
  
</script>
</HEAD>
<BODY class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<center class='FormHeaderFont' >Registro de Restricción</center>
<br>
<center class="CA_FormHeaderFont"><?php echo $empleado?></Center>
<br />
<br />

<TABLE WIDTH='98%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
    <tr>
        <td class='ColumnTD' align="right" >
		Año:&nbsp;</td>
		<td class='ColumnTD' align="left"><select class='select' name='te_anio' id='te_anio'>
		  <?php
		   for($a=2010; $a < 2050; $a++){
			 if ($a==$te_anio) echo "\t<option value=". $a . " selected>". $a ."</option>" . "\n";
		     else echo "\t<option value=". $a . ">". $a ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 </td>
	 </tr>
	 <tr>
		 <td class='ColumnTD' align="right" >Semana:&nbsp;</td>
		 <td class='ColumnTD' align="left" >
		 	<?php
				$sql ="set datefirst 1 select sem as codigo, descripcion, inicio from vCA_Turnos_Semanas order by 3 ";
				$combo->query = $sql;
			?>
			<?php
				$combo->name = "te_semana"; 
				$combo->value = $te_semana ."";
				$combo->more = "class='Select' style='width:230px' onchange='calcular();'";
				$rpta = $combo->Construir_Opcion("---Seleccione---");
				echo $rpta;
			?>
        </td>
   </tr>
   	<tr>
    <td class="ColumnTD" align="right" >
			Fecha Inicio&nbsp;</td>
    <td align="left" class="ColumnTD">
	  <input type='text' class='input' id="txtFechaInicio" name="txtFechaInicio" readonly size=11 value="<?php echo $fecha_inicio ?>"> 
   			<img id='imgDel' onclick="popFrame.fPopCalendar(txtFechaInicio,txtFechaInicio,popCal);return false" src="../Images/columnselect.gif" border="0"  style='cursor:hand;' alt="Seleccionar Fecha Inicial">
	</td>
  </tr>
  	<tr>
    <td class="ColumnTD" align="right" >
			Fecha Fin&nbsp;</td>
    <td align="left" class="ColumnTD">
	  <input type='text' class='input' id="txtFechaFin" name="txtFechaFin"  readonly size=11 value="<?php echo $fecha_fin ?>"> 
   			<img id='imgDel' onclick="popFrame.fPopCalendar(txtFechaFin,txtFechaFin,popCal);return false" src="../Images/columnselect.gif" border="0"  style='cursor:hand;' alt="Seleccionar Fecha Fin">
	</td>
  </tr>
	<TR>
		<TD class='ColumnTD' align=center >
		 Clase&nbsp;
		</TD>
		<TD class='ColumnTD' align=left>
			<?php 
			$sql ="select rc_codigo as codigo, rc_descripcion";
		    $sql .=" from wfm_restriccion_clase ";
		    $sql .=" where  rc_activo=1 " ;                   
			$sql .=" order by 2 asc"; 
			$combo->query = $sql;
			$combo->name = "rc_codigo"; 
			$combo->value = $rc_codigo."";
			$combo->more = " class='select' onchange='MostrarGrilla()' ";
			$rpta = $combo->Construir();
			echo $rpta;
			?>
		</TD>
	</TR>

	
	<TR>
		<TD class='CA_FieldCaptionTD' align=center colspan="2">&nbsp;
		</TD>
	</TR>
</table>
<br>
<div id="popCal" style="POSITION:absolute;visibility:hidden;border:2px ridge;width:10">
<iframe name="popFrame" id="popFrame" src="../../popcj_v.htm" frameborder="0" scrolling="no" width="183" height="188"></iframe>
</div>

<table class='FormTable' border=0 cellPadding=1 cellSpacing=1 width='100%' id='tblOpciones'>
	<tr align="center">
	<td class='ColumnTD'>
	<input  class="button" type="button" name="cmdGuardar" value="Aplicar" onclick="javascript:guardar();" style="width:80 px" title="Guardar" />
		 
	<input class="button" type="button" name="cmdCancelar" value="Cancelar" onclick="javascript:cancelar();" style="width:80 px" title="Cancelar" />
	</td>
	</tr>
</table>

<input type='hidden' id='empleado_codigo' name='empleado_codigo' value="<?php echo $empleado_codigo ?>">
<input type="hidden" id="hddrestriccion" name="hddrestriccion" value=""/>
<input type='hidden' id="hddaccion" name="hddaccion" value=''/>
<input type="hidden" id="hddrango" name="hddrango" value=""/>



<?php

if ($rc_codigo == 1 ){

	$rango = "";
	$rpta = "";
	$body="";
	$npag = 1;
	$orden = "dd_codigo";
    $buscam = "";
    $torder="ASC";

	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	//$objr->setNoSeleccionable(true);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from  = "disponibilidad_dias ";
		
	$objr->setFrom($from);
	
	$where = " dd_activo=1" ;

	$objr->setWhere($where);
	$objr->setSize(10);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Descripcion";
    $arrAlias[3] = "Lunes";
	$arrAlias[4] = "Martes";
	$arrAlias[5] = "Miercoles";
	$arrAlias[6] = "Jueves";
	$arrAlias[7] = "Viernes";
    $arrAlias[8] = "Sabado";
    $arrAlias[9] = "Domingo";
	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "dd_codigo";
    $arrCampos[1] = "dd_codigo"; 
    $arrCampos[2] =	"dd_descripcion";
    $arrCampos[3] =	"case dia1 when 1 then 'Si' else 'No' end";
	$arrCampos[4] = "case dia2 when 1 then 'Si' else 'No' end";
	$arrCampos[5] = "case dia3 when 1 then 'Si' else 'No' end";
	$arrCampos[6] = "case dia4 when 1 then 'Si' else 'No' end";
	$arrCampos[7] = "case dia5 when 1 then 'Si' else 'No' end";
    $arrCampos[8] = "case dia6 when 1 then 'Si' else 'No' end";
    $arrCampos[9] = "case dia7 when 1 then 'Si' else 'No' end";
    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
	
}

if ($rc_codigo == 2 ){

	$rango = "";
	$rpta = "";
	$body="";
	$npag = 1;
	$orden = "dh_codigo";
    $buscam = "";
    $torder="ASC";

	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	//$objr->setNoSeleccionable(true);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from  = "disponibilidad_horas ";
		
	$objr->setFrom($from);
	
	$where = " dh_activo=1" ;

	$objr->setWhere($where);
	$objr->setSize(10);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Descripcion";
    $arrAlias[3] = "Hora_Inicio";
	$arrAlias[4] = "Minuto_Inicio";
	$arrAlias[5] = "Hora_Fin";
	$arrAlias[6] = "Minuto_Fin";

	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "dh_codigo";
    $arrCampos[1] = "dh_codigo"; 
    $arrCampos[2] =	"dh_descripcion";
    $arrCampos[3] =	"dh_hora_inicio";
	$arrCampos[4] = "dh_minuto_inicio";
	$arrCampos[5] = "dh_hora_fin";
	$arrCampos[6] = "dh_minuto_fin";

    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
	
}

if ($rc_codigo == 3 || $rc_codigo==4){

	$rango = "";
	$rpta = "";
	$body="";
	$npag = 1;
	$orden = "restriccion_codigo";
    $buscam = "";
    $torder="ASC";

	$objr = new MyGrilla;
	$objr->setDriver_Coneccion(db_name());
    $objr->setUrl_Coneccion(db_host());
    $objr->setUser(db_user());
    $objr->setPwd(db_pass());
    $objr->setOrder($orden);
	$objr->setFindm($buscam);
	//$objr->setNoSeleccionable(true);
	$objr->setFont("color=#000000");
	$objr->setFormatoBto("class=button");
	$objr->setFormaTabla(" class=FormTABLE style='width:100%' cellspacing='1' cellpadding='0' border='0' ");
	$objr->setFormaCabecera(" class=ColumnTD ");
	$objr->setFormaItems(" class=DataTD onMouseout=this.style.backgroundColor='' onMouseover=this.style.backgroundColor='#F4F4F4'");
	$objr->setTOrder($torder);
	
	$from  = "wfm_restriccion ";
		
	$objr->setFrom($from);
	
	$where = " rc_codigo in ( " . $rc_codigo . ")" ;

	$objr->setWhere($where);
	$objr->setSize(10);
	$objr->setUrl($_SERVER["PHP_SELF"]);
	$objr->setPage($npag);
	$objr->setMultipleSeleccion(false);
	// Arreglo de Alias de los Campos de la consulta
    $arrAlias[0] = "root";
    $arrAlias[1] = "Codigo";
    $arrAlias[2] = "Descripcion";
    //$arrAlias[3] = "Hora_Inicio";
	//$arrAlias[4] = "Minuto_Inicio";
	//$arrAlias[5] = "Hora_Fin";
	//$arrAlias[6] = "Minuto_Fin";

	// Arreglo de los Campos de la consulta
    $arrCampos[0] = "restriccion_codigo";
    $arrCampos[1] = "restriccion_codigo"; 
    $arrCampos[2] =	"restriccion_descripcion";
    //$arrCampos[3] =	"dh_hora_inicio";
	//$arrCampos[4] = "dh_minuto_inicio";
	//$arrCampos[5] = "dh_hora_fin";
	//$arrCampos[6] = "dh_minuto_fin";

    
	$objr->setAlias($arrAlias);
	$objr->setCampos($arrCampos);
	
	$body = $objr->Construir();  //ejecutar
	//echo $objr->getmssql();
	$objr = null;
	echo $body;
	echo "<br>";
	echo Menu("../menu.php");
	
}


?>



<script event="onclick()" for="document">popCal.style.visibility = "hidden";</script>


<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>">
<!--<input type="hidden" id="rc_codigo" name="rc_codigo" value="<?php echo $rc_codigo?>"/>-->

<input type='hidden' id='area_codigo' name='area_codigo' value="<?php echo $area ?>">

</form>
</BODY>
</HTML>