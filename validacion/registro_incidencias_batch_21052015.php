<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/clsIncidencias.php"); 
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Asistencias.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php");  
require_once("../includes/clsCA_Empleados.php");
require_once("../includes/clsCA_Usuarios.php");

$supervisor_registra=$_SESSION["empleado_codigo"];

$ip_entrada="";
$ip_salida="";
$disabled="";
$cod_campana="";
$empleado_codigo="0";
$responsable_codigo="0";
$asistencia_codigo="0";
$incidencia_codigo="0";
$incidencia_hh_dd="0";
$asistencia_entrada="";
$asistencia_salida="";
$tolerancia="";
$cod="0";
$horas="";
$minutos="";
$fecha="";
$num="";
$area="";
$sql="";
$incidencia_observacion = "";
$tt_inicio="";
$tt_final="";
$hh_i="";
$hh_f="";
$hh_horario_diario="";
$duo=0;
$traslape=0;


$o = new ca_asistencia_incidencias();
$o->setMyUrl(db_host());
$o->setMyUser(db_user());
$o->setMyPwd(db_pass());
$o->setMyDBName(db_name());
//recuperamos el codigo de empresa
$o->asignarEmpresa();

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

$i=new incidencias();
$i->setMyUrl(db_host());
$i->setMyUser(db_user());
$i->setMyPwd(db_pass());
$i->setMyDBName(db_name());

$combo = new MyCombo();
$combo->setMyUrl(db_host());
$combo->setMyUser(db_user());
$combo->setMyPwd(db_pass());
$combo->setMyDBName(db_name());

$u = new ca_usuarios();//new
$u->MyUrl = db_host();
$u->MyUser= db_user();
$u->MyPwd = db_pass();
$u->MyDBName= db_name();

$ip_entrada=$_SERVER['REMOTE_ADDR'];
$ip_salida=$_SERVER['REMOTE_ADDR'];
if (isset($_GET["area"])) $area=$_GET["area"];
//if (isset($_GET["empleado"])) $empleado_codigo = $_GET["empleado"];
if (isset($_POST["empleado_cod"])) $empleados = $_POST["empleado_cod"];
if (isset($_GET["responsable_codigo"])) $responsable_codigo = $_GET["responsable_codigo"];
if (isset($_POST["responsable_cod"])) $responsable_codigo = $_POST["responsable_cod"];
//if (isset($_GET["asistencia"])) $asistencia_codigo = $_GET["asistencia"];
//if (isset($_POST["asistencia_cod"])) $asistencia_codigo = $_POST["asistencia_cod"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if (isset($_GET["area"])) $area=$_GET["area"];
if (isset($_POST["area_codigo"])) $area = $_POST["area_codigo"];
if (isset($_GET["num"])) $num = $_GET["num"];
if (isset($_POST["n"])) $num = $_POST["n"];
if (isset($_POST["incidencia_codigo"])){ 
 $incidencia_codigo = $_POST["incidencia_codigo"];
}
if (isset($_POST["horas"])) $horas = $_POST["horas"];
if (isset($_POST["numero_ticket"])) $numero_ticket = $_POST["numero_ticket"];
if (isset($_POST["incidencia_observacion"])) $incidencia_observacion = $_POST["incidencia_observacion"];
if (isset($_POST["minutos"])) $minutos = $_POST["minutos"];
if (isset($_POST["incidencia_hh_dd"])) $incidencia_hh_dd = $_POST["incidencia_hh_dd"];
if (isset($_POST["hh_i"])) $hh_i = $_POST["hh_i"];
if (isset($_POST["hh_f"])) $hh_f = $_POST["hh_f"];
if (isset($_POST["hh_horario_diario"])) $hh_horario_diario = $_POST["hh_horario_diario"];

$hora_inicio='';
$hora_fin='';
if (isset($_POST["hora_inicio"])) $hora_inicio = $_POST["hora_inicio"];
if (isset($_POST["hora_fin"])) $hora_fin = $_POST["hora_fin"];

if($cod_campana=='') $cod_campana=0;

//cierre de periodo
$u->area_codigo=$area;
$ndias=$u->Actualizacion_dias();


