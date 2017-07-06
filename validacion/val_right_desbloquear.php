<?php header("Expires: 0"); ?>
<?php
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Asistencias.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php");
require_once("../includes/clsCA_Asistencia_Responsables.php"); 
require_once("../includes/clsCA_Tiempos.php"); 
require_once("../includes/clsCA_Eventos.php");
require_once("../includes/clsCA_Empleados.php");
require_once("../includes/clsCA_Usuarios.php");

$id=$_SESSION["empleado_codigo"];
$empleado="";
$turno_codigo="0";
$tipo_area_codigo="1";
$tipo="0";
$vac="";
$tipo_area_codigo_empleado="";
$tiempo_derg='0';
$fecha_actual="";

$as=new ca_asistencia();
$as->MyUrl = db_host();
$as->MyUser= db_user();
$as->MyPwd = db_pass();
$as->MyDBName= db_name();

$ar=new ca_asistencia_responsables();
$ar->MyUrl = db_host();
$ar->MyUser= db_user();
$ar->MyPwd = db_pass();
$ar->MyDBName= db_name();

$ainc=new ca_asistencia_incidencias();
$ainc->MyUrl = db_host();
$ainc->MyUser= db_user();
$ainc->MyPwd = db_pass();
$ainc->MyDBName= db_name();

$e=new ca_eventos();
$e->MyUrl = db_host();
$e->MyUser= db_user();
$e->MyPwd = db_pass();
$e->MyDBName= db_name();

$u = new ca_usuarios();
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$o = new ca_tiempos();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$emp = new ca_empleados();
$emp->MyUrl = db_host();
$emp->MyUser= db_user();
$emp->MyPwd = db_pass();
$emp->MyDBName= db_name();

$u->empleado_codigo = $id;//responsable del empleado
//echo $id;
$r = $u->Identificar();
$nombre_usuario= $u->empleado_nombre;
$area= $u->area_codigo;
$area_descripcion= $u->area_nombre;
$tipo_area_codigo= $u->tipo_area_codigo;
$fecha_actual= $u->fecha_actual;

if (isset($_GET["tipo"])) $tipo = $_GET["tipo"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_GET["empleado_cod"])) {
    $empleado_cod = $_GET["empleado_cod"];
    $as->empleado_codigo=$empleado_cod;
    $as->fecha=$fecha;	  
    $rpta=$as->verificar_incidencia_vacaciones();
    $vac=$as->vac;
}

