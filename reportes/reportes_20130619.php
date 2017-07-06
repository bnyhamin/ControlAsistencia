<?php header("Expires: 0");

require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
//require_once("../../includes/librerias.php");
require_once("../../Includes/MyCombo.php");
require_once("../includes/clsCA_Usuarios.php");
require_once("../includes/clsCA_Areas.php");
require_once("../includes/clsCA_Empleado_Rol.php");

$id = "";
$nombre="";

$id = $_SESSION["empleado_codigo"];
$nombre=$_SESSION["empleado_nombres"];

$fecha_desde="";
$fecha_hasta="";
$rut="";

$cod_campana=0;
$area_codigo='00';// Estaba  inicializada en comillas, lo puse en cero.
$responsable_codigo=0;
$area_descripcion='';
$rol_ger='0';
$disabled_responsable="";
$disabled_campana="";
$opcion="0";
$is_reporte_42 = 0;
$usr = new ca_usuarios();
$usr->MyUrl = db_host();
$usr->MyUser= db_user();
$usr->MyPwd = db_pass();
$usr->MyDBName= db_name();

$obj = new areas();
$obj->MyUrl = db_host();
$obj->MyUser= db_user();
$obj->MyPwd = db_pass();
$obj->MyDBName= db_name();

$o = new ca_empleado_rol();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();
$o->MyDBName= db_name();

$usr->empleado_codigo = $id;
$r = $usr->Identificar();
$empleado = $usr->empleado_nombre;
$area = $usr->area_codigo;
$area_nombre = $usr->area_nombre;
$jefe = $usr->empleado_jefe; // responsable area
$fecha = $usr->fecha_actual;
$anio=substr($fecha,6); 
$mes=substr($fecha,3,2); 

$obj->area_codigo=$area;
$rpta=$obj->Query();
$area_desc=$obj->area_descripcion;



$CodCadena=$obj->TreeRecursivo($area);


$o->empleado_codigo=$id;
$o->rol_codigo=3;
$r=$o->Verifica_rol();
if($r=="OK"){
	$rol_ger='0';
	$rol_sup='0';
}else{
	$o->rol_codigo=6;
	$r=$o->Verifica_rol();
    if($r=="OK"){ 
		$rol_ger='1';	
        $rol_sup='0';
	}else {
		$o->rol_codigo=8;
		$r=$o->Verifica_rol();
	    if($r=="OK"){
		 $rol_ger='0';
		 $rol_sup='0';	
	    }else{
	     $o->rol_codigo=1;
		 $r=$o->Verifica_rol();
	     if($r=="OK"){
		 $rol_ger='0';
		 $rol_sup='1';	
	     }else{
	         $o->rol_codigo=2;
			 $r=$o->Verifica_rol();
		     if($r=="OK"){
			 $rol_ger='0';
			 $rol_sup='1';	
	        }
	    }  
    }  
  }
}
/*
echo "rol_ger:" . $rol_ger ."<br>";
echo "rol_sup:" . $rol_sup ."<br>";
*/
$id = $_SESSION["empleado_codigo"];
if (isset($_POST["Anio"])) $anio = $_POST["Anio"];
if (isset($_POST["Mes"]))  $mes= $_POST["Mes"];
if (isset($_POST["area_codigo"]))  $area_codigo= $_POST["area_codigo"];
if (isset($_POST["responsable_codigo"]))  $responsable_codigo= $_POST["responsable_codigo"];
if (isset($_POST["reportes"]))  $opcion= $_POST["reportes"];
if (isset($_POST["area_descripcion"]))  $area_descripcion= $_POST["area_descripcion"];
if (isset($_POST["fecha_desde"])) $fecha_desde = $_POST["fecha_desde"];
if (isset($_POST["fecha_hasta"])) $fecha_hasta = $_POST["fecha_hasta"];