if (isset($_POST["cod_campana"])){
    $cod = $_POST["cod_campana"];
    $cod_campana=$cod;
}
if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
    
    $arr = explode(",",$empleados);
    $num_arr = sizeof($arr);
    //echo $fecha."-".$ip_entrada."-".$incidencia_codigo."-".$responsable_codigo."-".$empleado_codigo;exit(0);
    //14/10/2011-10.252.196.115-24-3300-0
    
    for ($i=0; $i<$num_arr; $i++){
        //-- registrar
        $empleado_codigo=$arr[$i];
        $as->empleado_codigo=$empleado_codigo;
        $as->asistencia_fecha=$fecha;
        $rpta=$as->Query_fecha();

        $emp->empleado_codigo=$empleado_codigo;
        $rpta=$emp->Query();
        $empleado=$emp->empleado_nombre;
      
        if ($cod_campana==0){ // si campana es default, obtener el asignado.
            $o->empleado_codigo=$empleado_codigo;
            $rpta=$o->Obtener_servicio_empleado();
            $cod_campana=$o->cod_campana;
        }

        $o->empleado_codigo=$empleado_codigo;
        $o->incidencia_codigo =$incidencia_codigo;
        $o->responsable_codigo =$responsable_codigo;
        $o->incidencia_hh_dd=$incidencia_hh_dd;
        
        if($incidencia_codigo!=42 && $incidencia_codigo!=43 && $incidencia_codigo!=11){
            $o->horas =$horas;
            $o->minutos =$minutos;
        }else{
            $o->horas =0;
            $o->minutos=0;
        }
        //nuevas variables
        $o->ticket=$numero_ticket;
        $o->texto_descripcion=$incidencia_observacion;
        $o->aprobado='S';
        $o->empleado_jefe=$responsable_codigo;
        $o->empleado_ip=$_SERVER['REMOTE_ADDR'];
        $o->realizado='S';
        $o->area_codigo=$area;
        
        $o->fecha_inicio_liberalidad = $fecha_inicio_liberalidad;
        $o->fecha_fin_liberalidad = $fecha_fin_liberalidad;
        
        $o->cod_campana=$cod_campana;
        $o->fecha=$fecha;		
        //echo "$num,$ip_entrada,$ip_salida";
        $o->ip_registro=$ip_entrada;
        //** obtener valores
        $o->asistencia_codigo = $as->asistencia_codigo;
        $turno=$as->turno_codigo;
        
        $flag_registrar = 1;
        
        if($incidencia_codigo == 42 || $incidencia_codigo == 79){
            $respuesta = $o->existe_incidencia(7);
            if(!$respuesta){
        		$mensaje = "¡Error! Solo se puede registrar cuando exista incidencia de TARDANZA.";
                $flag_registrar = 0;
            }else{
                $resultado = $o->valida_incidencia_extra();
                if($resultado != "OK"){
                    $mensaje = "¡Error! No se puede registrar con menos de 1 hora de tardanza.";
                    $flag_registrar = 0;
                }
            }
        }
        

        if($incidencia_codigo == 157){
            $respuesta = $o->existe_incidencia(154);
            if(!$respuesta){
        		$mensaje = "¡Error! Solo se puede registrar cuando exista incidencia de DESCUENTO POR SALIDA ANTES DE HORA.'";
                $flag_registrar = 0;
            }
        }
		
		if($incidencia_codigo == 170){
            $respuesta = $o->existe_incidencia(38);
            if(!$respuesta){
        		$mensaje = "¡Error! Solo se puede registrar cuando exista incidencia de FALTAS'";
                $flag_registrar = 0;
            }
        }
        //hourminute
        $emp->empleado_codigo=$empleado_codigo;
        $emp->fecha=$fecha;
        $emp->Query_Turno();
        $as->turno_codigo=$emp->turno_codigo;
        $as->minutos_turno();
        $turno_diario = $as->turno_diario; //total de minutos del turno menos el refrigerio en caso de tenerlo
        $tt_inicio=$as->tt_inicio;
        $tt_final=$as->tt_final;
        $emp->t_codigo=$emp->turno_codigo;
        $duo=$emp->es_duo();
        //echo 'empleado_codigo:'.$empleado_codigo.'fecha:'.$fecha.'turno_codigo:'.$emp->turno_codigo.'<br/>';//=>
        //echo 'turno_inicio:'.$tt_inicio.'turno_final:'.$tt_final.'duo'.$duo.'<br/>';//=>
        //exit(0);
            $k=$as->valida_marca_entrada();
            if($k!=0){//cuando tiene registro de asistencia
                $as->saldo_tiempo(2);
                $tiempo=(($horas*1)*60)+($minutos*1);
                if($tiempo*1<=$as->saldo_tiempo*1){
                        
                    if($flag_registrar == 1){
                        $o->supervisor_registra = $supervisor_registra;
                        //Ajustes a eventos y registro de incidencias
                        if($incidencia_hh_dd*1==0){//0=>diaria
                            $o->horas =$as->diario_horas;
                            $o->minutos=$as->diario_minutos;
                        }
                        
                        if($incidencia_hh_dd*1==0 && $incidencia_codigo*1!=38){//0=>diaria y que sea diferente de 38(Falta)
                            $val_inc_diaria=0;
                            $val_inc_diaria=$o->validar_incidencias_diarias();
                            if($val_inc_diaria!=1){
                                $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);
                            }else{
                                $mensaje = "¡Error! ya existe una incidencia diaria para la asistencia.";
                            }    
                        }else{//1=>horaria
                            //echo 'horas_inicio'.$hora_inicio.'hora_fin'.$hora_fin.'<br/>';//=>
                            //echo 'hh_i'.$hh_i.'tt_inicio'.$tt_inicio.'tt_final'.$tt_final.'hh_f'.$hh_f.'<br/>';//=>
                            //comparar la hora ingresada con su turno programado
                            
                            
                            if($_POST["hdd_Incidencia_Inicio_Fin"] == 0){//si la validacion es por el tiempo
                                $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);                                                                        
                            }else{
                                if(($hh_i>= $tt_inicio && $hh_i<= $tt_final) && ($hh_f>= $tt_inicio && $hh_f<= $tt_final)){
                                    $o->hora_inicio=$hora_inicio;
                                    $o->hora_fin=$hora_fin;
                                    //$o->calcular_Hora_Minuto($hora_inicio,$hora_fin,$fecha,$duo);
                                    if($_POST["hdd_Incidencia_Inicio_Fin"] == 1){ //si nos piden el inicio y el fin calculamos las horas y minutos
                                        $o->calcular_Hora_Minuto($hora_inicio,$hora_fin,$fecha,$duo);    
                                    }else{
                                        $o->horas = $horas;
                                        $o->minutos = $minutos;
                                    }
                                    
                                    if($o->_tiempo!=0){
                                        $traslape=$o->validar_traslape_horas($hora_inicio,$hora_fin);
                                        if($traslape==0){
                                            $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);
                                        }else{
                                            $mensaje = "¡Error! ya existe una incidencia para ese rango de horas.";
                                        }
                                    }
                                }else{
                                    $mensaje = 'No esta dentro del turno programado!!';
                                }
                            }
                            
                            
                            
                            //$mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);//=>descomentar
                        }
                        
                    }
                    if($mensaje=='OK'){
                        echo "<br>Empleado: " . $empleado_codigo . ", Se guardo registro".$mensaje;
                    }else{
                        echo "<br>Empleado: " . $empleado_codigo . ": " . $mensaje;
                    }
                }else{
                    $mensaje = "No se puede registrar, el tiempo de la incidencia supera el saldo del turno!!";
                    echo "<br>Empleado: " . $empleado_codigo . ": " . $mensaje;
                }
            }else{//cuando no tiene registro de asistencia
                if(($incidencia_codigo*1==7) || ($incidencia_codigo*1==38)){
                    if($flag_registrar == 1){
                        //echo 'horas_inicio'.$hora_inicio.'hora_fin'.$hora_fin.'hh_dd'.$incidencia_hh_dd.'<br/>';//=>
                        //echo 'hh_i'.$hh_i.'tt_inicio'.$tt_inicio.'tt_final'.$tt_final.'hh_f'.$hh_f.'<br/>';//=>
                        if($incidencia_hh_dd*1==1){//incidencias horarias
                            
                            if(($hh_i>= $tt_inicio && $hh_i<= $tt_final) && ($hh_f>= $tt_inicio && $hh_f<= $tt_final)){
                                $o->hora_inicio=$hora_inicio;
                                $o->hora_fin=$hora_fin;
                                //$o->calcular_Hora_Minuto($hora_inicio,$hora_fin,$fecha,$duo);
                                if($_POST["hdd_Incidencia_Inicio_Fin"] == 1){ //si nos piden el inicio y el fin calculamos las horas y minutos
                                    $o->calcular_Hora_Minuto($hora_inicio,$hora_fin,$fecha,$duo);    
                                }else{
                                    $o->horas = $horas;
                                    $o->minutos = $minutos;
                                }
                                if($o->_tiempo!=0){
                                    $traslape=$o->validar_traslape_horas($hora_inicio,$hora_fin);
                                    if($traslape==0){
                                        $o->supervisor_registra = $supervisor_registra;
                                        $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);
                                    }else{
                                        $mensaje = "¡Error! ya existe una incidencia para ese rango de horas.";
                                    }
                                }
                            }else{
                                $mensaje = 'No esta dentro del turno programado!!';
                            }
                        }else{
                            $o->supervisor_registra = $supervisor_registra;
                            $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);
                        }
                        //=>descomentar
                        /*
                        $o->supervisor_registra = $supervisor_registra;
                        $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);
                        */
                    }
                    if($mensaje=='OK'){
                        echo "<br>Empleado: " . $empleado_codigo . ", Se guardo registro".$mensaje;
                    }else{
                        echo "<br>Empleado: " . $empleado_codigo . ": " . $mensaje;
                    }
                }
            }
  		
        } //for
    }//if
}//if