if (isset($_POST["empleado_cod"])) $empleado_cod = $_POST["empleado_cod"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if (isset($_POST["tipo"])) $tipo = $_POST["tipo"];

$as->empleado_codigo=$empleado_cod;
$u->empleado_codigo = $empleado_cod;
$r = $u->Identificar();
$tipo_area_codigo_empleado  = $u->tipo_area_codigo;
$u->getResponsableCodigo();
$id = $u->responsable_origen;

//Modificar turno
if (isset($_POST['hddmodturno'])){
    if ($_POST['hddmodturno']=='SVE'){
        $as->asistencia_codigo = $_GET['codigo'];
        $as->turno_codigo=$_POST['turno_codigo_' . $_GET['codigo']];
        $as->empleado_modifica_turno = $id;		
        $mensaje = $as->actualizar_turno();
        if($mensaje=='OK'){
?>
		<script type="text/javascript">
		 //alert('Guardado exitosamente!!');
		</script>
		<?php
		}
	}
  }

if (isset($_POST['hddanular'])){
  if ($_POST['hddanular']=='ANUL'){
	    $as->asistencia_codigo = $_GET['codigo'];
		$mensaje = $as->anular_asistencia();
		//echo "empleado_codigo:" . $empleado_codigo;
		if($mensaje=='OK'){
		?>
		<script type="text/javascript">
		  //alert('Anulado exitosamente!!');
		  window.parent.frames[1].location="val_left.php?f=<?php echo $fecha ?>&e=<?php echo $empleado_cod ?>";
		</script>
		<?php
		}
	}
}
//Quitar supervisor
if (isset($_POST['hdddelsup'])){
  if ($_POST['hdddelsup']=='DEL'){
		//-- registrar
	    $ar->empleado_codigo = $empleado_cod;
		$ar->asistencia_codigo = $_GET['codigo'];
		$ar->responsable_codigo = $_GET['responsable'];
		
		$mensaje = $ar->eliminar_responsable_elegido();
		if($mensaje=='OK'){
		?>
		<script type="text/javascript">
		 //alert('Supervisor eliminado exitosamente!!');
		</script>
		<?php
		}
	}
}	

//Aprobar evento(convertir a incidencia)
if (isset($_POST['hddaprobar'])){
    if ($_POST['hddaprobar']=='APRO'){

        $ainc->empleado_codigo = $empleado_cod;
        $ainc->evento_codigo=$_GET['event'];
        $ainc->asistencia_codigo = $_GET['asist'];
        $ainc->incidencia_codigo = $_GET['inc'];
        $ainc->cod_campana = $_GET['codcamp'];
        $ainc->responsable_codigo = $id;
        //$ainc->tiempo_derg="0"
        $ainc->tiempo_derg=$_GET['tiempo_derg'];
        $mensaje = $ainc->registrar_evento_a_incidencia();
        if($mensaje=='OK'){
?>
		<script type="text/javascript">
		 alert('Evento Aprobado!!');
		 window.parent.frames[1].location="val_left.php?f=<?php echo $fecha ?>&e=<?php echo $empleado_cod ?>";		 
		</script>
		<?php
		}else{
		  echo "error: " . $mensaje;
		}
	}	
	
}

//eliminar incidencia
if (isset($_POST['hdddelinc'])){
    if ($_POST['hdddelinc']=='DEL'){
        //-- registrar
        $ainc->asistencia_incidencia_codigo = $_GET['codigo'];
        $ainc->asistencia_codigo = $_GET['asistencia_codigo'];
        $ainc->empleado_codigo = $_GET['emp'];
        $ainc->responsable_codigo = $_GET['responsable'];
        
        $mensaje = $ainc->eliminar_incidencia();
        if($mensaje=='OK'){
?>
		<script type="text/javascript">
                    //alert('Incidencia eliminado exitosamente!!');
                    window.parent.frames[1].location="val_left.php?f=<?php echo $fecha ?>&e=<?php echo $empleado_cod ?>";		 
		</script>
<?php
        }
    }
}
?>

<html>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Asistencias del Día</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="../../default.js"></script>
<!--<script type="text/javascript" src="../tecla_f5.js"></script>
<script type="text/javascript" src="../mouse_keyright.js"></script>-->
<script type="text/javascript">
function actualizar_izq(){
window.parent.frames[1].location="val_left_desbloquear.php?f=<?php echo $fecha ?>&e=<?php echo $empleado_cod ?>";
return false;
}
function cmdIncidencias_onclick(){//METODO PARA AGREGAR INCIDENCIAS
 var empleado_codigo="<?php echo $empleado_cod ?>";
 var empleado="<?php echo $empleado ?>";
 var responsable_codigo="<?php echo $id ?>";
 var fecha="<?php echo $fecha?>";
 var area_responsable="<?php echo $area?>";
 var ivac="<?php echo $vac ?>";
 
 if(empleado_codigo !=0){
	 var num=document.frm.num.value; 
	 if(num!=0){ //registro de incidencias de asistencia
		 var rpta=Registro();
			if (rpta != '' ) {	
			  if(ivac!=1){	
			     var arr=rpta.split('_');
                             CenterWindow("registro_incidencias.php?asistencia=" + arr[0] + "&responsable=" + responsable_codigo + "&empleado=" + empleado_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");
			     /*if(arr[1]==1)
				  CenterWindow("registro_incidencias.php?asistencia=" + arr[0] + "&responsable=" + responsable_codigo + "&empleado=" + empleado_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");
			      else alert('No ha sido elegido en la asistencia, agreguese como responsable');*/
			  }else alert('No puede registrar otra incidencia si existe una incidencia de vacaciones!!');
			}
	  }else//registro de incidencias sin asistencia definida
		   CenterWindow("registro_incidencias.php?empleado=" + empleado_codigo + "&responsable=" + responsable_codigo + "&num=" + num + "&fecha=" + fecha + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");			
}
else alert('Seleccione empleado');
	  
}
function cmdTiempos_onclick(){
 var empleado_codigo="<?php echo $empleado_cod ?>";
 var area_responsable="<?php echo $area?>";
 var responsable_codigo="<?php echo $id ?>";
 var fecha="<?php echo $fecha?>";
 if(empleado_codigo !=0){
  alert("Opción Deshabilitada");	
}else alert('Seleccione empleado');
	  
}

function aprobar_evento(asistencia_codigo,incidencia_codigo,evento_codigo){
var fecha="<?php echo $fecha?>";

	var o=document.getElementById('cod_campana_' + asistencia_codigo + '_' + evento_codigo);
	var t=document.getElementById('tiempo_' + asistencia_codigo + '_' + evento_codigo);
	var hi=document.getElementById('hora_inicio_' + asistencia_codigo + '_' + evento_codigo);
    var hf=document.getElementById('hora_fin_' + asistencia_codigo + '_' + evento_codigo);

	if (o.value==0 || o.value==''){
			alert('Seleccione Unidad de Servicio');
			return false;
		}
		var cod_campana=o.value;
		var tiempo=t.value;
		var hora_i=hi.value;
		var hora_f=hf.value;
		
	  if(incidencia_codigo==41){
		//CenterWindow("sel_tiempo.php?empleado=<?php echo $empleado_cod ?>&responsable=<?php echo $id ?>&asistencia=" + asistencia_codigo + "&incidencia=" + incidencia_codigo + "&campana=" + cod_campana + "&evento=" + evento_codigo + "&fech=" + fecha + "&tiempo=" + tiempo,"ModalChild",550,150,"yes","center");			  	 
		    var valor = window.showModalDialog("sel_tiempo.php?empleado=<?php echo $empleado_cod ?>&responsable=<?php echo $id ?>&asistencia=" + asistencia_codigo + "&incidencia=" + incidencia_codigo + "&campana=" + cod_campana + "&evento=" + evento_codigo + "&fech=" + fecha + "&tiempo=" + tiempo, "Seleccion Incidencias","dialogWidth:550px; dialogHeight:150px");
			if (valor == ""){
			 return;
		    }
		}
		else{
		    valor="0";	
		}
		if (confirm('Seguro de aprobar evento seleccionado?')==false) return false;
		document.frm.action +="?asist=" + asistencia_codigo + "&inc=" + incidencia_codigo + "&codcamp=" + cod_campana + "&tim=" + tiempo + "&event=" + evento_codigo + "&hi=" + hora_i + "&hf=" + hora_f + "&tiempo_derg=" + valor;
		document.frm.hddaprobar.value='APRO';
		document.frm.submit();
				 	  
}

function cmdModificar_onclick(){
 var rpta=Registro();
    if (rpta != '' ) {
	var arr=rpta.split("_");
	var o=document.getElementById('turno_codigo_' + arr[0]);
	if(o.disabled==false){	
		if (o.value==0){
			alert('Seleccione Turno');
			return false;
		}
		if (confirm('Seguro de modificar turno a la asistencia seleccionada?')==false) return false;
		  document.frm.action +="?codigo=" + arr[0];
		  document.frm.hddmodturno.value='SVE';
		  document.frm.submit();
	 }else{
	   alert('Impedido de modificar turno!!');
	 } 
	  
	}  
}

function cmdAnular_onclick(){
 var rpta=Registro();
    if (rpta != '' ) {
	var arr=rpta.split("_");
    if (confirm('Seguro de anular la asistencia seleccionada?')==false) return false;
	  document.frm.action +="?codigo=" + arr[0];
	  document.frm.hddanular.value='ANUL';
	  document.frm.submit();
	}  
}

function cmdModificar_incidencia_onclick(asistencia,asistencia_incidencia,empleado,responsable,incidencia,incidencia_editable,flag_responsable_asistencia){
var responsable_codigo="<?php echo $id ?>";
var accion="";
if(incidencia_editable==0){ //incidencia de vacaciones
	alert('Incidencia no modificable!!');
 }
else{	
      if(flag_responsable_asistencia==1){
	  	   if(responsable!=responsable_codigo){	
				var fecha_actual="<?php echo $fecha_actual?>";	
				var fecha="<?php echo $fecha ?>";
				var nummes=(fecha.substring(6,10) + fecha.substring(3,5));
				var nummesactual=(fecha_actual.substring(6,10) + fecha_actual.substring(3,5));
				if(nummes*1<nummesactual*1) {
				   accion="";
				   alert("No puede modificar porque la asistencia no se encuentra en el mes actual!!")
				}else{
				   accion="mod";
				 }
		    }else accion="mod";
		    
			 if(accion=="mod"){   
		    	var area_responsable="<?php echo $area?>";
				CenterWindow("modificar_incidencias.php?asistencia=" + asistencia + "&asistencia_incidencia_codigo=" + asistencia_incidencia + "&responsable=" + responsable_codigo + "&empleado=" + empleado + "&area=" + area_responsable,"ModalChild",650,400,"yes","center");			  	 
		     } 	
	   }else{
		 if(flag_responsable_asistencia==0) 
		   alert('Usted no registro la incidencia, agreguese como responsable.\nLuego de agregarse como responsable solo puede modificar de las asistencias del mes actual');
		} 
		
}			
}




function cmdEliminar_onclick(asistencia_codigo,asistencia_incidencia,empleado,responsable,incidencia,incidencia_editable,flag_responsable_asistencia){
var responsable_codigo="<?php echo $id ?>";
if(incidencia_editable==0){
		alert('Incidencia no eliminable!!');
}else{	
		if(flag_responsable_asistencia==1){ //incidencia de vacaciones
		     if(responsable!=responsable_codigo){	
				var fecha_actual="<?php echo $fecha_actual?>";	
				var fecha="<?php echo $fecha ?>";
				var nummes=(fecha.substring(6,10) + fecha.substring(3,5));
				var nummesactual=(fecha_actual.substring(6,10) + fecha_actual.substring(3,5));
				if(nummes*1<nummesactual*1) {
				   accion="";
				   alert("No puede eliminar porque la asistencia no se encuentra en el mes actual!!")
				}else{
				   accion="del";
				 }
		    }else accion="del";
		    
			 if(accion=="del"){ 
			      if (confirm('Seguro de eliminar incidencia?')==false) return false;
				  document.frm.action +="?asistencia_codigo=" + asistencia_codigo +  "&codigo=" + asistencia_incidencia + "&emp=" + empleado + "&responsable=" + responsable_codigo;
				  document.frm.hdddelinc.value='DEL';
				  document.frm.submit();
			  }		   
	    }else{
	 		if(flag_responsable_asistencia==0) 
	    	alert('Usted no registro la incidencia, agreguese como responsable.\nLuego de agregarse como responsable solo puede eliminar de las asistencias del mes actual');
	    } 
  }
}  

function Quitar_onclick(asistencia,responsable,incidencia){
var tipo="<?php echo $tipo?>";
switch(tipo){
case '1': // Mi grupo
		if(incidencia==0){
	       if (confirm('Seguro de eliminar al supervisor?')==false) return false;
			  document.frm.action +="?codigo=" + asistencia + "&responsable=" + responsable;
			  document.frm.hdddelsup.value='DEL';
			  document.frm.submit()
		 }else{
		  alert('Registró o Modificó incidencias!!, no puede eliminar supervisor');
		 }
		 
		 break;
case '2':	//Compartido	
			  if(incidencia==0){
			   if (confirm('Seguro de eliminar al supervisor?')==false) return false;
				  document.frm.action +="?codigo=" + asistencia + "&responsable=" + responsable;
				  document.frm.hdddelsup.value='DEL';
				  document.frm.submit()
			 }else{
			  alert('Registró incidencias!!, no puede eliminar supervisor');
			 }
		
		break;
	}
}

function Agregar_onclick(asistencia){
 var empleado_codigo="<?php echo $empleado_cod ?>";
 var responsable_codigo="<?php echo $id ?>";
 var area_codigo="<?php echo $area ?>";
 CenterWindow("elegir_otros_responsables.php?asistencia=" + asistencia + "&responsable=" + responsable_codigo + "&empleado=" + empleado_codigo + "&area=" + area_codigo,"ModalChild",700,600,"yes","center");
}


function Registro(){ 
 valor='';
 var r=document.getElementsByTagName('input');
 for (var i=0; i< r.length; i++) {
	var o=r[i];
	if (o.id=='rdo') {
		if (o.checked) {
			valor= o.value;
		}
	}
 }
 if ( valor =='' ){
    alert('Seleccione Registro');
 }
 return valor;
}
function verificar(val){
var td = document.getElementById('tdc');
var turno_asignado="<?php echo $turno_codigo ?>";
//alert(turno_asignado);
//alert(val)
if(val!=0){
	if(turno_asignado==val){
	   td.style.background="";
	 }else{
	   td.style.background="#FF3300"; 
	 }
	}else{
	   td.style.background="";
	}
}

function activar_servicio(){
	var area_codigo="<?php echo $area ?>";
	pag="servicios_area.php?area=" + area_codigo
	//alert(pag);
 	CenterWindow(pag,"ModalChild",800,600,"yes","center");
}

function buscar_servicio(asistencia,evento){
	var d = document.getElementById('txt_cod_campana_' + asistencia + '_' + evento);
	var c = document.getElementById('cod_campana_' + asistencia + '_' + evento);
	var valor = window.showModalDialog("servicios.php?area=<?php echo $area?>&filtro=" + d.value,"Servicios",'dialogWidth:600px; dialogHeight:500px');
	if (valor == "" || valor == "0" ){
		 return false;
	}
	
	arr_valor = valor.split("¬");
	c.value = arr_valor[0];
	d.value =  arr_valor[1];
	return;
}

function nada(){
return;
}
</script>

</head>
<body class="PageBODY">
<script type="text/javascript">
function Go(){return}
</script>
<script type='text/javascript' src='cvmenu_var.js'></script> 
<script type='text/javascript' src='menu_com.js'></script>
<?php
if($empleado_cod!= 0){
    $emp->empleado_codigo=$empleado_cod;
    $emp->fecha=$fecha;
    if ($emp->Query_Area_Piloto($area)!=false) $rpta=$emp->Query_Turno();
    else $rpta=$emp->Query();
    $empleado=$emp->empleado_nombre;
    $turno_codigo=$emp->turno_codigo;
    $turno=$emp->turno_descripcion;
    $ainc->empleado_codigo=$empleado_cod;
    $rpta=$ainc->Obtener_servicio_empleado();
    $servicio=$ainc->cod_campana;	
  ?> 
<form id='frm' name='frm' action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>
<center class="FormHeaderFont">Asistencias del Día</Center>
<br>
<center>
<img  src='../images/personal-off.gif' width='15' height='15' border='0'>
<font color=#333399><b><?php echo $empleado?></b></font>
</Center>

    <table  width='70%' align="center">
    <tr>
        <td align="right" width="45%"><b>Fecha:</b></td>
        <td>
            <input class="CA_Input"  style="TEXT-ALIGN: center; WIDTH: 90px" name="txtFecha" id="txtFecha" readOnly value='<?php echo $fecha ?>'/>
        </td>
    </tr>
    <tr>
        <td align="right"><b>Turno Asignado:</b></td>
        <td>
            <input class='input' style="TEXT-ALIGN: center; WIDTH: 130px" name="txtTurno" id="txtTurno" readOnly value='<?php echo $turno ?>'/>
        </td>
    </tr>
    </table>
    <br/>	
    <?php
        $as->asistencia_fecha=$fecha;
        $as->responsable_codigo=$id;
        $rpta=$as->sel_listado_registros_dia($area,$servicio,$turno_codigo,$tipo_area_codigo_empleado);
        $num=$as->num_asistencias;
        echo $rpta;
    ?>
<input type='hidden' id='hddmodturno' name='hddmodturno' value=""/>
<input type='hidden' id='hdddelsup' name='hdddelsup' value=""/>
<input type='hidden' id='hdddelinc' name='hdddelinc' value=""/>
<input type='hidden' id='hdddeltx' name='hdddeltx' value=""/>
<input type='hidden' id='hddaprobar' name='hddaprobar' value=""/>
<input type='hidden' id='hddanular' name='hddanular' value=""/>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_cod ?>"/>
<input type='hidden' id='empleado_nombres' name='empleado_nombres' value="<?php echo $empleado ?>"/>
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>"/>
<input type='hidden' id='tipo' name='tipo' value="<?php echo $tipo ?>"/>
<input type='hidden' id='num' name='num' value="<?php echo $num ?>"/>			
<input type="button" style="width:0; heigth:0" value="ok" id="cmdx" name="cmdx" onClick="actualizar_izq()"/>
<?php
}
?>
</form>
</body>
</html>