//echo "<br> inicio-".$area_codigo."-fin<br>"; // comentar
			if($area_codigo!=0){
				switch($opcion){
				case 1: $disabled_responsable="";	
					    $disabled_campana="";
					    break;
				case 2: $disabled_responsable="disabled";	
					    $disabled_campana="";
					    break;
			    case 3: $disabled_responsable="disabled";	
					    $disabled_campana="disabled";
	        			break;
	        
				case 4: $disabled_responsable="disabled";	
					    $disabled_campana="disabled";
	        			break;
						 
				case 5: $disabled_responsable="disabled";	
					    $disabled_campana="";
	        			break;
				        
				case 6: $disabled_responsable="disabled";	
					    $disabled_campana="disabled";
	        			break;
				        
				case 7: $disabled_responsable="disabled";	
					    $disabled_campana="disabled";
	        			break;
						
				case 8: $disabled_responsable="disabled";	
					    $disabled_campana="disabled";
	        			break;
				        
				case 9: $disabled_responsable="disabled";	
					    $disabled_campana="disabled";
	        			break;
				        
				case 10: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        			 break;
				         
				case 11: $disabled_responsable="disabled";	
					     $disabled_campana="";
	        			 break;
						 		  		
				case 12: $disabled_responsable="disabled";	
					     $disabled_campana="";
	        			 break;
				         
				case 13: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        			 break;
				         
				case 14: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        			 break;
						  		  		 		 		 			
				case 15: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        			 break;
				         
				case 16: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        			 break;	
				         
				case 17: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        			 break;	
				case 19: $disabled_responsable="disabled";	
					     $disabled_campana="";
                         //$disabled_campana="disabled";
	        			 break;	
                         //se cambio la configuracion al igual que el  20 mcamayoc
                case 20: $disabled_responsable="disabled";	
                         $disabled_campana="disabled";
                         break;
                case 24: $disabled_responsable="";
                         //$disabled_responsable="disabled";	
                         //$disabled_campana="disabled";
                         $disabled_campana="";
                         break;	
                         	
				case 25: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        		     break;
				case 26: $disabled_responsable="disabled";	
					     $disabled_campana="disabled";
	        		     break;
				case 42: 
						$is_reporte_42 = 1;
	        		     break;	 	          				    
				}
			}	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title><?php echo tituloGAP() ?>- Reportes</title>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css" />
<!--Fecha-->
<link rel="stylesheet" type="text/css" media="all" href="../js/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../asistencias/app/app.js"></script>
<script type="text/javascript" src="../js/calendar.js"></script>
<script type="text/javascript" src="../js/lang/calendar-en.js"></script>
<script type="text/javascript" src="../js/calendar-setup.js"></script>

<style type="text/css">
	.oculto{display:none}
	.no_oculto{display:block}
</style>
</HEAD>
<script LANGUAGE=javascript>
var server="<?php echo $ip_app ?>";
var url_jr="<?php echo $url_jreportes ?>";

function cmdCancelar_onclick() {
	self.location.href="../menu.php";
}
function Ir(){
	self.location.href="reportes_generados.php";
}

