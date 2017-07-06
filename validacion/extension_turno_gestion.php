<?php header("Expires: 0");
require_once("../includes/Seguridad.php");
require_once("../../Includes/Connection.php");
require_once("../../Includes/Constantes.php"); 
require_once("../../Includes/mantenimiento.php");
require_once("../../Includes/librerias.php");
require_once("../../Includes/clsIncidencias.php"); 
require_once("../../Includes/MyCombo.php"); 
require_once("../includes/clsCA_Asistencias.php");
require_once("../includes/clsCA_Asistencia_Incidencias.php");  
require_once("../includes/clsCA_Empleados.php"); 

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

$ip_entrada=$_SERVER['REMOTE_ADDR'];
$ip_salida=$_SERVER['REMOTE_ADDR'];
//if (isset($_GET["area"])) $area=$_GET["area"];
//if (isset($_GET["empleado"])) $empleado_codigo = $_GET["empleado"];
if (isset($_POST["empleado_cod"])) $empleados = $_POST["empleado_cod"];

$usuario_especial = 0;
if (isset($_POST["usuario_especial"])) $usuario_especial = $_POST["usuario_especial"];
if (isset($_GET["responsable_codigo"])){
  //  $responsable_codigo = $_GET["responsable_codigo"];  
    $usuario_especial = $_GET["responsable_codigo"];
} 
 
if (isset($_POST["responsable_cod"])) $responsable_codigo = $_POST["responsable_cod"];
//if (isset($_GET["asistencia"])) $asistencia_codigo = $_GET["asistencia"];
//if (isset($_POST["asistencia_cod"])) $asistencia_codigo = $_POST["asistencia_cod"];
if (isset($_GET["fecha"])) $fecha = $_GET["fecha"];
if (isset($_POST["fecha"])) $fecha = $_POST["fecha"];
//if (isset($_GET["area"])) $area=$_GET["area"];
$area = 0;
if (isset($_POST["area_codigo"])) $area = $_POST["area_codigo"];

if (isset($_GET["num"])) $num = $_GET["num"];
if (isset($_POST["n"])) $num = $_POST["n"];
if (isset($_POST["incidencia_codigo"])){ 
 $incidencia_codigo = $_POST["incidencia_codigo"];
}
if (isset($_POST["horas"])) $horas = $_POST["horas"];
if (isset($_POST["minutos"])) $minutos = $_POST["minutos"];
if (isset($_POST["incidencia_hh_dd"])) $incidencia_hh_dd = $_POST["incidencia_hh_dd"];
if($cod_campana=='') $cod_campana=0;
if (isset($_POST["cod_campana"])){
	$cod = $_POST["cod_campana"];
	$cod_campana=$cod;
}

if (isset($_POST['hddaccion'])){
  if ($_POST['hddaccion']=='INS'){
    $arr = explode(",",$empleados);
    
    $num_arr = sizeof($arr);
    for ($i=0; $i<$num_arr; $i++){
    	$tmpa = explode("¬",$arr[$i]);
        $empleado_codigo=$tmpa[0];
        //$empleado_codigo=$arr[$i];
        $o->extension_tiempo=$tmpa[1];
        $o->empleado_codigo=$empleado_codigo;
        $o->empleado_tipo_pago=$tmpa[2];
        //echo "extension_tiempo".$o->extension_tiempo."empleado_codigo".$o->empleado_codigo."empleado_tipo_pago".$o->empleado_tipo_pago;
        //echo "x". $tmpa[2];
        $mensaje = $o->Registrar_ET();
        if($mensaje=='OK'){
            echo "<br>Empleado: " . $empleado_codigo . ", Se guardo registro OK.";
        }else{
            echo "<br>Empleado: " . $empleado_codigo . ": " . $mensaje;
        }
    } //for
  }//if
  if ($_POST['hddaccion']=='DEL'){
    $arr = explode(",",$empleados);
    $num_arr = sizeof($arr);
    for ($i=0; $i<$num_arr; $i++){
        $empleado_codigo=$arr[$i];
        $o->empleado_codigo=$empleado_codigo;
        $mensaje = $o->Eliminar_ET();
        if($mensaje=='OK'){
          echo "<br>Empleado: " . $empleado_codigo . ", Se guardo registro OK.";
        }else{
          echo "<br>Empleado: " . $empleado_codigo . ": " . $mensaje;
        }
    } //for
  }//if
}//if

?>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<title>Extension de Turnos</title>
<meta http-equiv="pragma" content="no-cache"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<script language="JavaScript" src="../../default.js"></script>
<script language="JavaScript" type="text/javascript" src="../jscript.js"></script>
<link href="../style/tstyle.css" rel="stylesheet" type="text/css"/>
<script>