?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Registro de Incidencias Masivas</title>
<meta http-equiv="pragma" content="no-cache">
<META NAME="GENERATOR" Content="Microsoft Visual Studio 6.0">
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" language="javascript" src="../js/jquery11.js"></script><!--jQuery v1.11.0-->
<!--Agregado mcortezc@atentoperu.com.pe 20140204-->
<!--<script language="JavaScript" src="../mouse_keyright.js"></script>-->
<script>

if("<?php echo $ndias;?>"=="0"){
    alert('PERIODO DE REGISTRO DE JORNADA PASIVA CERRADO');
    window.close();
}

function cmdCancelar_onclick() {
  window.opener.document.forms['frm'].submit();
  window.opener.document.frm.cmdx.click();
  window.opener.focus();
  window.close();
	
}
function ver_tipo(v) {
alert('aqui');
}

function cmdGrabar_onclick(){
    
    var cod_campana="<?php echo $cod_campana ?>";
    if (validarCampo('frm','inc_cod')!=true) return false;
    
    if(document.frm.cod_campana.disabled==false){
        if (validarCampo('frm','cod_campana')!=true) return false;
    }else{
      if(document.frm.cod_campana.disabled){
            if(document.frm.rdo.value==2 && cod_campana==0){
                alert('Seleccione Unidad de Servicio');  	     
                return false;
            }
        }
    }
  
    var arr=document.frm.inc_cod.value.split('_');	
    var codigo=arr[0];
    var hh_dd=arr[1];
    document.frm.hh_horario_diario.value=hh_dd;
  
    //if(document.frm.incidencia_codigo.value!=66 && document.frm.incidencia_codigo.value!=67 ){
    if(document.frm.incidencia_hh_dd.value==1){
        /*if(document.frm.horas.value==-1){
            alert('Indique  valor');
            document.frm.horas.focus();
            return false;
        }
        if(document.frm.minutos.value==-1){
            alert('Indique  valor');
            document.frm.minutos.focus();
            return false;
        }*/
        /*                    
        if(document.frm.horas.value==-1){
            if(document.frm.hora_inicio.value==''){
                alert('Indique  valor');
                document.frm.hora_inicio.focus();
                return false;
            }
            if(document.frm.hora_fin.value==''){
                alert('Indique  valor');
                document.frm.hora_fin.focus();
                return false;
            }  
            if(validaHoraInicioFin()==false){
                return false;
            }    
        } 
        */
        if(document.frm.hdd_Incidencia_Inicio_Fin.value==1){	
            if(document.frm.horas.value==-1){//hourminute
                if(document.frm.hora_inicio.value==''){
                    alert('Indique  valor de Hora de Inicio');
                    document.frm.hora_inicio.focus();
                    return false;
                }
                if(document.frm.hora_fin.value==''){
                    alert('Indique  valor de Hora de Fin');
                    document.frm.hora_fin.focus();
                    return false;
                }
                //revisar
                
                if(validaHoraInicioFin()==false){//hourminute
                    return false;
                }
           }
        }else{
            horas = document.frm.horas.value;
            minutos = document.frm.minutos.value;
            if(horas==-1  ){
                alert('Indique valor Horas');
                document.frm.horas.focus();
                return false;
            }
            if(minutos== -1 ){
                alert('Indique valor de Minutos');
                document.frm.minutos.focus();
                return false;
            }
        }                   
    }		
  //}	
  	
    if(document.frm.hdd_flag_numero_ticket.value == 1){ //se debe validar
        if(document.frm.numero_ticket.value=="" || document.frm.numero_ticket.value == 0){
            alert("El # de Ticket es necesario");
            document.frm.numero_ticket.focus();
            return false;
        }
    }
    
  if (confirm('Confirme datos de registro?')==false) return false;
  var cod_empleados="";
  for(i=0; i< document.frm.length; i++ ) { 
    if (frm.item(i).type=='checkbox'){
      if (frm.item(i).checked)      {
        if (frm.item(i).value>0){
          if (cod_empleados=='')
            cod_empleados=frm.item(i).value;
          else
            cod_empleados=cod_empleados + ',' + frm.item(i).value;
        }
      }
    }
  } 
  if (cod_empleados==''){
    alert('Seleccione empleados');
    return false
  }
  desactivar_hora(false);
  document.frm.empleado_cod.value=cod_empleados;
  document.frm.hddaccion.value="INS";
	
  document.frm.submit()
}

