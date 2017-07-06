<?php header("Expires: 0");

require_once("../includes/Seguridad.php");  
require('../../Includes/Connection.php');
require('../../Includes/Constantes.php');
//require('../../Includes/MyCombo.php');
require_once("../../Includes/mantenimiento.php");
require('../includes/bio_Plataforma.php');
require_once("../includes/clsCA_Empleado_Rol.php");

$fecha_inicio_temp=date("d/m/Y",time());
$fecha_fin_temp=date("d/m/Y",time()+86400);

 
$o = new BIO_Plataforma();
$o->MyDBName= db_name();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();

//$acceso = $_GET["acceso"];
$acceso = 1;

$o->acceso = $acceso;
$e = new ca_empleado_rol();
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());
$e->empleado_codigo = $_SESSION["empleado_codigo"];
$arr_roles=$e->Devolver_roles();


$acceso_permanente = false;

if( in_array(2, $arr_roles) || in_array(3, $arr_roles) || in_array(13, $arr_roles) ){  // jefe o administrador o Permisos Biometricos empleados y visitas

$acceso_permanente = true;

}

//$acceso_permanente = false; 
$resultado = "";  
if (isset($_GET["hddaccion"])){ 
    if ($_GET["hddaccion"]=="asignar"){
        if ($_FILES['archivo']["error"] > 0){
          echo "Error: " . $_FILES['archivo']['error'] . "<br>";
        }else{
          $directorio="../cargas/";
          $archivo=$_FILES['archivo']['tmp_name'];
          $nombrearchivo=$_FILES['archivo']['name'];
          
          $ext_permitidas = array("txt");
          
          $file_ext = "";
          if (!(strpos($nombrearchivo,".")===true)) {
                $file_ext = strtolower(end(explode(".",$nombrearchivo)));
          } 

          if (in_array($file_ext,$ext_permitidas)) {
              if( move_uploaded_file($archivo, $directorio.$nombrearchivo)){
                      $archivo = file($directorio.$nombrearchivo); 
        		      $lineas = count($archivo);
                      $empleados = "";
                      for($i=0; $i < $lineas; $i++){
                        $empleados .= trim($archivo[$i]);
                        if( ($i + 1) < $lineas)
                            $empleados .=","; 
                      }
                      $arr_plataformas = explode(",",$_POST["hplataformas"]);
                      $arr_empleados = explode(",",trim($empleados)); //enviamos los dnis cargados en el archivo 
                      
                      //echo  $_POST["hplataformas"]."-".trim($empleados);
                      
                      if(count($arr_empleados)){
                        $o->cargarEmpleadoPlataformas($arr_empleados, $arr_plataformas, $_POST['tipo_acceso'], $_POST['txtFechaInicio'], $_POST['txtFechaFin'], $_POST['observacion'], $_POST['permiso_activo'],$nombrearchivo );
                        $resultado = "Se cargaron correctamente:" . $o->cb_correctas." Registros.<br> Se encontrarón ".$o->cb_fallidas." registros fallidos";
                      }else{
                         $resultado = "El archivo no contiene registros para cargar";
                      }
                  }else{
                    $resultado = "archivo no subio";
                  }
          }else{
                echo "ERROR: El Formato del archivo no esta permitido.";
          }
       }
    }
}

?>
<html>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<HEAD>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
	
  <link rel="stylesheet" type="text/css" href="../../default.css"/>
	<script language="JavaScript" src="../../default.js"></script>
  <script language="JavaScript" src="../jscript.js"></script>
  <title>Asignar Acceso Empleados a Plataformas</title>
 
</head>
<script language='javascript'>

function guardar()
{   
    
        if (confirm("Confirme asignar plataforma ")){    
    
            var codigos='';
            var nodos=document.getElementsByTagName("input");
            var marcadoTipoAcceso = 't';
            
            for(var i=0;i<nodos.length;i++){
                if((nodos[i].type=="checkbox" || nodos[i].type=="radio")  && nodos[i].id.substring(0,3)=='chk'){
                    if(nodos[i].checked){
                        if (nodos[i].value!='on'){
                           if (codigos=='')
                                codigos=nodos[i].value;
                           else
                                codigos+= ',' + nodos[i].value;
                        }
                    }
                }
            }
            
            if (codigos==''){
                alert('Seleccione una Plataforma');
                return false
            } 
        
            document.frm.hplataformas.value=codigos;
             
            for(i=0;i<document.frm.tipo_acceso.length;i++){
            
                if(document.frm.tipo_acceso[i].checked) {
                    marcadoTipoAcceso=document.frm.tipo_acceso[i].value;
                }
            }
        
            if(marcadoTipoAcceso == 't'){
             
                 if(validaFechaMayor()){
                 
                    document.frm.submit();
                    //self.close(); 
                 }
             }else {
             
                   document.frm.submit();
                   //self.close();
             }
        }
  
}

