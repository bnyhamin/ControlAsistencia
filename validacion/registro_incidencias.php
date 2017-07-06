<?php
header("Expires: 0");
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
//echo $supervisor_registra;

$ip_entrada="";
$ip_salida="";
$disabled="";
$cod_campana="";
$transaccion="";
$punto="0";
$mensaje="";
$empleado_codigo="0";
$responsable_codigo="0";
$asistencia_codigo="0";
$incidencia_codigo="0";
$incidencia_hh_dd="0";
$asistencia_entrada="";
$asistencia_salida="";
$minutos_saldo=0;
$minutos_adicionales=10;
$tolerancia="";
$cod="0";
$horas="";
$minutos="";
$fecha="";
$num="";
$area="";
$sql="";
$indica=0;
$incidencia_observacion="";
$numero_ticket="";
$t_disponible="";
$duo=0;
$tt_inicio="";
$tt_final="";
$extension=0;
$tiempo=0;
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
if (isset($_POST["area_cod"])) $area = $_POST["area_cod"];
if (isset($_GET["empleado"])) $empleado_codigo = $_GET["empleado"];
if (isset($_POST["empleado_cod"])) $empleado_codigo = $_POST["empleado_cod"];
if (isset($_GET["responsable"])) $responsable_codigo = $_GET["responsable"];
if (isset($_POST["responsable_cod"])) $responsable_codigo = $_POST["responsable_cod"];
if (isset($_GET["asistencia"])) $asistencia_codigo = $_GET["asistencia"];
if (isset($_POST["asistencia_cod"])) $asistencia_codigo = $_POST["asistencia_cod"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
if (isset($_GET["area"])) $area=$_GET["area"];
if (isset($_POST["area_codigo"])) $area = $_POST["area_codigo"];
if (isset($_GET["num"])) $num = $_GET["num"];
if (isset($_POST["n"])) $num = $_POST["n"];

$incidencia_sel = isset($_POST["inc_cod"])?$_POST["inc_cod"]:"0";

//cierre de periodo
$u->area_codigo=$area;
$ndias=$u->Actualizacion_dias();

$o->area_codigo = $area;
$encontrado=$o->Verifica_Plataforma_Avaya();

//point
$as->empleado_codigo=$empleado_codigo;
$as->asistencia_fecha=$fecha;
$k=$as->valida_marca_entrada();
//if($k==0){
?>
    <!--<script type="text/javascript">
        alert("El empleado debe tener registro de asistencia para poder registrar incidencias");
        window.close();
    </script>-->
<?php
//}


$piloto="NO";
if($num==0){
	$as->empleado_codigo=$empleado_codigo;
	if ($emp->Query_Area_Piloto($area)!=false){
		$piloto="SI";
		$rpta=$as->verificar_turno_programado($fecha);
	}else{
		$rpta=$as->verificar_turno();
	}
	if($rpta=="OK"){
		if($as->turno==1){?>
                        <script type="text/javascript">
			alert("El empleado debe tener un turno asignado para  su registro de asistencia e incidencia.\nAsignelo en la opcion 'Programacion de turnos' ");
			window.close();
			</script>
		<?php
		}
	}
}
			
if (isset($_POST["incidencia_codigo"])){ 
 $incidencia_codigo = $_POST["incidencia_codigo"];
}
if (isset($_POST["horas"])) $horas = $_POST["horas"];
if (isset($_POST["minutos"])) $minutos = $_POST["minutos"];
if (isset($_POST["incidencia_hh_dd"])) $incidencia_hh_dd = $_POST["incidencia_hh_dd"];
if (isset($_POST["numero_ticket"])) $numero_ticket = $_POST["numero_ticket"];
if (isset($_POST["incidencia_observacion"])) $incidencia_observacion = $_POST["incidencia_observacion"];

//hourminute
$hora_inicio='';
$hora_fin='';
if (isset($_POST["hora_inicio"])) $hora_inicio = $_POST["hora_inicio"];
if (isset($_POST["hora_fin"])) $hora_fin = $_POST["hora_fin"];

if ($asistencia_codigo*1==0){
	//--obtener el codigo de asistencia para la fecha ingresada
	$as->empleado_codigo=$empleado_codigo;
	$as->asistencia_fecha=$fecha;
        
	$rpta=$as->Query_fecha();
        
	if($rpta=="OK"){
		$asistencia_codigo=$as->asistencia_codigo;
		if ($asistencia_codigo!=0) $num=1;
	}
}
//echo 'F: ' . $fecha .  ' ID: ' . $asistencia_codigo . ' Num: '. $num;


$emp->empleado_codigo=$empleado_codigo;
$rpta=$emp->Query();
$empleado=$emp->empleado_nombre;
 
$o->empleado_codigo=$empleado_codigo;
$rpta=$o->Obtener_servicio_empleado();
$cod_campana=$o->cod_campana;

if($cod_campana=='') $cod_campana=0;

if (isset($_POST["cod_campana"])) {
	$cod = $_POST["cod_campana"];
	$cod_campana=$cod;
}

$emp->empleado_codigo=$empleado_codigo;
$emp->fecha=$fecha;
$emp->Query_Turno();

if($asistencia_codigo*1!=0){//CON ASISTENCIA
    $as->empleado_codigo=$empleado_codigo;
    $as->asistencia_codigo=$asistencia_codigo;
    $as->saldo_tiempo(1);// mcortezc@atentoperu.com.pe
    $minutos_saldo=$as->saldo_tiempo;
    $t_disponible=$as->tiempo_disponible($as->saldo_tiempo);
}else{
    /*
    $emp->empleado_codigo=$empleado_codigo;
    $emp->fecha=$fecha;
    $emp->Query_Turno();
    */
    $as->turno_codigo=$emp->turno_codigo;
    $as->minutos_turno();// mcortezc@atentoperu.com.pe
    $minutos_saldo=$as->minutos_turno;
    $t_disponible=$as->tiempo_disponible($as->minutos_turno);
}

//obtener si ejecutivo es duo o no
$emp->get_extension_turno($empleado_codigo,$asistencia_codigo,$fecha,$num);
$duo=$emp->es_duo();
$extension=$emp->e_turno;
if($extension*1==1) $extension=$emp->extension_tiempo;//=>obtener el tiempo de extension de la asistencia
$as->turno_codigo=$emp->t_codigo;
$as->obtnerTurnoInicioFin();
$tt_inicio=$as->tt_inicio;
$tt_final=$as->tt_final;

if($minutos_saldo*1==$minutos_adicionales) $t_disponible=$as->tiempo_disponible(0);
else $minutos_adicionales=0;


if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
        //-- registrar
        $o->empleado_codigo = $empleado_codigo;
        $o->asistencia_codigo = $asistencia_codigo;
        $o->incidencia_codigo = $incidencia_codigo;
        
        //mcortezc@atentoperu.com.pe
        //Ajustes a eventos y registro de incidencias
        if($incidencia_hh_dd*1==0 && $incidencia_codigo*1!=38){//considerar que sea diferente de 38
            $val_inc_diaria=0;
            $val_inc_diaria=$o->validar_incidencias_diarias();
            if($val_inc_diaria==1){
?>
                <script type="text/javascript">
                    alert("¡Error! ya existe una incidencia diaria para la asistencia.");
                    window.close();
                </script>            
<?php
                exit;
            }
        }
        
        if($k==0){
            if(($incidencia_codigo*1!=7) && ($incidencia_codigo*1!=38)){
?>
            <script type="text/javascript">
                alert("¡Error! Solo se puede registrar incidencias de Faltas y Tardanzas.");
                window.close();
            </script>
<?php
            exit;
            }
        }
        
        if(($incidencia_codigo*1==4) || ($incidencia_codigo*1==42) || ($incidencia_codigo*1==157) || ($incidencia_codigo*1==178) || ($incidencia_codigo*1==179)
            ||($incidencia_codigo*1==166) 
            || ($incidencia_codigo*1==176)
            || ($incidencia_codigo*1==181)) //mcortezc21052015
            $indica=1;    
        else $indica=0;
        
        if($indica==0){
            if($asistencia_codigo*1!=0){
                $as->empleado_codigo=$empleado_codigo;
                $as->asistencia_codigo=$asistencia_codigo;
                $as->saldo_tiempo(1);
                $tiempo=(($horas*1)*60)+($minutos*1);
                if(!($tiempo*1<=($as->saldo_tiempo*1-$minutos_adicionales))){//mcortezc 15102014
?>
                    <script type="text/javascript">
                        alert('¡Error!. No se permite registrar incidencia, la suma de horas no deben superar el turno');
                        window.close();
                    </script>
<?php
                exit;
                }
            }
        }
        
        //para generar una incidencia de tipo HORAS EXTRAS REMUNERADAS = 2
        // debe existir la incidencia HORAS ADICIONALES PARA COMPENSACION(3)
        if($o->incidencia_codigo == 2){
            $respuesta = $o->existe_incidencia(3);
            if(!$respuesta){
?>
                <script type="text/javascript">
                    alert('¡Error! Solo se puede registrar cuando exista incidencia de HORAS ADICIONALES PARA COMPENSACION.');
                    window.close();
                </script>
<?php
                return;
            }
        }
        
        if($o->incidencia_codigo == 157 || $o->incidencia_codigo == 178 || $o->incidencia_codigo == 179 ){
            $respuesta = $o->existe_incidencia(154);
            if(!$respuesta){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! Solo se puede registrar cuando exista incidencia de DESCUENTO POR SALIDA ANTES DE HORA.');
        		window.close();
        		</script>
                <?php
                return;
            }
        }
        
        
        //mcortezc21052015
        //echo $o->incidencia_codigo;
        if( $o->incidencia_codigo == 181){
            $respuesta = $o->existe_incidencia(154);
            if(!$respuesta){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! Solo se puede registrar cuando exista incidencia de SALIDA ANTICIPADA ANTES DE FIN DE TURNO CON DESCUENTO Y CARTA.');
        		window.close();
        		</script>
                <?php
                return;
            }
        }
        //echo $o->incidencia_codigo;exit;
        
        if( $o->incidencia_codigo == 79){
            $respuesta = $o->existe_incidencia(7);
            if(!$respuesta){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! Solo se puede registrar cuando exista incidencia de TARDANZA.');
        		window.close();
        		</script>
                <?php
                return;
            }
        }
        
        
        //para registrar una justificacion de tardanza - inicio de turno
        if( $o->incidencia_codigo == 42 || $o->incidencia_codigo == 176){
            $respuesta = $o->existe_incidencia(7);
            if(!$respuesta){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! Solo se puede registrar cuando exista incidencia de TARDANZA.');
        		window.close();
        		</script>
                <?php
                return;
            }
            
            $mensaje = $o->valida_incidencia_extra();
            if($mensaje != "OK"){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! No se puede registrar con menos de 1 hora de tardanza.');
        		window.close();
        		</script>
                <?php
                return;
            }
        }
        
		//para registrar una capacitacion de inicio de turno
        if( $o->incidencia_codigo == 166){
            $respuesta = $o->existe_incidencia(7);
            if(!$respuesta){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! Solo se puede registrar cuando exista incidencia de TARDANZA.');
        		window.close();
        		</script>
                <?php
                return;
            }
            
            $mensaje = $o->valida_incidencia_extra();
            if($mensaje != "OK"){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! No se puede registrar con menos de 1 hora de tardanza.');
        		window.close();
        		</script>
                <?php
                return;
            }
        }
        
        //11 CAPACITACION FUERA DE EMPRESA (DIA COMPLETO)
        //12	DESCANSO POR FERIADO EXTRANJERO
        //151	DIA DE DESCANSO POR HORAS ADICIONALES
        //170	GESTION FUERA DE LA EMPRESA - DIA COMPLETO
        //172	EVENTO DE LA EMPRESA - DIA COMPLETO
		if( $o->incidencia_codigo == 11 || 
            $o->incidencia_codigo == 12 || 
            $o->incidencia_codigo == 151 || 
            $o->incidencia_codigo == 170 || 
            $o->incidencia_codigo == 172 ||
            $o->incidencia_codigo == 183){
            $respuesta = $o->existe_incidencia(38);
            if(!$respuesta){
                ?>
                        <script type="text/javascript">
        		alert('¡Error! Solo se puede registrar cuando exista incidencia de FALTAS.');
        		window.close();
        		</script>
                <?php
                return;
            }
        }
		if ($piloto=="SI"){
			$as->empleado_codigo=$empleado_codigo;
			$rpta=$as->update_turno_empleado_fecha($fecha);
		}
		
		
		$o->responsable_codigo =$responsable_codigo; 
		 
		$o->incidencia_hh_dd=$incidencia_hh_dd;
		
		if($incidencia_codigo!=42 && $incidencia_codigo!=43 && $incidencia_codigo!=11 && $incidencia_codigo!=166 && $incidencia_codigo!=176){
			$o->horas =$horas;
			$o->minutos =$minutos;
		}else{
		   $o->horas =0;
		   $o->minutos=0;
		}
                
                //mcortezc@atentoperu.com.pe
                //Ajustes a eventos y registro de incidencias
                if($incidencia_hh_dd*1==0){
                    $o->horas =$as->diario_horas;
                    $o->minutos=$as->diario_minutos;    
                    //$o->area_codigo
                }else if($incidencia_hh_dd=='1'){//hourminute=>horario
                    //echo 'horario';
                    
                    $o->hora_inicio=$hora_inicio;
                    $o->hora_fin=$hora_fin;
                    if($_POST["hdd_Incidencia_Inicio_Fin"] == 1){ //si nos piden el inicio y el fin calculamos las horas y minutos
                        $o->calcular_Hora_Minuto($hora_inicio,$hora_fin,$fecha,$duo);    
                    }else{
                        $o->horas = $horas;
                        $o->minutos = $minutos;
                    }
                    
                    //valida traslape
                    if($o->_tiempo!=0){
                        $traslape=$o->validar_traslape_horas($hora_inicio,$hora_fin);
                        if($traslape==1){
?>
                        <script type="text/javascript">
                            alert("¡Error! ya existe una incidencia para ese rango de horas.");
                            window.close();
                        </script>
<?php
                        exit;
                        }   
                    }//end valida traslape
                    
                    
                    
                }
                
		$o->cod_campana=$cod_campana;
		$o->fecha=$fecha;
		$o->ip_registro=$ip_entrada;
                
        $o->incidencia_observacion=$incidencia_observacion;
        if(trim($numero_ticket)=="") $o->ticket=NULL; else $o->ticket=$numero_ticket;
        //$o->ticket=$numero_ticket;
        $o->texto_descripcion=$incidencia_observacion;
        $o->aprobado='S';
        $o->empleado_jefe=$responsable_codigo;
        $o->empleado_ip=$_SERVER['REMOTE_ADDR'];
        $o->realizado='S';
        $o->supervisor_registra = $supervisor_registra;
        
        $o->fecha_inicio_liberalidad = $fecha_inicio_liberalidad;
        $o->fecha_fin_liberalidad = $fecha_fin_liberalidad;
        $mensaje = $o->registrar_incidencia($num,$ip_entrada,$ip_salida);
        //echo $mensaje;exit(0);
        //echo $mensaje;
        //echo $incidencia_codigo.$o->incidencia_codigo.$mensaje;exit;
         
        $punto="1";
                
		if($mensaje=='OK'){
			if ($piloto=="SI"){
				$as->empleado_codigo=$empleado_codigo;
				$as->update_turno_empleado_hoy();
			}
                        $transaccion="OK";
                        
		?>
                <!--<script type="text/javascript">
		 window.alert('Guardado exitosamente!!!');
		 window.opener.document.forms['frm'].submit();
		 window.opener.document.frm.cmdx.click();
		 window.close();
		</script>-->
		<?php
		} else if ($mensaje=='NO_TARDANZA'){
		?>
                <!--<script type="text/javascript">
		alert('¡Error! No existe tardanza a justificar.');
		//window.opener.document.forms['frm'].submit();
		//window.opener.document.frm.cmdx.click();
		window.close();
		</script>-->
		<?php
		} else if ($mensaje=='NO_JUSTIFICACION'){
		?>
                <!--<script type="text/javascript">
		alert('¡Error! No existe incidencia a justificar.');
		//window.opener.document.forms['frm'].submit();
		//window.opener.document.frm.cmdx.click();
		window.close();
		</script>	-->
		<?php
		}else if ($mensaje=='NO_FALTA'){
		?>
		<!--<script type="text/javascript">
		alert('¡Error! Debe Existir una Falta.');
		//window.opener.document.forms['frm'].submit();
		//window.opener.document.frm.cmdx.click();
		window.close();
		</script>	-->
		<?php
		}
        else{
			//if ($emp->Query_Area_Piloto($area)==false){
			if ($piloto=="SI"){
				$as->empleado_codigo=$empleado_codigo;
				$as->update_turno_empleado_hoy();
			}
			//echo $mensaje;
		}
	}
}
?>
<HTML>
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Control Presencial - Registro  de Incidencias</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script type="text/javascript" src="../../default.js"></script>