function habilitar(tipo){
//alert(tipo);
switch(tipo){
case '1': document.frm.cod_campana.disabled=true;
          document.frm.cod_campana.value=0;
        break;
case '2': document.frm.cod_campana.disabled=false;
          break;

 }
}

/*obtiene los flag deacuerdo a la incidencia seleccionada*/
function validar_incidencia(c){
    if(c!=0){
        var arr=c.split('_');//setear_tiempo con -1 requiere dato
        var codigo=arr[0];
        var hh_dd=arr[1];
        
        document.frm.incidencia_codigo.value=codigo;
        document.frm.incidencia_hh_dd.value=hh_dd;
        
        if(codigo == 2){
            document.frm.hdd_action.value = 'TIEMPOS'; //obtenemos los tiempos
            document.frm.submit();
        }
        
        $.post("../controllers/registro.controller.php",{operacion:"OBTIENE_FLAGS", incidencia:codigo},
            function(respuesta){
                var arr=respuesta.split('-');
                
                document.frm.hdd_Incidencia_Inicio_Fin.value= arr[0];
                document.frm.hdd_flag_numero_ticket.value   = arr[1];
                
                if(hh_dd==0){ //diario
                    setear_ticket("");
                    setear_hora("");
                    desactivar_hora(true);
                    desactivar_tiempo(true);
                    if(codigo==11) {
                        setear_tiempo(0);
                    }else{	
                        setear_tiempo(-1);
                    }
                }else{
                    if(hh_dd==1){ //si la incidencia es horaria
                        if(arr[0] == 1){ //si las horas de inicio y fin son requeridas
                            desactivar_hora(false);
                            desactivar_tiempo(true);
                            setear_tiempo(-1);
                        }else{
                            desactivar_hora(true);
                            desactivar_tiempo(false);
                            setear_hora("");
                            setear_tiempo(-1);
                        }
                        
                    }
                }
                
                if(arr[1] == 1){
                    document.frm.numero_ticket.disabled = false;
                    setear_ticket("");
                }else{
                    document.frm.numero_ticket.disabled = true;
                    setear_ticket("");
                }
            }
        );
    }    
}
  