function cancelar()
{
	   document.frm.hplataformas.value = '';
  	 self.close();
}
function checkear(){
   
if(document.frm.chk_todos.checked){
   checkear_todos_empleados(true);
}
else{
   checkear_todos_empleados(false);
  }
}

function checkear_todos_empleados(flag){
  var r=document.getElementsByTagName('input');
  for (var i=0; i< r.length; i++) {
       var o=r[i];
       var oo=o.id;
       if(oo.substring(0,3)=='chk'){
	       if(o.checked)
	   		{
	   			o.checked=flag;
	   		}else{
	   			o.checked=flag;
	   		}
	   }    
  }
}

function pedirFecha(campoTexto,nombreCampo) {
  ano = anoHoy();
  mes = mesHoy();
  dia = diaHoy();
  campoDeRetorno = campoTexto;
  titulo = nombreCampo;
  dibujarMes(ano,mes);
}

function mostrarOcultar(ID, tipo ) {
    
    div = document.getElementById(ID);
    if(tipo =='show'){
       div.style.display = 'none';
    }else {
       div.style.display = '';
    }

}

function validaFechaMayor() {

    var dInicio =document.frm.txtFechaInicio.value;
    var dFin    =document.frm.txtFechaFin.value; 
  
    var arrTmpInicio = dInicio.split("/");
    var arrTmpFin    = dFin.split("/");

    var anio1=arrTmpInicio[2];
    var mes1=arrTmpInicio[1];
    var dia1=arrTmpInicio[0];
    
    var anio2=arrTmpFin[2];
    var mes2=arrTmpFin[1];
    var dia2=arrTmpFin[0];
    
    var fecha1=new Date(anio1,mes1,dia1);
    var fecha2=new Date(anio2,mes2,dia2);
    
    if(fecha2<fecha1){
      alert("¡Fecha fin debe ser mayor o igual a fecha de inicio!") ;
      return false; 
    }
     return true;
}

 function cmdVerAsignacionPlataforma_onclick(CB_Codigo){
     
      var posicion_x; 
      var posicion_y; 
      posicion_x=(screen.width/2)-(680/2); 
      posicion_y=(screen.height/2)-(500/2); 
      
      var valor = window.open('log_carga_asignaciones.php?CB_Codigo='+CB_Codigo,'LogAsignaciones','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=850,height=350,left='+posicion_x+',top='+posicion_y);
      if (valor == "" || valor == "0" || valor == undefined){return false;}
      
      valor.focus();
    
}

</script>  	
<body>
<center class="TITOpciones">Asignar Accesos Empleados a Plataformas</center>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>?hddaccion=asignar' method='post' enctype="multipart/form-data">
<table  style='width:650px' border="0" align="center">
<tr>
  <td align="left">
    <?php if($acceso == 1){ ?>
    <input type="checkbox" name="chk_todos" id= "chk_todos" onclick="checkear()"/><font size="0.5">Seleccionar Todos</font>
    <?php } ?>
  </td>
</tr>
<tr>
  <td rowspan="2">
     <table class='table' style='width:300px' >
      <tr class='cabecera'>
          <td>&nbsp;</td>
          <td align="left">Plataforma</td>
      </tr>
          <?php
              $o->listarAccesos();
          ?>
      </table>
  </td>
  <td>
        <table class='table'  border="0">
        <tr class='cabecera'>
            <td colspan="2">Tipo de acceso:</td>
        </tr>    
        <td>Seleccione archivo (.txt)</td>
        <td> <input type="file" name="archivo" id="archivo"/></td>
        <tr>
        <td colspan="2" align="right"><a href="carga_de_prueba.txt" target="_blank"><img border="0" src="../images/bookmark-on.gif" /> ver formato de ejemplo</a> </td>
        </tr>
        </table>
  </td>
