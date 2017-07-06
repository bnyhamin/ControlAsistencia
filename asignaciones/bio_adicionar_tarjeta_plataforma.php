<?php header("Expires: 0");

require_once("../includes/Seguridad.php");  
require('../../Includes/Connection.php');
require('../../Includes/Constantes.php');
//require('../../Includes/MyCombo.php');
require_once("../../Includes/mantenimiento.php");
require('../includes/bio_Plataforma.php');
require('../includes/bio_Tarjeta_Proximidad.php');
require_once("../includes/clsCA_Empleado_Rol.php");

$fecha_inicio_temp=date("d/m/Y",time());
$fecha_fin_temp=date("d/m/Y",time());
//$fecha_fin_temp=date("d/m/Y",time()+86400);

 
$o = new BIO_Plataforma(); 
$o->MyDBName= db_name();
$o->MyUrl = db_host();
$o->MyUser= db_user();
$o->MyPwd = db_pass();


$t = new bio_TarjetaProximidad();
$t->MyDBName= db_name();
$t->MyUrl = db_host();
$t->MyUser= db_user();
$t->MyPwd = db_pass();

$acceso = $_GET["acceso"];
$o->acceso = $acceso;


/*
$e = new ca_empleado_rol();
$e->setMyUrl(db_host());
$e->setMyUser(db_user());
$e->setMyPwd(db_pass());
$e->setMyDBName(db_name());
$e->empleado_codigo = $_SESSION["empleado_codigo"];
$arr_roles=$e->Devolver_roles();

//echo print_r($arr_roles);

/*

$acceso_permanente = false;

if( in_array(2, $arr_roles) || in_array(3, $arr_roles) || in_array(13, $arr_roles) ){ // jefe o administrador o Permisos Biometricos empleados y visitas


$acceso_permanente = true;

}*/

//$acceso_permanente = false; 




if (isset($_GET["hddaccion"])){
    if ($_GET["hddaccion"]=="asignar"){
        
         $arr_plataformas = explode(",",$_POST["hplataformas"]);
         $arr_tarjetas   = explode(",",$_POST["hdtarjetas"]);
          
         $t->asignarTarjetasPlataformas($arr_tarjetas, $arr_plataformas, $_POST['tipo_acceso'], $_POST['txtFechaInicio'], $_POST['txtFechaFin'], $_POST['observacion'], $_POST['permiso_activo'] );       
         
         exit(); 
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
  <title>Asignar Acceso de Tarjetas a Plataformas</title>
 
</head>
<script language='javascript'>

function guardar()
{
    var codigos='';
    var nodos=document.getElementsByTagName("input");
    var marcadoTipoAcceso = 't';
    
    for(var i=0;i<nodos.length;i++){
        if(nodos[i].type=="checkbox" && nodos[i].id.substring(0,3)=='chk'){
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
     
    /*for(i=0;i<document.frm.tipo_acceso.length;i++){
   
        if(document.frm.tipo_acceso[i].checked) {
            marcadoTipoAcceso=document.frm.tipo_acceso[i].value;
        }
    }*/
	 marcadoTipoAcceso = 't'; //añadido
	 
     if(marcadoTipoAcceso == 't'){
     
         if(validaFechaMayor()){
         
            document.frm.submit();
            self.close();
         }
     }else {
     
           document.frm.submit();
           self.close();
     }
  
}

function cancelar()
{
	   document.frm.hplataformas.value = '';
  	 self.close();
}
function checkear(){
   
if(document.frm.chk_todos.checked){
   checkear_todos_tarjetas(true);
}
else{
   checkear_todos_tarjetas(false);
  }
}

function checkear_todos_tarjetas(flag){
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

</script>  	
<body>
<center class=TITOpciones>Asignar Accesos de Tarjetas a Plataformas</center>
<form name='frm' id='frm' action='<?php echo $_SERVER['PHP_SELF']; ?>?hddaccion=asignar' method='post' >
<table  style='width:650px' align='left' border=0 >
<tr>
  <td align="left">
    <input type="checkbox" name="chk_todos" id= "chk_todos" onclick="checkear()"/><font size="0.5">Seleccionar Todos</font>
  </td>
</tr>
<tr>
  <td>
     <table class='table' style='width:300px'>
      <tr class='cabecera'>
          <td>&nbsp;</td>
          <td align="left">Plataforma</td>
      </tr>
          <?php
              $o->listarAccesos();
          ?>
      </table>
  </td>
  <td valign="top">
       <table class='table' style='width:300px' border=0>
        <tr class='cabecera'>
            <td colspan=2>Tipo de acceso:</td>
        </tr>    
        <!--<tr>    
            <td align="left">
            <?php 
                $checked_temporal = "checked";
                
                //if($acceso_permanente){

            ?>
            <input type='radio' name='tipo_acceso' value='p' onclick='mostrarOcultar("rangoFechas","show")'  />Permanente
             <?php
                //}

             ?>
            &nbsp;&nbsp;<input type='radio' name='tipo_acceso' value='t' onclick='mostrarOcultar("rangoFechas", "hide")' <?php echo $checked_temporal; ?>  />Temporal</td>   
        </tr>-->
        <tr>
          <td>
          <?php
              
             $muestra_rango_fechas = "" ;
             /*if($acceso_permanente) {
                $muestra_rango_fechas = "none";
             }*/
          
          ?>
            <table id ='rangoFechas' style ='display:<?php echo $muestra_rango_fechas;  ?>'>
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
       <tr><td class='DataTD' valign='top' >Observaci&oacute;n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea name='observacion' rows='4' cols='30'></textarea></td></tr>
       <tr><td class='DataTD'>Permiso activo&nbsp;&nbsp;<input type='checkbox' name='permiso_activo'  value='1' checked></td></tr>
     </table>
     <br>
     <table class='sinborde' cellspacing='0' cellpadding='0' border='0' align='left' style='width:150px'>
      <tr align='left'>
       <td>
         	<input name='cmdGuarda' id='cmdGuarda' type='button' value='Asignar' class='Button' style='width:70px' onclick='guardar()'/>
       	  <input name='cmdCerra'  id='cmdCerra'  type='button' value='Cancelar' class='Button' style='width:70px' onclick='cancelar()'/> 
       </td>
      </tr>
      </table>
  </td>
</tr>
<tr>
  <td colspan=2>
    <table style='width:650px'  >
       <tr><td colspan=4 class=TITOpciones align='left'><font size="2">Tarjetas Seleccionadas</font></td></tr>
       <tr class='cabecera'>
          <td>Nro</td>
          <td align="left">C&oacute;digo</td>
          <td align="left">TarjetaDNI</td> 
          <td align="left">TarjetaNombre</td>
      </tr>
          <?php
              $t->listarTarjetasSeleccionados($_GET['ids']);
          ?>
      </table>
    </table>
  </td>
</tr>
</table>

<br>

<input type='hidden' name='hdtarjetas' id='hdtarjetas' value='<?php echo $_GET['ids']?>'/>
<input type='hidden' name='hplataformas' id='hplataformas' value=''/>

</form>
</body>
</html>