function validar_incidencia_1(c){
if(c!=0){	
	var arr=c.split('_');	
	var codigo=arr[0];
	var hh_dd=arr[1];
	document.frm.incidencia_codigo.value=codigo;
	document.frm.incidencia_hh_dd.value=hh_dd;
	
	if(hh_dd==0){ //diario
	    if(codigo==11) {
	 	    setear_tiempo(0);
		    desactivar_hora(true);
	      }else{	
	        setear_tiempo(-1);
	        desactivar_hora(true);	
		 }
	}else{ 
	  	if(hh_dd==1){ //horario
	  	   if(codigo==42 || codigo==43 || codigo == 157) {
	 	    setear_tiempo(0);
		    desactivar_hora(true);
	       }else{	
	       	if(codigo==66 || codigo==67){
	          setear_tiempo(-1);
	          desactivar_hora(true);	
	       	}else{
	       	 setear_tiempo(-1);
	         desactivar_hora(false);	
			}
		   }	
	  	}	
	}
	
	 
 }
} 
/*
function setear_tiempo(valor){
   document.frm.horas.value=valor;
   document.frm.minutos.value=valor;
} 
function activar_tiempo(valor){
   document.frm.hora_inicio.disabled=valor;
    document.frm.hora_fin.disabled=valor;
} 
*/
function setear_tiempo(valor){//hourminute
   document.frm.horas.value=valor;
   document.frm.minutos.value=valor;
} 

