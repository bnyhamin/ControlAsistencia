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
$tiempo=0;

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

//N
$as->empleado_codigo=$empleado_codigo;
$as->asistencia_fecha=$fecha;
$k=$as->valida_marca_entrada();
if($k==0){
    ?>
    <script type="text/javascript">
        alert("El empleado debe tener registro de asistencia para poder registrar incidencias");
        window.close();
    </script>
<?php
}
//N

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

//N
if($asistencia_codigo*1!=0){
    $as->empleado_codigo=$empleado_codigo;
    $as->asistencia_codigo=$asistencia_codigo;
    $as->saldo_tiempo(1);
    $t_disponible=$as->tiempo_disponible($as->saldo_tiempo);
}else{
    $emp->empleado_codigo=$empleado_codigo;
    $emp->fecha=$fecha;
    $emp->Query_Turno();
    $as->turno_codigo=$emp->turno_codigo;
    $as->minutos_turno();
    $t_disponible=$as->tiempo_disponible($as->minutos_turno);
}
//N

if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
        //-- registrar
        $o->empleado_codigo=$empleado_codigo;
        $o->asistencia_codigo =$asistencia_codigo;
        $o->incidencia_codigo =$incidencia_codigo;
        
        //N
        if(($incidencia_codigo*1==4) || ($incidencia_codigo*1==42) || ($incidencia_codigo*1==157) || ($incidencia_codigo*1==166) ) $indica=1;    
        else $indica=0;
        if($indica==0){
            if($asistencia_codigo*1!=0){
                $as->empleado_codigo=$empleado_codigo;
                $as->asistencia_codigo=$asistencia_codigo;
                $as->saldo_tiempo(1);
                $tiempo=(($horas*1)*60)+($minutos*1);
                if(!($tiempo*1<=$as->saldo_tiempo*1)){
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
        //N
        
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
        
        if($o->incidencia_codigo == 157){
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
        if( $o->incidencia_codigo == 42){
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
		if( $o->incidencia_codigo == 170){
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
		
		if($incidencia_codigo!=42 && $incidencia_codigo!=43 && $incidencia_codigo!=11 && $incidencia_codigo!=166){
			$o->horas =$horas;
			$o->minutos =$minutos;
		}else{
		   $o->horas =0;
		   $o->minutos=0;
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
                
                $mensaje = $o->registrar_incidencia($num,$ip_entrada,$ip_salida);
		         
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
<script type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
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
    }
    
}

function cmdCancelar_onclick() {
	self.returnValue = 0
	self.close();
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
    
    if(document.frm.incidencia_codigo.value!=66 && document.frm.incidencia_codigo.value!=67 )
    {
     if(document.frm.incidencia_hh_dd.value==1){	
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
      }		
    }	
    	
    
    if (confirm('Confirme datos de registro?')==false) {
        activar_tiempo(true);
        return false;
    }
    	//document.frm.action +="?codigo=" + asistencia + "&responsable=" + responsable;
        activar_tiempo(false);	
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
function validar_incidencia(c){
    
if(c!=0){	
    //document.frm.hdd_action.value = c;
	var arr=c.split('_');	
	var codigo=arr[0];
	var hh_dd=arr[1];
	document.frm.incidencia_codigo.value=codigo;
	document.frm.incidencia_hh_dd.value=hh_dd;
	
    
    if(codigo == 2){
        document.frm.hdd_action.value = 'TIEMPOS'; //obtenemos los tiempos
        document.frm.submit();
    }
    
	if(hh_dd==0){ //diario
	    if(codigo==11) {
	 	    setear_tiempo(0);
		    activar_tiempo(true);
	      }else{	
	        setear_tiempo(-1);
	        activar_tiempo(true);	
		 }
	}else{ 
	  	if(hh_dd==1)
		{ //horario		
			if(codigo==42 || codigo==43 || codigo==79 || codigo==166 || codigo == 150) 
			{
				setear_tiempo(0);
				activar_tiempo(true);
			}
			else
			{
				<?php if($o->codigo_empresa == 1){ ?>
				if(codigo==157 || codigo==4){
				  setear_tiempo(0);
				  activar_tiempo(true);	
				}
				else
				<?php } ?>
				if(codigo==66 || codigo==67)
				{
				  setear_tiempo(-1);
				  activar_tiempo(true);	
				}else{
				 setear_tiempo(-1);
				 activar_tiempo(false);	
				}
			}	
	  	}	
	}
 }
} 
function setear_tiempo(valor){
   document.frm.horas.value=valor;
   document.frm.minutos.value=valor;
} 
function activar_tiempo(valor){
   document.frm.horas.disabled=valor;
   document.frm.minutos.disabled=valor;
} 

  
</script>
</HEAD>
<BODY class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
<center class='FormHeaderFont' >Registro de Incidencias</center>
<br>
<center class="CA_FormHeaderFont"><?php echo $empleado?></Center>

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
<br>
<TABLE WIDTH='98%' ALIGN=center BORDER=0 CELLSPACING=1 CELLPADDING=0>
	<TR>
		<TD class='ColumnTD' align=right>
		 Incidencias&nbsp;
		</TD>
		<TD class='DataTD' align=left>
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
                    <input type='text' id="numero_ticket" name="numero_ticket" size="20" value=""/>
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
</form>
</BODY>
</HTML>
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