<script type="text/javascript" src="../tecla_f5.js"></script>
<script type="text/javascript" src="../mouse_keyright.js"></script>

<script type="text/javascript" src="../jscript.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery11.js"></script><!--jQuery v1.11.0-->
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<!--<script language="JavaScript" src="../mouse_keyright.js"></script>-->

<script type="text/javascript">


if("<?php echo $ndias;?>"=="0"){
    alert('PERIODO DE REGISTRO DE JORNADA PASIVA CERRADO');
    window.close();
}

if("<?php echo $punto;?>"=="1"){
    if("<?php echo $mensaje;?>"=="OK"){
        window.alert('Guardado exitosamente!!!');
        window.opener.document.forms['frm'].submit();
        window.opener.document.frm.cmdx.click();
        window.close();
    }else if("<?php echo $mensaje;?>"=="NO_TARDANZA"){
        alert('¡Error! No existe tardanza a justificar.');	
        window.close();
    }else if("<?php echo $mensaje;?>"=="NO_JUSTIFICACION"){
        alert('¡Error! No existe incidencia a justificar.');	
	window.close();
    }else if("<?php echo $mensaje;?>"=="NO_FALTA"){
        alert('¡Error! Debe Existir una Falta.');
        window.close();
    }else if("<?php echo $mensaje;?>"=="NO_SALDO"){
        alert('Error: Por acuerdo de Comité País: ¡debe haber Horas Adicionales previas para ser compensadas con tiempo!\n Registro no permitido!');
        window.close();
    }
    
}