function setear_hora(valor){//hourminute
   document.frm.hora_inicio.value=valor;
   document.frm.hora_fin.value=valor;
}

function setear_ticket(valor){//hourminute
   document.frm.numero_ticket.value=valor;
   document.frm.numero_ticket.value=valor;
}

function desactivar_tiempo(valor){
    document.frm.horas.disabled=valor;
    document.frm.minutos.disabled=valor;
}

function desactivar_hora(valor){//hourminute
    document.frm.hora_inicio.disabled=valor;
    document.frm.hora_fin.disabled=valor;
    if(valor){
        $("#hora_inicio").css("background","#eee");
        $("#hora_fin").css("background","#eee");
    }else{
        $("#hora_inicio").css("background","#fff");
        $("#hora_fin").css("background","#fff");
    }
}

function validar_ticket(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    //patron =/[A-Za-z\s]/; // 4
    patron = /^[a-zA-Z0-9]*$/
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}



function formatea_hora_minuto(o,e){
    var ok=false;
    var a=(document.all) ? e.keyCode : e.which;
    var texto= o.value;
    if (texto.length > 4){
        o.value = texto.substr(0,4);
        ok=true;
        return ok;
    }
    if (texto.length == 2){
        if(parseInt(texto) > 23 ){
            o.value='';
            return false;
        }else{
            o.value += ":";
        }    
    }
    if (texto.length == 4){
        var digito=texto.split(":")[1];
        if(parseInt(digito)>5){
            o.value = texto.split(":")[0]+":";
            return false;
        }
    }   
    if (a>=48 && a<=57){
        ok=true;
    }
    return ok;
}

function validaHoraInicioFin(){
    if(document.frm.hora_inicio.value!='' && document.frm.hora_fin.value!=''){
    if(verifica_formato_hora()==true){
        var bandera=true;
        var c_hora_i = document.frm.hora_inicio.value.split(":")[0];
        var c_minuto_i=document.frm.hora_inicio.value.split(":")[1];
        var hora_i=0;var minuto_i=0;var hh_i=0;
        if(c_hora_i.substr(0,1)=='0') hora_i=c_hora_i.substr(1,1);    
        else hora_i=c_hora_i;
        if(c_minuto_i.substr(0,1)=='0') minuto_i=c_minuto_i.substr(1,1);    
        else minuto_i=c_minuto_i;
        hora_i=parseInt(hora_i)*60;
        minuto_i=parseInt(minuto_i);
        hh_i=hora_i+minuto_i;
    
        var c_hora_f = document.frm.hora_fin.value.split(":")[0];
        var c_minuto_f=document.frm.hora_fin.value.split(":")[1];
        var hora_f=0;var minuto_f=0;var hh_f=0;
        if(c_hora_f.substr(0,1)=='0') hora_f=c_hora_f.substr(1,1);    
        else hora_f=c_hora_f;
        if(c_minuto_f.substr(0,1)=='0') minuto_f=c_minuto_f.substr(1,1);    
        else minuto_f=c_minuto_f;
        hora_f=parseInt(hora_f)*60;
        minuto_f=parseInt(minuto_f);
        hh_f=hora_f+minuto_f;

        if(hh_i>=hh_f){
            alert('La hora de inicio debe ser menor a la hora de fin');
            document.frm.hora_inicio.value='';
            document.frm.hora_inicio.focus();
            bandera=false;
            return bandera;
        }
        
        document.frm.hh_i.value=hh_i;
        document.frm.hh_f.value=hh_f;
        
    }else{
        bandera=false;
        alert('Formato incorrecto en Horas');
    }
    
    return bandera;
    }
}

function verifica_formato_hora(){
    var bandera_formato=true;
    var hh_inicio=document.frm.hora_inicio.value;
    var hh_fin=document.frm.hora_fin.value;
    if((hh_inicio.indexOf(":") == -1) || (hh_fin.indexOf(":") == -1)){
        bandera_formato=false;
        return bandera_formato;
    }
    if((hh_inicio.split(":")[0]=='') || (hh_inicio.split(":")[1]=='')){
        bandera_formato=false;
        return bandera_formato;
    }
    if((hh_fin.split(":")[0]=='') || (hh_fin.split(":")[1]=='')){  
        bandera_formato=false;
        return bandera_formato;
    }  
    return bandera_formato;
    
}