</tr>
<tr>
    <td valign="top">
       <table class='table'  border="0" width="100%">
        <tr class='cabecera'>
            <td colspan=2>Tipo de acceso:</td>
        </tr>    
        <tr>    
            <td align="left">
            <?php 
                $checked_temporal = "checked";
                
                if($acceso_permanente){

            ?>
            <input type='radio' name='tipo_acceso' value='p' onclick='mostrarOcultar("rangoFechas","show")'  />Permanente
             <?php
                }

             ?>
            &nbsp;&nbsp;<input type='radio' name='tipo_acceso' value='t' onclick='mostrarOcultar("rangoFechas", "hide")' <?php echo $checked_temporal; ?>  />Temporal</td>   
        </tr>
        <tr>
          <td>
            <table id ='rangoFechas' >
               <tr><td class='DataTD' nowrap="nowrap" >Fecha inicio</td>
                   <td class='DataTD'>&nbsp;&nbsp;&nbsp; <input type='text' class='input' id='txtFechaInicio' name='txtFechaInicio' readOnly size=11 value='<?php echo $fecha_inicio_temp;?>'> 
   			                    <img onClick='javascript:pedirFecha(txtFechaInicio,"Cambiar Fecha");' src='../images/columnselect.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Seleccionar Fecha de inicio'>
                  </td>
               </tr>
               <tr><td class='DataTD'>Fecha fin</td>
                    <td class='DataTD'>&nbsp;&nbsp;&nbsp; <input type='text' class='input' id='txtFechaFin' name='txtFechaFin' readOnly size=11 value='<?php echo $fecha_fin_temp;?>'> 
   			                    <img onClick='javascript:pedirFecha(txtFechaFin,"Cambiar Fecha");' src='../images/columnselect.gif' width='15' height='15' border='0'  style='cursor:hand;' alt='Seleccionar Fecha de fin'>
                    </td>
               </tr>
            </table>
          </td>
       </tr>
       <tr><td class='DataTD'>Observaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name='observacion' rows='4' cols='30'></textarea></td></tr>
       <tr><td class='DataTD'>Permiso activo&nbsp;&nbsp;<input type='checkbox' name='permiso_activo'  value='1' checked></td></tr>
     </table>
     <br>
     
      <?php if($acceso == 0){ ?>
      <table class='table' style='width:300px'>
      <tr >
       <td><p>Los mandos pueden asignar acceso a plataforma a una persona sólo una vez. Accesos adicionales solicitar a CIA</p>
       </td>
      </tr>
      </table>
      <?php } ?>
  </td>
</tr>
    
<tr>
    <td colspan="2" class='DataTD'>&nbsp;</td>
</tr>

<tr>
    <td colspan="2" align="center" >
        <?php if($resultado != ""):?>
        <table class='table' width="40%">
            <tr class='cabecera'><td colspan="2" align="center">Resultado de la carga</td></tr>
            <tr><td>Archivo:</td><td><?php echo $nombrearchivo?></td></tr>
            <tr><td>Correctos:</td><td><?php echo $o->cb_correctas?></td></tr>
            <tr>
                <td>Fallidos:</td>
                <td><?php echo $o->cb_fallidas?>
                <?php if($o->cb_fallidas > 0):?>
                    <a href="#" onclick="cmdVerAsignacionPlataforma_onclick(<?php echo $o->cb_codigo?>)">(ver detalle)</a>
                <?php endif;?>
                </td>
            </tr>
        </table>
        <?php endif;?>
    </td>
</tr>

<tr>
    <td colspan="2" class='DataTD'>&nbsp;</td>
</tr>

<tr align='center'>
       <td colspan="2">
         	<input name='cmdGuarda' id='cmdGuarda' type='button' value='Asignar' class='Button' style='width:70px' onclick='guardar()'/>
       	  <input name='cmdCerra'  id='cmdCerra'  type='button' value='Cancelar' class='Button' style='width:70px' onclick="self.location.href='../menu.php'" /> 
       </td>
</tr>
<!--
<tr>
  <td colspan=2>
    <table style='width:650px'  >
       <tr><td colspan=4 class=TITOpciones align='left'><font size="2">Empleados Seleccionados</font></td></tr>
       <tr class='cabecera'>
          <td>Nro</td>
          <td align="left">Empleado</td>
          <td align="left">Dni</td> 
          <td align="left">Area</td>
          
      </tr>
          <?php
              //$o->listarEmpleadosSeleccionados($_GET['ids']);
          ?>
      </table>
    </table>
  </td>
</tr>
-->
</table>

<br/>

<input type='hidden' name='hdempleados' id='hdempleados' value='<?php if(isset($_GET['ids'])) echo $_GET['ids']?>'/>
<input type='hidden' name='hplataformas' id='hplataformas' value=''/>

</form>
</body>
</html>