function Generar(){
var usuario="<?php echo $id ?>";	
var opcion=document.frm.reportes.value;
//alert (opcion); // para comentar
if(opcion==0){
	alert('Seleccione Reporte');
	document.frm.reportes.focus();
	return false;
}

if (validarCampo('frm','Anio')!=true) return false;
var anio=document.frm.Anio.value;
if (validarCampo('frm','Mes')!=true) return false;
var mes=document.frm.Mes.value;
//alert (document.frm.area_codigo.value); //para comentar
if(opcion!=5 && opcion!=38 && opcion!=39 && opcion!=40 && opcion!=41 && opcion!=43 && opcion!=44 && opcion!=6 && opcion!=15 && opcion!=19 && opcion!=37 && opcion!=27 && opcion!=28 && opcion!=35){
	if(document.frm.area_codigo.value=='' || document.frm.area_codigo.value=='00'){
	//if(document.frm.area_codigo.value==''){
	 alert('Busque area');
	 document.frm.area_descripcion.focus();
	 return false;	
	}
}

if( opcion!=15){
	var area=document.frm.area_codigo.value;
	var cod_campana=document.frm.cod_campana.value;
	var responsable_codigo=document.frm.responsable_codigo.value;
}


if(opcion==1 || opcion==2 || opcion==4 || opcion==7 || opcion==8 || opcion==13 || opcion==22 || opcion==24 ){
  // alert(url_jr);
  var valor = window.showModalDialog(url_jr + "Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&fecha_inicio=0&fecha_fin=0&area_codigo=" + area + "&responsable=" + responsable_codigo + "&campana=" + cod_campana + "&usuario_id=" + usuario + "&opcion=" + opcion , "Reporte","dialogWidth:400px; dialogHeight:150px");
}

if(opcion==35){
  var vwin = window.open(url_jr + "RRHH/admin/admingenerar.jsp", "ReporteJerarquia","dialogWidth:400px; dialogHeight:150px");
  vwin.focus();
}

if(opcion==5 || opcion==6 || opcion==27 || opcion==28){	
  var v = window.showModalDialog("sel_rango_fechas.php","Fechas",'dialogWidth:500px; dialogHeight:130px');
  if (v=='0' || v=='') return false;
  array=v.split("@");
  var valor = window.showModalDialog(url_jr + "Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&fecha_inicio=" + array[0] + "&fecha_fin=" + array[1]+ "&area_codigo=0&campana=0&responsable=0&usuario_id=" + usuario + "&opcion=" + opcion , "Reporte","dialogWidth:400px; dialogHeight:150px");
}

/*
if(opcion==6){
  var valor = window.showModalDialog(url_jr + "Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&fecha_inicio=0&fecha_fin=0&area_codigo=0&campana=0&responsable=0&usuario_id=" + usuario + "&opcion=" + opcion , "Reporte","dialogWidth:400px; dialogHeight:150px");
}
*/

if(opcion==3 || opcion==9 || opcion==10 || opcion==11 || opcion==14 ){
   var valor = window.showModalDialog("lista_incidencias.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:600px; dialogHeight:400px");
   //CenterWindow("lista_incidencias.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion ,"ModalChild",600,400,"yes","center");			  	 

}

if(opcion==12){
  var valor = window.showModalDialog("lista_empleados_area.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:800px; dialogHeight:600px");
 	
}
if(opcion==15){
	var rol_ger="<?php echo $rol_ger ?>";
	if(rol_ger==0){	
		var valor = window.showModalDialog("sel_gerencia.php?anio=" + anio + "&usuario=" + usuario + "&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:550px; dialogHeight:150px");
		if (valor == "") return false;
		
		frames['ifrm'].location.href ="areas.php?area_codigo=" + valor + "&anio=" + anio + "&usuario=" + usuario + "&opcion=" + opcion;
       	//CenterWindow("sel_gerencia.php?anio=" + anio + "&usuario=" + usuario + "&opcion=" + opcion ,"ModalChild",550,200,"yes","center");			  	 
	}else{
	  var cods_area="<?php echo $CodCadena ?>";
	  var area="<?php echo $area ?>";	
	  var valor = window.showModalDialog(url_jr + "Gap/generar.jsp?anio=" + anio + "&mes=0&fecha_inicio=0&fecha_fin=0&area_codigo=" + area + "&campana=0&responsable=0&usuario_id=" + usuario + "&opcion=" + opcion + "&cods_area=" + cods_area, "Reporte","dialogWidth:400px; dialogHeight:150px");
	
	}
}
if(opcion==16){
   //var valor = window.showModalDialog("lista_empleados_area.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:800px; dialogHeight:600px");
  CenterWindow("lista_empleados_area.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion ,"ModalChild",800,600,"yes","center");			  	 

}
if(opcion==17 ){
   var valor = window.showModalDialog("lista_eventos.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:600px; dialogHeight:400px");
}

if(opcion==18){
   var valor = window.open("../../reportes/Rep_Detalle_Personal.php?area=" + document.frm.area_codigo.value, 18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
   valor.focus();  
 }
if(opcion==19){//se cambio la configuracion al igual que el  20 mcamayoc
   //var valor = window.showModalDialog("lista_incidencias.php?anio=" + anio + "&mes=" + mes + "&area=0&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:600px; dialogHeight:400px");
   var valor = window.showModalDialog("lista_incidencias.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=0&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:600px; dialogHeight:400px");
 }
 
if(opcion==20){
   var valor = window.showModalDialog("lista_incidencias.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=0&responsable=0&opcion=" + opcion , "Seleccion Incidencias","dialogWidth:600px; dialogHeight:400px");
 }
 
if(opcion==37){
	if (validarCampo('frm','rut')!=true) return false;
	var rut = document.getElementById('rut').value;
	CenterWindow("../gestionturnos/procesos.php?opcion=reporte37&te_anio=" + anio + "&empleado_dni=" + rut ,"Reporte",200,180,"yes","center");
}
 
if(opcion==25){
	if (document.frm.fecha_desde.value==0 || document.frm.fecha_hasta.value==0){
		alert("Seleccione Intervalo de Fechas");
		return false;
	} else {
		window.open(url_jr + "Gap/reporte_trabajo_madrugada.jsp?fecha_desde=" + document.frm.fecha_desde.value + "&fecha_hasta=" + document.frm.fecha_hasta.value + "&anio=" + anio + "&mes=" + mes + "&area_codigo=" + area + "&usuario=" + usuario + "&campana=0&responsable=0&opcion=" + opcion + "&area_descripcion=" + document.frm.area_descripcion.value);
	}
 }
 
 
 //////
 if(opcion==38){
    var fechai=document.getElementById('fecha_desde').value;
    var fechaf=document.getElementById('fecha_hasta').value;
    if(fechai==""){
        alert("Debe seleccionar fecha de inicio");
        return false;
    }
    
    if(fechaf==""){
        alert("Debe seleccionar fecha de fin");
        return false;
    }
    
    window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=1","Consolidado",820,350);
    
  //var valor = window.showModalDialog("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf, "Consolidado de Incidencias por validador","dialogWidth:820px; dialogHeight:350px");
 	
 }
 
 
 if(opcion==39){
    var fechai=document.getElementById('fecha_desde').value;
    var fechaf=document.getElementById('fecha_hasta').value;
    if(fechai==""){
        alert("Debe seleccionar fecha de inicio");
        return false;
    }
    
    if(fechaf==""){
        alert("Debe seleccionar fecha de fin");
        return false;
    }
    
    //window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=2","Consolidado",820,350);
    window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=2","Consolidado","width=1250px, height=350px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
    
  //var valor = window.showModalDialog("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf, "Consolidado de Incidencias por validador","dialogWidth:820px; dialogHeight:350px");
 	
 }
 
 
 
 if(opcion==40){
    var fechai=document.getElementById('fecha_desde').value;
    var fechaf=document.getElementById('fecha_hasta').value;
    if(fechai==""){
        alert("Debe seleccionar fecha de inicio");
        return false;
    }
    
    if(fechaf==""){
        alert("Debe seleccionar fecha de fin");
        return false;
    }
    
    //window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=3","Consolidado",820,350);
    window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=3","Consolidado","width=1020px, height=350px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
    
    //"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes"
  //var valor = window.showModalDialog("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf, "Consolidado de Incidencias por validador","dialogWidth:820px; dialogHeight:350px");
 	
        
        //var valor = window.open("http://" + server + "/tmreportes/Gap/reportes.jsp?opcion=generar&reporte_codigo=1&fecha_desde=" + document.getElementById('fecha_desde').value + "&fecha_hasta=" + document.getElementById('fecha_hasta').value, 18,"width=950px, height=600px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
 }
 
 
 if(opcion==41){
    var fechai=document.getElementById('fecha_desde').value;
    var fechaf=document.getElementById('fecha_hasta').value;
    if(fechai==""){
        alert("Debe seleccionar fecha de inicio");
        return false;
    }
    
    if(fechaf==""){
        alert("Debe seleccionar fecha de fin");
        return false;
    }
    
    //window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=2","Consolidado",820,350);
    window.open("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf+"&reporte=4","Consolidado","width=1100px, height=350px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
    
  //var valor = window.showModalDialog("consolidado_validador.php?fechai="+fechai+"&fechaf="+fechaf, "Consolidado de Incidencias por validador","dialogWidth:820px; dialogHeight:350px");
 	
 }
 
 if(opcion==43){
    var fechai=document.getElementById('fecha_desde').value;
    var fechaf=document.getElementById('fecha_hasta').value;
	var cod_ar="";
	if(document.frm.area_codigo.value=="00") cod_ar="0";	
	else cod_ar=document.frm.area_codigo.value;
    if(fechai==""){
        alert("Debe seleccionar fecha de inicio");
        return false;
    }
    if(fechaf==""){
        alert("Debe seleccionar fecha de fin");
        return false;
    }
    window.open("reporte_turno_especial_extendido.php?fechai="+fechai+"&fechaf="+fechaf+"&area="+cod_ar+"&reporte=1","Consolidado","width=1250px, height=350px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
 }
 
 if(opcion==44){
    var fechai=document.getElementById('fecha_desde').value;
    var fechaf=document.getElementById('fecha_hasta').value;
	var cod_ar="";
	if(document.frm.area_codigo.value=="00") cod_ar="0";	
	else cod_ar=document.frm.area_codigo.value;
    if(fechai==""){
        alert("Debe seleccionar fecha de inicio");
        return false;
    }
    if(fechaf==""){
        alert("Debe seleccionar fecha de fin");
        return false;
    }
    window.open("reporte_turno_especial_extendido.php?fechai="+fechai+"&fechaf="+fechaf+"&area="+cod_ar+"&reporte=2","Consolidado","width=1250px, height=350px, center=yes, menubar=yes, location=no, resizable=yes, scrollbars=yes");
 }
 
 
if(opcion==26){
	if (document.frm.fecha_desde.value==0 || document.frm.fecha_hasta.value==0){
		alert("Seleccione Intervalo de Fechas");
		return false;
	} else {
		window.open(url_jr + "Gap/reporte_pago_movilidad_nocturna.jsp?fecha_desde=" + document.frm.fecha_desde.value + "&fecha_hasta=" + document.frm.fecha_hasta.value + "&anio=" + anio + "&mes=" + mes + "&area_codigo=" + area + "&usuario=" + usuario + "&campana=0&responsable=0&opcion=" + opcion + "&area_descripcion=" + document.frm.area_descripcion.value);
	}
 } 

if(opcion==42){
   var valor = window.showModalDialog(url_jr+"Gap/generar.jsp?anio=" + anio + "&mes=" + mes + "&area_codigo=" + area + "&fecha_inicio=0&fecha_fin=0&responsable=" + responsable_codigo +"&campana=0&usuario_id=" + usuario+"&opcion=" + opcion, "Reporte","dialogWidth:400px; dialogHeight:150px");
 	// CenterWindow("lista_empleados_area.php?anio=" + anio + "&mes=" + mes + "&area=" + area + "&usuario=" + usuario + "&campana=" + cod_campana +  "&responsable=0&opcion=" + opcion ,"ModalChild",800,600,"yes","center");

   valor.focus();  
 }
 
}





function buscararea(search){
var rol_ger="<?php echo $rol_ger ?>";
if(rol_ger==1){	
	var area="<?php echo $area ?>";
	alert(area);
	CenterWindow("../manto/listaAreas.php?strbuscar=" + search + "&flag_gerente=1&area_codigo=" + area + "&todos=0","ModalChild",600,500,"yes","center");
}
else{
	CenterWindow("../manto/listaAreas.php?strbuscar=" + search + "&flag_gerente=0&area_codigo=0&todos=1","ModalChild",600,500,"yes","center");
 }
	return true;
}

function filtroAreas(codigo, descripcion){
	document.frm.area_codigo.value=codigo;
	document.frm.area_descripcion.value=descripcion;	
	document.frm.submit();
}

function Consulta(opcion){
	switch(parseInt(opcion)){
	case 1: habilitar(false,false,false);
	        break;	        
	case 2: habilitar(false,true,false);
	        break;	
	case 3: habilitar(false,true,true);
	        break;	        
	case 4: habilitar(false,true,true);
	        break;			 
	case 5: habilitar(true,true,true);
	        break;	        
	case 6: habilitar(true,true,true);
	        break;	        
	case 7: habilitar(false,true,true);
	        break;			
	case 8: habilitar(false,true,true);
	        break;        
	case 9: habilitar(false,true,true);
	        break;
	case 10: habilitar(false,true,true);
	        break;
	case 11: habilitar(false,true,false);
	        break; 		
	case 12: habilitar(false,true,false);
	        break;
	case 13: habilitar(false,true,true);
	        break;
	case 14: habilitar(false,true,true);
	        break;  		 		 		 			
	case 15: habilitar(true,true,true);
	        break;
	case 16: habilitar(false,true,true);
	        break;	
	case 17: habilitar(false,true,true);
	        break;	
    case 19: habilitar(false,true,false);
	        break;	
	         //se cambio la configuracion al igual que el  20 mcamayoc
	case 20: habilitar(false,true,false);
	        break;	
    case 22: habilitar(false,true,true);
	         break;				 
	case 24: habilitar(false,false,false);
	        break;			
	case 27: habilitar(true,true,true);
	        break;        
	case 28: habilitar(true,true,true);
	        break; 
  	case 35: habilitar(true,true,true);
	        break;                 
    case 38: habilitar(false,false,false);
	        break;        
    case 39: habilitar(false,false,false);
	        break;        
    case 40: habilitar(false,false,false);
	        break;        
    case 41: habilitar(false,false,false);
	        break;        
    case 42: habilitar(false,true,true);
	        break;   	 		 				 			     
	case 43: habilitar(false,true,true);
	        break;
	case 44: habilitar(false,true,true);
	        break;
	} 
	obj =  document.getElementById("tr_intervalo");	        
	obj1 = document.getElementById("tr_area");
	obj4 = document.getElementById("tr_rut");
        
        
        if(parseInt(opcion)==38 || parseInt(opcion)==39 || parseInt(opcion)==40 || parseInt(opcion)==41 || parseInt(opcion)==43 || parseInt(opcion)==44){
            $("#tr_intervalo").attr({
                'style':'display:block'
            });
        }else{
            $("#tr_intervalo").attr({
                'style':'display:none'
            });
        }
	
/*	if ( parseInt(opcion)==37 ){
		habilitar(false,true,true);
		obj.className="oculto";
		obj1.className="oculto";
	}else{
		obj.className="no_oculto";
		obj1.className="no_oculto";
	}

	

*/	
	if (opcion==25 || opcion==26){
		habilitar(false,true,true);
		obj.className="no_oculto";
	} else {
		obj.className="oculto";
	}
	if (parseInt(opcion)==37 ){
		habilitar(true,true,true);
		obj4.className="no_oculto";
	}else{
		obj4.className="oculto";
	}
	// if (opcion == 42) {		
	// 	document.frm.area_codigo.value = "<?php echo $area ?>";
	// 	document.frm.area_descripcion.value = "<?php echo $area_desc ?>";
	// 	// document.frm.bus.hide = true;
	// };

}

function habilitar(bus,responsable,campana){
	document.frm.bus.disabled=bus;
	document.frm.responsable_codigo.disabled=responsable;	
	document.frm.cod_campana.disabled=campana;	
}

function areas(anio,area,usuario,opcion,codigos){
var valor = window.showModalDialog(url_jr + "Gap/generar.jsp?anio=" + anio + "&mes=0&fecha_inicio=0&fecha_fin=0&area_codigo=" + area + "&campana=0&usuario_id=" + usuario + "&opcion=" + opcion + "&cods_area=" + codigos + "&responsable=0", "Reporte","dialogWidth:400px; dialogHeight:150px");		
}
			 
</script>

<BODY class="PageBODY" onLoad="return WindowResize(10,20,'center')">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF']  ?>" method="post">
<center class='FormHeaderFont' >Reportes</center>
<table CLASS='FormTable' WIDTH='60%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
		<TD class='CA_FieldCaptionTD' align=center colspan="2">&nbsp;
		</TD>
	</TR>
	<tr>
   <td class='ColumnTD' align='right'>Periodo&nbsp;</td>
    <td class='DataTD' align='left' width='60%'>
        <select id="Mes" name="Mes" class='select'  style='width:90px'>
              <option value='1' <?php if($mes==1) echo "selected" ?>>Enero</option>
              <option value='2' <?php if($mes==2) echo "selected" ?> >Febrero</option>
              <option value='3' <?php if($mes==3) echo "selected" ?>>Marzo</option>
              <option value='4' <?php if($mes==4) echo "selected" ?>>Abril</option>
              <option value='5' <?php if($mes==5) echo "selected" ?>>Mayo</option>
              <option value='6' <?php if($mes==6) echo "selected" ?>>Junio</option>
              <option value='7' <?php if($mes==7) echo "selected" ?>>Julio</option>
              <option value='8' <?php if($mes==8) echo "selected" ?>>Agosto</option>
              <option value='9' <?php if($mes==9) echo "selected" ?>>Setiembre</option>
              <option value='10' <?php if($mes==10) echo "selected" ?>>Octubre</option>
              <option value='11' <?php if($mes==11) echo "selected" ?>>Noviembre</option>
              <option value='12' <?php if($mes==12) echo "selected" ?>>Diciembre</option>
         </select>
         <select id='Anio' name='Anio'  class='select' style='width:60px'>
          <?php 
		    $anno=$anio;
		    for($i=$anio-4;$i<=$anio+4;$i++){
			  if($i==$anno) echo "\t<option value=". $i." selected>". $i ."</option>" . "\n";
			  else echo "\t<option value=". $i . ">". $i."</option>" ."\n";
	        } ?>
        </select>
    </td>
    <td></td>
   </tr>
   <TR>
		<TD class='ColumnTD' width='30%' align=right>
		Tipo de Reporte&nbsp;
		</TD>
		<TD class='DataTD' align=left >
		  <select  class='select' name='reportes' id='reportes'style="width:400px" onchange='Consulta(this.value)'>
		  	<option value="0">----Seleccionar----</option>
		  	<?php //if($rol_ger==0){ 
		  	?>
		  	<option value="1" <?php  if($opcion==1) echo "selected"; ?> >1. Detalle de Incidencia por Empleado</option>
		  	<option value="2" <?php  if($opcion==2) echo "selected"; ?> >2. Detalle de Incidencia por Servicio</option>
		  	<option value="3" <?php  if($opcion==3) echo "selected"; ?> >3. Número de Empleados con Incidencias </option>
		  	<option value="4" <?php  if($opcion==4) echo "selected"; ?> >4. Total de Incidencias por Servicio </option>
          	<option value="5" <?php  if($opcion==5) echo "selected"; ?> >5. Listado de Tardanzas(solo RRHH)</option>
		  	<option value="6" <?php  if($opcion==6) echo "selected"; ?> >6. Listado de Faltas(solo RRHH) </option>
		  	<option value="7" <?php  if($opcion==7) echo "selected"; ?> >7. Detalle de Posiciones del Dia</option>
		  	<option value="8" <?php  if($opcion==8) echo "selected"; ?> >8. Detalle de Diario Gestión</option>
		  	<option value="9" <?php  if($opcion==9) echo "selected"; ?> >9. Detalle Diario por Empleado e Incidencia</option>
          	<option value="10" <?php  if($opcion==10) echo "selected"; ?> >10. Detalle Diario por Servicio e Incidencia</option>
		  	<option value="11" <?php  if($opcion==11) echo "selected"; ?> >11. Cantidad de Incidencias por Empleado y Servicio</option>
		  	<option value="12" <?php  if($opcion==12) echo "selected"; ?> >12. Cantidad de Incidencias de Empleado y por Dia</option>
		  	<option value="13" <?php  if($opcion==13) echo "selected"; ?> >13. Listado de Tardanzas de Area</option>
		  	<option value="14" <?php  if($opcion==14) echo "selected"; ?> >14. Listado de Incidencias de Area</option>
			<?//php }
			?>	
			<option value="15" <?php  if($opcion==15) echo "selected"; ?> >15. Consolidado de Incidencias por Area de Gerencia</option>
			<option value="16" <?php  if($opcion==16) echo "selected"; ?> >16. Reporte de Compensación de Horas Adicionales</option>
			<option value="17" <?php  if($opcion==17) echo "selected"; ?> >17. Listado de Eventos sin validar de Area</option>
			<option value="18" <?php  if($opcion==18) echo "selected"; ?>  >18. Detalle de Personal y Servicio asignado (SGRRHH)</option>
			<option value="19" <?php  if($opcion==19) echo "selected"; ?>  >19. Reporte Mensual de Incidencias por Areas y Servicio</option>
			<option value="20" <?php  if($opcion==20) echo "selected"; ?>  >20. Listado de Incidencias modificadas de Area</option>
			<option value="22" <?php  if($opcion==22) echo "selected"; ?>  >22. GAP vs CPSA</option>
<!--		  <option value="24" <?php  if($opcion==24) echo "selected"; ?> >23. Detalle de Incidencia por Empleado sin HTOP</option>     -->
		  	<option value="37" <?php  if($opcion==37) echo "selected"; ?> >24. Programacion Publicado y Ejecutado </option>
		  	<option value="27" <?php  if($opcion==27) echo "selected"; ?> >25. Listado de Tardanzas - Uso General</option>
			<option value="28" <?php  if($opcion==28) echo "selected"; ?> >26. Listado de Faltas - Uso General</option>
			<option value="35" <?php  if($opcion==35) echo "selected"; ?> >27. Reporte de Jerarquia</option>
                        
          	<option value="38" <?php  if($opcion==38) echo "selected"; ?> >28. Consolidado de incidencias por validador </option>
          	<option value="39" <?php  if($opcion==39) echo "selected"; ?> >29. Reporte detallado por incidencia validable</option>
          	<option value="40" <?php  if($opcion==40) echo "selected"; ?> >30. Reporte consolidado por incidencia validable</option>
          	<option value="41" <?php  if($opcion==41) echo "selected"; ?> >31. Reporte consolidado por validador </option>
          	<option value="42" <?php  if($opcion==42) echo "selected"; ?> >32. Reporte de Detalle de Horas adicionales </option>
			<option value="43" <?php  if($opcion==43) echo "selected"; ?> >33. Reporte Turno Especial </option>
			<option value="44" <?php  if($opcion==44) echo "selected"; ?> >34. Reporte Turno Extendido </option>
      
		  <!--
			<option value="25" <?php  if($opcion==25) echo "selected"; ?> >24. Reporte de Bonificación por Trabajo Madrugada</option>
			<option value="26" <?php  if($opcion==26) echo "selected"; ?> >25. Reporte de Pago por Movilidad Nocturna</option>
		  -->
		 </select>
		</TD>
	</TR>
   <?php 
   	 $disabled="";
   	 if($rol_sup=='1'){
	 	$area_codigo=$area;
		$area_descripcion=$area_desc;		
     	$disabled="disabled";
   	 }
	 ?>
	<tr>
	<td class='ColumnTD' align='right'>Area&nbsp;</td>
	<td class='DataTD'>
		   <input  class='Input' type='hidden' name='area_codigo' id='area_codigo' value="<?php echo $area_codigo ?>" />
           <input  class='Input' type='text' name='area_descripcion' id='area_descripcion' value='<?php echo $area_descripcion  ?>' maxlength="120" style='width:350px;' <?php  echo $disabled ?> />
      <?php if($rol_sup=='0'){?>
	       <img id='bus' src="../images/buscaroff.gif" alt="buscar area" onclick="return buscararea(document.frm.area_descripcion.value)" style="cursor:hand" />
	  <?php } else{?>
	  	    <img id='bus' src="../images/buscaroff.gif" alt="buscar area" onclick="return buscararea(document.frm.area_descripcion.value)" style="cursor:hand" style='visibility:hidden' />
	  <?php
	       }
	  ?>
	</td>
  </tr>
	
	<tr id="tr_intervalo" class="oculto">
	<td class='ColumnTD' align='right'>Intervalo de Fechas&nbsp;</td>
	<td class='DataTD'>
		Desde:&nbsp;<input type="text" name="fecha_desde" id="fecha_desde" style="width:80px" readonly value="<?php echo $fecha_desde;?>"/><button type="reset" id="f_trigger_b_desde">...</button>&nbsp;&nbsp;&nbsp;
		Hasta:&nbsp;<input type="text" name="fecha_hasta" id="fecha_hasta" style="width:80px" readonly value="<?php echo $fecha_hasta;?>"/><button type="reset" id="f_trigger_b_hasta">...</button>
	</td>
  </tr>
  

  <tr id="tr_rut" class="oculto">
	<td class='ColumnTD' align='right'>DNI&nbsp;</td>
	<td class='DataTD'>
		<input type="text" name="rut" id="rut" style="width:80px" maxlength="8" value="<?php echo $rut;?>"/>
	</td>
  </tr>
		
		<TR>
		<TD class='ColumnTD' width='30%' align=right>
		Responsable&nbsp;
		</TD>
		<TD class='DataTD' align=left >
		  <?php
		    $combo = new MyCombo();
			$combo->setMyUrl(db_host());
			$combo->setMyUser(db_user());
			$combo->setMyPwd(db_pass());
			$combo->setMyDBName(db_name());
			
			$sql=  "select vca_empleado_area.empleado_codigo as codigo, Empleado as descripcion ";
		    $sql .=  " from vca_empleado_area ";
			$sql .=  " inner join ca_empleado_rol on vca_empleado_area.empleado_codigo=ca_empleado_rol.empleado_codigo";
			$sql .=  " where Estado_Codigo=1 and area_codigo=" . $area_codigo ." and rol_codigo=1";
			$sql .=  " order by 2";	
			$combo->query = $sql;
			$combo->name = "responsable_codigo"; 
			$combo->value = $responsable_codigo ."";
			$combo->more = "class='Select' style='width:350px' " . $disabled_responsable . " ";
			$rpta = $combo->Construir();
			echo $rpta;

		  ?>
		</TD>
	</TR>
		<TR>
		<TD class='ColumnTD' width='30%' align=right>
		U.Servicio&nbsp;
		</TD>
		<TD class='DataTD' align=left >
		  <?php
		
			$sql="SELECT cod_campana as codigo,exp_nombrecorto  AS descripcion FROM v_campanas "; 
			$sql .=" WHERE coordinacion_codigo=" . $area_codigo;
			$sql .=" order by 2 asc"; 
			$combo->query = $sql;
			$combo->name = "cod_campana"; 
			$combo->value = $cod_campana ."";
			$combo->more = "class='Select' style='width:350px' " . $disabled_campana . " ";
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
<table align="center">
	<TR>
		<TD align=center>
		    <input class=button type="button" value="Generar" id=cmdGenerar name=cmdGenerar style="width=80px" LANGUAGE=javascript onclick="return Generar()" />
			<input class=button type="button" value="Cancelar" id=cmdCancelar name=cmdCancelar style="width=80px" LANGUAGE=javascript onclick="return cmdCancelar_onclick()" />
		    <input class=button type="button" value="Ir a Bandeja" id=cmdIr name=cmdIr style="width=90px" LANGUAGE=javascript onclick="Ir()" />
		
		</TD>
	</TR>
</table>
<iframe src="" id="ifrm" name="ifrm" width="0px" height="0px"></iframe>		
</form>
<script type="text/javascript">
	Consulta(document.frm.reportes.value);
    Calendar.setup({
        inputField     :    "fecha_desde",      // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_b_desde",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
		Calendar.setup({
        inputField     :    "fecha_hasta",      // id of the input field
        ifFormat       :    "%Y-%m-%d",       // format of the input field
        showsTime      :    false,            // will display a time selector
        button         :    "f_trigger_b_hasta",   // trigger for the calendar (button ID)
        singleClick    :    true,           // double-click mode
        step           :    1                // show all years in drop-down boxes (instead of every other year as default)
    });
		if (document.frm.reportes.value==25 || document.frm.reportes.value==26){
			obj = document.getElementById("tr_intervalo");
			obj.className="no_oculto";
		}
</script>
</BODY>
</HTML>
 