function checkear(){
 var i; 
 var valor='';
 
 for(i=0; i< document.frm.length; i++ ) { 
	 if (frm.item(i).type=='checkbox'){
	 	if (frm.chkall.checked==true) frm.item(i).checked=true;
		if (frm.chkall.checked==false) frm.item(i).checked=false;
	}
 } 
 return;
}
 
function validar_ticket(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    //patron =/[A-Za-z\s]/; // 4
    patron = /^[a-zA-Z0-9]*$/
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}
   
  
</script>
</head>
<BODY class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<center class='FormHeaderFont' >Registro de Incidencias Masivas</center>
<TABLE WIDTH='500px' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
		<TD class='ColumnTD' align=right>
		 Incidencias&nbsp;
		</TD>
		<TD class='DataTD' align=left>
			<!--<select class='select' id="inc_cod" name="inc_cod"  style='width:450px' onchange='validar_incidencia(this.value)' <?php //echo $disabled ?>>
			<option value='0'>SELECCIONAR</option>-->
			<?php
                            /*$sql="SELECT incidencia_codigo as codigo,incidencia_descripcion  AS descripcion,incidencia_hh_dd FROM ca_incidencias "; 
                            $sql .=" WHERE (area_codigo=0 or area_codigo=" . $area . ") and incidencia_manual=1  and incidencia_activo=1 ";
                            $sql .=" order by 2 asc";*/
                            
                            $sql=" SELECT incidencia_codigo as codigo,incidencia_descripcion AS descripcion,incidencia_hh_dd ";
                            $sql.=" FROM ca_incidencias ";
                            $sql.=" WHERE  incidencia_manual=1 and incidencia_activo=1 ";
                            $sql.=" AND INCIDENCIA_CODIGO in (";
                            $sql.=" SELECT INCIDENCIA_CODIGO FROM CA_INCIDENCIA_AREA WHERE (AREA_CODIGO = 0 or AREA_CODIGO = ".$area." ) ";
                            $sql.=" ) ";
                            $sql.=" order by 2 asc ";
                            
                            $combo->query = $sql;
                            $combo->name = "inc_cod"; 
                            //$combo->value = $cod_campana."";
                            $combo->more = " class='select' onchange='validar_incidencia(this.value)' style='width:450px' ".$disabled." ";
                            $rpta = $combo->construir_combo2values();
                            echo $rpta;
                            /*$result = consultar_sql($sql);
                            if (mssql_num_rows($result)>0){
                                $rs= mssql_fetch_row($result);
                                while ($rs){
                                    echo "<option value ='" . $rs[0] . "_" . $rs[2] . "' >" . $rs[1] . "</option>/n";
                                    $rs= mssql_fetch_row($result);
                                }
                            }*/
                        ?>
		  <!--</select>-->
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
			  <input type='radio' id='rdo' name='rdo' value='1' onClick="habilitar(this.value)" checked>	
        Unidad de Servicio Asignado		  
			  </td>
			</tr>
			<tr>
			  <td align="right"><b>Otros</b></td>
			  <td>
			  <input type='radio' id='rdo' name='rdo' value='2' onClick="habilitar(this.value)">
			  
    		<?php  
                    $sql ="select v_campanas.cod_campana as codigo, ";
                    $sql .=" v_campanas.exp_codigo + '-' + v_campanas.exp_NombreCorto +' (' + convert(varchar,v_campanas.cod_campana) + ')' as descripcion"; 
                    $sql .=" from v_campanas ";
                    $sql .=" where v_campanas.exp_activo=1 and coordinacion_codigo=" . $area;                   
                    $sql .=" order by 2 asc"; 
                    $combo->query = $sql;
                    $combo->name = "cod_campana"; 
                    $combo->value = $cod."";
                    $combo->more = " class='select' disabled";
                    $rpta = $combo->Construir();
                    echo $rpta;
			  //echo $area;
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
		  &nbsp;Horas&nbsp;<select   name='horas' id='horas' disabled >
		  <option value="-1">hh</option>
		  <?php
                    for($h=0; $h < 24; $h++){
                        $hh=$h;
                        if(strlen($h)<=1) $hh="0".$h;
                        if($h==0) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";
                        else
                        echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
                    }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <select  name='minutos' id='minutos' disabled>
		  <option value="-1">mm</option>
		  <?php
                    for($m=0; $m < 60; $m++){
                        $mm=$m;
                        if(strlen($m)<=1) $mm="0".$m;
                        if($m==0) echo "\t<option value=". $m . " selected>". $mm ."</option>" . "\n";
                        else
                        echo "\t<option value=". $m . ">". $mm ."</option>" . "\n";
		   }
		  ?>
		 </select> 	
		</TD>
	</TR>
        
        <tr>
            <td class="ColumnTD" align="right">Horas:&nbsp;</td>
            <td class='DataTD' align=left>
                &nbsp;Hora de Inicio&nbsp;
                <input type="text" id="hora_inicio" name="hora_inicio" size="3" maxlength="5" onKeyPress="return formatea_hora_minuto(this,event);" value="<?php echo $hora_inicio;?>" /><span style="font-weight:bold;color:blue;font-size: 11px;">(hh:mm)</span>
                &nbsp;&nbsp;Hora de Fin&nbsp;
                <input type="text" id="hora_fin" name="hora_fin" size="3" maxlength="5" onKeyPress="return formatea_hora_minuto(this,event);" onblur="validaHoraInicioFin();" value="<?php echo $hora_fin;?>"/><span style="font-weight:bold;color:blue;font-size: 11px;">(hh:mm)</span>
            </td>
        </tr>
        
        <TR>
		<TD class='ColumnTD' align=right>
                    Observaci&oacute;n&nbsp;
		</TD>
		<TD class='DataTD' align=left>
			<input type='text' id="incidencia_observacion" name="incidencia_observacion" style="width:500px" value='<?php echo $incidencia_observacion; ?>'/>
		</TD>
	</TR>
        <TR>
		<TD class='ColumnTD' align=right>
			# de Ticket&nbsp;
		</TD>
		<TD class='DataTD' align=left>
                    <input type='text' id="numero_ticket" name="numero_ticket" onkeypress="return validar_ticket(event)" maxlength="15" size="20" value=""/>
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
<br>
<table class='FormTable' align="center" border="0" cellPadding="0" cellSpacing="1" style="width:500px">
<tr align="center" >
    <td class="ColumnTD" bgcolor="#33CC33" width='4%'>
    <INPUT type=CHECKBOX align=center id='chkall' name='chkall' value='0' onclick='checkear()'><b>TODOS</b>
    </td>
    <td class="ColumnTD" width='4%'>Código
    </td>
    <td class="ColumnTD" width='20%'>Empleado
    </td>
    <td class="ColumnTD" width='8%'>Tiempo Disponible
    </td>
