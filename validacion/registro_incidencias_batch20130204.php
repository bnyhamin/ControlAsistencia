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
        
        
        $k=$as->valida_marca_entrada();
        if($k!=0){
        
            $as->saldo_tiempo(2);
            $tiempo=(($horas*1)*60)+($minutos*1);
            if($tiempo*1<=$as->saldo_tiempo*1){
                if($flag_registrar == 1){
                    $o->supervisor_registra = $supervisor_registra;
                    $mensaje = $o->registrar_incidencia($turno,$ip_entrada,$ip_salida);
                }
                if($mensaje=='OK'){
                    echo "<br>Empleado: " . $empleado_codigo . ", Se guardo registro OK. ".$mensaje;
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
<link href="../style/tstyle.css" rel="stylesheet" type="text/css">
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
  activar_tiempo(false);
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
function validar_incidencia(c){
if(c!=0){	
	var arr=c.split('_');	
	var codigo=arr[0];
	var hh_dd=arr[1];
	document.frm.incidencia_codigo.value=codigo;
	document.frm.incidencia_hh_dd.value=hh_dd;
	
	if(hh_dd==0){ //diario
	    if(codigo==11) {
	 	    setear_tiempo(0);
		    activar_tiempo(true);
	      }else{	
	        setear_tiempo(-1);
	        activar_tiempo(true);	
		 }
	}else{ 
	  	if(hh_dd==1){ //horario
	  	   if(codigo==42 || codigo==43 || codigo == 157) {
	 	    setear_tiempo(0);
		    activar_tiempo(true);
	       }else{	
	       	if(codigo==66 || codigo==67){
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
  
</SCRIPT>
</HEAD>
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
			<input type='text' id="incidencia_observacion" name="incidencia_observacion" style="width:500px" value='<?php echo $incidencia_observacion; ?>'/>
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
</form>
</BODY>
</HTML>