function cmdCancelar_onclick() {
  window.opener.document.forms['frm'].submit();
  window.opener.document.frm.cmdx.click();
  window.opener.focus();
  window.close();
	
}

function cmdCancelar() {
  self.location.href = "../gestionturnos/main_turnos_gestion.php";
}

function ver_tipo(v) {
alert('aqui');
}

function cmdGrabar_onclick(){
  var cod_empleados="";
  var tiempo_minimo = <?php echo $extension_tiempo_minutos?>;
  var tiempo_maximo = <?php echo $extension_tiempo_maximo?>;
  for(i=0; i< document.frm.length; i++ ) { 
    if (frm.item(i).type=='checkbox'){
      if (frm.item(i).checked){
        if (frm.item(i).value > 0){
	      	if( document.getElementById('txt_'+frm.item(i).value).value=='' || 
                document.getElementById('txt_'+frm.item(i).value).value < tiempo_minimo  || 
                document.getElementById('txt_'+frm.item(i).value).value > tiempo_maximo  ){
	      		alert('Alguno de los tiempos de extension no está permitido, por favor verifique ');
	      		return false;
	      	}
			if (cod_empleados=='')
				cod_empleados=frm.item(i).value+'¬'+document.getElementById('txt_'+frm.item(i).value).value+'¬'+document.getElementById('hdd_'+frm.item(i).value).value;
			else
				cod_empleados=cod_empleados + ',' + frm.item(i).value+'¬'+document.getElementById('txt_'+frm.item(i).value).value+'¬'+document.getElementById('hdd_'+frm.item(i).value).value;
                        
        }
      }
    }
  } 
  
  if (cod_empleados==''){
    alert('Seleccione empleados');
    return false
  }
  
  if (confirm('Confirme Actualizar los registros?')==false) return false;
  
  document.frm.empleado_cod.value=cod_empleados;
  document.frm.hddaccion.value="INS";
  document.frm.submit()
}