</tr>
<?php
    $o->fecha=$fecha;
    $o->area_codigo=$area;
    $o->responsable_codigo=$responsable_codigo;
    $cadena=$o->Listar_personal_batch();
    echo $cadena;
?>
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
<input type='hidden' id="incidencia_hh_dd" name="incidencia_hh_dd" value=''/>
<input type='hidden' id="incidencia_codigo" name="incidencia_codigo" value='<?php echo $incidencia_codigo ?>'/>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_codigo ?>"/>
<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>"/>
<input type='hidden' id='asistencia_cod' name='asistencia_cod' value="<?php echo $asistencia_codigo ?>"/>
<input type='hidden' id='asistencia_entrada' name='asistencia_entrada' value="<?php echo $asistencia_entrada ?>"/>
<input type='hidden' id='n' name='n' value="<?php echo $num ?>"/>
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>"/>
<input type='hidden' id='asistencia_salida' name='asistencia_salida' value="<?php echo $asistencia_salida ?>"/>
<input type='hidden' id='area_codigo' name='area_codigo' value="<?php echo $area ?>"/>
<input type='hidden' id='hh_i' name='hh_i' value=""/>
<input type='hidden' id='hh_f' name='hh_f' value=""/>
<input type='hidden' id='hh_horario_diario' name='hh_horario_diario' value=""/>
<input type='hidden' id='hdd_Incidencia_Inicio_Fin' name='hdd_Incidencia_Inicio_Fin' value=""/>
<input type='hidden' id='hdd_flag_numero_ticket' name='hdd_flag_numero_ticket' value=""/>

</form>
</BODY>
</HTML>