function cmdCancelar_onclick() {
	self.returnValue = 0
	self.close();
}
function ver_tipo(v) {
alert('aqui');
}

var operaciones = {
        salir : 'ningun valor'
};


    
function cmdGrabar_onclick(){
    
    var cod_campana="<?php echo $cod_campana ?>";
    
    if (validarCampo('frm','inc_cod')!=true) return false;
    
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
    
    if(document.frm.incidencia_codigo.value==43 || document.frm.incidencia_codigo.value==67){
     var a="<?php echo $num ?>";
     if(a==0){
       alert('No puede registrarse sin asistencia!!');
       return false;
     }
    }	
    
    hh_dd = document.frm.incidencia_hh_dd.value;
    //si la incidencia es horaria
    if(hh_dd == 1){
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
    
    if(document.frm.hdd_flag_numero_ticket.value == 1){ //se debe validar
        if(document.frm.numero_ticket.value=="" || document.frm.numero_ticket.value == 0){
            alert("El # de Ticket es necesario");
            document.frm.numero_ticket.focus();
            return false;
        }
    }
    	
    
    if (confirm('Confirme datos de registro?')==false) {
        return false;
    }
    desactivar_hora(false);	
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

function formatea_hora_minuto(o,e){//hourminute
    var ok=false;
    var a=(document.all) ? e.keyCode : e.which;
    var texto= o.value;
    if (texto.length > 4){
        o.value = texto.substr(0,4);
        ok=true;
        return ok;
    }
    
    if (texto.length == 2){//cuando sea hh completar :
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
        
    if (a>=48 && a<=57){//solo numeros
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
        
        var _es_duo="<?php echo $duo; ?>";//**
        var _ext_t="<?php echo $extension; ?>";//**
        
        if(_es_duo=="0"){//**
            if(hh_i>=hh_f){
                alert('La hora de inicio debe ser menor a la hora de fin');
                document.frm.hora_inicio.value='';
                document.frm.hora_inicio.focus();
                //document.frm.hora_inicio.select();
                bandera=false;
                return bandera;
            }
        }//**
        
        var turno_inicio="<?php echo $tt_inicio; ?>";
        var turno_final="<?php echo $tt_final; ?>";
        //alert(turno_final+_ext_t);
        turno_final=turno_final*1+_ext_t*1;
        //alert(turno_final);
        var _es_duo="<?php echo $duo; ?>";//**
        if(_es_duo=="0"){//**
        
            if(hh_i>= turno_inicio && hh_i<= turno_final){
                bandera=true;
            }else{
                alert('La hora de Inicio no esta dentro del turno programado!!');
                document.frm.hora_inicio.value='';
                document.frm.hora_inicio.focus();
                //document.frm.hora_inicio.select();
                bandera=false;
                return bandera;
            }

            if(hh_f>= turno_inicio && hh_f<= turno_final){
                bandera=true;
            }else{
                alert('La hora de Fin no esta dentro del turno programado!!');
                document.frm.hora_fin.value='';
                document.frm.hora_fin.focus();
                //document.frm.hora_fin.select();
                bandera=false;
                return bandera;
            }
        }
        //alert('hhinicio'+hh_i);
        //alert('hhfin'+hh_f);
        
    }else{
        bandera=false;
        alert('Formato incorrecto en Horas');
    }
    
    return bandera;
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

function validar(codigo, hh_dd){
    if(codigo == 2){
        document.frm.hdd_action.value = 'TIEMPOS'; //obtenemos los tiempos
        document.frm.submit();
    }
    if(hh_dd==0){ //diario
        if(codigo==11) {
            setear_tiempo(0);
            desactivar_hora(true);
        }else{	
            setear_tiempo(-1);
            desactivar_hora(true);	
        }
    }else{
        if(hh_dd==1){
            hdd_Incidencia_Inicio_Fin   = document.frm.hdd_Incidencia_Inicio_Fin.value;
        }
    }
    var numero_ticket      = document.frm.hdd_flag_numero_ticket.value
    if(numero_ticket == 0){
        alert("if");
        document.frm.numero_ticket.disabled = true;
    }else{
        alert("else");
        document.frm.numero_ticket.disabled = false;
    }
}


function validar_incidencia_1(c){
    if(c!=0){
        var arr=c.split('_');//setear_tiempo con -1 requiere dato
        var codigo=arr[0];
        var hh_dd=arr[1];
        valida_flag(codigo);
        document.frm.incidencia_codigo.value=codigo;
        document.frm.incidencia_hh_dd.value=hh_dd;
        validar(codigo,hh_dd);
    }    
}

/*
function validar_incidencia(c){//hourminute
    if(c!=0){
        
        var arr=c.split('_');//setear_tiempo con -1 requiere dato
        var codigo=arr[0];
        var hh_dd=arr[1];
        document.frm.incidencia_codigo.value=codigo;
        document.frm.incidencia_hh_dd.value=hh_dd;
        
        valida_flag(codigo);
        
        if(codigo == 2){
            document.frm.hdd_action.value = 'TIEMPOS'; //obtenemos los tiempos
            document.frm.submit();
        }
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
                if(codigo==42 || codigo==43 || codigo==79 || codigo==166 || codigo == 150 || codigo==176){
                        setear_tiempo(0);
                        desactivar_hora(true);//deshabilita control de inicio y fin
                }else{
                    <?php if($o->codigo_empresa == 1){ ?>
                    if(codigo==157 || codigo==178 || codigo==179 || codigo==4){
                      //alert(codigo);
                      setear_tiempo(0);
                      desactivar_hora(true);
                      
                    }else
                    <?php } ?>
                        
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
  
</script>
</HEAD>
<BODY class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
<center class='FormHeaderFont' >Registro de Incidencias</center>
<br/>
<center class="CA_FormHeaderFont"><?php echo $empleado?></center>

<table align="center">
		 <tr>
			<td><b>Tiempo disponible para registro de incidencia:</b></td>
                        <td style="background-color: #ED7715;">&nbsp;<?php echo $t_disponible;?>&nbsp;horas
		   </td>
		 </tr>
</table>

<?php //if($num!=0){ ?> 
<!--<table align="center">
		 <tr>
			<td><b>Nro. Asistencia:</b></td>
			<td><?php //echo $asistencia_codigo?>
		   </td>
		 </tr>
</table>-->
<?php //}?>
<br/>
<table width='98%' align="center" border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class='ColumnTD' align="right">
		 Incidencias&nbsp;
		</td>
		<td class='DataTD' align="left">
			<!--<select class='select' id="inc_cod" name="inc_cod"  style='width:450px' onchange='validar_incidencia(this.value)' <?php //echo $disabled ?>>
			<option value='0'>SELECCIONAR</option>-->
			<?php
                            /*query previo
                             * $sql="SELECT incidencia_codigo as codigo,incidencia_descripcion  AS descripcion,incidencia_hh_dd FROM ca_incidencias "; 
                            $sql .=" WHERE (area_codigo=0 or area_codigo=" . $area . ") and incidencia_manual=1  and incidencia_activo=1 ";
                            if ($encontrado == 'S'){
                                $sql .=" and auxiliaravaya='N' ";	
                            }
                            $sql .=" order by 2 asc";*/
                            
            $sql=" SELECT incidencia_codigo as codigo,incidencia_descripcion AS descripcion,incidencia_hh_dd ";
			$sql.=" FROM ca_incidencias ";
			$sql.=" WHERE  incidencia_manual=1 and incidencia_activo=1 ";
			$sql.=" AND INCIDENCIA_CODIGO in (";
			$sql.=" SELECT INCIDENCIA_CODIGO FROM CA_INCIDENCIA_AREA WHERE (AREA_CODIGO = 0 or AREA_CODIGO = ".$area." ) ";
			$sql.=" ) ";

            if ($encontrado == 'S'){
                    $sql .=" and auxiliaravaya='N' ";	
            }
			$sql.=" order by 2 asc ";

            $combo->query=$sql;
            $combo->name="inc_cod";
            //$combo->value = $incidencia_sel;
            $combo->more=" class='select' style='width:450px' onchange='validar_incidencia(this.value)' ".$disabled;
            echo $combo->construir_combo2values();
                        
                            /*$result = consultar_sql($sql);
                            if (mssql_num_rows($result)>0){
                                $rs= mssql_fetch_row($result);
                                while ($rs){
                                        echo "<option value ='" . $rs[0] . "¬" . $rs[2] . "' >" . $rs[1] . "</option>/n";
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
			  <?php echo $o->campana ?>
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
		    $sql .=" where  v_campanas.exp_activo=1 and coordinacion_codigo=" . $area;                   
			$sql .=" order by 2 asc"; 
			$combo->query = $sql;
			$combo->name = "cod_campana"; 
			$combo->value = $cod."";
			$combo->more = " class='select' disabled";
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
		  <!--&nbsp;Horas&nbsp;<select   name='horas' id='horas'>-->
                 &nbsp;Horas&nbsp;<select   name='horas' id='horas' disabled><!--hourminute-->
		  <option value="-1">hh</option>
		  <?php
		   for($h=0; $h < 24; $h++){
                        $hh=$h;
                        if(strlen($h)<=1) $hh="0".$h;
                        if($h==0) echo "\t<option value=". $h . " selected>". $hh ."</option>" . "\n";//hourminute
                        else//hourminute
                        echo "\t<option value=". $h . ">". $hh ."</option>" . "\n";
		   }
		  ?>
		 </select>
		 &nbsp;:&nbsp;Minutos&nbsp;
		 <!--<select  name='minutos' id='minutos' >-->
                 <select  name='minutos' id='minutos' disabled><!--hourminute-->
		  <option value="-1">mm</option>
		  <?php
		   for($m=0; $m < 60; $m++){
                        $mm=$m;
                        if(strlen($m)<=1) $mm="0".$m;
                        if($m==0) echo "\t<option value=". $m . " selected>". $mm ."</option>" . "\n";//hourminute
                        else//hourminute
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
			<input type='text' id="incidencia_observacion" name="incidencia_observacion" style="width:500px" value='<?php echo $incidencia_observacion; ?>'>
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
<input type='hidden' id="hddaccion" name="hddaccion" value=''/>
<input type='hidden' id="incidencia_hh_dd" name="incidencia_hh_dd" value=''/>
<input type='hidden' id="incidencia_codigo" name="incidencia_codigo" value='<?php echo $incidencia_codigo ?>'/>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_codigo ?>"/>
<input type='hidden' id='empleado_cod' name='area_cod' value="<?php echo $area ?>"/>
<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>"/>
<input type='hidden' id='asistencia_cod' name='asistencia_cod' value="<?php echo $asistencia_codigo ?>"/>
<input type='hidden' id='asistencia_entrada' name='asistencia_entrada' value="<?php echo $asistencia_entrada ?>"/>
<input type='hidden' id='n' name='n' value="<?php echo $num ?>"/>
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>"/>
<input type='hidden' id='asistencia_salida' name='asistencia_salida' value="<?php echo $asistencia_salida ?>"/>
<input type='hidden' id='area_codigo' name='area_codigo' value="<?php echo $area ?>"/>
<input type='hidden' id='hdd_action' name='hdd_action' value=""/>
<input type='hidden' id='hdd_Incidencia_Inicio_Fin' name='hdd_Incidencia_Inicio_Fin' value=""/>
<input type='hidden' id='hdd_flag_numero_ticket' name='hdd_flag_numero_ticket' value=""/>
</form>
</body>
</html>
<?php
if(isset($_POST["hdd_action"])){
    if($_POST["hdd_action"] == "TIEMPOS"){
        $o->empleado_codigo=$empleado_codigo;
        $o->asistencia_codigo =$asistencia_codigo;
        $o->incidencia_codigo =$incidencia_codigo;
        $respuesta = $o->existe_incidencia(3);
        if(!$respuesta){
            ?>
            <script language='javascript'>
    		alert('¡Error! Solo se puede registrar cuando exista incidencia de HORAS ADICIONALES PARA COMPENSACION.');
    		document.frm.inc_cod.value=0;
    		</script>
            <?php
            return;
        }else{
            $total_minutos = ($respuesta["horas"] * 60) + $respuesta["minutos"];  
            //if( $total_minutos >= 30 ){
            if(1){    
                ?>
                <script>
                c = "<?php echo $incidencia_sel?>";
                var arr=c.split('¬');	
            	var codigo=arr[0];
            	var hh_dd=arr[1];
            	document.frm.incidencia_codigo.value=codigo;
            	document.frm.incidencia_hh_dd.value=hh_dd;
                
                document.frm.horas.value="<?php echo $respuesta["horas"]?>";
                document.frm.minutos.value="<?php echo $respuesta["minutos"]?>";
                document.frm.inc_cod.value="<?php echo $incidencia_sel?>";
                document.frm.horas.disabled = true;
                document.frm.minutos.disabled = true;
                </script>
                <?php
            }else{
               ?>
                <script language='javascript'>
        		alert('¡Error! No se puede registrar incidencia para un tiempo menor a los 30 minutos');
        		document.frm.inc_cod.value=0;
        		</script>
                <?php 
            }
        }
    }
}
?>