function cmdEliminar_onclick(){
  var cod_empleados="";
  for(i=0; i< document.frm.length; i++ ) { 
    if (frm.item(i).type=='checkbox'){
      if (frm.item(i).checked)      {
        if (frm.item(i).value>0){
          if (cod_empleados=='')
            cod_empleados=frm.item(i).value;
          else
            cod_empleados=cod_empleados+','+frm.item(i).value;
        }
      }
    }
  } 
  if (cod_empleados==''){
    alert('Seleccione empleados');
    return false
  }
  if (confirm('Confirme Quitar Extension de los registros?')==false) return false;
  document.frm.empleado_cod.value=cod_empleados;
  document.frm.hddaccion.value="DEL";
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
	var arr=c.split('¬');	
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
	  	   if(codigo==42 || codigo==43 ) {
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

function solodecimal(valor){
    var valid="0123456789";
    var punto = 0;
    var rpta = '';
    for (var i=0; i < valor.length; i++){
        val = valor.substring(i, i+1);
        if (val == '.'){
          if (i == 0) rpta += '0';
          if (punto > 0) val = '';
          punto++;
        }
        if (valid.indexOf(val) > -1) rpta += val;
    }
    return rpta;
}

function validar(pos){
	var cantidad = document.getElementById(pos).value;
	var minimo = document.getElementById(pos).alt;
	if (cantidad == '' || cantidad*1<=0){
		if (minimo*1==0){
			document.getElementById(pos).value = '<?php echo $extension_tiempo_minutos ?>';
		}else{
			document.getElementById(pos).value = document.getElementById(pos).alt;
		}
	}
}

function validar1(pos){
	var cantidad = document.getElementById(pos).value;
	var minimo = document.getElementById(pos).alt;
	var maximo = '<?php echo $extension_tiempo_maximo ?>';
	if (cantidad == '' || cantidad*1 <= 0){
		if (minimo*1==0){
			document.getElementById(pos).value = '<?php echo $extension_tiempo_minutos ?>';
		}else{
			document.getElementById(pos).value = document.getElementById(pos).alt;
		}
		return;
	}else{
		if (cantidad*1 < minimo*1){
			alert('No puede extender turno menor al que ya fue extendido, se restableceran los valores');
			document.getElementById(pos).value = document.getElementById(pos).alt;
			return;
		}
		if (cantidad*1 > maximo*1){
			alert('No puede extender turno mayor al maximo permitido, se restableceran los valores');
			document.getElementById(pos).value = document.getElementById(pos).alt;
			return;
		}
	}
}
function MostrarArea()
{
    document.frm.responsable_cod.value = "0";
    document.frm.submit();
}

function MostrarGrilla()
{
    document.frm.submit();
}
</script>
</head>
<body class="PageBODY">
<form id='frm' name='frm' action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<center class='FormHeaderFont' >Extension de Turnos</center>
<br/>
<table align="center">
	<tr>
		<td align="center">
			<img src="../images/botonASIGNAR-off.gif" onmouseover="this.src='../images/botonASIGNAR-on.gif'" onmouseout="this.src='../images/botonASIGNAR-off.gif'" style="cursor:hand " onClick="return cmdGrabar_onclick()"/>&nbsp;&nbsp;
			<img src="../images/botonELIMINAR-off.gif" onmouseover="this.src='../images/botonELIMINAR-on.gif'" onmouseout="this.src='../images/botonELIMINAR-off.gif'" style="cursor:hand " onClick="return cmdEliminar_onclick()"/>&nbsp;&nbsp;
            <?php if($opcion == "DIARIA"):?>
			<img src="../images/botonCANCELAR-off.gif" onmouseover="this.src='../images/botonCANCELAR-on.gif'" onmouseout="this.src='../images/botonCANCELAR-off.gif'" style="cursor:hand " onclick="return cmdCancelar_onclick()"/>
            <?php else:?>
            <img src="../images/botonCANCELAR-off.gif" onmouseover="this.src='../images/botonCANCELAR-on.gif'" onmouseout="this.src='../images/botonCANCELAR-off.gif'" style="cursor:hand " onclick="return cmdCancelar()"/>
            <?php endif;?>
		</td>
	</tr>
</table>
<br/>
<!--pegar tabla-->
<table class='FormTable' align="center" border="0" cellpadding="0" cellspacing="1" style="width:650px">
<?php
    if($opcion == "DIARIA"){
        ?>
        <tr align="center" >
            <td class="ColumnTD" bgcolor="#33CC33" width='3%'>
            <input type="checkbox" align="center" id='chkall' name='chkall' value='0' onclick='checkear()'/><br/><b>TODOS</b>
            </td>
            <td class="ColumnTD" width='4%'>Código</td>
            <td class="ColumnTD" width='35%'>Empleado</td>
            <td class="ColumnTD" width='12%'>Turno</td>
            <td class="ColumnTD" width='6%'>Minutos</td>
            <td class="ColumnTD" width='2%'>Ext</td>
            <td class="ColumnTD" width='2%'>Tipo_Pago</td>
        </tr>
        <?php
        $o->fecha=$fecha;
    	$o->area_codigo=$area;
    	$o->responsable_codigo=$responsable_codigo;
    	$o->extension_tiempo=$extension_tiempo_minutos;
      	$cadena=$o->Listar_personal_asiste();
    	echo $cadena;    
    }else{//validacion gtr
    ?>  
        <tr align='left'>
        <td class="texto" align="center">Area:</td>
        <td class="texto">
        <?php
        
        $tiene_rol_administrador = $o->Tiene_Rol_Necesario($_SESSION["empleado_codigo"],3);
        if($tiene_rol_administrador == "SI"){ //si es el administador listamos todas las areas activas
            $sSql = "select Area_Codigo, Area_Descripcion 
                     from areas 
                     where Area_Activo = 1 and Area_Codigo <> 0 
                     order by 2 asc";
        }else{
            $sSql=" SELECT c.area_codigo, a.area_descripcion  
                    FROM ca_controller c inner join areas a on c.area_codigo=a.area_codigo 
                    WHERE  a.area_activo =1 and c.activo=1 and empleado_codigo= $usuario_especial
                    order by 2 ";    
        }
        

        $combo->query = $sSql;
        $combo->name = "area_codigo";
        $combo->value = $area."";
        
        $combo->more = " class=cv_select alt='Area' onchange='MostrarArea()'";
        $rpta = $combo->Construir();
        echo $rpta;
        ?>
        </td>
        </tr>
     
    <tr align='left'>
        <td class="texto" align="center">Responsable: </td>
        <td class="texto">
        <?php
        $sSql = "SELECT distinct   Empleados.empleado_codigo as empleado_responsable, Empleados.Empleado_Apellido_Paterno + ' ' + Empleados.Empleado_Apellido_Materno + ' ' + Empleados.Empleado_Nombres AS Responsable  
                 FROM CA_Asignacion_Empleados(nolock) INNER JOIN 
                     vDatos(nolock) ON CA_Asignacion_Empleados.Empleado_Codigo = vDatos.Empleado_Codigo INNER JOIN  
                     Empleados(nolock) ON CA_Asignacion_Empleados.Responsable_Codigo = Empleados.Empleado_Codigo 
                 WHERE CA_Asignacion_Empleados.Asignacion_Activo = 1  
                		AND vDatos.Area_Codigo = ". $area."
                        AND Area_Codigo in (SELECT c.area_codigo
						                    FROM ca_controller c 
		                                    WHERE c.activo=1 and empleado_codigo=".$usuario_especial.")";
        
        $combo->query = $sSql;
        $combo->name = 'responsable_cod';
        $combo->value = $responsable_codigo."";
        $combo->more = " class=cv_select alt='Responsable'onchange='MostrarGrilla()'";
        $rpta = $combo->Construir();
        echo $rpta;
        ?>
        </td>
    </tr>
    </table>
    <table class='FormTable' align="center" border="0" cellpadding="0" cellspacing="1" style="width:650px">
    <tr align="center" >
            <td class="ColumnTD" bgcolor="#33CC33" width='3%'>
            <input type="checkbox" align="center" id='chkall' name='chkall' value='0' onclick='checkear()'/><br/><b>TODOS</b>
            </td>
            <td class="ColumnTD" width='4%'>Código</td>
            <td class="ColumnTD" width='35%'>Empleado</td>
            <td class="ColumnTD" width='12%'>Turno</td>
            <td class="ColumnTD" width='6%'>Minutos</td>
            <td class="ColumnTD" width='2%'>Ext</td>
            <td class="ColumnTD" width='2%'>Tipo_Pago</td>
        </tr>
        <?php
        $o->fecha=$fecha;
    	$o->area_codigo=$area;
    	$o->responsable_codigo=$responsable_codigo;
    	$o->extension_tiempo=$extension_tiempo_minutos;
      	$cadena=$o->Listar_personal_asiste();
    	echo $cadena; 
    
    }
    ?>
<!--fin pegar tabla-->
<br/>
<table align="center">
	<tr>
		<td align="center">
			<img src="../images/botonASIGNAR-off.gif" onmouseover="this.src='../images/botonASIGNAR-on.gif'" onmouseout="this.src='../images/botonASIGNAR-off.gif'" style="cursor:hand " onclick="return cmdGrabar_onclick()"/>&nbsp;&nbsp;
			<img src="../images/botonELIMINAR-off.gif" onmouseover="this.src='../images/botonELIMINAR-on.gif'" onmouseout="this.src='../images/botonELIMINAR-off.gif'" style="cursor:hand " onclick="return cmdEliminar_onclick()"/>&nbsp;&nbsp;
            <?php if($opcion == "DIARIA"):?>
			<img src="../images/botonCANCELAR-off.gif" onmouseover="this.src='../images/botonCANCELAR-on.gif'" onmouseout="this.src='../images/botonCANCELAR-off.gif'" style="cursor:hand " onclick="return cmdCancelar_onclick()"/>
            <?php else:?>
            <img src="../images/botonCANCELAR-off.gif" onmouseover="this.src='../images/botonCANCELAR-on.gif'" onmouseout="this.src='../images/botonCANCELAR-off.gif'" style="cursor:hand " onclick="return cmdCancelar()"/>
            <?php endif;?>
		</td>
	</tr>
</table>

<input type='hidden' id="hddaccion" name="hddaccion" value=''/>
<input type='hidden' id="incidencia_hh_dd" name="incidencia_hh_dd" value=''/>
<input type='hidden' id="incidencia_codigo" name="incidencia_codigo" value='<?php echo $incidencia_codigo ?>'/>
<input type='hidden' id='empleado_cod' name='empleado_cod' value="<?php echo $empleado_codigo ?>"/>
<?php if($opcion == "DIARIA"):?>
<input type='hidden' id='responsable_cod' name='responsable_cod' value="<?php echo $responsable_codigo ?>"/>
<?php endif;?>
<input type='hidden' id='asistencia_cod' name='asistencia_cod' value="<?php echo $asistencia_codigo ?>"/>
<input type='hidden' id='asistencia_entrada' name='asistencia_entrada' value="<?php echo $asistencia_entrada ?>"/>
<input type='hidden' id='n' name='n' value="<?php echo $num ?>"/>
<input type='hidden' id='fecha' name='fecha' value="<?php echo $fecha ?>"/>
<input type='hidden' id='asistencia_salida' name='asistencia_salida' value="<?php echo $asistencia_salida ?>"/>
<?php if($opcion == "DIARIA"):?>
<input type='hidden' id='area_codigo' name='area_codigo' value="<?php echo $area ?>"/>
<?php endif;?>
<input type='hidden' id='usuario_especial' name='usuario_especial' value="<?php echo $usuario_especial ?>"/>
</form>
</body>
</html>